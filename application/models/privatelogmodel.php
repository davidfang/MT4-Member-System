<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-12
*/
class privatelogmodel extends CI_Model{

	public function add($role,$ip,$content){
		$data = array(
			'role'=>$role,
			'ip'=>$ip,
			'content'=>$content,
			'time'=>date('Y-m-d H:i:s'),
			);
		$this->db->insert('private_log',$data);
	}
}
?>