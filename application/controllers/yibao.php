<?php 
/**
 * 易宝支付文件
 * 
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com
 */

/**
 * 易宝支付控制器类
 *
 * 实现易宝支付的提交与回调
 * @package admin.payment
 * @since 1.0
 */
class YiBao extends MY_Controller{
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
		$this->load->model('paymentmodel');
		$pay = $this->paymentmodel->getMerchant('yibao');
		if($pay){
			$this->p1_MerId = $pay[0]['merchant'];
			$this->merchantKey = $pay[0]['secret'];
		}else{
			$this->p1_MerId = '';
			$this->merchantKey = '';
		}
	}

	/**
	 * 加密返回字符串
	 * @param  string $r0_Cmd   参照易宝支付协议
	 * @param  string $r1_Code  参照易宝支付协议
	 * @param  string $r2_TrxId 参照易宝支付协议
	 * @param  string $r3_Amt   参照易宝支付协议
	 * @param  string $r4_Cur   参照易宝支付协议
	 * @param  string $r5_Pid   参照易宝支付协议
	 * @param  string $r6_Order 参照易宝支付协议
	 * @param  string $r7_Uid   参照易宝支付协议
	 * @param  string $r8_MP    参照易宝支付协议
	 * @param  string $r9_BType 参照易宝支付协议
	 * @return string           参照易宝支付协议
	 */
	private function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
	{		
		#取得加密前的字符串
		$sbOld = "";
		#加入商家ID
		$sbOld = $sbOld.$this->p1_MerId;
		#加入业务类型
		$sbOld = $sbOld.$r0_Cmd;
		#加入交易结果
		$sbOld = $sbOld.$r1_Code;
		#加入易宝流水号
		$sbOld = $sbOld.$r2_TrxId;
		#加入支付金额
		$sbOld = $sbOld.$r3_Amt;
		#加入交易币种
		$sbOld = $sbOld.$r4_Cur;
		#加入商品名称
		$sbOld = $sbOld.$r5_Pid;
		#加入订单号
		$sbOld = $sbOld.$r6_Order;
		#加入会员ID
		$sbOld = $sbOld.$r7_Uid;
		#加入交易扩展信息
		$sbOld = $sbOld.$r8_MP;
		#加入交易结果返回类型
		$sbOld = $sbOld.$r9_BType;
		
		return $this->HmacMd5($sbOld,$this->merchantKey);
	}

	private function HmacMd5($data,$key)
	{

		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;

		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}

	/**
	 * 校验提交与返回参数
	 * @param string $r0_Cmd   参照易宝支付协议
	 * @param string $r1_Code  参照易宝支付协议
	 * @param string $r2_TrxId 参照易宝支付协议
	 * @param string $r3_Amt   参照易宝支付协议
	 * @param string $r4_Cur   参照易宝支付协议
	 * @param string $r5_Pid   参照易宝支付协议
	 * @param string $r6_Order 参照易宝支付协议
	 * @param string $r7_Uid   参照易宝支付协议
	 * @param string $r8_MP    参照易宝支付协议
	 * @param string $r9_BType 参照易宝支付协议
	 * @param bool $hmac     成功返回true，失败返回false
	 */
	private function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
	{
		if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 生成请求参数的加密结果
	 * @param  string $p0_Cmd          参照易宝支付协议
	 * @param  string $p2_Order        参照易宝支付协议
	 * @param  string $p3_Amt          参照易宝支付协议
	 * @param  string $p4_Cur          参照易宝支付协议
	 * @param  string $p5_Pid          参照易宝支付协议
	 * @param  string $p6_Pcat         参照易宝支付协议
	 * @param  string $p7_Pdesc        参照易宝支付协议
	 * @param  string $p8_Url          参照易宝支付协议
	 * @param  string $pa_MP           参照易宝支付协议
	 * @param  string $pd_FrpId        参照易宝支付协议
	 * @param  string $pr_NeedResponse 参照易宝支付协议
	 * @return string                  参照易宝支付协议
	 */
	public function getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
	{
		$sbOld = "";
		#加入业务类型
		$sbOld = $sbOld.$p0_Cmd;
		#加入商家ID
		$sbOld = $sbOld.$this->p1_MerId;
		#加入订单号
		$sbOld = $sbOld.$p2_Order;
		#加入交易金额
		$sbOld = $sbOld.$p3_Amt;
		#加入货币单位
		$sbOld = $sbOld.$p4_Cur;
		#加入商品名称或者会员名称
		$sbOld = $sbOld.$p5_Pid;
		#加入商品分类
		$sbOld = $sbOld.$p6_Pcat;
		#加入商品描述
		$sbOld = $sbOld.$p7_Pdesc;
		#加入商户接收支付成功数据的地址
		$sbOld = $sbOld.$p8_Url;
		#加入扩展信息
		$sbOld = $sbOld.$pa_MP;
		#加入银行通道
		$sbOld = $sbOld.$pd_FrpId;
		#加入是否需要应答机制
		$sbOld = $sbOld.$pr_NeedResponse;
		
		return $this->HmacMd5($sbOld,$this->merchantKey);
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
		$this->load->view('yibao',$data);
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
		$bgurl=site_url('yibao/receive');
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
		$this->load->view('yibao_submit',$data);
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
		
		$p1_MerId=$_REQUEST['p1_MerId'];
		$r0_Cmd=$_REQUEST['r0_Cmd'];
		$r1_Code=$_REQUEST['r1_Code'];
		$r2_TrxId=$_REQUEST['r2_TrxId'];
		$r3_Amt=$_REQUEST['r3_Amt'];
		$r4_Cur=$_REQUEST['r4_Cur'];
		$r5_Pid=$_REQUEST['r5_Pid'];
		$r6_Order=$_REQUEST['r6_Order'];
		$r7_Uid=$_REQUEST['r7_Uid'];
		$r8_MP=$_REQUEST['r8_MP'];
		$r9_BType=$_REQUEST['r9_BType'];
		$rb_BankId=$_REQUEST['rb_BankId'];
		$ro_BankOrderId=$_REQUEST['ro_BankOrderId'];
		$rp_PayDate=$_REQUEST['rp_PayDate'];//20130614153235
		$hmac=$_REQUEST['hmac'];

		#	判断返回签名是否正确（True/False）
		$bRet = $this->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

		#	校验码正确.
		$returnMsg='';
		if($bRet){
			if($r1_Code=="1"){
				//为“1”: 浏览器重定向;
				//为“2”: 服务器点对点通讯.
				//if($r9_BType=="1"){
					//$returnMsg	= $returnMsg.$r3_Amt."元 支付成功！";
				//}else{
				// 支付成功，业务逻辑处理
				echo "success";
				$returnMsg	= $returnMsg.$r3_Amt."元 支付成功！";
						
				$id = $r2_TrxId;//交易号
				$my_id = $r6_Order;//订单号
				$r5_Pid = $r5_Pid;//用户字段
				$amount = $r3_Amt;
				$mydate = strtotime($rp_PayDate);

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

				//时间判断 当前时间大于订单时间5分钟则不处理
				if((time() - strtotime($rp_PayDate)) > 300){
					$params=array('order_id'=>$id,'is_ok'=>1);
					$this->depositmodel->changeOrder($params,$my_id);
            		exit('订单超时');
        		}
		
				// 检查订单是否已处理，防止重复入金
				if($this->depositmodel->is_deposit($id)){
					exit('订单已处理');
				}

				//写入redis
				$r_message = '用户<span>' . $r5_Pid . '</span>在线入金<span>￥' . $amount . '</span>---' . $date = date('Y-m-d H:i:s',$mydate);
				$this->save_item_in_redis($r_message);

				//更新订单
				$params=array('order_id'=>$id,'is_ok'=>1);
				$this->depositmodel->changeOrder($params,$my_id);
				$server_name = $this->depositmodel->getServerName($my_id);
				// 更新账户余额
				if($my_config['is_rj']==1){
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
						"订单号：$id\r\n" . "时间：" . date('Y-m-d H:i:s',$mydate) . "\r\n与MT4服务器交互状态：\r\n" . $status;
					$this->email->subject($subject);
					$this->email->message($content);
					
					$this->email->send();
				}
			}else{
				echo "交易失败";
				exit;
			}
		}else{
			echo "交易信息被篡改";
			exit;
		}
	}
}
?>