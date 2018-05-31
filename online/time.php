<?php
date_default_timezone_set('Europe/Berlin');
if (isset($_POST['date-time']))
    echo strtotime($_POST['date-time']);
?>
<br />
<form method="POST">
<input type="text" name="date-time" placeholder="[[YYYY/]MM/DD ]HH:MM[:SS]" value="<?=isset($_POST['date-time'])?$_POST['date-time']:''?>" />
<input type="submit" />
</form>