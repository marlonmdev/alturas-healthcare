<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model {

  // Start of server-side processing datatables
  var $table_1 = 'loa_requests';
  var $table_2 = 'healthcare_providers';
  var $table_3 = 'max_benefit_limits';
  var $column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  var $column_search = ['loa_no', 'emp_id', 'health_card_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['loa_id' => 'desc']; // default order 

  private function _get_datatables_query($status) {
    $this->db->from($this->table_1 . ' as tbl_1');
    $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->join($this->table_3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
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

    // Start of billed server-side processing datatables
    var $table_1_billed = 'loa_requests';
    var $table_2_billed = 'healthcare_providers';
    var $table_3_billed = 'max_benefit_limits';
    var $column_order_billed = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
    var $column_search_billed = ['loa_no', 'emp_id', 'health_card_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
    var $order_billed = ['loa_id' => 'desc']; // default order 
  
    private function _get_datatables_query_billed($status) {
      $this->db->from($this->table_1_billed . ' as tbl_1');
      $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
      $this->db->join($this->table_3_billed . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
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
      $this->_get_datatables_query_billed($status);
      if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result_array();
    }
  
    function count_filtered_billed($status) {
      $this->_get_datatables_query_billed($status);
      $query = $this->db->get();
      return $query->num_rows();
    }
  
    function count_all_billed($status) {
      $this->db->from($this->table_1_billed)
               ->where_in('status', $status);
      return $this->db->count_all_results();
    }
    // End of server-side processing datatables

  function get_estimated_total_fee($cost_types) {
    $this->db->select('*')
            ->from('cost_types')
            ->where('ctype_id', $cost_types);
    return $this->db->get()->result_array();
  }

  function db_insert_loa_med_services($post_data) {
    return $this->db->insert('loa_cost_items', $post_data);
  }

  function db_get_cost_types() {
    $query = $this->db->get('cost_types');
    return $query->result_array();
  }

  function get_loa_info_by_id($loa_id) {
    return $this->db->get_where('loa_requests', ['loa_id' => $loa_id])->row_array();
  }

  function db_get_all_pending_loa() {
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->where('tbl_1.status', 'Pending')
            ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_approved_loa() {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_disapproved_loa() {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Disapproved')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_approved_loa($user_id) {
    $this->db->select('loa_id, loa_no, name_of_hospital, loa_request_type, availment_request_date, status, checked_by');
    $query = $this->db->get_where('loa_requests', ['status' => 'Approved', 'user_id' => $user_id]);
    return $query->result_array();
  }

  function db_get_disapproved_loa($user_id) {
    $this->db->select('loa_id, loa_no, name_of_hospital, loa_request_type, availment_request_date, status, checked_by');
    $query = $this->db->get_where('loa_requests', ['status' => 'Disapproved', 'user_id' => $user_id]);
    return $query->result_array();
  }


  function db_get_loa_info($loa_id) {
    $this->db->select('*')
             ->from('loa_requests as tbl_1')
             ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
             ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
             ->join('max_benefit_limits as tbl_4', 'tbl_1.emp_id= tbl_4.emp_id')
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function get_bill_info($loa_id) {
      return $this->db->get_where('billing', ['loa_id' => $loa_id])->row_array();
  }

  function get_paid_date($details_no) {
    return $this->db->get_where('payment_details', ['details_no' => $details_no])->row_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }


  function db_get_requesting_physician($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_get_doctor_name_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_approve_loa_request($loa_id, $data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  function db_disapprove_loa_request($loa_id, $disapproved_by, $disapprove_reason, $disapproved_on) {
    $data = array(
      'status' => 'Disapproved',
      'disapproved_by' => $disapproved_by,
      'disapprove_reason' => $disapprove_reason,
      'disapproved_on' => $disapproved_on
    );
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

    // Start of cancellation_requests server-side processing datatables
    var $table1 = 'loa_cancellation_requests';
    var $table2 = 'members';
    var $columnOrder = ['loa_no', 'first_name', null, 'tbl_1.confirmed_on',  'status', null, null]; //set column field database for datatable orderable
    var $columnSearch = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'requested_on', 'status']; //set column field database for datatable searchable 
    var $order1 = ['loa_id' => 'desc']; // default order 

    private function _get_cancell_datatables_query($status) {
      $this->db->from($this->table1 . ' as tbl_1');
      $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.requested_by = tbl_2.emp_id');
      $this->db->where('tbl_1.status', $status);
      $i = 0;

      if($this->input->post('filter')){
        $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
      } 
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

    function get_cancel_datatables($status) {
        $this->_get_cancell_datatables_query($status);
        if ($_POST['length'] != -1)
          $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_cancell_filtered($status) {
        $this->_get_cancell_datatables_query($status);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_cancell($status) {
        $this->db->from($this->table1)
                ->where('status', $status);
        return $this->db->count_all_results();
    }
    // End of server-side processing datatables

    function db_get_healthcare_providers() {
        return $this->db->get('healthcare_providers')->result_array();
    }

    function db_get_company_doctors() {
        $query = $this->db->get('company_doctors');
        return $query->result_array();
    }

    function db_update_loa_request($loa_id, $post_data) {
        $this->db->where('loa_id', $loa_id);
        return $this->db->update('loa_requests', $post_data);
    }

    function get_autocomplete($search_data) {
        $this->db->select('tbl_1.member_id, tbl_1.emp_id, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix')
                ->from('members as tbl_1')
                ->join('max_benefit_limits as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                ->like('tbl_1.first_name', $search_data)
                ->or_like('tbl_1.middle_name', $search_data)
                ->or_like('tbl_1.last_name', $search_data)
                ->or_like('tbl_1.suffix', $search_data)
                ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.last_name)', $search_data)
                ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name)', $search_data)
                ->or_like('CONCAT(tbl_1.first_name, " ",tbl_1.middle_name, " ", tbl_1.last_name, " ", tbl_1.suffix)', $search_data)
                ->group_start()
                    ->where('tbl_1.approval_status', 'Approved')
                    ->or_where('tbl_1.approval_status', 'Done')
                ->group_end()
                ->where('tbl_2.remaining_balance', 0);
        return $this->db->get()->result_array();
    }

    function db_get_member_details($member_id) {
        $this->db->select('*')
                ->from('members as tbl_1')
                ->join('max_benefit_limits as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                ->where('tbl_1.member_id', $member_id);
        return $this->db->get()->row_array();
            
    }

    function db_get_cost_types_by_hp($hp_id) {
        $query = $this->db->get_where('cost_types', ['hp_id' => $hp_id]);
        return $query->result_array();
    }

    function db_check_healthcare_provider_exist($hp_id) {
        $this->db->where('hp_id', $hp_id);
        $query = $this->db->get('healthcare_providers');
        return $query->num_rows() > 0 ? true : false;
    }

    function db_get_max_loa_id() {
        $this->db->select_max('loa_id');
        $query = $this->db->get('loa_requests');
        return $query->row_array();
    }

    function db_get_member_infos($emp_id){
        $this->db->where('emp_id', $emp_id);
        $query = $this->db->get('members');
        return $query->num_rows() > 0 ? $query->row_array() : false;
    }

    function db_insert_loa_request($post_data) {
        $query = $this->db->insert('loa_requests', $post_data);
        return $query ? $this->db->insert_id() : false;
    }

    function get_billing_by_emp_id ($emp_id){
      $this->db->select('*')
      ->where('emp_id', $emp_id)
      ->where('YEAR(billed_on)', date('Y'))
      ->order_by('billing_id', 'desc')
      ->limit(1);
    
      $query = $this->db->get('billing');
      return $query->row_array();
    }
    
    function db_get_max_billing_id() {
      $this->db->select_max('billing_id');
      $query = $this->db->get('billing');
      return $query->row_array();
    }
    
    function insert_billing($data) {
      return $this->db->insert('billing', $data);
    }
    function update_member_remaining_balance($emp_id, $data) {
      $this->db->where('emp_id', $emp_id);
      return $this->db->update('max_benefit_limits', $data); 
    }
    public function get_count_pending()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('loa_requests');
        $this->db->where('status', 'Pending');
        $query = $this->db->get();
        $result= $query->row();
        $count = $result->count;
        
        return $count;
    }
}
