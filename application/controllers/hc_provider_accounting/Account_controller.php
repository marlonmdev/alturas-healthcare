<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/account_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'hc-provider-accounting') {
			redirect(base_url());
		}
	}

	function account_settings() {
		$user_id = $this->session->userdata('user_id');
		$data['page_title'] = 'Alturas Healthcare - Healthcare Provider';
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->account_model->get_user_account_details($user_id);
		$this->load->view('templates/header', $data);
		$this->load->view('hc_provider_accounting_panel/dashboard/account_settings');
		$this->load->view('templates/footer');
	}

	/**
	 * It updates the user's password
	 */
	function update_account_password() {
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
			$updated = $this->account_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = array('status' => 'save-error', 'message' => 'Password Update Failed');
			}
			$response = array('status' => 'success', 'message' => 'Password Updated Successfully');
		}
		echo json_encode($response);
	}

	/**
	 * It checks if the current password is correct
	 * 
	 * @param current_password The name of the form field that contains the current password.
	 * 
	 * @return a boolean value.
	 */
	function check_current_password($current_password) {
		$user_id = $this->session->userdata('user_id');
		$row = $this->account_model->get_user_account_details($user_id);
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

	/**
	 * It verifies the hash of a string.
	 * 
	 * @param plain_text_str The string you want to hash.
	 * @param hashed_string The hashed string that you want to verify.
	 * 
	 * @return The result of the password_verify function.
	 */
	private function _verify_hash($plain_text_str, $hashed_string) {
		$result = password_verify($plain_text_str, $hashed_string);
		return $result;
	}


	/**
	 * It updates the username of the logged in user
	 */
	function update_account_username() {
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
			$updated = $this->account_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = array('status' => 'save-error', 'message' => 'Username Update Failed');
			}
			$response = array('status' => 'success', 'message' => 'Username Updated Successfully');
		}
		echo json_encode($response);
	}

	/**
	 * It checks if the current username is correct
	 * 
	 * @param current_username The value of the current username field.
	 * 
	 * @return a boolean value.
	 */
	function check_current_username($current_username) {
		$user_id = $this->session->userdata('user_id');
		$row = $this->account_model->get_user_account_details($user_id);
		$db_username = $row['username'];
		$match = $current_username === $db_username ? true : false;
		if (!$match) {
			$this->form_validation->set_message('check_current_username', 'Current username is incorrect');
			return false;
		}
		return true;
	}

	/**
	 * It checks if the username exists in the database
	 * 
	 * @param new_username The name of the field that we're checking.
	 */
	function check_username_exist($new_username) {
		$exists = $this->account_model->db_check_username($new_username);
		if (!$exists) {
			return true;
		} else {
			$this->form_validation->set_message('check_username_exist', 'Username already taken, Please try Another!');
			return false;
		}
	}
}
