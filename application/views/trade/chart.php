<!DOCTYPE html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<title><?php echo $title; ?></title> 
	<link rel="stylesheet" href="<?php echo base_url()?>public/css/jquery.mobile-1.3.1.min.css" />
	<script src="http://libs.baidu.com/jquery/1.7.1/jquery.min.js"></script>
	<script src="<?php echo base_url()?>public/js/jquery.mobile-1.3.1.min.js"></script>
	
    <script src="<?php echo base_url()?>public/js/chars/util.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/absPainter.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/ajax.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/crossLines.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/axis-x.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/tip.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/linepainter.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/volumePainter.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/axis-y.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/chartEventHelper.kl.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url()?>public/js/chars/controller.js" type="text/javascript" charset="utf-8"></script>	
	
	<style>

	#symbol{width:30%;float:left;}
	#ask{width:30%;float:left;}
	#bid{width:30%;float:left;}
	#chars{background-color:#000;}
	#clear{ clear:both; height:1px}
	#orderlist{ margin-top:10px;}
	#olist_left{float:left; width:60%;margin-top:10px;margin-top:10px;}
	#olist_right{float:right; width:35%; margin-top:10px;}
	.order1{color:green;}
	.order2{color:red;}

	#KT{height:28px;line-height:25px;background:#f0f0f0}
	#KT a:link{text-decoration: none;float:left;border:1px #f0f0f0 solid;height:26px;}
	#KT a:active {border:1px #666666 solid;height:26px;}
	#KT a:hover {border:1px #666666 solid;height:26px;}
	#KT img{text-decoration: none;float:left;border:1px #f0f0f0 solid;height:26px;}
canvas{position:absolute;z-index:1;cursor:crosshair;} 
	</style>
</head> 

<body> 

<div data-role="page" style=" background:#000000">


  <div data-role="none">
				
			 <div style="float:left;"> <select name="symbol" id="symbol1" data-role="none" onChange="javascript:changesymbol()">
			 	<?php
				if (!empty($_SESSION['symbol_array'])) {
					foreach ($_SESSION['symbol_array'] as $v) {
						echo '<option value="' . $v .'">'. $v .'</option>';
					}
				}
				?>
			 </select>
								
					 <select id="ktimes" data-role="none" onChange="javascript:changetimes()">
				<option value="1"  >1</option>
				<option value="5"  >5</option>
				<option value="15"  selected='selected'>15</option>
				<option value="30"  >30</option>
				<option value="60"   >1H</option>
				<option value="240"  >4H</option>
				<option value="1440"  >1D</option>
				<option value="10080"   >1W</option>
				<option value="43200"  >1M</option>
				</select>	</div>				
				<div style="float:left;")>
			 <a href="#" onClick='opennewline("bar");'>	<img src="<?php echo base_url()?>public/img/k1_b.png"></a>
				<a href="#" onClick='opennewline("candles");' ><img src="<?php echo base_url()?>public/img/k2_b.png"></a> 
				<a href="#" onClick='opennewline("line");' ><img src="<?php echo base_url()?>public/img/k3_b.png"></a> 
				<img src="<?php echo base_url()?>public/img/j_1.png">
				<a href="#" data-ajax="false" onClick='Addmacd();'><img src="<?php echo base_url()?>public/img/b_1.png"></a>
				</div>
				
				
				
			<script>function changetimes(){
			
			var s=document.getElementById("ktimes").value;
			
			
			OpenNewSymbol(s);
			}
			function changesymbol(){
			
		
			var t=document.getElementById("symbol1").value;
		
			OpenNewSymbol2(t);
			}
           </script>
		

		 </div>
			
<div style="clear:both;"></div>

		 
	<div id="chars">
		<canvas id="cav_char" style="z-index: 1; background: transparent;">
			        <p style="color:green">Your browser does not support html5<br />
您的浏览器不支持Html5，请下载最新版Chrome或Firefox浏览器浏览本网站。
			        </p>
		</canvas> 
	</div>
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
			<li><a href="<?php echo site_url('user')?>" data-ajax="false">返回首页</a></li>
			</ul>
		</div></div>
	 	
</div>
<script type="text/javascript">
<!-- 

function findDimensions() //函数：获取尺寸 
{ 


canvas=document.getElementsByTagName('canvas')[0];
canvas.width=$(window).width(); 
if($(window).height() > 370)
	canvas.height=$(window).height() - 72;
else if($(window).height() < 370)
	canvas.height = 370;
} 
findDimensions();

//调用函数，获取数值 
window.onresize=findDimensions; 
//--> 
function closeme(){ 
	var browserName=navigator.appName; 
	if (browserName=="Netscape") { 
		window.open('','_parent',''); 
		window.close(); 
	} else if (browserName=="Microsoft Internet Explorer") {
	 window.opener = "whocares"; window.close(); 
	} 
}

</script>

<script type="text/javascript">
var isGetQuotesEnd=true; //the begin flags of GetQuotes 
var CurrCharData; //Current DataSource
var CurrChar=undefined; //Current  Char Object
var CurrCharPainter=undefined;
var curr_ask,curr_bid,curr_time;
//var Curr_period=Htmlrequest("period");
//var Curr_CharType=Htmlrequest("chartype");
var Curr_period=15;
var Curr_CharType="candles";
var Curr_MAS=Htmlrequest("mas");
var MAS_Colors= {"3": "#00FF00" , "7" :'blue', "25" :'#FFA500'};
var CurrSymbol;
var invGetCurrQuotes;

if (Htmlrequest("symbol")!="")
{
	CurrSymbol=Htmlrequest("symbol");	
}else
{CurrSymbol=document.getElementById("symbol1").value;}
$("#symbol1 option[value="+CurrSymbol+"]").attr("selected",true);

//加入代码简代过程
function opennewline(newtype){

Curr_CharType=newtype;
			PrintChar();
}
function OpenNewSymbol(New_period){
Curr_period=New_period;
PrintChar();

}
function OpenNewSymbol2(New_symbol){
CurrSymbol=New_symbol;
PrintChar();

}

//我加入代码完成
function Addmacd(){
	if(isObj(CurrChar))
	{
		
		var obj=[];

			var item = { color: MAS_Colors["3"], daysCount: 3 };
			obj.push(item);
			item = { color: MAS_Colors["7"], daysCount: 7 };
			obj.push(item);
			item = { color: MAS_Colors["25"], daysCount: 25 };
			obj.push(item);	
				

		
		CurrChar.MAS=obj;
		CurrChar.draw();
	};
	}
if(!isObj(CurrSymbol)){
	CurrSymbol=<?php echo '"' . 
		(empty($_SESSION['symbol_array']) ? "XAUUSD" : $_SESSION['symbol_array'][0]) . '"' ; ?>;
};
$(document).ready(function(){
	$('input[type="radio"]').change( function() {
		if(isObj(CurrChar))
		{
			var t;
			if($("#rdoline").attr("checked")=="checked")
			{
				t="line";	
			}
			if($("#rdobar").attr("checked")=="checked")
			{
				t="bar";	
			}
			if($("#rdocandles").attr("checked")=="checked")
			{
				t="candles";	
			}
						CurrChar.chartype=t;
			CurrChar.draw();
		}	
	});

		

	$("[href='order.php']").click(function(event){
	   //$(this).attr("href",$(this).attr("href")+"?symbol="+CurrSymbol);
	   event.preventDefault();
		 window.open("order.php?symbol="+CurrSymbol,"_self");
	});
	
	PrintChar();
	var invPrintChar=setInterval("PrintChar()",300000);	
	//invGetCurrQuotes=setInterval("GetCurrQuotes('"+CurrSymbol+"')",500);
});

