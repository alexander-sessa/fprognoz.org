<p class="title text15b">&nbsp;&nbsp;&nbsp;Календарь чемпионата</p>
<pre>
<?php
$season = isset($s) ? $s : $cur_year;
$file = $online_dir.$cca.'/'.$season.'/cal';
if (is_file($file))
  include ($file);

?>
</pre>
