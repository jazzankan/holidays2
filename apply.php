<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-01-18
 * Time: 19:19
 */
$applicant = $_POST['applicant'];
$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <link href="css/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/jquery.comiseo.daterangepicker.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="scripts/moment.js"></script>
    <script
			  src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
			  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
			  crossorigin="anonymous"></script>
	<script src="scripts/datepicker-sv.js"></script>
	<script src="scripts/jquery.comiseo.daterangepicker.js"></script>
	    <script>
        $(function() { $("#e1").daterangepicker(); });
    </script>
    <script src="scripts/moment.js"></script>
    <title>Semesterlistan</title>
</head>
<body>
<p>$applicant</p>
<input id="e1" name="e1">
<script src="scripts/holiday2.js"></script>
</body>
</html>
EOD;
echo $html;