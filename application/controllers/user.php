<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-2
*/
class User extends MY_Controller {
	public function __construct(){
		//session_start();
		parent::__construct();
		//header('Content-Type:text/html;charset=utf-8');
		//$this->output->enable_profiler(TRUE);
	}
	//是否登录
	private function is_login() {
		if (isset ( $this->session->userdata ['username'] ) && isset($_SESSION['login'])) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function index() {
		$this->load->model ( 'exchange_rate_model' );
		$rs = $this->exchange_rate_model->get_exchange_rate ();
		if ($rs === 1) {
			$data ['msg'] = '还未设置汇率！';
		} else if ($rs === FALSE) {
			$data ['msg'] = '获取汇率设置有误！';
		} else {
			$data = $rs [0];
		}

		$this->load->model('messagemodel');
		$latestMessage = $this->messagemodel->getLatestMessage(1);
		if (!empty($latestMessage)) {
			$data->latestMessage = $latestMessage;
		}
		$data->title = '首页';
		$this->load->view('index',$data);
	}
	//密码找回
	public function getpwd(){
		$email = $this->input->post('email');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','trim|required');
		$this->form_validation->set_rules('captcha_f','验证码','trim|required|callback_vcode_check');
		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata ( 'info','验证码输入错误' );
			redirect('user/info');
		}

		$this->load->library('myredis');
		$key = $email . '|getpwd';
		if (!$this->myredis->multiTry($key,15,2)) {
			$this->session->set_flashdata ( 'info','同一用户15分钟内最多只能允许找回2次密码，请稍后再试。' );
			redirect('user/info');
		}

		//是否存在
		$this->load->model('usermodel');
		$result = $this->usermodel->check_user_exist('',$email);
		if ($result['email'] === FALSE) {
			$this->session->set_flashdata ( 'info', $email . '不存在' );
			redirect ( 'user/info' );
		}
		
		$this->load->model('usermodel');
		$username = $this->usermodel->get_username($email);
		$username = $username['username'];
		$time = date('Y-m-d H:i:s');

		if ($result['email']) {
			$pkey = substr(sha1($time),0,5) . md5($username) . mt_rand(1,100000) . sha1($email) . mt_rand(1,100000);
			$resetUrl = site_url('common/pwdReset') . '/' . $pkey;

			$this->load->model('resetmodel');
			$data = array('email'=>$email,'pkey'=>$pkey,'time'=>$time,'is_reset'=>0);
			if(!$this->resetmodel->add($data)){
				$this->session->set_flashdata ( 'info','重置密码失败' );
				redirect ( 'user/info' );
			}
			//邮件通知
			$my_config = require (APPPATH . 'config/mail.php');
			$this->load->library('email');
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $my_config['email']['host'];
			$config['smtp_user'] = $my_config['email']['username'];
			$config['smtp_pass'] = $my_config['email']['password'];
			$config['smtp_port'] = 25;
			$config['charset'] =$my_config['email']['charset'];
			$config['crlf'] ="\r\n";
			$config['newline']="\r\n";
			$config['wordwrap'] = TRUE;
			$config['wrapchars'] = 100;			
			$this->email->initialize($config);
			
			$this->email->from($my_config['email']['username'], 'Admin');
			$this->email->to($email);
			
			$subject = '来自' . $this->config->item('company_name') . '的密码重置通知';
			$content = "{$username}, 您好！\r\n您在{$time}重置了登陆密码，请点击下面的链接重置密码。\r\n注意：本链接只能提交一次，重置后的新密码会显示在提交后的页面上并邮件告知新密码，请注意查看。\r\n{$resetUrl}\r\n如果浏览器不能自动打开，请把地址复制到浏览器中手动打开。";
			$this->email->subject($subject);
			$this->email->message($content);
			
			$this->email->send();

			$this->session->set_flashdata ( 'info','重置密码成功！请登陆您的邮箱' . $email . '完成密码重置操作！' );
			redirect ( 'user/info' );
		}else{
			$this->session->set_flashdata ( 'info','重置密码失败' );
			redirect ( 'user/info' );
		}

	}

	/**
	 * 文件上传
	 */
	public function upload(){
		$data['title'] = '证件资料上传';
		if (!empty($_POST)) {
			$flag = true;
			$path = array();
			$config['upload_path'] = './uploads/' . date('Ymd') . '/';
			$config['allowed_types'] = 'jpg|jpeg|gif|bmp';
			$config['max_size']=512;
			$config['encrypt_name']=TRUE;

			if(!is_dir($config['upload_path'])){
				if(!mkdir($config['upload_path'])){
					$this->session->set_flashdata ( 'info', '目录创建失败！' );
					redirect ( '/user/info/' );
				}
			}
			$this->load->library('upload',$config);

			foreach ($_FILES as $key => $value) {
				if(!$this->upload->do_upload($key)){
					$flag = false;
					break;
				}else{
					$data = $this->upload->data();
					$path[$key] = str_replace(str_replace("\\", '/', FCPATH),'',$data['full_path']);
				}
			}

			if(!$flag){
				$this->session->set_flashdata ( 'info', '上传失败！失败原因：' . $this->upload->display_errors() );
				redirect ( '/user/info/' );
			}else{
				$certificate = json_encode($path);
				$this->load->model('usermodel');
				$result = $this->usermodel->update_user_certificate($this->session->userdata('user_id'),$certificate);
				$this->session->set_flashdata ( 'info', '上传成功！' );
				redirect ( '/user/info/' );
			}
		}else{
			$this->load->model('usermodel');
			$cer = $this->usermodel->get_user_certificate($this->session->userdata('user_id'));
			if ($cer !== FALSE) {
				$data['cer'] = json_decode($cer);
			}
			$this->load->view('fileupload', $data);
		}
	}

