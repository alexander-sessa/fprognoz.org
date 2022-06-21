#!/usr/bin/php
<?php
$time_start = microtime(true);
date_default_timezone_set('Europe/Berlin');
require_once('xscoregroups.inc.php');
require_once('realteam.inc.php');

function lock($lock, $timeout) {
    $timer = 0;
    while (is_file($lock) && $timer++ < $timeout)
        time_nanosleep(0, 1000);

    touch($lock);
    return ($timer < $timeout);
}

$log = '';
$online_dir = '/home/fp/data/online/';
$realteam1 = [];
foreach ($realteam as $tn1 => $tn2)
    $realteam1[strtoupper($tn1)] = $tn2;

$teamerr = '';
$time_start1 = microtime(true);
$ret = lock($online_dir . 'log/results.lock', 20000);
if ($ret === false)
   $log .= '!!! LOCK !!! '.(microtime(true) - $time_start1).' '; // ignore lock

$ctx = stream_context_create(['http' => [
    'method'  => 'GET',
    'timeout' => 25, // таймаут получения результатов, сек
    'header'  => "Accept-language: en\r\nCookie: regionName=Europe/Amsterdam;countryLocation=NL\r\n"
]]);
//for ($day=8; $day<=8; $day++)
for ($day=-1; $day<=14; $day++)
{
    $offset = 3600 + $day * 86400;
    $year = date('Y', time() + $offset);
    $d = date('d', time() + $offset);
    $m = date('m', time() + $offset);
    $date = "$m-$d";
    $week = date('W', strtotime("$year-$date") - 86400);
    $fname = $online_dir . 'results/' . (($week == '53' && $m == '01') ? ($year - 1).'.'.$week : $year.'.'.$week);
    if (is_file($fname))
    {
        $archive = file($fname);
        $seq = $archive[0];
        unset($archive[0]); // remove old seq
    }
    else
        $archive = [];

    $base = [];
    foreach ($archive as $line)
    {
        $data = explode(',', trim($line));
        $base[$data[0].' - '.$data[1].'/'.$data[6]] = $data;
    }
    $url = "http://www.xscores.com/soccer/livescores/$d-$m";
    $content = file_get_contents($url, 0, $ctx);
    $out = strpos($content, 'seq = ') ? substr($content, 6 + strpos($content, 'seq = '), 8) . "\n" : $seq; // seq
    $content = substr($content, strpos($content, '<div class="country_header_txt">GAMES</div>'));
    $content = substr($content, 0, strpos($content, "<div class='ad-line-hide gameList_ad_bottom'>"));
    $content = strtr($content, ['&nbsp;' => ' ', "\r" => '', "\t" => '']);
    $arr = explode('<div id="midbannerdiv">', $content);
    $data = '';
    if (sizeof($arr) > 1)
    {
        for ($i = 0; $i <= 1; $i++)
            if ($cut = strpos($arr[$i], '<a id="'))
//was          if ($cut = strpos($arr[$i], '<div id="1'))
                $data .= substr($arr[$i], $cut);

    }
    else
        $data .= substr($arr[0], strpos($arr[0], '<a id="'));
//was      $data .= substr($arr[0], strpos($arr[0], '<div id="1'));

    $matches = explode('

<a id="', $data);
//was  $matches = explode('<div id="1', $data);
  foreach($matches as $match)
   if (strpos($match, ' data-') && !strpos($match, '(W)') && !strpos($match, '(U17)') && !strpos($match, '(U19)') && !strpos($match, '(U21)'))
   {
    $match = strtr($match, array('<b>' => '', '</b>' => ''));
    $i = substr($match, 0, 7);
    $ev = substr($match, strpos($match, ' data-') + 6);
    $ev = substr($ev, 0, strpos($ev, '>')).';';
    $ev = '$'.str_replace(' data_', ';$', strtr($ev, ['-' => '_']));
    $ev = strtr($ev, [' "' => ' \"', '" ' => '\" ']);//, '".' => '\".']);
    //$ev = strtr($ev, [' "' => ' \"', '" ' => '\" ', '".' => '\".']);
    eval($ev);
    $g = $country_name.'#'.$league_short;
    if (isset($groups[$g])) {
      $g = $groups[$g];
      list($koh, $kom) = explode(':', $ko);
      //($koh == 0) ? $koh = 23 : $koh -= 1;
      if (strlen($koh) == 1) $koh = '0' . $koh;
      list($match_year, $date) = explode('_', $matchday, 2);
      $date = strtr($date, '_', '-');
      $d = "$date $koh:$kom";
      if (!isset($home_team)) {
        $home_team = substr($match, strpos($match, 'score_home_txt'));
        $home_team = substr($home_team, strpos($home_team, '>') + 1);
        $home_team = trim(substr($home_team, 0, strpos($home_team, '</div>')));
      }
      $home_team = strtr($home_team, '_', '-');
      if (isset($realteam1[$home_team]))
        $h = $realteam1[$home_team];
      else {
        $h = $home_team;
        $teamerr .= "$h\n";
      }
      unset($home_team);
      if (!isset($away_team)) {
        $away_team = substr($match, strpos($match, 'score_away_txt'));
        $away_team = substr($away_team, strpos($away_team, '>') + 1);
        $away_team = trim(substr($away_team, 0, strpos($away_team, '</div>')));
      }
      $away_team = strtr($away_team, '_', '-');
      if (isset($realteam1[$away_team]))
        $a = $realteam1[$away_team];
      else {
        $a = $away_team;
        $teamerr .= "$a\n";
      }
      unset($away_team);
      $minute = substr($match, strpos($match, '<div id="match_status" '));
      $minute = substr($minute, strpos($minute, '>') + 1);
      $minute = rtrim(substr($minute, 0, strpos($minute, '<')), "'");
      $minute = rtrim($minute, '+');
      switch ($game_status) {
        case 'Fin'  :
        case 'E/T'  :
        case 'Pen'  : $s = 'FT'; break;
        case 'Sched': $s = '-'; break;
        case 'H/T'  : $s = 'HT'; break;
        case 'Int'  : $s = 'SUS'; break;
        case 'Post' : $s = 'POS'; break;
        case 'Abd'  :
        case 'Canc' : $s = 'CAN'; break;
        default     : $s = $minute;
      }
      if (!strpos($match, '<div class="scoreh_ft score_cell centerTXT"> </div>') && ($cut = strpos($match, 'scoreh_ft'))) {
        $r = substr($match, $cut + 34);
        $r = substr($r, 0, strpos($r, '</div>
</div>'));
        $r = str_replace('</div>
<div class="scorea_ft score_cell centerTXT">', ':', $r);
        $r = strtr($r, ' ', '-');
        if ($cut = strpos($match, 'scoreh_et')) {
          $e = substr($match, $cut + 32);
          $e = substr($e, 0, strpos($e, '</div>
</div>'));
          $e = str_replace('</div>
<div class="scorea_et score_cell centerTXT">', ':', $e);
        }
        else
          $e = $r;
      }
      else
        $r = $e = '-:-';

      $z = (isset($base[$h.' - '.$a.'/'.$g][8])) ? $base[$h.' - '.$a.'/'.$g][8] : '';
      if ($i && $d && $h && $a) $base[$h.' - '.$a.'/'.$g] = array($h,$a,$d,$s,$e,$r,$g,$i,$z);
    }
   }

  foreach ($base as $match => $data)
  {
//    if ($data[0] == 'Levante' && $data[2] == '02-26 21:00')
//        continue;

//    if ($data[0] == 'Celtic' && $data[2] == '02-27 16:00')
//        continue;

    $out .= $data[0].','.$data[1].','.$data[2].','.$data[3].','.$data[4].','.$data[5].','.$data[6].','.$data[7].','.$data[8]."\n";
  }
  file_put_contents($fname, $out);
  file_put_contents($online_dir . 'log/teamerr', $teamerr);
}
unlink($online_dir . 'log/results.lock');
$log .= date('m-d H:i:s', time()).': '. (microtime(true) - $time_start);
$logfile = fopen($online_dir . 'log/xscores.log', 'a');
fwrite($logfile, $log ."\n");
fclose($logfile);
?>
