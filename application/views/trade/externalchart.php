<?php $this->load->view('header')?>
<div class="container-fluid">
<div class="row-fluid">

<div class="span2">
<?php $this->load->view('index_left')?>
</div>

<div class="span10">
	<div class="dropdown pull-right">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">选择货币对</a>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
		</ul>
	</div>

<iframe id="external_chart" frameborder="0" src="https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=72b8c0e262488c8c8bce3c418eb5481b&locale=en_GB&id=EURUSD&fp=5" width="973" height="650"></iframe>
<div class="alert alert-info">
注意：此图表行情数据仅供参考，并非反映服务器真实报价数据。
<br>
Attention: The diagram and charts presented here are for illustration purposes only and may not reflect actual server data.
</div>
<script>
	$(function(){
		var EXPORT_SYMBOLS=['XAUUSD','XAGUSD','USDCHF','GBPUSD','EURUSD','USDJPY','AUDUSD','EURCHF','EURJPY','AUDJPY','CHFJPY','EURCAD','CADJPY','EURGBP','GBPJPY','GBPCHF','USDCAD','NZDUSD','EURAUD','AUDNZD'];
		FillSymbols();

		function FillSymbols()
		{
			var dd=$("ul.dropdown-menu");
			dd.empty();
			$.each( EXPORT_SYMBOLS, function(index, content){
				dd.append("<li><a tabindex='-1' href='#' id='li_" + content + "'>" + content + "</a></li>");
			});
		}

		$("li a").click(function(){
			var n = $(this).text();
			switch (n)
			{
				case "XAUUSD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=3856996f81230e7ef02854a071a602ca&locale=en_GB&id=XAUUSD&fp=2");
					break;
				case "XAGUSD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=3a1ede2130121c19b38cf961e91e8b7f&locale=en_GB&id=XAGUSD&fp=4");
					break;
				case "USDCHF":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=6712567aa2c915dc8fa970ad0537ba82&locale=en_GB&id=USDCHF&fp=5");
					break;
				case "GBPUSD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=4cab97be3453f334da5b5c0e3d50094b&locale=en_GB&id=GBPUSD&fp=5");
					break;
				case "USDJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=68298f5fdc32e99ca808e5e1126b34d2&locale=en_GB&id=USDJPY&fp=3");
					break;
				case "AUDUSD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=d3fef9438fe4f7b234e3eecb110b7b7c&locale=en_GB&id=AUDUSD&fp=5");
					break;
				case "EURCHF":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=c8e0decc4a5c24dc69f8ab990745489a&locale=en_GB&id=EURCHF&fp=5");
					break;
				case "EURJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=657dfd3d1fda24052066f6911c5efe44&locale=en_GB&id=EURJPY&fp=3");
					break;
				case "AUDJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=0200292eb69826cbd97703b6e49199a9&locale=en_GB&id=AUDJPY&fp=3");
					break;
				case "CHFJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=87cef10bd250b2fec0fbdffe6f94c440&locale=en_GB&id=CHFJPY&fp=3");
					break;
				case "EURCAD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=40972f15fc94ac6be66fac3630ec64bf&locale=en_GB&id=EURCAD&fp=5");
					break;
				case "CADJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=c457e0cbd428117cda611fcc752e4c86&locale=en_GB&id=CADJPY&fp=3");
					break;
				
				case "EURGBP":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=fbb78f50a4a24f08e509ff89984e2034&locale=en_GB&id=EURGBP&fp=5");
					break;
				
				case "GBPJPY":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=4d6cf8a237f2db198ce35819d85190c0&locale=en_GB&id=GBPJPY&fp=3");
					break;
				
				case "GBPCHF":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=6657edd13f685d7018669d844d801fdf&locale=en_GB&id=GBPCHF&fp=5");
					break;
				
				case "USDCAD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=73cf5cf710193bf2a33ccb6289006da9&locale=en_GB&id=USDCAD&fp=5");
					break;
				
				case "NZDUSD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=efa4964532fd6c17edf6ae5d4ed593aa&locale=en_GB&id=NZDUSD&fp=5");
					break;
				
				case "EURAUD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=fad5d0d058a26f3aa601e5d1ff9c6d46&locale=en_GB&id=EURAUD&fp=5");
					break;
				
				case "AUDNZD":
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.				phtml?uid=2979278&key=3731c04903bb40dbfbd2834b958b9369&locale=en_GB&id=AUDNZD&fp=5");
					break;
				default:
					$("#external_chart").attr("src","https://rt1.ef.it-finance.com/EasyForex/itcharts.phtml?uid=2979278&key=72b8c0e262488c8c8bce3c418eb5481b&locale=en_GB&id=EURUSD&fp=5");
					break;
					break;
			}
		});
	});
</script>
</div>
</div>
<?php $this->load->view('footer')?>