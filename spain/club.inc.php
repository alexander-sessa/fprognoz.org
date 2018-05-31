<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/spainALBACETE.png" alt="" /></td>
    <td>
      <br />
      <b>Albacete Balompié</b><br /><br />
      город: <b>Albacete</b><br /><br />
      год основания: <b>1940</b><br /><br />
      арена: <b>Estadio Carlos Belmonte</b><br />
      вместительность: <b>17300</b><br /><br />
      <a href="http://www.albacetebalompie.com" target="_blank">http://www.albacetebalompie.com</a>
<?php if ($edit == "ALBACETE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ALBACETE"] as $pos => $bomber)
  if ($edit == "ALBACETE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALBACETE"] as $pos => $bomber)
  if ($edit == "ALBACETE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALBACETE(*)"] as $pos => $bomber)
  if ($edit == "ALBACETE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALBACETE(*)"] as $pos => $bomber)
  if ($edit == "ALBACETE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainALAVES.png" alt="" /></td>
    <td>
      <br />
      <b>Deportivo Alavés</b><br /><br />
      город: <b>Vitoria-Gasteiz</b><br /><br />
      год основания: <b>1921</b><br /><br />
      арена: <b>Estadio de Mendizorroza</b><br />
      вместительность: <b>19900</b><br /><br />
      <a href="http://www.alaves.com" target="_blank">http://www.alaves.com</a>
<?php if ($edit == "ALAVES") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ALAVES"] as $pos => $bomber)
  if ($edit == "ALAVES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALAVES"] as $pos => $bomber)
  if ($edit == "ALAVES")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALAVES(*)"] as $pos => $bomber)
  if ($edit == "ALAVES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALAVES(*)"] as $pos => $bomber)
  if ($edit == "ALAVES")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainALMERIA.png" alt="" /></td>
    <td>
      <br />
      <b>UD Almería</b><br /><br />
      город: <b>Almería</b><br /><br />
      год основания: <b>1989</b><br /><br />
      арена: <b>Estadio de los Juegos Mediterráneos</b><br />
      вместительность: <b>21350</b><br /><br />
      <a href="http://www.udalmeriasad.com" target="_blank">http://www.udalmeriasad.com</a>
<?php if ($edit == "ALMERIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ALMERIA"] as $pos => $bomber)
  if ($edit == "ALMERIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALMERIA"] as $pos => $bomber)
  if ($edit == "ALMERIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALMERIA(*)"] as $pos => $bomber)
  if ($edit == "ALMERIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ALMERIA(*)"] as $pos => $bomber)
  if ($edit == "ALMERIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainATHLETIC.png" alt="" /></td>
    <td>
      <br />
      <b>Athletic Club Bilbao</b><br /><br />
      город: <b>Bilbao</b><br /><br />
      год основания: <b>1898</b><br /><br />
      арена: <b>San Mamés Barria</b><br />
      вместительность: <b>53332</b><br /><br />
      <a href="http://www.athletic-club.net" target="_blank">http://www.athletic-club.net</a>
<?php if ($edit == "ATHLETIC") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ATHLETIC"] as $pos => $bomber)
  if ($edit == "ATHLETIC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATHLETIC"] as $pos => $bomber)
  if ($edit == "ATHLETIC")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATHLETIC(*)"] as $pos => $bomber)
  if ($edit == "ATHLETIC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATHLETIC(*)"] as $pos => $bomber)
  if ($edit == "ATHLETIC")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainATLETICO.png" alt="" /></td>
    <td>
      <br />
      <b>Club Atlético de Madrid</b><br /><br />
      город: <b>Madrid</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Estadio Vicente Calderón</b><br />
      вместительность: <b>54851</b><br /><br />
      <a href="http://www.clubatleticodemadrid.com" target="_blank">http://www.clubatleticodemadrid.com</a>
