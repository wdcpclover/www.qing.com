<?php
namespace app\dongadmin\controller;
use think\facade\Db;
use think\facade\Request;

class Flink extends  Base
{

    //友情链接列表
    public function index()
    {
      $flinkData=Db::name('Flink')->paginate(15);
      return view('',[
         'flinkData'=>$flinkData,
         'left_menu'=>3
        ]);

    }

    /*添加*/
    public function add(){
       if(request()->isPost()){
         $data=request()->post();
         $res=Db::name('flink')->insert($data);
         if($res){
            return alert('操作成功！','index',6,3);
         }else{
            return alert('操作失败！','index',5,3);
         }
       }
       return view('',[
         'left_menu'=>3
        ]);
    }

    /*修改*/
    public function edit(){
       //先取出填充的数据
       $id=Request::instance()->param('id');
       $flinkData=Db::name('flink')->find($id);       
       
       return view('',[
         'flinkData'=>$flinkData,
         'left_menu'=>3
        ]);

    }


    public function update(){
      //处理post过来的数据
       if(request()->isPost()){
         $data=request()->post();
         $res=Db::name('flink')->update($data);
         if($res){
            return alert('操作成功！','index',6,3);
         }else{
            return alert('操作失败！','index',5,3);
         }
       }
    }
    
    
}
