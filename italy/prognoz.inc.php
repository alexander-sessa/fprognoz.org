<?php
// парсинг программки
function parse_program($program_file) {
  $program = file_get_contents($program_file);
  $program = str_replace(')-', ') - ', $program);
  $fr = strpos($program, ' 1.') - strlen($program);
  $fr = strrpos($program, "\n", $fr) + 1;
  $program = substr($program, $fr);
  $fr = strpos($program, 'Контрольный с');
  $matches = explode("\n", substr($program, 0, $fr));
  $program = substr($program, $fr);
  $fr = strpos($program, '.');
  $date = trim(substr($program, $fr - 2, 5));
  $time = ($fr1 = strpos($program, ':', $fr)) && ($fr1 - $fr < 50) ? trim(substr($program, $fr1 - 2, 5)) : '';
  return array($matches, $date, $time, $program);
}

function GetTourFromCalendar($tour, $cal) { // вырезание тура из календарей всех видов
  $tourn = ltrim(substr($tour, -2), 'C0');
  if (($fr = strpos($cal, $tour)) === false) $fr = strpos($cal, ' Тур ' . $tourn);
  if ($fr === false) return $fr;
  $fr = strpos($cal, "\n", $fr) + 1;
  if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '=')) $fr = strpos($cal, "\n", $fr) + 1;
  if ($to = strpos($cal, ' Тур', $fr)) return substr($cal, $fr, $to - $fr);
  return substr($cal, $fr);
}

function HighlightedScanLine($team, $prognoz, $rprognoz, $warn) {
  $highlighted = '';
  for ($i=0; $i<strlen($rprognoz); $i++)
    $highlighted .= ($i != 10 && $prognoz[$i] == $rprognoz[$i]) ?
                    '<span class="blue">'.$prognoz[$i].'</span>' : $prognoz[$i];

  return mb_sprintf('%-21s' . $highlighted . '  %-3s', $team, $warn);
}

if (!isset($l)) $l = '';
($cca == 'UEFA') ? $tour = $l . strtoupper($t) : $tour = $cca . strtoupper($t);
$season_dir = $online_dir . $cca . '/' . $s . '/';
$tour_dir = $season_dir . 'prognoz/' . $tour . '/';
$show_pen_col = false;
if ($cca == 'UEFA') {
  $cal_file = $season_dir . $l . '/calc';
  $gen_file = $season_dir . $l . '/genc';
  if ($t == 8 || $t == 10 || $t == 12 || $t == 13) $show_pen_col = true;
}
elseif ($tour[3] == 'C') {
  $cal_file = $season_dir . $l . '/calc';
  $gen_file = $season_dir . $l . '/genc';
  if ($tour[4] % 2 == 0 || $tour[4] >= 7 || strlen($tour) == 6 || $cca == 'SUI') $show_pen_col = true;
}
elseif ($tour[3] == 'S') {
  $cal_file = $season_dir . '/cals';
  $gen_file = $season_dir . '/gens';
  $show_pen_col = true;
}
elseif ($tour[3] == 'P') {
  $cal_file = $season_dir . '/calp';
  $gen_file = $season_dir . '/genp';
  if ($tour[4] == 2) $show_pen_col = true;
}
elseif ($tour[3] == 'G') {
  $cal_file = $season_dir . '/cal';
  $gen_file = $season_dir . '/gen';
  $show_pen_col = true;
}
else {
  $cal_file = $season_dir . '/cal';
  $gen_file = $season_dir . '/gen';
}
$rprognoz = '';
$team_code = isset($c) ? $c : '';
$stat = false;

$teams = array(); $coach = array(); $lnames = array();
$maxteam = $maxcoach = $maxlname = 0;
$acodes = file($season_dir . 'codes.tsv');
foreach ($acodes as $scode) if ($scode[0] != '#') {
  list($code, $tname, $cname, $email, $lname, $yes_no) = explode('	', ltrim($scode, '-'));
  $teams[$code] = $tname;
  $maxteam = max($maxteam, mb_strlen($tname));
  $coach[$tname] = $cname;
  $maxcoach = max($maxcoach, mb_strlen($cname));
  if (trim($lname)) {
    $lnames[$tname] = $lname;
    $maxlname = max($maxlname, mb_strlen($lname));
  }
}

list($program_matches, $lastdate, $lasttm, $program) = parse_program($season_dir . 'programs/' . $tour);
list($cal, $gen) = parse_cal_and_gen($program);
if (is_file($cal_file) && $calt = trim(GetTourFromCalendar(str_replace('NEW', '', $tour), file_get_contents($cal_file))))
  $cal = $calt; // если есть, используем календарь тура из файла календаря турнира

$virtmatch = array();
if (trim($cal)) {
  $atemp = explode("\n", $cal);
  $cal = '';
  foreach ($atemp as $line) if ($line = trim($line)) {
    if ($cut = strpos($line, '  ')) $line = substr($line, 0, $cut);
    $cal .= $line . "\n";
    $virtmatch[] = $line;
  }
}
$teamCodes = array();
if (isset($_SESSION['Coach_name'])) {
  // составление списка своих команд в этом туре
  foreach ($cmd_db[$cca] as $code => $team)
    if ($team['usr'] == $_SESSION['Coach_name'] && ($cca == 'SUI' || strpos($cal, $team['cmd']) !== false))
      $teamCodes[] = $code;

  // отправка прогноза за несколько или за одну команду
  if (isset($_POST['submitpredict']) && isset($_POST['team_code']) && ($prognoz = trim($_POST['prognoz_str']))) {
    if ($_POST['team_code'] == 'один прогноз всем')
      foreach ($teamCodes as $tc)
        send_predict($cca, $s, $tc, $tour, $prognoz, $_POST['enemy_str'], $ip);
    else {
      if (trim($_POST['team_code'])) $team_code = strtr($_POST['team_code'], ['<mark>' => '', '</mark>' => '']);
      elseif (!$team_code) $team_code = $teamCodes[0];
      send_predict($cca, $s, $team_code, $tour, $prognoz, $_POST['enemy_str'], $ip);
    }
  }
}
(sizeof($teamCodes) == 0) ? $closed = true : $closed = is_file($tour_dir . 'closed');

