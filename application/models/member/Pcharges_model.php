<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pcharges_model extends CI_Model {

	function _get_charges_datatables_query($emp_id) {
		$this->db->from('billing')
				->where('emp_id',$emp_id)
				->where('personal_charge !=', 0);;
	}

	function get_personal_charges($emp_id) {
		$this->_get_charges_datatables_query($emp_id);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_charge_filtered($emp_id) {
		$this->_get_charges_datatables_query($emp_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all_charge($emp_id) {
		$this->db->from('billing');
		$this->db->where('emp_id', $emp_id);
		$this->db->where('personal_charge !=', 0);
		return $this->db->count_all_results();
	}

	function get_billing_info($billing_id) {
		return $this->db->get_where('billing', ['billing_id' => $billing_id])->row_array();
	}

	function submit_ha_request($loa_id,$noa_id) {
		$this->db->set('status','For Advance');
		$this->db->set('requested_on',date('Y-m-d'));
		if(!empty($loa_id)){
			$this->db->where('loa_id',$loa_id);
		}else if(!empty($noa_id)){
			$this->db->where('noa_id',$noa_id);
		}
		return $this->db->update('cash_advance');
	}

	function get_charge_details($billing_id) {
		$this->db->select('*')
				->from('billing as tbl_1')
				->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left')
				->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
				->where('tbl_1.billing_id',$billing_id);
		return $this->db->get()->result_array();
	}

	function _get_requested_datatables_query($status,$emp_id) {
		$this->db->from('cash_advance as tbl_1');
		if('tbl_1.loa_id' != 0){
			$this->db->join('billing as tbl_2','tbl_1.loa_id = tbl_2.loa_id');
		}else if('tbl_1.noa_id' != 0){
			$this->db->join('billing as tbl_2','tbl_1.noa_id = tbl_2.noa_id');
		}
		$this->db->where('tbl_1.status',$status);
		$this->db->where('tbl_1.emp_id',$emp_id);
		
	}

	function get_requested_advance($status, $emp_id) {
		$this->_get_requested_datatables_query($status,$emp_id);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_requested_filtered($status,$emp_id) {
		$this->_get_requested_datatables_query($status,$emp_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all_requested($status,$emp_id) {
		$this->db->from('cash_advance');
		$this->db->where('emp_id', $emp_id);
		$this->db->where('status', $status);
		return $this->db->count_all_results();
	}
}
