<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/members_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function fetch_all_pending_members() {
		$this->security->get_csrf_hash();
		$this->load->model('healthcare_coordinator/applicants_model');
		$list = $this->applicants_model->get_datatables();
		$data = [];
		foreach ($list as $member) {
			$row = [];
			$employee_id = $member['emp_id'];
			$app_id = $this->myhash->hasher($member['app_id'], 'encrypt');

			// split employee id number through the dash(-) separator
			$exploded = preg_split('/-(?=[0-9])/', $employee_id, 2);
			$emp_no = $exploded[0];
			$emp_year = $exploded[1];

			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'healthcare-coordinator/members/view/applicant/' . $app_id;

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">Pending</span></div>';

			$custom_actions = '<a class="me-2" href="' . $view_url . '" data-bs-toggle="tooltip" title="View Member Profile"><i class="mdi mdi-account-card-details fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showCreateUserAccount(\'' . $emp_no . '\', \'' . $emp_year . '\')" data-bs-toggle="tooltip" title="Create Member Account"><i class="mdi mdi-account-plus fs-2 text-primary"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $member['app_id'];
			$row[] = $full_name;
			$row[] = $member['emp_type'];
			$row[] = $member['current_status'];
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->applicants_model->count_all(),
			"recordsFiltered" => $this->applicants_model->count_filtered(),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_approved_members() {
		$this->security->get_csrf_hash();
		$approval_status = 'Approved';
		$list = $this->members_model->get_datatables($approval_status);
		$data = [];
		foreach ($list as $member) {
			$row = [];
			$member_id = $this->myhash->hasher($member['member_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'healthcare-coordinator/members/view/' . $member_id;

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $member['approval_status'] . '</span></div>';

			$custom_actions = '<a href="' . $view_url . '" data-bs-toggle="tooltip" title="View Member Profile"><i class="mdi mdi-account-card-details fs-2 text-info"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $member['member_id'];
			$row[] = $full_name;
			$row[] = $member['emp_type'];
			$row[] = $member['current_status'];
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->members_model->count_all($approval_status),
			"recordsFiltered" => $this->members_model->count_filtered($approval_status),
			"data" => $data,
		];
		echo json_encode($output);
	}


	private function _hash_password($password) {
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		return $hashed_password;
	}

	function create_member_user_account() {
		$this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp-id');
		$healthcard_no = $this->input->post('healthcard-no');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$result = $this->members_model->db_get_member_details($emp_id);
		if (!$result) {
			$response = [
				'status' => 'save-error', 
				'message' => 'Member Does Not Exist!'
			];
		} else {
			$first_name = $result['first_name'];
			$middle_name = $result['middle_name'];
			$last_name = $result['last_name'];
			$suffix = $result['suffix'];
			$contact_no = $result['contact_no'];
			$email = $result['email'];
			$date_regularized =  $result['date_regularized'];
			$pos_level =  $result['position_level'];

			$post_data = [
				'emp_id' => $emp_id,
				'full_name' => $first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $suffix,
				'mobile_number' => $contact_no,
				'email' => $email,
				'user_role' =>  'member',
				'username' => $username,
				'password' =>  $this->_hash_password($password),
				'status' => 'Active',
				'created_on' => date("Y-m-d")
			];
			$this->load->model('healthcare_coordinator/accounts_model');
			$saved = $this->accounts_model->db_insert_account($post_data);
			if (!$saved) {
				$response = [
					'status' => 'save-error', 
					'message' => 'User Account Create Failed!'
				];
			} else {
				// after inserting user account, update the members healthcard number and approval status to approved
				$date_approved = date("Y-m-d");
				$approved = $this->members_model->db_update_member_status($emp_id, $healthcard_no, $date_approved);
				if (!$approved) {
					$response = [
						'status' => 'error', 
						'message' => 'Unable to Update Member Status'
					];
				} else {
					// if approved insert the members max benefit limit based on months from day of regularization and current position level
					$year_regular = date("Y", strtotime($date_regularized));
					$current_year = date("Y");
					if ($year_regular === $current_year) {
						$current_mbl = $this->new_regular_mbl($date_regularized);
					} else {
						$current_mbl = $this->max_benefit_limit($pos_level);
					}

					$post_data = [
						'emp_id' => $emp_id,
						'max_benefit_limit' => $current_mbl,
						'remaining_balance' => $current_mbl,
					];

					$has_MBL = $this->members_model->db_insert_max_benefit_limit($post_data);
					if (!$has_MBL) {
						$response = [
							'status' => 'error', 
							'message' => 'Unable to Update Members Maximum Benefit Limit ' . $first_name . ' ' . $last_name . ' ' . $suffix
						];
					} else {
						$response = [
							'status' => 'success', 
							'message' => 'User Account Created for ' . $first_name . ' ' . $last_name . ' ' . $suffix
						];
					}
				}
			}
		}
		echo json_encode($response);
	}

	function new_regular_mbl($date_arg) {
		$regularized_month = date("m", strtotime($date_arg));
		$last_month_of_year = 12;
		$months_diff = $last_month_of_year - $regularized_month;
		$mbl_level_5 = 30000;
		$max_benefit_limit = ($mbl_level_5 / 12) * $months_diff;
		return $max_benefit_limit;
	}

	function max_benefit_limit($pos_level) {
		if ($pos_level <= 6) {
			$max_benefit_limit = 30000;
		} else if ($pos_level <= 9 && $pos_level > 6) {
			$max_benefit_limit = 50000;
		} else if ($pos_level > 9) {
			$max_benefit_limit = 100000;
		}
		return $max_benefit_limit;
	}

	function check_username_exist($username) {
		$this->load->model('healthcare_coordinator/accounts_model');
		$exists = $this->accounts_model->db_check_username($username);
		if (!$exists) {
			return true;
		} else {
			$this->form_validation->set_message('check_username_exist', 'Username already taken, Please try Another!');
			return false;
		}
	}

	function view_member_info() {
		$member_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $member = $this->members_model->db_get_member_details($member_id);
		$data['mbl'] = $this->members_model->db_get_member_mbl($member['emp_id']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/member_profile');
		$this->load->view('templates/footer');
	}
}
