<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/loa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

	function fetch_all_pending_loa() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$cost_types = explode(';', $loa['med_services']);
			$total_fee = 0;
			foreach($cost_types as $ctype){
				$fee = $this->loa_model->get_estimated_total_fee($ctype);
				foreach($fee as $costs){
					$fees = floatval($costs['op_price']);
					$total_fee += $fees;
				}
			}

			$wpercent = '';
			$nwpercent = '';

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
		    $remaining_mbl = 0;
			$company_charge = 0;
			$personal_charge = 0;
		   	$net_bill = floatval($total_fee);
			$previous_mbl = floatval($loa['remaining_balance']);
			$percentage = floatval($loa['percentage']);

			if($loa['work_related'] == 'Yes'){
				if($loa['percentage'] == ''){
					$company_charge = number_format($net_bill,2, '.',',');
					$personal_charge = number_format(0,2, '.',',');
					if($net_bill >= $previous_mbl){
						$remaining_mbl = number_format(0,2, '.',',');
					}else if($net_bill < $previous_mbl){
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}
				}else if($loa['percentage'] != ''){
					
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - $net_bill,2, '.',',');
					}else if($net_bill > $previous_mbl){
						$converted_percent = $percentage/100;
						$initial_company_charge = floatval($converted_percent) * $net_bill;
						$initial_personal_charge = $net_bill - floatval($initial_company_charge);

						if(floatval($initial_company_charge) <= $previous_mbl){
							$result = $previous_mbl - floatval($initial_company_charge);
							$int_personal = floatval($initial_personal_charge) - floatval($result);
							$personal_charge = number_format($int_personal,2, '.',',');
							$company_charge = number_format($previous_mbl,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
					
						}else if(floatval($initial_company_charge) > $previous_mbl){
							$personal_charge = number_format($initial_personal_charge,2, '.',',');
							$company_charge = number_format($initial_company_charge,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
						}
					}
					
				}
			}else if($loa['work_related'] == 'No'){
				if($loa['percentage'] == ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
					}else if($net_bill > $previous_mbl){
						$company_charge = number_format($previous_mbl,2, '.',',');
						$personal_charge = number_format($net_bill - $previous_mbl,2, '.',',');
						$remaining_mbl = number_format(0,2, '.',',');
					}
				}else if($loa['percentage'] != ''){
					if($net_bill <= $previous_mbl){
						$company_charge = number_format($net_bill,2, '.',',');
						$personal_charge = number_format(0,2, '.',',');
						$remaining_mbl = number_format($previous_mbl - floatval($net_bill),2, '.',',');
					}else if($net_bill > $previous_mbl){
						$converted_percent = $percentage/100;
						$initial_personal_charge = $converted_percent * $net_bill;
						$initial_company_charge = $net_bill - floatval($initial_personal_charge);
						if($initial_company_charge <= $previous_mbl){
							$result = $previous_mbl - $initial_company_charge;
							$initial_personal = $initial_personal_charge - $result;
							if($initial_personal < 0 ){
								$personal_charge = number_format(0,2, '.',',');
								$company_charge = number_format($initial_company_charge + $initial_personal_charge,2, '.',',');
								$remaining_mbl = number_format($previous_mbl - floatval($company_charge),2, '.',',');
							}else if($initial_personal >= 0){
								$personal_charge = number_format($initial_personal,2, '.',',');
								$company_charge = number_format($previous_mbl,2, '.',',');
								$remaining_mbl = number_format(0,2, '.',',');
							}
						}else if($initial_company_charge > $previous_mbl){
							$personal_charge = number_format($initial_personal_charge,2, '.',',');
							$company_charge = number_format($initial_company_charge,2, '.',',');
							$remaining_mbl = number_format(0,2, '.',',');
						}
					}
				}
			}

			
			if(floatval($total_fee) >  $previous_mbl) {
				$totalFee = '<span class="text-danger">'.number_format($total_fee,2, '.',',').'</span>';
				$prev_mbl = '<span class="text-danger">'.number_format($previous_mbl,2, '.',',').'</span>';
			}else{
				$totalFee = '<span>'.number_format($total_fee,2, '.',',').'</span>';
				$prev_mbl = '<span>'.number_format($previous_mbl,2, '.',',').'</span>';
			}


			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewPendingLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			if($loa['spot_report_file'] || $loa['incident_report_file'] || $loa['police_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\',\'' . $loa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
			}else{
				$custom_actions .= '';
			}
			

			// if work_related field is set to either yes or no, show either disabled or not disabled approve button 
			if($loa['work_related'] == ''){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';
				$custom_actions .= '<a class="me-2" data-bs-toggle="tooltip" title="Charge type is not yet set by HRD Coordinator" disabled><i class="mdi mdi-thumb-up fs-2 icon-disabled"></i></a>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';
				$custom_actions .= '<a class="me-2" href="JavaScript:void(0)" onclick="approveLoaRequest(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Approve LOA"><i class="mdi mdi-thumb-up fs-2 text-success"></i></a>';
			}

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="disapproveLoaRequest(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="Disapprove LOA"><i class="mdi mdi-thumb-down fs-2 text-danger"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file and medical services to None
				// $short_med_services = 'Npne';
				$view_file = 'None';
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];
			}else{

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $totalFee;
			$row[] = $wpercent. ', '.$nwpercent;
			$row[] = $prev_mbl;
			$row[] = $remaining_mbl;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function fetch_all_approved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$expiry_date = $loa['expiration_date'] ? date('m/d/Y', strtotime($loa['expiration_date'])) : 'None'; 

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			if($loa['spot_report_file'] || $loa['incident_report_file'] || $loa['police_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\',\'' . $loa['police_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning ps-2"></i></a>';
			}else{
				$custom_actions .= '';
			}

			$custom_actions .= '<a href="' . base_url() . 'company-doctor/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 ps-2 text-primary"></i></a>';

			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showBackDateForm(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\', \''.$loa['expiration_date'].'\')" data-bs-toggle="tooltip" title="Date Extension"><i class="mdi mdi-border-color fs-2 text-cyan"></i></a>';

			// $expires = strtotime('+1 week', strtotime($loa['approved_on']));
      // $expiration_date = date('m/d/Y', $expires);
			// call another function to determined if expired or not
			// $date_result = $this->checkExpiration($loa['approved_on']);
			// if($date_result == 'Expired'){
			// 	$custom_date = '<span class="text-danger">'.$expiration_date.'</span><span class="text-danger fw-bold ls-1"> [Expired]</span>';

			// 	$custom_actions = '<a href="JavaScript:void(0)" onclick="viewApprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// 	$custom_actions .= '<a data-bs-toggle="tooltip" title="Cannot Print Expired LOA"><i class="mdi mdi-printer fs-2 ps-2 icon-disabled"></i></a>';
			// }
				

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $loa['status'] . '</span></div>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';
				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $expiry_date;
			$row[] = $view_receipt;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function checkExpiration($passed_date){
		$approved_date = DateTime::createFromFormat("Y-m-d", $passed_date);

		$expiration_date = $approved_date->modify("+7 days");

		$current_date = new DateTime();

		$date_diff = $current_date->diff($expiration_date);

		$result = $date_diff->invert ? "Expired" : "Not Expired";

		return $result;
	}

	function backdate_expired(){
		$loa_id = $this->myhash->hasher($this->input->post('loa-id', TRUE), 'decrypt');
		$expiry_date = $this->input->post('expiry-date', TRUE);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('expiry-date', 'Expiry Date', 'required');
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' 				    => 'error',
				'expiry_date_error' => form_error('expiry-date'),
			];
		} else {
			$loa_info = $this->loa_model->get_loa_info_by_id($loa_id);
			if($loa_info['status'] == 'Referred'){
				$status = 'Referred';
			}else{
				$status = 'Approved';
			}
			$post_data = [
				'status'          => $status,
				'expiration_date' => date('Y-m-d', strtotime($expiry_date)),
				'extended_when' => date("Y-m-d"),
				'extended_by' 	=> $this->session->userdata('fullname'),
			];

			$updated = $this->loa_model->db_update_loa_request($loa_id, $post_data);

			if (!$updated) {
				$response = [
					'status'  => 'save-error', 
					'message' => 'LOA Request BackDate Failed'
				];
			}
			$response = [
				'status'  => 'success', 
				'message' => 'LOA Request BackDated Successfully'
			];
		}		
		echo json_encode($response);
	}


	function generate_printable_loa() {
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['row'] = $exist = $this->loa_model->db_get_loa_info($loa_id);
		$data['mbl'] = $this->loa_model->db_get_member_mbl($exist['emp_id']);
		$data['cost_types'] = $this->loa_model->db_get_cost_types();
		if($exist['requesting_physician'] != ''){
			$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
		}else{
			$data['req'] = 'Does not Exist from Database';	
		}
		$data['doc'] = $this->loa_model->db_get_doctor_by_id($exist['approved_by']);
		if (!$exist) {
			$this->load->view('pages/page_not_found');
		} else {
			$this->load->view('templates/header', $data);
			$this->load->view('company_doctor_panel/loa/generate_printable_loa');
			$this->load->view('templates/footer');
		}
	}

	function fetch_all_disapproved_loa() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			// Myhash is a custom library located in application/libraries directory -> Enabled Globally
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewDisapprovedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_expired_loa() {
		$this->security->get_csrf_hash();
		$status = 'Expired';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$expiry_date = $loa['expiration_date'] ? date('m/d/Y', strtotime($loa['expiration_date'])) : 'None'; 
			
			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';
			
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewExpiredLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showBackDateForm(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\')" data-bs-toggle="tooltip" title="Date Extension"><i class="mdi mdi-border-color fs-2 text-cyan"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';
				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $expiry_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function fetch_all_completed_loa() {
		$this->security->get_csrf_hash();
		$status = 'Completed';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCompletedLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file =  'None';
				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_referral_loa() {
		$this->security->get_csrf_hash();
		$status = 'Referred';
		$list = $this->loa_model->get_datatables($status);
		$data = [];

		foreach ($list as $loa) {
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');
			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];
			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';
			$expiry_date = $loa['expiration_date'] ? date('m/d/Y', strtotime($loa['expiration_date'])) : 'None'; 
			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $loa['status'] . '</span></div>';
			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewReferralLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			$custom_actions .= '<a href="' . base_url() . 'company-doctor/loa/requested-loa/generate-printable-loa/' . $loa_id . '" data-bs-toggle="tooltip" title="Print LOA"><i class="mdi mdi-printer fs-2 ps-2 text-primary"></i></a>';
			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showBackDateForm(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\', \''.$loa['expiration_date'].'\')" data-bs-toggle="tooltip" title="Date Extension"><i class="mdi mdi-border-color fs-2 text-cyan"></i></a>';
			
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				$view_file = 'None';
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];
			}else{
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];
				$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $expiry_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		);
		echo json_encode($output);
	}

	function fetch_all_cancelled_loa() {
		$this->security->get_csrf_hash();
		$status = 'Cancelled';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			// Myhash is a custom library located in application/libraries directory -> Enabled Globally
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewCancelledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_billed_loa() {
		$this->security->get_csrf_hash();
		$status = ['Billed', 'Payable', 'Payment'];
		$list = $this->loa_model->get_billed_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			// Myhash is a custom library located in application/libraries directory -> Enabled Globally
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">Billed</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewBilledLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
			}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all_billed($status),
			"recordsFiltered" => $this->loa_model->count_filtered_billed($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function fetch_all_paid_loa() {
		$this->security->get_csrf_hash();
		$status = 'Paid';
		$list = $this->loa_model->get_datatables($status);
		$data = [];
		foreach ($list as $loa) {
			// Myhash is a custom library located in application/libraries directory -> Enabled Globally
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$row = [];

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_loa_no = '<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewPaidLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			$view_receipt = '';
			if ($loa['loa_request_type'] === 'Consultation' || $loa['loa_request_type'] === 'Emergency') {
				// if request is consultation set the view file to None
				$view_file = 'None';

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

			} else {

				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];

				// link to the file attached during loa request
				if($loa['rx_file']){
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}else{
					$view_file ='None';
				}
				if($loa['hospital_receipt']){
					$view_receipt = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/hospital_receipt/' . $loa['hospital_receipt'] . '\')"><strong>View</strong></a>';
				}else{
					$view_receipt ='None';
				}
				}

			// this data will be rendered to the datatable
			$row[] = $custom_loa_no;
			$row[] = $full_name;
			$row[] = $loa['loa_request_type'];
			$row[] = $short_hp_name;
			$row[] = $view_file;
			$row[] = $view_receipt;
			$row[] = $custom_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->loa_model->count_all($status),
			"recordsFiltered" => $this->loa_model->count_filtered($status),
			"data" => $data,
		];
		echo json_encode($output);
	}

	function get_loa_info(){
		$doctor_name = $requesting_physician = "";
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);

		//check if requesting physician exist from DB
		$exist = $this->loa_model->db_get_requesting_physician($row['requesting_physician']);
		if (!$exist){
			$requesting_physician = "Does not exist from database";
		}else{
			$requesting_physician = $exist['doctor_name'];
		}
		//end

		// approved_by and disapproved_by is same value as doctor_id from Database
		if ($row['approved_by']){
			$doc = $this->loa_model->db_get_doctor_name_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		}elseif ($row['disapproved_by']){
			$doc = $this->loa_model->db_get_doctor_name_by_id($row['disapproved_by']);
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

		/* Taking the med_services column from the database and exploding it into an array.
		Then it is looping through the cost_types array and checking if the ctype_id is in the
		selected_cost_types array.
		If it is, it pushes the cost_type into the ct_array.
		Then it implodes the ct_array into a string and assigns it to the  variable. */
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		$is_empty = true;
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
				$is_empty = false;
			}
		endforeach;
		$selected_not_in_cost_types = array_diff($selected_cost_types, array_column($cost_types, 'ctype_id'));

		foreach ($selected_not_in_cost_types as $selected_cost_type) {
			// Handle the selected_cost_type that does not belong to $cost_types array
			if($selected_cost_type !== ''){
				$is_empty = false;
				array_push($ct_array, '[ <span class="text-success">' . $selected_cost_type . '</span> ]');
			}
			
		}
		$med_serv = implode(' ', $ct_array);

		/* Checking if the status is pending and the work related is not empty. If it is, then it will set
		the req_stat to for approval. If not, then it will set the req_stat to the status. */
		if($row['status'] == 'Pending' && $row['work_related'] != ''){
			$req_stat = 'for Approval';
		}else{
			$req_stat = $row['status'];
		}
		$paid_on = '';
		$bill = $this->loa_model->get_bill_info($row['loa_id']);
		if(!empty($bill)){
			$billed_on = date('F d, Y', strtotime($bill['billed_on']));
			$paid = $this->loa_model->get_paid_date($bill['details_no']);
			if(!empty($paid)){
				$paid_on = date('F d, Y', strtotime($paid['date_add']));
			}else{
				$paid_on = '';
			}
		}else{
			$billed_on = '';
		}

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'philhealth_no' => $row['philhealth_no'],
			'blood_type' => $row['blood_type'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'] != '' ? $row['email'] : 'None',
			'contact_person' => $row['contact_person'],
			'contact_person_addr' => $row['contact_person_addr'],
			'contact_person_no' => $row['contact_person_no'],
			'healthcare_provider' => $row['hp_name'],
			'loa_request_type' => $row['loa_request_type'],
			'med_services' => (!$is_empty)?$med_serv:'None',
			'health_card_no' => $row['health_card_no'],
			'requesting_company' => $row['requesting_company'],
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'chief_complaint' => $row['chief_complaint'],
			'requesting_physician' => $requesting_physician,
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $req_stat,
			'work_related' => $row['work_related'],
			'percentage' => $row['percentage'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' =>  $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => $row['disapproved_on'] ? date("F d, Y", strtotime($row['disapproved_on'])) : '',
			'expiry_date' => $row['expiration_date'] ? date("F d, Y", strtotime($row['expiration_date'])) : 'None',
			'cancelled_by' => $row['cancelled_by'],
			'cancellation_reason' => $row['cancellation_reason'],
			'cancelled_on' => $row['cancelled_on'] ? date("F d, Y", strtotime($row['cancelled_on'])) : '',
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
			'requested_by' => $row['requested_by'],
			'billed_on' => $billed_on,
			'paid_on' => $paid_on,
			'hospitalized_date' => date('F d, Y', strtotime($row['emerg_date']))
		];
		echo json_encode($response);
	}

	public function get_personal_and_company_charge($loa_id,$old_billing) {
        
        $loa_info = $this->loa_model->db_get_loa_info($loa_id);
       
			$company_charge = 0;
			$personal_charge = 0;
			$remaining_mbl = 0;
			
			$wpercent = '';
			$nwpercent = '';
			$net_bill = $loa_info['hospital_bill'];
            $prev_mbl = ($old_billing != null) ? $old_billing['after_remaining_bal'] : 0;
			$max_mbl = floatval($loa_info['max_benefit_limit']);
			$percentage = floatval($loa_info['percentage']);
			$previous_mbl = 0;
            // var_dump("status",$status);
            // var_dump("prev mbl",$prevmbl);
			if($old_billing){
				$previous_mbl = $prev_mbl;
			}else{
				$previous_mbl =$loa_info['remaining_balance'];
			}

			if($loa_info['work_related'] == 'Yes'){
               
				if($loa_info['percentage'] == ''){
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
					
                    
				}else if($loa_info['percentage'] != ''){
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
			}else if($loa_info['work_related'] == 'No'){
				if($loa_info['percentage'] == ''){
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
					
                   
				}else if($loa_info['percentage'] != ''){
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
	function approve_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->input->post('loa-id', TRUE), 'decrypt');
		$expiration_type = $this->input->post('expiration-type', TRUE);
		$expiration_date = $this->input->post('expiration-date', TRUE);
		$approved_by = $this->session->userdata('doctor_id');
		$approved_on = date("Y-m-d");
		$loa_info = $this->loa_model->db_get_loa_info($loa_id);
		$is_manual = json_decode($loa_info['is_manual']);
		$result = $this->loa_model->db_get_max_billing_id();
		$max_billing_id = !$result ? 0 : $result['billing_id'];
		$add_billing = $max_billing_id + 1;
		$current_year = date('Y').date('m');
		// call function loa_number
		$billing_no = $this->billing_number($add_billing, 5, 'BLN-'.$current_year);
		if($expiration_type == 'custom'){
			$this->form_validation->set_rules('expiration-date', 'Custom Expiration Date', 'required');
			if ($this->form_validation->run() == FALSE) {
				$response = [
					'token'                 => $token,
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
			'performed_fees'  => 'Approved',
			'status'          => ($is_manual === 1)?'Billed':'Approved',
			'approved_by'     => $approved_by,
			'approved_on'     => $approved_on,
			'expiration_date' => $expired_on
		];

		$approved = $this->loa_model->db_approve_loa_request($loa_id, $data_status);
		
		if ($approved) {
			if($is_manual === 1){
				$old_billing = $this->loa_model->get_billing_by_emp_id($loa_info['emp_id']);
				$result_charge = $this->get_personal_and_company_charge($loa_id,$old_billing);
				$data = [
					'billing_no'            => $billing_no,
					'billing_type'          => 'Reimburse',
					'emp_id'                => $loa_info['emp_id'],
					'loa_id'                => $loa_id,
					'hp_id'                 => $loa_info['hcare_provider'],
					'work_related'          => $loa_info['work_related'],
					// 'take_home_meds'        => isset($take_home_meds)?implode(',',$take_home_meds):$get_prev_meds,
					'net_bill'              => $loa_info['hospital_bill'],
					'company_charge'        =>  $result_charge['company_charge'],
					'personal_charge'       =>  $result_charge['personal_charge'],
					'before_remaining_bal'  =>  $result_charge['previous_mbl'],
					'after_remaining_bal'   =>  $result_charge['remaining_balance'],
					'pdf_bill'              => $loa_info['hospital_receipt'],
					// 'itemized_bill'         => isset($uploaded_files['itemize-pdf-file']) ? $uploaded_files['itemize-pdf-file']['file_name'] : $get_prev_mbl_by_bill_no['itemize-pdf-file'],
					// 'final_diagnosis_file'  => isset($uploaded_files['Final-Diagnosis']) ? $uploaded_files['Final-Diagnosis']['file_name'] : $get_prev_mbl_by_bill_no['final_diagnosis_file'],
					// 'medical_abstract_file' => isset($uploaded_files['Medical-Abstract']) ? $uploaded_files['Medical-Abstract']['file_name'] : $get_prev_maf,
					// 'prescription_file'     => isset($uploaded_files['Prescription']) ? $uploaded_files['Prescription']['file_name'] : $get_prev_pf,
					'billed_by'             => $this->session->userdata('fullname'),
					'billed_on'             => date('Y-m-d'),
					'status'                => 'Billed',
					// 'extracted_txt'         => $hospitalBillData,
					// 'attending_doctors'      => $attending_doctor,
					'request_date'          => $loa_info['request_date']
				];    
				$mbl = [
					'used_mbl'            => $result_charge['used_mbl'],
					'remaining_balance'      => $result_charge['remaining_balance']
				];
	
				$insert = $this->loa_model->insert_billing($data);
			
				if($insert){
					$this->loa_model->update_member_remaining_balance($loa_info['emp_id'], $mbl);
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

	function disapprove_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		// $loa_id = $this->myhash->hasher($this->input->post('loa-id'), 'decrypt');
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
		}else{
			$disapproved = $this->loa_model->db_disapprove_loa_request($loa_id, $disapproved_by, $disapprove_reason, $disapproved_on);
			if (!$disapproved) {
				$response = array('token' => $token, 'status' => 'error', 'message' => 'Unable to Disapprove LOA Request');
			}
			$response = array('token' => $token, 'status' => 'success', 'message' => 'LOA Request Disapproved Successfully');
			echo json_encode($response);
		}

		
	}

	function search_autocomplete() {
        $this->security->get_csrf_hash();
        $search_data = $this->input->post('search');
        $result = $this->loa_model->get_autocomplete($search_data);
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

	function fetch_member_details() {
		$member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $row = $this->loa_model->db_get_member_details($member_id);
        $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
        $current_date = date("d-m-Y");
        $diff = date_diff(date_create($birth_date), date_create($current_date));
        $age = $diff->format("%y");

        $response = [
            'status' => 'success',
            'token' => $this->security->get_csrf_hash(),
            'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
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

	function get_hp_services() {
		$token = $this->security->get_csrf_hash();
		$hp_id = $this->uri->segment(3);
		$cost_types = $this->loa_model->db_get_cost_types_by_hp($hp_id);
		$response = '';

		if(empty($cost_types)){
			$response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" multiple="multiple">';
			$response .= '<option value="" disabled>No Available Services</option>';
			$response .= '</select>';
		}else{
			$response .= '<select class="chosen-select form-select" style=" width: 300px; height: 200px;" id="med-services" name="med-services[]" data-placeholder="Choose services..." multiple="multiple">';
			foreach ($cost_types as $cost_type) {
				$response .= '<option value="'.$cost_type['ctype_id'].'" data-price="'.$cost_type['op_price'].'">'.$cost_type['item_description'].''.' ₱'.''.number_format($cost_type['op_price'],2,'.',',').'</option>';
			}
			$response .= '</select>';
		}
		echo json_encode($response);
	}

	function check_rx_file($str) {
		if (isset($_FILES['rx-file']['name']) && !empty($_FILES['rx-file']['name'])) {
			return true;
		} else {
			$this->form_validation->set_message('check_rx_file', 'Please choose RX/Request Document file to upload.');
			return false;
		}
	}

	function multiple_select() {
		$med_services = $this->input->post('med-services');
		if (count((array)$med_services) < 1) {
			$this->form_validation->set_message('multiple_select', 'Select at least one Service');
			return false;
		} else {
			return true;
		}
	}

	function loa_form_validation($type) {
		switch ($type) {
			case 'Empty':
			case 'Diagnostic Test':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				$this->form_validation->set_rules('rx-file', '', 'callback_check_rx_file');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];

					echo json_encode($response);
					exit();
				}
				break;
			case 'Emergency':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('hopitalized-date', 'Date Hospitalized', 'required');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[1000]');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'hospitalized_date_error' => form_error('hopitalized-date'),
					];

					echo json_encode($response);
					exit();
				}
				break;
			case 'Diagnostic Test Update':
				$this->form_validation->set_rules('healthcare-provider', 'HealthCare Provider', 'required');
				$this->form_validation->set_rules('loa-request-type', 'LOA Request Type', 'required');
				$this->form_validation->set_rules('med-services', 'Medical Services', 'callback_multiple_select');
				$this->form_validation->set_rules('chief-complaint', 'Chief Complaint', 'required|max_length[2000]');
				$this->form_validation->set_rules('requesting-physician', 'Requesting Physician', 'trim|required');
				$this->form_validation->set_rules('rx-file', '', 'callback_update_check_rx_file');
				if ($this->form_validation->run() == FALSE) {
					$response = [
						'status' => 'error',
						'healthcare_provider_error' => form_error('healthcare-provider'),
						'loa_request_type_error' => form_error('loa-request-type'),
						'med_services_error' => form_error('med-services'),
						'chief_complaint_error' => form_error('chief-complaint'),
						'requesting_physician_error' => form_error('requesting-physician'),
						'rx_file_error' => form_error('rx-file'),
					];
					echo json_encode($response);
					exit();
				}
				break;
		}
	}

	function loa_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function submit_loa_override() {
		$token = $this->security->get_csrf_hash();
		$input_post = $this->input->post(NULL, TRUE);
		// JSON Decode - Takes a JSON encoded string and converts it into a PHP value
		$physicians_tags = json_decode($this->input->post('attending-physician'), TRUE);
		$physician_arr = [];
		$hp_id = $this->input->post('healthcare-provider');
		$request_type = $this->input->post('loa-request-type'); 
		switch (true) {
			case ($request_type == ''):
				$this->loa_form_validation('Empty');
				break;
			case ($request_type == 'Diagnostic Test'):
				$this->loa_form_validation('Diagnostic Test');
				// check if the selected healthcare provider exist from database
				$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
				if (!$hp_exist) {
					$response = [
						'status' => 'save-error',
						'message' => 'Invalid Healthcare Provider'
					];
					echo json_encode($response);
					exit();
				} else {
					// if theres selected file to be uploaded
					$config['upload_path'] = './uploads/loa_attachments/';
					$config['allowed_types'] = 'jpg|jpeg|png';
					$config['encrypt_name'] = TRUE;
					$this->load->library('upload', $config);

					if (!$this->upload->do_upload('rx-file')) {
						$response = [
							'status' => 'save-error',
							'message' => 'File Not Uploaded'
						];
						echo json_encode($response);
						exit();
					} else {
						$upload_data = $this->upload->data();
						$rx_file = $upload_data['file_name'];

						$med_services = $this->input->post('med-services');

						// for physician multi-tags input
						if(empty($physicians_tags)) {
							$attending_physician = '';
						} else {
							foreach ($physicians_tags as $physician_tag) :
								array_push($physician_arr, ucwords($physician_tag['value']));
							endforeach;
							$attending_physician = implode(', ', $physician_arr);
						}

						// Call function insert_loa
						$this->insert_loa($input_post, $med_services, $attending_physician, $rx_file);
					}
				}
				break;
				case ($request_type == 'Emergency'):
					$this->loa_form_validation('Emergency');
					// check if the selected healthcare provider exist from database
					$hp_exist = $this->loa_model->db_check_healthcare_provider_exist($hp_id);
					if (!$hp_exist) {
						$response = [
							'status' => 'save-error',
							'message' => 'Invalid Healthcare Provider'
						];
						echo json_encode($response);
						exit();
					} else {
							// Call function insert_loa
							$this->insert_loa($input_post, "", "","");
					}
				break;
			default:
				$response = [
					'status' => 'save-error',
					'message' => 'Please Select Valid Request Type'
				];
				echo json_encode($response);
		}
	}

	function insert_loa($input_post, $med_services, $attending_physician, $rx_file) {
		// select the max loa_id from DB
		$result = $this->loa_model->db_get_max_loa_id();
		$max_loa_id = !$result ? 0 : $result['loa_id'];
		$add_loa = $max_loa_id + 1;
		$current_year = date('Y');
		// call function loa_number
		$loa_no = $this->loa_number($add_loa, 7, 'LOA-'.$current_year);
		$emp_id = $input_post['emp-id'];
		$member = $this->loa_model->db_get_member_infos($emp_id);

		$post_data = [
			'loa_no' => $loa_no,
			'emp_id' => $emp_id,
			'first_name' =>  $member['first_name'],
			'middle_name' =>  $member['middle_name'],
			'last_name' =>  $member['last_name'],
			'suffix' =>  $member['suffix'],
			'hcare_provider' => $input_post['healthcare-provider'],
			'loa_request_type' => $input_post['loa-request-type'],
			'med_services' => ($med_services !== "")? implode(';',$med_services) : "",
			'health_card_no' => $member['health_card_no'],
			'requesting_company' => $member['company'],
			'request_date' => date("Y-m-d"), 
			'emerg_date' => !empty($input_post['hopitalized-date']) ? date('Y-m-d', strtotime($input_post['hopitalized-date'])) : '0000-00-00',
			'chief_complaint' => (isset($input_post['chief-complaint']))?strip_tags($input_post['chief-complaint']):"",
			'requesting_physician' =>(isset($input_post['requesting-physician']))? ucwords($input_post['requesting-physician']):"",
			'attending_physician' => $attending_physician,
			'rx_file' => $rx_file,
			'status' => 'Pending',
			'requested_by' => $this->session->userdata('doctor_id'),
			'override_by' => $input_post['doctor-id'],
		];

		$inserted = $this->loa_model->db_insert_loa_request($post_data);
		// if loa request is not inserted
		if (!$inserted) {
			$response = [
				'status' => 'save-error',
				'message' => 'LOA Request Failed'
			];
		}
		$response = [
			'status' => 'success',
			'message' => 'LOA Request Save Successfully'
		];
		echo json_encode($response);
	}



}
