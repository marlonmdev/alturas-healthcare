<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_controller extends CI_Controller {

    function __construct() {
		parent::__construct();
        $this->load->model('healthcare_provider/noa_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
			redirect(base_url());
		}
	}

    function fetch_pending_noa_requests() {
		$this->security->get_csrf_hash();
		$status = 'Pending';
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->noa_model->get_datatables($status, $hcare_provider_id);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-warning">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a class="me-2" href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';
			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $noa['noa_no'];
			$row[] = $full_name;
			$row[] = $short_hosp_name;
			$row[] = $admission_date;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status, $hcare_provider_id),
			"recordsFiltered" => $this->noa_model->count_filtered($status, $hcare_provider_id),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_approved_noa_requests() {
		$this->security->get_csrf_hash();
		$status = 'Approved';
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->noa_model->get_datatables($status, $hcare_provider_id);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $noa['noa_no'];
			$row[] = $full_name;
			$row[] = $short_hosp_name;
			$row[] = $admission_date;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status, $hcare_provider_id),
			"recordsFiltered" => $this->noa_model->count_filtered($status, $hcare_provider_id),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_disapproved_noa_requests() {
		$this->security->get_csrf_hash();
		$status = 'Disapproved';
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->noa_model->get_datatables($status, $hcare_provider_id);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-danger">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $noa['noa_no'];
			$row[] = $full_name;
			$row[] = $short_hosp_name;
			$row[] = $admission_date;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status, $hcare_provider_id),
			"recordsFiltered" => $this->noa_model->count_filtered($status, $hcare_provider_id),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function fetch_closed_noa_requests() {
		$this->security->get_csrf_hash();
		$status = 'Closed';
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
		$list = $this->noa_model->get_datatables($status, $hcare_provider_id);
		$data = [];
		foreach ($list as $noa) {
			$noa_id = $this->myhash->hasher($noa['noa_id'], 'encrypt');
			$row = [];
			$full_name = $noa['first_name'] . ' ' . $noa['middle_name'] . ' ' . $noa['last_name'] . ' ' . $noa['suffix'];

			$admission_date = date("m/d/Y", strtotime($noa['admission_date']));
			$request_date = date("m/d/Y", strtotime($noa['request_date']));

			$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-info">' . $noa['status'] . '</span></div>';

			$custom_actions = '<a href="JavaScript:void(0)" onclick="viewNoaInfo(\'' . $noa_id . '\')" data-bs-toggle="tooltip" title="View NOA"><i class="mdi mdi-information fs-2 text-info"></i></a>';

			// shorten name of values from db if its too long for viewing and add ...
			$short_hosp_name = strlen($noa['hp_name']) > 24 ? substr($noa['hp_name'], 0, 24) . "..." : $noa['hp_name'];

			// this data will be rendered to the datatable
			$row[] = $noa['noa_no'];
			$row[] = $full_name;
			$row[] = $short_hosp_name;
			$row[] = $admission_date;
			$row[] = $request_date;
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->noa_model->count_all($status, $hcare_provider_id),
			"recordsFiltered" => $this->noa_model->count_filtered($status, $hcare_provider_id),
			"data" => $data,
		);

		echo json_encode($output);
	}

	function get_noa_info() {
		$noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
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
			// Full Month Date Year Format (F d Y)
			'request_date' => date("F d, Y", strtotime($row['request_date'])),
			'work_related' => $row['work_related'],
			'req_status' => $row['status'],
			'approved_by' => $doctor_name,
			'approved_on' => date("F d, Y", strtotime($row['approved_on'])),
			'disapproved_by' => $doctor_name,
			'disapprove_reason' => $row['disapprove_reason'],
			'disapproved_on' => date("F d, Y", strtotime($row['disapproved_on'])),
			'member_mbl' => number_format($row['max_benefit_limit'], 2),
			'remaining_mbl' => number_format($row['remaining_balance'], 2),
		);

		echo json_encode($response);
	}

}
