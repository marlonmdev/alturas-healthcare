<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pcharges_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/pcharges_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'member') {
			redirect(base_url());
		}
	}

	function fetch_unpaid_personal_charges() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->pcharges_model->get_personal_charges($emp_id);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];
			
				$added_on = date("m/d/Y", strtotime($pcharge['billed_on']));

				$billing_id = $this->myhash->hasher($pcharge['billing_id'],'encrypt');

				$custom_status = '<div class="text-left"><span class="badge rounded-pill bg-warning">Billed</span></div>';
				
				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPChargeModal(\''. $billing_id .'\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				$custom_actions .= '<a href="JavaScript:void(0)" onclick="tagPersonalChargeModal(\''. $billing_id .'\',\''.number_format($pcharge['personal_charge'],2,'.',',').'\')" data-bs-toggle="tooltip" title="Tag for Healthcare Advance"><i class="mdi mdi-tag-plus fs-2 text-success ps-2"></i></a>';

				// this data will be rendered to the datatable
				$row[] = $pcharge['billing_id'];
				$row[] = $pcharge['billing_no'];
				$row[] = number_format($pcharge['personal_charge'],2,'.',',');
				$row[] = $added_on; 	
				$row[] = $custom_status; 	
				$row[] = $custom_actions; 	
				$data[] = $row;	
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pcharges_model->count_all_charge($emp_id),
			"recordsFiltered" => $this->pcharges_model->count_charge_filtered($emp_id),
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_requested_personal_charges() {
		$token = $this->security->get_csrf_hash();
		$status = 'For Advance';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->pcharges_model->get_requested_advance($status, $emp_id);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];
			
			$billing_id = $this->myhash->hasher($pcharge['billing_id'], 'encrypt');

			$added_on = date("m/d/Y", strtotime($pcharge['requested_on']));

			$custom_status = '<div class="text-left"><span class="badge rounded-pill bg-success">For Approval</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPChargeModal(\'' . $billing_id . '\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $pcharge['billing_id'];
			$row[] = $pcharge['billing_no'];
			$row[] = $pcharge['excess_amount'];
			$row[] = $added_on;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pcharges_model->count_all_requested($status, $emp_id),
			"recordsFiltered" => $this->pcharges_model->count_requested_filtered($status, $emp_id),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function submit_healthcare_advance() {
		$token = $this->security->get_csrf_hash();
		$personal_charge = $this->input->post('personal_charge',TRUE);
		$billing_id = $this->myhash->hasher($this->input->post('billing_id'),'decrypt');
		$billing = $this->pcharges_model->get_billing_info($billing_id);
		$submitted = $this->pcharges_model->submit_ha_request($billing['loa_id'],$billing['noa_id']);

		if(!$submitted){
			echo json_encode([
				'token' => $token,
				'status' => 'error',
				'message' => 'Failed to Submit Request!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Healthcare Advance Submitted Successfully!'
			]);
		}
	}

	function fetch_charges_details() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(5),'decrypt');
		$billing = $this->pcharges_model->get_charge_details($billing_id);

		foreach($billing as $bill){
			if(!empty($bill['loa_id'])){
				$loa_noa_no = $bill['loa_no'];
			}else{
				$loa_noa_no = $bill['noa_no'];
			}
			$output = [
				'loa_noa_no' => $loa_noa_no,
				'token' => $token,
				'status' => $bill['status'],
				'billing_no' => $bill['billing_no'],
				'work_related' => $bill['work_related'],
				'percentage' => $bill['percentage'],
				'before_remaining_bal' => number_format($bill['before_remaining_bal'],2,'.',','),
				'net_bill' => number_format($bill['net_bill'],2,'.',','),
				'company_charge' => number_format($bill['company_charge'],2,'.',','),
				'personal_charge' => number_format($bill['personal_charge'],2,'.',','),
				'after_remaining_bal' => number_format($bill['after_remaining_bal'],2,'.',','),
				'billed_on' => date('F d, Y', strtotime($bill['billed_on'])),
			];
		}
		
		echo json_encode($output);
	}
}
