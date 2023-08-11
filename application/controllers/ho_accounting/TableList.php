<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TableList extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ho_accounting/List_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-accounting') {
            redirect(base_url());
        }
    }

    function index() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $data_options['options'] = $this->List_model->get_hc_provider();
        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/billing_list_table/unbilled_table', $data_options);
        $this->load->view('templates/footer');
    }

    function billed_record(){
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $data_options['options'] = $this->List_model->get_hc_provider();
        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/billing_list_table/billed_table', $data_options);
        $this->load->view('templates/footer');
    }

    function closed_record(){
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $data_options['options'] = $this->List_model->get_hc_provider();
        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/billing_list_table/closed_table', $data_options);
        $this->load->view('templates/footer');
    }

    function searchTableList() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $search = $this->input->post('search');
        $payloadLoa =  $this->List_model->billing_search($search);

        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/billing_list_table/unbilled_table',  array('payloadLoa' => $payloadLoa));
        $this->load->view('templates/footer');
    }

    function listByHopital() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $data['user_role'] = $this->session->userdata('user_role');

        $month = date("m", strtotime($this->uri->segment(5)));
        $year = date("Y", strtotime($this->uri->segment(5)));
        $idHospital = $this->uri->segment(4);

        $payloadBilling =  $this->List_model->getInHospitalDate($idHospital, $month, $year);
        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/billing_list_table/listByHopital',  array('payloadBilling' => $payloadBilling));
        $this->load->view('templates/footer');
    }

    function listInfoSummary() {
        $this->security->get_csrf_hash();
        $payloadLoa =  $this->List_model->loa_member();
        $this->load->view('templates/header');
        $this->load->view('ho_accounting_panel/billing_list_table/listInfoSummary',  array('payloadLoa' => $payloadLoa));
        $this->load->view('templates/footer');
    }
}
