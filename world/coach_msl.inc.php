<?php
// при назначении тренера, если нет e-mail, брать его из списка игроков

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

  file_put_contents($online_dir.'UNL/passwd/'.$code, md5($gp).':player');
  send_email('FPrognoz.org <fp@fprognoz.org>', $name, $mail,
                   'Пароль для сайта FPrognoz.org',
'
Вы получили случайно-сгенерированный пароль для входа на сайт ' . $this_site . ': '.$gp.'

При входе в качестве имени указывайте ваш ник '.$code.' или e-mail: '.$mail.'

Пароль можно сменить на странице ' . $this_site . '/?m=pass


Удачи!
');
}
$escape_chars = [
"\\" => '_',
"/" => '_',
"\0" => '_',
"`" => '_',
"'" => '_',
'"' => '_',
'*' => '_',
';' => '_',
':' => '_',
'|' => '_',
];
$closed = false;
$ac_head = '';
echo '<p class="title text15b">&nbsp;&nbsp;&nbsp;Тренерская Лиги Сайтов</p>
<hr size="1" width="98%">';
//Закрыто. На двери висит табличка <strong>"Регистрация в турнир откроется 28 ноября 2020 г."</strong>';
//exit;

  $s = $cur_year;
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  foreach ($codes as $line) {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    $role = trim($role);
    if ($role == 'coach'
      && (
          (isset($_SESSION['Coach_name']) && ($code == $_SESSION['Coach_name'] || $name == $_SESSION['Coach_name']))
        ||
          (isset($_SESSION['Coach_mail']) && $email == $_SESSION['Coach_mail'])
         )
       )
    {
      $team_name = $team;
      $closed = false;
      break;
    }
  }

  if (isset($_POST['teamname'])) {

// Обработка действий в ЛИГЕ САЙТОВ

    if (!$_POST['teamname'] || !$_POST['teamsite'] || !$_POST['coach1'] || !$_POST['cmail1'])
      echo '<p style="color:red;font-weight:bold">Не заполнены все обязательные поля!</p>';
    else {
      if (isset($_SESSION['Coach_name']))
        $human = true;
      else
      {
/*
        spl_autoload_register(function ($class) {
          $class = str_replace('\\', '/', $class);//'
          $path = dirname(__FILE__).'/'.$class.'.php';
          require_once $path;
        });
        $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret);
        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                          ->setExpectedAction('registration')
                          ->setScoreThreshold(0.5)
                          ->verify($_POST['token'], $_SERVER['REMOTE_ADDR'])
                          ->toArray();
        $human = ($resp['success'] && ($resp['score'] >= 0.5)) || $resp['error-codes'][0] == 'timeout-or-duplicate';
        $postdata = fopen($online_dir.'UNL/'.$s.'/postdata2', 'a');
        fwrite($postdata, var_export($_POST, true) . ',');
        fwrite($postdata, var_export($resp, true) . ',');
        fclose($postdata);
*/
        $human = true;
      }
    }
    if ($human) {
      $s = $cur_year;
      $postdata = fopen($online_dir.'UNL/'.$s.'/postdata', 'a');
      fwrite($postdata, var_export($_POST, true) . ',');
      fclose($postdata);

      $teamname = rawurldecode($_POST['teamname']);

      if (isset($_FILES['file']) && $fn = $_FILES['file']['name']) {
        $fn = 'images/sites/'.strtr($teamname, $escape_chars).substr($fn, strrpos($fn, '.'));
        move_uploaded_file($_FILES['file']['tmp_name'], $fn);
        $fn = '/'.$fn;
      }
      else if (substr($_POST['teamlabel'], 0, 5) == 'http:')
      {
        $fn = 'images/sites/'.strtr($teamname, $escape_chars).substr($_POST['teamlabel'], strrpos($_POST['teamlabel'], '.'));
        file_put_contents($fn, file_get_contents($_POST['teamlabel']));
        $fn = '/'.$fn;
      }
      else
        $fn = $_POST['teamlabel'];

      $sites_file = file($online_dir.'UNL/'.$s.'/sites.inc');
      $sites_out = '';
      $edit = false;
      foreach ($sites_file as $line) if ($line[0] != ')') {
        if (strpos($line, $_POST['teamname'])) {
          $sites_out .= "'$teamname' => '<a href=\"".$_POST['teamsite']."\" target=\"_blank\"><img src=\"".$fn."\" style=\"height:31px\"></a>',\n";
          $edit = true;
        }
        else
          $sites_out .= $line;
      }
      if (!$edit)
        $sites_out .= "'$teamname' => '<a href=\"".$_POST['teamsite']."\" target=\"_blank\"><img src=\"".$fn."\" style=\"height:31px\"></a>',\n";

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
        $player = '';
        if ($_POST['code'.$i] || $player = trim($_POST['player'.$i])) {
          $code = $_POST['code'.$i] ? $_POST['code'.$i] : $player;
          if (!isset($_POST['player'.$i]) || !$player)
            $player = $code;

          // в списке игроков у тренера может быть не указан e-mail, поэтому проверяем тренерские
          if ($code == $_POST['coach1'])
            $email = $_POST['cmail1'];
          else if ($code == $_POST['coach2'])
            $email = $_POST['cmail2'];
          else
            $email = $_POST['email'.$i];

          $order[$code] = isset($_POST['pos'.$i]) ? $_POST['pos'.$i] : $i;
          $squad[$code] = $code.'	'.$teamname.'	'.$player.'	'.$email.'	'
          . (in_array($code, [trim($_POST['coach1']), trim($_POST['coach2'])]) ? 'coach' : ($_POST['prog'.$i]) ?? '') . '	
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
          make_passwd($coach, $coach, $_POST['cmail'.$i]);
        }
      }
      $codes_out .= $squad[$_POST['coach1']];
      if ($_POST['coach2'])
        $codes_out .= $squad[$_POST['coach2']];

      asort($order);
      $csv = '';
      foreach ($order as $code => $pos)
      {
        if ($pos)
          $csv .= $code.';;;
';
        if (!in_array($code, [$_POST['coach1'], $_POST['coach2']]))
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

$closed = true;

//Если Вы уверены, что ваша команда успеет подготовиться за то малое время, что осталось до начала первого тура,
//подайте заявку e-mail-ом или на странице <a href="/?a=world&m=hq">Президиум</a>.<br>
//Заявка может быть учтена только при сохранении чётного количества участников турнира.';
if ($closed)
{
  echo '
Дверь в Тренерскую заперта. Изнутри не слышно ни звука.<br>
На двери висит табличка <strong>"Регистрация в турнир закончена"</strong>.<br>';
}
else
{
  if (!isset($team_name))
    echo '<p>Прежде, чем регистрировать команду, проверьте - может быть, она уже есть в списке <a href ="/?a=world&m=player&l=s">участников</a>.<br>
Команда может попасть туда как участник предыдущего сезона - в этом случае авторизуйтесь на сайте и отредактируйте заявку команды.<br>
Если Вы забыли или потеряли пароль, войдите на сайт без пароля по временной ссылке.<br>
Она будет выслана, если Вы при входе укажете e-mail с пустым полем пароля и кликните по "войти без пароля?".<br>
Вы сможете задать новый пароль, если после входа по полученной ссылке сразу перейдёте на страницу "Смена пароля" в личном кабинете (правая панель навигации).<br>
Если ваша проблема с доступом к своей сборной не решается, свяжитесь с <a href="/?a=world&m=hq">Президиумом Лиги</a>.</p>
<p>Последний срок открытой регистрации - 17 января.<br>
Турнир Лиги Сайтов пройдёт с 22 января по 8 мая.<br>
До его начала будут проведены 3 пробных тура - 1, 8 и 15 января.</p>
';
  else
    echo '<p>Вниманию тренеров: у нас есть "фан-зоны" для обсуждения турниров.<br>
Если хотите в них участвовать, после входа на сайт с паролем кликните по пункту "Показ фан-зоны" в личном кабинете (правая панель навигации).<br>
Игрокам они тоже доступны.<br>
При необходимости можно сделать закрытые зоны обсуждения для команд-участников. Если надо - сообщите.</p>';
  $codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
  $player = [];
  foreach ($codes as $line) {
    list($code, $team, $name, $email, $role) = explode('	', $line);
    $role = trim($role);
    $player[$team][$code] = ['code' => $code, 'name' => $name, 'email' => $email, 'role' => $role];
    if ($role == 'coach' && ((isset($_POST['coach1']) && $name == $_POST['coach1']) || (isset($_SESSION['Coach_name']) && $name == $_SESSION['Coach_name'])))
    {
      $team_name = $team;
      $closed = false;
    }
  }
  eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
  if (isset($team_name) && isset($sites[$team_name]) && $sites[$team_name]) {
    $logo = $sites[$team_name];
    $cut = strpos($logo, 'href=') + 6;
    $teamsite = substr($logo, $cut, strpos($logo, '"', $cut) - $cut);
    $cut = strpos($logo, 'src=') + 5;
    $teamlabel = substr($logo, $cut, strpos($logo, '"', $cut) - $cut);
    echo '<div style="padding-left: 20px">'.$logo.'</div>';
  }
  else $teamsite = $teamlabel = '';
  $coach = [];
  foreach ($player[$team_name] as $pcode => $pl)
    if ($pl['role'] == 'coach')
      $coach[] = $pl;

  if (count($coach) == 1)
    $coach[] = ['name' => '', 'email' => ''];
  echo '
<form id="coachform2" method="POST" enctype="multipart/form-data">
<style>
label.file-upload{position:relative;overflow:hidden}
label.file-upload input[type=file]{display:block;position:absolute;top:0;right:0;min-width:100%;min-height:100%;font-size:100px;text-align:right;filter:alpha(opacity=0);opacity:0;outline:0;background:#fff;cursor:inherit}
.fpgrid-1 {width:20px; margin: .5rem 0 0 15px}
.gutter-2.row {
    margin-right: -1px;
    margin-left: -1px;
  }
.gutter-2 > [class^="col-"], .gutter-2 > [class^=" col-"] {
    padding-right: 5px;
    padding-left: 5px;
}
</style>
<script src="/js/file-upload.js"></script>
<script>$(document).ready(function(){$(".file-upload").file_upload();})</script>
<div class="container-fluid">
  <div class="form-group row">
    <label for="teamname" class="col-sm-2 col-form-label">Название команды'.($team_name ? '' : '*').'</label>
    <div class="col-sm-8">
      '.($team_name ? '<strong>'.$team_name.'</strong>' : '').'<input type="'.($team_name ? 'hidden' : 'text').'" class="form-control" name="teamname" value="'.rawurlencode($team_name).'">
    </div>
  </div>
  <div class="form-group row">
    <label for="teamsite" class="col-sm-2 col-form-label">Сайт (URL)*</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="teamsite" name="teamsite" value="'.$teamsite.'">
    </div>
  </div>
  <div class="form-group row">
    <label for="teamlabel" class="col-sm-2 col-form-label">Эмблема</label>
    <div class="col-sm-5"><input type="text" class="form-control" id="teamlabel" name="teamlabel" value="'.$teamlabel.'"></div>
    <label class="file-upload btn btn-secondary">или выбор файла <input type="file" id="file" name="file"></label>
  </div>
  <div class="form-group row">
    <label for="coach1" class="col-sm-2 col-form-label">Главный тренер*</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="coach1" name="coach1" value="'.$coach[0]['code'].'">
    </div>
  </div>
  <div class="form-group row">
    <label for="cmail1" class="col-sm-2 col-form-label">E-mail гл. тренера*</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="cmail1" name="cmail1" value="'.$coach[0]['email'].'">
    </div>
  </div>
  <div class="form-group row">
    <label for="coach2" class="col-sm-2 col-form-label">Второй тренер</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="coach2" name="coach2" placeholder="не обязательно" value="'.($coach[1]['code'] ?? '').'">
    </div>
  </div>
  <div class="form-group row">
    <label for="cmail2" class="col-sm-2 col-form-label">E-mail 2-го тренера</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="cmail2" name="cmail2" placeholder="обязательно, если указан 2-й тренер" value="'.$coach[1]['email'].'">
    </div>
  </div>
</div>
<p>* - обязательные поля</p>
<p>
<br>
<strong>Состав команды:</strong> (учтите, что тренер не обязан быть игроком команды -
не забудьте добавить себя в состав, если собираетесь "выходить на поле")</p>
<p>Вы можете изменить номера игроков для предварительного формирования основного состава на все туры -
в него попадут игроки №№1-6.</p>
<p>Все участники, у которых указан e-mail, получат пароли на доступ к сайту с возможностью
самостоятельно делать ставки и оставлять комментарии в фан-зонах.</p>
<p>Если указан e-mail и отмечено "высылать материалы турнира", игрок будет получать:<br />
- программки туров после их создания (за 5 дней до начала тура),<br />
- напоминания утром в день тура или за день до тура, если на сервере ещё нет прогноза,<br />
- публикацию прогнозов всех игроков после начала первого МдП,<br />
- публикацию итогов или обзоров после завершения тура.</p>
<div class="container-fluid">
  <div class="form-group gutter-2 row">
    <div class="col-sm-1">№</div>
    <div class="col-sm-3">игровое имя участника</div>
    <div class="col-sm-3 hidden">имя, или повтор ника</div>
    <div class="col-sm-3">e-mail, не обязательно</div>
    <div class="col-sm-2 text-left" title="высылать материалы турнира"><i class="fas fa-envelope"></i></div>
  </div>
';
  $squad = isset($team_name) ?  file($online_dir.'UNL/'.$s.'/'.$team_name.'.csv') : [];
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
  <div class="form-group gutter-2 row">
    <div class="col-sm-1"><input type="text" class="form-control" name="pos' . $i . '" value="'.$i.'"></div>
    <div class="col-sm-3"><input type="text" class="form-control" id="code' . $i . '" name="code' . $i . '" placeholder="игровое имя участника" value="'.$players[$i]['code'].'"></div>
    <div class="col-sm-3 hidden"><input type="text" class="form-control" id="player' . $i . '" name="player' . $i . '" placeholder="имя участника" value="'.$players[$i]['name'].'"></div>
    <div class="col-sm-5 d-flex">
      <div>
        <input type="text" class="form-control" id="email' . $i . '" name="email' . $i . '" placeholder="e-mail, не обязательно" value="'.$players[$i]['mail'].'">
      </div>
      <div>
        <label for="email' . $i . '" class="fpgrid-1"><input type="checkbox" class="form-check-input" name="prog' . $i . '"' . $prog . $disabled.'></label></div>
      </div>
  </div>';
  }
  echo '
</div>
<br>
<button class="btn btn-primary ml-4"> сохранить изменения </button></p>
</form>
';
}
?>