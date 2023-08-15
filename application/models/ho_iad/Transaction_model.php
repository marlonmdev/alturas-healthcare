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
	function get_hc_provider(){
		$query = $this->db->get_where('healthcare_providers', ['accredited' => 1]);
		return $query->result_array();
	}
	function get_billing_status($emp_id, $hcare_provider_id){
		$this->db->select('*')
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
		$this->db->select('*')
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
    $this->db->where('tbl_2.status', $approval_status);
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
	
    function get_payment_nos($bill_id) {
        return $this->db->get_where('monthly_payable', ['bill_id' => $bill_id])->row_array();
    }

	function get_billing_by_id($billing_id){
		$query = $this->db->get_where('billing', ['billing_id' => $billing_id]);
		return $query->row_array();
	}
	
	function get_paymentdetails($details_no){
		$query = $this->db->get_where('payment_details', ['details_no' => $details_no]);
		return $query->row_array();
	}

	function fetch_for_payment_bills() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
                ->where('tbl_1.status', 'Payment')
				->where('tbl_3.audited_by', '')
				->order_by('tbl_3.bill_id', 'desc');
        return $this->db->get()->result_array();
    }

	function fetch_audited_bills() {
		$this->db->select('*')
				->from('billing as tbl_1')
				->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
				->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
				// ->where('tbl_3.status', 'Audited')
				->where('tbl_1.status', 'Payment')
				->where('tbl_3.payment_no !=', '')
				->order_by('tbl_3.bill_id', 'desc');
		return $this->db->get()->result_array();
	}

	function fetch_paid_bills() {
		$this->db->select('*')
				->from('billing as tbl_1')
				->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
				->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
				->where('tbl_1.status', 'Paid')
				->where('tbl_3.payment_no !=', '')
				->order_by('tbl_3.bill_id', 'desc');
		return $this->db->get()->result_array();
	}

	function get_billed_date($payment_no) {
        $this->db->select('*')
                ->from('monthly_payable as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.payment_no', $payment_no);
        return $this->db->get()->row_array();
    }

	  // Start of server-side processing datatables
	  var $table_1_monthly = 'billing';
	  var $table_2_monthly = 'noa_requests';
	  var $table_3_monthly = 'loa_requests';
	  var $table_4_monthly = 'members';
	  var $table_5_monthly = 'healthcare_providers';
	  var $table_6_monthly = 'locate_business_unit';
	  var $table_7_monthly = 'max_benefit_limits';
   
	  private function _get_monthly_datatables_query($payment_no) {
		  $this->db->select('*');
		  $this->db->from($this->table_1_monthly . ' as tbl_1');
		  $this->db->join($this->table_2_monthly . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id', 'left');
		  $this->db->join($this->table_3_monthly . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
		  $this->db->join($this->table_4_monthly . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
		  $this->db->join($this->table_5_monthly . ' as tbl_5', 'tbl_1.hp_id = tbl_5.hp_id');
		  $this->db->join($this->table_6_monthly . ' as tbl_6', 'tbl_4.business_unit = tbl_6.business_unit');
		  $this->db->join($this->table_7_monthly . ' as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id');
		  $this->db->where('tbl_1.payment_no', $payment_no);
	  }
   
	  public function monthly_bill_datatable($payment_no) {
		  $this->_get_monthly_datatables_query($payment_no);
		  if ($_POST['length'] != -1)
			   $this->db->limit($_POST['length'], $_POST['start']);
		   $query = $this->db->get();
		   return $query->result_array();
	  }
	   // end datatable

	   	  // Start of server-side processing datatables
	  var $table_1_monthlypaid = 'billing';
	  var $table_2_monthlypaid = 'noa_requests';
	  var $table_3_monthlypaid = 'loa_requests';
	  var $table_4_monthlypaid = 'members';
	  var $table_5_monthlypaid = 'healthcare_providers';
	  var $table_6_monthlypaid = 'locate_business_unit';
	  var $table_7_monthlypaid = 'max_benefit_limits';
   
	  private function _get_paid_monthly_datatables_query($payment_no) {
		  $this->db->select('*');
		  $this->db->from($this->table_1_monthlypaid . ' as tbl_1');
		  $this->db->join($this->table_2_monthlypaid . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id', 'left');
		  $this->db->join($this->table_3_monthlypaid . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
		  $this->db->join($this->table_4_monthlypaid . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
		  $this->db->join($this->table_5_monthlypaid . ' as tbl_5', 'tbl_1.hp_id = tbl_5.hp_id');
		  $this->db->join($this->table_6_monthlypaid . ' as tbl_6', 'tbl_4.business_unit = tbl_6.business_unit');
		  $this->db->join($this->table_7_monthlypaid . ' as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id');
		  $this->db->where('tbl_1.payment_no', $payment_no);
		  $this->db->where('tbl_1.status', 'Paid');
	  }
   
	  public function monthly_paid_bill_datatable($payment_no) {
		  $this->_get_paid_monthly_datatables_query($payment_no);
		  if ($_POST['length'] != -1)
			   $this->db->limit($_POST['length'], $_POST['start']);
		   $query = $this->db->get();
		   return $query->result_array();
	  }
	   // end datatable


	   function get_loa_info($loa_id){
        return $this->db->get_where('loa_requests', ['loa_id' => $loa_id])->row_array();
    }

    function get_noa_info($noa_id){
        return $this->db->get_where('noa_requests', ['noa_id' => $noa_id])->row_array();
    }

	function submit_audited_bill($user) {
		$this->db->set('audited_on', date('Y-m-d'))
				->set('status', 'Audited')
				->set('audited_by', $user)
				->where('payment_no', $this->input->post('payment_no'));
		return $this->db->update('monthly_payable');
	}

	function db_get_company_doctors() {
		$this->db->select('*')
				 ->from('company_doctors as t1')
				 ->join('user_accounts as t2', 't1.doctor_id = t2.doctor_id');
		return $this->db->get()->result_array();
	  }

	function get_loa_noa_billing_by_id($billing_id){
		$this->db->select('*')
				->from('billing as tbl_1')
				->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
				->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
				->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
				->join('healthcare_providers as tbl_5', 'tbl_1.hp_id = tbl_5.hp_id')
				->where('tbl_1.billing_id', $billing_id);
		return $this->db->get()->row_array();
	}

	function get_approved_by_doctor($id) {
		return $this->db->get_where('company_doctors',['doctor_id' => $id])->row_array();
	}
	
	function db_get_cost_types() {
		$query = $this->db->get('cost_types');
		return $query->result_array();
	}

	
	var $charge_table_1 = 'billing';
	var $charge_table_6 = 'loa_requests';
	var $charge_table_7 = 'noa_requests';
	var $charge_table_5 = 'members';
	private function _get_get_charging_for_report_query() {
	 $this->db->from($this->charge_table_1 . ' as tbl_1')
			 ->join($this->charge_table_6 . ' as tbl_6', 'tbl_1.loa_id = tbl_6.loa_id', 'left')
			 ->join($this->charge_table_7 . ' as tbl_7', 'tbl_1.noa_id = tbl_7.noa_id', 'left')
			 ->join($this->charge_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
			 ->where('tbl_1.status', 'Paid')
			 ->where('tbl_1.bu_charging_status', '') //erase this code before dryrun
			 ->where('tbl_1.company_charge !=', '')
			 ->order_by('tbl_1.request_date', 'asc');

		 if($this->input->post('filter')){
			 $this->db->like('tbl_5.business_unit', $this->input->post('filter'));
		 }
		 if ($this->input->post('start_date')) {
			 $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
			 $this->db->where('tbl_1.request_date >=', $startDate);
		 }
		 if ($this->input->post('end_date')){
			 $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
			 $this->db->where('tbl_1.request_date <=', $endDate);
		 }
	}

	function get_charging_for_report() {
	$this->_get_get_charging_for_report_query();
	if ($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
	$query = $this->db->get();
	return $query->result_array();
	}

	function get_business_units() {
        return $this->db->get('locate_business_unit')->result_array();
    }

	var $rcv_charge_table_1 = 'billing';
	var $rcv_charge_table_5 = 'members';
	private function _fetch_receivables_bu_query($bu_status) {
	$this->db->from($this->rcv_charge_table_1 . ' as tbl_1')
			->join($this->rcv_charge_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
			->where('tbl_1.bu_charging_status', $bu_status);

		 if($this->input->post('filter')){
			 $this->db->like('tbl_5.business_unit', $this->input->post('filter'));
		 }
	}

	function fetch_receivables_bu($bu_status) {
	$this->_fetch_receivables_bu_query($bu_status);
	if ($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
	$query = $this->db->get();
	return $query->result_array();
	}

	function get_billing_id($charging_no) {
        return $this->db->get_where('billing', ['bu_charging_no' => $charging_no])->result_array();
    }

	function get_bu_charges_info($billing_id) {
        $this->db->from('billing as tbl_1');
        $this->db->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
        $this->db->where_in('tbl_1.billing_id', $billing_id);
        $this->db->order_by('tbl_1.request_date', 'asc');
        return $this->db->get()->result_array();
     }

	 function get_receivables_charging($charge_no) {
        $this->db->from('billing as tbl_1');
        $this->db->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
        $this->db->where('tbl_1.bu_charging_no', $charge_no);
        $this->db->order_by('tbl_1.request_date', 'asc');
        return $this->db->get()->result_array();
     }

	 function get_charge_details($billing_id) {
		return $this->db->get_where('billing', ['billing_id' => $billing_id])->row_array();
	 }

	// Start of server-side processing datatables
	var $table_payment_1 = 'payment_details';
	var $table_payment_2 = 'billing';
	var $column_payment_order = ['payment_no', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank', NULL]; //set column field database for datatable orderable
	var $column_payment_search = ['payment_no', 'cv_number', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank']; //set column field database for datatable searchable 
	var $order_payment = ['details_id' => 'desc']; // default order 

	private function _get_payment_datatables_query() {

		$this->db->from($this->table_payment_1. ' as tbl_1'); 
		$this->db->join($this->table_payment_2. ' as tbl_2', 'tbl_1.details_no = tbl_2.details_no');
		$i = 0;

		if($this->input->post('filter') == 'nonaffiliated'){
			$this->db->where('tbl_1.acc_number', '');
		}else{
			$this->db->like('tbl_1.hp_id', $this->input->post('filter'));
		}


		// loop column 
		foreach ($this->column_payment_search as $item) {
			// if datatable send POST for search
			if ($_POST['search']['value']) {
				// first loop
				if ($i === 0) {
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_payment_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		// here order processing
		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_payment_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_payment)) {
			$order = $this->order_payment;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_payment_datatables() {
		$this->_get_payment_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']); 
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_payment_filtered() {
		$this->_get_payment_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_payment_all() {
		$this->db->from($this->table_payment_1);
		return $this->db->count_all_results();
	}
	// End of server-side processing datatables
	
	function get_payment_details($details_id) {
        $this->db->select('*')
                ->from('payment_details as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('billing as tbl_3', 'tbl_1.details_no = tbl_3.details_no')
                ->join('monthly_payable as tbl_4', 'tbl_3.payment_no = tbl_4.payment_no')
                ->where('tbl_1.details_id', $details_id);
        return $this->db->get()->row_array();
    }

    function get_loa($details_no) {
        $this->db->select('*');
        $this->db->from('billing as tbl_1');
        if('tbl_1.loa_id' !=''){
            $this->db->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
        }
        $this->db->where('details_no', $details_no);
        return $this->db->get()->result_array();
    }

    function get_noa($details_no) {
        $this->db->select('*');
        $this->db->from('billing as tbl_1');
        if('tbl_1.noa_id' !=''){
            $this->db->join('noa_requests as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id');
        }
        $this->db->where('details_no', $details_no);
        return $this->db->get()->result_array();
    }

	private function fetchLedgerQuery()
    {
        $this->db->from('billing as tbl_1')
            ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
            ->join('payment_details as tbl_6', 'tbl_1.details_no = tbl_6.details_no')
            ->where('tbl_1.status', 'Paid')
            ->order_by('tbl_6.details_id', 'asc');
    
        if ($this->input->post('year')) {  // <--comment this row if needed to display the current year paid bill if the year is empty to avoid lags
            $year = $this->input->post('year');
            if(!empty($year)){
                $this->db->where("YEAR(tbl_6.date_add)", $year);
            }else{
                $this->db->where("YEAR(tbl_6.date_add)", date('Y'));
            }
        }
        if ($this->input->post('month')) {
            $month = $this->input->post('month');
            $this->db->where("MONTH(tbl_6.date_add)", $month);
        }
        if ($this->input->post('bu_filter')) {
            $bu_filter = $this->input->post('bu_filter');
            $this->db->where("tbl_5.business_unit", $bu_filter);
        }
    }      
    
    function get_debit_credit_yearly()
    {
        $this->fetchLedgerQuery(); // Call the function to set up the query conditions
        if (!empty($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

	function get_employee_paid_ledger($years, $months, $bu_unit) {
        $this->db->from('billing as tbl_1')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->join('payment_details as tbl_6', 'tbl_1.details_no = tbl_6.details_no')
                ->where('tbl_1.status', 'Paid')
                ->order_by('tbl_6.details_id', 'asc');

        if(!empty($years)){
            $this->db->where("YEAR(tbl_6.date_add)", $years);
        }
        if (!empty($months)) {
            $this->db->where("MONTH(tbl_6.date_add)", $months);
        }
        if (!empty($bu_unit)) {
            $this->db->where("tbl_5.business_unit", $bu_unit);
        }
        return $this->db->get()->result_array();
    }

	private function fetch_mbl_ledger() {
        $this->db->from('members as tbl_1')
            ->join('max_benefit_limits as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id', 'left')
            ->join('billing as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id','left')
            ->where('tbl_2.company_charge !=', 0)
            ->where('YEAR(tbl_2.billed_on)', date('Y'))
            ->order_by('tbl_2.billing_id', 'asc');
    
        if ($this->input->post('year')) {
            $year = $this->input->post('year');
            $this->db->where("YEAR(tbl_6.start_date)", $year);
        }
        if ($this->input->post('bu_filter')) {
            $bu_filter = $this->input->post('bu_filter');
            $this->db->where("tbl_1.business_unit", $bu_filter);
        }
    }

    function get_ledger_mbl() {
        $this->fetch_mbl_ledger(); // Call the function to set up the query conditions
        if (!empty($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

	private function fetch_history_mbl_ledger() {
        $this->db->from('members as tbl_1')
            ->join('mbl_history as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id', 'left')
            ->join('billing as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id','left')
            ->where('tbl_2.company_charge !=', 0)
            ->where('YEAR(tbl_2.billed_on) !=', date('Y'))
            ->order_by('tbl_2.billing_id', 'asc');
    
        if ($this->input->post('year')) {
            $year = $this->input->post('year');
            $this->db->where("YEAR(tbl_6.start_date)", $year);
        }
        if ($this->input->post('bu_filter')) {
            $bu_filter = $this->input->post('bu_filter');
            $this->db->where("tbl_1.business_unit", $bu_filter);
        }
    }

    function get_ledger_history_mbl() {
        $this->fetch_history_mbl_ledger(); // Call the function to set up the query conditions
        if (!empty($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

	function get_employee_ledger_mbl($years, $bu_unit) {
        $this->db->from('members as tbl_1')
                ->join('max_benefit_limits as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id', 'left')
                ->join('billing as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id','left')
                ->where('tbl_2.company_charge !=', 0)
                ->where('YEAR(tbl_2.billed_on)', date('Y'))
                ->order_by('tbl_2.billing_id', 'asc');

        if (!empty($years)) {
            $this->db->where("YEAR(tbl_6.start_date)", $years);
        }
        if (!empty($bu_unit)) {
            $this->db->where("tbl_1.business_unit", $bu_unit);
        }
        return $this->db->get()->result_array();
       
    }

	function get_employee_history_mbl($years, $bu_unit) {
        $this->db->from('members as tbl_1')
                ->join('mbl_history as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id', 'left')
                ->join('billing as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id','left')
                ->where('tbl_2.company_charge !=', 0)
                ->where('YEAR(tbl_2.billed_on) !=', date('Y'))
                ->order_by('tbl_2.billing_id', 'asc');

        if (!empty($years)) {
            $this->db->where("YEAR(tbl_6.start_date)", $years);
        }
        if (!empty($bu_unit)) {
            $this->db->where("tbl_1.business_unit", $bu_unit);
        }
        return $this->db->get()->result_array();
       
    }

    
}
