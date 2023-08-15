<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Count_model extends CI_Model {

  function count_all_pending_loa($emp_id) {
    $query = $this->db->get_where('loa_requests', ['status' => 'Pending', 'emp_id' => $emp_id]);
    return $query->num_rows();
  }

  function count_all_pending_noa($emp_id) {
    $query = $this->db->get_where('noa_requests', ['status' => 'Pending', 'emp_id' => $emp_id]);
    return $query->num_rows();
  }

  function count_all_hospitals() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Hospital', 'accredited' => 1]);
    return $query->num_rows();
  }

  function count_all_laboratories() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Laboratory', 'accredited' => 1]);
    return $query->num_rows();
  }

  function count_all_clinics() {
    $query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Clinic', 'accredited' => 1]);
    return $query->num_rows();
  }
}
