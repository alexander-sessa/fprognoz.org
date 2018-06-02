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

$sendnews = '';
$sendinet = (isset($_POST['sendmail']) && !isset($_POST['sendinet'])) ? '' : ' checked="checked"';
if (isset($_SESSION['Coach_name']) && acl($_SESSION['Coach_name']) != 'player') {
  $country_code = ($_SESSION['Coach_name'] == 'Eugeny Gladyr') ? 'FIFA' : 'UEFA';
  if (isset($_POST['sendmail']) && trim($_POST['subject']) && trim($_POST['msgtext'])) {

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
  else {

  //////////////////////////////// EDITOR

    $file = isset($_GET['file']) ? $_GET['file'] : $online_dir. "QUOTAS/$cur_year/publish/".time();
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
