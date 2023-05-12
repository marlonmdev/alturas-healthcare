<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Count_model extends CI_Model {

  function count_all_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->num_rows();
  }

  function count_all_members() {
    $query = $this->db->get('members');
    return $query->num_rows();
  }

  function count_all_pending_loa() {
    $this->db->where('status', 'Pending');
    $query = $this->db->get('loa_requests');
    return $query->num_rows();
  }

  function count_all_pending_noa() {
    $this->db->where('status', 'Pending');
    $query = $this->db->get('noa_requests');
    return $query->num_rows();
  }

  function count_all_hospitals() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Hospital']);
    return $query->num_rows();
  }

  function count_all_laboratories() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Laboratory']);
    return $query->num_rows();
  }

  function count_all_clinics() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Clinic']);
    return $query->num_rows();
  }

  function db_get_company_doctors() {
    $this->db->select('*')
             ->from('company_doctors as t1')
             ->join('user_accounts as t2', 't1.doctor_id = t2.doctor_id');
    return $this->db->get()->result_array();
  }

  //Bar =================================================
  public function bar_pending(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Pending' ");
    return $query->num_rows(); 
  }

  public function bar_approved(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Approved' ");
    return $query->num_rows(); 
  } 
  public function bar_completed(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Completed' ");
    return $query->num_rows(); 
  } 
  public function bar_referral(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Referral' ");
    return $query->num_rows(); 
  }
  public function bar_expired(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Expired' ");
    return $query->num_rows(); 
  } 
  //End =================================================
}
