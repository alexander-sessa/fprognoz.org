<center>
<?php
$cclen = strlen($cca);

$dir = scandir($online_dir . $cca, 1);
$season = '';
$seasons = array();
$cup = array();
$gold = 0;
$poff = array();
$super = array();
foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1'))
  {
    $season = $subdir;
    $seasons[] = $season;
    if (!isset($s))
      $s = $season;

  }

if (!isset($c))
  $c = '';

$ligues = array(
"GOLDL" => "Золотая Лига",
"CHAML" => "Лига&nbsp;Чемпионов",
"CUPSL" => "Кубковая Лига",
"UEFAL" => "Лига Европы",
);
foreach ($seasons as $season)
{
  echo '<p><a href="?a='.$a.'&amp;s='.$season.'&amp;m=text&amp;ref=news" class="text18b">'.$season.'</a></p>
';
  if ($s == $season)
  {
    $online_service_path = "online/$cca/$s";
    if (is_dir("$online_service_path/programms"))
    {
      $dir = scandir("$online_service_path/programms");
      unset($dir[1]);
      unset($dir[0]);
      foreach ($ligues as $lg => $ltitle)
      {
        echo "<p class=\"text15b\">$ltitle</p>\n";
        foreach ($dir as $prog) if ($prog[0] == $lg[0] && $prog[1] == $lg[1])
        {
          $to = substr($prog, 5);
          echo "
  <p><a href=\"?a=$a&amp;s=$season&amp;m=text&amp;l=$lg&amp;t=$to&amp;ref=prog\">тур $to:</a>\n";
          if (is_file("$online_service_path/publish/$lg/itc$to"))
            echo "
  <a href=\"?a=$a&amp;s=$season&amp;m=text&amp;l=$lg&amp;t=$to&amp;ref=itog\">итоги,</a>
  <a href=\"?a=$a&amp;s=$season&amp;m=text&amp;l=$lg&amp;t=$to&amp;ref=rev\">обзор</a>
  </p>\n";
          else
            echo "
  <a href=\"?a=$a&amp;c=$c&amp;s=$season&amp;m=prognoz&amp;l=$lg&amp;t=$to\">прогнозы</a>
  </p>\n";

        }
      }
    }
  }
}
?>
</center>
