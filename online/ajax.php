<?php
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(md5('iv'.$salt, true), 0, 8);
$key = substr(md5('pass1'.$salt, true) . md5('pass2'.$salt, true), 0, 24);
$data = json_decode(trim(mcrypt_decrypt( MCRYPT_BLOWFISH, $key, base64_decode($_POST['data']), MCRYPT_MODE_CBC, $iv )), true);
$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

$ccn = array(
'SFP' => 'SFP-team',
'ENG' => 'England',
'BLR' => 'Belarus',
'GER' => 'Germany',
'NLD' => 'Netherlands',
'ESP' => 'Spain',
'ITA' => 'Italy',
'PRT' => 'Portugal',
'RUS' => 'Russia',
'UKR' => 'Ukraine',
'FRA' => 'France',
'SCO' => 'Scotland',
'UEFA'=> 'UEFA',
'FIN' => 'Finland',
'SBN' => 'SBN',
'FIFA'=> 'FIFA',
'FCL' => 'Friendly',
'UNL' => 'World',
'WL'  => 'World',
'IST' => 'SFP-20',
);

function build_search_map($file) {
  $access = file($file);
  $map = []; // коды и имена привязанные к мейлам
  $tmp = []; // коды, привязанные к имени, если не указан  мейл
  $nm = []; // соответствие мейла имени
  foreach ($access as $access_str) {
    list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
    $code = mb_strtoupper($code);
    $name = mb_strtoupper($name);
    if ($mail = strtoupper($mail)) {
      $map[$mail][] = $code;
      if ($name) {
        $map[$mail][] = $name;
        $nm[$mail][] = $name;
      }
    }
    else
      $tmp[$name][] = $code;
  }
  foreach ($nm as $mail => $names) {
    $nm[$mail] = array_unique($names);
    foreach ($nm[$mail] as $name)
      if (isset($tmp[$name]))
        $map[$mail] = array_merge($map[$mail], $tmp[$name]); // дополнили записями без мейла

  }
  $out = '';
  foreach ($map as $mail => $names) {
    $map[$mail] = array_unique($names);
    foreach ($map[$mail] as $name)
      $out .= ";$name";

    $out .= ";!;$mail;\n";
  }
  return $out;
}

// специфика хостинга на AWS - на других серверах не использовать
function send_email($from, $name, $email, $subj, $body) {
  $params = ['token' => 'FPrognoz.Org', 'from' => $from, 'name' => $name, 'email' => $email, 'subj' => $subj, 'body' => $body];
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
      'content' => http_build_query($params),
    ),
  ));
  return file_get_contents('http://forum.fprognoz.org/mail-proxy.php', false, $context);
}

function build_access() {
  global $ccn;
  global $data_dir;
  global $online_dir;
  $access = '';
  foreach ($ccn as $ccc => $cname) if ($ccc != 'SBN' && $ccc != 'FIFA') {
    $dir = scandir($online_dir.$ccc, 1);
    foreach ($dir as $s)
      if ($s[0] == '2')
        break;

    $codes = file($online_dir . $ccc . '/' . $s . '/codes.tsv');
    foreach ($codes as $line) if (trim($line)) {
      list($code, $cmd, $name, $email) = explode("\t", $line);
      $email = trim($email);
      $code = trim($code, '- ');
      if ($code[0] != '#' && $name && (!strpos($code, '@') || $cc = 'SFP')) {
        if (is_file($online_dir . $ccc . '/passwd/' . $code)) {
          list($hash, $role) = explode(':', file_get_contents($online_dir . $ccc . '/passwd/' . $code));
          $role = trim($role);
        }
        else {
          $hash = '';
          $role = '';
        }
        $access .= "$code;$ccc;$cmd;$name;$email;$hash;$role;\n";
        if (!is_dir($data_dir . 'personal/' . $name))
          mkdir($data_dir . 'personal/' . $name, 0755);

      }
    }
  }
  file_put_contents($data_dir . 'auth/.access', $access);
}

