<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	@php
	if ($GLOBALS['SysValue']['nav']['nav']=='CID') {
		if ($GLOBALS['SysValue']['other']['productPageThis']=='ALL') {
			$cur_page_num=' - все товары';
				if (!empty($GLOBALS['SysValue']['other']['catalogName']) && empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogName'].$cur_page_num.' - PROДАЧА</title>';
				} else if (!empty($GLOBALS['SysValue']['other']['catalogName']) && !empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogCategory'].$cur_page_num.' - PROДАЧА</title>';
				} else {
					echo '<title>@pageTitl@</title>';	
				}			
		} else {
			if ($GLOBALS['SysValue']['other']['productPageThis']!=1) {
				$cur_page_num=' - страница '.$GLOBALS['SysValue']['other']['productPageThis'];
				if (!empty($GLOBALS['SysValue']['other']['catalogName']) && empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogName'].$cur_page_num.' - PROДАЧА</title>';
				} else if (!empty($GLOBALS['SysValue']['other']['catalogName']) && !empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogCategory'].$cur_page_num.' - PROДАЧА</title>';
				} else {
					echo '<title>@pageTitl@</title>';	
				}				
			}
			else
			{
				echo '<title>@pageTitl@</title>';	
			}
		}
	} else {
			echo '<title>@pageTitl@</title>';	
		}
	php@

	<meta name="ktoprodvinul" content="c95d57d3680b0500">
	<meta name="cmsmagazine" content="c45d4085c1835d45ae4ee5d8a8b6e1f9">
	<meta http-equiv="Content-Type" content="text-html; charset=windows-1251">
	<meta name="description" content="@pageDesc@">
	<meta name="keywords" content="@pageKeyw@">
	<meta name="copyright" content="@pageReg@">
	<meta name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
	<meta name="domen-copyright" content="@pageDomen@">
	<meta content="General" name="rating">
	<meta name="ROBOTS" content="ALL">
	<meta name="viewport" content="width=device-width">
	<link rel="SHORTCUT ICON" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon"> 
	<link rel="icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<link rel="subresource" href="java/phpshop.js">
	<link rel="subresource" href="java/leftcatalogmenu.js">
	<!--<link rel="subresource" href="phpshop/lib/Subsys/JsHttpRequest/Js.js">-->
	<link rel="subresource" href="java/swfobject.js">
	<link rel="subresource" href="java/highslide/highslide-p.js">
	<link rel="stylesheet" type="text/css" href="java/jquery-ui-1.11.2.custom/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="java/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css">
	<link rel="stylesheet" type="text/css" href="java/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css">
	
	<link rel="subresource" href="java/jquery-ui-1.11.2.custom/jquery-ui.js">
	<!--	
    <script>
		$(function() {
	    	//$( document ).tooltip();
			$( "#mail" ).tooltip({ position: { my: "left+23 center", at: "right center" } });
			$( "#name_person" ).tooltip({ position: { my: "left+23 center", at: "right center" } });
			$( "#tel_name" ).tooltip({ position: { my: "left+23 center", at: "right center" } });
	  	});
	</script>
	-->
	<link rel="subresource" href="java/ap.js">
	<!--<link rel="subresource" href="http://yes-credit.su/crem/js/jquery-ui-1.8.23.custom.min.js">-->
	<!--<link rel="subresource" href="http://yes-credit.su/crem/js/crem.js">-->
	<!--с 18.11.2014 подключен vsevcredit.ru-->
	<link rel="subresource" href="http://vsevcredit.ru/js/widget1251.js">
	<script type="text/javascript" src="http://vsevcredit.ru/js/widget1251.js"></script>
	<script type="text/javascript" src="java/lib/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="java/carhartl-jquery-cookie-3caf209/jquery.cookie.js"></script>
	<script type="text/javascript" src="phpshop/lib/JsHttpRequest/JsHttpRequest.js"></script>
    <!-- <script type="text/javascript" src="java/leftcatalogmenu.js"></script>	-->
	<script type="text/javascript">
		var scripts = [
		'java/leftcatalogmenu.js',
  		'java/phpshop.js',
  		//'phpshop/lib/Subsys/JsHttpRequest/Js.js',
		'java/swfobject.js',
		'java/highslide/highslide-p.js',
		'java/ap.js',
		'http://vsevcredit.ru/js/widget1251.j',
		'java/jquery-ui-1.11.2.custom/jquery-ui.js'
		];
		var src;
		var script;
		var pendingScripts = [];
		var firstScript = document.scripts[0];
		// Watch scripts load in IE
		function stateChange() {
	  		// Execute as many scripts in order as we can
	  		var pendingScript;
	  		while (pendingScripts[0] && pendingScripts[0].readyState == 'loaded') {
		    		pendingScript = pendingScripts.shift();
		    		// avoid future loading events from this script (eg, if src changes)
		    		pendingScript.onreadystatechange = null;
		    		// can't just appendChild, old IE bug if element isn't closed
		    		firstScript.parentNode.insertBefore(pendingScript, firstScript);
	  		}
		}
		// loop through our script urls
		while (src = scripts.shift()) {
		  if ('async' in firstScript) { // modern browsers
		    script = document.createElement('script');
		    script.async = false;
		    script.src = src;
		    document.head.appendChild(script);
		  }
		  else if (firstScript.readyState) { // IE<10
		    // create a script and add it to our todo pile
		    script = document.createElement('script');
		    pendingScripts.push(script);
		    // listen for state changes
		    script.onreadystatechange = stateChange;
		    // must set src AFTER adding onreadystatechange listener
		    // else we miss the loaded event for cached scripts
		    script.src = src;
		  }
		  else { // fall back to defer
		    document.write('<script src="' + src + '" defer></'+'script>');
		  }
		}
	</script>
	<link href="@pageCss@" type="text/css" rel="stylesheet">
	<!-- <script type="text/javascript" src="java/phpshop.js"></script> -->
	<script type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js" defer></script>
	<!-- <script type="text/javascript" src="java/swfobject.js" defer></script> -->
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css">
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/script.js?v1"></script>

	<!-- <script type="text/javascript" src="java/highslide/highslide-p.js" defer></script> -->
	<!-- <script type="text/javascript" src="java/ap.js" defer></script> -->
	 
	<!-- credit -->
	<!--<script src="http://yes-credit.su/crem/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript" defer></script>-->
    	<!--<script src="http://yes-credit.su/crem/js/crem.js" type="text/javascript" defer></script>-->
    	<!--<link href="http://yes-credit.su/crem/css/blizter.css" rel="stylesheet" type="text/css">-->
	<!--/ credit -->

	<!-- Fancybox -->
    	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.pack.js" defer></script>
    	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.css">
    <!-- /Fancybox -->
	 
	<script type="text/javascript">
			<!--
			function SendForm()
			{		
				if   ( document.getElementById('email').value.search( /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/ ) == -1 ) 
				{   //^[w.\-_]+@[w\-_]+.[w.\-_]+$
					alert('Пожалуйста, заполните поле "почтовый ящик" корректно!');
					document.getElementById('email').focus();			
					return false;		
				}
				else
				{
					return true;
				}				
			}	 
			-->
	</script>
 
	<script type="text/javascript" src="java/aap.js" defer></script>

	<!--
	@php 
		require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
		$ST = new Seotools; 

		// Вывод метки, где 
		// $variable = название переменной (столбца). 
		// $default_value = значение по умолчанию 
		echo $ST->get("h1", $default_value); 
	php@
	-->
	
	@php
		if ($_COOKIE['sincity']=="sp")
			echo '<style type="text/css">.store, .shop, .shop_cheb{display:none;}</style>';
		else
			echo '<style type="text/css">.shop_spb{display:none;}</style>';
			
		if ($_COOKIE['sincity']=="chb")
			echo '<style type="text/css">.store, .shop, .shop_spb{display:none;}</style>';
		else
			echo '<style type="text/css">.shop_cheb{display:none;}</style>';
			
		if ($_COOKIE['sincity']=="other")
			echo '<style type="text/css">.shop_spb, .shop_cheb{display:none;}</style>';		
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
	
	<!-- Universal Analytics counter -->
	<script type="text/javascript">
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
		  ga('create', 'UA-29201270-1', 'auto');
		  ga('send', 'pageview');
	</script>
	
