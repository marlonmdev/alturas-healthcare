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
						'message' => 'NOA Request Failed'
					];
				}
				$response = [
					'status' => 'success', 
					'message' => 'NOA Request Save Successfully'
				];
			}
		}
		echo json_encode($response);
	}

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

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			/* Checking if the work_related column is empty. If it is empty, it will display the status column.
			If it is not empty, it will display the text "for Approval". */
			if($noa['work_related'] == ''){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';
			}

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-2" href="' . $view_url . '" data-bs-toggle="tooltip" title="Edit NOA" readonly><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showTagChargeType(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Tag NOA Charge Type"><i class="mdi mdi-tag-plus fs-2 text-primary"></i></a>';
			
			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$custom_actions .= '';
			}
			
			$custom_actions .= '<a href="Javascript:void(0)" onclick="cancelNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Delete NOA"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			
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

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/noa/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 text-primary pe-2"></i></a>';

			
			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
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

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

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

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-1" href="JavaScript:void(0)" onclick="viewExpiredNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="backDate(\'' . $noa_id . '\', \'' . $noa['noa_no'] . '\')" data-bs-toggle="tooltip" title="Back Date NOA"><i class="mdi mdi-update fs-2 text-cyan"></i></a>';

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

	//INITIAL BILLING====================================================
	function initial_billing() {
		$this->security->get_csrf_hash();
		$status = 'Initial';
		$list = $this->noa_model->get_datatables_ledger($status);
		$data = array();
		foreach ($list as $member){
			$row = array();
			$member_id = $this->myhash->hasher($member['emp_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'healthcare-coordinator/noa/billed/initial_billing2/' . $member_id;
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Member Profile"><i class="mdi mdi-eye fs-2 text-info me-2"></i></a>';

			$row[] = $member['noa_no'];
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
			"recordsTotal" => $this->noa_model->count_all_ledger($status),
			"recordsFiltered" => $this->noa_model->count_filtered_ledger($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	
	public function initial_billing2() {
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
 // 	function final_billing() {
	// 	$token = $this->security->get_csrf_hash(); 
	// 	$status = 'Billed';
	// 	$billing = $this->noa_model->get_final_datatables($status);

	// 	$data = array();
	// 	foreach ($billing as $bill){
	// 		$row = array();
	// 		$loa_id = $this->myhash->hasher($bill['loa_id'], 'encrypt');
	// 		$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
	// 		$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf fs-2 text-danger"></i></a>';
	// 		$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $bill['status'] . '</span></div>';

	// 		$row[] = $bill['noa_no'];
	// 		$row[] = $fullname;
	// 		$row[] = '₱' . number_format($bill['after_remaining_bal'], 2, '.', ',');
	// 		$workRelated = $bill['work_related'] . ' (' . $bill['percentage'] . '%)';
	// 		$row[] = $workRelated;
	// 		$row[] = '₱' . number_format($bill['company_charge'], 2, '.', ',');
	// 		$row[] = '₱' . number_format($bill['personal_charge'], 2, '.', ',');
	// 		$row[] = $pdf_bill;
	// 		$netBill = '₱' . number_format($bill['net_bill'], 2, '.', ',');
	// 		$row[] = $netBill;
	// 		$row[] = $custom_status;
	// 		$data[] = $row;
	// 	}

	// 	$output = [
	// 		"draw" => $_POST['draw'],
	// 		"data" => $data,
	// 	];

	// 	echo json_encode($output);
	// }

	function final_billing() {
		$token = $this->security->get_csrf_hash(); 
		$billing = $this->noa_model->get_final_datatables();

		$data = array();
		foreach ($billing as $bill){
			$row = array();
			$loa_id = $this->myhash->hasher($bill['loa_id'], 'encrypt');
			$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
			$request_date=date("F d, Y", strtotime($bill['tbl1_request_date']));

      if (empty($bill['billed_on'])) {
  			$billed_date = "No Billing Date Yet";
			}else{
  			$billed_date = date("F d, Y", strtotime($bill['billed_on']));
			}

			if (empty($bill['pdf_bill'])) {
  			$pdf_bill = 'Waiting for SOA';
			}else{
  			$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-file-pdf fs-2 text-danger"></i></a>';
			}

			if($bill['tbl1_status'] !== 'Billed'){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $bill['tbl1_status'] . '</span></div>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $bill['tbl1_status'] . '</span></div>';
			}

			$custom_actions = '';
			if($bill['tbl1_status'] == 'Billed'){
				if ($bill['guarantee_letter'] =='') {
	  			$custom_actions = '<a href="JavaScript:void(0)" onclick="GuaranteeLetter(\'' . $bill['billing_id'] . '\')" data-bs-toggle="tooltip" title="Guarantee Letter"><i class="mdi mdi-reply fs-2 text-info"></i></a>';
	  		}else{
					$custom_actions .= '<i class="mdi mdi-reply fs-2 text-secondary" title="Guarantee Letter Already Sent"></i>';
				}
			}else if($bill['tbl1_status'] == 'Approved'){
				$custom_actions .= '<a href="' . base_url() . 'healthcare-coordinator/noa/requests-list/approved/" data-bs-toggle="tooltip" title="Back to NOA"><i class="mdi mdi-pen fs-2 text-danger"></i></a>';
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
			$row[] = $pdf_bill;
			$netBill = '₱' . number_format($bill['net_bill'], 2, '.', ',');
			$row[] = $netBill;
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
			'status' => 'Billed',
			'total_payable' => $total_payable,
			'added_on' => date('Y-m-d'),
			'added_by' => $this->session->userdata('fullname'),
		];
		$inserted = $this->noa_model->insert_for_payment_consolidated($data);

		if($inserted){
			$this->noa_model->update_initial_billing($initial_status);
			$this->noa_model->update_monthly_payable($initial_status);
			$this->noa_model->update_noa_requests($initial_status);
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
		$for_payment = $this->noa_model->fetch_for_payment_bill($status);
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
			$label_custom = '<span class="fw-bold fs-5">Consolidated Billing for the Month of '.$month.', '.$bill['year'].'</span>';
			$hospital_custom = '<span class="fw-bold fs-5">'.$bill['hp_name'].'</span>';
			$status_custom = '<span class="badge rounded-pill bg-success text-white">'.$bill['status'].'</span>';
			$action_customs = '<a href="'.base_url().'healthcare-coordinator/bill/billed-noa/fetch-payable/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Hospital Bill"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';
			$action_customs .= '<a href="'.base_url().'healthcare-coordinator/bill/billed-noa/charging/'.$bill['bill_no'].'" data-bs-toggle="tooltip" title="View Charging"><i class="mdi mdi-file-document-box fs-2 text-danger"></i></a>';

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
			
			$row[] = $bill['noa_no'];
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
		$data['row'] = $this->noa_model->db_get_noa_info($noa_id);
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
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

	function update_noa_request() {
		$this->security->get_csrf_hash();
		$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
		$this->form_validation->set_rules('admission-date', 'Request Date of  Availment', 'required');
		$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'hospital_name_error' => form_error('hospital-name'),
				'admission_date_error' => form_error('admission-date'),
				'chief_complaint_error' => form_error('chief-complaint'),
			];
		} else {
			$post_data = [
				'hospital_id' => $inputPost['hospital-name'],
				'admission_date' => $inputPost['admission-date'],
				'chief_complaint' => strip_tags($inputPost['chief-complaint']),
			];
			$saved = $this->noa_model->db_update_noa_request($noa_id, $post_data);
			if ($saved) {
				$response = [
					'status' => 'success', 
					'message' => 'NOA Request Updated Successfully'
				];
			} else {
				$response = [
					'status' => 'save-error', 
					'message' => 'NOA Request Update Failed'
				];
			}
		}
		echo json_encode($response);
	}

	function cancel_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');

		$deleted = $this->noa_model->db_cancel_noa($noa_id);
		if ($deleted) {
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'NOA Request Cancelled Successfully'
			];
		} else {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'NOA Request Cancellation Failed'
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

			$uploaded_files = array();
			$error_occurred = FALSE;

			// Define the upload paths for each file
			$file_paths = array(
				'spot-report' => './uploads/spot_reports/',
				'incident-report' => './uploads/incident_reports/',
			);

			// Iterate over each file input and perform the upload
			$file_inputs = array('spot-report', 'incident-report');
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
		} else {
			$post_data = [
				'status'          => 'Approved',
				'expiration_date' => date('Y-m-d', strtotime($expiry_date)),
			];

			$updated = $this->noa_model->db_update_noa_request($noa_id, $post_data);

			if (!$updated) {
				$response = [
					'status'  => 'save-error', 
					'message' => 'NOA Request BackDate Failed'
				];
			}
			$response = [
				'status'  => 'success', 
				'message' => 'NOA Request BackDated Successfully'
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

}
