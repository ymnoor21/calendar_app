<?php
/*
* @author: Yamin Noor
* @purpose: accessing request / post variables and automatically implement the sanitization if needed
*/
class MyLibrary {
	/*
	* @author: Yamin Noor
	* @purpose: get a user input field from superglobal $_REQUEST variable
	* @params: key - index of $_REQUEST variable
	*/
	public static function getUserField($fieldName) {
		if($_REQUEST[$fieldName] != "") {
			return $_REQUEST[$fieldName];
		}
		else {
			return null;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: get a field from session
	* @params: key - index of superglobal $_SESSION variable
	*/
	public static function getFromSession($field) {
		if($_SESSION != "") {
			return $_SESSION[$field];
		}
		else {
			return null;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: set a variable into session
	* @params: key, value
	*/
	public static function setIntoSession($field, $value) {
		$_SESSION[$field] = $value;
	}

	/*
	* @author: Yamin Noor
	* @purpose: check if user is authenticated
	* @params: user_id
	*/
	public static function isUserAuthenticated($user_id) {
		if($_SESSION && $_SESSION['user_id'] == $user_id) {
			return $_SESSION['authenticated'];
		}
		else {
			return false;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: Get years
	* @params: void
	*/
	public static function getYears() {
		for($i = STARTING_YEAR; $i <= STARTING_YEAR+5; $i++) {
            $years[] = $i;
        }

        return $years;
	}

	/*
	* @author: Yamin Noor
	* @purpose: Get monthss
	* @params: void
	*/
	public static function getMonths() {
		for($i = 1; $i < 13; $i++) {
            $date       = date("Y") . "-" . $i . "-" . "01";
            $months[$i] = date("F", strtotime($date));
        }

        return $months;
	}

	/*
	* @author: Yamin Noor
	* @purpose: truncate a long text
	* @params: text to truncate, truncation from position
	*/
	public static function truncate($text, $chars = 20) {
		if(strlen($text) > $chars) {
		    $text = $text." ";
		    $text = substr($text,0,$chars);
		    $text = substr($text,0,strrpos($text,' '));
		    $text = $text."...";
		}
		
	    return $text;
	}
}