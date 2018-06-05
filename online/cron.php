#!/usr/bin/php
<?php
/*
    CRON - развитие и объединение скрипта автоматизации рассылок и управления приемом прогнозов (SCHEDULER)
    с другими периодическими скриптами.

    Сканирование мейл-бокса и парсинг свежих результатов выполняются безусловно каждую минуту перед обработкой событий.
    Функция парсера возвращает массив базы матчей, который впоследствии используется шедулером.
    Парсинг долговременных календарей и расписаний матчей на ближайший месяц выполняются дополнительными скриптами
    раз в сутки в ночное время, причем в разные часы, поскольку оба процесса длительны.

    структура каталогов: год (4 цифры) / месяц (2) / день (2)
    событие = файл с именем <timestamp>.<cca>.<tour>.<event>, в файле могут быть данные
    просмотр событий - каждую минуту

resend  повторная рассылка программки       -4 д до даты в программке
remind  рассылка напоминание опаздывающим   +10 ч
monitor проверка начала реальных матчей     с уточнением во время контрольного срока и далее через каждую минуту до начала;
                                            после начала не более 3 матчей из осн. части уточнить начало следующего матча и
                                            далее через каждые 5 минут до начала очередного, записывая число начавшихся;
                                            после начала 4 матчей из осн. части (+ доп с учетом отмен), закончить, если
                                            правилами турнира не предусмотрено другое.
-       контрольный срок для monitor        +12 ч
        публикация прогнозов                факт начала первого матча из программки (monitor)
        закрытие формы приема прогнозов     факт начала 4-го матча из основной части программки* (monitor)

        формат файла monitor-а:
число   - количество начавшихся матчей, для обычных ассоциаций при значении больше 3 закрываем форму приема прогнозов и прекращаем мониторить
15 строк с информацией о матчах, взятых из results, в виде: "хозяева,гости,минута".
*Формат подлежит проверке в момент уточнения: при нарушении рапортовать емейлом.

-       специальные действия монитора:      фиксировать окончание матчей для перестройки файлов результатов турниров Мастер-серии;
                                            фиксировать начало начало первого матча для парсинга туров на других сайтах МС;
                                            фиксировать начало второго тайма для ФП Финляндии;
*                                            фиксировать окончание тура и построение итогов (без рассылки);
-       функция монитора:                   выполнить функцию при заданном событии (см. выше). выполняется вторым скриптом
*/

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Berlin');
$online_dir = '/home/fp/data/online/';

$subjects = array(
'BLR' => 'ФП. Беларусь.',
'ENG' => 'ФП. Англия.',
'ESP' => 'ФП. Испания.',
'FIN' => 'ФП. Финляндия.',
'FRA' => 'ФП. Франция.',
'GER' => 'ФП. Германия.',
'ITA' => 'ФП. Италия.',
'NLD' => 'ФП. Голландия.',
'RUS' => 'ФП. Россия.',
'PRT' => 'ФП. Португалия.',
'SBN' => 'ФП. SBNet.',
'SCO' => 'ФП. Шотландия.',
'UKR' => 'ФП. Украина.',
'UEFA' => 'ФП. Лиги УЕФА.',
'SFP' => 'ФП. Сборная SFP.',
'WL' => 'ФП. Мировая Лига.',
'IST' => 'ФП. Турнир "SFP - 20 ЛЕТ!"',
);

$ccn = array(
'ENG' => 'england',
'BLR' => 'belarus',
'GER' => 'germany',
'NLD' => 'netherlands',
'ESP' => 'spain',
'ITA' => 'italy',
'PRT' => 'portugal',
'RUS' => 'russia',
'UKR' => 'ukraine',
'FRA' => 'france',
'SCO' => 'scotland',
'FIN' => 'finland',
'SBN' => 'sbn',
'UEFA' => 'uefa',
'CHA' => 'uefa',
'CUP' => 'uefa',
'GOL' => 'uefa',
'UEF' => 'uefa',
'SFP' => 'sfp-team',
'FFP' => 'sfp-team',
'FWD' => 'sfp-team',
'PRE' => 'sfp-team',
'PRO' => 'sfp-team',
'SPR' => 'sfp-team',
'SUP' => 'sfp-team',
'TOR' => 'sfp-team',
'FCL' => 'friendly',
'WL' => 'world',
'WLS' => 'world',
'IST' => 'sfp-20',
);

