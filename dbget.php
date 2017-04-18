<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-02-16
 * Time: 19:54
 */
require_once("CHoliday.php");

$saved = "";

if(!isset($_COOKIE['applicant'])) {
    echo "Cookie saknas";
}
else {
    $userid = $_COOKIE['applicant'];
    $readHoliday = new CHoliday();
    $readHoliday = $readHoliday->GetBookingsforOne($userid);

    foreach ($readHoliday as $s) {
        $saved .= "<li class='interval'>$s[2] -- $s[3] <img class='delete' id='$s[0]' role='button' title='Ta bort' src='Button-Close-icon.png'></li>";
    }
};
echo $saved;