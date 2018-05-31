<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="23%"><b>основной состав</b></td><td></td><td width="23%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/germany1860.png" alt="" /></td>
    <td>
      <br />
      <b>TSV 1860 München</b><br /><br />
      город: <b>München</b><br /><br />
      год основания: <b>1860</b><br /><br />
      арена: <b>Allianz-Arena</b><br />
      вместительность: <b>71901</b><br /><br />
      <a href="http://www.tsv1860.de" target="_blank">http://www.tsv1860.de</a>
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
      <a href="http://www.alemannia-aachen.de" target="_blank">http://www.alemannia-aachen.de</a>
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
    <td><br /><img src="images/germanyARMINIA.png" alt="" /></td>
    <td>
      <br />
      <b>DSC Arminia Bielefeld</b><br /><br />
      город: <b>Bielefeld</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>SchücoArena</b><br />
      вместительность: <b>27300</b><br /><br />
      <a href="http://www.arminia-bielefeld.de" target="_blank">http://www.arminia-bielefeld.de</a>
<?php if ($edit == "ARMINIA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ARMINIA"] as $pos => $bomber)
  if ($edit == "ARMINIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARMINIA"] as $pos => $bomber)
  if ($edit == "ARMINIA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARMINIA(*)"] as $pos => $bomber)
  if ($edit == "ARMINIA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ARMINIA(*)"] as $pos => $bomber)
  if ($edit == "ARMINIA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyAUGSBURG.png" alt="" /></td>
    <td>
      <br />
      <b>FC Augsburg</b><br /><br />
      город: <b>Augsburg</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>WWK Arena</b><br />
      вместительность: <b>30662</b><br /><br />
      <a href="http://www.fcaugsburg.de" target="_blank">http://www.fcaugsburg.de</a>
<?php if ($edit == "AUGSBURG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AUGSBURG"] as $pos => $bomber)
  if ($edit == "AUGSBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUGSBURG"] as $pos => $bomber)
  if ($edit == "AUGSBURG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUGSBURG(*)"] as $pos => $bomber)
  if ($edit == "AUGSBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AUGSBURG(*)"] as $pos => $bomber)
  if ($edit == "AUGSBURG")
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
      <a href="http://www.bayer04.de" target="_blank">http://www.bayer04.de</a>
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
    <td><br /><img src="images/germanyBAYERN.png" alt="" /></td>
    <td>
      <br />
      <b>FC Bayern München</b><br /><br />
      город: <b>München</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Allianz-Arena</b><br />
      вместительность: <b>71901</b><br /><br />
      <a href="http://www.fcbayern.telekom.de" target="_blank">http://www.fcbayern.telekom.de</a>
<?php if ($edit == "BAYERN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BAYERN"] as $pos => $bomber)
  if ($edit == "BAYERN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYERN"] as $pos => $bomber)
  if ($edit == "BAYERN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYERN(*)"] as $pos => $bomber)
  if ($edit == "BAYERN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BAYERN(*)"] as $pos => $bomber)
  if ($edit == "BAYERN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyBOCHUM.png" alt="" /></td>
    <td>
      <br />
      <b>VFL Bochum 1848</b><br /><br />
      город: <b>Bochum</b><br /><br />
      год основания: <b>1848</b><br /><br />
      арена: <b>rewirpowerSTADION</b><br />
      вместительность: <b>30748</b><br /><br />
      <a href="http://www.vfl-bochum.de" target="_blank">http://www.vfl-bochum.de</a>
<?php if ($edit == "BOCHUM") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOCHUM"] as $pos => $bomber)
  if ($edit == "BOCHUM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOCHUM"] as $pos => $bomber)
  if ($edit == "BOCHUM")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOCHUM(*)"] as $pos => $bomber)
  if ($edit == "BOCHUM")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOCHUM(*)"] as $pos => $bomber)
  if ($edit == "BOCHUM")
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
      <a href="http://www.bvb.de" target="_blank">http://www.bvb.de</a>
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
    <td><br /><img src="images/germanyBOR-M.png" alt="" /></td>
    <td>
      <br />
      <b>Borussia VfL Mönchengladbach</b><br /><br />
      город: <b>Mönchengladbach</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Stadion im BORUSSIA-PARK</b><br />
      вместительность: <b>54057</b><br /><br />
      <a href="http://www.borussia.de" target="_blank">http://www.borussia.de</a>
