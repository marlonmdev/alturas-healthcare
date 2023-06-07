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

	function submit_loa_request() {
		$this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
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
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
					];
					echo json_encode($response);
				}
				$rx_file = '';
				$med_services = [];
				// for physician multi-tags input
				foreach ($physicians_tags as $physician_tag) :
					array_push($physician_arr, ucwords($physician_tag['value']));
				endforeach;
				$attending_physicians = implode(', ', $physician_arr);
				// Call function insert_loa
				$this->insert_loa($input_post, $med_services, $attending_physicians, $rx_file);
				break;
			case ($request_type === 'Diagnostic Test'):
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
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];
					echo json_encode($response);
				} else {
					// if theres selected file to be uploaded
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
					$med_services = $this->input->post('med-services');
					// for physician multi-tags input
					foreach ($physicians_tags as $physician_tag) :
						array_push($physician_arr, ucwords($physician_tag['value']));
					endforeach;
					$attending_physician = implode(', ', $physician_arr);
					// Call function insert_loa
					$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file);
				}
				break;
		}
	}

	function insert_loa($input_post, $med_services, $attending_physician, $rx_file) {
		// select the max loa_id from DB
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		// call function loa_number
		$loa_no = $this->loa_number($add_loa, 8, 'LOA-');

		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $input_post['emp-id'],
			'first_name' =>  ucwords($input_post['first-name']),
			'middle_name' =>  ucwords($input_post['middle-name']),
			'last_name' =>  ucwords($input_post['last-name']),
			'suffix' =>  ucwords($input_post['suffix']),
			'date_of_birth' => date("Y-m-d", strtotime($input_post['date-of-birth'])),
			'gender' => $input_post['gender'],
			'philhealth_no' => $input_post['philhealth-no'],
			'blood_type' => $input_post['blood-type'],
			'contact_no' => $input_post['contact-no'],
			'home_address' => $input_post['home-address'],
			'city_address' => $input_post['city-address'],
			'email' =>  $input_post['email'],
			'contact_person' => $input_post['contact-person'],
			'contact_person_addr' =>  $input_post['contact-person-addr'],
			'contact_person_no' => $input_post['contact-person-no'],
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => implode(';', $med_services),
			'health_card_no' => $input_post['health-card-no'],
			'requesting_company' => $input_post['requesting-company'],
			'request_date' => date("Y-m-d", strtotime($input_post['request-date'])),
			'chief_complaint' => strip_tags($input_post['chief-complaint']),
			'requesting_physician' => ucwords($input_post['requesting-physician']),
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
			'requested_by' => $input_post['requested-by'],
			'status' => 'Pending',
		];
		$inserted = $this->loa_model->db_insert_loa_request($post_data);
		if (!$inserted) {
			$response = [
				'status' => 'save-error', 
				'message' => 'LOA Request Failed'
			];
		}
		$response = [
			'status' => 'success', 
			'message' => 'LOA Request Save Successfully'
		];
		echo json_encode($response);
	}

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

	function update_check_rx_file($str) {
		if (isset($_FILES['rx-file']['name'])) {
			return true;
		} else {
			$this->form_validation->set_message('update_check_rx_file', 'Please choose RX/Request Document file to upload.');
			return false;
		}
	}

	function fetch_all_pending_loa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$hcc_emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables($status);
		// $cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			// $ct_array = $row = [];
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$view_url = base_url() . 'healthcare-coordinator/loa/requested-loa/edit/' . $loa['loa_id'];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.
			If it is not empty, it will display the text "for Approval". */
			if($loa['work_related'] == ''){
				$custom_status = '<span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span>';
			}else{
				$custom_status = '<span class="badge rounded-pill bg-cyan">for Approval</span>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" class="me-2" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Tag LOA Charge Type"><i class="mdi mdi-tag-plus fs-2 text-primary"></i></a>';

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
			$row[] = $view_file;
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

	function fetch_all_approved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$expiry_date = $loa['expiration_date'] ? date('m/d/Y', strtotime($loa['expiration_date'])): 'None';

			$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-1" href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/update-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Performed"><i class="mdi mdi-playlist-check fs-2 text-success"></i></a>';

			$exists = $this->loa_model->check_loa_no($loa['loa_id']);
			if($loa['loa_request_type'] == 'Consultation'){
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '';
				}
			}else{
				if($exists){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '';
				}
			}				

			$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="loaCancellation(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\')" data-bs-toggle="tooltip" title="Cancel LOA Request"><i class="mdi mdi-close-circle fs-2 text-danger"></i></a>';
	
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
			$row[] = $view_file;
			$row[] = $expiry_date;
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
	function fetch_all_disapproved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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
			$row[] = $view_file;
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

	function fetch_all_completed_loa() {
		$this->security->get_csrf_hash();
		$list = $this->loa_model->get_completed_datatables();
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			if($loa['completed'] == 1){
				$custom_status = '<span class="badge rounded-pill bg-info">Completed</span>';
			}

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			
			if($loa['loa_request_type'] == 'Consultation'){

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-danger"></i></a>';

				$existed = $this->loa_model->check_if_loa_already_added($loa['loa_id']);

				if($existed){
					$custom_actions .= '<i class="mdi mdi-playlist-plus fs-2 text-secondary" title="Done Adding LOA fees"></i>';
				}else{
					$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/add-consult-fees/' . $loa_id . '" data-bs-toggle="tooltip" title="Add LOA fees"><i class="mdi mdi-playlist-plus fs-2 text-primary"></i></a>';
				}

			}else{

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-danger"></i></a>';

				$existed = $this->loa_model->check_if_loa_already_added($loa['loa_id']);

				if($existed){
					$custom_actions .= '<i class="mdi mdi-playlist-plus fs-2 text-secondary" title="Done Adding LOA fees"></i>';
				}else{
					$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/add-loa-fees/' . $loa_id . '" data-bs-toggle="tooltip" title="Add LOA fees"><i class="mdi mdi-playlist-plus fs-2 text-primary"></i></a>';
				}
				
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
			$row[] = $view_file;
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

	function fetch_all_rescheduled_loa() {
		$this->security->get_csrf_hash();
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$status = 'Reffered';
		$list = $this->loa_model->get_datatables($status);
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
			$row[] = $view_file;
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

	function fetch_all_expired_loa(){
		$this->security->get_csrf_hash();
		$status = 'Expired';
		$list = $this->loa_model->get_datatables($status);
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
			$row[] = $view_file;
			$row[] = $expiry_date;
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

	function fetch_all_cancelled_loa(){
		$this->security->get_csrf_hash();
		$status = 'Cancelled';
		$list = $this->loa_model->get_datatables($status);
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
			$row[] = $view_file;
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
			$row[] = $view_file;
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
		$status = 'Payable';
		$for_payment = $this->loa_model->fetch_for_payment_bill($status);
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

			$action_customs .= '<a href="'.base_url().'healthcare-coordinator/bill/billed/charging/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Charging"><i class="mdi mdi-file-document-box fs-2 text-danger"></i></a>';

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

			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-eye text-dark"></i>View</a>';

			$row[] = $bill['billing_no'];
			$row[] = $fullname;
			// $row[] = $bill['business_unit'];
			$row[] = $bill['loa_request_type'];
			$row[] = number_format($bill['total_net_bill'], 2, '.', ',');
			$row[] = $coordinator_bill;
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
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
		$row = $this->loa_model->db_get_loa_details($loa_id);
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
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
			'requesting_physician' => $row['doctor_name'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'requested_by' => $row['requested_by'],
			'approved_by' => $row['doctor_name'],
			'approved_on' => $row['approved_on']
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
			$updated = $this->loa_model->db_update_loa_charge_type($loa_id, $charge_type, $percentage);

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
<<<<<<< HEAD

=======
>>>>>>> 290971c67e83b5a21504a97060ea06dd282caa0d
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
<<<<<<< HEAD

=======
			
>>>>>>> 290971c67e83b5a21504a97060ea06dd282caa0d
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

	function submit_performed_loa_info() {
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

			if($inserted){
				echo json_encode([
					'token' => $token,
					'status' => 'success',
					'message' => 'Data Uploaded Successfully!'
				]);
			}else{
				echo json_encode([
					'token' => $token,
					'status' => 'failed',
					'message' => 'Data Failed to Upload!'
				]);
			}
		
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

	function add_performed_loa_fees() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$loa = $this->loa_model->get_all_completed_loa($loa_id);

			$data['user_role'] = $this->session->userdata('user_role');
			$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hpID($loa['hcare_provider'], $loa_id);
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
			$this->load->view('healthcare_coordinator_panel/loa/add_diagnostic_loa_fees.php');
			$this->load->view('templates/footer');
	}

	

	function remove_service_from_field($passed_id, $number_to_remove) {
		$row = $this->loa_model->db_get_loa($passed_id);
		$field_value = $row['med_services'];

		// Split the field value into an array using ";" delimiter
		$values_array = explode(";", $field_value);

		// Check if the number exists in the array
			if (in_array($number_to_remove, $values_array)) {
				// Remove the number from the array
				unset($values_array[array_search($number_to_remove, $values_array)]);
		
				// Join the remaining values in the array back into a string using ";" delimiter
				$new_field_value = implode(";", $values_array);
		
				// Update the database field with the new value
				$result = $this->loa_model->db_update_loa_med_services($passed_id, $new_field_value);
				// $this->db->set('my_field', $new_field_value)->update('my_table');
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
		$this->security->get_csrf_hash();
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
		// call function loa_number
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);

		$post_data = [
			'loa_no' => $loa_no,
			'old_loa_no' => $this->input->post('loa-num', TRUE),
			'emp_id' => $this->input->post('emp-id', TRUE),
			'first_name' =>  $loa['first_name'],
			'middle_name' => $loa['middle_name'],
			'last_name' =>  $loa['last_name'],
			'suffix' =>  $loa['suffix'],
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
			'status' => 'Reffered',
		];

		$inserted = $this->loa_model->db_insert_loa_request($post_data);
		$this->loa_model->set_older_loa_rescheduled($loa_id);
		$this->remove_number_from_field($loa_id, $med_services);

		$existing = $this->loa_model->check_if_loa_already_added($loa_id);
		$resched = $this->loa_model->check_if_done_created_new_loa($loa_id);
		if($existing && $resched['reffered'] == 1){
			$this->loa_model->_set_loa_status_completed($loa_id);
		}

		if($inserted){
			echo json_encode([
				'status' => 'success',
				'message' => 'LOA Added Successfully!',
			]);
		}else{
			echo json_encode([
				'status' => 'failed',
				'message' => 'LOA Not Added!',
			]);
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

	function submit_added_loa_fees() {
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

		$data = [
			'bill' => $bill,
			'service' => $service,
			'deduction' => $deduction,
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


	// FINAL BILLING====================================================
	function datatable_final_billing() {
    $token = $this->security->get_csrf_hash();
    $status = 'Billed';
    $billing = $this->loa_model->get_billed_datatables($status);
   // var_dump("billing",$billing['loa_id']);
    $data = [];

    foreach ($billing as $bill) {
      $row = [];
      if ($bill['done_matching'] != 1) {
      	//var_dump("billing",$bill['tbl1_loa_id']);
        $loa_id = $this->myhash->hasher($bill['tbl1_loa_id'], 'encrypt');
        //$loa_id = $this->myhash->hasher($bill['loa_id'], 'encrypt');

        $fullname = $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
        $coordinator_bill = '<a href="JavaScript:void(0)" onclick="viewCoordinatorBill(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Coordinator Billing"><i class="mdi mdi-eye fs-2 text-info"></i></a>';
        $pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf fs-2 text-info"></i></a>';
        $variance = $bill['net_bill'] - $bill['total_net_bill'];
        if ($variance > 0) {
          $net_variance = '<span class="text-danger">' . number_format($variance, 2, '.', ',') . '</span>';
        }else{
          $net_variance = number_format($variance, 2, '.', ',');
        }

        $custom_actions = '';

        $exists = $this->loa_model->check_if_loa_already_added($bill['loa_id']);


        if ($bill['loa_request_type'] == 'Consultation') {
        	if(!$exists){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/consultation_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        	}else{
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/consultation_fees/'. $loa_id . '" data-bs-toggle="tooltip" title="Edit Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        	}
         
        }else if ($bill['loa_request_type'] == 'Diagnostic Test') {
        	if(!$exists){
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/diagnostic_fees1/'. $loa_id . '" data-bs-toggle="tooltip" title="Add Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        	}else{
        		$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/billed/diagnostic_fees/'. $loa_id . '" data-bs-toggle="tooltip" title="Edit Service Fee"><i class="mdi mdi-pen fs-2 text-warning"></i></a>';
        	} 
        }

        $custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $loa_id . '\', \'' . $bill['loa_no'] . '\')" data-bs-toggle="tooltip" title="Re-Upload File"><i class="mdi mdi-key-plus fs-2 text-danger"></i></a>';

        $row[] = $bill['loa_no'];
        $row[] = $fullname;
        $row[] = $bill['loa_request_type'];
        $row[] = number_format($bill['total_net_bill'], 2, '.', ',');
        $row[] = $coordinator_bill;
        $row[] = number_format($bill['net_bill'], 2, '.', ',');
        $row[] = $pdf_bill;
        $row[] = $net_variance;
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
		$total_payable = floatval(str_replace(',', '', $this->input->post('total-hospital-bill', TRUE)));
		$matched = $this->loa_model->set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no);
		$status = $this->input->post('status', TRUE);

		$data = [
			'bill_no' => $bill_no,
			'type' => 'LOA',
			'hp_id' => $hp_id,
			'month' => $month,
			'year' => $year,
			'status' => 'Payable',
			'total_payable' => $total_payable,
			'added_on' => date('Y-m-d'),
			'added_by' => $this->session->userdata('fullname'),
		];
		$inserted = $this->loa_model->insert_for_payment_consolidated($data);
		if($inserted){
			$this->loa_model->update_loa_request_status($status);
			header('Location: ' . base_url() . 'healthcare-coordinator/bill/requests-list/for-charging');
    	exit;
		}else{
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

		$inserted = $this->loa_model->insert_added_loa_fees1($post_data);
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
			$this->loa_model->insert_service_fee1($postData);
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
			$this->loa_model->insert_deductions2($data);
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
			$this->loa_model->insert_philhealth1($add_deduct);
		}
		$existing = $this->loa_model->check_if_loa_already_added1($loa_id);
		$resched = $this->loa_model->check_if_done_created_new_loa1($loa_id);
		$rescheduled = $this->loa_model->check_if_status_cancelled1($loa_id);
		if($rescheduled){
			if($existing && $resched['reffered'] == 1){
				$this->loa_model->_set_loa_status_completed2($loa_id);
			}
		}else{
			if($existing){
				$this->loa_model->_set_loa_status_completed1($loa_id);
			}
		}

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
					'added_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$this->loa_model->insert_charge($data1);
		}
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


	  // if ($updated || $updated_deductions || $insert_deductions || $insert_charge) {
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
		//end

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
		//end
		
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
		//end
		
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
		//end

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
					'added_on' => date('Y-m-d'),
					'added_by' => $this->session->userdata('fullname')
				];
			}
			$updated = $this->loa_model->insert_charge($data1);
		}
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

	//END===============================================================

	//LEDGER============================================================
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
			$custom_status='<span class="badge rounded-pill bg-success">'.$member['status'].'</span>';
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Ledger"><i class="mdi mdi-eye fs-2 text-info me-2"></i></a>';

			$row[] = $full_name;
			$row[] = $member['emp_type'];
			$row[] = $member['current_status'];
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
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
	
			$row[] = $full_name;
			$row[] = '₱' . number_format($member['max_benefit_limit'], 2, '.', ',');
			$row[] = $member['acc_name'];
			$row[] = $member['acc_number'];
			$row[] = $member['check_num'];
			$row[] = $member['bank'];
			$row[] = date("F d, Y", strtotime($member['check_date']));
			$row[] = '₱' . number_format($member['amount_paid'], 2, '.', ',');
			$row[] = $image_cv;
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
		    'request_date' => date("F d, Y", strtotime($row['request_date'])),
		    'chief_complaint' => $row['chief_complaint'],
		    'work_related' => $row['work_related'],
		    'percentage' => $row['percentage'],
	    ];
	  }
    echo json_encode($response);
	}
	//END============================================================
	
}
