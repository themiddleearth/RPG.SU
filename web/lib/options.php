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
echo '<div style="margin-top:15px;margin-bottom:15px;width:100%;text-align:center;font-size:13px;color:gold;font-family:Georgia,Helvetica,Arial;">Настройки</div>';

if (!isset($_POST['see']))
{
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">';
    $result=myquery("select win,avatar,clan_id,view_smile,view_img,dvij,view_chat from game_users where user_id=$user_id LIMIT 1");
    list($win,$avatar,$clan_id,$view_smile,$view_img,$dvij,$view_chat)=mysql_fetch_array($result);

	$result=myquery("select status,gorod,hobbi,info,sex,dr_date,dr_month,dr_year,send_mail,work_IP,only_IP,send_pm,email,chat_variant,ICQnumber,send_ICQ,ICQ_attack,ICQ_bot,ICQ_inboy,ICQ_pm,geksa_view from game_users_data where user_id='$user_id' LIMIT 1");
	list($status,$gorod,$hobbbi,$inffo,$sex,$dn1,$ms1,$god1,$send_mail,$work_IP,$only_IP,$send_pm,$email,$chat_variant,$ICQnumber,$send_ICQ,$ICQ_attack,$ICQ_bot,$ICQ_inboy,$ICQ_pm,$geksa_view)=mysql_fetch_array($result);
    if ($dvij>3) $dvij=0;

    echo "<tr><td valign='top'>Твой аватар:<br><img src=http://".img_domain."/avatar/$avatar></td><td>";
    
    if (!isset($_GET['upload'])) $upload=""; else $upload = $_GET['upload'];	

    if ($win>=80)
    {
        $absolute_path = "../images/avatar/users";
        $size_limit = "yes";
        $adm = @mysql_result(@myquery("SELECT COUNT(*) FROM game_admins WHERE user_id='$user_id'"),0,0);
        if ($adm==1)
        {
            $limit_size = "51200";
            $image_max_width        = "250";    // максимальные ширина и высота
            $image_max_height        = "250";   //  для графических файлов
        }
        else
        {
            $limit_size = "15360";
            $image_max_width        = "150";    // максимальные ширина и высота
            $image_max_height        = "150";   //  для графических файлов
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
				echo"Максимальный размер ".($limit_size/1024)." килобайт<br>
				<form method=\"POST\" action=\"act.php?func=setup&upload=doupload\" enctype=\"multipart/form-data\">
				<input type=file name=file size=20 > <input name=\"submit\" type=\"submit\" value=\"Установить аватар\">";
                if (domain_name!='testing.rpg.su')
                {
				echo "<br><br>или выбрать себе аватар из галереи:<br><img id=\"ava\"><br>
				<SELECT style=\"width:150px\" id=\"sel_avatar\" name=\"file_gallery\" onChange=\"load_avatar();\"><option></option>";
                $nom=0;
				if (domain_name<>'localhost') 
				{						
					$dh = opendir('../images/avatar/gallery/');
					$ava_name='Аватар ';                
					while($file = readdir($dh))
					{
						if ($file=='.') continue;
						if ($file=='..') continue;
						$nom++;
						echo "<option value=\"$file\">$ava_name".$nom."</option>\n";
					}
				}
				echo"</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"sel_gallery\" type=\"submit\" value=\"Выбрать этот аватар\">";
                echo '<input type="hidden" name="max" value="'.$nom.'"><input type="submit" name="random" value="Выбрать случайно"> ';
                }
                echo"</form>";
				
				echo'Аватар:<br>
                1. Не должен быть больше размеров: '.$image_max_width.'х'.$image_max_height.'<br>
                2. Не должен иметь рекламное, порнографическое содержание<br>
                3. Не должен выходить за рамки Средиземья<br>
				4. Все недопустимые аватары будут удаляться, а владельцы наказаны по усмотрению администрации.';
            break;

			case "doupload":
				$endresult = "<font size=\"2\">Аватар закачан</font>";
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
                        $endresult = "<font size=\"2\">Ошибка закачки</font>";
                    }    
					elseif ($_FILES['file']['size'] == 0)
					{
						$endresult = "<font size=\"2\">Ты ничего не ".echo_sex('выбрал','выбрала')."</font>";
					}
					else
					{
						if (($size_limit == "yes") && ($limit_size < $_FILES['file']['size']) AND ($clan_id!=1))
						{
							$endresult = "<font size=\"2\">Большой размер (закачано $file_size байт, разрешено $limit_size байт)</font>";
						}
						else
						{
							$size = GetImageSize($file);	
							list($width,$height,$bar,$foo) = $size;
							if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
							{
								$endresult = "<font size=\"2\">Разешены форматы: GIF JPG PNG BMP</font>";
							}
							elseif ($width > $image_max_width AND $clan_id!=1)
							{
								$endresult = "Ошибка! Изображение должно быть не шире\n ".$image_max_width." пикселей, а твое $width пикселей<br></li>";
							}
							elseif ($height > $image_max_height AND $clan_id!=1)
							{
								$endresult = "Ошибка! Изображение должно быть не выше\n " . $image_max_height . " пикселей, а твое $height пикселей<br></li>";
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
									@copy($file, "$absolute_path/$file_name") or $endresult = "<font size=\"2\">Такой файл уже существует</font>";
									$upd=myquery("update game_users set avatar='users/".$file_name."' where user_id=".$char['user_id']."");
								}
							}
						}
					}
				}
				echo"<tr><td></td><td><center> $endresult  <a href=?func=setup>назад</a> </center></td></tr>";
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
				Ты можешь выбрать себе аватар из галереи:<br><img id=\"ava\"><br>";
				echo "<SELECT style=\"width:150px\" id=\"sel_avatar\" name=\"file_gallery\" onChange=\"load_avatar();\"><option></option>";
                $dh = opendir('../images/avatar/gallery/');
                $ava_name='Аватар ';
                $nom=0;
				while($file = readdir($dh))
				{
					if ($file=='.') continue;
					if ($file=='..') continue;
                    $nom++;
					echo "<option value=\"$file\">$ava_name".$nom."</option>\n";
				}
				echo"</SELECT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"sel_gallery\" type=\"submit\" value=\"Выбрать этот аватар\">";
                echo '<input type="hidden" name="max" value="'.$nom.'"><input type="submit" name="random" value="Выбрать случайно"> ';
				echo"</form>";
            break;

			case "doupload":
				$endresult = "<font size=\"2\">Аватар закачан</font>";
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
				echo"<tr><td></td><td><center> $endresult  <a href=?func=setup>назад</a> </center></td></tr>";
			break;
		}
	}
    if ($char['win']<50)
    {	
        echo '<tr><td></td><td>Ты '.echo_sex('должен','должна').' одержать 10 побед для выбора аватара из галереи и 80 побед для установки своего аватара</td></tr>';
    }

	echo "<form name=frm method=post>";
    echo '<tr><td>Статус</td><td><input name="status" type="text" value="'.$status.'" size="30" maxlength="20"></td></tr>
		  <tr><td>Город</td><td><input name="gorod" type="text" value="'.$gorod.'" size="30" maxlength="20"></td></tr>
		  <tr><td>Хобби</td><td><textarea name="hobbi" cols="60" class="input" rows="2">' . $hobbbi . '</textarea></td></tr>
		  <tr><td>Инфо</td><td><textarea name="info" cols="60" class="input" rows="8">' . $inffo . '</textarea></td></tr>';
    if ($sex!="male" AND $sex!="female")
    {
        echo '<tr><td>Пол</td><td><select name="sex1">
        <option value="male">Мужской</option>
        <option value="female">Женский</option>
        </select></td></tr>';
    }
    else
    {
        echo '<tr><td><input type="hidden" name="sex1" value="'.$sex.'"></td><td></td></tr>';
    }
    echo '<tr><td>&nbsp;&nbsp;</td></tr>';

    echo '<tr>
    <td>Дата рождения:</td>
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
        echo '<tr><td colspan="2" align="center">Настройка размера видимой карты:</td></tr>';
        echo '<tr><td align="right"><input name="dvij" type="radio" value=0'; if($dvij==0) echo ' checked'; echo'></td><td>Маленькая карта - 6 гексагон</td></tr>';
        echo '<tr><td align="right"><input name="dvij" type="radio" value=1'; if($dvij==1) echo ' checked'; echo'></td><td>Средняя карта - 18 гексагон</td></tr>';
    }
    if ($par>=2)
    {
        echo '<tr><td align="right"><input name="dvij" type="radio" value=2'; if($dvij==2) echo ' checked'; echo'></td><td>Большая карта - 36 гексагон</td></tr>';
    }
    if ($par>=3)
    {
        echo '<tr><td align="right"><input name="dvij" type="radio" value=3'; if($dvij==3) echo ' checked'; echo'></td><td>Огромная карта - 60 гексагон</td></tr>';
    }
	echo '
    <tr><td></td><td align="left"><br>Количество отображаемых гекс карты вокруг зоны досягаемости хода (не более 10 гекс): <input name="geksa_view1" size="4" maxsize="2" type="text" value="'.$geksa_view.'"></td></tr>
	<tr><td></td><td align="left"><input name="view_chat" type="checkbox" value="1"'; if($view_chat=='1') echo ' checked'; echo'>  Установите флажок, чтобы видеть общий чат игры</td></tr>';
    /*
	<tr><td></td><td>
	<b><u>Выберите вариант чата игры:</u></b><br>
	<table>
	<tr><td><input type="radio" name="chat_var" value=0'; if($chat_variant==0) echo ' checked'; echo'></td><td> Чат "AJAX". Чат по технологии AJAX. Работает без перезагрузки. Требует современные браузеры. Уменьшенный траффик.</td></tr>
	<tr><td><input type="radio" name="chat_var" value=1'; if($chat_variant==1) echo ' checked'; echo'></td><td> Чат "JS". Чат по технологии JavaScript. Работает c перезагрузкой. Не требует современные браузеры, но может не работать в старых версиях браузеров. Небольшой траффик.</td></tr>
	<tr><td><input type="radio" name="chat_var" value=2'; if($chat_variant==2) echo ' checked'; echo'></td><td> Чат "HTML". Чат по технологии HTML. Работает c перезагрузкой. Использует стандартные средства HTML. Работает в любых браузерах. Большой траффик.</td></tr>	
	</table>
	</td></tr>
    */
    echo '<tr><td></td><td align="left"><br><input name="view_smile" type="checkbox" value="1"'; if($view_smile=='1') echo ' checked'; echo'>  Установи флажок, если ты хочешь пользоваться смайлами в чате и на форуме</td></tr>
    <tr><td></td><td align="left"><input name="send_mail" type="checkbox" value="1"'; if($send_mail=='1') echo ' checked'; echo'>  Установи флажок, чтобы получать от администрации письма на e-mail, указанный при регистрации: &lt;'.$email.'&gt;</td></tr>
	<tr><td></td><td align="left"><input name="work_IP" type="checkbox" value="1"'; if($work_IP=='1') echo ' checked'; echo'> Если у тебя установлен фильтр на слово RPG, установи этот флажок. В этом случае игра будет работать через IP адреса.</td></tr>
	<tr><td></td><td align="left"><input name="send_pm" type="checkbox" value="1"'; if($send_pm=='1') echo ' checked'; echo'> Установи флажок, если хочешь, чтобы все личные письма, написанные тебе по игровой почте, дублировались на твой email адрес - &lt;'.$email.'&gt;</td></tr>
	<tr><td></td><td align="left"><input name="only_IP" type="checkbox" value="1"'; if($only_IP=='1') echo ' checked'; echo'> Установи этот флажок, чтобы запретить вход под твоим персонажем с других IP адресов. Твой персонаж будет "привязан" к текущему IP адресу - '.$_SERVER['REMOTE_ADDR'].'. Это стоит делать, если у тебя постоянный внешний IP адрес в сети Интернет. Данную настройку можно сбросить, воспользовавшись функцией восстановления пароля на главной странице по адресу http://www.rpg.su/index.php?option=for. 
	<font size=3 color=red><b>ВНИМАНИЕ! НЕ УСТАНАВЛИВАЙ ГАЛКУ, ЕСЛИ ТЫ НЕ ПОНИМАЕШЬ МЕХАНИЗМА РАБОТЫ ДАННОЙ ФУНКЦИИ!!!</b></font></td></tr>';
	/*
	<tr><td></td><td align="left"><input name="send_ICQ" type="checkbox" value="1"'; if($send_ICQ=='1') echo ' checked'; echo'>  Установите флажок если вы хотите получать оповещения о важных событиях по ICQ</td></tr>
	<tr><td></td><td>
	<b><u>Настройка системы оповещения по ICQ:</u></b><br>
	<table>
	<tr><td><input type="text" name="ICQnumber" value='.$ICQnumber.'></td><td> Укажите номер вашего ICQ (его никто не увидит, он будет использоваться только службой рассылки оповещений)</td></tr>
	<tr><td colspan=2><input name="ICQ_attack" type="checkbox" value="1"'; if($ICQ_attack=='1') echo ' checked'; echo'> Оповещать о нападении на вашего персонажа другими игроками</td></tr>
	<tr><td colspan=2><input name="ICQ_bot" type="checkbox" value="1"'; if($ICQ_bot=='1') echo ' checked'; echo'> Оповещать о нападении на вашего персонажа агрессивными монстрами</td></tr>
	<tr><td colspan=2><input name="ICQ_inboy" type="checkbox" value="1"'; if($ICQ_inboy=='1') echo ' checked'; echo'> Оповещать о призыве помочь в бою от соклановцев</td></tr>
	<tr><td colspan=2><input name="ICQ_pm" type="checkbox" value="1"'; if($ICQ_pm=='1') echo ' checked'; echo'> Оповещать о получении письма от других игроков</td></tr>
	</table>
	</td></tr>
	*/
	echo '<tr><td colspan="2" align="center"><br><input name="submit" type="submit" value="Сохранить"></td></tr>
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
	echo'Настройки изменены<br>';
}

	//ПСЖ для игрока
	echo '<br /><br /><br />';
	$check_psg=mysql_num_rows(myquery("SELECT user_id FROM game_users_psg WHERE user_id='".$char['user_id']."'"));
	if (isset($_POST['del_user']))	
	{
		echo '<font color="red">';
		if ($char['clan_id']<>0)
		{
			echo 'Данная операция недоступна персонажу, состоящему в клане!<br>';
		}
		else
		{
			if ($check_psg==0)
			{
				myquery("INSERT INTO game_users_psg (user_id, banned_date) VALUES ('".$char['user_id']."', ".time()." ) ");
				echo 'Ваш персонаж заблокирован!';
				$check_psg=1;
				$_SESSION['banned']=time();
				$topic_check=myquery("SELECT id FROM forum_topics WHERE top like 'ПСЖ (удалить По Собственному Желанию)'");
				while (list($topic_id)=mysql_fetch_array($topic_check))
				{
					myquery("INSERT INTO forum_otv (topics_id, text, user_id, timepost) VALUES ('".$topic_id."', 'ПСЖ', '".$char['user_id']."', ".time().") ");
					myquery("UPDATE forum_topics SET last_date=".time().", last_user='".$char['user_id']."', otv=otv+1 WHERE id='".$topic_id."'");
				}
			}
			else
			{
				myquery("DELETE FROM game_users_psg WHERE user_id='".$char['user_id']."' ");
				echo 'Ваш персонаж разблокирован!';	
				$check_psg=0;
				unset($_SESSION['banned']);
				$topic_check=myquery("SELECT id FROM forum_topics WHERE top like 'ПСЖ (удалить По Собственному Желанию)'");
				while (list($topic_id)=mysql_fetch_array($topic_check))
				{
					myquery("DELETE FROM forum_otv WHERE user_id='".$char['user_id']."' AND text like 'ПСЖ' AND topics_id='".$topic_id."' ");
					myquery("UPDATE forum_topics SET last_date=".time().", last_user='".$char['user_id']."', otv=otv-1 WHERE id='".$topic_id."'");
				}
			}
		}
		echo '</font>';
	}	
	
	if ($check_psg==1) { $button_name="Разблокировать персонажа"; }
	else { $button_name="Заблокировать персонажа"; }

	echo '<fieldset style="font-weight:normal;font-size:12px;color:#FF6347;margin-left:55px;width:650px;margin-bottom:30px;padding:15px;"><legend><b>Удаление персонажа</b></legend><form name=newpass method=post>&nbsp;&nbsp;&nbsp;Вы можете запустить процесс удаления своего персонажа, нажав на соответствующую кнопку. В течение недели Вы можете отменить своё решение. Через неделю персонаж будет заблокирован, а через 4 недели после подачи заявки автоматически удалён.<br />';
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
		echo'<br><br><b><font color=red>&nbsp;&nbsp;&nbsp;Пароль изменен!</font></b><br>';
		$email = mysql_result(myquery("SELECT email FROM game_users_data WHERE user_id='$user_id'"),0,0);

		$result=myquery("update game_users set user_pass='".$newpass1."' where user_id='$user_id'");
        
        include("class/class_email.php");

		$message  = "[http://".domain_name."] Средиземье :: Эпоха сражений. Изменение пароля!\n\n";
		$message .= "Твой новый пароль: ".$_POST['newpass']."\n\nС уважением, администрация RPG.SU";

		$subject = 'Средиземье :: Эпоха сражений [Изменение пароля]';

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
    echo'<br /><br /><br /><fieldset style="font-weight:normal;font-size:12px;color:#2FF5FB;margin-left:55px;width:650px;margin-bottom:30px;padding:15px;"><legend><b>Изменение пароля на вход в игру</b></legend><form name=newpass method=post>&nbsp;&nbsp;&nbsp;Ты можешь изменить свой пароль на вход в игру. Для этого тебе надо указать свой старый и новый пароль в соотв.полях формы:<br /><br>&nbsp;&nbsp;&nbsp;<input type=password name=pass size=10> Введи текущий пароль<br>&nbsp;&nbsp;&nbsp;<input type=password name=newpass size=10> Введи новый пароль <br>&nbsp;&nbsp;&nbsp;<input type=password name=newpass2 size=10> Повтори новый пароль еще раз<br /><br /><input name="subm" type="submit" value="Сменить пароль на вход в игру"></form></fieldset>';
}

OpenTable('close');
echo'</td><td width="172" valign="top">';

include('inc/template_stats.inc.php');

echo'</td></tr></table>';
set_delay_reason_id($user_id,25);

if (function_exists("save_debug")) save_debug(); 

?>