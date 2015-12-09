<?php
//header("Expires: Mon, 6 Dec 1977 00:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
//header("Cache-Control: no-cache, must-revalidate");
//header("Pragma: no-cache");

if (function_exists("start_debug")) start_debug(); 

if ($adm['stat'] != 1)
{
    setLocation('index.php');
    {if (function_exists("save_debug")) save_debug(); exit;}
}
else
{
    require('inc/template_header.inc.php');

    echo '<center>Просмотр статистики';

    echo '<form action="" method="post">
    [<a href="?opt=main&option=stat"> Всего</a>  | <a href="?opt=main&option=stat&type=unique">Последние посещения</a> | <a href="?opt=main&option=stat&&type=toprefer">Топ посетителей</a> | <a href="?opt=main&option=stat&type=host">Статистика хостов</a> | <a href="?opt=main&option=stat&type=agent">Браузеры и ОС</a> | <a href="?opt=main&option=stat&type=daily">Хиты по дням</a>]</center><br>
    Просмотр статистики по игроку: <input id="name_v" type="text" size=30>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value+\'&priv\'">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Пересечения игрока" onClick="location.href=\'admin.php?opt=main&option=stat&name_v=\'+document.getElementById(\'name_v\').value+\'&cros\'"><br>
    Просмотр статистики по IP: <input id="ip_v" type="text" size=20>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v=\'+document.getElementById(\'ip_v\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v=\'+document.getElementById(\'ip_v\').value+\'&priv\'"><br>
	  Просмотр статистики по динамическому IP: <input id="ip_v1" type="text" size=20>&nbsp;&nbsp;&nbsp;<input id="ip_v2" type="text" size=20>&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Детально" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v1=\'+document.getElementById(\'ip_v1\').value+\'&ip_v2=\'+document.getElementById(\'ip_v2\').value">&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Кратко" onClick="location.href=\'admin.php?opt=main&option=stat&ip_v1=\'+document.getElementById(\'ip_v1\').value+\'&ip_v2=\'+document.getElementById(\'ip_v2\').value+\'&priv\'"><br>
    Просмотр статистики по одинаковым IP: <input name="" type="button" value="Смотреть" onClick="location.href=\'admin.php?opt=main&option=stat&ip\'"></form><br><br><center>';

    if (isset($_GET['type']))
      $type = $_GET['type'];
    else
      $type = '';
    
    if (!isset($_GET['page']))
      $page = 1;
    else
      $page = (int)$_GET['page'];
    $line=25;

            
    switch ($type) 
    {
    case 'daily':
      $result = myquery("SELECT DISTINCT day, COUNT(*) AS refcount FROM game_activity GROUP BY day ORDER BY day DESC");
      echo '
      Хиты по дням<br>
      <table cellspacing="5" cellpadding="0" border="0" class="striped">
      <tr><td><font size="2" color="#eeeeee">Дата</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
      $i = 0;
      while ($report = mysql_fetch_array($result))
      {
        echo '<tr><td>' . $report['day'] . '</td><td><div align="right">' . $report['refcount'] . '</div></td></tr>';
      }
      echo '</table>';
      break;

    case 'agent':
      $result = myquery("SELECT DISTINCT agent, COUNT(*) AS refcount FROM game_activity GROUP BY host ORDER BY refcount DESC");
      echo '
      Статистика браузеров и ОС<br>
      <table cellspacing="5" cellpadding="0" border="0" class="striped">
      <tr><td><font size="2" color="#eeeeee">Браузеры и ОС</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
      while ($report = mysql_fetch_array($result))
      {
        $browser = get_browser($report['agent'], true);
        echo '<tr>
        <td>' . $browser['platform'] . ((isset($browser['win64']) && $browser['win64']) ? ' (x64) / ' : ' / '). $browser['browser'] . ' ' . $browser['version']. '</td>
        <td><div align="right">' . $report['refcount'] . '</div></td>
        </tr>';
      }
      echo '</table>';
      break;

    case 'host':
      $result = myquery("SELECT DISTINCT host, COUNT(*) AS refcount FROM game_activity GROUP BY host ORDER BY refcount DESC");
      echo '
      Статистика хостов<br>
      <table cellspacing="5" cellpadding="0" border="0" class="striped">
      <tr><td><font size="2" color="#eeeeee">Хост</font></td><td><font size="2" color="#eeeeee"><div align="right">Хиты</div></font></td></tr>';
      $i = 0;
      while ($report = mysql_fetch_array($result))
      {
        if ($i == 0)
        {
          echo '<tr><td>' . number2ip($report['host']) . '</td><td><div align="right">' . $report['refcount'] . '</div></td></tr>';
          $i = 1;
        }
        elseif ($i == 1)
        {
          echo '<tr><td>' . number2ip($report['host']) . '</td><td><div align="right">' . $report['refcount'] . '</div></td></tr>';
          $i = 0;
        }
      }
      echo '</table>';
      break;

    case 'toprefer':
      $result = myquery("SELECT DISTINCT name, COUNT(*) AS refcount FROM game_activity GROUP BY name ORDER BY refcount DESC, name");
      echo '
      Топ посетителей<br>
      <table cellspacing="5" cellpadding="0" border="0" class="striped">
      <tr><td><font size="2" color="#eeeeee">Больше всего посетили</font></td><td><font size="2" color="#eeeeee"><div align="right">хитов</div></font></td></tr>';
      $i = 0;
      while ($report = mysql_fetch_array($result))
      {
        if ($report['name'] != '')
        {
          if ($i == 0)
          {
            echo '<tr><td>' . $report['name'] . '</td><td><div align="right">' . $report['refcount'] . '</div></td></tr>';
            $i = 1;
          }
          elseif ($i == 1)
          {
            echo '<tr><td>' . $report['name'] . '</td><td><div align="right">' . $report['refcount'] . '</div></td></tr>';
            $i = 0;
          }
        }
      }
      echo '</table>';
      break;

    case 'del':
      //$update=myquery("delete from game_activity");
      break;

    case 'unique':
      $result = myquery("SELECT COUNT(*) FROM game_activity");
      $count = mysql_result($result,0,0);
      echo "Последние посещения: $count<br><br>";

      $query = "SELECT * FROM game_activity ORDER BY time DESC";
      $query1 = "SELECT COUNT(*) FROM game_activity ORDER BY time DESC";

    default:
      if ($type != 'unique')
      {
        $result = myquery("SELECT COUNT(*) AS hits FROM game_activity");
        $countarray = mysql_fetch_array($result);
        $count = $countarray['hits'];
        echo "Всего посещений: $count<br><br>";
        if(isset($_GET['name_v']) and $_GET['name_v']!='')
        {
          if (isset($_GET['priv']))
          {
            $query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE name='".$_GET['name_v']."' GROUP BY name, host";
          }
          elseif (isset($_GET['cros']))
          {
            $query = "SELECT max(t1.day) AS day, max(t1.time) AS time, t1.host, t1.name 
                FROM game_activity as t1
                Join game_activity as t2 On t1.host=t2.host and t2.name='".$_GET['name_v']."' and t1.name<>'".$_GET['name_v']."'
                GROUP BY name, host";
          }
          else
          {
            $query = "SELECT * FROM game_activity WHERE name='".$_GET['name_v']."' ORDER BY time DESC";
          }
        }
        elseif(isset($_GET['ip_v']) and $_GET['ip_v']!='')
        {
          $ip = ip2number($_GET['ip_v']);
          if (isset($_GET['priv']))
          {
            $query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE host='$ip' GROUP BY name, host";
          }
          else
          {
            $query = "SELECT * FROM game_activity WHERE host='$ip' ORDER BY time DESC";
          }
        }
        elseif(isset($_GET['ip_v1']) and $_GET['ip_v1']!='' and isset($_GET['ip_v2']) and $_GET['ip_v2']!='')
        {
          $ip1 = ip2number($_GET['ip_v1']);
          $ip2 = ip2number($_GET['ip_v2']);
          if (isset($_GET['priv']))
          {
            $query = "SELECT max(day) AS day, max(time) AS time, host, name FROM game_activity WHERE host>='$ip1' and host<='$ip2' GROUP BY name, host";
          }
          else
          {
            $query = "SELECT * FROM game_activity WHERE host>='$ip1' and host<='$ip2' ORDER BY time DESC";
          }
        }
        elseif (isset($_GET['ip']))
        {
          if(isset($_POST['mhost']) and $_POST['mhost']!='')
          {
            echo 'Детализация мультов по ip:'.long2ip($mhost);
            echo '<table cellspacing="0" cellpadding="2" border="1" class="striped">';
            echo '<tr style="color:white;text-weight:800;text-align:center;"><td>Имя</td><td>Последний хост</td><td>Дата последнего захода</td><td>Доп.Хост</td></tr>';
            $query = "SELECT unif.*
                    FROM
                    (
                    (SELECT game_activity_mult.host_more,game_users.clevel,game_users_active.host,game_users_data.last_visit,game_activity_mult.name,game_users.clan_id,game_users.user_id FROM game_activity_mult,game_users,game_users_data,game_users_active WHERE game_users.user_id=game_users_data.user_id AND game_users.user_id=game_users_active.user_id AND game_users.name=game_activity_mult.name AND game_activity_mult.host='".$mhost."' GROUP BY game_activity_mult.host,game_activity_mult.host_more,game_activity_mult.name)
                    UNION
                    (SELECT game_activity_mult.host_more,game_users_archive.clevel,game_users_active.host,game_users_data.last_visit,game_activity_mult.name,game_users_archive.clan_id,game_users_archive.user_id FROM game_activity_mult,game_users_archive,game_users_data,game_users_active WHERE game_users_archive.user_id=game_users_data.user_id AND game_users_archive.user_id=game_users_active.user_id AND game_users_archive.name=game_activity_mult.name AND game_activity_mult.host='".$mhost."' GROUP BY game_activity_mult.host,game_activity_mult.host_more,game_activity_mult.name)
                    ) as unif
                    ORDER BY unif.clevel DESC";
            $sel_ip = myquery($query);

            $allpage=ceil(mysql_num_rows($sel_ip)/$line);
            if ($page>$allpage) $page=$allpage;
            if ($page<1) $page=1;

            $query.=" limit ".(($page-1)*$line).", $line";
            $sel_ip = myquery($query);
            while ($ch_ip = mysql_fetch_array($sel_ip))
            {
              echo '<tr><td>';
              if($ch_ip['clan_id']>0) echo'<img src="http://images.rpg.su/clan/'.$ch_ip['clan_id'].'.gif">';
              echo '<a href="/view/?userid='.$ch_ip['user_id'].'" target="_blank">'.$ch_ip['name'].'</a> ['.$ch_ip['clevel'].']</td><td>'.long2ip($ch_ip['host']).'</td><td>'.date('d.m.Y : H:i:s', $ch_ip['last_visit']).'</td><td>'.$ch_ip['host_more'].'</td></tr>';

            }
                $href = "?opt=main&option=stat&mhost=".$mhost."&ip";
          }
          else
          {
            echo 'Проверка игроков по IP';
            echo '<table cellspacing="0" cellpadding="2" border="1" class="striped">';
            echo '<tr style="color:white;text-weight:800;text-align:center;"><td>Хост</td><td>Доп. Хост</td><td>Количество разных игроков</td></tr>';
            $query = "SELECT count(mult.name) as cnt,host,host_more FROM (SELECT host,host_more,name FROM game_activity_mult GROUP BY host,host_more,name) AS mult GROUP BY mult.host,mult.host_more HAVING count( mult.name ) >1 ORDER BY cnt DESC";
            $sel_ip = myquery($query);

            $allpage=ceil(mysql_num_rows($sel_ip)/$line);
            if ($page>$allpage) $page=$allpage;
            if ($page<1) $page=1;

            $query.=" limit ".(($page-1)*$line).", $line";
            $sel_ip = myquery($query);
            while ($ch_ip = mysql_fetch_array($sel_ip))
            {
              echo '<tr><td><a href="admin.php?opt=main&option=stat&mhost='.$ch_ip['host'].'&ip">'.number2ip($ch_ip['host']).'</a></td><td>'.$ch_ip['host_more'].'</td><td>'.$ch_ip['cnt'].'</td></tr>';

            }
                $href = "?opt=main&option=stat&ip";
          }
          echo '</table>';
              echo'<center>Страница: ';
                show_page($page,$allpage,$href);
        }
        else
        {
          $query = "SELECT * FROM game_activity ORDER BY time DESC";
        }
      }

      if (!isset($_GET['ip']))
      {
        $result = myquery($query);
        if ($result!=false AND mysql_num_rows($result))
        {
          $allpage=ceil(mysql_num_rows($result)/$line);
          if ($page>$allpage) $page=$allpage;
          if ($page<1) $page=1;

          $query.=" limit ".(($page-1)*$line).", $line";
          $result = myquery($query);
  
          echo 'Последние посещения<br>
            <table cellspacing="1" cellpadding="0" border="1" class="striped">';
          if (isset($_GET['priv']) or isset($_GET['cros']))
          {
            echo'<tr align="center">
            <td width="120"><font size="2" color="#eeeeee">Игрок</font></td>
            <td width="80"><font size="2" color="#eeeeee">Хост</font></td>
            <td width="100"><font size="2" color="#eeeeee">Дата  и Время</font></font></td>
            </tr>';

            $i = 0;
            while ($report = mysql_fetch_array($result))
            {
              echo '<tr align="center">
              <td>' . $report['name'] . '</td>
              <td>' . number2ip($report['host']). '</td>
              <td>' . date("d M Y H:i", $report['time']) . '</td>
              </tr>';
            }
          }
          else
          {
            echo '
            <tr align="center">
            <td><font size="2" color="#eeeeee">Дата и Время</font></td>
            <td><font size="2" color="#eeeeee">ОС</font></font></td>
            <td><font size="2" color="#eeeeee">Тип</font></td>
            <td><font size="2" color="#eeeeee">Браузер</font></td>
            <td><font size="2" color="#eeeeee"><div align="center">Версия</div></font></td>
            <td><font size="2" color="#eeeeee">Хост</font></td>
            <td><font size="2" color="#eeeeee">Линк</font></td>
            <td><font size="2" color="#eeeeee">Игрок</font></td>
            </tr>';
            $i = 0;
            while ($report = mysql_fetch_array($result))
            {
              $browser = get_browser($report['agent'], true);

              echo '<tr align="center">
                <td>' . date("d M Y H:i", $report['time']) . '</td>
                <td>' . $browser['platform'] . ((isset($browser['win64']) && $browser['win64']) ? ' (x64)' : '') . '</td>
                <td>' . ((isset($browser['ismobiledevice']) && $browser['ismobiledevice']) ? 'mobile' : '') . '</td>
                <td>' . $browser['browser'] . '</td>
                <td><div align="center">' . $browser['version'] . '</div></td>
                <td>' . number2ip($report['host']). '</td>
                <td>' . $report['ref'] . '</td>
                <td>' . $report['name'] . '</td>
              </tr>';
            }
          }
          echo '</table>';

          if (isset($_GET['type']) AND $_GET['type'] == 'unique')
            $href = "?opt=main&option=stat&type=unique";
          else
          {
            if(isset($_GET['name_v']) and $_GET['name_v']!='')
            {
              $href = "?opt=main&option=stat&name_v=".$_GET['name_v']."";
            }
            elseif(isset($_GET['ip_v']) and $_GET['ip_v']!='')
            {
              $href = "?opt=main&option=stat&ip_v=".$_GET['ip_v']."";
            }
            elseif(isset($_GET['ip_v1']) and $_GET['ip_v1']!='' and isset($_GET['ip_v2']) and $_GET['ip_v2']!='')
            {
              $href = "?opt=main&option=stat&ip_v1=$".$_GET['ip_v1']."&ip_v2=$".$_GET['ip_v2']."";
            }
            /*elseif(isset($ip_v1) and $ip_v1!='')
            {
              $href = "?opt=main&option=stat&ip_v1=$ip_v1";
            }*/
            else
            {
              $href = "?opt=main&option=stat";
            }
            if (isset($_GET['priv']))
            {
              $href=$href.'&priv';
            }
            elseif (isset($_GET['cros']))
            {
              $href=$href.'&cros';
            }
          }
          echo'<center>Страница: ';
          show_page($page,$allpage,$href);
        }
      }
    }
}

if (function_exists("save_debug")) save_debug(); 

?>