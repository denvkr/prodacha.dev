<?
function Page_news()// Создание страниц
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_ot=0;
$q=0;
while($q<$p)
  {
  $sql="select * from ".$SysValue['base']['table_name8']." order by id desc LIMIT $num_ot, $num_row ";
  $q++;
  $num_ot=$num_ot+$num_row;
  }
return $sql;
}

function Nav_news()// Навигация 
{
global $SysValue,$LoadItems;
$p=$SysValue['nav']['id']; if(@!$p) $p=1;
$num_row=$LoadItems['System']['num_row'];
$num_page=NumFrom("table_name8","");
$i=1;
$num=$num_page/$num_row;
while ($i<$num+1)
    {
	if($i!=$p){
	
	if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	@$navigat.="
	     <a href=\"./news_".$i.".html\">".$pageOt."-".$pageDo."</a> | ";
	}
	else{
	
     if($i==1) $pageOt=$i+@$pageDo;
	 else $pageOt=$i+@$pageDo-$i;
	 
	$pageDo=$i*$num_row;
	 @$navigat.="
	     <b>".$pageOt."-".$pageDo."</b> | ";
	}
	$i++;
	}
 if($num>1)
  {
 if($p>$num){$p_to=$i-1;}else{$p_to=$p+1;}
 $nava="
 <table cellpadding=\"0\" cellpadding=\"0\" border=\"0\">
        <tr >
	     <td class=style5>
".$SysValue['lang']['page_now'].": 
<a href=\"./news_".($p-1).".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat&nbsp<a href=\"./news_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td>
       </tr>
        </table>
		";
	}
return @$nava;
}

function News()
{
global $SysValue,$LoadItems,$p;
$sql=Page_news();
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$podrob=stripslashes($row['podrob']);
	
// Определяем переменые
$SysValue['other']['newsData']= $row['datas'];
$SysValue['other']['newsZag']= $row['zag'];
$SysValue['other']['newsKratko']=mySubstr(stripslashes($row['kratko']),300);
$SysValue['other']['newsAll']= $all;
$SysValue['other']['newsId']= $id;
//@$SysValue['other']['catalogCat']= "Дополнительно";

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['main_news_forma']);
	}

// Определяем переменые
$SysValue['other']['productFound']= $SysValue['lang']['found_of_products'];
$SysValue['other']['productNum']= $LoadItems['NumNews'];
$SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
$SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
//$SysValue['other']['catalogCategory']=$SysValue['lang']['news'];
$SysValue['other']['productPageThis']=$p;
$SysValue['other']['productPageNav']=Nav_news();
$SysValue['other']['productPageDis']=@$dis;

// Подключаем шаблон
@$disp=ParseTemplateReturn($SysValue['templates']['news_page_list']);
return @$disp;
}


function NewsPodrob($id)
{
global $system,$PHP_SELF,$SysValue;
$id=TotalClean($id,1);
$sql="select * from ".$SysValue['base']['table_name8']." where id=$id";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);

// Определяем переменые
$SysValue['other']['newsData']= $row['datas'];
$SysValue['other']['newsZag']= $row['zag'];
$SysValue['other']['newsKratko']= stripslashes($row['kratko']);
$SysValue['other']['newsPodrob']= stripslashes($row['podrob']);
//@$SysValue['other']['catalogCat']= "Дополнительно";

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['main_news_forma_full']);

// Определяем переменые
//$SysValue['other']['catalogCategory']=$SysValue['lang']['news'];
$SysValue['other']['productPageDis']=@$dis;

// Подключаем шаблон
if(!empty($row['zag']))
 @$disp=ParseTemplateReturn($SysValue['templates']['news_page_full']);
 else @$disp=404;
return @$disp;
}

?>
