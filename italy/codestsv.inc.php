    <p class="title text15b">Редактор команд и игроков</p>
<?php
if (isset($_POST['confirm']))
{
  $tsv = '';
  for ($i=0; $i<sizeof($_POST['code']); $i++) if ($_POST['code'][$i])
    $tsv .= $_POST['code'][$i].'	'.$_POST['team'][$i].'	'.$_POST['coach'][$i].'	'.$_POST['email'][$i].'	'.$_POST['long'][$i].'	'.$_POST['play'][$i].'
';
  file_put_contents($online_dir."$cca/$cur_year/codes.tsv", $tsv);
  echo "<br />Файл <b>codes.tsv</b> изменен<br>\n";
}
else
{
  $codes = file($online_dir."$cca/$cur_year/codes.tsv");
  $out = '';
  echo '<form action="" method="post"><table><tr><th>коды</th><th>команды</th><th>тренеры</th><th>адреса</th><th>длинные названия команд</th><th>уч.</th></tr>';
  $i = 0;
  foreach ($codes as $player)
  {
    $aplayer = explode('	', trim($player));
    if(!isset($aplayer[4])) $aplayer[4] = '';
    if(!isset($aplayer[5])) $aplayer[5] = '';
    echo '<tr>
<td><input type="text" name="code[]" value="'.$aplayer[0].'" size="10" /></td>
<td><input type="text" name="team[]" value="'.htmlspecialchars($aplayer[1]).'" size="12" /></td>
<td><input type="text" name="coach[]" value="'.$aplayer[2].'" size="18" /></td>
<td><input type="text" name="email[]" value="'.$aplayer[3].'" size="32" /></td>
<td><input type="text" name="long[]" value="'.htmlspecialchars($aplayer[4]).'" size="24" /></td>
<td><input type="text" name="play[]" value="'.$aplayer[5].'" size="2" /></td>
</tr>
';
  }
    echo '<tr>
<td><input type="text" name="code[]" value="" size="10" /></td>
<td><input type="text" name="team[]" value="" size="12" /></td>
<td><input type="text" name="coach[]" value="" size="18" /></td>
<td><input type="text" name="email[]" value="" size="32" /></td>
<td><input type="text" name="long[]" value="" size="24" /></td>
<td><input type="text" name="play[]" value="" size="2" /></td>
</tr>
</table><input type="submit" name="confirm" value="записать" /></form>';
}
?>