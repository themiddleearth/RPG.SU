<?
$dirclass="../class";
include('../inc/config.inc.php');
include('../inc/lib.inc.php');
DbConnect();


if (function_exists("start_debug")) start_debug(); 

include('../inc/template_header.inc.php');
?>
<style type="text/css">
<!--
body {
        background-color: #313131;
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 00px;
}
body,td,th {
        color: #CCCCCC;
        font-size: 12px;
        font-family: Verdana;
}
-->
</style>
<body>
<table border="0" cellpadding="6" width=100%>
<?
$sel=myquery("select * from game_zakon order by id");
while($zak=mysql_fetch_array($sel))
{
    echo'
    <tr>
    <td width="2%" height="9" valign="top" align=center><font color=#FF0000>'.$zak['id'].'</font></td>
    <td width="24%" valign="top">'.$zak['name'].'</td>
    <td width="70%">'.$zak['text'].'</td>
    <td valign="top">';
    if ($zak['time']!='0') echo '����� ���������: <font color=#FF0000>'.$zak['time'].'</font> �����';
    echo'</td>
    </tr>
    <tr><td>&nbsp;</td></tr>';
}

if (function_exists("save_debug")) save_debug(); 

?>
<tr><td colspan=4><font color=#FF0000>��� �������� ��������� ������ ������ - ����� ��������� ������������� ������������� � 2(3,4,5....) ����</font></td></tr>
</table>
</body>
</html>	