<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/russiaAVANGARD.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Авангард Курск</b><br /><br />
      город: <b>Курск</b><br /><br />
      год основания: <b>1958</b><br /><br />
      арена: <b>Стадион "Трудовые Резервы"</b><br />
      вместительность: <b>11329</b><br /><br />
      <a href="http://www.fc-avangard.ru" target="_blank">http://www.fc-avangard.ru</a>
<?php if ($edit == "AVANGARD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AVANGARD"] as $pos => $bomber)
  if ($edit == "AVANGARD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVANGARD"] as $pos => $bomber)
  if ($edit == "AVANGARD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVANGARD(*)"] as $pos => $bomber)
  if ($edit == "AVANGARD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVANGARD(*)"] as $pos => $bomber)
  if ($edit == "AVANGARD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaALANIA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Алания (Спартак) Владикавказ</b><br /><br />
      город: <b>Владикавказ</b><br /><br />
      год основания: <b>1921/2011</b><br /><br />
      арена: <b>Республиканский стадион "Спартак"</b><br />
      вместительность: <b>32464</b><br /><br />
      <a href="http://www.spartak-vladikavkaz.ru" target="_blank">http://www.spartak-vladikavkaz.ru</a>
<?php if ($edit == "ALANIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ALANIA"] as $pos => $bomber)
  if ($edit == "ALANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALANIA"] as $pos => $bomber)
  if ($edit == "ALANIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALANIA(*)"] as $pos => $bomber)
  if ($edit == "ALANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALANIA(*)"] as $pos => $bomber)
  if ($edit == "ALANIA")
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
      <a href="http://fc-amkar.org" target="_blank">http://fc-amkar.org</a>
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
    <td><br /><img src="images/russiaANZHI.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Анжи Махачкала</b><br /><br />
      город: <b>Каспийск</b><br /><br />
      год основания: <b>1991</b><br /><br />
      арена: <b>Анжи-Арена</b><br />
      вместительность: <b>31000</b><br /><br />
      <a href="http://www.fc-anji.ru" target="_blank">http://www.fc-anji.ru</a>
