<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends CommonController{
    public function index(){
        $this->display();
    }
    public function login(){
        $UserInfo=D("UserInfo");
        $BarUser=D("BarUser");
        $data=array();
        $user_id=I("user_id");
        //判断账号的格式是否正确：账号是否以“dsyy_”开始，并且剩下的字符串为数字
        if(stripos($user_id,"dsyy_")===0&&is_numeric(str_replace('dsyy_','',$user_id)))
        {
            $user_id=str_replace('dsyy_','',$user_id);

            $user_password=I("user_password");
            $code=I("code");
            //检测用户是否存在
            $user_password=$this->encryption($user_password); //对密码进行加密
            $parameter_userinfo_checkname=array(
                "where"=>array("id"=>$user_id)
            );
            $user_row=$UserInfo->scope("check_name",$parameter_userinfo_checkname)->find();
            $parameter_baruser_checkuser=array(
                "where"=>array("user_id"=>$user_id)
            );
            $bar_user_row=$BarUser->scope("check_user",$parameter_baruser_checkuser)->find();
            if($user_row&&$bar_user_row)
            {
                //检测密码
                $parameter_userinfo_checkpassword=array(
                    "where"=>array(
                        "id"=>$user_id,
                        "passwd"=>$user_password
                    )
                );
                $list=$UserInfo->scope("check_password",$parameter_userinfo_checkpassword)->find();
                if($list)
                {
                    if($this->check_verify($code))
                    {
                        $data["result"]=true;
                        $data["code"]="1000";
                        $data["message"]="用户登录成功";
                        $user["id"]=$list["id"];
                        $user["nick_name"]=$list["nick_name"];
                        $user["avatar"]=$list["avatar"];
                        $_SESSION["user"]=$user;
                    }
                    else
                    {
                        $data["result"]=false;
                        $data["code"]="7777";
                        $data["message"]="验证码错误";
                    }
                }
                else
                {
                    $data["result"]=false;
                    $data["code"]="1001";
                    $data["message"]="密码错误";
                }
            }
            else
            {
                $data["result"]=false;
                $data["code"]="1002";
                $data["message"]="用户不存在";
            }
        }
        else
        {
            $data["result"]=false;
            $data["code"]="1003";
            $data["message"]="账号格式不正确";
        }
        return $this->ajaxReturn($data);
    }
}