<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'hc-provider-front-desk') {
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

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

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
						array_push($ct_array, $cost_type['cost_type']);
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
			$row[] = $loa['loa_no'];
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

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

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
						array_push($ct_array, $cost_type['cost_type']);
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
			$row[] = $loa['loa_no'];
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

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

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
						array_push($ct_array, $cost_type['cost_type']);
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
			$row[] = $loa['loa_no'];
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

    function fetch_closed_loa_requests(){
        $this->security->get_csrf_hash();
		$status = 'Closed';
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->loa_model->get_datatables($status, $hcare_provider_id);
		$cost_types = $this->loa_model->db_get_cost_types();
		$data = [];
		foreach ($list as $loa) {
			$ct_array = $row = [];
			$loa_id = $this->myhash->hasher($loa['loa_id'], 'encrypt');

			$full_name = $loa['first_name'] . ' ' . $loa['middle_name'] . ' ' . $loa['last_name'] . ' ' . $loa['suffix'];

			$custom_date = date("m/d/Y", strtotime($loa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $loa['status'] . '</span></div>';

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
						array_push($ct_array, $cost_type['cost_type']);
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
			$row[] = $loa['loa_no'];
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

    function pending_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');

        $members = $this->loa_model->fetch_pending_loa_requests($hcare_provider_id);
        $payload_data = [];


        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;
            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/pending_loa_list', array('members' => $payload_data));
        $this->load->view('templates/footer');
    }


    function approved_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');    

        $members = $this->loa_model->fetch_approved_loa_requests($hcare_provider_id);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/approved_loa_list', array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

    function disapproved_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->fetch_disapproved_loa_requests($userHospital);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/disapproved_loa_list',  array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

    function closed_loa_requests() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->fetch_closed_loa_requests($userHospital);
        $payload_data = [];

        foreach ($members as $member) :
            $payload_med_services = [];
            $exploded_med_services = explode(";", $member->med_services);

            foreach ($exploded_med_services as $extracted_med_service_id) :

                $result_med_services = $this->loa_model->get_cost_type($extracted_med_service_id);
                array_push($payload_med_services, $result_med_services);

            endforeach;

            $member->med_services = $payload_med_services;
            array_push($payload_data, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/closed_loa_list',  array('members' => $payload_data));
        $this->load->view('templates/footer');
    }

    function get_loa_info() {
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
				array_push($ct_array, $cost_type['cost_type']);
			}
		endforeach;
		$med_serv = implode(', ', $ct_array);

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
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

}
