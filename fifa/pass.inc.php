<script type="text/javascript">//<![CDATA[
function show_alert() {
  var str = document.forms[0]["new_passwd"].value;
  if ( str.length < 4) {
    alert("Новый пароль слишком короткий!");
    return false;
  }
  else {
    document.forms[0].submit();
    return true;
  }
}
//]]></script>
    <p class="title text15b">&nbsp;&nbsp;&nbsp;Смена пароля</p>
    <hr size="1" width="98%" />
<?php
$ok = false;
if (isset($_POST['submitnewpass'])) {
  if ($_SESSION['Session_password'] != trim($_POST['old_passwd']))
    echo '<p style="color: red">Неверно указан старый пароль!<br /></p>';
  else { // смена пароля
    $team_codes = '';
    foreach ($usr_db[$_SESSION['Coach_name']] as $team_str)
    if ($team_str != 'I@FIFA' && !strpos($team_str, '@FCL') && !strpos($team_str, '@SFP')) {
      if ($team_codes)
        $team_codes .= ', ';

      $email = $cmd_db[$team_str]['eml'];
      list ($team_code, $country_code) = explode('@', $team_str);
      $team_codes .= $team_code;
      file_put_contents($online_dir.$country_code.'/passwd/'.$team_code, md5($_POST['new_passwd']).':'.$cmd_db[$team_str]['rol']);
    }
    echo '
<p>Пароль изменен. Войдите на сервер с новым паролем.</p>
<p>Для безопасности пароль выслан на email, который вы указывали для получения материалов футбол-прогноза: ';
    send_email('FPrognoz.org <fp@fprognoz.org>', $_POST['name_str'], $email,
               'Password for FPprognoz.org',
'Team code(s) = '.$team_codes.'
Password = '.$_POST['new_passwd'].'
');
    build_access();
    echo '</p>';
    $ok = true;
    $role = 'badlogin';
    session_unset();
    session_destroy();
  }
}
if (!$ok) {
  echo '<p>Чтобы сменить пароль для входа на сайт,';
  if (!isset($_SESSION['Session_password']))
    echo ' сначала авторизуйтесь, указав полное имя или код любой своей команды и пароль,';

  echo ' заполните поля ввода на этой странице и нажмите кнопку "сменить".<br />
Пароль будет изменен для доступа ко всем вашим командам.</p>
<form action="?m=pass" method="post" onSubmit="return show_alert(this);">
<p>старый пароль: <input type="password" name="old_passwd" value="" size="20" /></p>
<p>новый пароль : <input type="password" name="new_passwd" value="" size="20" />
<input type="submit" name="submitnewpass" value=" сменить " /></p>
</form>
';
}
?>
