<p class="title text15b">&nbsp;&nbsp;&nbsp;Сетка кубка</p>
<style>pre {font-size: 0.6em}</style>
<?php $season = isset($s) ? $s : $cur_year;
$file = $online_dir.$cca.'/'.$season.'/cup.inc';
if (is_file($file))
  include ($file);

?>
