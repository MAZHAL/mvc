<?php

/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 23:35
 */
namespace App\Model;
use Base\Model;
class User extends Model
{

    public function create(){
       return $this->find();
    }
}