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

$from = isset($_SESSION['Coach_name']) ? $senders[$cca] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$msgtext = isset($_POST['msgtext']) ? $_POST['msgtext'] : '';
$dir = scandir($online_dir.$cca);
$acodes = file($online_dir.$cca.'/'.$s.'/codes.tsv');
if (trim($from) && trim($subject) && trim($msgtext)) {
  $emails = '';
  foreach ($_POST['pl'] as $code => $send) {
    $addresses = explode(',', $cmd_db[$cca][$code]['eml']);
    foreach ($addresses as $address)
      if ($address = trim($address))
        $emails .= ($emails ? ', ' : '') . $cmd_db[$cca][$code]['usr'] . ' <' . $address . '>';

  }
  if ($emails)
    echo send_email($from, '', $emails, $subjects[$cca]." ".$_POST['subject'], $_POST['msgtext']);

}
?>
<p class="text15">Для отправки EMail отдельным игрокам ФП-ассоциации, отметьте их в левой колонке:</p>
<form action="" method="post">
<table width="100%">
<tr><td rowspan="2">
<?php
foreach ($acodes as $line) if (trim($line)) {
  list($code, $tname, $uname, $email, $lname, $confirm) = explode('	', $line);
  echo '<input type="checkbox" name="pl['.$code.']" /> '.$uname.' ('.$tname.')<br />';
}
?>
</td>
<td>Тема:<input name="subject" value="<?=$subject;?>" size="60" />
<img src="images/spacer.gif" width="42" height="1" alt="" />
<input type="submit" name="sendmail" value="Отправить" /></td></tr>
<tr><td><textarea name="msgtext" rows="36" cols="80"><?=$msgtext;?></textarea></td></tr>
</table>
</form>
