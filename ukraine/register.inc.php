<?php
$registered = false;
$codestsv = '';
$codes = file($online_dir."$cca/$cur_year/codes.tsv");
$realteams = array();
$art = file($online_dir."$cca/$cur_year/realteams");
foreach ($art as $line) if (($line = trim($line)))
{
  $ta = explode('	', $line);
  $realteams[$ta[0]]['n'] = $ta[1];
  $realteams[$ta[0]]['l'] = $ta[2];
}
foreach ($codes as $line)
{
  $ta = explode('	', trim($line));
  $codestsv .= $line;
  unset($realteams[$ta[0]]);
  if (isset($_SESSION['Coach_name']) && $_SESSION['Coach_name'] == $ta[2])
    $registered = true;

}
if (isset($_POST['_reg']))
{
  $err = false;
  if (!isset($_SESSION['Coach_name']))
  {
    if (!isset($_POST['user']) || !trim($_POST['user']))
    {
      $err = true;
      echo '<font color="red">Ошибка: не указано имя!</font><br />';
    }
    if (!isset($_POST['email']) || !trim($_POST['email']))
    {
      $err = true;
      echo '<font color="red">Ошибка: не указан EMail!</font><br />';
    }
    if (!strpos($_POST['email'], '@') || !strpos($_POST['email'], '.'))
    {
      $err = true;
      echo '<font color="red">Ошибка: недопустимый формат EMail!</font><br />';
    }
    if (!isset($_POST['pass1']) || !trim($_POST['pass1']))
    {
      $err = true;
      echo '<font color="red">Ошибка: не указан пароль!</font><br />';
    }
    elseif (!isset($_POST['pass2']) || trim($_POST['pass1']) != trim($_POST['pass2']))
    {
      $err = true;
      echo '<font color="red">Ошибка: несовпадение паролей!</font><br />';
    }
  }
  if (!isset($_POST['team']) || !trim($_POST['team']))
  {
    $err = true;
    echo '<font color="red">Ошибка: не указано название команды!</font><br />';
  }
}
if ($registered)
  echo 'Вы уже зарегистрированы для участия в '.$title;
else if (sizeof($codes) >= 32)
  echo 'Регистрация в '.$title.' остановлена в связи с полной укомплектованностью лиг.';
else if (isset($_POST['reg']) && !$err)
{
  if (!isset($_SESSION['Coach_name']))
    $_SESSION['Coach_name'] = ucwords(trim($_POST['user']));

  if (isset($_POST['email']))
    $email = trim($_POST['email']);
  else
    foreach ($cma_db as $ccode => $teams)
      foreach ($teams as $team)
        if ($_SESSION['Coach_name'] == $team['usr']) {
          $email = $team['eml'];
          break 2;
        }

  $codestsv .= $_POST['team'].'	'.$realteams[$_POST['team']]['n'].'	'.$_SESSION['Coach_name'].'	'.$email.'	'.$realteams[$_POST['team']]['l']."	да\n";
  file_put_contents($online_dir."$cca/$cur_year/codes.tsv", $codestsv);
  if (isset($_POST['pass1'])) {
    file_put_contents($online_dir.$cca.'/passwd/'.$_POST['team'], md5(trim($_POST['pass1'])).':player');
    send_email('FPrognoz.org <fp@fprognoz.org>', $_SESSION['Coach_name'], $email,
'Password for FPprognoz.org', 'Team code = '.$_POST['team'].'
Password = '.$_POST['pass1'].'
');
  }
  build_access();
  echo 'Регистрация успешна.<br />
Код команды:'.$_POST['team'].'<br />
Вы можете входить на сайт по своему имени или коду команды с указанным Вами паролем.<br />
';
  if (isset($_POST['pass1']))
    echo 'Памятка с реквизитами для входа также отправлена вам на EMail.<br />
';
}
else
{
  echo '<form action="" method="post">
';
  if (!isset($_SESSION['Coach_name'])) {
    echo 'Для участия в '.$title.' необходимо войти на сайт под своим именем.<br />
Если у Вас еще нет доступа на сайт, мы можем сделать его сейчас.<br />
Все поля обязательны к заполнению.<br />
<br />
Укажите своё настоящее полное имя (с фамилией):<br />
<input type="text" name="user" /><br />
<br />
Укажите свой EMail для получения материалов ассоциации (календарь, программки, прогнозы игроков, итоги, обзоры):<br />
<input type="text" name="email" /><br />
Внимание: указание недействительного EMail-а повлечет отмену регистрации.<br />
<br />
Укажите пароль (дважды для проверки правильности набора):<br />
<input type="password" name="pass1" /><br />
<input type="password" name="pass2" /><br />
';
  }
  echo '<br />
Выберите команду:<br />
<select name="team">
';
  foreach ($realteams as $code => $names)
    echo '<option value="'.$code.'">'.$names['l'].'</option>';

  echo '</select><br />
<br />
<input type="submit" name="reg" value="зарегистрироваться" />
</form>
';
}
?>
