<?php

if (function_exists("start_debug")) start_debug(); 

if ($adm['news'] >= 1)
{
  include_once('style/tinyMCE/tinyMCE_header.php');

  if (!isset($_POST['save']))
  {
    $result = myquery("SELECT * FROM game_news WHERE id='".(int)$_GET['id']."' LIMIT 1");
    $edit = mysql_fetch_array($result);

    echo'<form action="" method="post">
    <table width=100% border="0">
    <tr><td>Тема</td><td><input name="theme" type="text" value="'.$edit['theme'].'" size="50" maxlength="50"></td></tr>';

    echo '<tr><td valign=top>Текст:</td><td>';
    ?>
    <textarea id="elm1" name="elm1" rows="25" cols="80" style="width: 100%">
    <?
    echo $edit['text'];
    ?>
    </textarea>
    <?

    echo'<tr><td colspan="2" align="center"><input name="save" type="submit" value="Отредактировать"></td></tr>
    </table>
    </form>';
  }
  else
  {
    $theme = mysql_real_escape_string($_POST['theme']);
    $value = mysql_real_escape_string($_POST['elm1']);
                
    $result = myquery("UPDATE game_news SET theme='".$theme."',text='".$value."' WHERE id='".(int)$_GET['id']."'");
    
    echo ("<center>Новость изменена</center>");
    $da = getdate();
    $log = myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
                   VALUES ('".$char['name']."','Отредактировал новость: <b>".$theme."</b><br> новость - :".$value."',
                   '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());
    
    echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=news">';
  }
}

if (function_exists("save_debug")) save_debug(); 

?>