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

	public function fetch_billed(){
		$this->security->get_csrf_hash();

		$lists = $this->List_model->get_datatables();
		$data = [];
		$total_charge = 0;

		foreach($lists as $value){
			
			$row = [];
			// calling Myhash custom library inside application/libraries folder
			$billing_id = $this->myhash->hasher($value['billing_id'], 'encrypt');
			
			$charge = $value['company_charge'] == '' ? 0 : $value['company_charge'];

			$custom_comp_charge = '<span class="sum">'.$charge.'</span>';

			$fullname = $value['first_name']. ' ' .$value['middle_name']. ' ' .$value['last_name'];
			$custom_bill_no = '<mark class="bg-primary text-white">'. $value['billing_no'] .'</mark>';

			$total_charge = $total_charge + floatval($value["company_charge"]);

			$cost_type = $value['loa_id'] != '' ? 'LOA' : 'NOA'; 

			$custom_actions = '<a class="text-info fw-bold ls-1" href="' . base_url() . 'head-office-accounting/billing-list/billed/view/' . $billing_id . '" data-bs-toggle="tooltip"><u>View Receipt</u></a>';

			$row[] = $custom_bill_no;
			$row[] = $fullname;
			$row[] = $cost_type;
			$row[] = $value['billed_on'];
			$row[] = $custom_comp_charge;
			$row[] = $custom_actions;
			$row[] = $total_charge;
			$data[] = $row;
		}
		
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->List_model->count_all(),
			"recordsFiltered" => $this->List_model->count_filtered(),
			"data" => $data,
			"total" => number_format($total_charge, 2)
		];
		echo json_encode($output);
	}

	public function view_billed_details(){
		$id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['bill'] = $bill = $this->List_model->get_billing_info($id);
		$data['user_role'] = $this->session->userdata('user_role');
		$data['mbl'] = $this->List_model->get_member_mbl($bill['emp_id']);
		$data['services'] = $this->List_model->get_billing_services($bill['billing_no']);
		$data['deductions'] = $this->List_model->get_billing_deductions($bill['billing_no']);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_accounting_panel/billing_list_table/billing_receipt');
		$this->load->view('templates/footer');
		
	}
}
