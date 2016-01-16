<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
*/
class UserMt4AccountModel extends CI_Model
{
	
	function getMt4AccountById($id, $userId=0)
	{
		$userId += 0;
		if ($userId === 0) {
			$query = $this->db->where('id',$id)->get('user_mt4account');
		} else {
			$query = $this->db->where(array('id'=>$id,'user_id'=>$userId))->get('user_mt4account');
		}
		
		return $query->row_array();
	}

	function updateMt4AccountById($id,$params)
	{
		return $this->db->where('id',$id)->update('user_mt4account',$params);
	}
}