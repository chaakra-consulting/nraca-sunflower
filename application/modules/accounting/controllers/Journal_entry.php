<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Journal_entry extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("accounting/Journal_model");
        $this->load->model("accounting/Journal_header_model");
        //check permission to access this module
    }

    /* load clients list view */

    function index() {
    	$this->template->rander('journal_entry/index');
    }



    
    function modal_form() {
        //get custom fields

        // $view_data['model_info'] = $this->Journal_model->get_one($this->input->post('id'));
        // $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['kas_dropdown'] = $this->Master_Coa_Type_model->getCashCoa();
        $view_data['tipe_kamar_dropdown'] = $this->Master_Tipe_Kamar_model->getTipeKamarDropdown();
        
        $this->load->view('journal_entry/modal_form',$view_data);
    }

    function modal_form_edit() {

        validate_submitted_data(array(
            "id" => "numeric"
        ));


        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );
        $view_data['kas_dropdown'] = $this->Master_Coa_Type_model->getCashCoa();

        $view_data['model_info'] = $this->Journal_header_model->get_details($options)->row();
        $view_data['tipe_kamar_dropdown'] = $this->Master_Tipe_Kamar_model->getTipeKamarDropdown();
         // $view_data['clients_dropdown'] = array("" => "-") + $this->Master_Customers_model->get_dropdown_list(array("name"));
         $view_data['data'] = json_decode($view_data['model_info']->data, true);
            
         $view_data['info_coa'] = $this->Master_Coa_Type_model->get_details(array("id"=> $view_data['info_header']->fid_coa))->row();
         $view_data['tipe_kamar'] = $this->Master_Tipe_Kamar_model->get_details(array("id"=> $tipe_kamar['tipe_kamar']));
        //  print_r($view_data['data']['tipe_kamar']);
        //  exit;

        $this->load->view('journal_entry/modal_form_edit', $view_data);
    }

    function modal_form_detail() {
            validate_submitted_data(array(
                "id" => "numeric"
            ));
            $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['info_header'] = $this->Journal_header_model->get_details($options)->row();
        $view_data['data'] = json_decode($view_data['info_header']->data, true);

        $view_data['acc_dropdown'] = $this->Master_Coa_Type_model->getCoaEntry();

        $this->load->view('journal_entry/modal_form_detail',$view_data);
    }

    function modal_form_detail_edit() {
            validate_submitted_data(array(
                "id" => "numeric"
            ));
            $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );
        $view_data['model_info'] = $this->Journal_model->get_details(array('id'=>$id))->row();

        $view_data['info_header'] = $this->Journal_header_model->get_details(array('id' => $view_data['model_info']->fid_header))->row();
        $view_data['data'] = json_decode($view_data['info_header']->data, true);
        // print_r($view_data['data']);
        // exit;
        $view_data['acc_dropdown'] = $this->Master_Coa_Type_model->getCoaEntry();

        $this->load->view('journal_entry/modal_form_detail_edit',$view_data);
    }

    /* insert or update a client */

    function add() {
        // validate_submitted_data(array(
        //     "code" => "required",

        // ));

        $fid_coa = $this->input->post('fid_coa');

        $json_data = array(
            "jenis_entry" => $this->input->post('jenis_entry'),
            "tipe_kamar" => $this->input->post('tipe_kamar'),
            "no_kamar" => $this->input->post('no_kamar'),
            "nama_tamu" => $this->input->post('nama_tamu'),
            "tanggal_datang" => $this->input->post('tanggal_datang'),
            "tanggal_pergi" => $this->input->post('tanggal_pergi'),
        );

        $json_data_encoded = json_encode($json_data);
        
        $data = array(
            "code" => $this->input->post('code'),
            "voucher_code" => $this->input->post('voucher_code'),
            // "fid_coa" => $this->input->post('fid_coa'),
            "date" => $this->input->post('date'),
            "description" => $this->input->post('description'),
            "status" => 1,
            "type" => "jurnal_umum",
            // "status_pembayaran" => $this->input->post('status_pembayaran'),
            "data" => $json_data_encoded 
        );

        $save_id = $this->Journal_header_model->save($data);
        
        if ($save_id) {            
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred'), 'print' => print_r($save_id)));
        }
    }

    function add_detail() {
        validate_submitted_data(array(
            "fid_coa" => "required",

        ));
        $data_id = $this->input->post('id');

        $fid_coa = $this->input->post('fid_coa_header');

        $data = array(
            "journal_code" => $this->input->post('journal_code'),
            "voucher_code" => $this->input->post('voucher_code'),
            "date" => $this->input->post('date'),
            "type" => "jurnal_umum",
            "description" => $this->input->post('description'),
            "fid_coa" => $this->input->post('fid_coa'),
            "fid_header" => $data_id,
            "debet" => unformat_currency($this->input->post("debet")),
            "credit" => unformat_currency($this->input->post("credit")),
            "status_pembayaran" => $this->input->post('status_pembayaran'),
            "metode_pembayaran" => $this->input->post('metode_pembayaran'),
            "username" => "admin",
            "created_at" => get_current_utc_time()
        );


        

        $save_id = $this->Journal_model->save($data);
        if ($save_id) {
            // $data = $this->db->query("SELECT SUM(debet) AS debet,fid_header FROM transaction_journal WHERE fid_header = $data_id AND deleted = 0 ")->row();
            // $this->db->query("UPDATE transaction_journal SET credit = $data->debet WHERE fid_header = $data->fid_header AND fid_coa = '$fid_coa' ");
            
            echo json_encode(array("success" => true, "data" => $this->_row_data_entry($save_id), 'id' => $save_id ,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred'), 'print' => print_r($save_id)));
        }
    }

    function save_detail() {
        validate_submitted_data(array(
            "fid_coa" => "required",

        ));
        $data_id = $this->input->post('id');

        $fid_header = $this->input->post('fid_header');
        $fid_coa = $this->input->post('fid_coa_header');
        $data = array(
            "description" => $this->input->post('description'),
            "fid_coa" => $this->input->post('fid_coa'),
            "status_pembayaran" => $this->input->post('status_pembayaran'),
            "metode_pembayaran" => $this->input->post('metode_pembayaran'),
            "debet" => unformat_currency($this->input->post("debet")),
            "credit" => unformat_currency($this->input->post("credit")),      
        );


        

        $save_id = $this->Journal_model->save($data,$data_id);
        if ($save_id) {
            
            echo json_encode(array("success" => true, "data" => $this->_row_data_entry($save_id), 'id' => $save_id,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred'), 'print' => print_r($save_id)));
        }
    }

     function _triggerUpdate($fid_header,$fid_coa){
        $data = $this->db->query("SELECT SUM(a.debet) AS debet,a.fid_header,b.* FROM transaction_journal a JOIN transaction_journal_header b ON a.fid_header = b.id  WHERE a.fid_header = $fid_header AND a.deleted = 0 AND a.type = 'pengeluaran' ")->row();
        $query = $this->db->query("UPDATE transaction_journal SET credit = $data->debet WHERE fid_header = $data->fid_header AND fid_coa = $fid_coa ");
        if($query == true){
            return true;
        }else{
            return false;
        }
    }


    function save() {
        $data_id = $this->input->post('id');


        validate_submitted_data(array(
            "id" => 'numeric'
            // "journal_code" => "required"
        ));

        $fid_coa = $this->input->post('fid_coa');

        if($this->input->post('jenis_entry') == "Penjualan Kamar"){
            $json_data = array(
                "jenis_entry" => $this->input->post('jenis_entry'),
                "tipe_kamar" => $this->input->post('tipe_kamar'),
                "no_kamar" => $this->input->post('no_kamar'),
                "nama_tamu" => $this->input->post('nama_tamu'),
                "tanggal_datang" => $this->input->post('tanggal_datang'),
                "tanggal_pergi" => $this->input->post('tanggal_pergi'),
            );
        }else{
            $json_data = array(
                "jenis_entry" => $this->input->post('jenis_entry'),
            );
        }
        
        $json_data_encoded = json_encode($json_data);

        $data = array(
            "code" => $this->input->post('code'),
            "voucher_code" => $this->input->post('voucher_code'),
            // "fid_coa" => $this->input->post('fid_coa'),
            "date" => $this->input->post('date'),
            "description" => $this->input->post('description'),
            // "status_pembayaran" => $this->input->post('status_pembayaran'),
            "data" => $json_data_encoded,
        );


        $save_id = $this->Journal_header_model->save($data,$data_id);
        if ($save_id) {

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $data_id,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* delete or undo a client */

    function delete() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Journal_header_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Journal_header_model->delete($id)) {
                $this->Journal_header_model->triggerDelete($id);
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

     function delete_detail() {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Journal_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data_entry($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Journal_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of clients, prepared for datatable  */

    function list_data() {

        $list_data = $this->Journal_header_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of client list  table */

    private function _row_data($id) {
        $options = array(
            "id" => $id
        );
        $data = $this->Journal_header_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    /* prepare a row of client list table */

    private function _make_row($data) {
        $originalDate = $data->date;
        $newDate = date("d-M-Y", strtotime($originalDate));
    
        // Determine status label based on status_pembayaran
        // $status_label = $data->status_pembayaran == 1 
        //     ? '<span class="label label-success">Terverifikasi</span>' 
        //     : '<span class="label label-danger">Belum Terverifikasi</span>';
    
        $code = $data->code ? anchor(get_uri('accounting/journal_entry/entry/').$data->id.'/'.$data->fid_coa, $data->code) : '-';
        $row_data = array(
            $code,
            $data->voucher_code,
            $newDate,
            $data->description,
            // $status_label // Add status column after description
        );
    
        $row_data[] = anchor(get_uri("accounting/journal_entry/entry/").$data->id.'/'.$data->fid_coa, "<i class='fa fa-plus'></i>", array("class" => "edit", "title" => "Add Entry", "data-post-id" => $data->id))
            . modal_anchor(get_uri("accounting/journal_entry/modal_form_edit"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_client'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_client'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("accounting/journal_entry/delete"), "data-action" => "delete"));
    
        return $row_data;
    }

    // private function _make_row($data) {
    //     // $options = array(
    //     //     "id" => $data->id
    //     // );
        
    //     // $query = $this->Master_Customers_model->get_details($options)->row();
    //     $originalDate = $data->date;
    //     $newDate = date("d-M-Y", strtotime($originalDate));
    //     $row_data = array(
    //     	anchor(get_uri('accounting/journal_entry/entry/').$data->id.'/'.$data->fid_coa, $data->code),
    //         $data->voucher_code,
    //         $newDate,
    //         $data->description// $status
    //     );

    //     	$row_data[] = anchor(get_uri("accounting/journal_entry/entry/").$data->id.'/'.$data->fid_coa, "<i class='fa fa-plus'></i>", array("class" => "edit", "title" => "Add Entry", "data-post-id" => $data->id)).modal_anchor(get_uri("accounting/journal_entry/modal_form_edit"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_client'), "data-post-id" => $data->id))
    //             . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_client'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("accounting/journal_entry/delete"), "data-action" => "delete"));
        
    //     return $row_data;
    // }


     function list_data_entry($id) {

        $list_data = $this->Journal_model->get_details(array('fid_header' => $id))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_entry($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of client list  table */

    private function _row_data_entry($id) {
        $options = array(
            "id" => $id
        );
        $data = $this->Journal_model->get_details($options)->row();
        return $this->_make_row_entry($data);
    }

    /* prepare a row of client list table */

    private function _make_row_entry($data) {
        // $options = array(
        //     "id" => $data->id
        // );
        // $query = $this->Master_Customers_model->get_details($options)->row();
        $value = $this->Master_Coa_Type_model->get_details(array("id"=> $data->fid_coa))->row();
        $status_label = $data->status_pembayaran == 1 
            ? '<span class="label label-success">Terverifikasi</span>' 
            : '<span class="label label-danger">Belum Terverifikasi</span>';
        $row_data = array(
            $value->account_name,
            $value->account_number,
            $data->description,
            number_format($data->debet),
            number_format($data->credit),
            $data->metode_pembayaran ?? '-',
            $status_label 
        );
            // if(!in_array($value->account_number, $kas)){
            $row_data[] = modal_anchor(get_uri("accounting/journal_entry/modal_form_detail_edit"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => "Edit Entry", "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_client'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("accounting/journal_entry/delete_detail"), "data-action" => "delete"));
            // }else{
            //     $row_data[] = "";
            // }
        return $row_data;
    }


    public function entry($id = 0){
        if($id){
            // echo $id;
            
            $view_data['info_header'] = $this->Journal_header_model->get_details(array("id" => $id))->row();
            $view_data['data'] = json_decode($view_data['info_header']->data, true);
            
            $view_data['info_coa'] = $this->Master_Coa_Type_model->get_details(array("id"=> $view_data['info_header']->fid_coa))->row();
            $view_data['kas_dropdown'] = $this->Master_Coa_Type_model->getCashCoa();
            $view_data['tipe_kamar'] = $this->Master_Tipe_Kamar_model->get_details(array("id"=> $view_data['data']['tipe_kamar']));

            $this->template->rander("journal_entry/entry", $view_data);
  
        } else{
            show_404();
        }

    }

}