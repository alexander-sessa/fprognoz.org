<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
    <tr>
    <td><br /><img src="images/italyATALANTA.png" alt="" /></td>
    <td>
      <br />
      <b>Atalanta BC</b><br /><br />
      город: <b>Bergamo</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Stadio Atleti Azzurri d'Italia</b><br />
      вместительность: <b>24726</b><br /><br />
      <a href="http://www.atalanta.it" target="_blank">http://www.atalanta.it</a>
<?php if ($edit == "ATALANTA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ATALANTA"] as $pos => $bomber)
  if ($edit == "ATALANTA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATALANTA"] as $pos => $bomber)
  if ($edit == "ATALANTA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATALANTA(*)"] as $pos => $bomber)
  if ($edit == "ATALANTA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATALANTA(*)"] as $pos => $bomber)
  if ($edit == "ATALANTA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyASCOLI.png" alt="" /></td>
    <td>
      <br />
      <b>Ascoli Calcio 1898</b><br /><br />
      город: <b>Ascoli Piceno</b><br /><br />
      год основания: <b>1898</b><br /><br />
      арена: <b>Stadio Cino e Lillo Del Duca</b><br />
      вместительность: <b>20853</b><br /><br />
      <a href="http://www.ascolicalcio.net" target="_blank">http://www.ascolicalcio.net</a>
<?php if ($edit == "ASCOLI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ASCOLI"] as $pos => $bomber)
  if ($edit == "ASCOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASCOLI"] as $pos => $bomber)
  if ($edit == "ASCOLI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASCOLI(*)"] as $pos => $bomber)
  if ($edit == "ASCOLI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASCOLI(*)"] as $pos => $bomber)
  if ($edit == "ASCOLI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyAVELLINO.png" alt="" /></td>
    <td>
      <br />
      <b>AS Avellino</b><br /><br />
      город: <b>Avellino</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Stadio Partenio</b><br />
      вместительность: <b>10215</b><br /><br />
      <a href="http://www.asavellino.com/" target="_blank">http://www.asavellino.com/</a>
<?php if ($edit == "AVELLINO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AVELLINO"] as $pos => $bomber)
  if ($edit == "AVELLINO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVELLINO"] as $pos => $bomber)
  if ($edit == "AVELLINO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVELLINO(*)"] as $pos => $bomber)
  if ($edit == "AVELLINO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AVELLINO(*)"] as $pos => $bomber)
  if ($edit == "AVELLINO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyBARI.png" alt="" /></td>
    <td>
      <br />
      <b>AS Bari</b><br /><br />
      город: <b>Bari</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>Stadio Comunale San Nicola</b><br />
      вместительность: <b>58270</b><br /><br />
      <a href="http://www.asbari.it" target="_blank">http://www.asbari.it</a>
<?php if ($edit == "BARI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BARI"] as $pos => $bomber)
  if ($edit == "BARI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARI"] as $pos => $bomber)
  if ($edit == "BARI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARI(*)"] as $pos => $bomber)
  if ($edit == "BARI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BARI(*)"] as $pos => $bomber)
  if ($edit == "BARI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyBOLOGNA.png" alt="" /></td>
    <td>
      <br />
      <b>Bologna FC 1909</b><br /><br />
      город: <b>Bologna</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Stadio Renato Dall'Ara</b><br />
      вместительность: <b>36532</b><br /><br />
      <a href="http://www.bolognafc.it" target="_blank">http://www.bolognafc.it</a>
<?php if ($edit == "BOLOGNA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOLOGNA"] as $pos => $bomber)
  if ($edit == "BOLOGNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLOGNA"] as $pos => $bomber)
  if ($edit == "BOLOGNA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLOGNA(*)"] as $pos => $bomber)
  if ($edit == "BOLOGNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLOGNA(*)"] as $pos => $bomber)
  if ($edit == "BOLOGNA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyBRESCIA.png" alt="" /></td>
    <td>
      <br />
      <b>Brescia Calcio</b><br /><br />
      город: <b>Brescia</b><br /><br />
      год основания: <b>1911</b><br /><br />
      арена: <b>Stadio Mario Rigamonti</b><br />
      вместительность: <b>27547</b><br /><br />
      <a href="http://www.bresciacalcio.it" target="_blank">http://www.bresciacalcio.it</a>
<?php if ($edit == "BRESCIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRESCIA"] as $pos => $bomber)
  if ($edit == "BRESCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRESCIA"] as $pos => $bomber)
  if ($edit == "BRESCIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRESCIA(*)"] as $pos => $bomber)
  if ($edit == "BRESCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRESCIA(*)"] as $pos => $bomber)
  if ($edit == "BRESCIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyCAGLIARI.png" alt="" /></td>
    <td>
      <br />
      <b>Cagliari Calcio</b><br /><br />
      город: <b>Cagliari</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Stadio Is Arenas</b><br />
      вместительность: <b>16200</b><br /><br />
      <a href="http://www.cagliaricalcio.net" target="_blank">http://www.cagliaricalcio.net</a>
<?php if ($edit == "CAGLIARI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CAGLIARI"] as $pos => $bomber)
  if ($edit == "CAGLIARI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAGLIARI"] as $pos => $bomber)
  if ($edit == "CAGLIARI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAGLIARI(*)"] as $pos => $bomber)
  if ($edit == "CAGLIARI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAGLIARI(*)"] as $pos => $bomber)
  if ($edit == "CAGLIARI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyCATANIA.png" alt="" /></td>
    <td>
      <br />
      <b>Calcio Catania</b><br /><br />
      город: <b>Catania</b><br /><br />
      год основания: <b>1908 / 1946</b><br /><br />
      арена: <b>Stadio Angelo Massimino</b><br />
      вместительность: <b>23420</b><br /><br />
      <a href="http://www.ilcalciocatania.it/" target="_blank">http://www.ilcalciocatania.it/</a>
<?php if ($edit == "CATANIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CATANIA"] as $pos => $bomber)
  if ($edit == "CATANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CATANIA"] as $pos => $bomber)
  if ($edit == "CATANIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CATANIA(*)"] as $pos => $bomber)
  if ($edit == "CATANIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CATANIA(*)"] as $pos => $bomber)
  if ($edit == "CATANIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyCESENA.png" alt="" /></td>
    <td>
      <br />
      <b>AC Cesena</b><br /><br />
      город: <b>Cesena</b><br /><br />
      год основания: <b>1940</b><br /><br />
      арена: <b>Stadio Dino Manuzzi</b><br />
      вместительность: <b>24026</b><br /><br />
      <a href="http://www.cesenacalcio.it" target="_blank">http://www.cesenacalcio.it</a>
<?php if ($edit == "CESENA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CESENA"] as $pos => $bomber)
  if ($edit == "CESENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CESENA"] as $pos => $bomber)
  if ($edit == "CESENA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CESENA(*)"] as $pos => $bomber)
  if ($edit == "CESENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CESENA(*)"] as $pos => $bomber)
  if ($edit == "CESENA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyCHIEVO.png" alt="" /></td>
    <td>
      <br />
      <b>AC Chievo Verona</b><br /><br />
      город: <b>Verona</b><br /><br />
      год основания: <b>1929</b><br /><br />
      арена: <b>Stadio Marc'Antonio Bentegodi</b><br />
      вместительность: <b>44799</b><br /><br />
      <a href="http://www.chievoverona.it" target="_blank">http://www.chievoverona.it</a>
<?php if ($edit == "CHIEVO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHIEVO"] as $pos => $bomber)
  if ($edit == "CHIEVO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHIEVO"] as $pos => $bomber)
  if ($edit == "CHIEVO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHIEVO(*)"] as $pos => $bomber)
  if ($edit == "CHIEVO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHIEVO(*)"] as $pos => $bomber)
  if ($edit == "CHIEVO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
   <tr>
    <td><br /><img src="images/italyCOMO.png" alt="" /></td>
    <td>
      <br />
      <b>Calcio Como</b><br /><br />
      город: <b>Como</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Stadio Giuseppe Sinigaglia</b><br />
      вместительность: <b>13602</b><br /><br />
      <a href="http://www.calciocomo1907.it" target="_blank">http://www.calciocomo1907.it</a>
<?php if ($edit == "COMO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["COMO"] as $pos => $bomber)
  if ($edit == "COMO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["COMO"] as $pos => $bomber)
  if ($edit == "COMO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["COMO(*)"] as $pos => $bomber)
  if ($edit == "COMO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["COMO(*)"] as $pos => $bomber)
  if ($edit == "COMO")
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
      <a href="http://www.empolicalcio.it" target="_blank">http://www.empolicalcio.it</a>
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
    <td><br /><img src="images/italyFIORE.png" alt="" /></td>
    <td>
      <br />
      <b>ACF Fiorentina</b><br /><br />
      город: <b>Firenze</b><br /><br />
      год основания: <b>1926</b><br /><br />
      арена: <b>Stadio Artemio Franchi</b><br />
      вместительность: <b>47290</b><br /><br />
      <a href="http://it.violachannel.tv" target="_blank">http://it.violachannel.tv</a>
<?php if ($edit == "FIORE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FIORE"] as $pos => $bomber)
  if ($edit == "FIORE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FIORE"] as $pos => $bomber)
  if ($edit == "FIORE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FIORE(*)"] as $pos => $bomber)
  if ($edit == "FIORE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FIORE(*)"] as $pos => $bomber)
  if ($edit == "FIORE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyFOGGIA.png" alt="" /></td>
    <td>
      <br />
      <b>ACD Foggia Calcio</b><br /><br />
      город: <b>Foggia</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Stadio Comunale Pino Zaccheria</b><br />
      вместительность: <b>25085</b><br /><br />
      <a href="http://www.acdfoggiacalcio.it/" target="_blank">http://www.acdfoggiacalcio.it/</a>
<?php if ($edit == "FOGGIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FOGGIA"] as $pos => $bomber)
  if ($edit == "FOGGIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FOGGIA"] as $pos => $bomber)
  if ($edit == "FOGGIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FOGGIA(*)"] as $pos => $bomber)
  if ($edit == "FOGGIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FOGGIA(*)"] as $pos => $bomber)
  if ($edit == "FOGGIA")
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
      <a href="http://www.genoacfc.it" target="_blank">http://www.genoacfc.it</a>
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
    <td><br /><img src="images/italyHELLAS.png" alt="" /></td>
    <td>
      <br />
      <b>Hellas Verona FC</b><br /><br />
      город: <b>Verona</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Stadio Marc'Antonio Bentegodi</b><br />
      вместительность: <b>44799</b><br /><br />
      <a href="http://www.hellasverona.it" target="_blank">http://www.hellasverona.it</a>
