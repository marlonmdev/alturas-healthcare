<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table_1 = 'noa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = array('tbl_1.noa_no', 'tbl_1.first_name', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date'); //set column field database for datatable orderable
  var $column_search = array('tbl_1.noa_no', 'tbl_1.emp_id', 'tbl_1.health_card_no', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date'); //set column field database for datatable searchable 
  var $order = array('tbl_1.noa_id' => 'desc'); // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as tbl_1');
    $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id');
    $this->db->where('tbl_1.status', $status);
    if($this->input->post('filter')){
      $this->db->like('tbl_1.hospital_id', $this->input->post('filter'));
    }

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
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

   // Start of server-side processing datatables
   var $table_1_billed = 'noa_requests';
   var $table_2_billed = 'healthcare_providers';
   var $column_order_billed =  array('tbl_1.noa_no', 'tbl_1.first_name', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date'); //set column field database for datatable orderable
   var $column_search_billed = array('noa_no', 'emp_id', 'health_card_no', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)', 'hp_name', 'request_date'); //set column field database for datatable searchable 
   var $order_billed = array('noa_id' => 'desc'); // default order 
 
   private function _get_billed_datatables_query($status) {
     $this->db->from($this->table_1_billed . ' as tbl_1');
     $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id');
     if ($status == 'Billed') {
       $this->db->group_start();
       $this->db->or_where('status', 'Payable');
       $this->db->or_where('status', 'Payment');
       $this->db->or_where('status', 'Billed');
       $this->db->group_end();
     }
     if($this->input->post('filter')){
        $this->db->like('tbl_1.hospital_id', $this->input->post('filter'));
    }
     $i = 0;
   
     if ($this->input->post('filter')) {
       $this->db->like('tbl_2.hp_id', $this->input->post('filter'));
     }
   
     // loop column
     foreach ($this->column_search_billed as $item) {
       // if datatable sends POST for search
       if ($_POST['search']['value']) {
         // first loop
         if ($i === 0) {
           $this->db->group_start();
           $this->db->like($item, $_POST['search']['value']);
         } else {
           $this->db->or_like($item, $_POST['search']['value']);
         }
   
         if (count($this->column_search_billed) - 1 == $i) {
           $this->db->group_end();
         }
       }
       $i++;
     }
   
     // order processing
     if (isset($_POST['order'])) {
       $this->db->order_by($this->column_order_billed[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
     } else if (isset($this->order_billed)) {
       $order = $this->order_billed;
       $this->db->order_by(key($order), $order[key($order)]);
     }
   }
   
   function get_datatables_billed($status) {
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
     $this->db->from($this->table_1_billed);
     if ($status == 'Billed') {
       $this->db->group_start();
       $this->db->or_where('status', 'Payable');
       $this->db->or_where('status', 'Payment');
       $this->db->or_where('status', 'Billed');
       $this->db->group_end();
     }
     return $this->db->count_all_results();
   }
   // End of server-side processing datatables
 

  function db_get_max_noa_id() {
    $this->db->select_max('noa_id');
    $query = $this->db->get('noa_requests');
    return $query->row_array();
  }

  function db_insert_noa_request($post_data) {
    return $this->db->insert('noa_requests', $post_data);
  }

  function db_update_noa_request($noa_id, $post_data) {
    $this->db->where('noa_id', $noa_id);
    return $this->db->update('noa_requests', $post_data);
  }

  function db_cancel_noa($noa_id) {
    $this->db->where('noa_id', $noa_id)
             ->delete('noa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_all_pending_noa() {
    $this->db->select('*')
             ->from('noa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
             ->where('tbl_1.status', 'Pending')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_approved_noa() {
    $this->db->select('*')
             ->from('noa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_disapproved_noa() {
    $this->db->select('*')
             ->from('noa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
             ->where('tbl_1.status', 'Disapproved')
             ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_noa_info($noa_id) {
    $this->db->select('*')
             ->from('noa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
             ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
             ->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
             ->where('tbl_1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  function db_get_billed_noa_info($noa_id) {
    $this->db->select('*')
    ->from('noa_requests as tbl_1')
    ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
    ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
    ->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
    ->join('billing as tbl_6', 'tbl_1.noa_id = tbl_6.noa_id')
    ->join('payment_details as tbl_7', 'tbl_6.details_no = tbl_7.details_no','left')
    ->where('tbl_1.noa_id', $noa_id);
return $this->db->get()->row_array();
  }

  function db_get_member_details($member_id) {
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }

  function db_check_hospital_exist($hospital_id) {
    $this->db->where('hp_id', $hospital_id);
    $query = $this->db->get('healthcare_providers');
    return $query->num_rows() > 0 ? true : false;
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }
}
