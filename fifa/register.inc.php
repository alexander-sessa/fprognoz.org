<?php
$res = true;
if (isset($_SESSION['Coach_name'])) {
  $coach_name = $_SESSION['Coach_name'];
  if (!is_file($data_dir . 'personal/'.$coach_name.'/team.2018')) {
    if (isset($_POST['register'])) {
      if ($_POST['register'] == 'не хочу в сборную') {
        touch($data_dir . 'personal/'.$coach_name.'/team.2018');
        echo '
<br />
Очень жаль... Ну может быть в следующем году?';
      }
      else {
        file_put_contents($data_dir . 'personal/'.$coach_name.'/team.2018', $_POST['assoc']);
        $codesf = fopen($online_dir . 'WL/2018/'.$_POST['assoc'].'.csv', 'a');
        fwrite($codesf, $coach_name.";;;\n");
        fclose($codesf);
        echo '

<br />
Спасибо за проявленное желание играть в сборной!<br />
Если решите передумать, выйти из сборной можно на странице <a href="/?a=world&m=player">Мировая Лига - Игроки</a>.<br />
А если захотите попробовать себя в роли тренера сборной, напишите об этом руководителям ФП-ассоциации.';
      }
    }
    else {
      $usr_assocs = [];
      foreach (['BLR', 'ENG', 'ESP', 'GER', 'ITA', 'NLD', 'PRT', 'SCO', 'UKR'] as $usr_cc) {
        $codes = file_get_contents($online_dir . 'WL/2018/'.$usr_cc.'.csv');
        foreach ($cmd_db[$usr_cc] as $team)
          if ($team['usr'] == $coach_name) {
            if (($res = strpos($codes, $coach_name)) !== false)
              break 2;

            $usr_assocs[] = $usr_cc;
          }

      }
      if ($res === false) {
        echo '
<div id="mwin" class="popup">
<p>14 июня завершается формирование сборных ФП-ассоциаций для участия в турнире "Мировая Лига 2018".<br />
Возможно, Вы забыли выбрать команду, в которой хотите сыграть?<br />
Сделайте это сейчас, поставив отметку перед названием страны:</p>
<form action="/?m=register" method="POST">';
        foreach ($usr_assocs as $assoc) echo '
<input name="assoc" type="radio" value="'.$assoc.'" /> '.$ccn[$assoc].'</br>';
        echo '
<br />
<input type="submit" name="register" value="записаться" /> &nbsp; &nbsp;
<input type="submit" name="register" value="не хочу в сборную" />
</form>
<a class="close" title="Закрыть" href="#x"></a>
</div>
<script>$(".overlay").fadeTo("slow",0.65);$("#mwin").addClass("popup-show")</script>
';
      }
    }
  }
}
?>
