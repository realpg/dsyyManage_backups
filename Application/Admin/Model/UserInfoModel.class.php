<?php
namespace Admin\Model;
use Think\Model;
class UserInfoModel extends Model{
    protected $_scope=array(
        //检测用户是否存在
        "check_name"=>array(
            "field"=>array("id"),
            "where"=>array("id"=>"null"),
        ),
        //检测密码
        "check_password"=>array(
            "field"=>array("id","nick_name","avatar"),
            "where"=>array(
                "id"=>"null",
                "passwd"=>"null"
                ),
        ),
    );
}