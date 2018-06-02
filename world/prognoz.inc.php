<?php
define('MAIN_SIZE', '5');

function team_ccode($team) {
  return strtr($team, [
    'Англия' => 'ENG',
    'Белоруссия' => 'BLR',
    'Испания' => 'ESP',
    'Франция' => 'FRA',
    'Германия' => 'GER',
    'Италия' => 'ITA',
    'Голландия' => 'NLD',
    'Португалия' => 'PRT',
    'Россия' => 'RUS',
    'Шотландия' => 'SCO',
    'Украина' => 'UKR',
    'КСП «Торпедо»' => 'КСП «Торпедо»',
    'Onedivision' => 'Onedivision',
    'PrimeGang' => 'PrimeGang',
    'КЛФП Харьков' => 'КЛФП Харьков',
  ]);
}

function ccode_team($ccode) {
  return strtr($ccode, [
    'ENG' => 'Англия',
    'BLR' => 'Белоруссия',
    'ESP' => 'Испания',
    'FRA' => 'Франция',
    'GER' => 'Германия',
    'ITA' => 'Италия',
    'NLD' => 'Голландия',
    'PRT' => 'Португалия',
    'RUS' => 'Россия',
    'SCO' => 'Шотландия',
    'UKR' => 'Украина',
    'КСП «Торпедо»' => 'КСП «Торпедо»',
    'Onedivision' => 'Onedivision',
    'PrimeGang' => 'PrimeGang',
    'КЛФП Харьков' => 'КЛФП Харьков',
  ]);
}

// добавление результатов тура
function rewrite_cal($prognoz_dir, $line0, $score0, $score) {
  $cal = file_get_contents($prognoz_dir.'/cal');
  $line9 = str_replace(';'.$score0."\n", ';'.$score, $line0);
  $cal = str_replace($line0, $line9, $cal);
  file_put_contents($prognoz_dir.'/cal', $cal);
}

// парсинг программок
function parse_program($program_file) {
  $program = file_get_contents($program_file);
  $program = str_replace(')-', ') - ', $program);
  $fr = strpos($program, ' 1.') - strlen($program);
  $fr = strrpos($program, "\n", $fr) + 1;
  $program = substr($program, $fr);
  $fr = strpos($program, 'Последний с');
  $matches = explode("\n", substr($program, 0, $fr));
  $program = substr($program, $fr);
  $fr = strpos($program, '.');
  $date = trim(substr($program, $fr - 2, 5));
  $time = ($fr1 = strpos($program, ':', $fr)) && ($fr1 - $fr < 50) ? trim(substr($program, $fr1 - 2, 5)) : '';
  return array($matches, $date, $time, $program);
}

function players_table($array, $side, $coach, $rprognoz, $half2) {
  global $replaces;
  global $players;
  $allpoints = array(0,0,0,0,0,0,0,0,0,0);
  $list = '';
  $worepl = 0;
  for ($p=0; $p<sizeof($array); $p++) {
    list($name, $prog, $time, $repl) = explode(';', trim($array[$p]));
    if ($prog) {
      $players[$side][$p]['name'] = $name;
      $players[$side][$p]['goal'] = $players[$side][$p]['pass'] = 0;
      if ($p < MAIN_SIZE) {
        if (trim($repl))
          $players[$side][$p]['repl'] = 4; // для подсчёта таймов
        else
          $replaces[4][$side][] = $p; // для отображения замен в протоколе
      }
      else if ($repl == 4) {
        $replaces[4][$side][] = $p;
        $players[$side][$p]['repl'] = 4;
      }
      $prognozColored = ' ';
      $points = 0;
      $pointm = 0;
      $main = false;
      for ($i=0; $i<6; $i++) {
        if ($rprognoz[$i] != '?') {
          switch ($rprognoz[$i]) {
            case  1 : $index = 0; break;
            case  0 : $index = 1; break;
            case 'X': $index = 1; break;
            case  2 : $index = 2; break;
            case '-': $index = -1; break;
            case '=': $index = -1; break;
          }
        }
        $point = $players[$side][$p]['maxpts'][$i] = 0;
        for ($j=0; $j<3; $j++) if (isset($prog[$i * 3 + $j])) {
          if ($p < MAIN_SIZE ) {
            if (!$i && !$j) {
              $prognozColored .= '<u>';
              $main = true;
            }
            else if (($coach || $half2) && $repl && $i == 3 && !$j) {
              $prognozColored .= '<u>';
              $main = true;
            }
            else if (!$coach && !$half2 && $i == 3 && !$j) {
              $prognozColored .= '<u>';
              $main = true;
            }
          }
          else if (($coach || $half2) && $repl && $i == 3 && !$j) {
            $prognozColored .= '<u>';
            $main = true;
          }
          if ($rprognoz[$i] != '?' && $rprognoz[$i] != '-' && $rprognoz[$i] != '=' && $j == $index) {
            $prognozColored .= '<span style="background-color: lime; font-weight: bold;">'.$prog[$i * 3 + $j].'</span>';
            $point = $prog[$i * 3 + $j];
            $players[$side][$p]['maxpts'][$i] = max($players[$side][$p]['maxpts'][$i], $prog[$i * 3 + $j]);
          }
          else {
            $prognozColored .= $prog[$i * 3 + $j];
            $players[$side][$p]['maxpts'][$i] = max($players[$side][$p]['maxpts'][$i], $prog[$i * 3 + $j]);
          }
          if (($i == 2 && $j == 2 && $p < MAIN_SIZE) || ($i == 5 && $j == 2 && $main))
            $prognozColored .= '</u>';

        }
        else $prognozColored .= ' ';

        $prognozColored .= ' ';
        $players[$side][$p]['points'][$i] = $point;
        $points += $point;
        if ($main) {
          $allpoints[$i] += $point;
          $pointm += $point;
        }
        ($i) ? $players[$side][$p]['psumm'][$i] = $players[$side][$p]['psumm'][$i - 1] + $point
             : $players[$side][$p]['psumm'][$i] = $point;

        if ($i == 2) {
          if ($main) $main = false;
          if ($half2)
            $prognozColored .= ' <input type="checkbox" disabled="disabled" '.($repl ? 'checked="checked"' : '').' />  ';
          else if ($coach)
            $prognozColored .= ' <input type="checkbox" name="'.$name.'" '.($repl ? 'checked="checked"' : '').' />  ';
          else
            $prognozColored .= ' <input type="checkbox" disabled="disabled" '.($p < MAIN_SIZE ? 'checked="checked"' : '').' />  ';
        }
      }
      if ($main) $prognozColored .= '</u>';
      $list .= $name . str_repeat(' ', 21 - mb_strlen($name)) . $prognozColored . sprintf('%5s', $points) . ' (' . $pointm . ")\n";
      if ($p < MAIN_SIZE) $worepl += $points;
    }
  }
  return [$allpoints, $list, $worepl];
}

// текст в начале МдП - следует разнообразить рандомом
function auto_preambula($position, $min) {
  return ($min == 1 || $min == 46 ? 'Мяч ' : 'Игра продолжается ') . ($position == 0 ? 'в центре поля.' : 'на половине поля ' . ($position > 0 ? 'гостей.' : 'хозяев.'));
}

