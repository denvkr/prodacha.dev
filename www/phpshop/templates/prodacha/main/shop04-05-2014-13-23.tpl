<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>@pageTitl@</title>
	<meta http-equiv="Content-Type" content="text-html; charset=windows-1251">
	<meta name="description" content="@pageDesc@">
	<meta name="keywords" content="@pageKeyw@">
	<meta name="copyright" content="@pageReg@">
	<meta name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
	<meta name="domen-copyright" content="@pageDomen@">
	<meta content="General" name="rating">
	<meta name="ROBOTS" content="ALL">
	<meta name="viewport" content="width=device-width">

	<link rel="SHORTCUT ICON" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon" /> 
	<link rel="icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<link href="@pageCss@" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="java/phpshop.js"></script>
	<script type="text/javascript" src="java/tabpane.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></script>
	<script type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
	<script type="text/javascript" src="java/swfobject.js"></script>
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css" type="text/css">
	<script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/script.js" type="text/javascript"></script>
	<script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/accounting.min.js" type="text/javascript"></script>
	
	<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css"/>
	
	<!-- credit -->
	<script src="http://yes-credit.su/crem/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
    <script src="http://yes-credit.su/crem/js/crem.js" type="text/javascript"></script>
    <link href="http://yes-credit.su/crem/css/blizter.css" rel="stylesheet" type="text/css"/>
	<!--/ credit -->
	
	 <!-- Fancybox -->
    <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.pack.js"></script>
    <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.css" rel="stylesheet">
    <!-- /Fancybox -->
	
	<script language="javascript">
			<!--
			function SendForm()
			{		
				if   ( document.getElementById('email').value.search( /^[\w\.-_]+@[\w-_]+\.[\w\.-_]+$/ ) == -1 ) 
				{
					
					alert('Пожалуйста, заполните поле "почтовый ящик" корректно!');
					document.getElementById('email').focus();			
					return false;		
				}
				else
				{
					
					return true;

				}				
			}
			 
			//-->
		</script>
		
	
	<script type="text/javascript" src="java/ap.js"></script>
	<script type="text/javascript" src="java/aap.js"></script>
	
	@php
		if ($_COOKIE['sincity']=="sp")					
			echo "<style>.store, .shop{display:none;}</style>";
		else					
			echo "<style>.shop_spb{display:none;}</style>";
	php@

	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-29201270-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
</head>
<body onload="pressbutt_load('@thisCat@','@pathTemplate@','false','false');NavActive('@NavActive@');LoadPath('@ShopDir@');" >
<script type="text/javascript">
	load_();
	
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
var yaParams = {/*Здесь параметры визита*/};
</script>

<script type="text/javascript">
(function (d, w, c) {
(w[c] = w[c] || []).push(function() {
try {
w.yaCounter17951710 = new Ya.Metrika({id:17951710,
webvisor:true,
clickmap:true,
trackLinks:true,
accurateTrackBounce:true,
trackHash:true,params:window.yaParams||{ }});
} catch(e) { }
});

var n = d.getElementsByTagName("script")[0],
s = d.createElement("script"),
f = function () { n.parentNode.insertBefore(s, n); };
s.type = "text/javascript";
s.async = true;
s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

if (w.opera == "[object Opera]") {
d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/17951710" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div class="black_overlay" id="fade" onclick="document.getElementById('fade').style.display='none'"></div>
<div class="topline">
		<div class="wrapper">
			<div class="topmenu">
			
			
				<ul class="menu">    
                @topMenu@                                                        
				</ul>
				
				<div class="netref">
				@php					
					require($_SERVER['DOCUMENT_ROOT'].'/net_pather.php');					
				php@			
				</div>
				
			</div>
            @usersDisp@
			
		</div>
	</div>
	<div class="head">
				<div class="logo"><a href="/"><img src="images/logo.png" alt="" border="0"></a></div>
		<div class="phone">
			<!--<span class="name" style="padding-top: 8px;">Интернет-магазин. Звоните:<span style="font-weight: bold;">	-->					
			@php
					
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];					
					if(!isset($city)){
						$city = 'm';						
					}
					echo $time_rabotu[$city];							
					
			php@						
			<!--</span></span> -->
			<span class="number">
			@php
					
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];					
					if(!isset($city)){
						$city = 'm';						
					}
					echo $telnum[$city];							
					
			php@			
			</span>
		</div>
		<div class="work_time">

          	@php
					
				/*	require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];					
					if(!isset($city)){
						$city = 'm';						
					}
					echo $warning[$city];							
					*/
					
			php@

			<span class="time">			
				@php					
						
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];													
					if(!isset($city)){
						$city = 'm';						
					}
					echo $rejim_rabotu[$city];												
				php@				
			</span> 
		</div>
		<div class="chart" onclick="window.location.replace('/order/');" style="cursor:pointer;">
			<span class="charttitle">Корзина</span>
			<span class="prods">Товаров:</span><span class="red"><span id="num">@num@</span>&nbsp;шт.</span>
			<span class="summ">Сумма:</span><span class="red"><span id="sum">@sum@</span>&nbsp;@productValutaName@</span>
            <span class="charttitle2" id="order" style="display:@orderEnabled@; "><a href="/order/" >Оформить заказ</a></span>
		</div>
	</div>


	<div id="main">
		<div class="left">
			<div class="search">
				 <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
					<input  class="keyword" type="text" name="words"  value="Я ищу..." onfocus="if(this.value=='Я ищу...'){this.value='';}" onblur="if(this.value==''){this.value='Я ищу...';}" />
				</form>
			</div>
			<div class="menu">
				<ul class="mainmenu">
                @leftCatal@
				</ul>
			</div>
			<!-- menu end -->
			
			@banersDisp@
			@oprosDisp@
			@leftMenu@
  		
           
		
			
		</div>
		<!-- left end -->
		<div class="content">
			
			<script type="text/javascript" src="java/highslide/highslide-p.js"></script>			
			<script type="text/javascript">
				hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>',position: 'top right',fade: 2});
				hs.graphicsDir = 'java/highslide/graphics/';
				 hs.wrapperClassName = 'borderless';
			</script>
		
			<div class="catalog">				
				@DispShop@
			</div>
		</div>
		<!-- Content end -->
	</div>
	<!-- main end -->
   
        <div style="clear:both"></div>
