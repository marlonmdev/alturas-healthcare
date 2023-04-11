<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
          redirect(base_url());
        }
    }

    function fetch_pending_loa_requests(){
			$this->security->get_csrf_hash();
			$status = 'Pending';
			$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
			$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
			$cost_types = $this->loa_model->db_get_cost_types();
			$data = [];
			foreach ($list as $loa) {
				$ct_array = $row = [];
				$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

				$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

				$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

				$custom_date = date("m/d/Y", strtotime($loa['request_date']));

				/* Checking if the work_related column is empty. If it is empty, it will display the status column.
				If it is not empty, it will display the text "for Approval". */
				if($loa['work_related'] == ''){
					$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';
				}else{
					$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">for Approval</span></div>';
				}

				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				// initialize multiple varibles at once
				$view_file = $short_med_services = '';
				if ($loa['loa_request_type'] === 'Consultation') {
					// if request is consultation set the view file and medical services to None
					$view_file = $short_med_services = 'None';
				} else {
					// convert into array members selected cost types/med_services using PHP explode
					$selected_cost_types = explode(';', $loa['med_services']);
					// loop through all the cost types from DB
					foreach ($cost_types as $cost_type) :
						if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
							array_push($ct_array, $cost_type['item_description']);
						endif;
					endforeach;
					// convert array to string and add comma as a separator using PHP implode
					$med_services = implode(', ', $ct_array);
					// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
					$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
					// link to the file attached during loa request
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}

				// this data will be rendered to the datatable
				$row[] = $custom_loa_no;
				$row[] = $full_name;
				$row[] = $loa['loa_request_type'];
				$row[] = $short_med_services;
				$row[] = $view_file;
				$row[] = $custom_date;
				$row[] = $custom_status;
				$row[] = $custom_actions;
				$data[] = $row;
			}

			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->loa_model->count_all($status, $hcare_provider_id),
				"recordsFiltered" => $this->loa_model->count_filtered($status, $hcare_provider_id),
				"data" => $data,
			];
			echo json_encode($output);
    }

    function fetch_approved_loa_requests(){
      		$this->security->get_csrf_hash();
			$status = 'Approved';
      		$hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
			$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
			$cost_types = $this->loa_model->db_get_cost_types();
			$data = [];
			foreach ($list as $loa) {
				$ct_array = $row = [];
				$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

				$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

				$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

				$custom_date = date("m/d/Y", strtotime($loa['request_date']));

				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $loa['status'] . '</span></div>';

				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				// initialize multiple varibles at once
				$view_file = $short_med_services = '';
				if ($loa['loa_request_type'] === 'Consultation') {
					// if request is consultation set the view file and medical services to None
					$view_file = $short_med_services = 'None';
				} else {
					// convert into array members selected cost types/med_services using PHP explode
					$selected_cost_types = explode(';', $loa['med_services']);
					// loop through all the cost types from DB
					foreach ($cost_types as $cost_type) :
						if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
							array_push($ct_array, $cost_type['item_description']);
						endif;
					endforeach;
					// convert array to string and add comma as a separator using PHP implode
					$med_services = implode(', ', $ct_array);
					// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
					$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
					// link to the file attached during loa request
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}

				// this data will be rendered to the datatable
				$row[] = $custom_loa_no;
				$row[] = $full_name;
				$row[] = $loa['loa_request_type'];
				$row[] = $short_med_services;
				$row[] = $view_file;
				$row[] = $custom_date;
				$row[] = $custom_status;
				$row[] = $custom_actions;
				$data[] = $row;
			}

			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->loa_model->count_all($status, $hcare_provider_id),
				"recordsFiltered" => $this->loa_model->count_filtered($status, $hcare_provider_id),
				"data" => $data,
			];
			echo json_encode($output);
    }

    function fetch_disapproved_loa_requests(){
      $this->security->get_csrf_hash();
			$status = 'Disapproved';
      $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
			$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
			$cost_types = $this->loa_model->db_get_cost_types();
			$data = [];
			foreach ($list as $loa) {
				$ct_array = $row = [];
				$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

				$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

				$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

				$custom_date = date("m/d/Y", strtotime($loa['request_date']));

				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $loa['status'] . '</span></div>';

				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				// initialize multiple varibles at once
				$view_file = $short_med_services = '';
				if ($loa['loa_request_type'] === 'Consultation') {
					// if request is consultation set the view file and medical services to None
					$view_file = $short_med_services = 'None';
				} else {
					// convert into array members selected cost types/med_services using PHP explode
					$selected_cost_types = explode(';', $loa['med_services']);
					// loop through all the cost types from DB
					foreach ($cost_types as $cost_type) :
						if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
							array_push($ct_array, $cost_type['item_description']);
						endif;
					endforeach;
					// convert array to string and add comma as a separator using PHP implode
					$med_services = implode(', ', $ct_array);
					// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
					$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
					// link to the file attached during loa request
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}

				// this data will be rendered to the datatable
				$row[] = $custom_loa_no;
				$row[] = $full_name;
				$row[] = $loa['loa_request_type'];
				$row[] = $short_med_services;
				$row[] = $view_file;
				$row[] = $custom_date;
				$row[] = $custom_status;
				$row[] = $custom_actions;
				$data[] = $row;
			}

			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->loa_model->count_all($status, $hcare_provider_id),
				"recordsFiltered" => $this->loa_model->count_filtered($status, $hcare_provider_id),
				"data" => $data,
			];
			echo json_encode($output);
    }

    function fetch_completed_loa_requests(){
      $this->security->get_csrf_hash();
			$status = 'Completed';
      $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
			$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
			$cost_types = $this->loa_model->db_get_cost_types();
			$data = [];
			foreach ($list as $loa) {
				$ct_array = $row = [];
				$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

				$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

				$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

				$custom_date = date("m/d/Y", strtotime($loa['request_date']));

				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $loa['status'] . '</span></div>';

				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				// initialize multiple varibles at once
				$view_file = $short_med_services = '';
				if ($loa['loa_request_type'] === 'Consultation') {
					// if request is consultation set the view file and medical services to None
					$view_file = $short_med_services = 'None';
				} else {
					// convert into array members selected cost types/med_services using PHP explode
					$selected_cost_types = explode(';', $loa['med_services']);
					// loop through all the cost types from DB
					foreach ($cost_types as $cost_type) :
						if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
							array_push($ct_array, $cost_type['item_description']);
						endif;
					endforeach;
					// convert array to string and add comma as a separator using PHP implode
					$med_services = implode(', ', $ct_array);
					// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
					$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
					// link to the file attached during loa request
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}

				// this data will be rendered to the datatable
				$row[] = $custom_loa_no;
				$row[] = $full_name;
				$row[] = $loa['loa_request_type'];
				$row[] = $short_med_services;
				$row[] = $view_file;
				$row[] = $custom_date;
				$row[] = $custom_status;
				$row[] = $custom_actions;
				$data[] = $row;
			}

			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->loa_model->count_all($status, $hcare_provider_id),
				"recordsFiltered" => $this->loa_model->count_filtered($status, $hcare_provider_id),
				"data" => $data,
			];
			echo json_encode($output);
    }

		function fetch_billed_loa_requests(){
      $this->security->get_csrf_hash();
			$status = 'Billed';
      $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
			$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
			$cost_types = $this->loa_model->db_get_cost_types();
			$data = [];
			foreach ($list as $loa) {
				$ct_array = $row = [];
				$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

				$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

				$custom_loa_no = 	'<mark class="bg-primary text-white">'.$loa['loa_no'].'</mark>';

				$custom_date = date("m/d/Y", strtotime($loa['request_date']));

				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-cyan">' . $loa['status'] . '</span></div>';

				$custom_actions = '<a href="JavaScript:void(0)" onclick="viewLoaInfo(\'' . $loa_id . '\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

				// initialize multiple varibles at once
				$view_file = $short_med_services = '';
				if ($loa['loa_request_type'] === 'Consultation') {
					// if request is consultation set the view file and medical services to None
					$view_file = $short_med_services = 'None';
				} else {
					// convert into array members selected cost types/med_services using PHP explode
					$selected_cost_types = explode(';', $loa['med_services']);
					// loop through all the cost types from DB
					foreach ($cost_types as $cost_type) :
						if (in_array($cost_type['ctype_id'], $selected_cost_types)) :
							array_push($ct_array, $cost_type['item_description']);
						endif;
					endforeach;
					// convert array to string and add comma as a separator using PHP implode
					$med_services = implode(', ', $ct_array);
					// if medical services are too long for displaying to the table shorten it and add the ... characters at the end 
					$short_med_services = strlen($med_services) > 35 ? substr($med_services, 0, 35) . "..." : $med_services;
					// link to the file attached during loa request
					$view_file = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/loa_attachments/' . $loa['rx_file'] . '\')"><strong>View</strong></a>';
				}

				// this data will be rendered to the datatable
				$row[] = $custom_loa_no;
				$row[] = $full_name;
				$row[] = $loa['loa_request_type'];
				$row[] = $short_med_services;
				$row[] = $view_file;
				$row[] = $custom_date;
				$row[] = $custom_status;
				$row[] = $custom_actions;
				$data[] = $row;
			}

			$output = [
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->loa_model->count_all($status, $hcare_provider_id),
				"recordsFiltered" => $this->loa_model->count_filtered($status, $hcare_provider_id),
				"data" => $data,
			];
			echo json_encode($output);
    }


  function get_pending_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['work_related'] != '' ? 'for Approval': $row['status'],
			'work_related' => $row['work_related'],
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

  function get_approved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);
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
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_disapproved_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);
		$doctor_name = "";
		if ($row['disapproved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from Database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y");
		// get selected medical services
		$selected_cost_types = explode(';', $row['med_services']);
		$ct_array = [];
		foreach ($cost_types as $cost_type) :
			if (in_array($cost_type['ctype_id'], $selected_cost_types)) {
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

	function get_completed_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);
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
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}


	function get_billed_loa_info() {
		$loa_id =  $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);
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
				array_push($ct_array, '[ <span class="text-success">'.$cost_type['item_description'].'</span> ]');
			}
		endforeach;
		$med_serv = implode(' ', $ct_array);

		$response = [
			'status' => 'success',
			'token' => $this->security->get_csrf_hash(),
			'loa_id' => $row['loa_id'],
			'loa_no' => $row['loa_no'],
			'first_name' => $row['first_name'],
			'middle_name' => $row['middle_name'],
			'last_name' => $row['last_name'],
			'suffix' => $row['suffix'],
			'date_of_birth' => 	date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'blood_type' => $row['blood_type'],
			'philhealth_no' => $row['philhealth_no'],
			'contact_no' => $row['contact_no'],
			'home_address' => $row['home_address'],
			'city_address' => $row['city_address'],
			'email' => $row['email'],
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
			'requesting_physician' => $row['doctor_name'],
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'work_related' => $row['work_related'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

}
