<?php 
/**
* 获取客户端相关信息
*/
class ClientInfo
{
	private $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php';

	public function getClientUserAgent()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
	}

	public function getISPInfo($ip,$format='json')
	{
		$url = $this->url . '?format=' . $format . '&ip=' . $ip;
		$data = json_decode(file_get_contents($url));
		if(is_object($data)){
			if ($data->ret == 1) {
				$info = "国家：{$data->country}-省份：{$data->province}-城市：{$data->city}-详细：{$data->district}-运营商：{$data->isp}-类型：{$data->type}-描述：{$data->desc}";
				return $info;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
}
 ?>