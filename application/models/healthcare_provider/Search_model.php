<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_model extends CI_Model {

  function get_autocomplete($search_data) {
    $this->db->select('member_id, emp_id, first_name, middle_name, last_name, suffix, approval_status')
             ->like('first_name', $search_data)
             ->or_like('middle_name', $search_data)
             ->or_like('last_name', $search_data)
             ->or_like('suffix', $search_data)
             ->having('approval_status', 'Approved');
    return $this->db->get('members')->result_array();
  }

  function db_get_member_details($member_id) {
    $this->db->select('*')
             ->from('members')
             ->where('member_id', $member_id);
    $query = $this->db->get();
    return $query->row_array();
  }

  function db_get_member_by_healthcard($healthcard_no){
    $this->db->select('*')
             ->from('members')
             ->join('max_benefit_limits as mbl', 'members.emp_id = mbl.emp_id')
             ->where('health_card_no', $healthcard_no);
    $query = $this->db->get();
    return $query->row_array();
  }

  function db_get_loa_noa_history($emp_id,$hp_id) {
    $this->db->select('*')
             ->from('members as tbl_1')
             ->join('loa_requests as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id','left')
             ->join('noa_requests as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id','left')
             ->where('tbl_1.emp_id', $emp_id)
             ->where('tbl_2.hcare_provider', $hp_id)
             ->where('tbl_3.hospital_id', $hp_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function db_get_member_by_name($first_name, $last_name, $date_of_birth){
    $this->db->select('*')
             ->from('members')
             ->join('max_benefit_limits as mbl', 'members.emp_id = mbl.emp_id')
             ->where('first_name', $first_name)
             ->where('last_name', $last_name)
             ->where('date_of_birth', $date_of_birth);
    $query = $this->db->get();
    return $query->row_array();
  }

}
