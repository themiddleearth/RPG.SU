<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['pm'] >= 1)
{
  if (!isset($_GET['page']))
    $page = 1;
  else
    $page = (int)$_GET['page'];
  $line=25;

	if (isset($_GET['all']))
	{
		echo '<center>��������� ���������: </center><br><br>';
		
		$pm=myquery("select count(*) from game_pm where komu!='1' and otkogo>0 and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC");

		$allpage = ceil(mysql_result($pm, 0, 0) / $line);
		if ($page > $allpage) $page = $allpage;
		if ($page < 1) $page = 1;

		$otkogo = 0;
		if (isset($_GET['naf'])) $otkogo = -100;
		$pm=myquery("select * from game_pm where komu!='1' and otkogo>$otkogo and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC  limit ".(($page-1)*$line).", $line");
		echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>����</td><td>�� ����</td><td>�����</td><td>����</td><td>���������</td></tr>';
		while($p=mysql_fetch_array($pm))
		{
			$komu = get_user("name",$p['komu']);
			$otkogo = get_user("name",$p['otkogo']);
 
			$p['post']=str_replace('[quote]','<hr>',$p['post']);
			$p['post']=str_replace('[/quote]','<hr>',$p['post']);

			$p['post']=str_replace('quote]','<hr>',$p['post']);
			$p['post']=str_replace('/quote]','<hr>',$p['post']);

			$p['post']=str_replace('[quote','<hr>',$p['post']);
			$p['post']=str_replace('[/quote','<hr>',$p['post']);

			$p['post']=str_replace('quote','<hr>',$p['post']);
			$p['post']=str_replace('/quote','<hr>',$p['post']);

			$p['post']=str_replace('[b]','<b>',$p['post']);
			$p['post']=str_replace('[/b]','</b>',$p['post']);

			$p['post']=str_replace('<br />','<br>',$p['post']);

			$p['post']=str_replace('[i]','<i>',$p['post']);
			$p['post']=str_replace('[/i]','</i>',$p['post']);

			$p['post']=str_replace('[u]','<u>',$p['post']);
			$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
		}
		echo'<tr><td colspan=5>';
		$href = 'admin.php?opt=main&option=pm&all&';
		if (isset($_GET['naf']))
		{
			$href.='naf&';
		}
		echo'<center>��������: ';
		show_page($page,$allpage,$href);
		echo '</td></tr>';
		echo'</table>';
	}

	if (isset($_GET['del']))
	{
		echo '<center>��������� ��������� ���������: </center><br><br>';
		
		$pm=myquery("select count(*) from game_pm_deleted where komu!='1' and otkogo>0 and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC");

		$allpage = ceil(mysql_result($pm, 0, 0) / $line);
		if ($page > $allpage) $page = $allpage;
		if ($page < 1) $page = 1;

		$otkogo = 0;
		if (isset($_GET['naf'])) $otkogo = -100;
		$pm=myquery("select * from game_pm_deleted where komu!='1' and otkogo>$otkogo and otkogo!='1' and komu!='612' and otkogo!='612' and view!=3 and clan!=1 order by `time` DESC limit ".(($page-1)*$line).", $line");
		echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>����</td><td>�� ����</td><td>�����</td><td>����</td><td>���������</td><td></td></tr>';
		while($p=mysql_fetch_array($pm))
		{
			$komu = get_user("name",$p['komu']);
			$otkogo = get_user("name",$p['otkogo']);
			
			$p['post']=str_replace('[quote]','<hr>',$p['post']);
			$p['post']=str_replace('[/quote]','<hr>',$p['post']);

			$p['post']=str_replace('quote]','<hr>',$p['post']);
			$p['post']=str_replace('/quote]','<hr>',$p['post']);

			$p['post']=str_replace('[quote','<hr>',$p['post']);
			$p['post']=str_replace('[/quote','<hr>',$p['post']);

			$p['post']=str_replace('quote','<hr>',$p['post']);
			$p['post']=str_replace('/quote','<hr>',$p['post']);

			$p['post']=str_replace('[b]','<b>',$p['post']);
			$p['post']=str_replace('[/b]','</b>',$p['post']);

			$p['post']=str_replace('[i]','<i>',$p['post']);
			$p['post']=str_replace('[/i]','</i>',$p['post']);

			$p['post']=str_replace('[u]','<u>',$p['post']);
			$p['post']=str_replace('[/u]','</u>',$p['post']);
			echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
		}
		echo'<tr><td colspan=5>';
		$href = 'admin.php?opt=main&option=pm&del&';
		if (isset($_GET['naf']))
		{
			$href.='naf&';
		}
		echo'<center>��������: ';
		show_page($page,$allpage,$href);
		echo '</td></tr>';
		echo'</table>';
	}

	if (isset($_GET['userr']))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>����� ������:<br><br><font size="1" face="Verdana" color="#ffffff">�����: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="�����" onClick="location.href=\'admin.php?opt=main&option=pm&userr&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($_GET['user_name']))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>����</td><td>�� ����</td><td>�����</td><td>����</td><td>���������</td><td></td></tr>';
			$usrid = get_user("user_id",$_GET['user_name'],1);

			$otkogo = 0;
			if (isset($_GET['naf'])) $otkogo = -100;
			
			$usr=myquery("select count(*)  from game_pm where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc");
      $allpage = ceil(mysql_result($usr, 0, 0) / $line);
      if ($page > $allpage) $page = $allpage;
      if ($page < 1) $page = 1;

			$usr=myquery("select *  from game_pm where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr&user_name='.$_GET['user_name'].'&';
			if (isset($_GET['naf']))
			{
				$href.='naf&';
			}
			echo'<center>��������: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	if (isset($_GET['userr_del']))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>��������� ����� ������:<br><br><font size="1" face="Verdana" color="#ffffff">�����: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="�����" onClick="location.href=\'admin.php?opt=main&option=pm&userr_del&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($_GET['user_name']))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>����</td><td>�� ����</td><td>�����</td><td>����</td><td>���������</td><td></td></tr>';
			$usrid = get_user("user_id",$_GET['user_name'],1);
			
			$otkogo = 0;
			if (isset($_GET['naf'])) $otkogo = -100;
			
			$usr=myquery("select count(*)  from game_pm_deleted where komu='$usrid' and otkogo>$otkogo and komu!='1' and komu!='612' and view!=3 order by `time` desc");
      $allpage = ceil(mysql_result($pm, 0, 0) / $line);
      if ($page > $allpage) $page = $allpage;
      if ($page < 1) $page = 1;

			$usr=myquery("select *  from game_pm_deleted where komu='$usrid' and komu!='1' and otkogo>$otkogo and komu!='612' and view!=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr_del&user_name='.$_GET['user_name'].'&';
			if (isset($_GET['naf']))
			{
				$href.='naf&';
			}
			echo'<center>��������: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	if (isset($_GET['userr_sent']))
	{
		echo'<div id="content" onclick="hideSuggestions();"><center>������������ ����� ������:<br><br><font size="1" face="Verdana" color="#ffffff">�����: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="" type="button" value="�����" onClick="location.href=\'admin.php?opt=main&option=pm&userr_sent&user_name=\'+document.getElementById(\'keyword\').value"></div><script>init();</script>';

		if(isset($_GET['user_name']))
		{
			echo'<table border=1 width=100% cellpadding="2" cellspacing="1"><tr><td>����</td><td>�� ����</td><td>�����</td><td>����</td><td>���������</td><td></td></tr>';
			$otkogo = 0;
			if (isset($_GET['naf'])) $otkogo = -100;
			
			$usrid = get_user("user_id",$_GET['user_name'],1);
			$usr=myquery("select count(*)  from game_pm where otkogo='$usrid' and komu!='1' and otkogo>$otkogo  and komu!='612' and view=3 order by `time` desc");
      $allpage = ceil(mysql_result($pm, 0, 0) / $line);
      if ($page > $allpage) $page = $allpage;
      if ($page < 1) $page = 1;

			$usr=myquery("select *  from game_pm where otkogo='$usrid' and komu!='1'  and otkogo>$otkogo and komu!='612' and view=3 order by `time` desc limit ".(($page-1)*$line).", $line");

			while($p=mysql_fetch_array($usr))
			{
				$komu = get_user("name",$p['komu']);
				$otkogo = get_user("name",$p['otkogo']);
				
				$p['post']=str_replace('[quote]','<hr>',$p['post']);
				$p['post']=str_replace('[/quote]','<hr>',$p['post']);

				$p['post']=str_replace('quote]','<hr>',$p['post']);
				$p['post']=str_replace('/quote]','<hr>',$p['post']);

				$p['post']=str_replace('[quote','<hr>',$p['post']);
				$p['post']=str_replace('[/quote','<hr>',$p['post']);

				$p['post']=str_replace('quote','<hr>',$p['post']);
				$p['post']=str_replace('/quote','<hr>',$p['post']);

				$p['post']=str_replace('[b]','<b>',$p['post']);
				$p['post']=str_replace('[/b]','</b>',$p['post']);

				$p['post']=str_replace('[i]','<i>',$p['post']);
				$p['post']=str_replace('[/i]','</i>',$p['post']);

				$p['post']=str_replace('[u]','<u>',$p['post']);
				$p['post']=str_replace('[/u]','</u>',$p['post']);
				echo'<tr><td>'.$komu.'</td><td>'.$otkogo.'</td><td>'.date("d.m.Y H:i",$p['time']).'</td><td>'.$p['theme'].'</td><td>'.$p['post'].'</td></tr>';
			}
			echo'<tr><td colspan=5>';
			$href = 'admin.php?opt=main&option=pm&userr_sent&user_name='.$_GET['user_name'].'&';
			if (isset($_GET['naf']))
			{
				$href.='naf&';
			}
			echo'<center>��������: ';
			show_page($page,$allpage,$href);
			echo '</td></tr>';
			echo'</table>';
		}
	}

	echo'<br><hr><table><tr><td>[<a href="?opt=main&option=pm&all">��������� 150 ���������</a>]</td><td>[<a href="?opt=main&option=pm&del">��������� 150  ��������� ���������</a>]</td><td>[<a href="?opt=main&option=pm&userr">����� ������</a>]</td><td>[<a href="?opt=main&option=pm&userr_del">��������� ����� ������</a>]</td><td>[<a href="?opt=main&option=pm&userr_sent">������������ ����� ������</a>]</td></tr>
	<tr><td>[<a href="?opt=main&option=pm&all&naf">��������� 150 ��������� c� ����������</a>]</td><td>[<a href="?opt=main&option=pm&del&naf">��������� 150  ��������� ��������� �� ����������</a>]</td><td>[<a href="?opt=main&option=pm&userr&naf">����� ������ �� ����������</a>]</td><td>[<a href="?opt=main&option=pm&userr_del&naf">��������� ����� ������ �� ����������</a>]</td><td></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 
?>