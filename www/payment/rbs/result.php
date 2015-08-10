<?
exit('exit');
/*
  +-------------------------------------+
  |  PHPShop 2.1 Enterprise             |
  |  Модуль ResultUrl RBS               |
  +-------------------------------------+
 */

function WriteLog($MY_LMI_HASH) {
    global $REQUEST_URI, $REMOTE_ADDR, $_POST;
    $handle = fopen("../paymentlog.log", "a+");

    foreach ($_POST as $k => $v)
        @$post.=$k . "=" . $v . "\r\n";


    $str = "
  RBS Payment Start ------------------
  date=" . date("F j, Y, g:i a") . "
  $post
  MY_LMI_HASH=$MY_LMI_HASH
  REQUEST_URI=$REQUEST_URI
  IP=$REMOTE_ADDR
  RBS Payment End --------------------
  ";
    fwrite($handle, $str);
    fclose($handle);
}

function UpdateNumOrder($uid) {
    $all_num = explode("-", $uid);
    $ferst_num = $all_num[0];
    $last_num = $all_num[1];
    return $ferst_num . $last_num;
}

// Парсируем установочный файл
$SysValue = parse_ini_file("../../phpshop/inc/config.ini", 1);
while (list($section, $array) = each($SysValue))
    while (list($key, $value) = each($array))
        $SysValue['other'][chr(73) . chr(110) . chr(105) . ucfirst(strtolower($section)) . ucfirst(strtolower($key))] = $value;

// as a part of ResultURL script
// your registration data

$MERCHANTNUMBER = $SysValue['rbs']['MERCHANTNUMBER'];    //кошелек
$MERCHANTPASSWD = $SysValue['rbs']['MERCHANTPASSWD'];
$KEY = $SysValue['rbs']['KEY'];

/*
$S = base64_decode($my);
parse_str($S);
*/

// build own CRC
$HASH = $MERCHANTNUMBER . $MERCHANTPASSWD . $id . $KEY;
$MY_HASH = substr(strtoupper(md5("$HASH")), 0, 10);

if (strtoupper($MY_HASH) != strtoupper((string)$h)) {
    WriteLog($MY_HASH);
    header("Location: /fail/");
    exit();
} else {
// perform some action (change order state to paid)
// Подключаем базу MySQL
    @mysql_connect($SysValue['connect']['host'], $SysValue['connect']['user_db'], $SysValue['connect']['pass_db']) or
            @die("" . PHPSHOP_error(101, $SysValue['my']['error_tracer']) . "");
    mysql_select_db($SysValue['connect']['dbase']) or
            @die("" . PHPSHOP_error(102, $SysValue['my']['error_tracer']) . "");

    $new_uid = UpdateNumOrder($id);

// Приверяем сущ. заказа
    $sql = "select uid from " . $SysValue['base']['table_name1'] . " where uid='$new_uid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $uid = $row['uid'];

    if ($uid == $new_uid) {
// Записываем платеж в базу
        $sql = "INSERT INTO " . $SysValue['base']['table_name33'] . " VALUES 
('$new_uid','RBS','$s','" . date("U") . "')";
        $result = mysql_query($sql);
        WriteLog($MY_LMI_HASH);
        $id_hash = base64_encode(substr(abs(crc32(uniqid($id))), 0, 2) . base64_encode($id) . substr(abs(crc32(uniqid($id))), 0, 5));
        header("Location: /success/?inv=$id_hash");
        exit();
    } else {
        WriteLog($MY_LMI_HASH);
        header("Location: /fail/");
        exit();
    }
}
?>
