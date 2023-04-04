<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/billing_model');
        $this->load->model('healthcare_provider/loa_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
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

    function billing_search_member() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/search_member');
        $this->load->view('templates/footer');
    }

    function search_member_by_name() {
        $this->security->get_csrf_hash();
        $first_name = $this->security->xss_clean($this->input->post('first_name'));
        $last_name = $this->security->xss_clean($this->input->post('last_name'));
        $date_of_birth = $this->security->xss_clean($this->input->post('date_of_birth'));
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $member = $this->billing_model->get_member_by_name($first_name, $last_name, $date_of_birth);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->billing_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->billing_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->billing_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->billing_model->get_member_noa($member['emp_id'], $hcare_provider_id);

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
            $this->load->view('healthcare_provider_panel/billing/search_member_result');
            $this->load->view('templates/footer');
        }
    }

    function search_member_by_healthcard() {
        $this->security->get_csrf_hash();
        $healthcard_no = $this->security->xss_clean($this->input->post('healthcard_no'));
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $member = $this->billing_model->get_member_by_healthcard($healthcard_no);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->billing_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->billing_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->billing_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->billing_model->get_member_noa($member['emp_id'], $hcare_provider_id);

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
            $this->load->view('healthcare_provider_panel/billing/search_member_result');
            $this->load->view('templates/footer');
        }
    }

    function bill_patient_loa() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $loa_id = $this->myhash->hasher($url_id, 'decrypt');
        // $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $loa = $this->billing_model->get_loa_to_bill($loa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_cost_types_by_hp($loa['hcare_provider']);
        $data['loa'] = $loa;
        $data['request_type'] = $loa["loa_request_type"];
        $data['work_related'] = $loa["work_related"];
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['loa_id'] = $url_id;
        $data['loa_no'] = $loa['loa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];

        $view_page = '';
        if($loa["loa_request_type"] == 'Diagnostic Test'){
            $view_page = 'bill_patient_diagnostic_test_loa';
        }elseif($loa["loa_request_type"] == 'Consultation'){
            $view_page = 'bill_patient_consultation_loa';
        }

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/'.$view_page);
        $this->load->view('templates/footer');
    }

    function fetch_loa_to_bill() {
        $this->security->get_csrf_hash();
        $loa_id = $this->myhash->hasher($this->input->post('loa_id'), 'decrypt');
        $loa = $this->billing_model->get_loa_to_bill($loa_id);

       /* Exploding the string and then pushing it to an array. */
        $med_services = [];
        $exploded_med_services = explode(";", $loa['med_services']);

        foreach ($exploded_med_services as $ctype_id) :
            $cost_type = $this->billing_model->get_cost_type_by_id($ctype_id);
            array_push($med_services, $cost_type['cost_type']);
        endforeach;

        $data = [
            'token'             => $this->security->get_csrf_hash(),
            'user_role'         => $this->session->userdata('user_role'),
            'member_mbl'        => $this->session->userdata('b_member_mbl'),
            'remaining_balance' => $this->session->userdata('b_member_bal'),
            'loa_services'      => $med_services,
            'request_type'      => $loa["loa_request_type"],
        ];

        echo json_encode($data);
    }

    
    function diagnostic_loa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $loa_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        // request type for reference 
        $type = 'LOA';
        // call insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $loa_id);

        if($inserted){
            // if patients billing info is saved to DB call insert_billing_services() function
            $this->insert_billing_services($posted_data['ct-name'], $posted_data['ct-qty'], $posted_data['ct-fee'], $posted_data['billing-no']);

            // if there are added medications
            if($posted_data['medication-count'] > 0){
                $this->insert_medications($posted_data['medication-name'], $posted_data['medication-qty'], $posted_data['medication-fee'], $posted_data['billing-no']);
            }

            // if there are added professional fees
            if($posted_data['profee-count'] > 0){
                $this->insert_professional_fees($posted_data['prodoc-name'], $posted_data['profee-amount'], $posted_data['billing-no']);
            }

            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }

            // if SSS deduction has value
            if($posted_data['sss-deduction'] > 0){
                $this->insert_sss_deduction($posted_data['sss-deduction'], $posted_data['billing-no']);
            }
            // if the dynamic deductions exists 
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            if($posted_data['personal-charge'] > 0){
                $this->insert_personal_charge($type, $posted_data['emp-id'], $loa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            }
            // call to function that updates member's credit limit
            $this->update_member_mbl($posted_data);       
            // call to function that updates loa request status
            $this->update_request_status($type, $loa_id);
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();
            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id,
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed',
            ];
        }

        echo json_encode($response);
    }

    private function _unset_session_data(){
        $temp_data = [
            'b_member_info',
            'b_member_mbl',
            'b_member_bal',
            'b_hcare_provider',
            'b_healthcard_no'
        ];
		$this->session->unset_userdata($temp_data);
    }

    function consultation_loa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $loa_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        $type = 'LOA';
        // call to insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $loa_id);

        if($inserted){
            // insert consultation service
            $services = [
                'service_name'     => $posted_data['consultation'],
                'service_quantity' => $posted_data['consult-quantity'],
                'service_fee'      => $posted_data['consult-fee'],
                'billing_no'       => $posted_data['billing-no'],
                'added_on'         => date('Y-m-d')
            ];

            $this->billing_model->insert_consultation_billing_services($services);
            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }
            // if SSS deduction has value
            if($posted_data['sss-deduction'] > 0){
                $this->insert_sss_deduction($posted_data['sss-deduction'], $posted_data['billing-no']);
            }
            // if the dynamic deductions exists
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            if($posted_data['personal-charge'] > 0){
                $this->insert_personal_charge($type, $posted_data['emp-id'], $loa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            }
            // call to function that updates member's credit limit
            $this->update_member_mbl($posted_data);     
            // call to function that updates loa request status
            $this->update_request_status($type, $loa_id);  
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();

            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed'
            ];
        }

        echo json_encode($response);
    }

    function loa_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

    function bill_patient_noa() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $noa_id = $this->myhash->hasher($url_id, 'decrypt');
        // $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $noa = $this->billing_model->get_noa_to_bill($noa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_hospital_cost_types($noa['hospital_id']);
        $data['noa'] = $noa;
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['work_related'] = $noa["work_related"];
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['noa_id'] = $url_id;
        $data['noa_no'] = $noa['noa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];
        $data['rooms'] = $this->billing_model->get_hospital_room_types($noa['hospital_id']);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/bill_patient_noa');
        $this->load->view('templates/footer');
    }

    function noa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        // request type for reference 
        $type = 'NOA';
        // call insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $noa_id);

        if($inserted){
            // if patients billing info is saved to DB call insert_billing_services() function
            $this->insert_billing_services($posted_data['ct-names'], $posted_data['ct-qtys'], $posted_data['ct-fees'], $posted_data['billing-no']);
            // if there are added medications
            if($posted_data['medication-count'] > 0){
                $this->insert_medications($posted_data['medication-name'], $posted_data['medication-qty'], $posted_data['medication-fee'], $posted_data['billing-no']);
            }
            // if there are added professional fees
            if($posted_data['profee-count'] > 0){
                $this->insert_professional_fees($posted_data['prodoc-name'], $posted_data['profee-amount'], $posted_data['billing-no']);
            }
            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }
            // if SSS deduction has value
            if($posted_data['sss-deduction'] > 0){
                $this->insert_sss_deduction($posted_data['sss-deduction'], $posted_data['billing-no']);
            }
            // if the dynamic deductions exists 
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            if($posted_data['personal-charge'] > 0){
                $this->insert_personal_charge($type, $posted_data['emp-id'], $noa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            }
            // call to function that updates member's credit limit
            $this->update_member_mbl($posted_data);       
            // call to function that updates noa request status
            $this->update_request_status($type, $noa_id);
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();
            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed'
            ];
        }
        echo json_encode($response);
    }

    function noa_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

    function insert_patient_billing($type, $posted_data, $id){
        $work_related = $posted_data['work-related'];
        $net_bill = $posted_data['net-bill'];
        $remaining_bal = $posted_data['remaining-balance'];

        if($work_related == 'Yes' && $net_bill > $remaining_bal){
            $after_billing_balance = 0;
        }else{
            $after_billing_balance = $remaining_bal - $net_bill;
        }

        if($type === 'LOA'){
            $data = [
                'billing_no'            => $posted_data['billing-no'],
                'billing_type'          => 'Manual Billing',
                'emp_id'                => $posted_data['emp-id'],
                'loa_id'                => $id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $posted_data['work-related'],
                'total_services'        => $posted_data['total-services'],
                'total_medications'     => $posted_data['total-medications'],
                'total_pro_fees'        => $posted_data['total-profees'],
                'total_room_board'      => $posted_data['total-roomboard'],
                'total_bill'            => $posted_data['total-bill'],
                'total_deduction'       => $posted_data['total-deduction'],
                'net_bill'              => $posted_data['net-bill'],
                'company_charge'        => $posted_data['company-charge'],
                'personal_charge'       => $posted_data['personal-charge'],
                'before_remaining_bal'  => $posted_data['remaining-balance'],
                'after_remaining_bal'   => $after_billing_balance,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed'
            ];      
        }else if($type === 'NOA'){
            $data = [
                'billing_no'            => $posted_data['billing-no'],
                'billing_type'          => 'Manual Billing',
                'emp_id'                => $posted_data['emp-id'],
                'noa_id'                => $id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $posted_data['work-related'],
                'total_services'        => $posted_data['total-services'],
                'total_medications'     => $posted_data['total-medications'],
                'total_pro_fees'        => $posted_data['total-profees'],
                'total_room_board'      => $posted_data['total-roomboard'],
                'total_bill'            => $posted_data['total-bill'],
                'total_deduction'       => $posted_data['total-deduction'],
                'net_bill'              => $posted_data['net-bill'],
                'company_charge'        => $posted_data['company-charge'],
                'personal_charge'       => $posted_data['personal-charge'],
                'before_remaining_bal'  => $posted_data['remaining-balance'],
                'after_remaining_bal'   => $after_billing_balance,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed'
            ];      
        }
        // return value is either TRUE or FAlSE
        return $this->billing_model->insert_billing($data);
    }

    function insert_billing_services($ct_names, $ct_quantities, $ct_fees, $billing_no){
        $services = [];
        for ($x = 0; $x < count($ct_names); $x++) {
            $services[] = [
                'service_name'     => $ct_names[$x],
                'service_quantity' => $ct_quantities[$x],
                'service_fee'      => $ct_fees[$x],
                'billing_no'       => $billing_no,
                'added_on'         => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_diagnostic_test_billing_services($services);
    }

    function insert_medications($medication_names, $medication_qtys, $medication_fees, $billing_no){
        $medications = []; 
        for ($i = 0; $i < count($medication_names); $i++) {
            $medications[] = [
                'med_name'     => $medication_names[$i],
                'med_qty'      => $medication_qtys[$i],
                'med_fee'   => $medication_fees[$i],
                'billing_no'   => $billing_no,
                'added_on'     => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_medications($medications);
    }

    function insert_professional_fees($doctor_names, $professional_fees, $billing_no){
        $prof_fees = []; 
        for ($i = 0; $i < count($doctor_names); $i++) {
            $prof_fees[] = [
                'doctor_name'   => $doctor_names[$i],
                'pro_fee'      => $professional_fees[$i],
                'billing_no'    => $billing_no,
                'added_on'      => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_professional_fees($prof_fees);
    }

    function insert_philhealth_deduction($philhealth_deduction, $billing_no){
        $philhealth = [];
        $philhealth[] = [
            'deduction_name'   => 'Philhealth',
            'deduction_amount' => $philhealth_deduction,
            'billing_no'       => $billing_no,
            'added_on'         => date('Y-m-d')
        ];

        $this->billing_model->insert_billing_deductions($philhealth);
    }

    function insert_sss_deduction($sss_deduction, $billing_no){
        $sss = [];
        $sss[] = [
            'deduction_name'   => 'SSS',
            'deduction_amount' => $sss_deduction,
            'billing_no'       => $billing_no,
            'added_on'         => date('Y-m-d')
        ];

        $this->billing_model->insert_billing_deductions($sss);
    }

    function insert_other_deductions($deduction_names, $deduction_amounts, $billing_no){
        $deductions = []; 
        for ($y = 0; $y < count($deduction_names); $y++) {
            $deductions[] = [
                'deduction_name'   => $deduction_names[$y],
                'deduction_amount' => $deduction_amounts[$y],
                'billing_no'       => $billing_no,
                'added_on'         => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_deductions($deductions);
    }

    function insert_personal_charge($type, $emp_id, $id, $personal_charge, $billing_no){
        if($type === 'LOA'){
                $charge = [
                'emp_id'            => $emp_id,
                'loa_id'            => $id,
                'amount'            => $personal_charge,
                'billing_no'        => $billing_no,
                'status'            => 'Unpaid',
                'added_on'          => date('Y-m-d')
            ];
        }else if($type === 'NOA'){
            $charge = [
                'emp_id'            => $emp_id,
                'noa_id'            => $id,
                'amount'            => $personal_charge,
                'billing_no'        => $billing_no,
                'status'            => 'Unpaid',
                'added_on'          => date('Y-m-d')
            ];
        }
        $this->billing_model->insert_personal_charge($charge);
    }

    function update_member_mbl($posted_data){
        $emp_id = $posted_data['emp-id'];
        $remaining_bal = $posted_data['remaining-balance'];
        $net_bill = $posted_data['net-bill'];
        $member_mbl = $this->billing_model->get_member_mbl($posted_data['emp-id']);
        $remaining_bal = $member_mbl['remaining_balance'];
        $current_used_mbl = $member_mbl['used_mbl'] != '' ? $member_mbl['used_mbl'] : 0; 
        
        // calculate members used mbl
        $total_used_mbl = $current_used_mbl + $net_bill;

        // Update Member's Remaining Credit Limit Balance
        if($net_bill > 0 && $net_bill < $remaining_bal){
            // set used mbl value for update
            $used_mbl = $total_used_mbl >= $member_mbl['max_benefit_limit'] ?  $member_mbl['max_benefit_limit'] : $total_used_mbl;

            // calculate deduction of member's remaining MBL balance
            $new_balance = $remaining_bal - $net_bill;
            $data = [
                'used_mbl'          => $used_mbl,
                'remaining_balance' => $new_balance
            ];
            $this->billing_model->update_member_remaining_balance($emp_id, $data);
        }else if($net_bill >= $remaining_bal){
            $data = [
                'used_mbl'          => $member_mbl['remaining_balance'],
                'remaining_balance' => 0
            ];
            $this->billing_model->update_member_remaining_balance($emp_id, $data);
        }
    }

    function update_request_status($type, $id){
        $data = ['status' => 'Billed'];

        if($type == 'LOA'){
            $this->billing_model->update_loa_request($id, $data);
        }else if($type == 'NOA'){
            $this->billing_model->update_noa_request($id, $data);
        }
    }

    function view_request_billing(){
        $id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $type = $this->uri->segment(3);

        if($type == 'loa'){
            $data['bill'] = $bill = $this->billing_model->get_loa_billing_info($id);
        }else if($type == 'noa'){
            $data['bill'] = $bill = $this->billing_model->get_noa_billing_info($id);
        }
        
        $data['user_role'] = $this->session->userdata('user_role');
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_receipt');
		$this->load->view('templates/footer');
    }

    function upload_loa_pdf_bill_form() {
        $loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $loa = $this->billing_model->get_loa_to_bill($loa_id);
        $data['loa_id'] = $this->uri->segment(5);
        $data['loa_no'] = $loa['loa_no'];
        $data['healthcard_no'] = $loa['health_card_no'];
        $data['patient_name'] = $loa['first_name'].' '. $loa['middle_name'].' '. $loa['last_name'].' '.$loa['suffix'];
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/upload_loa_bill_pdf');
		$this->load->view('templates/footer');
	}

    function submit_loa_pdf_bill() {
        $this->security->get_csrf_hash();
        $loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $net_bill = $this->input->post('net-bill', TRUE);

        // PDF File Upload
        $config['upload_path'] = './uploads/pdf_bills/';
        $config['allowed_types'] = 'pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('pdf-file')) {
            $response = [
                'status'  => 'save-error',
                'message' => 'PDF Bill Upload Failed'
            ];

        } else {
            $upload_data = $this->upload->data();
            $pdf_file = $upload_data['file_name'];
            $loa = $this->billing_model->get_loa_to_bill($loa_id);

            $data = [
                'billing_no'            => 'BLN-' . strtotime(date('Y-m-d h:i:s')),
                'billing_type'          => 'PDF Billing',
                'emp_id'                => $loa['emp_id'],
                'loa_id'                => $loa_id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $loa['work_related'],
                'net_bill'              => $net_bill,
                'pdf_bill'              => $pdf_file,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed'
            ];    

            $inserted = $this->billing_model->insert_billing($data);
            if(!$inserted){
                $response = [
                   'status'  => 'save-error',
                   'message' => 'PDF Bill Upload Failed'
                ];
            }
            $type = 'LOA';
            $this->update_request_status($type, $loa_id);
            $response = [
                'status'  => 'success',
                'message' => 'PDF Bill Uploaded Successfully'
            ];   
        }

        echo json_encode($response);
	}

    function loa_pdf_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

	function upload_noa_pdf_bill_form() {
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $noa = $this->billing_model->get_noa_to_bill($noa_id);
        $data['noa_id'] = $this->uri->segment(5);
        $data['noa_no'] = $noa['noa_no'];
        $data['healthcard_no'] = $noa['health_card_no'];
        $data['patient_name'] = $noa['first_name'].' '. $noa['middle_name'].' '. $noa['last_name'].' '.$noa['suffix'];
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/upload_noa_bill_pdf');
		$this->load->view('templates/footer');
	}

    
    function db_upload_textfile(){
        $token = $this->security->get_csrf_hash();
        $arr_f      = [];     
        if(!empty($_FILES['textfile']['tmp_name']))            
        {   
            $filename   = $_FILES['textfile']['tmp_name']; 
            $myfile     = fopen($filename, "r") or die("Unable to open file!");
            while(! feof($myfile)) {
                $arr_f[]= fgets($myfile);                  
            }

            $fname      = $_FILES['textfile']['name'];
            $ext        = explode(".",$fname); 
            $ext        = $ext[1];

            if($ext == "csv"){ 
                $delimeter = ",";
            }else if($ext == "txt"){
                $delimeter = "|";
            }else{
                $response = [
                    'token' => $token,
                    'status' => 'error-delimiter',
                    'message' => 'Error in file format delimiter.',
                ];
                echo json_encode($response);
                exit;
            }

            fclose($myfile);     
            $flag = '';
            $check = explode("|", $arr_f[0]);
            $countcolumn = count($check);
            
            if(trim($countcolumn) != 8 && trim($countcolumn) != 6){
                $response = [
                    'token' => $token,
                    'status' => 'error-format',
                    'message' => 'Textfile Uploading Failed. Text file format does not match with the uploaded file!',
                ];
                echo json_encode($response);
                die();
            }
            else
            {                
                for ($i=0; $i < count($arr_f); $i++) 
                {
                    $arr = [];                
                    $arr = explode($delimeter, trim(str_replace('"', "", $arr_f[$i] )) );              
                    
                    if(trim($arr[0])!="" && $countcolumn == 8 )
                    {                          
                        $vcode              = $arr[0]; 
                        $vname              = $arr[1];                            
                        $address            = $arr[2];                            
                        $address2           = $arr[3];                            
                        $city               = $arr[4];                            
                        $contact            = $arr[5];                            
                        $vposting           = $arr[6];                            
                        $currency           = @$arr[7];                            
                    }else if(trim($arr[0])!="" && $countcolumn == 6){
                        $vcode              = $arr[0]; 
                        $vname              = $arr[1];                            
                        $address            = $arr[2];                            
                        $address2           = $arr[3];                            
                        $city               = $arr[4];                            
                        $contact            = $arr[5];                            
                        $vposting           = "";
                        $currency           = "";     
                    }
                    $user_fullname = $this->session->userdata('fullname');
                    $data = [
                        'tf_2' => $vcode,
                        'tf_3' => $vname,
                        'tf_4' => $address,
                        'tf_5' => $address2,
                        'tf_6' => $city,
                        'tf_7' => $contact,
                        'tf_8' => $vposting,
                        'tf_9' => $currency,
                        'date_added' => date('Y-m-d'),
                        'added_by' => $user_fullname,
                        'status' => 'Posted'
                    ];

                    //dli pwede mag double ug upload ang same vendor code ug vendor name
                    // if($vposting == "CONSIGNOR"){

                    //     $where   = "vendor_code = '$vcode' ";  
                    //     $buid    = $this->getStore($vcode);
                    //     $exist   = $this->vendor_model->check_if_already_exist("vendor_code","tbl_vendor",$where);
                    //     if($exist == 0){
                            $flag = $this->billing_model->insert_textfile($data);

                            //LOGS
                           /* $log  = date('Y-m-d H:i:s')."| $vcode | $vname | ".$this->vendor_model->session_user()." \r\n";                          
                            $this->vendor_model->writeLogs($log,"../logs/","UPLOAD VENDOR-");*/
                    //     }else{
                    //         $flag = 1;
                    //     }
                    // }
                } 

                if($flag){
                    $response = [
                        'token' => $token,
                        'status' => 'success',
                        'message' => 'Textfile Uploading Done.',
                    ];
                    echo json_encode($response);
                    
                }else{
                    $response = [
                        'token' => $token,
                        'status' => 'error',
                        'message' => 'Textfile Uploading Failed. Error in inserting data in the table!',
                    ];
                    echo json_encode($response);
                    
                } 
            }
               
        }else{
            $response = [
                'token' => $token,
                'status' => 'empty',
                'message' => 'There must be an error in uploading text file.',
            ];
            echo json_encode($response);
           
        }       
    }  


}