// президентские дополнения и поправки прогнозов
if (isset($_POST['addprognoz'])) {
  file_put_contents($tour_dir . 'adds', $_POST['addprognoz']);
  time_nanosleep(0, 5000);
  $new_itog = '';
  if (is_file($season_dir.'publish/'.($cca == 'UEFA' ? $l.'/itc' : 'it').strtolower($t))) {
    `/home/fp/fprognoz.org/online/build_results.php "$cca" "$s" "$t"`;
    $new_itog = ', итоги тура перестроены';
  }
  $hint = '<span class="red">дополнения сохранены'.$new_itog.'</span><br>
';
}
else $hint = '';
is_file($tour_dir . 'adds') ? $addfile = file_get_contents($tour_dir . 'adds') : $addfile = '';
if ($role == 'president') $hint .= '<a href="" onClick="$(\'#addsform\').toggle();return false;">дополнения и поправки прогнозов (команды, карточки/флаги, время)</a><br>
<form id="addsform" class="hidden" method="POST">
формат дополнений и поправок прогнозов (размер названия, отступы) такой же, как при публикации прогнозов;<br>
пропуск первого матча из-за красной карточки не помечайте - это делается автоматически при расчете итогов;<br>
указывайте среднеевропейское время - это поможет определить прогнозы, отправленные после начала матчей<br>
<textarea name=addprognoz class="monospace" rows="' . max(6, substr_count($addfile, "\n") + 1) . '" cols="67">'.htmlspecialchars($addfile).'</textarea><br>
<input type=submit name=submit value="сохранить">
</form>
';
$publish = is_file($tour_dir . 'published');
if ($publish || $role == 'president') $hint .= '<a href="" onClick="$(\'#chronology\').toggle(); return false;">показать/скрыть хронологию поступления прогнозов</a>';
$hint .= '<p id="chronology" class="hidden monospace">';

// выборка прогнозов из мейлбокса. $tour_dir,$publish,$teamCodes,$role,$teams[] -> $aprognoz[],$closed,$hint
$hidden = 'прогноз не показан';
$mbox = file($tour_dir . 'mail');
$have = array(); $aprognoz = array();
foreach ($mbox as $msg) {
  if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE) $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);
  list($team, $prog, $time, $pena) = explode(';', $msg);
  $prog = strtr($prog, ['x' => 'X', 'х' => 'X', 'Х' => 'X', '0' => 'X']);
  if ($publish && in_array($team, $teamCodes)) $closed = true;
  if (!$publish && !in_array($team, $teamCodes)) $prog = $hidden;
  if (!isset($teams[$team])) $warn = 'oЖ';
  else {
    $team = $teams[$team];
    if (in_array($team, $have)) $warn = '!!!';
    else { $have[] = $team; $warn = '  '; }
  }
  if (!isset($aprognoz[$team]['time']) || ($time > $aprognoz[$team]['time']))
    $aprognoz[$team] = array('prog' => $prog, 'time' => $time, 'pena' => $pena, 'warn' => $warn);

  if (($prog != $hidden) || ($role == 'president')) {
    $hint .= htmlspecialchars(mb_sprintf('%-21s%-20s%-5s', $team, $prog, $warn)) . date_tz('d M y  H:i:s', '', $time, $_COOKIE['TZ'] ?? 'Europe/Berlin') . "\n";
    if (($penalties = trim($pena)) && ($prog != $hidden)) $hint .= '                     ' . strtolower($penalties) . "\n";
  }
}
// дополнение выбранных прогнозов президентскими данными. $addfile,$hidden,$role $aprognoz[],$hint
if ($addfile) {
  $hint .= 'дополнения, поправки, наказания:
';
  $added = explode("\n", $addfile);
  foreach ($added as $line) if ($line = rtrim($line)) {
    if ($line[0] == ' ') { // строка пенальти начинается с пробела
      $aprognoz[$team]['pena'] = $line; // $team определена в предыдущей строке
      if ($prognoz != $hidden) $hint .= htmlspecialchars($line) . "\n";
    }
    else {
      $team = trim(mb_substr($line, 0, 20));
      $line = trim(mb_substr($line, 20));
      if ($cut = min(21, strpos($line, ' ', 15))) {
        $prognoz = trim(substr($line, 0, $cut));
        $line = trim(substr($line, $cut));
      }
      else {
        $prognoz = trim($line);
        $line = '';
      }
      if (!$publish && ($team != $teams[$team_code])) $prognoz = $hidden;
      if (isset($line[0]) && is_numeric($line[0])) $warn = '     ';
      else {
        $wln = (mb_strlen($line) > 4 && $line[4] == ' ') ? 5 : 4;
        $warn = strtr(mb_substr($line, 0, $wln), array('K' => 'К', 'а' => 'a', 'о' => 'o', 'с' => 'c'));
        $line = mb_substr($line, $wln);
      }
      if ($time = trim(substr($line, 0, 29))) $time = strtotime($time);
      $aprognoz[$team] = array('time' => $time, 'prog' => $prognoz, 'pena' => '', 'warn' => $warn);
      ($time) ? $date = date_tz('d M y  H:i:s', '', $time, $_COOKIE['TZ'] ?? 'Europe/Berlin') : $date = '';
      if (($prognoz != $hidden) || ($role == 'president'))
        $hint .= htmlspecialchars(mb_sprintf('%-21s%-20s%-5s', $team, $prognoz, $warn)) . $date . "\n";
    }
  }
}
$hint .= '</p>

';
// заголовок страницы с формой отправки прогноза. $closed,$teamCodes,$tour -> $head
$head = '';
if (!$closed && sizeof($teamCodes))
  $head .= '<form action="" name="tform" enctype="multipart/form-data" method="POST" onSubmit="return show_alert(this);">';

$head .= 'Код тура: <b>' . $tour . '</b>';
if (!$closed && sizeof($teamCodes)) {
  $head .= ', код команды: ';
  if (sizeof($teamCodes) == 1) $head .= '<input type="hidden" name="team_code" value="'.$teamCodes[0].'"><b>'.$teamCodes[0].'</b><br>';
  else {
    $head .= '<select name="team_code">';
    foreach ($teamCodes as $tc) {
      $head .= '<option value="'.$tc.'"';
      $selected = '';
      (isset($c) && $c == $tc) ? $selected = ' selected="selected"' : $c = $tc;
      $head .= $selected.'>'.$tc.'</option>';
    }
    $head .= '<option value="один прогноз всем">один прогноз всем</option></select><br>';
  }
  $head .= '<span class="small">прогноз на тур: <input type="text" id="prognoz_str" name="prognoz_str" value="" size="50">
<input type="hidden" name="enemy_str" value="">
<input type="submit" name="submitpredict" value=" отправить "></span></form>
<a href="?m=help#frm" class="small" target="_blank">как пользоваться формой отправки прогноза</a>
';
}