function auto_comment($position, $newposition, $min, $home, $away) {
  global $teams;
    // <0 - хозяева в защите, >0 - в атаке
    $texts = array(
-1 => array(
 -2 => [
'Этот гол напрашивался.',
'Вынимай! Вины голкипера в пропущенном мяче нет.',
'Гости успешно завершают атаку!',
'Мяч, преодолев последнее препятствие между ногами вратаря, влетает в ворота.',
'Вот это гол, вот это гол, голешник!',
       ],
 -1 => [
'Нападающие гостей прописались в штрафной хозяев.',
'Гости обстучали уже все штанги. Нужен гол.',
'игрок гостей отправил мяч в штрафную, но оборона хозяев отвела угрозу от ворот. Гости получают право на угловой.',
'игрок гостей навешивает, чтобы создать идеальную позицию для удара, но защитник хозяев успевает выбить мяч. Арбитр указал на угловой.',
'Неплохая возможность забить! игрок гостей находит возможность для удара, но один из защитников бросился под мяч и спас ворота. Гости получают право на угловой удар.',
'',
       ],
  0 => [
'Хозяева отодвигают мяч в центр поля.',
'Хозяева отразили атаку.',
'игрок гостей принял неплохое решение - навесить в штрафную, но перестарался: мяч покинул пределы поля. Хозяева вводят мяч в игру ударом от ворот.',
'игрок гостей исполнил навес в штрафную. Хороший навес, но защитники надежны в этом эпизоде, и мяч выбит подальше.',
'игрок гостей сделал хороший наброс в штрафную, но защитник хозяев сыграл на опережение и выбил мяч.',
'игрок гостей навешивает в штрафную, но там защитники сыграли лучше нападающих. Мяч выбит.',
       ],
  1 => [
'Хозяева проводят контратаку.',
'Пас через все поле, который сделал игрок хозяев оказался не очень точным. Угловой в исполнении хозяев.',
'игрок хозяев убежал в сольный проход, но не сумел создать опасный момент и ждёт подкрепления на подходе к штрафной гостей.',
'игрок хозяев делает длинный и точный пас поближе к штрафной гостей. Может получиться опасный момент.',
       ],
  2 => [
'Не забиваешь ты - забивают тебе: хозяева проводят успешную контратаку.',
'Острая контратака хозяев приводит к взятию ворот.',
'игрок хозяев делает удивительный забег, итогом которого становится точный удар из штрафной.',
       ],
 ),
0 => array(
 -2 => [
'Острая атака гостей и... ГОООООООООООООООЛ голголголголгол!',
'Гости опасно атакуют и мяч в сетке!',
'Гости забивают как на тренировке!',
'Дальний удар по воротам хозяев. Снаряд летит точно в цель!',
       ],
 -1 => [
'Гости переходят в атаку.',
'игрок гостей делает хороший навес в штрафную хозяев.',
'игрок гостей обострил ситуацию, сделав удачный прострел в штрафную хозяев.',
'Хороший дриблинг показывает игрок гостей, прорываясь в штрафную хозяев.',
       ],
  0 => [
'Команды уверенно контролируют середину поля.',
'Мяч не покидает центр поля',
'Игроки делают длинные передачи друг другу и ждут ошибки соперника, чтобы обострить игру.',
       ],
  1 => [
'Хозяева переходят в атаку.',
'игрок хозяев делает хороший навес в штрафную гостей.',
'игрок хозяев обострил ситуацию, сделав удачный прострел в штрафную гостей.',
'Хороший дриблинг показывает игрок хозяев, прорываясь в штрафную гостей.',
       ],
  2 => [
'Острая атака хозяев и... ГОООООООООООООООЛ голголголголгол!',
'Хозяева опасно атакуют, и мяч в сетке!',
'Хозяева забивают как на тренировке!',
'Дальний удар по воротам гостей. Снаряд летит точно в цель!',
       ],
 ),
1 => array(
 -2 => [
'Не забиваешь ты - забивают тебе: гости проводят успешную контратаку.',
'Острая контратака гостей приводит к взятию ворот.',
'игрок гостей делает удивительный забег, итогом которого становится точный удар из штрафной.',
       ],
 -1 => [
'Гости проводят контратаку.',
'Пас через все поле, который сделал игрок гостей оказался не очень точным. Угловой в исполнении гостей.',
'игрок гостей убежал в сольный проход, но не сумел создать опасный момент и ждёт подкрепления на подходе к штрафной хозяев.',
'игрок гостей делает длинный и точный пас поближе к штрафной хозяев. Может получиться опасный момент.',
       ],
  0 => [
'Гости отодвигают мяч в центр поля.',
'Гости отразили атаку.',
'игрок хозяев принял неплохое решение - навесить в штрафную, но перестарался: мяч покинул пределы поля. Гости вводят мяч в игру ударом от ворот.',
'игрок хозяев исполнил навес в штрафную. Хороший навес, но защитники надежны в этом эпизоде, и мяч выбит подальше.',
'игрок хозяев сделал хороший наброс в штрафную, но защитник гостей сыграл на опережение и выбил мяч.',
'игрок хозяев навешивает в штрафную, но там защитники сыграли лучше нападающих. Мяч выбит.',
       ],
  1 => [
'Нападающие хозяев прописались в штрафной гостей.',
'Хозяева обстучали уже все штанги. Нужен гол.',
'игрок хозяев отправил мяч в штрафную, но оборона гостей отвела угрозу от ворот. Хозяева получают право на угловой.',
'игрок хозяев навешивает, чтобы создать идеальную позицию для удара, но защитник гостей успевает выбить мяч. Арбитр указал на угловой.',
'Неплохая возможность забить! игрок хозяев находит возможность для удара, но один из защитников бросился под мяч и спас ворота. Хозяева получают право на угловой удар.',
       ],
  2 => [
'Этот гол напрашивался.',
'Вынимай! Вины голкипера в пропущенном мяче нет.',
'Хозяева успешно завершают атаку!',
'Мяч, преодолев последнее препятствие между ногами вратаря, влетает в ворота.',
'Вот это гол, вот это гол, голешник!',
       ],
 )
);
  $comment = $texts[$position][$newposition][$min % count($texts[$position][$newposition])];
  $comment = strtr($comment, [
'игрок хозяев' => $home[0],
'игрок гостей' => $away[0]
  ]);
  if ($min % 3) {
    if (strpos($comment, 'озяев'))
      $comment = strtr($comment, [
'Хозяева' => 'Игроки ' . $teams['home']['team'],
'хозяева' => 'игроки ' . $teams['home']['team'],
'хозяев' => $teams['home']['team'],
      ]);
    else
      $comment = strtr($comment, [
'Гости' => 'Игроки ' . $teams['away']['team'],
'гости' => 'игроки ' . $teams['away']['team'],
'гостей' => $teams['away']['team'],
      ]);
    $comment = strtr($comment, [
'Сборная' => 'Сборной',
'Жемчужина' => 'Жемчужины',
'Мегаспорт' => 'Мегаспорта',
'Эксперты' => 'Экспертов',
    ]);
  }
  return $comment;
}

function goal($side, $i) {
  global $players;
  global $teams;
  global $replaces;
  $arr = array();
  foreach($players[$side] as $p => $pdata)
    if (($i < 3 || !isset($replaces[4][$side])) && $p < MAIN_SIZE) // 1 тайм или не было замен: играют первые MAIN_SIZE
      $arr[12 - $p + 100 * ($pdata['psumm'][$i] - 5 * $pdata['goal'] - 2 * $pdata['pass']) + 10000 * $pdata['points'][$i]] = $p;
    else if ($i > 2 && isset($replaces[4][$side]) && ((in_array($p, $replaces[4][$side]) && $p >= MAIN_SIZE) || (!in_array($p, $replaces[4][$side]) && $p < MAIN_SIZE))) // на поле во 2 тайме
      $arr[12 - $p + 100 * ($pdata['psumm'][$i] - 5 * $pdata['goal'] - 2 * $pdata['pass']) + 10000 * $pdata['points'][$i]] = $p;

  krsort($arr, SORT_NUMERIC);
  $gn = current($arr);
  $an = next($arr);
  $players[$side][$gn]['goal']++;
  $players[$side][$an]['pass']++;
  $goaleador = $players[$side][$gn]['name'];
  $assistant = $players[$side][$an]['name'];
  $teams[$side]['goal']++;
  return array($goaleador, $assistant);
}

function ball($side, $i) {
  global $players;
  global $teams;
  global $replaces;
  $arr = array();
  foreach($players[$side] as $p => $pdata)
    if (($i < 3 || !isset($replaces[4][$side])) && $p < MAIN_SIZE) // 1 тайм или не было замен: играют первые MAIN_SIZE
      $arr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
    else if ($i > 2 && isset($replaces[4][$side]) && ((in_array($p, $replaces[4][$side]) && $p >= MAIN_SIZE) || (!in_array($p, $replaces[4][$side]) && $p < MAIN_SIZE))) // на поле во 2 тайме
      $arr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;

  krsort($arr, SORT_NUMERIC);
  $gn = current($arr);
  $an = next($arr);
  $goaleador = $players[$side][$gn]['name'];
  $assistant = $players[$side][$an]['name'];
  return array($goaleador, $assistant); // на самом деле игроки с мячом
}

// main

