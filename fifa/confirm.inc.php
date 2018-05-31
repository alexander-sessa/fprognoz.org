<?php
$out = '';
$cfm = array('?' => '', 'да' => '', 'нет' => '');
$ccs = array('BLR','ENG','ESP','FRA','GER','ITA','NLD','PRT','RUS','SCO','UKR');
if (isset($_SESSION['Coach_name']))
{
  if (isset($_POST['confirm']))
  {
    foreach ($ccs as $ccc) if (isset($_POST['confirm'.$ccc]) && ($value = $_POST['confirm'.$ccc]))
    {
      $codes = file($online_dir . $ccc . '/2018-19/codes.tsv');
      $tsv = '';
      foreach ($codes as $player)
      {
        $aplayer = explode('	', trim($player));
        if (!isset($aplayer[4])) $aplayer[4] = '';
        if (!isset($aplayer[5])) $aplayer[5] = '';
        if ($player[0] != '#' && $_SESSION['Coach_name'] == $aplayer[2])
          $aplayer[5] = $value;
        $tsv .= $aplayer[0].'	'.$aplayer[1].'	'.$aplayer[2].'	'.$aplayer[3].'	'.$aplayer[4].'	'.$aplayer[5].'
';
      }
      file_put_contents($online_dir . $ccc . '/2018-19/codes.tsv', $tsv);
    }
    touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/'.date('Y', time()));
    $out = '<br />Подтверждение получено.<br />Эта страница больше не будет показываться.<br />Изменить данные можно на страницах "Игроки" ФП-ассоциаций';
  }
  else
  {
    $out = '<form action="?a=fifa&m=confirm" method="post"><table width=916><tr><th align="left">код</th><th align="left">ассоциация</th><th align="left">команда</th><th>участие</th></tr>';
    foreach ($usr_db[$_SESSION['Coach_name']] as $team_str)
    {
      $ta = explode('@', $team_str);
      if (sizeof($ta) == 2 && in_array($ta[1], $ccs)) {
        $out .= '<tr><td>'.$ta[0].'</td><td>'.$ta[1].'</td><td>'.$cmd_db[$team_str]['cmd'].'</td><td align="center"><select name="confirm'.$ta[1].'">';
        foreach ($cfm as $choice => $selected) $out .= '<option>'.$choice.'</option>';
        $out .= '</select></td></tr>
';
      }
    }
    $out .= '<tr><td colspan="3">&nbsp;</td><td align="center"><input type="submit" name="confirm" value="отправить" /></td></tr></table></form><br />
    Эта страница показывается один раз в год во время сбора подтверждений на сезон.<br />
    Пожалуйста, вместо знаков "?" укажите Ваше согласие играть командой в новом сезоне ("да") или отказ от команды ("нет").<br />
    <br />';
  }
}
else $out = 'Эта страница требует аутентификации';
?>
<span class="title text15b">&nbsp;&nbsp;&nbsp;Подтверждение участия в сезоне</span>
<hr size="1" width="98%" />
<?=$out?>