function get_absent_mails($country_code, $tour) {
  global $online_dir;
  $sentto = array();
  $cca_home = $online_dir . $country_code;
  $dir = scandir($cca_home);
  foreach ($dir as $subdir) if ($subdir[0] == '2') $season = $subdir;
  $season_dir = $cca_home . '/' . $season;
  $tour_dir = $season_dir . '/prognoz/' . $tour;
  if (is_file($tour_dir . '/mail')) {
    $mbox = file($tour_dir . '/mail');
    $have = array();
    foreach ($mbox as $line) if ($line = rtrim($line))
      $have[] = strtoupper(substr($line, 0, strpos($line, ';')));

    if (is_file($tour_dir . '/adds')) {
      $added = file($tour_dir . '/adds');
      foreach ($added as $line) if ($line = rtrim($line)) if ($line[0] != ' ')
        $have[] = trim(substr($line, 0, 20));

    }
    // parse program
    $programm = file_get_contents($season_dir . '/programms/' . $tour);
    $programm = substr($programm, strpos($programm, "Последний с"));
    $playteams = array();
    $calfp = explode("\n", $programm);
    foreach ($calfp as $line)
    if (strpos($line, ' - ') && !strpos($line, 'ГОСТИ') && !strpos($line, 'Гости')) {
      $line = trim($line);
      if ($cut = strpos($line, '  ')) {
        $match = trim(substr($line, 0, $cut));
        if (strpos(substr($line, $cut), ' - ')) {
          $match2 = trim(substr($line, $cut));
          $cut = strpos($match2, ' - ');
          $playteams[] = substr($match2, 0, $cut);
          $playteams[] = substr($match2, $cut + 3);
        }
      }
      else $match = trim($line);
      $cut = strpos($match, ' - ');
      $playteams[] = substr($match, 0, $cut);
      $playteams[] = substr($match, $cut + 3);
    }
    // diff team lists and send
    $acodes = file($season_dir . '/codes.tsv');
    foreach ($acodes as $scode) if ($scode[0] != '#' && $scode[0] != '-') {
      list($code, $team, $name, $email) = explode('	', $scode);
      if (in_array($team, $playteams))
        if (!in_array($code, $have) && !in_array($team, $have) && !in_array(strtoupper($team), $have))
          if (!in_array($name . '#' . $email, $sentto))
            $sentto[] = $name . '#' . $email;

    }
  }
  return $sentto;
}

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

function send_to_all($country_code, $subj, $body) {
  global $online_dir;
  $senders = array(
'BLR' => '"PFL of Belarus" <blr@fprognoz.org>',
'ENG' => '"FPL of England" <eng@fprognoz.org>',
'ESP' => '"FPL of Spain" <esp@fprognoz.org>',
'FRA' => '"PFL of France" <fra@fprognoz.org>',
'GER' => '"PFL of Germany" <ger@fprognoz.org>',
'ITA' => '"PFL of Italy" <itl@fprognoz.org>',
'NLD' => '"PFL of Netherlands" <nld@fprognoz.org>',
'RUS' => '"PFL of Russia" <rus@fprognoz.org>',
'PRT' => '"PFL of Portugal" <prt@fprognoz.org>',
'SBN' => '"PFL of SBNet" <sbn@fprognoz.org>',
'SCO' => '"PFL of Scotland" <sco@fprognoz.org>',
'UKR' => '"PFL of Ukraine" <ukr@fprognoz.org>',
'UEFA' => 'UEFA <uefa@fprognoz.org>',
'SBN' => '"AFL of SBNet" <sbn@fprognoz.org>',
'FIN' => '"AFL of Finland" <fin@fprognoz.org>',
'SFP' => '"SFP-Team" <sfp@fprognoz.org>',
'WL' => '"World League" <wl@fprognoz.org>',
'IST' => '"SFP - 20!" <sfp@fprognoz.org>',
);
  $from = $senders[$country_code];
  $dir = scandir($online_dir . $country_code);
  foreach ($dir as $subdir) if ($subdir[0] == '2') $season = $subdir;
  $acodes = file($online_dir . "$country_code/$season/codes.tsv");
  $sentto = array();
  $emails = '';
  foreach ($acodes as $scode) if ($scode[0] != '#' && $scode[0] != '-') {
    $ateams = explode('	', $scode);
    $name = trim($ateams[2]);
    $email = trim($ateams[3]);
    if (!in_array($name, $sentto)) {
      $sentto[] = $name;
      $tarr = explode(',', $email);
      foreach ($tarr as $email) $emails .= $name . ' <' . trim($email) . '>, ';
    }
  }
  if ($emails = rtrim($emails, ', ')) send_email($from, '', $emails, $subj, $body);
}

function Schedule($timestamp, $country_code, $tour_code, $action, $pfname) {
  global $online_dir;
  $dir = $online_dir . 'schedule/'.date('Y/m/d', $timestamp);
  if (!is_dir($dir))
    mkdir($dir, 0755, true);

  if ($country_code == 'UEFA' && $tour_code[0] == 'G' && $action == 'monitor')
    file_put_contents($dir.'/'.($timestamp - 1).'.'.$country_code.'.'.$tour_code.'.'.$action, $pfname);
  else
    file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.$tour_code.'.'.$action, $pfname);

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

function pr_validate($pr) {
  $valid = true;
  $t = [];
  $t[1] = substr($pr, 0, 9);
  $t[2] = substr($pr, 9);
  for ($i = 1; $i < 3; $i++)
    if (strlen(count_chars($t[$i], 3)) != 9) {
      $valid = false;
    }

  return $valid;
}

