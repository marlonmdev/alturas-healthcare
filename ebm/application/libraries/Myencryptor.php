<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Myencryptor {

  public function __construct() {
    // Assign the CodeIgniter super-object
    $this->CI = &get_instance();
    $this->CI->load->library('encryption');
  }

  // Encrypts or decrypts a string
  function crypt($string, $type) {
    if ($type == "encrypt") {
      return base64_encode($this->CI->encryption->encrypt($string));
    } elseif ($type == "decrypt") {
      return $this->CI->encryption->decrypt(base64_decode($string));
    }
  }

  function hash_password($password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    return $hashed_password;
  }

  function verify_hash($plain_text_str, $hashed_string) {
    $result = password_verify($plain_text_str, $hashed_string);
    return $result;
  }
}
