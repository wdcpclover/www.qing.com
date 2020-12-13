<?php
namespace app\dongadmin\controller;
use think\facade\View;

class Index extends  Base
{
    
    public function index()
    {

        return view('',[
            'left_menu'=>1,
           ]);
           
    }

    /*退出*/
    public function logout() {

      // 清除session
      session(null);

      // 跳出
      return redirect('/');

  }

   
}