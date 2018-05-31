<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/englandARSENAL.png" alt="" /></td>
    <td>
      <br />
      <b>Arsenal FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1886</b><br /><br />
      арена: <b>Emirates Stadium</b><br />
      вместительность: <b>60355</b><br /><br />
      <a href="http://www.arsenal.com" target="_blank">http://www.arsenal.com</a>
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
    <td><br /><img src="images/englandASTON.png" alt="" /></td>
    <td>
      <br />
      <b>Aston Villa FC</b><br /><br />
      город: <b>Birmingham</b><br /><br />
      год основания: <b>1874</b><br /><br />
      арена: <b>Villa Park</b><br />
      вместительность: <b>42788</b><br /><br />
      <a href="http://www.avfc.co.uk" target="_blank">http://www.avfc.co.uk</a>
<?php if ($edit == "ASTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ASTON"] as $pos => $bomber)
  if ($edit == "ASTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASTON"] as $pos => $bomber)
  if ($edit == "ASTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASTON(*)"] as $pos => $bomber)
  if ($edit == "ASTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ASTON(*)"] as $pos => $bomber)
  if ($edit == "ASTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBLACKBURN.png" alt="" /></td>
    <td>
      <br />
      <b>Blackburn Rovers FC</b><br /><br />
      город: <b>Blackburn, Lancashire</b><br /><br />
      год основания: <b>1875</b><br /><br />
      арена: <b>Ewood Park</b><br />
      вместительность: <b>31367</b><br /><br />
      <a href="http://www.rovers.co.uk" target="_blank">http://www.rovers.co.uk</a>
<?php if ($edit == "BLACKBURN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BLACKBURN"] as $pos => $bomber)
  if ($edit == "BLACKBURN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKBURN"] as $pos => $bomber)
  if ($edit == "BLACKBURN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKBURN(*)"] as $pos => $bomber)
  if ($edit == "BLACKBURN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BLACKBURN(*)"] as $pos => $bomber)
  if ($edit == "BLACKBURN")
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
      <a href="http://www.blackpoolfc.co.uk" target="_blank">http://www.blackpoolfc.co.uk</a>
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
    <td><br /><img src="images/englandBOLTON.png" alt="" /></td>
    <td>
      <br />
      <b>Bolton Wanderers FC</b><br /><br />
      город: <b>Bolton</b><br /><br />
      год основания: <b>1874</b><br /><br />
      арена: <b>Reebok Stadium</b><br />
      вместительность: <b>28723</b><br /><br />
      <a href="http://www.bwfc.co.uk" target="_blank">http://www.bwfc.co.uk</a>
