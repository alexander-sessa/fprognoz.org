<?php
function Schedule($timestamp, $country_code, $tour_code, $action, $pfname)
{
  $dir = $online_dir.'schedule/'.date('Y/m/d', $timestamp);
  if (!is_dir($dir)) mkdir($dir);
  file_put_contents("$dir/$timestamp.$country_code.$tour_code.$action", $pfname);
}

if (isset($_POST['file_text']) && $_POST['file_text'])
{
  if ($file == 'tplpchm') $fname = $online_dir."$cca/$s/p.tpl";
  else if ($file == 'tplpcup') $fname = $online_dir."$cca/$s/pc.tpl";
  else if ($file == 'tplichm') $fname = $online_dir."$cca/$s/it.tpl";
  else if ($file == 'tplicup') $fname = $online_dir."$cca/$s/itc.tpl";
  else if ($file == 'tplrev') $fname = $online_dir."$cca/$s/header";
  else if ($file == 'calchm') $fname = $online_dir."$cca/$s/cal";
  else if ($file == 'genchm') $fname = $online_dir."$cca/$s/gen";
  else if ($file == 'calcup') $fname = $online_dir."$cca/$s/calc";
  else if ($file == 'gencup') $fname = $online_dir."$cca/$s/genc";
  else if ($file == 'bombers') $fname = $online_dir."$cca/$s/bombers";
  else if ($file == 'settings') $fname = "$a/settings.inc.php";
  else
  {
    $program = $_POST['file_text'];
    $fr = strpos($program, $cca, strripos($program, 'FP_Prognoz'));
    $tour_code = trim(substr($program, $fr, strpos($program, "\n", $fr) - $fr));
    if ($cut = strpos($tour_code, ' '))
      $tour_code = substr($tour_code, 0, $cut);
    if ($tour_code[strlen($cca)] == 'C')
      $pfname = $online_dir."$cca/$s/publish/pc".substr(str_replace('NEW', '', $tour_code), -1);
    else if ($tour_code[strlen($cca)] == 'S')
      $pfname = $online_dir."$cca/$s/publish/ps".substr(str_replace('NEW', '', $tour_code), -1);
    else if ($tour_code[strlen($cca)] == 'P')
      $pfname = $online_dir."$cca/$s/publish/pp".substr(str_replace('NEW', '', $tour_code), -1);
    else if ($tour_code[strlen($cca)] == 'G')
      $pfname = $online_dir."$cca/$s/publish/pg".substr(str_replace('NEW', '', $tour_code), -1);
    else
      $pfname = $online_dir."$cca/$s/publish/p".substr(str_replace('NEW', '', $tour_code), -2);

    if (is_file($pfname)) rename($pfname, $pfname.'.'.time());
    file_put_contents($pfname, $program);
    $fname = $online_dir."$cca/$s/programs/$tour_code";

    $program = str_replace(')-', ') - ', $program);
    $fr = strpos($program, "\n", strpos($program, "$tour_code ")) + 1;
    $program = substr($program, $fr);
    $fr = strpos($program, "Контрольный с");
    $matches = explode("\n", substr($program, 0, $fr));

    // make records for scheduler
    $program = substr($program, $fr);
    $months = array(
    ' января' => '.01',' янваpя' => '.01',
    ' февраля' => '.02', ' февpаля' => '.02',
    ' марта' => '.03', ' маpта' => '.03',
    ' апреля' => '.04', ' апpеля' => '.04',
    ' мая' => '.05',
    ' июня' => '.06',
    ' июля' => '.07',
    ' августа' => '.08',
    ' сентября' => '.09', ' сентябpя' => '.09',
    ' октября' => '.10',  ' октябpя' => '.10',
    ' ноября' => '.11',  ' ноябpя' => '.11',
    ' декабря' => '.12', ' декабpя' => '.12'
    );
    foreach ($months as $word => $digit)
      $program = str_replace($word, $digit, $program);
    $fr = strpos($program, '.');
    $lastdate = trim(substr($program, $fr - 2, 5));
    if (($fr1 = strpos($program, ':', $fr)) && ($fr1 - $fr < 50))
      $lasttm = trim(substr($program, $fr1 - 2, 5));
    else
      $lasttm = '23:59:59';
    $atemp = explode('.', $lastdate);
    $month = trim(sprintf('%02s',$atemp[1]));
    $year = date('Y', time());
    if (date('m', time()) > $month) $year++;
    $timestamp = strtotime("$lastdate.$year $lasttm");
    $pfname = str_replace($online_dir.'', '', $pfname);
    Schedule($timestamp - 345600, $cca, $tour_code, 'resend', $pfname);
    Schedule($timestamp + 36000, $cca, $tour_code, 'remind', $pfname);

    // add new real teams and fill program for scheduler
    $progsched = "0\n";
    include script_from_cache('online/realteam.inc.php');
    $oldzize = sizeof($realteam);
    foreach ($matches as $line) if ($line = trim($line))
    {
      if (strpos($line, ' - '))
      {
        (strpos($line, '│') !== false) ? $divider = '│' : $divider = '|';
        $h = '';
        $a = '';
        $atemp = explode($divider, $line);
        if (sizeof($atemp) > 2 && $cut = strpos($atemp[2], ' - '))
        {
          $h = trim(substr($atemp[2], 0, $cut));
          if ($cut1 = strrpos($h, '(')) $h = trim(substr($h, 0, $cut1));
          $a = trim(substr($atemp[2], $cut + 3));
          if (trim(substr($atemp[2], -3)))
            $a = trim(substr($a, 0, strrpos($a, ' ')));
          if ($cut1 = strrpos($a, '(')) $a = trim(substr($a, 0, $cut1));
        }
        if ($h) {
        if (!isset($realteam[$h])) $realteam[$h] = $h;
        if (!isset($realteam[$a])) $realteam[$a] = $a;
          $progsched .= $realteam[$h].','.$realteam[$a].",\n";
        }
      }
    }
    Schedule($timestamp + 43200, $cca, $tour_code, 'monitor', $progsched);
    $dir = $online_dir."$cca/$s/prognoz/$tour_code";
    if (!is_dir($dir)) mkdir ($dir, 0755);
    file_put_contents("$dir/term", $timestamp);
    touch("$dir/mail");
    touch("$dir/adds");

    if (sizeof($realteam) > $oldsize)
    {
      $temp = "<?php \$realteam = array (\n";
      foreach ($realteam as $t1 => $t2)
        $temp .= '"'.str_replace('"', '\"', $t1).'" => "'.$t2.'",
';
      $temp .= ');?>';
      file_put_contents('online/realteam.inc.php', $temp);
    }
  }
  file_put_contents($fname, str_replace("\r", '', $_POST['file_text']));
  echo 'Файл '.$fname.' изменен<br />
';
  if ($file == 'settings') { // а вдруг уже новый сезон?
    $cut = strpos($_POST['file_text'], '$cur_year');
    $cut = strpos($_POST['file_text'], "'", $cut) + 1;
    $len = strpos($_POST['file_text'], "'", $cut) - $cut;
    $new = substr($_POST['file_text'], $cut, $len);
    if ($new > $s) {
      mkdir($online_dir."$cca/$new/bomb", 0755, true);
      mkdir($online_dir."$cca/$new/bomc", 0755, true);
      mkdir($online_dir."$cca/$new/boms", 0755, true);
      mkdir($online_dir."$cca/$new/prognoz", 0755, true);
      mkdir($online_dir."$cca/$new/programs", 0755, true);
      mkdir($online_dir."$cca/$new/publish", 0755, true);
      copy($online_dir."$cca/$s/codes.tsv", $online_dir."$cca/$new/codes.tsv");
      copy($online_dir."$cca/$s/fp.cfg", $online_dir."$cca/$new/fp.cfg");
      copy($online_dir."$cca/$s/headers", $online_dir."$cca/$new/headers");
      copy($online_dir."$cca/$s/p.tpl", $online_dir."$cca/$new/p.tpl");
      copy($online_dir."$cca/$s/pc.tpl", $online_dir."$cca/$new/pc.tpl");
      copy($online_dir."$cca/$s/it.tpl", $online_dir."$cca/$new/it.tpl");
      copy($online_dir."$cca/$s/itc.tpl", $online_dir."$cca/$new/itc.tpl");
      echo 'Создана структура данных сезона '.$new.'.<br />
Можно начинать!<br />
';
    }
  }
}
else if (isset($_GET['file']))
{
  $file = $_GET['file'];
  if      ($file == 'tplpchm') $file_txt = file_get_contents($online_dir."$cca/$s/p.tpl");
  else if ($file == 'tplpcup') $file_txt = file_get_contents($online_dir."$cca/$s/pc.tpl");
  else if ($file == 'tplichm') $file_txt = file_get_contents($online_dir."$cca/$s/it.tpl");
  else if ($file == 'tplicup') $file_txt = file_get_contents($online_dir."$cca/$s/itc.tpl");
  else if ($file == 'tplrev') $file_txt = file_get_contents($online_dir."$cca/$s/header");
  else if ($file == 'calchm')  $file_txt = file_get_contents($online_dir."$cca/$s/cal");
  else if ($file == 'genchm')  $file_txt = file_get_contents($online_dir."$cca/$s/gen");
  else if ($file == 'calcup')  $file_txt = file_get_contents($online_dir."$cca/$s/calc");
  else if ($file == 'gencup')  $file_txt = file_get_contents($online_dir."$cca/$s/genc");
  else if ($file == 'bombers') $file_txt = file_get_contents($online_dir."$cca/$s/bombers");
  else if ($file == 'settings') $file_txt = file_get_contents("$a/settings.inc.php");
  else $file_txt = '';
  $nrows = max(35, substr_count($file_txt, "\n") + 1);
  if (mb_detect_encoding($file_txt, 'UTF-8', true) === FALSE)
  {
    if ($file[0] == 't') $file_txt = iconv('KOI8-R', 'UTF-8//IGNORE', $file_txt);
    else $file_txt = iconv('CP1251', 'UTF-8//IGNORE', $file_txt);
  }
  echo '<form method=post><textarea name=file_text rows='.$nrows.' cols=100>'.$file_txt.'</textarea><br /><input type=submit name=upload value="сохранить"></form>';
}
else {
if (!isset($t)) $t = '';
?>
<p>По ссылкам ниже открываются страницы редактирования файлов настроек сезона.<br />
Большинство из этих файлов не требуют ручного редактирования: одни из них генерируются скриптами,
другие - подготовлены администратором сервера.<br />
Но возможность что-то поправить быть должна всегда.</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=tplpchm">Заливка / редактирование макета программки тура чемпионата</a></p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=tplpcup">Заливка / редактирование макета программки кубкового тура</a></p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=tplichm">Заливка / редактирование макета итогов тура чемпионата</a></p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=tplicup">Заливка / редактирование макета итогов кубкового тура</a></p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=tplrev">Заливка / редактирование макета (шапки) обзора</a></p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=calchm">Заливка / редактирование календаря чемпионата</a><br />
Для построения календаря и списка генераторов чемпионата рекомендуем пользоваться
<a href=$online_dir."cal.php?cc=<?=$cca;?>" target="_blank">Генератором календаря</a>.<br />
Обычно последующая правка календаря чемпионата не нужна</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=genchm">Заливка / редактирование генераторов чемпионата</a><br />
Для построения календаря и списка генераторов чемпионата рекомендуем пользоваться
<a href=$online_dir."cal.php?cc=<?=$cca;?>" target="_blank">Генератором календаря</a>.<br />
Последующая правка списка генераторов недопустима!</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=calcup">Заливка / редактирование календаря кубка</a><br />
Для жеребьевки и создания списка генераторов кубка рекомендуем пользоваться
<a href=$online_dir."draw.php?cc=<?=$cca;?>" target="_blank">Скриптом жеребьевки кубка</a>.<br />
Последующая правка календаря чемпионата обычно не нужна, поскольку файл календаря автоматически
заполняется матчами, указанными в программках.</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=gencup">Заливка / редактирование генераторов кубка</a><br />
Для создания списка генераторов кубка рекомендуем пользоваться
<a href=$online_dir."draw.php?cc=<?=$cca;?>" target="_blank">Скриптом жеребьевки кубка</a>.<br />
В случае, если был опубликован список генераторов на все раунды кубка,
последующая правка списка генераторов недопустима!</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=bombers">Заливка / редактирование файла бомбардиров</a><br />
Для большинства лиг такой файл строится скриптом по данным статистики реальных бомбардиров.<br />
Игроки вскоре получат возможность самостоятельно управлять составом своей команды на странице "Команды".</p>

<p><a href="?<?="a=$a&amp;s=$s&amp;t=$t";?>&amp;m=files&amp;file=program">Заливка программки тура</a><br />
Рекомендуется пользоваться <a href=$online_dir."makeprogramm.php?cc=<?=$cca;?>" target="_blank">Генератором программок</a> -
он позволяет сделать всё то же, что иногда приходилось делать руками, но при этом программки выходят
гарантровано правильными.</p>
<?php } ?>