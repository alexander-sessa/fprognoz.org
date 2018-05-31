<center>
<?php
$dir = array();
$seasons = array();
$season = '';
if (is_dir($online_dir.$cca)) $dir = scandir($online_dir.$cca, 1);
foreach ($dir as $subdir) if (($subdir[0] == '2') || ($subdir[0] == '1')) {
  $season = $subdir;
  $seasons[] = $season;
  if (!isset($s)) $s = $season;
}

foreach ($seasons as $season) {
  echo '<p><a href="?a='.$a.'&amp;s='.$season.'&amp;m=text&amp;ref=news" class="text18b">'.$season.'</a></p>
';
  if ($s == $season) {
    $online_service_path = $online_dir.$cca.'/'.$s;
    if(is_dir($online_service_path.'/PRO')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=PRO&amp;m=text&amp;ref=news\">ProfiOpen</a></p>
<p>
";
      $dir = scandir($online_service_path.'/PRO');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n') {
        if (is_file($online_service_path.'/publish/PRO/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=PRO&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=PRO&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '</p>';
    }
    if(is_dir($online_service_path.'/FFP')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=FFP&amp;m=text&amp;ref=news\">Фестиваль&nbsp;ФП</a></p>
<p>
";
      $dir = scandir($online_service_path.'/FFP');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n') {
        if (is_file($online_service_path.'/publish/FFP/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=FFP&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=FFP&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '</p>';
    }
    if(is_dir($online_service_path.'/PRE')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=PRE&amp;m=text&amp;ref=news\">Predвидение</a></p>
<p>
";
      $dir = scandir($online_service_path.'/PRE');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n') {
        if (is_file($online_service_path.'/publish/PRE/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=PRE&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=PRE&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '</p>';
    }
    if(is_dir($online_service_path.'/VOO')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=VOO&amp;m=text&amp;ref=news\">Спартакиада</a></p>
<br />
";
    }
    if(is_dir($online_service_path.'/TOR')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=TOR&amp;m=text&amp;ref=news\">Лига&nbsp;\"Торпедо\"</a></p>
<p>
";
      $dir = scandir($online_service_path.'/TOR');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n') {
        if (is_file($online_service_path.'/publish/TOR/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=TOR&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=TOR&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '</p>';
    }
    if(is_dir($online_service_path.'/SPR')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=SPR&amp;m=text&amp;ref=news\">Спартакиада</a></p>
<p>
";
      $dir = scandir($online_service_path.'/SPR');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n') {
        if (is_file($online_service_path.'/publish/SPR/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=SPR&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=SPR&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '</p>';
    }
    if(is_dir($online_service_path.'/FWD')) {
      echo "<p class=\"text15b\"><a href=\"?a=$a&amp;s=$season&amp;l=FWD&amp;m=text&amp;ref=news\">Эксперт Лига</a></p>
<p>
";
      $dir = scandir($online_service_path.'/FWD');
      $i = 3;
      foreach ($dir as $file) if ($file[0] != '.' && $file[0] != 'n')
      {
        if (is_file($online_service_path.'/publish/FWD/it'.$file))
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=FWD&amp;m=text&amp;ref=itog&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        else
          echo "<a href=\"?a=$a&amp;s=$season&amp;l=FWD&amp;m=prognoz&amp;t=$file\">тур&nbsp;$file&nbsp;</a>";
        if (--$i) echo '&nbsp;';
        else {
          $i = 3;
          echo '<br />';
        }
      }
      echo '<p>';
    }
  }
  echo '<br />';
}
?>
</center>
