<?php
function team_ccode($team) {
  return strtr($team, [
    'Англия' => 'ENG',
    'Беларусь' => 'BLR',
    'Испания' => 'ESP',
    'Франция' => 'FRA',
    'Германия' => 'GER',
    'Италия' => 'ITA',
    'Голландия' => 'NLD',
    'Португалия' => 'PRT',
    'Россия' => 'RUS',
    'Шотландия' => 'SCO',
    'Швейцария' => 'SUI',
    'Украина' => 'UKR',
  ]);
}

function ccode_team($ccode) {
  return strtr($ccode, [
    'ENG' => 'Англия',
    'BLR' => 'Беларусь',
    'ESP' => 'Испания',
    'FRA' => 'Франция',
    'GER' => 'Германия',
    'ITA' => 'Италия',
    'NLD' => 'Голландия',
    'PRT' => 'Португалия',
    'RUS' => 'Россия',
    'SCO' => 'Шотландия',
    'SUI' => 'Швейцария',
    'UKR' => 'Украина',
  ]);
}

function short_name($name) {
  $replace = [
    ' им. Эдуарда Стрельцова' => '',
  ];
  return strtr($name, $replace);
}

// добавление результатов тура
function rewrite_cal($prognoz_dir, $scorez) {
  global $tour;
  $cal = '';
  $n = 1;
  foreach ($scorez as $match => $score)
  {
    if ($tour < 'UNL12')
      $group = $n == 1 ? 'Лига Сайтов' : ($n == 15 ? 'Лига Наций' : '');
    else if ($tour > 'UNL95')
      $group = $n == 1 ? 'Товарищеские матчи' : '';
    else
      $group = $n == 1 ? 'Финальный турнир' : ($n == 4 ? 'Товарищеские матчи' : '');

    $cal .= "$match;;$group;$score\n";
    $n++;
  }
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

function coach_room($roles, $cc, $all_predicts) {
  global $apikey;
  global $prognoz_dir;
  global $season_dir;
  global $tour;

  $out = '
<link rel="stylesheet" href="css/m.css?ver=5.3" type="text/css">
<h6>Управление составом на первый тайм</h6>
<p>Сформируйте состав, перемещая планки с именами в левой колонке.<br>
Первые 6 (для первого этапа Лиги Наций - 5) игроков с прогнозом<br>
начнут матч в основном составе.<br>
В правой колонке можно ввести прогнозы других участников команды.<br>
Пробелы в прогнозе не учитываются и используются для наглядности.<br>
Строка прогноза с ошибкой, подсвечивается светло-красным.<br>
<style>
.num_players, .sort_players, .prog_players {
	padding-inline-start: 0
}
.sort_players li {
	width: 196px;
	height: 1.5rem;
	padding: 3px;
	margin: 2px;
	display: block;
	position: relative;
}
.num_players li {
	width: 1.5rem;
	height: 1.5rem;
	padding: 3px;
	margin: 0 0 2px 0;
	display: block;
	position: relative;
	text-align: right;
}
.input_module {
	width: 200px;
	height: 1.5rem;
	padding: 3px;
	margin: 0 0 2px 0;
	display: block;
	position: relative;
	line-height: 1rem;
}
.input_module input {
	width: 196px;
	height: 1.4rem;
	padding: 3px;
}
</style>
<script>
function updateAll(event,l){
	list=""
	$("#sort_players_"+l+" input").each(function(){
		id="predict"+this.id.substring(6)
		val=$("#"+id).val()
		str=$("#"+id).get(0).outerHTML
		iof=str.indexOf(" style=")
		list+=\'\n<li class="input_module"><input type="text" id="\'+id+\'" name="predict[]" value="\'+val+\'"\'+(iof?str.substring(iof):">")+"</li>"
	})
	$("#predicts_"+l).html(list)
	$(".input_module input").change(function(){validate($(this).attr("id"),$(this).val())})
	$(".input_module input").on("input",function(){validate($(this).attr("id"),$(this).val())})
}
function validate(n,v){
	v=v.replace(/[^1-9]/g,"")
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
	$("#"+n).css("background-color",(r==true?"#fbfbfb":"#fbe0e0"))
	$("#save_squad_"+n.charAt(8)).prop("disabled",(r==true?false:true))
	return r
}
function sendRoster(l){
	var players = $(\'#sort_players_\'+l+\' input[name="player[]"]\').map(function(){return $(this).val()}).get()
	var predicts = $(\'#predicts_\'+l+\' input[name="predict[]"]\').map(function(){return $(this).val()}).get()
	$.post("/online/ajax.php",{data:"'.$apikey.'",tour:"'.$tour.'",team:$("#team-"+l).val(),players:players,predicts:predicts},function(r){
		$("#statusline").html(r)
		$("#save_squad_"+l).removeClass("btn-primary")
		$("#save_squad_"+l).addClass(r.indexOf("success")>0?"btn-success":"btn-danger")
	})
}
$(document).ready(function(){
	$(".input_module input").change(function(){validate($(this).attr("id"),$(this).val())})
	$(".input_module input").on("input",function(){validate($(this).attr("id"),$(this).val())})
	$("#sort_players_n").sortable({update: function(event){updateAll(event,"n")}})
	$("#sort_players_s").sortable({update: function(event){updateAll(event,"s")}})
})
</script>
';

  foreach ($roles as $league => $coach) if ($coach == 'coach' && ($league == 's' || $tour < 'UNL12' || $tour > 'UNL95' ))
  {
    // если есть, загрузить prognoz/tour/cc
    // обновить в нём прогнозы из all_predicts (если есть обновления, записать!)
    // а если нет, загрузить cc.csv и дополнить из all_predicts
    $cc_predicts = [];
    $new = false;
    if (is_file($prognoz_dir.'/'.$cc[$league]))
    {
      $cc_pr = file($prognoz_dir.'/'.$cc[$league], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($cc_pr as $pr_line)
      {
        list($name, $predict) = explode(';', $pr_line);
        $name = trim($name);
        if (isset($all_predicts[$name]) && trim($all_predicts[$name]) && $all_predicts[$name] != $predict)
        {
          $new = true; // получен обновлённый прогноз
          $cc_predicts[$league][$name] = $all_predicts[$name];
        }
        else
          $cc_predicts[$league][$name] = $predict;

      }
    }
    $cc_pr = file($season_dir.'/'.$cc[$league].'.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($cc_pr as $pr_line)
    {
      list($name, $email) = explode(';', $pr_line);
      $name = trim($name);
      if (!isset($cc_predicts[$league][$name]))
        if (isset($all_predicts[$name]))
        {
          $new = true; // получен обновлённый прогноз
          $cc_predicts[$league][$name] = $all_predicts[$name];
        }
        else
          $cc_predicts[$league][$name] = '';

    }

    if ($new)
    { // перенос новых прогнозов в прогнозы команды
      $predicts = '';
      foreach ($cc_predicts[$league] as $name => $predict)
        $predicts .= $name.';'.$predict.';;
';
      file_put_contents($prognoz_dir.'/'.$cc[$league], $predicts);
    }
//<form id="squad-'.$league.'" action="" name="squad-'.$league.'" method="POST">
//<input type="hidden" id="tour" name="tour" value="'.$tour.'">
    $out .= '
<br>
<div class="pl-3 h6">Управление сборной в '.($tour < 'UNL12' || $tour > 'UNL95' ? 'Лиге '.($league == 'n' ? 'Наций' : 'Сайтов') : 'Финальном турнире').'</div>
<input type="hidden" id="team-'.$league.'" name="team" value="'.urlencode($cc[$league]).'">
<div style="display:flex">
  <div class="numzone">
    <ul class="num_players">';
    $i = 1;
    foreach ($cc_predicts[$league] as $name => $predict)
      $out .= '
      <li>'.$i++.'</li>';

    $out .= '
    </ul>
  </div>
  <div class="dropzone">
    <ul id="sort_players_'.$league.'" class="sort_players">';
    $i = 1;
    foreach ($cc_predicts[$league] as $name => $predict)
      $out .= '
      <li class="sortable_module">'.$name.'<input type="hidden" id="player-'.$league.($i++).'" name="player[]" value="'.$name.'"></li>';

    $out .= '
    </ul>
  </div>
  <div class="progzone">
    <ul id="predicts_'.$league.'" class="prog_players">';
    $i = 1;
    foreach ($cc_predicts[$league] as $name => $predict)
      $out .= '
      <li class="input_module"><input type="text" id="predict-'.$league.($i++).'" name="predict[]" value="'.trim(preg_replace('/(\d{1,3})(?=((\d{3})*([^\d]|$)))/i', "$1 ", strtr($predict, [' ' => '']))).'"></li>';

    $out .= '
    </ul>
  </div>
</div>
<button class="btn btn-primary ml-3 mb-3" id="save_squad_'.$league.'" onClick="sendRoster(\''.$league.'\');return false">сохранить</button>
';
//</form>
  }
  return $out;
}

function players_table($array, $side, $coach, $rprognoz, $half1, $half2, $size) {
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
      if ($p < $size) {
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
          if ($p < $size ) {
            if (!$i && !$j) {
              $prognozColored .= '<mark>';
              $main = true;
            }
            else if (($coach || $half2) && $repl && $i == 3 && !$j) {
              $prognozColored .= '<mark>';
              $main = true;
            }
            else if (!$coach && !$half2 && $i == 3 && !$j) {
              $prognozColored .= '<mark>';
              $main = true;
            }
          }
          else if (($coach || $half2) && $repl && $i == 3 && !$j) {
            $prognozColored .= '<mark>';
            $main = true;
          }
          if ($rprognoz[$i] != '?' && $rprognoz[$i] != '-' && $rprognoz[$i] != '=' && $j == $index) {
            $prognozColored .= '<span class="b-lime">'.$prog[$i * 3 + $j].'</span>';
            $point = $prog[$i * 3 + $j];
            $players[$side][$p]['maxpts'][$i] = max($players[$side][$p]['maxpts'][$i], $prog[$i * 3 + $j]);
          }
          else {
            $prognozColored .= $prog[$i * 3 + $j];
            $players[$side][$p]['maxpts'][$i] = max($players[$side][$p]['maxpts'][$i], $prog[$i * 3 + $j]);
          }
          if (($i == 2 && $j == 2 && $p < $size) || ($i == 5 && $j == 2 && $main))
            $prognozColored .= '</mark>';

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
          if ($main)
            $main = false;

          if ($half2)
            $prognozColored .= '&nbsp; <input type="checkbox" disabled="disabled" '.($repl ? 'checked="checked"' : '').'> &nbsp; ';
          else if ($coach)
// здесь для товарищеских матчей надо не включать класс bench
            $prognozColored .= '&nbsp; <input type="checkbox" '.($p >= $size && false ? 'class="bench" ' : '').'name="'.rawurlencode($name).'" '.($repl ? 'checked="checked"' : '').' data-size="'.$size.'"> &nbsp; ';
          else
            $prognozColored .= '&nbsp; <input type="checkbox" disabled="disabled" '.($p < $size ? 'checked="checked"' : '').'> &nbsp; ';
        }
      }
//      if ($main)
//        $prognozColored .= '</mark>';

      $list .= $name . str_repeat(' ', 22 - mb_strlen($name)) . $prognozColored . sprintf('%5s', $points) . ' (' . $pointm . ")\n";
      if ($p < $size)
        $worepl += $points;

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
'Хозяева' => 'Игроки сборной ' . $teams['home']['team'],
'хозяева' => 'игроки сборной ' . $teams['home']['team'],
'хозяев' => 'сборной ' . $teams['home']['team'],
      ]);
    else
      $comment = strtr($comment, [
'Гости' => 'Игроки сборной ' . $teams['away']['team'],
'гости' => 'игроки сборной ' . $teams['away']['team'],
'гостей' => 'сборной ' . $teams['away']['team'],
      ]);
    $comment = strtr($comment, [
'ия' => 'ии',
'Украина' => 'Украины',
    ]);
  }
  return $comment;
}

function goal($side, $i, $size) {
  global $players;
  global $teams;
  global $replaces;
  $arr = array();
  foreach($players[$side] as $p => $pdata)
    if (($i < 3 || !isset($replaces[4][$side])) && $p < $size) // 1 тайм или не было замен: играют первые $size
      $arr[12 - $p + 100 * ($pdata['psumm'][$i] - 5 * $pdata['goal'] - 2 * $pdata['pass']) + 10000 * $pdata['points'][$i]] = $p;
    else if ($i > 2 && isset($replaces[4][$side]) && ((in_array($p, $replaces[4][$side]) && $p >= $size) || (!in_array($p, $replaces[4][$side]) && $p < $size))) // на поле во 2 тайме
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

function match_row($nm, $match, $home, $away, $tournament) {
  global $finished;
  global $half1;
  global $half2;
  global $id_arr;
  global $id_json;
  global $lastplayed;
  global $mdp;
  global $published;
  global $rprognoz;
  global $today_matches;
  global $today_bz;

  $out = '';
  $rt = '?';
  list($match_date, $match_time) = explode(' ', $match[2]);
  list($match_month, $match_day) = explode('-', $match_date);
  $dm = "$match_day .$match_month";
  $tn = $match_time;
  $st = $match[3];
  $mt = $match[5];
  if (($st != '-') && (($st <= '90') || ($st == 'HT')))
  {
    $today_matches++;
    if ($match[6]) // match id @livescore.bz
    {
      $id_arr .= "base[{$match[6]}] = [$nm,0,\"$mt\",\"$st\"]
";
      $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $match[6] . '","d":[' . $nm . ',0,"' . $mt . '","' . $st . '"]}';
      $today_bz++;
    }
    $mt = '<span class="red">' . $mt . '</span>';
    $rt = $st == 'HT' ? '<span class="red">' . $st . '</span>' : '<span class="blink">' . $st . "'" . '</span>';
    $lastplayed = $nm;
    if ($nm > 3)
      $half2 = true; // начался матч 2-го тайма
//    else if ($nm)
//      $half1 = true; // начался матч 1-го тайма

  }
  else if (($st == 'CAN') || ($st == 'POS') || ($st == 'SUS'))
  {
     $mt = $st;
     $rt = '-';
     $finished++;
  }
  else if ($st == 'FT')
  {
    list($gh, $ga) = explode(':', $mt);
    $rt = $gh == $ga ? 'X' : ($gh > $ga ? '1' : '2');
    $finished++;
    $lastplayed = $nm;
    if ($nm > 3)
      $half2 = true; // сыгран матч 2-го тайма
//    else if ($nm)
//      $half1 = true; // сыгран матч 1-го тайма

  }
  else if ($dm == date('d.m'))
  {
    $today_matches++;
    if ($match[6]) // match id @livescore.bz
    {
      $id_arr .= 'base[' . $match[6] . '] = [' . $nm . ',0,"-:-","-"];
';
      $id_json .= (strlen($id_json) ? ',' : '') . '{"id":"' . $match[6] . '","d":[' . $nm . ',0,"-:-","-"]}';
      $today_bz++;
    }
  }
  else if ($match_month.$match_day < date('md'))
  {
    $mt = $st = 'POS';
    $rt = '-';
  }
  $tr_id = $match[6] ? ' id="' . $match[6] . '"' : '';

  $mdp[$nm] = array('home' => $home, 'away' => $away, 'date' => $dm, 'rslt' => $mt, 'case' => $rt);
/*
  if ($nm == 4) // для судоку важно показать разделение таймов
    $out = '<tr><td colspan="'.($published ? 6 : 7).'"></td></tr>';
*/
  $out .= '<tr' . $tr_id . '><td class="tdn">'.$nm.'</td><td style="text-align:left;width:288px' . ($tr_id != '' ? ';cursor:pointer" onClick="details($(this).closest(\'tr\'))' : '') . '">'.$home.' - '.$away.'</td><td align="left">'.$tournament.'</td><td align="right">&nbsp;'.date_tz('d.m&\n\b\s\p;H:i', $match_date, $tn, $_COOKIE['TZ'] ?? 'Europe/Berlin').'&nbsp;</td><td align="center">&nbsp;'.$mt.'&nbsp;</td><td align="middle">&nbsp;'.$rt.'&nbsp;</td>';
  if (!$published) // дополняем таблицу блоком предсказаний
  {
    if ($rt == '1' || $rt == 'X' || $rt == '2')
    {
      $onchange = 'disabled="disabled" ';
      $dis = 'dis';
      $val = $rt;
    }
    else {
      $onchange = 'onchange="newpredict();"';
      $dis = '';
      $val = isset($prognoz_str) && $prognoz_str ? $prognoz_str[$nm - 1] : '';
    }
    $out .= '
    <td>
      <a href="#" onclick="predict('."'dice$nm$dis','1'".'); return false;">1</a>
      <a href="#" onclick="predict('."'dice$nm$dis','X'".'); return false;">X</a>
      <a href="#" onclick="predict('."'dice$nm$dis','2'".'); return false;">2</a>
      <input type="text" name="'.'dice'.$nm.'" value="'.$val.'" id="'.'dice'.$nm.'" class="pr_str" '.$onchange.'>
    </td>
';
  }
  $out .= '</tr>
';
  $rprognoz .= strlen($rt) > 1 ? '?' : $rt;
  return $out;
}

function ball($side, $i, $size) {
  global $players;
  global $teams;
  global $replaces;
  $arr = array();
  foreach($players[$side] as $p => $pdata)
    if (($i < 3 || !isset($replaces[4][$side])) && $p < $size) // 1 тайм или не было замен: играют первые $size
      $arr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;
    else if ($i > 2 && isset($replaces[4][$side]) && ((in_array($p, $replaces[4][$side]) && $p >= $size) || (!in_array($p, $replaces[4][$side]) && $p < $size))) // на поле во 2 тайме
      $arr[12 - $p + 100 * $pdata['psumm'][$i] + 10000 * $pdata['points'][$i]] = $p;

  krsort($arr, SORT_NUMERIC);
  $gn = current($arr);
  $an = next($arr);
  $goaleador = $players[$side][$gn]['name'];
  $assistant = $players[$side][$an]['name'];
  return array($goaleador, $assistant); // на самом деле игроки с мячом
}

function li_ball($i) {
  return ($i == '=') ? '' : '
        <li id="ball-'.$i.'" class="stage">
          <div class="sortable ball' . $i . '"><span class="shadow"></span><span class="digit' . $i . '"></span></div>
        </li>';
}

// форма подачи прогноза с шарами
function ball_form($nm, $match, $home, $away, $prognoz_half, $prognoz_array) {
  global $bn;
  $out = '';
  $half = $nm < 4 ? 1 : 2;
  if ($nm == 1) // начало 1 тайма
    $out .= '
  <div class="time">
    <div class="row">
      <div class="col-3 small text-left">победа хозяев</div>
      <div class="col-6 header_b">Первый тайм</div>
      <div class="col-3 small text-right">победа гостей</div>
    </div>';

  if ($nm == 4)
  {
    $out .= '
    <div class="small">неиспользованные ставки первого тайма</div>
    <ul id="time1-unused" class="sortable-list-1 container">
';
    for ($i = 1; $i <= 9; ++$i)
      if ($prognoz_half && strpos($prognoz_half[0], (string)$i) === false)
        $out .= li_ball($i);

    $out .= '
    </ul>
    <div style="clear: both"></div>
  </div>
  <div class="time">
    <div class="header_b"><br>Второй тайм</div>
    <div class="small">неиспользованные ставки второго тайма</div>
    <ul id="time2-unused" class="sortable-list-2 container">
';
    for ($i = 1; $i <= 9; ++$i)
      if (!isset($prognoz_half[1]) || strpos($prognoz_half[1], (string)$i) === false)
        $out .= li_ball($i);

    $out .= '
    </ul>
    <div style="clear: both"></div>
';
  }
  list($date, $time) = explode(' ', $match[2]);
  $out .= '
    <div>
      <div style="width:450px; text-align:center; font-weight:bold">'.$home.' - '.$away.'</div>
      <ul id="bet-'.$bn++.'" class="sortable-list-' . $half . ' home">'.
        (isset($prognoz_array[$bn - 2]) ? li_ball($prognoz_array[$bn - 2]) : '').'
      </ul>
      <div class="team" style="text-align:right">'.strtr($match[7], [': ' => '<br>']).'</div>
      <ul id="bet-'.$bn++.'" class="sortable-list-' . $half . ' home">'.
        (isset($prognoz_array[$bn - 2]) ? li_ball($prognoz_array[$bn - 2]) : '').'
      </ul>
      <div class="team">'.date_tz('Y-m-d<\b\r>H:i', $date, $time, $_COOKIE['TZ'] ?? 'Europe/Berlin').'</div>
      <ul id="bet-'.$bn++.'" class="sortable-list-' . $half . ' home">'.
        (isset($prognoz_array[$bn - 2]) ? li_ball($prognoz_array[$bn - 2]) : '').'
      </ul>
    </div>
    <div style="clear: both"></div>
';
  if ($nm == 6) // конец 2 тайма
    $out .= '
  </div>';

  return $out;
}

function prognozlist_head($rprognoz) {
  $out = '                        1-й  тайм    на   2-й  тайм   баллы игроков
<b>Правильный результат:   ';
  for ($i = 0; $i < 3; $i++)
    $out .= $rprognoz[$i] . '   ';

  $out .= '</b>поле  <b>';

  for ($i = 3; $i < 6; $i++)
    $out .= $rprognoz[$i] . '   ';

  $out .= 'команд(замен)</b>
';
  return $out;
}


// main - последовательность блоков:
// конфигурация
// идентификация
// парсинг программки и формирование таблицы тура или "шаров"
// получение командных данных ?
// получение предсказания
// формирование блоков результатов


// конфигурация
$season_dir = $online_dir . $cca . '/' . $s;
$tour = $cca . $t;
$program_file = $season_dir . '/programs/' . $tour;
$prognoz_dir = $season_dir. '/prognoz/' . $tour;
$closed = is_file($prognoz_dir.'/closed');
$published = is_file($prognoz_dir.'/published');
$config = json_decode(file_get_contents($season_dir . '/fp.cfg'), true);
$query_string = ($cut = strpos($_SERVER['QUERY_STRING'], '&n=')) ? substr($_SERVER['QUERY_STRING'], 0, $cut) : $_SERVER['QUERY_STRING'];
if ($published)
  $query_string = strtr($query_string, ['prognoz' => 'result']);

$half1 = $renew = false;
$half2 = $closed && filesize($prognoz_dir.'/closed');
$reload = !$half2 && is_file($prognoz_dir.'/term') ? 59 + file_get_contents($prognoz_dir.'/term') - time() : 0;
$notice = '&nbsp;';
$rprognoz = $protocol = $log = $program_table = $id_arr = $id_json = $js_bets = $prognozlist = '';
$today_matches = $today_bz = $finished = 0;
$teamCodes = $aprognoz = $scorez = $teams = $mdp = $cc = $my_predicts = $history = $prognoz_array = $prognoz_half = [];
$roles = ['n' => '', 's' => ''];
$myteam = ['n' => [], 's' => []];  // состав своих команд (только для тренера)

// идентификация
if (isset($_SESSION['Coach_name'])) {
  $teamCodes['n'] = $_SESSION['Coach_name'];
  foreach ($cmd_db[$cca] as $code => $team)
    if ($team['usr'] == $_SESSION['Coach_name'] || $team['eml'] == $_SESSION['Coach_mail'])
    {
      $teamCodes['s'] = $code;
      break;
    }

  // заплата для Финального турнира
  if ($tour > 'UNL11' && $tour < 'UNL95')
    switch ($_SESSION['Coach_name'])
    {
      case 'Андрей Новиков': $teamCodes['n'] = 'Севас'; break;
      case 'andrey tumanovich': $teamCodes['n'] = 'kipelov1234'; break;
      case 'ВитЬя Барановский': $teamCodes['s'] = 'ВитЬя Барановский'; break;
      case 'Вячеслав Ковалевский': $teamCodes['s'] = 'Вячеслав Ковалевский'; break;
      case 'Serge Vasiliev': $teamCodes['s'] = 'Serge Vasiliev'; break;
      case 'Максим Кузнецов': $teamCodes['s'] = 'Максим Кузнецов'; break;
      case 'Александр Сесса': $teamCodes['s'] = 'Александр Сесса'; break;
      case 'Кирилл Голощёков': $teamCodes['s'] = 'Кирилл Голощёков'; break;
    }

  if (count($teamCodes))
  {
    $team_select = $data_dir . 'personal/'.$_SESSION['Coach_name'].'/team.'.date('Y');
    if (($t < 12 || $t > 95) && isset($teamCodes['n']) && is_file($team_select))
    {
      // определение роли
      $cc['n'] = trim(file_get_contents($team_select));
      $codes = file($season_dir.'/'.$cc['n'].'.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($codes as $cline) {
        $arr = explode(';', $cline);
        if ($arr[0] == $teamCodes['n']) {
          $roles['n'] = $arr[2];
          break;
        }
      }
      // состав команды для тренера
      if ($roles['n'] == 'coach')
        foreach ($codes as $cline) {
          $arr = explode(';', $cline);
          $myteam['n'][] = $arr[0];
        }

    }
    if (isset($teamCodes['s']))
    {
      // определение роли
      $codes = file($season_dir.'/codes.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($codes as $cline) {
        $arr = explode('	', $cline);
        if ($arr[0] == $teamCodes['s']) {
          $cc['s'] = $arr[1];
          $roles['s'] = $arr[4];
          break;
        }
      }
      // состав команды для тренера
      if ($roles['s'] == 'coach')
      {
        $squad = file($season_dir.'/'.$cc['s'].'.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($squad as $cline)
          $myteam['s'][] = explode(';', $cline)[0];

      }
    }

    // выборка своих прогнозов и историй получения прогнозов для тренеров
    $predicts = file($prognoz_dir.'/mail', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($predicts as $predict) {
      list($name, $predict, $timestamp, $pena) = explode(';', $predict);
      $name = trim($name);
      if ($pena)
        $predict = $predict . ' ' . $pena; // для почты с пробелами между тройками

      $all_predicts[$name] = $predict;
      if (isset($teamCodes['n']) && $teamCodes['n'] == $name)
        $my_predicts['n'] = $predict;

      if (isset($teamCodes['s']) && $teamCodes['s'] == $name)
        $my_predicts['s'] = $predict;

      if (in_array($name, $myteam['n']))
        $history['n'][$timestamp][] = ['event' => 'predict', 'name' => $name, 'what' => $predict];

      if (in_array($name, $myteam['s']))
        $history['s'][$timestamp][] = ['event' => 'predict', 'name' => $name, 'what' => $predict];

    }
    $prognoz_post = current($my_predicts) ?? '==================';

    // сделать через API !!!
    // замены на второй тайм НЕ ПРИНИМАТЬ ПОСЛЕ НАЧАЛА 2 ТАЙМА !!!!!

    if (isset($_POST['replace'])) {
      if (filesize($prognoz_dir.'/closed'))
        $notice = '<span class="text-danger">✘ </span> поздно - начался 2-й тайм';
      else
      {
        $out = '';
        $log = $_SESSION['Coach_name'] . ';состав второго тайма:<br>';
        $team_name = urldecode($_POST['ccode']);
        $lines = file($prognoz_dir.'/'.$team_name);
        foreach ($lines as $line) if (trim($line)) {
          list($name, $predict, $ts, $rest) = explode(';', trim($line));
          $log .= (strlen($log) > 45 ? ', ' : ' ') . (isset($_POST[strtr(rawurlencode($name), ['.' => '_'])]) ? '<mark>' : '') . $name . (isset($_POST[rawurlencode($name)]) ? '</mark>' : '');
          $out .= $name.';'.$predict.';'.trim($ts).';'.(isset($_POST[strtr(rawurlencode($name), ['.' => '_'])]) ? '4' : '').'
';
        }
        $cc_pr = explode("\n", $out);
        file_put_contents($prognoz_dir.'/'.$team_name, $out);
        $hisfile = fopen($prognoz_dir.'/.'.$team_name, 'a');
        fwrite($hisfile, "$log;" . time() . "\n");
        fclose($hisfile);
        $notice = '<span class="text-success">✔ </span>состав 2-го тайма сохранён';
      }
    }
  }
}

// таблица программки тура
include ('online/realteam.inc.php');
include ('online/tournament.inc.php');
list($program_matches, $lastdate, $lasttm, $program) = parse_program($program_file);
list($last_day, $last_month) = explode('.', $lastdate);
$dm = "$last_day.$last_month";
$day_before = strtotime("-1 day $last_month/$last_day");
$base = get_results_by_date(date('m', $day_before), date('d', $day_before), $updates ?? NULL);

// заголовок тура
/* это не работает - надо неспешно починить!!!
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
*/
$head = '';
$tt = $t - 1;
if (is_dir($season_dir . 'UNL'.($tt < 10 ? '0' : '').$tt))
  $head .= '<a href="/?a=world&s='.$s.'&t='.($tt < 10 ? '0' : '').$tt.'&m=prognoz" title="предыдущий тур"><i class="fas fa-arrow-circle-left"></i></a> ';

if ($t < 12)
  $head .= 'Лиги Сайтов и Наций '.$s.'. Тур '.ltrim($t, '0');
else if ($t < 17)
  $head .= 'Финальный Турнир '.$s.'. Тур '.($t - 11);
else if ($t > 95)
  $head .= 'Лига Наций '.$s.'. Пробный тур '.($t - 95);

$tt = $t + 1;
if (is_dir($season_dir . 'UNL'.($tt < 10 ? '0' : '').$tt))
  $head .= ' <a href="/?a=world&s='.$s.'&t='.($tt < 10 ? '0' : '').$tt.'&m=prognoz" title="следующий тур"><i class="fas fa-arrow-circle-right"></i></a>';

$head .= '<br>';
if (count($teamCodes) && !$closed)
{
  $head .= '
<span class="small">код тура: <b>'.$cca.$t.'</b> ';
  if ($t > 11 && $t < 96 || count($teamCodes) == 1 || $teamCodes['n'] == $teamCodes['s'])
    $head .= ' <input type="hidden" id="single_code" name="team_code" value="'.current($teamCodes).'"><b>'.current($teamCodes).'</b><br>';
  else
  {
    $head .= '<select id="team_codes" name="team_code">';
    $oba = $options = '';
    foreach ($teamCodes as $league => $tc) {
      $options .= '<option value="'.$tc.'"';
      $selected = '';
      (isset($c) && $c == $tc) ? $selected = ' selected="selected"' : $c = $tc;
      $options .= $selected.'>'.$tc.', Лига '.($league == 's' ? 'Сайтов' : 'Наций').'</option>';
      $oba .= ($oba ? ';' : '') . $tc;
    }
    $head .= '<option value="'.$oba.'">один прогноз в оба турнира</option>'.$options.'</select><br>';
  }
  $prognoz_post = strtr($prognoz_post, [' ' => '']);
  $head .= '</span>
<span class="small">прогноз: </span><input type="text" id="prognoz_str" name="prognoz_str" value="'.(isset($prognoz_post) ? $prognoz_post : '').'" style="width:16rem">
<button id="send_predict" class="btn btn-primary" onClick="post_predicts()"> отправить </button>
';
  // подготовка переменных для работы с шарами
  $bn = 1; // номер ставки - места для шара
  $prognoz_half = str_split($prognoz_post, 9);
  $prognoz_array = str_split($prognoz_post);
  foreach ($prognoz_array as $bet)
    $js_bets .= ($js_bets ? ',' : '') . ($bet == '=' ? '0' : $bet);

}

// строки таблицы МдП, в шапке понадобится $n, поэтому она ниже
foreach ($program_matches as $line)
  if (strpos($line, ' - '))
  {
    $divider = (strpos($line, '│') !== false) ? '│' : '|';
    list($pre, $nm, $match, $dm, $post) = explode($divider, $line);
    $nm = rtrim(trim($nm), '.');
    list($match, $tournament) = explode('  ', $match, 2);
    $tournament = trim($tournament);
    list($home, $away) = explode(' - ', trim($match));
    $match = $realteam[$home].' - '.$realteam[$away].'/'.$tourname[$tournament];
    if (!$tournament || !isset($base[$match]))
      $match = $realteam[$home].' - '.$realteam[$away];

    if ($closed)
      $program_table .= match_row($nm, $base[$match], $home, $away, $tournament);
    else
      $program_table .= ball_form($nm, $base[$match], $home, $away, $prognoz_half, $prognoz_array);

  }

if (is_file($prognoz_dir.'/cal'))
{
  // выборка матчей (календаря) тура
  $tour_cal = file($prognoz_dir.'/cal', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $cal = '<ul style="margin-top:1rem;list-style-type:none;padding-inline-start:0">
';
  $i = 1;
  foreach ($tour_cal as $cal_line)
  {
    if (!strpos($cal_line, ';'))
      $cal_line .= ';;;';

    list($line, $link, $group, $score) = explode(';', $cal_line);
    $scorez[$line] = $score; // для проверки надо ли обновить календарь
    list($home, $away) = explode(' - ', $line);
    // для нечётного количества команд (Швейцария): заплата
    if ($home == $away)
      $away = 'Швейцария';

    $virtmatches[$i] = ['home' => ['team' => $home], 'away' => ['team' => $away]];
    foreach ($cc as $c)
      if (!isset($n) && (team_ccode($home) == $c || team_ccode($away) == $c))
      {
        $n = $i;
        break;
      }

    $i++;
  }

  if ($closed)
  {
    // если есть, используем симуляцию результатов
    if (isset($prognoz_str) && $prognoz_str)
      for ($i=0; $i<strlen($prognoz_str); $i++)
        if ($prognoz_str[$i] != '*')
          $rprognoz[$i] = $prognoz_str[$i];

    $program_table = '<table class="p-table">
  <tr><th>№</th><th align="left">МдП</th><th></th><th>дата&nbsp;время</th><th>счёт</th><th>исх.</th>' .
($published ? '' : '<th>прогноз</th>') . '</tr>
' . $program_table .
($published ? '' : '
  <tr class="text-right">
    <td></td>
    <td colspan="4" style="max-width:424px;line-height:1.0">для расчёта вариантов завершения матчей выберите в графе "прогноз" возможный исход и нажмите на кнопку</td>
    <td colspan="2">
      <form it="tform" action="" name="tform" method="get">
        <input type="hidden" name="a" value="world">
        <input type="hidden" name="m" value="prognoz">
        <input type="hidden" name="s" value="'.$s.'">
        <input type="hidden" name="t" value="'.$t.'">
        <input type="hidden" id="prognoz_str" name="prognoz_str" value="">
        <input type="hidden" id="whatsifn" name="n" value="'.($n = $n ?? 1).'">
        <input type="submit" class="btn btn-default btn-light border mt-1" value="что, если?">
      </form>
    </td>
  </tr>') . '
</table>
';
    if (isset($prognoz_str))
      $finished = $lastplayed = strlen(rtrim($prognoz_str, '=')); // чтобы строило протокол и показывало счёт

    $prognozlists = $teams_n = $teams_s = $players_n = $players_s = [];
    $prognozlist_head = prognozlist_head($rprognoz);

    // цикл сбора блоков матчей
    foreach ($virtmatches as $matchn => $teams)
    {
      $players = $replaces = $changes = [];
      $prognozlist = $homelist = '';
      $home_team_fn = team_ccode($teams['home']['team']);
      $away_team_fn = team_ccode($teams['away']['team']);
      if (($t < 12 || $t > 95) && $teams['home']['team'] != $home_team_fn)
      {
        $league = 'n';
        $size = 5;
      }
      else
      {
        $league = 's';
        $size = 6;
      }
      $homecoach = (isset($cc[$league]) && isset($roles[$league]) && $home_team_fn == $cc[$league] && $roles[$league] == 'coach') ? true : false;
      $awaycoach = (isset($cc[$league]) && isset($roles[$league]) && $away_team_fn == $cc[$league] && $roles[$league] == 'coach') ? true : false;
      if ($homecoach || $awaycoach)
        $homelist .= '<form id="squad2form" action="" method="POST"><input type="hidden" name="ccode" value="'.urlencode($homecoach ? $home_team_fn : $away_team_fn).'">';

      $home_arr = is_file($prognoz_dir.'/'.$home_team_fn) ? file($prognoz_dir.'/'.$home_team_fn, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
      $away_arr = is_file($prognoz_dir.'/'.$away_team_fn) ? file($prognoz_dir.'/'.$away_team_fn, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

      list($homepoints, $templist, $teams['home']['worepl']) = players_table($home_arr, 'home', $homecoach, $rprognoz, $half1, $half2, $size);
      $homelist .= $templist;
      list($awaypoints, $awaylist, $teams['away']['worepl']) = players_table($away_arr, 'away', $awaycoach, $rprognoz, $half1, $half2, $size);
      if ($homecoach || $awaycoach)
        $awaylist .= '</form>';

      $homeline = mb_sprintf('%-22s', short_name($teams['home']['team']));
      $teams['home']['total'] = 0;
      for ($i=0; $i<6; $i++) {
        $changes[$i] = $homepoints[$i] - $awaypoints[$i];
        $homeline .= '  ';
        if ($changes[$i] > 17) {
          $homeline .= '<span class="b-pink">';
          $changes[$i] = 4;
        }
        else if ($changes[$i] > 12) {
          $homeline .= '<span class="b-lime">';
          $changes[$i] = 3;
        }
        else if ($changes[$i] > 7) {
          $homeline .= '<span class="b-yell">';
          $changes[$i] = 2;
        }
        else if ($changes[$i] > 2) {
          $homeline .= '<span class="b-cyan">';
          $changes[$i] = 1;
        }
        else if ($changes[$i] > -3)
          $changes[$i] = 0;

        $homeline .= sprintf('%2s', $homepoints[$i]);
        if ($changes[$i] > 0)
          $homeline .= '</span>';

        $teams['home']['total'] += $homepoints[$i];

        if ($i == 2) {
          if ($homecoach && !$half2)
            $homeline .= '  <input type="hidden" name="replace" value="replace"><button type="submit" id="replace-'.$matchn.'" style="padding:0px 0px; width:26px; height:24px; font-weight:bolder; text-align:center" title="установите '.$size.' галочек тем, кто будет
играть во 2 тайме, и нажмите кнопку.
Максимальное количество замен = 3." disabled="disabled">&#x21c5;</button> ';
          else
            $homeline .= '      ';

        }
      }
      $homeline .= sprintf('%7s', $teams['home']['total']) . ' (' . ($teams['home']['total'] - $teams['home']['worepl']) . ')';
      $awayline = mb_sprintf('%-22s', short_name($teams['away']['team']));

    $teams['away']['total'] = 0;
    for ($i=0; $i<6; $i++) {
      $awayline .= '  ';
      if ($changes[$i] < -17) {
        $awayline .= '<span class="b-pink">';
        $changes[$i] = -4;
      }
      else if ($changes[$i] < -12) {
        $awayline .= '<span class="b-lime">';
        $changes[$i] = -3;
      }
      elseif ($changes[$i] < -7) {
        $awayline .= '<span class="b-yell">';
        $changes[$i] = -2;
      }
      elseif ($changes[$i] < -2) {
        $awayline .= '<span class="b-cyan">';
        $changes[$i] = -1;
      }
      $awayline .= sprintf('%2s', $awaypoints[$i]);
      if ($changes[$i] < 0) $awayline .= '</span>';
      $teams['away']['total'] += $awaypoints[$i];

      if ($i == 2)
        if ($awaycoach && !$half2)
          $awayline .= '  <input type="hidden" name="replace" value="replace"><button type="submit" id="replace-'.$matchn.'" style="padding:0px 0px; width:26px; height:24px; font-weight:bolder; text-align:center" title="установите '.$size.' галочек тем, кто будет
играть во 2 тайме, и нажмите кнопку.
Максимальное количество замен = 3." disabled="disabled">&#x21c5;</button> ';
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
      $protocol .= '<br><b>' . $min . ' минута (' . $mdp[$i+1]['home'] . ' - ' . $mdp[$i+1]['away'] . ')</b>: ' . auto_preambula($position, $min);
      $min_diff = abs($homepoints[$i] - $awaypoints[$i]);
      if ($min_diff < 3) $min_diff = 14 - $min_diff;
      $min += $min_diff % 15; // минута события
      do { // пока "мяч" не остановится
        $newposition = $diff > 0 ? min(2, $position + $diff) : max(-2, $position + $diff);
        $diff -= $newposition - $position;
        $protocol .= '<br>
<b>' . $min . ' минута</b>: ' . ($goal && abs($newposition) == 2 ?
'Невероятно! Буквально через минуту еще один мяч влетает в сетку ворот ' . ($newposition == 2 ? 'гостей' : 'хозяев') . '! ':
auto_comment($position, $newposition, $min_diff, ball('home', $i, $size), ball('away', $i, $size)));
        $min = ($min == 90 || $min == 45) ? $min .= '+' : $min + 1; // время продолжения действий на этом МдП
        $min_diff++;
        if ($newposition == -2) { // отличившиеся у гостей
          list ($goaleador, $assistant) = goal('away', $i, $size);
          $goal = 4;  // 4 и 2 = гости забили гол
        }
        elseif ($newposition == 2) { // отличившиеся у хозяев
          list ($goaleador, $assistant) = goal('home', $i, $size);
          $goal = 3;  // 3 и 1 = хозяева забили гол
        }
        if ($goal > 2) {
          $goal -= 2; // чтобы эта ветка не срабатывала при розыгрыше мяча после гола
          $position = 0; // мяч в центре
          $protocol .= '<br>
<b> Счёт ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '.</b>
Гол забил <b>' . $goaleador . '</b> с подачи <b>' . $assistant . '.</b><br>
' . ($newposition > 0 ? 'Гости' : 'Хозяева') . ($diff == 0 ? ' начинают с центра поля.' : ' теряют мяч после розыгрыша в центре.');
        }
        else $position = $newposition;
      } while ($diff != 0);

      if ($half1 && $lastplayed >= 3 && $i >= 2) {
        $half1 = false;
        $position = 0;
        $protocol .= '
<br>
<br>
<b>Звучит свисток на перерыв.';
        $protocol .= ' Счёт первого тайма: ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '</b>.';
      }
      $protocol .= '<br>
';
     }
      if ($half2 && $i == 2 && isset($replaces[4])) {
        $protocol .= '<b>В перерыве</b> тренер';
        if (isset($replaces[4]['home']) && isset($replaces[4]['away'])) {
          $out = '';
          $in = '';
          foreach ($replaces[4]['home'] as $p) {
            if ($p < $size) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          $protocol .= 'ы обеих команд внесли изменения в составы:<br>- у хозяев вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br>';
          $out = '';
          $in = '';
          foreach ($replaces[4]['away'] as $p) {
            if ($p < $size) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          $protocol .= '- у гостей вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br>';
        }
        elseif (isset($replaces[4]['home'])) {
          $out = '';
          $in = '';
          foreach ($replaces[4]['home'] as $p) {
            if ($p < $size) $out .= ', ' . $players['home'][$p]['name'];
            else $in .= ', ' . $players['home'][$p]['name'];
          }
          $protocol .= ' хозяев внёс изменение в состав: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br>';
        }
        else {
          $out = '';
          $in = '';
          foreach ($replaces[4]['away'] as $p) {
            if ($p < $size) $out .= ', ' . $players['away'][$p]['name'];
            else $in .= ', ' . $players['away'][$p]['name'];
          }
          $protocol .= ' гостей внёс изменение в состав: вместо<b>' . ltrim($out, ',') . '</b> на поле ';
          strrpos($in, ',') ?  $protocol .= 'вышли' : $protocol .= 'вышел';
          $protocol .= '<b>' . ltrim($in, ',') . '.</b><br>';
        }
      }
    }

    if ($finished == 6) {
      // лучший игрок матча: все очки, все голы, пасы, позиция, имя, команда
      $c = [];
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
<br>
<b>Финальный свисток - матч завершился со счётом ' . $teams['home']['goal'] . '-' . $teams['away']['goal'] . '.</b><br>
<br>
Лучшим игроком матча признан <b>'.$c['name'][0].' ('.$c['team'][0].')</b> - баллы: '. $c['summ'][0].($c['goal'][0] ? ', голы: '.$c['goal'][0] : '').($c['pass'][0] ? ', голевые передачи: '.$c['pass'][0] : '').'<br>
';
    }
    $protocol .= '<br>
';
    eval('$sites = '.file_get_contents($season_dir.'/sites.inc'));
    $prognozlist .= '
' . $homelist . $sites[$teams['home']['team']] . '
<b>' . $homeline . '</b>
<b>' . $awayline . '</b>
' . $sites[$teams['away']['team']] . '<br>' . $awaylist;

      // определяем, изменился ли счёт в матче
      $score = $teams['home']['goal'].':'.$teams['away']['goal'].' ('.$teams['home']['total'].'-'.$teams['away']['total'].')';
      if ($t < '12')
      {
        if ($matchn == 1 || $matchn == 15) ///// поменять!!!
          $cal .= '<li class="h6"><br>'.($matchn == 1 ? 'Лига Сайтов' : 'Лига Наций').'</li>
';
      }
      else if ($t > '95')
      {
        if ($matchn == 1)
          $cal .= '<li class="h6"><br>Товарищеские матчи</li>
';
      }
      else
        if ($matchn == 1 || $matchn == 4) ///// поменять!!!
          $cal .= '<li class="h6"><br>'.($matchn == 1 ? 'Финальный турнир' : 'Товарищеские матчи').'</li>
';
//          $cal .= '<li class="h6"><br>'.($matchn == 1 ? 'Лига Сайтов' : 'Лига Наций').'</li>
//';

      $cal .= '<li>
  <a href="javascript:void(0)" onClick="showTab('.$matchn.')">'.short_name($teams['home']['team']).' - '.short_name($teams['away']['team']).'</a>
  <div class="mt-res">'.$score.'</div>
</li>';
      if (!isset($prognoz_str) && $scorez[$teams['home']['team'].' - '.$teams['away']['team']] != $score)
      {
        $scorez[$teams['home']['team'].' - '.$teams['away']['team']] = $score;
        $renew = true;
      }
      // формирование блока матча с заголовком
      $match_title = '<div style="width:576px;min-width:576px">
  <h5>'.short_name($teams['home']['team']).' - '.short_name($teams['away']['team']).' '.$score.'</h5>
';
      if ($lastplayed && $rprognoz[0] != '?')
        $match_title .= '
  <p>
  владение мячом: ' . round(100 * $teams['home']['total'] / ($teams['home']['total'] + $teams['away']['total'])) . ' / ' . round(100 * $teams['away']['total'] / ($teams['home']['total'] + $teams['away']['total'])) . '%,
  время в атаке: ' . round(100 * $teams['home']['atack'] / $finished) . ' / ' . round(100 * $teams['away']['atack'] / $finished) . '%
  </p>
';
      $match_title .= '
</div>';

      $prognozlists[] = '<div id="tab-'.$matchn.'" class="multitabs"'.(isset($n) && $matchn == $n || $published && $matchn == 1 ? '' : ' style="display:none"').'>
'.$match_title.'
  <div class="monospace">
'.$prognozlist_head . $prognozlist.'
  </div>
  <div class="p-left">
'.$protocol.'
  </div>
</div>';

      if ($published)
        if ($league == 'n')
        {
          $players_n[] = $players;
          $teams_n[] = $teams;
        }
        else
        {
          $players_s[] = $players;
          $teams_s[] = $teams;
        }

    } // конец цикла матчей сборных
    $prognozlist = '';
    foreach ($prognozlists as $block)
      $prognozlist .= $block;

    if ($renew)
      rewrite_cal($prognoz_dir, $scorez);

  } // конец условия "closed"
  else
  {
    $i = 1;
    foreach ($scorez as $match => $score)
    {
      if ($t < '12')
      {
        if ($i == 1 || $i == 15) ///// поменять!!!
          $cal .= '<li class="h6">'.($i == 1 ? 'Лига Сайтов' : '<br>Лига Наций').'</li>
';
      }
      else if ($t > '95')
          $cal .= '<li class="h6">'.($i == 1 ? 'Товарищеские матчи' : '').'</li>
';
      else
        if ($i == 1 || $i == 4) ///// поменять!!!
          $cal .= '<li class="h6">'.($i == 1 ? 'Финальный турнир' : 'Товарищеские матчи').'</li>
';

      $cal .= '<li>
  <a href="javascript:void(0)" onClick="showTab('.$i++.')">'.short_name($match).'</a>
  <div class="mt-res">'.$score.'</div>
</li>';
    }
  }
  $cal .= '</ul>
';
}   // конец условия "есть календарь тура"

$hint = '';
if (!$published)
  foreach (['n', 's'] as $league)
  {
    if (($t < 12 || $t > 95 || $league == 's') && $roles[$league] == 'coach') {
      if (is_file($prognoz_dir.'/.'.$cc[$league])) {
        $his = file($prognoz_dir.'/.'.$cc[$league]);
        foreach ($his as $his_line) {
          list($name, $what, $timestamp) = explode(';', trim($his_line));
          $history[$league][$timestamp][] = ['event' => 'coach', 'name' => $name, 'what' => $what];
        }
      }
      $hint .= '<a href="" onClick=\'$("#chronology-'.$league.'").toggle(); return false\'>показать/скрыть историю тура в '.(($t < 12 || $t > 95) ? 'Лиге '.($league == 's' ? 'Сайтов' : 'Наций&nbsp;') : 'Финальном турнире').'</a><br>';
      $hint .= '<p id="chronology-'.$league.'" class="hidden text-left">';
      if (isset($history[$league]))
        foreach ($history[$league] as $timestamp => $events)
          foreach ($events as $event)
            $hint .= date_tz('Y-m-d H:i:s ', '', $timestamp, $_COOKIE['TZ'] ?? 'Europe/Berlin') . $event['name'] . ($event['event'] == 'predict' ? ' прислал прогноз ' : ' внес изменения в ') . $event['what'] . "<br>\n";

      $hint .= '</p>
';
    }
  }

if ($closed)

  $rules = '
<br>
Баллы всех игроков основного состава (показаны на желтом фоне) за каждый реальный матч (МдП) суммируются.<br>
Суммы показаны в строках команд между таблицами игроков (в конце - общий балл команды и эффективность замен).<br>
Возможные позиции мяча на поле: <b>защита - центр - атака - гол</b> (после гола и в начале таймов мяч находится в центре).<br>
Разность сумм баллов, набранных командами на МдП, определяет изменение положения на поле: преимущество в<br>
<span class="b-cyan">3-7</span> баллов приносит 1 позиционное изменение; <span class="b-yell">8-12</span> баллов - 2;
<span class="b-lime">13-17</span> баллов - 3; <span class="b-pink">18 и более</span> баллов - 4 позиционных изменения.<br>
Для того, чтобы забить гол из положения "атака" достаточно 1-го позиционного изменения, из положения "защита - необходимо<br>
3 изменения. 3 или 4 позиционных изменения могут привести к 2-м забитым голам на одном МдП, если команда находилась в<br>
атаке или в центре/атаке соответственно.<br>
<hr>
* - использован прогноз-генератор.';

else
{
  $hint .= '<p class="red">Срок отправки прогнозов '.date_tz('d.m H:i', substr($lastdate, 3, 2).'-'.substr($lastdate, 0, 2), $lasttm, $_COOKIE['TZ'] ?? 'Europe/Berlin').'<br></p>';
  $rules = '
<br>
В каждом тайме 3 матча, а каждый матч имеет 3 возможных исхода - итого 9 исходов в тайме.<br>
На все исходы надо расставить цифры от 1 до 9 так, чтобы каждая использовалась в тайме ровно один раз,
и получилась максимальная сумма на угаданных событиях, например: 921 843 765 238 149 576.<br>
Победа хозяев показана слева, ничья - в центре, победа гостей - справа.<br>
После расстановки всех 18 шаров должна заполниться строка "прогноз" - можно нажать кнопку "отправить".<br>
Если на вашем устройстве шары не двигаются, пожалуйста, сами заполните строку "прогноз" в таком порядке:
победа хозяев, ничья, победа гостей в 1-м матче, победа хозяев, ничья, победа гостей во 2-м матче и т.д.<br>
';
  $match_title = '';
  if ($roles['n'] == 'coach' || $roles['s'] == 'coach')
    $prognozlist = coach_room($roles, $cc, $all_predicts);

}

// REST-обработчики
if (isset($matches))       // REST responce on event 'matches'
  echo '[' . $id_json . ']';
else if (isset($updates)) // REST responce on event 'FT'
  echo '[{"id":"#dynamic","html":"' . rawurlencode('
	<div id="mt1">Матчи тура:' . $cal . '</div>
	<div id="pl" class="" tabindex="-1">' . $prognozlist . '</div>
') . '"}]';
else
{
  $html = $closed ? '' : '
<link rel="stylesheet" href="/css/balls.css?ver=17">';
  $html .= '
<script>//<![CDATA[';
  $html .= $published ? '' : '
var '.date_tz('\h\o\u\r\s=G,\m\i\n\u\t\e\s=i,\s\e\c\o\n\d\s=s', '', time(), $_COOKIE['TZ'] ?? 'Europe/Berlin').',sendfp='.(date('G')>2&&$today_matches>2*$today_bz?'true':'false').',base=[],mom=[]
' . $id_arr . '
function newpredict(){var p="";m=$("#pl").data("view")?6:18;for(i=1;i<=m;i++)p+=(ps=$("#dice"+i).val())?ps:"=";$("#prognoz_str").val(p);}
function predict(id,dice){$("#"+id).val(dice);newpredict()}
function post_predicts(){
  str=$("#prognoz_str").val();nicks=$("#single_code").length?$("#single_code").val():$("#team_codes option:selected").val();
  t=[];t[0]=str.substring(0,9);t[1]=str.substring(9);
  e="";for(i=0;i<2;i++)for(j=1;j<=9;j++)if(t[i].split(j).length!=2)e+="в"+(i?"о":"")+" "+(i+1)+"-м тайме кол-во ставок \""+j+"\" не равно 1\n";
  if(e=="")
    sendPredicts("'.$apikey.'","'.$tour.'",nicks.split(";"),str.split(";"))
  else{
    var r=confirm("Ошибка:\n"+e+"Вы действительно хотите отправить прогноз в таком виде?");
    if(r==true)
      sendPredicts("'.$apikey.'","'.$tour.'",nicks.split(";"),str.split(";"))

  }
  return false
}
function show_alert(){str=$("#prognoz_str").val();t=[];t[0]=str.substring(0,9);t[1]=str.substring(9);e="";for(i=0;i<2;i++)for(j=1;j<=9;j++)if(t[i].split(j).length!=2)e+="в"+(i?"о":"")+" "+(i+1)+"-м тайме кол-во ставок \""+j+"\" не равно 1\n";if(e==""){document.forms[0].submit();return true;}else{var r=confirm("Ошибка:\n"+e+"Вы действительно хотите отправить прогноз в таком виде?");if(r==true){document.forms[0].submit();return true;}}return false;}
$(function(){$(".dice").change(function(){newpredict()});})
var bets=['.$js_bets.']
function drawball(num){return \'	<li id="ball-\'+num+\'" class="stage"><div class="sortable ball\'+num+\'"><span class="shadow"></span><span class="digit\'+num+\'"></span></div></li>\n\'}
function drawpredict(t){var pr=ou="",uu=[1,2,3,4,5,6,7,8,9];for(i=0;i<9;i++){if(bets[i+t*9]){pr+=bets[i+t*9];delete uu[bets[i+t*9]-1]}else pr+="="}for(i=0;i<9;i++)if(uu[i])ou+=drawball(uu[i]);$("#time"+(t+1)+"-unused").html(ou);return pr}
function fillpredict(){$("#prognoz_str").val(drawpredict(0)+drawpredict(1))}
function makebet(event){
    var num=event.originalEvent.target.innerHTML.charAt(46)
    if (num<0||num>9)
      num=event.originalEvent.target.classList.value.charAt(13)

    var pos=event.target.id.substring(4)
    $("#bet-"+pos).html(drawball(num))
    bets[pos-1]=num
    fillpredict()
}
function unbet(event){var pos=event.target.id.substring(4);bets[pos-1]=0;fillpredict()}
$(document).ready(function(){
  $(".sortable-list-1").sortable({
    connectWith:".sortable-list-1"
  })
  $(".sortable-list-2").sortable({
    connectWith:".sortable-list-2"
  })
  $(".home").sortable({
    receive: function(event){makebet(event)},
    remove: function(event){unbet(event)},
  })
  $("input[type=checkbox]").change(function(){
    if($("#tab-"+$("#dynamic").data("tab")+" input[type=checkbox]:enabled:checked").length==$(this).data("size")&&$("#tab-"+$("#dynamic").data("tab")+" input.bench[type=checkbox]:enabled:checked").length<4){
      $("#replace-"+$("#dynamic").data("tab")).prop("disabled",false);
      $("#replace-"+$("#dynamic").data("tab")).css("color","green")
    }
    else {
      $("#replace-"+$("#dynamic").data("tab")).prop("disabled",true);
      $("#replace-"+$("#dynamic").data("tab")).css("color","red")}
    })
})
momup=function(i){clearInterval(mom[i]);mom[i]=setInterval(function(){if(!isNaN(base[i][3])){tm=+base[i][3];base[i][3]=(tm==45||tm==90)?tm+"+":++tm;row=$("#"+i)[0];row.cells[5].innerHTML="<span class=\"blink\">"+base[i][3]+"’</span>"}},60000)}
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
}';
  $html .= '
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
details=function(dom){mid=dom.attr("id");row=$(".p-table").find("tr[did="+mid+"]");if(row.length)row.remove();else{dom.after("<tr did="+mid+"><td colspan=7 class=\"det\"><div class=\"loaderP\"><div class=\"loader\"></td></tr>");socket.emit("footballdetails",Math.abs(mid))}}
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
socket.on("footdetails",function(data){data=data[0];if ($(".p-table").find("tr[did="+data.id+"]").length)mdetails(data.mdetay,data.id,data.pos1,data.pos2);else if ($(".p-table").find("tr[did=-"+data.id+"]").length)mdetails(data.mdetay,-data.id,data.pos2,data.pos1)})';
  $html .= $published ? '
//]]></script>
<div style="height:20px"></div>' : '
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){if(sendfp){$.post("'.$this_site.'",{a:"'.$a.'",m:"'.$m.'",s:"'.$s.'",t:"'.$t.'",matches:JSON.stringify(d.data.matches)},function(json){$.each(JSON.parse(json),function(idx,obj){base.push(obj.id);base[obj.id]=obj.d})})}$("#statusline").html("")})
socket.on("guncelleme",function(d){var json="";$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}});if(json.length)$.post("'.$this_site.'",{a:"'.$a.'",m:"'.$m.'",s:"'.$s.'",t:"'.$t.'"'.(isset($n)?',n:"'.$n.'"':'').',updates:"["+json+"]"},function(res){JSON.parse(res,function(k,v){if(k=="id")id=v;else if(k=="html")$(id).html(decodeURIComponent(v))})})})
'.($reload > 0 ? 'setTimeout(function(){location.reload();},'.$reload.'000)' : '').'//]]></script>
<div class="d-flex">
	<div id="statusline" class="w-100 text-left">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a></div>
	<div id="timedisplay" class="w-100 text-right">'.$notice.'</div>
</div>';
  $html .= '
<div class="h4 text-center">' . $head . '</div>
<div class="d-flex">
	<div class="table-condensed table-striped mx-auto">' . $program_table . '</div>
</div>
<div class="h6 text-center">' . $hint . '</div>
<div id="dynamic" data-tab="' . ($n ?? 1) . '">
	<div id="mt1">Матчи тура:' . ($cal ?? '') . '</div>
	<div id="pl" class="" tabindex="-1">' . $prognozlist . '</div>
</div>
<div class="p-left">' . $rules . '</div>
';

  if ($published && !is_file($season_dir.'/publish/it'.$t))
  {
    file_put_contents($season_dir.'/publish/it'.$t, $html);
    if ($t < 12 || $t > 95)
    { // основной турнир
      file_put_contents($season_dir.'/publish/plstn'.$t, var_export($players_n, true));
      file_put_contents($season_dir.'/publish/plsts'.$t, var_export($players_s, true));
      file_put_contents($season_dir.'/publish/tmstn'.$t, var_export($teams_n, true));
      file_put_contents($season_dir.'/publish/tmsts'.$t, var_export($teams_s, true));
    }
    else
    { // финальный турнир
      file_put_contents($season_dir.'/publish/plstf'.$t, var_export($players_s, true));
      file_put_contents($season_dir.'/publish/tmstf'.$t, var_export($teams_s, true));
    }
  }
  else
    print($html);

}
?>
