<?php
$ab = array();
if (!isset($s) || !$s) {
  $dir = scandir($online_dir . $cca, 1);
  foreach ($dir as $s)
    if ($s[0] == '2')
      break;

}
$bombers_file = $online_dir . $cca . '/' . $s . '/bombers';
if (is_file($bombers_file)) {
  $bombers = str_replace("\r", '', file_get_contents($bombers_file));
  $abombers = explode('Team: ', $bombers);
  foreach ($abombers as $bteam) {
    $ateam = explode("\n", $bteam);
    $tn = trim($ateam[0]);
    unset($ateam[0]);
    foreach($ateam as $line)
      if ($line = trim($line))
        $ab[$tn][substr($line, 0, 2)] = substr($line, 3);

  }
}
$edit = '';
?>
<form action="" method="post">
<table width="100%">
  <tr><td></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/belarusBATE.png" alt="" /></td>
    <td>
      <br />
      <b>ФК БАТЭ Борисов</b><br /><br />
      город: <b>Барысаў (Борисов)</b><br /><br />
      год основания: <b>1973</b><br /><br />
      арена: <b>Гарадскі стадыён</b><br />
      вместительность: <b>5502</b><br /><br />
      сайт: <a href="http://www.fcbate.by" target="_blank">http://www.fcbate.by</a>
