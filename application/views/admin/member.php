<?php $this->load->view('admin/header')?>
<div class="container-fluid">

<!-- 记录start -->
<script type="text/javascript">
$(function() {
        $('#datetimepicker1').datetimepicker({
            language: 'zh-CN'
        });

        $('#datetimepicker2').datetimepicker({
            language: 'zh-CN'
        });

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
	   	       $.post("<?php echo site_url('admin/member/del')?>",{id:pdata},
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

	   	if (window.location.href.indexOf("mtSearch")>0) {
			$("ul.nav-tabs li.active").removeClass("active");
			var li_s = $("#li_mt_s")
			if (!li_s.hasClass("active")) {
				li_s.addClass("active")
			}
		}else{
			$("ul.nav-tabs li.active").removeClass("active");
			var li_s = $("#li_mt_p")
			if (!li_s.hasClass("active")) {
				li_s.addClass("active")
			}
		}
        
    });
</script>
<ul class="nav nav-tabs">
	<li id="li_mt_p" class="active"><a href="#genaral" data-toggle="tab">普通搜索</a></li>
	<li id="li_mt_s"><a href="#mtAccount" data-toggle="tab">MT4账户搜索</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="genaral">
	<form class="form-inline" id="form_search" method="get">
		<label>帐号：</label>
		<input type="text" class="input-small" id="username" name="username"
			placeholder="Account">
		<label>邮箱：</label>
		<input type="text" id="email" name="email" class="input-small"
			placeholder="Email">
		<label class="text">
			时间范围：
			<div id="datetimepicker1" class="input-append date">
				<input data-format="yyyy-MM-dd hh:mm:ss" type="text"
					placeholder="起始时间" name="start_time" id="start_time"></input>
				<span class="add-on">
					<i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i>
				</span>
			</div>
			至
			<div id="datetimepicker2" class="input-append date">
				<input data-format="yyyy-MM-dd hh:mm:ss" type="text"
					placeholder="结束时间" name="end_time" id="end_time"></input>
				<span class="add-on">
					<i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i>
				</span>
			</div>
		</label>
		<button type="submit" class="btn" id="search">搜索</button>
		<button type="button" class="btn" id="delete">删除选中记录</button>
	</form>
</div>
<div class="tab-pane" id="mtAccount">
	<form class="form-inline" id="form_search" method="get" action="<?php echo site_url('admin/mtSearch') ?>">
		<label>MT4账户：</label>
		<input type="text" class="input-small" id="mt_user" name="mt_user"
			placeholder="MT4 Account">
		<label>服务器：</label>
		<select id="server" name="server">
			<?php foreach ($server as $v){?>
			<option value="<?php echo $v['id']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
			<?php }?>
		</select>
		<button type="submit" class="btn" id="search_mt">搜索</button>
	</form>
</div>
</div>
	<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="check_all" id="check_all" />
				</th>
				<th>序号</th>
				<th>帐号</th>
				<th>邮箱</th>
				<th>注册时间</th>
				<th>注册IP</th>
				<th>上次登录时间</th>
				<th>上次登录IP</th>
				<th>代理编号</th>
				<th>操作</th>
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
			<td><?php echo $v['username'] ?></td>
			<td><?php echo $v['email'] ?></td>
			<td><?php echo $v['reg_time'] ?></td>
			<td><?php echo $v['reg_ip'] ?></td>
			<td><?php echo $v['last_login'] ?></td>
			<td><?php echo $v['login_ip'] ?></td>
			<td><?php echo $v['ref_name']; ?></td>
			<td><a href="<?php echo site_url('admin/member/edit/' . $v['id']) ?>">编辑</a></td>
		</tr>
                <?php
													
}
												}
												?>
        </table>
<?php
echo $pageStr;
?>
<!-- 记录end -->

<?php $this->load->view('admin/footer')?>