//if (isset($_GET['t'])) while(list($k,$v) = each($_GET)) $$k=$v;
//if (substr(getcwd(), -6) == 'online') chdir('..');
$season_dir = $online_dir . 'WL/' . $s;
$tour = 'WLS' . $t;
$prognoz_dir = $season_dir. '/prognoz/' . $tour;
$program_file = $season_dir . '/programs/' . $tour;
$closed = is_file($prognoz_dir.'/closed');
$published = is_file($prognoz_dir.'/published');
$config = json_decode(file_get_contents($season_dir . '/fp.cfg'), true);
$query_string = $_SERVER['QUERY_STRING'];
if ($cut = strpos($_SERVER['QUERY_STRING'], '&n=')) $query_string = substr($query_string, 0, $cut);
if ($published) $query_string = strtr($query_string, ['prognoz' => 'result', '&renew=1' => '']);
//$team_code = isset($_SESSION['Coach_name']) ? $_SESSION['Coach_name'] : '';
$team_code = '';
if (isset($_SESSION['Coach_name'])) {
  foreach ($cmd_db['WL'] as $code => $team)
    if ($team['usr'] == $_SESSION['Coach_name'])
      $team_code = $code;

  $team_code = $team_code ? $team_code : $_SESSION['Coach_name'];
}
if (!isset($renew)) $renew = false;
if (isset($updates)) $renew = true;
else $updates = NULL;
$half2 = $pr_saved = false;
$prognozlist = $rprognoz = $protocol = $cc = $role = $log = '';

