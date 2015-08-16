<?

/*
  +-------------------------------------+
  |  PHPShop Enterprise                 |
  |  Модуль ResultUrl WebMoney          |
  +-------------------------------------+
 */

function WriteLog($MY_LMI_HASH) {
    global $mrh_pass2, $REQUEST_URI, $REMOTE_ADDR, $_POST;
    $handle = fopen("../paymentlog.log", "a+");

    foreach ($_POST as $k => $v)
        @$post.=$k . "=" . $v . "\r\n";


    $str = "
  WebMoney Payment Start ------------------
  date=" . date("F j, Y, g:i a") . "
  $post
  MY_LMI_HASH=$MY_LMI_HASH
  REQUEST_URI=$REQUEST_URI
  IP=$REMOTE_ADDR
  WebMoney Payment End --------------------
  ";
    fwrite($handle, $str);
    fclose($handle);
}

function UpdateNumOrder($uid) {
    $last_num = substr($uid, -2);
    $total = strlen($uid);
    $ferst_num = substr($uid, 0, ($total - 2));
    return $ferst_num . "-" . $last_num;
}

// Парсируем установочный файл
$SysValue = parse_ini_file("../../phpshop/inc/config.ini", 1);
while (list($section, $array) = each($SysValue))
    while (list($key, $value) = each($array))
        $SysValue['other'][chr(73) . chr(110) . chr(105) . ucfirst(strtolower($section)) . ucfirst(strtolower($key))] = $value;

// as a part of ResultURL script
// your registration data

$LMI_SECRET_KEY = $SysValue['webmoney']['LMI_SECRET_KEY'];

@extract($_POST);

// build own CRC
$HASH = $LMI_PAYEE_PURSE . $LMI_PAYMENT_AMOUNT . $LMI_PAYMENT_NO . $LMI_MODE . $LMI_SYS_INVS_NO . $LMI_SYS_TRANS_NO . $LMI_SYS_TRANS_DATE . $LMI_SECRET_KEY . $LMI_PAYER_PURSE . $LMI_PAYER_WM;
$MY_LMI_HASH = strtoupper(md5("$HASH"));

if (strtoupper($MY_LMI_HASH) != strtoupper((string)$LMI_HASH)) {
    echo "bad sign\n";
    WriteLog($MY_LMI_HASH);
    exit();
} else {
// perform some action (change order state to paid)
// Подключаем базу MySQL
    @mysql_connect($SysValue['connect']['host'], $SysValue['connect']['user_db'], $SysValue['connect']['pass_db']) or
            @die("" . PHPSHOP_error(101, $SysValue['my']['error_tracer']) . "");
    mysql_select_db($SysValue['connect']['dbase']) or
            @die("" . PHPSHOP_error(102, $SysValue['my']['error_tracer']) . "");

    $new_uid = UpdateNumOrder($LMI_PAYMENT_NO);


// Приверяем сущ. заказа
    $sql = "select uid from " . $SysValue['base']['table_name1'] . " where uid='$new_uid'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $uid = $row['uid'];

    if ($uid == $new_uid) {
// Записываем платеж в базу
        $sql = "INSERT INTO " . $SysValue['base']['table_name33'] . " VALUES 
('$LMI_PAYMENT_NO','WebMoney, $LMI_PAYER_PURSE, WMId$LMI_PAYER_WM','$LMI_PAYMENT_AMOUNT','" . date("U") . "')";
        $result = mysql_query($sql);
        WriteLog($MY_LMI_HASH);
// print OK signature
        echo "OK$LMI_PAYMENT_NO\n";
    } else {
        WriteLog($MY_LMI_HASH);
        echo "bad order num\n";
        exit();
    }
}
?>
