<?php
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
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

// добавление комментов
if (isset($_POST['c_from']))
{
  chdir('..');
  if ($have_redis)
    $redis = new Redis();
  else
  {
    include('comments/redis-emu.php');
    $redis = new Redis_emu();
  }
  $role = 'player';
  $a = $_POST['a'];
  $s = $_POST['s'];
  $c_from = $_POST['c_from'];
  $coach_name = $_POST['coach'];
  include('comments/main.php');
  exit;
}

function date_tz($format, $date, $time, $tz) {
  $datetime = new DateTime();
  $server_tz = new DateTimeZone(date_default_timezone_get());
  $datetime->setTimeZone($server_tz);
  if (strlen($time) == 10)
    $datetime->setTimestamp($time);
  else
  {
    if (!$date)
      $date = date('Y-m-d');
    else if (strlen($date) < 6)
      $date = date('Y-') . $date;

    list($y, $m, $d) = explode('-', $date);
    $datetime->setDate($y, $m, $d);
    list($m, $s) = explode(':', $time);
    $datetime->setTime($m, $s, 0);
  }

  $timezone = new DateTimeZone($tz);
  $datetime->setTimeZone($timezone);
  return $datetime->format($format);
}

function build_search_map($file) {
  $access = file($file);
  $map = []; // коды и имена привязанные к мейлам
  $tmp = []; // коды, привязанные к имени, если не указан  мейл
  $nm = []; // соответствие мейла имени
  foreach ($access as $access_str) {
    list($code, $as_code, $team, $name, $mail, $pwd, $rol) = explode(';', $access_str);
    $code = mb_strtoupper($code);
    $name = $as_code == 'UNL' ? $code : mb_strtoupper($name); // в UNL имена могут дублироваться, поэтому только код!
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

function lock($lock, $timer) {
  while ($timer-- && is_file($lock)) time_nanosleep(0, 1000);
  if ($timer) touch($lock);
  return ($timer);
}

function send_predict($country_code, $season, $team_code, $tour, $prognoz, $enemy_str, $ip) {
  global $ccn;
  global $data_dir;
  $time = time();
  $name = $email = '';
  $cca_home = $data_dir . 'online/' . $country_code . '/';
  $acodes = file($cca_home . $season .'/codes.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($acodes as $scode) if ($scode[0] != '#') {
    $ateams = explode('	', $scode);
    if (trim($ateams[0]) == $team_code) {
      $name = trim($ateams[2]);
      $email = trim($ateams[3]);
    }
  }
  $replyto = $email ? "\nReply-To: $email" : '';
  $mlist = $email;
  if (is_file($cca_home . 'emails')) {
    $atemp = file($cca_home . 'emails');
    foreach ($atemp as $line)
      if ($line = trim($line)) {
        list($pmail, $pcode) = explode(':', $line);
        if ($pcode != $enemy_str) $mlist .= ($mlist ? ', ' : '') . $pmail;
      }

  }
  $subj = strtoupper($ccn[$country_code]);
  $body = "FP_Prognoz\n$team_code\n$tour\n$prognoz\n";

  // write direct
  if (strpos($prognoz, '  ')) list($prognoz, $pena) = explode('  ', $prognoz);
  isset($pena) ? $pena = strtoupper(trim($pena)) : $pena = '';
  $tour_dir = $cca_home . $season . '/prognoz/' . $tour;
  if (!is_dir($tour_dir)) mkdir($tour_dir, 0755, true);
  if (is_file($tour_dir . '/mail')) {
    $lock = $tour_dir.'/lock';
    if (lock($lock, 5000)) {
      $content = file_get_contents($tour_dir . '/mail');
      file_put_contents($tour_dir . '/mail', $content . "$team_code;$prognoz;$time;$pena\n");
      unlink($lock);
      if (is_file($data_dir . 'personal/' . $name . '/navbar.inc'))
        unlink($data_dir . 'personal/' . $name . '/navbar.inc');

    }
    else
      echo 'В течение минуты прогноз должен появиться в списке полученных.<br>';
  }
  else
    file_put_contents($tour_dir . '/mail', "$team_code;$prognoz;$time;$pena\n");

  @mail('fp@fprognoz.org', $subj, $body,
'From: =?UTF-8?B?'.base64_encode($team_code).'?= <fp@fprognoz.org>'.$replyto.'
Date: '. date('r', $time) .'
MIME-Version: 1.0
Content-Type: text/plain;
        charset="utf8"
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 4.00.180121-'.$ip);
  if ($mlist)
    echo rtrim(trim(strtr(send_email($team_code . ' <fp@fprognoz.org>', '', $mlist, $subj, $body), ['Отправлено по адресам:<br>' => 'Дубль на: ', '<br>' => ', '])), ',').'<br>';

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
      if ($ccc == 'UNL')
        $name = $code; // здесь имена могут дублироваться, поэтому только код!

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

$iv = substr(hash('sha256', 'iv'.$salt), 0, 16);
$key = hash('sha256', 'pass1'.$salt);
$data = json_decode(trim(openssl_decrypt( base64_decode($_POST['data']), 'AES-256-CBC', $key, 0, $iv )), true);
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
      if (($hash == $pwd || $hash == $SuperPWD) && ($name_up == mb_strtoupper($code) || $name_up == mb_strtoupper($name) || $name_up == strtoupper($mail))) {
        $logfile = fopen($online_dir . 'log/auth.log', 'a');
        fwrite($logfile, date('Y-m-d H:i:s').' '.$name_str."\n");
        fclose($logfile);
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

Используйте его вместе с именем ' . $name_str . ',
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
    $data_cfg = array('cmd' => 'auth_token', 'name' => $name, 'mail' => $mail, 'ts' => (time() + 600));
    $cfg = rtrim(base64_encode(openssl_encrypt( json_encode($data_cfg), 'AES-256-CBC', $key, 0, $iv )), '=');
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
    if ($data['author'] == $president || $data['author'] == $vice || $data['author'] == $pressa || in_array($data['author'], $admin)) {
      if ($data['m'] == 'text') {
        switch ($data['ref']) {
          case 'it'  : $f = isset($data['t']) ? (isset($data['l']) ? 'publish/'.$data['l'].'/itc'.$data['t'] : 'publish/it'.$data['t']) : 'it.tpl'; break;
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
      $text = rtrim($text)."\n\n";
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
    $team_select = '';
    if (isset($_POST['p']) && $data['a'] == 'world')
    {
      $team_file = $data_dir.'personal/'.$data['author'].'/team.'.date('Y');
      if (is_file($team_file))
        $team_select = file_get_contents($team_file);

    }
    if ($team_select || $data['author'] == $president || $data['author'] == $vice || $data['author'] == $pressa || in_array($data['author'], $admin))
    {
      $cca = array_search($data['a'], $ccn);
      $email = '';
      if (isset($_POST['p']) && $data['a'] == 'world')
      {
        $codes = file($online_dir.$cca.'/'.$data['s'].'/'.$team_select.'.csv');
        $i = 0;
        foreach ($codes as $line) if (trim($line))
        {
          list($name, $mail, $long) = explode(';', $line);
          if (!isset($_POST['p']) || isset($_POST['p'][$i++]))
          {
            $emails = explode(',', $mail);
            foreach ($emails as $mail)
              if (trim($mail))
                $email .= ($email ? ',' : '') . $name . ' <' . trim($mail) . '>';

          }
        }
      }
      else if ($cca == 'FIFA' || $cca == 'UNL')
      {
        $amail = [];
        $map = explode(';', file_get_contents($data_dir . 'auth/.map'));
        foreach ($map as $mail)
          if (strpos($mail, '@') && strpos($mail, '.'))
          {
            $emails = explode(',', strtolower($mail));
            foreach ($emails as $mail)
              $amail[$mail] = 1;
          }

        foreach ($amail as $mail => $tt)
          $email .= ($email ? ',' : '') . trim($mail);

      }
      else
      {
        $codes = file($online_dir . $cca . '/' . $data['s'] . '/codes.tsv');
        $i = 0;
        foreach ($codes as $line) if (trim($line))
        {
          list($code, $cmd, $name, $mail, $long) = explode("\t", $line);
          if (!isset($_POST['p']) || isset($_POST['p'][$i++]))
          {
            $emails = explode(',', $mail);
            foreach ($emails as $mail)
              if (trim($mail))
                $email .= ($email ? ',' : '') . $name . ' <' . trim($mail) . '>';

          }
        }
      }
      if (!isset($data['t']) && !isset($_POST['p']))
        file_put_contents($online_dir . $cca . '/' . $data['s'] . '/publish/'.time(), $_POST['text']);

      $ret = send_email($data['a'].' <'.strtolower($cca).'@'.$_SERVER['HTTP_HOST'].'>', $data['author'], $email, $_POST['subj'], $_POST['text']);
    }
    echo $ret;
  }
  // переключение фан-зоны
  if ($data['cmd'] == 'fun_zone') {
    $coach_name = $data['c'];
    $file = $data_dir.'personal/'.$coach_name.'/gb.inc';
    if (is_file($file))
    {
      unlink($file);
      echo '';
    }
    else {
      touch($file);
      chdir('..');
      if ($have_redis)
        $redis = new Redis();
      else
      {
        include('comments/redis-emu.php');
        $redis = new Redis_emu();
      }
      $role = 'player';
      $a = $data['a'];
      $s = $data['s'];
      include('comments/main.php');
    }
    exit;
  }

  // API отправки прогнозов, составов, замен
  if ($data['cmd'] == 'send_by_api') {
    //$coach_name = $data['c'];
    $coach_mail = $data['email']; // не используется, может быть потом понадобится
    $ip = $_POST['ip'] ?? isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    $tour = $_POST['tour'];
    $cca = substr($tour, 0, 3);
    if (in_array($cca, ['UEF', 'CHA', 'CUP', 'GOL']))
      $cca = 'UEFA';

    include ('../' . $ccn[$cca] . '/settings.inc.php');
    $season_dir = $online_dir . $cca . '/' . $cur_year;
    $prognoz_dir = $season_dir. '/prognoz/' . $tour;
    $closed = is_file($prognoz_dir.'/closed');

    // замены на 2 тайм:

    if ($closed)
      echo '<i class="fas fa-times text-danger"></i> Увы, Вы опоздали - дедлайн уже наступил.<br>';
    else if (!count($_POST['predicts']))
      echo '<i class="fas fa-times text-danger"></i> Получен пустой прогноз.<br>';
    else
    {
      if (isset($_POST['team']))
      { // состав на 1 тайм: team, ['players'], ['predicts']
        // проверка полномочий
        $coach = '';
        $codes = file($season_dir . '/' . $_POST['team'] . '.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($codes as $line)
          if (!$coach && ($cut = strpos($line, $coach_mail)) && substr($line, $cut + 1 + strlen($coach_mail), 5) == 'coach')
            $coach = substr($line, 0, $cut - 1);

        if (!$coach)
        { // вероятно, был упрощенный файл состава команды, проверим в codes
          $codes = file($season_dir . '/codes.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
          foreach ($codes as $line)
            if (!$coach && ($cut = strpos($line, $_POST['team'])))
              if (strpos($line, $coach_mail) && strpos($line, '	coach'))
                $coach = substr($line, 0, $cut - 1);

          if (!$coach)
            die('<i class="fas fa-times text-danger"></i> Ваши полномочия не подтверждены.<br>');

        }
        $out = '';
        $all_predicts = [];
        $main_size = strlen($_POST['team']) != 3 || $_POST['team'] == 'SFP' || $tour > 'UNL11' ? 5 : 4; // size-1
        $log = 'состав первого тайма:<br>';
        if (is_file($prognoz_dir.'/'.$_POST['team']))
        {
          $lines =  file($prognoz_dir.'/'.$_POST['team']);
          foreach ($lines as $line) if (trim($line))
          {
            list($name, $predict, $ts, $rest) = explode(';', trim($line));
            $all_predicts[$name] = $predict;
          }
        }
        $added = [];
        $i = 0;
        $bopen = true;
        foreach ($_POST['players'] as $pos => $name) {
          $predict = strtr($_POST['predicts'][$pos], ['‎' => '', ' ' => '']);
          if ($predict && (!isset($all_predicts[$name]) || $all_predicts[$name] != $predict))
            $added[$name] = $predict;

          $out .= "$name;$predict;;\n";
          $log .= (strlen($log) > 45 ? ', ' : ' ') . ($i == 0 ? '<mark>' : '') . $name;
          if ($i++ == $main_size)
          {
            $log .= '</mark>';
            $bopen = false;
          }
        }
        if ($bopen)
          $log .= '</mark>';

        if (count($added)) {
          $log .= '<br> и при этом внёс прогноз' . (count($added) > 1 ? 'ы' : '');
          foreach ($added as $name => $predict)
            $log .= " $name:$predict";

        }
        file_put_contents($prognoz_dir.'/'.$_POST['team'], $out);
        $pr_saved = true;
        $hisfile = fopen($prognoz_dir.'/.'.$_POST['team'], 'a');
        fwrite($hisfile, "$coach;$log;" . time() . "\n");
        fclose($hisfile);
        echo '<i class="fas fa-check text-success"></i> Состав команды записан.<br>';
      }
      else if (isset($_POST['team_codes']))
      { // подача прогнозов: ['team_codes'], ['predicts']
        $enemy_str = ''; // потом сделать!
        foreach ($_POST['team_codes'] as $tc)
        {
          $prognoz_post = trim(rtrim(current($_POST['predicts']), '='));
          if (!next($_POST['predicts']))
            reset($_POST['predicts']);

          if ($prognoz_post)
          {
            echo '<i class="fas fa-check text-success"></i> Принят прогноз от '.$tc.' на тур '.$tour.'.<br>';
            send_predict($cca, $cur_year, $tc, $tour, $prognoz_post, $enemy_str, $ip);
          }
          else
            echo '<i class="fas fa-times text-danger"></i> Получен пустой прогноз.<br>';

        }
      }
    }
  }
}
?>