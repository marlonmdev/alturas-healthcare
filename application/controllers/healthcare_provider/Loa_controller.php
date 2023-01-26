<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
            redirect(base_url());
        }
    }

    function pending_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');

        $members = $this->loa_model->fetch_pending_loa_requests($hcare_provider_id);
        $payload_data = [];


        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;
            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/pending_loa_list', array('members' => $payload_data));
        $this->load->view('templates/footer');
    }


    function approved_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');    

        $members = $this->loa_model->fetch_approved_loa_requests($hcare_provider_id);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/approved_loa_list', array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

    function disapproved_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->fetch_disapproved_loa_requests($userHospital);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/disapproved_loa_list',  array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

    function closed_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->fetch_closed_loa_requests($userHospital);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/closed_loa_list',  array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

}
