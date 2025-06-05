<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class R_sales extends MY_Controller {

    function __construct() {
        parent::__construct();

     }


	public function index()
	{
		// Set default date range for the current year
		$start = date("Y") . "-01-01";
		$end = date("Y-m-d");
		$status = $_GET['status'];

		// Fetch and validate `start` and `end` parameters
		if (isset($_GET['start']) && isset($_GET['end'])) {
			$start = $_GET['start'];
			$end = $_GET['end'];
		} else {
			header("Location:" . base_url() . "reports/r_sales?start=" . $start . "&end=" . $end);
		}

		// Prepare view data
		$view_data['date_range'] = format_to_date($start) . " - " . format_to_date($end);
		$view_data['status'] = $status;

		// Construct the query to fetch data from sales_invoices and sales_invoices_items
		$this->db->select("
		tjh.code AS transaction_code,
		JSON_UNQUOTE(JSON_EXTRACT(tjh.data, '$.jenis_entry')) AS jenis_entry,
		CONCAT(coa.account_number, ' - ', coa.account_name) AS account,
		CONCAT(
			COALESCE(JSON_UNQUOTE(JSON_EXTRACT(tjh.data, '$.nama_tamu')), '-'),
			' / ',
			COALESCE(mtk.kelas_kamar, '-')
		) AS nama_tamu_kelas_kamar,
		CASE 
			WHEN tj.status_pembayaran = 0 THEN 'Belum Terverifikasi'
			WHEN tj.status_pembayaran = 1 THEN 'Terverifikasi'
			ELSE 'Tidak Diketahui'
		END AS status,
		tj.debet,
		tj.credit
		");
		$this->db->from("transaction_journal tj");
		$this->db->join("transaction_journal_header tjh", "tj.fid_header = tjh.id", "inner");
		$this->db->join("acc_coa_type coa", "tj.fid_coa = coa.id", "left");
		$this->db->join("master_tipe_kamar mtk", "JSON_UNQUOTE(JSON_EXTRACT(tjh.data, '$.tipe_kamar')) = mtk.id", "left");
		
		$this->db->where("tj.type", "jurnal_umum");
		$this->db->where("tj.date BETWEEN '$start' AND '$end'");
		$this->db->where("tj.deleted", 0);
		$this->db->where("tjh.deleted", 0);

		if ($status === "0" || $status === "1") {
			$this->db->where("tj.status_pembayaran", $status);
		}
		
		$this->db->group_by("tj.id");
		
		$view_data['sales_report'] = $this->db->get()->result();

		// $this->db->select("CONCAT(JSON_EXTRACT(data, '$.nama_tamu')) AS nama_tamu_kelas_kamar,");
		// $this->db->from("transaction_journal_header");
		// $result = $this->db->get()->result();
		// print_r($view_data);
		// exit;
		// Calculate the total tax in PHP
		// $view_data['total_tax'] = 0;
		// foreach ($view_data['sales_report'] as $report) {
		// 	$view_data['total_tax'] += $report->tax_amount;
		// }
		
		// Check if the report should be printed as PDF
		if (isset($_GET['print'])) {
			print_pdf("sales/sales_pdf", $view_data);
		} else {
			$this->template->rander("sales/sales_product", $view_data);
		}
	}

	// public function index()
	// {
	// 	// Set default date range for the current year
	// 	$start = date("Y") . "-01-01";
	// 	$end = date("Y-m-d");

	// 	// Fetch and validate `start` and `end` parameters
	// 	if (isset($_GET['start']) && isset($_GET['end'])) {
	// 		$start = $_GET['start'];
	// 		$end = $_GET['end'];
	// 	} else {
	// 		header("Location:" . base_url() . "reports/r_sales?start=" . $start . "&end=" . $end);
	// 	}

	// 	// Prepare view data
	// 	$view_data['date_range'] = format_to_date($start) . " - " . format_to_date($end);

	// 	// Construct the query to fetch data from sales_invoices and sales_invoices_items
	// 	$this->db->select("
    //     si.fid_cust, si.fid_custtt, si.fid_custttt, 
    //     mc1.name as customer_name1, 
    //     mc2.name as customer_name2, 
    //     mc3.name as customer_name3, 
    //     sii.title, 
    //     SUM(sii.total) as total,
    //     si.fid_tax as tax_name,
    //     (SUM(sii.total) * (taxes.value / 100)) as tax_amount
    // 	");
	// 	$this->db->from("sales_invoices si");
	// 	$this->db->join("sales_invoices_items sii", "si.id = sii.fid_invoices", "left");
	// 	$this->db->join("taxes", "si.fid_tax = taxes.id", "left");
	// 	// Join the master_customers table for each customer ID
	// 	$this->db->join("master_customers mc1", "si.fid_cust = mc1.id", "left");
	// 	$this->db->join("master_customers mc2", "si.fid_custtt = mc2.id", "left");
	// 	$this->db->join("master_customers mc3", "si.fid_custttt = mc3.id", "left");

	// 	// Add conditions to filter by date and ensure invoices are not deleted
	// 	$this->db->where("si.inv_date BETWEEN '$start' AND '$end'");
	// 	$this->db->where("si.deleted = 0"); // Filter to ensure the sales invoices are not deleted

	// 	// Group by product title
	// 	$this->db->group_by("sii.title");

	// 	// Execute the query and get results
	// 	$view_data['sales_report'] = $this->db->get()->result();

	// 	// Calculate the total tax in PHP
	// 	$view_data['total_tax'] = 0;
	// 	foreach ($view_data['sales_report'] as $report) {
	// 		$view_data['total_tax'] += $report->tax_amount;
	// 	}

	// 	// Check if the report should be printed as PDF
	// 	if (isset($_GET['print'])) {
	// 		print_pdf("sales/sales_pdf", $view_data);
	// 	} else {
	// 		$this->template->rander("sales/sales_product", $view_data);
	// 	}
	// }





}