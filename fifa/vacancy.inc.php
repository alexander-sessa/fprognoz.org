    <p class="title text15b">&nbsp;&nbsp;&nbsp;Форма отправки заявок на свободные команды</p>
    <hr size="1" width="98%" />
<?php

/* main section */

function HTTP_CURL($url, $ref, $uri, $cookie) {
  $ch = curl_init(); 
  $options = array(
CURLOPT_URL => $url,
CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SFPautoTrainer/1.0; +https://fprognoz.org)',
CURLOPT_COOKIEJAR => '/home/fp/fprognoz.org/sfp-team/cookie/'.$cookie,
CURLOPT_COOKIEFILE => '/home/fp/fprognoz.org/sfp-team/cookie/'.$cookie,
CURLOPT_HTTPHEADER => array('ACCEPT_LANGUAGE: ru', 'CONNECTION: Keep-Alive'),
CURLOPT_REFERER => $ref,
CURLOPT_HEADER => 1,
CURLOPT_FOLLOWLOCATION => 1,
CURLOPT_TIMEOUT => 60,
CURLOPT_RETURNTRANSFER => true
);
  if ($uri != '') {
    $options[CURLOPT_POST] = 1;
    $options[CURLOPT_POSTFIELDS] = $uri;
  }
  curl_setopt_array($ch, $options);
  $ret = curl_exec($ch); 
  curl_close($ch); 
  return $ret;
}

if (isset($_SESSION['Coach_name'])) {
  $name = $_SESSION['Coach_name'];
  $human = true;
}
else
  $human = false;

if (isset($_POST['name_str'])) {
  $name = $_POST['name_str'];
  $pemail = $_POST['email_str'];
  $teamsin = $_POST['teams_in'];
  $teamsout = $_POST['teams_out'];
  if (!$human) {
    $params['secret'] = '6Lc8dioUAAAAAC3_a9PP6k9r5aA2_o2S5kq1j3oJ';
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
    send_email('FPrognoz.org <fp@fprognoz.org>', $name, $pemail, "Vacancy", "FP_Prognoz\nVACANCY\n$name\n- $teamsout\n+ $teamsin\n");
    echo "<h1>Заявка принята. Копия заявки отправлена на адрес $pemail<br /></h1>";
    $email = str_replace(',', ' ', $pemail);
    $amail = explode(' ', $email);
    $replyto = '';
//    foreach ($amail as $email) if (!$replyto && ($email = trim($email)))
    foreach ($amail as $email) if ($email = trim($email)) {
      if (strpos($email, '@'))
        $replyto = "$name <$email>";
      else {
        if ($cz = strpos($email, ':')) $zone = substr($email, 0, $cz);
        else $zone = 2;
        $cn = strpos($email, '/');
        $net = substr($email, $cz + 1, $cn - $cz - 1);
        if ($cf = strpos($email, '.')) { $node = substr($email, $cn + 1, $cf - $cn - 1); $point = substr($email, $cf + 1); }
        else { $node = substr($email, $cn + 1); $point = 0; }
          $replyto = str_replace(' ', '.', $name)."@";
        if ($point) $email .= "p$point.";
          $replyto .= "f$node.n$net.z$zone.fidonet.org";
      }
    }
  }

  $mlist = array('fp@fprognoz.org');
  foreach ($mlist as $email) {
    @mail($email, 'Vacancy', "FP_Prognoz\nVACANCY\n$name\n- $teamsout\n+ $teamsin\n$pemail\n",
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
      if (($pemail = $team['eml']))
        break 2;

else
  echo
'<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

echo '
<script>function onSubmit(token){document.getElementById("proform").submit();}</script>
<p>Команды, указанные в этом разделе, отдаются желающим без конкурса. Для получения команды заполните и отправьте заявку.</p>
<center>
<form id="proform" name="tform" action="/?m=vacancy" enctype="multipart/form-data" method="post">
<table align="center" width="98%">
<tr><td align="right">укажите ваше имя и фамилию латинскими буквами: </td><td><input type="text" name="name_str" value="'.$name.'" size="40" /></td></tr>
<tr><td align="right">e-mail, на который отправлять информацию сервера: </td><td><input type="text" name="email_str" value="'.$pemail.'" size="40" /></td></tr>
<tr><td align="right">коды команд, от которых откажетесь: </td><td><input type="text" name="teams_out" value="'.$teamsout.'" size="40" /></td></tr>
<tr><td align="right">коды команд, которые хотите получить: </td><td><input type="text" name="teams_in" value="'.$teamsin.'" size="40" /></td></tr>
</table>
<p><button class="g-recaptcha" data-sitekey="6Lc8dioUAAAAAArLlgGJDVEy6nwwQDSKWrmAOvkq" data-callback="onSubmit"> отправить заявку </button></p>
</form></center>
Свободна команда Динамо (Брянск) (ФП России, Национальная лига).<br />
Присылайте заявки: команда достанется первому приславшему при условии наличия квоты.<br />
А ещё... ФК Тамбов во 2-м дивизионе России нужен? Если да, пишите.<br />
<br />
Вы также можете зарегистрировать команду для участия в клубных товарищеских матчах (<a href="?a=friendly&amp;m=register">Регистрация</a>)<br />
и в любительской ФП-ассоциации Финляндии (<a href="?a=finland&amp;m=register">РЕГИСТРАЦИЯ В ФП ФИНЛЯНДИИ</a>).<br />
';
?>
