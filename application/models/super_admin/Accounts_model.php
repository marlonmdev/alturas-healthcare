<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounts_model extends CI_Model {

	// Start of server-side processing datatables
	var $table = 'user_accounts';
	var $column_order = array('user_id', 'full_name', 'user_role', null, 'status', null); //set column field database for datatable orderable
	var $column_search = array('user_id', 'full_name', 'user_role', 'status'); //set column field database for datatable searchable 
	var $order = array('user_id' => 'desc'); // default order 

	private function _get_datatables_query() {
		if($this->input->post('filter')){
			$this->db->like('user_role', $this->input->post('filter'));
		}

		$this->db->from($this->table);
		$i = 0;
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

	function db_insert_account($post_data) {
		return $this->db->insert('user_accounts', $post_data);
	}

	function db_check_username($username) {
		$query = $this->db->get_where('user_accounts', array('username' => $username));
		return $query->num_rows() > 0 ? true : false;
	}

	function check_member_account_exist($emp_id) {
		$query = $this->db->get_where('user_accounts', array('emp_id' => $emp_id));
		return $query->num_rows() === 1 ? true : false;
	}

	function db_get_all_accounts() {
		$this->db->order_by('user_id', 'DESC');
		return $this->db->get('user_accounts')->result_array();
	}

	function db_get_designated_healthcare_provider($hp_id) {
		$query = $this->db->get_where('healthcare_providers', array('hp_id' => $hp_id));
		return $query->row_array();
	}

	function db_get_user_by_id($user_id) {
		$query = $this->db->get_where('user_accounts', array('user_id' => $user_id));
		return $query->row_array();
	}

	function db_get_user_photo($emp_id) {
		$this->db->select('emp_id, photo')
						 ->from('members')
						 ->where('emp_id', $emp_id);
		return $this->db->get()->row_array();
	}

	function db_get_current_user_status($user_id) {
		$this->db->where('user_id', $user_id);
		return $this->db->get('user_accounts')->row_array();
	}

	function db_update_user_password($user_id, $updated_password, $updated_on, $updated_by) {
		$data = [
			'password' => $updated_password,
			'updated_on' => $updated_on,
			'updated_by' => $updated_by,
		];
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $data);
	}

	function update_user_details($user_id, $post_data) {
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $post_data);
	}

	function db_activate_user_status($user_id) {
		$data = [
			'status' => 'Active',
		];
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $data);
	}

	function db_deactivate_user_status($user_id) {
		$data = [
			'status' => 'Blocked',
		];
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $data);
	}

	function db_reset_user_password($user_id, $post_data) {
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_accounts', $post_data);
	}

	function db_delete_user_account($user_id) {
		$this->db->where('user_id', $user_id)
						 ->delete('user_accounts');
		return $this->db->affected_rows() > 0 ? true : false;
	}
}
