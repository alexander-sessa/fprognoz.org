<?php
$season = isset($s) ? $s : $cur_year;
$codes = file($online_dir."$cca/$season/codes.tsv");
$out = '';
$tsv = '';
$warning = '';
$cfm = array('?' => '', 'да' => '', 'нет' => '');
foreach ($codes as $player) if ($player[0] != '#')
{
  $aplayer = explode('	', trim($player));
  if (!isset($aplayer[4])) $aplayer[4] = '';
  if (trim($aplayer[4])) $team_name = str_replace(' ', '&nbsp;', $aplayer[4]);
  else $team_name = $aplayer[1];
  if (!isset($aplayer[5])) $aplayer[5] = '';
  if (isset($_SESSION["Coach_name"]) && $_SESSION["Coach_name"] == $aplayer[2])
  {
    if (isset($_POST["confirm"])) $aplayer[5] = $_POST["confirm"];
    if (isset($_POST["email"])) $aplayer[3] = $_POST["email"];
    $out .= '<tr><td>'.$aplayer[0].'</td><td>'.$team_name.'</td><td>'.$aplayer[2].'</td><td align="center"><select name="confirm">';
    if (isset($aplayer[5]))
    {
      $cfm[$aplayer[5]] = ' selected';
//      if (!$aplayer[5])
//        $warning = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; !!! Пожалуйста, подтвердите свое участие !!!';
    }
    else
    {
      $cfm['?'] = ' selected';
//      $warning = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; !!! Пожалуйста, подтвердите свое участие !!!';
    }
    foreach ($cfm as $choice => $selected)
      $out .= '<option value="'.$choice.'"'.$selected.'>'.$choice.'</option>';

    $out .= '</select></td><td><input type="text" name="email" value="'.$aplayer[3].'"><input type="submit"></td></tr>
';
  }
  else $out .= '<tr><td>'.$aplayer[0].'</td><td>'.$team_name.'</td><td class="player_name">'.$aplayer[2].'</td><td align="center">'.$aplayer[5].'</td><td>'.($role == 'president' ? $aplayer[3] : '&nbsp;').'</td></tr>
';
  $tsv .= $aplayer[0].'	'.$aplayer[1].'	'.$aplayer[2].'	'.$aplayer[3].'	'.$aplayer[4].'	'.$aplayer[5].'
';
}
if (isset($_POST["confirm"]))
{
  file_put_contents($online_dir."$cca/$season/codes.tsv", $tsv);
  touch($data_dir . 'personal/'.$_SESSION["Coach_name"].'/'.date('Y', time()));
}
?>
<p class="title text15b">&nbsp;&nbsp;&nbsp;Состав ассоциации<?=$warning?></p>
<hr size="1" width="98%" />
<form action="" method="post">
<table width="100%">
<tr><th align="left">код</th><th align="left">команда</th><th align="left">тренер</th><th>участие</th><th align="left">e-mail</th></tr>
<?=$out?>
</table>
</form>
