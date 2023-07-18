    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Initial_billing_model extends CI_Model {

        var $table_1 = 'initial_billing'; 
        var $column_order = array('billing_no','pdf_bill', 'date_uploaded', 'initial_bill');
        var $column_search = array('billing_no','pdf_bill', 'date_uploaded', 'initial_bill'); //set column field database for datatable searchable 
        var $order = array('id' => 'desc'); // default order 
        function insert_initial_bill($data){
            return $this->db->insert('initial_billing', $data);
        }
        // function get_initial_bill($noa_id, $hp_id, $status)
        // {
        //     $this->db->select('*');
        //     $this->db->from('initial_billing');
        //     $this->db->where('noa_id', $noa_id);
        //     $this->db->where('hp_id', $hp_id);
        //     $this->db->where('status', $status);
        //     $this->db->order_by('id', 'DESC'); // Order by date_uploaded in descending order
        //     return $this->db->get()->result();
        // }
        
        function get_initial_billing_no($noa_id, $hp_id, $status){
            $this->db->select('*');
            $this->db->from('initial_billing');
            $this->db->where('noa_id', $noa_id);
            $this->db->where('hp_id', $hp_id);
            $this->db->where('status', $status);
            $this->db->order_by('id', 'DESC'); // Order by date_uploaded in descending order
            $this->db->limit(1); // Limit the result to 1 row
            return $this->db->get()->row();
        }

        private function _get_datatables_query($noa_id, $hp_id, $status) {

            $this->db->from($this->table_1);
            $this->db->where('status', $status);
            $this->db->where('hp_id', $hp_id);
            $this->db->where('noa_id', $noa_id);
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
    
        
        function get_datatables($noa_id, $hp_id, $status) {
            $this->_get_datatables_query($noa_id, $hp_id, $status);
            if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result_array();
        }
    
        function count_filtered($noa_id, $hp_id, $status) {
            $this->_get_datatables_query($noa_id, $hp_id, $status);
            $query = $this->db->get();
            return $query->num_rows();
        }
    
        function count_all($noa_id, $hp_id, $status){
            $this->db->select('*');
            $this->db->from('initial_billing');
            $this->db->where('noa_id', $noa_id);
            $this->db->where('hp_id', $hp_id);
            $this->db->where('status', $status);
            return $this->db->count_all_results();
        }   
    }