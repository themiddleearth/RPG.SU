<?php
if (function_exists('OpenTable'))
{
  OpenTable('title');
  $check = date('d:m');
  echo '<table width=100%><tr><td align="right">';
  echo '</td><td align=left><b><font face="Tahoma" color="#FFFF00">';
  switch($check)
  {
	case '01:01':
	{
		echo '����� ��� | (1874 �.) �������� � �������� �������� �������� ���������� � ����� �������� ���������� | ������ (��������). ���� � ������ ������ ��������� � ������, �� � ���� ����� ������ �����.';
		break;
	}
	case '03:01':
	{
		echo '���� �������� ���������� ������������ ������������ ����� �.�.��������(1892�.)';
		break;
	}
	case '07:01':
	{
		echo '��������� �������� (������������) | �� ��������� ������ - ����� ������ ������� �����, �� ��������� ���� - ������ �� ����; ���� � ������� - ������ �� �����. ���� �� ��������� ���� ����� - � ������ ������.';
		break;
	}
	case '14:01':
	{
		echo '������ ����� ���';
		break;
	}
	case '25:01':
	{
		echo '�������� ���� (���� ���������) | �������� ����. ���� � ���� ���� �������� ��������� - � ������ �����, ���� ���� - � ���������� ����.';
		break;
	}
	case '01:02':
	{
		echo '������� (������). ������� �������� � ����� - ������ ������. | ����� ������ ���� ������� - ����� � ���������.';
		break;
	}
	case '14:02':
	{
		echo '����������� ���������. (3019 �. III �����) | ���� ��. ���������';
		break;
	}
	case '16:02':
	{
		echo '��������� ����� ���';
		break;
	}
	case '25:02':
	{
		echo '������ ����� � �������� ������ (3019 �. III �����). ������ ��������, ���� �������, ������ ������ (3019 �. III �����)';
		break;
	}
	case '26:02':
	{
		echo '������ ��������, ���� ��������, ���������� �������';
		break;
	}
	case '30:02':
	{
		echo '������� �������� ����� ����� ������� �� �������� (3019 �. III �����)';
		break;
	}
	case '01:03':
	{
		echo '���� �������� �������� (2931 �. III �����) | ������ �������� (120 �. IV �����)';
		break;
	}
	case '02:03':
	{
		echo '������ ����� � �������� ������ (3019 �. III �����)';
		break;
	}
	case '03:03':
	{
		echo '����� ��� ��������� (3019 �. III �����) | ���������� �������� (3019 �. III �����)';
		break;
	}
	case '08:03':
	{
		echo '������� ��������� ������������������ � ����� ����� ���� � ���.(3019 �. III �����) | ������������� ������� ����';
		break;
	}
	case '11:03':
	{
		echo '������ ����� ������� (3019 �. III �����)';
		break;
	}
	case '15:03':
	{
		echo '����������� ����� (3019 �. III �����) | ������ ����� ������� (3019 �. III �����) | ������ �������, ������ ������ (3019 �. III �����) | ������ ��������, ���������� ������� (3019 �. III �����)';
		break;
	}
	case '17:03':
	{
		echo '������ ����� � ����� (3019 �. III �����) | ������ ������, ������ �������� (3019 �. III �����) | ������ ����� ������������, ������ �������� ���� (3019 �. III �����)';
		break;
	}
	case '22:03':
	{
		echo '������ � ��������� ����� ������� (3019 �. III �����)';
		break;
	}
	case '25:03':
	{
		echo '����� ����� ������� �������� (3019 �. III �����) | ����������� ������. | ������� �����-����, ���� �������(3019 �. III �����) | ���� �������� ������ ����������, ������ ���� ������ (3021 �. III �����)';
		break;
	}
	case '27:03':
	{
		echo '������ ����� � ����� (3019 �. III �����)';
		break;
	}
	case '28:03':
	{
		echo '����� ���-�������.(3019�. 3 �����)';

		break;
	}
	case '01:04':
	{
		echo '������ ������! - ���� ����� | ������ (�������).';
		break;
	}
	case '06:04':
	{
		echo '���� �������� ���� ������.';
		break;
	}
	case '08:04':
	{
		echo '����������� �� ������������� ���� (3019 �. III �����)';
		break;
	}
	case '12:04':
	{
		echo '���� ������������';
		break;
	}
	case '15:04':
	{
		echo '����� �� ����� ���������� (2509 �. III �����) | ������� ������� � ����� (2509 �. III �����)';
		break;
	}
	case '01:05':
	{
		echo '��������� �������� (3019 �. III �����) | ������� ���� ������ � ���� ��������� (3020 �. III �����) | �������� ����� � ����� | ��� (�������). � ��� ��� ������ - �������� ������ � ��� ������������.';
		break;
	}
	case '09:05':
	{
		echo '���� ������ (������ � ��� 1941-1945 ��.)';
		break;
	}
	case '01:06':
	{
		echo '������������� ���� ������ ����� | ���� (���������).';
		break;
	}
	case '21:06':
	{
		echo '���� ������������ � �������� (���. ��������� � 20 �� 22-�)';
		break;
	}
	case '22:06':
	{
		echo '���� ������ � ������';
		break;
	}
	case '25:06':
	{
		echo '������ ������� ������� ������� ����� ����� (3019 �. III �����)';
		break;
	}
	case '01:07':
	{
		echo '������� �������� � ����� (3019 �. III �����) | ���� (��������).';
		break;
	}
	case '13:07':
	{
		echo '����� � ����������� (1944 �. III �����) | ������ ��������, ������ ������� (1944 �. III �����)';
		break;
	}
	case '15:07':
	{
		echo '������ ����� � ����������� (1944 �. III �����)';
		break;
	}
	case '19:07':
	{
		echo '������������ ����� ������� ������������ � ������(3019 �. III �����)';
		break;
	}
	case '01:08':
	{
		echo '������ (�������)';
		break;
	}
	case '07:08':
	{
		echo '������������ ����� ������� ��������� � ������ (3019 �. III �����)';
		break;
	}
	case '25:08':
	{
		echo '���������� ������ ������� (3019 �. III �����)';
		break;
	}
	case '02:09':
	{
		echo '���� ������ ��.�.�. �������� (1973)';
		break;
	}
	case '18:09':
	{
		echo '������� ��������� �� �������� (3018 �. III �����)';
		break;
	}
	case '21:09':
	{
		echo '���� ������ ���������� "�������" (1937)';
		break;
	}
	case '22:09':
	{
		echo '���� �������� ������ � ����� (2890 � 2968 ��. III �����) | ��� ������ ������������ �� ���� (61 �. IV �����)';
		break;
	}
	case '29:09':
	{
		echo '��������� ������� �������� ����������. ����� III ����� (3021 �. III �����)';
		break;
	}
	case '04:10':
	{
		echo '�������� � ������� ������ (2 �. III �����) | ������ ��������, ���� �������� (2 �. III �����)';
		break;
	}
	case '29:10':
	{
		echo '����� � ������� (3018 �. III �����)';
		break;
	}
	case '02:11':
	{
		echo '���� ���������� ���� � ��������';
		break;
	}
	case '03:11':
	{
		echo '������������ �����. ������ ��������. ����� ����� ������ (3019 �. III �����)';
		break;
	}
	case '19:11':
	{
		echo '���� ����� � ����������';
		break;
	}
	case '29:11':
	{
		echo '���� ������ ���� ������� (1971)';
		break;
	}
	case '24:12':
	{
		echo '����� ��� �� ���������� ��������� (��. ��������� � 21 �� 26-�)';
		break;
	}
  }
  echo '</font></b></td>';

  echo '<td width=270 align=right><font face="verdana, tahoma" size="1" color="FFFF00">';

  $da = getdate();
  $newyear = GetGameCalendar_Year($da['year'],$da['mon'],$da['mday']);
  $newmonth = GetGameCalendar_Month($da['year'],$da['mon'],$da['mday']);

  echo '['.date('H:i').'] ';

  if ($newmonth==1) 		echo '�������� (1 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==2) 		echo '������ (2 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==3) 		echo '������ (3 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==4) 		echo '������� (4 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==5) 		echo '������� (5 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==6) 		echo '����� (6 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==7) 		echo '������ (7 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==8) 		echo '����� (8 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==9) 		echo '�������� (9 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==10) 		echo '��������� (10 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==11) 		echo '������ (11 �����), '.$newyear.' �.IV �����';
  elseif ($newmonth==12) 		echo '������� (12 �����), '.$newyear.' �.IV �����';

  echo '</font></td></tr></table>';
  OpenTable('close');
}

if (!defined("MODULE_ID"))
{
	define("MODULE_ID","undefined");
}

if ($char['name']=='The_Elf' OR $char['name']=='blazevic' OR $char['name']=='betatester' OR $char['name']=='mrHawk')
{
	$exec_time = $MyTimer->GetTime(5);
	echo '<font size=1 face="verdana, tahoma"><center>����� ���.�������: <b><font color=ff0000>' . $exec_time . '</font></b> ���   |   ���������� ��������: <b><font color=ff0000>'.$numsql.'</font></b>   |   ����� ��������: <b><font color=ff0000>' . $time_mysql_query . '</font></b> ��� ('.round($time_mysql_query*100/$exec_time,2).'%) | Module_id: '.MODULE_ID.'</center></font>';
}

echo '</body></html>';
while (ob_get_level() > 0) {
   ob_end_flush();
}
?>