// таблица программки тура
require_once('online/tournament.inc.php');
include script_from_cache('online/realteam.inc.php');
list($last_day, $last_month) = explode('.', $lastdate);
$year = substr($s, 0, 4);
// для сезонов вида 2019-20, прибавляем год для программок со сроком в январе...августе
if ($last_month < 9 && strlen($s) > 6)
  $year++;

if (!isset($updates)) $updates = NULL;
$base = get_results_by_date($last_month, $last_day, $updates, $year);
$mdp = array();
$program_table = '<table class="table-condensed table-striped p-table">
<thead>
<tr><th>№</th><th>матч для прогнозирования</th><th>турнир</th><th>дата время</th><th>счёт</th><th>исход</th><th colspan="3">прогноз</th>';
if ($closed && $publish) $program_table .= '<th>угадано прогнозов</th>';
$program_table .= '</tr>
</thead>
<tbody>
';
$id_arr = $id_json = '';
$today_matches = 0;
$today_bz = 0;
foreach ($program_matches as $line) if ($line = trim($line)) { // rows
  if (strpos($line, ' - ')) {
    (strpos($line, '│') !== false) ? $divider = '│' : $divider = '|';
    $home = $away = '';
    $atemp = explode($divider, $line);
    if (sizeof($atemp) > 2 && $cut = strpos($atemp[2], ' - ')) {
      $nm = rtrim(trim($atemp[1]), '.');
      $dm = trim($atemp[3]);
      $home = trim(substr($atemp[2], 0, $cut));
      if ($cut1 = strrpos($home, '(')) $home = trim(substr($home, 0, $cut1));
      if ($ct = strrpos($home, '(')) $hfix = trim(substr($home, 0, $ct));
      else $hfix = $home;
      $away = trim(substr($atemp[2], $cut + 3));
      if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
      else $afix = $away;
      if (trim(substr($atemp[2], -3))) {
        $cut = strrpos($away, ' ');
        $tournament = substr($away, $cut + 1);
        $away = trim(substr($away, 0, strrpos($away, ' ')));
        if ($cut1 = strrpos($away, '(')) $away = trim(substr($away, 0, $cut1));
        if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
        else $afix = $away;
        $match = $realteam[$hfix].' - '.$realteam[$afix].'/'.$tourname[trim($tournament)];;
        if (!trim($tournament) || !isset($base[$match])) $match = $realteam[$hfix].' - '.$realteam[$afix];
      }
      else {
        $tournament = '&nbsp;';
        $match = $realteam[$hfix].' - '.$realteam[$afix];
      }
      $mt = '-:-'; $rt = '?'; $tn = '19:00'; // по умолчанию информации о счёте и времени матча нет
      if (isset($base[$match])) {
        list($match_date, $match_time) = explode(' ', $base[$match][2]);
        list($match_month, $match_day) = explode('-', $match_date);
        // если дата отправки и матча совпадают, матч учитывается только если указано время отправки меньше 20:00.
        // для этого число "день матча" при проверке увеличиваетс на 1, чтобы срабатывало условие > $last_date
        ($lasttm && ($lasttm[0] != '2')) ? $d1 = 1 : $d1 = 0;
        // первое условие - для новогодних туров
        if (($last_month == 12 && $match_month == 1) || ($match_month . $match_day . $d1) > ($last_month . $last_day . '0')) {
          // грубая проверка на максимальный срок переноса матча (все месяцы считаются по 31 дню)
          list($prog_day, $prog_month) = explode('.', $dm);
          if (($prog_month == 12) && ($match_month == 1)) $prog_month = 0;
          if (($prog_month * 31 + $prog_day + 7) < ($match_month * 31 + $match_day)) $st = 'POS'; // перенос более, чем на 7 дней
          else { // матч надо учитывать
            $dm = $match_day . '.' . $match_month;
            $tn = $match_time;
            $st = $base[$match][3];
            $mt = $base[$match][5];
          }
          if (($st != '-') && (($st <= '90') || ($st == 'HT'))) {
            $today_matches++;
            if ($base[$match][6]) {
              $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"' . $mt . '","' . $st . '"];
';
              $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"' . $mt . '","' . $st . '"]}';
              $today_bz++;
            }
            $mt = '<span class="red">' . $mt . '</span>';
            ($st == 'HT') ? $rt = '<span class="red">' . $st . '</span>' : $rt = '<span class="blink">' . $st . '’</span>';
          }
          elseif (($st == 'CAN') || ($st == 'POS') || ($st == 'SUS')) {
            $mt = $st;
            $rt = '-';
          }
          elseif ($st == 'FT') {
            list($gh, $ga) = explode(':', $mt);
            if ($gh == $ga) $rt = 'X';
            else ($gh > $ga) ? $rt = '1' : $rt = '2';
            $stat = true;
          }
          elseif ($match_day == date('d', time())) {
            $today_matches++;
            if ($base[$match][6]) {
              $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"-:-","-"];
';
              $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"-:-","-"]}';
              $today_bz++;
            }
          }
        }
        $tr_id = $base[$match][6] ? ' id="' . $base[$match][6] . '"' : '';
      }
      else
      {
        $tr_id = '';
        $match_date = substr($dm, 3, 2).'-'.substr($dm, 0, 2);
      }
      $mdp[$nm] = array('home' => $home, 'away' => $away, 'trnr' => $tournament, 'date' => $dm, 'rslt' => $mt, 'case' => $rt);
      if ($nm == 11) {
        if ($closed) $colspan = 7;
        elseif ($role != 'badlogin') $colspan = 9;
        else $colspan = 6;
        $program_table .= '<tr><td colspan="' . $colspan . '" style="border: 1px solid darkgrey"></td></tr>
';
        $rprognoz .= ' ';
      }
      $program_table .= '<tr'.$tr_id.'><td class="tdn">'.$nm.'</td><td style="min-width:288px;'.($tr_id != '' ? 'cursor:pointer" onClick="details($(this).closest(\'tr\'))' : '').'">'.$home.' - '.$away.'</td><td>'.$tournament.'</td><td class="td1">&nbsp;'.date_tz('d.m H:i', $match_date, $tn, $_COOKIE['TZ'] ?? 'Europe/Berlin').'&nbsp;</td><td align="center">&nbsp;'.$mt.'&nbsp;</td><td align="center">&nbsp;'.$rt.'&nbsp;</td>';
