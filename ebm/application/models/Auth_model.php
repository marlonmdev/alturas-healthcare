<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model {

	function get_user_info($username) {
		$this->db->where('username', $username);
		$query = $this->db->get('user_accounts');
		return $query->num_rows() > 0 ? $query->row_array() : false; //ternary operator
	}

	function get_users_count() {
		$this->db->where('user_role', 'super-admin');
		$query = $this->db->get('user_accounts');
		return $query->num_rows() === 0 ? true : false;
	}
	function set_read_tnc($username) {
		$this->db->set('read_tnc', true)
						 ->where('username', $username);
		return $this->db->update('user_accounts');
	}
	function insert_default_account($post_data) {
		$this->db->insert('user_accounts', $post_data);
	}

	function set_online($user_id) {
		$this->db->set('online', 1)
						 ->where('user_id', $user_id);
		return $this->db->update('user_accounts');
	}

	function set_offline($user_id) {
		$this->db->set('online', 0)
						 ->where('user_id', $user_id);
		return $this->db->update('user_accounts');
	}
}
