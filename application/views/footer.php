<div class="footer">
<script language='javascript' src="<?php echo base_url()?>public/js/zh.js"></script>
<a href="javascript:zh_tran('s');" class="zh_click" id="zh_click_s">简体</a>
<a href="javascript:zh_tran('t');" class="zh_click" id="zh_click_t">繁體</a>
    <div class="container">
        <p>Copyright &copy; 2012-2013 <a href="<?php echo $this->config->item('company_url') ?>" target="_blank"><?php echo $this->config->item('company_name') ?></a>, All Rights Reserved.</p>
    </div>
</div>
</div>
<div class="demo" id="tip_window" style="display:none">
	<div class="title"><span class="close" title="关闭" id="close_tip">×</span><strong>系统公告</strong></div>
    <div class="demoContent">
    	<p>温馨提醒：</p>
    	<p>您有<font color="red">
    		<?php 
    		$_SESSION['unReadedMsg'] = isset($_SESSION['unReadedMsg']) ? $_SESSION['unReadedMsg'] : 0;
    		echo $_SESSION['unReadedMsg'];
    		 ?>
    	</font>条未读系统公告。</p>
    	<p><a href="<?php echo site_url('user/message') ?>">->点击我前往查看-></a></p>
    </div>
</div>
<script type="text/javascript">
	$(function(){
		var unReadedMsgNum = <?php echo $_SESSION['unReadedMsg'] ?>;
		if (unReadedMsgNum > 0) {
			$("#unReadedMsg").show().text(unReadedMsgNum);
			$("#tip_window").slideDown("slow");
		}

		$("#close_tip").click(function(){
			$("#tip_window").slideUp("slow");
		});
	});
</script>
</body>
</html>