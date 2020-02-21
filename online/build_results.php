#!/usr/bin/php
<?php
mb_internal_encoding('UTF-8');

function mb_sprintf($format) {
  $argv = func_get_args();
  array_shift($argv);
  return mb_vsprintf($format, $argv);
}

function mb_vsprintf($format, $argv, $encoding=null) {
  if (is_null($encoding)) $encoding = mb_internal_encoding();
  // Use UTF-8 in the format so we can use the u flag in preg_split
  $format = mb_convert_encoding($format, 'UTF-8', $encoding);
  $newformat = ""; // build a new format in UTF-8
  $newargv = array(); // unhandled args in unchanged encoding
  while ($format !== "") {
    // Split the format in two parts: $pre and $post by the first %-directive
    // We get also the matched groups
    list ($pre, $sign, $filler, $align, $size, $precision, $type, $post) =
        preg_split("!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
        $format, 2, PREG_SPLIT_DELIM_CAPTURE);
    $newformat .= mb_convert_encoding($pre, $encoding, 'UTF-8');
    if ($type == '%') $newformat .= '%%'; // an escaped %
    else if ($type == 's') {
      $arg = array_shift($argv);
      $arg = mb_convert_encoding($arg, 'UTF-8', $encoding);
      $padding_pre = $padding_post = '';
      if ($precision !== '') { // truncate $arg
        $precision = intval(substr($precision,1));
        if ($precision > 0 && mb_strlen($arg,$encoding) > $precision)
          $arg = mb_substr($precision,0,$precision,$encoding);
      }
      if ($size > 0) { // define padding
        $arglen = mb_strlen($arg, $encoding);
        if ($arglen < $size) {
          if ($filler == '') $filler = ' ';
          else if ($filler[0] == "'") $filler = substr($filler, 1);
          ($align == '-') ? $padding_post = str_repeat($filler, $size - $arglen)
                          : $padding_pre = str_repeat($filler, $size - $arglen);
        }
      }
      // escape % and pass it forward
      $newformat .= $padding_pre . str_replace('%', '%%', $arg) . $padding_post;
    }
    else if ($type != '') { // another type, pass forward
      $newformat .= "%$sign$filler$align$size$precision$type";
      $newargv[] = array_shift($argv);
    }
    $format = strval($post);
  }
  // Convert new format back from UTF-8 to the original encoding
  $newformat = mb_convert_encoding($newformat, $encoding, 'UTF-8');
  return vsprintf($newformat, $newargv);
}

