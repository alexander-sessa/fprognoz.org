<?php

# парсинг программки

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

# вырезание тура из календарей всех видов

function GetTourFromCalendar($tour, $cal) {
    $tourn = ltrim(substr($tour, -2), 'C0');
    if (($fr = strpos($cal, $tour)) === false)
        $fr = strpos($cal, ' Тур ' . $tourn);

    if ($fr === false)
        return $fr;

    $fr = strpos($cal, "\n", $fr) + 1;
    if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '='))
        $fr = strpos($cal, "\n", $fr) + 1;

    if ($to = strpos($cal, ' Тур', $fr))
        return substr($cal, $fr, $to - $fr);

    return substr($cal, $fr);
}

function get_card($team, $type) {
    global $cards;

    if ($team == 'Old Boys')
        return '*О';

    if (isset($cards[$team]))
    {
        if ($last = end($cards[$team]))  // было нарушение в прошлом туре
            if ($type == '*' && $last[0] == '*' || substr($last, 1) == 'Ж')
                return $type . 'К';

        while (!$last)
            $last = prev($cards[$team]); // поиск последнего нарушения

        if ($last && substr($last, 1) == 'Ж')
            return $type . 'К';          // была жёлтая, теперь красная
    }
    return $type.'Ж';
}

function get_generator($team) {
    global $aprognoz;
    global $generator;
    global $gs;
    global $hidden;
    global $publish;
    global $st;

    $aprognoz[$team]['prog'] = $publish ? $generator[$st][$gs++] : $hidden;
    $aprognoz[$team]['warn'] = get_card($team, '*');
}

function show_card($card) {
    switch (substr($card, 1, 2))
    {
        case 'Ж': return '<span class="text-white bg-warning px-1">'.$card[0].'<span>';
        case 'К': return '<span class="text-white bg-danger px-1">'.$card[0].'<span>';
        case 'О': return '<span class="text-black bg-info px-1">'.$card[0].'<span>';
        default : return '&nbsp';
    }
}

function virtual_match_preparation($home, $away) {
    global $aprognoz;
    global $results;

    // для обработки прогноза хозяев предварительно надо знать, где гостевой заменитель
    if (isset($aprognoz[$away]['prog']))
    {
        $last5 = trim(substr($aprognoz[$away]['prog'], strpos($aprognoz[$away]['prog'], ' ')));
        $replace = 11 + max (1, strpos($last5, '<')); // позиция выбранного гостем заменителя
        if ($results[$replace] == '-')
            $replace = 0; // помеченный матч не состоялся - естественный порядок замены
    }
    else
        $replace = 0; // для генератора - естественный порядок замены

    foreach (['home' => $home, 'away' => $away] as $side => $team)
    {
        if (!isset($aprognoz[$team]['prog']))
            get_generator($team); // выдача генератора

        $bad = false; // неверное оформление
        // подготовка прогноза к сравнениям
        list($first10, $last5) = explode(' ', $aprognoz[$team]['prog'], 2);
        $last5 = strtr($last5, ['<' => '']); // просто вырезаем, поскольку уже обработали в начале

        # подготовка основной части прогноза

        if ($first10[0] == '(')
        {   // двойник указан перед первой ставкой
            $first10 = substr($first10, strpos($first10, ')') + 1);
            $bad = true;
        }
        if ($side == 'home')
        {
            if ($dpos = strpos($first10, '('))
                $double = $first10[$dpos + 1]; // двойник хозяев
            else
            {
                $double = '';
                $replace = 0;
            }
            //$first10 = str_replace('(' . $double . ')', '', $first10);
            $replace = $dpos > 0 && $results[$dpos - 1] == '-' ? $replace : 0;
            // МдП с двойником не состоялся - использовать заменитель, иначе - естественный порядок
        }
        $brackets = 0;
        while ($cut = strpos($first10, '('))
        {
            $first10 = substr($first10, 0, $cut) . substr($first10, $cut + 3);
            $brackets++;
        }
        if ($brackets > 1)
            $bad = true;

        # проверка длины основного прогноза

        $ln = 10 - strlen($first10);
        if ($ln > 0)
        {
            $first10 .= str_repeat('=', $ln);
            $bad = true;
        }
        else if ($ln < 0)
        {
            $first10 = substr($first10, 0, 10);
            $bad = true;
        }

        # обработка дополнительной части прогноза

        $ln = 5 - strlen($last5);
        if ($ln > 0)
        {   // меньше (5) прогнозов в доп. части
            $last5 .= str_repeat('=', $ln);
            $bad = true;
        }
        else if ($ln < 0)
        {   // больше (5) прогнозов в доп.части
            $last5 = substr($last5, 0, 5);
            $bad = true;
        }

        # карточки за неверное оформление

        $warn = mb_substr(trim($aprognoz[$team]['warn']), 0, 2);
        if ($bad)
        {
            $card_color = substr($warn, 1);
            if (in_array($card_color, ['Ж', 'К']))
                $warn = 'aК';
            else
                $warn = get_card($team, 'o');
        }

        # применение красной карточки

        if (mb_strpos($warn, 'К'))
        {   // штрафная отмена первой ставки
            for ($j=0; $j < 10 && !in_array($first10[$j], ['1', 'X', '2']); $j++);
            $first10[$j] = '=';

            if ($results[$j] == '-')
            {   // если этот матч не состоялся, продублировать карточку на заменяющий
                if ($replace && $dpos == $j + 1 && $results[$replace] != '-')
                    $last5[$replace - 10] = '='; // перенос карты с несыгравшей форы на сыгравший гостевой заменитель
                else
                {   // в остальных случаях находится первый сыгранный дополнительный матч
                    $rep = 11;
                    while ($rep < strlen($results) && ($results[$rep] == '-' || $replace && $rep == $replace))
                        $rep++; // матч отменён или уже использован для замены

                    if ($replace && $rep > strlen($results))
                        $rep = $replace; // если больше некуда, то карту перенесим на заменитель

                }
                $last5[$rep - 11] = '=';
            }
        }
        else if (!in_array(mb_substr($warn, 1), ['Ж', 'О']))
            $warn = '  ';

        $aprognoz[$team]['warn'] = $warn;
        $aprognoz[$team]['prog'] = $first10 . ' ' . $last5;
    }
    return [$double, $dpos, $replace];
}

