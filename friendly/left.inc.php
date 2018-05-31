<?php

$dir = array();
$seasons = array();
$season = '';
$months = array(
'01' => 'янваpь',
'02' => 'февpаль',
'03' => 'маpт',
'04' => 'апpель',
'05' => 'май',
'06' => 'июнь',
'07' => 'июль',
'08' => 'август',
'09' => 'сентябpь',
'10' => 'октябpь',
'11' => 'ноябpь',
'12' => 'декабpь'
);
if (is_dir($online_dir.$cca)) $dir = scandir($online_dir.$cca, 1);
foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1'))
  {
    $season = $subdir;
    $seasons[] = $season;
    if (!isset($s))
      $s = $season;

  }

echo '<center>';
foreach ($seasons as $season)
{
  echo "<p><a href=\"?a=$a&amp;s=$season&amp;m=month\" class=\"text18b\">$season</a></p>\n";
  if ($s == $season)
  {
    $online_service_path = $online_dir.$cca.'/'.$s;
    foreach ($months as $mn => $month)
      if (is_dir("$online_service_path/$mn"))
        echo "<p><a href=\"?a=$a&amp;s=$season&amp;mo=$mn&amp;m=month\" class=\"text18b\">$month</a></p>\n";
  }
  echo '<br />';
}
echo '</center>';
?>
