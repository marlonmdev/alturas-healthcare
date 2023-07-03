<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History_model extends CI_Model {

	public function get_member_mbl($emp_id) {
		$this->db->where('emp_id', $emp_id);
		$query = $this->db->get('max_benefit_limits');
		return $query->row_array();
	}

	var $table_1 = 'billing'; 
	var $table_2 = 'loa_requests'; 
	var $table_3 = 'noa_requests';

	var $column_order_history = array('loa_no','noa_no', 'net_bill','billing_id','billing_no','tbl_1.status','tbl_1.request_date');
	var $column_search_history = array('loa_no','noa_no', 'net_bill','billing_id','billing_no','tbl_1.status','tbl_1.request_date'); //set column field database for datatable searchable 
	var $order_history = array('billing_id' => 'desc'); // default order 
	
	private function _get_history_datatables_query($emp_id) {
		// Select all data from the first table
		$this->db->select('tbl_1.status as tbl1_status, tbl_1.loa_id as tbl1_loa_id, tbl_1.request_date as tbl1_request_date, tbl_1.*, tbl_2.*, tbl_3.*');
		$this->db->from($this->table_1 . ' as tbl_1');
		$this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left');
		$this->db->join($this->table_3 . ' as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id','left');
		$this->db->where('tbl_1.emp_id', $emp_id);
		$this->db->where('YEAR(billed_on)', date('Y'));
		$i = 0;
		// loop column 
		foreach ($this->column_search_history as $item) {
		// if datatable send POST for search
		if ($_POST['search']['value']) {
			// first loop
			if ($i === 0) {
			$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
			$this->db->like($item, $_POST['search']['value']);
			} else {
			$this->db->or_like($item, $_POST['search']['value']);
			}
  
			if (count($this->column_search_history) - 1 == $i) //last loop
			$this->db->group_end(); //close bracket
		}
		$i++;
		}

		if ($this->input->post('start_date')) {
			$startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
			$this->db->where('tbl_1.request_date >=', $startDate);
		}

		if ($this->input->post('end_date')){
			$endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
			$this->db->where('tbl_1.request_date <=', $endDate);
		}
  
		// here order processing
		// if (isset($_POST['order'])) {
		// $this->db->order_by($this->column_order_history[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } else if (isset($this->order_history)) {
		// $order = $this->order_history;
		// $this->db->order_by(key($order), $order[key($order)]);
		// }
	   
	}
  
	
	function get_history_datatables($emp_id) {
		$this->_get_history_datatables_query($emp_id);
		if ($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
  
	function count_history_filtered($emp_id) {
		$this->_get_history_datatables_query($emp_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
  
	function count_all_history($emp_id) {
		$this->db->from($this->table_1)
				->where('emp_id', $emp_id);
		return $this->db->count_all_results();
	}
	
}