<?php if ($edit == "BOR-M") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOR-M"] as $pos => $bomber)
  if ($edit == "BOR-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-M"] as $pos => $bomber)
  if ($edit == "BOR-M")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-M(*)"] as $pos => $bomber)
  if ($edit == "BOR-M")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOR-M(*)"] as $pos => $bomber)
  if ($edit == "BOR-M")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyBRAUNSCH.png" alt="" /></td>
    <td>
      <br />
      <b>Braunschweiger TSV Eintracht 1895</b><br /><br />
      город: <b>Braunschweig</b><br /><br />
      год основания: <b>1895</b><br /><br />
      арена: <b>Eintracht-Stadion</b><br />
      вместительность: <b>23325</b><br /><br />
      <a href="http://www.eintracht.com" target="_blank">http://www.eintracht.com</a>
<?php if ($edit == "BRAUNSCH") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRAUNSCH"] as $pos => $bomber)
  if ($edit == "BRAUNSCH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAUNSCH"] as $pos => $bomber)
  if ($edit == "BRAUNSCH")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAUNSCH(*)"] as $pos => $bomber)
  if ($edit == "BRAUNSCH")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAUNSCH(*)"] as $pos => $bomber)
  if ($edit == "BRAUNSCH")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyDARMSTADT.png" alt="" /></td>
    <td>
      <br />
      <b>SV Darmstadt 1898</b><br /><br />
      город: <b>Darmstadt</b><br /><br />
      год основания: <b>1898</b><br /><br />
      арена: <b>Merck-Stadion am Böllenfalltor</b><br />
      вместительность: <b>16500</b><br /><br />
      <a href="http://www.sv98.de" target="_blank">http://www.sv98.de</a>
