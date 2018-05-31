<?php
mb_internal_encoding('UTF-8');

// вырезание тура из календарей всех видов
function GetTourFromCalendar($tour, $cal)
{
  $tourn = ltrim(ltrim(substr($tour, -2), '0'), 'C');
  if (($fr = strpos($cal, $tour)) === false)
    $fr = strpos($cal, " Тур $tourn");

  if ($fr === false)
    return $fr;

  $fr = strpos($cal, "\n", $fr) + 1;
  if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '='))
    $fr = strpos($cal, "\n", $fr) + 1;

  if ($to = strpos($cal, " Тур", $fr))
    return substr($cal, $fr, $to - $fr);

  return substr($cal, $fr);
}

// формирование файлов BOMBSORT и BOMB_ALL
function BOMB($country_code, $season, $calfname)
{
  global $online_dir;
  $abomber = array();
  $ateam = array();
  $axx = array();
  $header = '';
  $tarr = explode('/', $calfname);
  if (sizeof($tarr) == 1)
    $tcode = '';
  else
  {
    $tcode = '/'.$tarr[1];
    $calfname = $tarr[2];
  }
  if ($calfname == 'cal')
  {
    $bombdir = 'bomb';
    $bsortfn = 'BOMBSORT';
    $ballfn = 'BOMB_ALL';
  }
  elseif ($calfname == 'calc')
  {
    $bombdir = 'bombc';
    $bsortfn = 'BOMBSORTC';
    $ballfn = 'BOMB_ALLC';
  }
  else
  {
    $bombdir = 'bombs';
    $bsortfn = 'BOMBSORTS';
    $ballfn = 'BOMB_ALLS';
  }
  $dir = scandir($online_dir."$country_code/$season$tcode/$bombdir");
  foreach ($dir as $file) if ($file[0] != '.')
  {
    $header .= "Processing file $file\n";
    $bomb = file_get_contents($online_dir."$country_code/$season$tcode/$bombdir/$file");
    $bomb = str_replace("\r", "", $bomb);
    $amatch = explode("\n\n", $bomb);
    foreach ($amatch as $match) if (trim($match))
    {
      $cut = strpos($match, ' - ');
      $h = substr($match, 0, $cut);
      $cut += 3;
      $a = trim(substr($match, $cut, strpos($match, ':', $cut) - $cut - 2));
      $gh = 0; $ga = 0;
      $agoal = explode("\n ", $match);
      unset($agoal[0]);
      foreach ($agoal as $goal)
      {
        if ($gh < substr($goal, 0, strpos($goal, ':')))
        {
          $gh++;
          $teamn = $h;
        }
        else
        {
          $ga++;
          $teamn = $a;
        }
        $fr = strpos($goal, "' ") + 2;
        $to = strpos($goal, " (", $fr);
        $bomber = substr($goal, $fr, $to - $fr);
        if (!isset($abomber[$bomber]))
          $abomber[$bomber] = 0;

        $abomber[$bomber]++;
        $ateam[$bomber] = $teamn;
        $axx[$bomber] = substr($goal, $to + 2, 2);
      }
    }
  }
  // BOMBSORT
  $out = "$header-----------------\n";
  arsort($abomber);
  reset($abomber);
  $n = sizeof($abomber);
  for ($i=1; $i<=$n; $i++)
  {
    $bomber = key($abomber);
    $utbomber = $ateam[$bomber];
    $line = ".$bomber ( ".$ateam[$bomber]." / ".$axx[$bomber]." )  ";
    $out .= sprintf('%2s', $i).$line.str_repeat(' ', 39 - mb_strlen($line)).$abomber[$bomber]."\n";
    next($abomber);
  }
  file_put_contents($online_dir."$country_code/$season$tcode/$bsortfn", $out);

  // BOMB_ALL
  $out = "$header-----------------\n";
  asort($ateam);
  reset($ateam);
  $team = '';
  foreach ($ateam as $bomber => $teamn)
  {
    if (($teamn != $team) && ($teamn != $team.'(*)'))
    {
      $team = $teamn;
      $out .= "-----------------\n";
    }
    $line = "$teamn - $bomber (".$axx[$bomber].")";
    $out .= $line.str_repeat(' ', 40 - mb_strlen($line)).sprintf('%2s', $abomber[$bomber])."\n";
  }
  file_put_contents($online_dir."$country_code/$season$tcode/$ballfn", $out);
}

function MakeTourTable($a)
{
  // n m w d l g s r p h => sort_decs: p, r, g, h, w, n
  array_multisort($a['p'],SORT_DESC, $a['r'],SORT_DESC, $a['g'],SORT_DESC, $a['h'],SORT_DESC, $a['w'],SORT_DESC, $a['n'], $a['l'], $a['s'], $a['d'], $a['m']);
  $t = array();
  $t[] = '                   И  В  Н  П   М    О';
  $t[] = '';
  for($i=0; $i<sizeof($a['n']); $i++)
    $t[] = sprintf('%2s', $i+1).'.'.mb_substr($a['n'][$i], 0, 14)
         . str_repeat(' ', 15 - mb_strlen(mb_substr($a['n'][$i], 0, 14))).sprintf('%2s', $a['m'][$i])
         . sprintf('%3s', $a['w'][$i]).sprintf('%3s', $a['d'][$i]).sprintf('%3s', $a['l'][$i])
         . sprintf('%3s', $a['g'][$i]).'-'.sprintf('%-2s', $a['s'][$i]).sprintf('%3s', $a['p'][$i]);

  return $t;
}

function MakeWideTourTable($a)
{
  // n m w d l g s r p h => sort_decs: p, r, g, h, w, n
  array_multisort($a['p'],SORT_DESC, $a['r'],SORT_DESC, $a['g'],SORT_DESC, $a['h'],SORT_DESC, $a['w'],SORT_DESC, $a['n'], $a['l'], $a['s'], $a['d'], $a['m'], $a['t']);
  $t = array();
  $t[] = '                   И  В  Н  П   М    О  тренер';
  $t[] = '';
  for($i=0; $i<sizeof($a['n']); $i++)
    $t[] = sprintf('%2s', $i+1).'.'.mb_substr($a['n'][$i], 0, 14)
         . str_repeat(' ', 15 - mb_strlen(mb_substr($a['n'][$i], 0, 14))).sprintf('%2s', $a['m'][$i])
         . sprintf('%3s', $a['w'][$i]).sprintf('%3s', $a['d'][$i]).sprintf('%3s', $a['l'][$i])
         . sprintf('%3s', $a['g'][$i]).'-'.sprintf('%-2s', $a['s'][$i]).sprintf('%3s', $a['p'][$i]).'  '.$a['t'][$i];

  return $t;
}

function FillTemplate($template, $blockname, $tpltbl)
{
//  global $tpltbl;
  $fr = 0;
  while ($fr = (strpos($template, "[$blockname ", $fr + 1)))
  {
    $out = '';
    $to = strpos($template, "\n", $fr) + 1;
    $line = substr ($template, $fr, $to - $fr);
    $l = substr($line, strpos($line, ' ') + 1, strpos($line, ']') - strpos($line, ' ') - 1);
    $lblk = $tpltbl[$blockname][$l];
    if ($rblkpos = strpos($line, '[', 10))
    {
      $rblknm = substr($line, $rblkpos + 1, strpos($line, ' ', $rblkpos) - $rblkpos - 1);
      $r = substr($line, strpos($line, ' ', $rblkpos) + 1, strpos($line, ']', $rblkpos) - strpos($line, ' ', $rblkpos) - 1);
      $rblk = $tpltbl[$rblknm][$r];
      $m = max(sizeof($lblk), sizeof($rblk));
      for ($i=0; $i<$m; $i++)
        $out .= $lblk[$i] . str_repeat(' ', $rblkpos - mb_strlen($lblk[$i])).$rblk[$i]."\n";

    }
    else
      for ($i=0; $i<sizeof($lblk); $i++)
        $out .= $lblk[$i]."\n";

    $template = str_replace($line, $out, $template);
  }
  return $template;
}

function MakeRealmatch($programm)
{
  global $online_dir;
    // выборка данных по реальным матчам
    $fr = strpos($programm, "Последний с");
    $matches = explode("\n", substr($programm, 0, $fr));
    $programm = substr($programm, $fr);
    $months = array(
    ' января' => '.01',' янваpя' => '.01',
    ' февраля' => '.02', ' февpаля' => '.02',
    ' марта' => '.03', ' маpта' => '.03',
    ' апреля' => '.04', ' апpеля' => '.04',
    ' мая' => '.05',
    ' июня' => '.06',
    ' июля' => '.07',
    ' августа' => '.08',
    ' сентября' => '.09', ' сентябpя' => '.09',
    ' октября' => '.10',  ' октябpя' => '.10',
    ' ноября' => '.11',  ' ноябpя' => '.11',
    ' декабря' => '.12', ' декабpя' => '.12'
    );
    foreach ($months as $word => $digit)
      $programm = str_replace($word, $digit, $programm);

    $fr = strpos($programm, '.');
    $lastdate = trim(substr($programm, $fr - 2, 5));
    if (($fr1 = strpos($programm, ':', $fr)) && ($fr1 - $fr < 50))
      $lasttm = trim(substr($programm, $fr1 - 2, 5));
    else
      $lasttm = '';

    require_once('online/tournament.inc.php');
    $atemp = explode('.', $lastdate);
    $date = trim(sprintf('%02s',$atemp[1]).'-'.sprintf('%02s',$atemp[0]));
    $year = date('Y', time() + 3600);
    $month = date('m', time() + 3600);
    if (trim($atemp[1]) > ($month + 1) && $lastdate != '31.12')
      $fyear = $year - 1;
    else
      $fyear = $year;
    $week = date('W', strtotime("$fyear-$date"));
    $fname = $fyear.'.'.$week;
    if (is_file($online_dir."results/$fname"))
      $archive = file($online_dir."results/$fname");
    else
      $archive = array();

    $base = array();
    foreach ($archive as $line)
    {
      $data = explode(',', trim($line));
      $match = $data[0].' - '.$data[1];
      $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
      $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
    }
    if (++$week == '53') { $week = '01'; $fyear++; }
    if (strlen($week) == 1) $week = '0'.$week;
    $fname = $fyear.'.'.$week;
    if (is_file($online_dir."results/$fname")) $archive = file($online_dir."results/$fname"); else $archive = array();
    foreach ($archive as $line)
    {
      $data = explode(',', trim($line));
      $match = $data[0].' - '.$data[1];
      if (!isset($base[$match]))
        $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);

      if (!isset($base[$match.'/'.$data[6]]))
        $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);

    }
    include script_from_cache('online/realteam.inc.php');
    foreach ($matches as $line) if ($line = trim($line)) if (strpos($line, ' - '))
    {
        (strpos($line, '│') !== false) ? $divider = '│' : $divider = '|';
        $home = '';
        $away = '';
        $atemp = explode($divider, $line);
        if (sizeof($atemp) > 2 && $cut = strpos($atemp[2], ' - '))
        {
          $nm = rtrim(trim($atemp[1]), '.');
          $dm = trim($atemp[3]);
          $home = trim(substr($atemp[2], 0, $cut));
          if ($cut1 = strrpos($home, '(')) $home = trim(substr($home, 0, $cut1));
          if ($ct = strrpos($home, '(')) $hfix = trim(substr($home, 0, $ct));
          else $hfix = $home;
          $away = trim(substr($atemp[2], $cut + 3));
          if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
          else $afix = $away;
          if (trim(substr($atemp[2], -3)))
          {
            $cut = strrpos($away, ' ');
            $tournament = substr($away, $cut + 1);
            $away = trim(substr($away, 0, strrpos($away, ' ')));
            if ($cut1 = strrpos($away, '(')) $away = trim(substr($away, 0, $cut1));
            if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
            else $afix = $away;

            $match = $realteam[$hfix].' - '.$realteam[$afix].'/'.$tourname[trim($tournament)];;
            if (!trim($tournament) || !isset($base[$match]))
              $match = $realteam[$hfix].' - '.$realteam[$afix];

          }
          else
          {
            $tournament = '';
            $match = $realteam[$hfix].' - '.$realteam[$afix];
          }
          if (isset($base[$match]))
          {
            $dt = $base[$match][2];
// $d1 - контрольное время начала матча. если дата отправки и матча совпадают,
// матч учитывается только если указано время отправки меньше 20:00.
// для этого $d1 увеличивается на 0.5, чтобы срабатывало условие $d1 > $date
            $d1 = substr($dt, 0, 2).'-'.substr($dt, 3, 2);
            if ($lasttm && ($lasttm[0] != '2'))
              $d1 += .5;

          }
// фикс для тура на границе годов: вместо 01-го месяца сравнивается 13-й
          if ($d1[0] == '0' && $d1[1] == '1' && $date[0] == '1' && $date[1] == '2')
          {
            $d1[0] = 1;
            $d1[1] = 3;
          }
          $mt = '-:-';
          $rt = '-';
          if ((isset($base[$match])) && ($d1 > $date))
          {
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
              $st = 'POS';
            else
            {
              $dm = substr($dt, 3, 2).'.'.substr($dt, 0, 2);
              $st = $base[$match][3];
              $mt = $base[$match][5];
            }
            if (($st == 'AB') || ($st == 'AW') || ($st == 'CAN') || ($st == 'POS') || ($st == 'SUS'))
              $mt = $st;
            elseif ($st != '-')
            {
              $atemp = explode(':', $mt);
              if ($atemp[0] > $atemp[1])
                $rt = '1';
              elseif ($atemp[0] < $atemp[1])
                $rt = '2';
              else
                 $rt = 'X';
            }
          }
          $realmatch[$nm]['home'] = $home;
          $realmatch[$nm]['away'] = $away;
          $realmatch[$nm]['trnr'] = $tournament;
          $realmatch[$nm]['date'] = $dm;
          $realmatch[$nm]['rslt'] = $mt;
          $realmatch[$nm]['case'] = $rt;
        }
    }
    return $realmatch;
}

