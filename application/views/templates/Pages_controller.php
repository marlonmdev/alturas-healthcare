<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('ho_accounting/List_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-accounting') {
            redirect(base_url());
        }
    }

    function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['billed_count'] = $this->List_model->hp_billed_count();
		$data['payment_count'] = $this->List_model->hp_payment_history_count();
		$data['loa_count'] = $this->List_model->hp_approved_loa_count();
		$data['noa_count'] = $this->List_model->hp_approved_noa_count();

		$bill = $this->List_model->hp_paid_bill();

		foreach($bill as $paid){
			$hp_id = $paid['hp_id'];
			$data['paid_count'] = $this->List_model->hp_count_paid($hp_id);
			$data['hp_name'] = $paid['hp_name'];
		}
		
		// $data_paid = [
		// 	'hp_name' => $paid['hp_name'],
		// 	'paid_count' => $paid,
		// ];

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

    function approved_loa_requests() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/loa_billing_list/approved_loa_list.php', $hc_provider);
		$this->load->view('templates/footer');
	}

    function completed_loa_requests() {
        $data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/loa_billing_list/completed_loa_list.php', $hc_provider);
		$this->load->view('templates/footer');
    }

    function approved_noa_requests() {
        $data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/noa_billing_list/approved_noa_list.php', $hc_provider);
		$this->load->view('templates/footer');
    }

    function completed_noa_requests() {
        $data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/noa_billing_list/completed_noa_list.php', $hc_provider);
		$this->load->view('templates/footer');
    }

	function show_payment_history_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/payment_history.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function unbilled_loa_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/unbilled_loa.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function unbilled_noa_form() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/unbilled_noa.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_billed_loa_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/billed_loa_noa.php', $hc_provider);
		$this->load->view('templates/footer');
	}

}