<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pcharges_model extends CI_Model {

	function submit_ha_request($billing_id) {
		$personal_charge = floatval(str_replace(',','',$this->input->post('personal_charge')));
		$requested_amount = floatval(str_replace(',','',$this->input->post('requested_amount')));

		$this->db->set('personal_charge', $personal_charge);
		$this->db->set('excess_amount', $requested_amount);
		$this->db->set('status','For Advance');
		$this->db->set('requested_on',date('Y-m-d'));
		$this->db->where('billing_id',$billing_id);
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
		$this->db->join('billing as tbl_2','tbl_1.billing_id = tbl_2.billing_id');
		$this->db->where('tbl_1.emp_id',$emp_id);
		$this->db->where('tbl_1.status',$status); 
		if ($status == 'For Advance') {
			$this->db->group_start();
			$this->db->or_where('tbl_1.ebm_status', ''); 
			$this->db->group_end();
		}
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

	function _get_appr_requested_datatables_query($status,$emp_id) {
		$this->db->from('cash_advance as tbl_1');
		$this->db->join('billing as tbl_2','tbl_1.billing_id = tbl_2.billing_id');
		$this->db->where('tbl_1.emp_id',$emp_id);
		$this->db->where('tbl_1.ebm_status',$status); 
	}

	function get_appr_requested_advance($status, $emp_id) {
		$this->_get_appr_requested_datatables_query($status,$emp_id);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_appr_requested_filtered($status,$emp_id) {
		$this->_get_appr_requested_datatables_query($status,$emp_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_appr_all_requested($status,$emp_id) {
		$this->db->from('cash_advance');
		$this->db->where('emp_id', $emp_id);
		$this->db->where('status', $status);
		return $this->db->count_all_results();
	}
}
