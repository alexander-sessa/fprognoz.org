<?php
$closed = true;
$cci = array(
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
$ccr = array(
'ENG' => 'Англии',
'BLR' => 'Беларуси',
'GER' => 'Германии',
'NLD' => 'Голландии',
'ESP' => 'Испании',
'ITA' => 'Италии',
'PRT' => 'Португалии',
'RUS' => 'России',
'UKR' => 'Украины',
'FRA' => 'Франции',
'SUI' => 'Швейцарии',
'SCO' => 'Шотландии',
);
eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
$ac_head = '';
echo '<p class="title text15b">&nbsp;&nbsp;&nbsp;Тренерская Лиги Наций</p>
<hr size="1" width="98%">';
//Закрыто. Если ваша команда пробьётся в Финальный турнир, Вам <a href="/?a=world&m=coach_uft">сюда</a>';
//exit;
if (isset($_SESSION['Coach_name']) && !isset($_POST['teamname'])) {


  $s = $cur_year;
  $file = file($online_dir.'UNL/'.$s.'/hq');
  $hq = [];
  foreach ($file as $line) {
    list($ccode, $name, $email) = explode(';', trim($line));
    $hq[$name][] = $ccode;
  }

// Обработка действий в ЛИГЕ НАЦИЙ

  if (count($_POST)) {
    $closed = false;
    $ac = $_POST['cc'];
    $team = file_get_contents($online_dir.'UNL/'.$s.'/'.$ac.'.csv');
    if (count($_POST) > 1)
      while(list($k,$v)=each($_POST)) {
        $plr = trim(base64_decode($k)).';';
        if      ($v == 'уволить')   $team = str_replace($plr.'coach', $plr, $team);
        else if ($v == 'назначить') $team = str_replace($plr, $plr . 'coach', $team);
        else if ($v == 'исключить')
        {
          $team = str_replace($plr . ";\n", '', $team);
          unlink($data_dir . 'personal/'.explode(';', $plr)[0].'/team.'.$s);
        }
        else if ($v == 'призвать')  $team .= $plr . ";\n";
        file_put_contents($online_dir.'UNL/'.$s.'/'.$ac.'.csv', $team);
        file_put_contents($data_dir . 'personal/'.explode(';', $plr)[0].'/team.'.$s, $ac);
      }

  }

//

  if (isset($hq[$_SESSION['Coach_name']])) {
    $closed = false;
    if (count($hq[$_SESSION['Coach_name']]) > 1) {
      $ac_head = '
  <div style="width:100%;padding:8px 0">
    Выберите ФП-ассоциацию: <select id="ccselect" name="cc">';
      foreach ($hq[$_SESSION['Coach_name']] as $ccode) {
        $ac_head .= '
      <option value="'.$ccode.'"'.((isset($ac) && $ccode == $ac) ? ' selected="selected"' : '').'>'.$ccode.'</option>';
        if (!isset($ac))
          $ac = $ccode;

      }
      $ac_head .= '
    </select>
    <script>$(function(){$("#ccselect").change(function(){this.form.submit();});})</script>
  </div>';
    }
    else {
      $ac = $hq[$_SESSION['Coach_name']][0];
      $ac_head = '<input type="hidden" name="cc" value="'.$ac.'" />';
    }
    $team = file($online_dir.'UNL/'.$s.'/'.$ac.'.csv');
    $coach = [];
    if (count($team))
      foreach ($team as $line) {
        list($name, $mail, $role) = explode(';', trim($line));
        if ($role == 'coach')
          $coach[] = $line;

      }

    $season = (strlen($s) == 7) ? $s : (($ac == 'SUI') ? '2020-3' : '2020-21');
    $codes = file($online_dir.'UNL/'.$s.'/'.$ac.'.csv');
    $col_height = count($codes) * 24 + 144; //24
    $coach_col = '';
    if (count($codes) > count($coach) && count($coach) < 2) {
      $coach_col .= '
    <p>Вы можете назначить ' . (count($coach) ? 'ещё одного ' : '') . 'тренера сборной:</p>';
      foreach ($codes as $line) {
        list($name, $mail, $role) = explode(';', $line);
        if (!in_array($line, $coach))
          $coach_col .= '
    <input type="submit" value="назначить" name="'.base64_encode($name.';'.$mail).'" /> '.$name.'<br />';

      }
    }
    if (count($coach)) {
      $coach_col .= '
    <p>Вы можете уволить тренера сборной:</p>';
      foreach ($coach as $line) {
        list($name, $mail, $role) = explode(';', $line);
        $coach_col .= '
    <input type="submit" value="уволить" name="'.base64_encode($name.';'.$mail).'" /> '.$name.'<br />';
      }
    }
  }
  else {
    if (!isset($ac)) {
      foreach(['BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'SUI', 'UKR'] as $ccode) {
        $team = file($online_dir.'UNL/'.$s.'/'.$ccode.'.csv');
        foreach ($team as $line) {
          list($name, $mail, $role) = explode(';', trim($line));
          if ($role == 'coach' && $name == $_SESSION['Coach_name']) {
            $ac = $ccode;
            $closed = false;
            break;
          }
        }
        if (isset($ac)) {
          $ac_head = '<input type="hidden" name="cc" value="'.$ac.'" />';
          break;
        }
      }
      $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
      $player = [];
      foreach ($codes as $line) if (trim($line)){
        list($code, $team, $name, $email, $role) = explode('	', $line);
        $player[$team][$code] = ['code' => $code, 'name' => $name, 'email' => $email, 'role' => $role];
        if ($role == 'coach' && $name == $_SESSION['Coach_name']) {
          $team_name = $team;
          $closed = false;
        }
      }
    }
  }
}
if ($closed)
  echo '
Дверь в Тренерскую заперта изнутри.<br>
Сквозь дверь Вы слышите звуки эмоционального обсуждения, но чего именно - почти не разобрать.<br>
Понятно только, что некоторые ассоциации ещё не определились с тренерами их их президенты ищут кандидатов.<br>
Может быть именно Вас?
';
else if (isset($ac) && strlen($ac) == 3) {
  $team = file($online_dir.'UNL/'.$s.'/'.$ac.'.csv');
  $c = 0;
  $player = [];
  foreach ($team as $line) {
    list($name, $mail, $role) = explode(';', trim($line));
    if ($role == 'coach') {
      $player[$name] = 'coach';
      $c++;
    }
    else
      $player[$name] = $mail;

  }
  $season = (strlen($s) == 7) ? $s : (($ac == 'SUI') ? '2020-3' : '2020-21');
  $codes = file($online_dir.$ac.'/'.$season.'/codes.tsv');
  if (!isset($col_height))
    $col_height = count($codes) * 24;

  $player_col = '';
  if (count($team) > $c) {
    $player_col .= '
      <p>Вы можете исключить из сборной игрока:</p>';
    foreach ($player as $name => $mail)
      if ($mail != 'coach')
        $player_col .= '
      <input type="submit" value="исключить" name="'.base64_encode($name.';'.$mail).'" /> '.$name.' </br>';

  }
/*
  if (count($team) < 11) {
    $player_col .= '
      <p>Вы можете призвать игрока в сборную:</p>';
    foreach ($codes as $line) {
      list($code, $team, $name, $mail, $long, $conf) = explode('	', $line);
      if (!isset($player[$name]) && !is_file($data_dir . 'personal/'.$name.'/team.2020'))
        $player_col .= '
      <input type="submit" value="призвать" name="'.base64_encode($name.';'.$mail).'" /> '.$name.' ('.$team.') ('.$mail.')</br>';

    }
  }
*/
//  echo '
//    </div>
//  </div>';

echo '
<form method="POST">
<div>
  '.$ac_head.'
  <div style="width:100%">
    <div style="font-weight:bold">
      Сборная ' . (isset($ccr[$ac]) ? $ccr[$ac] . ' ' . $sites[$cci[$ac]] : $ac) . '
    </div>' .
(isset($coach_col) ? '
    <div>'.$coach_col.'</div>' : '') .'
    <div>'.$player_col.'</div>
  </div>
</div>
</form>
<div style="clear:both"></div>';
}
?>