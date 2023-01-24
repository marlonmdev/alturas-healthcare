<?php


class Noa_model extends CI_Model
{

    public function noa_member_pending($id)
    {

        $this->db->select('*');
        $this->db->from('noa_requests as t1');
        $this->db->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id');
        $this->db->where('t1.hospital_id', $id);
        $this->db->where('t1.status', 'Pending');

        $query = $this->db->get();
        return $query->result();
    }
    public function noa_member_approved($id)
    {

        $this->db->select('*');
        $this->db->from('noa_requests as t1');
        $this->db->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id');
        $this->db->where('t1.hospital_id', $id);
        $this->db->where('t1.status', 'Approved');

        $query = $this->db->get();
        return $query->result();
    }

    public function noa_member_closed($id)
    {

        $this->db->select('*');
        $this->db->from('noa_requests as t1');
        $this->db->join('healthcare_providers as t2', 't1.hospital_id = t2.hp_id');
        $this->db->where('t1.hospital_id', $id);
        $this->db->where('t1.status', 'Closed');

        $query = $this->db->get();
        return $query->result();
    }
}
