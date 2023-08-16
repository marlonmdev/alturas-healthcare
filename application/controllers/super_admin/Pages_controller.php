<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('super_admin/members_model');
		$this->load->model('super_admin/setup_model');
		$this->load->model('super_admin/count_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'super-admin') {
			redirect(base_url());
		}
	}

	function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcare_prov_count'] = $this->count_model->count_all_healthcare_providers();
		$data['members_count'] = $this->count_model->count_all_members();
		$data['pending_loa_count'] = $this->count_model->count_all_pending_loa();
		$data['pending_noa_count'] = $this->count_model->count_all_pending_noa();
		$data['doctors'] = $this->count_model->db_get_company_doctors();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function view_healthcare_providers() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals_count'] = $this->count_model->count_all_hospitals();
		$data['labs_count'] = $this->count_model->count_all_laboratories();
		$data['clinics_count'] = $this->count_model->count_all_clinics();
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['labs'] = $this->setup_model->db_get_laboratories();
		$data['clinics'] = $this->setup_model->db_get_clinics();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/healthcare_providers/healthcare_providers');
		$this->load->view('templates/footer');
	}

	function view_all_pending_members() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/pending_members');
		$this->load->view('templates/footer');
	}

	function view_all_approved_members() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/approved_members');
		$this->load->view('templates/footer');
	}

	function view_all_done_healthcard_id() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/healthcard_id_approved_members');
		$this->load->view('templates/footer');
	}

	function view_member_files() {
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['hcproviders'] = $this->members_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/view_employee_files');
		$this->load->view('templates/footer');
	}

	function view_diagnosis_files() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/show_member_files');
		$this->load->view('templates/footer');
	}

	function view_medical_abstract() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/show_medical_abstract');
		$this->load->view('templates/footer');
	}

	function view_take_home_meds() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/show_take_home_meds');
		$this->load->view('templates/footer');
	}

	function view_billed_soa() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/show_billed_soa');
		$this->load->view('templates/footer');
	}

	function view_incident_spot_reports() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file_loa'] = $this->members_model->get_employee_files_loa($emp_id);
		$data['file_noa'] = $this->members_model->get_employee_files_noa($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/members/show_incident_spot_reports');
		$this->load->view('templates/footer');
	}

	function view_all_accounts() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/user_accounts/accounts');
		$this->load->view('templates/footer');
	}

	function register_account_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['default_password'] = $this->config->item('default_password');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/user_accounts/register_account_form');
		$this->load->view('templates/footer');
	}

	function view_all_healthcare_providers() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/healthcare_providers');
		$this->load->view('templates/footer');
	}

	function view_all_company_doctors() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/company_doctors');
		$this->load->view('templates/footer');
	}

	function view_all_cost_types() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['price_group'] = $this->setup_model->get_price_group();
		$data['hospital'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/cost_types');
		$this->load->view('templates/footer');
	}

	function view_all_room_types() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospital'] = $this->setup_model->rt_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/room_types');
		$this->load->view('templates/footer');
	}

	function view_request_loa_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$data['doctors'] = $this->setup_model->db_get_company_doctors();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/request_loa_form');
		$this->load->view('templates/footer');
	}


	function view_pending_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/pending_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_approved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/approved_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_disapproved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/disapproved_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_completed_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/completed_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_cancelled_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/cancelled_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_expired_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/expired_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_billed_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/billed_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_paid_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/paid_loa_requests');
		$this->load->view('templates/footer');
	}

	function request_noa_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/request_noa_form');
		$this->load->view('templates/footer');
	}

	function view_pending_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/pending_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_approved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/approved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_disapproved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/disapproved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_billed_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/billed_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_paid_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/paid_noa_requests');
		$this->load->view('templates/footer');
	}
}
