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
		$status = 'Unpaid';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->pcharges_model->get_datatables($status, $emp_id);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];
			$pcharge_id = $this->myhash->hasher($pcharge['pcharge_id'], 'encrypt');

			$billing_date = date("m/d/Y", strtotime($pcharge['billing_date']));

			if ($pcharge['status']  == 'Unpaid') {
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $pcharge['status'] . '</span></div>';
			} else {
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $pcharge['status'] . '</span></div>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPchargeModal(\'' . $pcharge_id . '\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="bi bi-zoom-in icon-primary"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $pcharge['pcharge_id'];
			$row[] = $pcharge['billing_no'];
			$row[] = $billing_date;
			$row[] = $pcharge['pcharge_amount'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pcharges_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->pcharges_model->count_filtered($status, $emp_id),
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_paid_personal_charges() {
		$token = $this->security->get_csrf_hash();
		$status = 'Paid';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->pcharges_model->get_datatables($status, $emp_id);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];
			$pcharge_id = $this->myhash->hasher($pcharge['pcharge_id'], 'encrypt');

			$billing_date = date("m/d/Y", strtotime($pcharge['billing_date']));

			if ($pcharge['status']  == 'Unpaid') {
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $pcharge['status'] . '</span></div>';
			} else {
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $pcharge['status'] . '</span></div>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPchargeModal(\'' . $pcharge_id . '\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="bi bi-zoom-in icon-primary"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $pcharge['pcharge_id'];
			$row[] = $pcharge['billing_no'];
			$row[] = $billing_date;
			$row[] = $pcharge['pcharge_amount'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pcharges_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->pcharges_model->count_filtered($status, $emp_id),
			"data" => $data,
		];
		echo json_encode($output);
	}
}
