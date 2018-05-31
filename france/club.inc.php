<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
    <tr>
    <td><br /><img src="images/franceANGERS.png" alt="" /></td>
    <td>
      <br />
      <b>Angers SCO</b><br /><br />
      город: <b>Angers</b><br /><br />
      год основания: <b>1919</b><br /><br />
      арена: <b>Stade Jean Bouin</b><br />
      вместительность: <b>17100</b><br /><br />
      сайт: <a href="http://www.angers-sco.fr" target="_blank">http://www.angers-sco.fr</a>
<?php if ($edit == "ANGERS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ANGERS"] as $pos => $bomber)
  if ($edit == "ANGERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANGERS"] as $pos => $bomber)
  if ($edit == "ANGERS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANGERS(*)"] as $pos => $bomber)
  if ($edit == "ANGERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ANGERS(*)"] as $pos => $bomber)
  if ($edit == "ANGERS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceAUXERRE.png" alt="" /></td>
    <td>
      <br />
      <b>AJ Auxerre</b><br /><br />
      город: <b>Auxerre</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Stade de l'Abbé Deschamps</b><br />
      вместительность: <b>23467</b><br /><br />
      сайт: <a href="http://www.aja.fr" target="_blank">http://www.aja.fr</a>
<?php if ($edit == "AUXERRE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AUXERRE"] as $pos => $bomber)
  if ($edit == "AUXERRE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUXERRE"] as $pos => $bomber)
  if ($edit == "AUXERRE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUXERRE(*)"] as $pos => $bomber)
  if ($edit == "AUXERRE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUXERRE(*)"] as $pos => $bomber)
  if ($edit == "AUXERRE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceBASTIA.png" alt="" /></td>
    <td>
      <br />
      <b>SC Bastia</b><br /><br />
      город: <b>Furian</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Stade Armand Césari</b><br />
      вместительность: <b>16480</b><br /><br />
      сайт: <a href="http://www.sc-bastia.net" target="_blank">http://www.sc-bastia.net</a>
<?php if ($edit == "BASTIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BASTIA"] as $pos => $bomber)
  if ($edit == "BASTIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BASTIA"] as $pos => $bomber)
  if ($edit == "BASTIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BASTIA(*)"] as $pos => $bomber)
  if ($edit == "BASTIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BASTIA(*)"] as $pos => $bomber)
  if ($edit == "BASTIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceBORDO.png" alt="" /></td>
    <td>
      <br />
      <b>FC Girondins de Bordeaux</b><br /><br />
      город: <b>Bordeaux</b><br /><br />
      год основания: <b>1881</b><br /><br />
      арена: <b>Stade Jacques Chaban-Delmas</b><br />
      вместительность: <b>34263</b><br /><br />
      сайт: <a href="http://www.girondins.com" target="_blank">http://www.girondins.com</a>
<?php if ($edit == "BORDO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BORDO"] as $pos => $bomber)
  if ($edit == "BORDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BORDO"] as $pos => $bomber)
  if ($edit == "BORDO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BORDO(*)"] as $pos => $bomber)
  if ($edit == "BORDO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BORDO(*)"] as $pos => $bomber)
  if ($edit == "BORDO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceCAEN.png" alt="" /></td>
    <td>
      <br />
      <b>Stade Malherbe Caen</b><br /><br />
      город: <b>Caen</b><br /><br />
      год основания: <b>1897</b><br /><br />
      арена: <b>Stade Michel d'Ornano</b><br />
      вместительность: <b>21500</b><br /><br />
      сайт: <a href="http://www.smcaen.fr" target="_blank">http://www.smcaen.fr</a>
<?php if ($edit == "CAEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CAEN"] as $pos => $bomber)
  if ($edit == "CAEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAEN"] as $pos => $bomber)
  if ($edit == "CAEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAEN(*)"] as $pos => $bomber)
  if ($edit == "CAEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAEN(*)"] as $pos => $bomber)
  if ($edit == "CAEN")
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
    <td><br /><img src="images/franceGENGAM.png" alt="" /></td>
    <td>
      <br />
      <b>En Avant Guingamp</b><br /><br />
      город: <b>Guingamp</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Stade du Roudourou</b><br />
      вместительность: <b>18250</b><br /><br />
      сайт: <a href="http://www.eaguingamp.com" target="_blank">http://www.eaguingamp.com</a>
