<?php
function rewrite_cal($prognoz_dir, $line0, $score0, $score) {
  $cal = file_get_contents($prognoz_dir.'/cal');
  $line9 = str_replace(';'.$score0."\n", ';'.$score, $line0);
  $cal = str_replace($line0, $line9, $cal);
  file_put_contents($prognoz_dir.'/cal', $cal);
}

$season = $s;
$country_code = 'SFP';
$tour = $l.$t;
$prognoz_dir = $online_dir.'SFP/'.$s.'/prognoz/'.$tour;
$program_file = $online_dir.'SFP/'.$s.'/programms/'.$tour;
$rules = $rprognoz = $protocol = $team_code = '';
$hidden = 'прогноз не показан';
$stat = false;

$acodes = file($online_dir.'SFP/'.$s.'/codes.tsv');
if (isset($_SESSION['Coach_name'])) foreach ($acodes as $scode) if ($scode[0] != '#') {
  list($code, $team_code, $name, $email) = explode('	', ltrim($scode, '-'));
  if ($name == $_SESSION['Coach_name'])
    break;

}

if (is_file($program_file)) {

  // парсинг программки
  $programm = file_get_contents($program_file);
  $programm = substr($programm, strpos($programm, "\n", strpos($programm, $tour.' ')) + 1);
  $fr = strpos($programm, 'Последний с');
  $program_matches = explode("\n", substr($programm, 0, $fr));
  switch ($l) {
    case 'PRE':
    case 'SUP': $imax = 20; break;
    case 'PRO': $imax = 15; break;
    case 'FWD': $imax = 12; break;
    default   : $imax = count($program_matches) - 3;
  }
  //$imax = ($l == 'PRE' || $l == 'SUP') ? 20 : ($l == 'PRO' ? 15 : count($program_matches) - 3);
  $programm = substr($programm, $fr);
  $fr = strpos($programm, '.');
  $lastdate = trim(substr($programm, $fr - 2, 5));
  $lasttm = ($fr1 = strpos($programm, ':', $fr)) && ($fr1 - $fr < 50) ? trim(substr($programm, $fr1 - 2, 5)) : '';

  // парсинг матчей тура (для примитивного варианта)
  $calfp = explode("\n", $programm);
  $cal = '';
  foreach ($calfp as $line)
    if (strpos($line, ' - '))
      $cal .= trim($line)."\n";

  // отправка прогноза
  if ($team_code && isset($_POST['submitpredict']) && ($prognoz = trim($_POST['prognoz_str'])))
    send_predict('SFP', $season, $team_code, $tour, $prognoz, '', $ip);

  $publish = is_file($prognoz_dir.'/published');
  $closed = is_file($prognoz_dir.'/closed');

  // выборка матчей тура (расширенный вариант)
  $pro = false;
  $match_title = '';
  $aprognoz = array();
  if ($pro = is_file($prognoz_dir.'/cal')) {
    $cal_pro = file($prognoz_dir.'/cal');
    $cal = '';
    $i = 1;
    foreach ($cal_pro as $cal_line) if ($cal_line = trim($cal_line)) {
      if (!strpos($cal_line, ';')) $cal_line .= ';;;';
      list($line, $link1, $group, $score) = explode(';', $cal_line);
      if (trim($group)) {
        if ($cal) $cal .= '<br />';
        $cal .= '<b>'.$group.'</b><br />
';
      }
      $cal .= '<a href="/?a=sfp-team&amp;m=prognoz&amp;s='.$season.'&amp;l='.$l.'&amp;t='.$t.'&amp;n='.$i;
      if (isset($prognoz_str) && $prognoz_str)
        $cal .='&amp;prognoz_str='.$prognoz_str;

      $cal .= '" class="text14">'.$line.'</a> '.$score.'<br />
';
      if (!isset($n) && !(strpos($line, 'SFP') === false)) $n = $i;
      if (isset($n) && $n == $i) {
        list($hometeam, $awayteam) = explode(' - ', $line);
        $match_title = '<b>' . $line;
        if (trim($link1)) $link = $link1;
        $line0 = $cal_line."\n";
        $score0 = $score;
      }
      $i++;
    }
    if (!isset($n)) {
      $n = 1;
      list($line0, $link, $group, $score0) = explode(';', trim($cal_pro[0]));
      list($hometeam, $awayteam) = explode(' - ', $line0);
      $match_title = '<b>' . $line0;
    }
    $virtmatch = array();
    $home_arr = is_file($prognoz_dir.'/'.$hometeam) ? file($prognoz_dir.'/'.$hometeam) : [];
    $away_arr = is_file($prognoz_dir.'/'.$awayteam) ? file($prognoz_dir.'/'.$awayteam) : [];
    for ($i=0; $i<16; $i++) {
      if (isset($home_arr[$i])) {
        if ($l == 'PRO')
          $home_arr[$i] = strtr($home_arr[$i], ' ', ';');

        list($home, $prog) = explode(';', trim($home_arr[$i]));
        $aprognoz[$home]['prog'] = $prog;
        $aprognoz[$home]['time'] = 0;
      }
      else $home = '?';
      if (isset($away_arr[$i])) {
        if ($l == 'PRO')
          $away_arr[$i] = strtr($away_arr[$i], ' ', ';');

        list($away, $prog) = explode(';', trim($away_arr[$i]));
        $aprognoz[$away]['prog'] = $prog;
        $aprognoz[$away]['time'] = 0;
      }
      else $away = '?';
      if ($home != '?' || $away != '?') $virtmatch[] = $home.' - '.$away;
    }
  }
  elseif ($cal != 'календарь не найден') {
    $virtmatch = array();
    $atemp = explode("\n", $cal);
    $cal = '';
    foreach ($atemp as $line) if (($line = trim($line)) && strpos($line, ' - ')) {
      if ($cut = strpos($line, '  ')) $line = trim(substr($line, 0, $cut));
      $virtmatch[] = $line;
      $cal .= $line.'<br />
';
    }
  }

  // таблица программки тура
  require_once('online/tournament.inc.php');
  include script_from_cache('online/realteam.inc.php');
  list($last_day, $last_month) = explode('.', $lastdate);
  if (!isset($updates)) $updates = NULL;
  $base = get_results_by_date($last_month, $last_day, $updates);
  $mdp = array();
  $program_table = '<table>
<tr><th>№</th><th align="left">матч для прогнозирования</th><th>турнир</th><th>дата время</th><th>счёт</th><th>исход</th><th>прогноз</th></tr>
';
  $id_arr = $id_json = '';
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
        $mt = '-:-'; $rt = '?'; $tn = '??:??'; // по умолчанию информации о счёте и времени матча нет
        if (isset($base[$match])) {
          list($match_date, $match_time) = explode(' ', $base[$match][2]);
          list($match_month, $match_day) = explode('-', $match_date);
        // если дата отправки и матча совпадают, матч учитывается только если указано время отправки меньше 20:00.
        // для этого число "день матча" при проверке увеличиваетс на 1, чтобы срабатывало условие > $last_date
          //($lasttm && ($lasttm[0] != '2')) ? $d1 = 1 : $d1 = 0;
          $d1 = 1; // в турнирах сборной срок указан в день матчей
        // первое условие - для новогодних туров
          if (($last_month == 12 && $match_month == 1) || ($match_month . $match_day . $d1) > ($last_month . $last_day . '0')) {
          // грубая проверка на максимальный срок переноса матча (все месяцы считаются по 31 дню)
            list($prog_day, $prog_month) = explode('.', $dm);
            if (($prog_month == 12) && ($match_month == 1)) $prog_month = 0;
            if (($prog_month * 31 + $prog_day + 7) < ($match_month * 31 + $match_day))
              $st = 'POS'; // перенос более, чем на 7 дней
            else {         // матч надо учитывать
              $dm = $match_day . '.' . $match_month;
              $tn = $match_time;
              $st = $base[$match][3];
              $mt = $base[$match][5];
            }
            if (($st != '-') && (($st <= '90') || ($st == 'HT'))) {
              if ($base[$match][6]) {
                $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"' . $mt . '","' . $st . '"];
';
                $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"' . $mt . '","' . $st . '"]}';
              }
              $mttemp = $mt;
              $mt = '<span class="red">' . $mt . '</span>';
              ($st == 'HT') ? $rt = '<span class="red">' . $st . '</span>' : $rt = '<span class="blink">' . $st . "'" . '</span>';
            }
            elseif (($st == 'CAN') || ($st == 'POS') || ($st == 'SUS')) {
              $mt = $st;
              $rt = '-';
            }
            elseif ($st == 'FT') {
              list($gh, $ga) = explode(':', $mt);
              if ($gh == $ga) $rt = '0';
              else ($gh > $ga) ? $rt = '1' : $rt = '2';
              $stat = true;
            }
            elseif ($base[$match][6] && $match_day == date('d', time())) {
              $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"-:-","-"];
