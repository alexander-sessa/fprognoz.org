<?php
mb_internal_encoding('UTF-8');
require_once ('/home/fp/data/config.inc.php');
$iv = substr(md5('iv'.$salt, true), 0, 8);
$key = substr(md5('pass1'.$salt, true) . md5('pass2'.$salt, true), 0, 24);

$email = strtoupper($_POST['email']);
$keyword = mb_strtoupper($_POST['keyword']);
$data = json_decode(trim(mcrypt_decrypt( MCRYPT_BLOWFISH, $key, base64_decode($_POST['data']), MCRYPT_MODE_CBC, $iv )), true);

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

$afile = $data_dir . 'auth/.access';
$mfile = $data_dir . 'auth/.map';
if (!is_file($mfile) || filectime($afile) > filectime($mfile)) {
  $map = build_search_map($afile);
  file_put_contents($mfile, $map);
}
else
  $map = file_get_contents($mfile);

if (($ptr = strpos($map, ";$keyword;")) === false)
  echo 0;
else {
  $map = substr($map, 0, strpos($map, "\n", $ptr));
  echo (strpos($map, ";!;$email;", $ptr - 3) ? 2 : 1);
}
?>