<?php require ('italy/club-common.inc.php');?>
<form action="" method="post">
<table width="100%">
  <tr><td><?=$msg;?></td><td></td><td></td><td width="20%"><b>основной состав</b></td><td></td><td width="20%"><b>второй состав</b></td></tr>
  <tr>
    <td><br /><img src="images/portugalACADE.png" alt="" /></td>
    <td>
      <br />
      <b>Associação Académica de Coimbra OAF</b><br /><br />
      город: <b>Coimbra</b><br /><br />
      год основания: <b>1887</b><br /><br />
      арена: <b>Estádio EFAPEL</b><br />
      вместительность: <b>30075</b><br /><br />
      <a href="http://www.academica-oaf.pt" target="_blank">http://www.academica-oaf.pt</a>
<?php if ($edit == "ACADE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ACADE"] as $pos => $bomber)
  if ($edit == "ACADE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ACADE"] as $pos => $bomber)
  if ($edit == "ACADE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ACADE(*)"] as $pos => $bomber)
  if ($edit == "ACADE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ACADE(*)"] as $pos => $bomber)
  if ($edit == "ACADE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalAROUC.png" alt="" /></td>
    <td>
      <br />
      <b>FC Arouca</b><br /><br />
      город: <b>Arouca</b><br /><br />
      год основания: <b>1951</b><br /><br />
      арена: <b>Estádio Municipal de Arouca</b><br />
      вместительность: <b>7000</b><br /><br />
      <a href="http://fcarouca.eu" target="_blank">http://fcarouca.eu</a>
<?php if ($edit == "AROUC") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["AROUC"] as $pos => $bomber)
  if ($edit == "AROUC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AROUC"] as $pos => $bomber)
  if ($edit == "AROUC")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AROUC(*)"] as $pos => $bomber)
  if ($edit == "AROUC")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["AROUC(*)"] as $pos => $bomber)
  if ($edit == "AROUC")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalATLET.png" alt="" /></td>
    <td>
      <br />
      <b>Atlético Clube de Portugal</b><br /><br />
      город: <b>Lisboa</b><br /><br />
      год основания: <b>1942</b><br /><br />
      арена: <b>Estádio Da Tapadinha</b><br />
      вместительность: <b>10000</b><br /><br />
      <a href="http://www.atleticocp.pt" target="_blank">http://www.atleticocp.pt</a>
<?php if ($edit == "ATLET") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ATLET"] as $pos => $bomber)
  if ($edit == "ATLET")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLET"] as $pos => $bomber)
  if ($edit == "ATLET")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLET(*)"] as $pos => $bomber)
  if ($edit == "ATLET")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ATLET(*)"] as $pos => $bomber)
  if ($edit == "ATLET")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBELEN.png" alt="" /></td>
    <td>
      <br />
      <b>CF Os Belenenses</b><br /><br />
      город: <b>Lisboa</b><br /><br />
      год основания: <b>1919</b><br /><br />
      арена: <b>Estádio do Restelo</b><br />
      вместительность: <b>25000</b><br /><br />
      <a href="http://www.osbelenenses.com" target="_blank">http://www.osbelenenses.com</a>
<?php if ($edit == "BELEN") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BELEN"] as $pos => $bomber)
  if ($edit == "BELEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELEN"] as $pos => $bomber)
  if ($edit == "BELEN")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELEN(*)"] as $pos => $bomber)
  if ($edit == "BELEN")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BELEN(*)"] as $pos => $bomber)
  if ($edit == "BELEN")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBENFI.png" alt="" /></td>
    <td>
      <br />
      <b>SL Benfica</b><br /><br />
      город: <b>Lisboa</b><br /><br />
      год основания: <b>1904</b><br /><br />
      арена: <b>Estádio do Sport Lisboa e Benfica (da Luz)</b><br />
      вместительность: <b>65647</b><br /><br />
      <a href="http://www.slbenfica.pt" target="_blank">http://www.slbenfica.pt</a>
