<?php

/**
 * ���������� ������������� ������������ �� 1�
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.8
 */
// �����������
include_once("login.php");
PHPShopObj::loadClass("readcsv");

$F_done = null;
$GetItemCreate = 0;
$GetItemUpdate = 0;
$GetCatalogCreate = 0;

// ����� ������ ������������� ��� ����� ������ �������
$GLOBALS['option']['sort'] = 17;


// ��������������
if (function_exists('mod_option')) {
    call_user_func_array('mod_option', array(&$GLOBALS['option']));
}

// ����������� ������� �������������� � ��������
function updateCatalog($parent_id, $charID) {
    global $SysValue;

    $sql2_3 = 'select sort from ' . $SysValue['base']['table_name'] . ' WHERE id="' . $parent_id . '"';
    $result2_3 = mysql_query($sql2_3);
    $num2_3 = mysql_num_rows(@$result2_3);
    if (!$num2_3)
        return false;
    $row2_3 = mysql_fetch_array($result2_3);
    $sorts = unserialize($row2_3['sort']);
    $sel = "";

    if (is_array($sorts)) {
        foreach ($sorts as $k => $v) {
            if ($charID == $v)
                $sel = "selected";
        }
    }

    // ��������� �� �������� �� � �������� ����� ID
    if ($sel != "selected") {
        $sorts[] = trim($charID);
        $ss = addslashes(serialize($sorts));
        $sql2_4 = 'UPDATE ' . $SysValue['base']['table_name'] . ' SET sort="' . $ss . '" WHERE id="' . $parent_id . '"';
        $result2_4 = mysql_query($sql2_4);
    }
    return true;
}

// ������� ���������� ����� ��������������
function charsGenerator($parent_id, $CsvToArray) {

    global $SysValue;
    for ($i = $GLOBALS['option']['sort']; $i < count($CsvToArray); $i = $i + 2) { //�������� ������������ ��� ������ ����� ��������������� ��������
        $charName = trim($CsvToArray[$i]);
        $charValues = trim($CsvToArray[$i + 1]);
        $charValues = split("&&", $charValues);

        //�������� ������������� ��������������
        $sql2 = 'select id,name from ' . $SysValue['base']['table_name20'] . ' WHERE name like "' . $charName . '"';
        $result2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($result2);
        $charID = $row2['id'];

        if (strlen($charName)) {
            $go = false;
            foreach ($charValues as $charValue) {
                $charValue = trim($charValue);
                if (strlen($charValue)) {
                    $go = true;
                }
            }
            unset($charValue);
            if ($go) {//���� ���� ���� �� ���� �� ������ ��������
                if (!$charID) { //���� �������������� �� �������, ���� ������� ������ � ��������������
                    //������� ������
                    $sql2_1 = 'INSERT INTO ' . $SysValue['base']['table_name20'] . ' (name,category) VALUES("������ ' . $charName . '","0")'; //������� ������
                    $result2_1 = mysql_query($sql2_1);
                    $group_id = mysql_insert_id(); //�������� ��������� ����������� id - id ������
                    //������� ��������������, ����������� � ������
                    $sql2_2 = 'INSERT INTO ' . $SysValue['base']['table_name20'] . ' (name,category) VALUES("' . $charName . '","' . $group_id . '")'; //������� ���.
                    $result2_2 = mysql_query($sql2_2);
                    $charID = mysql_insert_id(); //�������� ��������� ����������� id - id ��������� ��������������

                    if (!(updateCatalog($parent_id, $charID))) { //���� ��� ������� �������� � ��������� �������� ��� �� ��� ������, ���������� ���������� ������������� � ������� ���������
                        $sql2_3 = 'DELETE FROM ' . $SysValue['base']['table_name20'] . ' WHERE id=' . $group_id;
                        $result2_3 = mysql_query($sql2_3);
                        $sql2_4 = 'DELETE FROM ' . $SysValue['base']['table_name20'] . ' WHERE id=' . $charID;
                        $result2_4 = mysql_query($sql2_4);
                        $charID = false;
                    }
                } else {//���� �������������� �������, ������ ������� ��������� �� � �������� �������
                    if (!(updateCatalog($parent_id, $charID))) {
                        $charID = false;
                    }
                }
            }
        } else {
            $charID = false;
        }


        if ($charID) { //���� ������� ��������  id �������������� (��� �������) - �������� ������ ��� ����������
            foreach ($charValues as $charValue) {
                $charValue = trim($charValue);
                if (strlen($charValue)) {
                    $sql3 = 'select id,name from ' . $SysValue['base']['table_name21'] . ' WHERE (name like "' . $charValue . '") AND (category="' . $charID . '")'; //�������� �������� ���-�
                    $result3 = mysql_query($sql3);
                    $row3 = mysql_fetch_array($result3);
                    $id = $row3['id'];
                    if (!$id) { //���� �� ������� �������� id �������� ��������, ������ ���� �������� �����
                        $sql4 = 'INSERT INTO ' . $SysValue['base']['table_name21'] . ' (name,category) VALUES("' . $charValue . '","' . $charID . '")'; //�������� ����. ���-�
                        $result4 = mysql_query($sql4);
                        $id = mysql_insert_id(); //�������� ��������� ����������� id � �� ����� id ����������� � ������
                    }
                    if ($id) {
                        $resCharsArray[$charID][] = $id;
                    }
                }
            }
        }
    }
    return $resCharsArray;
}

