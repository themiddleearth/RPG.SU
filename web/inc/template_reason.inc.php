<?php

if (function_exists("start_debug")) start_debug(); 

error_reporting (E_ALL);

$print_time=0;

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{      
	echo '<font color="white">&nbsp;';
	if ($reason == 'prana' AND $char['clevel']>0)
    {
        echo 'Вы слишком устали. Необходимо восстановить прану!<br><br>';		
    }
	elseif ($reason == 'block')
    {
		echo 'После совершённого действия Вам необходимо отдохнуть. В данном состоянии Вы дополнительно уязвимы для нападений!<br><br>';
		$print_time=1;
    }
	elseif ($reason == 'weigth')
    {
		echo 'Вы слишком устали. Вы несёте слишком много вещей!<br><br>';
		$print_time=1;
    }
	elseif ($reason == 'koni')
    {
		echo 'Ваш уровень коня и верховой езды не позволяет перемещаться без задержки!<br><br>';
		$print_time=1;
    }
    elseif ($reason == 'delay' && $user_time < $char['delay'])
    {   
        if ($char['delay_reason']==7 or $char['delay_reason']==8 or $char['delay_reason']==19 or $char['delay_reason']==20)        
	    {
		    $print_time=1;			
	    }
    }
}
echo '</font>';
if ($print_time==1)
{
	echo '<font color="#ff0000">&nbsp;Подождите:</font> ещё <span id="pendule"></span>&nbsp;<font color="#ff0000">'
		    .'<script language="JavaScript">
		    a='.abs($user_time - $char['delay']).'
			text1="";
		    function clock_status()
		    {
			    if (a<=9) text="&nbsp;"+a;
			    if (a<=0) {text1="(Ходите)";text="0";}
			    else text=a;
			    if (document.layers) {
				    document.layers.pendule.document.write(text);
				    document.layers.pendule.document.close();
				    document.layers.pend.document.write(text1);
				    document.layers.pend.document.close();
				    }
				    else
				    {
					    document.getElementById("pendule").innerHTML = text;
					    document.getElementById("pend").innerHTML = text1;
					    a=a-1;
					    window.setTimeout("clock_status()",1000); 
				    }
		    }
		    </script>
		    <body onLoad="clock_status(); GGearsInit();">'.'</font>сек.<span id="pend"></span><br><br>';
}

if (function_exists("save_debug")) save_debug(); 

?>