if (is_file($program_file)) {
  if ($team_code) {
    $codes = file($season_dir.'/codes.tsv');
    foreach ($codes as $cline) {
      $arr = explode('	', $cline);
      if ($arr[0] == $team_code) {
        $cc = $arr[1];
        $role = $arr[4];
        break;
      }
    }
    $history = []; // история событий (только для тренера)
    $myteam = []; // состав своей команды (только для тренера)
    if ($role == 'coach') foreach ($codes as $cline) {
      $arr = explode('	', $cline);
      if ($arr[1] == $cc) $myteam[] = $arr[0];
    }
    // отправка прогноза
    if (isset($_POST['submitpredict']) && ($prognoz_post = trim($_POST['prognoz_str'])))
      send_predict('WL', $s, $team_code, $tour, $prognoz_post, '', $ip);

    $predicts = file($prognoz_dir.'/mail');
    foreach ($predicts as $predict) {
      list($name, $predict, $timestamp) = explode(';', $predict);
      $all_predicts[$name] = $predict;
      if ($team_code == $name)
        $prognoz_post = $predict;

      if (in_array($name, $myteam))
        $history[$timestamp][] = ['event' => 'predict', 'name' => $name, 'what' => $predict];

    }
    $codes = file($season_dir.'/codes.tsv');
    foreach ($codes as $cline) {
      $arr = explode('	', $cline);
      if ($arr[0] == $team_code) {
        $cc = $arr[1];
        $role = $arr[4];
        break;
      }
    }
    // замены на второй тайм
    if (isset($_POST['replace'])) {
      $out = '';
      $log = 'состав второго тайма:<br />';
      $lines = file($prognoz_dir.'/'.$cc);
      foreach ($lines as $line) if (trim($line)) {
        list($name, $predict, $ts, $rest) = explode(';', trim($line));
        $log .= (strlen($log) > 45 ? ', ' : ' ') . (isset($_POST[$name]) ? '<b>' : '') . $name . (isset($_POST[$name]) ? '</b>' : '');
        $out .= $name.';'.$predict.';'.trim($ts).';'.(isset($_POST[$name]) ? '4' : '').'
';
      }
      $cc_pr = explode("\n", $out);
      file_put_contents($prognoz_dir.'/'.$cc, $out);
      $hisfile = fopen($prognoz_dir.'/.'.$cc, 'a');
      fwrite($hisfile, "$team_code;$log;" . time() . "\n");
      fclose($hisfile);
    }
    // состав на первый тайм
    if (!$closed && isset($_POST['playersorder'])) {
      $out = '';
      $log = 'состав первого тайма:<br />';
      $lines = file($prognoz_dir.'/'.$cc);
      foreach ($lines as $line) if (trim($line)) {
        list($name, $predict, $ts, $rest) = explode(';', trim($line));
        $all_predicts[$name] = $predict;
      }
      $added = [];
      $i = 0;
      $playersorder = explode('&', str_replace('players[]=' , '', $_POST['playersorder']));
      foreach ($playersorder as $pos) {
        $name = $_POST['name-'.$pos];
        $predict = strtr($_POST['playerId-'.$pos], ['‎' => '', ' ' => '']);
        if ($predict && (!isset($all_predicts[$name]) || $all_predicts[$name] != $predict))
          $added[$name] = $predict;

        $log .= (strlen($log) > 45 ? ', ' : ' ') . ($i == 0 ? '<b>' : '') . $name . ($i++ == 5 ? '</b>' : '');
        $out .= "$name;$predict;\n";
      }
      if (count($added)) {
        $log .= '<br /> и при этом внёс прогноз' . (count($added) > 1 ? 'ы' : '');
        foreach ($added as $name => $predict) $log .= " $name:$predict";
      }
      $cc_pr = explode("\n", $out);
      file_put_contents($prognoz_dir.'/'.$cc, $out);
      $pr_saved = true;
      $hisfile = fopen($prognoz_dir.'/.'.$cc, 'a');
      fwrite($hisfile, "$team_code;$log;" . time() . "\n");
      fclose($hisfile);
    }
  }
  list($program_matches, $lastdate, $lasttm, $program) = parse_program($program_file);
  // выборка матчей тура
  $match_title = '';
  $aprognoz = array();
  $teams = array();
  $tour_cal = array();
  $cal = '';
  if (is_file($prognoz_dir.'/cal')) {
    $tour_cal = file($prognoz_dir.'/cal');
    $i = 1;
    foreach ($tour_cal as $cal_line) if ($cal_line = trim($cal_line)) {
      if (!strpos($cal_line, ';')) $cal_line .= ';;;';
      list($line, $link1, $group, $score) = explode(';', $cal_line);
      if (trim($group))
        $cal .= '<p class="text15b">'.$group.'</p>
';
      $cal .= '<a href="?'.$query_string.'&amp;n='.$i;
      if (isset($prognoz_str) && $prognoz_str)
        $cal .='&amp;prognoz_str='.$prognoz_str;

      $cal .= '">'.$line.'</a> '.$score.'<br />
';
      list($home, $away) = explode(' - ', $line);
      if (!isset($n) && ($home == $cc || $away == $cc)) $n = $i;
      if (isset($n) && $n == $i) {
        $teams['home']['cc'] = $home;
        $teams['away']['cc'] = $away;
        $match_title = '<b>' . $line;
        if (trim($link1)) $link = $link1;
        $line0 = $cal_line."\n";
        $score0 = $score;
      }
      $i++;
    }
    if (!isset($n)) {
      $n = 1;
/*
      $teams['home']['cc'] = $home;
      $teams['away']['cc'] = $away;
      $match_title = '<b>' . $line;
      if (trim($link1)) $link = $link1;
      $line0 = $cal_line."\n";
      $score0 = $score;
*/
      list($line0, $link, $group, $score0) = explode(';', trim($tour_cal[0]));
      list($teams['home']['cc'], $teams['away']['cc']) = explode(' - ', $line0);
      $match_title = '<b>' . $line0;
    }
    $teams['home']['team'] = ccode_team($teams['home']['cc']);
    $teams['away']['team'] = ccode_team($teams['away']['cc']);

    $virtmatch = array();
    if ($closed) {
      $home_arr = is_file($prognoz_dir.'/'.$teams['home']['cc']) ? file($prognoz_dir.'/'.$teams['home']['cc']) : array();
      $away_arr = is_file($prognoz_dir.'/'.$teams['away']['cc']) ? file($prognoz_dir.'/'.$teams['away']['cc']) : array();
      $home_players = $away_players = 0;
      $pairs = max(count($home_arr), count($away_arr));
      for ($i=0; $i<$pairs; $i++) {
        if (isset($home_arr[$i]) && trim($home_arr[$i])) {
          list($home, $prog, $rest) = explode(';', trim($home_arr[$i]), 3);
          $aprognoz[$home]['prog'] = $prog;
          $home_players++;
        }
        else $home = '?';
        if (isset($away_arr[$i]) && trim($away_arr[$i])) {
          list($away, $prog, $rest) = explode(';', trim($away_arr[$i]), 3);
          $aprognoz[$away]['prog'] = $prog;
          $away_players++;
        }
        else $away = '?';
        if ($home != '?' || $away != '?') $virtmatch[] = $home.' - '.$away;
      }
    }
  }

  // таблица программки тура
  include ('online/realteam.inc.php');
  include ('online/tournament.inc.php');
  list($last_day, $last_month) = explode('.', $lastdate);
  $dm = $last_day . '.' . $last_month;
  $base = get_results_by_date($last_month, $last_day, $updates);
  if ($renew)
    touch($online_dir.'schedule/task/renew.WLS'.$t);

  $mdp = array();
  $program_table = '<table style="margin:auto">
<tr><th>№</th><th align="left">матч для прогнозирования</th><th>турнир</th><th>дата время</th><th>счёт</th><th>исход</th>' .
(!$published ? '<th>прогноз</th>' : '') .
(!$published && $closed ? '
<td rowspan="8" style="text-align:right;border-top:0">
<br />
для расчёта<br />
вариантов<br />
завершения<br />
матчей выберите в<br />
колонке "прогноз"<br />
возможный исход<br />
и нажмите кнопку<br />
<form action="" name="tform" method="get">
<input type="hidden" name="a" value="sfp-20" />
<input type="hidden" name="m" value="prognoz" />
<input type="hidden" name="s" value="'.$s.'" />
<input type="hidden" name="t" value="'.$t.'" />
<input type="hidden" id="prognoz_str" name="prognoz_str" value="" />
<input type="hidden" name="n" value="'.$n.'" />
<input type="submit" value="что будет, если?" />
</form>
</td>
' : '') . '</tr>
';
  $id_arr = $id_json = '';
  $today_matches = $today_bz = $finished = 0;
  foreach ($program_matches as $line)
    if ($line = trim($line)) { // rows
      if (strpos($line, ' - ')) {
        $divider = (strpos($line, '│') !== false) ? '│' : '|';
        list($pre, $nm, $match, $dm, $post) = explode($divider, $line);
        $nm = rtrim(trim($nm), '.');
        list($match, $tournament) = explode('  ', $match, 2);
        $tournament = trim($tournament);
        list($home, $away) = explode(' - ', trim($match));
        $match = $realteam[$home].' - '.$realteam[$away].'/'.$tourname[$tournament];
        if (!$tournament || !isset($base[$match]))
          $match = $realteam[$home].' - '.$realteam[$away];

        $mt = '-:-'; $rt = '?'; $tn = '??:??'; // по умолчанию информации о счёте и времени матча нет
        if (isset($base[$match])) {
          list($match_date, $match_time) = explode(' ', $base[$match][2]);
          list($match_month, $match_day) = explode('-', $match_date);
          $dm = $match_day . '.' . $match_month;
          $tn = $match_time;
          $st = $base[$match][3];
          $mt = $base[$match][5];
          if (($st != '-') && (($st <= '90') || ($st == 'HT'))) {
            $lastplayed = $nm;
            if ($nm > 3) $half2 = true; // начался матч 2-го тайма
            $today_matches++;
            if ($base[$match][6]) {
              $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"' . $mt . '","' . $st . '"];
';
              $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"' . $mt . '","' . $st . '"]}';
              $today_bz++;
            }
            $mttemp = $mt;
            $mt = '<span class="red">' . $mt . '</span>';
            ($st == 'HT') ? $rt = '<span class="red">' . $st . '</span>' : $rt = '<span class="blink">' . $st . "'" . '</span>';
          }
          else if (($st == 'CAN') || ($st == 'POS') || ($st == 'SUS')) {
              $mt = $st;
              $rt = '-';
              $finished++;
          }
          else if ($st == 'FT') {
            if ($nm > 3) $half2 = true; // сыгран матч 2-го тайма
            $lastplayed = $nm;
            list($gh, $ga) = explode(':', $mt);
            if ($gh == $ga) $rt = 'X';
            else ($gh > $ga) ? $rt = '1' : $rt = '2';
            $finished++;
          }
          else if ($dm == date('d.m')) {
            $today_matches++;
            if ($base[$match][6]) {
              $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"-:-","-"];
';
              $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $base[$match][6] . '","d":[' . $nm . ',0,"-:-","-"]}';
              $today_bz++;
            }
          }
          else if ($match_month.$match_day < date('md')) {
            $mt = $st = 'POS';
            $rt = '-';
          }
          $tr_id = $base[$match][6] ? ' id="' . $base[$match][6] . '"' : '';
        }
        else $tr_id = '';
        $mdp[$nm] = array('home' => $home, 'away' => $away, 'date' => $dm, 'rslt' => $mt, 'case' => $rt);
        if ($nm == 4) // для судоку важно показать разделение таймов
          $program_table .= '<tr><td colspan="'.($published ? 6 : 7).'"></td></tr>';

        $program_table .= '<tr' . $tr_id . '><td align="right">'.$nm.'</td><td style="text-align:left;width:288px' . ($tr_id != '' ? ';cursor:pointer" onClick="details($(this).closest(\'tr\'))' : '') . '">'.$home.' - '.$away.'</td><td align="left">'.$tournament.'</td><td align="right">&nbsp;'.$dm.' '.$tn.'&nbsp;</td><td align="center">&nbsp;'.$mt.'&nbsp;</td><td align="middle">&nbsp;'.$rt.'&nbsp;</td>';

        if ($rt == '1' || $rt == 'X' || $rt == '2') {
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
        if (!$closed) {
            if (!isset($prognoz_post)) $prognoz_post = '==================';
            $program_table .= '
    <td> 1
      <select class="dice" name="'.'dice'.($nm*3-2).'" id="'.'dice'.($nm*3-2).'"><option>=</option>';
            for ($option=1; $option<10; $option++)
              $program_table .= '<option' . ($option == $prognoz_post[$nm*3-3] ? ' selected="selected"' : '') . '>'.$option.'</option>';

            $program_table .= '</select> X
      <select class="dice" name="'.'dice'.($nm*3-1).'" id="'.'dice'.($nm*3-1).'"><option>=</option>';
            for ($option=1; $option<10; $option++)
              $program_table .= '<option' . ($option == $prognoz_post[$nm*3-2] ? ' selected="selected"' : '') . '>'.$option.'</option>';

            $program_table .= '</select> 2
      <select class="dice" name="'.'dice'.($nm * 3).'" id="'.'dice'.($nm * 3).'"><option>=</option>';
            for ($option=1; $option<10; $option++)
              $program_table .= '<option' . ($option == $prognoz_post[$nm*3-1] ? ' selected="selected"' : '') . '>'.$option.'</option>';

            $program_table .= '</select>
    </td>
';
        }
        else if ($closed && !$published)
          $program_table .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','X'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'.'dice'.$nm.'" value="'.$val.'" id="'.'dice'.$nm.'" class="pr_str" '.$onchange.' />
    </td>
';

        $program_table .= '</tr>
';
        if (strlen($rt) > 1) $rt = '?';
        $rprognoz .= $rt;
    }
  }
  $program_table .= '</table>