function build_prognozlist($country_code, $season, $tour) {
  global $online_dir;
  $teams = array();
  $maxteam = 0;
  $acodes = file($online_dir . "$country_code/$season/codes.tsv");
  foreach ($acodes as $scode) if ($scode[0] != '#') {
    $ateams = explode('	', ltrim($scode, '-'));
    $team = ($country_code == 'WL' || $country_code == 'IST') ? $ateams[0].' ('.$ateams[1].')' : $ateams[1];
    $teams[$ateams[0]] = $team;
    $maxteam = max($maxteam, mb_strlen($team));
  }
  $prognozlist = '';

  if ($country_code == 'WL' || $country_code == 'IST') {
    $path = $online_dir . "$country_code/$season/";
    $prognoz_dir = $path . 'prognoz/' . $tour . '/';
    $players = [];
    foreach ($acodes as $line) if (trim($line)) {
      list($code, $cc, $rest) = explode('	', $line, 3);
      $players[$code] = $cc;
    }

    $predicts = [];
    $mail = file($prognoz_dir . 'mail');
    foreach ($mail as $line) {
      list($code, $predict, $rest) = explode(';', $line, 3);
      $predicts[$code] = $predict;
    }

    $all_predicts = [];
    foreach ($predicts as $code => $predict) {
      $predict = strtr($predict, [' ' => '']);
      if (pr_validate($predict))
        $all_predicts[$players[$code]][$code] = $predict;
      else
        $prognozlist .= "Ошибка в прогнозе от $code: $predict\n";
    }
    $generators = [];
    $gen = file($path . 'gen');
    $t = ltrim(substr($tour, 3), 0);
    for ($i = 0; $i < 20; $i++)
      $generators[$i] = trim($gen[($t - 1) * 23 + 3 + $i]);

    $g = 0;

    $files = scandir($prognoz_dir);
    $participants = [];
    foreach ($files as $file) if ($file[0] != '.' && $file != 'cal' && $file != 'mail')
      $participants[] = $file;

    foreach ($participants as $cc) {
      $cc_predicts = [];
      $new = false;


     if (is_file($prognoz_dir . $cc)) {


      $cc_pr = file($prognoz_dir . $cc);
      foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
        list($code, $predict) = explode(';', $pr_line);
//      $name = strtr($code, '_', ' ');
        $code = strtr($code, ' ', '_');
        $predict = strtr($predict, [' ' => '']);
        if (isset($all_predicts[$cc][$code]) && $all_predicts[$cc][$code] != $predict) {
          $new = true;
          $cc_predicts[$code] = $all_predicts[$cc][$code];
        }
        else {
          if (pr_validate($predict))
            $cc_predicts[$code] = $predict;
          else {
            $cc_predicts[$code] = '';
            if (trim($predict))
              $prognozlist .= "Ошибка в прогнозе от $code: $predict\n";

          }
        }
      }

      $cc_pr = file($path . $cc.'.csv');
      foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
        $name = explode(';', $pr_line, 2)[0];
        $code = strtr($name, ' ', '_');
        if (!isset($cc_predicts[$code])) {
          if (isset($all_predicts[$cc][$code])) {
            $new = true;
            $cc_predicts[$code] = $all_predicts[$cc][$code];
          }
          else {
            $cc_predicts[$code] = '';
            if (trim($predict))
              $prognozlist .= "Ошибка в прогнозе от $code: $predict\n";

          }
        }
      }


     }
     else {
      $cc_pr = file($path . $cc.'.csv');
      foreach ($cc_pr as $pr_line) if (trim($pr_line)) {
        $name = explode(';', $pr_line, 2)[0];
        $code = strtr($name, ' ', '_');
        if (isset($all_predicts[$cc][$code])) {
          $new = true;
          $cc_predicts[$code] = $all_predicts[$cc][$code];
        }
        else {
          $cc_predicts[$code] = '';
          if (trim($predict))
            $prognozlist .= "Ошибка в прогнозе от $code: $predict\n";

        }
      }


     }


     // дополнительная сортировка $cc_predicts: пустышки вниз
      $n = 0;
      $out = '';
      foreach ($cc_predicts as $code => $predict) if (trim($predict)) {
        $n++;
        $out .= $code.';'.$predict.';;' . ($n <= 6 ? '1' : '') . "\n";
      }
      foreach ($cc_predicts as $code => $predict) if (!trim($predict)) {
        if ($n >= 6)
          $out .= "$code;;;\n";
        else {
          $n++;
          $out .= $code.';'.$generators[$g++].";;1\n";
        }
      }
// $log .= ' автоматическое построение списка прогнозов';
      file_put_contents($prognoz_dir . $cc, $out);
      $prognozlist .= "$cc\n\n$out\n";
    }
    touch($prognoz_dir . 'closed');
    return $prognozlist;
  }
  $mbox = file($online_dir . "$country_code/$season/prognoz/$tour/mail");
  $have = array();
  $aprognoz = array();
  foreach ($mbox as $msg) {
    if (mb_detect_encoding($msg, 'UTF-8', true) === FALSE)
      $msg = iconv('CP1251', 'UTF-8//IGNORE', $msg);
    $ta = explode(';', $msg);
    $team = $ta[0];
    $warn = '';
    if (isset($teams[$team])) $team = $teams[$team];
    else $warn = 'oЖ';
    if (in_array($team, $have)) $warn = '!!!';
    else $have[] = $team;
    $prognozlist .= $team . str_repeat(' ', 21 - mb_strlen($team)) . htmlspecialchars(sprintf('%-20s', $ta[1]))
                  . $warn . str_repeat(' ',  5 - mb_strlen($warn)) . date('d M y  H:i:s', $ta[2]) . "\n";
    if ($penalties = trim($ta[3])) $prognozlist .= '                     '.mb_strtolower($penalties)."\n";
    if (!isset($aprognoz[$team]['time']) || ($ta[2] > $aprognoz[$team]['time']))
    {
      $aprognoz[$team]['prog'] = $ta[1];
      $aprognoz[$team]['time'] = $ta[2];
      $aprognoz[$team]['pena'] = $ta[3];
      $aprognoz[$team]['warn'] = $warn;
    }
  }

  if (is_file($online_dir . "$country_code/$season/prognoz/$tour/adds")) {
    $addfile = file_get_contents($online_dir . "$country_code/$season/prognoz/$tour/adds");
    if (mb_detect_encoding($addfile, 'UTF-8', true) === FALSE)
      $addfile = iconv('CP1251', 'UTF-8//IGNORE', $addfile);
    $months = array(
    ' января' => ' Jan',' янваpя' => ' Jan',
    ' февраля' => ' Feb', ' февpаля' => ' Feb',
    ' марта' => ' Mar', ' маpта' => ' Mar',
    ' апреля' => ' Apr', ' апpеля' => ' Apr',
    ' мая' => ' May',
    ' июня' => ' Jun',
    ' июля' => ' Jul',
    ' августа' => ' Aug',
    ' сентября' => ' Sep', ' сентябpя' => ' Sep',
    ' октября' => ' Oct',  ' октябpя' => ' Oct',
    ' ноября' => ' Nov',  ' ноябpя' => ' Nov',
    ' декабря' => ' Dec', ' декабpя' => ' Dec',
    ' г.,' => '', ' г.' => '', ' inet' => ''
    );
    foreach ($months as $rus => $eng)
      $addfile = str_replace($rus, $eng, $addfile);

    $prognozlist .= "дополнения, поправки, наказания:\n";
    $added = explode("\n", $addfile);
    foreach ($added as $line) if ($line = rtrim($line)) {
      if ($line[0] != ' ') {
        $team = trim(mb_substr($line, 0, 20));
        $line = trim(mb_substr($line, 20));
        if ($cut = min(20, strpos($line, ' ', 15))) {
          $prognoz = trim(substr($line, 0, $cut));
          $line = trim(substr($line, $cut));
        }
        else {
          $prognoz = trim($line);
          $line = '';
        }
        if (($line[0] >= '0') && ($line[0] <= '9')) {
          $warn = '     ';
        }
        else {
          if (mb_strlen($line) > 4 && $line[4] == ' ') $wln = 5; else $wln = 4;
          $warn = mb_substr($line, 0, $wln);
          $line = mb_substr($line, $wln);
          $warn = str_replace('с', 'c', $warn);
          $warn = str_replace('о', 'c', $warn);
          $warn = str_replace('а', 'a', $warn);
          $warn = str_replace('K', 'К', $warn);
//          $warn = strtr($warn, 'соаK', 'coaК');
        }
        if ($time = trim(substr($line, 0, 29))) $time = strtotime(trim(substr($line, 0, 29)).' CET');
        else $time = 0;
        if ($time) $date = date('d M y  H:i:s', $time);
        else $date = '';
        $prognozlist .= $team . str_repeat(' ', 21 - mb_strlen($team)) . htmlspecialchars(sprintf('%-20s', $prognoz))
                      . $warn . str_repeat(' ',  5 - mb_strlen($warn)) . "$date\n";
        $aprognoz[$team]['time'] = $time;
        $aprognoz[$team]['prog'] = $prognoz;
        $aprognoz[$team]['warn'] = $warn;
      }
      else {
        $prognozlist .= htmlspecialchars($line)."\n";
        $aprognoz[$team]['pena'] = $line;
      }
    }
  }

  // parse program
  $programm = file_get_contents($online_dir . "$country_code/$season/programms/$tour");
  if (mb_detect_encoding($programm, 'UTF-8', true) === FALSE)
    $programm = iconv('CP1251', 'UTF-8//IGNORE', $programm);
  $fr = mb_strpos($programm, "Последний с");
  $programm = mb_substr($programm, $fr);
  list($cal, $gen) = parse_cal_and_gen($programm);
