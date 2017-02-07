 <?php
// ------------------------------------------------------------------------------------------

//Connect to holiday database

Class CHoliday{
	
public $iBooking = array();
public $iAllStaff = array();
public $iOnHoliday = "";
public $iAllStaffNumber = 0;
public $iComparray = array();
public $iStaffworking = 0;
public $iNamelength = array();
public $iMerged = array();
public $test = array();
	
protected function ConnectToDatabase() {

require_once('config.php');

$this->iMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$this->iMysqli->set_charset('utf8');

!mysqli_connect_error() or die("Connect failed: ".mysqli_connect_error()."<br>");
}

// -------------------------------------------------------------------------------------------
public function GetUsers(){

    $this->ConnectToDatabase();

    $query1 = "SELECT realname from user";

    $result = $this->iMysqli->query($query1)
    or die("Could not query database, query =<br/><pre>{$query1}</pre><br/>{$this->iMysqli->error}");
    !$this->iMysqli->errno or die("<p>Query =<br/><pre>{$query1}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");

    while ($row = $result->fetch_array(MYSQLI_NUM)){
        array_push($this->iAllStaff,$row[0]);
    }
    return $this->iAllStaff;
}

public function GetBookings($date, $allStaff){
$time = strtotime($date);
//var_dump($time);

$this->ConnectToDatabase();

//$this->GetUsers();

$query2=
"SELECT booking.startdate,booking.enddate,booking.type,booking.comment,user.userid,user.realname FROM booking
join user ON booking.userid = user.userid 
WHERE $time >= UNIX_TIMESTAMP(booking.startdate) AND $time <= UNIX_TIMESTAMP(booking.enddate)
ORDER BY user.realname";

$result2 = $this->iMysqli->query($query2) 

 or die("Could not query database, query =<br/><pre>{$query2}</pre><br/>{$this->iMysqli->error}");

// Check if there is a database error
!$this->iMysqli->errno or die("<p>Query =<br/><pre>{$query2}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");

while ($row = $result2->fetch_assoc()) {
    $temparray = $row;
    array_push($this->iComparray, ($temparray['realname']));
    //$namesworking = array_diff($this->iAllStaff, $this->iComparray);
   // $temparray['namesworking'] = $namesworking;
    array_push($this->iBooking, $temparray);
}
$this->iStaffworking = count($allStaff) - count($this->iBooking);
$this->iBooking['numbersworking'] = $this->iStaffworking;
$this->iBooking['namesworking'] = array_diff($allStaff, $this->iComparray);
//var_dump($this->iBooking['numbersworking']);
/* foreach($this->iUserids as $ids){
 	$this->iOnHoliday .= "," . $ids . "";
 }
 $this->iOnHoliday = substr($this->iOnHoliday, 1);*/
 
 /*$query2=
 "SELECT realname from user WHERE userid NOT IN($this->iOnHoliday)";
 
  $result2 = $this->iMysqli->query($query2) 
   or die("Could not query database, query =<br/><pre>{$query2}</pre><br/>{$this->iMysqli->error}");
 
 while ($row = $result2->fetch_array()){
 array_push($this->iWorking, $row[0]);
  }*/
//$namelength = count($this->iWorking);
//$this->iNamelength[0]= array('numbersworking'=>"$namelength");


//$this->iMerged = array_merge($this->iBooking,$this->iNamelength);
//$this->iMerged = json_encode($this->iMerged);
//$this->iNamelength = json_encode($this->iNamelength); 
//$this->iBooking = json_encode($this->iBooking);
//$this->iWorking = json_encode($this->iWorking);
//$this->iNamelength = json_encode($this->iNamelength);
$result2->close();
$this->iMysqli->close();
  } 

}
?>