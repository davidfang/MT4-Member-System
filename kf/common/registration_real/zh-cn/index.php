<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="zh-cn">

<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>申请真实帐户 - XXX公司</title>
<link rel="stylesheet" type="text/css" href="../../../common_themes/common.css" />
<link rel="stylesheet" type="text/css" href="../../../common_themes/blue/common.css" />
<!--[if (!IE)|(gt IE 8)]><!--><link rel="stylesheet" type="text/css" href="../../../common_themes/css3.css" "/><!--<![endif]-->
</head>
<body id="registration_real">
	<?php
		session_start();
		if (!isset($_SESSION['login'])) {
			exit('非法访问，请先登录！');
		}
	?>
<div id="form-body">

<div id="common-form">
<form name="common-form" class="filedform jqueryform" method="post" action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'kf')) . 'index.php/user/real';
?>">
<fieldset>
<div class="steps"><ul><li class="active" id="step1-btn"><span class="l"></span><span class="c">步骤一</span><span class="r"></span></li><li id="step2-btn"><span class="l"></span><span class="c">步骤二</span><span class="r"></span></li><li id="step3-btn"><span class="l"></span><span class="c">步骤三</span><span class="r"></span></li><li id="step4-btn"><span class="l"></span><span class="c">步骤四</span><span class="r"></span></li><li id="step5-btn"><span class="l"></span><span class="c">步骤五</span><span class="r"></span></li><li id="step-last-btn"><span class="l"></span><span class="c">检阅并递交</span><span class="r"></span></li><li id="step-ok"><span class="l"></span><span class="c">完成</span><span class="r"></span></li></ul><div class="clear"></div></div>

