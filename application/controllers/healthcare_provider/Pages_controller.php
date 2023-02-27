<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/count_model');
		$this->load->model('healthcare_provider/hospital_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
				redirect(base_url());
		}
	}

	function index() {
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$data['user_role'] = $this->session->userdata('user_role');
		$row = $this->hospital_model->get_hcare_provider($hp_id);
		$data['hp_name'] = $row['hp_name'];
		$data['loa_count'] = $this->count_model->hp_approved_loa_count($hp_id);
		$data['noa_count'] = $this->count_model->hp_approved_noa_count($hp_id);
		$data['bllled_count'] = $this->count_model->hp_done_billing_count($hp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function pending_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/loa/pending_loa_list');
		$this->load->view('templates/footer');
	}

	function approved_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/loa/approved_loa_list');
		$this->load->view('templates/footer');
	}

	function disapproved_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/loa/disapproved_loa_list');
		$this->load->view('templates/footer');
	}

	function completed_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/loa/completed_loa_list');
		$this->load->view('templates/footer');
	}

	function pending_noa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/noa/pending_noa_list');
		$this->load->view('templates/footer');
	}


	function approved_noa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/noa/approved_noa_list');
		$this->load->view('templates/footer');
	}


	function disapproved_noa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/noa/disapproved_noa_list');
		$this->load->view('templates/footer');
	}


	function completed_noa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/noa/completed_noa_list');
		$this->load->view('templates/footer');
	}

}
