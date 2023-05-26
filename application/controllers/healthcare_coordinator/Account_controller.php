<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/account_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function account_settings() {
		$this->load->model('healthcare_coordinator/setup_model');
		$user_id = $this->session->userdata('user_id');
		$data['page_title'] = 'Alturas Healthcare - Healthcare Coordinator';
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $this->account_model->get_user_account_details($user_id);
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/dashboard/account_settings');
		$this->load->view('templates/footer');
	}

	/**
	 * It updates the user's password
	 */
	function update_account_password() {
		$this->security->get_csrf_hash();
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
			$updated = $this->account_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = [
					'status' => 'save-error',
					'message' => 'Password Update Failed'
				];
			}
			$response = [
				'status' => 'success', 
				'message' => 'Password Updated Successfully'
			];
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
		$this->security->get_csrf_hash();
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
			$updated = $this->account_model->db_update_user_account($user_id, $post_data);
			if (!$updated) {
				$response = [
					'status' => 'save-error',
					'message' => 'Username Update Failed'
				];
			}
			$response = [
				'status' => 'success', 
				'message' => 'Username Updated Successfully'
			];
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

	function check_manager_username() {
		$this->security->get_csrf_hash();
		$mgr_username = $this->input->post('mgr-username', TRUE);
		$mgr_password = $this->input->post('mgr-password', TRUE);
		$expired_loa_id = $this->input->post('expired-loa-id', TRUE);
		$expired_loa_no = $this->input->post('expired-loa-no', TRUE);
		$expired_noa_id = $this->input->post('expired-noa-id', TRUE);
		$expired_noa_no = $this->input->post('expired-noa-no', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('mgr-username', 'Username', 'trim|required');
		$this->form_validation->set_rules('mgr-password', 'Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'mgr_username_error' => form_error('mgr-username'),
				'mgr_password_error' => form_error('mgr-password'),
			];
		} else {
			$result = $this->account_model->get_manager_info($mgr_username);
			if (!$result) {
				$response = [
					'status' => 'error',
					'message' => 'Incorrect Username or Password',
					'mgr_username_error' => '',
					'mgr_password_error' => '',
					'loa_id'  => $expired_loa_id,
					'loa_no'  => $expired_loa_no,
					'noa_id'  => $expired_noa_id,
					'noa_no'  => $expired_noa_no
				];
			} else {
				$verified = $this->_verify_hash($mgr_password, $result['password']);
				if (!$verified) {
					$response = [
						'status' => 'error',
						'message' => 'Incorrect Username or Password',
						'mgr_username_error' => '',
						'mgr_password_error' => '',
						'loa_id'  => $expired_loa_id,
						'loa_no'  => $expired_loa_no,
						'noa_id'  => $expired_noa_id,
						'noa_no'  => $expired_noa_no
					];
				} else {
					$response = [
						'status'  => 'success',
						'message' => 'Access Granted',
						'loa_id'  => $expired_loa_id,
						'loa_no'  => $expired_loa_no,
						'noa_id'  => $expired_noa_id,
						'noa_no'  => $expired_noa_no
					];
				}
			}
		}

		echo json_encode($response);
	}

	function check_manager_key() {
		$this->security->get_csrf_hash();
		$mgr_username = $this->input->post('mgr-username', TRUE);
		$mgr_password = $this->input->post('mgr-password', TRUE);
		$loa_id = $this->input->post('expired-loa-id', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('mgr-username', 'Username', 'trim|required');
		$this->form_validation->set_rules('mgr-password', 'Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'mgr_username_error' => form_error('mgr-username'),
				'mgr_password_error' => form_error('mgr-password'),
			];
		} else {
			$result = $this->account_model->get_manager_info($mgr_username);
			if (!$result) {
				$response = [
					'status' => 'error',
					'message' => 'Incorrect Username or Password',
					'mgr_username_error' => '',
					'mgr_password_error' => '',
					'loa_id'  => $loa_id,
					'company_doctor' => $result['doctor_id']
				];
			} else {
				$verified = $this->_verify_hash($mgr_password, $result['password']);
				if (!$verified) {
					$response = [
						'status' => 'error',
						'message' => 'Incorrect Username or Password',
						'mgr_username_error' => '',
						'mgr_password_error' => '',
						'loa_id'  => $loa_id,
						'company_doctor' => $result['doctor_id']
					];
				} else {
					$response = [
						'status'  => 'success',
						'message' => 'Access Granted',
						'loa_id'  => $loa_id,
						'company_doctor' => $result['doctor_id']
					];
				}
			}
		}

		echo json_encode($response);
	}

}
