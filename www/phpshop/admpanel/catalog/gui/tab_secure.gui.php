<?php
/**
 * ѕанель безопасности каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_secure($data) {
    global $SysValue;
    $secure_groups=$data['secure_groups'];
    
    $disp = ' аталог могут редактировать:<BR>';
    $sql = 'select * from ' . $SysValue['base']['table_name19'] . ' WHERE enabled="1"';
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    if ($num) {

        $disp.='<SCRIPT>
function enable_div2() {
if (document.getElementById("allusers").checked) {
	document.getElementById("regsel").disabled=true;
   } else {
	document.getElementById("regsel").disabled=false;
   }
}

</SCRIPT><DIV id="allreg">
&nbsp;&nbsp;&nbsp;
<input type="HIDDEN" name="9999" value="0">';

        if (strlen($secure_groups)) {
            $che = '';
        } else {
            $che = 'checked';
        }

        $disp.='<input type="checkbox" onClick="enable_div2()" id="allusers" name="seq[9999]" ' . $che . ' value="1">
<span name=txtLang id=txtLang>¬се, у кого есть права на ред. каталогов (снимите отметку, чтобы выбрать определенных пользователей)</span><BR>';

        $disp.='<DIV';
        if (!(strlen($secure_groups)))
            $disp.='disabled"'; $disp.='id="regsel" style="overflow-y:auto; height:280px;">';

        while ($row = mysql_fetch_array($result)) {
            if (strlen($secure_groups)) {
                $string = 'i' . $row['id'] . '-1i';
                if (strpos($secure_groups, $string) !== false) {
                    $che = 'checked';
                } else {
                    $che = '';
                }
            } else {
                $che = '';
            }

            if ($row['id'] == $_SESSION['idPHPSHOP']) {
                $che = 'checked';
                $amddis = 'disabled';
                $admval = '1';
                $admname = '<B>Ёто вы!</B> ';
            } else {
                $amddis = '';
                $admval = '0';
                $admname = '';
            }

            $disp.='&nbsp;&nbsp;&nbsp;
			<input type="HIDDEN" name="seq[' . $row['id'] . ']" value="' . $admval . '">
			<input type="checkbox" name="seq[' . $row['id'] . ']" ' . $che . ' ' . $amddis . ' value="1">' . $admname . $row['name'] . ' (login:' . $row['login'] . ',e-mail:' . $row['mail'] . ')<BR>';
        }
        $disp.='</DIV>';
    }
return $disp;
}

?>
