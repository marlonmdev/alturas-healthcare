<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/loa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	//====================================================================================================
	//EMERGENCY LOA
	//====================================================================================================
	function submit_loa_request() {
		$this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$request_type = $this->input->post('loa-request-type');

		switch (true) {
			case ($request_type == 'Emergency'):
				$this->form_validation->set_rules('hospital-name', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('admission-date', 'Date Hospitalized', 'required');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'admission_date_error' => form_error('admission-date'),
					];
					echo json_encode($response);
					exit();
				}
					// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($input_post['hospital-name']);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Invalid Healthcare Provider'
					];
					echo json_encode($response);
					exit();
				}else{
					$this->insert_loa($input_post, "", "", "");
				}
			break;
		}
	}

	function insert_loa($input_post, $med_services, $attending_physician, $rx_file) {
		// select the max loa_id from DB
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		$current_year = date('Y');
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);
		$emp_id = $input_post['emp-id'];
		$member = $this->loa_model->db_get_member_details1($emp_id);

		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $emp_id,
			'first_name' =>  $member['first_name'],
			'middle_name' =>  $member['middle_name'],
			'last_name' =>  $member['last_name'],
			'suffix' =>  $member['suffix'],
			'hcare_provider' => $input_post['hospital-name'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => ($med_services!=="")?implode(';', $med_services):"",
			'health_card_no' => $member['health_card_no'],
			'requesting_company' => $member['company'],
			'request_date' => date("Y-m-d"),
			'emerg_date' => (isset( $input_post['admission-date']))? $input_post['admission-date']:null,
			'chief_complaint' => (isset($input_post['chief-complaint']))?strip_tags($input_post['chief-complaint']):"",
			'requesting_physician' =>(isset($input_post['requesting-physician']))? ucwords($input_post['requesting-physician']):"",
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
			'status' => 'Pending',
			'requested_by' =>$this->session->userdata('user_id'),
		];
		$inserted = $this->loa_model->db_insert_loa_request($post_data);
		if (!$inserted) {
			$response = [
				'status' => 'save-error', 
				'message' => 'Failed to Save!'
			];
		}

		$response = [
			'status' => 'success', 
			'message' => 'Successfully Save!'
		];
		echo json_encode($response);
	}
	//====================================================================================================
	//END
	//====================================================================================================

	function edit_loa_request() {
		$loa_id = $this->uri->segment(5);
		$this->load->model('super_admin/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->loa_model->db_get_loa_info($loa_id);
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->loa_model->db_get_cost_types();
		$data['doctors'] = $this->loa_model->db_get_company_doctors();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/edit_loa_request');
		$this->load->view('templates/footer');
	}

	function update_loa_request() {
		$this->security->get_csrf_hash();
		$loa_id = $this->uri->segment(5);
		$input_post = $this->input->post(NULL, TRUE);
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$request_type = $this->input->post('loa-request-type');
		switch (true) {
			case ($request_type === 'Consultation'):
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					$response =[
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
					];
					echo json_encode($response);
				}else{
					$rx_file = '';
					$med_services = [];

					foreach ($physicians_tags as $physician_tag) :
						array_push($physician_arr, ucwords($physician_tag['value']));
					endforeach;
					$attending_physician = implode(', ', $physician_arr);
					$this->update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file);
				}
			break;

			case ($request_type === 'Diagnostic Test'):
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				$this->form_validation->set_rules('rx-file', '', 'callback_update_check_rx_file');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];
					echo json_encode($response);
				}else{
					$row = $this->loa_model->db_get_loa_attach_filename($loa_id);
					$db_filename = $row['rx_file'];
					$med_services = $this->input->post('med-services');

					foreach ($physicians_tags as $physician_tag) :
						array_push($physician_arr, ucwords($physician_tag['value']));
					endforeach;
					$attending_physician = implode(', ', $physician_arr);

					// if there is no filename selected set the one from database
					if (empty($_FILES['rx-file']['name'])) {
						$rx_file = $db_filename;
					} else {
						// if there is a new file to be uploaded
						$config['upload_path'] = './uploads/';
						$config['allowed_types'] = 'jpg|jpeg|png';
						$config['encrypt_name'] = TRUE;
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('rx-file')) {
							$response = [
								'status' => 'save-error', 
								'message' => 'RX/Request File Not Uploaded!'
							];
							echo json_encode($response);
							exit();
						}
						$upload_data = $this->upload->data();
						$rx_file = $upload_data['file_name'];
						// remove the old file when the new file was uploaded
						if ($row['rx_file'] !== '') {
							$file_path = './uploads/loa_attachments/' . $row['rx_file'];
							file_exists($file_path) ? unlink($file_path) : '';
						}
					}
					// Call function update_loa			
					$this->update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file);
				}
				break;
		}
	}

	function update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file) {
		$post_data = [
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => implode(';', $med_services),
			'health_card_no' => $input_post['health-card-no'],
			'chief_complaint' => strip_tags($input_post['chief-complaint']),
			'requesting_physician' => ucwords($input_post['requesting-physician']),
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
		];
		$updated = $this->loa_model->db_update_loa_request($loa_id, $post_data);
		if (!$updated) {
			$response = [
				'status' => 'save-error', 
				'message' => 'LOA Request Update Failed'
			];
		}
		$response = [
			'status' => 'success', 
			'message' => 'LOA Request Updated Successfully'
		];
		echo json_encode($response);
	}

	//==================================================
	//LETTER OF AUTHORIZATION (PENDING)
	//==================================================
	function fetch_all_pending_loa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$hcc_emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables_pending($status);

		$data = [];
		foreach ($list as $loa) {
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$view_url = base_url() . 'healthcare-coordinator/loa/requested-loa/edit/' . $loa['loa_id'];
			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-cyan text-black"><b>'.$loa['loa_no'].'</b></mark>';
			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.
			If it is not empty, it will display the text "for Approval". */
			if($loa['work_related'] == ''){
				$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';
			}else{
				$custom_status = '<span class="badge rounded-pill bg-cyan">for Approval</span>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" class="me-2" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Tagging"><i class="mdi mdi-tag-plus fs-4 text-primary"></i></a>';

			if($loa['spot_report_file'] || $loa['incident_report_file'] || $loa['police_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\',\'' . $loa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Reports"><i class="mdi mdi-teamviewer fs-4 text-warning"></i></a>';
			}else{
				$custom_actions .= '';
			}
			

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				$view_file = 'None';
				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_pending($status),
			"recordsFiltered" => $this->loa_model->count_filtered_pending($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function set_charge_type(){
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->input->post('loa-id'), 'decrypt');
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

			$uploaded_files = array();
			$error_occurred = FALSE;

			// Define the upload paths for each file
			$file_paths = array(
				'spot-report' => './uploads/spot_reports/',
				'incident-report' => './uploads/incident_reports/',
				'police-report' => './uploads/police_reports/',
			);

			// Iterate over each file input and perform the upload
			$file_inputs = array('spot-report', 'incident-report','police-report');
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
					'spot_report_file' => isset($uploaded_files['spot-report']) ? $uploaded_files['spot-report']['file_name'] : '',
					'incident_report_file' => isset($uploaded_files['incident-report']) ? $uploaded_files['incident-report']['file_name'] : '',
					'police_report_file' => isset($uploaded_files['police-report']) ? $uploaded_files['police-report']['file_name'] : '',
					'date_uploaded' => date('Y-m-d')
				];

				$updated = $this->loa_model->db_update_loa_charge_type($loa_id, $data);

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

	//==================================================
	//END
	//==================================================

	//==================================================
	//LETTER OF AUTHORIZATION (APPROVED)
	//==================================================

	function fetch_all_approved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->loa_model->get_datatables_approved($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-cyan text-black"><b>'.$loa['loa_no'].'</b></mark>';
			$expiry_date = $loa['expiration_date'] ? date('m/d/Y', strtotime($loa['expiration_date'])): 'None';
			$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

			if($loa['spot_report_file'] || $loa['incident_report_file'] || $loa['police_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\',\'' . $loa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Reports"><i class="mdi mdi-teamviewer fs-4 text-warning"></i></a>';
			}else{
				$custom_actions .= '';
			}
			
			$custom_actions .= '<a class="me-1" href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print"><i class="mdi mdi-printer fs-4 text-primary"></i></a>';

			if($loa['loa_request_type'] == 'Emergency'){
				$custom_actions .= '';
			}else{
				$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/update-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Performed"><i class="mdi mdi-playlist-check fs-4 text-success"></i></a>';
			}

			$exists = $this->loa_model->check_loa_no($loa['loa_id']);
			if($loa['loa_request_type'] == 'Consultation'){
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-4 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '';
				}
			}else{
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-4 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '';
				}
			}				

			$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="loaCancellation(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\')" data-bs-toggle="tooltip" title="Cancel LOA Request"><i class="mdi mdi-close-circle fs-4 text-danger"></i></a>';
	
			$custom_status = '<span class="badge rounded-pill bg-success">' . $loa['status'] . '</span>';
		
			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $expiry_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_approved($status),
			"recordsFiltered" => $this->loa_model->count_filtered_approved($status),
			"data" => $data,
		];
		echo json_encode($output);
	}
	//==================================================
	//END
	//==================================================

	//==================================================
	//LETTER OF AUTHORIZATION (DISAPPROVED)
	//==================================================
	function fetch_all_disapproved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->loa_model->get_datatables_disapproved($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-cyan text-black"><b>'.$loa['loa_no'].'</b></mark>';
			$custom_date = date("m/d/Y", strtotime($loa['request_date']));
			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_disapproved($status),
			"recordsFiltered" => $this->loa_model->count_filtered_disapproved($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	//==================================================
	//END
	//==================================================

	//==================================================
	//LETTER OF AUTHORIZATION (COMPLETED)
	//==================================================

	function fetch_all_completed_loa() {
		$this->security->get_csrf_hash();
		$list = $this->loa_model->get_completed_datatables();
		$data = [];
		foreach ($list as $loa) {
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-cyan text-black"><b>'.$loa['loa_no'].'</b></mark>';
			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			if($loa['completed'] == 1){
				$custom_status = '<span class="badge rounded-pill bg-success">Completed</span>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Info"><i class="mdi mdi-information fs-4 text-info"></i></a>';
			
			if($loa['loa_request_type'] == 'Consultation'){

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Schedule"><i class="mdi mdi-clipboard-text fs-4 ps-1 text-danger"></i></a>';
			}else{
				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Schedule"><i class="mdi mdi-clipboard-text fs-4 ps-1 text-danger"></i></a>';	
			}

			$rescheduled = $this->loa_model->check_if_status_cancelled($loa['loa_id']);
			if($rescheduled){
				$resched = $this->loa_model->check_if_done_created_new_loa($loa['loa_id']);
				if($resched['reffered'] != 1){
					$custom_actions .= '<a href="JavaScript:void(0)" onclick="showManagersKeyModal(\''.$loa_id.'\')" data-bs-toggle="tooltip" title="Create New LOA"><i class="mdi mdi-note-plus fs-2 text-cyan"></i></a>';
				}else{
					$custom_actions .= '<i class="mdi mdi-note-plus fs-2 text-secondary" title="Done Creating new LOA"></i>';
				}
			}
			
			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_c(),
			"recordsFiltered" => $this->loa_model->count_filtered_c(),
			"data" => $data,
		];
		echo json_encode($output);
	}
	//==================================================
	//END
	//==================================================

	function fetch_all_rescheduled_loa() {
		$this->security->get_csrf_hash();
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$status = 'Referred';
		$list = $this->loa_model->get_datatables_referral($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-success">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewReschedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-1" href="' . base_url() . 'healthcare-coordinator/loa/scheduled-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/rescheduled-loa/update-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Update LOA"><i class="mdi mdi-playlist-check fs-2 text-success"></i></a>';
			
			$exists = $this->loa_model->check_loa_no($loa['loa_id']);
			if($loa['loa_request_type'] == 'Consultation'){
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '<i class="me-1" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-secondary"></i></i>';
				}
			}else{
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '<i class="me-1" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-secondary"></i></i>';
				}
			}	
			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_referral($status),
			"recordsFiltered" => $this->loa_model->count_filtered_referral($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_expired_loa(){
		$this->security->get_csrf_hash();
		$status = 'Expired';
		$list = $this->loa_model->get_datatables_expired($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$expiry_date = $loa['expiration_date'] ? date("m/d/Y", strtotime($loa['expiration_date'])) : 'None';

			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewExpiredLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\')" data-bs-toggle="tooltip" title="Back Date LOA"><i class="mdi mdi-update fs-2 text-cyan"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $expiry_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_expired($status),
			"recordsFiltered" => $this->loa_model->count_filtered_expired($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_cancelled_loa(){
		$this->security->get_csrf_hash();
		$status = 'Cancelled';
		$list = $this->loa_model->get_datatables_cancelled($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCancelledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_cancelled($status),
			"recordsFiltered" => $this->loa_model->count_filtered_cancelled($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_billed_loa() {
		$this->security->get_csrf_hash();
		$status = 'Billed';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-info">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			
			if($loa['loa_request_type'] == 'Consultation'){

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-danger"></i></a>';

				$billing = $this->loa_model->check_if_already_added($loa['loa_id']);
				if($billing['for_charging'] == 1){
					$custom_actions .= '<i title="Matched with Billing"><i class="mdi mdi-compare fs-2 text-secondary"></i></i>';
				}else{
					$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/match_with_billing/' . $loa_id . '" data-bs-toggle="tooltip" title="Match with Billing"><i class="mdi mdi-compare fs-2 text-success"></i></a>';
				}
			}else{

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-danger"></i></a>';

				$billing = $this->loa_model->check_if_already_added($loa['loa_id']);
				if($billing['for_charging'] == 1){
					$custom_actions .= '<i title="Matched with Billing"><i class="mdi mdi-compare fs-2 text-secondary"></i></i>';
				}else{
					$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/match_with_billing/' . $loa_id . '" data-bs-toggle="tooltip" title="Match with Billing"><i class="mdi mdi-compare fs-2 text-success"></i></a>';
				}
			
				
			}

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_for_payment_loa() {
		$this->security->get_csrf_hash();
		// $status = 'Payable';
		$for_payment = $this->loa_model->fetch_for_payment_bill();
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

			// $bill_no = $this->myhash->hasher($bill['bill_no'], 'encrypt');
			// dapat maka encrypt sa bill_no

			$action_customs = '<a href="'.base_url().'healthcare-coordinator/bill/billed/fetch-payable/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

			// $action_customs .= '<a href="'.base_url().'healthcare-coordinator/bill/billed/charging/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Charging"><i class="mdi mdi-file-document-box fs-2 text-danger"></i></a>';

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

	function history() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$for_payment = $this->loa_model->history($status);
		$data = [];
		foreach($for_payment as $bill){
			$row = [];
			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
			$status_custom = '<span class="badge rounded-pill bg-success text-white">'.$bill['status'].'</span>';
			$action_customs = '<a href="'.base_url().'healthcare-coordinator/bill/billed/fetch-payable/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
			$action_customs .= '<a href="'.base_url().'healthcare-coordinator/bill/billed/charging/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Charging"><i class="mdi mdi-file-document-box fs-2 text-danger"></i></a>';

			$row[] = $fullname;
			$row[] = $bill['acc_number'];
			$row[] = $bill['check_num'];
			$row[] = date('F d, Y', strtotime($bill['check_date']));
			$row[] = $bill['bank'];
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

	function fetch_monthly_bill() {
		$token = $this->security->get_csrf_hash();
		$bill_no = $this->uri->segment(5);
		$billing = $this->loa_model->monthly_bill_datatable($bill_no);
		$data = [];

		foreach($billing as $bill){
			$row = [];
			$loa_id = $this->myhash->hasher($bill['loa_id'], 'encrypt');
			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
			$coordinator_bill = '<a href="JavaScript:void(0)" onclick="viewCoordinatorBill(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Coordinator Billing"><i class="mdi mdi-eye text-dark"></i>View</a>';
			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf text-danger"></i></a>';
			$detailed_pdf_bill = '<a href="JavaScript:void(0)" onclick="viewDetailedPDFBill(\'' . $bill['itemized_bill'] . '\' , \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf text-danger"></i></a>';

			// $record_diagnostic = '<a href="JavaScript:void(0)" onclick="PatientRecordDiagnostic(\'' . $bill['loa_no']. '\')" data-bs-toggle="tooltip" title="View Coordinator Billing"><i class="mdi mdi-eye text-dark"></i>View Record</a>';

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

			$row[] = $bill['billing_no'];
			$row[] = $bill['loa_no'];
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = $percent_custom;
			$row[] = $bill['loa_request_type'];
			$row[] = number_format($bill['net_bill'], 2, '.', ',');
			$row[] = $pdf_bill;
			$row[] = $detailed_pdf_bill;
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
		echo json_encode($output);
	}

	function get_data_patient_record() {

		$bill_no = $this->uri->segment(5);
		$row = $this->loa_model->get_monthly_bill($bill_no);
		// $data['loa'] = $row;
		// $data['emp_id'] = $row['emp_id'];
		// $data['itemized_bill'] = $this->loa_model->get_itemized_bill($row['emp_id']);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['tbl2_loa_id'],
			'loa_no' => $row['tbl2_loa_no'],
			'first_name' => $row['tbl4_fname'],
			'middle_name' => $row['tbl4_mname'],
			'last_name' => $row['tbl4_lname'],
			'suffix' => $row['tbl4_suffix'],
			'home_address' => $row['home_address'],
			'date_of_birth' => $row['date_of_birth'],
			'percentage' => $row['tbl2_percentage'],
			'percentage' => $row['tbl2_percentage'],
		];
		echo json_encode($response);
		// var_dump($response);
	}



	function fetch_billing_for_charging() {
		$this->security->get_csrf_hash();
		$bill_no = $this->uri->segment(5);
		$billing = $this->loa_model->get_billed_for_charging($bill_no);
		$data = [];

		foreach($billing as $bill){
			$row = [];
			$company_charge = '';
			$personal_charge = '';
			$remaining_mbl = '';

			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

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
						$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
					}else if($net_bill > $previous_mbl){
						$company_charge = number_format($previous_mbl,2, '.',',');
						$personal_charge = number_format($net_bill - $previous_mbl,2, '.',',');
						$remaining_mbl = number_format(0,2, '.',',');
					}
				}else if($bill['percentage'] != ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - floatval($net_bill),2, '.',',');
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
			
			$row[] = $bill['loa_no'];
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = $percent_custom;
			$row[] = number_format($bill['net_bill'],2, '.',',');
			$row[] = $company_charge;
			$row[] = $personal_charge;
			$row[] = number_format($bill['remaining_balance'],2, '.',',');
			$row[] = $remaining_mbl;
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}


	function get_pending_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_detail($loa_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['work_related'] != '' ? 'for Approval': $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_approved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('ho_accounting/Loa_model');
		$row = $this->Loa_model->db_get_loa_detail($loa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->Loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$cost_types = $this->Loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	

	function get_disapproved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_detail($loa_id);
		$doctor_name = "";
		if ($row['disapproved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => $row['date_of_birth'] ? date("F d, Y", strtotime($row['date_of_birth'])) : '',
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => $row['request_date'] ? date("F d, Y", strtotime($row['request_date'])) : '',
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => ($row['doctor_name']!==null)?$row['doctor_name']:'None',
			'attending_physician' => ($row['attending_physician']!==null)?$row['attending_physician']:'None',
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => $row['approved_on'] ? date("F d, Y", strtotime($row['approved_on'])) : '',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_cancelled_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_detail($loa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}
		$cost_types = $this->loa_model->db_get_cost_types();

		// Calculate Age
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'request_date' => $row['request_date'] ? date("F d, Y", strtotime($row['request_date'])) : '',
			'cancelled_by' => $row['cancelled_by'],
			'cancelled_on' => $row['cancelled_on'] ? date("F d, Y", strtotime($row['cancelled_on'])) : '',
			'cancellation_reason' => $row['cancellation_reason'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'health_card_no' => $row['health_card_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => $row['date_of_birth'] ? date("F d, Y", strtotime($row['date_of_birth'])) : '',
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
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'requesting_company' => $row['requesting_company'],
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => ($row['doctor_name']!==null)?$row['doctor_name']:'None',
			'attending_physician' => ($row['attending_physician']!==null)?$row['attending_physician']:'None',
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'approved_by' => $doctor_name,
			
		];
		echo json_encode($response);
	}

	function get_completed_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_detail($loa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => $row['date_of_birth'] ?	date("F d, Y", strtotime($row['date_of_birth'])) : '',
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => $row['request_date'] ? date("F d, Y", strtotime($row['request_date'])) : '',
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => ($row['doctor_name']!==null)?$row['doctor_name']:'None',
			'attending_physician' => ($row['attending_physician']!==null)?$row['attending_physician']:'None',
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => $row['approved_on'] ? date("F d, Y", strtotime($row['approved_on'])) : '',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_resched_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_resched_loa_details($loa_id);
		
		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $row['doctor_name'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'requested_by' => $row['requested_by'],
			'approved_by' => $row['doctor_name'],
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
		];
		echo json_encode($response);
	}

	function get_expired_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_resched_loa_details($loa_id);
		
		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $med_serv,
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => ($row['doctor_name']!==null)?$row['doctor_name']:'None',
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'requested_by' => $row['requested_by'],
			'approved_by' => $row['doctor_name'],
			'approved_on' => $row['approved_on'],
			'expiry_date' => $row['expiration_date']
		];
		echo json_encode($response);
	}


	function cancel_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->uri->segment(5);
		$deleted = $this->loa_model->db_cancel_loa($loa_id);
		if ($deleted) {
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'LOA Request Cancelled Successfully!'
			];
		} else {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'LOA Request Cancellation Failed!'
			];
		}
		echo json_encode($response);
	}

	

	function generate_printable_loa() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['mbl'] = $this->loa_model->db_get_member_mbl($exist['emp_id']);
		$data['cost_types'] = $this->loa_model->db_get_cost_types();
		$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
		$data['doc'] = $this->loa_model->db_get_doctor_by_id($exist['approved_by']);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/generate_printable_loa');
			$this->load->view('templates/footer');
		}
	}

	function generate_rescheduled_loa() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['mbl'] = $this->loa_model->db_get_member_mbl($exist['emp_id']);
		$data['cost_types'] = $this->loa_model->db_get_cost_types();
		$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
		$data['doc'] = $this->loa_model->db_get_doctor_by_id($exist['approved_by']);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/generate_printable_resched_loa');
			$this->load->view('templates/footer');
		}
	}

	function fetch_cancellation_requests() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$info = $this->loa_model->get_cancel_datatables($status);
		$dataCancellations = [];

		foreach($info as $data){
			$row = [];
			$loa_id = $this->myhash->hasher($data['loa_id'], 'encrypt');

			$fullname = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'] . ' ' . $data['suffix'];

			$custom_reason = '<a class="text-info fs-6 fw-bold" href="JavaScript:void(0)" onclick="viewReason(\''.$data['cancellation_reason'].'\')"><u>View Reason</u></a>';

			$custom_status = '<span class="badge rounded-pill bg-warning text-white ps-2 pe-2">'. $data['status'] .'</span>';

			$custom_action = '<a href="JavaScript:void(0)" onclick="confirmRequest(\''. $loa_id .'\')"><i class="mdi mdi-thumb-up text-info fs-3" title="Approve"></i></a>';

			$custom_action .= '<a href="JavaScript:void(0)" onclick="disapproveRequest(\''. $loa_id .'\')"><i class="mdi mdi-thumb-down-outline text-danger fs-3 ps-2" title="Disapprove"></i></a>';

			$short_hp_name = strlen($data['hp_name']) > 24 ? substr($data['hp_name'], 0, 24) . "..." : $data['hp_name'];

			$row[] = $data['loa_no'];
			$row[] = $fullname;
			$row[] = date('m/d/Y', strtotime($data['requested_on']));
			$row[] = $short_hp_name;
			$row[] = $custom_reason;
			$row[] = $custom_status;
			$row[] = $custom_action;
			$dataCancellations[] = $row;
		}
		$response = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_cancel($status),
			"recordsFiltered" => $this->loa_model->count_cancel_filtered($status),
			"data" => $dataCancellations,
		];
		echo json_encode($response);
		
	}

	function set_cancellation_approved() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$confirm_by = $this->session->userdata('fullname');
		$confirmed_on = date('Y-m-d');
		$confirmed = $this->loa_model->set_cancel_approved($loa_id, $confirm_by, $confirmed_on);
		
		if($confirmed){
			$status = 'Cancelled';
			$this->loa_model->set_cloa_request_status($loa_id, $status);
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Cancellation Approved!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'error',
				'message' => 'Cancellation Failed to Approved!'
			]);
		}
	}

	function set_cancellation_disapproved() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$disapproved_by = $this->session->userdata('fullname');
		$disapproved_on = date('Y-m-d');
		$confirmed = $this->loa_model->set_cancel_disapproved($loa_id, $disapproved_by, $disapproved_on);
		
		if($confirmed){
			$status = 'Approved';
			$this->loa_model->set_cloa_request_status($loa_id, $status);
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Cancellation Disapproved!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'error',
				'message' => 'Cancellation Failed to Disapproved!'
			]);
		}
	}

	function fetch_approved_cancellations() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$info = $this->loa_model->get_cancel_datatables($status);
		$dataCancellations = [];

		foreach($info as $data){
			$row = [];
			$loa_id = $this->myhash->hasher($data['loa_id'], 'encrypt');

			$fullname = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'] . ' ' . $data['suffix'];

			$custom_reason = '<a class="text-info fs-6 fw-bold" href="JavaScript:void(0)" onclick="viewReason(\''.$data['cancellation_reason'].'\')"><u>View Reason</u></a>';

			$custom_status = '<span class="badge rounded-pill bg-success text-white ps-2 pe-2">'. $data['status'] .'</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$short_hp_name = strlen($data['hp_name']) > 24 ? substr($data['hp_name'], 0, 24) . "..." : $data['hp_name'];

			$row[] = $data['loa_no'];
			$row[] = $fullname;
			$row[] = $short_hp_name;
			$row[] = $custom_reason;
			$row[] = $data['confirmed_on'];
			$row[] = $data['confirmed_by'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$dataCancellations[] = $row;
		}
		$response = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_cancel($status),
			"recordsFiltered" => $this->loa_model->count_cancel_filtered($status),
			"data" => $dataCancellations,
		];
		echo json_encode($response);
	}


	function cancel_approved_loa(){
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');

		$this->form_validation->set_rules('cancellation_reason', 'Reason for Cancellation', 'trim|required|max_length[2000]');

		if ($this->form_validation->run() == FALSE) {
			$response = [
				//'token' => $token,
				'status' => 'error',
				'cancellation_reason_error' => form_error('cancellation_reason'),
			];
		} else {
			$post_data = [
				'status'              => 'Cancelled',
				'cancellation_reason' => $this->input->post('cancellation_reason', TRUE),
				'cancelled_on' 				=> date("Y-m-d"),
				'cancelled_by' 				=> $this->session->userdata('fullname'),
			];


			$updated = $this->loa_model->db_update_loa_request($loa_id, $post_data);
			if (!$updated) {
				$response = [
					'status' => 'save-error', 
					'message' => 'LOA Request Cancel Failed'
				];
			}
			$response = [
				'status' => 'success', 
				'message' => 'LOA Request Cancelled Successfully'
			];
		}

		echo json_encode($response);
	}

	function fetch_disapproved_cancellations() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$info = $this->loa_model->get_cancel_datatables($status);
		$dataCancellations = [];

		foreach($info as $data){
			$row = [];
			$loa_id = $this->myhash->hasher($data['loa_id'], 'encrypt');

			$fullname = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'] . ' ' . $data['suffix'];

			$custom_reason = '<a class="text-info fs-6 fw-bold" href="JavaScript:void(0)" onclick="viewReason(\''.$data['cancellation_reason'].'\')"><u>View Reason</u></a>';

			$custom_status = '<span class="badge rounded-pill bg-danger text-white ps-2 pe-2">'. $data['status'] .'</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$short_hp_name = strlen($data['hp_name']) > 24 ? substr($data['hp_name'], 0, 24) . "..." : $data['hp_name'];

			$row[] = $data['loa_no'];
			$row[] = $fullname;
			$row[] = $short_hp_name;
			$row[] = $custom_reason;
			$row[] = $data['disapproved_on'];
			$row[] = $data['disapproved_by'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$dataCancellations[] = $row;
		}
		$response = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_cancel($status),
			"recordsFiltered" => $this->loa_model->count_cancel_filtered($status),
			"data" => $dataCancellations,
		];
		echo json_encode($response);
	}


	function view_tag_loa_completed() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_approved_loa($loa_id);
		$existing = $this->loa_model->check_loa_no($loa_id);
		// var_dump('request type',$loa);
		// var_dump('loa id',$loa_id);
		// $view_page ="";
		// var_dump($loa);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		
		if(!$existing){
			$data['user_role'] = $this->session->userdata('user_role');
			$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hp_ID($loa['hcare_provider']);
			$data['emp_id'] = $loa['emp_id'];
			$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
			$data['loa_no'] = $loa['loa_no'];
			$data['hc_provider'] = $loa['hp_name'];
			$data['hp_id'] = $loa['hp_id'];
			$data['loa_id'] = $loa['loa_id'];
			$data['med_services'] = $loa['med_services'];
			$data['request_type'] = $loa['loa_request_type'];
			$data['approved_on'] = $loa['approved_on'];
			$data['expired_on'] = $loa['expiration_date'];
			$data['bar'] = $this->loa_model->bar_pending();
			$data['bar1'] = $this->loa_model->bar_approved();
			$data['bar2'] = $this->loa_model->bar_completed();
			$data['bar3'] = $this->loa_model->bar_referral();
			$data['bar4'] = $this->loa_model->bar_expired();
			$data['bar_Billed'] = $this->loa_model->bar_billed();
			$data['bar5'] = $this->loa_model->bar_pending_noa();
			$data['bar6'] = $this->loa_model->bar_approved_noa();
			$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
			$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
			
			if($loa['loa_request_type'] == 'Consultation'){
				$view_page ='schedule_consultation.php';
			}else if($loa['loa_request_type'] == 'Diagnostic Test'){
				$view_page ='schedule_diagnostic.php';
			}
			// else if($loa['loa_request_type'] == 'Emergency'){
			// 	$view_page ='schedule_consultation.php';
			// }
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/'.$view_page);
			$this->load->view('templates/footer');

		}else{
			$loa_info['user_role'] = $this->session->userdata('user_role');
			$loa_info['loaInfo'] = $this->loa_model->get_performed_loa_data($loa_id);
			$loa_info['hc_provider'] = $loa['hp_name'];

			$loa_info['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
			$loa_info['hp_id'] = $loa['hp_id'];
			$loa_info['loa_id'] = $loa['loa_id'];
			$loa_info['emp_id'] = $loa['emp_id'];
			$loa_info['loa_no'] = $loa['loa_no'];
			$loa_info['request_type'] = $loa['loa_request_type'];
			$loa_info['approved_on'] = $loa['approved_on'];
			$loa_info['expired_on'] = $loa['expiration_date'];
			$loa_info['bar'] = $this->loa_model->bar_pending();
			$loa_info['bar1'] = $this->loa_model->bar_approved();
			$loa_info['bar2'] = $this->loa_model->bar_completed();
			$loa_info['bar3'] = $this->loa_model->bar_referral();
			$loa_info['bar4'] = $this->loa_model->bar_expired();
			$loa_info['bar_Billed'] = $this->loa_model->bar_billed();
			$loa_info['bar5'] = $this->loa_model->bar_pending_noa();
			$loa_info['bar6'] = $this->loa_model->bar_approved_noa();
			$loa_info['bar_Initial'] = $this->loa_model->bar_initial_noa();
			$loa_info['bar_Billed2'] = $this->loa_model->bar_billed_noa();

			
			if($loa['loa_request_type'] == 'Consultation'){
				$loa_info['loa_data'] = $this->loa_model->get_consultation_data($loa_id);
				$view_page ='edit_tag_complete_consultation.php';
			}else if($loa['loa_request_type'] == 'Diagnostic Test'){
				$view_page ='edit_tagged_loa_to_complete.php';
			}
			$this->load->view('templates/header', $loa_info);
			$this->load->view('healthcare_coordinator_panel/loa/'.$view_page.'');
			$this->load->view('templates/footer');
		}
	}

	function submit_performed_diagnostic() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp-id', TRUE);
		$hp_id = $this->input->post('hp-id', TRUE);
		$loa_id = $this->input->post('loa-id', TRUE);
		$loa_no = $this->input->post('loa-num', TRUE);
		$request_type = $this->input->post('request-type', TRUE);
		$ctype_id = $this->input->post('ctype_id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_performed = $this->input->post('date', TRUE);
		$time_performed = $this->input->post('time', TRUE);
		$reason = $this->input->post('reason', TRUE);
		$physician_fname = $this->input->post('physician-fname', TRUE);
		$physician_mname = $this->input->post('physician-mname', TRUE);
		$physician_lname = $this->input->post('physician-lname', TRUE);
		$added_by = $this->session->userdata('fullname');
		$added_on = date('Y-m-d');
	
		$post_data = [];
		for($x = 0; $x < count($ctype_id); $x++ ){
			$post_data[] = [
				'emp_id' => $emp_id,
				'hp_id' => $hp_id,
				'loa_id' => $loa_id,
				'loa_no' => $loa_no,
				'request_type' => $request_type,
				'ctype_id' =>$ctype_id[$x],
				'status' => $status[$x],
				'reason_cancellation' => ucfirst($reason[$x]),
				'date_performed' => $date_performed[$x],
				'time_performed' => $time_performed[$x],
				'physician_fname' => ucwords($physician_fname[$x]),
				'physician_mname' => ucwords($physician_mname[$x]),
				'physician_lname' => ucwords($physician_lname[$x]),
				'added_by' => $added_by,
				'added_on' => $added_on
			];
		}
			
		$inserted = $this->loa_model->insert_performed_loa_info($post_data);

			
		$hasEmpty = $this->loa_model->check_if_status_empty($loa_id);
		if(!$hasEmpty){
			$status = 'Completed';
			$this->loa_model->set_loa_status_completed($loa_id, $status);
		}

		$cancelled = $this->loa_model->check_if_service_cancelled($loa_id);
		if($cancelled){
			$service = $this->loa_model->get_cancelled_service($loa_id);

			foreach($service as $number){
				$number_to_remove = $number['ctype_id'];

				$this->remove_service_from_field($loa_id, $number_to_remove);
			}
		}

		$inserted = $this->loa_model->update_performed_fees($loa_id);


		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Successfully Saved!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Failed to Upload!'
			]);
		}
		// var_dump($inserted);
	}

	function submit_performed_consultation() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp-id', TRUE);
		$hp_id = $this->input->post('hp-id', TRUE);
		$loa_id = $this->input->post('loa-id', TRUE);
		$loa_no = $this->input->post('loa-num', TRUE);
		$request_type = $this->input->post('request-type', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_performed = $this->input->post('date', TRUE);
		$time_performed = $this->input->post('time', TRUE);
		$physician_fname = ucwords($this->input->post('physician-fname', TRUE));
		$physician_mname = ucwords($this->input->post('physician-mname', TRUE));
		$physician_lname = ucwords($this->input->post('physician-lname', TRUE));
		$added_by = $this->session->userdata('fullname');
		$added_on = date('Y-m-d');	

		$post_data = [
			'emp_id' => $emp_id,
			'hp_id' => $hp_id,
			'loa_id' => $loa_id,
			'loa_no' => $loa_no,
			'request_type' => $request_type,
			'status' => $status,
			'date_performed' => $date_performed,
			'time_performed' => $time_performed,
			'physician_fname' => $physician_fname,
			'physician_mname' => $physician_mname,
			'physician_lname' => $physician_lname,
			'added_by' => $added_by,
			'added_on' => $added_on
		];

		$inserted = $this->loa_model->insert_performed_loa_consult($post_data);
		$inserted = $this->loa_model->update_performed_fees($loa_id);
			
		$performed = $this->loa_model->check_if_all_status_performed($loa_id);
		if($performed){
			$status = 'Completed';
			$this->loa_model->set_loa_status_completed($loa_id, $status);
		}

		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Updated Successfully'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Update Failed'
			]);
		}
	}
	
	function submit_edited_loa_info() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->input->post('loa-id', TRUE);
		$ctype_id = $this->input->post('ctype_id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_performed = $this->input->post('date', TRUE);
		$time_performed = $this->input->post('time', TRUE);
		// $resched_date = $this->input->post('resched-date', TRUE);
		$reason = $this->input->post('reason', TRUE);
		$physician_fname = $this->input->post('physician-fname', TRUE);
		$physician_mname = $this->input->post('physician-mname', TRUE);
		$physician_lname = $this->input->post('physician-lname', TRUE);
		$edited_by = $this->session->userdata('fullname');
		$edited_on = date('Y-m-d');	

		$post_data = [];
		
		for($x = 0; $x < count($ctype_id); $x++ ){
			$post_data[] = [
				'ctype_id' =>$ctype_id[$x],
				'status' => $status[$x],
				'reason_cancellation' => ucfirst($reason[$x]),
				'date_performed' => $date_performed[$x],
				'time_performed' => $time_performed[$x],
				// 'reschedule_on' => $resched_date[$x],
				'physician_fname' => ucwords($physician_fname[$x]),
				'physician_mname' => ucwords($physician_mname[$x]),
				'physician_lname' => ucwords($physician_lname[$x]),
				'edited_by' => $edited_by,
				'edited_on' => $edited_on
			];

			$updated = $this->loa_model->insert_edited_performed_loa_info($post_data, $loa_id);
		}

		echo json_encode([
			'token' => $token,
			'status' => 'success',
			'message' => 'Data Uploaded Successfully!'
		]);	

		$hasEmpty = $this->loa_model->check_if_status_empty($loa_id);
		if(!$hasEmpty){
			$status = 'Completed';
			$this->loa_model->set_loa_status_completed($loa_id, $status);
		}
		
	}

	function submit_edited_consultation_loa() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->input->post('loa-id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_time_performed = $this->input->post('date', TRUE);
		$physician = $this->input->post('physician', TRUE);
		$edited_by = $this->session->userdata('fullname');
		$edited_on = date('Y-m-d');	
 
		$post_data = [
			'status' => $status,
			'date_time_performed' => $date_time_performed,
			'physician' => $physician,
			'edited_by' => $edited_by,
			'edited_on' => $edited_on
		];
		$update = $this->loa_model->update_consulation_loa_info($post_data, $loa_id);

		$performed = $this->loa_model->check_if_all_status_performed($loa_id);
		
		if($performed){
			$status = 'Completed';
			$this->loa_model->set_loa_status_completed($loa_id, $status);
		}

		echo json_encode([
			'token' => $token,
			'status' => 'success',
			'message' => 'Data Uploaded Successfully!'
		]);	

	}

	function fetch_performed_loa_info() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa_info = $this->loa_model->fetch_per_loa_info($loa_id);

		echo json_encode($loa_info);
	}

	function fetch_performed_consult_loa() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa_info = $this->loa_model->fetch_per_consult_loa_info($loa_id);

		echo json_encode($loa_info);
	}

	

	

	function remove_service_from_field($passed_id, $number_to_remove) {
		$row = $this->loa_model->db_get_loa($passed_id);
		$field_value = $row['med_services'];
		$values_array = explode(";", $field_value);

		if (in_array($number_to_remove, $values_array)) {
			unset($values_array[array_search($number_to_remove, $values_array)]);
			$new_field_value = implode(";", $values_array);
			$result = $this->loa_model->db_update_loa_med_services($passed_id, $new_field_value);
		}
	}

	function remove_number_from_field($passed_id, $number_to_remove) {
		$row = $this->loa_model->db_get_loa($passed_id);
		$field_value = $row['med_services'];

		// Split the field value into an array using ";" delimiter
		$values_array = explode(";", $field_value);

		// Check if the number exists in the array
		for($i = 0; $i < count($number_to_remove); $i++){

			if (in_array($number_to_remove[$i], $values_array)) {
				// Remove the number from the array
				unset($values_array[array_search($number_to_remove[$i], $values_array)]);
		
				// Join the remaining values in the array back into a string using ";" delimiter
				$new_field_value = implode(";", $values_array);
		
				// Update the database field with the new value
				$result = $this->loa_model->db_update_loa_med_services($passed_id, $new_field_value);
				// $this->db->set('my_field', $new_field_value)->update('my_table');
			}

		}
	}

	function create_rescheduled_to_new_loa() {
		$data['token'] = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$approved_by = $this->uri->segment(6);
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_rescheduled_loa_info($loa_id);

		$data['resched_services'] = $this->loa_model->get_rescheduled_services($loa_id, $loa['hcare_provider']);
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['fullname'] = $loa['first_name'].' '.$loa['middle_name'].' '.$loa['last_name'].' '.$loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['hp_name'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['emp_id'] = $loa['emp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['approved_by'] = $approved_by;

		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/create_new_loa.php');
		$this->load->view('templates/footer');
	}

	function get_hp_services(){
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->uri->segment(3);
		$cost_types = $this->loa_model->db_get_cost_types_by_hp_ID($hp_id);
		$response = '';

		if(empty($cost_types)){
			$response .= '<select class="chosen-select" id="med-services" name="med-services[]" multiple="multiple">';
			
			$response .= '<option value="" disabled>No Available Services</option>';

			$response .= '</select>';
		}else{
			$response .= '<select class="chosen-select" id="med-services" name="med-services[]" data-placeholder="Choose services..." multiple="multiple">';
                    
			foreach ($cost_types as $cost_type) {
				$response .= '<option value="'.$cost_type['ctype_id'].'">'.$cost_type['item_description'].'</option>';
			}

			$response .= '</select>';
		}

		echo json_encode($response);
	}

	function submit_rescheduled_loa_services() {
    $token = $this->security->get_csrf_hash();
    $config['upload_path'] = './uploads/referral/';
    $config['allowed_types'] = 'pdf|jpeg|jpg|png|gif|svg';
    $config['encrypt_name'] = TRUE;
    $this->load->library('upload', $config);
    
    $loa_id = $this->input->post('loa-id', TRUE);
    $loa = $this->loa_model->get_loa_request_info($loa_id);
    $med_services = $this->input->post('med-services', TRUE);
    $old_med_services = $this->input->post('old-ctype-id', TRUE);
    
    if($old_med_services > 1){
       $med = implode(';', $old_med_services);
    }else{
      $med = $old_med_services;
    }
    
    $created_on = date('Y-m-d');
    $default = strtotime('+1 week', strtotime($created_on));
    $expired_on = date('Y-m-d', $default);
    
    $result = $this->loa_model->db_get_max_loa_id();
    $max_loa_id = !$result ? 0 : $result['loa_id'];
    $add_loa = $max_loa_id + 1;
    $current_year = date('Y');
    
    // Call function loa_number
    $loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);
    
    if (!$this->upload->do_upload('referralfile')) {
      echo json_encode([
        'token' => $token,
        'status' => 'error',
        'message' => 'File upload failed!'
      ]);
    }else{
      $uploadData = $this->upload->data();
      $post_data = [
        'loa_no' => $loa_no,
        'old_loa_no' => $this->input->post('loa-num', TRUE),
        'emp_id' => $this->input->post('emp-id', TRUE),
        'first_name' => $loa['first_name'],
        'middle_name' => $loa['middle_name'],
        'last_name' => $loa['last_name'],
        'suffix' => $loa['suffix'],
        'hcare_provider' => $this->input->post('healthcare-provider', TRUE),
        'old_hc_provider' => $this->input->post('old-hp-id', TRUE),
        'loa_request_type' => $loa['loa_request_type'],
        'med_services' => implode(';', $med_services),
        'old_med_services' => $med,
        'health_card_no' => $loa['health_card_no'],
        'requesting_company' => $loa['requesting_company'],
        'request_date' => date("Y-m-d"),	
        'chief_complaint' => $loa['chief_complaint'],
        'requesting_physician' => $loa['requesting_physician'],
        'rx_file' => $loa['rx_file'],
        'requested_by' => $this->session->userdata('fullname'),
        'approved_by' => $this->input->post('approved_by'),
        'approved_on' => date("Y-m-d"),
        'work_related' => $loa['work_related'],
        'percentage' => $loa['percentage'],
        'expiration_date' => $expired_on,
        'status' => 'Referred',
        'upload_referral' => $uploadData['file_name'],
        'upload_referral_on' => date("Y-m-d"),
      ];
        
      $inserted = $this->loa_model->db_insert_loa_request($post_data);
      $this->loa_model->set_older_loa_rescheduled($loa_id);
      $this->remove_number_from_field($loa_id, $med_services);
        
      $existing = $this->loa_model->check_if_loa_already_added($loa_id);
      $resched = $this->loa_model->check_if_done_created_new_loa($loa_id);
        
      if ($existing && $resched['referred'] == 1) {
        $this->loa_model->_set_loa_status_completed($loa_id);
      }
        
      if ($inserted) {
        echo json_encode([
          'status' => 'success',
          'message' => 'Successfully Added!',
        ]);
      }else{
        echo json_encode([
          'status' => 'failed',
          'message' => 'Failed to save!',
        ]);
      }
    }
	}

	function loa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function tag_resched_to_complete() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_resched_loa($loa_id);
		$existing = $this->loa_model->check_loa_no($loa_id);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		
		if(!$existing){
			$data['user_role'] = $this->session->userdata('user_role');
			$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hp_ID($loa['hcare_provider']);
			$data['emp_id'] = $loa['emp_id'];
			$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
			$data['loa_no'] = $loa['loa_no'];
			$data['hc_provider'] = $loa['hp_name'];
			$data['hp_id'] = $loa['hp_id'];
			$data['loa_id'] = $loa['loa_id'];
			$data['med_services'] = $loa['med_services'];
			$data['request_type'] = $loa['loa_request_type'];
			$data['approved_on'] = $loa['approved_on'];
			$data['expired_on'] = $loa['expiration_date'];

			
			if($loa['loa_request_type'] == 'Consultation'){

				$view_page ='schedule_consultation.php';

			}else if($loa['loa_request_type'] == 'Diagnostic Test'){

				$view_page ='tag_to_complete_resched.php';
			}
			
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/'.$view_page.'');
			$this->load->view('templates/footer');

		}else{
			$loa_info['user_role'] = $this->session->userdata('user_role');
			$loa_info['loaInfo'] = $this->loa_model->get_performed_loa_data($loa_id);
			$loa_info['hc_provider'] = $loa['hp_name'];
			$loa_info['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
			$loa_info['hp_id'] = $loa['hp_id'];
			$loa_info['loa_id'] = $loa['loa_id'];
			$loa_info['emp_id'] = $loa['emp_id'];
			$loa_info['loa_no'] = $loa['loa_no'];
			$loa_info['request_type'] = $loa['loa_request_type'];
			$loa_info['approved_on'] = $loa['approved_on'];
			$loa_info['expired_on'] = $loa['expiration_date'];

			$loa_info['bar'] = $this->loa_model->bar_pending();
			$loa_info['bar1'] = $this->loa_model->bar_approved();
			$loa_info['bar2'] = $this->loa_model->bar_completed();
			$loa_info['bar3'] = $this->loa_model->bar_referral();
			$loa_info['bar4'] = $this->loa_model->bar_expired();

			if($loa['loa_request_type'] == 'Consultation'){
				$loa_info['loa_data'] = $this->loa_model->get_consultation_data($loa_id);
				$view_page ='edit_tag_complete_consultation.php';

			}else if($loa['loa_request_type'] == 'Diagnostic Test'){

				$view_page ='edit_tagged_resched_loa.php';
			}

			$this->load->view('templates/header', $loa_info);
			$this->load->view('healthcare_coordinator_panel/loa/'.$view_page.'');
			$this->load->view('templates/footer');
		}
	}

	function backdate_expired_loa(){
		$loa_id = $this->myhash->hasher($this->input->post('loa-id', TRUE), 'decrypt');
		$expiry_date = $this->input->post('expiry-date', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('expiry-date', 'Expiry Date', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' 				    => 'error',
				'expiry_date_error' => form_error('expiry-date'),
			];
		} else {
			$post_data = [
				'status'          => 'Approved',
				'expiration_date' => date('Y-m-d', strtotime($expiry_date)),
			];

			$updated = $this->loa_model->db_update_loa_request($loa_id, $post_data);

			if (!$updated) {
				$response = [
					'status'  => 'save-error', 
					'message' => 'LOA Request BackDate Failed'
				];
			}
			$response = [
				'status'  => 'success', 
				'message' => 'LOA Request BackDated Successfully'
			];
		}
		echo json_encode($response);
	}

	// function submit_added_loa_fees() {
	// 	$token = $this->security->get_csrf_hash();
	// 	$data['user_role'] = $this->session->userdata('user_role');
	// 	$loa_id =  $this->input->post('loa-id', TRUE);
	// 	$total_deduct =  $this->input->post('total-deduction', TRUE);
		
	// 	if($total_deduct != ''){
	// 		$total_deductions = $this->input->post('total-deduction', TRUE);
	// 	}else{
	// 		$total_deductions = 0;
	// 	}
	// 	//insert_added_loa_fees
	// 		$post_data = [
	// 			'emp_id' => $this->input->post('emp-id', TRUE),
	// 			'loa_id' => $this->input->post('loa-id', TRUE),
	// 			'hp_id' => $this->input->post('hp-id', TRUE),
	// 			'request_type' => $this->input->post('request-type', TRUE),
	// 			'medicines' => $this->input->post('medicines', TRUE),
	// 			'service_fee' => $this->input->post('service-fee', TRUE),
	// 			'total_services' => $this->input->post('total-bill', TRUE),
	// 			'total_deductions' => $total_deductions,
	// 			'total_net_bill' => $this->input->post('net-bill', TRUE),
	// 			'added_by' => $this->session->userdata('fullname'),
	// 			'added_on' => date('Y-m-d')
	// 		];

	// 	$inserted = $this->loa_model->insert_added_loa_fees($post_data);
	// 	//insert service fees
	// 	$req_type = $this->input->post('request-type', TRUE);
	// 	if($req_type == 'Diagnostic Test'){

	// 		$ctype_id = $this->input->post('ctype-id', TRUE);
	// 		$service_fee = $this->input->post('service-fee', TRUE);
	// 		$quantity = $this->input->post('quantity', TRUE);
	// 		$postData = [];
	// 		for($i = 0; $i < count($ctype_id); $i++){
	
	// 			$postData[] = [
	// 				'loa_id' => $this->input->post('loa-id', TRUE),
	// 				'ctype_id' => $ctype_id[$i],
	// 				'service_fee' => $service_fee[$i],
	// 				'quantity' => $quantity[$i],
	// 				'added_on' => date('Y-m-d')
	// 			];
	
	// 		}
	// 		$this->loa_model->insert_service_fee($postData);
	// 	}
		
	// 	//insert loa deductions
	// 	$deduct_name = $this->input->post('deduction-name', TRUE);
	// 	$deduct_amount = $this->input->post('deduction-amount', TRUE);
	// 	if($deduct_amount > 0){
	// 		$data = [];
	// 		for($x = 0; $x < count($deduct_name); $x++){
	// 			$data[] = [
	// 				'emp_id' => $this->input->post('emp-id', TRUE),
	// 				'loa_id' => $this->input->post('loa-id', TRUE),
	// 				'deduction_name' => $deduct_name[$x],
	// 				'deduction_amount' => $deduct_amount[$x],
	// 				'added_on' => date('Y-m-d'),
	// 				'added_by' => $this->session->userdata('fullname')
	// 			];
	// 		}
	// 		$this->loa_model->insert_deductions($data);
	// 	}
		
	// 	//insert philhealth deduction
	// 	$philhealth_deduct = $this->input->post('philhealth-deduction', TRUE);
	// 	if($philhealth_deduct > 0){
	// 		$add_deduct = [
	// 			'emp_id' => $this->input->post('emp-id', TRUE),
	// 			'loa_id' => $this->input->post('loa-id', TRUE),
	// 			'deduction_name' => 'Philhealth Benefits',
	// 			'deduction_amount' => $this->input->post('philhealth-deduction', TRUE),
	// 			'added_on' => date('Y-m-d'),
	// 			'added_by' => $this->session->userdata('fullname')
	// 		];
	// 		$this->loa_model->insert_philhealth($add_deduct);
	
	// 	}
	// 	$existing = $this->loa_model->check_if_loa_already_added($loa_id);
	// 	$resched = $this->loa_model->check_if_done_created_new_loa($loa_id);
	// 	$rescheduled = $this->loa_model->check_if_status_cancelled($loa_id);
	// 	if($rescheduled){
	// 		if($existing && $resched['reffered'] == 1){
	// 			$this->loa_model->_set_loa_status_completed($loa_id);
	// 		}
	// 	}else{
	// 		if($existing){
	// 			$this->loa_model->_set_loa_status_completed($loa_id);
	// 		}
	// 	}
		
		
	// 	if($inserted){
	// 		echo json_encode([
	// 			'token' => $token,
	// 			'status' => 'success',
	// 			'message' => 'Data Added Successfully!'
	// 		]);
	// 	}else{
	// 		echo json_encode([
	// 			'token' => $token,
	// 			'status' => 'failed',
	// 			'message' => 'Data Insertion Failed!'
	// 		]);
	// 	}
	// }

	function submit_consultation_fees() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$loa_id =  $this->input->post('loa-id', TRUE);
		$total_deduct =  $this->input->post('total-deduction', TRUE);
		
		if($total_deduct != ''){
			$total_deductions = $this->input->post('total-deduction', TRUE);
		}else{
			$total_deductions = 0;
		}
		//insert_added_loa_fees
			$post_data = [
				'emp_id' => $this->input->post('emp-id', TRUE),
				'loa_id' => $this->input->post('loa-id', TRUE),
				'hp_id' => $this->input->post('hp-id', TRUE),
				'request_type' => $this->input->post('request-type', TRUE),
				'medicines' => $this->input->post('medicines', TRUE),
				'service_fee' => $this->input->post('service-fee', TRUE),
				'total_services' => $this->input->post('total-bill', TRUE),
				'total_deductions' => $total_deductions,
				'total_net_bill' => $this->input->post('net-bill', TRUE),
				'added_by' => $this->session->userdata('fullname'),
				'added_on' => date('Y-m-d')
			];
		$inserted = $this->loa_model->insert_added_loa_fees($post_data);

		//insert loa deductions
		$deduct_name = $this->input->post('deduction-name', TRUE);
		$deduct_amount = $this->input->post('deduction-amount', TRUE);
		if($deduct_amount > 0){
			$data = [];
			for($x = 0; $x < count($deduct_name); $x++){
				$data[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'deduction_name' => $deduct_name[$x],
					'deduction_amount' => $deduct_amount[$x],
					'added_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$this->loa_model->insert_deductions($data);
		}
		
		//insert philhealth deduction
		$philhealth_deduct = $this->input->post('philhealth-deduction', TRUE);
		if($philhealth_deduct > 0){
			$add_deduct = [
				'emp_id' => $this->input->post('emp-id', TRUE),
				'loa_id' => $this->input->post('loa-id', TRUE),
				'deduction_name' => 'Philhealth Benefits',
				'deduction_amount' => $this->input->post('philhealth-deduction', TRUE),
				'added_on' => date('Y-m-d'),
				'added_by' => $this->session->userdata('fullname')
			];
			$this->loa_model->insert_philhealth($add_deduct);
	
		}
		$existing = $this->loa_model->check_if_loa_already_added($loa_id);
		$resched = $this->loa_model->check_if_done_created_new_loa($loa_id);
		$rescheduled = $this->loa_model->check_if_status_cancelled($loa_id);
		if($rescheduled){
			if($existing && $resched['reffered'] == 1){
				$this->loa_model->_set_loa_status_completed($loa_id);
			}
		}else{
			if($existing){
				$this->loa_model->_set_loa_status_completed($loa_id);
			}
		}

		$inserted = $this->loa_model->update_performed_fees_processing($loa_id);
		
		
		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Successfully Saved!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Insertion Failed!'
			]);
		}
	}

	function submit_diagnostic_fees() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$loa_id =  $this->input->post('loa-id', TRUE);
		$total_deduct =  $this->input->post('total-deduction', TRUE);
		
		if($total_deduct != ''){
			$total_deductions = $this->input->post('total-deduction', TRUE);
		}else{
			$total_deductions = 0;
		}
		//insert_added_loa_fees
			$post_data = [
				'emp_id' => $this->input->post('emp-id', TRUE),
				'loa_id' => $this->input->post('loa-id', TRUE),
				'hp_id' => $this->input->post('hp-id', TRUE),
				'request_type' => $this->input->post('request-type', TRUE),
				'medicines' => $this->input->post('medicines', TRUE),
				'total_services' => $this->input->post('total-bill', TRUE),
				'total_deductions' => $total_deductions,
				'total_net_bill' => $this->input->post('net-bill', TRUE),
				'added_by' => $this->session->userdata('fullname'),
				'added_on' => date('Y-m-d')
			];

		$inserted = $this->loa_model->insert_added_loa_fees($post_data);
		//insert service fees
		$req_type = $this->input->post('request-type', TRUE);
		if($req_type == 'Diagnostic Test'){

			$ctype_id = $this->input->post('ctype-id', TRUE);
			$service_fee = $this->input->post('service-fee', TRUE);
			$quantity = $this->input->post('quantity', TRUE);
			$postData = [];
			for($i = 0; $i < count($ctype_id); $i++){
	
				$postData[] = [
					'loa_id' => $this->input->post('loa-id', TRUE),
					'ctype_id' => $ctype_id[$i],
					'service_fee' => $service_fee[$i],
					'quantity' => $quantity[$i],
					'added_on' => date('Y-m-d')
				];
	
			}
			$this->loa_model->insert_service_fee($postData);
		}
		
		//insert loa deductions
		$deduct_name = $this->input->post('deduction-name', TRUE);
		$deduct_amount = $this->input->post('deduction-amount', TRUE);
		if($deduct_amount > 0){
			$data = [];
			for($x = 0; $x < count($deduct_name); $x++){
				$data[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'deduction_name' => $deduct_name[$x],
					'deduction_amount' => $deduct_amount[$x],
					'added_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$this->loa_model->insert_deductions($data);
		}
		
		//insert philhealth deduction
		$philhealth_deduct = $this->input->post('philhealth-deduction', TRUE);
		if($philhealth_deduct > 0){
			$add_deduct = [
				'emp_id' => $this->input->post('emp-id', TRUE),
				'loa_id' => $this->input->post('loa-id', TRUE),
				'deduction_name' => 'Philhealth Benefits',
				'deduction_amount' => $this->input->post('philhealth-deduction', TRUE),
				'added_on' => date('Y-m-d'),
				'added_by' => $this->session->userdata('fullname')
			];
			$this->loa_model->insert_philhealth($add_deduct);
	
		}
		// $existing = $this->loa_model->check_if_loa_already_added($loa_id);
		// $resched = $this->loa_model->check_if_done_created_new_loa($loa_id);
		// $rescheduled = $this->loa_model->check_if_status_cancelled($loa_id);
		// if($rescheduled){
		// 	if($existing && $resched['reffered'] == 1){
		// 		$this->loa_model->_set_loa_status_completed($loa_id);
		// 	}
		// }else{
		// 	if($existing){
		// 		$this->loa_model->_set_loa_status_completed($loa_id);
		// 	}
		// }
		$inserted = $this->loa_model->update_performed_fees1($loa_id);
		$inserted = $this->loa_model->_set_loa_status_completed($loa_id);
		
		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Saved Successfully!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Insertion Failed!'
			]);
		}
	}

	function match_loa_with_billing() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$loa = $this->loa_model->get_added_loa_fees($loa_id);
		$data['services'] = $this->loa_model->get_added_services($loa_id);
		$data['deductions'] = $this->loa_model->get_added_deductions($loa_id);
		$billing = $this->loa_model->get_hc_provider_billing($loa_id);

		$data['fullname'] = $loa['first_name'].' '.$loa['middle_name'].' '.$loa['last_name'].' '.$loa['suffix'];
		$data['loa_number'] = $loa['loa_no'];
		$data['loa_id'] = $loa['loa_id'];
		$data['request_type'] = $loa['request_type'];
		$data['total_services'] = $loa['total_services'];
		$data['total_deductions'] = $loa['total_deductions'];
		$data['total_net_bill'] = $loa['total_net_bill'];

		$data['net_bill'] = $billing['net_bill'];
		$data['pdf_bill'] = $billing['pdf_bill'];

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/match_loa_with_billing');
		$this->load->view('templates/footer');
	}

	


	



	function fetch_total_net_bill() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->input->post('hp_id');
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');
		$hospital = $this->loa_model->get_total_hp_net_bill($hp_id, $start_date, $end_date);
		$coordinator = $this->loa_model->get_total_hr_net_bill($hp_id, $start_date, $end_date);
		$variance = $hospital - $coordinator;
		$response = [
			'token' => $token,
			'total_hospital_bill' => number_format($hospital, 2, '.', ','),
			'total_coordinator_bill' => number_format($coordinator, 2, '.', ','),
			'total_variance' => number_format($variance, 2, '.', ','),
		];

		echo json_encode($response);

	}

	function get_loa_charging() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data = $this->loa_model->get_loa_charging($loa_id);
		
		echo json_encode($data);
	}

	function confirm_loa_charging() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->input->post('matched-loa-id', TRUE);
		$remaining_balance = $this->input->post('after-remaining-mbl', TRUE);
		$emp_id = $this->input->post('matched-emp-id', TRUE);

		$data = [
			'company_charge' => $this->input->post('m-company-charge', TRUE),
			'personal_charge' => $this->input->post('m-personal-charge', TRUE),
			'before_remaining_bal' => $this->input->post('before-remaining-mbl', TRUE),
			'after_remaining_bal' => $this->input->post('after-remaining-mbl', TRUE),
			'charging_confirmation' => '1'
		];
		$confirmed = $this->loa_model->confirm_loa_charging($loa_id, $data);
		$set = $this->loa_model->set_remaining_balance($emp_id, $remaining_balance);

		if($confirmed){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Charging Confirmed Successfully!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Failed to Confirmed Charging!'
			]);
		}
	}

	function fetch_coordinator_billing() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$bill = $this->loa_model->get_added_loa_fees($loa_id);
		$service = $this->loa_model->get_added_services($loa_id);
		$deduction = $this->loa_model->get_added_deductions($loa_id);
		$charge = $this->loa_model->get_added_charge($loa_id);

		$data = [
			'bill' => $bill,
			'service' => $service,
			'deduction' => $deduction,
			'charge'=>$charge,
		];

		echo json_encode($data);

	}

	function fetch_monthly_payable() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		// $bill_no =$this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$bill_no = $this->uri->segment(5);
		$data['payable'] = $this->loa_model->fetch_monthly_billed_loa($bill_no);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/view_monthly_billed_loa');
		$this->load->view('templates/footer');

	}

	function get_matched_total_bill() {
		$token = $this->security->get_csrf_hash();
		$bill_no = $this->input->post('bill_no');;
		$hospital = $this->loa_model->get_matched_total_hp_bill($bill_no);
		$coordinator = $this->loa_model->get_matched_total_hr_bill($bill_no);
		
		$response = [
			'token' => $token,
			'total_hospital_bill' => number_format($hospital, 2, '.', ','),
			'total_coordinator_bill' => number_format($coordinator, 2, '.', ','),
		];
		echo json_encode($response);
	}

	function get_bill_for_charging() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		// $bill_no =$this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$bill_no = $this->uri->segment(5);
		$data['payable'] = $this->loa_model->fetch_monthly_billed_loa($bill_no);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/view_monthly_billed_charging');
		$this->load->view('templates/footer');

	}


	function add_performed_consult_fees() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);
		$data['user_role'] = $this->session->userdata('user_role');

		$data['emp_id'] = $loa['emp_id'];
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['health_card_no'] = $loa['health_card_no'];
		$data['work_related'] = $loa['work_related'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['max_benefit_limit'] = number_format($loa['max_benefit_limit'],2);
		$data['remaining_balance'] = number_format($loa['remaining_balance'],2);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
	
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/add_consultation_loa_fees.php');
		$this->load->view('templates/footer');
	}

	//DIAGNOSTIC FORM================================================
	function get_hospital_services(){
    $token = $this->security->get_csrf_hash();
    $hp_id = $this->uri->segment(3);
    $cost_types = $this->loa_model->db_get_cost_types_by_hp($hp_id);
    $response = '';

    if(empty($cost_types)){
      $response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" multiple="multiple">';
      $response .= '<option value="" disabled>No Available Services</option>';
      $response .= '</select>';
    }else{
      $response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" data-placeholder="Choose services..." multiple="multiple">';
      foreach ($cost_types as $cost_type) {
        $response .= '<option value="'.$cost_type['ctype_id'].'" data-price="'.$cost_type['op_price'].'">'.$cost_type['item_description'].''.' ₱'.''.number_format($cost_type['op_price'],2,'.',',').'</option>';
      }
      $response .= '</select>';
    }
    echo json_encode($response);
	}

	function submit_diagnostic_form() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$hp_id = $this->input->post('healthcare-provider');
		$request_type = $this->input->post('loa-request-type');
		// var_dump($request_type);  
		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty');
			break;

			case ($request_type == 'Diagnostic Test'):
				$this->loa_form_validation('Diagnostic Test');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				// var_dump($hp_exist);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Invalid Healthcare Provider'
					];
					echo json_encode($response);
					exit();
				}else{
					// if theres selected file to be uploaded
					$config['upload_path'] = './uploads/loa_attachments/';
					$config['allowed_types'] = 'jpg|jpeg|png';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('rx-file')) {
						$response = [
							'status' => 'save-error',
							'message' => 'File Not Uploaded'
						];
						echo json_encode($response);
						exit();
					}else{
						$upload_data = $this->upload->data();
						$rx_file = $upload_data['file_name'];

						$med_services = $this->input->post('med-services');

						// for physician multi-tags input
						if(empty($physicians_tags)) {
							$attending_physician = '';
						}else{
							foreach ($physicians_tags as $physician_tag) :
								array_push($physician_arr, ucwords($physician_tag['value']));
							endforeach;
							$attending_physician = implode(', ', $physician_arr);
						}

						// Call function insert_loa
						$this->insert_loa1($input_post, $med_services, $attending_physician, $rx_file);
					}
				}
			break;

			default:
				$response = [
					'status' => 'save-error',
					'message' => 'Please Select Valid Request Type'
				];
				echo json_encode($response);
		}
	}

	function insert_loa1($input_post, $med_services, $attending_physician, $rx_file) {
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		$current_year = date('Y');
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);

		$emp_id = $input_post['emp-id'];
		// var_dump($member_id );
		$member = $this->loa_model->db_get_member_details1($emp_id);
		// var_dump($member);

		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $emp_id,
			// 'emp_id' => $member['emp_id'],
			'first_name' =>  $member['first_name'],
			'middle_name' =>  $member['middle_name'],
			'last_name' =>  $member['last_name'],
			'suffix' =>  $member['suffix'],
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => ($med_services!=="")?implode(';', $med_services):"",
			'health_card_no' => $member['health_card_no'],
			'requesting_company' => $member['company'],
			'request_date' => date("Y-m-d"),
			'emerg_date' => (isset( $input_post['admission-date']))? $input_post['admission-date']:null,
			'chief_complaint' => (isset($input_post['chief-complaint']))?strip_tags($input_post['chief-complaint']):"",
			'requesting_physician' =>(isset($input_post['requesting-physician']))? ucwords($input_post['requesting-physician']):"",
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
			'status' => 'Pending',
			'requested_by' =>$this->session->userdata('user_id'),
		];

		$inserted = $this->loa_model->insert_diagnostic_form($post_data);
		// if loa request is not inserted
		if (!$inserted) {
			$response = [
				'status' => 'save-error',
				'message' => 'Failed to Save!'
			];
		}
		$response = [
			'status' => 'success',
			'message' => 'Successfully Save!'
		];
		echo json_encode($response);
		// var_dump($response);
	}

	function loa_form_validation($type) {
		switch ($type) {
			case 'Empty':
			case 'Diagnostic Test':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				$this->form_validation->set_rules('rx-file', '', 'callback_check_rx_file');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief_complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];

					echo json_encode($response);
					// var_dump($response);
					exit();
				}
			break;
			case 'Diagnostic Test Update':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[2000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				$this->form_validation->set_rules('rx-file', '', 'callback_update_check_rx_file');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];
					echo json_encode($response);
					// var_dump($response);
					exit();
				}
				break;
		}
	}



	//HEALTHCARE ADVANCE================================================
	function healthcare_advance_datatable_pending() {
		$token = $this->security->get_csrf_hash();
		$status = 'Pending';
		$list = $this->loa_model->get_result_healthcare_advance_data_pending($status);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];

			$full_name = $pcharge['tbl_3_fname'] . ' ' . $pcharge['tbl_3_mname'] . ' ' . $pcharge['tbl_3_lname'] . ' ' . $pcharge['tbl_3_suffix'];
			$billing_id = $this->myhash->hasher($pcharge['billing_id'],'encrypt');
			$added_on = date("m/d/Y", strtotime($pcharge['requested_on']));
			$custom_status = '<div class="text-left"><span class="badge rounded-pill bg-warning">Billed</span></div>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPChargeModal(\''. $billing_id .'\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$row[] = $pcharge['noa_no'];
			$row[] = $full_name;
			$row[] = $pcharge['billing_no'];
			$row[] = $pcharge['hp_name'];
			$row[] = $added_on; 		
			$row[] = $custom_actions; 	
			$data[] = $row;	
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_healthcare_advance_data_pending($status),
			"recordsFiltered" => $this->loa_model->count_healthcare_advance_data_pending($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function healthcare_advance_modal_pending() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(4),'decrypt');
		$billing = $this->loa_model->get_charge_details($billing_id);

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
				'requested_on' => date('F d, Y', strtotime($bill['requested_on'])),
				'admission_date' => date('F d, Y', strtotime($bill['admission_date'])),
				'attending_doctors' => $bill['attending_doctors'],
				'billing_no' => $bill['billing_no'],
				'healthcard_no' => $bill['health_card_no'],
				'patient_name' => $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'],
				'patient_address' => $bill['home_address'],
				'hospital_name' => $bill['hp_name'],
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

	function healthcare_advance_datatable_approved(){
		$token = $this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->loa_model->get_result_healthcare_advance_data_approved($status);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];

			$full_name = $pcharge['tbl_3_fname'] . ' ' . $pcharge['tbl_3_mname'] . ' ' . $pcharge['tbl_3_lname'] . ' ' . $pcharge['tbl_3_suffix'];
			$billing_id = $this->myhash->hasher($pcharge['billing_id'],'encrypt');
			$added_on = date("m/d/Y", strtotime($pcharge['requested_on']));
			$custom_status = '<div class="text-left"><span class="badge rounded-pill bg-warning">Billed</span></div>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPChargeModal(\''. $billing_id .'\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$row[] = $pcharge['noa_no'];
			$row[] = $full_name;
			$row[] = $pcharge['billing_no'];
			$row[] = $pcharge['hp_name'];
			$row[] = $added_on; 		
			$row[] = $custom_actions; 	
			$data[] = $row;	
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_healthcare_advance_data_approved($status),
			"recordsFiltered" => $this->loa_model->count_healthcare_advance_data_approved($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function healthcare_advance_modal_approved() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(4),'decrypt');
		$billing = $this->loa_model->get_charge_details($billing_id);

		foreach($billing as $bill){
			if(!empty($bill['loa_id'])){
				$loa_noa_no = $bill['loa_no'];
			}else{
				$loa_noa_no = $bill['noa_no'];
			}
			$output = [
				'token' => $token,
				'status' => $bill['status'],
				'date_approved' => date('F d, Y', strtotime($bill['date_approved'])),
				'date_request' => date('F d, Y', strtotime($bill['requested_on'])),
				'billed_on' => date('F d, Y', strtotime($bill['billed_on'])),
				'admission_date' => date('F d, Y', strtotime($bill['admission_date'])),
				'attending_doctors' => $bill['attending_doctors'],
				'billing_no' => $bill['billing_no'],
				'loa_noa_no' => $loa_noa_no,
				'healthcard_no' => $bill['health_card_no'],
				'patient_name' => $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'],
				'patient_address' => $bill['home_address'],
				'hospital_name' => $bill['hp_name'],
				'percentage' => $bill['percentage'],
				'work_related' => $bill['work_related'],
				'remaining_mbl' => number_format($bill['after_remaining_bal'],2,'.',','),
				'hospital_bill' => number_format($bill['net_bill'],2,'.',','),
				'company_charge' => number_format($bill['company_charge'],2,'.',','),
				'personal_charge' => number_format($bill['personal_charge'],2,'.',','),
				'healthcare_advance' => number_format($bill['approved_amount'],2,'.',','),
				// 'before_remaining_bal' => number_format($bill['before_remaining_bal'],2,'.',','),
			];
		}
		echo json_encode($output);
	}

	function healthcare_advance_datatable_disapproved(){
		$token = $this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->loa_model->get_result_healthcare_advance_data_disapproved($status);
		$data = [];
		foreach ($list as $pcharge) {
			$row = [];

			$full_name = $pcharge['tbl_3_fname'] . ' ' . $pcharge['tbl_3_mname'] . ' ' . $pcharge['tbl_3_lname'] . ' ' . $pcharge['tbl_3_suffix'];
			$billing_id = $this->myhash->hasher($pcharge['billing_id'],'encrypt');
			$added_on = date("m/d/Y", strtotime($pcharge['requested_on']));
			$custom_status = '<div class="text-left"><span class="badge rounded-pill bg-warning">Billed</span></div>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPChargeModal(\''. $billing_id .'\')" data-bs-toggle="tooltip" title="View Personal Charge"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$row[] = $pcharge['noa_no'];
			$row[] = $full_name;
			$row[] = $pcharge['billing_no'];
			$row[] = $pcharge['hp_name'];
			$row[] = $added_on; 		
			$row[] = $custom_actions; 	
			$data[] = $row;	
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_healthcare_advance_data_disapproved($status),
			"recordsFiltered" => $this->loa_model->count_healthcare_advance_data_disapproved($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function healthcare_advance_modal_disapproved() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(4),'decrypt');
		$billing = $this->loa_model->get_charge_details($billing_id);

		foreach($billing as $bill){
			if(!empty($bill['loa_id'])){
				$loa_noa_no = $bill['loa_no'];
			}else{
				$loa_noa_no = $bill['noa_no'];
			}
			$output = [
				'token' => $token,
				'status' => $bill['status'],
				'date_disapproved' => date('F d, Y', strtotime($bill['disapproved_on'])),
				'date_request' => date('F d, Y', strtotime($bill['requested_on'])),
				'billed_on' => date('F d, Y', strtotime($bill['billed_on'])),
				'admission_date' => date('F d, Y', strtotime($bill['admission_date'])),
				'attending_doctors' => $bill['attending_doctors'],
				'billing_no' => $bill['billing_no'],
				'loa_noa_no' => $loa_noa_no,
				'healthcard_no' => $bill['health_card_no'],
				'patient_name' => $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'],
				'patient_address' => $bill['home_address'],
				'hospital_name' => $bill['hp_name'],
				'percentage' => $bill['percentage'],
				'work_related' => $bill['work_related'],
				'remaining_mbl' => number_format($bill['after_remaining_bal'],2,'.',','),
				'hospital_bill' => number_format($bill['net_bill'],2,'.',','),
				'company_charge' => number_format($bill['company_charge'],2,'.',','),
				'personal_charge' => number_format($bill['personal_charge'],2,'.',','),
				'healthcare_advance' => number_format($bill['approved_amount'],2,'.',','),
				// 'before_remaining_bal' => number_format($bill['before_remaining_bal'],2,'.',','),
			];
		}
		echo json_encode($output);
	}
	//END===============================================================

//====================================================================================================
//EMERGENCY LOA
//====================================================================================================
	function emergency_loa_datatable_pending() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$hcc_emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables_pending($status);

		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$view_url = base_url() . 'healthcare-coordinator/loa/requested-loa/edit/' . $loa['loa_id'];
			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';
			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.If it is not empty, it will display the text "for Approval". */
			if($loa['work_related'] == ''){
				$custom_status = '<span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span>';
			}else{
				$custom_status = '<span class="badge rounded-pill bg-cyan">for Approval</span>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" class="me-2" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Tag LOA Charge Type"><i class="mdi mdi-tag-plus fs-2 text-primary"></i></a>';

			if($loa['spot_report_file'] && $loa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$custom_actions .= '';
			}
			

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				$view_file = 'None';
				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];
			}else{
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_pending($status),
			"recordsFiltered" => $this->loa_model->count_filtered_pending($status),
			"data" => $data,
		];
		echo json_encode($output);
	}
