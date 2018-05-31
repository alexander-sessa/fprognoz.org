<?php
date_default_timezone_set('Europe/Berlin');
include('/home/fp/data/config.inc.php');
if ($have_redis)
  $redis = new Redis();
else {
  include('redis-emu.php');
  $redis = new Redis_emu();
}
$redis->connect($redis_host, $redis_port);
while(list($k,$v)=each($_GET)) $$k=$v;
if ($hash == crypt($user, $salt)) {
  ($vote) ? $vote = 1 : $vote = -1;
  $redis->zadd('voting:' . $id, $vote, $user);
}

$redis->close();
?>
