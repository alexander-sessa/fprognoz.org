<br />
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
if (isset($t))
  echo '<input type="submit" name="mailer" value="обзор тура" onclick='."'".'location.href="?a='.$a.'&amp;s='.$s.'&amp;t='.$t.'&amp;m=maillist&amp;file=review";'."'".' />
';
?>
<input type="submit" name="mailer" value="пресс-релиз" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=maillist";' />
