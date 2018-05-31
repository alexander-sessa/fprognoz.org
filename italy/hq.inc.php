<?php
error_reporting(0);
mb_internal_encoding('UTF-8');

if (isset($_SESSION['Coach_name']))
{
  $from = $_SESSION['Coach_name'];
  foreach ($usr_db[trim($from)] as $team_str)
  {
    $ta = explode('@', $team_str);
    if ($ta[1] == $cca)
      break;

  }
}
else if (isset($_POST['from'])) $from = $_POST['from']; else $from = '';
if (isset($_POST['email']))
  $email = $_POST['email'];
else if(isset($cmd_db[$team_str]['eml'])) $email = $cmd_db[$team_str]['eml'];
else $email = '';
if (isset($_POST['subject'])) $subject = $_POST['subject']; else $subject = '';
if (isset($_POST['msgtext'])) $msgtext = $_POST['msgtext']; else $msgtext = '';
if (trim($email) && trim($from) && trim($subject) && trim($msgtext))
{
  echo '<p class="text15">Сообщение отправлено:</p>';
  foreach ($ahq_db[$cca] as $pname => $prole) if ($prole == 'president')
  {
    foreach ($usr_db[trim($pname)] as $team_str)
    {
      $ta = explode('@', $team_str);
      if ($ta[1] == $cca)
        break;
    }
    send_email("\"$from\" <$email>", $pname, $cmd_db[$team_str]['eml'], $title.". Президенту: ".$_POST['subject'], $_POST['msgtext']);
  }
}
if (!$vice) $vice = 'должность свободна';
if (!$pressa) $pressa = 'должность свободна';
?>
<p class="text15">
Президент ассоциации: <b><?=$president;?></b><br />
<br />
Вице-президент: <b><?=$vice;?></b><br />
<br />
Пресс-атташе: <b><?=$pressa;?></b><br />
<br />
Если хотите оставить сообщение руководителям ассоциации, заполните все поля:
</p>
<form action="" method="post">
<table width="100%">
<tr><td>Имя:</td><td><input name="from" value="<?=$from;?>" size="80" /></td></tr>
<tr><td>E-Mail:</td><td><input name="email" value="<?=$email;?>" size="80" /></td></tr>
<tr><td>Тема:</td><td><input name="subject" value="<?=$subject;?>" size="80" />
<img src="images/spacer.gif" width="82" height="1" alt="" />
<input type="submit" name="sendmail" value="Отправить" /></td></tr>
<tr><td>Текст:</td><td><textarea name="msgtext" rows="21" cols="80"><?=$msgtext;?></textarea></td></tr>
</table>
</form>
