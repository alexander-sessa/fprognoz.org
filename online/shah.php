#!/usr/bin/php
<?php
/**********************************************************************/
/* Скрипт построения шахматки для футбол-прогноза.                    */
/* Вызывается с необязательными параметрами - количеством лиг (по     */
/* умолчанию = 1) и именем (можно с путем) конфигурационного файла    */
/* (по умолчанию -fp.cfg или FP.CFG). Из этого файла читаются имена   */
/* файлов кодов команд (codes.tsv) и календаря (cal).                 */
/* При построении шахматки выполняется сортировка с учетом турнирного */
/* положения.                                                         */
/* Результат выводится в файл shah в текущем каталоге.                */
/* Для турниров с большим числом команд предусмотрено укорачивание    */
/* имен команд в шахматке - для этого надо указать переменную $nameln */
/**********************************************************************/

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
$nameln = 14; // фиксированная максимальная длина имени команды

$upper = array('┌','─','┬','─','┐');
$rowln = array('│','▓','│',' ','│');
$midle = array('├','─','┼','╨','┤');
$lower = array('└','─','┴','─','┘');

$leagues = 1;
$WorkDir = '';
for ($i=1; $i<$argc; $i++)
  if ($argv[$i] == 2)
    $leagues = $argv[$i];
  else if (is_file(trim($argv[$i]))) {
    $WorkDir = trim($argv[$i]);
    $Config = file($WorkDir);
  }

if (strpos($WorkDir,'/')) $cuthere = strpos($WorkDir,'/') + 1;
elseif (strpos($WorkDir,"\\")) $cuthere = strpos($WorkDir,"\\") + 1;
if (isset($cuthere) && $cuthere > 0)
  $WorkDir = substr($WorkDir, 0, $cuthere);

