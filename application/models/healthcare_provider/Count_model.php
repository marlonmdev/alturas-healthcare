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

  public function total_patient($hp_id){
  	$this->db->select('*');
		$this->db->from('loa_requests');
		$this->db->where('hcare_provider', $hp_id);
		$this->db->group_by('emp_id');
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		return $num_rows;
  }


}