<?php if ($edit == "BOLTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOLTON"] as $pos => $bomber)
  if ($edit == "BOLTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLTON"] as $pos => $bomber)
  if ($edit == "BOLTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLTON(*)"] as $pos => $bomber)
  if ($edit == "BOLTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOLTON(*)"] as $pos => $bomber)
  if ($edit == "BOLTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBOURNEM.png" alt="" /></td>
    <td>
      <br />
      <b>AFC Bournemouth</b><br /><br />
      город: <b>Bournemouth</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Vitality Stadium</b><br />
      вместительность: <b>12000</b><br /><br />
      <a href="http://www.afcb.co.uk" target="_blank">http://www.afcb.co.uk</a>
<?php if ($edit == "BOURNEM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOURNEM"] as $pos => $bomber)
  if ($edit == "BOURNEM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOURNEM"] as $pos => $bomber)
  if ($edit == "BOURNEM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOURNEM(*)"] as $pos => $bomber)
  if ($edit == "BOURNEM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOURNEM(*)"] as $pos => $bomber)
  if ($edit == "BOURNEM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBRENTFORD.png" alt="" /></td>
    <td>
      <br />
      <b>Brentford FC</b><br /><br />
      город: <b>Brentford</b><br /><br />
      год основания: <b>1889</b><br /><br />
      арена: <b>Griffin Park</b><br />
      вместительность: <b>12763</b><br /><br />
      <a href="http://www.brentfordfc.co.uk" target="_blank">http://www.brentfordfc.co.uk</a>
<?php if ($edit == "BRENTFORD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRENTFORD"] as $pos => $bomber)
  if ($edit == "BRENTFORD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRENTFORD"] as $pos => $bomber)
  if ($edit == "BRENTFORD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRENTFORD(*)"] as $pos => $bomber)
  if ($edit == "BRENTFORD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRENTFORD(*)"] as $pos => $bomber)
  if ($edit == "BRENTFORD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBRIGHTON.png" alt="" /></td>
    <td>
      <br />
      <b>Brighton & Hove Albion FC</b><br /><br />
      город: <b>Falmer, East Sussex</b><br /><br />
      год основания: <b>1901</b><br /><br />
      арена: <b>The American Express Community Stadium</b><br />
      вместительность: <b>30750</b><br /><br />
      <a href="http://www.seagulls.co.uk" target="_blank">http://www.seagulls.co.uk</a>
<?php if ($edit == "BRIGHTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRIGHTON"] as $pos => $bomber)
  if ($edit == "BRIGHTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRIGHTON"] as $pos => $bomber)
  if ($edit == "BRIGHTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRIGHTON(*)"] as $pos => $bomber)
  if ($edit == "BRIGHTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRIGHTON(*)"] as $pos => $bomber)
  if ($edit == "BRIGHTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandBURNLEY.png" alt="" /></td>
    <td>
      <br />
      <b>Burnley FC</b><br /><br />
      город: <b>Burnley</b><br /><br />
      год основания: <b>1882</b><br /><br />
      арена: <b>Turf Moor</b><br />
      вместительность: <b>22546</b><br /><br />
      <a href="http://www.burnleyfootballclub.com" target="_blank">http://www.burnleyfootballclub.com</a>
<?php if ($edit == "BURNLEY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BURNLEY"] as $pos => $bomber)
  if ($edit == "BURNLEY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BURNLEY"] as $pos => $bomber)
  if ($edit == "BURNLEY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BURNLEY(*)"] as $pos => $bomber)
  if ($edit == "BURNLEY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BURNLEY(*)"] as $pos => $bomber)
  if ($edit == "BURNLEY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandCARDIFF.png" alt="" /></td>
    <td>
      <br />
      <b>Cardiff City FC</b><br /><br />
      город: <b>Cardiff</b><br /><br />
      год основания: <b>1889</b><br /><br />
      арена: <b>Cardiff City Stadium</b><br />
      вместительность: <b>33000</b><br /><br />
      <a href="http://www.cardiffcityfc.co.uk" target="_blank">http://www.cardiffcityfc.co.uk</a>
<?php if ($edit == "CARDIFF") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CARDIFF"] as $pos => $bomber)
  if ($edit == "CARDIFF")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CARDIFF"] as $pos => $bomber)
  if ($edit == "CARDIFF")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CARDIFF(*)"] as $pos => $bomber)
  if ($edit == "CARDIFF")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CARDIFF(*)"] as $pos => $bomber)
  if ($edit == "CARDIFF")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandCHARLTON.png" alt="" /></td>
    <td>
      <br />
      <b>Charlton Athletic FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>The Valley</b><br />
      вместительность: <b>27111</b><br /><br />
      <a href="http://www.cafc.co.uk" target="_blank">http://www.cafc.co.uk</a>
<?php if ($edit == "CHARLTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHARLTON"] as $pos => $bomber)
  if ($edit == "CHARLTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHARLTON"] as $pos => $bomber)
  if ($edit == "CHARLTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHARLTON(*)"] as $pos => $bomber)
  if ($edit == "CHARLTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHARLTON(*)"] as $pos => $bomber)
  if ($edit == "CHARLTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandCHELSEA.png" alt="" /></td>
    <td>
      <br />
      <b>Chelsea FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Stamford Bridge</b><br />
      вместительность: <b>41841</b><br /><br />
      <a href="http://www.chelseafc.com" target="_blank">http://www.chelseafc.com</a>
<?php if ($edit == "CHELSEA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHELSEA"] as $pos => $bomber)
  if ($edit == "CHELSEA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHELSEA"] as $pos => $bomber)
  if ($edit == "CHELSEA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHELSEA(*)"] as $pos => $bomber)
  if ($edit == "CHELSEA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHELSEA(*)"] as $pos => $bomber)
  if ($edit == "CHELSEA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandCRYSTAL.png" alt="" /></td>
    <td>
      <br />
      <b>Crystal Palace FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Selhurst Park</b><br />
      вместительность: <b>26309</b><br /><br />
      <a href="http://www.cpfc.co.uk" target="_blank">http://www.cpfc.co.uk</a>
<?php if ($edit == "CRYSTAL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CRYSTAL"] as $pos => $bomber)
  if ($edit == "CRYSTAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CRYSTAL"] as $pos => $bomber)
  if ($edit == "CRYSTAL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CRYSTAL(*)"] as $pos => $bomber)
  if ($edit == "CRYSTAL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CRYSTAL(*)"] as $pos => $bomber)
  if ($edit == "CRYSTAL")
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
      <a href="http://www.dcfc.co.uk" target="_blank">http://www.dcfc.co.uk</a>
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
    <td><br /><img src="images/englandEVERTON.png" alt="" /></td>
    <td>
      <br />
      <b>Everton FC</b><br /><br />
      город: <b>Liverpool</b><br /><br />
      год основания: <b>1878</b><br /><br />
      арена: <b>Goodison Park</b><br />
      вместительность: <b>40569</b><br /><br />
      <a href="http://www.evertonfc.com" target="_blank">http://www.evertonfc.com</a>
