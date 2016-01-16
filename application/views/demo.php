<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
<?php 
$attr = array('method'=>'post','class'=>'form-horizontal');
echo form_open('user/demo',$attr);
?>
<div class="alter alert-error">
<?php if(validation_errors()){?>
   <button type="button" class="close" data-dismiss="alert">&times;</button>
   <?php echo validation_errors();}?>
</div>
<div class="control-group">
   <label class="control-label" for="username">姓名</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'username','id'=>'username','value'=>set_value('username'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="email">电子邮箱</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'email','id'=>'email','value'=>set_value('email'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font>请确保Email有效，否则无法接收所申请的MT4账户</span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="password">密码</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'password','id'=>'password', 'maxlength'=>'25');
      echo form_password($data);
      ?>
      <span class="help-inline"><font color="red">*</font>密码必须为字母+数字的组合，不能为纯数字、纯字母，长度至少6位</span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="password_r">确认密码</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'password_r','id'=>'password_r', 'maxlength'=>'25');
      echo form_password($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="leverage">交易倍数</label>
   <div class="controls">
      <?php 
      $data = "'id'=>'leverage'";
      $option=array(
         '50'=>50,
         '100'=>100,
         '200'=>200,
         '400'=>400,
         );
      echo form_dropdown('leverage',$option,$data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="country">国家</label>
   <div class="controls">
      <select name="country" id="country" style="width:220px">
      <option value="Afganistan">Afganistan</option>
      <option>Albania</option>
      <option>Algeria</option>
      <option>American Samoa</option>
      <option>Andorra</option>
      <option>Angola</option>
      <option>Anguilla</option>
      <option>Antigua & Barbuda</option>
      <option>Argentina</option>
      <option>Armenia</option>
      <option>Aruba</option>
      <option>Australia</option>
      <option>Austria</option>
      <option>Azerbaijan</option>
      <option>Azores</option>
      <option>Bahamas</option>
      <option>Bahrain</option>
      <option>Bangladesh</option>
      <option>Barbados</option>
      <option>Belarus</option>
      <option>Belgium</option>
      <option>Benin</option>
      <option>Bermuda</option>
      <option>Bhutan</option>
      <option>Bolivia</option>
      <option>Bonaire</option>
      <option>Bosnia & Herzegovina</option>
      <option>Botswana</option>
      <option>Brazil</option>
      <option>British Indian Ocean Ter</option>
      <option>Brunei</option>
      <option>Bulgaria</option>
      <option>Burkina Faso</option>
      <option>Burundi</option>
      <option>Cambodia</option>
      <option>Cameroon</option>
      <option>Canada</option>
      <option>Canary Islands</option>
      <option>Cape Verde</option>
      <option>Cayman Islands</option>
      <option>Central African Republic</option>
      <option>Chad</option>
      <option>Channel Islands</option>
      <option>Chile</option>
      <option>China</option>
      <option>Christmas Island</option>
      <option>Cocos Island</option>
      <option>Columbia</option>
      <option>Comoros</option>
      <option>Congo</option>
      <option>Cook Islands</option>
      <option>Costa Rica</option>
      <option>Cote D'ivoire</option>
      <option>Croatia</option>
      <option>Cuba</option>
      <option>Curaco</option>
      <option>Cyprus</option>
      <option>Czeck Republic</option>
      <option>Denmark</option>
      <option>Djibouti</option>
      <option>Dominica</option>
      <option>Dominican Republic</option>
      <option>Dubai</option>
      <option>East Timor</option>
      <option>Ecuador</option>
      <option>Egypt</option>
      <option>El Salvador</option>
      <option>Equatorial Guinea</option>
      <option>Eritrea</option>
      <option>Estonia</option>
      <option>Ethiopia</option>
      <option>Falkland Islands</option>
      <option>Faroe Islands</option>
      <option>Fiji</option>
      <option>Finland</option>
      <option>France</option>
      <option>French Guiana</option>
      <option>French Polynesia</option>
      <option>French Southern Ter</option>
      <option>Gabon</option>
      <option>Gambia</option>
      <option>Georgia</option>
      <option>Germany</option>
      <option>Ghana</option>
      <option>Gibraltar</option>
      <option>Great Britain</option>
      <option>Greece</option>
      <option>Greenland</option>
      <option>Grenada</option>
      <option>Guadeloupe</option>
      <option>Guam</option>
      <option>Guatemala</option>
      <option>Guinea</option>
      <option>Guyana</option>
      <option>Haiti</option>
      <option>Hawaii</option>
      <option>Honduras</option>
      <option>Hong Kong</option>
      <option>Hungary</option>
      <option>Iceland</option>
      <option>India</option>
      <option>Indonesia</option>
      <option>Iran</option>
      <option>Iraq</option>
      <option>Ireland</option>
      <option>Isle of Man</option>
      <option>Israel</option>
      <option>Italy</option>
      <option>Jamaica</option>
      <option>Japan</option>
      <option>Jordan</option>
      <option>Kazakhstan</option>
      <option>Kenya</option>
      <option>Kiribati</option>
      <option>Korea North</option>
      <option>Korea South</option>
      <option>Kuwait</option>
      <option>Kyrgyzstan</option>
      <option>Laos</option>
      <option>Latvia</option>
      <option>Lebanon</option>
      <option>Lesotho</option>
      <option>Liberia</option>
      <option>Libya</option>
      <option>Liechtenstein</option>
      <option>Lithuania</option>
      <option>Luxembourg</option>
      <option>Macau</option>
      <option>Macedonia</option>
      <option>Madagascar</option>
      <option>Malawi</option>
      <option>Malaysia</option>
      <option>Maldives</option>
      <option>Mali</option>
      <option>Malta</option>
      <option>Marshall Islands</option>
      <option>Martinique</option>
      <option>Mauritania</option>
      <option>Mauritius</option>
      <option>Mayotte</option>
      <option>Mexico</option>
      <option>Midway Islands</option>
      <option>Moldova</option>
      <option>Monaco</option>
      <option>Mongolia</option>
      <option>Montserrat</option>
      <option>Morocco</option>
      <option>Mozambique</option>
      <option>Myanmar</option>
      <option>Nambia</option>
      <option>Nauru</option>
      <option>Nepal</option>
      <option>Netherland Antilles</option>
      <option>Netherlands</option>
      <option>Nevis</option>
      <option>New Caledonia</option>
      <option>New Zealand</option>
      <option>Nicaragua</option>
      <option>Niger</option>
      <option>Nigeria</option>
      <option>Niue</option>
      <option>Norfolk Island</option>
      <option>Norway</option>
      <option>Oman</option>
      <option>Pakistan</option>
      <option>Palau Island</option>
      <option>Palestine</option>
      <option>Panama</option>
      <option>Papua New Guinea</option>
      <option>Paraguay</option>
      <option>Peru</option>
      <option>Philippines</option>
      <option>Pitcairn Island</option>
      <option>Poland</option>
      <option>Portugal</option>
      <option>Puerto Rico</option>
      <option>Qatar</option>
      <option>Reunion</option>
      <option>Romania</option>
      <option>Russia</option>
      <option>Rwanda</option>
      <option>Saipan</option>
      <option>Samoa</option>
      <option>Samoa American</option>
      <option>San Marino</option>
      <option>Sao Tome & Principe</option>
      <option>Saudi Arabia</option>
      <option>Senegal</option>
      <option>Serbia & Montenegro</option>
      <option>Seychelles</option>
      <option>Sierra Leone</option>
      <option>Singapore</option>
      <option>Slovakia</option>
      <option>Slovenia</option>
      <option>Solomon Islands</option>
      <option>Somalia</option>
      <option>South Africa</option>
      <option>Spain</option>
      <option>Sri Lanka</option>
      <option>St Barthelemy</option>
      <option>St Eustatius</option>
      <option>St Helena</option>
      <option>St Kitts-Nevis</option>
      <option>St Lucia</option>
      <option>St Maarten</option>
      <option>St Pierre & Miquelon</option>
      <option>St Vincent & Grenadines</option>
      <option>Sudan</option>
      <option>Suriname</option>
      <option>Swaziland</option>
      <option>Sweden</option>
      <option>Switzerland</option>
      <option>Syria</option>
      <option>Tahiti</option>
      <option>Taiwan</option>
      <option>Tajikistan</option>
      <option>Tanzania</option>
      <option>Thailand</option>
      <option>Togo</option>
      <option>Tokelau</option>
      <option>Tonga</option>
      <option>Trinidad & Tobago</option>
      <option>Tunisia</option>
      <option>Turkey</option>
      <option>Turkmenistan</option>
      <option>Turks & Caicos Is</option>
      <option>Tuvalu</option>
      <option>Uganda</option>
      <option>Ukraine</option>
      <option>United Arab Emirates</option>
      <option>United Kingdom</option>
      <option>Uruguay</option>
      <option>USA</option>
      <option>Uzbekistan</option>
      <option>Vanuatu</option>
      <option>Vatican City State</option>
      <option>Venezuela</option>
      <option>Vietnam</option>
      <option>Virgin Islands (Brit)</option>
      <option>Virgin Islands (USA)</option>
      <option>Wake Island</option>
      <option>Wallis & Futana Is</option>
      <option>Yemen</option>
      <option>Zaire</option>
      <option>Zambia</option>
      <option>Zimbabwe</option>
   </select>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="state">省份</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'state','id'=>'state','value'=>set_value('state'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="city">城市</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'city','id'=>'city','value'=>set_value('city'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="zipcode">邮编</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'zipcode','id'=>'zipcode','value'=>set_value('zipcode'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="address">地址</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'address','id'=>'address','value'=>set_value('address'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="phone">手机</label>
   <div class="controls">
      <?php 
      $data = array('name'=>'phone','id'=>'phone','value'=>set_value('phone'), 'maxlength'=>'25');
      echo form_input($data);
      ?>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <label class="control-label" for="server">服务器</label>
   <div class="controls">
      <select id="server" name="server">
                        <option value="">请选择服务器</option>
                        <?php foreach ($server as $v){?>
                        <option value="<?php echo $v['local_port']?>" <?php echo $v['is_chief'] ? 'selected' : ''?>><?php echo $v['name']?></option>
                        <?php }?>
      </select>
      <span class="help-inline"><font color="red">*</font></span>
   </div>
</div>
<div class="control-group">
   <div class="controls">
      <?php 
      $data = array('name'=>'login_submit','id'=>'login_submit','value'=>'申请模拟账户','class'=>'btn');
      echo form_submit($data);
      ?>
   </div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
   $(function(){
      function checkForm(){
         var username = $("#username").val();
         var password = $("#password").val();
         var password_r = $("#password_r").val();
         var email = $("#email").val();
         var leverage = $("#leverage").val();
         var country = $("#country").val();
         var state = $("#state").val();
         var city = $("#city").val();
         var zipcode = $("#zipcode").val();
         var address = $("#address").val();
         var phone = $("#phone").val();

         if(username == "" || password == "" || email == "" || password_r == "" || leverage == "" || country == "" || state == "" || city == "" || zipcode == "" || address == "" || phone == ""){
            alert("必要信息未填写完整");
            return false;
         }

         var p = /^([a-zA-Z0-9_]+[_|\_|\.]?)*[a-zA-Z0-9_]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9_]+\.[a-zA-Z]{2,3}$/;
         if (!p.test(email)) {
            alert("Email格式不对");
            return false;
         }

         if(password.length<6){
            alert("密码长度至少为6位");
            return false;
         }

         var d=/^[0-9]+$/;
         if (d.test(password)) {
            alert("密码不能为纯数字");
            return false;
         };

         var s=/^[a-zA-Z]+$/;
         if (s.test(password)) {
            alert("密码不能为纯字母");
            return false;
         };

         if (password != password_r) {
            alert("两次输入密码不一致");
            return false;
         };

         var ph =/^1[0-9]{10}$/;
         if (!ph.test(phone)) {
            alert("手机号格式不对");
            return false;
         };

         $("#login_submit").attr("value","正在处理中，请稍后。。。");
         $("#login_submit").attr("disabled",true);
         $(".form-horizontal").submit();
         return true;
      }
      $("#login_submit").click(checkForm);
   });
</script>
</div>
</div>

<?php $this->load->view('footer')?>