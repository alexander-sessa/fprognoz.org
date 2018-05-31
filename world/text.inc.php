<p width=80%><font color=#<?=$main_ftcolor ?>><pre>
<?php
if (!isset($t))
  $t = '';

if (isset($l)) {
  $tt = $l.$t;
  $tp = $t;
  //$l .= '/';
}
else
{
  $tt = str_replace('NEW', '', $t);
  $tp = $tt;
  $l = '';
}
if ($ref == "itog") {
  $f = '';
  if (is_file($online_dir."$cca/$s/publish/".$l."it$tt"))
    $f = $online_dir."$cca/$s/publish/".$l."it$tt";
  else if (is_file($online_dir."$cca/$s/publish/".$l."it".strtolower($tt)))
    $f = $online_dir."$cca/$s/publish/".$l."it".strtolower($tt);
  if ($f)
  {
    $cont = file_get_contents($f);
    $cont = str_replace('<', '&lt;', $cont);
    $cont = str_replace('>', '&gt;', $cont);
//    $cont = str_replace(chr(146), "<img src=/images/redcard.gif>", $cont);
//    $cont = str_replace(chr(144), "<img src=/images/yellcard.gif>", $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
    $cont = str_replace('░', '<img src=/images/yellcard.gif>', $cont);
    $cont = str_replace('▓', '<img src=/images/redcard.gif>', $cont);
  }
  else $cont = 'Информация отсутствует'.$online_dir."$cca/$s/publish/".$l."it$tt";
}
else if ($ref == 'rev') {
  $f = '';
  if (is_file($online_dir."$cca/$s/publish/".$l."r$tt"))
    $f = $online_dir."$cca/$s/publish/".$l."r$tt";
  else if (is_file($online_dir."$cca/$s/publish/".$l."r".strtolower($tt)))
    $f = $online_dir."$cca/$s/publish/".$l."r".strtolower($tt);
  if ($f)
  {
    $cont = file_get_contents($f);
    $cont = str_replace('<', '&lt;', $cont);
    $cont = str_replace('>', '&gt;', $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', Str_Replace("<<","",$cont));
  }
  else $cont = 'Информация отсутствует'.$online_dir."$cca/$s/publish/".$l."r$tt";
}
else if ($ref == 'prog') {
  $f = $online_dir."$cca/$s/programs/$cca$l$t";
  if (is_file($f))
  {
    $cont = file_get_contents($f);
    $cont = str_replace('<', '&lt;', $cont);
    $cont = str_replace('>', '&gt;', $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
  }
  else $cont = 'Информация отсутствует';
}
else if ($ref == 'news') {
  if (is_file($online_dir."$cca/$s/publish/news"))
  {
    $cont = file_get_contents($online_dir."$cca/$s/publish/news");
    $cont = str_replace('<', '&lt;', $cont);
    $cont = str_replace('>', '&gt;', $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
  }
  else if (is_file($online_dir."$cca/$s/news"))
  {
    $cont = file_get_contents($online_dir."$cca/$s/news");
    $cont = str_replace('<', '&lt;', $cont);
    $cont = str_replace('>', '&gt;', $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('CP1251', 'UTF-8', str_replace('<<', '', $cont));
  }
  else $cont = 'Информация отсутствует';
}

echo $cont;
?>
</pre></font></p>