<?php if ($edit == "EVERTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["EVERTON"] as $pos => $bomber)
  if ($edit == "EVERTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EVERTON"] as $pos => $bomber)
  if ($edit == "EVERTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EVERTON(*)"] as $pos => $bomber)
  if ($edit == "EVERTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EVERTON(*)"] as $pos => $bomber)
  if ($edit == "EVERTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandFULHAM.png" alt="" /></td>
    <td>
      <br />
      <b>Fulham FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1879</b><br /><br />
      арена: <b>Craven Cottage</b><br />
      вместительность: <b>25700</b><br /><br />
      <a href="http://www.fulhamfc.com" target="_blank">http://www.fulhamfc.com</a>
<?php if ($edit == "FULHAM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FULHAM"] as $pos => $bomber)
  if ($edit == "FULHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FULHAM"] as $pos => $bomber)
  if ($edit == "FULHAM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FULHAM(*)"] as $pos => $bomber)
  if ($edit == "FULHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FULHAM(*)"] as $pos => $bomber)
  if ($edit == "FULHAM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandHUDDERS.png" alt="" /></td>
    <td>
      <br />
      <b>Huddersfield Town FC</b><br /><br />
      город: <b>Huddersfield, West Yorkshire</b><br /><br />
      год основания: <b>1908</b><br /><br />
      арена: <b>The John Smith's Stadium</b><br />
      вместительность: <b>24554</b><br /><br />
      <a href="http://www.htafc.com" target="_blank">http://www.htafc.com</a>
<?php if ($edit == "HUDDERS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HUDDERS"] as $pos => $bomber)
  if ($edit == "HUDDERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HUDDERS"] as $pos => $bomber)
  if ($edit == "HUDDERS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HUDDERS(*)"] as $pos => $bomber)
  if ($edit == "HUDDERS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HUDDERS(*)"] as $pos => $bomber)
  if ($edit == "HUDDERS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandHULLCITY.png" alt="" /></td>
    <td>
      <br />
      <b>Hull City AFC</b><br /><br />
      город: <b>Hull</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Kingston Communications Stadium</b><br />
      вместительность: <b>25504</b><br /><br />
      <a href="http://www.hullcityafc.net" target="_blank">http://www.hullcityafc.net</a>
<?php if ($edit == "HULLCITY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HULLCITY"] as $pos => $bomber)
  if ($edit == "HULLCITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HULLCITY"] as $pos => $bomber)
  if ($edit == "HULLCITY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HULLCITY(*)"] as $pos => $bomber)
  if ($edit == "HULLCITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HULLCITY(*)"] as $pos => $bomber)
  if ($edit == "HULLCITY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandLEEDS.png" alt="" /></td>
    <td>
      <br />
      <b>Leeds United FC</b><br /><br />
      город: <b>Leeds, West Yorkshire</b><br /><br />
      год основания: <b>1919</b><br /><br />
      арена: <b>Elland Road</b><br />
      вместительность: <b>40204</b><br /><br />
      <a href="http://www.leedsunited.com" target="_blank">http://www.leedsunited.com</a>
<?php if ($edit == "LEEDS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LEEDS"] as $pos => $bomber)
  if ($edit == "LEEDS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEEDS"] as $pos => $bomber)
  if ($edit == "LEEDS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEEDS(*)"] as $pos => $bomber)
  if ($edit == "LEEDS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEEDS(*)"] as $pos => $bomber)
  if ($edit == "LEEDS")
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
      <a href="http://www.lcfc.com" target="_blank">http://www.lcfc.com</a>
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
    <td><br /><img src="images/englandLIVERPOOL.png" alt="" /></td>
    <td>
      <br />
      <b>Liverpool FC</b><br /><br />
      город: <b>Liverpool</b><br /><br />
      год основания: <b>1892</b><br /><br />
      арена: <b>Anfield</b><br />
      вместительность: <b>45362</b><br /><br />
      <a href="http://www.liverpoolfc.tv" target="_blank">http://www.liverpoolfc.tv</a>
