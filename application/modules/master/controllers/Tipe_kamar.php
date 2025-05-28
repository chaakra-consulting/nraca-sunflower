<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tipe_kamar extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        //check permission to access this module
    }

    /* load clients list view */

    function index()
    {

        $view_data['data'] = "";
        $this->template->rander("tipe_kamar/index", $view_data);
    }

    /* load client add/edit modal */

    function modal_form()
    {
        //get custom fields
        $this->db->select('nama');
        $coa_data = $this->Master_Coa_Type_model->getCoaPenjualanDropdown();
        $view_data['acc_dropdown'] = $coa_data['dropdown'];

        $this->load->view('tipe_kamar/modal_form', $view_data);
    }

    // function get_customer_suggestion()
    // {
    //     $key = $this->input->get('q');
    //     $suggestion = array();

    //     $items = $this->Master_Customers_model->get_suggestion($key);

    //     foreach ($items as $item) {
    //         $suggestion[] = array("id" => $item->name, "text" => $item->name);
    //     }

    //     $suggestion[] = array("id" => "+", "text" => "+ " . lang("create_new_customers"));

    //     echo json_encode($suggestion);
    // }

    // function get_info_suggestion()
    // {
    //     $item = $this->Master_Customers_model->get_info_suggestion($this->input->post("item_name"));
    //     if ($item) {
    //         echo json_encode(array("success" => true, "cust" => $item));
    //     } else {
    //         echo json_encode(array("success" => false));
    //     }
    // }
    // function getId($id)
    // {

    //     if (!empty($id)) {
    //         $options = array(
    //             "id" => $id,
    //         );
    //         $data = $this->Master_Tipe_Kamar_model->get_details($options)->row();

    //         echo json_encode(array("success" => true, "data" => $data));
    //     } else {
    //         echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
    //     }
    // }

    function modal_form_edit()
    {

        validate_submitted_data(array(
            "id" => "numeric"
        ));


        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Master_Tipe_Kamar_model->get_details($options);
        
        $coa_data_dropdown = $this->Master_Coa_Type_model->getCoaPenjualanDropdown();
        $view_data['acc_dropdown'] = $coa_data_dropdown['dropdown'];

        $coa_data_jumlah = $this->Master_Coa_Type_model->getCoaPenjualanDropdown($options);
        $view_data['jumlah'] = $coa_data_jumlah['jumlah'];

        $this->load->view('tipe_kamar/modal_form_edit', $view_data);
    }

    /* insert or update a client */

    function add_tipe_kamar()
    {
        validate_submitted_data(array(
            // "code" => "required",
            "tipe_kamar" => "required",
            "kelas_kamar" => "required",
        ));

        $data = array(
            "tipe_kamar" => $this->input->post('tipe_kamar'),
            "kelas_kamar" => $this->input->post('kelas_kamar'),
            "created_at" => get_current_utc_time(),
            "updated_at" => get_current_utc_time(),
            "deleted" => 0,
        );
        $save_tipe_kamar_id = $this->Master_Tipe_Kamar_model->save($data);

        if ($save_tipe_kamar_id){           // Ambil array dari inputan
            $coa_type_ids = $this->input->post('coa_type_id');
            $jumlahs = $this->input->post('jumlah');
    
            if (!empty($coa_type_ids) && is_array($coa_type_ids)) {
                foreach ($coa_type_ids as $index => $coa_type_id) {
                    $jumlah = isset($jumlahs[$index]) ? $jumlahs[$index] : null;
    
                    if ($coa_type_id && $jumlah) {
                        $platform_tarif_data = array(
                            "master_tipe_kamar_id" => $save_tipe_kamar_id,
                            "coa_type_id" => $coa_type_id,
                            "jumlah" => $jumlah,
                            "created_at" => get_current_utc_time(),
                            "updated_at" => get_current_utc_time(),
                        );
                        $this->Master_Tipe_Kamar_Platform_Tarif_model->save($platform_tarif_data);
                    }
                }
            }
    
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_tipe_kamar_id), 'id' => $save_tipe_kamar_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save()
    {
        $customers_id = $this->input->post('id');


        validate_submitted_data(array(
            "tipe_kamar" => "required",
            "kelas_kamar" => "required",
        ));

        $data = array(
            "tipe_kamar" => $this->input->post('tipe_kamar'),
            "kelas_kamar" => $this->input->post('kelas_kamar'),
            "updated_at" => get_current_utc_time(),
        );

        $save_tipe_kamar_id = $this->Master_Tipe_Kamar_model->save($data, $customers_id);

        if ($save_tipe_kamar_id) {
            $coa_type_ids = $this->input->post('coa_type_id');
            $jumlahs = $this->input->post('jumlah');
    
            if (!empty($coa_type_ids) && is_array($coa_type_ids)) {
                foreach ($coa_type_ids as $index => $coa_type_id) {
                    $jumlah = isset($jumlahs[$index]) ? $jumlahs[$index] : null;
    
                    if ($coa_type_id && $jumlah) {
                        $platform_tarif_data = array(
                            "master_tipe_kamar_id" => $save_tipe_kamar_id,
                            "coa_type_id" => $coa_type_id,
                            "jumlah" => $jumlah,
                            "updated_at" => get_current_utc_time(),
                        );
                        $this->Master_Tipe_Kamar_Platform_Tarif_model->save($platform_tarif_data);
                    }
                }
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_tipe_kamar_id), 'id' => $save_tipe_kamar_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }


    /* delete or undo a client */

    function delete()
    {

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Master_Tipe_Kamar_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Master_Tipe_Kamar_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of clients, prepared for datatable  */

    function list_data()
    {

        $list_data = $this->Master_Tipe_Kamar_model->get_details();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of client list  table */

    private function _row_data($id)
    {
        $options = array(
            "id" => $id
        );
        $data = $this->Master_Tipe_Kamar_model->get_details($options);
        return $this->_make_row($data);
    }

    /* prepare a row of client list table */

    private function _make_row($data)
    {
        $row_data = array(
            $data->tipe_kamar,
            $data->kelas_kamar,
            $data->account_names
        );  

        $row_data[] = modal_anchor(get_uri("master/tipe_kamar/view"), "<i class='fa fa-eye'></i>", array("class" => "view", "title" => lang('view'), "data-post-id" => $data->id)) 
            . modal_anchor(get_uri("master/tipe_kamar/modal_form_edit"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => "Edit Customers", "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => "Delete Customers", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("master/tipe_kamar/delete"), "data-action" => "delete"));

        return $row_data;
    }

    function view()
    {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric"
        ));


        $id = $this->input->post('id');
        $options = array(
            "id" => $id,
        );

        $view_data['model_info'] = $this->Master_Tipe_Kamar_model->get_details($options);

        $coa_data = $this->Master_Coa_Type_model->getCoaPenjualanDropdown($options);
        $view_data['acc_dropdown'] = $coa_data['dropdown'];
        $view_data['jumlah'] = $coa_data['jumlah'];

        $this->load->view('tipe_kamar/view', $view_data);
    }


}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */