<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_model extends CI_Model{

    // Start of server-side processing datatables
	var $table_1 = 'billing';
    var $table_2 = 'members';
    var $table_3 = 'healthcare_providers';
	var $column_order = ['tbl_1.billing_no', 'tbl_2.first_name', 'tbl_1.request_date', 'tbl_1.company_charge', NULL]; //set column field database for datatable orderable
	var $column_search = ['tbl_1.hp_id', 'tbl_1.billing_no', 'tbl_2.first_name', 'tbl_2.middle_name', 'tbl_2.last_name', 'tbl_3.hp_name', 'tbl_1.request_date']; //set column field database for datatable searchable 
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
            $this->db->where('tbl_1.request_date >=', $startDate);
        }

        if ($this->input->post('endDate')){
            $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
            $this->db->where('tbl_1.request_date <=', $endDate);
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
                ->where('request_date >=', $startDate)
                ->where('request_date <=', $endDate);
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
        return $this->db->get_where('billing', array('status' => 'Payable'))->num_rows();
    }

    function hp_paid_count() {
        $this->db->where('status', 'Paid')
                ->where('company_charge !=', '')
                ->where('bu_charging_status', '');
        return $this->db->get('billing')->num_rows();
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
        if(!empty($hp_id)){
            $query = $this->db->get_where('healthcare_providers', ['hp_id' => $hp_id]);
            return $query->row_array();
        }
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

    function set_monthly_payable($payment_no,$paid_by,$paid_on) {
        $this->db->set('status', 'Paid')
                ->set('paid_by', $paid_by)
                ->set('paid_on', $paid_on)
                ->where('payment_no', $payment_no);
        return $this->db->update('monthly_payable');
    }

    function get_loa_noa_id($payment_no) {
        $this->db->where('payment_no', $payment_no);
        return $this->db->get('billing')->result_array();
    }

    function update_payable($bill_no) {
        $this->db->where('bill_no', $bill_no)
                ->set('status', 'Paid');
        return $this->db->update('monthly_payable');
    }

    function insert_total_paid($billing_id, $total_paid) {
        $this->db->set('total_paid_amount',$total_paid)
                ->where('billing_id',$billing_id);
        return $this->db->update('billing');
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
       $this->db->order_by('tbl_1.billing_id','asc');

      if($this->input->post('hp_id')){
        $this->db->like('tbl_1.hp_id', $this->input->post('hp_id'));
      }
      if ($this->input->post('startDate')) {
        $startDate = date('Y-m-d', strtotime($this->input->post('startDate')));
        $this->db->where('tbl_1.request_date >=', $startDate);
      }
      if ($this->input->post('endDate')){
        $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
        $this->db->where('tbl_1.request_date <=', $endDate);
      }
   }

   function get_for_payment_loa_noa() {
       $this->_get_billed_datatables_query();
       if ($this->input->post('length') != -1)
           $this->db->limit($this->input->post('length'), $this->input->post('start'));
       $query = $this->db->get();
       return $query->result_array();
   }
    // end datatable

    function get_approved_advance($billing_id) {
        $this->db->select('approved_amount')
                ->from('cash_advance')
                ->where('billing_id',$billing_id)
                ->where('status','Approved');
        return $this->db->get()->row_array();
    }

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
    $this->db->where('tbl_1.request_date >=', $startDate);
    }
    if ($this->input->post('endDate')){
    $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
    $this->db->where('tbl_1.request_date <=', $endDate);
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
                ->where('status', 'Payable');
    
        if (!empty($this->input->post('hp_id'))) {
            $this->db->where('hp_id', $this->input->post('hp_id'));
        }
        if (!empty($this->input->post('start_date'))) {
            $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
            $this->db->where('request_date >=', $startDate);
        }
        if (!empty($this->input->post('end_date'))) {
            $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
            $this->db->where('request_date <=', $endDate);
        }
        if (!empty($this->input->post('hp_id'))) {
            return $this->db->update('billing');
        }
    }
    

    function set_payment_no_date($payment_no,$user) {
        if(!empty($this->input->post('start_date'))){
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
        }else{
            $start_date = '';
        }
        if(!empty($this->input->post('end_date'))){
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
        }else{
            $end_date = '';
        }

        $data = array(
            'payment_no' => $payment_no,
            'hp_id' => $this->input->post('hp_id'),
            'startDate' => $start_date,
            'endDate' => $end_date,
            'total_payable' => floatval(str_replace(',','',$this->input->post('total_bill'))),
            'added_on' => date('Y-m-d'),
            'added_by' => $user
        );
        if(!empty($this->input->post('hp_id'))){
            return $this->db->insert('monthly_payable', $data);
        }
        
    }

    function fetch_for_payment_bills() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
                ->where('tbl_1.status', 'Payment')
                ->order_by('tbl_3.bill_id', 'desc');
        return $this->db->get()->result_array();
    }

    function fetch_paid_bills() {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->join('monthly_payable as tbl_3', 'tbl_1.payment_no = tbl_3.payment_no')
                ->join('payment_details as tbl_4', 'tbl_1.details_no = tbl_4.details_no')
                ->where('tbl_1.status', 'Paid')
                ->order_by('tbl_3.bill_id', 'desc');
        return $this->db->get()->result_array();
    }

    function get_billed_hp_name($payment_no) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.payment_no', $payment_no);
        return $this->db->get()->result_array();
    }

    function get_payment_nos($bill_id) {
        return $this->db->get_where('monthly_payable', ['bill_id' => $bill_id])->row_array();
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
    
    function get_print_billed_loa_noa($hp_id,$start_date,$end_date) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
                ->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
                ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->join('locate_business_unit as tbl_6', 'tbl_5.business_unit = tbl_6.business_unit')
                ->join('max_benefit_limits as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id')
                ->where('tbl_1.status', 'Payable');
        if(!empty($hp_id)){
            $this->db->where('tbl_1.hp_id', $hp_id);
        }
        if(!empty($start_date)){
            $startDate = date('Y-m-d', strtotime($start_date));
            $this->db->where('tbl_1.request_date >=', $startDate);
        }
        if(!empty($end_date)){
            $endDate = date('Y-m-d', strtotime($end_date));
            $this->db->where('tbl_1.request_date <=', $endDate);
        }
        
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
            $this->db->where('tbl_1.request_date >=', $startDate);
            }
            if ($this->input->post('endDate')){
            $endDate = date('Y-m-d', strtotime($this->input->post('endDate')));
            $this->db->where('tbl_1.request_date <=', $endDate);
            }
       }
   
       function get_paid_for_report() {
       $this->_get_get_paid_for_report_query();
       if ($_POST['length'] != -1)
           $this->db->limit($_POST['length'], $_POST['start']);
       $query = $this->db->get();
       return $query->result_array();
       }

       function set_new_cash_advance($bill_no,$new_advance) {
        $this->db->set('cash_advance', $new_advance);
            $this->db->where('billing_no', $bill_no);
        return $this->db->update('billing');
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

       var $paid_charge_table_1 = 'billing';
       var $paid_charge_table_5 = 'members';
       private function _get_paid_charging_for_report_query() {
       $this->db->from($this->paid_charge_table_1 . ' as tbl_1')
               ->join($this->paid_charge_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
               ->where('tbl_1.status', 'Paid')
               ->where('tbl_1.bu_charging_status', 'Paid');
   
            if($this->input->post('filter')){
                $this->db->like('tbl_5.business_unit', $this->input->post('filter'));
            }
       }
   
       function get_paid_charging_for_report() {
       $this->_get_paid_charging_for_report_query();
       if ($_POST['length'] != -1)
           $this->db->limit($_POST['length'], $_POST['start']);
       $query = $this->db->get();
       return $query->result_array();
       }

       function get_member_info($empId) {
        return $this->db->get_where('members', ['emp_id' => $empId])->row_array();
       }

       var $paid_details_table_1 = 'billing';
       var $padi_details_table_2 = 'loa_requests';
       var $paid_details_table_3 = 'noa_requests';
       var $paid_details_table_5 = 'members';
       private function _get_paid_details_for_report_query() {
       $this->db->from($this->paid_details_table_1 . ' as tbl_1')
                ->join($this->padi_details_table_2 . ' as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
                ->join($this->paid_details_table_3 . ' as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
                ->join($this->paid_details_table_5 . ' as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->where('tbl_1.emp_id', $this->input->post('emp_id'))
                ->where('tbl_1.status', 'Paid')
                ->where('tbl_1.bu_charging_status', 'Paid')
                ->order_by('tbl_1.billing_id', 'desc');
       }

       function get_paid_charging_details() {
        $this->_get_paid_details_for_report_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
       }

       function get_for_payment_bills($payment_no) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id', 'left')
                ->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id', 'left')
                ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->join('locate_business_unit as tbl_6', 'tbl_5.business_unit = tbl_6.business_unit')
                ->join('max_benefit_limits as tbl_7', 'tbl_1.emp_id = tbl_7.emp_id')
                ->where('tbl_1.payment_no', $payment_no);

        return $this->db->get()->result_array();
       }

       function get_bill_payment_no($hp_id,$start_date,$end_date) {
        $this->db->select('*')
                ->from('monthly_payable')
                ->where('hp_id',$hp_id)
                ->where('startDate',$start_date)
                ->where('endDate',$end_date);
        return $this->db->get()->row_array();
       }

       function get_bill_payment_details($payment_no) {
        return $this->db->get_where('monthly_payable', ['payment_no' => $payment_no])->row_array();
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

    function get_billing_id_by_bu() {
        $this->db->select('billing_id, business_unit')
                ->from('billing as tbl_1')
                ->join('members as tbl_5', 'tbl_1.emp_id = tbl_5.emp_id')
                ->where('tbl_1.status', 'Paid')
                ->where('tbl_1.bu_charging_status', '');

        if($this->input->post('bu_filter')){
            $this->db->like('tbl_5.business_unit', $this->input->post('bu_filter'));
        }
        if ($this->input->post('start_date')) {
            $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
            $this->db->where('tbl_1.request_date >=', $startDate);
        }
        if ($this->input->post('end_date')){
            $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
            $this->db->where('tbl_1.request_date <=', $endDate);
        }

        return $this->db->get()->result_array();
    }

    function tag_bu_charges($charging_no, $billing_ids) {
        $this->db->set('bu_charging_status', 'Receivable')
                ->set('bu_charging_no', $charging_no)
                ->set('bu_generated_on', date('Y-m-d'))
                ->where_in('billing_id', $billing_ids);

        // if(!empty($this->input->post('filter'))){
            return $this->db->update('billing');
        // }
    }
    
    function get_billing_id($charging_no) {
        return $this->db->get_where('billing', ['bu_charging_no' => $charging_no])->result_array();
    }

    function tag_charge_as_paid($charging_no, $bu_proof_payment) {
        $this->db->set('bu_charging_status', 'Paid')
                ->set('bu_tagged_paid_on', date('Y-m-d'))
                ->set('bu_proof_payment', $bu_proof_payment)
                ->where('bu_charging_no', $charging_no);
        return $this->db->update('billing');
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

     function get_bu_charging($charging_no) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id','left')
                ->join('noa_requests as tbl_3', 'tbl_1.noa_id = tbl_3.noa_id','left')
                ->join('members as tbl_4', 'tbl_1.emp_id = tbl_4.emp_id')
                ->where('tbl_1.status', 'Paid')
                ->where('tbl_1.bu_charging_status', 'Receivable')
                ->where('tbl_1.bu_charging_no', $charging_no)
                ->order_by('tbl_1.request_date', 'asc');
        return $this->db->get()->result_array();
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

     function get_paid_bu_charging($business_unit) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                ->where('tbl_1.status', 'Paid')
                ->where('tbl_1.bu_charging_status', 'Paid')
                ->where('tbl_2.business_unit', $business_unit);
        return $this->db->get()->result_array();
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

     function get_debit_credit() {
        $this->db->select('*')
            ->from('billing as tbl_1')
            ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
            ->join('payment_details as tbl_3', 'tbl_1.details_no = tbl_3.details_no')
            ->where('tbl_1.status', 'Paid');
            
        if (!empty($this->input->post('year'))) {
            $year = $this->input->post('year');
            $this->db->where("YEAR(tbl_3.date_add)", $year);
        }
        
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

    function submit_bank_accounts($data) {
        
        return $this->db->insert('bank_accounts', $data);
    }

    private function fetch_bank_Accounts() {
        $this->db->from('bank_accounts as tbl_1');
        $this->db->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id');
        $this->db->order_by('tbl_1.bank_id','desc');
    }

    function get_bank_accounts() {
        $this->fetch_bank_Accounts(); // Call the function to set up the query conditions
        if (!empty($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    function delete_bank_account() {
        $this->db->where('bank_id', $this->input->post('bank_id'))
		         ->delete('bank_accounts');
		return $this->db->affected_rows() > 0 ? true : false;
    }

    function update_bank_account($user) {
        $data = [
			'hp_id' => $this->input->post('hp_id'),
			'bank_name' => $this->input->post('bank_name'),
			'account_name' => $this->input->post('account_name'),
			'account_number' => $this->input->post('account_num'),
			'updated_on' => date('Y-m-d'),
			'updated_by' => $user,
		];
        $this->db->where('bank_id', $this->input->post('bank_id'));
        return $this->db->update('bank_accounts', $data);
    }

    function get_bank_details($hp_id) {
        return $this->db->get_where('bank_accounts',['hp_id' => $hp_id])->result_array();
    }

    function get_bank_numbers($bank_id) {
        return $this->db->get_where('bank_accounts',['bank_id' => $bank_id])->row_array();
    }
    


}