<?php if ($edit == "ATLETICO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ATLETICO"] as $pos => $bomber)
  if ($edit == "ATLETICO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLETICO"] as $pos => $bomber)
  if ($edit == "ATLETICO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLETICO(*)"] as $pos => $bomber)
  if ($edit == "ATLETICO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLETICO(*)"] as $pos => $bomber)
  if ($edit == "ATLETICO")
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
      <a href="http://www.fcbarcelona.com" target="_blank">http://www.fcbarcelona.com</a>
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
    <td><br /><img src="images/spainBETIS.png" alt="" /></td>
    <td>
      <br />
      <b>Real Betis Balompié</b><br /><br />
      город: <b>Sevilla</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Estadio Benito Villamarín</b><br />
      вместительность: <b>56500</b><br /><br />
      <a href="http://www.realbetisbalompie.es" target="_blank">http://www.realbetisbalompie.es</a>
<?php if ($edit == "BETIS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BETIS"] as $pos => $bomber)
  if ($edit == "BETIS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BETIS"] as $pos => $bomber)
  if ($edit == "BETIS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BETIS(*)"] as $pos => $bomber)
  if ($edit == "BETIS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BETIS(*)"] as $pos => $bomber)
  if ($edit == "BETIS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainCELTA.png" alt="" /></td>
    <td>
      <br />
      <b>Real Club Celta de Vigo</b><br /><br />
      город: <b>Vigo</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Estadio de Balaídos</b><br />
      вместительность: <b>31800</b><br /><br />
      <a href="http://www.celtavigo.net" target="_blank">http://www.celtavigo.net</a>
<?php if ($edit == "CELTA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CELTA"] as $pos => $bomber)
  if ($edit == "CELTA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTA"] as $pos => $bomber)
  if ($edit == "CELTA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTA(*)"] as $pos => $bomber)
  if ($edit == "CELTA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTA(*)"] as $pos => $bomber)
  if ($edit == "CELTA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainCORDOBA.png" alt="" /></td>
    <td>
      <br />
      <b>Córdoba CF</b><br /><br />
      город: <b>Córdoba</b><br /><br />
      год основания: <b>1954</b><br /><br />
      арена: <b>Estadio Nuevo Arcángel</b><br />
      вместительность: <b>25100</b><br /><br />
      <a href="http://www.cordobacf.com" target="_blank">http://www.cordobacf.com</a>
<?php if ($edit == "CORDOBA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CORDOBA"] as $pos => $bomber)
  if ($edit == "CORDOBA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CORDOBA"] as $pos => $bomber)
  if ($edit == "CORDOBA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CORDOBA(*)"] as $pos => $bomber)
  if ($edit == "CORDOBA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CORDOBA(*)"] as $pos => $bomber)
  if ($edit == "CORDOBA")
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
      <a href="http://www.canaldeportivo.com" target="_blank">http://www.canaldeportivo.com</a>
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
    <td><br /><img src="images/spainEIBAR.png" alt="" /></td>
    <td>
      <br />
      <b>SD Eibar</b><br /><br />
      город: <b>Eibar</b><br /><br />
      год основания: <b>1940</b><br /><br />
      арена: <b>Estadio Municipal de Ipurúa</b><br />
      вместительность: <b>6479</b><br /><br />
      <a href="http://www.sdeibar.com" target="_blank">http://www.sdeibar.com</a>