<?php if ($edit == "HELLAS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HELLAS"] as $pos => $bomber)
  if ($edit == "HELLAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HELLAS"] as $pos => $bomber)
  if ($edit == "HELLAS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HELLAS(*)"] as $pos => $bomber)
  if ($edit == "HELLAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HELLAS(*)"] as $pos => $bomber)
  if ($edit == "HELLAS")
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
      <a href="http://www.inter.it" target="_blank">http://www.inter.it</a>
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
    <td><br /><img src="images/italyJUVENTUS.png" alt="" /></td>
    <td>
      <br />
      <b>Juventus FC</b><br /><br />
      город: <b>Torino</b><br /><br />
      год основания: <b>1897</b><br /><br />
      арена: <b>Juventus Stadium</b><br />
      вместительность: <b>45666</b><br /><br />
      <a href="http://www.juventus.com" target="_blank">http://www.juventus.com</a>
<?php if ($edit == "JUVENTUS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["JUVENTUS"] as $pos => $bomber)
  if ($edit == "JUVENTUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVENTUS"] as $pos => $bomber)
  if ($edit == "JUVENTUS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVENTUS(*)"] as $pos => $bomber)
  if ($edit == "JUVENTUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVENTUS(*)"] as $pos => $bomber)
  if ($edit == "JUVENTUS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyJUVEST.png" alt="" /></td>
    <td>
      <br />
      <b>SS Juve Stabia</b><br /><br />
      город: <b>Castellammare di Stabia</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Stadio Comunale Romeo Menti</b><br />
      вместительность: <b>7642</b><br /><br />
      <a href="http://www.ssjuvestabia.it/" target="_blank">http://www.ssjuvestabia.it/</a>
<?php if ($edit == "JUVEST") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["JUVEST"] as $pos => $bomber)
  if ($edit == "JUVEST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVEST"] as $pos => $bomber)
  if ($edit == "JUVEST")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVEST(*)"] as $pos => $bomber)
  if ($edit == "JUVEST")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["JUVEST(*)"] as $pos => $bomber)
  if ($edit == "JUVEST")
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
      <a href="http://www.sslazio.it" target="_blank">http://www.sslazio.it</a>
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
    <td><br /><img src="images/italyLECCE.png" alt="" /></td>
    <td>
      <br />
      <b>US Lecce</b><br /><br />
      город: <b>Lecce</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>Stadio Comunale Via del Mare</b><br />
      вместительность: <b>33876</b><br /><br />
      <a href="http://www.uslecce.it" target="_blank">http://www.uslecce.it</a>
