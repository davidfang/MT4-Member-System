<?php $this->load->view('admin/header')?>
<div class="container-fluid">

<!-- 入金记录start -->
<script type="text/javascript">
$(function() {
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
	   	       $.post("<?php echo site_url('admin/real/del')?>",{id:pdata},
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
	<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="check_all" id="check_all" />
				</th>
				<th>序号</th>
				<th>姓名</th>
				<th>Email</th>
				<th>密码</th>
				<th>交易倍数</th>
				<th>国家</th>
				<th>省份</th>
				<th>城市</th>
				<th>邮编</th>
				<th>地址</th>
				<th>手机</th>
				<th>服务器</th>
				<th>MT4账户</th>
				<th>会员帐号</th>
			</tr>
		</thead>
            <?php
												if (! empty ( $list )) {
													foreach ( $list as $v ) {
														?>
                    <tr>
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td><?php echo $v['name'] ?></td>
			<td><?php echo $v['email'] ?></td>
			<td><?php echo $v['password'] ?></td>
			<td><?php echo $v['level'] ?></td>
			<td><?php echo $v['country'] ?></td>
			<td><?php echo $v['state'] ?></td>
			<td><?php echo $v['city'] ?></td>
			<td><?php echo $v['zipcode'] ?></td>
			<td><?php echo $v['address'] ?></td>
			<td><?php echo $v['phone'] ?></td>
			<td><?php echo $v['server'] ?></td>
			<td><?php echo $v['mt4_account'] ?></td>
			<td><?php echo $v['username'] ?></td>
		</tr>
                <?php
													
}
												}
												?>
        </table>
        <div style="height:30px;text-align: center;">
    <a href="<?php echo site_url('admin')?>" class="btn btn-primary" >返回</a>
</div>

<?php $this->load->view('admin/footer')?>