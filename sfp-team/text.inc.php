<?php
if (!isset($t))
  $t = '';

$tt = $t;
$tp = $t;
isset($l) ? $l .= '/' : $l = '';
$cont = '';
if ($ref == "itog") {
  $f = '';
  if (is_file($online_dir."$cca/$s/publish/".$l."it$tt"))
    $f = $online_dir."$cca/$s/publish/".$l."it$tt";
  else if (is_file($online_dir."$cca/$s/publish/".$l."it".strtolower($tt)))
    $f = $online_dir."$cca/$s/publish/".$l."it".strtolower($tt);
  if ($f)
    $cont = file_get_contents($f);

}
else if ($ref == 'news') {
  if (is_file($online_dir."$cca/$s/publish/$l".'news'))
    $cont = file_get_contents($online_dir."$cca/$s/publish/$l".'news');
  else if (is_file($online_dir."$cca/$s/$l".'news'))
    $cont = file_get_contents($online_dir."$cca/$s/$l".'news');
}
if (trim($cont))
{
  if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
    $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));

  echo '<pre>'.$cont.'</pre>';
}
else
  echo 'Информация отсутствует';

?>
