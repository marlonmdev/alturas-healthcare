<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_model extends CI_Model {


  public function get_autocomplete($search_data) {
    $this->db->select('emp_id, first_name, middle_name, last_name, suffix, approval_status')
             ->like('first_name', $search_data)
             ->or_like('middle_name', $search_data)
             ->or_like('last_name', $search_data)
             ->having('approval_status', 'Pending');
    return $this->db->get('members')->result_array();
  }

  public function db_get_member_details($emp_id) {
    $this->db->select('member_id, emp_id, first_name, middle_name, last_name, suffix, contactno, email, date_regularized')
             ->where('emp_id', $emp_id);
    return $this->db->get('members')->row_array();
  }
}
