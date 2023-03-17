<?php
defined('BASEPATH') or exit('No direct script access allowed');
Class Table_Controller extends CI_Controller{

	function __construct() {
		parent::__construct();
		$this->load->model('ho_iad/table_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'head-office-iad') {
			redirect(base_url());
		}
	}
	//Show table ==================================================
  	public function fetch_data(){
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$lists = $this->table_model->get_datatables($status);
		$data = [];
		$total_charge = 0;

		foreach($lists as $value){
			$row = [];
			$emp_id = $this->myhash->hasher($value['emp_id'], 'encrypt');
			$custom_bill_no = '<mark class="bg-primary text-white">'. $value['emp_id'] .'</mark>';
			$fullname = $value['first_name']. ' ' .$value['middle_name']. ' ' .$value['last_name'];
			$cost_type = $value['loa_id'] != '' ? 'LOA' : 'NOA'; 
			$custom_actions = '<a class="text-info fw-bold ls-1" href="' . base_url() . 'head-office-iad/record/'. $emp_id . '" data-bs-toggle="tooltip"><u>View Record</u></a>';
			
			$row[] = $custom_bill_no;
			$row[] = $fullname;
			$row[] = $cost_type;
			$row[] = $value['billed_on'];
			$row[] = $custom_actions;
			$data[] = $row;
		}
		
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->table_model->count_all($status),
			"recordsFiltered" => $this->table_model->count_filtered($status),
			"data" => $data
		];
		echo json_encode($output);
	}

	//Show record ==================================================
	function view_record(){
		$emp_id = $this->myhash->hasher($this->uri->segment(3), 'decrypt');
		// $emp_id = $this->uri->segment(3);
		$data['user_role'] = $this->session->userdata('user_role');
		$status = 'Billed';
		$data['lists'] = $this->table_model->get_billing_by_emp_id($emp_id, $status);

		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/billing/record');
		$this->load->view('templates/footer');
	}
}


