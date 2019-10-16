<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worktime\controllers\v1;

use \Worktime\controllers\v1\AppController;
/**
 * Description of Calendar
 *
 * @author james
 */
class CalendarController extends AppController {
    //put your code here
    public function indexAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
        if (!isset($this->params['startdate']))
            throw new \Worktime\Exception("Parameter StartDate is required",400);
        if (!isset($this->params['enddate']))
            throw new \Worktime\Exception("Parameter EndDate is required",400);
    }
    
    public function dateAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
    }
    
    public function datetimeAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
    }
    
    public function listAction()
    {

    }
    
    public function workdaysAction() 
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
        if (!isset($this->params['startdate']))
            throw new \Worktime\Exception("Parameter StartDate is required",400);
        if (!isset($this->params['enddate']))
            throw new \Worktime\Exception("Parameter EndDate is required",400);
    }
    
    public function worktimesAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
        if (!isset($this->params['startdatetime']))
            throw new \Worktime\Exception("Parameter StartDateTime is required",400);
        if (!isset($this->params['enddatetime']))
            throw new \Worktime\Exception("Parameter EndDateTime is required",400);
        if (!isset($this->params['unit']))
            throw new \Worktime\Exception("Parameter unit is required",400);
        if (!in_array($this->params['unit'],["second","minute","hour"]))
            throw new \Worktime\Exception("Parameter unit should be second, minute or hour",400);
    }
    
    public function neartimeAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
        if (!in_array($this->params['unit'],["second","minute","hour"]))
            throw new \Worktime\Exception("Parameter unit should be second, minute or hour",400);
    }
}