<?php if ($edit == "LIVERPOOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LIVERPOOL"] as $pos => $bomber)
  if ($edit == "LIVERPOOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVERPOOL"] as $pos => $bomber)
  if ($edit == "LIVERPOOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVERPOOL(*)"] as $pos => $bomber)
  if ($edit == "LIVERPOOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LIVERPOOL(*)"] as $pos => $bomber)
  if ($edit == "LIVERPOOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandMANCITY.png" alt="" /></td>
    <td>
      <br />
      <b>Manchester City FC</b><br /><br />
      город: <b>Manchester</b><br /><br />
      год основания: <b>1880</b><br /><br />
      арена: <b>Etihad Stadium</b><br />
      вместительность: <b>47726</b><br /><br />
      <a href="http://www.mcfc.co.uk" target="_blank">http://www.mcfc.co.uk</a>
<?php if ($edit == "MANCITY") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MANCITY"] as $pos => $bomber)
  if ($edit == "MANCITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANCITY"] as $pos => $bomber)
  if ($edit == "MANCITY")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANCITY(*)"] as $pos => $bomber)
  if ($edit == "MANCITY")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANCITY(*)"] as $pos => $bomber)
  if ($edit == "MANCITY")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandMANUNITED.png" alt="" /></td>
    <td>
      <br />
      <b>Manchester United FC</b><br /><br />
      город: <b>Manchester</b><br /><br />
      год основания: <b>1878</b><br /><br />
      арена: <b>Old Trafford</b><br />
      вместительность: <b>76212</b><br /><br />
      <a href="http://www.manutd.com" target="_blank">http://www.manutd.com</a>
<?php if ($edit == "MANUNITED") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MANUNITED"] as $pos => $bomber)
  if ($edit == "MANUNITED")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANUNITED"] as $pos => $bomber)
  if ($edit == "MANUNITED")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANUNITED(*)"] as $pos => $bomber)
  if ($edit == "MANUNITED")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MANUNITED(*)"] as $pos => $bomber)
  if ($edit == "MANUNITED")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandMIDDLES.png" alt="" /></td>
    <td>
      <br />
      <b>Middlesbrough FC</b><br /><br />
      город: <b>Middlesbrough</b><br /><br />
      год основания: <b>1876</b><br /><br />
      арена: <b>Riverside Stadium</b><br />
      вместительность: <b>34988</b><br /><br />
      <a href="http://www.mfc.co.uk" target="_blank">http://www.mfc.co.uk</a>
