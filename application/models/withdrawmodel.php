<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-12
*/
class withdrawmodel extends CI_Model{
	private $table;
	public function __construct(){
		parent::__construct();
		$this->table = $table = $this->db->dbprefix('withdraw');
	}
	//总记录数
	public function getItemsNum($condition=''){
			$sql = "select count(*) as itemsNum from {$this->table} $condition";
			$rs = $this->db->query($sql);
			return $rs->result_array()[0]['itemsNum'];
	}
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select * from {$this->table} $condition order by time desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	//获取导出数据
	public function getOutputData($condition,$select_item){
		if (is_array($select_item)) {
			$select_item = implode(',', $select_item);
		}
		$sql = "select $select_item from {$this->table} $condition order by time desc";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	//删除
	public function del($condition){
		$sql = "delete from {$this->table} where id in $condition";
		return $this->db->query($sql);
	}
	
	//添加
	public function add($data){
		$this->db->insert('withdraw',$data);
		return $this->db->insert_id();
	}
	
	//状态切换
	public function changeHK($id){
		$sql = "update {$this->table} set params = case params when '0' then '1' when '1' then '0' end where id = $id";
		if (! $this->db->query($sql)) {
			return 'Update error';
		}
		$row = $this->db->query ( "select params from {$this->table} where id = $id" );
		$row = $row->result_array();
		if (! $row) {
			return  'Get data error' ;
		}
		return $row;
	}

	public function changeInfo($params,$id){
		return $this->db->where('id',$id)->update('withdraw',$params);
	}

	//总汇款金额
	public function getTotalRMB(){
		$rs = $this->db->select_sum('rmb')->where('params','1')->get('withdraw');
		$rs = $rs->row_array();
		return $rs['rmb'];
	}

	/**
	 * 获取分组统计数组
	 * @param  string $type 分组类型-年/月/周
	 * @return array       分组数组
	 */
	public function getDataByTimezone($type='m'){
		switch ($type) {
			case 'm':
				$sql = "SELECT DATE_FORMAT(time,'%Y-%m') g_time, SUM(rmb) as g_amount from {$this->table} WHERE params = '1' GROUP BY g_time";
				break;
			case 'y':
				$sql = "SELECT DATE_FORMAT(time,'%Y') g_time, SUM(rmb) as g_amount from {$this->table} WHERE params = '1' GROUP BY g_time";
				break;
			case 'w':
				$sql = "SELECT DATE_FORMAT(time,'%Y-%uW') g_time, SUM(rmb) as g_amount from {$this->table} WHERE params = '1' GROUP BY g_time";
				break;
			default:
				return FALSE;
		}
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}

	/**
	 * 获取出金排行榜数据
	 * @param  integer $length 数据条数，为0则表示获取全部
	 * @param string $time 'a'-全部 'm'-月 'q'-季 'y'-年
	 * @return array          出金排行榜数据，二维数组
	 */
	public function getWithdrawTopList($length = 0,$time = 'a'){
		$sql = "SELECT {$this->db->dbprefix('user_mt4account')}.user_id, 
			{$this->db->dbprefix('users')}.username, sum({$this->table}.rmb) as g_amount
			FROM {$this->table}
			RIGHT JOIN {$this->db->dbprefix('user_mt4account')}
			ON {$this->table}.account = {$this->db->dbprefix('user_mt4account')}.mt4_account
			LEFT JOIN {$this->db->dbprefix('users')}
			ON {$this->db->dbprefix('users')}.id = {$this->db->dbprefix('user_mt4account')}.user_id
			WHERE {$this->table}.params=1";
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
}
?>