<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-01-18
 * Time: 19:19
 */
require_once 'CHoliday.php';

$nameAndId = explode(";", $_POST['applicant']);
$applicant = $nameAndId[0];
$cookieName = "applicant";
$cookieValue = $nameAndId[1];   //The userid
$savedPeriods = new CHoliday();
$savedPeriods = $savedPeriods->GetBookingsforOne($cookieValue);
$savedHolidays = "";
foreach ($savedPeriods as $s){
  $savedHolidays  .= "<li class='interval'>$s[2] -- $s[3] <img class='delete' id='$s[0]' role='button' title='Ta bort' src='Button-Close-icon.png'></li>"; //also in dbget.php
}

setcookie($cookieName, $cookieValue);

$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="scripts/moment.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Semesterlistan</title>
    <link rel="stylesheet" href="bootstrap-daterangepicker-master/daterangepicker.css">
    <script src="bootstrap-daterangepicker-master/daterangepicker.js"></script>
    <link rel="stylesheet" href="css/2style.css">
    <script src="scripts/holiday2.js"></script>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-sm-4">
<h3 id="appplicant">$applicant, här önskar du ledighet:</h3>
<div class="saved">
<h4>Valda perioder:</h4>
<ul>
$savedHolidays
</ul>
</div>
<div class="wait">
</div>
<input type="text" class="form-control btn btn-primary" id="daterange" name="daterange" size="24" value="Välj en period!">
<p id="backtolist"><a  href="index.php">Tillbaka till listan</a></p>
<script src="scripts/holiday2.js"></script>
</div>
</div>
</div>
</body>
</html>
EOD;
echo $html;