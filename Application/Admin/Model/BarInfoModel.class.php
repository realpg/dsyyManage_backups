<?php
namespace Admin\Model;
use Think\Model;
class BarInfoModel extends Model{
    protected $_scope=array(
        //基本信息
        "base"=>array(
            "field"=>array("id","name"),
            "where"=>array("id"=>"null"),
        ),
        //根据用户输出书吧
        "bar_manage"=>array(
            "field"=>array("a.id","a.name"),
            "table"=>array(
                "t_bar_info"=>"a",
                "t_bar_user"=>"b"
            ),
            "where"=>array(
                "b.user_id"=>"null",
                "a.id=b.bar_id",
            ),
        ),
    );
}