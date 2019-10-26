<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>FPrognoz.org Draw Tool (Cup)</title>
<link rel="StyleSheet" href="../css/fp.css" type="text/css">
</head>
<body>
<center>
<?php
//if (isset($_POST['ccode'])) $ccode = strtoupper(trim($_POST['ccode'])); else 
$ccode = '';
if (isset($_POST['basket1'])) $basket1 = $_POST['basket1']; else $basket1 = '';
if (isset($_POST['basket2'])) $basket2 = $_POST['basket2']; else $basket2 = '';

echo '<form name="dform" action="draw.php" method=post>
<table>
<tr><th>корзина 1 (сеяные)</th><th>корзина 2 (несеяные)</th></tr>
<tr><td><textarea name=basket1 rows=16 cols=58>'.$basket1.'</textarea></td><td><textarea name=basket2 rows=16 cols=58>'.$basket2.'</textarea></td></tr>
<tr><td><input type=submit name=draw value="жеребьевка и построение генераторов"></td><td>злые генераторы: <input name=angry type=checkbox value=1></td></tr>
</table>
</form>
';
if (!$basket1)
  echo 'Для жеребьевки укажите список команд по одной в строке в одной или двух корзинах (сеяные/несеяные).<br />
Для интернациональных турниров, где нужно разводить команды одного тренера, одной страны и ранее<br />
игравшие в одной группе, список указывается в расширенном формате: <b>команда, страна, тренер, группа</b><br />
или <b>команда (код страны) тренер, группа</b> (параметры разделяются запятыми или скобками).<br />
Отметка "злые генераторы" несколько увеличит шанс появления "единиц" в генераторах: 40 : 30 : 30
';
elseif (!$ccode)
{
  //$basket = file('basket');
  $basket = explode("\n", trim($basket1));
  if (!$basket2)
    shuffle($basket);
  else
    $basket = array_merge($basket, explode("\n", trim($basket2)));
  $all = array();
  $b1 = array();
  $b2 = array();
  $firstleg = "";
  $secondleg = "";
  $firstlegs = "";
  $secondlegs = "";

  $i = 1;
  foreach ($basket as $line) if ($line = trim($line))
  {
    $line = strtr($line, '()', ',,');
    $atemp = explode(',', $line);
    if (!isset($atemp[3])) $atemp[3] = $i++;
    $all[] = array(trim($atemp[0]), trim($atemp[1]), trim($atemp[2]), trim($atemp[3]));
  }
  $n = sizeof($all);
  $p = $n / 2;
  for ($i=0; $i<$p; $i++) $b1[] = $all[$i];
  for ($i=$p; $i<$n; $i++) $b2[] = $all[$i];

//////////////////////////////////////////

  while ($n = sizeof($b1))
  {
    // 1,2
    $counter = array();
    foreach ($b1 as $team)
    {
      if (isset($counter[$team[1]])) $counter[$team[1]]++; else $counter[$team[1]] = 1;
      if (isset($counter[$team[2]])) $counter[$team[2]]++; else $counter[$team[2]] = 1;
    }
    foreach ($b2 as $team)
    {
      if (isset($counter[$team[1]])) $counter[$team[1]]++; else $counter[$team[1]] = 1;
      if (isset($counter[$team[2]])) $counter[$team[2]]++; else $counter[$team[2]] = 1;
    }
    $bs = array();
    foreach ($b1 as $i => $team)
    {
      $bs['c'][] = $counter[$team[1]];
      $bs['n'][] = $counter[$team[2]];
      $bs['i'][] = $i;
    }
    array_multisort($bs['n'],SORT_DESC, $bs['c'],SORT_DESC, $bs['i']);
    reset($bs);
    $i = $bs['i'][0];
    $team = $b1[$i][0];
    $country = $b1[$i][1];
    $name = $b1[$i][2];
    $group = $b1[$i][3];
    unset($b1[$i]);
    shuffle($b2);
    $team2 = '';
    $j = 0;
    for ($i=0; $i<$n; $i++)
      if (($b2[$i][1] != $country) && ($b2[$i][2] != $name) && ($b2[$i][3] != $group))
        $j = $i;
    $team2 = $b2[$j][0];
    $country2 = $b2[$j][1];
    $name2 = $b2[$j][2];
    $group2 = $b2[$j][3];
    unset($b2[$j]);
  
//echo "$team ($country) $name - $team2 ($country2) $name2 \n";
    $firstleg .= "$team";
    $firstlegs .= "$team";
    if ($country) $firstleg .= " ($country)";
    if ($name) $firstleg .= " $name";
    $firstleg .= " - $team2";
    $firstlegs .= " - $team2";
    $secondleg .= "$team2";
    $secondlegs .= "$team2";
    if ($country2)
    {
      $firstleg .= " ($country2)";
      $secondleg .= " ($country2)";
    }
    if ($name2)
    {
      $firstleg .= " $name2";
      $secondleg .= " $name2";
    }
    $secondleg .= " - $team";
    $secondlegs .= " - $team";
    if ($country) $secondleg .= " ($country)";
    if ($name) $secondleg .= " $name";
    $firstleg .= "\n";
    $secondleg .= "\n";
    $firstlegs .= "\n";
    $secondlegs .= "\n";
    // 2,1
    $counter = array();
    foreach ($b1 as $team)
    {
      if (isset($counter[$team[1]])) $counter[$team[1]]++; else $counter[$team[1]] = 1;
      if (isset($counter[$team[2]])) $counter[$team[2]]++; else $counter[$team[2]] = 1;
    }
    foreach ($b2 as $team)
    {
      if (isset($counter[$team[1]])) $counter[$team[1]]++; else $counter[$team[1]] = 1;
      if (isset($counter[$team[2]])) $counter[$team[2]]++; else $counter[$team[2]] = 1;
    }
    $bs = array();
    foreach ($b2 as $i => $team)
    {
      $bs['c'][] = $counter[$team[1]];
      $bs['n'][] = $counter[$team[2]];
      $bs['i'][] = $i;
    }
    array_multisort($bs['n'],SORT_DESC, $bs['c'],SORT_DESC, $bs['i']);
    reset($bs);
    $i = $bs['i'][0];
    $team = $b2[$i][0];
    $country = $b2[$i][1];
    $name = $b2[$i][2];
    unset($b2[$i]);
    shuffle($b1);
    $team2 = '';
    $j = 0;
    for ($i=0; $i<$n-1; $i++)
      if (($b1[$i][1] != $country) && ($b1[$i][2] != $name))
        $j = $i;
    $team2 = $b1[$j][0];
    $country2 = $b1[$j][1];
    $name2 = $b1[$j][2];
    unset($b1[$j]);
    $firstleg .= "$team";
    $firstlegs .= "$team";
    if ($country) $firstleg .= " ($country)";
    if ($name) $firstleg .= " $name";
    $firstleg .= " - $team2";
    $firstlegs .= " - $team2";
    $secondleg .= "$team2";
    $secondlegs .= "$team2";
    if ($country2)
    {
      $firstleg .= " ($country2)";
      $secondleg .= " ($country2)";
    }
    if ($name2)
    {
      $firstleg .= " $name2";
      $secondleg .= " $name2";
    }
    $secondleg .= " - $team";
    $secondlegs .= " - $team";
    if ($country) $secondleg .= " ($country)";
    if ($name) $secondleg .= " $name";
    $firstleg .= "\n";
    $secondleg .= "\n";
    $firstlegs .= "\n";
    $secondlegs .= "\n";
  }
  $cal = "";
//  if ($country2 || $name2) 
  $cal .= "
<b>первые матчи</b> (для публикации):<br />
<textarea rows=$p cols=70>$firstleg</textarea><br />
<b>ответные матчи</b> (для публикации):<br />
<textarea rows=$p cols=70>$secondleg</textarea><br />";
  $cal .= "
<b>первые матчи</b> (для программки):   <b>ответные матчи</b>:<br />
<textarea rows=$p cols=34>$firstlegs</textarea><textarea rows=$p cols=34>$secondlegs</textarea><br />";

  $tours = 2;
  $genpt = $p + 1;
  $rows = 2 * ($genpt + 1);
  $league = 1;
  if ($_POST['angry']) $factor = 1; else $factor = 0;
  $out = array();

while ($genpt >= 3)
{
  for ($t=1; $t<=$tours; $t++)
  {
    for ($g=1; $g<=$genpt; $g++)
    {
      $pods = rand(1,10);
      for ($i=1; $i<=10; $i++)
      {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out[$t] .= $p;
        if ($i == $pods)
        {
          if ($p == 1) $f1 = 1; else $f1 = $factor;
          $pods = ceil(rand($factor, 6) / 3);
          if ($pods == 0) $pods++;
          if ($pods == $p) $pods = 'X';
          $out[$t] .= "($pods)";
          $pods = 0;
        }
      }
      $out[$t] .= ' ';
      for ($i=1; $i<=5; $i++)
      {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out[$t] .= $p;
      }
      $out[$t] .= " *\r\n";
    }
  }
  $out[1] .= "\r\n";
  $out[2] .= "\r\n";
  $genpt = $genpt/2 + 1;
}
  $gen .= "
<b>первые матчи</b>: (генераторы) <b>ответные матчи</b>:<br />
<textarea rows=$rows cols=22>$out[1]</textarea><textarea rows=$rows cols=22>$out[2]</textarea><br />";
}
echo "<table><tr><td>$cal</td><td>$gen</td></tr></table>";
?>
</body>
</html>
