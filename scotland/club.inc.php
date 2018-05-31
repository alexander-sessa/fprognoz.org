<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/scotlandABERDEEN.png" alt="" /></td>
    <td>
      <br />
      <b>Aberdeen FC</b><br /><br />
      город: <b>Aberdeen</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Pittodrie Stadium</b><br />
      вместительность: <b>22199</b><br /><br />
      <a href="http://www.afc.co.uk" target="_blank">http://www.afc.co.uk</a>
<?php if ($edit == "ABERDEEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ABERDEEN"] as $pos => $bomber)
  if ($edit == "ABERDEEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ABERDEEN"] as $pos => $bomber)
  if ($edit == "ABERDEEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ABERDEEN(*)"] as $pos => $bomber)
  if ($edit == "ABERDEEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ABERDEEN(*)"] as $pos => $bomber)
  if ($edit == "ABERDEEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandAYR.png" alt="" /></td>
    <td>
      <br />
      <b>Ayr United FC</b><br /><br />
      город: <b>Ayr</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Somerset Park</b><br />
      вместительность: <b>10128</b><br /><br />
      <a href="http://www.ayrunitedfc.co.uk" target="_blank">http://www.ayrunitedfc.co.uk</a>
<?php if ($edit == "AYR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AYR"] as $pos => $bomber)
  if ($edit == "AYR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AYR"] as $pos => $bomber)
  if ($edit == "AYR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AYR(*)"] as $pos => $bomber)
  if ($edit == "AYR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AYR(*)"] as $pos => $bomber)
  if ($edit == "AYR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandCELTIC.png" alt="" /></td>
    <td>
      <br />
      <b>Celtic FC</b><br /><br />
      город: <b>Glasgow</b><br /><br />
      год основания: <b>1888</b><br /><br />
      арена: <b>Celtic Park</b><br />
      вместительность: <b>60832</b><br /><br />
      <a href="http://www.celticfc.co.uk" target="_blank">http://www.celticfc.co.uk</a>
<?php if ($edit == "CELTIC") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CELTIC"] as $pos => $bomber)
  if ($edit == "CELTIC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTIC"] as $pos => $bomber)
  if ($edit == "CELTIC")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTIC(*)"] as $pos => $bomber)
  if ($edit == "CELTIC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CELTIC(*)"] as $pos => $bomber)
  if ($edit == "CELTIC")
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
      <a href="http://www.thedees.co.uk" target="_blank">http://www.thedees.co.uk</a>
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
      <a href="http://www.dundeeunitedfc.co.uk" target="_blank">http://www.dundeeunitedfc.co.uk</a>
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
    <td><br /><img src="images/scotlandELGIN.png" width="150" height="150" alt="" /></td>
    <td>
      <br />
      <b>Elgin City FC</b><br /><br />
      город: <b>Elgin</b><br /><br />
      год основания: <b>1893</b><br /><br />
      арена: <b>Borough Briggs</b><br />
      вместительность: <b>3927</b><br /><br />
      <a href="http://www.elgincity.com" target="_blank">http://www.elgincity.com</a>
<?php if ($edit == "ELGIN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ELGIN"] as $pos => $bomber)
  if ($edit == "ELGIN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ELGIN"] as $pos => $bomber)
  if ($edit == "ELGIN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ELGIN(*)"] as $pos => $bomber)
  if ($edit == "ELGIN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ELGIN(*)"] as $pos => $bomber)
  if ($edit == "ELGIN")
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
      <a href="http://www.falkirkfc.co.uk" target="_blank">http://www.falkirkfc.co.uk/</a>
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
    <td><br /><img src="images/scotlandHAMILTON.png" alt="" /></td>
    <td>
      <br />
      <b>Hamilton Academical FC</b><br /><br />
      город: <b>Hamilton</b><br /><br />
      год основания: <b>1874</b><br /><br />
      арена: <b>New Douglas Park</b><br />
      вместительность: <b>5396</b><br /><br />
      <a href="http://www.acciesfc.co.uk" target="_blank">http://www.acciesfc.co.uk</a>
