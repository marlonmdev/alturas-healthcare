<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/loa_model');
		$this->load->library('session');
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

	function check_hospital_receipt($str) {
		
			if (isset($_FILES['hospital-receipt']['name']) && !empty($_FILES['hospital-receipt']['name'])) {
				return true;
			} else {
				$this->form_validation->set_message('check_hospital_receipt', 'Please choose Hospital Receipt file to upload.');
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
		$med_services = json_decode($this->input->post('med-services'),true);
		// var_dump('med services',count((array)$med_services));
		if (count((array)$med_services) < 1) {
			
			$this->form_validation->set_message('multiple_select', 'Select at least one Service');
			return false;
		} else {
			return true;
		}
	}
	function edit_multiple_select() {
		$med_services = json_decode($this->input->post('edit-med-services'),true);
		//  var_dump('med services',$med_services);
		if (count((array)$med_services) < 1) {
			
			$this->form_validation->set_message('edit_multiple_select', 'Select at least one Service');
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

	function loa_form_validation($type,$is_accredited) {
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
				$this->form_validation->set_rules('healthcare-provider-category', 'HealthCare Provider Category', 'required');
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				
				$is_accredited = $this->session->userdata('is_accredited');
				// var_dump('is_accredited',$is_accredited);
				if(!$is_accredited){
					$this->form_validation->set_rules('hospital-receipt', '', 'callback_check_hospital_receipt');
					$this->form_validation->set_rules('hospital-bill', 'Hospital Bill','required');
				}else{
					$this->form_validation->set_rules('rx-file', '', 'callback_check_rx_file');
				}
				$this->session->unset_userdata('is_accredited');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'healthcare_provider_category_error' => form_error('healthcare-provider-category'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
						'hospital_receipt_error' => form_error('hospital-receipt'),
						'hospital_bill_error' => form_error('hospital-bill'),
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
				$this->form_validation->set_rules('healthcare-provider-category', 'HealthCare Provider Category', 'required');
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('edit-med-services', 'Medical Services', 'callback_edit_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				
				$is_rx_has_data = $this->session->userdata('is_rx_has_data');
				$is_hr_has_data = $this->session->userdata('is_hr_has_data');
				$inputed_files = $this->session->userdata('inputed_files');
				$is_accredited = $this->session->userdata('is_accredited');
				// var_dump('rx_file',$inputed_files['rx_file']);
				// var_dump('hospital_receipt',$inputed_files['hospital_receipt']);
				// var_dump('is_accredited',$is_accredited);
				if(!$is_accredited){
					if((!$is_hr_has_data && isset($inputed_files['hospital_receipt'])) || (!$is_hr_has_data && !isset($inputed_files['hospital_receipt']))){
						$this->form_validation->set_rules('hospital-receipt', '', 'callback_check_hospital_receipt');
						$this->form_validation->set_rules('hospital-bill', 'Hospital Bill','required');
					}
				}else{
					if((!$is_rx_has_data && !isset($inputed_files['hospital_receipt'])) || (!$is_rx_has_data && isset($inputed_files['hospital_receipt']))){
						$this->form_validation->set_rules('rx-file', '', 'callback_check_rx_file');
					}
					
				}
				$this->session->unset_userdata('inputed_files');
				$this->session->unset_userdata('is_accredited');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'healthcare_provider_category_error' => form_error('healthcare-provider-category'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('edit-med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
						'hospital_receipt_error' => form_error('hospital-receipt'),
						'hospital_bill_error' => form_error('hospital-bill'),
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
	// function get_hp_services(){
	// 	$token = $this->security->get_csrf_hash();
	// 	$hp_id = $this->uri->segment(3);
	// 	$cost_types = $this->loa_model->db_get_cost_types_by_hp($hp_id);
	// 	$response = '';

	// 	if(empty($cost_types)){
	// 		$response .= '<select class="chosen-select form-select" id="med-services" name="med-services[]" multiple="multiple">';
	// 		$response .= '<option value="" disabled>No Available Services</option>';
	// 		$response .= '</select>';
	// 	}else{
	// 		$response .= '<select class="chosen-select form-select" id="med-services" name="med-services[]" data-placeholder="Choose services..." multiple="multiple">';
	// 		foreach ($cost_types as $cost_type) {
	// 			$response .= '<option value="'.$cost_type['ctype_id'].'" data-price="'.$cost_type['op_price'].'">'.$cost_type['item_description'].''.' ₱'.''.number_format($cost_type['op_price'],2,'.',',').'</option>';
	// 		}
	// 		$response .= '</select>';
	// 	}
	// 	echo json_encode($response);
	// }

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
		$is_accredited =$request_type !== "Emergency"?(json_decode($_POST['is_accredited'],true)):false;
		$this->session->set_userdata('is_accredited', $is_accredited);
		// var_dump('hp_id',$hp_id);
		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty',$is_accredited);
				break;
			case ($request_type == 'Consultation'):
				$this->loa_form_validation('Consultation',$is_accredited);
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
				$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file,"",$is_accredited);
				break;
			case ($request_type == 'Diagnostic Test'):
				$this->loa_form_validation('Diagnostic Test',$is_accredited);
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
					$file_paths = [
						'hospital-receipt' => './uploads/hospital_receipt/',
						'rx-file' => './uploads/loa_attachments/'
					];
					$file_inputs = ['rx-file','hospital-receipt'];
					$uploaded_files = [];
					$error_occurred = false;
					foreach ($file_inputs as $input_name) {
						if ($input_name === 'hospital-receipt' && empty($_FILES[$input_name]['name'])) {
							// Skip the 'Medical-Abstract' field if it is empty
							continue;
						}
						if ($input_name === 'rx-file' && empty($_FILES[$input_name]['name'])) {
							// Skip the 'Medical-Abstract' field if it is empty
							continue;
						}
		
						$config['upload_path'] = $file_paths[$input_name];
						$this->upload->initialize($config);
		
						if (!$this->upload->do_upload($input_name)) {
							$error = $this->upload->display_errors();
							if (!empty($error)) {
								// If error occurred for required files or any other file, set error flag
								$error_occurred = TRUE;
							}
						} else {
							$uploaded_files[$input_name] = $this->upload->data();
						}
					}

					if($error_occurred){
						$response = [
							'status' => 'save-error',
							'message' => 'File Not Uploaded'
						];
						echo json_encode($response);
						exit();
					}else{
						$med_services = $this->input->post('med-services');
						// var_dump('services',$med_services);
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
						$this->insert_loa($input_post, $med_services, $attending_physician, isset($uploaded_files['rx-file']['file_name'])?$uploaded_files['rx-file']['file_name']:null, isset($uploaded_files['hospital-receipt']['file_name'])?$uploaded_files['hospital-receipt']['file_name']:null,$is_accredited);
					}
					// if (!$this->upload->do_upload('rx-file')) {
					// 	$response = [
					// 		'status' => 'save-error',
					// 		'message' => 'File Not Uploaded'
					// 	];
					// 	echo json_encode($response);
					// 	exit();
					// } else {
					// 	$upload_data = $this->upload->data();
					// 	$rx_file = $upload_data['file_name'];

					// 	$med_services = $this->input->post('med-services');
					// 	// var_dump('services',$med_services);
					// 	// for physician multi-tags input
					// 	if(empty($physicians_tags)) {
					// 		$attending_physician = '';
					// 	} else {
					// 		foreach ($physicians_tags as $physician_tag) :
					// 			array_push($physician_arr, ucwords($physician_tag['value']));
					// 		endforeach;
					// 		$attending_physician = implode(', ', $physician_arr);
					// 	}

					// 	// Call function insert_loa
					// 	$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file);
					// }
				}
				break;
				case ($request_type == 'Emergency'):
					$this->loa_form_validation('Emergency',$is_accredited);
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
							$this->insert_loa($input_post, "", "", "","",$is_accredited);
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

	function insert_loa($input_post, $med_services, $attending_physician, $rx_file, $hospital_receipt, $is_accredited) {
		// select the max loa_id from DB
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		$current_year = date('Y');
		// call function loa_number
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);

		$emp_id = $this->session->userdata('emp_id');
		$member = $this->loa_model->db_get_member_infos($emp_id);
		$tagvalue = json_decode($med_services);
		$services = [];
		// var_dump('rx_file',$rx_file);
		if($tagvalue!==null){
			foreach($tagvalue as $value){
				$services[] = (count(get_object_vars($value))>1)?$value->tagid:$value->value; 
			}
		}
		
		// var_dump($services);
		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $emp_id,
			'first_name' =>  $member['first_name'],
			'middle_name' =>  $member['middle_name'],
			'last_name' =>  $member['last_name'],
			'suffix' =>  $member['suffix'],
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'is_manual' => $is_accredited ? 0 : 1,
			'med_services' => ($services!=="")?implode(';', $services):"",
			'health_card_no' => $member['health_card_no'],
			'requesting_company' => $member['company'],
			'request_date' => date("Y-m-d"),
			'hospital_bill' =>  !$is_accredited ? $input_post['hospital-bill'] : null,
			'emerg_date' => (isset( $input_post['admission-date']))? $input_post['admission-date']:null,
			'chief_complaint' => (isset($input_post['chief-complaint']))?strip_tags($input_post['chief-complaint']):"",
			'requesting_physician' =>(isset($input_post['requesting-physician']))? ucwords($input_post['requesting-physician']):"",
			'attending_physician' => $attending_physician,
			'rx_file' => isset($rx_file)?$rx_file:null,
			'hospital_receipt' => isset($hospital_receipt)?$hospital_receipt:null,
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
		$data['ahcproviders'] = $this->loa_model->db_get_affiliated_healthcare_providers();
		$data['hcproviders'] = $this->loa_model->db_get_not_affiliated_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->loa_model->db_get_cost_types();
		$data['doctors'] = $this->loa_model->db_get_company_doctors();
		$data['is_accredited'] = ($exist['is_manual'])?true:false;
		$mbl = $this->loa_model->db_get_mbl($exist['emp_id']);
		$get_pac_loa = $this->loa_model->get_pac_loa($exist['emp_id']); 

		$med_services = 0;
    
        foreach ($get_pac_loa as $loa) :
			// var_dump("loa",$loa);
			$exploded_med_services = explode(";", $loa['med_services']);
			foreach ($exploded_med_services as $ctype_id) :
				$cost_type = $this->loa_model->get_loa_op_price($ctype_id);
					// var_dump(floatval($cost_type['op_price']));
					//var_dump("ctype_id",$ctype_id);
					if($loa['loa_request_type'] != 'Consultation'){
						$med_services += floatval($cost_type['op_price']); 
					}else{
						$med_services+= 500;
					}
			endforeach;

        endforeach;
		$r_mbl = floatval($mbl['remaining_balance'])-floatval($med_services);
		$data['mbl'] =  number_format(($r_mbl > 1) ? $r_mbl : 0,2);
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
		// var_dump('input post ',$input_post);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$hp_id = $this->input->post('healthcare-provider');
		$request_type = $this->input->post('loa-request-type');
		$is_accredited =$request_type !== "Emergency"?(json_decode($_POST['is_accredited'],true)):false;
		$is_rx_has_data = json_decode($_POST['is_rx_has_data'],true);
		$is_hr_has_data = json_decode($_POST['is_hr_has_data'],true);
		$this->session->set_userdata(['is_accredited'=> $is_accredited,
										'is_rx_has_data' => $is_rx_has_data,
											'is_hr_has_data' => $is_hr_has_data]);
		$important_data = [
			'rx_file' => $_FILES['rx-file']['name'],
			'hospital_receipt' => $_FILES['hospital-receipt']['name'],
			// Add more fields as needed
		];

		$this->session->set_userdata('inputed_files', $important_data);			

		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty',$is_accredited);
				break;
			case ($request_type == 'Consultation'):
				$this->loa_form_validation('Consultation',$is_accredited);
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
				$this->loa_form_validation('Diagnostic Test Update',$is_accredited);
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);

				if (!$hp_exist) {
					$response = ['status' => 'save-error', 'message' => 'Healthcare Provider Does Not Exist'];
					echo json_encode($response);
					exit();
				} else {
					
					$med_services = $this->input->post('edit-med-services');
					// var_dump('med services',$med_services);
					$old_rx_file = $input_post['file-attachment-rx'];
					$old_hr_file = $input_post['file-attachment-receipt'];
					$rx_file = '';
					$hospital_receipt = '';
				
					// for physician multi-tags input
					if(empty($physicians_tags)) {
						$attending_physician = '';
					} else {
						foreach ($physicians_tags as $physician_tag) :
							array_push($physician_arr, ucwords($physician_tag['value']));
						endforeach;
						$attending_physician = implode(', ', $physician_arr);
					}

						// if there is a new file to be uploaded
						$config['allowed_types'] = 'jpg|jpeg|png';
						$config['encrypt_name'] = TRUE;
						$this->load->library('upload', $config);
						$file_paths = [
							'hospital-receipt' => './uploads/hospital_receipt/',
							'rx-file' => './uploads/loa_attachments/'
						];
						$file_inputs = ['rx-file','hospital-receipt'];
						$uploaded_files = [];
						$error_occurred = false;
						$is_file_exist = false;
						$is_file_empty = true;
						foreach ($file_inputs as $input_name) {
							// var_dump('inputed files',$_FILES[$input_name]['name']);
							// var_dump('is readable',is_readable($file_paths[$input_name].$_FILES[$input_name]['name']));
							// var_dump('is existed',file_exists($file_paths[$input_name].$_FILES[$input_name]['name']));
							if($_FILES[$input_name]['name']!==''){
								$is_file_empty = false;
								if(file_exists($file_paths[$input_name].$_FILES[$input_name]['name']) && $_FILES[$input_name]['name'] !== ''){
									$is_file_exist = true;
									continue;
								}
								if ($input_name === 'hospital-receipt' && empty($_FILES[$input_name]['name'])) {
									// Skip the 'Medical-Abstract' field if it is empty
									continue;
								}
								if ($input_name === 'rx-file' && empty($_FILES[$input_name]['name'])) {
									// Skip the 'Medical-Abstract' field if it is empty
									continue;
								}
				
								$config['upload_path'] = $file_paths[$input_name];
								$this->upload->initialize($config);
				
								if (!$this->upload->do_upload($input_name)) {
									$error = $this->upload->display_errors();
									if (!empty($error)) {
										// If error occurred for required files or any other file, set error flag
										$error_occurred = TRUE;
									}
								} else {
									$uploaded_files[$input_name] = $this->upload->data();
								}
							}
						}
						
						// var_dump('uploaded_files',$uploaded_files);
						
						if($error_occurred){
							$response = [
								'status' => 'save-error',
								'message' => 'File Not Uploaded'
							];
							echo json_encode($response);
							exit();
						}else{
							// var_dump('is_file_empty',$is_file_empty);
							if($is_file_exist){
								$rx_file = ($rx_file!=='')?$rx_file:null;
								$hospital_receipt = ($hospital_receipt!=='')?$hospital_receipt:null;
							}else{

								$rx_file = ($is_accredited && !$is_file_empty) ? $uploaded_files['rx-file']['file_name'] : $old_rx_file;
								$hospital_receipt = (!$is_accredited && !$is_file_empty) ? $uploaded_files['hospital-receipt']['file_name'] : $old_hr_file;
								if ($old_rx_file !== '' && !$is_file_empty) {
									$file_path = './uploads/loa_attachments/' . $old_rx_file;
									file_exists($file_path) ? unlink($file_path) : '';
								}
								if ($old_hr_file !== '' && !$is_file_empty) {
									$file_path = './uploads/hospital_receipt/' . $old_hr_file;
									file_exists($file_path) ? unlink($file_path) : '';
								}
							}
						}
						
						
					
					// Call function update_loa			
					$this->update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file, $hospital_receipt, $is_accredited);
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

	function update_loa($loa_id, $input_post, $med_services, $attending_physician, $rx_file, $hospital_receipt, $is_accredited) {
		$tagvalue = json_decode($med_services);
		$services = [];
		$post_data = [];
		// var_dump('rx_file',$rx_file);
		if($tagvalue!==null){
			foreach($tagvalue as $value){
				$services[] = (count(get_object_vars($value))>1)?$value->tagid:$value->value; 
			}
		}

		if($is_accredited){
			$post_data = [
				'emp_id' => $this->session->userdata('emp_id'),
				'hcare_provider' => $input_post['healthcare-provider'],
				'loa_request_type' => $input_post['loa-request-type'],
				'med_services' => implode(';', $services),
				'chief_complaint' => strip_tags($input_post['chief-complaint']),
				'requesting_physician' => ucwords($input_post['requesting-physician']),
				'hospital_bill' =>  null,
				'attending_physician' => $attending_physician,
				'rx_file' => $rx_file,
				'hospital_receipt' => null,
				'is_manual' => 0,
			];
		}else{
			// var_dump('hospital_bill',$input_post['hospital-bill']);
			$post_data = [
				'emp_id' => $this->session->userdata('emp_id'),
				'hcare_provider' => $input_post['healthcare-provider'],
				'loa_request_type' => $input_post['loa-request-type'],
				'med_services' => implode(';', $services),
				'chief_complaint' => strip_tags($input_post['chief-complaint']),
				'requesting_physician' => ucwords($input_post['requesting-physician']),
				'hospital_bill' =>  $input_post['hospital-bill'],
				'attending_physician' => $attending_physician,
				'rx_file' => null,
				'hospital_receipt' => $hospital_receipt,
				'is_manual' => 1,
			];
		}
		
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
		// var_dump('resultlist',$resultList);
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

			if($value['spot_report_file'] && $value['incident_report_file'] != ''){
				$button .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $value['work_related'] . '\',\'' . $value['percentage'] . '\',\'' . $value['spot_report_file'] . '\',\'' . $value['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$button .= '';
			}

			$button .= '<a class="me-2 align-top" style="top:-20px!important;" href="' . base_url() . 'member/requested-loa/edit/' . $loa_id . '" data-bs-toggle="tooltip" title="Edit LOA"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$button .= '<a class="align-top" style="top:-20px!important;" href="JavaScript:void(0)" onclick="cancelPendingLoa(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Delete LOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_serv = '';
			$view_receipt = '';
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
				if($value['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($value['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				
				// var_dump('hospital_receipt',$value['hospital_receipt']);
			}

			$result['data'][] = [
				$custom_loa_no,
				$short_hp_name,
				$value['loa_request_type'],
				$request_date,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'None',
				$view_receipt,
				$custom_status,
				$button
			];
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

			if($value['spot_report_file'] && $value['incident_report_file'] != ''){
				$buttons .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $value['work_related'] . '\',\'' . $value['percentage'] . '\',\'' . $value['spot_report_file'] . '\',\'' . $value['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$buttons .= '';
			}

			// $for_cancellation = $this->loa_model->db_get_loa_cancellation_request($value['loa_id']);

			// if(empty($for_cancellation['status']) || $for_cancellation['status'] == 'Disapproved'){
			// 	$buttons .= '<a class="me-2" href="JavaScript:void(0)" onclick="requestLoaCancellation(\'' . $loa_id . '\', \'' . $value['loa_no'] . '\', \'' . $value['hcare_provider'] . '\')" data-bs-toggle="tooltip" title="Request LOA Cancellation"><i class="mdi mdi-close-circle fs-2 text-danger"></i></a>';
			// }else{
			// 	$buttons .= '<a class="me-2" data-bs-toggle="tooltip" title="Requested for Cancellation" disabled><i class="mdi mdi-close-circle fs-2 icon-disabled"></i></a>';
			// }

			// $button .= '<a href="' . base_url() . 'member/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Generate Printable LOA"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_med_serv = '';
			$view_receipt = '';
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
				$short_med_serv = strlen($med_serv) > 30 ? substr($med_serv, 0, 30) . "..." : $med_serv;
				if($value['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($value['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			$result['data'][] = [
				$custom_loa_no,
				$short_hp_name,
				$value['loa_request_type'],
				$expiry_date,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'None',
				$view_receipt,
				'<span class="badge rounded-pill bg-success">' . $value['status'] . '</span>',
				$buttons
			];
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
			$view_receipt = '';
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
				$short_med_serv = strlen($med_serv) > 30 ? substr($med_serv, 0, 30) . "..." : $med_serv;
				if($value['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $value['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($value['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $value['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			$result['data'][] = [
				$custom_loa_no,
				date("m/d/Y", strtotime($value['request_date'])),
				$short_hp_name,
				$value['loa_request_type'],
				// $short_med_serv,
				($value['loa_request_type'] === 'Diagnostic Test')?$view_file:'None',
				$view_receipt,
				'<span class="badge rounded-pill bg-danger">' . $value['status'] . '</span>',
				$button
			];
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
			$view_receipt = '';
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
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'None';
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		];
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
			$view_receipt = '';
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
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'None';
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		];
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
			$view_receipt = '';
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
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'None';
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"data" => $data,
		];
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
			$view_receipt = '';
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
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $custom_date;
			$row[] = $short_hp_name;
			$row[] = $loa['loa_request_type'];
			// $row[] = $short_med_services;
			$row[] = ($loa['loa_request_type'] ==='Diagnostic Test')?$view_file:'None';
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"data" => $data,
		];
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
		$is_empty = true;
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
				$is_empty = false;
			}
		endforeach;
		$selected_not_in_cost_types = array_diff($selected_cost_types, array_column($cost_types, 'ctype_id'));

		foreach ($selected_not_in_cost_types as $selected_cost_type) {
			// Handle the selected_cost_type that does not belong to $cost_types array
			if($selected_cost_type !== ''){
				$is_empty = false;
				array_push($ct_array, '[ <span class="text-success">' . $selected_cost_type . '</span> ]');
			}
			
		}
		$med_serv = implode(' ', $ct_array);

		/* Checking if the status is pending and the work related is not empty. If it is, then it will set
		the req_stat to for approval. If not, then it will set the req_stat to the status. */
		$req_stat = '';
		if($row['status'] == 'Pending' && $row['work_related'] != ''){
			$req_stat = 'for Approval';
		}else{
			$req_stat = $row['tbl_1_status'];
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
		// var_dump('workrelated',$row['work_related']);
		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'net_bill' => isset( $row['net_bill'])?$row['net_bill']:'',
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
			'med_services' => (!$is_empty)?$med_serv:'None',
			'hospital_bill' => isset($row['hospital_bill']) ? $row['hospital_bill'] : '',
			'hospital_receipt' => $row['hospital_receipt'],
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['tbl_1_request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $requesting_physician,
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $req_stat,
			'work_related' => $row['tbl_1_workrelated'],
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

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status, $emp_id),
			"recordsFiltered" => $this->loa_model->count_filtered($status, $emp_id),
			"data" => $data,
		];
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
