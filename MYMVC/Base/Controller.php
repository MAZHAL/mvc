<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 19:51
 */

namespace  Base;


abstract class Controller
{
    protected $data;
    protected $controller_name;
    protected $view_name;
    protected $template_dir;

    function __construct($controller_name, $view_name)
    {
        $this->controller_name = $controller_name;
        $this->view_name = $view_name;
        $this->template_dir = Application::getInstance()->base_dir.'/../App/View';
    }

    function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    function display($file = '')
    {
        if (empty($file))
        {
            $file = strtolower($this->controller_name).'/'.$this->view_name.'.php';
        }
        $path = $this->template_dir.'/'.$file;

        if(!empty($this->data)){
            //print_r($this->data);
            extract($this->data);
        } else {
            throw new \Exception('data is empty');
        }
        include $path;
    }
}