<!DOCTYPE HTML>
<?php 
//模版
$css_file_name = array(
      'bootstrap.big.min.css',
      'bootstrap.blue.min.css',
      'bootstrap.gray.min.css',
      'bootstrap.orange.min.css',
      'bootstrap.red.min.css',
      'bootstrap.min.css',
      );
if (isset($_COOKIE['default_style']) && 
  in_array($_COOKIE['default_style'], $css_file_name,true)) {
  $template = $_COOKIE['default_style'];
} else {
  $template = 'bootstrap.min.css';
  setcookie('default_style',$css_file_name[5],time()+30*24*60*60,'/');
}
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $title . '&nbsp;-&nbsp;' . $this->config->item('company_name') ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/<?php echo $template ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/admin.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.min.css">

<script src="http://libs.baidu.com/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.zh-CN.js"></script>
<style type="text/css">
body {background-color: #f2f2f2}
#bank_repeat{font-size: 20px;color: green;font-weight: bold;}
.demo{width:240px; height:150px;border:1px #e0e0e0 solid; position:fixed; right:2px; bottom:2px; background-color:rgb(198,252,104);}
.demo .title{height:23px; line-height:23px; background-color:#f8f8f8; font-size:14px; font-family:宋体;padding-left:5px; border-bottom:1px #e0e0e0 solid;}
.demo .title .close{float:right; padding-right:4px; color:red; cursor:pointer;}
.demo .title .close:hover{color:purple;}
.demoContent{font-size:12px; line-height:19px; padding:5px;}
.demoContent p{text-indent:2em;}
</style>
</head>
<?php 
//消息未读提醒
$CI = &get_instance();
$CI->load->model('messagemodel');
$CI->load->model('usermessagemodel');
$allMsg = $CI->messagemodel->getItemsNum(' where status = 0');
$readedMsg = $CI->usermessagemodel->getReadedMessageCount($CI->session->userdata('user_id'));
$unReadedMsg = $allMsg - $readedMsg;
$_SESSION['unReadedMsg'] = ((is_integer($unReadedMsg) && $unReadedMsg >=0) ? $unReadedMsg : 0);
?>
<body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo site_url('user')?>">会员中心
            <span style="color:red;font-size:16px; margin-left:5px">v<?php echo $this->config->item('version') ?></span></a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              欢迎您：<a href="<?php echo site_url('user')?>" class="navbar-link"><?php echo $this->session->userdata('username');?></a><span style="margin: 0 8px;vertical-align: 1px">|</span><a href="<?php echo site_url('user/logout')?>" class="navbar-link">退出登录</a>
            </p>
            <ul class="nav">
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"user/index") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/index')?>">首页</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"huichao") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/deposit')?>">在线入金</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"withdraw") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/withdraw')?>">在线出金</a></li>
              <li <?php 
              $data = require (APPPATH . 'config/mail.php');
              if ($data['is_zz'] != 1) {
                echo ' style="display:none "';
              }?>
              class=<?php echo stristr($_SERVER['PHP_SELF'],"transfer") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/transfer')?>">内部转账</a></li>
              <li <?php 
              if (!$this->config->item('webtrade')) {
                echo ' style="display:none "';
              }?>
              data-toggle="tooltip" title="请使用Chrome、Firefox或Opera等现代浏览器访问，其他浏览器访问不保证能正常使用本站所有功能" class=<?php echo stristr($_SERVER['PHP_SELF'],"trade/index") !==false ? "active" : ""; ?>><a class="hot" href="<?php echo site_url('trade/index')?>" style="color:red">在线交易</a></li>
              <li <?php 
              if (!$this->config->item('webtrade')) {
                echo ' style="display:none "';
              }?>
              data-toggle="tooltip" title="请使用Chrome、Firefox或Opera等现代浏览器访问，其他浏览器访问不保证能正常使用本站所有功能" class=<?php echo stristr($_SERVER['PHP_SELF'],"trade/chart") !==false ? "active" : ""; ?>><a id="quotations" href="<?php echo site_url('trade/chart')?>">行情查看</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"message") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/message')?>" style="color:red">系统公告</a><span id ="unReadedMsg" class="badge badge-important" style="position: absolute;left: 557px;top: 3px;display:none">0</span></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"about") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/view/about')?>">关于</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"contact") !==false ? "active" : ""; ?>><a href="<?php echo site_url('user/view/contact')?>">联系</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>