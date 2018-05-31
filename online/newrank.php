#!/usr/bin/php
<?php
$ranking = '/home/fp/data/online/ranking/';
include 'realteam.inc.php';
$year = 2018;
// rank 2018

function remote_file_size($url){
     $ch = curl_init($url);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);

     $data = curl_exec($ch);
     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

     curl_close($ch);
     return $size;
}

$url = 'http://www.european-football-statistics.co.uk/ecr/ECR' . $year . '.xls';
if (remote_file_size($url) != filesize($ranking . 'ECR' . $year . '.xls'))
{
  file_put_contents($ranking . 'ECR' . $year . '.xls', file_get_contents($url));
  exec("/usr/bin/ssconvert -O 'separator=\"	\"' ".$ranking."ECR$year.xls ".$ranking."ECR$year.txt 2>/dev/null");
//ssconvert -O 'separator="'$'\t''"' ECR2017.xls 2017.txt
//tr -d '\r' <ECR2017.tx > ECR2017.t
  $xl = file($ranking . 'ECR' . $year . '.txt');
  unset($xl[0]);
  $out = '';
  $log = '';
  foreach ($xl as $line) if (strlen($line) > 16)
  {
    $line = str_replace('	"NED"	', '	"NLD"	', $line);
    $line = str_replace('	"BLS"	', '	"BLR"	', $line);
    $line = str_replace('	NED	', '	NLD	', $line);
    $line = str_replace('	BLS	', '	BLR	', $line);
    $a = explode('	', $line);
    $a3 = trim($a[3], ' "');
    $a4 = trim($a[4], '"');
    if (trim($a3) && isset($realteam[$a3]))
        $out .= $realteam[$a3].','.strtoupper($a4).','.str_replace(',', '.', $a[5])."\n";
    else if (isset($a[5]))
        $log .= $a[5].' '.strtoupper($a4).','.$a3."\n";

  }
  file_put_contents($ranking . 'rank.'.$year, $out);
// rank 2017
  $xl = file($ranking . 'ECR' . ($year - 1) . '.txt');
  unset($xl[0]);
  $out = '';
  foreach ($xl as $line) if (strlen($line) > 16)
  {
    $line = str_replace('	NED	', '	NLD	', $line);
//    $line = str_replace('	BLS	', '	BLR	', $line);
    $a = explode('	', $line);
    $a2 = trim($a[3], ' "');
    $a3 = trim($a[4], '"');
    if (trim($a2) && isset($realteam[$a2]))
        $out .= $realteam[$a2].','.strtoupper(trim($a3)).','.str_replace(',', '.', $a[5])."\n";
    else if (isset($a[5]))
        $log .= $a[5].' '.strtoupper($a3).','.$a2."\n";
  }
  file_put_contents($ranking . 'rank.' . ($year - 1), $out);
// rank 2017 merge 2018
  $ar9 = file($ranking . 'rank.' . ($year - 1));
  $ar0 = file($ranking . 'rank.' . $year);
  $at = explode(',', $ar9[0]);
  $topr9 = $at[2];
  $at = explode(',', $ar0[0]);
  $topr0 = $at[2];
//  $balance = round(($topr7 - $topr8) / $topr7, 1);
  $week = date('W', time());
  ($week < 35) ? $week = min(50, $week + 18) : $week -= 35;
  $balance = 1 - $week / 50;
  $teams = array();
  foreach ($ar9 as $line) if ($line = trim($line))
  {
    $at = explode(',', $line);
    $teams[$at[0]]['cc'] = $at[1];
    $teams[$at[0]]['r'] = $at[2] * $balance;
//echo $at[0];
  }

  foreach ($ar0 as $line) if ($line = trim($line))
  {
    $at = explode(',', $line);
    $teams[$at[0]]['cc'] = $at[1];
    if (!isset($teams[$at[0]]['r']))
        $teams[$at[0]]['r'] = 0;

    $teams[$at[0]]['r'] += $at[2];
  }
  $out = '';
  $ateams = array();
  foreach ($teams as $tn => $at)
  {
    $ateams['tn'][] = $tn;
    if ($at['cc'] == 'WAL')
        $ateams['cc'][] = 'ENG';
    else
        $ateams['cc'][] = $at['cc'];

    $ateams['r'][] = round($at['r'], 2);
  }
  array_multisort($ateams['r'], SORT_DESC, $ateams['tn'], $ateams['cc']);
  for ($i=0; $i<sizeof($ateams['tn']); $i++)
    $out .= $ateams['tn'][$i].','.str_replace('BLS', 'BLR', $ateams['cc'][$i]).','.$ateams['r'][$i]."\n";

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
        if (isset($cnames[$tn])) $tn = $cnames[$tn];
        $r = $r / 8 - 100;// 10 - 70;
        if ($r < 0.4) $r = 0.4;
        $out .= $tn.',INT,'.$r."\n";
    }
  }
  file_put_contents($ranking . 'rank', $out);
  file_put_contents($ranking . 'rank.log', $log);
}
?>
