<?php
function check_dostup($card_id,$charboy)
{
	$dostup = 0;
	switch ($card_id)
	{
		case '1':
		{
			$dostup=1;
		}
		break;

		case '2':
		{
			$dostup=1;
		}
		break;

		case '3':
		{
            if ($charboy['bricks']>=1) $dostup=1;
		}
		break;

		case '4':
		{
            if ($charboy['bricks']>=3) $dostup=1;
		}
		break;

		case '5':
		{
            if ($charboy['bricks']>=4) $dostup=1;
		}
		break;

		case '6':
		{
            if ($charboy['bricks']>=7) $dostup=1;
		}
		break;

		case '7':
		{
            if ($charboy['bricks']>=2) $dostup=1;
		}
		break;

		case '8':
		{
            if ($charboy['bricks']>=5) $dostup=1;
		}
		break;

		case '9':
		{
            if ($charboy['bricks']>=2) $dostup=1;
		}
		break;

		case '10':
		{
            if ($charboy['bricks']>=3) $dostup=1;
		}
		break;

		case '11':
		{
            if ($charboy['bricks']>=2) $dostup=1;
		}
		break;

		case '12':
		{
            if ($charboy['bricks']>=3) $dostup=1;
		}
		break;

		case '13':
		{
            if ($charboy['bricks']>=7) $dostup=1;
		}
		break;

		case '14':
		{
            if ($charboy['bricks']>=8) $dostup=1;
		}
		break;

		case '15':
		{
            if ($charboy['bricks']>=0) $dostup=1;
		}
		break;

		case '16':
		{
            if ($charboy['bricks']>=5) $dostup=1;
		}
		break;

		case '17':
		{
            if ($charboy['bricks']>=4) $dostup=1;
		}
		break;

		case '18':
		{
            if ($charboy['bricks']>=6) $dostup=1;
		}
		break;

		case '19':
		{
            if ($charboy['bricks']>=0) $dostup=1;
		}
		break;

		case '20':
		{
            if ($charboy['bricks']>=8) $dostup=1;
		}
		break;

		case '21':
		{
            if ($charboy['bricks']>=9) $dostup=1;
		}
		break;

		case '22':
		{
            if ($charboy['bricks']>=11) $dostup=1;
		}
		break;

		case '23':
		{
            if ($charboy['bricks']>=13) $dostup=1;
		}
		break;

		case '24':
		{
            if ($charboy['bricks']>=15) $dostup=1;
		}
		break;

		case '25':
		{
            if ($charboy['bricks']>=16) $dostup=1;
		}
		break;

		case '26':
		{
            if ($charboy['bricks']>=18) $dostup=1;
		}
		break;

		case '27':
		{
            if ($charboy['bricks']>=24) $dostup=1;
		}
		break;

		case '28':
		{
            if ($charboy['bricks']>=7) $dostup=1;
		}
		break;

		case '29':
		{
            if ($charboy['bricks']>=9) $dostup=1;
		}
		break;

		case '30':
		{
            if ($charboy['bricks']>=1) $dostup=1;
		}
		break;

		case '31':
		{
            if ($charboy['bricks']>=6) $dostup=1;
		}
		break;

		case '32':
		{
            if ($charboy['bricks']>=10) $dostup=1;
		}
		break;

		case '33':
		{
            if ($charboy['bricks']>=14) $dostup=1;
		}
		break;

		case '34':
		{
            if ($charboy['bricks']>=17) $dostup=1;
		}
		break;

        case '35':
        {
            if ($charboy['bricks']>=20) $dostup=1;
        }
        break;

		case '50':
		{
            if ($charboy['gems']>=1) $dostup=1;
		}
		break;

		case '51':
		{
            if ($charboy['gems']>=2) $dostup=1;
		}
		break;

		case '52':
		{
            if ($charboy['gems']>=2) $dostup=1;
		}
		break;

		case '53':
		{
            if ($charboy['gems']>=3) $dostup=1;
		}
		break;

		case '54':
		{
            if ($charboy['gems']>=2) $dostup=1;
		}
		break;

		case '55':
		{
            if ($charboy['gems']>=5) $dostup=1;
		}
		break;

		case '56':
		{
            if ($charboy['gems']>=4) $dostup=1;
		}
		break;

		case '57':
		{
            if ($charboy['gems']>=6) $dostup=1;
		}
		break;

		case '58':
		{
            if ($charboy['gems']>=2) $dostup=1;
		}
		break;

		case '59':
		{
            if ($charboy['gems']>=3) $dostup=1;
		}
		break;

		case '60':
		{
            if ($charboy['gems']>=4) $dostup=1;
		}
		break;

		case '61':
		{
            if ($charboy['gems']>=3) $dostup=1;
		}
		break;

		case '62':
		{
            if ($charboy['gems']>=7) $dostup=1;
		}
		break;

		case '63':
		{
            if ($charboy['gems']>=7) $dostup=1;
		}
		break;

		case '64':
		{
            if ($charboy['gems']>=6) $dostup=1;
		}
		break;

		case '65':
		{
            if ($charboy['gems']>=9) $dostup=1;
		}
		break;

		case '66':
		{
            if ($charboy['gems']>=8) $dostup=1;
		}
		break;

		case '67':
		{
            if ($charboy['gems']>=7) $dostup=1;
		}
		break;

		case '68':
		{
            if ($charboy['gems']>=10) $dostup=1;
		}
		break;

		case '69':
		{
            if ($charboy['gems']>=5) $dostup=1;
		}
		break;

		case '70':
		{
            if ($charboy['gems']>=13) $dostup=1;
		}
		break;

		case '71':
		{
            if ($charboy['gems']>=4) $dostup=1;
		}
		break;

		case '72':
		{
            if ($charboy['gems']>=12) $dostup=1;
		}
		break;

		case '73':
		{
            if ($charboy['gems']>=14) $dostup=1;
		}
		break;

		case '74':
		{
            if ($charboy['gems']>=16) $dostup=1;
		}
		break;

		case '75':
		{
            if ($charboy['gems']>=15) $dostup=1;
		}
		break;

		case '76':
		{
            if ($charboy['gems']>=17) $dostup=1;
		}
		break;

		case '77':
		{
            if ($charboy['gems']>=21) $dostup=1;
		}
		break;

		case '78':
		{
            if ($charboy['gems']>=8) $dostup=1;
		}
		break;

		case '79':
		{
            if ($charboy['gems']>=0) $dostup=1;
		}
		break;

		case '80':
		{
            if ($charboy['gems']>=18) $dostup=1;
		}
		break;

		case '81':
		{
            if ($charboy['gems']>=11) $dostup=1;
		}
		break;

		case '82':
		{
            if ($charboy['gems']>=0) $dostup=1;
		}
		break;

		case '83':
		{
            if ($charboy['gems']>=5) $dostup=1;
		}
		break;

		case '100':
		{
            if ($charboy['monsters']>=0) $dostup=1;
		}
		break;

		case '101':
		{
            if ($charboy['monsters']>=1) $dostup=1;
		}
		break;

		case '102':
		{
            if ($charboy['monsters']>=1) $dostup=1;
		}
		break;

		case '103':
		{
            if ($charboy['monsters']>=3) $dostup=1;
		}
		break;

		case '104':
		{
            if ($charboy['monsters']>=2) $dostup=1;
		}
		break;

		case '105':
		{
            if ($charboy['monsters']>=3) $dostup=1;
		}
		break;

		case '106':
		{
            if ($charboy['monsters']>=4) $dostup=1;
		}
		break;

		case '107':
		{
            if ($charboy['monsters']>=6) $dostup=1;
		}
		break;

		case '108':
		{
            if ($charboy['monsters']>=3) $dostup=1;
		}
		break;

		case '109':
		{
            if ($charboy['monsters']>=5) $dostup=1;
		}
		break;

		case '110':
		{
            if ($charboy['monsters']>=6) $dostup=1;
		}
		break;

		case '111':
		{
            if ($charboy['monsters']>=7) $dostup=1;
		}
		break;

		case '112':
		{
            if ($charboy['monsters']>=8) $dostup=1;
		}
		break;

		case '113':
		{
            if ($charboy['monsters']>=0) $dostup=1;
		}
		break;

		case '114':
		{
            if ($charboy['monsters']>=5) $dostup=1;
		}
		break;

		case '115':
		{
            if ($charboy['monsters']>=6) $dostup=1;
		}
		break;

		case '116':
		{
            if ($charboy['monsters']>=6) $dostup=1;
		}
		break;

		case '117':
		{
            if ($charboy['monsters']>=5) $dostup=1;
		}
		break;

		case '118':
		{
            if ($charboy['monsters']>=8) $dostup=1;
		}
		break;

		case '119':
		{
            if ($charboy['monsters']>=9) $dostup=1;
		}
		break;

		case '120':
		{
            if ($charboy['monsters']>=11) $dostup=1;
		}
		break;

		case '121':
		{
            if ($charboy['monsters']>=9) $dostup=1;
		}
		break;

		case '122':
		{
            if ($charboy['monsters']>=10) $dostup=1;
		}
		break;

		case '123':
		{
            if ($charboy['monsters']>=14) $dostup=1;
		}
		break;

		case '124':
		{
            if ($charboy['monsters']>=11) $dostup=1;
		}
		break;

		case '125':
		{
            if ($charboy['monsters']>=12) $dostup=1;
		}
		break;

		case '126':
		{
            if ($charboy['monsters']>=15) $dostup=1;
		}
		break;

		case '127':
		{
            if ($charboy['monsters']>=17) $dostup=1;
		}
		break;

		case '128':
		{
            if ($charboy['monsters']>=25) $dostup=1;
		}
		break;

		case '129':
		{
            if ($charboy['monsters']>=2) $dostup=1;
		}
		break;

		case '130':
		{
            if ($charboy['monsters']>=4) $dostup=1;
		}
		break;

		case '131':
		{
            if ($charboy['monsters']>=13) $dostup=1;
		}
		break;

		case '132':
		{
            if ($charboy['monsters']>=18) $dostup=1;
		}
		break;

		case '133':
		{
			if ($charboy['monsters']>=2) $dostup=1;
		}
		break;

		case '134':
		{
			if ($charboy['monsters']>=10) $dostup=1;
		}
		break;
	}
    return $dostup;
}

