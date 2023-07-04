<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sse_model extends CI_Model {

    public function get_count_guarantee()
{
    $this->db->select('COUNT(*) as count');
    $this->db->from('billing');
    $this->db->where('guarantee_letter', 1);
    $query = $this->db->get();
    $result = $query->row();
    return $result->count;
}
public function get_count_to_bill()
    {
        // Subquery for loa_requests table
    $this->db->select('COUNT(*) as count');
    $this->db->from('loa_requests');
    $this->db->where_in('status', array('Approved', 'Completed', 'Referred'));
    $subquery_loa = $this->db->get_compiled_select();

    // Subquery for noa_requests table
    $this->db->select('COUNT(*) as count');
    $this->db->from('noa_requests');
    $this->db->where_in('status', array('Approved', 'Completed', 'Referred'));
    $subquery_noa = $this->db->get_compiled_select();

    // Combine both subqueries with UNION ALL
    $query = $this->db->query($subquery_loa . ' UNION ALL ' . $subquery_noa);

    // Get the total count by summing up the counts from both subqueries
    $total_count = 0;
    foreach ($query->result() as $row) {
        $total_count += $row->count;
    }

    return $total_count;
    }
}