<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-19
*/
class userbankmodel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	//添加
	public function addBank($data){
		$data['user_id']=$this->session->userdata('user_id');
		if(!$data['user_id']){
			return FALSE;
		}
		return $this->db->insert('user_bank',$data);
	}
	//获取
	public function getBank(){
		$rs = $this->db->where('user_id',$this->session->userdata('user_id'))->get('user_bank');
		return $rs->result_array();
	}
	//更新
	public function updateBank($data){
		if(!$this->session->userdata('user_id')){
			return FALSE;
		}
		return $this->db->where('user_id',$this->session->userdata('user_id'))->update('user_bank',$data);
	}
}
?>