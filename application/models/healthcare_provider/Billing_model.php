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

    function get_cost_type_by_id($ctype_id) {
        $query = $this->db->get_where('cost_types', ['ctype_id' => $ctype_id]);
        return $query->row_array();
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

    function get_healthcare_provider_by_id($hp_id){
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hp_id]);
        return $query->row_array();
    }

    function pay_billing_member($data) {
        return $this->db->insert('billing', $data);
    }

    function addEquipment($id) {
        $query = $this->db->get_where('cost_types', ['ctype_id' => $id]);
        return $query->row_array();
    }

    function create_billing($post_data) {
        return $this->db->insert('billing', $post_data);
    }

    function loa_cost_type_by($cost_type) {
        return $this->db->insert('billing_services', $cost_type);
    }

    function loa_personal_charges($personal_charges) {

        return $this->db->insert('personal_charges', $personal_charges);
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

    function insert_diagnostic_test_billing($data) {
        return $this->db->insert('billing', $data);
    }

    function insert_diagnostic_test_billing_services($services) {
        return $this->db->insert_batch('billing_services', $services);
    }

    function insert_diagnostic_test_billing_deductions($deductions) {
        return $this->db->insert_batch('billing_deductions', $deductions);
    }

    function insert_personal_charge($charge) {
        return $this->db->insert('personal_charges', $charge);
    }

    function update_member_remaining_balance($emp_id, $new_balance) {
        $this->db->set('remaining_balance', $new_balance)
                 ->where('emp_id', $emp_id);
        return $this->db->update('max_benefit_limits');
    }
}
