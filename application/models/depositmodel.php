<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-12
*/
class depositmodel extends CI_Model{
	private $table;
	public function __construct(){
		parent::__construct();
		$this->table = $this->db->dbprefix('deposit');
	}
	//总记录数
	public function getItemsNum($condition=''){
			$sql = "select count(*) as itemsNum from {$this->table} $condition";
			$rs = $this->db->query($sql);
			return $rs->result_array()[0]['itemsNum'];
	}
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select t1.*, t2.name from {$this->table} as t1 left join 
		{$this->db->dbprefix('server')} as t2 on t1.server_id = t2.id $condition 
		order by time desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	//总有效金额
	public function getTotalAmount(){
		$rs = $this->db->where('is_ok',1)->select_sum('amount')->get('deposit');
		$rs = $rs->result_array();
		return $rs[0]['amount'];
	}
	//删除
	public function del($condition){
		$sql = "delete from {$this->table} where id in $condition";
		return $this->db->query($sql);
	}
	
	//提交订单时插入订单
	public function add($params){
		$params['user_id'] = $this->session->userdata['user_id'];
		return $this->db->insert('deposit',$params);
	}
	//更新订单
	public function changeOrder($params,$my_id){
		$query = $this->db->where('my_id',$my_id)->update('deposit',$params);
		return $query;
	}
	//锁表
	public function lockTable(){
		$tb = $this->table;
		$this->db->query("lock table {$tb} write");
	}
	//解锁
	public function unlockTable(){
		$this->db->query("unlock tables");
	}

	//是否已即时入金
	public function is_deposit($id){
		$query = $this->db->select('id')->where('order_id',$id)->get('deposit');
		if ($query->num_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}

	//订单是否支付成功
	public function is_ok($my_id){
		$query = $this->db->select('is_ok')->where('my_id',$my_id)->get('deposit');
		$result = $query->result_array();
		if ($result['is_ok'] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
		
	}

	//根据订单号获取服务器端口号
	public function getServerLocalPort($my_id){
		$query = $this->db->select('server.local_port')->join('server','deposit.server_id=server.id')->where('my_id',$my_id)->get('deposit');
		$rs = $query->row_array();
		return empty($rs) ? FALSE : $rs['local_port'];
	}
	
	//根据订单号获取服务器名称
	public function getServerName($my_id){
		$query = $this->db->select('server.name')->join('server','deposit.server_id=server.id')->where('my_id',$my_id)->get('deposit');
		$rs = $query->row_array();
		return empty($rs) ? FALSE : $rs['name'];
	}
	/**
	 * 根据订单号获取MT4账户
	 * @param  string $my_id 订单号
	 */
	public function getMT4ByMyId($my_id){
		$query = $this->db->select('account')->where('my_id',$my_id)->get('deposit');
		$result = $query->row_array();
		return empty($result) ? FALSE : $result['account'];
	}

	/**
	 * 根据订单号获取订单金额
	 * @param  string $my_id 订单号
	 */
	public function getAmountByMyId($my_id){
		$query = $this->db->select('amount')->where('my_id',$my_id)->get('deposit');
		$result = $query->row_array();
		return empty($result) ? FALSE : $result['amount'];
	}

	/**
	 * 获取分组统计数组
	 * @param  string $type 分组类型-年/月/周
	 * @return array       分组数组
	 */
	public function getDataByTimezone($type='m'){
		switch ($type) {
			case 'm':
				$sql = "SELECT DATE_FORMAT(time,'%Y-%m') g_time, SUM(amount) as g_amount from {$this->table} WHERE is_ok=1 GROUP BY g_time";
				break;
			case 'y':
				$sql = "SELECT DATE_FORMAT(time,'%Y') g_time, SUM(amount) as g_amount from {$this->table} WHERE is_ok=1 GROUP BY g_time";
				break;
			case 'w':
				$sql = "SELECT DATE_FORMAT(time,'%Y-%uW') g_time, SUM(amount) as g_amount from {$this->table} WHERE is_ok=1 GROUP BY g_time";
				break;
			default:
				return FALSE;
		}
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}

	/**
	 * 获取入金排行榜数据
	 * @param  integer $length 数据条数，为0则表示获取全部
	 * @param string $time 'a'-全部 'm'-月 'q'-季 'y'-年
	 * @return array          入金排行榜数据
	 */
	public function getDepositTopList($length = 0,$time = 'a'){
		$sql = "SELECT {$this->db->dbprefix('user_mt4account')}.user_id, 
			{$this->db->dbprefix('users')}.username, sum({$this->table}.amount) as g_amount
			FROM {$this->table}
			RIGHT JOIN {$this->db->dbprefix('user_mt4account')}
			ON {$this->table}.account = {$this->db->dbprefix('user_mt4account')}.mt4_account
			LEFT JOIN {$this->db->dbprefix('users')}
			ON {$this->db->dbprefix('users')}.id = {$this->db->dbprefix('user_mt4account')}.user_id
			WHERE {$this->table}.is_success=1";
		switch ($time) {
			case 'm':
				$sql .= " AND TO_DAYS(NOW()) - TO_DAYS({$this->table}.time) <= 30";
				break;
			case 'q':
				$sql .= " AND TO_DAYS(NOW()) - TO_DAYS({$this->table}.time) <= 90";
				break;
			case 'y':
				$sql .= " AND TO_DAYS(NOW()) - TO_DAYS({$this->table}.time) <= 365";
				break;
			default:
				break;
		}
		$sql .=" GROUP BY user_id
			ORDER BY g_amount DESC";
		$length +=0;
		if (intval($length) > 0) {
			$sql .= " LIMIT $length";
		}

		$rs = $this->db->query($sql);
		return $rs->result_array();
	}

	/**
	 * 获取导出数据
	 * @param  string $condition   查询条件
	 * @param  array $select_item 查询字段
	 * @return array              交易数据
	 */
	public function getOutputData($condition,$select_item){
		if (is_array($select_item)) {
			$select_item = implode(',', $select_item);
		}
		//连表查询获取姓名信息
		$user_bank_table = $this->db->dbprefix('user_bank');
		$sql = "select $select_item from {$this->table} left join {$user_bank_table} on 
		{$user_bank_table}.user_id = {$this->table}.user_id $condition order by {$this->table}.time desc";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
}
?>