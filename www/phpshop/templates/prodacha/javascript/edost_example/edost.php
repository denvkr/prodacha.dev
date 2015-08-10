<?
/*
== ���������� POST ������� ========================================

�� ���������� ��������� � POST ������� ������������ ������ ��������.
��������� ��������� � ���� ������� (���������: �������� �������� (�������� ������) - ���������� ���� �������� - ���� �������� � ������).

��������� ��������� � POST �������:
1. edost_to_city - ����� ��� ������, ���� ��������� ��������� ������� (�������� �� ������� ����� � ��������� windows-1251)
2. edost_weight - ��� � ��
3. edost_strah - ����� ��� ��������� � ������
4. edost_length - ����� ������� (����� �� ���������)
5. edost_width - ������ ������� (����� �� ���������)
6. edost_height - ������ ������� (����� �� ���������)
7. edost_zip - �������� ������ (����� �� ���������)

===================================================================
*/


//== ��������� ����� ������ =======================================
//	ini_set("display_errors","0");
//	ini_set("display_startup_errors","0");
$image_path=curPageURL()."/phpshop/templates/prodacha/javascript/edost_example/";

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"];
	}
	return $pageURL;
}

$strJavaScriptPickPoint  = '<script type="text/javascript" src="http://www.pickpoint.ru/select/postamat.js"></script>';

$strJavaScript  = <<<EOT
<script language=javascript>

function EdostPickPoint(rz) {
	document.getElementById("edost_select_pickpoint").innerHTML='<br>������ ��������: '+rz['name']+', '+rz['id']+', '+rz['address'];
}

function edost_deliveryclick() {
	var arr = new Array();
	arr = document.getElementsByName("edost_delivery");

	for (var i = 0; i < arr.length; i++) {
		var obj = document.getElementsByName("edost_delivery").item(i);

		if (obj.checked == true) {
			//obj.value;
			var rez = obj.value.split('|');
			var s='';
			if ( ( rez[4] != -1 )) s='<br> ��������� �������� ��� ���������� �������: '+rez[4]+' ���., ������� ��� ���������: '+rez[5]+' ���.';
			document.getElementById("edost_select_delivery").innerHTML='������ �����: '+rez[0]+' ('+rez[1]+'), ��� ������: '+obj.id+', ���-�� ����: '+rez[2]+', ����: '+rez[3]+' ���.'+s;
			document.getElementById("edost_select_pickpoint").innerHTML='';
			break;
		}
	}
}

</script>
EOT;

require("edost_class.php"); //����������� ������ edost


//== ������ �������� ==============================================
	$edost_calc = new edost_class (); //�������� ������ edost

	$edost_calc -> SetSiteWin(); //����� ���������� � ��������� win
	$r = $edost_calc -> edost_calc_post(); //����� ������� �������� �� ���������� ��������� � POST �������

	//echo "<br><pre>".print_r($r, true)."</pre>";

	$st = '';
	$flPickPoint=false;

//	��������� ��������� � ������ r:
//	1. r['stat'] - ��� ���������� �������
//	2. r['qty_company'] - ���������� �������
//		������ �� ������� ������ (N):
//		3. r['id'.N] - ��� ������
//		4. r['price'.N] - ���� �������� � ������
//		5. r['day'.N] - ���������� ���� ��������
//		6. r['starh'.N] - ��� ����������� (1 - ���������� �� ����������, 0 - ���������� ��� ���������)
//		7. r['company'.N] - �������� ��������
//		8. r['name'.N] - �������� ������

