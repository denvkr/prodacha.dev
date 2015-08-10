<?php

/**
 * Выдача суммы голосов
 * @package PHPShopDepricated
 * @param int $id_charact ИД характеритики
 * @param int $id_good ИД товара
 * @return int
 */
function GetVoteValue($id_charact, $id_good) {
    global $SysValue;

    $sql = 'select AVG(rate) as avg,COUNT(rate) as count from ' . $SysValue['base']['table_name52'] . ' where ((id_charact=' . $id_charact . ') AND (id_good=' . $id_good . ') AND (enabled="1"))';
    $result = mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    $SysValue['sql']['num']++;
    $result2['avg'] = $row['avg'];
    $result2['count'] = round($row['count'], 1);

    return $result2;
}

/**
 * Преобразование числа в УПРАВЛЯЮЩИЕ звездочки
 * @package PHPShopDepricated
 * @param int $n
 * @param int $id
 * @param int $id_good
 * @return string
 */
function ShowStars($n, $id, $id_good) {

    $result = null;
    for ($y = 0; $y < 10; $y++) {
        $di = ceil($y / 2) * 2;
        $yy = $y + 1;
        if ($y < $n) {
            $cc = "";
        } else {
            $cc = "c";
        } //Включение пустых звездочек
        if ($di == $y) { //Проверка на четсность (определяем сторону звездочки)
            $adder = '<IMG
				onMouseOver="rreplace(' . $id . ',' . $yy . '); darker(' . $id . ',' . $y . ');" 
				onMouseOut=""
				onMouseUp="rswitch(' . $id . ',' . $id_good . ')" 
				id="chim' . $id . $y . '" 
				src="images/shop/astara' . $cc . '.gif">';
        } else {
            $adder = '<IMG
				onMouseOver="rreplace(' . $id . ',' . $yy . '); darker(' . $id . ',' . $y . ');" 
				onMouseOut=""
				onMouseUp="rswitch(' . $id . ',' . $id_good . ')" 
				id="chim' . $id . $y . '" 
				src="images/shop/astarb' . $cc . '.gif">';
        }
        $result.=$adder;
    }
    return $result;
}

/**
 * Преобразование числа в звездочки
 * @package PHPShopDepricated
 * @param int $n
 * @param int $id
 * @return string
 */
function ShowStars2($n, $id = false) {
    $result = null;
    for ($y = 0; $y < 10; $y++) {
        $di = ceil($y / 2) * 2;
        $yy = $y + 1;
        if ($y < $n) {
            $cc = "";
        } else {
            $cc = "c";
        } //Включение пустых звездочек
        if ($di == $y) { //Проверка на четсность (определяем сторону звездочки)
            $adder = '<IMG id="chim' . $id . $y . '" src="images/shop/astara' . $cc . '.gif">';
        } else {
            $adder = '<IMG id="chim' . $id . $y . '" src="images/shop/astarb' . $cc . '.gif">';
        }
        $result.=$adder;
    }

    return $result;
}

/**
 * Вывод всех оценок для товара, с итоговым полем и возможностью переголосования
 * @package PHPShopElementsDepricated
 */
