<?php 

namespace app\model;
use think\Model;

/**

 * 分类模型

 * 无限级分类

 */

 class Categorys extends Model

 {


	protected $name = 'category';//数据表


 	/*无限极分类列表 by qing*/
 	public function getTree($data='',$cate_id='')

	{
		if(empty($data)){
			$data = $this->order('listorder')->field('id,parent_id,cate_name,listorder')->select()->toArray();
		}
		return $this->_reSort($data,$cate_id);
	}



	//无限极分类树状结构，修改level值

	private function _reSort($data, $parent_id=0, $cate_level=0, $isClear=TRUE)

	{

		static $ret = array();

		if($isClear)

			$ret = array();

		foreach ($data as $k => $v)

		{

			if($v['parent_id'] == $parent_id)

			{

				$v['cate_level'] = $cate_level;

				$ret[] = $v;
				unset($data[$k]);

				$this->_reSort($data, $v['id'], $cate_level+1, FALSE);

			}

		}

		return $ret;

	}



	/*批量添加分类*/

    public function add($data) {

        $cate_names=explode(",",$data['cate_name']);

        foreach ($cate_names as $k => $v) {

            $data['cate_name']=$v;

            $this->insert($data);

        }

       return 1;

    }



	/*获取某栏目的所有子分类ID，返回多维数组*/

	public function getChildrenId($cate_list,$parent_id=0){

 		//由父类id得到全部子类
 		$arr = array();

 		foreach ($cate_list as $k => $v) {

 			if ($v['parent_id']==$parent_id) {

				 $arr[] = $v;
				 unset($cate_list[$k]);

 				$this->getChildrenId($cate_list,$v['id']);

 			}

 		}

 		return $arr;

 	}


 	/*获取某栏目的顶级父类，返回顶级栏目id*/
	public function getTopCategory($id){

 		$data = db('category')->field('id,parent_id')->find($id);
		if($data['parent_id'] != '0'){
			$this->getTopCategory($data['parent_id']);
		}
		return $data['parent_id'];

 	}

 	//由父类id得到全部子类，返回字符串
 	public function getChildrenIdStr($cate_list,$parent_id=0){

 		static $str='';
 		foreach ($cate_list as $k => $v) {

 			if ($v['parent_id']==$parent_id) {

 				$str =$str.','.$v['id'];

 				$this->getChildrenIdStr($cate_list,$v['id']);

 			}

 		}
 		
 		$str=ltrim($str,',');
 		$str=rtrim($str,',');
 		//print_r($str);
 		return $str;

 	}


 	//得到全部子级，多维数组

 	public function getChildren($cate_list,$parent_id=0){
		
		$arr = array();

		foreach ($cate_list as $key => $value) {

			if ($value['parent_id']==$parent_id) {

				$value['children'] = $this->getChildren($cate_list,$value['id']);

				$arr[] = $value;

			}

		}

		return $arr;

	}


	//获取多级分类数据
	public function getNavCateData(){
		$data=array();
        $allcate=db('category')->order('listorder asc')->select();
        foreach ($allcate as $k => $v) {
        	if($v['parent_id']==0){
        		
                foreach ($allcate as $k1 => $v1) {
                	if($v1['parent_id']==$v['id']){

                        foreach ($allcate as $k2 => $v2) {
                        	if($v2['parent_id']==$v1['id']){
                        		$v1['children'][]=$v2;
                        	}
                        }

                		$v['children'][]=$v1;
                	}
                }

        		$data[]=$v;
        	}
        }
        return $data;
	}



}

?>