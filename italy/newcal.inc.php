<?php
$season_dir = $online_dir.$cca.'/'.$cur_year.'/';
$caltpl = [
  10=>[[1,10],[2,9],[3,8],[4,7],[5,6],[10,6],[7,5],[8,4],[9,3],[1,2],[2,10],[3,1],[4,9],[5,8],[6,7],[10,7],[8,6],[9,5],[1,4],[2,3],[3,10],[4,2],[5,1],[6,9],[7,8],[10,8],[9,7],[1,6],[3,4],[2,5],[4,10],[5,3],[6,2],[8,9],[1,7],[10,9],[1,8],[2,7],[4,5],[3,6],[5,10],[6,4],[7,3],[9,1],[8,2]],
  12=>[[1,2],[3,4],[5,6],[7,8],[9,10],[11,12],[12,1],[2,3],[4,5],[6,7],[8,9],[10,11],[1,3],[5,2],[7,4],[9,6],[11,8],[10,12],[1,5],[3,12],[2,7],[4,9],[6,11],[8,10],[7,1],[5,3],[9,2],[11,4],[10,6],[12,8],[1,9],[3,7],[12,5],[2,11],[4,10],[8,6],[11,1],[9,3],[5,7],[10,2],[8,4],[6,12],[1,10],[3,11],[5,9],[7,12],[2,8],[4,6],[8,1],[10,3],[11,5],[9,7],[6,2],[12,4],[6,1],[8,3],[10,5],[11,7],[12,9],[4,2],[1,4],[3,6],[5,8],[7,10],[9,11],[2,12],[2,1],[4,3],[6,5],[8,7],[10,9],[12,11],[1,12],[3,2],[5,4],[7,6],[9,8],[11,10],[3,1],[2,5],[4,7],[6,9],[8,11],[12,10],[5,1],[12,3],[7,2],[9,4],[11,6],[10,8],[1,7],[3,5],[2,9],[4,11],[6,10],[8,12],[9,1],[7,3],[5,12],[11,2],[10,4],[6,8],[1,11],[3,9],[7,5],[2,10],[4,8],[12,6],[10,1],[11,3],[9,5],[12,7],[8,2],[6,4],[1,8],[3,10],[5,11],[7,9],[2,6],[4,12],[1,6],[3,8],[5,10],[7,11],[9,12],[2,4],[4,1],[6,3],[8,5],[10,7],[11,9],[12,2]],
  14=>[[7,13],[10,14],[1,11],[12,4],[6,5],[3,8],[9,2],[2,7],[13,10],[14,1],[11,12],[4,6],[5,3],[8,9],[7,10],[1,13],[12,14],[6,11],[3,4],[9,5],[8,2],[7,1],[10,2],[13,12],[14,6],[11,3],[4,9],[5,8],[12,7],[1,10],[6,13],[3,14],[9,11],[8,4],[2,5],[7,6],[10,12],[2,1],[13,3],[14,9],[11,8],[5,4],[3,7],[6,10],[1,12],[9,13],[8,14],[5,11],[4,2],[7,9],[10,3],[1,6],[12,2],[13,8],[14,5],[11,4],[8,7],[9,10],[3,1],[6,12],[5,13],[4,14],[2,11],[7,5],[10,8],[1,9],[12,3],[2,6],[13,4],[11,14],[4,7],[5,10],[8,1],[9,12],[6,3],[11,13],[14,2],[7,11],[10,4],[1,5],[12,8],[6,9],[3,2],[13,14],[14,7],[11,10],[4,1],[5,12],[8,6],[9,3],[2,13],[13,7],[14,10],[11,1],[4,12],[5,6],[8,3],[2,9],[7,2],[10,13],[1,14],[12,11],[6,4],[3,5],[9,8],[10,7],[13,1],[14,12],[11,6],[4,3],[5,9],[2,8],[1,7],[2,10],[12,13],[6,14],[3,11],[9,4],[8,5],[7,12],[10,1],[13,6],[14,3],[11,9],[4,8],[5,2],[6,7],[12,10],[1,2],[3,13],[9,14],[8,11],[4,5],[7,3],[10,6],[12,1],[13,9],[14,8],[11,5],[2,4],[9,7],[3,10],[6,1],[2,12],[8,13],[5,14],[4,11],[7,8],[10,9],[1,3],[12,6],[13,5],[14,4],[11,2],[5,7],[8,10],[9,1],[3,12],[6,2],[4,13],[14,11],[7,4],[10,5],[1,8],[12,9],[3,6],[13,11],[2,14],[11,7],[4,10],[5,1],[8,12],[9,6],[2,3],[14,13],[7,14],[10,11],[1,4],[12,5],[6,8],[3,9],[13,2]],16=>[[1,2],[3,4],[5,6],[7,8],[9,10],[11,12],[13,14],[15,16],[16,11],[12,3],[2,7],[14,1],[6,15],[4,9],[8,13],[10,5],[8,2],[7,4],[11,15],[5,12],[9,14],[13,16],[3,6],[1,10],[12,7],[10,8],[2,13],[16,9],[6,1],[14,3],[4,11],[15,5],[2,10],[3,16],[7,14],[1,12],[13,6],[5,11],[9,15],[8,4],[10,7],[12,9],[11,3],[6,8],[16,5],[14,2],[15,13],[4,1],[1,16],[7,15],[8,12],[10,14],[13,11],[9,6],[3,5],[2,4],[16,2],[15,3],[5,9],[4,10],[12,13],[14,8],[6,7],[11,1],[1,15],[7,11],[2,12],[14,4],[13,5],[8,16],[9,3],[10,6],[4,13],[16,7],[5,8],[12,14],[6,2],[15,10],[3,1],[11,9],[10,16],[7,5],[4,12],[8,15],[1,9],[13,3],[2,11],[14,6],[15,2],[12,10],[16,14],[3,7],[6,4],[5,1],[11,8],[9,13],[4,16],[14,15],[13,1],[8,3],[2,5],[12,6],[7,9],[10,11],[3,10],[15,12],[9,2],[13,7],[5,4],[11,14],[16,6],[1,8],[4,15],[8,9],[14,5],[12,16],[2,3],[7,1],[10,13],[6,11],[2,1],[4,3],[6,5],[8,7],[10,9],[12,11],[14,13],[16,15],[11,16],[3,12],[7,2],[1,14],[15,6],[9,4],[13,8],[5,10],[2,8],[4,7],[15,11],[12,5],[14,9],[16,13],[6,3],[10,1],[7,12],[8,10],[13,2],[9,16],[1,6],[3,14],[11,4],[5,15],[16,3],[14,7],[4,8],[12,1],[6,13],[11,5],[10,2],[15,9],[5,16],[3,11],[2,14],[8,6],[13,15],[9,12],[1,4],[7,10],[16,1],[14,10],[11,13],[12,8],[6,9],[5,3],[15,7],[4,2],[7,6],[3,15],[8,14],[9,5],[1,11],[10,4],[2,16],[13,12],[3,9],[16,8],[15,1],[12,2],[4,14],[6,10],[5,13],[11,7],[2,6],[10,15],[1,3],[14,12],[13,4],[9,11],[7,16],[8,5],[12,4],[3,13],[11,2],[16,10],[15,8],[5,7],[9,1],[6,14],[8,11],[1,5],[14,16],[4,6],[13,9],[2,15],[10,12],[7,3],[11,10],[3,8],[1,13],[6,12],[15,14],[16,4],[9,7],[5,2],[8,1],[12,15],[4,5],[14,11],[2,9],[7,13],[6,16],[10,3],[1,7],[13,10],[9,8],[15,4],[11,6],[5,14],[16,12],[3,2]],18=>[[3,8],[12,6],[7,15],[5,11],[10,14],[9,16],[18,17],[13,2],[1,4],[4,18],[8,10],[6,1],[16,3],[14,9],[17,12],[15,13],[2,5],[11,7],[4,6],[7,16],[18,2],[1,8],[12,11],[5,15],[3,14],[9,10],[13,17],[10,18],[11,1],[15,6],[8,12],[16,5],[14,7],[2,3],[9,13],[17,4],[7,2],[5,8],[1,15],[18,9],[4,11],[13,16],[3,10],[6,17],[12,14],[15,18],[14,5],[2,12],[10,7],[8,4],[16,6],[11,13],[9,3],[17,1],[18,8],[13,3],[6,11],[1,2],[7,9],[17,15],[4,14],[12,16],[5,10],[16,1],[9,5],[3,7],[14,13],[2,6],[15,4],[10,12],[8,17],[11,18],[1,10],[13,7],[17,11],[15,8],[18,16],[4,2],[12,9],[6,14],[5,3],[10,13],[8,6],[7,5],[11,15],[2,17],[14,18],[3,12],[16,4],[9,1],[8,11],[18,3],[6,10],[1,14],[12,7],[13,5],[15,2],[4,9],[17,16],[5,12],[2,8],[13,18],[9,17],[7,1],[3,6],[16,11],[10,4],[14,15],[18,7],[12,13],[11,2],[6,9],[1,3],[17,14],[15,10],[4,5],[8,16],[3,17],[12,4],[5,18],[9,8],[14,11],[7,6],[13,1],[10,2],[16,15],[2,16],[17,7],[4,3],[15,9],[11,10],[18,12],[1,5],[6,13],[8,14],[13,8],[14,16],[3,11],[10,17],[7,4],[9,2],[5,6],[12,15],[18,1],[4,13],[2,14],[6,18],[16,10],[8,7],[17,5],[15,3],[11,9],[1,12],[8,3],[6,12],[15,7],[4,1],[17,18],[14,10],[2,13],[16,9],[11,5],[3,16],[18,4],[12,17],[10,8],[5,2],[7,11],[13,15],[9,14],[1,6],[14,3],[16,7],[10,9],[8,1],[11,12],[2,18],[15,5],[6,4],[17,13],[13,9],[3,2],[7,14],[4,17],[5,16],[12,8],[6,15],[1,11],[18,10],[14,12],[10,3],[15,1],[9,18],[2,7],[16,13],[17,6],[8,5],[11,4],[4,8],[3,9],[13,11],[5,14],[6,16],[1,17],[18,15],[12,2],[7,10],[3,13],[9,7],[11,6],[15,17],[14,4],[2,1],[10,5],[8,18],[16,12],[13,14],[17,8],[18,11],[12,10],[1,16],[4,15],[6,2],[7,3],[5,9],[7,13],[3,5],[10,1],[14,6],[9,12],[11,17],[8,15],[2,4],[16,18],[12,3],[17,2],[18,14],[5,7],[15,11],[1,9],[4,16],[13,10],[6,8],[11,8],[3,18],[5,13],[9,4],[7,12],[10,6],[16,17],[2,15],[14,1],[12,5],[6,3],[17,9],[4,10],[1,7],[15,14],[8,2],[18,13],[11,16],[10,15],[13,12],[7,18],[9,6],[16,8],[3,1],[5,4],[2,11],[14,17],[11,14],[17,3],[1,13],[15,16],[4,12],[18,5],[8,9],[6,7],[2,10],[3,4],[16,2],[10,11],[12,18],[5,1],[7,17],[14,8],[9,15],[13,6],[8,13],[17,10],[16,14],[6,5],[11,3],[2,9],[4,7],[1,18],[15,12],[5,17],[14,2],[9,11],[7,8],[13,4],[3,15],[12,1],[10,16],[18,6]]];