<?php if ($edit == "BENFI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BENFI"] as $pos => $bomber)
  if ($edit == "BENFI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BENFI"] as $pos => $bomber)
  if ($edit == "BENFI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BENFI(*)"] as $pos => $bomber)
  if ($edit == "BENFI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BENFI(*)"] as $pos => $bomber)
  if ($edit == "BENFI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBOAVI.png" alt="" /></td>
    <td>
      <br />
      <b>Boavista FC</b><br /><br />
      город: <b>Porto</b><br /><br />
      год основания: <b>1903</b><br /><br />
      арена: <b>Estádio do Bessa Século XXI</b><br />
      вместительность: <b>28263</b><br /><br />
      <a href="http://www.boavistafc.pt" target="_blank">http://www.boavistafc.pt</a>
<?php if ($edit == "BOAVI") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BOAVI"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI(*)"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BOAVI(*)"] as $pos => $bomber)
  if ($edit == "BOAVI")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalCAMPO.png" alt="" /></td>
    <td>
      <br />
      <b>Campomaiorense</b><br /><br />
      город: <b>Campo Maior</b><br /><br />
      год основания: <b>1926</b><br /><br />
      арена: <b>Estádio Capitão Cesar Correia</b><br />
      вместительность: <b>7500</b><br /><br />
      <a href="http://campomaiorense.pt" target="_blank">http://campomaiorense.pt</a>
<?php if ($edit == "CAMPO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CAMPO"] as $pos => $bomber)
  if ($edit == "CAMPO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMPO"] as $pos => $bomber)
  if ($edit == "CAMPO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMPO(*)"] as $pos => $bomber)
  if ($edit == "CAMPO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CAMPO(*)"] as $pos => $bomber)
  if ($edit == "CAMPO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalCHAVE.png" alt="" /></td>
    <td>
      <br />
      <b>GD Chaves</b><br /><br />
      город: <b>Chaves</b><br /><br />
      год основания: <b>1949</b><br /><br />
      арена: <b>Estádio Municipal Eng. Manuel Branco Teixeira</b><br />
      вместительность: <b>12000</b><br /><br />
      <a href="http://www.gdchaves.pt" target="_blank">http://www.gdchaves.pt</a>
<?php if ($edit == "CHAVE") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["CHAVE"] as $pos => $bomber)
  if ($edit == "CHAVE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHAVE"] as $pos => $bomber)
  if ($edit == "CHAVE")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHAVE(*)"] as $pos => $bomber)
  if ($edit == "CHAVE")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["CHAVE(*)"] as $pos => $bomber)
  if ($edit == "CHAVE")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalESTOR.png" alt="" /></td>
    <td>
      <br />
      <b>GD Estoril Praia</b><br /><br />
      город: <b>Estoril</b><br /><br />
      год основания: <b>1939</b><br /><br />
      арена: <b>Estádio António Coimbra da Mota</b><br />
      вместительность: <b>5500</b><br /><br />
      <a href="http://www.estorilpraia.pt/" target="_blank">http://www.estorilpraia.pt/</a>
