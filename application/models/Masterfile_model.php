<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masterfile_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function insert_batch($data) {
		$this->db->insert_batch('members', $data);
		return $this->db->affected_rows() > 0 ? 1 : 0;
	}

	function members_list() {
		$query = $this->db->get('members');
		return $query->result();
	}
}
