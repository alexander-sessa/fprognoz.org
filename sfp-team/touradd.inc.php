<?php
include('online/realteam.inc.php');

function HTTP_CURL($url, $ref='', $post=false) {
  $ch = curl_init();
  $options = array(
CURLOPT_URL => $url,
CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SFPautoTrainer/1.0; +http://fprognoz.org)',
CURLOPT_COOKIEJAR => '/home/fp/data/cookie/getpage.txt',
CURLOPT_COOKIEFILE => '/home/fp/data/cookie/getpage.txt',
CURLOPT_HTTPHEADER => array('ACCEPT_LANGUAGE: ru;0.9,ru-RU;0.8', 'CONNECTION: Keep-Alive'),
CURLOPT_REFERER => $ref,
CURLOPT_HEADER => 1,
CURLOPT_FOLLOWLOCATION => 1,
CURLOPT_TIMEOUT => 60,
CURLOPT_RETURNTRANSFER => true
);
  if ($post) {
    $options[CURLOPT_POST] = 1;
    $options[CURLOPT_POSTFIELDS] = $post;
  }
  curl_setopt_array($ch, $options);
  $ret = curl_exec($ch); 
  curl_close($ch); 
  return $ret;
}

function table2array($table) {
  $array = $h = [];
  $rows = explode('</tr>', $table);
  foreach ($rows as $row)
  {
    if (!strpos($row, '</th>') && !strpos($row, '</td>'))
      continue;

    $cols = explode('</t', $row);
    if ($c = sizeof($h))
    {
      $a = [];
      for ($i = 0; $i < $c; $i++)
        if (isset($cols[$i]))
          $a[$h[$i]] = substr($cols[$i], strrpos($cols[$i], '>') + 1);

      if (trim($a['№']))
        $array[] = $a;

    }
    else
      foreach ($cols as $col)
        if ($title = substr($col, strrpos($col, '>') + 1))
          $h[] = trim($title);

  }
  return $array;
}

function Schedule($timestamp, $country_code, $tour_code, $action, $pfname) {
  global $online_dir;
  $dir = $online_dir . 'schedule/'.date('Y/m/d', $timestamp);
  if (!is_dir($dir))
    mkdir($dir, 0755, true);

  file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.$tour_code.'.'.$action, $pfname);
}

$cal_tran = [
 'Monday' => 'понедельник',
 'Tuesday' => 'вторник',
 'Wednesday' => 'среда',
 'Thirsday' => 'четверг',
 'Friday' => 'пятница',
 'Saturday' => 'суббота',
 'Sunday' => 'воскресенье',
 'September' => 'сентября',
 'October' => 'октября',
 'November' => 'ноября',
 'December' => 'декабря',
 'Januar' => 'января',
 'Februar' => 'февраля',
 'March' => 'марта',
 'April' => 'апреля',
 'May' => 'мая',
];

$cc = [
  'Австрия' => 'Авт',
  'Англия' => 'Анг',
  'Бельгия' => 'Бел',
  'Германия' => 'Гер',
  'Нидерланды' => 'Гол',
  'Испания' => 'Исп',
  'Италия' => 'Ита',
  'Франция' => 'Фра',
  'Турция' => 'Тур',
  'Греция' => 'Гри',
  'Россия' => 'Рос',
  'Украина' => 'Укр',
  'Швейцария' => 'Шва',
];

$dir = scandir($season_dir.$l, 1);
$next_tour = current($dir);
if ($next_tour[0] == '.')
  $next_tour = '00';

$next_tour++;
//$next_tour = ltrim($next_tour, '0');
$_tour = ($next_tour < 10 ? '0' : '' ) . $next_tour;
$program_url = 'http://fprognoz.org/?a=sfp-team&s='.$s.'&l='.$l.'&m=prognoz&t=' . $_tour;
$tour_code = $l . $_tour;
$monitor = [];