<?php if ($edit == "ESTOR") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["ESTOR"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR(*)"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["ESTOR(*)"] as $pos => $bomber)
  if ($edit == "ESTOR")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalIMORT.png" height="150" alt="" /></td>
    <td>
      <br />
      <b>Imortal DC Albufeira</b><br /><br />
      город: <b>Albufeira
      </b><br /><br />
      год основания: <b>1920</b><br /><br />
      арена: <b>Municipal de Albufeira</b><br />
      вместительность: <b>3500</b><br /><br />
      <a href="http://www.imortaldc.com" target="_blank">http://www.imortaldc.com</a>
<?php if ($edit == "IMORT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["IMORT"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT(*)"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["IMORT(*)"] as $pos => $bomber)
  if ($edit == "IMORT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalMARIT.png" alt="" /></td>
    <td>
      <br />
      <b>CS Marítimo Funchal</b><br /><br />
      город: <b>Funchal (Ilha da Madeira)</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Estádio dos Barreiros</b><br />
      вместительность: <b>8992</b><br /><br />
      <a href="http://www.csmaritimo.pt" target="_blank">http://www.csmaritimo.pt</a>
<?php if ($edit == "MARIT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["MARIT"] as $pos => $bomber)
  if ($edit == "MARIT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARIT"] as $pos => $bomber)
  if ($edit == "MARIT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARIT(*)"] as $pos => $bomber)
  if ($edit == "MARIT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["MARIT(*)"] as $pos => $bomber)
  if ($edit == "MARIT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalNACIO.png" alt="" /></td>
    <td>
      <br />
      <b>CD Nacional Funchal</b><br /><br />
      город: <b>Funchal (Ilha da Madeira)</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Estádio da Madeira</b><br />
      вместительность: <b>8589</b><br /><br />
      <a href="http://www.cdnacional.pt" target="_blank">http://www.cdnacional.pt</a>
<?php if ($edit == "NACIO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["NACIO"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO(*)"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["NACIO(*)"] as $pos => $bomber)
  if ($edit == "NACIO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalPACOS.png" alt="" /></td>
    <td>
      <br />
      <b>FC Paços de Ferreira</b><br /><br />
      город: <b>Paços de Ferreira</b><br /><br />
      год основания: <b>1950</b><br /><br />
      арена: <b>Estádio da Capital do Móvel</b><br />
      вместительность: <b>6452</b><br /><br />
      <a href="http://www.fcpf.pt" target="_blank">http://www.fcpf.pt</a>
<?php if ($edit == "PACOS") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PACOS"] as $pos => $bomber)
  if ($edit == "PACOS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PACOS"] as $pos => $bomber)
  if ($edit == "PACOS")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PACOS(*)"] as $pos => $bomber)
  if ($edit == "PACOS")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PACOS(*)"] as $pos => $bomber)
  if ($edit == "PACOS")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalPORTO.png" alt="" /></td>
    <td>
      <br />
      <b>FC Porto</b><br /><br />
      город: <b>Porto</b><br /><br />
      год основания: <b>1893</b><br /><br />
      арена: <b>Estádio Do Dragão</b><br />
      вместительность: <b>50399</b><br /><br />
      <a href="http://www.fcporto.pt" target="_blank">http://www.fcporto.pt</a>
<?php if ($edit == "PORTO") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["PORTO"] as $pos => $bomber)
  if ($edit == "PORTO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTO"] as $pos => $bomber)
  if ($edit == "PORTO")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTO(*)"] as $pos => $bomber)
  if ($edit == "PORTO")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["PORTO(*)"] as $pos => $bomber)
  if ($edit == "PORTO")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalRIOAV.png" alt="" /></td>
    <td>
      <br />
      <b>FC Rio Ave FC</b><br /><br />
      город: <b>Vila do Conde</b><br /><br />
      год основания: <b>1939</b><br /><br />
      арена: <b>Estádio do Rio Ave Futebol Clube</b><br />
      вместительность: <b>12815</b><br /><br />
      <a href="http://www.rioave-fc.pt/" target="_blank">http://www.rioave-fc.pt/</a>
