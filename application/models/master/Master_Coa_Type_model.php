<?php

class Master_Coa_Type_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'acc_coa_type';
        parent::__construct($this->table);
    }


    function get_details($options = array()){
        $id = get_array_value($options, "id");
        $parent = get_array_value($options, "parent");
        $no_parent = get_array_value($options, "no_parent");
        $kas_bank = get_array_value($options, "kas_bank");
        
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        if ($parent) {
            $where = " AND parent='$parent'";
        }

        if ($no_parent) {
            $where = " AND parent=$no_parent";
        }
        if($kas_bank){
            $where = " AND id in ($kas_bank)";
        }
        $data = $this->db->query("SELECT * FROM $this->table WHERE  deleted = 0  ".$where." ORDER BY id ASC");
        return $data;
    }

    function get_details_row($options = array()){
        $id = get_array_value($options, "id");
        $parent = get_array_value($options, "parent");
        $no_parent = get_array_value($options, "no_parent");
        $kas_bank = get_array_value($options, "kas_bank");
        $account_name = get_array_value($options, "account_name");
        
        $where = "";
        if ($id) {
            $where = " AND id=$id";
        }
        if ($parent) {
            $where = " AND parent='$parent'";
        }

        if ($no_parent) {
            $where = " AND parent=$no_parent";
        }

        if($kas_bank){
            $where = " AND id in ($kas_bank)";
        }

        if ($account_name) {
            $where .= " AND account_name LIKE " . $this->db->escape('%' . $account_name . '%');
        }
        
        $data = $this->db->query("SELECT * FROM $this->table WHERE  deleted = 0  ".$where." ORDER BY id ASC")->row_array();
        // print_r($data);
        // exit;
        return $data;
    }

    // function getListCoa(){

    //     $parent = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0 ORDER BY account_number ASC");

    //     foreach($parent->result() as $row){

    //         $child = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0 AND parental = $row->id ");
    //         $data->row = $row;

    //     }

    //     return $data;
    // }

    function get_dropdown_kas() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent = 'Head' AND deleted = 0")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }

    function getKas() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE id in(2,3,4,5,6,7,8,9,10) AND deleted = 0")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }

    function getCoaKas($kas) {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT account_number FROM $this->table WHERE account_number LIKE '$kas%' AND parent is NULL AND deleted = 0");
         $result = array();
        foreach ($list_data->result() as $data) {
            // $text = "";
            $result[$data->account_number] = $data->account_number;
        }
        return $result;
    }

    function getCoaPenjualanDropdown($options = array()) {
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND mtkpt.master_tipe_kamar_id=$id";
        }
    
        $list_data = $this->db->query("
            SELECT 
                acc.*, 
                mtkpt.jumlah
            FROM 
                acc_coa_type acc
            LEFT JOIN 
                master_tipe_kamar_platform_tarif mtkpt
                ON acc.id = mtkpt.coa_type_id
            WHERE 
                acc.parental = (
                    SELECT id FROM acc_coa_type 
                    WHERE account_number = '4100' AND deleted = 0
                    LIMIT 1
                )
                AND acc.deleted = 0
                $where
            ORDER BY 
                acc.account_number
        ")->result();
    
        $dropdown = array(); // untuk form_dropdown
        $jumlah = array(); // untuk mapping jumlah
    
        foreach ($list_data as $data) {
            $dropdown[$data->id] = $data->account_number . " - " . $data->account_name;
            if (!is_null($data->jumlah)) {
                $jumlah[$data->id] = $data->jumlah;
            }
        }
    
        return [
            "dropdown" => $dropdown,
            "jumlah" => $jumlah,
        ];
    }    

    function getCoaDrop( $field = "",$like = "") {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $get = substr($like, 0,3);
        $where = "";
        if($field != ""){
            $where = " AND $field LIKE '$get%'";
        }
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent is NULL $where  AND deleted = 0")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }

    function getCoaExpenses() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent is NULL AND deleted = 0  order by account_number asc")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }

    function getCoaIncome() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent is NULL  AND deleted = 0  order by account_number asc")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }


    function getCOA() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent is NULL AND deleted = 0")->result();

        return $list_data;
    }

     function getAllCoa() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE  deleted = 0 ORDER BY account_number ASC")->result();

        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name ." - ".$data->parent;
        }
        return $result;
    }

    function getCoaEntry(){
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE parent is NULL AND deleted = 0 ORDER BY account_number ASC")->result();

        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;
    }


    function getCashCoa(){
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE account_number in('1206','1207','10002','10003','10004','10005','10006','10007','10008','10009','10010','10011') AND deleted = 0 ORDER BY account_number ASC")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->account_number." - ".$data->account_name;
        }
        return $result;

    }




}
