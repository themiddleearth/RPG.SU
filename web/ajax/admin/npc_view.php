<?
include("ajax_header.inc.php");

if(ob_get_length()) ob_clean();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/html;charset=windows-1251');

$sel = myquery("SELECT * FROM game_npc_template WHERE npc_id='".$_GET['npc_id']."'");
while ($itemc = mysql_fetch_array($sel))
{
	echo'<table><tr><td><table border=0 bgcolor=111111>';
	echo'
	<tr><td>��� (����): </td><td>'.$itemc['npc_name'].' ('.$itemc['npc_race'].')</td></tr>
	<tr><td>�����/����: </td><td>'.$itemc['npc_max_hp'].'/'.$itemc['npc_max_mp'].'</td></tr>
	<tr><td>�������: </td><td>'.$itemc['npc_level'].'</td></tr>
	<tr><td>����� �����������: </td><td>'.$itemc['respawn'].' ������</td></tr>
	<tr><td>����: </td><td>'.$itemc['npc_str'].'&plusmn;'.$itemc['npc_str_deviation'].'</td></tr>
	<tr><td>������������: </td><td>'.$itemc['npc_dex'].'&plusmn;'.$itemc['npc_dex_deviation'].'</td></tr>
	<tr><td>��������: </td><td>'.$itemc['npc_pie'].'&plusmn;'.$itemc['npc_pie_deviation'].'</td></tr>
	<tr><td>������: </td><td>'.$itemc['npc_vit'].'&plusmn;'.$itemc['npc_vit_deviation'].'</td></tr>
	<tr><td>��������: </td><td>'.$itemc['npc_spd'].'&plusmn;'.$itemc['npc_spd_deviation'].'</td></tr>
	<tr><td>���������: </td><td>'.$itemc['npc_ntl'].'&plusmn;'.$itemc['npc_ntl_deviation'].'</td></tr>

	<tr><td>����: </td><td style="color:white;font-size:12px;font-weight:700;">'.$itemc['npc_exp_max'].'</td></tr>
	<tr><td>������: </td><td style="color:white;font-size:12px;font-weight:700;">'.$itemc['npc_gold'].'</td></tr>';
	
	if ($itemc['canmove'] == 0) echo'<tr><td colspan=2><b><font color=ff0000>�� ������������� �� ������</font></b></td></tr>';
	echo'
	<tr><td>������� �����: </td><td>'.$itemc['item'].'</td></tr>
	<tr><td colspan=2>';

	if ($itemc['agressive']=='-1') echo '<font color=#80FFFF> ��� ������. �������� ��������. �� ���� ������� ������.</font>';
	if ($itemc['agressive']=='0') echo '<font color=#80FFFF> ��� �� ����������</font>';
	if ($itemc['agressive']=='1') echo '<font color=#00FF00> ��� ������� �� �������, � ������� ������� �� '.$itemc['agressive_level'].' > ������ ����</font>';
	if ($itemc['agressive']=='2') echo '<font color=#FF0000><b> ��� �������� �� ���� �������!<b></font>

	</td></tr>';
	
	echo'</table></td>
	<td><img src="http://'.img_domain.'/npc/'.$itemc['npc_img'].'.gif" border=1><br />http://'.img_domain.'/npc/'.$itemc['npc_img'].'.gif';
	echo '</td>

	</tr></table><br><br>';
}
?>