<?php if ($edit == "EIBAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["EIBAR"] as $pos => $bomber)
  if ($edit == "EIBAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EIBAR"] as $pos => $bomber)
  if ($edit == "EIBAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EIBAR(*)"] as $pos => $bomber)
  if ($edit == "EIBAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EIBAR(*)"] as $pos => $bomber)
  if ($edit == "EIBAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainESPANYOL.png" alt="" /></td>
    <td>
      <br />
      <b>Reial Club Deportiu Espanyol</b><br /><br />
      город: <b>Cornellà de Llobregat (Barcelona)</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Estadi Cornellà-El Prat</b><br />
      вместительность: <b>40423</b><br /><br />
      <a href="http://www.rcdespanyol.com" target="_blank">http://www.rcdespanyol.com</a>
<?php if ($edit == "ESPANYOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ESPANYOL"] as $pos => $bomber)
  if ($edit == "ESPANYOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESPANYOL"] as $pos => $bomber)
  if ($edit == "ESPANYOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESPANYOL(*)"] as $pos => $bomber)
  if ($edit == "ESPANYOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESPANYOL(*)"] as $pos => $bomber)
  if ($edit == "ESPANYOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainGETAFE.png" alt="" /></td>
    <td>
      <br />
      <b>Getafe Club de Fútbol</b><br /><br />
      город: <b>Getafe (Madrid)</b><br /><br />
      год основания: <b>1983</b><br /><br />
      арена: <b>Coliseum Alfonso Pérez</b><br />
      вместительность: <b>17700</b><br /><br />
      <a href="http://www.getafecf.com" target="_blank">http://www.getafecf.com</a>
<?php if ($edit == "GETAFE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GETAFE"] as $pos => $bomber)
  if ($edit == "GETAFE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GETAFE"] as $pos => $bomber)
  if ($edit == "GETAFE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GETAFE(*)"] as $pos => $bomber)
  if ($edit == "GETAFE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GETAFE(*)"] as $pos => $bomber)
  if ($edit == "GETAFE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainGIJON.png" alt="" /></td>
    <td>
      <br />
      <b>Real Sporting de Gijón</b><br /><br />
      город: <b>Gijón</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Estadio Municipal El Molinón</b><br />
      вместительность: <b>29538</b><br /><br />
      <a href="http://www.realsporting.com" target="_blank">http://www.realsporting.com</a>
<?php if ($edit == "GIJON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GIJON"] as $pos => $bomber)
  if ($edit == "GIJON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIJON"] as $pos => $bomber)
  if ($edit == "GIJON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIJON(*)"] as $pos => $bomber)
  if ($edit == "GIJON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIJON(*)"] as $pos => $bomber)
  if ($edit == "GIJON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainGIRONA.png" alt="" /></td>
    <td>
      <br />
      <b>Girona FC</b><br /><br />
      город: <b>Girona</b><br /><br />
      год основания: <b>1930</b><br /><br />
      арена: <b>Estadi Municipal de Montilivi</b><br />
      вместительность: <b>11056</b><br /><br />
      <a href="http://www.gironafutbolclub.com" target="_blank">http://www.gironafutbolclub.com</a>
<?php if ($edit == "GIRONA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GIRONA"] as $pos => $bomber)
  if ($edit == "GIRONA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIRONA"] as $pos => $bomber)
  if ($edit == "GIRONA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIRONA(*)"] as $pos => $bomber)
  if ($edit == "GIRONA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GIRONA(*)"] as $pos => $bomber)
  if ($edit == "GIRONA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainGRANADA.png" alt="" /></td>
    <td>
      <br />
      <b>Granada CF</b><br /><br />
      город: <b>Granada</b><br /><br />
      год основания: <b>1931</b><br /><br />
      арена: <b>Estadio Nuevo Los Cármenes</b><br />
      вместительность: <b>23156</b><br /><br />
      <a href="http://www.granadacf.es" target="_blank">http://www.granadacf.es</a>
<?php if ($edit == "GRANADA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GRANADA"] as $pos => $bomber)
  if ($edit == "GRANADA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANADA"] as $pos => $bomber)
  if ($edit == "GRANADA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANADA(*)"] as $pos => $bomber)
  if ($edit == "GRANADA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRANADA(*)"] as $pos => $bomber)
  if ($edit == "GRANADA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainLASPALMAS.png" alt="" /></td>
    <td>
      <br />
      <b>UD Las Palmas</b><br /><br />
      город: <b>Las Palmas de Gran Canaria</b><br /><br />
      год основания: <b>1949</b><br /><br />
      арена: <b>Estadio de Gran Canaria</b><br />
      вместительность: <b>31250</b><br /><br />
      <a href="http://www.udlaspalmas.es" target="_blank">http://www.udlaspalmas.es</a>
<?php if ($edit == "LASPALMAS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LASPALMAS"] as $pos => $bomber)
  if ($edit == "LASPALMAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LASPALMAS"] as $pos => $bomber)
  if ($edit == "LASPALMAS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LASPALMAS(*)"] as $pos => $bomber)
  if ($edit == "LASPALMAS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LASPALMAS(*)"] as $pos => $bomber)
  if ($edit == "LASPALMAS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainLEGANES.png" alt="" /></td>
    <td>
      <br />
      <b>CD Leganés</b><br /><br />
      город: <b>Leganés</b><br /><br />
      год основания: <b>1928</b><br /><br />
      арена: <b>Estadio Municipal de Butarque</b><br />
      вместительность: <b>10958</b><br /><br />
      <a href="http://www.deportivoleganes.com" target="_blank">http://www.deportivoleganes.com</a>
<?php if ($edit == "LEGANES") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LEGANES"] as $pos => $bomber)
  if ($edit == "LEGANES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEGANES"] as $pos => $bomber)
  if ($edit == "LEGANES")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEGANES(*)"] as $pos => $bomber)
  if ($edit == "LEGANES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEGANES(*)"] as $pos => $bomber)
  if ($edit == "LEGANES")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainLEVANTE.png" alt="" /></td>
    <td>
      <br />
      <b>Levante UD</b><br /><br />
      город: <b>Valencia</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Estadio Ciudad de Valencia</b><br />
      вместительность: <b>25534</b><br /><br />
      <a href="http://www.levanteud.com" target="_blank">http://www.levanteud.com</a>
