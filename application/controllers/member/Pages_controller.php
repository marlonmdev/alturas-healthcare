<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/loa_model');
		$this->load->model('member/noa_model');
		$this->load->model('member/get_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'member') {
			redirect(base_url());
		}
	}

	function index() {
		$this->load->model('member/count_model');
		$this->load->model('member/mbl_model');
		$emp_id = $this->session->userdata('emp_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['pending_loa_count'] = $this->count_model->count_all_pending_loa($emp_id);
		$data['pending_noa_count'] = $this->count_model->count_all_pending_noa($emp_id);
		$data['doctors'] = $this->get_model->db_get_company_doctors();
		$data['mbl'] = $this->mbl_model->get_member_mbl($emp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function healthcare_providers() {
		$this->load->model('member/count_model');
		$this->load->model('member/get_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals_count'] = $this->count_model->count_all_hospitals();
		$data['labs_count'] = $this->count_model->count_all_laboratories();
		$data['clinics_count'] = $this->count_model->count_all_clinics();
		$data['hospitals'] = $this->get_model->db_get_hospitals();
		$data['labs'] = $this->get_model->db_get_laboratories();
		$data['clinics'] = $this->get_model->db_get_clinics();
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/healthcare_providers/healthcare_providers');
		$this->load->view('templates/footer');
	}

	function hmo_policy() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/hmo_policy/policy');
		$this->load->view('templates/footer');
	}

	function request_loa_form() {
		$emp_id = $this->session->userdata('emp_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['doctors'] = $this->loa_model->db_get_company_doctors();
		$data['costtypes'] = $this->loa_model->db_get_cost_types();
		$data['member'] = $this->loa_model->db_get_member_infos($emp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/loa/request_loa_form');
		$this->load->view('templates/footer');
	}


	function request_noa_form() {
		$emp_id = $this->session->userdata('emp_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->noa_model->db_get_all_hospitals();
		$data['member'] = $this->loa_model->db_get_member_infos($emp_id);
		$data['costtypes'] = $this->noa_model->db_get_all_cost_types();
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/noa/request_noa_form');
		$this->load->view('templates/footer');
	}

	function pending_requested_loa() {
		$emp_id = $this->session->userdata('emp_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->get_model->db_get_all_affiliate_hospitals();
		$data['member'] = $this->loa_model->db_get_member_infos($emp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/loa/pending_loa');
		$this->load->view('templates/footer');
	}

	function approved_requested_loa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/loa/approved_loa');
		$this->load->view('templates/footer');
	}

	function disapproved_requested_loa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/loa/disapproved_loa');
		$this->load->view('templates/footer');
	}

	function completed_requested_loa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/loa/completed_loa');
		$this->load->view('templates/footer');
	}

	function pending_requested_noa() {
		$emp_id = $this->session->userdata('emp_id');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->noa_model->db_get_all_hospitals();
		$data['member'] = $this->loa_model->db_get_member_infos($emp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/noa/pending_noa');
		$this->load->view('templates/footer');
	}

	function approved_requested_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/noa/approved_noa');
		$this->load->view('templates/footer');
	}

	function disapproved_requested_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/noa/disapproved_noa');
		$this->load->view('templates/footer');
	}

	function completed_requested_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/noa/completed_noa');
		$this->load->view('templates/footer');
	}

	function unpaid_personal_charges() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/personal_charges/unpaid_personal_charges');
		$this->load->view('templates/footer');
	}

	function paid_personal_charges() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/personal_charges/paid_personal_charges');
		$this->load->view('templates/footer');
	}

	function user_profile() {
		$emp_id = $this->session->userdata('emp_id');
		$this->load->model('member/account_model');
		$this->load->model('member/mbl_model');
		$data['page_title'] = 'HMO - HealthCare Coordinator';
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $this->account_model->db_get_user_details($emp_id);
		$data['mbl'] = $this->mbl_model->get_member_mbl($emp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('member_panel/dashboard/user_profile');
		$this->load->view('templates/footer');
	}
}
