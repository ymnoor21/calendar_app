<?php
/*
* @author: Yamin Noor
* @purpose: Logout user
*/
class logout_controller extends base_controller {
	public function __construct() {
		$this->disable_layout();
	}

	public function index() {
		if($_SESSION) {
			foreach($_SESSION as $key => $value) {
				$_SESSION[$key] = null;
				unset($_SESSION[$key]);
			}
		}

		session_unset();
		session_destroy();

		header("Location: " . DEFAULT_PAGE);
		exit;
	}
}