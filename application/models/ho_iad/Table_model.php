<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Table_model extends CI_Model {

    // Start of server-side processing datatables
    var $table_1 = 'loa_requests';
    var $table_2 = 'healthcare_providers';
    var $column_order = array('loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date');
    var $column_search = array('loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'hp_name', 'request_date'); //set column field database for datatable searchable 
    var $order = array('loa_id' =>  'desc'); // default order 
 
    private function _get_datatables_query($status) {
        $this->db->from($this->table_1 . ' as tbl_1');
        $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
        $this->db->where('status', $status);
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
 
    function get_datatables($status) {
        $this->_get_datatables_query($status);
        if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered($status) {
        $this->_get_datatables_query($status);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($status) {
        $this->db->from($this->table_1)
                ->where('status', $status)
                ->where('hcare_provider');
        return $this->db->count_all_results();
    }
    // End of server-side processing datatables

    function db_get_cost_types() {
        $query = $this->db->get('cost_types');
        return $query->result_array();
    }

}