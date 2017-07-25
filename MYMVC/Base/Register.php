<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 20:21
 * 注册器
 */

namespace Base;


class Register
{
    protected static $objects;

    static function set($alias, $object)
    {
        self::$objects[$alias] = $object;
    }

    static function get($key)
    {
        if (!isset(self::$objects[$key]))
        {
            return;
        }
        return self::$objects[$key];
    }

    function _unset($alias)
    {
        unset(self::$objects[$alias]);
    }
}