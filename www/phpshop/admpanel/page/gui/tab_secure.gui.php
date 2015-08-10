<?php

/**
 * Панель безопасности каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_secure($data) {

    if ($data['secure'] == 1)
        $sel4 = "checked";

    $secure_groups = $data['secure_groups'];

    $disp = '

    <SCRIPT>
    function enable_div1() {
        if (document . getElementById(\'secure_new\') . checked) {
            document.getElementById(\'allreg\').disabled = false;
            document.getElementById(\'allusers\').checked = true;
        } else {
            document.getElementById(\'allreg\').disabled = true;
        }
    }

    function enable_div2() {
        if (document . getElementById(\'allusers\') . checked) {
            document.getElementById(\'regsel\').disabled = true;
        } else {
            document.getElementById(\'regsel\').disabled = false;
        }
    }

    </SCRIPT>
    	<FIELDSET id = fldLayout >
   
    <input type="checkbox" id="secure_new" name="secure_new" onClick="enable_div1()" value="1" ' . $sel4 . '> <span name=txtLang id=txtLang>Показывать только зарегистрированным пользователям</span><BR>';

    $sql = 'select id,name from ' . $GLOBALS['SysValue']['base']['shopusers_status'] . ' WHERE enabled="1"';
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    if ($num) {

        $disp.='<DIV';
        if ($sel4 !== "checked")
            $disp.="disabled"; $disp.=' id="allreg">
            <span name=txtLang id=txtLang>Из зарегистрировавшихся показывать:</span><BR>
            &nbsp;&nbsp;&nbsp;
            <input type="HIDDEN" name="9999" value="0">';

        if (strlen($secure_groups)) {
            $che = '';
        } else {
            $che = 'checked';
        }

        $disp.='<input type="checkbox" onClick="enable_div2()" id="allusers" name="seq[9999]" ' . $che . ' value="1"><span name=txtLang id=txtLang>Всем пользователям (снимите отметку, чтобы выбрать определенные группы)</span><BR>

            <DIV';
        if (!(strlen($secure_groups)))
            $disp.="disabled"; $disp.=' id="regsel" style="overflow-y:auto; height:280px;">
                <BR>';

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
            $disp.='&nbsp;&nbsp;&nbsp;
			<input type="HIDDEN" name="seq[' . $row['id'] . ']" value="0">
			<input type="checkbox" name="seq[' . $row['id'] . ']" ' . $che . ' value="1">' . $row['name'] . '<BR>';
        }

        $disp.='</DIV>
</DIV>';
    }
    return $disp;
}
?>