';

  // use simulated results if any
  if (isset($prognoz_str) && $prognoz_str) {
    for ($i=0; $i<strlen($prognoz_str); $i++) if ($prognoz_str[$i] != '*')
      $rprognoz[$i] = $prognoz_str[$i];

  }

  if ($closed) {
    if (isset($prognoz_str))
      $finished = $lastplayed = strlen(rtrim($prognoz_str, '=')); // чтобы строило протокол и показывало счёт

    $prognozlist = '                       1-й  тайм    на   2-й  тайм   баллы игроков
<b>Правильный результат:  ';
    for ($i = 0; $i < 3; $i++)
      $prognozlist .= $rprognoz[$i] . '   ';

    $prognozlist .= '</b>поле  <b>';

    for ($i = 3; $i < 6; $i++)
      $prognozlist .= $rprognoz[$i] . '   ';

    $prognozlist .= 'команд(замен)</b>
';
    $replaces = array();
    $players = array();
    $homelist = '';
    $homecoach = ($teams['home']['cc'] == $cc && $role == 'coach') ? true : false;
    $awaycoach = ($teams['away']['cc'] == $cc && $role == 'coach') ? true : false;
    if ($homecoach || $awaycoach)
      $homelist .= '<form action="" method="POST"><input type="hidden" name="ccode" value="'.($homecoach ? $teams['home']['cc'] : $teams['away']['cc']).'" />';

    list($homepoints, $templist, $teams['home']['worepl']) = players_table($home_arr, 'home', $homecoach, $rprognoz, $half2);
    $homelist .= $templist;
    list($awaypoints, $awaylist, $teams['away']['worepl']) = players_table($away_arr, 'away', $awaycoach, $rprognoz, $half2);
    if ($homecoach || $awaycoach)
      $awaylist .= '</form>';

    $changes = array();
    $homeline = $teams['home']['team'] . str_repeat(' ', 20 - mb_strlen($teams['home']['team']));
    $teams['home']['total'] = 0;
    for ($i=0; $i<6; $i++) {
      $changes[$i] = $homepoints[$i] - $awaypoints[$i];
      $homeline .= '  ';
      if ($changes[$i] > 17) {
        $homeline .= '<span style="background-color: pink; font-weight: bold;">';
        $changes[$i] = 4;
      }
      else if ($changes[$i] > 12) {
        $homeline .= '<span style="background-color: lime; font-weight: bold;">';
        $changes[$i] = 3;
      }
      else if ($changes[$i] > 7) {
        $homeline .= '<span style="background-color: yellow; font-weight: bold;">';
        $changes[$i] = 2;
      }
      else if ($changes[$i] > 2) {
        $homeline .= '<span style="background-color: cyan; font-weight: bold;">';
        $changes[$i] = 1;
      }
      else if ($changes[$i] > -3)
        $changes[$i] = 0;

      $homeline .= sprintf('%2s', $homepoints[$i]);
      if ($changes[$i] > 0) $homeline .= '</span>';
      $teams['home']['total'] += $homepoints[$i];

      if ($i == 2) {
        if ($homecoach && !$half2)
          $homeline .= '   <input type="hidden" name="replace" value="replace" /><button type="submit" id="replace" style="padding:0" title="установите ".MAIN_SIZE." галочек тем, кто будет
играть во 2 тайме, и нажмите кнопку.
Максимальное количество замен = 3." disabled="disabled"> <i class="fa fa-refresh"></i> </button>';
        else
          $homeline .= '      ';
      }
    }
    $homeline .= sprintf('%7s', $teams['home']['total']) . ' (' . ($teams['home']['total'] - $teams['home']['worepl']) . ')';

    $awayline = $teams['away']['team'] . str_repeat(' ', 20 - mb_strlen($teams['away']['team']));

    $teams['away']['total'] = 0;
    for ($i=0; $i<6; $i++) {
      $awayline .= '  ';
      if ($changes[$i] < -17) {
        $awayline .= '<span style="background-color: pink; font-weight: bold;">';
        $changes[$i] = -4;
      }
      else if ($changes[$i] < -12) {
        $awayline .= '<span style="background-color: lime; font-weight: bold;">';
        $changes[$i] = -3;
      }
      elseif ($changes[$i] < -7) {
        $awayline .= '<span style="background-color: yellow; font-weight: bold;">';
        $changes[$i] = -2;
      }
      elseif ($changes[$i] < -2) {
        $awayline .= '<span style="background-color: cyan; font-weight: bold;">';
        $changes[$i] = -1;
      }
      $awayline .= sprintf('%2s', $awaypoints[$i]);
      if ($changes[$i] < 0) $awayline .= '</span>';
      $teams['away']['total'] += $awaypoints[$i];

      if ($i == 2)
        if ($awaycoach && !$half2)
          $awayline .= '   <input type="hidden" name="replace" value="replace" /><button type="submit" id="replace" style="padding:0" title="установите ".MAIN_SIZE." галочек тем, кто будет
играть во 2 тайме, и нажмите кнопку.
Максимальное количество замен = 3." disabled="disabled"> <i class="fa fa-refresh"></i> </button>';
        else
          $awayline .= '      ';
    }
    $awayline .= sprintf('%7s', $teams['away']['total']) . ' (' . ($teams['away']['total'] - $teams['away']['worepl']) . ')';

    // протокол и счёт
    $position = 0;
    $teams['home']['goal'] = $teams['away']['goal'] = 0;
    $protocol = '<p><b> Протокол матча</b></p>';
    $half1 = true;
    $teams['home']['atack'] = $teams['away']['atack'] = 0;
    for ($i=0; $i<$lastplayed; $i++) {
     if ($rprognoz[$i] != '?') {
      $diff = $changes[$i];

      if ($diff + $position > 0) $teams['home']['atack']++;
      else if ($diff + $position < 0) $teams['away']['atack']++;
      $min = $i * 15 + 1; // минута начала МдП
      $goal = 0; // 0 - нет гола, 1 - гол, 2 - продолжение после гола
      // начало МдП или текст после гола
      $protocol .= '<br /><b>' . $min . ' минута (' . $mdp[$i+1]['home'] . ' - ' . $mdp[$i+1]['away'] . ')</b>: ' . auto_preambula($position, $min);
      $min_diff = abs($homepoints[$i] - $awaypoints[$i]);
      if ($min_diff < 3) $min_diff = 14 - $min_diff;
      $min += $min_diff % 15; // минута события
      do { // пока "мяч" не остановится
        $newposition = $diff > 0 ? min(2, $position + $diff) : max(-2, $position + $diff);
        $diff -= $newposition - $position;
        $protocol .= '<br />
<b>' . $min . ' минута</b>: ' . ($goal && abs($newposition) == 2 ?
'Невероятно! Буквально через минуту еще один мяч влетает в сетку ворот ' . ($newposition == 2 ? 'гостей' : 'хозяев') . '! ':
auto_comment($position, $newposition, $min_diff, ball('home', $i), ball('away', $i)));
        $min = ($min == 90 || $min == 45) ? $min .= '+' : $min + 1; // время продолжения действий на этом МдП
        $min_diff++;
        if ($newposition == -2) { // отличившиеся у гостей
          list ($goaleador, $assistant) = goal('away', $i);
          $goal = 4;  // 4 и 2 = гости забили гол
        }
        elseif ($newposition == 2) { // отличившиеся у хозяев
          list ($goaleador, $assistant) = goal('home', $i);
          $goal = 3;  // 3 и 1 = хозяева забили гол
        }
        if ($goal > 2) {
          $goal -= 2; // чтобы эта ветка не срабатывала при розыгрыше мяча после гола
          $position = 0; // мяч в центре
          $protocol .= '<br />
<b> Счёт ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '.</b>
Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b><br />
' . ($newposition > 0 ? 'Гости' : 'Хозяева') . ($diff == 0 ? ' начинают с центра поля.' : ' теряют мяч после розыгрыша в центре.');
        }
        else $position = $newposition;
      } while ($diff != 0);

      if ($half1 && $lastplayed >= 3 && $i >= 2) {
        $half1 = false;
        $position = 0;
        $protocol .= '
<br />
<br />
<b>Звучит свисток на перерыв.';
        $protocol .= ' Счёт первого тайма: ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '</b>.';
      }
      $protocol .= '<br />
';
     }
      if ($half2 && $i == 2 && isset($replaces[4])) {
        $protocol .= '<b>В перерыве</b> тренер';
        if (isset($replaces[4]['home']) && isset($replaces[4]['away'])) {
          $out = '';
          $in = '';
          foreach ($replaces[4]['home'] as $p) {
            if ($p < MAIN_SIZE) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          $protocol .= 'ы обеих команд внесли изменения в составы:<br />- у хозяев вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
          $out = '';
          $in = '';
          foreach ($replaces[4]['away'] as $p) {
            if ($p < MAIN_SIZE) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          $protocol .= '- у гостей вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
        elseif (isset($replaces[4]['home'])) {
          $out = '';
          $in = '';
          foreach ($replaces[4]['home'] as $p) {
            if ($p < MAIN_SIZE) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          $protocol .= ' хозяев внёс изменение в состав: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
        else {
          $out = '';
          $in = '';
          foreach ($replaces[4]['away'] as $p) {
            if ($p < MAIN_SIZE) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          $protocol .= ' гостей внёс изменение в состав: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br />';
        }
      }
    }

    if ($finished == 6) {
      // лучший игрок матча: все очки, все голы, пасы, позиция, имя, команда
      $c = array();
      foreach ($players as $side => $data) foreach ($data as $pos => $pl) {
        $c['name'][] = $pl['name'];
        $c['team'][] = $teams[$side]['team'];
        $c['pnum'][] = $pos;
        $c['pass'][] = $pl['pass'];
        $c['goal'][] = $pl['goal'];
        $c['summ'][] = $pl['psumm'][5];
      }
      array_multisort($c['summ'], SORT_NUMERIC, SORT_DESC, $c['goal'], SORT_NUMERIC, SORT_DESC, $c['pass'], SORT_NUMERIC, SORT_DESC, $c['pnum'], SORT_NUMERIC, SORT_ASC, $c['name'], $c['team']);
      $protocol .= '
<br />
<b>Финальный свисток - матч завершился со счётом ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '.</b><br />
<br />
Лучшим игроком матча признан <b>'.$c['name'][0].' ('.$c['team'][0].')</b> - баллы: '. $c['summ'][0].($c['goal'][0] ? ', голы: '.$c['goal'][0] : '').($c['pass'][0] ? ', голевые передачи: '.$c['pass'][0] : '').'<br />
';
    }
    $protocol .= '<br />
<hr>
';
    eval('$sites = '.file_get_contents($season_dir.'/sites.inc'));
    $prognozlist .= '
' . $homelist . $sites[$teams['home']['cc']] . '
<b>' . $homeline . '</b>
<b>' . $awayline . '</b>
' . $sites[$teams['away']['cc']] . '<br />' . $awaylist;

$match_title .= '  '.$teams['home']['goal'].':'.$teams['away']['goal'].' ('.$teams['home']['total'].'-'.$teams['away']['total'].')</b>';
    if ($lastplayed && $rprognoz[0] != '?') $match_title .= '
<p style="font-weight:normal;font-size:84%">владение мячом: ' . round(100 * $teams['home']['total'] / ($teams['home']['total'] + $teams['away']['total'])) . ' / ' . round(100 * $teams['away']['total'] / ($teams['home']['total'] + $teams['away']['total'])) . '%
время в атаке: ' . round(100 * $teams['home']['atack'] / $finished) . ' / ' . round(100 * $teams['away']['atack'] / $finished) . '%
</p>';
    if ($renew)
      rewrite_cal($prognoz_dir, $line0, $score0, $teams['home']['goal'].':'.$teams['away']['goal'].' ('.$teams['home']['total'].'-'.$teams['away']['total'].')'."\n");

  }

  $tours = 0;
  foreach($config[0]['этапы'] as $etap) {
    $pre_etaps_tours = $tours;
    $tours += isset($etap['туров']) ? $etap['туров'] : count($etap['туры']);
    if ($t <= $tours)
      break;

  }
  $head = $config[0]['турнир'] . ' ' . $s . '. ' .
    $etap['название'] . '. ' .
    (isset($etap['префикс']) ? $etap['префикс'] : '') .
    ((isset($config[0]['нумерация']) && $config[0]['нумерация'] == 'поэтапная') ? $t - $pre_etaps_tours : $t) .
    (isset($etap['постфикс']) ? $etap['постфикс'] : '');
  if ($team_code && !$closed) $head .= '<br />
<br />
Код тура: <b>WLS'.$t.'</b>, игрок: <b>'.$team_code.'</b><br />
<form action="" name="tform" enctype="multipart/form-data" method="post" class="text15" onsubmit="return show_alert(this);">
прогноз на тур: <input type="text" id="prognoz_str" name="prognoz_str" value="'.(isset($prognoz_post) ? $prognoz_post : '').'" size="50" />
<input type="hidden" name="team_code" value="'.$team_code.'" />
<input type="submit" name="submitpredict" value=" отправить прогноз " />
</form>
';

$coach_form = false;

  if ($closed) {
    $hint = '';
    $rules = '
<br />
Баллы всех игроков основного состава (их прогнозы подчёркнуты) за каждый реальный матч (МдП) суммируются.<br />
Суммы показаны в строках команд между таблицами игроков (в конце - общий балл команды и эффективность замен).<br />
Возможные позиции мяча на поле: <b>защита - центр - атака - гол</b> (после гола и в начале таймов мяч находится в центре).<br>
Разность сумм баллов, набранных командами на МдП, определяет изменение положения на поле: преимущество в<br />
<span style="background-color: cyan; font-weight: bold;">3-7</span> баллов приносит 1 позиционное изменение;
<span style="background-color: yellow; font-weight: bold;">8-12</span> баллов - 2;
<span style="background-color: lime; font-weight: bold;">13-17</span> баллов - 3;
<span style="background-color: pink; font-weight: bold;">18 и более</span> баллов - 4 позиционных изменения.<br />
Для того, чтобы забить гол из положения "атака" достаточно 1-го позиционного изменения, из положения "защита - необходимо<br />
3 изменения. 3 или 4 позиционных изменения могут привести к 2-м забитым голам на одном МдП, если команда находилась в<br />
атаке или в центре/атаке соответственно.';
 if ($role == 'coach') {
  if (is_file($prognoz_dir.'/.'.$cc)) {
    $his = file($prognoz_dir.'/.'.$cc);
    foreach ($his as $his_line) {
      list($name, $what, $timestamp) = explode(';', trim($his_line));
      $history[$timestamp][] = ['event' => 'coach', 'name' => $name, 'what' => $what];
    }
  }
  $hint .= '<a href="" onClick="$(\'#chronology\').toggle(); return false;">показать/скрыть историю поступления прогнозов и управления составом</a>';
  $hint .= '<p id="chronology" class="hidden" style="text-align: left;">';
  foreach ($history as $timestamp => $events) foreach ($events as $event)
    $hint .= date('Y-m-d H:i:s ', $timestamp) . $event['name'] . ($event['event'] == 'predict' ? ' прислал прогноз ' : ' внес изменения в ') . $event['what'] . "<br />\n";

  $hint .= '</p>
';
 }
  }
  else {
    $hint = '<p class="red">Срок отправки прогнозов '.$lastdate.' '.$lasttm.' по времени сервера<br /></p>';
    $rules = '
<br />
В каждом тайме 3 реальных матча, каждый матч имеет 3 возможных исхода - получаем всего 9 возможных исходов в тайме.<br />
На все возможные исходы надо расставить цифры от 1 до 9 так, чтобы каждая цифра использовалась в тайме ровно один раз,<br />
и так, чтобы постараться на угаданных событиях набрать максимальную сумму.<br />
<br />
';
    $match_title = '';
 if ($role == 'coach') {
  if (is_file($prognoz_dir.'/.'.$cc)) {
    $his = file($prognoz_dir.'/.'.$cc);
    foreach ($his as $his_line) {
      list($name, $what, $timestamp) = explode(';', trim($his_line));
      $history[$timestamp][] = ['event' => 'coach', 'name' => $name, 'what' => $what];
    }
  }
  $hint .= '<a href="" onClick="$(\'#chronology\').toggle(); return false;">показать/скрыть историю поступления прогнозов и управления составом</a>';
  $hint .= '<p id="chronology" class="hidden" style="white-space:pre; font-family: monospace; text-align: left;">';
  foreach ($history as $timestamp => $events) foreach ($events as $event)
    $hint .= date('Y-m-d H:i:s ', $timestamp) . $event['name'] . ($event['event'] == 'predict' ? ' прислал прогноз ' : ' внес изменения в ') . $event['what'] . "\n";

  $hint .= '</p>
';
  $coach_form = true;
// если есть, загрузить prognoz/tour/cc
//   обновить в нём прогнозы из all_predicts (если есть обновления, записать!)
// а если нет, загрузить cc.csv и дополнить из all_predicts
  $cc_predicts = [];
  $new = false;

  if (isset($cc_pr)) {
    foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
      list($name, $predict) = explode(';', $pr_line);
      if (isset($all_predicts[$name]) && trim($all_predicts[$name]) && $all_predicts[$name] != $predict) {
        $new = true; // получен обновлённый прогноз
        $cc_predicts[$name] = $all_predicts[$name];
      }
      else
        $cc_predicts[$name] = $predict;
    }
  }
  else if (is_file($prognoz_dir.'/'.$cc)) {
    $cc_pr = file($prognoz_dir.'/'.$cc);
    foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
      list($name, $predict) = explode(';', $pr_line);
      if (isset($all_predicts[$name]) && trim($all_predicts[$name]) && $all_predicts[$name] != $predict) {
        $new = true; // получен обновлённый прогноз
        $cc_predicts[$name] = $all_predicts[$name];
      }
      else
        $cc_predicts[$name] = $predict;
    }
  }
  else {
    $cc_pr = file($season_dir.'/'.$cc.'.csv');
    foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
      list($name, $email) = explode(';', $pr_line);
      if (isset($all_predicts[$name])) {
        $new = true; // получен обновлённый прогноз
        $cc_predicts[$name] = $all_predicts[$name];
      }
      else
        $cc_predicts[$name] = '';

    }
  }
  if ($new) { // перенос новых прогнозов в прогнозы команды
    $out = '';
    foreach ($cc_predicts as $name => $predict)
      $out .= $name.';'.$predict.';;
';
    if (!$pr_saved)
      file_put_contents($prognoz_dir.'/'.$cc, $out);

  }
  $minheight = count($cc_predicts) * 22 + 10;
  $prognozlist = '
<style type="text/css">* html { overflow-x: hidden; }</style>
<script type="text/javascript" src="js/iutil.js"></script>
<script type="text/javascript" src="js/jquery.mobile.vmouse.min.js"></script>
<script type="text/javascript" src="js/idrag.vmouse.js"></script>
<script type="text/javascript" src="js/idrop.js"></script>
<script type="text/javascript" src="js/isortables.1.2.2.js"></script>
<style>
.dropzone {
	border: 1px solid #bbb;
	float: left;
	margin-right: 1px;
	padding: 5px 5px 5px 5px;
	//background-color: #f0f8ff;
	background-color: #fff;
	touch-action: none;
}
.dropzone ul {
	float: left;
	list-style-type: none;
	width: 180px;
	margin: 0;
	min-height: '.$minheight.'px;
	padding: 0;
	display: block;
}
.progzone {
	border: 1px solid #bbb;
	float: left;
	margin-right: 100px;
	padding: 5px;
	//background-color: #f0f8ff;
	background-color: #fff;
}
.progzone ul {
	float: left;
	list-style-type: none;
	width: 180px;
	margin: 0;
	min-height: '.$minheight.'px;
	padding: 0;
	display: block;
}
.hline {
	width: 178px;
	padding: 0;
	margin: 1px 0;
	display: block;
	border: 1px solid #ccc;
	background-color: #fbfbfb;
	position: relative;
	text-align: left;
	line-height: 5px;
}
.module {
	font-family: sans-serif;
	font-size: 15px;
	width: 178px;
	height: 20px;
	padding: 0;
	margin: 1px 0;
	cursor: move;
	display: block;
	border: 1px solid #ccc;
	background-color: #fbfbfb;
	position: relative;
	text-align: left;
	line-height: 20px;
}
.modul2 {
	font-family: sans-serif;
	width: 178px;
	padding: 0;
	margin: 2px 0;
	display: block;
	position: relative;
	text-align: left;
	line-height: 15px;
}
.player {
	width: 175px;
	margin: 0;
	display: inline;
	position: absolute;
	left: 0px;
	overflow: hidden;
	text-align: left;
	padding: 0 1px;
}
.prognoz2 {
	display: block;
	width: 175px;
	height: 18px;
	padding: 0 0 3px 0;
	position: relative;
}
.prognoz2 input {
	width: 172px;
	height: 17px;
	font-size: 15px;
	margin: 0;
	display: inline;
	position: absolute;
	left: 0px;
	overflow: hidden;
	background-color: #fbfbfb;
	text-align: left;
	padding: 0 1px;
}
.prognoz {
	margin-left:192px
}
.prognoz input {
	width: 172px;
	height: 15px;
	font-size: 15px;
	display: inline;
	overflow: hidden;
	background-color: #fbfbfb;
	text-align: left;
	padding-left:1px;
}
#teamedit p.submit {
	padding-right: 10px;
	clear: left;
}
.placemat {
	cursor: default;
	margin: 0;
	padding: 0;
	position: relative;
}
.placemat span {
	background-color: #ffe;
	border: 1px solid #ccc;
	padding: 0 10px 10px;
	position: absolute;
	text-align: justify;
	font-family: sans-serif;
	font-size: 15px;
}
* html #zones, * html .dropzone {
	height: 1%;
}
</style>
<div id="zones" style="">
<form id="teamedit" method="post" action="" onsubmit="serializeAll();">
  <input type="hidden" id="playersorder" name="playersorder" value="" />
<p>Сформируйте состав команды, перемещая планки с именами игроков в левой<br />
колонке. Первые '.MAIN_SIZE.' игроков с прогнозом начнут матч в основном составе.<br />
В правой колонке можно ввести прогнозы других участников команды.<br />
Пробелы в прогнозе не учитываются и используются только для наглядности.<br />
Если введён прогноз с ошибкой, его строка подсвечивается светло-красным.<br />
<br />
<input id="saveroster" type="submit" value="сохранить состав и прогнозы команды"></p>
  <div class="dropzone">
    <ul id="players">
';
  $i = 1;
  foreach ($cc_predicts as $name => $predict)
    $prognozlist .= '
<li class="module" id="playerId-'.$i.'"><span class="handle"><div class="player">'.$name.'<input type="hidden" name="name-'.$i.'" value="'.$name.'" /></div></span><div class="prognoz"><input type="text" name="playerId-'.($i++).'" value="'.preg_replace('/(\d{1,3})(?=((\d{3})*([^\d]|$)))/i', "$1 ", $predict).'" /><br /></div></li>';

  $prognozlist .= '
    </ul>
  </div>
  <div class="progzone">
    <ul id="predicts">
';
  $i = 1;
  foreach ($cc_predicts as $name => $predict)
    $prognozlist .= '
<li class="modul2" id="prognozId-'.$i.'"><div class="prognoz2"><input type="text" name="playerId-'.($i++).'" value="'.preg_replace('/(\d{1,3})(?=((\d{3})*([^\d]|$)))/i', "$1 ", $predict).'" /><br /></div></li>';
  $prognozlist .= '
    </ul>
  </div>
</form>
</div>
<script>
//<![CDATA[
var numbers = ["1","2","3","4","5","6","7","8","9","10","11","12"];
function initPlayers() {
	for (i=1; i<='.count($cc_predicts).'; i++) {o="#playerId-"+i; $(o).css("position","relative")}
	//$(numbers).each(function(o) {o="#playerId-"+o; $(o).css("position","relative");} );
}
function resetDroppableHeights() {
	max = 6;
	c = $("#players li").length;
	if ( c > max ) max = c;
	var maxheight = 20 * max + 5;
	height = 0 == $("#players li").length ? maxheight - $("#playersplacemat").height() : maxheight;
	$("#players").height(height);
	$("#predicts").height(height);
}
function dragChange(o) {
	screenWidth = self.innerWidth;
	screenHeight = self.innerHeight;
	elWidth = parseInt( $(o).css("width") );
	elHeight = parseInt( $(o).css("height") );
	elLeft = parseInt( $(o).css("left") );
	elTop = parseInt( $(o).css("top") );
	if ( screenWidth < ( parseInt(elLeft) + parseInt(elWidth) ) )
		$(o).css("left", ( screenWidth - elWidth ) + "px" );
	if ( screenHeight < ( parseInt(elTop) + parseInt(elHeight) ) )
		$(o).css("top", ( screenHeight - elHeight ) + "px" );
	if ( elLeft < 1 )
		$(o).css("left", "1px");
	if ( elTop < 1 )
		$(o).css("top", "1px");
}
function serializeAll() {
	serial1 = $.SortSerialize("players");
	$("#playersorder").attr("value",serial1.hash.replace(/playerId-/g, ""));
}
function updateAll() {
	if ( $("#players li").length )
		$("#playersplacemat span.handle").hide();
	else
		$("#playersplacemat span.handle").show();
	resetDroppableHeights();

	serial1 = $.SortSerialize("players");
	players = serial1.o["players"]
	$(players).each(function(i){
		predict = $(".prognoz input[name="+players[i]+"]").val()
		$("#prognozId-"+(i+1)+" div input").prop("name", players[i])
		$("#prognozId-"+(i+1)+" div input").val(predict)
	})
	$(".modul2").show()
}
function startDrag() {
	$(".modul2").hide()
}
function validate(n,v) {
	$(".prognoz input[name="+n+"]").val(v)
	v=v.replace(/[^1-9]/g, "")
	var a=[],i=0,r=true;
	v.split("").map(function(e){
		if(i<18){
			if(i++==9)a=[]
			if(typeof a[e]=="undefined")
				a[e]=1
			else
				r=false
		}
	})
	$(".prognoz2 input[name="+n+"]").css("background-color",(r==true?"#fbfbfb":"#fbe0e0"))
	$("#saveroster").prop("disabled",(r==true?false:true))
	return r
}
$(document).ready( function() {
	updateAll()
	initPlayers()
	$("ul#players").Sortable({accept:"module",activeclass:"activeDraggable",opacity:0.8,revert:true,onStart:startDrag,onStop:updateAll})
	$(".prognoz2 input").change(function(){validate($(this).prop("name"),$(this).val())})
	$(".prognoz2 input").on("input",function(){validate($(this).prop("name"),$(this).val())})
})
// ]]>
</script>
';
 } //coach


  }

  if (isset($matches))      // REST responce on event 'matches'
    echo '[' . $id_json . ']';
  else if (isset($updates)) // REST responce on event 'FT'
