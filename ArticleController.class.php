<?php
   /**
	* 自动加载表数据
	* 注释对应字段名
	* 字段对应表单
	* none      : id
	* input     : name,title
	* ueditor   : ueditor
	* img       : icon,img,images
	* number    : number
	* textarea  : 其它
    **/
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ArticleController extends AdminbaseController {
	
	/**
	* 取出表注释
	**/	
	private function Tis($table){
		$Model = new \Think\Model();
		return $Model->query("show full fields from cmf_".$table);
	}
	/**
	*定义一个列表
	*@param name 显示的名称
	*@param type 操作的表名称
	**/
	public function index(){
		$list='[
				{"name":"课程介绍","type":"class"},
				{"name":"学习概况","type":"about"}
				]';
		$this->list=json_decode($list,ture);
		$this->display();
	}
	/**
	* 编辑表数据
	* @param type 操作表名称
	* @param name 操作表名称(中文)
	**/
	public function edit(){
		$type=I("get.type");
		$name=I("get.name");
		if($type){
			if(!IS_POST){
				$count=M($type)->count();
				$page = $this->page($count, 10);
				$list=M($type)->limit($page->firstRow . ',' . $page->listRows)->order("id desc")->select();
				$this->assign("page", $page->show('Admin'));
				$this->tis=$this->Tis($type);
				$this->list=$list;
				$this->name=$name;
				$this->type=$type;
				$this->display();
			}else{
				$data=I("post.");
				foreach($data['id'] as $k => $v){
					$save=array();
					foreach($data as $name => $n){
						$save[$name]=$data[$name][$k];
					}
					unset($save['id']);
					$id=$v['id'];
					M($type)->where("id = '$id'")->save($save);
				}
				$this->success("保存成功");
			}
		}else{
			$msg=array("error"=>"miss parameter");
			die(json_encode($msg));
		}
	}

	/**
	* 添加表数据
	* @param type 操作表名称
	* @param name 操作表名称(中文)
	**/
	public function add(){
		$type=I("get.type");
		$name=I("get.name");
		$this->tis=$this->Tis($type);
		$this->display();
	}

	/**
	* 删除方法
	* @param id 删除数据id
	* @param table 操作表名称
	* @return json
	**/
	public function del(){
		$id=I("post.id");
		$table=I("post.table");
		if(IS_POST&&$id&&$table){
			$res=M($table)->where("id = '$id'")->delete();
			if($res){
				$return = array("mes"=>"success");
			}else{
				$return = array("mes"=>"fail");
			}
			die(json_encode($return));
		}
	}

}

