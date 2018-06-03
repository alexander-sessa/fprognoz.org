<?php
class Redis_emu {
  public $path;
  function __construct() {
    global $data_dir;
    $this->path = $data_dir . 'redis-emu/';
  }
  function connect($host=localhost, $port=6379) {
    return true;
  }
  function close() {
    return true;
  }
  function lock($key) {
    if (is_file($this->path . $key)) {
      $timer = 0;
      while (is_file($this->path . $key . '.lock') && $timer < 10) {
        $timer++;
        time_nanosleep(0, 1000);
      }
      touch($this->path . $key . '.lock');
      return ($timer < 10);
    }
    touch($this->path . $key . '.lock');
    return true;
  }
  function write($key, $out) {
    file_put_contents($this->path . $key, $out);
    unlink($this->path . $key . '.lock');
    return true;
  }
  function incr($key) {
    $this->lock($key);
    $id = file_get_contents($this->path . $key);
    $this->write($key, ++$id);
    return $id;
  }
  function save($key, $a) {
    return $this->write($key, "\$a=" . var_export($a, true) . ";");
  }
  function load($key) {
    $a = array();
    if (is_file($this->path . $key)) eval(file_get_contents($this->path . $key));
    return $a;
  }
  function get($key) {
    return is_file($this->path . $key) ? file_get_contents($this->path . $key) : false;
  }
  function exists($key) {
    return is_file($this->path . $key);
  }
  function clone($source, $destination) {
    if (!is_file($this->path . $source) || is_file($this->path . $destination))
      return false;

    return copy($this->path . $source, $this->path . $destination);
  }
  function hset($key, $name, $value) {
    $this->lock($key);
    $a = $this->load($key);
    $a[$name] = $value;
    return $this->save($key, $a);
  }
  function hmset($key, $array) {
    $this->lock($key);
    return $this->save($key, array_merge($this->load($key), $array));
  }
  function hget($key, $name) {
    $a = $this->load($key);
    return isset($a[$name]) ? $a[$name] : false;
  }
  function hgetall($key) {
    return $this->load($key);
  }
  function lpush($key, $value) {
    $this->lock($key);
    $a = $this->load($key);
    return $this->save($key, array_merge([$value],$a));
  }
  function lrange($key, $start=0, $stop=-1) {
    $a = $this->load($key);
    if ($stop == -1) return $a;
    else return array_slice($a, $start, 1 + $stop - $start);
  }
  function zadd($key, $score, $id) {
    $this->lock($key);
    $a = $this->load($key);
    $a[$id] = $score;
    return $this->save($key, $a);
  }
  function zcount($key, $min, $max) {
    $a = array_count_values($this->load($key));
    $c = 0;
    for ($i=$min;$i<=$max;$i++)
      if (isset($a[$i]))
        $c += $a[$i];
    return $c;
  }
  function zincrby($key, $inc, $id) {
    $this->lock($key);
    $a = $this->load($key);
    if (!isset($a[$id])) $a[$id] = 0;
    $a[$id] += $inc;
    return $this->save($key, $a);
  }
}
?>