<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>会员最新动态</title>
  <style type="text/css">
    ul,li{
      list-style: none;
      margin: 0;
      padding: 0;
    }
    body{
      font-size: 12px;
    }
    li:hover{
      color: #cd0200;
      border: 1px #cd0200 dashed;
    }
    li{
      width: 320px;
      height: 26px;
      line-height: 26px;
      overflow: hidden;
      border: 1px dashed gray;
      float:left;
      margin-top: 3px;
      margin-left: 5px;
    }
    #my_wrapper{
      overflow: hidden;
      width: 1000px;
      border: 1px solid #d2e1f1;
    }
    #log_header{
      padding: 7px 16px 2px;
    }
    #log_header h2{
      font-size: 14px;
      color: #458fce;
      border-bottom: solid 1px #d2e1f1;
    }
    #log_content{
      padding: 3px 10px 9px 5px;
    }
    span{
      color: red;
    }
  </style>
  <script type="text/javascript" src="<?php echo base_url()?>public/js/jquery.min.js"></script>
  <script type="text/javascript">
  	$(function(){
  		function getNews(){
  			$.ajax({
  				url:"<?php echo site_url('common/trends') ?>",
  				type:'post',
  				success:function(data){
            if (data=="0") {
              $("#log_content").text('').append("记录获取失败或为空");
              return;
            };
            var list = $.parseJSON(data);
            var str = "";

            for (var i = list.length - 1; i >= 0; i--) {
              str += "<li>" + list[i] + "</li>"
            };
            str = "<ul>" + str + "</ul>";

  					$("#log_content").text('').append(str);
  				},
  				fail:function(data) {
  					$("#log_content").text('').append('error');
  				}
  			});
  		}

      var log = setInterval(getNews,5000);

      $('#log_content').mouseenter(function(){
        clearInterval(log);
      });

      $('#log_content').mouseleave(function(){
        log = setInterval(getNews,5000);
      });
  	});
  </script>
</head>
<body>
<div id="my_wrapper">
  <div id="log_header"><h2>会员最新动态</h2></div>
  <div id="log_content"></div>
</div>
</body>
</html>