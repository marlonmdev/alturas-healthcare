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
	var $column_closed_search = ['tbl_1.hp_id', 'tbl_1.billing_no', 'tbl_2.first_name', 'tbl_2.middle_name', 'tbl_2.last_name', 'tbl_3.hp_name', 'tbl_1.billed_on', 'tbl_1.payment_no', 'tbl_1.status', 'tbl_4.check_date']; //set column field database for datatable searchable 
	var $order_closed = ['tbl_1.billing_id' => 'asc']; // default order 

	private function _get_closed_datatables_query($status) {

		$this->db->from($this->table_closed_1. ' as tbl_1');
        $this->db->join($this->table_closed_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
        $this->db->join($this->table_closed_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
        $this->db->join($this->table_closed_4. ' as tbl_4', 'tbl_1.payment_no = tbl_4.payment_no');
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
        $this->db->select('tbl_1.billing_id, tbl_1.billing_no, tbl_1.emp_id, tbl_1.hp_id, tbl_1.total_bill, tbl_1.total_deduction, tbl_1.net_bill, tbl_1.company_charge, tbl_1.personal_charge, tbl_1.before_remaining_bal, tbl_1.after_remaining_bal, tbl_1.billed_by, tbl_1.billed_on, tbl_2.health_card_no, tbl_2.first_name, tbl_2.middle_name, tbl_2.last_name, tbl_2.suffix, tbl_3.hp_name')
                 ->from('billing as tbl_1')
                 ->join('members as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id')
                 ->join('healthcare_providers as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id')
                 ->where('tbl_1.billing_id', $id);
        return $this->db->get()->row_array();
    }

    function get_member_mbl($emp_id) {
        $query = $this->db->get_where('max_benefit_limits', ['emp_id' => $emp_id]);
        return $query->row_array();
    } 

    function get_billing_services($billing_no){
        $query = $this->db->get_where('billing_services', ['billing_no' => $billing_no]);
        return $query->result_array();
     }

    function get_billing_deductions($billing_no){
        $query = $this->db->get_where('billing_deductions', ['billing_no' => $billing_no]);
        return $query->result_array();
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

    function set_payment_no($hp_id, $startDate, $endDate, $payment_no, $status) {
        $this->db->set('payment_no', $payment_no)
                ->set('status', 'Paid')
                ->where('status', $status)
                ->where('hp_id', $hp_id)
                ->where('billed_on >=', $startDate)
                ->where('billed_on <=', $endDate);
        return $this->db->update('billing');
    }

    function get_loa_noa_id($hp_id, $startDate, $endDate) {
        $this->db->where('hp_id', $hp_id)
                 ->where('billed_on >=', $startDate)
                 ->where('billed_on <=', $endDate);
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
    
    // Start of server-side processing datatables
	var $table_payment_1 = 'payment_details';
	var $column_payment_order = ['payment_no', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank', NULL]; //set column field database for datatable orderable
	var $column_payment_search = ['payment_no', 'acc_number', 'acc_name', 'check_num', 'check_date', 'bank']; //set column field database for datatable searchable 
	var $order_payment = ['payment_id' => 'desc']; // default order 

	private function _get_payment_datatables_query() {

		$this->db->from($this->table_payment_1);
		$i = 0;

        if($this->input->post('filter')){
			$this->db->like('hp_id', $this->input->post('filter'));
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

    function get_payment_details($payment_id) {
        $this->db->select('*')
                ->from('payment_details as tbl_1')
                ->join('healthcare_providers as tbl_2', 'tbl_1.hp_id = tbl_2.hp_id')
                ->where('tbl_1.payment_id', $payment_id);
        return $this->db->get()->row_array();
    }

    function get_loa($payment_no) {
        $this->db->select('*');
        $this->db->from('billing as tbl_1');
        if('tbl_1.loa_id' !=''){
            $this->db->join('loa_requests as tbl_2', 'tbl_1.loa_id = tbl_2.loa_id');
        }
        $this->db->where('payment_no', $payment_no);
        return $this->db->get()->result_array();
    }

    function get_noa($payment_no) {
        $this->db->select('*');
        $this->db->from('billing as tbl_1');
        if('tbl_1.noa_id' !=''){
            $this->db->join('noa_requests as tbl_2', 'tbl_1.noa_id = tbl_2.noa_id');
        }
        $this->db->where('payment_no', $payment_no);
        return $this->db->get()->result_array();
    }

    function get_employee_payment($billing_id) {
        $this->db->select('*')
                ->from('billing as tbl_1')
                ->join('payment_details as tbl_2', 'tbl_1.payment_no = tbl_2.payment_no')
                ->join('members as tbl_3', 'tbl_1.emp_id = tbl_3.emp_id')
                ->join('healthcare_providers as tbl_4', 'tbl_1.hp_id = tbl_4.hp_id')
                ->where('tbl_1.billing_id', $billing_id);
        return $this->db->get()->row_array();
        
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

}
