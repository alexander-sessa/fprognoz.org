<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/belarusBATE.png" alt="" /></td>
    <td>
      <br />
      <b>ФК БАТЭ Борисов</b><br /><br />
      город: <b>Барысаў (Борисов)</b><br /><br />
      год основания: <b>1973</b><br /><br />
      арена: <b>Гарадскі стадыён</b><br />
      вместительность: <b>5502</b><br /><br />
      <a href="http://www.fcbate.by" target="_blank">http://www.fcbate.by</a>
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
    <td><br /><img src="images/belarusBELSHINA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Белшина Бобруйск</b><br /><br />
      город: <b>Бабруйск (Бобруйск)</b><br /><br />
      год основания: <b>1977</b><br /><br />
      арена: <b>Стадыён "Спартак"</b><br />
      вместительность: <b>3661</b><br /><br />
      <a href="http://www.fcbelshina.by" target="_blank">http://www.fcbelshina.by</a>
<?php if ($edit == "BELSHINA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BELSHINA"] as $pos => $bomber)
  if ($edit == "BELSHINA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELSHINA"] as $pos => $bomber)
  if ($edit == "BELSHINA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELSHINA(*)"] as $pos => $bomber)
  if ($edit == "BELSHINA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELSHINA(*)"] as $pos => $bomber)
  if ($edit == "BELSHINA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
   <td><img src="images/belarusVEDRICH.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Ведрич-97 Речица</b><br /><br />
      город: <b>Рэчыца (Речица)</b><br /><br />
      год основания: <b>1952</b><br /><br />
      арена: <b>Цэнтральны Стадыён</b><br />
      вместительность: <b>3550</b><br /><br />
      <a href="http://fcvedrich97.ucoz.ru/" target="_blank">http://fcvedrich97.ucoz.ru/</a>
<?php if ($edit == "VEDRICH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VEDRICH"] as $pos => $bomber)
  if ($edit == "VEDRICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VEDRICH"] as $pos => $bomber)
  if ($edit == "VEDRICH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VEDRICH(*)"] as $pos => $bomber)
  if ($edit == "VEDRICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VEDRICH(*)"] as $pos => $bomber)
  if ($edit == "VEDRICH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
   <td><img src="images/belarusGORODEYA.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Городея</b><br /><br />
      город: <b>Гарадея (Городея)</b><br /><br />
      год основания: <b>2004</b><br /><br />
      арена: <b>Стадыён "Сахарник"</b><br />
      вместительность: <b>2100</b><br /><br />
      <a href="http://fcgorodeya.by" target="_blank">http://fcgorodeya.by</a>
<?php if ($edit == "GORODEYA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GORODEYA"] as $pos => $bomber)
  if ($edit == "GORODEYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GORODEYA"] as $pos => $bomber)
  if ($edit == "GORODEYA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GORODEYA(*)"] as $pos => $bomber)
  if ($edit == "GORODEYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GORODEYA(*)"] as $pos => $bomber)
  if ($edit == "GORODEYA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusDINBR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Брест</b><br /><br />
      город: <b>Брэст (Брест)</b><br /><br />
      год основания: <b>1960</b><br /><br />
      арена: <b>Стадыён ДАСК "Брэсцкі"</b><br />
      вместительность: <b>10169</b><br /><br />
      <a href="http://www.dynamo-brest.by" target="_blank">http://www.dynamo-brest.by</a>
<?php if ($edit == "DINBR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DINBR"] as $pos => $bomber)
  if ($edit == "DINBR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINBR"] as $pos => $bomber)
  if ($edit == "DINBR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINBR(*)"] as $pos => $bomber)
  if ($edit == "DINBR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DINBR(*)"] as $pos => $bomber)
  if ($edit == "DINBR")
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
      <a href="http://www.dinamo-minsk.by" target="_blank">http://www.dinamo-minsk.by</a>
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
      <a href="http://www.fcgomel.by" target="_blank">http://www.fcgomel.by</a>
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
      <a href="http://www.fcgranit.by/" target="_blank">http://www.fcgranit.by/</a>
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
    <td align="middle"><img src="images/belarusISLOCH.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ислочь Минск</b><br /><br />
      город: <b>Мінскі раён (Минский район)</b><br /><br />
      год основания: <b>2007 </b><br /><br />
      арена: <b>Стадыён РЦАП-БДУ</b><br />
      вместительность: <b>1500</b><br /><br />
      <a href="http://vk.com/fcisloch" target="_blank">http://vk.com/fcisloch</a>