';
              $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"-:-","-"]}';
            }
          }
          else {
            $mt = $st = 'POS';
            $rt = '-';
          }
          $tr_id = $base[$match][6] ? ' id="' . $base[$match][6] . '"' : '';
        }
        else {
          $st = '-';
          $tr_id = '';
        }
        $mdp[$nm] = array('home' => $home, 'away' => $away, 'trnr' => $tournament, 'date' => $dm, 'rslt' => $mt, 'case' => $rt);

        if ($l == 'FWD' && $nm == 7) { // для Эксперт-лиги важно показать разделение таймов
          $program_table .= '<tr><td colspan ="6"><img src="images/spacer.gif" alt="" />';
          if (!$closed && ($role != 'badlogin')) $program_table .= '<td colspan="3"><img src="images/spacer.gif" alt="" /></td>';
          $program_table .= '</tr>
';
        }
        $program_table .= '<tr' . $tr_id . '><td align="right">'.$nm.'</td><td style="text-align:left;width:288px' . ($tr_id != '' ? ';cursor:pointer" onClick="details($(this).closest(\'tr\'))' : '') . '">'.$home.' - '.$away.'</td><td align="left">'.$tournament.'</td><td align="right">&nbsp;'.$dm.' '.$tn.'&nbsp;</td><td align="center">&nbsp;'.$mt.'&nbsp;</td><td align="middle">&nbsp;'.$rt.'&nbsp;</td>';

// $l, $nm, $rt, $mt, $mtemp, $prognoz_str, (!$closed && $team_code)
          if ($rt == '1' || $rt == '0' || $rt == '2') {
            $onchange = 'disabled="disabled" ';
            $dis = 'dis';
            $val = $rt;
          }
          else {
            $onchange = 'onchange="newpredict();"';
            $dis = '';
            if (isset($prognoz_str) && $prognoz_str)
              $val = $prognoz_str[$nm - 1];
            else
              $val = '';

          }

          if ($l == 'FWD') { // специальный метод указания ставок для Эксперт-лиги
            $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','0'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <a href="#" onclick="predict('."'dice$nm$dis','-'".'); return false;">-</a>
      <input type="text" name="'."dice$nm".'" value="" id="'."dice$nm".'" class="pr_str" '.$onchange.' />
    </td>';
            if (!$closed && $team_code)
              $program_table .= '
    <td>
      <a href="#" onclick="securedice('."'ddice$nm$dis',''".'); return false;">1</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','2'".'); return false;">2</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','3'".'); return false;">3</a>
      <input type="text" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" class="pr_str" '.$onchange.' />
    </td>
';
          }
          elseif ($l == 'SPR') { // двойные ставки для Спартакиады
            $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','0'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'."dice$nm".'" value="'.$val.'" id="'."dice$nm".'" class="pr_str" '.$onchange.' />
    </td>';
            if (!$closed && $team_code)
              $program_table .= '
    <td>
      <a href="#" onclick="securedice('."'ddice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','0'".'); return false;">X</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis','2'".'); return false;">2</a>
      <a href="#" onclick="securedice('."'ddice$nm$dis',''".'); return false;">_</a>
      <input type="text" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" class="pr_str" '.$onchange.' />
    </td>
';
            else
              $program_table .= '
      <input type="hidden" name="'."ddice$nm".'" value="" id="'."ddice$nm".'" class="pr_str" '.$onchange.' />
';
          }
          elseif ($l == 'PRE' || $l == 'SUP') { // ввод счёта для PREDвидения и Суперсерии
            $program_table .= '
    <td>
      <select name="'.'dice'.($nm*2-1).'" id="'.'dice'.($nm*2-1).'" '.$onchange.'>';
//            for ($option=0; $option<10; $option++) {
            foreach (array('*','0','1','2','3','4','5','6','7','8','9') as $option) {
               $program_table .= '<option';
               if (($rt == '1' || $rt == '0' || $rt == '2') && $option == $mt[0])
                 $program_table .= ' selected="selected"';
               elseif (isset($prognoz_str) && $prognoz_str && $option == $prognoz_str[$nm*2-2])
                 $program_table .= ' selected="selected"';
               elseif ($mt[0] == '<' && $option == $mttemp[0])
                 $program_table .= ' selected="selected"';

               $program_table .= '>'.$option.'</option>';
            }
            $program_table .= '</select> -
      <select name="'.'dice'.($nm * 2).'" id="'.'dice'.($nm * 2).'" '.$onchange.'>';
//            for ($option=0; $option<10; $option++) {
            foreach (array('*','0','1','2','3','4','5','6','7','8','9') as $option) {
               $program_table .= '<option';
               if (($rt == '1' || $rt == '0' || $rt == '2') && $option == $mt[2])
                 $program_table .= ' selected="selected"';
               elseif (isset($prognoz_str) && $prognoz_str && $option == $prognoz_str[$nm*2-1])
                 $program_table .= ' selected="selected"';
               elseif ($mt[0] == '<' && $option == $mttemp[2])
                 $program_table .= ' selected="selected"';

               $program_table .= '>'.$option.'</option>';
            }
//      <select name="'.'dice'.($nm*2-1).'" id="'.'dice'.($nm*2-1).'" '.$onchange.'><option>*</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option></select> -
//      <select name="'.'dice'.($nm * 2).'" id="'.'dice'.($nm * 2).'" '.$onchange.'><option>*</option><option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option></select>
            $program_table .= '</select>
    </td>
';
          }
          elseif ($l == 'TOR') { // Лига Торпедо принимает ставку "*"
            $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','0'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <a href="#" onclick="predict('."'dice$nm$dis','*'".'); return false;">*</a>
      <input type="text" name="'.'dice'.$nm.'" value="" id="'.'dice'.$nm.'" class="pr_str" '.$onchange.' />
    </td>
';
          }
          elseif ($l != 'PRO' || $nm <= 5) { // ProfiOpen: первые 5 матчей - исходы
            $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','0'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'.'dice'.$nm.'" value="'.$val.'" id="'.'dice'.$nm.'" class="pr_str" '.$onchange.' />
    </td>
';
          }

          elseif ($l == 'PRO') { // ProfiOpen: последние 5 матчей - счёт
            $program_table .= '
    <td>
      <select name="'.'dice'.(6+($nm-6)*2).'" id="'.'dice'.(6+($nm-6)*2).'" '.$onchange.'><option>*</option>';
            for ($option=0; $option<10; $option++) {
               $program_table .= '<option';
               if (($rt == '1' || $rt == '0' || $rt == '2') && $option == $mt[0])
                 $program_table .= ' selected="selected"';
               elseif (isset($prognoz_str) && $prognoz_str && $option == $prognoz_str[5+($nm-6)*2])
                 $program_table .= ' selected="selected"';
               elseif ($mt[0] == '<' && $option == $mttemp[0])
                 $program_table .= ' selected="selected"';

               $program_table .= '>'.$option.'</option>';
            }
            $program_table .= '</select> -
      <select name="'.'dice'.(7+($nm-6)*2).'" id="'.'dice'.(7+($nm-6)*2).'" '.$onchange.'><option>*</option>';
            for ($option=0; $option<10; $option++) {
               $program_table .= '<option';
               if (($rt == '1' || $rt == '0' || $rt == '2') && $option == $mt[2])
                 $program_table .= ' selected="selected"';
               elseif (isset($prognoz_str) && $prognoz_str && $option == $prognoz_str[6+($nm-6)*2])
                 $program_table .= ' selected="selected"';
               elseif ($mt[0] == '<' && $option == $mttemp[2])
                 $program_table .= ' selected="selected"';

               $program_table .= '>'.$option.'</option>';
            }
            $program_table .= '</select>
    </td>
';
          }


          if ($closed && $nm == 1) $program_table .= '
