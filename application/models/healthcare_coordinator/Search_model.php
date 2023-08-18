<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_model extends CI_Model {

  function get_autocomplete($search_data) {
    $this->db->select('tbl_1.member_id, tbl_1.emp_id, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix')
    ->from('members as tbl_1')
    ->join('max_benefit_limits as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
    ->where_in('tbl_1.approval_status', array('Approved', 'Done'))
    ->where('tbl_2.remaining_balance ', 0)
    ->group_start()
        ->like('tbl_1.first_name', $search_data)
        ->or_like('tbl_1.middle_name', $search_data)
        ->or_like('tbl_1.last_name', $search_data)
        ->or_like('tbl_1.suffix', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.last_name)', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name)', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name, " ", tbl_1.suffix)', $search_data)
    ->group_end();
  return $this->db->get()->result_array();
}
function get_autocomplete_setup($search_data) {
  $this->db->select('tbl_1.member_id, tbl_1.emp_id, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix')
  ->from('members as tbl_1')
  ->where_in('tbl_1.approval_status', array('Approved', 'Done'))
  ->group_start()
      ->like('tbl_1.first_name', $search_data)
      ->or_like('tbl_1.middle_name', $search_data)
      ->or_like('tbl_1.last_name', $search_data)
      ->or_like('tbl_1.suffix', $search_data)
      ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.last_name)', $search_data)
      ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name)', $search_data)
      ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name, " ", tbl_1.suffix)', $search_data)
  ->group_end();
return $this->db->get()->result_array();
}
  function get_autocomplete_affiliated($search_data) {
    $this->db->select('tbl_1.member_id, tbl_1.emp_id, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix')
    ->from('members as tbl_1')
    ->join('max_benefit_limits as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
    ->where_in('tbl_1.approval_status', array('Approved', 'Done'))
    ->where('tbl_2.remaining_balance >', 0)
    ->group_start()
        ->like('tbl_1.first_name', $search_data)
        ->or_like('tbl_1.middle_name', $search_data)
        ->or_like('tbl_1.last_name', $search_data)
        ->or_like('tbl_1.suffix', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.last_name)', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name)', $search_data)
        ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name, " ", tbl_1.suffix)', $search_data)
    ->group_end();
  return $this->db->get()->result_array();
}

  public function db_get_member_details($member_id) {
    $this->db->select('members.*, max_benefit_limits.remaining_balance')
             ->join('max_benefit_limits', 'members.emp_id = max_benefit_limits.emp_id', 'left')
             ->where('members.member_id', $member_id);
    $query = $this->db->get('members');
    return $query->row_array();
  }
  public function db_get_member_details1($emp_id) {
    $this->db->select('members.*, max_benefit_limits.remaining_balance')
             ->join('max_benefit_limits', 'members.emp_id = max_benefit_limits.emp_id', 'left')
             ->where('members.emp_id', $emp_id);
    $query = $this->db->get('members');
    return $query->row_array();
  }

  function db_get_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->result_array();
  }


}