<?php if ($edit == "ANZHI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ANZHI"] as $pos => $bomber)
  if ($edit == "ANZHI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANZHI"] as $pos => $bomber)
  if ($edit == "ANZHI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANZHI(*)"] as $pos => $bomber)
  if ($edit == "ANZHI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANZHI(*)"] as $pos => $bomber)
  if ($edit == "ANZHI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaARSENALT.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Арсенал Тула</b><br /><br />
      город: <b>Тула</b><br /><br />
      год основания: <b>2008</b><br /><br />
      арена: <b>Стадион "Арсенал"</b><br />
      вместительность: <b>20048</b><br /><br />
      <a href="http://arsenaltula.ru" target="_blank">http://arsenaltula.ru</a>
<?php if ($edit == "ARSENALT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ARSENALT"] as $pos => $bomber)
  if ($edit == "ARSENALT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALT"] as $pos => $bomber)
  if ($edit == "ARSENALT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALT(*)"] as $pos => $bomber)
  if ($edit == "ARSENALT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALT(*)"] as $pos => $bomber)
  if ($edit == "ARSENALT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaBALTIKA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Балтика Калининград</b><br /><br />
      город: <b>Калининград</b><br /><br />
      год основания: <b>1954</b><br /><br />
      арена: <b>Стадион "Балтика"</b><br />
      вместительность: <b>14664</b><br /><br />
      <a href="http://www.fc-baltika.ru" target="_blank">http://www.fc-baltika.ru</a>
<?php if ($edit == "BALTIKA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BALTIKA"] as $pos => $bomber)
  if ($edit == "BALTIKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BALTIKA"] as $pos => $bomber)
  if ($edit == "BALTIKA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BALTIKA(*)"] as $pos => $bomber)
  if ($edit == "BALTIKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BALTIKA(*)"] as $pos => $bomber)
  if ($edit == "BALTIKA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaBIOLOG.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Биолог Новокубанск</b><br /><br />
      город: <b>Новокубанск</b><br /><br />
      год основания: <b></b><br /><br />
      арена: <b>Стадион "Биолог"</b><br />
      вместительность: <b>2300</b><br /><br />
      <a href="http://fcbiolog.ru" target="_blank">http://fcbiolog.ru</a>
<?php if ($edit == "BIOLOG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BIOLOG"] as $pos => $bomber)
  if ($edit == "BIOLOG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BIOLOG"] as $pos => $bomber)
  if ($edit == "BIOLOG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BIOLOG(*)"] as $pos => $bomber)
  if ($edit == "BIOLOG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BIOLOG(*)"] as $pos => $bomber)
  if ($edit == "BIOLOG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaVOLGA-T.png" height="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Волга Тверь</b><br /><br />
      город: <b>Тверь</b><br /><br />
      год основания: <b>1957</b><br /><br />
      арена: <b>Стадион "Химик"</b><br />
      вместительность: <b>7680</b><br /><br />
      <a href="http://www.fc-volga.ru" target="_blank">http://www.fc-volga.ru</a>
<?php if ($edit == "VOLGA-T") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VOLGA-T"] as $pos => $bomber)
  if ($edit == "VOLGA-T")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLGA-T"] as $pos => $bomber)
  if ($edit == "VOLGA-T")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLGA-T(*)"] as $pos => $bomber)
  if ($edit == "VOLGA-T")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLGA-T(*)"] as $pos => $bomber)
  if ($edit == "VOLGA-T")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaKAVKAZ.png" height="150" alt="" /></td>
    <td>
      <br />
      <b>ФК Газпром Трансгаз Ставрополь</b><br /><br />
      город: <b>Рыздвяный</b><br /><br />
      год основания: <b>1986</b><br /><br />
      арена: <b>Стадион "Факел"</b><br />
      вместительность: <b>1290</b><br /><br />
      <a href="http://www.fc-gts.ru" target="_blank">http://www.fc-gts.ru</a>
<?php if ($edit == "KAVKAZ") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KAVKAZ"] as $pos => $bomber)
  if ($edit == "KAVKAZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAVKAZ"] as $pos => $bomber)
  if ($edit == "KAVKAZ")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAVKAZ(*)"] as $pos => $bomber)
  if ($edit == "KAVKAZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAVKAZ(*)"] as $pos => $bomber)
  if ($edit == "KAVKAZ")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaDIN-BRN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Барнаул</b><br /><br />
      город: <b>Барнаул</b><br /><br />
      год основания: <b></b><br /><br />
      арена: <b>Стадион "Динамо"</b><br />
      вместительность: <b>15000</b><br /><br />
      <a href="http://www.fc-dinamo-barnaul.ru" target="_blank">http://www.fc-dinamo-barnaul.ru</a>
<?php if ($edit == "DIN-BRN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-BRN"] as $pos => $bomber)
  if ($edit == "DIN-BRN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-BRN"] as $pos => $bomber)
  if ($edit == "DIN-BRN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-BRN(*)"] as $pos => $bomber)
  if ($edit == "DIN-BRN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-BRN(*)"] as $pos => $bomber)
  if ($edit == "DIN-BRN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaDIN-B.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Брянск</b><br /><br />
      город: <b>Брянск</b><br /><br />
      год основания: <b>1931</b><br /><br />
      арена: <b>Стадион "Динамо"</b><br />
      вместительность: <b>10100</b><br /><br />
      <a href="http://www.fkdb.ru" target="_blank">http://www.fkdb.ru</a>
<?php if ($edit == "DIN-B") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-B"] as $pos => $bomber)
  if ($edit == "DIN-B")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-B"] as $pos => $bomber)
  if ($edit == "DIN-B")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-B(*)"] as $pos => $bomber)
  if ($edit == "DIN-B")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-B(*)"] as $pos => $bomber)
  if ($edit == "DIN-B")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaDIN-M.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Арена "Химки"</b><br />
      вместительность: <b>18636</b><br /><br />
      <a href="http://www.fcdinamo.ru" target="_blank">http://www.fcdinamo.ru</a>
<?php if ($edit == "DIN-M") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-M"] as $pos => $bomber)
  if ($edit == "DIN-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-M"] as $pos => $bomber)
  if ($edit == "DIN-M")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-M(*)"] as $pos => $bomber)
  if ($edit == "DIN-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-M(*)"] as $pos => $bomber)
  if ($edit == "DIN-M")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaDIN-SP.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Санкт-Петербург</b><br /><br />
      город: <b>Санкт-Петербург</b><br /><br />
      год основания: <b>2010</b><br /><br />
      арена: <b>Малая спортивная арена</b><br />
      вместительность: <b>3000</b><br /><br />
      <a href="http://www.fcdynamospb.ru" target="_blank">http://www.fcdynamospb.ru</a>
