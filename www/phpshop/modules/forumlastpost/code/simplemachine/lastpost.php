<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
    <head>
        <title>Форум</title>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <style>
            td {
                font-size: 12px;
                color: #696969;
            }
            a{
                color: #35A0C4;
                padding-left: 0px;
            }

            .post{
                color: f04a35;
                font-weight: bolder;
            }
        </style>
    </head>

    <body>

        <?

        include("Settings.php");
        mysql_connect ($db_server, $db_user, $db_passwd) or die("Невозможно подсоединиться к базе");
        mysql_select_db($db_name) or die("Невозможно подсоединиться к базе");
        mysql_query("SET NAMES 'cp1251'");

        function dataV($nowtime) {
            $Months = array("01"=>"января","02"=>"февраля","03"=>"марта",
                    "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
                    "08"=>"августа","09"=>"сентября",  "10"=>"октября",
                    "11"=>"ноября","12"=>"декабря");
            $curDateM = date("m",$nowtime);
            $t=date("d",$nowtime)."-".$curDateM."-".date("y",$nowtime)." ".date("H:s ",$nowtime);
            return $t;
        }

        function Total($id) {
            $sql="select pid from ibf_posts where topic_id=$id";
            $result=mysql_query($sql);
            $num = mysql_numrows($result);
            return $num;
        }


        if(empty($_GET['n'])) $limit=7;
        else $limit=htmlspecialchars(stripslashes($_GET['n']));


        $sql="select * from ".$db_prefix."messages  order by posterTime desc limit ".$limit;
        $result=mysql_query($sql);
        while($row = mysql_fetch_array($result)) {
            $name = $row['subject'];
            $last_post = $row['posterTime'];
            $last_poster_name = $row['posterName'];
            $description = $row['description'];
            $posts = $row['posts'];
            $last_id=$row['ID_TOPIC'];
            $id = $row['ID_MSG'];
            @$disp.='
<TR><TD width="20" class="post">
<img src="./comment.gif" alt="" border="0"> '.$posts.'</TD>
<TD>'.dataV($last_post).'  |  <img src="./icon-client.gif" alt="" border="0"> <b>'.$last_poster_name.'</b><BR>
<DIV><A title="'.$name.'" href="./index.php?topic='.$last_id.'.msg'.$id.'#msg'.$id.'" target="_blank">'.$name.'</A>
</DIV></TD>
</TR>
';
        }



        echo "<table>$disp</table>";

        ?>
    </body>
</html>