<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model{

    function fetch_pending_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->where('tbl_1.hcare_provider', $hp_id)
                 ->where('tbl_1.status', 'Pending');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_approved_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Approved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_disapproved_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Disapproved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_closed_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Closed');
        $query = $this->db->get();
        return $query->result();
    }

    function get_cost_type($id){
        $this->db->select('*')
                 ->from('cost_types')
                 ->where('ctype_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function db_get_loa_info($loa_id) {
        $this->db->select('*')
                ->from('loa_requests as tbl_1')
                ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
                ->join('max_benefit_limits as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
                ->where('tbl_1.loa_id', $loa_id);
        return $this->db->get()->row_array();
    }

    function db_get_requesting_physician($doctor_id) {
        $query = $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id));
        return $query->row_array();
    }

    function db_get_cost_types() {
        $query = $this->db->get('cost_types');
        return $query->result_array();
    }

}
