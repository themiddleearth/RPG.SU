<?php
// ��������� ����������
$contents ="";
$day=date("d"); //���������� ����
if (preg_match ("/�����/i", "$message"))
        {
        $day=$day-1;
        }
if (preg_match ("/���������/i", "$message"))
        {
        $day=$day-2;
        }
$monat=date("m"); //���������� �����
$year=date("y");// ���������� ���
$yesterday="no";
$ra=mt_rand(1,10);
function webread( $url )
{
global $contents, $terminator, $ra;
// ���������� ����� ��� ���������� �������� �� ���� ��������� ��������
$anek=false;
//���������� ��� ������ ���� ���� � ����������� ������ �� ����.
$patterns[0] = "/<a href=/";
$replacements[0] = "<a target=_blank href=http://www.rpg.su/";
//��� ���������� ������ ������
$contents ="";
//������ �������� � ������� ���������
if (!($fp = @fopen($url, 'r')))
{ return false;break;}
while ($line = @fgets($fp, 10024)) {
//���� ����� ������ ��������, �� $anek=true;
if (preg_match("/<a name=$ra>/", $line))
{
$anek=true;
}
// ����� �����, $anek=false;
if (preg_match("/<\/pre>/", $line))
{
$anek=false;
$contents .="</pre>".$terminator;
}
if ($anek)
{
$line=preg_replace($patterns, $replacements, $line);
$contents .=$line;
}
}
fclose($fp);

if ($contents=="") {return FALSE;}
else {
// $contents .="</pre>";
return TRUE;
}
}
function go_yesterday()
{
global $cur, $mon, $day, $monat, $year;

if ($day==1)
{
if ($monat==1)
{
if ($year==0) $year=99;
else $year=$year-1;
if ($year<10) $year="0".$year;
$monat=12;
$day=31;
}
else
{
switch ( $monat )
{

case 5:
$day=30;
break;

case 7:
$day=30;
break;

case 10:
$day=30;
break;

case 12:
$day=30;
break;

case 3:
if (date("L")==1)
{
$day=29;
}
else
{
$day=28;
}
break;



default:
$day=31;
}
$monat=$monat-1;
if ($monat <10)
{ $monat="0".$monat;}
}
}
else
{
$day=$day-1;
}
if ($day <10)
{ $day="0".$day;}
}
// ����������, ����� �������� �� ����� ������ � �������� �� �����
switch ( $op )
{
// ����� ����� ������� �� ������ ������ ��������
default:
}
// ���������� ����� ��������
//�����
if ($yesterday=="yes")
{
$year=$y;
$day=$d;
$monat=$m;
}
$url="http://www.anekdot.ru/an/an".$year.$monat."/".$typ.$year.$monat.$day.".html";
//�������� ������� ������ ��������
$i=0;
while (!webread($url))
{
//���� ������ ��� ���, ��
// ���������� ����� �������� ��� ���������� ���
go_yesterday();

$url="http://www.anekdot.ru/an/an".$year.$monat."/".$typ.$year.$monat.$day.".html";
$i++;
if ($i>2) { $contents="<pre>�������� ���� ��� anekdot.ru �� ��������!</pre>";break;}
}
$contents = str_replace ("<pre>", "", $contents);
$contents = str_replace ("</pre>", "", $contents);
$contents = str_replace ("<a name=$ra><table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">", "", $contents);
$contents = str_replace ("<tr><td width=\"1%\">&nbsp;</td><td width=90%>", "", $contents);
$contents = str_replace (" http://censor.net.ua/", "", $contents);
$contents = str_replace ("\n", "<br>", $contents);
$contents = str_replace ("<br><br>		<br><br>", "", $contents);
$mes=$contents;
?>