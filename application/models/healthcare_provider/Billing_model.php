<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing_model extends CI_Model {

    function get_member_by_name($first_name, $last_name, $date_of_birth) {
        $query = $this->db->get_where('members', ['first_name' => $first_name, 'last_name' => $last_name, 'date_of_birth' => $date_of_birth]);
        return $query->row_array();
    }

    function get_member_by_healthcard($healthcard_no) {
        $query = $this->db->get_where('members', ['health_card_no' => $healthcard_no]);
        return $query->row_array();
    }

    function get_healthcare_provider($hcare_provider_id) {
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hcare_provider_id]);
        return $query->row();
    }

   function get_member_mbl($emp_id) {
        $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
        return $query->row_array();
    } 

    function get_member_loa($emp_id, $hcare_provider_id) {
        $this->db->select('loa_id, loa_no, emp_id, request_date, hcare_provider, status')
                 ->from('loa_requests')
                 ->where('emp_id', $emp_id)
                 ->where('hcare_provider', $hcare_provider_id)
                 ->order_by('loa_id', 'DESC');
        return $this->db->get()->result_array();
    }
    
    function get_loa_to_bill($loa_id) {
        $query = $this->db->get_where('loa_requests', ['loa_id' => $loa_id]);
        return $query->row_array();
    }

    function get_noa_to_bill($noa_id) {
        $query = $this->db->get_where('noa_requests', ['noa_id' => $noa_id]);
        return $query->row_array();
    }

    function get_cost_type_by_id($ctype_id) {
        $query = $this->db->get_where('cost_types', ['ctype_id' => $ctype_id]);
        return $query->row_array();
    }
    
    function db_get_cost_types_by_hpID($hp_id) {
        $this->db->select('*')
                ->from('cost_types')
                ->where('hp_id', $hp_id);
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

    function get_all_cost_types() {
        $query = $this->db->get('cost_types');
        return $query->result_array();
    }

    function get_cost_types_by_hp($hp_id){
        $this->db->select('*')
                 ->from('cost_types')
                 ->where('hp_id', $hp_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_hospital_cost_types($hospital_id){
        $this->db->select('*')
                 ->from('cost_types')
                 ->where('hp_id', $hospital_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_healthcare_provider_by_id($hp_id){
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hp_id]);
        return $query->row_array();
    }

    function get_hospital_room_types($hospital_id){
         $this->db->select('*')
                 ->from('room_types')
                 ->where('hp_id', $hospital_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function close_billing_loa_requests($id) {
        $this->db->set('status', 'Closed')
                 ->where('loa_id', $id);
        return $this->db->update('loa_requests');
    }

    function close_billing_noa_requests($id) {
        $this->db->set('status', 'Closed')
                 ->where('noa_id', $id);
        return $this->db->update('noa_requests');
    }

    function billing_count($id) {
        $this->db->select('*')
                 ->from('billing')
                 ->where('hp_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_loa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.loa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_noa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.noa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_billing($billing_no){
        $query = $this->db->get_where('billing', array('billing_no' => $billing_no));
        return $query->row_array();
    }

    function get_billing_info($billing_id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.billing_id', $billing_id);
        return $this->db->get()->row_array();
    }

    function get_billing_services($billing_no){
       $query = $this->db->get_where('billing_services', ['billing_no' => $billing_no]);
       return $query->result_array();
    }

    function get_billing_medications($billing_no){
       $query = $this->db->get_where('billing_medications', ['billing_no' => $billing_no]);
       return $query->result_array();
    }

    function get_billing_professional_fees($billing_no){
       $query = $this->db->get_where('billing_professional_fees', ['billing_no' => $billing_no]);
       return $query->result_array();
    }

    function get_billing_room_boards($billing_no){
        $query = $this->db->get_where('billing_room_boards', ['billing_no' => $billing_no]);
        return $query->result_array();
    }

    function get_billing_deductions($billing_no){
       $query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
       return $query->result_array();
    }

    function insert_billing($data) {
        return $this->db->insert('billing', $data);
    }

    function insert_diagnostic_test_billing_services($services) {
        return $this->db->insert_batch('billing_services', $services);
    }

    function insert_consultation_billing_services($services) {
        return $this->db->insert('billing_services', $services);
    }

    function insert_billing_medications($medications){
        return $this->db->insert_batch('billing_medications', $medications);
    }

    function insert_billing_professional_fees($prof_fees){
        return $this->db->insert_batch('billing_professional_fees', $prof_fees);
    }

    function insert_billing_deductions($deductions) {
        return $this->db->insert_batch('billing_deductions', $deductions);
    }

    function insert_personal_charge($charge) {
        return $this->db->insert('personal_charges', $charge);
    }

    function update_member_remaining_balance($emp_id, $data) {
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('max_benefit_limits', $data);
    }

    function update_loa_request($loa_id, $data){
        $this->db->where('loa_id', $loa_id);
        return $this->db->update('loa_requests', $data);
    }

    function update_noa_request($noa_id, $data){
        $this->db->where('noa_id', $noa_id);
        return $this->db->update('noa_requests', $data);
    }

    function insert_textfile($data) {
        return $this->db->insert('soa_textfile', $data);
    }

}