<?php if ($edit == "GENGAM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GENGAM"] as $pos => $bomber)
  if ($edit == "GENGAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENGAM"] as $pos => $bomber)
  if ($edit == "GENGAM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENGAM(*)"] as $pos => $bomber)
  if ($edit == "GENGAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GENGAM(*)"] as $pos => $bomber)
  if ($edit == "GENGAM")
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
    <td><br /><img src="images/franceLENS.png" alt="" /></td>
    <td>
      <br />
      <b>Racing Club de Lens</b><br /><br />
      город: <b>Lens</b><br /><br />
      год основания: <b>1906</b><br /><br />
      арена: <b>Stade Bollaert-Delelis</b><br />
      вместительность: <b>41233</b><br /><br />
      сайт: <a href="http://www.rclens.fr/" target="_blank">http://www.rclens.fr/</a>
<?php if ($edit == "LENS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LENS"] as $pos => $bomber)
  if ($edit == "LENS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LENS"] as $pos => $bomber)
  if ($edit == "LENS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LENS(*)"] as $pos => $bomber)
  if ($edit == "LENS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LENS(*)"] as $pos => $bomber)
  if ($edit == "LENS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceLILLE.png" alt="" /></td>
    <td>
      <br />
      <b>Lille OSC Métropole</b><br /><br />
      город: <b>Villeneuve d'Ascq</b><br /><br />
      год основания: <b>1944</b><br /><br />
      арена: <b>Grand Stade Lille Métropole</b><br />
      вместительность: <b>50157</b><br /><br />
      сайт: <a href="http://www.losc.fr" target="_blank">http://www.losc.fr</a>
<?php if ($edit == "LILLE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LILLE"] as $pos => $bomber)
  if ($edit == "LILLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LILLE"] as $pos => $bomber)
  if ($edit == "LILLE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LILLE(*)"] as $pos => $bomber)
  if ($edit == "LILLE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LILLE(*)"] as $pos => $bomber)
  if ($edit == "LILLE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceLORIENT.png" alt="" /></td>
    <td>
      <br />
      <b>FC Lorient</b><br /><br />
      город: <b>Lorient</b><br /><br />
      год основания: <b>1926</b><br /><br />
      арена: <b>Stade Yves Allainmat - Le Moustoir</b><br />
      вместительность: <b>18970</b><br /><br />
      сайт: <a href="http://www.fclweb.fr" target="_blank">http://www.fclweb.fr</a>
<?php if ($edit == "LORIENT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LORIENT"] as $pos => $bomber)
  if ($edit == "LORIENT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LORIENT"] as $pos => $bomber)
  if ($edit == "LORIENT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LORIENT(*)"] as $pos => $bomber)
  if ($edit == "LORIENT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LORIENT(*)"] as $pos => $bomber)
  if ($edit == "LORIENT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceMETZ.png" alt="" /></td>
    <td>
      <br />
      <b>FC Metz</b><br /><br />
      город: <b>Metz</b><br /><br />
      год основания: <b>1932</b><br /><br />
      арена: <b>Stade Saint-Symphorien</b><br />
      вместительность: <b>26700</b><br /><br />
      сайт: <a href="http://www.fcmetz.com/" target="_blank">http://www.fcmetz.com/</a>
<?php if ($edit == "METZ") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["METZ"] as $pos => $bomber)
  if ($edit == "METZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METZ"] as $pos => $bomber)
  if ($edit == "METZ")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METZ(*)"] as $pos => $bomber)
  if ($edit == "METZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["METZ(*)"] as $pos => $bomber)
  if ($edit == "METZ")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceMONACO.png" alt="" /></td>
    <td>
      <br />
      <b>AS Monaco FC</b><br /><br />
      город: <b>Monaco</b><br /><br />
      год основания: <b>1919</b><br /><br />
      арена: <b>Stade Louis II.</b><br />
      вместительность: <b>18523</b><br /><br />
      сайт: <a href="http://www.asm-fc.com" target="_blank">http://www.asm-fc.com</a>
<?php if ($edit == "MONACO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MONACO"] as $pos => $bomber)
  if ($edit == "MONACO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONACO"] as $pos => $bomber)
  if ($edit == "MONACO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONACO(*)"] as $pos => $bomber)
  if ($edit == "MONACO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONACO(*)"] as $pos => $bomber)
  if ($edit == "MONACO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceMONTPEL.png" alt="" /></td>
    <td>
      <br />
      <b>Montpellier HSC</b><br /><br />
      город: <b>Montpellier</b><br /><br />
      год основания: <b>1974</b><br /><br />
      арена: <b>Stade de la Mosson</b><br />
      вместительность: <b>32939</b><br /><br />
      сайт: <a href="http://www.mhscfoot.com" target="_blank">http://www.mhscfoot.com</a>
<?php if ($edit == "MONTPEL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MONTPEL"] as $pos => $bomber)
  if ($edit == "MONTPEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONTPEL"] as $pos => $bomber)
  if ($edit == "MONTPEL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONTPEL(*)"] as $pos => $bomber)
  if ($edit == "MONTPEL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MONTPEL(*)"] as $pos => $bomber)
  if ($edit == "MONTPEL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceNANCY.png" alt="" /></td>
    <td>
      <br />
      <b>AS Nancy-Lorraine</b><br /><br />
      город: <b>Tomblaine</b><br /><br />
      год основания: <b>1967</b><br /><br />
      арена: <b>Stade Marcel Picot</b><br />
      вместительность: <b>20087</b><br /><br />
      сайт: <a href="http://www.asnl.net" target="_blank">http://www.asnl.net</a>
<?php if ($edit == "NANCY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NANCY"] as $pos => $bomber)
  if ($edit == "NANCY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANCY"] as $pos => $bomber)
  if ($edit == "NANCY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANCY(*)"] as $pos => $bomber)
  if ($edit == "NANCY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NANCY(*)"] as $pos => $bomber)
  if ($edit == "NANCY")
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
    <td><br /><img src="images/franceNICE.png" alt="" /></td>
    <td>
      <br />
      <b>OGC de Nice Côte d'Azur</b><br /><br />
      город: <b>Nice</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Stade Municipal du Ray</b><br />
      вместительность: <b>18696</b><br /><br />
      сайт: <a href="http://www.ogcnice.com" target="_blank">http://www.ogcnice.com</a>
