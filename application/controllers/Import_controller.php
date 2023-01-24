<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import_controller extends CI_Controller {

  var $fields;
  /** columns names retrieved after parsing */
  var $separator = ';';
  /** separator used to explode each line */
  var $enclosure = '"';
  /** enclosure used to decorate each field */
  var $max_row_size = 120400;
  /** maximum row size to be used for decoding */

  function __construct() {
    parent::__construct();
    $this->load->model('import_model');
  }

  public function index() {
    $data = array();

    // Get messages from the session
    if ($this->session->userdata('success_msg')) {
      $data['success_msg'] = $this->session->userdata('success_msg');
      $this->session->unset_userdata('success_msg');
    }
    if ($this->session->userdata('error_msg')) {
      $data['error_msg'] = $this->session->userdata('error_msg');
      $this->session->unset_userdata('error_msg');
    }

    // Get rows
    // $data['members'] = $this->import_model->getRows();
    $this->load->view('pages/import', $data);
  }

  public function import_csv_to_database() {
    $token = $this->security->get_csrf_hash();
    $data = $this->input->post('json');
    $members = json_decode($data, TRUE);
    $def_company = 'Alturas Supermarket Corporation';
    $insertedCount = $updatedCount = $rowCount = $notAddCount = 0;
    if ($members) {
      foreach ($members as $row) {
        $rowCount++;
        // ?: '' -> ternary operator shorthand: returns current value if exist else return empty string
        $date_of_birth = $row['Date of Birth'] ?: '';
        $date_regularized = $row['Date Regularized'] ?: '';
        $post_data = array(
          'emp_id' => $row['Emp ID'] ?: '',
          'first_name' => $row['Firstname'] ?: '',
          'middle_name' => $row['Middlename'] ?: '',
          'last_name' => $row['Lastname'] ?: '',
          'suffix' => $row['Suffix'] ?: '',
          'gender' => $row['Gender'] ?: '',
          'civil_status' => $row['Civil Status'] ?: '',
          'spouse' => $row['Spouse'] ?: '',
          'date_of_birth' => date("Y-m-d", strtotime($date_of_birth)),
          'home_address' => $row['Home Address'] ?: '',
          'city_address' => $row['City Address'] ?: '',
          'contact_no' => '0' . $row['Contact No'] ?: '',
          'email' => $row['Email'] ?: '',
          'position' => $row['Position'] ?: '',
          'position_level' => $row['Position Level'] ?: '',
          'emp_type' => $row['Emp Type'] ?: '',
          'current_status' => $row['Current Status'] ?: '',
          'business_unit' => $row['Business Unit'] ?: '',
          'dept_name' => $row['Department Name'] ?: '',
          'blood_type' => $row['Blood Type'] ?: '',
          'height' => $row['Height'] ?: '',
          'weight' => $row['Weight'] ?: '',
          'allergies' => $row['Allergies'] ?: '',
          'philhealth_no' => $row['Philhealth No'] ?: '',
          'contact_person' =>  $row['Contact Person'] ?: '',
          'contact_person_addr' => $row['Contact Person Address'] ?: '',
          'contact_person_no' => '0' . $row['Contact Person No'] ?: '',
          'date_regularized' => date("Y-m-d", strtotime($date_regularized)),
          'company' => $def_company
        );

        // Check whether emp_id already exists in the database
        $con = array(
          'where' => array(
            'emp_id' => $row['Emp ID']
          ),
          'returnType' => 'count'
        );
        $prevCount = $this->import_model->get_rows($con);

        if ($prevCount > 0) {
          // Update member's data
          $condition = array('emp_id' => $row['Emp ID']);
          $update = $this->import_model->update($post_data, $condition);
          if ($update) {
            $updatedCount++;
          }
        } else if ($prevCount <= 0 && $row['Emp ID'] != '' && $row['Emp ID'] != 0) {
          // Insert member's data
          $insert = $this->import_model->insert($post_data);
          if ($insert) {
            $insertedCount++;
          }
        }
      }
      $notAddCalc = ($rowCount - ($insertedCount + $updatedCount));
      $notAddedCount = $notAddCalc > 0 ? $notAddCount : 0;
      $successMsg = 'Members Imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertedCount . ') | Updated (' . $updatedCount . ') | Not Inserted (' . $notAddedCount . ')';
      $response = array('token' => $token, 'status' => 'success', 'message' => $successMsg);
    } else {
      $response = array('token' => $token, 'status' => 'error', 'message' => 'The uploaded CSV File is empty');
    }

    echo json_encode($response);
  }


  /*
  * Callback function to check file value and type during validation
  */
  public function file_check($str) {
    $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
      $mime = get_mime_by_extension($_FILES['file']['name']);
      $fileAr = explode('.', $_FILES['file']['name']);
      $ext = end($fileAr);
      if (($ext == 'csv') && in_array($mime, $allowed_mime_types)) {
        return true;
      } else {
        $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
        return false;
      }
    } else {
      $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
      return false;
    }
  }


  public function csv_format_download() {
    $this->load->helper('download');
    $filePath = base_url() . 'assets/csvFormat/applicants_format.csv';
    redirect($filePath);
  }

  public function import_txt_file_page() {
    $data = array();
    // Get messages from the session
    if ($this->session->userdata('success_msg')) {
      $data['success_msg'] = $this->session->userdata('success_msg');
      $this->session->unset_userdata('success_msg');
    }
    if ($this->session->userdata('error_msg')) {
      $data['error_msg'] = $this->session->userdata('error_msg');
      $this->session->unset_userdata('error_msg');
    }

    $this->load->view('pages/import_txt_file', $data);
  }

  public function db_upload_txt_file() {
    $token = $this->security->get_csrf_hash();
    $config['upload_path'] = './uploads/text_files/';
    $config['allowed_types'] = '*';
    $config['encrypt_name'] = TRUE;
    $this->load->library('upload', $config);
    if (!$this->upload->do_upload('txtFile')) {
      $response = array('status' => 'save-error', 'message' => 'Text File Not Uploaded');
      echo json_encode($response);
    } else {
      $data = array('upload_data' => $this->upload->data());
      $file_name = $data['upload_data']['file_name'];
      $file_path = './uploads/text_files/' .  $file_name;
      $file = fopen($file_path, 'r');

      while (!feof($file)) {
        $getTextLine = fgets($file);
        $explodeLine = explode(",", $getTextLine);

        list($name, $city, $post_code, $job_title) = $explodeLine;

        $post_data = array(
          'name' => $name,
          'city' => $city,
          'post_code' => $post_code,
          'job_title' => $job_title
        );
        $exists = $this->import_model->check_name_exist($name);
        if ($exists) {
          $this->import_model->update_val_from_txt($post_data, $name);
        } else {
          $this->import_model->insert_val_from_txt($post_data);
        }
      }
      fclose($file);
      $response = array('status' => 'success', 'message' => 'Successfully Imported Text File Data');
      echo json_encode($response);
    }
  }
}