function GetPrognoz($country_code, $season, $tour, $teams)
{
  global $online_dir;
  // выборка прогнозов из мейлбокса
  $mbox = file($online_dir."$country_code/$season/prognoz/$tour/mail");
  $have = array();
  $aprognoz = array();
  foreach ($mbox as $msg)
  {
    if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE)
      $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);
    $ta = explode(';', $msg);
    $team = $ta[0];
    $warn = '';
    if (isset($teams[$team]))
      $team = $teams[$team];
    else
      $warn = 'oЖ';
    if (in_array($team, $have))
      $warn = '!!!';
    else
      $have[] = $team;

    if (!isset($aprognoz[$team]['time']) || ($ta[2] > $aprognoz[$team]['time']))
    {
      $aprognoz[$team]['prog'] = $ta[1];
      $aprognoz[$team]['time'] = $ta[2];
      $aprognoz[$team]['pena'] = $ta[3];
      $aprognoz[$team]['warn'] = $warn;
    }
  }
  // дополнение выбранных прогнозов президентскими данными
  if (is_file($online_dir."$country_code/$season/prognoz/$tour/adds"))
  {
    $addfile = file_get_contents($online_dir."$country_code/$season/prognoz/$tour/adds");
    $months = array(
' января' => ' Jan',' янваpя' => ' Jan',
' февраля' => ' Feb', ' февpаля' => ' Feb',
' марта' => ' Mar', ' маpта' => ' Mar',
' апреля' => ' Apr', ' апpеля' => ' Apr',
' мая' => ' May',
' июня' => ' Jun',
' июля' => ' Jul',
' августа' => ' Aug',
' сентября' => ' Sep', ' сентябpя' => ' Sep',
' октября' => ' Oct',  ' октябpя' => ' Oct',
' ноября' => ' Nov',  ' ноябpя' => ' Nov',
' декабря' => ' Dec', ' декабpя' => ' Dec',
' г.,' => '', ' г.' => '', ' inet' => ''
);
    foreach ($months as $rus => $eng)
      $addfile = str_replace($rus, $eng, $addfile);

    $added = explode("\n", $addfile);
    foreach ($added as $line) if ($line = rtrim($line))
    {
      if ($line[0] != ' ')
      {
        $team = trim(mb_substr($line, 0, 20));
        $line = trim(mb_substr($line, 20));
        if ($cut = min(20, strpos($line, ' ', 15))) {
          $prognoz = trim(substr($line, 0, $cut));
          $line = trim(substr($line, $cut));
        }
        else
        {
          $prognoz = trim($line);
          $line = '';
        }
        if (($line[0] >= '0') && ($line[0] <= '9'))
          $warn = '     ';
        else
        {
          if (mb_strlen($line) > 4 && $line[4] == ' ') $wln = 5; else $wln = 4;
          $warn = mb_substr($line, 0, $wln);
          $line = mb_substr($line, $wln);
          $warn = str_replace('с', 'c', $warn);
          $warn = str_replace('о', 'c', $warn);
          $warn = str_replace('а', 'a', $warn);
          $warn = str_replace('K', 'К', $warn);
        }
        if ($time = trim(substr($line, 0, 29)))
          $time = strtotime(trim(substr($line, 0, 29)).' CET');
        else
          $time = 0;

        $aprognoz[$team]['time'] = $time;
        $aprognoz[$team]['prog'] = $prognoz;
        $aprognoz[$team]['warn'] = $warn;
      }
      else
        $aprognoz[$team]['pena'] = $line;

    }
  }
  return $aprognoz;
}

function GetGen($country_code, $season, $genfname)
{
  global $online_dir;
  $gensets = 0;
  if (is_file($online_dir."$country_code/$season/$genfname"))
  {
    $gensets = 1;
    $gen = file_get_contents($online_dir."$country_code/$season/$genfname");
    if (strpos($gen, $tour))
    {
      $begin = $tour;
      $end = $country_code;
    }
    else
    {
      if ($genfname == 'genc')
        $n = ltrim(substr($tour, strlen($country_code) + 1), '0');
      else
        $n = ltrim(substr($tour, strlen($country_code)), '0');
      $begin = "Тур $n";
      $end = "Тур";
    }
    $fr = strpos($gen, $begin);
    $fr = strpos($gen, "\n", $fr) + 1;
    if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '='))
      $fr = strpos($gen, "\n", $fr) + 1;
    if ($to = strpos($gen, $end, $fr))
      $gen = trim(substr($gen, $fr, $to - $fr));
    else
      $gen = trim(substr($gen, $fr));
  }
  return $gen;
}

function build_itogi($country_code, $season, $tour)
{
  global $online_dir;
  $teams = array();
  $coach = array();
  $lnames = array();
  $maxteam = 0;
  $maxcoach = 0;
  $maxlname = 0;
  $acodes = file($online_dir."$country_code/$season/codes.tsv");
  foreach ($acodes as $scode) if ($scode[0] != '#')
  {
    $ateams = explode('	', ltrim($scode, '-'));
    $tname = trim($ateams[1]);
    $teams[trim($ateams[0])] = $tname;
    $maxteam = max($maxteam, mb_strlen($tname));
    $cname = trim($ateams[2]);
    $coach[$tname] = $cname;
    $maxcoach = max($maxcoach, mb_strlen($cname));
    if ($l = trim($ateams[4]))
    {
      $lnames[$tname] = $l;
      $maxlname = max($maxlname, mb_strlen($l));
    }
  }
  // формирование показа программки тура с реальными результатами
  if (is_file($online_dir."$country_code/$season/programms/$tour"))
  {
    $tcode = '';
    if ($tour[strlen($country_code)] == 'L')
      $tcode = substr ($tour, 0, strlen($country_code) + 1);

    if ($tour[strlen($country_code)] == 'C')
    {
      $calfname = 'calc';
      $genfname = 'genc';
      $cardsfname = 'cardsc';
      $itplfname = $online_dir."$country_code/$season/itc.tpl";
      $itfname = $online_dir."$country_code/$season/publish/itc".substr(str_replace('NEW', '', $tour), -1);
      $rfname = $online_dir."$country_code/$season/publish/rc".substr(str_replace('NEW', '', $tour), -1);
      $hfname = $online_dir."$country_code/$season/$tcode/horsec";
      $vfname = $online_dir."$country_code/$season/$tcode/ravenc";
      $sfname = $online_dir."$country_code/$season/$tcode/superbc";
      $bfname = $online_dir."$country_code/$season/$tcode/bombc/b".sprintf('%02s', substr(str_replace('NEW', '', $tour), -1));
      $bsfname = $online_dir."$country_code/$season/$tcode/BOMBSORTC";
    }
    elseif ($tour[strlen($country_code)] == 'S')
    {
      $calfname = 'cals';
      $genfname = 'gens';
      $cardsfname = 'cardss';
      $itplfname = $online_dir."$country_code/$season/itc.tpl";
      $itfname = $online_dir."$country_code/$season/publish/its".substr($tour, -1);
      $rfname = $online_dir."$country_code/$season/publish/rs".substr($tour, -1);
      $hfname = "";
      $vfname = "";
      $sfname = "";
      $bfname = $online_dir."$country_code/$season/$tcode/bombs/b".sprintf('%02s', substr(str_replace('NEW', '', $tour), -1));
      $bsfname = $online_dir."$country_code/$season/$tcode/BOMBSORTS";
    }
    elseif ($tour[strlen($country_code)] == 'P')
    {
      $calfname = 'calp';
      $genfname = 'genp';
      $cardsfname = 'cardsp';
      $itplfname = $online_dir."$country_code/$season/itc.tpl";
      $itfname = $online_dir."$country_code/$season/publish/itp".substr($tour, -1);
      $rfname = $online_dir."$country_code/$season/publish/rp".substr($tour, -1);
      $hfname = "";
      $vfname = "";
      $sfname = "";
      $bfname = $online_dir."$country_code/$season/$tcode/bombp/b".sprintf('%02s', substr(str_replace('NEW', '', $tour), -1));
      $bsfname = $online_dir."$country_code/$season/$tcode/BOMBSORTP";
    }
    else
    {
      $calfname = 'cal';
      $genfname = 'gen';
      $cardsfname = 'cards';
      $itplfname = $online_dir."$country_code/$season/it.tpl";
      $itfname = $online_dir."$country_code/$season/publish/it".substr(str_replace('NEW', '', $tour), -2);
      $rfname = $online_dir."$country_code/$season/publish/r".substr(str_replace('NEW', '', $tour), -2);
      $hfname = $online_dir."$country_code/$season/$tcode/horse";
      $vfname = $online_dir."$country_code/$season/$tcode/raven";
      $sfname = $online_dir."$country_code/$season/$tcode/superb";
      $bfname = $online_dir."$country_code/$season/$tcode/bomb/b".substr(str_replace('NEW', '', $tour), -2);
      $bsfname = $online_dir."$country_code/$season/$tcode/BOMBSORT";
    }
    // парсинг программки
    $programm = file_get_contents($online_dir."$country_code/$season/programms/$tour");
    $programm = str_replace(')-', ') - ', $programm);
    $fr = strpos($programm, "$tour ");
    $fr = strpos($programm, "\n", $fr) + 1;
    $programm = substr($programm, $fr);
    $realmatch = MakeRealmatch($programm);
    $fr = strpos($programm, "Последний с");
    $programm = substr($programm, $fr);
    $atemp = array();
    $calfp = explode("\n", $programm);
    foreach ($calfp as $line)
      if ((strpos($line, ' - ') || strpos($line, ' *'))
      && !strpos($line, 'ГОСТИ')
      && !strpos($line, 'Гости'))
      {
        if (($cut = strpos($line, '  ')) != false)
        {
          $line2 = trim(substr($line, $cut));
          $line1 = trim(substr($line, 0, $cut));
        }
        else
        {
          $line1 = trim($line);
          $line2 = '';
        }
        if ($line2) $atemp[] = array($line1, $line2);
        else if ($line1) $atemp[][0] = $line1;
      }
    $cal = '';
    $gen = '';
    foreach ($atemp as $lines) if ($lines)
    {
      if (strpos($lines[0], ' - ')) $cal .= $lines[0]."\n";
      if (strpos($lines[0], ' *')) $gen .= $lines[0];
      if (strpos($lines[1], ' *')) $gen .= '  '.$lines[1];
      if (strpos($lines[0], ' *') || strpos($lines[1], ' *')) $gen .= "\n";
    }
    if (!trim($gen))
      $gen = GetGen($country_code, $season, $genfname);

    if ($gen)
    {
      $generator = array();
      $gen = str_replace('*', '', $gen);
      $atemp = explode("\n", $gen);
      foreach ($atemp as $line) if ($line = trim($line))
      {
        if ($cut = strpos($line, '  '))
        {
          $generator['1'][] = trim(substr($line, 0, $cut));
          $generator['2'][] = trim(substr($line, $cut));
          $gensets = 2;
        }
        else
          $generator['1'][] = trim($line);
      }
    }
    foreach ($atemp as $lines) if ($lines)
      if (strpos($lines[1], ' - ')) $cal .= $lines[1]."\n";

    $virtmatch = array();
    $atemp = explode("\n", $cal);
    foreach ($atemp as $line) if ($line = trim($line)) {
      if ($cut = mb_strpos($line, '  '))
        $line = mb_substr($line, 0, $cut);

      $virtmatch[] = $line;
    }
  }
  $aprognoz = GetPrognoz($country_code, $season, $tour, $teams);

  $cards = array();
  if (is_file($online_dir."$country_code/$season/$tcode/$cardsfname"))
  {
    $atemp = file($online_dir."$country_code/$season/$tcode/$cardsfname");
    foreach ($atemp as $line) if ($line = trim($line))
      $cards[trim(mb_substr($line, 0, 20))] = mb_split(',', mb_substr($line, 20));

  }
  $rprognoz = '';
  for ($i=0; $i<=15; $i++)
  {
    if ($i == 11)
      $rprognoz .= ' ';

    $rprognoz .= $realmatch[$i]['case'];
  }
  // формирование таблиц виртуальных матчей
  $newcaltour = '';
  $sprognoz = array();
  $tplayers = sizeof($virtmatch) * 2;
  $rplayers = 0;
  $nm = 0;
  $gs = 0;
  $st = 1;
  $z = sizeof($virtmatch);
  if ($gensets == 2) $z = $z / 2;
  $plrs = array();
  $bomb = array();
  $results = array();
  $wresults = array();
  foreach ($virtmatch as $line)
  {
    $atemp = explode(' - ', $line);
    $home = trim($atemp[0]);
    if (isset($aprognoz[$home]['prog']) && $aprognoz[$home]['warn'][0] != '*')
      $rplayers++;
    $away = trim($atemp[1]);
    if (isset($aprognoz[$away]['prog']) && $aprognoz[$away]['warn'][0] != '*')
      $rplayers++;

    // хозяин
    $bad = false;
    $podstr = '';
    if (!isset($aprognoz[$home]['prog']))
    { // выдача генератора
      $aprognoz[$home]['prog'] = $generator[$st][$gs];
      $aprognoz[$home]['warn'] = '*Ж';
      $gs++;
    }
    $prognoz = trim(str_replace('<', '', $aprognoz[$home]['prog']));
    if ($prognoz[0] == '(')
    {
      $prognoz = substr($prognoz, 3);
      $bad = true;
    }
    $warn = mb_substr(trim($aprognoz[$home]['warn']), 0, 2);
    if ($ppos = strpos($prognoz, '('))
    {
      $podstr = $prognoz[$ppos+1];
      $prognoz = str_replace("($podstr)", '', $prognoz);
    }
    else
      $ppos = 0;

    $atemp = explode(' ', $prognoz, 2);
    if ($ln = 10 - strlen($atemp[0]))
    {
      $atemp[0] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[0] = substr($atemp[0], 0, 10);
      $bad = true;
    }
    $atemp[1] = str_replace(' ', '', $atemp[1]);
    if ($ln = 5 - strlen($atemp[1]))
    {
      $atemp[1] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[1] = substr($atemp[1], 0, 5);
      $bad = true;
    }
    $prognoz = $atemp[0].' '.$atemp[1];
    if ($bad)
    {
      if (mb_strpos($warn, 'Ж'))
        $warn = 'aК';
      elseif (!mb_strpos($warn, 'К'))
        $warn = 'oЖ';
    }
    // здесь отслеживать карточки
    if (mb_strpos($warn, 'К'))
    {
      for ($j=0; $prognoz[$j] == '='; $j++);
        $prognoz[$j] = '=';
// если этот матч не состоялся, продублировать карточку на заменяющий
// при этом проследить случай замены несыгравшей форы
// а также проверить, чтобы заменяющий матч таки состоялся
      if ($rprognoz[$j] == '-')
      {
        if ($ppos == $j + 1)
        {
          $replacer = $replace;
          if ($rprognoz[$replacer] == '-')
          { // указанный гостями заменитель не играл, делаем обычную замену
            $replacer = 11;
            while (($rprognoz[$replacer] == '-') && ($replacer < 15))
              $replacer++; // поиск первого неиспользованного форо-заменителя

          }
          $rep = $replacer;
        }
        else
        {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 15))
            $rep++; // поиск первого неиспользованного заменителя

        }
////////  $prognoz[$rep+1] = '=';
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж'))
      $warn = '  ';

    $aprognoz[$home]['warn'] = $warn;
    $line1 = sprintf('%'.(15 + $ppos).'s', $podstr);
    $line2 = $home . str_repeat(' ', 14 - mb_strlen($home)) . sprintf('%-16s', $prognoz) . ' '
            .$warn . str_repeat(' ', 2 - mb_strlen($warn)) . ' ';
    $prognozh = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*')
      $sprognoz[$home] = $prognozh;

    $match = $home;
    if ($aprognoz[$home]['warn'][0] == '*') $match .= '(*)';
    $match .= ' - '.$away;
    if ($aprognoz[$away]['warn'][0] == '*') $match .= '(*)';
    $bomb[$match][0]['min'] = 0; // заплата для сухих матчей
//    if ($prognozr[$ppos - 1] == '-') $usereplace = true; else $usereplace = false;

    // гость
    $bad = false;
    if (!isset($aprognoz[$away]['prog']))
    { // выдача генератора
      $aprognoz[$away]['prog'] = $generator[$st][$gs];
      $aprognoz[$away]['warn'] = '*Ж';
      $gs++;
    }
    $prognoz = $aprognoz[$away]['prog'];
    if ($prognoz[0] == '(')
      $prognoz = substr($prognoz, 3);

    $warn = mb_substr(trim($aprognoz[$away]['warn']), 0, 2);
    if ($fr = strpos($prognoz, '('))
    {
      $fake = $prognoz[$fr+1];
      $prognoz = str_replace("($fake)", '', $prognoz);
    }
    $atemp = explode(' ', $prognoz, 2);
    if ($ln = 10 - strlen($atemp[0]))
    {
      $atemp[0] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[0] = substr($atemp[0], 0, 10);
      $bad = true;
    }
    $replacefix = strpos($atemp[1], '<');
    $atemp[1] = str_replace(' ', '', $atemp[1]);
    $replace = max(1, strpos($atemp[1], '<')) + 9;
    $atemp[1] = trim(str_replace('<', '', $atemp[1]));
    if ($ln = 5 - strlen($atemp[1]))
    {
      $atemp[1] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[1] = substr($atemp[1], 0, 5);
      $bad = true;
    }
    $prognoz = $atemp[0].' '.$atemp[1];
    if ($bad)
    {
      if (mb_strpos($warn, 'Ж'))
        $warn = 'aК';
      elseif (!mb_strpos($warn, 'К'))
        $warn = 'oЖ';
    }
    // здесь отслеживать карточки
    if (mb_strpos($warn, 'К'))
    {
      for ($j=0; $prognoz[$j] == '='; $j++);
        $prognoz[$j] = '=';
// если этот матч не состоялся, продублировать карточку на заменяющий
// при этом проследить случай замены несыгравшей форы
// а также проверить, чтобы заменяющий матч таки состоялся
      if ($rprognoz[$j] == '-')
      {
        if ($ppos == $j + 1)
        {
          $replacer = $replace;
          if ($rprognoz[$replacer] == '-')
          { // указанный гостями заменитель не играл, делаем обычную замену
            $replacer = 11;
            while (($rprognoz[$replacer] == '-') && ($replacer < 15))
              $replacer++; // поиск первого неиспользованного форо-заменителя
          }
          $rep = $replacer + 1;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 15))
            $rep++; // поиск первого неиспользованного заменителя

        }
        else
        {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 15))
            $rep++; // поиск первого неиспользованного заменителя

        }