if (is_file($season_dir.'cal') || is_file($season_dir.'gen'))
  echo '
<p style="font-weight:bold;color:red">ВНИМАНИЕ: в настройках сезона уже есть файлы календаря и генераторов чемпионата!<br />
Во избежание случайной потери данных редактируйте их содержимое на соответствующих страницах сезона!</p>';
else {
  $basket = isset($_POST['basket']) ? $_POST['basket'] : [];
  $cfg = file($season_dir.'fp.cfg');
  $season_config = json_decode($cfg[4], true);
  foreach ($season_config as $tournament)
    if ($tournament['type'] == 'chm' || $tournament['type'] == 'com') break;

  $groups = $tournament['format'][0]['groups'];
  $teams = (isset($tournament['format'][0]['tourn']) ? $tournament['format'][0]['tourn'] : $tournament['format'][0]['tours'][1]) / 2 + 1;
  $codes = file($season_dir.'codes.tsv');
  for ($i = count($codes) - 1; $i >= 0; $i--)
    if ($codes[$i][0] == '-' || $codes[$i][0] == '#')
      unset($codes[$i]);

  if (!isset($teams))
    $teams = count($codes);

  if (!isset($_POST['basket']))
    for ($j = 0; $j < $groups; $j++) {
      $basket[$j] = '';
      for ($i = 0; $i < $teams ; $i++)
        if (isset($codes[$i + $j * $teams]))
          $basket[$j] .= explode('	', $codes[$i + $j * $teams])[1]."\n";

    }

  $hint = ['высшая лига', 'первый дивизион', 'второй дивизион'];
  echo '
<form name="dform" method="POST">
  <p>';
  for ($i = 0; $i < $groups; $i++)
    echo '
    <textarea name=basket['.$i.'] style="width:30%;line-height:1em;height:'.$teams.'em" placeholder="'.$hint[$i].'">'.trim($basket[$i]).'</textarea>';

  echo '
  </p>';
  if (!isset($_POST['basket'])) {
    if (substr_count($basket[0], "\n") != substr_count($basket[count($basket) - 1], "\n")) {
      $disabled = ' disabled="disabled"';
      echo '
  <p style="font-weight:bold;color:red">Вы не готовы создать календарь чемпионата, поскольку не все лиги укомплектованы.</p>';
    }
    else
      $disabled = '';

    echo '
  <p>
    <b>"Злые" генераторы:</b> <input name="angry" type="checkbox" />
    &nbsp; &nbsp; &nbsp; <input type=submit name=draw value="построение календаря и генераторов"'.$disabled.'>
  </p>
  <p>
    Для создания календаря и списка прогнозов-генераторов для чемпионата укажите список команд каждой лиги по одной в строке в 1-й, 2-х или 3-х корзинах и нажмите кнопку.<br />
    Отметка "Злые" генераторы" несколько увеличит шанс появления "единиц" в генераторах: 40:30:30 вместо равновероятного.
  </p>';
  }
  echo '
</form>';
  if (isset($_POST['basket'])) {
    $comm = [];
    for ($j = 0; $j < $groups; $j++) {
      $comm[$j] = explode("\n", trim($basket[$j]));
      shuffle($comm[$j]);
    }
    $tours = (count($comm[0]) - 1) * 2;
    $mt = count($comm[0]) / 2;
    $cal = $caltpl[count($comm[0])];
    $out = '';
    for ($t = 0; $t < $tours; $t++) {
      $out .= ' Тур '.($t + 1).'        '.$cca.sprintf('%02d', $t + 1)."\n";
      for ($j = 0; $j < $groups; $j++)
        for ($i = 0; $i < $mt; $i++)
          $out .= trim($comm[$j][$cal[$i + $t * $mt][0] - 1]).' - '.trim($comm[$j][$cal[$i + $t * $mt][1] - 1])."\n";

      $out .= "\n";
    }
    file_put_contents($season_dir.'cal', $out);
    echo '
<p>
  Файлы cal и gen созданы, их можно посмотреть, а при необходимости и поправить на соответствующих страницах сезона.<br />
  Хотя их тексты приведены и на этой странице, рассылку игрокам все же удобнее будет сделать со страниц "Календарь" и "Генераторы".<br />
  Теперь можно <a href="/?a='.$a.'&m=mkpgm" target="_blank"><b>создавать программки туров чемпионата</b></a>.
</p>
<p>
  <textarea style="font-family:monospace;height:30em;line-height:1em;width:30%">'.$out.'</textarea>';
    $league = count($comm);
    $genpt = 10 * $league;
    $factor = isset($_POST['angry']) ? 1 : 0;
    $spacer = '      ';
    $out = "\n";
    for ($t = 1; $t <= $tours; $t++) {
      $out .= $cca.sprintf('%02d',$t)."\n\n";
      for ($g = 1; $g <= $genpt; $g++) {
        $pods = rand(1, 10);
        for ($i = 1; $i <= 10; $i++) {
          $p = ceil(rand($factor, 9) / 3);
          if ($p == 0)
            $p++;

          if ($p == 3)
            $p = 'X';

          $out .= $p;
          if ($i == $pods) {
            $f1 = ($p == 1) ? 1 : $factor;
            $pods = ceil(rand($factor, 6) / 3);
            if ($pods == 0)
              $pods++;

            if ($pods == $p)
              $pods = 'X';

            $out .= '('.$pods.')';
            $pods = 0;
          }
        }
        $out .= ' ';
        for ($i = 1; $i <= 5; $i++) {
          $p = ceil(rand($factor, 9) / 3);
          if ($p == 0)
            $p++;

          if ($p == 3)
            $p = 'X';

          $out .= $p;
        }
        $out .= ' *' . ($g % $league ? $spacer : "\n");
      }
      $out .= "\n";
    }
    file_put_contents($season_dir.'gen', $out);
    echo '
  <textarea style="font-family:monospace;height:30em;line-height:1em;width:'.(16 * $groups - 4).'em">'.$out.'</textarea>
</p>';
  }
}
?>