<?php if ($edit == "DIN-SP") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-SP"] as $pos => $bomber)
  if ($edit == "DIN-SP")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-SP"] as $pos => $bomber)
  if ($edit == "DIN-SP")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-SP(*)"] as $pos => $bomber)
  if ($edit == "DIN-SP")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-SP(*)"] as $pos => $bomber)
  if ($edit == "DIN-SP")
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
      <a href="http://www.dynamost.ru" target="_blank">http://www.dynamost.ru</a>
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
      <a href="http://www.fc-enisey.ru/" target="_blank">http://www.fc-enisey.ru/</a>
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
      <a href="http://www.fc-zenit.ru" target="_blank">http://www.fc-zenit.ru</a>
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
      <a href="http://2.fc-zenit.ru" target="_blank">http://2.fc-zenit.ru</a>
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
    <td><img src="images/spacer.gif" height="50" alt="" /><br /><img src="images/russiaKAMAZ.png" alt="" /></td>
    <td>
      <br />
      <b>ФК КамАЗ Набережные Челны</b><br /><br />
      город: <b>Набережные Челны</b><br /><br />
      год основания: <b></b>1981<br /><br />
      арена: <b>Стадион КамАЗ</b><br />
      вместительность: <b>9056</b><br /><br />
      <a href="http://www.fckamaz.ru" target="_blank">http://www.fckamaz.ru</a>
<?php if ($edit == "KAMAZ") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KAMAZ"] as $pos => $bomber)
  if ($edit == "KAMAZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAMAZ"] as $pos => $bomber)
  if ($edit == "KAMAZ")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAMAZ(*)"] as $pos => $bomber)
  if ($edit == "KAMAZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAMAZ(*)"] as $pos => $bomber)
  if ($edit == "KAMAZ")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaKRASNODAR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Краснодар</b><br /><br />
      город: <b>Краснодар</b><br /><br />
      год основания: <b>2007</b><br /><br />
      арена: <b>Стадион "Кубань"</b><br />
      вместительность: <b>35200</b><br /><br />
      <a href="http://www.fckrasnodar.ru" target="_blank">http://www.fckrasnodar.ru</a>
<?php if ($edit == "KRASNODAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KRASNODAR"] as $pos => $bomber)
  if ($edit == "KRASNODAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRASNODAR"] as $pos => $bomber)
  if ($edit == "KRASNODAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRASNODAR(*)"] as $pos => $bomber)
  if ($edit == "KRASNODAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRASNODAR(*)"] as $pos => $bomber)
  if ($edit == "KRASNODAR")
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
      <a href="http://www.kc-camapa.ru" target="_blank">http://www.kc-camapa.ru</a>
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
    <td><br /><img src="images/russiaKUBAN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Кубань Краснодар</b><br /><br />
      город: <b>Краснодар</b><br /><br />
      год основания: <b>1928</b><br /><br />
      арена: <b>Стадион "Кубань"</b><br />
      вместительность: <b>35200</b><br /><br />
      <a href="http://www.fckuban.ru" target="_blank">http://www.fckuban.ru</a>
