<div class="pagetitle">

	<h1>@php
	require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
	$ST = new Seotools; 

	// ����� �����, ��� 
	// $variable = �������� ���������� (�������). 
	// $default_value = �������� �� ��������� 

	$oldh1="@productName@";

	echo $ST->get("h1", $oldh1); 

	php@</h1>

</div>

<div style="clear: both;"></div>

<div class="productbox">

	<div class="prod_show_box">	

		@php
			require_once ($_SERVER['DOCUMENT_ROOT'] . '/product_icons.php');
		php@

		<div id="fotoload" align="center" >@productFotoList@</div>

	</div>
	
	<div class="prod_parm_col">
	
		<div class="articul">
			<span>�������: <span class="prod_articul">@productArt@</span></span>
		</div>
		
		<div class="parameters">
			@vendorDisp@
			
			<div class="addchart_block">
			
				<table class="addchart_block_table">
				
					<tr>
						
						<td>&nbsp;
						
						</td>
						
						<td>
							<span class="quantity" style="display:none">���-��: <input class="quantity" id="n@productUid@" type="num" maxlength="5" size="3" value="1" name="n@productUid@" style="display:none" /></span>
						</td>
						
					</tr>
					
					<tr>
						<td>
							<div class="addchart_block_table_price">
									<span class="price margino">
										<span style="font-size: 22px; text-transform: uppercase;">����</span>
										<div>@productPrice@ <span class="smallfont">@productValutaName@.</span></div>
									</span>
									<span class="prev_price @collaboration@"><noindex>@productPriceRub@</noindex></span>
									<span class="price_comlain" ><noindex>����� �������?</noindex</span>
							</div>
						</td>
						
						<td>
							<div class="addchart_block_table_addtochart">
	
	@php

	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);

	$host=$SysValue['connect']['host'];
	$user_db=$SysValue['connect']['user_db'];
	$pass_db=$SysValue['connect']['pass_db'];
	$dbase=$SysValue['connect']['dbase'];
	
	$dbcnx=mysql_connect($host,$user_db,$pass_db);
	
	if (!$dbcnx)
	{
		echo("<p> error MySql </p>");
		exit;
	}
	
	if (!@mysql_select_db($dbase,$dbcnx))
	{
		echo("<p> error DB MySql </p>");
		exit;
	}

	//�� ������ ����� ���� ��������
	$url=curPageURL();

	$s1=strpos($url,"UID_")+4;
	
	$s3=strpos($url,"_",$s1);
		
	if ($s3!==false)
		$s2=$s3;
	else
		$s2=strpos($url,".html");
	
	$len=$s2-$s1;
	
	$id=substr($url,$s1,$len);
		
	$us_ = $id;

    //��� �������� stihl ������������ ������ ��������

    $stihl_catalog_search=false;
	$viking_catalog_search=false;
	$row_sklad=false;
	$sql="select id,name,sklad from ".$SysValue['base']['products']." where category in (select id from ".$SysValue['base']['categories']." where name like '%stihl%' or name like '%viking%') and id=".$us_;

	$res=mysql_query($sql);
    while ($catalog_id_rows=mysql_fetch_row($res)) {
		if ($us_==$catalog_id_rows[0]) {
			if (preg_match("/stihl/i", $catalog_id_rows[1])) {
				$stihl_catalog_search=true;
			}
			if (preg_match("/viking/i", $catalog_id_rows[1])) {
				$viking_catalog_search=true;
			}
			if ($catalog_id_rows[2]==1) {
				$row_sklad=true;
			}			
		}          	 
    }

    if ($stihl_catalog_search==true || $viking_catalog_search==true) {
		if (isset($_COOKIE['sincity'])) {
			$region_info=$_COOKIE['sincity'];
		} else {
			$region_info='m';
		}
		echo '@ComStartNotice@';		
		if ($row_sklad==true) {
			//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}

		} else {
			if ($viking_catalog_search==true) {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="viking_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';		
			} else {
				echo '<span class="addtochart notice"> <input type="button" style="font-size:9px;" onclick="stihl_window(\\''.$region_info.'\\')"  value="'.$SysValue['lang']['stihl_string'].'" /> </span>';
			}
		}
		echo '@ComEndNotice@';	
		//echo '@ComStartCart@';
		//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCartNum(@productUid@,\\'n@productUid@\\')" value="@productSale@" /></span>';
		//echo '@ComEndCart@';
		//echo '@FastOrder@';							
		//echo '<!--@firstcreditpunch@-->';

	} else {
		$row_sklad=false;
		$price_n=false;
		$sql="select sklad,price_n from ".$SysValue['base']['products']." where id=".$GLOBALS['SysValue']['other']['productId'];
		$res=mysql_query($sql);
		while ($catalog_id_rows=mysql_fetch_row($res)) {
			if ($catalog_id_rows[0]==1) {
				$row_sklad=true;
			}
			if ($catalog_id_rows[1]>0) {
				$price_n=true;
			}
		}
		if ($row_sklad==true && $price_n=true) {
			echo '@ComStartNotice@';
			echo '<span class="addtochart notice">';
			echo '<input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');"  value="'.$SysValue['lang']['product_notice'].'">';
			echo '</span>';
			echo '@ComEndNotice@';	
		} else if ($row_sklad==false && $price_n=true) {
			echo '@ComStartCart@';
			echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@,\\'n@productUid@\\')"  value="'.$SysValue['lang']['productSale'].'"></span>';
			echo '@ComEndCart@';		
		} else {
			echo '@ComStartCart@';
			echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCart(@productUid@,\\'n@productUid@\\')"  value="'.$SysValue['lang']['productSale'].'"></span>';
			echo '@ComEndCart@';	
		}
	}
	
		//echo '@ComStartCart@';
		//echo '<span class="addtochart"><input type="button" onclick="javascript:AddToCartNum(@productUid@,\\'n@productUid@\\')" value="@productSale@" /></span>';
		//echo '@ComEndCart@';
		//echo '@FastOrder@';							
		//echo '<!--@firstcreditpunch@-->';
		//echo '@ComStartNotice@';
       	//echo '<span class="addtochart notice"> <input type="button" onclick="window.location.replace(\\'/users/notice.html?productId=@productUid@\\');" value="'.$SysValue['lang']['product_notice'].'" /> </span>';//@productNotice@
		//echo '@ComEndNotice@';
	//}
	
	function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	php@
							</div>
						</td>
					</tr>
					
					<tr>
						<td colspan="2"></td>
					</tr>
				
				</table>

				@ComStart@
				<div class="tovar_optionsDisp">@optionsDisp@</div>
				@ComEnd@
				
				@productParentList@
			
			</div><!-- /.addchart_block -->
		
		</div><!-- /.parameters -->
				
	</div><!-- /.prod_parm_col -->

