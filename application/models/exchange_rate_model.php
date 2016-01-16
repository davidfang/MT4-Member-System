<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-11
*/
class exchange_rate_model extends CI_Model{
	function __construct() {
		parent::__construct();
	}
	
	public function get_exchange_rate(){
		$rs = $this->db->select('id,withdraw_rate,deposit_rate,withdraw_factor,deposit_factor')->order_by('id','desc')->limit(1)->get('exchange_rate');
		if($rs->num_rows > 0){
			return $rs->result();
		}else if ($rs->num_rows === 0){
			return 1;//未设置汇率
		}else{
			return false;
		}
	}
	
	public function add_exchange_rate($withdraw,$deposit,$withdraw_factor,$deposit_factor){
		$data = array(
				'withdraw_rate'=>$withdraw,
				'deposit_rate'=>$deposit,
				'withdraw_factor'=>$withdraw_factor,
				'deposit_factor'=>$deposit_factor,
				'created'=>date('Y-m-d H:i:s')
				);
		$rs = $this->db->insert('exchange_rate',$data);
		return $rs ? $rs : FALSE;
	}

	public function get_withdraw_factor(){
		$rs = $this->db->select('withdraw_factor')->order_by('id','desc')->limit(1)->get('exchange_rate');
		$data = $rs->row_array();
		return $rs['withdraw_factor'];
	}
}
?>