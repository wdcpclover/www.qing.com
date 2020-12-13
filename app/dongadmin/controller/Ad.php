<?php

namespace app\dongadmin\controller;

use think\facade\Db;

use think\facade\Request;


class Ad extends  Base

{


	public function index() {

		// 获取推荐位类别
		$typeData=Db::name('adtype')->order('id desc')->select();

		// 获取广告列表
		$type_id=input('get.type_id');
		$where=[];
        if($type_id){
            $where[] = [
                ['a.type_id','=',$type_id]
            ];
        }
        $adData = Db::name('ad')->alias('a')->field('a.*,b.name')->join('adtype b','a.type_id=b.id')->where($where)->paginate([
			'list_rows'=> 10,//每页数量
			'query' => request()->param(),
			]);
		

		return view('', [

			'typeData' => $typeData,
			'adData' => $adData,
			'type_id'=>$type_id,
			'left_menu'=>2

		]);

	}



    public function add() {

		if(request()->isPost()) {

			// 入库的逻辑

			$data = input('post.');
			unset($data['file']);

			$res = Db::name('Ad')->insert($data);

			if($res){
				return alert('操作成功！','index',6,3);
			 }else{
				return alert('操作失败！','index',5,3);
			 }

		}else {

			// 获取推荐位类别

		    $types =Db::name('adtype')->select();

			return view('', [

				'types' => $types,
				'left_menu'=>2

			]);

		}

	}



	//编辑广告位

	public function edit(){

		$id=Request::instance()->param('id');

		$adData=Db::name('Ad')->find($id);


		// 获取推荐位类别

		$types =Db::name('Adtype')->select();



		if(request()->isPost()){

			$data=input('post.');
			unset($data['file']);
            $res=Db::name('Ad')->update($data);

			if($res){
				return alert('操作成功！','index',6,3);
			 }else{
				return alert('操作失败！','index',5,3);
			 }

		}

		return view('', [

				'types' => $types,
				'adData'=>$adData,
				'left_menu'=>2
			]);

	}



	//广告分组列表
	public function adtype(){
		$adTypeData=Db::name('adtype')->order('id desc')->select();
		return view('', [
				'adTypeData' => $adTypeData,
				'left_menu'=>2
			]);
	}


	//添加广告分组
	public function ad_type_add(){
		if(request()->isPost()) {
			$data = input('post.');
			$res=Db::name('adtype')->insert($data);
			if($res){
				return alert('操作成功！','adtype',6,3);
			 }else{
				return alert('操作失败！','adtype',5,3);
			 }
		}

		return view('',[
			'left_menu'=>2
		]);

	}


	//修改广告分组
	public function ad_type_edit(){
		if(request()->isPost()) {
			$data = input('post.');
			$res=Db::name('adtype')->update($data);
			if($res){
				return alert('操作成功！','adtype',6,3);
			 }else{
				return alert('操作失败！','adtype',5,3);
			 }
		}
		$id=request()->param('id');
		$adTypeData=Db::name('adtype')->find($id);
		return view('', [
				'adTypeData' => $adTypeData,
				'left_menu'=>2
			]);

	}

	public function ad_type_del(){
		$id=request()->param('id');
		$res=Db::name('adtype')->delete($id);
		if($res){
			return alert('操作成功！','adtype',6,3);
		 }else{
			return alert('操作失败！','adtype',5,3);
		 }
	}

	public function del(){
		$id=Request::instance()->param('id');
		$adData=Db::name('ad')->where('id',$id)->find();
		if($adData['thumb']){
			delFile($adData['thumb']);//删除文件
		}
		
		$res=Db::name('ad')->where('id',$id)->delete();
		if($res){
			return alert('操作成功！','index',6,3);
		 }else{
			return alert('操作失败！','index',5,3);
		 }
	}
	





}

