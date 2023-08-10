<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_update extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/billing_model');
	}
    public function update_provider_notification(){
        $guarantee = $this->billing_model->get_count_guarantee();
		$patient = $this->billing_model->get_count_to_bill();
		$data['guarantee'] = $guarantee;
		$data['patient'] = $patient;
		echo json_encode($data);
    }
}