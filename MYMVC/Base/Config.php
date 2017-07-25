<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 18:16
 */

namespace  Base;


class Config implements \ArrayAccess
{
    protected $path;
    protected $configs = array();

    function __construct($path)
    {
        $this->path = $path;
    }

    function offsetGet($key)
    {
        if (empty($this->configs[$key]))
        {
            $file_path = $this->path.'/'.$key.'.php';
            if(!file_exists($file_path)){
                throw new \Exception($file_path.'not exist');
            }
            $config = require $file_path;
            $this->configs[$key] = $config;
        }
        return $this->configs[$key];
    }

    function offsetSet($key, $value)
    {
        throw new \Exception("cannot write config file.");
    }

    function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }
}