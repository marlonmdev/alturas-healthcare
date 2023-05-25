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
		$data['user'] = $this->session->userdata('fullname');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['billing'] = $this->List_model->get_for_payment_loa_noa();
		$data['sum'] = $this->List_model->get_sum_billed();
		$data['business_unit'] = $this->List_model->get_business_units();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/print_billed_reports.php', $hc_provider);
		$this->load->view('templates/footer');
	} 

	function view_paid_loa_noa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/paid_loa_noa.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_for_payments() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/for_payment_bills.php', $hc_provider);
		$this->load->view('templates/footer');
	}
	
	function view_payments() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['payment_no'] = $this->uri->segment(4);
		$data['hp_name'] =$this->List_model->get_billed_hp_name($this->uri->segment(4));
		$data['pay'] =$this->List_model->get_billed_date($this->uri->segment(4));
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/view_monthly_bill.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_paid_bill() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['payment_no'] = $this->uri->segment(4);
		$data['hp_name'] =$this->List_model->get_billed_hp_name($this->uri->segment(4));
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/paid_loa_noa.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_monthly_paid_bill() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['payment_no'] = $this->uri->segment(4);
		$data['hp_name'] =$this->List_model->get_billed_hp_name($this->uri->segment(4));
		$data['pay'] =$this->List_model->get_billed_date($this->uri->segment(4));
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/view_monthly_paid_bill.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_generate_reports() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['bu'] = $this->List_model->get_business_units();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/reports/generate_reports.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_cash_advances() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['bu'] = $this->List_model->get_business_units();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/reports/cash_advances_report.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_charging() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hc_provider['hc_provider'] = $this->List_model->get_hc_provider();
		$data['bu'] = $this->List_model->get_business_units();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/reports/charging_report.php', $hc_provider);
		$this->load->view('templates/footer');
	}

	function view_bu_charging() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['bu'] = $this->List_model->get_business_units();
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/business_unit_charging.php');
		$this->load->view('templates/footer');
	}

	function view_charging_details() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['emp_id'] = $empId = $this->uri->segment(4);
		$data['member'] = $this->List_model->get_member_info($empId);
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/charging_details.php');
		$this->load->view('templates/footer');
	}

}