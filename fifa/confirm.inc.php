<?php
// === конфигурация поиска с шифрованием запроса ===============================
$placeholder = 'имя';
$mininput = 2; // минимальное количество символов для поиска
$data_cfg = [];
$iv = substr(md5('iv'.$salt, true), 0, 8);
$key = substr(md5('pass1'.$salt, true) . md5('pass2'.$salt, true), 0, 24);
$cfg = base64_encode(mcrypt_encrypt( MCRYPT_BLOWFISH, $key, json_encode($data_cfg), MCRYPT_MODE_CBC, $iv ));
// =============================================================================

$out = '';
$cfm = array('?' => '', 'да' => '', 'нет' => '');
$ccs = array('BLR','ENG','ESP','FRA','GER','ITA','NLD','PRT','RUS','SCO','UKR');
if (isset($_SESSION['Coach_name'])) {
  if (isset($_POST['confirm'])) {
    if ($_POST['new_name'] && $_POST['new_name'] != $_SESSION['Coach_name']) {
      // проверка уникальности нового имени и существующих имён, кодов и адресов
      $unique = true;
      $uname = mb_strtoupper($_POST['new_name']);
      foreach ($cmd_db as $ac => $teams)
        foreach ($teams as $code => $team)
          if ($uname == mb_strtoupper($team['usr'])
           || $uname == mb_strtoupper($team['eml'])
           || $uname == mb_strtoupper($code)) {
            $unique = false; // имя не принято, оставляем старое
            break 2;
          }
    }
    foreach ($ccs as $ccc) {
      $fn = strtolower($ccn[$ccc]).'/settings.inc.php';
      $settings = file_get_contents($fn);
      $season = substr($settings, strpos($settings, '$cur_year') + 10);
      $season = substr($season, strpos($season, "'") + 1, 7); // формат типа "2018-19"
      if (!strpos($season, '-'))
        $season = substr($season, 0, 4); // формат типа "2018"

      if (isset($_POST['confirm'.$ccc]) && ($value = $_POST['confirm'.$ccc])) {
        $fn = $online_dir . $ccc . '/' . $season . '/codes.tsv';
        $codes = file($fn);
        $tsv = '';
        foreach ($codes as $player) {
          list($code, $team, $coach, $email, $long_name, $confirm) = explode('	', $player);
          if ($player[0] != '#' && $_SESSION['Coach_name'] == $coach)
            $confirm = $value;

          $coach = (isset($unique) && $unique) ? $_POST['new_name'] : $coach;
          $tsv .= $code.'	'.$team.'	'.$coach.'	'.$email.'	'.$long_name.'	'.trim($confirm).'
';
        }
        file_put_contents($fn, $tsv);
      }
      if (isset($unique) && $unique)
        if (strpos($settings, $_SESSION['Coach_name']))
          file_put_contents($fn, str_replace($_SESSION['Coach_name'], $_POST['new_name'], $settings));

    }
    touch($data_dir . 'personal/'.$_SESSION['Coach_name'].'/'.date('Y', time()));
    $out = '<br />
Подтверждение получено.<br />
Эта страница больше не будет показываться до следующего межсезонья.<br />
Изменить данные можно на страницах "Игроки" ФП-ассоциаций.';
    if (isset($unique))
      if ($unique) {
        $out = '<br />
Смена имени вступит в силу со следующим открытием любой страницы сайта.<br />';
        rename($data_dir.'personal/'.$_SESSION['Coach_name'], $data_dir.'personal/'.$_POST['new_name']);
        // clone - мой метод клонирования ключа - в настоящем redis его нет!
        $redis->clone('c_user:'.$_SESSION['Coach_name'], 'c_user:'.$_POST['new_name']);
        $_SESSION['Coach_name'] == $_POST['new_name'];
        build_access();
      }
      else
        $out = '<br />
Новое имя не принято, поскольку оно совпадает с уже существующим именем или кодом команды.<br />
<a href="?a=uefa&m=hq">Свяжитесь с администрацией сайта для решения конфликта</a>.';

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
    $email = '';
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
          if ($team['eml'])
            $email = $team['eml'];

        }

    $out .= '
    <tr><td colspan="4">
      <br />
      <b>ВНИМАНИЕ</b>: с этого сезона снимается архаичное ограничение на написание имён игроков латиницей.<br />
      Вы можете сменить написание своего имени здесь и сейчас:
<input id="new_name" type="text" name="new_name" value="'.$_SESSION['Coach_name'].'" data-tpl="<?=$cfg ?>" style="padding-left:5px" />
<span id="valid_name"><i class="fa fa-check" style="color:green"> это имя используется Вами сейчас</i></span>
      <br />
    </td></tr>
    <tr><td colspan="3">&nbsp;</td><td align="center"><input id="cfm_button" type="submit" name="confirm" value="отправить" /></td></tr>
  </table>
</form>
<br />
';
  }
}
else $out = 'Эта страница требует аутентификации';
?>
<script>
$(document).ready(function(){
  $("#new_name").keyup(function(){
    no=false
    str=$(this).val()
    if (str.length < <?=$mininput ?>) {
      no=true
      $("#valid_name").html('<i class="fa fa-times" style="color:red"> введите хотя бы 2 буквы</i>')
    }
    else {
      if (str.indexOf(',', -1) + str.indexOf(';', -1) > 0) {
        no=true
        $("#valid_name").html('<i class="fa fa-times" style="color:red"> нельзя использовать знаки препинания</i>')
      }
      else {
        $.post("/online/ajax.php",{
            data: $(this).data("tpl"),
            keyword: $(this).val(),
            email: "<?=isset($email)?$email:'' ?>"
          },function(r){
            switch (r) {
              case '1': no=true;ok='<i class="fa fa-times" style="color:red"> это имя или код заняты</i>';break;
              case '2': no=false;ok='<i class="fa fa-check" style="color:green"> имя или код уже используется Вами</i>';break;
              default : no=false;ok='<i class="fa fa-check" style="color:green"> такое имя допустимо</i>';
            }
            $("#valid_name").html(ok)
        })
      }
    }
    $("#cfm_button").prop("disabled", no)
  })
});
</script>
<span class="title text15b">&nbsp;&nbsp;&nbsp;Подтверждение участия в сезоне</span>
<hr size="1" width="98%" />
<?=$out?>
