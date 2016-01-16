<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-19
*/
class common extends CI_Controller{
	public function index(){
		$this->load->view('common/index');
	}

	public function trends(){
		$result = $this->get_my_log();
		if ($result===false) {
			echo '0';
		}else{
			echo json_encode($this->get_my_log());
		}
	}
	/**
	 * 获取动态出入金、转账记录
	 * @return mixed 成功返回对应记录，失败返回false
	 */
	private function get_my_log()
	{
		require_once './include/Predis/Autoloader.php';
		Predis\Autoloader::register();

		try{
			$r = new Predis\Client();
			$r->connect('127.0.0.1',6379);

			$my_log_len = $r->llen($this->config->item('encryption_key'));
			return $my_log_len == 0 ? false : $r->lrange($this->config->item('encryption_key'),0,$my_log_len-1);
		}catch(Exception $e){
			return false;
		}
	}

	public function getRate(){
		define('CHINA_BANK_URL' , 'http://www.boc.cn/sourcedb/whpj/');
		$content = file_get_contents(CHINA_BANK_URL);
		$start = strpos($content,'美元');
		$start = strpos($content,'美元',$start+2);
		$end = strpos($content,'</tr>',$start);
		$key = substr($content,$start,$end-$start);
		preg_match_all('/(?<=<td>).*?(?=<\/td>)/',$key,$matches);
		if (empty($matches[0])) {
			echo 'error';
		}else{
			echo $matches[0][0] . '|' .$matches[0][2];
		}
	}

	public function pwdReset($pkey){
		if ($pkey == '') {
			exit();
		}
		$msg = '';
		$this->session->set_flashdata( 'return_url', site_url('user/login'));

		$this->load->model('resetmodel');
		$email = trim($this->resetmodel->getEmailByKey($pkey));
		if (!$email) {
			$this->session->set_flashdata ( 'info', '链接无效，请检查url地址是否有误' );
			redirect ( 'common/result' );
		}

		//是否已处理
		if ($this->resetmodel->isReset($pkey) === false) {
			//是否过期
			if ($this->resetmodel->isExpired($pkey,30) === false) {
				//更改密码
				$str = '23456789abcdefghjkmnpqrstuvwxyz';
				$str = str_shuffle($str);
				$pwd = substr($str, 0,8);
				$this->load->model('usermodel');
				$result = $this->usermodel->reset_pwd($email,$pwd);
				if ($result) {
					//更新已处理标志
					$this->resetmodel->updateReset($pkey);
					$msg = '重置密码成功，新密码为：<font color="red">' . $pwd . '</font>。请及时登录系统并修改您的密码。';
					
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
					$config['mailtype'] = 'html';		
					$this->email->initialize($config);
					
					$this->email->from($my_config['email']['username'], 'Admin');
					$this->email->to($email);
					$this->email->subject("密码重置成功 - " . $this->config->item('company_name'));
					$this->email->message($msg);
					
					$this->email->send();
				} else {
					$msg = '重置密码失败，请重试';
				}
			} else {
				$msg = '链接已过期，请重新提交密码重置申请';
			}
		} else {
			$msg = '链接已被处理过，请勿重复提交';
		}

		$this->session->set_flashdata ( 'info', $msg );
		redirect ( 'common/result' );
	}

	public function result() {
		$this->load->view ( 'result' );
	}

	public function home($refName='') {
		if($refName && strlen($refName) < 100) {
			$track_time = $this->config->item('track_time');
			$track_time = (is_integer($track_time) && ($track_time > 0) ? $track_time : 60);
			$this->input->set_cookie('refName', $refName, $track_time * 60);
		}
		redirect($this->config->item('company_url'));
	}

	public function backup() {
		$this->load->dbutil();
		$backup = & $this->dbutil->backup(array(
			'format'=>'zip',
			'filename'=>'backup.zip',
			));

		$this->load->helper('file');
		$backup_file = 'c:\backup.zip';
		write_file($backup_file,$backup);

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
		$this->email->initialize($config);
		
		$backup_email = 'eddy@rrgod.com';
		$this->email->from($my_config['email']['username'], 'Admin');
		$this->email->to($backup_email);
		
		$this->email->subject('数据库备份 - ' . date('Y-m-d H:i:s'));
		$this->email->attach($backup_file);
		$this->email->message('backup');
		
		$this->email->send();
	}
}
?>