////////    $prognoz[$rep+1] = '=';
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж'))
      $warn = '  ';

    $aprognoz[$away]['warn'] = $warn;
    $line3 = $away . str_repeat(' ', 14 - mb_strlen($away)) . sprintf('%-16s', $prognoz) . ' '
            .$warn . str_repeat(' ', 2 - mb_strlen($warn)) . ' ';
    $prognoza = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*')
      $sprognoz[$away] = $prognoza;

    $prognozr = str_replace(' ', '', $rprognoz);
    // удары по воротам
    $hith = 0; $hita = 0;
    for ($i=0; $i<15; $i++) if ($prognozr[$i] != '-')
    {
      if ($prognozr[$i] == $prognozh[$i])
      {
        $hith++;
        if ($aprognoz[$home]['warn'][0] != '*')
          $realmatch[$i+1]['hits']++;

      }
      if ($prognozr[$i] == $prognoza[$i])
      {
        $hita++;
        if ($aprognoz[$away]['warn'][0] != '*')
          $realmatch[$i+1]['hits']++;

      }
      if (($aprognoz[$home]['warn'][0] != '*') && ($prognozh[$i] != '='))
        $plrs[$i]++;

      if (($aprognoz[$away]['warn'][0] != '*') && ($prognoza[$i] != '='))
        $plrs[$i]++;

    }
    // голы, счет и протокол матча
    $goalh = 0; $goala = 0; $gn = 0;
    $mt = 10; // число матчей. участвующих в определении счета
    for ($i=0; $i<15; $i++)
    {
      if ($prognozr[$ppos - 1] == '-')
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
              while (($prognozr[$replace] == '-') && ($replace < 16))
                $replace++; // поиск первого неиспользованного форо-заменителя

            }
            $rep = $replace;
          }
          else
          {
            $rep = 10;
            while ((($prognozr[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 16))
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
          $bomb[$match][$gn]['min'] = round(rand(1, 9)) + $i * 9;
          $bomb[$match][$gn]['side'] = 'h';
          $bomb[$match][$gn]['team'] = $home;
          if ($aprognoz[$home]['warn'][0] == '*') 
            $bomb[$match][$gn]['team'] .= '(*)';

          $bomb[$match][$gn]['xx'] = $prognozr[$i].$prognoza[$i];
        }
        elseif ($ha > $hh)
        {
          $goala++;
          $gn = $goalh + $goala;
          $bomb[$match][$gn]['min'] = round(rand(1, 9)) + $i * 9;
          $bomb[$match][$gn]['side'] = 'a';
          $bomb[$match][$gn]['team'] = $away;
          if ($aprognoz[$away]['warn'][0] == '*') 
            $bomb[$match][$gn]['team'] .= '(*)';

          if ($ppos == $i + 1)
            $bomb[$match][$gn]['xx'] = $prognozr[$i].'*';
          else
            $bomb[$match][$gn]['xx'] = $prognozr[$i].$prognozh[$i];

        }
      }
      if ($gn == $mt)
        $mt++;
    }
    $results[] = $line1;
    $results[] = $line2.$goalh.sprintf('%4s', '('.$hith.')');
    $results[] = $line3.$goala.sprintf('%4s', '('.$hita.')');
    $wresults[] = $line1;
    if ($aprognoz[$home]['warn'][0] != '*')
      $wresults[] = $line2.$goalh.sprintf('%4s', '('.$hith.')').'  '.$coach[$home];
    else
      $wresults[] = $line2.$goalh.sprintf('%4s', '('.$hith.')').'  (* generator *)';

    if ($aprognoz[$away]['warn'][0] != '*')
      $wresults[] = $line3.$goala.sprintf('%4s', '('.$hita.')').'  '.$coach[$away];
    else
      $wresults[] = $line3.$goala.sprintf('%4s', '('.$hita.')').'  (* generator *)';

    // заготовка для обновления календаря
    $newcaltour .= "$home - $away  $goalh:$goala".str_repeat(' ', 30 - mb_strlen("$home - $away  $goalh:$goala")).'  (';
    if ($aprognoz[$home]['warn'][0] == '*')
      $newcaltour .= "*:$hith:";
    else
      $newcaltour .= $coach[$home].":$hith:";

    if ($aprognoz[$away]['warn'][0] == '*')
      $newcaltour .= "*:$hita)\n";
    else
      $newcaltour .= $coach[$away].":$hita)\n";

    if (++$nm > $z)
    {
      $gs = 0;
      $st = 2;
      $z = sizeof($virtmatch);
    }
  }
  // обновление файла календаря
  $newcaltour .= "\n";
  $tourfix = str_replace('NEW', '', $tour);
  $tourn = ltrim(ltrim(substr($tourfix, -2), '0'), 'C');

  if (is_file($online_dir."$country_code/$season/$tcode/$calfname"))
  {
    $cal = file_get_contents($online_dir."$country_code/$season/$tcode/$calfname");
    if ($caltour = GetTourFromCalendar($tourfix, $cal))
      $cal = str_replace($caltour, $newcaltour, $cal);
    else
      $cal .= " Тур $tourn     Код $tourfix\n$newcaltour";

  }
  else
    $cal = " Тур $tourn     Код $tourfix\n$newcaltour";

  file_put_contents($online_dir."$country_code/$season/$tcode/$calfname", $cal);

  // таблица числа угадавших прогнозы для формата stat
  $prognozr = str_replace(' ', '', $rprognoz);
  for ($i=0; $i<15; $i++)
  {
    if ($prognozr[$i] == '-')
      $realmatch[$i+1]['hits'] = '-';

    if (!isset($realmatch[$i+1]['hits']))
    {
      $realmatch[$i+1]['hits'] = 0;
      $realmatch[$i+1]['plyr'] = ':(';
    }
    elseif ($realmatch[$i+1]['hits'] == $plrs[$i])
      $realmatch[$i+1]['plyr'] = ':)';

    if ($realmatch[$i+1]['hits'] == 1)
      foreach ($sprognoz as $teamn => $progn)
        if ($progn[$i] == $prognozr[$i])
          $realmatch[$i+1]['plyr'] = $coach[$teamn];

    if (($plrs[$i] > 2) && ($plrs[$i] - $realmatch[$i+1]['hits'] == 1))
      foreach ($sprognoz as $teamn => $progn)
        if (($progn[$i] != '=') && ($progn[$i] != $prognozr[$i]))
          $realmatch[$i+1]['plyr'] = $coach[$teamn];

  }

  // формирование протоколов матчей тура
  $ab = array();
  $bout = '';
  if (is_file($online_dir."$country_code/$season/bombers"))
  { // parse bombers
    $bombers = file_get_contents($online_dir."$country_code/$season/bombers");
    $bombers = str_replace('', '', $bombers);
    if (mb_detect_encoding($bombers, 'UTF-8', true) === FALSE)
      $bombers = iconv('CP1251', 'UTF-8', $bombers);

    foreach ($teams as $tc => $tn)
    {
      $bombers = str_replace("Team: $tc\n", "Team: $tn\n", $bombers);
      $bombers = str_replace("Team: $tc(*)\n", "Team: $tn(*)\n", $bombers);
    }
    $abombers = explode('Team: ', $bombers);
    $stadium = array();
    foreach ($abombers as $bteam)
    {
      $ateam = explode("\n", $bteam);
      $tn = trim($ateam[0]);
      if ($ateam[1][0] != '1')
      {
        $stadium[$tn] = trim($ateam[1]);
        unset($ateam[1]);
      }
      unset($ateam[0]);
      foreach($ateam as $line) if ($line = trim($line))
        $ab[$tn][substr($line, 0, 2)] = substr($line, 3);

    }
  }
  foreach ($bomb as $match => $prot)
  {
    $hth = 0; $hta = 0; $fth = 0; $fta = 0;
    $mout = '';
    foreach ($prot as $goal => $data) if ($goal)
    {
      if ($data['side'] == 'h')
      {
        $fth++;
        if ($data['min'] < 46)
          $hth++;

      }
      elseif ($data['side'] == 'a')
      {
        $fta++;
        if ($data['min'] < 46)
          $hta++;
      }
      $mout .= " $fth:$fta ".sprintf('%2s', $data['min'])."' ";
      if (isset($ab[$data['team']][$data['xx']]))
        $mout .= $ab[$data['team']][$data['xx']];
      else
      {
        $mout .= 'Unknown ';
        if (mb_detect_encoding($data['team'], 'UTF-8', true) === FALSE)
        $mout .= iconv('CP1251', 'UTF-8', $data['team']);
        else
          $mout .= $data['team'];

        $mout .= ' '.$data['xx'];
      }
      $mout .= " (".$data['xx'].")\n";
    }
    if (mb_detect_encoding($match, 'UTF-8', true) === FALSE)
      $match = iconv('CP1251', 'UTF-8', $match);

    $hd = "$match $fth:$fta ($hth:$hta)\n";
    $bout .= $hd.str_repeat('-', strlen($hd))."\n$mout\n";
  }
  if ($calfname == 'calc')
    $fname = $online_dir."$country_code/$season/$tcode/bombc/b0$tourn";
  elseif (strlen($tourn) == 1)
    $fname = $online_dir."$country_code/$season/bomb/b0$tourn";
  else
    $fname = $online_dir."$country_code/$season/bomb/b$tourn";

  file_put_contents($fname, $bout);
  // обновление файла карточек
  foreach ($aprognoz as $tn => $atemp)
    $cards[$tn][$tourn] = $atemp['warn'];

  $bout = '';
  foreach ($cards as $tn => $atemp)
  {
    $bout .= $tn . str_repeat(' ', 20 - mb_strlen($tn));
    for ($i=0; $i<=$tourn; $i++)
      if (trim($atemp[$i]))
        $bout .= $atemp[$i].',';
      else
        $bout .= '  ,';

    $bout .= "\n";
  }
  file_put_contents($online_dir."$country_code/$season/$tcode/$cardsfname", $bout);
  // темные лошадки и белые вороны
  $horse = file($hfname);
  foreach ($horse as $i => $line)
  {
    $atemp = explode(',', $line);
    if ($atemp[2] == $tourn)
      unset($horse[$i]);

  }
  $raven = file($vfname);
  foreach ($raven as $i => $line)
  {
    $atemp = explode(',', $line);
    if ($atemp[2] == $tourn)
      unset($raven[$i]);

  }
  for ($i=1; $i<=15; $i++) if (strlen($trainer = $realmatch[$i]['plyr']) > 2)
  {
    foreach ($coach as $teamn => $pl) if ($pl == $trainer) $tmn = $teamn;
      if ($realmatch[$i]['hits'] == 1)
        $horse[] = "$trainer,$tmn,$tourn,".$realmatch[$i]['case'].",".$plrs[$i-1]."\n";
      else
        $raven[] = "$trainer,$tmn,$tourn,".$realmatch[$i]['case'].",".$plrs[$i-1]."\n";

  }
  $out = '';
  foreach($horse as $line)
    $out .= $line;

  file_put_contents($hfname, $out);
  $out = '';
  foreach($raven as $line)
    $out .= $line;
  file_put_contents($vfname, $out);

  BOMB($country_code, $season, ltrim("$tcode/$calfname", '/'));

  // итоги
  if (is_file($itplfname))
  {
    $template = file_get_contents($itplfname);
    // определение шаблона программки
    $fr = mb_strrpos($template, '[TourCode]') - 40;
//    $fr = mb_strrpos($template, ' N') - 8;
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line11 = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $lineln = mb_strlen($line);
    $i = 0;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $c1pos = $i;
    $c1val = mb_substr($line, $i, 1);
    $i++;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $i++;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $c2pos = $i;
    $c2val = mb_substr($line, $i, 1);
    $i++;
    $c3pos = mb_strpos($line, $c2val, $i);
    $c3val = $c2val;
    $c2matchmaxln = $c3pos - $c2pos - 7; // макс. длина строки матча
    $i = $c3pos + 1;
    $c4pos = mb_strpos($line, $c2val, $i + 1);
    $c4val = $c2val;
    $i = $c4pos + 1;
    $c5pos = mb_strpos($line, $c2val, $i + 1);
    $c5val = $c2val;
    $i = $c5pos + 1;
    $c6pos = mb_strpos($line, $c1val, $i + 1);
    $c6val = $c1val;
    // [TourNum]
    $template = str_replace('[TourNum]', $tourn, $template);
    // [TourCode]
    $template = str_replace('[TourCode]', sprintf('%-10s', $tourfix), $template);
    // [Programme]
    $programme = array();
    for ($nm=1; $nm<=15; $nm++)
    {
      if ($nm == 11)
        $programme[] = $line11;
      $line = str_repeat(' ', $c1pos).$c1val.sprintf('%2s', $nm).'.';
      $line = sprintf('%-'.$c2pos.'s', $line).$c2val.' ';
      $home = $realmatch[$nm]['home'];
      $away = $realmatch[$nm]['away'];
      if ((mb_strlen($home) + mb_strlen($away) + 3) > $c2matchmaxln)
        if (mb_strlen($home) >= mb_strlen($away))
        {
          if ($cut = mb_strpos($home, '('))
            $home = trim(mb_substr($home, 0, $cut));
          elseif ($cut = mb_strrpos($home, ' '))
            $home = mb_substr($home, 0, $cut + 2).'.';

        }

      if ((mb_strlen($home) + mb_strlen($away) + 3) > $c2matchmaxln)
      {
        if ($cut = mb_strpos($away, '('))
          $away = trim(mb_substr($away, 0, $cut));
        elseif ($cut = mb_strrpos($away, ' '))
          $away = mb_substr($away, 0, $cut + 2).'.';

        if ((mb_strlen($home) + mb_strlen($away) + 3) > $c2matchmaxln)
        {
          if ($cut = mb_strpos($home, '('))
            $home = trim(mb_substr($home, 0, $cut));
          elseif ($cut = mb_strrpos($home, ' '))
            $home = mb_substr($home, 0, $cut + 2).'.';

        }
      }
      $line .= $home . ' - ' . $away . str_repeat(' ', $c2matchmaxln - mb_strlen($home.' - '.$away));
//        $tn = ' '.$realmatch[$nm]['trnr'];
      if (strlen($realmatch[$nm]['trnr']) < 5)
        $tn = ' '.$realmatch[$nm]['trnr'];
      else
        $tn = mb_substr($realmatch[$nm]['trnr'], 0, 5);

      $tn = $tn . str_repeat(' ', 5 - mb_strlen($tn));
      if ($tn == '&nbsp')
        $tn = '     ';

      $line .= $tn . $c3val . $realmatch[$nm]['date'] . $c4val;
      if ((strlen($realmatch[$nm]['rslt']) < 4) || ($realmatch[$nm]['rslt'][1] == ':'))
        $mt = ' '.$realmatch[$nm]['rslt'];
      else
        $mt = $realmatch[$nm]['rslt'];

      $line .= sprintf('%-5s', $mt).$c5val.' '.$realmatch[$nm]['case'].' '.$c6val;
      $programme[] = $line;
    }
    // [PlayersHits]
    $playerhits = array();
    for ($nm=1; $nm<=15; $nm++)
    {
      if ($nm == 11)
        $playerhits[] = ' ';

      $pl = $realmatch[$nm]['plyr'];
      if (mb_strlen($pl) > 2)
        $pl = mb_substr($pl, 0, 1).'.'.mb_substr($pl, 1 + mb_strpos($pl, ' '));

      $playerhits[] = sprintf('%-2s', $realmatch[$nm]['hits']).' '.$pl;
    }
    $fr = strpos($template, '[Programme]');
    $to = strpos($template, "\n", $fr) + 1;
    $line = substr ($template, $fr, $to - $fr);
    $plhtpos = strpos($line, '[PlayersHits]');
    $out = '';
    for ($i=0; $i<16; $i++)
      $out .= $programme[$i].str_repeat(' ', $plhtpos - mb_strlen($programme[$i])).$playerhits[$i]."\n";

    $template = str_replace($line, $out, $template);
    // [NumPredict] в будущем возможен параметр n-N
    $template = str_replace('[NumPredict]', sprintf('%-12s', $tplayers), $template);
    // [NumPlayers] в будущем возможен параметр n-N
    $template = str_replace('[NumPlayers]', sprintf('%-12s', $rplayers), $template);
    // [Missed] в будущем возможен параметр n-N
    $missed = substr_count($cal, '*');
    $template = str_replace('[Missed]', $missed, $template);
    // [FairPlay] в будущем возможен параметр n-N
    $fairplay = round($missed / (substr_count($cal, '(') * 2), 3);
    $template = str_replace('[FairPlay]', $fairplay, $template);
    // [PresentResults]
    $template = str_replace('[PresentResults]', $rprognoz, $template);
    // [Results n-N]
    $fr = 0;
    $tpltbl = array(); 
    reset($results);
    $tl = max(14, $maxteam);
    while ($fr = (strpos($template, '[Results ', $fr + 1)))
    {
      $line = substr($template, $fr, strpos($template, ']', $fr) + 1 - $fr);
      $cut = strpos($line, '-');
      $n1 = substr($line, strpos($line, ' ') + 1, $cut - strpos($line, ' ') - 1);
      $n2 = substr($line, $cut + 1, strpos($line, ']', $cut) - $cut - 1);
      $rt = "$n1-$n2";
      if ($n2 - $n1 > 6)
        $tpltbl['Results'][$rt][] = str_repeat(' ', $tl + 20).'Счёт';

      for ($nm=$n1; $nm<=$n2; $nm++)
      {
        if ($nm % 2)
        {
          $tpltbl['Results'][$rt][] = substr(current($results), 1);
          next($results);
        }
        $tpltbl['Results'][$rt][] = substr(current($results), 0);
        //, $tl) . substr(current($results), $tl + 1);
        if (!next($results))
          $nm = $n2 + 1;

      }
    }
    // [Wide[Results n-N]
    $fr = 0;
    reset($wresults);
    $tl = max(14, $maxteam);
    while ($fr = (strpos($template, '[Wide[Results ', $fr + 1)))
    {
      $line = substr($template, $fr, strpos($template, ']', $fr) + 1 - $fr);
      $cut = strpos($line, '-');
      $n1 = substr($line, strpos($line, ' ') + 1, $cut - strpos($line, ' ') - 1);
      $n2 = substr($line, $cut + 1, strpos($line, ']', $cut) - $cut - 1);
      $rt = "$n1-$n2";
      if ($n2 - $n1 > 6)
        $tpltbl['Wide[Results'][$rt][] = str_repeat(' ', $tl + 20).'Счёт';

      for ($nm=$n1; $nm<=$n2; $nm++)
      {
        if ($nm % 2)
        {
          $tpltbl['Wide[Results'][$rt][] = substr(current($wresults), 1);
          next($wresults);
        }
        $tpltbl['Wide[Results'][$rt][] = substr(current($wresults), 0);
        //, $tl)).strtr(substr(current($wresults), $tl + 1), 'ЖК', 'ђ’');
        if (!next($wresults))
          $nm = $n2 + 1;

      }
    }
    $tt = array();
    $best = array();
    $firework = array();
    $gb = array();
    for ($tn=1; $tn<=$tourn; $tn++)
    {
      $fr = strpos($cal, " Тур $tn");
      $fr = strpos($cal, "\n", $fr) + 1;
      if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '='))
        $fr = strpos($cal, "\n", $fr) + 1;

      if ($to = strpos($cal, " Тур", $fr))
        $caltour = substr($cal, $fr, $to - $fr);
      else
        $caltour = substr($cal, $fr);

      $virtmatch = array();
      $atemp = explode("\n", trim($caltour));
      $amb = array();
      if (strpos($atemp[0],':')) foreach ($atemp as $line) if ($line = trim($line))
      {
        $atemp = explode(' - ', $line, 2);
        $home = $atemp[0];
        $atemp = explode('  ', $atemp[1], 2);
        $away = $atemp[0];
        $atemp = explode(':', $atemp[1], 2);
        $gh = $atemp[0];
        $atemp = explode('  ', $atemp[1], 2);
        $ga = $atemp[0];
        $atemp[1] = trim($atemp[1], ' ()');
        $atemp = explode(':', $atemp[1]);
        $th = $atemp[0];
        $hh = $atemp[1];
        $ta = $atemp[2];
        $ha = $atemp[3];
// tt: team =>
//  m mh ma  w wh wa  d dh da  l lh la  g gh ga  s sh sa  r rh ra  p ph pa
// bw bwh bwa bgh bga bsh bsa bl blh bla lgh lga lsh lsa wag was ser h hh ha
        $tt[$home]['m']++;
        $tt[$away]['m']++;
        $tt[$home]['mh']++;
        $tt[$away]['ma']++;
        $tt[$home]['en'][$tn] = $away;
        $tt[$away]['en'][$tn] = $home;
        $tt[$home]['g'] += $gh;
        $tt[$home]['sg'][$tn] = $gh;
        $tt[$away]['g'] += $ga;
        $tt[$away]['sg'][$tn] = $ga;
        $tt[$home]['gh'] += $gh;
        $tt[$away]['ga'] += $ga;
        $tt[$home]['s'] += $ga;
        $tt[$home]['ss'][$tn] = $ga;
        $tt[$away]['s'] += $gh;
        $tt[$away]['ss'][$tn] = $gh;
        $tt[$home]['sh'] += $ga;
        $tt[$away]['sa'] += $gh;
        $tt[$home]['r'] += $gh - $ga;
        $tt[$away]['r'] += $ga - $gh;
        $tt[$home]['rh'] += $gh - $ga;
        $tt[$away]['ra'] += $ga - $gh;
        $firework[] = array($gh + $ga, $home, $away, $gh, $th, $ga, $ta);
        $amb[$home]['f'] = 0; // sort: away, away vs gen, home, home vs gen
        $amb[$away]['f'] = 2;
        if ($th == '*')
          $tt[$home]['i']++;
        else
        {
          $tt[$home]['h'] += $hh;
          $tt[$home]['hh'] += $hh;
          $amb[$home]['h'] = $hh;
          $amb[$home]['t'] = $th;
          $amb[$home]['r'] = $gh - $ga;
          $amb[$home]['g'] = $gh;
          $amb[$away]['f']++;
          $gb[$th]['t'][$tn] = $hh;
          $gb[$th]['h'] += $hh;
          $gb[$th]['n'] = $home;
        }
        if ($ta == '*')
          $tt[$away]['i']++;
        else
        {
          $tt[$away]['h'] += $ha;
          $tt[$away]['ha'] += $ha;
          $amb[$away]['h'] = $ha;
          $amb[$away]['t'] = $ta;
          $amb[$away]['r'] = $ga - $gh;
          $amb[$away]['g'] = $ga;
          $amb[$home]['f']++;
          $gb[$ta]['t'][$tn] = $ha;
          $gb[$ta]['h'] += $ha;
          $gb[$ta]['n'] = $away;
        }
        if ($gh > $ga)
        {
          $tt[$home]['ser'][$tn] = 3;
          $tt[$home]['p'] += 3;
          $tt[$home]['ph'] += 3;
          $tt[$home]['w']++;
          $tt[$home]['wh']++;
          $tt[$away]['ser'][$tn] = 0;
          $tt[$away]['l']++;
          $tt[$away]['la']++;
          $amb[$home]['x'] = 3;
          $amb[$away]['x'] = 0;
          if (($gh - $ga) > 2)
          {
            $tt[$home]['bw']++;
            $tt[$home]['bwh']++;
            $tt[$home]['bg'] += $gh;
            $tt[$home]['bs'] += $ga;
            $tt[$home]['bgh'] += $gh;
            $tt[$home]['bsh'] += $ga;
            $tt[$away]['bl']++;
            $tt[$away]['bla']++;
            $tt[$away]['bls'] += $gh;
            $tt[$away]['blg'] += $ga;
            $tt[$away]['lga'] += $gh;
            $tt[$away]['lsa'] += $ga;
            $bbr = ($gh - $ga) * 10 + $gh;
            if ($bbr > $tt[$home]['bbr'])
            {
              $tt[$home]['bbr'] = $bbr;
              $tt[$home]['bbn'] = 1;
            }
            if ($bbr == $tt[$home]['bbr'])
              $tt[$home]['bbn']++;

            if ($bbr > $best['h']['bbr'])
            {
              $best['h']['bbr'] = $bbr;
              $best['h']['rez'] = "$gh-$ga";
              $best['h']['team'] = "$home - $away ($tn тур),";
            }
            elseif ($bbr == $best['h']['bbr'])
              $best['h']['team'] .= "$home - $away ($tn тур),";

            if ($ta != '*')
            {
              if ($bbr > $best['d']['bbr'])
              {
                $best['d']['bbr'] = $bbr;
                $best['d']['rez'] = "$gh-$ga";
                $best['d']['team'] = "$home - $away ($tn тур),";
              }
              elseif ($bbr == $best['d']['bbr'])
                $best['d']['team'] .= "$home - $away ($tn тур),";

            }
          }
        }
        elseif ($gh < $ga)
        {
          $tt[$home]['ser'][$tn] = 0;
          $tt[$home]['l']++;
          $tt[$home]['lh']++;
          $tt[$away]['ser'][$tn] = 3;
          $tt[$away]['p'] += 3;
          $tt[$away]['pa'] += 3;
          $tt[$away]['w']++;
          $tt[$away]['wa']++;
          $tt[$away]['wag'] += $ga;
          $tt[$away]['was'] += $gh;
          $amb[$home]['x'] = 0;
          $amb[$away]['x'] = 3;
          if (($ga - $gh) > 2)
          {
            $tt[$away]['bw']++;
            $tt[$away]['bwa']++;
            $tt[$away]['bg'] += $ga;
            $tt[$away]['bs'] += $gh;
            $tt[$away]['bga'] += $ga;
            $tt[$away]['bsa'] += $gh;
            $tt[$home]['bl']++;
            $tt[$home]['blh']++;
            $tt[$home]['blg'] += $gh;
            $tt[$home]['bls'] += $ga;
            $tt[$home]['lgh'] += $gh;
            $tt[$home]['lsh'] += $ga;
            $bbr = ($ga - $gh) * 10 + $ga;
            if ($bbr > $tt[$away]['bbr'])
            {
              $tt[$away]['bbr'] = $bbr;
              $tt[$away]['bbn'] = 1;
            }
            if ($bbr == $tt[$away]['bbr'])
              $tt[$away]['bbn']++;

            if ($bbr > $best['a']['bbr'])
            {
              $best['a']['bbr'] = $bbr;
              $best['a']['rez'] = "$ga-$gh";
              $best['a']['team'] = "$away - $home ($tn тур),";
           }
            elseif ($bbr == $best['a']['bbr'])
              $best['a']['team'] .= "$away - $home ($tn тур),";

            if ($th != '*')
            {
              if ($bbr > $best['g']['bbr'])
              {
                $best['g']['bbr'] = $bbr;
                $best['g']['rez'] = "$ga-$gh";
                $best['g']['team'] = "$away - $home ($tn тур),";
              }
              elseif ($bbr == $best['g']['bbr'])
                $best['g']['team'] .= "$away - $home(*) ($tn тур),";

            }
          }
        }
        else
        {
          $tt[$home]['ser'][$tn] = 1;
          $tt[$home]['p']++;
          $tt[$home]['ph']++;
          $tt[$home]['d']++;
          $tt[$home]['dh']++;
          $tt[$away]['ser'][$tn] = 1;
          $tt[$away]['p']++;
          $tt[$away]['pa']++;
          $tt[$away]['d']++;
          $tt[$away]['da']++;
          $amb[$home]['x'] = 1;
          $amb[$away]['x'] = 1;
        }
      }
    }
    // [TournTable n-N]
    // [HomeTable n-N]
    // [AwayTable n-N]
    // n m w d l g s r p h => sort_decs: p, r, g, h, w, n
    // [Statistics n-N]
    $fr = 0;
    reset($tt);
    $leaders = array();
    while ($fr = (strpos($template, '[TournTable ', $fr + 1)))
    {
      $line = substr($template, $fr, strpos($template, ']', $fr) + 1 - $fr);
      $cut = strpos($line, '-');
      $n1 = substr($line, strpos($line, ' ') + 1, $cut - strpos($line, ' ') - 1);
      $n2 = substr($line, $cut + 1, strpos($line, ']', $cut) - $cut - 1);
      $rt = "$n1-$n2";
      $att = array();
      $aht = array();
      $aat = array();
      $stt = array('m'=>'0','h'=>'0','g'=>'0','i'=>'0'); // m h g i
      for ($nm=$n1; $nm<=$n2; $nm++)
      {
        $tn = key($tt);
        $att['n'][] = $tn;
        $att['t'][] = $coach[$tn];
        $att['m'][] = max(0, $tt[$tn]['m']);
        $att['w'][] = max(0, $tt[$tn]['w']);
        $att['d'][] = max(0, $tt[$tn]['d']);
        $att['l'][] = max(0, $tt[$tn]['l']);
        $att['g'][] = max(0, $tt[$tn]['g']);
        $att['s'][] = max(0, $tt[$tn]['s']);
        $att['r'][] = $tt[$tn]['r'];
        $att['p'][] = max(0, $tt[$tn]['p']);
        $att['h'][] = max(0, $tt[$tn]['h']);

        $aht['n'][] = $tn;
        $aht['m'][] = max(0, $tt[$tn]['mh']);
        $aht['w'][] = max(0, $tt[$tn]['wh']);
        $aht['d'][] = max(0, $tt[$tn]['dh']);
        $aht['l'][] = max(0, $tt[$tn]['lh']);
        $aht['g'][] = max(0, $tt[$tn]['gh']);
        $aht['s'][] = max(0, $tt[$tn]['sh']);
        if (isset($tt[$tn]['rh']))
          $aht['r'][] = $tt[$tn]['rh'];
        else
          $aht['r'][] = 0;

        $aht['p'][] = max(0, $tt[$tn]['ph']);
        $aht['h'][] = max(0, $tt[$tn]['hh']);

        $aat['n'][] = $tn;
        $aat['m'][] = max(0, $tt[$tn]['ma']);
        $aat['w'][] = max(0, $tt[$tn]['wa']);
        $aat['d'][] = max(0, $tt[$tn]['da']);
        $aat['l'][] = max(0, $tt[$tn]['la']);
        $aat['g'][] = max(0, $tt[$tn]['ga']);
        $aat['s'][] = max(0, $tt[$tn]['sa']);
        if (isset($tt[$tn]['ra']))
          $aat['r'][] = $tt[$tn]['ra'];
        else
          $aat['r'][] = 0;

        $aat['p'][] = max(0, $tt[$tn]['pa']);
        $aat['h'][] = max(0, $tt[$tn]['ha']);

        $stt['m'] += $tt[$tn]['m'];
        $stt['h'] += $tt[$tn]['h'];
        $stt['g'] += $tt[$tn]['g'];
        $stt['i'] += $tt[$tn]['i'];
        if (!next($tt))
          $n2 = $nm;
      }
      array_multisort($att['p'],SORT_DESC, $att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['h'],SORT_DESC, $att['w'],SORT_DESC, $att['n'], $att['l'], $att['s'], $att['d'], $att['m'], $att['t']);
      for($i=0; $i<sizeof($att['n']); $i++)
        $tt[$att['n'][$i]]['pl'] = $i + 1;

      $leaders[] = $att['n'][0];
      $leaders[] = $att['n'][1];
      $leaders[] = $att['n'][2];
      $tpltbl['TournTable'][$rt] = MakeTourTable($att); 
      $tpltbl['Wide[TournTable'][$rt] = MakeWideTourTable($att); 
      $tpltbl['HomeTable'][$rt] = MakeTourTable($aht); 
      $tpltbl['AwayTable'][$rt] = MakeTourTable($aat); 

      $tpltbl['Statistics'][$rt][] = '';
      $tpltbl['Statistics'][$rt][] = 'Всего угадано - '.$stt['h'];
      $tpltbl['Statistics'][$rt][] = 'Средняя угадываемость за тур - '.round($stt['h'] / ($stt['m'] - $stt['i']), 3);
      $tpltbl['Statistics'][$rt][] = 'Средняя результативность - '.round($stt['g'] * 2 / $stt['m'], 3);
      $tpltbl['Statistics'][$rt][] = 'Число неявок - '.$stt['i'];
      $tpltbl['Statistics'][$rt][] = 'Рейтинг Fair Play - '.round($stt['i'] / $stt['m'], 3);
    }
    $template = FillTemplate($template, 'Wide[Results', $tpltbl);
    $template = FillTemplate($template, 'Results', $tpltbl);
    $template = FillTemplate($template, 'Wide[TournTable', $tpltbl);
    $template = FillTemplate($template, 'TournTable', $tpltbl);
    $template = FillTemplate($template, 'HomeTable', $tpltbl);
    $template = FillTemplate($template, 'AwayTable', $tpltbl);
    $template = FillTemplate($template, 'Statistics', $tpltbl);
    if ($maxlname)
      $nmax = $maxlname + 2;
    else
      $nmax = $maxteam + 2;

    if ($nmax < 14)
      $nmax = 14;
    $nfmt = '%-'.$nmax.'s';


