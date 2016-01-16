<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2014-3-17
*/
class AgentModel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 添加代理
	 * @param array $data 代理账户信息
	 */
	public function addAgent($data){
		return $this->db->insert('agent',$data);
	}
	/**
	 * 获取代理
	 * @param  integer $id 表记录id
	 * @return array     单个或多个代理记录
	 */
	public function getAgent($id=''){
		if($id==''){
			$rs = $this->db->get('agent');
			return $rs->result_array();
		}else{
			$rs = $this->db->get_where('agent',array('id' => $id),1);
			return $rs->row_array();
		}
	}
	/**
	 * 删除代理
	 * @param  [type] $condition 条件
	 */
	public function delAgent($condition){
		$table = $this->db->dbprefix('agent');
		$sql = "delete from $table where id in $condition";
		return $this->db->query($sql);
	}

	public function getAgentByAgentId($agentId){
		$rs = $this->db->get_where('agent',array('agent_id' => $agentId),1);
		return $rs->row_array();
	}

	//检查AgentId是否已存在
	public function is_exist($agent_id){
		$query = $this->db->get_where('agent',array('agent_id'=>$agent_id),1);
		if ($query->num_rows>0 || $query === FALSE) {
			return TRUE;
		}
		return FALSE;
	}
}
?>