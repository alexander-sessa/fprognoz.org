<?php
$months = array(
'01' => 'янваpе ',
'02' => 'февpале ',
'03' => 'маpте ',
'04' => 'апpеле ',
'05' => 'мае ',
'06' => 'июне ',
'07' => 'июле ',
'08' => 'августе ',
'09' => 'сентябpе ',
'10' => 'октябpе ',
'11' => 'ноябpе ',
'12' => 'декабpе '
);

$out = array();
$rows = 40;
if (!isset($mo) || !is_dir($online_dir.'FCL/'.$s.'/'.$mo)) {
  $dir = scandir($online_dir.'FCL/'.$s);
  foreach ($dir as $file) if ($file[0] == '0' || $file[0] == '1')
    $mo = $file;

}
$dir = scandir($online_dir.'FCL/'.$s.'/'.$mo);
foreach ($dir as $file) if ($file[0] != '.') {
  $ta = explode('_', $file);
  if (!isset($ta[3]))
    $ta[3] = ''; // result

  if (is_file($online_dir."FCL/$s/programms/FCL".$ta[2]))
    $out[] = "<a href=?a=friendly&amp;s=$s&amp;m=prognoz&amp;t=".$ta[2].'>'.$ta[0].' '.$ta[1].' &nbsp; '.$ta[3].'</a><br />';
  else
    $out[] = $ta[0].' '.$ta[1].' '.$ta[3].'<br />';

}
echo '<p class="title text15b">Результаты товарищеских матчей в ' . $months[$mo] . $s . ' г.</p>';
$matches = sizeof($out);
if (!$matches)
  echo 'В этом месяце матчей нет';
else {
  echo '<table width="100%"><tr>';
  for ($i=0; $i<4; $i++) {
    echo '<td>';
    for ($j=$i*$rows; $j<($i+1)*$rows; $j++)
      echo ($j < $matches ? $out[$j] : '<br />');

    echo '</td>';
  }
  echo '</tr></table>';
}
?>