// ��������� ���������
class ReadCsvCatalog extends PHPShopReadCsv {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $CsvToArray;
    var $ItemCreate = 0;

    function ReadCsvCatalog($file) {
        $this->CsvContent = parent::readFile($file);
        $this->TableName = $GLOBALS['SysValue']['base']['table_name'];
        parent::PHPShopReadCsv();
    }

    // ������� ����� ������
    function CreateCatalog($id) {
        $CsvToArray = $this->CsvToArray[$id];
        if (is_array($CsvToArray)) {
            $sql = "INSERT INTO " . $this->TableName . " SET
     id = '" . trim($CsvToArray[0]) . "',
     name = '" . parent::CleanStr($CsvToArray[1]) . "', 
     parent_to = '" . trim($CsvToArray[2]) . "' ";
            $result = mysql_query($sql);
            $this->ItemCreate++;
        }
    }

    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // ��������� �������� ������ ���������
    function ChekTree($id) {
        $row = $this->CsvToArray;
        $parent = $row[$id][2];
        $CheckId = parent::CheckId($id);
        if (empty($CheckId))
            $this->CreateCatalog($id);
        if ($parent != 0) {
            $CheckIdParent = parent::CheckId($parent);
            if (empty($CheckIdParent))
                $this->ChekTree($parent);
        }
    }

}

// ��������� �������
class ReadCsv1C extends PHPShopReadCsvPro {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $Sklad_status;
    var $ObjCatalog, $ObjSystem;
    var $ItemCreate = 0;
    var $ItemUpdate = 0;
    var $ImageSrc = "jpg";

    function ReadCsv1C($CsvContent, $ObjCatalog, $ObjSystem) {
        $this->ImagePath = $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Image/";
        $this->CsvContent = $CsvContent;
        $this->TableName = $GLOBALS['SysValue']['base']['table_name2'];
        $this->TableNameFoto = $GLOBALS['SysValue']['base']['table_name35'];
        $this->Sklad_status = $ObjSystem->getSerilizeParam("admoption.sklad_status");
        $this->ObjCatalog = $ObjCatalog;
        $this->ObjSystem = $ObjSystem;
        $this->GetIdValuta = PHPShopValuta::getAll();
        parent::PHPShopReadCsvPro();
        $this->DoUpdatebase();
    }

    // ���-�� ��������� �������
    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // ���-�� ���������� �������
    function GetItemUpdate() {
        return $this->ItemUpdate;
    }

    // ���-�� ��������� ���������
    function GetCatalogCreate() {
        if ($this->ObjCatalog)
            $num = $this->ObjCatalog->GetItemCreate();
        else
            $num = 0;
        return $num;
    }

    // ���� � ��������
    function ImagePlus($img) {
        if (!empty($img))
            return $this->ImagePath . $img;
    }

    // ������� ��������
    function DoUpdatebase() {
        $CsvToArray = $this->CsvToArray;
        if (is_array($CsvToArray)) {
            foreach ($CsvToArray as $v) {
                $this->UpdateBase($v);
                $this->UpdateBaseCatalog($v[15]);
            }
        }
    }

