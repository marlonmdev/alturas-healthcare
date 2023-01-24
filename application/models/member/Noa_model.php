<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table_1 = 'noa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = array('t1.noa_no', 't1.first_name', 't1.admission_date', 't2.hp_name', 't1.request_date', null, null); //set column field database for datatable orderable
  var $column_search = array('t1.noa_no', 't1.first_name', 't1.middle_name', 't1.last_name', 't1.suffix', 't1.admission_date', 't2.hp_name', 't1.request_date'); //set column field database for datatable searchable 
  var $order = array('t1.noa_id' => 'desc'); // default order 

  private function _get_datatables_query($status, $emp_id) {
    $this->db->from($this->table_1 . ' as t1');
    $this->db->join($this->table_2 . ' as t2', 't1.hospital_id = t2.hp_id');
    $this->db->where('t1.status', $status);
    $this->db->where('t1.emp_id', $emp_id);
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
    $this->db->from($this->table_1);
    $this->db->where('status', $status);
    $this->db->where('emp_id', $emp_id);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatabless

  function db_get_max_noa_id() {
    $this->db->select_max('noa_id');
    return $this->db->get('noa_requests')->row_array();
  }

  function db_insert_noa_request($post_data) {
    return $this->db->insert('noa_requests', $post_data);
  }

  function db_get_all_cost_types() {
    return $this->db->get('cost_types')->result_array();
  }

  function db_get_all_hospitals() {
    return $this->db->get_where('healthcare_providers', array('hp_type' => 'Hospital'))->result_array();
  }

  function db_update_noa_request($noa_id, $post_data) {
    $this->db->where('noa_id', $noa_id);
    return $this->db->update('noa_requests', $post_data);
  }

  function db_get_pending_noa($emp_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Pending')
             ->where('t1.emp_id', $emp_id)
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_approved_noa($emp_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Approved')
             ->where('t1.emp_id', $emp_id)
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_disapproved_noa($emp_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Disapproved')
             ->where('t1.emp_id', $emp_id)
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->join('members as t3', 't1.emp_id = t3.emp_id')
             ->where('t1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_approved_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->join('members as t3', 't1.emp_id = t3.emp_id')
             ->join('company_doctors as t4', 't1.approved_by = t4.doctor_id')
             ->where('t1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_disapproved_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->join('members as t3', 't1.emp_id = t3.emp_id')
             ->join('company_doctors as t4', 't1.disapproved_by = t4.doctor_id')
             ->where('t1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_closed_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->join('members as t3', 't1.emp_id = t3.emp_id')
             ->join('company_doctors as t4', 't1.approved_by = t4.doctor_id')
             ->where('t1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_doctor_by_id($doctor_id) {
    return $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id))->row_array();
  }

  function get_hospital_name($hospital_id) {
    $this->db->where('hp_id', $hospital_id);
    return $this->db->get('healthcare_providers')->row_array();
  }

  function db_check_hospital_exist($hospital_id) {
    $this->db->where('hp_id', $hospital_id);
    $query = $this->db->get('healthcare_providers');
    return $query->num_rows() > 0 ? true : false;
  }

  function db_cancel_noa($noa_id) {
    $this->db->where('noa_id', $noa_id);
    $this->db->delete('noa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_member_infos($emp_id) {
    $this->db->where('emp_id', $emp_id);
    $query = $this->db->get('members');
    return $query->num_rows() > 0 ? $query->row() : false;
  }
}