function show_bet($bet, $event=false, $affect=true) {
    switch ($event)
    {
        case 'moment' : $class = 'text-black bg-warning';  break;
        case 'correct': $class = 'text-primary fw-bolder'; break;
        case 'goal'   : $class = 'text-white bg-success';  break;
        case 'concede': $class = 'text-white bg-danger';   break;
        case 'badluck': $class = 'text-white bg-dark';     break;
        default: $class = '';
    }
    return '<span class="' . $class . ($affect ? '' : ' bg-opacity-50') . ' px-1">' . $bet . '</span>';
}

function virtual_match($home, $away) {
    global $aprognoz;
    global $mdp;
    global $results;

    $VMresults = ['init' => '', 'home' => '', 'away' => '', 'hitsh' => 0, 'hitsa' => 0, 'goalsh' => 0, 'goalsa' => 0, 'gm' => 0];
    list($double, $dpos, $replace) = virtual_match_preparation($home, $away);
    $mt = 10; // количество матчей, участвующих в определении счета
    for ($i = 0; $i < strlen($results); $i++)
    {
        $nm = $i < 11 ? $i + 1 : $i;
        if ($VMresults['goalsh'] + $VMresults['goalsa'] == $mt)
            $mt++; // если на всех МдП забиты голы, матч продлевается

        // определение матчей влияющих на счет:
        // к $mt добавлется компенсация несыгранных матчей
        // гостевой заменитель, не попавший в $mt учитывается отдельно
        $affect = $i <= $mt + substr_count($results, '-', 0, $mt) - ($mt < $replace ? 1 : 0) || $replace == $i + 1;  // МдП влияет на счёт
        if ($results[$i] == '-' || $results[$i] == ' ')
        {   // МдП не состоялся или интервал между 10 и 11 МдП
            $VMresults['init'] .= show_bet($dpos == $i + 1 ? $double : '&nbsp;');
            $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
            $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
        }
        else if ($dpos == $i + 1)
        {   // двойник
            $replace = 0; // матч не отменён - гостевой заменитель не играет
            if ($results[$i] == '?')
            {
                $VMresults['init'] .= show_bet($double, $double == $aprognoz[$away]['prog'][$i] ? false : 'moment');
                if ($aprognoz[$home]['prog'][$i] != $aprognoz[$away]['prog'][$i])
                {   // голевой момент (у гостей, если не совпадает с двойником хозяев)
                    $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], $aprognoz[$home]['prog'][$i] == '=' ? false : 'moment');
                    $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], $double == $aprognoz[$away]['prog'][$i] || $aprognoz[$away]['prog'][$i] == '=' ? false : 'moment');
                    if ($aprognoz[$home]['prog'][$i] != '=' || $double != $aprognoz[$away]['prog'][$i])
                        $VMresults['gm']++;

                }
                else
                {   // голевой момент у хозяев на второй ставке, если она не дублирует основную
                    $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                    $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
                    if ($double != $aprognoz[$away]['prog'][$i])
                        $VMresults['gm']++;

                }
            }

            # МдП сыграл

            else if ($aprognoz[$home]['prog'][$i] == $results[$i] && $aprognoz[$away]['prog'][$i] == $results[$i])
            {   // оба угадали
                $VMresults['hitsh']++;
                $VMresults['hitsa']++;
                $mdp[$nm]['shots'] += 2;
                $VMresults['init'] .= show_bet($double);
                $VMresults['home'] .= show_bet($results[$i], 'correct');
                $VMresults['away'] .= show_bet($results[$i], 'correct');
            }
            else if ($aprognoz[$home]['prog'][$i] == $results[$i] && $aprognoz[$away]['prog'][$i] != $results[$i])
            {   //  гол на основной ставке
                $VMresults['goalsh']++;
                $VMresults['hitsh']++;
                $mdp[$nm]['shots']++;
                $VMresults['init'] .= show_bet($double);
                $VMresults['home'] .= show_bet($results[$i], 'goal');
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], 'concede');
            }
            else if ($aprognoz[$away]['prog'][$i] == $results[$i] && $aprognoz[$home]['prog'][$i] != $results[$i] && $double != $results[$i])
            {   //  гол гостей на двойнике (пенальти)
                $VMresults['goalsa']++;
                $VMresults['hitsa']++;
                $mdp[$nm]['shots']++;
                $VMresults['init'] .= show_bet($double);
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], 'concede');
                $VMresults['away'] .= show_bet($results[$i], 'goal');
            }
            else if ($aprognoz[$away]['prog'][$i] == $results[$i] && $double == $results[$i])
            {   // гости угадали, но хозяева удачно подстраховались
                $VMresults['hitsa']++;
                $VMresults['init'] .= show_bet($double, 'correct');
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($double, 'correct');
            }
            else if ($double == $results[$i] && $double != $aprognoz[$away]['prog'][$i])
            {   // хозяева забили на дополнительной ставке
                $VMresults['goalsh']++;
                $VMresults['init'] .= show_bet($double, 'goal');
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], 'concede');
            }
            else
            {   // оба не угадали
                $VMresults['init'] .= show_bet($double);
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
            }
        }
        else if ($replace == $i + 1)
        {   //  фора хозякв
            $VMresults['init'] .= show_bet('Ф');
            if ($results[$i] == '?')
            {   // фора хозяев - всегда голевой момент (кроме гипотетического случая, когда у обоих "=")
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], $aprognoz[$home]['prog'][$i] == '=' ? false : 'moment');
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], $aprognoz[$home]['prog'][$i] == $aprognoz[$away]['prog'][$i] || $aprognoz[$away]['prog'][$i] == '=' ? false : 'moment');
//                if ($aprognoz[$home]['prog'][$i] != '=' || $aprognoz[$away]['prog'][$i] != '=')
                    $VMresults['gm']++;

            }
            else if ($aprognoz[$home]['prog'][$i] == $results[$i])
            {   //  гол на форе
                $VMresults['goalsh']++;
                $VMresults['hitsh']++;
                $mdp[$nm]['shots']++;
                $VMresults['home'] .= show_bet($results[$i], 'goal');
                if ($aprognoz[$away]['prog'][$i] == $results[$i])
                {   // пропуск на форе
                    $VMresults['hitsa']++;
                    $mdp[$nm]['shots']++;
                    $VMresults['away'] .= show_bet($results[$i], 'badluck');
                }
                else
                    $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], 'concede');

            }
            else if ($aprognoz[$away]['prog'][$i] == $results[$i])
            {   //  гол гостей на форе (пенальти)
                $VMresults['goalsa']++;
                $VMresults['hitsa']++;
                $mdp[$nm]['shots']++;
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], 'concede');
                $VMresults['away'] .= show_bet($results[$i], 'goal');
            }
            else
            {   // оба не угадали
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
            }
        }
        else if ($aprognoz[$home]['prog'][$i] == $aprognoz[$away]['prog'][$i])
        {   //  одинаковый прогноз
            $VMresults['init'] .= show_bet('&nbsp;');
            if ($aprognoz[$home]['prog'][$i] == $results[$i])
            {   //  оба угадали
                $VMresults['hitsh']++;
                $VMresults['hitsa']++;
                $mdp[$nm]['shots'] += 2;
                $VMresults['home'] .= show_bet($results[$i], 'correct');
                $VMresults['away'] .= show_bet($results[$i], 'correct');
            }
            else
            {   // оба мимо или МдП ещё не сыгран
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
            }
        }
        else
        {
            $VMresults['init'] .= show_bet('&nbsp;');
            if ($results[$i] == '?')
            {   // МдП ещё не сыгран
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], $aprognoz[$home]['prog'][$i] == '=' ? false : 'moment', $affect);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], $aprognoz[$away]['prog'][$i] == '=' ? false : 'moment', $affect);
                if ($affect)
                    $VMresults['gm']++; //  матч, влияющий на счёт

            }
            else if ($aprognoz[$home]['prog'][$i] == $results[$i])
            {   //  угадали хозяева
                if ($affect)
                    $VMresults['goalsh']++;

                $VMresults['hitsh']++;
                $mdp[$nm]['shots']++;
                $VMresults['home'] .= show_bet($results[$i], 'goal', $affect);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i], 'concede', $affect);
            }
            else if ($aprognoz[$away]['prog'][$i] == $results[$i])
            {   //  угадали гости
                if ($affect)
                    $VMresults['goalsa']++;

                $VMresults['hitsa']++;
                $mdp[$nm]['shots']++;
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i], 'concede', $affect);
                $VMresults['away'] .= show_bet($results[$i], 'goal', $affect);
            }
            else
            {   // непредвиденная ситуация
                $VMresults['home'] .= show_bet($aprognoz[$home]['prog'][$i]);
                $VMresults['away'] .= show_bet($aprognoz[$away]['prog'][$i]);
            }
        }
    }
    return $VMresults;
}

