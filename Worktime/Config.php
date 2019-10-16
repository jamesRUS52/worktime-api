<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worktime;

/**
 * Description of Config
 *
 * @author james
 */
class Config {
    
    use \jamesRUS52\phpfrm\TSingleton;
    
    private function __construct() {

    }
    
//    public function __toString() {
//        return json_encode($this, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
//    }
    
    // Restore Config object from Session
    public function __wakeup() {
        // When session is starting in APP() . it invoke this method to recreate object 
        self::$instance = $_SESSION['config'];
    }
    

    //put your code here
    private $timezone = "Europe/Moscow";
//    private $calendar = "RUS";
    private $dateformat = "Y-m-d";
//    private $datetimeformat = \DateTime::RFC3339;
    private $datetimeformat = "Y-m-d H:i:s";
    private $default = true;
    private $holidays = array(6,7); // Sat, Sun
    private $workdays = array(1,2,3,4,5); //Mon - Fr
    private $timestart = 9*60*60;
    private $timeend = 18*60*60;
    private $timestartshort = 9*60*60;
    private $timeendshort = 17*60*60;
    private $worktime = array ();
    private $tzoffset;
            
    function getTimezone() {
        return $this->timezone;
    }

    function getCalendar() {
        return $this->calendar;
    }

    function getDefault() {
        return $this->default;
    }

    function setTimezone($timezone) {
        $this->timezone = $timezone;
    }

    function setCalendar($calendar) {
        $this->calendar = $calendar;
    }

    function setDefault($default) {
        $this->default = $default;
    }

    function getDateformat() {
        return $this->dateformat;
    }

    function getDatetimeformat() {
        return $this->datetimeformat;
    }

    function setDateformat($dateformat) {
        $this->dateformat = $dateformat;
    }

    function setDatetimeformat($datetimeformat) {
        $this->datetimeformat = $datetimeformat;
    }

    function getHolidays() {
        return $this->holidays;
    }

    function getWorkdays() {
        return $this->workdays;
    }

    function setHolidays($holidays) {
        $this->holidays = $holidays;
    }

    function setWorkdays($workdays) {
        $this->workdays = $workdays;
    }
    
    function getTzoffset() {
        return $this->tzoffset;
    }

    
    private function SecondsToString($timeseconds)
    {
        $hour = floor($timeseconds/3600);
        $minutes = $timeseconds % 3600;
        $minute = floor($minutes / 60);
        $seconds = $minutes % 60 ; 
        $dt = new \DateTime();
        $dt->setTime($hour, $minute, $seconds);
        return $dt->format("H:i:s");
    }
    
    function getTimestart($format=false) 
    {
        if ($format)
            return $this->SecondsToString($this->timestart);
        else
            return $this->timestart;
    }

    function getTimeend($format=false) {
        if ($format)
            return $this->SecondsToString($this->timeend);
        else
            return $this->timeend;
        
    }

    function setTimestart($timestart) {
        $this->timestart = $timestart;
    }

    function setTimeend($timeend) {
        $this->timeend = $timeend;
    }

    function getTimestartshort($format=false) {
        if ($format)
            return $this->SecondsToString($this->timestartshort);
        else
            return $this->timestartshort;
    }

    function getTimeendshort($format=false) {
        if ($format)
            return $this->SecondsToString($this->timeendshort);
        else
            return $this->timeendshort;
    }

    function setTimestartshort($timestartshort) {
        $this->timestartshort = $timestartshort;
    }

    function setTimeendshort($timeendshort) {
        $this->timeendshort = $timeendshort;
    }

                    
    public function getConfig()
    {
        $output = array();
//        $output['calendar'] = $this->calendar;
        $output['timezone'] = $this->timezone;
        $output['dateformat'] = $this->dateformat;
        $output['datetimeformat'] = $this->datetimeformat;
        
        $dtstartfull = $this->SecondsToString($this->timestart);
        $dtendfull = $this->SecondsToString($this->timeend);
        $output['worktime'][] = array("short"=>false,"start"=>$dtstartfull,"end"=>$dtendfull);
        
        $dtstartshort = $this->SecondsToString($this->timestartshort);
        $dtendshort = $this->SecondsToString($this->timeendshort);
        $output['worktime'][] = array("short"=>true,"start"=>$dtstartshort,"end"=>$dtendshort);
        
        return $output;
    }
    
    public function setConfig($request)
    {
        
        foreach ($request as $param => $val)
        {
            if (property_exists(get_class($this),$param))
            {
                $this->$param = $val;
            }
        }
        
        $this->ApplyTimezone();
        $this->ApplyWorkTime();
        
        $_SESSION['config']=$this;
        
        
    }
    
    public function ApplyTimezone()
    {

        //throw new \Worktime\Exception("exc",400);
        try
        {
            $tz = new \DateTimeZone($this->timezone);
            @date_default_timezone_set($this->timezone);
            $this->tzoffset = $tz->getOffset(new \DateTime());
        }
        catch (\Exception $e)
        {
            throw new Exception("Unknown or bad timezone {$this->timezone}",400);
        }
    }
    
    public function ApplyWorkTime()
    {
        foreach ($this->worktime as $key=>$value)
        {
            if ($value['short'] === TRUE)
            {
                list($hour,$minute,$seconds) = explode(":", $value['start']);
                $this->timestartshort = $hour*3600 + $minute*60 + $seconds;
                list($hour,$minute,$seconds) = explode(":", $value['end']);
                $this->timeendshort = $hour*3600 + $minute*60 + $seconds;
            } 
            else if ($value['short'] === FALSE)
            {
                list($hour,$minute,$seconds) = explode(":", $value['start']);
                $this->timestart = $hour*3600 + $minute*60 + $seconds;
                list($hour,$minute,$seconds) = explode(":", $value['end']);
                $this->timeend = $hour*3600 + $minute*60 + $seconds;
            }
        }
    }

    public function Clear()
    {
        unset($_SESSION['config']);
        self::$instance = null;
        $_SESSION['config'] = self::getInstance();
    }
}
