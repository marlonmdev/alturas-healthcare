<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends CI_Model {
	//SUMMARY OF BILLING====================================================
	function get_member_by_id($emp_no){
		$query = $this->db->get_where('members', ['emp_no' => $emp_no]);
		return $query->row_array();
	}
	function get_member_by_healthcard($healthcard){
		$query = $this->db->get_where('members', ['health_card_no' => $healthcard]);
		return $query->row_array();
	}
	function get_member_by_name($first_name,$middle_name, $last_name){
		$query = $this->db->get_where('members', ['first_name' => $first_name,'middle_name'=>$middle_name,'last_name' => $last_name]);
		return $query->row_array();
	}
	function get_member_mbl($emp_id){
		$query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
		return $query->row_array();
	} 
	function get_healthcare_provider($hcare_provider_id){
		$query = $this->db->get_where('healthcare_providers', ['hp_id' => $hcare_provider_id]);
		return $query->row();
	}
	function get_billing_status($emp_id, $hcare_provider_id){
		$this->db->select('billing_id, billing_no, emp_id, billed_on,payment_no,loa_id,noa_id,status,total_bill')
						->from('billing')
						->where('emp_id', $emp_id)
						->order_by('loa_id', 'DESC');
		return $this->db->get()->result_array();
	}
  function get_member_noa($emp_id, $hcare_provider_id){
    $this->db->select('noa_id, noa_no, emp_id, request_date, status, hospital_id')
            ->from('noa_requests')
            ->where('emp_id', $emp_id)
            ->where('hospital_id', $hcare_provider_id)
            ->order_by('noa_id', 'DESC');
    return $this->db->get()->result_array();
  }

	function get_loa_billing_info($id){
		$this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
					->from('billing as tbl_1')
					->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
					->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
					->where('tbl_1.loa_id', $id);
		return $this->db->get()->row_array();
	}

	function get_noa_billing_info($id){
		$this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
					->from('billing as tbl_1')
					->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
					->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
					->where('tbl_1.billing_id', $id);
		return $this->db->get()->row_array();
	}

	function get_billing_info($id){
		$this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
					->from('billing as tbl_1')
					->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
					->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
					->where('tbl_1.billing_id', $id);
		return $this->db->get()->row_array();
	}

	function get_billing_services($billing_no){
		$query = $this->db->get_where('billing_services', ['billing_no' => $billing_no]);
		return $query->result_array();
	}
	function get_billing_deductions($billing_no){
		$query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
		return $query->result_array();
	}
	//END=====================================================================================

	//PAYMENT DETAILS ====================================================
	// var $table_closed_1 = 'billing';
 //  var $table_closed_2 = 'members';
 //  var $table_closed_3 = 'healthcare_providers';
 //  var $table_closed_4 = 'payment_details';
	// var $column_closed_order = ['tbl_1.payment_no','tbl_1.billed_on', 'tbl_1.billing_no'];
	// var $column_closed_search = ['tbl_1.payment_no', 'tbl_1.billed_on'];
	// var $order_closed = ['tbl_1.billing_id' => 'asc'];

	// private function _get_closed_datatables_query($status) {

	// 	$this->db->from($this->table_closed_1. ' as tbl_1');
 //    $this->db->join($this->table_closed_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
 //    $this->db->join($this->table_closed_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
 //    $this->db->join($this->table_closed_4. ' as tbl_4', 'tbl_1.payment_no = tbl_4.payment_no');
 //    $this->db->where('status', $status);
	// 	$i = 0;

 //    if($this->input->post('filter')){
	// 		$this->db->like('tbl_1.hp_id', $this->input->post('filter'));
	// 	}

	// 	foreach ($this->column_closed_search as $item) {
	// 		if ($_POST['search']['value']) {
	// 			if ($i === 0) {
	// 				$this->db->group_start();
	// 				$this->db->like($item, $_POST['search']['value']);
	// 			}else {
	// 				$this->db->or_like($item, $_POST['search']['value']);
	// 			}
	// 			if (count($this->column_closed_search) - 1 == $i) //last loop
	// 				$this->db->group_end(); //close bracket
	// 		}
	// 		$i++;
	// 	}

	// 	if (isset($_POST['order'])) {
	// 		$this->db->order_by($this->column_closed_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	// 	} else if (isset($this->order_closed)) {
	// 		$order = $this->order_closed;
	// 		$this->db->order_by(key($order), $order[key($order)]);
	// 	}
	// }

	// function count_closed_all() {
	// 	$this->db->from($this->table_closed_1)
 //            ->where('status', 'Paid');
	// 	return $this->db->count_all_results();
	// }
	// function count_closed_filtered($status) {
	// 	$this->_get_closed_datatables_query($status);
	// 	$query = $this->db->get();
	// 	return $query->num_rows();
	// }
	//END=====================================================================================

	//MEMBER =================================================================================
  var $table1 = 'members';
	var $table2 = 'billing';
	var $column_order = ['member_id', 'first_name', 'emp_type', 'status', 'business_unit', 'dept_name']; 
	
	var $column_search = ['member_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'emp_type', 'status', 'business_unit', 'dept_name', 'CONCAT(first_name, " ",last_name)',   'CONCAT(first_name, " ",last_name, " ", suffix)', 'CONCAT(first_name, " ",middle_name, " ",last_name)', 'CONCAT(first_name, " ",middle_name, " ",last_name, " ", suffix)']; //set column field database for datatable searchable 
  var $order = ['member_id' => 'desc']; // default order 

  private function _get_datatables_query($approval_status) {
		$this->db->group_by('emp_no');
		$this->db->from($this->table1 . ' as tbl_1');
    $this->db->join($this->table2 . ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
    $this->db->where('status', $approval_status);
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

	function get_datatables($approval_status) {
		$this->_get_datatables_query($approval_status);
		if ($_POST['length'] != -1)
		  $this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}
	function count_all($approval_status) {
    $this->db->from($this->table2);
    $this->db->where('status', $approval_status);
    return $this->db->count_all_results();
  }
	function count_filtered($approval_status) {
    $this->_get_datatables_query($approval_status);
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
	//END=====================================================================================

	//ACCOUNT SETTING=========================================================================
	function get_user_account_details($user_id) {
		return $this->db->get_where('user_accounts', array('user_id =' => $user_id))->row_array();
	}
	public function db_update_user_account($user_id, $post_data) {
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $post_data);
	}
	public function db_check_username($new_username) {
		$query = $this->db->get_where('user_accounts', array('username' => $new_username));
		return $query->num_rows() > 0 ? true : false;
	}
	//END ====================================================================================


	function get_billing_by_payment_no($payment_no){
		$query = $this->db->get_where('billing', ['payment_no' => $payment_no]);
		return $query->row_array();
	}
	function get_paymentdetails_by_payment_no($payment_no){
		$query = $this->db->get_where('payment_details', ['payment_no' => $payment_no]);
		return $query->row_array();
	}

    
}
