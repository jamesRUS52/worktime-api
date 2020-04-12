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
class ConfigModel extends AppModel {
    //put your code here
    
    
    public function indexAction()
    {
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case "PATCH" : $this->setConfig();
                break;
            case "GET" : $this->getConfig();
                break;
            case "DELETE" : $this->clearConfig();
                break;
            default : throw new \Worktime\Exception("Method not allowed", 405);
        }
    }


    public function setConfig()
    {
        if (!isset($this->params['requestbody']))
            throw new \Worktime\Exception("Request body is empty or invalid",400);


        $output = array("response"=>null);
        
        $request = $this->params['requestbody'];
        $conf = \Worktime\Config::getInstance();
        $conf->setConfig($request,true);

        $output["response"]["config"]=$conf->getConfig();
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }
    
    public function getConfig()
    {
        $conf = \Worktime\Config::getInstance();

        $output["response"]["config"]=$conf->getConfig();
        $output["status"]["code"]=200;
        $output["status"]["message"]="OK";

        print json_encode($output, JSON_NUMERIC_CHECK);
    }

    public function clearConfig()
    {
        $conf = \Worktime\Config::getInstance();
        
        $conf->Clear();
        $conf->ApplyTimezone();
        $this->getConfig();
    }

}
