<?php

class Journal_header_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'transaction_journal_header';
        parent::__construct($this->table);
    }


    function get_details($options = array()) {
        $id = get_array_value($options, "id");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $acc_filter = get_array_value($options, "acc_filter");
    
        $where = "";
        $join = "";
    
        if ($id) {
            $where .= " AND h.id = $id";
        }
    
        if (!empty($start) && !empty($end)) {
            $where .= " AND h.date BETWEEN '$start' AND '$end'";
        }
    
        if (!empty($acc_filter)) {
            $join .= " JOIN transaction_journal j ON h.id = j.fid_header";
            $where .= " AND j.fid_coa = '$acc_filter'";
        }

        $data = $this->db->query("SELECT h.* 
                                  FROM $this->table h 
                                  $join 
                                  WHERE h.type = 'jurnal_umum' 
                                  AND h.deleted = 0 
                                  $where");
        return $data;
    }

    function get_details_by_id($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        $data = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0 ".$where."  ");
        return $data;
    }

    function triggerDelete($id){

        $data = $this->db->query("UPDATE transaction_journal SET deleted = 1 WHERE fid_header = '$id'");

        return $data;
    }

}