</head>

<body onload="default_load('false','false');NavActive('index');LoadPath('@ShopDir@');" >

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
					<div class="logo"><a href="/"><img src="images/logo.png" alt="LOGO" border="0"></a></div>
		<div class="phone">
			
			<!--<span class="name" style="padding-top: 8px;">Интернет-магазин. Звоните:<span style="font-weight: bold;"> -->
			@php										
				require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');
				$city=$_COOKIE['sincity'];					
				if(!isset($city)){
					$city = 'm';						
				}
				echo $time_rabotu[$city];							
			php@	
			<!--</span></span>-->
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
				/*require($_SERVER['DOCUMENT_ROOT'].'/geo_info.php');
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
			<span class="charttitle"> Корзина</span>
			<span class="prods">Товаров:</span><span class="red"><span id="num">@num@</span> шт.</span>
			<span class="summ">Сумма:</span><span class="red"><span id="sum">@sum@</span> @productValutaName@</span>
            <span class="charttitle2" id="order" style="display:@orderEnabled@; "><a href="/order/" >Оформить заказ</a></span>
		</div>
	</div>
	<!--slider begin-->
	<div id="slides">
            <div class="slides_container">
                @rightMenu@
           
            </div>
    <a href="#" class="prev"></a>
	<a href="#" class="next"></a>
    </div>
	<!--slider end-->
				@php			
					if ($_COOKIE['sincity']=="m" || $_COOKIE['sincity']=="chb" || $_COOKIE['sincity']=="other" || !isset($_COOKIE['sincity'])) {
					$str='<script type="text/javascript">
						  $(document).ready(function(){
								$("div").remove(".piter_1");
						  })
						  </script>';  
					} else if ($_COOKIE['sincity']=="sp") {
					$str='';
					}
					echo $str;	
				php@

	<div id="main">
		<div class="left">
			<div class="search">
				   <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
					<input  class="keyword" type="text" name="words"  value="Я ищу..." onfocus="if(this.value=='Я ищу...'){this.value='';}" onblur="if(this.value==''){this.value='Я ищу...';}">
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
			<div class="popular">
				<div class="title">
					<div>Популярные товары</div>
					<span class="rightcorner"></span>
				</div>
				
				<div class="wrapper">
                <div class="tovar1" >@specMainIcon@</div>
				</div>
			</div>
			<!-- Popular end -->
			
			<div class="special">
				<div class="title">
					<div onclick="window.location.replace('/spec/');" style="cursor:pointer;">Спецпредложения</div><a href="/spec/">Все спецпредложения</a>
					<span class="rightcorner"></span>
				</div>
				<div class="wrapper">
                    <div class="tovar1" >@specMain@</div>             
				</div>
			</div>
			<!-- Special end  -->
			
			<div class="bought">
				<div class="title">
					<div>Только что купили</div>
					<span class="rightcorner"></span>
				</div>
				<div class="wrapper">
                      <div class="tovar1" >@nowBuy@</div>      
				</div>
			</div>
			<!-- Bought end  -->
			
			<div class="simpletextbox">
				<div class="simpletextbox2"><h1>@mainContentTitle@</h1></div>
				@mainContent@
			</div>
			
		</div>
		<!-- Content end -->
		</div>
	<!-- main end -->
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
					<script type="text/javascript">
						// <![CDATA[
							m=document.getElementById("cv1");
							m.value="nohspamcode";
						// ]]>
					</script>
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
<!-- RedHelper -->
<script type="text/javascript" charset="utf-8" src="https://web.redhelper.ru/service/main.js?c=prodacha" defer></script> 
<!--/Redhelper -->
</body>
<html>