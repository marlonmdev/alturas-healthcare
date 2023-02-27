<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applicants_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/applicants_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function fetch_all_pending_members() {
		$this->security->get_csrf_hash();
		$list = $this->applicants_model->get_datatables();
		$data = [];
		foreach ($list as $member) {
			$row = array();
			$employee_id = $member['emp_id'];
			$member_id = $this->myhash->hasher($member['app_id'], 'encrypt');
			// split employee id number through the dash(-) separator
			$exploded = preg_split('/-(?=[0-9])/', $employee_id, 2);
			$emp_no = $exploded[0];
			$emp_year = $exploded[1];

			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'healthcare-coordinator/member/view/applicant/' . $member_id;

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">Pending</span></div>';

			$custom_actions = '<a href="' . $view_url . '" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Member Details"><i class="bx bxs-user-detail"></i></a>';
			$custom_actions .= '<button class="btn btn-sm btn-warning" onclick="showCreateUserAccount(\'' . $emp_no . '\', \'' . $emp_year . '\')" data-bs-toggle="tooltip" title="Create Member Account"><i class="bx bxs-key"></i></button>';

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

		$result = $this->applicants_model->db_get_applicant($emp_id);
		if (!$result) {
			$response = [
				'status' => 'save-error', 
				'message' => 'Applicant Does Not Exist!'
			];
		} else {
			// get all the applicant's details
			$app_id = $result['app_id'];
			$emp_no = $result['emp_no'];
			$first_name = $result['first_name'];
			$middle_name = $result['middle_name'];
			$last_name = $result['last_name'];
			$suffix = $result['suffix'];
			$gender = $result['gender'];
			$civil_status = $result['civil_status'];
			$spouse = $result['spouse'];
			$date_of_birth = $result['date_of_birth'];
			$home_address = $result['home_address'];
			$city_address = $result['city_address'];
			$contact_no = $result['contact_no'];
			$email = $result['email'];
			$position = $result['position'];
			$position_level = $result['position_level'];
			$emp_type = $result['emp_type'];
			$current_status = $result['current_status'];
			$business_unit = $result['business_unit'];
			$dept_name = $result['dept_name'];
			$blood_type = $result['blood_type'];
			$height = $result['height'];
			$weight = $result['weight'];
			$allergies = $result['allergies'];
			$philhealth_no = $result['philhealth_no'];
			$contact_person = $result['contact_person'];
			$contact_person_addr = $result['contact_person_addr'];
			$contact_person_no = $result['contact_person_no'];
			$date_regularized =  $result['date_regularized'];
			$company = $result['company'];
			$date_approved = date("Y-m-d");
			$approval_status = 'Approved';
			$photo = $result['photo'];

			// this data will be inserted to user accounts table
			$post_data = [
				'emp_id' => $emp_id,
				'full_name' => $first_name . ' ' . $middle_name . ' ' . $last_name . ' ' . $suffix,
				'user_role' => 'member',
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
				// after inserting user account, insert the applicants details to members table
				$member_data = [
					'emp_id' => $emp_id,
					'emp_no' => $emp_no,
					'health_card_no' => $healthcard_no,
					'first_name' => $first_name,
					'middle_name' => $middle_name,
					'last_name' => $last_name,
					'suffix' => $suffix,
					'gender' => $gender,
					'civil_status' => $civil_status,
					'spouse' => $spouse,
					'date_of_birth' => $date_of_birth,
					'home_address' => $home_address,
					'city_address' => $city_address,
					'contact_no' => $contact_no,
					'email' => $email,
					'position' => $position,
					'position_level' => $position_level,
					'emp_type' => $emp_type,
					'current_status' => $current_status,
					'business_unit' => $business_unit,
					'dept_name' => $dept_name,
					'blood_type' => $blood_type,
					'height' => $height,
					'weight' => $weight,
					'allergies' => $allergies,
					'philhealth_no' => $philhealth_no,
					'contact_person' => $contact_person,
					'contact_person_addr' => $contact_person_addr,
					'contact_person_no' => $contact_person_no,
					'date_regularized' => $date_regularized,
					'company' => $company,
					'date_approved' => $date_approved,
					'approval_status' => $approval_status,
					'photo' => $photo,
				];

				$approved = $this->applicants_model->db_insert_member($member_data);
				if (!$approved) {
					$response = [
						'status' => 'error', 
						'message' => 'Unable to Update Member Status'
					];
				} else {
					// if inserted to members table delete from applicants table
					$this->applicants_model->db_delete_applicant($app_id);

					// if approved insert the members max benefit limit based on months from day of regularization and current position level
					$year_regular = date("Y", strtotime($date_regularized));
					$current_year = date("Y");
					if ($year_regular == $current_year) {
						$current_mbl = $this->new_regular_mbl($date_regularized);
					} else {
						$current_mbl = $this->max_benefit_limit($position_level);
					}

					$post_data = [
						'emp_id' => $emp_id,
						'max_benefit_limit' => $current_mbl,
						'used_mbl'          => 0,
						'remaining_balance' => $current_mbl,
					];

					$has_MBL = $this->applicants_model->db_insert_max_benefit_limit($post_data);
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

	function view_applicant_info() {
		$app_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $member = $this->applicants_model->db_get_applicant_details($app_id);
		$data['mbl'] = $this->applicants_model->db_get_member_mbl($member['emp_id']);

		/* This is checking if the image file exists in the directory. */
		$file_path = './uploads/profile_pics/' . $member['photo'];
		$data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/member_profile');
		$this->load->view('templates/footer');
	}
}
