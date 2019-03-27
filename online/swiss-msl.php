<?php
include ('swiss.inc.php');

eval('$games = ' . file_get_contents('/home/fp/data/online/UNL/2019/games.inc') . ';');

$teams = [];
$tour = 0;
foreach ($games as $team => $data)
{
  if (!$tour)
    $tour = 1 + count($data);

  $teams[] = $team;
}

echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ФП. Адаптированная швейцарская жеребьёвка '.$tour.'-го тура Лиги Сайтов</title>
</head>
<body style="font-family: sans-serif">
<h2>Адаптированная швейцарская жеребьёвка '.$tour.'-го тура Лиги Сайтов</h2>';
$res = SwissDraw($games, $teams, true);
echo "<hr>
{$res[0]}\n
<pre><h2> Тур $tour        UNL".($tour > 9 ? $tour : '0'.$tour)."\n";
foreach ($res[1] as $match)
  echo "$match\n";

echo '</h2></pre>
</body>
</html>
';