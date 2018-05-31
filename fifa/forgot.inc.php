    <font color='#<?=$main_hdcolor?>' class=text15b>&nbsp;&nbsp;&nbsp;Восстановление забытого пароля. Шаг 1</font>
    <hr size='1' color='#<?=$main_hrcolor?>' width='916'>
<?php
$ok = '';
if (isset($_POST['submitnewpass']))
{
  if (strlen($gp = trim($_POST['new_passwd'])) > 3)
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
    <p>Пароль утановлен. Войдите на сервер с новым паролем.</p>
    <p>Для безопасности пароль выслан на email, который вы указывали для получения материалов футбол-прогноза.</p>';
    $ok = 1;
    $role = 'badlogin'; session_unset(); session_destroy();
  }
  else
     echo '
     <p><font color=red>Пароль слишком короткий!</font><br /></p>';
}
if (!$ok)
{
?>
<p>Чтобы установить новый пароль для входа на сайт, заполните поле ввода на этой странице и нажмите кнопку "сменить".<br />
Пароль будет изменен для доступа ко всем вашим командам.</p>
<form action='?m=pass' method=POST>
<p>новый пароль : <input type=password name=new_passwd value=""size=20>
<input type=submit name=submitnewpass value=" сменить "></p>
</form>
<?php } ?>