    // ������� ��������
    function UpdateBaseCatalog($category) {
        if ($this->ObjCatalog)
            $this->ObjCatalog->ChekTree($category);
    }

    // �������� ���-�� ����
    function GetNumFoto($id) {
        $sql = "select id from " . $this->TableNameFoto . " where parent=$id";
        $result = mysql_query($sql);
        return mysql_num_rows($result);
    }

    // ��������� �� ������ �� ��������
    function getIdForImages($uid) {
        $sql = "select id from " . $this->TableName . " where uid='$uid' limit 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        return $row['id'];
    }

    // ���������� ������
    function UpdateBase($CsvToArray) {

        // ���� �� ������ � ����
        if ($_REQUEST['create'] == "true")
            $CheckBase = parent::CheckUid($CsvToArray[0]);
        else
            $CheckBase = true;

        // ���������
        if (!empty($CheckBase)) {

            $sql = "UPDATE " . $this->TableName . " SET ";

            // ��������������
            if (function_exists('mod_update')) {
                $sql.=call_user_func_array('mod_update', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_name") == 1 and !empty($CsvToArray[1]))
                $sql.="name='" . addslashes($CsvToArray[1]) . "', "; // ��������

            if ($this->ObjSystem->getSerilizeParam('1c_option.update_content') == 1 and !empty($CsvToArray[4]))
                $sql.="content='" . addslashes($CsvToArray[4]) . "', "; // ������� ��������

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_description") == 1 and !empty($CsvToArray[2]))
                $sql.="description='" . addslashes($CsvToArray[2]) . "', "; // ��������� ��������

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "', "; // ���������

            $sql.="price='" . @$CsvToArray[7] . "', "; // ���� 1
            // �����
            switch ($this->Sklad_status) {

                case(3):
                    // 0- ��� ����� �� ����� + ��� ������ �� ������ ������ 
                    if ((int)$CsvToArray[19]==0){
                        $sql.="sklad='1', ";
                        $sql.="p_enabled='0', ";
                        $sql.="yml='0', ";
                        //1,10,11,2,3 - � ������� �� ����� (��� ����� "��� �����") + ����� �� ������ ������� � ������� 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sql.="sklad='0', ";
                        $sql.="p_enabled='1', ";
                        $sql.="yml='1', ";
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sql.="sklad='0', ";
                        $sql.="p_enabled='0', ";
                        $sql.="yml='1', ";
                    }
                    $sql.="enabled='1', ";
                    break;

                case(2):
                    // 0- ��� ����� �� ����� + ��� ������ �� ������ ������ 
                    if ((int)$CsvToArray[19]==0){
                        $sql.="p_enabled='0', ";
                        $sql.="yml='0', ";
                        //1,10,11,2,3 - � ������� �� ����� (��� ����� "��� �����") + ����� �� ������ ������� � ������� 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sql.="p_enabled='1', ";
                        $sql.="yml='1', ";
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sql.="p_enabled='0', ";
                        $sql.="yml='1', ";
                    }                    
                    $sql.="enabled='1', ";                        
                    break;

                default: $sql.="";
            }

            if (!empty($CsvToArray[3])) {
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',";
                $sql.="pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";
            }

            $sql.="price2='" . @$CsvToArray[8] . "', "; // ���� 2
            $sql.="price3='" . @$CsvToArray[9] . "', "; // ���� 3
            $sql.="price4='" . @$CsvToArray[10] . "', "; // ���� 4
            $sql.="price5='" . @$CsvToArray[11] . "', "; // ���� 5
            $sql.="items='" . @$CsvToArray[18] . "', "; // ����� @$CsvToArray[6]
            $sql.="stockgroup='" . (int)@$CsvToArray[19] . "', "; // ����� @$CsvToArray[6]
			
            // ����������� ������
            if (is_numeric($CsvToArray[16]) and $CsvToArray[16] == 1) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
                $sql.="parent='" . $CsvToArray[16] . "', ";
            }

            //if(!empty($CsvToArray[12]))
            //$sql.="weight='" . @$CsvToArray[12] . "', "; // ���

            $sql.="datas='" . date("U") . "' "; // ���� ���������

            $sql.=" where uid='" . $CsvToArray[0] . "'";
            /*
			ob_start();
			echo $sql;

			$page = ob_get_contents();
			ob_end_clean();
			$file = "/uploading.log";
			$fw = fopen($file, "w+");
			fputs($fw,$page, strlen($page));
			fclose($fw);
            */
            $result = mysql_query($sql);
            $this->ItemUpdate++;

            // ��������� �������� � �������
            if (!empty($CsvToArray[3])) {
                $last_id = $this->getIdForImages($CsvToArray[0]);
                $ready_num_img = $this->GetNumFoto($last_id);
                $img_num = $CsvToArray[5] - $ready_num_img;
                $img_num = +$ready_num_img;
                while ($img_num < $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . ($img_num + 1) . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysql_query($sql);
                    $img_num++;
                }
            }
            $sql = "SELECT vendor_array,vendor FROM " . $GLOBALS['SysValue']['base']['products']. " where uid='" . $CsvToArray[0] . "'"; //id=9317";
            $result = mysql_query($sql);
            $result=mysql_fetch_array($result);
            $resultvendor=$result[1];
            $result=unserialize($result[0]);
            //var_dump ($result,$resultvendor);
            // ��������� ��������������
            //if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and $this->ObjSystem->getSerilizeParam("1c_option.update_sort") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {
                //$resCharsArray = '';

                // ��������� �������������
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);
                $vendor = null;
                if (is_array($resCharsArray)) {
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                echo (string) stristr ( $resultvendor,'i186' );
                //���������������� ��� � ������ ������
                //������ �������
                //���� �� ���������� ���������� �� ��������� ��� � ������ 1,10,11
                if ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11){
                   if ((!stristr ( $resultvendor,'i186' ))) 
                       $resultvendor.="i186-1200i";
                   //��������� ���� ��������������
                   $result[186][0] = '1200';
                } else
                //���� ���������� ��������� �� ������� � ������ 0,2,3
                if ((int)$CsvToArray[19]==0 || (int)$CsvToArray[19]==2 || (int)$CsvToArray[19]==3){
                    if (stristr ( $resultvendor,'i186' ))
                        $resultvendor=str_replace ( "i186-1200i" , "" ,$resultvendor );
                   if (array_key_exists(186,$result))
                      $result[186] = array();
                   
                }
                //����� ������� 
                //var_dump($result,$resultvendor);
                
                $resSerialized = serialize($result);

                $sql = "UPDATE " . $this->TableName . " SET ";
                $sql.="vendor='" . $resultvendor. "', ";
                $sql.="vendor_array='" . $resSerialized . "' ";
                $sql.=" where uid='" . $CsvToArray[0] . "'";
                $result = mysql_query($sql);
            //}
        } else {
            // ������� ����� �����
            // �����
            switch ($this->Sklad_status) {

                case(3):
                    // 0- ��� ����� �� ����� + ��� ������ �� ������ ������ 
                    if ((int)$CsvToArray[19]==0){
                        $sklad = 1;
                        $p_enabled=0;
                        $yml=0;
                        //1,10,11,2,3 - � ������� �� ����� (��� ����� "��� �����") + ����� �� ������ ������� � ������� 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sklad = 0;
                        $p_enabled=1;
                        $yml=1;
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sklad = 0;
                        $p_enabled=0;
                        $yml=0;
                    }
                    $enabled=1;
                    break;

                case(2):
                    if ((int)$CsvToArray[18]==0){
                        $p_enabled=0;
                        $yml=0;
                        //1,10,11,2,3 - � ������� �� ����� (��� ����� "��� �����") + ����� �� ������ ������� � ������� 
                    } elseif ((int)$CsvToArray[18]==1 || (int)$CsvToArray[18]==10 || (int)$CsvToArray[18]==11 || (int)$CsvToArray[18]==2) {
                        $p_enabled=1;
                        $yml=1;
                    } elseif ((int)$CsvToArray[18]==3) {
                        $p_enabled=0;
                        $yml=0;
                    }
                    $enabled=1;
                    break;
                default:
                    $sklad = 0;
                    $p_enabled=1;
                    $enabled = 1;
                    $yml=1;
                    break;
            }

            // ��������� ��������������
            $vendor = null;
            $vendor_array = nyll;
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {
                $resCharsArray = null;

                // ��������� �������������
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);
                $resSerialized = serialize($resCharsArray);
                if (is_array($resCharsArray)) {
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                
                $vendor_array = serialize($resCharsArray);
            }

            $sql = "INSERT INTO " . $this->TableName . " SET ";

            // ��������������
            if (function_exists('mod_insert')) {
                $sql.=call_user_func_array('mod_insert', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            // ������������ ���������
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "',";

            $sql.="name='" . addslashes(trim($CsvToArray[1])) . "',
            description='" . addslashes($CsvToArray[2]) . "',
            content='" . addslashes($CsvToArray[4]) . "',
            price='" . $CsvToArray[7] . "',
            sklad='" . $sklad . "',
            p_enabled='" . $p_enabled . "',
            enabled='" . $enabled . "',
            uid='" . $CsvToArray[0] . "',
            yml='".$yml."',
            datas='" . date("U") . "',
            vendor='" . $vendor . "',
            vendor_array='" . $vendor_array . "',";

            if (!empty($CsvToArray[3]))
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',
            pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";

            // ����������� ������
            if (is_numeric($CsvToArray[16]) and $CsvToArray[16] == 1) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
                $sql.="parent='" . $CsvToArray[16] . "', ";
            }

            $sql.="items='" . $CsvToArray[18] . "',
            weight='" . $CsvToArray[12] . "',
            price2='" . $CsvToArray[8] . "',
            price3='" . $CsvToArray[9] . "',
            price4='" . $CsvToArray[10] . "',
            price5='" . $CsvToArray[11] . "',
            baseinputvaluta='" . $this->GetIdValuta[$CsvToArray[14]] . "',
            ed_izm='" . $CsvToArray[13] . "'";//$CsvToArray[6]
            $result = mysql_query($sql);
            $this->ItemCreate++;

            // ��������� �������� � �������
            $img_num = 1;
            if (!empty($CsvToArray[3])) {
                //$last_id=$this->getIdForImages($CsvToArray[0]);
                $last_id = mysql_insert_id();
                while ($img_num <= $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . $img_num . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysql_query($sql);
                    $img_num++;
                }
            }
        }
    }

}

// �������������� ����
if (preg_match("/[^(0-9)|(\-)]/", $_GET['date']))
    $date = "";
else
    $date = '16-11-2016-22-14';//$_GET['date'];

$path = "sklad";
$dir = $path . "/" . $date;

// ������ ���������
if ($_GET['create_category'] == "true")
    $ReadCsvCatalog = new ReadCsvCatalog($dir . "/tree.csv");
else
    $ReadCsvCatalog = false;

// ���������� ��������� ��������
$PS = new PHPShopSystem();
$upload='upload_0.csv';//$_GET['files'];//"all"
// ������� �����
if ($upload == "all" and is_dir($dir))
    if (@$dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

            if ($file != "." and $file != ".." and $file != "tree.csv")
                $list_file[] = $file;
        }
        closedir($dh);
    }
