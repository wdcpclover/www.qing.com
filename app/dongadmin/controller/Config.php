<?php  

namespace app\dongadmin\controller;
use think\facade\Env;
use think\facade\Db;

/**

* 系统基本参数

*/

class Config extends Base

{

	//系统参数列表的方法

	public function index(){


        $confData1=Db::name('Config')->order('id asc')->where('config_type',1)->select();
        $confData2=Db::name('Config')->order('id asc')->where('config_type',2)->select();

        if(request()->isPost()) {
            $data=input('post.');
          
            foreach ($data as $k => $v) {
                Db::name('config')->where('ename',$k)->update(['value'=>$v]);
            }
            return alert('操作成功！','index',6,3);
        }

        return view('',[

            'confData1'=>$confData1,
            'confData2'=>$confData2,
            'left_menu'=>3,

        ]);

	}

    //删除缓存，会删除runtime下面各文件夹的文件
    public function del_cache(){
         $path=root_path().'runtime';
         delFileByDir($path);

        return alert('删除缓存成功！','index',6,3);
    }

    //修改密码
    public function edit_password(){

        $loginAdmin =session('adminAccount');

        if(request()->isPost()) {
            $data=input('post.');
            if(strlen($data['password'])<6){
                return alert('密码长度大于六位数','edit_password',5,3);
            }
            $new_password=$this->password_salt($data['password']);
            
            Db::name('admin')->where('id',$loginAdmin['id'])->update(['password'=>$new_password]);
            return alert('密码重置成功！','index',6,3);
        }
        return view('',[
            'left_menu'=>3
        ]);
        
    }

    


}

?>