switch ($l)
{
  case 'PRO':
    $url = 'http://profi-prognoza.ru/profiopen/online/prognoz.inc.php?s='.date('Y').'&t='.$next_tour;
    $html = file_get_contents($url);
    $html = substr($html, strpos($html, '<table style="margin:auto">'));
    $html = strtr($html, ['матч для прогнозирования' => 'МдП', 'дата время' => 'дата']);
    $tour_table = table2array($html);
    $timestamp = file_get_contents('http://profi-prognoza.ru/profiopen/online/' . date('Y') . '/' . $_tour . '/term');
    $program = '
Прогнозы на ' . $next_tour . '-й тур делать на этой странице:
' . $program_url . '
Последний срок: '. strtr(date_tz('l j F в H:i', '', $timestamp - 600, 'Europe/Moscow'), $cal_tran) .' по Москве.

Программка для тех, кто отправляет прогнозы емейлом:
Для матчей 1-5 угадываем исход. Ничья обозначается нулём!
Для матчей 6-10 угадываем счёт.
Строка прогноза должна выглядеть примерно так: 012014130132011

 |   | Код тура ' . $tour_code . '                              |     |
 |---+---------------------------------------------+-----|';
    foreach ($tour_table as $match)
    {
      $teams = explode(' - ', $match['МдП']);
      $line = '';
      foreach ($teams as $team)
        $line .= $realteam[$team] . ',';

      $monitor[] = $line;
      $program .= mb_sprintf('
 |%2s.| %-44s|', $match['№'], $match['МдП']) . strtr(explode(' ', $match['дата'])[0], ['&nbsp;' => '']) . '|';
    }
    $program .= '
 |---+---------------------------------------------+-----|
 | Контрольный срок отправки                  ' . date_tz('d.m H:i', '', $timestamp - 600, 'Europe/Berlin') . '|

В турнире участвуют:

';
    break;
  case 'FFP':
    $url = 'http://www.kfp.ru/fest/ffp'.date('Y').'/tur2009.php?tur='.$next_tour.'&ref=0';
    $html = file_get_contents($url);
    $html = iconv('windows-1251', 'utf-8', $html);
    $html = substr($html, strpos($html, 'Последний срок приема прогнозов: ') + 61);
    $html = substr($html, 0, strpos($html, '</table>'));
    $timestamp = strtotime(strtr(substr($html, 0, strpos($html, ' по')), [' в' => '.' . date('Y'), '-' => ':']) . ' Europe/Moscow');
    $table = explode('</td></tr><tr><td><b>', $html);
    unset($table[1]);
    unset($table[0]);
    $program = '
Прогнозы на ' . $next_tour . '-й тур делать на этой странице:
' . $program_url . '
Последний срок: '. strtr(date_tz('l j F в H:i', '', $timestamp - 600, 'Europe/Moscow'), $cal_tran) .' по Москве.

Программка для тех, кто отправляет прогнозы емейлом:

 |   | Код тура ' . $tour_code . '                              |     |
 |---+---------------------------------------------+-----|';
    foreach ($table as $line)
    {
      $line = trim(substr($line, 0, strpos($line, '</b>')));
      $n = trim(substr($line, 0, strpos($line, '.')));
      $line = substr($line, strpos($line, '(') + 1);
      $d = substr($line, 0, strpos($line, '-'));
      $line = substr($line, strpos($line, '-') + 1);
      $c = substr($line, 0, 2) . mb_strtolower(substr($line, 2, strpos($line, ')') - 2));
      $line = trim(substr($line, strpos($line, ')') + 1));
      $teams = explode('-	', $line);
      $line = '';
      foreach ($teams as $team)
        $line .= $realteam[$team] . ',';

      $monitor[] = $line;
      $program .= mb_sprintf('
 |%2s.| %-40s', $n, implode(' - ', $teams)) . $c . ' |' . $d . '|';
    }
    $program .= '
 |---+---------------------------------------------+-----|
 | Контрольный срок отправки                  ' . date_tz('d.m H:i', '', $timestamp - 600, 'Europe/Berlin') . '|

Для всех 11 матчей программки надо угадывать исход.
';
    break;
  case 'SPR':
    $html = HTTP_CURL('http://sportgiant.net/championships/xiii-spartakiada-2019' . ($next_tour > 9 ? '-final' : '') . '/calendar?locale=en');
    $html = substr($html, strpos($html, 'Tour ' . ($next_tour > 9 ? $next_tour - 9 : $next_tour) . ' '));
    $cut = strpos($html, '- ') + 2;
    $deadline = substr($html, $cut, strpos($html, '</h3>') - $cut);
    //$deadline = strtr($deadline, ['сентября' => 'September', 'октября' => 'October', 'ноября' => 'November', 'декабря' => 'December']);
    $timestamp = strtotime($deadline);
    $program = '
Прогнозы на ' . $next_tour . '-й тур делать на этой странице:
http://sportgiant.net/championships/xiii-spartakiada-2019-gruppa-c/tours/' . $next_tour . '.0
или же на странице нашего сайта:
http://fprognoz.org/?a=sfp-team&amp;s=2019-20&amp;l=SPR&amp;m=prognoz&amp;t=' . $next_tour . '
Последний срок: '. strtr(date_tz('l j F в H:i', '', $timestamp - 600, 'Europe/Moscow'), $cal_tran) .' по Москве.

Программка для тех, кто отправляет прогнозы емейлом:

 |   | Код тура ' . $tour_code . '                              |     |
 |---+---------------------------------------------+-----|';
    //получить url матча
    $page = substr($html, strpos($html, '/games/'), 14);
    $html = HTTP_CURL('http://sportgiant.net/' . $page . '?locale=ru&layout=false&brief=true');
    $html = substr($html, strpos($html, '<tbody>'));
    $html = substr($html, 0, strpos($html, '</tbody>'));
    $html = strtr($html, ['</a>' => '', ' сент.' => '.09', ' окт.' => '.10', ' нояб.' => '.11', ' дек.' => '.12']);
    $table = explode('<tr', $html);
    unset($table[0]);
    $n = 1;
    foreach ($table as $match)
    {
      $tds = explode('</td>', $match);
      $teams = [trim(substr($tds[1], strrpos($tds[1], '>') + 1)), trim(substr($tds[3], strrpos($tds[3], '>') + 1))];
      $line = '';
      foreach ($teams as $team)
        $line .= $realteam[$team] . ',';

      $monitor[] = $line;
      $program .= mb_sprintf('
 |%2s.| %-44s|', $n++, implode(' - ', $teams)) . substr(explode('</span>', $tds[2])[0], -5) . '|';
    }
    $program .= '
 |---+---------------------------------------------+-----|
 | Контрольный срок отправки                  ' . date_tz('d.m H:i', '', $timestamp - 600, 'Europe/Berlin') . '|

Для всех 10 матчей программки надо угадывать исход.
На 2 матча надо сделать дополнительный прогноз.
Строка прогноза должна выглядеть примерно так: 010120(1)1012(0)
';
    break;
  case 'TOR':
    // получить список туров и выбрать из него нужный
    $url = 'http://www.torpedoru.com/table.php?championat_id=103';
    $content = file_get_contents($url);
    $content = substr($content, strpos($content, 'js-nav-tours js-nav-container'));
    $content = substr($content, 0, strpos($content, '</div>'));
    $tours = explode("<a href = '/tour.php?id=", $content);
    foreach($tours as $tline)
      if (strpos($tline, 'Групповой турнир. ' . $next_tour . ' '))
      {
        $tid = substr($tline, 0, 4);
        $deadline = substr($tline, strpos($tline, ' &lt;До ') + 10, 19) . ' Europe/Moscow';
        $timestamp = strtotime($deadline);
        break;
      }

    $program = '
Прогнозы на ' . $next_tour . '-й тур делать на этой странице:
http://fprognoz.org/?a=sfp-team&amp;s=2019-20&amp;l=TOR&amp;m=prognoz&amp;t=' . $next_tour . '
Последний срок: '. strtr(date_tz('l j F в H:i', '', $timestamp - 600, 'Europe/Moscow'), $cal_tran) .' по Москве.

Обращаю внимание на то, что из 13-ти матчей надо поставить только на 10.
Три наиболее сложных для прогнозирования матча надо оставить без прогноза!

Программка для тех, кто отправляет прогнозы емейлом:
Ничья обозначается нулём, матчи без прогноза- *

 |   | Код тура ' . $tour_code . '                              |     |
 |---+---------------------------------------------+-----|';
    $html = file_get_contents('http://torpedoru.com/tour.php?id=' . $tid);
    $html = substr($html, strpos($html, '<table>'));
    $html = substr($html, 0, strpos($html, '</tr></table>'));
    $table = explode('</tr>', $html);
    foreach ($table as $match)
    {
      $tds = explode('</td>', $match);
      $n = substr($tds[0], strrpos($tds[0], '>') + 1);
      list($c, $match) = explode(') ', substr($tds[1], strrpos($tds[1], '>') + 2));
      $c = $cc[$c];
      $teams = explode(' - ', $match);
      $line = '';
      foreach ($teams as $team)
        $line .= $realteam[$team] . ',';

      $monitor[] = $line;
      $program .= mb_sprintf('
 |%2s.| %-40s', $n, $match) . $c . ' |' . date('d.m', $timestamp) . '|';
    }
    $program .= '
 |---+---------------------------------------------+-----|
 | Контрольный срок отправки                  ' . date_tz('d.m H:i', '', $timestamp - 600, 'Europe/Berlin') . '|
';
    break;
  case 'FWD':
    break;
}
if ($program)
{
  echo '
                <pre>
'.$program.'</pre>';
  $file = $online_dir . 'SFP/' . $s . '/publish/' . $tour_code;
  file_put_contents($file, $program);
  symlink($file, $online_dir . 'SFP/' . $s . '/' . $l . '/' . $_tour);
/*
  if ($l == 'FFP')
  {
    Schedule($timestamp - 93601, 'SFP', $tour_code, 'remind', $file);
    Schedule($timestamp, 'SFP', $tour_code, 'monitor', implode("\n", $monitor));
  }
*/
}
?>
