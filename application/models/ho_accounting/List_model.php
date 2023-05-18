<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_model extends CI_Model{

    // Start of server-side processing datatables
	var $table_1 = 'billing';
    var $table_2 = 'members';
    var $table_3 = 'healthcare_providers';
	var $column_order = ['tbl_1.billing_no', 'tbl_2.first_name', 'tbl_1.billed_on', 'tbl_1.company_charge', NULL]; //set column field database for datatable orderable
	var $column_search = ['tbl_1.hp_id', 'tbl_1.billing_no', 'tbl_2.first_name', 'tbl_2.middle_name', 'tbl_2.last_name', 'tbl_3.hp_name', 'tbl_1.billed_on']; //set column field database for datatable searchable 
	var $order = ['tbl_1.billing_id' => 'asc']; // default order 

	private function _get_datatables_query($status) {

		$this->db->from($this->table_1. ' as tbl_1');
        $this->db->join($this->table_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
        $this->db->join($this->table_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
        $this->db->where('status', $status);
		$i = 0;

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

	function count_all() {
		$this->db->from($this->table_1)
                ->where('status', 'Billed');
		return $this->db->count_all_results();
	}
	// End of server-side processing datatables

    //Closed
    // Start of server-side processing datatables
	var $table_closed_1 = 'billing';
    var $table_closed_2 = 'members';
    var $table_closed_3 = 'healthcare_providers';
    var $table_closed_4 = 'payment_details';
	var $column_closed_order = ['tbl_1.billing_no', 'tbl_2.first_name', 'tbl_1.billed_on', 'tbl_1.company_charge', 'tbl_4.check_date', NULL,'tbl_1.status',  NULL]; //set column field database for datatable orderable
	var $column_closed_search = ['tbl_1.hp_id', 'tbl_1.billing_no', 'tbl_2.first_name', 'tbl_2.middle_name', 'tbl_2.last_name', 'tbl_3.hp_name', 'tbl_1.billed_on', 'tbl_1.bill_no', 'tbl_1.status', 'tbl_4.check_date']; //set column field database for datatable searchable 
	var $order_closed = ['tbl_1.billing_id' => 'asc']; // default order 

	private function _get_closed_datatables_query($status) {

		$this->db->from($this->table_closed_1. ' as tbl_1');
        $this->db->join($this->table_closed_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
        $this->db->join($this->table_closed_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
        $this->db->join($this->table_closed_4. ' as tbl_4', 'tbl_1.bill_no = tbl_4.bill_no');
        $this->db->where('status', $status);
		$i = 0;

        if($this->input->post('filter')){
			$this->db->like('tbl_1.hp_id', $this->input->post('filter'));
		}

		// loop column 
		foreach ($this->column_closed_search as $item) {
			// if datatable send POST for search
			if ($_POST['search']['value']) {
				// first loop
				if ($i === 0) {
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_closed_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		// here order processing
		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_closed_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_closed)) {
			$order = $this->order_closed;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_closed_datatables($status) {
		$this->_get_closed_datatables_query($status);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	function count_closed_filtered($status) {
		$this->_get_closed_datatables_query($status);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_closed_all() {
		$this->db->from($this->table_closed_1)
                ->where('status', 'Paid');
		return $this->db->count_all_results();
	}
	// End of server-side processing datatables
    

    function get_column_sum($hp_id, $startDate, $endDate, $status) {
        $this->db->select_sum('company_charge')
                ->where('status', $status)
                ->where('hp_id', $hp_id)
                ->where('billed_on >=', $startDate)
                ->where('billed_on <=', $endDate);
        $query = $this->db->get('billing');
        $result = $query->result_array();
        $sum = $result[0]['company_charge'];
        return $sum;
    }

    function get_sum_billed() {
        $this->db->select_sum('net_bill')
                ->where('done_matching', '1')
                ->where('status', 'Payable');
        $query = $this->db->get('billing');
        $result = $query->result_array();
        $sum = $result[0]['net_bill'];
        return $sum;
    }

    public function get_hc_provider(){
        return $this->db->get('healthcare_providers')->result_array();
    }

    function get_hcare_provider($hp_id){
		$this->db->select('*')
                 ->from('healthcare_providers')
                 ->where('hp_id', $hp_id);
		$query = $this->db->get();
		return $query->row_array();
	}

    function get_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.billing_id', $id);
        return $this->db->get()->row_array();
    }

    function get_billing($billing_no){
        $query = $this->db->get_where('billing', array('billing_no' => $billing_no));
        return $query->row_array();
    }
    
    function hp_approved_loa_count(){
        return $this->db->get_where('loa_requests', array('status' => 'Approved'))->num_rows();
    }
    
    function hp_approved_noa_count(){
        return $this->db->get_where('noa_requests', array('status' => 'Approved'))->num_rows();
    }
    
    function hp_payment_history_count(){
        return $this->db->get('payment_details')->num_rows();
    }

    function hp_billed_count(){
        return $this->db->get_where('billing', array('status' => 'Billed'))->num_rows();
    }

    function hp_paid_bill(){
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('status', 'Paid');
        return $this->db->get()->result_array();
    }

    function hp_count_paid($hp_id) {
        $this->db->select('*')
                ->from('billing')
                ->where('hp_id', $hp_id)
                ->where('status', 'Paid');
        return $this->db->get()->num_rows();
    }
    
    function db_get_hp_name($hp_id) {
        $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hp_id]);
        return $query->row_array();
    }
//Payment Details
    function add_payment_details($data) {
        return $this->db->insert('payment_details', $data);
    }

    function set_details_no($payment_no,$details_no) {
        $this->db->set('details_no', $details_no)
                ->set('status', 'Paid')
                ->where('status', 'Payment')
                ->where('payment_no', $payment_no);
        return $this->db->update('billing');
    }

    function set_monthly_payable($bill_no,$paid_by,$paid_on,$details_no) {
        $this->db->set('status', 'Paid')
                ->set('details_no', $details_no)
                ->set('paid_by', $paid_by)
                ->set('paid_on', $paid_on)
                ->where('bill_no', $bill_no);
        return $this->db->update('monthly_payable');
    }

    function get_loa_noa_id($payment_no) {
        $this->db->where('payment_no', $payment_no);
        return $this->db->get('billing')->result_array();
    }
    
    function set_loa_status($loa_id) {
        $this->db->set('status', 'Paid')
                ->where('loa_id', $loa_id);
        return $this->db->update('loa_requests');
    }

    function set_noa_status($noa_id) {
        $this->db->set('status', 'Paid')
                ->where('noa_id', $noa_id);
        return $this->db->update('noa_requests');
    }

    function get_employee_mbl($emp_id) {
        return $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id])->row_array();
    }

    function set_max_benefit_limit($emp_id, $remaining_mbl, $used_mbl) {
        $this->db->set('remaining_balance', $remaining_mbl)
                ->set('used_mbl', $used_mbl)
                ->where('emp_id', $emp_id);
        return $this->db->update('max_benefit_limits');
    }

    function set_after_mbl_paid_amount($billing_id, $before_mbl, $remaining_mbl, $paid_amount) {
        $this->db->set('after_remaining_bal', $remaining_mbl)
                ->set('before_remaining_bal', $before_mbl)
                ->set('total_paid_amount', $paid_amount)
                ->where('billing_id', $billing_id);
        return $this->db->update('billing');
    }
    
    // Start of server-side processing datatables
	var $table_payment_1 = 'payment_details';
    var $table_payment_2 = 'billing';
	var $column_payment_order = ['payment_no', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank', NULL]; //set column field database for datatable orderable
	var $column_payment_search = ['payment_no', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank']; //set column field database for datatable searchable 
	var $order_payment = ['details_id' => 'desc']; // default order 

	private function _get_payment_datatables_query() {

        $this->db->from($this->table_payment_1. ' as tbl_1'); 
        $this->db->join($this->table_payment_2. ' as tbl_2', 'tbl_1.details_no = tbl_2.details_no');
		$i = 0;

        if($this->input->post('filter')){
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

    function get_employee_payment($billing_id) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('payment_details as tbl_2', 'tbl_1.bill_no = tbl_2.bill_no')
                ->join('members as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
                ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
                ->where('tbl_1.billing_id', $billing_id);
        return $this->db->get()->row_array();
        
    }

    function fetch_for_payment_bill($status) {
        $this->db->select('*')
                ->from('monthly_payable as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('status', $status)
                ->order_by('bill_id', 'asc');
    
        if($this->input->post('filter')){
          $this->db->like('tbl_1.hp_id', $this->input->post('filter'));
        }
    
        return $this->db->get()->result_array();
    }

    function set_payment_no($hp_id, $month, $year, $payment_no) {
        $this->db->set('payment_no', $payment_no)
                ->where('hp_id', $hp_id)
                ->where('month', $month)
                ->where('year', $year);
        return $this->db->update('monthly_payable');
    }
    
    function fetch_monthly_billed_noa($hp_id) {
    $this->db->select('*')
            ->from('healthcare_providers')
            ->where('hp_id', $hp_id);
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


    function get_bill_nos($hp_id, $status) {
        $this->db->where('hp_id', $hp_id)
                ->where('status', $status);
        return $this->db->get('monthly_payable')->result_array();
    }

    function get_monthly_net_bill($bill_no) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('loa_requests as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left')
                ->join('noa_requests as tbl_4', 'tbl_1.noa_id = tbl_4.noa_id', 'left')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->where('tbl_1.bill_no', $bill_no);
        return $this->db->get()->result_array();
    }

    function get_payment_no($bill_no) {
        $this->db->select('payment_no')
                ->from('monthly_payable')
                ->where('bill_no', $bill_no);
        return $this->db->get()->result_array();
    }

    function get_total_bill($bill_no) {
        $this->db->select_sum('net_bill')
                ->from('billing')
                ->where('bill_no', $bill_no);
        $query = $this->db->get();
        $result = $query->result_array();
        $sum = $result[0]['net_bill'];
        return $sum;
    }

    function get_check_details($details_no) {
        $this->db->select('*')
                ->from('payment_details as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.details_no', $details_no);
        return $this->db->get()->row_array();
    }

     // Start of server-side processing datatables
   var $table_1_billed = 'billing';
   var $table_2_billed = 'noa_requests';
   var $table_3_billed = 'loa_requests';
   var $table_4_billed = 'members';
   var $table_5_billed = 'healthcare_providers';
   var $table_6_billed = 'max_benefit_limits';

   private function _get_billed_datatables_query() {
       $this->db->select('*');
       $this->db->from($this->table_1_billed . ' as tbl_1');
       $this->db->join($this->table_2_billed . ' as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id', 'left');
       $this->db->join($this->table_3_billed . ' as tbl_3', 'tbl_1.loa_id = tbl_3.loa_id', 'left');
       $this->db->join($this->table_4_billed . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id');
       $this->db->join($this->table_5_billed . ' as tbl_5', 'tbl_1.hp_id = tbl_5.hp_id');
       $this->db->join($this->table_6_billed . ' as tbl_6', 'tbl_1.emp_id = tbl_6.emp_id');
       $this->db->where('tbl_1.done_matching', '1');
       $this->db->where('tbl_1.status', 'Payable');

      if($this->input->post('hp_id')){
        $this->db->like('tbl_1.hp_id', $this->input->post('hp_id'));
      }
      if ($this->input->post('startDate')) {
        $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
        $this->db->where('tbl_1.billed_on >=', $startDate);
      }
      if ($this->input->post('endDate')){
        $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
        $this->db->where('tbl_1.billed_on <=', $endDate);
      }
      if($this->input->post('business_unit')){
        $this->db->like('tbl_4.business_unit', $this->input->post('business_unit'));
      }
   }

   public function get_for_payment_loa_noa() {
       $this->_get_billed_datatables_query();
       if ($this->input->post('length') != -1)
           $this->db->limit($this->input->post('length'), $this->input->post('start'));
       $query = $this->db->get();
       return $query->result_array();
   }
    // end datatable

    function get_business_units() {
        return $this->db->get('locate_business_unit')->result_array();
    }

    //billing for charging datatable
    var $charging_table_1 = 'billing';
    var $charging_table_2 = 'loa_requests';
    var $charging_table_3 = 'noa_requests';
    var $charging_table_4 = 'max_benefit_limits';
    var $charging_table_5 = 'members';
    private function _get_datatables_charging_query() {
    $this->db->from($this->charging_table_1 . ' as tbl_1')
            ->join($this->charging_table_2 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
            ->join($this->charging_table_3 . ' as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
            ->join($this->charging_table_4 . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
            ->join($this->charging_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
            ->where('tbl_1.done_matching', '1');

    if($this->input->post('hp_id')){
        $this->db->like('tbl_1.hp_id', $this->input->post('hp_id'));
    }
    if ($this->input->post('startDate')) {
    $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
    $this->db->where('tbl_1.billed_on >=', $startDate);
    }
    if ($this->input->post('endDate')){
    $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
    $this->db->where('tbl_1.billed_on <=', $endDate);
    }
    if($this->input->post('business_unit')){
    $this->db->like('tbl_5.business_unit', $this->input->post('business_unit'));
    }
    }

    function get_billed_for_charging() {
    $this->_get_datatables_charging_query();
    if ($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
    }
    //end billing for charging datatable

    function get_loa_info($loa_id){
        return $this->db->get_where('loa_requests', ['loa_id' => $loa_id])->row_array();
    }

    function get_noa_info($noa_id){
        return $this->db->get_where('noa_requests', ['noa_id' => $noa_id])->row_array();
    }

    function submit_forPayment_bill($payment_no) {
        $this->db->set('payment_no', $payment_no)
                ->set('status', 'Payment')
                ->where('done_matching', '1')
                ->where('status', 'Payable')
                ->where('hp_id', $this->input->post('hp_id'));
        $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
        $this->db->where('billed_on >=', $startDate);
        $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
        $this->db->where('billed_on <=', $endDate);
        return $this->db->update('billing');
    }

    function set_payment_no_date($payment_no,$user) {
        $data = array(
            'payment_no' => $payment_no,
            'hp_id' => $this->input->post('hp_id'),
            'startDate' => date('Y-m-d', strtotime($this->input->post('start_date'))),
            'endDate' => date('Y-m-d', strtotime($this->input->post('end_date'))),
            'total_payable' => floatval(str_replace(',','',$this->input->post('total_bill'))),
            'added_on' => date('Y-m-d'),
            'added_by' => $user
        );
    
        return $this->db->insert('monthly_payable', $data);
    }

    function fetch_for_payment_bills() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
                ->where('tbl_1.status', 'Payment');
        return $this->db->get()->result_array();
    }

    function fetch_paid_bills() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
                ->join('payment_details as tbl_4', 'tbl_1.details_no = tbl_4.details_no')
                ->where('tbl_1.status', 'Paid');
        return $this->db->get()->result_array();
    }

    function get_billed_hp_name($payment_no) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.payment_no', $payment_no);
        return $this->db->get()->result_array();
    }

    function get_billed_date($payment_no) {
        $this->db->select('*')
                ->from('monthly_payable as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.payment_no', $payment_no);
        return $this->db->get()->row_array();
    }

    function get_total_payables($payment_no) {
        $this->db->select_sum('company_charge');
        $this->db->select_sum('cash_advance');
        $this->db->where('payment_no', $payment_no);
        $query = $this->db->get('billing');
        $result = $query->result_array();
    
        $sum_company_charge = $result[0]['company_charge'];
        $sum_cash_advance = $result[0]['cash_advance'];
    
        $total_sum = $sum_company_charge + $sum_cash_advance;
    
        return $total_sum;
    }
    

    function get_print_billed_loa_noa() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
                ->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
                ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->join('locate_business_unit as tbl_6', 'tbl_5.business_unit = tbl_6.business_unit')
                ->join('max_benefit_limits as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id')
                ->where('tbl_1.hp_id', $this->input->post('hp_id'))
                ->where('tbl_5.business_unit', $this->input->post('bu_filter'));
        $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
        $this->db->where('tbl_1.billed_on >=', $startDate);
        $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
        $this->db->where('tbl_1.billed_on <=', $endDate);
        return $this->db->get()->result_array();
    }

       //billing for charging datatable
       var $paid_table_1 = 'billing';
       var $paid_table_2 = 'loa_requests';
       var $paid_table_3 = 'noa_requests';
       var $paid_table_4 = 'max_benefit_limits';
       var $paid_table_5 = 'members';
       private function _get_get_paid_for_report_query() {
       $this->db->from($this->paid_table_1 . ' as tbl_1')
               ->join($this->paid_table_2 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
               ->join($this->paid_table_3 . ' as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
               ->join($this->paid_table_4 . ' as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
               ->join($this->paid_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
               ->where('tbl_1.status', 'Paid');
   
            if($this->input->post('hp_id')){
                $this->db->like('tbl_1.hp_id', $this->input->post('hp_id'));
            }
            if ($this->input->post('startDate')) {
            $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
            $this->db->where('tbl_1.billed_on >=', $startDate);
            }
            if ($this->input->post('endDate')){
            $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
            $this->db->where('tbl_1.billed_on <=', $endDate);
            }
       }
   
       function get_paid_for_report() {
       $this->_get_get_paid_for_report_query();
       if ($_POST['length'] != -1)
           $this->db->limit($_POST['length'], $_POST['start']);
       $query = $this->db->get();
       return $query->result_array();
       }

    


//=================================================

    public function loa_member()
    {
        return $this->db->get('loa_requests')->result_array();
    }

    public function billingList()
    {

        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');

        $query = $this->db->get();
        return $query->result_array();
    }

    

    public function billing_search($search)
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');
        $this->db->like('billing_no', $search);
        $query = $this->db->get();
        return $query->result_array();
    }



    public function getLoaClose()
    {
        $this->db->select('*');
        $this->db->from('loa_requests');
        $this->db->where('status', 'Closed');

        $query = $this->db->get();
        return $query->result();
    }

    public function getNoaClose()
    {
        $this->db->select('*');
        $this->db->from('noa_requests');
        $this->db->where('status', 'Closed');

        $query = $this->db->get();
        return $query->result();
    }



    public function getBilling()
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');

        $query = $this->db->get();
        return $query->result_array();
    }


    public function getInHospitalDate($hospital, $month, $year)
    {
        $this->db->select('*');
        $this->db->from('billing');
        $this->db->join('members', 'billing.emp_id = members.emp_id');
        $this->db->where('MONTH(billing.billing_date)', $month);
        $this->db->where('YEAR(billing.billing_date)', $year);
        $this->db->where('hp_id', $hospital);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_loa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.loa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_noa_billing_info($id){
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_services, tbl_1.total_medications, tbl_1.total_pro_fees, tbl_1.total_room_board, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.noa_id', $id);
        return $this->db->get()->row_array();
    }

    function get_billing_medications($billing_no){
        $query = $this->db->get_where('billing_medications', ['billing_no' => $billing_no]);
        return $query->result_array();
     }

     function get_billing_professional_fees($billing_no){
        $query = $this->db->get_where('billing_professional_fees', ['billing_no' => $billing_no]);
        return $query->result_array();
     }

     function get_billing_room_boards($billing_no){
        $query = $this->db->get_where('billing_room_boards', ['billing_no' => $billing_no]);
        return $query->result_array();
    }

    function get_billing_deductions($billing_no){
        $query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
        return $query->result_array();
     }

     function get_member_mbl($emp_id) {
        $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
        return $query->row_array();
    } 

    function get_billing_services($billing_no){
        $query = $this->db->get_where('billing_services', ['billing_no' => $billing_no]);
        return $query->result_array();
     }


}
