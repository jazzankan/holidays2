<?php

/**
 * Created by PhpStorm.
 * User: Anders Fredriksson
 * Date: 2016-12-10
 * Time: 17:58
 */
class RedDays
{

    public function freeDays ($selectedYear, $selectedMonth)
    {
        $freeArray = array();
        $month = file_get_contents("http://api.dryg.net/dagar/v2.1/".$selectedYear."/".$selectedMonth."");
        $month = json_decode($month,true);
        foreach ($month['dagar'] as $day) {
            if($day['arbetsfri dag'] == "Ja" && !isset($day['helgdag'])){
                array_push($freeArray,array($day['datum'], ""));
            }
            if($day['arbetsfri dag'] == "Ja" && isset($day['helgdag'])){
                array_push($freeArray,array($day['datum'], $day['helgdag']));
            }
            if(isset($day['klämdag'])){
                array_push($freeArray,array($day['datum'], "Klämdag"));
            }
        }
        return  $freeArray;
    }
}