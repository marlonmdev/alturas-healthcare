<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Count_model extends CI_Model {

  function count_all_healthcare_providers() {
    return $this->db->get('healthcare_providers')->num_rows();
  }

  function count_all_members() {
    return $this->db->get('members')->num_rows();
  }

  function count_all_pending_loa() {
    $this->db->where('status', 'Pending');
    return $this->db->get('loa_requests')->num_rows();
  }

  function count_all_pending_noa() {
    $this->db->where('status', 'Pending');
    return $this->db->get('noa_requests')->num_rows();
  }

  function count_all_hospitals() {
    $query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Hospital'));
    return $query->num_rows();
  }

  function count_all_laboratories() {
    $query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Laboratory'));
    return $query->num_rows();
  }

  function count_all_clinics() {
    $query = $this->db->get_where('healthcare_providers', array('hp_type' => 'Clinic'));
    return $query->num_rows();
  }

  function db_get_company_doctors() {
    $this->db->select('*')
             ->from('company_doctors as t1')
             ->join('user_accounts as t2', 't1.doctor_id = t2.doctor_id');
    return $this->db->get()->result_array();
  }
}
