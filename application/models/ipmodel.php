<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-15
*/
class ipmodel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	//添加
	public function addIp($data){
		return $this->db->insert('server',$data);
	}
	//获取
	public function getIp($id=''){
		if($id==''){
			$rs = $this->db->get('server');
			return $rs->result_array();
		}else{
			$rs = $this->db->get_where('server',array('id' => $id),1);
			return $rs->row_array();
		}
	}
	//删除
	public function delIp($condition){
		$table = $this->db->dbprefix('server');
		$sql = "delete from $table where id in $condition";
		return $this->db->query($sql);
	}
	//切换server状态
	public function changeStat($id){
		$this->db->where('id <>',$id)->update('server',array('is_chief'=>0));
		return $this->db->where('id',$id)->update('server',array('is_chief'=>1));
	}
	
	//判断server是否是主服务器
	public  function is_chief($id){
		$rs = $this->db->where('id',$id)->get('server');
		$data = $rs->row_array();
		if($data['is_chief']==1){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	//检查server是否已存在 端口号唯一
	public function is_exist($local_port){
		$query = $this->db->get_where('server',array('local_port'=>$local_port),1);
		if ($query->num_rows>0 || $query === FALSE) {
			return TRUE;
		}
		return FALSE;
	}

	//本地端口号获取服务器名称
	public function getServerByLocalPort($localPort){
		$rs = $this->db->get_where('server',array('local_port' => $localPort),1);
		return $rs->row_array();
	}
	/**
	 * 更新服务器数据
	 * @param  array $data 新数据
	 * @param  int $id   主键
	 * @return bool       成功返回true，失败返回false
	 */
	public function saveServer($data,$id)
	{
		return $this->db->where('id',$id)->update('server',$data);
	}

	/**
	 * 获取默认服务器
	 * @return array 默认服务器信息
	 */
	public function getDefaultServer()
	{
		$rs = $this->db->get_where('server',array('is_chief' => 1),1);
		return $rs->row_array();
	}
}
?>