//====================================================================================================
//END
//====================================================================================================

//====================================================================================================
//FINAL BILLING
//====================================================================================================
	function datatable_final_billing(){
    $token = $this->security->get_csrf_hash();
    $billing = $this->loa_model->get_billed_datatables();
    // var_dump($billing);
    $data = [];

    foreach ($billing as $bill) {
      $row = [];
      if ($bill['done_matching'] != 1) {
        $loa_id = $this->myhash->hasher($bill['tbl1_loa_id'], 'encrypt');
        $fullname = $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
        $request_date=date("F d, Y", strtotime($bill['tbl1_request_date']));
        $workRelated = $bill['tbl1_work_related'] . ' (' . $bill['percentage'] . '%)';


				if($bill['tbl1_status'] == 'Billed'){
					$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $bill['tbl1_status'] . '</span></div>';
				}else if($bill['tbl1_status'] == 'Completed'){
					$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $bill['tbl1_status'] . '</span></div>';
				}else if($bill['tbl1_status'] == 'Approved'){
					$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $bill['tbl1_status'] . '</span></div>';
				}

				if (empty($bill['pdf_bill'])) {
    			$pdf_bill = 'Waiting for SOA';
				}else{
    			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf fs-2 text-info';

    			if ($bill['done_re_upload'] == 'Done') {
        		$pdf_bill .= ' blink';
    			}

    			$pdf_bill .= '"></i></a>';
				}

        $custom_actions = '';
        $billed_date = '';
        $mbl='';
        $letter = $this->loa_model->check_if_guarantee_letter_already_added($bill['loa_id']);
        $performed_fees = $this->loa_model->check_if_performed_fees_is_processing($bill['loa_id']);
        $re_upload = $this->loa_model->check_if_re_upload_is_1($bill['loa_id']);
        // $emergency = $this->loa_model->check_if_emergency_already_added_in_billing($bill['loa_id']);



        if ($bill['loa_request_type'] == 'Consultation'){
        	if ($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Approved'){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/consultation_schedule/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Appointment Schedule"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        		$billed_date=date("F d, Y", strtotime($bill['billed_on']));

        	}else if($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Performed'){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/consultation_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        		$billed_date=date("F d, Y", strtotime($bill['billed_on']));

        		if ($bill['re_upload'] =='0') {
        			$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $bill['loa_no'] . '\')" data-bs-toggle="tooltip" title="Re-Upload File"><i class="mdi mdi-key-plus fs-2 text-primary"></i></a>';
        		}else{
							$custom_actions .= '<i class="mdi mdi-key-plus fs-2 text-secondary" title="Re-Upload another SOA Already Sent"></i>';
						}
    
					}else if($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Processing'){
						$billed_date=date("F d, Y", strtotime($bill['billed_on']));
						if ($bill['performed_fees'] =='Processing') {
        			$custom_actions .= '<i class="mdi mdi-pen fs-2 text-secondary" title="Detailed SOA Already Added"></i>';
        		}else{
							$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/consultation_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
						}

						if ($bill['re_upload'] =='1') {
        			$custom_actions .= '<i class="mdi mdi-key-plus fs-2 text-secondary" title="Re-Upload another SOA Already Sent"></i>';
        		}else{
							$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $bill['loa_no'] . '\')" data-bs-toggle="tooltip" title="Re-Upload File"><i class="mdi mdi-key-plus fs-2 text-primary"></i></a>';
						}

        		if ($bill['guarantee_letter'] =='') {
        			$custom_actions .= '<a href="JavaScript:void(0)" onclick="GuaranteeLetter(\'' . $loa_id . '\',\'' . $bill['billing_id'] . '\')" data-bs-toggle="modal" data-bs-target="#GuaranteeLetter" data-bs-toggle="tooltip" title="Guarantee Letter"><i class="mdi mdi-reply fs-2 text-danger"></i></a>';
        		}else{
							$custom_actions .= '<i class="mdi mdi-reply fs-2 text-secondary" title="Guarantee Letter Already Sent"></i>';
						}

        	}else if($bill['tbl1_status'] == 'Approved' && $bill['performed_fees'] == 'Approved'){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/update-loa/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Appointment Schedule"><i class="mdi mdi-pen fs-2 text-success"></i></a>';
        		$billed_date .='No Billing Date Yet';
        	}else if($bill['tbl1_status'] == 'Completed' && $bill['performed_fees'] == 'Performed'){
        		$custom_actions .='<i class="mdi mdi-cached fs-2 text-info"></i>Processing...';
        		$billed_date .='No Billing Date Yet';
        	}

        }else if ($bill['loa_request_type'] == 'Diagnostic Test') {
        	if ($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Approved') {
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/diagnostic_schedule/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Appointment Schedule"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        		$billed_date=date("F d, Y", strtotime($bill['billed_on']));

        	}else if($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Performed'){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/diagnostic_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Check Detailed SOA"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        		$billed_date=date("F d, Y", strtotime($bill['billed_on']));

        		if ($bill['re_upload'] =='0') {
        			$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $bill['loa_no'] . '\')" data-bs-toggle="tooltip" title="Re-Upload File"><i class="mdi mdi-key-plus fs-2 text-primary"></i></a>';
        		}else{
							$custom_actions .= '<i class="mdi mdi-key-plus fs-2 text-secondary" title="Re-Upload another SOA Already Sent"></i>';
						}

					}else if($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Processing'){
						$billed_date=date("F d, Y", strtotime($bill['billed_on']));
						if ($bill['performed_fees'] =='Processing') {
        			$custom_actions .= '<i class="mdi mdi-pen fs-2 text-secondary" title="Detailed SOA Already Added"></i>';
        		}else{
							$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/diagnostic_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Check Detailed SOA"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
						}

						if ($bill['re_upload'] =='1') {
        			$custom_actions .= '<i class="mdi mdi-key-plus fs-2 text-secondary" title="Re-Upload another SOA Already Sent"></i>';
        		}else{
							$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $bill['loa_no'] . '\')" data-bs-toggle="tooltip" title="Re-Upload File"><i class="mdi mdi-key-plus fs-2 text-primary"></i></a>';
						}

        		if ($bill['guarantee_letter'] =='') {
        			$custom_actions .= '<a href="JavaScript:void(0)" onclick="GuaranteeLetter(\'' . $loa_id . '\',\'' . $bill['billing_id'] . '\')" data-bs-toggle="modal" data-bs-target="#GuaranteeLetter" data-bs-toggle="tooltip" title="Guarantee Letter"><i class="mdi mdi-reply fs-2 text-danger"></i></a>';
        		}else{
							$custom_actions .= '<i class="mdi mdi-reply fs-2 text-secondary" title="Guarantee Letter Already Sent"></i>';
						}

        	}else if($bill['tbl1_status'] == 'Approved' && $bill['performed_fees'] == 'Approved'){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/update-loa/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Appointment Schedule"><i class="mdi mdi-pen fs-2 text-success"></i></a>';
        		$billed_date .='No Billing Date Yet';
        	}else if($bill['tbl1_status'] == 'Completed' && $bill['performed_fees'] == 'Performed'){
        		$custom_actions .='<i class="mdi mdi-cached fs-2 text-info"></i>Processing...';
        		$billed_date .='No Billing Date Yet';
        	}

        }else if ($bill['loa_request_type'] == 'Emergency') {
        	if($bill['tbl1_status'] == 'Approved' && $bill['performed_fees'] == 'Approved'){
        		$custom_actions .='<i class="mdi mdi-cached fs-2 text-success"></i>Processing...';
        		$billed_date .='No Billing Date Yet';
        	}else if($bill['status'] == 'Billed' && $bill['performed_fees'] == 'Approved'){
        		$billed_date=date("F d, Y", strtotime($bill['billed_on']));
        		if ($bill['guarantee_letter'] =='') {
        			$custom_actions .= '<a href="JavaScript:void(0)" onclick="GuaranteeLetter(\'' . $loa_id . '\',\'' . $bill['billing_id'] . '\')" data-bs-toggle="modal" data-bs-target="#GuaranteeLetter" data-bs-toggle="tooltip" title="Guarantee Letter"><i class="mdi mdi-reply fs-2 text-danger"></i></a>';
        		}else{
							$custom_actions .= '<i class="mdi mdi-reply fs-2 text-secondary" title="Guarantee Letter Already Sent"></i>';
						}
        	}
        }

        $row[] = $bill['loa_no'];
        $row[] = $fullname;
        $row[] = '₱' . number_format($bill['remaining_balance'], 2, '.', ',');
        $row[] = $workRelated;
        $row[] = $bill['loa_request_type'];
        $row[] = $request_date;
        $row[] = $billed_date;
        $row[] = number_format($bill['company_charge'], 2, '.', ',');
        $row[] = number_format($bill['personal_charge'], 2, '.', ',');
        $row[] = number_format($bill['cash_advance'], 2, '.', ',');
        $row[] = number_format($bill['net_bill'], 2, '.', ',');
        $row[] = $pdf_bill;
        $row[] = $custom_status;
        $row[] = $custom_actions;
        $data[] = $row;
      }
    }
    $output = [
      "draw" => $_POST['draw'],
      "data" => $data,
    ];
    echo json_encode($output);
    // var_dump($output);
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
    $bill_no = "BILL-" . date('His') . mt_rand(1000, 9999);
    // $total_payable = floatval(str_replace(',', '', $this->input->post('total-hospital-bill', TRUE)));
    // $matched = $this->loa_model->set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no);
    $status = $this->input->post('status', TRUE);

    $data = [
      'bill_no' => $bill_no,
      'type' => 'LOA',
      'hp_id' => $hp_id,
      'month' => $month,
      'year' => $year,
      'status' => 'Payable',
      // 'total_payable' => $total_payable,
      'added_on' => date('Y-m-d'),
      'added_by' => $this->session->userdata('fullname'),
    ];
    $inserted = $this->loa_model->insert_for_payment_consolidated($data);
    if ($inserted) {
      $this->loa_model->update_loa_request_status($hp_id, $start_date, $end_date,$status);
      $this->loa_model->set_bill_for_matched($hp_id, $start_date, $end_date,$bill_no);
      header('Location: ' . base_url() . 'healthcare-coordinator/bill/requests-list/for-charging');
      exit;
    } else {
      echo json_encode([
          'token' => $token,
          'status' => 'failed',
          'message' => 'Failed to Submit!'
      ]);
    }
	}

	function reason_adjustment(){
		$loa_id = $this->myhash->hasher($this->input->post('loa-id', TRUE), 'decrypt');
		$reason = $this->input->post('reason_adjustment', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('reason_adjustment', 'Reason for Adjustment', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' 				    => 'error',
				'reason_adjustment_error' => form_error('reason_adjustment'),
			];

		}else{
			$updated = $this->loa_model->db_update_billing($loa_id, $reason);
			if (!$updated) {
				$response = [
					'status'  => 'save-error', 
					'message' => 'Submit for Re-Uploading Failed'
				];
			}else{
				$response = [
				'status'  => 'success', 
				'message' => 'Successfully Submitted'
				];
			}
		}
		echo json_encode($response);
	}


	function submit_letter() {
    // $token = $this->security->get_csrf_hash();
    $pdf_file = $this->input->post('pdf_file');
    $billing_id = $this->input->post('billing_id');
    $token = $this->input->post('token');
   
    if (!isset($pdf_file) && !isset($billing_id)) {
        echo json_encode([
            'token' => $token,
            'status' => 'error',
            'message' => 'File upload failed!',
            'pdf file' => $pdf_file,
            'billing_id' => $billing_id,
        ]); 
    } else {
        $upload_on = date('Y-m-d');


        // Save the file data into the database
        $updated = $this->loa_model->db_update_letter($billing_id, $pdf_file, $upload_on);
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
        // var_dump($response);
    }
	}


	public function guarantee_pdf($loa_id){
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		// require_once('uploads\phpqrcode-master\qrlib.php');
		$row = $this->loa_model->db_get_data_for_gurantee($loa_id);
		$loa = $this->loa_model->db_get_loa_detail($loa_id);
		$name = $this->session->userdata('fullname');
		$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
		$qr_count = $this->loa_model->count_all_generated_guarantee_letter();
		$total = number_format($row['company_charge']+$row['cash_advance'],2,'.',',');
		$companyChargeWords = $this->convertNumberToWords($total);
		// Generate the qr code
		$data = [
			'Guarantee Payment no:' => $qr_count+1,
			'Member`s Name:' => $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ,
			'LOA no:' =>	$row['loa_no'],
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

		$pdf = new TCPDF();
	
		// Disable the header and footer lines
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		
		$logo = '<img src="'.base_url().'assets/images/letter_logo_final.png" style="width:95px; 	height:70px;">';
		$title = '<div >
		
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Corporate Center, North Wing</p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Island City Mall Dampas Dist </p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Tagbilaran City, Bohol, 6300 </p>
		<p style="line-height: 0; margin-right: 0; font-size: 8px;">Tel. no. 501-3000 local 1319 </p>
	  </div>';

		$pdf->SetMargins(25, 10, 15);
		$pdf->setFont('times', '', 10);
		// Add the letter content
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
						  <p style="font-weight: bold; text-decoration: underline; ">RE: Guarantee Letter for Payment Covered by Alturas Healthcare;</p>
						  <p style="line-height: 2 ;">Dear DR. SEPE;</p>
	
							<p>We are writing to confirm that <span style="font-weight: bold;text-transform: uppercase">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span>, a valued member of the Alturas Healthcare Program, has received medical services and treatments from your esteemed healthcare facility. We would like to assure you that we will cover applicable expenses incurred by our member during their visit, as outlined in our agreement with your organization.</p>
							<p style="line-height: 0;">Patient Details:</p>
							<p></p>
							<p style="line-height: 0;">Patient Name: '.$row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] . ' </p>
							<p style="line-height: 0;">Date of Birth: '.$row['date_of_birth'].'</p>
							<p style="line-height: 0;">Alturas Healthcare Program ID: '.$row['health_card_no'].'</p>
							<p style="line-height: 0;">LOA No: '.$row['loa_no'].'</p>
							<p></p>
							<p></p>
							<p >Therefore, in accordance with the terms and conditions of our agreement, Alturas Healthcare will be using this letter to guarantee payment of the bill amounting</span>'. (($row['cash_advance'] > 0) ? '<span style="font-weight: bold"> (PHP '.number_format($row['company_charge'], 2, '.', ',').')</span> to be charged to the company and <span style="font-weight: bold">(PHP '.number_format($row['cash_advance'], 2, '.', ',').')</span> as a cash advance payment, with the total amount of ' : '').'
							<span id="company_charge_words" style="font-weight: bold;text-transform: uppercase">' . $companyChargeWords . '</span> <span style="font-weight: bold">(PHP ' . $total . ')</span> only. We kindly request that you submit all relevant bills and supporting documentation for the services rendered to <span style="font-weight: bold;">' . rtrim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']) . '</span> directly to our designated billing department.</p>
							<p >We appreciate your collaboration and dedication to providing exceptional healthcare services to our members. Your continued partnership with the Alturas Healthcare Program is instrumental in fulfilling our mission of delivering comprehensive and accessible healthcare to our beneficiaries.</p>
							<p >Thank you for your attention to this matter, and we look forward to a continued successful relationship.</p>
							<p  style="line-height: 3;">Yours sincerely,</p>
						</div>';
		
		$signature = '<div><img src="' . base_url() . 'uploads/doctor_signatures/' . $doc['doctor_signature'] . '" alt="Doctor Signature" style="height:auto;width:170px;vertical-align:baseline;"> </div>';
		// $qr = '<img src="' . base_url() . 'uploads/qrcode/' . $qrfilename . '" alt="Guarantee Letter QR Code" style="height:100px;width:100px;"> ';
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
		$fileName = 'guarantee_letter' . $loa_id . '.pdf';
		$pdf->Output(getcwd() . '/uploads/guarantee_letter/' . $fileName, 'F');
		$response = [
			'status' => 'saved-pdf',
			'filename' => $fileName
		];
		echo json_encode($response);
	}

	function consultation_schedule(){
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_approved_loa($loa_id);
		$existing = $this->loa_model->check_loa_no($loa_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hp_ID($loa['hcare_provider']);
		$data['emp_id'] = $loa['emp_id'];
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['approved_on'] = $loa['approved_on'];
		$data['expired_on'] = $loa['expiration_date'];
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/schedule_consultation1.php');
		$this->load->view('templates/footer');
	}

	function consultation_schedule_submit() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp-id', TRUE);
		$hp_id = $this->input->post('hp-id', TRUE);
		$loa_id = $this->input->post('loa-id', TRUE);
		$loa_no = $this->input->post('loa-num', TRUE);
		$request_type = $this->input->post('request-type', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_performed = $this->input->post('date', TRUE);
		$time_performed = $this->input->post('time', TRUE);
		$physician_fname = ucwords($this->input->post('physician-fname', TRUE));
		$physician_mname = ucwords($this->input->post('physician-mname', TRUE));
		$physician_lname = ucwords($this->input->post('physician-lname', TRUE));
		$added_by = $this->session->userdata('fullname');
		$added_on = date('Y-m-d');	

		$post_data = [
			'emp_id' => $emp_id,
			'hp_id' => $hp_id,
			'loa_id' => $loa_id,
			'loa_no' => $loa_no,
			'request_type' => $request_type,
			'status' => $status,
			'date_performed' => $date_performed,
			'time_performed' => $time_performed,
			'physician_fname' => $physician_fname,
			'physician_mname' => $physician_mname,
			'physician_lname' => $physician_lname,
			'added_by' => $added_by,
			'added_on' => $added_on
		];

		$inserted = $this->loa_model->insert_performed_loa_consult($post_data);
		$inserted = $this->loa_model->update_performed_fees($loa_id);
			
		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Saved Successfully'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Saved Failed'
			]);
		}
	}

	function consultation_fees() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);

		$data['user_role'] = $this->session->userdata('user_role');
		$data['emp_id'] = $loa['emp_id'];
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['health_card_no'] = $loa['health_card_no'];
		$data['work_related'] = $loa['work_related'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['max_benefit_limit'] = number_format($loa['max_benefit_limit'],2);
		$data['remaining_balance'] = number_format($loa['remaining_balance'],2);
		$data['fees'] = $this->loa_model->db_get_hr_added_loa_fees($loa_id);
		$data['charge'] = $this->loa_model->db_get_hr_add_charges_fee($loa_id);
		$data['deduction'] = $this->loa_model->db_get_hr_deduction_fee($loa_id);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
	
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/consultation_fees.php');
		$this->load->view('templates/footer');
	}

	function update_consultation_fees() {
		$loa_id = $this->input->post('loa-id', TRUE);
		$total_deduct = $this->input->post('total-deduction', TRUE);

		if ($total_deduct != '') {
    	$total_deductions = $this->input->post('total-deduction', TRUE);
		}else {
    	$total_deductions = 0;
		}

		$post_data = [
			'service_fee' => $this->input->post('service-fee', TRUE),
	    'total_services' => $this->input->post('total-bill', TRUE),
	    'total_deductions' => $total_deductions,
	    'total_net_bill' => $this->input->post('net-bill', TRUE),
	    'updated_on' => date('Y-m-d')  
		];
		$updated=$this->loa_model->update_added_loa_fees($loa_id, $post_data);

		//Insert Charge Fee
	  $charge_name = $this->input->post('charge-name', TRUE);
		$charge_amount = $this->input->post('charge-amount', TRUE);
		if($charge_amount > 0){
			$data1 = [];
			for($x = 0; $x < count($charge_name); $x++){
				$data1[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'charge_name' => $charge_name[$x],
					'charge_amount' => $charge_amount[$x],
					'Updated_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$updated = $this->loa_model->insert_charge($data1);
		}
		//End

		//Insert Deduction Fee
	  $deduction_name = $this->input->post('deduction-name', TRUE);
		$deduction_amount = $this->input->post('deduction-amount', TRUE);
		if($deduction_amount > 0){
			$data1 = [];
			for($x = 0; $x < count($deduction_name); $x++){
				$data1[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'deduction_name' => $deduction_name[$x],
					'deduction_amount' => $deduction_amount[$x],
					'Updated_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$updated = $this->loa_model->insert_deduction($data1);
		}
		//End

		if (!$updated) {
			$response = [
				'status' => 'save-error', 
				'message' => 'Update Failed'
			];
		}
		$response = [
			'status' => 'success', 
			'message' => 'Updated Successfully'
		];
		echo json_encode($response);
	}

	function consultation_fees1() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);

		$data['user_role'] = $this->session->userdata('user_role');
		$data['cost_types'] = $this->loa_model->get_cost_types_by_hp($loa['hcare_provider'], $loa_id);
		$data['loa'] = $loa;
		$data['emp_id'] = $loa['emp_id'];
		$data['itemized_bill'] = $this->loa_model->get_itemized_bill($loa['emp_id']);
		$data['benefits'] = $this->loa_model->get_benefits_deduction($loa['emp_id']);
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['home_address'] = $loa['home_address'];
		$data['date_of_birth'] = $loa['date_of_birth'];
		$data['philhealth_no'] = $loa['philhealth_no'];
		$data['billed_on'] = $loa['billed_on'];
		$data['request_date'] = $loa['request_date'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['chief_complaint'] = $loa['chief_complaint'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['health_card_no'] = $loa['health_card_no'];
		$data['work_related'] = $loa['work_related'];
		$data['percentage'] = $loa['percentage'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['max_benefit_limit'] = number_format($loa['max_benefit_limit'],2);
		$data['remaining_balance'] = number_format($loa['remaining_balance'],2);

		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
	
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/insert_consultation_fees.php');
		$this->load->view('templates/footer');
	}

	function submit_consultation() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$loa_id =  $this->input->post('loa-id', TRUE);

		$hospital_charge = $this->input->post('hospital_charge', TRUE);
		$less_benefits = $this->input->post('total_deduction', TRUE);
		$total_net_bill = $this->input->post('net_bill', TRUE);

		$hospital_charge = str_replace(['₱', ','], '', $hospital_charge);
		$less_benefits = str_replace(['₱', ','], '', $less_benefits);
		$total_net_bill = str_replace(['₱', ','], '', $total_net_bill);

		//insert_added_loa_fees
		$post_data = [
			'emp_id' => $this->input->post('emp-id', TRUE),
			'loa_id' => $this->input->post('loa-id', TRUE),
			'hp_id' => $this->input->post('hp-id', TRUE),
			'request_type' => $this->input->post('request-type', TRUE),
			'total_services' => $hospital_charge,
			'total_deductions' => $less_benefits,
			'total_net_bill' => $total_net_bill,
			'added_by' => $this->session->userdata('fullname'),
			'added_on' => date('Y-m-d')
		];
		$inserted = $this->loa_model->insert_added_loa_fees1($post_data);
		$inserted = $this->loa_model->update_performed_fees_processing($loa_id);
		//end
	
		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Added Successfully!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Insertion Failed!'
			]);
		}
	}

	function diagnostic_schedule(){
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_approved_loa($loa_id);
		$existing = $this->loa_model->check_loa_no($loa_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hp_ID($loa['hcare_provider']);
		$data['emp_id'] = $loa['emp_id'];
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['approved_on'] = $loa['approved_on'];
		$data['expired_on'] = $loa['expiration_date'];
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/schedule_diagnostic1.php');
		$this->load->view('templates/footer');
	}

	function diagnostic_schedule_submit() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp-id', TRUE);
		$hp_id = $this->input->post('hp-id', TRUE);
		$loa_id = $this->input->post('loa-id', TRUE);
		$loa_no = $this->input->post('loa-num', TRUE);
		$request_type = $this->input->post('request-type', TRUE);
		$ctype_id = $this->input->post('ctype_id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date_performed = $this->input->post('date', TRUE);
		$time_performed = $this->input->post('time', TRUE);
		$reason = $this->input->post('reason', TRUE);
		$physician_fname = $this->input->post('physician-fname', TRUE);
		$physician_mname = $this->input->post('physician-mname', TRUE);
		$physician_lname = $this->input->post('physician-lname', TRUE);
		$added_by = $this->session->userdata('fullname');
		$added_on = date('Y-m-d');
	
		$post_data = [];
		for($x = 0; $x < count($ctype_id); $x++ ){
			$post_data[] = [
				'emp_id' => $emp_id,
				'hp_id' => $hp_id,
				'loa_id' => $loa_id,
				'loa_no' => $loa_no,
				'request_type' => $request_type,
				'ctype_id' =>$ctype_id[$x],
				'status' => $status[$x],
				'reason_cancellation' => ucfirst($reason[$x]),
				'date_performed' => $date_performed[$x],
				'time_performed' => $time_performed[$x],
				'physician_fname' => ucwords($physician_fname[$x]),
				'physician_mname' => ucwords($physician_mname[$x]),
				'physician_lname' => ucwords($physician_lname[$x]),
				'added_by' => $added_by,
				'added_on' => $added_on
			];
		}
			
		$inserted = $this->loa_model->insert_performed_loa_info($post_data);

		$cancelled = $this->loa_model->check_if_service_cancelled($loa_id);
		if($cancelled){
			$service = $this->loa_model->get_cancelled_service($loa_id);
			foreach($service as $number){
				$number_to_remove = $number['ctype_id'];
				$this->remove_service_from_field($loa_id, $number_to_remove);
			}
		}

		$inserted = $this->loa_model->update_performed_fees($loa_id);

		if($inserted){
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Successfully Saved!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Failed to Save!'
			]);
		}
	}

	function diagnostic_fees() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);

		$data['user_role'] = $this->session->userdata('user_role');
		// $data['cost_types'] = $this->loa_model->db_get_cost_types_by_hpID($loa['hcare_provider'], $loa_id);
		$data['cost_types'] = $this->loa_model->get_cost_types_by_hp($loa['hcare_provider'], $loa_id);
		$data['loa'] = $loa;
		$data['deduction'] = $this->loa_model->db_get_hr_added_deductions1($loa_id);
		$data['charge'] = $this->loa_model->db_get_hr_add_charges_fee($loa_id);
		$data['fees'] = $this->loa_model->db_get_hr_added_loa_fees($loa_id);
		$data['emp_id'] = $loa['emp_id'];
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['health_card_no'] = $loa['health_card_no'];
		$data['work_related'] = $loa['work_related'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['max_benefit_limit'] = number_format($loa['max_benefit_limit'],2);
		$data['remaining_balance'] = number_format($loa['remaining_balance'],2);
		$data['loa_id'] = $loa_id;
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
	
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/diagnostic_fees.php');
		$this->load->view('templates/footer');
	}

	function update_diagnostic_fees() {
	  $loa_id = $this->input->post('loa-id', TRUE);

	  //Update hr_added_loa_fees
	  $total_deduct = $this->input->post('total-deduction', TRUE);
	  if ($total_deduct != '') {
	    $total_deductions = $this->input->post('total-deduction', TRUE);
	  } else {
	    $total_deductions = 0;
	  }

	  $post_data = [
	    'medicines' => $this->input->post('medicines', TRUE),
	    'total_services' => $this->input->post('total-bill', TRUE),
	    'total_deductions' => $total_deductions,
	    'total_net_bill' => $this->input->post('net-bill', TRUE),
	    'updated_on' => date('Y-m-d')
	  ];
	  $updated = $this->loa_model->update_added_loa_fees($loa_id, $post_data);
	  //End

	  //Update hr_added_deductions
	  $deduct_id = $this->input->post('deduct_id');
	  $deduct_name = $this->input->post('benefits_name');
	  $deduct_amount = $this->input->post('benefits_amount');
	  if (!empty($deduct_id) && !empty($deduct_name) && !empty($deduct_amount)) {
	    $data = [];
	    foreach ($deduct_id as $index => $id) {
	      $data[] = [
	        'deduct_id' => $id,
	        'deduction_name' => $deduct_name[$index],
	        'deduction_amount' => $deduct_amount[$index],
	        'updated_on' => date('Y-m-d')
	      ];
	    }
	    $updated = $this->loa_model->update_added_deductions($loa_id, $data);
	  }
	  //End

	  //Insert Add Deduction Fee
	  $deduct_name = $this->input->post('deduction-name', TRUE);
		$deduct_amount = $this->input->post('deduction-amount', TRUE);
		if($deduct_amount > 0){
			$data = [];
			for($x = 0; $x < count($deduct_name); $x++){
				$data[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'deduction_name' => $deduct_name[$x],
					'deduction_amount' => $deduct_amount[$x],
					'Updated_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$updated = $this->loa_model->insert_deductions1($data);
		}
		//End

		//Insert Charge Fee
	  $charge_name = $this->input->post('charge-name', TRUE);
		$charge_amount = $this->input->post('charge-amount', TRUE);
		if($charge_amount > 0){
			$data1 = [];
			for($x = 0; $x < count($charge_name); $x++){
				$data1[] = [
					'emp_id' => $this->input->post('emp-id', TRUE),
					'loa_id' => $this->input->post('loa-id', TRUE),
					'charge_name' => $charge_name[$x],
					'charge_amount' => $charge_amount[$x],
					'Updated_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$updated = $this->loa_model->insert_charge($data1);
		}
		//End

		if ($updated) {
	    $response = [
	      'status' => 'success',
	      'message' => 'Updated Successfully'
	    ];
	  } else {
	    $response = [
	      'status' => 'save-error',
	      'message' => 'Update Failed'
	    ];
	  }
	  echo json_encode($response);
	}

	function diagnostic_fees1() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);

		$data['user_role'] = $this->session->userdata('user_role');
		$data['cost_types'] = $this->loa_model->get_cost_types_by_hp($loa['hcare_provider'], $loa_id);
		$data['loa'] = $loa;
		$data['billing_id'] = $loa['billing_id'];
		$data['emp_id'] = $loa['emp_id'];
		// $data['itemized_bill'] = $this->loa_model->get_itemized_bill($loa['emp_id'],$loa['billing_id']);
		$data['itemized_bill'] = $this->loa_model->get_itemized_bill($loa['billing_id']);
		// $data['benefits'] = $this->loa_model->get_benefits_deduction($loa['emp_id'],$loa['billing_id']);
		$data['benefits'] = $this->loa_model->get_benefits_deduction($loa['billing_id']);
		$data['full_name'] = $loa['first_name'] .' '. $loa['middle_name'] .' '. $loa['last_name'] .' '. $loa['suffix'];
		$data['home_address'] = $loa['home_address'];
		$data['date_of_birth'] = $loa['date_of_birth'];
		$data['philhealth_no'] = $loa['philhealth_no'];
		$data['billed_on'] = $loa['billed_on'];
		$data['request_date'] = $loa['request_date'];
		$data['loa_no'] = $loa['loa_no'];
		$data['hc_provider'] = $loa['hp_name'];
		$data['chief_complaint'] = $loa['chief_complaint'];
		$data['hp_id'] = $loa['hp_id'];
		$data['loa_id'] = $loa['loa_id'];
		$data['health_card_no'] = $loa['health_card_no'];
		$data['work_related'] = $loa['work_related'];
		$data['percentage'] = $loa['percentage'];
		$data['med_services'] = $loa['med_services'];
		$data['request_type'] = $loa['loa_request_type'];
		$data['pdf_bill'] = $loa['pdf_bill'];
		$data['max_benefit_limit'] = number_format($loa['max_benefit_limit'],2);
		$data['remaining_balance'] = number_format($loa['remaining_balance'],2);
		// $data['fees'] = $this->loa_model->db_get_hr_added_loa_fees($loa_id);


		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
	
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/insert_diagnostic_fees.php'); 
		$this->load->view('templates/footer');
	}

	function submit_diagnostic() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$loa_id = $this->input->post('loa-id', TRUE);

		$hospital_charge = $this->input->post('hospital_charge', TRUE);
		$less_benefits = $this->input->post('total_deduction', TRUE);
		$total_net_bill = $this->input->post('net_bill', TRUE);

		$hospital_charge = str_replace(['₱', ','], '', $hospital_charge);
		$less_benefits = str_replace(['₱', ','], '', $less_benefits);
		$total_net_bill = str_replace(['₱', ','], '', $total_net_bill);

		//insert_added_loa_fees
		$post_data = [
			'emp_id' => $this->input->post('emp-id', TRUE),
			'loa_id' => $this->input->post('loa-id', TRUE),
			'hp_id' => $this->input->post('hp-id', TRUE),
			'request_type' => $this->input->post('request-type', TRUE),
			'total_services' => $hospital_charge,
			'total_deductions' => $less_benefits,
			'total_net_bill' => $total_net_bill,
			'added_by' => $this->session->userdata('fullname'),
			'added_on' => date('Y-m-d')
		];
		$inserted = $this->loa_model->insert_added_loa_fees($post_data);
		$inserted = $this->loa_model->update_performed_fees_processing($loa_id);
		//end

		if ($inserted) {
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Data Added Successfully!'
			]);
		} else {
			echo json_encode([
				'token' => $token,
				'status' => 'failed',
				'message' => 'Data Insertion Failed!'
			]);
		}

		// var_dump($post_data);
	}
	//====================================================================================================
	//END
	//====================================================================================================

	//====================================================================================================
	//LEDGER
	//====================================================================================================
	function fetch_datatable() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$list = $this->loa_model->get_datatables_ledger($status);
		$data = array();
		foreach ($list as $member){
			$row = array();
			$member_id = $this->myhash->hasher($member['emp_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'healthcare-coordinator/loa_controller/fetch_ledger/'.$member['emp_id'];
			$custom_status='<span class="badge rounded-pill bg-success">'.$member['current_status'].'</span>';
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Ledger"><i class="mdi mdi-eye fs-2 text-info me-2"></i></a>';

			$row[] = $full_name;
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $member['emp_type'];
			$row[] =  '₱' . number_format($member['max_benefit_limit'], 2, '.', ',');
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_ledger($status),
			"recordsFiltered" => $this->loa_model->count_filtered_ledger($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

 	function fetch_ledger_data() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$emp_id = $this->input->post('emp_id');
	
		$list = $this->loa_model->get_datatables_ledger2($status,$emp_id);
		$data = array();
		foreach ($list as $member){
			$row = array();
			$member_id = $this->myhash->hasher($member['emp_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$image_cv = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $member['supporting_file'] . '\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-image fs-2 text-info"></i></a>';
      $custom_status = '<span class="badge rounded-pill bg-success">Paid</span>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewRecords(\''.$member['loa_id'].'\',\''.$member['noa_id'].'\')" data-bs-toggle="tooltip" title="View">View</a>';
	
			// $row[] = $full_name;
			// $row[] = '₱' . number_format($member['max_benefit_limit'], 2, '.', ',');
			$row[] = $member['acc_name'];
			$row[] = $member['payment_no'];
			$row[] = $member['acc_number'];
			$row[] = $member['check_num'];
			$row[] = $member['bank'];
			$row[] = date("F d, Y", strtotime($member['check_date']));
			$row[] = '₱' . number_format($member['total_paid_amount'], 2, '.', ',');
			$row[] = '₱' . number_format($member['after_remaining_bal'], 2, '.', ',');
			// $row[] = $image_cv;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_ledger2($status),
			"recordsFiltered" => $this->loa_model->count_filtered_ledger2($status,$emp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}

	// function fetch_ledger_data() {
	// 	$this->security->get_csrf_hash();
	// 	$status = 'Paid';
	// 	$emp_id = $this->input->post('emp_id');
	// 	$list = $this->loa_model->db_get_all_paid();
	// }

	function view_record() {
    $loa_id = $this->input->get('loa_id');
    $noa_id = $this->input->get('noa_id');
    $row = $this->loa_model->view_history($loa_id, $noa_id);

	  if($loa_id != ''){
	    $response = [
	      'status' => 'success',
	      'token' => $this->security->get_csrf_hash(),
	      'first_name' => $row['first_name'],
	      'middle_name' => $row['middle_name'],
	      'last_name' => $row['last_name'],
	      'suffix' => $row['suffix'],
	      'loa_request_type' => $row['loa_request_type'],
	      'request_date' => date("F d, Y", strtotime($row['request_date'])),
	      'chief_complaint' => $row['chief_complaint'],
	      'work_related' => $row['work_related'],
	      'percentage' => $row['percentage'],
	    ];
	  }else{
		  $response = [
		    'status' => 'success',
		    'token' => $this->security->get_csrf_hash(),
		    'first_name' => $row['first_name'],
		    'middle_name' => $row['middle_name'],
		    'last_name' => $row['last_name'],
		    'suffix' => $row['suffix'],
		    'type_request' => $row['type_request'],
		    'request_date' => date("F d, Y", strtotime($row['request_date'])),
		    'chief_complaint' => $row['chief_complaint'],
		    // 'work_related' => $row['work_related'],
		    // 'percentage' => $row['percentage'],
	    ];
	  }
    echo json_encode($response);
	}
	//====================================================================================================
	//END
	//====================================================================================================
	function convertNumberToWords($number) {
		// $number= number_format($number,2,'.',',');
		$number = str_replace(',', '', $number);
		$decimal = '';
		
		// Check if the number has a decimal part
		if (strpos($number, '.') !== false) {
			$parts = explode('.', $number);
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
			// var_dump('groups',$groups);
			// var_dump('groupCount',$groupCount);
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
		
		if ($decimal !== 0 ) {
			$decimalWords = [];
			
			if ($decimal < 10) {
				$decimalWords[] = $units[$decimal];
				// var_dump('decimal',$decimalWords);
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
		// var_dump('words',$result);
		
		return rtrim($result);
	}

	//====================================================================================================
	//VALIDATION
	//====================================================================================================
	function check_rx_file($str) {
		if (isset($_FILES['rx-file']['name']) && !empty($_FILES['rx-file']['name'])) {
			return true;
		} else {
			$this->form_validation->set_message('check_rx_file', 'Please choose RX/Request Document file to upload.');
			return false;
		}
	}

	function multiple_select() {
		$med_services = $this->input->post('med-services');
		if (count((array)$med_services) < 1) {
			$this->form_validation->set_message('multiple_select', 'Select at least one Service');
			return false;
		} else {
			return true;
		}
	}

	function update_check_rx_file($str) {
		if (isset($_FILES['rx-file']['name'])) {
			return true;
		} else {
			$this->form_validation->set_message('update_check_rx_file', 'Please choose RX/Request Document file to upload.');
			return false;
		}
	}
	//====================================================================================================
	//END
	//====================================================================================================

	
}
