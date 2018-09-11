#!/usr/bin/php
<?php
$ranking = '/home/fp/data/online/ranking/';
include 'realteam.inc.php';
$year = 2019;
// rank 2019

function remote_file_size($url) {
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
     $data = curl_exec($ch);
     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
     curl_close($ch);
     return $size;
}

function write_year_ranking($year) {
  global $ranking;
  global $realteam;
  $xl = file($ranking . 'ECR' . $year . '.txt');
  unset($xl[0]);
  $out = $log = '';
  foreach ($xl as $line) if (strlen($line) > 16) {
    $line = str_replace('	NED	', '	NLD	', $line);
    $a = explode('	', $line);
    $nc = $a[2] == '' || is_numeric($a[2]) ? 3 : 2;
    $team = trim($a[$nc++], ' "');
    $cc = trim($a[$nc++], '"');
    $score = isset($a[$nc]) ? $a[$nc] : 0;
    if ($team && isset($realteam[$team])) {
      $out .= $realteam[$team].','.$cc.','.str_replace(',', '.', $score)."\n";
    }
    else if ($score)
      $log .= $year.': ' . $score.' '.$cc.','.$team."\n";

  }
  file_put_contents($ranking . 'rank.' . $year, $out);
  return $log;
}

$url = 'http://www.european-football-statistics.co.uk/ecr/ECR' . $year . '.xls';
if (!is_file($ranking . 'ECR' . $year . '.xls') || remote_file_size($url) != filesize($ranking . 'ECR' . $year . '.xls')) {
  file_put_contents($ranking . 'ECR' . $year . '.xls', file_get_contents($url));
  exec("/usr/bin/ssconvert -O 'separator=\"	\"' ".$ranking."ECR$year.xls ".$ranking."ECR$year.txt 2>/dev/null");
  $log = write_year_ranking($year);
  $log .= write_year_ranking($year - 1);
  // merge rankings
  $week = date('W');
  $k = $week < 24 ? min($week + 18) : max(0, $week - 35);
  //$k = $week < 35 ? min(50, $week + 18) : $week - 35;
  $balance = 1 - $k / 50;
  $teams = array();
  $rank = file($ranking . 'rank.' . ($year - 1));
  foreach ($rank as $line) if ($line = trim($line)) {
    list($team, $cc, $score) = explode(',', $line);
    $teams[$team]['cc'] = $cc;
    $teams[$team]['r'] = $score * $balance;
  }
  $rank = file($ranking . 'rank.' . $year);
  foreach ($rank as $line) if ($line = trim($line)) {
    list($team, $cc, $score) = explode(',', $line);
    $teams[$team]['cc'] = $cc;
    if (!isset($teams[$team]['r']))
      $teams[$team]['r'] = 0;

    $teams[$team]['r'] += $score;
  }
  $out = '';
  $ateams = array();
  foreach ($teams as $tn => $at) {
    $ateams['tn'][] = $tn;
    $ateams['cc'][] = $at['cc'] == 'WAL' ? 'ENG' : $at['cc'];
    $ateams['r'][] = round($at['r'], 2);
  }
  array_multisort($ateams['r'], SORT_DESC, $ateams['tn'], $ateams['cc']);
  for ($i=0; $i<sizeof($ateams['tn']); $i++)
    $out .= $ateams['tn'][$i].','.$ateams['cc'][$i].','.$ateams['r'][$i]."\n";

// national team rank
  $url = "http://www.eloratings.net/World.tsv";
  if ($content = file_get_contents($url)) {
    $ccfile = file('en.teams.tsv');
    $cc = array();
    foreach ($ccfile as $line) {
      list($code, $country) = explode('	', trim($line), 2);
      if ($cut = strpos($country, '	'))
        $country = substr($country, 0, $cut);

      $cc[$code] = $country;
    }
    $rank = explode("\n", $content);
    include ('cnames.inc.php');
    foreach ($rank as $line) {
      list($pl, $pp, $code, $r, $trash) = explode('	', $line, 5);
        $tn = $cc[$code];
        if (isset($cnames[$tn]))
          $tn = $cnames[$tn];

        $r = $r / 8 - 100;// 10 - 70;
        if ($r < 0.4)
          $r = 0.4;

        $out .= $tn.',INT,'.$r."\n";
    }
  }
  file_put_contents($ranking . 'rank', $out);
  file_put_contents($ranking . 'rank.log', $log);
}
?>
