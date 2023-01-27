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
    return $this->db->get_where('members')->result_array();
  }

  function db_get_member_details($member_id) {
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }
}
