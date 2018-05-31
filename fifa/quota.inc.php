    <p class="title text15b">&nbsp;&nbsp;&nbsp;Информация о квотах</p>
    <hr size="1" width="98%" />
<p>
Регламент определяет каждому участнику ФП квоту в 2 команды плюс еще 1 команда в "отечественном" чемпионате.<br />
Квота увеличивается за спортивные достижения (выигрыш чемпионата, золотой бутсы, кубка, еврокубка)
и за активное участие в работе ФП-ассоциаций (см. столбец "дополнительная квота").<br />
Игроки, у которых превышена квота (показаны на розовом фоне), чтобы не терять команду, могут устроиться в одну или несколько
ФП-ассоциаций в качестве пресс-атташе - писать обзоры туров или этапов турниров. Иначе придётся отказаться от одной из своих команд.<br />
У игроков, показанных на зелёном фоне, есть возможность взять больше команд - к их услугам страница <a href="/?m=vacancy">"Вакансии"</a>.<br />
Сколько именно команд Вам доступно, можно определить отняв от числа в колонке "квота" число в колонке "учёт в квоте".<br />
Игроки, показанные на желтом фоне, могут взять еще одну команду в одной из "отечественных" ассоциаций, при условии,
что "отечественной" команды у них еще нет.
</p>
<?php
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
$coach = array();
$table = array();
$lnames = array();
$stat = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0, 7=>0, 8=>0, 9=>0);
$maxteam = 0;
$maxcoach = 0;
$maxlname = 0;
foreach ($ccs as $country_code => $country_name) {
  $dir = scandir($online_dir . $country_code);
  $season = '';
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
      $season = $subdir;

  $acodes = file($online_dir . $country_code . '/' . $season . '/codes.tsv');
  foreach ($acodes as $scode) if (($scode[0] != '-') && ($scode[0] != '#') && ($scode = trim($scode))) {
    $ateams = explode('	', $scode);
    $t = $country_code.':'.$ateams[0];
    $n = $ateams[1];
    $teams[$t] = $n;
    $maxteam = max($maxteam, strlen($n));
    $c = $ateams[2];
    $coach[$c]['teams'][] = "$n ($country_code)";
    $maxcoach = max($maxcoach, strlen($c));
    $m = $ateams[3];
    if ($l = trim($ateams[4])) {
      $lnames[$n] = $l;
      $maxlname = max($maxlname, strlen($l));
    }
  }
}
$qb = file($online_dir . 'QUOTAS/qb');
foreach ($qb as $line) if ($line = trim($line)) {
  $ac = explode(',', $line);
  $c = $ac[0];
  unset($ac[0]);
  $coach[$c]['qc'] = sizeof($ac);
  foreach ($ac as $qb)
    $coach[$c]['qb'][] = $qb;

}
foreach ($coach as $c => $ac) if ($c = trim($c)) {
  $r0 = 0;
  $rN = 0;
  if (isset($ac['teams'])) foreach ($ac['teams'] as $n) {
    if (strpos($n, '(ESP)')
     || strpos($n, '(ENG)')
     || strpos($n, '(ITA)')
     || strpos($n, '(GER)')
     || strpos($n, '(NLD)')
     || strpos($n, '(PRT)')
     || strpos($n, '(FRA)')
     || strpos($n, '(SCO)'))
      $r0++;

    if (strpos($n, '(BLR)')
     || strpos($n, '(RUS)')
     || strpos($n, '(UKR)'))
      $rN++;

    if ($n == 'Hamburger SV (GER)') {
      $rN++;
      $r0--;
    }
  }
  $rL = ($rN > 1) ? 1 : 0;
  $stat[$r0 + $rN]++;
  $qc = (isset($ac['qc'])) ? 2 + $ac['qc'] : 2;
  $table[] = array('c' => $c, 'rn' => $r0 + $rN, 'rl' => $r0 + $rL, 'qc' => $qc);
}
echo '<table width="100%">
  <tr>
    <th><a href="?m=quota">игрок</a></th>
    <th>команды игрока</th>
    <th><a href="?m=quota&amp;sort=0">всего команд</a></th>
    <th><a href="?m=quota&amp;sort=1">учёт в квоте</a></th>
    <th><a href="?m=quota&amp;sort=q">квота игрока</a></th>
    <th>дополнительная квота</th>
  </tr>
';
$tmp = array();
if (!isset($_GET['sort'])) {
  $order = SORT_ASC;
  foreach($table as $ma)
    $tmp[] = $ma['c'];

}
else switch($_GET['sort']) {
  case '0': $order = SORT_DESC; foreach($table as $ma) $tmp[] = $ma['rn']; break;
  case '1': $order = SORT_DESC; foreach($table as $ma) $tmp[] = $ma['rl']; break;
  case 'q': $order = SORT_DESC; foreach($table as $ma) $tmp[] = $ma['qc']; break;
  default : $order = SORT_ASC; foreach($table as $ma) $tmp[] = $ma['c']; break;
}
array_multisort($tmp, $order, $table);
foreach ($table as $ac) if ($c = trim($ac['c'])) {
  $out = '    <td align="left">'.$c.'</td>
    <td>';
  if (isset($coach[$c]['teams']))
    foreach ($coach[$c]['teams'] as $n)
      $out .= $n.'<br />';

  $out .= '</td>
    <td align="center">'.$ac['rn'].'</td>
    <td align="center">'.$ac['rl'].'</td>
    <td align="center">'.$ac['qc'].'</td>
';
  $qb = "";
  if (isset($coach[$c]['qb']))
    foreach ($coach[$c]['qb'] as $q)
      $qb .= $q.'<br />';

  if (!$qb)
    $qb = '&nbsp;';

  $out .= '    <td>'.$qb.'</td>
';
  if ($ac['qc'] < $ac['rl'])
    $color = 'pink';
  else if ($ac['qc'] == $ac['rl'])
    $color = 'yellow';
  else
    $color = 'lightgreen';

  echo '  <tr bgcolor="'.$color.'">'.$out.'</tr>
';
}
echo '</table>
<p class="title text15b">Статистика по количеству команд у игроков:</p>
<table><tr><td>команд</td><td>игроков</td><td> % </td><td>&nbsp;</td></tr>
';
foreach ($stat as $n => $r)
  echo '<tr><td>'.$n.'</td><td>'.$r.'</td><td>'.round(100 * $r / sizeof($coach)).'</td><td>'.str_repeat('<img src="/images/redcard.gif" alt="" />', $r).'</td></tr>
';
?>
</table>
