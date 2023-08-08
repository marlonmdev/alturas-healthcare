<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model {

  function db_get_affiliated_healthcare_providers() {
    $query = $this->db->get_where('healthcare_providers',['accredited'=>1]);
    return $query->result_array();
  }
  function db_get_not_affiliated_healthcare_providers() {
    $query = $this->db->get_where('healthcare_providers',['accredited'=>0]);
    return $query->result_array();
  }
  // function db_update_noa_charge_type($noa_id, $data) {
  //   $this->db->where('noa_id', $noa_id);
  //   return $this->db->update('noa_requests', $data);
  // }

  // Start of server-side processing datatables
  var $table_1 = 'noa_requests';
  var $table_2 = 'healthcare_providers';
  var $column_order = ['tbl_1.noa_no', 'tbl_1.first_name', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date', null, null]; //set column field database for datatable orderable
  var $column_search = ['tbl_1.noa_no', 'tbl_1.emp_id', 'tbl_1.health_card_no', 'tbl_1.first_name', 'tbl_1.middle_name', 'tbl_1.last_name', 'tbl_1.suffix', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date', 'CONCAT(tbl_1.first_name, " ",tbl_1.last_name)',   'CONCAT(tbl_1.first_name, " ",tbl_1.last_name, " ", tbl_1.suffix)', 'CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ",tbl_1.last_name)', 'CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ",tbl_1.last_name, " ", tbl_1.suffix)']; //set column field database for datatable searchable 
  var $order = ['tbl_1.noa_id' => 'asc']; // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as tbl_1');
    $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id');
    $this->db->where('tbl_1.status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hospital_id', $this->input->post('filter'));
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

  function db_update_noa_charge_type($noa_id, $data) {
    $this->db->where('noa_id', $noa_id);
    return $this->db->update('noa_requests', $data);
  }

  function db_get_healthcare_providers() {
    return $this->db->get('healthcare_providers')->result_array();
  }

  //INITIAL BILLING===================================================================
  var $initial1 = 'members';
  var $initial2 = 'initial_billing';
  var $initial3 = 'noa_requests';
  var $initial4 = 'billing';
  var $column_order_initial = ['member_id', 'first_name', 'emp_type', 'status', 'business_unit', 'dept_name']; 
  var $column_search_initial= ['member_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)', 'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $order_initial = ['emp_no' => 'desc']; // default order 

  private function _get_datatables_query_ledger($status) {
    $this->db->group_by('emp_no');
    $this->db->from($this->initial1 . ' as tbl_1');
    $this->db->join($this->initial2 . ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->join($this->initial3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
    $this->db->join($this->initial4 . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
    $this->db->where('tbl_2.status', $status);

    $i = 0;
    foreach ($this->column_search_initial as $item) {
      if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) { // check if search value is set and not empty
        if ($i === 0) {
          $this->db->group_start(); // start where clause group
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search_initial) - 1 == $i)
          $this->db->group_end(); // end where clause group
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $column_order_index = $_POST['order']['0']['column'];
      $column_order_dir = $_POST['order']['0']['dir'];
      $column_order = $this->column_order_initial[$column_order_index];
      $this->db->order_by($column_order, $column_order_dir);
    } else if (isset($this->order_initial)) {
      $this->db->order_by(key($this->order_initial), $this->order_initial[key($this->order_initial)]);
    }
  }

  function get_datatables_ledger($status) {
    $this->_get_datatables_query_ledger($status);
    if (isset($_POST['length']) && $_POST['length'] != -1) { 
      $this->db->limit($_POST['length'], $_POST['start']);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_all_ledger($status) {
    $this->db->from('initial_billing as tbl_2');
    $this->db->where('tbl_2.status', $status);
    return $this->db->count_all_results();
  }

  function count_filtered_ledger($status) {
    $this->_get_datatables_query_ledger($status);
    $query = $this->db->get();
  }
  public function get_member_info($emp_id) {
    $this->db->select('*');
    $this->db->from('initial_billing as tbl_1');
    $this->db->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
    $this->db->join('noa_requests as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
    $this->db->where('tbl_1.emp_id', $emp_id);
    $query = $this->db->get();
    return $query->result_array();

    $this->db->select('*');
    $this->db->from('members');
    $this->db->where('emp_id', $emp_id);

    $this->db->select('*');
    $this->db->from('max_benefit_limits');
    $this->db->where('emp_id', $emp_id);

    $this->db->select('*');
    $this->db->from('noa_requests');
    $this->db->where('emp_id', $emp_id);
  }

  // public function get_member_info($emp_id) {
  //   $this->db->select('*');
  //   $this->db->from('initial_billing as tbl_1');
  //   $this->db->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
  //   $this->db->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
  //   $this->db->join('noa_requests as tbl_4', 'tbl_1.noa_id = tbl_4.noa_id');
  //   $this->db->where('tbl_1.emp_id', $emp_id);
  //   $query = $this->db->get();
  //   return $query->result_array();
  // }

  // public function get_member_info($emp_id) {
  //   $this->db->select('*');
  //   $this->db->from('billing as tbl_1');
  //   $this->db->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
  //   $this->db->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
  //   $this->db->join('noa_requests as tbl_4', 'tbl_1.noa_id = tbl_4.noa_id');
  //   $this->db->where('tbl_1.emp_id', $emp_id);
  //   $query = $this->db->get();
  //   return $query->result_array();
  // }
  //END INITIAL BILLING==============================================================================

  //FINAL BILLING====================================================================================

  var $table1_final='noa_requests';
  var $table2_final='billing';
  var $table3_final='max_benefit_limits';
  var $table4_final='healthcare_providers';
  var $column_order_final=['noa_no', 'first_name','remaining_balance', 'net_bill'];
  var $column_search_final=['noa_no', 'first_name', 'middle_name', 'last_name', 'suffix','CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $order_final=['noa_no' => 'desc'];

  private function _get_final_datatables_query() {
    $this->db->select('tbl_1.status as tbl1_status, tbl_1.work_related as tbl1_work_related,tbl_1.request_date as tbl1_request_date, tbl_1.*, tbl_2.*, tbl_3.*,tbl_4.*');
    $this->db->from($this->table1_final . ' as tbl_1');
    $this->db->join($this->table2_final . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id', 'left');
    $this->db->join($this->table3_final . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id', 'left');
    $this->db->join($this->table4_final . ' as tbl_4', 'tbl_1.hospital_id = tbl_4.hp_id', 'left');
    // $this->db->where('tbl_1.status','Approved');
    // $this->db->or_where('tbl_1.status','Billed');
    $this->db->where_in('tbl_1.status', ['Approved', 'Billed']);

    // $i = 0;
    // if($this->input->post('filter')){
    //   $this->db->like('tbl_2.hp_id', $this->input->post('filter'));
    // }

    // if ($this->input->post('startDate')) {
    //   $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
    //   $this->db->where('tbl_2.request_date >=', $startDate);
    // }

    // if ($this->input->post('endDate')){
    //   $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
    //   $this->db->where('tbl_2.request_date <=', $endDate);
    // }

    $filter = $this->input->post('filter');
    if (!empty($filter)) {
      $this->db->like('tbl_1.hospital_id', $filter);
    }

    $startDate = $this->input->post('startDate');
    if (!empty($startDate)) {
        $startDate = date('Y-m-d', strtotime($startDate));
        $this->db->where('tbl_1.request_date >=', $startDate);
    }

    $endDate = $this->input->post('endDate');
    if (!empty($endDate)) {
        $endDate = date('Y-m-d', strtotime($endDate));
        $this->db->where('tbl_1.request_date <=', $endDate);
    }
  }

 
  function get_final_datatables() {
    // $this->_get_final_datatables_query();
    // if ($_POST['length'] != -1)
    // $this->db->limit($_POST['length'], $_POST['start']);
    // $query = $this->db->get();
    // return $query->result_array();
     $this->_get_final_datatables_query();
    $length = $this->input->post('length');
    $start = $this->input->post('start');

    if ($length != -1) {
        $this->db->limit($length, $start);
    }

    $query = $this->db->get();
    return $query->result_array();
  }
  //END==============================================================================================

    function get_total_hp_net_bill($hp_id, $start_date, $end_date) {
      $this->db->select_sum('net_bill')
                ->from('billing')
                ->where('status', 'Billed')
                ->where('hp_id', $hp_id)
                ->where('request_date >=', $start_date)
                ->where('request_date <=', $end_date)
                ->where('noa_id !=', '');
        $query = $this->db->get();
        $result = $query->result_array();
        $sum = $result[0]['net_bill'];
        return $sum;
    }

    function set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no) {
      $this->db->set('done_matching', '1')
              ->set('status', 'Payable')
              ->set('bill_no', $bill_no)
              ->where('status', 'Billed')
              ->where('hp_id', $hp_id)
              ->where('request_date >=', $start_date)
              ->where('request_date <=', $end_date)
              ->where('noa_id !=', '');
      return $this->db->update('billing');
    }
  
    function insert_for_payment_consolidated($data) {
      return $this->db->insert('monthly_payable', $data);
    }

    // function update_initial_billing() {
    //   $data = array('status' => 'Payable');
    //   $this->db->where('status', 'initial');
    //   return $this->db->update('initial_billing', $data);
    // }


    function update_noa_requests($hp_id, $start_date, $end_date) {
      $this->db->set('status', 'Payable')
            ->where('status', 'Billed')
            ->where('hospital_id', $hp_id)
            ->where('request_date >=', $start_date)
            ->where('request_date <=', $end_date);
      return $this->db->update('noa_requests');
    }

    function fetch_for_payment_bill() {
      $this->db->select('*')
              ->from('monthly_payable as tbl_1')
              ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
              ->where('type', 'NOA');
  
      if($this->input->post('filter')){
        $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
      }
  
      return $this->db->get()->result_array();
    }

    function fetch_monthly_billed_noa($bill_no) {
      $this->db->select('*')
              ->from('monthly_payable as tbl_1')
              ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
              ->where('bill_no', $bill_no);
      return $this->db->get()->row_array();
    }

    // Start of server-side processing datatables
var $table_1_monthly = 'billing';
var $table_2_monthly = 'noa_requests';
var $table_3_monthly = 'members';

private function _get_monthly_datatables_query($bill_no) {
  $this->db->from($this->table_1_monthly . ' as tbl_1');
  $this->db->join($this->table_2_monthly . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id');
  $this->db->join($this->table_3_monthly . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
  $this->db->where('tbl_1.bill_no', $bill_no);
  
}

function monthly_bill_datatable($bill_no) {
  $this->_get_monthly_datatables_query($bill_no);
  if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
  $query = $this->db->get();
  return $query->result_array();
}
// end datatable

function get_matched_total_hp_bill($bill_no) {
  $this->db->select_sum('net_bill')
            ->from('billing')
            ->where('bill_no', $bill_no);
    $query = $this->db->get();
    $result = $query->result_array();
    $sum = $result[0]['net_bill'];
    return $sum;
}

//billing for charging datatable
var $charging_table_1 = 'billing';
var $charging_table_2 = 'noa_requests';
var $charging_table_3 = 'members';
var $charging_table_4 = 'max_benefit_limits';
private function _get_datatables_charging_query($bill_no) {
  $this->db->from($this->charging_table_1 . ' as tbl_1')
          ->join($this->charging_table_2 . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id')
          ->join($this->charging_table_3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
          ->join($this->charging_table_4 . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
          ->where('tbl_1.bill_no', $bill_no);
}

function get_billed_for_charging($bill_no) {
  $this->_get_datatables_charging_query($bill_no);
  if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
  $query = $this->db->get();
  return $query->result_array();
}
//end billing for charging datatable

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
public function bar_billed(){
  $query = $this->db->query("SELECT status FROM loa_requests WHERE status='Billed' ");
  return $query->num_rows(); 
} 
public function bar_pending_noa(){
  $query = $this->db->query("SELECT status FROM noa_requests WHERE status='Pending' ");
  return $query->num_rows(); 
} 
public function bar_approved_noa(){
  $query = $this->db->query("SELECT status FROM noa_requests WHERE status='Approved' ");
  return $query->num_rows(); 
} 
public function bar_initial_noa(){
  $query = $this->db->query("SELECT status FROM initial_billing WHERE status='Initial' ");
  return $query->num_rows(); 
} 
public function bar_billed_noa(){
  $query = $this->db->query("SELECT status FROM noa_requests WHERE status='Billed' ");
  return $query->num_rows(); 
} 
//End =================================================

//BILLING STATEMENT=====================================
public function processing(){
  $query = $this->db->query("SELECT status FROM billing WHERE status='Payable'");
  $result = $query->row_array();
    
  if ($result && $result['status'] === 'Payable') {
    return 'Processing...';
  }
  return '';
}
//End =================================================

  // Gurantee Letter query
  function db_get_data_for_gurantee($noa_id) {
    $this->db->select('*')
            ->from('noa_requests as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('healthcare_providers as tbl_3', 'tbl_1.hospital_id = tbl_3.hp_id')
            ->join('company_doctors as tbl_4', 'tbl_1.approved_by = tbl_4.doctor_id')
            ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id= tbl_5.emp_id')
            ->join('billing as tbl_6', 'tbl_1.noa_id= tbl_6.noa_id')
            ->where('tbl_1.noa_id', $noa_id);
    return $this->db->get()->row_array();
  }

  // function db_get_doctor_by_id($doctor_id) {
  //   $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
  //   return $query->row_array();
  // }
  //End

  function db_update_letter($billing_id, $guarantee_letter, $upload_on) {
    $this->db->where('billing_id', $billing_id)
            ->set('guarantee_letter', $guarantee_letter)
            ->set('guarantee_uploaded_on', $upload_on);
    return $this->db->update('billing');
  }

  function count_all_generated_guarantee_letter() {
    $this->db->from('billing');
    $this->db->where('guarantee_letter !=', null);
    return $this->db->get()->num_rows();
  }

}