function bots() {
    global $virtmatch;
    global $aprognoz;

    $bots = 0;
    foreach ($virtmatch as $line)
    {
        $teams = explode(' - ', $line);
        foreach ($teams as $team)
            if (!isset($aprognoz[$team]) || $aprognoz[$team]['warn'] && $aprognoz[$team]['warn'][0] == '*')
                $bots++;

    }
    return $bots ? ' (из них генераторов - ' . $bots . ')' : '';
}

$l = $l ?? '';
$tour = $cca == 'UEFA' ? $l . strtoupper($t) : $cca . strtoupper($t);
$season_dir = $online_dir . $cca . '/' . $s . '/';
$tour_dir = $season_dir . 'prognoz/' . $tour . '/';
$show_pen_col = false;
if ($cca == 'UEFA')
{
    $cal_file = $season_dir . $l . '/calc';
    $gen_file = $season_dir . $l . '/genc';
    $cards_file = $season_dir . $l . '/cardsc';
    if ($t == 8 || $t == 10 || $t == 12 || $t == 13)
        $show_pen_col = true;

}
else if ($tour[3] == 'C')
{
    $cal_file = $season_dir . $l . '/calc';
    $gen_file = $season_dir . $l . '/genc';
    $cards_file = $season_dir . $l . '/cardsc';
    if ($tour[4] % 2 == 0 || $tour[4] >= 7 || strlen($tour) == 6 || $cca == 'SUI')
        $show_pen_col = true;

}
else if ($tour[3] == 'S')
{
    $cal_file = $season_dir . '/cals';
    $gen_file = $season_dir . '/gens';
    $cards_file = $season_dir . $l . '/cardss';
    $show_pen_col = true;
}
else if ($tour[3] == 'P')
{
    $cal_file = $season_dir . '/calp';
    $gen_file = $season_dir . '/genp';
    $cards_file = $season_dir . $l . '/cardsp';
    if ($tour[4] > 1)
        $show_pen_col = true;

}
else if ($tour[3] == 'G')
{
    $cal_file = $season_dir . '/cal';
    $gen_file = $season_dir . '/gen';
    $cards_file = $season_dir . $l . '/cardsg';
    $show_pen_col = true;
}
else
{
    $cal_file = $season_dir . '/cal';
    $gen_file = $season_dir . '/gen';
    $cards_file = $season_dir . $l . '/cards';
}
$results = '';
$team_code = $c ?? '';
$stat = false;
$aprognoz = $coach = $have = $lnames = $mdp = $teamCodes = $teams = $virtmatch = [];
$maxteam = $maxcoach = $maxlname = 0;
$acodes = file($season_dir . 'codes.tsv');
foreach ($acodes as $scode)
    if ($scode[0] != '#')
    {
        list($code, $tname, $cname, $email, $lname, $yes_no) = explode('	', ltrim($scode, '-'));
        $teams[$code] = $tname;
        $maxteam = max($maxteam, mb_strlen($tname));
        $coach[$tname] = $cname;
        $maxcoach = max($maxcoach, mb_strlen($cname));
        if (trim($lname))
        {
            $lnames[$tname] = $lname;
            $maxlname = max($maxlname, mb_strlen($lname));
        }
    }

list($program_matches, $lastdate, $lasttm, $program) = parse_program($season_dir . 'programs/' . $tour);
list($cal, $gen) = parse_cal_and_gen($program);
if (is_file($cal_file) && $calt = trim(GetTourFromCalendar(str_replace('NEW', '', $tour), file_get_contents($cal_file))))
    $cal = $calt; // если есть, используем календарь тура из файла календаря турнира

