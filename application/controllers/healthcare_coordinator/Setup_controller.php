<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_controller extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('healthcare_coordinator/setup_model');
    $user_role = $this->session->userdata('user_role');
    $logged_in = $this->session->userdata('logged_in');
    if ($logged_in !== true && $user_role !== 'healthcare-coordinator') {
      redirect(base_url());
    }
  }

  function fetch_all_healthcare_providers() {
    $resultList = $this->setup_model->db_get_all_healthcare_providers();
    $result = [];
    foreach ($resultList as $key => $value) {
      $actions = '<a class="me-2" href="Javascript:void(0)" onclick="viewHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="View Details"><i class="mdi mdi-information fs-2 text-info"></i></a>';

      $actions .= '<a class="me-2" href="Javascript:void(0)" onclick="editHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a>';

      $actions .= '<a class="me-2" href="Javascript:void(0)" onclick="deleteHealthCareProvider(' . $value['hp_id'] . ')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

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

  function check_doctor_name_exist($doctor_name) {
    $exists = $this->setup_model->db_check_doctor_name($doctor_name);
    if (!$exists) {
      return true;
    } else {
      $this->form_validation->set_message('check_doctor_name_exist', 'Company Doctor Already Exists!');
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
        $response = [
          'status' => 'save-error', 
          'message' => 'Image Not Uploaded'
        ];
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
    $resultList = $this->setup_model->get_datatables();
    $data = [];
    foreach ($resultList as $value) {
      $row = [];
      $ctype_id = $this->myhash->hasher($value['ctype_id'], 'encrypt');
      ;
      $actions = '<a href="Javascript:void(0)" onclick="editCostType(' . $value['ctype_id'] . ')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a> ';

      // $actions = '<a class="me-2" href="Javascript:void(0)" onclick="editCostType(\'' . $ctype_id . '\')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a> ';


      $actions .= '<a href="Javascript:void(0)" onclick="deleteCostType(' . $value['ctype_id'] . ')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

      $added_on = $value['date_added'] == '' ? '03/08/2023' :  date("m/d/Y", strtotime($value['date_added']));
  
      $row[] = $value['item_id'];
      $row[] = $value['item_description'];
      $row[] = number_format($value['op_price']);
      $row[] = number_format($value['ip_price']);
      $row[] =  $value['date_added'] ? date("m/d/Y", strtotime($value['date_added'])) : 'No Data Available';
      $row[] = $actions;
      $data[] = $row;
    }
    $output = [
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->setup_model->count_all(),
      "recordsFiltered" => $this->setup_model->count_filtered(),
      "data" => $data,
    ];
    echo json_encode($output);
    // var_dump($ctype_id);
  }

  // function get_cost_type_info() {
  //   $room_id = $this->myhash->hasher( $this->uri->segment(5), 'decrypt');

  //   $row = $this->setup_model->db_get_room_type_info($room_id);

  //   $response = [
  //     'status'      => 'success',
  //     'token'       => $this->security->get_csrf_hash(),
  //     'hp_id'       => $row['hp_id'],
  //     'room_type'   => $row['room_type'],
  //     'rt_hmo_req'  => $row['room_typ_hmo_req'],
  //     'room_number' => $row['room_number'],
  //     'room_rate'   => $row['room_rate'],
  //   ];

  //   echo json_encode($response);
  // }

  function register_cost_type() {
    $this->security->get_csrf_hash();
    $hospital_id = $this->input->post('hospital-filter-add');
    $price_list = $this->input->post('price-filter-add');
    if($price_list == 'other'){
      $price_category = strtoupper(strip_tags($this->input->post('other-price-filter')));
    }else{
      $price_category = strtoupper($this->input->post('price-filter-add'));
    }
    $item_id = (strip_tags($this->input->post('item-id')));
    $cost_type = strtoupper(strip_tags($this->input->post('cost-type')));
    $op_price = (strip_tags($this->input->post('op-price')));
    $ip_price = (strip_tags($this->input->post('ip-price')));
    
    $this->form_validation->set_rules('hospital-filter-add', 'Hospital', 'required');
    $this->form_validation->set_rules('price-filter-add', 'Price List Category', 'required');
    $this->form_validation->set_rules('cost-type', 'Item Description', 'trim|required|callback_check_cost_type_exist');    
    $this->form_validation->set_rules('op-price', 'Outpatient Price', 'trim|required');
    $this->form_validation->set_rules('ip-price', 'Inpatient Price', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'hp_id_error' => form_error('hospital-filter-add'),
        'price_list_error' => form_error('price-filter-add'),
        'cost_type_error' => form_error('cost-type'),
        'op_price_error' => form_error('op-price'),
        'ip_price_error' => form_error('ip-price'),
      ];
    } else {
      $post_data = [
        'hp_id'            => $hospital_id,
        'price_list_group' => $price_category,
        'item_id'          => $item_id,
        'item_description' => $cost_type,
        'op_price'         => $op_price,
        'ip_price'         => $ip_price,
        'date_added'       => date("Y-m-d"),
        'date_updated'     => '',
        'added_by'         => $this->session->userdata('fullname')
      ];
      $saved = $this->setup_model->db_insert_cost_type($post_data);
      if (!$saved) {
        $response = [
          'status' => 'save-error', 
          'message' => 'Cost Type Saved Failed'
        ];
      }else{
        $response = [
          'status' => 'success', 
          'message' => 'Cost Type Saved Successfully'
        ];
      }
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
      'price_group' =>$row['price_list_group'],
      'item_id' => $row['item_id'],
      'item_description' => $row['item_description'],
      'op_price' => $row['op_price'],
      'ip_price' => $row['ip_price']
    ];
    echo json_encode($response);
  }

  function update_cost_type() {
    $token = $this->security->get_csrf_hash();
    $ctype_id = $this->input->post('ctype-id');
    $item_id = $this->input->post('item_id');
    $item_description = $this->input->post('item_description');
    $old_outpatient_price = $this->input->post('old_outpatient_price');
    $old_inpatient_price = $this->input->post('old_inpatient_price');
    $new_outpatient_price = $this->input->post('new_outpatient_price');
    $new_inpatient_price = $this->input->post('new_inpatient_price');

    $this->form_validation->set_rules('new_outpatient_price', 'Out Patient Price', 'trim|required');
    $this->form_validation->set_rules('new_inpatient_price', 'In Patient Price', 'trim|required');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'new_outpatient_price_error' => form_error('new_outpatient_price'),
        'new_inpatient_price_error' => form_error('new_inpatient_price'),
      ];
    } else {
      $post_data = [
        'item_id' => $item_id,
        'item_description' => $item_description,
        'op_price' => $new_outpatient_price,
        'ip_price' => $new_inpatient_price,
        'old_op_price' => $old_outpatient_price,
        'old_ip_price' => $old_inpatient_price,
        'date_updated' => date("Y-m-d"),
      ];
      $updated = $this->setup_model->db_update_cost_type($ctype_id, $post_data);
      if (!$updated) {
        $response = [
          'token' => $token, 
          'status' => 'error', 
          'message' => 'Update Failed'
        ];
      }
      $response = [
        'token' => $token, 
        'status' => 'success', 
        'message' => 'Updated Successfully'
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
    }else{
      $response = [
        'token' => $token, 
        'status' => 'success', 
        'message' => 'Cost Type Deleted Successfully'
      ];
    }
    echo json_encode($response);
  }

  function fetch_room_types() {
    $result = $this->setup_model->get_room_datatables();
    $data = [];
    $response = [];

    foreach($result as $room){
      $room_id = $this->myhash->hasher($room['room_id'], 'encrypt');

      $actions = '<a class="me-2" href="Javascript:void(0)" onclick="editRoomType(\'' . $room_id . '\')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="mdi mdi-pencil-circle fs-2 text-success"></i></a> ';

      $actions .= '<a href="Javascript:void(0)" onclick="deleteRoomType(\'' . $room_id . '\')" data-toggle="tooltip" data-placement="top" title="Delete"><i class="mdi mdi-delete-circle fs-2 text-danger"></i></a>';

      $row = [];
      $row[] = $room['room_id'];
      $row[] = $room['hp_name'];
      $row[] = $room['room_type'];
      $row[] = $room['room_number'];
      $row[] = number_format($room['room_rate']);
      $row[] = date("m/d/Y", strtotime($room['date_added']));
      $row[] = $actions;
      $data[] =$row;
    }
    $response = [
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->setup_model->count_all_room(),
      "recordsFiltered" => $this->setup_model->count_room_filtered(),
      "data" => $data,
    ];
    echo json_encode($response);
  }

  function register_room_type() {
    $this->security->get_csrf_hash();
    $hospital_id = $this->input->post('hospital-filter');
    $room_type = ucwords(strip_tags($this->input->post('room-type')));
    $room_hmo_req = ucwords(strip_tags($this->input->post('room-hmo-req')));
    $room_number = ucwords(strip_tags($this->input->post('room-num')));
    $room_rate = strip_tags($this->input->post('room-rate'));
    
    $this->form_validation->set_rules('hospital-filter', 'Hospital', 'trim|required');
    $this->form_validation->set_rules('room-type', 'Room Type', 'trim|required');    
    $this->form_validation->set_rules('room-num', 'Room Number', 'trim|required');
    $this->form_validation->set_rules('room-rate', 'Room Rate', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 'error',
        'hospital_error' => form_error('hospital-filter'),
        'room_type_error' => form_error('room-type'),
        'room_num_error' => form_error('room-num'),
        'room_rate_error' => form_error('room-rate'),
      ];
    } else {
      $post_data = [
        'hp_id' => $hospital_id,
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
          'message' => 'Room Type Saved Failed'
        ];
      }else{
        $response = [
          'status' => 'success', 
          'message' => 'Room Type Saved Successfully'
        ];
      }
    }
    echo json_encode($response);
  }

  function get_room_type_info() {
    $room_id = $this->myhash->hasher( $this->uri->segment(5), 'decrypt');

    $row = $this->setup_model->db_get_room_type_info($room_id);

    $response = [
      'status'      => 'success',
      'token'       => $this->security->get_csrf_hash(),
      'hp_id'       => $row['hp_id'],
      'room_type'   => $row['room_type'],
      'rt_hmo_req'  => $row['room_typ_hmo_req'],
      'room_number' => $row['room_number'],
      'room_rate'   => $row['room_rate'],
    ];

    echo json_encode($response);
  }

  function update_room_type() {
    $token = $this->security->get_csrf_hash();
    $room_id = $this->myhash->hasher($this->input->post('room-id'), 'decrypt');
    $input_post = $this->input->post(NULL, TRUE);

    $this->form_validation->set_rules('hospital-filter', 'Healthcare Provider', 'trim|required');
    $this->form_validation->set_rules('room-type', 'Room Type', 'trim|required');
    $this->form_validation->set_rules('room-num', 'Room Number/s', 'trim|required');
    $this->form_validation->set_rules('room-rate', 'Room Rate', 'trim|required');
    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status'               => 'error',
        'hcare_provider_error' => form_error('hospital-filter'),
        'room_type_error'      => form_error('room-type'),
        'room_num_error'       => form_error('room-num'),
        'room_rate_error'      => form_error('room-rate'),
      ];
    } else {
      $post_data = [
        'hp_id'            => $input_post['hospital-filter'],
        'room_type'        => $input_post['room-type'],
        'room_typ_hmo_req' => $input_post['room-hmo-req'],
        'room_number'      => $input_post['room-num'],
        'room_rate'        => $input_post['room-rate'],
        'date_updated'     => date("Y-m-d"),
      ];

      $updated = $this->setup_model->db_update_room_type($room_id, $post_data);
      if (!$updated) {
        $response = [
          'token' => $token, 
          'status' => 'save-error', 
          'message' => 'Room Update Failed'
        ];
      }
      $response = [
        'token' => $token, 
        'status' => 'success', 
        'message' => 'Room Updated Successfully'
      ];
    }
    echo json_encode($response);
  }

  function delete_room_type() {
    $token = $this->security->get_csrf_hash();
    $room_id = $this->myhash->hasher( $this->uri->segment(5), 'decrypt');
    $deleted = $this->setup_model->db_delete_room_type($room_id);
    if (!$deleted) {
      $response = [
        'token' => $token, 
        'status' => 'error', 
        'message' => 'Room Delete Failed'
      ];
    }
    $response = [
      'token' => $token, 
      'status' => 'success', 
      'message' => 'Room Deleted Successfully'
    ];
    echo json_encode($response);
  }

}