<td rowspan="10">
для расчёта вариантов<br />
завершения матчей<br />
выберите в колонке<br />
"прогноз" возможный<br />
исход и / или укажите<br />
счёт и нажмите кнопку<br />
<form action="" name="tform" method="get">
<input type="hidden" name="a" value="sfp-team" />
<input type="hidden" name="m" value="prognoz" />
<input type="hidden" name="l" value="'.$l.'" />
<input type="hidden" name="s" value="'.$s.'" />
<input type="hidden" name="t" value="'.$t.'" />
<input type="hidden" name="n" value="'.$n.'" />
<input type="hidden" id="prognoz_str" name="prognoz_str" value="" size="50" />
&nbsp;<input type="submit" value=" что будет, если ...? " />
</form>
</td>
';

        $program_table .= '</tr>
';
        if (strlen($rt) > 1) $rt = '?';
        if ($l != 'PRE' && $l != 'SUP' && ($l != 'PRO' || $nm <= 5)) $rprognoz .= $rt;
        else if ($st == 'FT') {
          list($goalh, $goala) = explode(':', $base[$match][5]);
          $rprognoz .= min(9, $goalh) . min(9, $goala);
        }
        else $rprognoz .= '??';
      }
    }
  }
  $program_table .= '</table>
';

  $prognozlist = '';
  if (!$pro) {
    // выборка прогнозов из мейлбокса
    $mbox = is_file($prognoz_dir.'/mail') ? file($prognoz_dir.'/mail') : [];
    $have = [];
    foreach ($mbox as $msg) {
      list($team, $prog, $time) = explode(';', $msg);
      if (!$publish && !$team_code) $prog = $hidden;
      if (!$publish && ($prog != $hidden || $team_code)) $prognozlist .= '
' . $team . str_repeat(' ', 21 - mb_strlen($team)) . htmlspecialchars($prog) . str_repeat(' ', max(0, 22 - mb_strlen($prog))) . date('d M y  H:i:s', $time);
      if (!isset($aprognoz[$team]['time']) || ($time > $aprognoz[$team]['time'])) {
        $aprognoz[$team]['prog'] = $prog;
        $aprognoz[$team]['time'] = $time;
      }
    }
  }

  // use simulated results if any
  if (isset($prognoz_str) && $prognoz_str) {
    for ($i=0; $i<strlen($prognoz_str); $i++) if ($prognoz_str[$i] != '*')
      $rprognoz[$i] = $prognoz_str[$i];

  }

  if ($publish) switch ($l) {
    case 'PRE':
    case 'SUP':
      $prognozlist .= '
Правильный результат ';
      for ($i=0; $i<20; $i+=2) $prognozlist .= $rprognoz[$i] . ':' . $rprognoz[$i+1] . ' ';
      $prognozlist .= 'баллы (ос.)
';
      break;
    case 'PRO':
      $prognozlist .= '
Правильный результат ' . substr($rprognoz, 0, 5) . ' ';
      for ($i=5; $i<15; $i+=2) $prognozlist .= $rprognoz[$i] . ':' . $rprognoz[$i+1] . ' ';
      $prognozlist .= '  1 тайм  2 тайм   Матч

';
      break;
    case 'SPR':
      $prognozlist .= '<table><tr><td>
Правильный результат <b>' . substr($rprognoz, 0, 5) . '.' . substr($rprognoz, 5) . '</b> счёт(исходы)

</td></tr>
';
      break;
    default :
      $prognozlist .= '
Правильный результат <b>' . substr($rprognoz, 0, 5) . '.' . substr($rprognoz, 5) . '</b> счёт(исходы)

';
      break;
  }
  // теперь список прогнозов по парам виртуальных матчей в формате stat
  $teamh = 0;
  $teama = 0;
  $thith = 0;
  $thita = 0;
  $tptsh = 0;
  $tptsa = 0;
  $nm = 0;
  if ($publish && $l != 'PRE' && $l != 'SUP') foreach ($virtmatch as $line) {
    $hith = 0;
    $goalh = 0;
    $pointh = 0;
    $goal2h = 0;
    $hita = 0;
    $goala = 0;
    $pointa = 0;
    $goal2a = 0;
    $atemp = explode(' - ', $line);
    $home = trim($atemp[0]);
    // хозяин
    if (isset($aprognoz[$home]['time']) && $aprognoz[$home]['time'] != '') $date = date('d M y  H:i:s', $aprognoz[$home]['time']);
    isset($aprognoz[$home]['prog']) ? $prognozh = trim($aprognoz[$home]['prog']) : $prognozh = '';
    $prognozDoubled = '';
    $prognozColored = '';
    $j = 0;
    for ($i=0; $i<$imax; $i++) {
      if ($l == 'SPR') {
        if ($i == 5) {
          $prognozDoubled .= ' ';
          $prognozColored .= '.';
        }
        if (isset($prognozh[$j])) {
          if ($prognozh[$j] == $rprognoz[$i]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognozh[$j].'</span>';
            $hith++;
          }
          else $prognozColored .= $prognozh[$j];
          if (isset($prognozh[$j+1]) && $prognozh[$j+1] == '(') {
            $j += 2;
            if ($prognozh[$j] == $rprognoz[$i]) {
              $prognozDoubled .= '<span style="background-color: lime; font-weight: bold;">'.$prognozh[$j].'</span>';
              $hith++;
            }
            else $prognozDoubled .= $prognozh[$j];
            $j++;
          }
          else $prognozDoubled .= ' ';
        }
        else $prognozColored .= ' ';
        $j++;
      }
      elseif ($l != 'PRO' || $i < 5) {
        if ($i == 5) $prognozColored .= '.';
        if (isset($prognozh[$i])) ($prognozh[$i] == $rprognoz[$i]) ?
             $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognozh[$i].'</span>' :
             $prognozColored .= $prognozh[$i];
        else $prognozColored .= ' ';
      }
      else {
        if ($i == 5) $prognozColored .= ' ';
        if (isset ($prognozh[$i])) {
          if($rprognoz[$i] != '?' && $prognozh[$i] == $rprognoz[$i] && $prognozh[$i+1] == $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognozh[$i].':'.$prognozh[$i+1].'</span> ';
            $pointh += 4;
          }
          else if($rprognoz[$i] != '?' && $prognozh[$i] - $prognozh[$i+1] == $rprognoz[$i] - $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: yellow; font-weight: bold;">'.$prognozh[$i].':'.$prognozh[$i+1].'</span> ';
            $pointh += 3;
          }
          else if($rprognoz[$i] != '?' && (($prognozh[$i] > $prognozh[$i+1] && $rprognoz[$i] > $rprognoz[$i+1])
               || ($prognozh[$i] < $prognozh[$i+1] && $rprognoz[$i] < $rprognoz[$i+1]))) {
            $prognozColored .= '<span style="background-color: cyan; font-weight: bold;">'.$prognozh[$i].':'.$prognozh[$i+1].'</span> ';
            $pointh += 2;
          }
          else $prognozColored .= $prognozh[$i] . ':' . $prognozh[$i+1] . ' ';
        }
        else $prognozColored .= '    ';
        $i++;
      }
    }
    ($l == 'SPR') ? $line1 = str_repeat(' ', 21) . $prognozDoubled . "\n" : $line1 = '';
    $line1 .= $home . str_repeat(' ', 21 - mb_strlen($home)) . $prognozColored;
    // гость
    $away = trim($atemp[1]);
    if (isset($aprognoz[$away]['time']) && $aprognoz[$away]['time'] != '') $date = date('d M y  H:i:s', $aprognoz[$away]['time']);
    isset($aprognoz[$away]['prog']) ? $prognoza = $aprognoz[$away]['prog'] : $prognoza = '';
    $prognozDoubled = '';
    $prognozColored = '';
    $j = 0;
    for ($i=0; $i<$imax; $i++) {
      if ($l == 'SPR') {
        if ($i == 5) {
          $prognozDoubled .= ' ';
          $prognozColored .= '.';
        }
        if (isset($prognoza[$j])) {
          if ($prognoza[$j] == $rprognoz[$i]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognoza[$j].'</span>';
            $hita++;
          }
          else $prognozColored .= $prognoza[$j];
          if (isset($prognoza[$j+1]) && $prognoza[$j+1] == '(') {
            $j += 2;
            if ($prognoza[$j] == $rprognoz[$i]) {
              $prognozDoubled .= '<span style="background-color: lime; font-weight: bold;">'.$prognoza[$j].'</span>';
              $hita++;
            }
            else $prognozDoubled .= $prognoza[$j];
            $j++;
          }
          else $prognozDoubled .= ' ';
        }
        else $prognozColored .= ' ';
        $j++;
      }
      elseif ($l != 'PRO' || $i < 5) {
        if ($i == 5) $prognozColored .= '.';
        if(isset ($prognoza[$i])) ($prognoza[$i] == $rprognoz[$i]) ?
             $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognoza[$i].'</span>' :
             $prognozColored .= $prognoza[$i];
        else $prognozColored .= ' ';
        if ($rprognoz[$i] != '?') {
          if (isset($prognozh[$i]) && $rprognoz[$i] == $prognozh[$i]) {
            $hith++;
            $hh = 1;
          }
          else $hh = 0;
          if (isset($prognoza[$i]) && $rprognoz[$i] == $prognoza[$i]) {
            $hita++;
            $ha = 1;
          }
          else $ha = 0;
/*
          if ($hh > $ha && $prognoza[$i] != '*') $goalh++;
          else if ($ha > $hh && $prognozh[$i] != '*') $goala++;
*/
        }
      }
      else {
        if ($i == 5) $prognozColored .= ' ';
        if(isset ($prognoza[$i])) {
          if($rprognoz[$i] != '?' && $prognoza[$i] == $rprognoz[$i] && $prognoza[$i+1] == $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prognoza[$i].':'.$prognoza[$i+1].'</span> ';
            $pointa += 4;
          }
          else if($rprognoz[$i] != '?' && $prognoza[$i] - $prognoza[$i+1] == $rprognoz[$i] - $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: yellow; font-weight: bold;">'.$prognoza[$i].':'.$prognoza[$i+1].'</span> ';
            $pointa += 3;
          }
          else if($rprognoz[$i] != '?' && (($prognoza[$i] > $prognoza[$i+1] && $rprognoz[$i] > $rprognoz[$i+1])
               || ($prognoza[$i] < $prognoza[$i+1] && $rprognoz[$i] < $rprognoz[$i+1]))) {
            $prognozColored .= '<span style="background-color: cyan; font-weight: bold;">'.$prognoza[$i].':'.$prognoza[$i+1].'</span> ';
            $pointa += 2;
          }
          else $prognozColored .= $prognoza[$i] . ':' . $prognoza[$i+1] . ' ';
        }
        else $prognozColored .= '    ';
        $i++;
      }
    }
    ($l == 'SPR') ? $line2 = str_repeat(' ', 21) . $prognozDoubled . "\n" : $line2 = '';
    $line2 .= $away . str_repeat(' ', 21 - mb_strlen($away)) . $prognozColored;
    $rule = array(
      'FFL' => array(0,1,2,3,4,5,6,7,8,9,10),
      'FFP' => array(0,1,1,1,2,2,2,3,4,5,6,7),
      'PRO' => array(0,1,1,2,2,3),
      'SPR' => array(0,1,1,2,2,2,3,3,3,4,4),
      'TOR' => array(0,1,1,1,2,2,3,3,4,4,4),
    );
    ($hith > $hita) ? $goalh += $rule[$l][$hith - $hita] : $goala += $rule[$l][$hita - $hith];
    switch ($l) {
      case 'PRO':
        $goalh2 = 0;
        $goala2 = 0;
        if ($pointh - $pointa > 10)      $goalh2 = 3;
        else if ($pointh - $pointa > 5)  $goalh2 = 2;
        else if ($pointh - $pointa > 1)  $goalh2 = 1;
        else if ($pointa - $pointh > 10) $goala2 = 3;
        else if ($pointa - $pointh > 5)  $goala2 = 2;
        else if ($pointa - $pointh > 1)  $goala2 = 1;
        if ($nm < 6) {
          $teamh += $goalh + $goalh2;
          $teama += $goala + $goala2;
          $thith += $hith;
          $thita += $hita;
          $tptsh += $pointh;
          $tptsa += $pointa;
        }
        $prognozlist .= $line1.'  '.$goalh.' ('.$hith.') + '.$goalh2.' ('.sprintf('%2s', $pointh).') = '.($goalh + $goalh2).' ('.($hith + $pointh * 0.4).')
'.                      $line2.'  '.$goala.' ('.$hita.') + '.$goala2.' ('.sprintf('%2s', $pointa).') = '.($goala + $goala2).' ('.($hita + $pointa * 0.4).')

';
        if ($nm++ == 5) {
          if (!$thith && !$tptsh) $teama = min(3, $teama);
          if (!$thita && !$tptsa) $teamh = min(3, $teamh);
          $match_title .= '  '.$teamh.':'.$teama.' ('.$thith.'-'.$thita.') ('.$tptsh.'-'.$tptsa.')</b>';
          if (isset($renew))
            rewrite_cal($prognoz_dir, $line0, $score0, $teamh.':'.$teama.' ('.$thith.'-'.$thita.') ('.$tptsh.'-'.$tptsa.')'."\n");

          $prognozlist .= '
<b>Скамейка запасных</b>

';
        }
        break;
      case 'FFP':
        if ($nm < 6) {
          $teamh += $goalh;
          $teama += $goala;
          $thith += $hith;
          $thita += $hita;
        }
        $prognozlist .= $line1.'   '.$goalh.' ('.$hith.')
'.                      $line2.'   '.$goala.' ('.$hita.')

';
        if ($nm++ == 5) {
          if (!$thith) $teama = min(3, $teama);
          if (!$thita) $teamh = min(3, $teamh);
          $match_title .= '  '.$teamh.':'.$teama.' ('.$thith.'-'.$thita.')</b>';
          if (isset($link) && trim($link)) $match_title .= ' &nbsp; <a href="http://kfp.ru/fest/ffp2017/matchcenter.php?'.$link.'" target="_blank">Матч-центр на KFP.RU</a>';
          if (isset($renew))
            rewrite_cal($prognoz_dir, $line0, $score0, $teamh.':'.$teama.' ('.$thith.'-'.$thita.')'."\n");

          $prognozlist .= '
<b>Скамейка запасных</b>

';
        }
        break;
      case 'FFL':
        $prognozlist .= $line1.'   '.$goalh.' ('.$hith.')
'.                      $line2.'   '.$goala.' ('.$hita.')

';
        break;
      case 'SPR':
        if ($prognozh == '' && $goala > 2) $goala = 2;
        if ($prognoza == '' && $goalh > 2) $goalh = 2;
        if ($nm < 8) {
          $teamh += $goalh;
          $teama += $goala;
          $thith += $hith;
          $thita += $hita;
        }
        $prognozlist .= '<tr><td>'. $line1.'   '.$goalh.' ('.$hith.')
'.                      $line2.'   '.$goala.' ('.$hita.')
</td></tr>
';
        if ($nm++ == 7) {
          $match_title .= '  '.$teamh.':'.$teama.' ('.$thith.'-'.$thita.')</b>';
          if (isset($link) && trim($link)) $match_title .= ' &nbsp; <a href="http://sportgiant.net/games/'.$link.'" target="_blank">Матч-центр на SportGiant</a>';
          if (isset($renew))
            rewrite_cal($prognoz_dir, $line0, $score0, $teamh.':'.$teama.' ('.$thith.'-'.$thita.')'."\n");

          $prognozlist .= '<tr><td>

<b>Скамейка запасных</b>

</td></tr>
';
        }
        break;
      case 'TOR':
        if ($nm < 6) {
          $teamh += $goalh;
          $teama += $goala;
          $thith += $hith;
          $thita += $hita;
        }
        $prognozlist .= $line1.'   '.$goalh.' ('.$hith.')
'.                      $line2.'   '.$goala.' ('.$hita.')

';
        if ($nm++ == 5) {
          $match_title .= '  '.$teamh.':'.$teama.' ('.$thith.'-'.$thita.')</b>';
          if (isset($link) && trim($link)) $match_title .= ' &nbsp; <a href="http://www.torpedoru.com/match.php?id='.$link.'" target="_blank">Стадион матча на КСП "Торпедо"</a>';
          if (isset($renew))
            rewrite_cal($prognoz_dir, $line0, $score0, $teamh.':'.$teama.' ('.$thith.'-'.$thita.')'."\n");
          $prognozlist .= '
<b>Скамейка запасных</b>

';
        }
        break;
    }
  }
  if ($publish && $l == 'SPR') $prognozlist .= '</table>';

  if ($publish && ($l == 'PRE' || $l == 'SUP')) {
    $replaces = array();
    $players = array();
    $homelist = '';
    $homepoints = array(0,0,0,0,0,0,0,0,0,0);
    $homeworepl = 0;
    $lastplayed = 0;
    for ($p=0; $p<sizeof($home_arr); $p++) {
      list($name, $prog, $time, $repl) = explode(';', trim($home_arr[$p]));
      $players['home'][$p]['name'] = $name;
      if ($repl) $replaces[$repl]['home'][] = $p;
      $prognozColored = '';
      $points = 0;
      $pointm = 0;
      $main = false;
      for ($i=0; $i<20; $i+=2) {
        if ($rprognoz[$i] != '?') $lastplayed = $i / 2;
        $point = 0;
        $players['home'][$p]['points'][$i / 2] = $point;
        if (isset($prog[$i])) {
          if ($p < 6) {
            if (!$i) {
              $prognozColored .= '<u>';
              $main = true;
            }
            elseif ($repl && $i == ($repl - 1) * 2) {
              $prognozColored .= '</u>';
              $main = false;
            }
          }
          elseif ($repl && $i == ($repl - 1) * 2) {
            $prognozColored .= '<u>';
            $main = true;
          }
          if ($rprognoz[$i] != '?' && $prog[$i] == $rprognoz[$i] && $prog[$i+1] == $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point = 4;
          }
          elseif ($rprognoz[$i] != '?' && $prog[$i] - $prog[$i+1] == $rprognoz[$i] - $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: yellow; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point = 3;
          }
          elseif ($rprognoz[$i] != '?' && (($prog[$i] > $prog[$i+1] && $rprognoz[$i] > $rprognoz[$i+1])
               || ($prog[$i] < $prog[$i+1] && $rprognoz[$i] < $rprognoz[$i+1]))) {
            $prognozColored .= '<span style="background-color: cyan; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point = 2;
          }
          else $prognozColored .= $prog[$i] . ':' . $prog[$i+1] . ' ';
        }
        else $prognozColored .= '    ';

        $points += $point;
        if ($main) {
          $homepoints[$i / 2] += $point;
          $pointm += $point;
          $players['home'][$p]['points'][$i / 2] = $point;
        }
        ($i) ? $players['home'][$p]['psumm'][$i / 2] = $players['home'][$p]['psumm'][$i / 2 - 1] + $point
             : $players['home'][$p]['psumm'][$i / 2] = $point;
      }
      if ($main) $prognozColored .= '</u>';
      $homelist .= $name . str_repeat(' ', 21 - mb_strlen($name)) . $prognozColored . sprintf('%5s', $points) . ' (' . $pointm . ")\n";
      if ($p < 6) $homeworepl += $points;
    }

    $awaylist = '';
    $awaypoints = array(0,0,0,0,0,0,0,0,0,0);
    $awayworepl = 0;
    for ($p=0; $p<sizeof($away_arr); $p++) {
      list($name, $prog, $time, $repl) = explode(';', trim($away_arr[$p]));
      $players['away'][$p]['name'] = $name;
      if ($repl) $replaces[$repl]['away'][] = $p;
      $prognozColored = '';
      $points = 0;
      $pointm = 0;
      $main = false;
      for ($i=0; $i<20; $i+=2) {
        $point = 0;
        $players['away'][$p]['points'][$i / 2] = $point;
        if(isset ($prog[$i])) {
          if ($p < 6) {
            if (!$i) {
              $prognozColored .= '<u>';
              $main = true;
            }
            elseif ($repl && $i == ($repl - 1) * 2) {
              $prognozColored .= '</u>';
              $main = false;
            }
          }
          elseif ($repl && $i == ($repl - 1) * 2) {
            $prognozColored .= '<u>';
            $main = true;
          }
          if($rprognoz[$i] != '?' && $prog[$i] == $rprognoz[$i] && $prog[$i+1] == $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point += 4;
          }
          else if($rprognoz[$i] != '?' && $prog[$i] - $prog[$i+1] == $rprognoz[$i] - $rprognoz[$i+1]) {
            $prognozColored .= '<span style="background-color: yellow; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point += 3;
          }
          else if($rprognoz[$i] != '?' && (($prog[$i] > $prog[$i+1] && $rprognoz[$i] > $rprognoz[$i+1])
               || ($prog[$i] < $prog[$i+1] && $rprognoz[$i] < $rprognoz[$i+1]))) {
            $prognozColored .= '<span style="background-color: cyan; font-weight: bold;">'.$prog[$i].':'.$prog[$i+1].'</span> ';
            $point += 2;
          }
          else $prognozColored .= $prog[$i] . ':' . $prog[$i+1] . ' ';
        }
        else $prognozColored .= '    ';

        $points += $point;
        if ($main) {
          $awaypoints[$i / 2] += $point;
          $pointm += $point;
          $players['away'][$p]['points'][$i / 2] = $point;
        }
        ($i) ? $players['away'][$p]['psumm'][$i / 2] = $players['away'][$p]['psumm'][$i / 2 - 1] + $point
             : $players['away'][$p]['psumm'][$i / 2] = $point;
      }
      if ($main) $prognozColored .= '</u>';
      $awaylist .= $name . str_repeat(' ', 21 - mb_strlen($name)) . $prognozColored . sprintf('%5s', $points) . ' (' . $pointm . ")\n";
      if ($p < 6) $awayworepl += $points;
    }
    $changes = array();
    $homeline = $hometeam . str_repeat(' ', max(0, 20 - mb_strlen($hometeam)));
    $hometotal = 0;
    for ($i=0; $i<10; $i++) {
      $changes[$i] = $homepoints[$i] - $awaypoints[$i];
      $homeline .= '  ';
      if ($changes[$i] > 9) {
        $homeline .= '<span style="background-color: lime; font-weight: bold;">';
        $changes[$i] = 3;
      }
      elseif ($changes[$i] > 5) {
        $homeline .= '<span style="background-color: yellow; font-weight: bold;">';
        $changes[$i] = 2;
      }
      elseif ($changes[$i] > 1) {
        $homeline .= '<span style="background-color: cyan; font-weight: bold;">';
        $changes[$i] = 1;
      }
      elseif ($changes[$i] > -2)
        $changes[$i] = 0;

      $homeline .= sprintf('%2s', $homepoints[$i]);
      if ($changes[$i] > 0) $homeline .= '</span>';
      $hometotal += $homepoints[$i];
    }
    $homeline .= sprintf('%6s', $hometotal) . ' (' . ($hometotal - $homeworepl) . ')';

    $awayline = $awayteam . str_repeat(' ', 20 - mb_strlen($awayteam));
    $awaytotal = 0;
    for ($i=0; $i<10; $i++) {
      $awayline .= '  ';
      if ($changes[$i] < -9) {
        $awayline .= '<span style="background-color: lime; font-weight: bold;">';
        $changes[$i] = -3;
      }
      elseif ($changes[$i] < -5) {
        $awayline .= '<span style="background-color: yellow; font-weight: bold;">';
        $changes[$i] = -2;
      }
      elseif ($changes[$i] < -1) {
        $awayline .= '<span style="background-color: cyan; font-weight: bold;">';
        $changes[$i] = -1;
      }
      $awayline .= sprintf('%2s', $awaypoints[$i]);
      if ($changes[$i] < 0) $awayline .= '</span>';
      $awaytotal += $awaypoints[$i];
    }
    $awayline .= sprintf('%6s', $awaytotal) . ' (' . ($awaytotal - $awayworepl) . ')';

    // 0 - хозяева в защите, 2 - в атаке
    $texts = array(
0 => array(
 0 => 'В добавленное время еще один мяч влетает в сетку ворот хозяев!',
 1 => 'Гости успешно завершают атаку! Хозяева неудачно разыгрывают мяч и гости снова в атаке.',
 2 => 'Гости успешно завершают атаку! Хозяева начинают с центра поля.',
 3 => 'Атака гостей продолжается.',
 4 => 'Хозяева отодвигают мяч в центр поля.',
 5 => 'Хозяева проводят контратаку.',
 6 => 'Острая контратака хозяев приводит к взятию ворот. Гости начинают с центра поля.',
 ),
1 => array(
 0 => 'Гости атакут и забивают! Хозяева теряют мяч после розыгрыша в центре и вынуждены снова обороняться.',
 1 => 'Гости атакут и забивают! Хозяева начинают с центра поля.',
 2 => 'Гости переходят в атаку.',
 3 => 'Команды поочерёдно контролируют мяч в центре поля.',
 4 => 'Хозяева переходят в атаку.',
 5 => 'Хозяева атакут и забивают! Гости начинают с центра поля.',
 6 => 'Хозяева атакут и забивают! Гости теряют мяч после розыгрыша в центре и вынуждены снова обороняться.',
 ),
2 => array(
 0 => 'Острая контратака гостей приводит к взятию ворот. Хозяева начинают с центра поля.',
 1 => 'Гости проводят контратаку.',
 2 => 'Гости отодвигают мяч в центр поля.',
 3 => 'Атака хозяев продолжается.',
 4 => 'Хозяева успешно завершают атаку! Гости начинают с центра поля.',
 5 => 'Хозяева успешно завершают атаку! Гости неудачно разыгрывают мяч и хозяева снова в атаке.',
 6 => 'В добавленное время еще один мяч влетает в сетку ворот гостей!',
 )
);
    $position = 1;
    $goalh = 0;
    $goala = 0;
    $protocol = '<b> Протокол матча</b><br /><br />';
    $half1 = true;
    for ($i=0; $i<=$lastplayed; $i++) {
      if (isset($replaces[$i+1])) {
        ($i == 5) ? $protocol .= '<b>В перерыве</b> тренер'
                  : $protocol .= '<b>' . ($i * 9 + 1) . ' минута</b>: замен';
        if (isset($replaces[$i+1]['home']) && isset($replaces[$i+1]['away'])) {
          $out = '';
          $in = '';
          foreach ($replaces[$i+1]['home'] as $p) {
            if ($p < 6) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          ($i == 5) ? $protocol .= 'ы обеих команд внесли изменения в составы:<br />- у хозяев вместо' . ltrim($out, ',') . ' на поле '
                    : $protocol .= 'ы в обеих командах:<br />- у хозяев вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
          $out = '';
          $in = '';
          foreach ($replaces[$i+1]['away'] as $p) {
            if ($p < 6) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          $protocol .= '- у гостей вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
        elseif (isset($replaces[$i+1]['home'])) {
          $out = '';
          $in = '';
          foreach ($replaces[$i+1]['home'] as $p) {
            if ($p < 6) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          ($i == 5) ? $protocol .= ' хозяев внёс изменение в состав: вместо<b>' . ltrim($out, ',') . '</b> на поле '
                    : $protocol .= 'а в составе хозяев: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
        else {
          $out = '';
          $in = '';
          foreach ($replaces[$i+1]['away'] as $p) {
            if ($p < 6) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          ($i == 5) ? $protocol .= ' гостей внёс изменение в состав: вместо' . ltrim($out, ',') . ' на поле '
                    : $protocol .= 'а в составе гостей: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
      }
     if ($rprognoz[$i*2] != '?') {
      $diff = $changes[$i];
      if ($i < 9 && $position == 0 && $diff == -3) $diff = -2;
      if ($i < 9 && $position == 2 && $diff == 3) $diff = 2;
      $newposition = $position + $diff;
      if ($diff == -3) $diff = -2;
      if ($diff == 3) $diff = 2;
      $min = 1 + min(8, abs($homepoints[$i] - $awaypoints[$i]));
      $protocol .= '<b>' . ($i * 9 + $min) . ' минута</b> (' . $mdp[$i+1]['home'] . ' - ' . $mdp[$i+1]['away'] . '): ' . $texts[$position][$diff+3];
      $position = $newposition;
      if ($position < 0) { // отличившиеся у гостей
        $tarr = array();
        foreach($players['away'] as $p => $pdata) $tarr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
//        foreach($players['away'] as $p => $pdata) $tarr[$p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
        krsort($tarr, SORT_NUMERIC);
        $gn = current($tarr);
        $an = next($tarr);
        $goaleador = $players['away'][$gn]['name'];
        $assistant = $players['away'][$an]['name'];
        $goala ++;
        $protocol .= '<br /><b> Счёт матча становится ' . $goalh . '-' . $goala . '.</b> Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b>';
        for ($j=$i; $j<=$lastplayed; $j++) {
          $players['away'][$gn]['psumm'][$j] = $players['away'][$gn]['psumm'][$j] - 2;
          $players['away'][$an]['psumm'][$j] = $players['away'][$an]['psumm'][$j] - 1;
        }
      }
      elseif ($position > 2) { // отличившиеся у хозяев
        $tarr = array();
        foreach($players['home'] as $p => $pdata) $tarr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
//        foreach($players['home'] as $p => $pdata) $tarr[$p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
        krsort($tarr, SORT_NUMERIC);
        $gn = current($tarr);
        $an = next($tarr);
        $goaleador = $players['home'][$gn]['name'];
        $assistant = $players['home'][$an]['name'];
        $goalh++;
        $protocol .= '<br /><b> Счёт становится ' . $goalh . '-' . $goala . '.</b> Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b>';
//print_r($players['home'][$gn]); echo '<br />';
        for ($j=$i; $j<=$lastplayed; $j++) {
          $players['home'][$gn]['psumm'][$j] = $players['home'][$gn]['psumm'][$j] - 2;
          $players['home'][$an]['psumm'][$j] = $players['home'][$an]['psumm'][$j] - 1;
        }
//print_r($players['home'][$gn]); echo '<br />';
      }
      switch ($position) {
        case -3:
          $protocol .= '<br /><b>90+ минута</b> (' . $mdp[$i+1]['home'] . ' - ' . $mdp[$i+1]['away'] . ') ' . $texts[0][0];
          $tarr = array();
          foreach($players['away'] as $p => $pdata) $tarr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
//          foreach($players['away'] as $p => $pdata) $tarr[$p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
          krsort($tarr, SORT_NUMERIC);
          $goaleador = $players['away'][current($tarr)]['name'];
          $assistant = $players['away'][next($tarr)]['name'];
          $protocol .= ' Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b>';
          $goala ++;
          $protocol .= '<br /><b> Окончательный счёт матча: ' . $goalh . '-' . $goala . '.</b>';
          break;
        case -2:
          $position = 0;
          break;
        case -1:
          $position = 1;
          break;
        case  3:
          $position = 1;
          break;
        case  4:
          $position = 2;
          break;
        case  5:
          $protocol .= '<br /><b>90+ минута</b> (' . $mdp[$i+1]['home'] . ' - ' . $mdp[$i+1]['away'] . ') ' . $texts[2][6];
          $tarr = array();
          foreach($players['home'] as $p => $pdata) $tarr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
//          foreach($players['home'] as $p => $pdata) $tarr[$p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
          krsort($tarr, SORT_NUMERIC);
          $goaleador = $players['home'][current($tarr)]['name'];
          $assistant = $players['home'][next($tarr)]['name'];
          $protocol .= ' Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b>';
          $goalh ++;
          $protocol .= '<br /><b> Окончательный счёт матча: ' . $goalh . '-' . $goala . '.</b>';
          break;
        default;
          break;
      }
      if ($half1 && $lastplayed >= 4 && $i >= 4) {
        $half1 = false;
        $position = 1;
        $protocol .= '<br />
<b> Звучит свисток на перерыв.';
        if ($newposition >= 0 && $newposition <= 2) $protocol .= ' Счёт первого тайма: ' . $goalh . '-' . $goala . '.';
        $protocol .= '</b>';
      }
      $protocol .= '<br />
';
     }
    }
    if ($lastplayed >= 9) {
      $protocol .= ' <b>Финальный свисток - матч закончился';
      if ($newposition >= 0 && $newposition <= 2) $protocol .= ' со счётом ' . $goalh . '-' . $goala;
      $protocol .= '.</b>';
    }
    $protocol .= '<br />
<hr>
';

    $prognozlist .= '
' . $homelist . '
<b>' . $homeline . '</b>
<b>' . $awayline . '</b>

' . $awaylist . '</div>
<div>';
    $match_title .= '  '.$goalh.':'.$goala.' ('.$hometotal.'-'.$awaytotal.')</b>';
    if (isset($link) && trim($link)) $match_title .= ' &nbsp; <a href="http://pred.su/predm.php/'.$link.'" target="_blank">Матч-центр на PRED.SU</a>';
    if (isset($renew))
      rewrite_cal($prognoz_dir, $line0, $score0, $goalh.':'.$goala.' ('.$hometotal.'-'.$awaytotal.')'."\n");

  }

  // заголовок страницы с формой отправки прогноза
  if ($team_code && !$closed) $head = '
Код тура: <b>'.$tour.'</b>, ник игрока: <b>'.$team_code.'</b><br />
<form action="" name="tform" enctype="multipart/form-data" method="post" class="text15" onsubmit="return show_alert(this);">
прогноз на тур: <input type="text" id="prognoz_str" name="prognoz_str" value="" size="50" />
<input type="hidden" name="team_code" value="'.$team_code.'" />
<input type="submit" name="submitpredict" value=" отправить прогноз " />
</form>
';
  else {
    switch ($l) {
     case 'FWD':
      $tour = ltrim($t, '0');
      $head = '<b>Эксперт-Лига сборных №' . (substr($season, 5, 2) - 14) . '. Тур ' . $tour . '</b><br />';
      break;
     case 'PRE':
      $tour = ltrim($t, '0');
      $spre = str_replace('-', '/', $season);
      $head = '<b>III PREDвидение ' . $spre . '. Тур ' . $tour . '</b><br />';
      break;
     case 'SUP':
      $tour = ltrim($t, '0');
      $spre = str_replace('-', '/', $season);
      $head = '<b>Суперсерия. Матч ' . $tour . '</b><br />';
      break;
     case 'FFP':
      $tour = ltrim($t, '0');
      $sffp = str_replace('-', '/', $season);
//      elseif ($tour == 10) $head = '<b>ФФП-' . $sffp . '. Квалификация в плей-офф/доп.матч за 5 место в группе D (' . $tour . ' тур)</b><br />';
//      elseif ($tour == 11) $head = '<b>ФФП-' . $sffp . '. Квалификация в плей-офф (' . $tour . ' тур)</b><br />';
      if ($tour <= 5) $head = '<b>ФФП-' . $sffp . '. 1 этап, ' . $tour . ' тур</b><br />';
      else if ($tour <= 10) $head = '<b>ФФП-' . $sffp . '. 2 этап, ' . ($tour - 5) . ' тур (' . $tour . ' тур)</b><br />';
//      elseif ($tour <= 15) $head = '<b>ФФП-' . $sffp . '. 1/8 финала, ' . ($tour - 11) . ' матч (' . $tour . ' тур)</b><br />';
//      elseif ($tour <= 20) $head = '<b>ФФП-' . $sffp . '. 1/4 финала, ' . ($tour - 17) . ' матч (' . $tour . ' тур)</b><br />';
//      elseif ($tour <= 22) $head = '<b>ФФП-' . $sffp . '. 1/2 финала, ' . ($tour - 20) . ' матч (' . $tour . ' тур)</b><br />';
//      else $head = '<b>ФФП-' . $sffp . '. Финал и серия за 3 место, ' . ($tour - 22) . ' матч (' . $tour . ' тур)</b><br />';
      else $head = '<b>ФФП-' . $sffp . '. Финальный турнир, ' . ($tour - 10) . ' тур (' . $tour . ' тур)</b><br />';
      break;
     case 'PRO':
      $tour = ltrim($t, '0');
      $spro = substr($season, 0, 4);
      if ($tour <= 5) $head = '<b>PROFI OPEN ' . $spro . '. Групповой этап. Тур ' . $tour . '</b><br />';
      else if ($tour <= 10) $head = '<b>PROFI OPEN ' . $spro . '. 2-й групповой этап. Тур ' . ($tour - 5) . ' (' . $tour . ' тур)</b><br />';
//      elseif ($tour == 8) $head = '<b>PROFI OPEN ' . $spro . '. 1/8 финала</b><br />';
//      elseif ($tour == 9) $head = '<b>PROFI OPEN ' . $spro . '. 1/4 финала</b><br />';
//      elseif ($tour == 10) $head = '<b>PROFI OPEN ' . $spro . '. 1/2 финала</b><br />';
//      elseif ($tour == 11) $head = '<b>PROFI OPEN ' . $spro . '. Финал</b><br />';
      else $head = '<b>PROFI OPEN ' . $spro . '. Финальный турнир. Тур ' . ($tour - 10) . ' (' . $tour . ' тур)</b><br />';
      break;
     case 'SPR':
      $tour = ltrim($t, '0');
      if ($tour <= 5) $head = '<b>XII Спартакиада-2018. Этап 1. Тур ' . $tour . '</b><br />';
//      elseif ($tour <= 10) $head = '<b>XII Спартакиада-2018. Этап 2 Тур ' . ($tour - 5) . '</b><br />';
      else $head = '<b>XII Спартакиада-2018. Финал. Тур ' . ($tour - 7) . '</b><br />';
      break;
     case 'TOR':
      $tour = ltrim($t, '0');
      $stor = str_replace('-', '/', $season);
      if ($tour <= 7) $head = '<b>Групповой этап Лиги КСП «Торпедо»" - ' . $stor . '. Тур №' . $tour . '</b><br />';
      elseif ($tour <= 20) $head = '<b>Лига КСП «Торпедо»" - 2017/18. 1 стадия финального этапа. ' . ($tour - 7) . ' тур</b><br />';
      break;
    }
  }

  if ($closed)
    switch ($l) {
//      case 'FFP': $hint = '<p> <a href="/?a=sfp-team&amp;m=text&amp;ref=news&amp;s='.$season.'&amp;l='.$l.'">Турнирная таблица Фестиваля ФП - 2015/16</a></p>'; break;
//      case 'PRO': $hint = '<p> <a href="/?a=sfp-team&amp;m=text&amp;ref=news&amp;s='.$season.'&amp;l='.$l.'">Турнирная таблица ProfiOpen-2015</a></p>'; break;
      default   : $hint = '';
    }
  else {
    $hint = '<p class="red">Срок отправки прогнозов '.$lastdate.' '.$lasttm.' по времени сервера<br /></p>';
    switch($l) {
      case 'FFP': $match_title .= 'Для всех 11 матчей программки надо угадывать исход.'; break;
      case 'PRE': $match_title .= 'Для всех 10 матчей программки надо угадывать счёт.'; break;
      case 'SUP': $match_title .= 'Для всех 10 матчей программки надо угадывать счёт.'; break;
      case 'SPR': $match_title .= 'Для всех 10 матчей программки надо угадывать исход.<br />
На 2 матча надо сделать дополнительный прогноз.'; break;
      case 'PRO': $match_title .= 'На матчи 1-5 ставить исход, на матчи 6-10 - счёт.'; break;
      case 'FWD': $match_title .= 'В каждом тайме разрешается сделать по 6 ставок.<br />
1-3 наиболее трудных для угадывания матча целесообразно оставить с прочерками,<br />
перенеся дополнительные ставки на более предсказуемые игры!<br />
В каждом тайме можно сделать 1 тройную и 1 двойную ставки.<br />
Чтобы поставить двойную или тройную ставку, отметьте "2" или "3" в последней колонке<br />
("1" сбрасывает кратную ставку).'; break;
      case 'TOR': $match_title .= 'Ставить надо ТОЛЬКО на 10 матчей из 13-ти.<br />
3 наиболее трудных для угадывания матча нужно оставить со звездочками!'; break;
    }
  }

  if ($publish) switch ($l) {
    case 'FFP': $rules = 'Правило расчёта голов: разница в 1-3 исхода = 1 гол, 4-6 исходов = 2 гола,
далее - плюс 1 гол за каждое увеличение разницы.'; break;
    case 'SUP':
    case 'PRE': $rules = 'Начисление баллов игрокам: <span style="background-color: lime; font-weight: bold;">угадан счёт</span> = 4 очка,
<span style="background-color: yellow; font-weight: bold;">угадана разница мячей</span> = 3 очка,
<span style="background-color: cyan; font-weight: bold;">угадан исход</span> = 2 очка.<br />
Баллы всех игроков основного состава (их прогнозы подчёркнуты) за каждый реальный матч (МдП) суммируются.<br />
Суммы показаны в строках команд между таблицами игроков (в конце - общий балл команды и эффективность замен).<br />
Возможные позиции команд: <b>защита - центр - атака - гол</b> (после гола и в начале таймов мяч находится в центре).<br>
Сравнение сумм определяет положение на поле: преимущество в <span style="background-color: cyan; font-weight: bold;">2-5</span>
баллов приносит 1 позиционное изменение;<br />
<span style="background-color: yellow; font-weight: bold;">6-9</span> баллов - два;
<span style="background-color: lime; font-weight: bold;">10 и более</span> баллов - три позиционных изменения, но два гола засчитываются только на последнем МдП.<br />
Примечание: авторы и ассистенты забитых голов определяются так, как сказано в Регламенте турнира, поэтому возможны<br />
расхождения с официальными данными, для которых, очевидно, использован алгоритм, отличный от описанного в Регламенте.'; break;
    case 'PRO': $rules = 'Угаданные исходы в первом тайме подсвечены <span style="background-color: lime; font-weight: bold;">зелёным цветом</span>.
Во втором тайме подсвечиваются удачно предсказанные счета матчей:
<span style="background-color: lime; font-weight: bold;">угадан счёт</span> = 4 очка, <span style="background-color: yellow; font-weight: bold;">угадана разница мячей</span> = 3 очка, <span style="background-color: cyan; font-weight: bold;">угадан исход</span> = 2 очка. 
Начисление голов:
- в 1-м тайме: разница в 1-2 исхода = 1 гол, 3-4 исхода = 2 гола, 5 исходов = 3 гола.
- во 2-м тайме: разница в 2-5 очков = 1 гол, 6-10 очков = 2 гола, &gt;10 очков - 3 гола.
Очки второго тайма в оценке за матч учитываются с коэффициентом 0.4'; break;
    case 'TOR': $rules = 'Правило расчёта голов: разница в 1-3 исхода = 1 гол, 4-5 исходов = 2 гола,
6-7 исходов - 3 гола, 8-10 исходов - 4 гола.'; break;
    case 'SPR': $rules = 'Правило расчёта голов: разница в 1-2 исхода = 1 гол, 3-5 исходов = 2 гола,
6-8 исходов - 3 гола, 9-10 исходов - 4 гола.'; break;
    default   : $rules = '';
  }

//$prognozlist = '<h2>' . $match_title .'</h2>' . $prognozlist;
if (isset($updates))
  echo $prognozlist; // REST responce on event 'FT'
else
  echo '
<link href="css/prognoz.css" rel="stylesheet">
<script>//<![CDATA[
var '.date('\h\o\u\r\s=G,\m\i\n\u\t\e\s=i,\s\e\c\o\n\d\s=s',time()).',sendfp=false,base=[],mom=[]
' . $id_arr . '
function getDate(){if(seconds<59)seconds++;else{seconds=0;if(minutes<59)minutes++;else{minutes=0;hours=hours<23?hours+1:0}};var s=seconds+"",m=minutes+"";if(s.length<2)s="0"+s;if(m.length<2)m="0"+m;$("#timedisplay").html("время сервера: "+hours+":"+m+":"+s)}
setInterval(getDate, 1000);
function newpredict(){
	var p="";
	for(i=1;i<='.$imax.';i++){
		p+=(ps=$("#dice"+i).val())?ps:"' . ($l == 'FWD' ? '-";
		if(j=$("#ddice"+i).val())while(1<j--)p+=ps;
		p+=",";' : '*";') . ($l == 'SPR' ? '
		p+=(ps=$("#ddice"+i).val())?"("+ps+")":"";' : '') . '
	}
	$("#prognoz_str").val(p);
}
function predict(id,dice){$("#"+id).val(dice);newpredict()}
function securedice(id,dice){
	for(i=1;i<='.$imax.';i++){
		if((dd="#ddice"+i)=="#"+id){
			$(dd).prop("disabled",false);
			$(dd).val(dice);
		}
//		else $(dd).prop("disabled",true);
	}
	newpredict();
}
function show_alert() {
	s=$("#prognoz_str").val();' . ($l == 'SPR' ? '
	if(r=(s.match(/[(]/gi).length==2&&s.match(/[*]/gi).length==0)||confirm("В вашем прогнозе остались незаполненные позиции или количество двойных ставок не равно 2.' : $l == 'TOR' ? '
	if(r=(s.match(/[*]/gi).length==3||confirm("В вашем прогнозе количество звёздочек не равно 3.' : '
	if(r=(s.match(/[*]/gi).length==0||confirm("В прогнозе остались незаполненные позиции.') . '\nВы действительно хотите отправить его в таком виде?")))document.forms[0].submit();
	return r;
}
momup=function(i){clearInterval(mom[i]);mom[i]=setInterval(function(){if(!isNaN(base[i][3])){tm=+base[i][3];base[i][3]=(tm==45||tm==90)?tm+"+":++tm;row=$("#"+i)[0];row.cells[5].innerHTML="<span class=\"blink\">"+base[i][3]+"’</span>"}},60000);}
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
		row.cells[5].innerHTML=(s=="HT"||s=="SP")?"<span class=\"red\">"+s+"</span>":(s=="?"||s=="PP")?"<span>"+s+"</span>":(s=="FT")?"<span>"+((h==a)?0:(h>a)?1:2)+"</span>":"<span class=\"blink\">"+s+"’</span>"
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
		else if(t<6)out+="<img src=\"https://www.livescore.bz/img/N"+(t<4?"k":"s")+"kart.gif\" height=\"12\" />";
		else out+="<img src=\"https://www.livescore.bz/img/sub.gif\" height=\"12\" />";
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
socket=io.connect("//score2live.net:1998",{"reconnect":true,"reconnection delay":500,"max reconnection attempts":20,"secure":true})
socket.on("connect",function(){socket.emit("hellothere")})
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){if(sendfp){$.post("'.$this_site.'",{matches:JSON.stringify(d.data.matches),a:"sfp-team",m:"prognoz",l:"'.$l.'",s:"'.$s.'",t:"'.$t.'"},function(json){$.each(JSON.parse(json),function(idx,obj){base.push(obj.id);base[obj.id]=obj.d})})}$("#statusline").css("display","none")})
socket.on("footdetails",function(data){data=data[0];if ($(".p-table").find("tr[did="+data.id+"]").length)mdetails(data.mdetay,data.id,data.pos1,data.pos2)})
socket.on("guncelleme",function(d){var json="";$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}});if(json.length)$.post("'.$this_site.'",{updates:"["+json+"]",a:"sfp-team",m:"prognoz",l:"'.$l.'",s:"'.$s.'",t:"'.$t.'"'.(isset($n)?',n:"'.$n.'"':'').'},function(html){$("#pl").html(html)})})
//]]></script>
<div style="position:relative;width:100%;margin:0 0 20px 0">
	<div id="statusline" style="position:relative;float:left;text-align:left;display:block">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a></div>
	<div id="timedisplay" style="position:relative;float:right;text-align:right"></div>
</div>
<div style="float:top;width:100%">
	<div class="p-head">' . $head . '</div>
	<div class="p-table">' . $program_table . '</div>
	<div class="p-hint">' . $hint . '</div>
</div>
<div style="text-align:left;width:100%;margin-left:20px">
	<h2>' . $match_title . '</h2>
	<div id="pl" style="margin-right:0px;white-space:pre;font-family:monospace;font-size:14px;float:left;">' . $prognozlist . '</div>
	<div id="mt" class="text14" style="float:right;width:33%">
		<h2>Матчи тура:</h2>
		<br />' . $cal . '
	</div>
</div>
<div style="clear:both"></div>
<div id="pr" class="p-left">' . $protocol . '</div>
<div class="p-left">' . $rules . '</div>
<div style="clear:both;"></div>
';
}
?>
