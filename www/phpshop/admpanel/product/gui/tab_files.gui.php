<?php

/**
 * ѕанель файлов к товару
 * @param array $row массив данных
 * @return string 
 */
function tab_files($row) {
    $files = unserialize($row['files']);
    $disp = '
<table width="100%">
<tr>
  <td>

        <SCRIPT language=JavaScript>
            var numb;
            numb = ' . (count($files) - 1) . ';
            function add_new_row() {
                var currrow;
                var d=document.getElementById("tbl");
                currow = d.rows.length; // вычислить количество строк в таблице
                numb++;
                fid="filenum"+numb;
                d.insertRow(currow); // добавл€ем строку в таблицу
                d.rows[currow].insertCell(0); // добавл€ем €чейки
                d.rows[currow].insertCell(1);
                d.rows[currow].insertCell(2);

                d.rows[currow].cells[0].className="";
                d.rows[currow].cells[1].className="";
                d.rows[currow].cells[2].className="";

                d.rows[currow].cells[0].style.padding="5px";
                d.rows[currow].cells[0].innerHTML = \'<INPUT TYPE=TEXT style="width:90%;" name="filenum[]" id="filenum\'+numb+\'"><BUTTON style="width: 50px;" onclick="ReturnPic(fid);return false;"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>\';
                d.rows[currow].cells[1].innerHTML = \'<BUTTON onclick="remove_row();"><img src="../icon/wand.gif"  width="16" height="16" border="0" align="absmiddle"> -</BUTTON>\';

            }

            function remove_row() {
                var currrow;
                numb--;
                currow = document.getElementById("tbl").rows.length-1;
                document.getElementById("tbl").deleteRow(currow);
            }
        </SCRIPT>
               

<TABLE id="tbl" style="width:100%; border:1px solid gray; background:#ffffff;" CELLPADDING="0" CELLSPACING=0>
<TR>
<TD id=pane style="width: 90%;">‘айл</TD>
<TD><BUTTON  onclick="add_new_row();return false;">
<img src="../icon/wand.gif"  width="16" height="16" border="0" align="absmiddle"> +</BUTTON>
</TD>
</TR>
';

    if (is_array($files))
        foreach ($files as $num => $cfile) {
            $disp.='
<TR><TD style="padding:5px">
<INPUT TYPE=TEXT style="width:90%;" name="filenum[]" id="filenum' . $num . '" value="' . $cfile . '">
<BUTTON style="width: 50px;" 
onclick="ReturnPic(\'filenum' . $num . '\',0);return false;">
<img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
</TD><TD>
<BUTTON 
onclick="remove_row();return false;">
<img src="../icon/wand.gif"  width="16" height="16" border="0" align="absmiddle"> -</BUTTON>
</TD></TR>
';
        }
    $disp.='
</TABLE>

  </td>
</tr>
</table>
';
    return $disp;
}

?>