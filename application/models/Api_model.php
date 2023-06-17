<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {
    /*
    * get API from database cash_advance
    */
  function get_hmo_CA()
  {
   $this->db->select('emp_id, billing_id, hp_id, excess_amount, date_added, status');
   $this->db->from('cash_advance');

  $query = $this->db->get();

  return $query->result_array();
  }

  function update_apprv($data)
  {
    //  var_dump($data);
    $emp_id = $data['emp_id'];
    $approved_amount = $data['approved_amount'];
    $excess_amount = $data['excess_amount'];
    $billing_id = $data['billing_id'];

    // var_dump($emp_id, $approved_amount, $excess_amount, $billing_id);
    $date_prcs	= date("Y-m-d");
    $this->db->set('approved_amount',$approved_amount);
    $this->db->set('date_approved',$date_prcs);
    $this->db->set('disapproved_on','Approved');
    $this->db->where('emp_id',$emp_id);
    $this->db->where('billing_id',$billing_id);
    $this->db->update('cash_advance');
    
    
  }

  
}