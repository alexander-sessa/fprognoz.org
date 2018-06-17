<?php
if (is_file($online_dir.'WL/'.$s.'/publish/it'.$t.'.1'))
  include ($online_dir.'WL/'.$s.'/publish/it'.$t.'.'.(isset($n)?$n:1));
else
  include ($online_dir.'WL/'.$s.'/publish/it'.$l.$t.'.'.(isset($n)?$n:1));
?>
