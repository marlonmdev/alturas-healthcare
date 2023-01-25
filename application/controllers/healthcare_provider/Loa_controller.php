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

    function loaRequestListPending() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->loa_member_pending($userHospital);
        $payLoadData = array();


        foreach ($members as $member) :
            $payLoadMedService = array();
            $expodedMedServices = explode(";", $member->med_services);


            foreach ($expodedMedServices as $extractMedServicesId) :

                $resultMedServices = $this->loa_model->get_cost_type($extractMedServicesId);
                array_push($payLoadMedService, $resultMedServices);
            endforeach;
            $member->med_services = $payLoadMedService;
            array_push($payLoadData, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/pending_loa_list', array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }


    function loaRequestListApproved() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');    
        $members = $this->loa_model->loa_member_approved($userHospital);

        $payLoadData = array();


        foreach ($members as $member) :
            $payLoadMedService = array();
            $expodedMedServices = explode(";", $member->med_services);

            foreach ($expodedMedServices as $extractMedServicesId) :

                $resultMedServices = $this->loa_model->get_cost_type($extractMedServicesId);
                array_push($payLoadMedService, $resultMedServices);
            endforeach;

            $member->med_services = $payLoadMedService;
            array_push($payLoadData, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/approved_loa_list', array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }


    function loaRequestListClosed() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $userHospital =  $this->session->userdata('dsg_hcare_prov');
        $members = $this->loa_model->loa_member_closed($userHospital);
        $payLoadData = array();

        foreach ($members as $member) :
            $payLoadMedService = array();
            $expodedMedServices = explode(";", $member->med_services);

            foreach ($expodedMedServices as $extractMedServicesId) :

                $resultMedServices = $this->loa_model->get_cost_type($extractMedServicesId);
                array_push($payLoadMedService, $resultMedServices);
            endforeach;

            $member->med_services = $payLoadMedService;
            array_push($payLoadData, $member);
        endforeach;

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/loa/closed_loa_list',  array('members' => $payLoadData));
        $this->load->view('templates/footer');
    }

    function get_loa_info() {
		$doctor_name = $requesting_physician = "";
		$loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$row = $this->loa_model->db_get_loa_info($loa_id);

		//check if requesting physician exist from DB
		$exist = $this->loa_model->db_get_requesting_physician($row['requesting_physician']);
		if (!$exist) {
			$requesting_physician = "Does not exist from Database";
		} else {
			$requesting_physician = $exist['doctor_name'];
		}


		if ($row['approved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['approved_by']);
			$doctor_name = $doc['doctor_name'];
		} elseif ($row['disapproved_by']) {
			$doc = $this->loa_model->db_get_doctor_by_id($row['disapproved_by']);
			$doctor_name = $doc['doctor_name'];
		} else {
			$doctor_name = "Does not exist from database";
		}

		$cost_types = $this->loa_model->db_get_cost_types();
		// Calculate Age Based on Date of Birth
		$birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
		$current_date = date("d-m-Y");
		$diff = date_diff(date_create($birth_date), date_create($current_date));
		$age = $diff->format("%y") . ' years old';

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
			'date_of_birth' => date("F d, Y", strtotime($row['date_of_birth'])),
			'age' => $age,
			'gender' => $row['gender'],
			'philhealth_no' => $row['philhealth_no'],
			'blood_type' => $row['blood_type'],
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
			'requesting_physician' => $requesting_physician,
			'attending_physician' => $row['attending_physician'],
			'rx_file' => $row['rx_file'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
            'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		];
		echo json_encode($response);
	}

}
