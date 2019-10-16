<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Worktime\models;

use jamesRUS52\phpfrm\base\Model;

/**
 * Description of AppModel
 *
 * @author james
 */
class AppModel extends Model{
    //put your code here
    /**
     *
     * @var \PDO
     */
    public $DB;

    public function __construct() {
        $this->DB = \jamesRUS52\phpfrm\DB::getInstance();
    }
}
