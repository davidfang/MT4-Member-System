<?php $this->load->view('header')?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
<?php $this->load->view('index_left')?>
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
	   	       $.post("<?php echo site_url('user/mt4/del')?>",{id:pdata,tb:"server"},
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
	            <?php echo '"' . site_url('user/mt4/ch') . '"';?>,
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
echo form_open ( 'user/mt4/add', $attr );
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
			<label>MT4帐号：</label>
			<input type="text" id="username" name="username" class="input-small"
				placeholder="MT4交易帐号"><span class="help-inline"><font color="red">*</font></span>
			<label>密码：</label>
			<input type="password" id="password" name="password" class="input-small"
				placeholder="MT4帐号登陆密码"><span class="help-inline"><font color="red">*</font></span>
			<label>服务器</label>
			<select id="server" name="server">
				<option value="">请选择服务器</option>
				<?php foreach ($server as $v){?>
				<option value="<?php echo $v['id']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
				<?php }?>
			</select><span class="help-inline"><font color="red">*</font></span>
			<button type="submit" class="btn" id="add">绑定</button>
			<button type="button" class="btn" id="delete">删除选中记录</button>
<?php echo form_close()?>
<script type="text/javascript">
		function checkForm(){
			var username = $.trim($("#username").val());
			var password = $("#password").val();
			var server = $("#server").val();

			if(username == "" || password == "" || server == ""){
				alert("数据未填写完整");
				return false;
			}
			
			var p = /^[0-9]{1,15}$/;
			if(!p.test(username)){
				alert("帐号应为正整数值");
				return false;
			}
			return true;
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
				<th>MT4帐号</th>
				<th>密码</th>
				<th>服务器</th>
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
			<td><?php echo $v['mt4_account'] ?></td>
			<td><?php echo substr($v['password'],0,3) . '***' ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['is_chief'] == 1 ?
			'<a href="#" title="单击设置为主帐号" onclick="return changeHK(this)">主帐号</a>' :
			'<a href="#" title="单击设置为主帐号" onclick="return changeHK(this)">副帐号</a>';
			echo '<a href="' . site_url('user/mt4/edit/' . $v['id']) .'" style="margin-left:6px">编辑</a>'; ?></td>
		</tr>
                <?php }
echo '</table>';
 }?>
<span class="label label-info">温馨提示</span>
<div class="alert alert-info" style="margin-top:20px">
<p>只允许绑定真实MT4账户，不支持模拟MT4账户绑定</p>
<p>如果绑定MT4账户后修改了MT4账户的密码，必须在此删除该MT4账户后重新进行绑定，或编辑该MT4账户更新密码！</p>
</div>
</div>

	</div>

<?php $this->load->view('footer')?>