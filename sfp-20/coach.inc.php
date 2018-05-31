<?php
function make_passwd($code, $name, $mail) {
  global $mailpass;
  global $online_dir;
  if (is_file($online_dir.'IST/passwd/'.$code)) return true;
  if (isset($mailpass[$mail])) {
    file_put_contents($online_dir.'IST/passwd/'.$code, $mailpass[$mail].':player');
    return true;
  }
  $gp = '';
  $mix = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
  for ($i=0; $i<8; $i++)
    $gp .= $mix[rand(0,56)];

  file_put_contents($online_dir.'IST/passwd/'.$code, md5($gp).":player");
  send_email('FPrognoz.org <fp@fprognoz.org>', $name, $mail,
                   'Пароль для сайта FPrognoz.org',
'
Вы получили случайно-сгенерированный пароль для входа на сайт https://fprognoz.org: '.$gp.'

При входе в качестве имени указывайте ваш e-mail: '.$mail.'

Пароль можно сменить на странице https://fprognoz.org/?m=pass


Удачи!
');
}

$access = file($data_dir . 'auth/.access');
$mailpass = array();
foreach ($access as $line) {
  list($code, $cc, $team, $name, $mail, $hash, $role) = explode(';', $line);
  if ($mail && $hash && !isset($mailpass[$mail]))
    $mailpass[$mail] = $hash;

}

$closed = true;
echo '<p class="title text15b">&nbsp;&nbsp;&nbsp;Тренерская (начался турнир, поэтому добаление и изменение команд невозможно)</p>
<hr size="1" width="98%" />';
if (isset($_SESSION['Coach_name'])) {
  $s = $cur_year;
  $codes = file($online_dir.'IST/'.$s.'/codes.tsv');
  eval('$sites = '.file_get_contents($online_dir.'IST/'.$s.'/sites.inc'));
  $player = [];
  foreach ($codes as $line) {
    list($code, $team, $name, $email, $role) = explode('	', trim($line));
    $player[$team][$code] = ['code' => $code, 'name' => $name, 'email' => $email, 'role' => $role];
    if ($role == 'coach' && $name == $_SESSION['Coach_name']) {
      $team_name = $team;
      $closed = false;
    }
  }
  if (count($_POST)) {
    if (!$_POST['teamname'] || !$_POST['teamsite'] || !$_POST['coach1'] || !$_POST['cmail1'])
      echo '<p style="color:red;font-weight:bold">Не заполнены все обязательные поля!</p>';
    else {
      $s = $cur_year;
      $teamname = $_POST['teamname'];
      $postdata = fopen($online_dir.'IST/'.$s.'/postdata2', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $sites = file($online_dir.'IST/'.$s.'/sites.inc');
      $sites_out = '';
      $edit = false;
      foreach ($sites as $line) if ($line[0] != ')') {
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
      file_put_contents($online_dir.'IST/'.$s.'/sites.inc', $sites_out);

      $codes = file($online_dir.'IST/'.$s.'/codes.tsv');
      $codes_out = '';
      foreach ($codes as $line)
        if (!strpos($line, "	$teamname	"))
          $codes_out .= $line;

      $csv = '';
      for ($i=1; $i<13; $i++)
        if (($player = trim($_POST['player'.$i])) || ($code = $_POST['code'.$i])) {
            $email = $_POST['email'.$i];
            $code = trim($_POST['code'.$i]);
            if (!$code)
              $code = strtr($player, ' ', '_');
            else if (!$player)
              $player = $code;

            $c1 = $c2 = false;
            if ($email && ($email == $_POST['cmail1'])) {
              $codes_out .= $code.'	'.$teamname.'	'.$player.'	'.$email.'	coach	
';
              make_passwd($code, $player, $email);
              $c1 = true;
            }
            else if ($email && ($email == $_POST['cmail2'])) {
              $codes_out .= $code.'	'.$teamname.'	'.$player.'	'.$email.'	coach	
';
              make_passwd($code, $player, $email);
              $c1 = $c2 = true;
            }
            else if (($email = $_POST['email'.$i])) {
              $codes_out .= $code.'	'.$teamname.'	'.$player.'	'.$email.'	'.$_POST['prog'.$i].';'.$_POST['rmnd'.$i].';'.$_POST['pred'.$i].';'.$_POST['itog'.$i].'	
';
              make_passwd($code, $player, $email);
            }
            $csv .= $code.';'.$email.';
';
        }

      if (!$c1) {
        $coach = $_POST['coach1'];
        $code = strtr($coach, ' ', '_');
        $codes_out .= $code.'	'.$teamname.'	'.$coach.'	'.$_POST['cmail1'].'	coach	
';
        make_passwd($code, $coach, $_POST['cmail1']);
      }
      if (!$c2 && ($coach = $_POST['coach2'])) {
        $code = strtr($coach, ' ', '_');
        $codes_out .= $code.'	'.$teamname.'	'.$coach.'	'.$_POST['cmail2'].'	coach	
';
        make_passwd($code, $coach, $team['cmail2']);
      }
      file_put_contents($online_dir.'IST/'.$s.'/codes.tsv', $codes_out);
      file_put_contents($online_dir.'IST/'.$s.'/'.$teamname.'.csv', $csv);
      build_access();

      echo '<p style="font-weight:bold">Изменения сохранены.</p>';
      $closed = false;
    }
  }
}
else {
  if (isset($_POST['teamname'])) {
    if (!$_POST['teamname'] || !$_POST['teamsite'] || !$_POST['coach1'] || !$_POST['cmail1'])
      echo '<p style="color:red;font-weight:bold">Не заполнены все обязательные поля!</p>';
    else {
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
    if ($human) {
      $s = $cur_year;
      //$hq = file($online_dir.'IST/'.$s.'/hq');
      $postdata = fopen($online_dir.'IST/'.$s.'/postdata', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $teamname = $_POST['teamname'];
      $postdata = fopen($online_dir.'IST/'.$s.'/postdata2', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $sites = file($online_dir.'IST/'.$s.'/sites.inc');
      $sites_out = '';
      $edit = false;
      foreach ($sites as $line) if ($line[0] != ')') {
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
      file_put_contents($online_dir.'IST/'.$s.'/sites.inc', $sites_out);

      $codes = file($online_dir.'IST/'.$s.'/codes.tsv');
      $codes_out = '';
      foreach ($codes as $line)
        if (!strpos($codes, "	$teamname	"))
          $codes_out .= $line;

      $coach = trim($_POST['coach1']);
      $have[] = $coach;
      $code = strtr($coach, ' ', '_');
      $codes_out .= $code.'	'.$teamname.'	'.$coach.'	'.$_POST['cmail1'].'	coach	
';
      make_passwd($code, $coach, $_POST['cmail1']);
      if ($coach = trim($_POST['coach2'])) {
        $have[] = $coach;
        $code = strtr($coach, ' ', '_');
        $codes_out .= $code.'	'.$teamname.'	'.$coach.'	'.$_POST['cmail2'].'	coach	
';
        make_passwd($code, $coach, $team['cmail2']);
      }
      $csv = '';
      for ($i=1; $i<13; $i++) {
        if ($player = trim($_POST['player'.$i])) {
          if (($email = $_POST['email'.$i]) && !in_array($player, $have)) {
            $code = strtr($player, ' ', '_');
            $codes_out .= $code.'	'.$teamname.'	'.$player.'	'.$email.'	'.$_POST['prog'.$i].';'.$_POST['rmnd'.$i].';'.$_POST['pred'.$i].';'.$_POST['itog'.$i].'	
';
            make_passwd($code, $player, $email);
          }
          $csv .= $player.';'.$email.';
';
        }
      }
      file_put_contents($online_dir.'IST/'.$s.'/codes.tsv', $codes_out);
      file_put_contents($online_dir.'IST/'.$s.'/'.$teamname.'.csv', $csv);
      build_access();

      echo '<p style="font-weight:bold">Заявка принята. Дальнейшие инструкци будут высланы на указанный e-mail.</p>';
      $closed = false;
    }
  }
}
if ($closed) {
  echo '
Дверь в Тренерскую заперта изнутри.<br />
Сквозь дверь Вы слышите звуки эмоционального обсуждения, но чего именно - не разобрать.<br />
<br />
В двери Вы видете прорезь для писем с надписью <b>"Аккредитация тренеров"</b>, а под ней - бланки для заполнения:<br />
<br />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>function onSubmit(token){document.getElementById("coachform").submit();}</script>
<form id="coachform" method="POST">
<table>
<tr><td>Название команды*</td><td><input type="text" name="teamname" style="width:300px" /></td></tr>
<tr><td>Сайт (URL)*</td><td><input type="text" name="teamsite" style="width:300px" /></td></tr>
<tr><td>Эмблема команды</td><td><input type="text" name="teamlabel" style="width:300px" placeholder="не обязательно" /></td></tr>
<tr><td>Главный тренер*</td><td><input type="text" name="coach1" style="width:300px" placeholder="ник или имя" /> e-mail*<input type="text" name="cmail1" style="width:300px" /></td></tr>
<tr><td>Второй тренер</td><td><input type="text" name="coach2" style="width:300px" placeholder="не обязательно" /> e-mail <input type="text" name="cmail2" style="width:300px" placeholder="не обязательно" /></td></tr>
</table>
* - обязательные поля
<p><b>Состав команды:</b> (можно заполнить позднее, тренер не обязан быть игроком команды)</p>
<table>
<tr><th>№</th><th>ник или имя</th><th>e-mail &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; высылать:</th><th>программки</th><th>напоминания</th><th>прогнозы</th><th>итоги</th></tr>
';
  for ($i=1; $i<=12; $i++)
    echo '
<tr>
  <td>' . $i . '</td>
  <td><input type="text" name="player' . $i . '" style="width:200px" /></td>
  <td><input type="text" name="email' . $i . '" style="width:200px" placeholder="не обязательно" /></td>
  <td><input type="checkbox" name="prog' . $i . '" /></td>
  <td><input type="checkbox" name="rmnd' . $i . '" /></td>
  <td><input type="checkbox" name="pred' . $i . '" /></td>
  <td><input type="checkbox" name="itog' . $i . '" /></td>
</tr>';
  echo '
</table>
<p>если указан e-mail и отмечены соответствующие поля, игрок будет получать:<br />
- программки: программки туров после их создания (за 5 дней до начала тура),<br />
- напоминания: напоминания за сутки до тура, если на сервере ещё нет прогноза,<br />
- прогнозы: публикацию прогнозов всех игроков после начала первого МдП,<br />
- итоги: публикацию итогов после завершения тура.</p>
<p><button class="g-recaptcha" data-sitekey="6Lc8dioUAAAAAArLlgGJDVEy6nwwQDSKWrmAOvkq" data-callback="onSubmit" disabled="disabled"> отправить заявку </button></p>
</form>
';
}
else {
  echo 'Вниманию тренеров: у нас есть "фан-зоны" для обсуждения турниров.
Если хотите в них участвовать, откройте <a href="/">главную страницу сайта</a> и кликните по цветному квадрату справа от "Фан-зона".
Игрокам они тоже доступны.<br />
При необходимости можно сделать закрытые зоны обсуждения для команд-участников. Если надо - сообщите.</p>';
  $codes = file($online_dir.'IST/'.$s.'/codes.tsv');
  eval('$sites = '.file_get_contents($online_dir.'IST/'.$s.'/sites.inc'));
  $player = [];
  foreach ($codes as $line) {
    list($code, $team, $name, $email, $role) = explode('	', trim($line));
    $player[$team][$code] = ['code' => $code, 'name' => $name, 'email' => $email, 'role' => $role];
    if ($role == 'coach' && $name == $_SESSION['Coach_name']) {
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
<tr><td>Эмблема команды</td><td><input type="text" name="teamlabel" style="width:300px" value="'.$teamlabel.'" /> &nbsp; <input type="submit" name="upload" value=" загрузить эмблему " disabled="disabled"></td></tr>
<tr><td>Главный тренер*</td><td><input type="text" name="coach1" style="width:300px" value="'.$coach[0]['name'].'" /> e-mail*<input type="text" name="cmail1" style="width:300px" value="'.$coach[0]['email'].'" /></td></tr>
<tr><td>Второй тренер</td><td><input type="text" name="coach2" style="width:300px" value="'.$coach[1]['name'].'" /> e-mail <input type="text" name="cmail2" style="width:300px" value="'.$coach[1]['email'].'" /></td></tr>
</table>
* - обязательные поля
<p><b>Состав команды:</b> (тренер не обязан быть игроком команды)</p>
<!--p>Вы можете изменить номера игроков для предварительного формирования основного состава на все туры:<br />
в него попадут игроки №№1-6</p-->
<p>Добавилось поле "имя". Оно необязательное - если его не трогать, там будет повторён ник игрока.
Но если есть желание указать реальное имя - заполняйте. Имя не используется на автоматически генерируемых страницах, но может быть использовано в обзорах.</p>
<table>
<tr><th>№</th><th>ник</th><th>имя (то же, что ник?)</th><th>e-mail &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; высылать:</th><th>программки</th><th>напоминания</th><th>прогнозы</th><th>итоги</th></tr>
';
  $squad = file($online_dir.'IST/'.$s.'/'.$team_name.'.csv');
  $players = [];
  for ($i=1; $i<=12; $i++)
    $players[$i] = ['name' => '', 'mail' => '', 'role' => ''];

  $i = 1;
  foreach ($squad as $line) {
    list($name, $mail, $role) = explode(';', trim($line));
    $code = $name;
    $name = (isset($player[$team_name][$code]['name']) && $player[$team_name][$code]['name']) ? $player[$team_name][$code]['name'] : $name;
    $players[$i++] = ['code' => $code, 'name' => $name, 'mail' => $player[$team_name][$code]['email'], 'role' => $player[$team_name][$code]['role']];
//    $players[$i++] = ['code' => $player[$team_name][$name]['code'], 'name' => $player[$team_name][$name]['name'], 'mail' => $player[$team_name][$name]['email'], 'role' => $player[$team_name][$name]['role']];
  }
  for ($i=1; $i<=12; $i++) {
    if ($players[$i]['role'] == 'coach') {
      $prog = $rmnd = $pred = $itog = 'checked="checked"';
      $disabled = ' disabled="disabled"';
    }
    else {
      list($prog, $rmnd, $pred, $itog) = explode(';', $players[$i]['role']);
      $prog = ($prog == 'on') ? ' checked="checked"' : '';
      $rmnd = ($rmnd == 'on') ? ' checked="checked"' : '';
      $pred = ($pred == 'on') ? ' checked="checked"' : '';
      $itog = ($itog == 'on') ? ' checked="checked"' : '';
      $disabled = '';
    }
    echo '
<tr>
  <td><input type="text" name="pos' . $i . '" style="width:20px" value="'.$i.'" /></td>
  <td><input type="text" name="code' . $i . '" style="width:200px" value="'.$players[$i]['code'].'" /></td>
  <td><input type="text" name="player' . $i . '" style="width:200px" value="'.$players[$i]['name'].'" /></td>
  <td><input type="text" name="email' . $i . '" style="width:200px" value="'.$players[$i]['mail'].'" /></td>
  <td><input type="checkbox" name="prog' . $i . '"' . $prog . $disabled.' /></td>
  <td><input type="checkbox" name="rmnd' . $i . '"' . $rmnd . $disabled.' /></td>
  <td><input type="checkbox" name="pred' . $i . '"' . $pred . $disabled.' /></td>
  <td><input type="checkbox" name="itog' . $i . '"' . $itog . $disabled.' /></td>
</tr>';
  }
  echo '
</table>
<p>если указан e-mail и отмечены соответствующие поля, игрок будет получать:<br />
- программки: программки туров после их создания (за 5 дней до начала тура),<br />
- напоминания: напоминания за сутки до тура, если на сервере ещё нет прогноза,<br />
- прогнозы: публикацию прогнозов всех игроков после начала первого МдП,<br />
- итоги: публикацию итогов после завершения тура.</p>
<p><button class="g-recaptcha" disabled="disabled"> сохранить изменения </button></p>
</form>
';
}
?>