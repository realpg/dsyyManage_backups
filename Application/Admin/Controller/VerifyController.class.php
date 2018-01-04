<?php
namespace Admin\Controller;
use Think\Controller;
class VerifyController extends Controller{
    public function verify()
    {
        $Verify = new \Think\Verify();
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->fontSize = 100;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
}