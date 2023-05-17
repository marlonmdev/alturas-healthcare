<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/count_model');
		$this->load->model('healthcare_provider/hospital_model');
		$this->load->model('ho_accounting/List_model');
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
		$data['total_patient'] = $this->count_model->total_patient($hp_id);
		$data['hp_id'] = $hp_id;
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

	function billed_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/loa/billed_loa_list');
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

	function billed_noa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/noa/billed_noa_list');
		$this->load->view('templates/footer');
	}

	// function upload_textfile_form() {
	// 	$data['user_role'] = $this->session->userdata('user_role');
	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('healthcare_provider_panel/billing/upload_textfile');
	// 	$this->load->view('templates/footer');
	// }
	function upload_textfile_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		// $data['payment_no'] = $this->uri->segment(4);
		// $data['hp_name'] =$this->List_model->get_billed_hp_name($this->uri->segment(4));
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/upload_textfile.php', $hc_provider);
		$this->load->view('templates/footer');
	}
}
