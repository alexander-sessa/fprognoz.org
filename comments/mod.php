<?php
date_default_timezone_set('Europe/Berlin');

function send_comment_by_email($from, $name, $email, $subj, $body) {
  $email = str_replace(',', ' ', $email);
  $amail = explode(' ', $email);
  foreach ($amail as $email) if (strpos($email, '@'))
  {
    $email = trim($email);
    if (trim($from) && trim($subj) && trim($body))
      @mail("$name <$email>",
'=?UTF-8?B?'.base64_encode($subj).'?=',
str_replace("\r", '', $body).'
',
'From: '.$from.'
Reply-To: '.$from.'
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 3.07.170321');

  }
}

include('/home/fp/data/config.inc.php');
if ($have_redis)
  $redis = new Redis();
else {
  include('redis-emu.php');
  $redis = new Redis_emu();
}
$redis->connect($redis_host, $redis_port);

while(list($k,$v)=each($_GET)) $$k=$v;

$comments_email = 'SFP Comment System <scs@fprognoz.org>';

switch ($status) {
  case  1: // одобрить
    $redis->hset($key, 'status', 1);

    $parent = $redis->hget($key, 'parent');
    if ($parent[0] == 'c') {          // если добавлен ответ на чей-то коммент,
      $content = $redis->hgetall('content:' . explode(':', $parent)[1]);
      if ($content['notify']) {       // если автор того коммента хочет видеть ответы, шлём ему весточку
        $id = explode(':', $parent)[1];
        while ($parent[0] == 'c') {
          $parent = $redis->hget('content:' . $id, 'parent');
          $id = explode(':', $parent)[1];
        }
        $url = 'https://fprognoz.org/' . $id . '#comment';
        $status = 2;  // = отправлено уведомление об ответе
        $notify_user = $redis->hgetall('c_user:' . $content['userid']);
        send_comment_by_email ($comments_email,
'=?UTF-8?B?' . base64_encode($notify_user['nameof']) . '?=', $notify_user['e-mail'],
'Получен ответ на Ваш комментарий', '
На страницу <a href="' . $url . '">' . $url . '</a>
пользователем ' . $redis->hget('c_user:' . $redis->hget($key, 'userid'), 'nicknm') . ' добавлен ответ на Ваш комментарий:
<hr />
' . strip_tags($redis->hget($key, 'c_text'), '<p><a><strong><em><ol><ul><li>') . '
<hr />
');
      }
    }
    break;
  case  6: // банить юзера
    $redis->hset('c_user:' . $redis->hget($key, 'userid'), 'status', 2600000 + time()); // бан почти на месяц
  case -1: // скрыть коммент
    $redis->hset($key, 'status', -1);
    $redis->zincrby('activeuser', -1, $redis->hget($key, 'userid')); // декремент счётчика сообщений юзера
    break;
}
$mod_text = array(
 -1 => 'Комментарий скрыт от посторонних глаз',
  1 => 'Комментарий проверен модератором',
  2 => 'Комментарий проверен модератором. Отправлено уведомление об ответе',
  6 => 'Комментарий скрыт, пользователь заблокирован',
);
echo $mod_text[$status];
$redis->close();
?>
