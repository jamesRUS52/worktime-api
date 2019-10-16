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
class DateTime {
    //put your code here
    /**
     *
     * @var \DateTime
     */
    public $datetime;
    public $workday;
    public $worktime;
    public $short;
            
    function __construct($calendar, $datetime=null) {
        $config = \Worktime\Config::getInstance();
        $this->datetime = ($datetime!==null) ? \DateTime::createFromFormat($config->getDatetimeformat(), $datetime) : new \DateTime();

        $date = new Date($calendar, clone $this->datetime);
        $this->workday = $date->workday;
        $this->short = $date->short;
        
        $current_seconds_in_day = $this->datetime->format("H")*60*60 + $this->datetime->format("i")*60+$this->datetime->format("s");
        $config_start_second = ($date->short === false) ? $config->getTimestart() : $config->getTimestartshort();
        $config_end_second = ($date->short === false) ? $config->getTimeend() : $config->getTimeendshort();
        
        if ($this->workday && $current_seconds_in_day >= $config_start_second && $current_seconds_in_day < $config_end_second)
            $this->worktime = true;
        else
            $this->worktime = false;

    }
    
    function __clone() {
        $this->datetime = clone $this->datetime;
    }
    
    public function getDateTime()
    {
        $config = \Worktime\Config::getInstance();
        return array(
            "datetime"=>$this->datetime->format($config->getDatetimeformat()),
            "workday"=>$this->workday,
            "worktime"=> $this->worktime
                );
    }

    public function TimeToSeconds()
    {
        return $this->datetime->format("H")*3600+$this->datetime->format("i")*60+$this->datetime->format("s");
    }
}
