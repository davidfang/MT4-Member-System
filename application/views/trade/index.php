<?php $this->load->view('header')?>
<style type="text/css">
	body{
		font-size: 12px;
	}
	#online_order label{
		font-size: 12px;
	}
	#online_order .n-invalid {
		border: 1px solid #f00;
	}
	.form-horizontal .control-group {
		margin-bottom: 10px;
	}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/validator-0.6.4/jquery.validator.css">
<script type="text/javascript" src="<?php echo base_url()?>public/validator-0.6.4/jquery.validator.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/validator-0.6.4/local/zh_CN.js"></script>
<div id="ext_toolbar" style="display:none">
	<div id="ext_toolbar_close">
		<span style="display:none;">关闭</span>
	</div>
	<div id="ext_toolbar_install">
		<a class="btn" href="http://www.google.com/intl/zh-CN/chrome/" target="_blank">下载安装</a>
	</div>
	<div id="ext_toolbar_text" style="padding: 9px 10px 6px 38px; overflow: hidden; white-space: nowrap; -webkit-user-select: none; cursor: default; width: 1254px; background-position: 5px 5px;">
		温馨提醒：检测到您在使用IE浏览器访问本站，推荐使用Chrome、<a href="http://www.firefox.com.cn/download/" target="_blank">Firefox</a>或<a href="http://www.opera.com/zh-cn" target="_blank">Opera</a>等现代浏览器访问本站点，其他浏览器访问不保证能正常使用本站所有功能
	</div>
</div>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
	<button class="btn btn-block btn-inverse" data-toggle="collapse" data-target="#online_web">Web交易系统</button>
	<div class="row-fluid collapse in" id="online_web">
	<div class="span4">
		<!--<button class="btn btn-block" data-toggle="collapse" data-target="#online_price">即时报价</button>-->
		<div id="online_price" class="collapse in"  style="max-height:480px;overflow-x:hidden;overflow-y:scroll">
		<table class="table table-condensed table-hover">
			<caption></caption>
			<thead>
				<tr>
					<th>商品</th>
					<th>卖价</th>
					<th>买价</th>
				</tr>
			</thead>
			<tbody id="quoteslist">
			</tbody>
		</table>
		</div>
	</div>
	<div class="span4"><!--<button class="btn btn-block" type="button" id="jqtest" data-toggle="collapse" data-target="#online_order">在线下单</button>-->
		<div id="online_order" class="collapse in">
		<form method="post" class="form-horizontal" id="online_order_form">
			<div class="control-group">
				<label class="control-label" for="username">MT4帐号</label>
				<div class="controls">
					<select id="username" name="username" data-rule="帐号:required">
						<?php foreach ($mt4 as $v){?>
						<option value="<?php echo $v['mt4_account']?>" <?php echo $v['is_chief'] ? 'selected' : ''; $cur_server = $v['server_id'];?>><?php echo $v['mt4_account']?></option>
						<?php }?>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="server">服务器</label>
				<div class="controls">
					<select id="server" name="server" data-rule="服务器:required">
						<?php foreach ($server as $v){?>
						<option value="<?php echo $v['id']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
						<?php }?>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<label class="checkbox" style="color:red">
						<input type="checkbox" id="multiAccCheck">启用多账号同时下单
					</label>
					<div style="display:none" id="multiAcc">
						<?php foreach ($mt4 as $v){?>
						<label class="checkbox">
							<input name="checkbox" checked="checked" value="<?php echo $v['mt4_account']?>" type="checkbox"><?php echo $v['mt4_account']?>
						</label>
						<?php }?>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="currency">选择交易品种</label>
				<div class="controls">
					<select name="currency" id="currency" data-rule="交易品种:required">

					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="cur_price">当前价格</label>
				<div class="controls">
					<span name="cur_price" id="cur_price">请稍后......</span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="amount">交易量</label>
				<div class="controls">
					<input type="text" value="1.00" name="amount" id="amount" maxlength="20" class="input-medium" data-rule="交易量:required;amount" data-rule-amount="[/^[0-9]{1,3}$|^[0-9]{1,3}\.[0-9]{1,2}$/, '交易量填写有误']" />
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="sl">止损</label>
				<div class="controls">
					<input type="text" value="0.00" name="sl" id="sl" maxlength="20" class="input-medium" data-rule="止损:required;amount" data-rule-amount="[/^[0-9]{1,9}$|^[0-9]{1,9}\.[0-9]{1,5}$/, '止损填写有误']" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="tp">止盈</label>
				<div class="controls">
					<input type="text" value="0.00" name="tp" id="tp" maxlength="20" class="input-medium" data-rule="止盈:required;amount" data-rule-amount="[/^[0-9]{1,9}$|^[0-9]{1,9}\.[0-9]{1,5}$/, '止盈填写有误']" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="comment">备注</label>
				<div class="controls">
					<input type="text" name="comment" id="comment" maxlength="30" class="input-medium" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="select-choice-0">订单类型</label>
				<div class="controls">
					<select name="select-choice-0" id="cmbType" onChange="TypeChange()">
		                <option value="Instant" selected="selected">市价成交</option>
						<option value="Pending">挂单交易</option>
					</select>
					<span class="help-inline"><font color="red">*</font></span>
				</div>
			</div>
			
			<div id="divInstant">
				<div class="control-group">
					<input id="BtnBuy" type="submit" value="买"  class="btn btn-block btn-info" onclick="Order(this.id,'Instant')">
					<input id="BtnSell" type="button" value="卖" class="btn btn-block btn-danger"  onclick="Order(this.id,'Instant')">
				</div>
			</div>

			<div id="divPending" style="display:none">
				<div class="control-group">
					<label class="control-label" for="select-choice-1">挂单类型</label>
					<div class="controls">
						<select name="select-choice-1" id="cmbPendingType">
							<option value="2">Buy Limit</option>
							<option value="3">Sell Limit</option>
							<option value="4">Buy Stop</option>
							<option value="5">Sell Stop</option>
						</select>
						<span class="help-inline"><font color="red">*</font></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="edtPendingPrice">挂单价格</label>
					<div class="controls">
						<input type="text" name="edtPendingPrice" id="edtPendingPrice" maxlength="20" class="input-medium" data-rule="挂单价格:required;amount" data-rule-amount="[/^[0-9]{1,9}$|^[0-9]{1,9}\.[0-9]{1,5}$/, '挂单价格填写有误']" />
						<span class="help-inline"><font color="red">*</font></span>
					</div>
				</div>

				<div class="control-group">
					<input id="BtnPending" type="button" value="下单"  class="btn btn-block btn-info" onclick="Order(this.id,'Pending',$('#cmbPendingType').val())">
				</div>
			</div>
			<div id="orderResult" class="alert" style="display: none"></div>
		</form>
		</div>
	</div>
	<div class="span4">
		<!--<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#online_chart">图表</button>-->
		<script type="text/javascript" src="<?php echo base_url()?>public/js/highcharts.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>public/js/modules/exporting.js"></script>
		<div id="online_chart" class="collapse in">
		<div id="chart_container" style="min-width: 200px;  margin: 0 auto">
		</div>
		</div>
		<div id="signal" data-toggle="tooltip" title="连接状态"></div>
	</div>
