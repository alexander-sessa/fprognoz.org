<?php
$season = isset($s) ? $s : $cur_year;
$file = $online_dir.$cca.'/'.$season.'/cham.inc';
if (is_file($file))
  include ($file);

?>
