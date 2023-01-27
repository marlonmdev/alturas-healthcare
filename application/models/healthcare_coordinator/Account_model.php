<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model {

  function get_user_account_details($user_id) {
    $query = $this->db->get_where('user_accounts', ['user_id =' => $user_id]);
    return $query->row_array();
  }

  function db_update_user_account($user_id, $post_data) {
    $this->db->where('user_id', $user_id);
    return $this->db->update('user_accounts', $post_data);
  }

  function db_check_username($new_username) {
    $query = $this->db->get_where('user_accounts', ['username' => $new_username]);
    return $query->num_rows() > 0 ? true : false;
  }

  function db_get_user_details($emp_id) {
    $query = $this->db->get_where('members', ['emp_id =' => $emp_id]);
    return $query->row_array();
  }
}
