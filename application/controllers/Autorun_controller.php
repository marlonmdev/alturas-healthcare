<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autorun_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('autorun_model');
	}

	function update_all_expired_loa(){
		$this->security->get_csrf_hash();
		$rows = $this->autorun_model->get_all_approved_loa();
		if(!empty($rows)){
			foreach ($rows as $row) {
				$date_result = '';
				// call another function to determined if expired or not
				if(!empty($row['expiration_date'])){
					$date_result = $this->checkExpiration($row['expiration_date']);
				}

				if($date_result == 'Expired'){
					$this->autorun_model->update_loa_expired($row['loa_id']);
				}
			}
		}
	} 


	function update_member_expired_loa(){
		$this->security->get_csrf_hash();
		$emp_id = $this->uri->segment(5);
		$rows = $this->autorun_model->get_member_approved_loa($emp_id);
		if(!empty($rows)){
			foreach ($rows as $row) {
				$date_result = '';
				// call another function to determined if expired or not
				if(!empty($row['expiration_date'])){
					$date_result = $this->checkExpiration($row['expiration_date']);
				}

				if($date_result == 'Expired'){
					$this->autorun_model->update_loa_expired($row['loa_id']);
				}
			}
		}
	
	} 

	function checkExpiration($expiry_date){
		$expiration_date =  DateTime::createFromFormat("Y-m-d", $expiry_date);
		$current_date = new DateTime();
		$date_diff = $current_date->diff($expiration_date);
		$result = $current_date->getTimestamp() > $expiration_date->getTimestamp() ? 'Expired' : 'Not Expired';
		// Alternative way: $result = $date_diff->invert ? "Expired" : "Not Expired";
		return $result;
	}


}
