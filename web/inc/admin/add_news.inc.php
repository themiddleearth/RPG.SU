<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['news'] >= 1)
{
	include_once('style/tinyMCE/tinyMCE_header.php');

	if(empty($_POST['theme']))
	{
		echo "<form method=post>";
		echo "<input type=hidden name=option value=add_news>";
		echo "<table width=100% border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr><td>����:</td><td><input type=text name=theme size=50></td></tr>";

		echo '<tr><td valign=top>�����:</td><td>';
		?>
		<textarea id="elm1" name="elm1" rows="25" cols="80" style="width: 100%">
		</textarea>
		<?
		echo '<tr><td></td><td><input name="save" type="submit" value="��������"><input name="save" type="hidden" value=""></td></tr>';
		echo '</table>';
		echo '</form>';
	}
	else
	{
    $theme = mysql_real_escape_string($_POST['theme']);
    $value = mysql_real_escape_string($_POST['elm1']);
    $up=myquery("INSERT INTO game_news (id_user,theme,text,created,status) VALUES ('$user_id','$theme','$value','".date("j.m.Y H:i")."','0')");
    $id = mysql_insert_id();
    $da = getdate();

    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) VALUES (
    '".$char['name']."',
    '������� �������: ".$theme."',
    '".time()."',
    '".$da['mday']."',
    '".$da['mon']."',
    '".$da['year']."')
    ");

    $say = iconv("Windows-1251","UTF-8//IGNORE","���������: ��������� ����� ������� <a href=\"http://".domain_name."/news.php?id=".$id."\" target=\"_blank\">".$theme."</a><br /><br />".$value."");
    myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".$say."',".time().",-1)");
    echo "������� ���������";

		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=news">';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>