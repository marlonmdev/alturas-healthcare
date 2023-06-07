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
    
    function set_completed_value($loa_id) {
        $this->db->set('completed','')
                ->where('loa_id', $loa_id);
        return $this->db->update('loa_requests');
    }

    function check_if_loa_already_added($loa_id) {
        $query = $this->db->get_where('hr_added_loa_fees', ['loa_id' => $loa_id]);
        return $query->num_rows() > 0 ? true : false;
    }

    function check_if_done_created_new_loa($loa_id) {
        $this->db->select('reffered')
            ->from('loa_requests')
            ->where('loa_id', $loa_id);
        return $this->db->get()->row_array();
    }

    function check_if_status_cancelled($loa_id) {
        $this->db->select('status')
                ->where('status', 'Reffered')
                ->where('loa_id', $loa_id)
                ->group_by('loa_id');
        $query = $this->db->get('performed_loa_info');
    
        if ($query->num_rows() > 0) {
        return true;
        }else{
        return false;
        }
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
    function get_billed_loa_pdf($id) {
        $this->db->select('pdf_bill')
                 ->from('billing')
                 ->where('loa_id', $id);
        $query = $this->db->get();
        return $query->row(); // Return a single object representing the row
    }
    function get_billed_noa_pdf($id) {
        $this->db->select('pdf_bill')
                 ->from('billing')
                 ->where('noa_id', $id);
        $query = $this->db->get();
        return $query->row(); // Return a single object representing the row
    }
    function get_loa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_1.pdf_bill, tbl_1.attending_doctors, tbl_1.details_no, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.loa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_noa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_1.pdf_bill, tbl_1.final_diagnosis_file, tbl_1.medical_abstract_file, tbl_1.prescription_file,  tbl_1.attending_doctors, tbl_1.details_no, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.noa_id', $id);
        return $this->db->get()->row_array();
    }
    var $table_1_soa = 'billing';
     var $table_2_soa = 'noa_requests';
     var $table_3_soa = 'loa_requests';
    //  var $table_4_soa = 'members';
    //  var $table_5_soa = 'healthcare_providers';
    //  var $table_7_soa = 'max_benefit_limits';
    function get_re_upload_requests($hp_id,$emp_id){
        $query1 = $this->db->select('tbl_2.noa_no, tbl_2.request_date, tbl_1.status, tbl_1.noa_id')
            ->from($this->table_1_soa . ' as tbl_1')
            ->join($this->table_2_soa . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id')
            ->where('tbl_1.hp_id', $hp_id)
            ->where('tbl_1.emp_id', $emp_id)
            ->where('tbl_1.re_upload', 1)
            ->get()
            ->result_array();

        $query2 = $this->db->select('tbl_3.loa_no, tbl_3.request_date, tbl_1.status, tbl_1.loa_id')
            ->from($this->table_1_soa . ' as tbl_1')
            ->join($this->table_3_soa . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id')
            ->where('tbl_1.hp_id', $hp_id)
            ->where('tbl_1.emp_id', $emp_id)
            ->where('tbl_1.re_upload', 1)
            ->get()
            ->result_array();

            $result = array_merge($query1, $query2);

        return $result;

    }

    
    function get_billing($billing_no){
        $query = $this->db->get_where('billing', array('billing_no' => $billing_no));
        return $query->row_array();
    }
    function get_billing_no($loa_noa){
        $this->db->where('loa_id', $loa_noa);
        $this->db->or_where('noa_id', $loa_noa);
        $query = $this->db->get('billing');
        return $query->row_array();
    }
    function get_prev_mbl($billing_no, $emp_id) {
        $billing_row = $this->db->select('billing_id')->get_where('billing', ['billing_no' => $billing_no])->row();
    
        if ($billing_row !== null) {
            $billing_id = $billing_row->billing_id;
    
            $this->db->select('after_remaining_bal')
                ->where('billing_id <', $billing_id)
                ->where('emp_id', $emp_id)
                ->order_by('billing_id', 'desc')
                ->limit(1);
    
            $query = $this->db->get('billing');
            return $query->row_array();
        }
    
        return null; // or return a default value if needed
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
        $this->db->select('*')
                 ->from('billing_room_boards as tbl_1')
                 ->join('room_types as tbl_2', 'tbl_1.room_id = tbl_2.room_id')
                 ->where('tbl_1.billing_no', $billing_no);
        return $this->db->get()->result_array();
    }

    function get_billing_deductions($billing_no){
       $query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
       return $query->result_array();
    }

    function insert_billing($data) {
        return $this->db->insert('billing', $data);
    }
    function insert_hospital_charges($data) {
        return $this->db->insert('hospital_charges', $data);
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

    function insert_room_board($room) {
        return $this->db->insert('billing_room_boards', $room);
    }

    function insert_personal_charge($charge) {
        return $this->db->insert('personal_charges', $charge);
    }

    function update_member_remaining_balance($emp_id, $data) {
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('max_benefit_limits', $data); 
    }

    function check_re_upload_billing($billing_no){
        $query = $this->db->get_where('billing', ['billing_no' => $billing_no,'re_upload' => 1]);
        return $query->num_rows();
     }
    function insert_old_billing($billing_no) {
        $query = $this->db->get_where('billing', ['billing_no' => $billing_no,'re_upload' => 1]);
        $result = $query->result_array();

        // Insert the selected records into the destination table
        if (!empty($result)) {
          return  $this->db->insert_batch('re_upload_billing', $result);
        }
        // $this->db->where('billing_no', $billing_no);
        // $this->db->where('re_upload', 1);
        // return $this->db->insert('billing', $data);
    }

    function update_billing($data,$billing_no) {
        $this->db->where('billing_no', $billing_no);
        return $this->db->update('billing', $data); 
    }
    function update_affected_billing($data,$billing_no) {
        $this->db->where('billing_no', $billing_no);
        return $this->db->update('billing', $data); 
    }

    function get_affected_billing($billing_no, $emp_id){
        $billing_id = $this->db->select('billing_id')->get_where('billing', ['billing_no' => $billing_no])->row()->billing_id;
        $this->db->select('*')
            ->where('billing_id >', $billing_id)
            ->where('emp_id', $emp_id)
            ->where('status !=', 'Paid');
        $query = $this->db->get('billing');
        return $query->result_array();
    }
    
    function insert_cash_advance($data) {
        return $this->db->insert('cash_advance', $data);
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