	//登陆
	public function login($regname=''){
		//已登录则直接跳转至首页
		if ($this->is_login()) {
			redirect('user/index');
		}

		$this->load->model('ipmodel');
		$server=$this->ipmodel->getIp();
		$data = array(
			'server'=>$server,
			'title'=>'用户登录',
			'vcode'=>$this->generate_vcode(),
			'regname'=>urldecode($regname),
			);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','帐号','trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('password','密码','trim|required|min_length[6]|max_length[20]');
		
		if ($this->form_validation->run() !== FALSE) {
			$username=trim($this->input->post('username'));
			$password=trim($this->input->post('password'));
			$this->load->model('usermodel');
			$result = $this->usermodel->verify_user($username,$password);
			if ($result === FALSE){
				$data['error']='帐号/密码无效！';
				//private log
				$this->writePrivateLog($username . ' 会员登陆失败。尝试密码：' . $password);
				$this->load->view('login',$data);
			}else{
				$this->session->set_userdata('username',$this->input->post('username'));
				//private log
				$this->writePrivateLog($username . ' 会员登陆成功');
				redirect('user');
			}
		}else{
			if ($this->config->item('only_allow_mt4_login') === TRUE) {
				$this->load->view('mt4_login',$data);
			} else {
				$this->load->view('login',$data);
			}
		}
	}
	public function mt4login(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('mt4_username','帐号','trim|required|integer');
		$this->form_validation->set_rules('mt4_password','密码','trim|required|min_length[6]');
		$this->form_validation->set_rules('captcha','验证码','trim|required|callback_vcode_check');
		if ($this->form_validation->run() !== FALSE) {
			$username=trim($this->input->post('mt4_username'));
			$password=trim($this->input->post('mt4_password'));
			$server=trim($this->input->post('server'));
			$this->load->model('usermodel');
			//检查MT4帐号是否有效
			if($this->usermodel->is_valid_mt4account($username,$password,$server)){
				if ($this->usermodel->is_demo($username,$password,$server)) {
					$this->session->set_flashdata ( 'info', '不支持demo帐号' );
					redirect ( '/user/info/' );
				}
				//检查是否已关联注册帐号
				$result = $this->usermodel->check_user_mt4account($username,$server);
				if ($result !== FALSE) {
					$this->session->set_userdata('username',$result);
					$_SESSION['login']=TRUE;
					//private log
					$this->writePrivateLog($username . ' MT4登陆成功');
					redirect('user');
				}else{
					$this->session->set_userdata('mt4_account',$username);
					$this->session->set_userdata('mt4_account_pwd',$password);
					$this->session->set_userdata('server',$server);
					$data['title']='关联帐号';
					$this->load->view('relevance',$data);
				}
			}else{
				//private log
				$this->writePrivateLog($username . ' MT4登陆失败。尝试密码：' . $password);
				$this->session->set_flashdata ( 'info', '帐号/密码无效！' );
				redirect ( '/user/info/' );
			}
		}else{
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
			$data = array(
				'server'=>$server,
				'title'=>'用户登录',
				'vcode'=>$this->generate_vcode(),
				);
			if ($this->config->item('only_allow_mt4_login') === TRUE) {
				$this->load->view('mt4_login',$data);
			} else {
				$this->load->view('login',$data);
			}
		}
	}
	/**
	 * 关联帐号
	 */
	public function relevance()
	{
		$is_reg = trim($this->input->post('is_reg'));
		$email=trim($this->input->post('email'));
		$password=trim($this->input->post('password'));
		$username = $this->session->userdata('mt4_account') . '_' . mt_rand(1,10000);
		$rel_mt4_account = $this->session->userdata('mt4_account') . substr(md5($this->session->userdata('server')),0,6);
		$this->load->model('usermodel');
		//已注册，关联已有帐号
		if ($is_reg === 'yes') {
			if ($this->usermodel->verify_user_by_email($email,$password)) {
				if($this->usermodel->update_user_mt4account($email,$rel_mt4_account)){
					//*****自动绑定MT4账户*********
					$this->load->model('ipmodel');
					$tmpdata = $this->ipmodel->getServerByLocalPort($this->session->userdata('server'));
					$server_id = $tmpdata['id'];
					if($this->usermodel->checkMT4Exist(
						$this->session->userdata('mt4_account'),$server_id)===FALSE){
						$data=$this->usermodel->addMT4Account($this->session->userdata('mt4_account'),
							$this->session->userdata('mt4_account_pwd'),$this->session->userdata('server'),$server_id);
						$result = $this->parseMT4Answer($data);
						if ($result['success']!==TRUE){
							$msg = 'MT4账户自动绑定失败：' . $result['reason'] . '。请点击下面的返回链接手动绑定MT4账户。';
							$this->session->set_flashdata ( array('info'=>$msg,'return_url'=>site_url('user/mt4')));
							redirect ( '/user/result/' );
						} else {
							redirect('user');
						}
					}
					//*****************************
					redirect('user');
				}else{
					$msg = '更新数据错误，关联失败！';
					$this->session->set_flashdata ( 'info', $msg );
					redirect ( '/user/info/' );
				}
			}else{
				$msg = '身份校验错误，关联失败！';
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}
		}elseif ($is_reg === 'no') {//未注册，新增帐号并关联
			$exist = $this->usermodel->check_user_exist('',$email);
			if($exist['email']===TRUE){
				$this->session->set_flashdata ( 'info', 'Email已存在' );
				redirect ( '/user/info/' );
			}
			$result = $this->usermodel->add_user($username,
				$email,
				$this->session->userdata('mt4_account_pwd'),
				$rel_mt4_account
				);
			if ($result) {
				//写入redis
				$r_message = '欢迎新会员<span>' . $username . '</span>---' . $date = date('Y-m-d H:i:s');
				$this->save_item_in_redis($r_message);

				$this->usermodel->verify_user($username,$this->session->userdata('mt4_account_pwd'));
				$this->session->set_userdata('username',$username);
				$_SESSION['login']=TRUE;
				//*****自动绑定MT4账户*********
				$this->load->model('ipmodel');
				$tmpdata = $this->ipmodel->getServerByLocalPort($this->session->userdata('server'));
				$server_id = $tmpdata['id'];
				if($this->usermodel->checkMT4Exist(
					$this->session->userdata('mt4_account'),$server_id)===FALSE){
					$data=$this->usermodel->addMT4Account($this->session->userdata('mt4_account'),
						$this->session->userdata('mt4_account_pwd'),$this->session->userdata('server'),$server_id);
					$result = $this->parseMT4Answer($data);
					if ($result['success']!==TRUE){
						$msg = 'MT4账户自动绑定失败：' . $result['reason'] . '。请点击下面的返回链接手动绑定MT4账户。';
						$this->session->set_flashdata ( array('info'=>$msg,'return_url'=>site_url('user/mt4')));
						redirect ( '/user/result/' );
					} else {
						redirect('user');
					}
				}
				//*****************************
				redirect('user');
			}else{
				$msg = '关联失败！';
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}
		}else{
			show_error('参数非法'. $is_reg);
		}
	}
	//注册
	public function reg($refName=''){
		if ($this->is_login()) {
			redirect('user/index');
		}

		if($refName && strlen($refName) < 100) {
			$track_time = $this->config->item('track_time');
			$track_time = (is_integer($track_time) && ($track_time > 0) ? $track_time : 60);
			$this->input->set_cookie('refName', $refName, $track_time * 60);
		}

		$data = array('title'=>'用户注册','vcode'=>$this->generate_vcode());
		$this->load->view('reg',$data);
	}

	private function generate_vcode(){
		$this->load->helper('captcha');
		$this->load->model('captchamodel');
		$str = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
		$str = str_shuffle($str);
		$vals = array(
			'word'=>strtolower(substr($str, 0,4)),
		    'img_path' => './captcha/',
		    'img_url' => base_url() . 'captcha/',
		    'font_path' => './public/VeraSeBd.ttf',
		    'img_width' => 120,
		    'img_height' => 30,
		    'expiration' => 600,
    	);

    	$cap = create_captcha($vals);

    	$data = array(
    		'captcha_time'=>$cap['time'],
    		'ip_address'=>$this->input->ip_address(),
    		'word'=>$cap['word'],
    		);
    	$this->captchamodel->add($data);
    	return $cap['image'];
	}

	public function vcode(){
		echo $this->generate_vcode();
	}