function extra_hod($card_id)
{
	$extra = 0;
	switch ($card_id)
	{
        case 2:
        case 3:
        case 13:
        case 14:
        case 50:
		case 51:
			$extra=1;
		break;
		case 54:
			$extra=2;
		break;
		case 101:
			$extra=1;
		break;
		case 104:
			$extra=2;
		break;
		case 107:
			$extra=1;
		break;
    }
	return $extra;
}

function attack_enemy(&$users,$kogo,$attack)
{
	if ($users[$kogo]['wall']>=$attack)
	{
		$users[$kogo]['wall']-=$attack;
	}
	else
	{
		$ost = $attack - $users[$kogo]['wall'];
		$users[$kogo]['wall'] = 0;
		$users[$kogo]['tower']-= $ost;
	}
}

function make_action_card(&$users,$kto,$kogo,$card_id)
{
	switch ($card_id)
	{
		case '1':
		{
         	$users[$kto]['bricks']-=8;
         	$users[$kogo]['bricks']-=8;
		}
		break;

		case '2':
		{
         	$users[$kto]['bricks']+=2;
         	$users[$kto]['gems']+=2;
		}
		break;

		case '3':
		{
         	$users[$kto]['wall']+=1;
         	$users[$kto]['bricks']-=1;
		}
		break;

		case '4':
		{
         	$users[$kto]['bricks_add']+=1;
         	$users[$kto]['bricks']-=3;
		}
		break;

		case '5':
		{
			if ($users[$kto]['bricks_add']<$users[$kogo]['bricks_add'])
	         	$users[$kto]['bricks_add']+=2;
	        else
	         	$users[$kto]['bricks_add']+=1;
         	$users[$kto]['bricks']-=4;
		}
		break;

		case '6':
		{
         	$users[$kto]['bricks_add']+=1;
         	$users[$kto]['wall']+=4;
         	$users[$kto]['bricks']-=7;
		}
		break;

		case '7':
		{
         	$users[$kto]['gems']-=6;
         	$users[$kto]['wall']+=5;
         	$users[$kto]['bricks']-=2;
		}
		break;

		case '8':
		{
			if ($users[$kto]['bricks_add']<$users[$kogo]['bricks_add'])
	         	$users[$kto]['bricks_add']=$users[$kogo]['bricks_add'];
         	$users[$kto]['bricks']-=5;
		}
		break;

		case '9':
		{
         	$users[$kto]['wall']+=3;
         	$users[$kto]['bricks']-=2;
		}
		break;

		case '10':
		{
         	$users[$kto]['wall']+=4;
         	$users[$kto]['bricks']-=3;
		}
		break;

		case '11':
		{
         	$users[$kto]['gems']+=4;
         	$users[$kto]['bricks_add']+=1;
         	$users[$kogo]['bricks_add']+=1;
         	$users[$kto]['bricks']-=2;
		}
		break;

		case '12':
		{
			if ($users[$kto]['wall']==0)
	         	$users[$kto]['wall']+=6;
	        else
	         	$users[$kto]['wall']+=3;
         	$users[$kto]['bricks']-=3;
		}
		break;

		case '13':
		{
         	$users[$kto]['wall']-=6;
         	$users[$kogo]['wall']-=6;
         	$users[$kto]['bricks']-=7;
		}
		break;

		case '14':
		{
         	$users[$kto]['gems_add']+=1;
         	$users[$kto]['bricks']-=8;
		}
		break;

		case '15':
		{
         	$users[$kto]['bricks_add']-=1;
         	$users[$kogo]['bricks_add']-=1;
		}
		break;

		case '16':
		{
         	$users[$kto]['wall']+=6;
         	$users[$kto]['bricks']-=5;
		}
		break;

		case '17':
		{
         	$users[$kogo]['bricks_add']-=1;
         	$users[$kto]['bricks']-=4;
		}
		break;

		case '18':
		{
         	$users[$kto]['bricks_add']+=2;
         	$users[$kto]['bricks']-=6;
		}
		break;

		case '19':
		{
         	$users[$kto]['bricks_add']-=1;
         	$users[$kto]['wall']+=10;
         	$users[$kto]['gems']+=6;
		}
		break;

		case '20':
		{
         	$users[$kto]['wall']+=8;
         	$users[$kto]['bricks']-=8;
		}
		break;

		case '21':
		{
         	$users[$kto]['wall']+=7;
         	$users[$kto]['gems']+=7;
         	$users[$kto]['bricks']-=9;
		}
		break;

		case '22':
		{
         	$users[$kto]['wall']+=6;
         	$users[$kto]['tower']+=3;
         	$users[$kto]['bricks']-=11;
		}
		break;

		case '23':
		{
         	$users[$kto]['wall']+=12;
         	$users[$kto]['bricks']-=13;
		}
		break;

		case '24':
		{
         	$users[$kto]['wall']+=8;
         	$users[$kto]['tower']+=6;
			$users[$kto]['bricks']-=15;
		}
		break;

		case '25':
		{
         	$users[$kto]['wall']+=15;
         	$users[$kto]['bricks']-=16;
		}
		break;

		case '26':
		{
         	$users[$kto]['wall']+=6;
         	attack_enemy($users,$kogo,10);
         	$users[$kto]['bricks']-=18;
		}
		break;

		case '27':
		{
         	$users[$kto]['wall']+=20;
         	$users[$kto]['tower']+=8;
         	$users[$kto]['bricks']-=24;
		}
		break;

		case '28':
		{
         	$users[$kto]['wall']+=9;
         	$users[$kto]['monsters']-=5;
         	$users[$kto]['bricks']-=7;
		}
		break;

		case '29':
		{
         	$users[$kto]['wall']+=5;
         	$users[$kto]['monsters_add']+=1;
         	$users[$kto]['bricks']-=9;
		}
		break;

		case '30':
		{
         	$users[$kto]['wall']+=1;
         	$users[$kto]['tower']+=1;
         	$users[$kto]['monsters']+=2;
         	$users[$kto]['bricks']-=1;
		}
		break;

		case '31':
		{
		    if ($users[$kto]['wall']<$users[$kogo]['wall'])
		    {
		      $users[$kto]['monsters_add']-=1;
		    }
		    elseif ($users[$kto]['wall']>$users[$kogo]['wall'])
		    {
		      $users[$kogo]['monsters_add']-=1;
		    }
         	$users[$kto]['tower']-=2;
         	$users[$kto]['bricks']-=6;
		}
		break;

		case '32':
		{
         	$users[$kto]['wall']+=6;
         	$users[$kto]['monsters']+=6;
         	if ($users[$kto]['monsters_add']<$users[$kogo]['monsters_add']) $users[$kto]['monsters_add']+=1;
         	$users[$kto]['bricks']-=10;
		}
		break;

		case '33':
		{
         	$users[$kto]['wall']+=7;
         	attack_enemy($users,$kogo,6);
         	$users[$kto]['bricks']-=14;
		}
		break;

		case '34':
		{
			$l=$users[$kto]['wall'];
         	$users[$kto]['wall'] = $users[$kogo]['wall'];
         	$users[$kogo]['wall'] = $l;
         	$users[$kto]['bricks']-=17;
		}
		break;

        case '35':
        {
             $users[$kogo]['bricks_add'] -= 1;
             $users[$kogo]['monsters_add'] -= 1;
             $users[$kogo]['tower'] -= 5;
             $users[$kto]['monsters']-=5;
             $users[$kto]['bricks']-=20;
        }
        break;

		case '50':
		{
         	$users[$kto]['tower']+=1;
         	$users[$kto]['gems']-=1;
		}
		break;

		case '51':
		{
         	$users[$kogo]['tower']-=1;
         	$users[$kto]['gems']-=2;
		}
		break;

		case '52':
		{
         	$users[$kto]['tower']+=3;
         	$users[$kto]['gems']-=2;
		}
		break;

		case '53':
		{
         	$users[$kto]['gems_add']+=1;
         	$users[$kto]['gems']-=3;
		}
		break;

        case '54':
        {
             $users[$kto]['gems']-=2;
        }
        break;

        
		case '55':
		{
         	$users[$kto]['tower']+=3;
         	$users[$kto]['gems']-=5;
		}
		break;

		case '56':
		{
         	$users[$kto]['tower']+=2;
         	$users[$kogo]['tower']-=2;
         	$users[$kto]['gems']-=4;
		}
		break;

 		case '57':
		{
         	$users[$kto]['tower']+=3;
         	$users[$kogo]['tower']+=1;
         	$users[$kto]['gems_add']+=1;
         	$users[$kto]['gems']-=6;
		}
		break;

 		case '58':
		{
         	$users[$kogo]['tower']-=3;
         	$users[$kto]['gems']-=2;
		}
		break;

 		case '59':
		{
         	$users[$kto]['tower']+=5;
         	$users[$kto]['gems']-=3;
		}
		break;

 		case '60':
		{
         	$users[$kogo]['tower']-=5;
         	$users[$kto]['gems']-=4;
		}
		break;

 		case '61':
		{
         	$users[$kto]['tower']-=5;
         	$users[$kto]['gems_add']+=2;
         	$users[$kto]['gems']-=3;
		}
		break;

 		case '62':
		{
         	$users[$kto]['tower']+=3;
         	$users[$kto]['wall']+=3;
         	$users[$kto]['gems_add']+=1;
         	$users[$kto]['gems']-=7;
		}
		break;

 		case '63':
		{
			if ($users[$kto]['gems_add']<$users[$kogo]['gems_add'])
				$users[$kto]['gems_add']=$users[$kogo]['gems_add'];
			if ($users[$kogo]['gems_add']<$users[$kto]['gems_add'])
				$users[$kogo]['gems_add']=$users[$kto]['gems_add'];
         	$users[$kto]['gems']-=7;
		}
		break;

 		case '64':
		{
         	$users[$kto]['tower']+=8;
         	$users[$kto]['gems']-=6;
		}
		break;

 		case '65':
		{
         	$users[$kto]['tower']+=5;
         	$users[$kto]['gems_add']+=1;
         	$users[$kto]['gems']-=9;
		}
		break;

 		case '66':
		{
         	$users[$kogo]['tower']-=9;
         	$users[$kto]['gems_add']-=1;
         	$users[$kto]['gems']-=8;
		}
		break;

 		case '67':
		{
         	$users[$kto]['tower']+=5;
         	$users[$kogo]['bricks']-=6;
         	$users[$kto]['gems']-=7;
		}
		break;

 		case '68':
		{
         	$users[$kto]['tower']+=11;
         	$users[$kto]['gems']-=10;
		}
		break;

 		case '69':
		{
         	$users[$kto]['tower']-=7;
         	$users[$kto]['gems_add']-=1;
         	$users[$kogo]['tower']-=7;
         	$users[$kogo]['gems_add']-=1;
         	$users[$kto]['gems']-=5;
		}
		break;

 		case '70':
		{
         	$users[$kto]['tower']+=6;
         	$users[$kogo]['tower']-=4;
         	$users[$kto]['gems']-=13;
		}
		break;

 		case '71':
		{
         	$users[$kto]['tower']+=7;
         	$users[$kto]['bricks']-=10;
         	$users[$kto]['gems']-=4;
		}
		break;

 		case '72':
		{
         	$users[$kto]['tower']+=8;
         	$users[$kto]['wall']+=3;
         	$users[$kto]['gems']-=12;
		}
		break;

 		case '73':
		{
         	$users[$kto]['tower']+=8;
         	$users[$kto]['monsters_add']+=1;
         	$users[$kto]['gems']-=14;
		}
		break;

 		case '74':
		{
         	$users[$kto]['tower']+=15;
         	$users[$kto]['gems']-=16;
		}
		break;

 		case '75':
		{
         	$users[$kto]['tower']+=10;
         	$users[$kto]['wall']+=5;
         	$users[$kto]['monsters']+=5;
         	$users[$kto]['gems']-=15;
		}
		break;

 		case '76':
		{
         	$users[$kto]['tower']+=12;
         	attack_enemy($users,$kogo,6);
         	$users[$kto]['gems']-=17;
		}
		break;

 		case '77':
		{
         	$users[$kto]['tower']+=20;
         	$users[$kto]['gems']-=21;
		}
		break;

 		case '78':
		{
         	$users[$kto]['tower']+=11;
         	$users[$kto]['wall']-=6;
         	$users[$kto]['gems']-=8;
		}
		break;

 		case '79':
		{
         	$users[$kto]['gems']+=3;
         	$users[$kto]['tower']+=1;
         	$users[$kogo]['tower']+=1;
		}
		break;

 		case '80':
		{
         	$users[$kto]['monsters']+=6;
         	$users[$kto]['tower']+=13;
         	$users[$kto]['bricks']+=6;
         	$users[$kto]['gems']-=18;
		}
		break;

 		case '81':
		{
		    if ($users[$kto]['tower']>$users[$kogo]['wall'])
		    {
		      $users[$kogo]['tower']-=8;
		    }
		    else
		    {
		      attack_enemy($users,$kogo,8);
		      attack_enemy($users,$kto,8);
		    }
		    $users[$kto]['gems']-=11;
		}
		break;

		case '82':
		{
		    if ($users[$kto]['tower']<$users[$kogo]['tower'])
		    {
		      $users[$kto]['tower']+=2;
		    }
		    else
		    {
		      $users[$kto]['tower']+=1;
		    }
		}
		break;

 		case '83':
		{
	        $users[$kto]['tower']+=4;
	        $users[$kto]['monsters']-=3;
	        $users[$kogo]['tower']-=2;
	        $users[$kto]['gems']-=5;
		}
		break;

 		case '100':
		{
         	$users[$kto]['monsters']-=6;
         	$users[$kogo]['monsters']-=6;
		}
		break;

 		case '101':
		{
         	attack_enemy($users,$kogo,2);
         	$users[$kto]['monsters']-=1;
		}
		break;

 		case '102':
		{
         	$users[$kto]['gems']-=3;
         	attack_enemy($users,$kogo,4);
         	$users[$kto]['monsters']-=1;
		}
		break;

 		case '103':
		{
         	$users[$kto]['monsters_add']+=1;
         	$users[$kto]['monsters']-=3;
		}
		break;
        
        case '104':
        {
             $users[$kto]['monsters']-=2;
        }
        break;

 		case '105':
		{
         	attack_enemy($users,$kogo,6);
         	attack_enemy($users,$kto,3);
         	$users[$kto]['monsters']-=3;
		}
		break;

 		case '106':
		{
         	$users[$kogo]['tower']-=3;
         	attack_enemy($users,$kto,1);
         	$users[$kto]['monsters']-=4;
		}
		break;

 		case '107':
		{
         	$users[$kogo]['tower']-=2;
         	$users[$kto]['monsters']-=6;
		}
		break;

 		case '108':
		{
         	attack_enemy($users,$kogo,5);
         	$users[$kto]['monsters']-=3;
		}
		break;

 		case '109':
		{
         	$users[$kto]['wall']+=3;
         	attack_enemy($users,$kogo,4);
         	$users[$kto]['monsters']-=5;
		}
		break;

 		case '110':
		{
         	$users[$kogo]['tower']-=4;
         	$users[$kto]['monsters']-=6;
		}
		break;

 		case '111':
		{
         	$users[$kto]['monsters_add']+=2;
         	$users[$kto]['monsters']-=7;
		}
		break;

 		case '112':
		{
         	$users[$kto]['wall']+=4;
         	$users[$kto]['tower']+=2;
         	attack_enemy($users,$kogo,2);
         	$users[$kto]['monsters']-=8;
		}
		break;

 		case '113':
		{
         	$users[$kto]['monsters_add']+=1;
         	$users[$kogo]['monsters_add']+=1;
         	$users[$kto]['monsters']+=3;
		}
		break;

 		case '114':
		{
         	attack_enemy($users,$kogo,6);
         	$users[$kto]['monsters']-=5;
		}
		break;

 		case '115':
		{
			attack_enemy($users,$kogo,7);
         	$users[$kto]['monsters']-=6;
		}
		break;

 		case '116':
		{
         	$users[$kogo]['monsters']-=3;
         	attack_enemy($users,$kogo,6);
         	$users[$kto]['monsters']-=6;
		}
		break;

 		case '117':
		{
         	$users[$kto]['bricks']-=5;
         	$users[$kto]['gems']-=5;
         	$users[$kto]['monsters']-=5;
         	$users[$kogo]['bricks']-=5;
         	$users[$kogo]['gems']-=5;
         	$users[$kogo]['monsters']-=5;
         	attack_enemy($users,$kogo,6);
         	$users[$kto]['monsters']-=7;
		}
		break;

 		case '118':
		{
			if ($users[$kogo]['wall']==0)
            	attack_enemy($users,$kogo,10);
            else
            	attack_enemy($users,$kogo,6);
         	$users[$kto]['monsters']-=8;
		}
		break;

 		case '119':
		{
         	attack_enemy($users,$kogo,9);
         	$users[$kto]['monsters']-=9;
		}
		break;

		case '120':
		{
			if ($users[$kogo]['wall']>0)
            	attack_enemy($users,$kogo,10);
            else
            	attack_enemy($users,$kogo,7);
         	$users[$kto]['monsters']-=11;
		}
		break;

 		case '121':
		{
			if ($users[$kto]['gems_add']>$users[$kogo]['gems_add'])
            	attack_enemy($users,$kogo,12);
            else
            	attack_enemy($users,$kogo,8);
         	$users[$kto]['monsters']-=9;
		}
		break;

 		case '122':
		{
			if ($users[$kto]['wall']>$users[$kogo]['wall'])
         		$users[$kogo]['tower']-=6;
            else
            	attack_enemy($users,$kogo,6);
         	$users[$kto]['monsters']-=10;
		}
		break;

 		case '123':
		{
       		$users[$kogo]['tower']-=5;
       		$users[$kogo]['monsters']-=8;
         	$users[$kto]['monsters']-=14;
		}
		break;

 		case '124':
		{
       		$users[$kogo]['bricks_add']-=1;
            attack_enemy($users,$kogo,8);
         	$users[$kto]['monsters']-=11;
		}
		break;

 		case '125':
		{
			$gems_lose = min(10,$users[$kogo]['gems']);
			$bricks_lose = min(6,$users[$kogo]['bricks']);
       		$users[$kogo]['bricks']-=6;
       		$users[$kogo]['gems']-=10;
       		$users[$kto]['bricks']+=floor($gems_lose/2);
       		$users[$kto]['gems']+=floor($bricks_lose/2);
         	$users[$kto]['monsters']-=12;
		}
		break;

 		case '126':
		{
       		$users[$kto]['wall']+=4;
            attack_enemy($users,$kogo,10);
         	$users[$kto]['monsters']-=15;
		}
		break;

 		case '127':
		{
       		$users[$kogo]['monsters_add']-=1;
       		$users[$kogo]['monsters']-=5;
            attack_enemy($users,$kogo,10);
         	$users[$kto]['monsters']-=17;
		}
		break;

 		case '128':
		{
       		$users[$kogo]['monsters_add']-=1;
       		$users[$kogo]['gems']-=10;
            attack_enemy($users,$kogo,20);
         	$users[$kto]['monsters']-=25;
		}
		break;

 		case '129':
		{
			$users[$kto]['gems']+=1;
            attack_enemy($users,$kogo,3);
            $users[$kto]['monsters']-=2;
		}
		break;

 		case '130':
		{
            attack_enemy($users,$kogo,8);
            $users[$kto]['tower']-=3;
            $users[$kto]['monsters']-=4;
		}
		break;

 		case '131':
		{
            attack_enemy($users,$kogo,13);
            $users[$kto]['gems']-=3;
            $users[$kto]['monsters']-=13;
		}
		break;

		case '132':
		{
			$users[$kogo]['tower']-=12;
			$users[$kto]['monsters']-=18;
		}
		break;

		case '133':
		{
            if ($users[$kto]['wall']>$users[$kogo]['wall'])
            {
                attack_enemy($users,$kogo,3);
            }
            else
            {
                attack_enemy($users,$kogo,2);
            }
			$users[$kto]['monsters']-=2;
		}
		break;

		case '134':
		{
			$users[$kto]['monsters']-=10;
			$users[$kogo]['gems_add']-=1;
			$users[$kto]['gems']+=7;
		}
		break;

	}
    $users[$kto]['bricks']		=max(0,$users[$kto]['bricks']);
    $users[$kto]['bricks_add']	=max(0,$users[$kto]['bricks_add']);
    $users[$kto]['gems']		=max(0,$users[$kto]['gems']);
    $users[$kto]['gems_add']	=max(0,$users[$kto]['gems_add']);
    $users[$kto]['monsters']	=max(0,$users[$kto]['monsters']);
    $users[$kto]['monsters_add']=max(0,$users[$kto]['monsters_add']);
    $users[$kogo]['bricks']		=max(0,$users[$kogo]['bricks']);
    $users[$kogo]['bricks_add']	=max(0,$users[$kogo]['bricks_add']);
    $users[$kogo]['gems']		=max(0,$users[$kogo]['gems']);
    $users[$kogo]['gems_add']	=max(0,$users[$kogo]['gems_add']);
    $users[$kogo]['monsters']	=max(0,$users[$kogo]['monsters']);
    $users[$kogo]['monsters_add']=max(0,$users[$kogo]['monsters_add']);
}

