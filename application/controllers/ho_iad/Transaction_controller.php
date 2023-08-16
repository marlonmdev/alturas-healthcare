<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_controller extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('ho_iad/transaction_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'head-office-iad') {
			redirect(base_url());
		}
  }
	function redirectBack() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			header('location:' . $_SERVER['HTTP_REFERER']);
		}else{
			header('location:http://' . $_SERVER['SERVER_NAME']);
		}
		exit();
	}

	//PAYMENT HISTORY==================================================
	function search() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/search');
		$this->load->view('templates/footer');
	}

	function search_by_id() {
		$this->security->get_csrf_hash();
		$emp_no = $this->security->xss_clean($this->input->post('employee_id'));
		$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$member = $this->transaction_model->get_member_by_id($emp_no);

		if (!$member) {
			$arr = ['error' => 'No Record Found!'];
			$this->session->set_flashdata($arr);
			$this->redirectBack();
		} else {
			$data['member'] = $member;
			$data['user_role'] = $this->session->userdata('user_role');
			$data['member_mbl'] = $member_mbl = $this->transaction_model->get_member_mbl($member['emp_id']);
			$data['hp_name'] = $hp_name = $this->transaction_model->get_healthcare_provider($hcare_provider_id);
			$data['billing'] = $this->transaction_model->get_billing_status($member['emp_id'], $hcare_provider_id);
			$data['noa_requests'] = $this->transaction_model->get_member_noa($member['emp_id'], $hcare_provider_id);

			/* This is checking if the image file exists in the directory. */
			$file_path = './uploads/profile_pics/' . $member['photo'];
			$data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

			$this->session->set_userdata([
				'b_member_info'    => $member,
				'b_member_mbl'     => $member_mbl['max_benefit_limit'],
				'b_member_bal'     => $member_mbl['remaining_balance'],
				'b_hcare_provider' => $hp_name,
				'b_healthcard_no'  => $member['health_card_no'],
			]);

			$this->load->view('templates/header', $data);
			$this->load->view('ho_iad_panel/transaction/summary_of_billing');
			$this->load->view('templates/footer');
		}
	}

	function search_by_healthcard() {
		$this->security->get_csrf_hash();
		$healthcard = $this->security->xss_clean($this->input->post('employee_healthcard'));
		$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$member = $this->transaction_model->get_member_by_healthcard($healthcard);

		if (!$member) {
			$arr = ['error' => 'No Record Found!'];
			$this->session->set_flashdata($arr);
			$this->redirectBack();
		} else {
			$data['member'] = $member;
			$data['user_role'] = $this->session->userdata('user_role');
			$data['member_mbl'] = $member_mbl = $this->transaction_model->get_member_mbl($member['emp_id']);
			$data['hp_name'] = $hp_name = $this->transaction_model->get_healthcare_provider($hcare_provider_id);
			$data['billing'] = $this->transaction_model->get_billing_status($member['emp_id'], $hcare_provider_id);
			$data['noa_requests'] = $this->transaction_model->get_member_noa($member['emp_id'], $hcare_provider_id);

			/* This is checking if the image file exists in the directory. */
			$file_path = './uploads/profile_pics/' . $member['photo'];
			$data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

			$this->session->set_userdata([
				'b_member_info'    => $member,
				'b_member_mbl'     => $member_mbl['max_benefit_limit'],
				'b_member_bal'     => $member_mbl['remaining_balance'],
				'b_hcare_provider' => $hp_name,
				'b_healthcard_no'  => $member['health_card_no'],
			]);

			$this->load->view('templates/header', $data);
			$this->load->view('ho_iad_panel/transaction/summary_of_billing');
			$this->load->view('templates/footer');
		}
	}

	function search_by_name() {
		$this->security->get_csrf_hash();
		$first_name = $this->security->xss_clean($this->input->post('first_name'));
		$middle_name = $this->security->xss_clean($this->input->post('middle_name'));
		$last_name = $this->security->xss_clean($this->input->post('last_name'));
		$hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
		$member = $this->transaction_model->get_member_by_name($first_name,$middle_name, $last_name);

		if (!$member) {
			$arr = ['error' => 'No Record Found!'];
			$this->session->set_flashdata($arr);
			$this->redirectBack();
		}else {
			$data['member'] = $member;
			$data['user_role'] = $this->session->userdata('user_role');
			$data['member_mbl'] = $member_mbl = $this->transaction_model->get_member_mbl($member['emp_id']);
			$data['hp_name'] = $hp_name = $this->transaction_model->get_healthcare_provider($hcare_provider_id);
			$data['billing'] = $this->transaction_model->get_billing_status($member['emp_id'], $hcare_provider_id);
			$data['noa_requests'] = $this->transaction_model->get_member_noa($member['emp_id'], $hcare_provider_id);

			/* This is checking if the image file exists in the directory. */
			$file_path = './uploads/profile_pics/' . $member['photo'];
			$data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

			$this->session->set_userdata([
				'b_member_info'    => $member,
				'b_member_mbl'     => $member_mbl['max_benefit_limit'],
				'b_member_bal'     => $member_mbl['remaining_balance'],
				'b_hcare_provider' => $hp_name,
				'b_healthcard_no'  => $member['health_card_no'],
			]);

			$this->load->view('templates/header', $data);
			$this->load->view('ho_iad_panel/transaction/summary_of_billing');
			$this->load->view('templates/footer');
		}
	}

	function view_receipt(){
		$id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$type = $this->uri->segment(3);

		$data['bill'] = $bill = $this->transaction_model->get_billing_info($id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['mbl'] = $this->transaction_model->get_member_mbl($bill['emp_id']);
		$data['services'] = $this->transaction_model->get_billing_services($bill['billing_no']);
		$data['deductions'] = $this->transaction_model->get_billing_deductions($bill['billing_no']);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/billing_receipt');
		$this->load->view('templates/footer');
	}

	function view_payment_details(){
		$billing_id = $this->uri->segment(4);
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$billing = $this->transaction_model->get_billing_by_id($billing_id);
		$payment_details = $this->transaction_model->get_paymentdetails($billing['details_no']);

		if($billing['loa_id'] != ''){
			$request_type = 'LOA';
		}else{
			$request_type = 'NOA';
		}

		$response = [
			'token'          => $token,
			'payment_no'     => $billing['payment_no'],
			'account_no'     => $payment_details['acc_number'],
			'account_name'   => $payment_details['acc_name'],
			'check_no'     	 => $payment_details['check_num'],
			'check_date'     => $payment_details['check_date'],
			'bank'     	 		 => $payment_details['bank'],
			'amount_paid'    => number_format($payment_details['amount_paid'],2,'.',','),
			'type_request'     => $request_type,
		];

		echo json_encode($response);
	}
	//END ===============================================================

	//MEMBERS ==================================================
	function members(){
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/member/member');
		$this->load->view('templates/footer');
	}
	
	function fetch_all_members(){
		$this->security->get_csrf_hash();
		$approval_status = 'Paid';
		$list = $this->transaction_model->get_datatables($approval_status);
		$data = [];
		foreach ($list as $member){
			$row = [];
			$member_id = $this->myhash->hasher($member['member_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'head-office-iad/transaction/view_information/' . $member_id;
			$view_url2 = base_url() . 'head-office-iad/transaction/search/';
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Member Profile"><i class="mdi mdi-account-card-details fs-2 text-info me-2"></i></a>';
			$custom_actions .= '<a href="' . $view_url2 . '"  data-bs-toggle="tooltip" title="Search Payment Record"><i class="mdi mdi-magnify fs-2 text-info"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $member['emp_no'];
			$row[] = $full_name;
			$row[] = $member['emp_type'];
			$row[] = $member['current_status'];
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->transaction_model->count_all($approval_status),
			"recordsFiltered" => $this->transaction_model->count_filtered($approval_status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function view_information(){
		$member_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $member = $this->transaction_model->db_get_member_details($member_id);
		$data['mbl'] = $this->transaction_model->db_get_member_mbl($member['emp_id']);
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/member/member_profile');
		$this->load->view('templates/footer');
	}
	//END ===============================================================

	//ACCOUNT SETTINGS ==================================================
	function account_settings() {
		$user_id = $this->session->userdata('user_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->transaction_model->get_user_account_details($user_id);
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/dashboard/account_setting');
		$this->load->view('templates/footer');
	}
	function update_password() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$user_id = $this->session->userdata('user_id');
		$this->form_validation->set_rules('current-password', 'Current Password', 'trim|required|callback_check_current_password');
		$this->form_validation->set_rules('new-password', 'New Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm-password', 'Confirm Password', 'trim|required|matches[new-password]');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'current_password_error' => form_error('current-password'),
				'new_password_error' => form_error('new-password'),
				'confirm_password_error' => form_error('confirm-password'),
			];
		} else {
			$post_data = [
				'password' => $this->_hash_password($input_post['confirm-password']),
				'updated_on' =>  date("Y-m-d"),
				'updated_by' => $this->session->userdata('fullname'),
			];
			$updated = $this->transaction_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = ['status' => 'save-error', 'message' => 'Password Update Failed'];
			}
			$response = ['status' => 'success', 'message' => 'Password Updated Successfully'];
		}
		echo json_encode($response);
	}

	function check_current_password($current_password) {
		$user_id = $this->session->userdata('user_id');
		$row = $this->transaction_model->get_user_account_details($user_id);
		$db_password = $row['password'];
		$match = $this->_verify_hash($current_password, $db_password);
		if (!$match) {
			$this->form_validation->set_message('check_current_password', 'Current password is incorrect');
			return false;
		}
		return true;
	}
	private function _hash_password($password) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		return $hashed_password;
	}
	private function _verify_hash($plain_text_str, $hashed_string) {
		$result = password_verify($plain_text_str, $hashed_string);
		return $result;
	}

	function update_username() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$user_id = $this->session->userdata('user_id');
		$this->form_validation->set_rules('current-username', 'Current Username', 'trim|required|callback_check_current_username');
		$this->form_validation->set_rules('new-username', 'New Username', 'trim|required|min_length[6]|callback_check_username_exist');
		$this->form_validation->set_rules('confirm-username', 'Confirm Username', 'trim|required|matches[new-username]');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'current_username_error' => form_error('current-username'),
				'new_username_error' => form_error('new-username'),
				'confirm_username_error' => form_error('confirm-username'),
			];
		} else {
			$post_data = [
				'username' => $input_post['confirm-username'],
				'updated_on' =>  date("Y-m-d"),
				'updated_by' => $this->session->userdata('fullname'),
			];
			$updated = $this->transaction_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = ['status' => 'save-error', 'message' => 'Username Update Failed'];
			}
			$response = ['status' => 'success', 'message' => 'Username Updated Successfully'];
		}
		echo json_encode($response);
	}
	function check_current_username($current_username) {
		$user_id = $this->session->userdata('user_id');
		$row = $this->transaction_model->get_user_account_details($user_id);
		$db_username = $row['username'];
		$match = $current_username === $db_username ? true : false;
		if (!$match) {
			$this->form_validation->set_message('check_current_username', 'Current username is incorrect');
			return false;
		}
		return true;
	}
	function check_username_exist($new_username) {
		$exists = $this->transaction_model->db_check_username($new_username);
		if (!$exists) {
			return true;
		}else{
			$this->form_validation->set_message('check_username_exist', 'Username already taken, Please try Another!');
			return false;
		}
	}

	function fetch_for_payment_bill() {
		$token = $this->security->get_csrf_hash();
		$billing = $this->transaction_model->fetch_for_payment_bills();
		$data = [];
		$unique_bills = []; // initialize array to store unique bills
		foreach($billing as $bill) {
			$bill_id = $bill['payment_no'] . '_' . $bill['hp_id']; // concatenate payment_no and hp_id to create unique id
			if (!in_array($bill_id, $unique_bills)) { // check if bill with this id has already been added
				$row = [];

				$consolidated = '<span>Consolidated Bill with the Payment No. <span class="fw-bold">'.$bill['payment_no'].'</span></span>';

				$date = '<span>'.date('F d, Y', strtotime($bill['startDate'])).' to '.date('F d, Y', strtotime($bill['endDate'])).'</span>';

				if($bill['billing_type'] == 'PDF Billing'){
					$hp_name = '<span>'.$bill['hp_name'].'</span>';
				}else if($bill['billing_type'] == 'Reimburse'){
					$hp_name = '<span>Non-Affiliated Hospitals</span>';
				}

				$status = '<span class="text-center badge rounded-pill bg-warning">Billed</span>'; 
				
				$payment_id = $this->myhash->hasher($bill['bill_id'], 'encrypt');

				$action_customs = '<a href="'.base_url().'head-office-iad/biling/for-audit-list/'.$payment_id.'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

				// $action_customs .= '<a href="javascript:void(0)" onclick="tagDoneAudit(\''.$bill['payment_no'].'\')" data-bs-toggle="tooltip" title="Audited"><i class="mdi mdi-checkbox-marked-circle-outline fs-2 pe-2 text-danger"></i></a>';

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

	function fetch_audited_bill() {
		$token = $this->security->get_csrf_hash();
		$billing = $this->transaction_model->fetch_audited_bills();
		$data = [];
		$unique_bills = []; // initialize array to store unique bills
		foreach($billing as $bill) {
			$bill_id = $bill['payment_no'] . '_' . $bill['hp_id']; // concatenate payment_no and hp_id to create unique id
			if (!in_array($bill_id, $unique_bills)) { // check if bill with this id has already been added
				$row = [];

				$consolidated = '<span>Consolidated Bill with the Payment No. <span class="fw-bold">'.$bill['payment_no'].'</span></span>';

				$date = '<span>'.date('F d, Y', strtotime($bill['startDate'])).' to '.date('F d, Y', strtotime($bill['endDate'])).'</span>';

				if($bill['billing_type'] == 'PDF Billing'){
					$hp_name = '<span>'.$bill['hp_name'].'</span>';
				}else if($bill['billing_type'] == 'Reimburse'){
					$hp_name = '<span>Non-Affiliated Hospitals</span>';
				}

				$status = '<span class="text-center badge rounded-pill bg-info">Audited</span>'; 

				$payment_id = $this->myhash->hasher($bill['bill_id'], 'encrypt');

				$action_customs = '<a href="'.base_url().'head-office-iad/biling/audited-list/'.$payment_id.'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

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

	function fetch_paid_bill() {
		$token = $this->security->get_csrf_hash();
		$billing = $this->transaction_model->fetch_paid_bills();
		$data = [];
		$unique_bills = []; // initialize array to store unique bills
		foreach($billing as $bill) {
			$bill_id = $bill['payment_no'] . '_' . $bill['hp_id']; // concatenate payment_no and hp_id to create unique id
			if (!in_array($bill_id, $unique_bills)) { // check if bill with this id has already been added
				$row = [];

				$consolidated = '<span>Consolidated Paid Bill with the Payment No. <span class="fw-bold">'.$bill['payment_no'].'</span></span>';

				$date = '<span>'.date('F d, Y', strtotime($bill['startDate'])).' to '.date('F d, Y', strtotime($bill['endDate'])).'</span>';


				$status = '<span class="text-center badge rounded-pill bg-success">Paid</span>'; 
				
				$payment_id = $this->myhash->hasher($bill['bill_id'], 'encrypt');

				$action_customs = '<a href="'.base_url().'head-office-iad/biling/paid-list/'.$payment_id.'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a>';

				$check = $this->transaction_model->get_paymentdetails($bill['details_no']);
				
				if($bill['billing_type'] == 'PDF Billing'){
					$hp_name = '<span>'.$bill['hp_name'].'</span>';

					$action_customs .= '<a href="JavaScript:void(0)" onclick="viewCheckVoucher(\''.$check['supporting_file'].'\')" data-bs-toggle="tooltip" title="View Check Voucher"><i class="mdi mdi-file-pdf fs-2 pe-2 text-danger"></i></a>';
					
				}else if($bill['billing_type'] == 'Reimburse'){
					$hp_name = '<span>Non-Affiliated Hospitals</span>';

					$action_customs .= '';
				}

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

	function fetch_paid_bills() {
		$token = $this->security->get_csrf_hash();
		$payment_no = $this->input->post('payment_no');
		$billing = $this->transaction_model->monthly_paid_bill_datatable($payment_no);
		$data = [];
		$number = 1;
		foreach($billing as $bill){
			if($bill['company_charge'] && $bill['cash_advance'] != ''){
				$row = [];
				$wpercent = '';
				$nwpercent = '';
				$billing_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
	
				$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
	
				if($bill['loa_id'] != ''){
					$loa_noa = '<a href="JavaScript:void(0)" class="btn text-info text-decoration-underline" onclick="viewLOANOAdetails(\''.$billing_id.'\')" data-bs-toggle="tooltip">'.$bill['loa_no'].'</a>';
	
					$loa = $this->transaction_model->get_loa_info($bill['loa_id']);
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
					$loa_noa = '<a href="JavaScript:void(0)" class="btn text-info text-decoration-underline" onclick="viewLOANOAdetails(\''.$billing_id.'\')" data-bs-toggle="tooltip">'.$bill['noa_no'].'</a>';
					
					$noa = $this->transaction_model->get_noa_info($bill['noa_id']);
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

				
				if($bill['billing_type'] == 'PDF Billing'){
					$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger fs-3"></i></a>';

					$action_custom = '';

				}else if($bill['billing_type'] == 'Reimburse'){
					$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBillReimburse(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger fs-3"></i></a>';

					$check = $this->transaction_model->get_paymentdetails($bill['details_no']);

					$action_custom = '<a href="JavaScript:void(0)" onclick="viewCVReimburse(\'' . $check['supporting_file'] . '\')" data-bs-toggle="tooltip" title="View Check Voucher"><i class="mdi mdi-file-pdf text-danger fs-3"></i></a>';
				}
				
	
				$row[] = $number++;
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
				$row[] = $action_custom;
				$data[] = $row;
	
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
		$billing = $this->transaction_model->monthly_bill_datatable($payment_no);
		$data = [];
		$number = 1;
		foreach($billing as $bill){
			if($bill['company_charge'] && $bill['cash_advance'] != ''){
				$row = [];
				$wpercent = '';
				$nwpercent = '';
				$billing_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
	
				$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];
	
				if($bill['loa_id'] != ''){
					$loa_noa = '<a href="JavaScript:void(0)" class="btn text-info text-decoration-underline" onclick="viewLOANOAdetails(\''.$billing_id.'\')" data-bs-toggle="tooltip">'.$bill['loa_no'].'</a>';
	
					$loa = $this->transaction_model->get_loa_info($bill['loa_id']);
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
					$loa_noa = '<a href="JavaScript:void(0)" class="btn text-info text-decoration-underline" onclick="viewLOANOAdetails(\''.$billing_id.'\')" data-bs-toggle="tooltip">'.$bill['noa_no'].'</a>';
					
					$noa = $this->transaction_model->get_noa_info($bill['noa_id']);
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
	
				if($bill['billing_type'] == 'PDF Billing'){
					$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger fs-3"></i></a>';

				}else if($bill['billing_type'] == 'Reimburse'){
					$pdf_bill = '<a href="JavaScript:void(0)" onclick="viewPDFBillReimburse(\'' . $bill['pdf_bill'] . '\' , \''. $bill['noa_no'] .'\', \''. $bill['loa_no'] .'\')" data-bs-toggle="tooltip" title="View Hospital SOA"><i class="mdi mdi-magnify text-danger fs-3"></i></a>';
					
				}

				if(!empty($bill['details_no'])){
					$check = $this->transaction_model->get_paymentdetails($bill['details_no']);

					$action_custom = '<a href="JavaScript:void(0)" onclick="viewCVReimburse(\'' . $check['supporting_file'] . '\')" data-bs-toggle="tooltip" title="View Check Voucher"><i class="mdi mdi-file-pdf text-danger fs-3"></i></a>';
				}else{
					$action_custom = '';
				}
				

				$row[] = $number++;
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
				$row[] = $action_custom;
				$data[] = $row;
	
			}
			
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];

		echo json_encode($output);
	}

	function submit_audited() {
		$token = $this->security->get_csrf_hash();
		$user = $this->session->userdata('fullname');
		$audited = $this->transaction_model->submit_audited_bill($user);

		if(!$audited){
			echo json_encode([
				'token' => $token,
				'status' => 'error',
				'message' => 'Failed to Submit!',
			]);
		}else{
			echo json_encode([
				'token' => $token,
				'status' => 'success',
				'message' => 'Submitted Successfully!',
			]);
		}
	}

	function fetch_loa_noa_details() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data = $this->transaction_model->get_loa_noa_billing_by_id($billing_id);

		if($data['loa_id'] != ''){
			$request_type = $data['loa_request_type'];
			$loa = $this->transaction_model->get_loa_info($data['loa_id']);
			$doctor = $this->transaction_model->get_approved_by_doctor($loa['approved_by']);
			$approved_by = $doctor['doctor_name'];
			$requested_on = date('F d, Y',strtotime($loa['request_date']));
			$approved_on = date('F d, Y',strtotime($loa['approved_on']));
			$hospitalized_on = date('F d, Y',strtotime($loa['emerg_date']));
			$loa_noa_no = $loa['loa_no'];

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
		}else if($data['noa_id'] != ''){
			$noa = $this->transaction_model->get_noa_info($data['noa_id']);
			$doctor = $this->transaction_model->get_approved_by_doctor($noa['approved_by']);
			$approved_by = $doctor['doctor_name'];
			$requested_on = date('F d, Y',strtotime($noa['request_date']));
			$approved_on = date('F d, Y',strtotime($noa['approved_on']));
			$admission_date = date('F d, Y',strtotime($noa['admission_date']));
			$loa_noa_no = $noa['noa_no'];
			$request_type = 'NOA';

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

		$cost_types = $this->transaction_model->db_get_cost_types();
		// get selected medical services
		$selected_cost_types = explode(';', $data['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-info">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'loa_noa_no' => $loa_noa_no,
			'fullname' => $data['first_name'] .' '. $data['middle_name'] .' '. $data['last_name'] .' '. $data['suffix'],
			'business_unit' => $data['business_unit'],
			'hp_name' => $data['hp_name'],
			'requested_on' => $requested_on,
			'approved_on' =>  $approved_on,
			'approved_by' => $approved_by,
			'request_type' => $request_type,
			'percentage' => $wpercent .', '.$nwpercent,
			'services' => $med_serv,
			'admission_date' => isset($admission_date) ? $admission_date : '',
			'hospitalized_date' => isset($hospitalized_on) ? $hospitalized_on : '',
			'billed_on' =>  date('F d, Y',strtotime($data['billed_on'])),
			'billed_by' => $data['billed_by'],
			'billing_no' => $data['billing_no'],
			'net_bill' => number_format($data['net_bill'],2,'.',','),
			'personal_charge' => number_format($data['personal_charge'],2,'.',','),
			'company_charge' => number_format($data['company_charge'],2,'.',','),
			'cash_advance' => number_format($data['cash_advance'],2,'.',','),
			'total_payable' => number_format(floatval($data['cash_advance'] + $data['company_charge']),2,'.',','),
			'before_remaining_bal' => number_format($data['before_remaining_bal'],2,'.',','),
			'after_remaining_bal' => number_format($data['after_remaining_bal'],2,'.',','),
		];
		
		echo json_encode($response);
	}
	
	function fetch_bu_charges() {
		$token = $this->security->get_csrf_hash();
		$charge = $this->transaction_model->get_charging_for_report();
		$data = [];
	
		$healthCardTotals = []; // Store totals for each health_card_no
	
		foreach ($charge as $bill) {
			$row = [];
			if ($bill['company_charge'] != '' && $bill['cash_advance'] != '') {

				$fullname = $bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].' '.$bill['suffix'];

				if(!empty($bill['loa_id'])){
					$loa = $this->transaction_model->get_loa_info($bill['loa_id']);
					$loa_noa_no = $loa['loa_no'];
					$request_date = date('m/d/Y',strtotime($loa['request_date']));
				}else if(!empty($bill['noa_id'])){
					$noa = $this->transaction_model->get_noa_info($bill['noa_id']);
					$loa_noa_no = $noa['noa_no'];
					$request_date = date('m/d/Y',strtotime($noa['request_date']));
				}
				$row[] = $request_date;
				$row[] = $bill['health_card_no'];
				$row[] = $fullname;
				$row[] = $bill['business_unit'];
				$row[] = $loa_noa_no;
				$row[] = $bill['payment_no'];
				$row[] = number_format($bill['company_charge'], 2, '.', ',');
				$row[] = number_format($bill['cash_advance'], 2, '.', ',');
				$row[] = number_format(floatval($bill['company_charge'] + $bill['cash_advance']), 2, '.', ',');
				$row[] = '<a class="fw-bold text-end"href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewChargeDetails(\''.$bill['billing_id'].'\')">View Details</a>';
				$data[] = $row;

			}
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}
	
	function fetch_bu_receivables() {
		$token = $this->security->get_csrf_hash();
		$bu_status = 'Receivable';
		$receivables = $this->transaction_model->fetch_receivables_bu($bu_status);
		$data = [];
		$type = 'unpaid';
		$totals = [
			'company_charge' => 0,
			'cash_advance' => 0,
		];
		$uniqueEntries = [];

		foreach ($receivables as $receivable) {
			$chargingKey = $receivable['bu_charging_no'];
			$charging = $this->transaction_model->get_billing_id($receivable['bu_charging_no']);
			$IDs = array_column($charging, 'billing_id');
			$encodedArray = urlencode(serialize($IDs));

			if (!isset($uniqueEntries[$chargingKey])) {
				$uniqueEntries[$chargingKey] = [
					'company_charge' => 0,
					'cash_advance' => 0,
					'count' => 0,
					'action' => '<a href="'.base_url().'head-office-iad/charging/bu-receivables/fetch/'.$type.'/'.$encodedArray.'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a> '
				];
			}
	
			if ($receivable['company_charge'] && $receivable['cash_advance'] != '') {
				$uniqueEntries[$chargingKey]['company_charge'] += floatval($receivable['company_charge']);
				$uniqueEntries[$chargingKey]['cash_advance'] += floatval($receivable['cash_advance']);
				$uniqueEntries[$chargingKey]['count']++;
			}
		}
	
		foreach ($uniqueEntries as $key => $entry) {
			$row = [];
			$row[] = '<span class="text-info fw-bold">'.$key.'</span>';
			$row[] = date('F d, Y', strtotime($receivable['bu_generated_on']));
			$row[] = $receivable['business_unit'];
			$row[] = number_format($entry['company_charge'], 2, '.', ',');
			$row[] = number_format($entry['cash_advance'], 2, '.', ',');
			$row[] = number_format(floatval($entry['cash_advance'] + $entry['company_charge']), 2, '.', ',');
			$row[] = '<span class="bg-warning text-white badge rounded-pill">'.$receivable['bu_charging_status'].'</span>';
			$row[] = $entry['action'];
			$data[] = $row;
	
			$totals['company_charge'] += $entry['company_charge'];
			$totals['cash_advance'] += $entry['cash_advance'];
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
			"totals" => $totals,
		];
	
		echo json_encode($output);
	}

	function view_bu_receivables_details() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['type'] = $this->uri->segment(5);
		$encodedArray = $this->uri->segment(6);
		$billing_id = unserialize(urldecode($encodedArray));
		$details = $this->transaction_model->get_bu_charges_info($billing_id);

		$table = '<div class="table-responsive"><table class="table table-stripped table-responsive">';
		$table .= ' <thead>
						<tr>
							<th class="fw-bold">Healthcard No.</th>
							<th class="fw-bold">Employee`s Name</th>
							<th class="fw-bold">Request Date</th>
							<th class="fw-bold">LOA/NOA No.</th>
							<th class="fw-bold">Payment No.</th>
							<th class="fw-bold text-end">Company Charge</th>
							<th class="fw-bold text-end">Healthcare Advance</th>
							<th class="fw-bold text-end">Total Charge</th>
							<th class="fw-bold text-end"></th>
						</tr>
					</thead>';

		$previousHealthCardNo = '';
		$previousFullName = '';
		$totalPayableSum = 0;
		
		foreach ($details as $charge) {
			if ($charge['company_charge'] && $charge['cash_advance'] != '') {

				$currentHealthCardNo = $charge['health_card_no'];
				$currentFullName = $charge['first_name'] . ' ' . $charge['middle_name'] . ' ' . $charge['last_name'] . ' ' . $charge['suffix'];
		
				$total_payable = floatval($charge['company_charge'] + $charge['cash_advance']);
		
				$healthCardNo = ($currentHealthCardNo !== $previousHealthCardNo) ? $currentHealthCardNo : '';
				$fullName = ($currentFullName !== $previousFullName) ? $currentFullName : '';
				if($charge['loa_id'] != ''){
					$loa = $this->transaction_model->get_loa_info($charge['loa_id']);
					$loa_noa_no = $loa['loa_no'];
					$request_date = date('m/d/Y',strtotime($loa['request_date']));
				}else if($charge['noa_id'] != ''){
					$noa = $this->transaction_model->get_noa_info($charge['noa_id']);
					$loa_noa_no = $noa['noa_no'];
					$request_date = date('m/d/Y',strtotime($noa['request_date']));
				}
				$table .= ' <tbody>
								<tr>
									<td class="fs-6">' . $healthCardNo . '</td>
									<td class="fs-6">' . $fullName . '</td>
									<td class="fs-6">' . $request_date . '</td>
									<td class="fs-6">' . $loa_noa_no . '</td>
									<td class="fs-6">' . $charge['payment_no'] . '</td>
									<td class="fs-6 text-end">' . number_format($charge['company_charge'],2,'.',',') . '</td>
									<td class="fs-6 text-end">' . number_format($charge['cash_advance'],2,'.',',') . '</td>
									<td class="fs-6 text-end">' . number_format($total_payable, 2, '.', ',') . '</td>
									<td class="fs-6 text-end"><a class="fw-bold"href="JavaScript:void(0)" data-bs-toggle="tooltip" onclick="viewChargeDetails(\''.$charge['billing_id'].'\')">View Details</a></td>
								</tr>
							</tbody>';
		
				$previousHealthCardNo = $currentHealthCardNo;
				$previousFullName = $currentFullName;

				$totalPayableSum += $total_payable;
				$data['charging_no'] = $charge['bu_charging_no'];
				$data['business_unit'] = $charge['business_unit'];

			}
		}
		
		$table .= '<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="fw-bold text-end">TOTAL</td>
					<td class="fw-bold text-end">'.number_format($totalPayableSum,2,'.',',').'</td>
					<td></td>
				</tr>
			</tfoot>';

		$table .= '</table></div>';
		$data['table'] = $table;
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/bu_charges_receivables_details.php');
		$this->load->view('templates/footer');

	}

	function print_rcv_bu_charging($business_unit, $charge_no, $type) {
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');
		$pdf = new TCPDF();

		$business_unit =  base64_decode($business_unit);
		$charge_no =  base64_decode($charge_no);
		$type =  base64_decode($type);

		$charging = $this->transaction_model->get_receivables_charging($charge_no);
		if($type == 'unpaid'){
			$header = '<h3>Business Unit Charges</h3>';
			$pdfname = 'Charge_'.$business_unit .'_'.date('YmdHi');
		}else{
			$header = '<h3>Paid Business Unit Charges</h3>';
			$pdfname = 'PaidCharge_'.$business_unit .'_'.date('YmdHi');
		}

		$title = '<img src="'.base_url().'assets/images/HC_logo.png" style="width:110px;height:70px">';

		$dateGenerate = '<small>Transaction Date: ' . date('m/d/Y h:i A').'</small> <small>( Reprinted Copy )</small><br>';
		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcard No.</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Employee`s Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Request Date</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>LOA/NOA No.</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Payment No.</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Company Charge</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcare Advance</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Total Charge</strong></th>
						</tr>
					</thead>';

		$previousHealthCardNo = '';
		$previousFullName = '';
		$totalPayableSum = 0;
		$healthCardTotals = []; // Store totals for each health_card_no
		
		foreach ($charging as $charge) {

			if($charge['bu_start_date'] != '0000-00-00' || $charge['bu_end_date'] != '0000-00-00'){
				$date = '<h3>From '.date('F d, Y',strtotime($charge['bu_start_date'])).' to '.date('F d, Y',strtotime($charge['bu_end_date'])).'</h3>';
			}else{
				$date = '';
			}
			
			if ($charge['company_charge'] && $charge['cash_advance'] != '') {

				$currentHealthCardNo = $charge['health_card_no'];
				$currentFullName = $charge['first_name'] . ' ' . $charge['middle_name'] . ' ' . $charge['last_name'] . ' ' . $charge['suffix'];
		
				$total_payable = floatval($charge['company_charge'] + $charge['cash_advance']);
		
				$healthCardNo = ($currentHealthCardNo !== $previousHealthCardNo) ? $currentHealthCardNo : '';
				$fullName = ($currentFullName !== $previousFullName) ? $currentFullName : '';
				if($charge['loa_id'] != ''){
					$loa = $this->transaction_model->get_loa_info($charge['loa_id']);
					$loa_noa_no = $loa['loa_no'];
					$request_date = date('m/d/Y',strtotime($loa['request_date']));
				}else if($charge['noa_id'] != ''){
					$noa = $this->transaction_model->get_noa_info($charge['noa_id']);
					$loa_noa_no = $noa['noa_no'];
					$request_date = date('m/d/Y',strtotime($noa['request_date']));
				}
				$PDFdata .= ' <tbody>
								<tr>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . $healthCardNo . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . $fullName . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . $request_date . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . $loa_noa_no . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . $charge['payment_no'] . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . number_format($charge['company_charge'],2,'.',',') . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . number_format($charge['cash_advance'],2,'.',',') . '</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">' . number_format($total_payable, 2, '.', ',') . '</td>';
				$PDFdata .= '</tr>
							</tbody>';
		
				$previousHealthCardNo = $currentHealthCardNo;
				$previousFullName = $currentFullName;

				$totalPayableSum += $total_payable;
			}
		}
		$title .= '<style>h3 { margin: 0; padding: 0; line-height: .5; }</style>
				'.$header.'
				'.$date.'
				<h3>'.$business_unit.'</h3>
				<h3>'.$charge_no.'</h3><br>';

		$PDFdata .= '<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="fw-bold">TOTAL</td>
						<td>'.number_format($totalPayableSum,2,'.',',').'</td>
					</tr>
				</tfoot>';

		$PDFdata .= '</table>';

		$user = '<br><br><br><br><table>';
		$user .='<tr>
					<td></td>
					<td><strong>Prepared By:</strong></td>
					<td></td>
					<td><strong>Noted By:</strong></td>
					<td></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td><strong>'.strtoupper($this->session->userdata('fullname')).'</strong></td>
					<td></td>
					<td><strong>_____________________________</strong></td>
					<td></td>
				</tr>';
		$user .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('Business Unit Charging Report');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L', 'LEGAL');
		$pdf->WriteHtmlCell(0, 0, '', '', $dateGenerate, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', '', 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $user, 0, 1, 0, true, 'J', true);
		$pdf->lastPage();
		$pageCount = $pdf->getAliasNumPage(); // Get the number of pages
		for ($i = 1; $i <= $pageCount; $i++) {
			$pdf->setPage($i); // Set the page number
			$pdf->writeHTMLCell(0, 0, '', '', 'Page '.$i.' of '.$pageCount, 0, 1, 0, true, 'R', true);
		}
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	function fetch_bu_paid_charge() {
		$token = $this->security->get_csrf_hash();
		$bu_status = 'Paid';
		$receivables = $this->transaction_model->fetch_receivables_bu($bu_status);
		$data = [];
		$type = 'paid';
		$totals = [
			'company_charge' => 0,
			'cash_advance' => 0,
		];
		$uniqueEntries = [];

		foreach ($receivables as $receivable) {
			$chargingKey = $receivable['bu_charging_no'];
			$charging = $this->transaction_model->get_billing_id($receivable['bu_charging_no']);
			$IDs = array_column($charging, 'billing_id');
			$encodedArray = urlencode(serialize($IDs));

			if (!isset($uniqueEntries[$chargingKey])) {
				$uniqueEntries[$chargingKey] = [
					'company_charge' => 0,
					'cash_advance' => 0,
					'count' => 0,
					'action' => '<a href="'.base_url().'head-office-iad/charging/bu-receivables/fetch/'.$type.'/'.$encodedArray.'" data-bs-toggle="tooltip" title="View Billing"><i class="mdi mdi-format-list-bulleted fs-2 pe-2 text-info"></i></a> <a href="JavaScript:void(0)" onclick="viewSupDoc(\''.$receivable['bu_proof_payment'].'\', \''.$chargingKey.'\')" data-bs-toggle="tooltip" title="View Supporting Document"><i class="mdi mdi-file-find fs-2 pe-2 text-danger"></i></a>'
				];
			}
	
			if ($receivable['company_charge'] && $receivable['cash_advance'] != '') {
				$uniqueEntries[$chargingKey]['company_charge'] += floatval($receivable['company_charge']);
				$uniqueEntries[$chargingKey]['cash_advance'] += floatval($receivable['cash_advance']);
				$uniqueEntries[$chargingKey]['count']++;
			}
		}
	
		foreach ($uniqueEntries as $key => $entry) {
			$row = [];
			$row[] = '<span class="text-info fw-bold">'.$key.'</span>';
			$row[] = date('F d, Y', strtotime($receivable['bu_tagged_paid_on']));
			$row[] = $receivable['business_unit'];
			$row[] = number_format($entry['company_charge'], 2, '.', ',');
			$row[] = number_format($entry['cash_advance'], 2, '.', ',');
			$row[] = number_format(floatval($entry['cash_advance'] + $entry['company_charge']), 2, '.', ',');
			$row[] = '<span class="bg-success text-white badge rounded-pill">'.$receivable['bu_charging_status'].'</span>';
			$row[] = $entry['action'];
			$data[] = $row;
	
			$totals['company_charge'] += $entry['company_charge'];
			$totals['cash_advance'] += $entry['cash_advance'];
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
			"totals" => $totals,
		];
	
		echo json_encode($output);
	}

	function fetch_charge_details() {
		$token = $this->security->get_csrf_hash();
		$billing_id = $this->input->get('billing_id');
		$bill = $this->transaction_model->get_charge_details($billing_id);

		if(!empty($bill['loa_id'])){
			$loa = $this->transaction_model->get_loa_info($bill['loa_id']);
			$loa_noa_no = $loa['loa_no'];
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
		}else if(!empty($bill['noa_id'])){
			$noa = $this->transaction_model->get_noa_info($bill['noa_id']);
			$loa_noa_no = $noa['noa_no'];
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

		$data = [
			'token' => $token,
			'payment_no' => $bill['payment_no'],
			'billing_no' => $bill['billing_no'],
			'loa_noa_no' => $loa_noa_no,
			'percentage' => $wpercent .', '.$nwpercent,
			'before_mbl' => number_format($bill['before_remaining_bal'],2,'.',','),
			'net_bill' => number_format($bill['net_bill'],2,'.',','),
			'company_charge' => number_format($bill['company_charge'],2,'.',','),
			'personal_charge' => number_format($bill['personal_charge'],2,'.',','),
			'cash_advance' => number_format($bill['cash_advance'],2,'.',','),
			'after_mbl' => number_format($bill['after_remaining_bal'],2,'.',','),
			'billed_on' => date('F d,Y', strtotime($bill['billed_on'])),
		];

		echo json_encode($data);
	}

	function fetch_payment_history() {
		$this->security->get_csrf_hash();
	
		$list = $this->transaction_model->get_payment_datatables();
		$data = [];
		$previous_payment_no = '';
		$number = 1;
		foreach($list as $payment){
			// Check if payment_no is the same as the previous iteration
			if ($payment['payment_no'] !== $previous_payment_no) {
				$row = [];
				$details_id = $this->myhash->hasher($payment['details_id'], 'encrypt');
	
				$custom_actions = '<a class="text-info fw-bold ls-1 fs-4" href="JavaScript:void(0)" onclick="viewPaymentInfo(\'' . $details_id . '\',\'' . base_url() . 'uploads/paymentDetails/' . $payment['supporting_file'] . '\')"  data-bs-toggle="tooltip"><u><i class="mdi mdi-view-list fs-3" title="View Payment Details"></i></u></a>';
	
				$custom_actions .= '<a class="text-success fw-bold ls-1 ps-2 fs-4" href="javascript:void(0)" onclick="viewCheckVoucher(\'' . $payment['supporting_file'] . '\')" data-bs-toggle="tooltip"><u><i class="mdi mdi-file-pdf fs-3" title="View Proof"></i></u></a>';
	
				if(!empty($payment['acc_number'])){
					$acc_number = $payment['acc_number'];
				}else{
					$acc_number = 'None';
				}
				if(!empty($payment['acc_name'])){
					$acc_name = $payment['acc_name'];
				}else{
					$acc_name = 'None';
				}

				$row[] = $number++;
				$row[] = $payment['cv_number'];
				$row[] = $acc_number;
				$row[] = $acc_name;
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
			"recordsTotal" => $this->transaction_model->count_payment_all(),
			"recordsFiltered" => $this->transaction_model->count_payment_filtered(),
			"data" => $data
		];
		echo json_encode($output);
	}

	function fetch_details_payment() {
		$details_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$payment = $this->transaction_model->get_payment_details($details_id);
		
		$loa_no = $this->transaction_model->get_loa($payment['details_no']);
		$noa_no = $this->transaction_model->get_noa($payment['details_no']);
		$noa_loa_array = [];
		foreach($loa_no as $covered_loa){
			if($covered_loa['company_charge'] && $covered_loa['cash_advance'] != ''){
				if($covered_loa['loa_id'] != '' ){
					array_push($noa_loa_array, $covered_loa['loa_no']);
				}
			}
		}

		foreach($noa_no as $covered_noa){
			if($covered_noa['company_charge'] && $covered_noa['cash_advance'] != ''){
				if($covered_noa['noa_id'] != ''){
					array_push($noa_loa_array, $covered_noa['noa_no']);
				}
			}
		}

		$loa_noa_no = implode(',    ', $noa_loa_array);
		$bill_id = $this->myhash->hasher($payment['bill_id'], 'encrypt');

			$response = [
				'status' => 'success',
				'token' => $this->security->get_csrf_hash(),
				'payment_no' => $payment['payment_no'],
				'bill_id' => $bill_id,
				'hp_name' => $payment['hp_name'],
				'added_on' => date("F d, Y", strtotime($payment['date_add'])),
				'acc_number' => $payment['acc_number'],
				'acc_name' => $payment['acc_name'],
				'check_num' => $payment['check_num'],
				'check_date' => $payment['check_date'],
				'bank' => $payment['bank'],
				'amount_paid' => number_format(floatval($payment['amount_paid']),2,'.',','),
				'billed_date' => 'From '. date("F d, Y", strtotime($payment['startDate'])).' to '. date("F d, Y", strtotime($payment['endDate'])),
				'covered_loa_no' => $loa_noa_no,
				'billing_id' => $this->myhash->hasher($payment['billing_id'],'encrypt'),
				'billing_type' => $payment['billing_type'],
				'cv_number' => $payment['cv_number'],
				'cv_date' => $payment['cv_date'],
				'payee' => $payment['payee'],
			]; 

		echo json_encode($response);
	}

	function fetch_paid_bill_ledger() {
		$ledger = $this->transaction_model->get_debit_credit_yearly();
		$data = [];
		$fullnameData = [];
	
		foreach ($ledger as $bill) {
			if ($bill['company_charge'] && $bill['cash_advance'] != '') {
				$fullname = $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
				$date_add = $bill['date_add'];

				$company_charge = floatval($bill['company_charge']);
				$cash_advance = floatval($bill['cash_advance']);
				$total_paid_amount = floatval($bill['total_paid_amount']);
	
				$key = $fullname . '_' . $date_add;
				if (!isset($fullnameData[$key])) {
					$fullnameData[$key] = [
						'total_debit' => 0,
						'total_paid_amount' => 0,
						'business_unit' => $bill['business_unit'],
					];
				}
	
				$fullnameData[$key]['total_debit'] += ($company_charge + $cash_advance);
				$fullnameData[$key]['total_paid_amount'] += $total_paid_amount;
			}
		}
		$number = 1;
		foreach ($fullnameData as $key => $totals) {
			list($fullname, $date_add) = explode('_', $key);

			$row = [];
			$row[] = $number++;
			$row[] = date('F d,Y',strtotime($date_add));
			$row[] = $fullname;
			$row[] = $totals['business_unit'];
			$row[] = number_format($totals['total_debit'], 2, '.', ',');
			$row[] = number_format($totals['total_paid_amount'], 2, '.', ',');
			$data[] = $row;

			// $scndrow = ['','','','',number_format($totals['total_paid_amount'], 2, '.', ',')];
			// $data[] = $scndrow;
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}

	function fetch_mbl_ledger() {
		$filteredYear = $this->input->post('year', TRUE);
		$currentYear = date('Y');
	
		if ($filteredYear == $currentYear) {
			$mbl = $this->transaction_model->get_ledger_mbl();
		} else if ($filteredYear == '') {
			$mbl = $this->transaction_model->get_ledger_mbl();
		} else if ($filteredYear != $currentYear) {
			$mbl = $this->transaction_model->get_ledger_history_mbl();
		}
		
		$data = [];
		$number = 1;
		foreach ($mbl as $max) {
			$row = [];

			$fullname = $max['first_name'] . ' ' . $max['middle_name'] . ' ' . $max['last_name'] . ' ' . $max['suffix'];
			$action = '<a href="JavaScript:void(0)" onclick="viewMBLDetails(\''.$max['emp_id'].'\',\''.$filteredYear.'\',\''.$fullname.'\')" title="View MBL Details" class="btn-success btn-sm"><i class="mdi mdi-magnify"></i> View</a>';

			$row[] = $number++;
			$row[] = $max['health_card_no'];
			$row[] = $fullname;
			$row[] = $max['business_unit'];
			$row[] = $action;
			$data[] = $row;
			
		}
	
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}

	function fetch_mbl_ledger_details() {
		$filteredYear = $this->input->post('filteredYear');
		$currentYear = date('Y');

		if ($filteredYear == $currentYear) {
			$mbl = $this->transaction_model->get_current_ledger_mbl();
		} else if ($filteredYear == '') {
			$mbl = $this->transaction_model->get_current_ledger_mbl();
		} else if ($filteredYear != $currentYear) {
			$mbl = $this->transaction_model->get_history_mbl_details();
		}

		$data = [];
		$fullnameData = [];
	
		foreach ($mbl as $max) {
			$healcardno = $max['health_card_no'];
			$fullname = $max['first_name'] . ' ' . $max['middle_name'] . ' ' . $max['last_name'] . ' ' . $max['suffix'];
			$business_unit = $max['business_unit'];
	
			$company_charge = floatval($max['company_charge']);
			$mbl = floatval($max['max_benefit_limit']);
	
			$key = $healcardno . '_' . $fullname . '_' . $business_unit;
	
			if (!isset($fullnameData[$key])) {
				$fullnameData[$key] = [
					'date_used' => [],
					'used_mbl' => [],
					'max_benefit' => $max['max_benefit_limit'],
					'remaining_mbl' => 0,
				];
			}
	
			$fullnameData[$key]['date_used'][] = date('F d,Y', strtotime($max['billed_on']));
			$fullnameData[$key]['used_mbl'][] = $max['company_charge'];
		}
	
		$previous_fullname = '';
		$number = 1;
		foreach ($fullnameData as $key => $totals) {
			list($healcardno, $fullname, $business_unit) = explode('_', $key);

			$max_benefit = number_format($totals['max_benefit'], 2, '.', ',');

			$dates_used = $totals['date_used'];
			$used_mbls = $totals['used_mbl'];
			$remaining_mbl = $totals['max_benefit'];

			$num_entries = count($dates_used);

			for ($i = 0; $i < $num_entries; $i++) {
				$used_mbl = floatval($used_mbls[$i]);

				if ($fullname !== $previous_fullname) {
					$first_row = [
						'<span class="text-success">-----</span>',
						'<span class="text-success">-----</span>',
						'<span class="text-success">-----</span>',
						number_format($remaining_mbl, 2, '.', ',')
					];

					$data[] = $first_row;
				}

				$row = [
					$number++,
					$dates_used[$i],
					number_format($used_mbl, 2, '.', ','),
					number_format(max($remaining_mbl - $used_mbl, 0), 2, '.', ',')
				];

				$data[] = $row;

				$remaining_mbl = max($remaining_mbl - $used_mbl, 0);
				$previous_fullname = $fullname;
			}
		}
			$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
		];
	
		echo json_encode($output);
	}

	function ho_print_paid_ledger($month, $year, $bu_filter) {
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');
		$pdf = new TCPDF();

		$month =  base64_decode($month);
		$year =  base64_decode($year);
		$bu_filter =  base64_decode($bu_filter);

		if($month != 'none'){
			$months = $month; 
		}else{
			$months = '';
		}

		if($year != 'none'){
			$years = $year; 
		}else{
			$years = '';
		}

		if($bu_filter != 'none'){
			$bu_unit = $bu_filter; 
			$bu_header = '<h3>'.$bu_unit.'</h3>';
		}else{
			$bu_unit = '';
			$bu_header = '';

		}

		if(!empty($years || $months)){
			if($months == ''){
				$monthN = '';
				if($years != ''){
					$yearsF = 'For the Year '.$years;
				}else{
					$yearsF = '';
				}
			}else{
				$Numberedmonth = intval($months);
				$timestamp = mktime(0, 0, 0, $Numberedmonth, 1);
				$monthName = date('F', $timestamp);
				$monthN = 'For the Month of '.$monthName;
				$yearsF = $years;
			}
			$header = '<h3>'.$monthN.' '.$yearsF.'</h3>';
		}else{
			$header = '';
		}

		$ledger = $this->transaction_model->get_employee_paid_ledger($years, $months, $bu_unit);

		$title = '<img src="'.base_url().'assets/images/HC_logo.png" style="width:110px;height:70px">';
		$title .= '<style>h3 { margin: 0; padding: 0; line-height: .5; }</style>
					<h3>Paid Bill Ledger</h3>
					'.$bu_header.'
					'.$header.'<br>';

		$dateGenerate = '<small>Date Generated : '.date('F d, Y').'</small><br>';
		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Date Paid</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Member`s Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Debit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Credit</strong></th>
						</tr>
					</thead>';

		$fullnameData = [];
		$totalDebit = 0;
		$totalCredit = 0;
		foreach ($ledger as $bill) {
			if ($bill['company_charge'] && $bill['cash_advance'] != '') {
				$fullname = $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
				$date_add = $bill['date_add'];

				$company_charge = floatval($bill['company_charge']);
				$cash_advance = floatval($bill['cash_advance']);
				$total_paid_amount = floatval($bill['total_paid_amount']);
	
				$key = $fullname . '_' . $date_add;
				if (!isset($fullnameData[$key])) {
					$fullnameData[$key] = [
						'total_debit' => 0,
						'total_paid_amount' => 0,
						'business_unit' => $bill['business_unit'],
					];
				}
	
				$fullnameData[$key]['total_debit'] += ($company_charge + $cash_advance);
				$fullnameData[$key]['total_paid_amount'] += $total_paid_amount;
			}
		}
	
		foreach ($fullnameData as $key => $totals) {
			list($fullname, $date_add) = explode('_', $key);
			
			$PDFdata .= '<tbody><tr>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.date('m/d/Y',strtotime($date_add)).'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$totals['business_unit'].'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($totals['total_debit'], 2, '.', ',').'</td>
							<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($totals['total_paid_amount'], 2, '.', ',').'</td>
						</tr>';

			$totalDebit += floatval($totals['total_debit']);
			$totalCredit += floatval($totals['total_paid_amount']);
		}
		$PDFdata .=  '<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td class="fw-bold">TOTALS</td>
							<td class="fw-bold">'.number_format($totalDebit,2,'.',',').'</td>
							<td class="fw-bold">'.number_format($totalCredit,2,'.',',').'</td>
						</tr>
					</tfoot>';
		$PDFdata .= '</table>';

		$user = '<br><br><br><br><table>';
		$user .='<tr>
					<td></td>
					<td><strong>Prepared By:</strong></td>
					<td></td>
					<td><strong>Noted By:</strong></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td><strong>'.strtoupper($this->session->userdata('fullname')).'</strong></td>
					<td></td>
					<td><strong>_____________________________</strong></td>
				</tr>';
		$user .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('Paid Bill Ledger');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $dateGenerate, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', '', 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $user, 0, 1, 0, true, 'J', true);
		$pdf->lastPage();
		$pageCount = $pdf->getAliasNumPage(); // Get the number of pages
		for ($i = 1; $i <= $pageCount; $i++) {
			$pdf->setPage($i); // Set the page number
			$pdf->writeHTMLCell(0, 0, '', '', 'Page '.$i.' of '.$pageCount, 0, 1, 0, true, 'R', true);
		}
		$pdfname = 'LedgerPaid_'.date('YmdHi');
		$pdf->Output($pdfname.'.pdf', 'I');
	}

	function print_mbl_ledger($year, $bu_filter){
		$this->security->get_csrf_hash();
		$this->load->library('tcpdf_library');
		$pdf = new TCPDF();

		$year =  base64_decode($year);
		$bu_filter =  base64_decode($bu_filter);

		if($year != 'none'){
			$years = $year; 
			$yr_header = '<h3>For the Year '.$years.'</h3>';
		}else{
			$years = '';
			$yr_header = '';
		}

		if($bu_filter != 'none'){
			$bu_unit = $bu_filter; 
			$bu_header = '<h3>'.$bu_unit.'</h3>';
		}else{
			$bu_unit = '';
			$bu_header = '';

		}

		if($years == date('Y') || $years ==''){
			$mbls = $this->transaction_model->get_employee_ledger_mbl($years, $bu_unit);
		}else{
			$mbls = $this->transaction_model->get_employee_history_mbl($years, $bu_unit);
		}

		$title = '<img src="'.base_url().'assets/images/HC_logo.png" style="width:110px;height:70px">';
		$title .= '<style>h3 { margin: 0; padding: 0; line-height: .5; }</style>
					<h3>Max Benefit Limit Ledger</h3>
					'.$bu_header.'
					'.$yr_header.'<br>';

		$dateGenerate = '<small>Date Generated : '.date('F d, Y').'</small><br>';
		$PDFdata = '<table style="border:.5px solid #000; padding:3px" class="table table-bordered">';
		$PDFdata .= ' <thead>
						<tr class="border-secondary">
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Healthcard No.</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Member`s Name</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Business Unit</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Date Used</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Used MBL</strong></th>
							<th class="fw-bold" style="border:.5px solid #000; padding:1px"><strong>Remaining MBL</strong></th>
						</tr>
					</thead>';
		$PDFdata .= '<tbody>';

		$fullnameData = [];
	
		foreach ($mbls as $max) {
			$healcardno = $max['health_card_no'];
			$fullname = $max['first_name'] . ' ' . $max['middle_name'] . ' ' . $max['last_name'] . ' ' . $max['suffix'];
			$business_unit = $max['business_unit'];
	
			$company_charge = floatval($max['company_charge']);
			$mbl = floatval($max['max_benefit_limit']);
	
			$key = $healcardno . '_' . $fullname . '_' . $business_unit;
	
			if (!isset($fullnameData[$key])) {
				$fullnameData[$key] = [
					'date_used' => [],
					'used_mbl' => [],
					'max_benefit' => $max['max_benefit_limit'],
					'remaining_mbl' => 0,
				];
			}
	
			$fullnameData[$key]['date_used'][] = $max['billed_on'];
			$fullnameData[$key]['used_mbl'][] = $max['company_charge'];
		}
	
		$previous_fullname = '';

		foreach ($fullnameData as $key => $totals) {
			list($healcardno, $fullname, $business_unit) = explode('_', $key);

			$max_benefit = number_format($totals['max_benefit'], 2, '.', ',');

			$dates_used = $totals['date_used'];
			$used_mbls = $totals['used_mbl'];
			$remaining_mbl = $totals['max_benefit'];

			$num_entries = count($dates_used);

			for ($i = 0; $i < $num_entries; $i++) {
				$used_mbl = floatval($used_mbls[$i]);

				if ($fullname !== $previous_fullname) {
					$PDFdata .= '<tr>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$healcardno.'</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$fullname.'</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$business_unit.'</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">----</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">----</td>
									<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($remaining_mbl, 2, '.', ',').'</td>
								</tr>';
				}
				$PDFdata .= '<tr>
								<td class="fs-5" style="border:.5px solid #000; padding:1px"></td>
								<td class="fs-5" style="border:.5px solid #000; padding:1px"></td>
								<td class="fs-5" style="border:.5px solid #000; padding:1px"></td>
								<td class="fs-5" style="border:.5px solid #000; padding:1px">'.$dates_used[$i].'</td>
								<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format($used_mbl, 2, '.', ',').'</td>
								<td class="fs-5" style="border:.5px solid #000; padding:1px">'.number_format(max($remaining_mbl - $used_mbl, 0), 2, '.', ',').'</td>
							</tr>';

				$remaining_mbl = max($remaining_mbl - $used_mbl, 0);
				$previous_fullname = $fullname;
			}
		}
				
		$PDFdata .= '</tbody></table>';
		$user = '<br><br><br><br><table>';
		$user .='<tr>
					<td></td>
					<td><strong>Prepared By:</strong></td>
					<td></td>
					<td><strong>Noted By:</strong></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
		$user .='<tr>
					<td></td>
					<td><strong>'.strtoupper($this->session->userdata('fullname')).'</strong></td>
					<td></td>
					<td><strong>________________________</strong></td>
				</tr>';
		$user .= '</table>';

		$pdf->setPrintHeader(false);
		$pdf->setTitle('Max Benefit Limit Ledger');
		$pdf->setFont('times', '', 10);
		$pdf->AddPage('L');
		$pdf->WriteHtmlCell(0, 0, '', '', $dateGenerate, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', '', 0, 1, 0, true, 'C', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $PDFdata, 0, 1, 0, true, 'R', true);
		$pdf->WriteHtmlCell(0, 0, '', '', $user, 0, 1, 0, true, 'J', true);
		$pdf->lastPage();
		$pageCount = $pdf->getAliasNumPage(); // Get the number of pages
		for ($i = 1; $i <= $pageCount; $i++) {
			$pdf->setPage($i); // Set the page number
			$pdf->writeHTMLCell(0, 0, '', '', 'Page '.$i.' of '.$pageCount, 0, 1, 0, true, 'R', true);
		}
		$pdfname = 'LedgerMbl_'.date('YmdHi');
		$pdf->Output($pdfname.'.pdf', 'I');
	}



	//END ==================================================
}
		