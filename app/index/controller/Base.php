<?php
namespace app\index\controller;
use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Base extends BaseController
{
    

    public function initialize() {
        
      View::assign('leftProductData', $this->getLeftProduct());//公共产品左侧菜单

        //系统参数
        $configData=Db::name('config')->field('value')->select();
        foreach($configData as $k=>$v){
          if($k==0){
            View::assign('web_title', $v['value']);//首页title
          }
          if($k==2){
            View::assign('web_keywords', $v['value']);//首页关键字
          }
          if($k==12){
            View::assign('web_description', $v['value']);//首页描述
          }
          if($k==3){
            View::assign('copyright', $v['value']);//版权信息
          }
          if($k==4){
            View::assign('beian', $v['value']);//备案号
          }
          if($k==6){
            View::assign('address', $v['value']);//地址
          }
          if($k==10){
            View::assign('email', $v['value']);//邮箱
          }
          if($k==7){
            View::assign('tel1', $v['value']);//电话
          }
          
        }

    }

    public function getLeftProduct(){
      $archivesData=Db::name('archives')->where('cate_id',2)->where('ishot',1)->limit(4)->order('listorder asc')->order('id desc')->select();
      return $archivesData;
    }

    public function getChildCategoryData($id)
    {
       $data=Db::name('category')->where('parent_id',$id)->select();
       return $data;
    }




    /*获取多级导航数据*/
    public function getNavCategory(){
        $categoryData=Db::name('category')->getNavCateData();;
        return $categoryData;
    }



    //获取当前的位置信息
    public function getPositionByCatId($cateId){
        $positionData=array();
          while($cateId){
                $cates=Db::name('category')->field('id,parent_id,cate_name,index_template')->where('id='.$cateId)->find();               
                $positionData[]=array(
                    'id'=>$cates['id'],
                    'cate_name'=>$cates['cate_name'],
                    'parent_id'=>$cates['parent_id'],
                    'index_template'=>$cates['index_template'],
                );
                $cateId=$cates['parent_id'];
           }
          //将取出的当前位置信息数组 倒序
          $positionData=array_reverse($positionData);
          return $positionData;
    }


    public function getcategoryTree(){
        $categoryDataTree=Db::name('category')->where('parent_id',14)->select()->toArray();;
        foreach ($categoryDataTree as $k => $v) {
           $categoryDataTree[$k]['children']=db('category')->field('id,cate_name,parent_id')->where('parent_id',$v['id'])->select();
        }
        return $categoryDataTree;
    }
    
}
