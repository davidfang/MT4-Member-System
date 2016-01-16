<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trade extends My_Controller {

	private $ipAddr = '';//当前请求MT4服务器IP
	private $ipAddrPort = 0;//当前请求webtrader插件端口

	public function __construct(){
		parent::__construct();
		if (!$this->config->item('webtrade')) {
			exit('Access Denied');
		}
		//过滤login参数
		if (isset($_GET['login']) && is_numeric($_GET['login'])) {
			$login = $_GET['login'] + 0;
			$mt4Acc = array();
			if (!isset($_SESSION['user_mt4_account'])) {
				$this->load->model('usermodel');
				$mt4 = $this->usermodel->getMT4Account();

				foreach ($mt4 as $v) {
					$mt4Acc[] = $v['mt4_account'];
				}
				$_SESSION['user_mt4_account'] = json_encode($mt4Acc);
			}else{
				$mt4Acc = json_decode($_SESSION['user_mt4_account'],TRUE);
			}

			if (!in_array($login, $mt4Acc)) {
				exit(json_encode(array('error'=>'illegal operation - login')));
			}
		}
		//过滤服务器参数
		if (isset($_GET['server']) && is_numeric($_GET['server'])) {
			$sid = $_GET['server'] + 0;
			$mySrv = array();
			if (!isset($_SESSION['all_server'])) {
				$this->load->model('ipmodel');
				$server=$this->ipmodel->getIp();
				foreach ($server as $v) {
					$mySrv[$v['ip']. '-' . $v['port']. '-' . $v['params']] = $v['id'];
				}

				$_SESSION['all_server'] = json_encode($mySrv);
			}else{
				$mySrv = json_decode($_SESSION['all_server'],TRUE);
			}

			if (!in_array($sid, $mySrv)) {
				exit(json_encode(array('error'=>'illegal operation - server')));
			}
			//取服务器IP
			foreach ($mySrv as $k=>$v) {
				if ($v == $sid) {
					$sinfo = explode('-', $k);
					$this->ipAddr = $sinfo[0];
					$this->ipAddrPort = $sinfo[2];
				}
			}

		}
	}

	public function index(){
		$this->load->model('usermodel');
		$this->load->model('ipmodel');
		$mt4 = $this->usermodel->getMT4Account();
		$server=$this->ipmodel->getIp();
		if(!$mt4){
			$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
			redirect ( '/user/info/' );
		}
		if (empty($server)) {
			$this->session->set_flashdata ( 'info','未配置服务器' );
			redirect('user/info');
		}
		$main_server_id = 0;
		foreach ($server as $v) {
			if ($v['is_chief'] == 1) {
				$this->ipAddr = $v['ip'];
				$this->ipAddrPort = $v['params'];
				$main_server_id = $v['id'];
			}
		}
		$main_acc = 0;
		foreach ($mt4 as $v) {
			if ($v['is_chief'] == 1) {
				$main_acc = $v['mt4_account'];
			}
		}
		$history_order = $this->getHistory($main_acc,$main_server_id);

		$symbolList = array();
		if(!isset($_SESSION['symbols'])){
			$params['type'] = 0;
			$params['login'] = $main_acc;
			$sd = $this->mt4('getquote',$params);
			$sd = $this->parseMT4Answer($sd);

			if (isset($sd['success']) && $sd['success'] === true && (!empty($sd['servertime']))) {
				unset($sd['success']);
				unset($sd['servertime']);
				foreach ($sd as $k => $v) {
					$symbolList[] = $k;
				}
				$_SESSION['symbol_array'] = $symbolList;
				if (empty($symbolList)) {
					$symbolStr = '[]';
				}else{
					$symbolList = array_map(function($item){
						return '"' . $item . '"';
					}, $symbolList);
					$symbolStr = '[' . implode(',', $symbolList) . ']';
				}
				$_SESSION['symbols'] = $symbolStr;
			}else{
				$this->session->set_flashdata ( 'info','get symbolList error' );
				redirect('user/info');
			}
		}
		//浏览器提示
		$is_ie=FALSE;
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') >= 0) {
			$is_ie = TRUE;
		}

		$data=array('mt4'=>$mt4,
			'server'=>$server,
			'title'=>'在线交易',
			'history_order'=>$history_order,
			'is_ie'=>$is_ie,
			);
		$data['history_order'] = $this->load->view('trade/history',$data,TRUE);
		$this->load->view('trade/index',$data);
	}

	public function chart(){
		if(!isset($_SESSION['symbols'])){
			redirect('trade/index');
		}
		if (!isset($_GET['login']) || !is_numeric($_GET['login'])) {
			$this->load->model('usermodel');
			$mt4 = $this->usermodel->getMT4Account();
			if(!$mt4){
				$this->session->set_flashdata ( 'info', '获取绑定MT4帐号出错或还未绑定MT4帐号，请先绑定MT4帐号' );
				redirect ( '/user/info/' );
			}
			foreach ($mt4 as $v) {
				if ($v['is_chief'] == 1) {
					$_GET['login'] = $v['mt4_account'];
				}
			}
		}

		if (!isset($_GET['server']) || !is_numeric($_GET['server'])) {
			$this->load->model('ipmodel');
			$server=$this->ipmodel->getIp();
			if(!$server){
				$this->session->set_flashdata ( 'info', '未配置服务器' );
				redirect ( '/user/info/' );
			}
			foreach ($server as $v) {
				if ($v['is_chief'] == 1) {
					$_GET['server'] = $v['id'];
				}
			}
		}

		$data = array('title'=>'行情查看',
			'login'=>$_GET['login'],
			'server'=>$_GET['server'],
			);
		$this->load->view('trade/chart',$data);
	}

	public function getServerId($acc,$show=TRUE){
		$acc = $acc + 0;
		if ($acc===0) {
			$data['error']='invalid params';
			echo json_encode($data);
			exit();
		}

		$this->load->model('usermodel');
		$mt4 = $this->usermodel->getMT4Account();
		$result = array();
		foreach ($mt4 as $v) {
			if ($v['mt4_account']==$acc) {
				$result[]=$v['server_id'];
			}
		}
		if (empty($result)) {
			$data['error'] = 'unknow account';
			echo json_encode($data);
			exit();
		}
		$data['error'] = '';
		$data['id'] = $result;
		if ($show) {
			echo json_encode($data);
		}else{
			return json_encode($data);
		}
	}

	//获取历史订单信息。
	public function getHistory($login='',$server=0,$is_show=0){
		if (!is_numeric($login) || !is_numeric($server) || $login<=0 || $server<=0) {
			exit("invalid params");
		}
		$this->load->model('usermodel');
		$this->load->model('ipmodel');
		$res = $this->ipmodel->getIp($server);
		if (!$res) {
			exit("get server error!");
		}
		$server = $res['local_port'];
		$to = time()+1*24*60*60;
		$params = array(
			'login'=>$login,
			'from'=>$to - 30*24*60*60,
			'to'=>$to,
			);
		$history_order = array();
		$data = $this->usermodel->getHistory($params,$server);
		$data = ($data[0][0] == 'result' && $data[0][1] == 1 && $data[2][1] != 0) ? $data[2][1] : array();
		if (!empty($data)) {
			$orders = explode("\n", $data);
			unset($orders[0]);
			foreach ($orders as $v) {
				$history_order[] = explode(';', $v);
			}
		}
		date_default_timezone_set('UTC');
		if ($is_show==1) {
			$data = array('history_order'=>$history_order);
			$this->load->view('trade/history',$data);
		}else{
			return $history_order;
		}
	}

	//获取MT4用户信息
	public function getAccountInfoByItem($login='',$item='leverage'){
		$this->load->model('usermodel');
		$params = array(
			'login'=>$login,
			);
		return $this->usermodel->getAccountInfo($params,7781);
	}


	public function externalchart(){
		$data = array('title'=>'行情查看');
		$this->load->view('trade/externalchart',$data);
	}

	public function getData(){
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		$server = isset($_GET['server']) ? $_GET['server'] : 0;
		$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : '';
		$login = isset($_GET['login']) ? $_GET['login'] : '';

		$this->load->model('usermodel');
		if (!isset($_SESSION['time_zone']) || ($_SESSION['get_time_zone'] === false)) {
			$params=array('login'=>$login);
			$data = $this->mt4('getTimeZone',$params);
			$data = $this->parseMT4Answer($data);

			if (isset($data['success']) && $data['success'] === true) {
				$_SESSION['time_zone']=$data['time'] + intval($this->config->item('phone_time_zone'));
				$_SESSION['get_time_zone'] = true;
			}else{
				$_SESSION['time_zone']=0;
				$_SESSION['get_time_zone'] = false;
			}
		}

		if ($action==='quotes') {
			$params['type'] = 0;
			$params['login'] = $login;
			$data = $this->mt4('getquote',$params);
			$data = $this->parseMT4Answer($data);

			if (isset($data['success']) && $data['success'] === true) {
				$time = $_SESSION['time_zone'] * 60 * 60;
				unset($data['success']);
				$servertimets=$data['servertime'];
				unset($data['servertime']);
				foreach ($data as $k => $v) {
					$price = explode('|', $v);
					if (!isset($_SESSION[$k . '__'])) {
						$list['direction'] = 'up';
					}else{
						$list['direction'] = $price[0] >= $_SESSION[$k . '__'] ? 'up' : 'down';
					}
					$_SESSION[$k . '__'] = $price[0];
					$list['symbol'] = $k;
					$list['bid'] = $price[1] == 0 ? $price[1] : strval(floatval($price[1]));
					$list['ask'] = $price[0] == 0 ? $price[0] : strval(floatval($price[0]));
					$list['time'] = date('Y.m.d H:i:s',$price[2]-$time);
					$list['timestamp'] = $price[2]-$time;
					$jsData[]=$list;
				}
				session_write_close();
				$p['rows'] = $jsData;
				$p['total'] = count($jsData);
				$final['error'] = '';
				$final['severtime'] = date('Y.m.d H:i:s',$servertimets);
				$final['data'] = $p;
				$final['severtimets']=$servertimets;
			}else{
				$final['error'] = 'error';
			}
			echo json_encode($final);

		}elseif($action === 'orderlist'){
			$time = $_SESSION['time_zone'] * 60 * 60;
			$params['login'] = $login;
			session_write_close();
			$data = $this->mt4('getopenedtradesforlogin',$params);
			$data = $this->parseMT4Answer($data);
			if (isset($data['success']) && $data['success']===true) {
				$order_list = explode('|', $data['orders']);
				foreach ($order_list as $k => $v) {
					$order_data = explode(';', $v);
					$sData['order'] = intval($order_data[0]);
					$sData['login'] = $order_data[1];
					$sData['symbol'] = $order_data[2];
					$sData['cmd'] = intval($order_data[3]);
					$sData['volume'] = floatval($order_data[4]);
					$sData['open_time'] = date('Y-m-d H:i:s',intval($order_data[5])-$time);
					$sData['open_price'] = floatval($order_data[6]);
					$sData['sl'] = floatval($order_data[7]);
					$sData['tp'] = floatval($order_data[8]);
					if ($order_data[3] == 0 || $order_data[3] == 1) {
						$sData['profit'] = floatval($order_data[10]);
					} else {
						$sData['profit'] = 0;
					}

					if($sData['open_price'] != 0 && $sData['login'] == $params['login']){
						$jsData[] = $sData;
						$orde[] = $sData['order'];
					}
				}
				$p['rows'] = $jsData;
				$p['total'] = intval($data['traders']);
				$p['orde'] = $orde;
				$final['error'] = '';
				$final['data'] = $p;
			}else{
				$final['error'] = 'error';
			}
			echo json_encode($final);
			
		}elseif ($action==='symbolinfo' && $symbol!=='') {
			session_write_close();
			$params['symbol'] = $symbol;
			$data = $this->mt4('getSymbolInfo',$params);
			$data = $this->parseMT4Answer($data);
			if (isset($data['success']) && $data['success']===true) {
				$final['error'] = '';
				$final['data'] = $data;
			}else{
				$final['error'] = 'error';
			}
			echo json_encode($final);
		}elseif ($action==='historyquotes' && $symbol!=='') {
			session_write_close();
			$t = time();
			$period = isset($_GET['period']) ? $_GET['period'] : 15;
			$from = isset($_GET['from']) ? $_GET['from'] : $t-7*24*60*60;
			$to = isset($_GET['to']) ? $_GET['to'] : $t;
			$from += $_SESSION['time_zone']*60*60;
			$to += $_SESSION['time_zone']*60*60;
			$params['symbol'] = $symbol;
			$params['period'] = $period;
			$params['from'] = $from;
			$params['to'] = $to;
			$data = $this->mt4('historyquotes',$params);
			$data = $this->parseMT4Answer($data);

			if (isset($data['success']) && $data['success']===true) {
				$price_list = explode('|', $data['data']);
				foreach ($price_list as $v) {
					$price_data = explode(';', $v);
					$p = array_map("intval", $price_data);
					$jsData[] = $p;
				}
				$final['error'] = '';
				$final['symbol'] = $symbol;
				$final['period'] = $period;
				$final['digits'] = $data['digits'];
				$final['data'] = $jsData;
				$final['currentprice'] = $jsData[count($jsData)-1][4];
			}else{
				$final['error'] = 'error';
			}
			echo json_encode($final);
		}elseif ($action==='userinfo') {
			$params['login'] = $login;
			session_write_close();
			$data = $this->mt4('getmargininfo',$params);
			$data = $this->parseMT4Answer($data);

			if (isset($data['success']) && $data['success'] === true) {
				unset($data['success']);
				$final['data'] = $data;
				$final['error'] = '';
			}else{
				$final['error'] = isset($data['result']) ? $data['result'] : 'error';
			}
			echo json_encode($final);
		}
	}

	function mtTrade(){
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		$volume = isset($_GET['volume']) ? $_GET['volume'] : 0;
		$price  = isset($_GET['price']) ? $_GET['price'] : 0;
		$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : '';
		$symbol = iconv('utf-8', 'gbk', $symbol);
		$cmd    = isset($_GET['cmd']) ? $_GET['cmd'] : '';
		$sl     = isset($_GET['sl']) ? $_GET['sl'] : 0;
		$tp     = isset($_GET['tp']) ? $_GET['tp'] : 0;
		$server = isset($_GET['server']) ? $_GET['server'] : 0;
		$orderid= isset($_GET['order']) ? $_GET['order'] : '';
		$comment= isset($_GET['comment']) ? $_GET['comment'] : '';
		$login = isset($_GET['login']) ? $_GET['login'] : '';
		$multiAcc = isset($_GET['multiAcc']) ? urldecode($_GET['multiAcc']) : FALSE;

		if ($action !== '') {
			if ($action === 'neworder' || $action === 'pendingorder') {
				if ($volume == 0 || $price == 0) {
					$final['error'] = ':volume or price is invalid';
				}else{
					if ($multiAcc) {
						$multiAccOrder = $this->config->item('multiAccOrder');
						if (!in_array($this->session->userdata('username'), $multiAccOrder)) {
							$final['error'] = '权限不足。请联系管理员开通多账号下单权限。';
							echo json_encode($final);
							exit();
						}
						$login = explode('#', $multiAcc);
					}else{
						$login = array($login);
					}
					foreach ($login as $v) {
						$params['login'] = $v;
						$params['symbol'] = $symbol;
						$params['volume'] = $volume;
						$params['price'] = $price;
						$params['sl'] = $sl;
						$params['tp'] = $tp;
						$params['comment'] = iconv('utf-8','gbk',$comment);
						$params['cmd'] = $cmd;
						if($multiAcc){
							//账号过滤
							$mt4Acc = json_decode($_SESSION['user_mt4_account'],TRUE);
							if (!in_array($v, $mt4Acc)) {
								$final['error'] = 'illegal operation - ' . $v;
								break;
							}
							//多账号下单服务器获取处理
							$cur_serv = json_decode($this->getServerId($v,FALSE),TRUE)['id'];
							if (count($cur_serv) > 1) {
								$final['error'] = '账号' . $v . '对应多个服务器';
								break;
							}
							$sid = $cur_serv[0];
							$mySrv = json_decode($_SESSION['all_server'],TRUE);
							foreach ($mySrv as $k=>$v) {
								if ($v == $sid) {
									$sinfo = explode('-', $k);
									$this->ipAddr = $sinfo[0];
									$this->ipAddrPort = $sinfo[2];
								}
							}
						}

						$data = $this->mt4('createorder',$params);
						$data = $this->parseMT4Answer($data);
						if (isset($data['success']) && $data['success'] === true && isset($data['OrderID'])
							&& (($data['OrderID']+0)>0) ) {
							$final['error'] = '';
						}else{
							$final['error'] = isset($data['result']) ? $data['result'] . ' server error' : 'error';
						}
					}
				}	
				echo json_encode($final);
			}elseif ($action==='closeorder' && $orderid !== '' && is_numeric($orderid)) {
				$params['login'] = $login;
				session_write_close();
				//是否是自己的订单
				$myOrders = $this->getOrdersById($params['login']);
				if (!in_array($orderid, $myOrders)) {
					exit(json_encode(array('error'=>'illegal operation')));
				}

				$params['orderid'] = $orderid;
				$data = $this->mt4('closeorder',$params);
				$data = $this->parseMT4Answer($data);

				if (isset($data['success']) && $data['success'] === true) {
					unset($data['success']);
					$final['error'] = '';
				}else{
					$final['error'] = isset($data['result']) ? $data['result'] : 'error';
				}
				echo json_encode($final);
			}elseif ($action==='updateorder' && $orderid !== '' && is_numeric($orderid)) {
				if (($sl<0) || ($tp<0)) {
					$final['error'] = 'invalid sl or tp value';
					echo json_encode($final);
					exit();
				}
				//是否是自己的订单
				$myOrders = $this->getOrdersById($login);
				session_write_close();
				if (!in_array($orderid, $myOrders)) {
					exit(json_encode(array('error'=>'illegal operation')));
				}

				$params['orderid'] = $orderid;
				$params['price'] = $price;
				$params['sl'] = $sl;
				$params['tp'] = $tp;
				$data = $this->mt4('updateOrder',$params);
				$data = $this->parseMT4Answer($data);

				if (isset($data['success']) && $data['success'] === true) {
					$final['error'] = '';
				}else{
					$final['error'] = isset($data['reason']) ? $data['reason'] : 'error';
				}
				echo json_encode($final);
			}
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

	private function mt4($action,$params,$server_ip='',$webtrader_port=''){
		$port = $webtrader_port === '' ? $this->ipAddrPort : $webtrader_port;
		$ip = $server_ip === '' ? $this->ipAddr : $server_ip;
		$this->load->library('mt4datareciver');
		$this->mt4datareciver->sethashandkey();
		$this->mt4datareciver->OpenConnection($ip,$port);
		$answerData =$this->mt4datareciver->MakeRequest($action, $params);
		$answerData = iconv('gbk', 'utf-8', $answerData);
		$this->mt4datareciver->CloseConnection();
		if(substr($answerData, 0,8)!='result=1'){
			//file_put_contents('log.txt', $ip . '|' . $action . '|' . json_encode($params) . '|' . $answerData . '|' . date('Y-m-d H:i:s') . "\r\n",FILE_APPEND);
		}
		$resultData = explode('&',$answerData);
		foreach ($resultData as $v){
			$data[]=explode('=',$v);
		}
		return $data;
	}

	/**
	 * 获取用户所有未平仓订单
	 *
	 * array (size=2)
	 * 0 => int 21081
	 * 1 => int 21082
	 * 
	 * @param  string $login mt4账号ID
	 * @return mixed        成功返回数组（含所有订单ID），失败返回false
	 */
	private function getOrdersById($login){
		if(!is_numeric($login)){
			return FALSE;
		}
		$params['login'] = $login;
		$data = $this->mt4('getopenedtradesforlogin',$params);
		$data = $this->parseMT4Answer($data);
		if (isset($data['success']) && $data['success']===true) {
			$order_list = explode('|', $data['orders']);
			foreach ($order_list as $k => $v) {
				$order_data = explode(';', $v);
				$sData[] = intval($order_data[0]);
			}
			return $sData;
		}else{
			return FALSE;
		}
	}
}