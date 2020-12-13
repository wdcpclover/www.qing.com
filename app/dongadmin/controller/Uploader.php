<?php

namespace app\dongadmin\controller;

use app\BaseController;
use think\facade\Db;
use think\File;

use think\facade\Config;
use think\facade\Env;
use think\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


class Uploader extends BaseController
{
    /**
     * 图片上传，ajax返回
     * @author  王晴儿
     */
    public function upload(){
        $this->local_upload();//本地存储图片
    }
    public function local_upload(){
        $file = request()->file('file');
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'pic', $file);
        if($savename){
            $return['path'] =DIRECTORY_SEPARATOR.'public' . DIRECTORY_SEPARATOR . 'upload'.DIRECTORY_SEPARATOR.$savename;
        }else{
            // 上传失败获取错误信息
            $return['error']   = 1;
            $return['success'] = 0;
            $return['message'] = '上传出错'.$file->getError();
        }
        exit(json_encode($return));
    }




    //删除图片
    public function removefile(){
        $path = input("request.cover",0);
        Db::name('img')->where('path',$path)->delete();
        return ['status' => 1, 'info' => '删除成功'];
    }



    //删除分类缩略图
    public function remove_categoryimg(){
        $cateimg = input("request.cover",0);
        $cate_id = input("request.cate_id",0);
        Db::name('category')->where('id',$cate_id)->update(array('cate_img'=>''));
        return ['status' => 1, 'info' => '删除成功'];
    }

    //删除公共
    public function remove_img(){
        $cateimg = input("request.cover",0);
        $id = input("request.id",0);
        $model = input("request.model",0);
        Db::name($model)->where('id',$id)->update(array('thumb'=>''));
        return ['status' => 1, 'info' => '删除成功'];
    }

    //删除广告图片
    public function remove_adimg(){
        $cateimg = input("request.cover",0);
        $id = input("request.id",0);
        Db::name('ad')->where('id',$id)->update(array('image'=>''));
        return ['status' => 1, 'info' => '删除成功'];
    }

    //删除图片公共
    public function delete_img(){
        $path = input("request.path",0);
        $model = input("request.model",0);
        $id = input("request.id",0);
        $res=Db::name($model)->find($id);
        if($res){
            Db::name($model)->where('id',$id)->update(['thumb'=>'']);
        }
        
        delFile($path);
        
        return json_encode(['status' => 1, 'info' => '删除成功']);
    }

}