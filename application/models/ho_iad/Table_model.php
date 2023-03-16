<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Table_model extends CI_Model {
	var $table_1 = 'billing';
	var $table_2 = 'members';
	var $table_3 = 'healthcare_providers';
	var $column_order = ['tbl_1.billing_no', 'tbl_2.first_name', 'tbl_1.billed_on', 'tbl_1.company_charge', NULL];
	var $column_search = ['tbl_1.hp_id', 'tbl_1.billing_no', 'tbl_2.first_name', 'tbl_2.middle_name', 'tbl_2.last_name', 'tbl_3.hp_name', 'tbl_1.billed_on'];
	var $order = ['tbl_1.billing_id' => 'asc'];

	private function _get_datatables_query($status) {
		
		$this->db->from($this->table_1. ' as tbl_1');
		$this->db->join($this->table_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
		$this->db->join($this->table_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
		$this->db->where('status', $status);
		$i = 0;

    if($this->input->post('filter')){
			$this->db->like('tbl_1.hp_id', $this->input->post('filter'));
		}
		if ($this->input->post('startDate')) {
			$startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
			$this->db->where('tbl_1.billed_on >=', $startDate);
		}
		if ($this->input->post('endDate')){
			$endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
			$this->db->where('tbl_1.billed_on <=', $endDate);
		}

		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

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

  	function get_billing_by_emp_id($emp_id, $status){
		$this->db->select('*')
			->from('billing as tbl_1')
			->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
			->join('members as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
			->where('tbl_1.emp_id', $emp_id)
			->where('status', $status);
		return $this->db->get()->result_array();
	}


}






































//  // Load the database library
//  $this->load->database();

//  // Select distinct values from a table
//  $query = $this->db->distinct()->select('column_name')->from('table_name')->get();
 
//  // Check if there are any results
//  if ($query->num_rows() > 0) {
// 	 foreach ($query->result() as $row) {
// 		 // Do something with the results
// 		 echo $row->column_name;
// 	 }
//  } 


// private function _get_datatables_query($status) {
// 	$this->db->distinct()->select('tbl_1.emp_id, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_1.hp_id, tbl_3.hp_name, tbl_1.loa_id, tbl_1.noa_id, tbl_1.billed_on');
// 	$this->db->from($this->table_1. ' as tbl_1');
// 	$this->db->join($this->table_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
// 	$this->db->join($this->table_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
// 	$this->db->where('status', $status);
// 	$i = 0;

// if($this->input->post('filter')){
// 		$this->db->like('tbl_1.hp_id', $this->input->post('filter'));
// 	}
// 	if ($this->input->post('startDate')) {
// 		$startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
// 		$this->db->where('tbl_1.billed_on >=', $startDate);
// 	}
// 	if ($this->input->post('endDate')){
// 		$endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
// 		$this->db->where('tbl_1.billed_on <=', $endDate);
// 	}

// 	foreach ($this->column_search as $item) {
// 		if ($_POST['search']['value']) {
// 			if ($i === 0) {
// 				$this->db->group_start();
// 				$this->db->like($item, $_POST['search']['value']);
// 			}else {
// 				$this->db->or_like($item, $_POST['search']['value']);
// 			}
// 			if (count($this->column_search) - 1 == $i)
// 				$this->db->group_end();
// 		}
// 		$i++;
// 	}

// 	if (isset($_POST['order'])) {
// 		$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
// 	} else if (isset($this->order)) {
// 		$order = $this->order;
// 		$this->db->order_by(key($order), $order[key($order)]);
// 	}
// }
 