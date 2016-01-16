<?php $this->load->view('admin/header')?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

		<div class="span10">
		
		<script type="text/javascript">
		$(function(){
		$("#check_all").click(function(){
            $('input[name="checkbox"]').prop("checked",$(this).prop("checked"));
        });
    
	   	$("#delete").click(function(){
	   		var v =$('input[name="checkbox"]:checked');
	   		var pdata = '';
	   		v.each(function(){
	   			pdata += this.value+ '#';
	   		});
	   		if(pdata === ''){
	   			alert("请先选择要删除的记录！");
	   		}else{
	   			if(!confirm("确定要删除？")){
					return false;
				}
	   	       $.post("<?php echo site_url('admin/agent/del')?>",{id:pdata,tb:"agent"},
	   			function(data){
	   				var msg ="";
	   				if(data == "1"){
                        msg="删除成功！";
                        //$('input[name="checkbox"]:checked').parent().parent().hide(1000);
                        $('input[name="checkbox"]:checked').parent().parent().remove();
                	}else if(data =="0"){
                		msg="删除失败！";
                	}else{
                        msg="未知错误！";
                    }
                    $("#result").show().html(msg).delay(1000).hide(1000);
                }
                );
           }
       });
		});
		</script>
<?php
$attr = array (
		'method' => 'post',
		'class' => 'form-inline' 
);
echo form_open ( 'admin/agent/add', $attr );
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
			<label>代理名称：</label>
			<input type="text" class="input-small" id="server_name" name="server_name"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>代理ID：</label>
			<input type="text" class="input-small" id="ip" name="ip"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>组名称：</label>
			<input type="text" id="port" name="port" class="input-small"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>备注：</label>
			<input type="text" id="local_port" name="local_port"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<button type="submit" class="btn" id="add">添加</button>
			<button type="button" class="btn" id="delete">删除选中记录</button>
<?php echo form_close()?>
<script type="text/javascript">
		function checkForm(){
			var server_name = $("#server_name").val();
			var ip = $("#ip").val();
			var port = $("#port").val();
			var local_port = $("#local_port").val();

			if(server_name == "" || ip == "" || port == "" || local_port==""){
				alert("数据未填写完整");
				return false;
			}
			
			var p = /^[0-9]{1,5}$/;
			if(p.test(port)){
				if(port<0 || port >65535 || local_port<0 || local_port >65535){
					//alert("端口号应在0-65535之间");
					//return false;
				}
			}else{
				//alert("端口号应为正整数值");
				//return false;
			}
			
			var p2 = /^(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))(\.(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))){3}$/;
			if(!p2.test(ip)){
				//alert("ip地址非法");
				//return false;
			}
		}
		$("#add").click(checkForm);
</script>
<!-- 服务器信息 -->
            <?php
            if (! empty ( $data )) {
														?>
														<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="check_all" id="check_all" />
				</th>
				<th>序号</th>
				<th>代理名称</th>
				<th>代理ID</th>
				<th>组名称</th>
				<th>备注</th>
			</tr>
		</thead>
		<?php foreach ( $data as $v ) { ?>
        <tr>
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['agent_name'] ?></td>
			<td><?php echo $v['agent_id'] ?></td>
			<td><?php echo $v['group_name'] ?></td>
			<td><?php echo $v['comment'] ?></td>
		</tr>
                <?php }
echo '</table>';
 }?>

<span class="label label-warning">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
1.如果未启用真实账户自动开户，或配置为自动生成组名，则无需进行代理设置<br/>
2.添加代理信息前，请确保代理MT4账户及对应的组已存在
</div>

</div>

	</div>

<?php $this->load->view('admin/footer')?>