</div>
<div class="row-fluid" style="margin-top:10px;">
	<div class="span12">
		<button class="btn btn-block btn-inverse" data-toggle="collapse" data-target="#online_open_orders">交易</button>
		<div id="online_open_orders" class="collapse in" style="max-height:200px;overflow-x:hidden;overflow-y:scroll">
		<table class="table table-condensed table-hover">
			<thead>
				<tr>
					<th>订单</th>
					<th>时间</td>
					<th>类型</td>
					<th>手数</td>
					<th>商品</td>
					<th>价位</td>
					<th>止损</td>
					<th>止盈</td>
					<th>价位</td>
					<th>获利</td>
					<th colspan="2" style="text-align:center">操作</td>
				</tr>
			</thead>
			<tbody id="ul_orderlist">
				<?php if(isset($open_order) && !empty($open_order)){
					foreach ($open_order as $v) { ?>
					<tr>
						<td><?php echo $v['id'] ?></td>
						<td><?php echo $v['account'] ?></td>
						<td><?php echo $v['amount'] ?></td>
						<td><?php echo $v['order_id'] ?></td>
						<td><?php echo $v['my_id'] ?></td>
						<td><?php echo $v['time'] ?></td>
						<td><?php echo $v['amount'] ?></td>
						<td><?php echo $v['order_id'] ?></td>
						<td><?php echo $v['my_id'] ?></td>
						<td><?php echo $v['time'] ?></td>
						<td>编辑</td>
						<td>平仓</td>
					</tr>
				<?php }} ?>
			</tbody>
			<tfoot>
				<tr style="background-color: #858A8D;font-weight:bold">
					<td colspan="9"><div id="userinfo">加载中。。。</div></td>
					<td><div id="accProfit">0.00</div></td>
					<td colspan="2" style="text-align:center"><button class="btn btn-danger" id="one_click">一键平仓</button></td>
				</tr>
			</tfoot>
		</table>
	</div>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<button class="btn btn-block btn-inverse" data-toggle="collapse" data-target="#online_history">
			账户历史</button>
		<div id="online_history" class="collapse"  style="max-height:200px;overflow-x:hidden;overflow-y:scroll">
		<table class="table table-bordered table-condensed table-hover">
			<thead>
				<tr>
					<th>订单</th>
					<th>开仓时间</td>
					<th>类型</td>
					<th>手数</td>
					<th>商品</td>
					<th>开仓价</td>
					<th>止损</td>
					<th>止盈</td>
					<th>平仓时间</td>
					<th>平仓价</td>
					<th>手续费</td>
					<th>获利</td>
				</tr>
				<tr>
					<td colspan="12"><button class="btn btn-block" id="update_history">更新账户历史（此处仅显示最近一个月交易记录，更多记录请登录客户端软件查询）</button></td>
				</tr>
			</thead>
			<tbody>
				<?php echo $history_order; ?>
			</tbody>

		</table>
	</div>
	</div>
</div>
</div>

<div id="order_edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">修改订单<span id="edit_order_type" style="margin-left:10px;color:red"></span></h4>
	</div>
	<div class="modal-body">
<!--<iframe name="editOrder" src="" frameborder="0" width="100%" height="300px" scrolling="no"></iframe>-->
<div id="divOperation">
	<form id="form_modify" class="form-horizontal" method="post" action="">
		<div class="control-group">
		    <label class="control-label" id="symbolEdit"></label>
		    <div class="controls">
		      <label id="edit_symbol_price">0</label>
		    </div>
		</div>
		<div class="control-group">
			<label class="control-label" for="order_id_m">订单号</label>
			<div class="controls">
				<label id="order_id_m"></label>
				<input type="hidden" name="cmd" id="editCMD" value="0" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="edtSL">止损</label>
			<div class="controls">
				<input type="text" name="edtSL" id="edtSL" placeholder="">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="edtTP">止盈</label>
			<div class="controls">
				<input type="text" name="edtTP" id="edtTP" placeholder="">
			</div>
		</div>
		<div class="control-group" id="cmd_show">
			<label class="control-label" for="edtPrice">价位</label>
			<div class="controls">
				<input type="text" name="edtPrice" id="edtPrice" placeholder="">
			</div>
		</div>
		<div class="control-group">
			<button type="button" class="btn btn-block btn-info" onclick="PostModify();" id="button_modify">确定</button>
		</div>
	</form>