	//真实账户申请
	public function real(){
		$first_name = trim($this->input->post('contact_x'));
		$last_name = trim($this->input->post('contact_m'));

		if ($first_name && $last_name) {
			$params = array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'sex'=> trim($this->input->post('sex')),
				'country'=> trim($this->input->post('country')),
				'country_res'=> trim($this->input->post('country_res')),
				'province'=> trim($this->input->post('province')),
				'city'=> trim($this->input->post('city')),
				'address'=> trim($this->input->post('address')),
				'card'=> trim($this->input->post('card')),
				'birthday'=> trim($this->input->post('birthday')),
				'phone_code'=> trim($this->input->post('phone_code')),
				'phone'=> trim($this->input->post('phone')),
				'mobile'=> trim($this->input->post('mobile')),
				'email'=> trim($this->input->post('email')),
				'qq'=> trim($this->input->post('qq')),
				'usertype'=> trim($this->input->post('usertype')),
				'level'=> trim($this->input->post('lever')),
				'injectionof'=> trim($this->input->post('injectionof')),
				'brokerid'=> trim($this->input->post('brokerid')),
				'brokername'=> trim($this->input->post('brokername')),
				'agentid'=> trim($this->input->post('agentid')),
				'agentname'=> trim($this->input->post('agentname')),
				'bank'=> trim($this->input->post('bank')),
				'banksub'=> trim($this->input->post('banksub')),
				'bankcode'=> trim($this->input->post('bankcode')),
				'bankaddress'=> trim($this->input->post('bankaddress')),
				'bankname'=> trim($this->input->post('bankname')),
				'bankcard'=> trim($this->input->post('bankcard')),
				'employment'=> trim($this->input->post('employment')),
				'employment_content'=> trim($this->input->post('employment_content')),
				'employment_2'=> trim($this->input->post('employment_2')),
				'securities'=> trim($this->input->post('securities')),
				'options'=> trim($this->input->post('options')),
				'commodity'=> trim($this->input->post('commodity')),
				'futures'=> trim($this->input->post('futures')),
				'exchange'=> trim($this->input->post('exchange')),
				'cfds'=> trim($this->input->post('cfds')),
				'frequency'=> trim($this->input->post('frequency')),
				'knowledge'=> trim($this->input->post('knowledge')),
				'annual_income'=> trim($this->input->post('annual_income')),
				'net_worth'=> trim($this->input->post('net_worth')),
				'liquid_assets'=> trim($this->input->post('liquid_assets')),
				'bankruptcy'=> trim($this->input->post('bankruptcy')),
				'bankruptcy_content'=> trim($this->input->post('bankruptcy_content')),
				'pastaccount'=> trim($this->input->post('pastaccount')),
				'pastaccount_content'=> trim($this->input->post('pastaccount_content')),
				'totalinvestment'=> trim($this->input->post('totalinvestment')),
				'isrelative'=> trim($this->input->post('isrelative')),
				'isrelative_content'=> trim($this->input->post('isrelative_content')),
				'user_id'=>$this->session->userdata('user_id'),
				'add_time'=>date('Y-m-d H:i:s'),
				);
			$this->load->model('realaccountmodel');
			if($insert_id = $this->realaccountmodel->add($params)){
				$msg = '真实账户申请成功，请等待官方处理您的申请资料！<br />请登陆申请真实账户时所填写的邮箱，查看真实账户申请结果！';
				//写入redis
				$r_message = '会员<span>' . $this->session->userdata('username') . '</span>申请真实账户---' . date ( 'Y-m-d H:i:s' );
				$this->save_item_in_redis($r_message);
				//在线开户
				$email_msg = '';
				if ($this->config->item('open_account_online') === TRUE) {
					$msg = '真实账户申请提交成功！<br />';
					$agent_id = $params['agentid'] + 0;
					$group_name = $this->config->item('default_group_name');

					if ($this->config->item('auto_generate_group_name') === FALSE) {
						if($agent_id){
							$this->load->model('agentmodel');
							$agent_info = $this->agentmodel->getAgentByAgentId($agent_id);
							if ($agent_info) {
								$group_name = $agent_info['group_name'];
							}
						}
					}else{
						$random_length = $this->config->item('random_number_length');
						$random_str = '';
						for ($i=0; $i < $random_length; $i++) { 
							$random_str .= mt_rand(0,9);
						}					
						$group_name = ($agent_id == 0) ? $this->config->item('default_group_name') : $agent_id;
					}

					$level = 100;
					$password = substr(str_shuffle('23456789'), 0, 3) .
							substr(str_shuffle('abcdefghgkmnpqrstuvwxyz'), 0, 3);
						//开户
						$real_params['group'] = iconv ('utf-8','gbk',$group_name);
						$real_params['agent'] = (($agent_id == 0) ? '' : $agent_id);
						$real_params['login'] = (($agent_id == 0) ? 0 : $agent_id . $random_str);
						$real_params['country'] = iconv ('utf-8','gbk',$params['country']);
						$real_params['state'] = iconv ('utf-8','gbk',$params['province']);
						$real_params['city'] = iconv ('utf-8','gbk',$params['city']);
						$real_params['address'] = iconv ('utf-8','gbk',$params['address']);
						$real_params['name'] = iconv ('utf-8','gbk',$params['first_name'] . $params['last_name']);
						$real_params['email'] = iconv ('utf-8','gbk',$params['email']);
						$real_params['password'] = iconv ('utf-8','gbk',$password);
						$real_params['password_investor'] = iconv ('utf-8','gbk',$password);
						$real_params['password_phone'] = iconv ('utf-8','gbk',$password);
						$real_params['leverage'] = iconv ('utf-8','gbk',$level);
						$real_params['zipcode'] = iconv ('utf-8','gbk','');
						$real_params['phone'] = iconv ('utf-8','gbk',$params['mobile']);
						$real_params['id'] = '';
						$real_params['comment'] = iconv ('utf-8','gbk','apply real account online');
						
						$this->load->model('usermodel');
						$this->load->model('ipmodel');
						$list = $this->ipmodel->getDefaultServer();
						$server_name = $list['name'];
						$local_port = $list['local_port'];
						$demodata = $this->usermodel->createMT4Account($real_params,$local_port);
						$result = $this->parseMT4Answer($demodata);
						if ($result['success']===TRUE) {
							$msg .= 'MT4实盘账户申请成功，请保存好以下账户信息。<br />登陆账号：' . $result['login'] . 
								'<br />登陆密码：' . $password . '<br />服务器：' . $server_name . '<br />';
							$email_msg = "\r\nMT4账户：{$result['login']}\r\n代理ID：{$real_params['agent']}";
							$this->realaccountmodel->updateMt4Account($result['login'],$insert_id);
						}else{
							$msg .= 'MT4实盘账户申请失败：' . $result['reason'];
							$email_msg = "\r\nMT4账户自动开户失败，请手动处理";
						}
				}

				//邮件通知
				$email_config = require (APPPATH . 'config/mail.php');
				$this->sendEmail($email_config,'真实账户申请通知', "客户信息：{$this->session->userdata('username')}\r\nEmail：" . 
					$params['email'] . "\r\nQQ：" . $params['qq'] . "{$email_msg}\r\n详细信息请登陆会员管理系统后台查看");
			}else{
				$msg = '申请真实账户失败!';
			}
			echo $msg;
		} else {
			$this->load->model('usermodel');
			if ($this->usermodel->get_user_certificate($this->session->userdata('user_id'))) {
				$data['url'] = base_url() . 'kf/common/registration_real/zh-cn/';
				$this->load->view('agency',$data);
			}else{
				//redirect('user/upload');
				$data['url'] = site_url('user/upload');
				$this->load->view('agency',$data);
			}
		}
	}

	//模拟账户申请
	public function demo(){
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		if ($username !== FALSE && $email !== FALSE) {
			//处理申请
			$country = $this->input->post('country');
			$state = $this->input->post('state');
			$city = $this->input->post('city');
			$address = $this->input->post('address');
			$password = $this->input->post('password');
			$zipcode = $this->input->post('zipcode');
			$phone = $this->input->post('phone');
			$level = $this->input->post('leverage');


			$params['group'] = iconv ('utf-8','gbk','demoforex');
			$params['agent'] = '';
			$params['login'] = 0;
			$params['country'] = iconv ('utf-8','gbk',$country);
			$params['state'] = iconv ('utf-8','gbk',$state);
			$params['city'] = iconv ('utf-8','gbk',$city);
			$params['address'] = iconv ('utf-8','gbk',$address);
			$params['name'] = iconv ('utf-8','gbk',$username);
			$params['email'] = iconv ('utf-8','gbk',$email);
			$params['password'] = iconv ('utf-8','gbk',$password);
			$params['password_investor'] = iconv ('utf-8','gbk',$password);
			$params['password_phone'] = iconv ('utf-8','gbk',$password);
			$params['leverage'] = iconv ('utf-8','gbk',$level);
			$params['zipcode'] = iconv ('utf-8','gbk',$zipcode);
			$params['phone'] = iconv ('utf-8','gbk',$phone);
			$params['id'] = '';
			$params['comment'] = iconv ('utf-8','gbk','apply demo account online');
			$local_port = trim($this->input->post('server'));

			$this->load->model('usermodel');
			$this->load->model('ipmodel');
			$list = $this->ipmodel->getServerByLocalPort($local_port);
			$server_name = $list['name'];
			$demodata = $this->usermodel->createMT4Account($params,$local_port);
			$result = $this->parseMT4Answer($demodata);
			if ($result['success']===TRUE) {
				//可以添加邮件通知，发送注册信息给客户
				//初始余额
				$demo['login']= $result['login'];
				$demo['value']= 100000;
				$demo['comment'] = 'initial blance of demo account';
				$demodata = $this->usermodel->changeBlance($demo,'changebalance',$local_port);
				$result = $this->parseMT4Answer($demodata);
				if ($result['success'] !== TRUE) {
					$msg = '初始化余额失败<br />';
				}
				//写入记录至数据库
				$this->load->model('demoaccountmodel');
				$data = array(
					'name'=>$username,
					'email'=>$email,
					'password'=>$password,
					'level'=>$level,
					'country'=>$country,
					'state'=>$state,
					'city'=>$city,
					'zipcode'=>$zipcode,
					'address'=>$address,
					'phone'=>$phone,
					'server'=>$server_name,
					'mt4_account'=>$result['login'],
					'username'=>$this->session->userdata('username'),
					);
				if($this->demoaccountmodel->add($data)===FALSE){
					$msg .= '写入数据库失败<br />';
				}
				$mt4_download_url = site_url('user/mt4download');
				$msg .= 'MT4模拟账户申请成功，请保存好以下账户信息。<br />登陆账号：' . $result['login'] . 
					'<br />登陆密码：' . $params['password'] . '<br />服务器：' . $server_name .
					'<br /><a target="_blank" href="' . $mt4_download_url . '">立即下载MT4客户端进行模拟交易</a>';
				//写入redis
				$r_message = '会员<span>' . $this->session->userdata('username') . '</span>申请模拟账户---' . date ( 'Y-m-d H:i:s' );
				$this->save_item_in_redis($r_message);

				//邮件通知
				$email_config = require (APPPATH . 'config/mail.php');
				$this->sendEmail($email_config,'模拟账户申请通知', "客户信息：\r\nEmail：" . 
					$data['email'] . "\r\nMT4 Account：" . $data['mt4_account'] . "\r\n详细信息请登陆会员管理系统后台查看");

				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/result/' );
			}else{
				$msg = 'MT4模拟账户申请失败：' . $result['reason'];
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}
		} else {
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
			$data=array('title'=>'模拟账户申请','server'=>$server);
			$this->load->view('demo',$data);
		}
	}
	//客户端下载
	public function mt4download(){
		redirect($this->config->item('company_url'));
	}
	/**
	 * 保存记录至redis
	 * @access private
	 * @param  string $value 保存值
	 * @return boolean       成功返回true，失败返回false
	 */
	private function save_item_in_redis($value='')
	{
		require_once './include/Predis/Autoloader.php';
		Predis\Autoloader::register();

		try{
			$r = new Predis\Client();
			$r->connect('127.0.0.1',6379);
			$my_log_len = $r->llen($this->config->item('encryption_key'));
			if($my_log_len < 21){
				$r->rpush($this->config->item('encryption_key'),$value);
			}else{
				$r->lpop($this->config->item('encryption_key'));
				$r->rpush($this->config->item('encryption_key'),$value);
			}
			return TRUE;
		}catch(Exception $e){
			echo $e->getMessage();
			return FALSE;
		}	
	}
	//退出登陆
	public function logout(){
		$this->session->unset_userdata('username');
		session_destroy();
		redirect('user/login');
	}
	//验证码校验
	public function vcode_check($captcha){
		$this->load->model('captchamodel');
		$expiration = time() - 600;
		$this->captchamodel->del($expiration);
		$condition = array(
			'word'=>strtolower($captcha),
			'ip_address'=>$this->input->ip_address(),
			'captcha_time >'=>$expiration,
			);
		return $this->captchamodel->get($condition);
	}
	//处理注册请求
	public function add(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username','帐号','trim|required|min_length[3]|max_length[20]');
		$this->form_validation->set_rules('email','邮箱','trim|required|valid_email');
		$this->form_validation->set_rules('password','密码','trim|required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('captcha','验证码','trim|required|callback_vcode_check');
		
		if ($this->form_validation->run() !== FALSE){
			$username=trim($this->input->post('username'));
			$email=trim($this->input->post('email'));
			$password=trim($this->input->post('password'));
			$this->load->model('usermodel');
			$rs = $this->usermodel->check_user_exist($username,$email);
			if($rs['username'] === TRUE){
				$msg = '帐号已存在';
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}elseif ($rs['email'] === TRUE){
				$msg = 'Email已存在';
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}

			$result = $this->usermodel->add_user($username,$email,$password);
			if ($result) {
				$msg = '注册成功！';

				//写入redis
				$r_message = '欢迎新会员<span>' . $username . '</span>---' . $date = date('Y-m-d H:i:s');
				$this->save_item_in_redis($r_message);
			}else{
				$msg = '注册失败！';
			}
			$this->session->set_flashdata (array('info'=>$msg,'regname'=>$username));
			redirect ( '/user/info/' );
		}else{
			$data = array('title'=>'用户注册');
			$this->load->view('reg',$data);
		}
	}
	
	public function bind_mt4_account(){
		$data = array('title'=>'绑定MT4帐号');
		$this->load->view('bind_mt4_account',$data);
	}
	
	public function setinfo($type){
		if($type==='mt4'){
			$this->load->view('mt4');
		}else if($type ==='pwd'){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('password_old','旧密码','trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_new','新密码','trim|required|min_length[6]|max_length[20]');

			if($this->form_validation->run()!==FALSE){
				$password_old = trim($this->input->post ( 'password_old' ));
				$password_new = trim($this->input->post ( 'password_new' ));
				$this->load->model ( 'usermodel' );
				$rs = $this->usermodel->modify_pwd ( $password_old, $password_new ,$this->session->userdata('username'));
				if ($rs === 1) {
					$msg = '旧密码错误，密码修改失败！';
				} else if ($rs === true) {
					$msg = '密码修改成功！';
				} else {
					$msg = '密码修改失败！';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}else{
				$data = array('title'=>'密码修改');
				$this->load->view('index',$data);
			}
		}else if($type ==='withdraw'){
			$username = trim($this->input->post('username'));
			$this->load->model('userbankmodel');
			if(!$username){
				$this->load->model('regionmodel');
				$country = $this->regionmodel->get_regions(0,0);
				$province = $this->regionmodel->get_regions(1,$country[0]['region_id']);
				$data = array('country'=>$country,'province'=>$province);
				if($list = $this->userbankmodel->getBank()){
					$data['info']=$list[0];
					$city = $this->regionmodel->get_regions(2,$list[0]['province']);
					$data['city']=$city;
				}
				$data['title']='取款信息设置';
				$this->load->view('withdraw',$data);
			}else{
				//处理表单
				$this->load->library('form_validation');
				$this->form_validation->set_rules('username','收款人姓名','trim|required|max_length[25]');
				$this->form_validation->set_rules('bank','收款人银行帐号','trim|required|min_length[16]|max_length[19]');
				$this->form_validation->set_rules('country','国家','trim|required');
				$this->form_validation->set_rules('province','省份','trim|required');
				$this->form_validation->set_rules('city','城市','trim|required');
				$this->form_validation->set_rules('bank_name','收款账户开户行名称','trim|required');

				if ($this->form_validation->run() !== FALSE){
					$data =array(
							'name'=>$username,
							'code' => trim($this->input->post('bank')),
							'country' => trim($this->input->post('country')),
							'province' => trim($this->input->post('province')),
							'city' => trim($this->input->post('city')),
							'address' => trim($this->input->post('bank_name')),
							);
					//添加
					if(!$this->userbankmodel->getBank()){
						$r = $this->userbankmodel->addBank($data);
					}else{//更新
						$r = $this->userbankmodel->updateBank($data);
					}
					if($r){
						$msg='取款信息保存成功';
					}else{
						$msg='取款信息保存失败';
					}
					$this->session->set_flashdata ( 'info', $msg );
					redirect ( '/user/info/' );
				}
			}
		}else{
			$data = array('title'=>'首页');
			$this->load->view('index',$data);
		}
	}
	//mt4账户相关
	public function mt4($action='list', $id = 0){
		$this->load->model('usermodel');
		if ($action ==='add') {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username','MT4帐号','trim|required|integer');
			$this->form_validation->set_rules('password','密码','trim|required');
			$this->form_validation->set_rules('server','服务器','trim|required');
			if ($this->form_validation->run() !== FALSE){
				$username=trim($this->input->post('username'));
				$password=trim($this->input->post('password'));
				$server_id=trim($this->input->post('server'));

				$this->load->model('ipmodel');
				$sv = $this->ipmodel->getIp($server_id);
				$local_port = $sv['local_port'];

				if(!$this->usermodel->is_demo($username, $password,$local_port)){
					if($this->usermodel->checkMT4Exist($username,$server_id)===TRUE){
						$msg = '该MT4账户已被绑定，请先删除再进行绑定';
					}else{
						$data=$this->usermodel->addMT4Account($username,$password,$local_port,$server_id);
						$result = $this->parseMT4Answer($data);
						if ($result['success']===TRUE) {
							$msg = 'MT4账户绑定成功';
							if (isset($_SESSION['user_mt4_account'])) {
								unset($_SESSION['user_mt4_account']);
							}
						}else{
							$msg = 'MT4账户绑定失败：' . $result['reason'];
						}
					}
				}else {
					$msg = '不支持demo帐号';
				}
				$this->session->set_flashdata ( 'info', $msg );
				redirect ( '/user/info/' );
			}else{
				$list=$this->usermodel->getMT4Account();
				$this->load->model('ipmodel');
				$server=$this->ipmodel->getIp();
				$data = array('data'=>$list,'server'=>$server,'title'=>'绑定MT4帐号');

				$this->load->view('mt4',$data);
			}
		}else if($action==='del'){
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
			$this->load->model ( 'usermodel' );
			if ($this->usermodel->delMT4Account ( $condition )) {
				echo '1';
			} else {
				echo '0';
			}
		}else if($action === 'ch'){
			$id = $this->input->post('id')+0;
			if(!$id){
				echo '非法操作';
			}
			$this->load->model ( 'usermodel' );
			if(!$this->usermodel->changeStat($id)){
				exit('Update error!');
			}
			if($this->usermodel->is_chief($id)){
				echo '主帐号';
			}else{
				echo '副帐号';
			}
		} else if ($action === 'edit') {
			$serverId = $this->input->post('serverId') + 0;
			$this->load->model('UserMt4AccountModel');
			if ($serverId > 0) {
				$id = $this->input->post('id') + 0;
				$mt4Pass = mysql_real_escape_string($this->input->post('mt4Pass'));
				$mt4Account = $this->input->post('mt4Account') + 0;
				if ($id === 0 || strlen($mt4Pass) < 5) {
					$this->session->set_flashdata ( 'info', '参数无效' );
					redirect ( '/user/info/' );
				}

				$this->load->model('usermodel');
				$this->load->model('ipmodel');
				
				$ipData = $this->ipmodel->getIp($serverId);
				if (!$ipData) {
					$this->session->set_flashdata ( 'info', '获取服务器信息失败，请重试' );
					redirect ( '/user/info/' );
				}
				$localPort = $ipData['local_port'];

				if (!$this->usermodel->is_valid_mt4account($mt4Account, $mt4Pass, $localPort)) {
					$this->session->set_flashdata ( 'info', 'MT4密码无效' );
					redirect ( '/user/info/' );
				}

				if ($this->UserMt4AccountModel->updateMt4AccountById($id,array('password'=>$mt4Pass))) {
					$this->session->set_flashdata ( 'info', '保存成功' );
					$this->session->set_flashdata ( 'return_url', site_url('user/mt4') );
					redirect ( '/user/info/' );
				} else {
					$this->session->set_flashdata ( 'info', '保存失败' );
					redirect ( '/user/info/' );
				}
			} else {
				$id += 0;
				if ($id === 0) {
					show_404();
				}

				$mt4Account = $this->UserMt4AccountModel->getMt4AccountById($id,
					$this->session->userdata('user_id'));

				if (!$mt4Account) {
					$this->session->set_flashdata ( 'info', '获取MT4账户信息失败，请重试' );
					redirect ( '/user/info/' );
				}
				$mt4Account['title'] = 'MT4账户编辑';
				$this->load->view('mt4edit', $mt4Account);
			}
		}else {
			$list=$this->usermodel->getMT4Account();
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
			$data = array('data'=>$list,'server'=>$server,'title'=>'绑定MT4帐号');
			$this->load->view('mt4',$data);
		}
	}
	
	// 提示信息页面
	public function info() {
		$this->load->view ( 'info' );
	}

	// 提示信息页面 非自动跳转
	public function result() {
		$this->load->view ( 'result' );
	}


	private function isEqualId($account,$account_to,$local_port,$check_item){
		$this->load->model('usermodel');
		$from = $this->parseMT4Answer($this->usermodel->getAccountInfo(array('login'=>$account),$local_port));
		$to = $this->parseMT4Answer($this->usermodel->getAccountInfo(array('login'=>$account_to),$local_port));
		if ($from['success'] === TRUE && $to['success'] === TRUE) {
			if (isset($from[$check_item]) && isset($to[$check_item]) && 
				trim($from[$check_item]) === trim($to[$check_item])) {
				return TRUE;
			}
		}

		return FALSE;
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
	
	/**
	 * 加载在线入金表单
	 *
	 * @access public
	 * @return void
	 */
	public function deposit(){
		//判断是否已绑定mt4账号
		$this->load->model ( 'usermodel' );
		$mt4 = $this->usermodel->getMT4Account();
		if(!$mt4){
			$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
			redirect ( '/user/info/' );
		}
		//判断是否已设置汇率
		$this->load->model('exchange_rate_model');
		$exchange_rate = $this->exchange_rate_model->get_exchange_rate();
		if ($exchange_rate === 1 || $exchange_rate === false) {
			$this->session->set_flashdata ( 'info', '获取汇率出错或未设置汇率，请联系管理员' );
			redirect ( '/user/info/' );
		}
		//判断加载哪一类表单
		$this->load->model('paymentmodel');
		$result = $this->paymentmodel->getEnabledPayment();
		if ($result) {
			if ($result[0]['code'] === 'bibao') {
				redirect('bibao');
			}elseif ($result[0]['code'] === 'kuaiqian') {
				$this->load->model('usermodel');
				$this->load->model('ipmodel');
				$mt4 = $this->usermodel->getMT4Account();
				$server=$this->ipmodel->getIp();
				$data=array('mt4'=>$mt4,'server'=>$server,'title'=>'在线入金');
				$this->load->view('deposit',$data);
			}elseif ($result[0]['code'] === 'yibao') {
				redirect('yibao');
			}elseif ($result[0]['code'] === 'huichao') {
				redirect('huichao');
			}elseif ($result[0]['code'] === 'huanxun') {
				redirect('huanxun');
			}elseif ($result[0]['code'] === 'zhifu') {
				redirect('zhifu');
			}
		}else{
			$this->session->set_flashdata ( 'info', '未启用支付方法' );
			redirect ( '/user/info/' );
		}
	}
	
	//出金
	public function withdraw($a="list"){
		//判断是否已绑定mt4账号
		$this->load->model ( 'usermodel' );
		$mt4 = $this->usermodel->getMT4Account();
		if(!$mt4){
			$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
			redirect ( '/user/info/' );
		}
		//判断是否已设置取款信息
		$this->load->model('userbankmodel');
		if(!$this->userbankmodel->getBank()){
			$this->session->set_flashdata ( 'return_url', site_url('/user/setinfo/withdraw/') );
			$this->session->set_flashdata ( 'info', '您还未设置取款信息，请先设置取款信息。点击页面下方的返回链接进入取款信息设置页面。' );
			redirect ( '/user/result/' );
		}
		if($a==='add'){
			$flag = 0;
			$msg='';
			$mt4_info = '';
			$amount = $this->input->post('amount');
			$local_port = $this->input->post('server');
			$account=trim($this->input->post('account'));
			$username = trim($this->input->post('username'));
			$c = $this->input->post('country');
			$p = $this->input->post('province');
			$ci = $this->input->post('city');
			$this->load->model('regionmodel');
			$c_n=$this->regionmodel->get_name($c);
			$p_n=$this->regionmodel->get_name($p);
			$ci_n=$this->regionmodel->get_name($ci);
			$area = $c_n . $p_n . $ci_n;
			$bankcode = trim($this->input->post('bank'));
			$bankname=trim($this->input->post('bank_name'));
			if ($username===FALSE || $account===FALSE || $bankcode===FALSE || $bankname===FALSE) {
				$this->session->set_flashdata ( 'info', '参数非法' );
				redirect ( '/user/info/' );
			}

			if ($amount<=0) {
				$this->session->set_flashdata ( 'info', '非法操作' );
				redirect ( '/user/info/' );
			}
			
			$this->load->model('usermodel');
			//验证账户有效性
			$password = $this->usermodel->get_mt4account_pwd($account);
			$valid = $this->usermodel->is_valid_mt4account($account,$password,$local_port);
			if ($valid === FALSE) {
				$this->session->set_flashdata ( 'info', 'MT4账户验证失败，请确认：1.服务器是否选择正确。2.MT4账户密码是否正确/已过期。' );
				redirect ( '/user/info/' );
			}
			//验证保证金
			$data = $this->usermodel->getmargininfo($account,$local_port);
			$data = $this->parseMT4Answer($data);

			$balanceData = $this->usermodel->getBalance($account,$local_port);
			$balanceData = $this->parseMT4Answer($balanceData);

			if ($data['success'] !== TRUE || $balanceData['success'] !== TRUE) {
				$this->session->set_flashdata ( 'info', '获取MT4账户有效保证金出错' );
				redirect ( '/user/info/' );
			} else {
				$credit = $data['balance'] - $balanceData['balance'];
				$free_margin = $data['freeMargin'] - $credit;
			}
			if ($amount > $free_margin) {
				$this->session->set_flashdata ( 'info', '非法操作，取款额不能大于取款额度（可用保证金-信用额度）' );
				redirect ( '/user/info/' );
			}

			$my_config = require (APPPATH . 'config/mail.php');
			$this->load->model('exchange_rate_model');
			$hl = $this->exchange_rate_model->get_exchange_rate();//取汇率
			if ($hl === 1 || $hl === false) {
				$this->session->set_flashdata ( 'info', '获取汇率出错或未设置汇率，请联系管理员' );
				redirect ( '/user/info/' );
			}
			$withdraw_rate = $hl[0]->withdraw_rate;
			$withdraw_factor = $hl[0]->withdraw_factor;

			//识别多币种账户
			$rmb_account = trim($my_config['rmb_account']);
			if ( $rmb_account != '') {
				if(preg_match($rmb_account, $account) > 0){
					//是人民币账户
					$withdraw_rate = 1;
				}
			}

			$rmbAmount = round ( $withdraw_rate * $amount, 2 );
			$commission = round( (1 - $withdraw_factor) * $withdraw_rate * $amount, 2 );
			//手续费最小值
			$minCommission = floatval($this->config->item('minCommission'));
			$minCommission = $minCommission >= 0 ? $minCommission : 0;
			$commission = $commission > $minCommission ? $commission : $minCommission;//手续费最小值
			if ($commission - $rmbAmount >= 0) {
				$this->session->set_flashdata ( 'info', "出金失败，当前出金金额￥{$rmbAmount}不足以支付出金手续费￥{$commission}" );
				redirect ( '/user/info/' );
			}
			
			$this->before_withdraw($account,$amount);

			$time=date ( 'Y-m-d H:i:s' );
			$data =array(
					'account'=>$account,
					'amount'=>$amount,
					'name'=>$username,
					'bank_code'=>$bankcode,
					'bank_name'=>$bankname,
					'area'=>$area,
					'time'=>$time,
					'cjhl'=>$withdraw_rate,
					'rmb'=>($rmbAmount - $commission),
					'is_success'=>0,
					'params'=>0,
					'user_id'=> $this->session->userdata['user_id'],
					);
			$this->load->model('withdrawmodel');
			$id = $this->withdrawmodel->add($data);
			if($id !== FALSE){
				$msg='出金请求提交成功。';
			}else{
				$msg='出金请求提交失败。';
			}
			if ($my_config['is_cj']==1) {
				//处理出金扣款
				$params ['login'] = $account;
				$params ['value'] = -$amount;
				$params ['comment'] = "Withdraw online By $account";
				
				$data = $this->usermodel->changeBlance($params,$local_port);
				$result = $this->parseMT4Answer($data);
				if ($result['success']===TRUE) {
					$msg .= "更新MT4账户余额成功。";
					$flag = 1;
				}else{
					$msg .= $result['reason'] . "本次出金未更新至MT4账户，请手动处理。";
					$mt4_info = $result['reason'] . "本次出金未更新至MT4账户，请手动处理。";
				}

			}else{
				$flag=2;//未开启此功能
			}

			//更新数据库记录
			$params=array('is_success'=>$flag);
			$this->withdrawmodel->changeInfo($params,$id);

			//private log
			$this->writePrivateLog('在线出金');

			//邮件通知
			if ($my_config['is_open'] == 1) {
				$this->load->library('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = $my_config['email']['host'];
				$config['smtp_user'] = $my_config['email']['username'];
				$config['smtp_pass'] = $my_config['email']['password'];
				$config['smtp_port'] = 25;
				$config['charset'] =$my_config['email']['charset'];
				$config['crlf'] ="\r\n";
				$config['newline']="\r\n";
				$config['wordwrap'] = TRUE;			
				$this->email->initialize($config);
				
				$this->email->from($my_config['email']['username'], 'Admin');
				$this->email->to($my_config['list']);
				
				if ($flag===1) {
					$subject = "在线出金通知[更新成功]";
					$mt4_info ='成功';
				} elseif($flag===0) {
					$subject = "在线出金通知[更新失败-$mt4_info]";
				}else{
					$subject = "在线出金通知";
					$mt4_info ='未开启此功能';
				}

				//取服务器名称
				$this->load->model('ipmodel');
				$result = $this->ipmodel->getServerByLocalPort($local_port);
				$server_name = $result['name'];

				$content = "取款交易帐号：$account\r\n" . "服务器：$server_name\r\n" . "取款金额：$amount\r\n" . 
					"收款人姓名：$username\r\n" . "收款人银行帐号：$bankcode\r\n" . "地区：$area\r\n" . "收款账户开户行名称：$bankname\r\n" . "时间：" . 
					$time . "\r\n与MT4服务器交互状态：" . $mt4_info;
				$this->email->subject($subject);
				$this->email->message($content);
				
				$this->email->send();
			}

			//写入redis
			$r_message = '会员<span>' . $this->session->userdata('username') . '</span>在线出金<span>$' . $amount . '</span>---' . $time;
			$this->save_item_in_redis($r_message);

			$this->session->set_flashdata ( 'info', $msg );
			redirect ( '/user/info/' );
		}
		$this->load->model('ipmodel');
		$this->load->model('userbankmodel');
		$this->load->model('regionmodel');
			
		$mt4 = $this->usermodel->getMT4Account();
		$server=$this->ipmodel->getIp();
		$data=array('mt4'=>$mt4,'server'=>$server,'title'=>'在线出金');
		
		$country = $this->regionmodel->get_regions(0,0);
		$province = $this->regionmodel->get_regions(1,$country[0]['region_id']);
		$data['country']=$country;
		$data['province']=$province;
		if($list = $this->userbankmodel->getBank()){
			$data['info']=$list[0];
			$city = $this->regionmodel->get_regions(2,$list[0]['province']);
			$data['city']=$city;
		}
		
		$this->load->view('dowithdraw',$data);
	}
	
	//转帐
	public function transfer($a="list"){
		$data = require (APPPATH . 'config/mail.php');
		if ($data['is_zz'] != 1) {
			exit();
		}
		//判断是否已绑定mt4账号
		$this->load->model ( 'usermodel' );
		$mt4 = $this->usermodel->getMT4Account();
		if(!$mt4){
			$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
			redirect ( '/user/info/' );
		}
		if ($a==='add') {
			$flag = 0;
			$msg='';
			$mt4_info = '';

			$amount = $this->input->post('amount');
			if ($amount<=0) {
				$this->session->set_flashdata ( 'info', '非法操作' );
				redirect ( '/user/info/' );
			}

			$account = trim($this->input->post('account'));
			$account_to = trim($this->input->post('account_to'));
			$local_port = trim($this->input->post('server'));

			$this->load->model('usermodel');
			//验证相互转账的MT4账户在MT4服务器上的指定标志信息是否一致
			if ($this->config->item('transfer_check_from_mt4server') === TRUE && 
				!$this->isEqualId($account,$account_to,$local_port,$this->config->item('check_item'))) {
				$this->session->set_flashdata ( 'info', '转账失败：转出MT4账户与转入MT4账户身份信息不一致，系统仅允许同一身份的MT4账户之间转账' );
				redirect ( '/user/info/' );
			}
			//验证转出MT4账户有效性
			$password = $this->usermodel->get_mt4account_pwd($account);
			$valid = $this->usermodel->is_valid_mt4account($account,$password,$local_port);
			if ($valid === FALSE) {
				$this->session->set_flashdata ( 'info', '转出MT4账户验证失败，请确认：1.服务器是否选择正确。2.MT4账户密码是否正确/已过期。' );
				redirect ( '/user/info/' );
			}

			//验证转出MT4账户保证金
			$data = $this->usermodel->getmargininfo($account,$local_port);
			$data = $this->parseMT4Answer($data);

			$balanceData = $this->usermodel->getBalance($account,$local_port);
			$balanceData = $this->parseMT4Answer($balanceData);

			if ($data['success'] !== TRUE || $balanceData['success'] !== TRUE) {
				$this->session->set_flashdata ( 'info', '获取转出MT4账户有效保证金出错' );
				redirect ( '/user/info/' );
			} else {
				$credit = $data['balance'] - $balanceData['balance'];
				$free_margin = $data['freeMargin'] - $credit;
			}
			if ($amount > $free_margin) {
				$this->session->set_flashdata ( 'info', '非法操作，转账金额不能大于转出MT4账户转账额度（可用保证金-信用额度）' );
				redirect ( '/user/info/' );
			}

			//出金
			$my_config = require (APPPATH . 'config/mail.php');
			$time=date ( 'Y-m-d H:i:s' );
			$data =array(
					'transfer_from'=>$account,
					'transfer_to'=>$account_to,
					'amount'=>$amount,
					'transfer_time'=>$time,
					'is_success'=>0,
					'user_id'=>$this->session->userdata['user_id'],
					);
			$this->load->model('transfermodel');
			$id = $this->transfermodel->add($data);
			if($id !== FALSE){
				$msg='转账请求提交成功。';
			}else{
				$msg='转账请求提交失败。';
			}

			//处理出金
			$params ['login'] = $account;
			$params ['value'] = -$amount;
			$params ['comment'] = "transfer to $account_to";
			
			$data = $this->usermodel->changeBlance($params,$local_port);
			$result = $this->parseMT4Answer($data);
			if ($result['success']===TRUE) {
				$msg .= "转出账户{$account}出金成功。";

				//处理入金
				$this->load->model('exchange_rate_model');
				$hl = $this->exchange_rate_model->get_exchange_rate();//取汇率
				if ($hl === 1 || $hl === false) {
					$this->session->set_flashdata ( 'info', '获取汇率出错或未设置汇率，请联系管理员' );
					redirect ( '/user/info/' );
				}
				$deposit_rate = $hl[0]->deposit_rate;
				$withdraw_rate = $hl[0]->withdraw_rate;
				$huilv = 1;
				//识别多币种账户
				$file_data = require (APPPATH . 'config/mail.php');
				$rmb_account = trim($file_data['rmb_account']);
				if ( $rmb_account != '') {
					if(preg_match($rmb_account, trim($account)) > 0){
						//转出账户是人民币账户
						if(preg_match($rmb_account, trim($account_to)) > 0){
							//转入账户是人民币账户
							$huilv = 1;
						}else{
							$huilv = 1 / $deposit_rate;
						}
					}else{
						//转出账户是美元账户
						if(preg_match($rmb_account, trim($account_to)) > 0){
							//转入账户是人民币账户
							$huilv = $withdraw_rate;
						}else{
							$huilv = 1;
						}
					}
				}

				$params ['login'] = $account_to;
				$params ['value'] = round($amount * $huilv,2);
				$params ['comment'] = "transfer from $account";
				
				$data = $this->usermodel->changeBlance($params,$local_port);
				$result = $this->parseMT4Answer($data);
				if ($result['success']===TRUE) {
					$msg .= "转入账户{$account_to}入金成功。";
					$flag = 1;

					//更新数据库记录
					$update_data=array('is_success'=>$flag);
					$this->transfermodel->update($update_data,$id);
				}else{
					$msg .= $result['reason'] . "转入账户{$account_to}入金失败。";
					$mt4_info = $result['reason'] . "转入账户{$account_to}入金失败，请手动处理。";
				}
			}else{
				$msg .= $result['reason'] . "转出账户{$account}出金失败";
				$mt4_info = $result['reason'] . "转出账户{$account}出金失败，请手动处理。";
			}

			//邮件通知
			/*
			if ($my_config['is_open'] == 1) {
				$this->load->library('email');
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = $my_config['email']['host'];
				$config['smtp_user'] = $my_config['email']['username'];
				$config['smtp_pass'] = $my_config['email']['password'];
				$config['smtp_port'] = 25;
				$config['charset'] =$my_config['email']['charset'];
				$config['crlf'] ="\r\n";
				$config['newline']="\r\n";
				$config['wordwrap'] = TRUE;			
				$this->email->initialize($config);
				
				$this->email->from($my_config['email']['username'], 'Admin');
				$this->email->to($my_config['list']);
				
				if ($flag===1) {
					$subject = "内部转账通知[成功]";
					$mt4_info ='成功';
				} elseif($flag===0) {
					$subject = "内部转账通知[失败-$mt4_info]";
				}

				//取服务器名称
				$this->load->model('ipmodel');
				$result = $this->ipmodel->getServerByLocalPort($local_port);
				$server_name = $result['name'];

				$content = "转出MT4帐号：$account\r\n" . "服务器：$server_name\r\n" . "转入MT4帐号：$account_to\r\n" . "金额：$amount\r\n" . 
					"时间：" . $time . "\r\n与MT4服务器交互状态：" . $mt4_info;
				$this->email->subject($subject);
				$this->email->message($content);
				
				$this->email->send();
			}
			*/

			//写入redis
			$r_message = '会员<span>' . $this->session->userdata('username') . '</span>在线转账<span>$' . $amount . '</span>---' . $time;
			$this->save_item_in_redis($r_message);

			$this->session->set_flashdata ( 'info', $msg );
			redirect ( '/user/info/' );

		}
		$this->load->model('ipmodel');
		$mt4 = $this->usermodel->getMT4Account();
		$server=$this->ipmodel->getIp();;
		$data=array('mt4'=>$mt4,'server'=>$server,'title'=>'内部转账');
		$this->load->view('transfer',$data);
	}
	
	//信息查询
	public function checkinfo($info='deposit'){
		$this->load->model ( 'depositmodel' );
		$this->load->model ( 'usermodel' );
		$mt4 = $this->usermodel->getMT4Account();
		
		//默认条件
		if($mt4){
			$cur_user_id = $this->session->userdata['user_id'];
			$default = " where user_id = '{$cur_user_id}' and (";
			foreach ($mt4 as $v){
				$a=$v['mt4_account'];
				$default .= " account = '{$a}' or ";
			}
			$default = rtrim ( $default, 'or ' );
			$default .= ')';

			foreach ($mt4 as $v) {
				$user_has_mt4Accounts[] = $v['mt4_account'];
			}
		}else{
			$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
			redirect ( '/user/info/' );
		}

		if($info==='deposit'){
			$data = array ();
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
				
			// 生成查询条件
			$where = array ();
			$account = mysql_real_escape_string(trim($this->input->get ( 'account' )));
			$order_id = mysql_real_escape_string(trim($this->input->get ( 'order_id' )));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
			
			//只显示支付成功的记录
			$is_ok = 1;

			if ($is_ok) {
				$where [] = " is_ok = 1 and ";
			}
			if ($account) {
				//过滤
				if (!in_array($account, $user_has_mt4Accounts)) {
					exit('access denied');
				}
				$where [] = " account = '{$account}' and ";
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
				$condition = rtrim ( $condition, 'and ' );
			} else {
				$condition = '';
			}

			if($account){
				$condition = $condition===' where  is_ok = 1' ? $default . ' and is_ok = 1 ' : $condition;
			}else{
				$condition .= str_replace('where', ' and ', $default);
			}
			
			$data ['itemsNum'] = $this->depositmodel->getItemsNum ( $condition ); // 总记录数
			$pageCount = ceil ( $data ['itemsNum'] / $pageSize ); // 总页数
			if ($pn && is_numeric ( $pn ) && $pn >= 1 && $pn <= $pageCount) {
				$pageNow = $pn;
			}
			$p = ($pageNow - 1) * $pageSize;
			$data ['list'] = $this->depositmodel->getPageItems ( $condition, $p, $pageSize );
				
			// 分页条
			$params = array (
					'totalCount' => $data ['itemsNum'],
					'pageSize' => $pageSize,
					'pageNavNum' => 10,
					'showGoto' => true,
					'showTotal' => true,
					'position' => 'right',
					'pageNow' => $pageNow,
					'url'=>'user/checkinfo/deposit',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['mt4']=$mt4;
			$data['title']='入金查询';
			$this->load->view('depositlist.php',$data);
		}elseif ($info==='withdraw'){
			$data = array ();
			$this->load->model ( 'withdrawmodel' );
			
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
			
			// 生成查询条件
			$where = array ();
			$ch_name = mysql_real_escape_string(trim($this->input->get ( 'ch_name' )));
			$account = mysql_real_escape_string(trim($this->input->get ( 'account' )));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
				
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
			
			if ($account) {
				//过滤
				if (!in_array($account, $user_has_mt4Accounts)) {
					exit('access denied');
				}
				$where [] = " account = '{$account}' and ";
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
				$condition = rtrim ( $condition, 'and ' );
			} else {
				$condition = '';
			}
			
			if($account){
				$condition = $condition==='' ? $default : $condition;
			}else{
				if($condition !== ''){
					$condition .= str_replace('where', ' and ', $default);
				}else{
					$condition = $default;
				}
			}
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
					'url'=>'user/checkinfo/withdraw',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['mt4']=$mt4;
			$data['title']='出金查询';
			$this->load->view('withdrawlist.php',$data);
		}elseif($info==='transfer'){
			$data = require (APPPATH . 'config/mail.php');
			if ($data['is_zz'] != 1) {
				exit();
			}
			//默认条件
			if($mt4){
				$default = " where user_id = '{$cur_user_id}' ";
			}else{
				exit();
			}

			$data = array ();
			$this->load->model ( 'transfermodel' );
			
			$list = array ();
			$pageSize = 20;
			$pageNow = 1;
			$pageCount = 0;
			
			// 生成查询条件
			$where = array ();
			$account_from = mysql_real_escape_string(trim($this->input->get ( 'account_from' )));
			$account_to = mysql_real_escape_string(trim($this->input->get ( 'account_to' )));
			$start_time = mysql_real_escape_string($this->input->get ( 'start_time' ));
			$end_time = mysql_real_escape_string($this->input->get ( 'end_time' ));
			$pn = mysql_real_escape_string($this->input->get ( 'pageNow' ));
			
			if ($account_from) {
				//过滤
				if (!in_array($account_from, $user_has_mt4Accounts)) {
					exit('access denied');
				}
				$where [] = " transfer_from = '{$account_from}' and ";
			}
			if ($account_to) {
				//过滤
				if (!in_array($account_to, $user_has_mt4Accounts)) {
					exit('access denied');
				}
				$where [] = " transfer_to = '{$account_to}' and ";
			}
			if ($start_time && $end_time) {
				$where [] = " transfer_time between '$start_time' and '$end_time' and ";
			}
			
			$condition = " where user_id = '{$cur_user_id}' and (";
			if (! empty ( $where )) {
				foreach ( $where as $v ) {
					$condition .= $v;
				}
				$condition = rtrim ( $condition, 'and ' );
				$condition .= ')';
			} else {
				$condition = $default;
			}
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
					'url'=>'user/checkinfo/transfer',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			$data['mt4']=$mt4;
			$data['title']='转账查询';
			$this->load->view('transferlist.php',$data);
		}else{
			show_404();
		}
	}
	
	/**********************************************************/
	//快钱信息提交
	public function kq(){
		$this->load->model('paymentmodel');
		$pay = $this->paymentmodel->getMerchant('kuaiqian');
		if(!$pay){
			echo 'get merchant error';
			exit();
		}
		$merchant=$pay[0]['merchant'];
		$username=trim($this->input->post('username'));
		$amount=$this->input->post('amount');
		$bank=$this->input->post('bank');
		$remark=trim($this->input->post('remark'));
		$server=$this->input->post('server');
		//验证MT4账户是否有效
		$this->load->model('usermodel');
		$this->load->model('ipmodel');
		$pw = $this->usermodel->get_mt4account_pwd($username);
		$pt = $this->ipmodel->getIp($server);
		$pt = $pt['local_port'];
		if ($this->usermodel->is_valid_mt4account($username,$pw,$pt) === FALSE) {
			$this->session->set_flashdata ( 'info', 'MT4帐号无效<br />请检查：1、服务器是否选择错误。2、MT4帐号密码是否已过期（修改MT4帐号密码后需重新绑定MT4帐号）');
			redirect('user/result');
		}
		$bgurl=site_url('user/kqrv');
		$my_id = date('YmdHis') . mt_rand(0,10000);
		$data = array(
				'username'=>$username,
				'merchant'=>$merchant,
				'amount'=>$amount,
				'bank'=>$bank,
				'remark'=>$remark,
				'bgurl'=>$bgurl,
				'my_id'=>$my_id,
				);
		//写入订单
		$params = array(
				'account'=>$username,
				'amount'=>$amount,
				'my_id'=>$my_id,
				'server_id'=>$server,
				'time'=>date('Y-m-d H:i:s'),
				);
		$this->load->model('depositmodel');
		$pay = $this->depositmodel->add($params);
		$this->load->view('kq',$data);
	}
	
	//快钱回调
	public function kqrv(){
		$my_config = require (APPPATH . 'config/mail.php');
		$flag = 0;//是否成功更新MT4余额
		$status = "";
		set_time_limit(0);
		ini_set('date.timezone','PRC');
		
		function kq_ck_null($kq_va, $kq_na) {
			if ($kq_va == "") {
				return $kq_va = "";
			} else {
				return $kq_va = $kq_na . '=' . $kq_va . '&';
			}
		}
		
		// 人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
		$kq_check_all_para = kq_ck_null($_REQUEST ['merchantAcctId'], 'merchantAcctId');
		// 网关版本，固定值：v2.0,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['version'], 'version');
		// 语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['language'], 'language');
		// 签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['signType'], 'signType');
		// 支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['payType'], 'payType');
		// 银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['bankId'], 'bankId');
		// 商户订单号，,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['orderId'], 'orderId');
		// 订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['orderTime'], 'orderTime');
		// 订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['orderAmount'], 'orderAmount');
		// 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['dealId'], 'dealId');
		// 银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
		$kq_check_all_para .= kq_ck_null($_REQUEST ['bankDealId'], 'bankDealId');
		// 快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
		$kq_check_all_para .= kq_ck_null($_REQUEST ['dealTime'], 'dealTime');
		// 商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['payAmount'], 'payAmount');
		// 费用，快钱收取商户的手续费，单位为分。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['fee'], 'fee');
		// 扩展字段1，该值与提交时相同
		$kq_check_all_para .= kq_ck_null($_REQUEST ['ext1'], 'ext1');
		// 扩展字段2，该值与提交时相同。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['ext2'], 'ext2');
		// 处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
		$kq_check_all_para .= kq_ck_null($_REQUEST ['payResult'], 'payResult');
		// 错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
		$kq_check_all_para .= kq_ck_null($_REQUEST ['errCode'], 'errCode');
		
		$trans_body = substr($kq_check_all_para, 0, strlen($kq_check_all_para) - 1);
		$MAC = base64_decode($_REQUEST ['signMsg']);
		
		$fp = fopen ( FCPATH . 'public/pay/99bill.cert.rsa.20140728.cer', "r" );
		$cert = fread($fp, 8192);
		fclose($fp);
		$pubkeyid = openssl_get_publickey($cert);
		$ok = openssl_verify($trans_body, $MAC, $pubkeyid);
		
		if ($ok == 1) {
			switch ($_REQUEST ['payResult']) {
				case '10' :
					// 支付成功，业务逻辑处理
					$rtnOK = 1;
					$rtnUrl = site_url('user/show') . '?msg=success';
					
					$my_id = $_REQUEST['orderId'];//自定义订单号
					$id = $_REQUEST ['dealId'];//快钱交易号
					$r5_Pid = $_REQUEST ['ext1'];//用户字段
					$amount = $_REQUEST ['orderAmount'] / 100; // 分转为元
					$mydate = $_REQUEST ['dealTime'];//交易时间 20130508135432

					//时间判断 当前时间大于订单时间8分钟则不处理
					$d_time = strtotime($mydate);
					if ((time() - $d_time) > (8 * 60)) {
						exit;
					}
		
					// 检查订单是否已处理，防止重复入金
					$this->load->model('depositmodel');
					if($this->depositmodel->is_deposit($id)){
						break;
					}

					//写入redis
					$r_message = '用户<span>' . $r5_Pid . '</span>在线入金<span>￥' . $amount . '</span>---' . $date = date('Y-m-d H:i:s',$d_time);
					$this->save_item_in_redis($r_message);

					//更新订单
					$params=array('order_id'=>$id,'is_ok'=>1);
					$this->depositmodel->changeOrder($params,$my_id);
					$server_name = $this->depositmodel->getServerName($my_id);
					// 更新账户余额
					if($my_config['is_rj']==1){
						$local_port = $this->depositmodel->getServerLocalPort($my_id);
						if ($local_port===FALSE) {
							break;
						}

						$this->load->model('exchange_rate_model');
						$hl = $this->exchange_rate_model->get_exchange_rate();//取汇率
						$huilv = $hl[0]->deposit_rate;
						$deposit_factor=$hl[0]->deposit_factor;
						$params ['login'] = $r5_Pid;
						$params ['value'] = round($amount * $deposit_factor / $huilv, 2);
						$params ['comment'] = "D online $id";

						$this->load->model('usermodel');
						$data = $this->usermodel->changeBlance($params,$local_port);
						$result = $this->parseMT4Answer($data);
						if ($result['success']===TRUE || (isset($result['']) && is_null($result['']))) {
							$status .= "更新交易账户余额成功。\r\n";
							$flag = 1;
						}else{
							$status .= $result['reason'] . "，本次入金未更新至账户，请手动处理。\r\n";
						}
					}else{
						$flag=2;//未开启此功能
					}
		
					//更新订单
					$params=array('is_success'=>$flag);
					$this->depositmodel->changeOrder($params,$my_id);
					
					// 发送邮件通知
					if ($my_config['is_open'] == 1) {
						$this->load->library('email');
						$config['protocol'] = 'smtp';
						$config['smtp_host'] = $my_config['email']['host'];
						$config['smtp_user'] = $my_config['email']['username'];
						$config['smtp_pass'] = $my_config['email']['password'];
						$config['smtp_port'] = 25;
						$config['charset'] =$my_config['email']['charset'];
						$config['crlf'] ="\r\n";
						$config['newline']="\r\n";
						$config['wordwrap'] = TRUE;			
						$this->email->initialize($config);
						
						$this->email->from($my_config['email']['username'], 'Admin');
						$this->email->to($my_config['list']);
						
						if ($flag===1) {
							$subject = "在线入金通知[更新成功]";
						} elseif($flag===0) {
							$subject = "在线入金通知[更新失败-$status]";
						}else{
							$subject = "在线入金通知";
						}
						$content = "账户ID：$r5_Pid\r\n" . "金额(￥)：$amount\r\n" . "服务器：$server_name\r\n" .
							"订单号：$id\r\n" . "时间：" . date('Y-m-d H:i:s',strtotime($mydate)) . "\r\n与MT4服务器交互状态：\r\n" . $status;
						$this->email->subject($subject);
						$this->email->message($content);
						
						$this->email->send();
					}
					break;
				default :
					$rtnOK = 1;
					// 支付失败
					$rtnUrl = site_url('user/show') . '?msg=false';
					break;
			}
		} else {
			$rtnOK = 1;
			// 验证签名失败
			$rtnUrl = site_url('user/show') . '?msg=error';
		}
		$vdata = array('rtnOK'=>$rtnOK,'rtnUrl'=>$rtnUrl);
		$this->load->view('kqrv',$vdata);
	}
	
	public function show(){
		$msg = html_escape($_GET['msg']);
		$data=array('msg'=>$msg);
		$this->load->view('show',$data);
	}
	/**
	 * 系统公告
	 */
	public function message($id = 0){
		$id += 0;

		$data = array('title' => '系统公告');
		$this->load->model('messagemodel');
		$this->load->model('usermessagemodel');
		if ($id === 0) {
			//显示公告列表
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
					'url'=>'user/message',
			);
			$data ['pageStr'] = $this->getPageStr ( $params );
			//公告列表页面
			$this->load->view('messagelist', $data);
		} else {
			//显示单条公告
			$data['message'] = $this->messagemodel->getMessage($id);
			if (!$data['message']) {
				$this->session->set_flashdata ( 'info', '公告不存在' );
				redirect ( '/user/info/' );
			}
			//是否已读
			if (!$this->usermessagemodel->isReaded($this->session->userdata('user_id'),$id)) {
				//未读则写入已读item
				$this->usermessagemodel->insertItem($this->session->userdata('user_id'),$id);
			}
			$this->load->view('messagesingle', $data);
		}
	}
	/**
	 * 邮件发送
	 * @param  array $my_config 邮件类配置信息
	 * @param  string $subject   邮件主题
	 * @param  string $content   邮件内容
	 * @param  string $toList    收件人列表
	 * @return boolean           成功返回TRUE，失败返回FALSE
	 */
	private function sendEmail($my_config,$subject,$content,$toList='')
	{
		$this->load->library('email');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $my_config['email']['host'];
		$config['smtp_user'] = $my_config['email']['username'];
		$config['smtp_pass'] = $my_config['email']['password'];
		$config['smtp_port'] = 25;
		$config['charset'] =$my_config['email']['charset'];
		$config['crlf'] ="\r\n";
		$config['newline']="\r\n";
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);
		
		$this->email->from($my_config['email']['username'], 'Admin');
		if($toList===''){
			$toList = $my_config['list'];
		}
		$this->email->to($toList);
		$this->email->subject($subject);
		$this->email->message($content);
		
		return $this->email->send();
	}

	/**********************************************************/
	
	public function view($page = 'about'){
		if ($page === 'about') {
			$this->load->view ( 'pages/about.php' ,array('title'=>'关于'));
		} else if ($page === 'contact') {
			$this->load->view ( 'pages/contact.php' ,array('title'=>'联系'));
		}
	}

	public function balance($login='',$local_port=''){
		if ($login === '' || $local_port === '') {
			echo "error";
			exit();
		}
		$this->load->model('usermodel');
		$allAcc = $this->usermodel->getMT4Account();
		$flag = FALSE;
		foreach ($allAcc as $v) {
			if (in_array($login, $v)) {
				$flag = TRUE;
			}
		}
		if (!$flag) {
			exit('非法操作');
		}
		
		$data = $this->usermodel->getmargininfo($login,$local_port);
		$data = $this->parseMT4Answer($data);

		//上面获取的balance其实是净值（包含信用额度），非余额
		$balanceData = $this->usermodel->getBalance($login,$local_port);
		$balanceData = $this->parseMT4Answer($balanceData);

		if ($data['success'] !== TRUE || $balanceData['success'] !== TRUE) {
			//echo '<span class="margin">error</span>';
			echo 'error';
		} else {
			//echo "<span class=\"margin\">总余额：" . $data['balance'] . "|有效保证金：" . $data['freeMargin'] . '</span>';
			echo $data['equity'] . '|' . $data['freeMargin'] . '|' . $balanceData['balance'] . '|' . ($data['balance'] - $balanceData['balance']);
		}
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