<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 20:44
 */
namespace Base;


abstract class EventGenerator
{
    public $observers = [];
    public function __construct()
    {
        $name = strtolower(str_replace('App\Model\\', '', get_class($this)));
        if (!empty(Application::getInstance()->config['model'][$name]['observer']))
        {
            $observers = Application::getInstance()->config['model'][$name]['observer'];
            foreach($observers as $class)
            {
                $this->observers[] = new $class;
            }
        }

    }

    public function notify($event)
    {
        foreach($this->observers as $observer)
        {
            $observer->update($event);
        }
    }
}