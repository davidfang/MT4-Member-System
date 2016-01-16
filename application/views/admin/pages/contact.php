<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="jumbotron">
<h1>电子邮箱：<a href="<?php echo 'mailto:' . $this->config->item('contact') ?>"><?php echo $this->config->item('contact') ?></a></h1>
</div>
<?php $this->load->view('admin/footer')?>