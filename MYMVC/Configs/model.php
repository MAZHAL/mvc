<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 17:27
 */
$config = array(
    'user' => array(
        'observer' => array(
            'App\Observer\User\UserAdd1',
            'App\Observer\User\UserAdd2',
            //'App\Observer\UserAdd3',
            //'App\Observer\UserAdd4',
        ),
    ),
    'goods' => array(
        'observer' => array(
            'App\Observer\Goods\UserAdd1',
            'App\Observer\Goods\UserAdd2',
            //'App\Observer\UserAdd3',
            //'App\Observer\UserAdd4',
        ),
    ),
);
return $config;