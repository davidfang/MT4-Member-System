<?php 
/**
 * 汇潮支付文件
 * 
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com
 */

/**
 * 汇潮支付控制器类
 *
 * 实现汇潮支付的提交与回调
 * @package admin.payment
 * @since 1.0
 */
class HuiChao extends MY_Controller{
	/**
	 * 商户ID
	 * 
	 * @var string
	 */
	private $p1_MerId;

	/**
	 * 商户密钥
	 * 
	 * @var string
	 */
	private $merchantKey;

	/**
	 * 构造函数，获取商户ID及KEY
	 *
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		header('Content-Type:text/html;charset=utf-8');
		//error_reporting(E_ALL);
		$this->load->model('paymentmodel');
		$pay = $this->paymentmodel->getMerchant('huichao');
		if($pay){
			$this->p1_MerId = $pay[0]['merchant'];
			$this->merchantKey = $pay[0]['secret'];
		}else{
			$this->p1_MerId = '';
			$this->merchantKey = '';
		}
	}

	/**
	 * 加载支付页面
	 *
	 * @access public
	 */
	public function index(){
		if ($this->p1_MerId === '') {
			echo 'get merchant error';
			exit();
		}
		$this->load->model('usermodel');
		$this->load->model('ipmodel');
		$mt4 = $this->usermodel->getMT4Account();
		$server=$this->ipmodel->getIp();
		$data=array('mt4'=>$mt4,'server'=>$server,'title'=>'在线入金');
		$this->load->view('huichao',$data);
	}

	/**
	 * 提交支付信息
	 *
	 * @access public
	 */
	public function submit(){
		$username=trim($this->input->post('username'));//MT4帐号
		$amount=$this->input->post('amount');//金额
		$bank=$this->input->post('bank');//银行
		$remark=trim($this->input->post('remark'));//备注
		$server=$this->input->post('server');//服务器

		$this->beforeSubmit($amount);
		$this->berfore_deposition($username);
		
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
		$bgurl=site_url('huichao/receive');
		$my_id = date('YmdHis') . mt_rand(0,10000);//订单号
		$data = array(
				'username'=>$username,
				'p1_MerId'=>$this->p1_MerId,
				'merchantKey'=>$this->merchantKey,
				'amount'=>$amount,
				'bank'=>$bank,
				'remark'=>$remark,
				'bgurl'=>$bgurl,
				'my_id'=>$my_id,
				'server'=>$server,
				'ct'=>&$this,
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
		$this->load->view('huichao_submit',$data);
	}

	/**
	 * 保存记录至redis
	 * @access private
	 * @param  string $value 保存值
	 * @return boolean       成功返回true，失败返回false
	 */
	private function save_item_in_redis($value='')
	{
		require './include/Predis/Autoloader.php';
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

	public function receive(){
		$my_config = require (APPPATH . 'config/mail.php');
		$flag = 0;//是否成功更新MT4余额
		$status = "";
		set_time_limit(0);
		ini_set('date.timezone','PRC');

		//MD5私钥
		$MD5key = $this->merchantKey;
		//订单号
		$BillNo = $_POST["BillNo"];
		//金额
		$Amount = $_POST["Amount"];
		//支付状态
		$Succeed = $_POST["Succeed"];
		//支付结果
		$Result = $_POST["Result"];
		//取得的MD5校验信息
		$MD5info = $_POST["MD5info"]; 
		//备注
		//$Remark = $_POST["Remark"];

		//校验源字符串
  		$md5src = $BillNo.$Amount.$Succeed.$MD5key;
  		//MD5检验结果
		$md5sign = strtoupper(md5($md5src));
		
		$r2_TrxId=$BillNo;
		$r3_Amt=$Amount;
		//$r5_Pid=$Remark;//此处需要自己手动从数据库中获取mt4账户
		//调试用
		//file_put_contents('./log.log', date('Y-m-d H:i:s') . json_encode($_POST) . "\r\n");
		
		$r6_Order=$BillNo;
		
		//$rp_PayDate=$_REQUEST['rp_PayDate'];
		//$ru_Trxtime=$_REQUEST['ru_Trxtime'];//时间戳

		if ($MD5info==$md5sign){
			
			if ($Succeed=='88') {
				// 支付成功，业务逻辑处理
				$returnMsg	= $r3_Amt."元 支付成功！";
				echo $returnMsg;
						
				$id = $r2_TrxId;//交易号
				$my_id = $r6_Order;//订单号
				$amount = $r3_Amt; 

				//时间判断 当前时间大于订单时间8分钟则不处理
				// if ((time() - $mydate) > (8 * 60)) {
				// 	exit('超时');
				// }
				$this->load->model('depositmodel');
		
				// 检查订单是否已处理，防止重复入金
				$this->depositmodel->lockTable();
				if($this->depositmodel->is_deposit($id)){
					$this->depositmodel->unlockTable();
					exit('订单已处理');
				}
				//更新订单
				$params=array('order_id'=>$id,'is_ok'=>1);
				$this->depositmodel->changeOrder($params,$my_id);
				$this->depositmodel->unlockTable();

				//校验金额
				$myOrderAmount = $this->depositmodel->getAmountByMyId($my_id);
				if ($myOrderAmount) {
					if ($myOrderAmount != $amount) {
						exit('非法操作：返回金额和本地记录不符');
					}
				}else{
					exit('获取本地订单金额出错');
				}

				//获取订单对应的MT4账户
				$r5_Pid = $this->depositmodel->getMT4ByMyId($my_id);
				if ($r5_Pid === FALSE) {
					file_put_contents('./log.log', date('Y-m-d H:i:s') . ": get mt4 account error\r\n",FILE_APPEND);
				}

				//写入redis
				$r_message = '用户<span>' . $r5_Pid . '</span>在线入金<span>￥' . $amount . '</span>---' . $date = date('Y-m-d H:i:s');
				$this->save_item_in_redis($r_message);

				$server_name = $this->depositmodel->getServerName($my_id);
				// 更新账户余额
				if(($my_config['is_rj']==1) && ($r5_Pid !== FALSE)){
					$local_port = $this->depositmodel->getServerLocalPort($my_id);
					if ($local_port===FALSE) {
						exit('获取服务器出错');
					}

					$this->load->model('exchange_rate_model');
					$hl = $this->exchange_rate_model->get_exchange_rate();//取汇率
					$huilv = $hl[0]->deposit_rate;
					//识别多币种账户
					$rmb_account = trim($my_config['rmb_account']);
					if ( $rmb_account != '') {
						if(preg_match($rmb_account, trim($r5_Pid)) > 0){
							//是人民币账户
							$huilv = 1;
						}
					}
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
				}elseif($r5_Pid === FALSE){
					$status .= '获取MT4帐号失败' . "，本次入金未更新至账户，请手动处理。\r\n";
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
						"订单号：$id\r\n" . "与MT4服务器交互状态：\r\n" . $status;
					$this->email->subject($subject);
					$this->email->message($content);
					
					$this->email->send();
				}
			}else{
				echo "<hr>交易失败";
				//支付错误信息提示
				switch ($Succeed) {
					case '22':
						echo '交易网址不正确';
						break;
					case '26':
						echo '金额超过限定值';
						break;
					case '3':
						echo '交易金额超过单笔限额';
						break;
					case '4':
						echo '本月交易金额超过月限额';
						break;
					default:
						echo '未知错误，请咨询汇潮支付技术人员。错误代码：' . $Succeed;
				}
				exit;
			}
		}else{
			echo "交易信息被篡改";
			exit;
		}
	}
}
?>