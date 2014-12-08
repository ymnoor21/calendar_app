<?php
/*
*
* @author: Yamin Noor
* @purpose: create note object
*
*/

class note
{
	//initialize note variables
	private $_noteID 		= 0;
	private $_noteUID 		= 0;
	private $_noteText		= "";
	private $_noteDate 		= "";
	private $_noteMdate 	= "";
	private $_noteCdate		= "";	
	private $_loaded 		= false;

	public function __construct($note_id = null) {
		if($note_id != "") {
			$this->selectByID($note_id);
		}
		else {
			$this->_loaded = false;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: load note by note_id field (primary key field)
	* @params: void
	*/
	public function selectByID($note_id) {
		$sql = "select * from tbl_notes WHERE note_id = " . $note_id . " LIMIT 1";
		$result = mysql::query_row('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result != "") {
			$this->setID($result['note_id']);
			$this->setUID($result['note_uid']);
			$this->setText($result['note_text']);
			$this->setDate($result['note_date']);
			$this->setMdate($result['note_mdate']);
			$this->setCdate($result['note_cdate']);
			$this->_loaded = true;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: insert note
	* @params: void
	*/
	public function insert() {
		$sql = "INSERT INTO tbl_notes (note_uid, note_text, note_date, note_mdate) VALUES 
				({$this->getUID()}, '{$this->getText()}', '{$this->getDate()}', NOW())";

		$result = mysql::query('main', $sql);
		$last_insert_id = mysql::last_insert_id('main');

		if($result && $last_insert_id > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	/*
	* @author: Yamin Noor
	* @purpose: update note by note_id field (primary key field)
	* @params: void
	*/
	public function update() {
		$sql = "UPDATE tbl_notes SET 
					note_text = '{$this->getText()}',
					note_mdate = NOW()
				WHERE note_id = " . $this->getID();

		$result = mysql::query('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		if($result) {
			$this->selectByID();
		}

		return $result;
	}

	/*
	* @author: Yamin Noor
	* @purpose: delete note by note_id field (primary key field)
	* @params: void
	*/
	public function delete() {
		$sql = "DELETE FROM tbl_notes WHERE note_id = " . $this->getID();
		$result = mysql::query('main', $sql);
		
		if(mysql::is_error())
		{
			return false;
		}

		return $result;
	}

	//getter method for note_id
	public function getID() {
		return $this->_noteID;
	}

	//setter method for note_id
	public function setID($note_id) {
		$this->_noteID = $note_id;
	}

	//getter method for note_uid
	public function getUID() {
		return $this->_noteUID;
	}

	//setter method for note_uid
	public function setUID($note_uid) {
		$this->_noteUID = $note_uid;
	}

	//getter method for note_text
	public function getText() {
		return $this->_noteText;
	}

	//setter method for note_text
	public function setText($note_text) {
		$this->_noteText = $note_text;
	}

	//getter method for note_date
	public function getDate() {
		return $this->_noteDate;
	}

	//setter method for note_date
	public function setDate($note_date) {
		$this->_noteDate = $note_date;
	}

	//getter method for note_mdate
	public function getMdate() {
		return $this->_noteMdate;
	}

	//setter method for note_mdate
	public function setMdate($note_mdate) {
		$this->_noteMdate = $note_mdate;
	}

	//getter method for note_cdate
	public function getCdate() {
		return $this->_noteCdate;
	}

	//setter method for note_cdate
	public function setCdate($note_cdate) {
		$this->_noteCdate = $note_cdate;
	}

	/*
	* @author: Yamin Noor
	* @purpose: indicates whether note object has been created or not
	* @params: void
	*/
	public function isLoaded() {
		return $this->_loaded;
	}
}