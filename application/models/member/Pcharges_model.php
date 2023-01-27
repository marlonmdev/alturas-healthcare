<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pcharges_model extends CI_Model {

	// Start of server-side processing datatables
	var $table_1 = 'personal_charges';
	var $table_2 = 'billing';
	var $column_order = ['tbl_1.pcharge_id', 'tbl_1.billing_no', 'tbl_1.pcharge_amount', 'tbl_2.billing_date']; //set column field database for datatable orderable
	var $column_search = ['tbl_1.pcharge_id', 'tbl_1.billing_no', 'tbl_1.pcharge_amount', 'tbl_2.billing_date']; //set column field database for datatable searchable 
	var $order = ['tbl_1.pcharge_id' => 'desc']; // default order 

	private function _get_datatables_query($status, $emp_id) {
		$this->db->from($this->table_1 . ' as tbl_1');
		$this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.billing_no = tbl_2.billing_no');
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

	public function count_all($status, $emp_id) {
		$this->db->from($this->table_1);
		$this->db->where('status', $status);
		$this->db->where('emp_id', $emp_id);
		return $this->db->count_all_results();
	}
	// End of server-side processing datatabless


	public function db_get_personal_charges_info($emp_id) {
		$this->db->select('*');
		$this->db->from('personal_charges as tbl_1');
		$this->db->join('bliing as tbl_2', 'tbl_1.blling_no = tbl_2.billing_no');
		$this->db->where('emp_id', $emp_id);
		return $this->db->get()->result_array();
	}
}
