<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages_controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		$user_role = $this->session->userdata('user_role');
		$logged_in = $this->session->userdata('logged_in');
		if ($logged_in !== true && $user_role !== 'healthcare-provider') {
				redirect(base_url());
		}
	}

	function index() {
		$this->load->model('healthcare_provider/count_model');
		$hp_id = $this->session->userdata('dsg_hcare_prov');
		$data['user_role'] = $this->session->userdata('user_role');
		$data['loa_count'] = $this->count_model->hp_approved_loa_count($hp_id);
		$data['noa_count'] = $this->count_model->hp_approved_noa_count($hp_id);
		$data['bllled_count'] = $this->count_model->hp_done_billing_count($hp_id);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/dashboard/index');
		$this->load->view('templates/footer');
	}

	private function _hash_string($str) {
		$hashed_string = password_hash($str, PASSWORD_BCRYPT, array('cost' => 12));
		return $hashed_string;
	}

	function image_resize($path, $file) {
		$config_resize = array();
		$config_resize['image_library'] = 'gd2';
		$config_resize['source_image'] = $path;
		$config_resize['maintain_ratio'] = TRUE;
		$config_resize['width'] = '225';
		$config_resize['height'] = '215';
		$config_resize['new_image'] = './users/thumbs/' . $file;
		$this->load->library('image_lib', $config_resize);
		$this->image_lib->resize();
	}
}
