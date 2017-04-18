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
public $iOneUser = array();
public $iHaveBookings = array();
	
protected function ConnectToDatabase() {

require_once('config.php');

$this->iMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$this->iMysqli->set_charset('utf8');

!mysqli_connect_error() or die("Connect failed: ".mysqli_connect_error()."<br>");
}

// -------------------------------------------------------------------------------------------
public function GetUsers(){

    $this->ConnectToDatabase();

    $query1 = "SELECT realname, userid from user";

    $result = $this->iMysqli->query($query1)
    or die("Could not query database, query =<br/><pre>{$query1}</pre><br/>{$this->iMysqli->error}");
    !$this->iMysqli->errno or die("<p>Query =<br/><pre>{$query1}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");

    while ($row = $result->fetch_array(MYSQLI_NUM)){
        array_push($this->iAllStaff,$row);
    }
    return $this->iAllStaff;
}

public function GetBookings($date, $allStaff){
$time = strtotime($date);
$allStaff = array_column($allStaff,0);
$this->ConnectToDatabase();

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
$result2->close();
$this->iMysqli->close();
  }

     public function GetBookingsforOne($userid){
         $this->ConnectToDatabase();

         $query=
             "SELECT booking.bookingid, booking.userid, booking.startdate, booking.enddate FROM booking
              WHERE booking.userid = $userid AND booking.startdate > DATE_SUB(CURDATE(),INTERVAL 90 DAY)
              ORDER BY booking.startdate";

         $result = $this->iMysqli->query($query)

         or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$this->iMysqli->error}");

// Check if there is a database error
         !$this->iMysqli->errno or die("<p>Query =<br/><pre>{$query}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");

         while ($row = $result->fetch_row()) {
             array_push($this->iOneUser, $row);
         }
         return $this->iOneUser;

         $result->close();
         $this->iMysqli->close();
     }


  public function InsertHoliday($userid, $startTime, $endTime){
         $this->ConnectToDatabase();

         $query="INSERT INTO booking (userid, startdate, enddate)
VALUES ('$userid', '$startTime', '$endTime')";
      if ($this->iMysqli->query($query) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $query . "<br>" . $this->iMysqli->error;
          return "Error";
      }

     }

     public function DeleteHoliday($id){
         $this->ConnectToDatabase();

         $query="DELETE FROM  booking 
          WHERE bookingid = $id";
         if ($this->iMysqli->query($query) === TRUE) {
             echo "Record deleted successfully";
         } else {
             echo "Error: " . $query . "<br>" . $this->iMysqli->error;
             return "Error";
         }

     }

     public function GetSeasonsBookings(){

         $month = date("m",time());
         $year = date("Y",time());
         // Any applications later than may 1 or november 1
         $month = $month > 1 && $month < 9 ? 05 : 11;
         $stpoint = "$year-0$month-01";

         $this->ConnectToDatabase();

         $query=
             "SELECT booking.userid FROM booking
              WHERE booking.startdate > '$stpoint'";

         $result = $this->iMysqli->query($query)

         or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$this->iMysqli->error}");

// Check if there is a database error
         !$this->iMysqli->errno or die("<p>Query =<br/><pre>{$query}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");

         while ($row = $result->fetch_row()) {
             array_push($this->iHaveBookings, $row[0]);
         }
         return $this->iHaveBookings;

         $result->close();
         $this->iMysqli->close();

     }

 }
?>