function rating($obj, $row) {
    global $SysValue;

    $id_good = $row['id'];
    $ids_dir = $row['category'];

    $disp = $id_category = $script = $script2 = $all = $total2 = $votedate = null;

    // Управляющие переменные
    if (!empty($_SESSION['UsersId']))
        $id_user = $_SESSION['UsersId'];
    else $id_user=null;

    if (!$ids_dir) {
        return "Для данного товара рейтинг не предусмотрен";
    }

    // Получаем идентификатор таблицу рейтинга
    $sql = 'SELECT id_category,revoting FROM ' . $SysValue['base']['table_name50'] . ' WHERE ((ids_dir REGEXP ",' . $ids_dir . ',") AND (enabled="1"))';
    $result = mysql_query($sql);
    $SysValue['sql']['num']++;

    while (@$row = mysql_fetch_array($result)) {
        $id_category.=',' . $row['id_category'];
        $revoting = $row['revoting'];
    }
    $id_category = substr($id_category, 1);

    if (!$id_category) {
        return "Для данного товара рейтинг не предусмотрен";
    }

    // Получаем каждую характеристику
    $sql = 'SELECT id_charact,name FROM ' . $SysValue['base']['table_name51'] . ' WHERE ((id_category IN (' . $id_category . ')) AND (enabled="1")) ORDER BY num';
    $result = mysql_query($sql);
    $SysValue['sql']['num']++;

    @$chars_amount = mysql_num_rows($result);
    if (!$chars_amount) {
        return "Для данного товара рейтинг не предусмотрен";
    }
    $i = 0;
    while (@$row = mysql_fetch_array(@$result)) {
        $id_charact = $row['id_charact'];
        $disp.='<TR><TD>' . $row['name'] . '</TD>';

        // Получаем количество голосов, для каждой характеристики
        $avgcount = GetVoteValue($id_charact, $id_good);
        $amo = trim($avgcount['count']);
        if (!$amo) {
            $amo = "0";
        }

        $avgclean = trim($avgcount['avg']);
        $avg = ceil($avgclean);

        if (!empty($id_user)) {
            $sqlc = 'select rate,date from ' . $SysValue['base']['table_name52'] . ' where ((id_charact=' . $id_charact . ') AND (id_good=' . $id_good . ') AND (id_user=' . $id_user . '))';
            $resultc = mysql_query($sqlc);
            $yes = mysql_num_rows($resultc);
            $SysValue['sql']['num']++;
        }

        if (!empty($yes)) {
            $rowc = mysql_fetch_array($resultc);
            $rate = $rowc['rate'];
            $ratetxt = $rowc['rate'] / 2;
            $total2+=$rate;
            $votedate = date("d.m.Y H:i", $rowc['date']);
        } else {
            $rate = "0";
            $ratetxt = "нет";
        }

        // Управляющие звездочки только зарегистрированным
        if ($id_user && ((!$yes) || ($yes && $revoting))) {
            $starsvote = ShowStars($rate, $id_charact, $id_good);
        } else {
            $starsvote = ShowStars2($rate);
        }

        $disp.='<TD>' . ShowStars2($avg) . ' ' . ($avg / 2) . '</TD>';
        $disp.='<TD>' . $starsvote . '
		<INPUT TYPE="TEXT" onMouseUp="switch' . $id_charact . '()" style="width:30px;" readonly disabled id="r' . $id_charact . '" name="ratechar[' . $id_charact . ']" value="' . $ratetxt . '">
		<IMG id="ok' . $id_charact . '" style="visibility:hidden;" SRC="images/shop/icon-activate.gif">
		</TD>';
        $disp.='</TR>';
        $script.='oknum[' . $i . ']="' . $id_charact . '";' . "\n";
        $script2.='oknum2[' . $i . ']="' . $id_charact . '";' . "\n";
        $i++;
        $all+=$avgclean;
    }

    $total = $all / $chars_amount;
    $total = round($total, 2);
    $total2 = $total2 / $chars_amount;
    $total2 = round($total2, 1);

    //Если уже голосовали
    if ($votedate) {
        $done = 'Вы уже голосовали <BR>' . $votedate . '<BR>';
        if ($revoting) {
            $done.='Можете переголосовать.';
        } else {
            $done.='Переголосование ОТКЛЮЧЕНО';
        }
    } else {
        $done = '&nbsp;<BR>&nbsp;';
    }

    $disp = '<SCRIPT>
function checker(idc,idgood) {
oknum=new Array();
' . $script . '
bad=0;
for (i=0; i<oknum.length;i++) {
	curokname="ok"+oknum[i];
	if (document.getElementById(curokname).style.visibility == "hidden") {bad=1;}
}

if (bad==1) {
	document.getElementById("okfinal").style.visibility = "hidden";
	document.getElementById("rateit").style.visibility = "hidden";
} else {
	badbad=0;
	for (i=0; i<oknum.length;i++) {
		enabled=1;
		rate=0;
		idc=oknum[i];
		//Сохранение в БД
		var req = new Subsys_JsHttpRequest_Js();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseJS) {
					result=(req.responseJS.result||"");
					if (result=="7") {

					} else if(result=="0") {
						document.getElementById("statusdiv").innerHTML="Не удалось сохранить!";
						badbad=1;
					}  
				}
			}
		}
		req.caching = false;
		// Подготваливаем объект.
		var dir=dirPath();// Реальное размещение
		req.open("POST", "phpshop/ajax/ratingvote.php", true);
		req.send({ idc: idc,idgood:idgood,rate:rate,enabled:enabled });
		
		if (badbad==1) {
			document.getElementById("okfinal").style.visibility = "visible";
			document.getElementById("rateit").innerHTML = "НЕ УДАЛОСЬ сохранить!";

		} else {
			document.getElementById("rateit").style.visibility = "visible";
			window.location.reload();
		}

	}

}
}

