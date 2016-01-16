//jalert
(function($){var lang=$.trim($('input[name=lang]').val());if(lang=='en'){alerttitle='Message prompts：';confirmtitle='Confirmation message：';ybtn='OK';nbtn='Cancel'}else{alerttitle='消息提示：';confirmtitle='确认信息：';ybtn='确定';nbtn='取消'};$.alerts={verticalOffset:-35,horizontalOffset:0,repositionOnResize:true,overlayOpacity:.4,overlayColor:'#000000',draggable:true,okButton:ybtn,cancelButton:nbtn,dialogClass:null,alert:function(message,title,callback){if(title==null)title=alerttitle;$.alerts._show(title,message,null,'alert',function(result){if(callback)callback(result)})},confirm:function(message,title,callback){if(title==null)title=confirmtitle;$.alerts._show(title,message,null,'confirm',function(result){if(callback)callback(result)})},prompt:function(message,value,title,callback){if(title==null)title=alerttitle;$.alerts._show(title,message,value,'prompt',function(result){if(callback)callback(result)})},_show:function(title,msg,value,type,callback){$.alerts._hide();$.alerts._overlay('show');$("BODY").append('<div id="popup_container">'+'<h1 id="popup_title"><span class="fLe"></span><span class="icoSw"></span ><span id="thistitle"></span><span class="fRi"></span><a id="btnCls"></a></h1>'+'<div id="popup_content">'+'<div id="popup_message"></div>'+'</div>'+'</div>');if($.alerts.dialogClass)$("#popup_container").addClass($.alerts.dialogClass);var pos=($.browser.msie&&parseInt($.browser.version)<=6)?'absolute':'fixed';$("#popup_container").css({position:pos,zIndex:99999,padding:0,margin:0});$("#thistitle").text(title);$("#popup_message").addClass(type);$("#popup_message").text(msg);$("#popup_message").html($("#popup_message").text().replace(/\n/g,'<br />'));$("#popup_container").css({minWidth:$("#popup_container").outerWidth(),maxWidth:$("#popup_container").outerWidth()});$.alerts._reposition();$.alerts._maintainPosition(true);switch(type){case'alert':$("#popup_message").after('<div id="popup_panel"><div class="fLe"></div><input type="button" value="'+$.alerts.okButton+'" id="popup_ok" class="botbtn"/><div class="fRi"></div></div>');$("#popup_ok,#btnCls").click(function(){$.alerts._hide();callback(true)});$("#popup_ok").focus().keypress(function(e){if(e.keyCode==13||e.keyCode==27)$("#popup_ok").trigger('click')});break;case'confirm':$("#popup_message").after('<div id="popup_panel"><div class="fLe"></div><input type="button" value="'+$.alerts.cancelButton+'" id="popup_cancel"  class="botbtn"/><input type="button" value="'+$.alerts.okButton+'" id="popup_ok"  class="botbtn"/><div class="fRi"></div></div>');$("#popup_ok,#btnCls").click(function(){$.alerts._hide();if(callback)callback(true)});$("#popup_cancel,#btnCls").click(function(){$.alerts._hide();if(callback)callback(false)});$("#popup_ok").focus();$("#popup_ok, #popup_cancel").keypress(function(e){if(e.keyCode==13)$("#popup_ok").trigger('click');if(e.keyCode==27)$("#popup_cancel").trigger('click')});break;case'prompt':$("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="'+$.alerts.okButton+'" id="popup_ok" /> <input type="button" value="'+$.alerts.cancelButton+'" id="popup_cancel" /></div>');$("#popup_prompt").width($("#popup_message").width());$("#popup_ok").click(function(){var val=$("#popup_prompt").val();$.alerts._hide();if(callback)callback(val)});$("#popup_cancel").click(function(){$.alerts._hide();if(callback)callback(null)});$("#popup_prompt, #popup_ok, #popup_cancel").keypress(function(e){if(e.keyCode==13)$("#popup_ok").trigger('click');if(e.keyCode==27)$("#popup_cancel").trigger('click')});if(value)$("#popup_prompt").val(value);$("#popup_prompt").focus().select();break}},_hide:function(){$("#popup_container").remove();$.alerts._overlay('hide');$.alerts._maintainPosition(false)},_overlay:function(status){switch(status){case'show':$.alerts._overlay('hide');$("BODY").append('<div id="popup_overlay"></div>');$("#popup_overlay").css({position:'absolute',zIndex:99998,top:'0px',left:'0px',width:'100%',height:$(document).height(),background:$.alerts.overlayColor,opacity:$.alerts.overlayOpacity});break;case'hide':$("#popup_overlay").remove();break}},_reposition:function(){var top=(($(window).height()/2)-($("#popup_container").outerHeight()/2))+$.alerts.verticalOffset;var left=(($(window).width()/2)-($("#popup_container").outerWidth()/2))+$.alerts.horizontalOffset;if(top<0)top=0;if(left<0)left=0;if($.browser.msie&&parseInt($.browser.version)<=6)top=top+$(window).scrollTop();$("#popup_container").css({top:top+'px',left:left+'px'});$("#popup_overlay").height($(document).height())},_maintainPosition:function(status){if($.alerts.repositionOnResize){switch(status){case true:$(window).bind('resize',$.alerts._reposition);break;case false:$(window).unbind('resize',$.alerts._reposition);break}}}};$.alert=function(message,title,callback){$.alerts.alert(message,title,callback)};$.confirm=function(message,title,callback){$.alerts.confirm(message,title,callback)};$.prompt=function(message,value,title,callback){$.alerts.prompt(message,value,title,callback)}})(jQuery);
jQuery.itxalert=function(t,f){if(f==null){$.alert(t,null,null);}else{$.alert(t,null,function(){f.focus()});}}
//scrollTo
jQuery.fn.scrollTo=function(speed,less) {return this.each(function(){if(less){var targetOffset=$(this).offset().top-less;}else{var targetOffset=$(this).offset().top}$('html,body').stop().animate({scrollTop:targetOffset},speed);});};
//jqueryform
;(function($){$.fn.ajaxSubmit=function(options){if(!this.length){log('ajaxSubmit: skipping submit process - no element selected');return this}if(typeof options=='function'){options={success:options}}var url=$.trim(this.attr('action'));if(url){url=(url.match(/^([^#]+)/)||[])[1]}url=url||window.location.href||'';options=$.extend(true,{url:url,type:this.attr('method')||'GET',iframeSrc:/^https/i.test(window.location.href||'')?'javascript:false':'about:blank'},options);var veto={};this.trigger('form-pre-serialize',[this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');return this}if(options.beforeSerialize&&options.beforeSerialize(this,options)===false){log('ajaxSubmit: submit aborted via beforeSerialize callback');return this}var n,v,a=this.formToArray(options.semantic);if(options.data){options.extraData=options.data;for(n in options.data){if(options.data[n]instanceof Array){for(var k in options.data[n]){a.push({name:n,value:options.data[n][k]})}}else{v=options.data[n];v=$.isFunction(v)?v():v;a.push({name:n,value:v})}}}if(options.beforeSubmit&&options.beforeSubmit(a,this,options)===false){log('ajaxSubmit: submit aborted via beforeSubmit callback');return this}this.trigger('form-submit-validate',[a,this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-submit-validate trigger');return this}var q=$.param(a);if(options.type.toUpperCase()=='GET'){options.url+=(options.url.indexOf('?')>=0?'&':'?')+q;options.data=null}else{options.data=q}var $form=this,callbacks=[];if(options.resetForm){callbacks.push(function(){$form.resetForm()})}if(options.clearForm){callbacks.push(function(){$form.clearForm()})}if(!options.dataType&&options.target){var oldSuccess=options.success||function(){};callbacks.push(function(data){var fn=options.replaceTarget?'replaceWith':'html';$(options.target)[fn](data).each(oldSuccess,arguments)})}else if(options.success){callbacks.push(options.success)}options.success=function(data,status,xhr){var context=options.context||options;for(var i=0,max=callbacks.length;i<max;i++){callbacks[i].apply(context,[data,status,xhr||$form,$form])}};var fileInputs=$('input:file',this).length>0;var mp='multipart/form-data';var multipart=($form.attr('enctype')==mp||$form.attr('encoding')==mp);if(options.iframe!==false&&(fileInputs||options.iframe||multipart)){if(options.closeKeepAlive){$.get(options.closeKeepAlive,fileUpload)}else{fileUpload()}}else{$.ajax(options)}this.trigger('form-submit-notify',[this,options]);return this;function fileUpload(){var form=$form[0];if($(':input[name=submit],:input[id=submit]',form).length){alert('Error: Form elements must not have name or id of "submit".');return}var s=$.extend(true,{},$.ajaxSettings,options);s.context=s.context||s;var id='jqFormIO'+(new Date().getTime()),fn='_'+id;window[fn]=function(){var f=$io.data('form-plugin-onload');if(f){f();window[fn]=undefined;try{delete window[fn]}catch(e){}}};var $io=$('<iframe id="'+id+'" name="'+id+'" src="'+s.iframeSrc+'" onload="window[\'_\'+this.id]()" />');var io=$io[0];$io.css({position:'absolute',top:'-1000px',left:'-1000px'});var xhr={aborted:0,responseText:null,responseXML:null,status:0,statusText:'n/a',getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(){this.aborted=1;$io.attr('src',s.iframeSrc)}};var g=s.global;if(g&&!$.active++){$.event.trigger("ajaxStart")}if(g){$.event.trigger("ajaxSend",[xhr,s])}if(s.beforeSend&&s.beforeSend.call(s.context,xhr,s)===false){if(s.global){$.active--}return}if(xhr.aborted){return}var cbInvoked=false;var timedOut=0;var sub=form.clk;if(sub){var n=sub.name;if(n&&!sub.disabled){s.extraData=s.extraData||{};s.extraData[n]=sub.value;if(sub.type=="image"){s.extraData[n+'.x']=form.clk_x;s.extraData[n+'.y']=form.clk_y}}}function doSubmit(){var t=$form.attr('target'),a=$form.attr('action');form.setAttribute('target',id);if(form.getAttribute('method')!='POST'){form.setAttribute('method','POST')}if(form.getAttribute('action')!=s.url){form.setAttribute('action',s.url)}if(!s.skipEncodingOverride){$form.attr({encoding:'multipart/form-data',enctype:'multipart/form-data'})}if(s.timeout){setTimeout(function(){timedOut=true;cb()},s.timeout)}var extraInputs=[];try{if(s.extraData){for(var n in s.extraData){extraInputs.push($('<input type="hidden" name="'+n+'" value="'+s.extraData[n]+'" />').appendTo(form)[0])}}$io.appendTo('body');$io.data('form-plugin-onload',cb);form.submit()}finally{form.setAttribute('action',a);if(t){form.setAttribute('target',t)}else{$form.removeAttr('target')}$(extraInputs).remove()}}if(s.forceSync){doSubmit()}else{setTimeout(doSubmit,10)}var data,doc,domCheckCount=50;function cb(){if(cbInvoked){return}$io.removeData('form-plugin-onload');var ok=true;try{if(timedOut){throw'timeout'}doc=io.contentWindow?io.contentWindow.document:io.contentDocument?io.contentDocument:io.document;var isXml=s.dataType=='xml'||doc.XMLDocument||$.isXMLDoc(doc);log('isXml='+isXml);if(!isXml&&window.opera&&(doc.body==null||doc.body.innerHTML=='')){if(--domCheckCount){log('requeing onLoad callback, DOM not available');setTimeout(cb,250);return}}cbInvoked=true;xhr.responseText=doc.documentElement?doc.documentElement.innerHTML:null;xhr.responseXML=doc.XMLDocument?doc.XMLDocument:doc;xhr.getResponseHeader=function(header){var headers={'content-type':s.dataType};return headers[header]};var scr=/(json|script)/.test(s.dataType);if(scr||s.textarea){var ta=doc.getElementsByTagName('textarea')[0];if(ta){xhr.responseText=ta.value}else if(scr){var pre=doc.getElementsByTagName('pre')[0];if(pre){xhr.responseText=pre.innerHTML}}}else if(s.dataType=='xml'&&!xhr.responseXML&&xhr.responseText!=null){xhr.responseXML=toXml(xhr.responseText)}data=$.httpData(xhr,s.dataType)}catch(e){log('error caught:',e);ok=false;xhr.error=e;$.handleError(s,xhr,'error',e)}if(ok){s.success.call(s.context,data,'success',xhr);if(g){$.event.trigger("ajaxSuccess",[xhr,s])}}if(g){$.event.trigger("ajaxComplete",[xhr,s])}if(g&&!--$.active){$.event.trigger("ajaxStop")}if(s.complete){s.complete.call(s.context,xhr,ok?'success':'error')}setTimeout(function(){$io.removeData('form-plugin-onload');$io.remove();xhr.responseXML=null},100)}function toXml(s,doc){if(window.ActiveXObject){doc=new ActiveXObject('Microsoft.XMLDOM');doc.async='false';doc.loadXML(s)}else{doc=(new DOMParser()).parseFromString(s,'text/xml')}return(doc&&doc.documentElement&&doc.documentElement.tagName!='parsererror')?doc:null}}};$.fn.ajaxForm=function(options){if(this.length===0){var o={s:this.selector,c:this.context};if(!$.isReady&&o.s){log('DOM not ready, queuing ajaxForm');$(function(){$(o.s,o.c).ajaxForm(options)});return this}log('terminating; zero elements found by selector'+($.isReady?'':' (DOM not ready)'));return this}return this.ajaxFormUnbind().bind('submit.form-plugin',function(e){if(!e.isDefaultPrevented()){e.preventDefault();$(this).ajaxSubmit(options)}}).bind('click.form-plugin',function(e){var target=e.target;var $el=$(target);if(!($el.is(":submit,input:image"))){var t=$el.closest(':submit');if(t.length==0){return}target=t[0]}var form=this;form.clk=target;if(target.type=='image'){if(e.offsetX!=undefined){form.clk_x=e.offsetX;form.clk_y=e.offsetY}else if(typeof $.fn.offset=='function'){var offset=$el.offset();form.clk_x=e.pageX-offset.left;form.clk_y=e.pageY-offset.top}else{form.clk_x=e.pageX-target.offsetLeft;form.clk_y=e.pageY-target.offsetTop}}setTimeout(function(){form.clk=form.clk_x=form.clk_y=null},100)})};$.fn.ajaxFormUnbind=function(){return this.unbind('submit.form-plugin click.form-plugin')};$.fn.formToArray=function(semantic){var a=[];if(this.length===0){return a}var form=this[0];var els=semantic?form.getElementsByTagName('*'):form.elements;if(!els){return a}var i,j,n,v,el;for(i=0,max=els.length;i<max;i++){el=els[i];n=el.name;if(!n){continue}if(semantic&&form.clk&&el.type=="image"){if(!el.disabled&&form.clk==el){a.push({name:n,value:$(el).val()});a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y})}continue}v=$.fieldValue(el,true);if(v&&v.constructor==Array){for(j=0,jmax=v.length;j<jmax;j++){a.push({name:n,value:v[j]})}}else if(v!==null&&typeof v!='undefined'){a.push({name:n,value:v})}}if(!semantic&&form.clk){var $input=$(form.clk),input=$input[0];n=input.name;if(n&&!input.disabled&&input.type=='image'){a.push({name:n,value:$input.val()});a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y})}}return a};$.fn.formSerialize=function(semantic){return $.param(this.formToArray(semantic))};$.fn.fieldSerialize=function(successful){var a=[];this.each(function(){var n=this.name;if(!n){return}var v=$.fieldValue(this,successful);if(v&&v.constructor==Array){for(var i=0,max=v.length;i<max;i++){a.push({name:n,value:v[i]})}}else if(v!==null&&typeof v!='undefined'){a.push({name:this.name,value:v})}});return $.param(a)};$.fn.fieldValue=function(successful){for(var val=[],i=0,max=this.length;i<max;i++){var el=this[i];var v=$.fieldValue(el,successful);if(v===null||typeof v=='undefined'||(v.constructor==Array&&!v.length)){continue}v.constructor==Array?$.merge(val,v):val.push(v)}return val};$.fieldValue=function(el,successful){var n=el.name,t=el.type,tag=el.tagName.toLowerCase();if(successful===undefined){successful=true}if(successful&&(!n||el.disabled||t=='reset'||t=='button'||(t=='checkbox'||t=='radio')&&!el.checked||(t=='submit'||t=='image')&&el.form&&el.form.clk!=el||tag=='select'&&el.selectedIndex==-1)){return null}if(tag=='select'){var index=el.selectedIndex;if(index<0){return null}var a=[],ops=el.options;var one=(t=='select-one');var max=(one?index+1:ops.length);for(var i=(one?index:0);i<max;i++){var op=ops[i];if(op.selected){var v=op.value;if(!v){v=(op.attributes&&op.attributes['value']&&!(op.attributes['value'].specified))?op.text:op.value}if(one){return v}a.push(v)}}return a}return $(el).val()};$.fn.clearForm=function(){return this.each(function(){$('input,select,textarea',this).clearFields()})};$.fn.clearFields=$.fn.clearInputs=function(){return this.each(function(){var t=this.type,tag=this.tagName.toLowerCase();if(t=='text'||t=='password'||tag=='textarea'){this.value=''}else if(t=='checkbox'||t=='radio'){this.checked=false}else if(tag=='select'){this.selectedIndex=-1}})};$.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=='function'||(typeof this.reset=='object'&&!this.reset.nodeType)){this.reset()}})};$.fn.enable=function(b){if(b===undefined){b=true}return this.each(function(){this.disabled=!b})};$.fn.selected=function(select){if(select===undefined){select=true}return this.each(function(){var t=this.type;if(t=='checkbox'||t=='radio'){this.checked=select}else if(this.tagName.toLowerCase()=='option'){var $sel=$(this).parent('select');if(select&&$sel[0]&&$sel[0].type=='select-one'){$sel.find('option').selected(false)}this.selected=select}})};function log(){if($.fn.ajaxSubmit.debug){var msg='[jquery.form] '+Array.prototype.join.call(arguments,'');if(window.console&&window.console.log){window.console.log(msg)}else if(window.opera&&window.opera.postError){window.opera.postError(msg)}}}})(jQuery);


$(function(){

$('a,input[type=submit],button').bind('focus',function(){if(this.blur){this.blur();}});

$("table.itxtable:not(.nothover) tbody tr:not(.nothover)").hover(function(){$(this).addClass('active');},function(){$(this).removeClass('active');});
$('dl input,dl select,dl select,dl textarea').focus(function(){$(this).next("p").attr("class","field-validation-tip")}).blur(function(){$(this).next("p").removeClass("field-validation-tip")});
$("div.fielddiv").hover(function(){$(this).addClass("active");},function(){$(this).removeClass("active");});



$('div.bank_list li').hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});
$('div.bank_list li').click(function(){$("div.bank_list li").removeClass("active");$(this).addClass("active");$('#bank_checked').text($(this).find('img:first').attr('title'));$('input[name=bank]').val($(this).find('img:first').attr('title'));});




$("button[name=prevbutton]").click(function(){
$("div.stepdiv").hide();$(this).parents("div.stepdiv").prev("div.stepdiv").show();$("#common-form").scrollTo(500);
$("div.steps li").removeClass('active');
$("div.steps li#"+$(this).parents("div.stepdiv").prev("div.stepdiv").attr('id')+'-btn').addClass('active');
});


$("button[name=nextbutton]").click(function(){
	var lang=$.trim($('input[name=lang]').val());
	var div=$(this).parents("div.stepdiv");
	var steps=$('#common-form').find(div).index();

	var errcount=0;var errmsg='';
	switch(lang)
	   {
		case "en":{errtitle='Error：';empty_tip=' Must be filled';format_tip=' Malformed';momey_tip=' Amount should be greater than <span class="en">0</span> ';digits_tip='长度应为6位'};break;
		case "zh-hk":{errtitle='錯誤提示：';empty_tip=' 必須填寫';format_tip=' 格式錯誤';momey_tip=' 金額應大於 <span class="en">0</span> ';digits_tip='长度应为6位'};break;
		default:{errtitle='错误提示：';empty_tip=' 必须填写';format_tip=' 格式错误';momey_tip=' 金额应大于 <span class="en">0</span>';digits_tip='长度应为6位';digit_tip='如果您没有经纪商ID，请填写XXXXXX'};break;
	   }

	$(div).find('.empty,.mobile,.email,digits').each(function(){
	var $input=$(this);
	var $inputval=$.trim($input.val());

	if ($input.hasClass('empty')){
		var $errtip=$(this).next('p').text()+empty_tip;
		if($inputval==''){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}
	if ($input.hasClass('digit')){
		var $errtip=digit_tip;
		if($inputval==''){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}

	if ($input.hasClass('mobile')){
		var $errtip=$(this).next('p').text()+format_tip;
		if($inputval!=''&$inputval.search(/^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/)==-1){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}
	if ($input.hasClass('digits')){
		var $errtip=$(this).next('p').text()+digits_tip;
		if($inputval!=''&parseInt($inputval.length)!=6){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}
	if ($input.hasClass('email')){
		var $errtip=$(this).next('p').text()+format_tip;
		if($inputval!=''&$inputval.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)==-1){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}
	if ($input.hasClass('money')){
		var $errtip=$(this).next('p').text()+momey_tip;
		if($inputval!=''&parseInt($inputval)<1){errcount=errcount+1;errmsg+='<span class="en">'+errcount+'.</span> '+$errtip+'<br/>';$input.next('p').attr('class','field-validation-error');}
	}

	});

	switch(lang)
	   {
		case "en":{errdef='<strong class="red">Occurred in the following <span class="en">'+errcount+'</span> entry error：</strong><br/>';};break;
		case "zh-hk":{errdef='<strong class="red">共發生以下 <span class="en">'+errcount+'</span> 項錯誤：</strong><br/>';};break;
		default:{errdef='<strong class="red">共发生以下 <span class="en">'+errcount+'</span> 项错误：</strong><br/>';};break;
	   }

	if (errcount>0){
		$.alert(errdef+errmsg,errtitle,function(){$(div).find('p.field-validation-error:first').prev().focus().scrollTo(500,50);});
		return false;
	} else{
		$(div).find('p').attr('class','field-validation-valid')
		$("div.stepdiv").hide();$(div).next('.stepdiv').show();
		$("div.steps li").removeClass('active');$("div.steps li").eq(steps).addClass('active');
		$("#common-form").scrollTo(500);
	}
});

//接受协议
$('#confirm_form').click(function(){
	if($(this).attr('checked')=='checked'){$(this).parents("div.stepdiv").find('button[name=nextbutton]').removeAttr('disabled');}else{$(this).parents("div.stepdiv").find('button[name=nextbutton]').attr('disabled','disabled');}
});



//提交表单
$("form.filedform button[type=submit]:last").click(function(){
	var $t=$(this);
	var $tdiv=$(this).parents("div.submit");
	$tdiv.find('button').attr('disabled','disabled');
	$tdiv.after('<div class="loading-line">Submission...</div>');
	$t.find('em').addClass('loading');
	var queryString=$("form.filedform").formSerialize();
	$.post(
		$("form.filedform").attr('action'),
		queryString,
		function(data){
			if(data==""){
				$tdiv.find('button').removeAttr('disabled');$('.loading-line').remove();$t.find('em').removeClass('loading');
				$.alert('System Error!');return false;
			}else{
			$('#successdata .successdata').html(data + '<br /><a href="http://www.xxx.com/">返回官网首页</a><br /><a href="http://www.xxx.com/vip.html">返回会员中心</a>');
			$("div.stepdiv").hide();
			$('#successdata').show();
			$("div.steps li").removeClass('active');
			$('#step-ok').addClass('active').show();
			$("#common-form").scrollTo(500);
			}
});
	return false;
});

});






function tobirthday(t,field){
if(t.val().length>6){t.parents('div.fielddiv').find('input[name='+field+']').val(t.val().substr(6,4)+'-'+t.val().substr(10,2)+'-'+t.val().substr(12,2));}else{t.parents('div.fielddiv').find('input[name='+field+']').val('');}
}



function showfooter(){
	//不在框架调用时 增加布局样式
	$('#common-form').prepend('<a href="'+document.URL+'" class="newwinlink" target="_blank"></a>');
	if(window.top==window.self||$('#form-body').width()==1000){$('body,#form-body').addClass('framebody');}else{}
}

function getnewURL(url){window.open(url)}




