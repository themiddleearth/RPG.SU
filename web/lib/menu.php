<?
//�������� ���������� � ��������
if (isset($_SESSION['banned']))
{
	echo '<font color="red" size="5"><center>';
	$block_date=date("H:i d.m.Y",($_SESSION['banned']+60*60*24*7));
	echo '��� �������� ����� ������������ � '. $block_date;
	echo '</center></font>';
}

echo'
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TR><TD width=80><IMG height=43 src="http://'.img_domain.'/nav1/1.gif" width=80></TD>
<TD vAlign=top width=18 background="http://'.img_domain.'/nav1/spacer.gif">
<IMG height=43 src="http://'.img_domain.'/nav1/2.gif" width=18></TD>
<TD vAlign=top width=22 background="http://'.img_domain.'/nav1/3.gif">
<IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></TD>
<TD width="100%" background="http://'.img_domain.'/nav1/3.gif" align=center vAlign=center>';



echo'
<table cellSpacing=0 cellPadding=0 width="100%" border=0><tr>
<td><a href="http://'.domain_name.'/act.php?func=main" target="game">����</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>
<td><a href="http://'.domain_name.'/act.php?func=hero" target="game">��������</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';

$stroitel=$char['stroitel'];  //��������
if ($stroitel>=1)
{
	echo '<td><a href="http://'.domain_name.'/act.php?func=main&act=build" target="game">�������������</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';
}

/*
$map = mysql_result(myquery("SELECT game_maps.name FROM game_maps,game_users_map WHERE game_maps.id = game_users_map.map_name AND game_users_map.user_id=$user_id)"),0,0);
if ($map=='������� ��������')
*/
if ($char['clevel']<5)
{
	echo '<td><a style="font-size:15px;color:#FF0000" href="http://'.domain_name.'/act.php?func=help_newbie" target="game">������ ����!</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';
}
else
{
	echo '<td><a href="http://'.domain_name.'/act.php?func=boy" target="game">���</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';
}

echo '<td><a href="http://'.domain_name.'/act.php?func=online" target="game">������</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';

echo '<td><a href="http://'.domain_name.'/act.php?func=jurnal" target="game">������</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';

echo'<td><a href="http://'.domain_name.'/act.php?func=jaloba" target="game">������ �������</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';

$result = myquery("SELECT hide,privat FROM game_admins WHERE user_id=".$user_id."");
if (mysql_num_rows($result))
{
	echo'<td><nobr><a href="http://'.domain_name.'/admin.php" target="_blank">Admin</a>';
	list($hide,$adprivat) = mysql_fetch_array($result);
	if ($hide>0)
	{
		if (isset($privat))
		{
			$privat=(int)$privat;
			$adprivat = $privat;
			$up=myquery("update game_admins set privat='$privat' where user_id=".$user_id." limit 1");
			$up=myquery("update game_users set hide='$privat' where user_id=".$user_id." limit 1");
		}
		if (!isset($func)) $func='main';
		if ($adprivat==1)
		{
			echo'&nbsp;<a href="http://'.domain_name.'/act.php?func='.$func.'&privat=0"><img src="http://'.img_domain.'/nav/ball_red.jpg" width=14 height=14 border="0" title="����� �� ����"></a>';
		}
		else
		{
			echo'&nbsp;<a href="http://'.domain_name.'/act.php?func='.$func.'&privat=1"><img src="http://'.img_domain.'/nav/ball_green.jpg" width=14 height=14 border="0" title="����� � ����"></a>';
		}
	}
	echo'</td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td></td>';
}

echo'<td><a href="http://'.domain_name.'/logout.php" target="game">�����</a></td></tr></table>
</TD>
<TD vAlign=top width=22 background="http://'.img_domain.'/nav1/3.gif">
<IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22></TD>
<TD vAlign=top width=18 background="http://'.img_domain.'/nav1/spacer.gif"><IMG height=43 src="http://'.img_domain.'/nav1/6.gif" width=18></TD>
<TD width=80><IMG height=43 src="http://'.img_domain.'/nav1/7.gif" width=80></TD>
</TR>
</TABLE>';
?>