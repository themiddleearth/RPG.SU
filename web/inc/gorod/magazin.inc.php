<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	error_reporting(E_ALL);
	
	$da = getdate();
	$day = $da['mday'];
	$month = $da['mon'];
	
	$no_cost=0;
	if ($no_cost==1)
	{
		$gp1 = 0;
		$gp2 = 0;	
	}
	else
	{
		$gp1 = 200;
		$gp2 = 150;
	}

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ ����� �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';

	$img='http://'.img_domain.'/race_table/orc/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top">';

	$sel = myquery("SELECT * FROM game_admins WHERE user_id=$user_id");
	$adm = mysql_num_rows($sel);
	if ($adm==1 AND isset($moder))
	{
		if (isset($_POST['da']) AND isset($_POST['card_id']))
		{
			 //���������
			 $gift = mysql_fetch_array(myquery("SELECT * FROM game_gift WHERE id=".$_POST['card_id'].""));
			 $user_from = $gift['user_from'];
			 $comment = $gift['gift_text'];
			 $user_to = $gift['user_to'];
			 list($user_from_name) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id='$user_from') UNION (SELECT name FROM game_users_archive WHERE user_id='$user_from')"));
			 list($user_to_name) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id='$user_to') UNION (SELECT name FROM game_users_archive WHERE user_id='$user_to')"));
			 if ($gift['che']==2)
			 {
				 echo '�������� ���������!<br><br>';
				 list($pol_from) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_from"));
				 $theme = "���� �������� ������ $user_to_name ���� ������� �����������.";
				 $post = "�� ".echo_sex('������','�������',$pol_from)." �������� � ����������: [quote]".$comment."[/quote] � ".date("H-i d-m-Y",$gift['time_send']).". �������� ������ ��������� � ���� ���������� ������ $user_to_name!";
				 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_from', '0', '$theme', '$post', '0','".time()."',0)");
				 list($pol_to) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_to"));
				 $theme = "�� ".echo_sex('�������','��������',$pol_to)." �������� �� ������ $user_from_name";
				 $post = "�� ".echo_sex('�������','��������',$pol_to)." �������� �� ������ $user_from_name. �� ������ �� ����������� � <a href=\"http://".domain_name."/view/?name=$user_to_name\">���� ���������� � ����� ���������!</a>";
				 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_to', '0', '$theme', '$post', '0','".time()."',0)");
				 myquery("UPDATE game_gift SET time_send=".time().", che=1 WHERE id=".$_POST['card_id']."");
			 }
			 else
			 {
				 $absolute_path = '../../images/gift/tmp';
				 $absolute_path_new = '../../images/gift/gallery';
				 $file_card = $gift['gift_img'];
                 if (is_file("$absolute_path_new/$file_card"))
                 {
				    @unlink ("$absolute_path_new/$file_card");
                 }
				 if (copy("$absolute_path/$file_card", "$absolute_path_new/$file_card"))
				 {
					 echo '�������� ���������!<br><br>';
					 list($pol_from) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_from"));
					 $theme = "���� �������� ������ $user_to_name ���� ������� �����������.";
					 $post = "�� ".echo_sex('������','�������',$pol_from)." �������� � ����������: [quote]".$comment."[/quote] � ".date("H-i d-m-Y",$gift['time_send']).". �������� ������ ��������� � ���� ���������� ������ $user_to_name!";
					 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_from', '0', '$theme', '$post', '0','".time()."',0)");
					 list($pol_to) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_to"));
					 $theme = "�� ".echo_sex('�������','��������',$pol_to)." �������� �� ������ $user_from_name";
					 $post = "�� ".echo_sex('�������','��������',$pol_to)." �������� �� ������ $user_from_name. �� ������ �� ����������� � <a href=\"http://".domain_name."/view/?name=$user_to_name\">���� ���������� � ����� ���������!</a>";
					 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_to', '0', '$theme', '$post', '0','".time()."',0)");
					 myquery("UPDATE game_gift SET time_send=".time().", che=1 WHERE id=".$_POST['card_id']."");
				 }
				 else
				 {
					 echo '��������� ������!<br><br>';
					 list($pol_from) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id='$user_from'"));
					 $theme = "���� �������� ������ $user_to_name �� ���� ������� �����������.";
					 $post = "�� ".echo_sex('������','�������',$pol_from)." �������� � ����������: [quote]".$comment."[/quote] � ".date("H-i d-m-Y",$gift['time_send']).". ��� ��������� �������� ��������� ������ � �������� �� ���� ����������. �� ���������� ���� ���� ������!";
					 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_from', '0', '$theme', '$post', '0','".time()."',0)");
					 myquery("DELETE FROM game_gift WHERE id=".$_POST['card_id']."");
					 if ($gift['che']==2)
					 {
						myquery("UPDATE game_users SET gp=gp+$gp1,CW=CW+".($gp1*money_weight)." WHERE user_id='$user_from'");
						myquery("UPDATE game_users_archive SET gp=gp+$gp1,CW=CW+".($gp1*money_weight)." WHERE user_id='$user_from'");
						setGP($user_from,$gp1,45);
					 }
					 if ($gift['che']==0)
					 {
						myquery("UPDATE game_users SET gp=gp+$gp2,CW=CW+".($gp2*money_weight)." WHERE user_id='$user_from'");
						myquery("UPDATE game_users_archive SET gp=gp+$gp2,CW=CW+".($gp2*money_weight)." WHERE user_id='$user_from'");
						setGP($user_from,$gp2,45);
					 }
				 }
                 if (is_file("$absolute_path/$file_card"))
                 {
				    @unlink ("$absolute_path/$file_card");
                 }
			 }
		}
		if ((isset($_POST['net']) OR (isset($_POST['net_gp']))) AND isset($_POST['card_id']))
		{
			 //���������
			 echo '�������� �������!<br><br>';
			 $gift = mysql_fetch_array(myquery("SELECT * FROM game_gift WHERE id=".$_POST['card_id'].""));
			 $user_from = $gift['user_from'];
			 $comment = $gift['gift_text'];
			 $user_to = $gift['user_to'];
			 list($user_to) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=$user_to) UNION (SELECT name FROM game_users_archive WHERE user_id=$user_to)"));
			 list($pol_from) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_from"));
			 $theme = "���� �������� ������ $user_to ���� ��������� �����������.";
			 $post = "�� ".echo_sex('������','�������',$pol_from)." �������� � ����������: [quote]".$comment."[/quote],  � ".date("H-i d-m-Y",$gift['time_send']).", �� ��� ���� ��������� �����������!";
			 myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('$user_from', '0', '$theme', '$post', '0','".time()."',0)");
			 $absolute_path = '../../images/gift/tmp';
			 $file_name = $gift['gift_img'];
             if (is_file("$absolute_path/$file_name"))
             {
			    @unlink ("$absolute_path/$file_name");
             }
			 if (isset($_POST['net_gp']))
			 {
				 if ($gift['che']==2)
				 {
					myquery("UPDATE game_users SET gp=gp+$gp1,CW=CW+".($gp1*money_weight)." WHERE user_id=$user_from");    
					myquery("UPDATE game_users_archive SET gp=gp+$gp1,CW=CW+".($gp1*money_weight)." WHERE user_id=$user_from");    
					setGP($user_from,$gp1,45); 
				 }
				 if ($gift['che']==0)
				 {
					myquery("UPDATE game_users SET gp=gp+$gp2,CW=CW+".($gp2*money_weight)." WHERE user_id=$user_from");
					myquery("UPDATE game_users_archive SET gp=gp+$gp2,CW=CW+".($gp2*money_weight)." WHERE user_id=$user_from");
					setGP($user_from,$gp2,45);
				 }
			 }
			 myquery("DELETE FROM game_gift WHERE id=".$_POST['card_id']."");
		}
		// ������������� ��������
		echo '<table width="100%">
		<tr><td><font color="white">�� ����</td><td><font color="white">����</td><td><font color="white">��������</td><td><font color="white">���������</td><td></td></tr>';
		$sel = myquery("SELECT * FROM game_gift WHERE che<>1 ORDER BY time_send ASC");
		while ($gift = mysql_fetch_array($sel))
		{
			list($user_from) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$gift['user_from'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$gift['user_from'].")"));
			list($user_to) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$gift['user_to'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$gift['user_to'].")"));
			echo '<tr><td>'.$user_from.'</td><td>'.$user_to.'</td><td><img src=http://'.img_domain.'/gift/';
			if ($gift['che']==0) echo 'tmp';
			else echo 'gallery';
			echo '/'.$gift['gift_img'].' width=100></td><td>'.$gift['gift_text'].'</td><td><form name="gift'.$gift['id'].'" method="post" action=""><input type="hidden" name="card_id" value="'.$gift['id'].'"><input type="submit" name="da" value="���������"><br><input type="submit" name="net" value="���������"><br><input type="submit" name="net_gp" value="��������� (������� ������)"></form></td>';
		}
		echo '</table>';
	}
	else
	{
		
		if (isset($_POST['give_card']))
		{
			if ($char['GP']<$gp1)
			{
				echo '<center><font color=white><b>������� � ��� � ��������! (������� '.$gp1.' �����)</b></font></center>';
			}
			elseif (!isset($_POST['send_card']))
			{
				echo '<script language="JavaScript" type="text/javascript">
				function load_card()
				{
					document.getElementById("card_gal").src = "http://'.img_domain.'/gift/gallery/"+document.getElementById("sel_card").value;
					width_img = document.getElementById("card_gal").width;
					max_width = 150;
					if (width_img>max_width)
					{
						koef = max_width/width_img;
						document.getElementById("card_gal").width = document.getElementById("card_gal").width*koef;
						document.getElementById("card_gal").height = document.getElementById("card_gal").height*koef;
					}
				}
				</script>';
				echo '
				<div id="content" onclick="hideSuggestions();">
				<form action="" method="post" enctype="multipart/form-data"><table width="100%">
				<tr><td> ����: </td><td><input type="text" id="keyword" onkeyup="handleKeyUp(event)" value="" name="komu_name" size=35 maxsize=35><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>';
				if ($char['clevel']>=9)
				{
					echo '<tr><td>������� ���� �������� (����. ������ 150�150): </td><td><input type=file name=file id="file" size=35 ></td></tr>';
				}
				echo '<tr><td>������� �� �������:<br>(+'.($gp1-$gp2).') ����� � ��������� ��������)</td><td><img id="card_gal"><br>
				<SELECT style="width:150px" id="sel_card" name="sel_card" onChange="load_card();"><option value="0"></option>';
				if (domain_name=='localhost')
				{
					$dh = opendir('../images/gift/gallery/');
				}
				else
				{
					$dh = opendir('../../images/gift/gallery/');
				}
				while($filegal = readdir($dh))
				{
					if ($filegal=='.') continue;
					if ($filegal=='..') continue;
					$selec = "";
					echo "<option value=\"$filegal\" \"$selec\">$filegal</option>\n";
				}
				echo'</SELECT></td></tr>
				<tr><td>��������� ����������: (�� ����� 250 ��������)</td><td><textarea name="comment" cols=35 rows=10></textarea></td></tr>
				<tr><td><span title="�� ������ �������� ��������� ��������� ����������, ������� ����������� �� ����� ������">��������� ��������� ����������: (�� ����� 100 ��������)</span></td><td><textarea name="private" cols=35 rows=5></textarea></td></tr>
				<tr><td></td><td><br><input type="hidden" name="give_card"><input type="submit" name="send_card" value="������� ��������"></td></tr></table></form>
				</div><script>init();</script>';
			}
			else
			{
				$prov = myquery("(SELECT user_id FROM game_users WHERE name='".$_POST['komu_name']."') UNION (SELECT user_id FROM game_users_archive WHERE name='".$_POST['komu_name']."')");
				if (mysql_num_rows($prov))
				{
					list($user_to) = mysql_fetch_array($prov);
					$col_card = mysql_result(myquery("SELECT COUNT(*) FROM game_gift WHERE user_to=$user_to AND che=1 AND time_send>=".(time()-7*24*60*60).""),0,0);
					if ($col_card<5000)
					{
						if (isset($_FILES['file']) AND $_FILES['file']['size']>0 AND $char['clevel']>=9)
						{
							$extensions = array(".gif", ".jpeg", ".jpg", ".GIF", ".JPEG", ".JPG");
							$image_max_width    = "150";
							$image_max_height   = "150";
							
							$endresult = "<font size=\"2\">���� �������� ��������� ��� ���������. ����� �������� ����� �������� ������������, ��� ����� ��������� ��� ������� ���������� ������</font>";
							$flag = 0;

							$size = GetImageSize($_FILES['file']['tmp_name']);	
							list($width,$height,$bar,$foo) = $size;
							if ($width > $image_max_width)
							{
								$endresult = "<font size=\"2\">������! ����������� ������ ���� �� ����\n ".$image_max_width." ��������, � ���� $width ��������</font>";
								$flag = 2;
							}
							if ($height > $image_max_height)
							{
								$endresult = "<font size=\"2\">������! ����������� ������ ���� �� ����\n " . $image_max_height . " ��������, � ���� $height ��������</font>";
								$flag = 2;
							}
							if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
							{
								$endresult = "<font size=\"2\">�������� �������: GIF JPG PNG BMP</font>";
								$flag = 2;
							}
							// ������������ ���� �������� - ������������
							$last_id = mysql_result(myquery("SELECT id FROM game_gift ORDER BY id DESC LIMIT 1"),0,0);
							$last_id++;
							$new_file_name = $user_id.'_'.$last_id;
							if ($bar==1) $new_file_name.=".gif";
							if ($bar==2) $new_file_name.=".jpg";
							if ($bar==3) $new_file_name.=".png";
							if ($bar==6) $new_file_name.=".bmp";
							$absolute_path = '../../images/gift/tmp';
                            if (is_file("$absolute_path/$new_file_name"))
                            {
							    @unlink ("$absolute_path/$new_file_name");
                            }
							if ($flag!=2 AND copy($_FILES['file']['tmp_name'], "$absolute_path/$new_file_name"))
							{
								  $comment = addslashes(htmlspecialchars($_POST['comment']));
								  $private = addslashes(htmlspecialchars($_POST['private']));
								  myquery("INSERT INTO game_gift (user_from,user_to,gift_img,gift_text,time_send,che,private) VALUES ($user_id,$user_to,'".$new_file_name."','".$comment."',".time().",0,'".$private."')");
								  myquery("UPDATE game_users SET GP=GP-$gp2,CW=CW-".(money_weight*$gp2)." WHERE user_id=$user_id");
								  setGP($user_id,-$gp2,46);
								  $theme = "����� �������� ��������� �� ���������";
								  $post = "�� ��������� ��������� ����� ��������: <hr>
								  �������� <br>
								  <img src=\"$absolute_path/$new_file_name\"><hr>���������<br>$comment";
								  myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES (612,$user_id, '$theme', '$post', '0','".time()."',0)");
								  myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES (2694,$user_id, '$theme', '$post', '0','".time()."',0)");
								  myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES (4214,$user_id, '$theme', '$post', '0','".time()."',0)");
								  echo '<font size=2 color=white><center><b>���� �������� ������� ��� �������� �����������. <br>�������� ����� ������ � ���������� � ��������� � ������� 15 ���� ����� ����, ��� ����� ��������� �����������!<br><br>������� ��� '.echo_sex('��������������','���������������').' �������� ������ ������ ��������!</b></center></font>';
							}
							else
							{
								echo 'Error!';
								if ($flag == 2 ) echo $endresult;
							}
						}
						else
						{
							// �������� �� �������. ��� ���������. ������ ����� ���� ��� � ������ ���������
							$comment = addslashes(htmlspecialchars($_POST['comment']));						
							$private = addslashes(htmlspecialchars($_POST['private']));
							myquery("INSERT INTO game_gift (user_from,user_to,gift_img,gift_text,time_send,che,private) VALUES ($user_id,$user_to,'".$_POST['sel_card']."','".$comment."',".time().",2,'".$private."')");
							echo '<font size=2 color=white><center><b>���� �������� ������� ��� �������� �����������. <br>�������� ����� ������ � ���������� � ��������� � ������� 15 ���� ����� ����, ��� ����� ��������� �����������!<br><br>������� ��� '.echo_sex('��������������','���������������').' �������� ������ ������ ��������!</b></center></font>';
							myquery("UPDATE game_users SET GP=GP-$gp1,CW=CW-".(money_weight*$gp1)." WHERE user_id=$user_id");
							setGP($user_id,-$gp1,46);
						}
					}
					else
					{
						echo ' � ������ <b><font color=white>'.$_POST['komu_name'].'</font></b> ���������� ������������ ���������� �������� �������� (5 ��.)';
					}
				}
				else
				{
					echo '������ <b><font color=white>'.$_POST['komu_name'].'</font></b> ��� � ���� ������';
				}
			}
		}
		elseif (isset($_POST['give_buket']))
		{
		}
		else
		{
			echo '<br><center><font color=white face=Verdana,Tahoma,Arial size=3><b>����� ���������� � ������ ��������!</b></font><br><br><img src="http://'.img_domain.'/gift/podarki2.jpg" width="476"><br><br>��� �� �������?
			<br><br>
			<form action="" method="post">
			<input type="hidden" name="town" value='.$town.'">
			<input type="submit" name="give_card" value="�������� �������� ('.$gp2.' �����)"><br>
			<br>
			</form></center>';
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

	if ($adm == 1)	
	{
		echo '<center><br><br>';
		$cou = myquery("SELECT COUNT(*) FROM game_gift WHERE che<>1");
		list($cou) = mysql_fetch_row($cou);
		echo '�� ���������: '.$cou.' ��������.<br><a href="../lib/town.php?option='.$option.'&moder">������������ ��������</a></center>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>