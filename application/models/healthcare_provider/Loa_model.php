<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model{

    // Start of server-side processing datatables
    var $table_1 = 'loa_requests';
    var $table_2 = 'healthcare_providers';
   // var $column_order = array('loa_no', 'first_name', 'loa_request_type', null, null, 'request_date', null, null); //set column field database for datatable orderable
    var $column_order = array('loa_no', 'first_name', 'loa_request_type', null, null, 'request_date');
    var $column_search = array('loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'request_date'); //set column field database for datatable searchable 
    var $order = array('loa_id' => 'desc'); // default order 

    private function _get_datatables_query($status, $hp_id) {
        $this->db->from($this->table_1 . ' as tbl_1');
        $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
        $this->db->where('status', $status);
        $this->db->where('hcare_provider', $hp_id);
        $i = 0;
        // loop column 
        foreach ($this->column_search as $item) {
        // if datatable send POST for search
        if ($_POST['search']['value']) {
            // first loop
            if ($i === 0) {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $_POST['search']['value']);
            } else {
            $this->db->or_like($item, $_POST['search']['value']);
            }

            if (count($this->column_search) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
        }

        // here order processing
        if (isset($_POST['order'])) {
        $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($status, $hp_id) {
        $this->_get_datatables_query($status, $hp_id);
        if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered($status, $hp_id) {
        $this->_get_datatables_query($status, $hp_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($status, $hp_id) {
        $this->db->from($this->table_1)
                ->where('status', $status)
                ->where('hcare_provider', $hp_id);
        return $this->db->count_all_results();
    }
    // End of server-side processing datatables

    function fetch_pending_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->where('tbl_1.hcare_provider', $hp_id)
                 ->where('tbl_1.status', 'Pending');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_approved_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Approved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_disapproved_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Disapproved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_closed_loa_requests($hp_id){
        $this->db->select('*')
                 ->from('loa_requests')
                 ->where('hcare_provider', $hp_id)
                 ->where('status', 'Closed');
        $query = $this->db->get();
        return $query->result();
    }

    function get_cost_type($id){
        $this->db->select('*')
                 ->from('cost_types')
                 ->where('ctype_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function db_get_cost_type($id){
        $query = $this->db->get('cost_types');
        return $query->result();
    }

    function db_get_loa_info($loa_id) {
        $this->db->select('*')
                 ->from('loa_requests as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
                 ->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id')
                 ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id= tbl_5.emp_id')
                 ->where('tbl_1.loa_id', $loa_id);
        return $this->db->get()->row_array();
    }

    function db_get_doctor_by_id($doctor_id) {
        $query = $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id));
        return $query->row_array();
    }

    function db_get_requesting_physician($doctor_id) {
        $query = $this->db->get_where('company_doctors', array('doctor_id' => $doctor_id));
        return $query->row_array();
    }

    function db_get_cost_types() {
        $query = $this->db->get('cost_types');
        return $query->result_array();
    }

}