<?php if ($edit == "LEVANTE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LEVANTE"] as $pos => $bomber)
  if ($edit == "LEVANTE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEVANTE"] as $pos => $bomber)
  if ($edit == "LEVANTE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEVANTE(*)"] as $pos => $bomber)
  if ($edit == "LEVANTE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEVANTE(*)"] as $pos => $bomber)
  if ($edit == "LEVANTE")
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
      <a href="http://www.malagacf.com" target="_blank">http://www.malagacf.com</a>
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
    <td><br /><img src="images/spainMALLORCA.png" alt="" /></td>
    <td>
      <br />
      <b>Real Club Deportivo Mallorca</b><br /><br />
      город: <b>Palma de Mallorca</b><br /><br />
      год основания: <b>1916</b><br /><br />
      арена: <b>Iberostar Estadi</b><br />
      вместительность: <b>23142</b><br /><br />
      <a href="http://www.rcdmallorca.es" target="_blank">http://www.rcdmallorca.es</a>
<?php if ($edit == "MALLORCA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MALLORCA"] as $pos => $bomber)
  if ($edit == "MALLORCA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALLORCA"] as $pos => $bomber)
  if ($edit == "MALLORCA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALLORCA(*)"] as $pos => $bomber)
  if ($edit == "MALLORCA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MALLORCA(*)"] as $pos => $bomber)
  if ($edit == "MALLORCA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainNUMANCIA.png" alt="" /></td>
    <td>
      <br />
      <b>CD Numancia de Soria</b><br /><br />
      город: <b>Soria</b><br /><br />
      год основания: <b>1945</b><br /><br />
      арена: <b>Nuevo Estadio Los Pajaritos</b><br />
      вместительность: <b>9025</b><br /><br />
      <a href="http://www.cdnumancia.com" target="_blank">http://www.cdnumancia.com</a>
<?php if ($edit == "NUMANCIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NUMANCIA"] as $pos => $bomber)
  if ($edit == "NUMANCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NUMANCIA"] as $pos => $bomber)
  if ($edit == "NUMANCIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NUMANCIA(*)"] as $pos => $bomber)
  if ($edit == "NUMANCIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NUMANCIA(*)"] as $pos => $bomber)
  if ($edit == "NUMANCIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainOSASUNA.png" alt="" /></td>
    <td>
      <br />
      <b>CA Osasuna</b><br /><br />
      город: <b>Pamplona (Iruñea)</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Estadio El Sadar</b><br />
      вместительность: <b>19800</b><br /><br />
      <a href="http://www.osasuna.es" target="_blank">http://www.osasuna.es</a>
<?php if ($edit == "OSASUNA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["OSASUNA"] as $pos => $bomber)
  if ($edit == "OSASUNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OSASUNA"] as $pos => $bomber)
  if ($edit == "OSASUNA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OSASUNA(*)"] as $pos => $bomber)
  if ($edit == "OSASUNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OSASUNA(*)"] as $pos => $bomber)
  if ($edit == "OSASUNA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainPONFER.png" alt="" /></td>
    <td>
      <br />
      <b>SD Ponferradina</b><br /><br />
      город: <b>Ponferrada</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Estadio El Toralín</b><br />
      вместительность: <b>8800</b><br /><br />
      <a href="http://www.sdponferradina.com" target="_blank">http://www.sdponferradina.com</a>
<?php if ($edit == "PONFER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PONFER"] as $pos => $bomber)
  if ($edit == "PONFER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PONFER"] as $pos => $bomber)
  if ($edit == "PONFER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PONFER(*)"] as $pos => $bomber)
  if ($edit == "PONFER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PONFER(*)"] as $pos => $bomber)
  if ($edit == "PONFER")
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
      <a href="http://www.rayovallecano.es" target="_blank">http://www.rayovallecano.es</a>
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
    <td><br /><img src="images/spainREAL.png" alt="" /></td>
    <td>
      <br />
      <b>Real Madrid Club de Fútbol</b><br /><br />
      город: <b>Madrid</b><br /><br />
      год основания: <b>1902</b><br /><br />
      арена: <b>Estadio Santiago Bernabéu</b><br />
      вместительность: <b>85454</b><br /><br />
      <a href="http://www.realmadrid.com" target="_blank">http://www.realmadrid.com</a>