function GetSelectMas()
{	
	var obj=[];
	if($("#chkMA3").attr("checked")=="checked")
	{
		var item =  3 ;
		obj.push(item);
	}
	if($("#chkMA7").attr("checked")=="checked")
	{
		var item =  7 ;
		obj.push(item);
	}
	if($("#chkMA25").attr("checked")=="checked")
	{
		var item = 25 ;
		obj.push(item);
	}
	Curr_MAS=obj;
	return Curr_MAS;
}
function redirectCharURL(p,t){
	 $(this).attr("href",$(this).attr("href")+"?symbol="+CurrSymbol+"&period="+p+"&chartype="+t);   
}
function isObj(str)
{
   	if(str==null||typeof(str)=='undefined') return false; return true;
}
function PrintChar()
{	
	var obj_mas=[];	
	if (Curr_MAS!="")
	{
		var m_ar=Curr_MAS.split(',');
		$.each(m_ar, function(index, content){
			var item= { 
				color: MAS_Colors[content], 
				daysCount: content 
				};
			obj_mas.push(item);		
		});
	}
	CanvasTextOutCenter("cav_char","Loading......");
	PrintSymbolChar(CurrSymbol,Curr_period,"cav_char",Curr_CharType,obj_mas);
}	
function CanvasTextOutCenter(canid,txt)
{
	var elem = document.getElementById(canid);
	if (!elem || !elem.getContext) {return;}
	var context = elem.getContext('2d');
	if (!context) {return;}
	context.fillStyle    = 'lime';
	context.font         = '30px sans-serif';
	context.textBaseline = 'top';
	if (context.fillText) {
		    context.fillText(txt,elem.width/2-context.measureText(txt).width/2, elem.height/2-15);
	  }
}
function GetCurrQuotes(symbol){
	if (!isGetQuotesEnd) return false;
   $.ajax({ 
        type: "Get", 
        url: "getdata?action=quotes&login="+<?php echo $login ?>+"&server="+<?php echo $server ?>, 
        dataType: "json",	        
        beforeSend: function(){	 
        	isGetQuotesEnd=false;         
        }, 
        success: function(json){	      
            if(json!=null&&typeof(json)!='undefined'&& json.error==''){
            	//CurrCharData.quote.severtime=json.severtimets;
            		$.each( json.data.rows, function(index, content){
	            		if(symbol==content.symbol)
	            		{
	            			curr_ask=content.ask;
	            			curr_bid=content.bid;
	            			curr_time=content.time;
	            			InsertOrUpdateCurrPrice(content.symbol,content.bid,content.timestamp);
	            		}		            		           	
            		});
            		
	         }           
            isGetQuotesEnd=true;
        },
        error:function()
        {
        	 isGetQuotesEnd=true;    
        }
	});
};
function Htmlrequest(paras){
	var url = location.href;
	var paraString = url.substring(url.indexOf("?")+1,url.length).split("&");
	var paraObj = {}
	for (i=0; j=paraString[i]; i++){
		paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length);
	}
	var returnValue = paraObj[paras.toLowerCase()];
		if(typeof(returnValue)=="undefined"){
		return "";
	}else{
		return returnValue;
		}
}
/*
 * 时间格式话
 */