<div id="step1" class="stepdiv">
<div class="step-title">填写您的个人联络资料<br/><span class="en">Fill in your personal contact details</span></div>
<div class="fielddiv">
	<h3>A. 个人资料</h3>
	<dl><dt>客户姓名：</dt><dd>
	<div class="two" style="width:15%;margin-right:3%;"><input name="contact_x" type="text" class="input empty"/><p><span class="u">姓氏</span></p></div>
	<div class="two" style="width:26.5%"><input name="contact_m" type="text" class="input empty"/><p><span class="u">名字</span></p></div>
	<div class="two" style="width:50%;margin-left:2%">
	<input name="sex" type="radio" class="radio" value="先生" id="sex_a" checked="checked"/><label class="fl" for="sex_a">先生</label>
	<input name="sex" type="radio" class="radio" value="太太" id="sex_b"/><label class="fl" for="sex_b">太太</label>
	<input name="sex" type="radio" class="radio" value="小姐" id="sex_c"/><label class="fl" for="sex_c">小姐</label>
	<input name="sex" type="radio" class="radio" value="女士" id="sex_d"/><label class="fl" for="sex_d">女士</label></div>
	<div class="clear"></div>
	</dd></dl>
	<dl><dt>联络地址：</dt><dd>
	<div class="two"><select name="country" class="select empty"><option value="Bhutan">不丹</option><option value="East Timor">东帝汶</option><option value="China" selected="selected">中国</option><option value="Central African Republic">中非共和国</option><option value="Denmark">丹麦</option><option value="Ukraine">乌克兰</option><option value="Uzbekistan">乌兹别克斯坦</option><option value="Uganda">乌干达</option><option value="Uruguay">乌拉圭</option><option value="Chad">乍得</option><option value="Jordan">乔丹</option><option value="Georgia">乔治亚</option><option value="Yemen">也门</option><option value="Armenia">亚美尼亚</option><option value="Israel">以色列</option><option value="Iraq">伊拉克</option><option value="Iran (Islamic Republic Of)">伊朗（伊斯兰共和国）</option><option value="Belize">伯利兹</option><option value="Cape Verde">佛得角</option><option value="Russian Federation">俄罗斯联邦</option><option value="Bulgaria">保加利亚</option><option value="Croatia (Local Name: Hrvatska)">克罗地亚（当地名称：赫尔瓦次卡）</option><option value="Guam">关岛</option><option value="Gambia">冈比亚</option><option value="Iceland">冰岛</option><option value="Guinea">几内亚</option><option value="Guinea-Bissau">几内亚比绍</option><option value="Liechtenstein">列支敦士登</option><option value="Congo">刚果</option><option value="Congo, The Democratic Republic Of The">刚果，在民主共和国</option><option value="Liberia">利比里亚</option><option value="Macedonia, Former Yugoslav Republic Of">前南斯拉夫马其顿 前南斯拉夫的共和国</option><option value="Canada">加拿大</option><option value="Ghana">加纳</option><option value="Gabon">加蓬</option><option value="Hungary">匈牙利</option><option value="Northern Mariana Islands">北马里亚纳群岛</option><option value="Yugoslavia">南斯拉夫</option><option value="Antarctica">南极洲</option><option value="South Georgia, South Sandwich Islands">南格鲁吉亚，南桑威奇群岛</option><option value="South Africa">南非</option><option value="Botswana">博茨瓦纳</option><option value="Qatar">卡塔尔</option><option value="Rwanda">卢旺达</option><option value="Luxembourg">卢森堡</option><option value="Indonesia">印尼</option><option value="India">印度</option><option value="Guatemala">危地马拉</option><option value="Ecuador">厄瓜多尔</option><option value="Eritrea">厄立特里亚</option><option value="Cuba">古巴</option><option value="Taiwan">台湾</option><option value="Kyrgyzstan">吉</option><option value="Djibouti">吉布提</option><option value="Kazakhstan">哈萨克斯坦</option><option value="Colombia">哥伦比亚</option><option value="Costa Rica">哥斯达黎加</option><option value="Cameroon">喀麦隆</option><option value="Reunion">团圆</option><option value="Tuvalu">图瓦卢</option><option value="Turkmenistan">土库曼斯坦</option><option value="Turkey">土耳其</option><option value="Saint Kitts And Nevis">圣基茨和尼维斯</option><option value="Sao Tome And Principe">圣多美和普林西比</option><option value="Saint Vincent And The Grenadines">圣文森特和格林纳丁斯</option><option value="St. Helena">圣海伦娜</option><option value="St. Pierre And Miquelon">圣皮埃尔和密克隆</option><option value="Christmas Island">圣诞岛</option><option value="Saint Lucia">圣露西亚</option><option value="San Marino">圣马力诺</option><option value="Guyana">圭亚那</option><option value="Tanzania, United Republic Of">坦桑尼亚联合共和国，美利坚 共和国</option><option value="Egypt">埃及</option><option value="Ethiopia">埃塞俄比亚</option><option value="Kiribati">基里巴斯</option><option value="Tajikistan">塔吉克斯坦</option><option value="Senegal">塞内加尔</option><option value="Sierra Leone">塞拉利昂</option><option value="Cyprus">塞浦路斯</option><option value="Seychelles">塞舌尔</option><option value="Mexico">墨西哥</option><option value="Togo">多哥</option><option value="Dominica">多米尼加</option><option value="Dominican Republic">多米尼加共和国</option><option value="Korea, Republic Of">大韩民国，共和国</option><option value="Austria">奥地利</option><option value="Venezuela">委内瑞拉</option><option value="Bangladesh">孟加拉</option><option value="Angola">安哥拉</option><option value="Anguilla">安圭拉</option><option value="Antigua And Barbuda">安提瓜和巴布达</option><option value="Andorra">安道尔</option><option value="Micronesia, Federated States Of">密克罗尼西亚联邦 的国家</option><option value="Nicaragua">尼加拉瓜</option><option value="Nigeria">尼日利亚</option><option value="Niger">尼日尔</option><option value="Nepal">尼泊尔</option><option value="Bahamas">巴哈马</option><option value="Pakistan">巴基斯坦</option><option value="Barbados">巴巴多斯</option><option value="Papua New Guinea">巴布亚新几内亚</option><option value="Paraguay">巴拉圭</option><option value="Panama">巴拿马</option><option value="Bahrain">巴林</option><option value="Brazil">巴西</option><option value="Burkina Faso">布基那法索</option><option value="Bouvet Island">布维岛</option><option value="Burundi">布隆迪</option><option value="Greece">希腊</option><option value="Palau">帕劳</option><option value="Cook Islands">库克群岛</option><option value="Cayman Islands">开曼群岛</option><option value="Germany">德国</option><option value="Italy">意大利</option><option value="Solomon Islands">所罗门群岛</option><option value="Tokelau">托克劳</option><option value="Latvia">拉脱维亚</option><option value="Norway">挪威</option><option value="Czech Republic">捷克共和国</option><option value="Moldova, Republic Of">摩尔多瓦共和国</option><option value="Morocco">摩洛哥</option><option value="Monaco">摩纳哥</option><option value="Brunei Darussalam">文莱达鲁萨兰国</option><option value="Fiji">斐济</option><option value="Swaziland">斯威士兰</option><option value="Slovakia (Slovak Republic)">斯洛伐克（斯洛伐克共和国)</option><option value="Slovenia">斯洛文尼亚</option><option value="Svalbard And Jan Mayen Islands">斯瓦尔巴群岛和扬马延群岛</option><option value="Sri Lanka">斯里兰卡</option><option value="Singapore">新加坡</option><option value="New Caledonia">新喀里多尼亚</option><option value="New Zealand">新西兰</option><option value="Japan">日本</option><option value="Chile">智利</option><option value="Korea, Democratic People's Republic Of">朝鲜民主人民'其中的共和国</option><option value="Cambodia">柬埔寨</option><option value="Grenada">格林纳达</option><option value="Greenland">格陵兰</option><option value="Belgium">比利时</option><option value="Mauritania">毛里塔尼亚</option><option value="Mauritius">毛里求斯</option><option value="Tonga">汤加</option><option value="Saudi Arabia">沙特阿拉伯</option><option value="France">法国</option><option value="French Southern Territories">法国南部领土</option><option value="France, Metropolitan">法国本土</option><option value="French Guiana">法属圭亚那</option><option value="French Polynesia">法属波利尼西亚</option><option value="Faroe Islands">法罗群岛</option><option value="Poland">波兰</option><option value="Puerto Rico">波多黎各</option><option value="Bosnia And Herzegowina">波斯尼亚和黑塞哥维那</option><option value="Thailand">泰国</option><option value="Zimbabwe">津巴布韦</option><option value="Honduras">洪都拉斯</option><option value="Haiti">海地</option><option value="Australia">澳大利亚</option><option value="Macau">澳门</option><option value="Ireland">爱尔兰</option><option value="Estonia">爱沙尼亚</option><option value="Jamaica">牙买加</option><option value="Turks And Caicos Islands">特克斯和凯科斯群岛</option><option value="Trinidad And Tobago">特立尼达和多巴哥</option><option value="Bolivia">玻利维亚</option><option value="Nauru">瑙鲁</option><option value="Sweden">瑞典</option><option value="Switzerland">瑞士</option><option value="Guadeloupe">瓜德罗普岛</option><option value="Wallis And Futuna Islands">瓦利斯群岛和富图纳群岛</option><option value="Vanuatu">瓦努阿图</option><option value="Belarus">白俄罗斯</option><option value="Bermuda">百慕大</option><option value="Pitcairn">皮特凯恩</option><option value="Gibraltar">直布罗陀</option><option value="Falkland Islands (Malvinas)">福克兰群岛 （马尔维纳斯）</option><option value="Kuwait">科威特</option><option value="Comoros">科摩罗</option><option value="Cote D'Ivoire">科特Ð '科特迪瓦</option><option value="Cocos (Keeling) Islands">科科斯（基林）群岛</option><option value="Peru">秘鲁</option><option value="Tunisia">突尼斯</option><option value="Lithuania">立陶宛</option><option value="Somalia">索马里</option><option value="Namibia">纳米比亚</option><option value="Niue">纽埃</option><option value="Virgin Islands (British)">维尔京群岛</option><option value="Myanmar">缅甸</option><option value="Romania">罗马尼亚</option><option value="Holy See (Vatican City State)">罗马教廷（梵蒂冈城国）</option><option value="American Samoa">美属萨摩亚</option><option value="Lao People's Democratic Republic">老挝人民\的民主共和国</option><option value="Kenya">肯尼亚</option><option value="Finland">芬兰</option><option value="Sudan">苏丹</option><option value="Suriname">苏里南</option><option value="United Kingdom">英国</option><option value="British Indian Ocean Territory">英属印度洋领地</option><option value="Netherlands">荷兰</option><option value="Netherlands Antilles">荷属安的列斯</option><option value="Mozambique">莫桑比克</option><option value="Lesotho">莱索托</option><option value="Philippines">菲律宾</option><option value="El Salvador">萨尔瓦多</option><option value="Samoa">萨摩亚</option><option value="Portugal">葡萄牙</option><option value="Mongolia">蒙古</option><option value="Montserrat">蒙特塞拉特</option><option value="Western Sahara">西撒哈拉</option><option value="Spain">西班牙</option><option value="Norfolk Island">诺福克岛</option><option value="Benin">贝宁</option><option value="Zambia">赞比亚</option><option value="Equatorial Guinea">赤道几内亚</option><option value="Heard And Mc Donald Islands">赫德和麦克唐纳群岛</option><option value="Viet Nam">越南</option><option value="Azerbaijan">阿塞拜疆</option><option value="Afghanistan">阿富汗</option><option value="Algeria">阿尔及利亚</option><option value="Albania">阿尔巴尼亚</option><option value="Libyan Arab Jamahiriya">阿拉伯利比亚民众国</option><option value="Syrian Arab Republic">阿拉伯叙利亚共和国</option><option value="United Arab Emirates">阿拉伯联合酋长国</option><option value="Oman">阿曼</option><option value="Argentina">阿根廷</option><option value="Aruba">阿鲁巴</option><option value="Hong Kong">香港</option><option value="Maldives">马尔代夫</option><option value="Malawi">马拉维</option><option value="Martinique">马提尼克岛</option><option value="Malaysia">马来西亚</option><option value="Mayotte">马约特</option><option value="Marshall Islands">马绍尔群岛</option><option value="Malta">马耳他</option><option value="Madagascar">马达加斯加</option><option value="Mali">马里</option><option value="Lebanon">黎巴嫩</option></select><p><span class="u">国籍</span></p></div>
	<div class="two"><select name="country_res" class="select empty"><option value="Bhutan">不丹</option><option value="East Timor">东帝汶</option><option value="China" selected="selected">中国</option><option value="Central African Republic">中非共和国</option><option value="Denmark">丹麦</option><option value="Ukraine">乌克兰</option><option value="Uzbekistan">乌兹别克斯坦</option><option value="Uganda">乌干达</option><option value="Uruguay">乌拉圭</option><option value="Chad">乍得</option><option value="Jordan">乔丹</option><option value="Georgia">乔治亚</option><option value="Yemen">也门</option><option value="Armenia">亚美尼亚</option><option value="Israel">以色列</option><option value="Iraq">伊拉克</option><option value="Iran (Islamic Republic Of)">伊朗（伊斯兰共和国）</option><option value="Belize">伯利兹</option><option value="Cape Verde">佛得角</option><option value="Russian Federation">俄罗斯联邦</option><option value="Bulgaria">保加利亚</option><option value="Croatia (Local Name: Hrvatska)">克罗地亚（当地名称：赫尔瓦次卡）</option><option value="Guam">关岛</option><option value="Gambia">冈比亚</option><option value="Iceland">冰岛</option><option value="Guinea">几内亚</option><option value="Guinea-Bissau">几内亚比绍</option><option value="Liechtenstein">列支敦士登</option><option value="Congo">刚果</option><option value="Congo, The Democratic Republic Of The">刚果，在民主共和国</option><option value="Liberia">利比里亚</option><option value="Macedonia, Former Yugoslav Republic Of">前南斯拉夫马其顿 前南斯拉夫的共和国</option><option value="Canada">加拿大</option><option value="Ghana">加纳</option><option value="Gabon">加蓬</option><option value="Hungary">匈牙利</option><option value="Northern Mariana Islands">北马里亚纳群岛</option><option value="Yugoslavia">南斯拉夫</option><option value="Antarctica">南极洲</option><option value="South Georgia, South Sandwich Islands">南格鲁吉亚，南桑威奇群岛</option><option value="South Africa">南非</option><option value="Botswana">博茨瓦纳</option><option value="Qatar">卡塔尔</option><option value="Rwanda">卢旺达</option><option value="Luxembourg">卢森堡</option><option value="Indonesia">印尼</option><option value="India">印度</option><option value="Guatemala">危地马拉</option><option value="Ecuador">厄瓜多尔</option><option value="Eritrea">厄立特里亚</option><option value="Cuba">古巴</option><option value="Taiwan">台湾</option><option value="Kyrgyzstan">吉</option><option value="Djibouti">吉布提</option><option value="Kazakhstan">哈萨克斯坦</option><option value="Colombia">哥伦比亚</option><option value="Costa Rica">哥斯达黎加</option><option value="Cameroon">喀麦隆</option><option value="Reunion">团圆</option><option value="Tuvalu">图瓦卢</option><option value="Turkmenistan">土库曼斯坦</option><option value="Turkey">土耳其</option><option value="Saint Kitts And Nevis">圣基茨和尼维斯</option><option value="Sao Tome And Principe">圣多美和普林西比</option><option value="Saint Vincent And The Grenadines">圣文森特和格林纳丁斯</option><option value="St. Helena">圣海伦娜</option><option value="St. Pierre And Miquelon">圣皮埃尔和密克隆</option><option value="Christmas Island">圣诞岛</option><option value="Saint Lucia">圣露西亚</option><option value="San Marino">圣马力诺</option><option value="Guyana">圭亚那</option><option value="Tanzania, United Republic Of">坦桑尼亚联合共和国，美利坚 共和国</option><option value="Egypt">埃及</option><option value="Ethiopia">埃塞俄比亚</option><option value="Kiribati">基里巴斯</option><option value="Tajikistan">塔吉克斯坦</option><option value="Senegal">塞内加尔</option><option value="Sierra Leone">塞拉利昂</option><option value="Cyprus">塞浦路斯</option><option value="Seychelles">塞舌尔</option><option value="Mexico">墨西哥</option><option value="Togo">多哥</option><option value="Dominica">多米尼加</option><option value="Dominican Republic">多米尼加共和国</option><option value="Korea, Republic Of">大韩民国，共和国</option><option value="Austria">奥地利</option><option value="Venezuela">委内瑞拉</option><option value="Bangladesh">孟加拉</option><option value="Angola">安哥拉</option><option value="Anguilla">安圭拉</option><option value="Antigua And Barbuda">安提瓜和巴布达</option><option value="Andorra">安道尔</option><option value="Micronesia, Federated States Of">密克罗尼西亚联邦 的国家</option><option value="Nicaragua">尼加拉瓜</option><option value="Nigeria">尼日利亚</option><option value="Niger">尼日尔</option><option value="Nepal">尼泊尔</option><option value="Bahamas">巴哈马</option><option value="Pakistan">巴基斯坦</option><option value="Barbados">巴巴多斯</option><option value="Papua New Guinea">巴布亚新几内亚</option><option value="Paraguay">巴拉圭</option><option value="Panama">巴拿马</option><option value="Bahrain">巴林</option><option value="Brazil">巴西</option><option value="Burkina Faso">布基那法索</option><option value="Bouvet Island">布维岛</option><option value="Burundi">布隆迪</option><option value="Greece">希腊</option><option value="Palau">帕劳</option><option value="Cook Islands">库克群岛</option><option value="Cayman Islands">开曼群岛</option><option value="Germany">德国</option><option value="Italy">意大利</option><option value="Solomon Islands">所罗门群岛</option><option value="Tokelau">托克劳</option><option value="Latvia">拉脱维亚</option><option value="Norway">挪威</option><option value="Czech Republic">捷克共和国</option><option value="Moldova, Republic Of">摩尔多瓦共和国</option><option value="Morocco">摩洛哥</option><option value="Monaco">摩纳哥</option><option value="Brunei Darussalam">文莱达鲁萨兰国</option><option value="Fiji">斐济</option><option value="Swaziland">斯威士兰</option><option value="Slovakia (Slovak Republic)">斯洛伐克（斯洛伐克共和国)</option><option value="Slovenia">斯洛文尼亚</option><option value="Svalbard And Jan Mayen Islands">斯瓦尔巴群岛和扬马延群岛</option><option value="Sri Lanka">斯里兰卡</option><option value="Singapore">新加坡</option><option value="New Caledonia">新喀里多尼亚</option><option value="New Zealand">新西兰</option><option value="Japan">日本</option><option value="Chile">智利</option><option value="Korea, Democratic People's Republic Of">朝鲜民主人民'其中的共和国</option><option value="Cambodia">柬埔寨</option><option value="Grenada">格林纳达</option><option value="Greenland">格陵兰</option><option value="Belgium">比利时</option><option value="Mauritania">毛里塔尼亚</option><option value="Mauritius">毛里求斯</option><option value="Tonga">汤加</option><option value="Saudi Arabia">沙特阿拉伯</option><option value="France">法国</option><option value="French Southern Territories">法国南部领土</option><option value="France, Metropolitan">法国本土</option><option value="French Guiana">法属圭亚那</option><option value="French Polynesia">法属波利尼西亚</option><option value="Faroe Islands">法罗群岛</option><option value="Poland">波兰</option><option value="Puerto Rico">波多黎各</option><option value="Bosnia And Herzegowina">波斯尼亚和黑塞哥维那</option><option value="Thailand">泰国</option><option value="Zimbabwe">津巴布韦</option><option value="Honduras">洪都拉斯</option><option value="Haiti">海地</option><option value="Australia">澳大利亚</option><option value="Macau">澳门</option><option value="Ireland">爱尔兰</option><option value="Estonia">爱沙尼亚</option><option value="Jamaica">牙买加</option><option value="Turks And Caicos Islands">特克斯和凯科斯群岛</option><option value="Trinidad And Tobago">特立尼达和多巴哥</option><option value="Bolivia">玻利维亚</option><option value="Nauru">瑙鲁</option><option value="Sweden">瑞典</option><option value="Switzerland">瑞士</option><option value="Guadeloupe">瓜德罗普岛</option><option value="Wallis And Futuna Islands">瓦利斯群岛和富图纳群岛</option><option value="Vanuatu">瓦努阿图</option><option value="Belarus">白俄罗斯</option><option value="Bermuda">百慕大</option><option value="Pitcairn">皮特凯恩</option><option value="Gibraltar">直布罗陀</option><option value="Falkland Islands (Malvinas)">福克兰群岛 （马尔维纳斯）</option><option value="Kuwait">科威特</option><option value="Comoros">科摩罗</option><option value="Cote D'Ivoire">科特Ð '科特迪瓦</option><option value="Cocos (Keeling) Islands">科科斯（基林）群岛</option><option value="Peru">秘鲁</option><option value="Tunisia">突尼斯</option><option value="Lithuania">立陶宛</option><option value="Somalia">索马里</option><option value="Namibia">纳米比亚</option><option value="Niue">纽埃</option><option value="Virgin Islands (British)">维尔京群岛</option><option value="Myanmar">缅甸</option><option value="Romania">罗马尼亚</option><option value="Holy See (Vatican City State)">罗马教廷（梵蒂冈城国）</option><option value="American Samoa">美属萨摩亚</option><option value="Lao People's Democratic Republic">老挝人民\的民主共和国</option><option value="Kenya">肯尼亚</option><option value="Finland">芬兰</option><option value="Sudan">苏丹</option><option value="Suriname">苏里南</option><option value="United Kingdom">英国</option><option value="British Indian Ocean Territory">英属印度洋领地</option><option value="Netherlands">荷兰</option><option value="Netherlands Antilles">荷属安的列斯</option><option value="Mozambique">莫桑比克</option><option value="Lesotho">莱索托</option><option value="Philippines">菲律宾</option><option value="El Salvador">萨尔瓦多</option><option value="Samoa">萨摩亚</option><option value="Portugal">葡萄牙</option><option value="Mongolia">蒙古</option><option value="Montserrat">蒙特塞拉特</option><option value="Western Sahara">西撒哈拉</option><option value="Spain">西班牙</option><option value="Norfolk Island">诺福克岛</option><option value="Benin">贝宁</option><option value="Zambia">赞比亚</option><option value="Equatorial Guinea">赤道几内亚</option><option value="Heard And Mc Donald Islands">赫德和麦克唐纳群岛</option><option value="Viet Nam">越南</option><option value="Azerbaijan">阿塞拜疆</option><option value="Afghanistan">阿富汗</option><option value="Algeria">阿尔及利亚</option><option value="Albania">阿尔巴尼亚</option><option value="Libyan Arab Jamahiriya">阿拉伯利比亚民众国</option><option value="Syrian Arab Republic">阿拉伯叙利亚共和国</option><option value="United Arab Emirates">阿拉伯联合酋长国</option><option value="Oman">阿曼</option><option value="Argentina">阿根廷</option><option value="Aruba">阿鲁巴</option><option value="Hong Kong">香港</option><option value="Maldives">马尔代夫</option><option value="Malawi">马拉维</option><option value="Martinique">马提尼克岛</option><option value="Malaysia">马来西亚</option><option value="Mayotte">马约特</option><option value="Marshall Islands">马绍尔群岛</option><option value="Malta">马耳他</option><option value="Madagascar">马达加斯加</option><option value="Mali">马里</option><option value="Lebanon">黎巴嫩</option></select><p><span class="u">居住国家</span></p></div>
	<div class="clear"></div>
	</dd></dl>

	<dl><dt></dt><dd>
	<div class="two"><input name="province" type="text" class="input empty"/><p>所属<span class="u">直辖市/省份</span></p></div>
	<div class="two"><input name="city" type="text" class="input empty"/><p>所属<span class="u">城市</span></p></div>
	<div class="clear"></div>
	</dd></dl>

	<dl><dt></dt><dd><input name="address" type="text" class="input empty"/><p>您的<span class="u">详细地址</span></p></dd></dl>

	<dl><dt>证件信息：</dt><dd>
	<div class="two"><input name="card" type="text" class="input en empty" onkeyup="tobirthday($(this),'birthday')" onblur="tobirthday($(this),'birthday')"/><p>您的<span class="u">证件号码</span></p></div>
	<div class="two"><input name="birthday" type="text" class="input en empty"/><p>您的<span class="u">出生年月日</span></p></div>
	<div class="clear"></div>
	</dd></dl>
