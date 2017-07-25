<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 18:11
 */
namespace App\Controller;
use Base\Controller;
use App\Model\T;
class Home extends Controller{
    public function index(){

//       $t = new T();
//       return $t->field('tname')->where(' tid >1 ')->limit(0,1)->select();
       return T::first(['tid'=>2])->data  ;

    }
}