</div><!-- /.productbox -->

<div class="tabs">
	
	<ul class="tabNavigation">
		<li><a href="#tab1">��������</a></li>
		<li><a href="#tab6">�������� � ������</a></li>
		<li><a href="#tab7">�������� � ���������</a></li>		
		<!--<li><a href="#tab2">�����</a></li>-->
		<li><a href="#tab3">�������</a></li>
		<li><a href="#tab4">������</a></li>
		<li><a href="#tab5">������</a></li>
	</ul>
	
	<div id="tab1">@productDes@</div>
	
	<!--<div id="tab2">@productFiles@</div>-->
	
	<div id="tab3">@ratingfull@</div>
	
	<div id="tab4">
		<div id="bg_catalog_1" style="margin-top:10px">����������� �������������</div>
		
		<textarea id="message" style="width: 340px" rows="5" onkeyup="return countSymb();"></textarea>
		
		<div style="font-size: 10px; margin-bottom: 5px">������������ ���������� ��������: <span id="count" style="width: 30px; color: green; text-aling: center">0</span>/&nbsp;&nbsp;&nbsp;500 </div>
		<div style="padding: 5px"> <img onmouseover="this.style.cursor='hand';" title="�������" onclick="emoticon(':-D');" alt="�������" src="images/smiley/grin.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':)');" alt="���������" src="images/smiley/smile3.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':(');" alt="��������" src="images/smiley/sad.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="� ����" onclick="emoticon(':shock:');" alt="� ����" src="images/smiley/shok.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="�������������" onclick="emoticon(':cool:');" alt="�������������" src="images/smiley/cool.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����������" onclick="emoticon(':blush:');" alt="����������" src="images/smiley/blush2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="�������" onclick="emoticon(':dance:');" alt="�������" src="images/smiley/dance.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':rad:');" alt="��������" src="images/smiley/happy.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��� ������" onclick="emoticon(':lol:');" alt="��� ������" src="images/smiley/lol.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="� ��������������" onclick="emoticon(':huh:');" alt="� ��������������" src="images/smiley/huh.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����������" onclick="emoticon(':rolly:');" alt="����������" src="images/smiley/rolleyes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����" onclick="emoticon(':thuf:');" alt="����" src="images/smiley/threaten.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������� ����" onclick="emoticon(':tongue:');" alt="���������� ����" src="images/smiley/tongue.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':smart:');" alt="��������" src="images/smiley/umnik2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':wacko:');" alt="���������" src="images/smiley/wacko.gif" border="0" /> <img onmouseover="this.style.cursor='hand';" title="�����������" onclick="emoticon(':yes:');" alt="�����������" src="images/smiley/yes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':yahoo:');" alt="���������" src="images/smiley/yu.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':sorry:');" alt="��������" src="images/smiley/sorry.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��� ���" onclick="emoticon(':nono:');" alt="��� ���" src="images/smiley/nono.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="������ �� ������" onclick="emoticon(':dash:');" alt="������ �� ������" src="images/smiley/dash.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="������������" onclick="emoticon(':dry:');" alt="������������" src="images/smiley/dry.gif" border="0"> </div>
		
		<div style="padding:5px" id="commentButtonAdd">
			<input type="button"  value="�������� �����������" onclick="commentList('@productUid@','add',1)" />
		</div>
		
		<div id="commentButtonEdit" style="padding:5px; visibility:hidden; display:none">
			<input type="button"  value="�������� �����������" onclick="commentList('@productUid@','add',1)" />
			<input type="button"  value="������� �����������" onclick="commentList('@productUid@','edit_add','1')" />
			<input type="button"  value="�������" onclick="commentList('@productUid@','dell','1')" />
			<input type="hidden" id="commentEditId" />
		</div>
		
		<div id="commentList"> </div>
		
		<script>
			setTimeout("commentList('@productUid@','list')",500);
        </script>
		
	</div>
	
	<div id="tab5">@pagetemaDisp@</div>
	
	<div id="tab6">@warrantyInfo@</div>

	<div id="tab7">@deliveryInfo@</div>
		
	<img src="../images/spacer.gif" width="783" height="1" alt="" />

