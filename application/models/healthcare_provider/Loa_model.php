<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Loa_model extends CI_Model{

    function loa_member_pending($id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $id)
                 ->where('status', 'Pending');
        $query = $this->db->get();
        return $query->result();
    }

    function loa_member_approved($id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $id)
                 ->where('status', 'Approved');
        $query = $this->db->get();
        return $query->result();
    }

    function loa_member_disapproved($id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $id)
                 ->where('status', 'Disapproved');
        $query = $this->db->get();
        return $query->result();
    }

    function loa_member_closed($id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $id)
                 ->where('status', 'Closed');
        $query = $this->db->get();
        return $query->result();
    }

    function get_cost_type($id){
        $this->db->select('*')
                 ->from('cost_types')
                 ->where('ctype_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

}