<?php if ($edit == "KUBAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KUBAN"] as $pos => $bomber)
  if ($edit == "KUBAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KUBAN"] as $pos => $bomber)
  if ($edit == "KUBAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KUBAN(*)"] as $pos => $bomber)
  if ($edit == "KUBAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KUBAN(*)"] as $pos => $bomber)
  if ($edit == "KUBAN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaLOKO.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Локомотив Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Стадион "Локомотив"</b><br />
      вместительность: <b>28800</b><br /><br />
      <a href="http://www.fclm.ru" target="_blank">http://www.fclm.ru</a>
<?php if ($edit == "LOKO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LOKO"] as $pos => $bomber)
  if ($edit == "LOKO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LOKO"] as $pos => $bomber)
  if ($edit == "LOKO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LOKO(*)"] as $pos => $bomber)
  if ($edit == "LOKO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LOKO(*)"] as $pos => $bomber)
  if ($edit == "LOKO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaMASHUK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Машук-КМВ Пятигорск</b><br /><br />
      город: <b>Пятигорск</b><br /><br />
      год основания: <b>1936</b><br /><br />
      арена: <b>Центральный стадион "Машук"</b><br />
      вместительность: <b>10365</b><br /><br />
      <a href="http://www.fc-mashuk.ru/" target="_blank">http://www.fc-mashuk.ru/</a>
<?php if ($edit == "MASHUK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MASHUK"] as $pos => $bomber)
  if ($edit == "MASHUK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MASHUK"] as $pos => $bomber)
  if ($edit == "MASHUK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MASHUK(*)"] as $pos => $bomber)
  if ($edit == "MASHUK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MASHUK(*)"] as $pos => $bomber)
  if ($edit == "MASHUK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaMORDOV.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Мордовия Саранск</b><br /><br />
      город: <b>Саранск</b><br /><br />
      год основания: <b>1961</b><br /><br />
      арена: <b>Стадион "Старт"</b><br />
      вместительность: <b>12000</b><br /><br />
      <a href="http://www.fc-mordovia.ru/" target="_blank">http://www.fc-mordovia.ru/</a>
<?php if ($edit == "MORDOV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MORDOV"] as $pos => $bomber)
  if ($edit == "MORDOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MORDOV"] as $pos => $bomber)
  if ($edit == "MORDOV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MORDOV(*)"] as $pos => $bomber)
  if ($edit == "MORDOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MORDOV(*)"] as $pos => $bomber)
  if ($edit == "MORDOV")
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
      <a href="http://www.fcmoscow.ru" target="_blank">http://www.fcmoscow.ru</a>
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
    <td><br /><img src="images/russiaROSTOV.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ростов</b><br /><br />
      город: <b>Ростов-на-Дону</b><br /><br />
      год основания: <b>1930</b><br /><br />
      арена: <b>Стадион "Олимп 2"</b><br />
      вместительность: <b>15840</b><br /><br />
      <a href="http://www.fc-rostov.ru" target="_blank">http://www.fc-rostov.ru</a>
<?php if ($edit == "ROSTOV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ROSTOV"] as $pos => $bomber)
  if ($edit == "ROSTOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSTOV"] as $pos => $bomber)
  if ($edit == "ROSTOV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSTOV(*)"] as $pos => $bomber)
  if ($edit == "ROSTOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSTOV(*)"] as $pos => $bomber)
  if ($edit == "ROSTOV")
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
      <a href="http://www.rotor-fc.com" target="_blank">http://www.rotor-fc.com</a>
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
    <td><br /><img src="images/russiaRUBIN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Рубин Казань</b><br /><br />
      город: <b>Казань</b><br /><br />
      год основания: <b>1958</b><br /><br />
      арена: <b>Центральный стадион "Казань"</b><br />
      вместительность: <b>30133</b><br /><br />
      <a href="http://www.rubin-kazan.ru" target="_blank">http://www.rubin-kazan.ru</a>
