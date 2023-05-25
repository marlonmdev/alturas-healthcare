<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Patient_model extends CI_Model {
	var $table1 = 'noa_requests';
	var $table5 = 'loa_requests';
	var $table2 = 'members';
	var $table3 = 'max_benefit_limits';
	var $table4 = 'healthcare_providers';
	var $column_order = ['tbl_2.member_id', 'tbl_2.first_name', 'tbl_2.business_unit', 'tbl_2.dept_name', 'tbl_4.hp_name']; 
	
	var $column_search = [
    'tbl_2.emp_no',
    'tbl_2.first_name',
    'tbl_2.middle_name',
    'tbl_2.last_name',
    'tbl_2.suffix',
    'tbl_2.business_unit',
    'tbl_2.dept_name',
    'tbl_4.hp_name',
    'CONCAT(tbl_2.first_name, " ", tbl_2.last_name)',
    'CONCAT(tbl_2.first_name, " ", tbl_2.last_name, " ", tbl_2.suffix)',
    'CONCAT(tbl_2.first_name, " ", tbl_2.middle_name, " ", tbl_2.last_name)',
    'CONCAT(tbl_2.first_name, " ", tbl_2.middle_name, " ", tbl_2.last_name, " ", tbl_2.suffix)'
  ];
  var $order = ['member_id' => 'desc']; // default order 

  private function _get_datatables_query($hp_id,$loa_noa) {

		if($loa_noa === "noa"){
      $this->db->group_by('emp_no');
      $this->db->from($this->table1 . ' as tbl_1');
      $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
      $this->db->join($this->table3 . ' as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id');
      $this->db->join($this->table4 . ' as tbl_4', 'tbl_1.hospital_id = tbl_4.hp_id');
      $this->db->where('tbl_1.hospital_id', $hp_id);
      $this->db->group_start()
         ->where('tbl_1.status', 'Approved')
         ->or_where('tbl_1.status', 'Completed')
         ->or_where('tbl_1.status', 'Billed')
         ->group_end();
    }elseif($loa_noa === "loa"){
      $this->db->group_by('emp_no');
      $this->db->from($this->table5 . ' as tbl_5');
      $this->db->join($this->table2 . ' as tbl_2', 'tbl_5.emp_id = tbl_2.emp_id');
      $this->db->join($this->table3 . ' as tbl_3', 'tbl_5.emp_id = tbl_3.emp_id');
      $this->db->join($this->table4 . ' as tbl_4', 'tbl_5.hcare_provider = tbl_4.hp_id');
      $this->db->where('tbl_5.hcare_provider', $hp_id);
      $this->db->group_start()
      ->where('tbl_5.status', 'Approved')
      ->or_where('tbl_5.status', 'Completed')
      ->or_where('tbl_5.status', 'Billed')
      ->group_end();
    }
    $i = 0;

    foreach ($this->column_search as $item) {
      if ($_POST['search']['value']) {
        if ($i === 0) {
          $this->db->group_start();
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i)
          $this->db->group_end(); 
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

	function get_datatables($hp_id,$loa_noa) {
		$this->_get_datatables_query($hp_id,$loa_noa);
		if ($_POST['length'] != -1)
		  $this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
	function count_all($hp_id,$loa_noa) {
    if($loa_noa === "noa"){
      $this->db->from($this->table1);
      $this->db->where('hospital_id', $hp_id);
    }elseif($loa_noa === "loa"){
      $this->db->from($this->table5);
      $this->db->where('hcare_provider', $hp_id);
    }
  
    return $this->db->count_all_results();
  }
	function count_filtered($hp_id,$loa_noa) {
    $this->_get_datatables_query($hp_id,$loa_noa);
    $query = $this->db->get();
    return $query->num_rows();
  }
  function db_get_member_details($member_id) {
    $query = $this->db->get_where('members', ['member_id' => $member_id]);
    return $query->row_array();
  }
  function db_get_member_mbl($emp_id) {
    $this->db->select('*');
    $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
    return $query->row_array();
  }
     // Start of server-side processing datatables
     var $table_1_soa = 'billing';
     var $table_2_soa = 'noa_requests';
     var $table_3_soa = 'loa_requests';
     var $table_4_soa = 'members';
     var $table_5_soa = 'healthcare_providers';
     var $table_7_soa = 'max_benefit_limits';
     var $column_search_soa = [
      'tbl_4.first_name',
      'tbl_4.middle_name',
      'tbl_4.last_name',
      'tbl_4.suffix',
      'tbl_4.business_unit',
      'tbl_4.dept_name',
      'tbl_2.noa_no',
      'tbl_3.loa_no',
      'tbl_1.billing_no',
      'CONCAT(tbl_4.first_name, " ", tbl_4.last_name)',
      'CONCAT(tbl_4.first_name, " ", tbl_4.last_name, " ", tbl_4.suffix)',
      'CONCAT(tbl_4.first_name, " ", tbl_4.middle_name, " ", tbl_4.last_name)',
      'CONCAT(tbl_4.first_name, " ", tbl_4.middle_name, " ", tbl_4.last_name, " ", tbl_4.suffix)'
    ];
     private function _get_soa_list_datatables_query($hp_id) {
         $this->db->select('*');
         $this->db->from($this->table_1_soa . ' as tbl_1');

         if($this->input->post('loa_noa') === "LOA"){
          $this->db->join($this->table_3_soa . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id');
        }elseif($this->input->post('loa_noa') === "NOA"){
          $this->db->join($this->table_2_soa . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id');
        }else{
          $this->db->join($this->table_3_soa . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
          $this->db->join($this->table_2_soa . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id', 'left');
        } 
         $this->db->join($this->table_4_soa . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
         $this->db->join($this->table_5_soa . ' as tbl_5', 'tbl_1.hp_id = tbl_5.hp_id');
         $this->db->join($this->table_7_soa . ' as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id');
         $this->db->where('tbl_1.hp_id', $hp_id);

         $i = 0;
        // loop column 
        foreach ($this->column_search_soa as $item) {
        // if datatable send POST for search
        if ($this->input->post('searchInput')) {
            // first loop
            if ($i === 0) {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $this->input->post('searchInput'));
            } else {
            $this->db->or_like($item, $this->input->post('searchInput'));
            }

            if (count($this->column_search_soa) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
        }
        //  $this->db->where('tbl_1.pdf_bill IS NOT NULL');
         
        if ($this->input->post('startDate')) {
          $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
          $this->db->where('tbl_1.billed_on >=', $startDate);
        }
        if ($this->input->post('endDate')){
          $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
          $this->db->where('tbl_1.billed_on <=', $endDate);
        }
        if($this->input->post('loa_noa') === "loa"){

        }
        
     }
  
     public function soa_list_datatable($hp_id) {
         $this->_get_soa_list_datatables_query($hp_id);
         if ($_POST['length'] != -1)
              $this->db->limit($_POST['length'], $_POST['start']);
          $query = $this->db->get();
          return $query->result_array();
     }

     public function get_hp_name($hp_id){
      $this->db->select('hp_name');
      $this->db->from('healthcare_providers');
      $this->db->where('hp_id', $hp_id);
      $query = $this->db->get();
      return $query->row_array();
     }

     function get_loa_info($loa_id){
      return $this->db->get_where('loa_requests', ['loa_id' => $loa_id])->row_array();
  }

  function get_noa_info($noa_id){
      return $this->db->get_where('noa_requests', ['noa_id' => $noa_id])->row_array();
  }
     function count_filtered_soa($hp_id) {
      $this->_get_soa_list_datatables_query($hp_id);
      $query = $this->db->get();
      return $query->num_rows();
  }

  function count_all_soa($hp_id) {
      $this->db->from($this->table_1_soa)
              ->where('hp_id', $hp_id);
      return $this->db->count_all_results();
  }
}