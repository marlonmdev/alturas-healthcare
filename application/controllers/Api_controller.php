<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding"); 

class Api_controller extends CI_Controller {

    function __construct() {
      parent::__construct();
      $this->load->model('Api_model');
    }

    function get_hmo_CA_data()
    {
      $data = $this->Api_model->get_hmo_CA();
      echo json_encode($data);
    }

    function update_incorp_apprv()
    {
      $data['emp_id']               = $_POST['emp_id'];
      $data['approved_amount']      = $_POST['approved_amount'];
      $data['excess_amount']        = $_POST['excess_amount'];
      $data['billing_id']           = $_POST['billing_id']; 
      $this->Api_model->update_apprv($data);
   
      $data['html'] ='success';
      echo json_encode($data);
    }
}    