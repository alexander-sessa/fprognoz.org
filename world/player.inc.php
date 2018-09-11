<?php
$s = $cur_year;
$out = '';
$ccr = array(
'ENG' => 'Англия',
'BLR' => 'Беларусь',
'GER' => 'Германия',
'NLD' => 'Голландия',
'ESP' => 'Испания',
'ITA' => 'Италия',
'PRT' => 'Португалия',
'RUS' => 'Россия',
'UKR' => 'Украина',
'FRA' => 'Франция',
'SCO' => 'Шотландия',
'Kings Forecasts' => 'Kings Forecasts',
'Onedivision' => 'Onedivision',
'PrimeGang' => 'PrimeGang',
'КЛФП Харьков' => 'КЛФП Харьков',
'КСП «Торпедо»' => 'КСП «Торпедо»',
);

$player = isset($_SESSION['Coach_name']) ? $_SESSION['Coach_name'] : '';
foreach($ccr as $ccode => $country) {
  $team = file($online_dir.'WL/'.$s.'/'.$ccode.'.csv');
  foreach ($team as $line) {
    list($name, $mail, $role) = explode(';', trim($line));
    $hash = base64_encode(trim($line));
    $show = true;
    if ($name == $player)
      if (isset($_POST['out'])) {
        $team = file_get_contents($online_dir.'WL/'.$s.'/'.$ccode.'.csv');
        file_put_contents($online_dir.'WL/'.$s.'/'.$ccode.'.csv', str_replace($line, '', $team));
        $show = false;
      }
      else if (isset($_POST[$hash])) {
        $mail = $_POST[$hash];
        $team = file_get_contents($online_dir.'WL/'.$s.'/'.$ccode.'.csv');
        file_put_contents($online_dir.'WL/'.$s.'/'.$ccode.'.csv', str_replace($line, "$name;$mail;$role\n", $team));
      }

    if ($show)
      $out .= '
  <tr>
    <td>'.$country.'</td>
    <td>'.$name.'</td>
    <td>'.($role == 'coach' ? 'тренер' : '').'</td>
    <td style="text-align:center">'.($name == $player ? '<input type="submit" name="out" value="выйти">' : 'да').'</td>
    <td>'.($name == $player ? '<input id="changemail" type="text" name="'.$hash.'" value="'.$mail.'"><script>$(function(){$("#changemail").change(function(){this.form.submit();});})</script>
' : '').'</td>
  </tr>';

  }
}
?>
<p class="title text15b">&nbsp;&nbsp;&nbsp;Состав Мировой Лиги</p>
<hr size="1" width="99%" />
<form method="POST">
<table width="99%">
<tr><th align=left>команда</th><th align=left>игрок</th><th align=left>тренер</th><th>участие</th><th align=left>e-mail</th></tr>
<?=$out?>
</table>
</form>
<br />