if (trim($cal))
{
    $atemp = explode("\n", $cal);
    $cal = '';
    foreach ($atemp as $line)
        if ($line = trim($line))
        {
            if ($cut = strpos($line, '  '))
                $line = substr($line, 0, $cut);

            $cal .= $line . "\n";
            $virtmatch[] = $line;
        }
}
if (isset($_SESSION['Coach_name']))
{   // составление списка своих команд в этом туре
    foreach ($cmd_db[$cca] as $code => $team)
        if ($team['usr'] == $_SESSION['Coach_name'] && ($cca == 'SUI' || strpos($cal, $team['cmd']) !== false))
            $teamCodes[] = $code;

    # отправка прогноза за несколько или за одну команду

    if (isset($_POST['submitpredict']) && isset($_POST['team_code']) && ($prognoz = trim($_POST['prognoz_str'])))
    {
        if ($_POST['team_code'] == 'один прогноз всем')
            foreach ($teamCodes as $tc)
                send_predict($cca, $s, $tc, $tour, $prognoz, $_POST['enemy_str'], $ip);

        else
        {
            if (trim($_POST['team_code']))
                $team_code = strtr($_POST['team_code'], ['<mark>' => '', '</mark>' => '']);
            else if (!$team_code)
                $team_code = $teamCodes[0];

            send_predict($cca, $s, $team_code, $tour, $prognoz, $_POST['enemy_str'], $ip);
        }
    }
}
$closed = sizeof($teamCodes) ? is_file($tour_dir . 'closed') : true;

# президентские дополнения и поправки прогнозов

if (isset($_POST['addprognoz']))
{
    file_put_contents($tour_dir . 'adds', $_POST['addprognoz']);
    time_nanosleep(0, 5000);
    $new_itog = '';
    if (is_file($season_dir.'publish/'.($cca == 'UEFA' ? $l.'/itc' : 'it').strtolower($t)))
    {
        `/home/fp/fprognoz.org/online/build_results.php "$cca" "$s" "$t"`;
        $new_itog = ', итоги тура перестроены';
    }
    $hint = '<span class="red">дополнения сохранены'.$new_itog.'</span><br>
';
}
else
    $hint = '';

$addfile = is_file($tour_dir . 'adds') ? file_get_contents($tour_dir . 'adds') : '';
if ($role == 'president')
    $hint .= '<a href="#addsform" class="btn btn-sm btn-outline-primary m-1" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="addsform">дополнения и поправки прогнозов (команды, карточки/флаги, время)</a><br>
<form id="addsform" class="collapse" method="POST">
формат дополнений и поправок прогнозов (размер названия, отступы) такой же, как при публикации прогнозов;<br>
пропуск первого матча из-за красной карточки не помечайте - это делается автоматически при расчете итогов;<br>
указывайте среднеевропейское время - это поможет определить прогнозы, отправленные после начала матчей<br>
<textarea name="addprognoz" class="monospace w-100" rows="' . max(6, substr_count($addfile, "\n") + 1) . '">'.htmlspecialchars($addfile).'</textarea><br>
<button class="btn btn-sm btn-primary" type="submit" name="submit">сохранить</button>
</form>
';
$publish = is_file($tour_dir . 'published');
if ($publish || $role == 'president')
    $hint .= '<a href="#chronology" class="btn btn-sm btn-outline-secondary m-1" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="chronology">показать/скрыть хронологию поступления прогнозов</a>';

$hint .= '<p id="chronology" class="collapse monospace">';

# выборка прогнозов из мейлбокса. $tour_dir,$publish,$teamCodes,$role,$teams[] -> $aprognoz[],$closed,$hint

$hidden = 'прогноз не показан';
$mbox = file($tour_dir . 'mail');
foreach ($mbox as $msg)
{
    if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE)
        $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);

    list($team, $prog, $time, $pena) = explode(';', $msg);
    $prog = strtr($prog, ['x' => 'X', 'х' => 'X', 'Х' => 'X', '0' => 'X']);
    if ($publish && in_array($team, $teamCodes))
        $closed = true;

    if (!$publish && !in_array($team, $teamCodes))
        $prog = $hidden;

    if (!isset($teams[$team]))
        $warn = 'oЖ';
    else
    {
        $team = $teams[$team];
        if (in_array($team, $have))
            $warn = '!!!';
        else
        {
            $have[] = $team;
            $warn = '  ';
        }
    }
    if (!isset($aprognoz[$team]['time']) || $time > $aprognoz[$team]['time'])
        $aprognoz[$team] = ['prog' => $prog, 'time' => $time, 'pena' => $pena, 'warn' => $warn];

    if ($prog != $hidden || $role == 'president')
    {
        $hint .= htmlspecialchars(mb_sprintf('%-21s%-20s%-5s', $team, $prog, $warn)) . date_tz('d M y  H:i:s', '', $time, $_COOKIE['TZ'] ?? 'Europe/Berlin') . "\n";
        if (($penalties = trim($pena)) && ($prog != $hidden))
            $hint .= '                     ' . strtolower($penalties) . "\n";

    }
}

# дополнение выбранных прогнозов президентскими данными. $addfile,$hidden,$role $aprognoz[],$hint

if ($addfile)
{
    $hint .= 'дополнения, поправки, наказания:
';
    $added = explode("\n", $addfile);
    foreach ($added as $line)
        if ($line = rtrim($line))
        {
            if ($line[0] == ' ')
            { // строка пенальти начинается с пробела
                $aprognoz[$team]['pena'] = $line; // $team определена в предыдущей строке
                if ($prognoz != $hidden)
                    $hint .= htmlspecialchars($line) . "\n";

            }
            else
            {
                $team = trim(mb_substr($line, 0, 20));
                $line = trim(mb_substr($line, 20));
                if ($cut = min(21, strpos($line, ' ', 15)))
                {
                    $prognoz = trim(substr($line, 0, $cut));
                    $line = trim(substr($line, $cut));
                }
                else
                {
                    $prognoz = trim($line);
                    $line = '';
                }
                if (!$publish && ($team != $teams[$team_code]))
                    $prognoz = $hidden;

                if (isset($line[0]) && is_numeric($line[0]))
                    $warn = '     ';
                else
                {
                    $wln = (mb_strlen($line) > 4 && $line[4] == ' ') ? 5 : 4;
                    $warn = strtr(mb_substr($line, 0, $wln), array('K' => 'К', 'а' => 'a', 'о' => 'o', 'с' => 'c'));
                    $line = mb_substr($line, $wln);
                }
                if ($time = trim(substr($line, 0, 29)))
                    $time = strtotime($time);

                $aprognoz[$team] = ['time' => $time, 'prog' => $prognoz, 'pena' => '', 'warn' => $warn];
                $date = $time ? date_tz('d M y  H:i:s', '', $time, $_COOKIE['TZ'] ?? 'Europe/Berlin') : '';
                if (($prognoz != $hidden) || ($role == 'president'))
                    $hint .= htmlspecialchars(mb_sprintf('%-21s%-20s%-5s', $team, $prognoz, $warn)) . $date . "\n";

            }
        }

}
$hint .= '</p>

