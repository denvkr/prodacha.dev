<?php

/**
 * ����� ������� ��������
 * @package PHPShopCoreFunction
 * @param int $deliveryID �� ��������
 * @return string
 */
function delivery($obj,$deliveryID,$totalsumma=0) {
    global $SysValue;
    
    $pred=$br=$my=$alldone=$waytodo=null;

    if (empty($SysValue['nav'])) {
        $engineinc=0;
        $pathTemplate='/'.chr(47).$GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); // ���� �� �������
    } else {
        $engineinc=1;
        $pathTemplate='';
    }

    if ($totalsumma==0) {
    	$totalsumma=$obj->get('cart_sum');
    }
    //echo $totalsumma;
    $table=$SysValue['base']['table_name30'];

    if($deliveryID>0) {
        $sql="select * from ".$table." where (enabled='1' and id='".$deliveryID."') order by city";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
        $isfolder=$row['is_folder'];
        $PID=$row['PID'];
        $sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."') order by city";

       		if ($isfolder) { //���� �������� �����, �� �������� ����� ������� �����

   			if ($_COOKIE['sincity']=="m"  || $_COOKIE['sincity']=="other") {
   				if ($totalsumma>=1000 && $totalsumma<=2999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('41')) order by city";
   				}
   				if ($totalsumma>=3000 && $totalsumma<=9999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','3','24','22')) order by city";
   				}
   				if ($totalsumma>=10000 && $totalsumma<=19999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','66','23','25')) order by city";
   				}
   				if ($totalsumma>=20000 && $totalsumma<=49999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','66','26','27','28','35','38','39','40')) order by city";
   				}
   				if ($totalsumma>=50000) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','66','29','30','31','32','33','34','35','36','37','42')) order by city";
   				}
   			}
    			 
   			if ($_COOKIE['sincity']=="sp") {
   				if ($totalsumma>=1000 && $totalsumma<=2999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('41')) order by city";
   				}
   				if ($totalsumma>=3000 && $totalsumma<=9999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','44','45','47')) order by city";
   				}
   				if ($totalsumma>=10000 && $totalsumma<=19999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','64','46','48')) order by city";
   				}
   				if ($totalsumma>=20000 && $totalsumma<=49999) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','64','49','50','51','61','62')) order by city";
   				}
   				if ($totalsumma>=50000) {
   					$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."' and id in ('11','64','52','53','54','55','56','57','58','59','60','63')) order by city";
   				}
   			}
    	
   			$PIDpr=$deliveryID; //��������� ������, ��� �����������
   			$stop=0;

		} else { //���� �������� �������, �� �������� ����� ������

			if ($row['PID']=='1') {

   				if ($_COOKIE['sincity']=="m" || $_COOKIE['sincity']=="other") {
   					if ($totalsumma>=1000 && $totalsumma<=2999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('41')) order by city";
   					}
   					if ($totalsumma>=3000 && $totalsumma<=9999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','3','24','22')) order by city";
   					}
   					if ($totalsumma>=10000 && $totalsumma<=19999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','66','23','25')) order by city";
   					}
   					if ($totalsumma>=20000 && $totalsumma<=49999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','66','26','27','28','35','38','39','40')) order by city";
   					}
   					if ($totalsumma>=50000) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','66','29','30','31','32','33','34','35','36','37','42')) order by city";
   					}
   				}
   				if ($_COOKIE['sincity']=="sp") {
   					if ($totalsumma>=1000 && $totalsumma<=2999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('41')) order by city";
   					}
   					if ($totalsumma>=3000 && $totalsumma<=9999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','44','45','47')) order by city";
   					}
   					if ($totalsumma>=10000 && $totalsumma<=19999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','64','46','48')) order by city";
   					}
   					if ($totalsumma>=20000 && $totalsumma<=49999) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','64','49','50','51','61','62')) order by city";
   					}
   					if ($totalsumma>=50000) {
   						$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."' and id in ('11','64','52','53','54','55','56','57','58','59','60','63')) order by city";
   					}
   				}
        	    	
	    	
	    } else {
            	    $sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."') order by city";
	    }	
            $PIDpr=$row['PID']; //��������� ������, ��� �����������
            $stop=1;
        }
    } else { //���� �� ��������, ������ ��������� ����� ��� �������� ��������
        $stop=0;
        $isfolder=1; //���� �� �������� ID, ������ �������� 0 �������������, ������� �������� ������ ��������
        $PID=false;
        $deliveryID=0; //����������� ������� �������������, ���� ������ �� ��������
        $sqlvariants="select * from ".$table." where (enabled='1' and PID='0') order by city";
    }
    $resultvariants=mysql_query($sqlvariants);// ��������� ��������
    $varamount=mysql_num_rows($resultvariants);


    if ($PID!==false) { //���� ���� ������, ��������� ���������
        $pred='';
        $ii=0;
        $num=0;
        while ($PIDpr!=0) {//������ ���� �� ������ �� ������ �������� ������
            $num++;

            //�������� ������� ������
            $sqlpr="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and id='".$PIDpr."') order by city";
            $resultpr=mysql_query($sqlpr);
            $rowpr = mysql_fetch_array($resultpr);

            $PIDpr=$rowpr['PID']; //������ ������������� ������. �� ������� ����
            $city=$rowpr['city'];
            $predok=$rowpr['city'].' > '.$predok; //�������, ������� ����� ���������� ������� ��������

            //�������� ���������� ������� � ������������.
            $sqlprr="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$PIDpr."') order by city";
            $resultprr=mysql_query($sqlprr);
            $ii=0;
            while ($rowsos = mysql_fetch_array($resultprr)) {
                $sqlsosed="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$rowsos['id']."') order by city";
                $resultsosed=mysql_query($sqlsosed);
                $sosed=mysql_num_rows($resultsosed);
                $sosfolder=$rowsos['is_folder'];
                if ($sosfolder) {
                    if ($sosed) {
                        $ii++;
                    }
                } else {
                    $ii++;
                }
            }

            //���� ((���� ������, �.�. �� ������� ������ ����� ������� ���-�� ������)
            // � (������� �������� ������ �������)), �� ���������� ����������� ������� �� ������� ����
            if (($ii>1) && ($num>0)) { //���������� ������ "�����" ���� ������ 1 ������� ������ � �������� � (���� ���� ������� ���� ������� �������� ������ �������)
                $pred='�������: '.$city.' <A href="javascript:UpdateDelivery('.$PIDpr.')" title="������� ������ ������ ��������"><img src="../'.$pathTemplate.'/images/shop/icon-activate.gif" alt=""  border="0" align="absmiddle">����� �����</A> <BR> '.$pred;
            }
        }
        if (strlen($pred)) {
            $br='<BR>';
        } else {
            $br='';
        } //���� ���� ���� ����������� ����, �� ���������� �������� ������,
    } //���� ���� ������, ��������� ���������


    $varamount=0;
    $chkdone=0; //�� ������� ������������� �������� �� �������
    while($row = mysql_fetch_array($resultvariants)) {

        if(!empty($deliveryID)) {//���� ��������� �������������
            if($row['id'] == $deliveryID) {
                $chk="selected";
            } else {
                $chk="";

                if ($isfolder) { //���� ���������� ������������� ����� � �������� ��������� ����
                    if($row['flag']==1) { //�� ������ �������� �� ���������
                        $chk="selected";
                        $chkdone=$row['id']; //���� ��������� ������������� ��������, �� �������� ��� ����� ��������
                    } else {
                        $chk="";
                    }
                }
            }
        } elseif($engineinc) {//���� �� ��������� �������������, �� ������������ ��������� ������

            if($row['flag']==1) { //�� ������ �������� �� ���������
                $chk="selected";
                $chkdone=$row['id']; //���� ��������� ������������� ��������, �� �������� ��� ����� ��������
            } else {
                $chk="";
            }

        }

        // �������� ���������� ������� � ������������.
        $sqlpot="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$row['id']."') order by city";
        $resultpot=mysql_query($sqlpot);
        $pot=mysql_num_rows($resultpot);


        $city=$row['city'];
        if ((!$row['is_folder'])||($pot)) {
        	if ($_COOKIE['sincity']=="sp") 
			{ 
				if (intval($totalsumma)>=1000 && (($row['id']==11) OR ($row['id']==14) OR ($row['id']==1) OR ($row['id']==41) OR ($row['id']==44) OR ($row['id']==45) OR ($row['id']==46) OR ($row['id']==47) OR ($row['id']==48) OR ($row['id']==49) OR ($row['id']==50) OR ($row['id']==51) OR ($row['id']==52) OR ($row['id']==53) OR ($row['id']==54) OR ($row['id']==55) OR ($row['id']==56) OR ($row['id']==57) OR ($row['id']==58) OR ($row['id']==59) OR ($row['id']==60) OR ($row['id']==61) OR ($row['id']==62) OR ($row['id']==63) OR ($row['id']==64))) {
					@$disp.='<span id="spanvar_'.$row['id'].'" ><OPTION id="var_'.$row['id'].'" value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION></span>';
				} else if (intval($totalsumma)<1000 && (($row['id']==14)))	{
					@$disp.='<span id="spanvar_'.$row['id'].'" ><OPTION id="var_'.$row['id'].'" value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION></span>';
				}						
			}
			if ($_COOKIE['sincity']=="chb") 
			{							
				if ( ($row['id']==43) OR ($row['id']==1)  )
					@$disp.='<span id="spanvar_'.$row['id'].'" ><OPTION id="var_'.$row['id'].'" value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION></span>';
			}
			if ($_COOKIE['sincity']=="m" || $_COOKIE['sincity']=="other")
			{
				if (intval($totalsumma)>=1000 && (($row['id']==11) OR ($row['id']==10) OR ($row['id']==13) OR ($row['id']==1) OR ($row['id']==3) OR ($row['id']==22) OR ($row['id']==23) OR ($row['id']==24) OR ($row['id']==25) OR ($row['id']==27) OR ($row['id']==28) OR ($row['id']==29) OR ($row['id']==30) OR ($row['id']==31) OR ($row['id']==32) OR ($row['id']==33) OR ($row['id']==34) OR ($row['id']==35) OR ($row['id']==36) OR ($row['id']==37) OR ($row['id']==38) OR ($row['id']==40) OR ($row['id']==41) OR ($row['id']==42) OR ($row['id']==66))) {
					@$disp.='<span id="spanvar_'.$row['id'].'" ><OPTION id="var_'.$row['id'].'" value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION></span>';
				} else if (intval($totalsumma)<1000 && (($row['id']==10) OR ($row['id']==13)))	{
					@$disp.='<span id="spanvar_'.$row['id'].'" ><OPTION id="var_'.$row['id'].'" value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION></span>';
				}
			}
        				
            $varamount++;
            $curid=	$row['id'];
        }

    }


    if ($varamount==0) {
        $makechoise='<OPTION value=0>[�������� �� ���������]</OPTION>';
        $alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
        $deliveryID=0;
        $curid=$deliveryID;
    } elseif ($varamount>=1 && $deliveryID>0) {
        $makechoise='<OPTION value="0" id="makeyourchoise">�������� ��������</OPTION>'; //$deliveryID
        $alldone='';
    } else {
        $alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
    }

    if ($varamount==1) {
        if (!(($curid==$deliveryID))) $waytodo='<IMG onload="UpdateDelivery('.$curid.');" SRC="../'.$pathTemplate.'/images/shop/flag_green.gif">';
    }

    if ($stop) {
        $makechoise='';
        $alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
    } else {
        if ($chkdone) $waytodo='<IMG onload="UpdateDelivery('.$chkdone.');" SRC="../'.$pathTemplate.'/images/shop/flag_green.gif">';
    }




    $disp='<DIV id="seldelivery">'.$pred.$br.$my.$stylenah.'<SELECT onchange="UpdateDelivery(this.value)" name="dostavka_metod" id="dostavka_metod">
'.$makechoise.'
'.$disp.'
</SELECT>'.$alldone.$waytodo.'</DIV>';

    if($obj)
        $obj->set('orderDelivery',$disp);
    else
        return $disp;
}
?>
