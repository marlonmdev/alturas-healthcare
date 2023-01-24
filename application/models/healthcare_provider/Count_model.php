<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Count_model extends CI_Model {

  public function hp_approved_loa_count($hp_id){
    return $this->db->get_where('loa_requests', array('hcare_provider' => $hp_id, 'status' => 'Approved'))->num_rows();
  }

  public function hp_approved_noa_count($hp_id){
    return $this->db->get_where('noa_requests', array('hospital_id' => $hp_id, 'status' => 'Approved'))->num_rows();
  }

  public function hp_done_billing_count($hp_id){
    return $this->db->get_where('billing', array('hp_id' => $hp_id))->num_rows();
  }


}
