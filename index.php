<?php
$time_start = microtime(true);
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(hash('sha256', 'iv'.$salt), 0, 16);
$key = hash('sha256', 'pass1'.$salt);

$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

# форматированный вывод времени в указанной tz; если длина time = 10 - это ts

function date_tz($format, $date, $time, $tz='Europe/Berlin') {
    $datetime = new DateTime();
    $server_tz = new DateTimeZone(date_default_timezone_get());
    $datetime->setTimeZone($server_tz);
    if (strlen($time) == 10)
        $datetime->setTimestamp($time);
    else
    {
        if (!$date)
            $date = date('Y-m-d');
        else if (strlen($date) < 6)
            $date = date('Y-') . $date;

        list($y, $m, $d) = explode('-', $date);
        $datetime->setDate($y, $m, $d);
        if (strpos($time, ':'))
        {
            list($h, $m) = explode(':', $time);
            $h = ltrim($h, '0');
            $m = ltrim($m, '0');
            if (!is_numeric($h))
                $h = 0;

            if (!is_numeric($m))
                $m = 0;

            $datetime->setTime($h, $m, 0);
        }
        else
            $datetime->setTime(12, 0, 0);

    }
    if (!$tz)
        $tz = 'Europe/Berlin';

    $timezone = new DateTimeZone($tz);
    $datetime->setTimeZone($timezone);
    return $datetime->format($format);
}

# Определение прав юзера

function acl($name, $a='') {
    global $admin;
    if ($a)
        include ('$a/settings.inc.php');
    else
    {
        global $president;
        global $vice;
        global $pressa;
        global $coach;
    }
    $hq = $admin;
    if (!in_array($president, $hq))
        $hq[] = $president;

    $a = explode(',', $vice); // может быть больше одного
        foreach ($a as $v)
            $hq[] = trim($v);

    if (in_array($name, $hq))
        return 'president';

    $hq = [];
    $a = explode(',', $pressa); // может быть больше одного
    foreach ($a as $p)
        $hq[] = trim($p);

    if (in_array($name, $hq))
        return 'pressa';

    $hq = [];
    $a = explode(',', $coach); // может быть больше одного
    foreach ($a as $p)
        $hq[] = trim($p);

    if (in_array($name, $hq))
        return 'coach';

    return 'player';
}

# sprintf для юникода

function mb_vsprintf($format, $argv, $encoding=null) {
    if (is_null($encoding))
        $encoding = mb_internal_encoding();

    // Use UTF-8 in the format so we can use the u flag in preg_split
    $format = mb_convert_encoding($format, 'UTF-8', $encoding);
    $newformat = ''; // build a new format in UTF-8
    $newargv = [];   // unhandled args in unchanged encoding
    while ($format !== '')
    {   // Split the format in two parts: $pre and $post by the first %-directive
        // We get also the matched groups
        list ($pre, $sign, $filler, $align, $size, $precision, $type, $post) =
            preg_split("!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
            $format, 2, PREG_SPLIT_DELIM_CAPTURE);
        $newformat .= mb_convert_encoding($pre, $encoding, 'UTF-8');
        if ($type == '%')
            $newformat .= '%%'; // an escaped %
        else if ($type == 's')
        {
            $arg = array_shift($argv);
            $arg = mb_convert_encoding($arg, 'UTF-8', $encoding);
            $padding_pre = $padding_post = '';
            if ($precision !== '')
            {   // truncate $arg
                $precision = intval(substr($precision, 1));
                if ($precision > 0 && mb_strlen($arg,$encoding) > $precision)
                    $arg = mb_substr($precision, 0, $precision, $encoding);

            }
            if ($size > 0)
            { // define padding
                $arglen = mb_strlen($arg, $encoding);
                if ($arglen < $size)
                {
                    if ($filler == '')
                        $filler = ' ';
                    else if ($filler[0] == "'")
                        $filler = substr($filler, 1);
                    ($align == '-') ? $padding_post = str_repeat($filler, $size - $arglen)
                                    : $padding_pre = str_repeat($filler, $size - $arglen);
                }
            }
            // escape % and pass it forward
            $newformat .= $padding_pre . str_replace('%', '%%', $arg) . $padding_post;
        }
        else if ($type != '')
        {   // another type, pass forward
            $newformat .= "%$sign$filler$align$size$precision$type";
            $newargv[] = array_shift($argv);
        }
        $format = strval($post);
    }
    // Convert new format back from UTF-8 to the original encoding
    $newformat = mb_convert_encoding($newformat, $encoding, 'UTF-8');
    return vsprintf($newformat, $newargv);
}

function mb_sprintf($format) {
    $argv = func_get_args();
    array_shift($argv);
    return mb_vsprintf($format, $argv);
}

# Определение текущего сезона

function current_season($y, $m, $cc) {
    if ($cc == 'SUI')
        return '2021-4';
//    else if ($cc == 'RUS' || $cc == 'FRA')
//        return '2018-19';
    else
        if ($m < 8) $y--;

    return $y . '-' . (substr($y, 2) + 1);
}

# Построение персональной навигации по актуальным турам

function build_personal_nav() {
    global $ccn;
    global $cmd_db;
    global $data_dir;
    global $online_dir;

    $debug_str = '';
    $have = []; // для устранения дублирования кодов туров
    $currentTime = time();
    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc') || !isset($_SESSION['Next_Event']) || $_SESSION['Next_Event'] <= $currentTime)
    {
        $statusColor = array('0' => 'noplay', '1' => 'toolate', '2' => 'alarm', '3' => 'absent', '4' => 'playing', '5' => 'present', '6' => 'result');
        $tudb = [];
        $out = '';
        $nextEvent = $currentTime + 300;
        $startTime = $currentTime - 259200; // - 3 day
//        $startTime = $currentTime - 518400; // - 6 day
        $startDay = date('d', $startTime);
        $startMonth = date('m', $startTime);
        $startYear = date('Y', $startTime);
        $sched[0] = "$startYear/$startMonth";
        $sched[1] = ($startMonth == 12) ? ($startYear + 1)."/01" : sprintf("%4d/%02d", $startYear, $startMonth + 1);
        $world = file_get_contents($online_dir . 'UNL/'.$startYear.'/codes.tsv');
/////        $final = file($online_dir . 'UNL/'.$startYear.'/final', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $tout = '';
        for ($nm=0; $nm <= 1; $nm++)
            if (is_dir($online_dir . 'schedule/'.$sched[$nm]))
            {
                $dir = scandir($online_dir . 'schedule/'.$sched[$nm]);
                foreach ($dir as $fname)
                    if ($fname[0] != '.' && ($nm || $fname >= $startDay))
                    {
                        $subdir = scandir($online_dir . 'schedule/'.$sched[$nm].'/'.$fname);
                        foreach ($subdir as $event)
                            if ($event[0] != '.' && !strpos($event, '.resend'))
                            {
                                list($timeStamp, $countryCode, $tourCode, $action) = explode('.', $event);
                                if ($timeStamp > time() + 1036800)
                                    continue; // 864000 1036800 1209600 ограничение для слишком отдалённых туров

                                $currentSeason = current_season($startYear, $startMonth, $countryCode);

//if (in_array($tourCode, ['SUI09', 'SUIC7']))
//    $currentSeason = '2021-4';
//else 
if ($countryCode == 'SUI')
    $currentSeason = '2022-1';

                                // World
//          if ($countryCode == 'UNL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false) {
$UNLseason = 2022;
                                if ($countryCode == 'UNL' && $action == 'remind')
                                { // танцуют ВСЕ!!!
                                    $tour_dir = $online_dir . 'UNL/'.$UNLseason.'/prognoz/'.$tourCode;
                                    if (is_file($tour_dir.'/published'))
                                        $status = 6; // завершён
                                    else if (is_file($tour_dir.'/closed'))
                                        $status = 4; // играется
/////            else if ($tourCode > 'UNL11' && !in_array($_SESSION['Coach_name'], $final))
/////              $status = 0; // не участвует
                                    else if (strpos("\n".file_get_contents($tour_dir.'/mail'), "\n".$_SESSION['Coach_name'].';') !== false)
                                        $status = 5; // есть прогноз
                                    else
                                        $status = ($timeStamp <= $currentTime + 86400) ? 2 : 3; // нет прогноза

                                    $tout .= '
                                <a class="'.$statusColor[$status].'" href="/?a=world&s='.$UNLseason.'&t='.substr($tourCode, 3).'&m='.($status == 6 ? 'result' : 'prognoz').'">'.$tourCode.'</a>
';
                                }
                                if ($countryCode == 'SFP')
                                    $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/'.substr($tourCode, 0, 3).'/it'.substr($tourCode, -2);
                                else if ($tourCode[4] == 'L')
                                    $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/'.substr($tourCode, 0, 5).'/itc'.substr($tourCode, -2);
                                else
                                    $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/it'.strtolower(substr($tourCode, 3));

                                $uefaflag = 0;
                                $tour_dir = $online_dir.$countryCode.'/'.$currentSeason.'/prognoz/'.$tourCode;
                                if ($countryCode != 'UNL') //&& $countryCode != 'IST')
                                    foreach ($cmd_db[$countryCode] as $code => $team)
                                        if ($team['usr'] == $_SESSION['Coach_name'])
                                        {
                                            $team_str = $code.'@'.$countryCode;
                                            $cut = strlen($code); ///// для совместимости. ПЕРЕДЕЛАТЬ!
                                            if (is_file($itFName))
                                            {
                                                if ($uefaflag == 0)
                                                    $tudb[$team_str][$tourCode] = 6; // 6 - опубликованы итоги

                                                if ($tourCode[4] == 'L')
                                                    $uefaflag = 1;

                                            }
                                            // ФП совпала, итогов еще нет, надо проверить, есть ли команда в программке тура
                                            else if (!isset($tudb[$team_str][$tourCode]))
                                            {   // первое упоминание тура
                                                $content = file_get_contents($online_dir.$countryCode.'/'.$currentSeason.'/programs/'.$tourCode);
                                                $content = substr($content, strpos($content, 'Контрольный с'));
                                                if ($countryCode != 'SUI' && ($tourCode[4] == 'L' || in_array($tourCode[3], ['C', 'G', 'P', 'S'])) && !strpos($content, $cmd_db[$countryCode][$code]['cmd']))
                                                {
                                                    if ($tourCode[4] != 'L')
                                                        $tudb[$team_str][$tourCode] = 0; // 0 - неучастие

                                                }
                                                else if (is_file($tour_dir.'/published'))
                                                {   // идущий тур
                                                    $team_str1 = ($countryCode == 'SFP') ? $cmd_db[$countryCode][$code]['cmd'] : $team_str;
                                                    $content = "\n".file_get_contents($tour_dir.'/mail');
                                                    if (strpos($content, "\n".substr($team_str1, 0, $cut).';') === false)
                                                    {
                                                        if ($content = is_file($tour_dir.'/adds') ? ".\n".file_get_contents($tour_dir.'/adds') : '')
                                                        {
                                                            $cut = strpos($content, "\n".$cmd_db[$countryCode][$code]['cmd'].' ');
                                                            $ss = substr($content, $cut, strpos($content, "\n", $cut + 1) - $cut);
                                                            $ast = $cut ? strpos($ss, '*') : -1;
                                                        }
                                                        else
                                                            $cut = false;

                                                        if (!$cut || $ast)
                                                            $tudb[$team_str][$tourCode] = is_file($tour_dir.'/closed') ? 1 : 2; // прогноза нет
                                                        else
                                                            $tudb[$team_str][$tourCode] = 4; // прогноз найден в дополнениях

                                                    }
                                                    else
                                                        $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в почте

                                                }
                                                else
                                                {   // ещё не начавшийся тур
                                                    $team_str1 = $countryCode == 'SFP' ? $cmd_db[$countryCode][$code]['cmd'] : $team_str;
                                                    $content = is_file($tour_dir.'/mail') ? "\n".file_get_contents($tour_dir.'/mail') : '';
                                                    if ((strpos($content, "\n".substr($team_str, 0, $cut).';') === false) && (strpos($content, "\n".$team_str1.';') === false))
                                                    {
                                                        $content = is_file($tour_dir.'/adds') ? ".\n".file_get_contents($tour_dir.'/adds') : '';
                                                        if ($cut = strpos($content, "\n".$cmd_db[$countryCode][$code]['cmd'].' '))
                                                            $tudb[$team_str][$tourCode] = 5; // прогноз найден в дополнениях
                                                        else
                                                            $tudb[$team_str][$tourCode] = $action == 'remind' && $timeStamp <= $currentTime + 86400 ? 2 : 3; // прогноза нет

                                                    }
                                                    else
                                                        $tudb[$team_str][$tourCode] = 5; // прогноз найден в почте

                                                }
                                            }
                                            else if ($tudb[$team_str][$tourCode] == 3)
                                                if (($action == 'remind' && $timeStamp <= $currentTime + 86400) || is_file($tour_dir.'/published'))
                                                    $tudb[$team_str][$tourCode] = 2; // 2 - прогноза нет и уже горит

                                        }

                                if ($currentTime < $timeStamp)
                                    $nextEvent = min($timeStamp, $nextEvent); // ближайшее cледующее событие

                            //}
                    }
            }
    }
    if ($tout)
        $out .= '
                            <li class="nav-item">' . $tout .'
                            </li>';
/*
if ($_SESSION['Coach_name'] == 'Александр Сесса')
{
echo var_export($tudb, false);
exit();
}
*/
    $prev_fp = '';
    foreach (['SFP', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'UKR', 'SUI', 'UEFA'] as $countryCode)
    {
        $currentSeason = current_season($startYear, $startMonth, $countryCode);

//if (in_array($tourCode, ['SUI09', 'SUIC7']))
//    $currentSeason = '2021-4';
//else 
if ($countryCode == 'SUI')
    $currentSeason = '2022-1';

        $tout = '';
        foreach ($cmd_db[$countryCode] as $c => $team)
            if ($team['usr'] == $_SESSION['Coach_name'])
            {
                $team_str = $c.'@'.$countryCode;
                $ll = ($countryCode == 'SFP') ? 'Сборная' : $team['cmd'];
                $prev_fp = $countryCode;
                if (isset($tudb[$team_str]))
                    foreach ($tudb[$team_str] as $tcode => $status)
                    {
                        if (in_array($tcode, $have))
                            continue;

                        $have[] = $tcode;
                        if (strlen($tcode) > 3 && $tcode[4] == 'L')
                        {
                            $cclen = 5;
                            $ll = '&l='.substr($tcode, 0, $cclen);
                        }
                        else if ($countryCode == 'SFP')
                        {
                            $cclen = 3;
                            $ll = substr($tcode, 0, $cclen);
                            $ll = (in_array($ll, ['PRO', 'FFP', 'TOR', 'SPR'])) ? '&l='.$ll : '&';
                        }
                        else
                        {
                            $cclen = 3;
                            $ll = '';
                        }
                        $linktext = ($status != 6) ? 'prognoz' : 'text&ref=it';

//if (in_array($tourCode, ['SUI09', 'SUIC7']))
//    $currentSeason = '2021-4';
//else 
if ($countryCode == 'SUI')
    $currentSeason = '2022-1';

                        if ($ll != '&' && ($status != 0 || $countryCode != 'SFP'))
                            $tout .= '
                                <a class="'.$statusColor[$status].'" href="/?a='.strtolower($ccn[$countryCode]).'&c='.$c.$ll.'&s='.$currentSeason
                                .'&m='.$linktext.'&t='.strtolower(substr($tcode, $cclen)).'">'.$tcode.'</a>';

                    }
            }
            if ($tout)
                $out .= '
                            <li class="nav-item">' . $tout .'
                            </li>';

    }

    file_put_contents($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc', $out);
    $_SESSION['Next_Event'] = $nextEvent;
    }
    return $debug_str;
}

# Отправка EMail (специфика хостинга на AWS - на других серверах не использовать)

function send_email($from, $name, $email, $subj, $body) {
    $params = ['token' => 'FPrognoz.Org', 'from' => $from, 'name' => $name, 'email' => $email, 'subj' => $subj, 'body' => $body];
    $context = stream_context_create(['http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
        'content' => http_build_query($params),
    ]]);
    return file_get_contents('http://forum.fprognoz.org/mail-proxy.php', false, $context);
}

