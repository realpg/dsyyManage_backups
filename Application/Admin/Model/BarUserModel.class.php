<?php
namespace Admin\Model;
use Think\Model;
class BarUserModel extends Model{
    protected $_scope=array(
        //检测用户是否存在
        "check_user"=>array(
            "field"=>array("id"),
            "where"=>array("user_id"=>"null"),
        ),
    );
}