<?php
/**
* 
*/
class DemoAccountModel extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function add($params){
		return $this->db->insert('demoaccount',$params);
	}

	public function getAll(){
		$query = $this->db->order_by('id','desc')->get('demoaccount');
		return $query->result_array();
	}

	public function getById($id){
		$query = $this->db->get_where('demoaccount',array('id'=>$id),1);
		return $query->row_array();
	}
}
?>