# Блокировка файла на время его изменения

function lock($lock, $timer) {
    while ($timer-- && is_file($lock))
        time_nanosleep(0, 1000);

    if ($timer)
        touch($lock);

    return ($timer);
}

# Отправка прогноза

function send_predict($country_code, $season, $team_code, $tour, $prognoz, $enemy_str, $ip) {
    global $ccn;
    global $data_dir;

    $time = time();
    $email = '';
    $cca_home = $data_dir . 'online/' . $country_code . '/';
    $acodes = file($cca_home . $season .'/codes.tsv');
    foreach ($acodes as $scode) if ($scode[0] != '#')
    {
        $ateams = explode('	', $scode);
        if (trim($ateams[0]) == $team_code || trim($ateams[1]) == $team_code)
        {
            $name = trim($ateams[2]);
            $email = trim($ateams[3]);
        }
    }
    $replyto = $email ? "\nReply-To: $email" : '';
    $mlist = $email;
    if (is_file($cca_home . 'emails'))
    {
        $atemp = file($cca_home . 'emails');
        foreach ($atemp as $line)
        {
            list($pmail, $pcode) = explode(':', trim($line));
            if ($pcode != $enemy_str)
                $mlist .= ($mlist ? ', ' : '') . $pmail;

        }
    }
    $subj = strtoupper($ccn[$country_code]);
    $body = "FP_Prognoz\n$team_code\n$tour\n$prognoz\n";

    // write direct
    if (strpos($prognoz, '  '))
        list($prognoz, $pena) = explode('  ', $prognoz);

    $pena = isset($pena) ? strtoupper(trim($pena)) : '';
    $tour_dir = $cca_home . $season . '/prognoz/' . $tour;
    if (!is_dir($tour_dir))
        mkdir($tour_dir, 0755, true);

    if (is_file($tour_dir . '/mail'))
    {
        $lock = $tour_dir.'/lock';
        if (lock($lock, 5000))
        {
            $content = file_get_contents($tour_dir . '/mail');
            file_put_contents($tour_dir . '/mail', $content . "$team_code;$prognoz;$time;$pena\n");
            unlink($lock);
            if (is_file($data_dir . 'personal/' . $name . '/navbar.inc'))
                unlink($data_dir . 'personal/' . $name . '/navbar.inc');

        }
        else
            echo 'В течение минуты прогноз должен появиться в списке полученных.<br>
';
    }
    else
        file_put_contents($tour_dir . '/mail', "$team_code;$prognoz;$time;$pena\n");

    @mail('fp@fprognoz.org', $subj, $body,
'From: =?UTF-8?B?'.base64_encode($team_code).'?= <fp@fprognoz.org>'.$replyto.'
Date: '. date('r', $time) .'
MIME-Version: 1.0
Content-Type: text/plain;
        charset="utf8"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 4.00.180121-'.$ip);

    echo send_email($team_code . ' <fp@fprognoz.org>', '', $mlist, $subj, $body);
}

# Построение файла аутентификации юзеров

function build_access() {
    global $ccn;
    global $data_dir;
    global $online_dir;

    $access = '';
    foreach ($ccn as $ccc => $cname)
        if ($ccc != 'SBN' && $ccc != 'FCL' && $ccc != 'WL' && $ccc != 'IST' && $ccc != 'FIFA')
        {
            $dir = scandir($online_dir.$ccc, 1);
            foreach ($dir as $s)
                if ($s[0] == '2')
                    break;

            $codes = file($online_dir . $ccc . '/' . $s . '/codes.tsv');
            foreach ($codes as $line)
                if (trim($line))
                {
                    list($code, $cmd, $name, $email) = explode("\t", $line);
                    $email = trim($email);
// не надо минус резать!     $code = trim($code, '- ');
                    $code = trim($code);
                    if ($ccc == 'UNL')
                        $name = $code; // здесь имена могут дублироваться, поэтому только код!

                    if (($code[0] != '#' || $code == '#MS24#') && $name && $email && (!strpos($code, '@') || $cc = 'SFP'))
                    {
                        if (is_file($online_dir . $ccc . '/passwd/' . $code))
                        {
                            list($hash, $role) = explode(':', file_get_contents($online_dir . $ccc . '/passwd/' . $code));
                            $role = trim($role);
                        }
                        else
                            $hash = $role = '';

                        $access .= "$code;$ccc;$cmd;$name;$email;$hash;$role;\n";
                        if (!is_dir($data_dir . 'personal/' . $name))
                            mkdir($data_dir . 'personal/' . $name, 0755);

                    }
                }
        }

    file_put_contents($data_dir . 'auth/.access', $access);
}

# Обновление результатов из livescore.bz

function bz_matches($json) {
    global $online_dir;

    $leagues = [
'England: Premier League',
'England: FA Cup',
'England: League Cup',
'England: Community Shield',
'England: Championship',
'England: Championship Playoff',

'Germany: Bundesliga',
'Germany: DFB Pokal',
'Germany: CUP',
'Germany: 1. Bundesliga qualification',
'Germany: German Super Cup',

'Italy: Serie A',
'Italy: CUP',
'Italy: Coppa Italia',
'Italy: Serie A/B Playoff',
'Italy: Super Cup',

'France: Ligue I',
'France: Ligue 1',
'France: Cup',
'France: SuperCup',

'Spain: La Liga',
'Spain: Segunda Play-Offs',
'Spain: Copa del Rey',
'Spain: Supercup',

'Scotland: Premier League',
'Scotland: Tennents Scottish Cup',

'Holland: Eredivisie',
'Holland: Amstel CUP',
'Holland: Amstel Cup',
'Holland: Super Cup',

'Portugal: Primeira Liga',
'Portugal: Cup',
'Portugal: League CUP',
'Portugal: League Cup',
'Portugal: Super Cup',

'Russia: League 1',
'Russia: League 2',
'Russia: CUP',
'Russia: Cup',
'Russia: Super Cup',
'Russia: Premier League  Championship Playoffs',
'Russia: Premier League  Relegation Playoffs',

'Belarus: Premier League',
'Belarus: Cup',
'Belarus: SuperCup',
'Belarus: Premier League Championship Group',
'Belarus: Premier League Relegation Group',

'Ukraine: League 1',
'Ukraine: CUP',
'Ukraine: Cup',
'Ukraine: SuperCup',

'Belgium: Jupiler',
'Greece: Super League',
'Switzerland: Premier League',
'Turkey: Super League',

'UEFA: Euro',
'UEFA: Champions League',
'UEFA: Europa League',
'UEFA: Europa Conference League',
'UEFA: Nations League',
'World: Friendly',
'FIFA: World Cup',
'FIFA: World Cup Qualification - Africa',
'FIFA: World Cup Qualification - Asia',
'FIFA: World Cup Qualification - Central America',
'FIFA: World Cup Qualification - Europe',
'FIFA: World Cup Qualification - Oceania',
'FIFA: World Cup Qualification - South America',
'International: Euro qualification',
'International: World Cup Group Stage',
'International: CONCACAF Gold Cup',
'International: Copa America Group Stage',
    ];
//'CAF: African Nations Championship',

    $res_path = $online_dir . 'results/';
    $lock = $online_dir . 'log/results.lock';
    include ('online/realteam.inc.php');
    $matches = json_decode($json, true); //  $matches = json_decode(stripslashes($json), true);
    $update = false;
    $year = date('Y');
    $m = date('m');
    $d = date('d');
    $week = date('W', time() - 86400);
    $fname = ($week == '52' && $m == '01') ? $res_path.($year - 1).'.'.$week : $res_path.$year.'.'.$week;
    if (lock($lock, 10000))
    {   // lock
        $archive = is_file($fname) ? file($fname) : [];
        $base = [];
        $seq = trim($archive[0]);
        unset($archive[0]); // remove seq
        foreach ($archive as $line)
        {
            list($h,$a,$d,$s,$f,$r,$g,$i,$z) = explode(',', trim($line));
            $base[$h.' - '.$a.' '.substr($d, 0, 5)] = array($h,$a,$d,$s,$f,$r,$g,$i,$z);
        }
        if (sizeof($matches))
            foreach ($matches as $match)
            {
                extract($match);
                $league = $uadi.': '.$ladi;
                if (in_array($league, $leagues))
                {
                    if (isset($realteam[$t1]))
                        $t1 = $realteam[$t1];

                    if (isset($realteam[$t2]))
                        $t2 = $realteam[$t2];

                    list($dd, $mm, $yy) = explode('/', $tar3);
                    $time = strtotime($yy . '-' . $mm . '-' . $dd . 'T' . $tar2 . '+0:00');
                    if (isset($base[$t1.' - '.$t2.' '.date('m-d', $time)]))
                    {
                        if ($base[$t1.' - '.$t2.' '.date('m-d', $time)][8] != $id)
                        {
                            $base[$t1.' - '.$t2.' '.date('m-d', $time)][8] = $id;
                            $update = true;
                        }
                    }
                }
            }

        if ($update)
        {
            $out = $seq . "\n";
            foreach ($base as $id => $data)
                $out .= implode(',', $data) . "\n";

            file_put_contents($fname, $out);
        }
        unlink($lock);
    }
}

# Загрузка архива результатов матчей

function str_putcsv($input) {
    $fp = fopen('php://temp', 'r+b');
     fputcsv($fp, $input, ',', '|');
     rewind($fp);
     $data = str_replace('|', '', rtrim(stream_get_contents($fp), "\n"));
     fclose($fp);
     return $data;
}

function load_archive($fname, $updates) {
    global $online_dir;

    $archive = file($fname);
    $save = false;
    if ($updates)
        foreach ($updates as $update)
            foreach ($archive as $n => $line)
                if (strpos($line, trim($update['idx'], '"')))
                {
                    $arr = explode(',', trim($line));
                    if ($arr[3] != 'FT')
                    {
                        $arr[3] = 'FT';
                        $arr[4] = $arr[5] = $update['evs'].':'.$update['deps'];
                        $archive[$n] = str_putcsv($arr);
                        $save = true;
                    }
                }

    if ($save)
    {
        $out = '';
        foreach ($archive as $line)
            $out .= trim($line) . "\n";

        if (lock($online_dir . 'log/results.lock', 10000))
        {
            file_put_contents($fname, $out);
            unlink($online_dir . 'log/results.lock');
        }
    }
    else
        $archive[0] = 0; // seq не имеет значения для неактуального архива; используем его для обнуления $updates

    return $archive;
}

function get_results_by_date($month, $day, $update=NULL, $year=NULL) {
    global $online_dir;

    $updates = json_decode(stripslashes($update), true);
    $date = sprintf('%02d-%02d', trim($month), trim($day));
    if (!$year)
        $year = date('Y');

//  if ($month > 7) $year--;
    $base = [];
    $week = date('W', strtotime($year.'-'.$date));
///// коррекция в конце года. надо бы написать код автоматической коррекции
//  if (in_array($date, ['12-31'])) $week = '01';
    if (in_array($date, ['12-26']))
        $year--;

    $fname = $year.'.'.$week;
    $seq = 0;
    if (is_file($online_dir . 'results/'.$fname))
    {
        $archive = load_archive($online_dir . 'results/'.$fname, $updates);
        $seq = trim($archive[0]);
        if ($seq)
            $updates = []; // уже проапдейтили, обнуляем

        unset($archive[0]);
    }
    else
        $archive = array();

///// коррекция в конце года. надо бы написать код автоматической коррекции
    if (++$week == '53') //54
    {
        $week = '01';
        $year++;
    }
    else if (strlen($week) == 1)
        $week = '0'.$week;

    $fname = $year.'.'.$week;
    if (is_file($online_dir . 'results/'.$fname))
    {
//    $archive2 = file($online_dir . 'results/'.$fname);
        $archive2 = load_archive($online_dir . 'results/'.$fname, $updates);
        $seq = max($seq, trim($archive2[0]));
        unset($archive2[0]);
        $archive = array_merge($archive, $archive2);
    }
    $base['seq'] = $seq;
    foreach ($archive as $line)
    {
        $data = explode(',', trim($line));
        $match = $data[0].' - '.$data[1];
        if (!isset($data[8]))
            $data[8] = '';

        $mdate = substr($data[2], 0, 5);
        $mm = substr($mdate, 0, 2);
///// коррекция в конце года. надо бы написать код автоматической коррекции
        if ($month == 12 && $mm == '01')
            $mdate = '13-'.substr($mdate, 3);

        if ($mdate > $month.'-'.$day)
        {
            if (!isset($base[$match]))
                $base[$match] = [$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8],$data[6]];

            $base[$match.'/'.$data[6]] = [$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8],$data[6]];
        }
    }
    return $base;
}

