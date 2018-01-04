$(function(){
	$('#entry').click(function(){
		if($('#user_id').val()==''){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('请输入管理员账号');
		}
        else if($('#user_password').val()==''){
			$('.mask,.dialog').show();
			$('.dialog .dialog-bd p').html('请输入管理员密码');
		}
        else if($('#code').val()==''){
            $('.mask,.dialog').show();
            $('.dialog .dialog-bd p').html('请输入验证码');
        }
        else{
			$('.mask,.dialog').hide();
			var user_id=$('#user_id').val();
			var user_password=$('#user_password').val();
            var code=$('#code').val();
			$.post("../dsyyManage/Admin/Login/login",{"user_id":user_id,"user_password":user_password,"code":code},function(data){
				if(data.code=="1000")
					{
						location.href="../dsyyManage/Admin/Index/index";
					}
				else
					{
						$('.mask,.dialog').show();
						$('.dialog .dialog-bd p').html(data.message);
					}
                $("#verify").attr("src","/dsyyManage/Admin/Verify/verify");
			},"json")
		}
	});
});