</div>

<div class="fielddiv">
	<h3>B. 联络资料</h3>
	<dl><dt>电话号码：</dt><dd>
	<div class="two" style="width:30%;"><input name="phone_code" type="text" class="input en empty" value="0086"/><p><span class="u">国家代码</span></p></div>
	<div class="two" style="width:66%;"><input name="phone" type="text" class="input en empty"/><p><span class="u">电话号码</span></p></div>
	</dd></dl>

	<!--<dl><dt>手机号码：</dt><dd>
	<div class="two" style="width:30%;"><input name="mobile_code" type="text" class="input en empty" value="0086"/><p><span class="u">国家代码</span></p></div>
	<div class="two"><input name="mobile" type="text" class="input en empty"/><p><span class="u">手机号码</span></p></div>
	<div class="clear"></div>
	</dd></dl>-->
    <dl><dt>手机号码：</dt><dd><input name="mobile" type="text" class="input empty"/><p><span class="u">手机号码</span></p></dd></dl>

	<dl><dt>其它：</dt><dd>
	<div class="two"><input name="email" type="text" class="input en empty email"/><p>您的<span class="u">电子信箱</span></p></div>
	<div class="two"><input name="qq" type="text" class="input en"/><p>您的<span class="u en">QQ</span></p></div>
	<div class="clear"></div>
	</dd></dl>
