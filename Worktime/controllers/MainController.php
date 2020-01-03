<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Worktime\controllers;
/**
 * Description of MainController
 *
 * @author james
 */
class MainController extends AppController {
    //put your code here
    public function indexAction()
    {
        $log = \jamesRUS52\phpfrm\Log::getInstance();
        $log->err("qwe");
    }
}
