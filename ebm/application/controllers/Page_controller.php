<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('auth_model');
	}

	public function index() {
		$data['page_title'] = "Alturas Healthcare Portal";
		$empty = $this->auth_model->get_users_count();
		if ($empty) {
			$this->set_default_super_admin_user();
			$this->set_default_hcare_coordinator_user();
			$this->load->view('pages/index', $data);
		} else {
			$this->load->view('pages/index', $data);
		}
	}

	private function _hash_password($password) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		return $hashed_password;
	}

	public function set_default_super_admin_user() {
		// this default credentials is located in the config folder/config.php
		$superadmin_userrole = $this->config->item('def_admin_userrole');
		$superadmin_username = $this->config->item('def_admin_username');
		$superadmin_password = $this->config->item('def_admin_password');
		$post_data = array(
			'member_id' => '',
			'emp_id' => '',
			'full_name' => 'IT SysDev',
			'user_role' => $superadmin_userrole,
			'username' => $superadmin_username,
			'password' => $this->_hash_password($superadmin_password),
			'dsg_hcare_prov' => '',
			'status' => 'Active',
			'date_added' => date("Y-m-d"),
		);
		// if there is none insert detault super admin user account
		$this->auth_model->insert_default_account($post_data);
	}

	public function set_default_hcare_coordinator_user() {
		// this default credentials is located in the config folder/config.php
		$hcc_userrole = $this->config->item('def_hcc_userrole');
		$hcc_username = $this->config->item('def_hcc_username');
		$hcc_password = $this->config->item('def_hcc_password');
		$post_data = array(
			'member_id' => '',
			'emp_id' => '',
			'full_name' => 'Alturas Healthcare Coordinator',
			'user_role' => $hcc_userrole,
			'username' => $hcc_username,
			'password' => $this->_hash_password($hcc_password),
			'dsg_hcare_prov' => '',
			'status' => 'Active',
			'date_added' => date("Y-m-d"),
		);
		// if there is none insert detault health care coordinator user account
		$this->auth_model->insert_default_account($post_data);
	}

	public function format_url($str) {
		$separator = '-';
		$result = strtolower($str);
		$result = preg_replace('/[^[:alnum:]]/', ' ', $result);
		$result = preg_replace('/[[:space:]]+/', $separator, $result);
		return trim($result, $separator);
	}
}
