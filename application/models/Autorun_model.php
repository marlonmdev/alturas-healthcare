<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autorun_model extends CI_Model {

	function get_all_approved_loa() {
		$this->db->select('loa_id, status, expiration_date');
    $query = $this->db->get_where('loa_requests', ['status' => 'Approved']);
    return $query->result_array();
	}

	function get_member_approved_loa($emp_id) {
		$this->db->select('loa_id, status, expiration_date');
    $query = $this->db->get_where('loa_requests', ['emp_id' => $emp_id, 'status' => 'Approved']);
    return $query->result_array();
	}

	function update_loa_expired($loa_id) {
		$this->db->set('status', 'Expired')
						 ->where('loa_id', $loa_id);
		return $this->db->update('loa_requests');
	}

}
