<?php
@header("Content-type: text/vnd.wap.wml");
$season = '2018-19';
$cc = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '.'));
$cc = substr($cc, strrpos($cc, '/') + 1);
$country_code = strtoupper($cc);
$season_dir = '/home/fp/data/online/'.$country_code.'/'.$season.'/';
$out = '';
if (isset($_GET['tour']))
{
  $tour = $_GET['tour'];
  if (is_file($season_dir.'programms/'.$tour))
  {
    if (($tour[0] == 'C') || ($tour[strlen($country_code)] == 'C'))
      $calfname = 'calc';
    else
      $calfname = 'cal'; 
    $programm = file_get_contents($season_dir.'programms/'.$tour);
    $programm = str_replace(')-', ') - ', $programm);
    $fr = strpos($programm, "$tour ");
    $fr = strpos($programm, "\n", $fr) + 1;
    $programm = substr($programm, $fr);
    $fr = strpos($programm, "Последний с");
    $matches = explode("\n", substr($programm, 0, $fr));
    $programm = substr($programm, $fr);
    $months = array(
    ' января' => '.01',' янваpя' => '.01',
    ' февраля' => '.02', ' февpаля' => '.02',
    ' марта' => '.03', ' маpта' => '.03',
    ' апреля' => '.04', ' апpеля' => '.04',
    ' мая' => '.05',
    ' июня' => '.06',
    ' июля' => '.07',
    ' августа' => '.08',
    ' сентября' => '.09', ' сентябpя' => '.09',
    ' октября' => '.10',  ' октябpя' => '.10',
    ' ноября' => '.11',  ' ноябpя' => '.11',
    ' декабря' => '.12', ' декабpя' => '.12'
);
    foreach ($months as $word => $digit)
      $programm = str_replace($word, $digit, $programm);

    $fr = strpos($programm, '.');
    $lastdate = trim(substr($programm, $fr - 2, 5));
    if (($fr1 = strpos($programm, ':', $fr)) && ($fr1 - $fr < 50))
      $lasttm = trim(substr($programm, $fr1 - 2, 5));
    else
      $lasttm = '';

    $out .= "$tour: $lastdate $lasttm<br/>\n";
    foreach ($matches as $line) if ($line = trim($line)) if (strpos($line, ' - '))
    {
      (strpos($line, '│') !== false) ? $divider = '│' : $divider = '|';
      $h = '';
      $a = '';
      $atemp = explode($divider, $line);
      if ($cut = strpos($atemp[2], ' - '))
      {
        $n = rtrim(trim($atemp[1]), '.');
        $d = trim($atemp[3]);
        $h = trim(substr($atemp[2], 0, $cut));
        $a = trim(substr($atemp[2], $cut + 3));
        if (trim(substr($atemp[2], -3)))
          $a = trim(substr($a, 0, strrpos($a, ' ')));

        if ($n == 11)
          $out .= "<br/>\n";

        $out .= "$n. $h - $a<br/>\n";
      }
    } 
    $out .= '
Прогноз:<input name="predict" /><br />
Код:<input name="team" /><br />
Пароль:<input name="pass" /><br />
<anchor>
<go method="post" href="index.php">
<postfield name="predict" value="$(predict)" />
<postfield name="team" value="$(team)" />
<postfield name="pass" value="$(pass)" />
<postfield name="cc" value="'.$country_code.'" />
<postfield name="tour" value="'.$tour.'" />
</go>
submit
</anchor>
';
  }
}
else
{
  $tours = scandir($season_dir.'programms');
  if (sizeof($tours) > 2)
  {
    foreach ($tours as $t) if (($t[0] != '.') && ($t = trim($t)))
      if (!is_file($season_dir.'publish/'.$t))
        $out .= '<a href="'.$cc.'.php?tour='.$t.'">'.$t.'</a><br />
';
  }
  else
    $out = '---<br />
';
}
print '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">
<wml><card id="fp_'.$cc.'" title="'.$country_code.'">
'.$out.'</card></wml>';
?>
