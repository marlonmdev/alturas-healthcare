<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noa_model extends CI_Model{

     // Start of server-side processing datatables
    var $table_1 = 'noa_requests';
    var $table_2 = 'healthcare_providers';
    var $column_order = ['tbl_1.noa_no', 'tbl_1.first_name', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date']; //set column field database for datatable orderable
    var $column_search = ['tbl_1.noa_no', 'tbl_1.emp_id', 'tbl_1.health_card_no', 'tbl_1.first_name', 'tbl_1.middle_name', 'tbl_1.last_name', 'tbl_1.suffix', 'tbl_1.admission_date', 'tbl_2.hp_name', 'tbl_1.request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
    var $order = ['tbl_1.noa_id' => 'desc']; // default order 

    private function _get_datatables_query($status, $hp_id) {
        $this->db->from($this->table_1 . ' as tbl_1');
        $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id');
        $this->db->where('tbl_1.status', $status);
        $this->db->where('hospital_id', $hp_id);
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
                ->where('hospital_id', $hp_id);
        return $this->db->count_all_results();
    }
    // End of server-side processing datatables

    function fetch_pending_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Pending');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_approved_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Approved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_disapproved_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Disapproved');
        $query = $this->db->get();
        return $query->result();
    }

    function fetch_closed_noa_requests($hp_id){
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->where('tbl_1.hospital_id', $hp_id)
                 ->where('tbl_1.status', 'Closed');
        $query = $this->db->get();
        return $query->result();
    }

    // function db_get_noa_info($noa_id) {
    //     $this->db->select('*')
    //             ->from('noa_requests as tbl_1')
    //             ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
    //             ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
    //             ->where('tbl_1.noa_id', $noa_id);
    //     return $this->db->get()->row_array();
    // }

    function db_get_noa_info($noa_id) {
        $this->db->select('*')
                 ->from('noa_requests as tbl_1')
                 ->join('healthcare_providers as tbl_2', 'tbl_1.hospital_id = tbl_2.hp_id')
                 ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
                 ->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
                 ->where('tbl_1.noa_id', $noa_id);
        return $this->db->get()->row_array();
      }

    function db_get_doctor_name_by_id($doctor_id) {
        $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
        return $query->row_array();
    }
    function fetch_initial_bill($hp_id,$emp_id){
        $this->db->select('*')
                 ->from('initial_billing')
                 ->where('hp_id', $hp_id)
                 ->where('emp_id', $emp_id)
                 ->where('status', 'Initial');
        $query = $this->db->get();
        return $query->result();
    }
  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }
  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }
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
  function get_noa_history($hp_id,$emp_id){
    $this->db->select('*')
    ->from('noa_requests')
    ->where('hospital_id', $hp_id)
    ->where('emp_id', $emp_id);
    $query = $this->db->get();
    return $query->result();
}
}
