<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function noa_number($input, $pad_len = 8, $prefix = null) {
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
		/* The below code is a PHP code that is used to save the data from the form to the database. */
		$this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$default_status = 'Pending';

		$this->load->library('form_validation');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');

		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'chief_complaint_error' => form_error('chief-complaint'),
				'admission_date_error' => form_error('admission-date'),
			];
		} else {
			// check if the selected hospital exist from database
			$hospital_id = $this->input->post('hospital-name');
			$hp_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
			if (!$hp_exist) {
				$response = [
					'status' => 'save-error', 
					'message' => 'Hospital Does Not Exist'
				];
				echo json_encode($response);
				exit();
			} else {
				// select the max noa_id from DB
				$result = $this->noa_model->db_get_max_noa_id();
				$max_loa_id = !$result ? 0 : $result['noa_id'];
				$add_loa = $max_loa_id + 1;
				// call function loa_number
				$noa_no = $this->noa_number($add_loa, 8, 'NOA-');

				$member_id = $this->myhash->hasher($this->input->post('member-id'), 'decrypt');
				$member = $this->noa_model->db_get_member_details($member_id);

				$post_data = [
					'noa_no' => $noa_no,
					'emp_id' => $member['emp_id'],
					'first_name' =>  $member['first_name'],
					'middle_name' =>  $member['middle_name'],
					'last_name' =>  $member['last_name'],
					'suffix' =>  $member['suffix'],
					'date_of_birth' => $member['date_of_birth'],
					'health_card_no' => $member['health_card_no'],
					'requesting_company' => $member['company'],
					'hospital_id' => $inputPost['hospital-name'],
					'admission_date' => $inputPost['admission-date'],
					'chief_complaint' => strip_tags($inputPost['chief-complaint']),
					'request_date' => date("Y-m-d"),
					'status' => $default_status,
					'requested_by' => $this->session->userdata('emp_id')
				];

				$saved = $this->noa_model->db_insert_noa_request($post_data);
				if (!$saved) {
					$response = [
						'status' => 'save-error', 
						'message' => 'NOA Request Failed'
					];
				}
				$response = [
					'status' => 'success', 
					'message' => 'NOA Request Save Successfully'
				];
			}
		}
		echo json_encode($response);
	}

	function fetch_all_pending_noa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$hcc_emp_id = $this->session->userdata('emp_id');
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$view_url = base_url() . 'healthcare-coordinator/noa/requested-loa/edit/' . $noa_id;

			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			if ($noa['requested_by'] !== $hcc_emp_id) {
				$custom_actions .= '<a class="me-2" disabled><i class="mdi mdi-pencil-circle fs-2 text-success icon-disabled"></i></a>';

				$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Tag LOA Charge Type"><i class="mdi mdi-tag-plus fs-2 text-primary"></i></a>';

				$custom_actions .= '<a disabled><i class="mdi mdi-delete-circle fs-2 icon-disabled"></i></a>';
			} else {
				$custom_actions .= '<a class="me-2" href="' . $view_url . '" data-bs-toggle="tooltip" title="Edit NOA" readonly><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

				$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Tag NOA Charge Type"><i class="mdi mdi-tag-plus fs-2 text-primary"></i></a>';

				$custom_actions .= '<a href="Javascript:void(0)" onclick="cancelNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Delete NOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';
			}

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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_approved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/noa/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_disapproved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}


	function fetch_all_completed_noa() {
		$this->security->get_csrf_hash();
		$status = 'Completed';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function get_pending_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
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
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_approved_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$birthday = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($birthday), date_create($today));
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
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_disapproved_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);

		$doctor_name = "";
		if ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$birthday = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($birthday), date_create($today));
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
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_completed_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$birthday = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($birthday), date_create($today));
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
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function edit_noa_request() {
		$this->load->model('healthcare_coordinator/setup_model');
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->noa_model->db_get_noa_info($noa_id);
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/edit_noa_request');
		$this->load->view('templates/footer');
	}

	function update_noa_request() {
		$this->security->get_csrf_hash();
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
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
			$post_data = [
				'hospital_id' => $inputPost['hospital-name'],
				'admission_date' => $inputPost['admission-date'],
				'chief_complaint' => strip_tags($inputPost['chief-complaint']),
			];
			$saved = $this->noa_model->db_update_noa_request($noa_id, $post_data);
			if ($saved) {
				$response = [
					'status' => 'success', 
					'message' => 'NOA Request Updated Successfully'
				];
			} else {
				$response = [
					'status' => 'save-error', 
					'message' => 'NOA Request Update Failed'
				];
			}
		}
		echo json_encode($response);
	}

	function cancel_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');

		$deleted = $this->noa_model->db_cancel_noa($noa_id);
		if ($deleted) {
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'NOA Request Cancelled Successfully'
			];
		} else {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'NOA Request Cancellation Failed'
			];
		}
		echo json_encode($response);
	}

	function generate_printable_noa() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['mbl'] = $this->noa_model->db_get_member_mbl($exist['emp_id']);
		$data['doc'] = $this->noa_model->db_get_doctor_by_id($exist['approved_by']);
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/noa/generate_printable_noa');
			$this->load->view('templates/footer');
		}
	}

	function set_charge_type(){
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->input->post('noa-id'), 'decrypt');
		$charge_type = $this->input->post('charge-type', TRUE);

		$this->form_validation->set_rules('charge-type', 'Charge Type', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'token' => $token, 
				'status' => 'error',
				'charge_type_error' => form_error('charge-type'),
			];
			echo json_encode($response);
		}else{
			$updated = $this->noa_model->db_update_noa_charge_type($noa_id, $charge_type);

			if (!$updated) {
				$response = [
					'token' => $token, 
					'status' => 'save-error', 
					'message' => 'Save Failed'
				];
			} else {
				$response = [
					'token' => $token, 
					'status' => 'success', 
					'message' => 'Saved Successfully'
				];
			}
			echo json_encode($response);
		}

	}

}
