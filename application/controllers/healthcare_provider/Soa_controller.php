<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Soa_controller extends CI_Controller {

    function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
			redirect(base_url());
		}
	}

    function soaCreate() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/soa/create_soa.php');
        $this->load->view('templates/footer');
    }

    function soaRequest() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/soa/reprint_soa.php');
        $this->load->view('templates/footer');
    }
}
