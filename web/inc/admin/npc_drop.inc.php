<?php

if (function_exists("start_debug")) start_debug(); 

if ($adm['items'] >= 2)
{
	if (isset($_GET['del']))
	{
		myquery("DELETE FROM game_npc_drop WHERE id=".$_GET['del']."");
	}
	if (!isset($_GET['npc']) AND isset($_POST['npc_id']))
	{
		$_GET['npc']=$_POST['npc_id'];
	}
	if (isset($_POST['save']))
	{
		if($_POST['drop_type']==1)
			$it = myquery("SELECT id FROM game_items_factsheet WHERE name='".$_POST['keyword']."'");
		elseif ($_POST['drop_type']==2)
			$it = myquery("SELECT id FROM craft_resource WHERE name='".$_POST['keyword']."'");
		if ($it==false)
		{
			echo '<span style="color:red;">������� �� ������!</span>';
		}
		else
		{
			list($items_id) = mysql_fetch_array($it);
			if (isset($_POST['edit_id']))
			{
				myquery("UPDATE game_npc_drop SET drop_type='".$_POST['drop_type']."', items_id='".$items_id."', variant='".$_POST['variant']."', random='".$_POST['chance']."', random_max='".$_POST['chance_max']."', kuda='".$_POST['kuda']."' WHERE id=".$_POST['edit_id']."");
			}
			else
			{
				myquery("INSERT INTO game_npc_drop (npc_id,drop_type,items_id,variant,random,random_max,kuda) VALUES (".$_POST['npc_id'].",".$_POST['drop_type'].",$items_id,".$_POST['variant'].",".$_POST['chance'].",".$_POST['chance_max'].",".$_POST['kuda'].")");
			}
		}
	}
	
	$npc = mysql_fetch_array(myquery("SELECT * FROM game_npc WHERE npc_id=".$_GET['npc'].""));
	echo'<table border=0><tr><td><table border=0 bgcolor=111111>';
	echo'
	<tr><td>��� (����): </td><td>'.$npc['npc_name'].' ('.$npc['npc_race'].')</td></tr>
	<tr><td>�����/����: </td><td>'.$npc['npc_max_hp'].'/'.$npc['npc_max_mp'].'</td></tr>
	<tr><td>�������: </td><td>'.$npc['npc_level'].'</td></tr>
	<tr><td>����� �����������: </td><td>'.$npc['respawn'].' ������</td></tr>
	<tr><td>����: </td><td>'.$npc['npc_str'].'&plusmn;'.$npc['npc_str_deviation'].'</td></tr>
	<tr><td>������������: </td><td>'.$npc['npc_dex'].'&plusmn;'.$npc['npc_dex_deviation'].'</td></tr>
	<tr><td>��������: </td><td>'.$npc['npc_wis'].'&plusmn;'.$npc['npc_wis_deviation'].'</td></tr>
	<tr><td>������: </td><td>'.$npc['npc_basefit'].'&plusmn;'.$npc['npc_basefit_deviation'].'</td></tr>
	<tr><td>��������: </td><td>'.$npc['npc_basedef'].'&plusmn;'.$npc['npc_basedef_deviation'].'</td></tr>
	<tr><td>���������: </td><td>'.$npc['npc_ntl'].'&plusmn;'.$npc['npc_ntl_deviation'].'</td></tr>

	<tr><td>����: </td><td style="color:white;font-size:12px;font-weight:700;">'.$npc['npc_exp'].'</td></tr>
	<tr><td>������: </td><td style="color:white;font-size:12px;font-weight:700;">'.$npc['npc_gold'].'</td></tr>

	<tr><td>������������: </td><td>'.@mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$npc['npc_map_name'].""),0,0).': '.$npc['npc_xpos'].', '.$npc['npc_ypos'].'</td></tr>
	<tr><td>';
	if ($npc['stay'] =='1') echo'<b><font color=ff0000>����� ������ ����� �� �����</font></b>';
	echo'</td></tr><tr><td>';
	if ($npc['canmove'] =='0') echo'<b><font color=ff0000>�� ������������� �� ������</font></b>';
	echo'</td></tr><tr><td>������� �����: </td><td>'.$npc['item'].'</td></tr><tr><td colspan=2>';

	if ($npc['agressive']=='-1') echo '<font color=#80FFFF> ��� ������. ��� �������. ������� ������.</font>';
	if ($npc['agressive']=='0') echo '<font color=#80FFFF> ��� �� ����������</font>';
	if ($npc['agressive']=='1') echo '<font color=#00FF00> ��� ������� �� �������, � ������� ������� �� '.$npc['level_user'].' > ������ ����</font>';
	if ($npc['agressive']=='2') echo '<font color=#FF0000><b> ��� �������� �� ���� �������!<b></font>';

	echo '</td></tr><tr><td>���������� ������� ����: </td><td><font color=#FF0000 szie=3><b><u>'.$npc['npc_kill'].'</u></b></font></td></tr></table></td>
		<td><img src="http://'.img_domain.'/npc/'.$npc['npc_img'].'.gif" border=1></td>

		</tr></table><br><br>
		
	��� ������� ������� ��������� ���������� ��������: 
	<table bgcolor="#E0E0E0" border=1 cellspacing=2 cellpadding=2>';
	$sel = myquery("SELECT * FROM game_npc_drop WHERE npc_id=".$_GET['npc']."");
	while ($drop=mysql_fetch_array($sel))
	{
		if($drop['drop_type']==1)
			echo '<tr style="color:#580058;font-weight:800"><td>'.@mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$drop['items_id'].""),0,0).' </td><td>';
		elseif($drop['drop_type']==2)
			echo '<tr style="color:#580058;font-weight:800"><td>'.@mysql_result(myquery("SELECT name FROM craft_resource WHERE id=".$drop['items_id'].""),0,0).' </td><td>';
		if($drop['drop_type']==1) echo '�������';
		elseif($drop['drop_type']==2) echo '������';
		echo '</td><td>';
		if ($drop['variant']==0) echo '�������� ���������';
		if ($drop['variant']==1) echo '�������� ���� ���';
		echo '</td><td>';
		if ($drop['kuda']==0) echo '�������� �� �����';
		if ($drop['kuda']==1) echo '�������� ����� � ���������';
		//echo '</td><td>���� ��������� - '.$drop['random'].' �� '.$drop['random_max'].' (���� ������� ����� ������ ������ �������� � '.$drop['random_all'].' ������� �� 100)</td>
		<td><input type="button" value="�������������" onclick="location.href=\'admin.php?opt=main&option=npc_items&npc='.$_GET['npc'].'&edit='.$drop['id'].'\'">     <input type="button" value="�������" onclick="location.href=\'admin.php?opt=main&option=npc_items&npc='.$_GET['npc'].'&del='.$drop['id'].'\'"></tr>';
	}
	echo "</table><br><br>";
		
	?>
	<script type="text/javascript">
	/* URL to the PHP page called for receiving suggestions for a keyword*/
	
	var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
	var startSearch = 1;
	
	function switch_type(type)
	{
		if(type.selectedIndex==0)
		{
			getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		}
		if(type.selectedIndex==1)
		{
			getFunctionsUrl = "suggest/suggest_resource.php?keyword=";
		}
	}
	</script>
	<?
	echo'<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
	echo'<script type="text/javascript" src="suggest/suggest.js"></script>';
	echo '<div id="content" onclick="hideSuggestions();"><b>';
	if (isset($_GET['edit']))
	{
		echo '�������������� ��������, ����������� �� �������:';
		$npc_items = mysql_fetch_array(myquery("SELECT * FROM game_npc_drop WHERE id=".$_GET['edit'].""));
	}
	else
	{
		echo '���������� ��������, ����������� �� �������:';
	}
	?>
	</b><br><br>
	<form action="" method="POST" name="add_items">
	<table border=1 cellspacing=1 cellpadding=0>
	<tr>
	<td>�������� ��������: </td><td><input id="keyword" name="keyword" type="text" size="50" onkeyup="handleKeyUp(event)"<?
	if (isset($_GET['edit']))
	{
		if($npc_items['drop_type']==1)
			echo ' value="'.@mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$npc_items['items_id'].""),0,0).'"';
		elseif($npc_items['drop_type']==2)
			echo ' value="'.@mysql_result(myquery("SELECT name FROM craft_resource WHERE id=".$npc_items['items_id'].""),0,0).'"';
	}
	?>><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>
	<tr><td>������� ���������: </td><td>
	<select name="variant">
	<?
	if (isset($_GET['edit']))
	{
		echo '
		<option value="0" ';if ($npc_items['variant']==0) echo 'selected'; echo'>�������� ���������</option>
		<option value="1" ';if ($npc_items['variant']==1) echo 'selected'; echo'>�������� ���� ���</option>
		';
	}
	else
	{
		?>
		<option value="0" selected>�������� ���������</option>
		<option value="1">�������� ���� ���</option>
		<?
	}
	?>
	</select>
	</td></tr>
	<tr><td>
	<tr><td>��� �������� ����: </td><td>
	<select name="drop_type" onChange="switch_type(this)">
	<?
	if (isset($_GET['edit']))
	{
		echo '
		<option value="1" ';if ($npc_items['drop_type']==1) echo 'selected'; echo'>�������</option>
		<option value="2" ';if ($npc_items['drop_type']==2) echo 'selected'; echo'>������</option>
		';
	}
	else
	{
		?>
		<option value="1" selected>�������</option>
		<option value="2">������</option>
		<?
	}
	?>
	</select>
	</td></tr>
	<tr><td>
	���� ���������:</td><td>
	<input name="chance" size="10" type="text"
	<?
	if (isset($_GET['edit']))
	{
		echo ' value="'.$npc_items['random'].'"';
	}
	?>> �� <input type="text" name="chance_max" size="10"
	<?
	if (isset($_GET['edit']))
	{
		echo ' value="'.$npc_items['random_max'].'"';
	}
	?>>
	</td></tr>
	<tr><td> ���� �������� �������: </td><td>
	<select name="kuda">
	<?
	if (isset($_GET['edit']))
	{
		echo '
		<option value="0" ';if ($npc_items['kuda']==0) echo 'selected'; echo'>������ �� �����</option>
		<option value="1" ';if ($npc_items['kuda']==1) echo 'selected'; echo'>������������ � ���������</option>
		';
	}
	else
	{
		?>
		<option value="0" selected>������ �� �����</option>
		<option value="1">������������ � ���������</option>
		<?
	}
	?>
	</select>
	</td></tr>
	<tr><td colspan="2"><!--���� �� ������� �������� ����� 1 ��������, �� ��� ������� �������� ����� ������ ���� ��������� ����� ����� ������ ���������� ���������<br><input type-"text" maxsize="3" size"5" name="random_all"
	<?
/*
	if (isset($_GET['edit']))
	{
		echo ' value="'.$npc_items['random_all'].'"';
	}
	else
	{
		echo ' value="100"';
	}
*/
	?>> - % ��������� ����� �������� ����� ����� ������</td></tr>
	</table>--></div><script>init();</script>
	<?
	if (isset($_GET['edit']))
	{
		echo '<input type="hidden" name="edit_id" value="'.$_GET['edit'].'">';
	}
	?>
	<input type="hidden" name="npc_id" value="<?=$_GET['npc'];?>"><input type="submit" name="save" value="��������">
	<?
}

if (function_exists("save_debug")) save_debug(); 

?>