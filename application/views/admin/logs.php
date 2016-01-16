<?php $this->load->view('admin/header')?>
<div class="container-fluid">

<!-- 日志start -->
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
	   	       $.post("<?php echo site_url('admin/logs/del')?>",{id:pdata,tb:"deposition"},
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
	<form class="form-inline" id="form_search" method="get">
		<label>日志类别：</label>
		<select class="input-small" name="type">
			<option value="">全部</option>
			<option value="1">普通</option>
			<option value="0">错误</option>
		</select>
		<label>内容：</label>
		<input type="text" class="input-small" id="content" name="content"
			placeholder="内容">
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
	<div id="result" class="alert alert-success" style="display: none"></div>
	<table class="table table-bordered table-condensed table-hover">
		<caption></caption>
		<thead>
			<tr>
				<th>
					<input type="checkbox" name="check_all" id="check_all" />
				</th>
				<th>序号</th>
				<th>日志类别</th>
				<th>内容</th>
				<th>时间</th>
			</tr>
		</thead>
            <?php
												if (! empty ( $list )) {
													foreach ( $list as $v ) {
														?>
                    <tr <?php echo $v['type'] == 0 ? 'class="error"' : 'class="success"' ?>>
			<td>
				<input type="checkbox" name="checkbox"
					value="<?php echo $v['id'] ?>" />
			</td>
			<td><?php echo $v['id'] ?></td>
			<td>
                        <?php
														if ($v ['type'] == 1) {
															echo '普通';
														} else if ($v ['type'] == 0) {
															echo '错误';
														}
														?></td>
			<td><?php echo $v['content'] ?></td>
			<td><?php echo $v['time'] ?></td>
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