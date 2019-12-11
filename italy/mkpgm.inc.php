<?php
mb_regex_encoding('UTF-8');
include 'online/realteam.inc.php';
include 'online/cc.inc.php';
include 'online/translate.inc.php';
$granted = $admin;
$granted[] = $president;
$arr = explode(',', $vice);
foreach ($arr as $name)
    $granted[] = trim($name);

function GetTourFromCalendar($tour, $cal) {
  $cclen = $tour[4] == 'L' ? 5 : 3;
  $tourn = ltrim(ltrim(substr($tour, $cclen), '0'), 'C');
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

function FillTemplate($template, $blockname, $tpltbl) {
  $fr = 0;
  if ($fr = (strpos($template, "[$blockname]", $fr + 1))) {
    $out = '';
    $to = strpos($template, "\n", $fr) + 1;
    $line = substr ($template, $fr, $to - $fr);
    $lblk = $tpltbl[$blockname];
    $tmp = substr ($template, 0, $fr);
    $lblkpos = $fr - strrpos($tmp, "\n") - 1;
    if ($rblkpos = strpos($line, '[', 10)) {
      $rblknm = substr($line, $rblkpos + 1, strpos($line, ']', $rblkpos) - $rblkpos - 1);
      $rblk = $tpltbl[$rblknm];
      $m = max(sizeof($lblk), sizeof($rblk));
      for ($i=0; $i<$m; $i++) {
        if ($i) $out .= str_repeat(' ', $lblkpos);
        $out .= trim($lblk[$i]) . str_repeat(' ', $rblkpos - mb_strlen(trim($lblk[$i]))) . trim($rblk[$i])."\n";
      }
    }
    else
      for ($i=0; $i<sizeof($lblk); $i++) {
        if ($i) $out .= str_repeat(' ', $lblkpos);
        $out .= trim($lblk[$i])."\n";
      }

    $template = str_replace($line, $out, $template);
  }
  return $template;
}

function Schedule($timestamp, $country_code, $tour_code, $action, $pfname) {
  global $online_dir;
  $dir = $online_dir . 'schedule/'.date('Y/m/d', $timestamp);
  if (!is_dir($dir)) mkdir($dir, 0755, true);
  file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.$tour_code.'.'.$action, $pfname);
  if ($country_code == 'UEFA' && $action != 'resend') {
    file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.str_replace('UEFA', 'CHAM', $tour_code).'.'.$action, $pfname);
    file_put_contents($dir.'/'.($timestamp-1).'.'.$country_code.'.'.str_replace('UEFA', 'GOLD', $tour_code).'.'.$action, $pfname);
    file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.str_replace('UEFA', 'CUPS', $tour_code).'.'.$action, $pfname);
  }
}

function mkPrognozDir($timestamp, $ccode, $season, $TourCode) {
  global $online_dir;
  if (!is_dir($online_dir . "$ccode/$season/prognoz/$TourCode"))
    mkdir ($online_dir . "$ccode/$season/prognoz/$TourCode", 0755, true);

  file_put_contents($online_dir . "$ccode/$season/prognoz/$TourCode/term", $timestamp);
  touch($online_dir . "$ccode/$season/prognoz/$TourCode/mail");
  touch($online_dir . "$ccode/$season/prognoz/$TourCode/adds");
}