</div>
<div class="submit" style="width:222px;"><button type="button" name="nextbutton" class="orange"><span><em class="icon_next"></em>继续，进入下一步</span></button><div class="clear"></div></div>
</div>

<div id="step2" class="stepdiv">
<div class="step-title">填写经纪商&amp;收款银行资料<br/><span class="en">Fill in the broker &amp; Beneficiary Bank data</span></div>
	<div class="fielddiv">
		<h3>A. 帐户性质</h3>
		<dl><dt>帐户类型：</dt><dd>
		<div class="two">
			<input name="usertype" type="radio" class="radio" value="标准账户" id="usertype_a" checked="checked"/><label class="fl" for="usertype_a">标准账户</label>
			<input name="usertype" type="radio" class="radio" value="微型账户" id="usertype_b"/><label class="fl" for="usertype_b">微型账户</label>
			<div class="clear"></div>
			<p>选择<span class="u">帐户类型</span></p>
		</div>
		<div class="two">
			<input name="lever" type="radio" class="radio" value="1:100"  checked="checked"/><label class="fl en" for="lever_a">1:100</label>
			<input name="lever" type="radio" class="radio" value="1:200" /><label class="fl en" for="lever_b">1:200</label>
			<input name="lever" type="radio" class="radio" value="1:300" /><label class="fl en" for="lever_c">1:300</label><br/>
			<input name="lever" type="radio" class="radio" value="1:400" /><label class="fl en" for="lever_d">1:400</label>
            <input name="lever" type="radio" class="radio" value="1:500" /><label class="fl en" for="lever_d">1:500</label>
			<div class="clear"></div>
			<p>选择<span class="u">杠杆比例</span></p>
		</div>
		</dd></dl>
		<dl><dt>计划注资：</dt><dd><input name="injectionof" type="text" class="input en empty money" onkeyup="this.value=this.value.replace(/[\D]/g,'');"/><p>计划<span class="u">注资金额</span> <span class="red bold en">USD</span></p></dd></dl>
	</div>

	<div class="fielddiv">
		<h3>B. 经纪商&amp;代理商</h3>
		<dl style="display:none"><dt>经纪商：</dt><dd>
		<div class="two"><input name="brokerid" type="text" class="input en empty digit digits" value="XXXXXX" /><p>经纪商<span class="u">ID</span></p></div>
		<div class="two"><input name="brokername" type="text" class="input" value="XXXXXX" /><p>经纪商<span class="u">名称</span></p></div>
		<div class="clear"></div>
		</dd></dl>

		<dl><dt>代理商：</dt><dd>
		<div class="two"><input name="agentid" type="text" class="input en empty"/><p>代理商<span class="u">ID</span>-若无请填写0</p></div>
		<div class="two"><input name="agentname" type="text" class="input empty"/><p>代理商<span class="u">名称</span>-若无请填写none</p></div>
		<div class="clear"></div>
		</dd></dl>
	</div>

	<div class="fielddiv">
		<h3>C. 收款银行资料</h3>
		<dl><dt>开户银行：</dt><dd>
		<div class="two"><input name="bank" type="text" class="input cn empty"/><p>收款帐户的<span class="u">开户行</span></p></div>
		<div class="two"><input name="banksub" type="text" class="input cn"/><p>收款帐户的<span class="u">开户行分行</span></p></div>
		<div class="clear"></div>
		</dd></dl>
		<dl style="display:none"><dt>银行代码：</dt><dd>
		<div class="two"><input name="bankcode" type="text" class="input en empty" value="SWIFT" /><p>收款银行的 <span class="en u">SWIFT CODE</span></p></div>
		<div class="two"><input name="bankaddress" type="text" class="input empty" value="默认地址" /><p>收款银行的<span class="u">地址</span></p></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>收款帐户：</dt><dd>
		<div class="two"><input name="bankname" type="text" class="input cn empty"/><p>收款帐户的<span class="u">户名</span></p></div>
		<div class="two"><input name="bankcard" type="text" class="input en empty"/><p>收款帐户的<span class="u">卡号</span></p></div>
		<div class="clear"></div>
		</dd></dl>
	</div>
