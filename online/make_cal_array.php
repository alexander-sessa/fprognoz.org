<?php
$a = '$cal = [';
foreach ([10, 12, 14, 16, 18] as $n) {
  $a .= $n.'=>[';
  $cal = file('cal.'.$n, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($cal as $line)
    $a .= '['.$line.'],';
  $a .= '],';
}
$a .= ']';
echo strtr($a, [',]' => ']']);
