#!/usr/bin/php
<?php
date_default_timezone_set('UTC');
$fixtures = '/home/fp/data/online/fixtures';
$cc = 'INT';
$out = '';
$url = "http://www.fifa.com/worldFootball/results/_results.htmx?gender=m&idAssociation1=0&idAssociation2=0&MatchStatus=1&rangeDate=2&ClassificationGroup=0&r=nr&page=0";
if ($content = file_get_contents($url)) {
  $table = substr($content, strpos($content, '<tbody>') + 7);
  $table = substr($table, 0, strpos($table, '</tbody>'));
  $ayear = explode('<td colspan="2" class="noBord thirty">', $table);
  unset($ayear[0]);
  $pweek = $pyear = '';
  foreach ($ayear as $match) {
    $fr = strpos($match, 'title="') + 7;
    $home = substr($match, $fr, strpos($match, '">', $fr) - $fr);
    $fr = strpos($match, ' noBord thirty">', $fr) + 16;
    $fr = strpos($match, 'title="', $fr) + 7;
    $away = substr($match, $fr, strpos($match, '">', $fr) - $fr);
    $fr = strpos($match, '<td class="secInfo sixteen">', $fr) + 28;
    $date = substr($match, $fr, strpos($match, '</td>', $fr) - $fr);
    $fr = strpos($match, '<td class="r secInfo thirty">', $fr) + 29;
    $tourn = substr($match, $fr, strpos($match, '</td>', $fr) - $fr);
    $day = substr($date, 0, 2);
    $month = substr($date, 3, 2);
    $year = substr($date, 6, 4);
    $week = date('W', strtotime("$year/$month/$day 12:00 GMT"));
    if ($week != $pweek) {
      if ($pweek) {
        if (!$pyear) $pyear = $year;
        if (!is_dir("$fixtures/$pyear/$pweek")) mkdir("$fixtures/$pyear/$pweek", 0755);
        file_put_contents("$fixtures/$pyear/$pweek/$cc", $out);
      }
      $pweek = $week;
      $pyear = $year;
      $out = '';
    }
    $out .= "$home,$away,$month-$day,12:00,$tourn\n";
  }
  if (!is_dir("$fixtures/$year/$week")) mkdir("$fixtures/$year/$week", 0755, true);
  file_put_contents("$fixtures/$year/$week/$cc", $out);
}
?>
