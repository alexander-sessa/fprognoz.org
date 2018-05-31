<?php
error_reporting(0);
mb_internal_encoding('UTF-8');

$senders = array(
'FIFA' => 'FIFA <fifa@fprognoz.org>',
'UEFA' => 'UEFA <uefa@fprognoz.org>',
);
$subjects = array(
'FIFA' => 'ФП. Пресс-релиз ФИФА.',
'UEFA' => 'ФП. Пресс-релиз УЕФА.',
);

//if (isset($_POST['sendmail']) && !isset($_POST['sendnews'])) $sendnews = '';
//else $sendnews = ' checked="checked"';
$sendnews = '';
if (isset($_POST['sendmail']) && !isset($_POST['sendinet'])) $sendinet = '';
else $sendinet = ' checked="checked"';
if (isset($_SESSION['Country_code']) && isset($_SESSION['Coach_name']) && isset($_SESSION['Session_password']))
{
  if ($_SESSION['Coach_name'] == 'Eugeny Gladyr')
    $country_code = 'FIFA';
  else
    $country_code = 'UEFA';

  if (isset($_POST['sendmail']) && trim($_POST['subject']) && trim($_POST['msgtext']))
  {

  //////////////////////////////// SENDER

    $file = $_POST['file'];
    if (is_file($file)) rename($file, $file.'.'.time());
    file_put_contents($file, $_POST['msgtext']);
    if ($sendinet)
    {
      $from = $senders[$country_code];
      $acodes = file($data_dir . 'auth/.access');
      $sentto = array();
      foreach ($acodes as $scode) if ($scode[0] != '#')
      {
        $ateams = explode(';', trim($scode)); // $name = $ateams[3]; $email = $ateams[4];
        if (!in_array($ateams[3], $sentto)) 
        {
          $sentto[] = $ateams[3];
          send_email($senders[$country_code], $ateams[3], $ateams[4], $_POST['subject'], $_POST['msgtext']);
        }
      }
    }
  }
  else
  {

  //////////////////////////////// EDITOR

    if (!isset($_GET['file']))
      $file = $online_dir. 'QUOTAS/2017-18/publish/".time();
    else
      $file = $_GET['file'];

    echo '<form method="post" action="">
<table width="100%">
<tr><td>От:</td><td>'.htmlspecialchars($senders[$country_code]).'
<input type="hidden" name="file" value="'.$file.'" /><img src="images/spacer.gif" width="190" height="1" alt="" />
Получатели: игроки <input type="checkbox" name="sendinet"'.$sendinet.' />
SU.FOOTBALL.PROGNOZ <input type="checkbox" name="sendnews"'.$sendnews.' disabled="disabled" />
</td></tr>
<tr><td>Тема:</td><td><input name="subject" value="'.$subjects[$country_code].'" size="80" />
<img src="images/spacer.gif" width="82" height="1" alt="" />
<input type="submit" name="sendmail" value="Отправить" /></td></tr>
<tr><td>Текст:</td><td><textarea name="msgtext" rows="35" cols="80"></textarea></td></tr>
</table>
</form>
';
  }
}
else echo 'access denied';
?>