<div class="submit"><button type="button" name="prevbutton" class="orange"><span><em class="icon_prev"></em>返回上一步</span></button><button type="button" name="nextbutton" class="orange"><span><em class="icon_next"></em>继续，进入下一步</span></button><div class="clear"></div></div>
</div>


<div id="step3" class="stepdiv">
	<div class="step-title">填写就业资料&amp;投资经验<br/><span class="en">Complete employment information &amp; investment experience</span></div>
	<div class="fielddiv">
		<h3>A. 就业资料</h3>
		<dl><dt>就业状况：</dt><dd>
		<div class="two"><select name="employment" class="select"><option value="">请选择...</option><option value="受雇">受雇</option><option value="自雇">自雇</option><option value="已退休">已退休</option><option value="失业">失业</option></select><p><span class="u">就业状况</span></p></div>
		<div class="two" id="employment_2"><select name="employment_2" class="select" ><option value="">请选择...</option><option value="行政/文书">行政/文书</option><option value="广告/市场策划/公关界">广告/市场策划/公关界</option><option value="航空">航空</option><option value="艺术,娱乐及媒体界">艺术，娱乐及媒体界</option><option value="经纪/交易者">经纪/交易者</option><option value="建造/地产">建造/地产</option><option value="教育/培训">教育/培训</option><option value="工程/建筑">工程/建筑</option><option value="执行管理">执行管理</option><option value="金融服务/银行">金融服务/银行</option><option value="外汇交易">外汇交易</option><option value="政府/军方">政府/军方</option><option value="医护服务">医护服务</option><option value="款待/旅游">款待/旅游</option><option value="人力资源">人力资源</option><option value="资讯科技界">资讯科技界</option><option value="保险界">保险界</option><option value="执法/紧急事故应变">执法/紧急事故应变</option><option value="法律/法规">法律/法规</option><option value="物流/运输界">物流/运输界</option><option value="制造/生产/营运">制造/生产/营运</option><option value="销售">销售</option><option value="公用事业">公用事业</option></select><p><span class="u">业务性质</span></p></div>
		<div class="two" id="employment_3" style="display:none"><select name="employment_3" class="select" ><option value="">请选择...</option><option value="储蓄">储蓄</option><option value="退休金">退休金</option><option value="政府援助">政府援助</option><option value="礼物馈赠">礼物馈赠</option><option value="遗产继承">遗产继承</option><option value="出售物业">出售物业</option><option value="出售/清算投资">出售/清算投资</option><option value="其它">其它</option></select><p>资金来源</p></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>补充：</dt><dd><input name="employment_content" type="text" class="input"/><p><span class="u">补充说明</span></p></dd></dl>
	</div>

	<div class="fielddiv" id="empiricaldata">
		<h3>B. 投资经验资料</h3>
		<dl class="bold"><dt></dt><dd><div class="two">阁下有多久的交易经验？</div><div class="two">交易频率</div><div class="clear"></div></dd></dl>
		<dl><dt>证券投资：</dt><dd>
		<div class="two"><select name="securities" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="securities_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>期权投资：</dt><dd>
		<div class="two"><select name="options" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="options_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>商品投资：</dt><dd>
		<div class="two"><select name="commodity" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="commodity_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>期货投资：</dt><dd>
		<div class="two"><select name="futures" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="futures_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>外汇投资：</dt><dd>
		<div class="two"><select name="exchange" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="exchange_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dt>差价合约投资：</dt><dd>
		<div class="two"><select name="cfds" class="select"><option value="">请选择...</option><option value="没有">没有</option><option value="一年">一年</option><option value="两年">两年</option><option value="三年">三年</option><option value="四年">四年</option><option value="五年">五年</option><option value="六年">六年</option><option value="七年">七年</option><option value="八年">八年</option><option value="九年">九年</option><option value="十年">十年</option><option value="十一年">十一年</option><option value="十二年">十二年</option><option value="十三年">十三年</option><option value="十四年">十四年</option><option value="十五年">十五年</option><option value="十六年">十六年</option><option value="十七年">十七年</option><option value="十八年">十八年</option><option value="十九年">十九年</option><option value="二十年">二十年</option><option value="二十一年">二十一年</option><option value="二十二年">二十二年</option><option value="二十三年">二十三年</option><option value="二十四年">二十四年</option><option value="二十五年以上">二十五年以上</option></select></div>
		<div class="two"><select name="cfds_frequency" class="select" ><option value="">请选择...</option><option value="每日">每日</option><option value="每星期">每星期</option><option value="每月">每月</option><option value="每年">每年</option></select></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dd>阁下是否在过去的四个季度中，每季度有多于<span class="en">10</span>次的交易(共<span class="en">40</span>次)？交易产品需与阁下账户的交易产品一致</dd><dd style="width:140px;"><input name="frequency" type="radio" class="radio" value="是" id="frequency_yes"/><label class="fl" for="frequency_yes">是</label><input name="frequency" type="radio" class="radio" value="否" id="frequency_no"/><label class="fl mr0" for="frequency_no">否</label><div class="clear"></div></dd></dl>
		<dl><dd>阁下是否在金融服务行业中担任需有与交易产品或服务相关知识的专业职位至少一年？</dd><dd style="width:140px;"><input name="knowledge" type="radio" class="radio" value="是" id="knowledge_yes"/><label class="fl" for="knowledge_yes">是</label><input name="knowledge" type="radio" class="radio" value="否" id="knowledge_no"/><label class="fl mr0" for="knowledge_no">否</label><div class="clear"></div></dd></dl>
	</div>

