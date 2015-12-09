<?
function show_combat_log($boy,$for_hod=0,$width="100%")
{
	$est = 0;
	$text = '<table width='.$width.' id="log_table" border=1 cellspacing=0 cellpadding=0><tbody>';
	$boy=(int)$boy;
	if ($for_hod>0)
	{
		$add_where=' AND game_combats_log_data.hod='.$for_hod;
	}
	else
	{
		$add_where='';
	}
	$sel = myquery("SELECT game_combats_log_data.* , text1.name AS name, text1.mode AS mode, text1.kuda AS kuda , text2.name AS na_kogo_name, text2.mode AS na_kogo_id , text3.name AS kto_name, text3.mode AS kto_id, sex1.sex AS sex
	FROM (
	game_combats_log_data
	)
	LEFT JOIN game_combats_log_text AS text1 ON ( text1.id = game_combats_log_data.text_id )
	LEFT JOIN game_combats_log_text AS text2 ON ( text2.id = game_combats_log_data.na_kogo )
	LEFT JOIN game_combats_log_text AS text3 ON ( text3.id = game_combats_log_data.kto )
	LEFT JOIN game_users_data AS sex1 ON ( sex1.user_id = game_combats_log_data.user_id )
	WHERE game_combats_log_data.boy =$boy AND game_combats_log_data.action<>99 $add_where
	ORDER BY game_combats_log_data.hod, game_combats_log_data.sort, game_combats_log_data.id");

	$cur_user = -1;
	$cur_hod = 0;
	while ($log=mysql_fetch_array($sel))
	{
		if (($cur_user!=$log['user_id'])OR($cur_hod!=$log['hod']))
		{
			if ($cur_user!=-1)
			{
				if ($prev_log['kto_name']=='') $prev_log['kto_name']='&nbsp;';
				$text.='<tr height="20"><td height="20" align="center" valign="center"><b>'.$prev_log['hod'].'</b></td><td height="20" align="center" valign="center"><b><font color="#80FF80">'.$prev_log['kto_name'].'</font></b></td><td height="20" valign="center" style="padding-left:5px;">'.$action.'</td></tr>';
				$est=1;
			}
			$action = '';
			$cur_user=$log['user_id'];
		}
		if ($cur_hod!=$log['hod'])
		{
			if ($cur_hod>0)
			{
				$text.='<tr style="height:5px;background-color:#000080"><td colspan="3"></td></tr>';
				$est = 1;
			}
			$cur_hod=$log['hod'];
		}
		switch ($log['action'])
		{
			case 1:
			{
				$action.= '����� ������ ���� �'.$log['hod'].'';
			}
			break;
			case 3:
			{
				$l_pol = 'male';
				$action.= '� ��� '.echo_sex('�������','��������',$l_pol).' <span style="font-weight:900;color:gold;font-style:italic;">'.$log['na_kogo_name'].'</span>';				
			}
			break;
			case 4:
			{
				$action.= '���� ���� ��� ��������...�� �� - '.echo_sex('����','�����',$log['sex']).'!';
			}
			break;
			case 5:
			{
				$action.= '������ ������� ����� ��� �� 100%';
			}
			break;
			case 6:
			{
				$action.= '����� <font color="white"><b>'.$log['na_kogo_name'].'</b></font> �� ��������� � ���� ���� ��� ��� ��� ����';
			}
			break;
			case 61:
			{
				$action.= '����� <font color="white"><b>'.$log['na_kogo_name'].'</b></font> �� ��������� � ���� ���� ��� ��� ��� �����';
			}
			break;
			case 7:
			{
				$action.= ''.echo_sex('��������','����������',$log['sex']).' �������� ���������� <<b>('.$log['name'].')</b>> (<font color=#FFBC79>'.$log['procent'].'%</font>) �� ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> ';				
				if ($log['add_hp']>0)
				{
					$action.='(+<font color=#00FF80>'.$log['add_hp'].'</font> ��.��������)';
				}
				if ($log['add_mp']>0)
				{
					if ($log['add_hp']>0)
					{
						$action.=', ';
					}
					$action.='+<font color=#00FF80>'.$log['add_mp'].'</font> ��.����';
				}
				if ($log['add_stm']>0)
				{
					if ($log['add_hp']>0 OR $log['add_mp']>0)
					{
						$action.=', ';
					}
					$action.='+<font color=#00FF80>'.$log['add_stm'].'</font> ��.�������';
				}
				$action.='. ��������� ';
				if ($log['minus_hp']>0)
				{
					$action.='-<font color=#79D3FF>'.$log['minus_hp'].'</font> ��.��������';
				}
				if ($log['minus_mp']>0)
				{
					if ($log['minus_hp']>0)
					{
						$action.=', ';
					}
					$action.='-<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
				}
				if ($log['minus_stm']>0)
				{
					if ($log['minus_hp']>0 OR $log['minus_mp']>0)
					{
						$action.=', ';
					}
					$action.='-<font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
				}
				$action.= '.';
			}
			break;
			case 8:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.����, ��.�������� ��� ��.������� ��� ������� c ������� ����������� ������ <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 9:
			{
				$action.= '��������� ������������ ���������� ����������.';
			}
			break;
			case 10:
			{
				$action.= ''.echo_sex('�����������','������������',$log['sex']).' �������� ������� <'.$log['name'].'> �� <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#FFBC79>'.$log['procent'].'%</font>). +<font color=#00FF80>'.$log['add_hp'].'</font> ��.��������.';
			}
			break;
			case 11:
			{
				$action.= '������������ ��������';
			}
			break;
			case 12:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.������� ��� ������� ����������.';
			}
			break;
			case 13:
			{
				$action.= ''.echo_sex('�����������','������������',$log['sex']).' ������� '.$log['name'].' �� <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#FFBC79>'.$log['procent'].'%</font>).   ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 14:
			{
				$action.= '������������ �������';
			}
			break;
			case 15:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.������� ��� ������������� ��������';
			}
			break;
			case 16:
			{
                $addstr = '';
                if ($log['minus_hp']==5) {$addstr='��������';$log['name']='��� ����';};
                $action.= ''.echo_sex('��������','���������',$log['sex']).' '.$addstr.' ������ <font color=ff0000><b>'.$log['mode'].'</b></font> (<font color=#FFBC79>'.$log['procent'].'%</font>) �� '.$log['name'].' ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (-<font color=#82FFFF>'.$log['add_hp'].'</font> ��.����� ��������)     ';
                if ($log['minus_mp']!=0)
                {
                    $action.= '��������� <font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
                }
                else
                {
                    $action.= '��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
                }
			}
			break;
			case 17:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.������� ��� ������ '.$log['mode'].'';
			}
			break;
			case 18:
			{
				$action.= '������������ ���!';
			}
			break;
			case 19:
			{
				$action.= ''.echo_sex('��������','����������',$log['sex']).' �������� ���������� <<b>('.$log['name'].')</b>> (<font color=#FFBC79>'.$log['procent'].'%</font>) �� ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (';
				if ($log['add_hp']!=0)
				{
					$action.='-<font color=#82FFFF>'.$log['add_hp'].'</font> ��.����� ��������';
				}
				if ($log['add_mp']!=0)
				{
					if ($log['add_hp']!=0)
					{
						$action.=', ';
					}
					$action.='-<font color=#82FFFF>'.$log['add_mp'].'</font> ��.����� ����';
				}
				if ($log['add_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0)
					{
						$action.=', ';
					}
					$action.='-<font color=#82FFFF>'.$log['add_stm'].'</font> ��.����� �������';
				}
				$action.=').   ��������� ';
				if ($log['minus_mp']!=0)
				{
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
				}
				if ($log['minus_hp']!=0)
				{
					if ($log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_hp'].'</font> ��.��������';
				}
				if ($log['minus_stm'])
				{
					if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
				}
				$action.='.';
			}
			break;
			case 20:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.����, ��.�������� ��� ��.������� ��� ������ ���������� ����������� <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 21:
			{
				$action.= '������������ ���������� ���������� ������!';
			}
			break;
			case 22:
			{
				$action.= ''.echo_sex('��������','���������',$log['sex']).' ������ �� '.$log['mode'].' (<font color=#FFBC79>'.$log['procent'].'%</font>) '.$log['name'].' ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (-<font color=#82FFFF>'.$log['add_hp'].'</font> ��.����� ��������).';
			}
			break;
			case 23:
			{
				$action.= '������������ �������� ��������!';
			}
			break;
			case 24:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.������� ��� ������ ����������';
			}
			break;
			case 25:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� '.echo_sex('�����������','������������',$log['sex']).'!';
			}
			break;
			case 26:
			{
				$action.= '<font color=ffff00><b>����.����!</b></font> '.echo_sex('�����','�������',$log['sex']).' '.$log['name'].' <�������> (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['mode'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������)';
			}
			break;
			case 27:
			{
				$action.= ''.echo_sex('������','�������',$log['sex']).' �� ��������� ��� (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['mode'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������)';
			}
			break;
			case 28:
			{
				$action.= ''.echo_sex('�����','�������',$log['sex']).' '.$log['name'].' <�������> (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['mode'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������)';
			}
			break;
			case 29:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� '.echo_sex('�����������','������������',$log['sex']).'!     ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 30:
			{
				$action.= '<font color=ff0000><b>�� ����� ������ ����� �� '.echo_sex('������','�������',$log['sex']).' ������� � ����� � ��������� ��� '.echo_sex('������','�������',$log['sex']).'</b></font>';
			}
			break;
			case 31:
			{
				$action.= '<font color=ff0000><b>�� '.echo_sex('������','�������',$log['sex']).' ������� � ����� � '.echo_sex('�������','��������',$log['sex']).' '.$log['procent'].'% ���������</b></font>';
			}
			break;
			case 32:
			{
				$action.= '<font color=ffff00><b>����.����!</b></font> '.echo_sex('�����','�������',$log['sex']).' '.$log['name'].' ������� <'.$log['mode'].'> (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['kuda'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 33:
			{
				$action.= ''.echo_sex('�����','�������',$log['sex']).' '.$log['name'].' ������� <'.$log['mode'].'> (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['kuda'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 34:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ������ ��������� �������!';
			}
			break;
			case 35:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ������� ��� ����� �������';
			}
			break;
			case 36:
			{
				$action.= '���� ������������ �������!';
			}
			break;
			case 37:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>.  �� �� '.echo_sex('����','������',$log['sex']).' �������� ���������� ������� ���������� '.$log['mode'].' ('.$log['name'].').';
				if (($log['minus_mp']!=0) OR ($log['minus_hp']) OR ($log['minus_stm']))
				{
					$action.= '   ��������� ';
					if ($log['minus_mp']!=0)
					{
						$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
					}
					if ($log['minus_hp']!=0)
					{
						if ($log['minus_mp']!=0)
						{
							$action.=', ';
						}
						$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.��������';
					}
					if ($log['minus_stm']!=0)
					{
						if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
						{
							$action.=', ';
						}
						$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
					}
					$action.='.';
				}
			}
			break;
			case 38:
			{
				 $action.= ''.echo_sex('��������','����������',$log['sex']).' ��������� ���������� <<b>('.$log['name'].')</b>> (<font color=#FFBC79>'.$log['procent'].'%</font>) �� ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (';
				if ($log['add_hp']!=0)
				{
					$action.='<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������';
				}
				if ($log['add_mp']!=0)
				{
					if ($log['add_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#F4518A>'.$log['add_mp'].'</font> ��.����� ����';
				}
				if ($log['add_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#F4518A>'.$log['add_stm'].'</font> ��.����� �������';
				}
				$action.=').    ��������� ';
				if ($log['minus_mp']!=0)
				{
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
				}
				if ($log['minus_hp']!=0)
				{
					if ($log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_mp'].'</font> ��.��������';
				}
				if ($log['minus_stm']!=0)
				{
					if ($log['minus_mp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='<font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
				}
				$action.='.';
			}
			break;
			case 39:
			{
				if ($log['procent']!=0)
				{
					$action.= '(<font color=ff0000><b>���������� ���� ����� ��������� �������, �� ��� ��� ��������� ������ �������</b></font>. ��������� ������ ��������� �� '.$log['procent'].'%)';
				}
			}
			break;
			case 40:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. ��������� ��.����, ��.�������� ��� ��.������� ��� ����� ���������� ����������� <font color=#8080C0>'.$log['mode'].' ('.$log['name'].')</font>';
			}
			break;
			case 41:
			{
				$action.= '���� ������������ ��������� �����������!';
			}
			break;
			case 42:
			{
				$action.= ''.echo_sex('�����','�������',$log['sex']).' ���� ���������� '.$log['mode'].' (<font color=#FFBC79>'.$log['procent'].'%</font>) � <font color=ff0000><b>'.$log['kuda'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).';
			}
			break;
			case 43:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. � ��������� ����������� ������!';
			}
			break;
			case 44:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ������� ��� ����� ����������';
			}
			break;
			case 45:
			{
				$action.= '���� ������������ ��������� ����������!';
			}
			break;
			break;
			case 46:
			{
				$action.= '&nbsp;&nbsp;�� ����� ��� ������� (';
				if ($log['minus_hp']!=0)
				{
					$action.='-'.$log['minus_hp'].' ��.����� ��������';
				}
				if ($log['minus_mp']!=0)
				{
					if ($log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.='-'.$log['minus_mp'].' ��.����� ����';
				}
				if ($log['minus_stm']!=0)
				{
					if ($log['minus_hp']!=0 OR $log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action.='-'.$log['minus_stm'].' ��.����� �������';
				}
				$action.='). ��������� ����: ';
				if ($log['add_hp']!=0 OR $log['minus_hp']!=0)
				{
					$action.=''.$log['add_hp'].' ��.����� ��������';
				}
				if ($log['add_mp']!=0 OR $log['minus_mp']!=0)
				{
					if ($log['add_hp']!=0 OR $log['minus_hp']!=0)
					{
						$action.=', ';
					}
					$action.=''.$log['add_mp'].' ��.����� ����';
				}
				if ($log['add_stm']!=0 OR $log['minus_stm']!=0)
				{
					if ($log['add_hp']!=0 OR $log['add_mp']!=0 OR $log['minus_hp']!=0 OR $log['minus_mp']!=0)
					{
						$action.=', ';
					}
					$action=''.$log['add_stm'].' ��.����� �������';
				}
				$action.='.';
			}
			break;
			case 47:
			{
				$action.= '<font color="#8080C0" face="Tahoma"><b>&nbsp;&nbsp;�� '.echo_sex('�����','�������',$log['sex']).' �� ������������ ����������</b></font>';
			}
			break;
			case 48:
			{
				$action.= '<font color="#FF8000" size="2" face="Tahoma"><b>&nbsp;&nbsp;'.$log['name'].' �������� ������ ��� '.$log['na_kogo_name'].'</b></font>';
			}
			break;
			case 49:
			{
				$action.= '<font color="#FF8000" size="2" face="Tahoma"><b>&nbsp;&nbsp;'.$log['name'].' ������� ������ ��� '.$log['na_kogo_name'].'</b></font>';
			}
			break;
			case 50:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' ���� ����� �� ���� ��������.</font>';
			}
			break;
			case 51:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' ��� ���� �� ���� ��������.</font>';
			}
			break;
			case 52:
			{
				if ($log['add_hp']!=0 OR $log['procent']!=0)
				{
					$action.= '�� ��������� ';
					if ($log['add_hp']!=0)
					{
						$action.='<b><font color="#FF0000">'.$log['add_hp'].'</font></b> �����';
					}
					if ($log['procent']!=0)
					{
						if ($log['add_hp']!=0)
						{
							$action.=' � ';
						}
						$action.='<b><font color="#FF0000">'.$log['procent'].'</font></b> �����';
					}
					$action.='!';
				}
			}
			break;
			case 53:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' ������� � ���� ���.</font>';
			}
			break;
			case 54:
			{
				$action.= '<font color="#0080C0" size="2" face="Verdana">'.$log['na_kogo_name'].' ������ � ���� ���.</font>';
			}
			break;
			case 55:
			{
				$action.= '�� '.echo_sex('�������','�������',$log['sex']).' ���.    �� '.echo_sex('���������','����������',$log['sex']).' ';
				if ($log['add_hp']>0) $action.='<b><font color="#FF0000">'.$log['add_hp'].'</font></b> ����� �����';
				if ($log['procent']>0)
				{
					if ($log['add_hp']>0) $action.=' � ';
					$action.='<b><font color="#FF0000">'.$log['procent'].'</font></b> �����';
				}
				if ($log['add_hp']==0 AND $log['procent']==0) $action.=' � � � � � � ! (���)';
			}
			break;
			case 56:
			{
				$action.= '�� '.echo_sex('����������','�����������',$log['sex']).' �� �����.';
			}
			break;
			case 57:
			{
				$action.= '������ ����� ������ ����� ���������';
			}
			break;
			case 58:
			{
				$action.= '�������� ����� ������ ����� ���������';
			}
			break;
			case 59:
			{
				if ($log['procent']!=0)
				{
					$action.= '(<font color=ff0000><b>������ � ����� ����������� �� ������ � ����� ����������</b></font>. ��������� ������ ��������� �� '.$log['procent'].'%)';
				}
			}
			break;
			case 60:
			{
				$action.= '��������� ����� ������ ����� �����������';
			}
			break;
			case 61:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ������� ��� ��������';
			}
			break;
			case 62:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ���� ��� ��������';
			}
			break;
			case 63:
			{
				$action.= '���� ������������ �����!';
			}
			break;
			case 64:
			{
				$action.= ''.echo_sex('���������','����������',$log['sex']).' '.$log['mode'].' (� <font color=ff0000><b>'.$log['kuda'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 65:
			{
				$action.= ''.echo_sex('���������','����������',$log['sex']).' '.$log['mode'].' (� <font color=ff0000><b>'.$log['kuda'].'</b></font> ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
			}
			break;
			case 66:
			{
				$action.= '���� ������� ������ ���� ����!';
			}
			break;
			case 67:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ������� ��� ������';
			}
			break;
			case 68:
			{
				$action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ���� ��� ������';
			}
			break;
			case 69:
			{
				$action.= '������ ������������ ���������!';
			}
			break;
			case 70:
			{
				$action.= ''.echo_sex('������','�������',$log['sex']).' '.$log['mode'].' (� <font color=ff0000><b>'.$log['kuda'].'</b></font>) ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
			}
			break;
			case 71:
			{
				$action.= ''.echo_sex('������','�������',$log['sex']).' '.$log['mode'].' (� <font color=ff0000><b>'.$log['kuda'].'</b></font>) ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b> (<font color=#F4518A>'.$log['add_hp'].'</font> ��.����� ��������).     ��������� <font color=#79D3FF>'.$log['minus_mp'].'</font> ��.����';
			}
			break;
			case 72:
			{
				$action.= '���� ������ ������ ���� ����!';
			}
			break;
            case 73:
            {
                $action.= echo_sex('�����������','������������',$log['sex']).' '.$log['mode'].'.';
            }
            break;
            case 74:
            {
                $action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ������� ��� ������������� ������';
            }
            break;
            case 75:
            {
                $action.= '<font color=#FF0000><b>��������</b></font>. �� ������� ���� ��� ������������� ������';
            }
            break;
            case 76:
            {
                $action.= '������������� ������������� ������!';
            }
            break;
            case 77:
            {
                $action.= '<font color=#FF0000><b>���������</b></font> ������� �������� ������ �������!';

                if ($log['minus_stm'] != 0)
                  $action.= ' ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.�������';
                elseif ($log['minus_mp'] != 0)
                  $action.= ' ��������� <font color=#79D3FF>'.$log['minus_stm'].'</font> ��.����';
            }
            break;
            case 78:
            {
                $action.= '����� ����������� �������.';
            }
            break;
            case 79:
            {
                $action.= '����� �������������� �������.';
            }
            break;
            case 80:
            {
                $action.= '����� ��������� �������.';
            }
            break;
			case 81:
            {
				$action.= '<b><font color=#FFFFFF size="2" face="Verdana">'.$log['name'].'</font></b> ������� ���������� � ���.';
            }
            break;
			case 82:
			{
				$action.= ''.echo_sex('���������','����������',$log['sex']).' ���.';
			}
			break;
			case 83:
			{
				$action.= '������ �� ��������� � �����.';
			}
			break;
			case 84:
			{
				$action.= ''.echo_sex('�����������','�����������',$log['sex']).' ������������� ������ <font color=#00FF80>'.$log['add_hp'].'</font> ��. ��������.';
			}
			break;
			case 85:
			{
				$action.= ''.echo_sex('�������','��������',$log['sex']).' ���� ��������.';
			}
			break;
			case 86:
			{
				$action.= ''.echo_sex('�������','��������',$log['sex']).' <font color=#F4518A>'.$log['add_hp'].'</font> ��. �������� �� �����.';
			}
			break;
			case 87:
			{
				$action.= ''.echo_sex('�������','��������',$log['sex']).' ������ �� <font color=#82FFFF>'.$log['add_hp'].'</font> ��. ����� ��������.';
			}
			break;
			case 88:
			{
				$action.= '��������������� '.echo_sex('����','�������',$log['sex']).' <font color=#F4518A>'.$log['add_hp'].'</font> ��. ����� ������ <b><font color=#FFFFFF>'.$log['na_kogo_name'].'</font></b>.';
			}
			break;
			case 89:
			{
				$action.= echo_sex('�������','��������',$log['sex']).' <font color=#F4518A>'.$log['add_hp'].'</font> ������ � ���������� ������������.';
			}
			break;
			case 90:
			{
				$action.= echo_sex('����','������',$log['sex']).' � ���������� ������������.';
			}
			break;
		}
		$action.='<br />';
		$prev_log = $log;
	}
	if (isset($prev_log))
	{
		if ($prev_log['kto_name']=='') $prev_log['kto_name']='&nbsp;';
		$text.='<tr height="20"><td height="20" align="center" valign="center"><b>&nbsp;'.$prev_log['hod'].'&nbsp;</b></td><td height="20" align="center" valign="center"><b><font color="#80FF80">'.$prev_log['kto_name'].'</font></b></td><td height="20" valign="center">&nbsp;&nbsp;'.$action.'</td></tr>';
		$est= 1;
	}
	$text.='</tbody></table>';

	if ($for_hod==0)
	{
		$sel = myquery("SELECT game_combats_log_data.*, text.name AS user_name, text.mode AS clan_id, text.kuda AS clevel
		FROM (
		game_combats_log_data
		)
		LEFT JOIN game_combats_log_text AS text ON ( text.id = game_combats_log_data.kto )
		WHERE game_combats_log_data.boy=$boy AND game_combats_log_data.action=99
		ORDER BY text.mode,text.name");

		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$text.='<br><br>����� ��������! <br>';
			$i = mysql_num_rows($sel);
			if ($i<=1)
			{
				$text.='�������:  ';
			}
			else
			{
				$text.='��������:  ';
			}
			while ($log = mysql_fetch_array($sel))
			{
				if ((int)$log['clan_id']>0) $text.='<img src="http://'.img_domain.'/clan/'.$log['clan_id'].'.gif">';
				$text.=''.$log['user_name'].'['.$log['clevel'].']';
				$i--;
				if ($i>0)
				{
					$text.=', ';
				}
				else
				{
					$text.='.';
				}
			}
		}
	}

	if ($est==0) return '';

	return $text;
}
?>