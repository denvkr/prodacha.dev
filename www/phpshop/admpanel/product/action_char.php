<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

if (CheckedRules($UserStatus["cat_prod"], 2) == 1) {
    // Подключаем библиотеку поддержки.
    require_once "../../lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

    // Прием переменных
    $addit = trim(htmlspecialchars($_REQUEST['addit'],ENT_QUOTES,'cp1251'));
    $seld = $_REQUEST['selopts'];

    if (strlen($addit)) {
        $sql = 'select * from ' . $SysValue['base']['table_name21'] . ' where ((`name` = "' . $addit . '") AND (category=' . $_REQUEST['num'] . '))';
        $result = mysql_query($sql);
        $numrows = mysql_num_rows($result);
    }

    if (!$numrows) {
        $sql = 'INSERT INTO ' . $SysValue['base']['table_name21'] . ' VALUES ("","' . $addit . '","' . $_REQUEST['num'] . '",0,"")';
        $result = mysql_query($sql);
//VALUES ('$productID','$category_new','".CleanStr(trim($name_new))."','".addslashes($EditorContent)."','".addslashes($EditorContent2)."','$priceOne','$priceBox','$numBox','1','$enabled_new','$uid_new','$spec_new','$odnotip_new','$vendor','".serialize($vendor_new)."','$yml_new','$num_new','','$title_new','$title_enabled_new','".date("U")."','$page','".$_SESSION['idPHPSHOP']."','$descrip_new','$descrip_enabled_new','$title_shablon_new','$descrip_shablon_new','$keywords_new','$keywords_enabled_new','$keywords_shablon_new','$pic_small_new','$pic_big_new','".serialize($yml_bid_array_new)."','$parent_enabled_new','$parent_new','$items_new','$weight_new','$price2','$price3','$price4','$price5','".serialize($filenum)."','$baseinputvaluta_new','$edizm_new')";
    }

    $sql = "select * from " . $SysValue['base']['table_name21'] . " where category=" . intval($_REQUEST['num']) . " order by num asc";
    $result = mysql_query($sql);




    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        @$interfaces[$i]['id'] = $id;
        @$interfaces[$i]['name'] = $row['name'];

        if ((in_array($id, $seld)) || (trim($row['name']) == $addit)) {
            @$interfaces[$i]['selected'] = true;
        } else {
            @$interfaces[$i]['selected'] = false;
        }
        $i++;
    }

    $_RESULT = array(
        "interfaces" => @$interfaces
    );
}
?>