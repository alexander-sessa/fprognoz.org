<?php
$time_start = microtime(true);
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

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

function fp_auth($cc, $name, $password) {
  global $pwd_db;
  global $ahq_db;
  foreach ($pwd_db[$name] as $hash) {
    if ($hash && md5($password) == $hash) {
      if (isset($ahq_db[$cc][$name]))
        return $ahq_db[$cc][$name];
      else
        return 'player';

    }
  }
  return 'badlogin';
}

function current_season($y, $m) {
  if ($m < 8) $y--;
  return $y . '-' . (substr($y, 2) + 1);
}

function build_personal_nav() {
  global $_SESSION;
  global $ccn;
  global $cmd_db;
  global $usr_db;
  global $data_dir;
  global $online_dir;
  $debug_str = '';
  $currentTime = time();
  if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/nav.inc')
  || !isset($_SESSION['Next_Event'])
  || $_SESSION['Next_Event'] <= $currentTime) {
    $statusColor = array('0' => 'none', '1' => 'toolate', '2' => 'alarm', '3' => 'absent', '4' => 'playing', '5' => 'present', '6' => 'result');
    $tudb = array();
    $out = '';
    $nextEvent = $currentTime + 300;
//    $startTime = $currentTime - 259200; // - 3 day
    $startTime = $currentTime - 518400; // - 6 day
    $startDay = date('d', $startTime);
    $startMonth = date('m', $startTime);
    $startYear = date('Y', $startTime);
    $sched[0] = "$startYear/$startMonth";
    $sched[1] = ($startMonth == 12) ? ($startYear + 1)."/01" : sprintf("%4d/%02d", $startYear, $startMonth + 1);
    $currentSeason = current_season($startYear, $startMonth);
    $world = file_get_contents($online_dir . 'WL/'.$startYear.'/codes.tsv');
//    $sfp20 = file_get_contents($online_dir . 'IST/'.$startYear.'/codes.tsv');
    $world_title = true;
    for ($nm=0; $nm <= 1; $nm++) {
      $dir = scandir($online_dir . 'schedule/'.$sched[$nm]);
      foreach ($dir as $fname) if ($fname[0] != '.' && ($nm || $fname >= $startDay)) {
        $subdir = scandir($online_dir . 'schedule/'.$sched[$nm].'/'.$fname);
        foreach ($subdir as $event) if ($event[0] != '.' && !strpos($event, '.resend')) {
          list($timeStamp, $countryCode, $tourCode, $action) = explode('.', $event);
//if ($_SESSION['Coach_name'] == 'Alexander Sessa') echo $event."<br />\n";
// World
          if ($countryCode == 'WL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false) {
            if ($world_title) {
              $world_title = false;
              $out .= '
<br />
Мировая лига
<br />';
            }
            $tour_dir = $online_dir . 'WL/'.$startYear.'/prognoz/'.$tourCode;
            if (is_file($tour_dir.'/published'))
              $status = 6; // завершён
            else if (is_file($tour_dir.'/closed'))
              $status = 4; // играется
            else if (strpos(file_get_contents($tour_dir.'/mail'), strtr($_SESSION['Coach_name'], ' ', '_')) !== false)
              $status = 5; // есть прогноз
            else
              $status = ($timeStamp <= $currentTime + 86400) ? 2 : 3; // нет прогноза

            $out .= '<a href="/?a=world&s='.$startYear.'&l=S&t='.substr($tourCode, 3).'&m='.($status == 6 ? 'result' : 'prognoz').'" class="'.$statusColor[$status].'">'.$tourCode.'</a> ';
          }
/*
          if ($countryCode == 'IST' && $action == 'remind' && strpos($sfp20, $_SESSION['Coach_name']) !== false) {
            if ($world_title) {
              $world_title = false;
              $out .= '
<br />
SFP - 20 ЛЕТ!
<br />';
            }
            $tour_dir = $online_dir . 'IST/'.$startYear.'/prognoz/'.$tourCode;
            foreach ($usr_db[$_SESSION['Coach_name']] as $team_code)
              if (strpos($team_code, '@IST'))
                $code = substr($team_code, 0, -4);

            if (is_file($tour_dir.'/published'))
              $status = 6; // завершён
            else if (is_file($tour_dir.'/closed'))
              $status = 4; // играется
            else if (strpos(file_get_contents($tour_dir.'/mail'), $code) !== false)
              $status = 5; // есть прогноз
            else
              $status = ($timeStamp <= $currentTime + 86400) ? 2 : 3; // нет прогноза

            $out .= '<a href="/?a=sfp-20&s='.$startYear.'&t='.substr($tourCode, 3).'&m='.($status == 6 ? 'result' : 'prognoz').'" class="'.$statusColor[$status].'">'.$tourCode.'</a> ';
          }
*/
          if ($countryCode == 'SFP')
            $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/'.substr($tourCode, 0, 3).'/it'.substr($tourCode, -2);
          elseif ($tourCode[4] == 'L')
            $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/'.substr($tourCode, 0, 5).'/itc'.substr($tourCode, -2);
          else
            $itFName = $online_dir.$countryCode.'/'.$currentSeason.'/publish/it'.strtolower(substr($tourCode, 3));

          $uefaflag = 0;
          $tour_dir = $online_dir.$countryCode.'/'.$currentSeason.'/prognoz/'.$tourCode;
          if ($countryCode != 'WL' && $countryCode != 'IST')
            foreach ($usr_db[$_SESSION['Coach_name']] as $team_str)
              if (($cut = strpos($team_str, '@'.$countryCode)) && $cut != 1 && !strpos($team_str, '@FCL')) {

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
              if (!strpos($content, $cmd_db[$team_str]['cmd'])) {
                if ($tourCode[4] != 'L')
                  $tudb[$team_str][$tourCode] = 0; // 0 - неучастие

              }
              elseif (is_file($tour_dir.'/closed')) {
                $team_str1 = ($countryCode == 'SFP') ? $cmd_db[$team_str]['cmd'] : $team_str;
                $content = file_get_contents($tour_dir.'/mail');
                if (strpos($content, substr($team_str1, 0, $cut).';') === false) {
                  if (is_file($tour_dir.'/adds')) {
                    $content = file_get_contents($tour_dir.'/adds');
                    if (strpos($content, $cmd_db[$team_str]['cmd'].' ') === false)
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
                  $team_str1 = $cmd_db[$team_str]['cmd'];
                else
                  $team_str1 = $team_str;

                $content = is_file($tour_dir.'/mail') ? file_get_contents($tour_dir.'/mail') : '';
                if ((strpos($content, substr($team_str, 0, $cut).';') === false)
                && (strpos($content, $team_str1.';') === false)) {
                  if (is_file($tour_dir.'/adds'))
                    $content = file_get_contents($tour_dir.'/adds');
                  else
                    $content = '';

                  if (strpos($content, $cmd_db[$team_str]['cmd'].' ') === false) {
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
    $prev_fp = '';
    foreach ($usr_db[$_SESSION['Coach_name']] as $team_str) if (!strpos($team_str, '@FCL')) {
      $c = substr($team_str, 0, strrpos($team_str, '@'));
      if ($c != 'I' && isset($cmd_db[$team_str])) {
        if ($cmd_db[$team_str]['ccn'] == 'SFP') //
          $ll = 'Сборная';
        else
          $ll = $cmd_db[$team_str]['cmd']; //

        if (substr($out, -7) != '<br />
') $out .= '<br />';
        if ($prev_fp == 'SFP'
        || ($prev_fp != 'UEFA' && $cmd_db[$team_str]['ccn'] == 'UEFA')
        || $cmd_db[$team_str]['ccn'] == 'FIN'
        || ($prev_fp != 'FIN' && $cmd_db[$team_str]['ccn'] == 'SBN'))
          $out .= '<img src="images/greydot.png" width="116" height="1" alt="" /><br />';

        if ($cmd_db[$team_str]['ccn'] != 'WL' && $cmd_db[$team_str]['ccn'] != 'IST' && trim($ll))
          $out .= '<a href="/?a='.strtolower($ccn[$cmd_db[$team_str]['ccn']]).'&c='.$c.'">'.$ll.' ('.$cmd_db[$team_str]['ccn'].')</a>
';
        $prev_fp = $cmd_db[$team_str]['ccn'];
        $tout = '';
        $i = 0;
        $br = 0;
//         if ($status > 0)
        if (isset($tudb[$team_str])) foreach ($tudb[$team_str] as $tcode => $status) {
          if ($i == 0 && $br == 0) {
            $br = 1;
            $tout .= '<br />
';
          }
          if (strlen($tcode) > 3 && $tcode[4] == 'L') {
            $cclen = 5;
            $ll = '&l='.substr($tcode, 0, $cclen);
          }
          elseif ($cmd_db[$team_str]['ccn'] == 'SFP') {
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

          if ($ll != '&') {
            if ($status != 0 || $cmd_db[$team_str]['ccn'] != 'SFP') {
              $br = 0;
              $tout .= '<a href="/?a='.strtolower($ccn[$cmd_db[$team_str]['ccn']]).'&c='.$c.$ll.'&s='.$currentSeason.'&m='.$linktext.'&t='
                    . strtolower(substr($tcode, $cclen)) .'" class="'.$statusColor[$status].'">'.$tcode.'</a>
';
              if (($i += 1 + strlen($tcode)) > 15)
                $i = 0;

           }
           else
             $i = -1;

          }
        }
        if ($tout)
          $out .= $tout;

      }
    }
    file_put_contents($data_dir . 'personal/'.$_SESSION['Coach_name'].'/nav.inc', $out);
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
      if (is_file($data_dir . 'personal/' . $name . '/nav.inc'))
        unlink($data_dir . 'personal/' . $name . '/nav.inc');

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
// "вездеход" для руководящего состава
$add = 'I;FIFA;UEFA;Alexander Sessa;;;president;
I;FIFA;FIFA;Eugeny Gladyr;;;president;
I;FIFA;UEFA;Eugene Plugin;;;president;
';
  global $ccn;
  global $data_dir;
  global $online_dir;
  $access = '';
  foreach ($ccn as $ccc => $cname) if ($ccc != 'SBN' && $ccc != 'FIFA') {
    $dir = scandir($online_dir.$ccc, 1);
    foreach ($dir as $s)
      if ($s[0] == '2')
        break;

    $codes = file($online_dir . $ccc . '/' . $s . '/codes.tsv');
    foreach ($codes as $line) if ($line = trim($line)) {
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
  file_put_contents($data_dir . 'auth/.access', $access.$add);
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
  $matches = json_decode($json, true);
  $update = false;
  $year = date('Y', time());
  $m = date('m', time());
  $d = date('d', time());
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
    foreach ($matches as $match) {
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
  $updates = json_decode($update, true);
  $date = sprintf('%02d-%02d', trim($month), trim($day));
  $year = date('Y', time());
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
  unset($calfp[1]);
  unset($calfp[0]);
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
'UEFA' => 'UEFA',
'FIN' => 'Finland',
'SBN' => 'SBN',
'FIFA' => 'FIFA',
'FCL' => 'Friendly',
'WL' => 'World',
'IST' => 'SFP-20',
);
session_save_path('/var/lib/php/sessions');
$cookie = 'fprognozmain';
$role = 'badlogin';
$debug_str = '';
if (isset($_POST['matches']) || isset($_POST['updates']) || isset($_POST['mtscores']))
  while(list($k,$v)=each($_POST)) $$k=$v;
else
  while(list($k,$v)=each($_GET)) $$k=$v;

if (!isset($a))
  $a = 'fifa';
else if (!is_dir($a)) {
  http_response_code(404);
  $a = 'fifa';
  $m = '404';
}
include ("$a/settings.inc.php");
if (!isset($s)) $s = $cur_year;
if (isset($m)) {
  if (!is_file($a . '/' . $m . '.inc.php')) {
    http_response_code(404);
    $a = 'fifa';
    $m = '404';
  }
  else if ($m == 'prognoz' && $s != $cur_year)
    $m = 'main';
}
else {
  if ($a == 'fifa' && isset($_COOKIE[$cookie]))
    $m = 'news';
  else {
    $m = 'main';
    if ($a == 'fifa')
      setcookie($cookie, '1');

  }
}
if (isset($ls))
  setcookie('fprognozls', $ls);

$access = file($data_dir . 'auth/.access');
$cmd_db = array(); // основная база команд
$usr_db = array(); // соответствие игроков командам и наоборот
$pwd_db = array(); // используется только для проверки пароля в fp_auth()
$ahq_db = array(); // президенты и пресса
/*
Для аутентификации по e-mail добавить установку имени с выбором из вариантов полного имени
*/
foreach ($access as $access_str) {
  list($cd, $cc, $cm, $nm, $em, $pw, $rl) = explode(';', $access_str);
  $cmd_db[$cd.'@'.$cc] = ['ccn' => $cc, 'cmd' => $cm, 'usr' => $nm, 'eml' => $em, 'rol' => $rl];

  $usr_db[$nm][] = $cd.'@'.$cc;       // связь: игрок - его команды
  $usr_db[$cd][] = $nm;               // обратная связь: код команды - игрок
  if (trim($em))
    $usr_db[$em][] = $nm; // привязка имен игрока к e-mail

  $pwd_db[$cd][] = $pw;
  $pwd_db[$nm][] = $pw;
  $pwd_db[$em][] = $pw;

  if (trim($rl) && $rl[1] == 'r')
    $ahq_db[$cc][$cd] = $rl; // pResident or pRessa

}
$notification = '';
if (isset($_COOKIE['fprognozls']))
  $fprognozls = $_COOKIE['fprognozls'];
else
  $fprognozls = 'inscore';

if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
else
  $ip = $_SERVER['REMOTE_ADDR'];

session_start();
if (isset($_POST['logout'])) {
  $role = 'badlogin';
  session_unset();
  session_destroy();
}
if (isset($_POST['pass_str']) && isset($_POST['name_str'])) { // autentification
/*
  if (strpos(trim($_POST['name_str']), '@')) {
    $role = fp_auth($cca, $_POST['name_str'], $_POST['pass_str']);
// здесь установить $coach_name
  }
  else 
*/
  if (strpos(trim($_POST['name_str']), ' ')) {
    $coach_name = ucwords($_POST['name_str']);
    $role = fp_auth($cca, $coach_name, $_POST['pass_str']);

  }
  else
    foreach ($usr_db[trim($_POST['name_str'])] as $coach_name) {
      $role = fp_auth($cca, $coach_name, $_POST['pass_str']);
      if ($role != 'badlogin')
        break;

  }
  if ($role != 'badlogin') {
    $_SESSION['Coach_name'] = $coach_name;
    $_SESSION['Session_password'] = $_POST['pass_str'];
    $debug_str = build_personal_nav();
  }
  else { // check if should send new password
    foreach ($usr_db[trim($_POST['name_str'])] as $team_str) {
      $ta = explode('@', $team_str);
      if ($ta[0] == $_POST['pass_str']) {
        $gp = '';
        $mix = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
        for ($i=0; $i<8; $i++)
          $gp .= $mix[rand(0,56)];

        $team_codes = '';
        foreach ($usr_db[trim($_POST['name_str'])] as $team_str) {
          if ($team_codes)
            $team_codes .= ', ';

          $ta = explode('@', $team_str);
          $team_codes .= $ta[0];
          file_put_contents($online_dir.$ta[1].'/passwd/'.$ta[0], md5($gp).":player");
        }
        send_email('FPrognoz.org <fp@fprognoz.org>', $_POST['name_str'], $cmd_db[$team_str]['eml'],
                   'Password for FPrognoz.org',
'Team code(s) = '.$team_codes.'
Password = '.$gp.'
');
        build_access();
        $notification = 'Пароль выслан';
        break;
      }
    }
    if (!$notification)
      $notification = 'Ошибка входа';

  }
}
else { // restore session
  if (isset($_SESSION['Session_password'])) {
    $coach_name = $_SESSION['Coach_name'];
    build_personal_nav();
    if (isset($cca)) $_SESSION['Country_code'] = $cca;
      else $cca = '';

    if ($coach_name == 'Alexander Sessa')
      $role = 'president';
    else
      $role = fp_auth($cca, $_SESSION['Coach_name'], $_SESSION['Session_password']);
// confirm
    if (isset($team)) $_SESSION['Session_team'] = $team;
//    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/'.date('Y', time()))) {
//      $a = 'fifa';
//      $m = 'confirm';
//    }
  }
  else {
    $role = 'badlogin';
    session_unset();
  }
}

if (isset($_SESSION['Coach_name'])) {
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
        file_get_contents("https://fprognoz.org/?a=sfp-team&l=$l&s=$s&m=prognoz&t=$t&renew=1&n=$n");

      unlink($lock);
    }
    else {                 // иначе ждём пока его обновят; это ajax - можно ждать несколько секунд
      $timer = 50000;
      while ($timer-- && is_file($lock)) time_nanosleep(0, 1000);
    }
  }
  else if ($a == 'world') {
    $lock = $online_dir . 'log/renew.' . $l;
    if (!is_file($lock)) { // если первый, обновляем календарь
      touch($lock);
      $matches = 7;
      for ($nm=1; $nm<=$matches; $nm++)
        file_get_contents("https://fprognoz.org/?a=world&l=S&s=2018&m=prognoz&t=$t&renew=1&n=$n");

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
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <meta name="description" content="SFP">
  <meta name="author" content="Alexander Sessa">
  <link href="css/main.css" rel="stylesheet">
  <link href="css/popup.css" rel="stylesheet">
  <link href="css/fa/css/font-awesome.min.css" rel="stylesheet">
  <link href="js/croppic/croppic.css" rel="stylesheet">
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
  <script src='/js/jquery/jquery-3.2.1.min.js'></script>
  <script src='/js/jquery/jquery.color.js'></script>
  <script src='/js/socket.io/socket.io.slim.js'></script>
<script>
$.browser = {};
$.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
$.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
$.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
$.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
$(document).ready(function(){
  $("a[name=modal]").click(function(e){e.preventDefault();$('.overlay').fadeTo("fast",0.65);$("#mwin").addClass("popup-show")});
  $(".popup .close,.overlay").click(function(e){e.preventDefault();$(".overlay").hide();$("#mwin").removeClass("popup-show")});
});
</script>
</head>
<body>
<div class="overlay"></div>
<center>
<table cellspacing="0" cellpadding="0" border="0" width="<?=1216?>px">

<tr style="height: 110px;">
  <td width="140px" class="head">
    <a href="/" class="side"><img src="/images/sfp-100.png" alt="Main Page" /></a>
  </td>
  <td width="936px" class="head">
    <a href="/?a=<?=$a?>" class="head"><br />
    &nbsp;<?=$description?></a>
    <?php if ($role == 'president' || $role == 'pressa') include script_from_cache("$a/$role.inc.php");?>
  </td>
  <td width="140px" class="head">
    <a href="/?a=uefa"><img src="/images/uefa.gif" alt="UEFA" /></a>
    <a href="/?a=ukraine"><img src="/images/ukraine.gif" alt="UKR" /></a>
    <a href="/?a=russia"><img src="/images/russia.gif" alt="RUS" /></a>
    <a href="/?a=belarus"><img src="/images/belarus.gif" alt="BLR" /></a>
    <br />
    <a href="/?a=england"><img src="/images/england.gif" alt="ENG" /></a>
    <a href="/?a=germany"><img src="/images/germany.gif" alt="GER" /></a>
    <a href="/?a=spain"><img src="/images/spain.gif" alt="ESP" /></a>
    <a href="/?a=italy"><img src="/images/italy.gif" alt="ITA" /></a>
    <br />
    <a href="/?a=netherlands"><img src="/images/netherlands.gif" alt="NLD" /></a>
    <a href="/?a=portugal"><img src="/images/portugal.gif" alt="PRT" /></a>
    <a href="/?a=france"><img src="/images/france.gif" alt="FRA" /></a>
    <a href="/?a=scotland"><img src="/images/scotland.gif" alt="SCO" /></a>
    <br />
    <a href="/?a=turkey"><img src="/images/turkey.gif" alt="TUR" /></a>
    <a href="/?a=austria"><img src="/images/austria.gif" alt="AUT" /></a>
    <a href="/?a=belgium"><img src="/images/belgium.gif" alt="BEL" /></a>
    <a href="/?a=greece"><img src="/images/greece.gif" alt="GRC" /></a>
    <br />
    <a href="/?a=finland"><img src="/images/finland.gif" alt="FIN" /></a>
    <a href="/?a=world"><img src="/images/fifa23x15.png" alt="WORLD" /></a>
    <a href="/?a=sfp-team"><img src="/images/sfp23x15.png" alt="SFP" /></a>
    <a href="/?a=friendly"><img src="/images/fcl23x15.png" alt="FCL" /></a>
    <br />
  </td>
</tr>

<tr>
  <td align="center" height="100%" class="side">
    &nbsp;<?php include script_from_cache("$a/left.inc.php");?>
  </td>
  <td align="left" class="main">
    <?php include script_from_cache("$a/$m.inc.php");?>
    <?php if ($gb_status == 'on') include 'comments/main.php';?>
<?php
include ('fifa/register.inc.php');
?>
  </td>
  <td valign="top" class="side">
    <br /><br />
    <?php include script_from_cache("$a/menu.inc.php");?>
    <br />
    <form method="post" action="">
<?php if (!isset($_SESSION['Coach_name']) || $role == 'badlogin') {?>
    <p>&nbsp;имя, e-mail или код:<br /><input type="text" name="name_str" size="12" /></p>
    <p>&nbsp;пароль:<br /><input type="password" name="pass_str" size="12" /></p>
    <br />
    <p><input type="submit" name="login" value="Вход" /></p>
    <br />
<?php if ($notification) echo "<p>$notification</p>"; else {?>
    <p class="text11">У Вас нет пароля?<br />
Укажите полное имя,<br />
а в поле пароля -<br />
код вашей команды.<br />
После нажатия кнопки<br />
"Вход" пароль будет<br />
выслан на ваш емейл</p>
<?php }
    } else {?>
    <p>Добро пожаловать,<br /><span style="color: lightgreen;"><b><?=$_SESSION['Coach_name']?></b></span></p>
    <br />
    <p>Ваши команды/туры:</p>
    <p>
<?php
  if (isset($_SESSION['Coach_name'])) {
    if (!is_file($data_dir . 'personal/'.$_SESSION['Coach_name'].'/nav.inc'))
      build_personal_nav();

    include script_from_cache($data_dir . 'personal/'.$_SESSION['Coach_name'].'/nav.inc');
  }?>
    </p>
    <br />
    <br />
    <p>&nbsp;&nbsp;<a href="/?m=pass">Сменить пароль</a></p>
    <br />
    <p><input type="submit" name="logout" value="Выход" /></p>
<?php } ?>
    <br />
    </form>
  </td>
</tr>

<tr style="height: 110px;">
  <td class="foot">
    <img src="<?=$left_image?>" height="100" alt="" />
  </td>
  <td class="foot">
<img src="/images/sfp-88x31.png" width="88" height="31" alt="SFP Button" title="SFP Button" />
<a href="http://www.livescore.in/" title="Livescore.in" target="_blank"><img src="https://advert.livescore.in/livescore_in_88x31a.gif" width="88" height="31" border="0" alt="LIVESCORE.in" /></a>
<a href="http://primegang.ru/" target="_blank"><img src="images/primegang.gif" border="0" width="88" height="31" alt="Клуб футбольных прогнозистов PrimeGang" /></a>
<?php if (false && $is_redis) echo '<img src="https://redis.io/images/redis-small.png" border="0" alt="Redis" title="Powered by Redis" />'; ?>
    <br /><br />
    Site Design: Alexander Sessa, Serge Vasiliev. Elapsed time <?=round(microtime(true) - $time_start, 3)?> sec
  </td>
  <td class="foot">
    <img src="<?=$rght_image?>" height="100" alt="" />
  </td>
</tr>

</table>
</center><form id="gb_toggle" method="post"><input type="hidden" name="toggle_gb" value="" /></form><?=$debug_str?>
</body>
</html>
<?php
}
if ($is_redis) $redis->close();
?>
