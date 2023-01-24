<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/Billing_model');
        $this->load->model('healthcare_provider/Loa_model');
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

    function billing1FindMember() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing1FindMember');
        $this->load->view('templates/footer');
    }

    function billing2ResultMember() {
        $this->security->get_csrf_hash();
        $firstNameMember = $this->input->post('firstNameMember');
        $lastNameMember = $this->input->post('lastNameMember');
        $dateMember =  $this->input->post('dateMember');
        $idHospital =  $this->session->userdata('dsg_hcare_prov');
        $member = $this->Billing_model->find_billing_member($firstNameMember, $lastNameMember, $dateMember);

        if ($member) {
            $memberMBL = $this->Billing_model->find_mbl($member->emp_id);
            $hospitalName = $this->Billing_model->find_hospital($idHospital);
            $userLoaList = $this->Billing_model->findUserLoa($member->emp_id, $idHospital);
            $userNoaList = $this->Billing_model->findUserNoa($member->emp_id, $idHospital);
            $billing_number = "RHI-" . strtotime(date('Y-m-d h:i:sa')) . $member->member_id;

            $this->session->set_userdata(array(
                'bmember' => $member,
                'bmemberMBL' => $memberMBL,
                'bhospital' => $hospitalName,
                'bhealth_card_no' => $member->health_card_no,
                'bbilling_number' => $billing_number
            ));

            $data['user_role'] = $this->session->userdata('user_role');

            $data['member'] = $member;
            $data['memberMBL'] = $memberMBL;
            $data['hospital'] = $hospitalName;
            $data['billing_number'] = $billing_number;
            $data['userLoaList'] = $userLoaList;
            $data['userNoaList'] = $userNoaList;


            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/billing2ResultMember', array('data' => $data));
            $this->load->view('templates/footer');
        } else {
            $arr = array('error' => 'No Members Found!');
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        }
    }

    function billing2ResultMemberById() {
        $this->security->get_csrf_hash();

        $healthCardNo = $this->input->post('healthCardNo');

        $idHospital =  $this->session->userdata('dsg_hcare_prov');
        $member = $this->Billing_model->find_billing_member_by_healthcard($healthCardNo);

        if ($member) {
            $memberMBL = $this->Billing_model->find_mbl($member->emp_id);
            $hospitalName = $this->Billing_model->find_hospital($idHospital);
            $userLoaList = $this->Billing_model->findUserLoa($member->emp_id, $idHospital);
            $userNoaList = $this->Billing_model->findUserNoa($member->emp_id, $idHospital);
            $billing_number = "RHI-" . strtotime(date('Y-m-d h:i:sa')) . $member->member_id;

            $this->session->set_userdata(array(
                'bmember' => $member,
                'bmemberMBL' => $memberMBL,
                'bhospital' => $hospitalName,
                'bhealth_card_no' => $member->health_card_no,
                'bbilling_number' => $billing_number
            ));

            $data['page_title'] = 'Alturas Healthcare - Healthcare Provider';
            $data['user_role'] = $this->session->userdata('user_role');

            $data['member'] = $member;
            $data['memberMBL'] = $memberMBL;
            $data['hospital'] = $hospitalName;
            $data['billing_number'] =  $billing_number;
            $data['userLoaList'] = $userLoaList;
            $data['userNoaList'] = $userNoaList;

            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/billing2ResultMember.php', array('data' => $data));
            $this->load->view('templates/footer');
        } else {
            $arr = array('error' => 'No Members Found!');
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        }
    }

    function billing3BillLoa() {
        $this->security->get_csrf_hash();

        $this->cart->destroy();
        $this->session->unset_userdata(array('equipments'));

        $loa_id = $this->uri->segment(6);
        $billing_number = $this->uri->segment(7);
        $billingLoaResultDb = $this->Billing_model->billingLoa($loa_id);
        $billingLoaResult = $billingLoaResultDb["med_services"];
        $payLoadMedService = array();
        $expodedMedServices = explode(";", $billingLoaResult);

        foreach ($expodedMedServices as $extractMedServicesId) :
            $resultMedServices = $this->Billing_model->getCostType($extractMedServicesId);
            array_push($payLoadMedService, $resultMedServices);
        endforeach;

        $cost_type = $this->Billing_model->find_cost_type();

        $data['cost_type'] = $cost_type;
        $data['loaService'] = $payLoadMedService;
        $data['loaRequestType'] = $billingLoaResultDb["loa_request_type"];
        $data['memberMBL'] = $this->session->userdata('bmemberMBL');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['user_info'] =
            array(
                'member' => $this->session->userdata('bmember'),
                'remaining_balance' => $this->session->userdata('bmemberMBL'),
                'health_card_no' => $this->session->userdata('bhealth_card_no'),
                'billing_number' => $billing_number,
            );

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing3Loa.php', array('data' => $data));
        $this->load->view('templates/footer');
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

        $cost_type = $this->Billing_model->find_cost_type();
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
        $data['cost_type'] = $this->Billing_model->find_cost_type();

        $data['page_title'] = 'Alturas Healthcare - Healthcare Provider';
        $data['user_role'] = $this->session->userdata('user_role');
        $data['equipments'] = $this->session->userdata('equipments');

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/billing3NoaReview', array('data' => $data));
        $this->load->view('templates/footer');
    }

    function billing5Final() {
        $this->security->get_csrf_hash();

        $data['page_title'] = 'Alturas Healthcare - Healthcare Provider';
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

            $this->Billing_model->loa_personal_charges($perosonalCharges);
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
            $this->Billing_model->loa_cost_type_by($dataSave);
        }

        $this->Billing_model->close_billing_noa_requests($this->input->post('noa_id'));
        $this->Billing_model->pay_billing_member($billingData);
        redirect('healthcare-provider/billing/billing-person', 'refresh');
    }

    //Ajax call
    function addEquip() {
        $token = $this->security->get_csrf_hash();

        $id = $this->uri->segment(6);
        $data = $this->Billing_model->addEquipment($id);
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



    function getBillingLoa() {
        $this->security->get_csrf_hash();

        $this->cart->destroy();
        $this->session->unset_userdata(array('equipments'));

        //$loa_id = $this->uri->segment(6);
        $loa_id = $this->input->post('id');
        $billingLoaResultDb = $this->Billing_model->billingLoa($loa_id);
        $billingLoaResult = $billingLoaResultDb["med_services"];

        $payLoadMedService = array();
        $expodedMedServices = explode(";", $billingLoaResult);

        foreach ($expodedMedServices as $extractMedServicesId) :
            $resultMedServices = $this->Billing_model->getCostType($extractMedServicesId);
            array_push($payLoadMedService, $resultMedServices);
        endforeach;

        $cost_type = $this->Billing_model->find_cost_type();

        $data['page_title'] = 'HMO - HealthCare Provider';
        $data['loaService'] = $payLoadMedService;
        $data['memberMBL'] = $this->session->userdata('bmemberMBL');
        $data['loaRequestType'] = $billingLoaResultDb["loa_request_type"];
        $data['user_role'] = $this->session->userdata('user_role');
        echo json_encode($data);
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
        $this->Billing_model->loa_cost_type_by($loaCostBy);
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

        $this->Billing_model->loa_personal_charges($perosonalCharges);
        echo json_encode($perosonalCharges);
    }


    function billLoaMember() {
        $this->security->get_csrf_hash();

        $mbr_remaining_bal = $this->input->post('mbr_remaining_bal');
        $loa_id = $this->input->post('loa_id');
        $total_bill = $this->input->post('total_bill');
        $idHospital =  $this->session->userdata('dsg_hcare_prov');

        $this->Billing_model->close_billing_loa_requests($loa_id);

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
        echo json_encode($this->Billing_model->pay_billing_member($billingSchema));
    }


    function billingServicesMember() {
        $this->security->get_csrf_hash();
    }
}