//      if (!$closed && ($role != 'badlogin')) {
//        (($publish && $nm == 1) || $rt != '?') ? $onchange = 'disabled="disabled"' :  $onchange = 'onchange="newpredict(); return false;"';
      if ($rt == '1' || $rt == 'X' || $rt == '2') {
        $onchange = 'disabled="disabled" ';
        $val = $rt;
      }
      else {
        $onchange = 'onchange="newpredict();"';
        (isset($prognoz_str) && $prognoz_str) ? $val = $prognoz_str[$nm - 1] : $val = '';
      }
      $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm','X'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm','2'".'); return false;">2</a>
      <input type="text" name="dice' . $nm . '" value="'.$val.'" id="dice' . $nm . '" class="pr_str" '.$onchange.'>
    </td>
    <td>';
      if (!$closed && $role != 'badlogin' && $cca != 'SUI') {
        if ($nm < 11) $program_table .= '
      <a href="#" onclick="securedice('."'ddice$nm','1'".'); return false;">1</a>
      <a href="#" onclick="securedice('."'ddice$nm','X'".'); return false;">X</a>
      <a href="#" onclick="securedice('."'ddice$nm','2'".'); return false;">2</a>';
        else $program_table .= '
      <a href="#" onclick="securehome('."'ddice$nm'".'); return false;">&lt;</a>';
        $program_table .= '
      <input type="text" name="ddice' . $nm . '" value="" id="ddice' . $nm . '" class="pr_str" '.$onchange.'>
    </td>
    <td>
';
      }
      else $program_table .= '      <input type="hidden" name="ddice' . $nm . '" value="" id="ddice' . $nm . '">
    </td>
';
      if ($show_pen_col && !$closed && $role != 'badlogin')
      {
        if ($cca == 'SUI')
          $program_table .= '<td>';

        $program_table .= '      <a href="#" onclick="penalty('."'pen$nm','0'".'); return false;">&laquo;</a>п<a href="#" onclick="penalty('."'pen$nm','1'".'); return false;">&raquo;</a>
      <input type="text" name="pen' . $nm . '" value="" id="pen' . $nm . '" class="pr_str" '.$onchange.'>
    </td>
';
      }
      else $program_table .= '      <input type="hidden" name="pen' . $nm . '" value="" id="pen' . $nm . '">
    </td>
';
      if ($closed && $nm == 1) $program_table .= '
<td rowspan="' . (count($program_matches) + 1) . '">
<div style="-webkit-writing-mode: vertical-rl; writing-mode: tb-rl; height: 17em;">
&nbsp;для&nbsp;расчёта&nbsp;вариантов&nbsp;завершения<br>
&nbsp;матчей&nbsp;укажите&nbsp;в&nbsp;колонке&nbsp;"прогноз"<br>
&nbsp;возможные&nbsp;исходы&nbsp;и&nbsp;нажмите&nbsp;кнопку:<br>
</div>
<br><br>
<form action="" name="tform" method="get">
<input type="hidden" name="a" value="'.$a.'">
<input type="hidden" name="m" value="prognoz">
<input type="hidden" name="l" value="'.$l.'">
<input type="hidden" name="s" value="'.$s.'">
<input type="hidden" name="t" value="'.$t.'">
<input type="hidden" id="prognoz_str" name="prognoz_str" value="" size="50">
<input type="submit" value="если?">
</form>
</td>
';
      if ($publish) $program_table .= '<td><!--' . $nm . '--></td>'
;
      $program_table .= '</tr>
';
      if (strlen($rt) > 1) $rt = '?';
      $rprognoz .= $rt;
    }
  }
}
$program_table .= '</tbody>
</table>
';
if (!$closed)
  $hint = '<p class="red">Контрольный срок отправки прогнозов: '.date_tz($lasttm ? 'd.m H:i' : 'd.m', substr($lastdate, 3, 2).'-'.substr($lastdate, 0, 2), $lasttm, $_COOKIE['TZ'] ?? 'Europe/Berlin').'</p>' . $hint;

// теперь список прогнозов по парам виртуальных матчей
// use simulated results if any
if (isset($prognoz_str) && $prognoz_str) {
  for ($i=0; $i<strlen($prognoz_str); $i++) if ($prognoz_str[$i] != '*')
    $rprognoz[$i] = $prognoz_str[$i];
}
// выборка генераторов
$gensets = 1;
if (is_file($gen_file)) { /* parse generators */
  $gen = file_get_contents($gen_file);
  if (strpos($gen, $tour)) {
    $begin = $tour;
    $end = $cca;
  }
  else {
    $nt = ltrim(substr($tour, strlen($cca) + 1), '0');
    $begin = 'Тур ' . $nt;
    $end = 'Тур';
  }
  $fr = mb_strpos($gen, $begin);
  $fr = mb_strpos($gen, "\n", $fr) + 1;
  if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '=')) $fr = mb_strpos($gen, "\n", $fr) + 1;
  if ($to = mb_strpos($gen, $end, $fr)) $gen = trim(mb_substr($gen, $fr, $to - $fr));
  else $gen = trim(mb_substr($gen, $fr));
}
$generator = array();
if ($gen) {
  $gen = str_replace('*', '', $gen);
  $atemp = explode("\n", $gen);
  foreach ($atemp as $line) if ($line = trim($line)) {
    if ($cut = mb_strpos($line, '  ')) {
      $generator['1'][] = trim(mb_substr($line, 0, $cut));
      $line = trim(substr($line, $cut));
      if ($cut = mb_strpos($line, '  ')) {
        $generator['2'][] = trim(mb_substr($line, 0, $cut));
        $generator['3'][] = trim(mb_substr($line, $cut));
        $gensets = 3;
      }
      else {
        $generator['2'][] = trim($line);
        $gensets = 2;
      }
    }
    else $generator['1'][] = trim($line);
  }
}

