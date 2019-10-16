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
class SlaController extends AppController {
    //put your code here
    public function indexAction()
    {
        if (!isset($this->params['calendar']))
            throw new \Worktime\Exception("Parameter Calendar is required",400);
        if (!isset($this->params['startperiod']))
            throw new \Worktime\Exception("Parameter StartPeriod is required",400);
        if (!isset($this->params['endperiod']))
            throw new \Worktime\Exception("Parameter EndPeriod is required",400);
        if (!isset($this->params['unit']))
            throw new \Worktime\Exception("Parameter unit is required",400);
        if (!isset($this->params['nonserveperiods']))
            throw new \Worktime\Exception("Parameter NonServePeriods is required",400);
    }
    
    
}
