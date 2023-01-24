<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cost_item_controller extends CI_Controller {

    function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
			redirect(base_url());
		}
	}

    function costItemReq() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/cost_item/costItemReqView.php');
        $this->load->view('templates/footer');
    }

    function costItemReqListPending() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/cost_item/costItemReqListViewPending.php');
        $this->load->view('templates/footer');
    }

    function costItemReqListApproved() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/cost_item/costItemReqListViewApproved.php');
        $this->load->view('templates/footer');
    }
}
