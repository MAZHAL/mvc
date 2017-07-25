<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 23:58
 */

namespace App\Observer\User;

use Base\Observers;

class UserAdd implements Observers
{
    public function update($event){
        echo '观察者1';
    }
}