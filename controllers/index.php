<?php
/*
* @author: Yamin Noor
* @purpose: handle initial landing
*/
class index_controller extends base_controller
{
	public function __construct() {
		$this->disable_layout();
	}

	public function index()
	{
		//goto login page
		header("Location: /signin");
		exit;
	}

   public function _error() {
      $this->set_view('error');
   }
}