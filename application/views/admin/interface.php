<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-inline');
echo form_open('admin/payment/add',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo validation_errors();}?>
</div>
<label for="username">名称</label>
		<?php 
		$data = array('name'=>'payname','id'=>'payname','maxlength'=>'20','class'=>"input-small");
		echo form_input($data);
		?>
<span class="help-inline"><font color="red">*</font></span>

	<label for="username">商户号</label>
		<?php 
		$data = array('name'=>'username','id'=>'username','maxlength'=>'30','class'=>"input-small");
		echo form_input($data);
		?>
<span class="help-inline"><font color="red">*</font></span>
	<label for="password">密钥</label>
		<?php 
		$data = array('name'=>'password','id'=>'password');
		echo form_input($data);
		?>

	<label for="payment_type">类型</label>
		<?php 
		$data = array('kuaiqian'=>'快钱',
			'bibao'=>'币宝',
			'yibao'=>'易宝',
			'huichao'=>'汇潮',
			'huanxun'=>'环迅',
			'zhifu'=>'智付',
			);
		echo form_dropdown('payment_type',$data);
		?>
<span class="help-inline"><font color="red">*</font></span>

		<?php 
		$data = array('name'=>'login_submit','id'=>'login_submit','value'=>'添加','class'=>'btn');
		echo form_submit($data);
		?>
		<button type="button" class="btn" id="delete">删除选中记录</button>

<?php echo form_close()?>
<script type="text/javascript">
	$(function(){
		function checkForm(){
			var username = $("#username").val();
			var payname = $("#payname").val();

			if(username == "" || payname==""){
				alert("商户号/名称不能为空");
				return false;
			}
		}
		$("#login_submit").click(checkForm);

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
	   	       $.post("<?php echo site_url('admin/payment/del')?>",{id:pdata},
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
	        <?php echo '"' . site_url('admin/payment/change') . '"';?>,
	        {"id": id},
	        function() {
		        window.location.reload();
	            return false;
	        }
	    );
	}
</script>

<!-- 服务器信息 -->
            <?php
            if (! empty ( $list )) {
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
				<th>商户号</th>
				<th>密钥</th>
				<th>状态</th>
			</tr>
		</thead>
		<?php foreach ( $list as $v ) { ?>
                    <tr 
					<?php 
                    $c =$v['enabled'] == 1 ? 'info' : '';
                    echo 'class="' . $c . '"';?>
                    >
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['merchant'] ?></td>
			<td><?php echo strlen($v['secret']) <= 9 ? $v['secret'] : substr($v['secret'], 0,3) . '***' . substr($v['secret'],strlen($v['secret'])-3) ?></td>
			<td><?php echo $v['enabled'] == 1 ? '<a href="#" title="单击启用该接口" onclick="return changeHK(this)">已启用</a>' : '<a href="#" title="单击启用该接口" onclick="return changeHK(this)">未启用</a>' ?></td>
		</tr>
                <?php }
echo '</table>';
 }?>

</div>

</div>

<?php $this->load->view('admin/footer')?>