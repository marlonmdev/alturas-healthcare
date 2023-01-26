<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model{

    public function fetch_pending_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Pending');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_approved_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Approved');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_disapproved_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Disapproved');
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_closed_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Closed');
        $query = $this->db->get();
        return $query->result();
    }
}
