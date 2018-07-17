<?php
/*
- редактирование страниц посредством CKEditor
- вставка вырезанной цитаты в комментариях
- отправка токена с учетом смены пароля (закомментировано)
- смена пароля по токену или сразу после входа
- забыл пароль
*/
$time_start = microtime(true);
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(md5('iv'.$salt, true), 0, 8);
$key = substr(md5('pass1'.$salt, true) . md5('pass2'.$salt, true), 0, 24);
$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

function acl($name, $a='') {
  global $admin;
  if ($a)
    include ('$a/settings.inc.php');
  else {
    global $president;
    global $vice;
    global $pressa;
  }
  $hq = $admin;
  if (!in_array($president, $hq))
    $hq[] = $president;

  $a = explode(',', $vice); // может быть больше одного
  foreach ($a as $v) {
    $v = trim($v);
    if (!in_array($v, $hq)) $hq[] = $v;
  }
  if (in_array($name, $hq))
    return 'president';

  $hq = [];
  $a = explode(',', $pressa); // может быть больше одного
  foreach ($a as $p)
    $hq[] = trim($p);

  if (in_array($name, $hq))
    return 'pressa';

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

function current_season($y, $m) {
  if ($m < 8) $y--;
  return $y . '-' . (substr($y, 2) + 1);
}

function build_personal_nav() {
  global $_SESSION;
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
    $startTime = $currentTime - 1259200; // - 3 day
//    $startTime = $currentTime - 518400; // - 6 day
    $startDay = date('d', $startTime);
    $startMonth = date('m', $startTime);
    $startYear = date('Y', $startTime);
    $sched[0] = "$startYear/$startMonth";
    $sched[1] = ($startMonth == 12) ? ($startYear + 1)."/01" : sprintf("%4d/%02d", $startYear, $startMonth + 1);
    $currentSeason = current_season($startYear, $startMonth);
    $world = file_get_contents($online_dir . 'WL/'.$startYear.'/codes.tsv');
//    $sfp20 = file_get_contents($online_dir . 'IST/'.$startYear.'/codes.tsv');
    $tout = '';
    for ($nm=0; $nm <= 1; $nm++) {
      $dir = scandir($online_dir . 'schedule/'.$sched[$nm]);
      foreach ($dir as $fname) if ($fname[0] != '.' && ($nm || $fname >= $startDay)) {
        $subdir = scandir($online_dir . 'schedule/'.$sched[$nm].'/'.$fname);
        foreach ($subdir as $event) if ($event[0] != '.' && !strpos($event, '.resend')) {
          list($timeStamp, $countryCode, $tourCode, $action) = explode('.', $event);
//if ($_SESSION['Coach_name'] == 'Alexander Sessa') echo $event."<br />\n";
// World
          if ($countryCode == 'WL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false) {
            $tour_dir = $online_dir . 'WL/'.$startYear.'/prognoz/'.$tourCode;
            if (is_file($tour_dir.'/published'))
              $status = 6; // завершён
            else if (is_file($tour_dir.'/closed'))
              $status = 4; // играется
            else if (strpos(file_get_contents($tour_dir.'/mail'), $_SESSION['Coach_name']) !== false)
              $status = 5; // есть прогноз
            else
              $status = ($timeStamp <= $currentTime + 86400) ? 2 : 3; // нет прогноза
            $tout .= '
                                <a class="'.$statusColor[$status].'" href="/?a=world&s='.$startYear.'&l=S&t='.substr($tourCode, 3).'&m='.($status == 6 ? 'result' : 'prognoz').'">'.$tourCode.'</a>
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
          if ($countryCode != 'WL' && $countryCode != 'IST')
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
              $content = file_get_contents($online_dir.$countryCode.'/'.$currentSeason.'/programms/'.$tourCode);
              $content = substr($content, strpos($content, 'Последний с'));
              if (!strpos($content, $cmd_db[$countryCode][$code]['cmd'])) {
                if ($tourCode[4] != 'L')
                  $tudb[$team_str][$tourCode] = 0; // 0 - неучастие

              }
              elseif (is_file($tour_dir.'/closed')) {
                $team_str1 = ($countryCode == 'SFP') ? $cmd_db[$countryCode][$code]['cmd'] : $team_str;
                $content = file_get_contents($tour_dir.'/mail');
                if (strpos($content, substr($team_str1, 0, $cut).';') === false) {
                  if (is_file($tour_dir.'/adds')) {
                    $content = file_get_contents($tour_dir.'/adds');
                    if (strpos($content, $cmd_db[$countryCode][$code]['cmd'].' ') === false)
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

                $content = is_file($tour_dir.'/mail') ? file_get_contents($tour_dir.'/mail') : '';
                if ((strpos($content, substr($team_str, 0, $cut).';') === false)
                && (strpos($content, $team_str1.';') === false)) {
                  if (is_file($tour_dir.'/adds'))
                    $content = file_get_contents($tour_dir.'/adds');
                  else
                    $content = '';

                  if (strpos($content, $cmd_db[$countryCode][$code]['cmd'].' ') === false) {
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
    if ($tout)
      $out .= '
                            <li class="nav-item">' . $tout .'
                            </li>';

    $prev_fp = '';
    foreach (['BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'PRT', 'SCO', 'UKR'] as $countryCode) {
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
            $linktext = 'text&ref=itog';

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
  $cca_home = $data_dir . 'online/' . $country_code . '/';
  $acodes = file($cca_home . $season .'/codes.tsv');
  foreach ($acodes as $scode) if ($scode[0] != '#') {
    $ateams = explode('	', $scode);
    if (trim($ateams[0]) == $team_code) {
      $name = trim($ateams[2]);
      $email = trim($ateams[3]);
    }
  }
  $replyto = '';
  trim($email) ? $replyto = "\nReply-To: $email" : $replyto = '';
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
  foreach ($ccn as $ccc => $cname) if ($ccc != 'SBN' && $ccc != 'FCL' && $ccc != 'FIFA') {
    $dir = scandir($online_dir.$ccc, 1);
    foreach ($dir as $s)
      if ($s[0] == '2')
        break;

    $codes = file($online_dir . $ccc . '/' . $s . '/codes.tsv');
    foreach ($codes as $line) if (trim($line)) {
      list($code, $cmd, $name, $email) = explode("\t", $line);
      $email = trim($email);
      $code = trim($code, '- ');
      if ($code[0] != '#' && $name && (!strpos($code, '@') || $cc = 'SFP')) {
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
'England: CUP',
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
'France: FA Cup',
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

'UEFA: Champions League',
'UEFA: Europa League',
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
  $matches = json_decode(stripslashes($json), true);
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
    $base[$match] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8]);
    $base[$match.'/'.$data[6]] = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[8]);
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
'CHE' => 'Швейцария',
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
'belarus' => 'Белоруссия',
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
if (isset($m)) {
  if (!is_file($a . '/' . $m . '.inc.php')) {
    http_response_code(404);
    $a = 'fifa';
    $m = '404';
  }
}
else {
  $m = isset($s) ? 'text' : 'main';
  $ref = 'news';
  if ($a == 'fifa') {
    if (isset($_COOKIE['fprognozmain']))
      $m = 'news'; // при повторном посещении показывать новости
    else
      setcookie('fprognozmain', '1');
  }
}
if (!isset($s))
  $s = $cur_year;

$season = '';
$ccd = ($a == 'sfp-20') ? 'WL' : $cca;
if (is_dir($online_dir.$ccd))
  $dir = scandir($online_dir.$ccd, 1);

foreach ($dir as $subdir)
  if (($subdir[0] == '2') || ($subdir[0] == '1')) {
    $seasons[] = $subdir; // список сезонов для меню
    if (!isset($s))
      $s = $subdir;

  }

if ($cca == 'UNL') { // для показа в Лиге Наций подобрать еще и сезоны Мировой Лиги
  $dir = scandir($online_dir.'WL', 1);
  foreach ($dir as $subdir)
    if (($subdir[0] == '2') || ($subdir[0] == '1'))
      $seasons[] = $subdir;

}
if (!isset($t))
  $t = '01';

if ($m == 'prognoz' && $s != $cur_year)
  $m = 'main'; // форма prognoz только для текущего сезона!

if (isset($ls))
   setcookie('fprognozls', $ls);

$fprognozls = isset($_COOKIE['fprognozls']) ? $_COOKIE['fprognozls'] : 'inscore';


////////// SIDEBAR

$sidebar = '';

if (in_array($cca, $classic_fa)) { // сбор туров сезона для классических асоциаций
  $tournaments = ['R' => [], 'G' => [], 'P' => [], 'C' => [], 'S' => []];
  $tnames = ['R' => 'Чемпионат', 'G' => 'Золотой матч', 'P' => 'Плей-офф', 'C' => 'Кубок', 'S' => 'Суперкубок'];
  $cclen = strlen($cca);

  $season_dir = $online_dir.$cca.'/'.$s.'/';
  if (is_dir($season_dir.'programms')) {
    $dir = scandir($season_dir.'programms');
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
                    <a href="#'.$tindex.'Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled" id="'.$tindex.'Submenu">';
        foreach ($ttours as $to) {
          $tt = ltrim(strtr($to, ['NEW' => '']), '0');
          if ($tt < 10) $tt = ' ' . $tt;
          if ($tindex != 'R')
            $to = strtolower($tindex) . $to;

          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;<span>'.$tt.'</span></a>:&nbsp;';
          if (is_file($season_dir . 'publish/it' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a>';
          else
            $sidebar .=  $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';
          $sidebar .= '
                            </div>
                        </li>';
        }
        $sidebar .= '
                    </ul>
                </li>';
      }

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
  if ($cca == 'FRA')
    $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=register">Выбор команды</a></li>';
  $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=hq">Президиум</a></li>
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'switzerland') { // сбор туров сезона Швейцарии
  $season_dir = $online_dir.$cca.'/'.$s.'/';
  if (is_dir($season_dir.'programs')) {
    $ttours = [];
    $dir = scandir($season_dir.'programs');
    unset($dir[1], $dir[0]);
    foreach ($dir as $prog)
      $ttours[] = substr($prog, 3);

    if (count($ttours)) {
      rsort($ttours, SORT_NUMERIC);
      $tname = 'Чемпионат';
      $sidebar .= '
                <li class="active">
                    <a href="#CHESubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled" id="CHESubmenu">';
      foreach ($ttours as $to) {
        $tt = ' ' . ltrim($to, '0');
        $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;t='.$to;
        $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;<span>'.$tt.'</span></a>:&nbsp;';
        if (is_file($season_dir . 'publish/it' . $to))
          $sidebar .= $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a>';
        else
          $sidebar .=  $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';
        $sidebar .= '
                            </div>
                        </li>';
      }
      $sidebar .= '
                    </ul>
                </li>';
    }

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
  $season_dir = $online_dir.$cca.'/'.$s.'/';
  if (is_dir($season_dir.'programms')) {
    $dir = scandir($season_dir.'programms');
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
                    <a href="#'.$league.'Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled" id="'.$league.'Submenu">';
        foreach ($ttours as $to) {
          $tt = ltrim(strtr($to, ['NEW' => '']), '0');
          if ($tt < 10) $tt = ' ' . $tt;
          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;<span>'.$tt.'</span></a>:&nbsp;';
          if (is_file($season_dir . 'publish/' . $league . '/itc' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a>';
          else
            $sidebar .=  $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';
          $sidebar .= '
                            </div>
                        </li>';
        }
        $sidebar .= '
                    </ul>
                </li>';
      }

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
  $season_dir = $online_dir.$cca.'/'.$s.'/';
  foreach ($leagues as $league => $tname)
    if (is_dir($season_dir.$league)) {
      $sidebar .= '
                <li class="active">
                    <a href="#'.$league.'Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled" id="'.$league.'Submenu">';
      $dir = scandir($season_dir.$league, 1);
      foreach ($dir as $tt)
        if (!in_array($tt[0], ['.', 'n'])) {
          $to = $tt;
          $prefix = '<a href="?a='.$a.'&amp;s='.$s.'&amp;l='.$league.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;<span>'.$tt.'</span></a>:&nbsp;';
          if (is_file($season_dir . 'publish/' . $league . '/it' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=itog">итоги</a>';
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

  if (is_file($season_dir.'codes.tsv'))
    $sidebar .= '
                <li><a href="?a='.$a.'&amp;s='.$s.'&amp;m=player">Состав команды</a></li>';

}

else if ($a == 'world' || $a == 'sfp-20') { // сбор туров Мировой Лиги и Лиги Наций + юбилейный турнир
  $tnames = ['ULN' => 'Лига Наций', 'WL' => 'Мировая Лига', 'IST' => 'Турнир SFP-20!'];
  foreach ($tnames as $code => $tname) {
    $season_dir = $online_dir.$code.'/'.$s.'/';
    $aa = $code == 'IST' ? 'sfp-20' : 'world';
    if (is_dir($season_dir.'programs')) { // хоть здесь каталог без ошибки назван :)
      $sidebar .= '
                <li class="active">
                    <a href="#'.$code.'Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$tname.'</a>
                    <ul class="collapse list-unstyled" id="'.$code.'Submenu">';
      $dir = scandir($season_dir.'programs', 1);
      foreach ($dir as $prog)
        if ($prog[0] != '.') {
          $tt = substr($prog, 3);
          $to = $tt;
          $prefix = '<a href="?a='.$aa.'&amp;s='.$s.'&amp;t='.$to;
          $sidebar .= '
                        <li>
                            <div class="tlinks">
                            '.$prefix.'&amp;m=text&amp;ref=prog">тур&nbsp;<span>'.$tt.'</span></a>:&nbsp;';
          if (is_file($season_dir . 'publish/it' . $to))
            $sidebar .= $prefix.'&amp;m=text&amp;ref=itog">итоги,</a>&nbsp;'.$prefix.'&amp;m=text&amp;ref=rev">обзор</a>';
          else
            $sidebar .=  $prefix.'&amp;m=prognoz"> &nbsp; прогнозы</a>';
          $sidebar .= '
                            </div>
                        </li>';
        }

      $sidebar .= '
                    </ul>
                </li>';
      if (is_file($season_dir.'publish/table.cal'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=table">Турнирная таблица</a></li>';

      if (is_file($season_dir.'cal'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=cal">Календарь</a></li>';

      if (is_file($season_dir.'gen'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=gen">Генераторы</a></li>';

      if (is_file($season_dir.'codes.tsv'))
        $sidebar .= '
                <li><a href="?a='.$aa.'&amp;s='.$s.'&amp;m=player">Игроки</a></li>';

    }
  }
  $sidebar .= '
                <li><a href="?a='.$a.'&amp;m=coach">Тренерская</a></li>
                <li><a href="?a='.$a.'&amp;m=hq">Президиум</a></li>
                <li><a href="?a='.$a.'&amp;m=hof">ЗАЛ СЛАВЫ</a></li>';
}

else if ($a == 'fifa')
  $sidebar .= '
                <li><a href="?m=news">Новости</a></li>
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


///////// /SIDEBAR


session_start();
if (isset($token)) { // вход по ссылке
  $data = json_decode(trim(mcrypt_decrypt( MCRYPT_BLOWFISH, $key, base64_decode($token), MCRYPT_MODE_CBC, $iv )), true);
  if ($data['cmd'] == 'auth_token' && $data['ts'] > time())
    $_SESSION['Coach_name'] = $data['name'];
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
    if ($hash == $pwd &&
       ($name_str == mb_strtoupper($code) || $name_str == mb_strtoupper($name) || $name_str == strtoupper($mail))) {
      $passed = true;
      if (isset($_POST['submitnewpass'])) {
        $sendpwd = $mail;     // для отправки сообщения о смене пароля
        $team_codes[$as_code] = $code; // для последующей смены пароля
      }
      if (strlen($coach_name) < strlen($name))
        $coach_name = $name; // выбираем самое длинное имя из указанных

    }
    else if ($m == 'authentifying') {
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
  if (strtotime('7/16') < time() && time() <= strtotime('9/1')
   && !is_file($data_dir.'personal/'.$coach_name.'/'.date('Y'))) {
    $a = 'fifa';
    $m = 'confirm'; // кампания сбора подтверждений с 16 июля по 1 сентября
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
  $is_redis = false;
  $gb_status = 'off';
}
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
  else if ($a == 'world') {
    $lock = $online_dir . 'log/renew.WLS';
    if (!is_file($lock)) { // если первый, обновляем календарь
      touch($lock);
      $matches = 8;
      for ($nm=1; $nm<=$matches; $nm++)
        file_get_contents("https://fprognoz.org/?a=world&l=S&s=2018&m=prognoz&t=$t&renew=1&n=$nm");

      unlink($lock);
    }
    else {                 // иначе ждём пока его обновят; это ajax - можно ждать несколько секунд
      $timer = 20000;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=$title?></title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link href="css/fp.css?ver=97" rel="stylesheet">
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-92920347-1', 'auto');
        ga('send', 'pageview');
    </script>
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <!-- Popper.JS UMD ver. -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="/js/jquery/jquery.color.js"></script>
    <script src="/js/socket.io/socket.io.slim.js"></script>
    <script type="text/javascript">
        $.browser={};
        $.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());
        $.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());
        $.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());
        $.browser.msie=/msie/.test(navigator.userAgent.toLowerCase());
        function validateEmail(email){var re=/\S+@\S+\.\S+/;return re.test(email)}
        var fg=0
        function passwordCheck(str){
            $.post("/online/ajax.php",{
                data:$("#pass_str").data("tpl"),
                nick:$("#name_str").val(),
                pswd:str
            },
            function(r){
                if(r=='0'){
                    $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times" /> неверный пароль</span>');fg=setTimeout(function(){$("#forget").show()},17000)
                }
                else if(r=='1'){
                    $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check" /> добро пожаловать!</span>');$("#l_form").submit()
                }
                else if(r=='2'){
                    $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check" /> пароль выслан</span>')
                }
            })
        }
        function emailCheck(str){
            $.post("/online/ajax.php",{
                data:$("#name_str").data("tpl"),
                nick:str,
                email:str
            },
            function(r){
                if(r=='0')
                    $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times" /> такой e-mail не найден</span>')
                else {
                    str=$("#pass_str").val()
                    if (str.length)
                        passwordCheck(str)
                    else
                        $("#valid_name").html('<span style="color:lightblue;cursor:pointer" onClick="tokenSend(); return false" title="Вам будет выслана ссылка для входа"><i class="fas fa-check" /> войти без пароля?</span>')
                }
            })
        }
        function nicknameCheck(str){
            $.post("/online/ajax.php",{
                data:$("#name_str").data("tpl"),
                nick:str,
                email:""
            },function(r){
                if(r=='0')
                    $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times" /> имя/e-mail не найдены</span>');
                else{
                    str=$("#pass_str").val()
                    if (str.length)
                        passwordCheck(str)
                    else
                        $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check" /> теперь введите пароль</span>');
                }
            })
        }
        function tokenSend(){
            $.post("/online/ajax.php",{
                data:$("#l_form").data("tpl"),
                nick:$("#name_str").val()
            },function(r){
                if(r==1)
                    $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check" /> проверьте вашу почту</span>')
                else
                    $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times" /> не удалось отправить</span>')
            })
        }
        function newPassword(){
            return true;
        }
        $(document).ready(function(){
<?php
  if (isset($_GET['logout']))
    echo '            history.pushState(null, "'.$title.'", "'.$this_site.'")
';
?>
            $('#sidebarCollapse').on('click',function(){
                $('#sidebar').toggleClass('active');
                $('#sidebar').toggleClass('collapsed');
                $(this).toggleClass('active');
                $(this).toggleClass('collapsed');
                //$('#navbarFlag').toggle();
            });
            $('#rightbarCollapse').on('click',function(){
                $('#rightbar').toggleClass('active');
                $(this).toggleClass('active');
                if($('#rightbarIconUser').is(':hidden')){
                    $('#rightbarIconUser').show();
                    $('#rightbarIconUserX').hide();
                }
                else if($('#rightbarIconUserX').is(':hidden')){
                    $('#rightbarIconUser').hide();
                    $('#rightbarIconUserX').show();
                }
            });
            $("a[name=modal]").click(function(e){e.preventDefault();$('.overlay').fadeTo("fast",0.65);$("#mwin").addClass("popup-show")});
            $(".popup .close,.overlay").click(function(e){e.preventDefault();$(".overlay").hide();$("#mwin").removeClass("popup-show")});
            $("#name_str").blur(function(){
                if(!$("#name_str").is(":hover")){
                    str=$(this).val()
                    if(str.length<2)
                        $("#valid_name").html('<span style="color:pink"><i class="fas fa-times"></i> введите хотя бы 2 буквы</span>')
                    else if(validateEmail(str))
                        emailCheck(str)
                    else
                        nicknameCheck(str)
                }
            })
            $("#pass_str").keyup(function(){
                if($("#pass_str").is(":focus")){
                    clearTimeout(fg)
                    str=$(this).val()
                    if (str.length)
                        passwordCheck(str)
                    else{
                        str=$("#name_str").val()
                        if(validateEmail(str))
                            emailCheck(str)
                        else
                            $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check" /> теперь введите пароль</span>')
                    }
                }
            })

            $(window).scroll(function(){
                if($(this).scrollTop()>100)
                    $('.scrollToTop').fadeIn();
                else
                    $('.scrollToTop').fadeOut();
            });
            $('.scrollToTop').click(function(){
                $('html,body').animate({scrollTop:0},800);
                return false;
            });

        });
    </script>
</head>

<body>

    <a href="#" class="scrollToTop"><i class="fas fa-arrow-circle-up"></i></a>

    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar"<?=($sidebar_show ? ' class="active"' : '')?>>
            <div class="sidebar-header">
                <a href="/"><h5>SFP - симпатичный футбол-прогноз</h5></a>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#assocSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><img src="images/63x42/<?=($a == 'sfp-20' ? 'world' : $a)?>.png" class="flag" /><?=($a == 'sfp-20' ? 'Лига Наций' : $fa[$a])?></a>
                    <ul class="collapse list-unstyled" id="assocSubmenu">
<?php
foreach ($fa as $ae => $ar)
  echo '
                        <li>
                            <a href="?a='.$ae.'"><img src="images/63x42/'.$ae.'.png" class="flag" />'.$ar.'</a>
                        </li>
';
?>
                    </ul>
                </li>
                <li class="active">
                    <a href="#seasonSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Сезон <?=$s?></a>
                    <ul class="collapse list-unstyled" id="seasonSubmenu">
<?php
foreach ($seasons as $ss)
  echo '
                        <li>
                            <a href="?a='.$a.'&s='.$ss.'">'.$ss.'</a>
                        </li>
';
?>
                    </ul>
                </li>
<?=$sidebar?>
            </ul>
        </nav>

        <nav id="rightbar">
            <div class="rightbar-header">
<?php
  if (!isset($_SESSION['Coach_name']) || $role == 'badlogin') {
    $data_cfg = ['cmd' => 'unique_check'];
    $ncfg = base64_encode(mcrypt_encrypt( MCRYPT_BLOWFISH, $key, json_encode($data_cfg), MCRYPT_MODE_CBC, $iv ));
    $data_cfg = ['cmd' => 'password_check'];
    $pcfg = base64_encode(mcrypt_encrypt( MCRYPT_BLOWFISH, $key, json_encode($data_cfg), MCRYPT_MODE_CBC, $iv ));
    $data_cfg = ['cmd' => 'send_token'];
    $tcfg = base64_encode(mcrypt_encrypt( MCRYPT_BLOWFISH, $key, json_encode($data_cfg), MCRYPT_MODE_CBC, $iv ));
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
У Вас нет пароля?<br />
Укажите полное имя,<br />
а в поле пароля -<br />
код вашей команды.<br />
После нажатия кнопки<br />
"Вход" пароль будет<br />
выслан на ваш емейл
                </h0>';

  }
  else {
    echo '
                <h5>Добро пожаловать,
                <span style="color: lightgreen;"><b>' . $_SESSION['Coach_name'] . '</b></span> !</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#cmdSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Ваши команды</a>
                    <ul class="collapse list-unstyled" id="cmdSubmenu">';
    foreach ($cmd_db as $cc => $cc_data)
      foreach ($cc_data as $code => $team)
        if ($team['usr'] == $_SESSION['Coach_name'])
          echo '
                <li><a href="?a=' . strtolower($ccn[$cc]) . '">' . ($cc == 'SFP' ? 'Сборная сайта' : $team['cmd']) . ' (' . $cc . ')</a></li>
';
?>
                    </ul>
                </li>
                <li class="active">
                    <a class="dropdown-item" href="#" onClick="document.getElementById('gb_toggle').submit(); return false;">Показ фан-зоны &nbsp; <img src="images/<?=$gb_status?>.gif" border = "0" alt="<?=$gb_status?>" /></a>
<form id="gb_toggle" method="post"><input type="hidden" name="toggle_gb" value="" /></form>
                    <a class="dropdown-item" id="change_pass" href="?m=pass"<?=(isset($data['ts']) ? ' data-ts="'.$data['ts'].'" onClick="newPassword()"' : (isset($_POST['pass_str']) ? ' data-ts="'.time().'" onClick="newPassword()"' : ''))?>>Смена пароля</a>
                    <a class="dropdown-item" href="?logout=1">Выход</a>
                    <p></p>
                </li>
<?php
      if ($role == 'president') { ?>
                <li><a href="/online/makeprogramm.php?cc=<?=$cca?>" target="MakeProgram">Создать новый тур</a></li>
                <li class="active">
                    <a href="#mailSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Рассылка</a>
                    <ul class="collapse list-unstyled" id="mailSubmenu">
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=email";">Выбранным игрокам</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=maillist">Пресс-релиз</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=maillist&amp;file=program">Программка</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=maillist&amp;file=prognoz">Прогнозы</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=maillist&amp;file=itogi">Итоги</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=maillist&amp;file=review">Обзор</a></li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#editSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Редактирование</a>
                    <ul class="collapse list-unstyled" id="editSubmenu">
                        <li><a href="?a=<?=$a?>&amp;m=files&amp;file=settings">Основные настройки</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=editnews">Новости</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=codestsv">Игроки</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=calchm">Календарь</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=genchm">Генераторы</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=calcup">Календарь кубка</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=gencup">Генераторы кубка</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=bombers">Бомбардиры</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=files&amp;file=program">Программка тура</a></li>
                    </ul>
                </li>
                <li class="active">
                    <a href="#tplSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Макеты</a>
                    <ul class="collapse list-unstyled" id="tplSubmenu">
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=tplpchm">Программка</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=tplpcup">Программка кубка</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=tplichm">Итоги тура</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=tplicup">Итоги плей-офф тура</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=files&amp;file=tplrev">Шапка обзора</a></li>
                    </ul>
                </li>
<?php
      }
      else if ($role == 'pressa') {
?>
                <li class="active">
                    <a href="#mailSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Рассылка</a>
                    <ul class="collapse list-unstyled" id="mailSubmenu">
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=email";">Выбранным игрокам</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;m=maillist">Пресс-релиз</a></li>
                        <li><a href="?a=<?=$a?>&amp;s=<?=$s?>&amp;t=<?=$t?>&amp;m=maillist&amp;file=review">Обзор</a></li>
                    </ul>
                </li>
<?php
      }
?>
            </ul>
<?php
    }
?>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">
            <header class="header">
                <h3><?=$description?></h3>
            </header>
            <nav class="navbar navbar-expand-lg navbar-lignt bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="navbar-btn<?=($sidebar_show ? ' active' : '')?>">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button type="button" id="rightbarCollapse" class="navbar-btn">
                        <div id="rightbarIconUser"><i class="fas fa-user"></i></div>
                        <div id="rightbarIconUserX"><i class="fas fa-user-times"></i></div>
                    </button>
                    <button type="button" id="navbarFlag" class="navbar-flag" style="background: url(images/63x42/<?=$a?>.png) no-repeat; background-size: 100%; display:none" onClick="location.href='?a=<?=$a?>'">
                    </button>
                    <button class="btn btn-light d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarMyTours" aria-controls="navbarMyTours" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarMyTours">
                        <ul class="nav navbar-nav ml-auto">
<?php
  if (isset($_SESSION['Coach_name'])) {
    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc'))
      build_personal_nav();

    include($data_dir . 'personal/'.$_SESSION['Coach_name'].'/navbar.inc');
  }
  echo '
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="main">';
  include ($a . '/' . $m . '.inc.php');
  if ($gb_status == 'on')
    include 'comments/main.php';

  include ('fifa/register.inc.php'); // регистрация в сборных ассоциаций
?>
            </div>
        </div>

    </div>

    <footer class="footer">
        <p>
            <img src="/images/sfp-88x31.png" width="88" height="31" alt="SFP Button" title="SFP Button" />
            <a href="http://www.livescore.in/" title="Livescore.in" target="_blank"><img src="https://advert.livescore.in/livescore_in_88x31a.gif" width="88" height="31" border="0" alt="LIVESCORE.in" /></a>
            <a href="http://profi-prognoza.ru/" target="_blank"><img src="images/bannerprofi.gif" border="0" width="88" height="31" alt="Сайт cпортивного прогнозирования \"Профессионалы прогноза\"" /></a>
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
