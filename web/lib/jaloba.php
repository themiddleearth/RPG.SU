<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

include_once('inc/template.inc.php');
require_once('inc/template_header.inc.php');

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="100%" height="100%">
		<fieldset style="margin-left:55px;width:650px;margin-bottom:30px;padding:15px;">
			<legend><b>����� ��� �������� ��������� ������������� ����</b></legend>
			<p align="justify">
			��������� ������, �� (������������� ����) ��������� ������� ���, ����� ���� ���� ��� ��� �������, ���������� � �������������.<br />
			�� ��� ������ ������ ��� ������ ���������� ��� � ��� � ��� ���������� �� ������ ������.<br />
			���� ��� ���-�� ��������� � ���� ��� �� �� ������ � ���-�� ����������� - �������� ��� ������ � �� �������� ��� ������, ����� ��������� ���� ���������� ��� ������ ���� ��� ������� ��� ����� ����������.<br />
			���� �� ������ �������� ���� - �� ������ �� ����� �� ����� ������� � ����� ������ ��� ����� �������� ��� �� ����!<br />
			���� ������ �� ������ �������� � ������������� ���� �����<br />
			</p>
			<form name="jaloba" method="post" action="">
			�������� ����� ������ � ������������ �����:<br />
			<textarea name="jaloba" style="width:100%;height:190px" ></textarea>
			<input type="submit" name="submit_jaloba" value="��������� ������ ������������� ����">
			</form>
			<?
			if (isset($_POST['submit_jaloba']))
			{
				$msg = mysql_real_escape_string(htmlspecialchars($_POST['jaloba']));
				myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '".$user_id."', '������ ������������� ����', '$msg','0','".time()."')");
				myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('14475', '".$user_id."', '������ ������������� ����', '$msg','0','".time()."')");
				myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('3500', '".$user_id."', '������ ������������� ����', '$msg','0','".time()."')");
				QuoteTable('open');
				echo '<br /><span style="color:red;font-weight:900;font-size:13px;">�������! ���� ��������� ���������� ������������� ����!</span><br />';
				QuoteTable('close');
			}
			?>
		</fieldset>
		</td>
		<td valign="top" width="200">
		<table border=0 width=172 cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<? include('inc/template_stats.inc.php'); ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?
if (function_exists("save_debug")) save_debug(); 

?>