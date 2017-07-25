<?php
namespace  Base;
class Loader
{
    static function autoload($class)
    {
        $path = BASEDIR.'/../'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        if(!file_exists($path)){
            throw new \Exception($path.'not find');
        }
        require $path;
    }
}
