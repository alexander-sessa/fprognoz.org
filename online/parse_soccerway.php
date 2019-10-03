#!/usr/bin/php
<?php
date_default_timezone_set('UTC');
$fixtures = '/home/fp/data/online/fixtures';

$debug = false;

$ccodes = array(
'INT.ACN' => [40109, 40358, 40359, 40360, 40357, 40356],
'INT.CA'  => [43009, 43010, 43011, 43007, 43008],
'INT.CCC' => [43244, 43245, 43246, 43243],
'INT.UNL' => [54499, 54500, 54501, 54502, 54503, 54497, 54498],
'INT.ECQ' => [46025, 46026, 46027, 46028, 46029, 46030, 46031, 46032, 46033],
'INT.CNL' => [46555, 46556, 46557, 46558, 46559],
'INT.CLU' => [50008, 51368, 51369, 51370, 51366, 51367, 51365],
'INT.FRI' => [50834, 50835, 51146],

'ENG.PL'  => [53145],
'ENG.D1'  => [53782, 53781, 53780],
'GER.PL'  => [53499],
'ITA.PL'  => [54890],
'ESP.PL'  => [53502],
'FRA.PL'  => [53638],
'NLD.PL'  => [54058, 54057, 54056],
'RUS.PL'  => [53628],
'RUS.D1'  => [53625],
'PRT.PL'  => [53517],
'SCO.PL'  => [53513, 53514],
'BLR.PL'  => [51126],
'UKR.PL'  => [54067, 54066, 54068, 54672, 54673],
'BEL.PL'  => [53516, 53515],
'SUI.PL'  => [54327],

'ENG.CUP' => [53377, 53380, 53381, 53386, 53387, 53383,
              51908, 51909, 51911, 51912, 51910],
'GER.CUP' => [53844, 53845, 53847, 53848, 53846],
'ITA.CUP' => [53620, 53621, 53623, 53624, 53622],
'ESP.CUP' => [53578, 53582, 53584, 53585, 53583],
'FRA.CUP' => [54024, 54027, 54030, 54031, 54029,
              52200, 52203, 52205, 52206, 52204],
'NLD.CUP' => [52896, 52897, 52899, 52900, 52898],
'RUS.CUP' => [53600, 53605, 53607, 53608, 53606],
'PRT.CUP' => [53859, 53860, 53862, 53863, 53861,
              51750, 51751, 51749],
'SCO.CUP' => [53839, 53840, 53841, 53838,
              51129, 51127, 51130, 51131, 51128],
'UEL.CUP' => [54011, 54010, 54004, 54008, 54013, 54014, 54009],
'UCL.CUP' => [54143, 54142, 54140, 54146, 54147, 54141],
'BLR.CUP' => [54062, 54064, 54065, 54063],
'UKR.CUP' => [53232, 53235, 53237, 53238, 53236],
'BEL.CUP' => [52763, 52765, 52766, 52764],

'ENG.SC' => [49799],
'GER.SC' => [49806],
'ITA.SC' => [],
'ESP.SC' => [49797, 53560],
'FRA.SC' => [49737],
'NLD.SC' => [49798],
'RUS.SC' => [49668],
'PRT.SC' => [49738],
'UEL.SC' => [49825],
'BLR.SC' => [46600],
'UKR.SC' => [49614],
);

function parse_team_name($match, $side) {
  $fr = strpos($match, $side) + 6;
  $to = strpos($match, '/td>', $fr);
  $str = substr($match, $fr, $to - $fr);
  if ($fr = strpos($str, 'title="')) {
    $fr = $fr + 7;
    return trim(substr($str, $fr, strpos($str, '">', $fr) - $fr));
  }
  else {
    $fr = strrpos($str, '">') + 2;
    return trim(substr($str, $fr, strpos($str, '<', $fr) - $fr));
  }
}

$out = [];
foreach ($ccodes as $cc => $rounds) foreach ($rounds as $round_id) {
  $has_next_page = 1;
  $tournament = explode('.', $cc)[1];
//'http://www.soccerway.com/ajax.php?block_id=page_competition_1_block_competition_matches_6&block_name=block_competition_matches&callback_params=%7B%22page%22%3A%200%2C%20%22round_id%22%3A%20' . $round_id . '%2C%20%22outgroup%22%3A%20false%2C%20%22view%22%3A%202%7D&action=changePage&params=%7B%22page%22%3A%20'.$page.'%7D'
  for ($page=0; $page<8 && $has_next_page==1; $page++) if ($sw = file_get_contents('http://int.soccerway.com/a/block_competition_matches_summary?block_id=page_competition_1_block_competition_matches_summary_5&callback_params=%7B%22page%22%3A%220%22%2C%22block_service_id%22%3A%22competition_summary_block_competitionmatchessummary%22%2C%22round_id%22%3A%22'.$round_id.'%22%2C%22outgroup%22%3A%22%22%2C%22view%22%3A%222%22%2C%22competition_id%22%3A%227%22%7D&action=changePage&params=%7B%22page%22%3A'.$page.'%7D')) {
//'http://int.soccerway.com/a/block_competition_matches_summary?block_id=page_competition_1_block_competition_matches_summary_5&callback_params=%7B%22page%22%3A%220%22%2C%22block_service_id%22%3A%22competition_summary_block_competitionmatchessummary%22%2C%22round_id%22%3A%22'.$round_id.'%22%2C%22outgroup%22%3A%22%22%2C%22view%22%3A%222%22%2C%22competition_id%22%3A%227%22%7D&action=changePage&params=%7B%22page%22%3A'.$page.'%7D'
    $decoded = json_decode($sw, true);
    $sw = $decoded['commands'][0]['parameters']['content'];
    $has_next_page = $decoded['commands'][1]['parameters']['attributes']['has_next_page'];
//    $amonth = explode('page_competition_1_block_competition_matches_6_match', $sw);
    $amonth = explode('page_competition_1_block_competition_matches_summary_5_match', $sw);
    unset($amonth[0]);
    foreach ($amonth as $match) {
      $fr = strpos($match, 'dd/mm/yy') + 10;
      $to = strpos($match, '</span>', $fr);
      $str = substr($match, $fr, $to - $fr);
      list($day, $month, $year) = explode('/', $str);
      $year = '20'.$year;
      $home = parse_team_name($match, 'team-a');
      $away = parse_team_name($match, 'team-b');
      if ($fr = strpos($match, "'HH : MM'>")) {
        $time = substr($match, $fr + 10);
        $time = str_replace(' ', '', substr($time, 0, strpos($time, '<')));
      }
      else $time = '19:00';
      $mtime = strtotime("$year/$month/$day");
      $week = date('W', $mtime);
      if (strpos($time, ':')) $out["$year/$week"][$cc][] = "$home,$away,".date('m-d', $mtime).",$time,$tournament\n";
    }
  }
}
foreach ($out as $dir => $tournaments) {
  if (!is_dir("$fixtures/$dir")) mkdir("$fixtures/$dir", 0755, true);
  foreach ($tournaments as $tournament => $matches)
    if ($debug) echo "\n$dir/$tournament\n".implode('', $matches);
    else file_put_contents("$fixtures/$dir/$tournament", implode('', $matches));

}
?>