/////    echo '[{"id":"div3","html":"html 3"},{"id":"div4","html":"html 4"}]';
    echo '[{"id":"#pl","html":"' . rawurlencode('<h2 style="text-align:right">' . $match_title . '</h2>' .
    $prognozlist) . '"},{"id":"#pr","html":"' . rawurlencode($protocol) . '"}]';
  else if (isset($mtscores)) // REST responce on delayed event after 'FT'
    echo '
    <h2>Матчи тура:</h2>
      <br />
      <br />
      ' . $cal;
  else {
    $html = '
<link href="css/prognoz.css" rel="stylesheet">
' . ($published ? '<div style="height:20px"></div>' : '<script>//<![CDATA[
var '.date('\h\o\u\r\s=G,\m\i\n\u\t\e\s=i,\s\e\c\o\n\d\s=s').',sendfp='.(date('G')>2&&$today_matches>2*$today_bz?'true':'false').',base=[],mom=[]
' . $id_arr . '
function getDate(){if(seconds<59)seconds++;else{seconds=0;if(minutes<59)minutes++;else{minutes=0;hours=hours<23?hours+1:0}};var s=seconds+"",m=minutes+"";if(s.length<2)s="0"+s;if(m.length<2)m="0"+m;$("#timedisplay").html("время сервера: "+hours+":"+m+":"+s)}
setInterval(getDate, 1000);
function newpredict(){var p="";m=$("#pl").data("view")?6:18;for(i=1;i<=m;i++)p+=(ps=$("#dice"+i).val())?ps:"=";$("#prognoz_str").val(p);}
function predict(id,dice){$("#"+id).val(dice);newpredict()}
function show_alert(){str=$("#prognoz_str").val();t=[];t[0]=str.substring(0,9);t[1]=str.substring(9);e="";for(i=0;i<2;i++)for(j=1;j<=9;j++)if(t[i].split(j).length!=2)e+="в"+(i?"о":"")+" "+(i+1)+"-м тайме кол-во ставок \""+j+"\" не равно 1\n";if(e==""){document.forms[0].submit();return true;}else{var r=confirm("Ошибка:\n"+e+"Вы действительно хотите отправить прогноз в таком виде?");if(r==true){document.forms[0].submit();return true;}}return false;}
$(function(){$(".dice").change(function(){newpredict()});})
$(document).ready(function(){
cb=0;re=0;$("input[type=checkbox]").change(function(){cb+=(this.checked?1:-1);if($(this).hasClass("bench"))re+=(this.checked?1:-1);$("#replace").prop("disabled",cb==0?false:true);$("#replace").css("color",cb==0&&re<4?"green":"red")})
})
'
.($today_matches || true ? '
function mtscore(){$.post("'.$this_site.'",{a:"'.$a.'",m:"'.$m.'",s:"'.$s.'",t:"'.$t.'",mtscores:"1"},function(html){$("#mt").html(html)})}
momup=function(i){clearInterval(mom[i]);mom[i]=setInterval(function(){if(!isNaN(base[i][3])){tm=+base[i][3];base[i][3]=(tm==45||tm==90)?tm+"+":++tm;row=$("#"+i)[0];row.cells[5].innerHTML="<span class=\"blink\">"+base[i][3]+"’</span>"}},60000);}
for(i=1;i<=6;i++){if($.isArray(base[i])&&!isNaN(base[i][3]))momup(i)}
scorefix=function(d){
	i=d.idx;m=+d.dk;if(m<1)m=1;else if(m>59)m=base[i][3];h=d.evs;a=d.deps
	s=h+":"+a
	if(base[i][2]!=s){base[i][2]=s;base[i][1]=1}else base[i][1]=0
	s=+d.s;
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
socket=io.connect("https://score2live.net:1998",{"reconnect":true,"reconnection delay":500,"max reconnection attempts":20,"secure":true})
socket.on("connect",function(){socket.emit("hellothere")})
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){if(sendfp){$.post("'.$this_site.'",{a:"'.$a.'",m:"'.$m.'",s:"'.$s.'",t:"'.$t.'",matches:JSON.stringify(d.data.matches)},function(json){$.each(JSON.parse(json),function(idx,obj){base.push(obj.id);base[obj.id]=obj.d})})}$("#statusline").css("display","none")})
socket.on("footdetails",function(data){data=data[0];if ($(".p-table").find("tr[did="+data.id+"]").length)mdetails(data.mdetay,data.id,data.pos1,data.pos2)})
socket.on("guncelleme",function(d){var json="";$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}});if(json.length)$.post("'.$this_site.'",{a:"'.$a.'",m:"'.$m.'",s:"'.$s.'",t:"'.$t.'"'.(isset($n)?',n:"'.$n.'"':'').',updates:"["+json+"]"},function(res){JSON.parse(res,function(k,v){if(k=="id")id=v;else if(k=="html")$(id).html(decodeURIComponent(v))});setInterval(mtscore,50000);})})
' : '
') . '//]]></script>
<div style="height:20px">
	<div id="statusline" style="text-align:left;display:' . ($today_matches ? 'block' : 'none') . '">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a>. Если Вы долго видите эту фразу, перегрузите страницу.</div>
	<div id="timedisplay" style="position:relative;float:right;text-align:right"></div>
