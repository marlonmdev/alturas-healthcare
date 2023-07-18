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
}
