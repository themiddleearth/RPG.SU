<?

if (function_exists("start_debug")) start_debug(); 

$stroitel=$char['stroitel'];
if ($stroitel>=1)
{
	echo'<b>���� ������������� ���������: '.$stroitel.'</b><br>�������� ��������� ��� ��������� ������:<br><br>';

	if (isset($create))
	{
		$create=(int)$create;
		//�������� ��������
		if ($char['clan_id']==1)
		{
			$select3=myquery("select * from craft_build where id='$create'");
		}
		else
		{
			$select3=myquery("select * from craft_build where admin=0 and id='$create'");
		}
		if (mysql_num_rows($select3))
		{
			$build3=mysql_fetch_array($select3);

			//�������� ������ �������������
			if ($build3['lev_need']>$stroitel)
			{
				$no=1;
				echo '<font color=red>��������� ������� ������������� ���������</font><br>';	
			}
		
			if ($build3['res_need']!='')
			{
				$a=explode("|",$build3['res_need']);
				for ($i=0;$i<count($a);$i++)
				{
					$b=explode("-",$a[$i]);
					$select1=myquery("select * from craft_resource_user where res_id='$b[0]' and user_id='$user_id' and col>='$b[1]'");
					if (!mysql_num_rows($select1)) 
					{
						$no=1;
						echo '��������� �������: ';	
						$select=myquery("select * from craft_resource where id='$b[0]'");
						$build=mysql_fetch_array($select);
						echo "$build[name]<br>";
					}
				}
			}
		
			//�������� �����
			if ($build3['cost']>$char['GP'])
			{
				$no=1;
				echo '<br><font color=red>��������� �����</font>';	
			}
			
			$already = myquery("SELECT * FROM craft_build_user WHERE map=".$char['map_name']." AND x=".$char['map_xpos']." AND y=".$char['map_ypos']."");
			if (mysql_num_rows($already)>0)
			{
				$no=1;
				echo '<br><font color=red>�� ������ ��������� ������ 1 ������ �� ������ �����</font>';	
			}
			
			//���� ��� ������� ������
			if (!isset($no) or $user_id==1 or $user_id==612)
			{
				if ($build3['res_need']!='')
				{
					$a=explode("|",$build3['res_need']);
					for ($i=0;$i<count($a);$i++)
					{
						$b=explode("-",$a[$i]);
						$select1=myquery("update craft_resource_user set col=GREATEST(0,col-$b[1]) where user_id='$user_id' and res_id='$b[0]'");
					}
				}	
				$query = "INSERT INTO craft_build_user (map, x, y, user_id, type, create_date, create_time, dohod, gold, sell, status) VALUES ('".$char['map_name']."', '".$char['map_xpos']."', '".$char['map_ypos']."', '$user_id', '".$build3['id']."', '".time()."', '".$build3['create_time']."', '', 0 ,0 ,'0')";
				myquery($query);
				myquery("UPDATE game_users SET gp=gp-$build3[cost], func='craft',hod=".time()." WHERE user_id='$user_id'");
				setGP($user_id,-$build3['cost'],15);
				echo'������ ����� ��������� ����� '.$build3['create_time'].' ���.<script>location.replace("craft.php");</script>';
			}
		}
	}

	if (isset($id))
	{
		$id=(int)$id;
		if ($char['clan_id']==1)
		{
			$select=myquery("select * from craft_build where id=$id");
		}
		else
		{
			$select=myquery("select * from craft_build where admin=0 and where id=$id");
		}
		if (mysql_num_rows($select))
		{
			$build=mysql_fetch_array($select);
			if ($build['dom']=='0')
			{
				echo"<font color=yellow><b>$build[name]</b></font><br>������� ����: <font color=yellow>$build[col]</font><br><br>����������:<br>";
				dohod($build['res_dob'],0);
				echo"<br><b><font color=red>������� ��� ���������:</font></b><br>";
				treb($build['res_need']);
				echo"<br>��������� ������� �������������: <font color=red>$build[lev_need]</font><br>";

				echo"<br>�����������:";
				if ($build['race']!=0) echo"<br> ����� �������� ������ ���� ".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$build['race'].""),0,0)."";
				elseif ($build['clevel']<'0') echo"<br> �������� ����� ����� $build[clevel] ������";
				elseif ($build['item']!='' and $build['item']!='0') echo"<br> ��� ������ ��������� ������� $build[item]";
				else echo" ���.";
			}

			if ($build['dom']=='1')
			{
				echo"<b>$build[name]</b><br>������� : $build[col] ���������<br>";
				echo"<br>������� ��� ���������:<br>";
				treb($build['res_need']);
				echo"��������� ������� �������������: $build[lev_need]<br>";

				echo"<br>�����������:";
				if ($build['race']!=0) echo"<br> ������� �������� ����� ������ ���� ".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$build['race'].""),0,0)."";
				elseif ($build['clevel']<'0') echo"<br> ������� �������� ����� ����� $build[clevel] ������";
				else echo" ���.";
			}

			echo "<br><br>���� <font color=red><b>$build[cost]</b></font> �������<br><a href=?func=main&act=build&create=$build[id]>���������</a>";
		}
	}


	if (!isset($id) and !isset($create))
	{
		if ($char['clan_id']==1)
		{
			$select=myquery("select * from craft_build");
		}
		else
		{
			$select=myquery("select * from craft_build where admin=0");
		}
		while ($build=mysql_fetch_array($select))
		{
			if ($build['lev_need']>$stroitel) echo '<font color=aaaaff>';
			else echo '<font color=#80FFFF>';
			echo'<a href=?func=main&act=build&id='.$build['id'].'>'.$build['name'].'</a> - '.$build['cost'].'</font><br>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>