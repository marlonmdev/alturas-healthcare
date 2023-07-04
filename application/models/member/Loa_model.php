<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table = 'loa_requests';
  var $column_order = ['loa_no', 'request_date', 'hcare_provider', 'loa_request_type']; //set column field database for datatable orderable
  var $column_search = ['loa_no', 'request_date', 'expiration_date', 'hp_name', 'loa_request_type', 'med_services']; //set column field database for datatable searchable 
  var $order = ['loa_id' => 'desc']; // default order 

  private function _get_datatables_query($status, $emp_id) {
    $this->db->from($this->table . ' as tbl_1');
    $this->db->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('tbl_1.status', $status);
    $this->db->where('tbl_1.emp_id', $emp_id);
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

  function get_datatables($status, $emp_id) {
    $this->_get_datatables_query($status, $emp_id);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered($status, $emp_id) {
    $this->_get_datatables_query($status, $emp_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all($status, $emp_id) {
    $this->db->from($this->table)
             ->where('status', $status)
             ->where('emp_id', $emp_id);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

   function get_billed_datatables($emp_id) {
    $status = ['Billed', 'Payable', 'Payment'];
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where_in('tbl_1.status', $status)
             ->where('tbl_1.emp_id', $emp_id)
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
   }

   function get_paid_datatables($emp_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Paid')
             ->where('tbl_1.emp_id', $emp_id)
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
   }
 
   function get_bill_info($loa_id) {
    return $this->db->get_where('billing', ['loa_id' => $loa_id])->row_array();
  }

  function get_paid_date($details_no) {
    return $this->db->get_where('payment_details', ['details_no' => $details_no])->row_array();
  }

  function db_get_max_loa_id() {
    $this->db->select_max('loa_id');
    $query = $this->db->get('loa_requests');
    return $query->row_array();
  }


  function db_insert_loa_request($post_data) {
    $query = $this->db->insert('loa_requests', $post_data);
    return $query ? $this->db->insert_id() : false;
  }

  function db_update_loa_request($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $post_data);
  }

  function db_get_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->result_array();
  }

  function db_get_company_doctors() {
    $query = $this->db->get('company_doctors');
    return $query->result_array();
  }

  function db_get_cost_types() {
    $query = $this->db->get('cost_types');
    return $query->result_array();
  }

  function db_get_cost_types_by_hp($hp_id) {
    $query = $this->db->get_where('cost_types', ['hp_id' => $hp_id]);
    return $query->result_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }

  function db_get_pending_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.work_related, tbl_1.request_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->having('tbl_1.status', 'Pending')
             ->where('tbl_1.emp_id', $emp_id)
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_approved_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.hcare_provider, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.work_related, tbl_1.request_date, tbl_1.approved_on, tbl_1.expiration_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('loa_id', 'DESC')
             ->where('tbl_1.emp_id', $emp_id);
    return $this->db->get()->result_array();
  }

  function db_get_disapproved_loa($emp_id) {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.rx_file, tbl_1.work_related, tbl_1.request_date, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Disapproved')
             ->where('tbl_1.emp_id', $emp_id);
    $this->db->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_loa_services($loa_id){
    $this->db->select('loa_id, med_services')
             ->from('loa_requests')
             ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_get_loa_info($loa_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_get_requesting_physician($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_doctor_info($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function get_hospital_name($hospital_id) {
    $this->db->where('hospital_id', $hospital_id);
    $query = $this->db->get('affiliate_hospitals');
    return $query->row_array();
  }

  function db_check_healthcare_provider_exist($hp_id) {
    $this->db->where('hp_id', $hp_id);
    $query = $this->db->get('healthcare_providers');
    return $query->num_rows() > 0 ? true : false;
  }

  function db_get_loa_attach_filename($loa_id) {
    $this->db->select('loa_id, rx_file')
             ->where('loa_id', $loa_id);
    return $this->db->get('loa_requests')->row_array();
  }

  function db_cancel_loa($loa_id) {
    $this->db->where('loa_id', $loa_id)
             ->delete('loa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_member_infos($emp_id){
    $this->db->where('emp_id', $emp_id);
    $query = $this->db->get('members');
    return $query->num_rows() > 0 ? $query->row_array() : false;
  }

  function db_get_status_pending($emp_id){
    $this->db->select('status');
    $this->db->where('emp_id', $emp_id);
    $query = $this->db->get('loa_requests');
    return $query->row_array();
  }

  function db_insert_loa_cancellation_request($post_data){
    $query = $this->db->insert('loa_cancellation_requests', $post_data);
    return $query;
  }

  function db_get_loa_cancellation_request($loa_id){
    $this->db->where('loa_id', $loa_id);
    return $this->db->get('loa_cancellation_requests')->row_array();
  }

  // Start of cancellation_requests server-side processing datatables
  var $table1 = 'loa_cancellation_requests';
  var $columnOrder = ['loa_no', 'requested_on', null, 'confirmed_on', null, null, null]; //set column field database for datatable orderable
  var $columnSearch = ['loa_no', 'requested_on', 'confirmed_on', 'confirmed_by', 'status']; //set column field database for datatable searchable 
  var $order1 = ['lcancel_id' => 'desc']; // default order 

  private function _get_cancel_datatables_query($status, $emp_id) {
    $this->db->from($this->table1);
    $this->db->where('status', $status);
    $this->db->where('requested_by', $emp_id);
    $i = 0;
    // loop column 
    foreach ($this->columnSearch as $item) {
      // if datatable send POST for search
      if ($_POST['search']['value']) {
        // first loop
        if ($i === 0) {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->columnSearch) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    // here order processing
    if (isset($_POST['order'])) {
      $this->db->order_by($this->columnOrder[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order1)) {
      $order = $this->order1;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_cancel_datatables($status, $emp_id) {
    $this->_get_cancel_datatables_query($status, $emp_id);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_cancell_filtered($status, $emp_id) {
    $this->_get_cancell_datatables_query($status, $emp_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_cancell($status, $emp_id) {
    $this->db->from($this->table1)
             ->where('status', $status)
             ->where('requested_by', $emp_id);;
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function db_get_mbl($emp_id){
    $this->db->where('emp_id', $emp_id);
    $query = $this->db->get('max_benefit_limits');
    return $query->num_rows() > 0 ? $query->row_array() : false;
  }

  var $table_1 = 'loa_requests'; 
  var $table_3 = 'billing';
  var $column_order_history = array('tbl_1.loa_no', 'tbl_2.net_bill', 'tbl_1.status','tbl_1.request_date');
  var $column_search_history = array('tbl_1.loa_no','tbl_2.net_bill','tbl_1.status','tbl_1.request_date'); //set column field database for datatable searchable 
  var $order_history = array('tbl_1.loa_id' => 'desc'); // default order 
  private function _get_loa_datatables_query($emp_id) {
      // Select all data from the first table
      $this->db->select('tbl_1.status as tbl1_status, tbl_1.loa_id as tbl1_loa_id, tbl_1.request_date as tbl1_request_date, tbl_1.*, tbl_2.*');
      $this->db->from($this->table_1 . ' as tbl_1');
      $this->db->join($this->table_3 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left');
      $this->db->where('tbl_1.emp_id', $emp_id);
      $i = 0;
      // loop column 
      foreach ($this->column_search_history as $item) {
      // if datatable send POST for search
      if ($_POST['search']['value']) {
          // first loop
          if ($i === 0) {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
          } else {
          $this->db->or_like($item, $_POST['search']['value']);
          }

          if (count($this->column_search_history) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
      }

      // here order processing
      if (isset($_POST['order'])) {
      $this->db->order_by($this->column_order_history[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } else if (isset($this->order_history)) {
      $order = $this->order_history;
      $this->db->order_by(key($order), $order[key($order)]);
      }
     
  }

  
  function get_loa_datatables($emp_id) {
      $this->_get_loa_datatables_query($emp_id);
      if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result_array();
  }

  function count_loa_filtered($emp_id) {
      $this->_get_loa_datatables_query($emp_id);
      $query = $this->db->get();
      return $query->num_rows();
  }

  function count_all_loa($emp_id) {
      $this->db->from($this->table_1)
              ->where('emp_id', $emp_id);
      return $this->db->count_all_results();
  }

  function get_loa_op_price($ctype_id) {
      $this->db->select('op_price')
           ->from('cost_types')
           ->where('ctype_id', $ctype_id);
      $query = $this->db->get();
      return $query->row_array();
  }

  // function get_loa_status($loa_id) {
  //   $this->db->select('loa_requests.status');
  //   $this->db->from('loa_requests');
  //   $this->db->where('loa_id', $loa_id);
  //   $query = $this->db->get();
  //   $result = $query->row_array();
  //   return $result['status'];
  // }

  function db_get_loa_info_patient($loa_id,$is_performed) {
    // var_dump("passed loa id",$loa_id);
    $query = $this->db->select('loa_request_type')
    ->from('loa_requests')
    ->where('loa_id', $loa_id)
    ->get();
    $status = $query->row()->loa_request_type;
    
    $this->db->select('tbl_1.status as tbl_1_status, tbl_1.*, tbl_2.* ,tbl_3.*, tbl_5.*');
    $this->db->from('loa_requests as tbl_1');
    $this->db->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id');
    // $this->db->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id');
    $this->db->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id');
  
    if ($status !=='Emergency') {
        $this->db->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id');
        $this->db->select('tbl_4.*');
    }
    if ($is_performed) {
        $this->db->join('performed_loa_info as tbl_6', 'tbl_1.loa_id = tbl_6.loa_id');
        $this->db->select('tbl_6.*');
    }

    $this->db->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function get_performed_info($loa_id){
    return $this->db->get_where('performed_loa_info', ['loa_id' => $loa_id,
                    'physician_fname !=' => '', 'physician_mname !=' => '','physician_lname !=' => '',])->result_array();
  }

  function check_performed_loa($loa_id) {
    $this->db->from('performed_loa_info')
        ->where('loa_id', $loa_id);
    return $this->db->count_all_results();
  }

  function paid_loa($details_no) {
    $this->db->from('payment_details')
        ->where('details_no', $details_no);
    return $this->db->get()->row_array();
  }

  function get_loa_billing_info($id){
    $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.billing_type, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_1.pdf_bill, tbl_1.attending_doctors, tbl_1.details_no, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_2.health_card_no, tbl_3.hp_name')
             ->from('billing as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
             ->where('tbl_1.loa_id', $id);
    return $this->db->get()->row_array();
  }

  function get_pac_loa($emp_id) {
    $this->db->select('*')
    ->from('loa_requests')
    ->where('emp_id', $emp_id)
    ->where_in('status', array('Pending', 'Approved', 'Completed'));
    return $this->db->get()->result_array();
   }

}
