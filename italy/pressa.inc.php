<br />
<?php
if (isset($t))
  echo '<input type="submit" name="mailer" value="обзор тура" onclick='."'".'location.href="?a='.$a.'&amp;s='.$s.'&amp;t='.$t.'&amp;m=maillist&amp;file=review";'."'".' />
';
?>
<input type="submit" name="mailer" value="пресс-релиз" onclick='location.href="?<?="a=$a&amp;s=$s";?>&amp;m=maillist";' />
