<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('ho_iad/transaction_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'head-office-iad') {
			redirect(base_url());
		}
    }
    function redirectBack() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:http://' . $_SERVER['SERVER_NAME']);
        }
        exit();
    }

    function search() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('ho_iad_panel/transaction/search');
        $this->load->view('templates/footer');
    }

    function search_by_healthcard() {
        $this->security->get_csrf_hash();
        $healthcard_no = $this->security->xss_clean($this->input->post('healthcard_no'));
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $member = $this->transaction_model->get_member_by_healthcard($healthcard_no);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->transaction_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->transaction_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->transaction_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->transaction_model->get_member_noa($member['emp_id'], $hcare_provider_id);

            /* This is checking if the image file exists in the directory. */
            $file_path = './uploads/profile_pics/' . $member['photo'];
            $data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

            $this->session->set_userdata([
                'b_member_info'    => $member,
                'b_member_mbl'     => $member_mbl['max_benefit_limit'],
                'b_member_bal'     => $member_mbl['remaining_balance'],
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no'  => $member['health_card_no'],
            ]);

            $this->load->view('templates/header', $data);
            $this->load->view('ho_iad_panel/transaction/summary_of_billing');
            $this->load->view('templates/footer');
        }
    }

    function search_by_name() {
        $this->security->get_csrf_hash();
        $first_name = $this->security->xss_clean($this->input->post('first_name'));
        $last_name = $this->security->xss_clean($this->input->post('last_name'));
        $date_of_birth = $this->security->xss_clean($this->input->post('date_of_birth'));
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $member = $this->transaction_model->get_member_by_name($first_name, $last_name, $date_of_birth);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->transaction_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->transaction_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->transaction_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->transaction_model->get_member_noa($member['emp_id'], $hcare_provider_id);

            $this->session->set_userdata([
                'b_member_info'    => $member,
                'b_member_mbl'     => $member_mbl['max_benefit_limit'],
                'b_member_bal'     => $member_mbl['remaining_balance'],
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no'  => $member['health_card_no'],
            ]);

            $this->load->view('templates/header', $data);
            $this->load->view('ho_iad_panel/transaction/summary_of_billing');
            $this->load->view('templates/footer');
        }
    }
}
		