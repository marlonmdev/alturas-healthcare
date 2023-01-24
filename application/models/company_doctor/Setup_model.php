<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_model extends CI_Model {

	function db_get_hospitals() {
		$query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Hospital'));
		return $query->result_array();
	}

	function db_get_laboratories() {
		$query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Laboratory'));
		return $query->result_array();
	}

	function db_get_clinics() {
		$query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Clinic'));
		return $query->result_array();
	}

	function db_insert_affiliate_hospital($post_data) {
		return $this->db->insert('affiliate_hospitals', $post_data);
	}

	function db_check_hospital_name($hospital_name) {
		$query = $this->db->get_where('affiliate_hospitals', array('hospital_name' => $hospital_name));
		return $query->num_rows() > 0 ? true : false;
	}

	function db_get_all_affiliate_hospitals() {
		$query = $this->db->get('affiliate_hospitals');
		return $query->result_array();
	}

	function db_get_all_cost_types() {
		$query = $this->db->get('cost_types');
		return $query->result_array();
	}

	function db_get_hospital_info($hospital_id) {
		$query = $this->db->get_where('affiliate_hospitals', array('hospital_id' => $hospital_id));
		return $query->row();
	}
}
