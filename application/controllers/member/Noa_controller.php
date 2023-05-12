<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'member') {
			redirect(base_url());
		}
	}

	function noa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function date_valid($date) {
		$day = (int) substr($date, 0, 2);
		$month = (int) substr($date, 3, 2);
		$year = (int) substr($date, 6, 4);
		return checkdate($month, $day, $year);
	}

	function submit_noa_request() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$member = $this->noa_model->db_get_member_infos($emp_id);
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$default_status = 'Pending';

		$this->load->library('form_validation');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'chief_complaint_error' => form_error('chief-complaint'),
				'admission_date_error' => form_error('admission-date'),
			);
			echo json_encode($response);
			exit();
		} else {
			// check if the selected hospital exist from database
			$hospital_id = $this->input->post('hospital-name');
			$hp_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
			if (!$hp_exist) {
				$response = array('status' => 'save-error', 'message' => 'Hospital Does Not Exist');
				echo json_encode($response);
				exit();
			} else {
				// select the max noa_id from DB
				$result = $this->noa_model->db_get_max_noa_id();
				$max_noa_id = !$result ? 0 : $result['noa_id'];
				$add_noa = $max_noa_id + 1;
				$current_year = date('Y');
				// call function loa_number
				$noa_no = $this->noa_number($add_noa, 7, 'NOA-'.$current_year);

				$post_data = array(
					'noa_no' => $noa_no,
					'emp_id' => $emp_id,
					'first_name' =>  $member->first_name,
					'middle_name' =>  $member->middle_name,
					'last_name' =>  $member->last_name,
					'suffix' =>  $member->suffix,
					'date_of_birth' => $member->date_of_birth,
					'health_card_no' => $member->health_card_no,
					'requesting_company' => $member->company,
					'hospital_id' => $inputPost['hospital-name'],
					'admission_date' => $inputPost['admission-date'],
					'chief_complaint' => strip_tags($inputPost['chief-complaint']),
					'request_date' => date("Y-m-d"),
					'status' => $default_status,
					'requested_by' => $emp_id,
				);

				$saved = $this->noa_model->db_insert_noa_request($post_data);
				if (!$saved) {
					$response = ['status' => 'save-error', 'message' => 'NOA Request Failed'];
				}
				$response = ['status' => 'success', 'message' => 'NOA Request Save Successfully'];
			}
			echo json_encode($response);
		}
	}

	function edit_noa_request() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['page_title'] = 'Alturas Healthcare - Member';
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['hospitals'] = $this->noa_model->db_get_all_hospitals();
		$data['costtypes'] = $this->noa_model->db_get_all_cost_types();
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('member_panel/noa/edit_noa_request');
			$this->load->view('templates/footer');
		}
	}

	function update_noa_request() {
		$token = $this->security->get_csrf_hash();
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('admission-date', 'Request Date of  Availment', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'admission_date_error' => form_error('admission-date'),
				'chief_complaint_error' => form_error('chief-complaint'),
			];
		} else {
			$hospital_id = $this->input->post('hospital-name');
			$hospital_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
			if (!$hospital_exist) {
				$response = ['status' => 'save-error', 'message' => 'Hospital Does Not Exist'];
			} else {
				$post_data = [
					'hospital_id' => $inputPost['hospital-name'],
					'admission_date' => $inputPost['admission-date'],
					'chief_complaint' => strip_tags($inputPost['chief-complaint']),
				];
				$updated = $this->noa_model->db_update_noa_request($noa_id, $post_data);
				if (!$updated) {
					$response = array('status' => 'save-error', 'message' => 'NOA Request Update Failed');
				}
				$response = array('status' => 'success', 'message' => 'NOA Request Updated Successfully');
			}
		}
		echo json_encode($response);
	}


	function fetch_pending_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->noa_model->db_get_pending_noa($emp_id);
		$result = array();
		foreach ($resultList as $key => $value) {
			$noa_id = $this->myhash->hasher($value['noa_id'], 'encrypt');
			// this is for datatable values
			$custom_noa_no = '<mark class="bg-primary text-white">'.$value['noa_no'].'</mark>';

			$button = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$button .= '<a class="me-2" href="' . base_url() . 'member/requested-noa/edit/' . $noa_id . '" data-bs-toggle="tooltip" title="Edit NOA"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$button .= '<a href="JavaScript:void(0)" onclick="cancelPendingNoa(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Delete NOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				'<span class="badge rounded-pill bg-warning">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_approved_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->noa_model->db_get_approved_noa($emp_id);
		$result = array();
		foreach ($resultList as $key => $value) {
			$noa_id = $this->myhash->hasher($value['noa_id'], 'encrypt');

			$custom_noa_no = '<mark class="bg-primary text-white">'.$value['noa_no'].'</mark>';

			$button = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// $button .= '<a href="' . base_url() . 'member/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				'<span class="badge rounded-pill bg-success">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_disapproved_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->noa_model->db_get_disapproved_noa($emp_id);
		$result = array();
		foreach ($resultList as $key => $value) {
			$noa_id = $this->myhash->hasher($value['noa_id'], 'encrypt');

			$custom_noa_no = '<mark class="bg-primary text-white">'.$value['noa_no'].'</mark>';

			$button = '<a href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				'<span class="badge rounded-pill bg-danger">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_completed_noa() {
		$token = $this->security->get_csrf_hash();
		$status = 'Completed';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->noa_model->get_datatables($status, $emp_id);
		$data = array();
		foreach ($list as $noa) {
			$row = array();
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->noa_model->count_filtered($status, $emp_id),
			"data" => $data,
		);

		echo json_encode($output);
	}


	function get_pending_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('member/noa_model');
		$row = $this->noa_model->db_get_noa_info($noa_id);

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		$response = [
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
			'req_status' => $row['work_related'] != '' ? 'for Approval': $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage']
		];
		echo json_encode($response);
	}

	function get_approved_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('member/noa_model');
		$row = $this->noa_model->db_get_approved_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "";
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
			'percentage' => $row['percentage'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
		);
		echo json_encode($response);
	}

	function get_disapproved_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('member/noa_model');
		$row = $this->noa_model->db_get_disapproved_noa_info($noa_id);

		$doctor_name = "";
		if ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "";
		}

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		$response = [
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
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
		];
		echo json_encode($response);
	}

	function get_completed_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('member/noa_model');
		$row = $this->noa_model->db_get_closed_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "";
		}

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		$response = [
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
			'percentage' => $row['percentage'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
		];
		echo json_encode($response);
	}

	function get_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$this->load->model('member/noa_model');
		$row = $this->noa_model->db_get_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "";
		}

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		$response = [
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
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
		];
		echo json_encode($response);
	}

	function cancel_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$deleted = $this->noa_model->db_cancel_noa($noa_id);
		if ($deleted) {
			$response = array('token' => $token, 'status' => 'success', 'message' => 'NOA Request Cancelled Successfully');
		} else {
			$response = array('token' => $token, 'status' => 'error', 'message' => 'NOA Request Cancellation Failed');
		}
		echo json_encode($response);
	}

	function generate_printable_noa() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['page_title'] = 'Alturas Healthcare - Member';
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['mbl'] = $this->noa_model->db_get_member_mbl($exist['emp_id']);
		$data['doc'] = $this->noa_model->db_get_doctor_by_id($exist['approved_by']);
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('member_panel/noa/generate_printable_noa');
			$this->load->view('templates/footer');
		}
	}
}
