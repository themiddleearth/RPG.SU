<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['mag'] >= '1')
{
  if (isset($_GET['del']))
  {
    echo'�������';
    $name = mysql_result(myquery("SELECT name FROM game_mag WHERE id='".$_GET['del']."'"),0,0);
    $da = getdate();
    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
     VALUES (
     '".$char['name']."',
     '������ �� ������ ����� �������: <b>".$name."</b>',
     '".time()."',
     '".$da['mday']."',
     '".$da['mon']."',
     '".$da['year']."')")
       or die(mysql_error());
    $del=myquery("delete from game_mag where id='".$_GET['del']."'");
    echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=mag">';
  }

  if (isset($_GET['edit']))
  {
    if (!isset($_POST['see']))
    {
      echo'<center><form action="" method="post">
      <font size="1" face="Verdana" color="#ffffff">';

      echo'<table width=70% border=0 align=left>';

      $kto=myquery("select * from game_mag where id=".$_GET['edit']."");
      $mag=mysql_fetch_array($kto);

      echo'<tr><td align=right>���</td><td>'.$mag['name'].'</td></tr>';

      echo'<tr><td align=right>��� � ������: </td><td><select name="gorod"><option value="0"';
      if ($mag['town']==0) echo ' selected';
      echo '>����� ��� ����</option>';
      $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
      while($t=mysql_fetch_array($result))
      {
        echo '<option value="'.$t['town'].'"';
        if ($mag['town']==$t['town']) echo 'selected';
          echo '>'.$t['rustown'].'</option>';
      }
      echo '</select></td></tr>';

      $status2 = $mag['status'];

      echo'<tr><td align=right>������ ��������</td><td><input name="mol" type="checkbox" value="1"'; if($mag['mol']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ ��������</td><td><input name="izgn" type="checkbox" value="1"'; if($mag['izgn']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ ����������</td><td><input name="obn" type="checkbox" value="1"'; if($mag['obn']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ �������</td><td><input name="slep" type="checkbox" value="1"'; if($mag['slep']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="prok" type="checkbox" value="1"'; if($mag['prok']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="teleport" type="checkbox" value="1"'; if($mag['teleport']==1) echo'checked'; echo'></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="lab" type="checkbox" value="1"'; if($mag['lab']==1) echo'checked'; echo'></td></tr>';

      echo '<tr><td align=right>������ ����</td><td><input name="status" value="'.$status2.'" type="text" size="60"></td></tr>';


      echo'<tr><td align=right><input name="submit" type="submit" value="���������"><input name="see" type="hidden" value=""></td></tr>';
      echo'</table></form>';
    }
    else
    {
      if(!isset($_POST['mol']))      $_POST['mol']='0';
      if(!isset($_POST['izgn']))     $_POST['izgn']='0';
      if(!isset($_POST['obn']))      $_POST['obn']='0';
      if(!isset($_POST['slep']))     $_POST['slep']='0';
      if(!isset($_POST['prok']))     $_POST['prok']='0';
      if(!isset($_POST['teleport'])) $_POST['teleport']='0';
      if(!isset($_POST['lab']))      $_POST['lab']='0';

      $up=myquery("UPDATE game_mag SET
                  mol='".$_POST['mol']."',
                  izgn='".$_POST['izgn']."',
                  obn='".$_POST['obn']."',
                  slep='".$_POST['slep']."',
                  prok='".$_POST['prok']."',
                  teleport='".$_POST['teleport']."',
                  lab='".$_POST['lab']."',
                  status='".$_POST['status']."',
                  town='".$_POST['gorod']."'
                  WHERE id='".$_GET['edit']."'");
      echo '<br>������';
      $name = mysql_result(myquery("SELECT name FROM game_mag WHERE id=".$_GET['edit'].""),0,0);
      $da = getdate();
      $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
       VALUES (
       '".$char['name']."',
       '������� ����: <b>".$name."</b>',
       '".time()."',
       '".$da['mday']."',
       '".$da['mon']."',
       '".$da['year']."')")
         or die(mysql_error());
      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=mag">';
    }
  }

  if(!isset($_GET['edit']) and !isset($_GET['new']) and !isset($_GET['del']))
  {
    $ktoo=myquery("select * from game_mag ORDER BY name ASC");
    echo '<center><table>';
    $i=0;
    while($stat=mysql_fetch_array($ktoo))
    {
      $i++;
      if ($stat['town']==0)
      {
        $rustown = '����� ��� ����';
      }
      else
      {
        $sel = myquery("SELECT rustown FROM game_gorod WHERE town='".$stat['town']."' LIMIT 1");
        list($rustown)=mysql_fetch_array($sel);
      }
      if ($i==1) echo '<tr bgcolor=#000040>';
      else {$i=0;echo '<tr bgcolor=#000024>';};
      echo '<td><b>'.$stat['name'].'</b></td><td>'.$rustown.'</td><td><a href=?opt=main&option=mag&edit='.$stat['id'].'>�������������</a></td><td><a href=?opt=main&option=mag&del='.$stat['id'].'>�������</a></td></tr>';
    }
    echo'</table>';
    echo'<a href="?opt=main&option=mag&new">��������</a>';
  }

  if(isset($new))
  {
    if (!isset($see))
    {
      echo'<div id="content" onclick="hideSuggestions();"><center><form action="" method="post">
      <font size="1" face="Verdana" color="#ffffff">';

      echo'<table border=0 align=left>';

      echo'<tr><td align=right>���</td><td><input name="name_v" value="" type="text" size="30" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>';

      echo'<tr><td align=right>��� � ������: </td><td><select name="gorod"><option value="0">����� ��� ����</option>';
      $result = myquery("SELECT town,rustown FROM game_gorod where rustown<>'' ORDER BY rustown");
      while($t=mysql_fetch_array($result))
      {
        echo '<option value="'.$t['town'].'"';
        echo '>'.$t['rustown'].'</option>';
      }
      echo '</select></td></tr>';

      echo'<tr><td align=right>������ ��������</td><td><input name="mol" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ ��������</td><td><input name="izgn" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ ����������</td><td><input name="obn" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ �������</td><td><input name="slep" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="prok" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="teleport" type="checkbox" value="1"></td></tr>';
      echo'<tr><td align=right>������ ���������</td><td><input name="lab" type="checkbox" value="1"></td></tr>';

      echo '<tr><td align=right>������ ����</td><td><input name="status" type="text" size="60"></td></tr>';


      echo'<tr><td align=right><input name="submit" type="submit" value="���������"><input name="see" type="hidden" value=""></td></tr>';
      echo'</table></form></div><script>init();</script>';
    }
    else
    {
      $sel = myquery("SELECT * FROM game_users WHERE name='$name_v'");
      if (!mysql_num_rows($sel)) $sel = myquery("SELECT * FROM game_users_archive WHERE name='$name_v'");
      if (mysql_num_rows($sel))
      {
        if(!isset($mol)) $mol='0';
        if(!isset($izgn)) $izgn='0';
        if(!isset($obn)) $obn='0';
        if(!isset($slep)) $slep='0';
        if(!isset($prok)) $prok='0';
        if(!isset($teleport)) $teleport='0';
        if(!isset($lab)) $lab='0';

        $up=myquery("insert into game_mag set
        name='$name_v',
        mol='$mol',
        izgn='$izgn',
        obn='$obn',
        slep='$slep',
        prok='$prok',
        teleport='$teleport',
        lab='$lab',
        status='$status',
        town='$gorod'");

        $da = getdate();
        $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
         VALUES (
         '".$char['name']."',
         '������� ������ ����: <b>".$name_v."</b>',
         '".time()."',
         '".$da['mday']."',
         '".$da['mon']."',
         '".$da['year']."')")
           or die(mysql_error());
        
        echo '<br>������';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?option=mag&opt=main">';
      }
      else
      {
        echo '<br>����� �� ������';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?option=mag&opt=main">';
      }
    }
  }
  echo'<center><a href="?opt=main&option=mag">�������</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>