# 系统简介
该系统基于MT4交易平台，与MT4交易平台无缝对接。主要实现了MT4会员管理各项功能，可实现会员在线开户/自动出金/自动入金/内部转账等功能，与MT4服务器对接，集成在线交易功能。

部分交易相关功能后台软件插件基于MT4插件接口用C++开发；web后台利用php、mysql开发，前台基于bootstrap、jquery构建。

# 系统自定义配置文件
* application/config/mail.php
* application/config/deposit.php
* application/config/production/*
* /kf/common/registration_real/zh-cn/index.php

系统更新时请注意被误替换。

# 环境：
- php 5.4.x
- mysql 5.5.x
- 需开启的相关PHP扩展：mysql、socket
- 环迅支付需启用PHP SOAP扩展
- 快钱支付需启用PHP openssl扩展
- 启用会员动态、后台部分限制功能需安装resdis

# 安装步骤
* 导入sql目录下的数据库文件
* 配置application/config/database.php文件，对数据库连接信息进行设置
* 配置application/config/config.php文件，对网站全局信息进行设置
* 配置application/config/mail.php文件，对邮件发送相关信息进行设置