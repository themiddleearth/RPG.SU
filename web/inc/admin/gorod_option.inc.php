<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['gorod'] >= 1)
{
    if(!isset($edit) and !isset($new) and !isset($delete))
    {
        echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
        echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=gorod_option&new>�������� ����� �����</a></td></tr>";
        echo "<tr bgcolor=#333333><td>ID</td><td>��������</td><td>����</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_gorod_option order BY name ASC");
        while($ar=mysql_fetch_array($qw))
        {
            echo'<tr>
            <td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=gorod_option&edit='.$ar['id'].'>'.$ar['name'].'</a></td>
            <td>'.$ar['link'].'</td>
            <td><a href=admin.php?opt=main&option=gorod_option&delete='.$ar['id'].'>������� �����</a></td>
            </tr>';
        }
        echo'</table>';
    }

    if(isset($edit))
    {
        if (!isset($save))
        {
            $qw=myquery("SELECT * FROM game_gorod_option where id='$edit'");
            $ar=mysql_fetch_array($qw);
            echo'<form action="" method="post">
            ��������: <input type="text" name="name" value="'.$ar['name'].'" size=50><br>
            ����:            
            <select name="link">';
            $dh = opendir('inc/gorod/');
            $list='';
            while($file = readdir($dh))
            {
                if ($file=='.') continue;
                if ($file=='..') continue;
                $selec = "";
                if ($file == $ar['link']) $selec = "selected";
                $list .= "<option value=\"$file\" \"$selec\">$file</option>\n";
                $lastFile = $file;
            }
            echo $list;
            echo'</select><br>

            <input name="save" type="submit" value="���������"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'����� ������ ��������';
            $up=myquery("update game_gorod_option set name='$name',link='$link' where id='$edit'");
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod_option">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� ����� ������ : <b>".$name."</b>',
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
            ��������: <input type=text name="name" value="" size=50><br>
            ����:             
            <select name="link">';
            $list='';
            $dh = opendir('inc/gorod/');
            while($file = readdir($dh))
            {
                if ($file=='.') continue;
                if ($file=='..') continue;
                $selec = "";
                $list .= "<option value=\"$file\" \"$selec\">$file</option>\n";
                $lastFile = $file;
            }
            echo $list;
            echo'</select><br>

            <input name="save" type="submit" value="�������� �����"><input name="save" type="hidden" value="">';
        }
        else
        {
            echo'����� ���������';
            $up=myquery("insert into game_gorod_option (name,link) VALUES ('$name','$link')");
            echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod_option">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� ����� ������ : <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
        }
    }

    if(isset($delete))
    {
        echo'����� �������';
		list($name) = mysql_fetch_array(myquery("SELECT name FROM game_gorod_option WHERE id='$delete'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 '������ ����� ������ : <b>".$name."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_gorod_option where id='$delete'");
        $up=myquery("delete from game_gorod_set_option where option_id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod_option">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>