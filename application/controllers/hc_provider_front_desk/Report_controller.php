<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/Loa_model');
        $this->load->model('healthcare_provider/Noa_model');
        $this->load->model('healthcare_provider/Billing_model');
        $this->load->model('healthcare_provider/Member_profile_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'hc-provider-front-desk') {
            redirect(base_url());
        }
    }

    function report_list() {
        $data['page_title'] = 'Alturas HealthCare - HealthCare Provider';
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $loaPendingCount = $this->Loa_model->loa_member_approved($userHospital);
        $noaPendingCount = $this->Noa_model->noa_member_approved($userHospital);
        $billingCount = $this->Billing_model->billing_count($userHospital);

        $data['billingCount'] = count($billingCount);
        $data['loa_pending_count'] = count($loaPendingCount);
        $data['noa_pending_count'] = count($noaPendingCount);
        $this->load->view('templates/header', $data);
        $this->load->view('hc_provider_front_desk_panel/report/reportList.php');
        $this->load->view('templates/footer');
    }
}