<?php if ($edit == "BATE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BATE"] as $pos => $bomber)
  if ($edit == "BATE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BATE"] as $pos => $bomber)
  if ($edit == "BATE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BATE(*)"] as $pos => $bomber)
  if ($edit == "BATE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BATE(*)"] as $pos => $bomber)
  if ($edit == "BATE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusDINMN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Минск</b><br /><br />
      город: <b>Мінск (Минск)</b><br /><br />
      год основания: <b>1927</b><br /><br />
      арена: <b>Стадыён "Дынама"</b><br />
      вместительность: <b>41024</b><br /><br />
      сайт: <a href="http://www.dinamo-minsk.by" target="_blank">http://www.dinamo-minsk.by</a>
<?php if ($edit == "DINMN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DINMN"] as $pos => $bomber)
  if ($edit == "DINMN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINMN"] as $pos => $bomber)
  if ($edit == "DINMN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINMN(*)"] as $pos => $bomber)
  if ($edit == "DINMN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINMN(*)"] as $pos => $bomber)
  if ($edit == "DINMN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusGOMEL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Гомель</b><br /><br />
      город: <b>Гомель</b><br /><br />
      год основания: <b>1959</b><br /><br />
      арена: <b>Стадыён "Цэнтральны"</b><br />
      вместительность: <b>14307</b><br /><br />
      сайт: <a href="http://www.fcgomel.by" target="_blank">http://www.fcgomel.by</a>
<?php if ($edit == "GOMEL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GOMEL"] as $pos => $bomber)
  if ($edit == "GOMEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GOMEL"] as $pos => $bomber)
  if ($edit == "GOMEL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GOMEL(*)"] as $pos => $bomber)
  if ($edit == "GOMEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GOMEL(*)"] as $pos => $bomber)
  if ($edit == "GOMEL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusGRANIT.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Гранит Микашевичи</b><br /><br />
      город: <b>Мікашэвічы (Микашевичи)</b><br /><br />
      год основания: <b>1978 </b><br /><br />
      арена: <b>Стадыён "Польсье"</b><br />
      вместительность: <b>2000</b><br /><br />
      сайт: <a href="http://www.fcgranit.by/" target="_blank">http://www.fcgranit.by/</a>
<?php if ($edit == "GRANIT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GRANIT"] as $pos => $bomber)
  if ($edit == "GRANIT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANIT"] as $pos => $bomber)
  if ($edit == "GRANIT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANIT(*)"] as $pos => $bomber)
  if ($edit == "GRANIT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANIT(*)"] as $pos => $bomber)
  if ($edit == "GRANIT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusMINSK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Минск</b><br /><br />
      город: <b>Мінск (Минск)</b><br /><br />
      год основания: <b>1954 / 2006</b><br /><br />
      арена: <b>Стадыён "Дынама"</b><br />
      вместительность: <b>41000</b><br /><br />
      сайт: <a href="http://www.fcminsk.com" target="_blank">http://www.fcminsk.com</a>
<?php if ($edit == "MINSK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MINSK"] as $pos => $bomber)
  if ($edit == "MINSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MINSK"] as $pos => $bomber)
  if ($edit == "MINSK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MINSK(*)"] as $pos => $bomber)
  if ($edit == "MINSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MINSK(*)"] as $pos => $bomber)
  if ($edit == "MINSK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusNAFTAN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Нафтан Новополоцк</b><br /><br />
      город: <b>Наваполацк (Новополоцк)</b><br /><br />
      год основания: <b>1963</b><br /><br />
      арена: <b>Стадыён "Атлант"</b><br />
      вместительность: <b>4522</b><br /><br />
      сайт: <a href="http://www.fcnaftan.com" target="_blank">http://www.fcnaftan.com</a>
<?php if ($edit == "NAFTAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NAFTAN"] as $pos => $bomber)
  if ($edit == "NAFTAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAFTAN"] as $pos => $bomber)
  if ($edit == "NAFTAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAFTAN(*)"] as $pos => $bomber)
  if ($edit == "NAFTAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAFTAN(*)"] as $pos => $bomber)
  if ($edit == "NAFTAN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusNEMAN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Неман Гродно</b><br /><br />
      город: <b>Гродна (Гродно)</b><br /><br />
      год основания: <b>1964</b><br /><br />
      арена: <b>Стадыён "Нёман"</b><br />
      вместительность: <b>9000</b><br /><br />
      сайт: <a href="http://www.fcneman.by/" target="_blank">http://www.fcneman.by/</a>
<?php if ($edit == "NEMAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NEMAN"] as $pos => $bomber)
  if ($edit == "NEMAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEMAN"] as $pos => $bomber)
  if ($edit == "NEMAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEMAN(*)"] as $pos => $bomber)
  if ($edit == "NEMAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEMAN(*)"] as $pos => $bomber)
  if ($edit == "NEMAN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusSLAVIYA.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Славия Мозырь</b><br /><br />
      город: <b>Мазыр (Мозырь)</b><br /><br />
      год основания: <b>1987</b><br /><br />
      арена: <b>Стадыён "Юнацтва"</b><br />
      вместительность: <b>5371</b><br /><br />
      сайт: <a href="http://www.slaviya.by" target="_blank">http://www.slaviya.by</a>
<?php if ($edit == "SLAVIYA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SLAVIY"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIY"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIY(*)"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIY(*)"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandARSENAL.png" alt="" /></td>
    <td>
      <br />
      <b>Arsenal FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1886</b><br /><br />
      арена: <b>Emirates Stadium</b><br />
      вместительность: <b>60355</b><br /><br />
      сайт: <a href="http://www.arsenal.com" target="_blank">http://www.arsenal.com</a>
<?php if ($edit == "ARSENAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ARSENAL"] as $pos => $bomber)
  if ($edit == "ARSENAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENAL"] as $pos => $bomber)
  if ($edit == "ARSENAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENAL(*)"] as $pos => $bomber)
  if ($edit == "ARSENAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENAL(*)"] as $pos => $bomber)
  if ($edit == "ARSENAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBLACKPOOL.png" alt="" /></td>
    <td>
      <br />
      <b>Blackpool FC</b><br /><br />
      город: <b>Blackpool, Lancashire</b><br /><br />
      год основания: <b>1887</b><br /><br />
      арена: <b>Bloomfield Road</b><br />
      вместительность: <b>17600</b><br /><br />
      сайт: <a href="http://www.blackpoolfc.co.uk" target="_blank">http://www.blackpoolfc.co.uk</a>
<?php if ($edit == "BLACKPOOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BLACKPOOL"] as $pos => $bomber)
  if ($edit == "BLACKPOOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKPOOL"] as $pos => $bomber)
  if ($edit == "BLACKPOOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKPOOL(*)"] as $pos => $bomber)
  if ($edit == "BLACKPOOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKPOOL(*)"] as $pos => $bomber)
  if ($edit == "BLACKPOOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandDERBY.png" alt="" /></td>
    <td>
      <br />
      <b>Derby County FC</b><br /><br />
      город: <b>Derby</b><br /><br />
      год основания: <b>1884</b><br /><br />
      арена: <b>Pride Park Stadium</b><br />
      вместительность: <b>33597</b><br /><br />
      сайт: <a href="http://www.dcfc.co.uk" target="_blank">http://www.dcfc.co.uk</a>
<?php if ($edit == "DERBY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DERBY"] as $pos => $bomber)
  if ($edit == "DERBY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DERBY"] as $pos => $bomber)
  if ($edit == "DERBY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DERBY(*)"] as $pos => $bomber)
  if ($edit == "DERBY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DERBY(*)"] as $pos => $bomber)
  if ($edit == "DERBY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandLEICESTER.png" alt="" /></td>
    <td>
      <br />
      <b>Leicester City FC</b><br /><br />
      город: <b>Leicester, Leicestershire</b><br /><br />
      год основания: <b>1884</b><br /><br />
      арена: <b>King Power Stadium</b><br />
      вместительность: <b>32500</b><br /><br />
      сайт: <a href="http://www.lcfc.com" target="_blank">http://www.lcfc.com</a>
<?php if ($edit == "LEICESTER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LEICESTER"] as $pos => $bomber)
  if ($edit == "LEICESTER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEICESTER"] as $pos => $bomber)
  if ($edit == "LEICESTER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEICESTER(*)"] as $pos => $bomber)
  if ($edit == "LEICESTER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEICESTER(*)"] as $pos => $bomber)
  if ($edit == "LEICESTER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandNEWCASTLE.png" alt="" /></td>
    <td>
      <br />
      <b>Newcastle United FC</b><br /><br />
      город: <b>Newcastle-upon-Tyne</b><br /><br />
      год основания: <b>1892</b><br /><br />
      арена: <b>St. James' Park</b><br />
      вместительность: <b>52389</b><br /><br />
      сайт: <a href="http://www.nufc.co.uk" target="_blank">http://www.nufc.co.uk</a>
<?php if ($edit == "NEWCASTLE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NEWCASTLE"] as $pos => $bomber)
  if ($edit == "NEWCASTLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEWCASTLE"] as $pos => $bomber)
  if ($edit == "NEWCASTLE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEWCASTLE(*)"] as $pos => $bomber)
  if ($edit == "NEWCASTLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NEWCASTLE(*)"] as $pos => $bomber)
  if ($edit == "NEWCASTLE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandREADING.png" alt="" /></td>
    <td>
      <br />
      <b>Reading FC</b><br /><br />
      город: <b>Reading, Berkshire</b><br /><br />
      год основания: <b>1871</b><br /><br />
      арена: <b>Madejski Stadium</b><br />
      вместительность: <b>24200</b><br /><br />
      сайт: <a href="http://www.readingfc.co.uk" target="_blank">http://www.readingfc.co.uk</a>
<?php if ($edit == "READING") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["READING"] as $pos => $bomber)
  if ($edit == "READING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["READING"] as $pos => $bomber)
  if ($edit == "READING")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["READING(*)"] as $pos => $bomber)
  if ($edit == "READING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["READING(*)"] as $pos => $bomber)
  if ($edit == "READING")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandSTOKECITY.png" alt="" /></td>
    <td>
      <br />
      <b>Stoke City FC</b><br /><br />
      город: <b>Stoke-on-Trent, Staffordshire</b><br /><br />
      год основания: <b>1868</b><br /><br />
      арена: <b>Britannia Stadium</b><br />
      вместительность: <b>28383</b><br /><br />
      сайт: <a href="http://www.stokecityfc.com" target="_blank">http://www.stokecityfc.com</a>
<?php if ($edit == "STOKECITY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STOKECITY"] as $pos => $bomber)
  if ($edit == "STOKECITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STOKECITY"] as $pos => $bomber)
  if ($edit == "STOKECITY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STOKECITY(*)"] as $pos => $bomber)
  if ($edit == "STOKECITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STOKECITY(*)"] as $pos => $bomber)
  if ($edit == "STOKECITY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandWESTHAM.png" alt="" /></td>
    <td>
      <br />
      <b>West Ham United FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1895</b><br /><br />
      арена: <b>Boleyn Ground</b><br />
      вместительность: <b>35303</b><br /><br />
      сайт: <a href="http://www.whufc.com" target="_blank">http://www.whufc.com</a>
<?php if ($edit == "WESTHAM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WESTHAM"] as $pos => $bomber)
  if ($edit == "WESTHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTHAM"] as $pos => $bomber)
  if ($edit == "WESTHAM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTHAM(*)"] as $pos => $bomber)
  if ($edit == "WESTHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTHAM(*)"] as $pos => $bomber)
  if ($edit == "WESTHAM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandWOLVER.png" alt="" /></td>
    <td>
      <br />
      <b>Wolverhampton Wanderers FC</b><br /><br />
      город: <b>Wolverhampton, West Midlands</b><br /><br />
      год основания: <b>1877</b><br /><br />
      арена: <b>Molineux Stadium</b><br />
      вместительность: <b>29195</b><br /><br />
      сайт: <a href="http://www.wolves.co.uk" target="_blank">http://www.wolves.co.uk</a>
<?php if ($edit == "WOLVER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WOLVER"] as $pos => $bomber)
  if ($edit == "WOLVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLVER"] as $pos => $bomber)
  if ($edit == "WOLVER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLVER(*)"] as $pos => $bomber)
  if ($edit == "WOLVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLVER(*)"] as $pos => $bomber)
  if ($edit == "WOLVER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceCANNES.png" alt="" /></td>
    <td>
      <br />
      <b>Association Sportive de Cannes</b><br /><br />
      город: <b>Cannes</b><br /><br />
      год основания: <b>1902</b><br /><br />
      арена: <b>Stade Pierre de Coubertin</b><br />
      вместительность: <b>11211</b><br /><br />
      сайт: <a href="http://www.ascannes.info" target="_blank">http://www.ascannes.info</a>
<?php if ($edit == "CANNES") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CANNES"] as $pos => $bomber)
  if ($edit == "CANNES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CANNES"] as $pos => $bomber)
  if ($edit == "CANNES")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CANNES(*)"] as $pos => $bomber)
  if ($edit == "CANNES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CANNES(*)"] as $pos => $bomber)
  if ($edit == "CANNES")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceLAVAL.png" alt="" /></td>
    <td>
      <br />
      <b>Stade Lavallois Mayenne FC</b><br /><br />
      город: <b>Laval</b><br /><br />
      год основания: <b>1902</b><br /><br />
      арена: <b>Stade Francis Le Basser</b><br />
      вместительность: <b>18467</b><br /><br />
      сайт: <a href="http://www.stade-lavallois.com" target="_blank">http://www.stade-lavallois.com</a>
