<?php
include('/home/fp/data/config.inc.php');
if ($have_redis)
  $redis = new Redis();
else {
  include('redis-emu.php');
  $redis = new Redis_emu();
}
$redis->connect($redis_host, $redis_port);
if (isset($_POST['hash']) && ($_POST['hash'] == crypt($_POST['user'], $salt)))
  $redis->hset('content:' . $_POST['id'], 'c_text', urldecode($_POST['c_text']));

file_put_contents('test/c_text', urldecode($_POST['c_text']));
$redis->close();
exit();
?>
