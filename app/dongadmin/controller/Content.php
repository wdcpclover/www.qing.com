<?php
namespace app\dongadmin\controller;
use think\facade\Db;
use app\model\Categorys;


class Content extends  Base
{

    
    //文章列表
    public function index()
    {
      $cate_id=input('cate_id');
      $searchkey=input('searchkey');

      
     $where=[];//筛选条件数组
     if(input('cate_id')){
        $where[] = [
                     ['a.cate_id', '=', $cate_id],
                 ];
     }

     if(input('searchkey')){
        $where[] = [
                     ['title', 'like', '%'.$searchkey.'%'],
                 ];
     }
     $archivesData=Db::name('archives')->alias('a')->
            field('a.id,a.title,a.listorder,b.cate_name,a.time')->
            join('category b','a.cate_id=b.id')->
            where($where)->
            order('a.listorder asc')->//小到大
            order('a.id DESC')->//大-》小
            paginate([
              'list_rows'=> 10,//每页数量
              'query' => request()->param(),
              ]);
  

     //paginate(3,false,['query'=>request()->param()]);
    $category_model = new Categorys();
    $categoryData=$category_model->getTree();
    


      return view('',[
         'archivesData'=>$archivesData,
         'cate_id'=>$cate_id,
         'categoryData'=>$categoryData,//所有列表栏目
         'searchkey'=>$searchkey,
          'left_menu'=>1
        ]);

    }

    /*添加文章*/
    public function add(){
       $cate_id=input('cate_id');
       
       //如果有数据post过来
       if(request()->isPost()){

         $data = input('post.');
         $data['time']=time();
         unset($data['file']);

         
            $id=Db::name('archives')->insertGetId($data);

            if($id){
				return alert('添加数据成功！','index?&cate_id='.$cate_id,6,3);
			 }else{
				return alert('操作失败！','index',5,3);
			 }
       }

       $archivesData=Array(
            'id' =>'0',
            'thumb' =>''

        );

        $category_model = new Categorys();
        $categoryData=$category_model->getTree();


       return view('',[
         'archivesData'=>$archivesData,
         'categoryData'=>$categoryData,
         'cate_id'=>$cate_id,
         'left_menu'=>1
        ]);
    }


    /*修改文章*/
    public function edit()
    {
        $id=input('id');
        
        //获取数据进行填充，一维数组
        $archivesData=Db::name('archives')->find($id);

       
        //获取栏目
        $category_model = new Categorys();
        $categoryData=$category_model->getTree();


        if(request()->isPost()){

            $data=input('post.');

            unset($data['file']);
            
            $res=Db::name('archives')->update($data);
            
        
            
            if($res !== false){
                return alert('修改数据成功！','index?&cate_id='.$archivesData['cate_id'],6,3);
            }else{
                return alert('操作失败！','index',5,3);
            }
            return;
        }


        return view('',[
            'categoryData'=>$categoryData,
            'cate_id' =>$archivesData['cate_id'],//当前文章所属栏目id，方便判断选中
            'archivesData'=>$archivesData,
            'left_menu'=>1,
            ]);
    }


    
    //文章删除操作，删除文章的同时删除文章缩略图
    public function delect(){
      $id=input('id');//助手函数

      $archives=Db::name('archives')->field('thumb')->find($id);
      if($archives['thumb']){
        delFile($archives['thumb']);//删除缩略图文件
      }

      $res=Db::name('archives')->delete($id);
      if($res){
            return alert('删除成功!','index',6,3);
         }else{
            return alert('操作失败！','index',5,3);
         } 
    }


  
    
}