<?php if ($edit == "RUBIN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RUBIN"] as $pos => $bomber)
  if ($edit == "RUBIN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RUBIN"] as $pos => $bomber)
  if ($edit == "RUBIN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RUBIN(*)"] as $pos => $bomber)
  if ($edit == "RUBIN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RUBIN(*)"] as $pos => $bomber)
  if ($edit == "RUBIN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSATURN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Сатурн Московская область</b><br /><br />
      город: <b>Раменское</b><br /><br />
      год основания: <b>1946</b><br /><br />
      арена: <b>Стадион "Сатурн"</b><br />
      вместительность: <b>16726</b><br /><br />
      <a href="http://www.saturn-fc.ru" target="_blank">http://www.saturn-fc.ru</a>
<?php if ($edit == "SATURN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SATURN"] as $pos => $bomber)
  if ($edit == "SATURN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SATURN"] as $pos => $bomber)
  if ($edit == "SATURN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SATURN(*)"] as $pos => $bomber)
  if ($edit == "SATURN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SATURN(*)"] as $pos => $bomber)
  if ($edit == "SATURN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSIBIR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Сибирь Новосибирск</b><br /><br />
      город: <b>Новосибирск</b><br /><br />
      год основания: <b>1936</b><br /><br />
      арена: <b>Стадион "Спартак"</b><br />
      вместительность: <b>12567</b><br /><br />
      <a href="http://www.fc-sibir.ru" target="_blank">http://www.fc-sibir.ru</a>
<?php if ($edit == "SIBIR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SIBIR"] as $pos => $bomber)
  if ($edit == "SIBIR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIBIR"] as $pos => $bomber)
  if ($edit == "SIBIR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIBIR(*)"] as $pos => $bomber)
  if ($edit == "SIBIR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIBIR(*)"] as $pos => $bomber)
  if ($edit == "SIBIR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSKA-R.png" alt="" /></td>
    <td>
      <br />
      <b>ФК СКА Ростов-на-Дону</b><br /><br />
      город: <b>Ростов-на-Дону</b><br /><br />
      год основания: <b>1937</b><br /><br />
      арена: <b>Стадион СКА СКВО</b><br />
      вместительность: <b>27300</b><br /><br />
      <a href="http://www.ska-rostov.ru" target="_blank">http://www.ska-rostov.ru</a>
<?php if ($edit == "SKA-R") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SKA-R"] as $pos => $bomber)
  if ($edit == "SKA-R")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKA-R"] as $pos => $bomber)
  if ($edit == "SKA-R")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKA-R(*)"] as $pos => $bomber)
  if ($edit == "SKA-R")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SKA-R(*)"] as $pos => $bomber)
  if ($edit == "SKA-R")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaHABAR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК СКА Хабаровск</b><br /><br />
      город: <b>Хабаровск</b><br /><br />
      год основания: <b>1946</b><br /><br />
      арена: <b>Стадион им. В. И. Ленина</b><br />
      вместительность: <b>15200</b><br /><br />
      <a href="http://www.fc-skaenergy.ru" target="_blank">http://www.fc-skaenergy.ru</a>
<?php if ($edit == "HABAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HABAR"] as $pos => $bomber)
  if ($edit == "HABAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HABAR"] as $pos => $bomber)
  if ($edit == "HABAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HABAR(*)"] as $pos => $bomber)
  if ($edit == "HABAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HABAR(*)"] as $pos => $bomber)
  if ($edit == "HABAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSOKOL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Сокол Саратов</b><br /><br />
      город: <b>Саратов</b><br /><br />
      год основания: <b>2005</b><br /><br />
      арена: <b>Стадион "Локомотив"</b><br />
      вместительность: <b>15260</b><br /><br />
      <a href="http://www.sokol-saratov.ru" target="_blank">http://www.sokol-saratov.ru</a>
<?php if ($edit == "SOKOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SOKOL"] as $pos => $bomber)
  if ($edit == "SOKOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOKOL"] as $pos => $bomber)
  if ($edit == "SOKOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOKOL(*)"] as $pos => $bomber)
  if ($edit == "SOKOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOKOL(*)"] as $pos => $bomber)
  if ($edit == "SOKOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSPARTAK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Спартак Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Олимпийский стадион "Лужники"</b><br />
      вместительность: <b>84745</b><br /><br />
      <a href="http://spartak.com" target="_blank">http://spartak.com</a>
<?php if ($edit == "SPARTAK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK"] as $pos => $bomber)
  if ($edit == "SPARTAK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK"] as $pos => $bomber)
  if ($edit == "SPARTAK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK(*)"] as $pos => $bomber)
  if ($edit == "SPARTAK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK(*)"] as $pos => $bomber)
  if ($edit == "SPARTAK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSPARTAK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Спартак Москва 2</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b></b><br /><br />
      арена: <b>Футбольное поле 4 "Спартак"</b><br />
      вместительность: <b>4000</b><br /><br />
      <a href="http://spartak2.ru" target="_blank">http://spartak2.ru</a>
<?php if ($edit == "SPARTAK2") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK2"] as $pos => $bomber)
  if ($edit == "SPARTAK2")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK2"] as $pos => $bomber)
  if ($edit == "SPARTAK2")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK2(*)"] as $pos => $bomber)
  if ($edit == "SPARTAK2")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPARTAK2(*)"] as $pos => $bomber)
  if ($edit == "SPARTAK2")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaTAMBOV.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Тамбов</b><br /><br />
      город: <b>Тамбов</b><br /><br />
      год основания: <b></b><br /><br />
      арена: <b>Стадион "Спартак"</b><br />
      вместительность: <b>8000</b><br /><br />
      <a href="http://fc-tambov.ru" target="_blank">http://fc-tambov.ru</a>
<?php if ($edit == "TAMBOV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TAMBOV"] as $pos => $bomber)
  if ($edit == "TAMBOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TAMBOV"] as $pos => $bomber)
  if ($edit == "TAMBOV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TAMBOV(*)"] as $pos => $bomber)
  if ($edit == "TAMBOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TAMBOV(*)"] as $pos => $bomber)
  if ($edit == "TAMBOV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaTEREK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ахмат Грозный</b><br /><br />
      город: <b>Грозный</b><br /><br />
      год основания: <b>1958</b><br /><br />
      арена: <b>Ахмат Арена</b><br />
      вместительность: <b>30597</b><br /><br />
      <a href="http://www.fc-terek.ru" target="_blank">http://www.fc-terek.ru</a>
<?php if ($edit == "TEREK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TEREK"] as $pos => $bomber)
  if ($edit == "TEREK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TEREK"] as $pos => $bomber)
  if ($edit == "TEREK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TEREK(*)"] as $pos => $bomber)
  if ($edit == "TEREK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TEREK(*)"] as $pos => $bomber)
  if ($edit == "TEREK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaTOMSK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Томь Томск</b><br /><br />
      город: <b>Томск</b><br /><br />
      год основания: <b>1957</b><br /><br />
      арена: <b>Стадион "Труд"</b><br />
      вместительность: <b>15500</b><br /><br />
      <a href="http://fctomtomsk.ru" target="_blank">http://fctomtomsk.ru</a>
<?php if ($edit == "TOMSK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOMSK"] as $pos => $bomber)
  if ($edit == "TOMSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOMSK"] as $pos => $bomber)
  if ($edit == "TOMSK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOMSK(*)"] as $pos => $bomber)
  if ($edit == "TOMSK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOMSK(*)"] as $pos => $bomber)
  if ($edit == "TOMSK")
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
      <a href="http://www.torpedo33.com" target="_blank">http://www.torpedo33.com</a>
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
    <td><br /><img src="images/russiaTOR-M.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Торпедо Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1930</b><br /><br />
      арена: <b>Стадион "Торпедо" им. Эдуарда Стрельцова</b><br />
      вместительность: <b>14274</b><br /><br />
      <a href="http://fc-tm.ru/" target="_blank">http://fc-tm.ru/</a>
