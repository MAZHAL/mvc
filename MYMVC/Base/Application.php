<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 17:45
 */

namespace Base;


class Application
{
    public $base_dir;
    protected static $instance;
    public $config;

    protected function __construct($base_dir)
    {

        if(defined('APP_DEBUG')){
            $this->initSystemHandlers();
        }else{
            error_reporting(0);
        }
        $this->base_dir = $base_dir;
        $this->config = Factory::getConfig($base_dir.'/../Configs');


    }

    /*
     *
     * 错误接管
     * */
    public function initSystemHandlers(){
        set_error_handler([$this,'handlerError']);
        set_exception_handler([$this,'handlerException']);
    }
    /*
     *
     * 报错处理
     * 把错误打包成异常
     *
     *
     * */
    public function handlerError($errno,$errstr,$file,$line){
        //出错了抛出异常对象， 自定义异常函数handlerException会自动接收错误处理函数抛出的异常
        $exception=new \ErrorException($errstr,$errno,1,$file,$line);

        throw $exception;
    }
    /*
     *
     * 异常处理
     * */
    public function handlerException($exception){
        $this->handler($exception);
    }
    public function handler($exception){
        $msg=$exception->getMessage();
        $file=$exception->getFile();
        $line=$exception->getLine();
        echo 'line',$line ,'<br/>',$msg,$file,'<br/><pre>';
        //echo $line,'<br/>';
        $trace=$exception->getTrace();
        if($exception instanceof \ErrorException){
            array_shift($trace);
        }
        print_r($trace);
    }


//单例模式
    static function getInstance($base_dir = '')
    {
        if (self::$instance == null && !(self::$instance instanceof self))
        {
            self::$instance = new self($base_dir);
        }
        return self::$instance;
    }

    protected function revoke()
    {
        //获取路由参数
        static $ca=null;
        if($ca!==null){
            return $ca;
        }
        $path = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
        $path = trim($path,'/');
        if($path==''){
            $path = [];
        }else{
            $path = explode('/',trim($path,'/'));
        }
        $ca = $path+['Index','index'];//如果键名为数组，数组相加会将最先出现的值作为结果，后面键名相同的会被抛弃
        //分析地址栏参数
        $params = array_slice($path,2);//切割掉前两个

        for($i = 0,$len = count($params); $i<$len-1; $i+=2){
            $_GET[$params[$i]]=$params[$i+1];
        }
        // print_r($params);exit;
        return $ca;
    }
    /*
     *
     * 创建控制器
     * */
    public function dispatch(){
        list($c, $v) = $this->revoke();
        $c_low = strtolower($c);
        $c = ucwords($c);
        $class = '\\App\\Controller\\'.$c;

        if(!class_exists($class)){
            throw new \Exception('not find class');
        }
        $obj = new $class($c, $v);

        //加载装饰器
        $controller_config = $this->config['controller'];
        $decorators = [];

        if (isset($controller_config[$c_low]['decorator']))
        {
            $conf_decorator = $controller_config[$c_low]['decorator'];
            foreach($conf_decorator as $dec)
            {
                $decorators[] = new $dec;
            }
        }
        foreach($decorators as $decorator)
        {
            $decorator->beforeRequest($obj);
        }
        $return_value = $obj->$v();
        foreach($decorators as $decorator)
        {
            $decorator->afterRequest($return_value);
        }
    }



}