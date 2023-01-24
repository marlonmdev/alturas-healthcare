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

	function db_insert_healthcare_provider($post_data) {
		return $this->db->insert('healthcare_providers', $post_data);
	}

	function db_check_healthcare_provider($hp_name) {
		$query = $this->db->get_where('healthcare_providers', array('hp_name' => $hp_name));
		return $query->num_rows() > 0 ? true : false;
	}

	function db_get_healthcare_providers() {
		$this->db->select('*')
						 ->from('healthcare_providers');
		return $this->db->get()->result_array();
	}

	function db_get_all_healthcare_providers() {
		$this->db->select('*')
					   ->from('healthcare_providers')
					   ->order_by('hp_id', 'DESC');
		return $this->db->get()->result_array();
	}

	function db_get_healthcare_provider_info($hp_id) {
		$query = $this->db->get_where('healthcare_providers', array('hp_id' => $hp_id));
		return $query->row_array();
	}

	function db_update_healthcare_provider($hp_id, $post_data) {
		$this->db->where('hp_id', $hp_id);
		return $this->db->update('healthcare_providers', $post_data);
	}

	function db_delete_healthcare_provider($hp_id) {
		$this->db->where('hp_id', $hp_id)
						 ->delete('healthcare_providers');
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function db_get_company_doctors() {
		$this->db->select('*')
		         ->from('company_doctors');
		return $this->db->get()->result_array();
	}

	function db_get_all_company_doctors() {
		$this->db->select('*')
						 ->from('company_doctors')
						 ->order_by('doctor_id', 'DESC');
		return $this->db->get()->result_array();
	}

	function db_get_doctor_info($doctor_id) {
		$query = $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id));
		return $query->row_array();
	}

	function db_insert_company_doctor($post_data) {
		$inserted = $this->db->insert('company_doctors', $post_data);
		return $inserted ? $this->db->insert_id() : false;
	}

	function db_insert_company_doctor_user_account($account_data) {
		return $this->db->insert('user_accounts', $account_data);
	}

	function db_check_doctor_name($doctor_name) {
		$query = $this->db->get_where('company_doctors', array('doctor_name' => $doctor_name));
		return $query->num_rows() > 0 ? true : false;
	}

	function db_check_username($doctor_username) {
		$query = $this->db->get_where('user_accounts', array('username' => $doctor_username));
		return $query->num_rows() > 0 ? true : false;
	}

	function db_update_company_doctor($doctor_id, $post_data) {
		$this->db->where('doctor_id', $doctor_id);
		return $this->db->update('company_doctors', $post_data);
	}

	function db_update_company_doctor_account_name($doctor_id, $doctor_name) {
		$this->db->set('full_name', $doctor_name)
		         ->where('doctor_id', $doctor_id);
		return $this->db->update('user_accounts');
	}

	function db_delete_company_doctor($doctor_id) {
		$tables = array('company_doctors', 'user_accounts');
		$this->db->where('doctor_id', $doctor_id)
						 ->delete($tables);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function db_get_all_cost_types() {
		$this->db->select('*')
						 ->from('cost_types')
						 ->order_by('ctype_id', 'DESC');
		return $this->db->get()->result_array();
	}

	function db_get_cost_type_info($ctype_id) {
		$query = $this->db->get_where('cost_types', array('ctype_id' => $ctype_id));
		return $query->row_array();
	}

	function db_insert_cost_type($post_data) {
		return $this->db->insert('cost_types', $post_data);
	}

	function db_check_cost_type($cost_type) {
		$query = $this->db->get_where('cost_types', array('cost_type' => $cost_type));
		return $query->num_rows() > 0 ? true : false;
	}

	function db_update_cost_type($ctype_id, $post_data) {
		$this->db->where('ctype_id', $ctype_id);
		return $this->db->update('cost_types', $post_data);
	}

	function db_delete_cost_type($ctype_id) {
		$this->db->where('ctype_id', $ctype_id)
		         ->delete('cost_types');
		return $this->db->affected_rows() > 0 ? true : false;
	}
}
