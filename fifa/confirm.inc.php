<?php
$out = '';
$cfm = array('?' => '', 'да' => '', 'нет' => '');
$ccs = array('BLR','ENG','ESP','FRA','GER','ITA','NLD','PRT','RUS','SCO','UKR');
if (isset($_SESSION['Coach_name'])) {
  if (isset($_POST['confirm'])) {
    foreach ($ccs as $ccc) {
      if (isset($_POST['confirm'.$ccc]) && ($value = $_POST['confirm'.$ccc])) {
        $codes = file($online_dir . $ccc . '/2018-19/codes.tsv');
        $tsv = '';
        foreach ($codes as $player) {
          list($code, $team, $coach, $email, $long_name, $confirm) = explode('	', $player);
          if ($player[0] != '#' && $_SESSION['Coach_name'] == $coach)
            $confirm = $value;

          $coach = $_POST['new_name'] ? $_POST['new_name'] : $coach;
          $tsv .= $code.'	'.$team.'	'.$coach.'	'.$email.'	'.$long_name.'	'.trim($confirm).'
';
        }
        file_put_contents($online_dir . $ccc . '/2018-19/codes.tsv', $tsv);
      }
      if ($_POST['new_name'] && $_POST['new_name'] != $_SESSION['Coach_name']) {
        $fn = strtolower($ccn[$ccc]).'/settings.inc.php';
        $settings = file_get_contents($fn);
        if (strpos($settings, $_SESSION['Coach_name']))
        file_put_contents($fn, str_replace($_SESSION['Coach_name'], $_POST['new_name'], $settings));
      }
    }
    touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/'.date('Y', time()));
    $out = '<br />
Подтверждение получено.<br />
Эта страница больше не будет показываться до следующего межсезонья.<br />
Изменить данные можно на страницах "Игроки" ФП-ассоциаций.';
    if ($_POST['new_name'] && $_POST['new_name'] != $_SESSION['Coach_name']) {
      $out = '<br />
Смена имени вступит в силу со следующим открытием любой страницы сайта.<br />';
      rename($data_dir.'personal/'.$_SESSION['Coach_name'], $data_dir.'personal/'.$_POST['new_name']);
      $redis->clone('c_user:'.$_SESSION['Coach_name'], 'c_user:'.$_POST['new_name']);
      $_SESSION['Coach_name'] == $_POST['new_name'];
      build_access();
    }
  }
  else {
    $out = '<br />
Эта страница показывается один раз в год во время сбора подтверждений на будущий сезон.<br />
Пожалуйста, подтвердите Ваше согласие играть командой в новом сезоне (заменив "<b>?</b>" на "<b>да</b>") или отказ от команды ("<b>нет</b>").<br />
<br />
<form action="?a=fifa&m=confirm" method="post">
  <table width=916>
    <tr><td colspan="4"></td></tr>
    <tr><th align="left">код</th><th align="left">ассоциация</th><th align="left">команда</th><th>участие</th></tr>';
    foreach ($ccs as $cca)
      foreach ($cmd_db[$cca] as $code => $team)
        if (($team['usr'] == $_SESSION['Coach_name'])) {
          $out .= '
    <tr>
      <td>'.$code.'</td>
      <td>'.$cca.'</td>
      <td>'.$team['cmd'].'</td>
      <td align="center"><select name="confirm'.$cca.'">';
          foreach ($cfm as $choice => $selected)
            $out .= '<option>'.$choice.'</option>';

          $out .= '</select></td>
    </tr>
';
        }

    $out .= '
    <tr><td colspan="4">
      <br />
      <b>ВНИМАНИЕ</b>: с этого сезона снимается архаичное ограничение на написание имён игроков латиницей.<br />
      Вы можете сменить написание своего имени здесь и сейчас:
      <input type="text" name="new_name" value="'.$_SESSION['Coach_name'].'" style="padding-left:5px" /><br />
      <br />
    </td></tr>
    <tr><td colspan="3">&nbsp;</td><td align="center"><input type="submit" name="confirm" value="отправить" /></td></tr>
  </table>
</form>
<br />
';
  }
}
else $out = 'Эта страница требует аутентификации';
?>
<span class="title text15b">&nbsp;&nbsp;&nbsp;Подтверждение участия в сезоне</span>
<hr size="1" width="98%" />
<?=$out?>
