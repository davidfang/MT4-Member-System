<?php 
/**
 * 智付支付文件
 * 
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com
 */

/**
 * 智付支付控制器类
 *
 * 实现智付支付的提交与回调
 * @package admin.payment
 * @since 1.0
 */
class Zhifu extends MY_Controller{
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
		$pay = $this->paymentmodel->getMerchant('zhifu');
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
		$this->load->view('zhifu',$data);
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
		$bgurl=site_url('zhifu/receive');
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
		$this->load->view('zhifu_submit',$data);
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

		//商号
		$merchant_code	= $_POST["merchant_code"];

		//通知类型
		$notify_type = $_POST["notify_type"];

		//通知校验ID
		$notify_id = $_POST["notify_id"];

		//接口版本
		$interface_version = $_POST["interface_version"];

		//签名方式
		$sign_type = $_POST["sign_type"];

		//签名
		$dinpaySign = $_POST["sign"];

		//商家订单号
		$order_no = $_POST["order_no"];

		//商家订单时间
		$order_time = $_POST["order_time"];

		//商家订单金额
		$order_amount = $_POST["order_amount"];

		//回传参数
		$extra_return_param = $_POST["extra_return_param"];

		//智付交易定单号
		$trade_no = $_POST["trade_no"];

		//智付交易时间
		$trade_time = $_POST["trade_time"];

		//交易状态 SUCCESS 成功  FAILED 失败
		$trade_status = $_POST["trade_status"];

		//银行交易流水号
		$bank_seq_no = $_POST["bank_seq_no"];


		/**
		 *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
		*同时将商家支付密钥key放在最后参与签名，组成规则如下：
		*参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
		**/

		//组织订单信息
		$signStr = "";
		if($bank_seq_no != "") {
			$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
		}
		if($extra_return_param != "") {
		    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
		}
		$signStr = $signStr."interface_version=V3.0&";
		$signStr = $signStr."merchant_code=".$merchant_code."&";
		if($notify_id != "") {
		    $signStr = $signStr."notify_id=".$notify_id."&notify_type=offline_notify&";
		}

	        $signStr = $signStr."order_amount=".$order_amount."&";
	        $signStr = $signStr."order_no=".$order_no."&";
	        $signStr = $signStr."order_time=".$order_time."&";
	        $signStr = $signStr."trade_no=".$trade_no."&";
	        $signStr = $signStr."trade_status=".$trade_status."&";

		if($trade_time != "") {
		     $signStr = $signStr."trade_time=".$trade_time."&";
		}
		$key=$this->merchantKey;
		$signStr = $signStr."key=".$key;
		$signInfo = $signStr;
		//将组装好的信息MD5签名
		$sign = md5($signInfo);
		//echo "sign=".$sign."<br>";

		//比较智付返回的签名串与商家这边组装的签名串是否一致

		$returnMsg = '';
		$pay_amount = 0;
		if ($dinpaySign == $sign){
			$pay_amount = $order_amount;
			if ($trade_status == 'SUCCESS'){
				// 支付成功，业务逻辑处理
				$returnMsg	= '支付成功';
						
				$id = $trade_no;//交易号
				$my_id = $order_no;//订单号
				$amount = $order_amount;//金额

				//时间判断 当前时间大于订单时间8分钟则不处理
				// if ((time() - $mydate) > (8 * 60)) {
				// 	exit('超时');
				// }
				$this->load->model('depositmodel');
				//校验金额
				$myOrderAmount = $this->depositmodel->getAmountByMyId($my_id);
				if ($myOrderAmount) {
					if ($myOrderAmount != $amount) {
						exit('非法操作：返回金额和本地记录不符');
					}
				}else{
					exit('获取本地订单金额出错');
				}
		
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
				$returnMsg	= '支付失败';
			}
		}else{
			$returnMsg	= '支付失败（交易信息被篡改）';
		}

		$this->load->view('zhifu_result',array('result'=>$returnMsg,'amount'=>$pay_amount));
	}

	private function HexToStr($hex)
	{
		$string="";
		for ($i=0;$i<strlen($hex)-1;$i+=2)
			$string.=chr(hexdec($hex[$i].$hex[$i+1]));
		return $string;
	}
}
?>