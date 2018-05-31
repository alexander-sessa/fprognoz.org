#!/usr/bin/php
<?php
mb_internal_encoding('UTF-8');
$teams = array();
$codes = file('codes.tsv');
foreach ($codes as $team) if ($team[0] != '#' && $team[0] != '-')
{
  $ta = explode('	', $team);
  $teams[$ta[1]]['coach'][0] = $ta[2];
}
$cal = file_get_contents('calc');
if (mb_detect_encoding($cal, 'UTF-8', true) === FALSE)
  $cal = iconv('CP1251', 'UTF-8//IGNORE', $cal);

$cal = str_replace("\r", '', $cal);
$tours = explode("\n\n", $cal);
$tn = 1;
foreach ($tours as $tour) if (trim($tour))
{
  $matches = explode("\n", $tour);
  unset($matches[0]);
  $mn = 1;
  foreach ($matches as $match)
  {
    $cut = strpos($match, ' - ');
    $home = substr($match, 0, $cut);
    $match = substr($match, $cut + 3);
    $cut = strpos($match, '  ');
    $away = substr($match, 0, $cut);
    if (strpos($match, ':'))
    {
      $match = substr($match, $cut + 2);
      $cut = strpos($match, '  ');
      $teams[$home]['rez'][$tn] = substr($match, 0, $cut);
      $ta = explode(':', trim(substr($match, $cut + 2), ' ()'));
      $teams[$home]['coach'][$tn] = $ta[0];
      $teams[$away]['coach'][$tn] = $ta[2];
    }
    else
    {
      $teams[$home]['coach'][$tn] = $teams[$home]['coach'][0];
      $teams[$away]['coach'][$tn] = $teams[$away]['coach'][0];
    }
    $teams[$home]['oppo'][$tn] = $away;
    $teams[$away]['oppo'][$tn] = $home;
    if ($tn % 2)
    {
      $teams[$home]['1leg'][$tn] = 1;
      $teams[$away]['1leg'][$tn] = 2;
      $finalHome = $home;
      $finalAway = $away;
    }
    else
    {
      $teams[$home]['2leg'][$tn] = 1;
      $teams[$away]['2leg'][$tn] = 2;
    }
    $mn++;
  }
  $tn++;
}
$tn--;
//var_dump($teams);
$sort = array();
$tnFinal = $tn;
while ($tn > 0)
{
  foreach ($teams as $team => $ta)
  {
    if ($tn == $tnFinal && ($team == $finalHome || $team == $finalAway))
        $sort[$team][$tn] = $teams[$team]['1leg'][$tn]; // это для финала только

    if ($tn > 1 && isset($teams[$team]['1leg'][$tn]) && $teams[$team]['1leg'][$tn])
    {
      if (isset($teams[$team]['oppo'][$tn-2]))
      {
        $oppo = $teams[$team]['oppo'][$tn-2];
        $sort[$oppo][$tn-2] = $sort[$team][$tn] * 10;
        if (isset($teams[$oppo]['1leg'][$tn-2]))
          $sort[$oppo][$tn-2] += $teams[$oppo]['1leg'][$tn-2];

        $sort[$team][$tn-2] = $sort[$team][$tn] * 10 + $teams[$team]['1leg'][$tn-2];
      }
      else
        $sort[$team][$tn-2] = 9999999999;
    }
  }
  $tn -= 2;
}
foreach($sort as $team => $ta)
  foreach($ta as $tn => $key)
    $sort[$tn][$key] = $team;

for($i=1; $i<=$tnFinal; $i+=2)
  ksort($sort[$i]);

