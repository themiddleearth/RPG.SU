<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
    if (isset($town_id) AND $town_id!=$town)
    {
    echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
    }

$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
    $userr = mysql_fetch_array($userban);
    $min = ceil(($userr['time']-time())/60);
    echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться портом!';
    {if (function_exists("save_debug")) save_debug(); exit;}
}

$img='http://'.img_domain.'/race_table/human/table';
$width='100%';
$height='100%';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';
echo'<img src="http://'.img_domain.'/gorod/ship.jpg">';
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';


echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

if (isset($_POST['bil']))
{
    $bil=(int)$_POST['bil'];
    $sel=myquery("select * from game_port where id='$bil'");
    if ($sel!=false AND mysql_num_rows($sel)>0)
    {
        $q=mysql_fetch_array($sel);
        $cena=$q['cena'];

        if ($char['GP']-$cena >= 0)
        {
            $da = getdate();
            $tm_bil = explode(":",$q['time']);
            $datestamp = mktime($tm_bil[0],$tm_bil[1],0,$da['mon'],$da['mday'],$da['year']);
            myquery("DELETE FROM game_port_bil WHERE user_id='$user_id'");
            $sel=myquery("insert into game_port_bil (user_id, bil, buydate) values ('$user_id','$bil','$datestamp')");
            if (mysql_insert_id()>0)
            {
                $up=myquery("update game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id='".$char['user_id']."' and GP-$cena >='0' limit 1");
                setGP($user_id,-$cena,51);
                echo'<center><font color=ff0000>Билет куплен! Обязательно приходи до <b>'.$q['time'].'</b>!!!,<br> иначе корабль уплывет без тебя!</font></center>';
            }
            else
            {
                echo'<center><font color=ff0000>Произошла ошибка в программе! Просим обратиться к администраторам и сообщить им о следующей ошибке: &quot;'.mysql_error().'&quot;!</font></center>';
            }
        }
        else
        {
            echo'<center><font color=ff0000>У тебя не хватает денег!</font></center>';
        }
    }
}
$sell=myquery("select * from game_port_bil where user_id='".$char['user_id']."' and stat='0'");
if (mysql_num_rows($sell)==0)
{
    echo'<br /><form action="" method="post">&nbsp;&nbsp;&nbsp;Сегодняшние рейсы:<br><br />';

    $query = "SELECT `game_port`.`id`, `game_port`.`time`, `game_port`.`dlit`, `game_port`.`cena`, `game_port`.`nazv`, `game_port`.`town_kuda`, ".
                    "`game_gorod`.`rustown`, `game_maps`.`name` as `map_name`, `game_map`.`xpos`, `game_map`.`ypos`".
             "FROM `game_port` ".
             "RIGHT JOIN `game_gorod` ON `game_gorod`.`town` = `game_port`.`town_kuda`" .
             "RIGHT JOIN `game_map`   ON `game_map`.`town`   = `game_port`.`town_kuda` AND `game_map`.`to_map_name` = 0 " .
             "RIGHT JOIN `game_maps`  ON `game_maps`.`id`   = `game_map`.`name`" .
             "WHERE `town_from`=".$town." and `time`>='".date("H:i")."' ORDER BY `time`;";

    $sel = myquery($query);

    if(mysql_num_rows($sel) > 0)
    {
        while($row = mysql_fetch_assoc($sel))
        {
           $kuda = '<font color=#FFFF80>'.$row['rustown'].'</font> ('.$row['map_name'].' '.$row['xpos'].','.$row['ypos'].')';
           echo('&nbsp;&nbsp;&nbsp;<label><input type="radio" name="bil" value="'.$row['id'].'"><b><font color=#FFFF80>'.$row['nazv'].'</font></b> отплывает в '.$kuda.' в '.$row['time'].' (Время прибытия в '.$row['dlit'].' ч. <b> Цена билета: <font color=#FFFFFF>'.$row['cena'].'</font></b></a>)</label><br>');
        }
        echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="kup" type="submit" value="Купить билет"><input name="town_id" type="hidden" value="'.$town.'"> ';
    }
    else
    {
        echo'<center>На сегодня рейсов больше нет</center>';
    }
}
else
{
    echo'<center><font color=ff0000><b>Ты '.echo_sex('купил','купила').' билет</b></font>!';
}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';


if (mysql_num_rows($sell)>0)
{
    echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';
    $qq=mysql_fetch_array($sell);
    $selll=myquery("select * from game_port where id='".$qq['bil']."'");
    $qqq=mysql_fetch_array($selll);
    if (time()>($qq['buydate']+120))
    {
        echo'Ты '.echo_sex('опоздал','опоздала').' на корабль';
        $up=myquery("delete from game_port_bil where user_id='".$char['user_id']."'");
    }
    else
    {
        if (time()>=$qq['buydate'])
        {
            $up=myquery("update game_port_bil set stat=1 where user_id='".$char['user_id']."'");
            $update=myquery("DELETE FROM game_chat_log WHERE(user_id='".$char['user_id']."' AND town='$town')");
            $mapid = map_sea_id;
            $up=myquery("update game_users_map set map_name='$mapid', map_xpos='0', map_ypos='0' where user_id='$user_id'");
            echo'<script>location.href="'."../act.php?func=main\"".'</script>';
        }
        $kuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$qqq['town_kuda']."'"),0,0);
        $map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$qqq['town_kuda']."' and to_map_name=0"));
        $map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
        $kuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
        echo'<meta http-equiv="refresh" content="10">';
        echo'<center><font color=ff0000><b>Сейчас '.date("H:i").'</font></b><br>У тебя есть билет на одну поездку в '.$kuda.'. <br />Отправка ровно в '.$qqq['time'].'! <br />Билеты не возвращаются. Не опаздывай к отплытию!';
    }
    echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
}

if (function_exists("save_debug")) save_debug(); 

?>