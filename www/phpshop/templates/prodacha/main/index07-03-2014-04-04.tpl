<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>@pageTitl@</title>
	<meta name="ktoprodvinul" content="c95d57d3680b0500" />
	<meta name="cmsmagazine" content="c45d4085c1835d45ae4ee5d8a8b6e1f9" />
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
	<script type="text/javascript" src="java/phpshop.js" defer></script>
	<script type="text/javascript" src="phpshop/lib/Subsys/JsHttpRequest/Js.js"></script>
	<script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/js.js" defer></script>
	<script type="text/javascript" src="java/swfobject.js" defer></script>
	<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/style.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="java/highslide/highslide.css">
	<script src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/script.js?v1" type="text/javascript" defer></script>

	<script type="text/javascript" src="java/highslide/highslide-p.js" defer></script>
	<script type="text/javascript" src="java/ap.js" defer></script>
	 
	 	<!-- credit -->
	<script src="http://yes-credit.su/crem/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript" defer></script>
    <script src="http://yes-credit.su/crem/js/crem.js" type="text/javascript" defer></script>
    <link href="http://yes-credit.su/crem/css/blizter.css" rel="stylesheet" type="text/css"/>
	<!--/ credit -->
	 
	 <!-- Fancybox -->
    <script type="text/javascript" src="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.pack.js" defer></script>
    <link href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); php@javascript/fancybox/jquery.fancybox.css" rel="stylesheet">
    <!-- /Fancybox -->
	 
	 <script language="javascript">
			<!--
			function SendForm()
			{		
				if   ( document.getElementById('email').value.search( /^[w.-_]+@[w-_]+.[w.-_]+$/ ) == -1 ) 
				{
					
					alert('����������, ��������� ���� "�������� ����" ���������!');
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
		
	 
	<script type="text/javascript" src="java/aap.js" defer></script>
	 <!--
	 @php 
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
	$ST = new Seotools; 

	// ����� �����, ��� 
	// $variable = �������� ���������� (�������). 
	// $default_value = �������� �� ��������� 
	echo $ST->get("h1", $default_value); 
	 php@
	-->
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
<body onload="default_load('false','false');NavActive('index');LoadPath('@ShopDir@');" >

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
					<div class="logo"><a href="/"><img src="images/logo.png" alt="LOGO" border="0"></a></div>
		<div class="phone">
			
			<!--<span class="name" style="padding-top: 8px;">��������-�������. �������:<span style="font-weight: bold;"> -->
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
			<span class="charttitle"> �������</span>
			<span class="prods">�������:</span><span class="red"><span id="num">@num@</span>���.</span>
			<span class="summ">�����:</span><span class="red"><span id="sum">@sum@</span>�@productValutaName@</span>
            <span class="charttitle2" id="order" style="display:@orderEnabled@; "><a href="/order/" >�������� �����</a></span>
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

	<div id="main">
		<div class="left">
			<div class="search">
				   <form method="post" name="forma_search" action="/search/" onsubmit="return SearchChek()">
					<input  class="keyword" type="text" name="words"  value="� ���..." onfocus="if(this.value=='� ���...'){this.value='';}" onblur="if(this.value==''){this.value='� ���...';}">
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
					<div>���������� ������</div>
					<span class="rightcorner"></span>
				</div>
				
				<div class="wrapper">
                <div class="tovar1" >@specMainIcon@</div>
				</div>
				
			</div>
			<!-- Popular end -->
			
			<div class="special">
				<div class="title">
					<div onclick="window.location.replace('/spec/');" style="cursor:pointer;">���������������</div><a href="/spec/">��� ���������������</a>
					<span class="rightcorner"></span>
				</div>
				<div class="wrapper">
                                   <div class="tovar1" >@specMain@</div>             
				
				</div>
			</div>
			<!-- Special end  -->
			
			<div class="bought">
				<div class="title">
					<div>������ ��� ������</div>
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
			<div class="form">
				<div class="h2">������ ������</div>
				<form action="http://prodacha.ru/snip_call.php" method="POST" onsubmit="return SendForm()" >
					<input class="text" type="text" name="email" id="email" value="��� E-mail" onfocus="if(this.value=='��� E-mail'){this.value='';}" onblur="if(this.value==''){this.value='��� E-mail';}">
					<textarea class="textarea" name="textArea"  rows="3" cols="30" value="������" onfocus="if(this.value=='������'){this.value='';}" onblur="if(this.value==''){this.value='������';}">������</textarea>
				
				<input id="cv1" type="hidden" name="cv" value="null" />
				<script type="text/javascript">// <![CDATA[
					m=document.getElementById("cv1");
					m.value="nohspamcode";
				// ]]></script>

					<input type="submit" value="���������" class="button">
				</form>
			</div>	
		</div>
		<div class="copyright">
			<span class="copy">&copy; PRO���� - ��������-������� ������� �������</span>
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
<!-- 

-->
<div id="cartwindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_commercemanager_med.gif" alt="" width="32" height="32" border="0" align="middle"> </td>
      <td><b>��������...</b><br />
        ����� �������� � �������</td>
    </tr> 
  </table>
</div>
<div id="comparewindow" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
  <table width="100%" >
    <tr>
      <td width="40" ><img src="images/shop/i_compare_med.gif" alt="" width="32" height="32" border="0" align="middle"></td>
      <td><b>��������...</b><br />
        ����� �������� � ���������</td>
    </tr>
  </table>
</div>
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
    </script> -->
