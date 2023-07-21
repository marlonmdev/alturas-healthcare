<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_controller extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('company_doctor/setup_model');
    $user_role = $this->session->userdata('user_role');
    $logged_in = $this->session->userdata('logged_in');
    if($logged_in !== true && $user_role !== 'company-doctor') {
      redirect(base_url());
    }
  }

  // Start of Register Affiliate Hospital
  public function register_affiliate_hospital(){
    $token = $this->security->get_csrf_hash();
    $hospital_name = ucfirst(strip_tags($this->input->post('hospital-name')));
    $hospital_address = ucfirst(strip_tags($this->input->post('hospital-address')));
    $hospital_phone_number = strip_tags($this->input->post('hospital-phone-number'));

    $this->load->library('form_validation');
    $this->form_validation->set_rules('hospital-name', 'Hospital Name', 'trim|required|callback_check_hospital_name_exist');
    $this->form_validation->set_rules('hospital-address', 'Hospital Address', 'trim|required');
    $this->form_validation->set_rules('hospital-phone-number', 'Phone Number', 'required');

    if($this->form_validation->run() == FALSE){
      $response = array(
        'status' => 'error', 
        'hospital_name_error' => form_error('hospital-name'),
        'hospital_address_error' =>  form_error('hospital-address'),
        'hospital_phone_number_error' => form_error('hospital-phone-number')
      );
      
    }else{

      $post_data = array(
        'hospital_name' => $hospital_name,
        'hospital_address' => $hospital_address,
        'hospital_phone_no' => $hospital_phone_number,
        'date_added' => date("Y-m-d").' '.date("h:i:sa")
      );

      $this->load->model('healthcare_coordinator/setup_model');
      $saved = $this->setup_model->db_insert_affiliate_hospital($post_data);
      if($saved){
        $response = array ('status' => 'success', 'message' => 'Affiliate Hospital Saved Successfully!');
      }else{
        $response = array ('status' => 'save-error', 'message' => 'Affiliate Hospital Saved Failed!');
      }			
      
    }
    echo json_encode($response);
  }

  public function check_hospital_name_exist($hospital_name){
		$exists = $this->setup_model->db_check_hospital_name($hospital_name);
		if(!$exists){
			return true;
		}else{
			$this->form_validation->set_message('check_hospital_name_exist', 'Hospital Name Already Exists!');
			return false;
		}
	}

  public function fetch_all_affiliate_hospitals(){
		$resultList = $this->setup_model->db_get_all_affiliate_hospitals();
		$result = array();
		foreach ($resultList as $key => $value) {
      $button = '<div class="btn-group">';
			$button .= '<button type="button" class="btn btn-sm btn-info" onclick="viewUserAccount('.$value['hospital_id'].')" data-toggle="tooltip" data-placement="top" title="View Details"><i class="bx bxs-show"></i></button> ';

			$button .= '<button type="button" class="btn btn-sm btn-success" onclick="editAffiliateHospital('.$value['hospital_id'].')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-edit"></i></button> ';

			$button .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteAffiliateHospital('.$value['hospital_id'].')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bx bxs-trash"></i></button>';
      $button .= '</div>';

			$result['data'][] = array(
				$value['hospital_id'],
				$value['hospital_name'],
				$value['hospital_address'],
				date("m/d/Y", strtotime($value['date_added'])),
				$button
			);
		}
		echo json_encode($result);
	}

  
  public function edit_affiliate_hospital(){
    $hospital_id = $this->uri->segment(5);
    $this->load->model('admin_setup_model');
    $row = $this->admin_setup_model->db_get_hospital_info($hospital_id);

    $response = array(
      'status' => 'success', 
      'token' => $this->security->get_csrf_hash(),
      'hospital_id' => $row->hospital_id, 
      'hospital_name' => $row->hospital_name, 
      'hospital_address' => $row->hospital_address,
      'hospital_phone_number' => $row->hospital_phone_number, 
    );

    echo json_encode($response);
  }

}