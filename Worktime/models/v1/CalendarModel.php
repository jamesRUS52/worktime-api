<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Worktime\models\v1;

use \Worktime\models\AppModel;
use Worktime\Calendar;
/**
 * Description of CalendarModel
 *
 * @author james
 */
class CalendarModel extends AppModel {
    //put your code here
    public function indexAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "GET" : $this->getCalendar();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }
    
    public function listAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "GET" : $this->listCalendars();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }
    
    public function dateAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "GET" : $this->getDate();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }
    
    public function datetimeAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "GET" : $this->getDateTime();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }


    public function listCalendars()
    {
        $conn = $this->DB;
        
        $output = array();
        
        $calendars_sql ="
            select c.id,c.name,extract('year' from min(d.date)) min_year,extract ('year' from max(d.date)) max_year
            from calendars c 
            left join dates d on (c.id=d.calendar_id)
            group by c.id, c.name 
            order by c.id
            ";
        $calendars_stmt = $conn->prepare($calendars_sql);
        $calendars_stmt->execute();
        $calendars_res = $calendars_stmt->fetchAll();
        
        $output["response"] = array();
        foreach ($calendars_res as $calendar)
        {
            $output["response"][] = array(
                "id"=>$calendar['id'],
                "name"=>$calendar['name'],
                "min_year"=>$calendar['min_year'],
                "max_year"=>$calendar['max_year'],
                );
        }
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    
    public function getDate()
    {
        $conn = $this->DB;
        $config = \Worktime\Config::getInstance();
        $output = array();
        
        $date_param = (isset($this->params['date']))? $this->params['date'] : null; 
        $dt = new \Worktime\Date($this->params['calendar'], $date_param);
        
        $output["response"]=$dt->getDate();
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function getDateTime()
    {
        $conn = $this->DB;
        $config = \Worktime\Config::getInstance();
        $output = array();
        $datetime_param = (isset($this->params['datetime']))? $this->params['datetime'] : null; 
        $dt = new \Worktime\DateTime($this->params['calendar'], $datetime_param);
        
        $output["response"]=$dt->getDateTime();
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function getCalendar()
    {
        $startDate = $this->params['startdate']; 
        $endDate = $this->params['enddate'];
        
        $startDate = new \Worktime\Date($this->params['calendar'],$startDate);
        $endDate = new \Worktime\Date($this->params['calendar'],$endDate);
        
        if ($startDate->date >= $endDate->date)
            throw new \Worktime\Exception("End date have to bigger than Start date",400);
        
        $days = array();
        while ($startDate->date <= $endDate->date)
        {
            $days[] = $startDate->getDate();
            $startDate->date->add(new \DateInterval("P1D"));
            $startDate = new \Worktime\Date($this->params['calendar'],$startDate->date); 
        }
        
        $output["response"]=$days;
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function workdaysAction()
    {
        $startDate = $this->params['startdate']; 
        $endDate = $this->params['enddate'];
        
        $startDate = new \Worktime\Date($this->params['calendar'],$startDate);
        $endDate = new \Worktime\Date($this->params['calendar'],$endDate);
        
        if ($startDate->date > $endDate->date)
            throw new \Worktime\Exception("End date have to bigger or equal Start date",400);
        
        $days = array("work"=>0,"nonwork"=>0);
        while ($startDate->date <= $endDate->date)
        {
            if ($startDate->workday)
                $days["work"]++;
            else
                $days["nonwork"]++;
            $startDate->date->add(new \DateInterval("P1D"));
            $startDate = new \Worktime\Date($this->params['calendar'],$startDate->date); 
        }
        
        $output["response"]=$days;
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function worktimesAction()
    {
        $config = \Worktime\Config::getInstance();
        
        $startDateTime = $this->params['startdatetime']; 
        $endDateTime = $this->params['enddatetime'];
        
        $response  = Calendar::WorkTime($this->params['calendar'],$startDateTime,$endDateTime, $this->params['unit']);
        
        $output["response"]=$response;
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    
    
    public function neartimeAction()
    {
        $config = \Worktime\Config::getInstance();
        
        $nowDateTime = new \Worktime\DateTime($this->params['calendar']);
        if (isset($this->params['datetime']))
            $nowDateTime = new \Worktime\DateTime($this->params['calendar'],$this->params['datetime']);
        
        $daywings = 1;
        while ($daywings <= 512) // some more then 1 year to and back from origin date
        {
            $timeline = Calendar::CreateTimeline($this->params['calendar'], $nowDateTime->datetime, $daywings, "D");
            if (count($timeline) > 3)
                break;
            $daywings *= 2;
        }

        $now = $nowDateTime->worktime;
        
        for ($i=0;$i<count($timeline);$i++)
        {
            if (($nowDateTime->datetime->format("U")+$config->getTzoffset() >= $timeline[$i]["time"] && $i==count($timeline)-1) ||
                ($nowDateTime->datetime->format("U")+$config->getTzoffset() >= $timeline[$i]["time"] && $nowDateTime->datetime->format("U")+$config->getTzoffset() < $timeline[$i+1]["time"])) // found interval
            {
                $nowtype =$timeline[$i]["type"];
                $next = (count($timeline)>$i+1) ? $timeline[$i+1]["time"] : null;
                $prev = ($i>0) ? $timeline[$i]["time"] : null;
                $elapse = ($prev !== null) ? ($nowDateTime->datetime->format("U")+$config->getTzoffset()) - $prev : null;
                $remain = ($next !== null) ? $next - ($nowDateTime->datetime->format("U")+$config->getTzoffset()) : null;
                break;
            }
            $dottime = \DateTime::createFromFormat("U", $timeline[$i]["time"]);
        }
        $next = ($next !== null) ? \DateTime::createFromFormat("U",$next) : null;
        $prev = ($prev !== null) ? \DateTime::createFromFormat("U",$prev) : null;
        
        
        $now = boolval($now);
        $next = ($next !== null) ? $next->format($config->getDatetimeformat()) : null;
        $prev = ($prev !== null) ? $prev->format($config->getDatetimeformat()) : null;
        
        switch ($this->params['unit'])
        {
            case "second" :
                break;
            case "minute" :
                $elapse = ($elapse !== null) ? $elapse / 60: null;
                $remain = ($remain !== null) ? $remain / 60: null;
                break;
            case "hour" :
                $elapse = ($elapse !== null) ? $elapse / 3600: null;
                $remain = ($remain !== null) ? $remain / 3600: null;
                break;
                
        }
        
        $output["response"]=array("work"=>$now,"next"=> $next, "prev"=>$prev, "remain"=>$remain, "elapse"=>$elapse);
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
    }
}
