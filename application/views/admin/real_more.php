<?php $this->load->view('admin/header')?>
<div class="container-fluid">
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>字段</th>
				<th>值</th>
			</tr>
		</thead>
		<tr class="info"><td colspan="2">个人资料<?php echo (empty($list['mt4_account']) ? '' : "<font color='red'>(实盘MT4账户：{$list['mt4_account']})</font>"); ?></td></tr>
		<tr>
			<td>姓名</td>
			<td><?php echo $list['first_name'] . $list['last_name'] . $list['sex'] ?></td>
		</tr>
		<tr>
			<td>国籍</td>
			<td><?php echo $list['country'] ?></td>
		</td>
		<tr>
			<td>居住国家</td>
			<td><?php echo $list['country_res'] ?></td>
		</tr>
		<tr>
			<td>省份</td>
			<td><?php echo $list['province'] ?></td>
		</tr>
		<tr>
			<td>城市</td>
			<td><?php echo $list['city'] ?></td>
		</td>
		<tr>
			<td>地址</td>
			<td><?php echo $list['address'] ?></td>
		</tr>
		<tr>
			<td>证件号</td>
			<td><?php echo $list['card'] ?></td>
		</tr>
		<tr>
			<td>出生年月</td>
			<td><?php echo $list['birthday'] ?></td>
		</tr>
		<!-- 联络资料-->
		<tr class="info"><td colspan="2">联络资料</td></tr>
		<tr>
			<td>电话号码</td>
			<td><?php echo $list['phone_code'] . ' - ' . $list['phone'] ?></td>
		</tr>
		<tr>
			<td>手机号码</td>
			<td><?php echo $list['mobile'] ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?php echo $list['email'] ?></td>
		</tr>
		<tr>
			<td>QQ</td>
			<td><?php echo $list['qq'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">帐户性质</td></tr>
		<tr>
			<td>帐户类型：</td>
			<td><?php echo $list['usertype'] ?></td>
		</tr>
		<tr>
			<td>杠杆比例</td>
			<td><?php echo $list['level'] ?></td>
		</tr>
		<tr>
			<td>计划注资</td>
			<td><?php echo $list['injectionof'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">经纪商&amp;代理商</td></tr>
		<tr>
			<td>经纪商ID</td>
			<td><?php echo $list['brokerid'] ?></td>
		</tr>
		<tr>
			<td>经纪商名称</td>
			<td><?php echo $list['brokername'] ?></td>
		</tr>
		<tr>
			<td>代理商ID</td>
			<td><?php echo $list['agentid'] ?></td>
		</tr>
		<tr>
			<td>代理商名称</td>
			<td><?php echo $list['agentname'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">收款银行资料</td></tr>
		<tr>
			<td>收款帐户的开户行</td>
			<td><?php echo $list['bank'] ?></td>
		</tr>
		<tr>
			<td>收款帐户的开户分行</td>
			<td><?php echo $list['banksub'] ?></td>
		</tr>
		<tr>
			<td>收款银行的SWIFT CODE</td>
			<td><?php echo $list['bankcode'] ?></td>
		</tr>
		<tr>
			<td>收款银行的地址</td>
			<td><?php echo $list['bankaddress'] ?></td>
		</tr>
		<tr>
			<td>收款银行的户名</td>
			<td><?php echo $list['bankname'] ?></td>
		</tr>
		<tr>
			<td>收款银行的卡号</td>
			<td><?php echo $list['bankcard'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">就业资料</td></tr>
		<tr>
			<td>就业状况</td>
			<td><?php echo $list['employment'] . '-' . $list['employment_2'] ?></td>
		</tr>
		<tr>
			<td>补充说明</td>
			<td><?php echo $list['employment_content'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">投资经验资料</td></tr>
		<tr>
			<td>证券投资</td>
			<td><?php echo $list['securities'] ?></td>
		</tr>
		<tr>
			<td>期权投资</td>
			<td><?php echo $list['options'] ?></td>
		</tr>
		<tr>
			<td>商品投资</td>
			<td><?php echo $list['commodity'] ?></td>
		</tr>
		<tr>
			<td>期货投资</td>
			<td><?php echo $list['futures'] ?></td>
		</tr>
		<tr>
			<td>外汇投资</td>
			<td><?php echo $list['exchange'] ?></td>
		</tr>
		<tr>
			<td>差价合约投资</td>
			<td><?php echo $list['cfds'] ?></td>
		</tr>
		<tr>
			<td>在过去的四个季度中，每季度有多于10次的交易</td>
			<td><?php echo $list['frequency'] ?></td>
		</tr>
		<tr>
			<td>是否在金融服务行业中担任需有与交易产品或服务相关知识的专业职位至少一年</td>
			<td><?php echo $list['knowledge'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">财务资料</td></tr>
		<tr>
			<td>年薪</td>
			<td><?php echo $list['annual_income'] ?></td>
		</tr>
		<tr>
			<td>净资产</td>
			<td><?php echo $list['net_worth'] ?></td>
		</tr>
		<tr>
			<td>流动资产</td>
			<td><?php echo $list['liquid_assets'] ?></td>
		</tr>
		<tr>
			<td>曾是否于过往十年宣布破产</td>
			<td><?php echo $list['bankruptcy'] ?></td>
		</tr>
		<tr>
			<td>破产说明</td>
			<td><?php echo $list['bankruptcy_content'] ?></td>
		</tr>
		<tr>
			<td>现在或过去曾否在<?php echo $this->config->item('company_name') ?>开立过账户</td>
			<td><?php echo $list['pastaccount'] ?></td>
		</tr>
		<tr>
			<td>账户说明</td>
			<td><?php echo $list['pastaccount_content'] ?></td>
		</tr>
		<tr>
			<td>亲友是否为<?php echo $this->config->item('company_name') ?>的干事，董事，雇员或关联人士</td>
			<td><?php echo $list['isrelative'] ?></td>
		</tr>
		<tr>
			<td>亲友说明</td>
			<td><?php echo $list['isrelative_content'] ?></td>
		</tr>

		<tr class="info"><td colspan="2">证件资料</td></tr>
		<tr>
			<td colspan="2">证件正面照

			<?php 
				if(isset($cer->certificate1)){
					echo '<img src="' . base_url() . $cer->certificate1 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">证件反面照

			<?php 
				if(isset($cer->certificate2)){
					echo '<img src="' . base_url() . $cer->certificate2 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">银行卡正面照

			<?php 
				if(isset($cer->certificate3)){
					echo '<img src="' . base_url() . $cer->certificate3 . '" />';
				}else{
					echo '<font color="red">未上传</font>';
				}
			?>
			</td>
		</tr>
    </table>
    <div style="height:30px;text-align: center;">
    <a href="<?php echo site_url('admin/real')?>" class="btn btn-primary" >返回</a>
    <a href="#" class="btn btn-danger" id="open_account">开户</a>
</div>

<div id="open_account_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">开设真实MT4帐户</h4><div id="mt4_acc_result" style="color:red;font-size:18px"></div>
	</div>
	<div class="modal-body">
		
			<?php 
			$attr = array('method'=>'post','id'=>'form_mt4_open','class'=>'form-horizontal');
			echo form_open('user/mt4login',$attr);?>
			<input type="hidden" name="user_id" value="<?php echo $list['id'] ?>" />
			<div class="control-group">
			   <label class="control-label" for="mt4_acc" style="color:blue">MT4帐号</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'mt4_acc','id'=>'mt4_acc','value'=>'', 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">留空则自动生成MT4帐号</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="agent_acc" style="color:blue">代理MT4帐号</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'agent_acc','id'=>'agent_acc','value'=>'', 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red"></font></span>
			   </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="server" style="color:blue">服务器</label>
				<div class="controls">
					<select id="server" name="server">
						<option value="">请选择服务器</option>
						<?php foreach ($server as $v){?>
						<option value="<?php echo $v['local_port']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
						<?php }?>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="group_name" style="color:blue">所属组名称</label>
			   <div class="controls">
			   	<div id="dyGroup">
			      <?php 
			      $data = array('name'=>'group_name','id'=>'group_name','value'=>$this->config->item('default_group_name'), 'maxlength'=>'25');
			      echo form_input($data);
			      ?></div>
			      <span class="help-inline"><font color="red">*服务器上组必须已存在</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="username">姓名</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'username','id'=>'username','value'=>$list['first_name'] . $list['last_name'] . $list['sex'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="email">电子邮箱</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'email','id'=>'email','value'=>$list['email'], 'maxlength'=>'100');
			      echo form_input($data);
			      ?>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="country">国家</label>
			   <div class="controls">
			   	  <?php 
			      $data = array('name'=>'country','id'=>'country','value'=>$list['country'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="state">省份</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'state','id'=>'state','value'=>$list['province'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="city">城市</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'city','id'=>'city','value'=>$list['city'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="address">地址</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'address','id'=>'address','value'=>$list['address'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="id_num">证件号</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'id_num','id'=>'id_num','value'=>$list['card'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="phone">手机</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'phone','id'=>'phone','value'=>$list['mobile'], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="phone">备注</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'comment','id'=>'comment','value'=>$list['bank'] . $list['banksub'] . "：" . $list['bankcard'], 'maxlength'=>'100');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red"></font></span>
			   </div>
			</div>
			<div class="control-group">
			   <label class="control-label" for="leverage">交易倍数</label>
			   <div class="controls">
			      <?php 
			      $data = array('name'=>'leverage','id'=>'leverage','value'=>explode(':',$list['level'])[1], 'maxlength'=>'25');
			      echo form_input($data);
			      ?>
			      <span class="help-inline"><font color="red">*</font></span>
			   </div>
			</div>
			<div class="control-group">
				<div class="controls">
				<span class="help-inline" style="color: red">提交开户前请仔细核对上述开户信息是否有误</span>
				</div>
			</div>
			<?php
			echo form_close();
			?>
		</div>
	
	<div class="modal-footer">
		<button class="btn" id="open_account_close" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary" id="do_open_account">开户</button>
	</div>
</div>
<?php 
$loading_url = base_url() . '/public/img/loading.gif';
 ?>
<script type="text/javascript">
$(function(){
	var group_html = $("#dyGroup").html();

	$("#server").change(function(){
		if ($(this).val() != "") {
			$("#do_open_account").text("正在获取组信息。。。").attr("disabled",true);
			$.ajax({
				url:"<?php echo site_url('admin/getGroups') ?>",
				type:"post",
				data:{"server":$(this).val()},
				dataType:"json",
				success:function(data){
					if (data != null && typeof(data) == "object") {
						if (typeof(data.error_msg) != 'undefined') {
							alert("组信息获取失败：" + data.error_msg);
							$("#dyGroup").html(group_html);
						} else {
							var sel_s = '<select id="group_name" name="group_name">';
							$.each(data,function(i,c){
								sel_s += '<option value="' + c[0] + '">' + c[0] + '</option>'
							});
							sel_s +='</select>';
							$("#dyGroup").html(sel_s);
						}
					}
					$("#do_open_account").text("开户").attr("disabled",false);
				},
				fail:function(data){
					alert("请求错误，请重试。");
					$("#do_open_account").text("开户").attr("disabled",false);
				}
			});
		}
	});

	$("#do_open_account").click(function(){
		var mt4_acc = $.trim($("#mt4_acc").val());
		var agent_acc = $.trim($("#agent_acc").val());
		var group_name = $.trim($("#group_name").val());

		if(group_name == ""){
			alert("组名称不能为空");
			return false;
		}

		var p = /^[0-9]{1,15}$/;
		if(mt4_acc != "" && !p.test(mt4_acc)){
			alert("MT4帐号应为纯数字");
			return false;
		}
		if(agent_acc != "" && !p.test(agent_acc)){
			alert("代理MT4帐号应为纯数字");
			return false;
		}

		$(this).text("处理中。。。请勿关闭本窗口").attr("disabled","disabled");
		$.ajax({
  			url:"<?php echo site_url('admin/openMt4Acc') ?>",
  			type:"post",
  			dataType:"json",
  			data:$("#form_mt4_open").serialize(),
  			success:function(data){
  				$("#mt4_acc_result").empty();
  				if (data != null && typeof(data.success) != 'undefined') {
  					var reason = "";
  					if (data.success == true) {
  						$("#mt4_acc_result").append("开户成功。请保存好以下帐户信息：<br />帐号："+
  							data.login+"<br />密码："+data.password+"<br />投资者密码："+
  							data.password_investor+"<br />服务器："+$("#server option:selected").text());
  					} else {
  						alert("开户失败。失败原因：" + data.reason);
  					}
  				} else {
  					alert("未知错误" + data);
  				}
  				$("#do_open_account").text("开户").attr("disabled",false);
  			},
  			fail:function(data) {
  				alert("请求错误，请重试。" + data);
  				$("#mt4_acc_result").empty();
  				$("#do_open_account").text("开户").attr("disabled",false);
  			}
  		});
  		return false;
	});

	$("#open_account").click(function(){
		$('#open_account_modal').modal('show');
		$("#server").trigger("change");
		return false;
	});
});
</script>
<?php $this->load->view('admin/footer')?>