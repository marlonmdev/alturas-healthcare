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

	function loa_number($input, $pad_len = 8, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
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
				} else {
					$rx_file = '';
					$med_services = [];
					// for physician multi-tags input
					foreach ($physicians_tags as $physician_tag) :
						array_push($physician_arr, ucwords($physician_tag['value']));
					endforeach;
					$attending_physician = implode(', ', $physician_arr);
					// Call function update_loa
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
				} else {
					$row = $this->loa_model->db_get_loa_attach_filename($loa_id);
					$db_filename = $row['rx_file'];
					$med_services = $this->input->post('med-services');

					// for physician multi-tags input
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

			$expires = strtotime('+1 week', strtotime($loa['approved_on']));
      $expiration_date = date('m/d/Y', $expires);
			// call another function to determined if expired or not
			$date_result = $this->checkExpiration($loa['approved_on']);

      if($date_result == 'Expired'){
				$custom_date = '<span class="text-danger">'.$expiration_date.'</span><span class="text-danger fw-bold ls-1"> [Expired]</span>';

				$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				$custom_actions .= '<a class="me-1" data-bs-toggle="tooltip" title="Cannot Print Expired LOA"><i class="mdi mdi-printer fs-2 icon-disabled"></i></a>';

			}else{
				$custom_date = $expiration_date;

				$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				$custom_actions .= '<a class="me-1" href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

				$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/update-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Update LOA"><i class="mdi mdi-playlist-check fs-2 text-success"></i></a>';

				if($loa['loa_request_type'] == 'Consultation'){
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaConsult(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}else{
					$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-cyan"></i></a>';
				}				

				$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="loaCancellation(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\')" data-bs-toggle="tooltip" title="Cancel LOA Request"><i class="mdi mdi-close-circle fs-2 text-danger"></i></a>';

			}
	
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

	function checkExpiration($passed_date){
		$approved_date = DateTime::createFromFormat("Y-m-d", $passed_date);

		$expiration_date = $approved_date->modify("+7 days");

		$current_date = new DateTime();

		$date_diff = $current_date->diff($expiration_date);

		$result = $date_diff->invert ? "Expired" : "Not Expired";

		return $result;
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
		$status = 'Completed';
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
			}else{

				$custom_actions .= '<a class="me-1" href="JavaScript:void(0)" onclick="viewPerformedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View Performed LOA Info"><i class="mdi mdi-clipboard-text fs-2 ps-1 text-danger"></i></a>';
			}

			// $custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/loa/requested-loa/add-loa-fees/' . $loa_id . '" data-bs-toggle="tooltip" title="Add LOA fees"><i class="mdi mdi-playlist-check fs-2 text-success"></i></a>';


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

	function get_pending_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_details($loa_id);
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
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_approved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('ho_accounting/Loa_model');
		$row = $this->Loa_model->db_get_loa_details($loa_id);
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
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_disapproved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_details($loa_id);
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
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_cancelled_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('healthcare_coordinator/loa_model');
		$row = $this->loa_model->db_get_loa_details($loa_id);

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
			'cancelled_on' => $row['cancelled_on'] ? date("F d, Y", strtotime($row['cancelled_on'])) : '',
			'cancelled_by' => $row['cancelled_by'],
			'cancellation_reason' => $row['cancellation_reason'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
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
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
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
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/generate_printable_loa');
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
				'token' => $token,
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
		
		if(!$existing){
			$data['user_role'] = $this->session->userdata('user_role');
			$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hpID($loa['hcare_provider']);
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

				$view_page ='tag_to_complete_consultation.php';

			}else if($loa['loa_request_type'] == 'Diagnostic Test'){

				$view_page ='tag_loa_to_complete.php';
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
			$loa_info['expiration_date'] = $loa['expiration_date'];


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
		$resched_date = $this->input->post('resched-date', TRUE);
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
					'reschedule_on' => $resched_date[$x],
					'physician_fname' => ucwords($physician_fname[$x]),
					'physician_mname' => ucwords($physician_mname[$x]),
					'physician_lname' => ucwords($physician_lname[$x]),
					'added_by' => $added_by,
					'added_on' => $added_on
				];
			}
			
			$inserted = $this->loa_model->insert_performed_loa_info($post_data);
			
			// $performed = $this->loa_model->check_if_all_status_performed($loa_id);
			// if($performed){
			// 	$status = 'Completed';
			// 	$this->loa_model->set_loa_status_completed($loa_id, $status);
			// }

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
		$date_time_performed = $this->input->post('date', TRUE);
		$physician = $this->input->post('physician', TRUE);
		$added_by = $this->session->userdata('fullname');
		$added_on = date('Y-m-d');	

		$post_data = [
			'emp_id' => $emp_id,
			'hp_id' => $hp_id,
			'loa_id' => $loa_id,
			'loa_no' => $loa_no,
			'request_type' => $request_type,
			'status' => $status,
			'date_time_performed' => $date_time_performed,
			'physician' => $physician,
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
		$date_time_performed = $this->input->post('date', TRUE);
		$physician = $this->input->post('physician', TRUE);
		$edited_by = $this->session->userdata('fullname');
		$edited_on = date('Y-m-d');	

		$post_data = [];
		
		for($x = 0; $x < count($ctype_id); $x++ ){
			$post_data[] = [
				'ctype_id' =>$ctype_id[$x],
				'status' => $status[$x],
				'date_time_performed' => $date_time_performed[$x],
				'physician' => $physician[$x],
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

		$performed = $this->loa_model->check_if_all_status_performed($loa_id);
		
		if($performed){
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
			$data['cost_types'] = $this->loa_model->db_get_cost_types_by_hpID($loa['hcare_provider']);
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
		
			$this->load->view('templates/header', $data);
			$this->load->view('healthcare_coordinator_panel/loa/add_diagnostic_loa_fees.php');
			$this->load->view('templates/footer');
	}

	function get_autocomplete() {
		$term = $this->input->get('term');
		$products = $this->db->like('physician', $term)->get('performed_loa_info')->result_array();
		$suggestions = array_column($products, 'physician');

		echo json_encode($suggestions);
	}

	function remove_number_from_field($passed_id, $number_to_remove) {
    $loa_id = $this->myhash->hasher($passed_id, 'decrypt');
		$row = $this->loa_model->db_get_loa($loa_id);
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
				$result = $this->loa_model->db_update_loa_med_services($loa_id, $new_field_value);
        $this->db->set('my_field', $new_field_value)->update('my_table');
    }

	}

	
}
