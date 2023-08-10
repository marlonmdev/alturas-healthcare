<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mbl_history extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('member/loa_model');
        $this->load->model('member/noa_model');
        $this->load->model('member/history_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'member') {
			redirect(base_url());
		}
	}

	function fetch_loa_mbl_history() {
		$this->security->get_csrf_hash(); 
		$emp_id = $this->input->post('emp_id');
		$list = $this->loa_model->get_loa_datatables($emp_id);
		//  var_dump('loa',$list);
		$data = array();
		$custom_actions = '';
		foreach ($list as $loa){
			$row = array(); 
			// var_dump('loa',$loa['tbl1_loa_id']);
			$loa_id = $this->myhash->hasher($loa['tbl1_loa_id'], 'encrypt');
			
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaHistoryInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA">'.$loa['loa_no'].'</a>';
            
            $med_services = 0;
            $exploded_med_services = explode(";", $loa['med_services']);
    
            foreach ($exploded_med_services as $ctype_id) :
                $cost_type = $this->loa_model->get_loa_op_price($ctype_id);
                // var_dump(floatval($cost_type['op_price']));
                if($loa['loa_request_type'] != 'Consultation'){
                    $med_services += floatval($cost_type['op_price']);
                }else{
                    $med_services = 500;
                }
                
            endforeach;
            
			$row[] = $loa['tbl1_loa_id'];
			$row[] = $loa['tbl1_request_date'];
			$row[] = $loa['hp_name'];
			$row[] = $custom_actions;
			$row[] = $loa['loa_request_type'];
			$row[] =  $loa['tbl1_status'];
            $row[] = ($loa['is_manual'])?number_format($loa['hospital_bill'],2):number_format(floatval((isset($loa['net_bill']) ? $loa['net_bill'] : $med_services)),2);
			
			$data[] = $row;
		}

		// if(!$list){
		// 	$his_mbl = $this->history_model->get_his_mbl($emp_id);
		// 	$row[] = '';
		// 	$row[] =  '';
		// 	$row[] = '';	
		// 	$row[] = 'BEGINNING MBL';	
		// 	$row[] = number_format($his_mbl,2);
		// 	$row[] = '';	
		// 	$data[] = $row;
		// }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_loa($emp_id),
			"recordsFiltered" => $this->loa_model->count_loa_filtered($emp_id),
			"data" => $data,
		);
		echo json_encode($output);	
	}

	function fetch_noa_mbl_history() {
		$this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp_id');
		$list = $this->noa_model->get_noa_datatables($emp_id);
		
		$data = array();
		
		// var_dump('list',$list);
		foreach ($list as $noa){
			$row = array(); 

			$noa_id = $this->myhash->hasher($noa['tbl1_noa_id'], 'encrypt');
			
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaHistoryInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View LOA">'.$noa['noa_no'].'</a>';
			
			$row[] = $noa['tbl1_noa_id'];
			$row[] =  $noa['tbl1_request_date'];
			$row[] =  $noa['hp_name'];
			$row[] = $custom_actions;	
			$row[] =  $noa['type_request'];
			$row[] =  $noa['tbl1_status'];
            $row[] = ($noa['is_manual'])?number_format($noa['hospital_bill'],2):number_format(floatval((isset($noa['net_bill']) ? $noa['net_bill'] : 0)),2);
			$data[] = $row;
		}
		// var_dump('list',$list);
		// if(!$list){
		// 	$his_mbl = $this->history_model->get_his_mbl($emp_id);
		// 	$row[] = '';
		// 	$row[] =  '';
		// 	$row[] = '';	
		// 	$row[] = 'BEGINNING MBL';	
		// 	$row[] = number_format($his_mbl,2);
		// 	$row[] = '';	
		// 	$data[] = $row;
		// }

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all_noa($emp_id),
			"recordsFiltered" => $this->noa_model->count_noa_filtered($emp_id),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function fetch_loa_noa_mbl_history() {
		// var_dump("executed");
		$this->security->get_csrf_hash();
		$emp_id = $this->input->post('emp_id');
		$list = $this->history_model->get_history_datatables($emp_id);
		$startmbl = $this->history_model->get_start_mbl($emp_id);
		$max_mbl = $this->loa_model->db_get_member_mbl($emp_id);
		// var_dump("start mbl",$startmbl);
		$row1 = array();
		$data = array();
		$counter = true;
				
				

			foreach ($list as $bill){
			 $row = array();
				if($this->input->post('loa_noa') === "NOA"){
					if($bill['loa_id']){
					  continue;
					}
				  }
	
				if($this->input->post('loa_noa') === "LOA"){
					if($bill['noa_id']){
					  continue;
					}
				  }
	
				$loa_id = $this->myhash->hasher($bill['loa_id'], 'encrypt');
				$noa_id = $this->myhash->hasher($bill['noa_id'], 'encrypt');
				if(isset($bill['loa_id'])){
					$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaHistoryInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA">'.$bill['loa_no'].'</a>';
				}
				
				if(isset($bill['noa_id'])){
					$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaHistoryInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA">'.$bill['noa_no'].'</a>';
				}

				if($counter){
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = '';
					$row1[] = 'BEGINNING MBL';
					$row1[] = number_format(($startmbl),2);
					$row1[] = '';
					$data[] = $row1;
					$counter = false;
				}

				$row[] = $bill['billing_id'];	
				$row[] = $bill['tbl1_request_date'];
				$row[] = $custom_actions;	
				$row[] = $bill['billing_no'];
				$row[] = $bill['tbl1_status'];
				$row[] = number_format(floatval($bill['net_bill']),2);
				$row[] = number_format(floatval($bill['company_charge']),2);
				$row[] = number_format(floatval($bill['personal_charge']),2);
				$row[] = number_format(floatval($bill['cash_advance']),2);
				$row[] = number_format(floatval($bill['after_remaining_bal']),2);
				$data[] = $row;
			}
			
			if(!$list){
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = '';
				$row1[] = 'BEGINNING MBL';
				$row1[] = number_format(($max_mbl['remaining_balance']),2);
				$row1[] = '';
				$data[] = $row1;
			}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->history_model->count_all_history($emp_id),
			"recordsFiltered" => $this->history_model->count_history_filtered($emp_id),
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
			// var_dump("id",$loa_id);
			// var_dump("status",$row['tbl_1_status']);
		}
		
		//  var_dump("row",$row);
		$billing = $this->loa_model->get_loa_billing_info($row['loa_id']); 
		$attending_doctors =[];
		$docs = $this->history_model->get_attending_doctors($billing['billing_id'],$billing['emp_id']);
		foreach($docs as $doctors){
			array_push($attending_doctors,$doctors["doc_name"]);
		}
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
		// var_dump("status",$row['tbl_1_status']);
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
		// var_dump('cost_types',$selected_cost_types);
		$selected_not_in_cost_types = array_diff($selected_cost_types, array_column($cost_types, 'ctype_id'));

		foreach ($selected_not_in_cost_types as $selected_cost_type) {
			// Handle the selected_cost_type that does not belong to $cost_types array
			if($selected_cost_type !== ''){
				array_push($ct_array, $selected_cost_type);
			}
			
		}
		// var_dump('ct_array',$ct_array);
		if($isperformed){
			foreach ($performed as $physician) :
				if (isset($physician['physician_fname']) || isset($physician['physician_mname']) || isset($physician['physician_lname'])) {
					array_push($physicians, $physician['physician_fname'].' '.$physician['physician_mname'].' '.$physician['physician_lname']);	
				}
			endforeach;
			// var_dump("physicians",$physicians);
		}
		// if($row['loa_request_type'] === 'Diagnostic Test'){
			// var_dump('ct_array',$ct_array);
		// }else{
		// 	var_dump('ct_array2',$ct_array);
		// } 
		// $med_serv = implode('', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_no' => $row['loa_no'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => $ct_array,
			'healthcare_provider' => $row['hp_name'],
			// 'med_services' => ($row['loa_request_type'] === 'Diagnostic Test') ? $ct_array : (($row['loa_request_type'] === 'Consultation') ?['Consultation']:['Emergency Loa']),
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'complaints' => $row['chief_complaint'],
			'requesting_physician' => ($row['loa_request_type'] !== "Emergency")? $row['doctor_name'] :"",
			'attending_physician' => $physicians,
			'rx_file' => isset($row['rx_file'])? $row['rx_file']:'',
			'hospital_receipt' => isset($row['hospital_receipt'])? $row['hospital_receipt']:'',
			'hospital_bill' => isset($row['hospital_bill'])?number_format($row['hospital_bill'],2,'.',','):"",
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
			'net_bill' => isset($billing['net_bill'])?number_format($billing['net_bill'],2,'.',','):"",
			'paid_amount' =>isset($paid_loa['amount_paid'])?$paid_loa['amount_paid']:"",
			'attending_doctors' =>isset($billing['attending_doctors'])?$attending_doctors: "",
			// 'attending_doctors' =>isset($billing['attending_doctors'])?explode(';', $billing['attending_doctors']): "",
			
		];
		// var_dump('bill',number_format($billing['net_bill'],2,'.',','));
		echo json_encode($response);
	}
	
	function get_noa_history_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$row = $this->noa_model->_db_get_noa_info($noa_id);
		$billing = $this->noa_model->get_noa_billing_info($noa_id);
		$paid_noa = $this->noa_model->paid_noa(isset($billing['details_no'])?$billing['details_no']:null);
		$attending_doctors =[];
		$docs = $this->history_model->get_attending_doctors($billing['billing_id'],$billing['emp_id']);
		foreach($docs as $doctors){
			array_push($attending_doctors,$doctors["doc_name"]);
		}
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

		// var_dump('hospital receipt',$row['hospital_receipt']);
		// var_dump('medical_services',$row['medical_services']);
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
			'hospital_receipt' => isset($row['hospital_receipt'])? $row['hospital_receipt']:'',
			'med_services'	=> isset($row['medical_services'])? explode(';',$row['medical_services']):'',
			'hospital_bill' => isset($row['hospital_bill'])?number_format($row['hospital_bill'],2,'.',','):"",
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
			'net_bill' => isset($billing['net_bill'])?number_format($billing['net_bill'],2,'.',','):"",
			'paid_amount' =>isset($paid_noa['amount_paid'])?$paid_noa['amount_paid']:"",
			'attending_doctors' =>isset($billing['attending_doctors'])?$attending_doctors:""
			// 'attending_doctors' =>isset($billing['attending_doctors'])?explode(';', $billing['attending_doctors']):""
		);
		// var_dump("response",$response);
		echo json_encode($response);
	}

	
}
