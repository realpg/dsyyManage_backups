function edit_password(user_id)
{
    var user_password=$("#user_password").val();
    var user_new_password=$("#user_new_password").val();
    var user_confirm_password=$("#user_confirm_password").val();
    var code=$("#code").val();
    if(user_password=="")
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('请输入原密码');
    }
    else if(user_new_password=="")
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('请输入新密码');
    }
    else if(user_confirm_password=="")
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('请输入确认密码');
    }
    else if(user_new_password!=user_confirm_password)
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('两次输入的密码不一致');
    }
    else if(code=="")
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('请输入验证码');
    }
    else
    {
        $.post("/dsyyManage/Admin/User/editDo",{"user_password":user_password,"user_new_password":user_new_password,"code":code},function(data){
            if(data.code=="1000")
            {
                $('.mask,.dialog').show();
                $('.dialog .dialog-bd p').html('修改成功，请重新登录');
                $('.dialog .dialog-ft').html("<a href='/dsyyManage/Admin'><button class='btn btn-info JyesBtn'>确认</button></a>")
            }
            else
            {
                $('.mask,.dialog').show();
                $('.dialog .dialog-bd p').html(data.message);
            }
            $("#verify").attr("src","/dsyyManage/Admin/Verify/verify");
        },"json")
    }
}
function All(e, itemName)
{
    var aa = document.getElementsByName(itemName);
    for (var i=0; i<aa.length; i++)
        aa[i].checked = e.checked; //得到那个总控的复选框的选中状态
}
function Item(e, allName)
{
    var all = document.getElementsByName(allName)[0];
    if(!e.checked) {
        all.checked = false;
    }else{
        var aa = document.getElementsByName(e.name);
        for (var i=0; i<aa.length; i++)
            if(!aa[i].checked) return;
        all.checked = true;
    }
}
function batch_delete()
{
    var obj = document.getElementsByName("mm[]");
    var selected="";
    for(var i=0; i<obj.length; i++)
    {
        if(obj[i].checked)
            selected += obj[i].value+','; //如果选中，将value添加到变量selected中
    }
    if(selected=="")
    {
        $('.mask,.dialog').show();
        $('.dialog .dialog-bd p').html('请选择要删除的信息');
    }
    else
    {
        $("#batchdelete").submit();
    }
}

function delayed_dialog(data,time)
{
    var text="";
    var link="";
    if(data==0)
    {
        text="操作失败";
        link="";
    }
    else
    {
        text="操作成功"
        link=data;
    }
    $('.automatic').html(text+"&nbsp;&nbsp;"+time);
    $('dialog').show().delay(3000).hide(0);
    function jump(count) {
        window.setTimeout(function(){
            count--;
            if(count > 0) {
                $('.automatic').html(text+"&nbsp;&nbsp;"+count);
                jump(count);
            } else {
                location.href=link;
            }
        }, 1000);
    }
    jump(time);
}
