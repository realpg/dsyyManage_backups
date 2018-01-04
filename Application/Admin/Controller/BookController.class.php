<?php
namespace Admin\Controller;
use Think\Controller;
class BookController extends CommonController{
    public function index()
    {
        $BookObj=D("BookObj");
        $p=I("p",1);  //当前页数
        $title=I("searchname","");  //搜索关键字
        $type=I("type","");  //按分类查询
        $bar_id=I("bar_id");
        //获取书吧信息
        $BarInfo=D("BarInfo");
        $parameter_barinfo_base=array(
            "where"=>array("id"=>$bar_id)
        );
        $bar_row=$BarInfo->scope("base",$parameter_barinfo_base)->find();
        //对图书进行分组分页查询（同种书归为一类）
        $parameter_bookobj_books=array(
            "where"=>array(
                "owner_id"=>$bar_id,
                "b.title"=>array("like","%".$title."%"),
                "a.type"=>array("like","%".$type."%"),
                "a.book_id= b.id"
            )
        );
        $book_rows=$BookObj->scope("book_lists",$parameter_bookobj_books)->limit(($p-1)*CUSTOM_PAGING,CUSTOM_PAGING)->select();
        $book_count=count($BookObj->scope("count_total_page",$parameter_bookobj_books)->select());
        $book_page = new \Think\Page($book_count,CUSTOM_PAGING);// 实例化分页类
        $book_show = $book_page->show();// 分页显示输出
        //图书分类
        $parameter_bookobj_style=array(
            "field"=>array("type"),
            "where"=>array("owner_id"=>$bar_id)
        );
        $type_rows=$BookObj->scope("book_type",$parameter_bookobj_style)->select();

        $this->assign('bar_row',$bar_row);// 赋值数据
        $this->assign('book_count',$book_count);// 赋值数据
        $this->assign('book_rows',$book_rows);// 赋值数据集
        $this->assign('page_show',$book_show);// 赋值分页输出
        $this->assign('type_rows',$type_rows);// 输出分类
        $this->assign('title',$title==""?"搜索书名...":$title);// 当前搜索
        $this->assign('type',$type);// 当前
        $this->display(); // 输出模板
    }
    public function browse()
    {
        $BookInfo=D("BookInfo");
        $BookObj=D("BookObj");
        $BorrowInfo=D("BorrowInfo");
        $bar_id=I("bar_id");
        $book_id=I("book_id");
        $book_code=I("searchname","");  //搜索图书编号
        //查询图书基本信息
        $parameter_bookinfo_base=array(
            "where"=>array("id"=>$book_id)
        );
        $book_row=$BookInfo->scope("base",$parameter_bookinfo_base)->find();
        //查询书吧里所有这本书
        $parameter_bookobj_barbooks=array(
            "where"=>array(
                "owner_id"=>$bar_id,
                "book_id"=>$book_id,
                "book_code"=>array("like","%".$book_code."%"),
            )
        );
        $book_lists=$BookObj->scope("bar_book_lists",$parameter_bookobj_barbooks)->select();
        //计算一共多少本书
        $parameter_bookobj_total_book=array(
            "where"=>array(
                "owner_id"=>$bar_id,
                "book_id"=>$book_id,
            )
        );
        $book_count_total=$BookObj->scope("count_total_book",$parameter_bookobj_total_book)->count();  //计算一共有多少本这本书
        //计算这本书在借阅状态中的数目
        $parameter_bookobj_end_book=array(
            "where"=>array(
                "owner_id"=>$bar_id,
                "book_id"=>$book_id,
                "status"=>1,
            )
        );
        $book_count_end=$BookObj->scope("count_status_book",$parameter_bookobj_end_book)->count();  //计算这本书在借阅状态中的数目
//        借阅信息
        foreach($book_lists as $k=>$book_list)
        {
            if($book_list["status"]==1)
            {
                $book_obj_id=$book_list["id"];
                $parameter_borrowinfo_borrower=array(
                    "where"=>array(
                        "a.book_obj_id"=>$book_obj_id,
                        "a.status"=>0,
                        "a.user_id= b.id",
                    )
                );
                $borrow_borrower_row=$BorrowInfo->scope("borrow_borrower",$parameter_borrowinfo_borrower)->find();
                $book_lists[$k]["borrow_time"]=$borrow_borrower_row["borrow_time"];
                $book_lists[$k]["user_name"]=$borrow_borrower_row["user_name"];
                $book_lists[$k]["user_avatar"]=$borrow_borrower_row["user_avatar"];
                $book_lists[$k]["user_tel"]=$borrow_borrower_row["user_tel"];
                $parameter_borrowinfo_operator=array(
                    "where"=>array(
                        "a.book_obj_id"=>$book_obj_id,
                        "a.status"=>0,
                        "a.oper_id= c.id"
                    )
                );
                $borrow_operator_row=$BorrowInfo->scope("borrow_operator",$parameter_borrowinfo_operator)->find();
                $book_lists[$k]["oper_name"]=$borrow_operator_row["oper_name"];
                $book_lists[$k]["oper_avatar"]=$borrow_operator_row["oper_avatar"];
            }
        }

        $this->assign("bar_id",$bar_id);  //书吧id
        $this->assign("book_id",$book_id);  //书吧id
        $this->assign("book_row",$book_row);  //图书基本信息
        $this->assign("book_lists",$book_lists);  //书吧里所有这本书
        $this->assign("book_count_total",$book_count_total);  //一共有多少本这本书
        $this->assign("book_count_end",$book_count_end);  //本书在借阅状态中的数目
        $this->display(); // 输出模板
    }
}