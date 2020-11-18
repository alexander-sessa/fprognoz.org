#!/usr/bin/php
<?php
require '/home/fp/vendor/autoload.php';
use \PhpOffice\PhpSpreadsheet\Reader\Xls;

$ranking = '/home/fp/data/online/ranking/';
include 'realteam.inc.php';
$year = 2021;

function remote_file_size($url) {
    stream_context_set_default(['http' => ['method' => 'HEAD']]);
    $head = array_change_key_case(get_headers($url, 1));
    return $head['content-length'] ?? 0;
}

function HTTP_CURL($url, $ref) {
  $ch = curl_init();
  $options = array(
CURLOPT_URL => $url,
CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; NewRank/1.0; +https://fprognoz.org)',
CURLOPT_HTTPHEADER => array('ACCEPT_LANGUAGE: en', 'CONNECTION: Keep-Alive'),
CURLOPT_REFERER => $ref,
CURLOPT_HEADER => false,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_TIMEOUT => 60,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => 'gzip',
CURLOPT_SSL_VERIFYPEER => false
);
  curl_setopt_array($ch, $options);
  $ret = curl_exec($ch);
  curl_close($ch);
  return $ret;
}

function write_year_ranking($year) {
  global $ranking;
  global $realteam;
  $out = $log = '';
  $reader = new Xls();
  $spreadsheet = $reader->load($ranking . 'ECR-' . $year . '.xls');
  $sheet = $spreadsheet->getSheet(0);
  $col = 'B'; // в последнем ECR2019.xls A1 = 2019, что плохо
  while ($sheet->getCell($col.'1') != (string)$year)
    $col++;

  $row = 1;
  while (trim($sheet->getCell($col.($row++))))
  {
    $nc = $col;
    $team = trim($sheet->getCell(($nc++).(string)$row)->getCalculatedValue(), ' "');
    $cc = trim($sheet->getCell(($nc++).(string)$row)->getCalculatedValue(), '"');
    if ($cc == 'NED')
      $cc = 'NLD';

    $score = $sheet->getCell($nc.(string)$row)->getCalculatedValue() ?? 0;
    if ($team && isset($realteam[$team]))
      $out .= $realteam[$team].','.$cc.','.str_replace(',', '.', $score)."\n";
    else if ($score)
      $log .= $year.': ' . $score.' '.$cc.','.$team."\n";

  }

/* Gil Vicente
  if ($year == 2019)
    $out .= 'Gil Vicente,PRT,20
';
*/

  file_put_contents($ranking . 'rank.' . $year, $out);
  return $log;
}

$url = 'http://www.european-football-statistics.co.uk/ecr/ECR-' . $year . '.xls';
if (!is_file($ranking . 'ECR-' . $year . '.xls') || remote_file_size($url) != filesize($ranking . 'ECR-' . $year . '.xls')) {
  file_put_contents($ranking . 'ECR-' . $year . '.xls', HTTP_CURL($url, 'http://www.european-football-statistics.co.uk/ecr/ecr01-20.htm'));
  $log = write_year_ranking($year);
  $log .= write_year_ranking($year - 1);
  // merge rankings
  $week = date('W');
  //$k = $week < 24 ? min(50, $week + 18) : max(0, $week - 35);
  //$k = $week < 35 ? min(50, $week + 18) : $week - 35;
  $k = $week < 35 ? min(50, $week + 18) : max(0, $week - 35);
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
    $ateams['cc'][] = $at['cc'] == 'WAL' ? 'ENG' : $at['cc'] == 'LIE' ? 'SUI' : $at['cc'];
    $ateams['r'][] = round($at['r'], 2);
  }
  array_multisort($ateams['r'], SORT_DESC, $ateams['tn'], $ateams['cc']);
  for ($i=0; $i<sizeof($ateams['tn']); $i++)
    $out .= $ateams['tn'][$i].','.$ateams['cc'][$i].','.$ateams['r'][$i]."\n";

// national team rank
  $url = "http://www.eloratings.net/World.tsv";
  if ($content = HTTP_CURL($url, 'http://www.eloratings.net'))
  {
    $ccfile = file('en.teams.tsv');
    $cc = array();
    foreach ($ccfile as $line)
    {
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