$lines = pow(2, 2 + ceil($tnFinal/2)) + 1;
$line = array();
$line[0] = '<p class="title text15b">&nbsp;&nbsp;&nbsp;Сетка кубка</p>';
$line[1] = '<pre>';
for ($i=2; $i<=$lines; $i++)
{
  $line[$i] = '';
  for ($tn=1; $tn<=$tnFinal; $tn+=2)
  {
     switch (($i + pow(2, ceil($tn/2))) % pow(2, 1 + ceil($tn/2)))
     {
       case 0:
         $team = current($sort[$tn]);
         $line[$i] .= $team . str_repeat(' ', 17 - mb_strlen($team));
         if ($teams[$team]['1leg'][$tn] == 1)
           $line[$i] .= sprintf('%-4s  ', $teams[$team]['rez'][$tn]);
         else
         {
           if (isset($teams[$team]['rez'][$tn + 1]))
             $line[$i] .= sprintf('%-4s│ ', $teams[$team]['rez'][$tn + 1]);
           else
             $line[$i] .= '    │ ';
         }
         break;
       case 1:
         $team = current($sort[$tn]);
         if ($teams[$team]['1leg'][$tn] == 1)
           $line[$i] .= '─────────────────────┐ ';
         else
           $line[$i] .= '─────────────────────┘ ';
         break;
       case 2:
         $team = current($sort[$tn]);
         if ($teams[$team]['coach'][$tn] == '*')
           $teams[$team]['coach'][$tn] = $teams[$team]['coach'][$tn + 1];
         $line[$i] .= $teams[$team]['coach'][$tn]
                    . str_repeat(' ', 21 - mb_strlen($teams[$team]['coach'][$tn]));
         if ($teams[$team]['1leg'][$tn] == 1)
           $line[$i] .= '│ ';
         else
           $line[$i] .= '  ';
         break;
       case 3:
         $team = current($sort[$tn]);
         if (!next($sort[$tn]))
           reset($sort[$tn]);
         if ($teams[$team]['1leg'][$tn] == 1)
         {
           if (($i + pow(2, 1 + ceil($tn/2))) % pow(2, 2 + ceil($tn/2)) == 1)
             $line[$i] .= '                     ├─';
           else
             $line[$i] .= '                     │ ';
         }
         else
           $line[$i] .= '                       ';
         break;
       default:
         $team = current($sort[$tn]);
         if ($teams[$team]['1leg'][$tn] == 1)
           $line[$i] .= '                       ';
         else
         {
           if (($i + pow(2, 1 + ceil($tn/2))) % pow(2, 2 + ceil($tn/2)) == 1)
             $line[$i] .= '                     ├─';
           else
             $line[$i] .= '                     │ ';
         }
         break;
     }
  }
}
$line[$i] = '</pre>';
foreach($line as $out) echo rtrim($out)."\n";
/*  %4+2            %8+4             %16+8         %32+16
           $line[$i] .= '                      ├─';
       24
    18     4
00  
11  
22 team1  rez1
33 line1
04 coach1        team21  rez21
15               line21
26 team2  rez2   coach21
37 line2
00 coach2                        team31  res31
11                               line31
22 team3  rez3                   coach31
33 line3
04 coach3        team22  rez22
15               line22
26 team4  rez4   coach22
37 line4
00 coach4                                        team41  res4
11                                               line41
22 team1  rez1                                   coach41
33 line1
04 coach1        team21  rez21
15               line21
26 team2  rez2   coach21
37 line2
00 coach2                        team31  res31
11                               line31
22 team3  rez3                   coach31
33 line3
44 coach3        team22  rez22
55               line22
66 team4  rez4   coach22
77 line4
00 coach4                                                       team51  res51 (winner)
11                                                              line51
22 team1  rez1                                                  coach51
33 line1
44 coach1        team21  rez21
55               line21
66 team2  rez2   coach21
77 line2
11 coach2                        team31  res31
22                              line31
00 team3  rez3                   coach31
33 line3
44 coach3        team22  rez22
55               line22
66 team4  rez4   coach22
77 line4
00 coach4                                        team42  res42
11                                               line42
22 team1  rez1                                   coach42
33 line1
44 coach1        team21  rez21
55               line21
66 team2  rez2   coach21
77 line2
00 coach2                        team31  res31
11                               line31
22 team3  rez3                   coach31
33 line3
44 coach3        team22  rez22
55               line22
66 team4  rez4   coach22
77 line4
00 coach4
11                                                                                 winner
*/
?>