    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Initial_billing_model extends CI_Model {

        function insert_initial_bill($data){
            return $this->db->insert('initial_billing', $data);
        }
        function get_initial_bill($noa_id, $hp_id, $status)
        {
            $this->db->select('*');
            $this->db->from('initial_billing');
            $this->db->where('noa_id', $noa_id);
            $this->db->where('hp_id', $hp_id);
            $this->db->where('status', $status);
            $this->db->order_by('id', 'DESC'); // Order by date_uploaded in descending order
            return $this->db->get()->result();
        }

        function count_all($noa_id,$hp_id,$status){
            $this->db->select('*');
            $this->db->from('initial_billing');
            $this->db->where('noa_id', $noa_id);
            $this->db->where('hp_id', $hp_id);
            $this->db->where('status', $status);
            return $this->db->count_all_results();
        }
        
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
    }