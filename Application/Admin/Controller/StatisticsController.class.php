<?php
namespace Admin\Controller;
use Think\Controller;
class StatisticsController extends CommonController{
    public function index()
    {
        $BarInfo=D("BarInfo");
        $BookObj=D("BookObj");
        $bar_id=I("bar_id");
        //获取书吧信息
        $parameter_barinfo_base=array(
            "where"=>array("id"=>$bar_id)
        );
        $bar_row=$BarInfo->scope("base",$parameter_barinfo_base)->find();
        //获取书吧总图书量
        $parameter_bookobj_total=array(
            "where"=>array("owner_id"=>$bar_id)
        );
        $book_count_total=$BookObj->scope("count_total",$parameter_bookobj_total)->count();
        //在借图书总量
        $parameter_bookobj_lend=array(
            "where"=>array(
                "owner_id"=>$bar_id,
                "status"=>1
            )
        );
        $book_count_lend=$BookObj->scope("count_status",$parameter_bookobj_lend)->count();

        $this->assign('bar_row',$bar_row);// 赋值数据
        $this->assign("book_count_total",$book_count_total);  //书吧总图书量
        $this->assign("book_count_lend",$book_count_lend);  //在借图书总量
        $this->display();
    }
    //本月数据统计
    public function data()
    {
        $BookObj=D("BookObj");
        $book_db=M("Book_obj");
        $bar_id=I("bar_id");
        //获取当前月份
        $thismonth=date('m月');
        //获取本月起始时间和结束时间
       	$beginThismonth=date("Y-m-d H:s:i",mktime(0,0,0,date('m'),1,date('Y')));  //月初
        $endThismonth=date("Y-m-d H:s:i",time());  //当前时间
        $end=$this->diffBetweenTwoDays($beginThismonth,$endThismonth);  //计算输出几天的数据
        //X轴（时间）
        $times=array();
        for($i=0;$i<$end;$i++)
        {
            $times[$i]=$i+1;
        }
        $book_count_total_time_rows=array();
        $book_count_lend_time_rows=array();
        foreach($times as $k=>$time)
        {
            $endThistime=date("Y-m-d",mktime(0,0,0,date('m'),2,date('Y'))+86400*$k);
            //当前日期的图书总数
            $parameter_bookobj_total_time=array(
                "where"=>array(
                    "owner_id"=>$bar_id,
                    "create_time"=>array("ELT",$endThistime)
                )
            );
            $book_count_total_time_rows[$k]=$BookObj->scope("count_total_time",$parameter_bookobj_total_time)->count();
            //当前日期累计借出的图书总数
            $parameter_bookobj_lend_time=array(
                "where"=>array(
                    "a.owner_id"=>$bar_id,
                    "b.borrow_time"=>array("between" ,array($beginThismonth,$endThistime)),
                    "a.id=b.book_obj_id"
                )
            );
            $book_count_lend_time_rows[$k]=$BookObj->scope("count_lend_time",$parameter_bookobj_lend_time)->count();
            //当前日期累计归还的图书总数
            $parameter_bookobj_return_time=array(
                "where"=>array(
                    "a.owner_id"=>$bar_id,
                    "b.return_time"=>array("between" ,array($beginThismonth,$endThistime)),
                    "a.id=b.book_obj_id"
                )
            );
            $book_count_return_time_rows[$k]=$BookObj->scope("count_return_time",$parameter_bookobj_return_time)->count();
        }

        $data["thismonth"]=$thismonth; //当前月份
        $data["data_total"]=$book_count_total_time_rows;  //图书总数集合
        $data["data_lend"]=$book_count_lend_time_rows;  //借出图书数目集合
        $data["data_return"]=$book_count_return_time_rows;  //归还图书数目集合
        $max_data=array_search(max($book_count_total_time_rows),$book_count_total_time_rows);  //图书总数集合最大值的位置
        $data["data_max"]=$book_count_total_time_rows[$max_data]%10==0?$book_count_total_time_rows[$max_data]+20:$book_count_total_time_rows[$max_data]-$book_count_total_time_rows[$max_data]%10+20; //y轴最大值
        $data["time"]=$times;
        return $this->ajaxReturn($data);
    }
    //按图书类型统计
    public function type()
    {
        $BookObj=D("BookObj");
        $bar_id=I("bar_id");
        //获取图书类型
        $parameter_bookobj_style=array(
            "field"=>array("type"),
            "where"=>array("owner_id"=>$bar_id)
        );
        $type_rows=$BookObj->scope("book_type",$parameter_bookobj_style)->select();
        $type=array();
        $book_count_total_style_rows=array();
        $book_count_lend_style_rows=array();
        foreach($type_rows as $k=>$type_row)
        {
            $type[$k]=$type_row["type"];
            //当前类型的图书总数
            $parameter_bookobj_total_type=array(
                "where"=>array(
                    "owner_id"=>$bar_id,
                    "type"=>$type_row["type"]
                )
            );
            $book_count_total_style_rows[$k]=$BookObj->scope("count_total_type",$parameter_bookobj_total_type)->count();
            //当前类型的借出的图书总数
            $parameter_bookobj_lend_type=array(
                "where"=>array(
                    "owner_id"=>$bar_id,
                    "type"=>$type_row["type"],
                    "status"=>1
                )
            );
            $book_count_lend_style_rows[$k]=$BookObj->scope("count_status_type",$parameter_bookobj_lend_type)->count();
        }

        $data["data_total"]=$book_count_total_style_rows;  //图书总数集合
        $data["data_lend"]=$book_count_lend_style_rows;  //借出图书数目集合
        $max_data=array_search(max($book_count_total_style_rows),$book_count_total_style_rows);  //图书总数集合最大值的位置
        $data["data_max"]=$book_count_total_style_rows[$max_data]%10==0?$book_count_total_style_rows[$max_data]+10:$book_count_total_style_rows[$max_data]-$book_count_total_style_rows[$max_data]%10+10; //y轴最大值
        $data["type"]=$type;
        return $this->ajaxReturn($data);
    }
    public function parameter()
    {
        $bar_id=I("bar_id");
        return $this->ajaxReturn($bar_id);
    }
}