<?

if (function_exists("start_debug")) start_debug(); 

include('craft/func.inc.php');
if (!isset($_GET['act']))
  $act = '00';
else
  $act = $_GET['act'];

if (isset($act))
{
	if ($act=='build')
	{
		echo'<br>';
		QuoteTable('open');
		include('craft/stroitel.php');
		QuoteTable('close');
	}
	
	$selbuild=myquery("select * from craft_build_user where x='".$char['map_xpos']."' and y='".$char['map_ypos']."' and map=".$char['map_name']."");
	if ($selbuild!=false and mysql_num_rows($selbuild)>0)
	{
		if (isset($_GET['act']) AND $_GET['act']=='01')
		{
		}
		else 
		{
			echo'<br>����� �� ������ ��������� ���������:<br><br>';
			QuoteTable('open');
		}
		while ($build=mysql_fetch_array($selbuild))
		{
			$build_id=$build['id'];
			$build_type=$build['type'];
			$build_dohod=$build['dohod'];
			$build_gold=$build['gold'];
			$build_user=$build['user_id'];
			$build_sell=$build['sell'];
			$build_template = mysql_fetch_array(myquery("SELECT * FROM craft_build WHERE id=$build_type"));
			$buildname = $build_template['name'];
		
			if($build['status']=='0')
			{
				echo'����� �������� ������!';
			}
			elseif ($act=='01')
			{
				if ($build_template['include']=='sawmill')
				{
					list($usec, $sec) = explode(" ", microtime());
					$capt = ((float)$usec + (float)$sec);
					$_SESSION['captcha'] = $capt;
					$_POST['digit'] = $capt;
				}
				if (($build_user!=$user_id) AND ($build_sell<1))
				{
					include('craft/act/02.php');
				}
				else
				{
					include('craft/act/01.php');
				}
			}
			elseif ($act=='02')
			{
			include('craft/act/02.php');
			}
			elseif ($act=='03')
			{
			include('craft/act/03.php');
			}
			elseif ($act=='04')
			{
			include('craft/act/04.php');
			}
			elseif ($act=='05')
			{
			include('craft/act/05.php');
			}
			elseif ($act=='06')
			{
			include('craft/act/06.php');
			}
			elseif ($act=='07')
			{
			include('craft/act/07.php');
			}
			elseif ($act=='08')
			{
			include('craft/act/08.php');
			}
			else
			{
				$selcraft=myquery("select * from craft_build where id='".$build_type."'");
				if (mysql_num_rows($selcraft))
				{
					$building=mysql_fetch_array($selcraft);
					if ($building['dom']!='1')
					{
						if ($user_time < $char['delay'] AND $char['block']==1)
						{
							echo '<b><font color="yellow">'.$building['name'].'</font></b><br>';
						}
						else
						{
							echo "<a href=?func=main&act=01&".$building['include'].">".$building['name']."</a><br>";
						}
						if ($building['opis']!='') echo "<br>$building[opis]<br><br>";
						echo"���������� ������� ���� <b>������ - ";
						rab($build_id);		
						echo" / �� - $building[col] </b>";
						rab_names($build_id);
						if ($building['include']=='')
						{
							echo"<br>����� ������ <font color=yellow>$building[rab_time]</font> ���";
							echo"<br>��������: ";
							user($build_user);
							echo '<br><b><font color=yellow>����� �� ������:</font><br>';
							dohod($build_dohod, $build_gold);
							if ($build_dohod=='' and $build_gold<='0') echo ' <font color=red>���</font>';
						}
					}
						
					if ($building['dom']=='1')
					{
						echo "$building[name]!<br>�����������: $building[col] <br>�������� ";
						user($build_user);
						if ($build_user==$user_id) echo'<br><br><a href=?func=main&act=07>�����</a><br><a href="?func=main&act=05">��������� �� �������<a>';
						if ($build_sell>=1) echo'<br><br>��� ������ ���������� �� ������� �� '.$build_sell.' �������!';
						if ($build_sell>=1 and $build_user==$user_id) echo'<br><a href="?func=main&act=06">������<a>';
					}
				}
			}
		}
		if (isset($_GET['act']) AND $_GET['act']=='01')
		{}
		else 
		{
			QuoteTable('close');
			echo'<br>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>