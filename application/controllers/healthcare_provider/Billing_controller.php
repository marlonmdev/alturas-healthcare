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
        $url_id = $this->uri->segment(4); // encrypted id
        $loa_id = $this->myhash->hasher($url_id, 'decrypt');
        $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $loa = $this->billing_model->get_loa_to_bill($loa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_all_cost_types();
        $data['loa'] = $loa;
        $data['request_type'] = $loa["loa_request_type"];
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
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

    function bill_patient_noa() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $noa_id = $this->myhash->hasher($url_id, 'decrypt');
        $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $noa = $this->billing_model->get_noa_to_bill($noa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_all_cost_types();
        $data['noa'] = $noa;
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['noa_id'] = $url_id;
        $data['noa_no'] = $noa['noa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];

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

    function insert_patient_billing($type, $posted_data, $id){
        if($type === 'LOA'){
            $data = [
                'billing_no'        => $posted_data['billing-no'],
                'emp_id'            => $posted_data['emp-id'],
                'loa_id'            => $id,
                'hp_id'             => $this->session->userdata('dsg_hcare_prov'),
                'total_bill'        => $posted_data['total-bill'],
                'total_deduction'   => $posted_data['total-deduction'],
                'net_bill'          => $posted_data['net-bill'],
                'personal_charge'   => $posted_data['personal-charge'],
                'mbr_remaining_bal' => $posted_data['remaining-balance'],
                'billed_by'         => $this->session->userdata('fullname'),
                'billed_on'         => date('Y-m-d')
            ];      
        }else if($type === 'NOA'){
            $data = [
                'billing_no'        => $posted_data['billing-no'],
                'emp_id'            => $posted_data['emp-id'],
                'noa_id'            => $id,
                'hp_id'             => $this->session->userdata('dsg_hcare_prov'),
                'total_bill'        => $posted_data['total-bill'],
                'total_deduction'   => $posted_data['total-deduction'],
                'net_bill'          => $posted_data['net-bill'],
                'personal_charge'   => $posted_data['personal-charge'],
                'mbr_remaining_bal' => $posted_data['remaining-balance'],
                'billed_by'         => $this->session->userdata('fullname'),
                'billed_on'         => date('Y-m-d')
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

    function noa_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

}
