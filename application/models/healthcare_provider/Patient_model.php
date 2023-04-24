<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient_model extends CI_Model {
	var $table1 = 'loa_requests';
	var $table2 = 'members';
	var $table3 = 'max_benefit_limits';
	var $table4 = 'healthcare_providers';
	var $column_order = ['tbl_2.member_id', 'tbl_2.first_name', 'tbl_2.business_unit', 'tbl_2.dept_name', 'tbl_4.hp_name']; 
	
	var $column_search = ['emp_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'business_unit', 'dept_name','hp_name','CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['member_id' => 'desc']; // default order 

  private function _get_datatables_query($hp_id) {
		$this->db->group_by('emp_no');
		$this->db->from($this->table1 . ' as tbl_1');
    $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->join($this->table3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
    $this->db->join($this->table4 . ' as tbl_4', 'tbl_1.hcare_provider = tbl_4.hp_id');
   	$this->db->where('hcare_provider', $hp_id);
    $i = 0;

    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end(); 
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

	function get_datatables($hp_id) {
		$this->_get_datatables_query($hp_id);
		if ($_POST['length'] != -1)
		  $this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
	function count_all($hp_id) {
    $this->db->from($this->table1);
    $this->db->where('hcare_provider', $hp_id);
    return $this->db->count_all_results();
  }
	function count_filtered($hp_id) {
    $this->_get_datatables_query($hp_id);
    $query = $this->db->get();
    return $query->num_rows();
  }
  function db_get_member_details($member_id) {
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }
  function db_get_member_mbl($emp_id) {
    $this->db->select('*');
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }
}