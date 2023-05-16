<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient_controller extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/patient_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
			redirect(base_url());
		}
  }
	function redirectBack() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			header('location:' . $_SERVER['HTTP_REFERER']);
		}else{
			header('location:http://' . $_SERVER['SERVER_NAME']);
		}
		exit();
	}
	//List of Patient
	public function design(){
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/patient/list_of_patient');
		$this->load->view('templates/footer');
	}
	function fetch_all_patient(){
		$this->security->get_csrf_hash();
		$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$loa_noa = $this->uri->segment(4);
		if($loa_noa === "loa"){
			$list = $this->patient_model->get_datatables($hcare_provider_id,$loa_noa);
		}elseif($loa_noa === "noa"){
			$list = $this->patient_model->get_datatables($hcare_provider_id,$loa_noa);
		}
		
		$data = array();
		foreach ($list as $member){
			$row = array(); 

			$member_id = $this->myhash->hasher($member['member_id'], 'encrypt');
			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$short_hosp_name = strlen($member['hp_name']) > 24 ? substr($member['hp_name'], 0, 24) . "..." : $member['hp_name'];
			$mbl =  number_format($member['max_benefit_limit'],2);
			$rmbl =  number_format($member['remaining_balance'],2);
			$view_url = base_url() . 'healthcare-provider/patient/view_information/' . $member_id;
			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="Patient Profile"><i class="mdi mdi-account-card-details fs-2 text-info me-2"></i></a>';

			$percent=0;
			$p=null;
      @$p = ($rmbl /$mbl) * 100;
      $percent = number_format($p);

			if($percent == 100){
	      $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
					        <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent'  style='width: $percent%; '>
					          <b style='color: #fffff;'><center>$percent%</center></b>
					        </div>
					      </div>";
	    }elseif ($percent <= 50) {
	      if($percent <= 29){
	        $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
                    <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='$percent' style='width: $percent%;'>
                    </div>
	                  <b style='color: red;'><center>".$percent." %</center></b>
	                </div>";

	      }else{
	        $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
					          <div class='progress-bar progress-bar-danger progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='$percent' style='width: $percent%;'>
					          </div>
					          <b style='color: red;'><center>$percent%</center></b>
	                </div>";
	      }
	    }elseif ($percent >= 51) {
	      $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
                  <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='25' style='width: $percent%;'>
                    <b style='color: #fffff;'><center>$percent%</center></b>
                  </div>
	              </div>";
	    }

			// this data will be rendered to the datatable
			$row[] = $member['emp_no'];
			$row[] = $full_name;
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $short_hosp_name;
			// $row[] = $mbl;
			// $row[] = $rmbl;
			$row[] = $bar;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->patient_model->count_all($hcare_provider_id,$loa_noa),
			"recordsFiltered" => $this->patient_model->count_filtered($hcare_provider_id,$loa_noa),
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function view_information(){
		$member_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $member = $this->patient_model->db_get_member_details($member_id);
		$data['mbl'] = $this->patient_model->db_get_member_mbl($member['emp_id']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/patient/patient_profile');
		$this->load->view('templates/footer');
	}

}