// выдача виртуальных матчей делается в 2-х форматах: scanprog (со временем) и stat (с результатом)
$prognozlist = '';
if ($stat) {
  $cards = array();
  if (is_file($season_dir . $l . '/cardsc')) {
    $atemp = file($season_dir . $l . '/cardsc');
    foreach ($atemp as $line) if ($line = trim($line))
      $cards[trim(substr($line, 0, 20))] = str_split(substr($line, 20), 2);
  }
  $newcaltour = '';
  $sprognoz = array();
  $tplayers = sizeof($virtmatch) * 2;
  // формирование заголовка формата stat
  $rplayers = 0;
  foreach ($virtmatch as $line) {
    list($home, $away) = explode(' - ', $line);
    if ($aprognoz[$home]['warn'] && $aprognoz[$home]['warn'][0] != '*') $rplayers++;
    if ($aprognoz[$away]['warn'] && $aprognoz[$away]['warn'][0] != '*') $rplayers++;
  }
  $prognozlist .= 'Количество прогнозов - ' . $tplayers . '
Количество реальных игроков - ' . $rplayers . '
&nbsp;
Правильный прогноз   ' . $rprognoz . '

';
}
// формирование таблиц виртуальных матчей

$nm = $gs = 0;
$st = 1;
$z = sizeof($virtmatch) / $gensets;
$enemy = '';
$plrs = array();
for ($i=0; $i<strlen($rprognoz); $i++) $plrs[$i] = 0;
foreach ($virtmatch as $line) {
  list($home, $away) = explode(' - ', $line);
  if ($team_code) {
    if ($home == $teams[$team_code]) $enemy = $away;
    elseif ($away == $teams[$team_code]) $enemy = $home;
  }
  // хозяин
  if (!isset($aprognoz[$home]['prog'])) {  // выдача генератора
    ($publish) ? $aprognoz[$home]['prog'] = $generator[$st][$gs] : $aprognoz[$home]['prog'] = $hidden;
    $aprognoz[$home]['warn'] = '*Ж';
    $date = '                   ';
    $gs++;
  }
  else if (isset($aprognoz[$home]['time']) && $aprognoz[$home]['time'])
    $date = date_tz('d M y  H:i:s', '', $aprognoz[$home]['time'], $_COOKIE['TZ'] ?? 'Europe/Berlin');
  else
    $date = '                   ';

  if ($stat) {
    $bad = false;
    $podstr = '';
    $prognoz = trim(str_replace('<', '', $aprognoz[$home]['prog'])); // гостевой заменитель просто удаляется
    if ($prognoz[0] == '(') { // доп. прогноз перед первым
      $prognoz = substr($prognoz, 3);
      $bad = true;
    }
    $warn = mb_substr(trim($aprognoz[$home]['warn']), 0, 2);
    if ($ppos = strpos($prognoz, '(')) { // вырезали и запомнили доп. прогноз
      $podstr = $prognoz[$ppos+1];
      $prognoz = str_replace('(' . $podstr . ')', '', $prognoz);
    }
    else $ppos = 0;
    list($first10, $last5) = explode(' ', $prognoz, 2);
    if ($ln = 10 - strlen($first10)) { // меньше 10 прогнозов в осн. части
      $first10 .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0) { // больше 10 прогнозов в осн. части
      $first10 = substr($first10, 0, 10);
      $bad = true;
    }
    $last5 = str_replace(' ', '', $last5);
    $lastlen = strlen($rprognoz) - 11;
    if ($ln = $lastlen - strlen($last5)) { // меньше (5) прогнозов в доп. части
      $last5 .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0) { // больше (5) прогнозов в доп.части
      $last5 = substr($last5, 0, $lastlen);
      $bad = true;
    }
    $prognoz = $first10 . ' ' . $last5;
    if ($bad) {
      if (mb_strpos($warn, 'Ж')) $warn = 'aК';
      elseif (!mb_strpos($warn, 'К')) $warn = 'oЖ';
    }
    // TODO: здесь отслеживать карточки
    if (mb_strpos($warn, 'К')) {
      for ($j=0; $prognoz[$j] == '='; $j++);
      $prognoz[$j] = '=';
// если этот матч не состоялся, продублировать карточку на заменяющий
// при этом проследить случай замены несыгравшей форы
// а также проверить, чтобы заменяющий матч таки состоялся
      if ($rprognoz[$j] == '-') {
        if ($ppos == $j + 1) {
          $replacer = $replace;
          if ($rprognoz[$replacer] == '-') { // указанный гостями заменитель не играл, делаем обычную замену
            $replacer = 11;
            while (($rprognoz[$replacer] == '-') && ($replacer < strlen($rprognoz))) $replacer++; // поиск первого неиспользованного форо-заменителя
          }
          $rep = $replacer;
        }
        else {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < strlen($rprognoz))) $rep++; // поиск первого неиспользованного заменителя
        }
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж')) $warn = '  ';
    $aprognoz[$home]['warn'] = $warn;
    $prognozh = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*') $sprognoz[$home] = $prognozh;
    ($ppos && $podstr == $rprognoz[$ppos-1]) ? $line1 = sprintf('<span class="blue">%'.(21 + $ppos).'s</span>', $podstr)
                                             : $line1 = sprintf('%'.(21 + $ppos).'s', $podstr);
    $line2 = HighlightedScanLine($home, $prognoz, $rprognoz, $warn);
  }
  else { // формат scanprog
    $addline = htmlspecialchars(mb_sprintf('%-21s%-20s', $home, $aprognoz[$home]['prog']));
    ($publish || ($role == 'president')) ? $addline .= mb_sprintf('%-5s', $aprognoz[$home]['warn']) . $date . "\n" : $addline .= "                        \n";
    $prognozlist .= $addline;
  }

  if (!isset($aprognoz[$away]['prog'])) { // выдача генератора гостю
    ($publish) ? $aprognoz[$away]['prog'] = $generator[$st][$gs] : $aprognoz[$away]['prog'] = $hidden;
    $aprognoz[$away]['warn'] = '*Ж';
    $date = '                   ';
    $gs++;
  }
  else if (isset($aprognoz[$away]['time']) && $aprognoz[$away]['time'])
    $date = date_tz('d M y  H:i:s', '', $aprognoz[$away]['time'], $_COOKIE['TZ'] ?? 'Europe/Berlin');
  else
    $date = '                   ';

  $match = $home;
  if ($aprognoz[$home]['warn'] && $aprognoz[$home]['warn'][0] == '*') $match .= '(*)';
  $match .= ' - '.$away;
  if ($aprognoz[$away]['warn'] && $aprognoz[$away]['warn'][0] == '*') $match .= '(*)';
  $bomb[$match][0]['min'] = 0; // заплата для сухих матчей
//    if ($prognozr[$ppos - 1] == '-') $usereplace = true; else $usereplace = false;
  // гость
  if ($stat) {
    $bad = false;
    $prognoz = $aprognoz[$away]['prog'];
    if ($prognoz[0] == '(') $prognoz = substr($prognoz, 3);
    $warn = mb_substr(trim($aprognoz[$away]['warn']), 0, 2);
    if ($fr = strpos($prognoz, '(')) { // доп. прогноз у гостя просто вырезается
      $fake = $prognoz[$fr+1];
      $prognoz = str_replace('(' . $fake . ')', '', $prognoz);
    }
    list($first10, $last5) = explode(' ', $prognoz, 2);
    $ln = 10 - strlen($first10);
    if ($ln > 0) {
      $first10 .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0) {
      $first10 = substr($first10, 0, 10);
      $bad = true;
    }
    $replacefix = strpos($last5, '<'); // вырезаем и запоминаем гостевой заменитель
    $last5 = str_replace(' ', '', $last5);
    $replace = max(1, strpos($last5, '<')) + 9;
    $last5 = trim(str_replace('<', '', $last5));
    if ($ln = $lastlen - strlen($last5)) {
      $last5 .= str_repeat('=', $ln);
      $bad = true;
    }
    elseif ($ln < 0) {
      $last5 = substr($last5, 0, $lastlen);
      $bad = true;
    }
    $prognoz = $first10.' '.$last5;
    if ($bad) {
      if (mb_strpos($warn, 'Ж')) $warn = 'aК';
      elseif (!mb_strpos($warn, 'К')) $warn = 'oЖ';
    }
    // TODO: здесь отслеживать карточки
    if (mb_strpos($warn, 'К')) {
      for ($j=0; $prognoz[$j] == '='; $j++);
      $prognoz[$j] = '=';
// если этот матч не состоялся, продублировать карточку на заменяющий
// при этом проследить случай замены несыгравшей форы
// а также проверить, чтобы заменяющий матч таки состоялся
      if ($rprognoz[$j] == '-') {
        if ($ppos == $j + 1) {
          $replacer = $replace;
          if ($rprognoz[$replacer] == '-') { // указанный гостями заменитель не играл, делаем обычную замену
            $replacer = 11;
            while (($rprognoz[$replacer] == '-') && ($replacer < strlen($rprognoz))) $replacer++; // поиск первого неиспользованного форо-заменителя
          }
          $rep = $replacer + 1;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < strlen($rprognoz))) $rep++; // поиск первого неиспользованного заменителя
        }
        else {
          $rep = 11;
          while ((($rprognoz[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < strlen($rprognoz))) $rep++; // поиск первого неиспользованного заменителя
        }
        $prognoz[$rep] = '=';
      }
    }
    elseif (!mb_strpos($warn, 'Ж')) $warn = '  ';
    $aprognoz[$away]['warn'] = $warn;
    $prognoza = str_replace(' ', '', $prognoz);
    if ($warn[0] != '*') $sprognoz[$away] = $prognoza;
    $prognozr = str_replace(' ', '', $rprognoz);
    $line3 = HighlightedScanLine($away, $prognoz, $rprognoz, $warn);

    // удары по воротам
    $shoth = $shota = 0;
    for ($i=0; $i<strlen($rprognoz)-1; $i++) if ($prognozr[$i] != '-') {
      if (!isset($mdp[$i+1]['shots'])) $mdp[$i+1]['shots'] = 0;
      if ($prognozr[$i] == $prognozh[$i]) { $shoth++; if ($aprognoz[$home]['warn'][0] != '*') $mdp[$i+1]['shots']++; }
      if ($prognozr[$i] == $prognoza[$i]) { $shota++; if ($aprognoz[$away]['warn'][0] != '*') $mdp[$i+1]['shots']++; }
      if (($aprognoz[$home]['warn'][0] != '*') && ($prognozh[$i] != '=')) $plrs[$i]++;
      if (($aprognoz[$away]['warn'][0] != '*') && ($prognoza[$i] != '=')) $plrs[$i]++;
    }

    // голы, счет и протокол матча
    $goalh = $goala = 0;
    $mt = 10; // число матчей. участвующих в определении счета
    for ($i=0; $i<strlen($rprognoz); $i++) {
      ($ppos && $prognozr[$ppos - 1] == '-') ? $usereplace = true : $usereplace = false;
      if ($i < $mt) {
        if ($prognozr[$i] == '-') { // замена несостоявшегося матча
          if (!$replacefix) $usereplace = false;
          if ($ppos == $i + 1) {
            if ($prognozr[$replace] == '-') { // указанный гостями заменитель не играл, ищем обычную замену
              $replace = 10;
              while (($prognozr[$replace] == '-') && ($replace < 16)) $replace++; // поиск первого неиспользованного форо-заменителя
            }
            $rep = $replace;
          }
          else {
            $rep = 10;
            while ((($prognozr[$rep] == '-') || ($usereplace && ($rep == $replace))) && ($rep < 16)) $rep++; // поиск первого неиспользованного заменителя
          }
          $usereplace = false;
          $prognozr[$i] = $prognozr[$rep]; $prognozh[$i] = $prognozh[$rep]; $prognoza[$i] = $prognoza[$rep];
          if ($ppos == $i + 1) {
            $ppos = 0;
            if ($prognozr[$i] == $prognozh[$i]) $prognoza[$i] = '='; // фора!
            $line1 = sprintf('%-' . (22 + $replace) . 's', rtrim($line1)) . 'ф';
          }
          $prognozr[$rep] = '-';
        }
        ($prognozr[$i] == $prognozh[$i] || (($ppos == $i + 1) && ($prognozr[$i] == $podstr))) ? $shh = 1 : $shh = 0;
        ($prognozr[$i] == $prognoza[$i]) ? $sha = 1 : $sha = 0;
        if ($shh > $sha) $goalh++;
        elseif ($sha > $shh) $goala++;
        if ($goalh + $goala == $mt) $mt++;
      }
    }
    if (!trim($line1)) $line1 = '&nbsp;';
    $prognozlist .= $line1 . "\n" . $line2 . $goalh . ' (' .$shoth . ")\n" . $line3 . $goala .' (' . $shota . ")\n";
  }
  else { // формат scanprog
    $addline = htmlspecialchars(mb_sprintf('%-21s%-20s', $away, $aprognoz[$away]['prog']));
    ($publish || ($role == 'president')) ? $addline .= mb_sprintf('%-5s', $aprognoz[$away]['warn']) . $date . "\n\n" : $addline .= "                        \n\n";
    $prognozlist .= $addline;
  }
  if (++$nm > $z) {
    $gs = 0;
    $st++;
    //$z = sizeof($virtmatch);
  }
}
if ($enemy) str_replace('name="enemy_str" value=""', 'name="enemy_str" value="' . $enemy . '"', $head);

