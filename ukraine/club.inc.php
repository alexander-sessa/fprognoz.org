<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/ukraineARSENAL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Арсенал Киев</b><br /><br />
      город: <b>Киев</b><br /><br />
      год основания: <b>1934</b><br /><br />
      арена: <b>Стадион "Динамо" им. Валерия Лобановского</b><br />
      вместительность: <b>17000</b><br /><br />
      <a href="http://arsenal-kyiv.com" target="_blank">http://arsenal-kyiv.com</a>
<?php if ($edit == "ARSENALK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ARSENALK"] as $pos => $bomber)
  if ($edit == "ARSENALK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALK"] as $pos => $bomber)
  if ($edit == "ARSENALK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALK(*)"] as $pos => $bomber)
  if ($edit == "ARSENALK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARSENALK(*)"] as $pos => $bomber)
  if ($edit == "ARSENALK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineVOLYN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Волынь Луцк</b><br /><br />
      город: <b>Луцк</b><br /><br />
      год основания: <b>1960</b><br /><br />
      арена: <b>Стадион "Авангард"</b><br />
      вместительность: <b>12080</b><br /><br />
      <a href="http://www.fcvolyn.net" target="_blank">http://www.fcvolyn.net</a>
<?php if ($edit == "VOLYN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VOLYN"] as $pos => $bomber)
  if ($edit == "VOLYN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLYN"] as $pos => $bomber)
  if ($edit == "VOLYN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLYN(*)"] as $pos => $bomber)
  if ($edit == "VOLYN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VOLYN(*)"] as $pos => $bomber)
  if ($edit == "VOLYN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineDIN-K.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Динамо Киев</b><br /><br />
      город: <b>Киев</b><br /><br />
      год основания: <b>1927</b><br /><br />
      арена: <b>НСК "Олимпийский"</b><br />
      вместительность: <b>70050</b><br /><br />
      <a href="http://www.fcdynamo.kiev.ua" target="_blank">http://www.fcdynamo.kiev.ua</a>
<?php if ($edit == "DIN-K") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DIN-K"] as $pos => $bomber)
  if ($edit == "DIN-K")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-K"] as $pos => $bomber)
  if ($edit == "DIN-K")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-K(*)"] as $pos => $bomber)
  if ($edit == "DIN-K")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DIN-K(*)"] as $pos => $bomber)
  if ($edit == "DIN-K")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineDNEPR.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Днепр Днепропетровск</b><br /><br />
      город: <b>Днепропетровск</b><br /><br />
      год основания: <b>1918</b><br /><br />
      арена: <b>Днепр Арена</b><br />
      вместительность: <b>31003</b><br /><br />
      <a href="http://www.fcdnipro.dp.ua" target="_blank">http://www.fcdnipro.dp.ua</a>
<?php if ($edit == "DNEPR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DNEPR"] as $pos => $bomber)
  if ($edit == "DNEPR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DNEPR"] as $pos => $bomber)
  if ($edit == "DNEPR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DNEPR(*)"] as $pos => $bomber)
  if ($edit == "DNEPR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DNEPR(*)"] as $pos => $bomber)
  if ($edit == "DNEPR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineVORSKLA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ворскла Полтава</b><br /><br />
      город: <b>Полтава</b><br /><br />
      год основания: <b>1955</b><br /><br />
      арена: <b>Стадион "Ворскла" им. Алексея Бутовского</b><br />
      вместительность: <b>24795</b><br /><br />
      <a href="http://www.vorskla.com.ua/" target="_blank">http://www.vorskla.com.ua/</a>
<?php if ($edit == "VORSKLA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VORSKLA"] as $pos => $bomber)
  if ($edit == "VORSKLA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VORSKLA"] as $pos => $bomber)
  if ($edit == "VORSKLA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VORSKLA(*)"] as $pos => $bomber)
  if ($edit == "VORSKLA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VORSKLA(*)"] as $pos => $bomber)
  if ($edit == "VORSKLA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineZARYA-L.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Заря Луганск</b><br /><br />
      город: <b>Луганск</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Стадион "Авангард"</b><br />
      вместительность: <b>22288</b><br /><br />
      <a href="http://www.zarya-lugansk.com" target="_blank">http://www.zarya-lugansk.com</a>
<?php if ($edit == "ZARYA-L") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZARYA-L"] as $pos => $bomber)
  if ($edit == "ZARYA-L")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARYA-L"] as $pos => $bomber)
  if ($edit == "ZARYA-L")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARYA-L(*)"] as $pos => $bomber)
  if ($edit == "ZARYA-L")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARYA-L(*)"] as $pos => $bomber)
  if ($edit == "ZARYA-L")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineZIRKA.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Зирка Кропивницкий</b><br /><br />
      город: <b>Кропивницкий</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Стадион "Зирка"</b><br />
      вместительность: <b>14628</b><br /><br />
      <a href="http://www.fczirka.com.ua" target="_blank">http://www.fczirka.com.ua</a>
<?php if ($edit == "ZIRKA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZIRKA"] as $pos => $bomber)
  if ($edit == "ZIRKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZIRKA"] as $pos => $bomber)
  if ($edit == "ZIRKA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZIRKA(*)"] as $pos => $bomber)
  if ($edit == "ZIRKA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZIRKA(*)"] as $pos => $bomber)
  if ($edit == "ZIRKA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineILLICH.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Ильичёвец Мариуполь</b><br /><br />
      город: <b>Мариуполь</b><br /><br />
      год основания: <b>1938</b><br /><br />
      арена: <b>Стадион "Ильичёвец"</b><br />
      вместительность: <b>12680</b><br /><br />
      <a href="http://www.fcilich.com" target="_blank">http://www.fcilich.com</a>
<?php if ($edit == "ILLICH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ILLICH"] as $pos => $bomber)
  if ($edit == "ILLICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ILLICH"] as $pos => $bomber)
  if ($edit == "ILLICH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo mb_substr($bomber, 0, 26).'<br />';
?>
    </td>
    <td>
<?php foreach ($ab["ILLICH(*)"] as $pos => $bomber)
  if ($edit == "ILLICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ILLICH(*)"] as $pos => $bomber)
  if ($edit == "ILLICH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineKARPATY.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Караты Львов</b><br /><br />
      город: <b>Львов</b><br /><br />
      год основания: <b>1963</b><br /><br />
      арена: <b>Стадион "Украина"</b><br />
      вместительность: <b>28051</b><br /><br />
      <a href="http://www.fckarpaty.lviv.ua" target="_blank">http://www.fckarpaty.lviv.ua</a>
<?php if ($edit == "KARPATY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KARPATY"] as $pos => $bomber)
  if ($edit == "KARPATY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARPATY"] as $pos => $bomber)
  if ($edit == "KARPATY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARPATY(*)"] as $pos => $bomber)
  if ($edit == "KARPATY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARPATY(*)"] as $pos => $bomber)
  if ($edit == "KARPATY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineKRIVBAS.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Кривбас Кривой Рог</b><br /><br />
      город: <b>Кривой Рог</b><br /><br />
      год основания: <b>1959</b><br /><br />
      арена: <b>Стадион "Металлург"</b><br />
      вместительность: <b>29783</b><br /><br />
      <a href="http://www.fckryvbas.com.ua/" target="_blank">http://www.fckryvbas.com.ua/</a>
<?php if ($edit == "KRIVBAS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KRIVBAS"] as $pos => $bomber)
  if ($edit == "KRIVBAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRIVBAS"] as $pos => $bomber)
  if ($edit == "KRIVBAS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRIVBAS(*)"] as $pos => $bomber)
  if ($edit == "KRIVBAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KRIVBAS(*)"] as $pos => $bomber)
  if ($edit == "KRIVBAS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineMETAL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Металист Харьков</b><br /><br />
      город: <b>Харьков</b><br /><br />
      год основания: <b>1925</b><br /><br />
      арена: <b>Областной спорткомплекс "Металист"</b><br />
      вместительность: <b>41307</b><br /><br />
      <a href="http://www.metalist.ua/" target="_blank">http://www.metalist.ua/</a>
<?php if ($edit == "METAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["METAL"] as $pos => $bomber)
  if ($edit == "METAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METAL"] as $pos => $bomber)
  if ($edit == "METAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METAL(*)"] as $pos => $bomber)
  if ($edit == "METAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METAL(*)"] as $pos => $bomber)
  if ($edit == "METAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineMET-D.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Металлург Донецк</b><br /><br />
      город: <b>Донецк</b><br /><br />
      год основания: <b>1996</b><br /><br />
      арена: <b>Стадион "Металлург"</b><br />
      вместительность: <b>5194</b><br /><br />
      <a href="http://metallurg.donetsk.ua/" target="_blank">http://metallurg.donetsk.ua/</a>
<?php if ($edit == "MET-D") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MET-D"] as $pos => $bomber)
  if ($edit == "MET-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-D"] as $pos => $bomber)
  if ($edit == "MET-D")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-D(*)"] as $pos => $bomber)
  if ($edit == "MET-D")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MET-D(*)"] as $pos => $bomber)
  if ($edit == "MET-D")
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
      <a href="http://http://www.fcmetalurg.com/" target="_blank">http://www.fcmetalurg.com/</a>
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
      <a href="http://fcniva.vn.ua" target="_blank">http://fcniva.vn.ua</a>
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
    <td><br /><img src="images/ukraineNIKOLAEV.png" height="150" width="150" alt="" /></td>
    <td>
      <br />
      <b>МФК Николаев</b><br /><br />
      город: <b>Николаев</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Центральный городской стадион</b><br />
      вместительность: <b>25175</b><br /><br />
      <a href="http://mfc.mk.ua" target="_blank">http://mfc.mk.ua</a>
