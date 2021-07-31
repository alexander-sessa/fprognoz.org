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
        return '2021-3';
//    else if ($cc == 'RUS' || $cc == 'FRA')
//        return '2018-19';
    else
        if ($m < 9) $y--;

    return $y . '-' . (substr($y, 2) + 1);
}

# Построение персональной навигации по актуальным турам

function build_personal_nav() {
    global $ccn;
    global $cmd_db;
    global $data_dir;
    global $online_dir;

    $debug_str = '';
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
                                $currentSeason = current_season($startYear, $startMonth, $countryCode);

//if (in_array($tourCode, ['SUI09']))
//  $currentSeason = '2021-1';

                                // World
//          if ($countryCode == 'UNL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false) {
                                if ($countryCode == 'UNL' && $action == 'remind')
                                { // танцуют ВСЕ!!!
                                    $tour_dir = $online_dir . 'UNL/'.$startYear.'/prognoz/'.$tourCode;
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
                                <a class="'.$statusColor[$status].'" href="/?a=world&s='.$startYear.'&t='.substr($tourCode, 3).'&m='.($status == 6 ? 'result' : 'prognoz').'">'.$tourCode.'</a>
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
                                            if (($countryCode != 'SUI' || $tourCode[3] == 'C' || $tourCode[3] == 'S') && !strpos($content, $cmd_db[$countryCode][$code]['cmd']))
                                            {
                                                if ($tourCode[4] != 'L')
                                                    $tudb[$team_str][$tourCode] = 0; // 0 - неучастие

                                            }
                                            else if (is_file($tour_dir.'/closed'))
                                            {
                                                $team_str1 = ($countryCode == 'SFP') ? $cmd_db[$countryCode][$code]['cmd'] : $team_str;
                                                $content = "\n".file_get_contents($tour_dir.'/mail');
                                                if (strpos($content, "\n".substr($team_str1, 0, $cut).';') === false)
                                                {
                                                    if (is_file($tour_dir.'/adds'))
                                                    {
                                                        $content = "\n".file_get_contents($tour_dir.'/adds');
                                                        if (strpos($content, "\n".$cmd_db[$countryCode][$code]['cmd'].' ') === false)
                                                            $tudb[$team_str][$tourCode] = 1; // 1 - прогноза нет и больше не принимаются
                                                        else
                                                            $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в дополнениях
                                                    }
                                                    else
                                                        $tudb[$team_str][$tourCode] = 1; // 1 - прогноза нет и больше не принимаются

                                                }
                                                else
                                                    $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в почте

                                            }
                                            else
                                            {
                                                if ($countryCode == 'SFP')
                                                    $team_str1 = $cmd_db[$countryCode][$code]['cmd'];
                                                else
                                                    $team_str1 = $team_str;

                                                $content = is_file($tour_dir.'/mail') ? "\n".file_get_contents($tour_dir.'/mail') : '';
                                                if ((strpos($content, "\n".substr($team_str, 0, $cut).';') === false) && (strpos($content, "\n".$team_str1.';') === false))
                                                {
                                                    if (is_file($tour_dir.'/adds'))
                                                        $content = "\n".file_get_contents($tour_dir.'/adds');
                                                    else
                                                        $content = '';

                                                    if (strpos($content, "\n".$cmd_db[$countryCode][$code]['cmd'].' ') === false)
                                                    {
                                                        if (($action == 'remind' && $timeStamp <= $currentTime + 86400) || is_file($tour_dir.'/published'))
                                                            $tudb[$team_str][$tourCode] = 2; // 2 - прогноза нет и уже горит
                                                        else
                                                            $tudb[$team_str][$tourCode] = 3; // 3 - прогноза нет

                                                    }
                                                    else if (is_file($tour_dir.'/published'))
                                                        $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в дополнениях
                                                    else
                                                        $tudb[$team_str][$tourCode] = 5; // 5 - прогноз найден в дополнениях

                                                }
                                                else if (is_file($tour_dir.'/published'))
                                                    $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в почте
                                                else
                                                    $tudb[$team_str][$tourCode] = 5; // 5 - прогноз найден в почте

                                            }
                                        }
                                        else if ($tudb[$team_str][$tourCode] == 3)
                                            if (($action == 'remind' && $timeStamp <= $currentTime + 86400) || is_file($tour_dir.'/published'))
                                                $tudb[$team_str][$tourCode] = 2; // 2 - прогноза нет и уже горит

                                if ($currentTime < $timeStamp)
                                    $nextEvent = min($timeStamp, $nextEvent); // ближайшее cледующее событие

                            }
                    }
            }
    }
