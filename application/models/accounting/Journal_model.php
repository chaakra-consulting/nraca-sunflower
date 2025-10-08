<?php

class Journal_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'transaction_journal';
        parent::__construct($this->table);
    }


    function get_details($options = array()){
        $id = get_array_value($options, "id");
        $fid_header = get_array_value($options, "fid_header");
        $kamar_filter = get_array_value($options, "kamar_filter");

        $where = "";
        $join = "";
        
        if ($id) {
            $where = " AND h.id=$id";
        }
        if ($fid_header) {
            $where = " AND h.fid_header=$fid_header";
        }
        if ($kamar_filter) {
            $join .= "JOIN acc_coa_type act ON act.id = h.fid_coa";
            $where .= " AND act.account_number LIKE '41%'";
        }
        $data = $this->db->query("SELECT * 
                                FROM $this->table h 
                                $join 
                                WHERE h.type = 'jurnal_umum' 
                                AND  h.deleted = 0  "
                                .$where." 
                                ORDER BY h.id DESC");
        return $data;
    }


     function get_details_by_date($options = array()){
        $start_date = get_array_value($options, "start_date");
        $end_date = get_array_value($options, "end_date");
        
            $where = " AND (date >='".$start_date."'AND  date <='".$end_date."')";

        $data = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0  ".$where." ORDER BY id DESC");
        return $data;
    }
    function get_details_by_id($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        $data = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0   $where  ".$where." ORDER BY id DESC");
        return $data;
    }

    public function verifikasi($id){
        $query = $this->db->query("UPDATE $this->table SET status_pembayaran='1' where id='$id' ");
        //return $query->result_array();
    }
}