function IntervalsTable() {
  global $a;
  global $ccc;
  global $ccs;
  global $d;
  global $matches;
  global $online_dir;
  global $suffix;
  global $translate;
  $out = '
  <thead>
    <tr>
      <th colspan="3">игровые дни</th>
      <th>всего</th>';
  foreach ($ccs as $cc => $country)
    if ($cc != 'SUI')
      $out .= '
      <th>'.$cc.'</th>';

  $out .= '
    </tr>
  </thead>
  <tbody>';
  $time = strtotime('tue +-2 week');
  for ($w = -2; $w <= 6; $w++)
  {
    $time += 604800 + 3600;
    $week = date('W', $time);
    $year = date('Y', $time);

    if ($week == '01')
      $year = 2020;

    $day1 = date('m-d', $time);

if ($day1 == '12-31')
  $day1 = '01-01'; // fix 2020

    $day2 = date('m-d', $time + 172800);
    $day3 = date('m-d', $time + 259200);
    $day4 = date('m-d', $time + 518400);
    $day5 = date('m-d', $time + 604800);
    $s1 = $s2 = '';
    $t1 = $t2 = 0;
    foreach ($ccs as $cc => $country) if ($cc != 'SUI1')
    {
      foreach ($suffix as $tournament)
        if (is_file($online_dir . "fixtures/$year/$week/$cc$tournament"))
        {
          $ac = file($online_dir . "fixtures/$year/$week/$cc$tournament", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
          foreach ($ac as $line)
          {
            list($home, $away, $date, $kick, $tnmt) = explode(',', trim($line));
            if ($cc == 'RUS' || $cc == 'UKR' || $cc == 'BLR')
            { // перевод кирилличных названий
              $home = $translate[$home];
              $away = $translate[$away];
            }
            else if ($cc == 'UCL' || $cc == 'UEL')
              $tnmt = '';
            else if ($cc == 'INT')
            {
              if ($home == 'FYR Macedonia')
                $home = 'North Macedonia';

              if ($away == 'FYR Macedonia')
                $away = 'North Macedonia';
/*
              if ($tnmt == 'Friendly')
                $tnmt = 'FR';
              else {
                $to = '';
                if (strpos($tnmt, ' W')) $to .= 'W';
                if (strpos($tnmt, 'ontinental')) $to .= 'C';
                if (strpos($tnmt, ' C')) $to .= 'C';
                if (strpos($tnmt, ' F')) $to .= 'F';
                if (strpos($tnmt, ' Q')) $to .= 'Q';
                $tnmt = $to;
              }
*/
            }
            if ($date > date('m-d') || $date[0] == '0') // fix 2020
            {
              if ($date < $day1 && $date > '01-00')
              {
                if (isset($day3p))
                  $matches[$day3p][$cc][$home.' - '.$away] = [trim($home),trim($away),$date,$kick,$tnmt];

              }
              else if ($date < $day3)
                $matches[$day1][$cc][$home.' - '.$away] = [trim($home),trim($away),$date,$kick,$tnmt];
              else
                $matches[$day3][$cc][$home.' - '.$away] = [trim($home),trim($away),$date,$kick,$tnmt];

            }
          }
        }

      $m1 = isset($matches[$day1][$cc]) ? sizeof($matches[$day1][$cc]) : 0;
      $t1 += $m1;
      $s1 .= '<td'.($m1 && ($cc == $ccc) ? ' bgcolor=pink' : '').'>'.$m1.'</td>';
      $m3 = isset($matches[$day3][$cc]) ? sizeof($matches[$day3][$cc]) : 0;
      $t2 += $m3;
      $s2 .= '<td'.($m3 && ($cc == $ccc) ? ' bgcolor=pink' : '').'>'.$m3.'</td>';
    }
    if ($t1 > 0)
    {
      if (!$d)
        $d = $day1;

      $out .= "
<tr>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day1&add=$uadd#tour'>вт $day1</a> &nbsp;-</td>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day1&add=$uadd#tour'>чт $day2</a></td>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day1+$day3&add=$uadd#tour'> + </a></td>
<td>$t1</td>$s1</tr>";
    }
    if ($t2 > 0)
    {
      if (!$d)
        $d = $day3;

      $out .= "
<tr>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day3&add=$uadd#tour'>пт $day3</a> &nbsp;-</td>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day3&add=$uadd#tour'>пн $day4</a></td>
<td><a href='?a=$a&m=mkpgm&cc=$ccc&d=$day3+$day5&add=$uadd#tour'>+</a></td>
<td>$t2</td>$s2</tr>";
    }
    $day3p = $day3;

  }
  $out .= '
  </tdata>';
  return $out;
}

function BuildMatchesRank($d) {
  global $ccc;
  global $ccn;
  global $ccrank;
  global $matches;
  global $mrated;
  global $rat;
  global $realteam;
  global $selected;
  global $teams;
  $ret = '';
  foreach ($matches[$d] as $cc => $liga)
    foreach ($liga as $match => $mdata) {
      $rating = 0;
      $tournament = $mdata[4];
      $rating += ($tournament == 'D1') ? $rat[$cc] / 2 : $rat[$cc];
      if ($ccc == 'BLR' && $cc == 'RUS')
        $rating += $rat[$cc] / 2;

      if ($cc == 'INT')
      {
          if ($tournament == 'CLU')
              $rating = 100;
          else
          {
              $rating = ($rating + $teams[$cc][$mdata[0]] + $teams[$cc][$mdata[1]] + 200) / 2;
              if ($ccn[$ccc] != $mdata[0] && $ccn[$ccc] != $mdata[1])
                  $rating -= 100;

              if (!isset($teams[$cc][$mdata[0]]))
              {
                  if ($_SESSION['Coach_name'] == 'Александр Сесса')
                      $ret .= ' no rank: '.$mdata[0].'<br>';

                  $ratingh = 20;
              }
              else
                  $ratingh = $teams[$cc][$mdata[0]];

              if (!isset($teams[$cc][$mdata[1]]))
              {
                  if ($_SESSION['Coach_name'] == 'Александр Сесса')
                      $ret .= ' no rank: '.$mdata[1].'<br>';

                  $ratinga = 20;
              }
              else
                  $ratinga = $teams[$cc][$mdata[1]];

              $rating -= abs(10 + $ratingh - $ratinga);
          }
      }
      else {
        $cc1 = $cc;
        if ($tournament == 'D1' || $tournament == '3R' || $tournament == '2R') {
          if (isset($teams[$cc][$realteam[$mdata[0]]]))
            $rating += $teams[$cc][$realteam[$mdata[0]]];
          else
            $teams[$cc][$realteam[$mdata[0]]] = 0;

          if (isset($teams[$cc][$realteam[$mdata[1]]]))
            $rating += $teams[$cc][$realteam[$mdata[1]]];
          else
            $teams[$cc][$realteam[$mdata[1]]] = 0;

        }
        else {
          if (!isset($teams[$cc1][$realteam[$mdata[0]]])) {
            if ($_SESSION['Coach_name'] == 'Александр Сесса')
              $ret .= ' no rank: '.$cc.' '.$mdata[0].'<br>';

            $teams[$cc1][$realteam[$mdata[0]]] = 0;
          }
          else
            $rating += $teams[$cc1][$realteam[$mdata[0]]];

          if (!isset($teams[$cc1][$realteam[$mdata[1]]])) {
            if ($_SESSION['Coach_name'] == 'Александр Сесса')
              $ret .= ' no rank: '.$cc.' '.$mdata[1].'<br>';

            $teams[$cc1][$realteam[$mdata[1]]] = 0;
          }
          else
            $rating += $teams[$cc1][$realteam[$mdata[1]]];

        }
        $rating -= abs($ccrank[$cc][4] + $teams[$cc1][$realteam[$mdata[0]]] - $teams[$cc1][$realteam[$mdata[1]]]);
      }
      $rating = max(0, $rating);

      $matches[$d][$cc][$match][5] = $rating;
      $mrated[] = $rating;
      $selected[$cc][$match] = $matches[$d][$cc][$match];
    }

  return $ret;
}

function SortableMatchesSublist($min, $max, $ccc=NULL) {
  global $selected;
  $l1 = $l2 = '';
  foreach ($selected as $cc => $liga)
    foreach ($liga as $match => $mdata)
      if ($mdata[5] < $max && $mdata[5] >= $min) {
        $li = '
<li class="sortable_module">
  '.$match.'
  <div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div>
  <div class="mdate">'.$mdata[2].'</div>
  <div class="mrate">&nbsp;'.$mdata[5].'</div>
  <input type="hidden" name="match[]" value="'.$match.'|'.$cc.'|'.$mdata[2].'">
</li>';
        $cc == $ccc ? $l1 .= $li : $l2 .= $li;
      }

  return $l1 . $l2;
}

function SortableMatchesList($ddd, $add) {
  global $matches;
  global $mrated;
  global $selected;
  $list = '';
  if (strlen($ddd) > 5 && $ddd[5] == ' ') {
    $dde = substr($ddd, 6);
    $ddd = substr($ddd, 0, 5);
  }
  else
    $dde = '';

  $list .= BuildMatchesRank($ddd);
  if ($dde)
    $list .= BuildMatchesRank($dde);

  if ($add) {
    $matchesadd = explode("\n", $add);
    foreach($matchesadd as $matchadd)
      if (trim($matchadd)) {
        $mdata = explode(',', $matchadd);
        $match = trim($mdata[0]);
        $mpair = explode(' - ', $match);
        $cc = trim($mdata[2]);
        $rating = 400;
        $matches[$ddd][$cc][$match] = [trim($mpair[0]), trim($mpair[1]), trim($mdata[1]), trim($mdata[2]), trim($mdata[3]), $rating];
        $mrated[] = $rating;
        $selected[$cc][$match] = $matches[$ddd][$cc][$match];
      }

  }
  rsort($mrated);
  $r10 = $mrated[9];
  $r15 = $mrated[14];
  $r20 = (sizeof($mrated) > 20) ? max(10, $mrated[99]) : 0; // $r20 = max(30, $mrated[39])
  $list .= SortableMatchesSublist($r10, 999, $ccc);
  $list .= '<li class="sortable_module" id="hline-1"><hr></li>';
  $list .= SortableMatchesSublist($r15, $r10, $ccc);
  $list .= '<li class="sortable_module" id="hline-2"><hr></li>';
  $list .= SortableMatchesSublist($r20, $r15);
  return $list;
}

$ccc = isset($_GET['cc']) ? $_GET['cc'] : $cca;
/////if (!$ccc || !isset($_SESSION['Coach_name']))
/////  die('access denied');

////////include('../' . strtolower($ccn[$ccc]) . '/settings.inc.php');
if (!in_array($_SESSION['Coach_name'], $granted))
  die('access denied');

$cal = $gen = '';
$notice = 'Выберите интервал из первой колонки. Плюс объединяет два интервала';
$rat = isset($_POST['rat']) ? $_POST['rat'] : [];
foreach ($ccrank as $cct => $ccarr)
  if (!isset($rat[$cct]))
    $rat[$cct] = 0;

if (!$rat[$ccc])
  $rat[$ccc] = ($ccc == 'SCO') ? 100 : 300; // базовый рейтинг своих матчей по умолчанию, но у шотландцев он понижен

$add = isset($_POST['add']) ? $_POST['add'] : (isset($_GET['add']) ? $_GET['add'] : '');
$d = isset($_GET['d']) ? $_GET['d'] : (isset($_POST['d']) ? $_POST['d'] : '');
$uadd = str_replace("\n", "%0A", $add);
$countries = '';
foreach ($ccn as $code => $name)
  if ($code != 'UEFA' && $code != 'SUI')
    $countries .= '
<a href="?a='.$a.'&m=mkpgm&cc='.$code.'&d='.$d.'&add='.$uadd.'#tour"><img src="/images/63x42/'.strtolower($name).'.png" alt="'.$name.'" class="flag-15"></a><input type="text" name="rat['.$code.']" value="'.$rat[$code].'" class="i-tiny">';

if (isset($_POST['tour'])) {
  $TourCode = trim($_POST['tour']);
  $ccode = mb_substr($TourCode, 0, 3);
  if ($ccode == 'UEF')
    $ccode = 'UEFA';

  $season_dir = $online_dir . $ccode . '/' . $cur_year . '/';
  $tourn = ltrim(substr($TourCode, strlen($ccode)), '0');
  $sfx = ($tourn[0] == 'C') ? 'c' : '';
  $ptplfname = $season_dir.'p'.$sfx.'.tpl';
  if (is_file($season_dir . 'cal' . $sfx))
    $cal = trim(GetTourFromCalendar(strtr($TourCode, ['NEW' => '']), file_get_contents($season_dir .'cal' . $sfx)));
  if (is_file($season_dir . 'gen' . $sfx))
    $gen = file_get_contents($season_dir . 'gen' . $sfx);

  $notice = ' &raquo; добавлена программка ' . $TourCode;
  $Srok = $_POST['srok'];
  $tpltbl = array();
  if ($cal && !$sfx) {
    $cfg = file($season_dir . 'fp.cfg');
    $decoded = json_decode($cfg[4], true);
    foreach ($decoded as $tournament)
      if ($tournament['type'] == 'chm' || $tournament['type'] == 'com')
        break;

    $groups = $tournament['format'][0]['groups'];
    if ($groups > 1) {
      $atemp = explode("\n", $cal);
      $tours = isset($tournament["format"][0]["tourn"]) ? $tournament["format"][0]["tourn"] : $tournament["format"][0]["tours"][1];
      $m = ($tours + 2) / 4;
      $virtmatch = array();
      $n = 0;
      $maxln = 0;
      foreach ($atemp as $line) if ($line = trim($line)) {
        if ($n < $m) {
          $virtmatch[0][] = $line;
          $maxln = max($maxln, mb_strlen($line));
        }
        else if ($n < $m * 2) {
          $virtmatch[1][] = $line;
          if ($groups > 2) $maxln = max($maxln, mb_strlen($line));
        }
        else $virtmatch[2][] = $line;
        $n++;
      }
      $maxln = max($maxln, 24);
      $cal = '';
      for ($i=0; $i<$m; $i++) {
        $cal .= $virtmatch[0][$i] . str_repeat(' ', $maxln - mb_strlen($virtmatch[0][$i])) . '    ' . $virtmatch[1][$i];
        if ($groups > 2)
          $cal .= str_repeat(' ', $maxln - mb_strlen($virtmatch[1][$i])) . '    ' . $virtmatch[2][$i];

        $cal .= "\n";
      }
    }
  }
  $tpltbl['Calendar'] = $cal ? explode("\n", $cal) : explode("\n", $_POST['cal']);

  if ($gen) {
    if (($fr = mb_strpos($gen, "ур $tourn")) || ($fr = mb_strpos($gen, $TourCode))) {
      $fr = mb_strpos($gen, "\n", $fr) + 1;
      if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '='))
        $fr = mb_strpos($gen, "\n", $fr) + 1;

      if (($to = mb_strpos($gen, "Тур", $fr)) || ($to = mb_strpos($gen, $ccode, $fr)))
        $gen = trim(mb_substr($gen, $fr, $to - $fr));
      else
        $gen = trim(mb_substr($gen, $fr));

    }
    else $gen = '';
  }
  $tpltbl['Generators'] = $gen ? explode("\n", $gen) :  explode("\n", $_POST['gen']);
  $m = 1;
  $prog = array();
  $date1 = '13-00';
  $date2 = '00-00';
  foreach ($_POST['match'] as $mline) {
      list($prog[$m]['match'], $prog[$m]['cc'], $prog[$m]['date']) = explode('|', $mline);
      $date1 = min($date1, $prog[$m]['date']);
      $date2 = max($date2, $prog[$m]['date']);
      $prog[$m]['date'] = mb_substr($prog[$m]['date'], -2).'.'.mb_substr($prog[$m]['date'], 0, 2);
      if (++$m > 15) break;
  }
  $date1 = mb_substr($date1, -2).'.'.mb_substr($date1, 0, 2);
  $date2 = mb_substr($date2, -2).'.'.mb_substr($date2, 0, 2);
  $Dates = ($date1 == $date2) ? '      ' . $date1 : $date1 . '-' . $date2;

  if (is_file($ptplfname)) {
    $template = str_replace('', '', file_get_contents($ptplfname));
    // определение шаблона программки
    $fr = mb_strrpos($template, '[TourCode]') - 40;
    $fr = mb_strrpos($template, ' N') - 8;
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line11 = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $lineln = mb_strlen($line);
    $i = 0;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' '))
      $i++;

    $c1pos = $i;
    $c1val = mb_substr($line, $i++, 1);
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' '))
      $i++;

    $i++;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' '))
      $i++;

    $c2pos = $i;
    $c2val = mb_substr($line, $i++, 1);
    $c3pos = mb_strpos($line, $c2val, $i);
    $c3val = $c2val;
    $c2matchmaxln = $c3pos - $c2pos - 7; // макс. длина строки матча
    $i = $c3pos + 1;
    $c4pos = mb_strpos($line, $c2val, $i + 1);
    $c4val = $c2val;
    $template = strtr($template, [ '[TourNum]' => sprintf('%02s', $tourn), '[TourCode]' => sprintf('%-10s', $TourCode), "    \n" => "\n", '[__Dates__]' => $Dates]);
    // [Programme]
    $programme = array();
    $progsched = '';
    foreach ($prog as $m => $mt) {
      if ($m == 11) $programme[] = $line11;
      $line = str_repeat(' ', $c1pos) . $c1val . sprintf('%2s', $m) . '.';
      $line = sprintf('%-' . $c2pos . 's', $line) . $c2val . ' ';
      $line .= sprintf('%-' . ($c2matchmaxln + strlen($mt['match']) - mb_strlen($mt['match'])) . 's', $mt['match']);
      $t = ' '.sprintf('%-4s', $mt['cc']);
      $line .= $t . $c3val . $mt['date'] . $c4val;
      $programme[] = $line;
      $ta = explode(' - ', $mt['match']);
      $progsched .= $realteam[$ta[0]] . ',' . $realteam[$ta[1]] . ",\n";
    }
    // [Srok]
    if (mb_strlen($Srok) < 6) {
      $Srok = sprintf('%6s', $Srok);
      $template = str_replace('[Srok]', sprintf('%6s', $Srok), $template);
    }
    else {
      $l = mb_strlen($Srok);
      $template = str_replace(sprintf('%' . $l . 's', '[Srok]'), $Srok, $template);
    }
    $template = FillTemplate($template, 'Calendar', $tpltbl);
    $template = FillTemplate($template, 'Generators', $tpltbl);
    $out = '';
    foreach ($programme as $line)
      $out .= $line . "\n";

    $program = str_replace("[Programme]\n", $out, $template);
    if (isset($_POST['preview']))
      echo "<pre>$program</pre>";
    else {
      $cclen = ($TourCode[4] == 'L') ? 5 : 3;
      $pfname = $season_dir . 'publish/p' . mb_strtolower(mb_substr(str_replace('NEW', '', $TourCode), $cclen));
      if (is_file($pfname))
        rename($pfname, $pfname . '.' . time());

      file_put_contents($pfname, $program);
      file_put_contents($season_dir . 'programs/' . $TourCode, $program);
      // make records for scheduler
      $month = trim(mb_substr($Srok, mb_strpos($Srok, '.') + 1, 2));
      $year = date('Y');
      if (date('m') > $month)
        $year++;

      if (mb_strpos($Srok, ' '))
        str_replace(' ', ".$year ", $Srok);
      else
        $Srok .= ".$year 23:59:59";

      $timestamp = strtotime($Srok);
      Schedule($timestamp - 345600, $ccode, $TourCode, 'resend', $pfname);
      Schedule($timestamp + 36000, $ccode, $TourCode, 'remind', $pfname);
      Schedule($timestamp + 43200, $ccode, $TourCode, 'monitor', $progsched);
      mkPrognozDir($timestamp, $ccode, $cur_year, $TourCode);
      if ($ccode == 'UEFA')
        foreach (['CHAM', 'GOLD', 'CUPS'] as $l)
          mkPrognozDir($timestamp, $ccode, $cur_year, str_replace('UEFA', $l, $TourCode));

    }
  }
  else
    $notice = 'ошибка: нет шаблона программки!';

}
  $matches = $mrated = $selected = $teams = [];