function get_results($lastdate) {
  global $online_dir;
  list($day, $month) = explode('.', $lastdate);
  $date = sprintf('%02d-%02d', trim($month), trim($day));
  $year = date('Y', time());
//  $month = date('m', time());
//  (trim($atemp[1]) > ($month + 1) && $lastdate != '31.12') ? $fyear = $year - 1 : $fyear = $year;
//  ($month > 7) ? $fyear = $year - 1 : $fyear = $year;
  $fyear = $year;
  $base = array();
  $week = date('W', strtotime($fyear.'-'.$date));
  if (in_array($date, ['12-31'])) $week = '01';
  if (in_array($date, ['12-26'])) $fyear = $year - 1;

  $fname = $fyear.'.'.$week;
  $archive = is_file($online_dir . 'results/'.$fname) ? file($online_dir . 'results/'.$fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
  if (++$week == '53') //54
  {
    $week = '01';
    $fyear++;
  }
  else if (strlen($week) == 1)
    $week = '0'.$week;

  $fname = $fyear.'.'.$week;
  if (is_file($online_dir . 'results/'.$fname))
    $archive = array_merge($archive, file($online_dir . 'results/'.$fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

  foreach ($archive as $line)
    if (strpos($line, ',')) {
      $data = explode(',', $line);
      $match = $data[0].' - '.$data[1];
      $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
      $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
    }

  return $base;
}

function parse_cal_and_gen($program) {
  $acal = array();
  $agen = array();
  $calfp = explode("\n", $program);
  unset($calfp[1], $calfp[0]);
  foreach ($calfp as $line)
    if ((strpos($line, ' - ') || strpos($line, ' *'))
     && !mb_strpos($line, 'ГОСТИ') && !mb_strpos($line, 'Гости')) {
      if (strpos($line, '*')) { // parse line with generator
        if ($fr = strpos($line, ' - ')) { // match and generator
          $cut = strpos($line, '  ', $fr);
          if ($cut) {
            $acal[0][] = trim(substr($line, 0, $cut));
            $agen[] = trim(substr($line, $cut));
          }
        }
        else { // generators only
          $agen[] = trim($line);
/*
          $n = 0;
          while ($cut = strpos($line, ' *')) {
            $agen[$n++][] = trim(substr($line, 0, $cut + 2));
            $line = substr($line, $cut + 2);
          }
*/
        }
      }
      else { // line w/o generator
        $n = 0;
        $line .= '  ';
        while ($fr = strpos($line, ' - ')) {
          $cut = strpos($line, '  ', $fr);
          if ($cut) {
            $acal[$n++][] = trim(substr($line, 0, $cut));
            $line = substr($line, $cut);
          }
          else
            break;

        }
      }
    }

  $cal = $gen = '';
  foreach ($acal as $a)
    foreach ($a as $l)
      $cal .= "$l\n";

  foreach ($agen as $l)
    $gen .= "$l\n";

//  foreach ($agen as $a) foreach ($a as $l) $gen .= "$l\n";

  return [$cal, $gen];
}

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
function BOMB($country_code, $calfname)
{
  global $season_dir;
  global $tcode;
  $abomber = $ateam = $axx = [];
  $header = '';
  if ($calfname == 'cal')
  {
    $bombdir = 'bomb';
    $bsortfn = 'BOMBSORT';
    $ballfn  = 'BOMB_ALL';
  }
  elseif ($calfname == 'calp')
  {
    $bombdir = 'bombp';
    $bsortfn = 'BOMBSORTP';
    $ballfn  = 'BOMB_ALLP';
  }
  elseif ($calfname == 'cals')
  {
    $bombdir = 'bombs';
    $bsortfn = 'BOMBSORTS';
    $ballfn  = 'BOMB_ALLS';
  }
  else
  {
    $bombdir = $tcode.'bombc';
    $bsortfn = $tcode.'BOMBSORTC';
    $ballfn  = $tcode.'BOMB_ALLC';
  }
  $dir = scandir($season_dir.$bombdir);
  foreach ($dir as $file) if ($file[0] != '.')
  {
    $header .= "Processing file $file\n";
    $bomb = file_get_contents($season_dir.$bombdir.'/'.$file);
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
    $out .= sprintf('%2s', $i).mb_sprintf('%-50s', $line).sprintf('%2s', $abomber[$bomber])."\n";
    next($abomber);
  }
  file_put_contents($season_dir.$bsortfn, $out);

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
    $out .= mb_sprintf('%-50s', $line) . sprintf('%2s', $abomber[$bomber])."\n";
  }
  file_put_contents($season_dir.$ballfn, $out);
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

function MakeRealmatch($program)
{
  global $online_dir;
    // выборка данных по реальным матчам
    $fr = strpos($program, "Контрольный с");
    $matches = explode("\n", substr($program, 0, $fr));
    $program = substr($program, $fr);
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
      $program = str_replace($word, $digit, $program);

    $fr = strpos($program, '.');
    $lastdate = trim(substr($program, $fr - 2, 5));
    if (($fr1 = strpos($program, ':', $fr)) && ($fr1 - $fr < 50))
      $lasttm = trim(substr($program, $fr1 - 2, 5));
    else
      $lasttm = '';

    require_once('tournament.inc.php');
    include ('realteam.inc.php');
    $base = get_results($lastdate);
    $atemp = explode('.', $lastdate);
    $date = trim(sprintf('%02s',$atemp[1]).'-'.sprintf('%02s',$atemp[0]));
/*
    $year = date('Y', time());
    $month = date('m', time());
    if (trim($atemp[1]) > ($month + 1) && $lastdate != '31.12')
      $fyear = $year - 1;
    else
      $fyear = $year;
    $week = date('W', strtotime("$fyear-$date"));
//    if ($week == '53') { $week = '01'; }
    if ($week == '54') { $week = '01'; }
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
//    if ($week == '53') { $week = '01'; }
//    elseif (++$week == '53') { $week = '01'; $fyear++; }
    if ($week == '54') { $week = '01'; }
    elseif (++$week == '54') { $week = '01'; $fyear++; }
    if (strlen($week) == 1) $week = '0'.$week;
    $fname = $fyear.'.'.$week;
    if (is_file($online_dir."results/$fname")) $archive = file($online_dir."results/$fname"); else $archive = array();
    foreach ($archive as $line)
    {
      $data = explode(',', trim($line));
      $match = $data[0].' - '.$data[1];
      if (!isset($base[$match]) || $base[$match][4] == '-')
        $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);

      if (!isset($base[$match.'/'.$data[6]]))
        $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);

    }
*/
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

    if (!isset($aprognoz[$team]['time']) || ($ta[2] > $aprognoz[$team]['time'])) {
      $aprognoz[$team]['prog'] = strtr($ta[1], ['x' => 'X', 'х' => 'X', 'Х' => 'X', '0' => 'X']);
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
        if ($cut = min(21, strpos($line, ' ', 15))) {
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
          $wln = (mb_strlen($line) > 4 && $line[4] == ' ') ? 5 : 4;
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

function GetGen($country_code, $genfname) {
  global $season_dir;
  if (is_file($season_dir.$genfname)) {
    $gen = file_get_contents($season_dir.$genfname);
    if (strpos($gen, $tour)) {
      $begin = $tour;
      $end = $country_code;
    }
    else {
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

function group_table($group, $rounds=2)
{
  global $acal;
  global $ateams;
  global $nameln;
  $upper = ['┌','─','┬','─','┐'];
  $rowln = ['│','▒','│',' ','│'];
  $midle = ['├','─','┼','╨','┤'];
  $lower = ['└','─','┴','─','┘'];
  $rez = $gen = $teamt = $ret = [];
  $cal = $acal[$group];
  $teams = $ateams[$group];
  $nteams = count($teams);
  $nmatches = count($cal);
  foreach ($teams as $team => $i)
    $teamt[$team]['p'] = $teamt[$team]['w'] = $teamt[$team]['d'] = $teamt[$team]['l'] = $teamt[$team]['g'] = $teamt[$team]['s'] = 0;

  for ($i=0; $i<$nmatches; $i++)
  {
    $split1 = strpos($cal[$i], ' - ');
    $split2 = strpos($cal[$i], '  ', $split1 + 3);
    $split3 = strpos($cal[$i], '  ', $split2 + 3);
    $ho = trim(substr($cal[$i], 0, $split1));
    $go = trim(substr($cal[$i], $split1 + 3, $split2 - $split1 - 3));
    if ($re = trim(substr($cal[$i], $split2, $split3 - $split2)))
    {
      $ge = trim(substr($cal[$i], $split3));
      $rez[$ho][$go] = $re;
      $gen[$ho][$go] = $ge;
      if ($rounds == 1)
      { // для однокругового турнира
        $atemp = explode(':', $re);
        $rez[$go][$ho] = $atemp[1].':'.$atemp[0];
        $gen[$go][$ho] = $ge;
      }
/* СОРТИРОВКА СПИСКА КОМАНД
        Более высокое место занимает команда, набpавшее большее количество очков.
                                     (вес 10000000)
        Пpи pавном кол-ве очков более высокое место полyчает та команда, y котоpой:
        - лyчше pазница мячей;         (вес 100000)
        - большее кол-во забитых мячей;  (вес 1000)
        - большее кол-во yгаданных исходов; (вес 1)
*/
      list($hg, $ag) = explode(':', $re);
      if ($hg > $ag)
      {
          $teams[$ho] += 30000000;
          $teamt[$ho]['p'] += 3;
          $teamt[$ho]['w'] ++;
          $teamt[$go]['l'] ++;
      }
      else if ($hg < $ag)
      {
          $teams[$go] += 30000000;
          $teamt[$go]['p'] += 3;
          $teamt[$go]['w'] ++;
          $teamt[$ho]['l'] ++;
      }
      else {
          $teams[$ho] += 10000000;
          $teams[$go] += 10000000;
          $teamt[$ho]['p']++;
          $teamt[$go]['p']++;
          $teamt[$ho]['d']++;
          $teamt[$go]['d']++;
      }
      $teamt[$ho]['g'] += $hg;
      $teamt[$go]['g'] += $ag;
      $teamt[$ho]['s'] += $ag;
      $teamt[$go]['s'] += $hg;
      $diff = ($hg - $ag) * 100000;
      $teams[$ho] += $diff;
      $teams[$go] -= $diff;
      $teams[$ho] += $hg * 1000;
      $teams[$go] += $ag * 1000;
      $atemp = explode(':', $ge);
      $teams[$ho] += $atemp[1];
      $teams[$go] += rtrim($atemp[3], ')');
    }
  }
  $team = [];
  arsort($teams);
  reset($teams);
  foreach($teams as $name => $sortpoints)
    $team[] = $name;

  $out =  ' Группа ' . ($group + 1) . ':' . str_repeat(' ', $nameln - 7);
  for ($j = 1; $j <= $nteams; $j++)
    $out .= sprintf("%3d ", $j);

  $out .= '   В  Н  П   М   О';
  $ret[] = $out;
  $out = str_repeat(' ', $nameln + 3) . $upper[0];
  for ($j=1; $j<$nteams; $j++)
    $out .= $upper[1] . $upper[1] . $upper[1] . $upper[2];

  $out .= $upper[1] . $upper[1] . $upper[1] . $upper[4];
  $ret[] = $out;
  for ($h = 0; $h < $nteams; $h++)
  {
    $n = $h + 1;
    $line1 = sprintf("%2s", $n) . '.' . mb_substr($team[$h], 0, $nameln) . str_repeat(' ', $nameln - mb_strlen(mb_substr($team[$h], 0, $nameln)));
    $line2 = str_repeat(' ', $nameln + 3);
    for ($g=0; $g<$nteams; $g++)
    {
      $line1 .= $rowln[0];
      if ($g == 0 && $h < ($nteams - 1))
        $line2 .= $midle[0];

      if ($g > 0 && $h < ($nteams - 1))
        $line2 .= $midle[2];

      if ($g == 0 && $h == ($nteams - 1))
        $line2 .= $lower[0];

      if ($g > 0 && $h == ($nteams - 1))
        $line2 .= $lower[2];

      if ($h == $g)
      {
        $line1 .= str_repeat($rowln[1], 3);
        $line2 .= str_repeat($midle[1], 3);
      }
      else
      {
        if (isset($rez[$team[$h]][$team[$g]]))
        {
          $line1 .= $rez[$team[$h]][$team[$g]];
          $line2 .= (strpos($gen[$team[$h]][$team[$g]],'(*:') !== false ? $midle[3] : $midle[1]) .
                  $midle[1] . (strpos($gen[$team[$h]][$team[$g]],':*:') ? $midle[3] : $midle[1]);
        }
        else
        {
            $line1 .= '   ';
            $line2 .= str_repeat($midle[1], 3);
        }
      }
    }
    $line1 .= $rowln[4];
    $line2 .= $h < ($nteams - 1) ? $midle[4] : $lower[4];
    $ret[] = $line1.'  '.+$teamt[$team[$h]]['w'].'  '.+$teamt[$team[$h]]['d'].'  '.+$teamt[$team[$h]]['l'].'  '.+$teamt[$team[$h]]['g'].'-'.+$teamt[$team[$h]]['s'].' '.sprintf('%2s', +$teamt[$team[$h]]['p']);
    $ret[] = $line2;
  }
  $ret[] = '';
  return $ret;
}

//function get_groups($tcode, $s)
function get_groups($tcode, $ccal)
{
  global $acal;
  global $ateams;
  global $nameln;
//  global $online_dir;
//  $ccal = file_get_contents($online_dir.'UEFA/'.$s.'/'.$tcode.'/calc');
  $fr = $longest = 0;
  while ($fr = strpos($ccal, $tcode, $fr))
  {
    $fr = strpos($ccal, "\n", $fr) + 1;
    $to = strpos($ccal, "\n\n", $fr);
    $tour = substr($ccal, $fr, $to - $fr);
    $matches = explode("\n", $tour);
    if (count($matches) >= 8)
    {
      $ngroups = count($matches) / 2;
      if (!count($ateams)) for ($i=0; $i<$ngroups; $i++)
      { // формирование составов групп по первому туру с числом матчей >= 8
        $line = $matches[2 * $i];
        $line = substr($line, 0, strpos($line, '  '));
        $team = substr($line, 0, strpos($line, ' - '));
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
        $team = substr($line, strpos($line, ' - ') + 3);
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));

        $line = $matches[2 * $i + 1];
        $line = substr($line, 0, strpos($line, '  '));
        $team = substr($line, 0, strpos($line, ' - '));
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
        $team = substr($line, strpos($line, ' - ') + 3);
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
      }
      for ($i=0; $i<$ngroups; $i++)
      { // формирование календарей групп
        $acal[$i][] = $matches[2 * $i];
        $acal[$i][] = $matches[2 * $i + 1];
      }
    }
  }
  if (!isset($nameln))
    $nameln = $longest;

}

function init_tt() {
  $a = [
    'm', 'mh', 'ma', 'w', 'wh', 'wa', 'wag', 'was', 'd', 'dh', 'da', 'l', 'lh', 'la', 'lgh', 'lga', 'lsh', 'lsa',
    'g', 'gh', 'ga', 's', 'sh', 'sa', 'h', 'hh', 'ha', 'r', 'rh', 'ra', 'p', 'ph', 'pa', 'pl', 'i',
    'bw', 'bwh', 'bwa', 'bg', 'bgh', 'bga', 'bs', 'bsh', 'bsa', 'bl', 'blh', 'bla', 'blg', 'bls', 'bbn', 'bbr'];
  return array_fill_keys($a, 0);
}

function horse_raven($fname, $tourn, $gb, $maxcoach, $maxteam) {
  global $cca;

  $a = [];
  $maxln = 24;
  $file = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (!sizeof($file))
    return '';

  foreach ($file as $line)
  {
    list($tn, $team, $tour, $bet, $players) = explode(',', $line);
    if ($tour <= $tourn)
    {
      if (!isset($a[$tn]))
        $a[$tn] = ['c' => 0, 'f' => 0, 'n' => $team, 'p' => 0, 's' => ''];

      $a[$tn]['c']++;
      if (isset($a[$tn]['t']) && $a[$tn]['t'] == $tour)
        $a[$tn]['s'] = rtrim($a[$tn]['s'], ',').$bet.',';
      else
        $a[$tn]['s'] .= "$tour/$bet,";

      if (strlen($a[$tn]['s']) > $maxln && !strpos($a[$tn]['s'], "\n"))
        $a[$tn]['s'] .= "\n                                            ";

      $a[$tn]['t'] = $tour;
      $a[$tn]['f'] += $bet == '2' ? 400 : $bet == 'X' ? 20 : 1;
      $a[$tn]['p'] += $players;
    }
  }
  $att = [];
  foreach ($a as $tn => $at)
  {
    $att['t'][] = $tn;
    $att['n'][] = $at['n'];
    $att['c'][] = $at['c'];
    $att['f'][] = $at['f'];
    $att['p'][] = $at['p'];
    $att['g'][] = $cca == 'UEFA' ? $gb[$tn][$at['n']]['p'] : $gb[$tn]['p'];
    // $maxln = max($maxln, strlen($a[$tn]['s']));
  }
  array_multisort($att['c'],SORT_DESC, $att['p'],SORT_DESC, $att['f'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n']);
  $out = mb_sprintf('%'.($maxcoach + $maxteam).'s', 'раз тур/рез').sprintf('%'.$maxln.'s', "из\n");
  for($i = 0; $i < sizeof($att['t']); $i++)
  {
    $tr = $att['t'][$i];
    $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.') . mb_sprintf(' %-'.($maxcoach + 1).'s', $tr)
        . mb_sprintf('%-'.($maxteam + 1).'s', $att['n'][$i]) . sprintf('%2s', $att['c'][$i]).'  '
        . mb_sprintf('%-'.$maxln.'s', rtrim($a[$tr]['s'], ',')) . sprintf('%4s', $att['p'][$i])."\n";
  }
  return rtrim($out);
}

function build_itogi($country_code, $season, $tour)
{
  global $online_dir;
  global $season_dir;
  global $tcode;
  $teams = $coach = $lnames = [];
  $maxteam = $maxcoach = $maxlname = 0;
  $acodes = file($season_dir.'codes.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($acodes as $scode) if ($scode[0] != '#')
  {
    list($code, $tname, $cname, $email, $lname, $conf) = explode('	', ltrim($scode, '-'));
    $teams[$code] = $tname;
    $maxteam = max($maxteam, mb_strlen($tname));
    $coach[$tname] = $cname;
    $maxcoach = max($maxcoach, mb_strlen($cname));
    if ($lname)
    {
      $lnames[$tname] = $lname;
      $maxlname = max($maxlname, mb_strlen($lname));
    }
  }
  // формирование показа программки тура с реальными результатами
  if (is_file($season_dir.'programs/'.$tour))
  {
    if ($country_code == 'UEFA' || $tour[strlen($country_code)] == 'C')
    {
      $calfname = $tcode.'calc';
      $genfname = $tcode.'genc';
      $cardsfname = $tcode.'cardsc';
      $itplfname = $season_dir.'itc.tpl';
      $itfname = $season_dir.'publish/'.$tcode.'itc'.substr(str_replace('NEW', '', $tour), strlen($country_code) + 1);
      $rfname = $season_dir.'publish/'.$tcode.'rc'.substr(str_replace('NEW', '', $tour), strlen($country_code) + 1);
      $hfname = $season_dir.$tcode.'horsec';
      $vfname = $season_dir.$tcode.'ravenc';
      $sfname = $season_dir.$tcode.'superbc';
      $bfname = $season_dir.$tcode.'bombc/b'.sprintf('%02s', substr(str_replace('NEW', '', $tour), strlen($country_code) + 1));
      $bsfname = $season_dir.$tcode.'BOMBSORTC';
    }
    else if ($tour[strlen($country_code)] == 'S')
    {
      $calfname = 'cals';
      $genfname = 'gens';
      $cardsfname = 'cardss';
      $itplfname = $season_dir.'itc.tpl';
      $itfname = $season_dir.'publish/its'.substr($tour, -1);
      $rfname = $season_dir.'publish/rs'.substr($tour, -1);
      $hfname = $vfname = $sfname = '';
      $bfname = $season_dir.'bombs/b'.sprintf('%02s', substr(str_replace('NEW', '', $tour), -1));
      $bsfname = $season_dir.'BOMBSORTS';
    }
    elseif ($tour[strlen($country_code)] == 'P')
    {
      $calfname = 'calp';
      $genfname = 'genp';
      $cardsfname = 'cardsp';
      $itplfname = $season_dir.'itc.tpl';
      $itfname = $season_dir.'publish/itp'.substr($tour, -1);
      $rfname = $season_dir.'publish/rp'.substr($tour, -1);
      $hfname = $vfname = $sfname = '';
      $bfname = $season_dir.'bombp/b'.sprintf('%02s', substr(str_replace('NEW', '', $tour), -1));
      $bsfname = $season_dir.'BOMBSORTP';
    }
    else
    {
      $calfname = 'cal';
      $genfname = 'gen';
      $cardsfname = 'cards';
      $itplfname = $season_dir.'it.tpl';
      $itfname = $season_dir.'publish/it'.substr(str_replace('NEW', '', $tour), -2);
      $rfname = $season_dir.'publish/r'.substr(str_replace('NEW', '', $tour), -2);
      $hfname = $season_dir.$tcode.'horse';
      $vfname = $season_dir.$tcode.'raven';
      $sfname = $season_dir.$tcode.'superb';
      $bfname = $season_dir.$tcode.'bomb/b'.substr(str_replace('NEW', '', $tour), -2);
      $bsfname = $season_dir.$tcode.'BOMBSORT';
    }
    // парсинг программки
    $program = file_get_contents($season_dir.'programs/'.$tour);
    $program = str_replace(')-', ') - ', $program);
    $fr = strpos($program, "$tour ");
    //$fr = strpos($program, ' 1.') - strlen($program);
    $fr = strpos($program, "\n", $fr) + 1;
    $program = substr($program, $fr);
    $realmatch = MakeRealmatch($program);
    $fr = strpos($program, 'Контрольный с');
    $program = substr($program, $fr);
    list($cal, $gen) = parse_cal_and_gen($program);
    if (!trim($gen))
      $gen = GetGen($country_code, $genfname);

    if ($gen) {
      $generator = array();
      $gen = str_replace('*', '', $gen);
      $atemp = explode("\n", $gen);
      foreach ($atemp as $line) if ($line = trim($line)) {
        if ($cut = strpos($line, '  ')) {
          $generator['1'][] = trim(substr($line, 0, $cut));
          $line = trim(substr($line, $cut));
          if ($cut = strpos($line, '  ')) {
            $generator['2'][] = trim(substr($line, 0, $cut));
            $generator['3'][] = trim(substr($line, $cut));
            $gensets = 3;
          }
          else {
            $generator['2'][] = trim($line);
            $gensets = 2;
          }
        }
        else {
          $generator['1'][] = trim($line);
          $gensets = 1;
        }
      }
    }
    if (!trim($cal))
      $cal = GetTourFromCalendar($tour, file_get_contents($season_dir.$calfname));

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
  if (is_file($season_dir.$cardsfname))
  {
    $atemp = file($season_dir.$cardsfname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($atemp as $line)
      $cards[trim(mb_substr($line, 0, 20))] = mb_split(',', mb_substr($line, 20));

  }
  $rprognoz = '';
  for ($i=1; $i<=count($realmatch); $i++)
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
  $z = $z / $gensets;
  $plrs = array();
  $bomb = array();
  $results = array();
  $wresults = array();
  foreach ($virtmatch as $line) {
    $atemp = explode(' - ', $line);
    $home = trim($atemp[0]);
    if (isset($aprognoz[$home]['prog']) && (!isset($aprognoz[$home]['warn'][0]) || $aprognoz[$home]['warn'][0] != '*'))
      $rplayers++;
    $away = trim($atemp[1]);
    if (isset($aprognoz[$away]['prog']) && (!isset($aprognoz[$away]['warn'][0]) || $aprognoz[$away]['warn'][0] != '*'))
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
    if ($ln = count($realmatch) - 10 - strlen($atemp[1]))
    {
      $atemp[1] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[1] = substr($atemp[1], 0, count($realmatch) - 10);
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
            while (($rprognoz[$replacer] == '-') && ($replacer < count($realmatch)))
              $replacer++; // поиск первого неиспользованного форо-заменителя

          }
          $rep = $replacer;
        }
        else
        {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < count($realmatch)))
            $rep++; // поиск первого неиспользованного заменителя

        }
////////  $prognoz[$rep+1] = '=';
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж'))
      $warn = '  ';

    $aprognoz[$home]['warn'] = $warn;
    $warn = str_replace('Ж', '░', $warn);
    $warn = str_replace('К', '▓', $warn);
    $line1 = sprintf('%'.(15 + $ppos).'s', $podstr);
    $line2 = $home . str_repeat(' ', 14 - mb_strlen($home)) . sprintf('%-16s', $prognoz) . ' '
            .$warn . str_repeat(' ', 2 - mb_strlen($warn)) . ' ';
    $prognozh = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*')
      $sprognoz[$home] = $prognozh;

    $match = $home;
    if (isset($aprognoz[$home]['warn'][0]) && $aprognoz[$home]['warn'][0] == '*') $match .= '(*)';
    $match .= ' - '.$away;
    if (isset($aprognoz[$away]['warn'][0]) && $aprognoz[$away]['warn'][0] == '*') $match .= '(*)';
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
    if ($ln = count($realmatch) - 10 - strlen($atemp[1]))
    {
      $atemp[1] .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0)
    {
      $atemp[1] = substr($atemp[1], 0, count($realmatch) - 10);
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
            while (($rprognoz[$replacer] == '-') && ($replacer < count($realmatch)))
              $replacer++; // поиск первого неиспользованного форо-заменителя
          }
          $rep = $replacer + 1;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < count($realmatch)))
            $rep++; // поиск первого неиспользованного заменителя

        }
        else
        {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < count($realmatch)))
            $rep++; // поиск первого неиспользованного заменителя

        }
////////    $prognoz[$rep+1] = '=';
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж'))
      $warn = '  ';

    $aprognoz[$away]['warn'] = $warn;
    $warn = str_replace('Ж', '░', $warn);
    $warn = str_replace('К', '▓', $warn);
    $line3 = $away . str_repeat(' ', 14 - mb_strlen($away)) . sprintf('%-16s', $prognoz) . ' '
            .$warn . str_repeat(' ', 2 - mb_strlen($warn)) . ' ';
    $prognoza = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*')
      $sprognoz[$away] = $prognoza;

    $prognozr = str_replace(' ', '', $rprognoz);
    // удары по воротам
    $hith = $hita = 0;
    for ($i = 0; $i < count($realmatch); $i++)
    {
      $plrs[$i] = $plrs[$i] ?? 0;
      $realmatch[$i + 1]['hits'] = $realmatch[$i + 1]['hits'] ?? 0;
      if ($prognozr[$i] != '-')
      {
        if ($prognozr[$i] == $prognozh[$i]) {
          $hith++;
          if ($aprognoz[$home]['warn'][0] != '*')
            $realmatch[$i + 1]['hits']++;

        }
        if ($prognozr[$i] == $prognoza[$i]) {
          $hita++;
          if ($aprognoz[$away]['warn'][0] != '*')
            $realmatch[$i + 1]['hits']++;

        }
        if (($aprognoz[$home]['warn'][0] != '*') && ($prognozh[$i] != '='))
          $plrs[$i]++;

        if (($aprognoz[$away]['warn'][0] != '*') && ($prognoza[$i] != '='))
          $plrs[$i]++;

      }
    }
    // голы, счет и протокол матча
    $goalh = $goala = $gn = 0;
    $mt = 10; // число матчей. участвующих в определении счета
    for ($i=0; $i<count($realmatch); $i++) {
      $usereplace = $ppos ? ($prognozr[$ppos - 1] == '-') : false;
      if ($i < $mt) {
        if ($prognozr[$i] == '-') { // замена несостоявшегося матча
          if (!$replacefix)
            $usereplace = false;

          if ($ppos == $i + 1) {
            if ($prognozr[$replace] == '-') { // указанный гостями заменитель не играл, ищем обычную замену
              $replace = 10;
              while (($prognozr[$replace] == '-') && ($replace < 16))
                $replace++; // поиск первого неиспользованного форо-заменителя

            }
            $rep = $replace;
          }
          else {
            $rep = 10;
            while ((($prognozr[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 16))
              $rep++; // поиск первого неиспользованного заменителя

          }
          $usereplace = false;
          $prognozr[$i] = $prognozr[$rep];
          $prognozh[$i] = $prognozh[$rep];
          $prognoza[$i] = $prognoza[$rep];
          if ($ppos == $i + 1) {
            $ppos = 0;
            if ($prognozr[$i] == $prognozh[$i])
              $prognoza[$i] = '='; // фора!

            $line1 = sprintf('%-'.(16 + $replace).'s', rtrim($line1))."ф";
          }
          $prognozr[$rep] = '-';
        }
/*
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
*/
        $hh = ($prognozr[$i] == $prognozh[$i] || ($ppos == $i + 1) && ($prognozr[$i] == $podstr)) ? 1 : 0;
        $ha = ($prognozr[$i] == $prognoza[$i]) ? 1 : 0;
        if ($hh > $ha) {
          $goalh++;
          $gn = $goalh + $goala;
          $bomb[$match][$gn]['min'] = round(rand(1, 9)) + $i * 9;
          $bomb[$match][$gn]['side'] = 'h';
          $bomb[$match][$gn]['team'] = $home;
          if ($aprognoz[$home]['warn'][0] == '*') 
            $bomb[$match][$gn]['team'] .= '(*)';

          $bomb[$match][$gn]['xx'] = $prognozr[$i].$prognoza[$i];
        }
        elseif ($ha > $hh) {
          $goala++;
          $gn = $goalh + $goala;
          $bomb[$match][$gn]['min'] = round(rand(1, 9)) + $i * 9;
          $bomb[$match][$gn]['side'] = 'a';
          $bomb[$match][$gn]['team'] = $away;
          if ($aprognoz[$away]['warn'][0] == '*') 
            $bomb[$match][$gn]['team'] .= '(*)';

          $bomb[$match][$gn]['xx'] =  ($ppos == $i + 1) ? $prognozr[$i].'*' : $prognozr[$i].$prognozh[$i];
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
    $newcaltour .= mb_sprintf('%-32s', "$home - $away  $goalh:$goala").'  (';
    if ($aprognoz[$home]['warn'][0] == '*')
      $newcaltour .= "*:$hith:";
    else
      $newcaltour .= $coach[$home].":$hith:";

    if ($aprognoz[$away]['warn'][0] == '*')
      $newcaltour .= "*:$hita)\n";
    else
      $newcaltour .= $coach[$away].":$hita)\n";

    if (++$nm >= $z) {
      $gs = 0;
      $st++;
      $z = sizeof($virtmatch);
    }
  }
  // обновление файла календаря
  $newcaltour .= "\n";
  $tourfix = str_replace('NEW', '', $tour);
  $tourn = ltrim(ltrim(substr($tourfix, -2), '0'), 'C');

  if (is_file($season_dir.$calfname))
  {
    $cal = file_get_contents($season_dir.$calfname);
    if ($caltour = GetTourFromCalendar($tourfix, $cal))
      $cal = str_replace($caltour, $newcaltour, $cal);
    else
      $cal .= " Тур $tourn     Код $tourfix\n$newcaltour";

  }
  else
    $cal = " Тур $tourn     Код $tourfix\n$newcaltour";

  file_put_contents($season_dir.$calfname, $cal);
  if ($country_code == 'UEFA')
    get_groups(rtrim($tcode, '/'), $cal);

  // таблица числа угадавших прогнозы для формата stat
  $prognozr = str_replace(' ', '', $rprognoz);
  for ($i=0; $i<count($realmatch); $i++)
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
  if (is_file($season_dir.'bombers'))
  { // parse bombers
    $bombers = file_get_contents($season_dir.'bombers');
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
      unset($ateam[0]);
      foreach($ateam as $line)
        if ($line = trim($line))
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
    $bout .= $hd.str_repeat('-', mb_strlen($hd))."\n$mout\n";
  }
/*
  if ($calfname == 'calc')
    $fname = $online_dir."$country_code/$season/$tcode/bombc/b0$tourn";
  elseif ($calfname == 'cals')
    $fname = $online_dir."$country_code/$season/$tcode/bombs/b0$tourn";
  elseif ($calfname == 'calp')
    $fname = $online_dir."$country_code/$season/$tcode/bombp/b0$tourn";
  elseif (strlen($tourn) == 1)
    $fname = $online_dir."$country_code/$season/bomb/b0$tourn";
  else
    $fname = $online_dir."$country_code/$season/bomb/b$tourn";
*/
  file_put_contents($bfname, $bout);
  // обновление файла карточек
  foreach ($aprognoz as $tn => $atemp)
    $cards[$tn][$tourn] = $atemp['warn'];

  $bout = '';
  foreach ($cards as $tn => $atemp)
  {
    $bout .= $tn . str_repeat(' ', 20 - mb_strlen($tn));
    for ($i=0; $i<=$tourn; $i++)
      if (isset($atemp[$i]) && trim($atemp[$i]))
        $bout .= $atemp[$i].',';
      else
        $bout .= '  ,';

    $bout .= "\n";
  }
  file_put_contents($season_dir.$cardsfname, $bout);
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
  for ($i=1; $i<=count($realmatch); $i++) if (isset($realmatch[$i]['plyr']) && strlen($trainer = $realmatch[$i]['plyr']) > 2) { /////
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

  BOMB($country_code, $calfname);

  // итоги
  if (is_file($itplfname))
  {
    $template = str_replace("\r", '', file_get_contents($itplfname));
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
    for ($nm=1; $nm<=count($realmatch); $nm++)
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
    for ($nm=1; $nm<=count($realmatch); $nm++) {
      if ($nm == 11)
        $playerhits[] = ' ';

      $pl = isset($realmatch[$nm]['plyr']) ? $realmatch[$nm]['plyr'] : '';
      if (mb_strlen($pl) > 2)
        $pl = mb_substr($pl, 0, 1).'.'.mb_substr($pl, 1 + mb_strpos($pl, ' '));

      $playerhits[] = sprintf('%-2s', $realmatch[$nm]['hits']).' '.$pl;
    }
    $fr = strpos($template, '[Programme]');
    $to = strpos($template, "\n", $fr) + 1;
    $line = substr ($template, $fr, $to - $fr);
    $plhtpos = strpos($line, '[PlayersHits]');
    $out = '';
    for ($i=0; $i<=count($realmatch); $i++)
      $out .= mb_sprintf('%-'.$plhtpos.'s', $programme[$i]) . $playerhits[$i]."\n";

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

      for ($nm = $n1; $nm <= $n2; $nm++)
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
    // [EuroResults]
    if ($country_code == 'UEFA')
    {
      reset($wresults);
      $tl = max(14, $maxteam);
      if (substr($tour, -2) < '07')
      { // если номер тура меньше 6, показываем групповой формат:
        $groups = $tour[0] == 'U' ? 8 : 6;
        for ($g = 0; $g < $groups; $g++ ) {
          $tpltbl['EuroResults']['1-32'][] = str_repeat(' ', $tl).'Группа '.($g + 1);
          $tpltbl['EuroResults']['1-32'][] = '';
          $tpltbl['EuroResults']['1-32'][] = 'Прав.прогноз  '.$rprognoz.'    Счёт';
          $tpltbl['EuroResults']['1-32'][] = substr(current($wresults), 1);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = current($wresults);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = current($wresults);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = substr(current($wresults), 1);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = current($wresults);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = current($wresults);
          next($wresults);
          $tpltbl['EuroResults']['1-32'][] = '';
          $gt = group_table($g);
          foreach ($gt as $line)
            $tpltbl['EuroResults']['1-32'][] = $line;

        }
      }
      else {
/*
  для плей-офф:
  Прав.прогноз  $rprognoz   Счёт
  wresults для всех
*/
      }
    }
    $tt = $best = $firework = $gb = array();
    for ($tn = 1; $tn <= $tourn; $tn++) {
      $fr = strpos($cal, " Тур $tn");
      $fr = strpos($cal, "\n", $fr) + 1;
      if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '='))
        $fr = strpos($cal, "\n", $fr) + 1;

      $caltour = ($to = strpos($cal, " Тур", $fr)) ? substr($cal, $fr, $to - $fr) : substr($cal, $fr);
      $virtmatch = $amb = array();
      $atemp = explode("\n", trim($caltour));
      if (strpos($atemp[0],':')) foreach ($atemp as $line) if ($line = trim($line)) {
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
/*
        list($home, $line) = explode(' - ', $line, 2);
        list($away, $line) = explode('  ', $atemp[1], 2);
        list($gh, $line) = explode(':', $atemp[1], 2);
        list($ga, $line) = explode('  ', $atemp[1], 2);
        list($th, $hh, $ta, $ha) = explode(':', trim($line, ' ()'));
*/
// tt: team =>
//  m mh ma  w wh wa  d dh da  l lh la  g gh ga  s sh sa  r rh ra  p ph pa
// bw bwh bwa bg bgh bga bs bsh bsa bl blh bla lgh lga lsh lsa wag was h hh ha
// w wh wa l lh la bls blg
// ser
        if (!isset($tt[$home]))
          $tt[$home] = init_tt();

        if (!isset($tt[$away]))
          $tt[$away] = init_tt();

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
          $amb[$away]['f'] = 1 + ($amb[$away]['f'] ?? 0);
          if ($country_code == 'UEFA')
          {
            $gb[$th][$home]['t'][$tn] = $hh;
            $gb[$th][$home]['h'] = $hh + (isset($gb[$th][$home]['h']) ? $gb[$th][$home]['h'] : 0);
          }
          else
          {
            $gb[$th]['t'][$tn] = $hh;
            $gb[$th]['h'] = $hh + ($gb[$th]['h'] ?? 0);
            $gb[$th]['n'] = $home;
          }
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
          $amb[$home]['f'] = 1 + ($amb[$home]['f'] ?? 0);
          if ($country_code == 'UEFA')
          {
            $gb[$ta][$away]['t'][$tn] = $ha;
            $gb[$ta][$away]['h'] = $ha + (isset($gb[$ta][$away]['h']) ? $gb[$ta][$away]['h'] : 0);
          }
          else
          {
            $gb[$ta]['t'][$tn] = $ha;
            $gb[$ta]['h'] = $ha + ($gb[$ta]['h'] ?? 0);
            $gb[$ta]['n'] = $away;
          }
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

            if (!isset($best['h']) || $bbr > $best['h']['bbr'])
            {
              $best['h']['bbr'] = $bbr;
              $best['h']['rez'] = "$gh-$ga";
              $best['h']['team'] = "$home - $away ($tn тур),";
            }
            else if ($bbr == $best['h']['bbr'])
              $best['h']['team'] .= "$home - $away ($tn тур),";

            if ($ta != '*')
            {
              if (!isset($best['d']) || $bbr > $best['d']['bbr'])
              {
                $best['d']['bbr'] = $bbr;
                $best['d']['rez'] = "$gh-$ga";
                $best['d']['team'] = "$home - $away ($tn тур),";
              }
              else if ($bbr == $best['d']['bbr'])
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

            if (!isset($best['a']) || $bbr > $best['a']['bbr'])
            {
              $best['a']['bbr'] = $bbr;
              $best['a']['rez'] = "$ga-$gh";
              $best['a']['team'] = "$away - $home ($tn тур),";
           }
            elseif ($bbr == $best['a']['bbr'])
              $best['a']['team'] .= "$away - $home ($tn тур),";

            if ($th != '*')
            {
              if (!isset($best['g']) || $bbr > $best['g']['bbr'])
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
    $template = FillTemplate($template, 'EuroResults', $tpltbl);
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
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
            . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
            . sprintf('%2s', $att['g'][$i])
            . ' (в гостях - ' . sprintf('%2s', $att['ga'][$i])
            . ', пропущено - ' . sprintf('%2s', $att['s'][$i]).")\n";

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
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
            . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
            . sprintf('%3s', sprintf('%+d', $att['r'][$i]))
            . ' ('.$att['g'][$i].'-'.$att['s'][$i].")\n";

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
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
            . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
            . sprintf('%2s', $att['bw'][$i])
            . ' ('.$att['bg'][$i].'-'.$att['bs'][$i]
            . ') В гостях: '.sprintf('%2s', $att['bwa'][$i])
            . ' ('.$att['bga'][$i].'-'.$att['bsa'][$i].")\n";

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
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
            . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
            . sprintf('%2s', $att['bw'][$i])
            . ' ('.$att['bg'][$i].'-'.$att['bs'][$i]
            . ') В гостях: '.sprintf('%2s', $att['bwa'][$i])
            . ' ('.$att['bsa'][$i].'-'.$att['bga'][$i].")\n";

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
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
            . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
            . sprintf('%2s', $att['wa'][$i])
            . ' ('.$att['wag'][$i].'-'.$att['was'][$i].")\n";

    $template = str_replace($line, rtrim($out), $template);
  }
  // [BestResults] в будущем возможен параметр n-N
  $out = '';
  $outg = '';
  if (isset($best['d']['bbr']) && (!isset($best['g']['bbr']) || $best['d']['bbr'] > $best['g']['bbr']))
    $out .= sprintf('%-8s', $best['d']['rez']) . rtrim($best['d']['team'], ',')."\n";

  if (isset($best['g']['rez']) && trim($best['g']['rez']))
    $out .= mb_sprintf('%-8s', $best['g']['rez'].'г') . rtrim($best['g']['team'], ',')."\n";

  if (isset($best['h']['bbr']) && ((!isset($best['d']['bbr']) || $best['h']['bbr'] > $best['d']['bbr'])) && ((!isset($best['a']['bbr']) || $best['h']['bbr'] > $best['a']['bbr'])))
    $outg .= sprintf('%-8s', $best['h']['rez'].'(*)') . rtrim($best['h']['team'], ',')."\n";

  if (isset($best['a']['bbr']) && (!isset($best['g']['bbr']) || $best['a']['bbr'] > $best['g']['bbr']))
    $outg .= mb_sprintf('%-8s', $best['a']['rez'].'(*)г') . rtrim($best['a']['team'], ',')."\n";

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
    foreach ($gb as $tr => $gbteams)
      // цикл по тренерам
      if ($country_code == 'UEFA')
      {
        foreach ($gbteams as $nm => $atemp)
        { // цикл по их командам
          //$nm = $gb[$tr]['n'];
          $att['t'][] = $tr;
          $att['n'][] = $nm;
          $att['h'][] = $gb[$tr][$nm]['h'];
          $att['z'][] = sizeof($gb[$tr][$nm]['t']);
          $att['pl'][] = $tt[$nm]['pl'];
          $att['p'][] = $tt[$nm]['p'];
          $att['r'][] = $tt[$nm]['r'];
          $att['g'][] = $tt[$nm]['g'];
        }
      }
      else
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
      $nm = $att['n'][$i];
      if ($country_code == 'UEFA')
        $gb[$tr][$nm]['p'] = $i + 1;
      else
        $gb[$tr]['p'] = $i + 1;

      $out .= sprintf('%2s', $i+1).'.  '
            . mb_sprintf('%-'.($maxcoach + 1).'s', $tr)
            . mb_sprintf('%-'.($maxteam + 1).'s', $nm)
            . sprintf('%3s', $att['h'][$i]);

      $missed = 0;
      for ($j=1; $j<=$tourn; $j++)
      {
        if (isset($gb[$tr]['t'][$j]) || isset($gb[$tr][$nm]['t'][$j]))
        {
          if ($j > $nt)
            if ($country_code == 'UEFA')
              $out .= sprintf('%3s', $gb[$tr][$nm]['t'][$j]);
            else
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i]+1).'-'.$att['t'][$i].')';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].', '.$att['p'][$i].' оч.)';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].')';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].', '.$att['p'][$i].' оч.)';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].')';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].')';
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
    $min = max(3, $att['l'][2]);
    $i = 0;
    while ($att['l'][$i] >= $min)
    {
      $out .= mb_sprintf($nfmt, $att['n'][$i]) . sprintf('%2s', $att['l'][$i])
          .' (туры '.($att['t'][$i] - $att['l'][$i] + 1).'-'.$att['t'][$i].')';
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
  if ($fr = (strpos($template, $line))) {
    $att = $atn = array();
    foreach ($tt as $tn => $atemp) {
      $pts = 0;
      $ptn = '';
      for ($i=1; $i<=$tourn; $i++)
        if (in_array($atemp['en'][$i], $leaders)) if ($atemp['ser'][$i]) {
          $pts += $atemp['ser'][$i];
          $ptn .= $atemp['en'][$i].':'.$atemp['ser'][$i].',';
        }
      if ($pts) {
        $att[$tn] = $pts;
        $atn[$tn] = trim($ptn, ',');
      }
    }
    arsort($att);
    $out = '';
    $i = 0;
    $max = 5;
    $prev = 99;
    foreach($att as $tn => $pts) {
      if (($i == $max) && ($pts >= $prev))
        $max++;

      if ($i++ < $max) {
        $nm = isset($lnames[$tn]) ? $lnames[$tn] : $tn;
        $out .= ($i ? sprintf('%2s. ', $i) : '_1_.') . ' '
              . mb_sprintf('%-'.$nmax.'s', $nm)
              . sprintf('%3s', $pts).' ('. $atn[$tn] .")\n";
        $prev = $pts;
      }
    }
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

    $att['t'][] = $amb[$tn]['t'] ?? 0;
    $att['h'][] = $amb[$tn]['h'] ?? 0;
    $att['x'][] = $amb[$tn]['x'] ?? 0;
    $att['cr'][] = $amb[$tn]['r'] ?? 0;
    $att['cg'][] = $amb[$tn]['g'] ?? 0;
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
    $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.').' '
          . mb_sprintf('%-'.($maxcoach + 1).'s', $att['t'][$i])
          . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
          . sprintf('%2s', $att['h'][$i]).'  ('
          . $att['cg'][$i].':'.($att['cg'][$i] - $att['cr'][$i])."$f)\n";
  }
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
//sleep(1);
  // [SuperBoots] в будущем возможен параметр n-N
  // b d h/m pl p r g
  $line = "\n[SuperBoots]";
  if ($fr = (strpos($template, $line)))
  {
    $atb = [];
    for ($i=1; $i<=$tourn; $i++) if ($tn = trim($superb[$i]['t']))
    {
      if (!isset($atb[$tn]))
        $atb[$tn] = array_fill_keys(['b', 'd', 'h', 'm'], 0);

      $atb[$tn]['n'] = $superb[$i]['n'];
      $atb[$tn]['b']++;
      $atb[$tn]['d'] += $superb[$i]['d'];
      $atb[$tn]['h'] += $superb[$i]['h'];
      $atb[$tn]['m'] += $superb[$i]['m'];
    }
    $att = [];
    foreach ($atb as $tn => $atemp)
    {
      $att['t'][] = $tn;
      $att['n'][] = isset($lnames[$atb[$tn]['n']]) ? $lnames[$atb[$tn]['n']] : $atb[$tn]['n'];
      $att['b'][] = $atb[$tn]['b'];
      $att['d'][] = $atb[$tn]['d'];
      $att['h'][] = $atb[$tn]['h'];
      $att['m'][] = $atb[$tn]['m'];
      $att['pl'][] = $tt[$nm]['pl'];
      $att['p'][] = $tt[$nm]['p'];
      $att['r'][] = $tt[$nm]['r'];
      $att['g'][] = $tt[$nm]['g'];
    }
    for($i = 0; $i < sizeof($att['t']); $i++)
      $att['o'][$i] = $att['h'][$i] / $att['m'][$i];

    array_multisort($att['b'],SORT_DESC, $att['d'],SORT_DESC, $att['o'],SORT_DESC, $att['pl'], $att['p'],SORT_DESC, $att['r'],SORT_DESC, $att['g'],SORT_DESC, $att['t'], $att['n'], $att['h'], $att['m']);
    $out = str_repeat(' ', $maxcoach + $nmax - 12).'раз отрыв рез  из
';
    for($i = 0; $i < sizeof($att['t']); $i++)
    {
      $tr = $att['t'][$i];
      $out .= ($i ? sprintf('%2s. ', $i + 1) : '_1_.') . mb_sprintf(' %-'.($maxcoach + 1).'s', $tr)
          . mb_sprintf('%-'.$nmax.'s', $att['n'][$i])
          . sprintf('%2s', $att['b'][$i]) . sprintf('%5s', $att['d'][$i])
          . sprintf('%5s', $att['h'][$i]) . sprintf('%5s', $att['m'][$i])."\n";
    }
    $template = str_replace($line, rtrim($out), $template);
  }
  // [DarkHorse]
  $line = "\n[DarkHorse]";
  if (strpos($template, $line))
    $template = str_replace($line, horse_raven($hfname, $tourn, $gb, $maxcoach, $maxteam), $template);

  // [WhiteRaven]
  $line = "\n[WhiteRaven]";
  if (strpos($template, $line))
    $template = str_replace($line, horse_raven($vfname, $tourn, $gb, $maxcoach, $maxteam), $template);

    // [Cards]
    $maxln = max(14, $maxteam);
    $out = sprintf('%'.($maxln + 3).'s', 'Тур  ');
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
        {
          $warn = str_replace('Ж', '░', $atour[$i]);
          $warn = str_replace('К', '▓', $warn);
          $out .= $warn;
        }
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
  $content .= "\nОБЗОР МАТЧЕЙ $tourn-ГО ТУРА\n=======================\n\n";
  $bs = file_get_contents($bfname);
  if (mb_detect_encoding($bs, 'UTF-8', true) === FALSE)
    $bs = iconv('CP1251', 'UTF-8//IGNORE', $bs);

  $content .= $bs;
  $bs = file_get_contents($bsfname);
  if (mb_detect_encoding($bs, 'UTF-8', true) === FALSE)
    $bs = iconv('CP1251', 'UTF-8//IGNORE', $bs);

  $content .= "БОМБАРДИРЫ ПОСЛЕ $tourn-ГО ТУРА\n\n===========================\n\n";
  $content .= substr($bs, strpos($bs, '-') + 18);
  file_put_contents($rfname, $content);

  return $template;
}

$online_dir = '/home/fp/data/online/';
$tour_code_prefix = $argv[1]; //'CHAML'; //'RUS' 
$s = $argv[2]; //'2019-20'; //
$t = $argv[3]; //'01'; //
chdir('/home/fp/fprognoz.org/online');
$atourn = ['CHAML', 'GOLDL', 'CUPSL', 'UEFAL'];
$acal = $ateams = [];
if (in_array($tour_code_prefix, $atourn))
{
  $cca = 'UEFA';
//  get_groups($tour_code_prefix, $s);
}
else
  $cca = $tour_code_prefix;

$season_dir = $online_dir.$cca.'/'.$s.'/';
$tcode = ($cca == 'UEFA') ? $tour_code_prefix.'/' : '';
$nameln = 14; // фиксированная максимальная длина имени команды
$body = build_itogi($cca, $s, $tour_code_prefix.strtoupper($t));
?>