// таблица числа угадавших прогнозы для формата stat
if ($stat) {
  $prognozr = str_replace(' ', '', $rprognoz);
  for ($i = 0; $i < strlen($rprognoz) - 1; $i++) {
    $mdp[$i+1]['playr'] = '';
    if ($prognozr[$i] == '-') $mdp[$i+1]['shots'] = '-';
    if (!isset($mdp[$i+1]['shots'])) {
      $mdp[$i+1]['shots'] = 0;
      $mdp[$i+1]['playr'] = ':(';
    }
    elseif ($mdp[$i+1]['shots'] == $plrs[$i]) $mdp[$i+1]['playr'] = ':)';
    if ($mdp[$i+1]['shots'] == 1) foreach ($sprognoz as $teamn => $progn)
      if ($progn[$i] == $prognozr[$i]) $mdp[$i+1]['playr'] = $coach[$teamn];

    if (($plrs[$i] > 2) && ($plrs[$i] - $mdp[$i+1]['shots'] == 1)) foreach ($sprognoz as $teamn => $progn)
      if (($progn[$i] != '=') && ($progn[$i] != $prognozr[$i])) $mdp[$i+1]['playr'] = $coach[$teamn];

    $program_table = str_replace('<!--' . ($i + 1) .'-->', $mdp[$i+1]['shots'].' '.$mdp[$i+1]['playr'], $program_table);
  }
  $prognozlist .= '
';
}
// подсветка своей команды
$cal = "\n" . $cal . "\n";
foreach ($teamCodes as $code) {
  $myteam = $teams[$code];
  $cal = str_replace("\n" . $myteam . ' -', "\n" . '<span class="magenta">' . $myteam . '</span> -', $cal);
  $cal = str_replace('- ' . $myteam . "\n", '- <span class="magenta">' . $myteam . "</span>\n", $cal);
  $prognozlist = str_replace("\n" . $myteam . ' ', "\n" . '<span class="magenta">' . $myteam . '</span> ', $prognozlist);
}
//<link href="css/prognoz.css?ver=625" rel="stylesheet">
if (isset($matches))
  echo '[' . $id_json . ']'; // REST responce on event 'matches'
