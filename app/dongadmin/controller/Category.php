<?php  

namespace app\dongadmin\controller;
use think\facade\Db;
use app\model\Categorys;

class Category extends  Base

{


	public function index(){

		//商品分类列表的方法
        $category_model = new Categorys();
        $categoryTree =$category_model->getTree();

        return view('',[
            'categoryTree'=>$categoryTree,
            'left_menu'=>1,
        ]);


	}



    //分类添加

	public function add(){
        $category_model = new Categorys();
        $categoryTree =$category_model->getTree();


        //处理添加操作
        if(request()->isPost()) {

            //接收post
            $data = input('post.');
            unset($data['file']);


            // 把数据插入数据表中

            $res = $category_model->add($data);

            if($res) {

                return alert('操作成功！','index',6,3);

            }else {

                return alert('操作失败！','index',5,3);

            }

        }



        return view('', [

            'categoryTree'=>$categoryTree,
            'left_menu'=>1

        ]);


	}




    //编辑页面
    public function edit($id=0) {


        if(intval($id) < 1) {

            $this->error('参数不合法');

        }

        $categoryData =Db::name('category')->find($id);//一维数组


        $category_model = new Categorys();
        $categoryTree =$category_model->getTree();




        if(request()->isPost()) {

            $data=input('post.');
            unset($data['file']);

            $res =Db::name('category')->update($data);
            if($res){
				return alert('操作成功！','index',6,3);
			 }else{
				return alert('操作失败或者没有内容更新！','index',5,3);
			 }
        }
        

        return view('', [

            'categoryTree'=> $categoryTree,

            'categoryData' => $categoryData,
            'left_menu'=>1

        ]);


    }





    /*删除*/

    public function del($id=''){

        //如果分类id是空，则跳转到列表页面
        if ($id=='') {

            $this->redirect('category/index');

        }



        //根据id找到当前的记录
        $category = Db::name('Category')->find($id);


        //先找出所有分类，再根据id循环

        $categorys = Db::name('Category')->field('id,parent_id')->select();
        $category_model = new Categorys();

        $cateStr = $category_model->getChildrenIdStr($categorys,$id);
    
        $cate_list=explode(',',$cateStr);
        foreach ($cate_list as $k => $v) {

            Db::name('category')->where('id',$v)->delete();

        }

        Db::name('category')->where('id',$id)->delete();
        
        return redirect('index');



    }




}



?>