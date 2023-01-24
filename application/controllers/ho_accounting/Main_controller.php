<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');

		$this->load->model('ho_accounting/List_model');
		if ($logged_in !== true && $user_role !== 'head-office-accounting') {
			redirect(base_url());
		}
	}

	public function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		$billingList = $this->List_model->getBilling();
		$intialResult = $finalResult = [];

		foreach (array_column($billingList, 'billing_date') as $date) {
			foreach (array_filter($billingList, function ($v) use ($date) {
				return $v['billing_date'] == $date;
			}) as $billing) {
				$intialResult[$date][$billing['billing_id']] = $billing;
			}
		}

		foreach ($intialResult as $resDate) {
			$initialResDate = [];
			foreach (array_column($resDate, 'hp_id') as $hp_id) {
				foreach (array_filter($resDate, function ($v) use ($hp_id) {
					return $v['hp_id'] == $hp_id;
				}) as $billing) {
					$initialResDate[$hp_id][$billing['billing_id']] = $billing;
				}
			}
			array_push($finalResult, $initialResDate);
		}

		$data["cutoffresult"] = $finalResult;
		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/dashboard/index');
		$this->load->view('templates/footer');
	}
}
