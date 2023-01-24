<?php


class Billing_model extends CI_Model
{

    public function find_billing_member($firstNameMember, $lastNameMember, $dateMember)
    {
        $authA = $this->db->get_where('members', array('first_name' => $firstNameMember, 'last_name' => $lastNameMember, 'date_of_birth' => $dateMember))->row();
        return $authA;
    }

    public function find_billing_member_by_healthcard($healthCardNo)
    {
        $authA = $this->db->get_where('members', array('health_card_no' => $healthCardNo))->row();
        return $authA;
    }


    public function find_hospital($id)
    {
        $hospitalName = $this->db->get_where('healthcare_providers', array('hp_id' => $id))->row();
        return $hospitalName;
    }

    public function find_mbl($id)
    {
        $hospitalName = $this->db->get_where('max_benefit_limits', array('emp_id' => $id))->row();
        return $hospitalName;
    }

    public function findUserLoa($id, $idHospital)
    {
        return $this->db->get_where('loa_requests', array('emp_id' => $id, 'hcare_provider' => $idHospital))->result();
    }
    public function billingLoa($id)
    {
        return $this->db->get_where('loa_requests', array('loa_id' => $id))->row_array();
    }

    public function getCostType($id)
    {
        return $this->db->get_where('cost_types', array('ctype_id' => $id))->row_array();
    }

    public function findUserNoa($id, $idHospital)
    {
        return $this->db->get_where('noa_requests', array('emp_id' => $id, 'hospital_id' => $idHospital))->result();
    }

    public function find_cost_type()
    {
        $costTypes = $this->db->get('cost_types')->result();
        return $costTypes;
    }


    public function pay_billing_member($data)
    {
        return $this->db->insert('billing', $data);
    }

    public function addEquipment($id)
    {
        return $this->db->get_where('cost_types', array('ctype_id' => $id))->row_array();
    }

    public function create_billing($post_data)
    {
        return $this->db->insert('billing', $post_data);
    }

    public function loa_cost_type_by($cost_type)
    {
        return $this->db->insert('billing_services', $cost_type);
    }

    public function loa_personal_charges($personal_charges)
    {

        return $this->db->insert('personal_charges', $personal_charges);
    }


    public function close_billing_loa_requests($id)
    {
        $this->db->set('status', 'Closed');
        $this->db->where('loa_id', $id);
        return $this->db->update('loa_requests'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2 

    }

    public function close_billing_noa_requests($id)
    {
        $this->db->set('status', 'Closed');
        $this->db->where('noa_id', $id);
        return $this->db->update('noa_requests'); // gives UPDATE `mytable` SET `field` = 'field+1' WHERE `id` = 2 

    }

    public function billing_count($id)
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->where('hp_id', $id);

        $query = $this->db->get();
        return $query->result();
    }
}
