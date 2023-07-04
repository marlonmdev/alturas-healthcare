<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sse_controller extends CI_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->model('Sse_model');
	}
    

    public function count_uploaded_letter()
    {
        // Set the response headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        // Set the timeout limit to 0 to keep the connection open
        set_time_limit(0);

        $data = $this->Sse_model->get_count_guarantee();
      
        echo "data: " . json_encode(intVal($data)) . "\n\n";
          // Flush the output buffer to send the response immediately
        ob_flush();
        flush();

    }

    public function count_to_bill()
    {
        // Set the response headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        // Set the timeout limit to 0 to keep the connection open
        set_time_limit(0);

        $data = $this->Sse_model->get_count_to_bill();
      
        echo "data: " . json_encode(intVal($data)) . "\n\n";
          // Flush the output buffer to send the response immediately
        ob_flush();
        flush();
    }


}