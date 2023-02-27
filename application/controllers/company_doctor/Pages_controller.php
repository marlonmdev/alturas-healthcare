<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

	public function index() {
		$this->load->model('company_doctor/count_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcare_prov_count'] = $this->count_model->count_all_healthcare_providers();
		$data['members_count'] = $this->count_model->count_all_members();
		$data['pending_loa_count'] = $this->count_model->count_all_pending_loa();
		$data['pending_noa_count'] = $this->count_model->count_all_pending_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	public function view_healthcare_providers() {
		$this->load->model('company_doctor/setup_model');
		$this->load->model('company_doctor/count_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals_count'] = $this->count_model->count_all_hospitals();
		$data['labs_count'] = $this->count_model->count_all_laboratories();
		$data['clinics_count'] = $this->count_model->count_all_clinics();
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['labs'] = $this->setup_model->db_get_laboratories();
		$data['clinics'] = $this->setup_model->db_get_clinics();
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/healthcare_providers/healthcare_providers');
		$this->load->view('templates/footer');
	}

	public function view_all_members() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/members');
		$this->load->view('templates/footer');
	}

	public function view_pending_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/pending_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_approved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/approved_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_disapproved_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/disapproved_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_completed_loa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/completed_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_pending_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/pending_noa_requests');
		$this->load->view('templates/footer');
	}

	public function view_approved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/approved_noa_requests');
		$this->load->view('templates/footer');
	}

	public function view_disapproved_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/disapproved_noa_requests');
		$this->load->view('templates/footer');
	}

	public function view_completed_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/completed_noa_requests');
		$this->load->view('templates/footer');
	}
}