function counttotal() {
oknum2=new Array();
' . $script2 . '
totalsum=0;
for (i2=0; i2<oknum2.length;i2++) {
	currname="r"+oknum2[i2];
	if (document.getElementById(currname).value=="нет") {vale=0;} else {vale=document.getElementById(currname).value;}
	
        totalsum+=parseFloat(vale);
}

totalsum=totalsum/(oknum2.length);
tsum=Math.ceil(totalsum*10)/10;
//alert(oknum2.length);
document.getElementById("totalamount").innerHTML=tsum;
document.getElementById("r999").value =(tsum);
darker(999,totalsum*2);

}



function rreplace(idc,n) {
rname="r"+idc;
if (document.getElementById(rname).disabled==true) {document.getElementById(rname).value = n/2;}
counttotal();
}


function darker(idc,n) {
var nameimg="";
if (document.getElementById("r"+idc).disabled==false) {return;}
for (y=0; y<10; y++) {
	di=Math.ceil(y/2)*2;
	nameimg="chim"+idc+y;
if (y<=n) {
	if (di==y) { //Проверка на четсность (определяем сторону звездочки)
		document.getElementById(nameimg).src=\'images/shop/astarao.gif\';
	}  else {
		document.getElementById(nameimg).src=\'images/shop/astarbo.gif\'; 
	}
} else {
	if (di==y) { //Проверка на четсность (определяем сторону звездочки)
		document.getElementById(nameimg).src=\'images/shop/astarac.gif\';
	}  else {
		document.getElementById(nameimg).src=\'images/shop/astarbc.gif\'; 
	}

}

} //for
}		  


function rswitch(idc,idgood) {
rname="r"+idc;
okname="ok"+idc;
	if (document.getElementById(rname).disabled==true) {
		var rate=document.getElementById(rname).value;
		rate=rate*2;
		enabled=0;
		//Сохранение в БД
		var req = new Subsys_JsHttpRequest_Js();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseJS) {
					result=(req.responseJS.result||"");
					if (result=="1") {
						document.getElementById(rname).disabled=false;
						document.getElementById(okname).style.visibility = "visible";
						document.getElementById("statusdiv").innerHTML="Вы авторизованы. <BR>Ваш голос учтен!";
						checker(idc,idgood);
					} else if(result=="2") {
						document.getElementById(rname).disabled=false;
						document.getElementById(okname).style.visibility = "visible";
						document.getElementById("statusdiv").innerHTML="Вы уже голосовали.<BR> Данные обновлены.";
						checker(idc,idgood);
					} else if(result=="3") {
						document.getElementById(rname).disabled=false;
						document.getElementById(okname).style.visibility = "visible";
						document.getElementById("statusdiv").innerHTML="Результат сохранен.<BR>&nbsp;";
						checker(idc,idgood);
					} else if(result=="0") {
						document.getElementById("statusdiv").innerHTML="Вы не авторизованы. Пройдите<BR> авторизацию для выставления рейтинга!";

					}  
				}
			}
		}
		req.caching = false;
		// Подготваливаем объект.
		var dir=dirPath();// Реальное размещение
		req.open("POST", "phpshop/ajax/ratingvote.php", true);
		req.send({ idc: idc,idgood:idgood,rate:rate,enabled:enabled });
	} else {
		document.getElementById(rname).disabled=true;
		document.getElementById(okname).style.visibility = "hidden";
	}