//== ����� ����������� ============================================

	//echo "<br><pre>".print_r($r['warning'], true)."</pre>";

	$warning_name = array(
		1 => '��������� ��������� � ��������� �������� �� ����������!',
		2 => '� ����� ������� ��� ��������� ��������� � ��������� ��������!',
		);


	if ( isset($r['warning']) && count($r['warning'])>0 ) {
		foreach($r['warning'] as $n)
			$st .= "<b>��������������:</b> {$warning_name[$n]}<br><br>";
	}

	if ( $r['qty_company']==0 ) {
		switch($r['stat']) {
			// ���� ������ �� �������� ������� �� ������ edost
			case 2:		$st = "������ � ������� ������������"; break;
			case 3:		$st = "�� ������ ������ �������� (������ ��� �������������)"; break;
			case 4:		$st = "�� ������ ������� ���������"; break;
			case 5:		$st = "�� ������ ����� ��� ������"; break;
			case 6:		$st = "���������� ������ ������� ��������"; break;
			case 7:		$st = "�� ������ �������� �������� � ���������� ��������"; break;
			case 8:		$st = "������ ������� �� ��������"; break;
			case 9:		$st = "�������� ����� �������� �� ����"; break;
			case 11:	$st = '<div id="edost_err_text" align="center" style="position:relative;font-size:16px;top:-100px;">��������� ���������� � ���������� �� ��������.</div>'; break; //$st = "�� ������ ���"; break;
			case 12:	$st = "�� ������ ������ �������� (������ ��� �������������)"; break;
			case 14:	$st = "��������� ������� �� ��������� ��������� ������ �� ������"; break;
			case 15:	$st = "�� ������ ����� ��������"; break;
			case 16:	$st = "��� �������� ���� �� ������������ ����������� ��������� ������ ��������"; break;
			// ���� ������ �� ������ edost_class
			case 10:	$st = "�� ������ ������ XML"; break;
			default:	$st = "� ������ ����� �������������� ������ �������� �� ��������������";
		};
	}
	else {

/*
		$st =
// �������
	'<table align="center" width="700" cellpadding="0" cellspacing="0" border="1" bordercolor="#D0D0D0">
		<tr height="15">
			<td width="30%" align="center">������ ��������</td>
			<td width="30%" align="center">��� �����������</td>
			<td width="15%" align="center">���� ��������</td>
			<td align="center">���������</td>
		</tr>';

		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['name'.$i]=='') $q='&nbsp;'; else $q=$r['name'.$i];
				$st = $st.
		'<tr height="15">'.
			'<td align="center">'.$r['company'.$i].'</td>'.
			'<td width="20%" align="center">'.$q.'</td>'.
			'<td align="center">'.$r['day'.$i].'</td>'.
			'<td align="center">'.$r['price'.$i].'</td>'.
// ��������� �������� � ��������� ��������� �������� ��� ���������� �������
//			'<td align="center">'.$r['price'.$i].' ('.$r['pricecash'.$i].') - ('.$r['transfer'.$i].')</td>'.
		'</tr>';
			}

		$st = $st.
	'</table>';

		$st = $st.'<br><br><br>';
*/

	// ������� � �������
		$st .=
		'<table align="left" width="700px" cellpadding="0" cellspacing="0" border="1" bordercolor="#D0D0D0" style="position:relative;top:-100px">
		<tr height="15"><td>
			<table align="center" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0"><tr>
				<td width="25px"></td>
				<td width="70px"></td>
				<td width="35%">������ ��������</td>
				<td width="21%" align="center">��� �����������</td>
				<td width="15%" align="center">���� ��������</td>
				<td width="15%"align="center">���������</td>
				<td width="20%" align="center">��������� �� ����������</td>				
			</tr></table>
		</td></tr>';

		$office = '';

		$r_sorted=array();
		$r_sorted['qty_company']=$r['qty_company'];
		$r_sorted['stat']=$r['stat'];
		$r_sorted['warning']=$r['warning'];
		$cnt=1;
		$c2=1;		
		$c22=1;
		$c16=1;
		$c14=1;

		//���������� ��������� ������������
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if($r['strah'.$i]==1) $strah==1;			
				break;				
		}		
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==22) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c22;
				$c22++;				
				$cnt++;				
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==51) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c22;
				$c22++;					
				$cnt++;
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==16) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c16;
				$c16++;
				$cnt++;				
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==49) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c16;
				$c16++;				
				$cnt++;	
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==14) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c14;
				$c14++;
				$cnt++;
				
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]='�� ��������� �� ����������';
				$r_sorted['strah'.$cnt]=1;
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c14;
				$c14++;
				$cnt++;							
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==48) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c14;
				$c14++;
				$cnt++;
				
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]='�� �������� �� ����������';
				$r_sorted['strah'.$cnt]=1;
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c14;
				$c14++;
				$cnt++;				
			}
		}
		for ($i=1; $i<=$r['qty_company']; $i++) {
			if ($r['id'.$i]==2) {
				$r_sorted['price'.$cnt]=$r['price'.$i];
				$r_sorted['day'.$cnt]=$r['day'.$i];				
				$r_sorted['company'.$cnt]=$r['company'.$i];
				$r_sorted['name'.$cnt]=$r['name'.$i];
				$r_sorted['strah'.$cnt]=$r['strah'.$i];
				$r_sorted['id'.$cnt]=$r['id'.$i];
				$r_sorted['pricecash'.$cnt]=$r['pricecash'.$i];
				$r_sorted['transfer'.$cnt]=$r['transfer'.$i];
				$r_sorted['c'.$cnt]=$c2;
				$c2++;
				$cnt++;
			}
		}
		//print_r($r);		
		$r=array();
		$r=$r_sorted;
		//print_r($r_sorted);
		//������� ��� ����� �������
		$width1='140px';
		$width2='105px';
		$width3='90px';
		$width4='70px';
		$width5='70px';						
		for ($i=1; $i<=$r['qty_company']; $i++) {
			$st_sub='';
			if ($r['name'.$i]=='') $q=''; else $q=' ('.$r['name'.$i].')';

			if ( isset($r['office'.$i]) && isset($r['office'.$i][0]['name']) ) {
				$office .= '<table align="center" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0">
				<tr><td style="padding-top: 10px;"><b>'.$r['company'.$i].'</b>:<td></tr>';
				//echo "<br><pre>".print_r($r['office'.$i], true)."</pre>";
				for ($h = 0; $h < count($r['office'.$i]); $h++) {
					//echo '<br>'.$i.' = '.$r['office'.$i][$h]['name'];

					if ( isset($r['office'.$i][$h]['name']) ) {

						$office .= '<tr><td style="padding-top: 10px;">����� ������ '.'<b>'.$r['office'.$i][$h]['name'].'</b>';

						if (isset($r['office'.$i][$h]['id']))
							$office .= ' (<a href="http://www.edost.ru/office.php?c='.$r['office'.$i][$h]['id'].'" target="_blank" style="cursor: pointer; text-decoration: none;" >�������� �� �����</a>)';

                	    $office .= '<br>';

						if (isset($r['office'.$i][$h]['code'])) $office .= '���: '.$r['office'.$i][$h]['code'].', ';
						if (isset($r['office'.$i][$h]['address'])) $office .= '�����: '.$r['office'.$i][$h]['address'].', <br>';
						if (isset($r['office'.$i][$h]['tel'])) $office .= '�������: '.$r['office'.$i][$h]['tel'].', ';
						if (isset($r['office'.$i][$h]['schedule'])) $office .= '����: '.$r['office'.$i][$h]['schedule'].' ';
						$office .= '<td></tr>';
					}

				}
				$office .= '</table>';
			}

				if ($r['id'.$i] == 29) {
					$flPickPoint=true;
					$refPickPoint = '<br><a style="font-family: Arial; font-size: 10pt; color: rgb(222, 0, 0); text-decoration: none;" href="#" id="EdostPickPointRef1" onclick="PickPoint.open(EdostPickPoint, {city:\''.$r['pickpointmap'.$i].'\', ids:null});">������� �������� ��� ����� ������</a>';
				}
				else {
					$refPickPoint = '';
				}

				$for_label = $r['id'.$i].'-'.$r['strah'.$i];
				if ($r['id'.$i]==2){
					if ($r['c'.$i]==1 && $r['c'.($i+1)]==2 && $r['c'.($i+2)]!=3) {
						if ($r['strah'.($i+1)]==1) {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.($i+1)].' ���.</b></p></td></tr>';
						} else {
							$st_sub='<td width="'.$width1.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'</td>'.
									'<td width="'.$width2.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'</td>'.
									'<td width="'.$width3.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b></p></td>'.
									'<td width="'.$width4.'" align="left"></td></tr>';
						}
						
						$st .=
						'<tr height="40px"><td>
						<table align="center" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0"><tr>'.
						'<td height="40px" width="5px" align="center"></td>'.
						'<td width="70px"><label for="'.$for_label.'"><img src="'.$image_path.'delivery_img/'.$r['id'.$i].'.gif" width="60px" height="32px" border="0"></label> </td>'.
						'<td width="'.$width1.'">'.$r['company'.$i].$refPickPoint.'</td>'.
						$st_sub.
						'</table></td></tr>';
					}
				}
				if ($r['id'.$i]!=2) {
					//echo $r['c'.$i].' '.$r['c'.($i+1)].' '.$r['c'.($i+2)].'<br>';
					if ($r['c'.$i]==1 && $r['c'.($i+1)]==2 && $r['c'.($i+2)]!=3) {
						//echo '2<br>';	
						if ($r['strah'.($i+1)]==1) {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.($i+1)].' ���.</b></p></td></tr>';
						} else {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"></td></tr>';
						}											
							//$st_sub='<td width="20%" align="center">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'</td>'.
							//'<td width="15%" align="center">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'</td>'.
							//'<td align="center"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b></p></td></tr>';
						$st .=
					'<tr height="40px"><td>
						<table align="left" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0"><tr>'.
							'<td height="40px" width="5px" align="center"></td>'.						
							'<td width="70px"><label for="'.$for_label.'"><img src="'.$image_path.'delivery_img/'.$r['id'.$i].'.gif" width="60px" height="32px" border="0"></label> </td>'.
							'<td width="'.$width1.'">'.$r['company'.$i].$refPickPoint.'</td>'.
							$st_sub.
						'</table></td></tr>';	
					} else if ($r['c'.$i]==1 && $r['c'.($i+1)]==2 && $r['c'.($i+2)]==3 && $r['c'.($i+3)]!=4) {
						//echo '3<br>';
						if ($r['strah'.($i+1)]==1) {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+2)].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+2)].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.($i+1)].' ���.</b></p></td></tr>';
						} else {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'<br>'.$r['name'.($i+2)].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'<br>'.$r['day'.($i+2)].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"></td></tr>';
						}						
						//$st_sub='<td width="20%" align="center">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'<br>'.$r['name'.($i+2)].'</td>'.
						//	'<td width="15%" align="center">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'<br>'.$r['day'.($i+2)].'</td>'.
						//	'<td align="center"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b></p></td>'.
						//	'<td align="center"></td></tr>';
						$st .=
					'<tr height="40px"><td>
						<table align="left" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0"><tr>'.
							'<td height="40px" width="25px" align="center"></td>'.						
							'<td width="70px"><label for="'.$for_label.'"><img src="'.$image_path.'delivery_img/'.$r['id'.$i].'.gif" width="60px" height="32px" border="0"></label> </td>'.
							'<td width="'.$width1.'">'.$r['company'.$i].$refPickPoint.'</td>'.
							$st_sub.
						'</table></td></tr>';	
					} else if ($r['c'.$i]==1 && $r['c'.($i+3)]==4) {
						//echo '4<br>';
						if ($r['strah'.($i+1)]==1 && $r['strah'.($i+3)]==1) {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+2)].'</td>'.
									'<td width="'.$width3.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+2)].'</td>'.
									'<td width="'.$width4.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b></p></td>'.
									'<td width="'.$width5.'" align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.($i+1)].' ���.</b><br><b>'.$r['price'.($i+3)].' ���.</b></td></tr>';
						} else {
							$st_sub='<td width="'.$width2.'" align="left">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'<br>'.$r['name'.($i+2)].'<br>'.$r['name'.($i+3)].'</td>'.
								'<td width="'.$width3.'" align="left">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'<br>'.$r['day'.($i+2)].'<br>'.$r['day'.($i+3)].'</td>'.
								'<td width="'.$width4.'"align="left"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b><br><b>'.$r['price'.($i+3)].' ���.</b></p></td>'.
								'<td width="'.$width5.'" align="left"></td></tr>';
						}
							//$st_sub='<td width="20%" align="center">'.$r['name'.$i].'<br>'.$r['name'.($i+1)].'<br>'.$r['name'.($i+2)].'<br>'.$r['name'.($i+3)].'</td>'.
							//'<td width="15%" align="center">'.$r['day'.$i].'<br>'.$r['day'.($i+1)].'<br>'.$r['day'.($i+2)].'<br>'.$r['day'.($i+3)].'</td>'.
							//'<td align="center"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b><br><b>'.$r['price'.($i+1)].' ���.</b><br><b>'.$r['price'.($i+2)].' ���.</b><br><b>'.$r['price'.($i+3)].' ���.</b></p></td></tr>';
							$st .=
						'<tr height="40px"><td>
							<table align="left" width="700px" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0"><tr>'.
								'<td height="40px" width="5px" align="center"></td>'.						
								'<td width="70px"><label for="'.$for_label.'"><img src="'.$image_path.'delivery_img/'.$r['id'.$i].'.gif" width="60px" height="32px" border="0"></label> </td>'.
								'<td width="'.$width1.'">'.$r['company'.$i].$refPickPoint.'</td>'.
								$st_sub.
							'</table></td></tr>';
					} /* else {
							$st_sub='<td width="20%" align="center">'.$r['name'.$i].'</td>'.
							'<td width="15%" align="center">'.$r['day'.$i].'</td>'.
							'<td align="center"><p class="c2" style="margin:0 0 0 0"><b>'.$r['price'.$i].' ���.</b></p></td></tr>';				
					}*/
			
				}