<?php if ($edit == "NIKOLAEV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NIKOLAEV"] as $pos => $bomber)
  if ($edit == "NIKOLAEV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIKOLAEV"] as $pos => $bomber)
  if ($edit == "NIKOLAEV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIKOLAEV(*)"] as $pos => $bomber)
  if ($edit == "NIKOLAEV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NIKOLAEV(*)"] as $pos => $bomber)
  if ($edit == "NIKOLAEV")
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
      <a href="http://olimpik.com.ua" target="_blank">http://olimpik.com.ua</a>
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
      <a href="http://www.facebook.com/soccerway" target="_blank">http://www.facebook.com/soccerway</a>
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
      <a href="http://www.fcsevastopol.com" target="_blank">http://www.fcsevastopol.com</a>
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
    <td><br /><img src="images/ukraineSTAL.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Сталь Днепродзержинск</b><br /><br />
      город: <b>Днепродзержинск</b><br /><br />
      год основания: <b>1926</b><br /><br />
      арена: <b>Стадион "Металлург"</b><br />
      вместительность: <b>2900</b><br /><br />
      <a href="http://www.fcstal.com.ua" target="_blank">http://www.fcstal.com.ua</a>
<?php if ($edit == "STAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STAL"] as $pos => $bomber)
  if ($edit == "STAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STAL"] as $pos => $bomber)
  if ($edit == "STAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STAL(*)"] as $pos => $bomber)
  if ($edit == "STAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STAL(*)"] as $pos => $bomber)
  if ($edit == "STAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/ukraineCHERN.png" alt="" /></td>
    <td>
      <br />
      <b>ФК Черноморец Одесса</b><br /><br />
      город: <b>Одесса</b><br /><br />
      год основания: <b>1936</b><br /><br />
      арена: <b>Стадион "Черноморец"</b><br />
      вместительность: <b>34164</b><br /><br />
      <a href="http://www.chernomorets.odessa.ua" target="_blank">http://www.chernomorets.odessa.ua</a>
<?php if ($edit == "CHERN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHERN"] as $pos => $bomber)
  if ($edit == "CHERN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERN"] as $pos => $bomber)
  if ($edit == "CHERN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERN(*)"] as $pos => $bomber)
  if ($edit == "CHERN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHERN(*)"] as $pos => $bomber)
  if ($edit == "CHERN")
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
      <a href="http://www.shakhtar.com" target="_blank">http://www.shakhtar.com</a>
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
