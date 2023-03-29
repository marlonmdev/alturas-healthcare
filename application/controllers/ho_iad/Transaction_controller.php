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
		$middle_name = $this->security->xss_clean($this->input->post('last_name'));
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
		$payment_no = $this->uri->segment(4);
		$token = $this->security->get_csrf_hash();
		$data['user_role'] = $this->session->userdata('user_role');
		$billing = $this->transaction_model->get_billing_by_payment_no($payment_no);
		$payment_details = $this->transaction_model->get_paymentdetails_by_payment_no($payment_no);

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
			'amount_paid'    => $payment_details['amount_paid'],
			'type_request'     => $request_type,
		];

		echo json_encode($response);
	}
	//END ===============================================================

	//MEMBERS ==================================================
	public function members(){
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/member/member');
		$this->load->view('templates/footer');
	}
	
	function fetch_all_members(){
		$this->security->get_csrf_hash();
		$approval_status = 'Paid';
		$list = $this->transaction_model->get_datatables($approval_status);
		$data = array();
		foreach ($list as $member){
			$row = array();
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

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->transaction_model->count_all($approval_status),
			"recordsFiltered" => $this->transaction_model->count_filtered($approval_status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function view_information(){
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
	public function account_settings() {
		$user_id = $this->session->userdata('user_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->transaction_model->get_user_account_details($user_id);
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/dashboard/account_setting');
		$this->load->view('templates/footer');
	}
	public function update_password() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$user_id = $this->session->userdata('user_id');
		$this->form_validation->set_rules('current-password', 'Current Password', 'trim|required|callback_check_current_password');
		$this->form_validation->set_rules('new-password', 'New Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('confirm-password', 'Confirm Password', 'trim|required|matches[new-password]');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'status' => 'error',
				'current_password_error' => form_error('current-password'),
				'new_password_error' => form_error('new-password'),
				'confirm_password_error' => form_error('confirm-password'),
			);
		} else {
			$post_data = array(
				'password' => $this->_hash_password($input_post['confirm-password']),
				'updated_on' =>  date("Y-m-d"),
				'updated_by' => $this->session->userdata('fullname'),
			);
			$updated = $this->transaction_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = array('status' => 'save-error', 'message' => 'Password Update Failed');
			}
			$response = array('status' => 'success', 'message' => 'Password Updated Successfully');
		}
		echo json_encode($response);
	}

	public function check_current_password($current_password) {
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

	public function update_username() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$user_id = $this->session->userdata('user_id');
		$this->form_validation->set_rules('current-username', 'Current Username', 'trim|required|callback_check_current_username');
		$this->form_validation->set_rules('new-username', 'New Username', 'trim|required|min_length[6]|callback_check_username_exist');
		$this->form_validation->set_rules('confirm-username', 'Confirm Username', 'trim|required|matches[new-username]');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'status' => 'error',
				'current_username_error' => form_error('current-username'),
				'new_username_error' => form_error('new-username'),
				'confirm_username_error' => form_error('confirm-username'),
			);
		} else {
			$post_data = array(
				'username' => $input_post['confirm-username'],
				'updated_on' =>  date("Y-m-d"),
				'updated_by' => $this->session->userdata('fullname'),
			);
			$updated = $this->transaction_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = array('status' => 'save-error', 'message' => 'Username Update Failed');
			}
			$response = array('status' => 'success', 'message' => 'Username Updated Successfully');
		}
		echo json_encode($response);
	}
	public function check_current_username($current_username) {
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
	public function check_username_exist($new_username) {
		$exists = $this->transaction_model->db_check_username($new_username);
		if (!$exists) {
			return true;
		}else{
			$this->form_validation->set_message('check_username_exist', 'Username already taken, Please try Another!');
			return false;
		}
	}
	//END ==================================================
}
		