';

# заголовок страницы с формой отправки прогноза. $closed,$teamCodes,$tour -> $head

$head = '';
if (!$closed && sizeof($teamCodes))
    $head .= '<form action="" name="tform" enctype="multipart/form-data" method="POST" onSubmit="return show_alert(this);">';

$head .= 'Код тура: <b>' . $tour . '</b>';
if (!$closed && sizeof($teamCodes))
{
    $head .= ', код команды: ';
    if (sizeof($teamCodes) == 1)
        $head .= '<input type="hidden" name="team_code" value="'.$teamCodes[0].'"><b>'.$teamCodes[0].'</b><br>';
    else
    {
        $head .= '<select name="team_code">';
        foreach ($teamCodes as $tc)
        {
            $head .= '<option value="' . $tc . '"';
            $selected = '';
            if (isset($c) && $c == $tc)
                $selected = ' selected="selected"';
            else
                $c = $tc;

            $head .= $selected . '>' . $tc . '</option>';
        }
        $head .= '<option value="один прогноз всем">один прогноз всем</option></select><br>';
    }
    $head .= '<span class="small">прогноз на тур: <input type="text" id="prognoz_str" name="prognoz_str" value="">
<input type="hidden" name="enemy_str" value="">
<input type="submit" name="submitpredict" value=" отправить "></span></form>
<a href="?m=help#frm" class="small" target="_blank">как пользоваться формой отправки прогноза</a>
';
}

# таблица программки тура

require_once ('online/tournament.inc.php');
include ('online/realteam.inc.php');
list($last_day, $last_month) = explode('.', $lastdate);
$year = substr($s, 0, 4);
// прибавляем год для программок со сроком в январе...июле
if ($last_month < 8 && strlen($s) > 6)
    $year++;

if (!isset($updates))
    $updates = NULL;

if ($lastdate == '28.12')
    $year = 2022;

$base = get_results_by_date($last_month, $last_day, $updates, $year);
$program_table = '
<table class="table table-sm table-condensed table-striped p-table mx-auto">
  <thead>
    <tr><th>№</th><th>матч для прогнозирования</th><th>турнир</th><th>дата время</th><th>счёт</th><th>исход</th><th colspan="2">прогноз</th>';
if ($closed && $publish)
    $program_table .= '<th>угадано</th>';

$program_table .= '
    </tr>
  </thead>
<tbody>
';
$id_arr = $id_json = '';
$today_matches = $today_bz = 0;
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
      $mdp[$nm] = ['home' => $home, 'away' => $away, 'trnr' => $tournament, 'date' => $dm, 'rslt' => $mt, 'case' => $rt, 'shots' => 0];
      if ($nm == 11) {
        if ($closed) $colspan = 9;
        elseif ($role != 'badlogin') $colspan = 9;
        else $colspan = 6;
        $program_table .= '<tr><td colspan="' . $colspan . '" style=""></td></tr>
';
        $results .= ' ';
      }
      $program_table .= '<tr'.$tr_id.'><td class="tdn">'.$nm.'</td><td style="'.($tr_id != '' ? 'cursor:pointer" onClick="details($(this).closest(\'tr\'))' : '').'">'.$home.' - '.$away.'</td><td>'.$tournament.'</td><td class="td1">'.date_tz('d.m H:i', $match_date, $tn, $_COOKIE['TZ'] ?? 'Europe/Berlin').'</td><td align="center">&nbsp;'.$mt.'&nbsp;</td><td align="center">&nbsp;'.$rt.'&nbsp;</td>';
//      if (!$closed && ($role != 'badlogin')) {
//        (($publish && $nm == 1) || $rt != '?') ? $onchange = 'disabled="disabled"' :  $onchange = 'onchange="newpredict(); return false;"';
            if (in_array($rt, ['1', 'X', '2', '-']))
            {
                $onchange = 'disabled="disabled" ';
                $val = $rt;
            }
            else
            {
                $onchange = 'onchange="newpredict();"';
                $val = isset($prognoz_str) && $prognoz_str ? $prognoz_str[$nm > 10 ? $nm : $nm - 1] : '';
            }
            $program_table .= '
    <td>
      <nobr>
        <button class="bet bg-primary" onClick="predict('."'dice$nm','1'".'); return false" title="хозяева">1</button>
        <button class="bet bg-success" onClick="predict('."'dice$nm','X'".'); return false" title="ничья">X</button>
        <button class="bet bg-danger"  onClick="predict('."'dice$nm','2'".'); return false" title="гости">2</button>
        <input type="text" name="dice' . $nm . '" value="'.$val.'" id="dice' . $nm . '" class="pr_str" '.$onchange.'>
      </nobr>
    </td>
    <td>
      <nobr>';
      if (!$closed && $role != 'badlogin' && $cca != 'SUI') {
        if ($nm < 11) $program_table .= '
        <button class="bet bg-primary" onClick="securedice('."'ddice$nm','1'".'); return false" title="хозяева">1</button>
        <button class="bet bg-success" onClick="securedice('."'ddice$nm','X'".'); return false" title="ничья">X</button>
        <button class="bet bg-danger"  onClick="securedice('."'ddice$nm','2'".'); return false" title="гости">2</button>';
        else
            $program_table .= '
        <button class="bet bg-info text-black" onClick="securehome('."'ddice$nm'".'); return false" title="замена несыгравшего матча с двойником">&#706;</button>';

        $program_table .= '
        <input type="text" name="ddice' . $nm . '" value="" id="ddice' . $nm . '" class="pr_str" '.$onchange.'>
      </nobr>
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
      if ($publish) $program_table .= '<td><!--' . $nm . '--></td>'
;
      $program_table .= '</tr>
';
      if (strlen($rt) > 1) $rt = '?';
      $results .= $rt;
    }
  }
}
if ($closed)
    $program_table .= '
      <tr class="align-middle no-publish">
        <td colspan="6" class="text-end pe-2 small">
          для расчёта вариантов завершения матчей укажите в колонке "прогноз" возможные исходы и нажмите кнопку
        </td>
        <td colspan="3">
          <button type="button" class="btn btn-outline-dark btn-sm" onClick="calcResults()">что,&nbsp;если?</button>
        </td>
      </tr>';

