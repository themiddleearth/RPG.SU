<script type="text/javascript" language="JavaScript">
function count()
{
  top.form.need.value=top.form.ostr.value+top.form.ontl.value+top.form.opie.value+top.form.ovit.value+top.form.odex.value+top.form.ospd.value
}
</script>
<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['items'] >= 1)
{
	if(isset($_GET['info']) and $adm['items'] == 2)
	{
		$info = (int)$_GET['info'];
		$item=mysql_fetch_array(myquery("select * from game_items_factsheet where id=$info"));
		$sel_user = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=0 GROUP BY item_id");
		list($count_user) = mysql_fetch_array($sel_user);
		$sel_down = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=2 GROUP BY item_id");    
		list($count_down) = mysql_fetch_array($sel_down);
		$sel_rynok = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND (priznak=1 OR priznak=3) GROUP BY item_id");    
		list($count_rynok) = mysql_fetch_array($sel_rynok);
		$sel_hran = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=4 GROUP BY item_id");    
		list($count_hran) = mysql_fetch_array($sel_hran);

		$all_items = $count_user+$count_rynok+$count_down+$count_hran;
		echo '<center><b>������� ���������� �������� <font color=#FFFFFF>'.$item['name'].'</font> � ����</b><br>';
		echo '<ul>';
		echo '<li>���������� � �������: <font color=#FFFF00><b>'.$count_user.'</b></font></li>';
		echo '<li>���������� �� ������ � �� �����: <font color=#FFFF00><b>'.$count_rynok.'</b></font></li>';
		echo '<li>���������� �� �����: <font color=#FFFF00><b>'.$count_down.'</b></font></li>';
		echo '<li>���������� � ������ ����������: <font color=#FFFF00><b>'.$count_hran.'</b></font></li>';
		echo '</ul>';
		echo '<b>����� <font color=#FF0000><b>'.$all_items.'</b></font> '.pluralForm($all_items,'�������','��������','���������').'<br>';

		$select = myquery("SELECT user_id,SUM(count_item) AS kol FROM game_items WHERE item_id=".$item['id']." AND priznak=0 GROUP BY user_id ORDER BY kol DESC");
		if (mysql_num_rows($select))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">�������� � �������</font></b><br><br>';
			echo '<table>';
			$nom=0;
			while ($it=mysql_fetch_array($select))
			{
				$nom++;
				if ($nom==6) $nom=1;
				if ($nom==1) echo '<tr>';
				echo '<td>';
				echo '<font size="2" color="#bbbbbb">';
				$clan_id = (int)get_user('clan_id',$it['user_id']); 
				if ($clan_id!=0) echo'<img src="http://'.img_domain.'/clan/'.$clan_id.'.gif"> ';
				echo '<a href="http://'.domain_name.'/view/?userid='.$it['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
				echo'' . get_user('name',$it['user_id']). '</font> ('.$it['kol'].')<br>';
				echo '</td>';
				if ($nom==5) echo '</tr>';
			}
			echo '</table>';
		}
				
		$select = myquery("SELECT user_id,SUM(count_item) AS kol FROM game_items WHERE item_id=".$item['id']." AND priznak=4 GROUP BY user_id ORDER BY kol DESC");
		if (mysql_num_rows($select))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">�������� � ����� �������</font></b><br><br>';
			echo '<table>';
			$nom=0;
			while ($it=mysql_fetch_array($select))
			{
				$nom++;
				if ($nom==6) $nom=1;
				if ($nom==1) echo '<tr>';
				echo '<td>';
				echo '<font size="2" color="#bbbbbb">';
				$clan_id = (int)get_user('clan_id',$it['user_id']); 
				if ($clan_id!=0) echo'<img src="http://'.img_domain.'/clan/'.$clan_id.'.gif"> ';
				echo '<a href="http://'.domain_name.'/view/?userid='.$it['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
				echo'' . get_user('name',$it['user_id']). '</font> ('.$it['kol'].')<br>';
				echo '</td>';
				if ($nom==5) echo '</tr>';
			}
			echo '</table>';
		}
				
		$select = myquery("SELECT game_items.map_name,game_items.map_xpos,game_items.map_ypos,game_maps.name FROM game_items LEFT JOIN game_maps ON game_maps.id=game_items.map_name WHERE item_id=".$item['id']." AND priznak=2 ORDER BY game_maps.name");
		if (mysql_num_rows($select ))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">�������� �� �����</font></b><br><br>';
			echo '<table>';
			while ($it=mysql_fetch_array($select))
			{
				echo '<tr><td>';
				echo '<font size="2" color="#bbbbbb">';
				echo '����� �� �����: '.$it['name'].' x-'.$it['map_xpos'].', y-'.$it['map_ypos'].'';
				echo '</font><br>';
				echo '</td></tr>';
			}
			echo '</table>';
		}

		$select_old_items = myquery("SELECT DISTINCT town FROM game_items WHERE item_id=".$item['id']." AND priznak=1");
		if (mysql_num_rows($select_old_items))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">�������� �� �����</font></b><br><br>';
			{
				echo '<table>';
				while (list($town) = mysql_fetch_array($select_old_items))
				{
					$kol_items = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id']." AND town=$town"),0,0);
					$select = myquery("SELECT rustown FROM game_gorod WHERE town=".$town."");
					list($rustown) = mysql_fetch_array($select);
					if ($kol_items!=0)
					{
						echo '<tr><td>';
						echo '<font size="2" color="#bbbbbb">';
						echo ''.$kol_items.' '.pluralForm($kol_items,'�������','��������','���������').' �� ����� � ������: '.$rustown.'.<br>';
						echo '</font>';
						echo '</td></tr>';
					}
				}
				echo '</table>';
			}
		}
		
		$select_old_items = myquery("SELECT DISTINCT town FROM game_items WHERE item_id=".$item['id']." AND priznak=3");
		if (mysql_num_rows($select_old_items))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">�������� �� �����</font></b><br><br>';
			{
				echo '<table>';
				while (list($town) = mysql_fetch_array($select_old_items))
				{
					$kol_items = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id']." AND town=$town"),0,0);
					$select = myquery("SELECT rustown FROM game_gorod WHERE town=".$town."");
					list($rustown) = mysql_fetch_array($select);
					if ($kol_items!=0)
					{
						echo '<tr><td>';
						echo '<font size="2" color="#bbbbbb">';
						echo ''.$kol_items.' '.pluralForm($kol_items,'�������','��������','���������').' �� ����� � ������: '.$rustown.'.<br>';
						echo '</font>';
						echo '</td></tr>';
					}
				}
				echo '</table>';
			}
		}
		
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($_GET['new']))
	{
		echo'<center><b>���������� ������ ��������</b><br><br>';
		echo'�� ������ ��������: <br><table cellspacing=2 cellpadding=2 border=0><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=1">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=4">���</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=5">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=3">��������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=6">����</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=7">�����</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=2">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=8">����</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=10">��������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=11">�����</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=24">�����������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=14">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=15">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=16">���������</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=18">����</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=21">������</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=19">����������� ��������</a></td></tr><tr align="center"><td>';
        //<a href="admin.php?opt=main&option=items&new&tp_new=17">���.�����</a> | 
        echo '<a href="admin.php?opt=main&option=items&new&tp_new=13">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=12">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=9">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=22">���� �������</a></td></tr>
        <tr align="center"><td><a href="admin.php?opt=main&option=items&new&tp_new=20">����� ���������</a></td></tr>
        <tr align="center"><td><a href="admin.php?opt=main&option=items&new&tp_new=23">��������� ���������</a></td></tr></table>';
		if (isset($_GET['tp_new']))
		{
			if (!isset($_POST['save']))
			{
        if (isset($_GET['tp_new']))
          $tp_new = (int) $_GET['tp_new'];
        else
          $tp_new = 0;

          echo '<script language="JavaScript" type="text/javascript">
				function count1()
				{
					val=
					Number(document.new_item_form.ostr.value)+
					Number(document.new_item_form.ontl.value)+
					Number(document.new_item_form.opie.value)+
					Number(document.new_item_form.ovit.value)+
					Number(document.new_item_form.odex.value)+
					Number(document.new_item_form.ospd.value)+
					Number(document.new_item_form.olucky.value);
					if (val>=5) val=0;
					val=val/2;
					document.new_item_form.need.value=val;
				}
				function count2()
				{
					document.new_item_form.plus.value=
					Number(document.new_item_form.dstr.value)+
					Number(document.new_item_form.dntl.value)+
					Number(document.new_item_form.dpie.value)+
					Number(document.new_item_form.dvit.value)+
					Number(document.new_item_form.ddex.value)+
					Number(document.new_item_form.dspd.value)+
					Number(document.new_item_form.dlucky.value)+
					((Number(document.new_item_form.hp_p.value)+
					  Number(document.new_item_form.mp_p.value)+
					  Number(document.new_item_form.stm_p.value)+
					  Number(document.new_item_form.pr_p.value)
					 )/15
					);
				}
				</script>';
				echo'<center><form action="" name="new_item_form" method="post">
				<table border="0" width="100%">
				<tr><td align="right">��������:</td><td><input name="name" value="" type="text" size="25"></td></tr>
				<tr><td align="right">���:</td><td><input name="type" value="'.type_str($tp_new).'" type="text" size="25" readonly="true"></td></tr>';

        if ($tp_new!=23)
        {
          if($tp_new==1 OR $tp_new==21 OR $tp_new==19) echo'<tr><td align="right">����:</td><td><input name="indx" value="" type="text" size="5">&plusmn;<input name="deviation" value="" type="text" size="3"></td></tr>';
          if($tp_new==3) echo'<tr><td align="right">���-�� �������:</td><td><input name="item_uselife" value="" type="text" size="5"></td></tr>';
          else $item_uselife=100;

          if($tp_new==4) echo'<tr><td align="right">������:</td><td><input name="indx" value="" type="text" size="5"></td></tr>';

          if($tp_new==3) 
          {
            echo'<tr><td align="right">�������� ���������</td><td><select name="sv"><option>�����</option><option selected>������</option><option>�������</option></select></td></tr>
            <tr><td align="right">����, ������ ��� �������:</td><td><input name="indx" value="" type="text" size="5">&plusmn;<input name="deviation" value="" type="text" size="3"></td></tr>';
          }
          if($tp_new==1 or $tp_new==3 or $tp_new==4 or $tp_new==18 OR $tp_new==19 OR $tp_new==21)
            echo'<tr><td align="right">��� ������</td><td><input name="mode" value="" type="text" size="40"> - ������: ��������� �����</td></tr>';
          echo'<tr><td align="right">���:</td><td><input name="weight" value="" type="text" size="4"></td></tr>
          <tr><td align="right">��������:</td><td><input name="redkost" value="" type="text" size="4" maxsize="2"></td></tr>';
        }
        echo '<tr><td align="right">�������� ��������: (���� �����)</td><td><textarea name="curse" cols="30" rows="15"></textarea></td></tr>

				<tr><td align="right">����� �������:</td><td><input name="imgg" value="*���" type="text" size="40"> *��� - �������� ����� (��� .gif)</td></tr>
				<tr><td align="right">����� �������� �������:</td><td><input name="imgbig" value="big/*���" type="text" size="40"> *��� - �������� �����</td></tr>';

        if ($tp_new!=23)
        {
          echo'<tr><td align="right">&nbsp;</td><td></td></tr>
          <tr><td align="right">������� ����:</td><td><input name="item_cost" value="" type="text" size="8"> ����� (��� ������� ���������)</td></tr>';
          if ($tp_new==3)
          {
            echo'<tr><td align="right">��������� �������:</td><td><input name="quantity" value="20" type="text" size="8">% �� ������� ����</td></tr>'; 
            echo'<tr><td align="right">����� ����� ��������</td><td><input name="cooldown" value="120" type="text" size="8"> ������</td></tr>'; 
          }
          if($tp_new==1 OR $tp_new==18 OR $tp_new==19)
          {
            echo'<tr><td align=right>����� ������:</td><td><select name="type_weapon"><option value="0">��� ������</option>';
            echo'<option value="1">��������</option>';
            echo'<option value="2">����������</option>';
            echo'<option value="3">�������</option>';
            echo'<option value="4">��������</option>';
            echo'<option value="5">�������</option>';
            echo'<option value="6">�����������</option>';
            echo'</select></td></tr>';
            echo '<tr><td align=right>������� ������� ������:</td><td><input name="type_weapon_need" value="" type="text" size="5"></td></tr>';
          }
          if ($tp_new==1)
          {
            echo '<tr><td align=right>��������� ������</td><td><input name="in_two_hands" type="checkbox" value="1"></td></tr>';
          }
          if ($tp_new!=20)
          {
            echo'<tr><td align="right">&nbsp;</td><td></td></tr>

            <tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>������� ��������������:</b></font></td><td>
            <tr><td align="right">�������: </td><td><input name="oclevel" value="" type="text" size="3"></td></tr>
            <tr><td align="right">����: </td><td><input name="ostr" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">���������: </td><td><input name="ontl" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">��������: </td><td><input name="opie" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">������: </td><td><input name="ovit" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">������������: </td><td><input name="odex" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">��������: </td><td><input name="ospd" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">�����: </td><td><input name="olucky" value="" type="text" size="3" onChange="count1()"></td></tr>
            <tr><td align="right">&nbsp;</td><td></td></tr>
            <tr><td align="right">����� ����.�������������</td><td><input name="need" value="" type="text" size="4" readonly="true"></td></tr>
            <tr><td align="right">&nbsp;</td><td></td></tr>';
          }
        }
                
        if ($tp_new!=20)
        {
          echo '
					<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>��������� ��������������:</b></font></td><td>
					<tr><td align="right">����: </td><td><input name="dstr" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">���������: </td><td><input name="dntl" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">��������: </td><td><input name="dpie" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">������: </td><td><input name="dvit" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">������������: </td><td><input name="ddex" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">��������: </td><td><input name="dspd" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">�����: </td><td><input name="dlucky" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">&nbsp;</td><td>';

					echo'<tr bgcolor=000000><td align="right">�������� ������ ���� � �������:</td>
					<td>
					<table border=0>
					<tr><td><input name="hp_p" value="" type="text" size="3" onChange="count2()"></td><td>�����</td></tr>
					<tr><td><input name="mp_p" value="" type="text" size="3" onChange="count2()"></td><td>����</td></tr>
					<tr><td><input name="stm_p" value="" type="text" size="3" onChange="count2()"></td><td>�������</td></tr>
					<tr><td><input name="pr_p" value="" type="text" size="3" onChange="count2()"></td><td>�����</td></tr>
					</table>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';

					echo'<tr bgcolor=000000><td align="right">��������� �������� �����:</td>
					<td>
					��: <input name="cc_p" value="" type="text" size="2"><br>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';
                    
					if(($tp_new==2 OR $tp_new==5 OR $tp_new==6 OR $tp_new==8)and($tp_new!=23))
					{
						echo'<tr bgcolor=000000><td align="right" width="50%">��� �����<br /><br />(������ �������� �����, ������ �������� ���� � ����, ���� �������� ������, ���� �������� ���)</td>
						<td>
						&nbsp;<input name="def_type" value="0" type="radio" checked>  ������<br>
						&nbsp;<input name="def_type" value="1" type="radio">  ��������<br>
						&nbsp;<input name="def_type" value="2" type="radio">  ���������� (��������)<br>
						&nbsp;<input name="def_type" value="3" type="radio">  ���� (������������)<br>
						<br>
						�������� "����������" ������: <input name="def_index" value="0" type="text" size="4" maxsize="3"><br />
						�������� "����������" ������: <input name="magic_def_index" value="0" type="text" size="4" maxsize="3">
						</td>
						</tr>';
					}

					echo'<tr><td align="right">����� �����.�������������</td><td><input name="plus" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
				}
				else
				{
					echo '<tr><td align="right">������� �����</td><td>
					<select name="variant">
					<option value="1">����� ������� ������</option>
					<option value="2">����� ������� ������</option>
					<option value="3">����� �������� ������</option>
					<option value="4">����� ���������� ������</option>
					<option value="5">����� ������ ������</option>
					</select>
					<table border=1 cellspacing=2>
					<tr><td valign="middle">����� ������� ������</td><td>������� ��� �������������:<br />����� "���������" - 0<br />������� ������ - 8<br />��������� ����� �� ������������ ���� - 120 ���.</td></tr>
					<tr><td valign="middle">����� ������� ������</td><td>������� ��� �������������:<br />����� "���������" - 55<br />������� ������ - 12<br />��������� ����� �� ������������ ���� - 180 ���.</td></tr>
					<tr><td valign="middle">����� �������� ������</td><td>������� ��� �������������:<br />����� "���������" - 85<br />������� ������ - 16<br />��������� ����� �� ������������ ���� - 240 ���.</td></tr>
					<tr><td valign="middle">����� ���������� ������</td><td>������� ��� �������������:<br />����� "���������" - 115<br />������� ������ - 20<br />��������� ����� �� ������������ ���� - 300 ���.</td></tr>
					<tr><td valign="middle">����� ������ ������</td><td>������� ��� �������������:<br />����� "���������" - 145<br />������� ������ - 24<br />��������� ����� �� ������������ ���� - 420 ���.</td></tr>
					</table>
					</td></tr>';
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>����� ��� ��������</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>���-�� ����������� �����</td><td><input name="quantity"  type="text" size="5" value="1">';
				}

        if ($tp_new!=23)
        {
          echo'<tr><td align="right">������ ��� ����:</td><td><select name="race"><option value=0 selected></option>';
          $res=myquery("select name,id from game_har where disable=0");
          while($option=mysql_fetch_array($res))
            echo "<option value=".$option['id'].">".$option["name"]."</option>";
          echo'</select></td></tr>
          <tr><td align="right">&nbsp;</td><td></td></tr>
          <tr><td align=right>������ �������</td><td><select name="personal">
			  <option value="0">������� �������</option>
			  <option value="1">���������� ������ ��� ���������</option>
			  <option value="2">���������� ������ ��� ��������</option>
			  </select></td></tr>
          <tr><td align=right>������� ����� �����</td><td><input name="can_up" type="checkbox" value="1" ';
          if ($tp_new!=19 AND $tp_new!=21 AND $tp_new!=20) echo 'checked';
          echo '></td></tr>
					<tr><td align=right>������� ����� ������������</td><td><input name="can_use" type="checkbox" value="1"></td></tr>
          <tr><td align=right>� �������� ����������� �������������</td><td><input name="breakdown" type="checkbox" value="1"></td></tr>
          <tr><td align="right">������������� ��������</td><td><input name="item_uselife_max" value="100" type="text" size="5"></td></tr>';
        }
        echo '<tr><td align="right">���������� ��������� ����. � 1 ������ (0 - ��� ������)</td><td><input name="kol_per_user" value="0" type="text" size="4" maxsize="3"></td></tr>				     
        <tr><td align="right">����� ��� ������������</td><td>
				<select name="view">
				<option value="0">���������� ������� ����������</option>
				<option value="1" selected>���������� ������ ����������</option>
				<option value="2">�� ����������</option>
				</select></td></tr>';
        if ($tp_new!=23)
        {
            echo '
            <tr><td align=right>�������������� ��� �����:</td><td>
            <select name="clan_id">
            <option value="0" selected>��� �������� � �����</option>';
            $sel = myquery("SELECT clan_id,nazv FROM game_clans WHERE raz='0' ORDER BY clan_id");
            while ($cl = mysql_fetch_array($sel))
            {
              echo '<option value="'.$cl['clan_id'].'">'.$cl['nazv'].'</option>'; 
            }
            echo '</select>
            </td></tr>
            <tr><td align="right">&nbsp;</td><td></td></tr>';
        }
        echo '
				<tr><td align="right">����� ����� �������� � �������� (0 - ����������)</td><td><input name="life_time" value="0" type="text" size="10" maxsize="10"></td></tr>				     
				<tr><td align="right">��� �������� (0 - ���� ���)</td><td><input name="set_id" value="0" type="text" size="10" maxsize="10"></td></tr>				     
				<tr><td align="right"><input name="save" type="submit" value="��������"></td></tr>
				<input name="save" type="hidden" value="">
				</table>
				</form>';
			}
			else
			{
				echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>�������: '.$_POST['name'].' ��������<b></font>';
				if (!isset($_POST['personal'])) $_POST['personal'] = 0;				

				if (!isset($_POST['can_up'])) $_POST['can_up1']=0;

				if (!isset($_POST['can_use'])) $_POST['can_use1']=0;
				
				if (!isset($_POST['breakdown'])) $_POST['break']=0;

				if (!isset($_POST['in_two_hands'])) $_POST['in_two']=0;

				if(!isset($_POST['indx'])) $_POST['indx']=0;
				if(!isset($_POST['deviation'])) $_POST['deviation']=0;
				if(!isset($_POST['mode'])) $_POST['mode']='';
				if(!isset($_POST['sv'])) $_POST['sv']='';

				if(!isset($_POST['item_uselife'])) $_POST['item_uselife']=100;				

				$type = (int)$_GET['tp_new'];

				if(!isset($_POST['type_weapon'])) $_POST['type_weapon']=0;
				if(!isset($_POST['type_weapon_need'])) $_POST['type_weapon_need']=0;
				if(!isset($_POST['def_type'])) $_POST['def_type']=0;
				if(!isset($_POST['def_index'])) $_POST['def_index']=0;
				if(!isset($_POST['magic_def_index'])) $_POST['magic_def_index']=0;
				if(!isset($_POST['clan_id'])) $_POST['clan_id']=0;

				//if ($type==12) $pers=1;

				if(!isset($_POST['quantity'])) $_POST['quantity']=0; 
				if(!isset($_POST['cooldown'])) $_POST['cooldown']=0; 
                
        if (!isset($_POST['kol_per_user'])) $_POST['kol_per_user']=0;

				if(!isset($_POST['ostr'])) $_POST['ostr']=0; 
				if(!isset($_POST['ontl'])) $_POST['ontl']=0; 
				if(!isset($_POST['opie'])) $_POST['opie']=0; 
				if(!isset($_POST['ovit'])) $_POST['ovit']=0; 
				if(!isset($_POST['odex'])) $_POST['odex']=0; 
				if(!isset($_POST['ospd'])) $_POST['ospd']=0; 
				if(!isset($_POST['oclevel'])) $_POST['oclevel']=0; 
				if(!isset($_POST['dstr'])) $_POST['dstr']=0; 
				if(!isset($_POST['dntl'])) $_POST['dntl']=0; 
				if(!isset($_POST['dpie'])) $_POST['dpie']=0; 
				if(!isset($_POST['dvit'])) $_POST['dvit']=0; 
				if(!isset($_POST['ddex'])) $_POST['ddex']=0; 
				if(!isset($_POST['dspd'])) $_POST['dspd']=0; 
				if(!isset($_POST['sv'])) $_POST['sv']=0; 
				if(!isset($_POST['race'])) $_POST['race']=0; 
				if(!isset($_POST['hp_p'])) $_POST['hp_p']=0;
				if(!isset($_POST['mp_p'])) $_POST['mp_p']=0;
				if(!isset($_POST['stm_p'])) $_POST['stm_p']=0;
				if(!isset($_POST['pr_p'])) $_POST['pr_p']=0;
				if(!isset($_POST['cc_p'])) $_POST['cc_p']=0;
				if(!isset($_POST['quantity'])) $_POST['quantity']=0; 
				if(!isset($_POST['def_type'])) $_POST['def_type']=0; 
				if(!isset($_POST['def_index'])) $_POST['def_index']=0; 
				if(!isset($_POST['type_weapon'])) $_POST['type_weapon']=0; 
				if(!isset($_POST['type_weapon_need'])) $_POST['type_weapon_need']=0; 
				if(!isset($_POST['break'])) $_POST['break']=0; 
				if(!isset($_POST['item_uselife_max'])) $_POST['item_uselife_max']=0; 
				if(!isset($_POST['magic_def_index'])) $_POST['magic_def_index']=0; 
				if(!isset($_POST['in_two'])) $_POST['in_two']=0; 
				if(!isset($_POST['olucky'])) $_POST['olucky']=0; 
				if(!isset($_POST['dlucky'])) $_POST['dlucky']=0; 
				if(!isset($_POST['can_up1'])) $_POST['can_up1']=0; 
				if(!isset($_POST['clan_id'])) $_POST['clan_id']=0; 
				if(!isset($_POST['variant'])) $_POST['variant']=0; 
				if(!isset($_POST['item_name'])) $_POST['item_name']=''; 
				if(!isset($_POST['life_time'])) $_POST['life_time']=0;
				if(!isset($_POST['set_id'])) $_POST['set_id']=0;

				if ($type==20)
				{
					$oclevel = $variant;
					$item_id = 0;
					$selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
					if (mysql_num_rows($selitemid)>0)
					{
						$item_id=mysqlresult($selitemid,0,0);
					}
					$indx = $item_id;
				}
                
        if (!isset($_POST['weight'])) $_POST['weight'] = 0;
        if (!isset($_POST['item_cost'])) $_POST['item_cost'] = 0;
        if (!isset($_POST['redkost'])) $_POST['redkost'] = 0;
                
				$up=myquery("INSERT INTO game_items_factsheet (name,type,indx,deviation,mode,weight,curse,img,item_uselife,".
                    "item_cost,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,".
                    "stm_p,pr_p,cc_p,view,redkost,imgbig,personal,quantity,cooldown,def_type,def_index,type_weapon,".
                    "type_weapon_need,breakdown,item_uselife_max,magic_def_index,in_two_hands,olucky,dlucky,can_up,".
                    "can_use,clan_id,kol_per_user,life_time,set_id)VALUES ('".$_POST['name']."','".$type.
                    "','".$_POST['indx']."','".$_POST['deviation']."','".mysql_real_escape_string($_POST['mode']).
                    "','".$_POST['weight']."','".mysql_real_escape_string($_POST['curse'])."','".$_POST['imgg']."','".
                    $_POST['item_uselife']."','".$_POST['item_cost']."','".$_POST['ostr']."','".$_POST['ontl']."','".
                    $_POST['opie']."','".$_POST['ovit']."','".$_POST['odex']."','".$_POST['ospd']."','".$_POST['oclevel']."','".
                    $_POST['dstr']."','".$_POST['dntl']."','".$_POST['dpie']."','".$_POST['dvit']."','".$_POST['ddex']."','".
                    $_POST['dspd']."','".$_POST['sv']."','".$_POST['race']."','".$_POST['hp_p']."','".$_POST['mp_p']."','".
                    $_POST['stm_p']."','".$_POST['pr_p']."','".$_POST['cc_p']."','".$_POST['view']."','".$_POST['redkost']."','".
                    $_POST['imgbig']."','".$_POST['personal']."','".$_POST['quantity']."','".$_POST['cooldown']."','".$_POST['def_type']."','".
                    $_POST['def_index']."','".$_POST['type_weapon']."','".$_POST['type_weapon_need']."','".$_POST['break']."','".
                    $_POST['item_uselife_max']."','".$_POST['magic_def_index']."','".$_POST['in_two']."','".$_POST['olucky']."','".
                    $_POST['dlucky']."','".$_POST['can_up1']."','".$_POST['can_use1']."','".$_POST['clan_id']."','".
                    $_POST['kol_per_user']."', '".$_POST['life_time']."', '".$_POST['set_id']."')");

				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 '������� ����� ������� <b>".$_POST['name']."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=items">';

			}
		}
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($rename) and $adm['items'] == 2)
	{
		$select = myquery("UPDATE game_items_factsheet SET name = '".$name2."' WHERE name='".$name1."'");
		echo'<center><br><br><font color=ff0000><b>�������: '.$name1.' ������������ �: '.$name2.'!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '��������� �������������� �������� <b>".$name1."</b> � <b>".$name2."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
		 or die(mysql_error());
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	function delete_from_map($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND priznak=2"),0,0);
			if ($kol>0)
			{
				$select = myquery("DELETE from game_items WHERE item_id=$item_id AND priznak=2");
				echo'<center><br><br><font color=ff0000><b>�������: '.$name_items.'('.$kol.' ��.) ������ � ����!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 '������ � ���� ������� <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}

	if(isset($_GET['del_0']) and $adm['items'] == 2)
	{
		delete_from_map($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	function delete_from_market($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND (priznak=1 OR priznak=3 OR priznak=4)"),0,0);
			if ($kol>0)
			{
				$select = myquery("DELETE from game_items WHERE item_id=$item_id AND (priznak=1 OR priznak=3 OR priznak=4)");
				echo'<center><br><br><font color=ff0000><b>�������: '.$name_items.'('.$kol.' ��.) ������ � ������!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 '������ � ������ ������� <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}

	if(isset($_GET['del_1']) and $adm['items'] == 2)
	{
		delete_from_market($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	function delete_from_users($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND priznak=0"),0,0);
			if ($kol>0)
			{
				$sel_user = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND priznak=0");
				while (list($itemsid) = mysql_fetch_array($sel_user))
				{
					$Item = new Item($itemsid);
					$Item->admindelete();
				}
				echo'<center><br><br><font color=ff0000><b>�������: '.$name_items.'('.$kol.' ��.) ������ � �������!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 '������ � ������� ������� <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}
	
	if(isset($_GET['del_2']) and $adm['items'] == 2)
	{
		delete_from_users($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($_GET['del_3']) and $adm['items'] == 2)
	{
		list($name_items, $type_items) = mysql_fetch_array(myquery("SELECT name, type FROM game_items_factsheet WHERE id=".(int)$_GET['del'].";"));
                if ($type_items == 23)
		{
			$res = myquery("SELECT `id`,`user_id` FROM `game_items` WHERE `priznak`=0 AND `used`=22 AND `item_id` = ".(int)$_GET['del'].";");
			while ($list = mysql_fetch_array($res))
			{
				$item = new Item($list['id']);
				$item->setChar($list['user_id']);
				$item->down();
			}

			myquery("DELETE FROM `game_items_complect` WHERE `complect_id` = ".(int)$_GET['del'].";;");
		}
		delete_from_map("",(int)$_GET['del']);
		delete_from_market("",(int)$_GET['del']);
		delete_from_users("",(int)$_GET['del']);
		$select = myquery("DELETE from game_items_factsheet where id='".(int)$_GET['del']."'");
		echo'<center><br><br><font color=ff0000><b>�������: '.$name_items.' ������ �� ������������!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������ �� ������������ ������� <b>".$name_items."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
		 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	//����� ������� ���������
	if (isset($show_table))
	{
		require_once ("items_table.inc.php");
	}
		
	//����� �������� ���������
	if (isset($delete_items) AND $adm['items'] == 2)
	{
		?>
		<script type="text/javascript">
		/* URL to the PHP page called for receiving suggestions for a keyword*/
		var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		var startSearch = 3;
		</script><?
		echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
		echo '<div id="content" onclick="hideSuggestions();"><center>�������� ���������:</center><br><br>';
		echo '<center><form action="" method="post">
		��� ��������        <input id="keyword" name="name_items" value="" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div><br><br>';
		echo '<input name="del_0" type="submit" value="������� ����������� �� �����">';
		echo '<br><br>';
		echo '<input name="del_1" type="submit" value="������� � ������, �������� � �����">';
		echo '<br><br>';
		echo '<input name="del_2" type="submit" value="������� � �������">';
		echo '<br><br>';
		echo '<input name="del_3" type="submit" value="������� �� ������������ (������������� ����� ������� �� ���� ���������)">';
		echo '<br><br>';
		echo '</form></center>';
		echo '</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table></div><script>init();</script>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if (isset($rename_items) AND $adm['items'] == 2)
	{
		?>
		<script type="text/javascript">
		/* URL to the PHP page called for receiving suggestions for a keyword*/
		var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		var startSearch = 1;
  </script><?
		echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
		echo '<div id="content" onclick="hideSuggestions();"><center>�������������� ���������:</center><br><br>';
		echo '<center><form action="" method="post">
		<table border="0" width="100%">
		<tr><td>��� ��������</td><td><input name="name1" value="" type="text" size="50"  onkeyup="handleKeyUp(event)" id="keyword"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td><td>�������� ��</td><td>';
		echo '<input name="name2" value="" type="text" size="50">';
		echo '</td>';
		echo '<td><input name="rename" type="submit" value="�������������"></td>';
		echo '</td></tr>';
		echo '</table></form>';
		echo '</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table></div><script>init();</script>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//������������ ��������
	if (isset($_GET['clon']) AND $adm['items'] == 2)
	{
    $clon = $_GET['clon'];
		$sel = myquery("SELECT * FROM game_items_factsheet WHERE id=$clon");
		$item = mysql_fetch_array($sel);
		$name_items = $item['name'];
		$new_name = '!('.$name_items.')!';
		$new = myquery("
		INSERT INTO game_items_factsheet
			(name,type,indx,deviation,mode,weight,curse,img,item_uselife,item_cost,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,pr_p,cc_p,personal,view,quantity,cooldown,def_type,def_index,type_weapon,type_weapon_need,in_two_hands,olucky,dlucky,can_up,can_use,clan_id,life_time,set_id)
		VALUES
			('$new_name','".$item['type']."','".$item['indx']."','".$item['deviation']."','".$item['mode']."','".$item['weight']."','".$item['curse']."','".$item['img']."','".$item['item_uselife']."','".$item['item_cost']."','".$item['ostr']."','".$item['ontl']."','".$item['opie']."','".$item['ovit']."','".$item['odex']."','".$item['ospd']."','".$item['oclevel']."','".$item['dstr']."','".$item['dntl']."','".$item['dpie']."','".$item['dvit']."','".$item['ddex']."','".$item['dspd']."','".$item['sv']."','".$item['race']."','".$item['hp_p']."','".$item['mp_p']."','".$item['stm_p']."','".$item['pr_p']."','".$item['cc_p']."','".$item['personal']."','".$item['view']."','".$item['quantity']."','".$item['cooldown']."','".$item['def_type']."','".$item['def_index']."','".$item['type_weapon']."','".$item['type_weapon_need']."','".$item['in_two_hands']."','".$item['olucky']."','".$item['dlucky']."','".$item['can_up']."','".$item['can_use']."','".$item['clan_id']."','".$item['life_time']."','".$item['set_id']."')");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '���������� �������:  <b>".$name_items."</b> � ������� <b>".$new_name."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
				 or die(mysql_error());
		echo'<center><br><br><font color=ff0000><b>�������: "'.$name_items.'" ���������� � ������� "'.$new_name.'"!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="3;url=admin.php?option=items&opt=main&tp='.$item['type'].'">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//�������� �����
	if ($char['name']=='bruser' or $char['name']=='mrHawk')
	{
		echo'<center><br /><a href="admin.php?opt=main&option=items&show_table">�������� ��� ��������</a></center>';
	}
	echo'<CENTER><br /><a href="admin.php?opt=main&option=items&new">�������� �������</a>';
	if($adm['items'] == 2)
	{
		echo' | <a href="admin.php?opt=main&option=items&delete_items">�������� ���������</a>';
		echo' | <a href="admin.php?opt=main&option=items&rename_items">�������������� ���������</a>&nbsp;<br />&nbsp;';
        echo'&nbsp;<br /><hr>
        <br><table cellspacing=2 cellpadding=2 border=0><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=1">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=4">���</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=5">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=3">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=6">����</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=7">�����</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=2">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=8">����</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=10">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=11">�����</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=24">�����������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=14">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=15">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=16">���������</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=18">����</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=21">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=19">����������� ��������</a></td></tr><tr align="center"><td>';
        //<a href="admin.php?opt=main&option=items&tp=17">���.�����</a> | 
        echo '<a href="admin.php?opt=main&option=items&tp=13">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=12">������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=9">��������</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=22">���� �������</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=20">����� ���������</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=23">��������� ���������</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=666">���������</a></td></tr><tr align="center">
        <td><a href="admin.php?opt=main&option=items&tp=0">��� ��������</a></td></tr></table>';
	}

	if (isset($_GET['tp']))
	{
    $line=40;
    $tp = (int)$_GET['tp'];
		if ($tp==666)
		{
			$items=myquery("select COUNT(*) from game_items_factsheet where type>=90 order by id DESC");
		}
		elseif ($tp==0)
        {
            $items=myquery("select COUNT(*) from game_items_factsheet order by name, id DESC");
            $line=mysql_result($items,0,0)+10;
        }
        else
		{
			$items=myquery("select COUNT(*) from game_items_factsheet where type=$tp order by id DESC");
		}
		$allpage=ceil(mysql_result($items,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		if ($tp==666)
		{
			$items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) where game_items_factsheet.type>=90 order by game_items_factsheet.id DESC limit ".(($page-1)*$line).", $line");
		}
		elseif ($tp==0)
    {
        $items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) order by game_items_factsheet.name ASC");
    }
    else
		{
			$items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) where game_items_factsheet.type=$tp order by game_items_factsheet.id DESC limit ".(($page-1)*$line).", $line");
		}
		echo'<table border=0><tr><td>��������</td><td>����</td><td>����</td><td>����</td><td>����</td><td>� ����</td><td colspan="5" align="center">��������</td></tr>';
		while($item=mysql_fetch_array($items))
		{
			echo'<tr'; if ($item['sv']=='�����') echo ' bgcolor="#800003"'; if ($item['sv']=='������') echo ' bgcolor="#020062"'; if ($item['sv']=='�������') echo ' bgcolor="#045500"';echo'><td><font color=#FF8080>'.$item['id'].'</font>. ';
			if ($item['view']=='0') echo '<font color=#FF8080>'.$item['name'].'</font>';
			elseif ($item['view']=='2') echo '<span style="color:#00FF40;text-decoration:line-through;">'.$item['name'].'</span>'; 
			else echo $item['name'];
			//echo'</td><td><font color=#FF80C0>'.$item['redkost'].'</font></td><td>'.$item['indx'].'&plusmn;'.$item['deviation'].'</td><td><font color=#FFFF00>'.$item['item_cost'].'</font></td><td><font color=#FF0000>'.(($item['race']==0) ? "&nbsp;" : $item['race_name']).'<font></td><td>'.mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id'].""),0,0).'</td>';
			if ($tp==3)
			{
				$count_item = mysqlresult(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id'].""),0,0);
			}
			else
			{
				$count_item = mysqlresult(myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." GROUP BY item_id"),0,0);
			}
			echo'</td><td><font color=#FF80C0>'.$item['redkost'].'</font></td><td>'.$item['indx'].'&plusmn;'.$item['deviation'].'</td><td><font color=#FFFF00>'.$item['item_cost'].'</font></td><td><font color=#FF0000>'.(($item['race']==0) ? "&nbsp;" : $item['race_name']).'<font></td><td>'.$count_item.'</td>';

			if($adm['items'] == 2)
			{
				echo'<td><a href="admin.php?opt=main&option=items&edit='.$item['id'].'">���.</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				echo'<td><a href="admin.php?opt=main&option=items&del_3&del='.$item['id'].'">��.</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				if ($item['type']!=23) echo'<td><a href="admin.php?opt=main&option=items&add_user='.$item['id'].'">���.������</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				echo'<td><a href="admin.php?opt=main&option=items&clon='.$item['id'].'">����.</a></td>';
				if ($item['type']!=23) echo'<td><a href="admin.php?opt=main&option=items&info='.$item['id'].'" target="_blank">����</a></td>';
			}
			echo'</tr>';
		}
		echo'</table>';
		$href = 'admin.php?opt=main&option=items&tp='.$tp.'&';
		echo'<center>��������: ';
		show_page($page,$allpage,$href);
	}

	//���������� �������� ������
	if(isset($_GET['add_user']) and $adm['items'] == 2)
	{
    $add_user = (int)$_GET['add_user'];
		if (!isset($_POST['see']))
		{
			$items=myquery("select name from game_items_factsheet where id=$add_user");
			list($name_item)=mysql_fetch_array($items);
			echo '<div id="content" onclick="hideSuggestions();"><hr><center>���������� ��������: <font size="2" face="Verdana" color="#ff0000"><b>'.$name_item.'</b></font> ��� ������:<br>';
			echo '<form action="admin.php?opt=main&option=items&add_user='.$add_user.'" method="post"><font size="1" face="Verdana" color="#ffffff">��� ������: <input name="user_name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>';
			echo '<br>���������� ���������: <input type="text" maxsize="3" size="3" name="kol" value="1">';
			echo '<br>������������� (0 - ������): <input type="text" maxsize="3" size="3" name="max_uselife" value="0">';
			echo '<br><input name="see" type="submit" value="�������� ������"></form></div><script>init();</script>';
		}
		else
		{
      $user_name = mysql_real_escape_string($_POST['user_name']);
			$sel = myquery("SELECT user_id FROM game_users WHERE name='".$user_name."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT user_id FROM game_users_archive WHERE name='".$user_name."';");
			if (mysql_num_rows($sel)>0 and isset($_POST['kol']) and is_numeric($_POST['kol']) and $_POST['kol']>0 and isset($_POST['max_uselife']) and is_numeric($_POST['max_uselife']) and $_POST['max_uselife'] >=0)
			{
				list($user_id_add)=mysql_fetch_array($sel);
				$Item = new Item();
				$ar = $Item->add_user($add_user,$user_id_add,0,0,0,$_POST['kol'],$_POST['max_uselife']);				
				if ($ar[0]>0)
				{
					echo '<hr><center>�������';
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
					 VALUES (
					 '".$char['name']."',
					 '������� ������� <b>".$Item->getFact('name')."</b> ������ <b>".$user_name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
							 or die(mysql_error());
					echo '<meta http-equiv="refresh" content="3;url=admin.php?option=items&opt=main">';
				}
			}
			else
				echo '���-�� ������� �������!';
		}
	}

	if(isset($_GET['edit']) and $adm['items'] == 2)
	{
    $edit = (int) $_GET['edit'];
		$usr=myquery("select * from game_items_factsheet where id=$edit");
		if (mysql_num_rows($usr))
		{
			$it=mysql_fetch_array($usr);
			if (!isset($_POST['save']))
			{
				echo'
				<script language="JavaScript" type="text/javascript">
				function count1()
				{
					document.form1.need.value=
					Number(document.form1.ostr1.value)+
					Number(document.form1.ontl1.value)+
					Number(document.form1.opie1.value)+
					Number(document.form1.ovit1.value)+
					Number(document.form1.odex1.value)+
					Number(document.form1.ospd1.value)+
					Number(document.form1.olucky1.value);
				}
				function count2()
				{
					document.form1.plus.value=
					Number(document.form1.dstr1.value)+
					Number(document.form1.dntl1.value)+
					Number(document.form1.dpie1.value)+
					Number(document.form1.dvit1.value)+
					Number(document.form1.ddex1.value)+
					Number(document.form1.dspd1.value)+
					Number(document.form1.dlucky1.value)+
					((Number(document.form1.hp_p1.value)+
					  Number(document.form1.mp_p1.value)+
					  Number(document.form1.stm_p1.value)+
					  Number(document.form1.pr_p1.value)
					 )/15
					);
				}
				</script>
				<body OnLoad="count1();count2();">
				<center><form action="" name="form1" method="post">
				<table border="0" width="100%">
				<tr><td align="right">��������:</td><td><input name="name1" value="'.$it['name'].'" type="text" size="40"></td></tr>';

        if ($it['type']!=23)
        {
          if($it['type']==1 OR $it['type']==19 OR $it['type']==21) echo'<tr><td align="right">����:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5">&plusmn;<input name="deviation1" value="'.$it['deviation'].'" type="text" size="3"></td></tr>';
          if($it['type']==3) echo'<tr><td align="right">���-�� �������:</td><td><input name="item_uselife1" value="'.$it['item_uselife'].'" type="text" size="5"></td></tr>';

          if($it['type']==4) echo'<tr><td align="right">������:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5"></td></tr>';

          if($it['type']==3)
          {
            echo '<tr><td align="right">�������� ���������</td><td><select name="sv1"><option'; if ($it['sv']=='�����') echo ' selected'; echo'>�����</option><option'; if ($it['sv']=='������') echo ' selected'; echo'>������</option><option'; if ($it['sv']=='�������') echo ' selected'; echo'>�������</option></select></td></tr>';
            echo '<tr><td align="right">����, ������ ��� �������:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5">&plusmn;<input name="deviation1" value="'.$it['deviation'].'" type="text" size="3"></td></tr>';
          }
          if($it['type']==1 or $it['type']==3 or $it['type']==4 or $it['type']==18 or $it['type']==19 or $it['type']==21) echo'<tr><td align="right">��� ������</td><td><input name="mode1" value="'.stripslashes($it['mode']).'" type="text" size="40"></td></tr>';
          echo'<tr><td align="right">���:</td><td><input name="weight1" value="'.$it['weight'].'" type="text" size="4"></td></tr>
          <tr><td align="right">��������:</td><td><input name="redkost" value="'.$it['redkost'].'" type="text" size="4" maxsize="2"></td></tr>';
        }
				echo '<tr><td align="right">�������� ��������: (���� �����)</td><td><textarea name="curse1" cols="40" rows="15">'.stripslashes($it['curse']).'</textarea></td></tr>';
				echo'<tr><td align="right">����� �������:</td><td><input name="imgg1" value="'.$it['img'].'" type="text" size="40">&nbsp;&nbsp;<img src="http://'.img_domain.'/item/'.$it['img'].'.gif" align="middle"></td></tr>';
				echo'<tr><td align="right">����� �������� �������:</td><td><input name="imgbig1" value="'.$it['imgbig'].'" type="text" size="40"></td></tr>';
        if ($it['type']!=23)
        {
          echo'<tr><td align="right">&nbsp;</td><td></td></tr>
          <tr><td align="right">������� ����:</td><td><input name="item_cost1" value="'.$it['item_cost'].'" type="text" size="8"> ����� (��� ������� ���������)</td></tr>';
          if ($it['type']==3)
          {
            echo'<tr><td align="right">��������� �������:</td><td><input name="quantity1" value="'.$it['quantity'].'" type="text" size="8">% �� ������� ����</td></tr>';             
            echo'<tr><td align="right">����� ����� ���������</td><td><input name="cooldown1" value="'.$it['cooldown'].'" type="text" size="8"> ������</td></tr>';
          }
          if($it['type']==1 OR $it['type']==18 OR $it['type']==19)
          {
            echo'<tr><td align=right>����� ������:</td><td><select name="type_weapon1"><option value="0"'; if($it['type_weapon']==0) {echo' selected ';}; echo'>��� ������</option>';
            echo'<option value="1"'; if($it['type_weapon']==1) {echo' selected ';}; echo'>��������</option>';
            echo'<option value="2"'; if($it['type_weapon']==2) {echo' selected ';}; echo'>����������</option>';
            echo'<option value="3"'; if($it['type_weapon']==3) {echo' selected ';}; echo'>�������</option>';
            echo'<option value="4"'; if($it['type_weapon']==4) {echo' selected ';}; echo'>��������</option>';
            echo'<option value="5"'; if($it['type_weapon']==5) {echo' selected ';}; echo'>�������</option>';
            echo'<option value="6"'; if($it['type_weapon']==6) {echo' selected ';}; echo'>�����������</option>';
            echo'</select></td></tr>';
            echo '<tr><td align=right>������� ������� ������:</td><td><input name="type_weapon_need1" value="'.$it['type_weapon_need'].'" type="text" size="5"></td></tr>
            <tr><td align=right>��������� ������</td><td><input name="in_two_hands" type="checkbox" value="1"'; if($it['in_two_hands']==1) echo' checked'; echo'></td></tr>';
          }
        }
				
				if ($it['type']==21)
				{
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$it['quantity'].""),0,0);
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>������������ � ���������</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script>';
				}
				
				if ($it['type']!=20 and $it['type']!=23)
				{
					echo '<tr><td align="right">&nbsp;</td><td></td></tr>

					<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>������� ��������������:</b></font></td><td>
					<tr><td align="right">�������: </td><td><input name="oclevel1" value="'.$it['oclevel'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">����: </td><td><input name="ostr1" value="'.$it['ostr'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">���������: </td><td><input name="ontl1" value="'.$it['ontl'].'" type="text" size="3" onChange="count1()"></td></tr><br>
					<tr><td align="right">��������: </td><td><input name="opie1" value="'.$it['opie'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">������: </td><td><input name="ovit1" value="'.$it['ovit'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">������������: </td><td><input name="odex1" value="'.$it['odex'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">��������: </td><td><input name="ospd1" value="'.$it['ospd'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">�����: </td><td><input name="olucky1" value="'.$it['olucky'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>
					<tr><td align="right">����� ����.�������������</td><td><input name="need" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
        }
                
        if ($it['type']!=20)
        {
					echo '<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>��������� ��������������:</b></font></td><td>
					<tr><td align="right">����: </td><td><input name="dstr1" value="'.$it['dstr'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">���������: </td><td><input name="dntl1" value="'.$it['dntl'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">��������: </td><td><input name="dpie1" value="'.$it['dpie'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">������: </td><td><input name="dvit1" value="'.$it['dvit'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">������������: </td><td><input name="ddex1" value="'.$it['ddex'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">��������: </td><td><input name="dspd1" value="'.$it['dspd'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">�����: </td><td><input name="dlucky1" value="'.$it['dlucky'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';

					echo'<tr bgcolor=000000><td align="right">�������� ������ ���� � �������:</td>
					<td>
					<table border=0>
					<tr><td><input name="hp_p1" value="'.$it['hp_p'].'" type="text" size="3" onChange="count2()"></td><td>�����</td></tr>
					<tr><td><input name="mp_p1" value="'.$it['mp_p'].'" type="text" size="3" onChange="count2()"><td>����</td></td></tr>
					<tr><td><input name="stm_p1" value="'.$it['stm_p'].'" type="text" size="3" onChange="count2()"></td><td>�������</td></tr>
					<tr><td><input name="pr_p1" value="'.$it['pr_p'].'" type="text" size="3" onChange="count2()"></td><td>�����</td></tr>
					</table>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>
					<tr bgcolor=000000><td align="right">��������� �������� �����:</td>
					<td>
					��: <input name="cc_p1" value="'.$it['cc_p'].'" type="text" size="2"><br>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';
                

					if($it['type']==2 OR $it['type']==5 OR $it['type']==6 OR $it['type']==8)
					{
						echo'<tr bgcolor=000000><td align="right" width="50%">��� �����<br /><br />(������ �������� �����, ������ �������� ���� � ����, ���� �������� ������, ���� �������� ���)</td>
						<td>
						&nbsp;<input name="def_type1" value="0" type="radio"'; if ($it['def_type']==0) echo ' checked'; echo'>  ������<br>
						&nbsp;<input name="def_type1" value="1" type="radio"'; if ($it['def_type']==1) echo ' checked'; echo'>  ��������<br>
						&nbsp;<input name="def_type1" value="2" type="radio"'; if ($it['def_type']==2) echo ' checked'; echo'>  ���������� (��������)<br>
						&nbsp;<input name="def_type1" value="3" type="radio"'; if ($it['def_type']==3) echo ' checked'; echo'>  ���� (������������)<br>
						<br>
						�������� "����������" ������: <input name="def_index1" value="'.$it['def_index'].'" type="text" size="4" maxsize="3"><br />
						�������� "����������" ������: <input name="magic_def_index1" value="'.$it['magic_def_index'].'" type="text" size="4" maxsize="3">
						</td>
						</tr>';
					}

					echo'<tr><td align="right">����� �����.�������������</td><td><input name="plus" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
          if ($it['type']==23)
          {
            ?>
            <script type="text/javascript">
            /* URL to the PHP page called for receiving suggestions for a keyword*/
            var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
            var startSearch = 2;
            </script>
            <?
            
            //echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>�������� ������� � ��������?</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>���-�� ����������� �����</td><td><input name="quantity1"  type="text" size="5" value="'.$it['quantity'].'">';
            echo '
            <script type="text/javascript">
            function sh_hd_schema()
            {
              el = document.getElementById("schema");
              if (el.style.display=="none")
              {
                el.style.display="block";
              }
              else
              {
                el.style.display="none";
              }
            }
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
            function refresh_schema()
            {
              if(AjaxRequest)
              {
                try
                {
                  if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
                  {
                    URL = "./ajax/admin/item_complect.php?read='.$it['id'].'";
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
                  setTimeout("refresh_schema();", 50);
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
                         el = document.getElementById("schema");
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
            
            function save_schema(ids)
            {
              if(AjaxRequestWork)
              {
                try
                {
                  if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
                  {
                    if (ids==\'new\')
                    {
                      item = document.getElementById("item_id").value;
                    }
                    else
                    {
                      item = document.getElementById("item_id_"+ids).value;
                    }
                    URL = "./ajax/admin/item_complect.php?read='.$it['id'].'&save="+item;
                    AjaxRequestWork.open("GET", URL, true);
                    AjaxRequestWork.onreadystatechange = process_save_delete;
                    AjaxRequestWork.send(null);
                  }
                }
                catch(e)
                {
                }
              }
              else
              {
                  AjaxRequestWork = createXmlHttpRequestObject();
              }
            }
            function delete_schema(ids)
            {
              if(AjaxRequestWork)
              {
                try
                {
                  if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
                  {
                    URL = "./ajax/admin/item_complect.php?read='.$it['id'].'&delete="+ids;
                    AjaxRequestWork.open("GET", URL, true);
                    AjaxRequestWork.onreadystatechange = process_save_delete;
                    AjaxRequestWork.send(null);
                  }
                }
                catch(e)
                {
                }
              }
              else
              {
                  AjaxRequestWork = createXmlHttpRequestObject();
              }
            }
            function process_save_delete()
            {
              try
              {
                if (AjaxRequestWork.readyState == 4)
                {
                  if (AjaxRequestWork.status == 200)
                  {
                    try
                    {
                     if (AjaxRequestWork.responseText!=\'ok\')
                     {
                        //alert(AjaxRequestWork.responseText)
                     }
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
              refresh_schema();
            }

            var AjaxRequest = createXmlHttpRequestObject();
            var AjaxRequestWork = createXmlHttpRequestObject();
            refresh_schema();
            </script>
            <tr style="height:50px;"><td style="border:2px gold groove;" colspan=2 align="center"><span style="color:red;font-weight:900;" onClick="sh_hd_schema()";>�������/������������� ������ ��������� ���������</span><div id="schema" style="display:none;">
            </div></td></tr>';
          }
				}
				else
				{
					echo '<tr><td align="right">������� �����</td><td>
					<select name="variant">
					<option value="1"'; if ($it['oclevel']==1) echo ' selected'; echo'>����� ������� ������</option>
					<option value="2"'; if ($it['oclevel']==2) echo ' selected'; echo'>����� ������� ������</option>
					<option value="3"'; if ($it['oclevel']==3) echo ' selected'; echo'>����� �������� ������</option>
					<option value="4"'; if ($it['oclevel']==4) echo ' selected'; echo'>����� ���������� ������</option>
					<option value="5"'; if ($it['oclevel']==5) echo ' selected'; echo'>����� ������ ������</option>
					</select>
					<table border=1 cellspacing=2>
					<tr><td valign="middle">����� ������� ������</td><td>������� ��� �������������:<br />����� "���������" - 0<br />������� ������ - 8<br />��������� ����� �� ������������ ���� - 120 ���.</td></tr>
					<tr><td valign="middle">����� ������� ������</td><td>������� ��� �������������:<br />����� "���������" - 55<br />������� ������ - 12<br />��������� ����� �� ������������ ���� - 180 ���.</td></tr>
					<tr><td valign="middle">����� �������� ������</td><td>������� ��� �������������:<br />����� "���������" - 85<br />������� ������ - 15<br />��������� ����� �� ������������ ���� - 240 ���.</td></tr>
					<tr><td valign="middle">����� ���������� ������</td><td>������� ��� �������������:<br />����� "���������" - 115<br />������� ������ - 19<br />��������� ����� �� ������������ ���� - 300 ���.</td></tr>
					<tr><td valign="middle">����� ������ ������</td><td>������� ��� �������������:<br />����� "���������" - 145<br />������� ������ - 22<br />��������� ����� �� ������������ ���� - 420 ���.</td></tr>
					</table>
					</td></tr>';
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$it['indx'].""),0,0);
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>����� ��� ��������</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>���-�� ����������� �����</td><td><input name="quantity1"  type="text" size="5" value="'.$it['quantity'].'">';
					echo '
					<script type="text/javascript">
					function sh_hd_schema()
					{
						el = document.getElementById("schema");
						if (el.style.display=="none")
						{
							el.style.display="block";
						}
						else
						{
							el.style.display="none";
						}
					}
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
					function refresh_schema()
					{
						if(AjaxRequest)
						{
							try
							{
								if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
								{
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'";
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
							setTimeout("refresh_schema();", 50);
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
										 el = document.getElementById("schema");
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
					
					function save_schema(ids)
					{
						if(AjaxRequestWork)
						{
							try
							{
								if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
								{
									if (ids==\'new\')
									{
										res = document.getElementById("res_id").value;
										col = document.getElementById("new_col").value;
									}
									else
									{
										res = document.getElementById("res_id_"+ids).value;
										col = document.getElementById("col_"+ids).value;
									}
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'&save="+res+"&col="+col;
									AjaxRequestWork.open("GET", URL, true);
									AjaxRequestWork.onreadystatechange = process_save_delete;
									AjaxRequestWork.send(null);
								}
							}
							catch(e)
							{
							}
						}
						else
						{
							AjaxRequestWork = createXmlHttpRequestObject();
						}
					}
					function delete_schema(ids)
					{
						if(AjaxRequestWork)
						{
							try
							{
								if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
								{
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'&delete="+ids;
									AjaxRequestWork.open("GET", URL, true);
									AjaxRequestWork.onreadystatechange = process_save_delete;
									AjaxRequestWork.send(null);
								}
							}
							catch(e)
							{
							}
						}
						else
						{
							AjaxRequestWork = createXmlHttpRequestObject();
						}
					}
					function process_save_delete()
					{
						try
						{
							if (AjaxRequestWork.readyState == 4)
							{
								if (AjaxRequestWork.status == 200)
								{
									try
									{
										 if (AjaxRequestWork.responseText!=\'ok\')
										 {
											//alert(AjaxRequestWork.responseText)
										 }
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
						refresh_schema();
					}

					var AjaxRequest = createXmlHttpRequestObject();
					var AjaxRequestWork = createXmlHttpRequestObject();
					refresh_schema();
					</script>
					<tr style="height:50px;"><td style="border:2px gold groove;" colspan=2 align="center"><span style="color:red;font-weight:900;" onClick="sh_hd_schema()";>�������/������������� ����� �������� ��������</span><div id="schema" style="display:none;">
					</div></td></tr>';
				}
				
				echo'<tr><td align="right">������ ��� ����:</td><td><select name="race1"><option value=0></option>';
				$res=myquery("select id,name from game_har where disable=0");
				while($option=mysql_fetch_array($res))
				{
					echo "<option value=".$option['id']."";
					if ($it['race']==$option["id"]) echo ' selected';
					echo ">".$option["name"]."</option>";
				}
				echo'</select></td></tr>';
				
				echo'<tr><td align="right">��������:</td><td><select name="torg[]" size=10 multiple><option value="0">�� ���������</option>';
				$sel3=myquery("select game_shop.*,game_maps.name AS map_name from game_shop,game_maps WHERE game_maps.id=game_shop.map order by binary game_maps.name,game_shop.pos_x,game_shop.pos_y");
				while ($shop=mysql_fetch_array($sel3))
				{
					$f = 1;
					switch($it['type'])
					{
						case 6:  { if ($shop['shlem']!='1') $f=0;}; break;
						case 1:  { if ($shop['oruj']!='1') $f=0;}; break;
						case 5:  { if ($shop['dosp']!='1') $f=0;}; break;
						case 4:  { if ($shop['shit']!='1') $f=0;}; break;
						case 8:  { if ($shop['pojas']!='1') $f=0;}; break;
						case 7:  { if ($shop['mag']!='1') $f=0;}; break;
						case 2:  { if ($shop['ring']!='1') $f=0;}; break;
						case 3:  { if ($shop['artef']!='1') $f=0;}; break;
						case 12: { if ($shop['svitki']!='1') $f=0;}; break;
						case 13: { if ($shop['eliksir']!='1') $f=0;}; break;
						case 20: { if ($shop['schema']!='1') $f=0;}; break;
						case 18: { if ($shop['luk']!='1') $f=0;}; break;
						case 24: { if ($shop['instrument']!='1') $f=0;}; break;
						case 97: { if ($shop['others']!='1') $f=0;}; break;
						default: {$f=0;}; break;
					}
					if ($f==1)
					{
						echo '<option value='.$shop['id'];
						$shop_name = $shop['name'].'  -  '.$shop['map_name'].' '.$shop['pos_x'].' '.$shop['pos_y'];
						$check = myquery("SELECT * FROM game_shop_items WHERE shop_id='".$shop['id']."' AND items_id='$edit'");
						if (mysql_num_rows($check)) echo ' selected';
						/*
						if ($shop['shlem']=='1') $shop_name.=' | �����';
						if ($shop['oruj']=='1') $shop_name.=' | ������';
						if ($shop['dosp']=='1') $shop_name.=' | �������';
						if ($shop['shit']=='1') $shop_name.=' | ����';
						if ($shop['pojas']=='1') $shop_name.=' | �����';
						if ($shop['mag']=='1') $shop_name.=' | �����';
						if ($shop['ring']=='1') $shop_name.=' | ������';
						if ($shop['artef']=='1') $shop_name.=' | ���������';
						if ($shop['svitki']=='1') $shop_name.=' | ������';
						if ($shop['eliksir']=='1') $shop_name.=' | ��������"';
						if ($shop['schema']=='1') $shop_name.=' | ����� ���������';
						if ($shop['luk']=='1') $shop_name.=' | ����';
						*/
						echo '>'.$shop_name.'</option>';
					}
				}
				echo'</select></td></tr>';

				$sell=mysql_result(myquery("select count(*) from game_items where item_id='".$it['id']."' AND priznak=0"),0,0);

				echo'<tr><td colspan=2><font color=ff0000><b>������� ����� ������������� ������� � ����! (��������� � '.$sell.' �������!!!)</b></font></td></tr>
				<tr><td align=right>"������" �������</td><td><select name="personal">
			    <option value="0"'; if ($it['personal']=='0') echo ' selected'; echo ' >������� �������</option>
			    <option value="1"'; if ($it['personal']=='1') echo ' selected'; echo ' >���������� ������ ��� ���������</option>
			    <option value="2"'; if ($it['personal']=='2') echo ' selected'; echo ' >���������� ������ ��� ��������</option>
			    </select></td></tr>
				<tr><td align=right>������� ����� �����</td><td><input name="can_up" type="checkbox" value="1"'; if($it['can_up']==1) echo' checked'; echo'></td></tr>
				<tr><td align=right>������� ����� ������������</td><td><input name="can_use" type="checkbox" value="1"'; if($it['can_use']==1) echo' checked'; echo'></td></tr>
				<tr><td align=right>� �������� ����������� �������������</td><td><input name="breakdown" type="checkbox" value="1"'; if($it['breakdown']==1) echo' checked'; echo'></td></tr>
				<tr><td align="right">������������� ��������</td><td><input name="item_uselife_max" value="'.$it['item_uselife_max'].'" type="text" size="5" maxsize="5"></td></tr>
                <tr><td align=right>���������� ��������� ����. � 1 ������ (0 - ��� ������)</td><td><input name="kol_per_user1" value="'.$it['kol_per_user'].'" type="text" size="4" maxsize="3"></td></tr>                     
				<tr><td align=right>����� ��� ������������</td><td>
				<select name="view">
				<option value="0"'; if ($it['view']=='0') echo ' selected'; echo '>���������� ������� ����������</option>
				<option value="1"'; if ($it['view']=='1') echo ' selected'; echo '>���������� ������ ����������</option>
				<option value="2"'; if ($it['view']=='2') echo ' selected'; echo '>�� ����������</option>
				</select>
				</td></tr>
				<tr><td align=right>�������������� ��� �����:</td><td>
				<select name="clan_id1">
				<option value="0"'; if ($it['clan_id']==0) echo ' selected'; echo '>��� �������� � �����</option>';
				$sel = myquery("SELECT clan_id,nazv FROM game_clans WHERE raz='0' ORDER BY clan_id");
				while ($cl = mysql_fetch_array($sel))
				{
					echo '<option value="'.$cl['clan_id'].'"'; if ($it['clan_id']==$cl['clan_id']) echo ' selected'; echo '>'.$cl['nazv'].'</option>'; 
				}
				echo '</select>
				</td></tr>
				<tr><td align="right">����� ����� �������� � �������� (0 - ����������)</td><td><input name="life_time" value="'.$it['life_time'].'" type="text" size="10" maxsize="10"></td></tr>				     
				<tr><td align="right">��� �������� (0 - ���� ���)</td><td><input name="set_id" value="'.$it['set_id'].'" type="text" size="10" maxsize="10"></td></tr>				     
				<tr><td align="right">&nbsp;</td><td></td></tr>
				<input name="edit" type="hidden" value="'.$edit.'">
				<tr><td align="right"><input name="save" type="submit" value="���������"></td>';
				echo'</tr><input name="save" type="hidden" value="">
				</table>
				</form>';
				echo '<a href="?opt=main&option=search&item_name='.$it['name'].'">������ ����� �� ��������</a>';
			}
			else
			{
				$da = getdate();

						$cur_item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$edit"));
						echo ('<br><br><center>�������: <font color=ff0000 size=2 face=verdana><b>'.$cur_item['name'].'<b></font> �������!');

				if (!isset($_POST['personal']))          $pers              = 0;   else $pers              = (int)$_POST['personal'];
				if (!isset($_POST['can_up']))            $can_up1           = 0;   else $can_up1           = 1;
				if (!isset($_POST['can_use']))           $can_use1          = 0;   else $can_use1          = 1;
				if (!isset($_POST['breakdown']))         $break             = 0;   else $break             = 1;
				if (!isset($_POST['in_two_hands']))      $in_two            = 0;   else $in_two            = 1;
				if (!isset($_POST['indx1']))             $indx1             = 0;   else $indx1             = (int)$_POST['indx1'];
				if (!isset($_POST['deviation1']))        $deviation1        = 0;   else $deviation1        = (int)$_POST['deviation1'];
				if (!isset($_POST['kol_per_user1']))     $kol_per_user1     = 0;   else $kol_per_user1     = (int)$_POST['kol_per_user1'];
				if (!isset($_POST['ostr1']))             $ostr1             = 0;   else $ostr1             = (int)$_POST['ostr1'];
				if (!isset($_POST['ontl1']))             $ontl1             = 0;   else $ontl1             = (int)$_POST['ontl1'];
				if (!isset($_POST['opie1']))             $opie1             = 0;   else $opie1             = (int)$_POST['opie1'];
				if (!isset($_POST['ovit1']))             $ovit1             = 0;   else $ovit1             = (int)$_POST['ovit1'];
				if (!isset($_POST['odex1']))             $odex1             = 0;   else $odex1             = (int)$_POST['odex1'];
				if (!isset($_POST['ospd1']))             $ospd1             = 0;   else $ospd1             = (int)$_POST['ospd1'];
				if (!isset($_POST['olucky1']))           $olucky1           = 0;   else $olucky1           = (int)$_POST['olucky1'];
				if (!isset($_POST['item_uselife1']))     $item_uselife1     = 100; else $item_uselife1     = (int)$_POST['item_uselife1'];
				if (!isset($_POST['oclevel1']))          $oclevel1          = 0;   else $oclevel1          = (int)$_POST['oclevel1'];
				if (!isset($_POST['dstr1']))             $dstr1             = 0;   else $dstr1             = (int)$_POST['dstr1'];
				if (!isset($_POST['dntl1']))             $dntl1             = 0;   else $dntl1             = (int)$_POST['dntl1'];
				if (!isset($_POST['dpie1']))             $dpie1             = 0;   else $dpie1             = (int)$_POST['dpie1'];
				if (!isset($_POST['dvit1']))             $dvit1             = 0;   else $dvit1             = (int)$_POST['dvit1'];
				if (!isset($_POST['ddex1']))             $ddex1             = 0;   else $ddex1             = (int)$_POST['ddex1'];
				if (!isset($_POST['dspd1']))             $dspd1             = 0;   else $dspd1             = (int)$_POST['dspd1'];
				if (!isset($_POST['dlucky1']))           $dlucky1           = 0;   else $dlucky1           = (int)$_POST['dlucky1'];
				if (!isset($_POST['sv1']))               $sv1               = 0;   else $sv1               = (int)$_POST['sv1'];
				if (!isset($_POST['race1']))             $race1             = 0;   else $race1             = (int)$_POST['race1'];
				if (!isset($_POST['hp_p1']))             $hp_p1             = 0;   else $hp_p1             = (int)$_POST['hp_p1'];
				if (!isset($_POST['mp_p1']))             $mp_p1             = 0;   else $mp_p1             = (int)$_POST['mp_p1'];
				if (!isset($_POST['stm_p1']))            $stm_p1            = 0;   else $stm_p1            = (int)$_POST['stm_p1'];
				if (!isset($_POST['pr_p1']))             $pr_p1             = 0;   else $pr_p1             = (int)$_POST['pr_p1'];
				if (!isset($_POST['cc_p1']))             $cc_p1             = 0;   else $cc_p1             = (int)$_POST['cc_p1'];
				if (!isset($_POST['type_weapon1']))      $type_weapon1      = 0;   else $type_weapon1      = (int)$_POST['type_weapon1'];
				if (!isset($_POST['type_weapon_need1'])) $type_weapon_need1 = 0;   else $type_weapon_need1 = (int)$_POST['type_weapon_need1'];
				if (!isset($_POST['def_type1']))         $def_type1         = 0;   else $def_type1         = (int)$_POST['def_type1'];
				if (!isset($_POST['def_index1']))        $def_index1        = 0;   else $def_index1        = (int)$_POST['def_index1'];
				if (!isset($_POST['magic_def_index1']))  $magic_def_index1  = 0;   else $magic_def_index1  = (int)$_POST['magic_def_index1'];
				if (!isset($_POST['item_uselife_max']))  $item_uselife_max  = 0;   else $item_uselife_max  = (int)$_POST['item_uselife_max'];
				if (!isset($_POST['in_two']))            $in_two            = 0;   else $in_two            = (int)$_POST['in_two'];
				if (!isset($_POST['quantity1']))         $quantity1         = 0;   else $quantity1         = (int)$_POST['quantity1'];
				if (!isset($_POST['cooldown1']))         $cooldown1         = 0;   else $cooldown1         = (int)$_POST['cooldown1'];
				if (!isset($_POST['weight1']))           $weight1           = 0;   else $weight1           = (int)$_POST['weight1'];
				if (!isset($_POST['item_cost1']))        $item_cost1        = 0;   else $item_cost1        = (int)$_POST['item_cost1'];
				if (!isset($_POST['life_time']))         $life_time         = 0;   else $life_time         = (int)$_POST['life_time'];
				if (!isset($_POST['set_id']))            $set_id            = 0;   else $set_id            = (int)$_POST['set_id'];
				if (!isset($_POST['view']))              $view              = 0;   else $view              = (int)$_POST['view'];
				if (!isset($_POST['clan_id1']))          $clan_id1          = 0;   else $clan_id1          = (int)$_POST['clan_id1'];
				if (!isset($_POST['imgbig1']))           $imgbig1           = '';  else $imgbig1           = mysql_real_escape_string($_POST['imgbig1']);
				if (!isset($_POST['redkost']))           $redkost           = '';  else $redkost           = mysql_real_escape_string($_POST['redkost']);
				if (!isset($_POST['mode1']))             $mode1             = '';  else $mode1             = mysql_real_escape_string($_POST['mode1']);
				if (!isset($_POST['imgg1']))             $imgg1             = '';  else $imgg1             = mysql_real_escape_string($_POST['imgg1']);
				if (!isset($_POST['curse1']))            $curse1            = '';  else $curse1            = mysql_real_escape_string($_POST['curse1']);
				if (!isset($_POST['name1']))             $name1             = '';  else $name1             = mysql_real_escape_string($_POST['name1']);

				if ($it['type']==20)
				{
				  $oclevel1 = $variant;
				  $item_id = 0;
				  $selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
				  if (mysql_num_rows($selitemid)>0)
				  {
					$item_id=mysqlresult($selitemid,0,0);
				  }
				  $indx1 = $item_id;
				}

				if ($it['type']==21)
				{
				  $item_id = 0;
				  $selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
				  if (mysql_num_rows($selitemid)>0)
				  {
					$item_id=mysqlresult($selitemid,0,0);
				  }
				  $quantity1 = $item_id;
				}

				$update=myquery("update game_items_factsheet set
						 name='$name1',indx='$indx1',deviation='$deviation1',mode='".$mode1."',weight='$weight1',curse='".$curse1."',img='$imgg1',
						 item_uselife='$item_uselife1',item_cost='$item_cost1',ostr='$ostr1',ontl='$ontl1',opie='$opie1',ovit='$ovit1',odex='$odex1',
						 ospd='$ospd1',olucky='$olucky1',oclevel='$oclevel1',dstr='$dstr1',dntl='$dntl1',dpie='$dpie1',dvit='$dvit1',ddex='$ddex1',
						 dspd='$dspd1',dlucky='$dlucky1',sv='$sv1',race='$race1',view='$view',redkost='$redkost',hp_p='$hp_p1',mp_p='$mp_p1',
						 stm_p='$stm_p1',pr_p='$pr_p1',cc_p='$cc_p1',personal='$pers',quantity='$quantity1',cooldown='$cooldown1',
						 type_weapon='$type_weapon1',type_weapon_need='$type_weapon_need1',def_type='$def_type1',def_index='$def_index1',
						 magic_def_index='$magic_def_index1',breakdown='$break',item_uselife_max='$item_uselife_max',in_two_hands='$in_two',
						 can_up='$can_up1',can_use='$can_use1',clan_id='$clan_id1',kol_per_user='$kol_per_user1',imgbig='$imgbig1', life_time='$life_time',
						 set_id='$set_id' where id=$edit");


				myquery("DELETE FROM game_shop_items WHERE items_id = $edit");
				if (isset($_POST["torg"]))
				{
					for ($i=0; $i<count($_POST["torg"]); $i++)
					{
						if ($_POST["torg"][$i]>0)
							myquery("INSERT INTO game_shop_items (shop_id,items_id) VALUES (".$_POST["torg"][$i].",$edit)");
					}
				}

				//������� ����.������ ��������� ��������
				$delta_item_uselife_max = $cur_item['item_uselife_max']-$item_uselife_max;
				myquery("UPDATE game_items SET item_uselife_max=GREATEST(1,item_uselife_max-$delta_item_uselife_max) WHERE item_id=$edit");

				//������� ����� ����� ���������
				if ($cur_item['life_time']<>$life_time)
				{
					if ($life_time == 0) $dead_time = 0;
					elseif ($cur_item['life_time'] == 0) $dead_time = time() + $life_time;
					else $dead_time = "dead_time +".($life_time - $cur_item['life_time']);
					myquery("UPDATE game_items SET dead_time=".$dead_time." WHERE item_id=".$edit."");
					//������� ����� ������ �������� � ���
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				   VALUES (
				   '".$char['name']."',
				   '������� ����� ����� �������� <b>".$name1."</b>',
				   '".time()."',
				   '".$da['mday']."',
				   '".$da['mon']."',
				   '".$da['year']."')")
						   or die(mysql_error());
				}
				
				// ������� ������ ��������
				if ($cur_item['personal']<>$pers)
				{
					if ($pers == 0)
					{
						myquery("UPDATE game_items SET personal = ".$pers." WHERE item_id = ".$edit."");
					}
					elseif ($pers == 1)
					{
						myquery("UPDATE game_items SET personal = user_id WHERE item_id = ".$edit."");
					}
					elseif ($pers == 2)
					{
						myquery("UPDATE game_items SET personal = user_id WHERE item_id = ".$edit." and used > 0");
					}					
				}				

				//�������� ������ ���� � ��� ������������� ������� �������������� ������
				$sel_item_on_user = myquery("SELECT user_id FROM game_items WHERE user_id>0 AND used<>0 AND item_id=$edit AND priznak=0");
				while ($item_user = mysql_fetch_array($sel_item_on_user))
				{
					if ($cur_item['weight']!=$weight1)
					{
						$razn = $weight1-$cur_item['weight'];
						myquery("UPDATE game_users SET CW=(CW + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET CW=(CW + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dstr']!=$dstr1)
					{
						$razn = $dstr1-$cur_item['dstr'];
						myquery("UPDATE game_users SET STR=(STR + $razn),STR_MAX=(STR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET STR=(STR + $razn),STR_MAX=(STR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dntl']!=$dntl1)
					{
						$razn = $dntl1-$cur_item['dntl'];
						myquery("UPDATE game_users SET NTL=(NTL + $razn),NTL_MAX=(NTL_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET NTL=(NTL + $razn),NTL_MAX=(NTL_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dpie']!=$dpie1)
					{
						$razn = $dpie1-$cur_item['dpie'];
						myquery("UPDATE game_users SET PIE=(PIE + $razn),PIE_MAX=(PIE_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET PIE=(PIE + $razn),PIE_MAX=(PIE_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dvit']!=$dvit1)
					{
						$razn = $dvit1-$cur_item['dvit'];
						myquery("UPDATE game_users SET VIT=(VIT + $razn),VIT_MAX=(VIT_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET VIT=(VIT + $razn),VIT_MAX=(VIT_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['ddex']!=$ddex1)
					{
						$razn = $ddex1-$cur_item['ddex'];
						myquery("UPDATE game_users SET DEX=(DEX + $razn),DEX_MAX=(DEX_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET DEX=(DEX + $razn),DEX_MAX=(DEX_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dspd']!=$dspd1)
					{
						$razn = $dspd1-$cur_item['dspd'];
						myquery("UPDATE game_users SET SPD=(SPD + $razn),SPD_MAX=(SPD_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET SPD=(SPD + $razn),SPD_MAX=(SPD_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dlucky']!=$dlucky1)
					{
						$razn = $dlucky1-$cur_item['dlucky'];
						myquery("UPDATE game_users SET lucky=(lucky + $razn),lucky_max=(lucky_max + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET lucky=(lucky + $razn),lucky_max=(lucky_max + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['hp_p']!=$hp_p1)
					{
						$razn = $hp_p1-$cur_item['hp_p'];
						myquery("UPDATE game_users SET HP=(HP + $razn),HP_MAX=(HP_MAX + $razn),HP_MAXX=(HP_MAXX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET HP=(HP + $razn),HP_MAX=(HP_MAX + $razn),HP_MAXX=(HP_MAXX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['mp_p']!=$mp_p1)
					{
						$razn = $mp_p1-$cur_item['mp_p'];
						myquery("UPDATE game_users SET MP=(MP + $razn),MP_MAX=(MP_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET MP=(MP + $razn),MP_MAX=(MP_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['stm_p']!=$stm_p1)
					{
						$razn = $stm_p1-$cur_item['stm_p'];
						myquery("UPDATE game_users SET STM=(STM + $razn),STM_MAX=(STM_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET STM=(STM + $razn),STM_MAX=(STM_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['pr_p']!=$pr_p1)
					{
						$razn = $pr_p1-$cur_item['pr_p'];
						myquery("UPDATE game_users SET PR=(PR + $razn),PR_MAX=(PR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET PR=(PR + $razn),PR_MAX=(PR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['cc_p']!=$cc_p1)
					{
						$razn = $cc_p1-$cur_item['cc_p'];
						myquery("UPDATE game_users SET CC=(CC + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET CC=(CC + $razn) WHERE user_id=".$item_user['user_id']."");
					}
				}

				$log=myquery("INSERT INTO game_log_adm (adm,cur_time,dei) VALUES ('".$char['name']."','".date("j.m.Y H:i")."','������� �������:  ".$cur_item['name']." (����� �������� - ".mysql_real_escape_string($name1)."');");
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
							  VALUES ('".$char['name']."','������� � ������������ ������� <b>".$name1."</b> (������ �������� - ".$cur_item['name'].")',
							 '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());

				echo '<br>�������� �������������� (������� ��� ����) � '.mysql_num_rows($sel_item_on_user).' �������<br>';
			}
		}
	}
}

if (function_exists("save_debug")) save_debug(); 
?>
</body>
</html>