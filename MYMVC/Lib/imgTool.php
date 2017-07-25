<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/1/31
 * Time: 2:59
 */

namespace Lib;


class imgTool
{
    public $img=[];
    public $thumb='';//存储缩略图文件地址
    // imageInfo 分析图片的信息
    // return array()
    public function imageInfo($image) {
        // 判断图片是否存在
        if(!file_exists($image)) {
            return false;
        }

        $info = getimagesize($image);

        if($info == false) {
            return false;
        }

        // 此时info分析出来,是一个数组
        $this->img['width'] = $info[0];
        $this->img['height'] = $info[1];
        $this->img['ext'] = substr($info['mime'],strpos($info['mime'],'/')+1);

        return  $this->img;
    }



    /*
        加水印功能
        parm String $dst 等操作图片
        parm String $water 水印小图
        parm String $save,不填则默认替换原始图
    */
    public  function water($dst,$water,$save=NULL,$pos=2,$alpha=50) {
        // 先保证2个图片存在
        if(!file_exists($dst) || !file_exists($water)) {
            return false;
        }


        // 首先保证水印不能比待操作图片还大
        $dinfo = $this->imageInfo($dst);
        $winfo = $this->imageInfo($water);

        if($winfo['height'] > $dinfo['height'] || $winfo['width'] > $dinfo['width']) {
            return false;
        }

        // 两张图,读到画布上! 但是图片可能是png,可能是jpeg,
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $wfunc = 'imagecreatefrom' . $winfo['ext'];

        if(!function_exists($dfunc) || !function_exists($wfunc)) {
            return false;
        }


        // 动态加载函数来创建画布
        $dim = $dfunc($dst);  // 创建待操作的画布
        $wim = $wfunc($water);  // 创建水印画布


        // 根据水印的位置 计算粘贴的坐标
        switch($pos) {
            case 0: // 左上角
                $posx = 0;
                $posy = 0;
                break;

            case 1: // 右上角
                $posx = $dinfo['width'] - $winfo['width'];
                $posy = 0;
                break;

            case 3: // 左下角
                $posx = 0;
                $posy = $dinfo['height'] - $winfo['height'];
                break;

            default:
                $posx = $dinfo['width'] - $winfo['width'];
                $posy = $dinfo['height'] - $winfo['height'];
        }


        // 加水印
        imagecopymerge ($dim,$wim, $posx , $posy , 0 , 0 , $winfo['width'] , $winfo['height'] , $alpha);

        // 保存
        if(!$save) {
            $save = $dst;
            unlink($dst); // 删除原图
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($dim,$save);

        imagedestroy($dim);
        imagedestroy($wim);

        return true;
    }


    /**
    thumb 生成缩略图
    等比例缩放,两边留白
     **/
    public function thumb($dst,$save=NULL,$width=200,$height=200) {
        // 首先判断待处理的图片存不存在
        $dinfo = $this->imageInfo($dst);
        if($dinfo == false) {
            return false;
        }

        // 计算缩放比例
        $calc = min($width/$dinfo['width'], $height/$dinfo['height']);

        // 创建原始图的画布
        $dfunc = 'imagecreatefrom' . $dinfo['ext'];
        $dim = $dfunc($dst);

        // 创建缩略画布
        $tim = imagecreatetruecolor($width,$height);

        // 创建白色填充缩略画布
        $white = imagecolorallocate($tim,255,255,255);

        // 填充缩略画布
        imagefill($tim,0,0,$white);

        // 复制并缩略
        $dwidth = (int)$dinfo['width']*$calc;
        $dheight = (int)$dinfo['height']*$calc;

        $paddingx = (int)($width - $dwidth) / 2;
        $paddingy = (int)($height - $dheight) / 2;


        imagecopyresampled($tim,$dim,$paddingx,$paddingy,0,0,$dwidth,$dheight,$dinfo['width'],$dinfo['height']);

        // 保存图片
        if(!$save) {
            $save = $dst;
            unlink($dst);
        }

        $createfunc = 'image' . $dinfo['ext'];
        $createfunc($tim,$save);

        imagedestroy($dim);
        imagedestroy($tim);

        return true;

    }
}
$a=new imgTool();
