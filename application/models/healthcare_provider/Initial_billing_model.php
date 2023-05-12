<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Initial_billing_model extends CI_Model {

    function insert_initial_bill($data){
        return $this->db->insert('initial_billing', $data);
    }
    function get_initial_bill($noa,$loa){
        $this->db->select('*');
        $this->db->from('initial_billing');
        return $this->db->where('id', $loa); 
    }

}