<?php if ($edit == "MIDDLES") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MIDDLES"] as $pos => $bomber)
  if ($edit == "MIDDLES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MIDDLES"] as $pos => $bomber)
  if ($edit == "MIDDLES")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MIDDLES(*)"] as $pos => $bomber)
  if ($edit == "MIDDLES")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MIDDLES(*)"] as $pos => $bomber)
  if ($edit == "MIDDLES")
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
      <a href="http://www.nufc.co.uk" target="_blank">http://www.nufc.co.uk</a>
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
    <td><br /><img src="images/englandNORWICH.png" alt="" /></td>
    <td>
      <br />
      <b>Norwich City FC</b><br /><br />
      город: <b>Norwich, Norfolk</b><br /><br />
      год основания: <b>1902</b><br /><br />
      арена: <b>Carrow Road</b><br />
      вместительность: <b>27244</b><br /><br />
      <a href="http://www.canaries.co.uk" target="_blank">http://www.canaries.co.uk</a>
<?php if ($edit == "NORWICH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NORWICH"] as $pos => $bomber)
  if ($edit == "NORWICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NORWICH"] as $pos => $bomber)
  if ($edit == "NORWICH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NORWICH(*)"] as $pos => $bomber)
  if ($edit == "NORWICH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NORWICH(*)"] as $pos => $bomber)
  if ($edit == "NORWICH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandNOTTING.png" alt="" /></td>
    <td>
      <br />
      <b>Nottingham Forest FC</b><br /><br />
      город: <b>Nottingham</b><br /><br />
      год основания: <b>1865</b><br /><br />
      арена: <b>The City Ground</b><br />
      вместительность: <b>30576</b><br /><br />
      <a href="http://www.nottinghamforest.co.uk" target="_blank">http://www.nottinghamforest.co.uk</a>
<?php if ($edit == "NOTTING") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NOTTING"] as $pos => $bomber)
  if ($edit == "NOTTING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOTTING"] as $pos => $bomber)
  if ($edit == "NOTTING")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOTTING(*)"] as $pos => $bomber)
  if ($edit == "NOTTING")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NOTTING(*)"] as $pos => $bomber)
  if ($edit == "NOTTING")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandPORTSM.png" alt="" /></td>
    <td>
      <br />
      <b>Portsmouth FC</b><br /><br />
      город: <b>Portsmouth</b><br /><br />
      год основания: <b>1898</b><br /><br />
      арена: <b>Fratton Park</b><br />
      вместительность: <b>20821</b><br /><br />
      <a href="http://www.portsmouthfc.co.uk" target="_blank">http://www.portsmouthfc.co.uk</a>
<?php if ($edit == "PORTSM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PORTSM"] as $pos => $bomber)
  if ($edit == "PORTSM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTSM"] as $pos => $bomber)
  if ($edit == "PORTSM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTSM(*)"] as $pos => $bomber)
  if ($edit == "PORTSM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTSM(*)"] as $pos => $bomber)
  if ($edit == "PORTSM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandPRESTON.png" alt="" /></td>
    <td>
      <br />
      <b>Preston North End FC</b><br /><br />
      город: <b>Preston</b><br /><br />
      год основания: <b>1863</b><br /><br />
      арена: <b>Deepdale Stadium</b><br />
      вместительность: <b>23408</b><br /><br />
      <a href="http://www.pnefc.net" target="_blank">http://www.pnefc.net</a>
<?php if ($edit == "PRESTON") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PRESTON"] as $pos => $bomber)
  if ($edit == "PRESTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PRESTON"] as $pos => $bomber)
  if ($edit == "PRESTON")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PRESTON(*)"] as $pos => $bomber)
  if ($edit == "PRESTON")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PRESTON(*)"] as $pos => $bomber)
  if ($edit == "PRESTON")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandQUEENS.png" alt="" /></td>
    <td>
      <br />
      <b>Queens Park Rangers FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1885</b><br /><br />
      арена: <b>Loftus Road Stadium</b><br />
      вместительность: <b>18360</b><br /><br />
      <a href="http://www.qpr.co.uk" target="_blank">http://www.qpr.co.uk</a>
