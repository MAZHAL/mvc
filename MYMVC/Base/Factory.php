<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 20:12
 */
namespace Base;

class Factory{
    static $proxy = null;

    /**
     * 获取配置文件
     * @param $dir
     * @return Config|bool
     */
    static function getConfig($dir){
        $key = 'config';
        $config = Register::get('config');
        if(!$config){
            $config = new Config($dir);
            Register::set($key, $config);
        }
        return $config;
    }

    /**
     * @param $name
     * @return bool
     * @throws \Exception
     */
    static function getModel($name)
    {
        $key = 'app_model_'.$name;
        $model = Register::get($key);
        if (!$model) {
            $class = '\\App\\Model\\'.ucwords($name);
            if(!class_exists($class)){
                throw new \Exception($class.' not find');
            }
            $model = new $class;
            Register::set($key, $model);
        }
        return $model;
    }

    /**
     * 代理模式，主从数据库连接
     * @param string $id
     * @return Database\DB|Database\Proxy|bool|null
     * @throws \Exception
     */
    static function getDatabase($id = 'proxy')
    {
        if ($id == 'proxy')
        {
            if (!self::$proxy)
            {

                self::$proxy = new \Base\Database\Proxy;
            }
            return self::$proxy;
        }
        $key = 'database_'.$id;
        if ($id == 'slave')
        {
            $slaves = Application::getInstance()->config['database']['slave'];
            if(empty($slaves)){
                throw new \Exception('database not find');
            }
            $db_conf = $slaves[array_rand($slaves)];


        }
        else
        {
            $db_conf = Application::getInstance()->config['database'][$id];

        }
        if(empty($db_conf)){
            throw new \Exception('database not find');
        }
        $db = Register::get($key);
        if (!$db) {
            $db = new Database\DB($db_conf);
            Register::set($key, $db);
        }
        return $db;
    }

}