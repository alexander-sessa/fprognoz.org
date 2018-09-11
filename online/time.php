<?php
date_default_timezone_set('Europe/Berlin');
if (isset($_POST['date-time']))
    if (is_numeric($_POST['date-time']) && strlen($_POST['date-time']) == 10)
        echo date('Y-m-d H:i:s', $_POST['date-time']);
    else
        echo strtotime($_POST['date-time']);
?>
<br />
<form method="POST">
<input type="text" name="date-time" placeholder="[[YYYY/]MM/DD ]HH:MM[:SS]" value="<?=isset($_POST['date-time'])?$_POST['date-time']:''?>" />
<input type="submit" />
</form>
