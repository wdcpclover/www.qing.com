<?php

namespace app\dongadmin\controller;
use think\facade\Db;

class Login extends  Base

{
    public function initialize() {
        
        //已登录直接跳转
        $account = session('adminAccount');
        if($account && $account['id']) {
            return $this->redirect(url('index/index'));

        }
    }

    //后台登录的逻辑
	public function index()

    {
      
        if(request()->isPost()) {

            $data = input('post.');
            


            // 通过用户名 获取 用户相关信息

            $adminData = Db::name('admin')->where('username',$data['username'])->find();//一维数组


            if(!$adminData || $adminData['status'] !=1 ) {

                return alert('用户不存在，或者此用户未被审核通过','index',5,3);

            }



            if(!captcha_check($data['verifycode'])) {
                // 校验失败
                return alert('验证码不正确','index',5,3);
            }


            if($adminData['password'] !=$this->password_salt($data['password'])) {
                return alert('密码不正确','index',5,3);
            }

            session('adminAccount', $adminData);
            
            return alert('登录成功！','index/index',6,3);


        }else {

            
            return view();

        }

    }

    

}