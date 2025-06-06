<?php

class Sales_Invoices_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'sales_invoices';
        parent::__construct($this->table);
    }


    function get_details($options = array()){
    $id = get_array_value($options, "id");
    $start_date = get_array_value($options, "start_date");
    $end_date = get_array_value($options, "end_date");
    $where = "";
    
    if ($id) {
        $where = " AND id=$id";
    }
    
    if ($start_date) {
        $where .= " AND (inv_date >= '".$start_date."' AND inv_date <= '".$end_date."')";
    }
    
    $data = $this->db->query("SELECT * FROM $this->table 
                              WHERE deleted = 0 ".$where." 
                              ORDER BY ABS(DATEDIFF(inv_date, CURDATE())) ASC, id DESC");
    
    return $data;
}


    function get_detailss($options = array()){
        $id = get_array_value($options, "id");
        $start_date=get_array_value($options, "start_date");
        $end_date=get_array_value($options, "end_date");
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        if ($start_date) {
            $start_date = get_array_value($options, "start_date");
            $end_date = get_array_value($options, "end_date");  
            $where .= " AND (inv_date >='".$start_date."'AND  inv_date <='".$end_date."')";
        }

        $data = $this->db->query("SELECT * FROM $this->table WHERE  deleted = 0  ".$where." ORDER BY id DESC");
        return $data;

    }

    function get_invoices_total_summary($invoice_id = 0)
    {
        $invoice_items_table = $this->db->dbprefix('sales_invoices_items');
        $invoices_table = $this->db->dbprefix('sales_invoices');

        // Query untuk mendapatkan subtotal dari item
        $item_sql = "SELECT SUM($invoice_items_table.total) AS invoice_subtotal
                 FROM $invoice_items_table
                 LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.fid_invoices    
                 WHERE $invoice_items_table.deleted = 0 
                 AND $invoice_items_table.fid_invoices = $invoice_id 
                 AND $invoices_table.deleted = 0";
        $item = $this->db->query($item_sql)->row();

        // Query untuk mendapatkan informasi faktur
        $invoice_sql = "SELECT $invoices_table.* 
                    FROM $invoices_table
                    WHERE $invoices_table.deleted = 0 
                    AND $invoices_table.id = $invoice_id";

        $invoice = $this->db->query($invoice_sql)->row();

        $result = new stdClass();
        $result->invoice_subtotal = $item->invoice_subtotal;

        // Total faktur
        $result->invoice_total = $result->invoice_subtotal;
        $result->grand_total = $result->invoice_total;
        $result->balance_due = number_format($result->invoice_total, 2, ".", "");

        // Simbol mata uang
        $result->currency_symbol = get_setting("currency_symbol");
        $result->currency = get_setting("default_currency");

        return $result;
    }


    function get_invoices_value($invoice_id = 0){

        $query = $this->db->query("SELECT
                                            SUM( $invoice_items_table.total ) AS invoice_subtotal 
                                        FROM
                                            $invoice_items_table
                                            LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.fid_order 
                                        WHERE
                                            $invoice_items_table.deleted = 0 
                                            AND $invoice_items_table.fid_order = $invoice_id
                                            AND i$nvoices_table.deleted =0 ");

        return $query;
    }

     public function verifikasi($id){
        $query = $this->db->query("UPDATE sales_invoices SET is_verified='1', status = 'terverifikasi'  where id='$id' ");
        //return $query->result_array();
    }
    public function send($id){
        $query = $this->db->query("UPDATE sales_invoices SET  dikirim = 'Diterima', keterangan ='Diterima' where id='$id' ");
        //return $query->result_array();
    }
    function get_invoices($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        $data = $this->db->query("SELECT * FROM sales_invoices WHERE  deleted = 0  ".$where." ORDER BY id DESC");
        return $data;
    }

    function get_pembayaran($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND sales_invoices.id=$id";
        }
        $data = $this->db->query("SELECT sales_invoices.id,sales_invoices.code,sales_invoices.fid_cust,sales_invoices.fid_custt,sales_invoices.deleted,sales_payments.paid,sales_payments.pay_date,sales_invoices.vessel_id,sales_invoices.marketing,master_customers.name,master_customers.address FROM sales_invoices JOIN sales_payments ON sales_invoices.id=sales_payments.fid_inv JOIN master_customers ON sales_invoices.fid_cust=master_customers.id  WHERE  sales_invoices.deleted = 0  ".$where." ORDER BY sales_invoices.id DESC");
        return $data;
    }



}
