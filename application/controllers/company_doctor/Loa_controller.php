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

			if($loa['spot_report_file'] && $loa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning"></i></a>';
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
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file and medical services to None
				// $short_med_services = 'Npne';
				$view_file = 'None';
				$short_hp_name = strlen($loa['hp_name']) > 24 ? substr($loa['hp_name'], 0, 24) . "..." : $loa['hp_name'];
			}else{

				// if Hospital Name is too long for displaying to the table shorten it and add the ... characters at the end 
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

			if($loa['spot_report_file'] && $loa['incident_report_file'] != ''){
				$custom_actions .= '<a href="JavaScript:void(0)" onclick="viewReports(\'' . $loa_id . '\',\'' . $loa['work_related'] . '\',\'' . $loa['percentage'] . '\',\'' . $loa['spot_report_file'] . '\',\'' . $loa['incident_report_file'] . '\')" data-bs-toggle="tooltip" title="View Uploaded Reports"><i class="mdi mdi-teamviewer fs-2 text-warning ps-2"></i></a>';
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
			$post_data = [
				'status'          => 'Approved',
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
		$data['req'] = $this->loa_model->db_get_doctor_by_id($exist['requesting_physician']);
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
			$custom_actions .= '<a href="JavaScript:void(0)" onclick="showBackDateForm(\'' . $loa_id . '\', \'' . $loa['loa_no'] . '\', \''.$loa['expiration_date'].'\')" data-bs-toggle="tooltip" title="Date Extension"><i class="mdi mdi-border-color fs-2 text-cyan"></i></a>';

			// initialize multiple varibles at once
			$view_file = $short_hp_name = '';
			if ($loa['loa_request_type'] === 'Consultation') {
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
		$status = 'closed';
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
			if ($loa['loa_request_type'] === 'Consultation') {
				// if request is consultation set the view file to None
				$view_file =  'None';
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
		$status = 'Reffered';
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
			if ($loa['loa_request_type'] === 'Consultation') {
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
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			endif;
		endforeach;
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
			'med_services' => $med_serv,
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
			'paid_on' => $paid_on
		];
		echo json_encode($response);
	}

	function approve_loa_request() {
		$token = $this->security->get_csrf_hash();
		$loa_id = $this->myhash->hasher($this->input->post('loa-id', TRUE), 'decrypt');
		$expiration_type = $this->input->post('expiration-type', TRUE);
		$expiration_date = $this->input->post('expiration-date', TRUE);
		$approved_by = $this->session->userdata('doctor_id');
		$approved_on = date("Y-m-d");

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

		$data = [
			'performed_fees'  => 'Approved',
			'status'          => 'Approved',
			'approved_by'     => $approved_by,
			'approved_on'     => $approved_on,
			'expiration_date' => $expired_on
		];

		$approved = $this->loa_model->db_approve_loa_request($loa_id, $data);

		if ($approved) {
			$response = ['token' => $token, 'status' => 'success', 'message' => 'LOA Request Approved Successfully'];
		}else{
			$response = ['token' => $token, 'status' => 'save-error', 'message' => 'Unable to Approve LOA Request'];
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


}
