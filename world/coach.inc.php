<?php
function make_passwd($code, $name, $mail) {
  global $mailpass;
  global $online_dir;
  global $this_site;
  if (is_file($online_dir.'UNL/passwd/'.$code)) return true;
  if (isset($mailpass[$mail])) {
    file_put_contents($online_dir.'UNL/passwd/'.$code, $mailpass[$mail].':player');
    return true;
  }
  $gp = '';
  $mix = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
  for ($i=0; $i<8; $i++)
    $gp .= $mix[rand(0,56)];

  file_put_contents($online_dir.'UNL/passwd/'.$code, md5($gp).":player");
  send_email('FPrognoz.org <fp@fprognoz.org>', $name, $mail,
                   'Пароль для сайта FPrognoz.org',
'
Вы получили случайно-сгенерированный пароль для входа на сайт ' . $this_site . ': '.$gp.'

При входе в качестве имени указывайте ваш e-mail: '.$mail.'

Пароль можно сменить на странице ' . $this_site . '/?m=pass


Удачи!
');
}

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
echo '<p class="title text15b">&nbsp;&nbsp;&nbsp;Тренерская</p>
<hr size="1" width="98%" />';
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

    $season = (strlen($s) == 7) ? $s : (($ac == 'SUI') ? '2019-4' : '2019-20');
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
else {
  if (isset($_POST['teamname'])) {

// Обработка действий в ЛИГЕ САЙТОВ

    if (!$_POST['teamname'] || !$_POST['teamsite'] || !$_POST['coach1'] || !$_POST['cmail1'])
      echo '<p style="color:red;font-weight:bold">Не заполнены все обязательные поля!</p>';
    else {
      if (isset($_SESSION['Coach_name']))
        $human = true;
      else
      {
        $params['secret'] = '6Lc8dioUAAAAAC3_a9PP6k9r5aA2_o2S5kq1j3oJ';
        $params['response'] = $_POST['g-recaptcha-response'];
        $options = array(
          'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($params)
          )
        );
        $context = stream_context_create($options);
        $fp = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $human = strpos($fp, '"success": true') ? true : false;
      }
    }
    if ($human) {
      $s = $cur_year;
      $postdata = fopen($online_dir.'UNL/'.$s.'/postdata', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $teamname = $_POST['teamname'];
      $postdata = fopen($online_dir.'UNL/'.$s.'/postdata2', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $sites_file = file($online_dir.'UNL/'.$s.'/sites.inc');
      $sites_out = '';
      $edit = false;
      foreach ($sites_file as $line) if ($line[0] != ')') {
        if (strpos($line, $_POST['teamname'])) {
          $sites_out .= "'$teamname' => '<a href=\"".$_POST['teamsite']."\" target=\"_blank\"><img src=\"".$_POST['teamlabel']."\" style=\"height:31px\"></a>',\n";
          $edit = true;
        }
        else
          $sites_out .= $line;
      }
      if (!$edit)
        $sites_out .= "'$teamname' => '<a href=\"".$_POST['teamsite']."\" target=\"_blank\"><img src=\"".$_POST['teamlabel']."\" style=\"height:31px\"></a>',\n";
      $sites_out .= ');';
      file_put_contents($online_dir.'UNL/'.$s.'/sites.inc', $sites_out);

      $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
      $codes_out = '';
      foreach ($codes as $line)
        if (!strpos($line, "	$teamname	"))
          $codes_out .= $line;

      $order = [];
      $squad = [];
      for ($i=1; $i<17; $i++) {
        if ($player = trim($_POST['player'.$i])) {
          $code = isset($_POST['code'.$i]) ? $_POST['code'.$i] : $player;
          $email = $_POST['email'.$i];
          $order[$code] = isset($_POST['pos'.$i]) ? $_POST['pos'.$i] : $i;
          $squad[$code] = $code.'	'.$teamname.'	'.$player.'	'.$email.'	'
          . (in_array($code, [trim($_POST['coach1']), trim($_POST['coach2'])]) ? 'coach' : $_POST['prog'.$i]) . '	
';
          if ($email)
            make_passwd($code, $player, $email);

        }
      }
      for ($i=1; $i<3; $i++) {
        $coach = trim($_POST['coach'.$i]);
        if ($coach && !isset($order[$coach])) // неиграющий тренер
        {
          $order[$coach] = 0;
          $squad[$coach] = $coach.'	'.$teamname.'	'.$coach.'	'.$_POST['cmail'.$i].'	coach	
';
          make_passwd($code, $coach, $_POST['cmail'.$i]);
        }
      }
      asort($order);
      $csv = '';
      foreach ($order as $code => $pos)
      {
        if ($pos)
          $csv .= $code.';;;
';
        $codes_out .= $squad[$code];
      }
      file_put_contents($online_dir.'UNL/'.$s.'/codes.tsv', $codes_out);
      file_put_contents($online_dir.'UNL/'.$s.'/'.$teamname.'.csv', $csv);
      build_access();

      if (isset($_SESSION['Coach_name']))
        echo '<p style="font-weight:bold">Изменения сохранены. Участникам команды, у которых добавился или изменился e-mail, высланы сгенерированные пароли.</p>';
      else
        echo '<p style="font-weight:bold">Заявка принята. Дальнейшие инструкции будут высланы на указанный e-mail.</p>';

      $closed = false;
      $ac = $teamname;
    }
  }
}
if ($closed) {
  echo '
Дверь в Тренерскую заперта изнутри.<br />
Сквозь дверь Вы слышите звуки эмоционального обсуждения, но чего именно - не разобрать.<br />
<br />
В двери Вы видите прорезь для писем с надписью <b>"Аккредитация тренеров"</b>, а под ней - бланки для заполнения:<br />
<br />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>function onSubmit(token){document.getElementById("coachform").submit();}</script>
<form id="coachform" method="POST">
<table>
<tr><td>Название команды*</td><td><input type="text" name="teamname" style="width:300px" /></td></tr>
<tr><td>Сайт (URL)*</td><td><input type="text" name="teamsite" style="width:300px" /></td></tr>
<tr><td>Эмблема команды</td><td><input type="text" name="teamlabel" style="width:300px" placeholder="не обязательно" /></td></tr>
<tr><td>Главный тренер*</td><td><input type="text" name="coach1" style="width:300px" placeholder="ник или имя" /> e-mail*<input type="text" name="cmail1" style="width:300px" /></td></tr>
<tr><td>Второй тренер</td><td><input type="text" name="coach2" style="width:300px" placeholder="не обязательно" /> e-mail&nbsp; <input type="text" name="cmail2" style="width:300px" placeholder="не обязательно" /></td></tr>
</table>
<p>* - обязательные поля</p>
<p>
<br>
<b>Состав команды:</b> (его можно заполнить позднее, тренер не обязан быть игроком команды)</p>
<p>Все участники, у которых указан e-mail, получат пароли на доступ к сайту с возможностью
самостоятельно делать ставки и оставлять комментарии в фан-зонах.<br>
Если указан e-mail и отмечено "высылать материалы турнира", игрок будет получать:<br />
- программки туров после их создания (за 5 дней до начала тура),<br />
- напоминания утром в день тура или за день до тура, если на сервере ещё нет прогноза,<br />
- публикацию прогнозов всех игроков после начала первого МдП,<br />
- публикацию итогов или обзоров после завершения тура.</p>
<p>
<table>
<tr><th>№</th><th>ник или имя</th><th>e-mail &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </th><th>высылать материалы турнира</th></tr>
';
  for ($i=1; $i<=16; $i++)
    echo '
<tr>
  <td>' . $i . '</td>
  <td><input type="text" name="player' . $i . '" style="width:200px" /></td>
  <td><input type="text" name="email' . $i . '" style="width:200px" placeholder="не обязательно" /></td>
  <td><input type="checkbox" name="prog' . $i . '" /></td>
</tr>';
  echo '
</table>
<br>
<button class="g-recaptcha" data-sitekey="6Lc8dioUAAAAAArLlgGJDVEy6nwwQDSKWrmAOvkq" data-callback="onSubmit"> отправить заявку </button></p>
</form>
';
}
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
  $season = (strlen($s) == 7) ? $s : (($ac == 'SUI') ? '2019-4' : '2019-20');
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
else {
  echo 'Вниманию тренеров: у нас есть "фан-зоны" для обсуждения турниров.<br>
Если хотите в них участвовать, после входа на сайт с паролем кликните по пункту "Показ фан-зоны" в личном кабинете (правая панель навигации).<br>
Игрокам они тоже доступны.<br>
При необходимости можно сделать закрытые зоны обсуждения для команд-участников. Если надо - сообщите.</p>';
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  $player = [];
  foreach ($codes as $line) {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    $role = trim($role);
    $player[$team][$code] = ['code' => $code, 'name' => $name, 'email' => $email, 'role' => $role];
    if ($role == 'coach' && ($name == $_POST['coach1'] || $name == $_SESSION['Coach_name'])) {
      $team_name = $team;
      $closed = false;
    }
  }
  if (isset($sites[$team_name]) && $sites[$team_name]) {
    $logo = $sites[$team_name];
    $cut = strpos($logo, 'href=') + 6;
    $teamsite = substr($logo, $cut, strpos($logo, '"', $cut) - $cut);
    $cut = strpos($logo, 'src=') + 5;
    $teamlabel = substr($logo, $cut, strpos($logo, '"', $cut) - $cut);
    echo $logo;
  }
  else $teamsite = $teamlabel = '';
  $coach = [];
  foreach ($player[$team_name] as $pcode => $pl)
    if ($pl['role'] == 'coach')
      $coach[] = $pl;

  if (count($coach) == 1)
    $coach[] = ['name' => '', 'email' => ''];
  echo '
<form id="coachform2" method="POST">
<table>
<tr><td>Название команды*</td><td><b>'.$team_name.'</b><input type="hidden" name="teamname" value="'.$team_name.'" /></td></tr>
<tr><td>Сайт (URL)*</td><td><input type="text" name="teamsite" style="width:300px" value="'.$teamsite.'" /></td></tr>
<tr><td>Эмблема команды</td><td><input type="text" name="teamlabel" style="width:300px" value="'.$teamlabel.'" /> &nbsp; <input type="submit" name="upload" value=" загрузить эмблему " disabled="disabled" /></td></tr>
<tr><td>Главный тренер*</td><td><input type="text" name="coach1" style="width:300px" value="'.$coach[0]['name'].'" /> e-mail*<input type="text" name="cmail1" style="width:300px" value="'.$coach[0]['email'].'" /></td></tr>
<tr><td>Второй тренер</td><td><input type="text" name="coach2" style="width:300px" value="'.$coach[1]['name'].'" /> e-mail&nbsp; <input type="text" name="cmail2" style="width:300px" value="'.$coach[1]['email'].'" /></td></tr>
</table>
<p>* - обязательные поля</p>
<p>
<br>
<strong>Состав команды:</strong> (тренер не обязан быть игроком команды)</p>
<!--p>Вы можете изменить номера игроков для предварительного формирования основного состава на все туры:<br>
в него попадут игроки №№1-5</p-->
<p>Заполнение колонки "имя" не обязательно - если не заполнять, там будет повторён ник игрока.<br>
Но если есть желание указать реальное имя - заполняйте.<br>
Имя не используется на автоматически генерируемых страницах, но может быть использовано в обзорах.</p>
<p>Все участники, у которых указан e-mail, получат пароли на доступ к сайту с возможностью
самостоятельно делать ставки и оставлять комментарии в фан-зонах.</p>
<p>Если указан e-mail и отмечено "высылать материалы турнира", игрок будет получать:<br />
- программки туров после их создания (за 5 дней до начала тура),<br />
- напоминания утром в день тура или за день до тура, если на сервере ещё нет прогноза,<br />
- публикацию прогнозов всех игроков после начала первого МдП,<br />
- публикацию итогов или обзоров после завершения тура.</p>
<table>
<tr><th>№</th><th>ник</th><th>имя (то же, что ник?)</th><th>e-mail &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </th><th>высылать материалы турнира</th></tr>
';
  $squad = file($online_dir.'UNL/'.$s.'/'.$team_name.'.csv');
  $players = [];
  for ($i=1; $i<=16; $i++)
    $players[$i] = ['code' => '', 'name' => '', 'mail' => '', 'role' => ''];

  $i = 1;
  foreach ($squad as $line) {
    list($name, $mail, $role) = explode(';', trim($line));
    $code = $name;
    $name = (isset($player[$team_name][$code]['name']) && $player[$team_name][$code]['name']) ? $player[$team_name][$code]['name'] : $name;
    $players[$i++] = ['code' => $code, 'name' => $name, 'mail' => $player[$team_name][$code]['email'], 'role' => $player[$team_name][$code]['role']];
//    $players[$i++] = ['code' => $player[$team_name][$name]['code'], 'name' => $player[$team_name][$name]['name'], 'mail' => $player[$team_name][$name]['email'], 'role' => $player[$team_name][$name]['role']];
  }
  for ($i=1; $i<=16; $i++) {
    if ($players[$i]['role'] == 'coach') {
      $prog = $rmnd = $pred = $itog = 'checked="checked"';
      $disabled = ' disabled="disabled"';
    }
    else {
      $prog = ($players[$i]['role'] == 'on') ? ' checked="checked"' : '';
      $disabled = '';
    }
    echo '
<tr>
  <td><input type="text" name="pos' . $i . '" style="width:24px; text-align:right" value="'.$i.'" /></td>
  <td><input type="text" name="code' . $i . '" style="width:200px" value="'.$players[$i]['code'].'" /></td>
  <td><input type="text" name="player' . $i . '" style="width:200px" value="'.$players[$i]['name'].'" /></td>
  <td><input type="text" name="email' . $i . '" style="width:200px" value="'.$players[$i]['mail'].'" /></td>
  <td><input type="checkbox" name="prog' . $i . '"' . $prog . $disabled.' /></td>
</tr>';
  }
  echo '
</table>
<p>
<br>
<button class="g-recaptcha"> сохранить изменения </button></p>
</form>
';
}

?>