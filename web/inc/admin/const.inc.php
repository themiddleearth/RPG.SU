<?

if (function_exists("start_debug")) start_debug(); 

if($char['clan_id']==1 or $char['name']=='mrHawk')
{
	
	if (isset($_POST['save']))
	{
		foreach ($_POST as $key => $value)
		{
			if (substr($key,0,6)=='const_')
			{
				$field_name = substr($key,6);
				myquery("UPDATE game_constants SET `value` = '".$value."' WHERE `name` = '".$field_name."'");
			}    
		}			
	}
	
	$sel = myquery("SELECT * FROM game_constants");
	echo '<h2><center>��������� �������� ��������� ��������</center></h2>
	<form action="" method="post">';
	echo '<table cellpadding=2 cellspacing=2>
	<tr><td> �������� ��������� </td><td> �������� </td></tr>';
	
	while ($t = mysql_fetch_array($sel))
	{
		echo '<tr><td> '.$t['name'].'</td><td> <input type="text" name="const_'.$t['name'].'" value="'.$t['value'].'" size="20"> </td></tr>';
	}
	
	echo '</table>
	<input type="submit" name="save" value="���������� ���������">
	</form>';
	?>
	<br /><br /><br /><br />
//��� ���������� ����� ����� - clans_war=1 � chaos_war=1<br />
//��� ���������� ����� ������ - clans_war=1 � chaos_war=0, clans_war_type=4<br />
//��� ���������� ����� ����������� - clans_war=1 � chaos_war=0, clans_war_type=6<br />
//��� ���������� ���� � "������� ������" - clans_war=1<br />
//��������!!! ����� ��������� ����� ����������� ��������� clans_war=0<br />
//����������: -1 - �����. 0 - ��� ����������. 1 - �������. 2 - �������. 3 - �����.<br/>
	<?
}

if (function_exists("save_debug")) save_debug(); 

?>