<div class="submit"><button type="button" name="prevbutton" class="orange"><span><em class="icon_prev"></em>返回上一步</span></button><button type="button" name="nextbutton" class="orange"><span><em class="icon_next"></em>继续，进入下一步</span></button><div class="clear"></div></div>
</div>

<div id="step4" class="stepdiv">
<div class="step-title">财务资料<br/><span class="en">Financial Information</span></div>
	<div class="fielddiv">
		<h3>财务资料</h3>
		<dl><dt>财务状况：</dt><dd>
		<div class="two" style="width:30.5%"><select name="annual_income" class="select"><option value="">请选择...</option><option value="少于 $25,000">少于 $25,000</option><option value="$25,000 - $49,999" id="3">$25,000 - $49,999</option><option value="$50,000 - $99,999" id="4">$50,000 - $99,999</option><option value="$100,000 - $249,999">$100,000 - $249,999</option><option value="$250,000 - $1,000,000">$250,000 - $1,000,000</option><option value="多于 $1,000,000">多于 $1,000,000</option></select><p>年薪</p></div>
		<div class="two" style="width:31%"><select name="net_worth" class="select"><option value="">请选择...</option><option value="少于 $25,000">少于 $25,000</option><option value="$25,000 - $49,999">$25,000 - $49,999</option><option value="$50,000 - $99,999">$50,000 - $99,999</option><option value="$100,000 - $249,999">$100,000 - $249,999</option><option value="$250,000 - $999,999">$250,000 - $999,999</option><option value="$1,000,000 - $4,999,999">$1,000,000 - $4,999,999</option><option value="$5,000,000 - $9,999,999">$5,000,000 - $9,999,999</option><option value="多于$10,000,000以上">多于$10,000,000以上</option></select><p>净资产</p></div>
		<div class="two" style="width:31%"><select name="liquid_assets" class="select"><option value="">请选择...</option><option value="少于 $25,000">少于 $25,000</option><option value="$25,000 - $49,999">$25,000 - $49,999</option><option value="$50,000 - $99,999">$50,000 - $99,999</option><option value="$100,000 - $249,999">$100,000 - $249,999</option><option value="$250,000 - $999,999">$250,000 - $999,999</option><option value="$1,000,000 - $4,999,999">$1,000,000 - $4,999,999</option><option value="$5,000,000 - $9,999,999">$5,000,000 - $9,999,999</option><option value="多于$10,000,000以上">多于$10,000,000以上</option></select><p>流动资产</p></div>
		<div class="clear"></div>
		</dd></dl>
		<dl><dd>阁下曾是否于过往十年宣布破产？</dd><dd style="width:140px;"><input name="bankruptcy" type="radio" class="radio" value="是" id="bankruptcy_yes"/><label class="fl" for="bankruptcy_yes">是</label><input name="bankruptcy" type="radio" class="radio" value="否" checked="checked" id="bankruptcy_no"/><label class="fl" for="bankruptcy_no">否</label><div class="clear"></div></dd></dl>
		<dl><dt>说明：</dt><dd><input name="bankruptcy_content" type="text" class="input" disabled="disabled"/><p>如果答案为"是"，请提供<span class="u">解除破产的日期及说明情况</span></p></dd></dl>
		<dl><dd>阁下现在或过去曾否在德尔卡普金融集团开立过账户？</dd><dd style="width:140px;"><input name="pastaccount" type="radio" class="radio" value="是" id="pastaccount_yes"/><label class="fl" for="pastaccount_yes">是</label><input name="pastaccount" type="radio" class="radio" value="否" checked="checked" id="pastaccount_no"/><label class="fl" for="pastaccount_no">否</label><div class="clear"></div></dd></dl>
		<dl><dt>说明：</dt><dd><input name="pastaccount_content" type="text" class="input" disabled="disabled"/><p>如果答案为"是"，请提供<span class="u">账户号码</span></p></dd></dl>
		<dl><dd>阁下的个人资产投资组合，包括现金及/或其他金融工具，是否至少为 <span class="en">$500,000</span>？</dd><dd style="width:140px;"><input name="totalinvestment" type="radio" class="radio" value="是" id="totalinvestment_yes"/><label class="fl" for="totalinvestment_yes">是</label><input name="totalinvestment" type="radio" class="radio" value="否" id="totalinvestment_no"/><label class="fl" for="totalinvestment_no">否</label><div class="clear"></div></dd></dl>
		<dl><dd>阁下的亲友是否为德尔卡普金融集团的干事，董事，雇员或关联人士？</dd><dd style="width:140px;"><input name="isrelative" type="radio" class="radio" value="是" id="isrelative_yes"/><label class="fl" for="isrelative_yes">是</label><input name="isrelative" type="radio" class="radio" value="否" checked="checked" id="isrelative_no"/><label class="fl" for="isrelative_no">否</label><div class="clear"></div></dd></dl>
		<dl><dt>说明：</dt><dd><input name="isrelative_content" type="text" class="input en" disabled="disabled"/><p>如果答案为"是"，请提供<span class="u">账户号码</span></p></dd></dl>
	</div>
