<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
    <tr>
    <td><br /><img src="images/netherlandsDENHAAG.png" alt="" /></td>
    <td>
      <br />
      <b>HFC ADO Den Haag</b><br /><br />
      город: <b>Den Haag</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Kyocera Stadion</b><br />
      вместительность: <b>15000</b><br /><br />
      <a href="http://www.adodenhaag.nl" target="_blank">http://www.adodenhaag.nl</a>
<?php if ($edit == "DENHAAG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DENHAAG"] as $pos => $bomber)
  if ($edit == "DENHAAG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DENHAAG"] as $pos => $bomber)
  if ($edit == "DENHAAG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DENHAAG(*)"] as $pos => $bomber)
  if ($edit == "DENHAAG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DENHAAG(*)"] as $pos => $bomber)
  if ($edit == "DENHAAG")
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
      <a href="http://www.ajax.nl" target="_blank">http://www.ajax.nl</a>
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
      <a href="http://www.az.nl" target="_blank">http://www.az.nl</a>
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
    <td><br /><img src="images/netherlandsCAMBUUR.png" alt="" /></td>
    <td>
      <br />
      <b>SC Cambuur Leeuwarden</b><br /><br />
      город: <b>Leeuwarden</b><br /><br />
      год основания: <b>1964</b><br /><br />
      арена: <b>Cambuurstadion</b><br />
      вместительность: <b>11230</b><br /><br />
      <a href="http://www.cambuur.nl" target="_blank">http://www.cambuur.nl</a>
<?php if ($edit == "CAMBUUR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CAMBUUR"] as $pos => $bomber)
  if ($edit == "CAMBUUR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMBUUR"] as $pos => $bomber)
  if ($edit == "CAMBUUR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMBUUR(*)"] as $pos => $bomber)
  if ($edit == "CAMBUUR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMBUUR(*)"] as $pos => $bomber)
  if ($edit == "CAMBUUR")
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
      <a href="http://www.fc-eindhoven.nl" target="_blank">http://www.fc-eindhoven.nl</a>
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
      <a href="http://www.feyenoord.nl" target="_blank">http://www.feyenoord.nl</a>
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
    <td><br /><img src="images/netherlandsGRAAF.png" alt="" /></td>
    <td>
      <br />
      <b>BV De Graafschap</b><br /><br />
      город: <b>Doetinchem</b><br /><br />
      год основания: <b>1954</b><br /><br />
      арена: <b>Stadion De Vijverberg</b><br />
      вместительность: <b>12600</b><br /><br />
      <a href="http://www.degraafschap.nl" target="_blank">http://www.degraafschap.nl</a>
<?php if ($edit == "GRAAF") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GRAAF"] as $pos => $bomber)
  if ($edit == "GRAAF")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRAAF"] as $pos => $bomber)
  if ($edit == "GRAAF")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRAAF(*)"] as $pos => $bomber)
  if ($edit == "GRAAF")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRAAF(*)"] as $pos => $bomber)
  if ($edit == "GRAAF")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsGRONING.png" alt="" /></td>
    <td>
      <br />
      <b>FC Groningen</b><br /><br />
      город: <b>Groningen</b><br /><br />
      год основания: <b>1971</b><br /><br />
      арена: <b>Euroborg</b><br />
      вместительность: <b>22579</b><br /><br />
      <a href="http://www.fcgroningen.nl" target="_blank">http://www.fcgroningen.nl</a>
<?php if ($edit == "GRONING") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GRONING"] as $pos => $bomber)
  if ($edit == "GRONING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRONING"] as $pos => $bomber)
  if ($edit == "GRONING")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRONING(*)"] as $pos => $bomber)
  if ($edit == "GRONING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GRONING(*)"] as $pos => $bomber)
  if ($edit == "GRONING")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsHEEREN.png" alt="" /></td>
    <td>
      <br />
      <b>SC Heerenveen</b><br /><br />
      город: <b>Heerenveen</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Abe Lenstra Stadion</b><br />
      вместительность: <b>26800</b><br /><br />
      <a href="http://www.sc-heerenveen.nl" target="_blank">http://www.sc-heerenveen.nl</a>
<?php if ($edit == "HEEREN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HEEREN"] as $pos => $bomber)
  if ($edit == "HEEREN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEEREN"] as $pos => $bomber)
  if ($edit == "HEEREN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEEREN(*)"] as $pos => $bomber)
  if ($edit == "HEEREN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEEREN(*)"] as $pos => $bomber)
  if ($edit == "HEEREN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsHERACL.png" alt="" /></td>
    <td>
      <br />
      <b>Heracles Almelo</b><br /><br />
      город: <b>Almelo</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Polman Stadion</b><br />
      вместительность: <b>12400</b><br /><br />
      <a href="http://www.heracles.nl" target="_blank">http://www.heracles.nl</a>
<?php if ($edit == "HERACL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HERACL"] as $pos => $bomber)
  if ($edit == "HERACL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERACL"] as $pos => $bomber)
  if ($edit == "HERACL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERACL(*)"] as $pos => $bomber)
  if ($edit == "HERACL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERACL(*)"] as $pos => $bomber)
  if ($edit == "HERACL")
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
      <a href="http://www.nac.nl/" target="_blank">http://www.nac.nl/</a>
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
    <td><br /><img src="images/netherlandsPSV.png" alt="" /></td>
    <td>
      <br />
      <b>PSV Eindhoven</b><br /><br />
      город: <b>Eindhoven</b><br /><br />
      год основания: <b>1913</b><br /><br />
      арена: <b>Philips Stadion</b><br />
      вместительность: <b>35119</b><br /><br />
      <a href="http://www.psv.nl" target="_blank">http://www.psv.nl</a>
