<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members_model extends CI_Model {

  // Start of server-side processing datatables
  var $table = 'members';
  var $column_order = ['member_id', 'first_name', 'emp_type', 'current_status', 'business_unit', 'dept_name']; //set column field database for datatable orderable
  var $column_search = ['member_id', 'emp_id', 'health_card_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'current_status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['member_id' => 'desc']; // default order 

  private function _get_datatables_query($approval_status) {
    $this->db->from($this->table);
    $this->db->where('approval_status', $approval_status);
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

  function get_datatables($approval_status) {
    $this->_get_datatables_query($approval_status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered($approval_status) {
    $this->_get_datatables_query($approval_status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all($approval_status) {
    $this->db->from($this->table)
             ->where('approval_status', $approval_status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_member_details($member_id) {
    $this->db->select('*');
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }

  function db_get_member_mbl($emp_id) {
    $this->db->select('*');
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }

  function db_update_member_status($emp_id, $healthcard_no, $date_approved) {
    $data = array(
      'health_card_no' => $healthcard_no,
      'date_approved' => $date_approved,
      'approval_status' => 'Approved',
    );
    $this->db->where('emp_id', $emp_id);
    return $this->db->update('members', $data);
  }

  function db_insert_max_benefit_limit($mbl_data) {
    return $this->db->insert('max_benefit_limits', $mbl_data);
  }

  function check_id_if_existed($filename, $emp_id) {
    $this->db->where('emp_id', $emp_id)
            ->where('healthcard_id', $filename);
    $query = $this->db->get('healthcards');
    if ($query->num_rows() > 0) {
        return true; // data exists
    } else {
        return false; // data does not exist
    }
  }

  function insert_scanned_emp_id($info) {
    return $this->db->insert('healthcards', $info);
  }

  function set_approval_status($status, $emp_id) {
    $this->db->set('approval_status', $status)
            ->where('emp_id', $emp_id);
    return $this->db->update('members');
  }

  function get_healthcard($emp_id) {
   $this->db->select('*')
            ->from('healthcards')
            ->where('emp_id', $emp_id);
    return $this->db->get()->row_array();
  }

   // Start of server-side processing datatables
   var $table_1 = 'members';
   var $table_2 = 'healthcards';
   var $hc_column_order = ['member_id', 'first_name', 'emp_type', 'current_status', 'business_unit', 'dept_name']; //set column field database for datatable orderable
   var $hc_column_search = ['member_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'current_status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
   var $hc_order = ['member_id' => 'desc']; // default order 
 
   private function _get_done_datatables_query($approval_status) {
     $this->db->from($this->table_1. ' as tbl_1')
              ->join($this->table_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
     $this->db->where('tbl_1.approval_status', $approval_status);
     $i = 0;
     // loop column 
     foreach ($this->hc_column_search as $item) {
       // if datatable send POST for search
       if ($_POST['search']['value']) {
         // first loop
         if ($i === 0) {
           $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
           $this->db->like($item, $_POST['search']['value']);
         } else {
           $this->db->or_like($item, $_POST['search']['value']);
         }
 
         if (count($this->hc_column_search) - 1 == $i) //last loop
           $this->db->group_end(); //close bracket
       }
       $i++;
     }
 
     // here order processing
     if (isset($_POST['order'])) {
       $this->db->order_by($this->hc_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
     } else if (isset($this->hc_order)) {
       $order = $this->hc_order;
       $this->db->order_by(key($order), $order[key($order)]);
     }
   }
 
   function get_done_datatables($approval_status) {
     $this->_get_done_datatables_query($approval_status);
     if ($_POST['length'] != -1)
       $this->db->limit($_POST['length'], $_POST['start']);
     $query = $this->db->get();
     return $query->result_array();
   }
 
   function count_done_filtered($approval_status) {
     $this->_get_done_datatables_query($approval_status);
     $query = $this->db->get();
     return $query->num_rows();
   }
 
   function count_all_done($approval_status) {
     $this->db->from($this->table_1)
              ->where('approval_status', $approval_status);
     return $this->db->count_all_results();
   }
   // End of server-side processing datatables
  //Bar =================================================
  public function bar_pending(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Pending' ");
    return $query->num_rows(); 
  }

  public function bar_approved(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Approved' ");
    return $query->num_rows(); 
  } 
  public function bar_completed(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Completed' ");
    return $query->num_rows(); 
  } 
  public function bar_referral(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Referral' ");
    return $query->num_rows(); 
  }
  public function bar_expired(){
    $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Expired' ");
    return $query->num_rows(); 
  } 
  //End =================================================
  
}
