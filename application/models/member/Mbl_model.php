<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbl_model extends CI_Model {

	public function get_member_mbl($emp_id) {
		$this->db->where('emp_id', $emp_id);
		return $this->db->get('max_benefit_limits')->row_array();
	}
}