</div>') . '
<div style="float:top;width:100%">
<div class="p-head">' . $head . '</div>
<div class="p-table">' . $program_table . '</div>
<div class="p-hint">' . $hint . '</div>
</div>
<div style="text-align:left;width:100%;margin-left:20px">
  <div id="pl" data-view="'.($closed?1:0).'" class="p-left" style='.($coach_form ? '"width:59%"' : '"margin-right:0px;white-space:pre;font-family:monospace;font-size:14px"').'><h2 style="text-align:right">' . $match_title . '</h2>' .
    $prognozlist . '
  </div>
  <div id="mt" class="text14" style="float:right;width:38%">
    <h2>Матчи тура:</h2>
      <br />
      <br />
      ' . $cal . '
  </div>
</div>
<div style="clear:both"></div>
<div id="pr" class="p-left">' . $protocol . '</div>
<div class="p-left">' . $rules . '</div>
<div style="clear:both;"></div>
';

    if ($published && !is_file($season_dir.'/publish/it'.$t.'.'.$n)) {
      file_put_contents($season_dir.'/publish/it'.$t.'.'.$n, $html);
      $statfile = fopen($season_dir.'/publish/plst'.$t, 'a');
      fwrite($statfile, var_export($players, true) . ',');
      fclose($statfile);
      $statfile = fopen($season_dir.'/publish/tmst'.$t, 'a');
      fwrite($statfile, var_export($teams, true) . ',');
      fclose($statfile);
    }
    else
      print($html);

  }
}
?>
