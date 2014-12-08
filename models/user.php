<?php
/*
*
* @author: Yamin Noor
* @purpose: create user object
*
*/

class user
{
	//initialize user variables
	private $_userID 		= 0;
	private $_userEmail 	= "";
	private $_userPass		= "";
	private $_userFname 	= "";
	private $_userLname 	= "";
	private $_userLastLogin	= "";	
	private $_loaded 		= false;

	public function __construct($user_id = null) {
		if($user_id != "") {
			$this->selectByUserID($user_id);
		}
		else {
			$this->_loaded = false;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: load user by user_id field (primary key field)
	* @params: void
	*/
	public function selectByUserID($user_id) {
		$sql = "select * from tbl_users WHERE user_id = " . $user_id . " LIMIT 1";
		$result = mysql::query_row('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result != "") {
			$this->setID($result['user_id']);
			$this->setPass($result['user_pass']);
			$this->setEmail($result['user_email']);
			$this->setFirstName($result['user_fname']);
			$this->setLastName($result['user_lname']);
			$this->setLastLogin($result['user_last_login']);
			$this->_loaded = true;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: load user by user_email field (unique key field)
	* @params: void
	*/
	public function selectByEmail() {
		$sql = "select * from tbl_users WHERE user_email = '" . $this->getEmail() . "' LIMIT 1";
		$result = mysql::query_row('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result != "") {
			$this->setID($result['user_id']);
			$this->setPass($result['user_pass']);
			$this->setEmail($result['user_email']);
			$this->setFirstName($result['user_fname']);
			$this->setLastName($result['user_lname']);
			$this->setLastLogin($result['user_last_login']);
			$this->_loaded = true;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: insert user
	* @params: void
	*/
	public function insert() {
		$sql = "INSERT INTO tbl_users (user_email, user_pass, user_fname, user_lname, user_last_login)
				VALUES ('{$this->getEmail()}', '{$this->getPass()}', '{$this->getFirstName()}', '{$this->getLastName()}', NOW())";

		$result = mysql::query('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result) {
			$this->selectByUserID();
		}

		return $result;
	}

	/*
	* @author: Yamin Noor
	* @purpose: update user by user_id field (primary key field)
	* @params: void
	*/
	public function update() {
		$user_pass_txt = ($this->getPass() != "") ? " user_pass = '" . $this->getPass() . "', " : "";

		$sql = "UPDATE tbl_users SET 
					$user_pass_txt
					user_fname = '{$this->getFirstName()}',
					user_lname = '{$this->getLastName()}', 
					user_last_login = '{$this->getLastLogin()}'
				WHERE user_id = " . $this->getID();

		$result = mysql::query('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result) {
			$this->selectByUserID();
		}

		return $result;
	}

	/*
	* @author: Yamin Noor
	* @purpose: delete user by user_id field (primary key field)
	* @params: void
	*/
	public function delete() {
		$sql = "DELETE FROM tbl_users WHERE user_id = " . $this->getID();
		$result = mysql::query('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		return $result;
	}

	//getter method for user_id
	public function getID() {
		return $this->_userID;
	}

	//setter method for user_id
	public function setID($user_id) {
		$this->_userID = $user_id;
	}

	//getter method for user_pass
	public function getPass() {
		return $this->_userPass;
	}

	//setter method for user_pass
	public function setPass($user_pass) {
		$this->_userPass = $user_pass;
	}

	//getter method for user email
	public function getEmail() {
		return $this->_userEmail;
	}

	//setter method for user_email
	public function setEmail($user_email) {
		$this->_userEmail = $user_email;
	}

	//getter method for user_fname
	public function getFirstName() {
		return $this->_userFname;
	}

	//setter method for user_lname
	public function setFirstName($user_fname) {
		$this->_userFname = $user_fname;
	}

	//getter method for user_lname
	public function getLastName() {
		return $this->_userLname;
	}

	//setter method for user_lname
	public function setLastName($user_lname) {
		$this->_userLname = $user_lname;
	}

	//getter method for user last login
	public function getLastLogin() {
		return $this->_userLastLogin;
	}

	//setter method for user last login
	public function setLastLogin($user_last_login) {
		$this->_userLastLogin = $user_last_login;
	}

	/*
	* @author: Yamin Noor
	* @purpose: check if the user object has been created or not
	* @params: void
	*/
	public function isLoaded() {
		return $this->_loaded;
	}
}