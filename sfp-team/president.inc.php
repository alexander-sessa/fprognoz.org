<?php
if (!isset($s))
{
  $s = '';
  if (isset($_SESSION['Country_code']))
  {
    $dir = scandir($online_dir.$_SESSION['Country_code']);
    foreach ($dir as $subdir)
      if ($subdir[0] == '2')
        $s = $subdir;

  }
}
?>
<br />
рассылка:
<input type="submit" name="mailer" value="игрокам" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=email";' />
файлы:
<input type="submit" name="news" value="новости" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=editnews";' />
<input type="submit" name="codes" value="игроки" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=codestsv";' />