<?php if ($edit == "LAVAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LAVAL"] as $pos => $bomber)
  if ($edit == "LAVAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAVAL"] as $pos => $bomber)
  if ($edit == "LAVAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAVAL(*)"] as $pos => $bomber)
  if ($edit == "LAVAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAVAL(*)"] as $pos => $bomber)
  if ($edit == "LAVAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceNANT.png" alt="" /></td>
    <td>
      <br />
      <b>FC Nantes</b><br /><br />
      город: <b>Nantes</b><br /><br />
      год основания: <b>1943</b><br /><br />
      арена: <b>Stade de la Beaujoire - Louis Fonteneau</b><br />
      вместительность: <b>38285</b><br /><br />
      сайт: <a href="http://www.fcnantes.com" target="_blank">http://www.fcnantes.com</a>
<?php if ($edit == "NANT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NANT"] as $pos => $bomber)
  if ($edit == "NANT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANT"] as $pos => $bomber)
  if ($edit == "NANT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANT(*)"] as $pos => $bomber)
  if ($edit == "NANT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANT(*)"] as $pos => $bomber)
  if ($edit == "NANT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceLYON.png" alt="" /></td>
    <td>
      <br />
      <b>Olympique Lyonnais</b><br /><br />
      город: <b>Lyon</b><br /><br />
      год основания: <b>1950</b><br /><br />
      арена: <b>Stade de Gerland</b><br />
      вместительность: <b>41842</b><br /><br />
      сайт: <a href="http://www.olweb.fr" target="_blank">http://www.olweb.fr</a>
<?php if ($edit == "LYON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LYON"] as $pos => $bomber)
  if ($edit == "LYON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LYON"] as $pos => $bomber)
  if ($edit == "LYON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LYON(*)"] as $pos => $bomber)
  if ($edit == "LYON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LYON(*)"] as $pos => $bomber)
  if ($edit == "LYON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceMARSELLE.png" alt="" /></td>
    <td>
      <br />
      <b>Olympique de Marseille</b><br /><br />
      город: <b>Marseille</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Stade Vélodrome</b><br />
      вместительность: <b>60031</b><br /><br />
      сайт: <a href="http://www.om.net" target="_blank">http://www.om.net</a>
<?php if ($edit == "MARSELLE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MARSELLE"] as $pos => $bomber)
  if ($edit == "MARSELLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARSELLE"] as $pos => $bomber)
  if ($edit == "MARSELLE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARSELLE(*)"] as $pos => $bomber)
  if ($edit == "MARSELLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARSELLE(*)"] as $pos => $bomber)
  if ($edit == "MARSELLE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germany1860.png" alt="" /></td>
    <td>
      <br />
      <b>TSV 1860 München</b><br /><br />
      город: <b>München</b><br /><br />
      год основания: <b>1860</b><br /><br />
      арена: <b>Allianz-Arena</b><br />
      вместительность: <b>71901</b><br /><br />
      сайт: <a href="http://www.tsv1860.de" target="_blank">http://www.tsv1860.de</a>
<?php if ($edit == "1860") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["1860"] as $pos => $bomber)
  if ($edit == "1860")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["1860"] as $pos => $bomber)
  if ($edit == "1860")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["1860(*)"] as $pos => $bomber)
  if ($edit == "1860")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["1860(*)"] as $pos => $bomber)
  if ($edit == "1860")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyALEMANNIA.png" alt="" /></td>
    <td>
      <br />
      <b>TSV Alemannia Aachen</b><br /><br />
      город: <b>Aachen</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Neuer Tivoli</b><br />
      вместительность: <b>32960</b><br /><br />
      сайт: <a href="http://www.alemannia-aachen.de" target="_blank">http://www.alemannia-aachen.de</a>
<?php if ($edit == "ALEMANIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ALEMANIA"] as $pos => $bomber)
  if ($edit == "ALEMANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALEMANIA"] as $pos => $bomber)
  if ($edit == "ALEMANIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALEMANIA(*)"] as $pos => $bomber)
  if ($edit == "ALEMANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALEMANIA(*)"] as $pos => $bomber)
  if ($edit == "ALEMANIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyBAYER.png" alt="" /></td>
    <td>
      <br />
      <b>TSV Bayer 04 Leverkusen</b><br /><br />
      город: <b>Leverkusen</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>BayArena</b><br />
      вместительность: <b>30210</b><br /><br />
      сайт: <a href="http://www.bayer04.de" target="_blank">http://www.bayer04.de</a>
<?php if ($edit == "BAYER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BAYER"] as $pos => $bomber)
  if ($edit == "BAYER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYER"] as $pos => $bomber)
  if ($edit == "BAYER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYER(*)"] as $pos => $bomber)
  if ($edit == "BAYER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYER(*)"] as $pos => $bomber)
  if ($edit == "BAYER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyBOR-D.png" alt="" /></td>
    <td>
      <br />
      <b>BV Borussia 09 Dortmund</b><br /><br />
      город: <b>Dortmund</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Signal-Iduna-Park</b><br />
      вместительность: <b>80720</b><br /><br />
      сайт: <a href="http://www.bvb.de" target="_blank">http://www.bvb.de</a>
<?php if ($edit == "BOR-D") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOR-D"] as $pos => $bomber)
  if ($edit == "BOR-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-D"] as $pos => $bomber)
  if ($edit == "BOR-D")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-D(*)"] as $pos => $bomber)
  if ($edit == "BOR-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-D(*)"] as $pos => $bomber)
  if ($edit == "BOR-D")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyWERDER.png" alt="" /></td>
    <td>
      <br />
      <b>SV Werder Bremen</b><br /><br />
      город: <b>Bremen</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Weserstadion</b><br />
      вместительность: <b>42358</b><br /><br />
      сайт: <a href="http://www.werder.de" target="_blank">http://www.werder.de</a>
