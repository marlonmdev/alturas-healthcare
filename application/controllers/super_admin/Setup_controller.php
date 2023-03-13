<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_controller extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('super_admin/setup_model');
    $user_role = $this->session->userdata('user_role');
    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in !== true && $user_role !== 'super-admin') {
      redirect(base_url());
    }
  }

  function fetch_all_healthcare_providers() {
    $resultList = $this->setup_model->db_get_all_healthcare_providers();
    $result = [];
    foreach ($resultList as $key => $value) {
      $actions = '<a class="me-2" href="Javascript:void(0)" onclick="viewHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="View Details"><i class="mdi mdi-information fs-2 text-info"></i></a>';

      $actions .= '<a class="me-2" href="Javascript:void(0)" onclick="editHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

      $actions .= '<a href="Javascript:void(0)" onclick="deleteHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

      $result['data'][] = [
        $value['hp_id'],
        $value['hp_name'],
        $value['hp_type'],
        $value['hp_address'],
        $actions
      ];
    }
    echo json_encode($result);
  }

  function register_healthcare_provider() {
    $this->security->get_csrf_hash();
    $input_post = $this->input->post();
    $this->form_validation->set_rules('hp-type', 'Type', 'required');
    $this->form_validation->set_rules('hp-name', 'Name', 'trim|required|callback_check_healthcare_provider_exist');
    $this->form_validation->set_rules('hp-address', 'Address', 'trim|required');
    $this->form_validation->set_rules('hp-contact', 'Contact Number', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'type_error' => form_error('hp-type'),
        'name_error' => form_error('hp-name'),
        'address_error' =>  form_error('hp-address'),
        'contact_error' => form_error('hp-contact')
      ];
    } else {
      $post_data = [
        'hp_type' => strip_tags($input_post['hp-type']),
        'hp_name' => ucwords(strip_tags($input_post['hp-name'])),
        'hp_address' => strip_tags($input_post['hp-address']),
        'hp_contact' => strip_tags($input_post['hp-contact']),
        'date_added' => date("Y-m-d"),
        'date_updated' => date("Y-m-d")
      ];
      $saved = $this->setup_model->db_insert_healthcare_provider($post_data);
      if (!$saved) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Healthcare Provider Saved Failed'
        ];
      }
      $response = [
        'status' => 'success', 
        'message' => 'Healthcare Provider Saved Successfully'
      ];
    }
    echo json_encode($response);
  }


  function check_healthcare_provider_exist($hp_name) {
    $exists = $this->setup_model->db_check_healthcare_provider($hp_name);
    if (!$exists) {
      return true;
    } else {
      $this->form_validation->set_message('check_healthcare_provider_exist', 'Healthcare Provider Already Exists!');
      return false;
    }
  }

  function get_healthcare_provider_info() {
    $token = $this->security->get_csrf_hash();
    $hp_id = $this->uri->segment(5);
    $row = $this->setup_model->db_get_healthcare_provider_info($hp_id);
    $response = [
      'status' => 'success',
      'token' => $token,
      'hp_id' => $row['hp_id'],
      'hp_type' => $row['hp_type'],
      'hp_name' => $row['hp_name'],
      'hp_address' => $row['hp_address'],
      'hp_contact' => $row['hp_contact'],
      'date_added' => date("m/d/Y", strtotime($row['date_added'])),
      'date_updated' => date("m/d/Y", strtotime($row['date_updated'])),
    ];
    echo json_encode($response);
  }

  function update_healthcare_provider() {
    $this->security->get_csrf_hash();
    $input_post = $this->input->post();
    $hp_id = $this->input->post('hp-id');
    $this->form_validation->set_rules('hp-type', 'Type', 'required');
    $this->form_validation->set_rules('hp-name', 'Name', 'trim|required');
    $this->form_validation->set_rules('hp-address', 'Address', 'trim|required');
    $this->form_validation->set_rules('hp-contact', 'Contact Number', 'required');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'type_error' => form_error('hp-type'),
        'name_error' => form_error('hp-name'),
        'address_error' =>  form_error('hp-address'),
        'contact_error' => form_error('hp-contact')
      ];
    } else {
      $post_data = [
        'hp_type' => $input_post['hp-type'],
        'hp_name' => ucwords(strip_tags($input_post['hp-name'])),
        'hp_address' => strip_tags($input_post['hp-address']),
        'hp_contact' => strip_tags($input_post['hp-contact']),
        'date_updated' => date("Y-m-d")
      ];
      $updated = $this->setup_model->db_update_healthcare_provider($hp_id, $post_data);
      if (!$updated) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Healthcare Provider Updated Failed'
        ];
      }
      $response = [
        'status' => 'success', 
        'message' => 'Healthcare Provider Updated Successfully'
      ];
    }
    echo json_encode($response);
  }


  function delete_healthcare_provider() {
    $token = $this->security->get_csrf_hash();
    $hp_id = $this->uri->segment(5);
    $deleted = $this->setup_model->db_delete_healthcare_provider($hp_id);
    if (!$deleted) {
      $response = [
        'token' => $token, 
        'status' => 'error', 
        'message' => 'Healthcare Provider Delete Failed'
      ];
    }
    $response = [
      'token' => $token, 
      'status' => 'success', 
      'message' => 'Healthcare Provider Deleted Successfully'
    ];
    echo json_encode($response);
  }

  function fetch_all_company_doctors() {
    $resultList = $this->setup_model->db_get_all_company_doctors();
    $result = [];
    foreach ($resultList as $key => $value) {
      $doctor_id = $this->myhash->hasher($value['doctor_id'], 'encrypt');
      $actions = '<a class="me-2" href="Javascript:void(0)" onclick="editCompanyDoctor(\'' . $doctor_id . '\')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

      $actions .= '<a href="Javascript:void(0)" onclick="deleteCompanyDoctor(\'' . $doctor_id . '\')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';
      
      if ($value['doctor_signature'] != '') {
        $view_signature = '<a href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/doctor_signatures/' . $value['doctor_signature'] . '\')"><strong>View</strong></a>';
      } else {
        $view_signature = '<strong class="text-secondary">None</strong>';
      }

      $result['data'][] = [
        $value['doctor_id'],
        $value['doctor_name'],
        $view_signature,
        date("m/d/Y", strtotime($value['date_added'])),
        date("m/d/Y", strtotime($value['date_updated'])),
        $actions
      ];
    }
    echo json_encode($result);
  }

  function register_company_doctor() {
    $this->security->get_csrf_hash();
    $input_post = $this->input->post(NULL, TRUE);
    $this->form_validation->set_rules('doctor-name', 'Doctor\'s Name', 'trim|required|callback_check_doctor_name_exist');
    $this->form_validation->set_rules('doctor-signature', '', 'callback_check_signature');
    $this->form_validation->set_rules('doctor-username', 'Username', 'required|min_length[5]|callback_check_username_exists');
    $this->form_validation->set_rules('doctor-password', 'Password', 'required');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'doctor_name_error' => form_error('doctor-name'),
        'doctor_signature_error' => form_error('doctor-signature'),
        'doctor_username_error' => form_error('doctor-username'),
        'doctor_password_error' => form_error('doctor-password'),
      ];
    } else {
      $config['upload_path'] = './uploads/doctor_signatures/';
      $config['allowed_types'] = 'jpg|jpeg|png';
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload('doctor-signature')) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Image Not Uploaded'
        ];
        echo json_encode($response);
        exit();
      } else {
        $upload_data = $this->upload->data();
        $signature = $upload_data['file_name'];
        $post_data = [
          'doctor_name' => ucwords($input_post['doctor-name']),
          'doctor_signature' => $signature,
          'date_added' => date("Y-m-d"),
          'date_updated' => date("Y-m-d"),
        ];
        $inserted = $this->setup_model->db_insert_company_doctor($post_data);
        if (!$inserted) {
          $response = [
            'status' => 'save-error', 
            'message' => 'Company Doctor Saved Failed'
          ];
        } else {
          $doctor_id = $this->db->insert_id();
          $account_data  = [
            'emp_id' => '',
            'full_name' => ucwords($input_post['doctor-name']),
            'user_role' => 'company-doctor',
            'dsg_hcare_prov' => '',
            'doctor_id' => $doctor_id,
            'username' => $input_post['doctor-username'],
            'password' => $this->_hash_password($input_post['doctor-password']),
            'status' => 'Active',
            'photo' => '',
            'created_on' => date("Y-m-d"),
            'updated_on' => date("Y-m-d"),
            'updated_by' => ''
          ];
          $saved = $this->setup_model->db_insert_company_doctor_user_account($account_data);
          if (!$saved) {
            $response = [
              'status' => 'save-error', 
              'message' => 'Company Doctor Account Save Failed'
            ];
          }
          $response = [
            'status' => 'success', 
            'message' => 'Company Doctor Saved Successfully'
          ];
        }
      }
    }
    echo json_encode($response);
  }

  function check_signature($str) {
    if (isset($_FILES['doctor-signature']['name']) && !empty($_FILES['doctor-signature']['name'])) {
      return true;
    } else {
      $this->form_validation->set_message('check_signature', 'Please Choose Signature Image.');
      return false;
    }
  }

  function update_check_signature($str) {
    if (isset($_FILES['edit-signature']['name']) && !empty($_FILES['edit-signature']['name'])) {
      return true;
    } else {
      $this->form_validation->set_message('update_check_signature', 'Please Choose Signature Image.');
      return false;
    }
  }

  private function _hash_password($password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    return $hashed_password;
  }

  function check_doctor_name_exist($doctor_name) {
    $exists = $this->setup_model->db_check_doctor_name($doctor_name);
    if (!$exists) {
      return true;
    } else {
      $this->form_validation->set_message('check_doctor_name_exist', 'Company Doctor Already Exists!');
      return false;
    }
  }

  function check_username_exists($doctor_username) {
    $exists = $this->setup_model->db_check_username($doctor_username);
    if (!$exists) {
      return true;
    } else {
      $this->form_validation->set_message('check_username_exists', 'Username Already Taken!');
      return false;
    }
  }

  function get_doctor_info() {
    $doctor_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
    $row = $this->setup_model->db_get_doctor_info($doctor_id);
    $response = [
      'status' => 'success',
      'token' => $this->security->get_csrf_hash(),
      'doctor_name' => $row['doctor_name'],
      'signature' => $row['doctor_signature'],
    ];
    echo json_encode($response);
  }

  function update_company_doctor() {
    $token = $this->security->get_csrf_hash();
    $doctor_id = $this->myhash->hasher($this->input->post("doctor-id"), 'decrypt');
    $doctor_name = $this->security->xss_clean($this->input->post('doctor-name'));
    $this->form_validation->set_rules('doctor-name', 'Doctor\'s Name', 'trim|required');
    $this->form_validation->set_rules('edit-signature', '', 'callback_update_check_signature');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'doctor_name_error' => form_error('doctor-name'),
        'edit_signature_error' => form_error('edit-signature'),
      ];
    } else {
      $config['upload_path'] = './uploads/doctor_signatures/';
      $config['allowed_types'] = 'jpg|jpeg|png';
      $config['encrypt_name'] = TRUE;
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload('edit-signature')) {
        $response = array('status' => 'save-error', 'message' => 'Image Not Uploaded');
        echo json_encode($response);
        exit();
      } else {
        $upload_data = $this->upload->data();
        $signature = $upload_data['file_name'];
        $row = $this->setup_model->db_get_doctor_info($doctor_id);
        if ($row['doctor_signature'] !== '') {
          $file_path = './uploads/doctor_signatures/' . $row['doctor_signature'];
          // the unlink function deletes an image from directory
          file_exists($file_path) ? unlink($file_path) : '';
        }

        $post_data = [
          'doctor_name' => $doctor_name,
          'doctor_signature' => $signature,
          'date_updated' => date("Y-m-d"),
        ];
        $updated = $this->setup_model->db_update_company_doctor($doctor_id, $post_data);
        if (!$updated) {
          $response = [
            'token' => $token, 
            'status' => 'error', 
            'message' => 'Company Doctor Update Failed'
          ];
        }
        $this->setup_model->db_update_company_doctor_account_name($doctor_id, $doctor_name);
        $response = [
          'token' => $token, 
          'status' => 'success', 
          'message' => 'Company Doctor Updated Successfully'
        ];
      }
    }
    echo json_encode($response);
  }

  function delete_company_doctor() {
    $token = $this->security->get_csrf_hash();
    $doctor_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
    $row = $this->setup_model->db_get_doctor_info($doctor_id);
    if ($row['doctor_signature'] !== '') {
      $file_path = './uploads/doctor_signatures/' . $row['doctor_signature'];
      // the unlink function deletes an image from directory
      file_exists($file_path) ? unlink($file_path) : '';
    }
    $deleted = $this->setup_model->db_delete_company_doctor($doctor_id);
    if (!$deleted) {
      $response = [
        'token' => $token, 
        'status' => 'error', 
        'message' => 'Company Doctor Delete Failed'
      ];
    }
    $response = [
      'token' => $token, 
      'status' => 'success', 
      'message' => 'Company Doctor Deleted Successfully'
    ];
    echo json_encode($response);
  }

  function fetch_all_cost_types() {
    $resultList = $this->setup_model->db_get_all_cost_types();
    $result = [];
    foreach ($resultList as $key => $value) {
      $actions = '<a class="me-2" href="Javascript:void(0)" onclick="editCostType(' . $value['ctype_id'] . ')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a> ';

      $actions .= '<a href="Javascript:void(0)" onclick="deleteCostType(' . $value['ctype_id'] . ')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

      $result['data'][] = [
        $value['ctype_id'],
        $value['item_description'],
        date("m/d/Y", strtotime($value['date_added'])),
        date("m/d/Y", strtotime($value['date_updated'])),
        $actions
      ];
    }
    echo json_encode($result);
  }

  function register_cost_type() {
    $this->security->get_csrf_hash();
    $cost_type = ucfirst(strip_tags($this->input->post('cost-type')));
    $this->form_validation->set_rules('cost-type', 'Cost Type', 'trim|required|callback_check_cost_type_exist');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'cost_type_error' => form_error('cost-type'),
      ];
    } else {
      $post_data = [
        'cost_type' => $cost_type,
        'date_added' => date("Y-m-d"),
        'date_updated' => date("Y-m-d"),
      ];
      $saved = $this->setup_model->db_insert_cost_type($post_data);
      if (!$saved) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Cost Type Saved Failed'
        ];
      }
      $response = [
        'status' => 'success', 
        'message' => 'Cost Type Saved Successfully'
      ];
    }
    echo json_encode($response);
  }

  function check_cost_type_exist($cost_type) {
    $exists = $this->setup_model->db_check_cost_type($cost_type);
    if (!$exists) {
      return true;
    } else {
      $this->form_validation->set_message('check_cost_type_exist', 'Cost Type Already Exists!');
      return false;
    }
  }

  function get_cost_type_info() {
    $ctype_id = $this->uri->segment(5);
    $row = $this->setup_model->db_get_cost_type_info($ctype_id);
    $response = [
      'status' => 'success',
      'token' => $this->security->get_csrf_hash(),
      'cost_type' => $row['cost_type'],
    ];
    echo json_encode($response);
  }

  function update_cost_type() {
    $token = $this->security->get_csrf_hash();
    $ctype_id = $this->input->post('ctype-id');
    $cost_type = $this->input->post('cost-type');
    $this->form_validation->set_rules('cost-type', 'Cost Type', 'trim|required');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'cost_type_error' => form_error('cost-type'),
      ];
    } else {
      $post_data = [
        'cost_type' => $cost_type,
        'date_updated' => date("Y-m-d"),
      ];
      $updated = $this->setup_model->db_update_cost_type($ctype_id, $post_data);
      if (!$updated) {
        $response = [
          'token' => $token, 
          'status' => 'error', 
          'message' => 'Cost Type Update Failed'
        ];
      }
      $response = [
        'token' => $token, 
        'status' => 'success', 
        'message' => 'Cost Type Updated Successfully'
      ];
    }
    echo json_encode($response);
  }

  function delete_cost_type() {
    $token = $this->security->get_csrf_hash();
    $ctype_id = $this->uri->segment(5);
    $deleted = $this->setup_model->db_delete_cost_type($ctype_id);
    if (!$deleted) {
      $response = [
        'token' => $token, 
        'status' => 'error', 
        'message' => 'Cost Type Delete Failed'
      ];
    }
    $response = [
      'token' => $token, 
      'status' => 'success', 
      'message' => 'Cost Type Deleted Successfully'
    ];
    echo json_encode($response);
  }

  function fetch_room_types() {
    $result = $this->setup_model->get_room_datatables();
    $data = [];

    foreach($result as $room){
      $row = [];
      if($room['date_added'] == "" ){
        $date_added = '03/08/2023';
      }else{
        $date_added = date('m/d/Y', strtotime($room['date_added']));
      }

      $row[] = $room['room_type'];
      $row[] = $room['room_typ_hmo_req'];
      $row[] = $room['room_number'];
      $row[] = number_format($room['room_rate']);
      $row[] = $date_added;
      $data[] =$row;
      
      $response = [
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->setup_model->count_all_room(),
        "recordsFiltered" => $this->setup_model->count_room_filtered(),
        "data" => $data,
      ];
    }
    echo json_encode($response);
  }

  function register_room_type() {
    $this->security->get_csrf_hash();
    $room_group = ucwords(strip_tags($this->input->post('room-group')));
    $room_type = ucwords(strip_tags($this->input->post('room-type')));
    $room_hmo_req = strip_tags($this->input->post('room-hmo-req'));
    $room_number = strip_tags($this->input->post('room-num'));
    $room_rate = strip_tags($this->input->post('room-rate'));
    
    $this->form_validation->set_rules('room-type', 'Room Type', 'trim|required');    
    $this->form_validation->set_rules('room-num', 'Room Number', 'trim|required');
    $this->form_validation->set_rules('room-rate', 'Room Rate', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'room_type_error' => form_error('room-type'),
        'room_num_error' => form_error('room-num'),
        'room_rate_error' => form_error('room-rate'),
      ];
    } else {
      $post_data = [
        'room_group' => $room_group,
        'room_type' => $room_type,
        'room_typ_hmo_req' => $room_hmo_req,
        'room_number' => $room_number,
        'room_rate' => $room_rate,
        'date_added' => date("Y-m-d"),
      ];
      $saved = $this->setup_model->db_insert_room_type($post_data);
      if (!$saved) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Cost Type Saved Failed'
        ];
      }
      $response = [
        'status' => 'success', 
        'message' => 'Cost Type Saved Successfully'
      ];
    }
    echo json_encode($response);
  }



}
