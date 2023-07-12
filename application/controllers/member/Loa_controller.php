<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/loa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'member') {
			redirect(base_url());
		}
	}

	function check_rx_file($str) {
		if (isset($_FILES['rx-file']['name']) && !empty($_FILES['rx-file']['name'])) {
			return true;
		} else {
			$this->form_validation->set_message('check_rx_file', 'Please choose RX/Request Document file to upload.');
			return false;
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

	function multiple_select() {
		$med_services = $this->input->post('med-services');
		if (count((array)$med_services) < 1) {
			$this->form_validation->set_message('multiple_select', 'Select at least one Service');
			return false;
		} else {
			return true;
		}
	}

	function loa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function loa_form_validation($type) {
		switch ($type) {
			case 'Empty':
			case 'Consultation':
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
					exit();
				}
				break;
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
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];

					echo json_encode($response);
					exit();
				}
				break;
			case 'Emergency':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('admission-date', 'Date of Visit', 'required');
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
					exit();
				}
				break;
		}
	}

	function get_hp_services(){
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

	function get_hp_services_on_edit(){
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->uri->segment(4);
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_services($loa_id);
		$cost_types = $this->loa_model->db_get_cost_types_by_hp($hp_id);
		$selectedOptions = explode(';', $row['med_services']);
		$response = '';

		if(empty($cost_types)){
			$response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" multiple="multiple">';
			
			$response .= '<option value="" disabled>No Available Services</option>';

			$response .= '</select>';
		}else{
			$response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" data-placeholder="Choose services..." multiple="multiple">';
                    
			foreach ($cost_types as $cost_type) {
				$select = in_array($cost_type['ctype_id'], $selectedOptions) ? 'selected' : '';

				$response .= '<option value="'.$cost_type['ctype_id'].'" '.$select.'>'.$cost_type['item_description'].'</option>';
			}

			$response .= '</select>';
		}
		

		echo json_encode($response);
	}

	function submit_loa_request() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$hp_id = $this->input->post('healthcare-provider');
		$request_type = $this->input->post('loa-request-type'); 
		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty');
				break;
			case ($request_type == 'Consultation'):
				$this->loa_form_validation('Consultation');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Invalid Healthcare Provider'
					];
					echo json_encode($response);
					exit();
				}
				// if request type is Consultation set rx_file and med_services to be empty
				$rx_file = '';
				$med_services = [];

				// for physician multi-tags input
				if(empty($physicians_tags)) {
					$attending_physician = '';
				} else {
					foreach ($physicians_tags as $physician_tag) :
						array_push($physician_arr, ucwords($physician_tag['value']));
					endforeach;
					$attending_physician = implode(', ', $physician_arr);
				}

				//  Call function insert_loa
				$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file);
				break;
			case ($request_type == 'Diagnostic Test'):
				$this->loa_form_validation('Diagnostic Test');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Invalid Healthcare Provider'
					];
					echo json_encode($response);
					exit();
				} else {
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
					} else {
						$upload_data = $this->upload->data();
						$rx_file = $upload_data['file_name'];

						$med_services = $this->input->post('med-services');

						// for physician multi-tags input
						if(empty($physicians_tags)) {
							$attending_physician = '';
						} else {
							foreach ($physicians_tags as $physician_tag) :
								array_push($physician_arr, ucwords($physician_tag['value']));
							endforeach;
							$attending_physician = implode(', ', $physician_arr);
						}

						// Call function insert_loa
						$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file);
					}
				}
				break;
				case ($request_type == 'Emergency'):
					$this->loa_form_validation('Emergency');
					// check if the selected healthcare provider exist from database
					$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
					if (!$hp_exist) {
						$response = [
							'status' => 'save-error',
							'message' => 'Invalid Healthcare Provider'
						];
						echo json_encode($response);
						exit();
					} else {
							// Call function insert_loa
							$this->insert_loa($input_post, "", "", "");
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

	function insert_loa($input_post, $med_services, $attending_physician, $rx_file) {
		// select the max loa_id from DB
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		$current_year = date('Y');
		// call function loa_number
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);

		$emp_id = $this->session->userdata('emp_id');
		$member = $this->loa_model->db_get_member_infos($emp_id);

		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $emp_id,
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
			'requested_by' => $emp_id,
		];

		$inserted = $this->loa_model->db_insert_loa_request($post_data);
		// if loa request is not inserted
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

	function edit_loa_request() {
		$this->load->model('super_admin/setup_model');
		$loa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->loa_model->db_get_cost_types();
		$data['doctors'] = $this->loa_model->db_get_company_doctors();
		// if loa request does not exist
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('member_panel/loa/edit_loa_request');
			$this->load->view('templates/footer');
		}
	}

	function update_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$hp_id = $this->input->post('healthcare-provider');
		$request_type = $this->input->post('loa-request-type');
		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty');
				break;
			case ($request_type == 'Consultation'):
				$this->loa_form_validation('Consultation');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				// if healthcare provider does not exist
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Healthcare Provider Does Not Exist'
					];
					echo json_encode($response);
					exit();
				} else {
					// if request type is Consultation set rx_file and med_services to be empty
					$rx_file = '';
					$med_services = [];
					
					// for physician multi-tags input
					if(empty($physicians_tags)) {
						$attending_physician = '';
					} else {
						foreach ($physicians_tags as $physician_tag) :
							array_push($physician_arr, ucwords($physician_tag['value']));
						endforeach;
						$attending_physician = implode(', ', $physician_arr);
					}

					// Call function update_loa
					$this->update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file);
				}
				break;
			case ($request_type == 'Diagnostic Test'):
				$this->loa_form_validation('Diagnostic Test Update');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				if (!$hp_exist) {
					$response = array('status' => 'save-error', 'message' => 'Healthcare Provider Does Not Exist');
					echo json_encode($response);
					exit();
				} else {
					$db_filename = $input_post['file-attachment'];
					$med_services = $this->input->post('med-services');

					// for physician multi-tags input
					if(empty($physicians_tags)) {
						$attending_physician = '';
					} else {
						foreach ($physicians_tags as $physician_tag) :
							array_push($physician_arr, ucwords($physician_tag['value']));
						endforeach;
						$attending_physician = implode(', ', $physician_arr);
					}

					// if there is no filename selected set the one from database
					if (empty($_FILES['rx-file']['name'])) {
						$rx_file = $db_filename;
					} else {
						// if there is a new file to be uploaded
						$config['upload_path'] = './uploads/loa_attachments/';
						$config['allowed_types'] = 'jpg|jpeg|png';
						$config['encrypt_name'] = TRUE;
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('rx-file')) {
							$response = [
								'status' => 'save-error', 
								'message' => 'File Not Uploaded!'
							];
							echo json_encode($response);
							exit();
						}
						$upload_data = $this->upload->data();
						$rx_file = $upload_data['file_name'];
						// remove the old file when the new file was uploaded
						if ($db_filename !== '') {
							$file_path = './uploads/loa_attachments/' . $db_filename;
							file_exists($file_path) ? unlink($file_path) : '';
						}
					}
					// Call function update_loa			
					$this->update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file);
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

	function update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file) {
		$post_data = [
			'emp_id' => $this->session->userdata('emp_id'),
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => implode(';', $med_services),
			'chief_complaint' => strip_tags($input_post['chief-complaint']),
			'requesting_physician' => ucwords($input_post['requesting-physician']),
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
		];
		$updated = $this->loa_model->db_update_loa_request($loa_id, $post_data);
		// If loa is not updated
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

	function fetch_pending_loa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->loa_model->db_get_pending_loa($emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$result = [];
		foreach ($resultList as $key => $value) {
			// decrypt the id passed from the view which is encrypted
			$loa_id = $this->myhash->hasher($value['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$value['loa_no'].'</mark>';

			$request_date = $value['request_date'] ? date("m/d/Y", strtotime($value['request_date'])) : 'None';

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.
			If it is not empty, it will display the text "for Approval". */
			if($value['work_related'] == ''){
				$custom_status = '<span class="badge rounded-pill bg-warning">' . $value['status'] . '</span>';
			}else{
				$custom_status = '<span class="badge rounded-pill bg-cyan">for Approval</span>';
			}

			$button = '<a class="me-2 align-top" style="top:-20px!important;" href="JavaScript:void(0)" onclick="viewLoaInfoModal(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$button .= '<a class="me-2 align-top" style="top:-20px!important;" href="' . base_url() . 'member/requested-loa/edit/' . $loa_id . '" data-bs-toggle="tooltip" title="Edit LOA"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$button .= '<a class="align-top" style="top:-20px!important;" href="JavaScript:void(0)" onclick="cancelPendingLoa(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Delete LOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_serv = '';
			$ct_array = [];
			$short_hp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			if ($value['loa_request_type'] === 'Consultation') {
				$view_file = $short_med_serv = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $value['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
						array_push($ct_array, $cost_type['item_description']);
					}
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_serv = implode(', ', $ct_array);
				$short_med_serv = strlen($med_serv) > 30 ? substr($med_serv, 0, 30) . "..." : $med_serv;
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
			}

			$result['data'][] = array(
				$custom_loa_no,
				$short_hp_name,
				$value['loa_request_type'],
				$request_date,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'NONE',
				$custom_status,
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_approved_loa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->loa_model->db_get_approved_loa($emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$result = [];
		foreach ($resultList as $key => $value) {
			// decrypt the id passed from the view which is encrypted
			$loa_id = $this->myhash->hasher($value['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$value['loa_no'].'</mark>';

			$expiry_date = $value['expiration_date'] ? date('m/d/Y', strtotime($value['expiration_date'])) : 'None';

			// $expires = strtotime('+1 week', strtotime($value['approved_on']));
      // $expiration_date = date('m/d/Y', $expires);
			// // call another function to determined if expired or not
			// $date_result = $this->checkExpiration($value['approved_on']);
			
			// if($date_result == 'Expired'){
			// 	$custom_date = '<span class="text-danger">'.$expiration_date.'</span><span class="text-danger fw-bold ls-1"> [Expired]</span>';
			// }else{
			// 	$custom_date = $expiration_date;
			// }
			
			$buttons = '<a class="me-2" href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// $for_cancellation = $this->loa_model->db_get_loa_cancellation_request($value['loa_id']);

			// if(empty($for_cancellation['status']) || $for_cancellation['status'] == 'Disapproved'){
			// 	$buttons .= '<a class="me-2" href="JavaScript:void(0)" onclick="requestLoaCancellation(\'' . $loa_id . '\', \'' . $value['loa_no'] . '\', \'' . $value['hcare_provider'] . '\')" data-bs-toggle="tooltip" title="Request LOA Cancellation"><i class="mdi mdi-close-circle fs-2 text-danger"></i></a>';
			// }else{
			// 	$buttons .= '<a class="me-2" data-bs-toggle="tooltip" title="Requested for Cancellation" disabled><i class="mdi mdi-close-circle fs-2 icon-disabled"></i></a>';
			// }

			// $button .= '<a href="' . base_url() . 'member/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Generate Printable LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_serv = '';
			$ct_array = [];
			$short_hp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			if ($value['loa_request_type'] === 'Consultation') {
				$view_file = $short_med_serv = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $value['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
						array_push($ct_array, $cost_type['item_description']);
					}
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_serv = implode(', ', $ct_array);
				$short_med_serv = strlen($med_serv) > 30 ? substr($med_serv, 0, 30) . "..." : $med_serv;
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
			}

			$result['data'][] = array(
				$custom_loa_no,
				$short_hp_name,
				$value['loa_request_type'],
				$expiry_date,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'NONE',
				'<span class="badge rounded-pill bg-success">' . $value['status'] . '</span>',
				$buttons
			);
		}
		echo json_encode($result);
	}

	function checkExpiration($passed_date){
		$approved_date = DateTime::createFromFormat("Y-m-d", $passed_date);

		$expiration_date = $approved_date->modify("+7 days");

		$current_date = new DateTime();

		$date_diff = $current_date->diff($expiration_date);

		$result = $date_diff->invert ? "Expired" : "Not Expired";

		return $result;
	}

	function fetch_disapproved_loa() {
		$emp_id = $this->session->userdata('emp_id');
		$resultList = $this->loa_model->db_get_disapproved_loa($emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$result = [];
		foreach ($resultList as $key => $value) {
			$loa_id = $this->myhash->hasher($value['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$value['loa_no'].'</mark>';

			$button = '<a href="JavaScript:void(0)" onclick="viewDisapprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_serv = '';
			$ct_array = [];
			$short_hp_name = strlen($value['hp_name']) > 24 ? substr($value['hp_name'], 0, 24) . "..." : $value['hp_name'];

			if ($value['loa_request_type'] === 'Consultation') {
				$view_file = $short_med_serv = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $value['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
						array_push($ct_array, $cost_type['item_description']);
					}
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_serv = implode(', ', $ct_array);
				$short_med_serv = strlen($med_serv) > 30 ? substr($med_serv, 0, 30) . "..." : $med_serv;
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
			}

			$result['data'][] = array(
				$custom_loa_no,
				date("m/d/Y", strtotime($value['request_date'])),
				$short_hp_name,
				$value['loa_request_type'],
				// $short_med_serv,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'NONE',
				'<span class="badge rounded-pill bg-danger">' . $value['status'] . '</span>',
				$button
			);
		}
		echo json_encode($result);
	}

	function fetch_completed_loa() {
		$token = $this->security->get_csrf_hash();
		$status = 'Completed';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables($status, $emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-info">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_services = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = $short_med_services = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
						array_push($ct_array, $cost_type['item_description']);
					endif;
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
				$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}


	function fetch_expired_loa() {
		$token = $this->security->get_csrf_hash();
		$status = 'Expired';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables($status, $emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewExpiredLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_services = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = $short_med_services = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
						array_push($ct_array, $cost_type['item_description']);
					endif;
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
				$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	function fetch_billed_loa() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_billed_datatables($emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-info">Billed</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewBilledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_services = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = $short_med_services = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
						array_push($ct_array, $cost_type['item_description']);
					endif;
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
				$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function fetch_paid_loa() {
		$token = $this->security->get_csrf_hash();
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_paid_datatables($emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-success">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewBilledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_services = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = $short_med_services = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
						array_push($ct_array, $cost_type['item_description']);
					endif;
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
				$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'NONE';
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function get_loa_info() {
		$doctor_name = $requesting_physician = "";
		$loa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->loa_model->db_get_loa_detail($loa_id);

		//check if requesting physician exist from DB
		$exist = $this->loa_model->db_get_requesting_physician($row['requesting_physician']);
		if (!$exist) {
			$requesting_physician = "Does not exist from Database";
		} else {
			$requesting_physician = $exist['doctor_name'];
		}


		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age Based on Date of Birth
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y") . ' years old';

		/* Taking the med_services column from the database and exploding it into an array.
		Then it is looping through the cost_types array and checking if the ctype_id is in the
		selected_cost_types array.
		If it is, it pushes the cost_type into the ct_array.
		Then it implodes the ct_array into a string and assigns it to the  variable. */
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		/* Checking if the status is pending and the work related is not empty. If it is, then it will set
		the req_stat to for approval. If not, then it will set the req_stat to the status. */
		$req_stat = '';
		if($row['status'] == 'Pending' && $row['work_related'] != ''){
			$req_stat = 'for Approval';
		}else{
			$req_stat = $row['status'];
		}

		$paid_on = '';
		$bill = $this->loa_model->get_bill_info($row['loa_id']);
		if(!empty($bill)){
			$billed_on = date('F d, Y', strtotime($bill['billed_on']));
			$paid = $this->loa_model->get_paid_date($bill['details_no']);
			if(!empty($paid)){
				$paid_on = date('F d, Y', strtotime($paid['date_add']));
			}else{
				$paid_on = '';
			}
		}else{
			$billed_on = '';
		}

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'philhealth_no' => $row['philhealth_no'],
			'blood_type' => ($row['city_address']!=="")?$row['blood_type']:'None',
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => ($row['city_address']!=="")?$row['city_address']:'None',
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
			'requesting_physician' => $requesting_physician,
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $req_stat,
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => $row['disapproved_on'] ? date("F d, Y", strtotime($row['disapproved_on'])) : '',
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : '',
			'cancelled_by' => $row['cancelled_by'] ? $row['cancelled_by'] : '',
			'cancelled_on' => $row['cancelled_on'] ? date("F d, Y", strtotime($row['cancelled_on'])) : '',
			'cancellation_reason' => $row['cancellation_reason'],
			'billed_on' => $billed_on,
			'paid_on' => $paid_on,
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function cancel_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_attach_filename($loa_id);
		$deleted = $this->loa_model->db_cancel_loa($loa_id);
		if ($deleted) {
			if ($row['rx_file'] !== '') {
				$file_path = './uploads/loa_attachments/' . $row['rx_file'];
				file_exists($file_path) ? unlink($file_path) : '';
			}
			$response = [
				'token' => $token,
				'status' => 'success', 
				'message' => 'LOA Request Cancelled Successfully'
			];
		} else {
			$response = [
				'token' => $token,
				'status' => 'error',
				'message' => 'LOA Request Cancellation Failed'
			];
		}
		echo json_encode($response);
	}

	function generate_printable_loa() {
		$loa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['mbl'] = $this->loa_model->db_get_member_mbl($exist['emp_id']);
		$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
		$data['doc'] = $this->loa_model->db_get_doctor_by_id($exist['approved_by']);
		$data['cost_types'] = $this->loa_model->db_get_cost_types();
		
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('member_panel/loa/generate_printable_loa');
			$this->load->view('templates/footer');
		}
	}

	function request_loa_cancellation(){
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->input->post('loa_id', TRUE), 'decrypt');
		$current_date = date("Y-m-d");

		$this->form_validation->set_rules('cancellation_reason', 'Reason for Cancellation', 'trim|required|max_length[2000]');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'token' => $token,
				'status' => 'error',
				'cancellation_reason_error' => form_error('cancellation_reason'),
			];
		} else {
			$post_data = [
				'loa_id'              => $loa_id,
				'loa_no'              => $this->input->post('loa_no', TRUE),
				'cancellation_reason' => $this->input->post('cancellation_reason', TRUE),
				'hp_id'               => $this->input->post('hp_id', TRUE),
				'requested_by' 				=> $this->session->userdata('emp_id'),
				'requested_on' 				=> $current_date,
				'status'              => 'Pending',
				'confirmed_by' 				=> '',
				'confirmed_on' 				=> '',
			];

			$inserted = $this->loa_model->db_insert_loa_cancellation_request($post_data);
			// if loa cancellation request is not inserted
			if (!$inserted) {
				$response = [
					'token' => $token,
					'status' => 'save-error',
					'message' => 'Cancellation Request Submit Failed'
				];
			}
			$response = [
				'token' => $token,
				'status' => 'success',
				'message' => 'Cancellation Request Submitted Successfully'
			];	
		}

		echo json_encode($response);
	}


	function fetch_cancelled_loa(){
		$token = $this->security->get_csrf_hash();
		$status = 'Cancelled';
		$emp_id = $this->session->userdata('emp_id');
		$list = $this->loa_model->get_datatables($status, $emp_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCancelledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_services = '';
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				$view_file = $short_med_services = 'None';
			} else {
				// convert into array members selected cost types/med_services using PHP explode
				$selected_cost_types = explode(';', $loa['med_services']);
				// loop through all the cost types from DB
				foreach ($cost_types as $cost_type) :
					if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
						array_push($ct_array, $cost_type['item_description']);
					endif;
				endforeach;
				// convert array to string and add comma as a separator using PHP implode
				$med_services = implode(', ', $ct_array);
				// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
				$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $view_file;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}

	// function fetch_cancelled_loa() {
	// 	$this->security->get_csrf_hash();
	// 	$status = 'Approved';
	// 	$emp_id = $this->session->userdata('emp_id');
	// 	$info = $this->loa_model->get_cancel_datatables($status, $emp_id);
	// 	$dataCancellations = [];

	// 	foreach($info as $data){
	// 		$row = [];
	// 		$loa_id = $this->myhash->hasher($data['loa_id'], 'encrypt');

	// 		$custom_reason = '<a class="text-info fs-6 fw-bold" href="JavaScript:void(0)" onclick="viewReasonModal(\''.$data['cancellation_reason'].'\')"><u>View Reason</u></a>';

	// 		if($data['status'] == 'Approved'){
	// 			$custom_status = '<span class="badge rounded-pill bg-success">Cancelled</span>';
	// 		}
			
	// 		$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

	// 		$row[] = $data['loa_no'];
	// 		$row[] = $data['requested_on'];
	// 		$row[] = $custom_reason;
	// 		$row[] = $data['confirmed_on'];
	// 		$row[] = $data['confirmed_by'];
	// 		$row[] = $custom_status;
	// 		$row[] = $custom_actions;
	// 		$dataCancellations[] = $row;
	// 	}
	// 	$response = [
	// 		"draw" => $_POST['draw'],
	// 		"recordsTotal" => $this->loa_model->count_all_cancell($status, $emp_id),
	// 		"recordsFiltered" => $this->loa_model->count_cancell_filtered($status, $emp_id),
	// 		"data" => $dataCancellations,
	// 	];
	// 	echo json_encode($response);
	// }

}
