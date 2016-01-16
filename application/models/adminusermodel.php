<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-18
*/
class AdminUserModel extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	//验证用户是否合法
	public function verify_user($username,$password){
		$query=$this->db->where('username',$username)->where('password',sha1($password))
			->limit(1)->get('admin_user');
		if($query->num_rows >0){
			//记录登陆IP
			$this->db->where('username',$username)->update('admin_user',array('login_ip'=>$this->session->userdata['ip_address']));
			return TRUE;
		}else{
			//记录失败日志
			//$this->load->model('logsmodel');
			//$this->logsmodel->add_log(1,"尝试登陆失败：用户名{$username} | 密码{$password} | IP{$this->session->userdata['ip_address']}");
			return FALSE;
		}
	}
	//验证用户是否已存在
	public function check_user_exist($username,$email){
		$exist = array('username'=>FALSE,'email'=>FALSE);
		if ($username == '' or $email == '') {
			return FALSE;
		}
		
		$query=$this->db->where('username',$username)->limit(1)->get('admin_user');
		if($query->num_rows >0){
			$exist['username']=TRUE;
		}

		$query=$this->db->where('email',$email)->limit(1)->get('admin_user');
		if($query->num_rows >0){
			$exist['email']=TRUE;
		}
		
		return $exist;
	}
	//新增用户
	public function add_user($username,$email,$password){
		$data = array('id'=>'',
				'username'=>$username,
				'email'=>$email,
				'password'=>sha1($password),
				'reg_time'=>date('Y-m-d H:i:s'),
				'last_login'=>date('Y-m-d H:i:s'),
				'login_ip'=>'',
				'reg_ip'=>$this->session->userdata['ip_address']);//注册IP
		$query=$this->db->insert('admin_user',$data);
		return $query ? $query : FALSE;
	}
	
	//密码修改
	public function modify_pwd($password_old,$password_new,$username='admin'){
		$data=array('password'=>sha1($password_new));
		if ($this->verify_user($username, $password_old)){
			return $this->db->where('username',$username)->update('admin_user',$data);
		}else{
			return 1;//旧密码错误
		}
	}
}
?>