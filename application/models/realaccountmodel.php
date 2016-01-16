<?php
/**
* 
*/
class RealAccountModel extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		$this->table = $table = $this->db->dbprefix('realaccount');
	}

	public function add($params){
		$this->db->insert('realaccount',$params);
		return $this->db->insert_id();
	}

	public function getAll(){
		$query = $this->db->order_by('id','desc')->get('realaccount');
		return $query->result_array();
	}

	public function getById($id){
		$query = $this->db->get_where('realaccount',array('id'=>$id),1);
		return $query->row_array();
	}

	//总记录数
	public function getItemsNum($condition=''){
		$sql = "select count(*) as itemsNum from {$this->table} $condition";
		$rs = $this->db->query($sql);
		return $rs->result_array()[0]['itemsNum'];
	}
	
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select * from {$this->table} $condition order by add_time desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}

	/**
	 * 更新MT4账户
	 * @param  integer $mt4Account MT4账户
	 * @param  integer $id 待更新记录的ID
	 * @return void
	 */
	public function updateMt4Account($mt4Account,$id)
	{
		$this->db->where('id',$id);
		return $this->db->update('realaccount',array('mt4_account' => strval($mt4Account)));
	}
}
?>