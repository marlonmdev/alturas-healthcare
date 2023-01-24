<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Get_model extends CI_Model {

	public function db_get_hospitals() {
		return $this->db->get_where('healthcare_providers', array('hp_type' => 'Hospital'))->result_array();
	}

	public function db_get_all_affiliate_hospitals() {
		return $this->db->get('healthcare_providers')->result_array();
	}

	public function db_get_company_doctors() {
		$this->db->select('*')
				 		 ->from('company_doctors as t1')
				 		 ->join('user_accounts as t2', 't1.doctor_id = t2.doctor_id');
		return $this->db->get()->result_array();
	}

	public function db_get_laboratories() {
		return $this->db->get_where('healthcare_providers', array('hp_type' => 'Laboratory'))->result_array();
	}

	public function db_get_clinics() {
		return $this->db->get_where('healthcare_providers', array('hp_type' => 'Clinic'))->result_array();
	}
}
