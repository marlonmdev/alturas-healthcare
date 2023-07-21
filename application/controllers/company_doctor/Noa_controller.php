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

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning pe-2"></i></a>';
			}else{
				$custom_actions .= '';
			}

			// if work_related field is set to either yes or no, show either disabled or not disabled approve button 
			if($noa['work_related'] == ''){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';

				$custom_actions .= '<a class="me-2" data-bs-toggle="tooltip" title="Charge type is not yet set by HRD Coordinator" disabled><i class="mdi mdi-thumb-up fs-2 icon-disabled"></i></a>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';

				$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="approveNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Approve NOA"><i class="mdi mdi-thumb-up fs-2 text-success"></i></a>';
			}

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
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'company-doctor/noa/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 ps-2 text-primary"></i></a>';

			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning ps-2"></i></a>';
			}else{
				$custom_actions .= '';
			}
			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $expiry_date;
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

	function generate_printable_noa() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['mbl'] = $this->noa_model->db_get_member_mbl($exist['emp_id']);
		$data['doc'] = $this->noa_model->db_get_doctor_by_id($exist['approved_by']);

		if($exist['position_level'] <= 6){
			$data['room_type'] = 'Payward';
		}else if($exist['position_level'] > 6 && $exist['position_level'] < 10){
			$data['room_type'] = 'Semi-private';
		}else if($exist['position_level'] > 9){
			$data['room_type'] = 'Regular Private';
		}

		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('company_doctor_panel/noa/generate_printable_noa');
			$this->load->view('templates/footer');
		}
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

	function fetch_paid_noa() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
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

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPaidNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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

	function fetch_billed_noa() {
		$this->security->get_csrf_hash();
		$status = ['Billed', 'Payable', 'Payment'];
		$list = $this->noa_model->get_billed_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">Billed</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $expiry_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all_billed($status),
			"recordsFiltered" => $this->noa_model->count_filtered_billed($status),
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

		/* Checking if the status is pending and the work related is not empty. If it is, then it will set
		the req_stat to for approval. If not, then it will set the req_stat to the status. */
		if($row['status'] == 'Pending' && $row['work_related'] != ''){
			$req_stat = 'for Approval';
		}else{
			$req_stat = $row['status'];
		}
		$paid_on = '';
		$bill = $this->noa_model->get_billing_info($row['noa_id']);
		if(!empty($bill)){
			$billed_on = date('F d, Y', strtotime($bill['billed_on']));
			$paid = $this->noa_model->get_paid_date($bill['details_no']);
			if(!empty($paid)){
				$paid_on = date('F d, Y', strtotime($paid['date_add']));
			}else{
				$paid_on = '';
			}
		}else{
			$billed_on = '';
		}

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
			'req_status' => $req_stat,
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'billed_on' => $billed_on,
			'paid_on' => $paid_on,
		);

		echo json_encode($response);
	}

	// public function approve_noa_request() {
	// 	$token = $this->security->get_csrf_hash();
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$approved_by = $this->session->userdata('doctor_id');
	// 	$approved_on = date("Y-m-d");
	// 	$approved = $this->noa_model->db_approve_noa_request($noa_id, $approved_by, $approved_on);
	// 	if ($approved) {
	// 		$response = ['token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully'];
	// 	} else {
	// 		$response = ['token' => $token, 'status' => 'error', 'message' => 'Unable to Approve NOA Request!'];
	// 	}
	// 	echo json_encode($response);
	// }


	function approve_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->input->post('noa-id', TRUE), 'decrypt');
		$expiration_type = $this->input->post('expiration-type', TRUE);
		$expiration_date = $this->input->post('expiration-date', TRUE);
		$approved_by = $this->session->userdata('doctor_id');
		$approved_on = date("Y-m-d");

		if($expiration_type == 'custom'){
			$this->form_validation->set_rules('expiration-date', 'Custom Expiration Date', 'required');
			if ($this->form_validation->run() == FALSE) {
				$response = [
					'token' 							  => $token,
					'status'                => 'error',
					'expiration_date_error' => form_error('expiration-date'),
				];

				echo json_encode($response);
				exit();
			}
		}

		switch($expiration_type){
			case 'default': 
					$default = strtotime('+1 week', strtotime($approved_on));
					$expired_on = date('Y-m-d', $default);
				break;
			case '2 weeks':
					$expires = strtotime('+2 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case '3 weeks':
					$expires = strtotime('+3 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case '4 weeks':
					$expires = strtotime('+4 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case 'custom':
					$expired_on = date('Y-m-d', strtotime($expiration_date));
				break;
		}

		$data = [
			'status'          => 'Approved',
			'approved_by'     => $approved_by,
			'approved_on'     => $approved_on,
			'expiration_date' => $expired_on
		];

		$approved = $this->noa_model->db_approve_noa_request($noa_id, $data);
		if ($approved) {
			$response = ['token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully'];
		} else {
			$response = ['token' => $token, 'status' => 'save-error', 'message' => 'Unable to Approve NOA Request'];
		}
		echo json_encode($response);
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

	function fetch_member_details() {
		$this->security->get_csrf_hash();
        $search_data = $this->input->post('search');
        $result = $this->noa_model->get_autocomplete($search_data);
        if (!empty($result)) {
            foreach ($result as $row) :
                $member_id = $this->myhash->hasher($row['member_id'], 'encrypt');
                echo '<strong class="d-block mx-2 p-1 my-1"><a href="#" onclick="getMemberValues(\'' . $member_id . '\')" class="text-secondary" data-toggle="tooltip" data-placement="top" title="Click to fill form with Data">'
                    . $row['first_name'] . ' '
                    . $row['middle_name'] . ' '
                    . $row['last_name'] . ' '
                    . $row['suffix'] . '</a></strong>';
            endforeach;
        } else {
            echo "<p class='text-center mt-1'><em>No data found...</em></p>";
        }
	}

	function fetch_auto_complete() {
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $row = $this->noa_model->db_get_member_details($member_id);
        $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
        $current_date = date("d-m-Y");
        $diff = date_diff(date_create($birth_date), date_create($current_date));
        $age = $diff->format("%y");

        $response = [
            'status' => 'success',
            'token' => $this->security->get_csrf_hash(),
            'member_id' => $row['member_id'],
            'emp_id' => $row['emp_id'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'suffix' => $row['suffix'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth'],
            'age' => $age,
            'philhealth_no' => $row['philhealth_no'],
            'blood_type' =>  $row['blood_type'],
            'home_address' => $row['home_address'],
            'city_address' => $row['city_address'],
            'contact_no' => $row['contact_no'],
            'email' => $row['email'],
            'contact_person' => $row['contact_person'],
            'contact_person_addr' => $row['contact_person_addr'],
            'contact_person_no' => $row['contact_person_no'],
            'health_card_no' => $row['health_card_no'],
            'requesting_company' => $row['company'],
            'mbl' => number_format($row['remaining_balance'],2,'.',',')
        ];
        echo json_encode($response);
	}

	function noa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function submit_noa_override() {
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
					$max_noa_id = !$result ? 0 : $result['noa_id'];
					$add_noa = $max_noa_id + 1;
					$current_year = date('Y');
					// call function loa_number
					$noa_no = $this->noa_number($add_noa, 7, 'NOA-'.$current_year);
					$member_id = $inputPost['member-id'];
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
						'admission_date' => date('Y-m-d', strtotime($inputPost['admission-date'])),
						'chief_complaint' => strip_tags($inputPost['chief-complaint']),
						'request_date' => date("Y-m-d"),
						'status' => $default_status,
						'requested_by' => $this->session->userdata('doctor_id'),
						'override_by' => $inputPost['doctor-id']
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
}
