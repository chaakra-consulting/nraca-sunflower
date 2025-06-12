<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert batch untuk transaction_journal_header
     */
    public function insert_batch_transaction_journal_header($data)
    {
        if (!empty($data)) {
            $this->db->insert_batch('transaction_journal_header', $data);
        }
    }

    /**
     * Insert batch untuk transaction_journal
     */
    // public function insert_batch_transaction_journal($data)
    // {
    //     if (!empty($data)) {
    //         $this->db->insert_batch('transaction_journal', $data);
    //     }
    // }
    
    public function insert_batch_transaction_journal($data)
    {
        if (empty($data)) return;

        foreach ($data as $item) {
            // Cek apakah record sudah ada
            // $this->db->where('journal_code', $item['journal_code']);
            $this->db->where('fid_header', $item['fid_header']);
            $this->db->where('deleted', 0);
            $existing = $this->db->get('transaction_journal')->row();

            if ($existing) {
                // Update tanpa mengubah fid_header
                $updateData = $item;
                unset($updateData['fid_header']);
                // unset($updateData['created_at']);
                $this->db->where('id', $existing->id);
                $this->db->update('transaction_journal', $updateData);
            } else {
                // Insert baru
                // $item['created_at'] = date('Y-m-d H:i:s.u');
                $item['status_pembayaran'] = 0;
                $this->db->insert('transaction_journal', $item);
            }
        }
    }

    /**
     * Ambil ID berdasarkan journal_code
     */
    public function get_journal_header_by_code($journalCode)
    {
        return $this->db->get_where('transaction_journal_header', ['journal_code' => $journalCode])->row();
    }
}
