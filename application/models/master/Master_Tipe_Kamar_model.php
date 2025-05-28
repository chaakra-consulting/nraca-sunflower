<?php

class Master_Tipe_Kamar_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'master_tipe_kamar';
        parent::__construct($this->table);
    }

    function get_details($options = array()){
        $id = get_array_value($options, "id");
        $where = "";
        if ($id) {
            $where = " AND mtk.id=$id";
        }
    
        $sql = "SELECT 
                    mtk.*,
                    GROUP_CONCAT(DISTINCT acc.account_name ORDER BY acc.account_name SEPARATOR ', ') AS account_names
                FROM 
                    master_tipe_kamar AS mtk
                LEFT JOIN 
                    master_tipe_kamar_platform_tarif AS tarif ON tarif.master_tipe_kamar_id = mtk.id
                LEFT JOIN 
                    acc_coa_type AS acc ON acc.id = tarif.coa_type_id
                WHERE 
                    mtk.deleted = 0
                    $where
                GROUP BY 
                    mtk.id
                ORDER BY 
                    mtk.id DESC";
    
        $tipe_kamar = $this->db->query($sql)->result();

        // Loop tiap tipe kamar untuk ambil data acc_coa_type-nya
        foreach ($tipe_kamar as &$row) {
            $row->coa_types = $this->db->query("
                SELECT acc.*
                FROM master_tipe_kamar_platform_tarif AS tarif
                LEFT JOIN acc_coa_type AS acc ON acc.id = tarif.coa_type_id
                WHERE tarif.master_tipe_kamar_id = {$row->id}
            ")->result();
        }

        return $tipe_kamar;
    }

    function getTipeKamarDropdown() {
        // $where["deleted"] = 0;
        // $where["id"] = (2,3);
        // $get = substr($like, 0,3);
        // $where = "";
        // if($field != ""){
        //     $where = " AND $field LIKE '$get%'";
        // }
        $list_data = $this->db->query("SELECT * FROM $this->table WHERE deleted = 0")->result();
        $result = array();
        foreach ($list_data as $data) {
            // $text = "";
            $result[$data->id] = $data->tipe_kamar." - ".$data->kelas_kamar;
        }

        return $result;
    }

    // function get_suggestion($keyword = "") {
    //     // $items_table = $this->db->dbprefix('master_items');
        

    //     $sql = "SELECT *
    //     FROM $this->table
    //     WHERE deleted = 0  AND name LIKE '%$keyword%' ";
    //     return $this->db->query($sql)->result();
    // }

    // function get_info_suggestion($item_name = "") {
    //     // $estimate_items_table = $this->db->dbprefix('estimate_items');
    //     $table_cust = $this->table;

    //     $sql = "SELECT *
    //     FROM $table_cust
    //     WHERE $table_cust.deleted=0 AND $table_cust.name = '$item_name'
    //     ORDER BY id DESC LIMIT 1";
    //     $result = $this->db->query($sql);

    //     if ($result->num_rows()) {
    //         return $result->row();
    //     }
    // }

}
