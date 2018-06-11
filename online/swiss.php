<?php
include ('swiss.inc.php');

$teams = [
'Onedivision',
'PrimeGang',
'Red Anfield',
'Kings Forecasts',
'Россия',
'Испания',
'КЛФП Харьков',
'КСП «Торпедо»',
'Шотландия',
'Португалия',
'Голландия',
'Италия',
'Белоруссия',
'Германия',
'Англия',
'Украина'
];
$games = []; // массив сыгранных матчей
$tours = 8;  // количество туров

// Simulator
for ($i = 1; $i <= $tours; $i++) {
  $res = SwissDraw($games, $teams, false);
  //print_r($res);
  echo "<br />\n".$res[0]."<br />\n";
  echo "В $i-м туре играют:<br />\n";
  $score = [];
  foreach ($res[1] as $match) {
    echo "$match<br />\n";
    list($home, $away) = explode(' - ', $match);
    if ($home == $away)
      $games[$home][$away] = 1;
    else {
      switch (floor(rand(0, 300) / 100)) {
        case 0: $games[$home][$away] = $score[$home] = 0; $games[$away][$home] = $score[$away] = 3; break;
        case 1: $games[$home][$away] = $score[$home] = 1; $games[$away][$home] = $score[$away] = 1; break;
        default: $games[$home][$away] = $score[$home] = 3; $games[$away][$home] = $score[$away] = 0;
      }
    }
  }
  // ranking
  echo "<br />\nПоложение команд после симуляции $i-го тура:<br />\n";
  $rank = [];
  foreach ($teams as $team)
    $rank[$team] = array_sum($games[$team]);
    arsort($rank);
    $newteams = [];
    foreach ($rank as $team => $points) {
       $newteams[] = $team;
       echo "$points	$team (+{$score[$team]})<br />\n";
    }

  $teams = $newteams;
}