<?php
require "CHoliday.php";

$appliedFor = new CHoliday;
$appliedFor->GetBookings();
$appliedFor = $appliedFor->iStaffworking;
echo $appliedFor;
//print_r($appliedFor);
?>