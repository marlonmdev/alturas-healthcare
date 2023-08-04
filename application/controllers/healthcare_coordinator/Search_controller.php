<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Search_controller extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('healthcare_coordinator/search_model');
    $user_role = $this->session->userdata('user_role');
    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
      redirect(base_url());
    }
  }

  function search_autocomplete() {
    $this->security->get_csrf_hash();
    $search_data = $this->input->post('search');
    $result = $this->search_model->get_autocomplete($search_data);
    if (!empty($result)) {
      foreach ($result as $row) :
        $member_id = $this->myhash->hasher($row['member_id'], 'encrypt');
        echo '<strong class="d-block mx-2 p-1 my-1"><a href="#" onclick="getMemberValues(\'' . $member_id . '\')" class="text-secondary" data-toggle="tooltip" data-placement="top" title="Click to fill form with Data">'
                . $row['first_name'] . ' '
                . $row['middle_name'] . ' '
                . $row['last_name'] . ' '
                . $row['suffix'] . '</a></strong>';
      endforeach;
    }else{
      echo "<p class='text-center mt-1'><em>No data found...</em></p>";
    }
  }

  //==================================================
  //Get Deteails for Emergency Form
  //==================================================
  function get_searched_member_details() {
    $type = $this->uri->segment(4);
    if($type === 'search1'){
      $member_id = $this->uri->segment(5);
      $row = $this->search_model->db_get_member_details1($member_id);
    }else{
      $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
      $row = $this->search_model->db_get_member_details($member_id);
    }
       
    $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
    $current_date = date("d-m-Y");
    $diff = date_diff(date_create($birth_date), date_create($current_date));
    $age = $diff->format("%y");

    $response = [
      'status' => 'success',
      'token' => $this->security->get_csrf_hash(),
      'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
      'emp_id' => $row['emp_id'],
      'first_name' => $row['first_name'],
      'middle_name' => $row['middle_name'],
      'last_name' => $row['last_name'],
      'suffix' => $row['suffix'],
      'date_of_birth' => $row['date_of_birth'],
      'age' => $age,
      'gender' => $row['gender'],
      'philhealth_no' => $row['philhealth_no'],
      'blood_type' =>  $row['blood_type'],
      'home_address' => $row['home_address'],
      'city_address' => $row['city_address'],
      'contact_no' => $row['contact_no'],
      'email' => $row['email'],
      'contact_person' => $row['contact_person'],
      'contact_person_no' => $row['contact_person_no'],
      'contact_person_addr' => $row['contact_person_addr'],
      'health_card_no' => $row['health_card_no'],
      'requesting_company' => $row['company'],
      'mbl' => $row['remaining_balance']
    ];
    echo json_encode($response);
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //Get Details for Diagnostic Form
  //==================================================
  function get_details_for_diagnostic() {
    $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
    $row = $this->search_model->db_get_member_details($member_id);
    $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
    $current_date = date("d-m-Y");
    $diff = date_diff(date_create($birth_date), date_create($current_date));
    $age = $diff->format("%y");

    $response = [
      'status' => 'success',
      'token' => $this->security->get_csrf_hash(),
      'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
      'emp_id' => $row['emp_id'],
      'first_name' => $row['first_name'],
      'middle_name' => $row['middle_name'],
      'last_name' => $row['last_name'],
      'suffix' => $row['suffix'],
      'date_of_birth' => $row['date_of_birth'],
      'age' => $age,
      'gender' => $row['gender'],
      'philhealth_no' => $row['philhealth_no'],
      'blood_type' =>  $row['blood_type'],
      'home_address' => $row['home_address'],
      'city_address' => $row['city_address'],
      'contact_no' => $row['contact_no'],
      'email' => $row['email'],
      'contact_person' => $row['contact_person'],
      'contact_person_no' => $row['contact_person_no'],
      'contact_person_addr' => $row['contact_person_addr'],
      'mbl' => $row['remaining_balance'],
      'health_card_no' => $row['health_card_no'],
      'requesting_company' => $row['company'],
      
    ];
    echo json_encode($response);
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //Get Deteails for Admission Form
  //==================================================
  function get_searched_member_details_for_noa() {
    $type = $this->uri->segment(4);
    if($type === 'search1'){
      $member_id = $this->uri->segment(5);
      $row = $this->search_model->db_get_member_details1($member_id);
      // var_dump($member_id);
      // var_dump($row);
    }else{
      $member_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
      $row = $this->search_model->db_get_member_details($member_id);
      // var_dump($member_id);
      // var_dump($row);
    }
    // var_dump($type === 'search1');
       
    $birth_date = date("d-m-Y", strtotime($row['date_of_birth']));
    $current_date = date("d-m-Y");
    $diff = date_diff(date_create($birth_date), date_create($current_date));
    $age = $diff->format("%y");

    $response = [
      'status' => 'success',
      'token' => $this->security->get_csrf_hash(),
      'member_id' => $this->myhash->hasher($row['member_id'], 'encrypt'),
      'emp_id' => $row['emp_id'],
      'first_name' => $row['first_name'],
      'middle_name' => $row['middle_name'],
      'last_name' => $row['last_name'],
      'suffix' => $row['suffix'],
      'date_of_birth' => $row['date_of_birth'],
      'age' => $age,
      'gender' => $row['gender'],
      'philhealth_no' => $row['philhealth_no'],
      'blood_type' =>  $row['blood_type'],
      'home_address' => $row['home_address'],
      'city_address' => $row['city_address'],
      'contact_no' => $row['contact_no'],
      'email' => $row['email'],
      'contact_person' => $row['contact_person'],
      'contact_person_no' => $row['contact_person_no'],
      'contact_person_addr' => $row['contact_person_addr'],
      'health_card_no' => $row['health_card_no'],
      'requesting_company' => $row['company'],
      'mbl' => $row['remaining_balance']
    ];
    echo json_encode($response);
  }
  //==================================================
  //END
  //==================================================
}
