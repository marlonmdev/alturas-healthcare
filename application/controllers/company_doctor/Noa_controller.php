<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

	function fetch_all_pending_noa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="approveNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Approve NOA"><i class="mdi mdi-thumb-up fs-2 text-success"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="disapproveNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Disapprove NOA"><i class="mdi mdi-thumb-down fs-2 text-danger"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_all_approved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_all_disapproved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_all_closed_noa() {
		$this->security->get_csrf_hash();
		$status = 'Closed';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewClosedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function get_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		$response = array(
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'noa_id' => $row['noa_id'],
			'noa_no' => $row['noa_no'],
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'hospital_name' => $row['hp_name'],
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'chief_complaint' => $row['chief_complaint'],
			// Full Month Date Year Format (F d Y)
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'work_related' => $row['work_related'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		);

		echo json_encode($response);
	}

	// public function approve_noa_request1() {
	// 	$token = $this->security->get_csrf_hash();
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$approved_by = 'Dr. ' . $this->session->userdata('fullname');
	// 	$approved_on = date("Y-m-d");
	// 	$approved = $this->noa_model->db_approve_noa_request($noa_id, $approved_by, $approved_on);
	// 	if ($approved) {
	// 		$response = array('token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully');
	// 	} else {
	// 		$response = array('token' => $token, 'status' => 'error', 'message' => 'Unable to Approve NOA Request!');
	// 	}
	// 	echo json_encode($response);
	// }

	public function approve_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$work_related = $this->input->post('work-related');
		$approved_by = $this->session->userdata('doctor_id');
		$approved_on = date("Y-m-d");
		$this->form_validation->set_rules('work-related', 'Work Related', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'token' => $token,
				'status' => 'error',
				'work_related_error' => form_error('work-related'),
			);
			echo json_encode($response);
		} else {
			$approved = $this->noa_model->db_approve_noa_request($noa_id, $work_related, $approved_by, $approved_on);
			if ($approved) {
				$response = array('token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully');
			} else {
				$response = array('token' => $token, 'status' => 'error', 'message' => 'Unable to Approve NOA Request!');
			}
			echo json_encode($response);
		}
	}

	public function disapprove_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$disapprove_reason = $this->input->post('disapprove-reason');
		$disapproved_by = $this->session->userdata('doctor_id');
		$disapproved_on = date("Y-m-d");
		$this->form_validation->set_rules('disapprove-reason', 'Reason for Disapproval', 'required|max_length[500]');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'token' => $token,
				'status' => 'error',
				'disapprove_reason_error' => form_error('disapprove-reason'),
			);
			echo json_encode($response);
		} else {
			$disapproved = $this->noa_model->db_disapprove_noa_request($noa_id, $disapproved_by, $disapprove_reason, $disapproved_on);
			if (!$disapproved) {
				$response = array('token' => $token, 'status' => 'error', 'message' => 'Unable to Disapprove NOA Request!');
			}
			$response = array('token' => $token, 'status' => 'success', 'message' => 'NOA Request Disapproved Successfully');
			echo json_encode($response);
		}
	}
}
