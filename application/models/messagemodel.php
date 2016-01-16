<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2014-5-15
*/
class MessageModel extends CI_Model{
	private $table;
	public function __construct(){
		parent::__construct();
		$this->table = $this->db->dbprefix('message');
	}
	//总记录数
	public function getItemsNum($condition=''){
			$sql = "select count(*) as itemsNum from {$this->table} $condition";
			$rs = $this->db->query($sql);
			return $rs->result_array()[0]['itemsNum'];
	}
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select * from {$this->table} $condition order by addTime desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	//删除
	public function deleteMessage($id){
		return $this->db->where('id',$id)->delete('message');
		//return $this->db->where('id',$id)->update('message',array('status'=>1));
	}

	//恢复
	public function recoverMessage($id){
		return $this->db->where('id',$id)->update('message',array('status'=>0));
	}

	public function addMessage($data){
		return $this->db->insert('message',$data);
	}

	public function updateMessage($id,$data){
		return $this->db->where('id',$id)->update('message',$data);
	}

	public function getMessage($id=0){
		if ($id === 0) {
			$query = $this->db->get('message');
			return $query->result_array();
		} else {
			$query = $this->db->where('id',$id)->get('message');
			return $query->row_array();
		}
	}

	public function getLatestMessage($num){
		$query = $this->db->limit($num)->order_by('addTime','desc')->get('message');
		return $query->row_array();
	}
}
?>