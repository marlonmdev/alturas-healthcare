<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_update extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/count_model');
	}
    public function update_member_notification(){
		$emp_id = $this->uri->segment(5);
        $resubmit_loa = $this->count_model->count_all_resubmit_loa($emp_id);
		$resubmit_noa = $this->count_model->count_all_resubmit_Noa($emp_id);
		// var_dump('pending loa',$resubmit_loa);
		// var_dump('pending noa',$resubmit_noa);
		$data['resubmit_loa'] = $resubmit_loa;
		$data['resubmit_noa'] = $resubmit_noa;
		echo json_encode($data);
    }
}