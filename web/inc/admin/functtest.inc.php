<?

if (function_exists("start_debug")) start_debug(); 

if (domain_name == 'testing.rpg.su' or domain_name == 'localhost')
{
	echo '<center><h2><font color="yellow">������� ��� ������������</font></h2>';
	if (isset($_GET['house']))
	{	
		function delete_house($user_id,$build_id=0,$town=0)
		{
			//���� $build_id=0 - ������� ��� ��������� ��-�� ������� � ������� ���� �����
			//����� ������� ���������� $build_id ������ �� houses_users ��-�� �������
			//��� �������� ���� - ��-�� ������� - ��������� ������ �� �������. 
			//���������� �������� �� �������� � ��� ��������� (����� ���������)
			$weight = 0;
			
			// ���� ������ �������� ���, �� ���������� ��� ������ � ������
			if ($build_id>=1 AND $build_id<=4)
			{
				$build_id = 0;
			}
			
			if ($build_id == 0)//������� ���
			{
				$selitems = myquery("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=".$user_id." AND game_items_factsheet.type<>13 AND (game_items.town=".$town." OR ".$town."=0) GROUP BY game_items.user_id");
				myquery("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=".$user_id." AND (game_items.town=".$town." OR ".$town."=0) ");
				$weight = 0;
				if (mysql_num_rows($selitems)) 
				{
					$weight += mysql_result($selitems,0,0);			
				} 
			}
			//���������� �������� �� �������� � ��� ���������
			if ($build_id==0 OR ($build_id>=13 AND $build_id<=16))//������� ��������� ���������
			{
				$selitems = myquery("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items_factsheet.type=13 AND (game_items.town=".$town." OR ".$town."=0) GROUP BY game_items.user_id");
				myquery("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=$user_id AND (game_items.town=".$town." OR ".$town."=0) ");
				$weight = 0;
				if (mysql_num_rows($selitems)) 
				{
					$weight += mysql_result($selitems,0,0);			
				} 
			}
			//���������� ������� �� ���������
			if ($build_id==0 OR ($build_id>=9 AND $build_id<=12))//������� ��������� ��������
			{
				$hransel = myquery("SELECT crm.id, crm.col FROM craft_resource_market crm WHERE crm.user_id=".$user_id." AND crm.priznak=1 AND (crm.town=".$town." OR ".$town."=0)");
				$weight=0;
				if ($hransel!=false AND mysql_num_rows($hransel)>0)
				{
					while ($hran = mysql_fetch_array($hransel))
					{
						$Res = new Res(0, 0, $user_id);
						$Res->take_house(0, $hran['id'], (int)$hran['col']);
					}
				}				
			}
			
			//������� � ������� ����
			if ($build_id==0 OR ($build_id>=6 AND $build_id<=8))//������� ������
			{
				myquery("DELETE FROM game_users_horses WHERE user_id=".$user_id." AND used=0 AND (town=".$town." OR ".$town."=0) ");	   
			}
			
			// ������� ��������� ������
			if ($weight > 0) 
			{
				myquery("UPDATE game_users SET CW=CW+".$weight." WHERE user_id=".$user_id."");
				myquery("UPDATE game_users_archive SET CW=CW+".$weight." WHERE user_id=".$user_id.""); 
			}
			
			// ������� ����/��������� ������ �� �������������			
			if ($build_id==0)
			{
				//������� ��� ���������+���
				myquery("DELETE FROM houses_market WHERE user_id=".$user_id." AND (town_id=".$town." OR ".$town."=0)");				
				myquery("DELETE FROM houses_users WHERE user_id=".$user_id." AND (town_id=".$town." OR ".$town."=0)");
				if (mysql_num_rows(myquery("SELECT * FROM houses_users WHERE user_id=".$user_id."")) == 0)
				{
					myquery("DELETE FROM houses_nalog WHERE user_id=".$user_id."");
				}
			}
			else
			{
				//������� ���������� ������/��������� 
				myquery("DELETE FROM houses_market WHERE user_id=$user_id AND build_id=$build_id AND (town_id=".$town." OR ".$town."=0) ");
				myquery("DELETE FROM houses_users WHERE user_id=$user_id AND build_id=$build_id AND (town_id=".$town." OR ".$town."=0) ");
			}
						
			//������ ������ �����������
			if ($build_id==0)
			{
				$theme = '��� ���� ��������� ���� ����������!';
				$post = '� ����� � ���������� ����������� ������� ��� ���� ��������� ���� ����������!';
			}	
			else
			{
				list($name) = mysql_fetch_array(myquery("SELECT name FROM houses_templates WHERE id = ".$build_id." "));
				$theme = '���� ��������� <b>'.$name.'</b> ���� ����������!';
				$post = '� ����� � ���������� ����������� ������� ���� ��������� <b>'.$name.'</b> ���� ����������!';
			}
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$user_id."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
		}

		$da = getdate();
		
		//������ � �������� � ��������� ����
		//�������� � ������� ������������������� ������
		$sel = myquery("SELECT build_id, user_id, town_id FROM houses_users WHERE type>1 AND ((doska_repair>doska AND doska>0) OR (stone_repair>stone AND stone>0)) ");
		while ($ch=mysql_fetch_array($sel))
		{
			$user_id = $ch['user_id'];
			delete_house($user_id, $ch['build_id'], $ch['town_id']);
		}

		//�������� ����� ���������
		$sel = myquery("SELECT SUM(houses_nalog.nalog-houses_nalog.pay) AS summa, houses_nalog.user_id, houses_users.square AS square FROM houses_nalog,houses_users WHERE houses_users.user_id=houses_nalog.user_id AND houses_users.type=1 GROUP BY houses_nalog.user_id HAVING summa>=(square*700)");	
		while ($ch=mysql_fetch_array($sel))
		{
			$user_id = $ch['user_id'];
			delete_house($user_id, 0, 0);
		}
		
		myquery("INSERT INTO houses_nalog (user_id,nalog,nalog_time)
                            SELECT hu.user_id, @nalog := SUM(hu.square)*50, ".mktime(23,59,59,$da['mon'],$da['mday'],$da['year'])."
                            FROM houses_users hu WHERE hu.type=1 GROUP BY user_id
							ON DUPLICATE KEY UPDATE houses_nalog.nalog=houses_nalog.nalog+@nalog");
		
		myquery("UPDATE houses_users SET stone_repair=stone_repair+stone*0.1, doska_repair=doska_repair+doska*0.1");
		
		//������� ����������� � ���, ��� �������� ����� �� ���
		myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder)
				 SELECT hu.user_id, 0, '�������� ����� �� ���', concat('����� ������ ������ �� ��� ���������� <b>', round(SUM(hn.nalog-hn.pay),2), '</b> �����'), '0','".time()."', 1			 
				 FROM houses_nalog hn, houses_users hu WHERE hu.user_id=hn.user_id AND hu.type=1 GROUP BY hu.user_id");	

		echo '<br>����� ��������� �������!';
	}	
	
	echo '</center><ol>';
	echo '<li><a href=admin.php?opt=main&option=functtest&house>������ ������ �� ���</a></li>';	
	echo '</ol>';		
}

if (function_exists("save_debug")) save_debug(); 

?>