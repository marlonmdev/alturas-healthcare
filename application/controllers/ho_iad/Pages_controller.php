<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
       $this->load->model('ho_iad/transaction_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-iad') {
            redirect(base_url());
        }
    }

    function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['doctors'] = $this->transaction_model->db_get_company_doctors();
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
		$this->load->view('ho_iad_panel/billing/record');
		$this->load->view('templates/footer');
	}

	function view_billing_list() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_of_billing');
		$this->load->view('templates/footer');
	}

	function view_for_audit() {
		$data['payment_no'] = $payment_no = $this->uri->segment(4);
		$data['pay'] = $this->transaction_model->get_billed_date($payment_no);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_for_audit');
		$this->load->view('templates/footer');
	}

	function view_audited() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_of_audited_bill');
		$this->load->view('templates/footer');
	}

	function view_audited_list() {
		$data['payment_no'] = $payment_no = $this->uri->segment(4);
		$data['pay'] = $this->transaction_model->get_billed_date($payment_no);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_of_audited');
		$this->load->view('templates/footer');
	}

	function view_paid_bill() {
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_of_paid_bill');
		$this->load->view('templates/footer');
	}
	
	function view_paid_list() {
		$data['payment_no'] = $payment_no = $this->uri->segment(4);
		$data['pay'] = $this->transaction_model->get_billed_date($payment_no);
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/transaction/list_of_paid');
		$this->load->view('templates/footer');
	}

}