<?php if ($edit == "LECCE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LECCE"] as $pos => $bomber)
  if ($edit == "LECCE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LECCE"] as $pos => $bomber)
  if ($edit == "LECCE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LECCE(*)"] as $pos => $bomber)
  if ($edit == "LECCE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LECCE(*)"] as $pos => $bomber)
  if ($edit == "LECCE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyLIVORNO.png" alt="" /></td>
    <td>
      <br />
      <b>AS Livorno Calcio</b><br /><br />
      город: <b>Livorno</b><br /><br />
      год основания: <b>1915</b><br /><br />
      арена: <b>Stadio Armando Picchi</b><br />
      вместительность: <b>19238</b><br /><br />
      <a href="http://www.livornocalcio.it" target="_blank">http://www.livornocalcio.it</a>
<?php if ($edit == "LIVORNO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LIVORNO"] as $pos => $bomber)
  if ($edit == "LIVORNO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVORNO"] as $pos => $bomber)
  if ($edit == "LIVORNO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVORNO(*)"] as $pos => $bomber)
  if ($edit == "LIVORNO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVORNO(*)"] as $pos => $bomber)
  if ($edit == "LIVORNO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyMILAN.png" alt="" /></td>
    <td>
      <br />
      <b>AC Milan</b><br /><br />
      город: <b>Milano</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Stadio Giuseppe Meazza</b><br />
      вместительность: <b>82995</b><br /><br />
      <a href="http://www.acmilan.com" target="_blank">http://www.acmilan.com</a>
<?php if ($edit == "MILAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MILAN"] as $pos => $bomber)
  if ($edit == "MILAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MILAN"] as $pos => $bomber)
  if ($edit == "MILAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MILAN(*)"] as $pos => $bomber)
  if ($edit == "MILAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MILAN(*)"] as $pos => $bomber)
  if ($edit == "MILAN")
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
      <a href="http://www.modenafc.net" target="_blank">http://www.modenafc.net</a>
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
      <a href="http://www.sscnapoli.it" target="_blank">http://www.sscnapoli.it</a>
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
    <td><br /><img src="images/italyNOVARA.png" alt="" /></td>
    <td>
      <br />
      <b>Novara Calcio</b><br /><br />
      город: <b>Novara</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>Stadio Comunale Silvio Piola</b><br />
      вместительность: <b>17875</b><br /><br />
      <a href="http://www.novaracalcio.com" target="_blank">http://www.novaracalcio.com</a>
