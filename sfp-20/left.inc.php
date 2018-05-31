<?php

$dir = array();
$seasons = array();
$season = '';
$cclen = strlen($cca);
if (is_dir($online_dir.$cca)) $dir = scandir($online_dir.$cca, 1);
foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1')) {
    $season = $subdir;
    $seasons[] = $season;
    if (!isset($s))
      $s = $season;

  }

echo '<center>';
foreach ($seasons as $season) {
  echo "<a href='?a=$a&s=$season&m=text&ref=news'><font color=#$left_ftcolor class=text18b>$season</font></a><br />\n";
  if ($s == $season) {
    $online_service_path = $online_dir.$cca.'/'.$s;
    if (is_dir("$online_service_path/programs")) {
      $dir = scandir("$online_service_path/programs");
      unset($dir[1]);
      unset($dir[0]);
/*
      echo '<p class="text15b">ФП-Судоку</p>
';
*/
      foreach ($dir as $prog) {
        $to = substr($prog, 3);
        $tt = ltrim($to, '0');
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        if ($tt == '1') echo '<p><br /><b>Групповой этап:</b></p>';
        else if ($tt == '8') echo '<p><br /><b>Финальный этап:</b></p>';
        else if ($tt == '15') echo '<p><br /><b>Золотой матч:</b></p>';
        else if ($tt == '96') echo '<p><br /><b>Пробный турнир:</b></p>';
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/stat.$to"))
          echo $prefix.'&amp;m=result">итоги,</a>&nbsp;'.$prefix.'&amp;m=stat">стат.</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
      }
    }
  }
}
?>