else if (isset($updates))
  echo $prognozlist; // REST responce on event 'FT'
else
  echo '<script>
function newpredict(){
	var i,dd,p="",ps="",min=0,max=0;
	for(i=1;i<=10;i++){
		dd="dice"+i;
		p=$("#"+dd).val()?p+$("#"+dd).val():p+"=";
		dd="ddice"+i;
		if($("#"+dd).val())p=p+"("+$("#"+dd).val()+")";
	}
	p=p+" ";
	for(i=11;i<=15;i++){
		dd="dice"+i;
		p=$("#"+dd).val()?p+$("#"+dd).val():p+"=";
		dd = "ddice"+i;
		if($("#"+dd).val()=="<")p=p+"<";
	}
	for(i=1;i<=15;i++){
		dd="pen"+i;
		if($("#"+dd).val()){
			min=Math.min(min,$("#"+dd).val());
			max=Math.max(max,$("#"+dd).val());
		}
	}
	for(j=1;j<=max;j++)for(i=1;i<=15;i++){
		dd="pen"+i;
		if($("#"+dd).val()==j)
			ps=(ps=="")?"  penalty - "+i:ps+","+i;
	}
	$("#prognoz_str").val(p+ps);
}
function predict(id,dice){
	if($("#"+id).prop("disabled"))return false;
	$("#"+id).val(dice);
	newpredict();
}
function securedice(id,dice){
//	if($("#"+id).prop("disabled"))return false;
	var i,dd;
	for(i=1;i<=10;i++){
		dd="ddice"+i;
		$("#"+dd).val("");
		if(dd==id){
			$("#"+dd).prop("disabled",false);
			$("#"+dd).val(dice);
		}
		else $("#"+dd).prop("disabled",true);
	}
	newpredict();
}
function securehome(id){
//	if($("#"+id).prop("disabled"))return false;
	var i,dd;
	for(i=11;i<=15;i++){
		dd="ddice"+i;
		$("#"+dd).val("");
		if(dd==id) {
			$("#"+dd).prop("disabled",false);
			$("#"+dd).val("<");
		}
		else $("#"+dd).prop("disabled",true);
	}
	newpredict();
}
function penalty(id,diff){
	if($("#"+id).prop("disabled"))return false;
	var p=$("#"+id).val();
	if(diff>0)p++;else{p--;if(p<1)p=""}
	$("#"+id).val(p);
	newpredict();
}
function show_alert(){
	var str=$("#prognoz_str").val();
	if(str.search("=")==-1){
		document.forms[0].submit();
		return true;
	}else{
		var r=confirm("В прогнозе остались незаполненные позиции. Вы действительно хотите отправить его в таком виде?");
		if(r==true){
			document.forms[0].submit();
			return true;
		}
	}
	return false;
}
var '.date_tz('\h\o\u\r\s=G,\m\i\n\u\t\e\s=i,\s\e\c\o\n\d\s=s', '', time(), $_COOKIE['TZ'] ?? 'Europe/Berlin').',sendfp='.(date('G',time())>2&&$today_matches>2*$today_bz?'true':'false').',base=[]
' . $id_arr . '
mom=[]
momup=function(i){clearInterval(mom[i]);mom[i]=setInterval(function(){if(!isNaN(base[i][3])){tm=+base[i][3];base[i][3]=(tm==45||tm==90)?tm+"+":++tm;row=$("#"+i)[0];row.cells[5].innerHTML="<span class=\"blink\">"+base[i][3]+"’</span>"}},60000)}
scorefix=function(d){
	i=d.idx
	m=+d.dk
	if(m<1)m=1;else if(m>59)m=base[i][3]
	h=d.evs;a=d.deps
	s=h+":"+a
	if(base[i][2]!=s){base[i][2]=s;base[i][1]=1}else base[i][1]=0
	s=+d.s
	if(s==0){base[i][3]="?";base[i][2]="-:-"}
	else if(s==1){base[i][3]=(m>45)?"45+":m;momup(i)}
	else if(s==2)base[i][3]="HT"
	else if(s==3){base[i][3]=(m>45)?"90+":m+45;momup(i)}
	else if(s==4||s==8||s==11){if(base[i][3]!="FT"){base[i][3]="FT";var bg=$("#"+i).css("background-color");$("#"+i).css("background-color","gold");$("#"+i).animate({"background-color":bg},20000)}}
	else if(s==5)base[i][3]="SP"
	else if(s==13||s==14)base[i][3]="PP"
	else base[i][3]="?"
	s=base[i][3]
	if(s!="?"){
		r=base[i][2]
		row=$("#"+i)[0]
		row.cells[4].innerHTML=(base[i][1]==1)?"<span class=\"blink\">"+r+"</span>":(s=="FT")?r:"<span class=\"red\">"+r+"</span>"
		row.cells[5].innerHTML=(s=="HT"||s=="SP")?"<span class=\"red\">"+s+"</span>":(s=="?"||s=="PP")?"<span>"+s+"</span>":(s=="FT")?"<span>"+((h==a)?"X":(h>a)?1:2)+"</span>":"<span class=\"blink\">"+s+"’</span>"
	}
}
function detrow(t,tmpz){
	m=t>5?1:0;out="";
	for(i=1-m;i<tmpz.length-m;i++){
		tmps=tmpz[i].split(":");
		out+="<tr class=\"sortable\" data-min=\""+tmps[0]+"\"><td class=\"side\">";
		if(t==0||t==2||t==4)out+="<div class=\"min right\">"+tmps[0]+"\"</div><div class=\"right\">"+tmps[1]+"</div>";
		else if(t==6)out+="<div class=\"min right\">"+tmps[0]+"\"</div><div class=\"right\">"+tmps[2]+"<br><em>"+tmps[1]+"</em></div>";
		out+="</td><td class=\"center\">";
		if(t<2)out+="&#9917;";
		else if(t<6)out+="<i class=\"text-"+(t<4?"danger":"warning")+"\">&#x25AE;</i>";
		else out+="<h5 class=\"green-red\">&#x21c5;</h3>";
		out+="</td><td class=\"side\">";
		if(t==1||t==3||t==5)out+="<div class=\"min left\">"+tmps[0]+"\"</div><div class=\"left\">"+tmps[1]+"</div>";
		else if(t==7)out+="<div class=\"min left\">"+tmps[0]+"\"</div><div class=\"left\">"+tmps[2]+"<br><em>"+tmps[1]+"</em></div>";
		out+="</td></tr>";
	}
	return out;
}
details=function(dom){mid=dom.attr("id");row=$(".p-table").find("tr[did="+mid+"]");if(row.length)row.remove();else{dom.after("<tr did="+mid+"><td colspan=7 class=\"det\"><div class=\"loaderP\"><div class=\"loader\"></td></tr>");socket.emit("footballdetails",mid)}}
mdetails=function(tmpd,id,pos1,pos2){
	if(tmpd!=""&&tmpd!=null&&tmpd!="########?~?|"){
		tps=tmpd.split("#");tab="<table class=\"tablex\">";
		if(pos1!=0||pos2!=0)tab+="<tr><td class=\"side\"><div class=\"right\" style=\"width:"+pos1+"%;background-color:"+(pos1>pos2?"red":"dimgrey")+";color:white\">"+pos1+" &nbsp; </div></td><td class=\"center\">%</td><td class=\"side\"><div class=\"left\" style=\"width:"+pos2+"%;background-color:"+(pos1<pos2?"red":"dimgrey")+";color:white\"> &nbsp; "+pos2+"</div></td></tr>";
		for(j=0;j<6;j++)tab+=detrow(j,tps[j].split(","));
		if(tmpd.indexOf("?~?")!=-1){tmps=tmpd.split("?~?");tps=tmps[1].split("|");if(tps.length){tab+=detrow(6,tps[0].split("**"));tab+=detrow(7,tps[1].split("**"))}}
		tab+="</table>";$("tr[did="+id+"] td.det").html(tab);
		$wrapper=$("tr[did="+id+"]").find(".tablex");$wrapper.find(".sortable").sort(function(a,b){one=a.dataset.min;two=b.dataset.min;ones=one.split("+");one=ones[0];twos=two.split("+");two=twos[0];return +one - +two}).appendTo($wrapper);
	}else $("tr[did="+id+"] td.det").html("<table class=\"tablex\"><tr><td><div align=center style=\"padding:4px;\">информация о матче пока не поступила</div></td></tr></table>")
}
socket=io.connect("//www.score2live.net:1998",{"reconnect":true,"reconnection delay":500,"max reconnection attempts":20,"secure":true})
socket.on("connect",function(){socket.emit("hellothere")})
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){if(sendfp){$.post("'.$this_site.'",{matches:JSON.stringify(d.data.matches),a:"'.$a.'"'.($l?',l:"'.$l.'"':'').',m:"prognoz",s:"'.$s.'",t:"'.$t.'"},function(json){$.each(JSON.parse(json),function(idx,obj){base.push(obj.id);base[obj.id]=obj.d})})}$("#statusline").css("display","none")})
socket.on("footdetails",function(data){data=data[0];if ($(".p-table").find("tr[did="+data.id+"]").length)mdetails(data.mdetay,data.id,data.pos1,data.pos2)})
socket.on("guncelleme",function(d){var json="";$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}});if(json.length)$.post("'.$this_site.'",{updates:"["+json+"]",a:"'.$a.'"'.($l?',l:"'.$l.'"':'').',m:"prognoz",s:"'.$s.'",t:"'.$t.'"'.(isset($c)?',c:"'.$c.'"':'').'},function(html){$("#pl").html(html)})})
</script>
<div class="d-flex">
	<div id="statusline" class="w-100 text-left">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a></div>
	<div id="timedisplay" class="w-100 text-right">&nbsp;</div>
</div>
<div class="h4 text-center">' . $head . '</div>
<div class="d-flex">
	<div class="mx-auto">' . $program_table . '</div>
</div>
<div class="h6 text-center">' . $hint . '</div>
<div class="d-flex">
	<div id="pl" class="monospace w-100">' . $prognozlist . '</div>
	<div id="mt">Матчи тура:' . $cal . '</div>
</div>
';
?>