<?php if ($edit == "WERDER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WERDER"] as $pos => $bomber)
  if ($edit == "WERDER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WERDER"] as $pos => $bomber)
  if ($edit == "WERDER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WERDER(*)"] as $pos => $bomber)
  if ($edit == "WERDER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WERDER(*)"] as $pos => $bomber)
  if ($edit == "WERDER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyWOLFSBURG.png" alt="" /></td>
    <td>
      <br />
      <b>VfL Wolfsburg</b><br /><br />
      город: <b>Wolfsburg</b><br /><br />
      год основания: <b>1945</b><br /><br />
      арена: <b>VOLKSWAGEN ARENA</b><br />
      вместительность: <b>30000</b><br /><br />
      сайт: <a href="http://www.vfl-wolfsburg.de" target="_blank">http://www.vfl-wolfsburg.de</a>
<?php if ($edit == "WOLFSBURG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WOLFSBURG"] as $pos => $bomber)
  if ($edit == "WOLFSBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLFSBURG"] as $pos => $bomber)
  if ($edit == "WOLFSBURG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLFSBURG(*)"] as $pos => $bomber)
  if ($edit == "WOLFSBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WOLFSBURG(*)"] as $pos => $bomber)
  if ($edit == "WOLFSBURG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyEMPOLI.png" alt="" /></td>
    <td>
      <br />
      <b>Empoli FC</b><br /><br />
      город: <b>Empoli</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Stadio Carlo Castellani</b><br />
      вместительность: <b>19847</b><br /><br />
      сайт: <a href="http://www.empolicalcio.it" target="_blank">http://www.empolicalcio.it</a>
<?php if ($edit == "EMPOLI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["EMPOLI"] as $pos => $bomber)
  if ($edit == "EMPOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EMPOLI"] as $pos => $bomber)
  if ($edit == "EMPOLI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EMPOLI(*)"] as $pos => $bomber)
  if ($edit == "EMPOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EMPOLI(*)"] as $pos => $bomber)
  if ($edit == "EMPOLI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyGENOA.png" alt="" /></td>
    <td>
      <br />
      <b>Genoa CFC</b><br /><br />
      город: <b>Genova</b><br /><br />
      год основания: <b>1893</b><br /><br />
      арена: <b>Stadio Comunale Luigi Ferraris</b><br />
      вместительность: <b>36703</b><br /><br />
      сайт: <a href="http://www.genoacfc.it" target="_blank">http://www.genoacfc.it</a>
<?php if ($edit == "GENOA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GENOA"] as $pos => $bomber)
  if ($edit == "GENOA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENOA"] as $pos => $bomber)
  if ($edit == "GENOA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENOA(*)"] as $pos => $bomber)
  if ($edit == "GENOA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENOA(*)"] as $pos => $bomber)
  if ($edit == "GENOA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyINTER.png" alt="" /></td>
    <td>
      <br />
      <b>FC Internazionale Milano</b><br /><br />
      город: <b>Milano</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>Stadio Giuseppe Meazza</b><br />
      вместительность: <b>82995</b><br /><br />
      сайт: <a href="http://www.inter.it" target="_blank">http://www.inter.it</a>
<?php if ($edit == "INTER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["INTER"] as $pos => $bomber)
  if ($edit == "INTER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INTER"] as $pos => $bomber)
  if ($edit == "INTER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INTER(*)"] as $pos => $bomber)
  if ($edit == "INTER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INTER(*)"] as $pos => $bomber)
  if ($edit == "INTER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyLAZIO.png" alt="" /></td>
    <td>
      <br />
      <b>SS Lazio</b><br /><br />
      город: <b>Roma</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Stadio Olimpico</b><br />
      вместительность: <b>82656</b><br /><br />
      сайт: <a href="http://www.sslazio.it" target="_blank">http://www.sslazio.it</a>
<?php if ($edit == "LAZIO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LAZIO"] as $pos => $bomber)
  if ($edit == "LAZIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAZIO"] as $pos => $bomber)
  if ($edit == "LAZIO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAZIO(*)"] as $pos => $bomber)
  if ($edit == "LAZIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LAZIO(*)"] as $pos => $bomber)
  if ($edit == "LAZIO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyMODENA.png" alt="" /></td>
    <td>
      <br />
      <b>Modena FC</b><br /><br />
      город: <b>Modena</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Stadio Alberto Braglia</b><br />
      вместительность: <b>20507</b><br /><br />
      сайт: <a href="http://www.modenafc.net" target="_blank">http://www.modenafc.net</a>
<?php if ($edit == "MODENA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MODENA"] as $pos => $bomber)
  if ($edit == "MODENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MODENA"] as $pos => $bomber)
  if ($edit == "MODENA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MODENA(*)"] as $pos => $bomber)
  if ($edit == "MODENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MODENA(*)"] as $pos => $bomber)
  if ($edit == "MODENA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyNAPOLI.png" alt="" /></td>
    <td>
      <br />
      <b>SSC Napoli</b><br /><br />
      город: <b>Napoli</b><br /><br />
      год основания: <b>1904 / 1926 / 2004</b><br /><br />
      арена: <b>Stadio San Paolo</b><br />
      вместительность: <b>76824</b><br /><br />
      сайт: <a href="http://www.sscnapoli.it" target="_blank">http://www.sscnapoli.it</a>
<?php if ($edit == "NAPOLI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NAPOLI"] as $pos => $bomber)
  if ($edit == "NAPOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAPOLI"] as $pos => $bomber)
  if ($edit == "NAPOLI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAPOLI(*)"] as $pos => $bomber)
  if ($edit == "NAPOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NAPOLI(*)"] as $pos => $bomber)
  if ($edit == "NAPOLI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyPALERMO.png" alt="" /></td>
    <td>
      <br />
      <b>US Città di Palermo</b><br /><br />
      город: <b>Palermo</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Stadio Renzo Barbera</b><br />
      вместительность: <b>37242</b><br /><br />
      сайт: <a href="http://www.ilpalermocalcio.it" target="_blank">http:http://www.ilpalermocalcio.it</a>
<?php if ($edit == "PALERMO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PALERMO"] as $pos => $bomber)
  if ($edit == "PALERMO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PALERMO"] as $pos => $bomber)
  if ($edit == "PALERMO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PALERMO(*)"] as $pos => $bomber)
  if ($edit == "PALERMO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PALERMO(*)"] as $pos => $bomber)
  if ($edit == "PALERMO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyROMA.png" alt="" /></td>
    <td>
      <br />
      <b>AS Roma</b><br /><br />
      город: <b>Roma</b><br /><br />
      год основания: <b>1927</b><br /><br />
      арена: <b>Stadio Olimpico</b><br />
      вместительность: <b>82656</b><br /><br />
      сайт: <a href="http://www.asroma.it" target="_blank">http://www.asroma.it</a>
<?php if ($edit == "ROMA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ROMA"] as $pos => $bomber)
  if ($edit == "ROMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROMA"] as $pos => $bomber)
  if ($edit == "ROMA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROMA(*)"] as $pos => $bomber)
  if ($edit == "ROMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROMA(*)"] as $pos => $bomber)
  if ($edit == "ROMA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italySAMPDOR.png" alt="" /></td>
    <td>
      <br />
      <b>UC Sampdoria</b><br /><br />
      город: <b>Genova</b><br /><br />
      год основания: <b>1946</b><br /><br />
      арена: <b>Stadio Comunale Luigi Ferraris</b><br />
      вместительность: <b>36703</b><br /><br />
      сайт: <a href="http://www.sampdoria.it" target="_blank">http://www.sampdoria.it</a>
<?php if ($edit == "SAMPDORIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SAMPDORIA"] as $pos => $bomber)
  if ($edit == "SAMPDORIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SAMPDORIA"] as $pos => $bomber)
  if ($edit == "SAMPDORIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SAMPDORIA(*)"] as $pos => $bomber)
  if ($edit == "SAMPDORIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SAMPDORIA(*)"] as $pos => $bomber)
  if ($edit == "SAMPDORIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyVENEZIA.png" alt="" /></td>
    <td>
      <br />
      <b>FBC Unione Venezia</b><br /><br />
      город: <b>Mestre</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Stadio Pier Giovanni Mecchia</b><br />
      вместительность: <b>3335</b><br /><br />
      сайт: <a href="http://www.fbcunionevenezia.com/" target="_blank">http://www.fbcunionevenezia.com/</a>
<?php if ($edit == "VENEZIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VENEZIA"] as $pos => $bomber)
  if ($edit == "VENEZIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VENEZIA"] as $pos => $bomber)
  if ($edit == "VENEZIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VENEZIA(*)"] as $pos => $bomber)
  if ($edit == "VENEZIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VENEZIA(*)"] as $pos => $bomber)
  if ($edit == "VENEZIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsAJAX.png" alt="" /></td>
    <td>
      <br />
      <b>AFC Ajax</b><br /><br />
      город: <b>Amsterdam</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Amsterdam ArenA</b><br />
      вместительность: <b>53052</b><br /><br />
      сайт: <a href="http://www.ajax.nl" target="_blank">http://www.ajax.nl</a>
<?php if ($edit == "AJAX") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AJAX"] as $pos => $bomber)
  if ($edit == "AJAX")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AJAX"] as $pos => $bomber)
  if ($edit == "AJAX")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AJAX(*)"] as $pos => $bomber)
  if ($edit == "AJAX")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AJAX(*)"] as $pos => $bomber)
  if ($edit == "AJAX")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsAZALK.png" alt="" /></td>
    <td>
      <br />
      <b>Alkmaar Zaanstreek</b><br /><br />
      город: <b>Alkmaar</b><br /><br />
      год основания: <b>1967</b><br /><br />
      арена: <b>AFAS Stadion</b><br />
      вместительность: <b>17023</b><br /><br />
      сайт: <a href="http://www.az.nl" target="_blank">http://www.az.nl</a>
<?php if ($edit == "AZALK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AZALK"] as $pos => $bomber)
  if ($edit == "AZALK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AZALK"] as $pos => $bomber)
  if ($edit == "AZALK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AZALK(*)"] as $pos => $bomber)
  if ($edit == "AZALK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AZALK(*)"] as $pos => $bomber)
  if ($edit == "AZALK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsEINDH.png" alt="" /></td>
    <td>
      <br />
      <b>FC Eindhoven</b><br /><br />
      город: <b>Eindhoven</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Jan Louwers Stadion</b><br />
      вместительность: <b>5400</b><br /><br />
      сайт: <a href="http://www.fc-eindhoven.nl" target="_blank">http://www.fc-eindhoven.nl</a>
<?php if ($edit == "EINDH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["EINDH"] as $pos => $bomber)
  if ($edit == "EINDH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINDH"] as $pos => $bomber)
  if ($edit == "EINDH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINDH(*)"] as $pos => $bomber)
  if ($edit == "EINDH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINDH(*)"] as $pos => $bomber)
  if ($edit == "EINDH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsFEYEN.png" alt="" /></td>
    <td>
      <br />
      <b>Feyenoord Rotterdam</b><br /><br />
      город: <b>Rotterdam</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>Stadion Feijenoord</b><br />
      вместительность: <b>51777</b><br /><br />
      сайт: <a href="http://www.feyenoord.nl" target="_blank">http://www.feyenoord.nl</a>
<?php if ($edit == "FEYEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FEYEN"] as $pos => $bomber)
  if ($edit == "FEYEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FEYEN"] as $pos => $bomber)
  if ($edit == "FEYEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FEYEN(*)"] as $pos => $bomber)
  if ($edit == "FEYEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FEYEN(*)"] as $pos => $bomber)
  if ($edit == "FEYEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsBREDA.png" alt="" /></td>
    <td>
      <br />
      <b>NAC Breda</b><br /><br />
      город: <b>Breda</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Rat Verlegh Stadion</b><br />
      вместительность: <b>19000</b><br /><br />
      сайт: <a href="http://www.nac.nl/" target="_blank">http://www.nac.nl/</a>
<?php if ($edit == "BREDA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BREDA"] as $pos => $bomber)
  if ($edit == "BREDA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BREDA"] as $pos => $bomber)
  if ($edit == "BREDA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BREDA(*)"] as $pos => $bomber)
  if ($edit == "BREDA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BREDA(*)"] as $pos => $bomber)
  if ($edit == "BREDA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsUTRECT.png" alt="" /></td>
    <td>
      <br />
      <b>FC Utrecht</b><br /><br />
      город: <b>Utrecht</b><br /><br />
      год основания: <b>1970</b><br /><br />
      арена: <b>Stadion Galgenwaard</b><br />
      вместительность: <b>24426</b><br /><br />
      сайт: <a href="http://www.fcutrecht.nl" target="_blank">http://www.fcutrecht.nl</a>
<?php if ($edit == "UTRECT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["UTRECT"] as $pos => $bomber)
  if ($edit == "UTRECT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UTRECT"] as $pos => $bomber)
  if ($edit == "UTRECT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UTRECT(*)"] as $pos => $bomber)
  if ($edit == "UTRECT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UTRECT(*)"] as $pos => $bomber)
  if ($edit == "UTRECT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsVITESSE.png" alt="" /></td>
    <td>
      <br />
      <b>SBV Vitesse</b><br /><br />
      город: <b>Arnhem</b><br /><br />
      год основания: <b>1892</b><br /><br />
      арена: <b>GelreDome</b><br />
      вместительность: <b>28278</b><br /><br />
      сайт: <a href="http://www.vitesse.nl/" target="_blank">http://www.vitesse.nl/</a>
<?php if ($edit == "VITESSE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VITESSE"] as $pos => $bomber)
  if ($edit == "VITESSE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITESSE"] as $pos => $bomber)
  if ($edit == "VITESSE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITESSE(*)"] as $pos => $bomber)
  if ($edit == "VITESSE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITESSE(*)"] as $pos => $bomber)
  if ($edit == "VITESSE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBOAVI.png" alt="" /></td>
    <td>
      <br />
      <b>Boavista FC</b><br /><br />
      город: <b>Porto</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Estádio do Bessa Século XXI</b><br />
      вместительность: <b>28263</b><br /><br />
      сайт: <a href="http://www.boavistafc.pt" target="_blank">http://www.boavistafc.pt</a>
<?php if ($edit == "BOAVI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOAVI"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI(*)"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI(*)"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalESTOR.png" alt="" /></td>
    <td>
      <br />
      <b>GD Estoril Praia</b><br /><br />
      город: <b>Estoril</b><br /><br />
      год основания: <b>1939</b><br /><br />
      арена: <b>Estádio António Coimbra da Mota</b><br />
      вместительность: <b>5500</b><br /><br />
      сайт: <a href="http://www.estorilpraia.pt/" target="_blank">http://www.estorilpraia.pt/</a>
<?php if ($edit == "ESTOR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ESTOR"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR(*)"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR(*)"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalIMORT.png" height="150" alt="" /></td>
    <td>
      <br />
      <b>Imortal DC Albufeira</b><br /><br />
      город: <b>Albufeira
      </b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Municipal de Albufeira</b><br />
      вместительность: <b>3500</b><br /><br />
      сайт: <a href="http://www.imortaldc.com" target="_blank">http://www.imortaldc.com</a>
<?php if ($edit == "IMORT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["IMORT"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT(*)"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT(*)"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalNACIO.png" alt="" /></td>
    <td>
      <br />
      <b>CD Nacional Funchal</b><br /><br />
      город: <b>Funchal (Ilha da Madeira)</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Estádio da Madeira</b><br />
      вместительность: <b>8589</b><br /><br />
      сайт: <a href="http://www.cdnacional.pt" target="_blank">http://www.cdnacional.pt</a>
<?php if ($edit == "NACIO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NACIO"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO(*)"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO(*)"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBRAGA.png" alt="" /></td>
    <td>
      <br />
      <b>Sporting Braga</b><br /><br />
      город: <b>Braga</b><br /><br />
      год основания: <b>1921</b><br /><br />
      арена: <b>Estádio AXA</b><br />
      вместительность: <b>30286</b><br /><br />
      сайт: <a href="http://www.scbraga.pt" target="_blank">http://www.scbraga.pt</a>
<?php if ($edit == "BRAGA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRAGA"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA(*)"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA(*)"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalSETUB.png" alt="" /></td>
    <td>
      <br />
      <b>Vitória Setúbal FC</b><br /><br />
      город: <b>Setúbal</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Estádio do Bonfim</b><br />
      вместительность: <b>18694</b><br /><br />
      сайт: <a href="http://www.vfc.pt" target="_blank">http://www.vfc.pt</a>
<?php if ($edit == "SETUB") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SETUB"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB(*)"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB(*)"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaAMKAR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Амкар Пермь</b><br /><br />
      город: <b>Пермь</b><br /><br />
      год основания: <b>1994</b><br /><br />
      арена: <b>Стадион "Звезда"</b><br />
      вместительность: <b>20000</b><br /><br />
      сайт: <a href="http://fc-amkar.org" target="_blank">http://fc-amkar.org</a>
<?php if ($edit == "AMKAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AMKAR"] as $pos => $bomber)
  if ($edit == "AMKAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AMKAR"] as $pos => $bomber)
  if ($edit == "AMKAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AMKAR(*)"] as $pos => $bomber)
  if ($edit == "AMKAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AMKAR(*)"] as $pos => $bomber)
  if ($edit == "AMKAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaDIN-ST.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Ставрополь</b><br /><br />
      город: <b>Ставрополь</b><br /><br />
      год основания: <b>1924</b><br /><br />
      арена: <b>Стадион "Динамо"</b><br />
      вместительность: <b>15982</b><br /><br />
      сайт: <a href="http://www.dynamost.ru" target="_blank">http://www.dynamost.ru</a>
<?php if ($edit == "DIN-ST") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-ST"] as $pos => $bomber)
  if ($edit == "DIN-ST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-ST"] as $pos => $bomber)
  if ($edit == "DIN-ST")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-ST(*)"] as $pos => $bomber)
  if ($edit == "DIN-ST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-ST(*)"] as $pos => $bomber)
  if ($edit == "DIN-ST")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaENISEY.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Енисей Красноярск</b><br /><br />
      город: <b>Красноярск</b><br /><br />
      год основания: <b>1937</b><br /><br />
      арена: <b>Центральный стадион</b><br />
      вместительность: <b>32500</b><br /><br />
      сайт: <a href="http://www.fc-enisey.ru/" target="_blank">http://www.fc-enisey.ru/</a>
<?php if ($edit == "ENISEY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ENISEY"] as $pos => $bomber)
  if ($edit == "ENISEY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ENISEY"] as $pos => $bomber)
  if ($edit == "ENISEY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ENISEY(*)"] as $pos => $bomber)
  if ($edit == "ENISEY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ENISEY(*)"] as $pos => $bomber)
  if ($edit == "ENISEY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaZENIT-SP.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Зенит Санкт-Петербург</b><br /><br />
      город: <b>Санкт-Петербург</b><br /><br />
      год основания: <b>1925</b><br /><br />
      арена: <b>Стадион Петровский</b><br />
      вместительность: <b>21838</b><br /><br />
      сайт: <a href="http://www.fc-zenit.ru" target="_blank">http://www.fc-zenit.ru</a>
<?php if ($edit == "ZENIT-SP") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-SP"] as $pos => $bomber)
  if ($edit == "ZENIT-SP")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-SP"] as $pos => $bomber)
  if ($edit == "ZENIT-SP")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-SP(*)"] as $pos => $bomber)
  if ($edit == "ZENIT-SP")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-SP(*)"] as $pos => $bomber)
  if ($edit == "ZENIT-SP")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><img src="images/spacer.gif" height="50" alt="" /><br /><img src="images/russiaZENIT-2.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Зенит Санкт-Петербург II</b><br /><br />
      город: <b>Санкт-Петербург</b><br /><br />
      год основания: <b></b>2001<br /><br />
      арена: <b>Малая спортивная арена</b><br />
      вместительность: <b>2835</b><br /><br />
      сайт: <a href="http://2.fc-zenit.ru" target="_blank">http://2.fc-zenit.ru</a>
<?php if ($edit == "ZENIT-2") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-2"] as $pos => $bomber)
  if ($edit == "ZENIT-2")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-2"] as $pos => $bomber)
  if ($edit == "ZENIT-2")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-2(*)"] as $pos => $bomber)
  if ($edit == "ZENIT-2")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZENIT-2(*)"] as $pos => $bomber)
  if ($edit == "ZENIT-2")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaKRSOV.png" alt="" /></td>
    <td>
      <br />
      <b>ПФК Крылья Советов Самара</b><br /><br />
      город: <b>Самара</b><br /><br />
      год основания: <b>1942</b><br /><br />
      арена: <b>Стадион "Металлург"</b><br />
      вместительность: <b>33001</b><br /><br />
      сайт: <a href="http://www.kc-camapa.ru" target="_blank">http://www.kc-camapa.ru</a>
<?php if ($edit == "KRSOV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KRSOV"] as $pos => $bomber)
  if ($edit == "KRSOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRSOV"] as $pos => $bomber)
  if ($edit == "KRSOV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRSOV(*)"] as $pos => $bomber)
  if ($edit == "KRSOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRSOV(*)"] as $pos => $bomber)
  if ($edit == "KRSOV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaMOSCOW.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1997</b><br /><br />
      арена: <b>Стадион "Торпедо" им. Эдуарда Стрельцова</b><br />
      вместительность: <b>14274</b><br /><br />
      сайт: <a href="http://www.fcmoscow.ru" target="_blank">http://www.fcmoscow.ru</a>
<?php if ($edit == "MOSCOW") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MOSCOW"] as $pos => $bomber)
  if ($edit == "MOSCOW")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOSCOW"] as $pos => $bomber)
  if ($edit == "MOSCOW")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOSCOW(*)"] as $pos => $bomber)
  if ($edit == "MOSCOW")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOSCOW(*)"] as $pos => $bomber)
  if ($edit == "MOSCOW")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaROTOR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ротор Волгоград</b><br /><br />
      город: <b>Волгоград</b><br /><br />
      год основания: <b>1929</b><br /><br />
      арена: <b>Центральный стадион "Ротор"</b><br />
      вместительность: <b>32120</b><br /><br />
      сайт: <a href="http://www.rotor-fc.com" target="_blank">http://www.rotor-fc.com</a>
<?php if ($edit == "ROTOR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ROTOR"] as $pos => $bomber)
  if ($edit == "ROTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROTOR"] as $pos => $bomber)
  if ($edit == "ROTOR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROTOR(*)"] as $pos => $bomber)
  if ($edit == "ROTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROTOR(*)"] as $pos => $bomber)
  if ($edit == "ROTOR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaTOR-V.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Торпедо Владимир</b><br /><br />
      город: <b>Владимир</b><br /><br />
      год основания: <b>1959</b><br /><br />
      арена: <b>Стадион "Торпедо"</b><br />
      вместительность: <b>19700</b><br /><br />
      сайт: <a href="http://www.torpedo33.com" target="_blank">http://www.torpedo33.com</a>
<?php if ($edit == "TOR-V") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOR-V"] as $pos => $bomber)
  if ($edit == "TOR-V")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-V"] as $pos => $bomber)
  if ($edit == "TOR-V")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-V(*)"] as $pos => $bomber)
  if ($edit == "TOR-V")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-V(*)"] as $pos => $bomber)
  if ($edit == "TOR-V")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandDUNDEE.png" alt="" /></td>
    <td>
      <br />
      <b>Dundee FC</b><br /><br />
      город: <b>Dundee</b><br /><br />
      год основания: <b>1893</b><br /><br />
      арена: <b>Dens Park</b><br />
      вместительность: <b>12085</b><br /><br />
      сайт: <a href="http://www.thedees.co.uk" target="_blank">http://www.thedees.co.uk</a>
<?php if ($edit == "DUNDEE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DUNDEE"] as $pos => $bomber)
  if ($edit == "DUNDEE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUNDEE"] as $pos => $bomber)
  if ($edit == "DUNDEE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUNDEE(*)"] as $pos => $bomber)
  if ($edit == "DUNDEE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUNDEE(*)"] as $pos => $bomber)
  if ($edit == "DUNDEE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandDUN_UTD.png" alt="" /></td>
    <td>
      <br />
      <b>Dundee United FC</b><br /><br />
      город: <b>Dundee</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Tannadice Park</b><br />
      вместительность: <b>14209</b><br /><br />
      сайт: <a href="http://www.dundeeunitedfc.co.uk" target="_blank">http://www.dundeeunitedfc.co.uk</a>
<?php if ($edit == "DUN_UTD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DUN_UTD"] as $pos => $bomber)
  if ($edit == "DUN_UTD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUN_UTD"] as $pos => $bomber)
  if ($edit == "DUN_UTD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUN_UTD(*)"] as $pos => $bomber)
  if ($edit == "DUN_UTD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUN_UTD(*)"] as $pos => $bomber)
  if ($edit == "DUN_UTD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandFALKIRK.png" alt="" /></td>
    <td>
      <br />
      <b>Falkirk FC</b><br /><br />
      город: <b>Falkirk</b><br /><br />
      год основания: <b>1876</b><br /><br />
      арена: <b>Falkirk Community Stadium</b><br />
      вместительность: <b>9008</b><br /><br />
      сайт: <a href="http://www.falkirkfc.co.uk" target="_blank">http://www.falkirkfc.co.uk/</a>
<?php if ($edit == "FALKIRK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FALKIRK"] as $pos => $bomber)
  if ($edit == "FALKIRK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FALKIRK"] as $pos => $bomber)
  if ($edit == "FALKIRK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FALKIRK(*)"] as $pos => $bomber)
  if ($edit == "FALKIRK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FALKIRK(*)"] as $pos => $bomber)
  if ($edit == "FALKIRK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandINVER.png" alt="" /></td>
    <td>
      <br />
      <b>Inverness Caledonian Thistle FC</b><br /><br />
      город: <b>Inverness</b><br /><br />
      год основания: <b>1994</b><br /><br />
      арена: <b>Tulloch Caledonian Stadium</b><br />
      вместительность: <b>7918</b><br /><br />
      сайт: <a href="http://www.caleythistleonline.com" target="_blank">http://www.caleythistleonline.com</a>
<?php if ($edit == "INVER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["INVER"] as $pos => $bomber)
  if ($edit == "INVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INVER"] as $pos => $bomber)
  if ($edit == "INVER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INVER(*)"] as $pos => $bomber)
  if ($edit == "INVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INVER(*)"] as $pos => $bomber)
  if ($edit == "INVER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandSTRAN.png" alt="" /></td>
    <td>
      <br />
      <b>Stranraer FC</b><br /><br />
      город: <b>Stranraer</b><br /><br />
      год основания: <b>1870</b><br /><br />
      арена: <b>Stair Park</b><br />
      вместительность: <b>6250</b><br /><br />
      сайт: <a href="http://www.stranraerfc.org" target="_blank">http://www.stranraerfc.org</a>
<?php if ($edit == "STRAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STRAN"] as $pos => $bomber)
  if ($edit == "STRAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STRAN"] as $pos => $bomber)
  if ($edit == "STRAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STRAN(*)"] as $pos => $bomber)
  if ($edit == "STRAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STRAN(*)"] as $pos => $bomber)
  if ($edit == "STRAN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainBARCELONA.png" alt="" /></td>
    <td>
      <br />
      <b>FC Barcelona</b><br /><br />
      город: <b>Barcelona</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Camp Nou</b><br />
      вместительность: <b>99787</b><br /><br />
      сайт: <a href="http://www.fcbarcelona.com" target="_blank">http://www.fcbarcelona.com</a>
<?php if ($edit == "BARCELONA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BARCELONA"] as $pos => $bomber)
  if ($edit == "BARCELONA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARCELONA"] as $pos => $bomber)
  if ($edit == "BARCELONA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARCELONA(*)"] as $pos => $bomber)
  if ($edit == "BARCELONA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARCELONA(*)"] as $pos => $bomber)
  if ($edit == "BARCELONA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainDEPOR.png" alt="" /></td>
    <td>
      <br />
      <b>Real Club Deportivo de La Coruña</b><br /><br />
      город: <b>A Coruña (La Coruña)</b><br /><br />
      год основания: <b>1906</b><br /><br />
      арена: <b>Estadio Municipal de Riazor</b><br />
      вместительность: <b>34600</b><br /><br />
      сайт: <a href="http://www.canaldeportivo.com" target="_blank">http://www.canaldeportivo.com</a>
<?php if ($edit == "DEPOR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DEPOR"] as $pos => $bomber)
  if ($edit == "DEPOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DEPOR"] as $pos => $bomber)
  if ($edit == "DEPOR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DEPOR(*)"] as $pos => $bomber)
  if ($edit == "DEPOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DEPOR(*)"] as $pos => $bomber)
  if ($edit == "DEPOR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainMALAGA.png" alt="" /></td>
    <td>
      <br />
      <b>Málaga Club de Fútbol</b><br /><br />
      город: <b>Málaga</b><br /><br />
      год основания: <b>1948</b><br /><br />
      арена: <b>Estadio La Rosaleda</b><br />
      вместительность: <b>29500</b><br /><br />
      сайт: <a href="http://www.malagacf.com" target="_blank">http://www.malagacf.com</a>
<?php if ($edit == "MALAGA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MALAGA"] as $pos => $bomber)
  if ($edit == "MALAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALAGA"] as $pos => $bomber)
  if ($edit == "MALAGA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALAGA(*)"] as $pos => $bomber)
  if ($edit == "MALAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALAGA(*)"] as $pos => $bomber)
  if ($edit == "MALAGA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainRAYOVAL.png" alt="" /></td>
    <td>
      <br />
      <b>Rayo Vallecano</b><br /><br />
      город: <b>Madrid</b><br /><br />
      год основания: <b>1924</b><br /><br />
      арена: <b>Estadio del Rayo Vallecano</b><br />
      вместительность: <b>15500</b><br /><br />
      сайт: <a href="http://www.rayovallecano.es" target="_blank">http://www.rayovallecano.es</a>
<?php if ($edit == "RAYOVAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RAYOVAL"] as $pos => $bomber)
  if ($edit == "RAYOVAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RAYOVAL"] as $pos => $bomber)
  if ($edit == "RAYOVAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RAYOVAL(*)"] as $pos => $bomber)
  if ($edit == "RAYOVAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RAYOVAL(*)"] as $pos => $bomber)
  if ($edit == "RAYOVAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainVALENCIA.png" alt="" /></td>
    <td>
      <br />
      <b>Valencia Club de Fútbol</b><br /><br />
      город: <b>Valencia</b><br /><br />
      год основания: <b>1919</b><br /><br />
      арена: <b>Estadio de Mestalla</b><br />
      вместительность: <b>55000</b><br /><br />
      сайт: <a href="http://www.valenciacf.com" target="_blank">http://www.valenciacf.com</a>
<?php if ($edit == "VALENCIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VALENCIA"] as $pos => $bomber)
  if ($edit == "VALENCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALENCIA"] as $pos => $bomber)
  if ($edit == "VALENCIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALENCIA(*)"] as $pos => $bomber)
  if ($edit == "VALENCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALENCIA(*)"] as $pos => $bomber)
  if ($edit == "VALENCIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainVILLAR.png" alt="" /></td>
    <td>
      <br />
      <b>Villarreal Club de Fútbol</b><br /><br />
      город: <b>Villarreal</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Camp El Madrigal</b><br />
      вместительность: <b>24500</b><br /><br />
      сайт: <a href="http://www.villarrealcf.es" target="_blank">http://www.villarrealcf.es</a>
<?php if ($edit == "VILLAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VILLAR"] as $pos => $bomber)
  if ($edit == "VILLAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VILLAR"] as $pos => $bomber)
  if ($edit == "VILLAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VILLAR(*)"] as $pos => $bomber)
  if ($edit == "VILLAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VILLAR(*)"] as $pos => $bomber)
  if ($edit == "VILLAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineMET-Z.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Металлург Запорожье</b><br /><br />
      город: <b>Запорожье</b><br /><br />
      год основания: <b>1935</b><br /><br />
      арена: <b>Стадион "Славутич-Арена"</b><br />
      вместительность: <b>12000</b><br /><br />
      сайт: <a href="http://http://www.fcmetalurg.com/" target="_blank">http://www.fcmetalurg.com/</a>
<?php if ($edit == "MET-Z") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MET-Z"] as $pos => $bomber)
  if ($edit == "MET-Z")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-Z"] as $pos => $bomber)
  if ($edit == "MET-Z")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-Z(*)"] as $pos => $bomber)
  if ($edit == "MET-Z")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-Z(*)"] as $pos => $bomber)
  if ($edit == "MET-Z")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineNIVA-V.png" height="150" width="150" alt="" /></td>
    <td>
      <br />
      <b>ПФК Нива Винница</b><br /><br />
      город: <b>Винница</b><br /><br />
      год основания: <b>1958</b><br /><br />
      арена: <b>Центральный городской стадион</b><br />
      вместительность: <b>14000</b><br /><br />
      сайт: <a href="http://fcniva.vn.ua" target="_blank">http://fcniva.vn.ua</a>
<?php if ($edit == "NIVA-V") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NIVA-V"] as $pos => $bomber)
  if ($edit == "NIVA-V")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIVA-V"] as $pos => $bomber)
  if ($edit == "NIVA-V")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIVA-V(*)"] as $pos => $bomber)
  if ($edit == "NIVA-V")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIVA-V(*)"] as $pos => $bomber)
  if ($edit == "NIVA-V")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineOLIMPIK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Олимпик Донецк</b><br /><br />
      город: <b>Донецк</b><br /><br />
      год основания: <b>2001</b><br /><br />
      арена: <b>Стадион СК Олимпик</b><br />
      вместительность: <b>1320</b><br /><br />
      сайт: <a href="http://olimpik.com.ua" target="_blank">http://olimpik.com.ua</a>
<?php if ($edit == "OLIMPIK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["OLIMPIK"] as $pos => $bomber)
  if ($edit == "OLIMPIK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OLIMPIK"] as $pos => $bomber)
  if ($edit == "OLIMPIK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OLIMPIK(*)"] as $pos => $bomber)
  if ($edit == "OLIMPIK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OLIMPIK(*)"] as $pos => $bomber)
  if ($edit == "OLIMPIK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineOBOLON.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Оболонь Киев</b><br /><br />
      город: <b>Киев</b><br /><br />
      год основания: <b>1992</b><br /><br />
      арена: <b>Оболонь Арена</b><br />
      вместительность: <b>5103</b><br /><br />
      сайт: <a href="http://www.facebook.com/soccerway" target="_blank">http://www.facebook.com/soccerway</a>
<?php if ($edit == "OBOLON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["OBOLON"] as $pos => $bomber)
  if ($edit == "OBOLON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OBOLON"] as $pos => $bomber)
  if ($edit == "OBOLON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OBOLON(*)"] as $pos => $bomber)
  if ($edit == "OBOLON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OBOLON(*)"] as $pos => $bomber)
  if ($edit == "OBOLON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineSEVAS.png" alt="" /></td>
    <td>
      <br />
      <b>ПФК Севастополь</b><br /><br />
      город: <b>Севастополь</b><br /><br />
      год основания: <b>2002</b><br /><br />
      арена: <b>СК "Севастополь"</b><br />
      вместительность: <b>5864</b><br /><br />
      сайт: <a href="http://www.fcsevastopol.com" target="_blank">http://www.fcsevastopol.com</a>
<?php if ($edit == "SEVAST") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SEVAST"] as $pos => $bomber)
  if ($edit == "SEVAST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVAST"] as $pos => $bomber)
  if ($edit == "SEVAST")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVAST(*)"] as $pos => $bomber)
  if ($edit == "SEVAST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVAST(*)"] as $pos => $bomber)
  if ($edit == "SEVAST")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineSHAH-D.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Шахтер Донецк</b><br /><br />
      город: <b>Донецк</b><br /><br />
      год основания: <b>1936</b><br /><br />
      арена: <b>Донбасс Арена</b><br />
      вместительность: <b>53423</b><br /><br />
      сайт: <a href="http://www.shakhtar.com" target="_blank">http://www.shakhtar.com</a>
<?php if ($edit == "SHAH-D") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SHAH-D"] as $pos => $bomber)
  if ($edit == "SHAH-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAH-D"] as $pos => $bomber)
  if ($edit == "SHAH-D")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAH-D(*)"] as $pos => $bomber)
  if ($edit == "SHAH-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAH-D(*)"] as $pos => $bomber)
  if ($edit == "SHAH-D")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