$program_table .= '
    </tbody>
  </table>
';
if (!$closed)
    $hint = '<p class="red">Контрольный срок отправки прогнозов: '.date_tz($lasttm ? 'd.m H:i' : 'd.m', substr($lastdate, 3, 2).'-'.substr($lastdate, 0, 2), $lasttm, $_COOKIE['TZ'] ?? 'Europe/Berlin').'</p>' . $hint;

# использование симуляции

if (isset($prognoz_str) && $prognoz_str)
{
    $prognoz_str = strtr($prognoz_str, ['=' => '?']);
    for ($i = 0; $i < strlen($prognoz_str); $i++)
        if (in_array($prognoz_str[$i], ['1', 'X', '2']))
            $results[$i] = $prognoz_str[$i];

}

# выборка генераторов

$generator = [];
$gensets = 1;
if (is_file($gen_file))
{
    $gen = file_get_contents($gen_file);
    if (strpos($gen, $tour))
    {
        $begin = $tour;
        $end = $cca;
    }
    else
    {
        $nt = ltrim(substr($tour, strlen($cca) + 1), '0');
        $begin = 'Тур ' . $nt;
        $end = 'Тур';
    }
    $fr = mb_strpos($gen, $begin);
    $fr = mb_strpos($gen, "\n", $fr) + 1;
    if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '='))
        $fr = mb_strpos($gen, "\n", $fr) + 1;

    if ($to = mb_strpos($gen, $end, $fr))
        $gen = trim(mb_substr($gen, $fr, $to - $fr));
    else
        $gen = trim(mb_substr($gen, $fr));
}
if ($gen)
{
    $gen = str_replace('*', '', $gen);
    $atemp = explode("\n", $gen);
    foreach ($atemp as $line)
        if ($line = trim($line))
        {
            if ($cut = mb_strpos($line, '  '))
            {
                $generator['1'][] = trim(mb_substr($line, 0, $cut));
                $line = trim(substr($line, $cut));
                if ($cut = mb_strpos($line, '  '))
                {
                    $generator['2'][] = trim(mb_substr($line, 0, $cut));
                    $generator['3'][] = trim(mb_substr($line, $cut));
                    $gensets = 3;
                }
                else
                {
                    $generator['2'][] = trim($line);
                    $gensets = 2;
                }
            }
            else
                $generator['1'][] = trim($line);

        }

}

# виртуальные матчи

