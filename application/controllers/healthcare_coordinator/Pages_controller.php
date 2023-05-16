<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/members/healthcard_id_monitoring');
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


	function view_pending_loa_list() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/pending_loa_requests');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/approved_loa_requests');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/disapproved_loa_requests');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/expired_loa_requests');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/cancelled_loa_requests');
		$this->load->view('templates/footer');
	}

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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/completed_loa_requests');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/referred_loa_requests');
		$this->load->view('templates/footer');
	}

	function view_all_billed_loa() {
		$this->load->model('healthcare_coordinator/loa_model');
		$data['hcproviders'] = $this->loa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->loa_model->bar_pending();
		$data['bar1'] = $this->loa_model->bar_approved();
		$data['bar2'] = $this->loa_model->bar_completed();
		$data['bar3'] = $this->loa_model->bar_referral();
		$data['bar4'] = $this->loa_model->bar_expired();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/billed_loa');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/for_payment_loa');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/loa/ledger');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/request_noa_form');
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

	function view_billed_no() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/billed_noa');
		$this->load->view('templates/footer');
	}

	function view_for_payment_noa() {
		$this->load->model('healthcare_coordinator/noa_model');
		$data['hcproviders'] = $this->noa_model->db_get_healthcare_providers();
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bar'] = $this->noa_model->bar_pending();
		$data['bar1'] = $this->noa_model->bar_approved();
		$data['bar2'] = $this->noa_model->bar_completed();
		$data['bar3'] = $this->noa_model->bar_referral();
		$data['bar4'] = $this->noa_model->bar_expired();
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/for_payment_noa');
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
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_coordinator_panel/noa/initial_billing');
		$this->load->view('templates/footer');
	}
}
