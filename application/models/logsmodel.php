<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-12
*/
class logsmodel extends CI_Model{
	private $table;
	public function __construct(){
		parent::__construct();
		$this->table = $table = $this->db->dbprefix('logs');
	}
	//总记录数
	public function getItemsNum($condition=''){
			$sql = "select count(*) as itemsNum from {$this->table} $condition";
			$rs = $this->db->query($sql);
			return $rs->result_array()[0]['itemsNum'];
	}
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select * from {$this->table} $condition order by time desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	//删除
	public function del($condition){
		if(!$condition){
			return false;
		}
		$sql = "delete from {$this->table} where id in $condition";
		return $this->db->query($sql);
	}

	public function add_log($type,$content){
		$data = array(
			'type'=>$type,
			'content'=>$content,
			'time'=>date('Y-m-d H:i:s'),
			);
		$this->db->insert('logs',$data);
	}
}
?>