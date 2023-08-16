<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_update{

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Sse_model'); // Load the model
        // $this->CI->load->library('security');
    }
	public function updateSpan()
    {
        $guarantee = $this->CI->Sse_model->get_count_guarantee();
		$patient = $this->CI->Sse_model->get_count_to_bill();
		$baseurl = base_url().'assets/vendors/jquery/jquery.min.js';
        // $this->CI->output->append_output('<script src='{$baseurl}'></script>');
        $js = "
            <script src='$baseurl'></script>
            <script>
                $(document).ready(function(){
                    $('#letter-count').text($guarantee);
                    $('#billing-count').text($patient);
                });
            </script>
        ";
        $this->CI->output->append_output($js);
    }
}
?>