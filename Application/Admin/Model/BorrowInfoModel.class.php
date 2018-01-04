<?php
namespace Admin\Model;
use Think\Model;
class BorrowInfoModel extends Model{
    protected $_scope=array(
        //借阅者信息
        "borrow_borrower"=>array(
            "field"=>array(
                "a.borrow_time"=>"borrow_time",
                "b.nick_name"=>"user_name",
                "b.avatar"=>"user_avatar",
                "b.phonenum"=>"user_tel",
            ),
            "table"=>array(
                "t_borrow_info"=>"a",
                "t_user_info"=>"b",
            ),
            "where"=>array(
                "a.book_obj_id"=>"null",
                "a.status"=>0,
                "a.user_id= b.id",
            )
        ),
        //操作者信息
        "borrow_operator"=>array(
            "field"=>array(
                "c.nick_name"=>"oper_name",
                "c.avatar"=>"oper_avatar",
            ),
            "table"=>array(
                "t_borrow_info"=>"a",
                "t_user_info"=>"c",
            ),
            "where"=>array(
                "a.book_obj_id"=>"null",
                "a.status"=>0,
                "a.oper_id= c.id"
            )
        )
    );
}