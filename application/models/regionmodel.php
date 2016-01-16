<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-19
*/
class regionmodel extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_regions($type=0,$parent=0){
		$rs = $this->db->select('region_id,region_name')->get_where('region',array('region_type'=>$type,'parent_id'=>$parent));
		return $rs->result_array();
	}
	
	public function get_name($id){
		$rs = $this->db->select('region_name')->get_where('region',array('region_id'=>$id));
		$data = $rs->row();
		return $data->region_name;
	}
}
?>