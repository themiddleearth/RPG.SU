<?

if (function_exists("start_debug")) start_debug(); 

include('inc/template.inc.php');
require('inc/template_header.inc.php');

$ms_vsadnik=8;
$vsadnik=5;

$ms_vsadnik2=15;
$vsadnik2=9;

$char_vsadnik=get_horse_level($char['vsadnik']);
$ms_vsad=get_vsad_level($char['vsadnik']);

$par = 0;
if ($ms_vsad>=$ms_vsadnik2 AND $char_vsadnik>=$vsadnik2)
{
	$par = 3;
}
elseif ($ms_vsad>=$ms_vsadnik AND $char_vsadnik>=$vsadnik)
{
	$par = 2;
}
elseif ($ms_vsad>0 AND $char_vsadnik>0)
{
	$par = 1;
}

echo'<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">';
OpenTable('title');
echo '<div style="margin-top:15px;margin-bottom:15px;width:100%;text-align:center;font-size:13px;color:gold;font-family:Georgia,Helvetica,Arial;">���������</div>';

if (!isset($_POST['see']))
{
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">';
    $result=myquery("select win,avatar,clan_id,view_smile,view_img,dvij,view_chat from game_users where user_id=$user_id LIMIT 1");
    list($win,$avatar,$clan_id,$view_smile,$view_img,$dvij,$view_chat)=mysql_fetch_array($result);

	$result=myquery("select status,gorod,hobbi,info,sex,dr_date,dr_month,dr_year,send_mail,work_IP,only_IP,send_pm,email,chat_variant,ICQnumber,send_ICQ,ICQ_attack,ICQ_bot,ICQ_inboy,ICQ_pm,geksa_view from game_users_data where user_id='$user_id' LIMIT 1");
	list($status,$gorod,$hobbbi,$inffo,$sex,$dn1,$ms1,$god1,$send_mail,$work_IP,$only_IP,$send_pm,$email,$chat_variant,$ICQnumber,$send_ICQ,$ICQ_attack,$ICQ_bot,$ICQ_inboy,$ICQ_pm,$geksa_view)=mysql_fetch_array($result);
    if ($dvij>3) $dvij=0;

    echo "<tr><td valign='top'>���� ������:<br><img src=http://".img_domain."/avatar/$avatar></td><td>";
    
    if (!isset($_GET['upload'])) $upload=""; else $upload = $_GET['upload'];	

    if ($win>=80)
    {
        $absolute_path = "../images/avatar/users";
        $size_limit = "yes";
        $adm = @mysql_result(@myquery("SELECT COUNT(*) FROM game_admins WHERE user_id='$user_id'"),0,0);
        if ($adm==1)
        {
            $limit_size = "51200";
            $image_max_width        = "250";    // ������������ ������ � ������
            $image_max_height        = "250";   //  ��� ����������� ������
        }
        else
        {
            $limit_size = "15360";
            $image_max_width        = "150";    // ������������ ������ � ������
            $image_max_height        = "150";   //  ��� ����������� ������
        }
        $limit_ext = "yes";
        $ext_count = "2";
        $extensions = array(".gif", ".jpeg", ".jpg", ".GIF", ".JPEG", ".JPG");

        switch($upload)
        {
            default:
				echo '<script language="JavaScript" type="text/javascript">
				function load_avatar()
				{
					document.getElementById("ava").src = "http://'.img_domain.'/avatar/gallery/"+document.getElementById("sel_avatar").value;
				}
				</script>';
				echo"������������ ������ ".($limit_size/1024)." ��������<br>
				<form method=\"POST\" action=\"act.php?func=setup&upload=doupload\" enctype=\"multipart/form-data\">
				<input type=file name=file size=20 > <input name=\"submit\" type=\"submit\" value=\"���������� ������\">";
                if (domain_name!='testing.rpg.su')
                {
				echo "<br><br>��� ������� ���� ������ �� �������:<br><img id=\"ava\"><br>
				<SELECT style=\"width:150px\" id=\"sel_avatar\" name=\"file_gallery\" onChange=\"load_avatar();\"><option></option>";
                $nom=0;
				if (domain_name<>'localhost') 
				{						
					$dh = opendir('../images/avatar/gallery/');
					$ava_name='������ ';                
					while($file = readdir($dh))
					{
						if ($file=='.') continue;
						if ($file=='..') continue;
						$nom++;
						echo "<option value=\"$file\">$ava_name".$nom."</option>\n";
					}
				}
				echo"</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"sel_gallery\" type=\"submit\" value=\"������� ���� ������\">";
                echo '<input type="hidden" name="max" value="'.$nom.'"><input type="submit" name="random" value="������� ��������"> ';
                }
                echo"</form>";
				
				echo'������:<br>
                1. �� ������ ���� ������ ��������: '.$image_max_width.'�'.$image_max_height.'<br>
                2. �� ������ ����� ���������, ���������������� ����������<br>
                3. �� ������ �������� �� ����� ����������<br>
				4. ��� ������������ ������� ����� ���������, � ��������� �������� �� ���������� �������������.';
            break;

			case "doupload":
				$endresult = "<font size=\"2\">������ �������</font>";
				if (isset($sel_gallery))
				{
					$upd=myquery("update game_users set avatar='gallery/$file_gallery' where user_id=$user_id");
				}
				elseif (isset($random))
				{
                    $file_gallery = '';
                    $dh = opendir('../images/avatar/gallery/');
                    $nom=0;
                    $r = mt_rand(1,$max);
				    while($file = readdir($dh))
				    {
					    if ($file=='.') continue;
					    if ($file=='..') continue;
                        $nom++;
                        if ($nom==$r)
                        {
                            $file_gallery = $file;
					    }                        
				    }
					$upd=myquery("update game_users set avatar='gallery/$file_gallery' where user_id=$user_id");
				}
				else
				{
                    if (!isset($_FILES['file']))
                    {
                        $endresult = "<font size=\"2\">������ �������</font>";
                    }    
					elseif ($_FILES['file']['size'] == 0)
					{
						$endresult = "<font size=\"2\">�� ������ �� ".echo_sex('������','�������')."</font>";
					}
					else
					{
						if (($size_limit == "yes") && ($limit_size < $_FILES['file']['size']) AND ($clan_id!=1))
						{
							$endresult = "<font size=\"2\">������� ������ (�������� $file_size ����, ��������� $limit_size ����)</font>";
						}
						else
						{
							$size = GetImageSize($file);	
							list($width,$height,$bar,$foo) = $size;
							if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
							{
								$endresult = "<font size=\"2\">�������� �������: GIF JPG PNG BMP</font>";
							}
							elseif ($width > $image_max_width AND $clan_id!=1)
							{
								$endresult = "������! ����������� ������ ���� �� ����\n ".$image_max_width." ��������, � ���� $width ��������<br></li>";
							}
							elseif ($height > $image_max_height AND $clan_id!=1)
							{
								$endresult = "������! ����������� ������ ���� �� ����\n " . $image_max_height . " ��������, � ���� $height ��������<br></li>";
							}
							else
							{
								if (isset($submit))
								{
									$file_name=''.$char['user_id'].'_'.(mt_rand(0,1000)).'.gif';
                                    if (is_file("$absolute_path/$file_name"))
                                    {
									    @unlink ("$absolute_path/$file_name");
                                    }
									@copy($file, "$absolute_path/$file_name") or $endresult = "<font size=\"2\">����� ���� ��� ����������</font>";
									$upd=myquery("update game_users set avatar='users/".$file_name."' where user_id=".$char['user_id']."");
								}
							}
						}
					}
				}
				echo"<tr><td></td><td><center> $endresult  <a href=?func=setup>�����</a> </center></td></tr>";
			break;
		}
	}	
   elseif ($win>=10)
    {
        switch($upload)
        {
            default:
				echo '<script language="JavaScript" type="text/javascript">
				function load_avatar()
				{
					document.getElementById("ava").src = "http://'.img_domain.'/avatar/gallery/"+document.getElementById("sel_avatar").value;
				}
				</script>';
				echo"
				<form method=\"POST\" action=\"act.php?func=setup&upload=doupload\">
				�� ������ ������� ���� ������ �� �������:<br><img id=\"ava\"><br>";
				echo "<SELECT style=\"width:150px\" id=\"sel_avatar\" name=\"file_gallery\" onChange=\"load_avatar();\"><option></option>";
                $dh = opendir('../images/avatar/gallery/');
                $ava_name='������ ';
                $nom=0;
				while($file = readdir($dh))
				{
					if ($file=='.') continue;
					if ($file=='..') continue;
                    $nom++;
					echo "<option value=\"$file\">$ava_name".$nom."</option>\n";
				}
				echo"</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"sel_gallery\" type=\"submit\" value=\"������� ���� ������\">";
                echo '<input type="hidden" name="max" value="'.$nom.'"><input type="submit" name="random" value="������� ��������"> ';
				echo"</form>";
            break;

			case "doupload":
				$endresult = "<font size=\"2\">������ �������</font>";
				if (isset($sel_gallery))
				{
					$upd=myquery("update game_users set avatar='gallery/$file_gallery' where user_id=$user_id");
				}
				elseif (isset($random))
				{
                    $file_gallery = '';
                    $dh = opendir('../images/avatar/gallery/');
                    $nom=0;
                    $r = mt_rand(1,$max);
				    while($file = readdir($dh))
				    {
					    if ($file=='.') continue;
					    if ($file=='..') continue;
                        $nom++;
                        if ($nom==$r)
                        {
                            $file_gallery = $file;
					    }                        
				    }
					$upd=myquery("update game_users set avatar='gallery/$file_gallery' where user_id=$user_id");
				}
				echo"<tr><td></td><td><center> $endresult  <a href=?func=setup>�����</a> </center></td></tr>";
			break;
		}
	}
    if ($char['win']<50)
    {	
        echo '<tr><td></td><td>�� '.echo_sex('������','������').' �������� 10 ����� ��� ������ ������� �� ������� � 80 ����� ��� ��������� ������ �������</td></tr>';
    }

	echo "<form name=frm method=post>";
    echo '<tr><td>������</td><td><input name="status" type="text" value="'.$status.'" size="30" maxlength="20"></td></tr>
		  <tr><td>�����</td><td><input name="gorod" type="text" value="'.$gorod.'" size="30" maxlength="20"></td></tr>
		  <tr><td>�����</td><td><textarea name="hobbi" cols="60" class="input" rows="2">' . $hobbbi . '</textarea></td></tr>
		  <tr><td>����</td><td><textarea name="info" cols="60" class="input" rows="8">' . $inffo . '</textarea></td></tr>';
    if ($sex!="male" AND $sex!="female")
    {
        echo '<tr><td>���</td><td><select name="sex1">
        <option value="male">�������</option>
        <option value="female">�������</option>
        </select></td></tr>';
    }
    else
    {
        echo '<tr><td><input type="hidden" name="sex1" value="'.$sex.'"></td><td></td></tr>';
    }
    echo '<tr><td>&nbsp;&nbsp;</td></tr>';

    echo '<tr>
    <td>���� ��������:</td>
    <td>
    <select name="dn">
    <option value=0></option>';
    for ($i=1;$i<32;$i++)
    {
        echo '<option'; if ($i==$dn1) echo ' selected'; echo'>'.$i.'</option>';
    }
    echo'</select>
    <select name="ms">
    <option value=0></option>';
    for ($i=1;$i<13;$i++)
    {
        echo '<option'; if ($i==$ms1) echo ' selected'; echo'>'.$i.'</option>';
    }
    echo'</select>
    <select name="god">
    <option value=0></option>';
    for ($i=1960;$i<2005;$i++)
    {
        echo '<option'; if ($i==$god1) echo ' selected'; echo'>'.$i.'</option>';
    }
    echo'</select>
    </td></tr>';

    if ($par>=1)
    {
        echo '<tr><td colspan="2" align="center">��������� ������� ������� �����:</td></tr>';
        echo '<tr><td align="right"><input name="dvij" type="radio" value=0'; if($dvij==0) echo ' checked'; echo'></td><td>��������� ����� - 6 ��������</td></tr>';
        echo '<tr><td align="right"><input name="dvij" type="radio" value=1'; if($dvij==1) echo ' checked'; echo'></td><td>������� ����� - 18 ��������</td></tr>';
    }
    if ($par>=2)
    {
        echo '<tr><td align="right"><input name="dvij" type="radio" value=2'; if($dvij==2) echo ' checked'; echo'></td><td>������� ����� - 36 ��������</td></tr>';
    }
    if ($par>=3)
    {
        echo '<tr><td align="right"><input name="dvij" type="radio" value=3'; if($dvij==3) echo ' checked'; echo'></td><td>�������� ����� - 60 ��������</td></tr>';
    }
	echo '
    <tr><td></td><td align="left"><br>���������� ������������ ���� ����� ������ ���� ������������ ���� (�� ����� 10 ����): <input name="geksa_view1" size="4" maxsize="2" type="text" value="'.$geksa_view.'"></td></tr>
	<tr><td></td><td align="left"><input name="view_chat" type="checkbox" value="1"'; if($view_chat=='1') echo ' checked'; echo'>  ���������� ������, ����� ������ ����� ��� ����</td></tr>';
    /*
	<tr><td></td><td>
	<b><u>�������� ������� ���� ����:</u></b><br>
	<table>
	<tr><td><input type="radio" name="chat_var" value=0'; if($chat_variant==0) echo ' checked'; echo'></td><td> ��� "AJAX". ��� �� ���������� AJAX. �������� ��� ������������. ������� ����������� ��������. ����������� �������.</td></tr>
	<tr><td><input type="radio" name="chat_var" value=1'; if($chat_variant==1) echo ' checked'; echo'></td><td> ��� "JS". ��� �� ���������� JavaScript. �������� c �������������. �� ������� ����������� ��������, �� ����� �� �������� � ������ ������� ���������. ��������� �������.</td></tr>
	<tr><td><input type="radio" name="chat_var" value=2'; if($chat_variant==2) echo ' checked'; echo'></td><td> ��� "HTML". ��� �� ���������� HTML. �������� c �������������. ���������� ����������� �������� HTML. �������� � ����� ���������. ������� �������.</td></tr>	
	</table>
	</td></tr>
    */
    echo '<tr><td></td><td align="left"><br><input name="view_smile" type="checkbox" value="1"'; if($view_smile=='1') echo ' checked'; echo'>  �������� ������, ���� �� ������ ������������ �������� � ���� � �� ������</td></tr>
    <tr><td></td><td align="left"><input name="send_mail" type="checkbox" value="1"'; if($send_mail=='1') echo ' checked'; echo'>  �������� ������, ����� �������� �� ������������� ������ �� e-mail, ��������� ��� �����������: &lt;'.$email.'&gt;</td></tr>
	<tr><td></td><td align="left"><input name="work_IP" type="checkbox" value="1"'; if($work_IP=='1') echo ' checked'; echo'> ���� � ���� ���������� ������ �� ����� RPG, �������� ���� ������. � ���� ������ ���� ����� �������� ����� IP ������.</td></tr>
	<tr><td></td><td align="left"><input name="send_pm" type="checkbox" value="1"'; if($send_pm=='1') echo ' checked'; echo'> �������� ������, ���� ������, ����� ��� ������ ������, ���������� ���� �� ������� �����, ������������� �� ���� email ����� - &lt;'.$email.'&gt;</td></tr>
	<tr><td></td><td align="left"><input name="only_IP" type="checkbox" value="1"'; if($only_IP=='1') echo ' checked'; echo'> �������� ���� ������, ����� ��������� ���� ��� ����� ���������� � ������ IP �������. ���� �������� ����� "��������" � �������� IP ������ - '.$_SERVER['REMOTE_ADDR'].'. ��� ����� ������, ���� � ���� ���������� ������� IP ����� � ���� ��������. ������ ��������� ����� ��������, ���������������� �������� �������������� ������ �� ������� �������� �� ������ http://www.rpg.su/index.php?option=for. 
	<font size=3 color=red><b>��������! �� ������������ �����, ���� �� �� ��������� ��������� ������ ������ �������!!!</b></font></td></tr>';
	/*
	<tr><td></td><td align="left"><input name="send_ICQ" type="checkbox" value="1"'; if($send_ICQ=='1') echo ' checked'; echo'>  ���������� ������ ���� �� ������ �������� ���������� � ������ �������� �� ICQ</td></tr>
	<tr><td></td><td>
	<b><u>��������� ������� ���������� �� ICQ:</u></b><br>
	<table>
	<tr><td><input type="text" name="ICQnumber" value='.$ICQnumber.'></td><td> ������� ����� ������ ICQ (��� ����� �� ������, �� ����� �������������� ������ ������� �������� ����������)</td></tr>
	<tr><td colspan=2><input name="ICQ_attack" type="checkbox" value="1"'; if($ICQ_attack=='1') echo ' checked'; echo'> ��������� � ��������� �� ������ ��������� ������� ��������</td></tr>
	<tr><td colspan=2><input name="ICQ_bot" type="checkbox" value="1"'; if($ICQ_bot=='1') echo ' checked'; echo'> ��������� � ��������� �� ������ ��������� ������������ ���������</td></tr>
	<tr><td colspan=2><input name="ICQ_inboy" type="checkbox" value="1"'; if($ICQ_inboy=='1') echo ' checked'; echo'> ��������� � ������� ������ � ��� �� �����������</td></tr>
	<tr><td colspan=2><input name="ICQ_pm" type="checkbox" value="1"'; if($ICQ_pm=='1') echo ' checked'; echo'> ��������� � ��������� ������ �� ������ �������</td></tr>
	</table>
	</td></tr>
	*/
	echo '<tr><td colspan="2" align="center"><br><input name="submit" type="submit" value="���������"></td></tr>
	<input name="see" type="hidden" value="">
	</table>';
}
else
{
  if (isset($_POST['status'])) $status = mysql_real_escape_string(htmlspecialchars($_POST['status'])); else $status = "";
  if (isset($_POST['gorod']))  $gorod  = mysql_real_escape_string(htmlspecialchars($_POST['gorod']));  else $gorod = "";
  if (isset($_POST['hobbi']))  $hobbi  = mysql_real_escape_string(htmlspecialchars($_POST['hobbi']));  else $hobbi = "";
  if (isset($_POST['info']))   $info   = mysql_real_escape_string(htmlspecialchars($_POST['info']));   else $info = "";

  if (isset($_POST['dn']))     $dn     = (int)$_POST['dn'];
  if (isset($_POST['ms']))     $ms     = (int)$_POST['ms'];
  if (isset($_POST['god']))    $god    = (int)$_POST['god'];

  if (isset($_POST['dvij']))   $dvij   = $_POST['dvij']; else $dvij = 0;

  if (isset($_POST['view_chat'])  && $_POST['view_chat']  == 1) $view_chat  = 1; else $view_chat  = 0;
  if (isset($_POST['view_smile']) && $_POST['view_smile'] == 1) $view_smile = 1; else $view_smile = 0;
  if (isset($_POST['send_mail'])  && $_POST['send_mail']  == 1) $send_mail  = 1; else $send_mail  = 0;
  if (isset($_POST['work_IP'])    && $_POST['work_IP']    == 1) $work_IP    = 1; else $work_IP    = 0;
  if (isset($_POST['send_pm'])    && $_POST['send_pm']    == 1) $send_pm    = 1; else $send_pm    = 0;
  if (isset($_POST['only_IP'])    && $_POST['only_IP']    == 1) $only_IP    = 1; else $only_IP    = 0;

  if (isset($_POST['view_img'])   && $_POST['view_img']   == 1) $view_img    = 1; else $view_img   = 0;

  if (isset($_POST['sex1']))   $sex1    = $_POST['sex1'];
  if (isset($_POST['geksa_view1']))   $geksa_view1  = min(10,$_POST['geksa_view1']); else $geksa_view1 = 2;


	$result=myquery("update game_users set view_smile='$view_smile',view_img='$view_img',dvij='$dvij',view_chat='$view_chat' where user_id='$user_id'") or die(mysql_error());
	$result=myquery("update game_users_data set status='$status',gorod='$gorod',hobbi='$hobbi',info='$info',sex='$sex1',dr_date='$dn',dr_month='$ms',dr_year='$god',send_mail='$send_mail',work_IP='$work_IP',only_IP='$only_IP',only_IP_number=".HostIdentify().",send_pm='$send_pm',geksa_view='$geksa_view1' where user_id='$user_id'") or die(mysql_error());
	echo'��������� ��������<br>';
}

	//��� ��� ������
	echo '<br /><br /><br />';
	$check_psg=mysql_num_rows(myquery("SELECT user_id FROM game_users_psg WHERE user_id='".$char['user_id']."'"));
	if (isset($_POST['del_user']))	
	{
		echo '<font color="red">';
		if ($char['clan_id']<>0)
		{
			echo '������ �������� ���������� ���������, ���������� � �����!<br>';
		}
		else
		{
			if ($check_psg==0)
			{
				myquery("INSERT INTO game_users_psg (user_id, banned_date) VALUES ('".$char['user_id']."', ".time()." ) ");
				echo '��� �������� ������������!';
				$check_psg=1;
				$_SESSION['banned']=time();
				$topic_check=myquery("SELECT id FROM forum_topics WHERE top like '��� (������� �� ������������ �������)'");
				while (list($topic_id)=mysql_fetch_array($topic_check))
				{
					myquery("INSERT INTO forum_otv (topics_id, text, user_id, timepost) VALUES ('".$topic_id."', '���', '".$char['user_id']."', ".time().") ");
					myquery("UPDATE forum_topics SET last_date=".time().", last_user='".$char['user_id']."', otv=otv+1 WHERE id='".$topic_id."'");
				}
			}
			else
			{
				myquery("DELETE FROM game_users_psg WHERE user_id='".$char['user_id']."' ");
				echo '��� �������� �������������!';	
				$check_psg=0;
				unset($_SESSION['banned']);
				$topic_check=myquery("SELECT id FROM forum_topics WHERE top like '��� (������� �� ������������ �������)'");
				while (list($topic_id)=mysql_fetch_array($topic_check))
				{
					myquery("DELETE FROM forum_otv WHERE user_id='".$char['user_id']."' AND text like '���' AND topics_id='".$topic_id."' ");
					myquery("UPDATE forum_topics SET last_date=".time().", last_user='".$char['user_id']."', otv=otv-1 WHERE id='".$topic_id."'");
				}
			}
		}
		echo '</font>';
	}	
	
	if ($check_psg==1) { $button_name="�������������� ���������"; }
	else { $button_name="������������� ���������"; }

	echo '<fieldset style="font-weight:normal;font-size:12px;color:#FF6347;margin-left:55px;width:650px;margin-bottom:30px;padding:15px;"><legend><b>�������� ���������</b></legend><form name=newpass method=post>&nbsp;&nbsp;&nbsp;�� ������ ��������� ������� �������� ������ ���������, ����� �� ��������������� ������. � ������� ������ �� ������ �������� ��� �������. ����� ������ �������� ����� ������������, � ����� 4 ������ ����� ������ ������ ������������� �����.<br />';
	echo '<form name="del_user" method="POST">';
	echo '<br><input type="submit" value="'.$button_name.'" name="del_user">';
	echo '</form></fieldset>';

