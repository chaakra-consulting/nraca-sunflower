<?php

class Master_Aruskas_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'aruskas';
        parent::__construct($this->table);
    }

    function get_details($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        $data = $this->db->query("SELECT * FROM aruskas WHERE  deleted = 0  ".$where." ORDER BY id DESC");
        return $data;
    }
    
    function get_detailss($kas = array()){
        $id = get_array_value($kas, "id");
        $start_date=get_array_value($kas, "start_date");
        $end_date=get_array_value($kas, "end_date");
           $where = "";
           if ($id) {
               $where = " AND id=$id";
           }
           if ($start_date) {
               $start_date = get_array_value($kas, "start_date");
               $end_date = get_array_value($kas, "end_date");  
               $where .= " AND (tgl >='".$start_date."'AND  tgl <='".$end_date."')";
           }
           $data = $this->db->query("SELECT * FROM aruskas WHERE  deleted = 0  ".$where." ORDER BY id DESC");
           return $data;
       }
    
    function get_suggestion($keyword = "") {
        // $items_table = $this->db->dbprefix('master_items');
        

        $sql = "SELECT *
        FROM $this->table
        WHERE deleted = 0  AND name LIKE '%$keyword%' ";
        return $this->db->query($sql)->result();
    }

    function get_info_suggestion($item_name = "") {
        // $estimate_items_table = $this->db->dbprefix('estimate_items');
        $table_kas = $this->table;

        $sql = "SELECT *
        FROM $table_kas
        WHERE $table_kas.deleted=0 AND $table_kas.name = '$item_name'
        ORDER BY id DESC LIMIT 1";
        $result = $this->db->query($sql);

        if ($result->num_rows()) {
            return $result->row();
        }
    }
}
