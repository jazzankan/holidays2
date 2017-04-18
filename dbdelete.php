<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-03-23
 */
require_once("CHoliday.php");

    if(!isset($_POST['bookingid'])) {
        echo "Ingen post";
    }
    else{
        $bookingId = $_POST['bookingid'];
        $deleteBooking = new CHoliday();
        $deleteBooking->DeleteHoliday($bookingId);
        echo "Done";
    };