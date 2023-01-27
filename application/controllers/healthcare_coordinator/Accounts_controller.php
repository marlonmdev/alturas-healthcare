<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounts_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/accounts_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function fetch_all_accounts() {
		$this->security->get_csrf_hash();
		$list = $this->accounts_model->get_datatables();
		$data = array();
		foreach ($list as $account) {
			$row = [];
			// calling Myhash custom library inside application/libraries folder
			$user_id = $this->myhash->hasher($account['user_id'], 'encrypt');

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewUserAccount(\'' . $user_id . '\')" data-bs-toggle="tooltip" title="View Details"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="resetUserPassword(\'' . $user_id . '\')" data-bs-toggle="tooltip" title="Reset User Password"><i class="mdi mdi-restart fs-2 text-warning"></i></a>';

			if ($account['user_role'] == 'member') {
				$custom_actions .= '<a class="me-2" disabled><i class="mdi mdi-pencil-circle fs-2 text-success icon-disabled"></i></a>';
			} else {
				$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="editUserAccount(\'' . $user_id . '\')" data-bs-toggle="tooltip" title="Edit User Details"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';
			}

			// if ($account['user_role'] == 'company-doctor') {
			// 	$custom_actions .= '<a disabled><i class="mdi mdi-delete-circle fs-2 text-danger icon-disabled"></i></a>';
			// } else {
			// 	$custom_actions .= '<a href="JavaScript:void(0)" onclick="deleteUserAccount(\'' . $user_id . '\')" data-bs-toggle="tooltip" title="Delete User Account"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';
			// }

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="deleteUserAccount(\'' . $user_id . '\')" data-bs-toggle="tooltip" title="Delete User Account"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

			$custom_user_role =  ucwords(str_replace("-", " ", $account['user_role']));

			$custom_switch = $account['status'] === 'Blocked' ? '<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="changeUserAccountStatus(\'' . $user_id . '\')"></div>' : '<div class="form-check form-switch text-center">
			<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="changeUserAccountStatus(\'' . $user_id . '\')" checked></div>';

			$custom_status = $account['status'] === 'Blocked' ? '<span class="badge rounded-pill bg-danger">' . $account['status'] . '</span>' : '<span class="badge rounded-pill bg-primary">' . $account['status'] . '</span>';

			// this data will be rendered to the datatable
			$row[] = $account['user_id'];
			$row[] = $account['full_name'];
			$row[] = $custom_user_role;
			$row[] = $account['username'];
			$row[] = $custom_switch;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->accounts_model->count_all(),
			"recordsFiltered" => $this->accounts_model->count_filtered(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	private function _hash_password($password) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		return $hashed_password;
	}

	// Start of Register User Account
	function register_user_account_validation() {
		$this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
		$emp_id = $this->input->post('emp-id') != '' ? $this->input->post('emp-id') : 'n/a';
		$user_role = $this->input->post('user-role');
		switch (true) {
			case ($user_role === 'healthcare-provider'):
				$this->form_validation->set_rules('full-name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('user-role', 'User Role', 'trim|required');
				$this->form_validation->set_rules('dsg-hcare-prov', 'Designated HealthCare Provider', 'required');
				$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[20]|callback_check_username_exist');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'full_name_error' =>  form_error('full-name'),
						'user_role_error' =>  form_error('user-role'),
						'dsg_hcare_prov_error' =>  form_error('dsg-hcare-prov'),
						'username_error' => form_error('username'),
						'password_error' => form_error('password'),
					];
					echo json_encode($response);
				} else {
					$this->insert_user_account($emp_id, $input_post);
				}
				break;
			case ($user_role !== 'healthcare-provider'):
				$this->form_validation->set_rules('full-name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('user-role', 'User Role', 'trim|required');
				$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|max_length[20]|callback_check_username_exist');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'full_name_error' =>  form_error('full-name'),
						'user_role_error' =>  form_error('user-role'),
						'username_error' => form_error('username'),
						'password_error' => form_error('password'),
					];
					echo json_encode($response);
				} else {
					$this->insert_user_account($emp_id, $input_post);
				}
				break;
		}
	}
	// End of Register User Account

	function insert_user_account($emp_id, $input_post) {
		$post_data = [
			'emp_id' => $emp_id,
			'full_name' => ucwords(strip_tags($input_post['full-name'])),
			'user_role' =>  $input_post['user-role'],
			'dsg_hcare_prov' => $input_post['dsg-hcare-prov'],
			'username' => strip_tags($input_post['username']),
			'password' =>  $this->_hash_password($input_post['password']),
			'status' => 'Active',
			'created_on' => date("Y-m-d"),
		];
		$saved = $this->accounts_model->db_insert_account($post_data);
		if (!$saved) {
			$response = [
				'status' => 'save-error',
				'message' => 'User Account Saved Failed'
			];
		}
		$response = [
			'status' => 'success', 
			'message' => 'User Account Saved Successfully'
		];
		echo json_encode($response);
	}

	function check_username_exist($username) {
		$exists = $this->accounts_model->db_check_username($username);
		if (!$exists) {
			return true;
		} else {
			$this->form_validation->set_message('check_username_exist', 'Username already taken, Please try Another!');
			return false;
		}
	}

	function get_user_account_details() {
		$user_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->accounts_model->db_get_user_by_id($user_id);
		// get user designated hospital if exist
		if ($row['dsg_hcare_prov'] === '' || $row['dsg_hcare_prov'] === null) {
			$dsg_care_prov = 'N/A';
		} else {
			$dsg = $this->accounts_model->db_get_designated_healthcare_provider($row['dsg_hcare_prov']);
			$dsg_care_prov = $dsg['hp_name'];
		}
		// get user photo if exist
		if ($row['emp_id'] == '' || $row['emp_id'] == null) {
			$photo = '';
		} else {
			$user = $this->accounts_model->db_get_user_photo($row['emp_id']);
			$photo = $user['photo'];
		}

		$emp_id = $row['emp_id'] ?: 'None';
		$user_role = str_replace("-", " ", $row['user_role']);
		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'photo' => $photo,
			'emp_id' => $emp_id,
			'full_name' => $row['full_name'],
			'user_role' => ucwords($user_role),
			'dsg_hcare_prov' => $dsg_care_prov,
			'username' => $row['username'],
			'created_on' => date("F d, Y", strtotime($row['created_on'])),
			'updated_on' => date("F d, Y", strtotime($row['updated_on'])),
		];
		echo json_encode($response);
	}

	function edit_user_account_details() {
		$user_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->accounts_model->db_get_user_by_id($user_id);
		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'user_id' =>  $this->myhash->hasher($row['user_id'], 'encrypt'),
			'full_name' => $row['full_name'],
			'user_role' => $row['user_role'],
			'dsg_hcare_prov' => $row['dsg_hcare_prov'],
			'username' => $row['username'],
			'created_on' => date("F d, Y", strtotime($row['created_on'])),
			'updated_on' => date("F d, Y", strtotime($row['updated_on'])),
		];
		echo json_encode($response);
	}


	function update_user_account_validation() {
		/* The below code is a validation for the user account update. */
		$this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		$user_id = $this->myhash->hasher($this->input->post('user-id'), 'decrypt');
		$user_role = $this->input->post('user-role');
		switch (true) {
			case ($user_role === 'healthcare-provider'):
				$this->form_validation->set_rules('full-name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('user-role', 'User Role', 'trim|required');
				$this->form_validation->set_rules('dsg-hcare-prov', 'Designated HealthCare Provider', 'required');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'full_name_error' =>  form_error('full-name'),
						'user_role_error' =>  form_error('user-role'),
						'dsg_hcare_prov_error' =>  form_error('dsg-hcare-prov'),
					];
					echo json_encode($response);
				} else {
					$this->db_update_user_account($user_id, $input_post);
				}
				break;
			case ($user_role !== 'healthcare-provider'):
				$this->form_validation->set_rules('full-name', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('user-role', 'User Role', 'trim|required');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'full_name_error' =>  form_error('full-name'),
						'user_role_error' =>  form_error('user-role'),
					];
					echo json_encode($response);
				} else {
					$this->db_update_user_account($user_id, $input_post);
				}
				break;
		}
	}

	function db_update_user_account($user_id, $input_post) {
		/* Updating the user details in the database. */
		$post_data = [
			'full_name' => ucwords($input_post['full-name']),
			'user_role' =>  $input_post['user-role'],
			'dsg_hcare_prov' => $input_post['dsg-hcare-prov'],
			'updated_on' => date("Y-m-d"),
			'updated_by' => $this->session->userdata('fullname')
		];
		$updated = $this->accounts_model->update_user_details($user_id, $post_data);
		if (!$updated) {
			$response = [
				'status' => 'save-error', 
				'message' => 'User Account Update Failed'
			];
		}
		$response = [
			'status' => 'success', 
			'message' => 'User Account Updated Successfully'
		];
		echo json_encode($response);
	}


	function change_user_account_status() {
		$token = $this->security->get_csrf_hash();
		$user_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$this->load->model('healthcare_coordinator/accounts_model');
		$res = $this->accounts_model->db_get_current_user_status($user_id);
		if ($res['status'] === 'Blocked') {
			$activated = $this->accounts_model->db_activate_user_status($user_id);
			if (!$activated) {
				$response = [
					'token' => $token, 
					'status' => 'save-error', 
					'message' => 'User Account Activate Failed'
				];
			}
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'User Account Activated Successfully'
			];
		} else {
			$deactivated = $this->accounts_model->db_deactivate_user_status($user_id);
			if (!$deactivated) {
				$response = [
					'token' => $token, 
					'status' => 'save-error', 
					'message' => 'User Account Deactivate Failed'
				];
			}
			$response = [
				'token' => $token, 
				'status' => 'success', 
				'message' => 'User Account Deactivated Successfully'
			];
		}
		echo json_encode($response);
	}

	function reset_user_password() {
		$token = $this->security->get_csrf_hash();
		$user_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$default_password = $this->config->item('def_user_password');
		$new_password = $this->_hash_password($default_password);
		$post_data = [
			'password' => $new_password,
			'updated_on' => date("Y-m-d"),
			'updated_by' => $this->session->userdata('fullname')
		];
		$resetted = $this->accounts_model->db_reset_user_password($user_id, $post_data);
		if (!$resetted) {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'Password Reset Failed'
			];
		}
		$response = [
			'token' => $token, 
			'status' => 'success', 
			'message' => 'Password Reset Successfully'
		];
		echo json_encode($response);
	}

	function delete_user_account() {
		$token = $this->security->get_csrf_hash();
		$user_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$deleted = $this->accounts_model->db_delete_user_account($user_id);
		if (!$deleted) {
			$response = [
				'token' => $token, 
				'status' => 'error', 
				'message' => 'User Account Delete Failed'
			];
		}
		$response = [
			'token' => $token, 
			'status' => 'success', 
			'message' => 'User Account Deleted Successfully'
		];
		echo json_encode($response);
	}
}