//	'<td height="40" width="25" align="center"> <input type="radio" id="'.$for_label.'" name="edost_delivery" value="'.$r['company'.$i].'|'.$r['name'.$i].'|'.$r['day'.$i].'|'.$r['price'.$i].'|'.$r['pricecash'.$i].'|'.$r['transfer'.$i].'" onclick="edost_deliveryclick();"> </td>'.

		}


		$st .=
	'</table>';

		$st .= $office;

		$st .=
	'<table align="center" width="700" cellpadding="0" cellspacing="0" border="0" bordercolor="#D0D0D0">
		<tr height="15"><td>
			<br><span id="edost_select_delivery"></span>
			<br><span id="edost_select_pickpoint"></span>
		</td></tr>
	</table>';
		$st .='<div id="edost_text" style="position:relative;top:-100px"><p>������ ����� ������������ �������� ������������ ��� ��������� ������ � ����� ������. ������� ������������ ��������,<br />';
		$st .='������� ����� ������������ ��������, �� ������ ��� ���������� ������ ����� �������.</p>';
		$st .='<p><b>��������!</b> ����������� ��������� � ����� �������� �������� <u>����������������</u> � ����� �������� ��� � �������, ��� � � �������';
		$st .='�������. ��� �������, ���������� �� ��������� ��������� �������� ���������������. <br />';
		$st .='���������� ������ ������� � ���, ��� ������������� ��������� �� ����� ����� �������� ������� ��������, ������� �����<br /> �������, � �������������, � ��������� ��������, �����';
		$st .=' ��������. ����� �������� ����������, ��������� � �����������<br /> ������� � ������ ����� ������������ ��������.<br />';
		$st .='�� ����� �������, ���������� ��������-�������� PRO���� ���������� ����������� ��� �������� ������,<br /> ������� ������������ ��� ������� ��������� ��������, ����� �������������';
		$st .='����� �������� ���������� ����������.</p><div>';
		
	}

	if (!$flPickPoint) $strJavaScriptPickPoint = ''; //���� ��� ������ PickPoint, �� �� ���������� JavaScript

	//$st = $edost_calc -> decode($strJavaScriptPickPoint.$strJavaScript.$st); // ������������ ��������� � ��������� �������� (���� ��������� � �� ������ ��������� - ������� ��� ������)

	echo $st;

?>