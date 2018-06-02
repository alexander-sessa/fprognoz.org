<?php
$season = $s;
$country_code = 'FCL';
$tour = 'FCL'.$t;
$rprognoz = '';
$hidden = 'прогноз не показан';
$stat = false;

if (isset($_POST['team_code']) && trim($_POST['team_code']))
  $team_code = $_POST['team_code'];
else if (isset($c))
  $team_code = $c;

if (!isset($team_code))
  $team_code = '';

$acodes = file($online_dir.'FCL/'.$s.'/codes.tsv');
$teams = array();

foreach ($acodes as $scode) if ($scode[0] != '#')
{
  $ateams = explode('	', ltrim($scode, '-'));
  $ta = explode('@', $ateams[0]);
  $teams[trim($ta[0])] = trim($ateams[1]);
  if (trim($ta[0]) == $team_code)
  {
    $name = trim($ateams[2]);
    $email = trim($ateams[3]);
  }
}
if (($role != 'badlogin') && isset($_POST['submitpredict']) && ($prognoz = trim($_POST['prognoz_str'])))
{ // отправка прогноза
  $replyto = '';
  $time = time();
  if ($email)
  {
    $amail = explode(' ', str_replace(',', ' ', $email));
    foreach ($amail as $eml) if ($eml = trim($eml))
      if (strpos($eml, '@'))
        $replyto = "\nReply-To: $name <$eml>";
  }
  @mail('fp@fprognoz.org', strtoupper($ccn[$country_code]), "FP_Prognoz\n$team_code\n$tour\n$prognoz\n",
'From: '. $team_code .' <fp@fprognoz.org>'. $replyto .'
Date: '. date('r', $time) .'
MIME-Version: 1.0
Content-Type: text/plain;
        charset="koi8-r"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 3.05.151004-'. $ip);
  echo 'Прогноз отправлен на официальный адрес fp@fprognoz.org.<br />
';
  if (!is_dir())
    mkdir($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour, 0755, true);

  list($prog, $pena) = explode('  ', $prognoz);
  if (is_file($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/mail')) {
    $timer = 0;
    while (is_file($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/lock') && $timer < 5) {
      echo $timer++;
      sleep(1);
    }
    if ($timer < 5) {
      touch($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/lock');
      $content = file_get_contents($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/mail');
      file_put_contents($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/mail', $content .
                         $team_code .';'. $prog .';'. $time .';'. strtoupper($pena) ."\n");
      unlink($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/lock');
    }
    else
      echo 'В течение 5 минут прогноз должен появиться в списке полученных.<br />
';

  }
  else
    file_put_contents($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/mail', $team_code .';'. $prog .';'. $time .';'. strtoupper($pena). "\n");

  if ($email)
  {
    echo 'Контрольная копия: ';
    send_email($team_code.' <fp@fprognoz.org>', $name, $email, strtoupper($ccn[$country_code]), "FP_Prognoz\n$team_code\n$tour\n$prognoz\n");
  }
}
// формирование показа программки тура с реальными результатами
if (is_dir($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour)) {
  if (is_file($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/published'))
    $publish = true;
  else
    $publish = false;

  if (is_file($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/closed'))
    $closed = true;
  else
    $closed = false;

  // парсинг программки
  $programm = file_get_contents($online_dir.$country_code.'/'.$season.'/programms/'.$tour);
  $fr = strpos($programm, "$tour ");
  $fr = strpos($programm, "\n", $fr) + 1;
  $programm = substr($programm, $fr);
  $fr = strpos($programm, "Последний с");
  $matches = explode("\n", substr($programm, 0, $fr));
  $programm = substr($programm, $fr);
  $fr = strpos($programm, '.');
  $lastdate = trim(substr($programm, $fr - 2, 5));
  if (($fr1 = strpos($programm, ':', $fr)) && ($fr1 - $fr < 50))
    $lasttm = trim(substr($programm, $fr1 - 2, 5));
  else
    $lasttm = '';

  $atemp = array();
  $calfp = explode("\n", $programm);
  $cal = '';
  foreach ($calfp as $line)
    if (strpos($line, ' - '))
      $cal .= trim($line)."\n";

  require_once('online/tournament.inc.php');
  $base = get_results($lastdate);
//  if (!isset($team_code))
  if (isset($_SESSION['Coach_name']))
    foreach ($cmd_db['FCL'] as $code => $team)
      if ($team['usr'] == $_SESSION['Coach_name'] && ($guest = strpos($cal, $team['cmd'])) !== false) {
        $team_code = $code;
        break;
      }

  if (strpos($programm, 'Матч на нейтральном поле! '))
    $neut = true;
  else
    $neut = false;

  if (strpos($programm, 'При ничьей играется доп.время и пенальти!'))
    $pena = true;
  else
    $pena = false;

  // UI
  echo "<center><script type=\"text/javascript\">//<![CDATA[
function newpredict()
{
  var i; var dd; var p=''; var ps=''; var min=0; var max=0;
  for(i=1; i<=10; i++) {
    dd = 'dice'+i;
    if (document.getElementById(dd).value) p=p+document.getElementById(dd).value;
    else p=p+'=';
    dd = 'ddice'+i;
    if (document.getElementById(dd).value) p=p+'('+document.getElementById(dd).value+')';
  }
  p=p+' ';
  for(i=11; i<=15; i++) {
    dd = 'dice'+i;
    if (document.getElementById(dd).value) p=p+document.getElementById(dd).value;
    else p=p+'=';
    dd = 'ddice'+i;
    if (document.getElementById(dd).value=='<') p=p+'<';
  }
  for(i=1; i<=15; i++) {
    dd = 'pen'+i;
    if (document.getElementById(dd).value) {
      if (min>document.getElementById(dd).value) min=document.getElementById(dd).value;
      if (max<document.getElementById(dd).value) max=document.getElementById(dd).value;
    }
  }
  for(j=1; j<=max; j++) for(i=1; i<=15; i++)
  {
    dd = 'pen'+i;
    if (document.getElementById(dd).value==j) {
      if (ps=='') ps = '  penalty - '+i; else ps = ps+','+i;
    }
  }
  document.getElementById('prognoz_str').value=p+ps;
}
function predict(id,dice)
{
  document.getElementById(id).value=dice;
  newpredict();
}
function securedice(id,dice)
{
  var i;
  var dd;
  for(i=1; i<=10; i++) {
    dd = 'ddice'+i;
    document.getElementById(dd).value='';
    if (dd == id) {
      document.getElementById(dd).disabled=false;
      document.getElementById(dd).value=dice;
    }
    else document.getElementById(dd).disabled=true;
  }
  newpredict();
}
function securehome(id)
{
  var i; var dd;
  for(i=11; i<=15; i++) {
    dd = 'ddice'+i;
    document.getElementById(dd).value='';
    if (dd == id) {
      document.getElementById(dd).disabled=false;
      document.getElementById(dd).value='<';
    }
    else document.getElementById(dd).disabled=true;
  }
  newpredict();
}
function penalty(id,diff)
{
  var p=document.getElementById(id).value;
  if (diff>0) p++; else { p--; if (p<1) p=''; }
  document.getElementById(id).value=p;
  newpredict();
}
function show_alert() {
  var str = document.forms[0][\"prognoz_str\"].value;
  if ( str.search(\"=\") == -1 ) {
    document.forms[0].submit();
    return true;
  }
  else {
    var r = confirm(\"В прогнозе остались незаполненные позиции. Вы действительно хотите отправить его в таком виде?\");
    if (r == true) {
      document.forms[0].submit();
      return true;
    }
    else
      return false;

  }
}
//]]></script>
";
echo '<form action="" name="tform" enctype="multipart/form-data" method="post" class="text15" onSubmit="return show_alert(this);"><br />
<br />Код матча: <b>'.$tour.'</b>, код команды: <b>'.$team_code.'</b><input type="hidden" name="team_code" value="'.$team_code.'" />
';
  if (!$closed && ($role != 'badlogin'))
    echo '<br />прогноз на тур: <input type="text" id="prognoz_str" name="prognoz_str" value="" size="50" />
<input type="submit" name="submitpredict" value=" отправить прогноз " /><br />
<a href="?m=help" target="_blank">как пользоваться формой отправки прогноза</a>
';
  // формирование вывода и базы тура
  echo '<br />
<table align="center">
  <tr>
    <td>
      <table>
';
  include script_from_cache('online/realteam.inc.php');
  $realmatch = array();
  foreach ($matches as $line) if ($line = trim($line))
  {
    if (strpos($line, ' - '))
    {
      (strpos($line, '│') !== false) ? $divider = '│' : $divider = '|';
      $atemp = explode($divider, $line);
      if (sizeof($atemp) > 2 && $cut = strpos($atemp[2], ' - '))
      {
        $nm = rtrim(trim($atemp[1]), '.');
        $dm = trim($atemp[3]);
        $home = trim(substr($atemp[2], 0, $cut));
        $away = trim(substr($atemp[2], $cut + 3));
        if (trim(substr($atemp[2], -3)))
        {
          $cut = strrpos($away, ' ');
          $tournament = substr($away, $cut + 1);
          $away = trim(substr($away, 0, strrpos($away, ' ')));
          $match = $realteam[$home].' - '.$realteam[$away].'/'.$tourname[trim($tournament)];;
          if (!trim($tournament) || !isset($base[$match]))
            $match = $realteam[$home].' - '.$realteam[$away];

        }
        else
        {
          $tournament = '&nbsp;';
          $match = $realteam[$home].' - '.$realteam[$away];
        }
        if (isset($base[$match]))
        {
          $dt = $base[$match][2];
          $d1 = substr($dt, 0, 2).'-'.substr($dt, 3, 2);
          // фикс для тура на границе годов: вместо 01-го месяца сравнивается 13-й
          if ($d1[0] == '0' && $d1[1] == '1' && $date[0] == '1' && $date[1] == '2')
          {
            $d1[0] = 1;
            $d1[1] = 3;
          }
          if ($d1 >= $date)
          { // допускается матч в день отправки прогноза
            $tarr = explode('.', $dm);
            $tdp = ltrim($tarr[0], '0');
            $tmp = ltrim($tarr[1], '0');
            $tarr = explode(' ', $dt);
            $tarr = explode('-', $tarr[0]);
            $tdb = ltrim($tarr[1], '0');
            $tmb = ltrim($tarr[0], '0');
            if (($tmp == 12) && ($tmb == 1))
              $tmb = 13;

            if (($tmp * 31 + $tdp + 7) < ($tmb * 31 + $tdb))
            { // матч сыгран раньше
              $tn = '??:??';
              $st = 'PP';
            }
            else
            {
              $dm = substr($dt, 3, 2).'.'.substr($dt, 0, 2);
              $tn = substr($dt, 6, 5);
              $st = $base[$match][3];
              $mt = $base[$match][5];
            }
            if ($st != '-' && ($st <= '90' || $st == 'HT'))
            {
              $mt = "<font color=\"red\">$mt</font>";
              $rt = "<font color=\"red\"><blink>$st</blink></font>";
              if (!$publish)
              {
                $publish = true;
                touch($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/published');
              }
            }
            elseif (($st == 'AB') || ($st == 'AW') || ($st == 'CAN') || ($st == 'POS') || ($st == 'SUS'))
            {
              $mt = $st;
              $rt = '-';
            }
            elseif ($mt == '-:-')
              $rt = '?';
            elseif ($st != '-')
            {
              $atemp = explode(':', $mt);
              if ($atemp[0] > $atemp[1])
                $rt = '1';
              elseif ($atemp[0] < $atemp[1])
                $rt = '2';
              else
                $rt = 'X';

              $stat = true;
              if (!$publish)
              {
                $publish = true;
                touch($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/published');
              }
            }
            else
            {
              $mt = '-:-';
              $rt = '?';
            }
          }
          else
          {
            $mt = '-:-';
            $rt = '?';
            $tn = '??:??';
          }
        }
        else
        {
          $mt = '-:-';
          $rt = '?';
          $tn = '??:??';
        }
        $realmatch[$nm]['home'] = $home;
        $realmatch[$nm]['away'] = $away;
        $realmatch[$nm]['trnr'] = $tournament;
        $realmatch[$nm]['date'] = $dm;
        $realmatch[$nm]['rslt'] = $mt;
        $realmatch[$nm]['case'] = $rt;

        if ($nm == 11)
        {
          echo "<tr><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td>";
          if (!$closed && ($role != 'badlogin'))
            echo "<td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td><td><img src=\"images/spacer.gif\" alt=\"\" /></td>";

          echo "</tr>\n";
          $rprognoz .= ' ';
        }
        echo "<tr><td align=\"right\">$nm</td><td align=\"left\" width=\"288\">$home - $away</td><td align=\"left\">$tournament</td><td title=\"$tn\" align=\"right\">$dm</td><td align=\"center\">$mt</td><td align=\"right\">$rt</td>";
        if (!$closed && ($role != 'badlogin'))
        {
          if ($rt == '?')
          {
            $onchange = 'onchange="newpredict();"';
            $dis = '';
          }
          else
          {
            $onchange = 'disabled ';
            $dis = 'dis';
          }
          if ($publish && $nm == 1)
          {
            $onchange = 'disabled ';
            $dis = 'dis';
          }
          echo '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','X'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'."dice$nm".'" value="" id="'."dice$nm".'" class="pr_str" '.$onchange.' />
    </td>
    <td>';
          if ($nm < 11)
          {
            if (!$neut && !$guest)
              echo '
      <a href="#" onclick="securedice('."'ddice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','X'".'); return false;">X</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" class="pr_str" '.$onchange.' />';
            else
              echo '      <input type="hidden" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" />';

          }
          else
          {
            if (!$neut && $guest)
              echo '
      <a href="#" onclick="securehome('."'ddice$nm$dis'".'); return false;">&lt;</a>
      <input type="text" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" class="pr_str" '.$onchange.' />';
            else
              echo '      <input type="hidden" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" />';

          }
          echo '
    </td>
    <td>
';
          if ($pena)
            echo '      <a href="#" onclick="penalty('."'pen$nm$dis','0'".'); return false;">&laquo;</a>п<a href="#" onclick="penalty('."'pen$nm$dis','1'".'); return false;">&raquo;</a>
      <input type="text" name="'."pen$nm".'" value="" id="'."pen$nm".'" class="pr_str" '.$onchange.' />
    </td>
';
          else
            echo '      <input type="hidden" name="'."pen$nm".'" value="" id="'."pen$nm".'" />
    </td>
';

        }
        echo "</tr>\n";
        if (strlen($rt) > 1)
          $rt = '?';

        $rprognoz .= $rt;
      }
    }
  }
  echo "</table></td>\n<td><img src=\"images/spacer.gif\" width=\"20\" alt=\"\" /></td>\n";
  // показ календаря тура
  echo "<td align=\"left\">в туре встречаются:\n";
  $virtmatch = array();
  $atemp = explode("\n", $cal);
  foreach ($atemp as $line) if ($line = trim($line))
    $virtmatch[] = $line;

  echo "<pre>$cal</pre>\nсрок отправки прогнозов $lastdate $lasttm</td>\n</tr></table>\n";
  // выборка прогнозов из мейлбокса
  $prognozlist = '';
  $mbox = file($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/mail');
  $have = array();
  $aprognoz = array();
  foreach ($mbox as $msg)
  {
    if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE)
      $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);

    $ta = explode(';', $msg);
    $team = $ta[0];
    if (!$publish && (($role == 'badlogin') || ($team != $team_code)))
      $ta[1] = $hidden;

    $warn = '';
    if (isset($teams[$team]))
      $team = $teams[$team];

    if (in_array($team, $have))
      $warn = '!!!';
    else
      $have[] = $team;

    if (($ta[1] != $hidden) || ($role == 'president')) {
    $prognozlist .= $team . str_repeat(' ', 21 - mb_strlen($team))
                  . htmlspecialchars($ta[1]) . str_repeat(' ', 20 - mb_strlen($ta[1]))
                  . $warn . str_repeat(' ',  5 - mb_strlen($warn)) . date('d M y  H:i:s', $ta[2]) . "\n";
    if (($penalties = trim($ta[3])) && ($ta[1] != $hidden))
        $prognozlist .= '                     '.strtolower($penalties)."\n";
    }
    if (!isset($aprognoz[$team]['time']) || ($ta[2] > $aprognoz[$team]['time']))
    {
      $aprognoz[$team]['prog'] = $ta[1];
      $aprognoz[$team]['time'] = $ta[2];
      $aprognoz[$team]['pena'] = $ta[3];
      $aprognoz[$team]['warn'] = $warn;
    }
  }
  // выдача виртуальных матчей делается в 2-х форматах: scanprog и stat
  $prognozlist .= '<hr />
';
  if ($stat)
    $prognozlist .= "
Прав. прогноз  $rprognoz
";
  // формирование таблиц виртуальных матчей
  foreach ($virtmatch as $line)
  {
    $atemp = explode(' - ', $line);
    $home = trim($atemp[0]);
    $away = trim($atemp[1]);

    if (!isset($aprognoz[$home]['prog']))
    {
      $aprognoz[$home]['prog'] = $hidden;
      $date = '';
    }
    else
      $date = date('d M y  H:i:s', $aprognoz[$home]['time']);

    if ($stat)
    { // stat для хозяина
      $podstr = '';
      $prognoz = trim(str_replace('<', '', $aprognoz[$home]['prog']));

      $warn = mb_substr(trim($aprognoz[$home]['warn']), 0, 2);
      if ($neut)
      {
        if ($fr = strpos($prognoz, '('))
          $prognoz = str_replace('('.$prognoz[$fr+1].')', '', $prognoz);

        $ppos = 0;
      }
      else
      {
        if ($prognoz[0] == '(')
          $prognoz = substr($prognoz, 3);

        if ($ppos = strpos($prognoz, '('))
        {
          $podstr = $prognoz[$ppos+1];
          $prognoz = str_replace("($podstr)", '', $prognoz);
        }
        else $ppos = 0;
      }
      $atemp = explode(' ', $prognoz, 2);
      if ($ln = 10 - strlen($atemp[0]))
        $atemp[0] .= str_repeat('=', $ln);
      elseif ($ln < 0)
        $atemp[0] = substr($atemp[0], 0, 10);

      $atemp[1] = str_replace(' ', '', $atemp[1]);
      if ($ln = 5 - strlen($atemp[1]))
        $atemp[1] .= str_repeat('=', $ln);
      elseif ($ln < 0)
        $atemp[1] = substr($atemp[1], 0, 5);

      $prognoz = $atemp[0].' '.$atemp[1];
      $warn = '  ';

      $aprognoz[$home]['warn'] = $warn;
      $prognozh = str_replace(' ', '', $prognoz);
      $pprognozh = $prognozh;

      if ($neut)
        $line1 = '';
      elseif ($podstr == $rprognoz[$ppos - 1])
        $line1 = sprintf('<font color="blue"><b>%'.(15 + $ppos).'s</b></font>', $podstr);
      else
        $line1 = sprintf('%'.(15 + $ppos).'s', $podstr);

      $prognozColored = '';
      for ($i=0; $i<=15; $i++)
        if($i != 10 && $prognoz[$i] == $rprognoz[$i])
          $prognozColored .= '<font color="blue"><b>'.$prognoz[$i].'</b></font>';
        else
          $prognozColored .= $prognoz[$i];

      $line2 = $home . str_repeat(' ', 15 - mb_strlen($home))
             . $prognozColored . str_repeat(' ', 17 - strlen($prognoz))
             . $warn . str_repeat(' ', 3 - mb_strlen($warn));
    }
    else
    {
      $addline = $home . str_repeat(' ', 21 - mb_strlen($home))
                . htmlspecialchars($aprognoz[$home]['prog']) . str_repeat(' ', 20 - mb_strlen($aprognoz[$home]['prog']));
      if ($publish || ($role == 'president'))
        $addline .= $aprognoz[$home]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$home]['warn'])) . $date."\n";
      else
        $addline .= "                        \n";
      $prognozlist .= $addline;
      if ($publish && ($date == '                   ')) $addfile .= $addline;
    }
    if (!isset($aprognoz[$away]['prog'])) {
      $aprognoz[$away]['prog'] = $hidden;
      $date = '';
    }
    else
      $date = date('d M y  H:i:s', $aprognoz[$away]['time']);

    if ($stat)
    { // stat для гостя
      $prognoz = $aprognoz[$away]['prog'];
      if ($prognoz[0] == '(') $prognoz = substr($prognoz, 3);
      if (isset($aprognoz[$away]['warn']))
        $warn = mb_substr(trim($aprognoz[$away]['warn']), 0, 2);
      else
        $warn = '  ';

      if ($fr = strpos($prognoz, '('))
        $prognoz = str_replace('('.$prognoz[$fr+1].')', '', $prognoz);

      $atemp = explode(' ', $prognoz, 2);
      $ln = 10 - strlen($atemp[0]);
      if ($ln >= 0)
        $atemp[0] .= str_repeat('=', $ln);
      else
        $atemp[0] = substr($atemp[0], 0, 10);

      if ($neut)
        $replacefix = false;
      else
        $replacefix = strpos($atemp[1], '<');

      $atemp[1] = str_replace(' ', '', $atemp[1]);
      $replace = max(1, strpos($atemp[1], '<')) + 9;
      $atemp[1] = trim(str_replace('<', '', $atemp[1]));
      $ln = 5 - strlen($atemp[1]);
      if ($ln >= 0)
        $atemp[1] .= str_repeat('=', $ln);
      else
       $atemp[1] = substr($atemp[1], 0, 5);

      $prognoz = $atemp[0].' '.$atemp[1];
      $warn = '  ';
      $aprognoz[$away]['warn'] = $warn;
      $prognoza = str_replace(' ', '', $prognoz);
      $pprognoza = $prognoza;
      $prognozr = str_replace(' ', '', $rprognoz);
      $prognozColored = '';
      for ($i=0; $i<=15; $i++)
        if($i != 10 && $prognoz[$i] == $rprognoz[$i])
          $prognozColored .= '<font color="blue"><b>'.$prognoz[$i].'</b></font>';
        else
          $prognozColored .= $prognoz[$i];

      $line3 = $away . str_repeat(' ', 15 - mb_strlen($away))
             . $prognozColored . str_repeat(' ', max(0, 17 - strlen($prognoz)))
             . $warn . str_repeat(' ', 3 - mb_strlen($warn));
      // удары по воротам
      $hith = 0; $hita = 0;
      for ($i=0; $i<15; $i++) if ($prognozr[$i] != '-')
      {
        if ($prognozr[$i] == $prognozh[$i])
          $hith++;

        if ($prognozr[$i] == $prognoza[$i])
          $hita++;

      }
      // голы, счет и протокол матча
      $goalh = 0;
      $goala = 0;
      $gn = 0;
      $prh = 0;
      $pra = 0;
      $mt = 10; // число матчей. участвующих в определении счета
      for ($i=0; $i<15; $i++)
      {
        if ($ppos && $prognozr[$ppos - 1] == '-')
          $usereplace = true;
        else
          $usereplace = false;

        if ($i < $mt)
        {
          if ($prognozr[$i] == '-')
          { // замена несостоявшегося матча
            if (!$replacefix)
              $usereplace = false;

            if ($ppos == $i + 1)
            {
              if ($prognozr[$replace] == '-')
              { // указанный гостями заменитель не играл, ищем обычную замену
                $replace = 10;
                while ($prognozr[$replace] == '-' && $replace < 16)
                  $replace++; // поиск первого неиспользованного форо-заменителя
              }
              $rep = $replace;
            }
            else
            {
              $rep = 10;
              while (($prognozr[$rep] == '-' || ($usereplace && $rep == $replace)) && $rep < 16)
                $rep++; // поиск первого неиспользованного заменителя

            }
            $usereplace = false;
            $prognozr[$i] = $prognozr[$rep];
            $prognozh[$i] = $prognozh[$rep];
            $prognoza[$i] = $prognoza[$rep];
            if ($ppos == $i + 1)
            {
              $ppos = 0;
              if ($prognozr[$i] == $prognozh[$i])
                $prognoza[$i] = '='; // фора!

              $line1 = sprintf('%-'.(16 + $replace).'s', rtrim($line1))."ф";
            }
            $prognozr[$rep] = '-';
          }
          if ($prognozr[$i] == $prognozh[$i])
            $hh = 1;
          elseif (($ppos == $i + 1) && ($prognozr[$i] == $podstr))
            $hh = 1;
          else
            $hh = 0;

          if ($prognozr[$i] == $prognoza[$i])
            $ha = 1;
          else
            $ha = 0;

          if ($hh > $ha)
          {
            $goalh++;
            $gn = $goalh + $goala;
          }
          elseif ($ha > $hh)
          {
            $goala++;
            $gn = $goalh + $goala;
          }
        }
        if ($gn == $mt)
          $mt++;

      }
      if ($pena && $goalh == $goala)
      {// обработка доп.времени
        $i = $mt;
        $mt += 3;
        $mainTime = '  в основное время: '.$goalh.':'.$goala;
        for ($i=$i; $i<15 && $i<$mt; $i++)
        {
          if ($prognozr[$i] == '-')
          { // замена несостоявшегося матча
            $rep = $mt;
            while ($prognozr[$rep] == '-' && $rep < 16)
              $rep++; // поиск первого неиспользованного заменителя

            $prognozr[$i] = $prognozr[$rep];
            $prognozh[$i] = $prognozh[$rep];
            $prognoza[$i] = $prognoza[$rep];
            $prognozr[$rep] = '-';
          }
          if ($prognozr[$i] == $prognozh[$i])
            $hh = 1;
          else
            $hh = 0;

          if ($prognozr[$i] == $prognoza[$i])
            $ha = 1;
          else
            $ha = 0;

          if ($hh > $ha)
            $goalh++;
          elseif ($ha > $hh)
            $goala++;

        }
      }
      else
        $mainTime = '';

      if ($pena && $goalh == $goala)
      {// обработка 11-метровых
        $rprognozh = str_replace(' ', '', $rprognoz);
        $rprognoza = $rprognozh;
        $penah = array();
//        if (!strpos($aprognoz[$home]['pena'], ' - '))
//          str_replace('. ', '. - ', $aprognoz[$home]['pena']);

        $ta = explode(',', trim(substr($aprognoz[$home]['pena'], 3 + strpos($aprognoz[$home]['pena'], ' - '))));

        $j = 0;
        if (sizeof($ta) > 1) for ($i=0; $i<sizeof($ta); $i++)
        { // определенные удары хозяина
          $mn = intval(trim($ta[$i])) - 1;
          if ($rprognozh[$mn] != '-')
          {
            $penah[$j]['n'] = $mn + 1;
            if ($rprognozh[$mn] == $pprognozh[$mn])
              $penah[$j]['r'] = '+';
            else
              $penah[$j]['r'] = '-';

            $rprognozh[$mn] = '-';
            $j++;
          }
        }
        for ($i=0; $i<15; $i++)
        { // неопределенные удары хозяина
          if ($rprognozh[$i] != '-')
          {
            $penah[$j]['n'] = $i + 1;
            if ($rprognozh[$i] == $pprognozh[$i])
              $penah[$j]['r'] = '+';
            else
              $penah[$j]['r'] = '-';

            $rprognozh[$i] = '-';
            $j++;
          }
        }
        $penaa = array();
//        if (!strpos($aprognoz[$away]['pena'], ' - '))
//          str_replace(' ', ' - ', $aprognoz[$away]['pena']);

        $ta = explode(',', trim(substr($aprognoz[$away]['pena'], 3 + strpos($aprognoz[$away]['pena'], ' - '))));
        $j = 0;
        if (sizeof($ta) > 1) for ($i=0; $i<sizeof($ta); $i++)
        { // определенные удары гостя
//echo '#'.$ta[$i].'#';
          $mn = intval(trim($ta[$i])) - 1;
          if ($rprognoza[$mn] != '-')
          {
            $penaa[$j]['n'] = $mn + 1;
            if ($rprognoza[$mn] == $pprognoza[$mn])
              $penaa[$j]['r'] = '+';
            else
              $penaa[$j]['r'] = '-';

            $rprognoza[$mn] = '-';
            $j++;
          }
        }
        for ($i=0; $i<15; $i++)
        { // неопределенные удары гостя
          if ($rprognoza[$i] != '-')
          {
            $penaa[$j]['n'] = $i + 1;
            if ($rprognoza[$i] == $pprognoza[$i])
              $penaa[$j]['r'] = '+';
            else
              $penaa[$j]['r'] = '-';

            $rprognoza[$i] = '-';
            $j++;
          }
        }
        $pline1 = '';
        $pline2 = '';
        for ($i=0; $i<5; $i++)
        { // первые 5 ударов с каждой стороны
          $pline1 .= sprintf('%2d', $penah[$i]['n']).'('.$penah[$i]['r'].'),';
          $pline2 .= sprintf('%2d', $penaa[$i]['n']).'('.$penaa[$i]['r'].'),';
          if ($penah[$i]['r'] == '+')
            $prh++;

          if ($penaa[$i]['r'] == '+')
            $pra++;

        }
        while ($prh == $pra && $i < sizeof($penah))
        { // теперь, если поровну, по 1 удару
          $pline1 .= sprintf('%2d', $penah[$i]['n']).'('.$penah[$i]['r'].'),';
          $pline2 .= sprintf('%2d', $penaa[$i]['n']).'('.$penaa[$i]['r'].'),';
          if ($penah[$i]['r'] == '+')
            $prh++;

          if ($penaa[$i]['r'] == '+')
            $pra++;

          $i++;
        }
        if ($prh > $pra)
          $goalh++;
        else if ($prh < $pra)
          $goala++;

        $mainTime .= '

  серия 11-метровых ударов:
' . $home . str_repeat(' ', 15 - mb_strlen($home)) . $pline1 . '  ' . $prh . '
' . $away . str_repeat(' ', 15 - mb_strlen($away)) . $pline2 . '  ' . $pra . '

  окончательный счет - ' . $goalh . ':' . $goala;
      }
      if (!trim($line1))
        $line1 = "&nbsp;";

      $prognozlist .= "$line1\n$line2$goalh ($hith)\n$line3$goala ($hita)\n$mainTime";
      // читать линк, если изменился счет, поменять линк
      $link = file_get_contents($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/link');
      $newlink = $online_dir . 'FCL/' . $s . '/' .substr($lastdate, 3) . '/'
               . $lastdate . '_' . trim($cal) . '_' . $t . '_' . $goalh . ':' . $goala;
      if (strcmp($link, $newlink) != 0)
      {
        file_put_contents($online_dir.$country_code.'/'.$season.'/prognoz/'.$tour.'/link', $newlink);
        unlink($link);
        symlink('../programms/'.$tour, $newlink);
      }
    }
    else
    {
      $addline = $away . str_repeat(' ', 21 - mb_strlen($away))
                . htmlspecialchars($aprognoz[$away]['prog']) . str_repeat(' ', 20 - mb_strlen($aprognoz[$away]['prog']));
      if ($publish || ($role == 'president'))
        $addline .= $aprognoz[$away]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$away]['warn'])) . $date."\n\n";
      else
        $addline .= "                        \n\n";

      $prognozlist .= $addline;
      if ($publish && ($date == '                   '))
        $addfile .= $addline;

    }
  }
  echo '<br />
<table align="center">
  <tr>
    <td align="left">
    <div style="white-space:pre-wrap; font-family: monospace;">
'.$prognozlist.'
    </div>
    </td>
  </tr>
</table>
</form></center>
';
}
?>
