<?php
/* Приоритеты подбора соперника:
1. При нечетном количестве команд тур пропускает слабейший из еще не пропускавших
2. Во втором и последующих турах сильнейшие играют с сильнейшими - к чёрту "метод пенальти":
   важно выбрать сильнейших соперников для лидеров, а середняки и так разберутся

$games[$team1][$team2] = $result - массив сыгранных матчей 
$teams - массив команд расставленных по убыванию рейтинга (по местам)
Разумеется, можно было бы построить $teams на основе $games, но:
- во первых, для первого тура $games пуст;
- всё равно, какой-то скрипт будет строить турнирную таблицу.
*/
function SwissDraw($games, $teams, $verbose=false) {
  $countTeams = count($teams);
  $newRounds = [];
  $error = '';
  if ($countTeams > 1)
  {
    $nbGames = ceil($countTeams / 2);
    $hasDummy = $countTeams % 2;
    if (count($games[key($games)]) == 0) {
      $error = 'Начальное распределение команд.';
      for ($i = 0; $i < $nbGames - $hasDummy; $i++)
        $newRounds[] = $teams[$i].' - '.$teams[$nbGames - $hasDummy + $i];

      if ($hasDummy)
        $newRounds[] = $teams[$countTeams - 1].' - Old Stars';

    }
    else { // не первый тур
      $dummy = [0 => 'none'];
      if ($hasDummy) // при необходимости выбираем кандидатов на пропуск тура
        for ($i = $countTeams - 1; $i >= 0; $i--)
          //if (!isset($games[$teams[$i]][$teams[$i]]))
          if (!isset($games[$teams[$i]][$teams[$i]]) && !isset($games[$teams[$i]]['Old Stars']))
            $dummy[] = $teams[$i];

      // brute force: если не находится решение, для последней определённой пары создаём в
      // $fgamBes фиктивный матч и повторяем процесс c предпоследнего шага
      $n = $nbGames - $hasDummy;
      if ($verbose)
        echo "<br />Формирование матчей (brute force)<br />\n";

      foreach ($dummy as $byeTeam) if (!$hasDummy || $byeTeam != 'none') {
        $fgames = [];
        $used = [];
        $newRounds = [];
        $lasti = $lastj = '';
        $repeat = $repeats = 0;
        $m = -1;
        if ($verbose && $hasDummy)
          echo "$byeTeam в этом туре играет с ботами.<br />\n";

        // если надо повторить предыдущую итерацию, если превышено к-во попыток - стоп, иначе следущая пара
        for ($i = 0; $i < $countTeams - 1; $i++) {
          if (!$i && $verbose)
            echo "<hr>\n";

          for ($j = $i + 1; $j < $countTeams; $j++) {
            if (isset($fgames[$teams[$j]])) {
              unset ($fgames[$teams[$j]]); // удалили тупики предыдущей итерации
              if ($verbose)
                echo "$teams[$j] - убрана блокировка предыдущей итерации.<br />\n";

            }
          }
          if ($teams[$i] == $byeTeam) {
            if ($verbose)
              echo 'Пропуск команды '.$teams[$i]." - не играет в этом туре.<br />\n";

          }
          else if (isset($used[$teams[$i]])) {
            if ($verbose)
              echo 'Пропуск команды '.$teams[$i]." - уже распределена.<br />\n";

            if ($i == 0)
              die('Fail');

          }
          else {
            $match = false;
            if ($verbose)
              echo 'Поиск пары для команды '.$teams[$i].'. Попытка '.($repeat + 1).': ';

            for ($j = $i + 1; $j < $countTeams; $j++) {
              if ($teams[$j] == $byeTeam) {
                if ($verbose)
                  echo $teams[$j]." пропускает тур; ";

              }
              else if ($i == $j) {
                if ($verbose)
                  echo '';

              }
              else if (isset($used[$teams[$j]])) {
                if ($verbose)
                  echo $teams[$j]." уже распределена; ";

              }
              else if (isset($games[$teams[$i]][$teams[$j]]) || isset($games[$teams[$j]][$teams[$i]])) {
                if ($verbose)
                  echo $teams[$j]." уже играли с ней; ";

              }
              else if (isset($fgames[$teams[$i]][$teams[$j]]) || isset($fgames[$teams[$j]][$teams[$i]])) {
                if ($verbose)
                  echo $teams[$j]." - ведет в тупик; ";

              }
              else {
                $newRounds[++$m] = $teams[$i].' - '.$teams[$j];
                $lasti = $teams[$i];
                $lastj = $teams[$j];
                $used[$lasti] = true;
                $used[$lastj] = true;
                $match = true;
                if ($verbose)
                  echo $teams[$i].' - '.$teams[$j]." OK<br />\n";

                break;
              }
            }
            if ($match) {
              if (count($newRounds) == $n)
                break 2; // готово!

              $repeat = 0; // пара есть - можно искать следующую пару,
            }
            else {                                   // но если невозможно подобрать пару,
              unset($fgames[$lasti][$lastj]);        // надо снять запрет
              list($lasti, $lastj) = explode(' - ', $newRounds[$m]);
              $fgames[$lasti][$lastj] = -1;          // исключить предыдущую найденную пару
              if (!count($newRounds))
                die('
Невозможно составить список матчей.'); // больше нет вариантов
              if ($verbose)
                echo "\nТупик: ".$newRounds[$m]." удалена, $lasti и $lastj разлочены.<br />
Откат к поиску нового соперника для $lasti.<br />\n";

              unset($newRounds[$m--]);
              unset($used[$lasti]);
              unset($used[$lastj]);
              $repeat++;                             // посчитать попытку
              if ($repeats++ > $countTeams * $countTeams)
                die('
Слишком много попыток.'); // тупая защита от бесконечного цикла

              $i = array_search($lasti, $teams) - 1; // и откатиться на шаг
            }
          }
        }
      }
      if (!count($newRounds))
        $error = 'Невозможно составить список матчей.';
      else {
        if ($hasDummy)
          $newRounds[] = $byeTeam.' - Old Stars';

        if (count($newRounds) < $n)
          $error = 'Невозможно составить ПОЛНЫЙ список матчей.';

      }
    }
  }
  else
    $error = 'Кажется, победитель турнира известен еще до его начала.';

  return [$error, $newRounds];
}
?>
