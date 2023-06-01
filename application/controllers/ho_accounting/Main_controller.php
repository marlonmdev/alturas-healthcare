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
		$concatenated = '';
		$payment_nos = [];

		foreach($for_payment as $bill){
			$last_two_digits = substr($bill['bill_no'], -2);
			$group_key = $bill['hp_id'] . '_' . $bill['month'] . '_' . $bill['year']; // Create a unique group key for each group
			if (!isset($payment_nos[$group_key])) {
				$payment_nos[$group_key] = 'PMT-0000'; // Initialize the concatenated payment_no for this group
			}
			$payment_nos[$group_key] .= $last_two_digits; // Concatenate the last two digits to the payment_no for this group
			foreach ($payment_nos as $payment_no) {
				list($hp_id, $month, $year) = explode('_', $group_key);
				$this->List_model->set_payment_no($hp_id, $month, $year, $payment_no);
			}
		}
		$payment = $this->List_model->fetch_for_payment_bill($status);
		foreach($payment as $bill){
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

				$payment_no_custom = '<span class="fw-bold fs-5">'.$bill['payment_no'].'</span>';
	
				$label_custom = '<span class="fw-bold fs-5">Consolidated Billing for the Month of '.$month.', '.$bill['year'].'</span>';
	
				$hospital_custom = '<span class="fw-bold fs-5">'.$bill['hp_name'].'</span>';
	
				$status_custom = '<span class="badge rounded-pill bg-info text-white">'.$bill['status'].'</span>';
	
				$hp_id = $this->myhash->hasher($bill['hp_id'], 'encrypt');
				$month = $this->myhash->hasher($bill['month'], 'encrypt');
				$year = $this->myhash->hasher($bill['year'], 'encrypt');

				$action_customs = '<a href="' . base_url() . 'head-office-accounting/bill/billed-loa/fetch-payable/' . $hp_id . '/' . $month . '/' . $year . '" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
	
				$action_customs .= '<a href="'.base_url().'head-office-accounting/bill/billed-noa-loa/print/' . $hp_id . '/' . $month . '/' . $year . '" data-bs-toggle="tooltip" title="Print Reports"><i class="mdi mdi-printer fs-2 text-primary"></i></a>';

				// $action_customs .= '<a href="JavaScript:void(0)" onclick="addPaymentDetails()" data-bs-toggle="tooltip" title="View Check Details"><i class="mdi mdi-file-document fs-2 text-cyan"></i></a>';
	
				$row[] = $payment_no_custom;
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

	function view_paid_loa_noa() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
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

				$payment_no = '<span class="fw-bold fs-5">'.$bill['payment_no'].'</span>';
	
				$label_custom = '<span class="fw-bold fs-5">Paid Bill for the Month of '.$month.', '.$bill['year'].'</span>';
	
				$hospital_custom = '<span class="fw-bold fs-5">'.$bill['hp_name'].'</span>';
	
				$status_custom = '<span class="badge rounded-pill bg-success text-white">'.$bill['status'].'</span>';
	
				$hp_id = $this->myhash->hasher($bill['hp_id'], 'encrypt');
				$month = $this->myhash->hasher($bill['month'], 'encrypt');
				$year = $this->myhash->hasher($bill['year'], 'encrypt');

				$action_customs = '<a href="' . base_url() . 'head-office-accounting/bill/paid-loa/fetch-payable/' . $hp_id . '/' . $month . '/' . $year . '" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
	
				$action_customs .= '<a href="JavaScript:void(0)" onclick="viewPaymentDetails(\''.$bill['details_no'].'\')"
				 data-bs-toggle="tooltip" title="View Check Details"><i class="mdi mdi-file-document fs-2 text-cyan"></i></a>';
			
				$row[] = $payment_no;
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
				$counter = 1;
				$counter++;
				$details_no = "details-" . strtotime(date('h:i:s')).$counter;
				
				
				$added_by = $this->session->userdata('fullname');

				$data = array(
					"details_no" => $details_no,
					"hp_id" => $this->input->post('pd-hp-id'),
					"total_hp_bill" => str_replace(',', '', $this->input->post('p-total-bill')),
					"acc_number" => $this->input->post('acc-number'),
					"acc_name" => $this->input->post('acc-name'),
					"check_num" => $this->input->post('check-number'),
					"check_date" => $this->input->post('check-date'),
					"bank" => $this->input->post('bank'),
					"amount_paid" => $this->input->post('amount-paid'),
					"supporting_file" => $uploadData['file_name'],
					"date_add" => date('Y-m-d h:i:s'),
					"added_by" => $added_by
					
				);
				$this->List_model->add_payment_details($data);
				$paid_by = $this->session->userdata('fullname');
				$paid_on = $this->input->post('check-date');
				$payment_no = $this->input->post('pd-payment-no');
			
				$this->List_model->set_details_no($payment_no,$details_no);
				$this->List_model->set_monthly_payable($payment_no,$paid_by,$paid_on);
				$result = $this->List_model->get_loa_noa_id($payment_no);

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
			$custom_bill_no = '<mark class="bg-primary text-white">'. $value['bill_no'] .'</mark>';

			$cost_type = $value['loa_id'] != '' ? 'LOA' : 'NOA'; 

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $value['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewEmployeePaymentD(\'' . $billing_id . '\')" data-bs-toggle="tooltip" title="View Payment Details"><i class="mdi mdi-view-list fs-3 text-info"></i></a>';
			
			$row[] = $custom_bill_no;
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
				'bill_no' => $payment['bill_no'],
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
		$previous_payment_no = '';
	
		foreach($list as $payment){
			// Check if payment_no is the same as the previous iteration
			if ($payment['payment_no'] !== $previous_payment_no) {
				$row = [];
				$details_id = $this->myhash->hasher($payment['details_id'], 'encrypt');
	
				$custom_details_no = '<span class="text-dark fw-bold">'.$payment['payment_no'].'</span>';
	
				$custom_actions = '<a class="text-info fw-bold ls-1 fs-4" href="JavaScript:void(0)" onclick="viewPaymentInfo(\'' . $details_id . '\',\'' . base_url() . 'uploads/paymentDetails/' . $payment['supporting_file'] . '\')"  data-bs-toggle="tooltip"><u><i class="mdi mdi-view-list fs-3" title="View Payment Details"></i></u></a>';
	
				// $custom_actions .= '<a class="text-success fw-bold ls-1 ps-2 fs-4" href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/paymentDetails/' . $payment['supporting_file'] . '\')" data-bs-toggle="tooltip"><u><i class="mdi mdi-file-image fs-3" title="View Proof"></i></u></a>';
	
				$row[] = $custom_details_no;
				$row[] = $payment['acc_number'];
				$row[] = $payment['acc_name'];
				$row[] = $payment['check_num'];
				$row[] = $payment['check_date'];
				$row[] = $payment['bank'];
				$row[] = $custom_actions;
				$data[] = $row;
				
				// Update the previous_payment_no variable
				$previous_payment_no = $payment['payment_no'];
			}
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
		$details_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$payment = $this->List_model->get_payment_details($details_id);
		
		$loa_no = $this->List_model->get_loa($payment['details_no']);
		$noa_no = $this->List_model->get_noa($payment['details_no']);
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
				'added_on' => date("F d, Y", strtotime($payment['date_add'])),
				'acc_number' => $payment['acc_number'],
				'acc_name' => $payment['acc_name'],
				'check_num' => $payment['check_num'],
				'check_date' => $payment['check_date'],
				'bank' => $payment['bank'],
				'amount_paid' => $payment['amount_paid'],
				'billed_date' => 'From '. date("F d, Y", strtotime($payment['startDate'])).' to '. date("F d, Y", strtotime($payment['endDate'])),
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

	function fetch_consolidated_paid() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['month'] = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
		$data['year'] = $this->myhash->hasher($this->uri->segment(7), 'decrypt');
		$data['hc_provider'] = $this->List_model->fetch_monthly_billed_noa($hp_id);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/view_monthly_paid_bill');
		$this->load->view('templates/footer');
	}

	function fetch_monthly_bill() {
		$status = 'Billed';
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->input->post('hp_id');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$converted_month = (int)$month;
		$payable = $this->List_model->get_bill_nos($hp_id,$year,$converted_month,$status);

		$data = []; // Create an empty array to hold the data
		
		foreach($payable as $pay){
			$billing = $this->List_model->monthly_bill_datatable($pay['bill_no']);
	
			foreach($billing as $bill){
				$row = [];

				$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

				if($bill['loa_id'] != ''){
					$loa_noa = $bill['loa_no'];

				}else if($bill['noa_id'] != ''){
					$loa_noa = $bill['noa_no'];
				}
				
				$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-dark"></i>View</a>';

				$row[] = $bill['billing_no'];
				$row[] = $loa_noa;
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

	function get_total_hp_bill() {
		$status = 'Billed';
		$hp_id = $this->input->post('hp_id');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$converted_month = (int)$month;
		$payable = $this->List_model->get_bill_nos($hp_id, $year, $converted_month, $status);
		$total_bill = 0; // Initialize total_bill to 0
		foreach($payable as $pay) {
			$sum = $this->List_model->get_total_bill($pay['bill_no']);
			$total_bill += $sum; // Add the calculated sum to the total_bill
		}
		$output = [
			"total_bill" => number_format($total_bill,2, '.',','),
		];
		echo json_encode($output);
	}

	function get_total_hp_paid_bill() {
		$status = 'Paid';
		$hp_id = $this->input->post('hp_id');
		$month = $this->input->post('month');
		$year = $this->input->post('year');
		$converted_month = (int)$month;
		$payable = $this->List_model->get_bill_nos($hp_id, $year, $converted_month,$status);
		$total_bill = 0; // Initialize total_bill to 0
		foreach($payable as $pay) {
			$sum = $this->List_model->get_total_bill($pay['bill_no']);
			$total_bill += $sum; // Add the calculated sum to the total_bill
		}
		$output = [
			"total_bill" => number_format($total_bill,2, '.',','),
		];
		echo json_encode($output);
	}

	function get_payment_details() {
		$details_no = $this->input->post('details_no');
		$check = $this->List_model->get_check_details($details_no);
		
		echo json_encode($check);
	}
	
	function print_billed_loa_noa() {
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$month = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
		$year = $this->myhash->hasher($this->uri->segment(7), 'decrypt');
		$converted_month = (int)$month;
		$status = 'Billed';
		$payable = $this->List_model->get_bill_nos($hp_id,$year,$converted_month,$status);

		foreach($payable as $pay){
			$data['payable'] = $this->List_model->get_monthly_net_bill($pay['bill_no']);
			$data['payment_no'] = $this->List_model->get_payment_no($pay['bill_no']);
		}
		$data['user'] = $this->session->userdata('fullname');
		$data['month'] = $converted_month;
		$data['year'] = $year;
		$data['hc_provider'] = $this->List_model->fetch_monthly_billed_noa($hp_id);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/print_summary_details');
		$this->load->view('templates/footer');
		
	}

	function fetch_all_for_payment() {
		$token = $this->security->get_csrf_hash();
		$bill = $this->List_model->get_for_payment_loa_noa();
		$data = [];
		foreach($bill as $pay){
			$row = [];
			$company_charge = '';
			$personal_charge = '';
			$remaining_mbl = '';

			if($pay['loa_id'] != ''){
				$loa_noa = $pay['loa_no'];

			}else if($pay['noa_id'] != ''){
				$loa_noa = $pay['noa_no'];
			}
			$fullname =  $pay['first_name'] . ' ' . $pay['middle_name'] . ' ' . $pay['last_name'] . ' ' . $pay['suffix'];
			$wpercent = '';
			$nwpercent = '';
			$net_bill = floatval($pay['net_bill']);
			$previous_mbl = floatval($pay['remaining_balance']);

			if($pay['loa_id'] != ''){
				$loa_noa = $pay['loa_no'];
				$loa = $this->List_model->get_loa_info($pay['loa_id']);
				if($loa['work_related'] == 'Yes'){ 
					if($loa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa['percentage'].'%  W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa['work_related'] == 'No'){
				   if($loa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			  
			}else if($pay['noa_id'] != ''){
				$loa_noa = $pay['noa_no'];
				$noa = $this->List_model->get_noa_info($pay['noa_id']);
				if($noa['work_related'] == 'Yes'){ 
					if($noa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa['percentage'].'%  W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa['work_related'] == 'No'){
				   if($noa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			}

			$payable = floatval($pay['company_charge'] + floatval($pay['cash_advance']));

			if($pay['loa_id'] != ''){
				$loa_id = $pay['loa_id'];
				$no = $pay['loa_no'];
			}else{
				$loa_id = '';
			}

			if($pay['noa_id'] != ''){
				$noa_id = $pay['noa_id'];
				$no = $pay['noa_no'];
			}else{
				$noa_id = '';
			}

			$cash_advance = number_format(floatval($pay['cash_advance']),2, '.',',');
			$hospital_bill = number_format(floatval($pay['net_bill']),2, '.',',');
			$company_charge = number_format(floatval($pay['company_charge']),2, '.',',');

			if(floatval($payable) > floatval($pay['net_bill'])){
				$action = '<a href="JavaScript:void(0)" onclick="adjustHAdvance(\''.$pay['billing_no']. '\',\''.$no.'\', \''.$fullname.'\', \''.$cash_advance.'\',\''.$hospital_bill.'\',\''.$company_charge.'\')" data-bs-toggle="tooltip" title="Adjust Healthcare Advance"><i class="mdi mdi-table-edit fs-3"></i></a>';
			}else{
				$action = '';
			}

			$row[] = $pay['billing_no'];
			$row[] = $loa_noa;
			$row[] = $fullname;
			$row[] = $pay['business_unit'];
			$row[] = number_format($pay['before_remaining_bal'],2, '.',',');
			$row[] = $wpercent. ', '.$nwpercent;
			$row[] = number_format($pay['net_bill'],2, '.',',');
			$row[] = number_format($pay['company_charge'],2, '.',',');
			$row[] = number_format($pay['cash_advance'],2, '.',',');
			$row[] = number_format($payable,2, '.',',');
			$row[] = number_format($pay['personal_charge'],2, '.',',');
			$row[] = number_format($pay['after_remaining_bal'],2, '.',',');
			$row[] = $action;
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
			"token" => $token
		];

		echo json_encode($output);
		 
	}

	function fetch_charging_billed() {
		$this->security->get_csrf_hash();
		$billing = $this->List_model->get_billed_for_charging();
		$data = [];

		foreach($billing as $bill){
			$row = [];
			$company_charge = '';
			$personal_charge = '';
			$remaining_mbl = '';
			
			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
			$wpercent = '';
			$nwpercent = '';
			$net_bill = floatval($bill['net_bill']);
			$previous_mbl = floatval($bill['remaining_balance']);

			if($bill['loa_id'] != ''){
				$loa_noa = $bill['loa_no'];
				$loa = $this->List_model->get_loa_info($bill['loa_id']);
				if($loa['work_related'] == 'Yes'){ 
					if($loa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa['percentage'].'%  W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa['work_related'] == 'No'){
				   if($loa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			   $percentage = floatval($loa['percentage']);

			if($loa['work_related'] == 'Yes'){
				if($loa['percentage'] == ''){
					$company_charge = number_format($net_bill,2, '.',',');
					$personal_charge = number_format(0,2, '.',',');
					if($net_bill >= $previous_mbl){
						$remaining_mbl = number_format(0,2, '.',',');
					}else if($net_bill < $previous_mbl){
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}
				}else if($loa['percentage'] != ''){
					
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
			}else if($loa['work_related'] == 'No'){
				if($loa['percentage'] == ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
					}else if($net_bill > $previous_mbl){
						$company_charge = number_format($previous_mbl,2, '.',',');
						$personal_charge = number_format($net_bill - $previous_mbl,2, '.',',');
						$remaining_mbl = number_format(0,2, '.',',');
					}
				}else if($loa['percentage'] != ''){
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

			}else if($bill['noa_id'] != ''){
				$loa_noa = $bill['noa_no'];
				$noa = $this->List_model->get_noa_info($bill['noa_id']);
				if($noa['work_related'] == 'Yes'){ 
					if($noa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa['percentage'].'%  W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa['work_related'] == 'No'){
				   if($noa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			   $percentage = floatval($noa['percentage']);

			if($noa['work_related'] == 'Yes'){
				if($noa['percentage'] == ''){
					$company_charge = number_format($net_bill,2, '.',',');
					$personal_charge = number_format(0,2, '.',',');
					if($net_bill >= $previous_mbl){
						$remaining_mbl = number_format(0,2, '.',',');
					}else if($net_bill < $previous_mbl){
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}
				}else if($noa['percentage'] != ''){
					
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
			}else if($noa['work_related'] == 'No'){
				if($noa['percentage'] == ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
					}else if($net_bill > $previous_mbl){
						$company_charge = number_format($previous_mbl,2, '.',',');
						$personal_charge = number_format($net_bill - $previous_mbl,2, '.',',');
						$remaining_mbl = number_format(0,2, '.',',');
					}
				}else if($noa['percentage'] != ''){
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
			}

			$row[] = $loa_noa;
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = $wpercent. ', '.$nwpercent;
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

	function fetch_for_payment_bill() {
		$token = $this->security->get_csrf_hash();
		$billing = $this->List_model->fetch_for_payment_bills();
		$data = [];
		$unique_bills = []; // initialize array to store unique bills
		foreach($billing as $bill) {
			$bill_id = $bill['payment_no'] . '_' . $bill['hp_id']; // concatenate payment_no and hp_id to create unique id
			if (!in_array($bill_id, $unique_bills)) { // check if bill with this id has already been added
				$row = [];

				$consolidated = '<span>Consolidated Bill with the Payment No. <span class="fw-bold">'.$bill['payment_no'].'</span></span>';

				if($bill['startDate'] == '0000-00-00' || $bill['endDate'] == '0000-00-00'){
				  $date = '';
				}else{
					$date = '<span>'.date('F d, Y', strtotime($bill['startDate'])).' to '.date('F d, Y', strtotime($bill['endDate'])).'</span>';
				}

				$hp_name = '<span>'.$bill['hp_name'].'</span>';

				$status = '<span class="text-center badge rounded-pill bg-info">Billed</span>'; 

				$payment_no = $this->myhash->hasher($bill['payment_no'], 'encrypt');

				$action_customs = '<a href="'.base_url().'head-office-accounting/bill/fetch_payments/'.$bill['payment_no'].'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

				$action_customs .= '<a href="javascript:void(0)" onclick="addPaymentDetails(\''.$bill['payment_no'].'\')" data-bs-toggle="tooltip" title="Add Payment Details"><i class="mdi mdi-file-document fs-2 pe-2 text-danger"></i></a>';

				$row[] = $consolidated;
				$row[] = $date;
				$row[] = $hp_name;
				$row[] = $status;
				$row[] = $action_customs;
				$data[] = $row;
				$unique_bills[] = $bill_id; // add unique bill id to array
			}
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_payment_bill() {
		$token = $this->security->get_csrf_hash();
		$payment_no = $this->input->post('payment_no');
		$billing = $this->List_model->monthly_bill_datatable($payment_no);
		$data = [];
		foreach($billing as $bill){
			$row = [];
			$wpercent = '';
			$nwpercent = '';

			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

			if($bill['loa_id'] != ''){
				$loa_noa = $bill['loa_no'];
				$loa = $this->List_model->get_loa_info($bill['loa_id']);
				if($loa['work_related'] == 'Yes'){ 
					if($loa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa['percentage'].'%  W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa['work_related'] == 'No'){
				   if($loa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			}else if($bill['noa_id'] != ''){
				$loa_noa = $bill['noa_no'];
				$noa = $this->List_model->get_noa_info($bill['noa_id']);
				if($noa['work_related'] == 'Yes'){ 
					if($noa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa['percentage'].'%  W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa['work_related'] == 'No'){
				   if($noa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			}

			$payable = floatval($bill['company_charge'] + floatval($bill['cash_advance']));

			
			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger fs-5"></i></a>';

			$row[] = $bill['billing_no'];
			$row[] = $loa_noa;
			$row[] = $fullname;
			$row[] = $bill['business_unit'];
			$row[] = number_format($bill['before_remaining_bal'],2, '.',',');
			$row[] = $wpercent .', '.$nwpercent;
			$row[] = number_format($bill['net_bill'], 2, '.', ',');
			$row[] = number_format($bill['company_charge'], 2, '.', ',');
			$row[] = number_format($bill['cash_advance'], 2, '.', ',');
			$row[] = number_format($payable, 2, '.', ',');
			$row[] = number_format($bill['personal_charge'], 2, '.', ',');
			$row[] = number_format($bill['after_remaining_bal'],2, '.',',');
			$row[] = $pdf_bill;
			$data[] = $row;

		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_paid_bills() {
		$token = $this->security->get_csrf_hash();
		$billing = $this->List_model->fetch_paid_bills();
		$data = [];
		$unique_bills = []; // initialize array to store unique bills
		foreach($billing as $bill) {
			$bill_id = $bill['payment_no'] . '_' . $bill['hp_id']; // concatenate payment_no and hp_id to create unique id
			if (!in_array($bill_id, $unique_bills)) { // check if bill with this id has already been added
				$row = [];

				$consolidated = '<span>Paid Bill with the Payment No. <span class="fw-bold">'.$bill['payment_no'].'</span></span>';

				if($bill['startDate'] == '0000-00-00' || $bill['endDate'] == '0000-00-00'){
					$date = '';
				  }else{
					  $date = '<span>'.date('F d, Y', strtotime($bill['startDate'])).' to '.date('F d, Y', strtotime($bill['endDate'])).'</span>';
				  }

				$hp_name = '<span>'.$bill['hp_name'].'</span>';

				$status = '<span class="text-center badge rounded-pill bg-success">Paid</span>'; 

				$payment_no = $this->myhash->hasher($bill['payment_no'], 'encrypt');

				$action_customs = '<a href="'.base_url().'head-office-accounting/bill/fetch_paid/'.$bill['payment_no'].'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

				$check = $this->List_model->get_check_details($bill['details_no']);

				$action_customs .= '<a href="javascript:void(0)" onclick="viewImage(\''.base_url().'uploads/paymentDetails/'.$check['supporting_file'].'\')" data-bs-toggle="tooltip" title="View Check"><i class="mdi mdi-image fs-2 pe-2 text-danger"></i></a>';

				$row[] = $consolidated;
				$row[] = $date;
				$row[] = $hp_name;
				$row[] = date('F d, Y', strtotime($bill['check_date']));
				$row[] = $status;
				$row[] = $action_customs;
				$data[] = $row;
				$unique_bills[] = $bill_id; // add unique bill id to array
			}
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_monthly_paid_bill() {
		$token = $this->security->get_csrf_hash();
		$payment_no = $this->input->post('payment_no');
		$billing = $this->List_model->monthly_bill_datatable($payment_no);
		$data = [];
		foreach($billing as $bill){
			$row = [];

			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

			if($bill['loa_id'] != ''){
				$loa_noa = $bill['loa_no'];
				$loa = $this->List_model->get_loa_info($bill['loa_id']);
				if($loa['work_related'] == 'Yes'){ 
					if($loa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa['percentage'].'%  W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa['work_related'] == 'No'){
				   if($loa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			}else if($bill['noa_id'] != ''){
				$loa_noa = $bill['noa_no'];
				$noa = $this->List_model->get_noa_info($bill['noa_id']);
				if($noa['work_related'] == 'Yes'){ 
					if($noa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa['percentage'].'%  W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa['work_related'] == 'No'){
				   if($noa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			}

			$total_paid = floatval($bill['company_charge']) + floatval($bill['cash_advance']);
			
			$status = '<span class="text-center badge rounded-pill bg-success text-white">Paid</span>'; 

			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger"></i></a>';

			$row[] = $bill['billing_no'];
			$row[] = $loa_noa;
			$row[] = $fullname;
			$row[] = $wpercent .', '.$nwpercent;
			$row[] = number_format($bill['net_bill'], 2, '.', ',');
			$row[] = number_format($bill['company_charge'], 2, '.', ',');
			$row[] = number_format($bill['cash_advance'], 2, '.', ',');
			$row[] = number_format($total_paid, 2, '.', ',');
			$row[] = number_format($bill['personal_charge'], 2, '.', ',');
			$row[] = number_format($bill['after_remaining_bal'],2, '.',',');
			$row[] = $status;
			$row[] = $pdf_bill;
			$data[] = $row;

		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function fetch_bu_charging() {
		$token = $this->security->get_csrf_hash();
		$charge = $this->List_model->get_charging_for_report();
		$data = [];
	
		$healthCardTotals = []; // Store totals for each health_card_no
	
		foreach ($charge as $bill) {
			$health_card_no = $bill['health_card_no'];
			// Check if the health_card_no already has a total calculated
			if (!isset($healthCardTotals[$health_card_no])) {
				$healthCardTotals[$health_card_no] = [
					'company_charge' => 0,
					'cash_advance' => 0,
					'total_paid' => 0,
					'fullname' => $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'],
					'business_unit' => $bill['business_unit'],
					'emp_id' => $bill['emp_id']
				];
			}
	
			// Update totals for the current health_card_no
			$healthCardTotals[$health_card_no]['company_charge'] += floatval($bill['company_charge']);
			$healthCardTotals[$health_card_no]['cash_advance'] += floatval($bill['cash_advance']);
			$healthCardTotals[$health_card_no]['total_paid'] += (floatval($bill['company_charge']) + floatval($bill['cash_advance']));
		}
	
		// Create data array with unique health_card_no and their totals
		foreach ($healthCardTotals as $health_card_no => $totals) {
			$row = [];
			$row[] = $health_card_no;
			$row[] = $totals['fullname'];
			$row[] = $totals['business_unit'];
			$row[] = number_format($totals['company_charge'], 2, '.', ',');
			$row[] = number_format($totals['cash_advance'], 2, '.', ',');
			$row[] = number_format($totals['total_paid'], 2, '.', ',');
			$row[] = '<span class="bg-danger text-white badge rounded-pill">Unpaid</span>';
			$row[] = '<a href="' . base_url() . 'head-office-accounting/charging/member/'. $totals['emp_id'] .'" data-bs-toggle="tooltip" title="View Details"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
			$data[] = $row;
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}
	

	function fetch_charging_details() {
		$token = $this->security->get_csrf_hash();
		$details = $this->List_model->get_charging_details();
		$data = [];
		
		foreach($details as $bill){
			$row = [];
			
			if($bill['loa_id'] != ''){
				$loa_noa = $bill['loa_no'];

			}else if($bill['noa_id'] != ''){
				$loa_noa = $bill['noa_no'];
			}
			$total = floatval($bill['company_charge']) + floatval($bill['cash_advance']);

			$row[] = $bill['billing_no'];
			$row[] = $loa_noa;
			$row[] = number_format($bill['company_charge'], 2, '.', ',');
			$row[] = number_format($bill['cash_advance'], 2, '.', ','); 
			$row[] = number_format($total, 2, '.', ',');
			$row[] = '<span class="bg-danger text-white badge rounded-pill">Unpaid</span>';
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}
	

	function fetch_paid_bill_report() {
		$token = $this->security->get_csrf_hash();
		$this->List_model->get_paid_for_report();
	}

	function fetch_for_pay_details() {
		$token = $this->security->get_csrf_hash();
		$payment_no = $this->input->get('payment_no');
		$billing = $this->List_model->get_billed_hp_name($payment_no);
		$sum = $this->List_model->get_total_payables($payment_no);
		
		$output = [
			'billing' => $billing,
			'total_payable' => number_format($sum,2, '.',','),
		];
		echo json_encode($output);
	}
	
	function view_forpayment_loa_noa() {
		$token = $this->security->get_csrf_hash();
		$data = $this->List_model->get_for_payment_loa_noa();

		$output = [
			'token' => $token,
			'data' => $data,
			
		];
		echo json_encode($output);
	}
	
	function get_business_units(){
		$token = $this->security->get_csrf_hash();
		$units = $this->List_model->get_business_units();
		$response = '';

		if(empty($units)){
			$response .= '<select class="chosen-select" id="charging-bu-filter" name="charging-bu-filter">';
			
			$response .= '<option value="" disabled>No Available Business Units</option>';

			$response .= '</select>';
		}else{
			// Sort the $units array by the 'business_unit' key
			array_multisort(array_column($units, 'business_unit'), SORT_ASC, $units);
	
			$response .= '<select class="chosen-select" id="charging-bu-filter" name="charging-bu-filter" data-placeholder="Choose Business Units...">';
			
			$response .= '<option value="">Choose Business Units...</option>';
			foreach ($units as $business) {
				
				$response .= '<option value="'.$business['business_unit'].'">'.$business['business_unit'].'</option>';
			}
	
			$response .= '</select>';
		}

		echo json_encode($response);
	}

	function get_business_u() {
		$token = $this->security->get_csrf_hash();
		$units = $this->List_model->get_business_units();
		$response = '';
	
		if(empty($units)){
			$response .= '<select class="chosen-select" id="billed-bu-filter" name="billed-bu-filter">';
			
			$response .= '<option value="" disabled>No Available Business Units</option>';
	
			$response .= '</select>';
		}else{
			// Sort the $units array by the 'business_unit' key
			array_multisort(array_column($units, 'business_unit'), SORT_ASC, $units);
	
			$response .= '<select class="chosen-select" id="billed-bu-filter" name="billed-bu-filter" data-placeholder="Choose Business Units...">';
			
			$response .= '<option value="">Choose Business Units...</option>';
			foreach ($units as $business) {
				
				$response .= '<option value="'.$business['business_unit'].'">'.$business['business_unit'].'</option>';
			}
	
			$response .= '</select>';
		}
	
		echo json_encode($response);
	}

	function submit_for_payment_bill() {
		$token = $this->security->get_csrf_hash();
		$user = $this->session->userdata('fullname');
		$number = 0;
		$number++;
		$payment_no = 'PMT-' . date('Ymis') . $number;
		$this->List_model->submit_forPayment_bill($payment_no);
		$inserted = $this->List_model->set_payment_no_date($payment_no,$user);
		if($inserted){
			echo json_encode([
				'token' => $token,
				'payment_no' => $payment_no,
				'status' => 'success',
				'message' => 'Successfully Submitted!'
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'error',
				'message' => 'Failed to Submit!'
			]);
		}
	}

	function submit_adjusted_advance() {
		$this->security->get_csrf_hash();
		$bill_no = $this->input->post('bill-no', TRUE);
		$new_advance = $this->input->post('new-setup-advance', TRUE);
		$inserted = $this->List_model->set_new_cash_advance($bill_no,$new_advance);

		if(!$inserted){
			echo json_encode([
				'status' => 'failed',
				'message' => 'Healthcare Advance Failed to Set up!',
			]);
		}else{
			echo json_encode([
				'status' => 'success',
				'message' => 'Healthcare Advance Set Up Successfully!',
			]);
		}
	}
	
	function print_bills($hp_id,$start_date,$end_date,$bu_filter)
	{
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');

		$hp_id =  base64_decode($hp_id);
		$start_date =  base64_decode($start_date);
		$end_date =  base64_decode($end_date);
		$bu_filter =  base64_decode($bu_filter);

	    if($bu_filter == 'none'){
			$bu = '';
		}else{
			$bu = $bu_filter;
		}
		if($hp_id == 'none'){
			$hp = '';
		}else{
			$hp = $hp_id;
		}
		if($start_date == 'none'){
			$start = '';
		}else{
			$start = $start_date;
		}
		if($end_date == 'none'){
			$end = '';
		}else{
			$end = $end_date;
		}
		$billed = $this->List_model->get_print_billed_loa_noa($hp,$start,$end,$bu);
		$hospital = $this->List_model->db_get_hp_name($hp_id);
		$pdf = new TCPDF();

		$user = $this->session->userdata('fullname');
		
		if(!empty($start) || !empty($end)){
			$formattedStart_date = date('F d, Y', strtotime($start));
			$formattedEnd_date = date('F d, Y', strtotime($end));

			$date = '<h3>From '.$formattedStart_date.' to '.$formattedEnd_date.'</h3>';
		}else{
			$date = '';
		}

		if(!empty($hospital['hp_name'])){
			$hospital = $hospital['hp_name']; 
		}else{
			$hospital = '';
		}

		if(!empty($bu)){
			$business_u = '<h3>BU: '.$bu.'</h3>';
		}else{
			$business_u = '';
		}
		
		$title =  '<h3>ALTURAS HEALTHCARE SYSTEM</h3>
            <h3>Billing Summary Details</h3>
			'.$date.'
            <h3>'.$hospital.'</h3>
			'.$business_u.'<br>';


		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Billing No</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>LOA/NOA #</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Patient Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Current MBL</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Percentage</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Hospital Bill</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Company Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcare Advance</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Total Payable</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Personal Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Remaining MBL</strong></th>
						</tr>
					</thead>';

				$totalPayableSum = 0;
				foreach($billed as $bill){
					$wpercent = '';
					$nwpercent = '';
					if($bill['loa_id'] != ''){
						$loa_noa = $bill['loa_no'];
						$loa = $this->List_model->get_loa_info($bill['loa_id']);
						if($loa['work_related'] == 'Yes'){ 
							if($loa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $loa['percentage'].'%  W-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($loa['work_related'] == 'No'){
						if($loa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $loa['percentage'].'% NonW-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}

					}else if($bill['noa_id'] != ''){
						$loa_noa = $bill['noa_no'];
						$noa = $this->List_model->get_noa_info($bill['noa_id']);
						if($noa['work_related'] == 'Yes'){ 
							if($noa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $noa['percentage'].'%  W-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($noa['work_related'] == 'No'){
						if($noa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $noa['percentage'].'% NonW-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}
					}

					$fullname =  $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
					
					$total_payable = floatval($bill['company_charge'] + $bill['cash_advance']);

		$PDFdata .= ' <tbody>
						<tr>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['billing_no'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$loa_noa.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['business_unit'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['before_remaining_bal'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$wpercent. ', '.$nwpercent.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['net_bill'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['company_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['cash_advance'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($total_payable,2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['personal_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['after_remaining_bal'],2,'.',',').'</td>
						</tr>
					</tbody>';

					$totalPayableSum += $total_payable;
				}

		$PDFdata .= '<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="fw-bold fs-5">TOTAL </td>
					<td class="fw-bold fs-5">'.number_format($totalPayableSum,2,'.',',').'</td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>';

		$PDFdata .= '</table><br><br><br>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('Billing Report');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'C', true);
		$pdf->lastPage();

		$pdfname = 'bill_'.uniqid();
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	function print_forPayment($hp_id,$start_date,$end_date,$bu_filter,$payment_no) {
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');

		$hp_id =  base64_decode($hp_id);
		$start_date =  base64_decode($start_date);
		$end_date =  base64_decode($end_date);
		$bu_filter =  base64_decode($bu_filter);
		$payment_no =  base64_decode($payment_no);

		if($bu_filter == 'none'){
			$bu = '';
		}else{
			$bu = $bu_filter;
		}
		if($hp_id == 'none'){
			$hp = '';
		}else{
			$hp = $hp_id;
		}
		if($start_date == 'none'){
			$start = '';
		}else{
			$start = $start_date;
		}
		if($end_date == 'none'){
			$end = '';
		}else{
			$end = $end_date;
		}

		$billed = $this->List_model->get_for_payment_bills($payment_no);
		$hospital = $this->List_model->db_get_hp_name($hp);
		$pdf = new TCPDF();

		if(!empty($start) || !empty($end)){
			$formattedStart_date = date('F d, Y', strtotime($start));
			$formattedEnd_date = date('F d, Y', strtotime($end));

			$date = '<h3>From '.$formattedStart_date.' to '.$formattedEnd_date.'</h3>';
		}else{
			$date = '';
		}

		if(!empty($hospital['hp_name'])){
			$hospital = $hospital['hp_name'];
		}else{
			$hospital = '';
		}

		if(!empty($bu)){
			$business_u = '<h3>BU: '.$bu.'</h3>';
		}else{
			$business_u = '';
		}

		$title =  '<h3>ALTURAS HEALTHCARE SYSTEM</h3>
            <h3>Billing Summary Details</h3>
			'.$date.'
            <h3>'.$hospital.'</h3>
			'.$business_u.'
			<h3>'.$payment_no.'</h3><br>';
			

		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Billing No</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>LOA/NOA #</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Patient Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Current MBL</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Percentage</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Hospital Bill</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Company Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcare Advance</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Total Payable</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Personal Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Remaining MBL</strong></th>
						</tr>
					</thead>';

				$totalPayableSum = 0;
				foreach($billed as $bill){
					$wpercent = '';
					$nwpercent = '';
					if($bill['loa_id'] != ''){
							$loa_noa = $bill['loa_no'];
							$loa = $this->List_model->get_loa_info($bill['loa_id']);
							if($loa['work_related'] == 'Yes'){ 
								if($loa['percentage'] == ''){
								$wpercent = '100% W-R';
								$nwpercent = '';
								}else{
								$wpercent = $loa['percentage'].'%  W-R';
								$result = 100 - floatval($loa['percentage']);
								if($loa['percentage'] == '100'){
									$nwpercent = '';
								}else{
									$nwpercent = $result.'% NonW-R';
								}
								
								}	
						}else if($loa['work_related'] == 'No'){
							if($loa['percentage'] == ''){
								$wpercent = '';
								$nwpercent = '100% NonW-R';
								}else{
								$nwpercent = $loa['percentage'].'% NonW-R';
								$result = 100 - floatval($loa['percentage']);
								if($loa['percentage'] == '100'){
									$wpercent = '';
								}else{
									$wpercent = $result.'%  W-R';
								}
								
								}
						}

						}else if($bill['noa_id'] != ''){
							$loa_noa = $bill['noa_no'];
							$noa = $this->List_model->get_noa_info($bill['noa_id']);
							if($noa['work_related'] == 'Yes'){ 
								if($noa['percentage'] == ''){
								$wpercent = '100% W-R';
								$nwpercent = '';
								}else{
								$wpercent = $noa['percentage'].'%  W-R';
								$result = 100 - floatval($noa['percentage']);
								if($noa['percentage'] == '100'){
									$nwpercent = '';
								}else{
									$nwpercent = $result.'% NonW-R';
								}
								
								}	
						}else if($noa['work_related'] == 'No'){
							if($noa['percentage'] == ''){
								$wpercent = '';
								$nwpercent = '100% NonW-R';
								}else{
								$nwpercent = $noa['percentage'].'% NonW-R';
								$result = 100 - floatval($noa['percentage']);
								if($noa['percentage'] == '100'){
									$wpercent = '';
								}else{
									$wpercent = $result.'%  W-R';
								}
								
								}
						}
					}

					$fullname =  $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
					
					$total_payable = floatval($bill['company_charge'] + $bill['cash_advance']);

		$PDFdata .= ' <tbody>
						<tr>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['billing_no'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$loa_noa.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['business_unit'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['before_remaining_bal'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$wpercent. ', '.$nwpercent.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['net_bill'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['company_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['cash_advance'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($total_payable,2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['personal_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['after_remaining_bal'],2,'.',',').'</td>
						</tr>
					</tbody>';

					$totalPayableSum += $total_payable;
				}

		$PDFdata .= '<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="fw-bold fs-5">TOTAL </td>
					<td class="fw-bold fs-5">'.number_format($totalPayableSum,2,'.',',').'</td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>';

		$PDFdata .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('For Payment Report');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'C', true);
		$pdf->lastPage();

		$pdfname = 'bill_'.uniqid();
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	function print_payment($hp_id,$start_date,$end_date) {
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');

		$hp_id =  base64_decode($hp_id);
		$start_date =  base64_decode($start_date);
		$end_date =  base64_decode($end_date);

		$formattedStart_date = date('F d, Y', strtotime($start_date));
		$formattedEnd_date = date('F d, Y', strtotime($end_date));

		if($start_date == '0000-00-00' || $end_date == '0000-00-00'){
			$date = '';
		}else{
			$date = '<h3>From '.$formattedStart_date.' to '.$formattedEnd_date.'</h3>';
		}

		$payment_no = $this->List_model->get_bill_payment_no($hp_id,$start_date,$end_date);
		$billed = $this->List_model->get_for_payment_bills($payment_no['payment_no']);
		$hospital = $this->List_model->db_get_hp_name($hp_id);
		$pdf = new TCPDF(); 

		$title =  '<h3>ALTURAS HEALTHCARE SYSTEM</h3>
            <h3>For Payment Summary Details</h3>
			'.$date.'
            <h3>'.$hospital['hp_name'].'</h3>
			<h3>'.$payment_no['payment_no'].'</h3><br>';
			

		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Billing No</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>LOA/NOA #</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Patient Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Current MBL</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Percentage</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Hospital Bill</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Company Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcare Advance</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Total Payable</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Personal Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Remaining MBL</strong></th>
						</tr>
					</thead>';

				$totalPayableSum = 0;
				foreach($billed as $bill){
					$wpercent = '';
					$nwpercent = '';
					if($bill['loa_id'] != ''){
						$loa_noa = $bill['loa_no'];
						$loa = $this->List_model->get_loa_info($bill['loa_id']);
						if($loa['work_related'] == 'Yes'){ 
							if($loa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $loa['percentage'].'%  W-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($loa['work_related'] == 'No'){
						if($loa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $loa['percentage'].'% NonW-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}

					}else if($bill['noa_id'] != ''){
						$loa_noa = $bill['noa_no'];
						$noa = $this->List_model->get_noa_info($bill['noa_id']);
						if($noa['work_related'] == 'Yes'){ 
							if($noa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $noa['percentage'].'%  W-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($noa['work_related'] == 'No'){
						if($noa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $noa['percentage'].'% NonW-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}
					}

					$fullname =  $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
					
					$total_payable = floatval($bill['company_charge'] + $bill['cash_advance']);

					$remaining_mbl = floatval($bill['remaining_balance'] - $bill['company_charge']);
					if(floatval($remaining_mbl) <= 0){
						$mbl = 0;
					}else if(floatval($remaining_mbl) > 0){
						$mbl = $remaining_mbl;
					}

		$PDFdata .= ' <tbody>
						<tr>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['billing_no'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$loa_noa.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['business_unit'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['before_remaining_bal'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$wpercent. ', '.$nwpercent.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['net_bill'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['company_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['cash_advance'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($total_payable,2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['personal_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['after_remaining_bal'],2,'.',',').'</td>
						</tr>
					</tbody>';

					$totalPayableSum += $total_payable;
				}

		$PDFdata .= '<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="fw-bold fs-5">TOTAL </td>
					<td class="fw-bold fs-5">'.number_format($totalPayableSum,2,'.',',').'</td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>';

		$PDFdata .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('For Payment Report');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'C', true);
		$pdf->lastPage();

		$pdfname = 'bill_'.uniqid();
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	function print_paid($hp_id,$start_date,$end_date) {
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');

		$hp_id =  base64_decode($hp_id);
		$start_date =  base64_decode($start_date);
		$end_date =  base64_decode($end_date);

		$formattedStart_date = date('F d, Y', strtotime($start_date));
		$formattedEnd_date = date('F d, Y', strtotime($end_date));

		if($start_date == '0000-00-00' || $end_date == '0000-00-00'){
			$date = '';
		}else{
			$date = '<h3>From '.$formattedStart_date.' to '.$formattedEnd_date.'</h3>';
		}

		$payment_no = $this->List_model->get_bill_payment_no($hp_id,$start_date,$end_date);
		$billed = $this->List_model->get_for_payment_bills($payment_no['payment_no']);
		$hospital = $this->List_model->db_get_hp_name($hp_id);
		$pdf = new TCPDF();

		$title =  '<h3>ALTURAS HEALTHCARE SYSTEM</h3>
            <h3>Paid Summary Details</h3>
			'.$date.'
			<h3>'.$hospital['hp_name'].'</h3>
			<h3>'.$payment_no['payment_no'].'</h3><br>';
			

		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Billing No</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>LOA/NOA #</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Patient Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Current MBL</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Percentage</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Hospital Bill</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Company Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcare Advance</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Total Payable</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Personal Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Remaining MBL</strong></th>
						</tr>
					</thead>';

				$totalPayableSum = 0;
				foreach($billed as $bill){
					$wpercent = '';
					$nwpercent = '';
					if($bill['loa_id'] != ''){
						$loa_noa = $bill['loa_no'];
						$loa = $this->List_model->get_loa_info($bill['loa_id']);
						if($loa['work_related'] == 'Yes'){ 
							if($loa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $loa['percentage'].'%  W-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($loa['work_related'] == 'No'){
						if($loa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $loa['percentage'].'% NonW-R';
							$result = 100 - floatval($loa['percentage']);
							if($loa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}

					}else if($bill['noa_id'] != ''){
						$loa_noa = $bill['noa_no'];
						$noa = $this->List_model->get_noa_info($bill['noa_id']);
						if($noa['work_related'] == 'Yes'){ 
							if($noa['percentage'] == ''){
							$wpercent = '100% W-R';
							$nwpercent = '';
							}else{
							$wpercent = $noa['percentage'].'%  W-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$nwpercent = '';
							}else{
								$nwpercent = $result.'% NonW-R';
							}
							
							}	
					}else if($noa['work_related'] == 'No'){
						if($noa['percentage'] == ''){
							$wpercent = '';
							$nwpercent = '100% NonW-R';
							}else{
							$nwpercent = $noa['percentage'].'% NonW-R';
							$result = 100 - floatval($noa['percentage']);
							if($noa['percentage'] == '100'){
								$wpercent = '';
							}else{
								$wpercent = $result.'%  W-R';
							}
							
							}
					}
					}

					$fullname =  $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
					
					$total_payable = floatval($bill['company_charge'] + $bill['cash_advance']);

					$remaining_mbl = floatval($bill['remaining_balance'] - $bill['company_charge']);
					if(floatval($remaining_mbl) <= 0){
						$mbl = 0;
					}else if(floatval($remaining_mbl) > 0){
						$mbl = $remaining_mbl;
					}

		$PDFdata .= ' <tbody>
						<tr>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['billing_no'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$loa_noa.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$bill['business_unit'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['before_remaining_bal'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$wpercent. ', '.$nwpercent.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['net_bill'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['company_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['cash_advance'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($total_payable,2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['personal_charge'],2,'.',',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($bill['after_remaining_bal'],2,'.',',').'</td>
						</tr>
					</tbody>';

					$totalPayableSum += $total_payable;
				}

		$PDFdata .= '<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="fw-bold fs-5">TOTAL </td>
					<td class="fw-bold fs-5">'.number_format($totalPayableSum,2,'.',',').'</td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>';

		$PDFdata .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('Paid Report');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'C', true);
		$pdf->lastPage();

		$pdfname = 'bill_'.uniqid();
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	
	
}