<?php if ($edit == "DARMSTADT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DARMSTADT"] as $pos => $bomber)
  if ($edit == "DARMSTADT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DARMSTADT"] as $pos => $bomber)
  if ($edit == "DARMSTADT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DARMSTADT(*)"] as $pos => $bomber)
  if ($edit == "DARMSTADT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DARMSTADT(*)"] as $pos => $bomber)
  if ($edit == "DARMSTADT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyDUISBURG.png" alt="" /></td>
    <td>
      <br />
      <b>MDV Duisburg</b><br /><br />
      город: <b>Duisburg</b><br /><br />
      год основания: <b>1902</b><br /><br />
      арена: <b>Schauinsland-Reisen-Arena</b><br />
      вместительность: <b>31514</b><br /><br />
      <a href="http://www.msv-duisburg.de" target="_blank">http://www.msv-duisburg.de</a>
<?php if ($edit == "DUISBURG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["DUISBURG"] as $pos => $bomber)
  if ($edit == "DUISBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUISBURG"] as $pos => $bomber)
  if ($edit == "DUISBURG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUISBURG(*)"] as $pos => $bomber)
  if ($edit == "DUISBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["DUISBURG(*)"] as $pos => $bomber)
  if ($edit == "DUISBURG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyEINTRACHT.png" alt="" /></td>
    <td>
      <br />
      <b>Eintracht Frankfurt</b><br /><br />
      город: <b>Frankfurt am Main</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Commerzbank-Arena</b><br />
      вместительность: <b>52300</b><br /><br />
      <a href="http://www.eintracht.de" target="_blank">http://www.eintracht.de</a>
<?php if ($edit == "EINTRACHT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["EINTRACHT"] as $pos => $bomber)
  if ($edit == "EINTRACHT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINTRACHT"] as $pos => $bomber)
  if ($edit == "EINTRACHT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINTRACHT(*)"] as $pos => $bomber)
  if ($edit == "EINTRACHT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["EINTRACHT(*)"] as $pos => $bomber)
  if ($edit == "EINTRACHT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyFREIBURG.png" alt="" /></td>
    <td>
      <br />
      <b>SC Freiburg</b><br /><br />
      город: <b>Freiburg</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Schwarzwald-Stadion</b><br />
      вместительность: <b>24000</b><br /><br />
      <a href="http://www.scfreiburg.com" target="_blank">http://www.scfreiburg.com</a>
<?php if ($edit == "FREIBURG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FREIBURG"] as $pos => $bomber)
  if ($edit == "FREIBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FREIBURG"] as $pos => $bomber)
  if ($edit == "FREIBURG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FREIBURG(*)"] as $pos => $bomber)
  if ($edit == "FREIBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FREIBURG(*)"] as $pos => $bomber)
  if ($edit == "FREIBURG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyFORTUNA.png" alt="" /></td>
    <td>
      <br />
      <b>Düsseldorfer TUS Fortuna 1895</b><br /><br />
      город: <b>Düsseldorf</b><br /><br />
      год основания: <b>1895</b><br /><br />
      арена: <b>ESPRIT arena</b><br />
      вместительность: <b>54500</b><br /><br />
      <a href="http://www.fortuna-duesseldorf.de" target="_blank">http://www.fortuna-duesseldorf.de</a>
<?php if ($edit == "FORTUNA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["FORTUNA"] as $pos => $bomber)
  if ($edit == "FORTUNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FORTUNA"] as $pos => $bomber)
  if ($edit == "FORTUNA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FORTUNA(*)"] as $pos => $bomber)
  if ($edit == "FORTUNA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["FORTUNA(*)"] as $pos => $bomber)
  if ($edit == "FORTUNA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyGREUTHER.png" alt="" /></td>
    <td>
      <br />
      <b>SPVGG Greuther Fürth</b><br /><br />
      город: <b>Fürth</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Stadion am Laubenweg</b><br />
      вместительность: <b>18000</b><br /><br />
      <a href="http://www.greuther-fuerth.de" target="_blank">http://www.greuther-fuerth.de</a>
<?php if ($edit == "GREUTHER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GREUTHER"] as $pos => $bomber)
  if ($edit == "GREUTHER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GREUTHER"] as $pos => $bomber)
  if ($edit == "GREUTHER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GREUTHER(*)"] as $pos => $bomber)
  if ($edit == "GREUTHER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GREUTHER(*)"] as $pos => $bomber)
  if ($edit == "GREUTHER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHANNOVER.png" alt="" /></td>
    <td>
      <br />
      <b>Hannover 96</b><br /><br />
      город: <b>Hannover</b><br /><br />
      год основания: <b>1896</b><br /><br />
      арена: <b>HDI-Arena</b><br />
      вместительность: <b>49000</b><br /><br />
      <a href="http://www.hannover96.de" target="_blank">http://www.hannover96.de</a>
<?php if ($edit == "HANNOVER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HANNOVER"] as $pos => $bomber)
  if ($edit == "HANNOVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANNOVER"] as $pos => $bomber)
  if ($edit == "HANNOVER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANNOVER(*)"] as $pos => $bomber)
  if ($edit == "HANNOVER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANNOVER(*)"] as $pos => $bomber)
  if ($edit == "HANNOVER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHANSA.png" alt="" /></td>
    <td>
      <br />
      <b>FC Hansa Rostock</b><br /><br />
      город: <b>Rostock</b><br /><br />
      год основания: <b>1965</b><br /><br />
      арена: <b>DKB Arena</b><br />
      вместительность: <b>29000</b><br /><br />
      <a href="http://www.fc-hansa.de" target="_blank">http://www.fc-hansa.de</a>
<?php if ($edit == "HANSA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HANSA"] as $pos => $bomber)
  if ($edit == "HANSA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANSA"] as $pos => $bomber)
  if ($edit == "HANSA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANSA(*)"] as $pos => $bomber)
  if ($edit == "HANSA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HANSA(*)"] as $pos => $bomber)
  if ($edit == "HANSA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHAMBURG.png" alt="" /></td>
    <td>
      <br />
      <b>Hamburger SV</b><br /><br />
      город: <b>Hamburg</b><br /><br />
      год основания: <b>1887</b><br /><br />
      арена: <b>Imtech Arena</b><br />
      вместительность: <b>57030</b><br /><br />
      <a href="http://www.hsv.de" target="_blank">http://www.hsv.de</a>
<?php if ($edit == "HAMBURG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HAMBURG"] as $pos => $bomber)
  if ($edit == "HAMBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMBURG"] as $pos => $bomber)
  if ($edit == "HAMBURG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMBURG(*)"] as $pos => $bomber)
  if ($edit == "HAMBURG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HAMBURG(*)"] as $pos => $bomber)
  if ($edit == "HAMBURG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHEIDEN.png" alt="" /></td>
    <td>
      <br />
      <b>1 FC. Heidenheim 1946</b><br /><br />
      город: <b>Heidenheim an der Brenz</b><br /><br />
      год основания: <b>1946</b><br /><br />
      арена: <b>Voith-Arena</b><br />
      вместительность: <b>15000</b><br /><br />
      <a href="http://www.fc-heidenheim.de" target="_blank">http://www.fc-heidenheim.de</a>
<?php if ($edit == "HEIDEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HEIDEN"] as $pos => $bomber)
  if ($edit == "HEIDEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEIDEN"] as $pos => $bomber)
  if ($edit == "HEIDEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEIDEN(*)"] as $pos => $bomber)
  if ($edit == "HEIDEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HEIDEN(*)"] as $pos => $bomber)
  if ($edit == "HEIDEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHERTHA.png" alt="" /></td>
    <td>
      <br />
      <b>Hertha BSC</b><br /><br />
      город: <b>Berlin</b><br /><br />
      год основания: <b>1892</b><br /><br />
      арена: <b>Olympiastadion Berlin</b><br />
      вместительность: <b>77116</b><br /><br />
      <a href="http://www.herthabsc.de" target="_blank">http://www.herthabsc.de</a>
<?php if ($edit == "HERTHA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HERTHA"] as $pos => $bomber)
  if ($edit == "HERTHA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERTHA"] as $pos => $bomber)
  if ($edit == "HERTHA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERTHA(*)"] as $pos => $bomber)
  if ($edit == "HERTHA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HERTHA(*)"] as $pos => $bomber)
  if ($edit == "HERTHA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyHOFFEN.png" alt="" /></td>
    <td>
      <br />
      <b>TSG 1899 Hoffenheim</b><br /><br />
      город: <b>Sinsheim-Hoffenheim</b><br /><br />
      год основания: <b>1899</b><br /><br />
      арена: <b>Wirsol Rhein-Neckar-Arena</b><br />
      вместительность: <b>30164</b><br /><br />
      <a href="http://www.achtzehn99.de" target="_blank">http://www.achtzehn99.de</a>
<?php if ($edit == "HOFFEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["HOFFEN"] as $pos => $bomber)
  if ($edit == "HOFFEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HOFFEN"] as $pos => $bomber)
  if ($edit == "HOFFEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HOFFEN(*)"] as $pos => $bomber)
  if ($edit == "HOFFEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["HOFFEN(*)"] as $pos => $bomber)
  if ($edit == "HOFFEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyINGOL.png" alt="" /></td>
    <td>
      <br />
      <b>FC Ingolstadt 04</b><br /><br />
      город: <b>Ingolstadt</b><br /><br />
      год основания: <b>2004</b><br /><br />
      арена: <b>Audi-Sportpark</b><br />
      вместительность: <b>15729</b><br /><br />
      <a href="http://www.fcingolstadt.de" target="_blank">http://www.fcingolstadt.de</a>
<?php if ($edit == "INGOL") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["INGOL"] as $pos => $bomber)
  if ($edit == "INGOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INGOL"] as $pos => $bomber)
  if ($edit == "INGOL")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INGOL(*)"] as $pos => $bomber)
  if ($edit == "INGOL")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["INGOL(*)"] as $pos => $bomber)
  if ($edit == "INGOL")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyKAISER.png" alt="" /></td>
    <td>
      <br />
      <b>1. FC Kaiserslautern</b><br /><br />
      город: <b>Kaiserslautern</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Fritz-Walter-Stadion</b><br />
      вместительность: <b>49780</b><br /><br />
      <a href="http://www.fck.de" target="_blank">http://www.fck.de</a>