$prognozlist = '';
if ($stat)
{   // выдача виртуальных матчей делается в 2-х форматах: scanprog (со временем) и stat (с результатом)
    $cards = [];
    if (is_file($cards_file))
    {
        $atemp = file($cards_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($atemp as $line)
            $cards[trim(substr($line, 0, 20))] = explode(',', mb_substr(rtrim($line, ','), 20));

    }
    $nm = $gs = 0;
    $st = 1;
    $tour_size = sizeof($virtmatch);
    $z = $tour_size / $gensets;
    $block_size = round($tour_size / ceil($tour_size / 10));
    $plrs = [];
    for ($i = 0; $i < strlen($results); $i++)
        $plrs[$i] = 0;

    $line = '';
    $aresults = str_split($results);
    foreach ($aresults as $result)
        $line .= show_bet($result);

    $thead = '
      <thead>
        <tr class="vmtab_header">
          <td><div class="small text-end">реальные исходы:</div></td>
          <td class="match_line font-monospace fw-bold" style="font-size: 105%;">' . $line . '</td>
          <td class="match_score small">счёт</td>
        </tr>
      </thead>';

    $prognozlist .= '
    <div>
      Количество прогнозов - ' . (sizeof($virtmatch) * 2) . bots() . '
    </div>';
}
else
    $thead = '
    <thead>
      <tr>
        <td class="small">виртуальные матчи</td>
        <td class="small">прогнозы</td>
        <td class="small">время поступления</td>
      </tr>
    </thead>';

$prognozlist .= '
  <table class="table table-sm table-condensed table-striped" style="max-width: 48rem">' . $thead . '
    <tbody>';

# формирование таблиц виртуальных матчей

$enemy = '';
foreach ($virtmatch as $line)
{
    list($home, $away) = explode(' - ', $line);
    if ($team_code)
    {   // определение противника открывшего страницу
        if ($home == $teams[$team_code])
            $enemy = $away;
        else if ($away == $teams[$team_code])
            $enemy = $home;
    }

    if ($stat)
    {
        $vm = virtual_match($home, $away);
        $prognozlist .= '
        <tr class="virtual_match">
          <td class="team_name" style="font-size: 105%;">
            <div style="line-height: 90%;">&nbsp;</div>
            <div>' . $home . '<sup> ' . show_card($aprognoz[$home]['warn']) . '</sup></div>
            <div style="padding-bottom: .25rem;">' . $away . '<sup> ' . show_card($aprognoz[$away]['warn']) . '</sup></div>
          </td>
          <td class="match_line font-monospace fw-bold" style="font-size: 105%;">
            <div class="init_line" style="line-height: 90%;">' . $vm['init'] . '</div>
            <div class="home_line">' . $vm['home'] . '</div>
            <div class="away_line">' . $vm['away'] . '</div>
          </td>
          <td class="match_score">
            <div class="sc_chances" style="line-height: 105%;" title="голевые моменты">
              <sub>' . ($vm['gm'] ? $vm['gm'] . ' г.м.' : '&nbsp;') . '</sub>
            </div>
            <div class="home_score"><b>' . $vm['goalsh'] . '</b> (' . $vm['hitsh'] . ')</div>
            <div class="away_score"><b>' . $vm['goalsa'] . '</b> (' . $vm['hitsa'] . ')</div>
          </td>
        </tr>';
        if (++$nm > $z)
        {
            $gs = 0;
            $st++;
        }
        if ($nm < $tour_size && $nm % $block_size == 0)
            $prognozlist .= '
  </tbody>' . $thead . '
  <tbody>';
    }
    else
        $prognozlist .= '
        <tr>
          <td class="team_name">
            <div>' . $home . '<sup> ' . (($aprognoz[$home]['warn'] ?? false) ? show_card($aprognoz[$home]['warn']) : '&nbsp;') . '</sup></div>
            <div>' . $away . '<sup> ' . (($aprognoz[$away]['warn'] ?? false) ? show_card($aprognoz[$away]['warn']) : '&nbsp;') . '</sup></div>
          </td>
          <td class="match_line">
            <div>' . strtr($aprognoz[$home]['prog'] ?? '&nbsp;', ['<' => '&lt;']) . '</div>
            <div>' . strtr($aprognoz[$away]['prog'] ?? '&nbsp;', ['<' => '&lt;']) . '</div>
          </td>
          <td class="match_score">
            <div>' . (($aprognoz[$home]['time'] ?? false) ? date_tz('d M y  H:i:s', '', $aprognoz[$home]['time'], $_COOKIE['TZ'] ?? 'Europe/Berlin') : '&nbsp;') . '</div>
            <div>' . (($aprognoz[$away]['time'] ?? false) ? date_tz('d M y  H:i:s', '', $aprognoz[$away]['time'], $_COOKIE['TZ'] ?? 'Europe/Berlin') : '&nbsp;') . '</div>
          </td>
        </tr>';

}
$prognozlist .= '
  </tbody>' . $thead . '
</table>';

# таблица числа угадавших прогнозы для формата stat

if ($stat)
{
    $results1 = str_replace(' ', '', $results);
    for ($i = 1; $i <= strlen($results1); $i++)
    {
        $j = $i > 10 ? $i : $i - 1; // заплата для учёта пробела в 'prog' и $results
        $mdp[$i]['playr'] = '';
        if (in_array($results1[$i - 1], ['-', '?']))
            $mdp[$i]['shots'] = '-';
        else if (!isset($mdp[$i]['shots']) || $mdp[$i]['shots'] == 0)
        {
            $mdp[$i]['shots'] = 0;
            $mdp[$i]['playr'] = ':(';
        }
        else if ($mdp[$i]['shots'] == $plrs[$i])
            $mdp[$i]['playr'] = ':)';

        if ($mdp[$i]['shots'] == 1)
        {
//if($_SESSION['Coach_name'] == 'Александр Сесса')
            foreach ($aprognoz as $team => $data)
                if ($data['warn'][0] != '*' && $data['prog'][$j] == $results[$j])
                {
                    $mdp[$i]['playr'] = $coach[$team];
                    break;
                }

        }
        if (($plrs[$i - 1] > 2) && ($plrs[$i - 1] - $mdp[$i]['shots'] == 1))
        {
            foreach ($aprognoz as $team => $data)
            {
                $progn = $data['prog'][$i];
                if ($data['warn'][0] != '*' && $progn != '=' && $progn != $results[$j])
                {
                    $mdp[$i]['playr'] = $coach[$team];
                    break;
                }
            }
        }
        $program_table = str_replace('<!--' . $i . '-->', $mdp[$i]['shots'] . ' ' . $mdp[$i]['playr'], $program_table);
    }
}

# подсветка своей команды

foreach ($teamCodes as $code)
    $prognozlist = str_replace('>' . $teams[$code] . '<', '><span class="magenta">' . $teams[$code] . '</span><', $prognozlist);

# вывод контента
//tomilen-bc
//tomilen-bc
if (isset($matches))
    echo '[' . $id_json . ']'; // REST responce on event 'matches'
else if ($updates)
    echo ''; //$prognozlist; // REST responce on event 'FT'
else
    echo '<script>
var sendfp='.(date('G',time())>2&&$today_matches>2*$today_bz?'true':'false').',base=[]
' . $id_arr . '
function showBet(bet,event=false,affect=true){
	if(bet=="")
		bet="?"

	switch (event)
	{
		case "moment":	cls="text-black bg-warning";	break
		case "correct":	cls="text-primary fw-bolder";	break
		case "goal":	cls="text-white bg-success";	break
		case "concede":	cls="text-white bg-danger";	break
		case "badluck":	cls="text-white bg-dark";	break
		default:	cls=""
	}
	return "<span class=\""+cls+(affect?"":" bg-opacity-50")+" px-1\">"+bet+"</span>"
}
function virtualMatch(results,el){
	var home=[],away=[],double="",dpos=-1,replace=-1,mt=10; // количество матчей, участвующих в определении счета
	var vm={"init":"","home":"","away":"","hh":0,"ha":0,"gh":0,"ga":0,"gm":0}
	el.find(".init_line span").each(function(i){
		if($(this).html()=="Ф") replace=i
		else if($(this).html()!="&nbsp;"){double=$(this).html();dpos=i}
	})
	el.find(".home_line span").each(function(){home.push($(this).html())})
	el.find(".away_line span").each(function(){away.push($(this).html())})
	for(i=0;i<results.length;i++){
		nm=i<11?i+1:i
		if(vm.gh+vm.ga==mt)mt++; // если на всех МдП забиты голы, матч продлевается
		affect=(i<=mt+results.slice(0,mt<10?mt-1:mt).filter(b=>b=="-").length-(mt<replace?1:0))||(replace==i+1);  // МдП влияет на счёт
		if(results[i]=="-"||results[i]==" ")
		{ // МдП не состоялся или интервал между 10 и 11 МдП
			vm.home+=showBet(home[i])
			vm.away+=showBet(away[i])
		}
		else if(dpos==i)
		{ // двойник
			if(results[i]=="?"||results[i]=="")
			{
				if(home[i]!=away[i])
				{ // голевой момент (у гостей, если не совпадает с двойником хозяев)
					vm.home+=showBet(home[i],home[i]=="="?false:"moment")
					vm.away+=showBet(away[i],double==away[i]||away[i]=="="?false:"moment")
					if(home[i]!="="||double!=away[i])
						vm.gm++;

				}
				else
				{ // голевой момент у хозяев на второй ставке
					vm.home+=showBet(home[i])
					vm.away+=showBet(away[i])
					if(double!=away[i])
						vm.gm++;

				}
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double,double!=away[i]?"moment":false))
			}
			else if(home[i]==results[i]&&away[i]==results[i])
			{ // оба угадали
				vm.hh++
				vm.ha++
				mdp[nm]+=2
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double))
				vm.home+=showBet(home[i],"correct")
				vm.away+=showBet(away[i],"correct")
			}
			else if(home[i]==results[i]&&away[i]!=results[i])
			{ // гол на основной ставке
				vm.gh++
				vm.hh++
				mdp[nm]++
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double))
				vm.home+=showBet(home[i],"goal")
				vm.away+=showBet(away[i],"concede")
			}
			else if(away[i]==results[i]&&home[i]!=results[i]&&double!=results[i])
			{ // гол гостей на двойнике (пенальти)
				vm.ga++
				vm.ha++
				mdp[nm]++
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double))
				vm.home+=showBet(home[i],"concede")
				vm.away+=showBet(away[i],"goal")
			}
			else if(away[i]==results[i]&&double==results[i])
			{ // гости угадали, но хозяева удачно подстраховались
				vm.ha++
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double,"correct"))
				vm.home+=showBet(home[i])
				vm.away+=showBet(double,"correct")
			}
			else if(double==results[i]&&double!=away[i])
			{ // хозяева забили на дополнительной ставке
				vm.gh++
				el.find(".init_line span").eq(dpos).replaceWith(showBet(double,"goal"))
				vm.home+=showBet(home[i])
				vm.away+=showBet(away[i],"concede")
			}
			else
			{ // оба не угадали
				vm.home+=showBet(home[i])
				vm.away+=showBet(away[i])
			}
		}
		else if(replace==i)
		{ // фора хозяев
			if(results[i]=="?"||results[i]=="")
			{ // фора хозяев - всегда голевой момент
				vm.home+=showBet(home[i],home[i]=="="?false:"moment")
				vm.away+=showBet(away[i],home[i]==away[i]||away[i]=="="?false:"moment")
				if(home[i]!="="||away[i]!="=")
					vm.gm++

			}
			else if(home[i]==results[i])
			{ // гол на форе
				vm.gh++
				vm.hh++
				mdp[nm]++
				vm.home+=showBet(home[i],"goal")
				if(away[i]==results[i])
				{ // пропуск на форе
					vm.ha++
					mdp[nm]++
					vm.away+=showBet(away[i],"badluck")
				}
				else
					vm.away+=showBet(away[i],"concede")

			}
			else if(away[i]==results[i])
			{ // гол гостей на форе (пенальти)
				vm.ga++
				vm.ha++
				mdp[nm]++
				vm.home+=showBet(home[i],"concede")
				vm.away+=showBet(away[i],"goal")
			}
			else
			{ // оба не угадали
				vm.home+=showBet(home[i])
				vm.away+=showBet(away[i])
			}
		}
		else if(home[i]==away[i])
		{ // одинаковый прогноз
			if(home[i]==results[i])
			{ // оба угадали
				vm.hh++
				vm.ha++
				mdp[nm]+=2
				vm.home+=showBet(home[i],"correct")
				vm.away+=showBet(away[i],"correct")
			}
			else
			{ // оба мимо или МдП ещё не сыгран
				vm.home+=showBet(home[i])
				vm.away+=showBet(away[i])
			}
		}
		else if(results[i]=="?"||results[i]=="")
		{ // МдП ещё не сыгран
			vm.home+=showBet(home[i],home[i]=="="?false:"moment",affect)
			vm.away+=showBet(away[i],away[i]=="="?false:"moment",affect)
			if(affect)
				vm.gm++

		}
		else if(home[i]==results[i])
		{ // угадали хозяева
			if(affect)
				vm.gh++

			vm.hh++
			mdp[nm]++
			vm.home+=showBet(home[i],"goal",affect)
			vm.away+=showBet(away[i],"concede",affect)
		}
		else if(away[i]==results[i])
		{ // угадали гости
			if(affect)
				vm.ga++

			vm.ha++
			mdp[nm]++
			vm.home+=showBet(home[i],"concede",affect)
			vm.away+=showBet(away[i],"goal",affect)
		}
		else
		{
			vm.home+=showBet(home[i])
			vm.away+=showBet(away[i])
		}
	}
	return vm
}
function calcResults(){
	var results=[],result_line=""
	mdp=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
	$(".pr_str").each(function(){results.push($(this).val())})
	results.splice(10, 0, " ")
	results.forEach(function(value){result_line+=showBet(value)})
	$(".vmtab_header .match_line").html(result_line)
	$(".virtual_match").each(function(){
		vm=virtualMatch(results,$(this))
		$(this).find(".home_line").html(vm.home)
		$(this).find(".away_line").html(vm.away)
		$(this).find(".sc_chances").html("<sub>"+(vm.gm>0?vm.gm+" г.м.":"&nbsp;")+"</sub>")
		$(this).find(".home_score").html("<b>"+vm.gh+"</b> ("+vm.hh+")")
		$(this).find(".away_score").html("<b>"+vm.ga+"</b> ("+vm.ha+")")
	})
	$(".p-table tbody tr").each(function(i){$(this).find("td").eq(8).html(mdp[i<10?i+1:i])})
}

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
	$("#"+id).val($("#"+id).val()==dice?"":dice);
	newpredict();
}
function securedice(id,dice){
//	if($("#"+id).prop("disabled"))return false;
	var i,dd;
	var r=$("#"+id).val()
	for(i=1;i<=10;i++){
		dd="ddice"+i;
		$("#"+dd).val("");
		if(r)
			$("#"+dd).prop("disabled",false);
		else if(dd==id){
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
	calcResults()
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
socket.on("guncelleme",function(d){var json="";$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}});if(json.length)$.post("'.$this_site.'",{updates:"["+json+"]",a:"'.$a.'"'.($l?',l:"'.$l.'"':'').',m:"prognoz",s:"'.$s.'",t:"'.$t.'"'.(isset($c)?',c:"'.$c.'"':'').'})})
</script>
<div class="h4 text-center">'
. $head . '
</div>'
. $program_table . '
<div class="h6 text-center">'
. $hint . '
</div>
<div id="pl">'
. $prognozlist . '
</div>
<div id="statusline">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a></div>
';
?>
