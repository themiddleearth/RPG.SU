<?

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

include('inc/template.inc.php');
require('inc/template_header.inc.php');

echo'<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">';
OpenTable('title');

echo '<CENTER> ���������� "����������" ������</CENTER><hr><br><br>';

if (!isset($_GET['napr'])) $napr = 'ASC'; else $napr = $_GET['napr'];

if (!isset($_GET['add']) and !isset($_GET['del']) and !isset($_GET['edit']))
{
	if (!isset($_GET['orderr'])) $orderr = 'npc_order'; else  $orderr = $_GET['orderr'];
	$sel = myquery("SELECT
	game_npc.*,game_npc_template.*,game_users_npc.npc_order,game_users_npc.id AS fav_id,game_maps.name AS mapname
	FROM game_users_npc,game_npc,game_npc_template,game_maps 
	WHERE game_npc.id = game_users_npc.npc_id AND
	(game_npc.time_kill+game_npc_template.respawn)<unix_timestamp() AND
	game_npc.npc_id=game_npc_template.npc_id AND
	game_maps.id=game_npc.map_name AND
	game_users_npc.user_id = $user_id AND game_npc.view=1 AND game_npc.map_name IN (700,5,18)
	ORDER BY BINARY `".$orderr."` $napr");

	echo '<table border=0>';
	echo '<tr><td colspan="15"><a href="act.php?func=npc_fav&add=0">�������� ������ ���� � "���������"</a></td></tr>';
	if ($napr == 'ASC')
    $napr = 'DESC';
	else
    $napr = 'ASC';
	echo '<tr>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_order">���.�</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_name">��� (����)</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_max_hp">�����</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_level">�������</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_str">����</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_dex">���-��</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_pie">����.</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_vit">������</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_spd">����.</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_ntl">�����.</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_exp_max">����</a></td>
		<td><a href = "act.php?func=npc_fav&napr='.$napr.'&orderr=npc_gold">������</a></td>
		<td>�����</td>
			<td>�������������</td>
	  <td>�������</td>
	   </tr>';
  while ($row = mysql_fetch_array($sel))
  {
	 echo'<tr>
		  <td>'.$row["npc_order"].'</td>
		  <td>'.$row["npc_name"].' ('.$row["npc_race"].')</td>
		  <td>'.$row["npc_max_hp"].'</td>
		  <td>'.$row["npc_level"].'</td>
		  <td>'.$row["npc_str"].'&plusmn;'.$row["npc_str_deviation"].'</td>
		  <td>'.$row["npc_dex"].'&plusmn;'.$row["npc_dex_deviation"].'</td>
		  <td>'.$row["npc_pie"].'&plusmn;'.$row["npc_pie_deviation"].'</td>
		  <td>'.$row["npc_vit"].'&plusmn;'.$row["npc_vit_deviation"].'</td>
		  <td>'.$row["npc_spd"].'&plusmn;'.$row["npc_spd_deviation"].'</td>
		  <td>'.$row["npc_ntl"].'&plusmn;'.$row["npc_ntl_deviation"].'</td>
		  <td>'.$row["EXP"].' (����.='.$row["npc_exp_max"].')</td>
		  <td>'.$row["npc_gold"].'</td>
		  <td>'.$row["mapname"].'</td>
		  <td><a href="act.php?func=npc_fav&edit='.$row["fav_id"].'">�������������</a></td>
		  <td><a href="act.php?func=npc_fav&del='.$row["fav_id"].'">�������</a></td>
		  </tr>';
  }
  echo '</table>';
}

if (isset($_GET['del']))
{
	echo '��� �� ���������� ������!';
	$up = myquery("DELETE FROM game_users_npc WHERE (user_id = '$user_id' and id = '".$_GET['del']."')");
	echo '<meta http-equiv="refresh" content="1;url=act.php?func=npc_fav">';
}

if (isset($_GET['add']))
{
	if ($_GET['add'] == '0')
	{
		//������� ���� ����� ��� ������
		if (!isset($_GET['orderr'])) $orderr = 'npc_name'; else $orderr = $_GET['orderr'];
		echo '<table><tr><td><hr color=555555 size=1 width=100%>������� ����: NPC<br></td></tr>';
		if ($napr == 'ASC')
      $napr = 'DESC';
		else
      $napr='ASC';

		echo '<tr>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_name">��� (����)</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_max_hp">�����</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_level">�������</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_str">����</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_dex">���-��</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_pie">����.</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_vit">������</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_spd">����.</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_ntl">�����.</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_exp_max">����</a></td>
		<td><a href = "act.php?func=npc_fav&add=0&napr='.$napr.'&orderr=npc_gold">������</a></td>
		<td>�����</td>
		<td></td></tr>';
		
		$npc_online = time();
		$query = "
		SELECT
		game_npc.*,game_npc_template.*,game_maps.name AS mapname
		FROM game_npc,game_npc_template,game_maps 
		WHERE game_npc.time_kill+game_npc_template.respawn<unix_timestamp() AND
		game_npc.npc_id=game_npc_template.npc_id AND
		game_maps.id=game_npc.map_name AND
		game_npc.view=1 AND game_npc.map_name IN (700,5,18) AND
		game_npc.id NOT IN (
		SELECT npc_id
		FROM game_users_npc
		WHERE user_id = $user_id
		)
		ORDER BY BINARY game_npc.map_name,game_npc_template.$orderr $napr";
		$selnpc = myquery($query);
		$cur_map = -1;
		while ($row = mysql_fetch_array($selnpc))
		{
			if ($cur_map!=$row['map_name'])
			{
				echo '<tr><td colspan=13 align="center"><br><br><b><font color=red>'.$row['mapname'].'</font></b><td></tr>';
				$cur_map=$row['map_name'];
			}
			echo'<tr>
			<td>'.$row["npc_name"].' ('.$row["npc_race"].')</td>
			<td>'.$row["npc_max_hp"].'</td>
			<td>'.$row["npc_level"].'</td>
			<td>'.$row["npc_str"].'&plusmn;'.$row["npc_str_deviation"].'</td>
			<td>'.$row["npc_dex"].'&plusmn;'.$row["npc_dex_deviation"].'</td>
			<td>'.$row["npc_pie"].'&plusmn;'.$row["npc_pie_deviation"].'</td>
			<td>'.$row["npc_vit"].'&plusmn;'.$row["npc_vit_deviation"].'</td>
			<td>'.$row["npc_spd"].'&plusmn;'.$row["npc_spd_deviation"].'</td>
			<td>'.$row["npc_ntl"].'&plusmn;'.$row["npc_ntl_deviation"].'</td>
			<td>'.$row["EXP"].' ('.$row["npc_exp_max"].')</td>
			<td>'.$row["npc_gold"].'</td>
			<td>'.$row['mapname'].'</td>
			<td><a href="act.php?func=npc_fav&add='.$row["id"].'">�������� ����</a></td>
			</tr>';
		}
		echo'</table>';
	}
	else
	{
		//���������� � ���� ���������� ����
		echo '��� � ��������� ��������!';
		$up = myquery("INSERT INTO game_users_npc (user_id,npc_id) VALUES ('$user_id','".(int)$_GET['add']."')");
		echo '<meta http-equiv="refresh" content="1;url=act.php?func=npc_fav">';
	}
}

if (isset($_GET['edit']))
{
  $edit = $_GET['edit'];
	if (!isset($_POST['save']))
	{
		$sel = myquery("SELECT
		game_npc.*,game_npc_template.*,game_users_npc.npc_order,game_users_npc.id AS fav_id,game_maps.name AS mapname
		FROM game_users_npc,game_npc,game_npc_template,game_maps 
		WHERE game_npc.id = game_users_npc.npc_id AND
		game_npc.time_kill+game_npc_template.respawn<unix_timestamp() AND
		game_npc.npc_id=game_npc_template.npc_id AND
		game_maps.id=game_npc.map_name AND
		game_users_npc.user_id = $user_id AND game_users_npc.id = '".$edit."' AND game_npc.view=1 AND game_npc.map_name IN (700,5,18)");
		
		echo '<form action="" method="post"><table border=0>';
		echo '<tr><td colspan="15"><a href="act.php?func=npc_fav&add=0">�������� ������ ���� � "���������"</a></td></tr>';
		echo '<tr>
			  <td>�������</td>
			  <td>���(����)</td>
			  <td>�����</td>
			  <td>�������</td>
			  <td>����</td>
			  <td>���-��</td>
			  <td>����.</td>
			  <td>������</td>
			  <td>����.</td>
			  <td>�����.</td>
			  <td>����</td>
			  <td>������</td>
			  <td>�����</td>
			  <td>���������</td>
		 </tr>';
		while ($row = mysql_fetch_array($sel))
		{
		   echo'<tr>
				<td><input name="npc_order1" value="'.$row["npc_order"].'" type="text" size="5"></td>
				<td>'.$row["npc_name"].' ('.$row["npc_race"].')</td>
				<td>'.$row["npc_max_hp"].'</td>
				<td>'.$row["npc_level"].'</td>
				<td>'.$row["npc_str"].'&plusmn;'.$row["npc_str_deviation"].'</td>
				<td>'.$row["npc_dex"].'&plusmn;'.$row["npc_dex_deviation"].'</td>
				<td>'.$row["npc_pie"].'&plusmn;'.$row["npc_pie_deviation"].'</td>
				<td>'.$row["npc_vit"].'&plusmn;'.$row["npc_vit_deviation"].'</td>
				<td>'.$row["npc_spd"].'&plusmn;'.$row["npc_spd_deviation"].'</td>
				<td>'.$row["npc_ntl"].'&plusmn;'.$row["npc_ntl_deviation"].'</td>
				<td>'.$row["EXP"].' ('.$row["npc_exp_max"].')</td>
				<td>'.$row["npc_gold"].'</td>
				<td>'.$row['mapname'].'</td>
				<td><input name="save" type="submit" value="���������"></td>
				</tr>
		  <input name="save" type="hidden" value="">';
		}
		echo '</table></form>';
	}
	else
	{
		echo '��� � ��������� ��������!';
		if (!isset($_POST['npc_order1'])) $npc_order = '0'; else $npc_order = (int)$_POST['npc_order1'];
		$up = myquery("UPDATE game_users_npc SET npc_order='".$npc_order."' WHERE (user_id = '$user_id' and id = '".(int)$edit."')");
		echo '<meta http-equiv="refresh" content="1;url=act.php?func=npc_fav">';
	}
}
OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';

if (function_exists("save_debug")) save_debug(); 

?>