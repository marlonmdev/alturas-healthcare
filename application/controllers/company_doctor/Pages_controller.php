<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/members_model');
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
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/pending_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_approved_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/approved_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_disapproved_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/disapproved_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_completed_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/completed_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_referral_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/referral_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_expired_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/expired_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_cancelled_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/loa/cancelled_loa_requests');
		$this->load->view('templates/footer');
	}

	public function view_pending_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/pending_noa_requests');
		$this->load->view('templates/footer');
	}

	public function view_approved_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/noa/approved_noa_requests');
		$this->load->view('templates/footer');
	}

	public function view_disapproved_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
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

	function view_employee_files() {
		$this->load->model('healthcare_coordinator/noa_model');
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/view_employee_files');
		$this->load->view('templates/footer');
	}

	function view_member_files() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/show_member_files');
		$this->load->view('templates/footer');
	}

	function view_medical_abstract() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/show_medical_abstract');
		$this->load->view('templates/footer');
	}

	function view_take_home_meds() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/show_take_home_meds');
		$this->load->view('templates/footer');
	}

	function view_billed_soa() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/show_billed_soa');
		$this->load->view('templates/footer');
	}
}
