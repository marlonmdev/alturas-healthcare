<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    function get_member_by_healthcard($healthcard_no) {
        $query = $this->db->get_where('members', ['health_card_no' => $healthcard_no]);
        return $query->row_array();
    }
    function get_member_by_name($first_name, $last_name, $date_of_birth) {
        $query = $this->db->get_where('members', ['first_name' => $first_name, 'last_name' => $last_name, 'date_of_birth' => $date_of_birth]);
        return $query->row_array();
    }
    function get_member_mbl($emp_id) {
        $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
        return $query->row_array();
    } 
    function get_healthcare_provider($hcare_provider_id) {
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hcare_provider_id]);
        return $query->row();
    }
    function get_member_loa($emp_id, $hcare_provider_id) {
        $this->db->select('loa_id, loa_no, emp_id, request_date, hcare_provider, status')
                ->from('loa_requests')
                ->where('emp_id', $emp_id)
                ->where('hcare_provider', $hcare_provider_id)
                ->order_by('loa_id', 'DESC');
        return $this->db->get()->result_array();
    }
    function get_member_noa($emp_id, $hcare_provider_id) {
        $this->db->select('noa_id, noa_no, emp_id, request_date, status, hospital_id')
                ->from('noa_requests')
                ->where('emp_id', $emp_id)
                ->where('hospital_id', $hcare_provider_id)
                ->order_by('noa_id', 'DESC');
        return $this->db->get()->result_array();
    }

    
}