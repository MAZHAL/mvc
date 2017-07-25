<?php
/**
 * Created by PhpStorm.
 * User: MR.L
 * Date: 2017/7/4
 * Time: 20:14
 */

namespace Base\Database;

class DB extends \PDO
{
    public function __construct($cfg){
        $dsn = $cfg['type'].':host='.$cfg['host'].';dbname='.$cfg['dbname'];
        parent::__construct($dsn,$cfg['user'],$cfg['password']);
        $this->charset($cfg['charset']);
    }
    /*
     * 选择数据库
     * */
    public  function useDB($db){
        $this->exec('use '.$db);
    }
    /*
     *
     * 设置字符集
     * */
    public function charset($char){
        $this->exec('set names '.$char);
    }
    /*
     *
     * 查询一行
     * @$sql string 预处理sql
     * @$params  array()  关联数组，或索引树组，
     * */
    public function getRow($sql,$params=[]){
        $st = $this->prepare($sql);
        if($st->execute($params)){
            return $st->fetch(\PDO::FETCH_ASSOC);

        }else{
            list(,$errno,$errstr) = $st->errorInfo();
            throw new \Exception($errstr,$errno);

        }
    }
    /*
     * 查询多行
     * */
    public function getAll($sql,$params=[]){
        $st = $this->prepare($sql);

        if($st->execute($params)){
            return $st->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            list(,$errno,$errstr)=$st->errorInfo();
            throw new \Exception($errstr,$errno);

        }
    }
    /*
     *
     * 删除数据
     * */
    public function delete( $sql,$params=[] ){
        $st = $this->prepare($sql);
        if($st->execute($params)){
            return $st->rowCount();
        }else{
            list(,$errno,$errstr)=$st->errorInfo();
            throw new \Exception($errstr,$errno);

        }
    }
    /*
     *
     * 增加数据
     * */
    public function insert($sql,$params=[]){
        $st=$this->prepare($sql);
        if($st->execute($params)){
            return $this->lastInsertId();
        }else{
            list(,$errno,$errstr)=$st->errorInfo();
            throw new \Exception($errstr,$errno);

        }
    }
    /*
     *
     * 修改数据
     * */
    public function update($sql,$params=[]){
        $st=$this->prepare($sql);
        if($st->execute($params)){
            return $st->rowCount();
        }else{
            list(,$errno,$errstr)=$st->errorInfo();
            throw new \Exception($errstr,$errno);

        }
    }
}