// tt: team =>
//  m mh ma  w wh wa  d dh da  l lh la  g gh ga  s sh sa  r rh ra  p ph pa
// bw bwh bwa bgh bga bsh bsa wag was ser h hh ha
  // [TeamBombers] в будущем возможен параметр n-N
  // g ga s pl
  $line = '[TeamBombers]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($tt as $tn => $atemp)
    {
      if (isset($lnames[$tn]))
        $att['n'][] = $lnames[$tn];
      else
        $att['n'][] = $tn;

      $att['g'][] = max(0, $tt[$tn]['g']);
      $att['ga'][] = max(0, $tt[$tn]['ga']);
      $att['s'][] = max(0, $tt[$tn]['s']);
      $att['pl'][] = $tt[$tn]['pl'];
    }
    array_multisort($att['g'],SORT_DESC, $att['ga'],SORT_DESC, $att['s'], $att['pl'], $att['n']);
    $out = '';
    for($i=0; $i<sizeof($att['n']); $i++)
      $out .= sprintf('%2s', $i+1).'.  '
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['g'][$i])
            . ' (в гостях - ' . sprintf('%2s', $att['ga'][$i])
            . ', пропущено - ' . sprintf('%2s', $att['s'][$i]).")\n";

    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [BestDiff] в будущем возможен параметр n-N
  // r g pl
  $line = '[BestDiff]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($tt as $tn => $atemp)
    {
      if (isset($lnames[$tn]))
        $att['n'][] = $lnames[$tn];
      else
        $att['n'][] = $tn;

      $att['r'][] = $tt[$tn]['r'];
      $att['g'][] = max(0, $tt[$tn]['g']);
      $att['s'][] = max(0, $tt[$tn]['s']);
      $att['pl'][] = $tt[$tn]['pl'];
    }
    array_multisort($att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['pl'], $att['s'], $att['n']);
    $out = '';
    for($i=0; $i<sizeof($att['n']); $i++)
      $out .= sprintf('%2s', $i+1).'.  '
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%3s', sprintf('%+d', $att['r'][$i]))
            . ' ('.$att['g'][$i].'-'.$att['s'][$i].")\n";

    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [Burglar] в будущем возможен параметр n-N
  // bw br bg bbb bwa bra bga !!! !!! pl
  $line = '[Burglar]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($tt as $tn => $atemp)
    {
      if (isset($lnames[$tn]))
        $att['n'][] = $lnames[$tn];
      else
        $att['n'][] = $tn;

      $att['bw'][] = max(0, $tt[$tn]['bw']);
      $att['br'][] = $tt[$tn]['bg'] - $tt[$tn]['bs'];
      $att['bg'][] = max(0, $tt[$tn]['bg']);
      $att['bs'][] = max(0, $tt[$tn]['bs']);
      $att['bbb'][] = max(0, $tt[$tn]['bbr']) * 10 + max(0, $tt[$tn]['bbn']);
      $att['bwa'][] = max(0, $tt[$tn]['bwa']);
      $att['bra'][] = $tt[$tn]['bga'] - $tt[$tn]['bsa'];
      $att['bga'][] = max(0, $tt[$tn]['bga']);
      $att['bsa'][] = max(0, $tt[$tn]['bsa']);
      $att['pl'][] = $tt[$tn]['pl'];
    }
    array_multisort($att['bw'],SORT_DESC, $att['br'],SORT_DESC, $att['bg'],SORT_DESC, $att['bbb'],SORT_DESC, $att['bwa'],SORT_DESC, $att['bra'],SORT_DESC, $att['bga'],SORT_DESC, $att['pl'], $att['n'], $att['bs'], $att['bsa']);
    $out = '';
    for($i=0; $i<sizeof($att['n']); $i++) if ($att['bw'][$i])
      $out .= sprintf('%2s', $i+1).'.  '
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['bw'][$i])
            . ' ('.$att['bg'][$i].'-'.$att['bs'][$i]
            . ') В гостях: '.sprintf('%2s', $att['bwa'][$i])
            . ' ('.$att['bga'][$i].'-'.$att['bsa'][$i].")\n";

    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [BlackHole] в будущем возможен параметр n-N
  // bw br bg bbb bwa bra bga !!! !!! pl
  $line = '[BlackHole]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($tt as $tn => $atemp)
    {
      if (isset($lnames[$tn]))
        $att['n'][] = $lnames[$tn];
      else
        $att['n'][] = $tn;

      $att['bw'][] = max(0, $tt[$tn]['bl']);
      $att['br'][] = $tt[$tn]['blg'] - $tt[$tn]['bls'];
      $att['bg'][] = max(0, $tt[$tn]['blg']);
      $att['bs'][] = max(0, $tt[$tn]['bls']);
      $att['bwa'][] = max(0, $tt[$tn]['bla']);
      $att['bra'][] = $tt[$tn]['lsa'] - $tt[$tn]['lga'];
      $att['bga'][] = max(0, $tt[$tn]['lga']);
      $att['bsa'][] = max(0, $tt[$tn]['lsa']);
      $att['pl'][] = $tt[$tn]['pl'];
    }
    array_multisort($att['bw'],SORT_DESC, $att['br'], $att['bg'],SORT_DESC, $att['bwa'], $att['bra'],SORT_DESC, $att['bga'],SORT_DESC, $att['pl'], $att['n'], $att['bs'], $att['bsa']);
    $out = '';
    for($i=0; $i<sizeof($att['n']); $i++) if ($att['bw'][$i])
      $out .= sprintf('%2s', $i+1).'.  '
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['bw'][$i])
            . ' ('.$att['bg'][$i].'-'.$att['bs'][$i]
            . ') В гостях: '.sprintf('%2s', $att['bwa'][$i])
            . ' ('.$att['bsa'][$i].'-'.$att['bga'][$i].")\n";

    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [AgroGuest] в будущем возможен параметр n-N
  // wa war wag pa ra ga pl
  $line = '[AgroGuest]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($tt as $tn => $atemp)
    {
      if (isset($lnames[$tn]))
        $att['n'][] = $lnames[$tn];
      else
        $att['n'][] = $tn;

      $att['wa'][] = max(0, $tt[$tn]['wa']);
      $att['war'][] = $tt[$tn]['wag'] - $tt[$tn]['was'];
      $att['wag'][] = max(0, $tt[$tn]['wag']);
      $att['was'][] = max(0, $tt[$tn]['was']);
      $att['pa'][] = max(0, $tt[$tn]['pa']);
      $att['ra'][] = $tt[$tn]['ra'];
      $att['ga'][] = max(0, $tt[$tn]['ga']);
      $att['pl'][] = $tt[$tn]['pl'];
    }
    array_multisort($att['wa'],SORT_DESC, $att['war'],SORT_DESC, $att['wag'],SORT_DESC, $att['pa'],SORT_DESC, $att['ra'],SORT_DESC, $att['ga'],SORT_DESC, $att['pl'], $att['n'], $att['was']);
    $out = '';
    for($i=0; $i<sizeof($att['n']); $i++) if ($att['wa'][$i])
      $out .= sprintf('%2s', $i+1).'.  '
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['wa'][$i])
            . ' ('.$att['wag'][$i].'-'.$att['was'][$i].")\n";

    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [BestResults] в будущем возможен параметр n-N
  $out = '';
  $outg = '';
  if ($best['d']['bbr'] > $best['g']['bbr'])
    $out .= sprintf('%-8s', $best['d']['rez']) . rtrim($best['d']['team'], ',')."\n";

  if (trim($best['g']['rez']))
    $out .= sprintf('%-8s', $best['g']['rez'].'г') . rtrim($best['g']['team'], ',')."\n";

  if (($best['h']['bbr'] > $best['d']['bbr']) && ($best['h']['bbr'] > $best['a']['bbr']))
    $outg .= sprintf('%-8s', $best['h']['rez'].'(*)') . rtrim($best['h']['team'], ',')."\n";

  if ($best['a']['bbr'] > $best['g']['bbr'])
    $outg .= sprintf('%-8s', $best['a']['rez'].'(*)г') . rtrim($best['a']['team'], ',')."\n";

  if ($outg)
    $out .= "\n  с учётом игр с генераторами:\n".$outg;

  $template = str_replace('[BestResults]', rtrim($out), $template);
  // [Firework] в будущем возможен параметр n-N
  $line = '[Firework]';
  $maxln = max(24, $maxteam * 2 + 4);
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($firework as $tn => $atemp)
    {
      $att['gg'][] = $atemp[0];
      $att['h'][] = $atemp[1];
      $att['a'][] = $atemp[2];
      $att['gh'][] = $atemp[3];
      if ($atemp[4] == '*')
        $att['th'][] = '(*)';
      else
        $att['th'][] = '   ';

      $att['ga'][] = $atemp[5];
      if ($atemp[6] == '*')
        $att['ta'][] = '(*)';
      else
        $att['ta'][] = '';

    }
    array_multisort($att['gg'],SORT_DESC, $att['ta'], $att['th'], $att['ga'],SORT_DESC, $att['gh'],SORT_DESC, $att['h'], $att['a']);
    $out = '';
    for($i=0; $i<min(10,sizeof($att['gg'])); $i++)
