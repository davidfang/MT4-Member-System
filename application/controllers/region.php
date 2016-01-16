<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-19
*/
class region extends CI_Controller{
	public function index($type=0,$parent=0){
		$this->load->model('regionmodel');
		$list = $this->regionmodel->get_regions($type,$parent);
		$data = array('list'=>$list,'type'=>$type);
		echo $this->load->view('region',$data,TRUE);
	}
}
?>