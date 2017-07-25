<?php
namespace Base;
class Model extends EventGenerator{
    protected $table = '';//表名
    protected $db = null;
    protected $fields = [];//存储表信息
    protected $pk = '';//当前表的主键
    public $data = [];//存储orm操作的数据
    protected $option = [];

    public function __construct(){
        parent::__construct();
        $this->getTable();//获取模块的表名
        $this->getDB();//获取数据库的实例
        $this->parseTable();//分析表结构
        $this->reset();//初始化option的数据
    }
    /*
     * 根据Model名分析表明
     * */
    public function getTable(){
        $className=get_called_class();//表名就是model的名字
        $table = explode('\\',trim($className,'\\'));
        $table = array_slice($table,2);
        $this->table = strtolower($table[0]);
    }
    /*
     * 获取db的实例用于查询数据库
     * */
    public  function getDB(){
        $this->db = Factory::getDatabase();
    }
    /*
     *
     * 根据主键来删除一条信息
     * */
    public function remove($id){
        $sql='delete from '.$this->table.' where '.$this->pk.'=:id';
        return $this->db->delete($sql,[':id'=>$id]);
    }
    /*
     *
     * 增加数据
     * */
    public function add($data=[]){
        $num=func_num_args();
        if($num===0){
            $data=$this->data;
            $this->data=[];
        }
        $data=$this->facade($data);
        if(empty($data)){
            throw new \Exception('data obj is empty',500);
        }
        $keys = implode( ',',array_keys($data) );
        // $values=implode( ',',array_values($data) );
        $v=substr(str_repeat('?,',count($data)),0,-1);
        $sql='insert into '.$this->table.' ('.$keys.') values ('.$v.')';

        return $this->db->insert($sql,array_values($data));

    }
    /*
     *
     *更改数据
     * $data含有主键，即根据主键进行更改
     * */
    public function save($data=[]){
        $num=func_num_args();
        if($num===0){
            $data=$this->data;
            $this->data=[];
        }
        if(empty($data)){
            throw new \Exception('data obj is empty',500);
        }
        if(!isset($data[$this->pk])){
            throw new \Exception('need the params key');
        }
        $clonePk =$data[$this->pk];

        unset($data[$this->pk]);
        $sql='update '.$this->table.' set ';
        foreach($data as $k=>$v){
            $sql.=$k.'=? ,';
        }
        $sql=substr($sql,0,-1);
        $sql.=' where '.$this->pk.'= ?';
        $data[$this->pk]=$clonePk;
        return $this->db->update($sql,array_values($data));
    }

    /*
     *
     * 根据主键查询
     * */
    public function find($id){

        $sql='select * from '.$this->table.'  where '.$this->pk.'=:id';

        return $this->data = $this->db->getRow($sql,[':id'=>$id]);
    }
    public function parseTable(){
        $info=$this->db->getAll('desc '.$this->table);
        foreach($info as $v){
            $this->fields[]=$v['Field'];
            if($v['Key']==='PRI'){
                $this->pk=$v['Field'];
            }
        }

    }
    //orm操作 读取data里的数据
    public function __get($key){
        return isset($this->data[$key])?$this->data[$key]:null;
    }
    //orm 操作 设置data里的数据
    public function __set($key,$value){
        $this->data[$key]=$value;
    }
    /*
     *
     * select 查询
     * */
    public function select(){
        $sql = $this->parseSql();
        //$sql='select '.$this->option['cols'].' from '.$this->table.$this->option['where'].$this->option['group'].$this->option['order'].$this->option['limit'];
        return $this->db->getAll($sql);
    }
    /*
     *
     * 用指定的列查询
     * @param $cols 默认值是string 要查询的列名
     * */
    public function field($cols=''){

        $this->option['cols']=$cols;
        return $this;
    }
    /*
     *
     * where条件
     * @params $cond  默认 array 比如['name'=>'张三','age'=>20]会拼凑成where name='张三'and age=20
     *
     * */
    public function where($cond=''){
        $tmp=' where ';
        if(is_array($cond)){

            $len=count($cond);
            if($len>1){
                foreach($cond as $k=>$v){
                    $tmp.=$k.'=\''.$v.'\' and ';
                }
                $tmp=substr($tmp,0,-4);
            }else{
                foreach($cond as $k=>$v){
                    $tmp.=$k.'=\''.$v.'\'';
                }
            }

            $this->option['where']=$tmp;
        }else if( is_string($cond)){
            $this->option['where']= $tmp.$cond;
        }
        return $this;
    }
    /*
     *
     * group 条件
     * */
    public function group($col=''){
        if($col){
            $this->option['group']=' group by '.$col;
        }
        return $this;
    }
    /*
     *
     * having
     * */
    public function having($col=''){
        if($col){
            $this->option['having']=' having '.$col;
        }
        return $this;
    }
    /*
     * order
     * */
    public function order($col='',$a=''){
        if($col){
            $this->option['order'] = ' order by '.$col.' '.$a;
        }
        return $this;
    }
    /*
     * limit
     * */
    public function limit($offset,$n=null){

        if($n==null){
            $n=$offset;
            $offset=0;
        }

        $this->option['limit']=' limit '.$offset.','.$n;

        return $this;
    }
    /*
     *
     *  'cols'=>'*',
        'where'=>'',
        'group'=>'',
        'having'=>'',
        'order'=>'',
        'limit'=>''
     * */
    public function parseSql(){
        $sql = 'select %s from %s %s %s %s %s %s';
        $sql = sprintf($sql,$this->option['cols'],$this->table,$this->option['where'],$this->option['group'],$this->option['having'],$this->option['order'],$this->option['limit']);
        return $sql;
    }
    /*
     *
     * 还原optio的数据，
     * 防止影响下一次的查询；
     * */
    public function reset(){
        $this->option=[
            'cols'=>'*',
            'where'=>'',
            'group'=>'',
            'having'=>'',
            'order'=>'',
            'limit'=>''
        ];
    }
    /*
     *
     * 过滤非法字段
     * */
    public function facade($data){
        foreach($data as $k=>$v){
            if(!in_array($k,$this->fields)){
                unset($data[$k]);
            }
        }
        return $data;
    }

    public function __call($name,$params=[]){

        if($name == 'first'){
            $keys = array_keys(...$params)[0];
            $values = array_values(...$params)[0];
            $sql = $sql='select * from '.$this->table.'  where '.$keys.'=:val';
            $this->data = $this->data = $this->db->getRow($sql,[':val'=>$values]);
            return $this;
        }else{
            throw new \Exception('method not find');
        }

    }
    public static function __callStatic($name, $arguments){
        $instance = new static;

        return call_user_func_array([$instance, $name], $arguments);
    }

}