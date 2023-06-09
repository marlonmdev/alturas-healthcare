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
		if ($logged_in !== true && $user_role !== 'hc-provider-accounting') {
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
		$this->load->view('hc_provider_accounting_panel/patient/list_of_patient');
		$this->load->view('templates/footer');
	}
	function fetch_all_patient(){
		$this->security->get_csrf_hash();
		$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->patient_model->get_datatables($hcare_provider_id);
		
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
			"recordsTotal" => $this->patient_model->count_all($hcare_provider_id),
			"recordsFiltered" => $this->patient_model->count_filtered($hcare_provider_id),
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
		$data['loa'] = $loa = $this->loa_model->get_loa_history($hp_id,$member['emp_id']);
		$data['noa'] = $this->noa_model->get_noa_history($hp_id,$member['emp_id']);
		$data['hp_id'] = $hp_id;
		$this->load->view('templates/header', $data);
		$this->load->view('hc_provider_accounting_panel/patient/patient_profile');
		$this->load->view('templates/footer');
	}

	function list_of_soa() {
		$data['user_role'] = $this->session->userdata('user_role');
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$hp_name = $this->patient_model->get_hp_name($hp_id);
		$data['hp_name'] = $hp_name['hp_name']; // Assign hp_name to the data array
		$this->load->view('templates/header', $data);
		$this->load->view('hc_provider_accounting_panel/patient/list_of_soa.php', $data);
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
			$row[] = number_format($pay['cash_advance'],2, '.',',');
			$row[] =($pay['cash_advance']!=0) ? 'Approved': '';
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

		$data = array();
		$custom_actions = '';
		foreach ($list as $loa){
			$row = array(); 
			$loa_id = $this->myhash->hasher($loa['tbl1_loa_id'], 'encrypt');
			
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaHistoryInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$row[] = $loa['loa_no'];
			$row[] = (isset($loa['net_bill']) ? $loa['net_bill'] : 0);
			$row[] =  $loa['tbl1_status'];
			$row[] = $loa['tbl1_request_date'];
			// $row[] = $custom_actions;
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
		
		$data = array();
		foreach ($list as $noa){
			$row = array(); 

			$noa_id = $this->myhash->hasher($noa['tbl1_noa_id'], 'encrypt');
			
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaHistoryInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			
			$row[] = $noa['noa_no'];
			$row[] = (isset($noa['net_bill']) ? $noa['net_bill'] : 0);
			$row[] =  $noa['tbl1_status'];
			$row[] =  $noa['tbl1_request_date'];
			// $row[] = $custom_actions;	
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
		$isperformed = false;
		// var_dump("loa id",$loa_id);
		if($this->loa_model->check_performed_loa($loa_id)){
			$performed = $this->loa_model->get_performed_info($loa_id);
			$row = $this->loa_model->db_get_loa_info_patient($loa_id,true);
			$isperformed = true;
		}else{
			$row = $this->loa_model->db_get_loa_info_patient($loa_id,false);
			$isperformed = false;
		}
		
		//  var_dump("row",$row);
		$billing = $this->billing_model->get_loa_billing_info($row['loa_id']); 
		// var_dump($billing['attending_doctors']);
		$paid_loa = $this->loa_model->paid_loa(isset($billing['details_no'])?$billing['details_no']:null);
		$doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = isset($doc)?$doc['doctor_name']:"";
		} elseif ($row['disapproved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = isset($doc)?$doc['doctor_name']:"";
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
		// $perform_doctors = explode(';', $billing['attending_doctors']);
		// var_dump("perform_doctors",$perform_doctors);
		$ct_array = [];
		$physicians = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, $cost_type['item_description']);
			}
		endforeach;

		if($isperformed){
			foreach ($performed as $physician) :
				if (isset($physician['physician_fname']) || isset($physician['physician_mname']) || isset($physician['physician_lname'])) {
					array_push($physicians, $physician['physician_fname'].' '.$physician['physician_mname'].' '.$physician['physician_lname']);	
				}
			endforeach;
			// var_dump("physicians",$physicians);
		}
		
		$med_serv = implode('', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_no' => $row['loa_no'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => (count($ct_array)!=0) ? $ct_array : ['Consultation'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'complaints' => $row['chief_complaint'],
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $physicians,
			'rx_file' => $row['rx_file'],
			'pdf_bill' => isset($billing['pdf_bill'])?$billing['pdf_bill']:"",
			'req_status' => $row['tbl_1_status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'date_perform' => ($isperformed)?date("F d, Y", strtotime($row['date_performed'])):"",
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'expiration' => date("F d, Y", strtotime($row['expiration_date'])),
			'billed_on' => isset($billing['billed_on'])?date("F d, Y", strtotime($billing['billed_on'])):"",
			'paid_on' => isset($paid_loa['date_add'])?date("F d, Y", strtotime($paid_loa['date_add'])):"",
			'net_bill' => isset($billing['net_bill'])?$billing['net_bill']:"",
			'paid_amount' =>isset($paid_loa['amount_paid'])?$paid_loa['amount_paid']:"",
			'attending_doctors' =>isset($billing['attending_doctors'])?explode(';', $billing['attending_doctors']): ""
		];
		echo json_encode($response);
	}
	function get_noa_history_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);
		$billing = $this->billing_model->get_noa_billing_info($noa_id);
		$paid_noa = $this->noa_model->paid_noa(isset($billing['details_no'])?$billing['details_no']:null);
		// var_dump("noa ",$row);
		// var_dump("billing",$billing);
		// var_dump("paid noa",$paid_noa);
		// $doctor_name = "";
		if ($row['approved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->noa_model->db_get_doctor_name_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}
		 
		// $perform_doctors = explode(';', $billing['attending_doctors']);
		// var_dump("perform_doctors",$perform_doctors);
		// var_dump($row);
		$dateOfBirth = $row['date_of_birth'];
		$today = date("Y-m-d");
		$diff = date_diff(date_create($dateOfBirth), date_create($today));
		$age = $diff->format('%y') . ' years old';
		// var_dump("complaint",$row['chief_complaint']);
		// /* Checking if the status is pending and the work related is not empty. If it is, then it will set
		// the req_stat to for approval. If not, then it will set the req_stat to the status. */
		// if($row['status'] == 'Pending' && $row['work_related'] != ''){
		// 	$req_stat = 'for Approval';
		// }else{
		// 	$req_stat = $row['status'];
		// }

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
			'complaints' => $row['chief_complaint'],
			'pdf_bill' => isset($billing['pdf_bill'])?$billing['pdf_bill']:"",
			'final_diagnosis' => isset($billing['final_diagnosis_file'])?$billing['final_diagnosis_file']:"",
			'medical_abstract' => isset($billing['medical_abstract_file'])?$billing['medical_abstract_file']:"",
			'prescription' => isset($billing['prescription_file'])?$billing['prescription_file']:"",
			// Full Month Date Year Format (F d Y)
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'work_related' => $row['work_related'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'expiration' => date("F d, Y", strtotime($row['expiration_date'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'billed_on' => isset($billing['billed_on'])?(date("F d, Y", strtotime($billing['billed_on']))):"",
			'paid_on' => isset($paid_noa['date_add'])?date("F d, Y", strtotime($paid_noa['date_add'])):"",
			'net_bill' => isset($billing['net_bill'])?$billing['net_bill']:"",
			'paid_amount' =>isset($paid_noa['amount_paid'])?$paid_noa['amount_paid']:"",
			'attending_doctors' =>isset($billing['attending_doctors'])?explode(';', $billing['attending_doctors']):""
		);
		// var_dump("response",$response);
		echo json_encode($response);
	}
}