//      $out .= sprintf('%-'.$maxln.'s', $att['h'][$i].' - '.$att['a'][$i])
      $out .= $att['h'][$i].' - '.$att['a'][$i]
            . str_repeat(' ', $maxln - mb_strlen($att['h'][$i].' - '.$att['a'][$i]))
            . $att['th'][$i].$att['gh'][$i].':'.$att['ga'][$i].$att['ta'][$i]."\n";

    $template = str_replace($line, rtrim($out), $template);
  }
  // [GoldenBoots] в будущем возможен параметр n-N
  // h szoft pl p r g
  $line = "\n[GoldenBoots]";
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    foreach ($gb as $tr => $atemp)
    {
      $nm = $gb[$tr]['n'];
      $att['t'][] = $tr;
      $att['n'][] = $nm;
      $att['h'][] = $gb[$tr]['h'];
      $att['z'][] = sizeof($gb[$tr]['t']);
      $att['pl'][] = $tt[$nm]['pl'];
      $att['p'][] = $tt[$nm]['p'];
      $att['r'][] = $tt[$nm]['r'];
      $att['g'][] = $tt[$nm]['g'];
    }
    array_multisort($att['h'],SORT_DESC, $att['z'], $att['pl'], $att['p'],SORT_DESC, $att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n'], $att['h']);
    $out = '';
    $nt = $tourn - floor((64 - $maxcoach - $maxteam) / 3);
    for($i=0; $i<sizeof($att['t']); $i++) if ($tr = trim($att['t'][$i]))
    {
      $gb[$tr]['p'] = $i + 1;
      $out .= sprintf('%2s', $i+1).'.  '
            . $tr . str_repeat(' ', $maxcoach + 1 - mb_strlen($tr))
            . $gb[$tr]['n'] . str_repeat(' ', $maxteam + 1 - mb_strlen($gb[$tr]['n']))
            . sprintf('%3s', $att['h'][$i]);

      $missed = 0;
      for ($j=1; $j<=$tourn; $j++)
      {
        if (isset($gb[$tr]['t'][$j]))
        {
          if ($j > $nt)
            $out .= sprintf('%3s', $gb[$tr]['t'][$j]);

        }
        else
        {
          $missed++;
          if ($j > $nt)
            $out .= sprintf('%3s', '-');

        }
      }
      $out .= sprintf('%6s', "($missed)\n");
    }
    $hl1 = '';
    $hl2 = '';
    for ($j=max(1, $nt + 1); $j<=$tourn; $j++)
      $hl1 .= sprintf('%3s', $j);

    $hl2 = strtr($hl1, '0123456789', '----------');
    $template = str_replace('АЯ БУТСА"', 'АЯ БУТСА"'.sprintf('%'.($maxcoach+$maxteam-12).'s', ' ')."Всего$hl1 проп", $template);
    $out = sprintf('%'.($maxcoach+$maxteam-13).'s', ' ')."-----$hl2 ----\n"
         . str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [Series]
  $line = '[Series]';
  if ($fr = (strpos($template, $line)))
  {
    $nt = floor((78 - $maxteam) / 2); 
    $out = '';
    ksort($tt);
    $series = array();
    foreach ($tt as $tn => $atemp)
    {
      $out .= $tn . str_repeat(' ', $maxteam + 1 - mb_strlen($tn));
      $pr = '';
      $pg0 = 0;
      $ps0 = 0;
      $series[$tn]['0']['p']['n'] = 0;
      $series[$tn]['0']['v']['n'] = 0;
      $series[$tn]['0']['n']['n'] = 0;
      $series[$tn]['0']['np']['n'] = 0;
      $series[$tn]['0']['nv']['n'] = 0;
      $series[$tn]['0']['s0'] = 0;
      $series[$tn]['0']['g0'] = 0;
      $series[$tn]['0']['p']['p'] = 0;
      $series[$tn]['0']['v']['p'] = 0;
      $series[$tn]['0']['n']['p'] = 0;
      $series[$tn]['0']['np']['p'] = 0;
      $series[$tn]['0']['nv']['p'] = 0;
      $series[$tn]['0']['s']['n'] = 0;
      for ($j=1; $j<=$tourn; $j++)
      {
        $series[$tn][$j]['p']['n'] = 0;
        $series[$tn][$j]['v']['n'] = 0;
        $series[$tn][$j]['n']['n'] = 0;
        $series[$tn][$j]['np']['n'] = 0;
        $series[$tn][$j]['nv']['n'] = 0;
        $series[$tn][$j]['s0'] = 0;
        $series[$tn][$j]['g0'] = 0;
        $series[$tn][$j]['p']['p'] = 0;
        $series[$tn][$j]['v']['p'] = 0;
        $series[$tn][$j]['n']['p'] = 0;
        $series[$tn][$j]['np']['p'] = 0;
        $series[$tn][$j]['nv']['p'] = 0;
        $pt = 1;
        if (isset($tt[$tn]['ser'][$j]))
          $rr = $tt[$tn]['ser'][$j];
        else
           $rr = '-';

        if ($j > ($tourn - $nt))
          $out .= " $rr";

        if ($tt[$tn]['sg'][$j])
          $pg0 = 0;
        else
        {
          $series[$tn][$j]['g0'] = 1;
          if ($pg0++)
            $series[$tn][$j]['g0'] = $series[$tn][$j-$pt]['g0'] + 1;

          unset($series[$tn][$j-$pt]['g0']);
        }
        if ($tt[$tn]['ss'][$j])
          $ps0 = 0;
        else
        {
          $series[$tn][$j]['s0'] = 1;
          if ($ps0++)
            $series[$tn][$j]['s0'] = $series[$tn][$j-$pt]['s0'] + 1;

          unset($series[$tn][$j-$pt]['s0']);
        }
        switch ($rr)
        {
          case '0':
          $series[$tn][$j]['p']['n'] = 1;
          $series[$tn][$j]['nv']['n'] = 1;
          $series[$tn][$j]['nv']['p'] = 0;
          if ($pr == '0')
          {
            $series[$tn][$j]['p']['n'] = $series[$tn][$j-$pt]['p']['n'] + 1;
            unset($series[$tn][$j-$pt]['p']);
            $series[$tn][$j]['nv']['n'] = $series[$tn][$j-$pt]['nv']['n'] + 1;
            $series[$tn][$j]['nv']['p'] = $series[$tn][$j-$pt]['nv']['p'];
            unset($series[$tn][$j-$pt]['nv']);
          }
          if ($pr == '1')
          {
            $series[$tn][$j]['nv']['n'] = $series[$tn][$j-$pt]['nv']['n'] + 1;
            $series[$tn][$j]['nv']['p'] = $series[$tn][$j-$pt]['nv']['p'];
            unset($series[$tn][$j-$pt]['nv']);
          }
          break;
          case '3':
          $series[$tn][$j]['v']['n'] = 1;
          $series[$tn][$j]['np']['n'] = 1;
          $series[$tn][$j]['np']['p'] = 3;
          if ($pr == '3')
          {
            $series[$tn][$j]['v']['n'] = $series[$tn][$j-$pt]['v']['n'] + 1;
            unset($series[$tn][$j-$pt]['v']);
            $series[$tn][$j]['np']['n'] = $series[$tn][$j-$pt]['np']['n'] + 1;
            $series[$tn][$j]['np']['p'] = $series[$tn][$j-$pt]['np']['p'] + 3;
            unset($series[$tn][$j-$pt]['np']);
          }
          if ($pr == '1')
          {
            $series[$tn][$j]['np']['n'] = $series[$tn][$j-$pt]['np']['n'] + 1;
            $series[$tn][$j]['np']['p'] = $series[$tn][$j-$pt]['np']['p'] + 3;
            unset($series[$tn][$j-$pt]['np']);
          }
          break;
          case '1':
          $series[$tn][$j]['n']['n'] = 1;
          $series[$tn][$j]['np']['n'] = 1;
          $series[$tn][$j]['np']['p'] = 1;
          $series[$tn][$j]['nv']['n'] = 1;
          $series[$tn][$j]['nv']['p'] = 1;
          if ($pr == '1')
          {
            $series[$tn][$j]['n']['n'] = $series[$tn][$j-$pt]['n']['n'] + 1;
            unset($series[$tn][$j-$pt]['n']);
            $series[$tn][$j]['np']['n'] = $series[$tn][$j-$pt]['np']['n'] + 1;
            $series[$tn][$j]['np']['p'] = $series[$tn][$j-$pt]['np']['p'] + 1;
            unset($series[$tn][$j-$pt]['np']);
            $series[$tn][$j]['nv']['n'] = $series[$tn][$j-$pt]['nv']['n'] + 1;
            $series[$tn][$j]['nv']['p'] = $series[$tn][$j-$pt]['nv']['p'] + 1;
            unset($series[$tn][$j-$pt]['nv']);
          }
          if ($pr == '3')
          {
            $series[$tn][$j]['np']['n'] = $series[$tn][$j-$pt]['np']['n'] + 1;
            $series[$tn][$j]['np']['p'] = $series[$tn][$j-$pt]['np']['p'] + 1;
            unset($series[$tn][$j-$pt]['np']);
          }
          if ($pr == '0')
          {
            $series[$tn][$j]['nv']['n'] = $series[$tn][$j-$pt]['nv']['n'] + 1;
            $series[$tn][$j]['nv']['p'] = $series[$tn][$j-$pt]['nv']['p'] + 1;
            unset($series[$tn][$j-$pt]['nv']);
          }
          break;
          default:
          break;
        }
        $pr = $rr;
      }
      $out .= "\n";
    }
    $template = str_replace($line, rtrim($out), $template);
  }
  // [LongSeries]
  $line = '[LongSeries]';
  if ($fr = (strpos($template, $line)))
  {
    $out = '
победные серии:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['v']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['v']['n'];
        $att['t'][] = $ct;
      }

    array_multisort($att['l'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
            . ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].')';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";

    }
    $out .= '
серии без поражений:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['np']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['np']['n'];
        $att['t'][] = $ct;
        $att['p'][] = $series[$tn][$ct]['np']['p'];
      }

    array_multisort($att['l'],SORT_DESC, $att['p'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].', '.$att['p'][$i].' оч.)';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= '
ничейные серии:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['n']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['n']['n'];
        $att['t'][] = $ct;
      }

    array_multisort($att['l'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].')';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= '
серии без побед:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['nv']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['nv']['n'];
        $att['t'][] = $ct;
        $att['p'][] = 0 + $series[$tn][$ct]['nv']['p'];
    }

    array_multisort($att['l'],SORT_DESC, $att['p'], $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].
      ', '.$att['p'][$i].' оч.)';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= '