# Выбор расписания и генераторов тура из программки

function parse_cal_and_gen($program) {
    $acal = $agen = [];
    $calfp = explode("\n", $program);
    unset($calfp[1], $calfp[0]);
    foreach ($calfp as $line)
        if ((strpos($line, ' - ') || strpos($line, ' *')) && !mb_strpos($line, 'ГОСТИ') && !mb_strpos($line, 'Гости'))
        {
            if (strpos($line, '*'))
            {   // parse line with generator
                if ($fr = strpos($line, ' - '))
                {   // match and generator
                    $cut = strpos($line, '  ', $fr);
                    if ($cut)
                    {
                        $acal[0][] = trim(substr($line, 0, $cut));
                        $agen[] = trim(substr($line, $cut));
                    }
                }
                else
                {   // generators only
                    $agen[] = trim($line);
            }
        }
        else
        {   // line w/o generator
            $n = 0;
            $line .= '  ';
            while ($fr = strpos($line, ' - '))
            {
                $cut = strpos($line, '  ', $fr);
                if ($cut)
                {
                    $acal[$n++][] = trim(substr($line, 0, $cut));
                    $line = substr($line, $cut);
                }
                else
                    break;

            }
        }
    }
    $cal = $gen = '';
    foreach ($acal as $a)
        foreach ($a as $l)
            $cal .= "$l\n";

    foreach ($agen as $l)
        $gen .= "$l\n";

    return [$cal, $gen];
}

# Загрузка конфигурации сезона

function season_config($file) {
    $config = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $cal = $config[2]; // если не будет переобъявлен в этапах
    $season_config = json_decode($config[4], true);
    $season_config[0]['lang'] = isset($season_config[0]['турнир']) || !isset($season_config[0]['type']) ? 'ru' : 'en';
    foreach($season_config as $n => $tournament)
        if ($season_config[0]['lang'] == 'en')
        {
            if (!isset($tournament['type']))
                $season_config[$n]['type'] = ($cal == 'calc' || isset($tournament['format'][0]['cal']) && $tournament['format'][0]['cal'] == 'calc') ? 'cup' : 'chm'; // по умолчанию - чемпионат

            if (!isset($tournament['format'][0]['cal']))
                $season_config[$n]['format'][0]['cal'] = ($season_config[$n]['type'] == 'cup') ? 'calc' : $cal;

            if (isset($tournament['format'][1]) && !isset($tournament['format'][1]['cal']))
                $season_config[$n]['format'][1]['cal'] = 'calp'; // предполагаем, что это 2-й этап чемпионата: плей-офф

        }
        else
        {
            // при неоходимости переведём на английский
        }

    return $season_config;
}

$ccn = [
'SFP' => 'SFP-team',
'ENG' => 'England',
'BLR' => 'Belarus',
'GER' => 'Germany',
'NLD' => 'Netherlands',
'ESP' => 'Spain',
'ITA' => 'Italy',
'PRT' => 'Portugal',
'RUS' => 'Russia',
'UKR' => 'Ukraine',
'FRA' => 'France',
'SCO' => 'Scotland',
'UEFA'=> 'UEFA',
'FIN' => 'Finland',
'SUI' => 'Switzerland',
'FIFA'=> 'FIFA',
'FCL' => 'Friendly',
'UNL' => 'World',
'WL'  => 'World',
'IST' => 'SFP-20',
];

$fa = [
'fifa'    => 'ФП ФИФА',
'uefa'    => 'ФП УЕФА',
'sfp-team'=> 'Сборная SFP',
'world'   => 'Лига Наций',
'england' => 'Англия',
'belarus' => 'Беларусь',
'germany' => 'Германия',
'netherlands' => 'Голландия',
'spain'   => 'Испания',
'italy'   => 'Италия',
'portugal'=> 'Португалия',
'russia'  => 'Россия',
'ukraine' => 'Украина',
'france'  => 'Франция',
'scotland'=> 'Шотландия',
'switzerland' => 'Швейцария',
'finland' => 'Финляндия',
'austria' => 'Австрия',
'belgium' => 'Бельгия',
'greece'  => 'Греция',
'turkey'  => 'Турция',
];

$classic_fa = ['AUS', 'BEL', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'GRE', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'SUI', 'TUR', 'UKR'];

$role = 'badlogin';
$notification = '';
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$auth = isset($_POST['pass_str']) && isset($_POST['name_str']);
$parameters = (isset($_POST['matches']) || isset($_POST['updates']) || isset($_POST['mtscores'])) ? $_POST : $_GET;
foreach ($parameters as $k => $v)
    $$k = $v;

if ($auth && !$_POST['pass_str'] && strpos($_POST['name_str'], '@') && strpos($_POST['name_str'], '.'))
{
    $m = 'authentifying';
    $email_ok = false;
}
if (isset($ls))
    setcookie('fprognozls', $ls, ['SameSite' => 'Strict']);

$fprognozls = isset($_COOKIE['fprognozls']) ? $_COOKIE['fprognozls'] : 'enetscores';
$editable_class = '';

# определяем, что показывать, если нечего показывать

$sidebar_show = false;
if (!isset($a))
    $a = 'fifa';

else if (!is_dir($a))
{
    http_response_code(404);
    $a = 'fifa';
    $m = '404';
}
else if (count($_GET) == 1 || count($_GET) == 2 && isset($_GET['s']))
    $sidebar_show = true; // не сворачивать левое меню при выборе ассоциации и сезона

include ($a.'/settings.inc.php');

# аутентификация

session_start();
if (isset($token))
{ // вход по ссылке
    $data = json_decode(trim(openssl_decrypt( base64_decode($token), 'AES-256-CBC', $key, 0, $iv )), true);
    if ($data['cmd'] == 'auth_token' && $data['ts'] > time())
    {
        $_SESSION['Coach_name'] = $data['name'];
        $_SESSION['Coach_mail'] = $data['mail'];
    }
    else
        $notification = '
ссылка для входа<br>
не действительна';

}
if (isset($_GET['logout']))
{
    $role = 'badlogin';
    session_unset();
    session_destroy();
}
$coach_name = isset($_SESSION['Coach_name']) ? $_SESSION['Coach_name'] : '';

$passed = false;
$sendpwd = '';
$team_codes = [];

// изменить: $cmd_db нужна только при авторизованном входе и в большинстве скриптов только с данными одного игрока
$access = file($data_dir . 'auth/.access');
$cmd_db = []; // база данных команд, сгруппированная по ассоциациям
if ($auth || isset($_POST['submitnewpass']))
{
    $hash = md5($_POST['pass_str']);
    $name_str = mb_strtoupper(isset($_POST['name_str']) ? $_POST['name_str'] : $coach_name);
}
foreach ($access as $access_str)
{
    list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
    $cmd_db[$as_code][$code] = ['ccn' => $as_code, 'cmd' => $team, 'usr' => $name, 'eml' => $mail, 'rol' => $rol];
    if ($auth || isset($_POST['submitnewpass']))
    {   // аутентификация или контрольная проверка пароля
        if (($pwd == '' || $hash == $pwd || $hash == $SuperPWD || (isset($data) && $data['cmd'] == 'auth_token' && $data['ts'] > time()))
        && ($name_str == mb_strtoupper($code) || $name_str == mb_strtoupper($name)
        || $name_str == strtoupper($mail) || (isset($_SESSION['Coach_mail']) && strtoupper($_SESSION['Coach_mail']) == strtoupper($mail))))
        {
            $passed = true;
            if (isset($_POST['submitnewpass']))
            {
                $sendpwd = $mail;     // для отправки сообщения о смене пароля
                $team_codes[$as_code] = $code; // для последующей смены пароля
            }
            if (strlen($coach_name) < strlen($name))
                $coach_name = $name; // выбираем самое длинное имя из указанных

            if ($mail)
                $_SESSION['Coach_mail'] = $mail;

        }
        else if (isset($m) && $m == 'authentifying')
        {
            if ($name_str == strtoupper($mail))
            {
                $email_ok = true;
                break;
            }
        }
        else if ($auth && !$pwd && $_POST['pass_str'] == $code && $_POST['name_str'] == $name)
        {
            $sendpwd = $mail; // выполнилось условие отправки первого пароля
            $team_codes[$as_code] = $code;
        }
    }
}
if ($auth)
{
    if ($passed)
    {
         $_SESSION['Coach_name'] = $coach_name;
         build_personal_nav();
    }
    else if (!$notification)
        $notification = 'Ошибка входа';

}
else
{   // restored session
    if ($coach_name)
    {
        build_personal_nav();
        if (!isset($cca))
            $cca = '';

    }
    else
        session_unset();

}

