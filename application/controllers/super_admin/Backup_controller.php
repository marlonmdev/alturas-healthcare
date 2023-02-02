<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backup_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'super-admin') {
			redirect(base_url());
		}
	}

	function database_backup(){
		$this->security->get_csrf_hash();
		$this->load->dbutil();
		$this->load->helper('download');
		$file_name = $this->security->xss_clean($this->input->post('backup-name'));

		$prefs = [
			'format'    => 'zip',
			'filename'  => $file_name .'_'. date('m-d-Y_g:ia') . '.sql'
		];

		$db_name = $file_name .'_'. date('m-d-Y_g:ia') . '.zip';
		$backup = $this->dbutil->backup($prefs);
		force_download($db_name.'.zip', $backup);
	}

}
