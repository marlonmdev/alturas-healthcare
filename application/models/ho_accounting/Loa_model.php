<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model{

    // Start of server-side processing datatables
  var $table_1 = 'loa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  var $column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['loa_id' => 'desc']; // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as tbl_1');
    $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
        $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }
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
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

      // Start of server-side processing datatables
      var $table_1_billed = 'loa_requests';
      var $table_2_billed = 'healthcare_providers';
      var $column_order_billed = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
      var $column_search_billed = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
      var $order_billed = ['loa_id' => 'desc']; // default order 
    
      private function _get_billed_datatables_query($status) {
        $this->db->from($this->table_1_billed . ' as tbl_1');
        $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
        $this->db->where_in('status', $status);
        $i = 0;
    
        if($this->input->post('filter')){
            $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
        }
        // loop column 
        foreach ($this->column_search_billed as $item) {
          // if datatable send POST for search
          if ($_POST['search']['value']) {
            // first loop
            if ($i === 0) {
              $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
              $this->db->like($item, $_POST['search']['value']);
            } else {
              $this->db->or_like($item, $_POST['search']['value']);
            }
    
            if (count($this->column_search_billed) - 1 == $i) //last loop
              $this->db->group_end(); //close bracket
          }
          $i++;
        }
    
        // here order processing
        if (isset($_POST['order'])) {
          $this->db->order_by($this->column_order_billed[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order_billed)) {
          $order = $this->order_billed;
          $this->db->order_by(key($order), $order[key($order)]);
        }
      }
    
      function get_billed_datatables($status) {
        $this->_get_billed_datatables_query($status);
        if ($_POST['length'] != -1)
          $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
      }
    
      function count_filtered_billed($status) {
        $this->_get_billed_datatables_query($status);
        $query = $this->db->get();
        return $query->num_rows();
      }
    
      function count_all_billed($status) {
        $this->db->from($this->table_1_billed)
                 ->where_in('status', $status);
        return $this->db->count_all_results();
      }
      // End of server-side processing datatables

      function get_bill_info($loa_id){
        return $this->db->get_where('billing', ['loa_id' => $loa_id])->row_array();
      }

      function get_paid_date($details_no) {
        return $this->db->get_where('payment_details', ['details_no' => $details_no])->row_array();
      }

    function db_get_cost_types() {
        $query = $this->db->get('cost_types');
        return $query->result_array();
    }

    function db_get_loa_details($loa_id) {
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
        $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
        return $query->row_array();
    }

      
}