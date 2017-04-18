<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-04-13
 * Time: 19:27
 */
$errormsg = "";

if(!empty($_POST)){
if($_POST["usr"] == "new"  && $_POST["passw"] == "holidays"){
    setcookie("holidays");
    header("Location:index.php");
  }
  else{
    $errormsg = "<b>Nu blev det lite fel. Försök igen!</b>";
  }
}
//echo ($_POST["usr"]);
//echo ($_POST["passw"]);

$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/2style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Login-Semesterlistan</title>
</head>
<body>
<div id="login">
<h3>Logga in till semesterlistan</h3>
<form action="" method="post">
<label for="usr">Användarnamn</label><br>
<input type="text" id="usr" name="usr">
<p>
<label for="passw">Lösenord</label><br>
<input type="password" id="passw" name="passw">
</p>
<input type="submit" id="loginsubmit" value="Skicka">
</form>
<div class="errormsg">
$errormsg
</div>
</div>
</body>
</html>
EOD;
echo $html;