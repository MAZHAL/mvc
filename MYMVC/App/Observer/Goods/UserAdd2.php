<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 23:58
 */

namespace App\Observer;


class UserAdd2
{
    public function update($event){
        echo '观察者2';
    }
}