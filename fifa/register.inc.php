<?php
$res = true;
if (isset($_SESSION['Coach_name'])) {
  $coach_name = $_SESSION['Coach_name'];
  if (!is_file($data_dir . 'personal/'.$coach_name.'/team.2021')) {
    if (isset($_POST['register'])) {
      if ($_POST['register'] == 'не хочу в сборную') {
        touch($data_dir . 'personal/'.$coach_name.'/team.2021');
        echo '
<br>
Очень жаль... Ну может быть в следующем году?';
      }
      else {
        file_put_contents($data_dir . 'personal/'.$coach_name.'/team.2021', $_POST['assoc']);
        $codesf = fopen($online_dir . 'UNL/2021/'.$_POST['assoc'].'.csv', 'a');
        fwrite($codesf, $coach_name.';'.$_SESSION['Coach_mail'].";;\n");
        fclose($codesf);
        echo '

<br>
Спасибо за проявленное желание играть в сборной!<br>
Если решите передумать, выйти из сборной можно на странице <a href="/?a=world&m=player">Лига Наций - Игроки</a>.<br>
А если захотите попробовать себя в роли тренера сборной, напишите об этом руководителям ФП-ассоциации.';
      }
    }
    else {
      $usr_assocs = [];
      foreach (['BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'SUI', 'UKR'] as $usr_cc) {
        $codes = file_get_contents($online_dir . 'UNL/2021/'.$usr_cc.'.csv');
        foreach ($cmd_db[$usr_cc] as $team)
          if ($team['usr'] == $coach_name) {
            if (substr_count($codes, "\n") >= 11)
              break; // сборная полностью укомплектована

            if (($res = strpos($codes, $coach_name)) !== false)
              break; // не участвует в ассоциации

            $usr_assocs[] = $usr_cc;
          }

      }
      if (count($usr_assocs) && ($res === false)) {
//<div id="mwin" class="popup">
        echo '
<p>31 января завершается формирование сборных ФП-ассоциаций для участия в турнире "Лига Наций 2021".<br>
Пожалуйста, выберите команду, в которой хотите сыграть.<br>
Количество мест в сборных ограничено (11), поэтому не откладывайте решение.<br>
Лучше сделать это сейчас, поставив отметку перед названием страны:</p>
<form action="/?m=register" method="POST">';
        foreach ($usr_assocs as $assoc) echo '
<input name="assoc" type="radio" value="'.$assoc.'" onClick="$(`#teamregister`).removeAttr(`disabled`)"> '.$fa[strtolower($ccn[$assoc])].'<br>';
        echo '
<br>
<input id="teamregister" type="submit" name="register" value="записаться" class="btn btn-primary" disabled="disabled"> &nbsp; &nbsp;
<input type="submit" name="register" value="не хочу в сборную" class="btn btn-warning">
<br>
<br>
</form>
';
//<a class="close" title="Закрыть" href="#x"></a>
//</div>
//<script>$(".overlay").fadeTo("slow",0.65);$("#mwin").addClass("popup-show")</script>
      }
    }
  }
}
?>
