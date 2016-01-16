<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-2
*/
class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		header('Content-Type:text/html;charset=utf-8');
		//$this->output->enable_profiler(TRUE);
		// 判断是否登录，排除login、reg、add页面
		$sec = $this->uri->segment ( 2 );
		if ($sec !== 'receive' && $sec !== 'relevance' && $sec !== 'mt4login' && $sec !== 'real' && $sec !== 'login' && $sec !== 'getpwd' && $sec !== 'vcode' && $sec !== 'reg' && $sec !== 'add' && $sec !== 'kqrv' && $sec !== 'info' && ! $this->is_login ()) {
			redirect ( 'user/login' );
		}
	}

	//是否登录
	private function is_login() {
		$CI = &get_instance();
		if (isset($CI->session->userdata['username']) && isset ( $_SESSION['login'] )) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function beforeSubmit($amount){
		$data = require (APPPATH . 'config/mail.php');
		//金额限制
		if ($amount<$data['min_amount'] || $amount>$data['max_amount']) {
			$this->session->set_flashdata ( 'info', '单笔入金量不得小于 <font color="red">' . 
				$data['min_amount'] . '</font> RMB，不得大于<font color="red">' . 
				$data['max_amount'] . '</font> RMB。');
			redirect('user/info');
		}
	}

	public function berfore_deposition($mt4Acc){
		$data = require (APPPATH . 'config/deposit.php');

		//检测入金时间
		$s_t = mktime($data['deposit_time']['start'],0,0,date('m'),date('d'),date('Y'));
		$e_t = mktime($data['deposit_time']['end'],0,0,date('m'),date('d'),date('Y'));
		if (($e_t < time() || time() < $s_t) && !in_array($mt4Acc, $data['withdraw_time_exception'])) {
			$this->session->set_flashdata ( 'info', '当前时间禁止入金。请在每日'.$data['deposit_time']['start'].
				'时至'.$data['deposit_time']['end'].'时入金' );
			redirect ( '/user/info/' );
		}
	}

	public function before_withdraw($mt4Acc,$amount){
		$data = require (APPPATH . 'config/deposit.php');
		$this->load->library('myredis');

		//检测出金黑名单
		$black_list = $data['withdraw_blacklist'];
		if (in_array($mt4Acc, $black_list)) {
			$this->session->set_flashdata ( 'info', '该账号已被禁止出金' );
			redirect ( '/user/info/' );
		}

		//检测出金时间
		$s_t = mktime($data['withdraw_time']['start'],0,0,date('m'),date('d'),date('Y'));
		$e_t = mktime($data['withdraw_time']['end'],0,0,date('m'),date('d'),date('Y'));
		if (($e_t < time() || time() < $s_t) && !in_array($mt4Acc, $data['withdraw_time_exception'])) {
			$this->session->set_flashdata ( 'info', '当前时间禁止出金。请在每日'.$data['withdraw_time']['start'].
				'时至'.$data['withdraw_time']['end'].'时出金' );
			redirect ( '/user/info/' );
		}

		if(!in_array($mt4Acc, $data['withdraw_max_amount_exception'])){
			//检测单笔出金额度
			if ($amount > $data['withdraw_max_amount_single_transaction']) {
				$this->session->set_flashdata ( 'info', '超出单笔出金额度：' .  $data['withdraw_max_amount_single_transaction'] . '元');
				redirect ( '/user/info/' );
			}

			//检测日出金额度
			$key = $_SESSION['login'] . '|maxWithdrawAmountPerDay|' . $this->config->item('encryption_key');
			if ($this->myredis->check_withdraw_per_day($key,$amount,$data['withdraw_max_amount_per_day'])) {
				$this->session->set_flashdata ( 'info', '超出每日总出金额度：' .  $data['withdraw_max_amount_per_day'] . '元' );
				redirect ( '/user/info/' );
			}
		}

		//检测出金次数限制
		$key = $_SESSION['login'] . '|withdrawTimes|' . $this->config->item('encryption_key');
		$expire = strtotime(date('Y-m-d',strtotime('+1 day')) . ' 00:00:00');
		$times = $data['withdraw_max_times_per_day'];
		if (($times > 0) && !$this->myredis->multiTryAtSpecificTime($key,$expire,$times)) {
			$this->session->set_flashdata ( 'info', '一个会员账户一天最多可操作' . 
				$data['withdraw_max_times_per_day'] . '次出金，请稍后再试');
			redirect ( '/user/info/' );
		}
	}
}
?>