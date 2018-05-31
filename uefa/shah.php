#!/usr/bin/php
<?php
mb_internal_encoding('UTF-8');
$nameln = 14; // фиксированная максимальная длина имени команды
$out = '';
$atourn = array('CHAML', 'GOLDL', 'CUPSL', 'UEFAL');
//$atourn = array('CHAML');
//$atourn = array('GOLDL');
//$atourn = array('CUPSL');
//$atourn = array('UEFAL');

function group_table($acal, $ateams, $group, $nameln)
{
  $upper = array('┌','─','┬','─','┐');;
  $rowln = array('│','▒','│',' ','│');;
  $midle = array('├','─','┼','╨','┤');;
  $lower = array('└','─','┴','─','┘');;
  $rez = array();
  $gen = array();
  $cal = $acal[$group];
  $teams = $ateams[$group];
  $teamt = array();
  $nteams = sizeof($teams);
  $nmatches = sizeof($cal);
  foreach ($teams as $team => $i)
  {
    $teamt[$team]['p'] = 0;
    $teamt[$team]['w'] = 0;
    $teamt[$team]['d'] = 0;
    $teamt[$team]['l'] = 0;
    $teamt[$team]['g'] = 0;
    $teamt[$team]['s'] = 0;
  }
    for ($i=0; $i<$nmatches; $i++)
    {
      $split1 = strpos($cal[$i], ' - ');
      $split2 = strpos($cal[$i], '  ', $split1 + 3);
      $split3 = strpos($cal[$i], '  ', $split2 + 3);
      $ho = trim(substr($cal[$i], 0, $split1));
      $go = trim(substr($cal[$i], $split1 + 3, $split2 - $split1 - 3));
      if ($re = trim(substr($cal[$i], $split2, $split3 - $split2)))
      {
        $ge = trim(substr($cal[$i], $split3));
        $rez[$ho][$go] = $re;
        $gen[$ho][$go] = $ge;
        /* для однокругового турнира раскомментировать
        $atemp = explode(':', $re);
        $rez[$go][$ho] = $atemp[1].':'.$atemp[0];
        $gen[$go][$ho] = $ge;
        */
        /* СОРТИРОВКА СПИСКА КОМАНД
        Более высокое место занимает команда, набpавшее большее количество очков.
                                     (вес 10000000)
        Пpи pавном кол-ве очков более высокое место полyчает та команда, y котоpой:
        - лyчше pазница мячей;         (вес 100000)
        - большее кол-во забитых мячей;  (вес 1000)
        - большее кол-во yгаданных исходов; (вес 1)
        */
        $atemp = explode(':', $re);
        if ($atemp[0] > $atemp[1])
        {
          $teams[$ho] += 30000000;
          $teamt[$ho]['p'] += 3;
          $teamt[$ho]['w'] ++;
          $teamt[$go]['l'] ++;
        }
        elseif ($atemp[0] < $atemp[1])
        {
          $teams[$go] += 30000000;
          $teamt[$go]['p'] += 3;
          $teamt[$go]['w'] ++;
          $teamt[$ho]['l'] ++;
        }
        else {
          $teams[$ho] += 10000000;
          $teams[$go] += 10000000;
          $teamt[$ho]['p']++;
          $teamt[$go]['p']++;
          $teamt[$ho]['d']++;
          $teamt[$go]['d']++;
        }
        $teamt[$ho]['g'] += $atemp[0];
        $teamt[$go]['g'] += $atemp[1];
        $teamt[$ho]['s'] += $atemp[1];
        $teamt[$go]['s'] += $atemp[0];
        $diff = ($atemp[0] - $atemp[1]) * 100000;
        $teams[$ho] += $diff;
        $teams[$go] -= $diff;
        $teams[$ho] += $atemp[0] * 1000;
        $teams[$go] += $atemp[1] * 1000;
        $atemp = explode(':', $ge);
        $teams[$ho] += $atemp[1];
        $teams[$go] += rtrim($atemp[3], ')');
      }
    }
    $team = array();
    arsort($teams);
    reset($teams);
    foreach($teams as $name => $sortpoints)
      $team[] = $name;

    $out =  ' Группа ' . ($group + 1) . ':' . str_repeat(' ', $nameln - 7);
    for ($j=1; $j<=$nteams; $j++)
      $out .= sprintf("%3d ", $j);

    $out .= "   В  Н  П   М   О\n";

    $out .= str_repeat(' ', $nameln + 3) . $upper[0];
    for ($j=1; $j<$nteams; $j++)
      $out .= $upper[1] . $upper[1] . $upper[1] . $upper[2];

    $out .= $upper[1] . $upper[1] . $upper[1] . $upper[4]."\n";
    for ($h=0; $h<$nteams; $h++)
    {
      $n = $h + 1;
      $line1 = sprintf("%2s", $n) . '.' . mb_substr($team[$h], 0, $nameln)
             . str_repeat(' ', $nameln - mb_strlen(mb_substr($team[$h], 0, $nameln)));
      $line2 = str_repeat(' ', $nameln + 3);
      for ($g=0; $g<$nteams; $g++)
      {
        $line1 .= $rowln[0];
        if ($g == 0 && $h < ($nteams - 1))
          $line2 .= $midle[0];

        if ($g > 0 && $h < ($nteams - 1))
          $line2 .= $midle[2];

        if ($g == 0 && $h == ($nteams - 1))
          $line2 .= $lower[0];

        if ($g > 0 && $h == ($nteams - 1))
          $line2 .= $lower[2];

        if ($h == $g)
        {
          $line1 .= str_repeat($rowln[1], 3);
          $line2 .= str_repeat($midle[1], 3);
        }
        else
        {
          if (isset($rez[$team[$h]][$team[$g]]))
          {
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
          else
          {
            $line1 .= '   ';
            $line2 .= str_repeat($midle[1], 3);
          }
        }
      }
      $line1 .= $rowln[4];
      if ($h < ($nteams - 1))
        $line2 .= $midle[4];
      else
        $line2 .= $lower[4];

      $out .= $line1.'  '.(0 + $teamt[$team[$h]]['w']).'  '.(0 + $teamt[$team[$h]]['d']).'  '.(0 + $teamt[$team[$h]]['l']).'  '.(0 + $teamt[$team[$h]]['g']).'-'.(0 + $teamt[$team[$h]]['s']).' '.sprintf('%2s', 0 + $teamt[$team[$h]]['p'])."\n";
      $out .= $line2."\n";
    }
    $out .= "\n";
    return $out;
}

foreach ($atourn as $tcode)
{
  $out = '';
  $ccal = file_get_contents("/home/fp/data/online/UEFA/2017-18/$tcode/calc");
  $fr = 0;
  $ateams = array();
  $acal = array();
  $longest = 0;
  while ($fr = strpos($ccal, $tcode, $fr))
  {
    $fr = strpos($ccal, "\n", $fr) + 1;
    $to = strpos($ccal, "\n\n", $fr);
    $tour = substr($ccal, $fr, $to - $fr);
    $matches = explode("\n", $tour);
    if (sizeof($matches) >= 8)
    {
      $ngroups = sizeof($matches) / 2;
      if (!sizeof($ateams)) for ($i=0; $i<$ngroups; $i++)
      { // формирование составов групп по первому туру с числом матчей >= 8
        $line = $matches[2 * $i];
        $line = substr($line, 0, strpos($line, '  '));
        $team = substr($line, 0, strpos($line, ' - '));
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
        $team = substr($line, strpos($line, ' - ') + 3);
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));

        $line = $matches[2 * $i + 1];
        $line = substr($line, 0, strpos($line, '  '));
        $team = substr($line, 0, strpos($line, ' - '));
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
        $team = substr($line, strpos($line, ' - ') + 3);
        $ateams[$i][$team] = 0;
        $longest = max($longest, mb_strlen($team));
      }
      for ($i=0; $i<$ngroups; $i++)
      { // формирование календарей групп
        $acal[$i][] = $matches[2 * $i];
        $acal[$i][] = $matches[2 * $i + 1];
      }
    }
  }
  if (!isset($nameln))
    $nameln = $longest;

  for ($group=0; $group<$ngroups; $group++) 
    $out .= group_table($acal, $ateams, $group, $nameln);

  file_put_contents('shah.'.$tcode, $out);
}
// echo $out;
?>
