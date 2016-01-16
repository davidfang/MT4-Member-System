<?php
/**
 * @author Eddy eddy@rrgod.com
 * @link http://www.rrgod.com Eddy Blog
 * 2013-4-19
*/
?>
<option value="0" selected>
<?php 
switch ($type){
	case 0:
		echo '请选择国家';
		break;
	case 1:
		echo '请选择省';
		break;
	case 2:
		echo '请选择市';
		break;
	default:
		echo '未知错误';
}
?>
</option>
<?php foreach ($list as $v){?>
<option value="<?php echo $v['region_id'] ?>"><?php echo $v['region_name'] ?></option>
<?php }?>