#! /usr/bin/php
<?php
$time = time();
date_default_timezone_set('Europe/Berlin');
$log = date('m-d H:i:s', $time)." scanmail:";
require ('/home/fp/data/config.inc.php');

function scan_mail($imap) {
  if (!$count = imap_num_msg($imap)) return [];

  //echo $count;
  $predicts = [];
  for ($m = $count; $m > 0; --$m) {
    $header = imap_header($imap, $m);
    //print_r(imap_header($imap, $m));
    if ($header->Unseen != 'U') break; // читаем до первого прочитаного

    $time = $header->udate;
    $str = imap_fetchstructure($imap, $m);
    $msg = imap_fetchbody($imap, $m, 1);
    if (isset($str->parts)) {
      switch ($str->parts[0]->encoding) {
        case  3: $msg = imap_base64($msg); break;
        case  4: $msg = imap_qprint($msg); break;
        default:
      }
      if ($str->parts[0]->subtype == 'HTML')
        $msg = strip_tags(str_ireplace('<br', "\n<br", $msg));

    }
    $msg = str_ireplace('fp_prognoz@', '', $msg);
    $msg = str_ireplace('            ', "\n", $msg);
    $msg = str_ireplace('&#8232;', '', $msg);
    $msg = str_ireplace('﻿', "\n", $msg);
    $msg = str_ireplace('�', ' ', $msg);
    $msg = str_ireplace('�', '', $msg);
    $msg = str_ireplace('FP_Prognoz', 'FP_Prognoz', $msg);
    $atemp = explode('FP_Prognoz', $msg);
    unset($atemp[0]);
    foreach ($atemp as $msg) {
      $ta = explode("\n", $msg);
      $team = $tour = $prognoz = '';
      foreach ($ta as $line) if ($line = trim($line)) {
        if (!$team)
          $team = ltrim($line, '> ');
        else {
          if (!$tour)
            $tour = strtoupper(ltrim($line, '> '));
          else {
            $prognoz = strtoupper(ltrim($line, '> '));
            $prognoz = strtr($prognoz, ['�' => 'X', 'Х' => 'X', 'х' => 'X']);
            if (strlen($prognoz) > 15 && ($pen = strpos($prognoz, ' ', 15))) {
              $penalties = trim(substr($prognoz, $pen + 1));
              $prognoz = substr($prognoz, 0, $pen);
            }
            else
              $penalties = '';

            if ($team && $tour && $prognoz && strlen($tour) <= 8)
              $predicts[$tour][] = "$team;$prognoz;$time;$penalties\n";

            $tour = $prognoz = '';
          }
        }
      }
    }
  }
  imap_close($imap);
  //print_r($predicts);
  return $predicts;
}

function get_cca($tour) {
  $cca = substr($tour, 0, 3);
  if ($cca == 'KON')
    return 'konkurs';

  if ($cca == 'VAC')
    return 'vacancy';

  if ($cca == 'WLS')
    return 'WL';

  if ($cca == 'UEF' || $cca == 'CHA' || $cca == 'CUP' || $cca == 'GOL')
    return 'UEFA';

  if ($cca == 'FFP' || $cca == 'FFL' || $cca == 'PRO' || $cca == 'PRE' || $cca == 'SPR' || $cca == 'SUP' || $cca == 'TOR' || $cca == 'FWD')
    return 'SFP';

  return $cca;
}

function last_season($cca) {
  $season = '';
  $dir = scandir($cca);
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
      $season = $subdir;

  return $season;
}

$imap = imap_open($mail_server, $mail_user, $mail_password);
if (!$imap)
  $log .= ' can not connect to ' . $mail_server . ': ' . imap_last_error();
else {
  $newpredicts = scan_mail($imap);
  foreach ($newpredicts as $tour => $predicts) {
    $cca = get_cca($tour);
    if (is_dir($online_dir . $cca)) {
      $tour_dir = $online_dir . $cca . '/' . last_season($online_dir . $cca) . '/prognoz/' . $tour;
      if (!is_dir($tour_dir)) mkdir($tour_dir, 0755, true);
      $new = '';
      $content = is_file($tour_dir . '/mail') ? file_get_contents($tour_dir . '/mail') : '';
      foreach ($predicts as $line) {
        $log .= ' ' . trim($line);
        if (strpos($content, $line) === false) {
          list($team, $predict, $tstamp, $pena) = explode(';', $line);
          $line1 = $team . ';' . $predict . ';' . ($tstamp - 1) . ';' . $pena;
          if (strpos($content, $line) === false) {
            $new .= $line;
            $log .= ' received;';
          }
          else $log .= ' already received;';
        }
        else $log .= ' already received;';
      }
      $mail = fopen($tour_dir . '/mail', 'a');
      fwrite($mail, $new);
      fclose($mail);
      // потом удалить эту проверку
      if ($cca == 'SFP' && !is_file($tour_dir . '/closed')) touch($online_dir . 'schedule/task/post.' . $tour);
    }
    else $log .= ' no association dir ' . $cca;
  }
}
if (strlen($log) > 25) {
  $logfile = fopen($online_dir . 'log/scanmail.log', 'a');
  fwrite($logfile, $log." finished\n");
  fclose($logfile);
}
?>

