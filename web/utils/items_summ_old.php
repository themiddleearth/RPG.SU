<?
$dirclass = "../class";
require('../inc/config.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

$max_level = mysql_result(myquery("SELECT oclevel FROM game_items_factsheet ORDER BY oclevel DESC LIMIT 1"),0,0);
for ($i=0;$i<=$max_level;$i=$i+8)
{
    echo '<span style="font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 16px;
	color: #000066;
	text-align: justify">�������� ��� ������ �� '.$i.' �� ������ '.($i+8).'</span><br><table border=2 bordercolor=darkgreen cellspacing=1 cellpadding=1>';
    $sel = myquery("SELECT DISTINCT(type) FROM game_items_factsheet WHERE view='1' AND redkost='' AND oclevel>=$i AND oclevel<".($i+8)."");
    while (list($type) = mysql_fetch_array($sel))
    {
        echo '<tr><td ALIGN="CENTER" VALIGN="MIDDLE" BGCOLOR="#FFFFBF" rowspan="7"><STRONG>';
        echo $type;
        echo '</STRONG></td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,dstr FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY dstr DESC LIMIT 1"));
        echo '<tr><td><b>����</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,dntl FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY dntl DESC LIMIT 1"));
        echo '<tr><td><b>���������</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,dpie FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY dpie DESC LIMIT 1"));
        echo '<tr><td><b>��������</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,dvit FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY dvit DESC LIMIT 1"));
        echo '<tr><td><b>������</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,ddex FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY ddex DESC LIMIT 1"));
        echo '<tr><td><b>������������</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
        list($name,$har) = mysql_fetch_array(myquery("SELECT name,dspd FROM game_items_factsheet WHERE type='$type' AND redkost='' AND view='1' ORDER BY dspd DESC LIMIT 1"));
        echo '<tr><td><b>��������</b></td><td>'.$name.'</td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$har.'</td></tr>';
    }
    echo '</table><br><br>';
    
    echo '<span style="font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 16px;
	color: #000066;
	text-align: justify">������������ �������������� �� ��������� ��� ������ �� 0 �� ������ '.($i+8).'</span><br><table border=2 bordercolor=darkgreen cellspacing=1 cellpadding=1>';
    
    $str = 0;
    $pie = 0;
    $spd = 0;
    $vit = 0;
    $dex = 0;
    $ntl = 0;
    $hp = 0;
    $mp = 0;
    $stm = 0;
    $seltype = myquery("SELECT DISTINCT(type) FROM game_items_factsheet WHERE view='1' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)."");
    while (list($type) = mysql_fetch_array($seltype))
    {
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY dstr DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $str+=$it['dstr'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY dpie DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $pie+=$it['dpie'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY dspd DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $spd+=$it['dspd'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY dvit DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $vit+=$it['dvit'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY ddex DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $dex+=$it['ddex'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY dntl DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $ntl+=$it['dntl'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY hp_p DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $hp+=$it['hp_p'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY mp_p DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $mp+=$it['mp_p'];
        }
        $sel = myquery("SELECT * FROM game_items_factsheet WHERE view='1' AND type='$type' AND redkost='' AND oclevel>=0 AND oclevel<".($i+8)." ORDER BY stm_p DESC LIMIT 1");
        while ($it = mysql_fetch_array($sel))
        {
            $stm+=$it['stm_p'];
        }
    }
    echo '<tr><td><b>����</b></td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$str.'</td></tr>';
    echo '<tr><td><b>���������</b></td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$ntl.'</td></tr>';
    echo '<tr><td><b>��������</b></td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$pie.'</td></tr>';
    echo '<tr><td><b>������</b></td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$vit.'</td></tr>';
    echo '<tr><td><b>������������</b></td><td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$dex.'</td></tr>';
    echo '<tr><td><b>��������</b></td<td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$spd.'</td></tr>';
    echo '<tr><td><b>HP</b></td<td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$hp.'</td></tr>';
    echo '<tr><td><b>MP</b></td<td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$mp.'</td></tr>';
    echo '<tr><td><b>STM</b></td<td ALIGN="RIGHT" VALIGN="MIDDLE" BGCOLOR="#BFFFFF">'.$stm.'</td></tr>';
    echo '</table><br><br>';
}
?>