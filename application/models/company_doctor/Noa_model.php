<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table_1 = 'noa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = array('t1.noa_no', 't1.first_name', 't1.admission_date', 't2.hp_name', 't1.request_date', null, null); //set column field database for datatable orderable
  var $column_search = array('t1.noa_no', 't1.first_name', 't1.middle_name', 't1.last_name', 't1.suffix', 't1.admission_date', 't2.hp_name', 't1.request_date'); //set column field database for datatable searchable 
  var $order = array('t1.noa_id' => 'desc'); // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as t1');
    $this->db->join($this->table_2 . ' as t2', 't1.hospital_id = t2.hp_id');
    $this->db->where('t1.status', $status);
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
    $this->db->from($this->table_1);
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_all_pending_noa() {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Pending')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_approved_noa() {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Approved')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_disapproved_noa() {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->where('t1.status', 'Disapproved')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as t1')
             ->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id')
             ->join('max_benefit_limits as t3', 't1.emp_id = t3.emp_id')
             ->where('t1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_doctor_name_by_id($doctor_id) {
    return $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id))->row_array();
  }


  function db_approve_noa_request($noa_id, $work_related, $approved_by, $approved_on) {
    $data = array(
      'status' => 'Approved',
      'work_related' => $work_related,
      'approved_by' => $approved_by,
      'approved_on' => $approved_on
    );
    $this->db->where('noa_id', $noa_id);
    return $this->db->update('noa_requests', $data);
  }

  function db_disapprove_noa_request($noa_id, $disapproved_by, $disapprove_reason, $disapproved_on) {
    $data = array(
      'status' => 'Disapproved',
      'disapproved_by' => $disapproved_by,
      'disapprove_reason' => $disapprove_reason,
      'disapproved_on' => $disapproved_on
    );
    $this->db->where('noa_id', $noa_id);
    return $this->db->update('noa_requests', $data);
  }
}
