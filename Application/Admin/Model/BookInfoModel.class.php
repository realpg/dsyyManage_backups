<?php
namespace Admin\Model;
use Think\Model;
class BookInfoModel extends Model{
    protected $_scope=array(
        //基本信息
        "base"=>array(
            "field"=>array("id","images_medium","title","origin_title","author","type","binding","publisher","pubdate","summary"),
            "where"=>array("id"=>"null"),
        ),
    );
}