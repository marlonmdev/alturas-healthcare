<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/search_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
            redirect(base_url());
        }
    }

    function search_member_by_healthcard(){
        $token = $this->security->get_csrf_hash();
        $healthcard_no = $this->security->xss_clean($this->input->post('healthcard_no'));
        $exists = $row = $this->search_model->db_get_member_by_healthcard($healthcard_no);
        if(!$exists){
            $response = [
                'token' => $token,
                'status' => 'error',
                'message' => 'No Member Found!'
            ];
        }else{
            // Calculate Age based on Date of Birth
            $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
            $current_date = date("d-m-Y");
            $diff = date_diff(date_create($birth_date), date_create($current_date));
            $age = $diff->format("%y");
            // Format Max Benefit Limit
            $member_mbl = empty($row['max_benefit_limit']) ? 'None' : '&#8369;' . number_format($row['max_benefit_limit'], 2);
            // Format Remaining Balance
            $member_rmg_bal = empty($row['remaining_balance']) ? 'None' : '&#8369;' . number_format($row['remaining_balance'], 2);

            /* This is checking if the image file exists in the directory. */
            $file_path = './uploads/profile_pics/' . $row['photo'];
            // $data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';
            
            $response = [
                'token' => $token,
                'status' => 'success',
                'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
                'emp_id' => $row['emp_id'],
                'hcard_no' => $row['health_card_no'],
                'photo' => $row['photo'],
                'fullname' => $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'].' '.$row['suffix'],
                'gender' => $row['gender'],
                'date_of_birth' => date('F d, Y', strtotime($row['date_of_birth'])),
                'age' => $age,
                'civil_status' => $row['civil_status'],
                'spouse' => $row['spouse'],
                'philhealth_no' => $row['philhealth_no'],
                'blood_type' =>  $row['blood_type'],
                'height' => $row['height'],
                'weight' => $row['weight'],
                'home_address' => $row['home_address'],
                'city_address' => $row['city_address'] != '' ? $row['city_address'] : 'None',
                'contact_no' => $row['contact_no'],
                'email' => $row['email'],
                'position' => $row['position'],
                'pos_level' => $row['position_level'],
                'emp_type' => $row['emp_type'],
                'current_status' => $row['current_status'],
                'business_unit' => $row['business_unit'],
                'dept_name' => $row['dept_name'],
                'contact_person' => $row['contact_person'],
                'contact_person_addr' => $row['contact_person_addr'],
                'contact_person_no' => $row['contact_person_no'],
                'mbr_mbl' => $member_mbl,
                'mbr_rmg_bal' => $member_rmg_bal,
                'photo_status' => file_exists($file_path) ? 'Exist' : 'Not Found'
            ];
        }
        echo json_encode($response);
    }

    function search_member_by_name(){
        $token = $this->security->get_csrf_hash();
        $first_name = $this->security->xss_clean($this->input->post('first_name'));
        $last_name = $this->security->xss_clean($this->input->post('last_name'));
        $date_of_birth =  date("Y-m-d", strtotime($this->input->post('date_of_birth')));
        $exists = $row = $this->search_model->db_get_member_by_name($first_name, $last_name, $date_of_birth);
        if(!$exists){
            $response = [
                'token' => $token,
                'status' => 'error',
                'message' => 'No Member Found!'
            ];
        }else{
            // Calculate Age based on Date of Birth
            $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
            $current_date = date("d-m-Y");
            $diff = date_diff(date_create($birth_date), date_create($current_date));
            $age = $diff->format("%y");
            // Format Max Benefit Limit
            $member_mbl = empty($row['max_benefit_limit']) ? 'None' : '&#8369;' . number_format($row['max_benefit_limit'], 2);
            // Format Remaining Balance
            $member_rmg_bal = empty($row['remaining_balance']) ? 'None' : '&#8369;' . number_format($row['remaining_balance'], 2);
            /* This is checking if the image file exists in the directory. */
            $file_path = './uploads/profile_pics/' . $row['photo'];

            $response = [
                'token' => $token,
                'status' => 'success',
                'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
                'emp_id' => $row['emp_id'],
                'hcard_no' => $row['health_card_no'],
                'photo' => $row['photo'],
                'fullname' => $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'].' '.$row['suffix'],
                'gender' => $row['gender'],
                'date_of_birth' => date('F d, Y', strtotime($row['date_of_birth'])),
                'age' => $age,
                'civil_status' => $row['civil_status'],
                'spouse' => $row['spouse'],
                'philhealth_no' => $row['philhealth_no'],
                'blood_type' =>  $row['blood_type'],
                'height' => $row['height'],
                'weight' => $row['weight'],
                'home_address' => $row['home_address'],
                'city_address' => $row['city_address'] != '' ? $row['city_address'] : 'None',
                'contact_no' => $row['contact_no'],
                'email' => $row['email'],
                'position' => $row['position'],
                'pos_level' => $row['position_level'],
                'emp_type' => $row['emp_type'],
                'current_status' => $row['current_status'],
                'business_unit' => $row['business_unit'],
                'dept_name' => $row['dept_name'],
                'contact_person' => $row['contact_person'],
                'contact_person_addr' => $row['contact_person_addr'],
                'contact_person_no' => $row['contact_person_no'],
                'requesting_company' => $row['company'],
                'mbr_mbl' => $member_mbl,
                'mbr_rm_mbl' => $member_rmg_bal,
                'photo_status' => file_exists($file_path) ? 'Exist' : 'Not Found'
            ];
        }
        echo json_encode($response);
    }


}