<?php if ($edit == "HAMILTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HAMILTON"] as $pos => $bomber)
  if ($edit == "HAMILTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMILTON"] as $pos => $bomber)
  if ($edit == "HAMILTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMILTON(*)"] as $pos => $bomber)
  if ($edit == "HAMILTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMILTON(*)"] as $pos => $bomber)
  if ($edit == "HAMILTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandHEARTS.png" alt="" /></td>
    <td>
      <br />
      <b>Heart of Midlothian FC</b><br /><br />
      город: <b>Edinburgh</b><br /><br />
      год основания: <b>1874</b><br /><br />
      арена: <b>Tynecastle Stadium</b><br />
      вместительность: <b>17420</b><br /><br />
      <a href="http://www.heartsfc.co.uk" target="_blank">http://www.heartsfc.co.uk</a>
<?php if ($edit == "HEARTS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HEARTS"] as $pos => $bomber)
  if ($edit == "HEARTS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEARTS"] as $pos => $bomber)
  if ($edit == "HEARTS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEARTS(*)"] as $pos => $bomber)
  if ($edit == "HEARTS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEARTS(*)"] as $pos => $bomber)
  if ($edit == "HEARTS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandHIBER.png" alt="" /></td>
    <td>
      <br />
      <b>Hibernian FC (Edinburgh)</b><br /><br />
      город: <b>Edinburgh</b><br /><br />
      год основания: <b>1875</b><br /><br />
      арена: <b>Easter Road Stadium</b><br />
      вместительность: <b>20421</b><br /><br />
      <a href="http://www.hibernianfc.co.uk" target="_blank">http://www.hibernianfc.co.uk</a>
<?php if ($edit == "HIBER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HIBER"] as $pos => $bomber)
  if ($edit == "HIBER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HIBER"] as $pos => $bomber)
  if ($edit == "HIBER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HIBER(*)"] as $pos => $bomber)
  if ($edit == "HIBER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HIBER(*)"] as $pos => $bomber)
  if ($edit == "HIBER")
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
      <a href="http://www.caleythistleonline.com" target="_blank">http://www.caleythistleonline.com</a>
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
    <td><br /><img src="images/scotlandKILMAR.png" alt="" /></td>
    <td>
      <br />
      <b>Kilmarnock FC</b><br /><br />
      город: <b>Kilmarnock</b><br /><br />
      год основания: <b>1869</b><br /><br />
      арена: <b>Rugby Park</b><br />
      вместительность: <b>18128</b><br /><br />
      <a href="http://www.kilmarnockfc.co.uk" target="_blank">http://www.kilmarnockfc.co.uk</a>
<?php if ($edit == "KILMAR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KILMAR"] as $pos => $bomber)
  if ($edit == "KILMAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KILMAR"] as $pos => $bomber)
  if ($edit == "KILMAR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KILMAR(*)"] as $pos => $bomber)
  if ($edit == "KILMAR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KILMAR(*)"] as $pos => $bomber)
  if ($edit == "KILMAR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandMOTHER.png" alt="" /></td>
    <td>
      <br />
      <b>Motherwell FC</b><br /><br />
      город: <b>Motherwell</b><br /><br />
      год основания: <b>1886</b><br /><br />
      арена: <b>Fir Park</b><br />
      вместительность: <b>13742</b><br /><br />
      <a href="http://www.motherwellfc.co.uk" target="_blank">http://www.motherwellfc.co.uk</a>
<?php if ($edit == "MOTHER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MOTHER"] as $pos => $bomber)
  if ($edit == "MOTHER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOTHER"] as $pos => $bomber)
  if ($edit == "MOTHER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOTHER(*)"] as $pos => $bomber)
  if ($edit == "MOTHER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MOTHER(*)"] as $pos => $bomber)
  if ($edit == "MOTHER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandPARTICK.png" alt="" /></td>
    <td>
      <br />
      <b>Partick Thistle FC</b><br /><br />
      город: <b>Glasgow</b><br /><br />
      год основания: <b>1876</b><br /><br />
      арена: <b>Firhill stadium</b><br />
      вместительность: <b>13079</b><br /><br />
      <a href="http://www.ptfc.co.uk" target="_blank">http://www.ptfc.co.uk</a>
<?php if ($edit == "PARTICK") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PARTICK"] as $pos => $bomber)
  if ($edit == "PARTICK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARTICK"] as $pos => $bomber)
  if ($edit == "PARTICK")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARTICK(*)"] as $pos => $bomber)
  if ($edit == "PARTICK")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PARTICK(*)"] as $pos => $bomber)
  if ($edit == "PARTICK")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandRANGERS.png" alt="" /></td>
    <td>
      <br />
      <b>Rangers FC</b><br /><br />
      город: <b>Glasgow</b><br /><br />
      год основания: <b>1873</b><br /><br />
      арена: <b>Ibrox Stadium</b><br />
      вместительность: <b>51082</b><br /><br />
      <a href="http://www.rangers.co.uk" target="_blank">http://www.rangers.co.uk</a>