<?php if ($edit == "NOVARA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NOVARA"] as $pos => $bomber)
  if ($edit == "NOVARA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOVARA"] as $pos => $bomber)
  if ($edit == "NOVARA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOVARA(*)"] as $pos => $bomber)
  if ($edit == "NOVARA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOVARA(*)"] as $pos => $bomber)
  if ($edit == "NOVARA")
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
      <a href="http://www.ilpalermocalcio.it" target="_blank">http:http://www.ilpalermocalcio.it</a>
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
    <td><br /><img src="images/italyPARMA.png" alt="" /></td>
    <td>
      <br />
      <b>Parma FC</b><br /><br />
      город: <b>Parma</b><br /><br />
      год основания: <b>1913 / 2004</b><br /><br />
      арена: <b>Stadio Ennio Tardini</b><br />
      вместительность: <b>28783</b><br /><br />
      <a href="http://www.fcparma.com" target="_blank">http://www.fcparma.com</a>
<?php if ($edit == "PARMA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PARMA"] as $pos => $bomber)
  if ($edit == "PARMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARMA"] as $pos => $bomber)
  if ($edit == "PARMA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARMA(*)"] as $pos => $bomber)
  if ($edit == "PARMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARMA(*)"] as $pos => $bomber)
  if ($edit == "PARMA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyPISA.png" alt="" /></td>
    <td>
      <br />
      <b>Pisa Calcio</b><br /><br />
      город: <b>Pisa</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Arena Garibaldi - Stadio Romeo Anconetani</b><br />
      вместительность: <b>17500</b><br /><br />
      <a href="http://www.acpisa1909.it" target="_blank">http://www.acpisa1909.it</a>
<?php if ($edit == "PISA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PISA"] as $pos => $bomber)
  if ($edit == "PISA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PISA"] as $pos => $bomber)
  if ($edit == "PISA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PISA(*)"] as $pos => $bomber)
  if ($edit == "PISA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PISA(*)"] as $pos => $bomber)
  if ($edit == "PISA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyRIMINI.png" alt="" /></td>
    <td>
      <br />
      <b>Rimini Calcio FC</b><br /><br />
      город: <b>Rimini</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Stadio Romeo Neri</b><br />
      вместительность: <b>9798</b><br /><br />
      <a href="http://www.riminicalcio.com" target="_blank">http://www.riminicalcio.com</a>
<?php if ($edit == "RIMINI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RIMINI"] as $pos => $bomber)
  if ($edit == "RIMINI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIMINI"] as $pos => $bomber)
  if ($edit == "RIMINI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIMINI(*)"] as $pos => $bomber)
  if ($edit == "RIMINI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIMINI(*)"] as $pos => $bomber)
  if ($edit == "RIMINI")
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
      <a href="http://www.asroma.it" target="_blank">http://www.asroma.it</a>
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
    <td><br /><img src="images/italySASSUOLO.png" alt="" /></td>
    <td>
      <br />
      <b>US Sassuolo Calcio</b><br /><br />
      город: <b>Sassuolo</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Stadio Città del Tricolore</b><br />
      вместительность: <b>29546</b><br /><br />
      <a href="http://www.sassuolocalcio.it/" target="_blank">http://www.sassuolocalcio.it/</a>
