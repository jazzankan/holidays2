<?php
/**
 * Created by PhpStorm.
 * User: Anders Fredriksson
 * Date: 2016-11-21
 * Time: 22:06
 */
if(!isset($_COOKIE["holidays"])) {
    header("Location:login.php");
}

require_once("Calendar.php");
require_once("RedDays.php");
require("CHoliday.php");

$calendar = new Calendar();
$calendar = $calendar->show();
$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/2style.css">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.7.1/release/featherlight.min.css" type="text/css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Semesterlistan</title>
</head>
<body>
<div id="calwrap">
$calendar
</div>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.7.1/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
<script src="scripts/pickname.js"></script>
</body>
</html>
EOD;
echo $html;
