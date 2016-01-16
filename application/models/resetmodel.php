<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-6-10
*/

/**
* 密码重置
*/
class ResetModel extends CI_Model
{
	/**
	 * 新增记录
	 * @param array $data 待插入数据
	 */
	public function add($data) {
		$this->db->insert('reset',$data);
		return $this->db->insert_id();
	}

	/**
	 * 根据重置key获取email
	 * @param  string $key key
	 * @return string      email
	 */
	public function getEmailByKey($key) {
		$query = $this->db->select('email')->where('pkey',$key)->limit(1)->get('reset');
		if ($query) {
			$data =$query->row_array();
			return isset($data['email']) ? $data['email'] : '';
		} else {
			return '';
		}
	}

	/**
	 * 重置链接是否过期
	 * @param  string  $key      key
	 * @param  integer  $miniutes 时间
	 * @return boolean
	 */
	public function isExpired($key,$miniutes) {
		$time = date('Y-m-d H:i:s',time() - $miniutes * 60);
		$query = $this->db->select('id')->where(
			array(
			'pkey' => $key,
			'time >=' => $time)
			)->limit(1)->get('reset');
		if ($query) {
			return ($query->num_rows() > 0 ? false : true);
		} else {
			return 1;
		}
	}

	/**
	 * 更新是否已重置标志
	 * @param  string  $key      key
	 * @return [type] [description]
	 */
	public function updateReset($key) {
		return $this->db->where('pkey',$key)->update('reset',array('is_reset'=>1));
	}

	/**
	 * 是否已重置
	 * @param  string  $key      key
	 * @return boolean
	 */
	public function isReset($key) {
		$query = $this->db->select('is_reset')->where('pkey',$key)->limit(1)->get('reset');
		if ($query) {
			$data =$query->row_array();
			return ($data['is_reset'] == 1 ? true : false);
		} else {
			return 1;
		}
	}
}
 ?>