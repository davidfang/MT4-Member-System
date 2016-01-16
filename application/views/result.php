<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>结果</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/admin.css">

</head>
<div class="container">
<div class="jumbotron">
<?php
$url = ($this->session->flashdata('return_url') === false) ? $_SERVER['HTTP_REFERER'] : $this->session->flashdata('return_url');
echo '<p>' . $this->session->flashdata('info') . '</p><p><a href="' . $url . '">返回</a></p>';
?>
</div>
<?php $this->load->view('footer')?>