<?php if ($edit == "SASSUOLO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SASSUOLO"] as $pos => $bomber)
  if ($edit == "SASSUOLO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SASSUOLO"] as $pos => $bomber)
  if ($edit == "SASSUOLO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SASSUOLO(*)"] as $pos => $bomber)
  if ($edit == "SASSUOLO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SASSUOLO(*)"] as $pos => $bomber)
  if ($edit == "SASSUOLO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italySALER.png" alt="" /></td>
    <td>
      <br />
      <b>US Salernitana 1919</b><br /><br />
      город: <b>Salerno</b><br /><br />
      год основания: <b>2011</b><br /><br />
      арена: <b>Stadio Arechi</b><br />
      вместительность: <b>37245</b><br /><br />
      <a href="http://www.salernocalcio1919.it/" target="_blank">http://www.salernocalcio1919.it/</a>
<?php if ($edit == "SALER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SALER"] as $pos => $bomber)
  if ($edit == "SALER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SALER"] as $pos => $bomber)
  if ($edit == "SALER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SALER(*)"] as $pos => $bomber)
  if ($edit == "SALER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SALER(*)"] as $pos => $bomber)
  if ($edit == "SALER")
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
      <a href="http://www.sampdoria.it" target="_blank">http://www.sampdoria.it</a>
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
    <td><br /><img src="images/italySIENA.png" alt="" /></td>
    <td>
      <br />
      <b>Robur Siena S.S.D.</b><br /><br />
      город: <b>Siena</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Stadio Comunale Artemio Franchi - Montepaschi Arena</b><br />
      вместительность: <b>15373</b><br /><br />
      <a href="http://www.robursiena.it" target="_blank">http://www.robursiena.it</a>
