<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>FPrognoz.org Draw Tool (EC Group stage)</title>
<link rel="StyleSheet" href="../css/fp.css" type="text/css">
</head>
<body>
<center>
<?php
if (isset($_POST['cc'])) $cc = strtoupper(trim($_POST['cc']));
elseif (isset($_GET['cc'])) $cc = strtoupper(trim($_GET['cc']));
else $cc = '';
if (isset($_POST['basket1'])) $basket1 = $_POST['basket1']; else $basket1 = '';
if (isset($_POST['basket2'])) $basket2 = $_POST['basket2']; else $basket2 = '';

echo '<form name="dform" action="cal6.php" method=post>
<table>
<tr><th>корзина 1</th><th>корзина 2 (для ЕК не используется)</th></tr>
<tr><td><textarea name=basket1 rows=16 cols=58>'.$basket1.'</textarea></td><td><textarea name=basket2 rows=16 cols=59 disabled>'.$basket2.'</textarea></td></tr>
<tr><td><input type=submit name=draw value="жеребьевка и построение генераторов"></td><td>код ассоциации: <input type=text name=cc value="'.$cc.'" size=5> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;злые генераторы: <input name=angry type=checkbox value=1></td></tr>
</table>
</form>
';
if (!$basket1)
  echo 'Для жеребьевки укажите список команд по одной в строке в 1-й или 2-х корзинах (если есть деление на сеяных/несеяных).<br />
Отметка "злые генераторы" несколько увеличит шанс появления "единиц" в генераторах: 40:30:30 вместо равновероятного.
';
else
{
  $tours = 10;
  $out = '';
  $out2 = '';
  $i1 = 0; $i2 = 0;$i1 = 0; $i2 = 0;
  $cal = '501432354201150234543021251340';
  $comma = explode("\n", trim($basket1));
  $gr = 0;
  $i = 0;
  $comm = array();
  foreach ($comma as $team) if (trim($team))
    if ($i++ < 6)
    {
      $comm[$gr][] = trim($team);
      $com2[$gr][] = trim(substr($team, 0, strpos($team, '(')));
    }
    else
    { 
      $i = 1;
      $ig[$gr] = 0;
      $gr++;
      $comm[$gr][] = trim($team);
      $com2[$gr][] = trim(substr($team, 0, strpos($team, '(')));
    } 
  for ($t=1; $t<=$tours; $t++)
  {
    if ($cc == 'UEFAL')
      $tt = $t;
    else
      $tt = $t; //      $tt = $t + 2;

    $out .= " Тур $tt     Код $cc".sprintf('%02d',$tt)."\r\n";
    $out2 .= " Тур $tt     Код $cc".sprintf('%02d',$tt)."\r\n";
    for ($g=0; $g<=$gr; $g++)
    {
      $o = ($t - 1) * 6;
      $out .= trim($comm[$g][$cal[$o++]]).' - '.trim($comm[$g][$cal[$o++]])."\r\n";
      $out .= trim($comm[$g][$cal[$o++]]).' - '.trim($comm[$g][$cal[$o++]])."\r\n";
      $out .= trim($comm[$g][$cal[$o++]]).' - '.trim($comm[$g][$cal[$o++]])."\r\n";
      $o = ($t - 1) * 6;
      $out2 .= trim($com2[$g][$cal[$o++]]).' - '.trim($com2[$g][$cal[$o++]])."\r\n";
      $out2 .= trim($com2[$g][$cal[$o++]]).' - '.trim($com2[$g][$cal[$o++]])."\r\n";
      $out2 .= trim($com2[$g][$cal[$o++]]).' - '.trim($com2[$g][$cal[$o++]])."\r\n";
    }
    $out .= "\r\n";
    $out2 .= "\r\n";
  }

  $calend = "
<textarea rows=16 cols=78>$out</textarea><br />
<textarea rows=16 cols=78>$out2</textarea>";

  $genpt = 16; $league = 1;
  if ($_POST['angry']) $factor = 1; else $factor = 0;
  $spacer = '      ';
  $out = "\r\n";
  for ($t=1; $t<=$tours; $t++)
  {
    if ($cc == 'UEFAL')
      $tt = $t;
    else
      $tt = $t; //      $tt = $t + 2;

    $out .= $cc.sprintf('%02d',$tt)."\r\n\r\n";
    for ($g=1; $g<=$genpt; $g++)
    {
      $pods = rand(1,10);
      for ($i=1; $i<=10; $i++)
      {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out .= $p;
        if ($i == $pods)
        {
          if ($p == 1) $f1 = 1; else $f1 = $factor;
          $pods = ceil(rand($factor, 6) / 3);
          if ($pods == 0) $pods++;
          if ($pods == $p) $pods = 'X';
          $out .= "($pods)";
          $pods = 0;
        }
      }
      $out .= ' ';
      for ($i=1; $i<=5; $i++)
      {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out .= $p;
      }
      if ($g % $league) $out .= " *$spacer"; else $out .= " *\r\n";
    }
    $out .= "\r\n";
  }
  $gen = "
<textarea rows=16 cols=38>
$out</textarea>";
}
echo "<table><tr><td>$calend</td><td>$gen</td></tr></table>";
?>
</body>
</html>