checker(idc,idgood);
}



</SCRIPT>
<FORM>
<TABLE celpadding=1>
<TR><TD><B>Характеристики</TD><TD><B>Оценки:</B></TD><TD><B>Ваши оценки:</B></TD></TR>
' . $disp . '
<TR><TD>Итого:</TD><TD>' . ShowStars2($total) . ' ' . ($total / 2) . '</TD><TD>
' . ShowStars2($total2, 999) . ' <B id="totalamount">' . ($total2 / 2) . '</B>
<INPUT TYPE="HIDDEN" disabled id="r999" value="нет">
<IMG id="okfinal" style="visibility:hidden;" SRC="images/shop/icon-activate.gif">

</TD></TR>
<TR>
<TD colspan=2><I>Всего выставлено оценок: <B>' . $amo . '</B></I></TD>
<TD>
<DIV id="statusdiv">' . $done . '</DIV>';

    if ($id_user) {
        $disp.='<DIV id="rateit" style="color:green; visibility:hidden;" >сохранено</DIV>';
    } else {
        $disp.='Только <A href="/users/register.html">зарегистрированные пользователи</A><BR>
		 могут выставлять оценки товарам!';
    }
    $disp.='</TD></TR></TABLE></FORM>';
    $obj->set('ratingfull', $disp);
}

/**
 * Выдача имени товара
 * @package PHPShopDepricated
 * @param int $id ИД товара
 * @return string
 */
function getgoodname($id) {
    global $SysValue;

    $sql = "select name from " . $SysValue['base']['table_name2'] . " where id=$id and enabled='1'";
    $result = mysql_query($sql);
    @$amount = mysql_num_rows($result);
    if (!$amount) {
        return false;
    } else {
        @$row = mysql_fetch_array(@$result);
        return $row['name'];
    }
}

/**
 * Вывод всех оценок для товара, с итоговым полем и возможностью переголосовани
 * @package PHPShopElementsDepricated
 * @return string
 */
function ratingtop() {
    global $SysValue;

    $top = null;
    $sql = 'select AVG(rate) as avg,COUNT(DISTINCT id_user) as count, (AVG(rate)*COUNT(DISTINCT id_user)*0.5) as ttr, id_good from ' . $SysValue['base']['table_name52'] . ' where ((enabled="1"))  GROUP BY id_good ORDER BY ttr DESC LIMIT 20';
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $rate = round($row['avg'], 2) / 2;
        $id = $row['id_good'];
        $name = getgoodname($id);
        if ($name) {
            $top.='<TR><TD><A href="shop/UID_' . $id . '.html">' . $name . '</A></TD><TD>' . $rate . '</TD><TD>' . $row['count'] . '</TD><TD>' . $row['ttr'] . '</TD></TR>';
        }
    }

    return '<TABLE border=1><TR><TD>Товар</TD><TD>Оценка</TD><TD>Кол-во голосов</TD><TD>Итоговый рейтинг</TD></TR>' . $top . '</TABLE>';
}

/**
 * Вывод рейтинга в кратком выводе товара
 * @param int $id_good ИД товара
 * @return string
 */
function ratingshort($id_good) {
    global $SysValue;

    $sql = 'select AVG(rate) as avg,COUNT(DISTINCT id_user) as count from ' . $SysValue['base']['table_name52'] . ' where ((id_good=' . $id_good . ') AND (enabled="1"))';
    $result = mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    @$SysValue['sql']['num']++;
    $vote = round($row['avg'], 2);
    $amount = round($row['count'], 1);
    $voteshow = $vote / 2;
    $voteshow = round($voteshow, 3);
    $result2 = ShowStars2($vote, "0") . '(' . $voteshow . ')<BR>Всего голосовало:' . $amount;

    return $result2;
}

?>