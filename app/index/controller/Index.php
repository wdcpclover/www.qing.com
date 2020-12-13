<?php
namespace app\index\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;

class Index extends Base
{
    public function index()
    {
        $adData=Db::name('ad')->where('type_id',1)->select();
        $archivesData=Db::name('archives')->where('cate_id',2)->limit(12)->order('listorder asc')->order('id desc')->select();
        return view('',[
            'adData'=>$adData,
            'menu_id'=>0,
            'archivesData'=>$archivesData
        ]);
    }

    public function company(){
        $id=1;
        $categoryData=Db::name('category')->find($id);
        return view('',[
            'categoryData'=>$categoryData,
            'menu_id'=>1
        ]);
    }
    
    public function product(){
        $id=2;
        $categoryData=Db::name('category')->find($id);
        $archivesData=Db::name('archives')->field('id,thumb,title')->where('cate_id',$id)->paginate(12);
        return view('',[
            'categoryData'=>$categoryData,
            'menu_id'=>$id,
            'archivesData'=>$archivesData
        ]);
    }


    public function list(){
        $id=input('id');

       $categoryData=Db::name('category')->find($id);
       $archivesData=Db::name('archives')->field('id,time,title')->where('cate_id',$id)->paginate(20);
       //print_r($archivesData);
        return view('',[
            'categoryData'=>$categoryData,
            'archivesData'=>$archivesData,
            'menu_id'=>$id
        ]);
    }

    public function contact(){
        $id=7;
        $categoryData=Db::name('category')->find($id);
        return view('',[
            'categoryData'=>$categoryData,
            'menu_id'=>7
        ]);
    }

    public function article(){
        $id=input('id');

       $archivesData=Db::name('archives')->find($id);
       $categoryData=Db::name('category')->find($archivesData['cate_id']);

        return view('',[
            'categoryData'=>$categoryData,
            'archivesData'=>$archivesData,
            'menu_id'=>$id
        ]);
    }

  
}