//  if (is_file("$country_code/$season/$tcode/$calfname"))
//    $calt = trim(GetTourFromCalendar(str_replace('NEW', '', $tour), file_get_contents("$country_code/$season/$tcode/$calfname")));
//  if ($calt) $cal = $calt;
  $virtmatch = array();
  $atemp = explode("\n", $cal);
  foreach ($atemp as $line) if ($line = trim($line)) {
    if ($cut = mb_strpos($line, '  '))
      $line = mb_substr($line, 0, $cut);
    $virtmatch[] = $line;
  }

  // now virtual matches
  $prognozlist .= "\n----------------------\n";
  $cnmtpl = '%-15s';
  // select generators
  $gensets = 0;
  // if no generators in the program read from gen
  if (!trim($gen)) { // prefer generators from gen file
    if ($tour[3] == 'C') $genfname = $online_dir . "$country_code/$season/genc";
    else $genfname = $online_dir . "$country_code/$season/gen";
    if (is_file($genfname)) {
      // parse generators
      $gen = file_get_contents($genfname);
      if (strpos($gen, $tour)) {
        $begin = $tour;
        $end = $country_code;
      }
      else {
        if ($tour[3] == 'C')
          $n = ltrim(substr($tour, strlen($country_code) + 1), '0');
        else
          $n = ltrim(substr($tour, strlen($country_code)), '0');
        $begin = "Тур $n";
        $end = "Тур";
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
  }

  $gensets = 1;
  if ($gen) {
    $generator = array();
    $gen = str_replace('*', '', $gen);
    $atemp = explode("\n", $gen);
    foreach ($atemp as $line) if ($line = trim($line)) {
      if ($cut = mb_strpos($line, '  ')) {
        $generator['1'][] = trim(mb_substr($line, 0, $cut));
        $line = trim(mb_substr($line, $cut));
        if ($cut = strpos($line, '  ')) {
          $generator['2'][] = trim(mb_substr($line, 0, $cut));
          $generator['3'][] = trim(mb_substr($line, $cut));
          $gensets = 3;
        }
        else {
          $generator['2'][] = trim($line);
          $gensets = 2;
        }
      }
      else
        $generator['1'][] = trim($line);

    }
  }

  // формирование таблиц виртуальных матчей
  $n = $g = 0;
  $s = 1;
  $z = sizeof($virtmatch) / $gensets;
  foreach ($virtmatch as $line) {
    $atemp = explode(' - ', $line);

    $h = trim($atemp[0]);
    if ($aprognoz[$h]['time'])
      $date = date('d M y  H:i:s', $aprognoz[$h]['time']);
    else
      $date = '';
    if (!isset($aprognoz[$h]['prog'])) {
      $aprognoz[$h]['prog'] = $generator[$s][$g];
      $aprognoz[$h]['warn'] = '*Ж';
      $date = '                   ';
      $g++;
    }
    $addline = $h . str_repeat(' ', 21 - mb_strlen($h)) . htmlspecialchars(sprintf('%-20s', $aprognoz[$h]['prog']));
    $addline .= $aprognoz[$h]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$h]['warn'])) . "$date\n";
    $prognozlist .= $addline;
    if ($date == '                   ')
      $addfile .= $addline;

    $a = trim($atemp[1]);
    if ($aprognoz[$a]['time'])
      $date = date('d M y  H:i:s', $aprognoz[$a]['time']);
    else
      $date = '';
    if (!isset($aprognoz[$a]['prog'])) {
      $aprognoz[$a]['prog'] = $generator[$s][$g];
      $aprognoz[$a]['warn'] = '*Ж';
      $date = '                   ';
      $g++;
    }
    $addline = $a . str_repeat(' ', 21 - mb_strlen($a)) . htmlspecialchars(sprintf('%-20s', $aprognoz[$a]['prog']));
    $addline .= $aprognoz[$a]['warn'] . str_repeat(' ',  5 - mb_strlen($aprognoz[$a]['warn'])) . "$date\n\n";
    $prognozlist .= $addline;
    if ($date == '                   ')
      $addfile .= $addline;

    if (++$n >= $z) {
      $g = 0;
      $s++;
      $z = sizeof($virtmatch) * $s / $gensets;
    }
  }
  file_put_contents($online_dir . "$country_code/$season/prognoz/$tour/adds", $addfile);
  return $prognozlist;
}

