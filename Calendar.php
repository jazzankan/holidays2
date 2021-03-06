<?php
/**
 *
 *@author  Xu Ding
 *@email   thedilab@gmail.com
 *@website http://www.StarTutorial.com
 * some additions by Anders Fredriksson
 **/
class Calendar {

    /**
     * Constructor
     */
    public function __construct(){
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }

    /********************* PROPERTY ********************/
    private $dayLabels = array("Måndag","Tisdag","Onsdag","Torsdag","Fredag","Lördag","Söndag");

    private $currentYear=0;

    private $currentMonth=0;

    private $currentDay=0;

    private $currentDate=null;

    private $daysInMonth=0;

    private $naviHref= null;

    private $year = null;

    private $month = null;

    private $weekText = "";

    private $workFree = "";

    private $monthArray = array();

    private $numbersworking = "";

    private $namesworking = "";

    private $allWorkers = array();


    /********************* PUBLIC **********************/

    //Get names to select element, Anders

    public function getWorkers(){
        $workers = new CHoliday;
        $workers = $workers->GetUsers();
        $this->allWorkers = $workers;
        sort($this->allWorkers);
    }

    /**
     * print out the calendar
     */
    public function show() {

        $this->getWorkers();

        if(null==$this->year&&isset($_GET['year'])){

            $year = $_GET['year'];

        }else if(null==$this->year){

            $year = date("Y",time());

        }

        if(null==$this->month&&isset($_GET['month'])){

            $month = $_GET['month'];

        }else if(null==$this->month){

            $month = date("m",time());
            // We want it to be summer or winter holiday time. Anders
            $month = $month > 1 && $month < 9 ? 06 : 12;

        }

        $this->currentYear=$year;

        $this->currentMonth=$month;

        //Get workfree days from free API
        $monthArray = new RedDays();
        $monthArray = $monthArray->freeDays($year, $month);
        $this->monthArray = $monthArray;

        $this->daysInMonth=$this->_daysInMonth($month,$year);

        $content='<div id="calendar">'.
            '<div class="box">'.
            $this->_createNavi().
            '</div>'.
            '<div class="box-content">'.
            '<ul class="label">'.$this->_createLabels().'</ul>';
        $content.='<div class="clear"></div>';
        $content.='<ul class="dates">';

        $weeksInMonth = $this->_weeksInMonth($month,$year);
        // Create weeks in a month
        for( $i=0; $i<$weeksInMonth; $i++ ){

            //Create days in a week
            for($j=1;$j<=7;$j++){
                $content.=$this->_showDay($i*7+$j);
            }
        }

        $content.='</ul>';

        $content.='<div class="clear"></div>';

        $content.='</div>';

        $content.='</div>';

        return $content;

    }

    /********************* PRIVATE **********************/
    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber){

        $this->weekText = "";
        $this->workFree = "";
        $redDay = "";
        $this->numbersworking = "";
        $this->namesworking = "";

        if($this->currentDay==0){

            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));

            if(intval($cellNumber) == intval($firstDayOfTheWeek)){

                $this->currentDay=1;

            }
        }

        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){

            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));

            $redIndex = array_search($this->currentDate,array_column($this->monthArray, 0));
            if($redIndex !== false) {
                $redDay = $this->monthArray[$redIndex][1];
                $this->workFree = "workfree";
            }

            //Get people working (first the number of)
            if($this->workFree !== "workfree"){
            $atJob = new CHoliday;
            $atJob->GetBookings($this->currentDate, $this->allWorkers);
            $atJob = $atJob->iBooking;
            $numberAtJob = $atJob['numbersworking'];
            if(!isset($atJob[0]['realname'])){
               $namesAtJob = "Alla jobbar";
            }
            else {
                $namesAtJob = $atJob['namesworking'];
                sort($namesAtJob);
                $namesAtJob = implode("<br>", $namesAtJob);
            }
            $numcolor= "";
            if($numberAtJob < 10){
               $numcolor = "numred";
            }
            else {
                $numcolor = "numgreen";
            }
            $this->numbersworking = "<span class=$numcolor><b>$numberAtJob</b> jobbar:</span>";
            $this->namesworking = $namesAtJob;
                //var_dump($atJob[1]['namesworking']);
                //echo $this->namesworking;
                //echo "Här tar det slut";
            }

            $cellDate = $this->currentDay;
            if($cellNumber%7==1){
                $week = date($this->currentDate);
                $currentWeekNumber = date("W",strtotime($week));

                $this->weekText = "Vecka: ".$currentWeekNumber." ";
            }

            $this->currentDay++;


        }else{

            $this->currentDate =null;

            $cellDate=null;
            if($cellNumber === 1) {
                $week = date($this->currentYear.'-'.$this->currentMonth.'-01');
                $currentWeekNumber = date("W",strtotime($week));
                $this->weekText = "Vecka: ".$currentWeekNumber." ";
              }
            }

        return '<li id="li-'.$this->currentDate.'" class="'.$this->workFree. ''. ($cellNumber%7==1?' start ':($cellNumber%7==0 || $cellNumber%7==6 ?' end ':' ')).
        ($cellDate==null?'mask':'').'"><span class="week">'.$this->weekText.'</span>'.$cellDate.'<span class="red">'.$redDay.'</span><span class="numbersw"><br>'.
            $this->numbersworking.'</span><br><span class="namesw">'.$this->namesworking.'</span></li>';
    }

    /**
     * create navigation
     */
    private function _createNavi(){

        setlocale(LC_ALL,'sv_SE','Swedish');

        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;

        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;

        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;

        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;

        $working = "<option value='choose'>-- Namn --</option>";
        foreach ($this->allWorkers as $works) {
            $working .= "<option class='applname' value='$works[0];$works[1]'>$works[0]</option>";
}

        return
            '<div class="header">'.
            '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Föregående</a>'.
            '<span class="sinners" data-featherlight="lightbox.php">Ännu inte ansökt</span>'.
            '<span class="title">'.strftime('%Y %B',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
            '<span class="search"> Ansök: '.
            '<form id="apply" name="apply" action="apply.php" method="post">
            <select name="applicant">'.
                  $working.
                '</select></form>
            </span>'.
            '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Nästa</a>'.
            '</div>';
    }
    /**
     * create calendar week labels
     */
    private function _createLabels(){

        $content='';

        foreach($this->dayLabels as $index=>$label){

            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';

        }

        return $content;
    }



    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month=null,$year=null){

        if( null==($year) ) {
            $year =  date("Y",time());
        }

        if(null==($month)) {
            $month = date("m",time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);

        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);

        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));

        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));

        if($monthEndingDay<$monthStartDay){

            $numOfweeks++;

        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month=null,$year=null){

        if(null==($year))
            $year =  date("Y",time());

        if(null==($month))
            $month = date("m",time());

        return date('t',strtotime($year.'-'.$month.'-01'));
    }

}