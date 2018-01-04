<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController{
    public function edit()
    {
        $this->display();
    }
    public function editDo()
    {
        $UserInfo=D("UserInfo");
        $user_password=I("user_password");
        $user_new_password=I("user_new_password");
        $code=I("code");
        if($this->check_verify($code))
        {
            $user=$_SESSION["user"];
            $user_id=$user["id"];
            $user_password=$this->encryption($user_password); //对密码进行加密
            //检测密码
            $parameter_userinfo_checkpassword=array(
                "where"=>array(
                    "id"=>$user_id,
                    "passwd"=>$user_password
                )
            );
            $row=$UserInfo->scope("check_password",$parameter_userinfo_checkpassword)->find();
            $data=array();
            if($row)
            {
                $user_new_password=$this->encryption($user_new_password); //对密码进行加密
                //修改密码
                $parameter_userinfo_savepassword=array(
                        "id"=>$user_id,
                        "passwd"=>$user_new_password
                    );
                $rows=$UserInfo->save($parameter_userinfo_savepassword);
                if($rows)
                {
                    $data["result"]=true;
                    $data["code"]="1000";
                    $data["message"]="修改密码成功";
                }
                else
                {
                    $data["result"]=false;
                    $data["code"]="9999";
                    $data["message"]="修改密码失败";
                }
            }
            else
            {
                $data["result"]=false;
                $data["code"]="1004";
                $data["message"]="修改密码失败，原密码不正确";
            }
        }
        else
        {
            $data["result"]=false;
            $data["code"]="7777";
            $data["message"]="验证码错误";
        }
        return $this->ajaxReturn($data);
    }
}