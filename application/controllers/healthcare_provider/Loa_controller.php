<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/Loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
            redirect(base_url());
        }
    }

    function loaRequestListPending() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->Loa_model->loa_member_pending($userHospital);
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

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/pending_loa_list', array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }


    function loaRequestListApproved() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');    
        $members = $this->Loa_model->loa_member_approved($userHospital);

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

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/approved_loa_list', array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }


    function loaRequestListClosed() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->Loa_model->loa_member_closed($userHospital);
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

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/closed_loa_list',  array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }


    function loaRequestListDisapproved() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->Loa_model->loa_member_disapproved($userHospital);
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

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/disapproved_loa_list',  array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }
}
