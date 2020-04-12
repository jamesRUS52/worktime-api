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
class SlaModel extends AppModel {
    //put your code here
    public function indexAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "GET" : $this->getSLA();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }
    
    public function getSLA()
    {
        $startDateTime = $this->params['startperiod']; 
        $endDateTime = $this->params['endperiod'];
        $response  = Calendar::WorkTime($this->params['calendar'],$startDateTime,$endDateTime, $this->params['unit']);
        $work = $response['time'];
        $nonserve = 0;
        $nonserveperiods = json_decode($this->params['nonserveperiods']);
        foreach ($nonserveperiods as $period)
        {
            $resp = Calendar::WorkTime($this->params['calendar'],$period->start,$period->end, $this->params['unit']);
            $nonserve +=$resp['time'];
        }
        $sla = 100 - round($nonserve/$work,5);
        
        $output["response"]=array("worktime"=>$work,"nonservetime"=>$nonserve,"sla"=>$sla,"unit"=>$this->params['unit']);
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";
        
        print json_encode($output, JSON_NUMERIC_CHECK);
        
    }
    
    
}