if (isset($data['cmd'])) {
  // проверка уникальности имени
  if ($data['cmd'] == 'unique_check') {
    $afile = $data_dir . 'auth/.access';
    $mfile = $data_dir . 'auth/.map';
    if (!is_file($mfile) || (filectime($afile) > filectime($mfile))) {
      $map = build_search_map($afile); // обновление кэша имён
      file_put_contents($mfile, $map);
    }
    else
      $map = file_get_contents($mfile);  // или чтение старого

    $email = strtoupper($_POST['email']);
    $keyword = mb_strtoupper($_POST['nick']);
    if (($ptr = strpos($map, ";$keyword;")) === false)
      echo 0; // не совпало (уникально)
    else {
      $map = substr($map, 0, strpos($map, "\n", $ptr));
      echo (strpos($map, ";!;$email;", $ptr - 3) ? 2 : 1); // своё/чужое
    }
    exit;
  }

  if ($data['cmd'] == 'password_check') {
    $sendpwd = [];
    $access = file($data_dir . 'auth/.access');
    $hash = md5($_POST['pswd']);
    $name_str = $_POST['nick'];
    $name_up = mb_strtoupper($name_str);
    foreach ($access as $access_str) {
      list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
      if ($hash == $pwd && ($name_up == mb_strtoupper($code) || $name_up == mb_strtoupper($name) || $name_up == strtoupper($mail))) {
        echo 1; // комбинация совпала
        exit;
      }
      if (!$pwd && $mail && $_POST['pswd'] == $code && $name_str == $name)
        $sendpwd = [$as_code, $mail]; // выполнилось условие отправки первого пароля

    }
    if (count($sendpwd)) {
      // генерируем и высылаем пароль
      $gp = '';
      $mix = '23456789qwertyuiopasdfghjkzxcvbnmQWERTYUPASDFGHJKLZXCVBNM';
      for ($i=0; $i<8; $i++)
        $gp .= $mix[rand(0,56)];

      file_put_contents($online_dir.$sendpwd[0].'/passwd/'.$_POST['pswd'], md5($gp).':player');
      send_email('FPrognoz.org <fp@fprognoz.org>', $name_str, $sendpwd[1], 'ФП. Пароль для сайта ' . $this_site,
'Вы получили случайно-сгенерированный пароль для доступа на сайт ' . $this_site . '

'.$gp.'

Используйте его вместе с именем ' . $coach_name . ',
или с указанным в поле "имя" кодом вашей команды: ' . $_POST['pswd'] . '
или же с вашим e-mail адресом ' . $sendpwd[1] . '.

Пароль можно сменить на странице '.$this_site.'/?m=pass
');
      build_access();
      echo 2;
    }
    else
      echo 0; // комбинация не совпала

    exit;
  }

  // отправка токена
  if ($data['cmd'] == 'send_token') {
    $access = file($data_dir . 'auth/.access');
    $email = $_POST['nick'];
    foreach ($access as $access_str) {
      list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
      if (strcasecmp($mail, $email) == 0)
        break;

    }
    $data_cfg = array('cmd' => 'auth_token', 'name' => $name, 'ts' => (time() + 600));
    $cfg = base64_encode(mcrypt_encrypt( MCRYPT_BLOWFISH, $key, json_encode($data_cfg), MCRYPT_MODE_CBC, $iv ));
    $link = $this_site . '/?token='.urlencode(rtrim($cfg, '='));
    $ret = send_email('FPrognoz.org <fp@fprognoz.org>', '', $email, 'ФП. Временная ссылка для входа на сайт ' . $this_site,
'Вы получили временную ссылку для для доступа на сайт ' . $this_site . '
Она действительна только в течение 10 минут с момента отправки.

'.$link.'

');
//С помощью этой ссылки Вы можете сменить пароль без необходимости указания действующего пароля.
//Внимание: смена пароля возможна только со страницы, на которую ведёт временная ссылка.
    echo ($ret[0] == r ? '0' : '1');
  }

  // запись файла
  if ($data['cmd'] == 'save_file') {
    include ('../' . $data['a'] . '/settings.inc.php');
    if ($data['author'] == $president || $data['author'] == $vice || in_array($data['author'], $admin)) {
      if ($data['m'] == 'main')
        $file = $data_dir . 'online/' . $cca . '/news';
      else
        $file = $data_dir . 'online/' . $cca . '/' . $data['s'] . '/' . $data['m'];
      copy($file, $file . '.' . time());
      $text = urldecode($_POST['text']);
      if (strpos($text, '</p>')) {
        $tidy = new tidy;
        $tidy->parseString($text, ['indent' => true, 'show-body-only' => true, 'wrap' => 200], 'utf8');
        $tidy->cleanRepair();
        $text = $tidy->value;
      }
      file_put_contents($file, $text);
    }
    echo '';
  }
}
?>