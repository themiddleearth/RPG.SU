<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['help'] >= 1)
{
    include_once('style/tinyMCE/tinyMCE_header.php');

	if (!isset($_GET['new']) and !isset($_GET['edit']) and !isset($_GET['delete_razdel']) and !isset($_GET['delete_kateg']))
    {
        echo '<table border=0 width=90%><td><td align=left>';
        $q=myquery("select DISTINCT kateg from game_help order by id");
        while($h=mysql_fetch_array($q))
        {
        	echo'<li><b>'.$h['kateg'].'     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="admin.php?opt=main&option=help&delete_kateg='.$h['kateg'].'">������� ��� ���������</a>]</li><ol>';
            $qq=myquery("select id, name from game_help where kateg='".$h['kateg']."'");
            while($hh=mysql_fetch_array($qq))
            {
            	echo'<a href="admin.php?opt=main&option=help&edit='.$hh['id'].'"><li>'.$hh['name'].'</a>     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="admin.php?opt=main&option=help&delete_razdel='.$hh['id'].'">������� ������</a>]</li>';
            }
            echo'</ol>';
        }
        echo'</td></tr></table>';

        echo'<br><a href="admin.php?opt=main&option=help&new">��������</a>';
    }

	if (isset($_GET['delete_razdel']))
    {
      echo'������ ������';
      list($name) = mysql_fetch_array(myquery("SELECT name FROM game_help WHERE id='".(int)$_GET['delete_razdel']."'")); 
      $da = getdate();
      $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
                    VALUES ('".$char['name']."', '������ ������ ������: <b>".$name."</b>',
                   '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());
      $up=myquery("delete from game_help where id='".(int)$_GET['delete_razdel']."'");
      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=help">';
    }

 	if (isset($_GET['delete_kateg']))
    {
        echo'��������� �������';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 '������ ��������� ������: <b>".(int)$_GET['delete_kateg']."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_help where kateg='".(int)$_GET['delete_kateg']."'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=help">';
    }

	if (isset($_GET['edit']))
    {
    	if (!isset($_POST['save']))
      {
        $result=myquery("select * from game_help where id='".(int)$_GET['edit']."'");
        $help=mysql_fetch_array($result);

		    echo "<form action=\"\" method=post>";
		    echo "<table width=100% border=0 cellspacing=3 cellpadding=3 align=left>";
		    echo "<tr><td>���������:</td><td><input type=text name=kateg value='".$help['kateg']."' size=40></td></tr>";
		    echo "<tr><td>����:</td><td><input type=text name=name value='".$help['name']."' size=80></td></tr>";

		    echo '<tr><td valign=top>�����:</td><td>';
            ?>
            <textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
            <?
            echo $help['text'];
            ?>
            </textarea>
            <?
            
		    echo '<tr><td></td><td><input name="save" type="submit" value="���������"><input name="save" type="hidden" value=""></td></tr>';
		    echo '</table>';
		    echo '</form>';
      }
      else
      {
        echo'���������';
        $da = getdate();
        $log = myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
                        VALUES ('".$char['name']."', '������� ������ ������: <b>".$_POST['name']."</b> (��������� - ".$_POST['kateg'].")',
                       '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());

        $up = myquery("update game_help set kateg='$kateg', name='".$_POST['name']."', text='".$_POST['elm1']."' where id='".(int)$_GET['edit']."'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=help">';
    }
  }

	if (isset($_GET['new']))
  {
    if (!isset($_POST['save']))
    {
      echo "<form action=\"\" method=post>";
      echo "<table width=100% border=0 cellspacing=3 cellpadding=3 align=left>";
      echo "<tr><td>���������:</td><td><input type=text name=kateg value='' size=40></td></tr>";
      echo "<tr><td>����:</td><td><input type=text name=name value='' size=80></td></tr>";

      echo '<tr><td valign=top>�����:</td><td>';
          ?>
          <textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
          </textarea>
          <?
          
      echo '<tr><td></td><td><input name="save" type="submit" value="���������"><input name="save" type="hidden" value=""></td></tr>';
      echo '</table>';
      echo '</form>';
    }
    else
    {
      echo '���������';
      $up = myquery("insert into game_help (kateg,name,text) VALUES ('".$_POST['kateg']."','".$_POST['name']."','".$_POST['elm1']."')");
      $da = getdate();

      $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
      VALUES (
      '".$char['name']."',
      '������� ������ ������: <b>".$name."</b> (��������� - ".$_POST['kateg'].")',
      '".time()."',
      '".$da['mday']."',
      '".$da['mon']."',
      '".$da['year']."')")
      or die(mysql_error());

      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=help">';
    }
	}
}

if (function_exists("save_debug")) save_debug(); 

?>