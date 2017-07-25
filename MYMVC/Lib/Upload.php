<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/1/29
 * Time: 22:36
 */
namespace Lib;
class Upload{
    public $file=[];//存储文件信息
    public $ext='';//存储文件后缀
    public $name='';//文件名
    public $dir='';//创建的文件夹
    public $maxSize=1;//规定上传的文件的大小
    public $allowExt='jpeg,jpg,gif,bmp,png';//允许上传的格式

    public function up($name){

        if( !isset($_FILES[$name]) ){//判断是否有文件上传

            throw new \Exception('no file upload',500);

        }
        if( $_FILES[$name]['error']!==0 ){ //判断文件上传是否成功

            throw new \Exception('upload file is fail',500);

        }else{

            $this->file=$_FILES[$name];

        }
        //判断后缀名是不是允许的
        if( !$this->isAllowExt($this->getExt()) ){

            throw new \Exception('ext is error',500);

        }
        //判断文件大小
        if( !$this->isAllowSize($this->file['size']) ){

            throw new \Exception('file is big',500);

        }

        if( move_uploaded_file($this->file['tmp_name'],APP_PATH.$this->createDir().'/'.$this->createName().'.'.$this->getExt()) ){
            return [
                'url'=>$this->dir.'/'.$this->name.'.'.$this->ext,
                //'dir'=>$this->dir
                'ext'=>$this->ext
            ];
        }else{
            throw new \Exception('upload file is fail',500);
        }
    }
    /*
     *
     * 获取文件后缀
     * */
    public function getExt(){
        $tmp=explode('.',$this->file['name']);
        return $this->ext = strtolower(end($tmp));
    }
    /*
     *
     * 生成文件名
     * */
    public function createName($length=6){
        $str='abcdefghijklmnopqrstuvwxyz1234567890';
        return $this->name = substr(str_shuffle($str),0,$length);
    }
    /*
     *
     * 创建目录
     * */
    public function createDir(){
        $this->dir='/upload/'.date('Ym/d',time());
        $dir=APP_PATH.$this->dir;
        if(is_dir($dir)|| mkdir($dir,0777,true)){
            return $this->dir;
        }else{
            throw new \Exception('create file is fail',500);
        }

    }
    /*
   * 传进去的ext是一个用逗号分割的允许后缀组成的字符串
   * 添加允许的后缀
   *
   * */
    public function setExt($ext){
        $this->allowExt=$ext;
    }
    /*
     * 设置允许上传文件的大小
     * $int,是一个整型，单位是M
     * */
    public function setSize($int){
        $this->maxSize=$int;
    }


    /*
    *判断是不是允许的后缀名；并解决大小写问题
    * 返回布尔值
    *
    * */
    protected function isAllowExt($ext){
        return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));
    }
    /*
     *判断是不是允许的大小
     *
     * */
    protected function isAllowSize($size){
        return $size<=$this->maxSize*1024*1024;
    }


}

?>