if (isset($_POST['pass']) and $_POST['pass']!='' and
    isset($_POST['newpass']) and $_POST['newpass']!='' and
    isset($_POST['newpass2']) and $_POST['newpass2']!='' and
    $_POST['newpass']==$_POST['newpass2'] and isset($_POST['subm']))
{
    $pass1=md5($_POST['pass']);
    $newpass1=md5($_POST['newpass']);

	$result = myquery("SELECT * FROM game_users WHERE user_id='$user_id' AND user_pass='$pass1' LIMIT 1");
	if (mysql_num_rows($result)!='0')
	{
		echo'<br><br><b><font color=red>&nbsp;&nbsp;&nbsp;������ �������!</font></b><br>';
		$email = mysql_result(myquery("SELECT email FROM game_users_data WHERE user_id='$user_id'"),0,0);

		$result=myquery("update game_users set user_pass='".$newpass1."' where user_id='$user_id'");
        
        include("class/class_email.php");

		$message  = "[http://".domain_name."] ���������� :: ����� ��������. ��������� ������!\n\n";
		$message .= "���� ����� ������: ".$_POST['newpass']."\n\n� ���������, ������������� RPG.SU";

		$subject = '���������� :: ����� �������� [��������� ������]';

		$e_mail = new emailer();
		$e_mail->email_init();
		$e_mail->to = $email;
		$e_mail->subject = $subject;
		$e_mail->message = $message;
		$e_mail->send_mail();
	}
}
else
{
    echo'<br /><br /><br /><fieldset style="font-weight:normal;font-size:12px;color:#2FF5FB;margin-left:55px;width:650px;margin-bottom:30px;padding:15px;"><legend><b>��������� ������ �� ���� � ����</b></legend><form name=newpass method=post>&nbsp;&nbsp;&nbsp;�� ������ �������� ���� ������ �� ���� � ����. ��� ����� ���� ���� ������� ���� ������ � ����� ������ � �����.����� �����:<br /><br>&nbsp;&nbsp;&nbsp;<input type=password name=pass size=10> ����� ������� ������<br>&nbsp;&nbsp;&nbsp;<input type=password name=newpass size=10> ����� ����� ������ <br>&nbsp;&nbsp;&nbsp;<input type=password name=newpass2 size=10> ������� ����� ������ ��� ���<br /><br /><input name="subm" type="submit" value="������� ������ �� ���� � ����"></form></fieldset>';
}

OpenTable('close');
echo'</td><td width="172" valign="top">';

include('inc/template_stats.inc.php');

echo'</td></tr></table>';
set_delay_reason_id($user_id,25);

if (function_exists("save_debug")) save_debug(); 

?>