Date.prototype.format = function(format) {
    /*
     * eg:format="yyyy-MM-dd hh:mm:ss";
     */
    var o = {
        "M+" :this.getMonth() + 1, // month
        "A+" :GetMonthName(this.getMonth() + 1),
        "d+" :this.getDate(), // day
        "h+" :this.getHours(), // hour
        "m+" :this.getMinutes(), // minute
        "s+" :this.getSeconds(), // second
        "q+" :Math.floor((this.getMonth() + 3) / 3), // quarter
        "S" :this.getMilliseconds()
    // millisecond
    }
    

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
        	if (k[0]=="A")
        		format = format.replace(RegExp.$1, o[k]);
        	else
        		format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]:("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
Date.prototype.formatEx = function(format,zone) {
    /*
     * eg:format="yyyy-MM-dd hh:mm:ss";
     */
	var thistime = this.getTime();
	LocateTimeOffset = this.getTimezoneOffset() * 60000;
	var t = thistime + LocateTimeOffset+(zone*3600000);
	var d= new Date(t);
	
    var o = {
        "M+" :d.getMonth() + 1, // month
        "A+" :GetMonthName(d.getMonth() + 1),
        "d+" :d.getDate(), // day
        "h+" :d.getHours(), // hour
        "m+" :d.getMinutes(), // minute
        "s+" :d.getSeconds(), // second
        "q+" :Math.floor((d.getMonth() + 3) / 3), // quarter
        "S"  :d.getMilliseconds()
    // millisecond
    }
    

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
        	if (k[0]=="A")
        		format = format.replace(RegExp.$1, o[k]);
        	else
        		format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]:("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
function ConverZoneTime(strdate)
{
	if(LocateTimeOffset==0){ 
		var today;
		today = new Date();
		FormatTimeEx(today,"yyyy.MM.dd hh:mm");
	}
	var t=Date.parse(strdate);
	return t-LocateTimeOffset;	
}
function InsertOrUpdateCurrPrice(symbol,bid,t)
{
	if(CurrSymbol!=symbol){return;}
    if(!isObj(CurrCharData)){return;}
    //if(CurrCharData.quote.severtime==null || typeof(CurrCharData.quote.severtime)=='undefined'){return;} 
    //alert("");
    var curchardatalasrow=CurrCharData.datas[CurrCharData.datas.length-1];
    var t=ConverZoneTime(curr_time.replace(/\./g,   "/"),0) /1000; //mt时间是gmt 0时区
    var timespace=t-curchardatalasrow.quoteTime; 
    var item,needupdate=false;
    if (timespace<=0){CurrCharData.quote.currentprice=curchardatalasrow.close;return;}
    CurrCharData.quote.currentprice=bid;
    
    if (timespace<CurrCharData.quote.datatype*60)
	{
    	if(	curchardatalasrow.high ==Math.max(curchardatalasrow.high,bid) && 
    	    curchardatalasrow.low ==Math.min(curchardatalasrow.low,bid)&& 
    	    curchardatalasrow.close==bid )
    	{
    		needupdate=false;
    	}else{
			curchardatalasrow.high =Math.max(curchardatalasrow.high,bid);
			curchardatalasrow.low  =Math.min(curchardatalasrow.low,bid);
			curchardatalasrow.close=bid;
			needupdate=true;
		}
	}
	else
	{
		var i=0
		while( t>curchardatalasrow.quoteTime+i*(CurrCharData.quote.datatype*60)) {i++;}		
		var it=curchardatalasrow.quoteTime+(i-1)*(CurrCharData.quote.datatype*60);
		item = {
                quoteTime:it,
                preClose:bid,
                open:  bid,
                high:  bid,
                low:   bid,
                close: bid,
                volume:0,
                amount: 0,
                isCurr:true
				};
		CurrCharData.datas.push(item);
		needupdate=true;
	}	
    if(isObj(CurrCharPainter))
    	{
    		CurrCharPainter.paint();
    	}  
    
}
function FormatUniTime(t,period)
{
	var y=t%period;
	return t-y;
}
function getCharDatajax(v_symbol,v_period,olddatas,beforeSendCallBack,successCallback,errCallback) {
	var data={
		quote: {                	
	    	symbol:v_symbol,
	    	datatype:v_period,
	    	xformat:'',
			yformat:'',
	    	low:0,
			high:0,
			digits:0
    	},
    	datas: []
    }
	var fromtime=null;
	var now= parseInt((new Date()).getTime()/1000);
	if (!isObj(olddatas)|| olddatas.datas.length==0)
		{
			fromtime=now- (86400*GetPeriodDefaultDays(v_period));
		}
	else
		{
			fromtime=olddatas.datas[olddatas.datas.length-1].quoteTime;
		}	
	$.ajax({ 
        type: "Get", 
        //获取历史数据
        url: "getdata?action=historyquotes&symbol="+v_symbol+"&period="+v_period+"&from="+fromtime+
        	"&to="+now+"&login="+<?php echo $login ?>+"&server="+<?php echo $server ?>, 
        dataType: "json", 
        beforeSend: function(){	 
        	beforeSendCallBack();
        }, 
        success: function(json){	      
            if(isObj(json)&& json.error==''){ 
            	if (json.digits === null) {
            		alert("Get Data Error!");
            		window.location.href=window.location.href;
            		return false;
            	}
            	if (!isObj(olddatas)){olddatas=data;}
            	olddatas.quote.symbol=json.symbol;
            	olddatas.quote.xformat=GetTimeFormat(json.period);
            	olddatas.quote.yformat="";
            	olddatas.quote.datatype=Number(json.period);
            	olddatas.quote.digits=Number(json.digits);
            	var dig=Math.pow(10,json.digits);
            	//var d = new Date();
        	    for (var i = 0; i < json.data.length; i++) {
        	        var rawData = json.data[i];
        	        var pClose;        	        
        	        var $tmpopen=rawData[1]/dig;
        	        if (i==0){pClose=Number($tmpopen);}else{pClose=Number(json.data[i-1][1]/dig);}       	        
        	        var item = {
        	            quoteTime:rawData[0],
        	            preClose:pClose ,
        	            open: fomatFloat(Number($tmpopen),json.digits),
        	            high: fomatFloat(Number($tmpopen)+Number(rawData[2]/dig),json.digits),
        	            low:  fomatFloat(Number($tmpopen)+Number(rawData[3]/dig),json.digits),
        	            close: fomatFloat(Number($tmpopen)+Number(rawData[4]/dig),json.digits),
        	            volume: Number(rawData[5]),
        	            amount: 0,
        	            isCurr:false
        	        }
        	        if (olddatas.datas.length == 0) {
        	        	olddatas.low = item.low;
        	        	olddatas.high = item.high;
        	        } else {
        	        	olddatas.high = Math.max(olddatas.high, item.high);
        	        	olddatas.low = Math.min(olddatas.low, item.low);
        	        }
        	        olddatas.datas.push(item);
        	    }
            	return successCallback(olddatas);
            }else{ 
                return errCallback(json.error); 
            } 
        },error:function() 
        {
        	return errCallback("Network error"); 
        }
	})
	
}
function jsonToString(O) {
    //return JSON.stringify(jsonobj);
    var S = [];
    var J = "";
    if (Object.prototype.toString.apply(O) === '[object Array]') {
        for (var i = 0; i < O.length; i++)
            S.push(jsonToString(O[i]));
        J = '[' + S.join(',') + ']';
    }
    else if (Object.prototype.toString.apply(O) === '[object Date]') {
        J = "new Date(" + O.getTime() + ")";
    }
    else if (Object.prototype.toString.apply(O) === '[object RegExp]' || Object.prototype.toString.apply(O) === '[object Function]') {
        J = O.toString();
    }
    else if (Object.prototype.toString.apply(O) === '[object Object]') {
        for (var i in O) {
            O[i] = typeof (O[i]) == 'string' ? '"' + O[i] + '"' : (typeof (O[i]) === 'object' ? jsonToString(O[i]) : O[i]);
            S.push(i + ':' + O[i]);
        }
        J = '{' + S.join(',') + '}';
    }

    return J;
}
function fomatFloat(src,pos){     
	if(src==0)
		return 0;
	else
	{
		return Math.round(src*Math.pow(10, pos))/Math.pow(10, pos);
	}     
} ; 
function formatfloat2(f,size)  
{  
    aa=f.split("");  
    var varchar="";  
    var num=0,k=0; //num是已得小数点位数，K用来作是否到小数点控制  
    for(var i=0;i<aa.length;i++)  
    {  
       varchar+=aa[i];  
       if(aa[i]==".")  
       {  
        k=1;  
       }  
       if(k==1)  
       {  
        num++;  
        if(num > size) break;  
     
       }  
    
    }  
    num--;  
    for(;num<size;num++) //如果位数不够，则补0  
    {  
       varchar+="0";  
    }  
  
    return varchar;  
}  
function GetTimeFormat(period)
{
	if (period==1){return "dd AA hh:mm";} //1 min
	if (period==5){return "dd AA hh:mm";} //5 min
	if (period==15){return "dd AA hh:mm";} //15 min
	if (period==30){return "dd AA hh:mm";} //30 min
	if (period==60){return "dd AA hh:mm";} //1 h
	if (period==240){return "dd AA hh:mm";}//4 h
	if (period==1440){return "dd AA yyy";} //1 d
	if (period==10080){return "dd AA yyy";} // 1 w
	if (period==43200){return "dd AA yyy";} // 1 M
}
function ClearTabsCanvsRec()
{
	var $canvasarry =$('canvas');
	$canvasarry.each(function(index){       
		var canvas=$canvasarry[index];
		 if (canvas!=null)
		 {
			 var context = canvas.getContext('2d');
			 context.clearRect(0,0,canvas.width,canvas.height);		
		 }
	})
}
function GetCharTimeName(period)
{
	if(period==1)return "M1";
	if(period==5)return "M5";
	if(period==15)return "M15";
	if(period==30)return "M30";
	if(period==60)return "H1";
	if(period==240)return "H4";
	if(period==1440)return "Daily";
	if(period==10080)return "Weekly";
	if(period==43200)return "Mothly";
}
function GetPeriodDefaultDays(period)
{
	if (period==1){return 1;} 
	if (period==5){return 3;} 
	if (period==15){return 7;} 
	if (period==30){return 7;}
	if (period==60){return 30;} 
	if (period==240){return 30;}
	if (period==1440){return 1460;} 
	if (period==10080){return 1460;} 
	if (period==43200){return 1460} 
}
function getLocalStorage() {  
    try {  
        if( !! window.localStorage ) return window.localStorage;  
    } catch(e) {  
        return undefined;  
    }  
}
function PrintSymbolChar(symb,period,cavid,chartype,mas)
{
	var db=getLocalStorage();   
	var datas=null;
	clearInterval(invGetCurrQuotes);
	invGetCurrQuotes=setInterval("GetCurrQuotes('"+CurrSymbol+"')",500);
	if (db)
	{
		getCharDatajax(
			symb,
			period,
			datas,
			function(){},
			function(d){
				var s=jsonToString(d);
				db.removeItem(symb+'-'+period);
				db.setItem(symb+'-'+period,s);				
				var symbstr=db.getItem(symb+'-'+period);
				CurrCharData=eval("(" + symbstr + ")");
				ClearTabsCanvsRec();
				if(CurrCharPainter==null)
				{
					CurrChar=new DrawKLine(cavid,CurrCharData,chartype,mas);
					CurrChar.draw();
				}else{
					CurrCharPainter.data=CurrCharData;
					CurrCharPainter.implement.options.charType=chartype;
					CurrCharPainter.implement.options.MAS=mas
					CurrCharPainter.implement.options.dataRanges=null;
					CurrCharPainter.paint();
				}								
			},
			function(er)
			{
				if(er!=null) CanvasTextOutCenter("cav_char",er);
			}
			);       				
	}  
	else //不支持本地存储只能每次都取全部sh
	{
		getCharDatajax(
				symb,
				period,
				datas,
				function(){},
				function(d){
					CurrCharData=d;
					ClearTabsCanvsRec();
					CurrChar=new DrawKLine(cavid,CurrCharData,chartype,mas);			
					CurrChar.draw();								
				},
				function(er)
				{
					if(er!=null) CanvasTextOutCenter("cav_char",er);
				}
				);       			
	}
	
	
}
/*
 * 时间格式话
 */
Date.prototype.format = function(format) {
    /*
     * eg:format="yyyy-MM-dd hh:mm:ss";
     */
    var o = {
        "M+" :this.getMonth() + 1, // month
        "A+" :GetMonthName(this.getMonth() + 1),
        "d+" :this.getDate(), // day
        "h+" :this.getHours(), // hour
        "m+" :this.getMinutes(), // minute
        "s+" :this.getSeconds(), // second
        "q+" :Math.floor((this.getMonth() + 3) / 3), // quarter
        "S" :this.getMilliseconds()
    // millisecond
    }
    

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
        	if (k[0]=="A")
        		format = format.replace(RegExp.$1, o[k]);
        	else
        		format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]:("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
Date.prototype.formatEx = function(format,zone) {
    /*
     * eg:format="yyyy-MM-dd hh:mm:ss";
     */
	var thistime = this.getTime();
	LocateTimeOffset = this.getTimezoneOffset() * 60000;
	var t = thistime + LocateTimeOffset+(zone*3600000);
	var d= new Date(t);
	
    var o = {
        "M+" :d.getMonth() + 1, // month
        "A+" :GetMonthName(d.getMonth() + 1),
        "d+" :d.getDate(), // day
        "h+" :d.getHours(), // hour
        "m+" :d.getMinutes(), // minute
        "s+" :d.getSeconds(), // second
        "q+" :Math.floor((d.getMonth() + 3) / 3), // quarter
        "S"  :d.getMilliseconds()
    // millisecond
    }
    

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
        	if (k[0]=="A")
        		format = format.replace(RegExp.$1, o[k]);
        	else
        		format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]:("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}
/*
 * unix时间戳时间格式化
 */
function FormatTime(nS,strformat) {   
	if (strformat==null)
	 return new Date(parseInt(nS) * 1000);
	else
     return new Date(parseInt(nS) * 1000).format(strformat);    
} ; 
function FormatTimeEx(nS,strformat,zone) {
     return new Date(parseInt(nS) * 1000).formatEx(strformat,zone);    
} ; 
function GetMonthName(m)
{
	if(m==1)return "Jan";
	if(m==2)return "Feb";
	if(m==3)return "Mar";
	if(m==4)return "Apr";
	if(m==5)return "May";
	if(m==6)return "Jun";
	if(m==7)return "Jul";
	if(m==8)return "Aug";
	if(m==9)return "Sep";
	if(m==10)return "Oct";
	if(m==11)return "Nov";
	if(m==12)return "Dec";	
}


function convertDate (val, strformat/*,withWeek*/) {
    /*
    var year = Math.ceil(val / 10000) - 1;
    var day = val % 100;
    var month = (Math.ceil(val / 100) - 1) % 100;
    var d = new Date();
    d.setYear(year);
    d.setMonth(month - 1);
    d.setDate(day);
    if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;
    if (withWeek) {
        var weekNames = ['日', '一', '二', '三', '四', '五', '六'];
        return year + '-' + month + '-' + day + '(星期' + weekNames[d.getDay()] + ')';
    }
    else {
        return year + '-' + month + '-' + day;
        }
    */
	return FormatTimeEx(val,strformat,0);
	//return FormatTime(val,strformat);
   };

function calcMAPrices (ks, startIndex, count, daysCn) {
    var result = new Array();
    for (var i = startIndex; i < startIndex + count; i++) {
        var startCalcIndex = i - daysCn + 1;
        if (startCalcIndex < 0) {
            result.push(false);
            continue;
        }
        var sum = 0;
        for (var k = startCalcIndex; k <= i; k++) {
            sum += ks[k].close;
        }
        var val = sum / daysCn;
        result.push(val);
    }
    return result;
};    
var timer = {
        start:function(step){this.startTime = new Date();this.stepName = step;},
        stop:function(){
            var timeSpan = new Date().getTime() - this.startTime.getTime();
            setDebugMsg(this.stepName + '耗时' + timeSpan+'ms');
        }
    };
function kLine(options,obj) {
    this.options = options;
    this.dataRanges = null;
    this.kdrawobj=obj;
}

kLine.prototype = {
    initialize: function (painter) {
        painter.klOptions = this.options;
        painter.implement = this;
    },
    start: function () {
        //timer.start('start');
        var canvas = this.canvas;
        var ctx = this.ctx;
        this.painting = true;
        var options = this.klOptions;
        var clearPart = { width: canvas.width, height: options.priceLine.region.y - 3 };
        ctx.clearRect(0, 0, clearPart.width, clearPart.height);

        ctx.save();
        window.riseColor = options.riseColor;
        window.fallColor = options.fallColor;
        window.normalColor = options.normalColor;
        if (options.backgroundColor && !this.drawnBackground) {
            ctx.beginPath();
            ctx.fillStyle = options.backgroundColor;
            ctx.rect(0, 0, clearPart.width, clearPart.height);
            ctx.fill();
            //ctx.closePath();
            //this.drawnBackground = true;
        }
        ctx.translate(options.region.x, options.region.y);
        ctx.strokeStyle = options.borderColor;
        ctx.beginPath();
        ctx.rect(0, 0, options.region.width, options.region.height);
        ctx.stroke();
        //画水平底纹线
        var spaceHeight = options.region.height / (options.horizontalLineCount );
        for (var i = 1; i <= options.horizontalLineCount; i++) {
            var y = spaceHeight * i*1.5;
            if (y * 10 % 10 == 0) y += .5;
           // this.drawHLine(options.splitLineColor, 0, y, options.region.width, 1, options.lineStyle);
        }
        //画垂直底纹线
        var spaceWidth = options.region.width / (options.verticalLineCount + 1);
        for (var i = 1; i <= options.verticalLineCount; i++) {
            var w = Math.round(spaceWidth * i);
			
            
			
            this.drawVLine(options.splitLineColor, w+=.1, 0, options.region.height, 1, options.lineStyle);
			//ctx.fillStyle=options.riseColor;
//			ctx.fillText(w,w,0);
        }
        //timer.stop();
    },
    end: function () {
        this.ctx.restore();
        var me = this;
        var options = me.klOptions;
        var region = options.region;
        var volumeRegion = options.volume.region;

        function getIndex(x) {
            x -= region.x;
            var index = Math.ceil(x / (me.klOptions.spaceWidth + me.klOptions.barWidth)) - 1;
            var count = me.toIndex - me.startIndex + 1;
            if (index >= count) index = count - 1;
            return index;
        }
        function getX(x) {
            var index = getIndex(x);
            return region.x + me.klOptions.spaceWidth * (index + 1) + me.klOptions.barWidth * index + me.klOptions.barWidth * .5;
        }
        function getPriceColor(ki, price) {
            if (price > ki.preClose) {
                return riseColor;
            } else if (price < ki.preClose) {
                return fallColor;
            } else {
                return normalColor;
            }
        }
        function getTipHtml(x) {
            var index = me.startIndex + getIndex(x);
            if (index >= me.data.datas.length) index = me.data.datas.length - 1;
            if (index < 0) index = 0;
            var ki = me.data.datas[index];
            var tipHtml = '<div><b>' + convertDate(ki.quoteTime,"yyyy.MM.dd hh:mm") + '</b></div>' +
            //'Perclose：<font color="' + getPriceColor(ki, ki.preClose) + '">' + toMoney(ki.preClose) + '</font><br/>' +
            'Open：<font color="' + getPriceColor(ki, ki.open) + '">' + ki.open + '</font><br>' +
            'High：<font color="' + getPriceColor(ki, ki.high) + '">' + ki.high + '</font><br>' +
            'Low：<font color="' + getPriceColor(ki, ki.low) + '">' + ki.low + '</font><br>' +
           'Close：<font color="' + getPriceColor(ki, ki.close) + '">' + ki.close + '</font><br>' +
            'Volume：' + bigNumberToText(ki.volume / 100) + '<br>' +
            'Amount：' + bigNumberToText(ki.amount);
            return tipHtml;
        }

        
        //添加鼠标事件
         function getEventOffsetPosition(ev){                    
                    var offset = isTouchDevice()
                        ? setTouchEventOffsetPosition(ev, getPageCoord(me.canvas))
                        : getOffset(ev);
                    return offset;
                }
                
                var controllerEvents = {
                    onStart:function(ev){
                        ev = ev || event;
                        var offset = getEventOffsetPosition(ev);
                        me.controllerStartOffset = offset;
                        me.controllerStartRange = me.dataRanges;
                    },
                    onEnd:function(ev){
                        me.controllerStartOffset = null;
                        me.controllerStartRange = null;
                    },
                    onMove:function(ev){
                        if(!me.controllerStartOffset) return;
                        ev = ev || event;
                        var offset = getEventOffsetPosition(ev);
                        var moveX = offset.offsetX - me.controllerStartOffset.offsetX;
                        var currentRange = me.controllerStartRange;/*0-100*/
                        var regionWidth = region.width;
                        var moveValue =0- (moveX/regionWidth)*(currentRange.to-currentRange.start);
                        var start = currentRange.start+moveValue;
                        var to = currentRange.to + moveValue;
                        if(start<0) {
                            start = 0;
                            to = start + (currentRange.to-currentRange.start);
                        }
                        else{
                            if(to > 100){
                                to = 100;
                                start = to-(currentRange.to-currentRange.start);
                            }
                        }                        
                        var changeToValue = {left:start,right:to};
						me.dataRanges={ start: changeToValue.left, to: changeToValue.right };
						if(!me.painting)
							me.paint();
    //                    if(!me.painting)  me.implement.kdrawobj.draw({ start: changeToValue.left, to: changeToValue.right });
						
                    }
                };

                /*
                当touchstart时的位置在K线柱附近时表示要显示柱的描述信息框；否则则要控制K线的范围
                */
                var fingerSize = {width:30,height:20};
                function shouldDoControllerEvent(ev,evtType){
                    if(evtType == undefined) return true;
                    if(typeof me.shouldController == 'undefined') me.shouldController = true;
                    if(evtType == 'touchstart'){
                        var offset = getEventOffsetPosition(ev);
                        var showTip=false;

                        var x = offset.offsetX;
                        x -= region.x;
                        var index = Math.ceil(x / (me.klOptions.spaceWidth + me.klOptions.barWidth)) - 1;

                        var indexRange = Math.ceil(fingerSize.width / (me.klOptions.spaceWidth + me.klOptions.barWidth))+1;

                        var indexStart = Math.max(0,index - indexRange/2);
                        var indexTo = Math.min(me.filteredData.length-1,index+indexRange/2);
                        var yMin=999999;
                        var yMax=-1;
                        for(index = indexStart;index<=indexTo;index++){
                            var dataAtIndex = me.filteredData[index];
                            var yTop = region.y+ (me.high - dataAtIndex.high) * region.height / (me.high - me.low) - fingerSize.height;
                            var yBottom = region.y+(me.high - dataAtIndex.low) * region.height / (me.high - me.low) + fingerSize.height;
                            yMin = Math.min(yTop,yMin);
                            yMax = Math.max(yBottom,yMax);
                        }
                        showTip = offset.offsetY >= yMin && offset.offsetY <= yMax;
                        setDebugMsg('yMin='+yMin + ',yMax=' + yMax + ',offsetY='+offset.offsetY+',showTip=' + showTip);
                        me.shouldController = !showTip;
                        
                    }
                    //setDebugMsg('shouldController=' + me.shouldController);
                    return me.shouldController;
                }

                if(!me.crossLineAndTipMgrInstance){
                    me.crossLineAndTipMgrInstance = new crossLinesAndTipMgr(me.canvas, {
                        getCrossPoint: function (ev) { return { x: getX(ev.offsetX), y: ev.offsetY }; },
                        triggerEventRanges: { x: region.x, y: region.y + 1, width: region.width, height: volumeRegion.y + volumeRegion.height - region.y },
                        tipOptions: {
                            getTipHtml: function (ev) { return getTipHtml(ev.offsetX); },
                            size:{width:120,height:150},
                            position:{x:false,y:region.y+(region.height-150)/3}, //position中的值是相对于canvas的左上角的
                            opacity:80,
                            cssClass:'',
                            offsetToPoint:30
                        },
                        crossLineOptions: {
                            color: 'white'
                        },
                        shouldDoControllerEvent:shouldDoControllerEvent,
                        controllerEvents:controllerEvents
                    });
                    me.crossLineAndTipMgrInstance.addCrossLinesAndTipEvents();
                }
                else {
                    me.crossLineAndTipMgrInstance.updateOptions({
                            getCrossPoint: function (ev) { return { x: getX(ev.offsetX), y: ev.offsetY }; },
                            triggerEventRanges: { x: region.x, y: region.y + 1, width: region.width, height: volumeRegion.y + volumeRegion.height - region.y },
                            tipOptions: {
                                getTipHtml: function (ev) { return getTipHtml(ev.offsetX); },
                                size:{width:120,height:150},
                                position:{x:false,y:region.y+(region.height-150)/3}, //position中的值是相对于canvas的左上角的
                                opacity:80,
                                cssClass:'',
                                offsetToPoint:30
                            },
                            crossLineOptions: {
                                color: 'white'
                            },
                            shouldDoControllerEvent:shouldDoControllerEvent,
                            controllerEvents:controllerEvents
                        });
                }
                if (!me.addOrentationChangedEvent) {
                    me.addOrentationChangedEvent = true;

                    addEvent(window, 'orientationchange', function (ev) {
                        me.requestController = true;
                        me.implement.onOrentationChanged.call(me);
                    });
                }

                me.painting = false;
            },
    paintItems: function () {    	    
        var options = this.klOptions;              
        var region = options.region;
        //画标题        
        var maxDataLength = this.data.datas.length;
        var needCalcSpaceAndBarWidth = true;
        if (this.dataRanges == null) { //
            //计算dataRanges
            var dataCount = Math.ceil(region.width / (options.spaceWidth + options.barWidth))-1;
            if (dataCount > maxDataLength) dataCount = maxDataLength;
            this.dataRanges = {
                start: 100 * (this.data.datas.length - dataCount) / this.data.datas.length,
                to: 100
            };
            needCalcSpaceAndBarWidth = false;
        }
        var dataRanges =this.dataRanges;
        var startIndex = Math.ceil(dataRanges.start / 100 * maxDataLength);
        var toIndex = Math.ceil(dataRanges.to / 100 * maxDataLength) + 1;
        if (toIndex == maxDataLength) toIndex = maxDataLength - 1;
        this.startIndex = startIndex;
        this.toIndex = toIndex;
        var itemsCount = toIndex - startIndex + 1;
        if (needCalcSpaceAndBarWidth) {
            //重新计算spaceWidth和barWidth属性
            function isOptionsOK() { return (options.spaceWidth + options.barWidth) * itemsCount <= region.width; }
            var spaceWidth, barWidth;
           if (isOptionsOK()) {
                        //柱足够细了
                        spaceWidth = 1;
                        barWidth = (region.width - spaceWidth * itemsCount) / itemsCount;
                        if (barWidth > 4) {
                            spaceWidth = 2;
                            barWidth = ((region.width - spaceWidth * itemsCount) / itemsCount);
                        }
                    } else {
                        spaceWidth = 1;
                        barWidth = (region.width - spaceWidth * itemsCount) / itemsCount;
                        if (barWidth <= 2) {
                            spaceWidth = 0;
                            barWidth = (region.width - spaceWidth * itemsCount) / itemsCount;
                        } else if (barWidth > 4) {
                            spaceWidth = 2;
                            barWidth = ((region.width - spaceWidth * itemsCount) / itemsCount);
                        }
                    }
            
           options.barWidth = barWidth;
           options.spaceWidth = spaceWidth;
        }

        var filteredData = [];
        for (var i = startIndex; i <= toIndex && i < maxDataLength; i++) {
            filteredData.push(this.data.datas[i]);
        }
        var high, low;
        filteredData.each(function (val, a, i) {
            if (i == 0) { high = val.high; low = val.low; }
            else { high = Math.max(val.high, high); low = Math.min(low, val.low); }
        });
		high=high+high*0.0005;
		low=low-low*0.0005;
        this.high = high+((high-low)/options.horizontalLineCount);
        this.low = low;
        var ctx = this.ctx;                     
        var me = this;
        
        function getY(price) { 
		return Math.round((me.high - price) * region.height / (me.high - me.low))+0.1;
		}
        function getCandleLineX(i) { 
		var result = i * (options.spaceWidth + options.barWidth) + (options.spaceWidth + options.barWidth) * .5; 
		//if (result * 10 % 10 == 0) result += .5;
		result=Math.round(result);
		result += .1;
		//alert (result);
		 return result; }
      
        
        var c=ctx;
        //c.save();
		c.fillStyle = options.Captions.color; 
		c.font = options.Captions.font;
    /*  //画标题
        
       
        if (options.textBaseline) c.textBaseline = options.Captions.textBaseline;
        //c.translate(this.options.region.x, this.options.region.y);
        c.fillText(me.data.quote.symbol+","+GetCharTimeName(me.data.quote.datatype),-40,-20);
        //c.fillText(me.data.quote['currentprice'],region.width-80,getY(me.data.quote['currentprice']));
*/
        
                         
        //画y轴
	 var yAxisOptions = options.yAxis;
        yAxisOptions.region = yAxisOptions.region || { x: 0 - region.x, y: 0, height: region.height, width: region.x};
		
        var yAxisImp = new yAxis(yAxisOptions);
		var yvalues=calcAxisValuesEx(high, low, options.horizontalLineCount,fomatFloat,this.data.quote.digits);
		var ypos=[]
		for (var i = 0; i < options.horizontalLineCount-1; i++) {
        	var yy=Math.round(getY(yvalues[i]));
			yy=yy+=.1
			this.drawHLine(options.splitLineColor, 0, yy, region.width, 1, options.lineStyle);
			var TxtY=Math.round(yy-yAxisOptions.fontHeight);
			var TxtX=canvas.width-40;
			ctx.font = yAxisOptions.font;
            ctx.strokeStyle = yAxisOptions.color;	
			ctx.fillText(formatfloat2(String(yvalues[i]),this.data.quote.digits),canvas.width-45,yy-yAxisOptions.fontHeight);
			/*document.write("<div style='width:40px;top:"+yy-yAxisOptions.fontHeight+"px;position:absolute'>"+formatfloat2(String(yvalues[i]))+"</div>")*/
   		 }
     
        
        //timer.start('paintItems:移动均线');
        //画移动平均线
        this.implement.paintMAs.call(this, filteredData, getY);
        //timer.stop();
        //timer.start('paintItems:画柱');        
        var currentX = 0;
        var needCandleRect = options.barWidth > 1.5;
        
        
        
        var drawCandle = function (ki, a, i) {
            var isRise = ki.close > ki.open;
            if(options.charType=="candles")
            {
            	var color = isRise ? options.rColor : options.fColor;
            }
            else
            {
            	var color = options.rColor ;
            }
            var lineX = Math.round(getCandleLineX(i))+0.1;
            if (currentX == 0) currentX = lineX;
            else {
                if (lineX - currentX < 1) return;
            }
            currentX = lineX;
            var topY = Math.round(getY(ki.high))+0.1;
            var bottomY =Math.round(getY(ki.low))+0.1 ;
            if (needCandleRect) {
                ctx.fillStyle = color;
                ctx.strokeStyle = 'lime';
                var candleY, candleHeight;
                if (isRise) {
                    candleY = getY(ki.close);
                    candleHeight = getY(ki.open) - candleY;
                } else {
                    candleY = getY(ki.open);
                    candleHeight = getY(ki.close) - candleY;
                }
               
                if(options.charType=="candles")
                {
	                //垂直(最高最低)画线
	                ctx.beginPath();
	                ctx.moveTo(lineX, topY);
	                ctx.lineTo(lineX, bottomY);
	                ctx.stroke();
					
	                var candleX = Math.round(lineX - options.barWidth / 2)+0.1;
	                ctx.beginPath();
	                ctx.fillRect(candleX, candleY, options.barWidth, candleHeight);
	                ctx.strokeRect(candleX, candleY, options.barWidth, candleHeight<=0?1:candleHeight);
	                
            	}
                else if(options.charType=="line")
                {   
                	ctx.strokeStyle = riseColor;             	
                	if (candleY && i==0) {
                		ctx.moveTo(lineX, getY(ki.close));
                	}
                	else 
                		ctx.lineTo(lineX,getY(ki.close));
                }
                else if(options.charType=="bar")
                {
                	ctx.strokeStyle = riseColor;     
                    ctx.beginPath();
                    //ctx.lineWidth=3; 
	                ctx.moveTo(lineX, topY);
	                ctx.lineTo(lineX, bottomY);
	                var bwidth = (options.barWidth / 2) ;
	                if(bwidth<=0){bwidth=1;}
	                //ctx.lineWidth=1; 
	                ctx.moveTo(lineX-bwidth, getY(ki.open));
	                ctx.lineTo(lineX,getY(ki.open));	                
	                ctx.moveTo(lineX+bwidth, getY(ki.close));
	                ctx.lineTo(lineX,getY(ki.close));
	                ctx.stroke(); 
                }
                
            } else {
                if(options.charType=="candles")
                {
	                ctx.strokeStyle = color;
	                //画线
	                ctx.beginPath();
	                ctx.moveTo(lineX, topY);
	                ctx.lineTo(lineX, bottomY);
	                ctx.stroke();
                }
                else if(options.charType=="line")
            	{
	             	if (candleY && i==0) {
	            		ctx.moveTo(lineX, getY(ki.close));
	            	}
	            	else 
	            		ctx.lineTo(lineX,getY(ki.close));
            	}
                else if(options.charType=="bar")
                {
                	ctx.lineWidth=3; 
              	    ctx.beginPath();
                	//ctx.lineWidth=3; 
	                ctx.moveTo(lineX, topY);
	                ctx.lineTo(lineX, bottomY);
	                var bwidth = (options.barWidth / 2)-2;
	                if(bwidth<=0){bwidth=1;}
	                ctx.moveTo(lineX-bwidth, getY(ki.open));
	                ctx.lineTo(lineX,getY(ki.open));	                
	                ctx.moveTo(lineX+bwidth, getY(ki.close));
	                ctx.lineTo(lineX,getY(ki.close));
	                ctx.stroke(); 
                }
            }

        };
        //画蜡烛
        if(options.charType!="bar"){ctx.beginPath();}
        filteredData.each(drawCandle);
        if(options.charType!="bar"){ctx.stroke();}
		
		
		   //画当前价格横线
        //if(me.data['currentprice']>0)
        //{

		if(maxDataLength>0){
	        var currprice=typeof(me.data.quote['currentprice'])=='undefined'? me.data.datas[maxDataLength-1].close : me.data.quote['currentprice'];
	    	var currpricey=Math.round(getY(currprice)); 
	    	//var currpricey=getY(me.data.quote['currentprice']=='undefined'? me.data.quote['']: me.data.quote['currentprice']);           	
			currpricey=currpricey+=.1;
	    	this.drawHLine("#FFFFFF", 0, currpricey, region.width, 1, "solid");
			ctx.fillStyle="yellow";
			ctx.fillRect(region.width+5,currpricey,40,15);//画价格背景框
			ctx.fillStyle="red";
			ctx.fillText(currprice,options.region.width+5,currpricey);
		}
		

        //画X轴
        var xAxisOptions = options.xAxis;
        xAxisOptions.region = { x: 0, y: region.height + 2, width: region.width, height: 20 };
        var xAxisImp = new xAxis(xAxisOptions);
        var xScalers = [];
        var stepLength = filteredData.length / (options.xAxis.scalerCount - 1);
        if (stepLength < 1) {
            options.xAxis.scalerCount = filteredData.length;
            stepLength = 1;
        }
		xScalers.push(convertDate(filteredData[0].quoteTime, me.data.quote.xformat).substr(2));
        for (var i = 1; i < options.xAxis.scalerCount; i++) {
            var index = Math.ceil(i * stepLength);
            if (index >= filteredData.length) index = filteredData.length - 1;
            var quoteTime = convertDate(filteredData[index].quoteTime, me.data.quote.xformat);
			quoteTime = quoteTime.substr(2);
            xScalers.push(quoteTime);
        }
        var xPainter = new Painter(this.canvas.id, xAxisImp, xScalers);
        xPainter.paint();

        //画量
        this.implement.paintVolume.call(this, filteredData);
    },
    paintPriceLine: function () {
        if (this.hasPaintPriceLine) return;
        this.hasPaintPriceLine = true;
        var ctx = this.ctx;
        var options = this.klOptions.priceLine;
        var region = options.region;
        ctx.save();
        ctx.translate(region.x, region.y);

        ctx.clearRect(0 - region.x, 0, this.canvas.width, region.height);
        //画水平底纹线
        var spaceHeight = region.height / (options.horizontalLineCount );
        for (var i = 1; i <= options.horizontalLineCount; i++) {
            var y = spaceHeight * i;
            //if (y * 10 % 10 == 0) y += .5;
			y=Math.round(y)+0.1;
            this.drawHLine(options.splitLineColor, 0, y, region.width, 1, options.lineStyle);
        }
        //画垂直底纹线
        var spaceWidth = region.width / (options.verticalLineCount + 1);
        for (var i = 1; i <= options.verticalLineCount; i++) {
            var w = spaceWidth * i;
            //if (w * 10 % 10 == 0) w += .5;
			w=Math.round(w)+0.1;
            this.drawVLine(options.splitLineColor, w, 0, region.height, 1, options.lineStyle);
        }
        var d = this.data.datas;

        var ksLength = d.length;
        var priceRange;
        d.each(function (val, arr, i) {
            if (i == 0) { priceRange = { high: val.high, low: val.low }; }
            else {
                priceRange.high = Math.max(priceRange.high, val.close);
                priceRange.low = Math.min(priceRange.low, val.close);
            }
        });
        if (priceRange.low > 1) priceRange.low -= 1;
        function getRangeX(i) { return i * region.width / ksLength; }
        function getRangeY(val) { return (priceRange.high - val) * region.height / (priceRange.high - priceRange.low); }
        var currentX = 0;
        d.each(function (val, arr, i) {
            var x = getRangeX(i);
            if (currentX == 0) currentX = x;
            else {
                if (x - currentX < 1) return;
            }
            currentX = x;
            var y = getRangeY(val.close);
            if (i == 0) {
                ctx.beginPath();
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });
        ctx.strokeStype = options.borderColor;
        ctx.stroke();
        ctx.lineTo(region.width, region.height);
        ctx.lineTo(0, region.height);
        ctx.closePath();
        ctx.fillStyle = options.fillColor;
        ctx.globalAlpha = options.alpha;
        ctx.fill();
        ctx.globalAlpha = 1;
        var yAxisOptions = options.yAxis;
        yAxisOptions.region = yAxisOptions.region || { x: 0 - region.x, y: 0 - 3, height: region.height, width: region.x - 3 };
        //画y轴
        var yAxisImp = new yAxis(yAxisOptions);
        var yPainter = new Painter(
            this.canvas.id,
            yAxisImp,
            calcAxisValuesEx(priceRange.high, priceRange.low, (options.horizontalLineCount),fomatFloat,this.data.quote.digits)
        );

        yPainter.paint();
        ctx.restore();
    },
    paintMAs: function (filteredData, funcGetY) {
        var ctx = this.ctx;
        var options = this.klOptions;
        var MAs = options.MAs;
        var me = this;
        MAs.each(function (val, arr, index) {
            var MA = calcMAPrices(me.data.datas, me.startIndex, filteredData.length, val.daysCount);
            val.values = MA;
			var currentX = 0;
            MA.each(function (val, arr, i) {
                if (val) {
                    me.high = Math.max(me.high, val);
                    me.low = Math.min(me.low, val);
                }
            });
        });

        MAs.each(function (val, arr, index) {
            var MA = val.values;
            ctx.strokeStyle = val.color;
            ctx.beginPath();
            MA.each(function (val, arr, i) {
                var x = i * (options.spaceWidth + options.barWidth) + (options.spaceWidth + options.barWidth) * .5;
                
                if (!val) return;
                var y = funcGetY(val);
                if (y && i==0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.stroke();
        });
    },
    paintVolume: function (filteredData) {
        var ctx = this.ctx;
        var options = this.klOptions;
        //画量线
        var volumeOptions = options.volume;
        var volumeRegion = volumeOptions.region;
        ctx.restore();
        ctx.save();
        ctx.translate(volumeRegion.x, volumeRegion.y);
        ctx.globalAlpha = 1;
        //画水平底纹线
        var spaceHeight = volumeRegion.height / (volumeOptions.horizontalLineCount + 1);
        for (var i = 1; i <= volumeOptions.horizontalLineCount; i++) {
            var y = spaceHeight * i;
            //if (y * 10 % 10 == 0) y += .5;
			y=Math.round(y)+0.1;
            this.drawHLine(options.splitLineColor, 0, y, options.region.width, 1, options.lineStyle);
        }
        //画垂直底纹线
        var spaceWidth = options.region.width / (options.verticalLineCount + 1);
        for (var i = 1; i <= options.verticalLineCount; i++) {
            var w = spaceWidth * i;
            //if (w * 10 % 10 == 0) w += .5;
			w=Math.round(w)+0.1;
            this.drawVLine(options.splitLineColor, w, 0, volumeRegion.height, 1, options.lineStyle);
        }

        ctx.strokeStyle = options.borderColor;
        ctx.beginPath();
        ctx.rect(0, 0, volumeRegion.width, volumeRegion.height);
        ctx.stroke();
        //drawLines(ctx, [{ direction: 'H', position: .50, color: 'lightgray'}]);
        var maxVolume = 0;

        filteredData.each(function (val, arr, i) {
            maxVolume = Math.max(maxVolume, val.volume);
        });
		this.filteredData = filteredData; 
        maxVolume *= 1.05;
        function getVolumeY(v) { return volumeRegion.height - volumeRegion.height / maxVolume * v; }
        function getVolumeX(i) { return i * (options.spaceWidth + options.barWidth) + (options.spaceWidth) * .5; }
        ctx.globalAlpha = 1;
        ctx.strokeStyle =options.riseColor;
		
        filteredData.each(function (val, arr, i) {
            var x = getVolumeX(i);
            var y = getVolumeY(val.volume);
            ctx.beginPath();
            ctx.rect(x, y, options.barWidth, volumeRegion.height / maxVolume * val.volume);
            //ctx.fillStyle = val.close > val.open ? options.rColor : options.fColor;
			ctx.fillStyle = 'lime';
            ctx.fill();
            
            //ctx.strokeRect(x, y, options.barWidth, volumeRegion.height / maxVolume * val.volume);
        });

        //画y轴
        var volumeLevel;
        var volumeUnit;
        if (maxVolume < 10000 * 100) {
            volumeLevel = 10000;
            volumeUnit = '';//'万';
        }
        else {
            volumeLevel = 10000 * 100;
            volumeUnit =  '';//'百万';
        }

        var volumeScalers = [];
        volumeScalers.push((maxVolume / volumeLevel).toFixed(2));
        volumeScalers.push((maxVolume / 2 / volumeLevel).toFixed(2));
        volumeScalers.push(volumeUnit);
        var volumeScalerOptions = volumeOptions.yAxis;
        volumeScalerOptions.region = volumeScalerOptions.region || { x: 0 - volumeRegion.x, y: -3, width: volumeRegion.x - 3, height: volumeRegion.height };
        var volumeScalerImp = new yAxis(volumeScalerOptions);
        var volumeScalerPainter = new Painter(this.canvas.id, volumeScalerImp, volumeScalers);
        volumeScalerPainter.paint();
        ctx.restore();
        ctx.save();
    },
    onOrentationChanged: function (e) {
        var orientation = window.orientation;
        function getWidth() { return screen.width-40;/*((orientation == 90 || orientation == -90) ? screen.width : screen.height) - 40; */}
        if (typeof orientation != 'undefined') {
            setDebugMsg('orientation=' + orientation + ',getWidth=' + getWidth());
            //if(orientation == 90 || orientation == -90 || orientation == 0){
            var me = this;
            var width = getWidth();
            //var rate = width/me.canvas.width;
            me.canvas.width = width;
            var options = me.klOptions;
            var chartWidth = width - options.chartMargin.left - options.chartMargin.right;
            me.klOptions.volume.region.width =
                    me.klOptions.priceLine.region.width =
                    me.klOptions.region.width = chartWidth;
            //方向改变了，要重画controller
            me.controller = null;
            me.hasPaintPriceLine = false;
            me.implement.kdrawobj.draw({ start: me.dataRanges.start, to: me.dataRanges.to });
            // }
        }
    }
};

function DrawKLine(cavid,data,type,mas)
{
	this.canvas = document.getElementById(cavid);
	this.parent=this.canvas.parentNode;
	this.canvid =cavid;
    if (!this.canvas.getContext) return;
    this.ctx = this.canvas.getContext('2d');
    this.data = data;
    this.width = this.canvas.width;
    this.height = this.canvas.height;
    this.initialWidth=this.canvas.width;
    //this.printer=undefined;
    this.chartype=type;
    this.horizontalLineCount=Math.round((this.height-125)/30);
    this.id=new Date().getTime();
    this.MAS=(mas==null||typeof(mas)=='undefined') ?[]:mas;
    //this.charheight= this.height-125;
    this.charheight= this.height-80;
        

}
DrawKLine.prototype = {		
	    draw:function(ranges) {            
        var kOptions = {
	            backgroundColor:'black',
	            riseColor: 'lime',
	            fallColor: 'red',
	            rColor: 'black',
	            fColor: 'white',
	           	normalColor: 'black',
	            charType:'bar', //(bar,line,candles)
	            //主图区域的边距
	           // chartMargin:{left:45.5,top:20.5,right:30.5},
	            region: { x: 0.5, y: 0.5, width: this.initialWidth -45, height: this.charheight },
	            barWidth: 5,defaultWidth: 10, spaceWidth: 2,defaultspaceWidth: 2,horizontalLineCount: this.horizontalLineCount, verticalLineCount: 8, lineStyle: 'dashed', borderColor: 'white', splitLineColor: '#efefef', lineWidth: 1,
	            MAs: [
	                /*{ color: 'rgb(255,70,251)', daysCount: 5 },
	                { color: 'rgb(227,150,34)', daysCount: 10 },
	                { color: 'rgb(53,71,107)', daysCount: 20 },
	                { color: 'rgb(0,0,0)', daysCount: 60 }
	                */
	                ],
	            Captions: {
	                font: '11px Arial', // region: { },
	                color: 'white',
	                align: 'right',
	                fontHeight: 8,
	                textBaseline: 'top'
	            },            
	            yAxis: {
	                font: '11px Arial', // region: { },
	                color: 'white',
	                align: 'right',
	                fontHeight: 8,
	                textBaseline: 'top'
	            },
	            xAxis: {
	                font: '9px arial', // region: { },
	                color: 'white',
	                align: 'right',
	                fontHeight: 8,
	                textBaseline: 'top',
	                scalerCount: this.initialWidth/100
	            },
	            volume: {
	                region: { x: 0.5, y:  this.charheight+25.5, height: 50, width: this.initialWidth - 45 },
	                horizontalLineCount: 1,
	                yAxis: {
	                    font: '11px Arial', // region: { },
	                    color: 'white',
	                    align: 'right',
	                    fontHeight: 8,
	                    textBaseline: 'top'
	                }
            },
            priceLine: {
                region:{ x: 45.5, y: this.charheight+80.5, height: 30, width: this.initialWidth - 45.5 - 30.5 },
                verticalLineCount: 7,
                horizontalLineCount: 1, lineStyle: 'solid', borderColor: 'gray', splitLineColor: '#eeeeee',fillColor:'lightgray',alpha:.5,
                yAxis: {
                    font: '11px Arial', // region: { },
                    color: 'black',
                    align: 'right',
                    fontHeight: 8,
                    textBaseline: 'top'
                }
            },
            controller:{
                bar: { width: 10, height: 25, borderColor: 'black', fillColor: 'lightgray' },
                minBarDistance: 5
            }
        };       
        kOptions.charType=this.chartype;
        kOptions.MAs=this.MAS;
        //alert(this.id);
        if(!CurrCharPainter){    
        	
            if(this.canvas.width != this.initialWidth) this.canvas.width = this.initialWidth;
            var kl=new kLine(kOptions,this);
            CurrCharPainter = new Painter(this.canvid, kl, this.data);
        }
        else
        {
        	CurrCharPainter.paintImplement.options.MAs=this.MAS;
        	CurrCharPainter.paintImplement.options.charType=this.chartype;
			//CurrcharPainter.paintImplement.dataRanges=ranges;
        	
        }
        CurrCharPainter.data=this.data;
        CurrCharPainter.dataRanges = ranges;
        CurrCharPainter.paint();
    }
};


//调用函数，获取数值 




function exitpage()
{
	clearInterval(invGetCurrQuotes);
	clearInterval(invPrintChar);
}	</script>
</body>

</html>