<?
// $adm['npc'] = 1 - �������������� ������������ �����
// $adm['npc'] = 2 - ���������� ����� � �������������� ������������ �����
// $adm['npc'] = 3 - �������������� ������� ����� � �����
// $adm['npc'] = 4 - ���������� ����� � �������������� ������� ����� � �����
if (function_exists("start_debug")) start_debug(); 

if ($adm['npc'] >= 1)
{
	if (isset($_GET['new']) AND $adm['npc']>=2)
	{
		//���������� ������ ���� �� �������
		if (isset($_POST['submit']))
		{
			if (!isset($_POST['view'])) $view = 0; else $view = 1;
			if (!isset($_POST['stay'])) $stay = 0; else $stay = 1;
			if (!isset($_POST['dropable'])){$dropable = 0;}else{$dropable = 1;}
			$templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=".$_POST['npc_id'].""));
			myquery("INSERT INTO game_npc SET stay='".$stay."',npc_id='".$_POST['npc_id']."',map_name='".$_POST['map_name']."',xpos='".$_POST['xpos']."',ypos='".$_POST['ypos']."',view='".$view."',dropable='".$dropable."',HP=".$templ['npc_max_hp'].",MP=".$templ['npc_max_mp'].", EXP=".$templ['npc_exp_max']."");
			setLocation("admin.php?opt=main&option=npc");
		}
		else
		{
			?>
			<script type="text/javascript">
			function createXmlHttpRequestObject()
			{
				var xmlHttp;
				try
				{
					xmlHttp = new XMLHttpRequest();
				}
				catch(e)
				{
					var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
					"MSXML2.XMLHTTP.5.0",
					"MSXML2.XMLHTTP.4.0",
					"MSXML2.XMLHTTP.3.0",
					"MSXML2.XMLHTTP",
					"Microsoft.XMLHTTP");
					for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
					{
						try
						{
							xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
						}
						catch (e) {}
					}
				}
				if (!xmlHttp)
					alert("Error creating the XMLHttpRequest object.");
				else
					return xmlHttp;
			}
			function refresh_npc_view()
			{
				if(AjaxRequest)
				{
					try
					{
						if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
						{
							URL = "http://<?=domain_name;?>/ajax/admin/npc_view.php?npc_id="+document.getElementById("id_npc_template").value;
							AjaxRequest.open("GET", URL, true);
							AjaxRequest.onreadystatechange = process_refresh;
							AjaxRequest.send(null);
						}
					}
					catch(e)
					{
					}
				}
				else
				{
					AjaxRequest = createXmlHttpRequestObject();
					setTimeout("refresh_npc_view();", 50);
				}
			}
			function process_refresh()
			{
				try
				{
					if (AjaxRequest.readyState == 4)
					{
						if (AjaxRequest.status == 200)
						{
							try
							{
								 el = document.getElementById("npc_view");
								 el.innerHTML = AjaxRequest.responseText;
							}
							catch(e)
							{
							}
						}
						else
						{
						}
					}
				}
				catch(e)
				{
				}
			}
			var AjaxRequest = createXmlHttpRequestObject();
			</script>
			<?
			echo '
			<form name="new_npc" action="admin.php?opt=main&option=npc&new" method="POST">
			<table><tr><td>
			<table cellpadding=2>';
			echo '<tr><td>������ ����:</td><td>
			<select onChange="refresh_npc_view()" onBlur="refresh_npc_view()" onKeyPress="refresh_npc_view()" name="npc_id" id="id_npc_template">';
			$sel_templ=myquery("SELECT npc_id,npc_name FROM game_npc_template ORDER BY BINARY npc_name");
			while ($tem = mysql_fetch_array($sel_templ))
			{
				echo '<option value="'.$tem['npc_id'].'">'.$tem['npc_name'].'</option>';
			}
			echo '</select></td></tr>';
			echo '<tr><td>����� (�������):</td><td>';
			$selmap = myquery("SELECT id,name FROM game_maps ORDER BY BINARY name");
			echo '<select name="map_name">';
			while ($map = mysql_fetch_array($selmap))
			{
				echo '<option value="'.$map['id'].'">'.$map['name'].'</option>';
			}
			echo '</select>';
			echo ' X-<input type="text" size="5" name="xpos" value="">, Y-<input type="text" size="5" name="ypos" value=""></td></tr>';
			echo '<tr><td colspan=2><input type="checkbox" value="1" checked name="view">���������� �� view.rpg.su</td></tr>';
			echo '<tr><td colspan=2><input type="checkbox" value="1" name="dropable">�� ���� ������ "����"</td><td></td></tr>';
			echo '<tr><td colspan=2><select name="stay">
			<option value="0">������� ���</option>
            <option value="1">����� �� ����� ����� ������</option>
            <option value="2">��������� �� ��������� ���</option>
			<option value="3">��������� ������ � �������� �� ��������� ���</option>
			<option value="4">��������� ����� ������</option>
			 </select></tr>';
			//echo '<tr><td colspan=2><input type="checkbox" value="1" name="stay">����� �� ����� ����� ������</td></tr>';
            /*echo '<tr><td colspan=2><select name="npc_flag">
            <option value="0">������� ���</option>
            <option value="1">�������� 70% �� ����� ������ �������� (���<->����)</option>
            <option value="2">��� ���� ��� "��������" ������</option>
            <option value="3">��� ������ ��� ��� "��������" ������</option>
            <option value="4">��� �������� ������</option>
            <option value="5">��� 100% �� ����� ������ �������� (���<->����), �������� ������</option>
            <option value="6">������ ����� �� 1 ������ ����� (����� 1:1 ������)</option>
            <option value="7">������ ����� �� 2 ������ ����� (����� � 1.2 ���� ������� ������)</option>
            <option value="8">������ ����� �� 3 ������ ����� (����� � 1.4 ���� ������� ������)</option>
            </select></tr>';*/
			echo '</table>';
			echo '<input type="submit" name="submit" value="���������">
			</td><td><div id="npc_view"></div></td></tr></table>';
			echo '</form><script>refresh_npc_view();</script>';
		}
		echo '<br /><br /><a href="admin.php?opt=main&option=npc">�� �������</a><br /><br />';
	}
	elseif (isset($_GET['edit']))
	{
		//�������������� ���� �� �������
		if (isset($_POST['submit']))
		{
			if (!isset($_POST['view'])){$view = 0;}else{$view = 1;}
			if (!isset($_POST['stay'])){$stay = 0;}
			if (!isset($_POST['dropable'])){$dropable = 0;}else{$dropable = 1;}
			myquery("UPDATE game_npc SET stay='".$stay."',npc_id='".$_POST['npc_id']."',map_name='".$_POST['map_name']."',xpos='".$_POST['xpos']."',ypos='".$_POST['ypos']."',view='".$view."',dropable='".$dropable."' WHERE id=".$_GET['edit']."");
			if (!isset($_GET['npc_template']))
			{
				setLocation("admin.php?opt=main&option=npc");
			}
			else
			{
				setLocation("admin.php?opt=main&option=npc_template");
			}
		}
		else
		{
			?>
			<script type="text/javascript">
			function createXmlHttpRequestObject()
			{
				var xmlHttp;
				try
				{
					xmlHttp = new XMLHttpRequest();
				}
				catch(e)
				{
					var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
					"MSXML2.XMLHTTP.5.0",
					"MSXML2.XMLHTTP.4.0",
					"MSXML2.XMLHTTP.3.0",
					"MSXML2.XMLHTTP",
					"Microsoft.XMLHTTP");
					for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
					{
						try
						{
							xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
						}
						catch (e) {}
					}
				}
				if (!xmlHttp)
					alert("Error creating the XMLHttpRequest object.");
				else
					return xmlHttp;
			}
			function refresh_npc_view()
			{
				if(AjaxRequest)
				{
					try
					{
						if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
						{
							URL = "http://<?=domain_name;?>/ajax/admin/npc_view.php?npc_id="+document.getElementById("id_npc_template").value;
							AjaxRequest.open("GET", URL, true);
							AjaxRequest.onreadystatechange = process_refresh;
							AjaxRequest.send(null);
						}
					}
					catch(e)
					{
					}
				}
				else
				{
					AjaxRequest = createXmlHttpRequestObject();
					setTimeout("refresh_npc_view();", 50);
				}
			}
			function process_refresh()
			{
				try
				{
					if (AjaxRequest.readyState == 4)
					{
						if (AjaxRequest.status == 200)
						{
							try
							{
								 el = document.getElementById("npc_view");
								 el.innerHTML = AjaxRequest.responseText;
							}
							catch(e)
							{
							}
						}
						else
						{
						}
					}
				}
				catch(e)
				{
				}
			}
			var AjaxRequest = createXmlHttpRequestObject();
			</script>
			<?
			$npc = mysql_fetch_array(myquery("SELECT game_maps.name AS mapname,game_npc.*,game_npc_template.* FROM game_npc,game_npc_template,game_maps WHERE game_npc.map_name=game_maps.id AND game_npc.npc_id=game_npc_template.npc_id AND game_npc.id=".$_GET['edit']."")); 
			echo '<form method="POST" action="admin.php?opt=main&option=npc&edit='.$_GET['edit'].'';
			if (isset($_GET['npc_template'])) echo '&npc_template';
			echo '">';
			echo '<table><tr><td><table cellpadding=2>';
			echo '<tr><td>������ ����:</td><td>
			<select onChange="refresh_npc_view()" onBlur="refresh_npc_view()" onKeyPress="refresh_npc_view()" name="npc_id" id="id_npc_template">';
			$sel_templ=myquery("SELECT npc_id,npc_name FROM game_npc_template ORDER BY BINARY npc_name");
			while ($tem = mysql_fetch_array($sel_templ))
			{
				echo '<option value="'.$tem['npc_id'].'"';
				if ($npc['npc_id']==$tem['npc_id']) echo ' selected';
				echo '>'.$tem['npc_name'].'</option>';
			}
			echo '</select></td></tr>';
			echo '<tr><td>����� (�������):</td><td>';
			$selmap = myquery("SELECT id,name FROM game_maps ORDER BY BINARY name");
			echo '<select name="map_name">';
			while ($map = mysql_fetch_array($selmap))
			{
				echo '<option value="'.$map['id'].'"';
				if ($npc['map_name']==$map['id']) echo ' selected';
				echo '>'.$map['name'].'</option>';
			}
			echo '</select>';
			echo ' X-<input type="text" size="5" name="xpos" value="'.$npc['xpos'].'">, Y-<input type="text" size="5" name="ypos" value="'.$npc['ypos'].'"></td></tr>';
			echo '<tr><td colspan=2><input type="checkbox" value="1" name="view"'; if ($npc['view']==1) echo ' checked'; echo '>���������� �� view.rpg.su</td></tr>';
			echo '<tr><td colspan=2><input type="checkbox" value="1" name="dropable"'; if ($npc['dropable']==1) echo ' checked'; echo '>�� ���� ������ "����"</td><td></td></tr>';
			echo '<tr><td colspan=2><select name="stay">
			<option value="0" ';if ($npc['stay']==0) echo ' selected'; echo '>������� ���</option>
            <option value="1" ';if ($npc['stay']==1) echo ' selected'; echo '>����� �� ����� ����� ������</option>
            <option value="2" ';if ($npc['stay']==2) echo ' selected'; echo '>��������� �� ��������� ���</option>
			<option value="3" ';if ($npc['stay']==3) echo ' selected'; echo '>��������� ������ � �������� �� ��������� ���</option>
			 </select></tr>';
			//echo '<tr><td colspan=2><input type="checkbox" value="1" name="stay"'; if ($npc['stay']==1) echo ' checked'; echo '>����� �� ����� ����� ������</td></tr>';
            /*echo '<tr><td colspan=2><select name="npc_flag">
            <option value="0" ';if ($npc['npc_flag']==0) echo ' selected'; echo '>������� ���</option>
            <option value="1" ';if ($npc['npc_flag']==1) echo ' selected'; echo '>�������� 70% �� ����� ������ �������� (���<->����)</option>
            <option value="2" ';if ($npc['npc_flag']==2) echo ' selected'; echo '>��� ���� ��� "��������" ������</option>
            <option value="3" ';if ($npc['npc_flag']==3) echo ' selected'; echo '>��� ������ ��� ��� "��������" ������</option>
            <option value="4" ';if ($npc['npc_flag']==4) echo ' selected'; echo '>��� �������� ������</option>
            <option value="5" ';if ($npc['npc_flag']==5) echo ' selected'; echo '>��� 100% �� ����� ������ �������� (���<->����), �������� ������</option>
            <option value="6" ';if ($npc['npc_flag']==6) echo ' selected'; echo '>������ ����� �� 1 ������ ����� (����� 1:1 ������)</option>
            <option value="7" ';if ($npc['npc_flag']==7) echo ' selected'; echo '>������ ����� �� 2 ������ ����� (����� � 1.2 ���� ������� ������)</option>
            <option value="8" ';if ($npc['npc_flag']==8) echo ' selected'; echo '>������ ����� �� 3 ������ ����� (����� � 1.4 ���� ������� ������)</option>
            </select></tr>';*/			
			echo '</table>';
			echo '<input type="submit" name="submit" value="���������"></td><td><div id="npc_view"></div></td></tr></table>';
			echo '</form>';
			echo '<script>refresh_npc_view();</script>';
		}
		echo '<br /><br /><a href="admin.php?opt=main&option=npc">�� �������</a><br /><br />';
	}
	else
	{
		if (isset($_GET['delete']) AND $adm['npc']>=2)
		{
			myquery("DELETE FROM game_npc WHERE id=".$_GET['delete']."");
		}
		
		$sel_all = myquery("SELECT COUNT(*) FROM game_npc ORDER BY id DESC");
		
		
		if ($adm['npc']>=2)
		{
			echo '<br /><br /><a href="admin.php?opt=main&option=npc&new">�������� ����</a><br /><br />';
		}
		
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=40;
		$allpage=ceil(mysql_result($sel_all,0,0)/$line);
		if ($page==-1) $allpage=1;
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		
		$add = "";
		if (isset($_GET['map']))
		{
			$add = " AND map_name=".$_GET['map']."";
		}

		$sel = myquery("SELECT game_maps.name AS mapname,game_npc.*,game_npc_template.* FROM game_npc,game_npc_template,game_maps WHERE game_npc.map_name=game_maps.id AND game_npc.npc_id=game_npc_template.npc_id $add ORDER BY game_npc.id DESC limit ".(($page-1)*$line).", $line");
		while ($itemc = mysql_fetch_array($sel))
		{
			echo '<a href="admin.php?opt=main&option=npc&edit='.$itemc['id'].'">�������������</a>	
			<table border=0 bgcolor=111111>
			<tr>
			<td>'.$itemc['npc_name'].'<br />
			<span style="color:white;font-weight:900;font-size:13px;">'.$itemc['mapname'].', X-'.$itemc['xpos'].', Y-'.$itemc['ypos'].'<br />';
			if ($itemc['prizrak']==1)
			{
				echo '<br /><font color=red><strong>�������!</strong></font><br />';
			}
			if ($adm['npc']>=2)
			{
				echo '<br /><br /><br /><a href="admin.php?opt=main&option=npc&delete='.$itemc['id'].'">�������</a><br />';
			}
			echo '
			</td><td><img src="http://'.img_domain.'/npc/'.$itemc['npc_img'].'.gif" border=1></td>
			</tr></table><br><br>';
		}
		$href = 'admin.php?opt=main&option=npc&';
		echo'<center>��������: ';
		show_page($page,$allpage,$href);
		
		echo '<br /><br /><br />';
		$sq = myquery("SELECT DISTINCT game_npc.map_name AS npc_map, game_maps.name FROM game_npc,game_maps WHERE game_maps.id=game_npc.map_name ORDER BY  BINARY game_maps.name");
		while ($q = mysql_fetch_array($sq))
		{
			echo '<a href="admin.php?opt=main&option=npc&page=-1&map='.$q['npc_map'].'">��� ���� �� �����: '.$q['name'].'</a><br />';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 
?>