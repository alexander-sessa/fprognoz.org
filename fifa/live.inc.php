<center>
<a href="/?m=live&amp;ls=livescore.bz"><img src="https://www.livescore.bz/img/logoY.png" height="32" border="0" alt="livescore.bz" /></a>
<a href="/?m=live&amp;ls=inscore"><img src="/images/livescore_in.gif" height="34" border="0" alt="inscore" /></a>
<?php
if (!isset($ls)) $ls = 'livescore.bz';
if ($ls == 'inscore') {
  if(strpos($con = ini_get("disable_functions"), "fsockopen") === false) {if(is_resource($fs = fsockopen("www.livescore.in", 80, $errno, $errstr, 3)) && !($stop = $write = !fwrite($fs, "GET /ru/free/lsapi HTTP/1.1\r\nHost: www.livescore.in\r\nConnection: Close\r\nlsfid: 193650\r\n\r\n"))) {$content = "";while (!$stop && !feof($fs)) {$line = fgets($fs, 128);($write || $write = $line == "\r\n") && ($content .= $line);}fclose($fs);$c = explode("\n", $content);foreach($c as &$r) {$r = preg_replace("/^[0-9A-Fa-f]+\r/", "", $r);}$content = implode("", $c);} else $content .= $errstr."(".$errno.")<br />\n";} elseif(strpos($con, "file_get_contents") === false && ini_get("allow_url_fopen")) {$content = file_get_contents("https://www.livescore.in/ru/free/lsapi", 0, stream_context_create(array("http" => array("timeout" => 3, "header" => "lsfid: 193650 "))));} elseif(extension_loaded("curl") && strpos($con, "curl_") === false) {curl_setopt_array($curl = curl_init("https://www.livescore.in/ru/free/lsapi"), array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => array("lsfid: 193650 ")));$content = curl_exec($curl);curl_close($curl);} else {$content = "PHP inScore не может быть загружен. Попросите разрешения у хостинг-провайдера разрешить функцию `file_get_contents` вместе с функцией `allow_url_fopen` или `fsockopen`.";}echo $content;
}
else {
?>
<script type="text/javascript" src="https://www.livescore.bz/api.livescore.0.1.js" api="livescore" async></script><a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">recent matches</a>
<?php } ?>
</center>
