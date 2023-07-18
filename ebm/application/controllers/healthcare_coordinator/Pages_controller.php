<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_coordinator/members_model');
		$this->load->model('healthcare_coordinator/loa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
			redirect(base_url());
		}
	}

	function index() {
		$this->load->model('healthcare_coordinator/count_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcare_prov_count'] = $this->count_model->count_all_healthcare_providers();
		$data['members_count'] = $this->count_model->count_all_members();
		$data['pending_loa_count'] = $this->count_model->count_all_pending_loa();
		$data['pending_noa_count'] = $this->count_model->count_all_pending_noa();
		$data['doctors'] = $this->count_model->db_get_company_doctors();
		$data['bar'] = $this->count_model->bar_pending();
		$data['bar1'] = $this->count_model->bar_approved();
		$data['bar2'] = $this->count_model->bar_completed();
		$data['bar3'] = $this->count_model->bar_referral();
		$data['bar4'] = $this->count_model->bar_expired();
		$data['bar_Billed'] = $this->count_model->bar_billed();
		$data['bar5'] = $this->count_model->bar_pending_noa();
		$data['bar6'] = $this->count_model->bar_approved_noa();
		$data['bar_Initial'] = $this->count_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->count_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function view_healthcare_providers() {
		$this->load->model('healthcare_coordinator/setup_model');
		$this->load->model('healthcare_coordinator/count_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals_count'] = $this->count_model->count_all_hospitals();
		$data['labs_count'] = $this->count_model->count_all_laboratories();
		$data['clinics_count'] = $this->count_model->count_all_clinics();
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['labs'] = $this->setup_model->db_get_laboratories();
		$data['clinics'] = $this->setup_model->db_get_clinics();
		$data['bar'] = $this->count_model->bar_pending();
		$data['bar1'] = $this->count_model->bar_approved();
		$data['bar2'] = $this->count_model->bar_completed();
		$data['bar3'] = $this->count_model->bar_referral();
		$data['bar4'] = $this->count_model->bar_expired();
		$data['bar_Billed'] = $this->count_model->bar_billed();
		$data['bar5'] = $this->count_model->bar_pending_noa();
		$data['bar6'] = $this->count_model->bar_approved_noa();
		$data['bar_Initial'] = $this->count_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->count_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/healthcare_providers/healthcare_providers');
		$this->load->view('templates/footer');
	}

	function view_all_pending_members() {
		$this->load->model('healthcare_coordinator/applicants_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->applicants_model->bar_pending();
		$data['bar1'] = $this->applicants_model->bar_approved();
		$data['bar2'] = $this->applicants_model->bar_completed();
		$data['bar3'] = $this->applicants_model->bar_referral();
		$data['bar4'] = $this->applicants_model->bar_expired();
		$data['bar_Billed'] = $this->applicants_model->bar_billed();
		$data['bar5'] = $this->applicants_model->bar_pending_noa();
		$data['bar6'] = $this->applicants_model->bar_approved_noa();
		$data['bar_Initial'] = $this->applicants_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->applicants_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/create_account');
		$this->load->view('templates/footer');
	}

	function view_all_approved_members() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->model('healthcare_coordinator/loa_model');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/approved_members');
		$this->load->view('templates/footer');
	}

	function healthcard_monitoring() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->model('healthcare_coordinator/loa_model');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/healthcard_id_monitoring');
		$this->load->view('templates/footer');
	}

	function view_member_files() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/viewing_member_files');
		$this->load->view('templates/footer');
	}

	function view_final_diagnosis() {
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/show_member_files');
		$this->load->view('templates/footer');
	}

	function view_medical_abstract() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/show_medical_abstract');
		$this->load->view('templates/footer');
	}

	function view_take_home_meds() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/show_take_home_meds');
		$this->load->view('templates/footer');
	}

	function view_billed_soa() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file'] = $this->members_model->get_employee_files($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/show_billed_soa');
		$this->load->view('templates/footer');
	}

	function view_incident_spot_reports() {
		$data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member_id'] = $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['file_loa'] = $this->members_model->get_employee_files_loa($emp_id);
		$data['file_noa'] = $this->members_model->get_employee_files_noa($emp_id);
		$data['member'] = $this->members_model->db_get_member_details($member_id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/show_incident_spot_reports');
		$this->load->view('templates/footer');
	}

	function view_all_accounts() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/user_accounts/accounts');
		$this->load->view('templates/footer');
	}

	function register_account_form() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['default_password'] = $this->config->item('default_password');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/user_accounts/register_account_form');
		$this->load->view('templates/footer');
	}

	function view_all_healthcare_providers() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->model('healthcare_coordinator/setup_model');
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/setup/healthcare_providers');
		$this->load->view('templates/footer');
	}

	function view_all_company_doctors() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->model('healthcare_coordinator/setup_model');
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/setup/company_doctors');
		$this->load->view('templates/footer');
	}

	function view_all_cost_types() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['price_group'] = $this->setup_model->get_price_group();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospital'] = $this->setup_model->db_get_healthcare_providers();
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/setup/cost_types');
		$this->load->view('templates/footer');
	}

	function view_all_room_types() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospital'] = $this->setup_model->rt_get_healthcare_providers();
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/setup/room_types');
		$this->load->view('templates/footer');
	}

	function view_request_loa_form() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->setup_model->db_get_healthcare_providers();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$data['doctors'] = $this->setup_model->db_get_company_doctors();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/request_loa_form');
		$this->load->view('templates/footer');
	}
	//==================================================
	//Letter of Authorization
	//==================================================
	function view_pending_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_pending');
		$this->load->view('templates/footer');
	}

	function view_approved_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_approved');
		$this->load->view('templates/footer');
	}

	function view_disapproved_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_disapproved');
		$this->load->view('templates/footer');
	}

	function view_expired_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_expired');
		$this->load->view('templates/footer');
	}

	function view_cancelled_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_cancelled');
		$this->load->view('templates/footer');
	}
	//==================================================
	//End
	//==================================================

	//==================================================
	//Emergency of LOA
	//==================================================
	function view_emergency_loa_pending() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/emergency_loa/a_pending');
		$this->load->view('templates/footer');
	}
	//==================================================
	//End
	//==================================================

	function view_loa_cancellation_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_cancellations_requests');
		$this->load->view('templates/footer');
	}

	function view_loa_approved_cancellation() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_approved_cancellations');
		$this->load->view('templates/footer');
	}

	function view_loa_disapproved_cancellation() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_disapproved_cancellations');
		$this->load->view('templates/footer');
	}

	function view_completed_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_completed');
		$this->load->view('templates/footer');
	}

	function view_all_rescheduled_loa() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/loa_referral');
		$this->load->view('templates/footer');
	}

	function view_all_billed_loa() {
		$this->load->model('healthcare_coordinator/loa_model');
		// $data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		// var_dump($data['emp_id']);
		// $token = $this->security->get_csrf_hash();
		// $loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		// $loa = $this->loa_model->get_all_completed_loa($loa_id);
		// var_dump($loa_id);

		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		// $data['loa_requests'] = $this->loa_model->get_data_loa_requests();
		// var_dump($data['loa_requests']);
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/final_billing');
		$this->load->view('templates/footer');
	}

	function view_for_charging_lo() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');

		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/billing_statement');
		$this->load->view('templates/footer');
	}

	function history() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['month'] = $this->loa_model->month();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/history');
		$this->load->view('templates/footer');
	}

	function view_ledger() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/ledger');
		$this->load->view('templates/footer');
	}

	function fetch_ledger() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['emp_id'] = $this->uri->segment(4);
		// $data['emp_id'] = $emp_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		// $data['list'] = $this->loa_model->db_get_all_paid();
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/ledger2');
		$this->load->view('templates/footer');
	}

	function view_healthcare_advance_pending() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/healthcare_advance_pending');
		$this->load->view('templates/footer');
	}

	function view_healthcare_advance_approved() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/healthcare_advance_approved');
		$this->load->view('templates/footer');
	}

	function view_healthcare_advance_disapproved() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$data['bar_Billed'] = $this->loa_model->bar_billed();
		$data['bar5'] = $this->loa_model->bar_pending_noa();
		$data['bar6'] = $this->loa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->loa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->loa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/healthcare_advance_disapproved');
		$this->load->view('templates/footer');
	}

	function request_noa_form() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/request_noa_form');
		$this->load->view('templates/footer');
	}

	function request_emegency_loa_form() {
		$this->load->model('healthcare_coordinator/setup_model');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['hospitals'] = $this->setup_model->db_get_hospitals();
		$data['costtypes'] = $this->setup_model->db_get_all_cost_types();
		$data['bar'] = $this->setup_model->bar_pending();
		$data['bar1'] = $this->setup_model->bar_approved();
		$data['bar2'] = $this->setup_model->bar_completed();
		$data['bar3'] = $this->setup_model->bar_referral();
		$data['bar4'] = $this->setup_model->bar_expired();
		$data['bar_Billed'] = $this->setup_model->bar_billed();
		$data['bar5'] = $this->setup_model->bar_pending_noa();
		$data['bar6'] = $this->setup_model->bar_approved_noa();
		$data['bar_Initial'] = $this->setup_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->setup_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/emergency_loa/request_emerg_form');
		$this->load->view('templates/footer');
	}

	function view_pending_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/pending_noa_requests');
		$this->load->view('templates/footer');
	}
 
	function view_approved_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/approved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_disapproved_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/disapproved_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_expired_noa_list() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/expired_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_completed_noa_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/completed_noa_requests');
		$this->load->view('templates/footer');
	}

	function view_final_billing() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/final_billing');
		$this->load->view('templates/footer');
	}

	function billing_statement() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['status'] = $this->noa_model->processing(); // Fetch the status from the model
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/billing_statement');
		$this->load->view('templates/footer');
	}

	function view_initial_billing() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$data['bar_Billed'] = $this->noa_model->bar_billed();
		$data['bar5'] = $this->noa_model->bar_pending_noa();
		$data['bar6'] = $this->noa_model->bar_approved_noa();
		$data['bar_Initial'] = $this->noa_model->bar_initial_noa();
		$data['bar_Billed2'] = $this->noa_model->bar_billed_noa();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/initial_billing');
		$this->load->view('templates/footer');
	}

	function view_reset_mbl() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/setup/yearly_reset_mbl');
		$this->load->view('templates/footer');
	}
}
