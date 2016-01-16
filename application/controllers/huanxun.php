<?php 
/**
 * 环迅支付文件
 * 
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com
 */

/**
 * 环迅支付控制器类
 *
 * 实现环迅支付的提交与回调
 * @package admin.payment
 * @since 1.0
 */
class HuanXun extends MY_Controller{
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
		$pay = $this->paymentmodel->getMerchant('huanxun');
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

		/*$SignMD5=md5('000015' . 'GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ');
		$client = new SoapClient('http://webservice.ips.net.cn/web/Service.asmx?wsdl');

		$p = new param('000015',$SignMD5);
		$res = $client->__soapCall('GetBankList',array($p));
		$arr = explode('#',urldecode($res->GetBankListResult));*/
		$arr = Array(
			"北京银行|北京银行|00050",
			"广东发展银行|银行卡支付(全国范围)|00052",
			"广州市农村信用社|麒麟借记卡(广州地区)|00011",
			"广州市商业银行|羊城万事顺卡借记卡(广州地区)|00011",
			"华夏银行|华夏借记卡(全国范围)|00041",
			"交通银行|太平洋卡(全国范围)|00005",
			"民生银行|民生卡(全国范围)|00013",
			"平安银行|银行卡支付(全国范围)|00006",
			"浦东发展银行|东方卡(全国范围)|00032",
			"上海农村商业银行|如意借记卡(上海地区)|00030",
			"深圳发展银行|发展卡支付(全国范围)|00023",
			"深圳市农村商业银行|借记卡(深圳地区)|00011",
			"顺德农村信用合作社|顺德信用合作社借记卡(顺德地区)|00011",
			"兴业银行|在线兴业(全国范围)|00016",
			"邮政储蓄|邮政储蓄(全国范围)|00051",
			"招商银行|银行卡支付(全国范围)|00021",
			"中国工商银行|工行手机支付(仅限工行手机签约客户)|00026",
			"中国工商银行|网上签约注册用户(全国范围)|00004",
			"中国光大银行|银行卡支付(全国范围)|00057",
			"中国建设银行|网上银行签约客户(全国范围)|00003",
			"中国农业银行|网上银行签约客户(全国范围)|00017",
			"中国银行|银行卡支付(全国范围)|00083",
			"中信银行|银行卡支付(全国范围)|00054",
			"", 
			);
		unset($arr[count($arr)-1]);
		$bankList = array();
		foreach ($arr as $k => $v) {
			$d = explode('|', $v);
			$bankList[$d[0]] = $d[2];
		}
		
		$data=array('mt4'=>$mt4,'server'=>$server,'title'=>'在线入金','bankList'=>$bankList);
		$this->load->view('huanxun',$data);
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
		$bgurl=site_url('huanxun/receive');
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
		$this->load->view('huanxun_submit',$data);
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

		$billno = $_GET['billno'];
		$amount = $_GET['amount'];
		$mydate = $_GET['date'];
		$succ = $_GET['succ'];
		$msg = $_GET['msg'];
		$attach = $_GET['attach'];
		$ipsbillno = $_GET['ipsbillno'];
		$retEncodeType = $_GET['retencodetype'];
		$currency_type = $_GET['Currency_type'];
		$signature = $_GET['signature'];

		$ipsbanktime = $_GET['ipsbanktime'];

		$ar = explode('#',$attach);
		$r5_Pid = isset($ar[0])?trim($ar[0]):'';
		//'----------------------------------------------------
		//'   Md5摘要认证
		//'   verify  md5
		//'----------------------------------------------------

		//RetEncodeType设置为17（MD5摘要数字签名方式）
		//交易返回接口MD5摘要认证的明文信息如下：
		//billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】
		//例:(billno000001000123currencytypeRMBamount13.45date20031205succYipsbillnoNT2012082781196443retencodetype17GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)

		//返回参数的次序为：
		//billno + mercode + amount + date + succ + msg + ipsbillno + Currecny_type + retencodetype + attach + signature + bankbillno
		//注2：当RetEncodeType=17时，摘要内容已全转成小写字符，请在验证的时将您生成的Md5摘要先转成小写后再做比较
		$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
		//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
		$cert = $this->merchantKey;
		$signature_1ocal = md5($content . $cert);

		if ($signature_1ocal == $signature){
			if ($succ == 'Y') {
				// 支付成功，业务逻辑处理
				$returnMsg	= $amount."元 支付成功！";
						
				$id = $ipsbillno;//交易号
				$my_id = $billno;//订单号

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
				if ((time() - strtotime($ipsbanktime)) > (5 * 60)) {
					$params=array('order_id'=>$id,'is_ok'=>1);
					$this->depositmodel->changeOrder($params,$my_id);
					exit('超时');
				}
		
				//检查订单是否已处理，防止重复入金
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
				echo $returnMsg;
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

/**
 * 获取银行列表用
 */
class param
{
    public $MerCode;
    public $SignMD5;

	public function __construct($Mer_code,$SignMD5){
		$this->MerCode=$Mer_code;
		$this->SignMD5=$SignMD5;
	}
}
?>