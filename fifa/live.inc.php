<center>
<a href="/?m=live&amp;ls=livescore.bz"><img src="https://www.livescore.bz/img/logoY.png" height="32" border="0" alt="livescore.bz" class="bg-secondary"></a>
<a href="/?m=live&amp;ls=xscores.com"><img src="https://www.xscores.com/image/logosred_site.png" height="32" border="0" alt="xscores.com"></a>
<a href="/?m=live&amp;ls=enetscores"><img src="https://www.enetscores.com/img/client/enet_logo.png" height="34" border="0" alt="enetscores"></a>
<?php
$ls = $ls ?? 'enetscores';
if ($ls == 'enetscores')
    echo '
<script type="text/javascript" src="https://widget.enetscores.com/FW893EBB1C9892A8FA"></script>';
else if ($ls == 'xscores.com')
    echo '
<div id="widgetBox"><div id="free-livescore-widget-box" style="-webkit-overflow-scrolling:touch !important; overflow-y:auto !important;"><iframe id="free-livescore-iframe"></iframe></div><div id="xscoresLink" style="background-color:#036;height:40px;width:100%;"><a id="a-xscores-link" target="_blank" href="https://www.xscores.com" style="color:#FFFFFF;text-align:center;display:block;font-size:13px;font-weight:normal;font-family:\'Oswald\', sans-serif;padding-top:12px;text-decoration:none;" onmouseover="this.style.textDecoration=\'underline\'" onmouseout="this.style.textDecoration=\'none\'">Get your Free Livescore service at XScores.com</a></div></div><script id="widgethandler" type="application/javascript" src="https://widgets.xscores.com/widget.min.js" data-webuid="e70b5831d2cee1d007b783ba552b1384" data-height="900" data-width="970" data-type="1"></script>';
else
    echo '
<script type="text/javascript" src="https://www.livescore.bz/api.livescore.0.1.js" api="livescore" async></script><br><a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">recent matches</a>';
?>
</center>