</div>
	</div>
	<div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<div id="loading" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
 aria-hidden="true" data-backdrop="static">
	<div class="modal-body">
		<p>正在处理中，请稍后。。。</p>
	</div>
</div>

<div class="theme-popover-mask"></div>

<script type="text/javascript">
	var OP_BUY=0;
	var OP_SELL=1;
	var OP_BUY_LIMIT=2;
	var OP_SELL_LIMIT=3;
	var OP_BUY_STOP=4;
	var OP_SELL_STOP=5;
	var OP_BALANCE=6;
	var OP_CREDIT=7;

	var isGetQuotesEnd=true; //the begin flags of GetQuotes
	var SymbolInfo=[]; //Symbol Information
	var isUserInofEnd=true;
	var isGetOrderList=true;
	var isLoading = false;
	var EXPORT_SYMBOLS=<?php echo (isset($_SESSION['symbols']) ? $_SESSION['symbols'] : '[]'); ?>;
	//var tmp,t;
	var invGetQuotes;
	var invGetUserInfo;
	var invGetOrderList;
	var EditSymbol;
	var closeFlag=false;
	//var invGetData;

	var chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart_container',
				type: 'line',
				//marginRight: 130,
				//marginBottom: 25,
				borderWidth: 0
			},
			title: {
				text: EXPORT_SYMBOLS[0],
				x: -20 //center
			},
			subtitle: {
				text: '<?php echo $this->config->item("company_name") ?>',
				x: -20
			},
			xAxis: {
				labels: {
        			enabled: false
    			}
			},
			yAxis: {
				title: {
					text: '报价'
				},
				labels: {
                formatter: function() {
                    return this.value ;
                }
            }
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
			plotOptions: {
                    series: {
                        marker: {
                            enabled: false,
                            states: {
                                hover: {
                                    enabled: true,
                                    radius: 3
                                }
                            }
                        }
                    }
                },
			series: [{
                name: EXPORT_SYMBOLS[0],
                data:[]
            }]
		});

	$(function(){
		var userAgent = window.navigator.userAgent;
		if (userAgent.indexOf("MSIE") > 0) {
			$("#ext_toolbar").show().css("width",document.body.clientWidth + "px");
		}

		$("#ext_toolbar_close").click(function(){
			$("#ext_toolbar").hide();
		});
		
		FillSymbols();
		fillSelSymbol();

		invGetQuotes=setInterval("GetQuotes()",500);
		invGetUserInfo = setInterval("GetUserInfo()",1000);
		invGetOrderList = setInterval("GetOrderList()",1000);

		var cur_server = <?php echo $cur_server; ?>;
		$("option[value="+cur_server+"]").attr("selected",true);
		$("#server").attr("disabled",true);

		$("#username").change(function(){
			$("#ul_orderlist").html("");
			$("#online_history table tbody").html("");
			$("#update_history").trigger("click");
			$.ajax({
				type:"get",
				dataType:"json",
				url:"<?php echo site_url('trade/getServerId') . '/'; ?>" + $(this).val(),
				beforeSend: function(){
		        	$('#loading').modal('show');
		        },
				success:function(data){
					if (isObj(data) && data.error === "") {
						var id = data.id;
						if (id.length==1) {
							//选择服务器
							$("option[value="+id[0]+"]").attr("selected",true);
							$("#server").attr("disabled",true);
						}else{
							alert("该账号对应多个服务器，请手动选择正确的服务器");
							$("#server").attr("disabled",false);
						}
					}else{
						alert(data.error);
					}
				},
				error:function(){
					alert("ajax error");
				},
				complete:function(){
		        	$("#loading").modal('hide');
		        }
				});
		}).trigger("change");

		$("#quotations").click(function(e){
			e.preventDefault();
			window.location.href = "<?php echo site_url('trade/chart'); ?>"+"?server="+
			$("#server").val()+"&login="+$("#username").val();
		});
		
		//invGetData=setInterval("getData()",10000);
		
		chart.showLoading();
		isLoading=true;
		getData();

		$("#quoteslist tr").hover(
			function(){
				$(this).find("span").addClass("btn");
			},
			function(){
				$(this).find("span").removeClass("btn");
			}
		);

		$("#quoteslist tr").click(function(){
			//$('#loading').modal({
					//backdrop: true
				//});
			//$('<div class="modal-backdrop"></div>').appendTo(document.body);
			CurrSymbol=$(this).attr("id").split("_")[1];
			$("#currency").children().removeAttr('selected');
			$("#sel_"+CurrSymbol).attr("selected",true);
			$("#currency").trigger("change");
		});

		$("#currency").change(function(){
			$("#BtnBuy").attr("disabled",true);
			$("#BtnSell").attr("disabled",true);
			$("#BtnPending").attr("disabled",true);
			$("#cur_price").text("请稍后......")
			CurrSymbol=$(this).find("option:selected").text();
			chart.setTitle({text:CurrSymbol});
			chart.series[0].update({
	            name: CurrSymbol
	        });
	        if (!isLoading) {
	        	chart.showLoading();
				isLoading=true;
				getData();
	        };
		});

		$("#update_history").on("click",function(){
			$.ajax({
				method:"get",
				url:"getHistory/"+$("#username").val()+"/"+$("#server").val()+"/1",
				beforeSend:function(){
					$("#online_history").find("tbody").html("<font color='red'>账户历史更新中，请稍后。。。</font>");
				},
				success:function(data){
					$("#online_history").find("tbody").html(data);
				},
				error:function(){
					alert("ajax error");
				}
			});
		});

		$(document).ajaxStop(function(e,x,s){
			$("#signal").show();
		}).ajaxError(function(e,x,s,ex){
			$("#signal").hide();
		});

		$("#online_order").validator({theme: 'simple_bottom',ignore: ':hidden'});

		$("#order_edit_modal").on("hidden",function(){
			EditSymbol = '';
		});

		$("#one_click").click(function(){
			CloseAll();
		});

		$("#multiAccCheck").click(function(){
			var ac = $("#multiAcc");
			ac.css("display") == "none" ? ac.show() : ac.hide();
		});
	});

	function EditOrder(order){
		//window.frames["editOrder"].location.href="<?php echo site_url('trade/edit') ?>" + order;
		var params = order.split("&");
		$("#order_id_m").html(params[1].split("=")[1]);
		$("#edtSL").val(params[2].split("=")[1]);
		$("#edtTP").val(params[3].split("=")[1]);
		$("#edtPrice").val(params[4].split("=")[1]);
		
		EditSymbol = params[0].split("=")[1];
		$("#symbolEdit").html(EditSymbol).addClass("tx-green");

		var cmd = params[5].split("=")[1];
		$("#editCMD").val(cmd);
		$("#edit_order_type").html(formatAction(cmd));
		if (cmd == "0" || cmd == "1") {
			$("#cmd_show").hide();
		}else{

			$("#cmd_show").show();
		}

		$('#order_edit_modal').modal('show');
	}

	function FillSymbols()
	{
		var ql_ul=$("#quoteslist");
		ql_ul.empty();
		CurrSymbol=EXPORT_SYMBOLS[0];	
		$.each( EXPORT_SYMBOLS, function(index, content){
			ql_ul.append("<tr id='li_"+content+"'>"+
				"<td id='"+content+"'>"+content+"</td>"+
				"<td id='"+content+"_bid'><span class='up'>0.0000</span></td>"+
				"<td id='"+content+"_ask'><span class='up'>0.0000</span></td></tr>");

		});
		$("#quoteslist tr").click(function() {
			//CurrSymbol=$(this).attr("id").split("_")[1];
		});
	}

	function fillSelSymbol()
	{
		var cur = $("#currency");
		cur.empty();
		$.each(EXPORT_SYMBOLS,function(index,content){
			cur.append("<option value='"+index+"' id='sel_"+content+"'>"+content+"</option>");
		});
	}

	function TypeChange()
	{
		if ($("#cmbType").val()=="Instant"){
			$("#divInstant").show();
		}else{
			$("#divInstant").hide();
		}
		if ($("#cmbType").val()=="Pending"){
			$("#divPending").show();
			$("#edtPendingPrice").focus();
			//$("#online_order").validator("setField",{"edtPendingPrice":"挂单价格:required"});
		}else{
			$("#divPending").hide();
		}
	}

	function GetUserInfo()
	{
		if(!isUserInofEnd){return;}
		isUserInofEnd=false;
		var server = $("#server").val();
		$.ajax({ 
			type: "Get", 
			url: "getdata?action=userinfo"+"&server="+server+"&login="+$("#username").val(), 
			dataType: "json",     
			success: function(json){	      
				if(isObj(json)&& json.error==''){ 
					$("#userinfo").html("余额：" + toDecimal2(json.data.balance) + "&nbsp;&nbsp;净值：" + 
						toDecimal2(json.data.equity) + "&nbsp;已用保证金：" + toDecimal2(json.data.margin) +
						"&nbsp;可用保证金：" + toDecimal2(json.data.freeMargin) + "&nbsp;保证金比例：" + 
						((json.data.margin==0) ? "0.00" : toDecimal2(Number(json.data.equity)/Number(json.data.margin)*100)) + "%");
				}
				isUserInofEnd=true;
			},
			error:function(){ 
				isUserInofEnd=true; 
			}
		})
	}

	function GetOrderList(){
		if (!isGetOrderList) {
			return;
		}
		isGetOrderList=false;
		$.ajax({
			type: "Get",
			url: "getdata?action=orderlist&server="+$("#server").val()+"&login="+$("#username").val(),
			dataType: "json",    
			success: function(json){      
				if(json!=null||typeof(json)!='undefined'&& json.error==''){
					var ol_ul=$("#ul_orderlist");
					if(json.data!=null && typeof(json.data)!='undefined' && json.data.rows != null){
						var OrderListSource=json.data;
						var oldOrder = $("[id^='linew_']");
						if (oldOrder.length > json.data.total) {
							$.each(oldOrder,function(i,c){
								if (!in_array(c.id.split("_")[1],json.data.orde)) {
									c.parentNode.removeChild(c);
								}
							});
						}
						$.each( json.data.rows, function(index, content){
							if($('#linew_'+content.order).length==0){
								ol_ul.append("<tr id='linew_"+content.order+"' class='" + 
									(content.cmd==OP_BUY || content.cmd==OP_SELL ? 'warning' : 'error') +"'>"+
									"<td id='"+content.order+"_ord'>"+content.order+"</td>"+
									"<td id='"+content.order+"_opt'>"+content.open_time+"</td>"+
									"<td id='c_"+content.order+"'>"+formatAction(content.cmd)+"</td>"+
									"<td id='v_"+content.order+"'>"+toDecimal2(content.volume)+"</td>"+
									"<td id='sym_"+content.order+"'>"+content.symbol+"</td>"+
									"<td id='o_"+content.order+"'>"+content.open_price+"</td>"+
									"<td id='s_"+content.order+"'>"+content.sl+"</td>"+
									"<td id='t_"+content.order+"'>"+content.tp+"</td>"+
									"<td id='p_"+content.order+"_"+content.symbol+"_"+content.cmd+"'>0.00</td>"+
									"<td id='f_"+content.order+"'>"+(content.cmd==0 || content.cmd==1 ? toDecimal2(content.profit) : "")+"</td>"+
									"<td><a href='#' onclick='EditOrder("+
										"\"symbol="+content.symbol+"&order="+content.order+"&sl="+content.sl+"&tp="+content.tp+
										"&price="+content.open_price+"&cmd="+content.cmd+"\")'>修改</a></td>"+
									(content.cmd==OP_BUY || content.cmd==OP_SELL ? "<td><a href='#' onclick='CloseOrder("+content.order+")'>平仓</a></td>" : "")+
									"</tr>"
									);
							}else{
								if (content.cmd==0 || content.cmd==1) {
									$("#f_"+content.order).html(toDecimal2(content.profit));
								}
								$("#s_"+content.order).html(content.sl);
								$("#t_"+content.order).html(content.tp);
							}
						});
					}					            	
				}else{
					
				}
				isGetOrderList=true;
			},
			error:function(){
				isGetOrderList=true;
			}
		});

	};

	function in_array (needle, haystack, argStrict) {
		var key = '',
		strict = !! argStrict;

		if (strict) {
			for (key in haystack) {
				if (haystack[key] === needle) {
					return true;
				}
			}
		} else {
			for (key in haystack) {
				if (haystack[key] == needle) {
					return true;
				}
			}
		}

		return false;
	}

	function toDecimal2(x) {  
		var f = parseFloat(x);  
		if (isNaN(f)) {  
			return false;  
		}  
		var f = Math.round(x*100)/100;  
		var s = f.toString();  
		var rs = s.indexOf('.');  
		if (rs < 0) {  
			rs = s.length;  
			s += '.';  
		}  
		while (s.length <= rs + 2) {  
			s += '0';  
		}  
		return s;  
	}

	function formatAction(val){  
		if(val==OP_BUY)return "BUY";
		if(val==OP_SELL)return "SELL";
		if(val==OP_BUY_LIMIT)return "BUY LIMIT";
		if(val==OP_BUY_STOP)return "BUY_STOP";
		if(val==OP_SELL_LIMIT)return "SELL LIMIT";
		if(val==OP_SELL_STOP)return "SELL STOP";
	}

	function NewOrder(type,symbol,cmd,openprice,volumn,sl,tp,expried)
	{	
	 var strurl="";
	     straction="";
	 if (type=="Instant") 
	 {
		 straction ="neworder";
	 }
	 if (type=="Pending")
	 {
		 straction="pendingorder" 
	 }

	 var comment=$("#comment").val();

	 strurl= "mtTrade?action="+straction+
	 "&volume="+volumn+
	 "&price="+openprice+
	 "&symbol="+symbol+
	 "&cmd="+cmd+
	 "&sl="+sl+
	 "&tp="+tp+ 
	 "&comment="+comment+
	 (type=="Pending"?"&expried="+expried:"")+"&server="+$("#server").val();
	 if ($("#multiAccCheck").prop("checked") === true) {
	 	strurl += "&multiAcc=";
	 	$("input[name=checkbox]:checked").each(function(index,content){
	 		strurl += content.value + encodeURIComponent("#");
	 	});
	 	strurl = strurl.substring(0,strurl.length-3);
	 }else{
	 	strurl += "&login="+$("#username").val();
	 }
		 $.ajax({ 
	        type: "Get", 
	        url: strurl,
	        dataType: "json", 
	        beforeSend: function(){
	        	$("#orderResult").removeClass().addClass("alert");
	        	$('#loading').modal('show');
	        }, 
	        success: function(json){	      
	            if(json!=null&&typeof(json)!='undefined'&& json.error==''){ 				            	
	            	$("#orderResult").show().html("下单成功").addClass("alert-success").delay(3000).hide(1000);
	            }else{ 
	            	$("#orderResult").show().html("下单失败。参数错误或交易市场关闭。备注："+json.error).addClass("alert-error").delay(3000).hide(1000);
	            } 
	        },
	        error:function() {
	        	$("#orderResult").show().html("提交下单请求失败，请重试").addClass("alert-error").delay(3000).hide(1000);
	        },
	        complete:function(){
	        	$("#loading").modal('hide');
	        }
		}) 
	}
	function Order(id,type,cmd)
	{
		var validResult = $("#online_order_form").isValid();
		if(validResult === false || validResult === "undefined"){
			return false;
		}

		if (!confirm("确认下单？")){
			return false;
		}

		if (id=="BtnSell")
		{
			NewOrder(type,
						CurrSymbol,
						OP_SELL,
						curr_bid,
						$("#amount").val(),
						$("#sl").val(),
						$("#tp").val()
					);
		}
		if (id=="BtnBuy")
		{
			NewOrder(type,
					CurrSymbol,
					OP_BUY,
					curr_ask,
					$("#amount").val(),
					$("#sl").val(),
					$("#tp").val()
				);
		}
		if (id=="BtnPending")
		{
			NewOrder(type,
					CurrSymbol,
					cmd,
					$("#edtPendingPrice").val(),
					$("#amount").val(),
					$("#sl").val(),
					$("#tp").val(),
					"0"					
					);
		
		}
	}

	function GetQuotes(){
		if (!isGetQuotesEnd) return false;
		$.ajax({ 
			type: "Get", 
			url: "getdata?action=quotes&server="+$("#server").val()+"&login="+$("#username").val(), 
			dataType: "json",	        
			beforeSend: function(){	 
				isGetQuotesEnd=false;         
			}, 
			success: function(json){  
				if(json!=null&&typeof(json)!='undefined'&& json.error=='' && json.data.total>0){			            	
					$.each( json.data.rows, function(index, content){
							//美化报价显示
							var str=content.ask;
							var texts=str.substr(0, str.length-2); 
							var textssup= str.substr(str.length-2,2);
							var strbid=content.bid;
							var textsbid=strbid.substr(0, strbid.length-2); 
							var textssupbid= strbid.substr(strbid.length-2,2);
							if($("#"+content.symbol+"_ask").find("span").hasClass("btn")){
								$("#"+content.symbol+"_ask").html("<span class='btn'>" + texts + "<sup>" + textssup + "</sup></span>");
								$("#"+content.symbol+"_bid").html("<span class='btn'>" + textsbid + "<sup>" + textssupbid + "</sup></span>");
							}else{
								$("#"+content.symbol+"_ask").html("<span>" + texts + "<sup>" + textssup + "</sup></span>");
								$("#"+content.symbol+"_bid").html("<span>" + textsbid + "<sup>" + textssupbid + "</sup></span>");
							}

							$("div [id$='"+content.symbol+"_0']").html(content.bid);
							$("div [id$='"+content.symbol+"_1']").html(content.ask);
							$("div [id$='"+content.symbol+"_2']").html(content.ask);
							$("div [id$='"+content.symbol+"_3']").html(content.bid);
							$("div [id$='"+content.symbol+"_4']").html(content.ask);
							$("div [id$='"+content.symbol+"_5']").html(content.bid);
							
							if(content.direction=="up")
							{
								if ($("#"+content.symbol+"_ask span").hasClass("down")) {
									$("#"+content.symbol+"_ask span").removeClass("down");
									$("#"+content.symbol+"_bid span").removeClass("down");
								}
								$("#"+content.symbol+"_ask span").addClass("up");
								$("#"+content.symbol+"_bid span").addClass("up");

								$("div [id$='"+content.symbol+"_0']").attr("style","color:green");
								$("div [id$='"+content.symbol+"_1']").attr("style","color:green");
								$("div [id$='"+content.symbol+"_2']").attr("style","color:green");
								$("div [id$='"+content.symbol+"_3']").attr("style","color:green");
								$("div [id$='"+content.symbol+"_4']").attr("style","color:green");
								$("div [id$='"+content.symbol+"_5']").attr("style","color:green");
							}
							else
							{
								if ($("#"+content.symbol+"_ask span").hasClass("up")) {
									$("#"+content.symbol+"_ask span").removeClass("up");
									$("#"+content.symbol+"_bid span").removeClass("up");
								}
								$("#"+content.symbol+"_ask span").addClass("down");
								$("#"+content.symbol+"_bid span").addClass("down");

								$("div [id$='"+content.symbol+"_0']").attr("style","color:red");
								$("div [id$='"+content.symbol+"_1']").attr("style","color:red");
								$("div [id$='"+content.symbol+"_2']").attr("style","color:red");
								$("div [id$='"+content.symbol+"_3']").attr("style","color:red");
								$("div [id$='"+content.symbol+"_4']").attr("style","color:red");
								$("div [id$='"+content.symbol+"_5']").attr("style","color:red");
							}

							//当前货币对
							if(CurrSymbol==content.symbol)
				            {
				            	curr_ask=content.ask;
				            	curr_bid=content.bid;
				            	if(content.direction=="up"){
				            		$("#cur_price").html('<font style="color:green;font-size:18px;">'+
				            			content.bid+'/'+content.ask+'</font>');
				            	}else{
				            		$("#cur_price").html('<font style="color:red;font-size:18px;">'+
				            			content.bid+'/'+content.ask+'</font>');
				            	}
				            	if ($("#BtnBuy").prop("disabled")==true) {
				            		$("#BtnBuy").attr("disabled",false);
				            	}
				            	if ($("#BtnSell").prop("disabled")==true) {
				            		$("#BtnSell").attr("disabled",false);
				            	}
				            	if ($("#BtnPending").prop("disabled")==true) {
				            		$("#BtnPending").attr("disabled",false);
				            	}
				            	/*
				            	if (isObj(chart)) {
				            		if (tmp !== undefined) {
				            			$(tmp.element).remove();
				            		};
				            		t = chart.renderer.text(content.bid, 140, 150)
								            .css({
								                color: '#FF0000',
								            })
								            .add();
								    tmp = t;
				            	}*/
				            }
				            if (EditSymbol == content.symbol) {
				            	if(content.direction=="up"){
				            		$("#edit_symbol_price").html('<font style="color:green;font-size:18px;">'+
				            			content.bid+'/'+content.ask+'</font>');
				            	}else{
				            		$("#edit_symbol_price").html('<font style="color:red;font-size:18px;">'+
				            			content.bid+'/'+content.ask+'</font>');
				            	}
				            }
						});
						var p=0;
						$("[id^='f_']").each(function(index,content){
                    		p+=Number(content.innerHTML);
                    	});
            			$("#accProfit").html(toDecimal2(p));
				}	
				isGetQuotesEnd=true;
			},
			error:function()
			{
				isGetQuotesEnd=true;    
			}
		});
	};

	function exitpage()
	{
		clearInterval(invGetQuotes);
	}

	function isObj(str)
	{
	   	if(str==null||typeof(str)=='undefined') return false; return true;
	}

	/**
	* 和PHP一样的时间戳格式化函数
	* @param  {string} format    格式
	* @param  {int}    timestamp 要格式化的时间 默认为当前时间
	* @return {string}           格式化的时间字符串
	*/
	function date(format, timestamp){ 
    var a, jsdate=((timestamp) ? new Date(timestamp*1000) : new Date());
    var pad = function(n, c){
        if((n = n + "").length < c){
            return new Array(++c - n.length).join("0") + n;
        } else {
            return n;
        }
    };
    var txt_weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var txt_ordin = {1:"st", 2:"nd", 3:"rd", 21:"st", 22:"nd", 23:"rd", 31:"st"};
    var txt_months = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; 
    var f = {
        // Day
        d: function(){return pad(f.j(), 2)},
        D: function(){return f.l().substr(0,3)},
        j: function(){return jsdate.getDate()},
        l: function(){return txt_weekdays[f.w()]},
        N: function(){return f.w() + 1},
        S: function(){return txt_ordin[f.j()] ? txt_ordin[f.j()] : 'th'},
        w: function(){return jsdate.getDay()},
        z: function(){return (jsdate - new Date(jsdate.getFullYear() + "/1/1")) / 864e5 >> 0},
        // Week
        W: function(){
            var a = f.z(), b = 364 + f.L() - a;
            var nd2, nd = (new Date(jsdate.getFullYear() + "/1/1").getDay() || 7) - 1;
            if(b <= 2 && ((jsdate.getDay() || 7) - 1) <= 2 - b){
                return 1;
            } else{
                if(a <= 2 && nd >= 4 && a >= (6 - nd)){
                    nd2 = new Date(jsdate.getFullYear() - 1 + "/12/31");
                    return date("W", Math.round(nd2.getTime()/1000));
                } else{
                    return (1 + (nd <= 3 ? ((a + nd) / 7) : (a - (7 - nd)) / 7) >> 0);
                }
            }
        },
        // Month
        F: function(){return txt_months[f.n()]},
        m: function(){return pad(f.n(), 2)},
        M: function(){return f.F().substr(0,3)},
        n: function(){return jsdate.getMonth() + 1},
        t: function(){
            var n;
            if( (n = jsdate.getMonth() + 1) == 2 ){
                return 28 + f.L();
            } else{
                if( n & 1 && n < 8 || !(n & 1) && n > 7 ){
                    return 31;
                } else{
                    return 30;
                }
            }
        },
        // Year
        L: function(){var y = f.Y();return (!(y & 3) && (y % 1e2 || !(y % 4e2))) ? 1 : 0},
        //o not supported yet
        Y: function(){return jsdate.getFullYear()},
        y: function(){return (jsdate.getFullYear() + "").slice(2)},
        // Time
        a: function(){return jsdate.getHours() > 11 ? "pm" : "am"},
        A: function(){return f.a().toUpperCase()},
        B: function(){
            // peter paul koch:
            var off = (jsdate.getTimezoneOffset() + 60)*60;
            var theSeconds = (jsdate.getHours() * 3600) + (jsdate.getMinutes() * 60) + jsdate.getSeconds() + off;
            var beat = Math.floor(theSeconds/86.4);
            if (beat > 1000) beat -= 1000;
            if (beat < 0) beat += 1000;
            if ((String(beat)).length == 1) beat = "00"+beat;
            if ((String(beat)).length == 2) beat = "0"+beat;
            return beat;
        },
        g: function(){return jsdate.getHours() % 12 || 12},
        G: function(){return jsdate.getHours()},
        h: function(){return pad(f.g(), 2)},
        H: function(){return pad(jsdate.getHours(), 2)},
        i: function(){return pad(jsdate.getMinutes(), 2)},
        s: function(){return pad(jsdate.getSeconds(), 2)},
        //u not supported yet
        // Timezone
        //e not supported yet
        //I not supported yet
        O: function(){
            var t = pad(Math.abs(jsdate.getTimezoneOffset()/60*100), 4);
            if (jsdate.getTimezoneOffset() > 0) t = "-" + t; else t = "+" + t;
            return t;
        },
        P: function(){var O = f.O();return (O.substr(0, 3) + ":" + O.substr(3, 2))},
        //T not supported yet
        //Z not supported yet
        // Full Date/Time
        c: function(){return f.Y() + "-" + f.m() + "-" + f.d() + "T" + f.h() + ":" + f.i() + ":" + f.s() + f.P()},
        //r not supported yet
        U: function(){return Math.round(jsdate.getTime()/1000)}
    };
    return format.replace(/[\\]?([a-zA-Z])/g, function(t, s){
        if( t!=s ){
            // escaped
            ret = s;
        } else if( f[s] ){
            // a date function exists
            ret = f[s]();
        } else{
            // nothing special
            ret = s;
        }
        return ret;
    });
 }
	
	function getData() {
		var data = [],
		time = (new Date()).getTime();
		$.ajax({
			type:"get",
			dataType:"json",
			url:"getdata?action=historyquotes&symbol="+CurrSymbol+"&period=15"+"&server="+$("#server").val(),
			success:function(json){
				if(isObj(json)&& json.error==''){
					var len = json.data.length;
					var digit = Number(json.digits);
					var dig=Math.pow(10,json.digits);
					for (var i = 0; i < len; i++) {
						data.push({
							//x:json.data[i][0]-8*60*60,
							name:date("Y-m-d H:i:s",json.data[i][0]-8*60*60),
							y:json.data[i][1]/dig
						});
					};

				}else{
					data.push({
						x:time,
						y:json.error
					});
				}
				if (typeof data[0].y !== "string") {
					chart.series[0].setData(data);
				}else{
					chart.setTitle({text:"getdata error"});
					chart.series[0].setData([]);
				}			
				if (isLoading) {
					chart.hideLoading();
					isLoading=false;
				};
			},
			error:function(json){
				chart.setTitle({text:"getdata error"});
				chart.series[0].setData([]);
				if (isLoading) {
					chart.hideLoading();
					isLoading=false;
				};
			}
		});
	}

	function PostModify()
	{
		var sl = $.trim($("#edtSL").val());
		var tp = $.trim($("#edtTP").val());
		var curPrice = $("div [id=edit_symbol_price] > font").html().split("/");//市价。高价在前，低价在后
		var edtPrice = $.trim($("#edtPrice").val());//挂单价格
		var cmd = $("#editCMD").val();

		if (sl<0 || tp <0) {
			return false;
		}

		if (curPrice[0]==0) {
			alert("当前交易商品市场价不能为0，请稍等或刷新页面重试");
			return false;
		}

		var p =/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,})?$/;
		if(!p.test(sl) || !p.test(tp)){
			alert("金额必须为数值");
			return false;
			if (cmd != 0 || cmd != 1) {
				if (!p.test(edtPrice)) {
					alert("金额必须为数值");
					return false;
				}
			}
		}
		
		var symbolInfo = GetSymbolInformation(EditSymbol);
		if (!isObj(symbolInfo)) {
			alert("获取当前交易商品信息失败，请稍等或刷新页面重试");
			return false;
		}
		var diff = symbolInfo.stops_level / Math.pow(10,symbolInfo.digits);
		
		if (cmd==OP_BUY) {
			if ( ((sl!=0) && (curPrice[1] - sl < diff)) || ((tp!=0) && (tp - curPrice[1] < diff))) {
				alert("止损/止盈价设置有误，请检查");
				return false;
			}
		}else if(cmd==OP_SELL){
			if ( ((sl!=0) && (sl-curPrice[0] < diff)) || ((tp!=0) && (curPrice[0]-tp < diff))) {
				alert("止损/止盈价设置有误，请检查");
				return false;
			}
		}else if(cmd==OP_BUY_LIMIT || cmd==OP_BUY_STOP){
			if ( ((sl!=0) && (edtPrice-sl < diff)) || ((tp!=0) && (tp - edtPrice < diff))) {
				alert("止损/止盈价设置有误，请检查");
				return false;
			}
			if (cmd==OP_BUY_LIMIT && ((curPrice[1] - edtPrice) < diff)) {
				alert("挂单价格无效");
				return false;
			}
			if (cmd==OP_BUY_STOP && ((edtPrice - curPrice[1]) < diff)) {
				alert("挂单价格无效");
				return false;
			}
		}else if(cmd==OP_SELL_LIMIT || cmd==OP_SELL_STOP){
			if ( ((sl!=0) && (sl-edtPrice < diff)) || ((tp!=0) && (edtPrice-tp < diff))) {
				alert("止损/止盈价设置有误，请检查");
				return false;
			}
			if (cmd==OP_SELL_LIMIT && ((edtPrice - curPrice[0]) < diff)) {
				alert("挂单价格无效");
				return false;
			}
			if (cmd==OP_SELL_STOP && ((curPrice[0] - edtPrice) < diff)) {
				alert("挂单价格无效");
				return false;
			}
		}

		if (!confirm("无效价格可能会造成在当前市价自动止损/止盈。\r\n点击取消返回修改价格，点击确认修改订单。")) {
			return false;
		}

		var strurl="";
		strurl= "mtTrade?action=updateorder"+
				 "&order="+$("#order_id_m").html()+
				 "&price="+($("#edtPrice").length==0?0:$("#edtPrice").val())+
				 "&sl="+$("#edtSL").val()+
				 "&tp="+$("#edtTP").val()+"&server="+$("#server").val()+
				 "&login="+$("#username").val();
			$.ajax({ 
	        type: "Get", 
	        url: strurl,
	        dataType: "json", 
	        beforeSend: function(){
	            $("#button_modify").html("正在处理中，请稍后。。。");
	            $("#button_modify").attr("disabled",true);
	         }, 
	        success: function(json){	      
	            if(isObj(json)&& json.error==''){
	            	alert("修改订单成功");
	            	$('#order_edit_modal').modal('hide');
	            } 
	            else{alert(json.error);}
	        },
	        error:function() 
	        {  alert("Unknow Error");},
	        complete:function(){
	        	$("#button_modify").attr("disabled",false);
	            $("#button_modify").html("确定");
	        }
		}) 
	}

	function GetSymbolInformation(symbol)
	{
		var s;
		  for (var i = 0; i < SymbolInfo.length; i++) {        
		        if (SymbolInfo[i].symbol == symbol) {        
		        	return SymbolInfo[i];
		            break;        
		        };       
		    };
		  $.ajax({ 
		        type: "Get", 
		        async:false,
		        url: "getdata?action=symbolinfo&symbol="+symbol+
		        	"&server="+$("#server").val(), 
		        dataType: "json",	        
		        beforeSend: function(){	 	        
		        }, 
		        success: function(json){	      
		            if(isObj(json)&& json.error==''){	 
		            	var item={
		            			symbol:json.data.symbol,
		            			digits:json.data.digits,
		            			profit_mode:json.data.profit_mode,
		            			stops_level:json.data.stops_level,
		            			contract_size:json.data.contract_size,
		            			tick_value:json.data.tick_value,
		            			tick_size:json.data.tick_size,
		            			margin_mode:json.data.margin_mode,
		            			margin_initial:json.data.margin_initial	
		            	};
		            	SymbolInfo.push(item);
		            	s=item;
		            }
		        },
		        error:function()
		        {
		        	     
		        }
			});
		  return s;
	}

	function CloseAll()
	{
		if (confirm("全部平仓？"))
		{
			closeFlag = true;
			$("[id$='_ord']").each(function(index,oritem){
				CloseOrder(oritem.innerHTML);		 
			});
		}
	}

	function CloseOrder(order)
	{
		if (!closeFlag) {
			if (!confirm("确认平仓？")){
				return false;
			}
		};
		var currprice=$("td[id^=p_"+order+"]")[0].innerHTML;
		var currvolumn=$("td[id=v_"+order+"]")[0].innerHTML;
		var server = $("#server").val();
		

		var strurl="";
		strurl= "mtTrade?action=closeorder"+
				 "&volume="+currvolumn+
				 "&order="+order+
				 "&price="+currprice+
				 "&server="+server+
				 "&login="+$("#username").val();
		$.ajax({ 
	        type: "Get", 
	        url: strurl,
	        dataType: "json", 
	        beforeSend: function(){	
	        	$("#orderResult").removeClass().addClass("alert");
	        	$('#loading').modal('show');
	         }, 
	        success: function(json){	      
	            if(isObj(json)&& json.error==''){
					$("#orderResult").show().html("平仓成功").addClass("alert-success").delay(3000).hide(1000);
					$("#linew_"+order).remove();
	            } 
	            else{alert('failed: ' + json.error);}
	        },
	        error:function(){
	        	alert('operation failed.');
	        },
	        complete:function(){
	        	$("#loading").modal('hide');
	        }
		}) 
	}
</script>
</div>
<?php $this->load->view('footer')?>