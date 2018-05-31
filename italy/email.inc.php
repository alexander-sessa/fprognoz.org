<?php
error_reporting(0);
mb_internal_encoding('UTF-8');

$senders = array(
'QUOTAS' => 'UEFA <uefa@fprognoz.org>',
'BLR' => '"PFL of Belarus" <blr@fprognoz.org>',
'ENG' => '"FPL of England" <eng@fprognoz.org>',
'ESP' => '"FPL of Spain" <esp@fprognoz.org>',
'FRA' => '"PFL of France" <fra@fprognoz.org>',
'GER' => '"PFL of Germany" <ger@fprognoz.org>',
'ITA' => '"PFL of Italy" <itl@fprognoz.org>',
'NLD' => '"PFL of Netherlands" <nld@fprognoz.org>',
'RUS' => '"PFL of Russia" <rus@fprognoz.org>',
'PRT' => '"PFL of Portugal" <prt@fprognoz.org>',
'SCO' => '"PFL of Scotland" <sco@fprognoz.org>',
'UKR' => '"PFL of Ukraine" <ukr@fprognoz.org>',
'UEFA' => 'UEFA <uefa@fprognoz.org>',
'FIN' => '"AFL of Finland" <fin@fprognoz.org>',
'SBN' => '"AFL of SBNet" <sbn@fprognoz.org>',
'SFP' => '"SFP Team Coach" <sfp@fprognoz.org>',
'IST' => '"SFP - 20 ЛЕТ!" <sfp@fprognoz.org>',
);
$subjects = array(
'QUOTAS' => 'ФП. Пресс-релиз УЕФА.',
'BLR' => 'ФП. Беларусь.',
'ENG' => 'ФП. Англия.',
'ESP' => 'ФП. Испания.',
'FRA' => 'ФП. Франция.',
'GER' => 'ФП. Германия.',
'ITA' => 'ФП. Италия.',
'NLD' => 'ФП. Голландия.',
'RUS' => 'ФП. Россия.',
'PRT' => 'ФП. Португалия.',
'SCO' => 'ФП. Шотландия.',
'UKR' => 'ФП. Украина.',
'UEFA' => 'ФП. Лиги УЕФА.',
'SBN' => 'ФП. SBNet.',
'FIN' => 'ФП. Финляндия.',
'SFP' => 'ФП. Сборная сайта.',
'IST' => 'ФП. Турнир "SFP - 20 ЛЕТ!"',
);

if (isset($_SESSION['Country_code']) && isset($_SESSION['Coach_name']) && isset($_SESSION['Session_password']))
  $from = $senders[$_SESSION['Country_code']];
else
  $from = '';

if (isset($_POST['subject']))
  $subject = $_POST['subject'];
else
  $subject = '';

if (isset($_POST['msgtext']))
  $msgtext = $_POST['msgtext'];
else
  $msgtext = '';

$dir = scandir($online_dir.$_SESSION['Country_code']);

if ($s)
  $season = $s;
else
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
       $season = $subdir;

$acodes = file($online_dir.$_SESSION['Country_code']."/$season/codes.tsv");

if (trim($from) && trim($subject) && trim($msgtext))
{
  echo '<p class="text15">Сообщение отправлено:</p>';
  $emails = '';
  foreach ($_POST['pl'] as $uname => $send)
    $emails .= $cmd_db[$uname]['usr'] . ' <' . $cmd_db[$uname]['eml'] . '>, ';

  if ($emails = rtrim($emails, ', '))
    send_email($from, '', $emails, $subjects[$_SESSION['Country_code']]." ".$_POST['subject'], $_POST['msgtext']);
//    send_email($from, $cmd_db[$uname]['usr'], $cmd_db[$uname]['eml'], $subjects[$_SESSION['Country_code']]." ".$_POST['subject'], $_POST['msgtext']);
}
?>
<p class="text15">Для отправки EMail отдельным игрокам ФП-ассоциации, отметьте их в левой колонке:</p>
<form action="" method="post">
<table width="100%">
<tr><td rowspan="2">
<?php
foreach ($acodes as $line) if (trim($line))
{
  $ta = explode('	', $line);
  echo '<input type="checkbox" name="pl['.$ta[0].'@'.$_SESSION['Country_code'].']" /> '.$ta[2].' ('.$ta[1].')<br />';
}
?>
</td>
<td>Тема:<input name="subject" value="<?=$subject;?>" size="60" />
<img src="images/spacer.gif" width="42" height="1" alt="" />
<input type="submit" name="sendmail" value="Отправить" /></td></tr>
<tr><td><textarea name="msgtext" rows="36" cols="80"><?=$msgtext;?></textarea></td></tr>
</table>
</form>
