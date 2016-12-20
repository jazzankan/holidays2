<?php
require "CHoliday.php";

$appliedFor = new CHoliday;
$appliedFor->GetBookings();
$appliedFor = $appliedFor->iBooking;
echo $appliedFor;
//print_r($appliedFor);
?>