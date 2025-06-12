<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_import extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Excel_model');
    }

    public function index() {
        $this->load->view('excel_form');
    }

    public function import()
    {
        $path = $_FILES['file']['tmp_name'];

        include APPPATH . 'third_party/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load($path);
        $sheet = $objPHPExcel->getSheetByName('Data Penjualan');

        if (!$sheet) {
            echo json_encode(["success" => false, "message" => "Sheet 'Data Penjualan' tidak ditemukan."]);
            return;
        }

        $sheetData = $sheet->toArray(null, true, true, true);

        $journalIdMap = $this->import_transaction_journal_header($sheetData);
        $this->import_transaction_journal($journalIdMap);

        echo json_encode(["success" => true, "message" => "Import berhasil!"]);
    }

    public function import_transaction_journal_header($sheetData)
    {
        $journalIdMap = [];

        foreach ($sheetData as $index => $row) {
            if ($index == 1) continue;

            if (empty($row['A'])) {
                break;
            }

            $journalCode = trim($row['B']);
            if (empty($journalCode)) continue;

            $dateDatangObject = DateTime::createFromFormat('m/d/Y', $row['H']);
            $tanggalDatang = $dateDatangObject ? $dateDatangObject->format('Y-m-d') : null;
            $datePergiObject = DateTime::createFromFormat('m/d/Y', $row['I']);
            $tanggalPergi = $datePergiObject ? $datePergiObject->format('Y-m-d') : null;

            $kolomC = $row['C'];
            $parts = explode('/', $kolomC);
            $noKamar = isset($parts[0]) ? trim($parts[0]) : null;
            $tipeKamar = isset($parts[1]) ? trim($parts[1]) : null;

            $kamar = null;
            if ($tipeKamar) {
                $options = array("tipe_kamar" => $tipeKamar);
                $kamarList = $this->Master_Tipe_Kamar_model->get_details($options);
                $kamar = !empty($kamarList) ? $kamarList[0] : null;
            }

            $voucherCode = getCodeId('transaction_journal_header', "VCU-");

            $jsonData = [
                'jenis_entry'     => "Penjualan Kamar",
                'tipe_kamar'      => $kamar ? $kamar->id : null,
                'nama_tamu'       => $row['D'],
                'no_kamar'  => $noKamar,
                'tanggal_datang'  => $tanggalDatang,
                'tanggal_pergi'   => $tanggalPergi,
            ];

            $header = [
                'code' => $journalCode,
                'voucher_code' => $voucherCode,
                'date' => $tanggalDatang,
                'type' => 'jurnal_umum',
                'status' => 1,
                'description' => $row['F'] ?? '',
                'data' => json_encode($jsonData)
            ];

            $this->db->where('code', $journalCode);
            $existing = $this->db->get('transaction_journal_header')->row();

            if ($existing) {
                $this->db->where('code', $journalCode);
                $this->db->update('transaction_journal_header', $header);
                $insertId = $existing->id;
            } else {
                $this->db->insert('transaction_journal_header', $header);
                $insertId = $this->db->insert_id();
            }

            $journalIdMap[$journalCode] = [
                'id' => $insertId,
                'voucher_code' => $voucherCode,
                'tanggal' => $tanggalDatang,
                'row' => $row
            ];
        }
        return $journalIdMap;
    }

    public function import_transaction_journal($journalIdMap)
    {
        $dataJournal = [];

        foreach ($journalIdMap as $journalCode => $data) {
            $row = $data['row'];

            if (empty($row['A'])) {
                break;
            }
            // print_r($row);
            // exit;
            $voucherCode = $data['voucher_code'];
            $dateObject = DateTime::createFromFormat('m/d/Y', $row['H']);
            $tanggalDatang = $dateObject ? $dateObject->format('Y-m-d') : null;
            $fidHeader = $data['id'];
    
            $options = ["account_name" => $row['F']];
            $coa = $this->Master_Coa_Type_model->get_details_row($options);
            // $coa = !empty($coaList) ? $coaList[0] : null;
            $rowG = $row['G'];
            $rowGBersih = preg_replace('/[^\d]/', '', $rowG);
            $debet = intval($rowGBersih);
            $debet = is_numeric($debet) ? (int)$debet : 0;

            $formattedL = preg_match('/^[a-zA-Z\s]+$/', $row['L']) ? ucfirst(strtolower($row['L'])) : null;

            $dataJournal[] = [
                "journal_code" => $journalCode,
                "voucher_code" => $voucherCode,
                "date" => $tanggalDatang,
                "type" => "jurnal_umum",
                "description" => "",
                "fid_coa" => $coa ? $coa['id'] : '',
                "fid_header" => $fidHeader,
                "debet" => $debet,
                // "debet" => unformat_currency($this->input->post("debet")),
                // "credit" => unformat_currency($this->input->post("credit")),
                // "status_pembayaran" => 0,
                "metode_pembayaran" => $formattedL,
                "username" => "admin",
                // "created_at" => get_current_utc_time()
            ];
        }
    
        if (!empty($dataJournal)) {
            $this->Excel_model->insert_batch_transaction_journal($dataJournal);
        }
    }
    

    // public function import() {
    //     $path = $_FILES['file']['tmp_name'];
    
    //     include APPPATH . 'third_party/PHPExcel/IOFactory.php';
    //     $objPHPExcel = PHPExcel_IOFactory::load($path);
    
    //     $sheet = $objPHPExcel->getSheetByName('Data Penjualan');
    
    //     if (!$sheet) {
    //         echo json_encode(array("success" => false, "message" => "Sheet 'Data Penjualan' tidak ditemukan."));
    //         return;
    //     }
    
    //     $sheetData = $sheet->toArray(null, true, true, true);
    
    //     $dataHeader = [];
    //     $dataJournal = [];
    
    //     foreach ($sheetData as $index => $row) {
    //         if ($index == 1) continue;
            
    //         $journalCode = trim($row['B']);
    //         if (empty($journalCode)) continue;

    //         $tanggalDatang = date('Y-m-d', strtotime(str_replace('/', '-', $row['H'])));
    //         $tanggalPergi = date('Y-m-d', strtotime(str_replace('/', '-', $row['I'])));

    //         $kolomC = $row['C'];
    //         $parts = explode('/', $kolomC);

    //         $noKamar = isset($parts[0]) ? trim($parts[0]) : null;
    //         $tipeKamar = isset($parts[1]) ? trim($parts[1]) : null;

    //         if($tipeKamar){
    //             $options = array(
    //                 "tipe_kamar" => $tipeKamar,
    //             );    
    //             $kamarList = $this->Master_Tipe_Kamar_model->get_details($options);
    //             $kamar = !empty($kamarList) ? $kamarList[0] : null;
    //         }
    //         else $kamar = null;

    //         $voucherCode = getCodeId('transaction_journal_header',"VCU-");

    //         $jsonData = [
    //             'jenis_entry'     => "Penjualan Kamar",
    //             'tipe_kamar'     => $kamar ? $kamar->id : null,
    //             'nama_tamu'     => $row['D'],
    //             'tanggal_datang' => $tanggalDatang,
    //             'tanggal_pergi' => $tanggalPergi,
    //         ];

    //         $dataHeader[] = [
    //             'journal_code' => $journalCode,
    //             'voucher_code' => $voucherCode,
    //             'date' => $tanggalDatang,
    //             'type' => 'jurnal_umum',
    //             "status" => 1,
    //             'description' => $row['F'] ?? '',
    //             'data' => json_encode($jsonData)
    //             // Tambahkan kolom lain jika diperlukan
    //             // 'nama'    => $row['A'],
    //             // 'telepon' => $row['C']
    //         ];

    //         $options = array(
    //             "account_name" => $row['F'],
    //         );    
    //         $coaList = $this->Master_Coa_Type_model->get_details($options);
    //         $coa = !empty($coaList) ? $coaList[0] : null;

    //         $dataJournal[] = [
    //             "journal_code" => $journalCode,
    //             "voucher_code" => $voucherCode,
    //             "date" => $tanggalDatang,
    //             "type" => "jurnal_umum",
    //             "description" => "",
    //             "fid_coa" => $coa,
    //             "fid_header" => $data_id,
    //             "debet" => unformat_currency($this->input->post("debet")),
    //             "credit" => unformat_currency($this->input->post("credit")),
    //             "status_pembayaran" => $this->input->post('status_pembayaran'),
    //             "metode_pembayaran" => $this->input->post('metode_pembayaran'),
    //             "username" => "admin",
    //             "created_at" => get_current_utc_time()
    //         ];
    //     }
    
    //     if (!empty($dataHeader)) {
    //         $this->Excel_model->insert_batch_transaction_journal_header($dataHeader);
    //         echo json_encode(array("success" => true, 'message' => "Import berhasil!"));
    //     } else {
    //         echo json_encode(array("success" => false, 'message' => "Tidak ada data yang valid untuk diimpor."));
    //     }
    // }    
}
