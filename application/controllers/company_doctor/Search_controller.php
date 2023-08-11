<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_controller extends CI_Controller {

    function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'company-doctor') {
			redirect(base_url());
		}
	}

    // function permission() {
    //     $user_role = $this->session->userdata('user_role');
    //     $logged_in = $this->session->userdata('logged_in');
    //     if(isset($logged_in) && !empty($logged_in) && $user_role === 'company-doctor') {
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    function search_autocomplete(){
        $this->security->get_csrf_hash();
        $this->load->model('super_admin/search_model');
        $search_data = $this->input->post('search');
        $result = $this->search_model->get_autocomplete($search_data);
        if (!empty($result))
        {
            foreach ($result as $row):
                $employee_id = $row['emp_id'];
                $exploded = preg_split('/-(?=[0-9])/', $employee_id, 2);
                echo '<strong class="d-block mx-2 p-1 my-1"><a href="#" onclick="getMemberValues('.$exploded[0].', '.$exploded[1].')" class="text-secondary" data-toggle="tooltip" data-placement="top" title="Click to fill form with Data">'
                    .$row['first_name'].' '
                    .$row['middle_name'].' '
                    .$row['last_name'].' '
                    .$row['suffix'].'</a></strong>';
            endforeach;
        }
        else
        {
            echo "<p class='text-center mt-1'><em>No data found...</em></p>";
        }
    }

    function get_searched_member_details(){
        $emp_id = $this->uri->segment(5);
        $this->load->model('super_admin/search_model');
        $row = $this->search_model->db_get_member_details($emp_id);
        $response = [
            'status' => 'success', 
            'token' => $this->security->get_csrf_hash(),
            'member_id' => $row['member_id'],
            'emp_id' => $row['emp_id'], 
            'first_name' => $row['first_name'], 
            'middle_name' => $row['middle_name'], 
            'last_name' => $row['last_name'], 
            'suffix' => $row['suffix'],
            'mobile_number' => $row['contact_no'], 
            'email' => $row['email'],
            'date_regularized' => $row['date_regularized']
        ];
        echo json_encode($response);
    }


}
