<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worktime;

/**
 * Description of Date
 *
 * @author james
 */
class Date {
    //put your code here
    /**
     *
     * @var \DateTime
     */
    public $date;
    public $workday;
    public $short=false;
    public $worktime = array();
    public $calendar;
            
    function __construct($calendar, $date=null) {
        $config = \Worktime\Config::getInstance();
        if ($date instanceof \DateTime)
            $this->date = $date;
        else
            $this->date = ($date!==null) ? \DateTime::createFromFormat($config->getDateformat(), $date) : new \DateTime();
        $this->date->setTime(0, 0, 0);
        
        $this->calendar = $calendar;
        
        $this->createobj();
    }
    
    function __clone() {
        $this->date = clone $this->date;
    }

    private function createobj()
    {
        $config = \Worktime\Config::getInstance();
        $conn = \jamesRUS52\phpfrm\DB::getInstance();
        
        $calendar_sql = "select id from calendars where id=?";
        $calendar_stmt = $conn->prepare($calendar_sql);
        $calendar_stmt->execute([$this->calendar]);
        if ($calendar_stmt->fetchColumn() === false)
            throw new Exception("Calendar {$this->calendar} not found",400);


        $date_sql = "select datetype, short from dates where calendar_id=? and date=?";
        $date_stmt = $conn->prepare($date_sql);
        $date_stmt->execute([$this->calendar, $this->date->format("Y-m-d")]);
        $date_res = $date_stmt->fetch();

        if ($date_res)
        {
            $this->workday = ($date_res['datetype']=="holiday") ? false : true;
            $this->short = ($date_res['short']!==null) ? $date_res['short'] : false;
        }
        else 
        {
            $day_num = $this->date->format("N");
            $this->workday = (in_array($day_num, $config->getWorkdays())) ? true : false;
            $this->short = false;
        }
//        $this->workday = true;
//        $this->short = false;
        
        if ($this->workday)
        {
            if (!$this->short)
            {
                $this->worktime[] = array("start"=>$config->getTimestart(true),"end"=>$config->getTimeend(true));
            }
            else 
            {
                $this->worktime[] = array("start"=>$config->getTimestartshort(true),"end"=>$config->getTimeendshort(true));
            }
        }
    }

    public function AddPeriod($interval)
    {
        $this->date->add($interval);
        $this->createobj();
    }

    public function getDate()
    {
        $config = \Worktime\Config::getInstance();
        return array("date"=>$this->date->format($config->getDateformat()),
            "workday"=>$this->workday,
            "short"=> $this->short,
            "worktime" => $this->worktime
            );
    }

}
