<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/1/30
 * Time: 0:55
 */

namespace Lib;

class Page
{
    public $total=0;//数据总条数
    public $key='page';//地址栏上传的get键
    public $num=10;//每页默认是10条数据
    public $curr=1;//当前页码
    public $cnt=5;//表示一张页面显示几个页码
    public function __construct($total=0,$num=10,$page=false){
        $this->total=$total;
        $this->num=$num;

        $this->curr=isset($_GET[$this->key])?(int)$_GET[$this->key]:1;//计算当前页码

    }

    /*
     *
     * 负责输出页码
     * */

    public function show(){

       $max=ceil($this->total/$this->num);//计算最大页码
        //计算最左侧页码
        $left=$this->curr-floor(($this->cnt-1)/2);
        $left=max($left,1);

        $right=$left+$this->cnt-1;//计算最you侧页码
        $right=min($right,$max);

        $left=$right-$this->cnt+1;//计算最左侧页码
        $left=max($left,1);

        unset($_GET[$this->key]);

        $code=[];

        for($i=$left;$i<=$right;$i++){
            $list=[];
            $_GET[$this->key]=$i;
           $list['href']='/index.php/'.\X::app()->createUrl();

            $list['page']=$i;
            $code[]=$list;
        }

        return $code;
    }




}
$page=new Page(345);

$page->cnt=10;
$arr=$page->show();
//echo $page->curr;
foreach($arr as $v){
    if($page->curr==$v['page']){
        echo '['.$v['page'].']';
    }else{
        echo "<a href='#'>".'['.$v['page'].']'."</a>";
    }

}

?>
