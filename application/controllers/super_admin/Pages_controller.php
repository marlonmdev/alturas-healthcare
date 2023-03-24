<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'super-admin') {
			redirect(base_url());
		}
	}

	function index() {
		$this->load->model('super_admin/count_model');
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
		$this->load->model('super_admin/setup_model');
		$this->load->model('super_admin/count_model');
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

	function view_all_accounts() {
		$this->load->model('super_admin/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/user_accounts/accounts');
		$this->load->view('templates/footer');
	}

	function register_account_form() {
		$this->load->model('super_admin/setup_model');
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
		$this->load->model('super_admin/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['price_group'] = $this->setup_model->get_price_group();
		$data['hospital'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/cost_types');
		$this->load->view('templates/footer');
	}

	function view_all_room_types() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/setup/room_types');
		$this->load->view('templates/footer');
	}

	function view_request_loa_form() {
		$this->load->model('super_admin/setup_model');
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
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/pending_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_approved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/approved_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_disapproved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/disapproved_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_completed_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/loa/completed_loa_requests');
		$this->load->view('templates/footer');
	}

	function request_noa_form() {
		$this->load->model('super_admin/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/request_noa_form');
		$this->load->view('templates/footer');
	}

	function view_pending_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/pending_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_approved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/approved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_disapproved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/disapproved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_completed_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('super_admin_panel/noa/completed_noa_requests');
		$this->load->view('templates/footer');
	}
}
