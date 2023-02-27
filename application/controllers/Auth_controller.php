<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth_model');
	}

	private function _hash_password($password) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		return $hashed_password;
	}

	private function _verify_hash($plain_text_str, $hashed_string) {
		return password_verify($plain_text_str, $hashed_string);
	}

	public function index() {
		$this->load->view('pages/index');
	}

	public function page_not_found() {
		$this->load->view('pages/page_not_found');
	}

	/**
	 * It checks if the username and password are valid, and if they are, it returns a JSON response with
	 * the user's information
	 */
	public function check_login() {
		$token = $this->security->get_csrf_hash();
		$username = strip_tags(trim($this->input->post('username')));
		$password = strip_tags(trim($this->input->post('password')));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$response = array('status' => 'error', 'message' => validation_errors(),  'username' => $username, 'password' =>  $password);
		} else {
			$result = $this->auth_model->get_user_info($username);
			if (!$result) {
				$response = array('status' => 'error', 'message' => 'Incorrect Username or Password',  'username' => $username, 'password' => $password);
			} else if ($result['status'] === 'Blocked') {
				$response = array('status' => 'error', 'message' => 'User Account Deactivated',  'username' => $username, 'password' => $password);
			} else {
				$verified = $this->_verify_hash($password, $result['password']);
				if (!$verified) {
					$response = array('status' => 'error', 'message' => 'Incorrect Username or Password', 'username' => $username, 'password' => $password);
				} else {
					$response = array(
						'status' => 'success',
						'token' => $token,
						'user_id' => $result['user_id'], 
						'emp_id' => $result['emp_id'],
						'fullname' => $result['full_name'],
						'user_role' => $result['user_role'],
						'dsg_hcare_prov' => $result['dsg_hcare_prov'],
						'doctor_id' => $result['doctor_id'],
						'logged_in' => true,
						'next_route' => 'redirect-to-dashboard',
						'next_page' => $result['user_role'] . '/dashboard'
					);
				}
			}
		}

		echo json_encode($response);
	}

	/**
	 * It takes the user's information from the login form and stores it in the session
	 */
	public function redirect_to_dashboard() {
		$user_id = $this->input->post('user_id');
		$user_info = array(
			'user_id' => $user_id,
			'emp_id' => $this->input->post('emp_id'),
			'fullname' => $this->input->post('fullname'),
			'user_role' => $this->input->post('user_role'),
			'dsg_hcare_prov' => $this->input->post('dsg_hcare_prov'),
			'doctor_id' => $this->input->post('doctor_id'),
			'logged_in' => $this->input->post('logged_in'),
			'token' => $this->input->post('token'),
		);
		$online = $this->auth_model->set_online($user_id);
		if (!$online) {
			$response = array('status' => 'save-error', 'message' => 'Unable to Log You In...Please Try Again!');
		}
		$this->session->set_userdata($user_info);
		$response = array('status' => 'success', 'message' => 'Logging You In...');
		echo json_encode($response);
	}

	public function logout() {
		$user_id = $this->session->userdata('user_id');
		$this->auth_model->set_offline($user_id);
		$array_items = array('id', 'user_id', 'emp_id', 'fullname', 'user_role', 'dsg_hcare_prov', 'doctor_id', 'logged_in', 'token');
		$this->session->unset_userdata($array_items);
		redirect('/');
	}
}