<?php if ($edit == "RIOAV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["RIOAV"] as $pos => $bomber)
  if ($edit == "RIOAV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIOAV"] as $pos => $bomber)
  if ($edit == "RIOAV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIOAV(*)"] as $pos => $bomber)
  if ($edit == "RIOAV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["RIOAV(*)"] as $pos => $bomber)
  if ($edit == "RIOAV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalBRAGA.png" alt="" /></td>
    <td>
      <br />
      <b>Sporting Braga</b><br /><br />
      город: <b>Braga</b><br /><br />
      год основания: <b>1921</b><br /><br />
      арена: <b>Estádio AXA</b><br />
      вместительность: <b>30286</b><br /><br />
      <a href="http://www.scbraga.pt" target="_blank">http://www.scbraga.pt</a>
<?php if ($edit == "BRAGA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["BRAGA"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA(*)"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["BRAGA(*)"] as $pos => $bomber)
  if ($edit == "BRAGA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalSPCOV.png" alt="" /></td>
    <td>
      <br />
      <b>SC Covilhã</b><br /><br />
      город: <b>Covilhã</b><br /><br />
      год основания: <b>1923</b><br /><br />
      арена: <b>Complexo Desportivo da Covilhã</b><br />
      вместительность: <b>6125</b><br /><br />
      <a href="http://www.sportingdacovilha.com" target="_blank">http://www.sportingdacovilha.com</a>
<?php if ($edit == "SPCOV") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SPCOV"] as $pos => $bomber)
  if ($edit == "SPCOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPCOV"] as $pos => $bomber)
  if ($edit == "SPCOV")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPCOV(*)"] as $pos => $bomber)
  if ($edit == "SPCOV")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPCOV(*)"] as $pos => $bomber)
  if ($edit == "SPCOV")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalSPORT.png" alt="" /></td>
    <td>
      <br />
      <b>Sporting Clube de Portugal</b><br /><br />
      город: <b>Lisboa</b><br /><br />
      год основания: <b>1906</b><br /><br />
      арена: <b>Estádio José Alvalade</b><br />
      вместительность: <b>50466</b><br /><br />
      <a href="http://www.sporting.pt" target="_blank">http://www.sporting.pt</a>
<?php if ($edit == "SPORT") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SPORT"] as $pos => $bomber)
  if ($edit == "SPORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPORT"] as $pos => $bomber)
  if ($edit == "SPORT")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPORT(*)"] as $pos => $bomber)
  if ($edit == "SPORT")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SPORT(*)"] as $pos => $bomber)
  if ($edit == "SPORT")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalGUIMA.png" alt="" /></td>
    <td>
      <br />
      <b>Vitória Guimarães SC</b><br /><br />
      город: <b>Guimarães</b><br /><br />
      год основания: <b>1922</b><br /><br />
      арена: <b>Estádio Dom Afonso Henriques</b><br />
      вместительность: <b>30165</b><br /><br />
      <a href="http://www.vitoriasc.pt" target="_blank">http://www.vitoriasc.pt</a>
<?php if ($edit == "GUIMA") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["GUIMA"] as $pos => $bomber)
  if ($edit == "GUIMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GUIMA"] as $pos => $bomber)
  if ($edit == "GUIMA")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GUIMA(*)"] as $pos => $bomber)
  if ($edit == "GUIMA")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["GUIMA(*)"] as $pos => $bomber)
  if ($edit == "GUIMA")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
  <tr>
    <td><br /><img src="images/portugalSETUB.png" alt="" /></td>
    <td>
      <br />
      <b>Vitória Setúbal FC</b><br /><br />
      город: <b>Setúbal</b><br /><br />
      год основания: <b>1910</b><br /><br />
      арена: <b>Estádio do Bonfim</b><br />
      вместительность: <b>18694</b><br /><br />
      <a href="http://www.vfc.pt" target="_blank">http://www.vfc.pt</a>
<?php if ($edit == "SETUB") echo "<br /><br /><br /><br /><br />
      <input type=\"submit\" name=\"change\" value=\"сохранить_состав\" />\n";?>
    </td>
    <td>
<?php foreach ($ab["SETUB"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" name="o['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB(*)"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" disabled="disabled" value="'.$pos.'" size="1" /><br />';
  else
    echo "$pos<br />";
?>
    </td>
    <td>
<?php foreach ($ab["SETUB(*)"] as $pos => $bomber)
  if ($edit == "SETUB")
    echo '<input type="text" name="r['.$pos.']" value="'.$bomber.'" size="20" /><br />';
  else
    echo "$bomber<br />";
?>
    </td>
  </tr>
</table></form>
