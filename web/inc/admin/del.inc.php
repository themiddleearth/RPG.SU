<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['del'] >= 1)
{
	if (!isset($see))
	{
		echo'<div id="content" onclick="hideSuggestions();"><br><center><form action="" method="post">
		������� ������ �� ����: <input name="name" type="text" value="" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div> <input name="submit" type="submit" value="�������">
		<input name="see" type="hidden" value="">
		</form></div><script>init();</script>';
	 }
	 else
	 {
		$prov=myquery("select * from game_users where name='$name' and clan_id!='1'");
		if(!mysql_num_rows($prov)) $prov=myquery("select * from game_users_archive where name='$name' and clan_id!='1'");
		if(mysql_num_rows($prov))
		{
			$user=mysql_fetch_array($prov);
			$name = $user['name'];
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 '������ �� ���� ������: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());

			$klad = myquery("SELECT id FROM game_maps WHERE name='��������'");
			if ($klad!=false)
			{
				list($mapid) = mysql_fetch_array($klad);
				$sel = myquery("SELECT id,item_id FROM game_items WHERE user_id='".$user['user_id']."' AND priznak=0");
				while ($item = mysql_fetch_array($sel))
				{
					$flag = @mysql_result(@myquery("SELECT COUNT(*) FROM game_shop_items WHERE items_id='".$item['item_id']."' AND shop_id NOT IN (SELECT id FROM game_shop WHERE view='0')"),0,0);
					if ($flag==0 AND $mapid>0)
					{
						// ���� � ������� ���. ���������� �� �����
						list($dim_x,$dim_y) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$mapid ORDER BY xpos DESC, ypos DESC LIMIT 1"));
						$sellab = myquery("SELECT xpos,ypos FROM game_maze WHERE (move_up+move_down+move_left+move_right)<=1 AND map_name=$mapid AND (xpos<>0 AND ypos<>0) AND (xpos<>$dim_x AND ypos<>$dim_y)");
						$all = mysql_num_rows($sellab);
						$r = mt_rand(0,$all-1);
						mysql_data_seek($sellab,$r);	
						$pos_array = mysql_fetch_assoc($sellab);
						$posx = $pos_array['xpos'];
						$posy = $pos_array['ypos']; 
						$up = myquery("UPDATE game_items SET map_name=$mapid, map_xpos=$posx, map_ypos=$posy, user_id=0, priznak=2, item_cost=0, ref_id=1 WHERE id='".$item['id']."'");
					}
					else
					{
						// ��������� � ���������. �������
						$del=myquery("delete from game_items where id='".$item['id']."'");
					}
				}
			}
			if (admin_delete_user($user['user_id'])==1)
			{
				echo'<center>����� ������� ������ �� ���� ������</center>';
				echo'<meta http-equiv="refresh" content="1;url=?opt=main&option=users_del">';
			}
			else
			{
				echo '��������� ������ ��� �������� ������';
			}
		}
		else
		{
			echo'<center>����� �� ������';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>