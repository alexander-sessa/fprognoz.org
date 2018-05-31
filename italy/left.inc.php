<?php

$dir = array();
$seasons = array();
$cup = array();
$gold = array();
$poff = array();
$super = array();
$season = '';
//$gold = 0;
$cclen = strlen($cca);
if (is_dir($online_dir.$cca)) $dir = scandir($online_dir.$cca, 1);
foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1'))
  {
    $season = $subdir;
    $seasons[] = $season;
    if (!isset($s))
      $s = $season;

  }

echo '<center>
';
foreach ($seasons as $season)
{
  echo '<p><a href="?a='.$a.'&amp;s='.$season.'&amp;m=text&amp;ref=news" class="text18b">'.$season.'</a></p>
';
  if ($s == $season)
  {
    $online_service_path = $online_dir.$cca.'/'.$s;
   if (is_dir("$online_service_path/programms"))
   {
    $dir = scandir("$online_service_path/programms");
    unset($dir[1]);
    unset($dir[0]);
    echo '<p class="text15b">ЧЕМПИОНАТ</p>
';
    foreach ($dir as $prog)
    {
      switch ($prog[$cclen])
      {
      case '0':
      case '1':
      case '2':
      case '3':
        $to = substr($prog, $cclen);
        $tt = str_replace('NEW', '', $to);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
        break;
      case 'C':
        $cup[] = substr($prog, $cclen);
        break;
      case 'G':
        $gold[] = substr($prog, $cclen);
        break;
      case 'P':
        $poff[] = substr($prog, $cclen);
        break;
      case 'S':
        $super[] = substr($prog, $cclen);
        break;
      }
    }
    if (sizeof($gold) == 1)
    {
        $to = strtolower($gold[0]);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">зол.матч</a>:';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
    }
    elseif (sizeof($gold))
    {
      echo '<p class="text15b">Золотой турнир</p>
';
      foreach ($gold as $to)
      {
        $tt = str_replace('NEW', '', $to);
        $tt = str_replace('G', '', $to);
        $to = strtolower($to);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
      }
    }
    if (sizeof($poff))
    {
      echo '<p class="text15b">Play-off</p>
';
      foreach ($poff as $to)
      {
        $tt = str_replace('NEW', '', $to);
        $tt = str_replace('P', '', $to);
        $to = strtolower($to);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
      }
    }
    if (sizeof($cup))
    {
      echo '<p class="text15b">КУБОК</p>
';
      foreach ($cup as $to)
      {
        $tt = str_replace('NEW', '', $to);
        $tt = str_replace('C', '', $to);
        $to = strtolower($to);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
      }
    }
    if (sizeof($super))
    {
      echo '<p class="text15b">СУПЕРКУБОК</p>
';
      foreach ($super as $to)
      {
        $tt = str_replace('NEW', '', $to);
        $tt = str_replace('S', '', $to);
        $to = strtolower($to);
        $prefix = '<a href="?a='.$a.'&amp;s='.$season.'&amp;t='.$to;
        echo '<p>'.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;'.$tt.'</a>:&nbsp;';
        if (is_file("$online_service_path/publish/it$to"))
          echo $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a></p>
';
        else
          echo $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a></p>
';
      }
    }
   }
  }
}
echo '</center>
';
?>
