<?php
/**
 * Created by PhpStorm.
 * User: Anders Fredriksson
 * Date: 2016-11-21
 * Time: 22:06
 */
require_once("Calendar.php");
require_once("RedDays.php");
$calendar = new Calendar();
$calendar = $calendar->show();
$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/2style.css">
    <title>Semesterlistan</title>
</head>
<body>
$calendar
</body>
</html>
EOD;
echo $html;
