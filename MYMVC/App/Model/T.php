<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/5
 * Time: 2:17
 */

namespace App\Model;

use Base\Model;
class T extends Model
{
    public function create(){
        return $this->find(1);
    }
}