<?php if ($edit == "QUEENS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["QUEENS"] as $pos => $bomber)
  if ($edit == "QUEENS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["QUEENS"] as $pos => $bomber)
  if ($edit == "QUEENS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["QUEENS(*)"] as $pos => $bomber)
  if ($edit == "QUEENS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["QUEENS(*)"] as $pos => $bomber)
  if ($edit == "QUEENS")
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
      <a href="http://www.readingfc.co.uk" target="_blank">http://www.readingfc.co.uk</a>
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
    <td><br /><img src="images/englandSHEFFIELD.png" alt="" /></td>
    <td>
      <br />
      <b>Sheffield United FC</b><br /><br />
      город: <b>Sheffield</b><br /><br />
      год основания: <b>1889</b><br /><br />
      арена: <b>Bramall Lane</b><br />
      вместительность: <b>32702</b><br /><br />
      <a href="http://www.sufc.co.uk" target="_blank">http://www.sufc.co.uk</a>
<?php if ($edit == "SHEFFIELD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SHEFFIELD"] as $pos => $bomber)
  if ($edit == "SHEFFIELD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHEFFIELD"] as $pos => $bomber)
  if ($edit == "SHEFFIELD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHEFFIELD(*)"] as $pos => $bomber)
  if ($edit == "SHEFFIELD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SHEFFIELD(*)"] as $pos => $bomber)
  if ($edit == "SHEFFIELD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandSOUTH.png" alt="" /></td>
    <td>
      <br />
      <b>Southampton FC</b><br /><br />
      город: <b>Southampton, Hampshire</b><br /><br />
      год основания: <b>1885</b><br /><br />
      арена: <b>St. Mary's Stadium</b><br />
      вместительность: <b>32689</b><br /><br />
      <a href="http://www.saintsfc.co.uk" target="_blank">http://www.saintsfc.co.uk</a>
<?php if ($edit == "SOUTH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SOUTH"] as $pos => $bomber)
  if ($edit == "SOUTH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOUTH"] as $pos => $bomber)
  if ($edit == "SOUTH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOUTH(*)"] as $pos => $bomber)
  if ($edit == "SOUTH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SOUTH(*)"] as $pos => $bomber)
  if ($edit == "SOUTH")
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
      <a href="http://www.stokecityfc.com" target="_blank">http://www.stokecityfc.com</a>
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
    <td><br /><img src="images/englandSUNDER.png" alt="" /></td>
    <td>
      <br />
      <b>Sunderland AFC</b><br /><br />
      город: <b>Sunderland</b><br /><br />
      год основания: <b>1879</b><br /><br />
      арена: <b>Stadium of Light</b><br />
      вместительность: <b>49000</b><br /><br />
      <a href="http://www.safc.com" target="_blank">http://www.safc.com</a>