<?php if ($edit == "RANGERS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RANGERS"] as $pos => $bomber)
  if ($edit == "RANGERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RANGERS"] as $pos => $bomber)
  if ($edit == "RANGERS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RANGERS(*)"] as $pos => $bomber)
  if ($edit == "RANGERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RANGERS(*)"] as $pos => $bomber)
  if ($edit == "RANGERS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandROSS.png" alt="" /></td>
    <td>
      <br />
      <b>Ross County FC</b><br /><br />
      город: <b>Dingwall</b><br /><br />
      год основания: <b>1929</b><br /><br />
      арена: <b>Global Energy Stadium</b><br />
      вместительность: <b>6310</b><br /><br />
      <a href="http://www.rosscountyfootballclub.co.uk" target="_blank">http://www.rosscountyfootballclub.co.uk</a>
<?php if ($edit == "ROSS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ROSS"] as $pos => $bomber)
  if ($edit == "ROSS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSS"] as $pos => $bomber)
  if ($edit == "ROSS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSS(*)"] as $pos => $bomber)
  if ($edit == "ROSS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ROSS(*)"] as $pos => $bomber)
  if ($edit == "ROSS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandSTJOHN.png" alt="" /></td>
    <td>
      <br />
      <b>Saint Johnstone FC</b><br /><br />
      город: <b>Perth</b><br /><br />
      год основания: <b>1885</b><br /><br />
      арена: <b>McDiarmid Park</b><br />
      вместительность: <b>10673</b><br /><br />
      <a href="http://www.perthstjohnstonefc.co.uk" target="_blank">http://www.perthstjohnstonefc.co.uk</a>
<?php if ($edit == "STJOHN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STJOHN"] as $pos => $bomber)
  if ($edit == "STJOHN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STJOHN"] as $pos => $bomber)
  if ($edit == "STJOHN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STJOHN(*)"] as $pos => $bomber)
  if ($edit == "STJOHN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STJOHN(*)"] as $pos => $bomber)
  if ($edit == "STJOHN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/scotlandSTMIRREN.png" alt="" /></td>
    <td>
      <br />
      <b>Saint Mirren FC</b><br /><br />
      город: <b>Paisley</b><br /><br />
      год основания: <b>1877</b><br /><br />
      арена: <b>St. Mirren Park (Greenhill Road)</b><br />
      вместительность: <b>8016</b><br /><br />
      <a href="http://www.saintmirren.net" target="_blank">http://www.saintmirren.net</a>
<?php if ($edit == "STMIRREN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STMIRREN"] as $pos => $bomber)
  if ($edit == "STMIRREN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STMIRREN"] as $pos => $bomber)
  if ($edit == "STMIRREN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STMIRREN(*)"] as $pos => $bomber)
  if ($edit == "STMIRREN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STMIRREN(*)"] as $pos => $bomber)
  if ($edit == "STMIRREN")
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
      <a href="http://www.stranraerfc.org" target="_blank">http://www.stranraerfc.org</a>
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
</table></form>