//if ($_SESSION['Coach_name'] == 'Александр Сесса') echo var_export($tudb, false)."<br>\n";
    if ($tout)
        $out .= '
                            <li class="nav-item">' . $tout .'
                            </li>';


    $prev_fp = '';
    foreach (['SFP', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'UKR', 'SUI', 'UEFA'] as $countryCode)
    {
        $currentSeason = current_season($startYear, $startMonth, $countryCode);

//if (in_array($tourCode, ['SUI09']))
//  $currentSeason = '2021-1';

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

//if (in_array($tcode, ['SUI09']))
//  $currentSeason = '2021-1';
//else 
if ($countryCode == 'SUI')
    $currentSeason = '2021-3';

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
    else session_unset();
}

if (isset($_SESSION['Coach_name']))
{
    $apikey = rtrim(base64_encode(openssl_encrypt(json_encode(['cmd' => 'send_by_api', 'email' => $_SESSION['Coach_mail']]), 'AES-256-CBC', $key, 0, $iv )), '=');
    if (strtotime('7/15') < time() && time() <= strtotime('8/28') && !is_file($data_dir.'personal/'.$coach_name.'/'.date('Y')))
    {
        $a = 'fifa';
        $m = 'confirm'; // кампания сбора подтверждений с 15 июля по 28 августа
    }
    $role = acl($_SESSION['Coach_name']);
    if ($have_redis)
        $redis = new Redis();
    else
    {
        include('comments/redis-emu.php');
        $redis = new Redis_emu();
    }
    $is_redis = $redis->connect($redis_host, $redis_port);
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
else if (!in_array($m, ['main', 'news', 'pres', 'text', 'cal', 'gen', 'set']))
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
else if ($m == 'cal' || $m == 'gen')
{
    $content = file_get_contents($season_dir . $m);
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


# построение левого меню

$sidecmd = $role == 'president' ? ' <a href="/?m=champions&t=mkpgm"><button type="button" class="add_event small bg-danger rounded-circle text-white"  title="ДОБАВИТЬ ТУР">+</button></a>' : '';
$sidebar = '
          <select class="form-select px-4 py-3 w-100" name="season" aria-label="Season select" onchange="window.location.href=\'?a='.$a.'&amp;s=\'+this.value">';
if (!isset($s))
    $s = $cur_year;

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
          . ($a == 'fifa' ? sidebar_item('news', 'Новости') : '')
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
          . ($a != 'fifa' && is_file($season_dir.'codes.tsv') ? sidebar_item('player', 'Игроки') : '')
          . ($a == 'switzerland' ? sidebar_item('register', 'Выбор команды') : '')
          . sidebar_item($a == 'fifa' ? 'reglament' : 'news', 'Регламент')
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
    $tp = !$league && in_array($tour[0], ['c', 'g', 'p', 's']) ? $tour[0] : '';
    $prefix = '<div class="col-4">
                        <a class="text-decoration-none" href="?a='.$a.'&amp;s='.$s.($league ? '&amp;l='.$league : '').'&amp;t='.$tp.$tt;
    return '
                  <li>
                    <div class="row">
                      '.$prefix.'&amp;m=text&amp;ref=p">тур<span>'.($tt < 10 ? '&nbsp;' : '').$tn.':</span></a>
                      </div>'
                      . $prefix.'&amp;m='
                      . (
                        is_file($season_dir.'publish/'.$league.'/it'.$tour)
                        ? 'text&amp;ref=it">итоги,</a>
                      </div>
                      '.$prefix.'&amp;m='.(strpos($season_dir, 'UNL') ? 'stat&l='.$l.'">стат.' : 'text&amp;ref=r">обзор').'</a>'
                        : 'prognoz">прогнозы</a>
                      </div>
                      <div class="col-4">'
                        ) . '
                      </div>
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
            if (sizeof($ttours))
            {
                rsort($ttours, SORT_NUMERIC);
                $sidebar .= tournament_header($league, $tour_type, $leagues[$league]);
                foreach ($ttours as $to)
                    $sidebar .= tour_line($season_dir, 'c'.$to, $to, $league);

                $sidebar .= "\n                </ul>\n              </div>\n            </div>";
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
            $sidebar .= tournament_header($code[2], $tour_type, $tnames[$code]);
            for ($i = $ttours[$code][1]; $i >= $ttours[$code][0]; $i--)
            {
                $tp = $i + 1 - $ttours[$code][0];
                $tt = ($i <= 9 ? '0' : '' ) . $i;
                if (in_array('UNL'.$tt, $dir))
                    $sidebar .= tour_line($season_dir, $tt, $tp, $code[1]);

            }
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

            if (is_file($season_dir.'codes.tsv'))
                    $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$a.'&amp;s='.$s.'&amp;m=player&amp;l='.$code[1].'">Участники</a>
                    </div>
                  </li>';

            $sidebar .= '
                  <li>
                    <div class="row">
                      <a class="text-decoration-none text-start" href="?a='.$aa.'&amp;m=coach_'.$code.'">Тренерская</a>
                    </div>
                  </li>
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
            if (sizeof($ttours))
            {
                rsort($ttours, SORT_NUMERIC);
                $sidebar .= tournament_header($tindex, $tour_type, $tindex == 'g' && sizeof($ttours) > 1 ? 'Золотой турнир' : $tnames[$tindex]);
                foreach ($ttours as $to)
                    $sidebar .= tour_line($season_dir, ($tindex != 'r' ? $tindex : '').$to, $to);

                $sidebar .= "\n                </ul>\n              </div>\n            </div>";
            }

    }
}

$left = ['Выбор сезона и тура' . $sidecmd, $sidebar];

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

    echo '
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta property="og:image" content="https://fprognoz.org/images/sfp.jpg">
<link rel="icon" href="favicon.ico">
<title>FPrognoz.Org: ' . $fa[$a] . '</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/css/comments.css?ver=1" rel="stylesheet">
<link href="/js/croppic/croppic.css" rel="stylesheet">
<!--[if lt IE 9]><script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js" integrity="sha256-3Jy/GbSLrg0o9y5Z5n1uw0qxZECH7C6OQpVBgNFYa0g=" crossorigin="anonymous"></script><![endif]-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.3/js/solid.js" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.15.3/js/fontawesome.js" crossorigin="anonymous"></script>
<!-- CKEditor for Comments System -->
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/inline/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/inline/translations/ru.js"></script>
<script src="/js/jquery-ui/jquery-ui.min.js"></script>
<script src="/js/jquery-ui/jquery.ui.touch-punch.min.js"></script>
<script src="/js/croppic/croppic-3.0.min.js"></script>
<script src="/comments/comments.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>    <!-- Styles for Comment System -->
<script src="https://cdn.jsdelivr.net/npm/jstimezonedetect@1.0.7/dist/jstz.min.js" integrity="sha256-bt5sKtbHgPbh+pz59UcZPl0t3VrNmD8NUbPu8lF4Ilc=" crossorigin="anonymous"></script>
<style>
.monospace {
	white-space: pre-wrap;
	font-family: monospace;
	font-size: 1.1em;
	line-height: 1;
}
</style>
</head>

<body class="bg-light" style="padding-top:0;padding-bottom:0;font-family: Roboto,Arial,Helvetica,sans-serif;">
  <div class="fixed-top">
    <nav class="navbar navbar-expand-md navbar-dark" style="padding:0 5px; background: #1e2a64; background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: top left;background-size: 100% 40px;">
      <div class="container-fluid">
        <div class="dropdown">
          <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: left; width: 186px">
            <img src="/images/63x42/'.$a.'.png" alt="'.$fa[$a].'" title="'.$fa[$a].'" height="26">
            <span class="ps-2">'.($a == 'fifa' ? 'Ассоциация' : $fa[$a]).'</span>
          </button>
          <ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton1">';
foreach ($fa as $e => $r)
    echo '
            <li><a class="dropdown-item text-warning" href="?a='.$e.'"><img src="/images/63x42/'.$e.'.png" alt="'.$r.'" title="'.$r.'" height="28"><span class="ms-3">'.$r.'</span></a></li>';
echo '
          </ul>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarToggler">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 pe-0 w-100">'
            . navbar_item('hof', 'Зал Славы')
            . navbar_item('', 'Информация')
            . navbar_item('hq', 'Президиум')
            . '
          </ul>
        </div>
        <!--div class="nav-item text-light pt-2 me-2">
          <a class="fas fa-envelope-open h5" title="Внутрення почта"></a>
        </div-->
          <div class="dropdown">
              <button class="btn btn-dark dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="' . (isset($_SESSION['ms_uname']) || isset($_GET['m']) || isset($_POST['register']) ? 'false' : 'true') . '" style="background-image: url(/images/head1.jpg); background-repeat: repeat-x; background-position: left;width: 200px;">
                <i class="fas fa-user"></i> '
                . (isset($_SESSION['Coach_name']) ? $_SESSION['Coach_name'] : 'Вход') . '
              </button>
              <ul class="dropdown-menu bg-light" aria-labelledby="navbarDropdown" style="width: 300px; left: -84px">';
if (isset($_SESSION['Coach_name']) && $role != 'badlogin')
{
    echo '
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
                <form id="login_form" class="p-2 text-center" method="POST" data-tpl="'.$tcfg.'">
                  E-mail или код команды:
                  <input type="text" class="form-control" data-tpl="'.$ncfg.'" id="name_str" name="name_str">
                  Пароль:
                  <input type="password" class="form-control" data-tpl="'.$pcfg.'" id="pass_str" name="pass_str">
                  <p id="valid_name">
                    <button type="submit" class="btn btn-success shadow-sm m-2" id="login" name="login">Вход</button>
                  </p>
                </form>';
}
echo '
              </ul>
          </div>
      </div>
    </nav>
    <div class="d-flex" style="padding:0 5px; background: #1e2a64;">
      <div class="py-2 text-gold" id="notice">' . $notification . '</div>
      <div><a class="nav-link text-warning" href="?m=konkurs">Предсезонный конкурс</a></div>
      <!--ul class="navbar-nav me-auto">
        <li class="nav-item active pe-2"><div class="py-2 text-gold" id="notice">' . $notification . '</div></li>
        <li class="nav-item active pe-2"><a class="nav-link" href="/" target="_blank">ENG01</a></li>
      </ul-->
    </div>
  </div>

    <div style="height:80px"></div>
    <!--nav class="navbar navbar-expand-md navbar-dark mt-5" style="padding:0 5px; background: #1e2a64;">
    </nav-->

    <main class="min-vh-100" id="main">
      <div class="me-0 ' . (isset($rank_table) ? 'p-1' : 'row') . '">';
   if (!isset($rank_table))
       echo '
        <div class="col-md-2">
          <div class="m-2 text-dark"><h6>' . $left[0] . '</h6></div>
          <ul class="list-unstyled text-center">
' . $left[1] . '
          </ul>
        </div>
        <div class="col-md-6">';
    echo '
          <a id="~"></a>
          <div class="m-2 text-dark"><h6>' . $main[0] . '</h6></div>
          <div id="middle">
';
    $nohl = ['club', 'main', 'mkpgm', 'news', 'set'];
    include ('fifa/register.inc.php'); // регистрация в сборных ассоциаций
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

    echo '
          </div>
        </div>
      </div>';

    echo '
        <div class="col-md-4">';
    echo '
        <a id="comments"></a>
        <div class="m-2"><h6>Фан-зона</h6></div>
          <div class="bg-white shadow rounded-3 p-1" id="comments_wrapper" data-name="'.($_SESSION['ms_email'] ?? '').'" data-hash="'.$hash.'">';
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
