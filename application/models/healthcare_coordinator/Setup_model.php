<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_model extends CI_Model {

	function db_get_hospitals() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Hospital']);
		return $query->result_array();
	}

	function db_get_laboratories() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Laboratory']);
		return $query->result_array();
	}

	function db_get_clinics() {
		$query = $this->db->get_where('healthcare_providers', ['hp_type' => 'Clinic']);
		return $query->result_array();
	}

	function db_insert_healthcare_provider($post_data) {
		return $this->db->insert('healthcare_providers', $post_data);
	}

	function db_check_healthcare_provider($hp_name) {
		$query = $this->db->get_where('healthcare_providers', ['hp_name' => $hp_name]);
		return $query->num_rows() > 0 ? true : false;
	}

	function db_get_healthcare_providers() {
		$this->db->select('*')
						 ->from('healthcare_providers');
		return $this->db->get()->result_array();
	}

	function rt_get_healthcare_providers() {
		$this->db->select('*')
						 ->from('healthcare_providers');
		return $this->db->get()->result_array();
	}

	function db_get_all_healthcare_providers() {
		$this->db->select('*')
						 ->from('healthcare_providers')
						 ->order_by('hp_id', 'DESC');
		return $this->db->get()->result_array();
	}

	function db_get_healthcare_provider_info($hp_id) {
		$query = $this->db->get_where('healthcare_providers', ['hp_id' => $hp_id]);
		return $query->row_array();
	}

	function db_update_healthcare_provider($hp_id, $post_data) {
		$this->db->where('hp_id', $hp_id);
		return $this->db->update('healthcare_providers', $post_data);
	}

	function db_delete_healthcare_provider($hp_id) {
		$this->db->where('hp_id', $hp_id)
						 ->delete('healthcare_providers');
		return $this->db->affected_rows() > 0 ? true : false;
	}

	function db_get_company_doctors() {
		$this->db->select('*')
					   ->from('company_doctors');
		return $this->db->get()->result_array();
	}

	function db_get_all_company_doctors() {
		$this->db->select('*')
						 ->from('company_doctors')
						 ->order_by('doctor_id', 'DESC');
		return $this->db->get()->result_array();
	}

	function db_get_doctor_info($doctor_id) {
		$query = $this->db->get_where('company_doctors', ['doctor_id' => $doctor_id]);
		return $query->row_array();
	}

	function db_check_username($doctor_username) {
		$query = $this->db->get_where('user_accounts', ['username' => $doctor_username]);
		return $query->num_rows() > 0 ? true : false;
	}

	function db_insert_company_doctor($post_data) {
		return $this->db->insert('company_doctors', $post_data);
	}

	function db_insert_company_doctor_user_account($account_data) {
		return $this->db->insert('user_accounts', $account_data);
	}

	function db_check_doctor_name($doctor_name) {
		$query = $this->db->get_where('company_doctors', ['doctor_name' => $doctor_name]);
		return $query->num_rows() > 0 ? true : false;
	}

	function db_update_company_doctor($doctor_id, $post_data) {
		$this->db->where('doctor_id', $doctor_id);
		return $this->db->update('company_doctors', $post_data);
	}

	function db_update_company_doctor_account_name($doctor_id, $doctor_name) {
		$this->db->set('full_name', $doctor_name)
						 ->where('doctor_id', $doctor_id);
		return $this->db->update('user_accounts');
	}

	function db_delete_company_doctor($doctor_id) {
		$tables = array('company_doctors', 'user_accounts');
		$this->db->where('doctor_id', $doctor_id)
						 ->delete($tables);
		return $this->db->affected_rows() > 0 ? true : false;
	}

	 // Start of cost_types server-side processing datatables
	 var $table = 'cost_types';
	 var $column_order = ['item_id', 'item_description', 'op_price', 'ip_price', 'date_added']; //set column field database for datatable orderable
	 var $column_search = ['item_id', 'item_description', 'op_price', 'ip_price']; //set column field database for datatable searchable 
	 var $order = ['ctype_id' => 'asc']; // default order 
   
	 private function _get_datatables_query() {
	   $this->db->from($this->table);
	   $i = 0;

	   if($this->input->post('filter')){
			$this->db->like('price_list_group', $this->input->post('filter'));
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
   
	 function get_datatables() {
	   $this->_get_datatables_query();
	   if ($_POST['length'] != -1)
		 $this->db->limit($_POST['length'], $_POST['start']);
	   $query = $this->db->get();
	   return $query->result_array();
	 } 
   
	 function count_filtered() {
	   $this->_get_datatables_query();
	   $query = $this->db->get();
	   return $query->num_rows();
	 }
   
	 function count_all() {
	   $this->db->from($this->table);
	   return $this->db->count_all_results();
	 }
	 // End of server-side processing datatables

	  // Start of room_types server-side processing datatables
	var $room_table = 'room_types';
	var $hp_table = 'healthcare_providers';
	var $room_column_order = ['room_id', 'hp_name', 'room_type', 'room_number', 'room_rate', 'tbl_1.date_added']; //set column field database for datatable orderable
	var $room_column_search = ['room_id', 'room_group', 'room_type', 'room_typ_hmo_req', 'room_number', 'room_rate', 'hp_name']; //set column field database for datatable searchable 
	var $room_order = ['room_id' => 'asc']; // default order 
	
	private function _get_room_datatables_query() {
		$this->db->from($this->room_table . ' as tbl_1');
		$this->db->join($this->hp_table . ' as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id');
		$i = 0;
		// loop column 
		foreach ($this->room_column_search as $item) {
		  // if datatable send POST for search
		  if ($_POST['search']['value']) {
				// first loop
				if ($i === 0) {
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
		
				if (count($this->room_column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
				$i++;
		}
	
			// here order processing
			if (isset($_POST['order'])) {
				$this->db->order_by($this->room_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else if (isset($this->room_order)) {
				$order = $this->room_order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
	}
	
	function get_room_datatables() {
		$this->_get_room_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_room_filtered() {
		$this->_get_room_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_all_room() {
		$this->db->from($this->room_table);
		return $this->db->count_all_results();
	}
	// End of server-side processing datatables

	function get_price_group() {
		return $this->db->get('cost_types')->result_array();
	}

	function db_get_cost_type_info($ctype_id) {
		$query = $this->db->get_where('cost_types', ['ctype_id' => $ctype_id]);
		return $query->row_array();
	}

	function db_insert_cost_type($post_data) {
		return $this->db->insert('cost_types', $post_data);
	}

	function db_check_cost_type($cost_type) {
		$query = $this->db->get_where('cost_types', ['item_description' => $cost_type]);
		return $query->num_rows() > 0 ? true : false;
	}

	function db_insert_room_type($post_data) {
		return $this->db->insert('room_types', $post_data);
	}

	function db_get_all_cost_types() {
		return $this->db->get('cost_types')->result_array();
	}

	function db_get_room_type_info($room_id){
		$this->db->select('*')
             ->from('room_types as tbl_1')
             ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
             ->where('tbl_1.room_id', $room_id);
    return $this->db->get()->row_array();
	}
	function db_update_room_type($room_id, $post_data){
		$this->db->where('room_id', $room_id);
		return $this->db->update('room_types', $post_data);
	}
	function db_delete_room_type($room_id) {
		$this->db->where('room_id', $room_id)
		         ->delete('room_types');
		return $this->db->affected_rows() > 0 ? true : false;
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
}
