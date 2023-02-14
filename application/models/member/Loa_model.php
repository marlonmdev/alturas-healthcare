<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table = 'loa_requests';
  var $column_order = ['loa_no', 'request_date', 'hcare_provider', 'loa_request_type']; //set column field database for datatable orderable
  var $column_search = ['loa_no', 'request_date', 'hp_name', 'loa_request_type', 'med_services']; //set column field database for datatable searchable 
  var $order = ['loa_id' => 'desc']; // default order 

  private function _get_datatables_query($status, $emp_id) {
    $this->db->from($this->table . ' as tbl_1');
    $this->db->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('tbl_1.status', $status);
    $this->db->where('tbl_1.emp_id', $emp_id);
    $i = 0;
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

  function get_datatables($status, $emp_id) {
    $this->_get_datatables_query($status, $emp_id);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered($status, $emp_id) {
    $this->_get_datatables_query($status, $emp_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all($status, $emp_id) {
    $this->db->from($this->table)
             ->where('status', $status)
             ->where('emp_id', $emp_id);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_max_loa_id() {
    $this->db->select_max('loa_id');
    $query = $this->db->get('loa_requests');
    return $query->row_array();
  }

  function db_insert_loa_request($post_data) {
    $query = $this->db->insert('loa_requests', $post_data);
    return $query ? $this->db->insert_id() : false;
  }

  function db_update_loa_request($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $post_data);
  }

  function db_get_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->result_array();
  }

  function db_get_company_doctors() {
    $query = $this->db->get('company_doctors');
    return $query->result_array();
  }

  function db_get_cost_types() {
    $query = $this->db->get('cost_types');
    return $query->result_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }

  function db_get_pending_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.request_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->having('tbl_1.status', 'Pending')
             ->where('tbl_1.emp_id', $emp_id)
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_approved_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.request_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('loa_id', 'DESC')
             ->where('tbl_1.emp_id', $emp_id);
    return $this->db->get()->result_array();
  }

  function db_get_disapproved_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.request_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Disapproved')
             ->where('tbl_1.emp_id', $emp_id);
    $this->db->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_loa_info($loa_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_get_requesting_physician($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_doctor_info($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function get_hospital_name($hospital_id) {
    $this->db->where('hospital_id', $hospital_id);
    $query = $this->db->get('affiliate_hospitals');
    return $query->row_array();
  }

  function db_check_healthcare_provider_exist($hp_id) {
    $this->db->where('hp_id', $hp_id);
    $query = $this->db->get('healthcare_providers');
    return $query->num_rows() > 0 ? true : false;
  }

  function db_get_loa_attach_filename($loa_id) {
    $this->db->select('loa_id, rx_file')
             ->where('loa_id', $loa_id);
    return $this->db->get('loa_requests')->row_array();
  }

  function db_cancel_loa($loa_id) {
    $this->db->where('loa_id', $loa_id)
             ->delete('loa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_member_infos($emp_id) {
    $this->db->where('emp_id', $emp_id);
    $query = $this->db->get('members');
    return $query->num_rows() > 0 ? $query->row_array() : false;
  }
}
