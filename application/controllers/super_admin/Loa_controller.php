<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('super_admin/loa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'super-admin') {
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
		$this->load->model('super_admin/setup_model');
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->loa_model->db_get_loa_info($loa_id);
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->loa_model->db_get_cost_types();
		$data['doctors'] = $this->loa_model->db_get_company_doctors();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/edit_loa_request');
		$this->load->view('templates/footer');
	}

	function update_loa_request() {
		$this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$request_type = $this->input->post('loa-request-type');
		switch (true) {
			case ($request_type == 'Consultation'):
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
			case ($request_type == 'Diagnostic Test'):
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
			'chief_complaint' => strip_tags($input_post['chief-complaint']),
			'requesting_physician' => ucwords($input_post['requesting-physician']),
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
			'status' => 'Pending',
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
		$list = $this->loa_model->get_datatables($status);
		// $cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			// $ct_array = $row = [];
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$view_url = base_url() . 'super-admin/loa/requested-loa/edit/' . $loa_id;

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-2" href="' . $view_url . '" data-bs-toggle="tooltip" title="Edit LOA"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$custom_actions .= '<a href="Javascript:void(0)" onclick="cancelLoaRequest(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Delete LOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			// initialize multiple varibles at once
			// $view_file = $short_med_services = '';
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				// $view_file = $short_med_services = 'None';
				$view_file = 'None';

				// if Healthcare Provider name is too long for displaying to the table, shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {
				// convert into array members selected cost types/med_services using PHP explode
				// $selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				// foreach ($cost_types as $cost_type) :
				// 	if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
				// 		array_push($ct_array, $cost_type['cost_type']);
				// 	endif;
				// endforeach;
				// convert array to string and add comma as a separator using PHP implode
				// $med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end
				// $short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $loa['loa_no'];
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

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'super-admin/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

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
			$row[] = $loa['loa_no'];
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

	function fetch_all_disapproved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';

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
			$row[] = $loa['loa_no'];
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

	function fetch_all_closed_loa() {
		$this->security->get_csrf_hash();
		$status = 'Closed';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewClosedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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
			$row[] = $loa['loa_no'];
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
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('super_admin/loa_model');
		$row = $this->loa_model->db_get_loa_details($loa_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		//get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['cost_type']);
			}
		endforeach;
		$med_serv = implode(', ', $ct_array);

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
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_approved_loa_info() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('super_admin/loa_model');
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
		//get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['cost_type']);
			}
		endforeach;
		$med_serv = implode(', ', $ct_array);

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
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_disapproved_loa_info() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('super_admin/loa_model');
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
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		//get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['cost_type']);
			}
		endforeach;
		$med_serv = implode(', ', $ct_array);

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
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}


	function get_closed_loa_info() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->load->model('super_admin/loa_model');
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
		//get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['cost_type']);
			}
		endforeach;
		$med_serv = implode(', ', $ct_array);

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
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function cancel_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
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
		$data['cost_types'] = $this->loa_model->db_get_cost_types();
		$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
		$data['doc'] = $this->loa_model->db_get_doctor_by_id($exist['approved_by']);
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('super_admin_panel/loa/generate_printable_loa');
			$this->load->view('templates/footer');
		}
	}
}
