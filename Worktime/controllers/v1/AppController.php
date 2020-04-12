<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Worktime\controllers\v1;

use \jamesRUS52\phpfrm\base\Controller;
/**
 * Description of AppController
 *
 * @author james
 */
class AppController extends Controller {
    //put your code here
    
    public function __construct($route) {
        parent::__construct($route);
        
        header("Content-Type: Application/json");
        
        $conf = \Worktime\Config::getInstance();
        
        $this->runview = false;

        $requestbody = $this->getRequestBody();
        if ($requestbody !== null)
            $this->params['requestbody'] = $requestbody;

        if (isset($_SERVER['HTTP_X_CONFIG']) && !empty($_SERVER['HTTP_X_CONFIG']))
        {
            $config_param = json_decode($_SERVER['HTTP_X_CONFIG'], true);
            $conf->setConfig($config_param);
        }
        $conf->ApplyTimezone();
        
    }
    
    private function getRequestBody()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        return $request;
    }
}
