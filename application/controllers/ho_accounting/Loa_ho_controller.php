<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_ho_controller extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/Loa_model');
        $this->load->model('ho_accounting/List_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-accounting') {
            redirect(base_url());
        }
    }

    public function get_all_loa() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->List_model->getLoaClose($userHospital);
        $payLoadData = array();

        foreach ($members as $member) :
            $payLoadMedService = array();
            $expodedMedServices = explode(";", $member->med_services);

            foreach ($expodedMedServices as $extractMedServicesId) :
                $resultMedServices = $this->Loa_model->get_cost_type($extractMedServicesId);
                array_push($payLoadMedService, $resultMedServices);
            endforeach;
            
            $member->med_services = $payLoadMedService;
            array_push($payLoadData, $member);
        endforeach;

        // $this->load->view('templates/header', $data);
        // $this->load->view('healthcare_provider_panel/loa/loaRequestListClosed.php',  array('members' => $payLoadData));
        // $this->load->view('templates/footer');
        //loa_member
        $this->load->view('templates/header', $data);
        $this->load->view('ho_accounting_panel/loa_billing_list/loa_list_accounting.php', array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }
}
