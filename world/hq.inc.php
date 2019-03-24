<?php
$team = [];
$email = 'fp@fprognoz.org';
if (isset($_SESSION['Coach_name'])) {
  $from = $_SESSION['Coach_name'];
  if (isset($cmd_db[$cca]))
    foreach ($cmd_db[$cca] as $team)
      if ($team['usr'] == $from) {
        $email = isset($team['eml']) ? $team['eml'] : '';
        break;
      }

}
else
  $from = isset($_POST['from']) ? $_POST['from'] : '';

if (isset($_POST['email']))
  $email = $_POST['email'];

$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$msgtext = isset($_POST['msgtext']) ? $_POST['msgtext'] : '';
if (trim($email) && trim($from) && trim($subject) && trim($msgtext)) {
  $to = '';
//  foreach ([$president, $vice] as $name)
//    $to .= $to ? ', ' : '' . $cmd_db[$cca][$name]['eml'];
$to = 'alexander.sessa@gmail.com';

  echo send_email('"'.$from.'" <'.$email.'>', $pname, $to, $title.'. Президенту: '.$_POST['subject'], $_POST['msgtext']);
}
if (!$vice)
  $vice = 'должность свободна';

if (!$pressa)
  $pressa = 'должность свободна';
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
