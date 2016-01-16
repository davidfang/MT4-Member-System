<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('admin/modify/pwd',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<div class="control-group">
	<label class="control-label" for="password_old">旧密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_old','id'=>'password_old','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font></span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password_new">新密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_new','id'=>'password_new','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>密码长度至少6位</span>
	</div>
</div>
<div class="control-group">
	<label class="control-label" for="password_new_p">确认新密码</label>
	<div class="controls">
		<?php 
		$data = array('name'=>'password_new_p','id'=>'password_new_p','maxlength'=>'20');
		echo form_password($data);
		?>
		<span class="help-inline"><font color="red">*</font>请再次填写新密码</span>
	</div>
</div>
<div class="control-group">
	<div class="controls">
		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'修改','class'=>'btn');
		echo form_submit($data);
		?>
	</div>
</div>
<?php echo form_close()?>
<hr>
<script type="text/javascript" src="<?php echo base_url()?>public/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/modules/exporting.js"></script>
<div>时间间隔
	<select type="text" id="char_type">
	<option value="y">按年分组统计</option>
	<option value="m">按月分组统计</option>
	<option value="w">按周分组统计</option>
	</select>
</div>
<div id="chart_container" style="min-width: 400px; height: 400px; margin: 0 auto">
</div>
<hr>
<span class="label label-warning">推广注册</span>
<div class="alert alert-info" style="margin-top:20px">
	<p>通过下面任一链接注册的会员将成为代理id为<font color="red">agentid</font>的客户，将红色字体部分替换成具体的代理ID，分配给对应的代理即可</p>
<p>1.<?php echo site_url('user/reg') . '/<font color="red">agentid</font>'; ?></p>
<p>2.<?php echo site_url('common/home') . '/<font color="red">agentid</font>'; ?></p>
<p>备注：链接1为直接跳转到注册页面，链接2为跳转到官网首页，实际使用中根据具体情况自行选择</p>
</div>
<script type="text/javascript">
	$(function(){
		$("option[value=<?php echo $type; ?>]").attr("selected",true);
		$("#char_type").change(function(){
			window.location.href= <?php echo '"' . site_url('admin/index') . '/"'; ?> + $(this).val();
		});
		function checkForm(){
			var pwd_o = $("#password_old").val();
			var pwd_n = $("#password_new").val();
			var pwd_n_p = $("#password_new_p").val();

			if(pwd_o == "" || pwd_n == ""){
				alert("密码不能为空");
				return false;
			}
			if(pwd_o.length<6 || pwd_n.length<6){
				alert("密码长度至少为6位");
				return false;
			}
			if (pwd_n !== pwd_n_p) {
				alert("新密码两次输入不一致");
				return false;
			}
			if (pwd_n === pwd_o) {
				alert("新密码与旧密码相同，无需修改");
				return false;
			}
		}
		$("#login_submit").click(checkForm);
			var chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_container',
				type: 'line',
				marginRight: 130,
				marginBottom: 25,
				borderWidth: 1,
			},
			title: {
				text: '平台出金/入金走势图',
				x: -20 //center
			},
			labels: {
                items: [{
                    html: '合计<br />入金：￥<?php echo $sumDeposition; ?><br />出金：￥<?php echo $sumWithdraw; ?><br />结余：￥<?php echo $sumDeposition-$sumWithdraw; ?>',
                    style: {
                        left: '40px',
                        top: '1px',
                        color: 'blue'
                    }
                }]
            },
			subtitle: {
				text: '<?php echo $this->config->item("company_name") ?>',
				x: -20
			},
			xAxis: {
				categories: <?php echo $dp_x; ?>
			},
			yAxis: {
				title: {
					text: '金额'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				min:0
			},
			tooltip: {
				crosshairs: true,
                shared: true,
                /*
				formatter: function() {
						return '<b>'+ this.series.name +'</b><br/>'+
						this.x +': '+ this.y;
				}*/
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [{
				name: '入金',
				data: <?php echo $dp_y; ?>
			}, {
				name: '出金',
				data: <?php echo $wr_y; ?>
			}]
		});
		});
</script>