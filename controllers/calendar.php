<?php

/*
* @author: Yamin Noor
* @purpose: calendar functionality
*/
class calendar_controller extends base_controller
{
    public function __construct()
    {
        $this->layout = "home";

        $user_id = MyLibrary::getFromSession('user_id');

        if(!MyLibrary::isUserAuthenticated($user_id)) {
            header("Location: " . DEFAULT_PAGE);
            exit;
        }
    }

    /*
    * @author: Yamin Noor
    * @purpose: handle initial calendar page landing
    */
	public function index()
	{
        $this->view();
	}

    /*
    * @author: Yamin Noor
    * @purpose: draw the calendar
    * @params: month, year and data array
    */
	public function draw_calendar($month, $year, $data = null){
        $note_uid = MyLibrary::getFromSession('user_id');

        $calendar  = '<table cellpadding="0" cellspacing="0" class="calendar">';

       	$headings  = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
       	$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

       	$running_day         = date('w',mktime(0,0,0,$month,1,$year));
       	$days_in_month       = date('t',mktime(0,0,0,$month,1,$year));
       	$days_in_this_week   = 1;
       	$day_counter         = 0;
       	$dates_array         = array();

   	    $calendar            .= '<tr class="calendar-row">';

       	for($x = 0; $x < $running_day; $x++) {
       		$calendar .= '<td class="calendar-day-np">&nbsp;</td>';
       		$days_in_this_week++;
       	}

       	for($list_day = 1; $list_day <= $days_in_month; $list_day++){
            $list_day_txt = ($list_day <= 9) ? "0" . $list_day : $list_day;

       		$calendar      .= '<td class="calendar-day" valign="top">';
       		$calendar      .= '<div class="day-number" data-date="'. $year . "-" . $month . "-" . $list_day_txt .'">'.$list_day_txt.'</div>';
            $calendar      .= '<div id="'. $year . "-" . $month . "-" . $list_day_txt .'">';

            $note_date = $year . "-" . $month . "-" . $list_day_txt;

       		if($data[$list_day] != "")
       		{
                $loopcount = 0;
       		    foreach($data[$list_day] as $key => $value)
       		    {
                    $calendar .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'><img src='/images/note.png' align='absmiddle'/> <a href='javascript:void(0);' onclick='openNote(" . $value['note_id'] . ")'>" . MyLibrary::truncate($value['note_text']) . "</a></p>";
                    $loopcount++;

                    if($loopcount == MAX_P_COUNT-1) {
                        break;
                    }
      			}

                if($loopcount == MAX_P_COUNT-1 && count($data[$list_day]) > MAX_P_COUNT-1) {
                    $calendar .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'><a href='javascript:void(0)' onclick=\"openAllNotes(" .$note_uid. ", '" . $note_date . "');\">+More</a></p>";
                }
                elseif($loopcount <= MAX_P_COUNT-1) {
                    $remaining = MAX_P_COUNT - $loopcount;

                    for($i = 0; $i < $remaining; $i++) {
                        $calendar .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'>&nbsp;</p>";                        
                    }
                }
       		}
       		else
       		{
       		   $calendar.= str_repeat('<p>&nbsp;</p>',MAX_P_COUNT);
       		}

            $calendar .= "</div>";
       		$calendar .= '</td>';

       		if($running_day == 6) {
       			$calendar .= '</tr>';

       			if(($day_counter + 1) != $days_in_month){
       				$calendar .= '<tr class="calendar-row">';
       			}

       			$running_day = -1;
       			$days_in_this_week = 0;
       		}

       		$days_in_this_week++;
       		$running_day++;
       		$day_counter++;
       	}

       	if($days_in_this_week < 8) {
       		for($x = 1; $x <= (8 - $days_in_this_week); $x++) {
       			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
       		}
       	}

       	$calendar .= '</tr>';
       	$calendar .= '</table>';

       	return $calendar;
    }

    /*
    * @author: Yamin Noor
    * @purpose: calendar view page
    * @params: void
    */
    public function view() {
        $this->months = MyLibrary::getMonths();
        $this->years  = MyLibrary::getYears();

        $this->selected_month = ($_GET['month'] != "") ? $_GET['month'] : date("m");
        $this->selected_year  = ($_GET['year'] != "")  ? $_GET['year']  : date("Y");

        $month_formatted = ($this->selected_month <= 9) ? "0" . $this->selected_month : $this->selected_month;

        $selected_year_month = $this->selected_year . "-" . $month_formatted;
        $data = $this->getNotesForMonth($selected_year_month);

        $this->calendar = $this->draw_calendar($this->selected_month, $this->selected_year, $data);
        $this->set_view('index');
    }

    /*
    * @author: Yamin Noor
    * @purpose: handle calendar controller page not found
    * @params: void
    */
    public function _error() {
        $this->disable_layout();
        $this->set_view('error');
    }

    /*
    * @author: Yamin Noor
    * @purpose: ajax call to get note by note_id
    * @params: void
    */
    public function getnote() {
        $this->disable_layout();

        $response = new StdClass();
        $note_id   = MyLibrary::getUserField('note_id');

        $note = new Note($note_id);

        if($note != "") {
            $response->note_text = $note->getText();
            $response->note_date = $note->getDate();
            $response->flag = true;
        }
        else {
            $response->flag = false;
        }

        echo json_encode($response);
        exit;
    }

