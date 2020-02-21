<center>
<a href="/?m=live&amp;ls=livescore.bz"><img src="https://www.livescore.bz/img/logoY.png" height="32" border="0" alt="livescore.bz" /></a>
<a href="/?m=live&amp;ls=enetscores"><img src="https://www.enetscores.com/img/client/enet_logo.png" height="34" border="0" alt="enetscores" /></a>
<?php
if (!isset($ls)) $ls = 'livescore.bz';
echo ($ls == 'enetscores' ? '
<script type="text/javascript" src="https://widget.enetscores.com/FW893EBB1C9892A8FA"></script>' : '
<script type="text/javascript" src="https://www.livescore.bz/api.livescore.0.1.js" api="livescore" async></script><br><a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">recent matches</a>');
?>
</center>
