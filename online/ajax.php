<?php
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(md5('iv'.$salt, true), 0, 8);
$key = substr(md5('pass1'.$salt, true) . md5('pass2'.$salt, true), 0, 24);
$data = json_decode(trim(mcrypt_decrypt( MCRYPT_BLOWFISH, $key, base64_decode($_POST['data']), MCRYPT_MODE_CBC, $iv )), true);
$this_site = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

$ccn = array(
'SFP' => 'sfp-team',
'ENG' => 'england',
'BLR' => 'belarus',
'GER' => 'germany',
'NLD' => 'netherlands',
'ESP' => 'spain',
'ITA' => 'italy',
'PRT' => 'portugal',
'RUS' => 'russia',
'UKR' => 'ukraine',
'FRA' => 'france',
'SCO' => 'scotland',
'UEFA'=> 'uefa',
'FIN' => 'finland',
'SUI' => 'switzerland',
'FIFA'=> 'fifa',
'FCL' => 'Friendly',
'UNL' => 'world',
'WL'  => 'World',
'IST' => 'sfp-20',
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
    echo ($ret[0] == r ? 0 : 1);
  }

  // запись файла
  if ($data['cmd'] == 'save_file') {
    include ('../' . $data['a'] . '/settings.inc.php');
    if ($data['author'] == $president || $data['author'] == $vice || in_array($data['author'], $admin)) {
      if ($data['m'] == 'text') {
        switch ($data['ref']) {
          case 'it'  : $f = isset($data['t']) ? 'publish/it'.$data['t'] : 'it.tpl'; break;
          case 'itc' : $f = 'itc.tpl'; break;
          case 'p'   : $f = isset($data['t']) ? 'publish/p'.$data['t']  : 'p.tpl'; break;
          case 'pc'  : $f = 'pc.tpl'; break;
          case 'r'   : $f = isset($data['t']) ? 'publish/r'.$data['t']  : 'header'; break;
        }
        $file = $data_dir . 'online/' . $cca . '/' . $data['s'] . '/' . $f;
      }
      else if ($data['m'] == 'main')
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
    echo 1;
  }

  // запись конфигурационных файлов
  if ($data['cmd'] == 'save_config') {
    include ('../' . $data['a'] . '/settings.inc.php');
    if ($data['author'] == $president || $data['author'] == $vice || in_array($data['author'], $admin)) {
      // settings.inc.php
      $settings = '<?php
$cca = \''.$cca.'\';
$description = \''.$_POST['description'].'\';
$title = \''.$_POST['title'].'\';
$main_header = \''.$_POST['main_header'].'\';
$cur_year = \''.$_POST['cur_year'].'\';
$president = \''.$_POST['president'].'\';
$vice = \''.$_POST['vice'].'\';
$pressa = \''.$_POST['pressa'].'\';
$coach = \''.$_POST['coach'].'\';
$club_edit = '.(isset($_POST['club_edit']) ? 'true' : 'false').';
?>
';
      file_put_contents('../'.$data['a'].'/settings.inc.php', $settings);

      if ($_POST['cur_year'] > $data['s']) { // создать структуру нового сезона
        $old = $online_dir.$cca.'/'.$data['s'].'/';
        $new = $online_dir.$cca.'/'.$_POST['cur_year'].'/';
        mkdir($new.'bomb', 0755, true);
        mkdir($new.'bombc', 0755, true);
        mkdir($new.'bombs', 0755, true);
        mkdir($new.'prognoz', 0755, true);
        mkdir($new.'programs', 0755, true);
        mkdir($new.'publish', 0755, true);
        copy($old.'codes.tsv', $new.'codes.tsv');
        copy($old.'headers',   $new.'headers');
        copy($old.'p.tpl',     $new.'p.tpl');
        copy($old.'it.tpl',    $new.'it.tpl');
        copy($old.'pc.tpl',    $new.'pc.tpl');
        copy($old.'itc.tpl',   $new.'itc.tpl');
      }
      // fp.cfg
      $fpcfg = [];
      for ($t = 0; $t < count($_POST['tournament']); $t++) {
        if ($_POST['tournament'][$t]) $fpcfg[$t]['tournament'] = $_POST['tournament'][$t];
        if ($_POST['type'][$t]) $fpcfg[$t]['type'] = $_POST['type'][$t];
        if ($_POST['numeration'][$t]) $fpcfg[$t]['numeration'] = $_POST['numeration'][$t];
        if ($_POST['prefix'][$t]) $fpcfg[$t]['prefix'] = $_POST['prefix'][$t];
        for ($e = 0; $e < count($_POST['stage']); $e++)
          if (isset($_POST['tourn'][$t][$e])) {
            if ($_POST['stage'][$t][$e]) $fpcfg[$t]['format'][$e]['stage'] = $_POST['stage'][$t][$e];
            if ($_POST['suffix'][$t][$e]) $fpcfg[$t]['format'][$e]['suffix'] = $_POST['suffix'][$t][$e];
            if ($_POST['cal'][$t][$e]) $fpcfg[$t]['format'][$e]['cal'] = $_POST['cal'][$t][$e];
            if ($_POST['groups'][$t][$e]) $fpcfg[$t]['format'][$e]['groups'] = $_POST['groups'][$t][$e];
            if ($_POST['tourn'][$t][$e]) $fpcfg[$t]['format'][$e]['tourn'] = $_POST['tourn'][$t][$e];
            if ($_POST['round'][$t][$e]) $fpcfg[$t]['format'][$e]['round'] = $_POST['round'][$t][$e];
            if ($_POST['nprefix'][$t][$e]) $fpcfg[$t]['format'][$e]['nprefix'] = $_POST['nprefix'][$t][$e];
          }

      }
      $json = "prognoz\ncodes.tsv\ncal\n0\n" . json_encode($fpcfg, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
      file_put_contents($data_dir.'online/'.$cca.'/'.$_POST['cur_year'].'/fp.cfg', $json);
    }
    echo 1;
  }

  // рассылка
  if ($data['cmd'] == 'send_mail') {
    $ret = 0;
    include ('../' . $data['a'] . '/settings.inc.php');
    if ($data['author'] == $president || $data['author'] == $vice || $data['author'] == $pressa || in_array($data['author'], $admin)) {
      $cca = array_search($data['a'], $ccn);
      $email = '';
      if ($cca == 'FIFA') {
        $amail = [];
        $map = explode(';', file_get_contents($data_dir . 'auth/.map'));
        foreach ($map as $mail)
          if (strpos($mail, '@') && strpos($mail, '.')) {
            $emails = explode(',', strtolower($mail));
            foreach ($emails as $mail)
              $amail[$mail] = 1;
          }

        foreach ($amail as $mail => $tt)
          $email .= ($email ? ',' : '') . trim($mail);
      }
      else {
        $codes = file($online_dir . $cca . '/' . $data['s'] . '/codes.tsv');
        $i = 0;
        foreach ($codes as $line) if (trim($line)) {
          list($code, $cmd, $name, $mail, $long) = explode("\t", $line);
          if (!isset($_POST['p']) || isset($_POST['p'][$i++])) {
            $emails = explode(',', $mail);
            foreach ($emails as $mail)
              if (trim($mail))
                $email .= ($email ? ',' : '') . $name . ' <' . trim($mail) . '>';

          }
        }
      }
      if (!isset($data['t']))
        file_put_contents($online_dir . $cca . '/' . $data['s'] . '/publish/'.time(), $_POST['text']);

      $ret = send_email($data['a'].' <'.strtolower($cca).'@'.$_SERVER['HTTP_HOST'].'>', $data['author'], $email, $_POST['subj'], $_POST['text']);
    }
    echo $ret;
  }
}
?>