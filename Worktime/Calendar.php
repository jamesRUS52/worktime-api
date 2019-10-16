<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worktime;

/**
 * Description of Calendar
 *
 * @author james
 */
class Calendar {
    
    private $calendar = 'RUS';
    private $timezone = 'Europe/Moscow';
    private $holidays = array(6,7); // Sat, Sun
    private $holidates = array();
    private $workdays = array(1,2,3,4,5); //Mon - Fr
    private $workdates = array();
    private $clear_defaults = false; // clear general work holidays in database
    //put your code here
    function Calendar()
    {
    }
    
    /**
     * 
     * @param string $calendar
     * @param \DateTime $date
     * @param int $count
     * @param string $unit
     */
    public static function CreateTimeline($calendar,$date,$count,$unit)
    {
        $config = \Worktime\Config::getInstance();
        
        $startdate = clone $date;
        $startdate->sub(new \DateInterval("P{$count}{$unit}"));
        $stopdate = clone $date;
        $stopdate->add(new \DateInterval("P{$count}{$unit}"));
        
        $currentperiod = null;
        $timeline = array();
        
        $tzoffset = $config->getTzoffset();
        
        $i=0;
        while ($startdate <= $stopdate)
        {
            $date = new \Worktime\Date($calendar,clone $startdate);
            
            $configstarttime = ($date->short) ? $config->getTimestartshort() : $config->getTimestart();
            $configendtime = ($date->short) ? $config->getTimeendshort() : $config->getTimeend();
            
            if ($currentperiod === null)
            {
                if ($date->workday)
                {
                    if ($configendtime<86400)
                    {
                        $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configstarttime,"type"=>"work");
                        $i++;
                        $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configendtime,"type"=>"nonwork");
                        $i++;
                        //$timeline[$date->date->format("U")+$tzoffset+$configendtime]="nonwork";
                        $currentperiod = "nonwork";
                    }
                    else
                    {
                        //$timeline[$date->date->format("U")+$tzoffset+$configstarttime]="work";
                        $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configstarttime,"type"=>"work");
                        $i++;
                        $currentperiod = "work";
                    }
                }
                else
                {
                    //$timeline[$date->date->format("U")+$tzoffset]="nonwork";
                    $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset,"type"=>"nonwork");
                    $i++;
                    $currentperiod = "nonwork";
                }
            }
            else
            {
                if ($currentperiod == "nonwork")
                {
                    if ($date->workday)
                    {
                        if ($configendtime<86400)
                        {
                            //$timeline[$date->date->format("U")+$tzoffset+$configstarttime]="work";
                            $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configstarttime,"type"=>"work");
                            $i++;
                            //$timeline[$date->date->format("U")+$tzoffset+$configendtime]="nonwork";
                            $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configendtime,"type"=>"nonwork");
                            $i++;
                            $currentperiod = "nonwork";
                        }
                        else
                        {
                            //$timeline[$date->date->format("U")+$tzoffset+$configstarttime]="work";
                            $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configstarttime,"type"=>"work");
                            $i++;
                            $currentperiod = "work";
                        }
                    }
                }
                else
                {
                    if ($date->workday)
                    {
                        if ($configendtime<86400)
                        {
                            //$timeline[$date->date->format("U")+$tzoffset+$configendtime]="nonwork";
                            $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset+$configendtime,"type"=>"nonwork");
                            $i++;
                            $currentperiod = "nonwork";
                        }
                    }
                    else
                    {
                        //$timeline[$date->date->format("U")+$tzoffset]="nonwork";
                        $timeline[$i]=array("time"=>$date->date->format("U")+$tzoffset,"type"=>"nonwork");
                        $i++;
                        $currentperiod = "nonwork";
                    }
                }
            }
            $startdate->add(new \DateInterval("P1D"));
        }
        return $timeline;
    }
    
    public static function WorkTime($calendar,$start,$end, $unit)
    {
        $config = \Worktime\Config::getInstance();
        
        $startDateTime = new \Worktime\DateTime($calendar,$start);
        $endDateTime = new \Worktime\DateTime($calendar,$end);
        
        $startDate = new \Worktime\Date($calendar,clone $startDateTime->datetime);
        $endDate = new \Worktime\Date($calendar,clone $endDateTime->datetime);
        
        if ($startDateTime->datetime > $endDateTime->datetime)
            throw new \Worktime\Exception("End datetime have to bigger or equal Start datetime",400);
        
        $totalseconds = 0;
        if ($startDate->date == $endDate->date)
        {
            if ($startDateTime->workday)
            {
                $configstarttime = ($startDate->short) ? $config->getTimestartshort() : $config->getTimestart();
                $configendtime = ($startDate->short) ? $config->getTimeendshort() : $config->getTimeend();
                $starttime = ($configstarttime >= $startDateTime->TimeToSeconds()) ? $configstarttime : $startDateTime->TimeToSeconds();
                $endtime = ($configendtime >= $endDateTime->TimeToSeconds()) ? $endDateTime->TimeToSeconds() : $configendtime;
                $totalseconds = $endtime - $starttime;
            }
        }
        else
        {
            $nowDate = clone $startDate;
            
            while ($nowDate->date <= $endDate->date)
            {
                if ($nowDate->workday)
                {
                    $configstarttime = ($nowDate->short) ? $config->getTimestartshort() : $config->getTimestart();
                    $configendtime = ($nowDate->short) ? $config->getTimeendshort() : $config->getTimeend();
                    if ($nowDate->date == $startDate->date)
                    {
                        if ($startDateTime->TimeToSeconds() < $configstarttime)
                        {
                            $totalseconds += $configendtime - $configstarttime;
                        }
                        else if ($startDateTime->TimeToSeconds() >= $configstarttime && $startDateTime->TimeToSeconds()< $configendtime)
                        {
                            $totalseconds += $configendtime - $startDateTime->TimeToSeconds();
                        }

                    }
                    else  if ($nowDate->date == $endDate->date)
                    {
                        if ($endDateTime->TimeToSeconds() >= $configendtime)
                        {
                            $totalseconds += $configendtime - $configstarttime;
                        }
                        else if ($endDateTime->TimeToSeconds() >= $configstarttime && $endDateTime->TimeToSeconds()< $configendtime)
                        {
                            $totalseconds += $endDateTime->TimeToSeconds() - $configstarttime;
                        }

                    }
                    else
                    {
                        $totalseconds += $configendtime - $configstarttime;

                    }
                }
                $nowDate->AddPeriod(new \DateInterval("P1D"));

            }
        }
        
        $totaltime = 0;
        switch ($unit)
        {
            case "second" : $totaltime = $totalseconds;
                break;
            case "minute" : $totaltime = $totalseconds/60;
                break;
            case "hour" : $totaltime = $totalseconds/3600;
                break;
        }
        
        return array("time"=>$totaltime,"unit"=> $unit);
    }

}
