<?php $this->load->view('admin/header')?>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view('admin/left')?>
</div>

<div class="span10">
<div>
<h2 align="center"><?php echo $message['title'] ?></h2>
<p align="center" style="color:gray"><?php echo $message['creator'] . '发表于' . $message['addTime'] ?></p>
</div>
<div><?php echo $message['content'] ?></div>
<p align="center"><a href="<?php echo site_url('admin/message') ?>">返回</a></p>
</div>

</div>

<?php $this->load->view('admin/footer')?>