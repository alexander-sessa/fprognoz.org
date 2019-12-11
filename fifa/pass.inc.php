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
  if (!$passed)
    echo '<p style="color: red">Неверно указан старый пароль!<br /></p>';
  else { // смена пароля
    $team_list = '';
    foreach ($team_codes as $ac => $code) {
      $team_list .= $code.', ';
      file_put_contents($online_dir.$ac.'/passwd/'.$code, md5($_POST['new_passwd']).':player');
    }
    send_email('FPrognoz.org <fp@fprognoz.org>', $_SESSION['Coach_name'], $sendpwd, 'ФП. Пароль для сайта ' . $this_site,
'Вы сменили пароль для доступа на сайт ' . $this_site . '

'.$_POST['new_passwd'].'

Используйте его вместе с именем ' . $coach_name . ',
или с указанным в поле "имя" кодом одной из ваших команд: '.$team_list.'
или же с вашим e-mail адресом ' . $sendpwd . '.
');
    build_access();
    echo '
<p>Пароль изменен. Войдите на сервер с новым паролем.</p>
<p>Для безопасности пароль выслан на email, который вы указывали для получения материалов футбол-прогноза.</p>';
    $ok = true;
    $role = 'badlogin';
    session_unset();
    session_destroy();
  }
}
if (!$ok) {
  echo '<p>Чтобы сменить пароль для входа на сайт,';
  if (!isset($_SESSION['Coach_name']))
    echo ' сначала авторизуйтесь, указав полное имя, или e-mail, или код любой своей команды и пароль,';

  echo ' заполните поля ввода на этой странице и нажмите кнопку "сменить".<br />
Пароль будет изменен для доступа ко всем вашим командам.</p>
<form action="?m=pass'.($token ? '&token='.$token : '').'" method="post" onSubmit="return show_alert(this);">
'.($token ? '' : '<p>старый пароль: <input type="password" name="pass_str" value="" size="20"></p>').'
<p>новый пароль : <input type="password" name="new_passwd" value="" size="20">
<input type="submit" name="submitnewpass" value=" сменить "></p>
</form>
';
}
?>
