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
'SUI' => 'Швейцария',
'SCO' => 'Шотландия',
);
$ccf = array(
'ОЛФП' => 'ОЛФП',
'Чемпионат Прогнозов' => 'Чемпионат Прогнозов',
'FunkySouls' => 'FunkySouls',
'GER' => 'Германия',
'UKR' => 'Украина',
'ITA' => 'Италия',
);

eval('$cce = array('.file_get_contents($online_dir.'UNL/'.$s.'/cce').');');

if (!isset($l) || $l == 'n')
{
  $player = isset($_SESSION['Coach_name']) ? $_SESSION['Coach_name'] : '';
  $mycc = '';
  if ($player)
  {
    $cc = trim(file_get_contents($data_dir . 'personal/'.$player.'/team.'.date('Y')));
    $codes = file($online_dir.'UNL/'.$cur_year.'/'.$cc.'.csv');
    foreach ($codes as $cline)
    {
      $arr = explode(';', $cline);
      if ($arr[0] == $player && $arr[2] == 'coach')
      {
        $mycc = $cc;
        break;
      }
    }
  }
  foreach($ccr as $ccode => $country)
  {
    $team = file($online_dir.'UNL/'.$s.'/'.$ccode.'.csv' . (is_file($online_dir.'UNL/'.$s.'/'.$ccode.'.csv_') ? '_' : ''));
    foreach ($team as $line)
    {
      list($name, $mail, $role) = explode(';', trim($line));
      $hash = base64_encode(trim($line));
      $show = true;
      if ($name == $player)
        if (isset($_POST['out']))
        {
          $team = file_get_contents($online_dir.'UNL/'.$s.'/'.$ccode.'.csv');
          file_put_contents($online_dir.'UNL/'.$s.'/'.$ccode.'.csv', str_replace($line, '', $team));
          $show = false;
          unlink($data_dir . 'personal/'.$name.'/team.'.date('Y'));
        }
        else if (isset($_POST[$hash]))
        {
          $mail = $_POST[$hash];
          $team = file_get_contents($online_dir.'UNL/'.$s.'/'.$ccode.'.csv');
          file_put_contents($online_dir.'UNL/'.$s.'/'.$ccode.'.csv', str_replace($line, "$name;$mail;$role\n", $team));
        }

      if ($show)
        $out .= '
  <tr>
    <td>'.$country.'</td>
    <td'.($ccode == $mycc ? ' class="player_name">' : '>').$name.'</td>
    <td>'.($role == 'coach' ? 'тренер' : '').'</td>
    <td>'.($name == $player ? '<input type="submit" name="out" value="выйти">' : 'да').'</td>
    <td>'.($name == $player ? '<input id="changemail" type="text" name="'.$hash.'" value="'.$mail.'"><script>$(function(){$("#changemail").change(function(){this.form.submit();});})</script>
' : '').'</td>
  </tr>';

    }
  }
  echo '
<p class="title text15b">&nbsp;&nbsp;&nbsp;Участники Лиги Наций</p>
<hr size="1" width="99%">
<form method="POST">
<table width="99%">
  <tr><th align=left>команда</th><th align=left>игрок</th><th align=left>тренер</th><th>участие</th><th align=left>e-mail</th></tr>
' . $out . '
</table>
</form>
<br>';
}
else if ($l == 's')
{
  eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  $prev = '';
  $out = $head = $coach = $squad = $coach_prefix = '';
  foreach ($codes as $line)
  {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    if (!array_key_exists($team, $ccr))
    { // здесь только избранные
      if ($team != $prev)
      {
        $prev = $team;
        $out .= $head . $coach_prefix . $coach . $squad;
        $head = '
    <div class="teamcard">
      <div class="teamname">
        <div style="width:100px; text-align:center">'.$sites[$team].'</div>
        <div>'.$team.'</div>
      </div>
      <div>';
        $coach_prefix = 'Тренер: ';
        $coach = $code;
        $squad = '
      </div>
      <div>
        Состав: ';
        $lines = file($online_dir.'UNL/'.$s.'/'.$team.'.csv' . (is_file($online_dir.'UNL/'.$s.'/'.$team.'.csv_') ? '_' : ''));
        foreach ($lines as $line)
        {
          list($name, $mail, $role) = explode(';', trim($line));
          $squad .= $name.', ';
        }
        $squad = rtrim($squad, ', ');
        $squad .= '
      </div>
    </div>';
      }
      else if (trim($role == 'coach'))
      {
        $coach_prefix = 'Тренеры: ';
        $coach .= ', '.$code;
      }
    }
  }
  $out .= $head . $coach_prefix . $coach . $squad;
  echo '
<p class="title text15b">&nbsp;&nbsp;&nbsp;Участники Лиги Сайтов</p>
<style>
.teamcard { border: 1px solid blue; border-radius: 20px; margin:10px; padding: 10px }
.teamname { display:flex; font-weight:bold; font-size:125% }
</style>
<div>
' . $out . '
</div>';
}
else if ($l == 'f')
{
  eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  $prev = '';
  $out = $head = $coach = $squad = $coach_prefix = '';
  foreach ($codes as $line)
  {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    if (array_key_exists($team, $ccf))
    { // здесь только избранные
      if ($team != $prev)
      {
        $prev = $team;
        $out .= $head . $coach_prefix . $coach . $squad;
        $head = '
    <div class="teamcard">
      <div class="teamname">
        <div style="width:100px; text-align:center">'.$sites[$ccf[$team]].'</div>
        <div>'.$ccf[$team].'</div>
      </div>
      <div>';
        $coach_prefix = 'Тренер: ';
        $coach = $code;
        $squad = '
      </div>
      <div>
        Состав: ';
        $lines = file($online_dir.'UNL/'.$s.'/'.$team.'.csv' . (is_file($online_dir.'UNL/'.$s.'/'.$team.'.csv-') ? '-' : ''));
        foreach ($lines as $line)
        {
          list($name, $mail, $role) = explode(';', trim($line));
          $squad .= $name.', ';
        }
        $squad = rtrim($squad, ', ');
        $squad .= '
      </div>
    </div>';
      }
      else if (trim($role == 'coach'))
      {
        $coach_prefix = 'Тренеры: ';
        $coach .= ', '.$code;
      }
    }
  }
  $out .= $head . $coach_prefix . $coach . $squad;
  echo '
<p class="title text15b">&nbsp;&nbsp;&nbsp;Участники Финального Турнира</p>
<style>
.teamcard { border: 1px solid blue; border-radius: 20px; margin:10px; padding: 10px }
.teamname { display:flex; font-weight:bold; font-size:125% }
</style>
<div>
' . $out . '
</div>';
}
else if ($l == 'e')
{
  eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  $prev = '';
  $out = $head = $coach = $squad = $coach_prefix = '';
  foreach ($codes as $line)
  {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    if (array_key_exists($team, $cce))
    { // здесь только избранные
      if ($team != $prev)
      {
        $prev = $team;
        $out .= $head . $coach_prefix . $coach . $squad;
        $head = '
    <div class="teamcard">
      <div class="teamname">
        <div style="width:100px; text-align:center">'.$sites[$cce[$team]].'</div>
        <div>'.$cce[$team].'</div>
      </div>
      <div>';
        $coach_prefix = 'Тренер: ';
        $coach = $code;
        $squad = '
      </div>
      <div>
        Состав: ';
        $lines = file($online_dir.'UNL/'.$s.'/'.$team.'.csv');
        foreach ($lines as $line)
        {
          list($name, $mail, $role) = explode(';', trim($line));
          $squad .= $name.', ';
        }
        $squad = rtrim($squad, ', ');
        $squad .= '
      </div>
    </div>';
      }
      else if (trim($role == 'coach'))
      {
        $coach_prefix = 'Тренеры: ';
        $coach .= ', '.$code;
      }
    }
  }
  $out .= $head . $coach_prefix . $coach . $squad;
  echo '
<p class="title text15b">&nbsp;&nbsp;&nbsp;Участники Турнира ЧЕ 2021</p>
<style>
.teamcard { border: 1px solid blue; border-radius: 20px; margin:10px; padding: 10px }
.teamname { display:flex; font-weight:bold; font-size:125% }
</style>
<div>
' . $out . '
</div>';
}
