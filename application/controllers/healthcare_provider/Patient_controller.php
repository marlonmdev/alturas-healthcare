<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient_controller extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('healthcare_provider/patient_model');
		$this->load->model('healthcare_provider/loa_model');
        $this->load->model('healthcare_provider/noa_model');
        $this->load->model('healthcare_provider/billing_model');
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

	// 		$percent=0;
	// 		$p=null;
    //   @$p = ($rmbl /$mbl) * 100;
    //   $percent = number_format($p);

		// 	if($percent == 100){
	    //   $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
		// 			        <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent'  style='width: $percent%; '>
		// 			          <b style='color: #fffff;'><center>$percent%</center></b>
		// 			        </div>
		// 			      </div>";
	    // }elseif ($percent <= 50) {
	    //   if($percent <= 29){
	    //     $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
        //             <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='$percent' style='width: $percent%;'>
        //             </div>
	    //               <b style='color: red;'><center>".$percent." %</center></b>
	    //             </div>";

	    //   }else{
	    //     $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
		// 			          <div class='progress-bar progress-bar-danger progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='$percent' style='width: $percent%;'>
		// 			          </div>
		// 			          <b style='color: red;'><center>$percent%</center></b>
	    //             </div>";
	    //   }
	    // }elseif ($percent >= 51) {
	    //   $bar = "<div class='progress-container' id='animated-bar' style='border: 1px solid #E6E9ED;'>
        //           <div class='progress-bar progress-bar-info progress-bar-striped progress-bar-animated active' data-transitiongoal='$percent' aria-valuenow='25' style='width: $percent%;'>
        //             <b style='color: #fffff;'><center>$percent%</center></b>
        //           </div>
	    //           </div>";
	    // }

			// this data will be rendered to the datatable
			$row[] = $member['emp_no'];
			$row[] = $full_name;
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $short_hosp_name;
			// $row[] = $mbl;
			$row[] = $rmbl;
			// $row[] = $bar;
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
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['member'] = $member = $this->patient_model->db_get_member_details($member_id);
		$data['mbl'] = $this->patient_model->db_get_member_mbl($member['emp_id']);
		$data['loa'] = $this->loa_model->get_loa_history($hp_id,$member['emp_id']);
		$data['noa'] = $this->noa_model->get_noa_history($hp_id,$member['emp_id']);
		$data['hp_id'] = $hp_id;
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/patient/patient_profile');
		$this->load->view('templates/footer');
	}

	function list_of_soa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$hp_name = $this->patient_model->get_hp_name($hp_id);
		$data['hp_name'] = $hp_name['hp_name']; // Assign hp_name to the data array
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/patient/list_of_soa.php', $data);
		$this->load->view('templates/footer');
	}
	

	function fetch_lis_of_soa() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$bill = $this->patient_model->soa_list_datatable($hp_id);
		// var_dump($soa_list);
		$data = [];
		foreach($bill as $pay){
			$row = [];
			$company_charge = '';
			$personal_charge = '';
			$remaining_mbl = '';

			if($pay['loa_id'] != ''){
				$loa_noa = $pay['loa_no'];

			}else if($pay['noa_id'] != ''){
				$loa_noa = $pay['noa_no'];
			}
			$fullname =  $pay['first_name'] . ' ' . $pay['middle_name'] . ' ' . $pay['last_name'] . ' ' . $pay['suffix'];
			$wpercent = '';
			$nwpercent = '';
			$net_bill = floatval($pay['net_bill']);
			$previous_mbl = floatval($pay['remaining_balance']);

			if($pay['loa_id'] != ''){
				$loa_noa = $pay['loa_no'];
				$loa = $this->patient_model->get_loa_info($pay['loa_id']);
				if($loa['work_related'] == 'Yes'){ 
					if($loa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa['percentage'].'%  W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa['work_related'] == 'No'){
				   if($loa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa['percentage']);
					   if($loa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			  
			}else if($pay['noa_id'] != ''){
				$loa_noa = $pay['noa_no'];
				$noa = $this->patient_model->get_noa_info($pay['noa_id']);
				if($noa['work_related'] == 'Yes'){ 
					if($noa['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa['percentage'].'%  W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa['work_related'] == 'No'){
				   if($noa['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa['percentage']);
					   if($noa['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			}

			$payable = floatval($pay['company_charge'] + floatval($pay['cash_advance']));


			$row[] = $pay['billing_no'];
			$row[] = $loa_noa;
			$row[] = $fullname;
			$row[] = $pay['business_unit'];
			$row[] = number_format($pay['remaining_balance'],2, '.',',');
			$row[] = $wpercent. ', '.$nwpercent;
			$row[] = number_format($pay['net_bill'],2, '.',',');
			$row[] = number_format($pay['personal_charge'],2, '.',',');
			$row[] = number_format($pay['company_charge'],2, '.',',');
			$row[] =number_format($pay['cash_advance'],2, '.',',');
			$row[] =isset($pay['cash_advance']) ? 'Approved': '';
			$row[] = number_format($payable,2, '.',',');
			$data[] = $row;
		}
		$output = [
			"draw" => $_POST['draw'],
			"data" => $data,
			"token" => $token,
			"recordsTotal" => $this->patient_model->count_all_soa($hp_id),
			"recordsFiltered" => $this->patient_model->count_filtered_soa($hp_id),
		];

		echo json_encode($output);
	}

	//fetch loa history

	function fetch_all_patient_loa(){
		$this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp_id');
		$hp_id = $this->input->post('hp_id');
		$list = $this->loa_model->get_loa_datatables($emp_id, $hp_id);

		$date ="";
		// var_dump("list",$list);
		// var_dump("emp_id",$emp_id);
		// var_dump("hp_id",$hp_id);
		$data = array();
		$custom_actions = '';
		foreach ($list as $loa){
			$row = array(); 

			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			// $view_url = base_url() . 'healthcare-provider/patient/view_information/' . $member_id;
			

			if($loa['tbl1_status']==="Billed" || $loa['tbl1_status']==="Paid" || $loa['tbl1_status'] === "Payable"){
				$date = $loa['billed_on'];
				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaHistoryInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			}elseif($loa['tbl1_status']==="Approved"){
				$date = $loa['approved_on'];
				$custom_actions ='';
			}else{
				$custom_actions ='';
				$date = $loa['request_date'];
			}
			// this data will be rendered to the datatable
			$row[] = $loa['loa_no'];
			$row[] = (isset($loa['net_bill']) ? $loa['net_bill'] : 0);
			$row[] =  $loa['tbl1_status'];
			$row[] = $date;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_loa($emp_id, $hp_id),
			"recordsFiltered" => $this->loa_model->count_loa_filtered($emp_id, $hp_id),
			"data" => $data,
		);
		echo json_encode($output);	
	}
	function fetch_all_patient_noa(){
		$this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp_id');
		$hp_id = $this->input->post('hp_id');
		$list = $this->noa_model->get_noa_datatables($emp_id, $hp_id);
		// var_dump("list",$list);
		// var_dump("emp_id",$emp_id);
		// var_dump("hp_id",$hp_id);
		$data = array();
		$custom_actions = "";
		foreach ($list as $noa){
			$row = array(); 

			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			// $view_url = base_url() . 'healthcare-provider/patient/view_information/' . $member_id;
			
			
			if($noa['tbl1_status']==="Billed" || $noa['tbl1_status']==="Paid" || $noa['tbl1_status'] === "Payable"){
				$date = $noa['billed_on'];
				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaHistoryInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			}elseif($noa['tbl1_status']==="Approved"){
				$date = $noa['approved_on'];
				$custom_actions = "";
			}else{
				$date = $noa['request_date'];
				$custom_actions = "";
			}
			// this data will be rendered to the datatable
			$row[] = $noa['noa_no'];
			$row[] = (isset($noa['net_bill']) ? $noa['net_bill'] : 0);
			$row[] =  $noa['tbl1_status'];
			$row[] = $date;
			$row[] = $custom_actions;	
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all_noa($emp_id, $hp_id),
			"recordsFiltered" => $this->noa_model->count_noa_filtered($emp_id, $hp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	function get_loa_history_info() {
		$loa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		// var_dump($loa_id);
		$row = $this->loa_model->db_get_loa_info($loa_id);
		$billing = $this->billing_model->get_loa_billing_info($loa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birthDate = date("d-m-Y", strtotime($row['date_of_birth']));
		$currentDate = date("d-m-Y");
		$diff = date_diff(date_create($birthDate), date_create($currentDate));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['item_description']);
			}
		endforeach;
		$med_serv = implode('', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_no' => $row['loa_no'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $ct_array,
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'pdf_bill' => $billing['pdf_bill'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'expiration' => date("F d, Y", strtotime($row['expiration_date'])),
		];
		echo json_encode($response);
	}
	function get_noa_history_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);
		$billing = $this->billing_model->get_noa_billing_info($noa_id);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';

		/* Checking if the status is pending and the work related is not empty. If it is, then it will set
		the req_stat to for approval. If not, then it will set the req_stat to the status. */
		if($row['status'] == 'Pending' && $row['work_related'] != ''){
			$req_stat = 'for Approval';
		}else{
			$req_stat = $row['status'];
		}

		$response = array(
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'noa_id' => $row['noa_id'],
			'noa_no' => $row['noa_no'],
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'hospital_name' => $row['hp_name'],
			'admission_date' => date("F d, Y", strtotime($row['admission_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'pdf_bill' => $billing['pdf_bill'],
			// Full Month Date Year Format (F d Y)
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'work_related' => $row['work_related'],
			'req_status' => $req_stat,
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'expiration' => date("F d, Y", strtotime($row['expiration_date'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		);

		echo json_encode($response);
	}
}