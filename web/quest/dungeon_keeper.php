<?Php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
require_once('../inc/lib_session.inc.php');
?>
<html>
<head>
<title>���������� :: ����� �������� :: RPG online ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="��������������������� RPG OnLine ���� �� �������� ��.�.�.�������� '��������� �����' - ����� ������� ���� �� ������������� ������������">
<meta name="Keywords" content="���������� ����� �������� ��������� ����� ������� Lord of the Rings rpg ������� ������� ������ ���� online game �������� ��� ������� ����� ����� �� �������">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<?
//��������� ��� �������� ���������� ������ �������� ������ ���������� - �������� ��� ����������!!!
$map_level_id=array(691=>1,692=>2,804=>3); 
//��������, ��� ���� ��������� �� ������� ������� ����� �� ���� ���������� - �������� ��� ����������!!!
if(($char['map_name']==691 OR $char['map_name']==692 OR $char['map_name']==804) AND $char['map_xpos']==0 AND $char['map_ypos']==0)
{
	?>
	<table width="100%"><tr><td width="256">
	<img src="http://<?=img_domain;?>/nav/dungeon_keeper.gif" align="middle" height="400" width="256"></td><td>
	<?
	OpenTable('title',"100%","400");
	echo'<br><center><font size=4 face=verdana color=#fce66b>�������� ������</font><br><br>';
	echo '<hr align=center size=2 width=80%>';
	
	$level=$map_level_id[$char['map_name']];
	$field1 = 'level'.$level.'_success';
	$field2 = 'level'.$level.'_quest';
	$field3 = 'level'.$level.'_quests_count';
	
	//������ ��������� � ����������
	if (isset($_GET['talk']))
	{
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		if ($char['map_name']==691)
		{
			echo '<font color=#aaffa8>����������� ����, '.echo_sex('�������� �������������','�������� �����������������').'! � ����� ���, ��� �� '.echo_sex('�����','������').' ������ ���� ����� � ������������ ����� �������� ���� �� ������� �������, ������� ��������� ��. �������, �� ������ '.echo_sex('������������','�������������').' � ������, ��� ��� � �������� ���� ���� ����� ������������ ��������� ���������, �� ���� ���� �� ������ ���������, �� �� ������ ���������� � ������������ ����� ����. ��������� ������� ����� ��������� ���, � ���� �� ���������, �� ������ �������� � ���� ������� �� �� �����������. ������ � �� ����� �������� � �����������, � ���� � ��� ������, ��� �� ������������� ���������� ��� �������, ���� �������� ��������� �����-������ ������������� �� ����������, ��������, ���-�� � ���� ������� �������. ���� �� �������� '.echo_sex('��������','���������').' ������ ��� � ������� ��������� ��� ��� �������, ����� ��� ���� ����� ������� ������ � �� �������� ��������� ���������� �������� �������! ������!</font>';
		}
		elseif ($char['map_name']==692)
		{
			echo '<font color=#aaffa8>����������� ����, '.echo_sex('�������� �������������','�������� �����������������').'! � ��� ��� �� ����� '.echo_sex('�������','��������').' ���������� �����. ����� ���� ��� �� '.echo_sex('���������','����������').' ������ ������� ����������, ����� ������� ���������, �� ������ ��� ����� ����� ����� ���� ������. ��� �� ������, � ����������� ����� �� �������� ������ ������ ������. ������� ������ ������� ������ ���� ���������� ���������� ��� ������ ������� �����. �� ����� ����� �� �����������, �� ������� ������ �� ����. ����� ������ �� ����� ������ ����������� �� �� ������, �� �������� ������ ����. �� ��� ���� ������ �������� ������ � ������ ���� � ��������, �� ����� ������� ����� ��� ���������� ������� ����. ���� ������� ���������� ���� ������ ������� ����������. �������� �����, ����� �������� � ������ ����������� �������� ������� ����, ��� ��������� ����� ������ ������� ������. ��� ��� ����� ���� ������� ������. ��, ��������� �� �� ��� � ������� �� �� ������ �����!!! ����, � ��� �� ���, ��� ��� � ���������. ��� ������� ������ �� ����� ������, ��� ��. ������ ��� ���������� ������ �������, � �� �������� ��������� ��������������!</font>';
		}
		elseif ($char['map_name']==804)
		{
			echo '���� ������! �� ��-���������� ������� ������! � ���� �� ���� '.echo_sex('�����','�������').' �� ��� '.echo_sex('�������','��������').', �� � ������� �� �� ���������� ������ ����� ��� ��, ��� '.echo_sex('������','������').' ��. ���� �������� ��� �������? �����, �����, �� ���������, ��������� ���������, ����� �� ���������� ��������� ������. �-�����, �� ������� ������ ��������� ��� ����, ��� �� ������. ��������� ������������ � �����-�� ������ �������� �����������, ����� ������� ��������� ����� ������. �� ����� �� � � ���� �����������. �� �� ������� ������ ����, ���� ��������� ��� �������. �� ���� ���� ��� ��� �������� ��������� ��� ����, ����� ��������� ����, ���� �� ���������� 3 �������, �� �� �� ���-�� ���������. ������, ������� ��� ��� �� ���� ��� � ���� ��� �� �� ������ ��� �� ��� � ����� ����� ��������� � �����-������ ������ � ����� ��������. ������� ������� � ������! ���� ���,- �����! �� ���� ����� ����� ���� �� ���� ��������. �� ������� ������� ���� ������� ����!';
		}
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?choice=1" target="game">�������� �������</a>';
		echo '<br><a href="?choice=2" target="game">����������� ���� ������� �������</a>';
		echo '<br><a href="?choice=3" target="game">���������� � ���������� ������� (����� �������)</a>';
		echo '<br><a href="?begin" target="game">��������� ��������</a><br><br>';
	}
	//���� ����� ����� � ��, �������
	elseif (isset($_GET['exit']))
	{
		echo '<br>';
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		echo '<font color=#ff4433><b>"���� �� ������� �� ����������, �� ������� ��������� ���� �� �����, ��� ����� 10 �����"</b>, - ����������� ���� �������� �� ������. - <b>"�� ����� ������ ����� �� �����������?"</b></font><br><br>';
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?do_exit" target="game">��, � ���� ����� �� ���������� � ����������</a><br>';
		echo '<br><a href="?begin" target="game">���, � '.echo_sex('���������','����������').'</a><br><br>';
	}
	//���� � ������ ����� - ������
	elseif (isset($_GET['do_exit']))
	{
		myquery("UPDATE dungeon_users_data SET last_visit=".time()." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_map SET map_xpos=25,map_ypos=20,map_name=18 WHERE user_id=$user_id");
		setLocation("../act.php");
	}
	//������� � �� ���������� � �������
	elseif (isset($_GET['task']) and isset($_SESSION['dungeon']['quest_id']))
	{
		$quest_id=$_SESSION['dungeon']['quest_id'];		
		include("dungeon_inc/dungeon_quests.php");
		myquery("UPDATE dungeon_users_data SET ".$field2."=".$quests[$level][$quest_id]['id']." WHERE user_id=".$user_id."");
		for($i=1; $i<=count($quests[$level][$quest_id]['res']); $i++)
		{
			$id=$quests[$level][$quest_id]["res"][$i]["id"];
			$col=$quests[$level][$quest_id]["res"][$i]["kol"];
			myquery("INSERT INTO dungeon_users_progress (user_id,quest_id,res_id,res_num) VALUES (".$user_id.",".$quests[$level][$quest_id]['id'].",".$id.",".$col.")");
		}
		unset($_SESSION['dungeon']['quest_id']);
		setLocation("?talk");
	}	
	//���� ������ ���� �� ������� ��������� ��� ���� ����� ��������
	elseif (isset($_GET['choice']) OR (isset($_POST['choice']) AND $_POST['choice']==3))
	{
		if(isset($_GET['choice'])) $choice = $_GET['choice'];
		else $choice=3;
		//���� ����� �����
		if ($choice==1)
		{
			//��������, ��� �� � ����� ��� �������
			list($current_quest)=mysql_fetch_array(myquery("SELECT ".$field2." FROM dungeon_users_data WHERE user_id=".$user_id.""));
			if($current_quest!=0)
			{
				//c�����, ��� ����� ��� ����
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
				echo '<font color=#aaffa8><b>�������-��</b>, - ��������� ���������� �������� �� ������ ��������. - <b>�� �� ��� ���������� ���� �������! ������� ������� ����, � ����� ��� �������� ������.</b></font><br><br>';
				echo '</p></tr></td></table>';
				echo '<hr align=center size=2 width=80%>';
				echo '<br><a href="?talk" target="game">���������</a><br><br>';
			}
			else 
			{
				//������ �������
				//0 - ���������, �� ����� ������� ��� ���� ������
				//c������ ������� �� ������ ������
				include("dungeon_inc/dungeon_level_count.php");
				//���� � ����� ���� ������� �� ���� ������
				if($quests_num[$level]>0)
				{
					//1 - ���������, ����� ������� ���� ��� �� ��������
					$level_quests=range(1,$quests_num[$level]);
					//������� ���������, ����� ��������					
					$dones=myquery("SELECT dq.quest_id FROM dungeon_quests_done dqd JOIN dungeon_quests dq ON dqd.quest_id = dq.id WHERE dqd.user_id=".$user_id." and dq.quest_level=".$level."");
					$done_quests=array();
					while (list($done)=mysql_fetch_array($dones))
					{
						$done_quests[count($done_quests)]=$done;
					}
					//������ ��������� ��������� � ������ ����������
					$free_quests=array();
					
					for($i=0;$i<count($level_quests);$i++)
					{
						if(!in_array($level_quests[$i],$done_quests))
							$free_quests[count($free_quests)]=$level_quests[$i];
					}
					
				}else $free_quests=array();
				//���� ��� ������, ������� ���� ��� �� ���������
				if(count($free_quests)==0)
				{
					//�������, ��� ��� ��� ��
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
					echo '<font color=#aaffa8><b>������, �� � ���� ������ ��� ��� ���� �������</b>, - ������ ������ ��������� ����������.</font><br><br>';
					echo '</p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?talk" target="game">���������</a><br><br>';
				}
				else 
				{
					//2 - ������� ���� �� �������������
					$quest_id=$free_quests[array_rand($free_quests,1)];					
					include("dungeon_inc/dungeon_quests.php");
					$caption=$quests[$level][$quest_id]['name'];
					$text=$quests[$level][$quest_id]['description'];
					if(isset($_SESSION['dungeon'])) unset($_SESSION['dungeon']);
					$_SESSION['dungeon']['quest_id']=$quest_id;
					$needle='';
					for($i=1; $i<=count($quests[$level][$quest_id]['res']); $i++)
					{
						$needle.=''.$res[$quests[$level][$quest_id]["res"][$i]["id"]]["name"].' - <b><font color=red>'.$quests[$level][$quest_id]["res"][$i]["kol"].'</font></b> ��<br>';
					}
					$needle=substr($needle,0,strlen($needle)-2);
					
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><UL>';
					echo '<font color=#bcb1ff><LI><b>��� ���� �������:</b><font color=#aaffa8><p> '.$caption.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>����� �������:</b><font color=#aaffa8><p> '.$text.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>� ������������� ��������:</b><font color=#aaffa8><p> '.$needle.'</p></font><br>';					
					echo '</UL></p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?task" target="game">�������</a>';
					echo '<br><a href="?talk" target="game">����������</a><br><br>';
				}
			}
		}
		elseif ($choice==2)
		{
			//�������� �������� �������
			$have_quest=myquery("SELECT dq.quest_id, dud.".$field2." FROM dungeon_users_data dud JOIN dungeon_quests dq ON dud.".$field2."=dq.id WHERE user_id=".$user_id."");			
			include("dungeon_inc/dungeon_level_count.php");
			//��������� ���-�� ���������� �������
			list($quest_id, $id)=mysql_fetch_array($have_quest);
			$dones_num=mysql_num_rows(myquery("SELECT user_id FROM dungeon_quests_done dqd JOIN dungeon_quests dq ON dqd.quest_id = dq.id WHERE dqd.user_id=".$user_id." and dq.quest_level=".$level.""));
			$dones_num.=' �� '.$quests_num[$level];
			echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
			echo '<b><center><font color=#aaffa8>��������� ������� �� '.$level.' ������ ����������: <font color=red>'.$dones_num.'</font></b></center><br><br>';
			//���� ����� ����
			if($quest_id>0)
			{
				include("dungeon_inc/dungeon_quests.php");
				$caption=$quests[$level][$quest_id]['name'];				
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������.<br><br><center>';
				echo '<b>���� ������� �������:</b> '.$caption.'<br><br><b>�������� �����:</b></font>';
				$ress=myquery("SELECT res_id,res_num FROM dungeon_users_progress WHERE user_id=".$user_id." and quest_id=".$id." ");
				for($i=1; $i<=mysql_num_rows($ress); $i++)
				{
					list($id,$got)=mysql_fetch_array($ress);
					for($j=1; $j<=count($quests[$level][$quest_id]["res"]); $j++)
					if($quests[$level][$quest_id]["res"][$j]["id"]==$id)
					{
						$n=$j;
						break;
					}
					$res_name=$res[$id]['name'];
					$need=$got;
					if($need<=0) $font='<font color=#aaffa8>'; else $font='<font color=#ff4433>';
					echo '<br>'.$font.''.$res_name.': '.$need.' ��.';
				}
			}//���� ������ ���
			else 
			{
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������. - <b>���, ������ � ���� ��� �������� �������.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">���������</a><br><br>';			
		}
		//����� �������� �������
		elseif ($choice==3)
		{
			$have_quest=myquery("SELECT dq.quest_id, dud.".$field2." FROM dungeon_users_data dud JOIN dungeon_quests dq ON dud.".$field2."=dq.id WHERE user_id=".$user_id."");			
			list($quest_id, $id)=mysql_fetch_array($have_quest);			
			//���� ����� ����
			if($quest_id>0)
			{			
				include("dungeon_inc/dungeon_quests.php");
				//���� ���� ��� ������, ��� ������ �������
				if(isset($_POST['ress_num']))
				{
					$ress_num=(int)$_POST['ress_num'];
					$check_res = 0;
					for($i=0; $i<$ress_num; $i++)
					{
						//��� ������� ����
						$rid_index='rid'.$i;
						$col_index='col'.$i;
						$res_id=(int)$_POST[$rid_index];
						if(!is_numeric($_POST[$col_index])) $res_col=0;
						else $res_col=max(0,$_POST[$col_index]);
						if ($res_col > 0)
						{
							$res_need=mysql_result(myquery("SELECT res_num FROM dungeon_users_progress WHERE user_id=".$user_id." AND quest_id = ".$id." AND res_id=".$res_id.""),0,0);							
							$res_col=min($res_need,$res_col);								
							$res_result=$res_need-$res_col;							
							$Res = new Res(0, $res_id);
							$check = $Res->add_user(0, $user_id, -$res_col);
							if ($check == 1) //������ ������� ������ � ������
							{
								myquery("UPDATE dungeon_users_progress SET res_num=".$res_result." WHERE user_id=".$user_id." AND res_id =".$res_id." AND quest_id=".$id." ");
								$check_res = 1;
							}
							else
							{
								echo $Res->message;
							}
						}
					}					
					
					//��������, �� �������� �� �����
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><center>';
					$done_check=myquery("SELECT max(res_num) FROM dungeon_users_progress WHERE user_id=".$user_id." and quest_id = ".$id." ");
					list($res_num)=mysql_fetch_array($done_check);
					$done=1;
					if ($res_num>0) $done = 0;
					
					//���� ���������
					if($done==1)
					{
						//����� ��
						//������� ����� ��� ����������
						myquery("INSERT INTO dungeon_quests_done (user_id,quest_id) VALUES (".$user_id.",".$id.")");	
						//�������� ����� ���-�� ���������� �������	
						myquery("UPDATE dungeon_users_data SET ".$field3."=".$field3."+1, ".$field2."=0 WHERE user_id=".$user_id."");
						myquery("DELETE FROM dungeon_users_progress WHERE user_id=".$user_id." and quest_id = ".$id." ");
						//��������� �������
						if ($char['map_name']==691)
						{
							$give_elik = array(zelye_glubin_item_id);
							$key = 1275;
							$medal_id = 13;
						}
						if ($char['map_name']==692)
						{
							$give_elik = array(zelye_glubin_medium_item_id);
							$key = 1276;
							$medal_id = 14;
						}
						if ($char['map_name']==804)
						{
							$give_elik = array(zelye_glubin_big_item_id);
							$key = 1277;
							$medal_id = 15;
						}
						//���� �������� ��� ��������			
						$col=1;
						$priz='';
						for($j=0;$j<count($give_elik); $j++)
						{
							$i=$give_elik[$j];
							$Item = new Item();
							$ar = $Item->add_user($i,$user_id,1);
							if ($ar[0]>0)
							{
								$priz.='<br><font color=#bcb1ff>'.$Item->getFact('name').'</font><font color=#aaffa8> - </font><font color=red>'.$col.'</font> <font color=#aaffa8>��., </font>';
							}
						}
						//====================================== 
						$priz=substr($priz,0,strlen($priz)-2);
						echo '<font color=#aaffa8><b>�������, ������� ���������! �������, ��� '.echo_sex('�����','�������').' ��� � ����� �������� ����! ��, �������, ������ ������� ��� ������������� �� ����������� - � ������� � ��� ���� '.$priz.'!</b></font>';
						unset($col);

						//************************************		
						//�������� �� ������ ����������� ������!!!!!!
						//c������ ������� �� ������ ������
						include("dungeon_inc/dungeon_level_count.php");
						$dones_num=mysql_num_rows(myquery("SELECT user_id FROM dungeon_quests_done WHERE user_id=".$user_id.""));
						if($dones_num>=$quests_num[$level])
						{
							$blazevic=28591; 
							$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$blazevic.", '0', '����� ������ ����������', '������������, ��� ��������� ����� ����� :) ������ ��������� ���, ��� ����� ".$char['name']." ������ ".$level." ������� ����������.','0','".time()."')");
                            $stream_dan=2694; 
                            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$stream_dan.", '0', '����� ������ ����������', '������������, ��� ��������� ����� ����� :) ������ ��������� ���, ��� ����� ".$char['name']." ������ ".$level." ������� ����������.','0','".time()."')");
                            $send_id = 22811;
							$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$send_id.", '0', '����� ������ ����������', '������������, ��� ��������� ����� ����� :) ������ ��������� ���, ��� ����� ".$char['name']." ������ ".$level." ������� ����������.','0','".time()."')");
							echo '<br><br><font color=#aaffa8>��������� ���������� �������� �� ������ �������� � ��������� ��������: <font color=#aaffa8><b>���� ��� ���������� ������� ������, </font><font color=red>'.$char["name"].'</font>, <font color=#aaffa8> �� '.echo_sex('��������','���������').' ��� ��� �������! �� ����� �, ��� ���-�� ������ ��� �������. 
							      �� '.echo_sex('�������','��������').' ������ ���������� ��������. � ������������� ����� � ��� ���� �������� ���� �������� ���������� �������� � � ���� �������������� �������, ���� ���� ���� ����! �� ������ �������� ��� �� �������������� ��������! ������ ��� ��� �� ��� - ���� �� �������� ��������� ��� ������� ����� � ������ ����� ����� � �������� ���� ������� �����, �� �� ������ ����� ������ ��� ��� ���� ���������, ������� �� ����� ������ �������! �����!</b></font>';
							//������� ���������� ������
							myquery("UPDATE dungeon_users_data SET ".$field1."=".$field1."+1 WHERE user_id=".$user_id."");
							// ������ �����
							$Item = new Item();
							$Item->add_user($key,$user_id);
							$state = mysql_fetch_array(myquery("SELECT * FROM dungeon_users_data WHERE user_id=".$user_id." "));
							if ($level == 1) {$field11 = 'level2_success'; $field12 = 'level3_success'; }
							elseif ($level == 2) {$field11 = 'level1_success'; $field12 = 'level3_success'; }
							elseif ($level == 3) {$field11 = 'level1_success'; $field12 = 'level2_success'; }
							// ���������� ��� ������ ������
							if ($state[$field1] == 1)
							{
								myquery("INSERT INTO game_medal_users (user_id, medal_id, zachto) VALUES (".$user_id.", ".$medal_id.", CURDATE() )");
							}
							else
							{
								myquery("UPDATE game_medal_users SET zachto = concat(zachto,'<br>',CURDATE()) WHERE user_id = ".$user_id." and medal_id = ".$medal_id." ");
							}
							// ������ ���� ������
							if ($state[$field1] <= min($state[$field11], $state[$field12]))
							{
								$Item = new Item();
								$Item->add_user(544,$user_id);
								echo '<br><br><font color=#aaffa8>�� ��������, ��� ��������� ���������� ��� ��� ������ � ����� �������. ����� ��������� ��������� �� ��������� � ���������� ��������� �� ���: <b>���� � ������! ������ ����...� �� ���� ��� ���� ����� ����������!!! �� ��� ����� ���� ��������� �� ������ �������. �������� �� ������� �����! ����� ������ �������. ���� ������ ��������� �������� ������, ������ ������������ � ��������. Ÿ �������� �������� ��� ���������, ���� � ��� �� ����� ���������. ���� � � ������! ������� � ���������� ������������!</b></font>';
							}
							//������� ���������� �� ����������
							myquery("DELETE FROM dungeon_quests_done WHERE user_id=".$user_id."");
						}	
					}
					elseif ($check_res == 1)
					{
						echo '<br><font color=#aaffa8>�� '.echo_sex('����','�����').' �������.</font><br>';
					}					
					
				}
				else 
				{					
					//����� �������
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><center>
					<font color=#aaffa8>�� ������ ����� �������� �������:<br><br>';
					//������, ����� ���� ���� �������
					$ress_id=array();
					$i=0;
					echo '<form action="?choice=3" method="post">';
					for($j=1; $j<=count($quests[$level][$quest_id]["res"]); $j++)
					{
						$res_id=$quests[$level][$quest_id]["res"][$j]["id"];
						$the_res=myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id."");
						list($done_check)=mysql_fetch_array(myquery("SELECT res_num FROM dungeon_users_progress WHERE res_id =".$res_id." AND user_id=".$user_id." AND quest_id = '".$id."' "));
						if(mysql_num_rows($the_res)>0 AND $done_check>0)
						{
							$res_col=mysql_result($the_res,0,0);
							if($res_col>0)
							{
								$res_name=$res[$res_id]['name'];
								$inp_name='col'.$i;
								$hid_name='rid'.$i;
								echo '<font color=yellow><b>'.$res_name.'</b></font>, ����� 
								<INPUT type="text" size="3" maxlength="3" name="'.$inp_name.'" value="'.min($res_col, $done_check).'"> ��. (���������� �����: <font color=red>'.$done_check.'</font> ��. � ���� ����: <font color=red>'.$res_col.'</font> ��.)<br>
								<INPUT type="hidden" name="'.$hid_name.'" value="'.$res_id.'">';
								$i++;
							}
						}
					}
					if($i==0) echo '<font color=#ff4433><b>� ���� ��� ��������, ������� ����� �����</b></font><br>';
					else echo '<br><br><input type="submit" value="����� �������">';
					echo '<INPUT type="hidden" name="ress_num" value="'.$i.'"></form>';
				}
			}
			else 
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������. - <b>���, ������ � ���� ��� �������� �������.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">���������</a><br><br>';	
		}		
	}
	else
	{
		echo '<br><a href="?talk" target="game">���������� � ���������� ����������</a>';
		echo '<br><a href="?exit" target="game">����� �� ����������</a>';
		echo '<br><a href="../../act.php" target="game">���������</a><br><br>';
	}
	
	OpenTable('close');
	?>
	</td></tr></table>
	<?
	include("../inc/template_footer.inc.php");
}else 
	echo  '<meta http-equiv="refresh" content="0;url=../../act.php">';

?>