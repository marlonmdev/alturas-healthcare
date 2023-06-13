<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_profile_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/Member_profile_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'hc-provider-front-desk') {
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


    function memberProfile() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('hc_provider_front_desk_panel/member_profile/member_profile.php');
        $this->load->view('templates/footer');
    }

    function memberProfileInfoById() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');


        $healthCardNo =  $this->input->post('healthCardNo');

        $member = $this->Member_profile_model->find_member_profile_by_id($healthCardNo);
        if ($member) {
            $this->load->view('templates/header', $data);
            $this->load->view('hc_provider_front_desk_panel/member_profile/member_profile_info.php', array('member' => $member));
            $this->load->view('templates/footer');
        } else {
            $arr = array('error' => 'No Members Found!');
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        }
    }

    function memberProfileInfo() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');



        $firstNameMember = $this->input->post('firstNameMember');
        $lastNameMember = $this->input->post('lastNameMember');
        $dateMember =  $this->input->post('dateMember');

        $member = $this->Member_profile_model->find_member_profile($firstNameMember, $lastNameMember, $dateMember);
        if ($member) {
            $this->load->view('templates/header', $data);
            $this->load->view('hc_provider_front_desk_panel/member_profile/member_profile_info.php', array('member' => $member));
            $this->load->view('templates/footer');
        } else {
            $arr = array('error' => 'No Members Found!');
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        }
    }
    
    function memberProfileInfoMbl() {
        $idMember = $this->input->post('id');
        $token = $this->security->get_csrf_hash();
        $member = $this->Member_profile_model->find_member_profile($idMember);

        $data['page_title'] = 'HMO - HealthCare Provider';
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('hc_provider_front_desk_panel/member_profile/member_profile_info_mbl.php', array('member' => $member));
        $this->load->view('templates/footer');
    }
}
