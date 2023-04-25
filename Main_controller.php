<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		$this->load->model('ho_accounting/Loa_model');
		$this->load->model('ho_accounting/Noa_model');
		$this->load->model('ho_accounting/List_model');
		if ($logged_in !== true && $user_role !== 'head-office-accounting') {
			redirect(base_url());
		}
	}

	public function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$billingList = $this->List_model->getBilling();
		$intialResult = $finalResult = [];

		foreach (array_column($billingList, 'billing_date') as $date) {
			foreach (array_filter($billingList, function ($v) use ($date) {
				return $v['billing_date'] == $date;
			}) as $billing) {
				$intialResult[$date][$billing['billing_id']] = $billing;
			}
		}

		foreach ($intialResult as $resDate) {
			$initialResDate = [];
			foreach (array_column($resDate, 'hp_id') as $hp_id) {
				foreach (array_filter($resDate, function ($v) use ($hp_id) {
					return $v['hp_id'] == $hp_id;
				}) as $billing) {
					$initialResDate[$hp_id][$billing['billing_id']] = $billing;
				}
			}
			array_push($finalResult, $initialResDate);
		}

		$data["cutoffresult"] = $finalResult;
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function fetch_for_payment() {
		$this->security->get_csrf_hash();
		$status = 'Billed';
		$for_payment = $this->List_model->fetch_for_payment_bill($status);
		$data = [];
		$unique_combinations = []; // array to store unique combinations of hp_id, month, and year
		foreach($for_payment as $bill){
			// Check if current combination of hp_id, month, and year has already been processed
			$combination = $bill['hp_id'] . '_' . $bill['month'] . '_' . $bill['year'];
			if (!in_array($combination, $unique_combinations)) {
				$unique_combinations[] = $combination; // add current combination to unique combinations array
	
				$row = [];
				if($bill['month'] == '01'){
					$month = 'January';
				} else if($bill['month'] == '02'){
					$month = 'February';
				} else if($bill['month'] == '03'){
					$month = 'March';
				} else if($bill['month'] == '04'){
					$month = 'April';
				} else if($bill['month'] == '05'){
					$month = 'May';
				} else if($bill['month'] == '06'){
					$row[] = $bill['hp_name'];
					$month = 'June';
				} else if($bill['month'] == '07'){
					$month = 'July';
				} else if($bill['month'] == '08'){
					$month = 'August';
				} else if($bill['month'] == '09'){
					$month = 'September';
				} else if($bill['month'] == '10'){
					$month = 'October';
				} else if($bill['month'] == '11'){
					$month = 'November';
				} else if($bill['month'] == '12'){
					$month = 'December';
				}
	
				$label_custom = '<span class="fw-bold fs-5">Consolidated Billing for the Month of '.$month.', '.$bill['year'].'</span>';
	
				$hospital_custom = '<span class="fw-bold fs-5">'.$bill['hp_name'].'</span>';
	
				$status_custom = '<span class="badge rounded-pill bg-success text-white">'.$bill['status'].'</span>';
	
				$hp_id = $this->myhash->hasher($bill['hp_id'], 'encrypt');
				$month = $this->myhash->hasher($bill['month'], 'encrypt');
				$year = $this->myhash->hasher($bill['year'], 'encrypt');

				$action_customs = '<a href="' . base_url() . 'head-office-accounting/bill/billed-loa/fetch-payable/' . $hp_id . '/' . $month . '/' . $year . '" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
	
				$action_customs .= '<a href="'.base_url().'head-office-accounting/bill/billed-noa-loa/charging/' . $hp_id . '/' . $month . '/' . $year . '" data-bs-toggle="tooltip" title="View Charging"><i class="mdi mdi-file-document-box fs-2 text-danger"></i></a>';
	
				$row[] = $label_custom;
				$row[] = $hospital_custom;
				$row[] = $status_custom;
				$row[] = $action_customs;
				$data[] = $row;
			}
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
		echo json_encode($output);
	}

	public function fetch_billed(){
		$this->security->get_csrf_hash();
		$status = 'Billed';
		$lists = $this->List_model->get_datatables($status);
		$data = [];
		$total_charge = 0;

		foreach($lists as $value){
			
			$row = [];
			// calling Myhash custom library inside application/libraries folder
			$billing_id = $this->myhash->hasher($value['billing_id'], 'encrypt');
			
			$charge = $value['company_charge'] == '' ? 0 : number_format($value['company_charge'], 2);

			$fullname = $value['first_name']. ' ' .$value['middle_name']. ' ' .$value['last_name'];
			$custom_bill_no = '<mark class="bg-primary text-white">'. $value['billing_no'] .'</mark>';

			$cost_type = $value['loa_id'] != '' ? 'LOA' : 'NOA'; 

			$custom_actions = '<a class="text-info fw-bold ls-1" href="' . base_url() . 'head-office-accounting/billing-list/billed/view/'. $billing_id . '" data-bs-toggle="tooltip"><u>View Receipt</u></a>';
			
			$row[] = $custom_bill_no;
			$row[] = $fullname;
			$row[] = $cost_type;
			$row[] = $value['billed_on'];
			$row[] = $charge;
			$row[] = $custom_actions;
			$data[] = $row;
		}
		
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->List_model->count_all(),
			"recordsFiltered" => $this->List_model->count_filtered($status),
			"data" => $data
		];
		echo json_encode($output);
	}

	function get_company_charge_total() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->input->post('hp_id');
		$startDate = $this->input->post('startDate');
		$endDate = $this->input->post('endDate');
		$status = 'Billed';
		$result = $this->List_model->get_column_sum($hp_id, $startDate, $endDate, $status);

		$response = [
			'token' => $token,
			'total_company_charge' => number_format($result, 2),
		];

		echo json_encode($response);
	}

	function check_image($str){
		if(isset($_FILES['supporting-docu']['name']) && !empty($_FILES['supporting-docu']['name'])){
			return true;
		}else{
			$this->form_validation->set_message('check_image', 'Supporting Document is Required!');
			return false;
		}
	}

	function add_payment_details() {
		$token = $this->security->get_csrf_hash();

		$this->form_validation->set_rules('acc-number', 'Account Number', 'required');
		$this->form_validation->set_rules('acc-name', 'Account Name', 'required');
		$this->form_validation->set_rules('check-number', 'Check Number', 'required');
		$this->form_validation->set_rules('check-date', 'Check Date', 'required');
		$this->form_validation->set_rules('bank', 'Bank', 'required');
		$this->form_validation->set_rules('amount-paid', 'Amount Paid', 'required');
		$this->form_validation->set_rules('supporting-docu', '', 'callback_check_image');

		if(!$this->form_validation->run()){
			echo json_encode([
				'token' => $token,
				'status' => 'validation-error',
				'acc_num_error' => form_error('acc-number'),
				'acc_name_error' => form_error('acc-name'),
				'check_num_error' => form_error('check-number'),
				'check_date_error' => form_error('check-date'),
				'bank_error' => form_error('bank'),
				'paid_error' => form_error('amount-paid'),
				'image_error' => form_error('supporting-docu')
			]);
		}else{
			$config['upload_path'] = './uploads/paymentDetails/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload('supporting-docu')){
				echo json_encode([
					'token' => $token,
					'status' => 'error',
					'message' => 'Image upload failed!'
				]);
			}else{
				$uploadData = $this->upload->data();
				$payment_no = "PMD-" . strtotime(date('Y-m-d h:i:s'));
				$added_by = $this->session->userdata('fullname');

				$data = array(
					"payment_no" => $payment_no,
					"hp_id" => $this->input->post('hp_id'),
					"start_date" => $this->input->post('start_date'),
					"end_date" => $this->input->post('end_date'),
					"company_charge_paid" => $this->input->post('total-company-charge'),
					"acc_number" => $this->input->post('acc-number'),
					"acc_name" => $this->input->post('acc-name'),
					"check_num" => $this->input->post('check-number'),
					"check_date" => $this->input->post('check-date'),
					"bank" => $this->input->post('bank'),
					"amount_paid" => $this->input->post('amount-paid'),
					"supporting_file" => $uploadData['file_name'],
					"date_added" => date('Y-m-d h:i:s'),
					"added_by" => $added_by
					
				);
				$this->List_model->add_payment_details($data);

				$hp_id = $this->input->post('hp_id');
				$startDate = $this->input->post('start_date');
				$endDate = $this->input->post('end_date');
				$status = 'Billed';

				$this->List_model->set_payment_no($hp_id, $startDate, $endDate, $payment_no, $status);

				$result = $this->List_model->get_loa_noa_id($hp_id, $startDate, $endDate);
				
				if (!empty($result)) {
					foreach ($result as $row) {
						$loa_id = $row['loa_id'];
						$noa_id = $row['noa_id'];
				
						if (!empty($loa_id)) {
							$this->List_model->set_loa_status($loa_id);
						}
						if (!empty($noa_id)) {
							$this->List_model->set_noa_status($noa_id);
						}
					}
				}

				echo json_encode([
					'token' => $token,
					'status' => 'success',
					'message' => 'Data Added Successfully!'
				]);
			}
		}
	}

	function fetch_closed() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$lists = $this->List_model->get_closed_datatables($status);
		$data = [];
		$total_charge = 0;

		foreach($lists as $value){
			
			$row = [];
			// calling Myhash custom library inside application/libraries folder
			$billing_id = $this->myhash->hasher($value['billing_id'], 'encrypt');
			
			$charge = $value['company_charge'] == '' ? 0 : number_format($value['company_charge'], 2);

			$fullname = $value['first_name']. ' ' .$value['middle_name']. ' ' .$value['last_name'];
			$custom_payment_no = '<mark class="bg-primary text-white">'. $value['payment_no'] .'</mark>';

			$cost_type = $value['loa_id'] != '' ? 'LOA' : 'NOA'; 

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $value['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewEmployeePaymentD(\'' . $billing_id . '\')" data-bs-toggle="tooltip" title="View Payment Details"><i class="mdi mdi-view-list fs-3 text-info"></i></a>';
			
			$row[] = $custom_payment_no;
			$row[] = $fullname;
			$row[] = $cost_type;
			$row[] = $value['billed_on'];
			$row[] = $charge;
			$row[] = $value['check_date'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}
		
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->List_model->count_closed_all(),
			"recordsFiltered" => $this->List_model->count_closed_filtered($status),
			"data" => $data
		];
		echo json_encode($output);
	}

	function view_employee_payment() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');

		$payment = $this->List_model->get_employee_payment($billing_id);

			$full_name = $payment['first_name'] . ' ' . $payment['middle_name'] . ' ' . $payment['last_name'] . ' ' . $payment['suffix'];
			$status = '<span class="text-success">[Paid]</span>';

			if($payment['loa_id'] != ''){
				$request_type = 'LOA';
			}else{
				$request_type = 'NOA';
			}

			$response = [
				'token' => $token,
				'billing_no' => $payment['billing_no'],
				'hp_name' => $payment['hp_name'],
				'fullname' => $full_name,
				'request_type' => $request_type,
				'billed_on' => $payment['billed_on'],
				'company_charge' => $payment['company_charge'],
				'payment_no' => $payment['payment_no'],
				'check_date' => $payment['check_date'],
				'status' => $status
			];
			echo json_encode($response);
	}

	function fetch_unbilled_loa() {
		$this->security->get_csrf_hash();
        $status = 'Completed';
        $list = $this->Loa_model->get_datatables($status);
        $cost_types = $this->Loa_model->db_get_cost_types();
        $data = [];
        foreach ($list as $loa) {
            $ct_array = $row = [];
            $loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

            $full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

            $custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

            $custom_date = date("m/d/Y", strtotime($loa['request_date']));

            $custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

            $custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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
                        array_push($ct_array, $cost_type['cost_type']);
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
            $row[] = $full_name;
            $row[] = $loa['loa_request_type'];
            $row[] = $short_med_services;
            $row[] = $view_file;
            $row[] = $custom_date;
            $row[] = $custom_status;
            $row[] = $custom_actions;
            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Loa_model->count_all($status),
            "recordsFiltered" => $this->Loa_model->count_filtered($status),
            "data" => $data,
        ];
        echo json_encode($output);
	}

	function fetch_unbilled_noa() {
		$this->security->get_csrf_hash();
		$status = 'Completed';
		$list = $this->Noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $short_hosp_name;
			$row[] = $admission_date;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Noa_model->count_all($status),
			"recordsFiltered" => $this->Noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function unbilled_noa_details() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->Noa_model->db_get_noa_info($noa_id);

		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->Noa_model->db_get_doctor_by_id($row['approved_by']);
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

	function view_billed_details(){
		$id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['bill'] = $bill = $this->List_model->get_billing_info($id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['mbl'] = $this->List_model->get_member_mbl($bill['emp_id']);
		$data['services'] = $this->List_model->get_billing_services($bill['billing_no']);
		$data['deductions'] = $this->List_model->get_billing_deductions($bill['billing_no']);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/billing_receipt');
		$this->load->view('templates/footer');
		
	}

	function unbilled_loa_details() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
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
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_hp_name() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->input->post('hp_id');
		$hc_provider = $this->List_model->db_get_hp_name($hp_id);

		$response = [
			'token' => $token,
			'hp_name' => $hc_provider['hp_name'],
		];
		echo json_encode($response);
	}

	function payment_history_fetch() {
		$this->security->get_csrf_hash();

		$list = $this->List_model->get_payment_datatables();
		$data = [];

		foreach($list as $payment){
			$row = [];
			$payment_id = $this->myhash->hasher($payment['payment_id'], 'encrypt');

			$custom_payment_no = 	'<mark class="bg-primary text-white">'.$payment['payment_no'].'</mark>';

			$custom_actions = '<a class="text-info fw-bold ls-1 fs-4" href="JavaScript:void(0)" onclick="viewPaymentInfo(\'' . $payment_id . '\')"  data-bs-toggle="tooltip"><u><i class="mdi mdi-view-list fs-3" title="View Payment Details"></i></u></a>';

			$custom_actions .= '<a class="text-info fw-bold ls-1 ps-2 fs-4" href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'assets/paymentDetails/' . $payment['supporting_file'] . '\')" data-bs-toggle="tooltip"><u><i class="mdi mdi-file-image fs-3" title="View Proof"></i></u></a>';

			$row[] = $custom_payment_no;
			$row[] = $payment['acc_number'];
			$row[] = $payment['acc_name'];
			$row[] = $payment['check_num'];
			$row[] = $payment['check_date'];
			$row[] = $payment['bank'];
			$row[] = $custom_actions;
			$data[] = $row;

		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->List_model->count_payment_all(),
			"recordsFiltered" => $this->List_model->count_payment_filtered(),
			"data" => $data
		];
		echo json_encode($output);
	}

	function view_payment_details() {
		$payment_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$payment = $this->List_model->get_payment_details($payment_id);
		
		$payment_no = $payment['payment_no'];
		$loa_no = $this->List_model->get_loa($payment_no);
		$noa_no = $this->List_model->get_noa($payment_no);
		$noa_loa_array = [];
		foreach($loa_no as $covered_loa){
			if($covered_loa['loa_id'] != '' ){
				array_push($noa_loa_array, $covered_loa['loa_no']);
			}
		}

		foreach($noa_no as $covered_noa){
			if($covered_noa['noa_id'] != ''){
				array_push($noa_loa_array, $covered_noa['noa_no']);
			}
		}

		$loa_noa_no = implode(',    ', $noa_loa_array);
		
			$response = [
				'status' => 'success',
				'token' => $this->security->get_csrf_hash(),
				'payment_no' => $payment['payment_no'],
				'hp_name' => $payment['hp_name'],
				'start_date' => $payment['start_date'],
				'end_date' => $payment['end_date'],
				'acc_number' => $payment['acc_number'],
				'acc_name' => $payment['acc_name'],
				'check_num' => $payment['check_num'],
				'check_date' => $payment['check_date'],
				'bank' => $payment['bank'],
				'amount_paid' => $payment['amount_paid'],
				'covered_loa_no' => $loa_noa_no
				
			]; 
		echo json_encode($response);
	}

	function fetch_consolidated_bill() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['month'] = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
		$data['year'] = $this->myhash->hasher($this->uri->segment(7), 'decrypt');
		$data['hc_provider'] = $this->List_model->fetch_monthly_billed_noa($hp_id);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/view_monthly_bill');
		$this->load->view('templates/footer');
	}

	function fetch_monthly_bill() {
		$token = $this->security->get_csrf_hash();
	
		$hp_id = $this->input->post('hp_id');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$converted_month = (int)$month;
		$payable = $this->List_model->get_payment_nos($hp_id,$year,$converted_month);

		$data = []; // Create an empty array to hold the data
	
		foreach($payable as $pay){
			$billing = $this->List_model->monthly_bill_datatable($pay['payment_no']);

			foreach($billing as $bill){
				$row = [];
				
				$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
	
				$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-dark"></i>View</a>';
	
				$row[] = $bill['billing_no'];
				$row[] = $fullname;
				$row[] = number_format($bill['net_bill'], 2, '.', ',');
				$row[] = $pdf_bill;
				$data[] = $row;
	
			}
		}
	
		// Output the JSON data for all the fetched data
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}
	
}

