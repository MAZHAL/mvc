<?php
namespace Base\Database;

use Base\Factory;
//代理模式
class Proxy
{
    /*
     *
     * 查询一行
     * @$sql string 预处理sql
     * @$params  array()  关联数组，或索引树组，
     * */
    public function getRow($sql,$params=[]){
        if (substr($sql, 0, 6) == 'select')
        {
            return Factory::getDatabase('slave')->getRow($sql,$params);
        }

    }
    /*
     * 查询多行
     * */
    public function getAll($sql,$params=[]){
        return Factory::getDatabase('slave')->getAll($sql,$params);
    }
    /*
     *
     * 删除数据
     * */
    public function delete( $sql,$params=[] ){
        return Factory::getDatabase('master')->delete($sql,$params);
    }
    /*
     *
     * 增加数据
     * */
    public function insert($sql,$params=[]){
        return Factory::getDatabase('master')->insert($sql,$params);
    }
    /*
     *
     * 修改数据
     * */
    public function update($sql,$params=[]){
        return Factory::getDatabase('master')->insert($sql,$params);
    }
}