if (is_file("./" . $dir . "/" . $upload)) {
    $list_file[] = $upload;
}

// ������������
if (isset($error)) {
    if (is_array($list_file))
        $list_file[$error] = "";
}

if (is_array($list_file))
    foreach ($list_file as $val) {

        // �������� ������
        $time = explode(' ', microtime());
        $start_time = $time[1] + $time[0];

        //$fp = fopen($dir."/".$val, "r");
        $fp = file($dir . "/" . $val);
        if ($fp) {
            // ������ ����
            $ReadCsv = new ReadCsv1C($fp, $ReadCsvCatalog, $PS);
            $F_done.=$val . ";";
            $GetItemCreate+=$ReadCsv->GetItemCreate();
            $GetItemUpdate+=$ReadCsv->GetItemUpdate();
            $GetCatalogCreate+=$ReadCsv->GetCatalogCreate();

            // ���������
            if ($upload != "all")
                echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";

            // ��������� ������
            $time = explode(' ', microtime());
            $seconds = ($time[1] + $time[0] - $start_time);
            $seconds = substr($seconds, 0, 6);

            // ����� ���
            mysql_query("INSERT INTO " . $PHPShopBase->getParam('base.table_name12') . " VALUES ('','" . date("U") . "','$date','$val ','$seconds')");
        }
    }
else
    exit("�� ���� ��������� ���� " . $dir . "/" . $val);

if ($upload == "all")
    echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";
?>