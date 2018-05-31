<div style="white-space: pre-wrap; font-family: monospace; width: 98%;">
<?php
if (!isset($t))
  $t = '';

if ($cca == "UEFA") {
  $tt = 'c'.$t;
  $tp = $t;
  isset($l) ? $l .= '/' : $l = '';
}
elseif ($cca == "SFP") {
  $tt = $tp = $t;
  isset($l) ? $l .= '/' : $l = '';
}
else {
  $tt = str_replace('NEW', '', $t);
  $tp = $tt;
  $l = '';
}
$tname = '';
$lname = '';
if (is_file($online_dir."$cca/$s/codes.tsv")) {
  $acodes = file($online_dir."$cca/$s/codes.tsv");
  foreach ($acodes as $scode) if ($scode[0] != '#') {
    $ateams = explode('	', ltrim($scode, '-'));
    if (isset($_SESSION['Coach_name']) && $_SESSION['Coach_name'] == trim($ateams[2])) {
      $tname = trim($ateams[1]);
      $lname = trim($ateams[4]);
    }
  }
}
if ($ref == "itog") {
  $f = '';
  if (is_file($online_dir."$cca/$s/publish/".$l."it$tt"))
    $f = $online_dir."$cca/$s/publish/".$l."it$tt";
  else if (is_file($online_dir."$cca/$s/publish/".$l."it".strtolower($tt)))
    $f = $online_dir."$cca/$s/publish/".$l."it".strtolower($tt);
  if ($f) {
    $cont = file_get_contents($f);
    $cont = htmlspecialchars($cont);
//    $cont = str_replace(chr(146), "<img src=/images/redcard.gif>", $cont);
//    $cont = str_replace(chr(144), "<img src=/images/yellcard.gif>", $cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
    $cont = str_replace('░', '<img src="/images/yellcard.gif" alt="yellow card" />', $cont);
    $cont = str_replace('▓', '<img src="/images/redcard.gif" alt="red card" />', $cont);
    if (isset($_SESSION['Coach_name'])) {
      $cont = str_replace($_SESSION['Coach_name'], '<span class="magenta">'.$_SESSION['Coach_name'].'</span>', $cont);
      if ($lname)
        $cont = str_replace($lname, '<span class="magenta">'.$lname.'</span>', $cont);

      if ($tname)
        $cont = str_replace($tname, '<span class="magenta">'.$tname.'</span>', $cont);

    }
  }
  else $cont = 'Информация отсутствует'.$online_dir."$cca/$s/publish/".$l."it$tt";
}
else if ($ref == 'rev') {
  $f = '';
  if (is_file($online_dir."$cca/$s/publish/".$l."r$tt"))
    $f = $online_dir."$cca/$s/publish/".$l."r$tt";
  else if (is_file($online_dir."$cca/$s/publish/".$l."r".strtolower($tt)))
    $f = $online_dir."$cca/$s/publish/".$l."r".strtolower($tt);
  if ($f) {
    $cont = file_get_contents($f);
    $cont = htmlspecialchars($cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', Str_Replace("<<","",$cont));
  }
  else $cont = 'Информация отсутствует'.$online_dir."$cca/$s/publish/".$l."r$tt";
}
else if ($ref == 'prog') {
  $f = $online_dir."$cca/$s/publish/p".strtolower($tp);
  if (is_file($f)) {
    $cont = file_get_contents($f);
    $cont = htmlspecialchars($cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
  }
  else $cont = 'Информация отсутствует';
}
else if ($ref == 'news') {
  if (is_file($online_dir."$cca/$s/publish/news")) {
    $cont = file_get_contents($online_dir."$cca/$s/publish/news");
    $cont = htmlspecialchars($cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('KOI8-R', 'UTF-8', str_replace('<<', '', $cont));
  }
  else if (is_file($online_dir."$cca/$s/news")) {
    $cont = file_get_contents($online_dir."$cca/$s/news");
    $cont = htmlspecialchars($cont);
    if (mb_detect_encoding($cont, 'UTF-8', true) === FALSE)
      $cont = iconv('CP1251', 'UTF-8', str_replace('<<', '', $cont));
  }
  else $cont = 'Информация отсутствует';
}

echo $cont;
?>
</div>
