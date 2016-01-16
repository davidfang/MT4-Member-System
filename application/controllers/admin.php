<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-3
*/
class Admin extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		session_start();
		// 判断是否登录，排除login页面
		$sec = $this->uri->segment ( 2 );
		if ($sec !== 'login' && $sec !== 'info' && ! $this->is_login ()) {
			redirect ( 'admin/login' );
		}
		//$this->output->enable_profiler(TRUE);
	}
	public function index($type='m') {
		$data = array (
				'title' => '首页' 
		);
		//分组数据类型
		$type_array=array('y','m','w');
		if (!in_array($type, $type_array)) {
			$type='m';
		}
		$this->load->model('depositmodel');
		$this->load->model('withdrawmodel');
		$result = $this->depositmodel->getDataByTimezone($type);
		$result==false && $result=array();
		$data['dp_x'] = $this->array_php2js($result);
		$data['dp_y'] = $this->array_php2js($result,'y');

		$rs = $this->withdrawmodel->getDataByTimezone($type);
		$rs==false && $rs=array();
		$data['wr_x'] = $this->array_php2js($rs);

		//填充不存在的项为0
		$final=array();
		$wr_x = explode(',',str_replace("'",'',substr($data['wr_x'],1,strlen($data['wr_x'])-2)));
		foreach ($result as $val) {
			if (in_array($val['g_time'], $wr_x)) {
				foreach ($rs as $v) {
					if ($v['g_time']==$val['g_time']) {
						$amount = $v['g_amount'];
					}
				}
				$final[]=array('g_time'=>$val['g_time'],'g_amount'=>$amount);
			}else{
				$final[]=array('g_time'=>$val['g_time'],'g_amount'=>0);
			}
		}
		$data['wr_y'] = $this->array_php2js($final,'y');
		//合计
		$data ['sumDeposition'] = $this->depositmodel->getTotalAmount ();
		$data['sumWithdraw'] = $this->withdrawmodel->getTotalRMB();

		$data['type'] = $type;
		$this->load->view ( 'admin/index', $data );
	}
	// 登陆
	public function login() {
		$this->load->library ( 'form_validation' );
		$this->form_validation->set_rules ( 'username', 'Username', 'required' );
		$this->form_validation->set_rules ( 'password', 'Password', 'required' );
		
		if ($this->form_validation->run () !== FALSE) {
			$username = $this->input->post ( 'username' );
			$password = $this->input->post ( 'password' );
			$this->load->model ( 'adminusermodel' );
			$result = $this->adminusermodel->verify_user ( $username, $password );
			if ($result === FALSE) {
				//private log
				$this->writePrivateLog('登陆失败');
				$data['error']='帐号/密码无效！';
				$this->load->view ( 'admin/login' ,$data);
			} else {
				$this->session->set_userdata ( 'username', $username );
				$_SESSION ['admin'] = 'admin';
				//private log
				$this->writePrivateLog('登陆成功');
				redirect ( 'admin' );
			}
		} else {
			$this->load->view ( 'admin/login' );
		}
	}
	// 退出登陆
	public function logout() {
		$this->session->unset_userdata ( 'username' );
		unset($_SESSION ['admin']);
		$this->writePrivateLog('退出登陆');
		redirect ( 'admin/login' );
	}
	
	// 修改信息
	public function modify($a) {
		$msg = '';
		if ($a === 'pwd') {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password_old','旧密码','trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_new','新密码','trim|required|min_length[6]|max_length[20]');

			if($this->form_validation->run()!==FALSE){
				$password_old = trim($this->input->post ( 'password_old' ));
				$password_new = trim($this->input->post ( 'password_new' ));
				$this->load->model ( 'adminusermodel' );
				$rs = $this->adminusermodel->modify_pwd ( $password_old, $password_new );
				if ($rs === 1) {
					$msg = '旧密码错误，密码修改失败！';
				} else if ($rs === true) {
					$msg = '密码修改成功！';
				} else {
					$msg = '密码修改失败！';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' );
			}else{
				$data = array('title'=>'首页');
				$this->load->view('index',$data);
			}
		} else if ($a === 'rate') {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('withdraw','出金汇率','trim|required|greater_than[0]');
			$this->form_validation->set_rules('deposit','入金汇率','trim|required|greater_than[0]');
			$this->form_validation->set_rules('withdraw_factor','出金系数','trim|required|greater_than[0]');
			$this->form_validation->set_rules('deposit_factor','入金系数','trim|required|greater_than[0]');

			if($this->form_validation->run()!==FALSE){
				$withdraw = trim($this->input->post ( 'withdraw' ));
				$deposit = trim($this->input->post ( 'deposit' ));
				$withdraw_factor = trim($this->input->post ( 'withdraw_factor' ));
				$deposit_factor = trim($this->input->post ( 'deposit_factor' ));
				$this->load->model ( 'exchange_rate_model' );
				if (TRUE) {
					$rs = $this->exchange_rate_model->add_exchange_rate ( $withdraw, $deposit ,$withdraw_factor,$deposit_factor);
					if ($rs) {
						$msg = '汇率设置成功！';
					} else {
						$msg = '汇率设置失败！';
					}
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' );
			}else{
				$data = array ();
				$this->load->model ( 'exchange_rate_model' );
				$rs = $this->exchange_rate_model->get_exchange_rate ();
				if ($rs === 1) {
					$data ['msg'] = '还未设置汇率！';
				} else if ($rs === FALSE) {
					$data ['msg'] = '获取汇率设置有误！';
				} else {
					$data = $rs [0];
				}
				$data->title = '汇率设置';
				$this->load->view ( 'admin/exchange_rate', $data );
			}
		}else if($a==='ip'){
			$id = trim($this->input->post('id'))+0;
			if($id === FALSE){
				echo '非法操作';
			}
			$this->load->model ( 'ipmodel' );
			if(!$this->ipmodel->changeStat($id)){
				exit('Update error!');
			}
			if($this->ipmodel->is_chief($id)){
				echo '默认服务器';
			}else{
				echo '其它服务器';
			}
		}
	}

	public function edit($type,$data){
		if ($type === 'server') {
			$this->load->model('ipmodel');
			if (is_numeric($data)) {
				$result = $this->ipmodel->getIp($data);
				$server_data['list']=$result;
				$server_data['title']='编辑服务器';
				$this->load->view('admin/edit_server', $server_data);
			}elseif ($data === 'save') {
				$id=$this->input->post('id')+0;
				$new_data['name']=trim($this->input->post('server_name'));
				$new_data['ip']=trim($this->input->post('ip'));
				$new_data['port']=trim($this->input->post('port'))+0;
				$new_data['local_port']=trim($this->input->post('local_port'))+0;
				$new_data['is_chief']=$this->input->post('is_chief');
				$new_data['params']=trim($this->input->post('WebTraderPort'))+0;
				$result = $this->ipmodel->saveServer($new_data,$id);
				if($result===true){
					$this->session->set_flashdata ( 'info', '服务器设置保存成功' );
					redirect ( '/admin/info/' );
				}
			}
		}
	}

	public function payment($a=''){
		if ($a==='del') {
			$_POST ['id'] = mysql_real_escape_string($_POST ['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'paymentmodel' );
			if ($this->paymentmodel->del ( $condition )) {
				echo '1';
			} else {
				echo '0';
			}
		}elseif($a==='add'){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('payname','名称','trim|required');
			$this->form_validation->set_rules('username','商户号','trim|required');

			$this->load->model('paymentmodel');
			if($this->form_validation->run()!==FALSE){
				$data = array(
					'name'=>trim($this->input->post('payname')),
					'merchant'=>trim($this->input->post('username')),
					'secret'=>trim($this->input->post('password')),
					'code'=>trim($this->input->post('payment_type')),
					);
				if($this->paymentmodel->add($data)){
					$this->session->set_flashdata ( 'info', '接口添加成功' );
				}else{
					$this->session->set_flashdata ( 'info', '接口添加失败' );
				}
				redirect ( '/admin/info/' );
			}else{
				$pay = $this->paymentmodel->getAllPayment();
				$data=array('list'=>$pay,'title'=>'接口设置');
				$this->load->view ( 'admin/interface',$data );
			}
		}elseif ($a==='change') {
			//ajax
			$id = $this->input->post('id')+0;
			if($id === FALSE || !is_numeric($id)){
				echo '非法操作';
			}
			$this->load->model ( 'paymentmodel' );
			if(!$this->paymentmodel->change_state($id)){
				exit('Update error!');
			}
			if($this->paymentmodel->is_chief($id)===TRUE){
				echo '已启用';
			}else{
				echo '未启用';
			}
		}
	}
	//配置
	public function setinfo($sort) {
		if ($sort == 'exchange_rate') {
			$data = array ();
			$this->load->model ( 'exchange_rate_model' );
			$rs = $this->exchange_rate_model->get_exchange_rate ();
			if ($rs === 1) {
				$data ['msg'] = '还未设置汇率！';
			} else if ($rs === FALSE) {
				$data ['msg'] = '获取汇率设置有误！';
			} else {
				$data = $rs [0];
			}
			$data->title = '汇率设置';
			$this->load->view ( 'admin/exchange_rate', $data );
		} else if ($sort == 'interface') {
			$this->load->model('paymentmodel');
			$pay = $this->paymentmodel->getAllPayment();
			$data=array('list'=>$pay,'title'=>'接口设置');
			$this->load->view ( 'admin/interface',$data );
		} else if ($sort == 'others') {
			if ($smtp = $this->input->post ( 'smtp' )) {
				$username = $this->input->post ( 'username' );
				$password = $this->input->post ( 'password' );
				$code = $this->input->post ( 'code' );
				$mail = "'" . str_replace ( ',', "','", $this->input->post ( 'mail' ) ) . "'";
				$notify = $this->input->post ( 'notify' ) ? 1 : 0;
				$cj = $this->input->post ( 'cj' ) ? 1 : 0;
				$rj = $this->input->post ( 'rj' ) ? 1 : 0;
				$min_amount = $this->input->post ( 'min_amount' ) + 0;
				$min_amount = $min_amount < 0 ? 0 : $min_amount;
				$max_amount = $this->input->post ( 'max_amount' ) + 0;
				$max_amount = $max_amount < 0 ? 0 : $max_amount;
				$zz = $this->input->post ( 'zz' ) ? 1 : 0;
				$rmb_account = $this->input->post ( 'rmb_account' ) ? $this->input->post ( 'rmb_account' ) : '';
				
				$str = <<<MAIL
<?php
return array(
'is_open'=>$notify,
'is_cj'=>$cj,
'is_rj'=>$rj,
'is_zz'=>$zz,
'min_amount'=>$min_amount,
'max_amount'=>$max_amount,
'rmb_account'=>'$rmb_account',
'email' => array (
		'username' => '$username',
		'password' => '$password',
		'host' => '$smtp',
		'charset' => '$code'
),
'list' => array (
		$mail
));
?>
MAIL;
				if (file_put_contents ( APPPATH . 'config/mail.php', $str )) {
					$msg = '设置成功！';
				} else {
					$msg = '设置失败';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' );
			} else {
				$data = require (APPPATH . 'config/mail.php');
				$data['title']='其它设置';
				$this->load->view ( 'admin/others', $data );
			}
		} else if ($sort == 'ip') {
			$this->load->model ( 'ipmodel' );
			$list = $this->ipmodel->getIp ();
			$data = array (
					'data' => $list ,
					'title'=>'IP设置',
			);
			$this->load->view ( 'admin/ip', $data );
		}
	}

	//系统个性化配置
	public function individual(){
		if ($de_time = $this->input->post('de_time')) {
			$ds_time = $this->input->post('ds_time') + 0;
			$ds_time_exp = trim($this->input->post('ds_time_exp'));

			$ws_time = $this->input->post('ws_time');
			$we_time = $this->input->post('we_time') + 0;
			$we_time_exp = trim($this->input->post('we_time_exp'));

			$wmast = $this->input->post('wmast') + 0;
			$wmapd = $this->input->post('wmapd') + 0;
			$code = trim($this->input->post('code'));
			$max_times = $this->input->post('max_times') + 0;

			$mail = trim($this->input->post('mail'));
			if (($ds_time_exp != "" && !preg_match('/^(\d+,?)+$/', $ds_time_exp)) || 
				($we_time_exp != "" && !preg_match('/^(\d+,?)+$/', $we_time_exp)) || 
				($code != "" && !preg_match('/^(\d+,?)+$/', $code)) || 
				($mail != "" && !preg_match('/^(\d+,?)+$/', $mail))) {
				$this->session->set_flashdata ( 'info', '账号非法，只能为半角逗号分隔的纯数字' );
				redirect ( '/admin/info/' );
			}

			$str = <<<MAIL
<?php defined('BASEPATH') OR exit('No direct script access allowed');
return array(
'deposit_time'=>array('start'=>$ds_time,'end'=>$de_time),
'deposit_time_exception'=>array($ds_time_exp),
'withdraw_time'=>array('start'=>$ws_time,'end'=>$we_time),
'withdraw_time_exception'=>array($we_time_exp),
'withdraw_max_amount_single_transaction'=>$wmast,
'withdraw_max_amount_per_day'=>$wmapd,
'withdraw_max_amount_exception'=>array($code),
'withdraw_blacklist'=>array($mail),
'withdraw_max_times_per_day'=>$max_times,
);
MAIL;
			if (file_put_contents ( APPPATH . 'config/deposit.php', $str )) {
				$msg = '设置成功！';
			} else {
				$msg = '设置失败';
			}
			$this->session->set_flashdata ( 'info', $msg );
			redirect ( '/admin/info/' );
		}

		$data = require (APPPATH . 'config/deposit.php');
		$data['title']='出入金设置';
		$this->load->view ( 'admin/individual', $data );
	}
	
	// 提示信息页面
	public function info($url = '') {
		$data['url'] = ($url === '' ? $url : str_replace('__rrgod__', '/', $url));
		$this->load->view ( 'admin/info' , $data);
	}
	
	// 静态页面
	public function view($page = 'about') {
		if ($page === 'about') {
			$this->load->view ( 'admin/pages/about.php' ,array('title'=>'关于'));
		} else if ($page === 'contact') {
			$this->load->view ( 'admin/pages/contact.php' ,array('title'=>'联系'));
		}
	}
	
	private function is_login() {
		if (isset ( $this->session->userdata ['username'] ) && isset ( $_SESSION ['admin'] ) && $_SESSION ['admin'] === 'admin') {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	//会员管理
	public function member($action='list',$param=''){
		if($action === 'del'){
			$_POST ['id'] = mysql_real_escape_string($_POST ['id']);
			$con = explode ( '#', $_POST ['id'] );
			//不允许删除admin
			if (in_array(1, $con)) {
				exit('0');
			}
			
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'usermodel' );
			if ($this->usermodel->del ( $condition )) {
				$this->writePrivateLog('删除会员成功' . $_POST['id']);
				echo '1';
			} else {
				$this->writePrivateLog('删除会员失败' . $_POST['id']);
				echo '0';
			}
		}else if($action === 'edit'){
			if (strlen($param)!==0 && is_numeric($param)) {
				$data = array ();
				$data['title']='编辑用户';
				$this->load->model ( 'usermodel' );
				$s = $this->usermodel->get_user($param);
				$mt4 = $this->usermodel->getMT4Account($param);
				if (!empty($s)) {
					$s['certificate'] = json_decode($s['certificate']);
					$data['user'] = $s;
					$data['mt4_account'] = $mt4;
				}else{
					$this->session->set_flashdata ( 'info', '获取会员信息失败或会员不存在' );
					redirect ( '/admin/info/' );
				}

				$this->load->model('ipmodel');
				$data['server'] = $this->ipmodel->getIp();
				$this->load->view('admin/edit_member.php',$data);
			}else{
				redirect('admin/member');
			}
		}else{
			$data = array ();
			$this->load->model ( 'usermodel' );
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
				
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
				
			// 生成查询条件
			$where = array ();
			$username = mysql_real_escape_string($this->input->get ( 'username' ));
			$email = mysql_real_escape_string($this->input->get ( 'email' ));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
				
			if ($username) {
				$where [] = " username like '%{$username}%' and ";
			}
			if ($email) {
				$where [] = " email like '%{$email}%' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " reg_time between '$start_time' and '$end_time' and ";
			}
				
			$condition = ' where ';
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
			} else {
				$condition = '';
			}
			$condition = rtrim ( $condition, 'and ' );
			$data ['itemsNum'] = $this->usermodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->usermodel->getPageItems ( $condition, $p, $pageSize );
				
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/member',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['title']='会员管理';
			$data['server']=$server;
			$this->load->view ( 'admin/member.php', $data );
		}
		//$this->load->view('admin/member');
	}
	public function mtSearch(){
		$mt4_account = $this->input->get ( 'mt_user' ) + 0;
		$server      = $this->input->get ( 'server' ) + 0;
		if ($mt4_account==0 || $server==0) {
			redirect('admin/member');
		}
		$this->load->model ( 'usermodel' );
		$result = $this->usermodel->getUserByMT4AccountAndServerId($mt4_account,$server);
		$this->load->model('ipmodel');
		$server=$this->ipmodel->getIp();
		$condition = ' where id in ( ';
		if (is_array($result) && !empty($result)) {
			foreach ($result as $v) {
				$p[]=$v['id'];
			}
			$tmp = implode(',', $p);
			$condition .= $tmp . ')';
		}else{
			$condition = 'where id = -1';
		}

		$list = array ();
		$pageSize = 20;
		$pageNow = 1;
		$pageCount = 0;
		$pn = $this->input->get ( 'pageNow' ) + 0;

		$data ['itemsNum'] = $this->usermodel->getItemsNum ( $condition ); // 总记录数
		$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
		if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
			$pageNow = $pn;
		}
		$p = ($pageNow - 1) * $pageSize;
		$data ['list'] = $this->usermodel->getPageItems ( $condition, $p, $pageSize );
			
		// 分页条
		$params = array (
				'totalCount' => $data ['itemsNum'],
				'pageSize' => $pageSize,
				'pageNavNum' => 10,
				'showGoto' => true,
				'showTotal' => true,
				'position' => 'right',
				'pageNow' => $pageNow,
				'url'=>'admin/mtSearch',
		);
		$data ['pageStr'] = $this->getPageStr ( $params );
		$data['title']='会员管理';
		$data['server']=$server;
		$this->load->view ( 'admin/member.php', $data );
	}
	// deposit
	public function deposit($action = 'list') {
		if ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'depositmodel' );
			if ($this->depositmodel->del ( $condition )) {
				$this->writePrivateLog('删除入金记录成功' . $_POST['id']);
				echo '1';
			} else {
				$this->writePrivateLog('删除入金记录失败' . $_POST['id']);
				echo '0';
			}
		} else {
			$data = array ();
			$this->load->model ( 'depositmodel' );
			
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
			
			// 生成查询条件
			$where = array ();
			$account = mysql_real_escape_string($this->input->get ( 'account' ));
			$order_id = mysql_real_escape_string($this->input->get ( 'order_id' ));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));

			//只显示支付成功的记录
			$is_ok = 1;

			if ($is_ok) {
				$where [] = " is_ok = 1 and ";
			}
			
			if ($account) {
				$where [] = " account like '%{$account}%' and ";
			}
			if ($order_id) {
				$where [] = " order_id like '%{$order_id}%' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " time between '$start_time' and '$end_time' and ";
			}
			
			$condition = ' where ';
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
			} else {
				$condition = '';
			}
			$condition = rtrim ( $condition, 'and ' );
			$data ['itemsNum'] = $this->depositmodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->depositmodel->getPageItems ( $condition, $p, $pageSize );
			$data ['sumDeposition'] = $this->depositmodel->getTotalAmount ();
			
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/deposit',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['title']='入金管理';
			$data['total'] = $this->depositmodel->getTotalAmount();
			$this->load->view ( 'admin/deposit.php', $data );
		}
	}
	
	// withdraw
	public function withdraw($action = 'list') {
		if ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'withdrawmodel' );
			if ($this->withdrawmodel->del ( $condition )) {
				$this->writePrivateLog('删除出金记录成功' . $_POST['id']);
				echo '1';
			} else {
				$this->writePrivateLog('删除出金记录失败' . $_POST['id']);
				echo '0';
			}
		} else if($action === 'change'){
			$this->load->model ( 'withdrawmodel' );
			$id = $this->input->post('id')+0;
			if(!$id){
				show_404();
			}
			$row = $this->withdrawmodel->changeHK($id);
			if (! $row) {
				exit ( 'Get data error' );
			}
			if ($row[0] ['params'] == '1') {
				echo '已汇款';
			} else {
				echo '未汇款';
			}
		}else {
			$data = array ();
			$this->load->model ( 'withdrawmodel' );
				
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
				
			// 生成查询条件
			$where = array ();
			$ch_name = mysql_real_escape_string($this->input->get ( 'ch_name' ));
			$account = mysql_real_escape_string($this->input->get ( 'account' ));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
				
			if ($account) {
				$where [] = " account like '%{$account}%' and ";
			}
			if ($ch_name) {
				$where [] = " name like '%{$ch_name}%' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " time between '$start_time' and '$end_time' and ";
			}
				
			$condition = ' where ';
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
			} else {
				$condition = '';
			}
			$condition = rtrim ( $condition, 'and ' );
			$data ['itemsNum'] = $this->withdrawmodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->withdrawmodel->getPageItems ( $condition, $p, $pageSize );
				
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/withdraw',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['title']='出金管理';
			$data['total'] = $this->withdrawmodel->getTotalRMB();
			$this->load->view ( 'admin/withdraw.php' ,$data);
		}
	}
	
	// transfer
	public function transfer($action = 'list') {
		if ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'transfermodel' );
			if ($this->transfermodel->del ( $condition )) {
				$this->writePrivateLog('删除转账记录成功' . $_POST['id']);
				echo '1';
			} else {
				$this->writePrivateLog('删除转账记录失败' . $_POST['id']);
				echo '0';
			}
		} else {
			$data = array ();
			$this->load->model ( 'transfermodel' );
				
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
				
			// 生成查询条件
			$where = array ();
			$account_from = mysql_real_escape_string($this->input->get ( 'account_from' ));
			$account_to = mysql_real_escape_string($this->input->get ( 'account_to' ));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
				
			if ($account_from) {
				$where [] = " transfer_from like '%{$account_from}%' and ";
			}
			if ($account_to) {
				$where [] = " transfer_to like '%{$account_to}%' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " time between '$start_time' and '$end_time' and ";
			}
				
			$condition = ' where ';
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
			} else {
				$condition = '';
			}
			$condition = rtrim ( $condition, 'and ' );
			$data ['itemsNum'] = $this->transfermodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->transfermodel->getPageItems ( $condition, $p, $pageSize );
				
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/transfer',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['title']='内部转账';
			$this->load->view ( 'admin/transfer.php', $data );
		}
	}
	
	// logs
	public function logs($action = 'list') {
		if ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'logsmodel' );
			if ($this->logsmodel->del ( $condition )) {
				$this->writePrivateLog('删除系统日志成功' . $_POST['id']);
				echo '1';
			} else {
				$this->writePrivateLog('删除系统日志失败' . $_POST['id']);
				echo '0';
			}
		} else {
			$data = array ();
			$this->load->model ( 'logsmodel' );
				
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
				
			// 生成查询条件
			$where = array ();
			$type = mysql_real_escape_string($this->input->get ( 'type' ));
			$content = mysql_real_escape_string($this->input->get ( 'content' ));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
			if ($type != false) {
				$where [] = " type = '{$type}' and ";
			}
			if ($content) {
				$where [] = " content like '%{$content}%' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " time between '$start_time' and '$end_time' and ";
			}
				
			$condition = ' where ';
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
			} else {
				$condition = '';
			}
			$condition = rtrim ( $condition, 'and ' );
			$data ['itemsNum'] = $this->logsmodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->logsmodel->getPageItems ( $condition, $p, $pageSize );
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/logs',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['title']='系统日志';
			$this->load->view ( 'admin/logs.php', $data );
		}
	}

	//真实账户申请记录查看
	public function real($id=''){
		$this->load->model('realaccountmodel');
		if($id===''){
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' )) + 0;
			$pageSize = 20;
			$pageNow = 1;
			$condition = '';
			$data ['itemsNum'] = $this->realaccountmodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->realaccountmodel->getPageItems ( $condition, $p, $pageSize );
				
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'admin/real',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );

			//$data['list']=$this->realaccountmodel->getAll();
			$data['title']='真实账户申请记录';
			$this->load->view('admin/real',$data);
		}elseif(is_numeric($id)){
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
			$data['list']=$this->realaccountmodel->getById($id);
			$user_id = $data['list']['user_id'];
			if ($user_id != 0) {
				$this->load->model('usermodel');
				$result = $this->usermodel->get_user_certificate($user_id);
				$data['cer'] = json_decode($result);
			}
			$data['title']='账户详情';
			$data['server']=$server;
			$this->load->view('admin/real_more',$data);
		}

	}

	//模拟账户
	public function demo(){
		$this->load->model('demoaccountmodel');
		$data['list'] = $this->demoaccountmodel->getAll();
		$data['title']='账户详情';
		$this->load->view('admin/demo',$data);
	}
	// server
	public function server($action = 'list') {
		if ($action === 'add') {
			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'server_name', '服务器名称', 'required' );
			$this->form_validation->set_rules ( 'ip', 'IP地址', 'required' );
			$this->form_validation->set_rules ( 'port', 'MT4服务器端口号', 'required' );
			$this->form_validation->set_rules ( 'local_port', '本地通讯端口号', 'required' );
			
			if ($this->form_validation->run () !== FALSE) {
				$server_name = trim($this->input->post ( 'server_name' ));
				$ip = trim($this->input->post ( 'ip' ));
				$port = trim($this->input->post ( 'port' ))+0;
				$local_port = trim($this->input->post ( 'local_port' ))+0;
				$WebTraderPort = trim($this->input->post ( 'WebTraderPort' ))+0;
				$data = array (
						'name' => $server_name,
						'ip' => $ip,
						'port' => $port ,
						'local_port'=>$local_port,
						'params'=>$WebTraderPort,
				);
				$this->load->model ( 'ipmodel' );
				$exist = $this->ipmodel->is_exist($local_port);
				if($exist === TRUE){
					$this->session->set_flashdata ( 'info', '添加失败：本地端口号' . $local_port . '已存在，不能重复' );
					redirect ( '/admin/info/' );
				}
				$li = $this->ipmodel->getIp();
				if(empty($li)){
					$data['is_chief']=1;
				}
				if ($this->ipmodel->addIp ( $data )) {
					$msg = '添加成功！';
					if (isset($_SESSION['all_server'])) {
						unset($_SESSION['all_server']);
					}
				} else {
					$msg = '添加失败！';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' );
			}else{
				$this->load->model ( 'ipmodel' );
				$list = $this->ipmodel->getIp ();
				$data = array (
						'data' => $list ,
						'title'=>'IP设置',
				);
				$this->load->view ( 'admin/ip', $data );
			}
		} elseif ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			// $table = trim ( $_POST ['tb'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'ipmodel' );
			if ($this->ipmodel->delIp ( $condition )) {
				echo '1';
			} else {
				echo '0';
			}
		}
	}

	/*
	ajax查询接口
	 */
	/**
	 *  校验用户密码
	 */
	public function checkPass(){
		$username=trim($this->input->post('username'));
		$password=trim($this->input->post('password'));
		if (empty($username) || empty($password) || strlen($password) < 6) {
			$result = -1;
		}else{
			$this->load->model('usermodel');
			$result = $this->usermodel->verify_user($username,$password);
			if ($result === FALSE) {
				$result = 0;
			}else{
				$result = 1;
			}
		}

		echo json_encode(array('result'=>$result));
	}

	/**
	 * 重置用户密码
	 */
	public function resetPass(){
		$username=trim($this->input->post('username'));
		if (empty($username)) {
			$result = -1;
		}else{
			$this->load->model('usermodel');
			$result = $this->usermodel->reset_pwd($username,'123456',FALSE);
			if ($result == FALSE) {
				$result = 0;
			}else{
				$result = 1;
				$this->writePrivateLog('重置用户密码成功-' . $username);
			}
		}

		echo json_encode(array('result'=>$result));
	}

	/**
	 * 解除mt4绑定
	 */
	public function removeMTAccount(){
		$userid=trim($this->input->post('userid'));
		$mt4_account=trim($this->input->post('mtacc'));
		if (empty($userid) || empty($mt4_account) || !is_numeric($userid) || !is_numeric($mt4_account)) {
			$result = -1;
		}else{
			$this->load->model('usermodel');
			$result = $this->usermodel->delMT4AccountByUserId($userid,$mt4_account);
			if ($result === 0) {
				$result = 0;
			}else{
				$result = 1;
				$this->writePrivateLog('解除绑定mt4账户成功-' . $userid . '-' . $mt4_account);
			}
		}

		echo json_encode(array('result'=>$result));
	}

	public function data($action=''){
		if ($action === 'save') {
			$this->load->dbutil();
			$backup = & $this->dbutil->backup(array(
				'format'=>'zip',
				'filename'=>'backup.zip',
				//'ignore'=>array('yyy_private_log','yyy_logs'),
				));

			$this->load->helper('file');
			$backup_file = './uploads/' . date('Y-m-d-H-i-s') . '_backup.zip';
			write_file($backup_file,$backup);

			$this->load->helper('download');
			force_download('backup.zip',$backup);
		}
		$data = array('title'=>'数据备份');
		$this->load->view('admin/data',$data);
	}

	public function top($time = 'a'){
		$this->load->model('depositmodel');
		$d_data = $this->depositmodel->getDepositTopList(0,$time);

		$this->load->model('withdrawmodel');
		$w_data = $this->withdrawmodel->getWithdrawTopList(0,$time);

		$diff_data = array();
		foreach ($d_data as $d) {
			$diff_data[$d['username']] = $d['g_amount'];
			foreach ($w_data as $w) {
				if ($d['user_id'] == $w['user_id']) {
					$diff_data[$d['username']] = $d['g_amount'] - $w['g_amount'];
					break;
				}
			}
		}
		arsort($diff_data);

		$data = array('title'=>'排行榜',
			'd_data'=>array_slice($d_data,0,10,TRUE),
			'w_data'=>array_slice($w_data,0,10,TRUE),
			'diff_data'=>array_slice($diff_data,0,10,TRUE));
		$this->load->view('admin/top',$data);
	}

	public function agent($action = 'list'){
		if ($action === 'add') {
			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'server_name', '代理名称', 'required' );
			$this->form_validation->set_rules ( 'ip', '代理ID', 'required' );
			$this->form_validation->set_rules ( 'port', '组名称', 'required' );
			
			if ($this->form_validation->run () !== FALSE) {
				$server_name = trim($this->input->post ( 'server_name' ));
				$ip = trim($this->input->post ( 'ip' )) + 0;
				$port = trim($this->input->post ( 'port' ));
				$local_port = trim($this->input->post ( 'local_port' ));
				$data = array (
						'agent_name' => $server_name,
						'agent_id' => $ip,
						'group_name' => $port ,
						'comment'=>$local_port,
				);
				$clean_data = array_map('mysql_real_escape_string', $data);
				$this->load->model ( 'agentmodel' );
				$exist = $this->agentmodel->is_exist($ip);
				if($exist === TRUE){
					$this->session->set_flashdata ( 'info', '添加失败：代理ID' . $ip . '已存在，不能重复' );
					redirect ( '/admin/info/' );
				}
				if ($this->agentmodel->addAgent ( $clean_data )) {
					$msg = '添加成功！';
				} else {
					$msg = '添加失败！';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' );
			}else{
				$this->load->model ( 'agentmodel' );
				$list = $this->agentmodel->getAgent ();
				$data = array (
						'data' => $list ,
						'title'=>'代理设置',
				);
				$this->load->view ( 'admin/agent', $data );
			}
		} elseif ($action === 'del') {
			$_POST['id']=mysql_real_escape_string($_POST['id']);
			$con = explode ( '#', $_POST ['id'] );
			$condition = ' (';
			foreach ( $con as $v ) {
				if ($v !== '') {
					$condition .= $v . ',';
				}
			}
			$condition = rtrim ( $condition, ',' );
			$condition .= ') ';
			$this->load->model ( 'agentmodel' );
			if ($this->agentmodel->delAgent ( $condition )) {
				echo '1';
			} else {
				echo '0';
			}
		}else{
			$this->load->model ( 'agentmodel' );
			$list = $this->agentmodel->getAgent ();
			$data = array('title'=>'代理设置',
				'data'=>$list,
				);
			$this->load->view('admin/agent',$data);
		}
	}

	public function openMt4Acc(){
		array_map('mysql_real_escape_string',$_POST);
		$password = substr(str_shuffle('23456789'), 0, 3) .
			substr(str_shuffle('abcdefghgkmnpqrstuvwxyz'), 0, 3);
		$password_investor = substr(str_shuffle('23456789'), 0, 3) .
			substr(str_shuffle('abcdefghgkmnpqrstuvwxyz'), 0, 3);
		$password_phone = substr(str_shuffle('23456789'), 0, 3) .
			substr(str_shuffle('abcdefghgkmnpqrstuvwxyz'), 0, 3);

		$mt4_acc = trim($this->input->post('mt4_acc')) + 0;
		$agent_id = trim($this->input->post('agent_acc')) + 0;
		$group_name = trim($this->input->post('group_name'));
		$level = trim($this->input->post('leverage')) + 0;
		$server = trim($this->input->post('server'));
		$local_port = trim($this->input->post('server')) + 0;
		$user_id = trim($this->input->post('user_id')) + 0;

		$real_params['group'] = iconv ('utf-8','gbk',$group_name);
		$real_params['agent'] = (($agent_id == 0) ? '' : $agent_id);
		$real_params['login'] = (($mt4_acc == 0) ? 0 : $mt4_acc);
		$real_params['country'] = iconv ('utf-8','gbk',trim($this->input->post('country')));
		$real_params['state'] = iconv ('utf-8','gbk',trim($this->input->post('state')));
		$real_params['city'] = iconv ('utf-8','gbk',trim($this->input->post('city')));
		$real_params['address'] = iconv ('utf-8','gbk',trim($this->input->post('address')));
		$real_params['name'] = iconv ('utf-8','gbk',trim($this->input->post('username')));
		$real_params['email'] = iconv ('utf-8','gbk',trim($this->input->post('email')));
		$real_params['password'] = iconv ('utf-8','gbk',$password);
		$real_params['password_investor'] = iconv ('utf-8','gbk',$password_investor);
		$real_params['password_phone'] = iconv ('utf-8','gbk',$password_phone);
		$real_params['leverage'] = iconv ('utf-8','gbk',$level);
		$real_params['zipcode'] = iconv ('utf-8','gbk','');
		$real_params['phone'] = iconv ('utf-8','gbk',trim($this->input->post('phone')));
		$real_params['comment'] = iconv ('utf-8','gbk',trim($this->input->post('comment')));
		$real_params['status'] = 'RE';
		$real_params['id'] = iconv ('utf-8','gbk',trim($this->input->post('id_num')));

		$this->load->model('usermodel');
		$demodata = $this->usermodel->createMT4Account($real_params,$local_port);
		$result = $this->parseMT4Answer($demodata);
		if ( isset($result['success']) && $result['success']===TRUE) {
			$result['password'] = $password;
			$result['password_investor'] = $password_investor;
		}
		$this->load->model('realaccountmodel');
		if(!empty($result['login']) && !($this->realaccountmodel->updateMt4Account($result['login'],$user_id))){
			$result['update_result'] = FALSE;
		}

		echo json_encode($result);
	}

	public function getGroups(){
		$local_port = trim($this->input->post('server')) + 0;
		$gData = array();
		if ($local_port !== 0) {
			$this->load->model('usermodel');
			$data = $this->usermodel->getServerGroups($local_port);
			$result = $this->parseMT4Answer($data);
			if ( isset($result['success']) && $result['success']===TRUE) {
				$rdata = explode("\n", $result['size']);
				foreach ($rdata as $v) {
					$sv = explode(";", $v);
					if (isset($sv[1]) && $sv[1] == 1) {
						$gData[] = $sv;
					}
				}
			} else {
				$gData['error_msg']='get group data error' . (isset($result['reason']) ? $result['reason'] : "");
			}
		} else {
			$gData['error_msg'] = 'invalid server';
		}

		echo json_encode($gData);
	}

	public function message($action='list',$id = 0){
		$data = array('title'=>'系统公告');
		$this->load->model('messagemodel');
		if ($action === 'list') {
			if ($id === 0) {
				//所有公告
				$pn = mysql_real_escape_string($this->input->get ( 'pageNow' )) + 0;
				$pageSize = 20;
				$pageNow = 1;
				$condition = '';
				$data ['itemsNum'] = $this->messagemodel->getItemsNum ( $condition ); // 总记录数
				$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
				if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
					$pageNow = $pn;
				}
				$p = ($pageNow - 1) * $pageSize;
				$data ['list'] = $this->messagemodel->getPageItems ( $condition, $p, $pageSize );
					
				// 分页条
				$params = array (
						'totalCount' => $data ['itemsNum'],
						'pageSize' => $pageSize,
						'pageNavNum' => 10,
						'showGoto' => true,
						'showTotal' => true,
						'position' => 'right',
						'pageNow' => $pageNow,
						'url'=>'admin/message/list',
				);
				$data ['pageStr'] = $this->getPageStr ( $params );
				//公告列表页面
				$this->load->view('admin/messagelist', $data);
			} else {
				//单条公告展示
				$id += 0;
				$id === 0 && $id = 1;
				$data['message'] = $this->messagemodel->getMessage($id);
				$this->load->view('admin/messagesingle', $data);
			}
		} elseif ($action === 'add') {
			if (!empty($_POST['title'])) {
				//处理公告发布请求
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title','标题','trim|required|max_length[255]');
				$this->form_validation->set_rules('content','内容','trim|required');

				if ($this->form_validation->run() !== FALSE) {
					$title = $this->input->post('title');
					$content = $this->input->post('content');

					$time = date('Y-m-d-H-i-s');
					$messageData = array(
						'id'=>'',
						'title'=>$title,
						'content'=>$content,
						'creator'=>$this->session->userdata('username'),
						'addTime'=>$time,
						'modifyTime'=>$time,
						'status'=>0,
						); 
					if ($this->messagemodel->addMessage($messageData)) {
						$msg = '发布成功';
					} else {
						$msg = '发布失败';
					}

					$this->session->set_flashdata ( 'info', $msg );
					redirect ( '/admin/info/' . str_replace('/', '__rrgod__', site_url('admin/message')) );
				}
			}
			//发布公告表单页面
			$data['title'] = '发布公告';
			$this->load->view('admin/message', $data);
		} elseif ($action === 'delete'){
			//删除
			$id += 0;
			if ($id) {
				if($this->messagemodel->deleteMessage($id)){
					$msg = '删除成功';
				} else {
					$msg = '删除失败';
				}
			} else {
				$msg = "参数非法，删除失败";
			}

			$this->session->set_flashdata ( 'info', $msg );
			redirect ( '/admin/info/' );
		} elseif ($action === 'recover'){
			//删除
			$id += 0;
			if ($id) {
				if($this->messagemodel->recoverMessage($id)){
					$msg = '恢复成功';
				} else {
					$msg = '恢复失败';
				}
			} else {
				$msg = "参数非法，恢复失败";
			}

			$this->session->set_flashdata ( 'info', $msg );
			redirect ( '/admin/info/' );
		} elseif ($action === 'edit') {
			//编辑
			if (!empty($_POST['message_id'])) {
				//处理编辑保存
				$id = $this->input->post('message_id') + 0;
				if ($id) {
					$title = $this->input->post('title');
					$content = $this->input->post('content');

					$time = date('Y-m-d-H-i-s');
					$messageData = array(
						'title'=>$title,
						'content'=>$content,
						'creator'=>$this->session->userdata('username'),
						'modifyTime'=>$time,
						);
					if($this->messagemodel->updateMessage($id,$messageData)) {
						$msg = '保存成功';
					} else {
						$msg = '保存失败';
					}
				} else {
					$msg = '参数非法，保存失败';
				}

				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/admin/info/' . str_replace('/', '__rrgod__', site_url('admin/message')));
			} else {
				$id += 0;
				if ($id) {
					$data['message'] = $this->messagemodel->getMessage($id);
				} else {
					$msg = '参数非法';
					$this->session->set_flashdata ( 'info', $msg );
					redirect ( '/admin/info/' );
				}
			}

			$data['title'] = '编辑公告';
			$this->load->view('admin/messageedit', $data);
		}
	}

	public function dataExport($type,$info) {
		$this->load->library('myexcel');
		if ($type === 'withdraw') {
			$title = array('序号','银行','地区（省）','地区（市/区）','支行名','开户名','卡号','金额');
			$this->load->model ( 'withdrawmodel' );
			if ($info === 'notpay') {
				$data = $this->withdrawmodel->getOutputData(' where params = 0 ',
					array('area','bank_name','name','bank_code','rmb'));
			} elseif ($info === 'all') {
				$data = $this->withdrawmodel->getOutputData('',
					array('area','bank_name','name','bank_code','rmb'));
			} else {
				$this->session->set_flashdata ( 'info', '参数无效' );
				redirect ( '/admin/info/' );
			}

			$inputData = array();
			$indexNum = 1;
			foreach ($data as $k => $v) {
				foreach ($v as $kk => $vv) {
					if ($kk === 'area') {
						$p = mb_substr($vv, 2);
						$province = mb_substr($p, 0,3);
						if ( $province === '黑龙江' || $province === '内蒙古') {
							$city = mb_substr($p, 3);
						} else {
							$province = mb_substr($p, 0,2);
							$city = mb_substr($p, 2);
						}
						$inputData[$k][] = $province;
						$inputData[$k][] = $city;
					} elseif ($kk === 'bank_name') {
						$inputData[$k][] = $vv;
						$bank = '';
						$pos = mb_strpos($vv, '银行');
						if($pos !== false) {
							$bank = mb_substr($vv, 0, $pos + 2);
						}
						array_unshift($inputData[$k], $bank);
					} else {
						$inputData[$k][] = $vv;
					}
				}
				array_unshift($inputData[$k], $indexNum);
				$indexNum++;
			}

			$this->myexcel->exportDataToExcel('withdrawData.xls',$inputData,$title);
		} elseif ($type === 'deposit') {
			$title = array('序号','交易帐号','姓名','金额','交易号','订单号','时间');
			$this->load->model ( 'depositmodel' );
			if ($info === 'all') {
				$data = $this->depositmodel->getOutputData(' where is_ok = 1 ',
					array('account','name','amount','order_id','my_id','time'));
				if (empty($data)) {
					$this->session->set_flashdata ( 'info', '无数据或获取数据失败' );
					redirect ( '/admin/info/' );
				}
			} else {
				$this->session->set_flashdata ( 'info', '参数无效' );
				redirect ( '/admin/info/' );
			}

			$inputData = array();
			$indexNum = 1;
			foreach ($data as $k => $v) {
				foreach ($v as $vv) {
					$inputData[$k][] = $vv;
				}
				array_unshift($inputData[$k], $indexNum);
				$indexNum++;
			}

			$this->myexcel->exportDataToExcel('depositData.xls',$inputData,$title);
		} else {
			$this->session->set_flashdata ( 'info', '参数无效' );
			redirect ( '/admin/info/' );
		}
	}

	public function mt4Add(){
		$username = $this->input->post('mt4_acc') + 0;
		$password = mysql_real_escape_string($this->input->post('mt4_pass'));
		$server_id = $this->input->post('server') + 0;
		$user_id = $this->input->post('user_id') + 0;

		$result = array(
			'success'=>FALSE,
			'reason'=>'');
		if ($username == 0 || strlen($password) < 5 || $server_id == 0 || $user_id == 0) {
			$result['reason'] = '参数非法';
		} else {
			$this->load->model('usermodel');
			$this->load->model('ipmodel');
			$sv = $this->ipmodel->getIp($server_id);
			$local_port = $sv['local_port'] + 0;
			if ($local_port === 0) {
				$result['reason'] = '获取服务器信息失败';
				echo json_encode($result);
				exit();
			}

			if(!$this->usermodel->is_demo($username, $password,$local_port)){
				if($this->usermodel->checkMT4Exist($username,$server_id)===TRUE){
					$result['reason'] = '该MT4账户已被绑定，请先解除绑定再进行绑定';
				}else{
					$data=$this->usermodel->addMT4Account($username,$password,
						$local_port,$server_id,$user_id);
					$presult = $this->parseMT4Answer($data);
					if ($presult['success']===TRUE) {
						$result['success'] = TRUE;
						$result['account'] = $username;
						$result['server_name'] = $sv['name'];
					}else{
						$result['reason'] = isset($presult['reason']) ? $presult['reason'] : '未知错误';
					}
				}
			}else {
				$result['reason'] = '不支持demo帐号';
			}
		}

		echo json_encode($result);
	}

	//解析通讯结果
	private function parseMT4Answer($data){
		if ($data[0][0] === 'error') {
			$result['success'] =FALSE;
			$result['reason'] ='connection error';
			return $result;
		}

		$result = array();
		foreach ($data as $v){
			if ($v[0]==='result' && $v[1]==='1') {
				$result['success'] =TRUE;
			}else{
				if ($v[0]==='reason'){
					$result['success']=FALSE;
					$result['reason']=$v[1];
				}else{
					$result[$v[0]]=$v[1];
				}
			} 
		}
		return $result;
	}
	
	// 生成分页导航条
	private function getPageStr($params) {
		$queryStr = '';
		if (count ( $_GET ) > 1) {
			if (isset ( $_GET ['pageNow'] )) {
				unset ( $_GET ['pageNow'] );
			}
			foreach ( $_GET as $v => $k ) {
				$queryStr .= $v . '=' . urlencode ( $k ) . '&';
			}
			$pageUrl = site_url ($params['url'] ) . '/?' . $queryStr . 'pageNow=';
		} else {
			$pageUrl = site_url ($params['url'] ) . '/?pageNow=';
		}
		$params ['pageUrl'] = $pageUrl;
		$this->load->library ( 'pagination', $params );
		$this->pagination->curPageNum = $params ['pageNow'];
		return $this->pagination->generatePageNav ();
	}

	private function array_php2js(Array $data,$type='x') {
		if (empty($data)) {
			return '[]';
		}

		$s = array();
		foreach ($data as $k => $v) {
			if ($type==='x') {
				$s[]= "'" . $v['g_time'] . "'";
			}else{
				$s[]= $v['g_amount'];
			}
		}
		return '[' . implode(',', $s) . ']';
	}

	/**
	 * 写入私有日志
	 */
	private function writePrivateLog($type){
		$this->load->model('privatelogmodel');
		$this->load->library('clientinfo');
		$isp = $this->clientinfo->getISPInfo($this->session->userdata['ip_address']);
		$p_content = $type . ' | UserAgent: ' . $this->clientinfo->getClientUserAgent() .
				' | ISP: ' . $isp;
		$this->privatelogmodel->add($this->session->userdata('username'),
		$this->session->userdata['ip_address'],$p_content);
	}
}
?>