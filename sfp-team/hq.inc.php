<?php
error_reporting(0);
mb_internal_encoding('UTF-8');

if (isset($_SESSION['Coach_name']))
{
  $from = $_SESSION['Coach_name'];
  $codes = file($online_dir."SFP/$cur_year/codes.tsv");
  foreach ($codes as $player) if ($player[0] != '#')
  {
    $aplayer = explode('	', trim($player));
    if ($from == $aplayer[2])
    {
      break;
    }
  }
}
else if (isset($_POST['from'])) $from = $_POST['from']; else $from = '';
if (isset($_POST['email'])) $email = $_POST['email'];
else if(isset($aplayer[3])) $email = $aplayer[3];
else $email = '';
if (isset($_POST['subject'])) $subject = $_POST['subject']; else $subject = '';
if (isset($_POST['msgtext'])) $msgtext = $_POST['msgtext']; else $msgtext = '';
if (trim($email) && trim($from) && trim($subject) && trim($msgtext))
{
  echo "Сообщение отправлено:<br />";
  send_email("\"$from\" <$email>", 'SFP Team Coach', 'sfp@fprognoz.org', "ФП. Сборная сайта: ".$_POST['subject'], $_POST['msgtext']);
}
echo $team_str;
?>
<p>Если хотите задать вопрос или оставить сообщение тренеру сборной сайта, заполните все поля:</p>
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
