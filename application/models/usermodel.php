<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-2
*/
class UserModel extends CI_Model {
	private $table;
	function __construct() {
		parent::__construct();
		$this->table = $table = $this->db->dbprefix('users');
	}
	//验证用户是否合法
	public function verify_user($username,$password){
		$query=$this->db->where('username',$username)->where('password',sha1($password))
			->limit(1)->get('users');
		if($query->num_rows >0){
			$data=$query->result_array();
			//记录登陆IP、时间
			$this->db->where('username',$username)->update('users',array(
					'login_ip'=>$this->session->userdata['ip_address'],
					'last_login'=>date('Y-m-d H:i:s'),
					));
			$this->session->set_userdata('user_id',$data[0]['id']);
			$_SESSION['login']=$data[0]['id'];//应该放在外面较好
			return TRUE;
		}else{
			//记录失败日志
			$this->load->model('logsmodel');
			$this->logsmodel->add_log(1,"尝试登陆失败：用户名{$username} | 密码{$password} | IP{$this->session->userdata['ip_address']}");
			return FALSE;
		}
	}
	/**
	 * 根据Email和密码校验用户有效性
	 * @param  string $email    Email
	 * @param  string $password 密码
	 * @return boolean           成功返回TRUE，失败返回FALSE
	 */
	public function verify_user_by_email($email,$password){
		$query=$this->db->where('email',$email)->where('password',sha1($password))
			->limit(1)->get('users');
		if($query->num_rows >0){
			$data=$query->row_array();
			$this->session->set_userdata('user_id',$data['id']);
			$this->session->set_userdata('username',$data['username']);
			$_SESSION['login']=$data['id'];
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function update_user_mt4account($email,$mt4_account){
		$email = mysql_real_escape_string($email);
		$mt4_account = mysql_real_escape_string($mt4_account);
		return $this->db->query("update {$this->table} set mt4_account=CONCAT_WS(',',mt4_account,'{$mt4_account}') where email = '{$email}'");
	}

	/**
	 * 更新用户证件信息
	 */
	public function update_user_certificate($id,$certificate){
		return $this->db->where('id',$id)->update('users',array('certificate'=>$certificate));
	}

	/**
	 * 获取用户证件信息
	 */
	public function get_user_certificate($id='')
	{
		if ($id==='') {
			//获取所有用户
			$query = $this->db->select('certificate')->get('users');
			if ($query->num_rows>0) {
				return $query->result_array();
			}
		}else{
			//获取指定用户
			$query = $this->db->select('certificate')->where('id',$id)->limit(1)->get('users');
			if ($query->num_rows>0) {
				$result = $query->result_array();
				if(empty($result[0]['certificate'])){
					return FALSE;
				}else{
					return $result[0]['certificate'];
				}
			}

		}
	}
	//验证用户是否已存在
	public function check_user_exist($username,$email){
		$exist = array('username'=>FALSE,'email'=>FALSE);
		if ($username !== '') {
			$query=$this->db->where('username',$username)->limit(1)->get('users');
			if($query->num_rows >0){
				$exist['username']=TRUE;
			}
		}

		if ($email !== ''){
			$query=$this->db->where('email',$email)->limit(1)->get('users');
			if($query->num_rows >0){
				$exist['email']=TRUE;
			}
		}
		
		return $exist;
	}
	//新增用户
	public function add_user($username,$email,$password,$mt4_account=''){
		$ref_name = $this->input->cookie('refName') ? $this->input->cookie('refName') : '';
		$data = array('id'=>'',
				'username'=>$username,
				'email'=>$email,
				'password'=>sha1($password),
				'reg_time'=>date('Y-m-d H:i:s'),
				'last_login'=>date('Y-m-d H:i:s'),
				'login_ip'=>'',
				'reg_ip'=>$this->session->userdata['ip_address'],
				'mt4_account'=>$mt4_account,
				'ref_name'=>mysql_real_escape_string($ref_name),
				);//注册IP
		$query=$this->db->insert('users',$data);
		return $query ? $query : FALSE;
	}
	/**
	 * 获取用户信息
	 * @param  int $id 用户id
	 * @return mixed     数组
	 */
	public function get_user($id){
		$query = $this->db->where('id',$id)->get('users');
		return $query->row_array();
	}
	
	//删除
	public function del($condition){
		$sql = "delete from {$this->table} where id in $condition";
		return $this->db->query($sql);
	}
	
	//总记录数
	public function getItemsNum($condition=''){
		$sql = "select count(*) as itemsNum from {$this->table} $condition";
		$rs = $this->db->query($sql);
		return $rs->result_array()[0]['itemsNum'];
	}
	
	//获取分页数据
	public function getPageItems($condition,$p,$pageSize){
		$sql = "select * from {$this->table} $condition order by reg_time desc limit $p , $pageSize";
		$rs = $this->db->query($sql);
		return $rs->result_array();
	}
	
	//密码修改
	public function modify_pwd($password_old,$password_new,$username){
		$data=array('password'=>sha1($password_new));
		if ($this->verify_user($username, $password_old)){
			return $this->db->where('username',$username)->update('users',$data);
		}else{
			return 1;//旧密码错误
		}
	}

	//重置密码
	public function reset_pwd($email,$pwd,$is_mail=TRUE){
		if ($email=='' || $pwd == '') {
			return FALSE;
		}
		$condition = ($is_mail === TRUE) ? 'email' : 'username';
		return $this->db->where($condition,trim($email))->update('users',array('password'=>sha1($pwd)));
	}

	/**
	 * 根据注册Email获取用户名
	 * @param  string $email 注册Email
	 * @return array        array('username'=>'查询所得用户名')
	 */
	public function get_username($email)
	{
		$query = $this->db->select('username')->get_where('users',array('email'=>$email),1);
		return $query->row_array();
	}

	//验证MT4账户是否有效
	public function is_valid_mt4account($username,$password,$local_port){
		$params['login'] = $username;
		$params['pass'] = $password;
		$action = 'checkpassword';
		$data = $this->mt4($action,$params,$local_port);
		if ($data[0][0] !== 'error') {
			if($data[0][1]==='1'){
				$action = 'getaccountinfo';
				$data = $this->mt4($action,$params,$local_port);
				if ($data[0][1]==='1') {
					if($data[12][1] === '1'){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * 检查mt4帐号是否已关联注册帐号
	 * @param  string $mt4_account mt4帐号
	 * @return mixed              已关联返回关联用户名，未关联返回FALSE
	 */
	public function check_user_mt4account($mt4_account,$server)
	{
		$query=$this->db->select('username,id')->where('mt4_account like ','%' . $mt4_account . substr(md5($server),0,6) . '%')->limit(1)->get('users');
		if ($query->num_rows>0) {
			$result = $query->row_array();
			//记录登陆IP、时间
			$this->db->where('username',$result['username'])->update('users',array(
					'login_ip'=>$this->session->userdata['ip_address'],
					'last_login'=>date('Y-m-d H:i:s'),
					));
			$this->session->set_userdata('user_id',$result['id']);
			return $result['username'];
		}else{
			return FALSE;
		}
	}

	//取mt4账户密码
	public function get_mt4account_pwd($mt4_account){
		$query = $this->db->select('password')->where('user_id',$this->session->userdata['user_id'])->where('mt4_account',$mt4_account)->limit(1)->get('user_mt4account');
		$data = $query->row_array();
		return $data['password'];
	}

	//MT4账户绑定
	public function addMT4Account($username,$password,$local_port,$server_id,$user_id=0){		
		$params['login'] = $username;
		$params['pass'] = $password;
		$action = 'checkpassword';
		$data = $this->mt4($action,$params,$local_port);
		if(isset($data[0][1]) && $data[0][1]==='1'){
			//写入数据库
			$ins = array(
					'user_id'=>($user_id === 0) ? $this->session->userdata['user_id'] : $user_id,
					'mt4_account'=>$username,
					'password'=>$password,
					'server_id'=>$server_id,
					);
			$li = ($user_id === 0) ? $this->getMT4Account() : $this->getMT4Account($user_id);
			//第一个绑定帐号默认为主帐号
			if(empty($li)){
				$ins['is_chief']=1;
			}
			$this->db->insert('user_mt4account',$ins);
		}
		return $data;
	}
	
	//切换MT4帐号状态
	public function changeStat($id){
			$all = $this->getMT4Account();
			foreach ($all as $v) {
				if($v['id'] !== $id){
					$condition[] = $v['id'];
				}
			}
			$this->db->where_in('id',$condition)->update('user_mt4account',array('is_chief'=>0));
			return $this->db->where('id',$id)->update('user_mt4account',array('is_chief'=>1));
	}
	
	//判断MT4帐号是否是主号
	public  function is_chief($id){
		$rs = $this->db->where('id',$id)->get('user_mt4account');
		$data = $rs->row_array();
		if($data['is_chief']==1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	//判断MT4帐号是否是demo组帐号
	public function is_demo($username,$password,$local_port){
		$params['login'] = $username;
		$params['pass'] = $password;
		$action = 'getaccountinfo';
		$data = $this->mt4($action,$params,$local_port);
		foreach ($data as $v){
			if($v[0] === 'group'){
				if(substr($v[1], 0,4)==='demo'){
					return TRUE;
				}else{
					return FALSE;
				}
			}
		}
	}
	
	
	/**
	 * 获取会员账号已绑定mt4账户
	 * @param  integer $user_id 会员ID
	 * @return array 返回mt4账号数组
	 */
	public function getMT4Account($user_id = 0){
		if ($user_id === 0) {
			$user_id = $this->session->userdata['user_id'];
		}
		$result = $this->db->select('user_mt4account.id,mt4_account,password,user_mt4account.is_chief,server.name,user_mt4account.server_id')->join('server','user_mt4account.server_id=server.id','left')->where('user_id',$user_id)->get('user_mt4account');
		return $result->result_array();
	}
	
	//删除mt4账户
	public function delMT4Account($condition){
		$table = $this->db->dbprefix('user_mt4account');
		$sql = "delete from {$table} where id in $condition";
		return $this->db->query($sql);
	}
	/**
	 * 删除绑定mt4账户
	 * @param  integer $userid      用户id
	 * @param  integer $mt4_account mt4账户
	 * @return integer 失败返回0，成功返回大于0的数值
	 */
	public function delMT4AccountByUserId($userid,$mt4_account){
		 $result = $this->db->where(array('user_id'=>$userid,
			'mt4_account'=>$mt4_account))->delete('user_mt4account');
		 if ($result === TRUE) {
		 	return $this->db->affected_rows();
		 }else{
		 	return 0;
		 }		 
	}

	public function getUserByMT4AccountAndServerId($mt4_account,$server_id){
		$query = $this->db->select('users.id,users.username')->join('users','users.id=user_mt4account.user_id','left')
		->where(array('server_id'=>$server_id,
			'user_mt4account.mt4_account like'=>'%'.$mt4_account.'%'))->get('user_mt4account');
		return $query->result_array();
	}

	//取mt4账户密码
	public function getMT4Pass($account){
		$query = $this->db->select('password')->where('mt4_account',$account)->get('user_mt4account');
		$data = $query->row_array();
		if (empty($data)) {
			return FALSE;
		}
		return $data['password'];
	}
	
	//是否已存在
	public function checkMT4Exist($username,$server_id){
		$query=$this->db->where(array('mt4_account'=>$username,'server_id'=>$server_id))->limit(1)->get('user_mt4account');
		if($query->num_rows >0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	//创建MT4帐号
	public function createMT4Account($params,$local_port=7788){
		return $this->mt4('createaccount',$params,$local_port);
	}

	//取MT4账户余额
	public function getmargininfo($login,$local_port=7788){
		$params['login']=$login;
		//return $this->mt4('getaccountbalance',$params);
		return $this->mt4('getmargininfo',$params,$local_port);
		//return $this->mt4('getaccountinfo',$params);
		
	}

	//不喊信用额度的余额
	public function getBalance($login,$local_port=7788){
		$params['login']=$login;
		return $this->mt4('getaccountbalance',$params);	
	}

	//根据MT4账户获取服务器信息
	public function get_server_by_mt4($mt4_account){
		$query = $this->db->select('server.*')->join('server','server.id=user_mt4account.server_id','left')->where('user_mt4account.mt4_account',$mt4_account)->where('user_id',$this->session->userdata['user_id'])->get('user_mt4account');
		return $query->row_array();
	}

	//更改MT4帐号余额
	public function changeBlance($params,$local_port=7788){
		return $this->mt4('changebalance',$params,$local_port);
	}

	/**
	 * 获取历史订单信息。
	 *
	 * $params=array('login'=>'login','from'=>'from','to'=>'to')
	 * @return array
	 */
	public function getHistory($params,$local_port=7788){
		return $this->mt4('gethistory',$params,$local_port);
	}

	/**
	 * 获取MT4账户信息。
	 *
	 * $params=array('login'=>'login')
	 * @return array
	 */
	public function getAccountInfo($params,$local_port=7788){
		//玩下Exception:)
		if (!is_array($params)) {
			throw new Exception("the type of parameter $params should be Array.", 1);
		}
		return $this->mt4('getaccountinfo',$params,$local_port);
	}

	/**
	 * 获取服务器上所有组。
	 *
	 * @return array
	 */
	public function getServerGroups($local_port=7788){
		return $this->mt4('getgroups',array(),$local_port);
	}
	
	//mt4账户操作
	/*
	array (size=2)
  0 => 
    array (size=2)
      0 => string 'result' (length=6)
      1 => string '-3' (length=2)
  1 => 
    array (size=2)
      0 => string 'reason' (length=6)
      1 => string 'invalid params' (length=14)

      array (size=2)
  0 => 
    array (size=2)
      0 => string 'result' (length=6)
      1 => string '1' (length=1)
  1 => 
    array (size=2)
      0 => string 'login' (length=5)
      1 => string '2123459105' (length=10)
	*/
	private function mt4($action,$params,$local_port=7788){
		if (!defined('SERVER_ADDRESS') && !defined('SERVER_PORT')) {
			define('SERVER_ADDRESS','127.0.0.1');
			define('SERVER_PORT',$local_port);
		}

		$this->load->library('mt4datareciver');
		$this->mt4datareciver->OpenConnection(SERVER_ADDRESS,SERVER_PORT);
		$answerData =$this->mt4datareciver->MakeRequest($action, $params);
		$this->mt4datareciver->CloseConnection();
		if ($action=='changebalance') {
			file_put_contents('./orderLog.log',date('Y-m-d H:i:s') . " changebalance\r\n" . $answerData,FILE_APPEND);
		}
		$resultData = explode('&',$answerData);
		foreach ($resultData as $v){
			$data[]=explode('=',$v);
		}
		return $data;
	}
}
?>