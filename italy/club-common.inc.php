<?php
$ab = array();
if (!isset($s) || !$s) {
  $dir = scandir($online_dir . $cca, 1);
  foreach ($dir as $s)
    if ($s[0] == '2')
      break;

}
$bombers_file = $online_dir . $cca . '/' . $s . '/bombers';
if (is_file($bombers_file)) {
  $bombers = str_replace("\r", '', file_get_contents($bombers_file));
  if (mb_detect_encoding($bombers, 'UTF-8', true) === FALSE)
    $bombers = iconv('CP1251', 'UTF-8', $bombers);

  $abombers = explode('Team: ', $bombers);
  foreach ($abombers as $bteam) {
    $ateam = explode("\n", $bteam);
    $tn = trim($ateam[0]);
    unset($ateam[0]);
    foreach($ateam as $line)
      if ($line = trim($line))
        $ab[$tn][substr($line, 0, 2)] = substr($line, 3);

  }
}
$edit = $msg = '';
if ($club_edit && isset($_SESSION['Coach_name'])) {
  foreach ($usr_db[$_SESSION['Coach_name']] as $team_str) {
    $ta = explode('@', $team_str);
    if ($ta[1] == $cca) {
      $edit = $ta[0];
      break;
    }
  }
  if (isset($_POST['change'])) {
    foreach ($_POST['o'] as $pos => $bomber) {
      $ab[$edit][$pos] = $bomber;
      $ab[$edit.'(*)'][$pos] = $_POST['r'][$pos];
    }
    $bombers = '';
    foreach ($ab as $team => $squad) {
      $bombers .= "Team: $team\n";
      foreach ($squad as $pos => $bomber)
        $bombers .= "$pos $bomber\n";

      $bombers .= "\n";
    }
    file_put_contents($bombers_file, $bombers);
    $msg = '<font color="red">Состав команды записан</font>';
  }
}
?>