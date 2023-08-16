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

	function get_hp_services(){
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->uri->segment(4);
		$cost_types = $this->loa_model->db_get_cost_types_by_hp($hp_id);
		$response = [];

		if(empty($cost_types)){
		}else{
			foreach ($cost_types as $cost_type) {
				$data = [
					'ctyp_id' => $cost_type['ctype_id'],
					'ctyp_description' => $cost_type['item_description'],
					'ctyp_price' => number_format($cost_type['op_price'],2,'.',','),
				];
				array_push($response,$data);
			}
		}
		echo json_encode($response);
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
						'message' => 'Failed to save!'
					];
				}
				$response = [
					'status' => 'success', 
					'message' => 'Successfully Saved!'
				];
			}
		}
		echo json_encode($response);
	}

	//==================================================
	//NOTICE OF ADMISSION (PENDING)
	//==================================================
	function fetch_all_pending_noa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$list = $this->noa_model->get_datatables($status);
		$data = [];

		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$view_url = base_url() . 'healthcare-coordinator/noa/requested-loa/edit/' . $noa_id;
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];
			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));
			$custom_noa_no = '<mark class="bg-cyan text-black"><b>'.$noa['noa_no'].'</b></mark>';

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.
			If it is not empty, it will display the text "for Approval". */
			if($noa['work_related'] == ''){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';
			}

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

			$custom_actions .= '<a class="me-2" href="' . $view_url . '" data-bs-toggle="tooltip" title="Edit Admission Form" readonly><i class="mdi mdi-pencil-circle fs-4 text-success"></i></a>';

			// $custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Tagging"><i class="mdi mdi-tag-plus fs-4 text-primary"></i></a>';

			if($noa['accredited'] == '0' && $noa['type_request'] == 'Admission'){
				if ($noa['resubmit'] === 'Done') {
        	$custom_actions .= '<a href="JavaScript:void(0)" onclick="ChargeTypenotAffiliated(\'' . $noa_id . '\',\'' . $noa['hospital_receipt'] . '\',\'' . $noa['hospital_bill'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['work_related'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['police_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" class="blink-icon" data-bs-toggle="tooltip" title="Tagging"><i class="mdi mdi-tag-plus fs-4 text-primary"></i></a>';
    		}else{
        	$custom_actions .= '<a href="JavaScript:void(0)" onclick="ChargeTypenotAffiliated(\'' . $noa_id . '\',\'' . $noa['hospital_receipt'] . '\',\'' . $noa['hospital_bill'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['work_related'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['police_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="Tagging"><i class="mdi mdi-tag-plus fs-4 text-primary"></i></a>';
    		}
			}else{
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $noa_id . '\',\'' . $noa['percentage'] . '\',\'' . $noa['work_related'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['police_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="Tagging"><i class="mdi mdi-tag-plus fs-4 text-primary"></i></a>';
			}
			
			// if($noa['spot_report_file'] || $noa['incident_report_file'] != '' || $noa['police_report_file'] != ''){
			// 	$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\',\'' . $noa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Reports"><i class="mdi mdi-teamviewer fs-4 text-warning"></i></a>';
			// }else{
			// 	$custom_actions .= '';
			// }
			
			$custom_actions .= '<a href="Javascript:void(0)" onclick="cancelNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Delete Request"><i class="mdi mdi-delete-circle fs-4 text-danger"></i></a>';

			
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

	function update_noa_request() {
		$token = $this->security->get_csrf_hash();
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$medical_services = json_decode($this->input->post('noa-med-services'), TRUE);
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$is_accredited =json_decode($_POST['is_accredited'],true);
		$is_hr_has_data = json_decode($_POST['is_hr_has_data'],true);
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');
		$this->form_validation->set_rules('healthcare-provider-category', 'Healthcare Provider Category', 'required');
		
		if(!$is_accredited){
			$this->form_validation->set_rules('noa-med-services', 'Medical Services', 'required');
			if((!$is_hr_has_data && isset($_FILES['hospital-receipt']['name'])) || (!$is_hr_has_data && !isset($_FILES['hospital-receipt']['name']))){
				$this->form_validation->set_rules('hospital-receipt', '', 'callback_check_hospital_receipt');
			}
			
			$this->form_validation->set_rules('hospital-bill', 'Hospital Bill', 'required');
		}
		
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'chief_complaint_error' => form_error('chief-complaint'),
				'admission_date_error' => form_error('admission-date'),
				'med_services_error' => form_error('noa-med-services'),
				'hospital_receipt_error' => form_error('hospital-receipt'),
				'hospital_bill_error' => form_error('hospital-bill'),
				'healthcare_provider_category_error' => form_error('healthcare-provider-category'),
			];
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
					$response = ['status' => 'save-error', 'message' => 'Hospital Does Not Exist'];
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
					$response = ['status' => 'save-error', 'message' => 'Update Failed'];
				}else{
					$response = ['status' => 'success', 'message' => 'Updated Successfully'];
				
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

	function submit_charge_type_not_affiliated(){
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->input->post('noa_id'), 'decrypt');
		// var_dump($noa_id);
		$charge_type = $this->input->post('charge-type', TRUE);
		$percentage = $this->input->post('percentage', TRUE);

		$this->form_validation->set_rules('charge-type', 'Charge Type', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'token' => $token,
				'status' => 'error',
				'charge_type_error' => form_error('charge-type'),
			];
			echo json_encode($response);
		}else{
			$config['allowed_types'] = 'pdf|jpeg|jpg|png|gif|svg';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);

			$uploaded_files = [];
			$error_occurred = FALSE;

			// Define the upload paths for each file
			$file_paths = [
				'spot-report' => './uploads/spot_reports/',
				'incident-report' => './uploads/incident_reports/',
				'police-report' => './uploads/police_reports/',
			];

			// Iterate over each file input and perform the upload
			$file_inputs = ['spot-report', 'incident-report','police-report'];
			foreach ($file_inputs as $input_name) {
				$config['upload_path'] = $file_paths[$input_name];
				$this->upload->initialize($config);

				if ($_FILES[$input_name]['size']!== 0) {
					if (!$this->upload->do_upload($input_name)) {
						// Handle upload error
						$error_occurred = TRUE;
						break;
					}
				}
				$uploaded_files[$input_name] = $this->upload->data();
			}

			if ($error_occurred) {
				// Handle upload error response
				$response = [
					'token' => $token,
					'status' => 'upload-error',
					'message' => 'File Saving to Folder Failed',
				];
				echo json_encode($response);
			} else {
				$data = [
					'work_related' => $charge_type,
					'percentage' => $percentage,
					'hospital_bill' => str_replace(array("₱", ","), "", $this->input->post('hospital_bill', TRUE)),
					'spot_report_file' => isset($uploaded_files['spot-report']) ? $uploaded_files['spot-report']['file_name'] : '',
					'incident_report_file' => isset($uploaded_files['incident-report']) ? $uploaded_files['incident-report']['file_name'] : '',
					'police_report_file' => isset($uploaded_files['police-report']) ? $uploaded_files['police-report']['file_name'] : '',
					'date_uploaded' => date('Y-m-d')
				];

				$updated = $this->noa_model->db_update_noa_charge_type($noa_id, $data);

				if (!$updated) {
					$response = [
						'token' => $token,
						'status' => 'save-error',
						'message' => 'Save Failed',
					];
				} else {
					$response = [
						'token' => $token,
						'status' => 'success',
						'message' => 'Saved Successfully',
					];
				}
				echo json_encode($response);
				// var_dump($updated);
				// var_dump($response);

			}
		}
	}

	function submit_hospital_receipt(){
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		// var_dump($loa_id);
		$this->form_validation->set_rules('resubmit', 'Reason for Resubmit', 'trim|required|max_length[2000]');

		if ($this->form_validation->run() == FALSE){
			$response = [
				//'token' => $token,
				'status' => 'error',
				'resubmit_error' => form_error('resubmit'),
			];
		}else{
			$post_data = [
				'resubmit'              => 'Resubmit',
				'resubmit_reason' => $this->input->post('resubmit', TRUE),
				'resubmit_on' 				=> date("Y-m-d"),
				'resubmit_by' 				=> $this->session->userdata('fullname'),
			];

			$updated = $this->noa_model->db_update_loa_request1($noa_id, $post_data);
			if (!$updated) {
				$response = [
					'status' => 'save-error', 
					'message' => 'Re-submit Failed!'
				];
			}
			$response = [
				'status' => 'success', 
				'message' => 'Re-submit Successfully!'
			];
		}
		echo json_encode($response);
		// var_dump($response);
	}
	//==================================================
	//END
	//==================================================

	//==================================================
	//NOTICE OF ADMISSION (APPROVED)
	//==================================================
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
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';
			$custom_noa_no = '<mark class="bg-cyan text-black"><b>'.$noa['noa_no'].'</b></mark>';
			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';
			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';
			$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/noa/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print"><i class="mdi mdi-printer fs-4 text-primary pe-2"></i></a>';

			
			if($noa['spot_report_file'] || $noa['incident_report_file'] != '' || $noa['police_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\',\'' . $noa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Reports"><i class="mdi mdi-teamviewer fs-4 text-warning"></i></a>';
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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}
	//==================================================
	//END
	//==================================================

	//==================================================
	//NOTICE OF ADMISSION (DISAPPROVED)
	//==================================================

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
			$custom_noa_no = '<mark class="bg-cyan text-black"><b>'.$noa['noa_no'].'</b></mark>';
			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

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
			"draw"            => $_POST['draw'],
			"recordsTotal"    => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data"            => $data,
		];
		echo json_encode($output);
	}
	//==================================================
	//END
	//==================================================

	//==================================================
	//NOTICE OF ADMISSION (EXPIRED)
	//==================================================

	function fetch_all_expired_noa() {
		$this->security->get_csrf_hash();
		$status = 'Expired';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$row = [];
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];
			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';
			$custom_noa_no = '<mark class="bg-cyan text-black"><b>'.$noa['noa_no'].'</b></mark>';
			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewExpiredNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $noa_id . '\', \'' . $noa['noa_no'] . '\')" data-bs-toggle="tooltip" title="Back Date"><i class="mdi mdi-update fs-4 text-cyan"></i></a>';

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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}
	//==================================================
	//END
	//==================================================

	//INITIAL BILLING====================================================
	function initial_billing() {
		$this->security->get_csrf_hash();
		$status = 'Initial';
		$list = $this->noa_model->get_datatables_ledger($status);
		$data = [];
		foreach ($list as $member){
			$row = [];
			$member_id = $this->myhash->hasher($member['emp_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$workRelated = $member['work_related'] . ' (' . $member['percentage'] . '%)';
			$view_url = base_url() . 'healthcare-coordinator/noa/billed/initial_billing2/' . $member_id;
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Initial Bill"><i class="mdi mdi-eye fs-4 text-info me-2"></i></a>';

			$row[] = $member['billing_no'];
			$row[] = $member['noa_no'];
			$row[] = $full_name;
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $workRelated;
			$row[] = number_format($member['company_charge'], 2, '.', ',');
			$row[] = number_format($member['personal_charge'], 2, '.', ',');
			$row[] = number_format($member['cash_advance'], 2, '.', ',');
			
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all_ledger($status),
			"recordsFiltered" => $this->noa_model->count_filtered_ledger($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	
	function initial_billing2() {
	    $token = $this->security->get_csrf_hash();
	    $emp_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	    $data['user_role'] = $this->session->userdata('user_role');
	    $data['billing'] = $this->noa_model->get_member_info($emp_id);
	    $data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
	    $this->load->view('templates/header', $data);
	    $this->load->view('healthcare_coordinator_panel/noa/initial_billing2');
	    $this->load->view('templates/footer');
 	}
 	//END==================================================================

 	//FINAL BILLING========================================================
	function final_billing() {
		$token = $this->security->get_csrf_hash(); 
		$billing = $this->noa_model->get_final_datatables();

		$data = [];
		foreach ($billing as $bill){
			$row = [];
			$noa_id = $this->myhash->hasher($bill['noa_id'], 'encrypt');
			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
			$request_date=date("F d, Y", strtotime($bill['tbl1_request_date']));

      if (empty($bill['billed_on'])) {
  			$billed_date = "No Billing Date Yet";
			}else{
  			$billed_date = date("F d, Y", strtotime($bill['billed_on']));
			}



			if($bill['tbl1_status'] !== 'Billed'){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $bill['tbl1_status'] . '</span></div>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $bill['tbl1_status'] . '</span></div>';
			}

			$custom_actions = '';
			if($bill['tbl1_status'] == 'Billed'){
				if($bill['accredited']=='1'){
					$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View SOA"><i class="mdi mdi-file-pdf fs-4 text-danger"></i></a>';
					if ($bill['guarantee_letter'] =='') {
		  			$custom_actions .= '<a href="JavaScript:void(0)" onclick="GuaranteeLetter(\'' . $noa_id . '\',\'' . $bill['billing_id'] . '\')" data-bs-toggle="modal" data-bs-target="#GuaranteeLetter" data-bs-toggle="tooltip" title="Guarantee Letter"><i class="mdi mdi-reply fs-4 text-danger"></i></a>';
		  		}else{
						$custom_actions .= '<i class="mdi mdi-reply fs-4 text-secondary" title="Guarantee Letter Already Sent"></i>';
					}
				}else if($bill['accredited']=='0'){
					$pdf_bill = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $bill['pdf_bill'] . '\')"><i class="mdi mdi-file-image fs-4 text-danger"></i></a>';
					$custom_actions .='<i class="mdi mdi-cached fs-4 text-success"></i>Processing...';
				}
			}else if($bill['tbl1_status'] == 'Approved' && $bill['accredited']=='1'){
				$custom_actions .='<i class="mdi mdi-cached fs-4 text-success"></i>Processing...';
				$pdf_bill ='Waiting for SOA';
			}

			$row[] = $bill['noa_no'];
			$row[] = $fullname;
			$row[] = '₱' . number_format($bill['after_remaining_bal'], 2, '.', ',');
			$workRelated = $bill['tbl1_work_related'] . ' (' . $bill['percentage'] . '%)';
			$row[] = $workRelated;
			$row[] = $request_date;
			$row[] = $billed_date;
			$row[] = '₱' . number_format($bill['company_charge'], 2, '.', ',');
			$row[] = '₱' . number_format($bill['personal_charge'], 2, '.', ',');
			$row[] = '₱' . number_format($bill['cash_advance'], 2, '.', ',');
			$netBill = '₱' . number_format($bill['net_bill'], 2, '.', ',');
			$row[] = $netBill;
			$row[] = $pdf_bill;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function submit_final_billing() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->input->post('billed-hospital-filter', TRUE);
		$start_date = $this->input->post('start-date', TRUE);
		$end_date = $this->input->post('end-date', TRUE);
		$date = strtotime($this->input->post('start-date', TRUE));
		$month = date('m', $date);
		$year = date('Y', $date);
		$total_payable = floatval(str_replace(',', '', $this->input->post('total-hospital-bill', TRUE)));
		$bill_no = "BILL-" . date('His') . mt_rand(1000, 9999);
		$matched = $this->noa_model->set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no);
		$initial_status = $this->input->post('initial_status', TRUE);

		$data = [
			'bill_no' => $bill_no,
			'type' => 'NOA',
			'hp_id' => $hp_id,
			'month' => $month,
			'year' => $year,
			'status' => 'Payable',
			'total_payable' => $total_payable,
			'added_on' => date('Y-m-d'),
			'added_by' => $this->session->userdata('fullname'),
		];
		$inserted = $this->noa_model->insert_for_payment_consolidated($data);

		if($inserted){
			// $this->noa_model->update_initial_billing($initial_status);
			$this->noa_model->update_noa_requests($hp_id, $start_date, $end_date);
			header('Location: ' . base_url() . 'healthcare-coordinator/bill/noa-requests/for_payment');
    	exit;
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Failed to Submit!'
			]);
		}
	}
 	//END=================================================================

	//BILLING STATEMENT===================================================
	function fetch_payable_noa() {
		$this->security->get_csrf_hash();
		$status = 'Payable';
		$for_payment = $this->noa_model->fetch_for_payment_bill();
		$data = [];
		foreach($for_payment as $bill){
			$row = [];
			if($bill['month'] == '01'){
				$month = 'January';
			}else if($bill['month'] == '02'){
				$month = 'February';
			}else if($bill['month'] == '03'){
				$month = 'March';
			}else if($bill['month'] == '04'){
				$month = 'April';
			}else if($bill['month'] == '05'){
				$month = 'May';
			}else if($bill['month'] == '06'){
				$month = 'June';
			}else if($bill['month'] == '07'){
				$month = 'July';
			}else if($bill['month'] == '08'){
				$month = 'August';
			}else if($bill['month'] == '09'){
				$month = 'September';
			}else if($bill['month'] == '10'){
				$month = 'October';
			}else if($bill['month'] == '11'){
				$month = 'November';
			}else if($bill['month'] == '12'){
				$month = 'December';
			}

			$bill_no_custom = '<span class="fw-bold fs-5">'.$bill['bill_no'].'</span>';
			$label_custom = '<span class="fw-bold fs-5">Month of '.$month.', '.$bill['year'].'</span>';
			$hospital_custom = '<span class="fw-bold fs-5">'.$bill['hp_name'].'</span>';
			$status_custom = '<span class="badge rounded-pill bg-success text-white">'.$bill['status'].'</span>';
			$action_customs = '<a href="'.base_url().'healthcare-coordinator/bill/billed-noa/charging/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View List"><i class="mdi mdi-format-list-bulleted fs-4 text-danger"></i></a>';

			$row[] = $bill_no_custom;
			$row[] = $label_custom;
			$row[] = $hospital_custom;
			$row[] = $status_custom;
			$row[] = $action_customs;
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
		echo json_encode($output);
	}
 	//END=================================================================
	function fetch_monthly_bill() {
		$token = $this->security->get_csrf_hash();
		$bill_no = $this->uri->segment(5);
		$billing = $this->noa_model->monthly_bill_datatable($bill_no);
		$data = [];

		foreach($billing as $bill){
			$row = [];

			$noa_id = $this->myhash->hasher($bill['noa_id'], 'encrypt');

			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-dark"></i>View</a>';

			$row[] = $bill['billing_no'];
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = number_format($bill['net_bill'], 2, '.', ',');
			$row[] = $pdf_bill;
			$data[] = $row;
		
		}

		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_billing_for_charging() {
		$this->security->get_csrf_hash();
		$bill_no = $this->uri->segment(5);
		$billing = $this->noa_model->get_billed_for_charging($bill_no);
		// var_dump($billing);
		$data = [];

		foreach($billing as $bill){
			$row = [];
			$company_charge = '';
			$personal_charge = '';
			$remaining_mbl = '';

			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

			if ($bill['billing_type'] == 'Reimburse'){	
				if (empty($bill['pdf_bill'])) {
					$pdf_bill = 'No SOA';
				}
				if(empty($bill['itemized_bill'])){
					$detailed_pdf_bill = 'No SOA';
				}
				if(!empty($bill['pdf_bill'])){
					$pdf_bill = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $bill['pdf_bill'] . '\')"><i class="mdi mdi-file-image fs-4 text-danger"></i></a>';
				}
			}else{
				$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View Summary of SOA"><i class="mdi mdi-file-pdf fs-4 text-danger"></i></a>';
				$detailed_pdf_bill = '<a href="JavaScript:void(0)" onclick="viewDetailedPDFBill(\'' . $bill['itemized_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View Detailed SOA"><i class="mdi mdi-file-pdf fs-4 text-danger"></i></a>';
			}
			

			if($bill['work_related'] == 'Yes'){
				if($bill['percentage'] == ''){
					$label = 'Work Related';
					$percent = '100';
				}else{
					$label = 'Work Related';
					$percent = $bill['percentage'];
				}
			}else{
				if($bill['percentage'] == ''){
					$label = 'Non Work-Related';
					$percent = '100';
				}else{
					$label = 'Non Work-Related';
					$percent = $bill['percentage'];
				}
			}
			
			$percent_custom = '<span>'.$percent.'% '.$label.'</span>';

			$net_bill = floatval($bill['net_bill']);
			$previous_mbl = floatval($bill['remaining_balance']);
			$percentage = floatval($bill['percentage']);

			if($bill['work_related'] == 'Yes'){
				if($bill['percentage'] == ''){
					$company_charge = number_format($bill['net_bill'],2, '.',',');
					$personal_charge = number_format(0,2, '.',',');
					if($net_bill >= $previous_mbl){
						$remaining_mbl = number_format(0,2, '.',',');
					}else if($net_bill < $previous_mbl){
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}
				}else if($bill['percentage'] != ''){
					
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}else if($net_bill > $previous_mbl){
						$converted_percent = $percentage/100;
						$initial_company_charge = floatval($converted_percent) * $net_bill;
						$initial_personal_charge = $net_bill - floatval($initial_company_charge);

						if(floatval($initial_company_charge) <= $previous_mbl){
							$result = $previous_mbl - floatval($initial_company_charge);
							$int_personal = floatval($initial_personal_charge) - floatval($result);
							$personal_charge = number_format($int_personal,2, '.',',');
							$company_charge = number_format($previous_mbl,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
					
						}else if(floatval($initial_company_charge) > $previous_mbl){
							$personal_charge = number_format($initial_personal_charge,2, '.',',');
							$company_charge = number_format($initial_company_charge,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
						}
					}
					
				}
			}else if($bill['work_related'] == 'No'){
				if($bill['percentage'] == ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}else if($net_bill > $previous_mbl){
						$company_charge = number_format($previous_mbl,2, '.',',');
						$personal_charge = number_format($net_bill - $previous_mbl,2, '.',',');
						$remaining_mbl = number_format(0,2, '.',',');
					}
				}else if($bill['percentage'] != ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}else if($net_bill > $previous_mbl){
						$converted_percent = $percentage/100;
						$initial_personal_charge = $converted_percent * $net_bill;
						$initial_company_charge = $net_bill - floatval($initial_personal_charge);
						if($initial_company_charge <= $previous_mbl){
							$result = $previous_mbl - $initial_company_charge;
							$initial_personal = $initial_personal_charge - $result;
							if($initial_personal < 0 ){
								$personal_charge = number_format(0,2, '.',',');
								$company_charge = number_format($initial_company_charge + $initial_personal_charge,2, '.',',');
								$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
							}else if($initial_personal >= 0){
								$personal_charge = number_format($initial_personal,2, '.',',');
								$company_charge = number_format($previous_mbl,2, '.',',');
								$remaining_mbl = number_format(0,2, '.',',');
							}
						}else if($initial_company_charge > $previous_mbl){
							$personal_charge = number_format($initial_personal_charge,2, '.',',');
							$company_charge = number_format($initial_company_charge,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
						}
					}
				}
			}
			
			$row[] = $bill['billing_no'];
			$row[] = $bill['noa_no'];
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = $percent_custom;
			$row[] = $bill['type_request'];
			$row[] = number_format($bill['company_charge'],2, '.',',');
			$row[] = number_format($bill['personal_charge'],2, '.',',');
			$row[] = number_format($bill['cash_advance'],2, '.',',');
			$row[] = number_format($bill['net_bill'],2, '.',',');
			$row[] = $pdf_bill;
			$row[] = $detailed_pdf_bill;
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
		// var_dump($output);
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
			'req_status' => $row['work_related'] != '' ? 'for Approval': $row['status'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'health_card_no' => $row['health_card_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'contact_no' => $row['contact_no'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'hospital_name' => $row['hp_name'],
			'chief_complaint' => $row['chief_complaint'],
			'medical_services' => $row['medical_services'],
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
			'req_status' => $row['status'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'approved_by' => $doctor_name,
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'health_card_no' => $row['health_card_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'contact_no' => $row['contact_no'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'hospital_name' => $row['hp_name'],
			'chief_complaint' => $row['chief_complaint'],
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
			'req_status' => $row['status'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'health_card_no' => $row['health_card_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'contact_no' => $row['contact_no'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'hospital_name' => $row['hp_name'],
			'chief_complaint' => $row['chief_complaint'],
			'medical_services' => $row['medical_services'],
		];
		echo json_encode($response);
	}


	function get_expired_noa_info() {
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
			'req_status' => $row['status'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'approved_by' => $doctor_name,
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'health_card_no' => $row['health_card_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'contact_no' => $row['contact_no'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'hospital_name' => $row['hp_name'],
			'chief_complaint' => $row['chief_complaint'],
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
			'percentage' => $row['percentage'],
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
		// $data['row'] = $this->noa_model->db_get_noa_info($noa_id);
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$data['ahcproviders'] = $this->noa_model->db_get_affiliated_healthcare_providers();
		// var_dump($data['ahcproviders']);
		$data['hcproviders'] = $this->noa_model->db_get_not_affiliated_healthcare_providers();
		// var_dump($data['hcproviders']);
		$data['old_services'] = explode(';',$exist['medical_services']);
		// var_dump($data['old_services']);
		$data['is_accredited'] = ($exist['is_manual'])?true:false;
		// var_dump($data['is_accredited']);
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/edit_noa_request');
		$this->load->view('templates/footer');
	}

	

	function cancel_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');

		$deleted = $this->noa_model->db_cancel_noa($noa_id);
		if ($deleted) {
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'Successfully Deleted!'
			];
		} else {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'Failed to Delete!'
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
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();

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
			$this->load->view('healthcare_coordinator_panel/noa/generate_printable_noa');
			$this->load->view('templates/footer');
		}
	}

	function set_charge_type()
	{
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->input->post('noa-id'), 'decrypt');
		$charge_type = $this->input->post('charge-type', TRUE);
		$percentage = $this->input->post('percentage', TRUE);

		$this->form_validation->set_rules('charge-type', 'Charge Type', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'token' => $token,
				'status' => 'error',
				'charge_type_error' => form_error('charge-type'),
			];
			echo json_encode($response);
		} else {
			$config['allowed_types'] = 'pdf|jpeg|jpg|png|gif|svg';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);

			$uploaded_files = [];
			$error_occurred = FALSE;

			// Define the upload paths for each file
			$file_paths = [
				'spot-report' => './uploads/spot_reports/',
				'incident-report' => './uploads/incident_reports/',
				'police-report' => './uploads/police_reports/',
			];

			// Iterate over each file input and perform the upload
			$file_inputs = ['spot-report', 'incident-report','police-report'];
			foreach ($file_inputs as $input_name) {
				$config['upload_path'] = $file_paths[$input_name];
				$this->upload->initialize($config);
				if ($_FILES[$input_name]['size']!== 0) {
					if (!$this->upload->do_upload($input_name)) {
						// Handle upload error
						$error_occurred = TRUE;
						break;
					}
				}

				$uploaded_files[$input_name] = $this->upload->data();
			}

			if ($error_occurred) {
				// Handle upload error response
				$response = [
					'token' => $token,
					'status' => 'upload-error',
					'message' => 'File upload failed',
				];
				echo json_encode($response);
			} else {
				$data = [
					'work_related' => $charge_type,
					'percentage' => $percentage,
					'spot_report_file' => isset($uploaded_files['spot-report']) ? $uploaded_files['spot-report']['file_name'] : '',
					'incident_report_file' => isset($uploaded_files['incident-report']) ? $uploaded_files['incident-report']['file_name'] : '',
					'police_report_file' => isset($uploaded_files['police-report']) ? $uploaded_files['police-report']['file_name'] : '',
					'date_uploaded' => date('Y-m-d')

				];

				$updated = $this->noa_model->db_update_noa_charge_type($noa_id, $data);

				if (!$updated) {
					$response = [
						'token' => $token,
						'status' => 'save-error',
						'message' => 'Save Failed',
					];
				} else {
					$response = [
						'token' => $token,
						'status' => 'success',
						'message' => 'Saved Successfully',
					];
				}
				echo json_encode($response);
			}
		}
	}


	function backdate_expired_noa(){
		$noa_id = $this->myhash->hasher($this->input->post('noa-id', TRUE), 'decrypt');
		$expiry_date = $this->input->post('expiry-date', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('expiry-date', 'Expiry Date', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' 				    => 'error',
				'expiry_date_error' => form_error('expiry-date'),
			];
		}else{
			$post_data = [
				'status'          => 'Approved',
				'expiration_date' => date('Y-m-d', strtotime($expiry_date)),
			];

			$updated = $this->noa_model->db_update_noa_request($noa_id, $post_data);

			if (!$updated) {
				$response = [
					'status'  => 'save-error', 
					'message' => 'Failed to Save!'
				];
			}
			$response = [
				'status'  => 'success', 
				'message' => 'Successfully Saved!'
			];
		}		
		echo json_encode($response);
	}

	function get_total_hp_bill() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->input->post('hp_id');
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');
		$hospital = $this->noa_model->get_total_hp_net_bill($hp_id, $start_date, $end_date);
		$response = [
			'token' => $token,
			'total_hospital_bill' => number_format($hospital, 2, '.', ','),
		];

		echo json_encode($response);

	}

	

	function fetch_monthly_payable() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		// $bill_no =$this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$bill_no = $this->uri->segment(5);
		$data['payable'] = $this->noa_model->fetch_monthly_billed_noa($bill_no);
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/view_monthly_billed_noa');
		$this->load->view('templates/footer');

	}

	function fetch_total_hp_bill() {
		$token = $this->security->get_csrf_hash();
		$bill_no = $this->input->post('bill_no');;
		$hospital = $this->noa_model->get_matched_total_hp_bill($bill_no);
		
		$response = [
			'token' => $token,
			'total_hospital_bill' => number_format($hospital, 2, '.', ','),
		];
		echo json_encode($response);
	}

	function fetch_monthly_charging() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		// $bill_no =$this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$bill_no = $this->uri->segment(5);
		$data['payable'] = $this->noa_model->fetch_monthly_billed_noa($bill_no);
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/view_monthly_charging');
		$this->load->view('templates/footer');
	}

	function submit_letter() {
    $pdf_file = $this->input->post('pdf_file');
    $billing_id = $this->input->post('billing_id');
    $token = $this->input->post('token');
    var_dump($pdf_file);
    var_dump($billing_id);
   
    if(!isset($pdf_file) && !isset($billing_id)) {
      echo json_encode([
        'token' => $token,
        'status' => 'error',
        'message' => 'File upload failed!',
        'pdf file' => $pdf_file,
        'billing_id' => $billing_id,
      ]); 
    }else{
      $upload_on = date('Y-m-d');

      // Save the file data into the database
      $updated = $this->noa_model->db_update_letter($billing_id, $pdf_file, $upload_on);
      if (!$updated){
        $response = [
          'token' => $token,
          'status' => 'save-error',
          'message' => 'Save Failed',
        ];
      }else{
        $response = [
          'token' => $token,
          'status' => 'success',
         'message' => 'Saved Successfully',
        ];
      }
      echo json_encode($response);
    }
	}

	// public function guarantee_pdf($noa_id){
	// 	$this->security->get_csrf_hash();
	// 	$this->load->library('tcpdf_library');
	// 	$noa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$row = $this->noa_model->db_get_data_for_gurantee($noa_id);
	// 	$companyChargeWords = $this->convertNumberToWords($row['company_charge']);
	// 	$name = $this->session->userdata('fullname');
	// 	$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);

	
	// 	// Generate the PDF content
	// 	$pdf = new TCPDF();
	
	// 	// Disable the header and footer lines
	// 	$pdf->SetPrintHeader(false);
	// 	$pdf->SetPrintFooter(false);
	
	// 	// Set the font and size for the letter content...
	// 	$pdf->SetFont('Helvetica', '', 12);
	
	// 	// Add the letter content
	// 	$pdf->AddPage();
	
	// 	$html1 = '<div>
	// 				   <p id="generated-date" style="font-weight: bold;">' . date("F j, Y") . '</p>
	// 				   <p></p>
	// 					  <p style="font-weight: bold;line-height: 0;">JONE SIEGFRED L. SEPE</p>
	// 					  <p style="line-height: 0;">CEO/PRESIDENT</p>
	// 					  <p style="line-height: 0;">Gallares Street Poblacion II</p>
	// 					  <p style="line-height: 0;">Tagbilaran City, Bohol, 0139</p>
	// 			</div>';
	
	// 	$html2 = '<div style="text-align: justify;">
	// 				<p style="font-weight: bold;">Dear DR. SEPE;</p>
	
	// 				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This letter is in reference to the request for the</span> <span style="font-weight: bold;">Alturas Healthcare Program</span> on behalf of our client, <span style="font-weight: bold;text-transform: uppercase">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span>. The Alturas Group of Companies has assessed and validated the said request for assistance through the Crisis Intervention Section. Therefore, the company is using this letter to guarantee payment of the bill in the amount <span id="company_charge_words" style="font-weight: bold;text-transform: uppercase">' . $companyChargeWords . '</span> <span style="font-weight: bold">(PHP ' . number_format($row['company_charge'], 2 ,'.',',') . ')</span>.</p>
	
	// 				<p>Please be informed that the payment will be directly deposited into your company designated bank account. If you have any inquiries or require further information, please feel free to contact us at 233-0261.</p>
	
	// 				<p>Thank you for your consideration.</p>
	// 				</div>';
	
	// 	$html3 = '<div style="text-align: justify;">
	// 				<p>Yours sincerely,</p>
	// 				<p></p>
	
	// 				<p>Prepared By :</p>
	// 				<p></p>
	// 				<p style="line-height: 0;text-transform: uppercase;font-weight: bold;">' . $name . '</p>
	
	// 				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	// 				Approved By :</p>
					
	// 				<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	// 				<img src="' . base_url() . 'uploads/doctor_signatures/' . $doc['doctor_signature'] . '" alt="Doctor Signature" style="height:auto;width:170px;vertical-align:baseline;margin-left:-170px">			
	// 				</p>

	// 				<p style="line-height: -5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	// 				<span style="font-weight: bold">DR. MICHAEL D. UY</span></p>
	
	// 				<p style="line-height: -5;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	// 				Company Physician</p>
	
				  
	// 				</div>';
	
	// 	$pdf->writeHTML($html1);
	// 	$pdf->writeHTML($html2);
	// 	$pdf->writeHTML($html3);
	
	// 	// Output the PDF to the browser
	// 	// $pdf->Output('guarantee_letter.pdf', 'I');
	// 	// $pdfPath = 'uploads/guarantee_letter/guarantee_letter.pdf';
	// 	$fileName = 'guarantee_letter' . $noa_id . '.pdf';
	// 	$pdf->Output(getcwd() . '/uploads/guarantee_letter/' . $fileName, 'F');
	// 	$response = [
	// 		'status' => 'saved-pdf',
	// 		'filename' => $fileName
	// 	];
	// 	echo json_encode($response);
	
	// 	// file_put_c	ontents($pdfPath, $pdfContent);
	// 	}

	// public function guarantee_pdf($noa_id){
	// 	$this->security->get_csrf_hash();
	// 	$this->load->library('tcpdf_library');
	// 	$noa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$row = $this->noa_model->db_get_data_for_gurantee($noa_id);
	// 	$companyChargeWords = $this->convertNumberToWords($row['company_charge']);
	// 	$name = $this->session->userdata('fullname');
	// 	$doc = $this->noa_model->db_get_doctor_by_id($row['approved_by']);

	
	// 	// Generate the PDF content
	// 	$pdf = new TCPDF();
	
	// 	// Disable the header and footer lines
	// 	$pdf->SetPrintHeader(false);
	// 	$pdf->SetPrintFooter(false);
	
	// 	// Set the font and size for the letter content...
	// 	$pdf->SetFont('Helvetica', '', 12);
	
	// 	// Add the letter content
	// 	$pdf->AddPage();

	// 	$title = '<div>
	// 							<p><img src="'.base_url().'assets/images/HC_logo.png" style="width:180px;height:45px">
	// 						</div>';
	
	// 	$html1 = '<div>
	// 					   	<p id="generated-date" style="font-weight: bold;">' . date("F j, Y") . '</p>
	// 						  <p style="font-weight: bold;line-height: 0;">JONE SIEGFRED L. SEPE</p>
	// 						  <p style="line-height: 0;">CEO/PRESIDENT</p>
	// 						  <p style="line-height: 0;">0139 Gallares Street, Poblacion II,</p>
	// 						  <p style="line-height: 0;">Tagbilaran City, Bohol, 0139</p>
	// 						</div>';
	// 	$html2 ='<div><p style="font-weight: bold;text-decoration:underline">RE: Guarantee Letter for Payment Covered by Alturas Healthcare;</p>
	// 						<p></p>
	// 						<p>Dear<span style="font-weight: bold;"> DR. SEPE;</span></p>

	// 						<p>We are writing to confirm that <span style="font-weight: bold;text-transform: uppercase">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span>, a valued member of the Alturas Healthcare Program, has received medical services and treatments from your esteemed healthcare facility. We would like to assure you that we will cover applicable expenses incurred by our member during their visit, as outlined in our agreement with your organization.</p>

	// 						<p style="line-height: 0;">Patient Details:</p>
	// 						<p style="line-height: 0;">Patient Name: '.$row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] . ' </p>
	// 						<p style="line-height: 0;">Date of Birth: '.$row['date_of_birth'].'</p>
	// 						<p style="line-height: 0;">Alturas Healthcare Program ID: '.$row['health_card_no'].'</p>
	// 						<p style="line-height: 0;">LOA/NOA: '.$row['noa_no'].'</p>

	// 						<p>Therefore, in accordance with the terms and conditions of our agreement, Alturas Healthcare will be using this letter to guarantee payment of the bill amounting <span id="company_charge_words" style="font-weight: bold;text-transform: uppercase">' . $companyChargeWords . '</span> <span style="font-weight: bold">(PHP ' . number_format($row['company_charge'], 2,'.',',') . ')</span> only. We kindly request that you submit all relevant bills and supporting documentation for the services rendered to Patient Name directly to our designated billing department.</p>
	// 						<p>We appreciate your collaboration and dedication to providing exceptional healthcare services to our members. Your continued partnership with the Alturas Healthcare Program is instrumental in fulfilling our mission of delivering comprehensive and accessible healthcare to our beneficiaries.</p>
	// 						<p>Thank you for your attention to this matter, and we look forward to a continued successful relationship.</p>

	// 						<p></p>
	// 						<p>Yours sincerely,</p>
	// 						<img src="' . base_url() . 'uploads/doctor_signatures/' . $doc['doctor_signature'] . '" alt="Doctor Signature" style="height:auto;width:170px;vertical-align:baseline;">
	// 							<p style="line-height: 0;text-transform: uppercase;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dr. Michael D. Uy</span></p>
	// 							<p style="line-height: 1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Company Physician</p>
	// 					</div>';




	// 	$pdf->writeHTML($title);
	// 	$pdf->writeHTML($html1);
	// 	$pdf->writeHTML($html2);


	// 	$fileName = 'guarantee_letter' . $noa_id . '.pdf';
	// 	$pdf->Output(getcwd() . '/uploads/guarantee_letter/' . $fileName, 'F');
	// 	$response = [
	// 		'status' => 'saved-pdf',
	// 		'filename' => $fileName
	// 	];
	// 	echo json_encode($response);

	// 	}

	function guarantee_pdf($noa_id){
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');
		$noa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		// var_dump('loa_id',$loa_id);
		$row = $this->noa_model->db_get_data_for_gurantee($noa_id);
		// $loa = $this->loa_model->db_get_loa_detail($noa_id);
		
		// var_dump('companyChargeWords',$companyChargeWords);
		$name = $this->session->userdata('fullname');
		$doc = $this->noa_model->db_get_doctor_by_id(1);
		$qr_count = $this->noa_model->count_all_generated_guarantee_letter();
		$total = number_format($row['company_charge']+$row['cash_advance'],2,'.',',');
		$companyChargeWords = $this->convertNumberToWords($total);
		// Generate the qr code
		$data = [
			'Guarantee Payment no:' => $qr_count+1,
			'Member`s Name:' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ,
			'NOA no:' =>	$row['noa_no'],
			'Billing no:' => $row['billing_no'],
			'Total Billed Amount:' =>	number_format($row['net_bill'],2,'.',','),
			'Company Charge:' => number_format($row['company_charge'],2,'.',','),
			'Healthcare Advance:' => number_format($row['cash_advance'],2,'.',','),
			'Total Guaranteed Amount:' => $total,
		]; 
		$formattedText = '';
		foreach ($data as $key => $value) {
			$formattedText .= $key . ' ' . $value . "\n";
		}
		$barcodeText = $row['health_card_no'];  // The text to be encoded in the barcode
		$barcodeType = 'C128';        // The barcode type

		// Generate the PDF content
		$pdf = new TCPDF();
	
		// Disable the header and footer lines
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$logo = '<img src="'.base_url().'assets/images/letter_logo_final.png" style="width:95px; 	height:70px;">';
		$title = '<div >
		
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Corporate Center, North Wing</p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Island City Mall Dampas Dist</p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Tagbilaran City, Bohol, 6300</p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Tel. no. 501-3000 local 1319</p>
	  </div>';
		
		$pdf->SetMargins(25, 10, 15);
		$pdf->setFont('times', '', 10);
		$pdf->AddPage();
		$html1 = '<div>  <p id="generated-date" style=" line-height: 0;">' . date("F j, Y") . '</p>
					<p></p>
					<p></p>
					<p style="font-weight: bold;line-height: 0;">JONE SIEGFRED L. SEPE</p>
					<p style="font-weight: bold;line-height: 0;">CEO/PRESIDENT</p>
					<p style="font-weight: bold; line-height: 0;">RAMIRO COMMUNITY HOSPITAL</p>
					<p style="line-height: 0;">0139 Gallares Street, Poblacion II,</p> 
					<p style="line-height: 0;">Tagbilaran City, Bohol, 0139</p>
		   		  </div>';

		$html2 = '<div >
						  <p style=" font-weight: bold; text-decoration: underline;">RE: Guarantee Letter for Payment Covered by Alturas Healthcare;</p>
						  <p style="line-height: 2 ;">Dear DR. SEPE;</p>
	
							<p>We are writing to confirm that <span style="font-weight: bold;text-transform: uppercase">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span>, a valued member of the Alturas Healthcare Program, has received medical services and treatments from your esteemed healthcare facility. We would like to assure you that we will cover applicable expenses incurred by our member during their visit, as outlined in our agreement with your organization.</p>
							<p style="line-height: 0;">Patient Details:</p>
							<p></p>
							<p style="line-height: 0;">Patient Name: '.$row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] . ' </p>
							<p style="line-height: 0;">Date of Birth: '.$row['date_of_birth'].'</p>
							<p style="line-height: 0;">Alturas Healthcare Program ID: '.$row['health_card_no'].'</p>
							<p style="line-height: 0;">NOA No: '.$row['noa_no'].'</p>
							<p></p>
							<p></p>
							<p >Therefore, in accordance with the terms and conditions of our agreement, Alturas Healthcare will be using this letter to guarantee payment of the bill amounting</span>'. (($row['cash_advance'] > 0) ? '<span style="font-weight: bold"> (PHP '.number_format($row['company_charge'], 2, '.', ',').')</span> to be charged to the company and <span style="font-weight: bold">(PHP '.number_format($row['cash_advance'], 2, '.', ',').')</span> as a cash advance payment, with the total amount of ' : '').'
							<span id="company_charge_words" style="font-weight: bold;text-transform: uppercase">' . $companyChargeWords . '</span> <span style="font-weight: bold">(PHP ' . $total . ')</span> only. We kindly request that you submit all relevant bills and supporting documentation for the services rendered to <span style="font-weight: bold;">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span> directly to our designated billing department.</p>
							<p >We appreciate your collaboration and dedication to providing exceptional healthcare services to our members. Your continued partnership with the Alturas Healthcare Program is instrumental in fulfilling our mission of delivering comprehensive and accessible healthcare to our beneficiaries.</p>
							<p >Thank you for your attention to this matter, and we look forward to a continued successful relationship.</p>
							<p  style="line-height: 3;">Yours sincerely,</p>
							</div>';
		
							$signature = '<div><img src="' . base_url() . 'uploads/doctor_signatures/' . $doc['doctor_signature'] . '" alt="Doctor Signature" style="height:auto;width:170px;vertical-align:baseline;"> </div>';
							$html3 = '<div>
										<p style="line-height: 0;text-transform: uppercase;">Dr. Michael D. Uy</span></p>
						
						
										<p style="line-height: 1">Company Physician</p>
									</div>';
							
									$pdf->setTitle('Guarantee letter');
									$pdf->WriteHtmlCell(0, 0, '', '', $logo, 0, 1, 0, true, 'R', true);
									$pdf->SetY($pdf->GetY()-2);
									$pdf->SetX($pdf->GetX()+133);
									$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'L', true);
									$pdf->SetY($pdf->GetY()-25);
									$pdf->writeHTML($html1);
									$pdf->writeHTML($html2);
									$pdf->SetX($pdf->GetX()-20);
									$pdf->WriteHtmlCell(0, 0, '', '', $signature, 0, 1, 0, true, 'L', true);
									$pdf->SetY($pdf->GetY()-15);
									$pdf->WriteHtmlCell(0, 0, '', '', $html3, 0, 1, 0, true, 'L', true);
									$pdf->write1DBarcode($barcodeText, $barcodeType, 25, 149, 80, 5);
									$pdf->write2DBarcode($formattedText, 'QRCODE', 155, 230, 30, 30);
							
	
		$fileName = 'guarantee_letter' . $noa_id . '.pdf';
		$pdf->Output(getcwd() . '/uploads/guarantee_letter/' . $fileName, 'F');
		$response = [
			'status' => 'saved-pdf',
			'filename' => $fileName
		];
		echo json_encode($response);

		}


	
		function convertNumberToWords($number) {
				// $number= number_format($number,2,'.',',');
				$number = str_replace(',', '', $number);
				$decimal = '';
				
				// Check if the number has a decimal part
				if (strpos($number, '.') !== false) {
					$parts = explode('.', $number);
					// var_dump('parts',$parts);
					$number = $parts[0];
					$decimal = intval($parts[1]); 
				}
				
				$words = [];
				$units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
				$teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
				$tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
				$thousands = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];
				
				// Convert the number to words
				if ($number == 0) {
					$words[] = 'zero';
				} else {
					// Split the number into groups of three digits
					$groups = str_split(strrev($number), 3);
					$groupCount = count($groups);
					
					// Process each group
					for ($i = 0; $i < $groupCount; $i++) {
						$group = (int) strrev($groups[$i]);
						if ($group > 0) {
							$groupWords = [];
							
							// Get the hundreds part
							$hundreds = floor($group / 100);
							if ($hundreds > 0) {
								$groupWords[] = $units[$hundreds] . ' hundred';
							}
							
							// Get the tens and units part
							$tensUnits = $group % 100;
							if ($tensUnits > 0) {
								if ($tensUnits < 10) {
									$groupWords[] = $units[$tensUnits];
								} elseif ($tensUnits < 20) {
									$groupWords[] = $teens[$tensUnits - 10];
								} else {
									$tensDigit = floor($tensUnits / 10);
									$unitsDigit = $tensUnits % 10;
									if ($tensDigit > 0) {
										$groupWords[] = $tens[$tensDigit];
									}
									if ($unitsDigit > 0) {
										$groupWords[] = $units[$unitsDigit];
									}
								}
							}
							
							// Add the thousands suffix
							if ($groupCount > 1 && $group > 0) {
								$groupWords[] = $thousands[$i];
							}
							
							// Combine the group words and add to the main words array
							$words = array_merge($groupWords, $words);
						}
					}
				}
				
				// Convert the decimal part to words
				if ($decimal !== 0) {
					$decimalWords = [];
					//  var_dump('decimal',$decimal);
					if ($decimal < 10) {
						$decimalWords[] = $units[$decimal];
					} elseif ($decimal < 20) {
						$decimalWords[] = $teens[$decimal - 10];
					} else {
						$tensDigit = floor($decimal / 10);
						$unitsDigit = $decimal % 10;
						if ($tensDigit > 0) {
							$decimalWords[] = $tens[$tensDigit];
						}
						if ($unitsDigit > 0) {
							$decimalWords[] = $units[$unitsDigit];
						}
					}
					
					$words[] = 'and ' . implode(' ', $decimalWords) . ' cents';
				}
				
				// Combine and format the final result
				$result = implode(' ', $words);
				$result = ucwords($result);
				
				return rtrim($result);
			}

}
