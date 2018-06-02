<?php
$out = '';
$cfm = array('?' => '', 'да' => '', 'нет' => '');
$ccs = array('BLR','ENG','ESP','FRA','GER','ITA','NLD','PRT','RUS','SCO','UKR');
if (isset($_SESSION['Coach_name'])) {
  if (isset($_POST['confirm'])) {
    foreach ($ccs as $ccc)
      if (isset($_POST['confirm'.$ccc]) && ($value = $_POST['confirm'.$ccc])) {
        $codes = file($online_dir . $ccc . '/2018-19/codes.tsv');
        $tsv = '';
        foreach ($codes as $player) {
          list($code, $team, $coach, $email, $long_name, $confirm) = explode('	', $player);
          if ($player[0] != '#' && $_SESSION['Coach_name'] == $coach)
            $confirm = $value;

          $tsv .= $code.'	'.$team.'	'.$coach.'	'.$email.'	'.$long_name.'	'.trim($confirm).'
';
        }
        file_put_contents($online_dir . $ccc . '/2018-19/codes.tsv', $tsv);
      }

    touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/'.date('Y', time()));
    $out = '<br />Подтверждение получено.<br />Эта страница больше не будет показываться.<br />Изменить данные можно на страницах "Игроки" ФП-ассоциаций';
  }
  else {
    $out = '<form action="?a=fifa&m=confirm" method="post"><table width=916><tr><th align="left">код</th><th align="left">ассоциация</th><th align="left">команда</th><th>участие</th></tr>';
    foreach ($ccs as $cca)
      foreach ($cmd_db[$cca] as $code => $team)
        if (($team['usr'] == $_SESSION['Coach_name'])) {
          $out .= '<tr><td>'.$code.'</td><td>'.$cca.'</td><td>'.$team['cmd'].'</td><td align="center"><select name="confirm'.$cca.'">';
          foreach ($cfm as $choice => $selected)
            $out .= '<option>'.$choice.'</option>';

          $out .= '</select></td></tr>
';
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
