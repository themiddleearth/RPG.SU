<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['medal'] >= 1)
{
        if (!isset($search_all) AND !isset($name_v) AND !isset($del_medal) AND !isset($add))
        {
		    echo '<div id="content" onclick="hideSuggestions();"><center><a href="admin.php?opt=main&option=medal_game">������ ������� � ����</a><br><br>';

            echo '<center>������ ��� �������:</center><br><br>';
            echo'<center><font size="1" face="Verdana" color="#ffffff">�����: <input name="name_user" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>                                                                    
		    <input type="button" value="�����" OnClick="location.href=\'admin.php?option=medal&name_v=\'+document.getElementById(\'keyword\').value+\'&opt=main\'">';
            echo '<br><br>

		    <input name="" type="button" value="�������� ���� ������� � ��������" onClick="location.href=\'admin.php?opt=main&option=medal&search_all\'">';
            echo '<br></div><script>init();</script>';
        }

        //�������� ������� ���� �������
        if(isset($search_all))
        {
                echo '<center><font color=ffff00 size=2 face=verdana><b>������ ���� �������</b></font><br>';

                $sel_user = myquery("SELECT DISTINCT user_id FROM game_medal_users ORDER BY user_id");
                while ($user=mysql_fetch_array($sel_user))
                {
                        $user_ident = $user['user_id'];
                        $select = myquery("SELECT name FROM game_users WHERE user_id='$user_ident' LIMIT 1");
                        if (!mysql_num_rows($select)) $select = myquery("SELECT name FROM game_users_archive WHERE user_id='$user_ident' LIMIT 1");
                        list($name) = mysql_fetch_array($select);

                        echo '<hr>';
						echo'<br><font color=ff0000 size=2 face=verdana><b>'.$name.'<b></font>&nbsp;&nbsp;(&nbsp;<a href="admin.php?opt=main&option=medal&add='.$user_ident.'">�������� ������ ������</a>&nbsp;)';

                        $select = myquery("SELECT game_medal_users.id AS id,game_medal.nazv AS nazv, game_medal.opis AS opis,game_medal.image AS image,game_medal_users.zachto AS zachto FROM game_medal_users JOIN game_medal ON game_medal.id = game_medal_users.medal_id WHERE user_id='$user_ident' ORDER BY game_medal.id");

                        echo '<br><table border="1" cellspacing="0" cellpadding="0">';
                        echo '<tr><td>&nbsp;&nbsp;�������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�� ��� ������ ������&nbsp;&nbsp;</td><td></td></tr>';

                        while ($med=mysql_fetch_array($select))
                        {
								echo '<tr><td><img src="http://'.img_domain.'/medal/'.$med['image'].'"></td><td>'.$med['nazv'].'</td><td>'.$med['opis'].'</td><td>'.$med['zachto'].'</td><td><a href="admin.php?opt=main&option=medal&del_medal='.$med['id'].'">������� ��� ������</a></td</tr>';
                        }
                        echo '</table></center><br>';
                }
        }

        //�������� ������ ����������� ������
        if(isset($name_v))
        {
                $pers=myquery("select user_id from game_users where name='$name_v'");
                if (!mysql_num_rows($pers)) $pers=myquery("select user_id from game_users_archive where name='$name_v'");
                if (mysql_num_rows($pers))
                {
                        list($user_ident)=mysql_fetch_array($pers);

                        echo '<center><br><br><font color=ffff00 size=2 face=verdana><b>������ ������: '.$name_v.'</b></font><br>';
                        echo '<br><table border="1" cellspacing="0" cellpadding="0">';
                        echo '<tr><td>&nbsp;&nbsp;�������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�� ��� ������ ������&nbsp;&nbsp;</td><td></td></tr>';

                        $select = myquery("SELECT game_medal_users.id AS id,game_medal.nazv AS nazv, game_medal.opis AS opis,game_medal.image AS image,game_medal_users.zachto AS zachto FROM game_medal_users JOIN game_medal ON game_medal.id = game_medal_users.medal_id WHERE user_id='$user_ident' ORDER BY game_medal.id");
                        while ($med=mysql_fetch_array($select))
                        {
								echo '<tr><td><img src="http://'.img_domain.'/medal/'.$med['image'].'"></td><td>'.$med['nazv'].'</td><td>'.$med['opis'].'</td><td>'.$med['zachto'].'</td><td><a href="admin.php?opt=main&option=medal&del_medal='.$med['id'].'">������� ��� ������</a></td</tr>';
                        }

                        echo '</table>';
						echo '<br><a href="admin.php?opt=main&option=medal&add='.$user_ident.'">�������� ������ ������</a>';
                        echo '</center>';
                }
                else
                {
                        echo '����� �� ������';
                }

        }

        //�������� ������
        if(isset($del_medal))
        {
			$select = myquery("SELECT game_users.name FROM game_users JOIN game_medal_users ON game_medal_users.user_id = game_users.user_id WHERE game_medal_users.id=$del_medal");
			if (!mysql_num_rows($select)) $select = myquery("SELECT game_users_archive.name FROM game_users_archive JOIN game_medal_users ON game_medal_users.user_id = game_users_archive.user_id WHERE game_medal_users.id=$del_medal");

			list($name) = mysql_fetch_array($select);
			$up = myquery("DELETE FROM game_medal_users WHERE id=$del_medal");
			echo'<center><br><br><font color=ff0000 size=2 face=verdana><b>������ �������!<b></font></center><br><br>';
			echo '<meta http-equiv="refresh" content="1;url=admin.php?option=medal&opt=main">';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 '������ ������ � ������: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
        }

        //���������� ������
        if(isset($add))
        {
                $user_ident = $add;
                $select = myquery("SELECT name FROM game_users WHERE user_id='$user_ident' LIMIT 1");
                if (!mysql_num_rows($select)) $select = myquery("SELECT name FROM game_users_archive WHERE user_id='$user_ident' LIMIT 1");
                list($name) = mysql_fetch_array($select);
                If (!isset($save))
                {
                        echo'<center><form action="" method="post">';
                        echo'<font color=ff0000 size=2 face=verdana>���������� ������ ������: '.$name.'</font>';
                        echo '<br><br><font color=ffff00 size=2 face=verdana><b>������ ������������ �������</b></font><br>';

                        echo '<br><table border="1" cellspacing="0" cellpadding="0">';
                        echo '<tr><td>&nbsp;&nbsp;ID&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td><td>&nbsp;&nbsp;�������� ������&nbsp;&nbsp;</td></tr>';
                        $select = myquery("SELECT * FROM game_medal");
                        while ($medal = mysql_fetch_array($select))
                        {
                                echo '<tr><td>'.$medal['id'].'</td><td><img src="http://'.img_domain.'/medal/'.$medal['image'].'"></td><td>'.$medal['nazv'].'</td><td>'.$medal['opis'].'</td></tr>';
                        }
                        echo '</table>';

                        echo '<br><br>������� ID ������: <input name="id" value="" type="text" size="2">';
                        echo '<br>�� ��� ������ ������ ������: <textarea name="zachto" cols="40" rows="6"></textarea><br><br>';
                        echo '<input name="save" type="submit" value="��������"><input name="save" type="hidden" value="">
                        </form>';
                }
                else
                {
                        echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>������ ������: '.$name.' ���������<b></font><br><br>';
                        if(!isset($zachto)) $zachto='';
                        if(isset($id))
                        {
                                $up=myquery("INSERT INTO game_medal_users (user_id,medal_id,zachto) VALUES ('$user_ident','$id','$zachto')");
								$da = getdate();
								$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
								 VALUES (
								 '".$char['name']."',
								 '��� ������: <b>".$name."</b> ������� ������ ID �".$id." �� ".$zachto."',
								 '".time()."',
								 '".$da['mday']."',
								 '".$da['mon']."',
								 '".$da['year']."')")
									 or die(mysql_error());
								echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=medal">';
                        }
                }
        }
}

if (function_exists("save_debug")) save_debug(); 

?>