<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-30
*/
class captchamodel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function add($params){
		return $this->db->insert('captcha',$params);
	}
	//根据过期时间删除记录
	public function del($expiration){
		return $this->db->where('captcha_time <',$expiration)->delete('captcha');
	}

	//
	public function get($condition){
		$query = $this->db->where($condition)->get('captcha');
		if ($query->num_rows() == 0) {
			return FALSE;
		} else {
			return TRUE;
		}
		
	}
}
?>