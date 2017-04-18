<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 2017-04-01
 * Time: 16:06
 */
require_once("CHoliday.php");
$unbookedUserId = array();

$sinner = "";
$allStaff = new CHoliday;
$allStaff = $allStaff->GetUsers();
$allStaffId = array_column($allStaff,1);
$allStaffName = array_column($allStaff,0);

$bookedUsers = new CHoliday;
$bookedUsers = $bookedUsers->GetSeasonsBookings();

//Compare arrays
$unbookedUserId = array_diff($allStaffId, $bookedUsers);
$unbookedUserId = array_values($unbookedUserId); //for att reseta nycklarna

for($i = 0; $i < count($allStaffId); $i++) {
        for ($j = 0; $j < count($unbookedUserId); $j++) {
                if ($unbookedUserId[$j] == $allStaffId[$i]) {
                    $sinner .= "<li>$allStaffName[$i]</li>";
                }
        }
}
if($sinner == ""){
    $sinner = "Alla har ansökt!";
}


/*foreach ($unbookedUserId as $u) {
    $sinner .= "<li>$u</li>";
}*/
$sinnersList= "<ul>$sinner</ul>";

$html = <<<EOD
<!DOCTYPE html>
<html lang="sv">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h3>Har ännu inte ansökt om ledighet:</h3>
$sinnersList
</body>
</html>
EOD;
echo $html;