<?php if ($edit == "PSV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PSV"] as $pos => $bomber)
  if ($edit == "PSV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSV"] as $pos => $bomber)
  if ($edit == "PSV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSV(*)"] as $pos => $bomber)
  if ($edit == "PSV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSV(*)"] as $pos => $bomber)
  if ($edit == "PSV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsRODA.png" alt="" /></td>
    <td>
      <br />
      <b>SV Roda JC</b><br /><br />
      город: <b>Kerkrade</b><br /><br />
      год основания: <b>1962</b><br /><br />
      арена: <b>Parkstad Limburg Stadion</b><br />
      вместительность: <b>19979</b><br /><br />
      <a href="http://www.rodajc.nl" target="_blank">http://www.rodajc.nl</a>
<?php if ($edit == "RODA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RODA"] as $pos => $bomber)
  if ($edit == "RODA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RODA"] as $pos => $bomber)
  if ($edit == "RODA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RODA(*)"] as $pos => $bomber)
  if ($edit == "RODA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RODA(*)"] as $pos => $bomber)
  if ($edit == "RODA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsTWENTE.png" alt="" /></td>
    <td>
      <br />
      <b>FC Twente</b><br /><br />
      город: <b>Enschede</b><br /><br />
      год основания: <b>1965</b><br /><br />
      арена: <b>De Grolsch Veste</b><br />
      вместительность: <b>30014</b><br /><br />
      <a href="http://www.fctwente.nl" target="_blank">http://www.fctwente.nl</a>
<?php if ($edit == "TWENTE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TWENTE"] as $pos => $bomber)
  if ($edit == "TWENTE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TWENTE"] as $pos => $bomber)
  if ($edit == "TWENTE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TWENTE(*)"] as $pos => $bomber)
  if ($edit == "TWENTE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TWENTE(*)"] as $pos => $bomber)
  if ($edit == "TWENTE")
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
      <a href="http://www.fcutrecht.nl" target="_blank">http://www.fcutrecht.nl</a>
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
      <a href="http://www.vitesse.nl/" target="_blank">http://www.vitesse.nl/</a>
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
    <td><br /><img src="images/netherlandsVVV.png" alt="" /></td>
    <td>
      <br />
      <b>VVV Venlo</b><br /><br />
      город: <b>Venlo</b><br /><br />
      год основания: <b>1954</b><br /><br />
      арена: <b>Seacon Stadion De Koel</b><br />
      вместительность: <b>8000</b><br /><br />
      <a href="http://www.vvv-venlo.nl" target="_blank">http://www.vvv-venlo.nl</a>
<?php if ($edit == "VVV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["VVV"] as $pos => $bomber)
  if ($edit == "VVV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VVV"] as $pos => $bomber)
  if ($edit == "VVV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VVV(*)"] as $pos => $bomber)
  if ($edit == "VVV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["VVV(*)"] as $pos => $bomber)
  if ($edit == "VVV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsWILLEM.png" alt="" /></td>
    <td>
      <br />
      <b>Willem II</b><br /><br />
      город: <b>Tilburg</b><br /><br />
      год основания: <b>1896</b><br /><br />
      арена: <b>Koning Willem II Stadion</b><br />
      вместительность: <b>14637</b><br /><br />
      <a href="http://www.willem-ii.nl" target="_blank">http://www.willem-ii.nl</a>
<?php if ($edit == "WILLEM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WILLEM"] as $pos => $bomber)
  if ($edit == "WILLEM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WILLEM"] as $pos => $bomber)
  if ($edit == "WILLEM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WILLEM(*)"] as $pos => $bomber)
  if ($edit == "WILLEM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WILLEM(*)"] as $pos => $bomber)
  if ($edit == "WILLEM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/netherlandsZWOLLE.png" alt="" /></td>
    <td>
      <br />
      <b>PEC Zwolle</b><br /><br />
      город: <b>Zwolle</b><br /><br />
      год основания: <b>1990</b><br /><br />
      арена: <b>IJsseldelta Stadion</b><br />
      вместительность: <b>12500</b><br /><br />
      <a href="http://www.peczwolle.nl" target="_blank">http://www.peczwolle.nl</a>
<?php if ($edit == "ZWOLLE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ZWOLLE"] as $pos => $bomber)
  if ($edit == "ZWOLLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZWOLLE"] as $pos => $bomber)
  if ($edit == "ZWOLLE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZWOLLE(*)"] as $pos => $bomber)
  if ($edit == "ZWOLLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ZWOLLE(*)"] as $pos => $bomber)
  if ($edit == "ZWOLLE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
