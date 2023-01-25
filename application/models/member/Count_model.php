<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Count_model extends CI_Model {

  function count_all_pending_loa($emp_id) {
    $query = $this->db->get_where('loa_requests', array('status' => 'Pending', 'emp_id' => $emp_id));
    return $query->num_rows();
  }

  function count_all_pending_noa($emp_id) {
    $query = $this->db->get_where('noa_requests', array('status' => 'Pending', 'emp_id' => $emp_id));
    return $query->num_rows();
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
}
