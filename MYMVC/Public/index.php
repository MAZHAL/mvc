<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 17:26
 */
define('BASEDIR',__DIR__);
define('APP_DEBUG',true);
require BASEDIR.'/../Base/Loader.php';
spl_autoload_register('\\Base\\Loader::autoload');
\Base\Application::getInstance(__DIR__)->dispatch();