<?php if ($edit == "SUNDER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SUNDER"] as $pos => $bomber)
  if ($edit == "SUNDER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SUNDER"] as $pos => $bomber)
  if ($edit == "SUNDER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SUNDER(*)"] as $pos => $bomber)
  if ($edit == "SUNDER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SUNDER(*)"] as $pos => $bomber)
  if ($edit == "SUNDER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandSWANSEA.png" alt="" /></td>
    <td>
      <br />
      <b>Swansea City AFC</b><br /><br />
      город: <b>Swansea</b><br /><br />
      год основания: <b>1912</b><br /><br />
      арена: <b>Liberty Stadium</b><br />
      вместительность: <b>20750</b><br /><br />
      <a href="http://www.swanseacity.net" target="_blank">http://www.swanseacity.net</a>
<?php if ($edit == "SWANSEA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SWANSEA"] as $pos => $bomber)
  if ($edit == "SWANSEA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SWANSEA"] as $pos => $bomber)
  if ($edit == "SWANSEA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SWANSEA(*)"] as $pos => $bomber)
  if ($edit == "SWANSEA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SWANSEA(*)"] as $pos => $bomber)
  if ($edit == "SWANSEA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandTOTTENHAM.png" alt="" /></td>
    <td>
      <br />
      <b>Tottenham Hotspur FC</b><br /><br />
      город: <b>London</b><br /><br />
      год основания: <b>1882</b><br /><br />
      арена: <b>White Hart Lane</b><br />
      вместительность: <b>36763</b><br /><br />
      <a href="http://www.tottenhamhotspur.com" target="_blank">http://www.tottenhamhotspur.com</a>
<?php if ($edit == "TOTTENHAM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["TOTTENHAM"] as $pos => $bomber)
  if ($edit == "TOTTENHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOTTENHAM"] as $pos => $bomber)
  if ($edit == "TOTTENHAM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOTTENHAM(*)"] as $pos => $bomber)
  if ($edit == "TOTTENHAM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["TOTTENHAM(*)"] as $pos => $bomber)
  if ($edit == "TOTTENHAM")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandWATFORD.png" alt="" /></td>
    <td>
      <br />
      <b>Watford FC</b><br /><br />
      город: <b>Watford</b><br /><br />
      год основания: <b>1881</b><br /><br />
      арена: <b>Vicarage Road Stadium</b><br />
      вместительность: <b>21577</b><br /><br />
      <a href="http://www.watfordfc.com" target="_blank">http://www.watfordfc.com</a>
<?php if ($edit == "WATFORD") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WATFORD"] as $pos => $bomber)
  if ($edit == "WATFORD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WATFORD"] as $pos => $bomber)
  if ($edit == "WATFORD")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WATFORD(*)"] as $pos => $bomber)
  if ($edit == "WATFORD")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WATFORD(*)"] as $pos => $bomber)
  if ($edit == "WATFORD")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/englandWESTBA.png" alt="" /></td>
    <td>
      <br />
      <b>West Bromwich Albion FC</b><br /><br />
      город: <b>West Bromwich</b><br /><br />
      год основания: <b>1878</b><br /><br />
      арена: <b>The Hawthorns</b><br />
      вместительность: <b>28003</b><br /><br />
      <a href="http://www.wbafc.co.uk" target="_blank">http://www.wbafc.co.uk</a>
<?php if ($edit == "WESTBA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WESTBA"] as $pos => $bomber)
  if ($edit == "WESTBA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTBA"] as $pos => $bomber)
  if ($edit == "WESTBA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTBA(*)"] as $pos => $bomber)
  if ($edit == "WESTBA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WESTBA(*)"] as $pos => $bomber)
  if ($edit == "WESTBA")
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
      <a href="http://www.whufc.com" target="_blank">http://www.whufc.com</a>
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
    <td><br /><img src="images/englandWIGAN.png" alt="" /></td>
    <td>
      <br />
      <b>Wigan Athletic FC</b><br /><br />
      город: <b>Wigan</b><br /><br />
      год основания: <b>1932</b><br /><br />
      арена: <b>The DW Stadium</b><br />
      вместительность: <b>25138</b><br /><br />
      <a href="http://www.wiganlatics.co.uk" target="_blank">http://www.wiganlatics.co.uk</a>
<?php if ($edit == "WIGAN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["WIGAN"] as $pos => $bomber)
  if ($edit == "WIGAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WIGAN"] as $pos => $bomber)
  if ($edit == "WIGAN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WIGAN(*)"] as $pos => $bomber)
  if ($edit == "WIGAN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["WIGAN(*)"] as $pos => $bomber)
  if ($edit == "WIGAN")
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
      <a href="http://www.wolves.co.uk" target="_blank">http://www.wolves.co.uk</a>
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
</table></form>
