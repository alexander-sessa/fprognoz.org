<?php
$ab = array();
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
if (isset($_SESSION['Coach_name']) && isset($club_edit) && $club_edit) {
  foreach ($cmd_db[$cca] as $code => $team)
    if ($team['usr'] == $_SESSION['Coach_name']) {
      $edit = $code;
      break;
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
$out = '';
$clubs = file($a . '/club.data.tsv');
foreach ($clubs as $club) {
  list($code, $image, $name, $city, $year, $arena, $capacity, $site) = explode('	', trim($club));
  if (isset($ab[$code])) {
    $col1 = $col2 = $col3 = $col4 = '';
    foreach ($ab[$code] as $pos => $bomber) {
      $col1 .= ($edit == $code) ? '      <input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />
' : '      ' . $pos . '<br />
';
      $col2 .= ($edit == $code) ? '      <input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />
' : '      ' . $bomber . '<br />
';
    }
    if (isset($ab[$code.'(*)'])) foreach ($ab[$code.'(*)'] as $pos => $bomber) {
      $col3 .= ($edit == $code) ? '      <input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />
' : '      ' . $pos . '<br />
';
      $col4 .= ($edit == $code) ? '      <input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />
' : '      ' . $bomber . '<br />
';
    }
    $out .= '
  <tr>
    <td><br /><img src="images/' . $image . '" alt="' . $name . '" style="max-height:280;max-width:150;object-fit: cover;" /></td>
    <td>
      <br />
      <b>' . $name . '</b><br /><br />
      город: <b>' . $city . '</b><br /><br />
      год основания: <b>' . $year . '</b><br /><br />
      арена: <b>' . $arena . '</b><br />
      вместительность: <b>' . $capacity . '</b><br /><br />
      <a href="' . $site . '" target="_blank">' . $site . '</a>
      ' . ($edit == $code ? '<br /><br /><br /><br /><br />
      <input type="submit" name="change" value="сохранить_состав" />
' : '') . '
    </td>
    <td>
' . $col1 . '
    </td>
    <td>
' . $col2 . '
    </td>
    <td>
' . $col3 . '
    </td>
    <td>
' . $col4 . '
    </td>
  </tr>
';
  }
}
echo '
<form action="" method="post">
<table class="table-condenced table-striped w-100 mx-auto">
  <tr><td>' . $msg . '</td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
' . $out . '
</table></form>
';
?>