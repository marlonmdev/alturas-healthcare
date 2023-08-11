<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_update extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/loa_model');
		$this->load->model('company_doctor/noa_model');
	}
    public function update_doctor_notification(){
        $pending_loa = $this->loa_model->get_count_pending();
		$pending_noa = $this->noa_model->get_count_pending();
		// var_dump('pending loa',$pending_loa);
		$data['pending_loa'] = $pending_loa;
		$data['pending_noa'] = $pending_noa;
		echo json_encode($data);
    }
}