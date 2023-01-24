<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hospital_model extends CI_Model {

  public function insert_account($firstname, $lastname, $username, $email, $hashed_password){		
		$query = "INSERT INTO accounts (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)";
		$value = array($firstname, $lastname, $username, $email, $hashed_password);
		$inserted = $this->db->query($query, $value);
    return $inserted ? true : false;
	}

}