<?php
$config = array(
    'home' => array(
        //'logging' =>true,
        'decorator' => array(
            //'App\Decorator\Login',//是否检测登录
            'App\Decorator\Template',//输出的是模板数据
            'App\Decorator\Json',//输出的是json数据
        ),
    ),
    'default' => 'hello world',
);
return $config;