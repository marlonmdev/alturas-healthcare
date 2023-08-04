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

	function check_hospital_receipt($str) {
		// $is_accredited = $this->session->userdata('is_accredited');
		// var_dump('is_accredited',$is_accredited);
		// if($is_accredited === "false"){
			
			if (isset($_FILES['hospital-receipt']['name']) && !empty($_FILES['hospital-receipt']['name'])) {
				return true;
			} else {
				$this->form_validation->set_message('check_hospital_receipt', 'Please choose Hospital Receipt file to upload.');
				return false;
			}
		// }
		// $this->session->unset_userdata('is_accredited');
	}

	function submit_noa_request() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$member = $this->noa_model->db_get_member_infos($emp_id);
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$medical_services = json_decode($this->input->post('noa-med-services'), TRUE);
		$default_status = 'Pending';
		$is_accredited = json_decode($_POST['is_accredited'],true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');
		$this->form_validation->set_rules('healthcare-provider-category', 'Healthcare Provider Category', 'required');
		
		if(!$is_accredited){
			// var_dump('is_accredited',$is_accredited);
			$this->form_validation->set_rules('noa-med-services', 'Medical Services', 'required');
			$this->form_validation->set_rules('hospital-receipt', '', 'callback_check_hospital_receipt');
			$this->form_validation->set_rules('hospital-bill', 'Hospital Bill', 'required');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'chief_complaint_error' => form_error('chief-complaint'),
				'admission_date_error' => form_error('admission-date'),
				'med_services_error' => form_error('noa-med-services'),
				'hospital_receipt_error' => form_error('hospital-receipt'),
				'hospital_bill_error' => form_error('hospital-bill'),
				'healthcare_provider_category_error' => form_error('healthcare-provider-category'),
			);
			echo json_encode($response);
			exit();
		}else{
			// check if the selected hospital exist from database
			$config['upload_path'] = './uploads/hospital_receipt/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
		  
			if (!$this->upload->do_upload('hospital-receipt') && !$is_accredited) {
				$response = [
					'status'  => 'save-error',
					'message' => 'PDF Bill Upload Failed'
				];
	
			} else {
				$upload_data = $this->upload->data();
				$pdf_file = $upload_data['file_name'];

				$services = [];
				if(!$is_accredited){
					foreach($medical_services as $value){
						$services[] = $value['value']; 
					}
				}
				
				$hospital_id = $this->input->post('hospital-name');
				$hp_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
				if (!$hp_exist) {
					$response = array('status' => 'save-error', 'message' => 'Hospital Does Not Exist');
					echo json_encode($response);
					exit();
				}else{
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
						'hospital_bill' =>  !$is_accredited ? $inputPost['hospital-bill'] : null,
						'status' => $default_status,
						'requested_by' => $emp_id,
						'type_request' => $inputPost['type_request'],
						'is_manual' => $is_accredited ? 0 : 1,
						'medical_services' => ($services!=="")?implode(';', $services):"", 
						'hospital_receipt' => isset($pdf_file)?$pdf_file:null,
					);

					$saved = $this->noa_model->db_insert_noa_request($post_data);
					if (!$saved) {
						$response = ['status' => 'save-error', 'message' => 'NOA Request Failed'];
					}
					$response = ['status' => 'success', 'message' => 'NOA Request Save Successfully'];
				}
		}
			echo json_encode($response);
		}
	}

	function edit_noa_request() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['page_title'] = 'Alturas Healthcare - Member';
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['ahcproviders'] = $this->noa_model->db_get_affiliated_healthcare_providers();
		$data['hcproviders'] = $this->noa_model->db_get_not_affiliated_healthcare_providers();
		$data['old_services'] = explode(';',$exist['medical_services']);
		$data['is_accredited'] = ($exist['is_manual'])?true:false;
		// $data['hospitals'] = $this->noa_model->db_get_all_hospitals();
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
		$medical_services = json_decode($this->input->post('noa-med-services'), TRUE);
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$is_accredited =json_decode($_POST['is_accredited'],true);
		$is_hr_has_data = json_decode($_POST['is_hr_has_data'],true);
		
		// $this->session->set_userdata(['is_accredited'=> $is_accredited,
		// 									'hospital_receipt' => $_FILES['hospital-receipt']['name'],
		// 									'is_hr_has_data' => $is_hr_has_data]);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');
		$this->form_validation->set_rules('healthcare-provider-category', 'Healthcare Provider Category', 'required');
		
		if(!$is_accredited){
			// var_dump('is_accredited',$is_accredited);
			$this->form_validation->set_rules('noa-med-services', 'Medical Services', 'required');
			if((!$is_hr_has_data && isset($_FILES['hospital-receipt']['name'])) || (!$is_hr_has_data && !isset($_FILES['hospital-receipt']['name']))){
				$this->form_validation->set_rules('hospital-receipt', '', 'callback_check_hospital_receipt');
			}
			
			$this->form_validation->set_rules('hospital-bill', 'Hospital Bill', 'required');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'chief_complaint_error' => form_error('chief-complaint'),
				'admission_date_error' => form_error('admission-date'),
				'med_services_error' => form_error('noa-med-services'),
				'hospital_receipt_error' => form_error('hospital-receipt'),
				'hospital_bill_error' => form_error('hospital-bill'),
				'healthcare_provider_category_error' => form_error('healthcare-provider-category'),
			);
			echo json_encode($response);
			exit();
		}else{
			// check if the selected hospital exist from database
			$config['upload_path'] = './uploads/hospital_receipt/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['encrypt_name'] = TRUE;
			
			$this->load->library('upload', $config);
			$old_hr_file = $inputPost['file-attachment-receipt'];
			if (!$this->upload->do_upload('hospital-receipt') && !$is_accredited && isset($_FILES['hospital-receipt']['name']) && !$is_hr_has_data) {
					$response = [
						'status'  => 'save-error',
						'message' => 'PDF Bill Upload Failed'
					];
			}
			 else {
				$upload_data = $this->upload->data();
				$pdf_file = $upload_data['file_name'];

				$services = [];
				if(!$is_accredited){
					foreach($medical_services as $value){
						$services[] = $value['value']; 
					}
				}
				
				$hospital_id = $this->input->post('hospital-name');
				$hp_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
				if (!$hp_exist) {
					$response = array('status' => 'save-error', 'message' => 'Hospital Does Not Exist');
					echo json_encode($response);
					exit();
				}else{
					$post_data = [];
					if($is_accredited){
						$post_data = [
							'hospital_id' => $inputPost['hospital-name'],
							'admission_date' => $inputPost['admission-date'],
							'chief_complaint' => strip_tags($inputPost['chief-complaint']),
							'medical_services' => null, 
							'hospital_receipt' => null,
							'hospital_bill' =>   null,
							'is_manual' =>  0,
						];
					}else{
						$post_data = [
							'hospital_id' => $inputPost['hospital-name'],
							'admission_date' => $inputPost['admission-date'],
							'chief_complaint' => strip_tags($inputPost['chief-complaint']),
							'medical_services' => ($services!=="")?implode(';', $services):"", 
							'hospital_receipt' => $pdf_file,
							'hospital_bill' =>   $inputPost['hospital-bill'],
							'is_manual' => 1,
						];
					}
				
				$updated = $this->noa_model->db_update_noa_request($noa_id, $post_data);
				// var_dump('noa_id',$noa_id);
				if (!$updated) {
					$response = array('status' => 'save-error', 'message' => 'NOA Request Update Failed');
				}else{
					$response = array('status' => 'success', 'message' => 'NOA Request Updated Successfully');
				
					if($old_hr_file !== ''){
					$file_path = './uploads/hospital_receipt/' . $old_hr_file;
					file_exists($file_path) ? unlink($file_path) : '';
				}
					
				}
				
				}
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

			if($value['spot_report_file'] && $value['incident_report_file'] != ''){
				$button .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $value['work_related'] . '\',\'' . $value['percentage'] . '\',\'' . $value['spot_report_file'] . '\',\'' . $value['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$button .= '';
			}

			$button .= '<a class="me-2" href="' . base_url() . 'member/requested-noa/edit/' . $noa_id . '" data-bs-toggle="tooltip" title="Edit NOA"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$button .= '<a href="JavaScript:void(0)" onclick="cancelPendingNoa(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Delete NOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';
			
			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];
			$view_receipt = '';

			if($value['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}

			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				$view_receipt,
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

			if($value['spot_report_file'] && $value['incident_report_file'] != ''){
				$button .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $value['work_related'] . '\',\'' . $value['percentage'] . '\',\'' . $value['spot_report_file'] . '\',\'' . $value['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$button .= '';
			}


			// $button .= '<a href="' . base_url() . 'member/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];
			$view_receipt = '';
			if($value['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				$view_receipt,
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
			$view_receipt = '';
			if($value['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				$view_receipt,
				'<span class="badge rounded-pill bg-danger">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_billed_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->noa_model->db_get_billed_noa($emp_id);
		$result = array();
		foreach ($resultList as $key => $value) {
			$noa_id = $this->myhash->hasher($value['noa_id'], 'encrypt');

			$custom_noa_no = '<mark class="bg-primary text-white">'.$value['noa_no'].'</mark>';

			$button = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// $button .= '<a href="' . base_url() . 'member/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];
			$view_receipt = '';
			if($value['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				$view_receipt,
				'<span class="badge rounded-pill bg-success">Billed</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_paid_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->noa_model->get_paid_noa($emp_id);
		$result = array();
		foreach ($resultList as $key => $value) {
			$noa_id = $this->myhash->hasher($value['noa_id'], 'encrypt');

			$custom_noa_no = '<mark class="bg-primary text-white">'.$value['noa_no'].'</mark>';

			$button = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfoModal(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// $button .= '<a href="' . base_url() . 'member/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];
			$view_receipt = '';
			if($value['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			$result['data'][] = array(
				$custom_noa_no,
				date("m/d/Y", strtotime($value['admission_date'])),
				$short_hosp_name,
				date("m/d/Y", strtotime($value['request_date'])),
				$view_receipt,
				'<span class="badge rounded-pill bg-success">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}


	// function get_pending_noa_info() {
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$this->load->model('member/noa_model');
	// 	$row = $this->noa_model->db_get_noa_info($noa_id);

	// 	$dateOfBirth = $row['date_of_birth'];
	// 	$today = date("Y-m-d");
	// 	$diff = date_diff(date_create($dateOfBirth), date_create($today));
	// 	$age = $diff->format('%y') . ' years old';

	// 	$response = [
	// 		'status' => 'success',
	// 		'token' => $this->security->get_csrf_hash(),
	// 		'noa_id' => $row['noa_id'],
	// 		'noa_no' => $row['noa_no'],
	// 		'health_card_no' => $row['health_card_no'],
	// 		'requesting_company' => $row['requesting_company'],
	// 		'first_name' => $row['first_name'],
	// 		'middle_name' => $row['middle_name'],
	// 		'last_name' => $row['last_name'],
	// 		'suffix' => $row['suffix'],
	// 		'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
	// 		'age' => $age,
	// 		'hospital_name' => $row['hp_name'],
	// 		'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
	// 		'chief_complaint' => $row['chief_complaint'],
	// 		// Full Month Date Year Format (F d Y)
	// 		'request_date' => date("F d, Y", strtotime($row['request_date'])),
	// 		'req_status' => $row['work_related'] != '' ? 'for Approval': $row['status'],
	// 		'work_related' => $row['work_related'],
	// 		'percentage' => $row['percentage']
	// 	];
	// 	echo json_encode($response);
	// }

	// function get_approved_noa_info() {
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$this->load->model('member/noa_model');
	// 	$row = $this->noa_model->db_get_approved_noa_info($noa_id);

	// 	$doctor_name = "";
	// 	if ($row['approved_by']) {
	// 		$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
	// 		$doctor_name = $doc['doctor_name'];
	// 	} else {
	// 		$doctor_name = "";
	// 	}

	// 	$dateOfBirth = $row['date_of_birth'];
	// 	$today = date("Y-m-d");
	// 	$diff = date_diff(date_create($dateOfBirth), date_create($today));
	// 	$age = $diff->format('%y') . ' years old';

	// 	$paid_on = '';
	// 	$bill = $this->noa_model->gt_bill_info($row['noa_id']);
	// 	if(!empty($bill)){
	// 		$billed_on = date('F d, Y', strtotime($bill['billed_on']));
	// 		$paid = $this->noa_model->get_paid_date($bill['details_no']);
	// 		if(!empty($paid)){
	// 			$paid_on = date('F d, Y', strtotime($paid['date_add']));
	// 		}else{
	// 			$paid_on = '';
	// 		}
	// 	}else{
	// 		$billed_on = '';
	// 	}
	// 	$response = array(
	// 		'status' => 'success',
	// 		'token' => $this->security->get_csrf_hash(),
	// 		'noa_id' => $row['noa_id'],
	// 		'noa_no' => $row['noa_no'],
	// 		'health_card_no' => $row['health_card_no'],
	// 		'requesting_company' => $row['requesting_company'],
	// 		'first_name' => $row['first_name'],
	// 		'middle_name' => $row['middle_name'],
	// 		'last_name' => $row['last_name'],
	// 		'suffix' => $row['suffix'],
	// 		'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
	// 		'age' => $age,
	// 		'hospital_name' => $row['hp_name'],
	// 		'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
	// 		'chief_complaint' => $row['chief_complaint'],
	// 		// Full Month Date Year Format (F d Y)
	// 		'request_date' => date("F d, Y", strtotime($row['request_date'])),
	// 		'work_related' => $row['work_related'],
	// 		'percentage' => $row['percentage'],
	// 		'req_status' => $row['status'],
	// 		'approved_by' => $doctor_name,
	// 		'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
	// 		'billed_on' => $billed_on,
	// 		'paid_on' => $paid_on
	// 	);
	// 	echo json_encode($response);
	// }


	// function get_disapproved_noa_info() {
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$this->load->model('member/noa_model');
	// 	$row = $this->noa_model->db_get_disapproved_noa_info($noa_id);

	// 	$doctor_name = "";
	// 	if ($row['disapproved_by']) {
	// 		$doc = $this->noa_model->db_get_doctor_by_id($row['disapproved_by']);
	// 		$doctor_name = $doc['doctor_name'];
	// 	} else {
	// 		$doctor_name = "";
	// 	}

	// 	$dateOfBirth = $row['date_of_birth'];
	// 	$today = date("Y-m-d");
	// 	$diff = date_diff(date_create($dateOfBirth), date_create($today));
	// 	$age = $diff->format('%y') . ' years old';

	// 	$response = [
	// 		'status' => 'success',
	// 		'token' => $this->security->get_csrf_hash(),
	// 		'noa_id' => $row['noa_id'],
	// 		'noa_no' => $row['noa_no'],
	// 		'health_card_no' => $row['health_card_no'],
	// 		'requesting_company' => $row['requesting_company'],
	// 		'first_name' => $row['first_name'],
	// 		'middle_name' => $row['middle_name'],
	// 		'last_name' => $row['last_name'],
	// 		'suffix' => $row['suffix'],
	// 		'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
	// 		'age' => $age,
	// 		'hospital_name' => $row['hp_name'],
	// 		'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
	// 		'chief_complaint' => $row['chief_complaint'],
	// 		// Full Month Date Year Format (F d Y)
	// 		'request_date' => date("F d, Y", strtotime($row['request_date'])),
	// 		'req_status' => $row['status'],
	// 		'work_related' => $row['work_related'],
	// 		'percentage' => $row['percentage'],
	// 		'disapproved_by' => $doctor_name,
	// 		'disapprove_reason' => $row['disapprove_reason'],
	// 		'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
	// 	];
	// 	echo json_encode($response);
	// }

	// function get_completed_noa_info() {
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$this->load->model('member/noa_model');
	// 	$row = $this->noa_model->db_get_closed_noa_info($noa_id);

	// 	$doctor_name = "";
	// 	if ($row['approved_by']) {
	// 		$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);
	// 		$doctor_name = $doc['doctor_name'];
	// 	} else {
	// 		$doctor_name = "";
	// 	}

	// 	$dateOfBirth = $row['date_of_birth'];
	// 	$today = date("Y-m-d");
	// 	$diff = date_diff(date_create($dateOfBirth), date_create($today));
	// 	$age = $diff->format('%y') . ' years old';

	// 	$response = [
	// 		'status' => 'success',
	// 		'token' => $this->security->get_csrf_hash(),
	// 		'noa_id' => $row['noa_id'],
	// 		'noa_no' => $row['noa_no'],
	// 		'health_card_no' => $row['health_card_no'],
	// 		'requesting_company' => $row['requesting_company'],
	// 		'first_name' => $row['first_name'],
	// 		'middle_name' => $row['middle_name'],
	// 		'last_name' => $row['last_name'],
	// 		'suffix' => $row['suffix'],
	// 		'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
	// 		'age' => $age,
	// 		'hospital_name' => $row['hp_name'],
	// 		'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
	// 		'chief_complaint' => $row['chief_complaint'],
	// 		// Full Month Date Year Format (F d Y)
	// 		'request_date' => date("F d, Y", strtotime($row['request_date'])),
	// 		'work_related' => $row['work_related'],
	// 		'percentage' => $row['percentage'],
	// 		'req_status' => $row['status'],
	// 		'approved_by' => $doctor_name,
	// 		'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
	// 	];
	// 	echo json_encode($response);
	// }

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
		$selected_cost_types = explode(';', $row['medical_services']);
		$ct_array = [];
		$is_empty = false;
		foreach ($selected_cost_types as $selected_cost_type) :
			if($selected_cost_type !== ''){
				$is_empty = true;
				array_push($ct_array, '[ <span class="text-success">'.$selected_cost_type.'</span> ]');
			}
			
		endforeach;
		$med_serv = implode(' ', $ct_array);
		// var_dump('work_related',$row['work_related']);
		// var_dump('$selected_cost_types',count($selected_cost_types));
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
			'request_date' => date("F d, Y", strtotime($row['tbl_1_request_date'])),
			'req_status' => $row['tbl_1_status'],
			'work_related' => $row['tbl_1_work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'med_services' => ($is_empty)?$med_serv:'None',
			'hospital_bill' => ($row['hospital_bill'] >0 || $row['hospital_bill'] !== null) ? $row['hospital_bill'] : '',
			'net_bill' => isset( $row['net_bill'])?$row['net_bill']:'',
			'hospital_receipt' => $row['hospital_receipt'],
			// 'work_related'
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