if ($have_redis)
    $redis = new Redis();
else
{
    include('comments/redis-emu.php');
    $redis = new Redis_emu();
}
$is_redis = $redis->connect($redis_host, $redis_port);

if (isset($_SESSION['Coach_name']))
{
    $apikey = rtrim(base64_encode(openssl_encrypt(json_encode(['cmd' => 'send_by_api', 'email' => $_SESSION['Coach_mail']]), 'AES-256-CBC', $key, 0, $iv )), '=');
    if (strtotime('7/15') < time() && time() <= strtotime('8/28') && !is_file($data_dir.'personal/'.$coach_name.'/'.date('Y')))
    {
        $a = 'fifa';
        $m = 'confirm'; // кампания сбора подтверждений с 15 июля по 28 августа
    }
    $role = acl($_SESSION['Coach_name']);
    if (is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc'))
    {
        if (isset($_POST['toggle_gb']))
        {
            unlink($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc');
            $gb_status = 'off';
        }
        else
            $gb_status = 'on';

    }
    else
    {
        if (isset($_POST['toggle_gb']))
        {
            if (!is_dir($data_dir . 'personal/'.$_SESSION['Coach_name']))
                mkdir($data_dir . 'personal/'.$_SESSION['Coach_name'], 0755);

            touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc');
            $gb_status = 'on';
        }
        else
            $gb_status = 'off';

    }
}
else
{
    $apikey = '';
    $is_redis = false;
    $gb_status = 'off';
}

# формирование центрального инфоблока

if (!isset($m))
{   // если не запрошен контент, надо показать хоть что-то:
    if (isset($s))
        $m = 'news';                        // новости сезона
    else
    {
        $m = 'main';                        // информация об ассоциации
        if ($a == 'fifa')
            if (!isset($_COOKIE['fprognozmain']))
                setcookie('fprognozmain', '1', ['SameSite' => 'Strict']); // один раз покажем информацию о сайте
            else
            {
                $s = $cur_year;                 // потом всегда показывать свежие новости
                $m = 'news';
            }

    }
}
else if (!in_array($m, ['main', 'news', 'pres', 'text', 'cal', 'calc', 'calp', 'gen', 'genc', 'set']))
{ // проверка на псевдо-скрипты - им не требуется наличие файла
    if (!is_file($a . '/' . $m . '.inc.php'))
    {
        http_response_code(404);
        $a = 'fifa';
        $m = '404';
    }
    if ($m == 'prognoz' && isset($s) && $s != $cur_year)
        $m = 'news'; // форма prognoz только для текущего сезона!

}
$season_dir = $online_dir . $cca . '/' . (isset($s) ? $s : $cur_year) . '/';
if ($m == 'set' && $role == 'president')
    $config = season_config($online_dir . $cca . '/' . $cur_year . '/fp.cfg');
else if ($m == 'main' || $m == 'news')
{
    $fn = $online_dir . $cca . '/' . (isset($s) ? $s . '/' : '') . 'news';
    $content = file_get_contents(is_file($fn) ? $fn : $online_dir . $cca . '/news');
}
else if (in_array($m, ['cal', 'calc', 'calp', 'gen', 'genc']))
{
    $fn = $season_dir . ($l ?? false ? $l . '/' : '') . $m;
    $content = is_file($fn) ? file_get_contents($fn) : 'файл не найден';
    $editable_class = ' class="monospace w-100"';
}
else if ($m == 'text')
{
    $league = isset($l) ? $l.'/' : '';
    if (isset($t))
        $tt = $cca == 'UEFA' ? 'c'.$t : $t;

    if (isset($ref))
    {
        switch ($ref)
        {
            case 'news': $f = 'news'; break;
            case 'itog': $t = lcfirst($t);
            case 'it'  : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'it.tpl'; break;
            case 'itc' : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'itc.tpl'; break;
            case 'prog': $t = lcfirst($t);
            case 'p'   : $f = isset($t) ? ($a == 'sfp-team' ? 'programs/'.$l : 'publish/p').$t : 'p.tpl'; break;
            case 'pc'  : $f = isset($t) ? 'publish/p'.$t : 'pc.tpl'; break;
            case 'rev' : $t = lcfirst($t);
            case 'r'   : $f = isset($t) ? 'publish/'.$league.'r'.$tt : 'header'; break;
        }
        $content = is_file($season_dir . $f) ? file_get_contents($season_dir . $f) : 'файл не найден';
        $editable_class = ' class="monospace w-100"';
    }
}
if (isset($content) && trim($content) && !strpos($content, '</p>') && !strpos($content, '<br'))
    $editable_class = ' class="monospace w-100"'; // text, но если всё удалить, должно разрешить ввести html


if (!isset($s))
    $s = $cur_year;

# построение левого меню

if ($role == 'president')
{
    $sidecmd = ($cca == 'SUI' || is_file($data_dir.'online/'.$cca.'/'.$s.'/cal'))
                ? '
              <button type="button" id="addTour" class="btn btn-light btn_hq" title="Новый тур" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=mkpgm\'"><div class="fas fa-plus"></div></button>'
                : '
              <button type="button" id="addCal"  class="btn btn-light btn_hq" title="Создать календарь" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=newcal\'"><div class="fas fa-plus"></div></button>';

    $sidecmd .= '
              <button type="button" id="setSeason" class="btn btn-light btn_hq" title="Настройки" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=set\'"><div class="fas fa-cog"></div></button>
              <button type="button" id="templates" class="btn btn-light btn_hq" title="Макеты" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=text&ref=p\'"><div class="fas fa-list-alt"></div></button>';
}
else
    $sidecmd = '';

$sidebar = '
          <select class="form-select px-4 py-3 w-100" name="season" aria-label="Season select" onchange="window.location.href=\'?a='.$a.'&amp;s=\'+this.value">';
$season = '';
$ccd = ($a == 'sfp-20') ? 'WL' : $cca;
if (is_dir($online_dir.$ccd))
    $dir = scandir($online_dir.$ccd, 1);

foreach ($dir as $subdir)
    if (($subdir[0] == '2') || ($subdir[0] == '1'))
    {
        $seasons[] = $subdir;
        if (!isset($s))
            $s = $subdir;

    }

function sidebar_item($page, $name) {
    global $a;
    global $m;
    global $s;
    return '
            <li class="nav-item text-start">
              <a class="nav-link'
              . ($page == $m ? ' active" aria-current="page' : '')
              . '" href="?a=' . $a . '&amp;s=' . $s . '&amp;m=' . $page . '">' . $name . '</a>
            </li>';
}

foreach ($seasons as $ss)
    $sidebar .= '
            <option'.($ss == $s ? ' selected' : '').'>'.$ss.'</option>';

$sidebar .= '
          </select>
          <ul class="navbar-nav ms-4">'
          . (in_array($a, ['fifa', 'world']) ? sidebar_item('news', 'Новости') : '')
          . sidebar_item('pres', 'Пресс-релизы')
          . ($a == 'fifa' ? sidebar_item('konkurs', 'Конкурс') : '')
          . ($a == 'fifa' ? sidebar_item('vacancy', 'Вакансии') : '')
          . ($a == 'fifa' ? sidebar_item('quota', 'Квоты игроков') : '')
          . ($a == 'fifa' ? sidebar_item('history', 'История SFP') : '')
          . ($a == 'fifa' ? sidebar_item('help', 'Инструкция') : '')
          . ($a == 'fifa' ? sidebar_item('help-hq', 'Организатору') : '')
          . ($a == 'fifa' ? sidebar_item('real', 'Веб-сайты') : '')
          . ($a == 'fifa' ? sidebar_item('live', 'Результаты') : '')
          . (is_file($season_dir.'bombers') ? sidebar_item('club', 'Команды') : '')
          . (!in_array($a, ['fifa', 'world']) && is_file($season_dir.'codes.tsv') ? sidebar_item('player', 'Игроки') : '')
          . ($a == 'switzerland' ? sidebar_item('register', 'Выбор команды') : '')
          . sidebar_item(in_array($a, ['fifa', 'world']) ? 'reglament' : 'news', 'Регламент')
          . '
          </ul>';

function tournament_header($code, $tour_type, $tname) {
    return '
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading' . $code . '">
                <button class="accordion-button'
                . ($tour_type == $code ? '' : ' collapsed')
                . '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $code 
                . '" aria-expanded="' . ($tour_type == $code ? 'true' : 'false')
                . '" aria-controls="collapse' . $code . '">
                  ' . $tname . '
                </button>
              </h2>
              <div id="collapse' . $code . '" class="accordion-collapse collapse'
                . ($tour_type == $code ? ' show' : '')
                . '" aria-labelledby="heading' . $code
                . '" data-bs-parent="#sidebarAccordion">
                <ul class="accordion-body list-unstyled">';
}

function tour_line($season_dir, $tour, $tn, $league='') {
    global $a;
    global $s;

    if (strlen($league) == 1)
    {
        $l = $league;
        $league = '';
    }
    $tn = ($tn <= 9 ? '&nbsp;' : '') . $tn;
    $tt = strtr($tour, ['NEW' => '']);
    if (strlen($league) == 5)
        $tour = 'c' . $tour;

    //$tp = !$league && in_array($tour[0], ['c', 'g', 'p', 's']) ? $tour[0] : '';
    $prefix = '<div class="col-4">
                        <a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.($league ? '&amp;l='.$league : '').'&amp;t='.$tt;//$tp.$tt
    return '
                  <li class="row">
                    '.$prefix.'&amp;m=text&amp;ref=p">тур<span>&nbsp;'.$tn.':</span></a>
                    </div>'
                    . $prefix.'&amp;m='
                    . (
                        is_file($season_dir.'publish/'.$league.'/it'.$tour)
                        ? (strpos($season_dir, 'UNL') ? 'result' : 'text&amp;ref=it').'">итоги,</a>
                    </div>
                    '.$prefix.'&amp;m='.(strpos($season_dir, 'UNL') ? 'stat&l='.$l.'">стат.' : 'text&amp;ref=r">обзор').'</a>'
                        : 'prognoz">прогнозы</a>
                    </div>
                    <div class="col-4">'
                    ) . '
                    </div>
                  </li>';
}

function navbar_item($page, $name) {
    global $a;
    global $m;
    return '
            <li class="nav-item text-start">
              <a class="nav-link'
              . ($page == $m ? ' active" aria-current="page' : '')
              . '" href="?a=' . $a . ($page ? '&amp;m=' . $page : '') . '">' . $name . '</a>
            </li>';
}

if ($a == 'uefa')
{   // сбор туров сезона для еврокубков
    $leagues = ['GOLDL' => 'Золотая Лига', 'CHAML' => 'Лига Чемпионов', 'CUPSL' => 'Кубковая Лига', 'UEFAL' => 'Лига Европы', 'CONFL' => 'Лига Конференций'];
    $tournaments = ['GOLDL' => [], 'CHAML' => [], 'CUPSL' => [], 'UEFAL' => [], 'CONFL' => []];
    $tour_type = !isset($l) ? 'GOLDL' : $l;
    $sidebar .= '
          <div class="accordion" id="sidebarAccordion">';
    if (substr($s, 0, 4) != '2008' && is_dir($season_dir.'programs'))
    {   // в 2008-м была другая структура
        $dir = scandir($season_dir.'programs');
        unset($dir[1], $dir[0]);
        foreach ($dir as $prog)
        {
            $tour = substr($prog, 5);
            $tournaments[substr($prog, 0, 5)][] = $tour;
        }
        foreach ($tournaments as $league => $ttours)
        {
            if (sizeof($ttours))
            {
                rsort($ttours, SORT_NUMERIC);
                $sidebar .= tournament_header($league, $tour_type, $leagues[$league]);
                foreach ($ttours as $to)
                    $sidebar .= tour_line($season_dir, $to, $to, $league);

                $sidebar .= '
                  <li class="row">
                    <div class="col-6 text-start"><a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;m=calc">календарь</a></div>
                    <div class="col-6"><a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;m=genc">генераторы</a></div>
                  </li>
                </ul>
              </div>
            </div>';
            }
        }
    }
}
else if ($a == 'sfp-team')
{   // сбор туров сезона для SFP
    $leagues = ['PRO' => 'ProfiOpen', 'FFP' => 'Фестиваль ФП', 'PRE' => 'PREDвидение', 'TOR' => 'Лига КСП «Торпедо»', 'VOO' => 'Спартакиада', 'SPR' => 'Спартакиада', 'FWD' => 'Эксперт-Лига'];
    $tournaments = ['PRO' => [], 'FFP' => [], 'PRE' => [], 'TOR' => [], 'SPR' => [], 'FWD' => []];
    $tour_type = !isset($l) ? 'PRO' : $l;
    $sidebar .= '
          <div class="accordion" id="sidebarAccordion">';
    foreach ($leagues as $league => $tname)
        if (is_dir($season_dir.$league))
        {
            $sidebar .= tournament_header($league, $tour_type, $tname);
            if ($coach_name == 'Александр Сесса')
                $sidebar .= '
                  <li class="text-end">
                    <a href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;m=touradd">
                      <button type="button" class="small bg-danger rounded-circle text-white" title="ДОБАВИТЬ ТУР">+</button>
                    </a>
                  </li>';

            $dir = scandir($season_dir.$league, 1);
            foreach ($dir as $tt)
                if (!in_array($tt[0], ['.', 'n']))
                    $sidebar .= tour_line($season_dir, $tt, $tt, $league);

                $sidebar .= "\n                </ul>\n              </div>\n            </div>";

        }

}
else if ($a == 'world' && $s > '2018')
{   // сбор туров Лиги Наций
    $tnames = ['msl' => 'Лига Сайтов', 'unl' => 'Лига Наций', 'uft' => 'Финальный турнир', 'uec' => 'Турнир ЧЕ 2021', 'upt' => 'Пробные туры'];
    $ttours = ['msl' => [1, 11], 'unl' => [1, 11], 'uft' => [12, 16], 'uec' => [17, 21], 'upt' => [95, 99]];
    $tour_type = !isset($t) ? 'l' : ($t < 'UNL12' ? 'l' : ($t < 'UNL17' ? 't' : ($t < 'UNL22' ? 'c' : 't')));
    $dir = scandir($season_dir.'programs', 1);
    $sidebar .= '
          <div class="accordion" id="sidebarAccordion">';
    foreach ($tnames as $code => $tname)
        if (in_array('UNL'.$ttours[$code][0], $dir) || is_file($season_dir.'cal.'.$code))
        {
if ($code == 'upt')
$tour_type = 't';
            $sidebar .= tournament_header($code[2], $tour_type, $tnames[$code]);
            for ($i = $ttours[$code][1]; $i >= $ttours[$code][0]; $i--)
            {
                $tp = $i + 1 - $ttours[$code][0];
                $tt = ($i <= 9 ? '0' : '' ) . $i;
                if (in_array('UNL'.$tt, $dir))
                    $sidebar .= tour_line($season_dir, $tt, $tp, $code[1]);

            }
            if ($code != 'upt')
            {
                if (is_file($season_dir.'publish/table.'.$code[1]))
                    $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;s='.$s.'&amp;m=table_'.$code.'">Турнирная таблица</a>
                    </div>
                  </li>';

                if (is_file($season_dir.'cal.'.$code))
                    $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;s='.$s.'&amp;m=cal_'.$code.'">Календарь</a>
                    </div>
                  </li>';

                $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;m=teamroom&amp;l='.$code.'">Раздевалка</a>
                    </div>
                  </li>
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;m=coach_'.$code.'">Тренерская</a>
                    </div>
                  </li>';
                if (is_file($season_dir.'codes.tsv'))
                    $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;s='.$s.'&amp;m=player&amp;l='.$code[1].'">Участники</a>
                    </div>
                  </li>';

            }
                $sidebar .= '
                </ul>
              </div>
            </div>';
        }
}
else if (in_array($cca, $classic_fa))
{   // сбор туров сезона для классических асоциаций
    $tournaments = ['r' => [], 'g' => [], 'p' => [], 'c' => [], 's' => []];
    $tnames = ['r' => 'Чемпионат', 'g' => 'Золотой матч', 'p' => 'Плей-офф', 'c' => 'Кубок', 's' => 'Суперкубок'];
    $cclen = strlen($cca);
    $tour_type = !isset($t) || is_numeric($t[0]) ? 'r' : $t[0];
    $sidebar .= '
          <div class="accordion" id="sidebarAccordion">';
    if (is_dir($season_dir.'programs'))
    {
        $dir = scandir($season_dir.'programs');
        unset($dir[1], $dir[0]);
        foreach ($dir as $prog)
        {
            $tour = substr($prog, $cclen);
            if (is_numeric($tour[0]))
                $tournaments['r'][] = $tour; // регулярный чемпионат
            else
                $tournaments[lcfirst($tour[0])][] = substr($tour, 1); // прочие турниры с буквенным модификатором

        }
        foreach ($tournaments as $tindex => $ttours)
        {
            $ti = $tindex != 'r' ? $tindex : '';
            $is_cal = is_file($data_dir.'online/'.$cca.'/'.$s.'/cal'.$ti);
            $is_gen = is_file($data_dir.'online/'.$cca.'/'.$s.'/gen'.$ti);
            if (sizeof($ttours) || $is_cal || $is_gen)
            {
                rsort($ttours, SORT_NUMERIC);
                $sidebar .= tournament_header($tindex, $tour_type, $tindex == 'g' && sizeof($ttours) > 1 ? 'Золотой турнир' : $tnames[$tindex]);
                foreach ($ttours as $to)
                    $sidebar .= tour_line($season_dir, $ti.$to, $to);

                if ($is_cal)
                    $sidebar .= '
                  <li class="row">
                    <div class="col-6 text-start"><a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.($league ?? false ? '&amp;l='.$league : '').'&amp;m=cal'.$ti.'">календарь</a></div>'
                    . ($is_gen ? '
                    <div class="col-6"><a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.($league ?? false ? '&amp;l='.$league : '').'&amp;m=gen'.$ti.'">генераторы</a></div>' : '') . '
                  </li>';

                $sidebar .= "\n                </ul>\n              </div>\n            </div>";
            }
        }
    }
}

$left = ['Выбор сезона' . $sidecmd, $sidebar];

////////// rest-обработчик

if (isset($matches) || isset($updates) || isset($mtscores)) {
  if (isset($matches))
    bz_matches($matches);

  else if ($a == 'sfp-team' && $l != 'FFL' && $l != 'FWD') {
    $lock = $online_dir . 'log/renew.' . $l;
    if (!is_file($lock)) { // если первый, обновляем календарь
      touch($lock);
      $matches = sizeof(file($data_dir . "online/SFP/$s/prognoz/$l$t/cal"));
      for ($nm=1; $nm<=$matches; $nm++)
        file_get_contents("https://fprognoz.org/?a=sfp-team&l=$l&s=$s&m=prognoz&t=$t&renew=1&n=$nm");

      unlink($lock);
    }
    else {                 // иначе ждём пока его обновят; это ajax - можно ждать несколько секунд
      $timer = 50000;
      while ($timer-- && is_file($lock)) time_nanosleep(0, 1000);
    }
  }
  include $a.'/prognoz.inc.php'; // REST only
}
else {
    $associations = '';
    foreach ($fa as $e => $r)
        $associations .= '
            <li><a class="dropdown-item text-warning" href="?a='.$e.'"><img src="/images/63x42/'.$e.'.png" alt="'.$r.'" title="'.$r.'" height="28"><span class="ms-3">'.$r.'</span></a></li>';

    if ($a == 'sfp-20')
        $a = 'world';

    echo '
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=.75, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta property="og:image" content="https://fprognoz.org/images/sfp.jpg">
<link rel="icon" href="favicon.ico">
<title>FPrognoz.Org: ' . $fa[$a] . '</title>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/css/comments.css?ver=2" rel="stylesheet">
<!--[if lt IE 9]><script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js" integrity="sha256-3Jy/GbSLrg0o9y5Z5n1uw0qxZECH7C6OQpVBgNFYa0g=" crossorigin="anonymous"></script><![endif]-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/solid.js" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/fontawesome.js" crossorigin="anonymous"></script>
<!-- CKEditor for Comments System -->
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/inline/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/inline/translations/ru.js"></script>
<script src="/js/jquery-ui/jquery-ui.min.js"></script>
<script src="/js/jquery-ui/jquery.ui.touch-punch.min.js"></script>
<link href="/js/croppic/croppic.css" rel="stylesheet">
<script src="/js/croppic/croppic-3.0.min.js"></script>
<script src="/comments/comments.js?v=23"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jstimezonedetect@1.0.7/dist/jstz.min.js" integrity="sha256-bt5sKtbHgPbh+pz59UcZPl0t3VrNmD8NUbPu8lF4Ilc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/socket.io-client@2.4.0/dist/socket.io.slim.js" integrity="sha256-w9DVlb/Yjkq3lOk0YpBPzeL+FZvaNALjmkJoZQYGc0I=" crossorigin="anonymous"></script>
<style>
.fastlinks {line-height: 1rem;}
.fastlinks li {line-height: 1rem;}
.btn_hq {
	height: 1.8rem;
	width: 1.8rem;
	padding: 0.1rem;
	box-shadow: 1px 1px 5px grey;
	outline: none !important;
	border: 1px double black;
	border-radius: 0.25rem;
}
.nav-item	{ padding: 0 0.2em; }
.nav-item .noplay	{ color: lightgrey; }
.nav-item .toolate	{ color: red; }
.nav-item .alarm	{ color: coral; }
.nav-item .absent	{ color: yellow; }
.nav-item .playing	{ color: cyan; }
.nav-item .present	{ color: lightgreen; }
.nav-item .result	{ color: white; }
.nav-item A		{ text-decoration: none; }
.nav-item A:hover	{ color: gold; }
/* ---------------------------------------------------
    OVERLAY MASK
----------------------------------------------------- */
.overlay {
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 10;
    display: none;
    background-color: rgba(0, 0, 0, 0.65);
    position: fixed;
    cursor: default;
}
.overlay:target {
    display: block;
}
.loaderP { justify-content: space-around;	align-items: center;	display: flex;}
.loader {
	border: 3px solid #f3f3f3;
	border-radius: 50%;
	border-top: 3px solid blue;
	border-right: 3px solid green;
	border-bottom: 3px solid red;
	border-left: 3px solid pink;
	width: 15px;
	height: 15px;
	-webkit-animation: spinbz 2s linear infinite;
	animation: spinbz 2s linear infinite;
	margin-top: 1px;
	margin-bottom: 0px;
}
.loaderB {
	border: 6px solid #f3f3f3;
	border-radius: 50%;
	border-top: 6px solid blue;
	border-right: 6px solid green;
	border-bottom: 6px solid red;
	border-left: 6px solid pink;
	width: 48px;
	height: 48px;
	-webkit-animation: spinbz 2s linear infinite;
	animation: spinbz 2s linear infinite;
	margin-top: 135px;
	margin-bottom: 0px;
}
@-webkit-keyframes spinbz {
	0% { -webkit-transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); }
}
@keyframes spinbz {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}
.bet {
    width: 1.5em;
    height: 1.5em;
    vertical-align: middle;
    margin-bottom: 0.2em;
    color: white;
    font-weight: bold;
    border: 1px solid black;
    border-radius: 50%;
    --bs-bg-opacity: .67;
}
.bet:active {
    border: 2px solid black;
    border-radius: 50%;
}
.pr_str {
    font-size: 1.2em;
    font-weight: bold;
    width: 0.8em;
    height: 1.1em;
    box-sizing: content-box;
}
.blink { color: red;
    -webkit-animation: blink 2s linear infinite;
    -moz-animation: blink 2s linear infinite;
    -ms-animation: blink 2s linear infinite;
    -o-animation: blink 2s linear infinite;
    animation: blink 2s linear infinite;
}

@-webkit-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-moz-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-ms-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-o-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}
/* ---------------------------------------------------
    LIVESCORE.BZ
----------------------------------------------------- */
.tablex {
	border-top-left-radius: 0px;
	border-top-right-radius: 0px;
	border-bottom-left-radius: 0px;
	border-bottom-right-radius: 0px;
	margin: 0px 0px;
	width: 100%;
	border: 0px;
	border-spacing: 0px;
	padding: 0px;
}
.det {padding:0px;font-size:92%}
.det table tr td {background-color:lavender;color:black;vertical-align: middle; border-width: 0px 0px 0px 0px !important;}
.det table tr td em {opacity: 0.5;}
.det img {max-height: 12px; border-width: 0px 0px 0px 0px;display: inline-block;width: auto;margin: 0px 0px;float: none !important;}
.det table td:nth-child(2) {text-align: center !important;}
.det .min {width:36px}
.det .side {width:47%}
.det .center {width:6%;text-align:center}
.det .left {float:left;text-align:left}
.det .right {float:right;text-align:right}
.det .green-red {width:18px;color:red;font-weight:bolder;position:relative;display:inline-block;vertical-align:top;}
.det .green-red:before,
.det .green-red:after {content:"⇅";position:absolute;top:0;left:0;padding-left:1px;color:green;width:50%;overflow:hidden;}
.monospace {
	white-space: pre-wrap;
	font-family: monospace;
	font-size: 1.1em;
	line-height: 1;
}
/* ---------------------------------------------------
    PROGNOZ
----------------------------------------------------- */
.mt-res { display: inline; float: right; width: 100px; margin-left: 5px; }
.b-cyan	{ background-color: cyan;	font-weight: bold; }
.b-lime	{ background-color: lime;	font-weight: bold; }
.b-pink	{ background-color: pink;	font-weight: bold; }
.b-yell	{ background-color: yellow;	font-weight: bold; }
.s-hname{ text-align: left;	font-weight: bold;	padding-left: 12px; }
.s-name	{ text-align: left;		font-size: 0.9em;	padding-left: 12px; }
.s-num	{ text-align: right;		font-size: 0.9em;	padding-right: 12px; }
.s-spacer{ min-width: 60px; }
.s-table{ margin: 20px; }
#mt	{
	white-space:pre;
	width:33%;
}
.tribune li {
    background: #1e2a64;
    color: whitesmoke;
    list-style-type: none;
    padding: 8px;
    border-radius: 10px;
    margin-top: 4px;
    white-space: pre-wrap;
}
.tribune b { color: yellow; }
.blue { color: blue;	font-weight: bold; }
.magenta { color: magenta;	font-weight: bold; }
.red { color: red;	font-weight: bold; }
mark { font-weight: bold;	background-color: yellow;	padding: 0; }
.scrollToTop{
    display: none;
    position: fixed;
    bottom: 10px;
    left: 25px;
    z-index: 99;
    font-size: 48px;
    color: royalblue;
    opacity: 0.5;
}
.scrollToTop:hover{
    color: whitesmoke;
}
</style>
<script>
(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');ga(\'create\', \'UA-92920347-1\', \'auto\');ga(\'send\', \'pageview\');
$.browser={};$.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());$.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());$.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());$.browser.msie=/msie/.test(navigator.userAgent.toLowerCase())
var fg=0,ti=[],si=[],tz=jstz.determine();document.cookie="TZ="+tz.name()+";path=/;SameSite=Strict"