<?php if ($edit == "NICE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NICE"] as $pos => $bomber)
  if ($edit == "NICE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NICE"] as $pos => $bomber)
  if ($edit == "NICE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NICE(*)"] as $pos => $bomber)
  if ($edit == "NICE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NICE(*)"] as $pos => $bomber)
  if ($edit == "NICE")
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
    <td><br /><img src="images/francePSG.png" alt="" /></td>
    <td>
      <br />
      <b>Paris Saint-Germain FC</b><br /><br />
      город: <b>Paris</b><br /><br />
      год основания: <b>1970</b><br /><br />
      арена: <b>Parc des Princes</b><br />
      вместительность: <b>48428</b><br /><br />
      сайт: <a href="http://www.psg.fr" target="_blank">http://www.psg.fr</a>
<?php if ($edit == "PSG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PSG"] as $pos => $bomber)
  if ($edit == "PSG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSG"] as $pos => $bomber)
  if ($edit == "PSG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSG(*)"] as $pos => $bomber)
  if ($edit == "PSG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PSG(*)"] as $pos => $bomber)
  if ($edit == "PSG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceREDSTAR.png" alt="" /></td>
    <td>
      <br />
      <b>Red Star FC 93</b><br /><br />
      город: <b>Saint-Ouen</b><br /><br />
      год основания: <b>1897</b><br /><br />
      арена: <b>Stade de Paris (Stade Bauer)</b><br />
      вместительность: <b>10000</b><br /><br />
      сайт: <a href="http://www.redstarfc93.fr/" target="_blank">http://www.redstarfc93.fr/</a>
<?php if ($edit == "REDSTAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["REDSTAR"] as $pos => $bomber)
  if ($edit == "REDSTAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REDSTAR"] as $pos => $bomber)
  if ($edit == "REDSTAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REDSTAR(*)"] as $pos => $bomber)
  if ($edit == "REDSTAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REDSTAR(*)"] as $pos => $bomber)
  if ($edit == "REDSTAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceREIMS.png" alt="" /></td>
    <td>
      <br />
      <b>Stade de Reims</b><br /><br />
      город: <b>Reims</b><br /><br />
      год основания: <b>1909</b><br /><br />
      арена: <b>Stade Auguste-Delaune II</b><br />
      вместительность: <b>21684</b><br /><br />
      сайт: <a href="http://www.stade-de-reims.com/" target="_blank">http://www.stade-de-reims.com/</a>
<?php if ($edit == "REIMS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["REIMS"] as $pos => $bomber)
  if ($edit == "REIMS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REIMS"] as $pos => $bomber)
  if ($edit == "REIMS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REIMS(*)"] as $pos => $bomber)
  if ($edit == "REIMS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["REIMS(*)"] as $pos => $bomber)
  if ($edit == "REIMS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceRENN.png" alt="" /></td>
    <td>
      <br />
      <b>Stade Rennais FC</b><br /><br />
      город: <b>Rennes</b><br /><br />
      год основания: <b>1901</b><br /><br />
      арена: <b>Stade de la Route de Lorient</b><br />
      вместительность: <b>31127</b><br /><br />
      сайт: <a href="http://www.staderennais.com" target="_blank">http://www.staderennais.com</a>
<?php if ($edit == "RENN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RENN"] as $pos => $bomber)
  if ($edit == "RENN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RENN"] as $pos => $bomber)
  if ($edit == "RENN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RENN(*)"] as $pos => $bomber)
  if ($edit == "RENN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RENN(*)"] as $pos => $bomber)
  if ($edit == "RENN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceST_ETIEN.png" alt="" /></td>
    <td>
      <br />
      <b>AS Saint-Étienne Loire</b><br /><br />
      город: <b>Saint-Ètienne</b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Stade Geoffroy-Guichard</b><br />
      вместительность: <b>35616</b><br /><br />
      сайт: <a href="http://www.asse.fr" target="_blank">http://www.asse.fr</a>
<?php if ($edit == "ST_ETIEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ST_ETIEN"] as $pos => $bomber)
  if ($edit == "ST_ETIEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ST_ETIEN"] as $pos => $bomber)
  if ($edit == "ST_ETIEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ST_ETIEN(*)"] as $pos => $bomber)
  if ($edit == "ST_ETIEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ST_ETIEN(*)"] as $pos => $bomber)
  if ($edit == "ST_ETIEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/franceTOULOUSE.png" alt="" /></td>
    <td>
      <br />
      <b>Toulouse FC</b><br /><br />
      город: <b>Toulouse</b><br /><br />
      год основания: <b>1937</b><br /><br />
      арена: <b>Stade Municipal</b><br />
      вместительность: <b>35472</b><br /><br />
      сайт: <a href="http://www.tfc.info" target="_blank">http://www.tfc.info</a>
<?php if ($edit == "TOULOUSE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOULOUSE"] as $pos => $bomber)
  if ($edit == "TOULOUSE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOULOUSE"] as $pos => $bomber)
  if ($edit == "TOULOUSE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOULOUSE(*)"] as $pos => $bomber)
  if ($edit == "TOULOUSE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOULOUSE(*)"] as $pos => $bomber)
  if ($edit == "TOULOUSE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
