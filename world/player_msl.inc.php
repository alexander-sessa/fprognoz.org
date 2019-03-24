<?php
$s = $cur_year;
$out = '';
$ccr = array(
'ENG' => 'Англия',
'BLR' => 'Беларусь',
'GER' => 'Германия',
'NLD' => 'Голландия',
'ESP' => 'Испания',
'ITA' => 'Италия',
'PRT' => 'Португалия',
'RUS' => 'Россия',
'UKR' => 'Украина',
'FRA' => 'Франция',
'SUI' => 'Швейцария',
'SCO' => 'Шотландия',
);

eval('$sites = '.file_get_contents($online_dir.'UNL/'.$s.'/sites.inc'));
$codes = file($online_dir.'UNL/'.$s.'/codes.tsv');
$prev = '';
$out = $head = $coach = $squad = $coach_prefix = '';
foreach ($codes as $line)
{
  list($code, $team, $name, $email, $role) = explode('	', $line);
  if (!array_key_exists($team, $ccr))
  { // здесь только сборные сайтов
    if ($team != $prev)
    {
      $prev = $team;
      $out .= $head . $coach_prefix . $coach . $squad;
      $head = '
    <div class="teamcard">
      <div class="teamname">
        <div style="width:100px; text-align:center">'.$sites[$team].'</div>
        <div>'.$team.'</div>
      </div>
      <div>';
      $coach_prefix = 'Тренер: ';
      $coach = $code;
      $squad = '
      </div>
      <div>
        Состав: ';
      $lines = file($online_dir.'UNL/'.$s.'/'.$team.'.csv');
      foreach ($lines as $line)
      {
        list($name, $mail, $role) = explode(';', trim($line));
        $squad .= $name.', ';
      }
      $squad = rtrim($squad, ', ');
      $squad .= '
      </div>
    </div>';
    }
    else if (trim($role == 'coach'))
    {
      $coach_prefix = 'Тренеры: ';
      $coach .= ', '.$code;
    }
  }
}
$out .= $head . $coach_prefix . $coach . $squad;

?>
<p class="title text15b">&nbsp;&nbsp;&nbsp;Участники Лиги Сайтов</p>
<style>
.teamcard { border: 1px solid blue; border-radius: 20px; margin:10px; padding: 10px }
.teamname { display:flex; font-weight:bold; font-size:125% }
</style>
<div>
<?=$out?>
</div>
