<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['bot_combat'] >= 1)
{
  if(!isset($_GET['edit']) and !isset($_GET['new']) and !isset($_GET['delete']))
  {
    $pm=myquery("SELECT COUNT(*) FROM game_bot_combat");
    if (!isset($_GET['page']))
      $page=1;
    else
      $page = (int)$_GET['page'];

    $line=25;
    $allpage=ceil(mysql_result($pm,0,0)/$line);
    if ($page>$allpage) $page=$allpage;
    if ($page<1) $page=1;

    echo "<table border=0 cellspacing=3 cellpadding=3>";
    echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bot_combat&new>�������� ������</a></td></tr>";
    echo "<tr bgcolor=#333333><td>ID</td><td>�����</td><td></td></tr>";
    $qw=myquery("SELECT * FROM game_bot_combat order BY id ASC limit ".(($page-1)*$line).", $line");
    while($ar=mysql_fetch_array($qw))
    {
      echo '<tr>
      <td><a href=admin.php?opt=main&option=bot_combat&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
      <td>'.$ar['text'].'</td>
      <td><a href=admin.php?opt=main&option=bot_combat&delete='.$ar['id'].'>������� ������</a></td>
      </tr>';
    }
    echo'</table>';
    $href = '?opt=main&option=bot_combat&';
    echo'<center>��������: ';
    show_page($page,$allpage,$href);
  }

  if(isset($_GET['edit']))
  {
    if (!isset($_POST['save']))
    {
      $qw=myquery("SELECT * FROM game_bot_combat where id=".(int)$_GET['edit'].";");
      $ar=mysql_fetch_array($qw);
      echo'<form action="" method="post">
      �����: <br>(��������� ����.�������-����������:<br>
      %%name - ��� ������<br>
      %%race - ���� ������<br>
      %%npc - ��� NPC<br>
      <textarea name=text cols=60 rows=25>'.$ar['text'].'</textarea><br><br>
      <input name="save" type="submit" value="���������"><input name="save" type="hidden" value="">';
    }
    else
    {
      echo'������ ��������';
      $up=myquery("update game_bot_combat set text='".htmlspecialchars($_POST['text'])."' where id=".(int)$_GET['edit'].";");
      $da = getdate();
      $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
          VALUES ('".$char['name']."','������� ������� ���� � ��� �� : <b>".htmlspecialchars($_POST['text'])."</b>',
          '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());
      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
    }
  }


  if(isset($_GET['new']))
  {
    if (!isset($_POST['save']))
    {
      echo'<form action="" method="post">
      �����: <br>(��������� ����.�������-����������:<br>
      %%name - ��� ������<br>
      %%race - ���� ������<br>
      %%npc - ��� NPC<br>
      <textarea name=text cols=60 rows=25 value=""></textarea><br><br>
      <input name="save" type="submit" value="�������� ������"><input name="save" type="hidden" value="">';
    }
    else
    {
      echo'������ ���������';
      $up=myquery("insert into game_bot_combat (text) VALUES ('".htmlspecialchars($_POST['text'])."')");
      $da = getdate();
      $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
      VALUES (
      '".$char['name']."',
      '������� � ������� ���� � ��� ����� : <b>".htmlspecialchars($_POST['text'])."</b>',
      '".time()."',
      '".$da['mday']."',
      '".$da['mon']."',
      '".$da['year']."')")
      or die(mysql_error());
      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
    }
  }

  if(isset($_GET['delete']))
  {
    echo'������ �������';
    $text = @mysql_result(@myquery("SELECT text FROM game_bot_combat WHERE id=".(int)$_GET['delete'].";"),0,0);
    $da = getdate();
    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
     VALUES (
     '".$char['name']."',
     '������ �� ������� ���� � ��� ����� : <b>".htmlspecialchars($text)."</b>',
     '".time()."',
     '".$da['mday']."',
     '".$da['mon']."',
     '".$da['year']."')")
       or die(mysql_error());
        $up=myquery("delete from game_bot_combat where id=".(int)$_GET['delete'].";");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bot_combat">';
  }
}

if (function_exists("save_debug")) save_debug(); 

?>