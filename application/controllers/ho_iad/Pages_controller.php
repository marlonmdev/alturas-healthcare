<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('ho_accounting/List_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-iad') {
            redirect(base_url());
        }
    }

    function index() {
		$data['user_role'] = $this->session->userdata('user_role');
		// $data['billed_count'] = $this->List_model->hp_billed_count();
		// $data['payment_count'] = $this->List_model->hp_payment_history_count();
		// $data['loa_count'] = $this->List_model->hp_approved_loa_count();
		// $data['noa_count'] = $this->List_model->hp_approved_noa_count();

		// $bill = $this->List_model->hp_paid_bill();

		// foreach($bill as $paid){
		// 	$hp_id = $paid['hp_id'];
		// 	$data['paid_count'] = $this->List_model->hp_count_paid($hp_id);
		// 	$data['hp_name'] = $paid['hp_name'];
		// }
		
		// $data_paid = [
		// 	'hp_name' => $paid['hp_name'],
		// 	'paid_count' => $paid,
		// ];

		$this->load->view('templates/header', $data);
		$this->load->view('ho_iad_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

}