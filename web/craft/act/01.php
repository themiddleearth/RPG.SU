<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user!=$user_id and ($user_time >= $char['delay'] OR $char['block']!=1) )
{
	QuoteTable('open'); 	
	echo '<a href="?func=main&act=02">���������� �� ������<a><br>';
	QuoteTable('close'); 
}
if ($build_user==$user_id) 
{
	QuoteTable('open'); 	
	echo'<a href="?func=main&act=03">���������<a>';
	echo'<br><a href="?func=main&act=04">��������� ������<a>';
	echo'<br><a href="?func=main&act=05">��������� �� �������<a>';
	echo'<br><a href="?func=main&act=07">����������<a>';
	QuoteTable('close'); 
}
	
if ($build_sell>=1) 
{
	echo'<br><br>��� ������ ���������� �� ������� �� '.$build_sell.' �������!<br><a href="?func=main&act=06">������<a>';
}

if (function_exists("save_debug")) save_debug(); 

?>