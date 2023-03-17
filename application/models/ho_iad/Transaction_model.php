<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    function get_member_by_healthcard($emp_no) {
        // $query = $this->db->get_where('members', ['health_card_no' => $healthcard_no]);
        $query = $this->db->get_where('members', ['emp_no' => $emp_no]);
        return $query->row_array();
    }
    function get_member_by_name($first_name,$middle_name, $last_name) {
        $query = $this->db->get_where('members', ['first_name' => $first_name,'middle_name'=>$middle_name,'last_name' => $last_name]);
        return $query->row_array();
    }
    function get_member_mbl($emp_id) {
        $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
        return $query->row_array();
    } 
    function get_healthcare_provider($hcare_provider_id) {
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hcare_provider_id]);
        return $query->row();
    }
    // function get_member_loa($emp_id, $hcare_provider_id) {
    //     $this->db->select('loa_id, loa_no, emp_id, request_date, hcare_provider, status')
    //             ->from('loa_requests')
    //             ->where('emp_id', $emp_id)
    //             ->where('hcare_provider', $hcare_provider_id)
    //             ->order_by('loa_id', 'DESC');
    //     return $this->db->get()->result_array();
    // }
    function get_billing_status($emp_id, $hcare_provider_id) {
        $this->db->select('billing_id, billing_no, emp_id, billed_on,payment_no,loa_id,noa_id,status')
                ->from('billing')
                ->where('emp_id', $emp_id)
                // ->where('hcare_provider', $hcare_provider_id)
                ->order_by('loa_id', 'DESC');
        return $this->db->get()->result_array();
    }
    function get_member_noa($emp_id, $hcare_provider_id) {
        $this->db->select('noa_id, noa_no, emp_id, request_date, status, hospital_id')
                ->from('noa_requests')
                ->where('emp_id', $emp_id)
                ->where('hospital_id', $hcare_provider_id)
                ->order_by('noa_id', 'DESC');
        return $this->db->get()->result_array();
    }

    function get_loa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
            ->from('billing as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
            ->where('tbl_1.loa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_noa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
            ->from('billing as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
            ->where('tbl_1.billing_id', $id);
        return $this->db->get()->row_array();
    }

    function get_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
            ->from('billing as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
            ->where('tbl_1.billing_id', $id);
        return $this->db->get()->row_array();
    }

    function get_billing_services($billing_no){
        $query = $this->db->get_where('billing_services', ['billing_no' => $billing_no]);
        return $query->result_array();
     }
     function get_billing_deductions($billing_no){
        $query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
        return $query->result_array();
     }


    
}
