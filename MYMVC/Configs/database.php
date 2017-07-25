<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 17:27
 */
$config = array(
    'master' => array(
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '123456',
        'charset' =>'utf8',
        'dbname' => 'mm',
    ),
    'slave' => array(
        'slave1' => array(
            'type' => 'mysql',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => '123456',
            'charset' =>'utf8',
            'dbname' => 'mm',
        ),
        'slave2' => array(
            'type' => 'mysql',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => '123456',
            'charset' =>'utf8',
            'dbname' => 'mm',
        ),
    ),
);
return $config;