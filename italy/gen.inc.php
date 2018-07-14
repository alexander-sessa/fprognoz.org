<p class="title text15b">&nbsp;&nbsp;&nbsp;Прогнозы-генераторы на чемпионат</p>
<pre>
<?php
$season = isset($s) ? $s : $cur_year;
$file = $online_dir.$cca.'/'.$season.'/gen';
if (is_file($file))
  include ($file);

?>
</pre>
