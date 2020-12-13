<?php

namespace app\dongadmin\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use think\exception\HttpResponseException;


class Base extends  BaseController {
      
  
  public function initialize() {
    //  // 判定用户是否登录
    $loginAdmin =session('adminAccount');     
    if(!$loginAdmin) {
       return $this->redirect('/dongadmin/login/index');
    }
    
   View::assign('username', $loginAdmin['username']);


  }

  public function redirect(...$args){
    throw new HttpResponseException(redirect(...$args));
  }
    


    //通用后台密码加盐
    public function password_salt($str){
      $salt='ddsEf6KJ';
      return md5($str.$salt);
    }



    //更改状态
    public function status() {

        $id = Request::instance()->param('id','intval');
        $status = Request::instance()->param('status','intval');
        $model = Request::instance()->param('model');

        if(empty($id)) {

            $this->error('id不合法');

        }

        if(!is_numeric($status)) {

            $this->error('status不合法');

        }


        $res = Db::name($model)->where('id',$id)->update(['status'=>$status]);

        if($res) {

            $this->success('更新成功');

        }else {

            $this->error('更新失败');

        }

    }



    /*通用删除逻辑*/

    public function del(){

      $id=Request::instance()->param('id');
      $model=Request::instance()->param('m');
      $status=Request::instance()->param('status');

      if(!intval($id)){

        return $this->error('ID不合法');

      }


      //如果状态为空则是直接删除，不为空则更新状态

      if(empty($status)){

        $res=Db::name($model)->where('id',$id)->delete();

      }else{

        $res=Db::name($model)->save(['status'=>$status],['id'=>$id]);

      }


      if($res){
        return alert('操作成功！','index',6,3);
     }else{
        return alert('操作失败！','index',5,3);
     }

    }





      /*

      **公共排序方法

      **需要在排序html中传入下面三个参数

      */

      public function listorder($id,$model,$listorder) {

        $data=input('post.');

        $res = Db::name($model)->where('id',$id)->update(['listorder'=>$listorder]);

        if($res) {

           return ['data'=>$_SERVER['HTTP_REFERER'],'code'=>1];

        }else {

          return['data'=>$_SERVER['HTTP_REFERER'],'code'=>0,'msg'=>'失败！'];

        }



    }

   



    
    



}