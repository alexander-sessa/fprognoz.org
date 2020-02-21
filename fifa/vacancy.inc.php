    <p class="title text15b">&nbsp;&nbsp;&nbsp;Форма отправки заявок на свободные команды</p>
    <hr size="1" width="98%" />
<?php
if (isset($_SESSION['Coach_name'])) {
  $name = $_SESSION['Coach_name'];
  $human = true;
}
else
{
  $name = '';
  $human = false;
}
if (isset($_POST['name_str'])) {
  $name = $_POST['name_str'];
  $pemail = $_POST['email_str'];
  $teamsin = $_POST['teams_in'];
  $teamsout = $_POST['teams_out'];
  if (!$human) {
    $params['secret'] = $recaptcha_secret2;
    $params['response'] = $_POST['g-recaptcha-response'];
//    $uri = http_build_query($params);
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($params)
      )
    );
    $context = stream_context_create($options);
    $fp = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $human = strpos($fp, '"success": true') ? true : false;
  }
}
if (!isset($name))
  $name ='';

if (!isset($pemail))
  $pemail = '';

if (!isset($teamsin))
  $teamsin = '';

if (!isset($teamsout))
  $teamsout = '';

if (isset($_POST['name_str']) && $human && $name && $pemail) { // отправка вакансии
  if ($pemail) {
    send_email('FPrognoz.org <fp@fprognoz.org>', $name, $pemail, "Vacancy", "FP_Prognoz\n$name\nVACANCY\n- $teamsout\n+ $teamsin\n");
    echo "<h5>Заявка принята. Копия заявки отправлена на адрес $pemail<br /></h5>";
    $email = str_replace(',', ' ', $pemail);
    $amail = explode(' ', $email);
    $replyto = '';
    foreach ($amail as $email)
      if ($email = trim($email))
        if (strpos($email, '@'))
          $replyto = "$name <$email>";

  }
  $mlist = array('fp@fprognoz.org');
  foreach ($mlist as $email) {
    @mail($email, 'Vacancy', "FP_Prognoz\n$name\nVACANCY\n- $teamsout\n+ $teamsin\n$pemail\n",
'From: '.$name.' <fp@fprognoz.org>
Reply-To: '.$name.' <'.$pemail.'>
MIME-Version: 1.0
Content-Type: text/plain;
        charset="utf-8"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 2.00.150822-'.$ip);
  }
  sleep(1);
}
else if (isset($_POST['submitvac']))
   echo "<h2>Заявка не принята - проверьте заполнение полей</h2>";

/******/
/* UI */
/******/
if (isset($_SESSION['Coach_name']) && !$pemail)
  foreach ($cmd_db as $cca => $teams)
    foreach ($teams as $team)
      if (($_SESSION['Coach_name'] == $team['usr'])) {
        $pemail = $team['eml'];
        break 2;
      }

else
  echo
'<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

echo '
<script>function onSubmit(token){document.getElementById("proform").submit();}</script>
<p>Команды, указанные в этом разделе, отдаются желающим без конкурса. Для получения команды заполните и отправьте заявку.</p>
<center>
<form id="proform" name="tform" action="/?m=vacancy" enctype="multipart/form-data" method="post">
<table align="center" width="98%">
<tr><td align="right">укажите ваше игровое или полное имя: </td><td><input type="text" name="name_str" value="'.$name.'" size="40" /></td></tr>
<tr><td align="right">e-mail, на который отправлять информацию сервера: </td><td><input type="text" name="email_str" value="'.$pemail.'" size="40" /></td></tr>
<tr><td align="right">коды команд, от которых откажетесь: </td><td><input type="text" name="teams_out" value="'.$teamsout.'" size="40" /></td></tr>
<tr><td align="right">коды команд, которые хотите получить: </td><td><input type="text" name="teams_in" value="'.$teamsin.'" size="40" /></td></tr>
</table>
<p><button class="g-recaptcha" data-sitekey="'.$recaptcha_sitekey.'" data-callback="onSubmit"> отправить заявку </button></p>
</form></center>
<br>
Свободна команда ФП Германии, 3. Liga: Osnabrück (14 очков, 16 место).<br>
Команда достанется тому, кто первым подаст заявку.<br>
<br>
Вы также можете зарегистрировать команду для участия в открытых краткосрочных турнирах ФП ШВЕЙЦАРИИ (<a href="?a=switzerland&amp;m=register">ВЫБОР КОМАНДЫ</a>)<br>
и в любительской ФП-ассоциации Финляндии (<a href="?a=finland&amp;m=register">РЕГИСТРАЦИЯ В ФП ФИНЛЯНДИИ</a>).<br>
';
?>