$file = file($online_dir . 'ranking/rank', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($file as $line) {
  list($team, $code, $score) = explode(',', $line);
  $teams[$code][$team] = $teams['UCL'][$team] = $teams['UEL'][$team] = round($score,1);
}

?>
<link rel='stylesheet' href='css/m.css?ver=5.3' type='text/css' />
<?=$notice?>
<form id="progedit" method="post"><!-- onsubmit="serializeAll();"-->
<input type="hidden" name="d" value="<?=$d?>">
<table class="interval-table table-condensed table-striped">
<?=IntervalsTable()?>
</table>
<div>
  <p>
    <a name="tour"><input type="text" id="tour" class="i-small" name="tour" value="" placeholder=" код тура">
    контрольный срок: <input type="text" id="srok" class="i-small" name="srok" value="<?=isset($d) ? date('d.m', mktime(0, 0, 0, substr($d,0,2), substr($d,3), date('Y')) - 86400) : ''?>">
    <a href="online/draw.php?cc=<?=$cca?>" target="_BLANK"> &gt;&gt; Скрипт жеребьевки кубкового тура</a>
  </p>
  <textarea id="cal" class="ta-mkpgm" name="cal" placeholder="календарь (для 2х лиг - в 2 колонки, для 3х - в 3)
Не изменяйте это поле ввода, если на сервере уже есть календарь"></textarea><br>
  <textarea id="gen" class="ta-mkpgm" name="gen" placeholder="генераторы (для 2х лиг - в 2 колонки, для 3х - в 3)
Не изменяйте это поле ввода, если на сервере уже есть генераторы"></textarea><br>
  <input type="submit" name="saveprog" value="записать программку">
  <input type="submit" name="preview" value="предварительный просмотр программки"><br>
  <br>
  <textarea id="add" class="ta-mkpgm" <?php if(!$add) echo "onfocus=\"this.value=''; this.onfocus=null;\" ";?>name="add" placeholder="добавление матчей, отсутствующие в выборе:
Хозяева - Гости, месяц-день, код страны, код турнира
(указывать коды не обязательно)
Пример: Паровоз - Дирижабль, 08-29, RUS, CUP"><?=$add ? $add : ''?></textarea><br>
  <input type="submit" name="addmatch" value="добавить матчи"><br>
</div>
<div>
  <p>
    <br>
    <?=$countries?>
    <input type="submit" id="changek" name="changek" value="изменить">
    <br>
    Перетащите вверх 10+5 матчей. Коэффициенты стран помогут выбрать нужное.
  </p>
  <ul id="sortable">
  <?=SortableMatchesList($d, $add)?>
  </ul>
</div>
</form>
<script>$("#sortable").sortable()</script>
