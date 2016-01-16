<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2014-5-16
*/
class UserMessageModel extends CI_Model{
	private $table;
	public function __construct(){
		parent::__construct();
		$this->table = $this->db->dbprefix('user_message');
	}

	/**
	 * 获取用户已读消息数
	 * @param  integer $userId 用户ID
	 * @return integer         已读消息数
	 */
	public function getReadedMessageCount($userId){
		$query = $this->db->query("select count(*) as msgNum from {$this->table} where user_id = {$userId}");
		return $query->row_array()['msgNum'];
	}

	/**
	 * 查询指定消息制定用户是否已读
	 * @param  integer  $userId    用户ID
	 * @param  integer  $messageId 消息ID
	 * @return boolean            已读返回true，未读返回false
	 */
	public function isReaded($userId,$messageId){
		$query = $this->db->select('user_id')->where(
			array(
			'user_id'=>$userId,
			'message_id'=>$messageId))->get('user_message');	
		if ($query === false) {
			return true;
		}
		$result = $query->result_array();
		if (empty($result)) {
			return false;
		} else {
			return true;
		}
	}

	public function insertItem($userId,$messageId){
		return $this->db->insert('user_message',array('user_id'=>$userId,'message_id'=>$messageId));
	}
}
?>