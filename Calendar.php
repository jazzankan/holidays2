<?php
/**
 *@author  Xu Ding
 *@email   thedilab@gmail.com
 *@website http://www.StarTutorial.com
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


    /********************* PUBLIC **********************/

    /**
     * print out the calendar
     */
    public function show() {

        if(null==$this->year&&isset($_GET['year'])){

            $year = $_GET['year'];

        }else if(null==$this->year){

            $year = date("Y",time());

        }

        if(null==$this->month&&isset($_GET['month'])){

            $month = $_GET['month'];

        }else if(null==$this->month){

            $month = date("m",time());

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

            //Trying to get people working (first the number of)
            if($this->workFree !== "workfree"){
            $appliedFor = new CHoliday;
            $appliedFor->GetBookings();
            $appliedFor = $appliedFor->iAllStaffNumber;
            $this->numbersworking = $appliedFor;
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
        ($cellDate==null?'mask':'').'"><span class="week">'.$this->weekText.'</span>'.$cellDate.'<span class="red">'.$redDay.'</span><span class="names"><br>'.$this->numbersworking.'</span></li>';
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

        return
            '<div class="header">'.
            '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Föregående</a>'.
            '<span class="title">'.strftime('%Y %B',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
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