function lock($lock, $timeout) {
  $timer = 0;
  while (is_file($lock) && $timer++ < $timeout) time_nanosleep(0, 1000);
//  touch($lock);
  file_put_contents($lock, 'cron');
  return ($timer < $timeout);
}

function Today($year, $m, $d, $dayofweek, $minute) {
  global $online_dir;
  require_once('xscoregroups.inc.php');
  require_once('realteam.inc.php');
  $realteam1 = array();
  foreach ($realteam as $tn1 => $tn2) $realteam1[strtoupper($tn1)] = $tn2;
  $date = "$m-$d";
  ($dayofweek != 2 && $dayofweek != 5) ? $starttime = "$date 08:00" : $starttime = "$date 12:00";
  $week = date('W', strtotime("$year-$date") - 86400);
  ($week == '52' && $m == '01') ? $fname = $online_dir . 'results/'.($year - 1).'.'.$week : $fname = $online_dir . 'results/'.$year.'.'.$week;
  if (is_file($fname)) $archive = file($fname); else $archive = array();
  $base = array();
  $today = array();
  $old_seq = trim($archive[0]);
  unset($archive[0]); // remove old seq
  foreach ($archive as $line) {
    list($h,$a,$d,$s,$f,$r,$g,$i,$z) = explode(',', trim($line));
    $base[$i] = array($h,$a,$d,$s,$f,$r,$g,$i,$z);
    $today[$h.','.$a] = array($h,$a,$d,$s,$r,$r);
  }
  $update = false;
  if ($minute == 0) { // full reload hourly
    $update = true;
    $url = 'http://www.xscores.com/soccer/livescores';
    $content = file_get_contents($url);
    $seq = substr($content, 6 + strpos($content, 'seq = '), 8);
    $content = substr($content, strpos($content, '<div class="score_pen score_cell">PN</div>'));
    $content = substr($content, 0, strpos($content, "<div class='ad-line-hide gameList_ad_bottom'>"));
    $content = str_replace('&nbsp;', ' ', $content);
    $content = str_replace("\r", '', $content);
    $arr = explode('<div id="midbannerdiv">', $content);
    $data = '';
    for ($i = 0; $i <= 1; $i++)
      if ($cut = strpos($arr[$i], '<div id="1'))
        $data .= substr($arr[$i], $cut);

    $matches = explode('<div id="1', $data);
    foreach($matches as $match) if (strpos($match, ' data-') && !strpos($match, '(W)') && !strpos($match, '(U17)') && !strpos($match, '(U19)') && !strpos($match, '(U21)')) {
      $i = '1'.substr($match, 0, 6);
      $ev = substr($match, strpos($match, ' data-') + 6);
      $ev = substr($ev, 0, strpos($ev, '>')).';';
      $ev = '$'.str_replace(' data_', ';$', strtr($ev, ['-' => '_']));
      $ev = strtr($ev, [' "' => ' \"', '" ' => '\" ', '".' => '\".']);
      eval($ev);
      $g = $country_name.'#'.$league_short;
      if (isset($groups[$g])) {
        $g = $groups[$g];
        list($koh, $kom) = explode(':', $ko);
        ($koh == 0) ? $koh = 23 : $koh -= 1;
        if (strlen($koh) == 1) $koh = '0' . $koh;
        list($match_year, $date) = explode('_', $matchday, 2);
        $date = strtr($date, '_', '-');
        $d = "$date $koh:$kom";
        $home_team = strtr($home_team, '_', '-');
        if (isset($realteam1[$home_team]))
          $h = $realteam1[$home_team];
        else {
          $h = $home_team;
          $teamerr .= "$h\n";
        }
        $away_team = strtr($away_team, '_', '-');
        if (isset($realteam1[$away_team]))
          $a = $realteam1[$away_team];
        else {
          $a = $away_team;
          $teamerr .= "$a\n";
        }
        $minute = substr($match, strpos($match, '<div id="match_status" '));
        $minute = substr($minute, strpos($minute, '>') + 1);
        $minute = rtrim(substr($minute, 0, strpos($minute, '<')), "'");
        $minute = rtrim($minute, '+');
        switch ($game_status) {
          case 'Fin'  :
          case 'E/T'  :
          case 'Pen'  : $s = 'FT'; break;
          case 'Sched': $s = '-'; break;
          case 'H/T'  : $s = 'HT'; break;
          case 'Int'  : $s = 'SUS'; break;
          case 'Post' : $s = 'POS'; break;
          case 'Abd'  :
          case 'Canc' : $s = 'CAN'; break;
          default     : $s = $minute;
        }
        if (!strpos($match, '<div class="scoreh_ft score_cell centerTXT"> </div>') && ($cut = strpos($match, 'scoreh_ft'))) {
          $r = substr($match, $cut + 32);
          $r = substr($r, 0, strpos($r, '</div>
</div>'));
          $r = str_replace('</div>
<div class="scorea_ft score_cell centerTXT">', ':', $r);
          $r = strtr($r, ' ', '-');
          if ($cut = strpos($match, 'scoreh_et')) {
            $e = substr($match, $cut + 32);
            $e = substr($e, 0, strpos($e, '</div>
</div>'));
            $e = str_replace('</div>
<div class="scorea_et score_cell centerTXT">', ':', $e);
          }
          else
            $e = $r;
        }
        else
          $r = $e = '-:-';

        if ($i && $d && $h && $a && $d[2] == '-') {
          $base[$i] = array($h,$a,$d,$s,$e,$r,$g,$i,$base[$i][8]);
          if ($d > $starttime) $today[$h.','.$a] = array($h,$a,$d,$s,$e,$r);
        }
      }
    }
  }
  else {
    $url = 'http://2admin.xscores.com:5002/stream?s=1&seq=' . $old_seq;
    $content = file_get_contents($url);
    if ($seq = substr($content, 0, 8)) {
      $matches = explode('#', $content);
      unset($matches[0]);
      foreach($matches as $match) {
        list($ty,$ms,$i,$ts,$tr,$lg,$hl,$cn,$hm,$aw,$score,$sh,$se,$sp,$sc,$hy,$hr,$ay,$ar,$stat,$st,$fn,$vs,$cr) = explode('|', $match);
        if (isset($base[$i])) {
          $update = true;
          list($h,$a,$d,$s,$f,$r,$g,$i,$z) = $base[$i];
          $min = floor((time() - $ts) / 60) + $cr;
          if ($stat == 'Sched') $s = '-';
          else if (in_array($stat, array('Fin', 'E/T', 'Pen', 'FT', 'ET', 'E/T.', 'PEN.'))) $s = 'FT';
          else if ($stat == 'H/T') $s = 'HT';
          else if ($stat == 'Int') $s = 'SUS';
          else if ($stat == 'Post') $s = 'POS';
          else if ($stat == 'Abd' || $stat == 'Canc') $s = 'CAN';
          else if ($stat == '1 HF' || $stat == '1HF') $s = max(0, min($min, 44));
          else if ($stat == '2 HF' || $stat == '2HF') $s = max(45, min($min - 18, 89));
          $r = str_replace('-', ':', $score);
          if ($r == ':') $r = '-:-';
          $base[$i] = array($h,$a,$d,$s,$r,$r,$g,$i,$z);
          if ($d > $starttime) $today[$h.','.$a] = array($h,$a,$d,$s,$r,$r);
        }
      }
    }
    else $seq = $old_seq;
  }
  if (lock($online_dir . 'log/results.lock', 10000)) {
    $out = $seq . "\n";
    foreach ($base as $id => $data) {
      if (is_numeric($data[3]) && $data[3] < 90) {
        $update = true;
        $data[3]++;
      }
      $out .= $data[0].','.$data[1].','.$data[2].','.$data[3]. ','.$data[4].','.$data[5].','.$data[6].','.$data[7].','.$data[8]."\n";
    }
    if ($update) file_put_contents($fname, $out);
  }
  unlink($online_dir . 'log/results.lock');
  return $today;
}

// main()

$time = time();
$log = date('m-d H:i:s', $time)." cron:";
$time = round(floor($time / 10) * 10, -1) - 1;
$year = date('Y', $time);
$month = date('m', $time);
$day = date('d', $time);
$time_start = microtime(true);

//$time_scanm =  microtime(true);

//if (is_dir($online_dir . "schedule/$year/$month/$day")) {
if (true) {
  date('J', $time) < 23 ? $minute = date('i', $time) : $minute = 1;
  $base = Today($year, $month, $day, date('N', $time), $minute);
  $time_parse =  microtime(true);
  $absent = array();
  $today_dir = $online_dir . "schedule/$year/$month/$day";
  $dir = is_dir($today_dir) ? scandir($today_dir) : [];
  foreach ($dir as $file) if ($file[0] != '.') {
    list($timestamp, $cca, $tour, $action) = explode('.', $file);
//    $log .= date('m-d H:i', $timestamp).',';
    if ($timestamp > $time - 60 && $timestamp <= $time) { // периодичность запуска - 60 сек.
      if ($action == 'resend') { // рассылка всем
        $log .= " send program $tour to all;";
        $body = file_get_contents(trim(file_get_contents("$today_dir/$file")));
        send_to_all($cca, $subjects[$cca]." Программка тура $tour (авто-повтор)", $body);
      }
      else if ($action == 'remind') { // сбор адресов для рассылки "тормозам"
        $log .= " send reminder for $tour;";
        $absent[$tour] = get_absent_mails($cca, $tour);
      }
      else if ($action == 'monitor') { // check if some match started or finished or 2nd time started
        $tour_monitor = file("$today_dir/$file");
        $s = 0;
        $ms = 0;
        $progsched = '';
        $next = 2147483647;
        $adir = scandir($online_dir . $cca);
        foreach ($adir as $subdir)
          if ($subdir[0] == '2')
            $season = $subdir;

        $tfile = $online_dir . "$cca/$season/$tour/term";
        $term = is_file($tfile) ? trim(file_get_contents($tfile)) : $time;
        for ($m = 1; $m < sizeof($tour_monitor); $m++) if (trim($tour_monitor[$m])) {
          list($home, $away, $minute) = explode(',', $tour_monitor[$m]);
          $match = $home . ',' . $away;
          $minute = '';
//           && strtotime($year.'-'.$base[$match][2]) > $term
//           && strtotime($year.'-'.$base[$match][2]) < $term + 2764800) {
          if (isset($base[$match])) {
            if ($cca == 'SFP') { // $next = end of closest unfinished match: 6600 = 2*45 + 15 + 5 min
              if ($base[$match][3] != 'FT' && $base[$match][3] != 'POS' && $base[$match][3] != 'CAN') {
                $next = min($next, 6600 + strtotime("$year-" . $base[$match][2]));
                if ($base[$match][3] != '-') {
                  $minute = $base[$match][3];
                  $s++; // matches started
                }
              }
            }
            elseif ($cca == 'FIN') { // $next = begin of closest 2nd half
              if ($base[$match][3] == '-' || $base[$match][3] == 'HT' || ($base[$match][3] >= 1 && $base[$match][3] <= 45)) {
                $next = min($next, 3600 + strtotime("$year-" . $base[$match][2]));
                if ($base[$match][3] != '-') {
                  $minute = $base[$match][3];
                  $s++; // matches started
                }
              }
            }
            else {
              if ($base[$match][3] == '-') {
                $newyear = ($month == 12 && substr($base[$match][2], 0, 2) == '01') ? 1 : 0;
                $next = min($next, strtotime(($year + $newyear) . '-' . $base[$match][2]));
              }
              elseif ($base[$match][3] != 'POS' && $base[$match][3] != 'CAN') {
                $minute = $base[$match][3];
                if ($m < 11) $ms++; // matches started in main part
                $s++; // matches started total
              }
            }
          }
          $progsched .= $home . ',' . $away . ',' . $minute . "\n";
        }

        if ($s && $cca != 'SFP' && $cca != 'FIN') { // send predicts after some match started
          if (is_file($online_dir . "$cca/$season/publish/$tour")) {
            if (!strpos(file_get_contents($online_dir . "$cca/$season/publish/$tour"), '*')) {
              $log .= " due to fullhouse";
              $ms = 9; // stop monitoring and close the predict form because all predicts are here
            }
          }
          else {
            $log .= " publish predicts of $tour;";
            $prognozlist = str_replace('&lt;', '<', build_prognozlist($cca, $season, $tour));
            file_put_contents($online_dir . "$cca/$season/publish/$tour", $prognozlist);
            if ($tout[4] == 'L') {
              $cclen = 5;
              $ll = '&l='.substr($tour, 0, $cclen);
            }
            else {
              $cclen = 3;
              $ll = '';
            }
            $prognozlist .= "
Следить за ходом тура в реальном времени можно на странице
https://fprognoz.org/?a=".$ccn[$cca]."$ll&s=$season&m=prognoz&t=$t".strtolower(substr($tour, $cclen))."
";
            send_to_all($cca, $subjects[$cca]." Принятые прогнозы на тур $tour (авто-публикация)", $prognozlist);
            if ($cca == 'WL' || $cca == 'IST')
              touch($online_dir . "$cca/$season/prognoz/$tour/closed");
            else
              touch($online_dir . "$cca/$season/prognoz/$tour/published");

            touch($online_dir . "schedule/task/start.$tour");
          }
        }
        if ($next == 2147483647 || $ms > 3) { // close predict form after all matches or 4+ main started
          $log .= " close web form for $tour;";
          touch($online_dir . "$cca/$season/prognoz/$tour/closed");
          touch($online_dir . "schedule/task/close.$tour");
          if ($cca == 'SFP') touch($online_dir . "schedule/task/renew.$tour"); // last renew
          if ($cca == 'IST' && $next == 2147483647) touch($online_dir . "schedule/task/pblsh.$tour"); // publish results
        }
        else { // reschedule
          if ($cca == 'SFP' || $cca == 'IST') {
            if (is_file($online_dir . "$cca/$season/prognoz/$tour/closed")) { // if already parsed
              if ($s) {
                if (substr($tour, 0, 3) == 'SUP' && date('H', $time) == 15)
                  touch($online_dir . "schedule/task/parse.$tour");           // at 15:XX must parse 2nd time for PRED.SU

                touch($online_dir . "schedule/task/renew.$tour");
              }
            }
            else if ($s && $cca = 'SFP') {                      // else should parse
              touch($online_dir . "schedule/task/parse.$tour");
              $next -= 6000;    // for next try to parse 10 min after start of the 1st match
              if (substr($tour, 0, 3) == 'SUP') { // schedule monitor to next day, 15:00
                $tomorrow = $time + 86400;
                $tomorrow_year = date('Y', $tomorrow);
                $tomorrow_month = date('m', $tomorrow);
                $tomorrow_day = date('d', $tomorrow);
                $log .= " schedule monitor $tour to $tomorrow_month-$tomorrow_day 15:00;";
                Schedule(strtotime("$tomorrow_year-$tomorrow_month-$tomorrow_day 15:00"), $cca, $tour, 'monitor', $ms . "\n" . $progsched);
              }
            }
            else $next -= 6300; // for 1st try to parse 5 min after start of the 1st match
            if ($next < $time + 300) $next = $time + 300; // reshedule failed event to next 5 min
          }
          elseif ($cca == 'FIN') {}
//          elseif (!$s && $next < $time + 60) $next = $time + 60; // waiting for the 1st match
          elseif ($next < $time + 60) $next = $time + 60; // waiting for the match beginning
          $log .= ' schedule monitor ' . $tour . ' to ' . date('m-d H:i', $next) . ';';
          Schedule($next, $cca, $tour, 'monitor', $ms . "\n" . $progsched);
        }
      }
    }
  }
  if (sizeof($absent)) { // если есть повод, разослать напоминания
    $emails = array();
    foreach ($absent as $tour => $mails) foreach ($mails as $email) $emails[$email][] = $tour;
    foreach ($emails as $email => $tours) {
      list($name, $email) = explode('#', $email);
      $body = 'Добрый день.


Сообщаем, что к контрольному сроку на сервере ФП не обнаружен';
      (sizeof($tours) == 1) ? $body .= ' ваш прогноз на тур:' : $body .= 'ы ваши прогнозы на туры:';
      foreach ($tours as $tour) {
        $cca = $ccn[substr($tour, 0, 3)];
        ($cca == 'uefa') ? $t = substr($tour, 5) . '&l=' . substr($tour, 0, 5) : $t = substr($tour, 3);
        $body .= "\n$tour - https://fprognoz.org?a=$cca&m=prognoz&t=$t";
      }
      $body .= '
Если прогноз';
      (sizeof($tours) == 1) ? $body .= ' уже отправлен' : $body .= 'ы уже отправлены';
      $body .= ' на другой официальный адрес, повторно отправлять не надо.


Контрольно-дисциплинарный комитет ФП УЕФА
';
      $amail = explode(' ', str_replace(',', ' ', $email));
      $email = '';
      foreach ($amail as $eml) if ($eml = trim($eml)) $email .= "$name <$eml>,";
      if ($email = rtrim($email, ','))
        send_email('Fprognoz.Org <fp@fprognoz.org>', '', $email, 'ФП. Напоминание о необходимости отправить прогнозы', $body);

    }
  }
}
if (strlen($log) > 35) {
  $logfile = fopen($online_dir . 'log/scheduler.log', 'a');
  fwrite($logfile, $log." finished ".(microtime(true) - $time_start)."\n");
  fclose($logfile);
}
?>
