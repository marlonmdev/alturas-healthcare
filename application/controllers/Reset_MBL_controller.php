<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reset_MBL_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
		$this->load->model('healthcare_coordinator/members_model');

    }

    function yearly_reset_mbl() {
        // inserting the mbl history
        $history = $this->members_model->db_get_mbl_history();
        foreach($history as $mbl){
            $post_data = [
                'emp_id' => $mbl['emp_id'],
                'max_benefit_limit' => $mbl['max_benefit_limit'],
                'used_mbl' => $mbl['used_mbl'],
                'remaining_balance' => $mbl['remaining_balance'],
                'start_date' => $mbl['start_date'],
                'end_date' => date('Y-m-d'),
            ];

            $this->members_model->insert_mbl_history($post_data);
        }

        // updating the new mbl
        $result = $this->members_model->db_get_hc_member_details();

        foreach($result as $data){
            $date_regularized = $data['date_regularized'];
            $pos_level =  $data['position_level'];

            $year_regular = date("Y", strtotime($date_regularized));
            $current_year = date("Y");

            if ($year_regular === $current_year) {
                $current_mbl = $this->new_regular_mbl($date_regularized);
            } else {
                $current_mbl = $this->max_benefit_limit($pos_level);
            }

            $post_data = [
                'emp_id' => $data['emp_id'],
                'max_benefit_limit' => $current_mbl,
                'used_mbl' => 0,
                'remaining_balance' => $current_mbl,
                'start_date' => date('Y-m-d'),
            ];

            $this->members_model->reset_member_mbl($post_data, $data['emp_id']);
        }

    }
    
	function new_regular_mbl($date_arg) {
		$regularized_month = date("m", strtotime($date_arg));
		$last_month_of_year = 12;
		$months_diff = $last_month_of_year - $regularized_month;
		$mbl_level_5 = 30000;
		$max_benefit_limit = ($mbl_level_5 / 12) * $months_diff;
		return $max_benefit_limit;
	}

	function max_benefit_limit($pos_level) {
		if ($pos_level <= 6) {
			$max_benefit_limit = 30000;
		} else if ($pos_level <= 9 && $pos_level > 6) {
			$max_benefit_limit = 50000;
		} else if ($pos_level > 9) {
			$max_benefit_limit = 100000;
		}
		return $max_benefit_limit;
	}

}