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
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$data['user_role'] = $this->session->userdata('user_role');
		$row = $this->List_model->get_hcare_provider($hp_id);
		//$data['hp_name'] = $row['hp_name'];
		$data['loa_count'] = $this->List_model->hp_approved_loa_count($hp_id);
		$data['noa_count'] = $this->List_model->hp_approved_noa_count($hp_id);
		$data['bllled_count'] = $this->List_model->hp_done_billing_count($hp_id);
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
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/loa_billing_list/completed_loa_list.php');
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
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/noa_billing_list/completed_noa_list.php');
		$this->load->view('templates/footer');
    }

}