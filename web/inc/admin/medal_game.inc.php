<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['medal'] >= 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
        echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=medal_game&new>�������� ����� ������</a></td></tr>";
        echo "<tr bgcolor=#333333><td>ID</td><td>��������</td><td>��������</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_medal order BY id ASC");
        while($ar=mysql_fetch_array($qw))
        {
            echo'<tr>
            <td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=medal_game&edit='.$ar['id'].'>'.$ar['nazv'].'</a></td>
            <td>'.$ar['opis'].'</td>
            <td><a href=admin.php?opt=main&option=medal_game&delete='.$ar['id'].'>������� ������</a></td>
            </tr>';
        }
        echo'</table>';
    }

    if(isset($edit))
    {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_medal where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            ��������: <input type=text name=nazv value="'.$ar['nazv'].'" size=100><br>
            ��������: images\medal\<input type=text name=img_medal value="'.$ar['image'].'" size=30><br>
            �������� ������: <textarea name=opis cols=70 class=input rows=10>'.$ar['opis'].'</textarea><br><br>
            <input name="save" type="submit" value="���������"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'������ ��������';
            $up=myquery("update game_medal set nazv='$nazv',image='$img_medal',opis='$opis' where id='$edit'");
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=medal_game">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� ������ : <b>".$nazv."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
        }
    }


    if(isset($new))
    {
        if (!isset($save))
        {
            echo'<form action="" method="post">
            ��������: <input type=text name=nazv value="" size=100><br>
            ��������: images\medal\<input type=text name=img_medal value="" size=30><br>
            �������� ������: <textarea name=opis cols=70 class=input rows=10></textarea><br><br>
            <input name="save" type="submit" value="�������� ������"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'������ ���������';
            $up=myquery("insert into game_medal (nazv,image,opis) VALUES ('$nazv','$img_medal','$opis')");
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=medal_game">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� ������ : <b>".$nazv."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
        }
    }

    if(isset($delete))
    {
        echo'������ �������';
		list($nazv) = mysql_fetch_array(myquery("SELECT nazv FROM game_medal WHERE id='$delete'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 '������ ������ : <b>".$nazv."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_medal where id='$delete'");
        $up=myquery("delete from game_medal_user where medal_id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=medal_game">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>