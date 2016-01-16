<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/admin.css">

<script type="text/javascript">
        function showTime(){
        	setTimeout(function() {
                //window.location.href=document.referrer IE下document.referrer失效
        		window.location.href=<?php echo isset($_SERVER['HTTP_REFERER']) && empty($url) ? '"'
                 . $_SERVER['HTTP_REFERER'] . '"' : '"'.$url.'"';?>;
            }, 3000);
            
        	var i = 2;
            var time = document.getElementById("return");
    		if(time.innerHTML){
    			var t =setInterval(function(){
    				time.innerHTML = i;
    				i--;
    				if(i < 0){
    					window.clearInterval(t);
    				}
    				},1000)
    		}
        }
        window.onload = showTime;
</script>
</head>
<div class="container">
<div class="jumbotron">
<?php
echo '<p>' . $this->session->flashdata('info') . '</p><p>页面<font id="return" color="red">3</font>秒后自动返回！</p>';
?>
</div>
<?php $this->load->view('admin/footer')?>