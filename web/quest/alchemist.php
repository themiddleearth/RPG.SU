<?php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '5');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');

require_once('../inc/craft/craft.inc.php');

$gp = 5;

if (function_exists("start_debug")) start_debug(); 

?>
<html>
<head>
<title>������������ �����������</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<body>
<?
$user_func = getFunc($user_id);
$from_house = false;
if (isset($_GET['house']) AND (get_delay_reason_id($user_id)==32))
{
	$from_house = true;
	$gp = 0;
}
$id_alchemist="15,16";

$questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." AND id IN ($id_alchemist)");
if (mysql_num_rows($questsel) OR $from_house)
{
	if ($from_house==false)
	{
		echo '<span style="z-index:-100;position:absolute;left:0%;top:0%;"><img src="http://'.img_domain.'/quest/lab_left.jpg"></span>';
		echo '<span style="z-index:-100;position:absolute;right:0%;top:0%;"><img src="http://'.img_domain.'/quest/lab_right.jpg"></span>';
	}
	echo '<br><center><font size=1 face=verdana>';
	//echo '��������, ����������� �������� ������� �� ����������������� ������ ����� ������� ���������� �� ���������� ������ �����!';
	//echo '<br><br><br><a href="../act.php" target="game">����� �� �����������</a><br>';
	//exit;
	if ($user_func==2)
	{
		$local_func_id=craft_getFunc($user_id);
	}
	else
	{  
		$local_func_id = 0;
	}
	if (isset($_GET['mes'])) echo ('<center><b><font color="white">'.$_GET['mes'].'</font></b><br/></center>');
	if ($local_func_id!=2)
	{
		if (!isset($_GET['make']))
		{
			echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';		
			//echo '<img src="http://'.img_domain.'/quest/alchemist_lab.gif" align="left" border=0>';
			if (!$from_house) echo '<br><a href="../act.php" target="game">����� �� �����������</a><br><br/><b>��� ������ � ������������ ����������� ���������� ��������� '.$gp.' �����!</b><br />';
			echo '�� ������ � ����������� ����, �� ������� ����� ����������� ��������� �����.<br>������� �������, �� ��������� ����� �� ������ ��������,<br> � ������� �������� ������ ��������� ����� � �� ���� ��� �������. ��� ���:<br>';
			$eliksir = CreateArrayForCraftEliksir();
			echo '<table style="border-style:double;border-width:2px;border-color:gold;" cellspacing=2 cellpadding=2 border=1>
			<tr style="color:white;font-weight:700;text-align:center;vertical-align:middle;"><td>��������</td><td>�����</td><td>����������</td><td>�������</td></tr>';
			
			$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$char['user_id']." AND type=1"),0,0);
			$res = array();
			$sel_res = myquery("SELECT craft_resource.id,craft_resource.name,craft_resource_user.col FROM craft_resource LEFT JOIN (craft_resource_user) ON (craft_resource.id=craft_resource_user.res_id AND craft_resource_user.user_id=$user_id)");
			while ($r = mysql_fetch_array($sel_res))
			{
				$res[$r['id']]['name'] = $r['name'];
				if ($r['col']==NULL)
				{
					$res[$r['id']]['col'] = 0;
				}
				else
				{
					$res[$r['id']]['col'] = $r['col'];
				}
			}
			$kolba = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=0 AND ref_id=0 AND item_id=".kolba_item_id."");
			
			for ($i=0;$i<sizeof($eliksir);$i++)
			{
				echo '<tr><td>';
				$craft = 1;
				if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist']) $craft = 0;
				if ($char['clevel']<$eliksir[$i]['clevel']) $craft = 0;
				if (($char['CC']-$char['CW'])<$eliksir[$i]['weight'])
				{
					if ($prov==0) $craft = 0;
				}
				if (mysql_num_rows($kolba)==0) $craft = 0;
				for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
				{
					if ($res[$eliksir[$i]['resource'][$j]['id']]['col']<$eliksir[$i]['resource'][$j]['kol']) 
					{
						$craft = 0;
						$res[$j]['f'] = 0;
					}
					else
					{
						$res[$j]['f'] = 1;
					}
				}
				
				if ($craft==1)
				{
					if ($from_house)
					{
						echo '<br><a href="?make='.$i.'&house">'.$eliksir[$i]['name'].'</a>';
					}
					elseif ($char['GP']>=$gp)
					{
						echo '<br><a href="?make='.$i.'">'.$eliksir[$i]['name'].'</a>';
					}
				}
				else
				{
					echo $eliksir[$i]['name'];
				}
				echo '</td><td>'.$eliksir[$i]['time'].'
				</td>
				<td>';
				$str_level = ''.$eliksir[$i]['clevel'].' ������� ������';
				if ($char['clevel']<$eliksir[$i]['clevel'])
				{
					$str_level = '<span style="color:red;font-weight:900;">'.$str_level.'</span>';
				}
				$str_level_aclhemist = ''.$eliksir[$i]['alchemist'].' ������� ��������';
				if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist'])
				{
					$str_level_aclhemist = '<span style="color:red;font-weight:900;">'.$str_level_aclhemist.'</span>';
				}
				$str_kolba = '����� - 1 �� (����� ������ � ���������)';
				if (mysql_num_rows($kolba)<1)
				{
					$str_kolba = '<span style="color:red;font-weight:900;">'.$str_kolba.'</span>';
				}
				echo $str_level.'<br>'.$str_level_aclhemist.'<br>'.$str_kolba.'<br>';
				for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
				{
					$str_res = $res[$eliksir[$i]['resource'][$j]['id']]['name'].' - '.$eliksir[$i]['resource'][$j]['kol'].' ��.';
					if ($res[$j]['f']==0) 
					{
						$str_res = '<span style="color:red;font-weight:900;">'.$str_res.'</span>';
					}
					echo $str_res.'<br />';
				}
				echo '</td>
				<td style="text-align:center;">';
				?><a onmousemove=movehint(event,1) onmouseover="showhint('<font color=ff0000><b><?php
					echo '<center><font color=#0000FF>'.$eliksir[$i]['name'].'</font>';
					?></b></font>','<?php
					echo '<font color=000000>';
					echo '���: '.$eliksir[$i]['weight'].'<br>';
					if ($eliksir[$i]['hp']>0) echo '����������� �������� �� '.$eliksir[$i]['hp'].' '.pluralForm($eliksir[$i]['hp'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['hp']<0) echo '��������� �������� �� '.$eliksir[$i]['hp'].' '.pluralForm($eliksir[$i]['hp'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['mp']>0) echo '����������� ���� �� '.$eliksir[$i]['mp'].' '.pluralForm($eliksir[$i]['mp'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['mp']<0) echo '��������� ���� �� '.$eliksir[$i]['mp'].' '.pluralForm($eliksir[$i]['mp'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['stm']>0) echo '����������� ������� �� '.$eliksir[$i]['stm'].' '.pluralForm($eliksir[$i]['stm'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['stm']<0) echo '��������� ������� �� '.$eliksir[$i]['stm'].' '.pluralForm($eliksir[$i]['stm'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['str']>0) echo '����������� ���� �� '.$eliksir[$i]['str'].' '.pluralForm($eliksir[$i]['str'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['str']<0) echo '��������� ���� �� '.$eliksir[$i]['str'].' '.pluralForm($eliksir[$i]['str'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['pie']>0) echo '����������� �������� �� '.$eliksir[$i]['pie'].' '.pluralForm($eliksir[$i]['pie'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['pie']<0) echo '��������� �������� �� '.$eliksir[$i]['pie'].' '.pluralForm($eliksir[$i]['pie'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['dex']>0) echo '����������� ������������ �� '.$eliksir[$i]['dex'].' '.pluralForm($eliksir[$i]['dex'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['dex']<0) echo '��������� ������������ �� '.$eliksir[$i]['dex'].' '.pluralForm($eliksir[$i]['dex'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['vit']>0) echo '����������� ������ �� '.$eliksir[$i]['vit'].' '.pluralForm($eliksir[$i]['vit'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['vit']<0) echo '��������� ������ �� '.$eliksir[$i]['vit'].' '.pluralForm($eliksir[$i]['vit'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['ntl']>0) echo '����������� ��������� �� '.$eliksir[$i]['ntl'].' '.pluralForm($eliksir[$i]['ntl'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['ntl']<0) echo '��������� ��������� �� '.$eliksir[$i]['ntl'].' '.pluralForm($eliksir[$i]['ntl'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['spd']>0) echo '����������� �������� �� '.$eliksir[$i]['spd'].' '.pluralForm($eliksir[$i]['spd'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['spd']<0) echo '��������� �������� �� '.$eliksir[$i]['spd'].' '.pluralForm($eliksir[$i]['spd'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['lucky']>0) echo '����������� ����� �� '.$eliksir[$i]['lucky'].' '.pluralForm($eliksir[$i]['lucky'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['lucky']<0) echo '��������� ����� �� '.$eliksir[$i]['lucky'].' '.pluralForm($eliksir[$i]['lucky'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['cc']>0) echo '����������� ��� �� '.$eliksir[$i]['cc'].' '.pluralForm($eliksir[$i]['cc'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['cc']<0) echo '��������� ��� �� '.$eliksir[$i]['cc'].' '.pluralForm($eliksir[$i]['cc'],'�������','�������','������').'<br>';
					if ($eliksir[$i]['dlit']>0) echo '����� �������� ��������: '.ceil($eliksir[$i]['dlit']/60).' '.pluralForm(ceil($eliksir[$i]['dlit']/60),'������','������','�����').'<br>';    
					echo '</font>';
					?>',0,1,event,1)" onmouseout="showhint('','',0,0,event,1)"><?php echo '<img border=0 src="http://'.img_domain.'/item/'.$eliksir[$i]['img'].'.gif"></a></td><td>';
				echo '</tr>';
			}
			
			echo '</table><br><br>';
			echo '� ���� ������ '.getCraftLevel($user_id,2).' ������� ������ ������������� ��������� ����� (������� ��������)';
		}
		else
		{
			if (isset($_POST['digit']) OR isset($_POST['begin']))
			{
				//�������� ����� �����
				$i = (int)$_GET['make'];
				$craft = 1;
				
				$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$char['user_id']." AND type=1"),0,0);
				$res = array();
				$sel_res = myquery("SELECT craft_resource.id,craft_resource.name,craft_resource_user.col FROM craft_resource LEFT JOIN (craft_resource_user) ON (craft_resource.id=craft_resource_user.res_id AND craft_resource_user.user_id=$user_id)");
				while ($r = mysql_fetch_array($sel_res))
				{
					$res[$r['id']]['name'] = $r['name'];
					if ($r['col']==NULL)
					{
						$res[$r['id']]['col'] = 0;
					}
					else
					{
						$res[$r['id']]['col'] = $r['col'];
					}
				}
				$kolba = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=0 AND ref_id=0 AND item_id=".kolba_item_id."");
				
				$eliksir = CreateArrayForCraftEliksir(); 
				if ($i>=0 AND $i<sizeof($eliksir))
				{
					if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
					{
						$dlit=5;
					}
					else
					{
						$dlit = $eliksir[$i]['time'];
					}
					if (getCraftLevel($user_id,2)<$eliksir[$i]['alchemist']) {$craft = 0;}
					elseif ($char['clevel']<$eliksir[$i]['clevel']) {$craft = 0;}
					else
					{
						for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
						{
							if ($res[$eliksir[$i]['resource'][$j]['id']]['col']<$eliksir[$i]['resource'][$j]['kol']) $craft = 0;
						}
					}
					if ($craft==1)
					{
						if (mysql_num_rows($kolba)==0) $craft = 0;
					}
					if (($char['CC']-$char['CW'])<$eliksir[$i]['weight'])
					{
						if ($prov==0) $craft = 0;
					}
				}
				else
				{
					$craft = 0;
				}
				if (!$from_house AND $char['GP']<$gp)
				{
					$craft = 0;
				}
				
				if ($craft==1 and isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'])
				{
					unset($_SESSION['captcha']);
					craft_setFunc($user_id,2);
					set_delay_reason_id($user_id,4);
					myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
					myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit,eliksir) VALUES ($user_id,'alchemist',".time().",$dlit,$i)");
					$change_weight = 0;
					//������ �����
					list($kolba_weight)=mysql_fetch_array(myquery("SELECT weight FROM game_items_factsheet WHERE id=".kolba_item_id." LIMIT 1"));
					$change_weight=$change_weight+$kolba_weight;
					$kolba_check=myquery("Select count_item FROM game_items WHERE user_id=$user_id AND used=0 AND item_id=".kolba_item_id." AND priznak=0");
					$kolba_check=mysql_fetch_array($kolba_check);
					if ($kolba_check['count_item']>1)
					{
						myquery("Update game_items Set count_item=count_item-1 WHERE user_id=$user_id AND used=0 AND ref_id=0 AND item_id=".kolba_item_id." AND priznak=0");
					}
					elseif ($kolba_check['count_item']==1)
					{
						myquery("DELETE FROM game_items WHERE user_id=$user_id AND used=0 AND ref_id=0 AND item_id=".kolba_item_id." AND priznak=0 LIMIT 1");
					}
					//������� �������
					$eliksir = CreateArrayForCraftEliksir();
					for ($j=0;$j<sizeof($eliksir[$i]['resource']);$j++)
					{
						$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$eliksir[$i]['resource'][$j]['id'].""));
						list($kol) = mysql_fetch_array(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id'].""));
						$change_weight=$change_weight+($eliksir[$i]['resource'][$j]['kol']*$ress['weight']);
						if ($kol>$eliksir[$i]['resource'][$j]['kol'])
						{
							myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$eliksir[$i]['resource'][$j]['kol'].") WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
						}
						else
						{
							myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$eliksir[$i]['resource'][$j]['id']."");
						}
					}
					if (!$from_house)
					{
						myquery("UPDATE game_users SET CW=CW-$change_weight-".($gp*money_weight).",GP=GP-$gp WHERE user_id=$user_id");
						setGP($user_id,-$gp,66);
					}
					else
					{
						myquery("UPDATE game_users SET CW=CW-$change_weight WHERE user_id=$user_id");
					}
							
					ForceFunc($user_id,2);			
					if (!$from_house)
					{
						setLocation("../craft.php");
					}
					else
					{
						echo '<script>top.window.frames.game.location.replace("../craft.php?house");</script>';
					}
				}
				else
				{
					echo '�� �� ������ ������� ��� �����!<br><br><br><br>';
					if (!$from_house) echo '<a href="../act.php" target="game">����� �� �����������</a>'; 
				}
			}
			else
			{
				echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ����� �����"<br>';
				echo '<br><img src="../captcha_new/index.php?'.time().'">';
				echo '<form autocomplete="off" action="" method="POST" name="captcha"><br>
				<input type="text" size=6 maxsize=6 name="digit"><br>
				<input type="submit" name="subm" value="������ ����� �����">
				</form>';
			}
		}
	}
	if (!$from_house)
	{
		echo '<div style="position:absolute;right:0%;top:0%;">����������� ��� ������� ������ <a href="http://'.domain_name.'/view/?name=SVukraine" target="_blank">SVukraine</a><br>';
		echo '������� ������� ������� <a href="http://'.domain_name.'/view/?name=Tagava" target="_blank">Tagava</a></div>';
	}
}
																   

if ($_SERVER['REMOTE_ADDR']==debug_ip)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>