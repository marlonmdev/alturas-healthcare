<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table_1 = 'loa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  var $column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['loa_id' => 'desc']; // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as tbl_1');
    $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }
    // loop column 
    foreach ($this->column_search as $item) {
      // if datatable send POST for search
      if ($_POST['search']['value']) {
        // first loop
        if ($i === 0) {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    // here order processing
    if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables($status) {
    $this->_get_datatables_query($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered($status) {
    $this->_get_datatables_query($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all($status) {
    $this->db->from($this->table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_max_loa_id() {
    $this->db->select_max('loa_id');
    $query = $this->db->get('loa_requests');
    return $query->row_array();
  }

  function db_insert_loa_request($post_data) {
    return $this->db->insert('loa_requests', $post_data);
  }

  function db_update_loa_request($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $post_data);
  }

  function db_cancel_loa($loa_id) {
    $this->db->where('loa_id', $loa_id)
             ->delete('loa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_cost_types() {
    $query = $this->db->get('cost_types');
    return $query->result_array();
  }

  function db_get_cost_types_by_hpID($hp_id){
    $this->db->select('*')
             ->from('cost_types')
             ->where('hp_id', $hp_id);
    $query = $this->db->get();
    return $query->result_array();
}

  function db_get_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->result_array();
  }

  function db_get_company_doctors() {
    $query = $this->db->get('company_doctors');
    return $query->result_array();
  }

  function db_get_loa_attach_filename($loa_id) {
    $this->db->select('loa_id, rx_file')
             ->where('loa_id', $loa_id);
    return $this->db->get('loa_requests')->row_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }


  function db_get_all_pending_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status, tbl_1.requested_by')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Pending')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_approved_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function get_all_approved_loa($loa_id){
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->where('tbl_1.status', 'Approved')
            ->where('tbl_1.loa_id', $loa_id)
            ->order_by('loa_id', 'DESC');
    return $this->db->get()->row_array();
  }

  function db_get_all_disapproved_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Disapproved')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_approved_loa($user_id) {
    $this->db->select('loa_id, loa_no, name_of_hospital, loa_request_type, availment_request_date, status, checked_by');
    $query = $this->db->get_where('loa_requests', ['status' => 'Approved', 'user_id' => $user_id]);
    return $query->result_array();
  }

  function db_get_disapproved_loa($user_id) {
    $this->db->select('loa_id, loa_no, name_of_hospital, loa_request_type, availment_request_date, status, checked_by');
    $query = $this->db->get_where('loa_requests', ['status' => 'Disapproved', 'user_id' => $user_id]);
    return $query->result_array();
  }

  function db_get_loa_info($loa_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_get_loa($loa_id) {
    $query = $this->db->get_where('loa_requests', ['loa_id' => $loa_id]);
  } 

  function db_get_loa_details($loa_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
             ->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id')
             ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id= tbl_5.emp_id')
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_approve_loa_request($loa_id, $approved_by) {
    $data = [
      'status' => 'Approved',
      'approved_by' => $approved_by,
      'approved_on' => date('Y-m-d'),
    ];
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  function db_disapprove_loa_request($loa_id, $disapproved_by) {
    $data = [
      'status' => 'Disapproved',
      'disapproved_by' => $disapproved_by,
      'disapproved_on' => date('Y-m-d'),
    ];
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_update_loa_charge_type($loa_id, $charge_type, $percentage) {
    $data = [
      'work_related' => $charge_type,
      'percentage' => $percentage
    ];
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  // Start of cancellation_requests server-side processing datatables
  var $table1 = 'loa_cancellation_requests';
  var $table2 = 'members';
  var $table3 = 'healthcare_providers';
  var $columnOrder = ['loa_no', 'first_name', 'requested_on', 'hp_name', null, 'status', null]; //set column field database for datatable orderable
  var $columnSearch = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'requested_on', 'status', 'tbl_1.hp_id', 'hp_name']; //set column field database for datatable searchable 
  var $order1 = ['loa_id' => 'desc']; // default order 

  private function _get_cancel_datatables_query($status) {
    $this->db->from($this->table1 . ' as tbl_1');
    $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.requested_by = tbl_2.emp_id');
    $this->db->join($this->table3 . ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
    $this->db->where('tbl_1.status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('hp_id', $this->input->post('filter'));
    }
    // loop column 
    foreach ($this->columnSearch as $item) {
      // if datatable send POST for search
      if ($_POST['search']['value']) {
        // first loop
        if ($i === 0) {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->columnSearch) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    // here order processing
    if (isset($_POST['order'])) {
      $this->db->order_by($this->columnOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order1)) {
      $order = $this->order1;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_cancel_datatables($status) {
    $this->_get_cancel_datatables_query($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_cancel_filtered($status) {
    $this->_get_cancel_datatables_query($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_cancel($status) {
    $this->db->from($this->table1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function set_cancel_approved($loa_id, $confirm_by, $confirmed_on) {
    $this->db->set('status', 'Approved')
            ->set('confirmed_by', $confirm_by)
            ->set('confirmed_on', $confirmed_on)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_cancellation_requests');
  }

  function set_cancel_disapproved($loa_id, $disapproved_by, $disapproved_on) {
    $this->db->set('status', 'Disapproved')
            ->set('disapproved_by', $disapproved_by)
            ->set('disapproved_on', $disapproved_on)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_cancellation_requests');
  }

  function set_cloa_request_status($loa_id, $status) {
    $this->db->set('status', $status)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_requests');
  }

  function insert_performed_loa_info($post_data) {
    return $this->db->insert_batch('performed_loa_info', $post_data);
  }

  function insert_edited_performed_loa_info($post_data, $loa_id) {
    $this->db->where('loa_id', $loa_id);

    if (!empty($post_data)) {
        return $this->db->update_batch('performed_loa_info', $post_data, 'ctype_id');
    } else {
        // If $post_data is empty, return true to indicate that the update succeeded.
        return true;
    }
  }

  function update_consulation_loa_info($post_data, $loa_id) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('performed_loa_info', $post_data);
  }

  function insert_performed_loa_consult($post_data) {
    return $this->db->insert('performed_loa_info', $post_data);
  }

  function check_loa_no($loa_id) {
    $query = $this->db->get_where('performed_loa_info', ['loa_id' => $loa_id]);
    return $query->num_rows() > 0 ? true : false;
  }

  function get_performed_loa_data($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }

  function get_consultation_data($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }      

  function fetch_per_loa_info($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }

  function fetch_per_consult_loa_info($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function check_if_all_status_performed($loa_id) {
    $field_name = 'status';
    $this->db->distinct()
            ->select($field_name)
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    $query = $this->db->get();

    $results = $query->result();

    if (count($results) === 1 && $results[0]->$field_name === 'Performed') {
      return true;
    }
  }

  function set_loa_status_completed($loa_id, $status) {
    $this->db->set('status', $status)
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function db_update_loa_med_services($loa_id, $new_field_value){
    $this->db->set('med_services', $new_field_value)
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function get_all_completed_loa($loa_id){
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
            ->where('tbl_1.status', 'Completed')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }


}
