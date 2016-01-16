<?php defined('BASEPATH') OR exit('No direct script access allowed');
return array(
'deposit_time'=>array('start'=>0,'end'=>24),
'deposit_time_exception'=>array(),
'withdraw_time'=>array('start'=>0,'end'=>24),
'withdraw_time_exception'=>array(),
'withdraw_max_amount_single_transaction'=>200000,
'withdraw_max_amount_per_day'=>500000,
'withdraw_max_amount_exception'=>array(),
'withdraw_blacklist'=>array(),
'withdraw_max_times_per_day'=>3,
);