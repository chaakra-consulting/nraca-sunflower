<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class R_purchase extends MY_Controller {

    function __construct() {
        parent::__construct();

     }


    function index() {
        // Set default values for $start, $end, and $paid
        $start = isset($_GET['start']) ? $_GET['start'] : date("Y-m-01");
        $end = isset($_GET['end']) ? $_GET['end'] : date("Y-m-d");
        $paid = isset($_GET['paid']) ? $_GET['paid'] : 'paid';

        // Check if 'start' and 'end' parameters are set
        if (!isset($_GET['start']) || !isset($_GET['end'])) {
            // Redirect to reports page with default 'start' and 'end'
            header("Location:".base_url()."reports/r_purchase?start=".$start."&end=".$end);
            exit(); // Terminate script execution after redirection
        }

        // Format date range
        $date_range = format_to_date($start)." - ".format_to_date($end);

		$this->db
        ->select("
		tjh.code,
        CONCAT(
			COALESCE(coa.account_number, ''),
			' - ',
			COALESCE(coa.account_name, '')
		) AS account_number_name,
		tj.description,
		tj.debet,
		tj.credit
		");
		$this->db->from("transaction_journal tj");
		$this->db->join("transaction_journal_header tjh", "tj.fid_header = tjh.id", "inner");
        $this->db->join("acc_coa_type coa", "tj.fid_coa = coa.id");
		
		$this->db->where("tj.type", "pengeluaran");
		$this->db->where("tj.status_pembayaran", 1);
		$this->db->where("tj.date BETWEEN '$start' AND '$end'");
		$this->db->where("tj.deleted", 0);
		$this->db->where("tjh.deleted", 0);
		
		$this->db->group_by("tj.id");
		
        $purchase_report = $this->db->get()->result();
        // print_r($purchase_report);
        // exit;

        // Execute query using framework's database handler
        // $purchase_report = $this->db->query($sql);

        // Prepare view data
        $view_data = array(
            'date_range' => $date_range,
            'purchase_report' => $purchase_report
        );

        // Check if 'print' parameter is set
        if(isset($_GET['print'])) {
            print_pdf("purchase/purchase_pdf", $view_data);
        } else {
            $this->template->rander("purchase/purchase_product", $view_data);
        }
    }

    // function index() {
    //     // Set default values for $start, $end, and $paid
    //     $start = isset($_GET['start']) ? $_GET['start'] : date("Y-m-01");
    //     $end = isset($_GET['end']) ? $_GET['end'] : date("Y-m-d");
    //     $paid = isset($_GET['paid']) ? $_GET['paid'] : 'paid';

    //     // Check if 'start' and 'end' parameters are set
    //     if (!isset($_GET['start']) || !isset($_GET['end'])) {
    //         // Redirect to reports page with default 'start' and 'end'
    //         header("Location:".base_url()."reports/r_purchase?start=".$start."&end=".$end);
    //         exit(); // Terminate script execution after redirection
    //     }

    //     // Format date range
    //     $date_range = format_to_date($start)." - ".format_to_date($end);

    //     // Prepare SQL query using JOINs
    //     $sql = "SELECT purchase_invoices.*,
    //     SUM(purchase_invoices_items.total) AS total,
    //     SUM(purchase_invoices_items.quantity) AS qty
    //     FROM purchase_invoices
    //     JOIN purchase_invoices_items ON purchase_invoices.id = purchase_invoices_items.fid_invoices
    //     WHERE purchase_invoices.status = 'terverifikasi' 
    //     AND purchase_invoices.deleted = 0 
    //     AND purchase_invoices.inv_date BETWEEN '".$start."' AND '".$end."'
    //     AND purchase_invoices.paid = '".$paid."'";

    //     // if ($this->login_user->user_type != "manager") {
    //     $sql .= " AND purchase_invoices.code != '506 - Gaji'";
    //     // }

    //     $sql .= " GROUP BY purchase_invoices.id";


    //     // Execute query using framework's database handler
    //     $purchase_report = $this->db->query($sql);

    //     // Prepare view data
    //     $view_data = array(
    //         'date_range' => $date_range,
    //         'purchase_report' => $purchase_report
    //     );

    //     // Check if 'print' parameter is set
    //     if(isset($_GET['print'])) {
    //         print_pdf("purchase/purchase_pdf", $view_data);
    //     } else {
    //         $this->template->rander("purchase/purchase_product", $view_data);
    //     }
    // }

}