<?php if ($edit == "KAISER") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KAISER"] as $pos => $bomber)
  if ($edit == "KAISER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAISER"] as $pos => $bomber)
  if ($edit == "KAISER")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAISER(*)"] as $pos => $bomber)
  if ($edit == "KAISER")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KAISER(*)"] as $pos => $bomber)
  if ($edit == "KAISER")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyKARLSRUHER.png" alt="" /></td>
    <td>
      <br />
      <b>Karlsruher SC</b><br /><br />
      город: <b>Karlsruhe</b><br /><br />
      год основания: <b>1898</b><br /><br />
      арена: <b>Wildparkstadion</b><br />
      вместительность: <b>29699</b><br /><br />
      <a href="http://www.ksc.de" target="_blank">http://www.ksc.de</a>
<?php if ($edit == "KARLS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KARLS"] as $pos => $bomber)
  if ($edit == "KARLS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARLS"] as $pos => $bomber)
  if ($edit == "KARLS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARLS(*)"] as $pos => $bomber)
  if ($edit == "KARLS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KARLS(*)"] as $pos => $bomber)
  if ($edit == "KARLS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyKOLN.png" alt="" /></td>
    <td>
      <br />
      <b>1. FC Köln</b><br /><br />
      город: <b>Köln</b><br /><br />
      год основания: <b>1948</b><br /><br />
      арена: <b>RheinEnergieStadion</b><br />
      вместительность: <b>50076</b><br /><br />
      <a href="http://www.fc-koeln.de" target="_blank">http://www.fc-koeln.de</a>
<?php if ($edit == "KOLN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["KOLN"] as $pos => $bomber)
  if ($edit == "KOLN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOLN"] as $pos => $bomber)
  if ($edit == "KOLN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOLN(*)"] as $pos => $bomber)
  if ($edit == "KOLN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["KOLN(*)"] as $pos => $bomber)
  if ($edit == "KOLN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyLEIPZIG.png" alt="" /></td>
    <td>
      <br />
      <b>Rasen Ballsport Leipzig</b><br /><br />
      город: <b>Leipzig</b><br /><br />
      год основания: <b>2009</b><br /><br />
      арена: <b>Red Bull Arena</b><br />
      вместительность: <b>44345</b><br /><br />
      <a href="http://www.dierotenbullen.com" target="_blank">http://www.dierotenbullen.com</a>
<?php if ($edit == "LEIPZIG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["LEIPZIG"] as $pos => $bomber)
  if ($edit == "LEIPZIG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEIPZIG"] as $pos => $bomber)
  if ($edit == "LEIPZIG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEIPZIG(*)"] as $pos => $bomber)
  if ($edit == "LEIPZIG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["LEIPZIG(*)"] as $pos => $bomber)
  if ($edit == "LEIPZIG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyMAINZ.png" alt="" /></td>
    <td>
      <br />
      <b>1. FSV Mainz 05</b><br /><br />
      город: <b>Mainz</b><br /><br />
      год основания: <b>1905</b><br /><br />
      арена: <b>Coface Arena</b><br />
      вместительность: <b>34034</b><br /><br />
      <a href="http://www.mainz05.de" target="_blank">http://www.mainz05.de</a>
<?php if ($edit == "MAINZ") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MAINZ"] as $pos => $bomber)
  if ($edit == "MAINZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MAINZ"] as $pos => $bomber)
  if ($edit == "MAINZ")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MAINZ(*)"] as $pos => $bomber)
  if ($edit == "MAINZ")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MAINZ(*)"] as $pos => $bomber)
  if ($edit == "MAINZ")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyNURNBERG.png" alt="" /></td>
    <td>
      <br />
      <b>1. FC Nürnberg</b><br /><br />
      город: <b>Nürnberg</b><br /><br />
      год основания: <b>1900</b><br /><br />
      арена: <b>Max-Morlock-Stadion</b><br />
      вместительность: <b>50000</b><br /><br />
      <a href="http://www.fcn.de" target="_blank">http://www.fcn.de</a>
<?php if ($edit == "NURNBERG") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NURNBERG"] as $pos => $bomber)
  if ($edit == "NURNBERG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NURNBERG"] as $pos => $bomber)
  if ($edit == "NURNBERG")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NURNBERG(*)"] as $pos => $bomber)
  if ($edit == "NURNBERG")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NURNBERG(*)"] as $pos => $bomber)
  if ($edit == "NURNBERG")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyPADERBORN.png" alt="" /></td>
    <td>
      <br />
      <b>SC Paderborn 07</b><br /><br />
      город: <b>Paderborn</b><br /><br />
      год основания: <b>1907</b><br /><br />
      арена: <b>Benteler-Arena</b><br />
      вместительность: <b>15306</b><br /><br />
      <a href="http://www.scpaderborn07.de" target="_blank">http://www.scpaderborn07.de</a>
<?php if ($edit == "PADERBORN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PADERBORN"] as $pos => $bomber)
  if ($edit == "PADERBORN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PADERBORN"] as $pos => $bomber)
  if ($edit == "PADERBORN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PADERBORN(*)"] as $pos => $bomber)
  if ($edit == "PADERBORN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PADERBORN(*)"] as $pos => $bomber)
  if ($edit == "PADERBORN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanySANDHAUS.png" alt="" /></td>
    <td>
      <br />
      <b>SV Sandhausen</b><br /><br />
      город: <b>Sandhausen</b><br /><br />
      год основания: <b>1916</b><br /><br />
      арена: <b>Hardtwaldstadion</b><br />
      вместительность: <b>15414</b><br /><br />
      <a href="http://www.svsandhausen.de" target="_blank">http://www.svsandhausen.de</a>
<?php if ($edit == "SANDHAUS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SANDHAUS"] as $pos => $bomber)
  if ($edit == "SANDHAUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SANDHAUS"] as $pos => $bomber)
  if ($edit == "SANDHAUS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SANDHAUS(*)"] as $pos => $bomber)
  if ($edit == "SANDHAUS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SANDHAUS(*)"] as $pos => $bomber)
  if ($edit == "SANDHAUS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanySCHALKE.png" alt="" /></td>
    <td>
      <br />
      <b>FC Schalke 04</b><br /><br />
      город: <b>Gelsenkirchen</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Veltins-Arena</b><br />
      вместительность: <b>61973</b><br /><br />
      <a href="http://www.schalke04.de" target="_blank">http://www.schalke04.de</a>
<?php if ($edit == "SCHALKE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SCHALKE"] as $pos => $bomber)
  if ($edit == "SCHALKE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SCHALKE"] as $pos => $bomber)
  if ($edit == "SCHALKE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SCHALKE(*)"] as $pos => $bomber)
  if ($edit == "SCHALKE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SCHALKE(*)"] as $pos => $bomber)
  if ($edit == "SCHALKE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanySTPAULI.png" alt="" /></td>
    <td>
      <br />
      <b>FC St. Pauli</b><br /><br />
      город: <b>Hamburg</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Millerntor-Stadion</b><br />
      вместительность: <b>29546</b><br /><br />
      <a href="http://www.fcstpauli.com" target="_blank">http://www.fcstpauli.com</a>
<?php if ($edit == "STPAULI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STPAULI"] as $pos => $bomber)
  if ($edit == "STPAULI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STPAULI"] as $pos => $bomber)
  if ($edit == "STPAULI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STPAULI(*)"] as $pos => $bomber)
  if ($edit == "STPAULI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STPAULI(*)"] as $pos => $bomber)
  if ($edit == "STPAULI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanySTUTTGART.png" alt="" /></td>
    <td>
      <br />
      <b>VfB Stuttgart 1893</b><br /><br />
      город: <b>Stuttgart</b><br /><br />
      год основания: <b>1893</b><br /><br />
      арена: <b>Mercedes-Benz-Arena</b><br />
      вместительность: <b>60469</b><br /><br />
      <a href="http://www.vfb.de" target="_blank">http://www.vfb.de</a>
<?php if ($edit == "STUTTGART") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["STUTTGART"] as $pos => $bomber)
  if ($edit == "STUTTGART")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STUTTGART"] as $pos => $bomber)
  if ($edit == "STUTTGART")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STUTTGART(*)"] as $pos => $bomber)
  if ($edit == "STUTTGART")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["STUTTGART(*)"] as $pos => $bomber)
  if ($edit == "STUTTGART")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/germanyUNION.png" alt="" /></td>
    <td>
      <br />
      <b>1.FC Union Berlin</b><br /><br />
      город: <b>Berlin</b><br /><br />
      год основания: <b>1966</b><br /><br />
      арена: <b>Stadion An der Alten Försterei</b><br />
      вместительность: <b>21717</b><br /><br />
      <a href="http://www.fc-union-berlin.de" target="_blank">http://www.fc-union-berlin.de</a>
<?php if ($edit == "UNION") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["UNION"] as $pos => $bomber)
  if ($edit == "UNION")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UNION"] as $pos => $bomber)
  if ($edit == "UNION")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UNION(*)"] as $pos => $bomber)
  if ($edit == "UNION")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["UNION(*)"] as $pos => $bomber)
  if ($edit == "UNION")
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
      <a href="http://www.werder.de" target="_blank">http://www.werder.de</a>
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
      <a href="http://www.vfl-wolfsburg.de" target="_blank">http://www.vfl-wolfsburg.de</a>
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
</table></form>
