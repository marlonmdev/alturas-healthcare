<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members_model extends CI_Model {

  // Start of server-side processing datatables
  var $table = 'members';
  var $column_order = ['member_id', 'first_name', 'emp_type', 'current_status', 'business_unit', 'dept_name']; //set column field database for datatable orderable
  var $column_search = ['member_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'current_status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['member_id' => 'desc']; // default order 

  private function _get_datatables_query($approval_status) {
    $this->db->from($this->table);
    $this->db->where('approval_status', $approval_status);
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

  function get_datatables($approval_status) {
    $this->_get_datatables_query($approval_status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered($approval_status) {
    $this->_get_datatables_query($approval_status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all($approval_status) {
    $this->db->from($this->table)
             ->where('approval_status', $approval_status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_member_details($member_id) {
    $this->db->select('*');
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }

  function db_get_member_mbl($emp_id) {
    $this->db->select('*');
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }

  function db_update_member_status($emp_id, $healthcard_no, $date_approved) {
    $data = array(
      'health_card_no' => $healthcard_no,
      'date_approved' => $date_approved,
      'approval_status' => 'Approved',
    );
    $this->db->where('emp_id', $emp_id);
    return $this->db->update('members', $data);
  }

  function db_insert_max_benefit_limit($mbl_data) {
    return $this->db->insert('max_benefit_limits', $mbl_data);
  }
}
