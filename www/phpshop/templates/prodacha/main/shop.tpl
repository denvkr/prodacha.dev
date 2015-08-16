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
	<meta http-equiv="Content-Type" content="text-html; charset=windows-1251">
	@php
	if ($GLOBALS['SysValue']['nav']['nav']=='CID') {
		if ($GLOBALS['SysValue']['other']['productPageThis']==1) {
				echo '<meta name="description" content="@pageDesc@">';	
		}
	} else {
			echo '<meta name="description" content="@pageDesc@">';	
	}
	php@
	@php
	if ($GLOBALS['SysValue']['nav']['nav']=='CID') {
		if ($GLOBALS['SysValue']['other']['productPageThis']==1) {
				echo '<meta name="keywords" content="@pageKeyw@">';	
		}
	} else {
			echo '<meta name="keywords" content="@pageKeyw@">';	
	}
	php@
	<meta name="copyright" content="@pageReg@">
	<meta name="engine-copyright" content="PHPSHOP.RU, @pageProduct@">
	<meta name="domen-copyright" content="@pageDomen@">
	<meta content="General" name="rating">
	<meta name="ROBOTS" content="ALL">
	<meta name="viewport" content="width=device-width">
	<!--Подключаем JQUERY -->
	<script type="text/javascript" src="java/lib/jquery-1.11.0.min.js"></script>
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/lib/jquery-1.11.0.min.js"></script>-->
	<!-- Включаем линки для отложенной загруки скриптов -->
	<!-- <link rel="subresource" href="java/swfobject.js">-->
	<!-- <link rel="subresource" href="java/highslide/highslide-p.js">-->
	<script type="text/javascript" src="java/swfobject.js"></script>
	<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css">
	<script type="text/javascript" src="java/highslide/highslide-p.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/Slides-SlidesJS-3/source/jquery.slides.min.js"></script>
	<!-- Подключаем шаблон -->
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css" type="text/css">
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/style.css" type="text/css">	
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.pajinate.min.js"></script>
	<!-- Подключаем иконки -->
	<link rel="shortcut icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<link rel="icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<!-- Подключаем javascript библиотеку CMS -->
	<script type="text/javascript" src="java/phpshop.js"></script>
	<!-- Подключаем совместимость с браузерами -->
	<script type="text/javascript" src="java/tabpane.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></script>
	<!-- Подключаем AJAX -->
	<script type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
	<!-- Подключаем всплывающие подсказки -->
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css">
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css">
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<!-- Подключаем inputmask -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.inputmask-3.x/js/inputmask.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.inputmask-3.x/js/jquery.inputmask.js"></script>
	<!-- Подключаем browser detecting -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-browser-plugin-master/dist/jquery.browser.min.js"></script>	
	<!-- Подключаем анализ стоимости доставки edost -->
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.autocomplete.css" />
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery-1.2.6.pack.js"></script>-->
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.ajaxQueue.js"></script>-->
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.autocomplete.pack.js"></script>-->

	<script type="text/javascript" src="java/carhartl-jquery-cookie-3caf209/jquery.cookie.js"></script>
	<!-- Подключаем библиотеку слайдеры и iframe -->
	<!-- Fancybox -->
    <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.css">
    <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.pack.js"></script>
    <!-- /Fancybox -->
    <!-- Bootstrap -->
    	<link id="bootstrap_theme" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.css" rel="stylesheet">
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.js"></script>
	<!--/Bootstrap-->    
	<!-- Подключаем javascript|jquery шаблона -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/script.js"></script>

	<script type="text/javascript" src="java/ap.js"></script>	
	<script type="text/javascript" src="java/aap.js"></script>
	
	<!--интеграция с vsevkredit -->
	<script type="text/javascript">
	var VVC_SETTINGS = {
	        // shop_id - целое число. Идентификатор магазина в системе "Всё в кредит".
	        shop_id : @php
	        		$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
					//выводим id
					echo $SysValue['lang']['vsevkredit_id'];
	        php@,
	
	        // Номер заказа на стороне магазина; может быть строкой.
	        order_id : "@orderNum@",
	
	        // Фамилия и имя клиента (поле необязательно).
	        // Данные нужно указывать в порядке: ФАМИЛИЯ ИМЯ, а не наоборот.
	        name: "@UserName@",
	
	        // Номер сотового телефона клиента  (поле необязательно).
	        // Телефон указывает без 8 или +7, (принимаются только номера сотовых в коде страны «+7»).
	        // Телефон указывается в формате «1231234567».
	        phone:"@UserTel@",
	
	        // css - оформление кнопок
	        css: "green",
			// Обработчик событий виджета (поле необязательно).
	        onStateChange :
	            function (state) {
	                switch(state) {
	                    case 'opened':
	                        function v_opened(){
	                        	//console.log('opened');
	                        }
	                    break;
	                    case 'order':
	                        function v_opened(){
	                        	//console.log('order');
	                        }
	                    break;
	                    case 'closed':
	                        function v_opened(){
	                        	//console.log('closed');
	                        }
	                    break;
	                }
	            },
	        // По желанию вы можете добавить дополнительную кнопку внутри виджета для перехода
	        // (например на вашу страничку Вконтакте, или Личный кабинет покупателя на вашем сайте).
	        // (Поле необязательно.)
	        final_link: {
	            // название кнопки
	            text:'Личный кабинет',
	            // ссылка для перехода
	            uri:'http://prodacha.ru/'
	        }
	}
	</script>
	<!--с 18.11.2014 подключен vsevcredit.ru-->
    <script type="text/javascript" src="http://vsevcredit.ru/js/widget1251.js"></script>

	<script type="text/javascript" src="java/async_js_loader.js"></script>
		

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

	<!-- Universal Analytics counter -->
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-29201270-1', 'auto');
	  ga('require', 'displayfeatures');     
	  ga('send', 'pageview');
	
	</script>
		