function alt_card($card)
{
        switch ($card)
        {
               case 1:
               {
					  return '��� ������ ������ �� 8 �������� (0@)';
                      break;
               }
               case 2:
               {
					  return '+2 ������� +2 �������������. ������ ��� ��� (0@)';
                      break;
               }
               case 3:
               {
                      return '+1 �����. ������ ��� ��� (1@)';
                      break;
               }
               case 4:
               {
                      return '+1 ������ (3@)';
                      break;
               }
	       	   case 5:
               {
                      return '���� ������� < �������� �����, �� +2 �������, ����� +1 ������ (4@)';
                      break;
               }
               case 6:
               {
                      return '+4 ����� +1 ������ (7@)';
                      break;
               }
               case 7:
               {
                      return '+5 �����. �� ������� 6 �������������� (2@)';
                      break;
               }
               case 8:
               {
                      return '���� ������� < �������� ����� �� ������� ���������� = �������� ����� (5@)';
                      break;
               }
               case 9:
               {
                      return '+3 ����� (2@)';
                      break;
               }
               case 10:
               {
                      return '+4 ����� (3@)';
                      break;
               }
               case 11:
               {
                      return '+4 ������������� ��� ������ �������� +1 ������ (2@)';
                      break;
               }
               case 12:
               {
					  return '���� �����=0, +6 �����, ����� +3 ����� (3@)';
                      break;
               }
               case 13:
               {
                      return '��� ����� �������� � ��. ����� (7@)';
                      break;
               }
               case 14:
               {
                      return '+1 �����. ������ ��� ��� (8@)';
                      break;
               }
               case 15:
               {
                      return '��� ������ ������ 1 ������ (0@)';
                      break;
               }
               case 16:
               {
                      return '+6 ����� (5@)';
                      break;
               }
               case 17:
               {
                      return '-1 ������ ����� (4@)';
                      break;
               }
               case 18:
               {
                      return '+2 ������� (6@)';
                      break;
               }
               case 19:
               {
                      return '-1 ������, +10 �����, +5 �������������� (0@)';
                      break;
               }
               case 20:
               {
                      return '+8 ����� (8@)';
                      break;
               }
               case 21:
               {
                      return '+7 ����� +7 �������������� (9@)';
                      break;
               }
               case 22:
               {
                      return '+6 ����� +3 ����� (11@)';
                      break;
               }
               case 23:
               {
                      return '+12 ����� (13@)';
                      break;
               }
               case 24:
               {
                      return '+8 ����� +5 ����� (15@)';
                      break;
               }
               case 25:
               {
                      return '+15 ����� (16@)';
                      break;
               }
               case 26:
               {
                      return '+6 ����� 10 ��. ����� ����� (18@)';
                      break;
               }
               case 27:
               {
                      return '+20 ����� +8 ����� (24@)';
                      break;
               }
               case 28:
               {
                      return '+9 ����� �� ������� 5 ������ (7@)';
                      break;
               }
               case 29:
               {
                      return '+5 ����� +1 �������� (9@)';
                      break;
               }
               case 30:
               {
                      return '+1 ����� +1 ����� +2 ����� (1@)';
                      break;
               }
               case 31:
               {
					  return '����� � ������� ������ ������� -1 �������� 2 ��. ����� ����� (6@)';
                      break;
               }
               case 32:
               {
					  return '+6 ������ +6 ����� ���� �������� < �������� ����� �� +1 �������� (10@)';
                      break;
               }
               case 33:
               {
                      return '+ 7 ����� 6 ��. ����� ����� (14@)';
                      break;
               }
               case 34:
               {
                      return '���� � ��������� ����� �������� ������� (17@)';
                      break;
               }
               case 35:
               {
                      return '-1 ������ �����, -1 �������� �����, -5 ������ �����, 5 ��. ����� ����� ����� (20@)';
                      break;
               }
               case 36:
               {
                      return '';
                      break;
               }
               case 37:
               {
                      return '';
                      break;
               }
               case 38:
               {
                      return '';
                      break;
               }
               case 39:
               {
                      return '';
                      break;
               }
               case 40:
               {
                      return '';
                      break;
               }
               case 41:
               {
                      return '';
                      break;
               }
               case 42:
               {
                      return '';
                      break;
               }
               case 43:
               {
                      return '';
                      break;
               }
               case 44:
               {
                      return '';
                      break;
               }
	       	   case 45:
               {
                      return '';
                      break;
               }
               case 46:
               {
                      return '';
                      break;
               }
               case 47:
               {
                      return '';
                      break;
               }
               case 48:
               {
                      return '';
                      break;
               }
               case 49:
               {
                      return '';
                      break;
               }
               case 50:
               {
                      return '+1 ����� ������ ��� ��� (1*)';
                      break;
               }
               case 51:
               {
                      return '1 ��. ����� ����� �����. ������ ��� (2*)';
                      break;
               }
               case 52:
               {
                      return '+3 ����� (2*)';
                      break;
               }
               case 53:
               {
                      return '+1 ����� (3*)';
                      break;
               }
               case 54:
               {
                      return '�������� 1 ����� �������� 1 ����� ������ ��� (2*)';
                      break;
               }
	       	   case 55:
               {
                      return '+3 ����� ����� ������ �������� (5*)';
                      break;
               }
               case 56:
               {
					  return '+2 ����� 2 ��. ����� ����� ����� (4*)';
                      break;
               }
               case 57:
               {
                      return '+1 ����� +3 ����� +1 ����� ����� (6*)';
                      break;
               }
               case 58:
               {
					  return '3 ��. ����� ����� ����� (2*)';
                      break;
               }
               case 59:
               {
                      return '+5 ����� (3*)';
                      break;
               }
               case 60:
               {
					  return '5 ��. ����� ����� ����� (4*)';
                      break;
               }
               case 61:
			   {
					  return '5 ��. ����� ����� ����� +2 ����� (3*)';
                      break;
               }
               case 62:
               {
                      return '+1 ����� +3 ����� +3 ����� (7*)';
                      break;
               }
               case 63:
               {
                      return '����� ���� ������� ���������� ����� ����� ����������� (7*)';
                      break;
               }
               case 64:
               {
					  return '+8 ����� (6*)';
                      break;
               }
	       	   case 65:
               {
                      return '+5 ����� +1 ����� (9*)';
                      break;
               }
               case 66:
               {
					  return '-1 ����� 9 ��. ����� ����� ����� (8*)';
                      break;
               }
               case 67:
               {
                      return '+5 ����� ���� ������ 6 �������� (7*)';
                      break;
               }
               case 68:
               {
                      return '+11 ����� (10*)';
                      break;
               }
               case 69:
               {
                      return '���� ������� -1 ����� � -7 ����� (5*)';
                      break;
               }
               case 70:
               {
					  return '+6 ����� 4 ��. ����� ����� ����� (13*)';
                      break;
               }
               case 71:
               {
					  return '+7 ����� �� ������� 10 �������� (4*)';
                      break;
               }
               case 72:
               {
                      return '+8 ����� +3 ����� (12*)';
                      break;
               }
               case 73:
               {
                      return '+8 ����� +1 �������� (14*)';
                      break;
               }
               case 74:
               {
                      return '+15 ����� (16*)';
                      break;
               }
	       	   case 75:
               {
                      return '+10 ����� +5 ����� +5 ������ (15*)';
                      break;
               }
               case 76:
               {
                      return '+12 ����� 6 ��. ����� (17*)';
                      break;
               }
               case 77:
               {
                      return '+20 ����� (21*)';
                      break;
               }
               case 78:
               {
					  return '+11 ����� -6 ����� (8*)';
                      break;
               }
               case 79:
               {
                      return '�� ��������� 3 �������������� +1 ����� ���� ������� (0*)';
                      break;
               }
               case 80:
               {
					  return '+13 ����� +6 ������ +6 �������� (18*)';
                      break;
               }
               case 81:
               {
                      return '���� ����� > ����� �����, �� 8 ��. ����� ����� �����, ����� ���� 8 ��. ����� (11*)';
                      break;
               }
               case 82:
               {
                      return '���� ����� <  ����� �����, �� +2 �����, ����� +1 ����� (0*)';
                      break;
               }
               case 83:
               {
					  return '+4 ����� -3 ����� 2 ��. ����� ����� ����� (5*)';
                      break;
               }
               case 84:
               {
                      return '';
                      break;
               }
	       	   case 85:
               {
                      return '';
                      break;
               }
               case 86:
               {
                      return '';
                      break;
               }
               case 87:
               {
                      return '';
                      break;
               }
               case 88:
               {
                      return '';
                      break;
               }
               case 89:
               {
                      return '';
                      break;
               }
               case 90:
               {
                      return '';
                      break;
               }
               case 91:
               {
                      return '';
                      break;
               }
               case 92:
               {
                      return '';
                      break;
               }
               case 93:
               {
                      return '';
                      break;
               }
               case 94:
               {
                      return '';
                      break;
               }
	       	   case 95:
               {
                      return '';
                      break;
               }
               case 96:
               {
                      return '';
                      break;
               }
               case 97:
               {
                      return '';
                      break;
               }
               case 98:
               {
                      return '';
                      break;
               }
               case 99:
               {
                      return '';
                      break;
               }
               case 100:
               {
					  return '��� ������ ������ 6 ������ (0$)';
                      break;
               }
               case 101:
               {
                      return '2 ��. �����. ������ ��� ��� (1$)';
                      break;
               }
               case 102:
               {
                      return '4 ��. ����� -3 ������������� (1$)';
                      break;
               }
               case 103:
               {
                      return '+1 �������� (3$)';
                      break;
               }
               case 104:
               {
                      return '�������� 1 �����, �������� 1 �����. ������ ��� ��� (2$)';
                      break;
               }
	       	   case 105:
               {
                      return '6 ��. ����� �� ��������� 3 ��. ����� (3$)';
                      break;
               }
               case 106:
               {
					  return '3 ��. ����� ����� ����� �� ��������� 1 ��. ����� (4$)';
                      break;
               }
               case 107:
               {
					  return '2 ��. ����� ����� �����. ������ ��� ��� (6$)';
					  break;
               }
               case 108:
               {
                      return '5 ��. ����� (3$)';
                      break;
               }
               case 109:
               {
                      return '4 ��. ����� +3 ����� (5$)';
                      break;
               }
               case 110:
               {
					  return '4 ��. ����� ����� ����� (6$)';
                      break;
               }
               case 111:
               {
                      return '+2 �������� (7$)';
                      break;
               }
               case 112:
               {
					  return '2 ��. ����� +4 ����� +2 ����� (8$)';
                      break;
               }
               case 113:
               {
					  return '+1 ���� ��������� +3 ����� (0$)';
                      break;
               }
               case 114:
               {
                      return '6 ��. ����� (5$)';
                      break;
               }
	       	   case 115:
               {
                      return '7 ��. ����� (6$)';
                      break;
               }
               case 116:
               {
					  return '6 ��. ����� -3 ����� ����� (6$)';
                      break;
               }
               case 117:
               {
                      return '6 ��. ����� ��� ������ 5 ��������������, ��������, ������ (5$)';
                      break;
               }
               case 118:
               {
                      return '���� ����� ����� =0 - 10 ��. �����, ����� 6 ��. ����� (8$)';
                      break;
               }
               case 119:
               {
                      return '9 ��. ����� (9$)';
                      break;
               }
               case 120:
               {
                      return '���� ����� ����� >0 10 ��. �����, ����� 7 ��. ����� (11$)';
                      break;
               }
               case 121:
               {
                      return '���� ����� > ����� �����, 12 ��. �����, ����� 8 ��. ����� (9$)';
                      break;
               }
               case 122:
               {
                      return '���� ����� > ����� �����, 6 ��. ����� ����� ����� ����� 6 ��. ����� (10$)';
                      break;
               }
               case 123:
               {
                      return '5 ��. ����� ����� ����� -8 ������ ����� (14$)';
                      break;
               }
               case 124:
               {
                      return '5 ��. ����� -1 ������ ����� (11$)';
                      break;
               }
	       	   case 125:
               {
                      return '���� ������ 10 �������������� 6 ��������. �� ��������� 1/2 �� ����������� (12$)';
                      break;
               }
               case 126:
               {
                      return '10 ��. ����� +4 ����� (15$)';
                      break;
               }
               case 127:
               {
                      return '10 ��. ����� -5 ������ ����� -1 �������� ����� (17$)';
                      break;
               }
               case 128:
               {
                      return '20 ��. ����� ���� ������ 10 �������������� � 1 �������� (25$)';
                      break;
               }
               case 129:
               {
                      return '3 ��. ����� +1 ������������� (2$)';
                      break;
               }
               case 130:
               {
                      return '8 ��. ����� �� ��������� 3 ��. ����� ����� (4$)';
                      break;
               }
               case 131:
               {
                      return '13 ��. ����� -3 ������������� (13$)';
                      break;
               }
               case 132:
               {
                      return '12 ��. ����� ����� ����� (18$)';
                      break;
               }
			   case 133:
			   {
					  return '���� ����� > ����� �����, 3 ��. �����, ����� 2 ��. ����� (2$)';
					  break;
			   }

			   case 134:
			   {
					  return '+7 ��������������, -1 ����� ���������� (10$)';
					  break;
			   }

		}
}
?>