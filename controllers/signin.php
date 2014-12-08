<?php

/*
* @author: Yamin Noor
* @purpose: sign in user
*/

class signin_controller extends base_controller
{	
	//setup error message variable
	public $error_msg = null;

	//contructor selects the default layout
	public function __construct() {
		$this->layout = "default";
	}

	/*
	* @author: Yamin Noor
	* @purpose: display initial login screen, handle login for user
	* @params: void
	*/
	public function index()
	{
		$user_id = MyLibrary::getFromSession('user_id');
		
		if(MyLibrary::isUserAuthenticated($user_id)) {
			header("Location: /calendar"); //goto default calendar view
			exit;
		}
		else {
			$user_signin = MyLibrary::getUserField('user_signin');
			$user_email  = MyLibrary::getUserField('user_email');
			$user_pass   = MyLibrary::getUserField('user_pass');

			// check if the login credentials are submitted
			if($user_signin != "") {
				if($user_email != "" && $user_pass != "") {
					if($this->login($user_email, $user_pass)) {
						//login passed and authenticated
						header("Location: /calendar");
						exit;
					}
				}
				else {
					if($user_email == "") {
						$this->error_msg[] = "User Email is missing!";
					}

					if($user_pass == "") {
						$this->error_msg[] = "User Password is missing!";
					}			
				}
			}
			$this->set_view('index');
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: login & authenticate user by email & password
	* @params: email address, password of the user 
	*/
	private function login($user_email, $user_pass) {
		if($user_email == "") return false;

		$login_flag = false;

		$user = new User();
		$user->setEmail($user_email);
		$user->selectByEmail();

		if($user->isLoaded()) {
			if($user->getPass() == $this->hash_password($user_pass)) {
				$now = new DateTime();
				$last_login = $now->format("Y-m-d H:i:s");

				MyLibrary::setIntoSession('user_id', $user->getID());
				MyLibrary::setIntoSession('user_email', $user->getEmail());
				MyLibrary::setIntoSession('user_fname', $user->getFirstName());
				MyLibrary::setIntoSession('user_lname', $user->getLastName());
				MyLibrary::setIntoSession('user_last_login', $last_login);

				if(MyLibrary::getFromSession('user_id') != "") {
					MyLibrary::setIntoSession('authenticated', true);

					$user->setLastLogin($last_login);
					$user->update();//update the user with latest login info
				}
			}
			else {
				$this->error_msg[] = "Password mismatch";
			}
		}
		else {
			$this->error_msg[] = "Sorry, you are not a registered user";
		}

		$login_flag = (MyLibrary::getFromSession('authenticated') == true) ? true : false;

		return $login_flag;
	}

	/*
	* @author: Yamin Noor
	* @purpose: display page not found 
	* @params: void
	*/
	public function _error() {
		$this->disable_layout();
		$this->set_view('error');
	}

	/*
	* @author: Yamin Noor
	* @purpose: password hash generator
	* @params: void
	*/
	public function hash_password($passwd) {
		return md5($passwd);
	}
}