function validateEmail(email){var re=/\S+@\S+\.\S+/;return re.test(email)}
function passwordCheck(str){
  $.post("/online/ajax.php",{data:$("#pass_str").data("tpl"),nick:$("#name_str").val(),pswd:str},function(r){
    if(r==\'0\'){
      $("#valid_name").html(\'<span style="color:lightsalmon"><i class="fas fa-times"></i> неверный пароль</span>\');
      fg=setTimeout(function(){$("#forget").show()},17000)
    }
    else if(r==\'1\'){
      $("#valid_name").html(\'<span style="color:lightgreen"><i class="fas fa-check"></i> добро пожаловать!</span>\');
      $("#login_form").submit()}
    else if(r==\'2\'){
      $("#valid_name").html(\'<span style="color:lightgreen"><i class="fas fa-check"></i> пароль выслан</span>\')
    }
  })
}
function showTab(i){
var uri=window.location.search,pos=uri.lastIndexOf("&n=");if(pos>0)uri=uri.substring(0,pos);window.history.pushState(null, null, uri+"&n="+i);$(".multitabs").hide();$("#tab-"+i).show();$("#whatsifn").val(i);$("#dynamic").attr("data-tab",i);$("#pl").attr("tabindex",-1).focus();return false}
function sendPredicts(apikey,tour,codes,predicts){$.post("/online/ajax.php",{data:apikey,tour:tour,team_codes:codes,predicts:predicts},function(r){$("#statusline").html(r);$("#send_predict").removeClass("btn-primary");$("#send_predict").addClass(r.indexOf("success")>0?"btn-success":"btn-danger");$("#send_predict").removeAttr("disabled")})}
function emailCheck(str){$.post("/online/ajax.php",{data:$("#name_str").data("tpl"),nick:str,email:str},function(r){if(r==\'0\')$("#valid_name").html(\'<span style="color:lightsalmon"><i class="fas fa-times"></i> такой e-mail не найден</span>\');else{str=$("#pass_str").val();if(str.length)passwordCheck(str);else $("#valid_name").html(\'<span style="color:lightblue;cursor:pointer" onClick="tokenSend(); return false" title="Вам будет выслана ссылка для входа"><i class="fas fa-check"></i> войти без пароля?</span>\')}})}
function nicknameCheck(str){$.post("/online/ajax.php",{data:$("#name_str").data("tpl"),nick:str,email:""},function(r){if(r==\'0\')$("#valid_name").html(\'<span style="color:lightsalmon"><i class="fas fa-times"></i> имя/e-mail не найдены</span>\');else{str=$("#pass_str").val();if(str.length)passwordCheck(str);else $("#valid_name").html(\'<span style="color:lightgreen"><i class="fas fa-check"></i> теперь введите пароль</span>\')}})}
function tokenSend(){$.post("/online/ajax.php",{data:$("#login_form").data("tpl"),nick:$("#name_str").val()},function(r){if(r==\'1\')$("#valid_name").html(\'<span style="color:lightgreen"><i class="fas fa-check"></i> проверьте вашу почту</span>\');else $("#valid_name").html(\'<span style="color:lightsalmon"><i class="fas fa-times"></i> не удалось отправить</span>\')})}
function newPassword(){return true}
function addTournament(o){var id=$(o).data("id");a=+id.substring(id.length-1);if(typeof ti[a]=="undefined")ti[a]=a;ti[a]++;a=ti[a];html=\'<ul id="tournament-\'+a+\'"><li><div>Название турнира: </div><input type="text" name="tournament[\'+a+\']" value="" placeholder="не обязательно"> <div class="delete_stage" data-id="tournament-\'+a+\'"><button class="fas fa-trash" title="удалить турнир"></button></div></li><li><div>Префикс кода тура: </div><input type="text" name="prefix[\'+a+\']" value="" placeholder="по умолчанию - код ассоциации"></li><li><div>Схема розыгрыша: </div><select name="type[\'+a+\']"><option value="chm">чемпионат (круговой турнир)</option><option value="cup">кубок (турнир с выбыванием)</option><option value="com">комбинированный (группы + плей-офф)</option></select></li><li><div>Нумерация туров: </div><select name="numeration[\'+a+\']"><option value="stage">поэтапная (каждый этап начинается туром 1)</option><option value="toend">сквозная (без сброса номера, как в еврокубках)</option></select></li><li><h6>Этапы: <div class="add_stage" data-id="trn-\'+a+\'-st-0"><button class="fas fa-plus-circle" title="добавить этап"></button></div></h6><div id="div-trn-\'+a+\'-st-0" class="stage-div"></div></li></ul><div id="div-tournament-\'+a+\'" class="tournament-div"></div>\';$("#div-tournament-"+(a-1)).after(html);}
function addStage(o){var id=$(o).data("id");a=id.split(\'-\');b=+a[3];a=+a[1];if(typeof ti[a]=="undefined")ti[a]=a;if(typeof si[a]=="undefined")si[a]=[];if(typeof si[a][b]=="undefined")si[a][b]=b;si[a][b]++;b=si[a][b];html=\'<ul id="trn-\'+a+\'-st-\'+b+\'"><li><div>Название этапа: </div><input type="text" name="stage[\'+a+\'][\'+b+\']" value="" placeholder="не обязательно"> <div class="delete_stage" data-id="trn-\'+a+\'-st-\'+b+\'"><button class="fas fa-trash" title="удалить этап"></button></div></li><li><div>Суффикс кода тура: </div><input type="text" name="suffix[\'+a+\'][\'+b+\']" value="" placeholder="по умолчанию нет"></li><li><div>Файл календаря: </div><input type="text" name="cal[\'+a+\'][\'+b+\']" value="" placeholder="по умолчанию cal"></li><li><div>Количество групп (лиг): </div><input type="text" name="groups[\'+a+\'][\'+b+\']" value="" placeholder="по умолчанию 1"></li><li><div>Количество туров: </div><input type="text" name="tourn[\'+a+\'][\'+b+\']" value=""></li><li><div>Количество кругов: </div><input type="text" name="round[\'+a+\'][\'+b+\']" value="" placeholder="по умолчанию 2"></li><li><div>Префикс названия тура: </div><input type="text" name="nprefix[\'+a+\'][\'+b+\']" value="" placeholder="по умолчанию Тур: "></li></ul><div id="div-trn-\'+a+\'-st-\'+b+\'" class="stage-div"></div>\';$("#div-trn-"+a+"-st-"+(b-1)).after(html);}
function replaceEditable(m){
if($("#editable").data("hl")){var t=$("#editable").data("hl");$("#editable").html($("#editable").html().replace(new RegExp("<mark>"+t+"</mark>", \'g\'), t))}
if(m=="pres")
{
  $("#editable").replaceWith(\'<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="form-control mailSubject" placeholder=" Заголовок пресс-релиза"></p>Форматированный текст нового пресс-релиза, но можно и простой текст без украшательств:<div id="editable" name="text" class="border border-1" style="width:100%;height:30em"></div>Чисто текстовая версия нового пресс-релиза для примитивных почтовых клиентов (необязательно):<textarea name="altbody" class="monospace" style="width:100%;height:10em"></textarea></form>\')
  InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})
}
else if(m=="text"||m=="news"){
  if($("#editable").hasClass("monospace"))
    $("#editable").replaceWith(\'<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="form-control mailSubject" value="\'+$("#mailIcon").data("subj")+\'"></p><textarea id="editable" name="text" class="monospace" style="width:100%;height:\'+Math.max(20,$("#editable").html().split("\n").length)+\'em">\'+$("#editable").html()+"</textarea></form>");
  else
  {
    body=$("#editable").html()
    $("#editable").replaceWith(\'<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="form-control mailSubject" value="\'+$("#mailIcon").data("subj")+\'"></p><div id="editable" name="text" class="border border-1" style="width:100%;height:30em">\'+body+\'</div>Чисто текстовая версия рассылки для примитивных почтовых клиентов (необязательно):<textarea name="altbody" class="monospace" style="width:100%;height:10em"></textarea></form>\')
    InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})
  }
}
else if(m=="edit"){if($("#editable").hasClass("monospace"))$("#editable").replaceWith(\'<textarea id="editable" class="monospace" style="width:100%;height:\'+Math.max(20,$("#editable").html().split("\n").length)+\'em">\'+$("#editable").html()+"</textarea>");else InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})}
else{html=\'<form id="theMail" method="POST"><div id="editable" style="display: flex; width: 100%; align-items: stretch;perspective: 900px;"><div style="min-width: 13em; max-width: 13em; line-height: 1em">\';i=0;$(\'.player_name\').each(function(){name=$(this).html();if(name.indexOf("value=")>0)name=name.split(\'"\')[5];if(name)html+=\'<br><label><input type="checkbox" name="p[\'+(i++)+\']"> \'+name+"</label>"});html+=\'</div><div style="width: 100%; min-height: \'+(i*1.5)+\'em"><input type="text" name="subj" style="width: 100%" placeholder=" Заголовок сообщения"><textarea name="text" style="width: 100%; margin-top: 10px; height:\'+((i-2)*1.5)+\'em" placeholder=" Текст сообщения"></textarea></div></div></form>\';$("#editable").replaceWith(html)}
}
function bindCroppic(){
  if($("#funZoneIndicator").html()){
    var croppicContainerModalOptions={uploadUrl:"comments/img_save_to_file.php",cropUrl:"comments/img_crop_to_file.php?userId="+$("#comments_wrapper").data("name"),modal:true,doubleZoomControls:false,imgEyecandyOpacity:0.4,loaderHtml:"<div class=\"loader bubblingG\"><span id=\"bubblingG_1\"></span><span id=\"bubblingG_2\"></span><span id=\"bubblingG_3\"></span></div> ",}
    var cropContainerModal=new Croppic("cropContainerModal",croppicContainerModalOptions)
  }
}


$(document).ready(function(){
if($(".rightbar-header").data("log")=="out")history.pushState(null,"", "/")
else if($(".rightbar-header").data("log")=="in"){var x=window.matchMedia("(max-width:1200px)");if(x.matches){$("#rightbar").addClass("active");$("#rightbarCollapse").addClass("active");$("#rightbarIconUser").hide();$("#rightbarIconUserX").show()}};
$("#sidebarCollapse").click(function(){$("#sidebar").toggleClass("active");$(this).toggleClass("active")});
$("#rightbarCollapse").click(function(){$("#rightbar").toggleClass("active");$(this).toggleClass("active");if($("#rightbarIconUser").is(":hidden")){$("#rightbarIconUser").show();$("#rightbarIconUserX").hide()}else if($("#rightbarIconUserX").is(":hidden")){$("#rightbarIconUser").hide();$("#rightbarIconUserX").show()}});
$("#name_str").blur(function(){if(!$("#name_str").is(":hover")){var str=$(this).val();if(str.length<2)$("#valid_name").html(\'<span style="color:pink"><i class="fas fa-times"></i> введите хотя бы 2 буквы</span>\');else if(validateEmail(str)) emailCheck(str);else nicknameCheck(str)}})
$("#pass_str").keyup(function(k){
  if($("#pass_str").is(":focus")&&k.key!="Shift"){
    clearTimeout(fg);
    str=$(this).val();
    if(str.length)passwordCheck(str);
    else{
      str=$("#name_str").val();
      if(validateEmail(str))emailCheck(str);
      else $("#valid_name").html(\'<span style="color:lightgreen"><i class="fas fa-check"></i> теперь введите пароль</span>\')
    }
  }
})
$(window).scroll(function(){if($(this).scrollTop()>100)$(".scrollToTop").fadeIn();else $(".scrollToTop").fadeOut()});
$(".scrollToTop").click(function(){$("html,body").animate({scrollTop:0},400);return false});
$("#editIcon").click(function(){$("#saveIcon").toggleClass("d-none");$("#editIcon").toggleClass("d-none");replaceEditable("edit")})
$("#saveIcon").click(function(){$("#editIcon").toggleClass("d-none").css("background","green").css("color","whitesmoke");$("#saveIcon").toggleClass("d-none");if($("#editable").hasClass("monospace"))$("textarea#editable").replaceWith(\'<div id="editable" class="monospace">\'+$("#editable").val()+"</div>");else editable.destroy();$.post("/online/ajax.php",{data:$("#saveIcon").data("tpl"),text:encodeURIComponent($("#editable").html())},function(r){})})
$(".add_tournament").click(function(){addTournament(this);$(".add_stage").of("click").click(function(){addStage(this)});$(".delete_stage").off("click").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})})
$(".add_stage").click(function(){addStage(this);$(".delete_stage").off("click").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})})
$(".delete_stage").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})
$("#season_settings").change(function(){console.log(\'y\');$("#ConfigEditor").show();$("#saveCfgIcon").css("background","orangered")})
$("#ConfigEditor").click(function(){$.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#saveCfgIcon").data("tpl"))+\'&\'+$("#season_settings").serialize(),success:function(r){$("#saveCfgIcon").css("background","green")}})})
$("#MainForm").change(function(){$("#SubmitForm").show()})
$(".pressrelease-title").click(function(){show=$("#"+$(this).data("pr")).is(":hidden");$(".pressrelease").hide();if(show)$("#"+$(this).data("pr")).show();$("html,body").scrollTop(0)})
$("#sendMail").click(function(){
if($("#sendIcon").hasClass("d-none")){
  $("#sendIcon").toggleClass("d-none");$("#mailIcon").toggleClass("d-none");replaceEditable($(this).data("mode"))
}else{
  $(".overlay").fadeTo("slow",0.65);$(".overlay").html(\'<div class="loaderP"><div class="loaderB">\')
  if($("div[name=\'text\']").length)$("#theMail").append("<textarea name=\'text\' hidden>"+$("div[name=\'text\']").html()+"</textarea>")
  $.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#sendIcon").data("tpl"))+\'&\'+$("#theMail").serialize(),success:function(r){$("#editable").html(r);$("#mailIcon").toggleClass("d-none");$("#sendMail").css("background","green").css("color","whitesmoke");$("#sendIcon").toggleClass("d-none");$(".overlay").hide()}})}
})
$("a[name=modal]").click(function(e){e.preventDefault();$(\'.overlay\').fadeTo("fast",0.65);$("#mwin").addClass("popup-show")});
$(".popup .close,.overlay").click(function(e){e.preventDefault();$(".overlay").hide();$("#mwin").removeClass("popup-show")});
$("#editable").html(function(index,text){if($(this).data("hl")){var t=$(this).data("hl");return text.replace(new RegExp(t, \'g\'), "<mark>"+t+"</mark>")}})
if($("#timedisplay").length>6)setInterval(function(){if(seconds<59)seconds++;else{seconds=0;if(minutes<59)minutes++;else{minutes=0;hours=hours<23?hours+1:0}}var sts=seconds+"",stm=minutes+"";if(sts.length<2)sts="0"+sts;if(stm.length<2)stm="0"+stm;$("#timedisplay").html(hours+":"+stm+":"+sts)},1000)
$("#toggleFunZone").click(function(){$.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#toggleFunZone").data("tpl")),success:function(r){c=r?"on":"off";if(c=="on")bindCroppic()}})})
$(".c-content").change(function(){
  if($("#toggle"+this.id.substr(7)+" i").hasClass("fa-edit")&&html.indexOf("img-fluid")==-1)
    $(this).html($(this).html().replace(\'src=\', \'class="img-fluid" src=\'))
})
bindCroppic()
})



</script>
</head>

<body class="bg-light" style="padding-top:0;padding-bottom:0;font-family: Roboto,Arial,Helvetica,sans-serif;">
  <div class="overlay"></div>
  <a href="#" class="scrollToTop"><i class="fas fa-arrow-circle-up"></i></a>
  <div class="fixed-top">
    <nav class="navbar navbar-expand-md navbar-dark" style="padding:0 5px; background: #1e2a64; background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: top left;background-size: 100% 40px;">
      <div class="container-fluid">
        <div class="dropdown">
          <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: left; width: 186px">
            <img src="/images/63x42/'.$a.'.png" alt="'.$fa[$a].'" title="'.$fa[$a].'" height="26">
            <span class="ps-2">'.($a == 'fifa' ? 'Ассоциация' : $fa[$a]).'</span>
          </button>
          <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1">
' . $associations . '
          </ul>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarToggler">
<!--div class="col-md order-md-3 dropdown d-flex justify-content-between" style="max-width: 20rem;"-->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 pe-0 w-100">'
            . navbar_item('hof', 'Зал Славы')
            . navbar_item('', 'Информация')
            . navbar_item('hq', 'Президиум')
            . '
          </ul>
        <!--div class="nav-item text-light pt-2 me-2">
          <a class="fas fa-envelope-open h5" title="Внутрення почта"></a>
        </div-->
          <div class="dropdown">';
if (isset($_SESSION['Coach_name']) && $role != 'badlogin')
{
    echo '
              <button class="btn btn-dark dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="' . (isset($_SESSION['ms_uname']) || isset($_GET['m']) || isset($_POST['register']) ? 'false' : 'true') . '" style="background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: left;width: 200px;">
                <i class="fas fa-user"></i> '.  $_SESSION['Coach_name'] . '
              </button>
              <ul class="dropdown-menu bg-light" aria-labelledby="navbarDropdown" style="width: 240px; left: -20px;">
                <li><a class="dropdown-item" href="?m=profile">Профиль</a></li>
                <li><div class="dropdown-divider"></div></li>
                <li><a class="dropdown-item" href="?logout=1">Выйти</a></li>
                <li><div class="dropdown-divider"></div></li>';
    # команды игрока
    foreach ($cmd_db as $cc => $cc_data)
        if ($cc != 'UNL') // временно не показываем
            foreach ($cc_data as $code => $team)
                if ($team['usr'] == $_SESSION['Coach_name'])
                {
                    $highlight = $team['cmd'];
                    echo '
                <li><a class="dropdown-item" href="?a='.strtolower($ccn[$cc]).'">'.($cc == 'SFP' ? 'Сборная сайта' : $team['cmd']).' ('.$cc.')</a></li>';
                }

    echo '
              </ul>';
}
else
{
    $data_cfg = ['cmd' => 'unique_check'];
    $ncfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    $data_cfg = ['cmd' => 'password_check'];
    $pcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    $data_cfg = ['cmd' => 'send_token'];
    $tcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
              <button class="btn btn-dark dropdown-toggle show" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="' . (isset($_SESSION['ms_uname']) || isset($_GET['m']) || isset($_POST['register']) ? 'false' : 'true') . '" style="background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: left;width: 200px;">
                <i class="fas fa-user"></i> Вход
              </button>
              <ul class="dropdown-menu bg-light show" aria-labelledby="navbarDropdown" style="width: 240px; left: -20px;">
                <form id="login_form" class="p-2 text-center" method="POST" data-tpl="'.$tcfg.'">
                  E-mail или код команды:
                  <input type="text" class="form-control" data-tpl="'.$ncfg.'" id="name_str" name="name_str">
                  Пароль:
                  <input type="password" class="form-control" data-tpl="'.$pcfg.'" id="pass_str" name="pass_str">
                  <p id="valid_name">
                    <button type="submit" class="btn btn-success shadow-sm m-2" id="login" name="login">Вход</button>
                  </p>
                </form>
              </ul>';
}
echo '
          </div>
<!--/div-->
        </div>
      </div>
    </nav>
    <ul class="navbar fastlinks py-0 px-2 justify-content-md-center" style="background: #1e2a64; list-style: none; height: 50px;">
        ';
//      <li class="nav-item"><a class="nav-link text-light" href="?m=konk39">Предсезонный конкурс</a></li>
if (isset($_SESSION['Coach_name']))
{
    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc'))
        build_personal_nav();

    include($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc');
}
echo '
    </ul>
  </div>

    <div style="height:90px"></div>
    <!--nav class="navbar navbar-expand-md navbar-dark mt-5" style="padding:0 5px; background: #1e2a64;">
    </nav-->

    <main class="min-vh-100" id="main">
      <div class="d-lg-flex me-0">
        <div class="" style="min-width: 256px;">
          <div class="m-2 text-dark">
            <h6>
              ' . $left[0] . '
            </h6>
          </div>
          <ul class="list-unstyled text-center shadow rounded-3">
' . $left[1] . '
          </ul>
        </div>
        <div class="flex-fill mx-2" style="max-width: 992px;">
          <div class="my-2 text-dark">';
    if ($role == 'president')
    {
        if ($m == 'codestsv')
            echo '
              <button type="button" id="SubmitForm" class="btn btn-light btn_hq" title="Сохранить изменения"  onClick="$(\'#MainForm\').submit()"><div id="postForm" class="fas fa-edit"></div></button>';
        if ($m == 'player')
            echo '
              <button type="button" id="EditLink" class="btn btn-light btn_hq" title="Редактировать" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=codestsv\'"><div class="fas fa-edit"></div></button>';
        if ($m == 'set')
        {
            $data_cfg = ['cmd' => 'save_config', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
            $scfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
            echo '
              <button type="button" id="ConfigEditor" class="btn btn-light btn_hq"><div id="saveCfgIcon" class="fas fa-save" data-tpl="' . $scfg . '" title="Сохранить изменения"></div></button>';
        }
    }
    if (isset($content) && ($role == 'president' || $role == 'pressa' && (in_array($m, ['news', 'pres']) || $m == 'text' && $ref == 'r')))
    {
        $data_cfg = ['cmd' => 'save_file', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
        if (isset($l))
            $data_cfg['l'] = $l;

        if (isset($ref))
            $data_cfg['ref'] = $ref;

        if (isset($t))
            $data_cfg['t'] = $t;

        $scfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
        echo '
              <button type="button" id="editIcon" class="btn btn-light btn_hq" title="Редактировать"><div class="fas fa-edit"></div></button>
              <button type="button" id="saveIcon" class="btn btn-light btn_hq d-none" title="Сохранить изменения" data-tpl="' . $scfg . '"><div class="fas fa-save"></div></button>';
    }
    if ($role == 'president' && in_array($m, ['cal', 'gen', 'news', 'codestsv', 'player', 'pres', 'prognoz', 'text'])
    || $role == 'pressa' && (in_array($m, ['news', 'pres', 'player']) || $m == 'text' && $ref == 'r')
    || $role == 'coach' && $m == 'player')
    {
        $data_cfg = ['cmd' => 'send_mail', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
        if (isset($ref))
            $data_cfg['ref'] = $ref;

        if (isset($t))
        {
            $data_cfg['t'] = $t;
            switch ($t[0])
            {
                case 'c': $tname = ' кубка'; break;
                case 'p': $tname = ' плей-офф'; break;
                case 's': $tname = ' Суперкубка'; break;
                default : $tname = '';
            }
            $tt = $tname ? substr($t, 1) : ltrim($t, '0');
        }
        $subject = 'ФП. ' . $fa[$a] . '. ';
        switch ($m)
        {
            case 'cal': $subject .= 'Календарь чемпионата ' . $s; break;
            case 'gen': $subject .= 'Генераторы чемпионата ' . $s; break;
            case 'genc': $subject .= 'Генераторы кубка ' . $s; break;
            case 'text':
            switch ($ref)
            {
                case 'p'  : $subject .= 'Программка ' . $tt . ' тура' . $tname; break 2;
                case 'pc' : $subject .= 'Программка ' . $tt . ' тура кубка'; break 2;
                case 'it' : $subject .= 'Итоги ' . $tt . ' тура' . $tname; break 2;
                case 'itc': $subject .= 'Итоги ' . $tt . ' тура кубка'; break 2;
                case 'r'  : $subject .= 'Обзор ' . $tt . ' тура' . $tname; break 2;
                case 'rc' : $subject .= 'Обзор ' . $tt . ' тура кубка'; break 2;
            }
            case 'news': $subject .= 'Регламент сезона ' . $s; break;
            case 'pres': $subject .= 'Пресс-релиз. '; break;
        }
        $mcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
//        if (isset($t))
            echo '
              <button type="button" id="sendMail" class="btn btn-light btn_hq" data-mode="'.(isset($content) ? 'text' : $m).'">
                <div id="mailIcon" class="fas fa-envelope-open" data-subj="'.$subject.'" title="Подготовить текст к рассылке"></div>
                <div id="sendIcon" class="fas fa-envelope d-none" data-tpl="' . $mcfg . '" title="Рассылка текста"></div>
              </button>';
/*
        else if (isset($ref))
            foreach (['p' => 'программка', 'pc' => 'прог.п-офф', 'it' => 'итоги чемп.', 'itc' => 'итоги п-офф', 'r' => 'шапка обзора'] as $r => $name)
                echo '
              <a class="btn btn-'.($r == $ref ? 'dark' : 'secondary').' btn-sm" href="?a='.$a.'&s='.$s.'&m=text&ref='.$r.'">'.$name.'</a>';
*/
    }
    echo '
          </div>
          <div id="middle">
';
    $nohl = ['club', 'main', 'mkpgm', 'news', 'set'];
    //include ('fifa/register.inc.php'); // регистрация в сборных ассоциаций
    // ссылка для отката к прогнозам
    if ($role == 'president' && $m == 'text' && $ref == 'it')
        echo '
                <div style="position: absolute; z-index: 10; opacity: 0.5">
                  <a href="/?a='.$a.'&s='.$s.'&t='.$t.'&m=prognoz"><i class="fas fa-undo"></i></a>
                </div>';

    echo '
                <div id="editable"' . $editable_class . ' data-hl="'.(!in_array($m, $nohl) && ($a != 'sfp-team') && isset($highlight) ? $highlight : '').'">';
    if ($m == 'pres')
    {
        $pr = glob($season_dir.'publish/1*');
        if (sizeof($pr))
        {
            rsort($pr);
            echo '
                    <h4>Пресс-релиз'.(count($pr) > 1 ? 'ы' : '').'</h4>';
            $collapsed = isset($t) ? true : false;
            $uri = $_SERVER['REQUEST_URI'];
            if ($cut = strpos($uri, '&t='))
                $uri = substr($uri, 0, $cut);

            foreach ($pr as $file)
            {
                $ts = substr($file, -10);
                if (isset($t) && $t == $ts)
                    $collapsed = false;

                $text = file_get_contents($file);
                list($text, $subj) = explode(':Subj:', $text.':Subj:');
                echo '
                    <p>
                      <span class="pressrelease-title" data-pr="'.$ts.'">'.date('Y-m-d H:i', $ts).' - '.$subj.'</span>
                      <a href="javascript:;" onClick="$(\'#share' . $ts . '\').toggle();share' . $ts . '.select();return false;" class="fas fa-share-alt small text-info" aria-hidden="true" style="cursor:pointer" title="поделиться"> </a>
                      <input type="text" id="share' . $ts . '" class="small" style="display:none;width:25.5rem;height:1.4rem;" value="' . $this_site . $uri . '&t=' . $ts . '">
                    </p>
                    <div id="'.$ts.'" class="pressrelease"'.($collapsed ? ' style="display:none"' : '').'>'
                    . $text . '
                    </div>';
                $collapsed = true;
            }
        }
        else
            echo '
                    <h5>В этом сезоне пресс-релизов нет.</h5>';

    }
    else if ($m == 'set' && $role == 'president')
    {
        echo '
                    <h4>Редактирование настроек сезона</h4>
                    <form id="season_settings" action="" method="POST">
                        <ul>
                            <li><div>Название ФП-ассоциации: </div><input type="text" name="description" value="'.$description.'"></li>
                            <li><div>html-заголовок ассоциации: </div><input type="text" name="title" value="'.$title.'"></li>
                            <li><div>Заголовок новостных страниц: </div><input type="text" name="main_header" value="'.$main_header.'"></li>
                            <li><div>Название текущего сезона: </div><input id="cur_year" type="text" name="cur_year" value="'.$cur_year.'"></li>
                            <li><div>Президент: </div><input id="president" type="text" name="president" value="'.$president.'"></li>
                            <li><div>Вице-президент(ы): </div><input id="vice" type="text" name="vice" value="'.$vice.'" placeholder="нет; можно несколько имен через запятую"></li>
                            <li><div>Пресс-атташе: </div><input id="pressa" type="text" name="pressa" value="'.$pressa.'" placeholder="нет; можно несколько имен через запятую"></li>';
        if ($a != 'world')
            echo '
                            <li><div>Тренер(ы) сборной: </div><input id="coach" type="text" name="coach" value="'.$coach.'" placeholder="нет; можно несколько имен через запятую"></li>
                            <li><div>Разрешено редактировать составы: </div><input id="club_edit" type="checkbox" name="club_edit"'.($club_edit ? ' checked="checked"' : '').'></li>';
        echo '
                            <li><h5>Турниры: <div class="add_tournament" data-id="tournament-'.(count($config) - 1).'"><button class="fas fa-plus-circle" title="добавить турнир"></button></div></h5>';
        if (isset($config[0]['format']))
            foreach ($config as $n => $tournament)
            {
                $stages = count($config[0]['format']);
                echo '
                                <ul id="tournament-'.$n.'">
                                    <li><div>Название турнира: </div><input type="text" name="tournament['.$n.']" value="'.(isset($tournament['tournament']) ? $tournament['tournament'] : '').'" placeholder="не обязательно"> <div class="delete_stage" data-id="tournament-'.$n.'"><button class="fas fa-trash" title="удалить турнир"></button></div></li>
                                    <li><div>Префикс кода тура: </div><input type="text" name="prefix['.$n.']" value="'.(isset($tournament['prefix']) ? $tournament['prefix'] : '').'" placeholder="по умолчанию - код ассоциации"></li>
                                    <li><div>Схема розыгрыша: </div><select name="type['.$n.']"><option value="chm">чемпионат (круговой турнир)</option><option value="cup" '.(isset($tournament['type']) && $tournament['type'] == 'cup' ? ' selected="selected"' : '').'>кубок (турнир с выбыванием)</option><option value="com" '.(isset($tournament['type']) && $tournament['type'] == 'com' ? ' selected="selected"' : '').'>комбинированный (группы + плей-офф)</option></select></li>
                                    <li><div>Нумерация туров: </div><select name="numeration['.$n.']"><option value="stage">поэтапная (каждый этап начинается туром 1)</option><option value="toend" '.(isset($tournament['nume']) && $tournament['nume'] == 'toend' ? ' selected="selected"' : '').'>сквозная (без сброса номера, как в еврокубках)</option></select></li>
                                    <li><h6>Этапы: <div class="add_stage" data-id="trn-'.$n.'-st-'.($stages - 1).'"><button class="fas fa-plus-circle" title="добавить этап"></button></div></h6>';
                foreach ($tournament['format'] as $e => $stage)
                    echo '
                                        <ul id="trn-'.$n.'-st-'.$e.'">
                                            <li><div>Название этапа: </div><input type="text" name="stage['.$n.']['.$e.']" value="'.(isset($stage['stage']) ? $stage['stage'] : '').'" placeholder="не обязательно"> <div class="delete_stage" data-id="trn-'.$n.'-st-'.$e.'"><button class="fas fa-trash" title="удалить этап"></button></div></li>
                                            <li><div>Суффикс кода тура: </div><input type="text" name="suffix['.$n.']['.$e.']" value="'.(isset($stage['suffix']) ? $stage['suffix'] : '').'" placeholder="по умолчанию нет"></li>
                                            <li><div>Файл календаря: </div><input type="text" name="cal['.$n.']['.$e.']" value="'.(isset($stage['cal']) ? $stage['cal'] : '').'" placeholder="по умолчанию cal"></li>
                                            <li><div>Количество групп (лиг): </div><input type="text" name="groups['.$n.']['.$e.']" value="'.(isset($stage['groups']) ? $stage['groups'] : '').'" placeholder="по умолчанию 1"></li>
                                            <li><div>Количество туров: </div><input type="text" name="tourn['.$n.']['.$e.']" value="'.(isset($stage['tourn']) ? $stage['tourn'] : 1 + $stage['tours'][1] - $stage['tours'][0]).'"></li>
                                            <li><div>Количество кругов: </div><input type="text" name="round['.$n.']['.$e.']" value="'.(isset($stage['round']) ? $stage['round'] : '').'" placeholder="по умолчанию 2"></li>
                                            <li><div>Префикс названия тура: </div><input type="text" name="nprefix['.$n.']['.$e.']" value="'.(isset($stage['nprefix']) ? $stage['nprefix'] : '').'" placeholder="по умолчанию Тур: "></li>
                                        </ul>
                                        <div id="div-trn-'.$n.'-st-'.($stages - 1).'" class="stage-div"></div>';

                echo '
                                    </li>
                                </ul>
                                <div id="div-tournament-'.(count($config) - 1).'" class="tournament-div"></div>';
            }
            echo '
                            </li>
                        </ul>
                    </form>';

    }
    else if (isset($content))
    {
        if (!mb_detect_encoding($content))
            $content = mb_convert_encoding($content, 'UTF-8', 'KOI8-R');

        echo $content;
    }
    else if (is_file($a . '/' . $m . '.inc.php'))
        include ($a . '/' . $m . '.inc.php');

    $data_cfg = ['cmd' => 'fun_zone', 'a' => $a, 's' => $s, 'c' => $coach_name];
    $fcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
            </div>
          </div>
        </div>
        <div class="" style="max-width: 768px;">
          <a id="comments"></a>
          <div class="d-flex align-items-center">
            <h6 class="my-2">Фан-зона</h6>
            <div class="form-check form-switch m-2"><input id="toggleFunZone" class="form-check-input" type="checkbox"'.($gb_status == 'on' ? ' checked' : '').' data-tpl="'.$fcfg.'" data-bs-toggle="collapse" href="#comments_wrapper" role="button" aria-expanded="true" aria-controls="comments_wrapper"></div>
          </div>
          <div class="bg-white shadow rounded-3 p-1 collapse'.($gb_status == 'on' ? ' show' : '').'" id="comments_wrapper" data-name="'.($coach_name ?? '').'" data-hash="'.crypt($coach_name ?? '', $salt).'">';
    include 'comments/main.php';
    echo '
          </div>
        </div>
      </div>
    </main>

    <footer class="footer bg-secondary text-light ps-2">
      Дизайн и код: Александр Сесса
    </footer>

</body>
</html>
';
}
?>