    /*
    * @author: Yamin Noor
    * @purpose: ajax call to save note / update note
    * @params: void
    */
    public function savenote() {
        $this->disable_layout();

        $response = new StdClass();

        $note_text = MyLibrary::getUserField('note_text');
        $note_date = MyLibrary::getUserField('note_date');
        $note_id   = MyLibrary::getUserField('note_id');

        if($note_text != "") {
            $note_uid = MyLibrary::getFromSession('user_id');

            //if there is no note_id, it means a new note will be created
            if($note_id == "") 
            {
                $note = new Note();

                $note->setUID($note_uid);
                $note->setText($note_text);
                $note->setDate($note_date);

                if($note->insert()) {
                    $response->message  = "Thanks, your note has been added.";
                    $response->flag     = true;
                    $response->html     = $this->getNoteListHTMLForDate($note_date);
                }
                else {
                    $response->message  = "Sorry couldn't insert your note. Please try later.";
                    $response->flag     = false;
                }
            }
            //note_id is available, so its an update
            else {
                $note = new Note($note_id);
                $note->setText($note_text);

                if($note->update()) {
                    $response->message  = "Thanks, your note has been updated.";
                    $response->flag     = true;
                    $response->html     = $this->getNoteListHTMLForDate($note_date);
                }
                else {
                    $response->message  = "Sorry, couldn't update your note. Please try later.";
                    $response->flag     = false;
                }
            }
        }
        else {
            $response->flag = false;
        }

        echo json_encode($response);;
        exit;
    }

    /*
    * @author: Yamin Noor
    * @purpose: ajax call to delete note 
    * @params: void
    */
    public function deletenote() {
        $this->disable_layout();
        $response = new StdClass();

        $note_id = MyLibrary::getUserField('note_id');

        if($note_id != "") {
            $note = new Note($note_id);
            $note_date = $note->getDate();

            if($note->delete()) {
                $response->message = "Your note has been deleted.";
                $response->flag = true;
                $response->html     = $this->getNoteListHTMLForDate($note_date);
            }
            else {
                $response->message = "Sorry, couldn't delete your note. Please try later.";
                $response->flag = false;
            }
        }
        else {
            $response->message = "Sorry, couldn't delete your note. Please try later.";
            $response->flag = false;
        }

        echo json_encode($response);;
        exit;
    }

    /*
    * @author: Yamin Noor
    * @purpose: Get calendar HTML for specific day
    * @params: note date
    */
    public function getNoteListHTMLForDate($note_date) {
        $note_uid = MyLibrary::getFromSession('user_id');

        $allnotes  = $this->getNotesForDate($note_uid, $note_date);

        $txt = "";

        if($allnotes != "") {
            $loopcount = 0;
            foreach($allnotes as $key => $value)
            {
                $txt .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'><img src='/images/note.png' align='absmiddle'/> <a href='javascript:void(0);' onclick='openNote(" . $value['note_id'] . ")'>" . MyLibrary::truncate($value['note_text']) . "</a></p>";
                $loopcount++;

                if($loopcount == MAX_P_COUNT-1) {
                    break;
                }
            }

            if($loopcount == MAX_P_COUNT-1 && count($allnotes) > MAX_P_COUNT-1) {
                $txt .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'><a href='javascript:void(0)' onclick=\"openAllNotes(" . $note_uid . ", '" . $note_date. "')\">+More</a></p>";
            }
            elseif($loopcount <= MAX_P_COUNT-1) {
                $remaining = MAX_P_COUNT - $loopcount;

                for($i = 0; $i < $remaining; $i++) {
                    $txt .= "<p style='margin: 0px 20px 0px 0px; padding: 0px 0px 5px 0px; text-align: left'>&nbsp;</p>";                        
                }
            }
        }

        return $txt;
    }

    /*
    * @author: Yamin Noor
    * @purpose: get all notes for a particular date and logged in user
    * @params: note uid, day of interest
    */
    private function getNotesForDate($note_uid, $note_date) {
        $this->disable_layout();

        $sql = "SELECT * FROM tbl_notes WHERE note_date = '{$note_date}' AND note_uid = {$note_uid} ORDER BY note_id DESC";
        $result = mysql::query('main', $sql);
        
        if(mysql::is_error())
        {
            return false;
        }

        if($result != "") {
            for($i = 0; $i < count($result); $i++) {
                $result[$i]['note_date'] = date("m/d/Y", strtotime($result[$i]['note_date']));

                $notes[] = $result[$i];
            }
        }
        else {
            $notes = null;
        }

        return $notes;
    }

    /*
    * @author: Yamin Noor
    * @purpose: get all notes of a user using a specific date
    * @params: void
    */
    public function getnotesofuserbyday() {
        $this->disable_layout();
        $response = new StdClass();

        $note_uid   = MyLibrary::getUserField('note_uid');
        $note_date  = MyLibrary::getUserField('note_date');

        $notes = $this->getNotesForDate($note_uid, $note_date);

        if($notes != "") {
            $response->notes = $notes;
            $response->flag  = true;
        }
        else {
            $response->flag = false;
        }

        echo json_encode($response);
        exit;
    }

    /*
    * @author: Yamin Noor
    * @purpose: get all notes for a particular month and logged in user
    * @params: month of interest
    */
    private function getNotesForMonth($note_date) {
        $sql = "SELECT * FROM tbl_notes WHERE note_date LIKE '$note_date%' AND note_uid = " . MyLibrary::getFromSession('user_id') . " ORDER BY note_id DESC ";
        $result = mysql::query('main', $sql);
        
        if(mysql::is_error())
        {
            return false;
        }

        if($result != "") {
            for($i = 0; $i < count($result); $i++) {
                $dateexp = explode("-", $result[$i]['note_date']);

                $day = (int)$dateexp[2];

                $notes[$day][] = $result[$i];
            }
        }
        else {
            $notes = null;
        }

        return $notes;
    }
}