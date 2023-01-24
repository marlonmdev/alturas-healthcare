<?php


class List_model extends CI_Model
{


    public function loa_member()
    {
        return $this->db->get('loa_requests')->result_array();
    }
    public function billingList()
    {

        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');

        $query = $this->db->get();
        return $query->result_array();
    }



    public function billing_search($search)
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');
        $this->db->like('billing_no', $search);
        $query = $this->db->get();
        return $query->result_array();
    }



    public function getLoaClose()
    {
        $this->db->select('*');
        $this->db->from('loa_requests');
        $this->db->where('status', 'Closed');

        $query = $this->db->get();
        return $query->result();
    }

    public function getNoaClose()
    {
        $this->db->select('*');
        $this->db->from('noa_requests');
        $this->db->where('status', 'Closed');

        $query = $this->db->get();
        return $query->result();
    }



    public function getBilling()
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');

        $query = $this->db->get();
        return $query->result_array();
    }


    public function getInHospitalDate($hospital, $month, $year)
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');
        $this->db->where('MONTH(billing.billing_date)', $month);
        $this->db->where('YEAR(billing.billing_date)', $year);
        $this->db->where('hp_id', $hospital);
        $query = $this->db->get();
        return $query->result_array();
    }
    // public function loa_member_approved($id)
    // {

    //     $this->db->select('*');
    //     $this->db->from('loa_requests');
    //     $this->db->where('hcare_provider', $id);
    //     $this->db->where('status', 'Approved');

    //     $query = $this->db->get();
    //     return $query->result();
    // }

    // public function get_cost_type($id)
    // {
    //     return $this->db->get_where('cost_types', array('ctype_id' => $id))->result();
    // }
}
