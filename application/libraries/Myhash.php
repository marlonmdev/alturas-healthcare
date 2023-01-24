<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Myhash {

  public function __construct() {
    // Assign the CodeIgniter super-object
    $this->CI = &get_instance();
  }

  // Encrypts or decrypts a string
  function hasher($string, $type) {
    if ($type == "encrypt") {
      return hashids_encrypt($string, $this->CI->config->item('hashid_salt'), 20);
    } elseif ($type == "decrypt") {
      return hashids_decrypt($string, $this->CI->config->item('hashid_salt'), 20);
    }
  }
}
