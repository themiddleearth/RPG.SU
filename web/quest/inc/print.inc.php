<?php

if (function_exists("start_debug")) start_debug(); 

if ($_GET['prison_action'] == 'oborot_go')
{
	$f=mt_rand(1,7);
	switch ($f)
	{
		case 1:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>�-��, �-�����!</font>';
	    	QuoteTable('close');
	    	break;
	    case 2:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>��� �����..</font>';
	    	QuoteTable('close');
	    	break;
	    case 3:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>��, ��������!</font>';
	    	QuoteTable('close');
	    	break;
	    case 4:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>&quot;��������, ���������!&quot; - ������ ������� ������ �����������.</font>';
	    	QuoteTable('close');
	    	break;	    		    	
		case 5:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>�����, �������, �����!</font>';
	    	QuoteTable('close');
	    	break;	    	
		case 6:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>�� ���� ������ ����� � �������!</font>';
	    	QuoteTable('close');
	    	break;	    		    	
	    case 7:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>�-� - ���! �-� - ���!</font>';
	    	QuoteTable('close');
	    	break;	    	    	
	}
}
if ($_GET['prison_action'] == 'oborot_no')
{
	QuoteTable('open');
    echo '<font color=#FFFF00>������ ��� �� ��������!</font>';
    QuoteTable('close');
}
elseif ($_GET['prison_action'] == 'oborot_go_slow')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>&quot;�������������, ��, ����!&quot; - �������� ����������� � ����� ���� ������.</font>';	
	QuoteTable('close');
}
elseif ($_GET['prison_action'] == 'cant_exit')
{  
	QuoteTable('open');
	list($exp)=mysql_fetch_array(myquery("SELECT EXP FROM game_users WHERE user_id='$user_id'"));      	
	$play=mysql_fetch_array(myquery("select exp_was,exp_need from game_prison where user_id='$user_id'"));        
    $ob=$play['exp_was']+$play['exp_need']-$exp;  
	echo '<font color=#FFFF00>&quot;���, ��� '.echo_sex('��������','���������').' �� ����?&quot; - ����������� ���� � ���� �����������. - ��� �������, *�������� ��������*, � ���� ��� '.$ob.' �������� �������!</font>';	
	QuoteTable('close');
	echo '<br><br>';
}
elseif ($_GET['prison_action'] == 'run')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>�� '.echo_sex('����������','�����������').' ��������� ���������� � ����, �� ������� '.echo_sex('�������','��������').' � ������. ������������ ��������� ����, ������� ������� �� ������, ������ ������ � �������� �� ����� - ��������� � ����. �� ������� ������ ���� ��������� ��������� �� 15%.</font>';	
	QuoteTable('close');
	echo '<br><br>';
}
elseif ($_GET['prison_action'] == 'done')
{
	QuoteTable('open');
	echo '<font color=#16FF31 size=5> <B>�� ������� - � ������ ��������!</B></font>';
	QuoteTable('close');
	echo '<br><br>';
}elseif ($_GET['prison_action'] == 'go_out')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>������������ �������� ���� �� ������ � �������������� ������� ������� � ������. ��� ��� �������� ���� �� ��� ������� "����������" � ���������� ����� ������ ���� ����������� �������� - � ���������� �����.<B></B></font>';
	QuoteTable('close');
	echo '<br><br>';
}

if (function_exists("save_debug")) save_debug(); 

?>