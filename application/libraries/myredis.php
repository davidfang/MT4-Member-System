<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyRedis{
	private $ins = null;

	public function __construct($params= array('ip'=>'127.0.0.1',
		'port'=>6379)){
		require_once './include/Predis/Autoloader.php';
		Predis\Autoloader::register();

		try{
			$this->ins = new Predis\Client();
			$this->ins->connect($params['ip'],$params['port']);
		}catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function getIns(){
		return $this->ins;
	}

	/**
	 * 单一操作指定时间内最多允许操作次数
	 * @param  [type] $key    key
	 * @param  [type] $expire 过期时间：分钟
	 * @param  [type] $times  次数
	 * @return [type]         void
	 */
	public function multiTry($key,$expire,$times){
		if (!is_integer($expire) || !is_integer($times)) {
			return false;
		}
		$r = $this->getIns();
		if(!$r->exists($key)){
			$r->setex($key,$expire*60,1);
			return true;
		}else{
			if ($r->get($key)>=$times) {
				return false;
			}else{
				$r->incr($key);
				return true;
			}
		}
	}

	/**
	 * 检测账户日出金量限制
	 * @param  integer $key mt4账户联合加密key生成的key
	 * @param integer $amount 本次提交出金量
	 * @param integer $maxAmount 最大出金量
	 * @return bool 超出返回true，反之返回false
	 */
	public function check_withdraw_per_day($key,$amount,$maxAmount){
		if ($amount>$maxAmount) {
			return true;
		}

		$r = $this->getIns();
		if(!$r->exists($key)){
			$r->set($key,$amount*100);//转成分来处理，避免浮点数问题
			$r->expireat($key,mktime(0,0,0,date('m'),date('d')+1,date('Y')));
			return false;
		}else{
			$org_amount = $r->get($key);
			if (($org_amount/100) > $maxAmount || (($org_amount/100+$amount)>$maxAmount)) {
				return true;
			}else{
				//$r->incrbyfloat($key,$amount);//2.6.0版才开始支持此命令
				$r->incrby($key,$amount*100);
				return false;
			}
		}
	}

	/**
	 * 单一操作指定时间前最多允许操作次数
	 * @param  [type] $key    key
	 * @param  [type] $expire 过期时间点，时间戳
	 * @param  [type] $times  次数
	 * @return [type]         void
	 */
	public function multiTryAtSpecificTime($key,$expire,$times){
		if (!is_integer($expire) || !is_integer($times)) {
			return false;
		}
		$r = $this->getIns();
		if(!$r->exists($key)){
			$r->set($key,1);
			$r->expireat($key,$expire);
			return true;
		}else{
			if ($r->get($key)>=$times) {
				return false;
			}else{
				$r->incr($key);
				return true;
			}
		}
	}
}