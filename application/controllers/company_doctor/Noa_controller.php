<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

	function fetch_all_pending_noa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning pe-2"></i></a>';
			}else{
				$custom_actions .= '';
			}

			// if work_related field is set to either yes or no, show either disabled or not disabled approve button 
			if($noa['work_related'] == ''){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';

				$custom_actions .= '<a class="me-2" data-bs-toggle="tooltip" title="Charge type is not yet set by HRD Coordinator" disabled><i class="mdi mdi-thumb-up fs-2 icon-disabled"></i></a>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';

				$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="approveNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Approve NOA"><i class="mdi mdi-thumb-up fs-2 text-success"></i></a>';
			}

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="disapproveNoaRequest(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="Disapprove NOA"><i class="mdi mdi-thumb-down fs-2 text-danger"></i></a>';
			$view_receipt = '';
			if($noa['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $noa['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_all_approved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'company-doctor/noa/requested-noa/generate-printable-noa/' . $noa_id . '" data-bs-toggle="tooltip" title="Print NOA"><i class="mdi mdi-printer fs-2 ps-2 text-primary"></i></a>';

			if($noa['spot_report_file'] && $noa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $noa_id . '\',\'' . $noa['work_related'] . '\',\'' . $noa['percentage'] . '\',\'' . $noa['spot_report_file'] . '\',\'' . $noa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning ps-2"></i></a>';
			}else{
				$custom_actions .= '';
			}

			// $view_receipt = '';
			// if($noa['hospital_receipt']){
			// 	$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $noa['hospital_receipt'] . '\')"><strong>View</strong></a>';
			// }else{
			// 	$view_receipt ='None';
			// }
			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $expiry_date;
			// $row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function generate_printable_noa() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->noa_model->db_get_noa_info($noa_id);
		$data['mbl'] = $this->noa_model->db_get_member_mbl($exist['emp_id']);
		$data['doc'] = $this->noa_model->db_get_doctor_by_id($exist['approved_by']);

		if($exist['position_level'] <= 6){
			$data['room_type'] = 'Payward';
		}else if($exist['position_level'] > 6 && $exist['position_level'] < 10){
			$data['room_type'] = 'Semi-private';
		}else if($exist['position_level'] > 9){
			$data['room_type'] = 'Regular Private';
		}

		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('company_doctor_panel/noa/generate_printable_noa');
			$this->load->view('templates/footer');
		}
	}

	function fetch_all_disapproved_noa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];
			$view_receipt = '';
			if($noa['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $noa['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_paid_noa() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$list = $this->noa_model->get_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPaidNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];
			$view_receipt = '';
			if($noa['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $noa['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $request_date;
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status),
			"recordsFiltered" => $this->noa_model->count_filtered($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_billed_noa() {
		$this->security->get_csrf_hash();
		$status = ['Billed', 'Payable', 'Payment'];
		$list = $this->noa_model->get_billed_datatables($status);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$expiry_date = $noa['expiration_date'] ? date("m/d/Y", strtotime($noa['expiration_date'])) : 'None';

			$custom_noa_no = '<mark class="bg-primary text-white">'.$noa['noa_no'].'</mark>';

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">Billed</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];
			$view_receipt = '';
			if($noa['hospital_receipt']){
				$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $noa['hospital_receipt'] . '\')"><strong>View</strong></a>';
			}else{
				$view_receipt ='None';
			}
			// this data will be rendered to the datatable
			$row[] = $custom_noa_no;
			$row[] = $full_name;
			$row[] = $admission_date;
			$row[] = $short_hosp_name;
			$row[] = $expiry_date;
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all_billed($status),
			"recordsFiltered" => $this->noa_model->count_filtered_billed($status),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function get_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->noa_model->db_get_noa_info($noa_id);
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
		$paid_on = '';
		$bill = $this->noa_model->get_billing_info($row['noa_id']);
		if(!empty($bill)){
			$billed_on = date('F d, Y', strtotime($bill['billed_on']));
			$paid = $this->noa_model->get_paid_date($bill['details_no']);
			if(!empty($paid)){
				$paid_on = date('F d, Y', strtotime($paid['date_add']));
			}else{
				$paid_on = '';
			}
		}else{
			$billed_on = '';
		}
		$selected_cost_types = explode(';', $row['medical_services']);
		$ct_array = [];
		$is_empty = false;
		foreach ($selected_cost_types as $selected_cost_type) :
			if($selected_cost_type !== ''){
				$is_empty = true;
				array_push($ct_array, '[ <span class="text-success">'.$selected_cost_type.'</span> ]');
			}
			
		endforeach;
		$med_serv = implode(' ', $ct_array);
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
			'med_services' => ($is_empty)?$med_serv:'None',
			// Full Month Date Year Format (F d Y)
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'req_status' => $req_stat,
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'billed_on' => $billed_on,
			'paid_on' => $paid_on,
		);

		echo json_encode($response);
	}

	// public function approve_noa_request() {
	// 	$token = $this->security->get_csrf_hash();
	// 	$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
	// 	$approved_by = $this->session->userdata('doctor_id');
	// 	$approved_on = date("Y-m-d");
	// 	$approved = $this->noa_model->db_approve_noa_request($noa_id, $approved_by, $approved_on);
	// 	if ($approved) {
	// 		$response = ['token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully'];
	// 	} else {
	// 		$response = ['token' => $token, 'status' => 'error', 'message' => 'Unable to Approve NOA Request!'];
	// 	}
	// 	echo json_encode($response);
	// }
	public function get_personal_and_company_charge($noa_id,$old_billing) {
        
        $noa_info = $this->noa_model->db_get_noa_info($noa_id);
       
			$company_charge = 0;
			$personal_charge = 0;
			$remaining_mbl = 0;
			
			$wpercent = '';
			$nwpercent = '';
			$net_bill = $noa_info['hospital_bill'];
            $prev_mbl = ($old_billing != null) ? $old_billing['after_remaining_bal'] : 0;
			$max_mbl = floatval($noa_info['max_benefit_limit']);
			$percentage = floatval($noa_info['percentage']);
			$previous_mbl = 0;
            // var_dump("status",$status);
            // var_dump("prev mbl",$prevmbl);
			if($old_billing){
				$previous_mbl = $prev_mbl;
			}else{
				$previous_mbl =$noa_info['remaining_balance'];
			}


			if($noa_info['work_related'] == 'Yes'){
               
				if($noa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        $company_charge = $net_bill;
                        $personal_charge = 0;
                        if($net_bill >= $previous_mbl){
                            $remaining_mbl = 0;
                        }else if($net_bill < $previous_mbl){
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }
                    }
					
                    
				}else if($noa_info['percentage'] != ''){
					if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_company_charge = floatval($converted_percent) * $net_bill;
                            $initial_personal_charge = $net_bill - floatval($initial_company_charge);
                            
                            if(floatval($initial_company_charge) <= $previous_mbl){
                                $result = $previous_mbl - floatval($initial_company_charge);
                                $int_personal = floatval($initial_personal_charge) - floatval($result);
                                $personal_charge = $int_personal;
                                $company_charge = $previous_mbl;
                                $remaining_mbl = 0;
                        
                            }else if(floatval($initial_company_charge) > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                        }
                    }
					
				}
			}else if($noa_info['work_related'] == 'No'){
				if($noa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $company_charge;
                        }else if($net_bill > $previous_mbl){
                            $company_charge = $previous_mbl;
                            $personal_charge = $net_bill - $previous_mbl;
                            $remaining_mbl = 0;
                        }
                    }
					
                   
				}else if($noa_info['percentage'] != ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - floatval($net_bill);
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_personal_charge = $converted_percent * $net_bill;
                            $initial_company_charge = $net_bill - floatval($initial_personal_charge);
                            
                            if($initial_company_charge <= $previous_mbl){
                                $result = $previous_mbl - $initial_company_charge;
                                $initial_personal = $initial_personal_charge - $result;
                                if($initial_personal < 0 ){
                                    $personal_charge = 0;
                                    $company_charge = $initial_company_charge + $initial_personal_charge;
                                    $remaining_mbl = $previous_mbl - floatval($company_charge);
                                }else if($initial_personal >= 0){
                                    $personal_charge = $initial_personal;
                                    $company_charge = $previous_mbl;
                                    $remaining_mbl = 0;
                                }
                            }else if($initial_company_charge > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                            
                        }
                    }
				}
			}
		
            
            $data = array(
                'company_charge' => $company_charge,
                'personal_charge' => $personal_charge,
                'remaining_balance' =>$rmbl = $remaining_mbl,
                'used_mbl' =>  (($max_mbl-$rmbl)>0)? $max_mbl-$rmbl : $max_mbl,
                'previous_mbl' => $previous_mbl,
            );
			return  $data;
	}

	function billing_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);
		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function approve_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->input->post('noa-id', TRUE), 'decrypt');
		$expiration_type = $this->input->post('expiration-type', TRUE);
		$expiration_date = $this->input->post('expiration-date', TRUE);
		$approved_by = $this->session->userdata('doctor_id');
		$approved_on = date("Y-m-d");
		$noa_info = $this->noa_model->db_get_noa_info($noa_id);
		$is_manual = json_decode($noa_info['is_manual']);
		$result = $this->noa_model->db_get_max_billing_id();
		$max_billing_id = !$result ? 0 : $result['billing_id'];
		$add_billing = $max_billing_id + 1;
		$current_year = date('Y').date('m');
		// call function loa_number
		$billing_no = $this->billing_number($add_billing, 5, 'BLN-'.$current_year);

		if($expiration_type == 'custom'){
			$this->form_validation->set_rules('expiration-date', 'Custom Expiration Date', 'required');
			if ($this->form_validation->run() == FALSE) {
				$response = [
					'token' 							  => $token,
					'status'                => 'error',
					'expiration_date_error' => form_error('expiration-date'),
				];

				echo json_encode($response);
				exit();
			}
		}

		switch($expiration_type){
			case 'default': 
					$default = strtotime('+1 week', strtotime($approved_on));
					$expired_on = date('Y-m-d', $default);
				break;
			case '2 weeks':
					$expires = strtotime('+2 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case '3 weeks':
					$expires = strtotime('+3 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case '4 weeks':
					$expires = strtotime('+4 weeks', strtotime($approved_on));
					$expired_on = date('Y-m-d', $expires);
				break;
			case 'custom':
					$expired_on = date('Y-m-d', strtotime($expiration_date));
				break;
		}

		$data_status = [
			'status'          => ($is_manual === 1)?'Billed':'Approved',
			'approved_by'     => $approved_by,
			'approved_on'     => $approved_on,
			'expiration_date' => $expired_on
		];

		$approved = $this->noa_model->db_approve_noa_request($noa_id, $data_status);
		
		if ($approved) {
			if($is_manual === 1){
				$old_billing = $this->noa_model->get_billing_by_emp_id($noa_info['emp_id']);
				$result_charge = $this->get_personal_and_company_charge($noa_id,$old_billing);
				$data = [
					'billing_no'            => $billing_no,
					'billing_type'          => 'Reimburse',
					'emp_id'                => $noa_info['emp_id'],
					'noa_id'                => $noa_id,
					'hp_id'                 => $noa_info['hospital_id'],
					'work_related'          => $noa_info['work_related'],
					// 'take_home_meds'        => isset($take_home_meds)?implode(',',$take_home_meds):$get_prev_meds,
					'net_bill'              => $noa_info['hospital_bill'],
					'company_charge'        =>  $result_charge['company_charge'],
					'personal_charge'       =>  $result_charge['personal_charge'],
					'before_remaining_bal'  =>  $result_charge['previous_mbl'],
					'after_remaining_bal'   =>  $result_charge['remaining_balance'],
					'pdf_bill'              => $noa_info['hospital_receipt'],
					// 'itemized_bill'         => isset($uploaded_files['itemize-pdf-file']) ? $uploaded_files['itemize-pdf-file']['file_name'] : $get_prev_mbl_by_bill_no['itemize-pdf-file'],
					// 'final_diagnosis_file'  => isset($uploaded_files['Final-Diagnosis']) ? $uploaded_files['Final-Diagnosis']['file_name'] : $get_prev_mbl_by_bill_no['final_diagnosis_file'],
					// 'medical_abstract_file' => isset($uploaded_files['Medical-Abstract']) ? $uploaded_files['Medical-Abstract']['file_name'] : $get_prev_maf,
					// 'prescription_file'     => isset($uploaded_files['Prescription']) ? $uploaded_files['Prescription']['file_name'] : $get_prev_pf,
					'billed_by'             => $this->session->userdata('fullname'),
					'billed_on'             => date('Y-m-d'),
					'status'                => 'Billed',
					// 'extracted_txt'         => $hospitalBillData,
					// 'attending_doctors'      => $attending_doctor,
					'request_date'          => $noa_info['request_date']
				];    
				$mbl = [
					'used_mbl'            => $result_charge['used_mbl'],
					'remaining_balance'      => $result_charge['remaining_balance']
				];
	
				$insert = $this->noa_model->insert_billing($data);
			
				if($insert){
					$this->noa_model->update_member_remaining_balance($noa_info['emp_id'], $mbl);
					$response = ['token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully','next_page'=>'Billed'];
				}else{
					$response = ['token' => $token, 'status' => 'save-error', 'message' => 'Unable to Approve NOA Request'];
				}
			}else{
				$response = ['token' => $token, 'status' => 'success', 'message' => 'NOA Request Approved Successfully','next_page'=>'Approved'];
			}
		} else {
			$response = ['token' => $token, 'status' => 'save-error', 'message' => 'Unable to Approve NOA Request'];
		}
		echo json_encode($response);
	}

	public function disapprove_noa_request() {
		$token = $this->security->get_csrf_hash();
		$noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$disapprove_reason = $this->input->post('disapprove-reason');
		$disapproved_by = $this->session->userdata('doctor_id');
		$disapproved_on = date("Y-m-d");
		$this->form_validation->set_rules('disapprove-reason', 'Reason for Disapproval', 'required|max_length[500]');
		if ($this->form_validation->run() == FALSE) {
			$response = array(
				'token' => $token,
				'status' => 'error',
				'disapprove_reason_error' => form_error('disapprove-reason'),
			);
			echo json_encode($response);
		} else {
			$disapproved = $this->noa_model->db_disapprove_noa_request($noa_id, $disapproved_by, $disapprove_reason, $disapproved_on);
			if (!$disapproved) {
				$response = array('token' => $token, 'status' => 'error', 'message' => 'Unable to Disapprove NOA Request!');
			}
			$response = array('token' => $token, 'status' => 'success', 'message' => 'NOA Request Disapproved Successfully');
			echo json_encode($response);
		}
	}

	function fetch_member_details() {
		$this->security->get_csrf_hash();
        $search_data = $this->input->post('search');
        $result = $this->noa_model->get_autocomplete($search_data);
        if (!empty($result)) {
            foreach ($result as $row) :
                $member_id = $this->myhash->hasher($row['member_id'], 'encrypt');
                echo '<strong class="d-block mx-2 p-1 my-1"><a href="#" onclick="getMemberValues(\'' . $member_id . '\')" class="text-secondary" data-toggle="tooltip" data-placement="top" title="Click to fill form with Data">'
                    . $row['first_name'] . ' '
                    . $row['middle_name'] . ' '
                    . $row['last_name'] . ' '
                    . $row['suffix'] . '</a></strong>';
            endforeach;
        } else {
            echo "<p class='text-center mt-1'><em>No data found...</em></p>";
        }
	}

	function fetch_auto_complete() {
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $row = $this->noa_model->db_get_member_details($member_id);
        $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
        $current_date = date("d-m-Y");
        $diff = date_diff(date_create($birth_date), date_create($current_date));
        $age = $diff->format("%y");

        $response = [
            'status' => 'success',
            'token' => $this->security->get_csrf_hash(),
            'member_id' => $row['member_id'],
            'emp_id' => $row['emp_id'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'suffix' => $row['suffix'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth'],
            'age' => $age,
            'philhealth_no' => $row['philhealth_no'],
            'blood_type' =>  $row['blood_type'],
            'home_address' => $row['home_address'],
            'city_address' => $row['city_address'],
            'contact_no' => $row['contact_no'],
            'email' => $row['email'],
            'contact_person' => $row['contact_person'],
            'contact_person_addr' => $row['contact_person_addr'],
            'contact_person_no' => $row['contact_person_no'],
            'health_card_no' => $row['health_card_no'],
            'requesting_company' => $row['company'],
            'mbl' => number_format($row['remaining_balance'],2,'.',',')
        ];
        echo json_encode($response);
	}

	function noa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function submit_noa_override() {
			/* The below code is a PHP code that is used to save the data from the form to the database. */
			$this->security->get_csrf_hash();
			$emp_id = $this->session->userdata('emp_id');
			$inputPost = $this->input->post(NULL, TRUE); // returns all POST items with XSS filter
			$default_status = 'Pending';
	
			$this->load->library('form_validation');
			$this->form_validation->set_rules('hospital-name', 'Name of Hospital', 'required');
			$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
			$this->form_validation->set_rules('admission-date', 'Admission Date', 'required');
	
			if ($this->form_validation->run() == FALSE) {
				$response = [
					'status' => 'error',
					'hospital_name_error' => form_error('hospital-name'),
					'chief_complaint_error' => form_error('chief-complaint'),
					'admission_date_error' => form_error('admission-date'),
				];
			} else {
				// check if the selected hospital exist from database
				$hospital_id = $this->input->post('hospital-name');
				$hp_exist = $this->noa_model->db_check_hospital_exist($hospital_id);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error', 
						'message' => 'Hospital Does Not Exist'
					];
					echo json_encode($response);
					exit();
				} else {
					// select the max noa_id from DB
					$result = $this->noa_model->db_get_max_noa_id();
					$max_noa_id = !$result ? 0 : $result['noa_id'];
					$add_noa = $max_noa_id + 1;
					$current_year = date('Y');
					// call function loa_number
					$noa_no = $this->noa_number($add_noa, 7, 'NOA-'.$current_year);
					$member_id = $inputPost['member-id'];
					$member = $this->noa_model->db_get_member_details($member_id);
	
					$post_data = [
						'noa_no' => $noa_no,
						'emp_id' => $member['emp_id'],
						'first_name' =>  $member['first_name'],
						'middle_name' =>  $member['middle_name'],
						'last_name' =>  $member['last_name'],
						'suffix' =>  $member['suffix'],
						'date_of_birth' => $member['date_of_birth'],
						'health_card_no' => $member['health_card_no'],
						'requesting_company' => $member['company'],
						'hospital_id' => $inputPost['hospital-name'],
						'admission_date' => date('Y-m-d', strtotime($inputPost['admission-date'])),
						'chief_complaint' => strip_tags($inputPost['chief-complaint']),
						'request_date' => date("Y-m-d"),
						'status' => $default_status,
						'requested_by' => $this->session->userdata('doctor_id'),
						'override_by' => $inputPost['doctor-id']
					];
	
					$saved = $this->noa_model->db_insert_noa_request($post_data);
					if (!$saved) {
						$response = [
							'status' => 'save-error', 
							'message' => 'NOA Request Failed'
						];
					}
					$response = [
						'status' => 'success', 
						'message' => 'NOA Request Save Successfully'
					];
				}
			}
			echo json_encode($response);
	}
}
