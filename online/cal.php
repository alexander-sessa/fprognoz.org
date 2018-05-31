<?php
$online_dir = '/home/fp/data/online/';
$cc = isset($_POST['cc']) ? strtoupper(trim($_POST['cc'])) : isset($_GET['cc']) ? strtoupper(trim($_GET['cc'])) : '';
$basket1 = isset($_POST['basket1']) ? $_POST['basket1'] : '';
$basket2 = isset($_POST['basket2']) ? $_POST['basket2'] : '';
$basket3 = isset($_POST['basket3']) ? $_POST['basket3'] : '';
if ($cc) {
  $dir = scandir($online_dir . $cc);
  foreach ($dir as $subdir) if (($subdir[0] == '2')) $season = $subdir;
  $cfg = file($online_dir . "$cc/$season/fp.cfg");
  $decoded = json_decode($cfg[4], true);
  foreach($decoded as $tournament) if ($tournament["type"] == "chm") break;
  $groups = $tournament["format"][0]["groups"];
  $teams = $tournament["format"][0]["tours"][1] / 2 + 1;
  $codes = file($online_dir . "$cc/$season/codes.tsv");
  for ($i=sizeof($codes); $i>0; $i--)
    if ($codes[$i][0] == '-' || $codes[$i][0] == '#')
      unset($codes[$i]);

  if (!$basket1)
    for ($i=0; $i<$teams; $i++)
       $basket1 .= explode('	', $codes[$i])[1]."\n";

  if ($groups > 1 && !$basket2)
    for ($i=$i; $i<$teams*2; $i++)
       $basket2 .= explode('	', $codes[$i])[1]."\n";

  if ($groups == 3 && !$basket3)
    for ($i=$i; $i<$teams*3; $i++)
       $basket3 .= explode('	', $codes[$i])[1]."\n";

}
else if (!isset($teams))
  $teams = 16;

$body = (is_file($online_dir . "$cc/$season/cal") || is_file($online_dir . "$cc/$season/gen")) ? '<p style="color:red">ВНИМАНИЕ: в настройках сезона уже есть файлы календаря и генераторов чемпионата!<br />
  Во избежание случайной потери данных замените их содержимое на страницах "Заливка / редактирование календаря (генераторов) чемпионата" в разделе "Прочее"!</p>' : '';

$body .= '<form name="dform" method="POST">
<table>
<tr>';
$cols = 77;
if ($groups > 1) {$body .= '<th>корзина 1 (высшая лига)</th><th>корзина 2 (первый дивизион)</th>'; $cols = 49;}
if ($groups > 2) {$body .= '<th>корзина 3 (второй дивизион)</th>'; $cols = 40;}
$body .= '</tr>
<tr><td'.($groups == 1 ? ' colspan="2"' : '').'><textarea name=basket1 rows='.$teams.' cols='.$cols.'>'.$basket1.'</textarea></td>';
if ($groups > 1) $body .= '<td><textarea name=basket2 rows='.$teams.' cols='.$cols.'>'.$basket2.'</textarea></td>';
if ($groups > 2) $body .= '<td><textarea name=basket3 rows='.$teams.' cols=40>'.$basket3.'</textarea></td>';
$body .= '</tr>
<tr><td><input type=submit name=draw value="жеребьевка и построение генераторов"></td><td>код федерации: <input type=text name=cc value="'.$cc.'" size=3> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;злые генераторы: <input name=angry type=checkbox value=1></td></tr>
</table>
</form>
';
if (!$_POST['basket1'])
  $body .= 'Для жеребьевки укажите список команд по одной в строке в 1-й, 2-х или 3-х корзинах (для чемпионатов с 2-мя или 3-мя лигами).<br />
Отметка "злые генераторы" несколько увеличит шанс появления "единиц" в генераторах: 40:30:30 вместо равновероятного.
';
else {
  $out = '';
  $i1 = $i2 = $i3 = 0;
  $comm1 = explode("\n", trim($basket1));
  shuffle($comm1);
  $nc = sizeof($comm1);
  $tours = 2 * ($nc -  1);
  $cal = file('cal.' . $nc);
  $mt = $nc / 2;
  if (trim($basket2)) {
    $comm2 = explode("\n", trim($basket2));
    shuffle($comm2);
  }
  if (trim($basket3)) {
    $comm3 = explode("\n", trim($basket3));
    shuffle($comm3);
  }
  for ($t=1; $t<=$tours; $t++) {
    $out .= " Тур $t        $cc".sprintf('%02d',$t)."\r\n";
    for ($i=0; $i<$mt; $i++) {
      $ta = explode(',', trim($cal[$i1++]));
      $out .= trim($comm1[--$ta[0]]).' - '.trim($comm1[--$ta[1]])."\r\n";
    }
    if (isset($comm2)) for ($i=0; $i<$mt; $i++) {
      $ta = explode(',', trim($cal[$i2++]));
      $out .= trim($comm2[--$ta[0]]).' - '.trim($comm2[--$ta[1]])."\r\n";
    }
    if (isset($comm3)) for ($i=0; $i<$mt; $i++) {
      $ta = explode(',', trim($cal[$i3++]));
      $out .= trim($comm3[--$ta[0]]).' - '.trim($comm3[--$ta[1]])."\r\n";
    }
    $out .= "\r\n";
  }
  if (!is_file($online_dir . "$cc/$season/cal")) {
    file_put_contents($online_dir . "$cc/$season/cal", $out);
    $generated = 'Файлы cal и gen созданы, посмотреть их можно на странице сезона в "Прочее". Теперь можно строить программки'; 
  } else $generated = '';
  $calend = "
<textarea rows=16 cols=44>$out</textarea>";

  if (isset($comm3)) { $genpt = 30; $league = 3; }
  else if (isset($comm2)) { $genpt = 20; $league = 2; }
  else { $genpt = 10; $league = 1; }

  $factor = $_POST['angry'] ? 1 : 0;
  $spacer = '      ';
  $out = "\r\n";

  for ($t=1; $t<=$tours; $t++) {
    $out .= $cc.sprintf('%02d',$t)."\r\n\r\n";
    for ($g=1; $g<=$genpt; $g++) {
      $pods = rand(1,10);
      for ($i=1; $i<=10; $i++) {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out .= $p;
        if ($i == $pods) {
          $f1 = ($p == 1) ? 1 : $factor;
          $pods = ceil(rand($factor, 6) / 3);
          if ($pods == 0) $pods++;
          if ($pods == $p) $pods = 'X';
          $out .= "($pods)";
          $pods = 0;
        }
      }
      $out .= ' ';
      for ($i=1; $i<=5; $i++) {
        $p = ceil(rand($factor, 9) / 3);
        if ($p == 0) $p++;
        if ($p == 3) $p = 'X';
        $out .= $p;
      }
      $out .= ($g % $league) ? " *$spacer" : " *\r\n";
    }
    $out .= "\r\n";
  }
  if (!is_file($online_dir . "$cc/$season/gen"))
    file_put_contents($online_dir . "$cc/$season/gen", $out);
  $cols = $groups * 27;
  $gen = "
<textarea rows=16 cols=$cols>
$out</textarea>";
}
$body .= "<table><tr><td>$calend</td><td>$gen</td></tr></table>
<br>$generated";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>FPrognoz.org Draw Tool (Championsip)</title>
<link rel="StyleSheet" href="css/fpo.css" type="text/css">
</head>
<body>
<center>
<?php echo $body; ?>
</center>
</body>
</html>
