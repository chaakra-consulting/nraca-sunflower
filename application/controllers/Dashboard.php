<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->database();
        $this->load->library('template');
    }

    /**
     * Display the dashboard with purchase data
     */
    public function index()
    {
        // Get the year from GET parameter or default to current year
        $year = $this->input->get('search');
        if (empty($year) || !is_numeric($year)) {
            $year = date('Y');
        }

        // Prepare query for total purchases per month for the selected year
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(pi.inv_date, '%M') AS month_name,
                MONTH(pi.inv_date) AS month_number,
                YEAR(pi.inv_date) AS year,
                SUM(pii.total) AS total_per_month
            FROM 
                purchase_invoices pi
            JOIN 
                purchase_invoices_items pii ON pi.id = pii.fid_invoices
            WHERE 
                YEAR(pi.inv_date) = ?
                AND pi.paid = 'PAID'
                AND pi.code != '506 - Gaji'
                AND pi.deleted = 0
            GROUP BY 
                month_name, month_number, year
            ORDER BY 
                year, month_number
        ", array($year));

        // Prepare the data for the view
        $data = [];
        foreach ($query->result() as $row) {
            $data[] = [
                'month' => $row->month_name,
                'total' => $row->total_per_month
            ];
        }

        // Load the view with the data
        $this->template->rander("dashboard/index", ['data' => $data]);
    }

    public function index2()
    {
        // Get the year from GET parameter or default to current year
        $year = $this->input->get('search');
        if (empty($year) || !is_numeric($year)) {
            $year = date('Y');
        }

        // Prepare query for total purchases per month for the selected year
        $query = $this->db->query("
            SELECT 
                DATE_FORMAT(si.inv_date, '%M') AS month_name,
                MONTH(si.inv_date) AS month_number,
                YEAR(si.inv_date) AS year,
                SUM(sii.total) AS total_per_month
            FROM 
            sales_invoices si
            JOIN 
                sales_invoices_items sii ON si.id = sii.fid_invoices
            WHERE 
                YEAR(si.inv_date) = ?
                AND sii.deleted = 0
            GROUP BY 
                month_name, month_number, year
            ORDER BY 
                year, month_number
        ", array($year));

        // Prepare the data for the view
        $data = [];
        foreach ($query->result() as $row) {
            $data[] = [
                'month' => $row->month_name,
                'total' => $row->total_per_month
            ];
        }

        // Load the view with the data
        $this->template->rander("dashboard/index2", ['data' => $data]);
    }

    /**
     * Display the dashboard with sales data
     */
    public function index2_old()
    {
        // Get the selected customer ID and year from the query parameter
        $customer_id = $this->input->get('customer_id');
        $year = $this->input->get('year');

        // Default to the first customer if none is selected
        if (empty($customer_id)) {
            $customer_id = 1;  // Assuming 1 is a valid customer ID
        }

        // Default to current year if none is selected
        if (empty($year)) {
            $year = date('Y');
        }

        // Query for total sales grouped by year
        $this->db->select("YEAR(si.inv_date) as year, SUM(sii.total) as total_sales");
        $this->db->from("sales_invoices si");
        $this->db->join("sales_invoices_items sii", "si.id = sii.fid_invoices", "left");
        $this->db->where_in("YEAR(si.inv_date)", range(date('Y') - 4, date('Y'))); // Last 5 years
        $this->db->where("si.deleted", 0);
        $this->db->where("sii.deleted", 0);

        // If a customer is selected, filter by customer
        if (!empty($customer_id)) {
            $this->db->group_start(); // Start OR condition
            $this->db->where("si.fid_cust", $customer_id);
            $this->db->or_where("si.fid_custtt", $customer_id);
            $this->db->or_where("si.fid_custttt", $customer_id);
            $this->db->group_end(); // End OR condition
        }

        // Group by year to calculate total sales per year
        $this->db->group_by("YEAR(si.inv_date)");
        $this->db->order_by("year", "ASC");

        // Execute the query and store the result
        $sales_data = $this->db->get()->result();

        // Prepare chart data for visualization
        $chart_data = [];
        foreach ($sales_data as $row) {
            $chart_data[] = [
                'year' => $row->year,
                'total_sales' => $row->total_sales
            ];
        }

        // Fetch unique customers for the dropdown
        $this->db->select("id, name");
        $this->db->from("master_customers");
        $this->db->where("deleted", 0);
        $this->db->order_by("name", "ASC");
        $customers = $this->db->get()->result();

        // Fetch available years from sales_invoices
        $this->db->select("DISTINCT YEAR(inv_date) as year");
        $this->db->from("sales_invoices");
        $this->db->where("deleted", 0);
        $this->db->order_by("year", "DESC");
        $years = $this->db->get()->result();

        // Prepare the view data
        $view_data = [
            'sales_report' => $sales_data,
            'chart_data' => json_encode($chart_data),  // Data in JSON format for JS
            'customers' => $customers,  // List of customers for the dropdown
            'selected_customer' => $customer_id,  // Track selected customer
            'years' => $years,  // List of available years
            'selected_year' => $year  // Track selected year
        ];

        // Load the view with the data
        $this->template->rander("dashboard/index2", $view_data);
    }

    public function save_sticky_note()
    {
        $note_data = ['sticky_note' => $this->input->post("sticky_note")];
        $this->Users_model->save($note_data, $this->login_user->id);
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */
