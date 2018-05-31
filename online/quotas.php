<?php
$online_dir = '/home/fp/data/online/';
$ccs = array(
'ENG' => array('Англия', 'England'),
'BLR' => array('Беларусь', 'Belarus'),
'GER' => array('Германия', 'Germany'),
'NLD' => array('Голландия', 'Netherlands'),
'ESP' => array('Испания', 'Spain'),
'ITA' => array('Италия', 'Italy'),
'PRT' => array('Португалия', 'Portugal'),
'RUS' => array('Россия', 'Russia'),
'UKR' => array('Украина', 'Ukraine'),
'FRA' => array('Франция', 'France'),
'SCO' => array('Шотландия', 'Scotland'),
);
$teams = array();
// coach : team() : email()
$coach = array();
$lnames = array();
$maxteam = 0;
$maxcoach = 0;
$maxlname = 0;
foreach ($ccs as $country_code => $country_name)
{
  $dir = scandir($online_dir . $country_code);
  $season = '';
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
      $season = $subdir;
  $acodes = file($online_dir . $country_code . '/' . $season . '/codes.tsv');
  foreach ($acodes as $scode) if (($scode[0] != '-') && ($scode[0] != '#') && ($scode = trim($scode)))
  {
    $ateams = explode('	', $scode);
    $t = $country_code.':'.$ateams[0];
    $n = $ateams[1];
    $teams[$t] = $n;
    $maxteam = max($maxteam, strlen($n));
    $c = $ateams[2];
    $coach[$c]['teams'][] = "$n ($country_code)";
    $maxcoach = max($maxcoach, strlen($c));
    $m = $ateams[3];
    $amail = explode(' ', str_replace(',', ' ', $m));
    foreach ($amail as $email) if ($email = trim($email))
      $coach[$c]['emails'][] = strtolower($email);
    if ($l = trim($ateams[4]))
    {
      $lnames[$n] = $l;
      $maxlname = max($maxlname, strlen($l));
    }
//    echo "$t\t<$n>\t<$c>\t<$m>\t$l\n";
  }
}
$qb = file($online_dir . 'QUOTAS/qb');
foreach ($qb as $line) if ($line = trim($line))
{
  $ac = explode(',', $line);
  $c = $ac[0];
  unset($ac[0]);
  $coach[$c]['qc'] = sizeof($ac);
  foreach ($ac as $qb)
    $coach[$c]['qb'][] = $qb;
}
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\"  dir=\"ltr\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<meta http-equiv=\"Cache-Control\" content=\"no-cache\" />
<meta http-equiv=\"Pragma\" content=\"no-cache\" />
<title>FPrognoz.org Quotas</title>
<link rel=\"StyleSheet\" href=\"css/fpo.css\" type=\"text/css\" />
</head>
<body>
<center>
<table><tr><th><a href=\"quotas.php\">игрок</a></th><th>команды</th><th><a href=\"?sort=0\">количество</a></th><th><a href=\"?sort=q\">квота</a></th><th>дополн.квоты</th><th>адреса</th></tr>
";
$col = false;
ksort($coach);
$codes = '';
$i = 1;
foreach ($coach as $c => $ac) if ($c = trim($c))
{
//  echo $c."\t";
  $out = "<td align=\"left\">$c</td><td>";
  $codes .= "$i	";
  $i++;
  $r0 = 0;
  $rN = 0;
  foreach ($ac['teams'] as $n)
  {
    if (strpos($n, '(ESP)')
     || strpos($n, '(ENG)')
     || strpos($n, '(GER)')
     || strpos($n, '(ITA)')
     || strpos($n, '(NLD)')
     || strpos($n, '(PRT)')
     || strpos($n, '(FRA)')
     || strpos($n, '(SCO)'))
      $r0++;

    if (strpos($n, '(BLR)')
     || strpos($n, '(RUS)')
     || strpos($n, '(UKR)'))
      $rN++;

    if ($n == 'Karlsruher SC (GER)' || $n == 'Hamburger SV (GER)')
    {
      $rN++;
      $r0--;
    }
    $out .= "$n<br />";
    $codes .= "$n,";
  }
  $codes = rtrim($codes, ',') . "	$c	";
  $qc = 2;
  if (isset($ac['qc']))
    $qc += $ac['qc'];

  if ($rN > 1)
    $rL = 1;
  else
    $rL = 0;

  $out .= "</td><td>".($r0+$rL)." / ".($r0+$rN)."</td><td align=\"center\">$qc</td>";
  $qb = "";
  foreach ($ac['qb'] as $q)
    $qb .= "$q<br />";

  if (!$qb)
    $qb = "&nbsp;";

  $out .= "<td>$qb</td><td>";
//  foreach (array_unique($ac['emails']) as $m) echo "$m, ";
  foreach (array_unique($ac['emails']) as $m)
  {
    $out .= "$m<br />";
    $codes .= "$m, ";
  }
  $out .= "</td>";
  $codes = rtrim($codes, ', ') . "\n";
  if ($qc < $r0)
    $color = 'pink';
  elseif ($qc == $r0)
    $color = 'yellow';
  elseif ($col)
    $color = 'lightgreen';
  else
    $color = 'lightgreen';

  $col = !$col;
  echo "<tr bgcolor=\"$color\">$out</tr>
";
}
echo "</table>
</center>
</body>
</html>
";
file_put_contents($online_dir . 'QUOTAS/2017-18/codes.tsv', $codes);
?>
