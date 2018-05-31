#!/usr/bin/php
<?php
date_default_timezone_set('UTC');
$fixtures = '/home/fp/data/online/fixtures';

$debug = false;

$ccodes = array(
'ENG.PL' => [41547],
'ENG.D1' => [42070, 42069, 42068],
'GER.PL' => [47657],
'ITA.PL' => [42011],
'ESP.PL' => [41509],
'FRA.PL' => [41646],
'NLD.PL' => [47971, 47970, 47969],
'RUS.PL' => [47835],
'RUS.D1' => [47743],
'PRT.PL' => [47741],
'SCO.PL' => [47730, 47731, 47879, 47880, 47878],
'BLR.PL' => [45312],
'UKR.PL' => [47821, 47820, 47822],

'ENG.CUP' => [46370, 46371, 46373, 46374, 46372,
              42255, 42256, 42262, 42263, 42259],
'GER.CUP' => [42299, 42300, 42302, 42303, 42301],
'ITA.CUP' => [47675, 47676, 47678, 47679, 47677],
'ESP.CUP' => [47128, 47132, 47134, 47135, 47133],
'FRA.CUP' => [47680, 47683, 47686, 47687, 47685,
              46840, 46843, 46845, 46846, 46844],
'NLD.CUP' => [47221, 47222, 47224, 47225, 47223],
'RUS.CUP' => [47566, 47571, 47573, 47574, 47572],
'PRT.CUP' => [42341, 42342, 42344, 42345, 42343,
              46058, 46059, 46057],
'SCO.CUP' => [42234, 42235, 42237, 42238, 42236,
              45526, 45529, 45530, 45527],
'UEL.CUP' => [41835, 41829, 41833, 41837, 41838, 41834],
'UCL.CUP' => [42384, 42382, 42386, 42387, 42383],
'BLR.CUP' => [47266, 47267, 47268, 47269],
'UKR.CUP' => [47618, 47620, 47621, 47619],

'ENG.SC' => [43273],
'GER.SC' => [],
'ITA.SC' => [],
'ESP.SC' => [43340],
'FRA.SC' => [43263],
'NLD.SC' => [43342],
'RUS.SC' => [43146],
'PRT.SC' => [43279],
'UEL.SC' => [43343],
'BLR.SC' => [],
'UKR.SC' => [43145],
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
