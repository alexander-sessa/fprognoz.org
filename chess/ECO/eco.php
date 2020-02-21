<?php
$lines = explode('<tr>
<td>', file_get_contents('r_eco.htm'));
unset($lines[0]);
$out = [];
foreach ($lines as $line)
{
    list($eco, $moves, $names) = explode('</td>
<td>', $line, 3);
    $names = substr($names, 0, strpos($names, '</td>'));
    while (($cut = strpos($names, '<a')) !== false)
    {
        $from = strpos($names, '">', $cut) + 2;
        $to = strpos($names, '</a>');
        $names = substr($names, 0, $cut) . substr($names, $from, $to - $from) . substr($names, $to + 4);
    }
    $out[$eco] = $names;
}
file_put_contents('eco.inc', var_export($out, true));