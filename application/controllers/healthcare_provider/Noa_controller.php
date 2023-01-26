<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

    function __construct() {
		parent::__construct();
        $this->load->model('healthcare_provider/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
			redirect(base_url());
		}
	}

    function pending_noa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->noa_model->fetch_pending_noa_requests($hcare_provider_id);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/noa/pending_noa_list', array('members' => $members));
        $this->load->view('templates/footer');
    }


    function approved_noa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->noa_model->fetch_approved_noa_requests($hcare_provider_id);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/noa/approved_noa_list', array('members' => $members));
        $this->load->view('templates/footer');
    }


     function disapproved_noa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->noa_model->fetch_disapproved_noa_requests($hcare_provider_id);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/noa/disapproved_noa_list', array('members' => $members));
        $this->load->view('templates/footer');
    }


    function closed_noa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->noa_model->fetch_closed_noa_requests($hcare_provider_id);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/noa/closed_noa_list', array('members' => $members));
        $this->load->view('templates/footer');
    }
}