<?php if ($edit == "ISLOCH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ISLOCH"] as $pos => $bomber)
  if ($edit == "ISLOCH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ISLOCH"] as $pos => $bomber)
  if ($edit == "ISLOCH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ISLOCH(*)"] as $pos => $bomber)
  if ($edit == "ISLOCH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ISLOCH(*)"] as $pos => $bomber)
  if ($edit == "ISLOCH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusKOMM.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Слоним (бывший Коммунальник)</b><br /><br />
      город: <b>Слонім (Слоним)</b><br /><br />
      год основания: <b>1969</b><br /><br />
      арена: <b>Стадыён "Юнацтва"</b><br />
      вместительность: <b>2220</b><br /><br />
      <a href="http://fcslonim.hol.es/index.html" target="_blank">http://fcslonim.hol.es/index.html</a>
<?php if ($edit == "KOMM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KOMM"] as $pos => $bomber)
  if ($edit == "KOMM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOMM"] as $pos => $bomber)
  if ($edit == "KOMM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOMM(*)"] as $pos => $bomber)
  if ($edit == "KOMM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOMM(*)"] as $pos => $bomber)
  if ($edit == "KOMM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusKRUMKACH.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Крумкачы Минск</b><br /><br />
      город: <b>Минск</b><br /><br />
      год основания: <b>2014</b><br /><br />
      арена: <b>Стадыён ФК "Минск"</b><br />
      вместительность: <b>3100</b><br /><br />
      <a href="http://krumka.by" target="_blank">http://krumka.by</a>
<?php if ($edit == "KRUMKACH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KRUMKACH"] as $pos => $bomber)
  if ($edit == "KRUMKACH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRUMKACH"] as $pos => $bomber)
  if ($edit == "KRUMKACH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRUMKACH(*)"] as $pos => $bomber)
  if ($edit == "KRUMKACH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRUMKACH(*)"] as $pos => $bomber)
  if ($edit == "KRUMKACH")
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
      <a href="http://www.fcminsk.com" target="_blank">http://www.fcminsk.com</a>
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
      <a href="http://www.fcnaftan.com" target="_blank">http://www.fcnaftan.com</a>
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
      <a href="http://www.fcneman.by/" target="_blank">http://www.fcneman.by/</a>
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
    <td><br /><img src="images/belarusSKVICH.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК СКВИЧ Минск</b><br /><br />
      город: <b>Мінск (Минск)</b><br /><br />
      год основания: <b>2000</b><br /><br />
      арена: <b>САК "Алімпійскі"</b><br />
      вместительность: <b>1000</b><br /><br />
      <a href="http://www.skvich.by/" target="_blank">http://www.skvich.by/</a>
<?php if ($edit == "SKVICH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SKVICH"] as $pos => $bomber)
  if ($edit == "SKVICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKVICH"] as $pos => $bomber)
  if ($edit == "SKVICH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKVICH(*)"] as $pos => $bomber)
  if ($edit == "SKVICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKVICH(*)"] as $pos => $bomber)
  if ($edit == "SKVICH")
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
      <a href="http://www.slaviya.by" target="_blank">http://www.slaviya.by</a>
<?php if ($edit == "SLAVIYA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SLAVIYA"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIYA"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIYA(*)"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLAVIYA(*)"] as $pos => $bomber)
  if ($edit == "SLAVIYA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusSLUTSK.png" width="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Слуцк</b><br /><br />
      город: <b>Слуцк</b><br /><br />
      год основания: <b>1998</b><br /><br />
      арена: <b>Гарадскі стадыён</b><br />
      вместительность: <b>2000</b><br /><br />
      <a href="http://sfc-slutsk.by/" target="_blank">http://sfc-slutsk.by/</a>
<?php if ($edit == "SLUTSK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SLUTSK"] as $pos => $bomber)
  if ($edit == "SLUTSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLUTSK"] as $pos => $bomber)
  if ($edit == "SLUTSK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLUTSK(*)"] as $pos => $bomber)
  if ($edit == "SLUTSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SLUTSK(*)"] as $pos => $bomber)
  if ($edit == "SLUTSK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusTORPEDO.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Торпедо Жодино</b><br /><br />
      город: <b>Жодзіна (Жодино)</b><br /><br />
      год основания: <b>1961</b><br /><br />
      арена: <b>Стадыён "Тарпеда"</b><br />
      вместительность: <b>6524</b><br /><br />
      <a href="http://tarpeda.zhodzina.info" target="_blank">http://tarpeda.zhodzina.info</a>
<?php if ($edit == "TORPEDO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TORPEDO"] as $pos => $bomber)
  if ($edit == "TORPEDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORPEDO"] as $pos => $bomber)
  if ($edit == "TORPEDO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORPEDO(*)"] as $pos => $bomber)
  if ($edit == "TORPEDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORPEDO(*)"] as $pos => $bomber)
  if ($edit == "TORPEDO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusSHAKH.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Шахтёр Солигорск</b><br /><br />
      город: <b>Салігорск (Солигорск)</b><br /><br />
      год основания: <b>1963</b><br /><br />
      арена: <b>Стадыён "Будаўнік"</b><br />
      вместительность: <b>4200</b><br /><br />
      <a href="http://www.fcshakhter.by" target="_blank">http://www.fcshakhter.by</a>
<?php if ($edit == "SHAKH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SHAKH"] as $pos => $bomber)
  if ($edit == "SHAKH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAKH"] as $pos => $bomber)
  if ($edit == "SHAKH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAKH(*)"] as $pos => $bomber)
  if ($edit == "SHAKH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHAKH(*)"] as $pos => $bomber)
  if ($edit == "SHAKH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/belarusVITEBSK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК "Витебск"</b><br /><br />
      город: <b>Витебск</b><br /><br />
      год основания: <b>1960</b><br /><br />
      арена: <b>ЦСК</b><br />
      вместительность: <b>8144</b><br /><br />
      <a href="http://www.fc.vitebsk.by" target="_blank">http://www.fc.vitebsk.by</a>
<?php if ($edit == "VITEBSK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VITEBSK"] as $pos => $bomber)
  if ($edit == "VITEBSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITEBSK"] as $pos => $bomber)
  if ($edit == "VITEBSK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITEBSK(*)"] as $pos => $bomber)
  if ($edit == "VITEBSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VITEBSK(*)"] as $pos => $bomber)
  if ($edit == "VITEBSK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
