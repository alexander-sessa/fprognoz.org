<script type="text/javascript">//<![CDATA[
function show_alert() {
  var str = document.forms[0]["user"].value;
  if ( str.length < 4) {
    alert("Ошибка: не указано или неверно указано имя!");
    return false;
  }
  var str = document.forms[0]["email"].value;
  if ( str.length < 4) {
    alert("Ошибка: не указан EMail!");
    return false;
  }
  if ( str.search("[@]") == -1) {
    alert("Ошибка: недопустимый формат EMail!");
    return false;
  }
  var str = document.forms[0]["team"].value;
  if ( str.length < 4) {
    alert("Ошибка: не указано название команды!");
    return false;
  }
  var str = document.forms[0]["pass1"].value;
  if ( str.length < 4) {
    alert("Ошибка: не указан или указан слишком короткий пароль!");
    return false;
  }
  var str = document.forms[0]["pass1"].value;
  if ( str != document.forms[0]["pass2"].value) {
    alert("Ошибка: несовпадение паролей!");
    return false;
  }
  document.forms[0].submit();
  return true;
}
//]]></script>
<br />
<?php
if (isset($_POST['reg'])) {
  $serial = file_get_contents($online_dir.'FCL/cserial');
  file_put_contents($online_dir.'FCL/cserial', ++$serial);
  $_SESSION['Coach_name'] = ucwords($_POST['user']);
  $codestsv = '';
  $codes = file($online_dir.$cca.'/'.$cur_year.'/codes.tsv');
  foreach ($codes as $player)
    $codestsv .= $player;

  $codes[] = 'FCL'.$serial.'	'.trim($_POST['team']).'	'.$_SESSION["Coach_name"].'	'.trim($_POST['email'])."\n";
  $codestsv .= 'FCL'.$serial.'	'.trim($_POST['team']).'	'.$_SESSION["Coach_name"].'	'.trim($_POST['email'])."\n";
  file_put_contents($online_dir."FCL/$cur_year/codes.tsv", $codestsv);
  file_put_contents($online_dir.'FCL/passwd/FCL'.$serial, md5(trim($_POST['pass1'])).':player');
  send_email('FPrognoz.org <fp@fprognoz.org>', $_SESSION['Coach_name'], $_POST['email'],
               'Password for FPprognoz.org', "Team code = FCL$serial\nPassword = ".$_POST['pass1']."\n");
  build_access();
  echo 'Регистрация успешна.<br />
Вы можете входить на сайт по своему имени или коду команды FCL'.$serial.' с указанным Вами паролем.<br />
Памятка с реквизитами для входа также отправлена вам на EMail.<br />
Теперь Вы можете участвовать в товарищеских матчах.<br />
ВНИМАНИЕ: если Вы снимете со своей команды отметку об участии, через некоторое время команда будет удалена!<br />
';
}
if (isset($_SESSION['Coach_name'])) {
  $friendlyTeam = array();
  $codestsv = '';
  if (!isset($codes))
    $codes = file($online_dir.'FCL/'.$cur_year.'/codes.tsv');

  foreach ($codes as $player) {
    list($code, $tname, $pname, $email) = explode('	', $player);
    if ($pname == $_SESSION['Coach_name'])
      $friendlyTeam[$code] = $tname;
    else
      $codestsv .= $player;
  }
  if (isset($_POST['save'])) {
    $friendlyTeam = array();
    foreach ($_POST['ag'] as $team_str => $status) {
      list($code, $ac) = explode('@', $team_str);
      $team_str1 = ($ac == 'FCL') ? $code : $team_str;
      $codestsv .= $team_str1.'	'.$cmd_db[$ac][$code]['cmd'].'	'.$cmd_db[$ac][$code]['usr'].'	'.$cmd_db[$ac][$code]['eml']."\n";
      $friendlyTeam[$team_str1] = $cmd_db[$ac][$code]['cmd'];
    }
    file_put_contents($online_dir.'FCL/'.$cur_year.'/codes.tsv', $codestsv);
    echo '<font color="red">Изменение записано</font><br />
<br />
';
  }
  echo 'Отметьте свои команды, для которых Вы согласны получать приглашения на товарищеские матчи<br />
<br />
<form action="" method="post">
<table width="100%">
  <tr><td><b>ФП</b></td><td><b>код команды</b></td><td><b>название команды</b></td><td><b>отметка о согласии</b></td></tr>
';
  foreach (['BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'PRT', 'SCO', 'UKR'] as $ac)
    foreach ($cmd_db[$ac] as $code => $team)
      if ($team['usr'] == $coach_name) {
        echo '<tr><td>'.$ac.'</td><td>'.$code.'</td><td>'.$team['cmd'].'</td><td><input type="checkbox" name="ag['.$code.'@'.$ac.']"';
        if (isset($friendlyTeam[$code.'@'.$ac]))
          echo ' checked="checked"';

        echo " /></td></tr>\n";
      }

  echo '</table>
<br />
<input type="submit" name="save" value="записать" />
</form>
';
}
else {
  echo 'Для участия в товарищеских матчах необходимо войти на сайт под своим именем.<br />
Если у Вас еще нет доступа на сайт, мы можем сделать его сейчас.<br />
Все поля обязательны к заполнению.<br />
<form action="" method="post" onSubmit="return show_alert(this);">
Укажите своё имя (лучше латинскими буквами, а если Вы планируете в дальнейшем играть в "профессиональных"
ФП-ассоциациях, то имя должно быть настоящим и полным, с фамилией):<br />
<input type="text" name="user" /><br />
<br />
Укажите свой EMail для получения приглашений на матчи:<br />
<input type="text" name="email" /><br />
Внимание: указание недействительного EMail-а повлечет отмену регистрации.<br />
<br />
Укажите имя своей команды (это имя будет действительно только для товарищеских матчей):<br />
<input type="text" name="team" /><br />
Учтите, что Вы не сможете участвовать в матче с другим игроком с таким же написанием имени команды!<br />
<br />
Укажите пароль (дважды для проверки правильности набора):<br />
<input type="password" name="pass1"><br />
<input type="password" name="pass2"><br />
<br />
<input type="submit" name="reg" value="зарегистрироваться" />
</form>
';
}
?>