<div id="footer">
	<div class="center">
		<div class="foot_contacts">
			<div class="contacts">
				<div class="h2">Контакты</div>
				@php					
						
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];													
					if(!isset($city)){
						$city = 'm';						
					}
					echo $footcontacts1[$city];												
				php@			
			</div>
			<div class="graphic">
				<div class="h2">График работы</div>				
				@php					
						
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];													
					if(!isset($city)){
						$city = 'm';						
					}
					echo $footcontacts2[$city];												
				php@			
			</div>
			<div class="map">
				@php					
						
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];													
					if(!isset($city)){
						$city = 'm';						
					}
					echo $weonmap[$city];												
				php@	
				
				<div class="h2">Мы на карте</div>
				@php					
						
					require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');

					$city=$_COOKIE['sincity'];													
					if(!isset($city)){
						$city = 'm';						
					}
					echo $map[$city];												
				php@		
			</div>
			<div class="form">
				<div class="h2">Задать вопрос</div>
				<form action="http://prodacha.ru/snip_call.php" method="POST" onsubmit="return SendForm()" >
					<input class="text" type="text" name="email" id="email" value="Ваш E-mail" onfocus="if(this.value=='Ваш E-mail'){this.value='';}" onblur="if(this.value==''){this.value='Ваш E-mail';}">
					<textarea class="textarea" name="textArea"  rows="3" cols="30" value="Вопрос" onfocus="if(this.value=='Вопрос'){this.value='';}" onblur="if(this.value==''){this.value='Вопрос';}">Вопрос</textarea>
				
				<input id="cv1" type="hidden" name="cv" value="null" />
				<script type="text/javascript">// <![CDATA[
					m=document.getElementById("cv1");
					m.value="nohspamcode";
				// ]]></script>

					<input type="submit" value="Отправить" class="button">
				</form>
			</div>	
		</div>
		<div class="copyright">
			<span class="copy">&copy; PROДАЧА - интернет-магазин садовой техники</span>
			<div class="foot_menu">
				<ul>
					@topMenu@
					<li><a href="http://prodacha.ru/map/">КАРТА САЙТА</a></li>
				</ul>
                </div>
		</div>	
	</div>
	<!-- foot center end -->
</div>
<!-- 

-->
<div id="cartwindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="middle"> </td>
      <td><b>Внимание...</b><br />
        Товар добавлен в корзину</td>
    </tr>
  </table>
</div>
<div id="comparewindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="middle"></td>
      <td><b>Внимание...</b><br />
        Товар добавлен в сравнение</td>
    </tr>
  </table>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var prices = $('.descr_wrapper .price');
		var add = '<span class="smallfont"> руб.</span>';
		for(var i = 0; i < prices.length; i++) {
			var price = Number(prices[i].innerHTML.replace(/\D+/g,""));
			price = accounting.formatNumber(price, 0, " ", ",")
			prices[i].innerHTML = price + add;
		}
		
		prices = $('.addchart_block_table_price .price div');
		for(var i = 0; i < prices.length; i++) {
			var price = Number(prices[i].innerHTML.replace(/\D+/g,""));
			price = accounting.formatNumber(price, 0, " ", ",")
			prices[i].innerHTML = price + add;
		}
		
		prices = $('.prev_price');
		for(var i = 0; i < prices.length; i++) {
			var price = Number(prices[i].innerHTML.replace(/\D+/g,""));
			if (price !== 0) {
			price = accounting.formatNumber(price, 0, " ", ",");	
			prices[i].innerHTML = '<strike>' + price + '</strike>';
			}
		}
		
	});
</script>

<!-- RedHelper -->
<script id="rhlpscrtg" type="text/javascript" charset="utf-8" async="async" 
	src="https://web.redhelper.ru/service/main.js?c=prodacha">
</script> 
<!--/Redhelper -->
<!-- Livetex
<script type='text/javascript'> /* build:::7 */
    var liveTex = true,
    liveTexID = 57175,
    liveTex_object = true;
    (function() {
    var lt = document.createElement('script');
    lt.type ='text/javascript';
    lt.async = true;
    lt.src = 'http://cs15.livetex.ru/js/client.js';
    var sc = document.getElementsByTagName('script')[0];
    if ( sc ) sc.parentNode.insertBefore(lt, sc);
    else document.documentElement.firstChild.appendChild(lt);
    })();
    </script>
    -->
	