<?php if ($edit == "SIENA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SIENA"] as $pos => $bomber)
  if ($edit == "SIENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIENA"] as $pos => $bomber)
  if ($edit == "SIENA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIENA(*)"] as $pos => $bomber)
  if ($edit == "SIENA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SIENA(*)"] as $pos => $bomber)
  if ($edit == "SIENA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italySPAL.png" alt="" /></td>
    <td>
      <br />
      <b>Società Polisportiva Ars et Labor</b><br /><br />
      город: <b>Ferrara</b><br /><br />
      год основания: <b>1907/2013</b><br /><br />
      арена: <b>Stadio Paolo Mazza</b><br />
      вместительность: <b>19000</b><br /><br />
      <a href="http://www.spalferrara.it" target="_blank">http://www.spalferrara.it</a>
<?php if ($edit == "SPAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SPAL"] as $pos => $bomber)
  if ($edit == "SPAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPAL"] as $pos => $bomber)
  if ($edit == "SPAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPAL(*)"] as $pos => $bomber)
  if ($edit == "SPAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPAL(*)"] as $pos => $bomber)
  if ($edit == "SPAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyTERNANA.png" alt="" /></td>
    <td>
      <br />
      <b>Ternana Calcio</b><br /><br />
      город: <b>Terni</b><br /><br />
      год основания: <b>1925</b><br /><br />
      арена: <b>Stadio Libero Liberati</b><br />
      вместительность: <b>17460</b><br /><br />
      <a href="http://www.ternanacalcio.com" target="_blank">http://www.ternanacalcio.com</a>
<?php if ($edit == "TERNANA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TERNANA"] as $pos => $bomber)
  if ($edit == "TERNANA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TERNANA"] as $pos => $bomber)
  if ($edit == "TERNANA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TERNANA(*)"] as $pos => $bomber)
  if ($edit == "TERNANA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TERNANA(*)"] as $pos => $bomber)
  if ($edit == "TERNANA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyTORINO.png" alt="" /></td>
    <td>
      <br />
      <b>Torino FC</b><br /><br />
      город: <b>Torino</b><br /><br />
      год основания: <b>1906</b><br /><br />
      арена: <b>Stadio Olimpico di Torino</b><br />
      вместительность: <b>27994</b><br /><br />
      <a href="http://www.torinofc.it" target="_blank">http://www.torinofc.it</a>
<?php if ($edit == "TORINO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TORINO"] as $pos => $bomber)
  if ($edit == "TORINO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORINO"] as $pos => $bomber)
  if ($edit == "TORINO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORINO(*)"] as $pos => $bomber)
  if ($edit == "TORINO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TORINO(*)"] as $pos => $bomber)
  if ($edit == "TORINO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/italyUDINESE.png" alt="" /></td>
    <td>
      <br />
      <b>Udinese Calcio</b><br /><br />
      город: <b>Udine</b><br /><br />
      год основания: <b>1896</b><br /><br />
      арена: <b>Stadio Communale Friuli</b><br />
      вместительность: <b>41652</b><br /><br />
      <a href="http://www.udinese.it" target="_blank">http://www.udinese.it</a>
<?php if ($edit == "UDINESE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["UDINESE"] as $pos => $bomber)
  if ($edit == "UDINESE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UDINESE"] as $pos => $bomber)
  if ($edit == "UDINESE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UDINESE(*)"] as $pos => $bomber)
  if ($edit == "UDINESE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UDINESE(*)"] as $pos => $bomber)
  if ($edit == "UDINESE")
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
      <b>Venezia FC</b><br /><br />
      город: <b>Mestre</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Stadio Pierluigi Penzo</b><br />
      вместительность: <b>7426</b><br /><br />
      <a href="http://www.veneziafc.club" target="_blank">http://www.veneziafc.club</a>
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
</table></form>
