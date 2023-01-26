<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hospital_model extends CI_Model {

	function get_hcare_provider($hp_id){
		$this->db->select('*')
							->from('healthcare_providers')
							->where('hp_id', $hp_id);
		$query = $this->db->get();
		return $query->row_array();
	}

}