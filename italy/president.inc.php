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
<br />рассылка:<input type="submit" name="mailer" value="игрокам" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=email";' /><input type="submit" name="mailer" value="пресс-релиз" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=maillist";' /><?php if (isset($t)) {?><input type="submit" name="mailer" value="программка" onclick='location.href="?<?="a=$a&amp;s=$s&t=$t";?>&amp;m=maillist&amp;file=program";' /><input type="submit" name="publish" value="прогнозы" onclick='location.href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=maillist&amp;file=prognoz";' /><input type="submit" name="itogi" value="итоги" onclick='location.href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=maillist&amp;file=itogi";' /><input type="submit" name="mailer" value="обзор" onclick='location.href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=maillist&amp;file=review";' /><?php }?>
файлы:<input type="submit" name="makeprog" value="новая программка" onclick='nw=window.open("/online/makeprogramm.php?cc=<?=$cca;?>","MakeProgramm",""); nw.opener=self; return false;' /><input type="submit" name="news" value="новости" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=editnews";' /><input type="submit" name="codes" value="игроки" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=codestsv";' /><input type="submit" name="files" value="прочее" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=files";' />