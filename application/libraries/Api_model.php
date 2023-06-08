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
}