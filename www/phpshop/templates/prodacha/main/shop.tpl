<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	@php
	if ($GLOBALS['SysValue']['nav']['nav']=='CID') {
		if ($GLOBALS['SysValue']['other']['productPageThis']=='ALL') {
			$cur_page_num=' - ��� ������';
				if (!empty($GLOBALS['SysValue']['other']['catalogName']) && empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogName'].$cur_page_num.' - PRO����</title>';
				} else if (!empty($GLOBALS['SysValue']['other']['catalogName']) && !empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogCategory'].$cur_page_num.' - PRO����</title>';
				} else {
					echo '<title>@pageTitl@</title>';	
				}			
		} else {
			if ($GLOBALS['SysValue']['other']['productPageThis']!=1) {
				$cur_page_num=' - �������� '.$GLOBALS['SysValue']['other']['productPageThis'];
				if (!empty($GLOBALS['SysValue']['other']['catalogName']) && empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogName'].$cur_page_num.' - PRO����</title>';
				} else if (!empty($GLOBALS['SysValue']['other']['catalogName']) && !empty($GLOBALS['SysValue']['other']['catalogCategory'])) {
					echo '<title>'.$GLOBALS['SysValue']['other']['catalogCategory'].$cur_page_num.' - PRO����</title>';
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
	<!--���������� JQUERY -->
	<script type="text/javascript" src="java/lib/jquery-1.11.0.min.js"></script>
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/lib/jquery-1.11.0.min.js"></script>-->
	<!-- �������� ����� ��� ���������� ������� �������� -->
	<!-- <link rel="subresource" href="java/swfobject.js">-->
	<!-- <link rel="subresource" href="java/highslide/highslide-p.js">-->
	<script type="text/javascript" src="java/swfobject.js"></script>
	<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css">
	<script type="text/javascript" src="java/highslide/highslide-p.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/Slides-SlidesJS-3/source/jquery.slides.min.js"></script>
	<!-- ���������� ������ -->
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css" type="text/css">
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@/style.css" type="text/css">	
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.pajinate.min.js"></script>
	<!-- ���������� ������ -->
	<link rel="shortcut icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<link rel="icon" href="http://www.prodacha.ru/UserFiles/Image/favicon.ico" type="image/x-icon">
	<!-- ���������� javascript ���������� CMS -->
	<script type="text/javascript" src="java/phpshop.js"></script>
	<!-- ���������� ������������� � ���������� -->
	<script type="text/javascript" src="java/tabpane.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js"></script>
	<!-- ���������� AJAX -->
	<script type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
	<!-- ���������� ����������� ��������� -->
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.structure.min.css">
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.theme.min.css">
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<!-- ���������� inputmask -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.inputmask-3.x/js/inputmask.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery.inputmask-3.x/js/jquery.inputmask.js"></script>
	<!-- ���������� browser detecting -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/jquery-browser-plugin-master/dist/jquery.browser.min.js"></script>	
	<!-- ���������� ������ ��������� �������� edost -->
	<link rel="stylesheet" type="text/css" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.autocomplete.css" />
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery-1.2.6.pack.js"></script>-->
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.ajaxQueue.js"></script>-->
	<!--<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/edost_example/js/jquery.autocomplete.pack.js"></script>-->

	<script type="text/javascript" src="java/carhartl-jquery-cookie-3caf209/jquery.cookie.js"></script>
	<!-- ���������� ���������� �������� � iframe -->
	<!-- Fancybox -->
    <link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.css">
    <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.pack.js"></script>
    <!-- /Fancybox -->
    <!-- Bootstrap -->
    	<link id="bootstrap_theme" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/bootstrap.css" rel="stylesheet">
        <script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@js/bootstrap.js"></script>
	<!--/Bootstrap-->    
	<!-- ���������� javascript|jquery ������� -->
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/script.js"></script>

	<script type="text/javascript" src="java/ap.js"></script>	
	<script type="text/javascript" src="java/aap.js"></script>
	
	<!--���������� � vsevkredit -->
	<script type="text/javascript">
	var VVC_SETTINGS = {
	        // shop_id - ����� �����. ������������� �������� � ������� "�� � ������".
	        shop_id : @php
	        		$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
					//������� id
					echo $SysValue['lang']['vsevkredit_id'];
	        php@,
	
	        // ����� ������ �� ������� ��������; ����� ���� �������.
	        order_id : "@orderNum@",
	
	        // ������� � ��� ������� (���� �������������).
	        // ������ ����� ��������� � �������: ������� ���, � �� ��������.
	        name: "@UserName@",
	
	        // ����� �������� �������� �������  (���� �������������).
	        // ������� ��������� ��� 8 ��� +7, (����������� ������ ������ ������� � ���� ������ �+7�).
	        // ������� ����������� � ������� �1231234567�.
	        phone:"@UserTel@",
	
	        // css - ���������� ������
	        css: "green",
			// ���������� ������� ������� (���� �������������).
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
	        // �� ������� �� ������ �������� �������������� ������ ������ ������� ��� ��������
	        // (�������� �� ���� ��������� ���������, ��� ������ ������� ���������� �� ����� �����).
	        // (���� �������������.)
	        final_link: {
	            // �������� ������
	            text:'������ �������',
	            // ������ ��� ��������
	            uri:'http://prodacha.ru/'
	        }
	}
	</script>
	<!--� 18.11.2014 ��������� vsevcredit.ru-->
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
		var yaParams = {/*����� ��������� ������*/};
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
			<div id="reverse_call_href" class="catalog page_nava" onclick="ask_reverse_call(document.getElementsByClassName('netref'))">�������� ������</div>
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
			<span class="charttitle">�������</span>
			<span class="prods">�������:</span><span class="red"><span id="num">@num@</span>&nbsp;��.</span>
			<span class="summ">�����:</span><span class="red"><span id="sum">@sum@</span>&nbsp;@productValutaName@</span>
            <span class="charttitle2" id="order" style="display:@orderEnabled@; "><a href="/order/" >�������� �����</a></span>
		</div>
	</div>
	<div id="main">
		<div class="left">
			<div class="search">
					<!--
				   <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
					<input  class="keyword" type="text" name="words"  value="� ���..." onfocus="if(this.value=='� ���...'){this.value='';}" onblur="if(this.value==''){this.value='� ���...';}">
					<input name="s" value="" class="input-search__button input-search__button--enabled" type="submit">
				</form>
				-->
				<form action="/search/" role="search" method="post">
                                <div class="input-group">
                                    <input name="words" maxlength="50" id="search1" class="form-control" placeholder="����� �����" required type="text" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
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
				hs.registerOverlay({html: '<div class="closebutton" onclick="return hs.close(this)" title="�������"></div>',position: 'top right',fade: 2});
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
				<div class="h2">��������</div>
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
				<div class="h2">������ ������</div>				
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
				<div class="h2">�� �� �����</div>
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
				<div class="h2">������ ������</div>

				<form id="ask_question_form" action="http://prodacha.ru/snip_call.php" method="POST">
					<input class="text_ask_question_email" type="text" name="email" value="��� E-mail" onfocus="if(this.value=='��� E-mail'){this.value='';}" onblur="if(this.value==''){this.value='��� E-mail';}">
					<textarea class="textarea_ask_question" name="textArea_ask_question"  rows="3" cols="30" value="������" onfocus="if(this.value=='������'){this.value='';}" onblur="if(this.value==''){this.value='������';}">������</textarea>
					<input id="cv1" type="hidden" name="cv" value="nohspamcode">
					<input type="submit" name="button_ask_question" value="���������" class="button_ask_question">
				</form>
			</div>	
		</div>
		<div class="copyright">
			<span class="copy">&copy; 2015 PRO���� - ��������-������� ������� �������</span>
			<div class="foot_menu">
				<ul>
					@topMenu@
					<li><a href="http://prodacha.ru/map/">����� �����</a></li>
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
      <td><b>��������...</b><br />
        ����� �������� � �������</td>
    </tr>
  </table>
</div>
<div id="comparewindow">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="middle"></td>
      <td><b>��������...</b><br />
        ����� �������� � ���������</td>
    </tr>
  </table>
</div>
	<!-- RedHelper -->
	<script id="rhlpscrtg" type="text/javascript" charset="utf-8" src="https://web.redhelper.ru/service/main.js?c=prodacha"></script>
	<!--/Redhelper -->
</body>

</html>