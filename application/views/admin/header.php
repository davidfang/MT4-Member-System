<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $title . '&nbsp;-&nbsp;' . $this->config->item('company_name') ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/admin.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.min.css">

<script type="text/javascript" src="<?php echo base_url()?>public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.zh-CN.js"></script>

</head>

<body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo site_url('admin/index')?>">后台管理系统
          <span style="color:red;font-size:16px; margin-left:5px">v<?php echo $this->config->item('version') ?></span></a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              欢迎您：<a href="<?php echo site_url('admin')?>" class="navbar-link"><?php echo $this->session->userdata('username');?></a><span style="margin: 0 8px;vertical-align: 1px">|</span><a href="<?php echo site_url('admin/logout')?>" class="navbar-link">退出登录</a>
            </p>
            <ul class="nav">
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"admin/index") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/index')?>">首页</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"member") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/member')?>">会员管理</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"deposit") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/deposit')?>">入金管理</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"withdraw") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/withdraw')?>">出金管理</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"transfer") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/transfer')?>">内部转账</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"message") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/message')?>">系统公告</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"top") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/top')?>" style="color:red">排行榜</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"log") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/logs')?>">系统日志</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"about") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/view/about')?>">关于</a></li>
              <li class=<?php echo stristr($_SERVER['PHP_SELF'],"contact") !==false ? "active" : ""; ?>><a href="<?php echo site_url('admin/view/contact')?>">联系</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>