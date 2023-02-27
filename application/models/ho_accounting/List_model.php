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

	private function _get_datatables_query() {

		$this->db->from($this->table_1. ' as tbl_1');
        $this->db->join($this->table_2. ' as tbl_2', 'tbl_1.emp_id = tbl_2.emp_id');
        $this->db->join($this->table_3. ' as tbl_3', 'tbl_1.hp_id = tbl_3.hp_id');
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
		$this->db->from($this->table_1);
		return $this->db->count_all_results();
	}
	// End of server-side processing datatables

    public function get_hc_provider(){
        return $this->db->get('healthcare_providers')->result_array();
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