if (!isset($Config))
  if (is_file('fp.cfg')) $Config = file('fp.cfg');
  elseif (is_file('FP.CFG')) $Config = file('FP.CFG');
  else die('Нет конфигурационного файла "fp.cfg".
');

$Codes = trim($Config[1]);
if (is_file($WorkDir.$Codes)) $atemp = file($WorkDir.$Codes);
elseif (is_file($WorkDir.strtoupper($Codes))) $atemp = file($WorkDir.strtoupper($Codes));
elseif (is_file($WorkDir.strtolower($Codes))) $atemp = file($WorkDir.strtolower($Codes));
elseif (is_file($WorkDir.ucfirst($Codes))) $atemp = file($WorkDir.ucfirst($Codes));
else die('Нет файла кодов команд "'.$WorkDir.$Codes.'"
');

$longest = 0;
$teams = array();
$tnames = array();
foreach ($atemp as $line)
  if (($line = trim($line)) && ($line[0] != '#')) {
    $atemp1 = explode('	', $line);
    $line = $atemp1[1];
    $teams[$line] = 0;
    $tnames[0][] = $line;
    $longest = max($longest, mb_strlen($line));
  }
$nteams = sizeof($teams);
for ($i=0; $i<$nteams/$leagues; $i++)
  $tnames[1][] = $tnames[0][$i];
for ($i=$i; $i<$nteams; $i++)
  $tnames[2][] = $tnames[0][$i];

if (!isset($nameln))
  $nameln = $longest;

$Cal = trim($Config[2]);
if (is_file($WorkDir.$Cal)) $atemp = file($WorkDir.$Cal);
elseif (is_file(strtoupper($WorkDir.$Cal))) $atemp = file(strtoupper($WorkDir.$Cal));
elseif (is_file(strtolower($WorkDir.$Cal))) $atemp = file(strtolower($WorkDir.$Cal));
elseif (is_file(ucfirst($WorkDir.$Cal))) $atemp = file(ucfirst($WorkDir.$Cal));
else die('Нет файла календаря "'.$WorkDir.$Cal.'"
');

sort($atemp);
$cal = array();
foreach ($atemp as $line)
  if (strpos($line, ' - '))
    $cal[] = trim($line);
$matches = sizeof($cal);

for ($i=0; $i<$matches; $i++) {
  $split1 = strpos($cal[$i], ' - ');
  $split2 = strpos($cal[$i], '  ', $split1 + 3);
  $split3 = strpos($cal[$i], '  ', $split2 + 3);
  $ho = trim(substr($cal[$i], 0, $split1));
  $go = trim(substr($cal[$i], $split1 + 3, $split2 - $split1 - 3));

  if (in_array($go, $tnames[0]) && $re = trim(substr($cal[$i], $split2, $split3 - $split2)))
  {
    if (in_array($go, $tnames[1])) $l = 0; else $l = 1;
    $ge = trim(substr($cal[$i], $split3));
    $rez[$ho][$go] = $re;
    $gen[$ho][$go] = $ge;

    /* СОРТИРОВКА СПИСКА КОМАНД
    Более высокое место занимает команда, набpавшее большее количество очков.
                                 (вес 10000000)
    Пpи pавном кол-ве очков более высокое место полyчает та команда, y котоpой:
    - лyчше pазница мячей;	   (вес 100000)
    - большее кол-во забитых мячей;  (вес 1000)
    - большее кол-во yгаданных исходов; (вес 1)
    */
    $atemp = explode(':', $re);
    if ($atemp[0] > $atemp[1])
      $teams[$l][$ho] += 30000000;
    elseif ($atemp[0] < $atemp[1])
      $teams[$l][$go] += 30000000;
    else {
      $teams[$l][$ho] += 10000000;
      $teams[$l][$go] += 10000000;
    }
    $diff = ($atemp[0] - $atemp[1]) * 100000;
    $teams[$l][$ho] += $diff;
    $teams[$l][$go] -= $diff;
    $teams[$l][$ho] += $atemp[0] * 1000;
    $teams[$l][$go] += $atemp[1] * 1000;
    $atemp = explode(':', $ge);
    $teams[$l][$ho] += $atemp[1];
    $teams[$l][$go] += rtrim($atemp[3], ')');
  }
}

$nteams = $nteams / $leagues;
for ($l=0; $l<$leagues; $l++) {
  $tteams = $teams[$l];
  arsort($tteams);
  reset($tteams);
  $team = array();
  foreach($tteams as $name => $sortpoints)
    $team[] = $name;

  $out =  str_repeat(' ', $nameln + 3);
  for ($s=1; $s<=$nteams; $s++)
    $out .= sprintf("%3s ", $s);
  $out .= "\n";

  $out .= str_repeat(' ', $nameln + 3).$upper[0];
  for ($s=1; $s<$nteams; $s++)
    $out .= $upper[1].$upper[1].$upper[1].$upper[2];
  $out .= $upper[1].$upper[1].$upper[1].$upper[4]."\n";

  $namef = '%-'.$nameln.'s';

  for ($h=0; $h<$nteams; $h++) {
    $n = $h + 1;
//    $line1 = sprintf("%2s", $n).'.'.sprintf("$namef", substr(iconv('CP1251', 'KOI8-R', $team[$h]), 0, $nameln));
    $line1 = sprintf("%2s", $n).'.'.mb_substr($team[$h], 0, $nameln).str_repeat(' ', $nameln - mb_strlen(mb_substr($team[$h], 0, $nameln)));
    $line2 = str_repeat(' ', $nameln + 3);
    for ($g=0; $g<$nteams; $g++) {
      $line1 .= $rowln[0];
      if ($g == 0 && $h < ($nteams - 1)) $line2 .= $midle[0];
      if ($g > 0 && $h < ($nteams - 1)) $line2 .= $midle[2];
      if ($g == 0 && $h == ($nteams - 1)) $line2 .= $lower[0];
      if ($g > 0 && $h == ($nteams - 1)) $line2 .= $lower[2];
      if ($h == $g) {
        $line1 .= str_repeat($rowln[1], 3);
        $line2 .= str_repeat($midle[1], 3);
      } else {
        if (isset($rez[$team[$h]][$team[$g]])) {
          $line1 .= $rez[$team[$h]][$team[$g]];
          if (strpos($gen[$team[$h]][$team[$g]],'(*:') !== false)
	    $line2 .= $midle[3].$midle[1];
          else
            $line2 .= $midle[1].$midle[1];
          if (strpos($gen[$team[$h]][$team[$g]],':*:'))
            $line2 .= $midle[3];
          else
            $line2 .= $midle[1];
        }
        else {
          $line1 .= '   ';
          $line2 .= str_repeat($midle[1], 3);
        }
      }
    }
    $line1 .= $rowln[4];
    if ($h < ($nteams - 1)) $line2 .= $midle[4];
    else $line2 .= $lower[4];
    $out .= $line1."\n";
    $out .= $line2."\n";
  }
  file_put_contents('shah'.$l, $out);
}

// echo $out;
?>
