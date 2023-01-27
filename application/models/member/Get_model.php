<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Get_model extends CI_Model {

	public function db_get_hospitals() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Hospital']);
		return $query->result_array();
	}

	public function db_get_all_affiliate_hospitals() {
		$query = $this->db->get('healthcare_providers');
		return $query->result_array();
	}

	public function db_get_company_doctors() {
		$this->db->select('*')
				 		 ->from('company_doctors as tbl_1')
				 		 ->join('user_accounts as tbl_2', 'tbl_1.doctor_id = tbl_2.doctor_id');
		return $this->db->get()->result_array();
	}

	public function db_get_laboratories() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Laboratory']);
		return $query->result_array();
	}

	public function db_get_clinics() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Clinic']);
		return $query->result_array();
	}
}
