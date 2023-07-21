<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members_controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('company_doctor/members_model');
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

	function fetch_all_members() {
		$this->security->get_csrf_hash();
		$list = $this->members_model->get_datatables();
		$data = array();
		foreach ($list as $member) {
			$row = array();
			$member_id = $this->myhash->hasher($member['member_id'], 'encrypt');

			$full_name = $member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name'] . ' ' . $member['suffix'];
			$view_url = base_url() . 'company-doctor/member/view/' . $member_id;

			if($member['approval_status'] == 'Done'){
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">Approved</span></div>';
			}else{
				$custom_status = '<div class="text-center"><span class="badge rounded-pill bg-success">' . $member['approval_status'] . '</span></div>';
			}

			$custom_actions = '<a href="' . $view_url . '"  data-bs-toggle="tooltip" title="View Member Profile"><i class="mdi mdi-account-card-details fs-2 text-info"></i></a>';

			$custom_actions .= '<a href="' . base_url() . 'company-doctor/member/view/files/' . $member_id . '"  data-bs-toggle="tooltip" title="View Files"><i class="mdi mdi-file-multiple fs-2 text-danger ps-2"></i></a>';

			// this data will be rendered to the datatable
			$row[] = $member['member_id'];
			$row[] = $full_name;
			$row[] = $member['emp_type'];
			$row[] = $member['current_status'];
			$row[] = $member['business_unit'];
			$row[] = $member['dept_name'];
			$row[] = $custom_status;
			$row[] = $custom_actions;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->members_model->count_all(),
			"recordsFiltered" => $this->members_model->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function view_member_info() {
		$member_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
		$data['member'] = $member = $this->members_model->db_get_member_details($member_id);
		$data['mbl'] = $this->members_model->db_get_member_mbl($member['emp_id']);
		$data['page_title'] = 'HMO - Company Doctor';
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('company_doctor_panel/members/member_profile');
		$this->load->view('templates/footer');
	}
}