проигрышные серии:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['p']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;
        $att['l'][] = $series[$tn][$ct]['p']['n'];
        $att['t'][] = $ct;
      }

    array_multisort($att['l'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].')';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= '
сухие серии:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['s0']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['s0'];
        $att['t'][] = $ct;
      }

    array_multisort($att['l'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].')';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= '
голевая засуха:
--------------------------------------
';
    $att = array();
    foreach ($series as $tn => $atemp)
      foreach ($atemp as $ct => $atemp1) if (isset($atemp1['g0']))
      {
        if (isset($lnames[$tn]))
          $att['n'][] = $lnames[$tn];
        else
          $att['n'][] = $tn;

        $att['l'][] = $series[$tn][$ct]['g0'];
        $att['t'][] = $ct;
      }

    array_multisort($att['l'],SORT_DESC, $att['t'],SORT_DESC, $att['n']);
    $min = max(2, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= $att['n'][$i].str_repeat(' ', $nmax - mb_strlen($att['n'][$i])).sprintf('%2s', $att['l'][$i]).
      ' (туры '.($att['t'][$i]-$att['l'][$i]+1).'-'.$att['t'][$i].')';
      if ($att['t'][$i++] == $tourn)
        $out .= '*';

      $out .= "\n";
    }
    $out .= "\n* - непрерванная серия\n";
    $template = str_replace($line, $out, $template);
  }
  // [LeaderKiller] в будущем возможен параметр n-N
  // r g pl
  $line = '[LeaderKiller]';
  if ($fr = (strpos($template, $line)))
  {
    $att = array();
    $atn = array();
    foreach ($tt as $tn => $atemp)
    {
      $pts = 0;
      $ptn = '';
      for ($i=1; $i<=$tourn; $i++)
        if (in_array($atemp['en'][$i], $leaders)) if ($atemp['ser'][$i])
        {
          $pts += $atemp['ser'][$i];
          $ptn .= $atemp['en'][$i].':'.$atemp['ser'][$i].',';
        }

      $att[$tn] = $pts;
      $atn[$tn] = trim($ptn, ',');
    }
    arsort($att);
    $out = '';
    $i = 0;
    $max = 3;
    $prev = 99;
    foreach($att as $tn => $pts)
    {
      if (($i == $max) && ($pts >= $prev))
        $max++;

      if ($i++ < $max)
      {
        if (isset($lnames[$tn]))
          $nm = $lnames[$tn];
        else
          $nm = $tn;

        $out .= sprintf('%2s', $i) . '.  '
              . $nm . str_repeat(' ', $nmax -  mb_strlen($nm))
              . sprintf('%3s', $pts).' ('. $atn[$tn] .")\n";
        $prev = $pts;
      }
    }
    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [MiniBoots] в будущем возможен параметр n-N
  // ch x cr cg f=($a?$h+vs*) pl p r g ga
  $att = array();
  foreach ($tt as $tn => $atemp) if (isset($amb[$tn]))
  {
    if (isset($lnames[$tn]))
      $att['n'][] = $lnames[$tn];
    else
      $att['n'][] = $tn;

    $att['t'][] = $amb[$tn]['t'];
    $att['h'][] = $amb[$tn]['h'];
    $att['x'][] = $amb[$tn]['x'];
    $att['cr'][] = $amb[$tn]['r'];
    $att['cg'][] = $amb[$tn]['g'];
    $att['f'][] = $amb[$tn]['f'];
    $att['pl'][] = $tt[$tn]['pl'];
    $att['p'][] = $tt[$tn]['p'];
    $att['r'][] = $tt[$tn]['r'];
    $att['g'][] = $tt[$tn]['g'];
    $att['ga'][] = $tt[$tn]['ga'];
  }
  array_multisort($att['h'],SORT_DESC, $att['x'],SORT_DESC, $att['cr'],SORT_DESC, $att['cg'],SORT_DESC, $att['f'],SORT_DESC, $att['pl'], $att['p'],SORT_DESC, $att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['ga'],SORT_DESC, $att['t'], $att['n']);
  $out = '';
  for($i=0; $i<sizeof($att['n']); $i++) if (trim($att['t'][$i]))
  {
    switch ($att['f'][$i])
    {
      case '0': $f = 'д с ген.'; break;
      case '1': $f = 'д'; break;
      case '2': $f = 'г с ген.'; break;
      case '3': $f = 'г'; break;
    }
    $out .= sprintf('%2s', $i+1).'.  '
          . $att['t'][$i] . str_repeat(' ', $maxcoach + 1 - mb_strlen($att['t'][$i]))
          . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
          . sprintf('%2s', $att['h'][$i]).'  ('
          . $att['cg'][$i].':'.($att['cg'][$i] - $att['cr'][$i])."$f)\n";
  }
  $out = str_replace(' 1. ', '_1_.', $out);
  $template = str_replace('[MiniBoots]', rtrim($out), $template);
  $atemp = file($sfname);
  $superb = array();
  foreach ($atemp as $line) if ($line = trim($line))
  {
    $al = explode(',', $line);
    $superb[$al[0]]['t'] = $al[1];
    $superb[$al[0]]['n'] = $al[2];
    $superb[$al[0]]['d'] = $al[3];
    $superb[$al[0]]['h'] = $al[4];
    $superb[$al[0]]['m'] = $al[5];
  }
  $superb[$tourn]['t'] = $att['t'][0];
  $superb[$tourn]['n'] = $att['n'][0];
  $superb[$tourn]['d'] = $att['h'][0] - $att['h'][1];
  $superb[$tourn]['h'] = $att['h'][0];
  $m = 0;
  foreach ($realmatch as $atemp) if ($atemp['case'] != '-')
    $m++;

  $superb[$tourn]['m'] = $m;
  $content = '';
  foreach ($superb as $i => $al)
    $content .= $i.','.$al['t'].','.$al['n'].','.$al['d'].','.$al['h'].','.$al['m']."\n";

  file_put_contents($sfname, $content);
  sleep(1);
  // [SuperBoots] в будущем возможен параметр n-N
  // b d h/m pl p r g
  $line = "\n[SuperBoots]";
  if ($fr = (strpos($template, $line)))
  {
    $atb = array();
    for ($i=1; $i<=$tourn; $i++) if ($tn = trim($superb[$i]['t']))
    {
      $atb[$tn]['n'] = $superb[$i]['n'];
      $atb[$tn]['b']++;
      $atb[$tn]['d'] += $superb[$i]['d'];
      $atb[$tn]['h'] += $superb[$i]['h'];
      $atb[$tn]['m'] += $superb[$i]['m'];
    }
    $att = array();
    foreach ($atb as $ct => $atemp)
    {
      $att['t'][] = $ct;
      if (isset($lnames[$atb[$tn]['n']]))
        $att['n'][] = $lnames[$atb[$tn]['n']];
      else
        $att['n'][] = $atb[$tn]['n'];

      $att['b'][] = $atb[$tn]['b'];
      $att['d'][] = $atb[$tn]['d'];
      $att['h'][] = $atb[$tn]['h'];
      $att['m'][] = $atb[$tn]['m'];
      $att['pl'][] = $tt[$nm]['pl'];
      $att['p'][] = $tt[$nm]['p'];
      $att['r'][] = $tt[$nm]['r'];
      $att['g'][] = $tt[$nm]['g'];
    }
    for($i=0; $i<sizeof($att['t']); $i++)
      $att['o'][$i] = $att['h'][$i] / $att['m'][$i];

    array_multisort($att['b'],SORT_DESC, $att['d'],SORT_DESC, $att['o'],SORT_DESC, $att['pl'], $att['p'],SORT_DESC, $att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n'], $att['h'], $att['m']);
    $out = sprintf('%-'.($maxcoach-12).'s', ' ').str_repeat(' ', $nmax).'раз отрыв рез  из
';
    for($i=0; $i<sizeof($att['t']); $i++)
    {
      $tr = $att['t'][$i];
      $out .= sprintf('%2s', $i+1).'.  ' . $tr . str_repeat(' ', $maxcoach + 1 - mb_strlen($tr))
            . $att['n'][$i] . str_repeat(' ', $nmax - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['b'][$i]) . sprintf('%5s', $att['d'][$i])
            . sprintf('%5s', $att['h'][$i]) . sprintf('%5s', $att['m'][$i])."\n";
    }
    $out = str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [DarkHorse]
  $line = "\n[DarkHorse]";
  if ($fr = (strpos($template, $line)))
  {
    $atemp = file($hfname);
    $horse = array();
    foreach ($atemp as $l) if ($l = trim($l))
    {
      $al = explode(',', $l);
      if ($al[2] <= $tourn)
      {
        $tn = $al[0];
        $horse[$tn]['n'] = $al[1];
        $horse[$tn]['c']++;
        if (isset($horse[$tn]['t']) && ($horse[$tn]['t'] == $al[2]))
          $horse[$tn]['s'] = rtrim($horse[$tn]['s'], ',').$al[3].',';
        else
          $horse[$tn]['s'] .= $al[2].'/'.$al[3].',';

        if (strlen($horse[$tn]['s']) > $maxln && !strpos($horse[$tn]['s'], "\n"))
          $horse[$tn]['s'] .= "\n                                            ";

        $horse[$tn]['t'] = $al[2];
        if ($al[3] == '2')
           $al[3] = 400; 
        elseif ($al[3] == 'X')
           $al[3] = 20;

        $horse[$tn]['f'] += $al[3];
        $horse[$tn]['p'] += $al[4];
      }
    }
    $att = array();
    $maxln = 24;
    foreach ($horse as $tn => $atemp)
    {
      $att['t'][] = $tn;
      $att['n'][] = $horse[$tn]['n'];
      $att['c'][] = $horse[$tn]['c'];
      $att['f'][] = $horse[$tn]['f'];
      $att['p'][] = $horse[$tn]['p'];
      $att['g'][] = $gb[$tn]['p'];
      // $maxln = max($maxln, strlen($horse[$tn]['s']));
    }
    array_multisort($att['c'],SORT_DESC, $att['p'],SORT_DESC, $att['f'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n']);
    $out = '';
    for($i=0; $i<sizeof($att['t']); $i++)
    {
      $tr = $att['t'][$i];
      $out .= sprintf('%2s', $i+1).'.  '
            . $tr . str_repeat(' ', $maxcoach + 1 - mb_strlen($tr))
            . $att['n'][$i] . str_repeat(' ', $maxteam + 1 - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['c'][$i]).'  '
            . rtrim($horse[$tr]['s'], ',') . str_repeat(' ', $maxln - mb_strlen(rtrim($horse[$tr]['s'], ',')))
            . sprintf('%4s', $att['p'][$i])."\n";
    }
    $out = str_repeat(' ', $maxcoach + $maxteam - mb_strlen('раз тур/рез')) . 'раз тур/рез'.
           sprintf('%'.($maxln-1).'s', 'из')."\n".  str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [WhiteRaven]
  $line = "\n[WhiteRaven]";
  if ($fr = (strpos($template, $line)))
  {
    $atemp = file($vfname);
    $raven = array();
    foreach ($atemp as $l) if ($l = trim($l))
    {
      $al = explode(',', $l);
      if ($al[2] <= $tourn)
      {
        $tn = $al[0];
        $raven[$tn]['n'] = $al[1];
        $raven[$tn]['c']++;
        if (isset($raven[$tn]['t']) && ($raven[$tn]['t'] == $al[2]))
          $raven[$tn]['s'] = rtrim($raven[$tn]['s'], ',').$al[3].',';
        else
          $raven[$tn]['s'] .= $al[2].'/'.$al[3].',';

        if (strlen($raven[$tn]['s']) > $maxln && !strpos($raven[$tn]['s'], "\n"))
          $raven[$tn]['s'] .= "\n                                            ";

        $raven[$tn]['t'] = $al[2];
        if ($al[3] == '2')
          $al[3] = 400; 
        elseif ($al[3] == 'X')
          $al[3] = 20;

        $raven[$tn]['f'] += $al[3];
        $raven[$tn]['p'] += $al[4];
      }
    }
    $att = array();
    $maxln = 24;
    foreach ($raven as $t => $atemp)
    {
      $att['t'][] = $tn;
      $att['n'][] = $raven[$tn]['n'];
      $att['c'][] = $raven[$tn]['c'];
      $att['f'][] = $raven[$tn]['f'];
      $att['p'][] = $raven[$tn]['p'];
      $att['g'][] = $gb[$tn]['p'];
      // $maxln = max($maxln, strlen($raven[$tn]['s']));
    }
    array_multisort($att['c'],SORT_DESC, $att['p'],SORT_DESC, $att['f'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n']);
    $out = '';
    for($i=0; $i<sizeof($att['t']); $i++)
    {
      $tr = $att['t'][$i];
      $out .= sprintf('%2s', $i+1).'.  '
            . $tr . str_repeat(' ', $maxcoach + 1 - mb_strlen($tr))
            . $att['n'][$i] . str_repeat(' ', $maxteam + 1 - mb_strlen($att['n'][$i]))
            . sprintf('%2s', $att['c'][$i]).'  '
            . rtrim($raven[$tr]['s'], ',') . str_repeat(' ', $maxln - mb_strlen(rtrim($raven[$tr]['s'], ',')))
            . sprintf('%4s', $att['p'][$i])."\n";
    }
    $out = str_repeat(' ', $maxcoach + $maxteam - mb_strlen('раз тур/рез')) . 'раз тур/рез'.
           sprintf('%'.($maxln-1).'s', 'из')."\n".
    str_replace(' 1. ', '_1_.', $out);
    $template = str_replace($line, rtrim($out), $template);
  }
  // [Cards]
  $maxln = max(14, $maxteam);
  $out = sprintf('%'.($maxln).'s', 'Тур  ');
  $nt = $tourn - floor((79 - $maxln) / 2);
  for ($i=1; $i<=$tourn; $i++) if ($i>$nt)
    $out .= sprintf('%2s', $i);

  $out .= "\n";
  foreach ($cards as $nm => $atour)
  {
    $has = false;
    for ($i=1; $i<=$tourn; $i++) if (trim($atour[$i]))
      $has = true;

    if ($has)
    {
      $out .= $nm . str_repeat(' ', $maxln - mb_strlen($nm));
      for ($i=1; $i<=$tourn; $i++) if ($i>$nt)
        $out .= $atour[$i];

      $out .= "\n";
    }
  }
  $template = str_replace('[Cards]', rtrim($out), $template);
  file_put_contents($itfname, $template);
 }
  // review
  if (is_file($rfname))
    rename($rfname, $rfname.'.'.time());

  $content = file_get_contents($online_dir."$country_code/$season/header");
  $content .= "ОБЗОР МАТЧЕЙ $tourn-ГО ТУРА\n=======================\n";
  $bs = file_get_contents($bfname);
  if (mb_detect_encoding($bs, 'UTF-8', true) === FALSE)
    $bs = iconv('CP1251', 'UTF-8//IGNORE', $bs);

  $content .= $bs;
  $bs = file_get_contents($bsfname);
  if (mb_detect_encoding($bs, 'UTF-8', true) === FALSE)
    $bs = iconv('CP1251', 'UTF-8//IGNORE', $bs);

  $content .= "БОМБАРДИРЫ ПОСЛЕ $tourn-ГО ТУРА\n===========================\n";
  $content .= substr($bs, strpos($bs, '-') + 18);
  file_put_contents($rfname, $content);

  return $template;
}

function build_prognozlist($country_code, $season, $tour)
{
  global $online_dir;
  $teams = array();
  $maxteam = 0;
  $acodes = file($online_dir."$country_code/$season/codes.tsv");
  foreach ($acodes as $scode) if ($scode[0] != '#')
  {
    $ateams = explode('	', ltrim($scode, '-'));
    $teams[$ateams[0]] = $ateams[1];
    $maxteam = max($maxteam, mb_strlen($ateams[1]));
  }
  $prognozlist = '';
  $mbox = file($online_dir."$country_code/$season/prognoz/$tour/mail");
  $have = array();
  $aprognoz = array();
  foreach ($mbox as $msg) {
    if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE)
      $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);
    $ta = explode(';', $msg);
    $team = $ta[0];
    $warn = '';
    if (isset($teams[$team])) $team = $teams[$team];
    else $warn = 'oЖ';
    if (in_array($team, $have)) $warn = '!!!';
    else $have[] = $team;
    $prognozlist .= $team . str_repeat(' ', 21 - mb_strlen($team)) . htmlspecialchars(sprintf('%-20s', $ta[1]))
                  . $warn . str_repeat(' ',  5 - mb_strlen($warn)) . date('d M y  H:i:s', $ta[2] + 3600) . "\n";
    if ($penalties = trim($ta[3])) $prognozlist .= '                     '.mb_strtolower($penalties)."\n";
    if (!isset($aprognoz[$team]['time']) || ($ta[2] > $aprognoz[$team]['time']))
    {
      $aprognoz[$team]['prog'] = $ta[1];
      $aprognoz[$team]['time'] = $ta[2];
      $aprognoz[$team]['pena'] = $ta[3];
      $aprognoz[$team]['warn'] = $warn;
    }
  }

  if (is_file($online_dir."$country_code/$season/prognoz/$tour/adds")) {
    $addfile = file_get_contents($online_dir."$country_code/$season/prognoz/$tour/adds");
    if (mb_detect_encoding($addfile, 'UTF-8', true) === FALSE)
      $addfile = iconv('CP1251', 'UTF-8//IGNORE', $addfile);
    $months = array(
    ' января' => ' Jan',' янваpя' => ' Jan',
    ' февраля' => ' Feb', ' февpаля' => ' Feb',
    ' марта' => ' Mar', ' маpта' => ' Mar',
    ' апреля' => ' Apr', ' апpеля' => ' Apr',
    ' мая' => ' May',
    ' июня' => ' Jun',
    ' июля' => ' Jul',
    ' августа' => ' Aug',
    ' сентября' => ' Sep', ' сентябpя' => ' Sep',
    ' октября' => ' Oct',  ' октябpя' => ' Oct',
    ' ноября' => ' Nov',  ' ноябpя' => ' Nov',
    ' декабря' => ' Dec', ' декабpя' => ' Dec',
    ' г.,' => '', ' г.' => '', ' inet' => ''
    );
    foreach ($months as $rus => $eng)
      $addfile = str_replace($rus, $eng, $addfile);
    $prognozlist .= "дополнения, поправки, наказания:\n";
    $added = explode("\n", $addfile);
    foreach ($added as $line) if ($line = rtrim($line)) {
      if ($line[0] != ' ') {
        $team = trim(mb_substr($line, 0, 20));
        $line = trim(mb_substr($line, 20));
        if ($cut = min(20, strpos($line, ' ', 15))) {
          $prognoz = trim(substr($line, 0, $cut));
          $line = trim(substr($line, $cut));
        }
        else {
          $prognoz = trim($line);
          $line = '';
        }
        if (($line[0] >= '0') && ($line[0] <= '9')) {
          $warn = '     ';
        }
        else {
          if (mb_strlen($line) > 4 && $line[4] == ' ') $wln = 5; else $wln = 4;
          $warn = mb_substr($line, 0, $wln);
          $line = mb_substr($line, $wln);
          $warn = str_replace('с', 'c', $warn);
          $warn = str_replace('о', 'c', $warn);
          $warn = str_replace('а', 'a', $warn);
          $warn = str_replace('K', 'К', $warn);
//          $warn = strtr($warn, 'соаK', 'coaК');
        }
        if ($time = trim(substr($line, 0, 29))) $time = strtotime(trim(substr($line, 0, 29)).' CET');
        else $time = 0;
        if ($time) $date = date('d M y  H:i:s', $time + 3600);
        else $date = '';
        $prognozlist .= $team . str_repeat(' ', 21 - mb_strlen($team)) . htmlspecialchars(sprintf('%-20s', $prognoz))
                      . $warn . str_repeat(' ',  5 - mb_strlen($warn)) . "$date\n";
        $aprognoz[$team]['time'] = $time;
        $aprognoz[$team]['prog'] = $prognoz;
        $aprognoz[$team]['warn'] = $warn;
      }
      else {
        $prognozlist .= htmlspecialchars($line)."\n";
        $aprognoz[$team]['pena'] = $line;
      }
    }
  }

  // parse program
  $programm = file_get_contents($online_dir."$country_code/$season/programms/$tour");
  if (mb_detect_encoding($programm, 'UTF-8', true) === FALSE)
    $programm = iconv('CP1251', 'UTF-8//IGNORE', $programm);
  $fr = mb_strpos($programm, "Последний с");
  $programm = mb_substr($programm, $fr);
  $atemp = array();
  $calfp = explode("\n", $programm);
  foreach ($calfp as $line)
  if ((strpos($line, ' - ') || strpos($line, ' *')) && !mb_strpos($line, 'ГОСТИ') && !mb_strpos($line, 'Гости')) {
    if (($cut = mb_strpos($line, '  ')) != false) {
      $line2 = trim(mb_substr($line, $cut));
      $line1 = trim(mb_substr($line, 0, $cut));
    }
    else {
      $line1 = trim($line);
      $line2 = '';
    }
    if ($line2) $atemp[] = array($line1, $line2);
    else if ($line1) $atemp[][0] = $line1;
  }
  $cal = '';
  $gen = '';
  foreach ($atemp as $lines) {
    if (strpos($lines[0], ' - ')) $cal .= $lines[0]."\n";
    if (strpos($lines[0], ' *'))  $gen .= $lines[0];
    if (sizeof($lines) > 1 && strpos($lines[1], ' *')) $gen .= '  '.$lines[1];
    if (strpos($lines[0], ' *') || (sizeof($lines) > 1 && strpos($lines[1], ' *'))) $gen .= "\n";
  }
  foreach ($atemp as $lines)
    if (sizeof($lines) > 1 && strpos($lines[1], ' - '))
      $cal .= $lines[1]."\n";
//  if (is_file("$country_code/$season/$tcode/$calfname"))
//    $calt = trim(GetTourFromCalendar(str_replace('NEW', '', $tour), file_get_contents("$country_code/$season/$tcode/$calfname")));
//  if ($calt) $cal = $calt;
  $virtmatch = array();
  $atemp = explode("\n", $cal);
  foreach ($atemp as $line) if ($line = trim($line)) {
    if ($cut = mb_strpos($line, '  '))
      $line = mb_substr($line, 0, $cut);
    $virtmatch[] = $line;
  }

  // now virtual matches
  $prognozlist .= "\n----------------------\n";
  $cnmtpl = '%-15s';
  // select generators
  $gensets = 0;
  // if no generators in the program read from gen
  if (!trim($gen)) {
    if ($tour[3] == 'C') $genfname = $online_dir."$country_code/$season/genc";
    else $genfname = $online_dir."$country_code/$season/gen";
    if (is_file($genfname)) {
      // parse generators
      $gensets = 1;
      $gen = file_get_contents($genfname);
      if (strpos($gen, $tour)) {
        $begin = $tour;
        $end = $country_code;
      }
      else {
        if ($tour[3] == 'C')
          $n = ltrim(substr($tour, strlen($country_code) + 1), '0');
        else
          $n = ltrim(substr($tour, strlen($country_code)), '0');
        $begin = "Тур $n";
        $end = "Тур";
      }
      $fr = mb_strpos($gen, $begin);
      $fr = mb_strpos($gen, "\n", $fr) + 1;
      if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '='))
        $fr = mb_strpos($gen, "\n", $fr) + 1;
      if ($to = mb_strpos($gen, $end, $fr))
        $gen = trim(mb_substr($gen, $fr, $to - $fr));
      else
        $gen = trim(mb_substr($gen, $fr));
    }
  }

  if ($gen) {
    $generator = array();
    $gen = str_replace('*', '', $gen);
    $atemp = explode("\n", $gen);
    foreach ($atemp as $line) if ($line = trim($line)) {
      if ($cut = mb_strpos($line, '  ')) {
        $generator['1'][] = trim(mb_substr($line, 0, $cut));
        $generator['2'][] = trim(mb_substr($line, $cut));
        $gensets = 2;
      }
      else
        $generator['1'][] = trim($line);
    }
  }

  // формирование таблиц виртуальных матчей
  $n = 0;
  $g = 0;
  $s = 1;
  $z = sizeof($virtmatch);
  if ($gensets == 2)
    $z = $z / 2;
  foreach ($virtmatch as $line) {
    $atemp = explode(' - ', $line);

    $h = trim($atemp[0]);
    if ($aprognoz[$h]['time'])
      $date = date('d M y  H:i:s', $aprognoz[$h]['time'] + 3600);
    else
      $date = '';
    if (!isset($aprognoz[$h]['prog'])) {
      $aprognoz[$h]['prog'] = $generator[$s][$g];
      $aprognoz[$h]['warn'] = '*Ж';
      $date = '                   ';
      $g++;
    }
    $addline = $h . str_repeat(' ', 21 - mb_strlen($h)) . htmlspecialchars(sprintf('%-20s', $aprognoz[$h]['prog']));
    $addline .= $aprognoz[$h]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$h]['warn'])) . "$date\n";
    $prognozlist .= $addline;
    if ($date == '                   ')
      $addfile .= $addline;

    $a = trim($atemp[1]);
    if ($aprognoz[$a]['time'])
      $date = date('d M y  H:i:s', $aprognoz[$a]['time'] + 3600);
    else
      $date = '';
    if (!isset($aprognoz[$a]['prog'])) {
      $aprognoz[$a]['prog'] = $generator[$s][$g];
      $aprognoz[$a]['warn'] = '*Ж';
      $date = '                   ';
      $g++;
    }
    $addline = $a . str_repeat(' ', 21 - mb_strlen($a)) . htmlspecialchars(sprintf('%-20s', $aprognoz[$a]['prog']));
    $addline .= $aprognoz[$a]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$a]['warn'])) . "$date\n\n";
    $prognozlist .= $addline;
    if ($date == '                   ')
      $addfile .= $addline;

    if (++$n > $z) {
      $g = 0;
      $s = 2;
      $z = sizeof($virtmatch);
    }
  }
