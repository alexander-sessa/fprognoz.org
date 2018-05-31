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
if (isset($_POST['submitnewpass']))
{
  if ($_SESSION['Session_password'] != trim($_POST['old_passwd']))
  {
     echo '<p style="color: red">Неверно указан старый пароль!<br /></p>';
  }
  else
  { // смена пароля
    foreach ($usr_db[$_SESSION['Coach_name']] as $team_str)
    {
      if ($team_codes) $team_codes .= ', ';
      $ta = explode('@', $team_str);
      $team_codes .= $ta[0];
      file_put_contents($online_dir.$ta[1].'/passwd/'.$ta[0], md5($gp).':'.$cmd_db[$team_str]['rol']);
    }
    send_email('FPrognoz.org <fp@fprognoz.org>', $_POST['name_str'], $cmd_db[$team_str]['eml'],
               'Password for FPprognoz.org', "Team code(s) = $team_codes\nPassword = $gp\n");
    build_access();
    echo '
    <p>Пароль изменен. Войдите на сервер с новым паролем.</p>
    <p>Для безопасности пароль выслан на email, который вы указывали для получения материалов футбол-прогноза.</p>';
    $ok = true;
    $role = 'badlogin'; session_unset(); session_destroy();
  }
}
if (!$ok)
{
?>
<p>Чтобы сменить пароль для входа на сайт,
<?php if (!isset($_SESSION['Session_password'])) { ?> войдите на сайт, указав полное имя или код любой своей команды и пароль,<?php } ?>
 заполните поля ввода на этой странице и нажмите кнопку "сменить".<br />
Пароль будет изменен для доступа ко всем вашим командам.</p>
<form action="?m=pass" method="post" onSubmit="return show_alert(this);">
<p>старый пароль: <input type="password" name="old_passwd" value="" size="20" /></p>
<p>новый пароль : <input type="password" name="new_passwd" value="" size="20" />
<input type="submit" name="submitnewpass" value=" сменить " /></p>
</form>
<?php } ?>

