<?
define('money_weight',0);
define('domain_name', $_SERVER['HTTP_HOST']);
if (domain_name=='localhost')
{
    define('PHPRPG_DB_HOST', 'localhost');
    define('PHPRPG_DB_NAME', 'ageofwar_game');
    define('PHPRPG_DB_USER', 'root');
    define('PHPRPG_DB_PASS', '');
}
else
{
    define('PHPRPG_DB_HOST', 'localhost');
    define('PHPRPG_DB_NAME', 'gamerpgsu');
    define('PHPRPG_DB_USER', 'gamerpgsu');
    define('PHPRPG_DB_PASS', 'wYpxNsczNPVtr4Pd');
}
if (domain_name=='localhost')
{
    myquery ("set character_set_client='cp1251'");
    myquery ("set character_set_results='cp1251'");
    myquery ("set collation_connection='cp1251_general_ci'");
}

$db = mysql_pconnect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

$cur_time=time()-172800;
echo '�������� ������� � �����<br>';
$sel = myquery("INSERT INTO game_users_archive SELECT * FROM game_users WHERE game_users.delay<'".$cur_time."'");
$del = myquery("DELETE FROM game_users WHERE game_users.delay<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_users");

//������� ����������
//$cur_time=time()-60*60*24*45;
//$del = myquery("DELETE FROM game_stat WHERE time<'".$cur_time."'");
//$opt = myquery("OPTIMIZE TABLE game_stat");

//������� ����������
//$cur_time=time()-60*60*24*45;
//$del = myquery("DELETE FROM game_activity WHERE time<'".$cur_time."'");
//$opt = myquery("OPTIMIZE TABLE game_activity");


//������� ������������ ����������� �������
echo '������� ������������ ����������� �������<br>';
$cur_time=time()-60*60*24*2;
$del = myquery("DELETE FROM game_users_reg WHERE rego_time<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_users_reg");

//������� ����� ����
echo '������� ����� ����<br>';
$cur_time=time()-60*60*24*30;
$del = myquery("DELETE FROM game_combats_log WHERE time<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_combats_log");

//����������� ��������� ����� � �������� � ��������� � ����� �� ��� ��������
echo '��������� ��������<br>';
$time_for_check = time()-604800;
$select=myquery("SELECT * FROM game_items_old where priznak='1' and type!='' and (sell_time<'$time_for_check' or last_price=max_price) ORDER BY sell_time DESC");
while ($item=mysql_fetch_array($select))
{
        $check = myquery("SELECT user_id FROM game_users WHERE name='".$item['name']."'");
        if (!mysql_num_rows($check)) $check = myquery("SELECT user_id FROM game_users_archive WHERE name='".$item['name']."'");
        list($user_id_sell)=mysql_fetch_array($check);
        if ($user_id_sell!=$item['last_user'])
        {
            //������� ��� ������ ���-��
            $userid =  $item['last_user'];
            $it = $item['id'];
            $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p from game_items_old where priznak='1' and id='$it'");
            list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p)=mysql_fetch_array($sct);

            $result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
            $result=myquery("update game_users set CW=CW+$weight where user_id='$userid'");

            $result=myquery("update game_users set GP=GP+'".$item['last_price']."',CW=CW+'".($item['last_price']*money_weight)."' where name='$name'");
            $result=myquery("update game_users_archive set GP=GP+'".$item['last_price']."',CW=CW+'".($item['last_price']*money_weight)."' where name='$name'");

            $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
            list($rustown)=mysql_fetch_array($town_select);

            $check = myquery("SELECT name FROM game_users WHERE user_id='".$item['last_user']."'");
            if (!mysql_num_rows($check)) $check = myquery("SELECT name FROM game_users_archive WHERE user_id='".$item['last_user']."'");
            list($name_buy)=mysql_fetch_array($check);

            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name_buy', '�������������', '�������', '�� ����� ��� ".$ident." �� �������� � ".$rustown."! �� ��������� � ���� ���������','0','".time()."')");
            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name', '�������������', '�������', '���� ��� ".$ident." ��� ������ �� �������� � ".$rustown."! � ���� ������� ���������� ����� ������� ��������: ".$item['last_price']."','0','".time()."')");

            $delete=myquery("delete from game_items_old where priznak='1' and id='$it'");

            echo '������� '.$ident.' ������ ������� '.$name_buy.' �� '.$item['last_price'].'. �������� '.$name.'<br>';
        }
        else
        {
            //������� �� ��������. ������ ���������� ��� ���������
            $userid =  $user_id_sell;
            $it = $item['id'];
            $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p from game_items_old where priznak='1' and id='$it'");
            list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p)=mysql_fetch_array($sct);

            $result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
            $result=myquery("update game_users set CW=CW+$weight where user_id='$userid'");
            $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
            list($rustown)=mysql_fetch_array($town_select);
            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name', '�������������', '�������', '���� ��� ".$ident." ���� � ������� �� �������� � ".$rustown." ��� ��� �� �� ���������� � ������ � ������� ������ (�� ���� ��� ��� � �� ������� ����������!) ! �� ��������� � ���� ���������','0','".time()."')");
            $delete=myquery("delete from game_items_old where priznak='1' and id='$it'");
            echo '������� '.$ident.' ����� �� ������. �������� '.$name.'<br>';
        }
}


/*
//������� �����
$sel = myquery("SELECT name FROM game_users");
while (list($name)=mysql_fetch_array($sel))
{
    //����������� ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //�������� ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //������������ ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }

    //��������� ����������� ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //��������� �������� ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //��������� ������������ ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }
}
$sel = myquery("SELECT name FROM game_users_archive");
while (list($name)=mysql_fetch_array($sel))
{
    //����������� ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //�������� ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //������������ ������
    $sel_pm = myquery("SELECT * FROM game_pm WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }

    //��������� ����������� ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //��������� �������� ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //��������� ������������ ������
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }
}
$opt = myquery("OPTIMIZE TABLE game_pm");
$opt = myquery("OPTIMIZE TABLE game_pm_deleted");
*/

?>