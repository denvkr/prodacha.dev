<?php
/**
 * Панель сортировок каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_sorts($data) {
    global $SysValue;
    
    $sort=unserialize($data['sort']);
    
    $dis = null;
    $sql = "select * from " . $SysValue['base']['sort_categories'] . " where category=0 order by num";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $sel = "";
        if (is_array($sort))
            foreach ($sort as $v) {
                if ($id == $v)
                    $sel = "selected";
            }
        $dis.="
	<optgroup label=\"$name\">
	" . tab_sorts_val($id, $sort) . "
	</optgroup>
	";
    }
    $disp = "<select name=sort_new[] size=1 style=\"width: 100%;height:420px \" multiple>$dis</select>";
    return $disp;
}

function tab_sorts_val($n, $sort) {
    global $SysValue;
    $dis=null;
    $sql = "select * from " . $SysValue['base']['sort_categories'] . " where category=$n order by num";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = substr($row['name'], 0, 35);
        $sel = "";
        if (is_array($sort))
            foreach ($sort as $v) {
                if ($id == $v)
                    $sel = "selected";
            }
        $dis.="<option value=" . $id . " " . $sel . ">" . $name . "</option>\n";
    }
    return $dis;
}

?>