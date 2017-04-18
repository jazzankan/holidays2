<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-02-16
 * Time: 19:54
 */
require_once("CHoliday.php");

if(!isset($_COOKIE['applicant'])) {
    echo "Cookie saknas";
} else {
    $userid = $_COOKIE['applicant'];
    if(!isset($_POST['interval'])) {
        echo "Ingen post";
    }
    else{
        $selTime = $_POST['interval'];
        $startTime = substr($selTime,0,10);
        $endTime = substr($selTime,13);
        $createHoliday = new CHoliday();
        $createHoliday->InsertHoliday($userid,$startTime,$endTime);

        echo $startTime;
    }
}


