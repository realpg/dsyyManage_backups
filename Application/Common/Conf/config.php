<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '101.200.159.147', // 服务器地址
    'DB_NAME'   => 'dsyydb', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'Aa123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 't_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'MODULE_ALLOW_LIST'      =>  array('Admin'),  //设置可访问模块
    'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Login', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => '/Common/error',
);