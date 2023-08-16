<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'head-office-iad') {
			redirect(base_url());
		}
	}

	function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$data['page_title'] = 'Alturas Healthcare - Head Office IAD';
		$this->load->view('templates/header', $data);
		$this->load->view('ho_auditing_panel/dashboard/index');
		$this->load->view('templates/footer');
	}
}