</head>

<body onload="pressbutt_load('@thisCat@','@pathTemplate@','false','false');NavActive('@NavActive@');LoadPath('@ShopDir@');">

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
			<div id="reverse_call_href" class="catalog page_nava" onclick="ask_reverse_call(document.getElementsByClassName('netref'))">Заказать звонок</div>
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
		<div class="chart" onclick="window.location.replace('/order/');">
			<span class="charttitle">Корзина</span>
			<span class="prods">Товаров:</span><span class="red"><span id="num">@num@</span>&nbsp;шт.</span>
			<span class="summ">Сумма:</span><span class="red"><span id="sum">@sum@</span>&nbsp;@productValutaName@</span>
            <span class="charttitle2" id="order" style="display:@orderEnabled@; "><a href="/order/" >Оформить заказ</a></span>
		</div>
	</div>
	<div id="main">
		<div class="left">
			<div class="search">
					<!--
				   <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
					<input  class="keyword" type="text" name="words"  value="Я ищу..." onfocus="if(this.value=='Я ищу...'){this.value='';}" onblur="if(this.value==''){this.value='Я ищу...';}">
					<input name="s" value="" class="input-search__button input-search__button--enabled" type="submit">
				</form>
				-->
				<form action="/search/" role="search" method="post">
                                <div class="input-group">
                                    <input name="words" maxlength="50" id="search1" class="form-control" placeholder="Умный поиск" required type="text" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default form-control" type="submit" style="border-radius: 4px;"><span class="glyphicon glyphicon-search"></span></button>
                                    </span>
                                </div>
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
			<div class="ask_question_div">
				<div class="h2">Задать вопрос</div>

				<form id="ask_question_form" action="http://prodacha.ru/snip_call.php" method="POST">
					<input class="text_ask_question_email" type="text" name="email" value="Ваш E-mail" onfocus="if(this.value=='Ваш E-mail'){this.value='';}" onblur="if(this.value==''){this.value='Ваш E-mail';}">
					<textarea class="textarea_ask_question" name="textArea_ask_question"  rows="3" cols="30" value="Вопрос" onfocus="if(this.value=='Вопрос'){this.value='';}" onblur="if(this.value==''){this.value='Вопрос';}">Вопрос</textarea>
					<input id="cv1" type="hidden" name="cv" value="nohspamcode">
					<input type="submit" name="button_ask_question" value="Отправить" class="button_ask_question">
				</form>
			</div>	
		</div>
		<div class="copyright">
			<span class="copy">&copy; 2015 PROДАЧА - интернет-магазин садовой техники</span>
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
<div id="cartwindow">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="middle"> </td>
      <td><b>Внимание...</b><br />
        Товар добавлен в корзину</td>
    </tr>
  </table>
</div>
<div id="comparewindow">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="middle"></td>
      <td><b>Внимание...</b><br />
        Товар добавлен в сравнение</td>
    </tr>
  </table>
</div>
	<!-- RedHelper -->
	<script id="rhlpscrtg" type="text/javascript" charset="utf-8" src="https://web.redhelper.ru/service/main.js?c=prodacha"></script>
	<!--/Redhelper -->
</body>

</html>