<div class="submit"><button type="button" name="prevbutton" class="orange"><span><em class="icon_prev"></em>返回上一步</span></button><button type="button" name="nextbutton" class="orange"><span><em class="icon_next"></em>继续，进入下一步</span></button><div class="clear"></div></div>
</div>


<div id="step5" class="stepdiv">
<div class="step-title">阅读并接受协议<br/><span class="en">Read and accept the agreement</span></div>
<div class="fielddiv">
	<h3>接受协议</h3>
	<dl><dt>协议内容：</dt><dd><h4>请阁下仔细阅读以下协议内容</h4><div id="agreement"><div class="agreement"><p>
	1. 网上开设帐户 - 业务条款&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="" target="_blank">点此查看</a></p>
<p>
	2. 网上开设帐户 - 风险披露&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="" target="_blank">点此查看</a></p></div></div></dd></dl>
	<dl><dt>接受协议：</dt><dd><input type="checkbox" class="checkbox" name="yes" id="confirm_form"/> <label for="confirm_form" class="red">本人已详细阅读以上相关协议，理解并同意该业务及声明之条款！</label></dd></dl>
	<div class="clear"></div>
</div>
<div class="submit"><button type="button" name="prevbutton" class="orange"><span><em class="icon_prev"></em>返回上一步</span></button><button type="button" name="nextbutton" class="orange" disabled="disabled"><span><em class="icon_next"></em>继续，进入下一步</span></button><div class="clear"></div></div>
</div>

<div id="step-last" class="stepdiv">
	<div class="step-title">检阅并递交<br/><span class="en">Review and submit</span></div>
	<div class="fielddiv">
		<div id="formdatahtml"></div>
		<textarea id="formdata" name="formdata" class="none"></textarea>
	</div>