</div>

<div id="light_box1"></div>
  
<div id="complainlayer-container" class="simplemodal-container">

	<a href="javascript:void(0)" title="�������" class="modal-close simplemodal-close"><img src="../images/close.png" alt="" title="�������" style="border:0;"></a>
	
	<div tabindex="-1" class="simplemodal-wrap" style="height: 100%; outline: 0px; width: 100%; overflow: visible;">
		<div id="complainlayer" class="simplemodal-data">
		
			<div class="header"></div>
				
			<div class="message">
				
				<form id="complainForm" action="http://prodacha.ru/cheap_sbm.php" method="post">
					
					<input type="hidden" name="product" value="@productUid@">
					
					<table cellpadding="0" cellspacing="0" border="0" width="330">
						<tbody>
						
							<tr>
								<td colspan="2" style="padding-bottom:5px; font-size:14px;">����������, ��������� �����, ����� �� ����� ���������� ��� ������ �������</td>
							</tr>
							
							<tr>
								<td>���� ���:</td>
								<td class="aright"><input type="text" name="uname" value="" class="field"></td>
							</tr>
							
							<tr>
								<td>��� e-mail:</td>
								<td class="aright"><input type="text" name="uemail" value="" class="field"></td>
							</tr>
							
							<tr>
								<td>��� �������:</td>
								<td class="aright"><input type="text" name="uphone" value="" class="field"></td>
							</tr>
							
							<tr>
								<td>�������� ��������:<br><span style="font-size:10px;">��� ���� ����� �������</span></td>
								<td class="aright"><input type="text" name="sname" value="" class="field"></td>
							</tr>
							
							<tr>
								<td>������ �� �����:</td>
								<td class="aright"><input type="text" name="surl" value="" class="field"></td>
							</tr>
							
							<tr>
								<td>���������:</td>
								<td class="aright"><input type="text" name="sprice" value="" class="field"></td>
							</tr>
							
							<tr>
								<td colspan="2" style="text-align:center;"><input type="submit" class="submit" value="ok"></td>
							</tr>
							
						</tbody>
					</table>
					
					<input id="cv2" type="hidden" name="cv2" value="null" />
					
				</form>
				
				<script type="text/javascript">// <![CDATA[
					m=document.getElementById("cv2");
					m.value="nohspamcode";
				// ]]></script>		
			</div>

		</div>  
	</div>  
</div>
 
<script type="text/javascript">
	$('.addchart_block_table_price > .price_comlain').click( function( event ) {
		$('#light_box1, #complainlayer-container').show();
		return false;
	});
	
	$('#complainlayer-container .modal-close').click( function( event ) {
		$('#complainlayer-container, #light_box1').hide();
		return false;
	});
</script>