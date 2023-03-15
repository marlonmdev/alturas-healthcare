<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // $this->load->model('ho_iad/Loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-iad') {
            redirect(base_url());
        }
    }

    function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	function view_table() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/billing/table');
		$this->load->view('templates/footer');
	}

    function view_billed_loa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/billing/billed_loa');
		$this->load->view('templates/footer');
	}
    function view_billed_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/billing/billed_noa');
		$this->load->view('templates/footer');
	}

}