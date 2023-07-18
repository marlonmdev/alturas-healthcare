<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hospitals_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'super-admin') {
			redirect(base_url());
		}
	}

	function fetch_all_affiliate_hospitals() {
		$this->load->model('super_admin/setup_model');
		$resultList = $this->setup_model->db_get_all_affiliate_hospitals();
		$result = [];
		foreach ($resultList as $key => $value) {
			$button = '<div class="btn-group">';
			$button .= '<button type="button" class="btn btn-sm btn-info" onclick="viewUserAccount(' . $value['hospital_id'] . ')" data-toggle="tooltip" data-placement="top" title="View Details"><i class="bx bxs-show"></i></button> ';

			$button .= '<button type="button" class="btn btn-sm btn-success" onclick="editAffiliateHospital(' . $value['hospital_id'] . ')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-edit"></i></button> ';

			$button .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteAffiliateHospital(' . $value['hospital_id'] . ')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bx bxs-trash"></i></button>';
			$button .= '</div>';

			$result['data'][] = [
				$value['hospital_id'],
				$value['hospital_name'],
				$value['hospital_address'],
				date("m/d/Y", strtotime($value['date_added'])),
				$button
			];
		}
		echo json_encode($result);
	}
}