//  file_put_contents($online_dir."$country_code/$season/prognoz/$tour/adds", $addfile);
  return $prognozlist;
}

$senders = array(
'QUOTAS' => 'UEFA <uefa@fprognoz.org>',
'BLR' => '"PFL of Belarus" <blr@fprognoz.org>',
'ENG' => '"FPL of England" <eng@fprognoz.org>',
'ESP' => '"FPL of Spain" <esp@fprognoz.org>',
'FRA' => '"PFL of France" <fra@fprognoz.org>',
'GER' => '"PFL of Germany" <ger@fprognoz.org>',
'ITA' => '"PFL of Italy" <itl@fprognoz.org>',
'NLD' => '"PFL of Netherlands" <nld@fprognoz.org>',
'RUS' => '"PFL of Russia" <rus@fprognoz.org>',
'PRT' => '"PFL of Portugal" <prt@fprognoz.org>',
'SCO' => '"PFL of Scotland" <sco@fprognoz.org>',
'UKR' => '"PFL of Ukraine" <ukr@fprognoz.org>',
'UEFA' => 'UEFA <uefa@fprognoz.org>',
'SBN' => '"PFL of SBNet" <sbn@fprognoz.org>',
'WL' => '"World League" <wl@fprognoz.org>',
'WLS' => '"World League" <wl@fprognoz.org>',
);
$subjects = array(
'QUOTAS' => 'ФП. Пресс-релиз УЕФА.',
'BLR' => 'ФП. Беларусь.',
'ENG' => 'ФП. Англия.',
'ESP' => 'ФП. Испания.',
'FRA' => 'ФП. Франция.',
'GER' => 'ФП. Германия.',
'ITA' => 'ФП. Италия.',
'NLD' => 'ФП. Голландия.',
'RUS' => 'ФП. Россия.',
'PRT' => 'ФП. Португалия.',
'SCO' => 'ФП. Шотландия.',
'UKR' => 'ФП. Украина.',
'UEFA' => 'ФП. Лиги УЕФА.',
'SBN' => 'ФП. SBNet.',
'WL' => 'ФП. Мировая Лига.',
'WLS' => 'ФП. Мировая Лига.',
);

