<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Loa_model extends CI_Model {

  // Start of server-side processing datatables
  // var $table_1 = 'loa_requests';
  // var $table_2 = 'healthcare_providers';
  // var $column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  // var $column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  // var $order = ['loa_id' => 'desc']; // default order 

  // private function _get_datatables_query($status) {
  //   $this->db->from($this->table_1 . ' as tbl_1');
  //   $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
  //   $this->db->where('status', $status);
  //   $i = 0;

  //   if($this->input->post('filter')){
  //     $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
  //   }
  //   //loop column 
  //   foreach ($this->column_search as $item) {
  //     // if datatable send POST for search
  //     if ($_POST['search']['value']) {
  //       // first loop
  //       if ($i === 0) {
  //         $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
  //         $this->db->like($item, $_POST['search']['value']);
  //       } else {
  //         $this->db->or_like($item, $_POST['search']['value']);
  //       }

  //       if (count($this->column_search) - 1 == $i) //last loop
  //         $this->db->group_end(); //close bracket
  //     }
  //     $i++;
  //   }

  //   // here order processing
  //   if (isset($_POST['order'])) {
  //     $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
  //   } else if (isset($this->order)) {
  //     $order = $this->order;
  //     $this->db->order_by(key($order), $order[key($order)]);
  //   }
  // }

  // function get_datatables($status) {
  //   $this->_get_datatables_query($status);
  //   if ($_POST['length'] != -1)
  //     $this->db->limit($_POST['length'], $_POST['start']);
  //   $query = $this->db->get();
  //   return $query->result_array();
  // }

  // function count_filtered($status) {
  //   $this->_get_datatables_query($status);
  //   $query = $this->db->get();
  //   return $query->num_rows();
  // }

  // function count_all($status) {
  //   $this->db->from($this->table_1)
  //            ->where('status', $status);
  //   return $this->db->count_all_results();
  // }
  // End of server-side processing datatables

  //==================================================
  //LETTER OF AUTHORIZATION
  //PENDING
  //==================================================
  var $pending_table_1 = 'loa_requests';
  var $pending_table_2 = 'healthcare_providers';
  var $pending_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $pending_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $pending_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_pending($status) {
    $this->db->from($this->pending_table_1 . ' as tbl_1');
    $this->db->join($this->pending_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->pending_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
      if (count($this->pending_column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->pending_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->pending_order)) {
      $order = $this->pending_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_pending($status) {
    $this->_get_datatables_query_pending($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_pending($status) {
    $this->_get_datatables_query_pending($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_pending($status) {
    $this->db->from($this->pending_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //LETTER OF AUTHORIZATION
  //APPROVED
  //==================================================
  // var $table_1 = 'loa_requests';
  // var $table_2 = 'healthcare_providers';
  // var $column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  // var $column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  // var $order = ['loa_id' => 'desc']; // default order 

  // private function _get_datatables_query() {
  //   $this->db->from($this->table_1 . ' as tbl_1');
  //   $this->db->join($this->table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
  //   $this->db->group_start()
  //     ->where('tbl_1.performed_fees', 'Approved')
  //     ->or_where('tbl_1.status', 'Approved')
  //     ->group_end();
      
  //   $i = 0;
  //   if($this->input->post('filter')){
  //     $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
  //   }
  //   //loop column 
  //   foreach ($this->column_search as $item) {
  //     // if datatable send POST for search
  //     if ($_POST['search']['value']) {
  //       // first loop
  //       if ($i === 0) {
  //         $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
  //         $this->db->like($item, $_POST['search']['value']);
  //       } else {
  //         $this->db->or_like($item, $_POST['search']['value']);
  //       }

  //       if (count($this->column_search) - 1 == $i) //last loop
  //         $this->db->group_end(); //close bracket
  //     }
  //     $i++;
  //   }

  //   // here order processing
  //   if (isset($_POST['order'])) {
  //     $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
  //   } else if (isset($this->order)) {
  //     $order = $this->order;
  //     $this->db->order_by(key($order), $order[key($order)]);
  //   }
  // }

  // function get_datatables() {
  //   $this->_get_datatables_query();
  //   if ($_POST['length'] != -1)
  //     $this->db->limit($_POST['length'], $_POST['start']);
  //   $query = $this->db->get();
  //   return $query->result_array();
  // }

  // function count_filtered() {
  //   $this->_get_datatables_query();
  //   $query = $this->db->get();
  //   return $query->num_rows();
  // }

  // function count_all() {
  //   $this->db->from($this->table_1)
  //            ->where('status', 'Approved');
  //   return $this->db->count_all_results();
  // }

  var $approved_table_1 = 'loa_requests';
  var $approved_table_2 = 'healthcare_providers';
  var $approved_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; 
  var $approved_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; 
  var $approved_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_approved($status) {
    $this->db->from($this->approved_table_1 . ' as tbl_1');
    $this->db->join($this->approved_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->approved_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start(); 
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->approved_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->approved_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->approved_order)) {
      $order = $this->approved_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_approved($status) {
    $this->_get_datatables_query_approved($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_approved($status) {
    $this->_get_datatables_query_approved($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_approved($status) {
    $this->db->from($this->approved_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //LETTER OF AUTHORIZATION
  //DISAPPROVED
  //==================================================
  var $disapproved_table_1 = 'loa_requests';
  var $disapproved_table_2 = 'healthcare_providers';
  var $disapproved_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $disapproved_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $disapproved_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_disapproved($status) {
    $this->db->from($this->disapproved_table_1 . ' as tbl_1');
    $this->db->join($this->disapproved_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->disapproved_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
      if (count($this->disapproved_column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->disapproved_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->disapproved_order)) {
      $order = $this->disapproved_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_disapproved($status) {
    $this->_get_datatables_query_disapproved($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_disapproved($status) {
    $this->_get_datatables_query_pending($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_disapproved($status) {
    $this->db->from($this->pending_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //LETTER OF AUTHORIZATION
  //REFERRAL
  //==================================================
  var $referral_table_1 = 'loa_requests';
  var $referral_table_2 = 'healthcare_providers';
  var $referral_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $referral_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $referral_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_referral($status) {
    $this->db->from($this->referral_table_1 . ' as tbl_1');
    $this->db->join($this->referral_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->referral_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
      if (count($this->referral_column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->referral_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->referral_order)) {
      $order = $this->referral_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_referral($status) {
    $this->_get_datatables_query_referral($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_referral($status) {
    $this->_get_datatables_query_referral($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_referral($status) {
    $this->db->from($this->referral_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //LETTER OF AUTHORIZATION
  //EXPIRED
  //==================================================
  var $expired_table_1 = 'loa_requests';
  var $expired_table_2 = 'healthcare_providers';
  var $expired_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $expired_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $expired_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_expired($status) {
    $this->db->from($this->expired_table_1 . ' as tbl_1');
    $this->db->join($this->expired_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->expired_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
      if (count($this->expired_column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->expired_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->expired_order)) {
      $order = $this->expired_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_expired($status) {
    $this->_get_datatables_query_expired($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_expired($status) {
    $this->_get_datatables_query_expired($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_expired($status) {
    $this->db->from($this->expired_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

  //==================================================
  //LETTER OF AUTHORIZATION
  //CANCELLED
  //==================================================
  var $cancelled_table_1 = 'loa_requests';
  var $cancelled_table_2 = 'healthcare_providers';
  var $cancelled_column_order = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $cancelled_column_search = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $cancelled_order = ['loa_id' => 'asc'];

  private function _get_datatables_query_cancelled($status) {
    $this->db->from($this->cancelled_table_1 . ' as tbl_1');
    $this->db->join($this->cancelled_table_2 . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
    $this->db->where('status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
    }

    foreach ($this->cancelled_column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
      if (count($this->cancelled_column_search) - 1 == $i)
        $this->db->group_end();
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $this->db->order_by($this->cancelled_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->cancelled_order)) {
      $order = $this->cancelled_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_cancelled($status) {
    $this->_get_datatables_query_cancelled($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_filtered_cancelled($status) {
    $this->_get_datatables_query_cancelled($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_cancelled($status) {
    $this->db->from($this->cancelled_table_1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  //==================================================
  //END
  //==================================================

   // Start of server-side processing completed datatables
   var $table_1_c = 'loa_requests';
   var $table_2_c = 'healthcare_providers';
   var $column_order_c = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
   var $column_search_c = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
   var $order_c = ['loa_id' => 'asc']; // default order 
 
   private function _get_datatables_query_c() {
     $this->db->from($this->table_1_c . ' as tbl_1');
     $this->db->join($this->table_2_c . ' as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id');
     $this->db->where('completed', '1');
     $i = 0;
 
     if($this->input->post('filter')){
       $this->db->like('tbl_1.hcare_provider', $this->input->post('filter'));
     }
     // loop column 
     foreach ($this->column_search_c as $item) {
       // if datatable send POST for search
       if ($_POST['search']['value']) {
         // first loop
         if ($i === 0) {
           $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
           $this->db->like($item, $_POST['search']['value']);
         } else {
           $this->db->or_like($item, $_POST['search']['value']);
         }
 
         if (count($this->column_search_c) - 1 == $i) //last loop
           $this->db->group_end(); //close bracket
       }
       $i++;
     }
 
     // here order processing
     if (isset($_POST['order'])) {
       $this->db->order_by($this->column_order_c[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
     } else if (isset($this->order_c)) {
       $order = $this->order_c;
       $this->db->order_by(key($order), $order[key($order)]);
     }
   }
 
   function get_completed_datatables() {
     $this->_get_datatables_query_c();
     if ($_POST['length'] != -1)
       $this->db->limit($_POST['length'], $_POST['start']);
     $query = $this->db->get();
     return $query->result_array();
   }
 
   function count_filtered_c() {
     $this->_get_datatables_query_c();
     $query = $this->db->get();
     return $query->num_rows();
   }
 
   function count_all_c() {
     $this->db->from($this->table_1_c)
              ->where('completed', '1');
     return $this->db->count_all_results();
   }

   // Start of server-side processing datatables
   var $table_1_charged = 'billing';
   var $table_2_charged = 'loa_requests';
   var $table_3_charged = 'healthcare_providers';
   var $table_4_charged = 'members';
   var $column_order_charged = ['tbl_2.loa_no', 'tbl_4.first_name', 'tbl_2.loa_request_type', 'tbl_3.hp_id', NULL, NULL]; //set column field database for datatable orderable
   var $column_search_charged = ['tbl_2.loa_no', 'tbl_4.first_name', 'tbl_4.middle_name', 'tbl_4.last_name', 'tbl_4.suffix', 'tbl_2.loa_request_type', 'tbl_2.med_services', 'tbl_1.emp_id', 'tbl_2.health_card_no', 'tbl_3.hp_name', 'CONCAT(tbl_4.first_name, " ",tbl_4.last_name)', 'CONCAT(tbl_4.first_name, " ",tbl_4.last_name, " ", tbl_4.suffix)', 'CONCAT(tbl_4.first_name, " ",tbl_4.middle_name, " ",tbl_4.last_name)', 'CONCAT(tbl_4.first_name, " ",tbl_4.middle_name, " ",tbl_4.last_name, " ", tbl_4.suffix)']; //set column field database for datatable searchable 
   var $order_charged = ['tbl_1.loa_id' => 'asc']; // default order 
 
   private function _get_charged_datatables_query($status) {
     $this->db->from($this->table_1_charged . ' as tbl_1');
     $this->db->join($this->table_2_charged . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
     $this->db->join($this->table_3_charged . ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
     $this->db->join($this->table_4_charged . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
     $this->db->where('tbl_1.status', $status);
     $this->db->where('tbl_1.done_matching', '1');
     $i = 0;
     
     if($this->input->post('filter')){
       $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
     }

     // loop column 
     foreach ($this->column_search_charged as $item) {
       // if datatable send POST for search
       if ($_POST['search']['value']) {
         // first loop
         if ($i === 0) {
           $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
           $this->db->like($item, $_POST['search']['value']);
         } else {
           $this->db->or_like($item, $_POST['search']['value']);
         }
 
         if (count($this->column_search_charged) - 1 == $i) //last loop
           $this->db->group_end(); //close bracket
       }
       $i++;
     }
 
     // here order processing
     if (isset($_POST['order'])) {
       $this->db->order_by($this->column_order_charged[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
     } else if (isset($this->order_charged)) {
       $order = $this->order_charged;
       $this->db->order_by(key($order), $order[key($order)]);
     }
   }
 
   function get_charged_datatables($status) {
     $this->_get_charged_datatables_query($status);
     if ($_POST['length'] != -1)
       $this->db->limit($_POST['length'], $_POST['start']);
     $query = $this->db->get();
     return $query->result_array();
   }
 
   function count_filtered_charged($status) {
     $this->_get_charged_datatables_query($status);
     $query = $this->db->get();
     return $query->num_rows();
   }
 
   function count_all_charged($status) {
     $this->db->from($this->table_1_charged)
              ->where('status', $status);
     return $this->db->count_all_results();
   }
   // End of server-side processing datatables

  function db_get_max_loa_id() {
    $this->db->select_max('loa_id');
    $query = $this->db->get('loa_requests');
    return $query->row_array();
  }

  function db_insert_loa_request($post_data) {
    return $this->db->insert('loa_requests', $post_data);
  }

  function db_update_loa_request($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $post_data);
  }

  function db_update_billing($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id)
            ->set('reason_adjustment', $post_data)
            ->set('re_upload', '1');
    return $this->db->update('billing');
  }

  function db_update_letter($billing_id, $guarantee_letter, $upload_on) {
    $this->db->where('billing_id', $billing_id)
            ->set('guarantee_letter', $guarantee_letter)
            ->set('guarantee_uploaded_on', $upload_on);
    return $this->db->update('billing');
  }

  function db_cancel_loa($loa_id) {
    $this->db->where('loa_id', $loa_id)
             ->delete('loa_requests');
    return $this->db->affected_rows() > 0 ? true : false;
  }

  function db_get_cost_types() {
    $query = $this->db->get('cost_types');
    return $query->result_array();
  }

  

function db_get_cost_types_by_hp_ID($hp_id) {
  $this->db->select('*')
             ->from('cost_types')
             ->where('hp_id', $hp_id);
    $query = $this->db->get();
    return $query->result_array();
}

  function db_get_healthcare_providers() {
    $query = $this->db->get('healthcare_providers');
    return $query->result_array();
  }

  function month() {
    $query = $this->db->get('monthly_payable');
    return $query->result_array();
  }

  function insert_added_loa_fees($post_data) {
    return $this->db->insert('hr_added_loa_fees', $post_data);
  }

  function db_get_company_doctors() {
    $query = $this->db->get('company_doctors');
    return $query->result_array();
  }

  function db_get_loa_attach_filename($loa_id) {
    $this->db->select('loa_id, rx_file')
             ->where('loa_id', $loa_id);
    return $this->db->get('loa_requests')->row_array();
  }

  function db_get_member_mbl($emp_id){
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }


  function db_get_all_pending_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status, tbl_1.requested_by')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Pending')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  function db_get_all_approved_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status')
             ->from('loa_requests as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
             ->where('tbl_1.status', 'Approved')
             ->order_by('loa_id', 'DESC');
    return $this->db->get()->result_array();
  }

  // function get_all_approved_loa($loa_id){
  //   $this->db->select('*')
  //           ->from('loa_requests as tbl_1')
  //           ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
  //           ->where('tbl_1.status', 'Approved')
  //           ->where('tbl_1.loa_id', $loa_id)
  //           ->order_by('loa_id', 'DESC');
  //   return $this->db->get()->row_array();
  // }
  function get_all_approved_loa($loa_id){

    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->where('tbl_1.loa_id', $loa_id)
            // ->where('tbl_1.status', 'Approved')
            ->where('tbl_1.performed_fees', 'Approved');
            // ->where('tbl_1.completed', 0)
    return $this->db->get()->row_array();
  }

  function get_all_resched_loa($loa_id) {
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->where('tbl_1.status', 'Referred')
            ->where('tbl_1.loa_id', $loa_id)
            ->order_by('loa_id', 'DESC');
    return $this->db->get()->row_array();
  }

  function db_get_all_disapproved_loa() {
    $this->db->select('tbl_1.loa_id, tbl_1.loa_no, tbl_1.first_name, tbl_1.middle_name, tbl_1.last_name, tbl_1.suffix, tbl_2.hp_name, tbl_1.loa_request_type, tbl_1.med_services, tbl_1.request_date, tbl_1.rx_file, tbl_1.status')
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
             ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_get_loa($loa_id) {
    $this->db->select('med_services')
            ->from('loa_requests')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  } 

  function db_get_loa_detail($loa_id) {
        $this->db->select('*')
                 ->from('loa_requests as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
                 ->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id')
                 ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id= tbl_5.emp_id')
                 ->where('tbl_1.loa_id', $loa_id);
        return $this->db->get()->row_array();
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

  function view_history($loa_id, $noa_id) {
    $this->db->select('*')
      ->from('billing as tbl_1')
      ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
      ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
      ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id');

    if (!empty($loa_id)) {
      $this->db->join('loa_requests as loa', 'tbl_1.loa_id = loa.loa_id', 'left');
      $this->db->where('tbl_1.loa_id', $loa_id);
    } elseif (!empty($noa_id)) {
      $this->db->join('noa_requests as noa', 'tbl_1.noa_id = noa.noa_id', 'left');
      $this->db->where('tbl_1.noa_id', $noa_id);
    }
    return $this->db->get()->row_array();
  }

  function db_get_resched_loa_details($loa_id) {
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('healthcare_providers as tbl_3', 'tbl_1.hcare_provider = tbl_3.hp_id')
            ->join('company_doctors as tbl_4', 'tbl_1.requesting_physician = tbl_4.doctor_id')
            ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id= tbl_5.emp_id')
            ->join('company_doctors as tbl_6', 'tbl_1.approved_by = tbl_6.doctor_id')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function db_approve_loa_request($loa_id, $approved_by) {
    $data = [
      'status' => 'Approved',
      'approved_by' => $approved_by,
      'approved_on' => date('Y-m-d'),
    ];
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  function db_disapprove_loa_request($loa_id, $disapproved_by) {
    $data = [
      'status' => 'Disapproved',
      'disapproved_by' => $disapproved_by,
      'disapproved_on' => date('Y-m-d'),
    ];
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  function db_get_doctor_by_id($doctor_id) {
    $query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
    return $query->row_array();
  }

  function db_update_loa_charge_type($loa_id, $data) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('loa_requests', $data);
  }

  // Start of cancellation_requests server-side processing datatables
  var $table1 = 'loa_cancellation_requests';
  var $table2 = 'members';
  var $table3 = 'healthcare_providers';
  var $columnOrder = ['loa_no', 'first_name', 'requested_on', 'hp_name', null, 'status', null]; //set column field database for datatable orderable
  var $columnSearch = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'requested_on', 'status', 'tbl_1.hp_id', 'hp_name']; //set column field database for datatable searchable 
  var $order1 = ['loa_id' => 'desc']; // default order 

  private function _get_cancel_datatables_query($status) {
    $this->db->from($this->table1 . ' as tbl_1');
    $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.requested_by = tbl_2.emp_id');
    $this->db->join($this->table3 . ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
    $this->db->where('tbl_1.status', $status);
    $i = 0;

    if($this->input->post('filter')){
      $this->db->like('hp_id', $this->input->post('filter'));
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
    $this->_get_cancel_datatables_query($status);
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_cancel_filtered($status) {
    $this->_get_cancel_datatables_query($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_cancel($status) {
    $this->db->from($this->table1)
             ->where('status', $status);
    return $this->db->count_all_results();
  }
  // End of server-side processing datatables

  function set_cancel_approved($loa_id, $confirm_by, $confirmed_on) {
    $this->db->set('status', 'Approved')
            ->set('confirmed_by', $confirm_by)
            ->set('confirmed_on', $confirmed_on)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_cancellation_requests');
  }

  function set_cancel_disapproved($loa_id, $disapproved_by, $disapproved_on) {
    $this->db->set('status', 'Disapproved')
            ->set('disapproved_by', $disapproved_by)
            ->set('disapproved_on', $disapproved_on)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_cancellation_requests');
  }

  function set_cloa_request_status($loa_id, $status) {
    $this->db->set('status', $status)
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_requests');
  }

  function insert_performed_loa_info($post_data) {
    return $this->db->insert_batch('performed_loa_info', $post_data);
  }

  function insert_edited_performed_loa_info($post_data, $loa_id) {
    $this->db->where('loa_id', $loa_id);

    if (!empty($post_data)) {
        return $this->db->update_batch('performed_loa_info', $post_data, 'ctype_id');
    } else {
        // If $post_data is empty, return true to indicate that the update succeeded.
        return true;
    }
  }

  function update_consulation_loa_info($post_data, $loa_id) {
    $this->db->where('loa_id', $loa_id);
    return $this->db->update('performed_loa_info', $post_data);
  }

  function insert_performed_loa_consult($post_data) {
    return $this->db->insert('performed_loa_info', $post_data);
  }

  function check_loa_no($loa_id) {
    $query = $this->db->get_where('performed_loa_info', ['loa_id' => $loa_id]);
    return $query->num_rows() > 0 ? true : false;
  }

  function get_performed_loa_data($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }

  function get_consultation_data($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }      

  function fetch_per_loa_info($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  }

  function fetch_per_consult_loa_info($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function check_if_all_status_performed($loa_id) {
    $field_name = 'status';
    $this->db->distinct()
            ->select($field_name)
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id);
    $query = $this->db->get();

    $results = $query->result();

    if (count($results) === 1 && $results[0]->$field_name === 'Performed') {
      return true;
    }
  }

  function check_if_status_empty($loa_id) {
    $this->db->select('status')
            ->where('status', '')
            ->where('loa_id', $loa_id)
            ->group_by('loa_id');
    $query = $this->db->get('performed_loa_info');

    if ($query->num_rows() > 0) {
      return true;
    }else{
      return false;
    }
  }

  function check_if_service_cancelled($loa_id) {
    $this->db->select('status')
            ->where('status', 'Cancelled')
            ->where('loa_id', $loa_id)
            ->group_by('loa_id');
    $query = $this->db->get('performed_loa_info');

    if ($query->num_rows() > 0) {
      return true;
    }else{
      return false;
    }
  }

  function get_cancelled_service($loa_id) {
    $this->db->select('*')
            ->from('performed_loa_info')
            ->where('loa_id', $loa_id)
            ->where('status', 'Cancelled');
    return $this->db->get()->result_array();
  }

  function set_loa_status_completed($loa_id, $status) {
    $this->db->set('status', $status)
            ->set('completed', '1')
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }


  function update_performed_fees($loa_id) {
    $this->db->set('performed_fees', 'Performed')
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }
  function update_performed_fees1($loa_id) {
    $this->db->set('performed_fees', 'Processing')
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function _set_loa_status_completed($loa_id) {
    $this->db->set('completed', '')
            ->where('status', 'Billed')
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function update_performed_fees_processing($loa_id) {
    $this->db->set('performed_fees', 'Processing')
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function db_update_loa_med_services($loa_id, $new_field_value){
    $this->db->set('med_services', $new_field_value)
            ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function get_all_completed_loa($loa_id){
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->join('max_benefit_limits as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
            ->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
            ->join('billing as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function get_loa_information($emp_id,$request_date) {
    $this->db->select('*')
            ->from('loa_requests')
            ->where('emp_id',$emp_id)
            ->where('request_date',$request_date)
            ->where('status','Billed');
    return $this->db->get()->row_array();
  }


  

  function check_if_status_cancelled($loa_id) {
    $this->db->select('status')
            ->where('status', 'Referred')
            ->where('loa_id', $loa_id)
            ->group_by('loa_id');
    $query = $this->db->get('performed_loa_info');

    if ($query->num_rows() > 0) {
    return true;
    }else{
    return false;
    }
  }

  function get_rescheduled_services($loa_id, $hp_id) {
    $this->db->select('*')
            ->from('performed_loa_info as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('tbl_1.loa_id', $loa_id)
            ->where('tbl_2.hp_id', $hp_id)
            ->where('tbl_1.status', 'Referred');
    return $this->db->get()->result_array();
  }

  function get_rescheduled_loa_info($loa_id) {
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function get_loa_request_info($loa_id) {
    $this->db->select('*')
            ->from('loa_requests as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function set_older_loa_rescheduled($loa_id) {
    $this->db->set('reffered', '1')
            ->where('loa_id' , $loa_id);
    return $this->db->update('loa_requests');
  }


  function update_added_loa_fees($loa_id, $post_data) {
    $this->db->where('loa_id', $loa_id);
    $this->db->update('hr_added_loa_fees', $post_data);
  }

  function update_added_deductions($loa_id, $data) {
    $updated = true;
    
    foreach ($data as $deduction) {
      $deduct_id = $deduction['deduct_id'];
      $post_data = [
        'deduction_name' => $deduction['deduction_name'],
        'deduction_amount' => $deduction['deduction_amount'],
        'updated_on' => date('Y-m-d')
      ];
      
      $this->db->where('loa_id', $loa_id);
      $this->db->where('deduct_id', $deduct_id);
      $updated_single = $this->db->update('hr_added_deductions', $post_data);

      if (!$updated_single) {
        $updated = false;
        break;
      }
    }
    return $updated;
  }

  function check_if_already_added($loa_id) {
    $this->db->select('*')
            ->from('loa_requests')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  // function check_if_loa_already_added($loa_id) {
  //   $query = $this->db->get_where('hr_added_loa_fees', ['loa_id' => $loa_id]);
  //   return $query->num_rows() > 0 ? true : false;
  // }

  // function check_if_loa_already_added($loa_id) {
  //   $query = $this->db->get_where('hr_added_loa_fees', ['loa_id' => $loa_id]);
  //   return $query->num_rows() > 0 ? true : false;
  // }

  function check_if_loa_already_added($loa_id) {
    $query = $this->db->get_where('hr_added_loa_fees', ['loa_id' => $loa_id]);
    if ($query->num_rows() > 0) {
        return $query->row(); // Return the fetched row
    } else {
        return false;
    }
  }

  // function check_if_guarantee_letter_already_added($loa_id) {
  //   $query = $this->db->get_where('billing', ['loa_id' => $loa_id]);
  //   if ($query->num_rows() > 0) {
  //       return $query->row(); // Return the fetched row
  //   } else {
  //       return false;
  //   }
  // }


  function check_if_guarantee_letter_already_added($loa_id) {
    $this->db->select('guarantee_letter')
            ->from('billing')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function insert_deductions($data) {
    return $this->db->insert_batch('hr_added_deductions', $data);
  }

  function insert_deductions1($data) {
    return $this->db->insert_batch('hr_added_deductions', $data);
  }

  function insert_charge($data1) {
    return $this->db->insert_batch('hr_add_charges_fee', $data1);
  }
  function insert_deduction($data1) {
    return $this->db->insert_batch('hr_added_deductions', $data1);
  }

  function insert_philhealth($add_deduct) {
    return $this->db->insert('hr_added_deductions', $add_deduct);
  }

  function insert_service_fee($postData) {
    return $this->db->insert_batch('hr_added_service_fee', $postData);
  }

  function get_added_loa_fees($loa_id) {
    $this->db->select('*')
            ->from('hr_added_loa_fees as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('loa_requests as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id')
            ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function get_added_services($loa_id) {
    $this->db->select('*')
            ->from('hr_added_service_fee as tbl_1')
            ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
            ->where('tbl_1.loa_id', $loa_id);
    return $this->db->get()->result_array();
  }

  function get_added_deductions($loa_id) {
    return $this->db->get_where('hr_added_deductions', ['loa_id' => $loa_id])->result_array();
  }

  function get_added_charge($loa_id) {
    return $this->db->get_where('hr_add_charges_fee', ['loa_id' => $loa_id])->result_array();
  }

  function get_hc_provider_billing($loa_id) {
    $this->db->select('*')
            ->from('billing')
            ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function check_if_done_created_new_loa($loa_id) {
    $this->db->select('reffered')
        ->from('loa_requests')
        ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }
  
  // function set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no) {
  //   $this->db->set('done_matching', '1')
  //           ->set('status', 'Payable')
  //           ->set('bill_no', $bill_no)
  //           ->where('status', 'Billed')
  //           ->where('hp_id', $hp_id)
  //           ->where('billed_on >=', $start_date)
  //           ->where('billed_on <=', $end_date)
  //           ->where('loa_id !=', '');
  //   return $this->db->update('billing');
  // }

   function set_bill_for_matched($hp_id, $start_date, $end_date, $bill_no) {
    $this->db->set('status', 'Payable')
            ->set('bill_no', $bill_no)
            ->where('status', 'Billed')
            ->where('hp_id', $hp_id)
            ->where('request_date >=', $start_date)
            ->where('request_date <=', $end_date);
    return $this->db->update('billing');
  }

  function insert_for_payment_consolidated($data) {
    return $this->db->insert('monthly_payable', $data);
  }
  // function update_loa_request_status() {
  //   $data = array('status' => 'Payable');
  //   $this->db->where('status', 'Billed');
  //   $this->db->update('loa_requests', $data);
  // }

  function update_loa_request_status($hp_id, $start_date, $end_date) {
    $this->db->set('status', 'Payable')
            ->where('status', 'Billed')
            ->where('hcare_provider', $hp_id)
            ->where('request_date >=', $start_date)
            ->where('request_date <=', $end_date);
    return $this->db->update('loa_requests');
  }

  function fetch_for_payment_bill($status) {
    $this->db->select('*')
      ->from('monthly_payable as tbl_1')
      ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
      ->where('status', $status)
      ->where('type', 'LOA');
    if($this->input->post('filter')){
      $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
    }
    return $this->db->get()->result_array();
  }

  function history($status) {
    $this->db->select('*')
      ->from('billing as tbl_1')
      ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
      ->join('members as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
      ->join('payment_details as tbl_4', 'tbl_1.details_no = tbl_4.details_no')
      ->where('status', $status);
    if($this->input->post('filter')){
      $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
    }
    if ($this->input->post('startDate')) {
      $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
      $this->db->where('tbl_1.billed_on >=', $startDate);
    }

    if ($this->input->post('endDate')){
      $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
      $this->db->where('tbl_1.billed_on <=', $endDate);
    }
    return $this->db->get()->result_array();
  }



   // Start of server-side processing datatables
  //  var $table_1_billed = 'billing';
  //  var $table_2_billed = 'loa_requests';
  //  var $table_3_billed = 'hr_added_loa_fees';
  //  var $column_order_billed = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date']; //set column field database for datatable orderable
  //  var $column_search_billed = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  //  var $order_billed = ['loa_id' => 'desc']; // default order 
 
  //  private function _get_billed_datatables_query($status) {
  //   $this->db->select('tbl_1.loa_id as tbl1_loa_id, tbl_1.*, tbl_2.*, tbl_3.*');
  //    $this->db->from($this->table_1_billed . ' as tbl_1');
  //    $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
    
  //      $this->db->join($this->table_3_billed . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');

  //    $this->db->where('tbl_1.status', $status);
      
  //   if($this->input->post('filter')){
  //      $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
  //   }

  //   // if ($this->input->post('startDate')) {
  //   //   $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
  //   //   $this->db->where('tbl_1.billed_on >=', $startDate);
  //   // }

  //   // if ($this->input->post('endDate')){
  //   //   $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
  //   //   $this->db->where('tbl_1.billed_on <=', $endDate);
  //   // }

  //    if ($this->input->post('startDate')) {
  //     $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
  //     $this->db->where('tbl_2.request_date >=', $startDate);
  //   }

  //   if ($this->input->post('endDate')){
  //     $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
  //     $this->db->where('tbl_2.request_date <=', $endDate);
  //   }
  // }
 
  //  function get_billed_datatables($status) {
  //    $this->_get_billed_datatables_query($status);
  //    if ($_POST['length'] != -1)
  //      $this->db->limit($_POST['length'], $_POST['start']);
  //    $query = $this->db->get();
  //    return $query->result_array();
  //  }
    // End of server-side processing datatables

//Final Billing
  var $table_1_billed = 'loa_requests';
  var $table_2_billed = 'billing';
  var $table_3_billed = 'hr_added_loa_fees';
  var $column_order_billed = ['loa_no', 'first_name', 'loa_request_type', 'hp_name', null, 'request_date'];
  var $column_search_billed = ['loa_no', 'first_name', 'middle_name', 'last_name', 'suffix', 'loa_request_type', 'med_services', 'emp_id', 'health_card_no', 'hp_name', 'request_date', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $order_billed = ['loa_id' => 'desc'];
 
  // private function _get_billed_datatables_query() {
  //   $this->db->select('tbl_1.loa_id as tbl1_loa_id, tbl_1.status as tbl1_status, tbl_1.request_date as tbl1_request_date,tbl_1.*, tbl_2.*, tbl_3.*');
  //   $this->db->from($this->table_1_billed . ' as tbl_1');
  //   $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left');
  //   $this->db->join($this->table_3_billed . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
  //   $this->db->where('tbl_1.status','Completed');
  //   $this->db->or_where('tbl_1.status','Billed');
  //   $this->db->or_where('tbl_1.status','Approved');
      
  //   if($this->input->post('filter')){
  //     $this->db->like('tbl_2.hp_id', $this->input->post('filter'));
  //   }

  //   if ($this->input->post('startDate')){
  //     $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
  //     $this->db->where('tbl_2.billed_on >=', $startDate);
  //   }

  //   if ($this->input->post('endDate')){
  //     $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
  //     $this->db->where('tbl_2.billed_on <=', $endDate);
  //   }
  // }
 
  // function get_billed_datatables() {
  //   $this->_get_billed_datatables_query();
  //   if ($_POST['length'] != -1)
  //     $this->db->limit($_POST['length'], $_POST['start']);
  //   $query = $this->db->get();
  //   return $query->result_array();
  // }

  private function _get_billed_datatables_query() {
    $this->db->select('tbl_1.loa_id as tbl1_loa_id, tbl_1.status as tbl1_status, tbl_1.request_date as tbl1_request_date, tbl_1.*, tbl_2.*, tbl_3.*');
    $this->db->from($this->table_1_billed . ' as tbl_1');
    $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left');
    $this->db->join($this->table_3_billed . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
    $this->db->where_in('tbl_1.status', ['Completed', 'Billed', 'Approved']);

    $filter = $this->input->post('filter');
    if (!empty($filter)) {
      $this->db->like('tbl_1.hcare_provider', $filter);
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

public function get_billed_datatables() {
    $this->_get_billed_datatables_query();
    $length = $this->input->post('length');
    $start = $this->input->post('start');

    if ($length != -1) {
        $this->db->limit($length, $start);
    }

    $query = $this->db->get();
    return $query->result_array();
}
//End

    // function get_total_hp_net_bill($hp_id, $start_date, $end_date) {
    //   $this->db->select_sum('net_bill')
    //             ->from('billing')
    //             ->where('status', 'Billed')
    //             ->where('hp_id', $hp_id)
    //             ->where('billed_on >=', $start_date)
    //             ->where('billed_on <=', $end_date)
    //             ->where('loa_id !=', '');
    //     $query = $this->db->get();
    //     $result = $query->result_array();
    //     $sum = $result[0]['net_bill'];
    //     return $sum;
    // }

    // function get_total_hp_net_bill($hp_id, $start_date, $end_date) {
    //   $this->db->select_sum('net_bill')
    //             ->from('loa_requests as tbl_1')
    //             ->join('billing as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
    //             ->where('tbl_1.status', 'Billed')
    //             ->where('tbl_1.hcare_provider', $hp_id)
    //             ->where('tbl_1.request_date >=', $start_date)
    //             ->where('tbl_1.request_date <=', $end_date)
    //             ->where('tbl_1.loa_id !=', '');
    //     $query = $this->db->get();
    //     $result = $query->result_array();
    //     $sum = $result[0]['net_bill'];
    //     return $sum;
    // }
function get_total_hp_net_bill($hp_id, $start_date, $end_date) {
    $this->db->select_sum('net_bill')
        ->from('loa_requests as tbl_1')
        ->join('billing as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
        ->where('tbl_2.status', 'Billed')
        ->where('tbl_1.hcare_provider', $hp_id)
        ->where('tbl_1.request_date >=', $start_date)
        ->where('tbl_1.request_date <=', $end_date)
        ->where('tbl_1.loa_id !=', '');

    $query = $this->db->get();
    $result = $query->row();

    if ($result) {
        $sum = $result->net_bill;
        return $sum;
    } else {
        return 0;
    }
}


    function get_total_hr_net_bill($hp_id, $start_date, $end_date) {
      $this->db->select_sum('total_net_bill')
                ->from('billing as tbl_1')
                ->join('hr_added_loa_fees as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
                ->where('tbl_1.status', 'Billed')
                ->where('tbl_1.hp_id', $hp_id)
                ->where('tbl_1.billed_on >=', $start_date)
                ->where('tbl_1.billed_on <=', $end_date)
                ->where('tbl_1.loa_id !=', '');
        $query = $this->db->get();
        $result = $query->result_array();
        $sum = $result[0]['total_net_bill'];
        return $sum;
    }

    function get_loa_charging($loa_id) {
      $this->db->select('*')
              ->from('billing as tbl_1')
              ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id')
              ->join('members as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
              ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
              ->join('max_benefit_limits as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
              ->where('tbl_1.loa_id', $loa_id);
      return $this->db->get()->row_array();
    }
    
    function confirm_loa_charging($loa_id, $data) {
      $this->db->where('loa_id', $loa_id);
      return $this->db->update('billing', $data);
    }

    function set_remaining_balance($emp_id, $remaining_balance) {
      $this->db->set('remaining_balance', $remaining_balance)
              ->where('emp_id', $emp_id);
      return $this->db->update('max_benefit_limits');
    }

    function fetch_monthly_billed_loa($bill_no) {
      $this->db->select('*')
              ->from('monthly_payable as tbl_1')
              ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
              ->where('bill_no', $bill_no);
      return $this->db->get()->row_array();
    }

// Start of server-side processing datatables
var $table_1_monthly = 'billing';
var $table_2_monthly = 'loa_requests';
var $table_3_monthly = 'hr_added_loa_fees';
var $table_4_monthly = 'members';
private function _get_monthly_datatables_query($bill_no) {
  $this->db->from($this->table_1_monthly . ' as tbl_1');
  $this->db->join($this->table_2_monthly . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
  $this->db->join($this->table_3_monthly . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
  $this->db->join($this->table_4_monthly . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
  $this->db->where('tbl_1.bill_no', $bill_no);
  
}

function monthly_bill_datatable($bill_no) {
  $this->_get_monthly_datatables_query($bill_no);
  if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
  $query = $this->db->get();
  return $query->result_array();
}


function get_monthly_bill($bill_no) {
  $this->db->select('tbl_1.* ,tbl_2.*, tbl_4.*, tbl_2.loa_id as tbl2_loa_id, tbl_2.loa_no as tbl2_loa_no, tbl_4.first_name as tbl4_fname, tbl_4.middle_name as tbl4_mname, tbl_4.last_name as tbl4_lname, tbl_4.suffix as tbl4_suffix, tbl_2.percentage as tbl2_percentage');
    $this->db->from($this->table_1_monthly . ' as tbl_1');
    $this->db->join($this->table_2_monthly . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
    $this->db->join($this->table_3_monthly . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
    $this->db->join($this->table_4_monthly . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
    $this->db->where('tbl_2.loa_no', $bill_no);
    $query = $this->db->get();
    $result = $query->row_array(); // Retrieve a single row as an associative array
    return $result;
    // echo $this->db->last_query();
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



function get_matched_total_hr_bill($bill_no) {
  $this->db->select_sum('total_net_bill')
            ->from('billing as tbl_1')
            ->join('hr_added_loa_fees as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
            ->where('bill_no', $bill_no);
    $query = $this->db->get();
    $result = $query->result_array();
    $sum = $result[0]['total_net_bill'];
    return $sum;
}

 // function get_all_approved_loa($bill_no){
 //    $this->db->select('*')
 //            ->from('loa_requests as tbl_1')
 //            ->join('healthcare_providers as tbl_2', 'tbl_1.hcare_provider = tbl_2.hp_id')
 //            ->where('tbl_1.loa_id', $loa_id)
 //            // ->where('tbl_1.status', 'Approved')
 //            ->where('tbl_1.performed_fees', 'Approved');
 //            // ->where('tbl_1.completed', 0)
 //    return $this->db->get()->row_array();
 //  }

//billing for charging datatable
var $charging_table_1 = 'billing';
var $charging_table_2 = 'loa_requests';
var $charging_table_3 = 'hr_added_loa_fees';
var $charging_table_4 = 'max_benefit_limits';
var $charging_table_5 = 'members';
private function _get_datatables_charging_query($bill_no) {
  $this->db->from($this->charging_table_1 . ' as tbl_1')
          ->join($this->charging_table_2 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id')
          ->join($this->charging_table_3 . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left')
          ->join($this->charging_table_4 . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
          ->join($this->charging_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
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

//HEALTHCARE ADVANCE=============================================
// function submit_ha_request($billing_id) {
//     $this->db->set('status','For Advance');
//     $this->db->set('requested_on',date('Y-m-d'));
//     $this->db->where('billing_id',$billing_id);
//     return $this->db->update('cash_advance');
//   }

  function get_charge_details($billing_id) {
    $this->db->select('*')
      ->from('billing as tbl_1')
      ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left')
      ->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
      ->join('cash_advance as tbl_4', 'tbl_1.billing_id = tbl_4.billing_id','left')
      ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id','left')
      ->join('healthcare_providers as tbl_6', 'tbl_1.hp_id = tbl_6.hp_id','left')
      ->where('tbl_1.billing_id',$billing_id);
    return $this->db->get()->result_array();
  }

  function get_healthcare_advance_data_pending($status) {
    $this->db->select('tbl_3.first_name as tbl_3_fname, tbl_3.middle_name as tbl_3_mname, tbl_3.last_name as tbl_3_lname, tbl_3.suffix as tbl_3_suffix, tbl_1.*, tbl_2.*, tbl_3.*, tbl_4.*, tbl_5.*, tbl_6.*');
    $this->db->from('cash_advance as tbl_1');
    $this->db->join('billing as tbl_2','tbl_1.billing_id = tbl_2.billing_id');
    $this->db->join('members as tbl_3','tbl_2.emp_id= tbl_3.emp_id','left');
    $this->db->join('noa_requests as tbl_4','tbl_2.noa_id= tbl_4.noa_id','left');
    $this->db->join('loa_requests as tbl_5','tbl_2.loa_id= tbl_5.loa_id','left');
    $this->db->join('healthcare_providers as tbl_6','tbl_2.hp_id= tbl_6.hp_id');
    $this->db->where('tbl_1.status',$status);
  }

  function get_result_healthcare_advance_data_pending($status) {
    $this->get_healthcare_advance_data_pending($status);
    if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_healthcare_advance_data_pending($status) {
    $this->get_healthcare_advance_data_pending($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_healthcare_advance_data_pending($status) {
    $this->db->from('cash_advance');
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }

  function get_healthcare_advance_data_approved($status) {
    $this->db->select('tbl_3.first_name as tbl_3_fname, tbl_3.middle_name as tbl_3_mname, tbl_3.last_name as tbl_3_lname, tbl_3.suffix as tbl_3_suffix, tbl_1.*, tbl_2.*, tbl_3.*, tbl_4.*, tbl_5.*, tbl_6.*');
    $this->db->from('cash_advance as tbl_1');
    $this->db->join('billing as tbl_2','tbl_1.billing_id = tbl_2.billing_id');
    $this->db->join('members as tbl_3','tbl_2.emp_id= tbl_3.emp_id','left');
    $this->db->join('noa_requests as tbl_4','tbl_2.noa_id= tbl_4.noa_id','left');
    $this->db->join('loa_requests as tbl_5','tbl_2.loa_id= tbl_5.loa_id','left');
    $this->db->join('healthcare_providers as tbl_6','tbl_2.hp_id= tbl_6.hp_id');
    $this->db->where('tbl_1.status',$status);
  }

  function get_result_healthcare_advance_data_approved($status) {
    $this->get_healthcare_advance_data_approved($status);
    if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_healthcare_advance_data_approved($status) {
    $this->get_healthcare_advance_data_approved($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_healthcare_advance_data_approved($status) {
    $this->db->from('cash_advance');
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }

  function get_healthcare_advance_data_disapproved($status) {
    $this->db->select('tbl_3.first_name as tbl_3_fname, tbl_3.middle_name as tbl_3_mname, tbl_3.last_name as tbl_3_lname, tbl_3.suffix as tbl_3_suffix, tbl_1.*, tbl_2.*, tbl_3.*, tbl_4.*, tbl_5.*, tbl_6.*');
    $this->db->from('cash_advance as tbl_1');
    $this->db->join('billing as tbl_2','tbl_1.billing_id = tbl_2.billing_id');
    $this->db->join('members as tbl_3','tbl_2.emp_id= tbl_3.emp_id','left');
    $this->db->join('noa_requests as tbl_4','tbl_2.noa_id= tbl_4.noa_id','left');
    $this->db->join('loa_requests as tbl_5','tbl_2.loa_id= tbl_5.loa_id','left');
    $this->db->join('healthcare_providers as tbl_6','tbl_2.hp_id= tbl_6.hp_id');
    $this->db->where('tbl_1.status',$status);
  }

  function get_result_healthcare_advance_data_disapproved($status) {
    $this->get_healthcare_advance_data_disapproved($status);
    if ($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_healthcare_advance_data_disapproved($status) {
    $this->get_healthcare_advance_data_disapproved($status);
    $query = $this->db->get();
    return $query->num_rows();
  }

  function count_all_healthcare_advance_data_disapproved($status) {
    $this->db->from('cash_advance');
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }
//END============================================================

//FINAL BILLING==================================================
  function db_get_cost_types_by_hpID($hp_id, $loa_id){
    $this->db->select('*')
      ->from('performed_loa_info as tbl_1')
      ->join('cost_types as tbl_2', 'tbl_1.ctype_id = tbl_2.ctype_id')
      ->where('tbl_1.status', 'Performed')
      ->where('tbl_1.loa_id', $loa_id)
      ->where('tbl_2.hp_id', $hp_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function get_cost_types_by_hp($hp_id){
    $this->db->select('*')
      ->from('cost_types')
      ->where('hp_id', $hp_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function get_itemized_bill($emp_id){
    $this->db->select('*')
      ->from('itemized_bill')
      ->where('emp_id', $emp_id);
    $query = $this->db->get();
    return $query->result_array();
  }

  function get_benefits_deduction($emp_id){
    $this->db->select('*')
      ->from('benefits_deductions')
      ->where('emp_id', $emp_id);
    $query = $this->db->get();
    return $query->result_array();
  }


  function db_get_hr_add_charges_fee($loa_id) {
    $this->db->select('*')
      ->from('hr_add_charges_fee')
      ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  } 

  function db_get_hr_deduction_fee($loa_id) {
    $this->db->select('*')
      ->from('hr_added_deductions')
      ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  } 

  function db_get_hr_added_loa_fees($loa_id) {
    $this->db->select('*')
      ->from('hr_added_loa_fees')
      ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  } 

  function db_get_hr_added_deductions1($loa_id) {
    $this->db->select('*')
      ->from('hr_added_deductions')
      ->where('loa_id', $loa_id);
    return $this->db->get()->result_array();
  } 

  function insert_added_loa_fees1($post_data) {
    return $this->db->insert('hr_added_loa_fees', $post_data);
  }

  function insert_service_fee1($postData) {
    return $this->db->insert_batch('hr_added_service_fee', $postData);
  }

  function insert_deductions2($data) {
    return $this->db->insert_batch('hr_added_deductions', $data);
  }

  function insert_philhealth1($add_deduct) {
    return $this->db->insert('hr_added_deductions', $add_deduct);
  }

  function check_if_loa_already_added1($loa_id) {
    $query = $this->db->get_where('hr_added_loa_fees', ['loa_id' => $loa_id]);
    if ($query->num_rows() > 0) {
      return $query->row(); // Return the fetched row
    }else{
      return false;
    }
  }

  function check_if_done_created_new_loa1($loa_id) {
    $this->db->select('reffered')
      ->from('loa_requests')
      ->where('loa_id', $loa_id);
    return $this->db->get()->row_array();
  }

  function check_if_status_cancelled1($loa_id) {
    $this->db->select('status')
      ->where('status', 'Referred')
      ->where('loa_id', $loa_id)
      ->group_by('loa_id');
    $query = $this->db->get('performed_loa_info');

    if ($query->num_rows() > 0) {
      return true;
    }else{
      return false;
    }
  }

  function _set_loa_status_completed2($loa_id) {
    $this->db->set('completed', '')
      ->where('status', 'Billed')
      ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }

  function _set_loa_status_completed1($loa_id) {
    $this->db->set('completed', '')
      ->where('status', 'Billed')
      ->where('loa_id', $loa_id);
    return $this->db->update('loa_requests');
  }
// END============================================================

//LEDGER============================================================
  var $ledger1 = 'members';
  var $ledger2 = 'billing';
  var $ledger3 = 'max_benefit_limits';
  var $column_order_ledger = ['member_id', 'first_name', 'emp_type', 'status', 'business_unit', 'dept_name']; 
  var $column_search_ledger= ['member_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)', 'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $order_ledger = ['emp_no' => 'desc'];
  private function _get_datatables_query_ledger($status) {
    $this->db->group_by('emp_no');
    $this->db->from($this->ledger1 . ' as tbl_1');
    $this->db->join($this->ledger2 . ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->join($this->ledger3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
    $this->db->where('status',$status);

    $i = 0;
    foreach ($this->column_search_ledger as $item) {
      if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
        if ($i === 0) {
          $this->db->group_start(); // start where clause group
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search_ledger) - 1 == $i)
          $this->db->group_end(); // end where clause group
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $column_order_index = $_POST['order']['0']['column'];
      $column_order_dir = $_POST['order']['0']['dir'];
      $column_order = $this->column_order_ledger[$column_order_index];
      $this->db->order_by($column_order, $column_order_dir);
    } else if (isset($this->order_ledger)) {
      $this->db->order_by(key($this->order_ledger), $this->order_ledger[key($this->order_ledger)]);
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
    $this->db->from($this->ledger2);
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }

  function count_filtered_ledger($status) {
    $this->_get_datatables_query_ledger($status);
    $query = $this->db->get();
  }

  var $ledger_tbl1 = 'billing';
  var $ledger_tbl2 = 'payment_details';
  var $ledger_tbl3 = 'loa_requests';
  var $ledger_tbl4 = 'noa_requests';
  var $ledger_tbl5 = 'members';
  var $ledger_tbl6 = 'max_benefit_limits';
  var $column_order_ledger2 = ['first_name', 'max_benefit_limit', 'acc_number', 'check_num', 'bank','check_date','amount_paid','supporting_file','status']; 
  var $column_search_ledger2= ['first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)', 'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)'];
  var $order_ledger2 = ['billing_id' => 'desc'];

  private function _get_datatables_query_ledger2($status,$emp_id) {
    $this->db->from($this->ledger_tbl1 . ' as tbl_1');
    $this->db->join($this->ledger_tbl2 . ' as tbl_2', 'tbl_1.details_no = tbl_2.details_no');
    $this->db->join($this->ledger_tbl3 . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id','left');
    $this->db->join($this->ledger_tbl4 . ' as tbl_4', 'tbl_1.noa_id = tbl_4.noa_id','left');
    $this->db->join($this->ledger_tbl5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id');
    $this->db->join($this->ledger_tbl6 . ' as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id');
    $this->db->where('tbl_1.status',$status);
    $this->db->where('tbl_1.emp_id',$emp_id);

    $i = 0;
    foreach ($this->column_search_ledger2 as $item) {
      if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) { // check if search value is set and not empty
        if ($i === 0) {
          $this->db->group_start(); // start where clause group
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search_ledger2) - 1 == $i)
          $this->db->group_end(); // end where clause group
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $column_order_index = $_POST['order']['0']['column'];
      $column_order_dir = $_POST['order']['0']['dir'];
      $column_order = $this->column_order_ledger2[$column_order_index];
      $this->db->order_by($column_order, $column_order_dir);
    } else if (isset($this->order_ledger2)) {
      $this->db->order_by(key($this->order_ledger2), $this->order_ledger2[key($this->order_ledger2)]);
    }
  }
  function get_datatables_ledger2($status,$emp_id) {
    $this->_get_datatables_query_ledger2($status,$emp_id);
    if (isset($_POST['length']) && $_POST['length'] != -1) { 
      $this->db->limit($_POST['length'], $_POST['start']);
    }
    $query = $this->db->get();
    return $query->result_array();
  }

  function count_all_ledger2($status) {
    $this->db->from($this->ledger2);
    $this->db->where('status', $status);
    return $this->db->count_all_results();
  }

  function count_filtered_ledger2($status,$emp_id) {
    $this->_get_datatables_query_ledger2($status,$emp_id);
    $query = $this->db->get();
  }

  // function db_get_all_paid() {
  //   $this->db->select('*')
  //            ->from('billing as tbl_1')
  //            ->join('payment_details as tbl_2', 'tbl_1.details_no = tbl_2.details_no')
  //            ->where('tbl_1.status', 'Paid')
  //            ->order_by('loa_id', 'DESC');
  //   return $this->db->get()->result_array();
  // }

  // function db_get_all_paid() {
  //   $this->db->select('*')
  //     ->from('billing as tbl_1')
  //     ->join('payment_details as tbl_2', 'tbl_1.details_no = tbl_2.details_no','left')
  //     ->where('tbl_1.status','Paid');
  //   return $this->db->get()->result_array();
  // }

//END==================================================

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

}