<input type="hidden" name="lang" value="zh-cn"/>
<div class="submit"><button type="button" name="prevbutton" class="orange"><span><em class="icon_prev"></em>返回上一步</span></button><button type="submit" name="submit" class="orange"><span><em class="icon_save"></em>确认无误 现在申请</span></button></div>
</div>


<div id="successdata" class="stepdiv">
	<div class="step-title">操作已完成<br/><span class="en">Operation has been completed</span></div>
	<div class="fielddiv"><div class="successdata" ></div></div>
</div>


<div class="clear"></div>






</fieldset>
</form>
</div></div>

</body>
<script type="text/javascript" src="../../../../public/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="../../../../public/js/Ems.script.pack.js"></script>
<script type="text/javascript">


$(function(){

	//就业与投资经验 select处理
	$('select[name=employment] option').click(function(){if($(this).index()>0&$(this).index()<3){$('#employment_3').hide();$('#employment_3').find('select').attr('disabled','disabled');$('#employment_2').show();$('#employment_2').find('select').removeAttr('disabled')}else if($(this).index()>2){$('#employment_2').hide();$('#employment_2').find('select').attr('disabled','disabled');$('#employment_3').show();$('#employment_3').find('select').removeAttr('disabled')}else{$('#employment_2').show();$('#employment_3').hide();$('#employment_2').find('select').attr('disabled','disabled');$('#employment_3').find('select').attr('disabled','disabled')}});
	$('#empiricaldata').find('dl').each(function(){$(this).find('select:first option').click(function(){if($(this).index()>0){$(this).parents('dl').find('select:last').removeAttr('disabled');}else{$(this).parents('dl').find('select:last').attr('disabled','disabled');}});});

	//财务资料处理
	$('input[name=bankruptcy]').click(function(){if($(this).val()=='是'){$('input[name=bankruptcy_content]').removeAttr('disabled').focus();}else{$('input[name=bankruptcy_content]').attr('disabled','disabled').val('');}});
	$('input[name=pastaccount]').click(function(){if($(this).val()=='是'){$('input[name=pastaccount_content]').removeAttr('disabled').focus();}else{$('input[name=pastaccount_content]').attr('disabled','disabled').val('');}});
	$('input[name=isrelative]').click(function(){if($(this).val()=='是'){$('input[name=isrelative_content]').removeAttr('disabled').focus();}else{$('input[name=isrelative_content]').attr('disabled','disabled').val('');}});


	$("div#step5 button[name=nextbutton]").click(function(){

		var formdata='<h2 class="itxtitle">以下是您本次提交的资料：</h2>';
			formdata+='<h3 class="itxtitle">A. 个人联络资料</h3>';
			formdata+='<table class="itxtable"><tbody>';
			formdata+='<tr class="active"><th colspan="4" class="tl">个人资料</th></tr>';
			formdata+='<tr><td class="lefttd">客户姓名：</td><td colspan="3">'+$('input[name=contact_x]').val()+''+$('input[name=contact_m]').val()+'&nbsp;&nbsp;&nbsp;'+$('input[@type=radio][name=sex][checked]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">国籍：</td><td class="righttd en">'+$('select[name=country]').val()+'</td><td class="lefttd">居住国家：</td><td class="en">'+$('select[name=country_res]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">直辖市/省份：</td><td class="righttd">'+$('input[name=province]').val()+'</td><td class="lefttd">城市：</td><td>'+$('input[name=city]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">详细地址：</td><td colspan="3">'+$('input[name=address]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">证件号码：</td><td class="righttd ed">'+$('input[name=card]').val()+'</td><td class="lefttd">出生日期：</td><td>'+$('input[name=birthday]').val()+'</td></tr>';
			formdata+='<tr class="active"><th colspan="4" class="tl">联络资料</th></tr>';
			formdata+='<tr><td class="lefttd">电话号码：</td><td class="righttd en">'+$('input[name=phone_code]').val()+' '+$('input[name=phone]').val()+'</td><td class="lefttd">手机号码：</td><td class="en">'+$('input[name=mobile]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">电子信箱：</td><td class="righttd en">'+$('input[name=email]').val()+'</td><td class="lefttd en">QQ：</td><td class="en">'+$('input[name=qq]').val()+'</td></tr>';
			formdata+='</tbody></table>';
			formdata+='<h3 class="itxtitle">B. 经纪商&amp;收款银行资料</h3>';
			formdata+='<table class="itxtable"><tbody>';
			formdata+='<tr class="active"><th colspan="4" class="tl">帐户性质</th></tr>';
			formdata+='<tr><td class="lefttd">帐户类型：</td><td class="righttd ed">'+$('input[@type=radio][name=usertype][checked]').val()+'</td><td class="lefttd">杠杆比例：</td><td>'+$('input[@type=radio][name=lever][checked]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">计划注资：</td><td colspan="3" class="en">'+$('input[name=injectionof]').val()+' USD</td></tr>';
			formdata+='<tr class="active"><th colspan="4" class="tl">经纪商&amp;代理商</th></tr>';
			formdata+='<tr><td class="lefttd">经纪商 ID：</td><td class="righttd ed">'+$('input[name=brokerid]').val()+'</td><td class="lefttd">经纪商名称：</td><td>'+$('input[name=brokername]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">代理商 ID：</td><td class="righttd ed">'+$('input[name=agentid]').val()+'</td><td class="lefttd">代理商名称：</td><td>'+$('input[name=agentname]').val()+'</td></tr>';
			formdata+='<tr class="active"><th colspan="4" class="tl">收款银行资料</th></tr>';
			formdata+='<tr><td class="lefttd">开户银行：</td><td class="righttd">'+$('input[name=bank]').val()+'</td><td class="lefttd">分行：</td><td>'+$('input[name=banksub]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">银行代码：</td><td class="righttd">'+$('input[name=bankcode]').val()+'</td><td class="lefttd">银行地址：</td><td>'+$('input[name=bankaddress]').val()+'</td></tr>';
			formdata+='<tr><td class="lefttd">户名：</td><td class="righttd">'+$('input[name=bankname]').val()+'</td><td class="lefttd">卡号：</td><td class="en">'+$('input[name=bankcard]').val()+'</td></tr>';
			formdata+='</tbody></table>';
		$('#formdatahtml').html(formdata);
		$('#formdata').text(formdata);
		$("div.stepdiv").hide();$("div#step-last").show();
		$("div.steps li").removeClass('active');$("div.steps li#step-last-btn").addClass('active');
	});

});
</script>
<script type="text/javascript">showfooter()</script>
</html>




