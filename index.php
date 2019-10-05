<?php
/*
- бомбардиры
- в draw создавать файлы календаря и генераторов кубка
- двойное редатирование (текст + html), где это возможно, и формирование сообщений с обеими частями
- вставка вырезанной цитаты в комментариях
- отправка токена с учетом смены пароля (закомментировано)
- смена пароля по токену или сразу после входа
- забыл пароль
- fp.cfg для старых сезонов, конвертирование в utf-8
- история SFP
- инструкция
- организатору
- конкурсы
*/
$time_start = microtime(true);
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(hash('sha256', 'iv'.$salt), 0, 16);
$key = hash('sha256', 'pass1'.$salt);

$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

// форматированный вывод времени в указанной tz; если длина time = 10 - это ts
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

function acl($name, $a='') {
  global $admin;
  if ($a)
    include ('$a/settings.inc.php');
  else {
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

function mb_sprintf($format) {
  $argv = func_get_args();
  array_shift($argv);
  return mb_vsprintf($format, $argv);
}

function mb_vsprintf($format, $argv, $encoding=null) {
  if (is_null($encoding)) $encoding = mb_internal_encoding();
  // Use UTF-8 in the format so we can use the u flag in preg_split
  $format = mb_convert_encoding($format, 'UTF-8', $encoding);
  $newformat = ""; // build a new format in UTF-8
  $newargv = array(); // unhandled args in unchanged encoding
  while ($format !== "") {
    // Split the format in two parts: $pre and $post by the first %-directive
    // We get also the matched groups
    list ($pre, $sign, $filler, $align, $size, $precision, $type, $post) =
        preg_split("!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
        $format, 2, PREG_SPLIT_DELIM_CAPTURE);
    $newformat .= mb_convert_encoding($pre, $encoding, 'UTF-8');
    if ($type == '%') $newformat .= '%%'; // an escaped %
    else if ($type == 's') {
      $arg = array_shift($argv);
      $arg = mb_convert_encoding($arg, 'UTF-8', $encoding);
      $padding_pre = $padding_post = '';
      if ($precision !== '') { // truncate $arg
        $precision = intval(substr($precision,1));
        if ($precision > 0 && mb_strlen($arg,$encoding) > $precision)
          $arg = mb_substr($precision,0,$precision,$encoding);
      }
      if ($size > 0) { // define padding
        $arglen = mb_strlen($arg, $encoding);
        if ($arglen < $size) {
          if ($filler == '') $filler = ' ';
          else if ($filler[0] == "'") $filler = substr($filler, 1);
          ($align == '-') ? $padding_post = str_repeat($filler, $size - $arglen)
                          : $padding_pre = str_repeat($filler, $size - $arglen);
        }
      }
      // escape % and pass it forward
      $newformat .= $padding_pre . str_replace('%', '%%', $arg) . $padding_post;
    }
    else if ($type != '') { // another type, pass forward
      $newformat .= "%$sign$filler$align$size$precision$type";
      $newargv[] = array_shift($argv);
    }
    $format = strval($post);
  }
  // Convert new format back from UTF-8 to the original encoding
  $newformat = mb_convert_encoding($newformat, $encoding, 'UTF-8');
  return vsprintf($newformat, $newargv);
}

function current_season($y, $m, $cc) {
  if ($cc == 'SUI')
    return '2019-3';
//  else if ($cc == 'RUS' || $cc == 'FRA')
//    return '2018-19';
  else
    if ($m < 7) $y--;

  return $y . '-' . (substr($y, 2) + 1);
}

function build_personal_nav() {
  global $ccn;
  global $cmd_db;
  global $data_dir;
  global $online_dir;
  $debug_str = '';
  $currentTime = time();
  if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc')
  || !isset($_SESSION['Next_Event'])
  || $_SESSION['Next_Event'] <= $currentTime) {
    $statusColor = array('0' => 'noplay', '1' => 'toolate', '2' => 'alarm', '3' => 'absent', '4' => 'playing', '5' => 'present', '6' => 'result');
    $tudb = array();
    $out = '';
    $nextEvent = $currentTime + 300;
    $startTime = $currentTime - 259200; // - 3 day
//    $startTime = $currentTime - 518400; // - 6 day
    $startDay = date('d', $startTime);
    $startMonth = date('m', $startTime);
    $startYear = date('Y', $startTime);
    $sched[0] = "$startYear/$startMonth";
    $sched[1] = ($startMonth == 12) ? ($startYear + 1)."/01" : sprintf("%4d/%02d", $startYear, $startMonth + 1);
    $world = file_get_contents($online_dir . 'UNL/'.$startYear.'/codes.tsv');
    $final = file($online_dir . 'UNL/'.$startYear.'/final', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//    $sfp20 = file_get_contents($online_dir . 'IST/'.$startYear.'/codes.tsv');
    $tout = '';
    for ($nm=0; $nm <= 1; $nm++) {
      $dir = scandir($online_dir . 'schedule/'.$sched[$nm]);
      foreach ($dir as $fname) if ($fname[0] != '.' && ($nm || $fname >= $startDay)) {
        $subdir = scandir($online_dir . 'schedule/'.$sched[$nm].'/'.$fname);
        foreach ($subdir as $event) if ($event[0] != '.' && !strpos($event, '.resend')) {
          list($timeStamp, $countryCode, $tourCode, $action) = explode('.', $event);
          $currentSeason = current_season($startYear, $startMonth, $countryCode);


//if ($tourCode == 'SUI08' || $tourCode == 'SUI09')
//  $currentSeason = '2018-1';


// World
//          if ($countryCode == 'UNL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false) {
          if ($countryCode == 'UNL' && $action == 'remind') { // танцуют ВСЕ!!!
            $tour_dir = $online_dir . 'UNL/'.$startYear.'/prognoz/'.$tourCode;
            if (is_file($tour_dir.'/published'))
              $status = 6; // завершён
            else if (is_file($tour_dir.'/closed'))
              $status = 4; // играется
            else if ($tourCode > 'UNL11' && !in_array($_SESSION['Coach_name'], $final))
              $status = 0; // не участвует
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
          elseif ($tourCode[4] == 'L')
            $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/'.substr($tourCode, 0, 5).'/itc'.substr($tourCode, -2);
          else
            $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/it'.strtolower(substr($tourCode, 3));

          $uefaflag = 0;
          $tour_dir = $online_dir.$countryCode.'/'.$currentSeason.'/prognoz/'.$tourCode;
          if ($countryCode != 'UNL') //&& $countryCode != 'IST')
          foreach ($cmd_db[$countryCode] as $code => $team)
          if ($team['usr'] == $_SESSION['Coach_name']) {
            $team_str = $code.'@'.$countryCode;
            $cut = strlen($code); ///// для совместимости. ПЕРЕДЕЛАТЬ!

            if (is_file($itFName)) {
              if ($uefaflag == 0)
                $tudb[$team_str][$tourCode] = 6; // 6 - опубликованы итоги

              if ($tourCode[4] == 'L')
                $uefaflag = 1;

            }
            // ФП совпала, итогов еще нет, надо проверить, есть ли команда в программке тура
            elseif (!isset($tudb[$team_str][$tourCode])) { // первое упоминание тура
              $content = file_get_contents($online_dir.$countryCode.'/'.$currentSeason.'/programs/'.$tourCode);
              $content = substr($content, strpos($content, 'Контрольный с'));
              if ($countryCode != 'SUI' && !strpos($content, $cmd_db[$countryCode][$code]['cmd'])) {
                if ($tourCode[4] != 'L')
                  $tudb[$team_str][$tourCode] = 0; // 0 - неучастие

              }
              elseif (is_file($tour_dir.'/closed')) {
                $team_str1 = ($countryCode == 'SFP') ? $cmd_db[$countryCode][$code]['cmd'] : $team_str;
                $content = "\n".file_get_contents($tour_dir.'/mail');
                if (strpos($content, "\n".substr($team_str1, 0, $cut).';') === false) {
                  if (is_file($tour_dir.'/adds')) {
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
                if ((strpos($content, "\n".substr($team_str, 0, $cut).';') === false)
                && (strpos($content, "\n".$team_str1.';') === false)) {
                  if (is_file($tour_dir.'/adds'))
                    $content = "\n".file_get_contents($tour_dir.'/adds');
                  else
                    $content = '';

                  if (strpos($content, "\n".$cmd_db[$countryCode][$code]['cmd'].' ') === false) {
                    if (($action == 'remind' && $timeStamp <= $currentTime + 86400)
                    || is_file($tour_dir.'/published'))
                      $tudb[$team_str][$tourCode] = 2; // 2 - прогноза нет и уже горит
                    else
                      $tudb[$team_str][$tourCode] = 3; // 3 - прогноза нет

                  }
                  elseif (is_file($tour_dir.'/published'))
                    $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в дополнениях
                  else
                    $tudb[$team_str][$tourCode] = 5; // 5 - прогноз найден в дополнениях

                }
                elseif (is_file($tour_dir.'/published'))
                  $tudb[$team_str][$tourCode] = 4; // 4 - прогноз найден в почте
                else
                  $tudb[$team_str][$tourCode] = 5; // 5 - прогноз найден в почте

              }
            }
            elseif ($tudb[$team_str][$tourCode] == 3)
              if (($action == 'remind' && $timeStamp <= $currentTime + 86400)
              || is_file($tour_dir.'/published'))
                $tudb[$team_str][$tourCode] = 2; // 2 - прогноза нет и уже горит

            if ($currentTime < $timeStamp)
              $nextEvent = min($timeStamp, $nextEvent); // ближайшее cледующее событие

          }
        }
      }
    }
//if ($_SESSION['Coach_name'] == 'Александр Сесса') echo var_export($tudb, false)."<br />\n";
    if ($tout)
      $out .= '
                            <li class="nav-item">' . $tout .'
                            </li>';


    $prev_fp = '';
    foreach (['SFP', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'UKR', 'SUI', 'UEFA'] as $countryCode) {
      $currentSeason = current_season($startYear, $startMonth, $countryCode);


//if ($tourCode == 'SUI08' || $tourCode == 'SUI09')
//  $currentSeason = '2018-1';


      $tout = '';
      foreach ($cmd_db[$countryCode] as $c => $team) if ($team['usr'] == $_SESSION['Coach_name']) {
        $team_str = $c.'@'.$countryCode;
        $ll = ($countryCode == 'SFP') ? 'Сборная' : $team['cmd'];
        $prev_fp = $countryCode;
        if (isset($tudb[$team_str])) foreach ($tudb[$team_str] as $tcode => $status) {
          if (strlen($tcode) > 3 && $tcode[4] == 'L') {
            $cclen = 5;
            $ll = '&l='.substr($tcode, 0, $cclen);
          }
          elseif ($countryCode == 'SFP') {
            $cclen = 3;
            $ll = substr($tcode, 0, $cclen);
            if ($ll == 'PRO' || $ll == 'PRE' || $ll == 'FFP' || $ll == 'FFL' || $ll == 'TOR' || $ll == 'SPR' || $ll == 'SUP' || $ll == 'FWD' )
              $ll = '&l='.$ll;
            else
              $ll = '&';

          }
          else {
            $cclen = 3;
            $ll = '';
          }
          if ($status != 6)
            $linktext = 'prognoz';
          else
            $linktext = 'text&ref=it';



//if ($tcode == 'SUI08' || $tcode == 'SUI09')
//  $currentSeason = '2018-1';
//else if ($tcode == 'SUI01' || $tcode == 'SUI02' || $tcode == 'SUI02')
//  $currentSeason = '2018-2';


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

// специфика хостинга на AWS - на других серверах не использовать
function send_email($from, $name, $email, $subj, $body) {
  $params = ['token' => 'FPrognoz.Org', 'from' => $from, 'name' => $name, 'email' => $email, 'subj' => $subj, 'body' => $body];
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
      'content' => http_build_query($params),
    ),
  ));
  return file_get_contents('http://forum.fprognoz.org/mail-proxy.php', false, $context);
}

function lock($lock, $timer) {
  while ($timer-- && is_file($lock)) time_nanosleep(0, 1000);
  if ($timer) touch($lock);
  return ($timer);
}

function send_predict($country_code, $season, $team_code, $tour, $prognoz, $enemy_str, $ip) {
  global $ccn;
  global $data_dir;
  $time = time();
  $email = '';
  $cca_home = $data_dir . 'online/' . $country_code . '/';
  $acodes = file($cca_home . $season .'/codes.tsv');
  foreach ($acodes as $scode) if ($scode[0] != '#') {
    $ateams = explode('	', $scode);
    if (trim($ateams[0]) == $team_code) {
      $name = trim($ateams[2]);
      $email = trim($ateams[3]);
    }
  }
  $replyto = $email ? "\nReply-To: $email" : '';
  $mlist = $email;
  if (is_file($cca_home . 'emails')) {
    $atemp = file($cca_home . 'emails');
    foreach ($atemp as $line) {
      list($pmail, $pcode) = explode(':', trim($line));
      if ($pcode != $enemy_str) $mlist .= ($mlist ? ', ' : '') . $pmail;
    }
  }
  $subj = strtoupper($ccn[$country_code]);
  $body = "FP_Prognoz\n$team_code\n$tour\n$prognoz\n";

  // write direct
  if (strpos($prognoz, '  ')) list($prognoz, $pena) = explode('  ', $prognoz);
  isset($pena) ? $pena = strtoupper(trim($pena)) : $pena = '';
  $tour_dir = $cca_home . $season . '/prognoz/' . $tour;
  if (!is_dir($tour_dir)) mkdir($tour_dir, 0755, true);
  if (is_file($tour_dir . '/mail')) {
    $lock = $tour_dir.'/lock';
    if (lock($lock, 5000)) {
      $content = file_get_contents($tour_dir . '/mail');
      file_put_contents($tour_dir . '/mail', $content . "$team_code;$prognoz;$time;$pena\n");
      unlink($lock);
      if (is_file($data_dir . 'personal/' . $name . '/navbar.inc'))
        unlink($data_dir . 'personal/' . $name . '/navbar.inc');

    }
    else
      echo 'В течение минуты прогноз должен появиться в списке полученных.<br />
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

function build_access() {
  global $ccn;
  global $data_dir;
  global $online_dir;
  $access = '';
  foreach ($ccn as $ccc => $cname) if ($ccc != 'SBN' && $ccc != 'FCL' && $ccc != 'WL' && $ccc != 'IST' && $ccc != 'FIFA') {
    $dir = scandir($online_dir.$ccc, 1);
    foreach ($dir as $s)
      if ($s[0] == '2')
        break;

    $codes = file($online_dir . $ccc . '/' . $s . '/codes.tsv');
    foreach ($codes as $line) if (trim($line)) {
      list($code, $cmd, $name, $email) = explode("\t", $line);
      $email = trim($email);
      $code = trim($code, '- ');
      if ($ccc == 'UNL')
        $name = $code; // здесь имена могут дублироваться, поэтому только код!

      if ($code[0] != '#' && $name && $email && (!strpos($code, '@') || $cc = 'SFP')) {
        if (is_file($online_dir . $ccc . '/passwd/' . $code)) {
          list($hash, $role) = explode(':', file_get_contents($online_dir . $ccc . '/passwd/' . $code));
          $role = trim($role);
        }
        else {
          $hash = '';
          $role = '';
        }
        $access .= "$code;$ccc;$cmd;$name;$email;$hash;$role;\n";
        if (!is_dir($data_dir . 'personal/' . $name))
          mkdir($data_dir . 'personal/' . $name, 0755);

      }
    }
  }
  file_put_contents($data_dir . 'auth/.access', $access);
}

function script_from_cache($file) {
//  global $redis;
//  global $is_redis;
//  if ($is_redis && ($link = $redis->get('link:'.$file)))
//    $file = 'data://text/plain;base64,'.$redis->get('file:'.$link);

  return $file;
}

function get_results($lastdate) {
  global $online_dir;
  list($day, $month) = explode('.', $lastdate);
  $date = sprintf('%02d-%02d', trim($month), trim($day));
  $year = date('Y', time());
//  $month = date('m', time());
//  (trim($atemp[1]) > ($month + 1) && $lastdate != '31.12') ? $fyear = $year - 1 : $fyear = $year;
//  ($month > 7) ? $fyear = $year - 1 : $fyear = $year;
  $fyear = $year;
  $base = array();
  $week = date('W', strtotime($fyear.'-'.$date));
  if ($date == '01-02') $fyear = $year - 1;
  $fname = $fyear.'.'.$week;
  is_file($online_dir . 'results/'.$fname) ? $archive = file($online_dir . 'results/'.$fname) : $archive = array();
  if (++$week == '54') {
    $week = '01';
    $fyear++;
  }
  else if (strlen($week) == 1) $week = '0'.$week;
  $fname = $fyear.'.'.$week;
  if (is_file($online_dir . 'results/'.$fname)) $archive = array_merge($archive, file($online_dir . 'results/'.$fname));
  foreach ($archive as $line) {
    $data = explode(',', trim($line));
    $match = $data[0].' - '.$data[1];
    $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
    $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
  }
  return $base;
}

function bz_matches($json) {
  global $online_dir;
  $leagues = array(
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
);
//'CAF: African Nations Championship',

  $res_path = $online_dir . 'results/';
  $lock = $online_dir . 'log/results.lock';
  include ('online/realteam.inc.php');
//file_put_contents('log', $json);
  $matches = json_decode($json, true); //  $matches = json_decode(stripslashes($json), true);
  $update = false;
  $year = date('Y');
  $m = date('m');
  $d = date('d');
  $week = date('W', time() - 86400);
  $fname = ($week == '52' && $m == '01') ? $res_path.($year - 1).'.'.$week : $res_path.$year.'.'.$week;
  if (lock($lock, 10000)) { // lock
    $archive = (is_file($fname)) ? file($fname) : array();
    $base = array();
    $seq = trim($archive[0]);
    unset($archive[0]); // remove seq
    foreach ($archive as $line) {
      list($h,$a,$d,$s,$f,$r,$g,$i,$z) = explode(',', trim($line));
      $base[$h.' - '.$a.' '.substr($d, 0, 5)] = array($h,$a,$d,$s,$f,$r,$g,$i,$z);
    }
    if (count($matches)) foreach ($matches as $match) {
      extract($match);
      $league = $uadi.': '.$ladi;
      if (in_array($league, $leagues)) {
        if (isset($realteam[$t1])) $t1 = $realteam[$t1];
        if (isset($realteam[$t2])) $t2 = $realteam[$t2];
        list($dd, $mm, $yy) = explode('/', $tar3);
        $time = strtotime($yy . '-' . $mm . '-' . $dd . 'T' . $tar2 . '+0:00');
        if (isset($base[$t1.' - '.$t2.' '.date('m-d', $time)])) {
          if ($base[$t1.' - '.$t2.' '.date('m-d', $time)][8] != $id) {
            $base[$t1.' - '.$t2.' '.date('m-d', $time)][8] = $id;
            $update = true;
          }
        }
      }
    }
    if ($update) {
      $out = $seq . "\n";
      foreach ($base as $id => $data)
        $out .= $data[0].','.$data[1].','.$data[2].','.$data[3]. ','.$data[4].','.$data[5].','.$data[6].','.$data[7].','.$data[8]."\n";

      file_put_contents($fname, $out);
    }
    unlink($lock);
  }
}

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
  if ($updates) foreach ($updates as $update)
    foreach ($archive as $n => $line)
      if (strpos($line, trim($update['idx'], '"'))) {
        $arr = explode(',', trim($line));
        if ($arr[3] != 'FT') {
          $arr[3] = 'FT';
          $arr[4] = $arr[5] = $update['evs'].':'.$update['deps'];
          $archive[$n] = str_putcsv($arr);
          $save = true;
        }
      }

  if ($save) {
    $out = '';
    foreach ($archive as $line)
      $out .= trim($line) . "\n";

    if (lock($online_dir . 'log/results.lock', 10000)) {
      file_put_contents($fname, $out);
      unlink($online_dir . 'log/results.lock');
    }
  }
  else
    $archive[0] = 0; // seq не имеет значения для неактуального архива; используем его для обнуления $updates

  return $archive;
}

function get_results_by_date($month, $day, $update = NULL) {
  global $online_dir;
  $updates = json_decode(stripslashes($update), true);
  $date = sprintf('%02d-%02d', trim($month), trim($day));
  $year = date('Y');
//  if ($month > 7) $year--;
  $base = array();
  $week = date('W', strtotime($year.'-'.$date));
  if ($date == '01-02') $year--;
  $fname = $year.'.'.$week;
  $seq = 0;
  if (is_file($online_dir . 'results/'.$fname)) {
//    $archive = file($online_dir . 'results/'.$fname);
    $archive = load_archive($online_dir . 'results/'.$fname, $updates);
    $seq = trim($archive[0]);
    if ($seq)
      $updates = []; // уже проапдейтили, обнуляем

    unset($archive[0]);
  }
  else $archive = array();
  if (++$week == '54') {
    $week = '01';
    $year++;
  }
  else if (strlen($week) == 1) $week = '0'.$week;
  $fname = $year.'.'.$week;
  if (is_file($online_dir . 'results/'.$fname)) {
//    $archive2 = file($online_dir . 'results/'.$fname);
    $archive2 = load_archive($online_dir . 'results/'.$fname, $updates);
    $seq = max($seq, trim($archive2[0]));
    unset($archive2[0]);
    $archive = array_merge($archive, $archive2);
  }
  $base['seq'] = $seq;
  foreach ($archive as $line) {
    $data = explode(',', trim($line));
    $match = $data[0].' - '.$data[1];
    if (!isset($data[8])) $data[8] = '';
if (!isset($base[$match]))
    $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8],$data[6]);
    $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8],$data[6]);
  }
  return $base;
}

function parse_cal_and_gen($program) {
  $acal = array();
  $agen = array();
  $calfp = explode("\n", $program);
  unset($calfp[1], $calfp[0]);
  foreach ($calfp as $line) if ((strpos($line, ' - ') || strpos($line, ' *'))
                              && !mb_strpos($line, 'ГОСТИ') && !mb_strpos($line, 'Гости')) {
    if (strpos($line, '*')) { // parse line with generator
      if ($fr = strpos($line, ' - ')) { // match and generator
        $cut = strpos($line, '  ', $fr);
        if ($cut) {
          $acal[0][] = trim(substr($line, 0, $cut));
          $agen[] = trim(substr($line, $cut));
        }
      }
      else { // generators only
        $agen[] = trim($line);
/*
        $n = 0;
        while ($cut = strpos($line, ' *')) {
          $agen[$n++][] = trim(substr($line, 0, $cut + 2));
          $line = substr($line, $cut + 2);
        }
*/
      }
    }
    else { // line w/o generator
      $n = 0;
      $line .= '  ';
      while ($fr = strpos($line, ' - ')) {
        $cut = strpos($line, '  ', $fr);
        if ($cut) {
          $acal[$n++][] = trim(substr($line, 0, $cut));
          $line = substr($line, $cut);
        }
        else break;
      }
    }
  }
  $cal = $gen = '';
  foreach ($acal as $a) foreach ($a as $l) $cal .= "$l\n";
  foreach ($agen as $l) $gen .= "$l\n";
//  foreach ($agen as $a) foreach ($a as $l) $gen .= "$l\n";
  return [$cal, $gen];
}

function season_config($file) {
  $config = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $cal = $config[2]; // если не будет переобъявлен в этапах
  $season_config = json_decode($config[4], true);
  $season_config[0]['lang'] = isset($season_config[0]['турнир']) || !isset($season_config[0]['type']) ? 'ru' : 'en';
  foreach($season_config as $n => $tournament)
    if ($season_config[0]['lang'] == 'en') {
      if (!isset($tournament['type']))
        if ($cal == 'calc' || isset($tournament['format'][0]['cal']) && $tournament['format'][0]['cal'] == 'calc')
          $season_config[$n]['type'] = 'cup';
        else
          $season_config[$n]['type'] = 'chm'; // по умолчанию - чемпионат

      if (!isset($tournament['format'][0]['cal']))
        $season_config[$n]['format'][0]['cal'] = ($season_config[$n]['type'] == 'cup') ? 'calc' : $cal;

      if (isset($tournament['format'][1]) && !isset($tournament['format'][1]['cal']))
        $season_config[$n]['format'][1]['cal'] = 'calp'; // предполагаем, что это 2-й этап чемпионата: плей-офф

    }
    else {
      // при неоходимости переведём на английский
    }

  return $season_config;
}

$ccn = array(
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
);

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

$classic_fa = ['AUS', 'BEL', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'GRE', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'TUR', 'UKR'];

$role = 'badlogin';
$notification = '';
$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
$auth = isset($_POST['pass_str']) && isset($_POST['name_str']);
$parameters = (isset($_POST['matches']) || isset($_POST['updates']) || isset($_POST['mtscores'])) ? $_POST : $_GET;
foreach ($parameters as $k => $v)
  $$k = $v;

if ($auth && !$_POST['pass_str'] && strpos($_POST['name_str'], '@') && strpos($_POST['name_str'], '.')) {
  $m = 'authentifying';
  $email_ok = false;
}
if (isset($ls))
   setcookie('fprognozls', $ls);

$fprognozls = isset($_COOKIE['fprognozls']) ? $_COOKIE['fprognozls'] : 'inscore';
$editable_class = '';

////////// определяем, что показывать, если нечего показывать

$sidebar_show = false;
if (!isset($a))
  $a = 'fifa';
else if (!is_dir($a)) {
  http_response_code(404);
  $a = 'fifa';
  $m = '404';
}
else if (count($_GET) == 1 || count($_GET) == 2 && isset($_GET['s']))
  $sidebar_show = true; // не сворачивать левое меню при выборе ассоциации и сезона

include ("$a/settings.inc.php");

////////// аутентификация

session_start();
if (isset($token)) { // вход по ссылке
  $data = json_decode(trim(openssl_decrypt( base64_decode($token), 'AES-256-CBC', $key, 0, $iv )), true);
  if ($data['cmd'] == 'auth_token' && $data['ts'] > time())
  {
    $_SESSION['Coach_name'] = $data['name'];
    $_SESSION['Coach_mail'] = $data['mail'];
  }
  else
    $notification = '
ссылка для входа<br />
не действительна';

}
if (isset($_GET['logout'])) {
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
if ($auth || isset($_POST['submitnewpass'])) {
  $hash = md5($_POST['pass_str']);
  $name_str = mb_strtoupper(isset($_POST['name_str']) ? $_POST['name_str'] : $coach_name);
}
foreach ($access as $access_str) {
  list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
  $cmd_db[$as_code][$code] = ['ccn' => $as_code, 'cmd' => $team, 'usr' => $name, 'eml' => $mail, 'rol' => $rol];
  if ($auth || isset($_POST['submitnewpass'])) { // аутентификация или контрольная проверка пароля
    if (($hash == $pwd || $hash == $SuperPWD) &&
       ($name_str == mb_strtoupper($code) || $name_str == mb_strtoupper($name) || $name_str == strtoupper($mail)))
    {
      $passed = true;
      if (isset($_POST['submitnewpass'])) {
        $sendpwd = $mail;     // для отправки сообщения о смене пароля
        $team_codes[$as_code] = $code; // для последующей смены пароля
      }
      if (strlen($coach_name) < strlen($name))
        $coach_name = $name; // выбираем самое длинное имя из указанных

      if ($mail)
        $_SESSION['Coach_mail'] = $mail;

    }
    else if (isset($m) && $m == 'authentifying') {
      if ($name_str == strtoupper($mail)) {
        $email_ok = true;
        break;
      }
    }
    else if ($auth && !$pwd && $_POST['pass_str'] == $code && $_POST['name_str'] == $name) {
      $sendpwd = $mail; // выполнилось условие отправки первого пароля
      $team_codes[$as_code] = $code;
    }
  }
}
if ($auth) {
  if ($passed) {
    $_SESSION['Coach_name'] = $coach_name;
    build_personal_nav();
  }
/*
  else if ($sendpwd && count($team_codes)) {
    $gp = '';
    $mix = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
    for ($i=0; $i<8; $i++)
      $gp .= $mix[rand(0,56)];

    $team_list = '';
    foreach ($team_codes as $ac => $code) {
      $team_list .= $code.', ';
      file_put_contents($online_dir.$ac.'/passwd/'.$code, md5($gp).':player');
    }
    send_email('FPrognoz.org <fp@fprognoz.org>', $_POST['name_str'], $sendpwd, 'ФП. Пароль для сайта ' . $this_site,
'Вы получили случайно-сгенерированный пароль для доступа на сайт ' . $this_site . '

'.$gp.'

Используйте его вместе с именем ' . $coach_name . ',
или с указанным в поле "имя" кодом одной из ваших команд: '.$team_list.'
или же с вашим e-mail адресом ' . $sendpwd . '.

Пароль можно сменить на странице '.$this_site.'/?m=pass
');
    build_access();
    $notification = 'Пароль выслан';
  }
*/
  else if (!$notification)
    $notification = 'Ошибка входа';

}
else { // restored session
  if ($coach_name) {
    build_personal_nav();
    if (!isset($cca))
      $cca = '';

  }
  else session_unset();
}

if (isset($_SESSION['Coach_name'])) {
  $apikey = rtrim(base64_encode(openssl_encrypt( json_encode(['cmd' => 'send_by_api', 'email' => $_SESSION['Coach_mail']]), 'AES-256-CBC', $key, 0, $iv )), '=');
  if (strtotime('7/8') < time() && time() <= strtotime('8/31')
   && !is_file($data_dir.'personal/'.$coach_name.'/'.date('Y'))) {
    $a = 'fifa';
    $m = 'confirm'; // кампания сбора подтверждений с 8 июля по 31 августа
  }
  $role = acl($_SESSION['Coach_name']);
  if ($have_redis)
    $redis = new Redis();
  else {
    include('comments/redis-emu.php');
    $redis = new Redis_emu();
  }
  $is_redis = $redis->connect($redis_host, $redis_port);

  if (is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc')) {
    if (isset($_POST['toggle_gb'])) {
      unlink($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc');
      $gb_status = 'off';
    }
    else $gb_status = 'on';
  }
  else {
    if (isset($_POST['toggle_gb'])) {
      if (!is_dir($data_dir . 'personal/'.$_SESSION['Coach_name']))
        mkdir($data_dir . 'personal/'.$_SESSION['Coach_name'], 0755);

      touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/gb.inc');
      $gb_status = 'on';
    }
    else $gb_status = 'off';
  }
}
else {
  $apikey = '';
  $is_redis = false;
  $gb_status = 'off';
}
if (!isset($m)) { // если не запрошен контент, надо показать хоть что-то:
  if (isset($s))
    $m = 'news';                        // новости сезона
  else {
    $m = 'main';                        // информация об ассоциации
    if ($a == 'fifa')
      if (!isset($_COOKIE['fprognozmain']))
        setcookie('fprognozmain', '1'); // один раз покажем информацию о сайте
      else {
        $s = $cur_year;                 // потом всегда показывать свежие новости
        $m = 'news';
      }

  }
}
else if (!in_array($m, ['main', 'news', 'pres', 'text', 'cal', 'gen', 'set'])) { // проверка на псевдо-скрипты -
  if (!is_file($a . '/' . $m . '.inc.php')) {                                    // им не требуется наличие файла
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
else if ($m == 'main' || $m == 'news') {
  $fn = $online_dir . $cca . '/' . (isset($s) ? $s . '/' : '') . 'news';
  $content = file_get_contents(is_file($fn) ? $fn : $online_dir . $cca . '/news');
}
else if ($m == 'cal' || $m == 'gen') {
  $content = file_get_contents($season_dir . $m);
  $editable_class = ' class="monospace w-100"';
}
else if ($m == 'text') {
  $league = isset($l) ? $l.'/' : '';
  if (isset($t))
    $tt = $cca == 'UEFA' ? 'c'.$t : $t;

  switch ($ref) {
    case 'news': $f = 'news'; break;
    case 'itog': $t = lcfirst($t);
    case 'it'  : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'it.tpl'; break;
    case 'itc' : $f = isset($t) ? 'publish/'.$league.'it'.$tt : 'itc.tpl'; break;
    case 'prog': $t = lcfirst($t);
    case 'p'   : $f = isset($t) ? ($a == 'sfp-team' ? 'programs/'.$l : 'publish/p').$t  : 'p.tpl'; break;
    case 'pc'  : $f = isset($t) ? 'publish/p'.$t  : 'pc.tpl'; break;
    case 'rev' : $t = lcfirst($t);
    case 'r'   : $f = isset($t) ? 'publish/'.$league.'r'.$tt  : 'header'; break;
  }
  $content = is_file($season_dir . $f) ? file_get_contents($season_dir . $f) : 'файл не найден';
  $editable_class = ' class="monospace w-100"';
}
if (isset($content) && trim($content) && !strpos($content, '</p>') && !strpos($content, '<br'))
  $editable_class = ' class="monospace w-100"'; // text, но если всё удалить, должно разрешить ввести html

////////// построение левого меню

$sidebar = '';
if (!isset($s))
  $s = $cur_year;

$season = '';
$ccd = ($a == 'sfp-20') ? 'WL' : $cca;
if (is_dir($online_dir.$ccd))
  $dir = scandir($online_dir.$ccd, 1);

foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1')) {
    $seasons[] = $subdir;
    if (!isset($s))
      $s = $subdir;

  }

if (in_array($cca, $classic_fa)) { // сбор туров сезона для классических асоциаций
  $tournaments = ['R' => [], 'G' => [], 'P' => [], 'C' => [], 'S' => []];
  $tnames = ['R' => 'Чемпионат', 'G' => 'Золотой матч', 'P' => 'Плей-офф', 'C' => 'Кубок', 'S' => 'Суперкубок'];
  $cclen = strlen($cca);
  if (isset($t))
    $tour_type = is_numeric($t[0]) ? 'R' : ucfirst($t[0]);
  else
    $tour_type = '';

  if (is_dir($season_dir.'programs')) {
    $dir = scandir($season_dir.'programs');
    unset($dir[1], $dir[0]);
    foreach ($dir as $prog) {
      $tour = substr($prog, $cclen);
      if (is_numeric($tour[0]))
        $tournaments['R'][] = $tour; // регулярный чемпионат
      else
        $tournaments[$tour[0]][] = substr($tour, 1); // прочие турниры с буквенным модификатором

    }
    foreach ($tournaments as $tindex => $ttours)
      if (count($ttours)) {
        rsort($ttours, SORT_NUMERIC);
        $tname = ($tindex == 'G' && count($ttours) > 1) ? 'Золотой турнир' : $tnames[$tindex];
        $sidebar .= '
                <li class="active">
                    <a href="#'.$tindex.'Submenu" data-toggle="collapse" aria-expanded="'.($tour_type == $tindex ? 'true' : 'false').'" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled'.($tour_type == $tindex ? ' show' : '').'" id="'.$tindex.'Submenu">';
        foreach ($ttours as $to) {
          $tt = ltrim(strtr($to, ['NEW' => '']), '0');
          if ($tt < 10) $tt = ' ' . $tt;
          if ($tindex != 'R')
            $to = strtolower($tindex) . $to;

          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур<span>'.$tt.':</span></a>';
          if (is_file($season_dir . 'publish/it' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=it">итоги,</a>'.
                        $prefix.'&amp;m=text&amp;ref=r">обзор</a>';
          else
            $sidebar .= $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';
          $sidebar .= '
                            </div>
                        </li>';
        }
        $sidebar .= '
                    </ul>
                </li>';
      }

    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>';

    if (is_file($season_dir.'cham.inc'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=cham">Турнирная таблица</a></li>';

    if (is_file($season_dir.'gold.inc'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=gold">Золотая бутса</a></li>';

    if (is_file($season_dir.'cup.inc'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=cup">Сетка кубка</a></li>';

    if (is_file($season_dir.'cal'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=cal">Календарь</a></li>';

    if (is_file($season_dir.'gen'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=gen">Генераторы</a></li>';

    if (is_file($season_dir.'bombers'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=club">Команды</a></li>';

    if (is_file($season_dir.'codes.tsv'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=player">Игроки</a></li>';

  }
//  if ($cca == 'UKR')
//    $sidebar .= '
//                <li><a href="?a='.$a.'&amp;m=register">Выбор команды</a></li>';

  $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=hq">Президиум</a></li>
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'switzerland') { // сбор туров сезона Швейцарии
  if (is_dir($season_dir.'programs')) {
    $ttours = [];
    $dir = scandir($season_dir.'programs');
    unset($dir[1], $dir[0]);
    foreach ($dir as $prog)
      $ttours[] = substr($prog, 3);

    if (count($ttours)) {
      rsort($ttours, SORT_NUMERIC);
      $tname = 'Чемпионат';
// сезон в Швейцарии короткий, поэтому легко показываются все туры
//      $sidebar .= '
//                <li class="active">
//                    <a href="#SUISubmenu" data-toggle="collapse" aria-expanded="'.(isset($t) ? 'true' : 'false').'" class="dropdown-toggle">'.$tname.'</a>
//                    <ul class="collapse list-unstyled'.(isset($t) ? ' show' :'').'" id="SUISubmenu">';
      $sidebar .= '
                <li class="active">
                    <a href="#SUISubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled show" id="SUISubmenu">';
      foreach ($ttours as $to) {
        $tt = ' ' . ltrim($to, '0');
        $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;t='.$to;
        $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур<span>'.$tt.':</span></a>';
        if (is_file($season_dir . 'publish/it' . $to))
          $sidebar .= $prefix.'&amp;m=text&amp;ref=it">итоги,</a>'.
                      $prefix.'&amp;m=text&amp;ref=r">обзор</a>';
        else
          $sidebar .= $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';

        $sidebar .= '
                            </div>
                        </li>';
      }
      $sidebar .= '
                    </ul>
                </li>';
    }

    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>';

    if (is_file($season_dir.'cham.inc'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=cham">Турнирная таблица</a></li>';

    if (is_file($season_dir.'gold.inc'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=gold">Золотая бутса</a></li>';

    if (is_file($season_dir.'cal'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=cal">Календарь</a></li>';

    if (is_file($season_dir.'gen'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=gen">Генераторы</a></li>';

    if (is_file($season_dir.'bombers'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=club">Команды</a></li>';

    if (is_file($season_dir.'codes.tsv'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=player">Игроки</a></li>';

  }
  $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=register">Выбор команды</a></li>
                <li><a href="?a='.$a.'&amp;m=hq">Президиум</a></li>
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'uefa') { // сбор туров сезона для еврокубков
  $leagues = ['GOLDL' => 'Золотая Лига', 'CHAML' => 'Лига Чемпионов', 'CUPSL' => 'Кубковая Лига', 'UEFAL' => 'Лига Европы'];
  $tournaments = ['GOLDL' => [], 'CHAML' => [], 'CUPSL' => [], 'UEFAL' => []];
  if (substr($s, 0, 4) != '2008' && is_dir($season_dir.'programs')) { // в 2008-м была другая структура
    $dir = scandir($season_dir.'programs');
    unset($dir[1], $dir[0]);
    foreach ($dir as $prog) {
      $tour = substr($prog, 5);
      $tournaments[substr($prog, 0, 5)][] = $tour;
    }
    foreach ($tournaments as $league => $ttours)
      if (count($ttours)) {
        rsort($ttours, SORT_NUMERIC);
        $tname = $leagues[$league];
        $sidebar .= '
                <li class="active">
                    <a href="#'.$league.'Submenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled show" id="'.$league.'Submenu">';
        foreach ($ttours as $to) {
          $tt = ltrim(strtr($to, ['NEW' => '']), '0');
          if ($tt < 10) $tt = ' ' . $tt;
          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур<span>'.$tt.':</span></a>';
          if (is_file($season_dir . 'publish/' . $league . '/itc' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=it">итоги,</a>'.
                        $prefix.'&amp;m=text&amp;ref=r">обзор</a>';
          else
            $sidebar .= $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';

          $sidebar .= '
                            </div>
                        </li>';
        }
        $sidebar .= '
                    </ul>
                </li>';
      }

    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>';

    if (is_file($season_dir.'bombers'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=club">Команды</a></li>';

    if (is_file($season_dir.'codes.tsv'))
      $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=player">Игроки</a></li>';

  }
  $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'sfp-team') { // сбор туров сезона для SFP
  $leagues = [
'PRO' => 'ProfiOpen',
'FFP' => 'Фестиваль ФП',
'PRE' => 'PREDвидение',
'TOR' => 'Лига КСП «Торпедо»',
'VOO' => 'Спартакиада',
'SPR' => 'Спартакиада',
'FWD' => 'Эксперт-Лига'
];
  $tournaments = ['PRO' => [], 'FFP' => [], 'PRE' => [], 'TOR' => [], 'SPR' => [], 'FWD' => []];
  foreach ($leagues as $league => $tname)
    if (is_dir($season_dir.$league)) {
      $sidebar .= '
                <li class="active">
                    <a href="#'.$league.'Submenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled show" id="'.$league.'Submenu">';
      $dir = scandir($season_dir.$league, 1);
      foreach ($dir as $tt)
        if (!in_array($tt[0], ['.', 'n'])) {
          $to = $tt;
          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур<span>'.$tt.':</span></a>';
          if (is_file($season_dir . 'publish/' . $league . '/it' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=it">итоги</a>';
          else
            $sidebar .=  $prefix.'&amp;m=prognoz">прогнозы</a>';

          $sidebar .= '
                            </div>
                        </li>';
        }

      $sidebar .= '
                    </ul>
                </li>';
    }

  $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>';

  if (is_file($season_dir.'codes.tsv'))
    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=player">Состав команды</a></li>';

}

else if ($a == 'world' || $a == 'sfp-20') { // сбор туров Мировой Лиги и Лиги Наций + юбилейный турнир
  $tnames = ['MSL' => 'Лига Сайтов', 'UNL' => 'Лига Наций', 'UFT' => 'Финальный турнир', 'WL' => 'Мировая Лига', 'IST' => 'Турнир SFP-20!'];
  foreach ($tnames as $code => $tname) {
    $s_dir = $online_dir . ($code == 'MSL' || $code == 'UFT' ? 'UNL' : $code) . '/' . $s . '/';
    $aa = $code == 'IST' ? 'sfp-20' : 'world';
    $suffix = ($code != 'UFT' && $code != 'UNL' && $code != 'MSL' || substr($s, 0, 4) < '2018') ? '' : '_' . strtolower($code);
    if (($code != 'UFT' && $code != 'UNL' && $code != 'MSL' || substr($s, 0, 4) > '2018') && is_dir($s_dir.'programs')) {
//                    <a href="#SUISubmenu" data-toggle="collapse" aria-expanded="'.(isset($t) ? 'true' : 'false').'" class="dropdown-toggle">'.$tname.'</a>
//                    <ul class="collapse list-unstyled'.(isset($t) ? ' show' :'').'" id="SUISubmenu">';
      $sidebar .= '
                <li class="active">
                    <a href="#'.$code.'Submenu" data-toggle="collapse" aria-expanded="'.($code == 'UNL' ? 'false' : 'true').'" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled'.($code == 'UNL' ? '' : ' show').'" id="'.$code.'Submenu">';
      $dir = scandir($s_dir.'programs', 1);
      foreach ($dir as $prog)
        if ($prog[0] != '.' && $code != 'UFT' && $prog < 'UNL12') {
          $tt = substr($prog, 3);
          $to = $tt;
          $prefix = '<a href="?a='.$aa.'&amp;s='.$s.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур <span>'.$tt.':</span></a>';
          if (is_file($s_dir . 'publish/it' . $to))
            $sidebar .= $prefix.'&amp;m=result">итоги,</a>'.
                        $prefix.'&amp;m=stat&amp;l='.$suffix[2].'">стат.</a>';
          else
            $sidebar .= $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';

          $sidebar .= '
                            </div>
                        </li>';
        }
        else if ($prog[0] != '.' && $code == 'UFT' && $prog > 'UNL11' && $prog < 'UNL17') {
          $tt = substr($prog, 3);
          $to = $tt;
          $tt -= 11;
          $prefix = '<a href="?a='.$aa.'&amp;s='.$s.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=p">тур <span>'.$tt.':</span></a>';
          if (is_file($s_dir . 'publish/it' . $to))
            $sidebar .= $prefix.'&amp;m=result">итоги,</a>'.
                        $prefix.'&amp;m=stat&amp;l='.$suffix[2].'">стат.</a>';
          else
            $sidebar .= $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';

          $sidebar .= '
                            </div>
                        </li>';
        }

      $sidebar .= '
                    </ul>
                </li>';
      if (is_file($s_dir.'publish/table.n'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=table'.$suffix.'">Турнирная таблица</a></li>';

      if (is_file($s_dir.'cal'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=cal'.$suffix.'">Календарь</a></li>';

      if (is_file($s_dir.'codes.tsv'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=player'.($suffix != '_unl' ? '&amp;l='.$suffix[2] : '').'">Участники</a></li>';

      $sidebar .= '
                <li><a href="?a='.$aa.'&amp;m=coach'.$suffix.'">Тренерская</a></li>
                <br>';
    }
  }
  if (is_file($season_dir.'gen'))
    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=gen">Генераторы</a></li>';

  $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=reglament">Регламент</a></li>
                <li><a href="?a='.$a.'&amp;m=hq">Президиум</a></li>
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'fifa') {
  $sidebar .= '
                <li><a href="?m=news&amp;s='.$s.'">Новости</a></li>
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=pres">Пресс-релизы</a></li>
                <li><a href="?m=reglament">Регламент</a></li>
                <li><a href="?m=history">История SFP</a></li>
                <li><a href="?m=help">Инструкция</a></li>
                <li><a href="?m=help-hq">Организатору</a></li>
                <li><a href="?m=konkurs">Конкурсы</a></li>
                <li><a href="?m=vacancy">Вакансии</a></li>
                <li><a href="?m=quota">Квоты игроков</a></li>
                <li><a href="http://forum.fprognoz.org" target="forum">Форум</a></li>
                <li><a href="?m=real">Веб-сайты</a></li>
                <li><a href="?m=video">Видеотрансляции</a></li>
                <li><a href="?m=live&amp;ls='.$fprognozls.'">Результаты</a></li>
                <li><a href="?m=hof">ЗАЛ СЛАВЫ</a></li>';
}

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
  include script_from_cache($a.'/prognoz.inc.php'); // REST only
}
else {
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=.75">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=$title?></title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="/css/fp.css?ver=270" rel="stylesheet">
    <link href="/css/comments.css?ver=2" rel="stylesheet">
    <link href="/js/croppic/croppic.css" rel="stylesheet">

    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/solid.js" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/fontawesome.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.slim.js" integrity="sha256-RtMTraB5gGlLER0FkKBcaXCmZCQCxkKS/dXm7MSEoEY=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.6/jstz.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/inline/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/inline/translations/ru.js"></script>
    <script src="/js/jquery-ui/jquery-ui.min.js"></script>
    <script src="/js/jquery-ui/jquery.ui.touch-punch.min.js"></script>
    <script src="/js/croppic/croppic-3.0.min.js"></script>
    <script src="/js/fp.js?ver=194"></script>
</head>

<body>

    <div class="overlay"></div>

    <a href="#" class="scrollToTop"><i class="fas fa-arrow-circle-up"></i></a>

    <div class="wrapper">
<?php

////////// меню навигации (левое)
// с этим разобраться, и вообще - надо ли? '.($sidebar_show ? ' class="active"' : '').'
echo '
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="/?m=vacancy"><h5>Свободные команды:</h5>Германия и Украина</a><br><br>
                <a href="/?m=news&s=2019-20"><h6>Новости SFP - ФИФА</h6></a>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#assocSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><img src="images/63x42/'.($a == 'sfp-20' ? 'world' : $a).'.png" class="flag" />'.($a == 'sfp-20' ? 'Лига Наций' : $fa[$a]).' (выбор)</a>
                    <ul class="collapse list-unstyled" id="assocSubmenu">';
foreach ($fa as $ae => $ar)
  echo '
                        <li><a href="?a='.$ae.'"><img src="images/63x42/'.$ae.'.png" class="flag" />'.$ar.'</a></li>';
echo '
                    </ul>
                </li>
                <li class="active">
                    <a href="#seasonSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Сезон '.$s.' (выбор)</a>
                    <ul class="collapse list-unstyled" id="seasonSubmenu">';
foreach ($seasons as $ss)
  echo '
                        <li><a href="?a='.$a.'&s='.$ss.'">'.$ss.'</a></li>';
echo '
                    </ul>
                </li>
'.$sidebar.'
            </ul>
        </nav>

        <nav id="rightbar">';

////////// персональное меню (правое)

  echo '
            <div class="rightbar-header" data-log="' . (isset($_GET['logout']) ? 'out' : (!isset($_SESSION['Coach_name']) ? 'in' : $_SESSION['Coach_name'])) . '">';
  if (!isset($_SESSION['Coach_name']) || $role == 'badlogin')
  {
    $data_cfg = ['cmd' => 'unique_check'];
    $ncfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    $data_cfg = ['cmd' => 'password_check'];
    $pcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    $data_cfg = ['cmd' => 'send_token'];
    $tcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
                <form id="l_form" method="POST" data-tpl="'.$tcfg.'">
                    <p><input type="text" id="name_str" name="name_str" data-tpl="'.$ncfg.'" placeholder="имя, e-mail или код" /></p>
                    <p><input type="password" id="pass_str" name="pass_str" data-tpl="'.$pcfg.'" placeholder="пароль"/></p>
                    <p id="valid_name"><input type="submit" name="login" value="Вход" /></p>
                </form>';
    if ($notification)
      echo '
                <h6>' . $notification . '</h6>';
    else
      echo '
                <h0>
У Вас нет пароля?<br>
Укажите игровое имя,<br>
а в поле пароля -<br>
код вашей команды,<br>
если таковая есть.<br>
После нажатия кнопки<br>
"Вход" пароль будет<br>
выслан на ваш емейл.<br>
<br>
                </h0>';

    echo '
              </div>';
  }
  else
  {
    echo '
                <h5>Добро пожаловать,
                <span style="color: lightgreen;"><b>'.$_SESSION['Coach_name'].'</b></span> !</h5>
            </div>
            &nbsp;&nbsp; Ваши команды:
            <ul class="list-unstyled components">
                <!--li class="active">
                    <a href="#cmdSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Ваши команды</a>
                    <ul class="collapse list-unstyled" id="cmdSubmenu"-->';
    foreach ($cmd_db as $cc => $cc_data)
if ($cc != 'UNL') // временно не показываем
      foreach ($cc_data as $code => $team)
        if ($team['usr'] == $_SESSION['Coach_name']) {
          echo '
                <li><a href="?a='.strtolower($ccn[$cc]).'">'.($cc == 'SFP' ? 'Сборная сайта' : $team['cmd']).' ('.$cc.')</a></li>
';
          if ($cc == $cca && $m != 'newcal' && $m != 'player' && $m != 'codestsv' && $a != 'world')
            $highlight = $team['cmd'];
        }

    $data_cfg = ['cmd' => 'fun_zone', 'a' => $a, 's' => $s, 'c' => $coach_name];
    $fcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
                    <!--/ul>
                </li-->
                <p></p>
                <li id="funZone" data-tpl="'.$fcfg.'"><a id="toggleFunZone" href="javascript:void(0)">Показ фан-зоны &nbsp; <span id="funZoneIndicator"><img src="images/'.$gb_status.'.gif" border = "0" alt="'.$gb_status.'" /></span></a></li>
                <li><a id="change_pass" href="?m=pass"'.(isset($data['ts']) ? ' data-ts="'.$data['ts'].'" onClick="newPassword()"' : (isset($_POST['pass_str']) ? ' data-ts="'.time().'" onClick="newPassword()"' : '')).'>Смена пароля</a></li>
                <li><a href="?m=api">API</a></li>
                <li><a href="?logout=1">Выход</a></li>
                <p></p>';
    if ($role == 'president') {
      if ($cca == 'SUI' || is_file($data_dir.'online/'.$cca.'/'.$s.'/cal'))
        echo '
                <li><a href="?a='.$a.'&amp;m=mkpgm">Создать новый тур</a></li>';
      else
        echo '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=newcal">Создать календарь</a></li>';
      echo '
                <li><a href="?a='.$a.'&amp;m=set">Настройки сезона</a></li>
                <li class="active">
                    <a href="#tplSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Макеты</a>
                    <ul class="collapse list-unstyled" id="tplSubmenu">
                        <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=text&amp;ref=p">Программка</a></li>
                        <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=text&amp;ref=pc">Программка кубка</a></li>
                        <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=text&amp;ref=it">Итоги тура</a></li>
                        <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=text&amp;ref=itc">Итоги плей-офф тура</a></li>
                        <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=text&amp;ref=r">Шапка обзора</a></li>
                    </ul>
                </li>';
    }
    echo '
            </ul>';
  }
  echo '
        </nav>

        <!-- Page Content Holder -->
        <content id="content">
            <header class="header">
                <h3>'.$description.'</h3>
            </header>
            <nav class="navbar navbar-expand-lg navbar-lignt bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="navbar-btn'.($sidebar_show ? ' active' : '').'" title="Свернуть/показать панель навигации">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button type="button" id="rightbarCollapse" class="navbar-btn">
                        <div id="rightbarIconUser" title="Показать личный кабинет"><i class="fas fa-user"></i></div>
                        <div id="rightbarIconUserX" title="Свернуть личный кабинет"><i class="fas fa-user-times"></i></div>
                    </button>';
  if ($m == 'set' && $role == 'president') {
    $data_cfg = ['cmd' => 'save_config', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
    $scfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
                    <button type="button" id="ConfigEditor" class="navbar-btn" style="display:none">
                        <div id="saveCfgIcon" class="navbar-btn-icon" data-tpl="' . $scfg . '" title="Сохранить изменения"><i class="fas fa-save"></i></div>
                    </button>';
  }
  else if ($m == 'player' && $role == 'president') {
    echo '
                    <button type="button" id="EditLink" class="navbar-btn" onClick="location.href=\'?a='.$a.'&s='.$s.'&m=codestsv\'">
                        <div title="Редактировать"><i class="fas fa-edit"></i></div>
                    </button>';
  }
  else if ($m == 'codestsv' && $role == 'president') {
    echo '
                    <button type="button" id="SubmitForm" class="navbar-btn" style="display:none" onClick="$(\'#MainForm\').submit()">
                        <div id="postForm" class="navbar-btn-icon" title="Редактировать"><i class="fas fa-edit"></i></div>
                    </button>';
  }
  else if (isset($content) &&
    ($role == 'president' || $role == 'pressa' && (in_array($m, ['news', 'pres']) || $m == 'text' && $ref == 'r'))) {
    $data_cfg = ['cmd' => 'save_file', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
    if (isset($l)) $data_cfg['l'] = $l;
    if (isset($ref)) $data_cfg['ref'] = $ref;
    if (isset($t)) $data_cfg['t'] = $t;
    $scfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
                    <button type="button" id="inlineEditor" class="navbar-btn">
                        <div id="editIcon" class="navbar-btn-icon" title="Редактировать"><i class="fas fa-edit"></i></div>
                        <div id="saveIcon" class="navbar-btn-icon" data-tpl="' . $scfg . '" title="Сохранить изменения"><i class="fas fa-save"></i></div>
                    </button>';
  }
  if ($role == 'president' && in_array($m, ['cal', 'gen', 'news', 'codestsv', 'player', 'pres', 'prognoz', 'text'])
   || $role == 'pressa' && (in_array($m, ['news', 'pres', 'player']) || $m == 'text' && $ref == 'r')
   || $role == 'coach' && $m == 'player') {
    $data_cfg = ['cmd' => 'send_mail', 'author' => $_SESSION['Coach_name'], 'a' => $a, 's' => $s, 'm' => $m];
    if (isset($ref)) $data_cfg['ref'] = $ref;
    if (isset($t)) {
      $data_cfg['t'] = $t;
      switch ($t[0]) {
        case 'c': $tname = ' кубка'; break;
        case 's': $tname = ' Суперкубка'; break;
        case 'p': $tname = ' плей-офф'; break;
        default : $tname = '';
      }
      $tt = $tname ? substr($t, 1) : ltrim($t, '0');
    }
    $subject = 'ФП. ' . $fa[$a] . '. ';
    switch ($m) {
      case 'cal': $subject .= 'Календарь чемпионата ' . $s; break;
      case 'gen': $subject .= 'Генераторы чемпионата ' . $s; break;
      case 'text':
        switch ($ref) {
          case 'p'  : $subject .= 'Программка ' . $tt . ' тура' . $tname; break 2;
          case 'pc' : $subject .= 'Программка ' . $tt . ' тура кубка'; break 2;
          case 'it' : $subject .= 'Итоги ' . $tt . ' тура' . $tname; break 2;
          case 'itc': $subject .= 'Итоги ' . $tt . ' тура кубка'; break 2;
          case 'r'  : $subject .= 'Обзор ' . $tt . ' тура' . $tname; break 2;
          case 'rc' : $subject .= 'Обзор ' . $tt . ' тура кубка'; break 2;
        }
      case 'news':
      case 'pres': $subject .= 'Пресс-релиз. '; break;
    }
    $mcfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
    echo '
                    <button type="button" id="sendMail" class="navbar-btn">
                        <div id="mailIcon" class="navbar-btn-icon" data-mode="'.(isset($content) ? 'text' : $m).'" data-subj="'.$subject.'" title="Подготовить текст к рассылке"><i class="fas fa-envelope-open"></i></div>
                        <div id="sendIcon" class="navbar-btn-icon" data-tpl="' . $mcfg . '" title="Рассылка текста"><i class="fas fa-envelope"></i></div>
                    </button>';
  }
  echo '
                    <button type="button" id="navbarFlag" class="navbar-flag" style="background: url(images/63x42/'.$a.'.png) no-repeat; background-size: 100%; display:none" onClick="location.href=\'?a='.$a.'\'"></button>
                    <button id="btnMyTours" class="btn btn-light d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarMyTours" aria-controls="navbarMyTours" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-futbol"></i>
                    </button>
                    <div id="navbarMyTours" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav ml-auto">';
  if (isset($_SESSION['Coach_name'])) {
    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc'))
      build_personal_nav();

    include($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc');
  }
  echo '
                        </ul>
                    </div>
                </div>
            </nav>';
/* теперь список туров открыт при просмотре тура, поэтому дополнительная навигация не нужна
  if (isset($ref)) {
    $tt = $t;
    $prev = is_file($season_dir.'/publish/'.$ref.(--$tt)) ? '?a='.$a.'&s='.$s.'&t='.($tt).'&m=text&ref='.$ref : '#';
    $tt = $t;
    $next = is_file($season_dir.'/publish/'.$ref.(++$tt)) ? '?a='.$a.'&s='.$s.'&t='.($tt).'&m=text&ref='.$ref : '#';
    echo '
<div class="tour-tabs">
 <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link'.($prev == '#'? ' disabled' : '').'" href="'.$prev.'">предыдущий</a>
  </li>
  <li class="nav-item">
    <a class="nav-link'.($ref == 'p' ? ' active' : '').'" href="?a='.$a.'&s='.$s.'&t='.$t.'&m=text&ref=p">программка</a>
  </li>
  <li class="nav-item">
    <a class="nav-link'.($ref == 'it' ? ' active' : '').'" href="?a='.$a.'&s='.$s.'&t='.$t.'&m=text&ref=it">итоги</a>
  </li>
  <li class="nav-item">
    <a class="nav-link'.($ref == 'r' ? ' active' : '').'" href="?a='.$a.'&s='.$s.'&t='.$t.'&m=text&ref=r">обзор</a>
  </li>
  <li class="nav-item">
    <a class="nav-link'.($next == '#' ? ' disabled' : '').'" href="'.$next.'">следущий</a>
  </li>
 </ul>
</div>
';
  }
*/
  $nohl = ['club', 'main', 'mkpgm', 'news', 'set'];
  echo '
            <div class="main">';
  include ('fifa/register.inc.php'); // регистрация в сборных ассоциаций

  // ссылка для отката к прогнозам
  if ($role == 'president' && $m == 'text' && $ref == 'it')
    echo '
                <div style="position: absolute; z-index: 10; opacity: 0.5">
                  <a href="/?a='.$a.'&s='.$s.'&t='.$t.'&m=prognoz"><i class="fas fa-undo"></i></a>
                </div>';

  echo '
                <div id="editable"' . $editable_class . ' data-hl="'.(!in_array($m, $nohl) && ($a != 'sfp-team') && isset($highlight) ? $highlight : '').'">';
  if ($m == 'pres') {
    $pr = glob($season_dir.'publish/1*');
    if (count($pr)) {
      rsort($pr);
      echo '
                    <h4>Пресс-релиз'.(count($pr) > 1 ? 'ы' : '').'</h4>';
      $collapsed = false;
      foreach ($pr as $file) {
        $ts = substr($file, -10);
        echo '
                    <div class="pressrelease-title">'.date('Y-m-d H:i', $ts).'</div>
                    <div id="'.$ts.'" class="pressrelease"'.($collapsed ? ' style="display:none"' : '').'>
'.file_get_contents($file).'
                    </div>';
        $collapsed = true;
      }
    }
    else
      echo '
                    <h5>В этом сезоне пресс-релизов нет.</h5>';

  }
  else if ($m == 'set' && $role == 'president') {
    echo '
                    <h4>Редактирование настроек сезона</h4>
                    <form id="season_settings" action="" method="POST">
                        <ul>
                            <li><div>Название ФП-ассоциации: </div><input type="text" name="description" value="'.$description.'" /></li>
                            <li><div>html-заголовок ассоциации: </div><input type="text" name="title" value="'.$title.'" /></li>
                            <li><div>Заголовок новостных страниц: </div><input type="text" name="main_header" value="'.$main_header.'" /></li>
                            <li><div>Название текущего сезона: </div><input id="cur_year" type="text" name="cur_year" value="'.$cur_year.'" /></li>
                            <li><div>Президент: </div><input id="president" type="text" name="president" value="'.$president.'" /></li>
                            <li><div>Вице-президент(ы): </div><input id="vice" type="text" name="vice" value="'.$vice.'" placeholder="нет; можно несколько имен через запятую" /></li>
                            <li><div>Пресс-атташе: </div><input id="pressa" type="text" name="pressa" value="'.$pressa.'" placeholder="нет; можно несколько имен через запятую" /></li>';
    if ($a != 'world')
        echo '
                            <li><div>Тренер(ы) сборной: </div><input id="coach" type="text" name="coach" value="'.$coach.'" placeholder="нет; можно несколько имен через запятую" /></li>
                            <li><div>Разрешено редактировать составы: </div><input id="club_edit" type="checkbox" name="club_edit"'.($club_edit ? ' checked="checked"' : '').' /></li>';
    echo '
                            <li><h5>Турниры: <div class="add_tournament" data-id="tournament-'.(count($config) - 1).'"><button class="fas fa-plus-circle" title="добавить турнир" /></button></div></h5>';
    if (isset($config[0]['format']))
      foreach ($config as $n => $tournament)
      {
        $stages = count($config[0]['format']);
        echo '
                                <ul id="tournament-'.$n.'">
                                    <li><div>Название турнира: </div><input type="text" name="tournament['.$n.']" value="'.(isset($tournament['tournament']) ? $tournament['tournament'] : '').'" placeholder="не обязательно" /> <div class="delete_stage" data-id="tournament-'.$n.'"><button class="fas fa-trash" title="удалить турнир"></button></div></li>
                                    <li><div>Префикс кода тура: </div><input type="text" name="prefix['.$n.']" value="'.(isset($tournament['prefix']) ? $tournament['prefix'] : '').'" placeholder="по умолчанию - код ассоциации" /></li>
                                    <li><div>Схема розыгрыша: </div><select name="type['.$n.']"><option value="chm">чемпионат (круговой турнир)</option><option value="cup" '.(isset($tournament['type']) && $tournament['type'] == 'cup' ? ' selected="selected"' : '').'>кубок (турнир с выбыванием)</option><option value="com" '.(isset($tournament['type']) && $tournament['type'] == 'com' ? ' selected="selected"' : '').'>комбинированный (группы + плей-офф)</option></select></li>
                                    <li><div>Нумерация туров: </div><select name="numeration['.$n.']"><option value="stage">поэтапная (каждый этап начинается туром 1)</option><option value="toend" '.(isset($tournament['nume']) && $tournament['nume'] == 'toend' ? ' selected="selected"' : '').'>сквозная (без сброса номера, как в еврокубках)</option></select></li>
                                    <li><h6>Этапы: <div class="add_stage" data-id="trn-'.$n.'-st-'.($stages - 1).'"><button class="fas fa-plus-circle" title="добавить этап" /></button></div></h6>';
        foreach ($tournament['format'] as $e => $stage)
        {
          echo '
                                        <ul id="trn-'.$n.'-st-'.$e.'">
                                            <li><div>Название этапа: </div><input type="text" name="stage['.$n.']['.$e.']" value="'.(isset($stage['stage']) ? $stage['stage'] : '').'" placeholder="не обязательно" /> <div class="delete_stage" data-id="trn-'.$n.'-st-'.$e.'"><button class="fas fa-trash" title="удалить этап"></button></div></li>
                                            <li><div>Суффикс кода тура: </div><input type="text" name="suffix['.$n.']['.$e.']" value="'.(isset($stage['suffix']) ? $stage['suffix'] : '').'" placeholder="по умолчанию нет" /></li>
                                            <li><div>Файл календаря: </div><input type="text" name="cal['.$n.']['.$e.']" value="'.(isset($stage['cal']) ? $stage['cal'] : '').'" placeholder="по умолчанию cal" /></li>
                                            <li><div>Количество групп (лиг): </div><input type="text" name="groups['.$n.']['.$e.']" value="'.(isset($stage['groups']) ? $stage['groups'] : '').'" placeholder="по умолчанию 1" /></li>
                                            <li><div>Количество туров: </div><input type="text" name="tourn['.$n.']['.$e.']" value="'.(isset($stage['tourn']) ? $stage['tourn'] : 1 + $stage['tours'][1] - $stage['tours'][0]).'" /></li>
                                            <li><div>Количество кругов: </div><input type="text" name="round['.$n.']['.$e.']" value="'.(isset($stage['round']) ? $stage['round'] : '').'" placeholder="по умолчанию 2" /></li>
                                            <li><div>Префикс названия тура: </div><input type="text" name="nprefix['.$n.']['.$e.']" value="'.(isset($stage['nprefix']) ? $stage['nprefix'] : '').'" placeholder="по умолчанию Тур: " /></li>
                                        </ul>
                                        <div id="div-trn-'.$n.'-st-'.($stages - 1).'" class="stage-div"></div>';
        }
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
  else
    include ($a . '/' . $m . '.inc.php');

  echo '
                </div>
                <div id="comments_wrapper" data-name="'.$coach_name.'" data-hash="'.crypt($coach_name, $salt).'">';

  if ($gb_status == 'on')
    include 'comments/main.php';

  echo '
                </div>
            </div>
        </content>';
?>

    </div>

    <footer class="footer">
        <p>
            <img src="/images/sfp-88x31.png" width="88" height="31" alt="SFP Button" title="SFP Button" />
            <a href="http://www.livescore.in/" title="Livescore.in" target="_blank"><img src="https://advert.livescore.in/livescore_in_88x31a.gif" width="88" height="31" border="0" alt="LIVESCORE.in"></a>
            <a href="http://profi-prognoza.ru/" target="_blank"><img src="images/bannerprofi.gif" border="0" width="88" height="31" alt="Сайт cпортивного прогнозирования &laquo;Профессионалы прогноза&raquo;"></a>
            <a href="http://primegang.ru/" target="_blank"><img src="images/primegang.gif" border="0" width="88" height="31" alt="Клуб футбольных прогнозистов PrimeGang" /></a>
            <?=(false && $is_redis ? '<img src="https://redis.io/images/redis-small.png" border="0" alt="Redis" title="Powered by Redis" />':'')?>
        </p>
        <p>
            Design and code: Alexander Sessa. Elapsed time <?=round(microtime(true) - $time_start, 3)?> sec
        </p>
    </footer>

</body>

</html>
<?php
}
if ($is_redis) $redis->close();
?>