if (isset($_POST['sendmail']) && !isset($_POST['sendnews'])) $sendnews = '';
else $sendnews = ' checked';
if (isset($_POST['sendmail']) && !isset($_POST['sendinet'])) $sendinet = '';
else $sendinet = ' checked';
if (isset($_SESSION['Country_code']) && isset($_SESSION['Coach_name']) && isset($_SESSION['Session_password']))
{
  $country_code = $_SESSION['Country_code'];
  $dir = scandir($online_dir."$country_code");
  $season = '';
  foreach ($dir as $subdir) if ($subdir[0] == '2') $season = $subdir;

  if (isset($_POST['sendmail']) && trim($_POST['subject']) && trim($_POST['msgtext']))
  {

  //////////////////////////////// SENDER

    $file = $_POST['file'];
    if (is_file($file)) rename($file, $file.'.'.time());
    file_put_contents($file, $_POST['msgtext']);

    if ($sendinet)
    {
      $from = $senders[$country_code];
      $acodes = file($online_dir."$country_code/$season/codes.tsv");
      $sentto = array();
      foreach ($acodes as $scode) if ($scode[0] != '#')
      {
        $ateams = explode('	', trim($scode)); // $name = $ateams[2]; $email = $ateams[3];
        if (!in_array($ateams[2], $sentto)) 
        {
          $sentto[] = $ateams[2];
          send_email($senders[$country_code], $ateams[2], $ateams[3], $_POST['subject'], $_POST['msgtext']);
        }
      }
    }
  }


  else
  {

  //////////////////////////////// EDITOR

    if (isset($_GET['file']))
    {
      $file = $_GET['file'];
      if ($file == 'program')
      {
        $file = $online_dir."$cca/$s/publish/p".strtolower($t);
        if ($t[0] == 'G') $subject = " Программка золотого матча";
        else $subject = ' Программка '.ltrim($t, '0CPGS').'-го тура';
      }
      else if ($file == 'itogi')
      {
        $body = build_itogi($cca, $s, $cca.strtoupper($t));
        sleep(1);
        $file = $online_dir."$cca/$s/publish/it".strtolower($t);
        if ($t[0] == 'G') $subject = " Итоги золотого матча";
        else $subject = ' Итоги '.ltrim($t, '0CPGS').'-го тура';
      }
      else if ($file == 'review')
      {
        $file = $online_dir."$cca/$s/publish/r".strtolower($t);
        if ($t[0] == 'G') $subject = " Обзор золотого матча";
        else $subject = ' Обзор '.ltrim($t, '0CPGS').'-го тура';
      }
      else if ($file == 'prognoz')
      {
        $file = $online_dir."$cca/$s/publish/$cca".strtoupper($t);
//        if (is_file($file))
//          rename($file, $file.'.'.time());
        if (is_file($online_dir."$cca/$s/codes.tsv")) {
          $body = build_prognozlist($cca, $s, $cca.strtoupper($t));
//$body = "build_prognozlist($cca, $s, $cca" . strtoupper($t).")";
          $body = str_replace('&lt;', '<', $body);
          if (mb_detect_encoding($body, 'UTF-8', true) === FALSE)
            $body = iconv('CP1251', 'UTF-8//IGNORE', $body);
//        file_put_contents($file, $body);
        }
/* не здесь, а при фактической публикации !!!
        touch($online_dir."$cca/$s/prognoz/$cca".strtoupper($t)."/published");
        if (!strpos(file_get_contents($online_dir."$cca/$/prognoz/$cca".strtoupper($t)."/adds"), '*'))
          touch($online_dir."$cca/$season/prognoz/$cca".strtoupper($t)."/closed");
*/
        if ($t[0] == 'G') $subject = " Принятые прогнозы на золотой матч";
        else $subject = ' Принятые прогнозы на '.ltrim($t, '0CPGS').'-й тур';
      }
        if ($t[0] == 'C') $subject .= " кубка";
        else if ($t[0] == 'P') $subject .= " плей-офф";
        else if ($t[0] == 'S') $subject .= " суперкубка";
    }
    else
      $file = $online_dir."$cca/$s/publish/".time();

    if (isset($subject))
    {
      if (!isset($body)) $body = file_get_contents($file);
      if (mb_detect_encoding($body, 'UTF-8', true) === FALSE)
        $body = iconv('KOI8-R', 'UTF-8//IGNORE', $body);
    }
    else
    {
      $subject = '';
      $body = '';
    }
    $rows = max(35, substr_count($body, "\n"));
    echo '<form method=POST>
<table width=916>
<tr><td>От:</td><td>'.htmlspecialchars($senders[$country_code]).'
<input type=hidden name=file value="'.$file.'"><img src="images/spacer.gif" width=190 height=1>
Получатели: игроки <input type=checkbox name=sendinet'.$sendinet.'>
SU.FOOTBALL.PROGNOZ <input type=checkbox name=sendnews disable="disabled">
</td></tr>
<tr><td>Тема:</td><td><input name=subject value="'.$subjects[$country_code].$subject.'" size=99>
<img src="images/spacer.gif" width=12 height=1>
<input type=submit name=sendmail value="Отправить"></td></tr>
<tr><td>Текст:</td><td><textarea name=msgtext wrap=virtual rows='.$rows.' cols=100>'.$body.'</textarea></td></tr>
</table>
</form>
';
  }
}
else echo 'access denied';
?>