<?php if ($edit == "REAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["REAL"] as $pos => $bomber)
  if ($edit == "REAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REAL"] as $pos => $bomber)
  if ($edit == "REAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REAL(*)"] as $pos => $bomber)
  if ($edit == "REAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REAL(*)"] as $pos => $bomber)
  if ($edit == "REAL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainOVIEDO.png" alt="" /></td>
    <td>
      <br />
      <b>Real Oviedo CF</b><br /><br />
      город: <b>Oviedo</b><br /><br />
      год основания: <b>1926</b><br /><br />
      арена: <b>Estadio Nuevo Carlos Tartiere</b><br />
      вместительность: <b>29862</b><br /><br />
      <a href="http://www.realoviedo.es" target="_blank">http://www.realoviedo.es</a>
<?php if ($edit == "OVIEDO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["OVIEDO"] as $pos => $bomber)
  if ($edit == "OVIEDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OVIEDO"] as $pos => $bomber)
  if ($edit == "OVIEDO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OVIEDO(*)"] as $pos => $bomber)
  if ($edit == "OVIEDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["OVIEDO(*)"] as $pos => $bomber)
  if ($edit == "OVIEDO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainSOCIEDAD.png" alt="" /></td>
    <td>
      <br />
      <b>Real Sociedad de Fútbol</b><br /><br />
      город: <b>Donostia-San Sebastián</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Estadio Municipal de Anoeta</b><br />
      вместительность: <b>32076</b><br /><br />
      <a href="http://www.realsociedad.com" target="_blank">http://www.realsociedad.com</a>
<?php if ($edit == "SOCIEDAD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SOCIEDAD"] as $pos => $bomber)
  if ($edit == "SOCIEDAD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOCIEDAD"] as $pos => $bomber)
  if ($edit == "SOCIEDAD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOCIEDAD(*)"] as $pos => $bomber)
  if ($edit == "SOCIEDAD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOCIEDAD(*)"] as $pos => $bomber)
  if ($edit == "SOCIEDAD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainVALLADOL.png" alt="" /></td>
    <td>
      <br />
      <b>Real Valladolid CF</b><br /><br />
      город: <b>Valladolid</b><br /><br />
      год основания: <b>1928</b><br /><br />
      арена: <b>Estadio Municipal José Zorrilla</b><br />
      вместительность: <b>26512</b><br /><br />
      <a href="http://www.realvalladolid.es" target="_blank">http://www.realvalladolid.es</a>
<?php if ($edit == "VALLADOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VALLADOL"] as $pos => $bomber)
  if ($edit == "VALLADOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALLADOL"] as $pos => $bomber)
  if ($edit == "VALLADOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALLADOL(*)"] as $pos => $bomber)
  if ($edit == "VALLADOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VALLADOL(*)"] as $pos => $bomber)
  if ($edit == "VALLADOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainZARAGOZA.png" alt="" /></td>
    <td>
      <br />
      <b>Real Zaragoza</b><br /><br />
      город: <b>Zaragoza</b><br /><br />
      год основания: <b>1932</b><br /><br />
      арена: <b>Estadio de la Romareda</b><br />
      вместительность: <b>34596</b><br /><br />
      <a href="http://www.realzaragoza.com" target="_blank">http://www.realzaragoza.com</a>
<?php if ($edit == "ZARAGOZA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZARAGOZA"] as $pos => $bomber)
  if ($edit == "ZARAGOZA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARAGOZA"] as $pos => $bomber)
  if ($edit == "ZARAGOZA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARAGOZA(*)"] as $pos => $bomber)
  if ($edit == "ZARAGOZA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZARAGOZA(*)"] as $pos => $bomber)
  if ($edit == "ZARAGOZA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainREUS.png" alt="" /></td>
    <td>
      <br />
      <b>CF Reus Deportiu</b><br /><br />
      город: <b>Reus</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Estadio Camp Nou Municipal</b><br />
      вместительность: <b>4847</b><br /><br />
      <a href="http://www.cfreusdeportiu.com" target="_blank">http://www.cfreusdeportiu.com</a>
<?php if ($edit == "REUS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["REUS"] as $pos => $bomber)
  if ($edit == "REUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REUS"] as $pos => $bomber)
  if ($edit == "REUS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REUS(*)"] as $pos => $bomber)
  if ($edit == "REUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REUS(*)"] as $pos => $bomber)
  if ($edit == "REUS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainSEVILLA.png" alt="" /></td>
    <td>
      <br />
      <b>Sevilla FC</b><br /><br />
      город: <b>Sevilla</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Estadio Ramón Sánchez Pizjuán</b><br />
      вместительность: <b>48649</b><br /><br />
      <a href="http://www.sevillafc.es" target="_blank">http://www.sevillafc.es</a>
<?php if ($edit == "SEVILLA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SEVILLA"] as $pos => $bomber)
  if ($edit == "SEVILLA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVILLA"] as $pos => $bomber)
  if ($edit == "SEVILLA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVILLA(*)"] as $pos => $bomber)
  if ($edit == "SEVILLA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SEVILLA(*)"] as $pos => $bomber)
  if ($edit == "SEVILLA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/spainTENERIFE.png" alt="" /></td>
    <td>
      <br />
      <b>CD Tenerife</b><br /><br />
      город: <b>Santa Cruz de Tenerife</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Estadio Heliodoro Rodríguez Lopéz</b><br />
      вместительность: <b>23660</b><br /><br />
      <a href="http://www.clubdeportivotenerife.es" target="_blank">http://www.clubdeportivotenerife.es</a>
<?php if ($edit == "TENERIFE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TENERIFE"] as $pos => $bomber)
  if ($edit == "TENERIFE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TENERIFE"] as $pos => $bomber)
  if ($edit == "TENERIFE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TENERIFE(*)"] as $pos => $bomber)
  if ($edit == "TENERIFE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TENERIFE(*)"] as $pos => $bomber)
  if ($edit == "TENERIFE")
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
      <a href="http://www.valenciacf.com" target="_blank">http://www.valenciacf.com</a>
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
      <a href="http://www.villarrealcf.es" target="_blank">http://www.villarrealcf.es</a>
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
</table></form>
