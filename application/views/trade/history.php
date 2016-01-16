<?php if(isset($history_order) && !empty($history_order)){
	foreach ($history_order as $v) { ?>
	<tr class="info">
		<td><?php echo $v[1] ?></td>
		<td><?php echo date('Y-m-d H:i',$v[7]) ?></td>
		<td><?php 
		switch ($v[12]) {
			case '0':
			$v[12]='BUY';
			break;
			case '1':
			$v[12]='SELL';
			break;
			case '2':
			$v[12]='BUY_LIMIT';
			break;
			case '3':
			$v[12]='SELL_LIMIT';
			break;
			case '4':
			$v[12]='BUY_STOP';
			break;
			case '5':
			$v[12]='SELL_STOP';
			break;
			case '6':
			$v[12]='BALANCE';
			break;
			case '7':
			$v[12]='CREDIT';
			break;
			default:
			$v[12]='UNKNOW';
			break;
		}
		echo $v[12]; ?></td>
		<td><?php echo $v[6]/100 ?></td>
		<td><?php echo $v[2] ?></td>
		<td><?php echo $v[3] ?></td>
		<td><?php echo $v[13] ?></td>
		<td><?php echo $v[14] ?></td>
		<td><?php echo date('Y-m-d H:i',$v[8]) ?></td>
		<td><?php echo $v[4] ?></td>
		<td><?php echo $v[10] ?></td>
		<td><?php echo $v[5] ?></td>
	</tr>
	<?php }} ?>