<?php if ($edit == "TOR-M") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOR-M"] as $pos => $bomber)
  if ($edit == "TOR-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-M"] as $pos => $bomber)
  if ($edit == "TOR-M")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-M(*)"] as $pos => $bomber)
  if ($edit == "TOR-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOR-M(*)"] as $pos => $bomber)
  if ($edit == "TOR-M")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaTOSNO.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Тосно</b><br /><br />
      город: <b>Тосно</b><br /><br />
      год основания: <b>2013</b><br /><br />
      арена: <b>Малая Спортивная Арена</b><br />
      вместительность: <b>3000</b><br /><br />
      <a href="http://fctosno.ru" target="_blank">http://fctosno.ru</a>
<?php if ($edit == "TOSNO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOSNO"] as $pos => $bomber)
  if ($edit == "TOSNO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOSNO"] as $pos => $bomber)
  if ($edit == "TOSNO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOSNO(*)"] as $pos => $bomber)
  if ($edit == "TOSNO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOSNO(*)"] as $pos => $bomber)
  if ($edit == "TOSNO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaURAL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Урал Свердловская область</b><br /><br />
      город: <b>Екатеринбург</b><br /><br />
      год основания: <b>1930</b><br /><br />
      арена: <b>СКБ-Банк Арена</b><br />
      вместительность: <b>10000</b><br /><br />
      <a href="http://fc-ural.ru" target="_blank">http://fc-ural.ru</a>
<?php if ($edit == "URAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["URAL"] as $pos => $bomber)
  if ($edit == "URAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["URAL"] as $pos => $bomber)
  if ($edit == "URAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["URAL(*)"] as $pos => $bomber)
  if ($edit == "URAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["URAL(*)"] as $pos => $bomber)
  if ($edit == "URAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaUFA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Уфа</b><br /><br />
      город: <b>Уфа</b><br /><br />
      год основания: <b>2009</b><br /><br />
      арена: <b>Стадион "Нефтяник"</b><br />
      вместительность: <b>15234</b><br /><br />
      <a href="http://fcufa.pro" target="_blank">http://fcufa.pro</a>
<?php if ($edit == "UFA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["UFA"] as $pos => $bomber)
  if ($edit == "UFA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UFA"] as $pos => $bomber)
  if ($edit == "UFA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UFA(*)"] as $pos => $bomber)
  if ($edit == "UFA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UFA(*)"] as $pos => $bomber)
  if ($edit == "UFA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaFAKEL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Факел Воронеж</b><br /><br />
      город: <b>Воронеж</b><br /><br />
      год основания: <b>1947</b><br /><br />
      арена: <b>Центральный стадион профсоюзов</b><br />
      вместительность: <b>31793</b><br /><br />
      <a href="http://www.fakelfc.ru/" target="_blank">http://www.fakelfc.ru/</a>
<?php if ($edit == "FAKEL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FAKEL"] as $pos => $bomber)
  if ($edit == "FAKEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FAKEL"] as $pos => $bomber)
  if ($edit == "FAKEL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FAKEL(*)"] as $pos => $bomber)
  if ($edit == "FAKEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FAKEL(*)"] as $pos => $bomber)
  if ($edit == "FAKEL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaKHIMKI.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Химки</b><br /><br />
      город: <b>Химки</b><br /><br />
      год основания: <b>1997</b><br /><br />
      арена: <b>Стадион "Родина"</b><br />
      вместительность: <b>10033</b><br /><br />
      <a href="http://www.fckhimki.ru" target="_blank">http://www.fckhimki.ru</a>
<?php if ($edit == "KHIMKI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KHIMKI"] as $pos => $bomber)
  if ($edit == "KHIMKI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KHIMKI"] as $pos => $bomber)
  if ($edit == "KHIMKI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KHIMKI(*)"] as $pos => $bomber)
  if ($edit == "KHIMKI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KHIMKI(*)"] as $pos => $bomber)
  if ($edit == "KHIMKI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaCSKA.png" alt="" /></td>
    <td>
      <br />
      <b>ПФК ЦСКА Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b>1911</b><br /><br />
      арена: <b>Арена "Химки"</b><br />
      вместительность: <b>18636</b><br /><br />
      <a href="http://www.pfc-cska.com" target="_blank">http://www.pfc-cska.com</a>
<?php if ($edit == "CSKA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CSKA"] as $pos => $bomber)
  if ($edit == "CSKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CSKA"] as $pos => $bomber)
  if ($edit == "CSKA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CSKA(*)"] as $pos => $bomber)
  if ($edit == "CSKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CSKA(*)"] as $pos => $bomber)
  if ($edit == "CSKA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaCHERNOM.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Черноморец Новороссийск</b><br /><br />
      город: <b>Новороссийск</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Центральный стадион</b><br />
      вместительность: <b>12910</b><br /><br />
      <a href="http://www.fcchernomorets.ru" target="_blank">http://www.fcchernomorets.ru</a>
<?php if ($edit == "CHERNOM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHERNOM"] as $pos => $bomber)
  if ($edit == "CHERNOM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERNOM"] as $pos => $bomber)
  if ($edit == "CHERNOM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERNOM(*)"] as $pos => $bomber)
  if ($edit == "CHERNOM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERNOM(*)"] as $pos => $bomber)
  if ($edit == "CHERNOM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaCHERTAN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Чертаново Москва</b><br /><br />
      город: <b>Москва</b><br /><br />
      год основания: <b></b><br /><br />
      арена: <b>Стадион "Салют"</b><br />
      вместительность: <b>2500</b><br /><br />
      <a href="http://www.chertanovo-football.ru" target="_blank">http://www.chertanova-football.ru</a>
<?php if ($edit == "CHERTAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHERTAN"] as $pos => $bomber)
  if ($edit == "CHERTAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERTAN"] as $pos => $bomber)
  if ($edit == "CHERTAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERTAN(*)"] as $pos => $bomber)
  if ($edit == "CHERTAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERTAN(*)"] as $pos => $bomber)
  if ($edit == "CHERTAN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/russiaSHINNIK.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Шинник Ярославль</b><br /><br />
      город: <b>Ярославль</b><br /><br />
      год основания: <b>1957</b><br /><br />
      арена: <b>Стадион "Шинник"</b><br />
      вместительность: <b>22871</b><br /><br />
      <a href="http://www.shinnik.com" target="_blank">http://www.shinnik.com</a>
<?php if ($edit == "SHINNIK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SHINNIK"] as $pos => $bomber)
  if ($edit == "SHINNIK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHINNIK"] as $pos => $bomber)
  if ($edit == "SHINNIK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHINNIK(*)"] as $pos => $bomber)
  if ($edit == "SHINNIK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHINNIK(*)"] as $pos => $bomber)
  if ($edit == "SHINNIK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
