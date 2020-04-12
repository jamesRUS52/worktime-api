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
class MainModel extends AppModel {
    //put your code here
    
    
    
    public function dateAction()
    {
        $conn = $this->DB;
        $conf = \Worktime\Config::getInstance();
        $output = array();

        $dt = (isset($this->params['date'])) ? \DateTime::createFromFormat($conf->getDateformat(), $this->params['date']) : new \DateTime();

        $output["result"]["date"]= explode("T", $dt->format(DATE_ATOM))[0];
        $output["apicode"]=200;
        $output["apimessage"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function datetimeAction()
    {
        $conn = $this->DB;
        $conf = \Worktime\Config::getInstance();
        $output = array();

        $dt = new \DateTime();
        $output["result"]["datetime"]=$dt->format($conf->getDatetimeformat());// "d.m.Y H:i");
        $output["apicode"]=200;
        $output["apimessage"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
}
