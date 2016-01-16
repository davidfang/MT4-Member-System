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
	   	       $.post("<?php echo site_url('admin/server/del')?>",{id:pdata,tb:"server"},
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

		function changeHK(th) {
	        var id = $(th).parent().parent().children().eq(1).text();
	        $(th).text("处理中，请稍等！");
	        $(th).load(
	            <?php echo '"' . site_url('admin/modify/ip') . '"';?>,
	            {"id": id},
	            function() {
		            window.location.reload();
	                return false;
	            }
	        );
	    }
		</script>
<?php
$attr = array (
		'method' => 'post',
		'class' => 'form-inline' 
);
echo form_open ( 'admin/server/add', $attr );
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
			<label>名称：</label>
			<input type="text" class="input-small" id="server_name" name="server_name"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>IP地址：</label>
			<input type="text" class="input-small" id="ip" name="ip"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>端口号：</label>
			<input type="text" id="port" name="port" class="input-small"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>本地端口号：</label>
			<input type="text" id="local_port" name="local_port" class="input-small"
				placeholder=""><span class="help-inline"><font color="red">*</font></span>
			<label>WebTrader：</label>
			<input type="text" id="WebTraderPort" name="WebTraderPort" class="input-small"
				placeholder="没有请留空">
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
					alert("端口号应在0-65535之间");
					return false;
				}
			}else{
				alert("端口号应为正整数值");
				return false;
			}
			
			var p2 = /^(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))(\.(([01]?[\d]{1,2})|(2[0-4][\d])|(25[0-5]))){3}$/;
			if(!p2.test(ip)){
				alert("ip地址非法");
				return false;
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
				<th>名称</th>
				<th>Ip地址</th>
				<th>端口号</th>
				<th>本地端口号</th>
				<th>WebTrader</th>
				<th>操作</th>
			</tr>
		</thead>
		<?php foreach ( $data as $v ) { ?>
                    <tr <?php 
                    $c =$v['is_chief'] == 1 ? 'info' : '';
                    echo 'class="' . $c . '"';?>>
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['ip'] ?></td>
			<td><?php echo $v['port'] ?></td>
			<td><?php echo $v['local_port'] ?></td>
			<td><?php echo $v['params'] ?></td>
			<td><?php echo $v['is_chief'] == 1 ? '<a href="#" title="单击设置为默认服务器" onclick="return changeHK(this)">默认服务器</a>' : '<a href="#" title="单击设置为默认服务器" onclick="return changeHK(this)">其它服务器</a>' ?><span style="margin:0 6px">|</span><a href="<?php echo site_url('admin/edit/server/' . $v['id']); ?>">编辑</a></td>
		</tr>
                <?php }
echo '</table>';
 }?>

<span class="label label-warning">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
注意：IP地址和本地端口一经设置，其对应关系不能更改，否则会造成会员系统数据混乱！！！
</div>

</div>

	</div>

<?php $this->load->view('admin/footer')?>