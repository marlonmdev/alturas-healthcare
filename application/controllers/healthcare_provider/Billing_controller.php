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
            $data['billing_no'] = $billing_no = "BLN-" . strtotime(date('Y-m-d h:i:s'));

            $this->session->set_userdata([
                'b_member_info' => $member,
                'b_member_mbl' => $member_mbl['max_benefit_limit'],
                'b_member_bal' => $member_mbl['remaining_balance'],
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no' => $member['health_card_no'],
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
            $data['billing_no'] = $billing_no = "BLN-" . strtotime(date('Y-m-d h:i:s'));

            $this->session->set_userdata([
                'b_member_info' => $member,
                'b_member_mbl' => $member_mbl['max_benefit_limit'],
                'b_member_bal' => $member_mbl['remaining_balance'],
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no' => $member['health_card_no'],
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
        $billing_no = $this->security->xss_clean($this->input->post('billing_no'));
        $emp_id = $this->security->xss_clean($this->input->post('emp_id'));
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

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/bill_patient_loa');
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
            'token' =>  $this->security->get_csrf_hash(),
            'user_role' => $this->session->userdata('user_role'),
            'member_mbl' => $this->session->userdata('b_member_mbl'),
            'remaining_balance' => $this->session->userdata('b_member_bal'),
            'loa_services' => $med_services,
            'request_type' => $loa["loa_request_type"],
        ];

        echo json_encode($data);
    }

    function diagnostic_loa_final_billing(){
        $token = $this->security->get_csrf_hash();
        $loa_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        $posted_data =  $this->input->post(NULL, TRUE);

        $emp_id = $posted_data['emp-id'];
        $billing_no = $posted_data['billing-no'];
        $ct_names = $posted_data['ct-name'];
        $ct_quantities = $posted_data['ct-quantity'];
        $ct_fees = $posted_data['ct-fee'];
        $deduction_names = $posted_data['deduction-name'];
        $deduction_amounts = $posted_data['deduction-amount'];
        $total_bill = $posted_data['total-bill'];
        $total_deduction = $posted_data['total-deduction'];
        $net_bill = $posted_data['net-bill'];
        $personal_charge = $posted_data['personal-charge'];

        $data = [
            'billing_no'        => $billing_no,
            'emp_id'            => $emp_id,
            'loa_id'            => $loa_id,
            'hp_id'             => $this->session->userdata('dsg_hcare_prov'),
            'total_bill'        => $total_bill,
            'total_deduction'   => $total_deduction,
            'net_bill'          => $net_bill,
            'personal_charge'   => $personal_charge,
            'mbr_remaining_bal' => $this->session->userdata('b_member_bal'),
            'billed_by'         => $this->session->userdata('fullname'),
            'billed_on'         => date('Y-m-d')
        ];        

        $inserted = $this->billing_model->insert_diagnostic_test_billing($data);
        if($inserted){

            $services = [];
            $deductions = []; 

            for ($x = 0; $x < count($ct_names); $x++) {
                $services[] = [
                    'service_name' => $ct_names[$x],
                    'service_quantity'  => $ct_quantities[$x],
                    'service_fee'  => $ct_fees[$x],
                    'billing_no'   => $billing_no,
                    'date_created' => date('Y-m-d')
                ];
            }

            $this->billing_model->insert_diagnostic_test_billing_services($services);

            if(!empty($deduction_names)){
                for ($y = 0; $y < count($deduction_names); $y++) {
                    $deductions[] = [
                        'deduction_name' => $deduction_names[$y],
                        'deduction_amount'  => $deduction_amounts[$y],
                        'billing_no'   => $billing_no,
                        'date_created' => date('Y-m-d')
                    ];
                }
                $this->billing_model->insert_diagnostic_test_billing_deductions($deductions);
            }
            
            $response = [
                'token' => $token,
                'status' => 'success',
                'message' => 'Billed Successfully'
            ];

        }else{
            $response = [
                'token' => $token,
                'status' => 'error',
                'message' => 'Bill Transaction Failed'
            ];
        }

        echo json_encode($response);
    }


    function billing3BillNoa() {
        $token = $this->security->get_csrf_hash();
        $this->cart->destroy();
        $this->session->unset_userdata(array('equipments'));
        $member = array(
            'hp_id' => $this->input->post('hp_id'),
            'member_id' => $this->input->post('member_id'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'full_name' => $this->input->post('full_name'),
            'hospital_name' => $this->input->post('hospital_name'),
            'date_service' => $this->input->post('date_service'),
            'requesting_company' => $this->input->post('requesting_company'),
            'billing_number' => $this->input->post('billing_number'),
            'health_card_no' => $this->input->post('health_card_no'),
            'emp_type' => $this->input->post('emp_type'),
            'remaining_balance' => $this->input->post('remaining_balance'),
            'noa_id' => $this->input->post('noa_select_id')
        );
        $this->session->set_userdata(array(
            'intialBillingInfo' => $member
        ));

        $cost_type = $this->billing_model->get_all_cost_types();
        $data['member'] = $member;
        $data['cost_type'] = $cost_type;
        $data['page_title'] = 'HMO - HealthCare Provider';
        $data['noa_id'] = $this->input->post('noa_select_id');
        $data['user_role'] = $this->session->userdata('user_role');

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing3Noa.php', array('data' => $data));
        $this->load->view('templates/footer');
    }

    function billing3NoaReview() {
        $this->security->get_csrf_hash();
        $data['payload'] = $this->session->userdata('intialBillingInfo');
        $data['cost_type'] = $this->billing_model->find_cost_type();
        $data['user_role'] = $this->session->userdata('user_role');
        $data['equipments'] = $this->session->userdata('equipments');

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing3NoaReview', array('data' => $data));
        $this->load->view('templates/footer');
    }

    function billing5Final() {
        $this->security->get_csrf_hash();
        $data['user_role'] = $this->session->userdata('user_role');

        $data['payload'] = array(
            'token' => $this->input->post('token'),
            'hp_id' => $this->input->post('hp_id'),
            'member_id' => $this->input->post('member_id'),
            'hospital_name' => $this->input->post('hospital_name'),
            'date_service' => $this->input->post('date_service'),
            'requesting_company' => $this->input->post('requesting_company'),
            'billing_number' => $this->input->post('billing_number'),
            'full_name' => $this->input->post('full_name'),
            'health_card_no' => $this->input->post('health_card_no'),
            'emp_type' => $this->input->post('emp_type'),
            'remaining_balance' => $this->input->post('remaining_balance'),
            'philHealth' => $this->input->post('philHealth'),
            'totalCost' => $this->input->post('totalCost'),
        );

        if ($this->input->post('personal_charges') > 0) {

            $perosonalCharges = array(
                'emp_id' =>  $this->session->userdata('bmember')->emp_id,
                'loa_id' => "",
                'noa_id' => $this->input->post('noa_id'),
                'billing_no	' => $this->input->post('billing_number'),
                'pcharge_amount' => $this->input->post('personal_charges'),
                'date_created' => date("Y-m-d"),
                'notes' => '',
                'status' => 'Pending'
            );

            $this->billing_model->loa_personal_charges($perosonalCharges);
        }

        $billingData = array(
            'billing_no'         => $this->input->post('billing_number'),
            'emp_id'             => $this->session->userdata('bmember')->emp_id,
            'noa_id'             => $this->input->post('noa_id'),
            'hp_id'              => $this->input->post('hp_id'),
            'billed_by'          => $this->session->userdata('fullname'),
            'billing_date'       => date("Y-m-d"),
            'mbr_remaining_bal'  => $this->input->post('mbr_remaining_bal'),
            'total_bill'         =>  $this->input->post('total_bill'),
            'loa_id'             => '',
            'billing_img'        => '',
            'personal_charges'   => $this->input->post('personal_charges'),
        );

        foreach ($this->cart->contents() as $extract) {
            $dataSave = array(
                'billing_no' =>  $this->input->post('billing_number'),
                'emp_id' => $this->input->post('member_id'),
                'billing_date' => date("Y-m-d"),
                'bsv_cost_types' => $extract['id'],
                'bsv_ct_fee' => $extract['price'],
            );
            $this->billing_model->loa_cost_type_by($dataSave);
        }

        $this->billing_model->close_billing_noa_requests($this->input->post('noa_id'));
        $this->billing_model->pay_billing_member($billingData);
        redirect('healthcare-provider/billing/billing-person', 'refresh');
    }

    //Ajax call
    function addEquip() {
        $token = $this->security->get_csrf_hash();

        $id = $this->uri->segment(6);
        $data = $this->billing_model->addEquipment($id);
        $res = array('token' => $token, 'name' => $data['cost_type']);
        $this->cart->insert($res);
        echo json_encode($res);
    }


    function addEquipments() {
        $this->security->get_csrf_hash();

        $sessionPayload =  array(
            'id' => $this->input->post('ctype_id'),
            'qty'     => 1,
            'price'   => $this->input->post('amount'),
            'name'    => $this->input->post('cost_type'),
            'options' => array('billingNumber' => $this->input->post('billingNumber'), 'emp_id' => $this->input->post('emp_id'))

        );

        $data = array(
            'id'      => 'sku_123ABC',
            'qty'     => 1,
            'price'   => 39.95,
            'name'    => 'T-Shirt',
            'options' => array('Size' => 'L', 'Color' => 'Red')
        );

        $this->cart->insert($sessionPayload);
    }

    function showAllEquipment() {
        $this->security->get_csrf_hash();
        $res = $this->cart->contents();
        echo json_encode($res);
    }


    function postBillingLoa($data) {
        $this->security->get_csrf_hash();
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing5Final.php', array('data' => $data));
        $this->load->view('templates/footer');
    }

    
    function saveLoaCostType() {
        $token = $this->security->get_csrf_hash();

        $loaCostBy = array(
            'billing_no' => $this->session->userdata('bbilling_number'),
            'bsv_cost_types' => $this->input->post('bsv_cost_types'),
            'bsv_ct_fee' => $this->input->post('bsv_ct_fee'),
            'emp_id' => $this->session->userdata('bmember')->emp_id,
            'billing_date' => date("Y-m-d"),
        );
        $this->billing_model->loa_cost_type_by($loaCostBy);
        echo json_encode($loaCostBy);
    }

    function billPersonalCharges() {
        $this->security->get_csrf_hash();

        $perosonalCharges = array(
            'emp_id' =>  $this->session->userdata('bmember')->emp_id,
            'loa_id' => $this->input->post('loa_id'),
            'noa_id' => "",
            'billing_no	' => $this->session->userdata('bbilling_number'),
            'pcharge_amount' => $this->input->post('pcharge_amount'),
            'date_created' => date("Y-m-d"),
            'notes' => '',
            'status' => 'Pending'
        );

        $this->billing_model->loa_personal_charges($perosonalCharges);
        echo json_encode($perosonalCharges);
    }


    function billLoaMember() {
        $this->security->get_csrf_hash();

        $mbr_remaining_bal = $this->input->post('mbr_remaining_bal');
        $loa_id = $this->input->post('loa_id');
        $total_bill = $this->input->post('total_bill');
        $idHospital =  $this->session->userdata('dsg_hcare_prov');

        $this->billing_model->close_get_loa_to_bill_requests($loa_id);

        $billingSchema = array(
            'billing_no' => $this->session->userdata('bbilling_number'),
            'emp_id' => $this->session->userdata('bmember')->emp_id,
            'loa_id' => $loa_id,
            'noa_id' => "",
            'hp_id' => $idHospital,
            'total_bill' => $total_bill = $this->input->post('total_bill'),
            'mbr_remaining_bal' =>  $mbr_remaining_bal,
            'personal_charges' => $this->input->post('personal_charges'),
            'billing_img' => '',
            'billed_by' => $this->session->userdata('fullname'),
            'billing_date' =>  date("Y-m-d")
        );
        echo json_encode($this->billing_model->pay_billing_member($billingSchema));
    }

}
