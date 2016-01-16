<?php
/**
 * paymentmodel类文件
 * 
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com
 */

/**
 * 用于支付方式管理的数据库相关操作类
 *
 * 支付方式数据库表的相关操作，主要用于后台管理操作调用
 * @package admin
 * @since 2.0
 */
class paymentmodel extends CI_Model{
	public function getEnabledPayment(){
		$rs = $this->db->get_where('payment',array('enabled'=>1));
		return $rs->result_array();
	}
	
	public function getAllPayment(){
		$rs = $this->db->get('payment');
		return $rs->result_array();
	}
	
	public function getMerchant($code=''){
		if ($code !== '') {
			$rs = $this->db->select('merchant,secret')->get_where('payment',array('code'=>$code,'enabled'=>1));
			return $rs->result_array();
		}
		$rs = $this->db->select('merchant,secret')->get('payment');
		return $rs->result_array();
	}

	public function add($data){
		$li = $this->getAllPayment();
		//第一个绑定帐号默认为主帐号
		if(empty($li)){
			$data['enabled']=1;
		}else{
			$data['enabled']=0;
		}
		$query = $this->db->insert('payment',$data);
		return $query;
	}

	//删除
	public function del($condition){
		$table = $this->db->dbprefix('payment');
		$sql = "delete from {$table} where id in $condition";
		return $this->db->query($sql);
	}

	/**
	 * 判断支付方式是否已启用
	 * @param  integer  $id 支付方式ID
	 * @return boolean     已启用返回TRUE，未启用返回FAlSE
	 */
	public function is_chief($id)
	{
		$rs = $this->db->get_where('payment',array('id'=>$id),1);
		$data = $rs->row_array();
		if($data['enabled']==1){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	/**
	 * 改变支付方式的启用状态
	 * @param  integer $id 支付方式ID
	 * @return boolean     成功返回TRUE，失败返回FALSE
	 */
	public function change_state($id)
	{
		$query = $this->db->select('id')->get('payment') or die();
		$all = $query->result_array();
		foreach ($all as $v) {
			if($v['id'] !== $id){
				$condition[] = $v['id'];
			}
		}
		$this->db->where_in('id',$condition)->update('payment',array('enabled'=>0));
		return $this->db->where('id',$id)->update('payment',array('enabled'=>1));
	}
}
?>