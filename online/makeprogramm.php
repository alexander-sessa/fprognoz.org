<?php
//date_default_timezone_set('UTC');
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
session_save_path('/var/lib/php/sessions');
session_start();
require_once ('/home/fp/data/config.inc.php');
include 'realteam.inc.php';
include 'cc.inc.php';
include 'translate.inc.php';

function GetTourFromCalendar($tour, $cal) {
  $cclen = $tour[4] == 'L' ? 5 : 3;
  $tourn = ltrim(ltrim(substr($tour, $cclen), '0'), 'C');
  if (($fr = strpos($cal, $tour)) === false)
    $fr = strpos($cal, " Тур $tourn");

  if ($fr === false)
    return $fr;

  $fr = strpos($cal, "\n", $fr) + 1;
  if (($cal[$fr + 1] == '-') || ($cal[$fr + 1] == '='))
    $fr = strpos($cal, "\n", $fr) + 1;

  if ($to = strpos($cal, " Тур", $fr))
    return substr($cal, $fr, $to - $fr);

  return substr($cal, $fr);
}

function FillTemplate($template, $blockname, $tpltbl) {
  $fr = 0;
  if ($fr = (strpos($template, "[$blockname]", $fr + 1))) {
    $out = '';
    $to = strpos($template, "\n", $fr) + 1;
    $line = substr ($template, $fr, $to - $fr);
    $lblk = $tpltbl[$blockname];
    $tmp = substr ($template, 0, $fr);
    $lblkpos = $fr - strrpos($tmp, "\n") - 1;
    if ($rblkpos = strpos($line, '[', 10)) {
      $rblknm = substr($line, $rblkpos + 1, strpos($line, ']', $rblkpos) - $rblkpos - 1);
      $rblk = $tpltbl[$rblknm];
      $m = max(sizeof($lblk), sizeof($rblk));
      for ($i=0; $i<$m; $i++) {
        if ($i) $out .= str_repeat(' ', $lblkpos);
        $out .= trim($lblk[$i]) . str_repeat(' ', $rblkpos - mb_strlen(trim($lblk[$i]))) . trim($rblk[$i])."\n";
      }
    }
    else
      for ($i=0; $i<sizeof($lblk); $i++) {
        if ($i) $out .= str_repeat(' ', $lblkpos);
        $out .= trim($lblk[$i])."\n";
      }

    $template = str_replace($line, $out, $template);
  }
  return $template;
}

function Schedule($timestamp, $country_code, $tour_code, $action, $pfname) {
  global $online_dir;
  $dir = $online_dir . 'schedule/'.date('Y/m/d', $timestamp);
  if (!is_dir($dir)) mkdir($dir, 0755, true);
  file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.$tour_code.'.'.$action, $pfname);
  if ($country_code == 'UEFA' && $action != 'resend') {
    file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.str_replace('UEFA', 'CHAM', $tour_code).'.'.$action, $pfname);
    file_put_contents($dir.'/'.($timestamp-1).'.'.$country_code.'.'.str_replace('UEFA', 'GOLD', $tour_code).'.'.$action, $pfname);
    file_put_contents($dir.'/'.$timestamp.'.'.$country_code.'.'.str_replace('UEFA', 'CUPS', $tour_code).'.'.$action, $pfname);
  }
}

function mkPrognozDir($timestamp, $ccode, $season, $TourCode) {
  global $online_dir;
  if (!is_dir($online_dir . "$ccode/$season/prognoz/$TourCode"))
    mkdir ($online_dir . "$ccode/$season/prognoz/$TourCode", 0755, true);

  file_put_contents($online_dir . "$ccode/$season/prognoz/$TourCode/term", $timestamp);
  touch($online_dir . "$ccode/$season/prognoz/$TourCode/mail");
  touch($online_dir . "$ccode/$season/prognoz/$TourCode/adds");
}

$ccc = isset($_GET['cc']) ? $_GET['cc'] : '';
if (!$ccc || !isset($_SESSION['Coach_name']))
  die('access denied');

include('/' . strtolower($ccn[$ccc]) . '/settings.inc.php');
if ($_SESSION['Coach_name'] != $president
 && $_SESSION['Coach_name'] != $vice
 && !in_array($_SESSION['Coach_name'], $admin))
  die('access denied');

$notice = $cal = $gen = '';
$rat = isset($_POST['rat']) ? $_POST['rat'] : [];
$defaultadd = ' добавлять матчи здесь';
if (isset($_POST['add'])) $add = $_POST['add'];
elseif (isset($_GET['add'])) $add = $_GET['add'];
else $add = '';
if ($add == $defaultadd) $add = '';

if (isset($_GET['d'])) $d = $_GET['d'];
elseif (isset($_POST['d'])) $d = $_POST['d'];

if (isset($_POST['tour']) && $_POST['tour'] != ' код тура') {
  $TourCode = trim($_POST['tour']);
  if ($TourCode[0] == 'U')
    if ($TourCode[1] == 'E') $ccode = 'UEFA';
    else $ccode = 'UKR';
  else $ccode = mb_substr($TourCode, 0, 3);
  $season = '';
  $dir = scandir($online_dir . $ccode);
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
  $season = $subdir;
  $tmp = ltrim(str_replace($ccode, '', $TourCode), 'A');
  $tourn = ltrim($tmp, '0');

  if ($tmp[0] == 'C') {
    $ptplfname = $online_dir . "$ccode/$season/pc.tpl";
    if (is_file($online_dir . "$ccode/$season/calc"))
      $cal = trim(GetTourFromCalendar(str_replace('NEW', '', $TourCode), file_get_contents($online_dir . "$ccode/$season/calc")));
    if (is_file($online_dir . "$ccode/$season/genc"))
      $gen = file_get_contents($online_dir . "$ccode/$season/genc");
  }
  else {
    $ptplfname = $online_dir . "$ccode/$season/p.tpl";
    if (is_file($online_dir . "$ccode/$season/cal"))
      $cal = trim(GetTourFromCalendar(str_replace('NEW', '', $TourCode), file_get_contents($online_dir . "$ccode/$season/cal")));
    if (is_file($online_dir . "$ccode/$season/gen"))
      $gen = file_get_contents($online_dir . "$ccode/$season/gen");
  }

  $notice = " &raquo; добавлена программка $TourCode";
  $Srok = $_POST['srok'];
  $tpltbl = array();
  if ($cal && $tmp[0] != 'C') {
    $cfg = file($online_dir . "$ccode/$season/fp.cfg");
    $decoded = json_decode($cfg[4], true);
    foreach($decoded as $tournament) if ($tournament["type"] == "chm") break;
    $groups = $tournament["format"][0]["groups"];
    if ($groups > 1) {
      $atemp = explode("\n", $cal);
      $m = ($tournament["format"][0]["tours"][1] + 2) / 4;
      $virtmatch = array();
      $n = 0;
      $maxln = 0;
      foreach ($atemp as $line) if ($line = trim($line)) {
        if ($n < $m) {
          $virtmatch[0][] = $line;
          $maxln = max($maxln, mb_strlen($line));
        }
        else if ($n < $m * 2) {
          $virtmatch[1][] = $line;
          if ($groups > 2) $maxln = max($maxln, mb_strlen($line));
        }
        else $virtmatch[2][] = $line;
        $n++;
      }
      $maxln = max($maxln, 24);
      $cal = '';
      for ($i=0; $i<$m; $i++) {
        $cal .= $virtmatch[0][$i] . str_repeat(' ', $maxln - mb_strlen($virtmatch[0][$i]))
              . '    ' . $virtmatch[1][$i];
        if ($groups > 2)
          $cal .= str_repeat(' ', $maxln - mb_strlen($virtmatch[1][$i])) . '    ' . $virtmatch[2][$i];

        $cal .= "\n";
      }
    }
  }
//    $tpltbl['Calendar'] = explode("\n", iconv('CP1251', 'KOI8-R', $cal));
  if ($cal)
    $tpltbl['Calendar'] = explode("\n", $cal);
  else
    $tpltbl['Calendar'] = explode("\n", $_POST['cal']);

  if ($gen)
  {
    if (($fr = mb_strpos($gen, "ур $tourn")) || ($fr = mb_strpos($gen, $TourCode)))
    {
      $fr = mb_strpos($gen, "\n", $fr) + 1;
      if (($gen[$fr + 1] == '-') || ($gen[$fr + 1] == '='))
      $fr = mb_strpos($gen, "\n", $fr) + 1;
      if (($to = mb_strpos($gen, "Тур", $fr)) || ($to = mb_strpos($gen, $ccode, $fr)))
        $gen = trim(mb_substr($gen, $fr, $to - $fr));
      else
        $gen = trim(mb_substr($gen, $fr));
    }
    else $gen = '';
  }
//    $tpltbl['Generators'] = explode("\n", iconv('CP1251', 'KOI8-R', $gen));
  if ($gen)
    $tpltbl['Generators'] = explode("\n", $gen);
  else
    $tpltbl['Generators'] = explode("\n", $_POST['gen']);

  $m = 1;
  $marr = explode('&&', trim(str_replace('mzone-1[]=', '&', $_POST['mzone-1order']), '&'));
  $prog = array();
  $date1 = '13-00';
  $date2 = '00-00';
  foreach ($marr as $mline) {
    if ($mline[0] != 'h') {
      $ln = mb_strlen($mline);
      $prog[$m]['match'] = mb_substr($mline, 0, $mline - 8);
      $prog[$m]['cc'] = mb_substr($mline, $mline - 8, 3);
      $prog[$m]['date'] = mb_substr($mline, -5);
      $date1 = min($date1, $prog[$m]['date']);
      $date2 = max($date2, $prog[$m]['date']);
      $prog[$m]['date'] = mb_substr($prog[$m]['date'], -2).'.'.mb_substr($prog[$m]['date'], 0, 2);
      if (++$m > 15) break;
    }
  }
  $date1 = mb_substr($date1, -2).'.'.mb_substr($date1, 0, 2);
  $date2 = mb_substr($date2, -2).'.'.mb_substr($date2, 0, 2);
  if ($date1 == $date2) $Dates = "      $date1";
  else $Dates = "$date1-$date2";

  if (is_file($ptplfname))
  {
    $template = str_replace('', '', file_get_contents($ptplfname));
    // определение шаблона программки
    $fr = mb_strrpos($template, '[TourCode]') - 40;
    $fr = mb_strrpos($template, ' N') - 8;
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $fr = mb_strpos($template, "\n", $fr) + 1;
    $line11 = mb_substr($template, $fr, mb_strpos($template, "\n", $fr) - $fr);
    $lineln = mb_strlen($line);
    $i = 0;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $c1pos = $i;
    $c1val = mb_substr($line, $i, 1);
    $i++;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $i++;
    while (($i < $lineln) && (mb_substr($line, $i, 1) == ' ')) $i++;
    $c2pos = $i;
    $c2val = mb_substr($line, $i, 1);
    $i++;
    $c3pos = mb_strpos($line, $c2val, $i);
    $c3val = $c2val;
    $c2matchmaxln = $c3pos - $c2pos - 7; // макс. длина строки матча
    $i = $c3pos + 1;
    $c4pos = mb_strpos($line, $c2val, $i + 1);
    $c4val = $c2val;
    // [TourNum]
    $template = str_replace('[TourNum]', sprintf('%02s', $tourn), $template);
    // [TourCode]
    $template = str_replace('[TourCode]', sprintf('%-10s', $TourCode), $template);
    $template = str_replace("    \n", "\n", $template);
    // [__Dates__]
    $template = str_replace('[TourCode]', sprintf('%-10s', $TourCode), $template);
    $template = str_replace('[__Dates__]', $Dates, $template);

    // [Programme]
    $programme = array();
    $progsched = "0\n";
    foreach ($prog as $m => $mt) {
      if ($m == 11) $programme[] = $line11;
      $line = str_repeat(' ', $c1pos).$c1val.sprintf('%2s', $m).'.';
      $line = sprintf('%-'.$c2pos.'s', $line).$c2val.' ';
      $line .= sprintf('%-'.($c2matchmaxln + strlen($mt['match']) - mb_strlen($mt['match'])).'s', $mt['match']);
      $t = ' '.sprintf('%-4s', $mt['cc']);
      $line .= $t.$c3val.$mt['date'].$c4val;
      $programme[] = $line;
      $ta = explode(' - ', $mt['match']);
      $progsched .= $realteam[$ta[0]].','.$realteam[$ta[1]].",\n";
    }
    // [Srok]
    if (mb_strlen($Srok) < 6)
    {
      $Srok = sprintf('%6s', $Srok);
      $template = str_replace('[Srok]', sprintf('%6s', $Srok), $template);
    }
    else
    {
      $l = mb_strlen($Srok);
      $template = str_replace(sprintf('%'.$l.'s', '[Srok]'), $Srok, $template);
    }
    // [Calendar]
    $template = FillTemplate($template, 'Calendar', $tpltbl);
    // [Generators]
    $template = FillTemplate($template, 'Generators', $tpltbl);

    $out = '';
    foreach ($programme as $line) $out .= "$line\n";
    $programm = str_replace("[Programme]\n", $out, $template);
    if (isset($_POST['preview']))
      echo "<pre>$programm</pre>";
    else
    {
      if ($TourCode[4] == 'L')
        $cclen = 5;
      else
        $cclen = 3;

      $pfname = $online_dir . "$ccode/$season/publish/p".mb_strtolower(mb_substr(str_replace('NEW', '', $TourCode), $cclen));
      if (is_file($pfname))
        rename($pfname, $pfname.'.'.time());
      file_put_contents($pfname, $programm);
      file_put_contents($online_dir . "$ccode/$season/programms/$TourCode", $programm);
      // make records for scheduler
      $month = trim(mb_substr($Srok, mb_strpos($Srok, '.') + 1, 2));
      $year = date('Y', time());
      if (date('m', time()) > $month)
        $year++;

      if (mb_strpos($Srok, ' '))
        str_replace(' ', ".$year ", $Srok);
      else
        $Srok .= ".$year 23:59:59";

      $timestamp = strtotime($Srok);
      Schedule($timestamp - 345600, $ccode, $TourCode, 'resend', $pfname);
      Schedule($timestamp + 36000, $ccode, $TourCode, 'remind', $pfname);
      Schedule($timestamp + 43200, $ccode, $TourCode, 'monitor', $progsched);
      mkPrognozDir($timestamp, $ccode, $season, $TourCode);
      if ($ccode == 'UEFA')
      {
        mkPrognozDir($timestamp, $ccode, $season, str_replace('UEFA', 'CHAM', $TourCode));
        mkPrognozDir($timestamp, $ccode, $season, str_replace('UEFA', 'GOLD', $TourCode));
        mkPrognozDir($timestamp, $ccode, $season, str_replace('UEFA', 'CUPS', $TourCode));
      }
    }
  }
  else $notice = 'ошибка: нет шаблона программки!';
}
$teams = array();
$atemp = file($online_dir . 'ranking/rank');
foreach ($atemp as $row) if ($row = trim($row))
{
  $ar = explode(',', $row);
  $teams[$ar[1]][$ar[0]] = round($ar[2],1);
  $teams['UCL'][$ar[0]] = round($ar[2],1);
  $teams['UEL'][$ar[0]] = round($ar[2],1);
}
foreach ($ccrank as $cct => $ccarr) if (!isset($rat[$cct])) $rat[$cct] = 0;
if (!$rat[$ccc]) $rat[$ccc] = 300; if ($ccc == 'SCO') $rat[$ccc] = 100;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>Make FP Programm</title>
<script type="text/javascript">
//<![CDATA[
function addLoadEvent(func) {if ( typeof wpOnload!='function'){wpOnload=func;}else{ var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}}
//]]>
</script>
<style type="text/css">* html { overflow-x: hidden; }</style>
<script type='text/javascript' src='js/fat.js?ver=1.0-RC1_3660'></script>
<script type='text/javascript' src='js/jquery.js?ver=1.1.4'></script>
<script type='text/javascript' src='js/interface.js?ver=1.2'></script>
<link rel='stylesheet' href='css/matches.css?version=2.3' type='text/css' />
<script type="text/javascript">
// <![CDATA[
	var cols = ['mzone-1', 'mzone-2'];
	var matches = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40'];
		function initMatches() {
		jQuery(matches).each(function(o) {o='#matchprefix-'+o; jQuery(o).css('position','relative');} );
		}
	function resetDroppableHeights() {
		var max = 6;
		jQuery.map(cols, function(o) {
			var c = jQuery('#' + o + ' li').length;
			if ( c > max ) max = c;
		});
		var maxheight = 20 * max + 5;
		jQuery.map(cols, function(o) {
			height = 0 == jQuery('#' + o + ' li').length ? maxheight - jQuery('#' + o + 'placemat').height() : maxheight;
			jQuery('#' + o).height(height);
		});
	}
	function maxHeight(elm) {
		htmlheight = document.body.parentNode.clientHeight;
		bodyheight = document.body.clientHeight;
		var height = htmlheight > bodyheight ? htmlheight : bodyheight;
		jQuery(elm).height(height);
	}
	function getViewportDims() {
		var x,y;
		if (self.innerHeight) { // all except Explorer
			x = self.innerWidth;
			y = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			x = document.documentElement.clientWidth;
			y = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			x = document.body.clientWidth;
			y = document.body.clientHeight;
		}
		return new Array(x,y);
	}
	function dragChange(o) {
		var p = getViewportDims();
		var screenWidth = p[0];
		var screenHeight = p[1];
		var elWidth = parseInt( jQuery(o).css('width') );
		var elHeight = parseInt( jQuery(o).css('height') );
		var elLeft = parseInt( jQuery(o).css('left') );
		var elTop = parseInt( jQuery(o).css('top') );
		if ( screenWidth < ( parseInt(elLeft) + parseInt(elWidth) ) )
			jQuery(o).css('left', ( screenWidth - elWidth ) + 'px' );
		if ( screenHeight < ( parseInt(elTop) + parseInt(elHeight) ) )
			jQuery(o).css('top', ( screenHeight - elHeight ) + 'px' );
		if ( elLeft < 1 )
			jQuery(o).css('left', '1px');
		if ( elTop < 1 )
			jQuery(o).css('top', '1px');
	}
	function serializeAll() {
			var serial1 = jQuery.SortSerialize('mzone-1');
		jQuery('#mzone-1order').attr('value',serial1.hash.replace(/matchprefix-/g, ''));
		}
	function updateAll() {
		jQuery.map(cols, function(o) {
			if ( jQuery('#' + o + ' li').length )
				jQuery('#'+o+'placemat span.handle').hide();
			else
				jQuery('#'+o+'placemat span.handle').show();
		});
		resetDroppableHeights();
	}
	jQuery(document).ready( function() {
		updateAll();
		initMatches();
	});
// ]]>
</script>
</head>
<body>
<?php
//echo '<form id="selectfed" method=post>
$uadd = str_replace("\n", "%0A", $add);
if (!isset($d)) $d='';
echo '<form id="progedit" method="post" onsubmit="serializeAll();">
<a href="?cc=ENG'."&d=$d&add=$uadd".'"><img src="/images/england.gif" alt="England" border=0></a>
<input type=text size=2 name=rat[ENG] value="'.$rat['ENG'].'">
<a href="?cc=ESP'."&d=$d&add=$uadd".'"><img src="/images/spain.gif" alt="Spain" border=0></a>
<input type=text size=2 name=rat[ESP] value="'.$rat['ESP'].'">
<a href="?cc=ITA'."&d=$d&add=$uadd".'"><img src="/images/italy.gif" alt="Italy" border=0></a>
<input type=text size=2 name=rat[ITA] value="'.$rat['ITA'].'">
<a href="?cc=GER'."&d=$d&add=$uadd".'"><img src="/images/germany.gif" alt="Germany" border=0></a>
<input type=text size=2 name=rat[GER] value="'.$rat['GER'].'">
<a href="?cc=FRA'."&d=$d&add=$uadd".'"><img src="/images/france.gif" alt="France" border=0></a>
<input type=text size=2 name=rat[FRA] value="'.$rat['FRA'].'">
<a href="?cc=RUS'."&d=$d&add=$uadd".'"><img src="/images/russia.gif" alt="Russia" border=0></a>
<input type=text size=2 name=rat[RUS] value="'.$rat['RUS'].'">
<a href="?cc=UKR'."&d=$d&add=$uadd".'"><img src="/images/ukraine.gif" alt="Ukraine" border=0></a>
<input type=text size=2 name=rat[UKR] value="'.$rat['UKR'].'">
<a href="?cc=BLR'."&d=$d&add=$uadd".'"><img src="/images/belarus.gif" alt="Belarus" border=0></a>
<input type=text size=2 name=rat[BLR] value="'.$rat['BLR'].'">
<a href="?cc=NLD'."&d=$d&add=$uadd".'"><img src="/images/netherlands.gif" alt="Netherlands" border=0></a>
<input type=text size=2 name=rat[NLD] value="'.$rat['NLD'].'">
<a href="?cc=PRT'."&d=$d&add=$uadd".'"><img src="/images/portugal.gif" alt="Portugal" border=0></a>
<input type=text size=2 name=rat[PRT] value="'.$rat['PRT'].'">
<a href="?cc=SCO'."&d=$d&add=$uadd".'"><img src="/images/scotland.gif" alt="Scotland" border=0></a>
<input type=text size=2 name=rat[SCO] value="'.$rat['SCO'].'">
<input type=submit name="changek" value="изменить коэффициенты стран"> &nbsp;&nbsp;
<a href="draw.php?cc='.$ccc.'" target="_DRAW"> &gt;&gt; Скрипт жеребьевки кубкового тура</a>
'.$notice.'
<br />
';
//</form>
//<form id="progedit" method="post" onsubmit="serializeAll();">
?>

<div id="zones">
  <input type="hidden" id="mzone-1order" name="mzone-1order" value="" />
  <div class="dropzone">
    <div id="mzone-1placemat" class="placemat">
      <span class="handle">
<br />
Выберите, если нужно, федерацию, кликнув на флаг вверху.<br />
Затем выберите в таблице внизу интервал с матчами.<br />
На этом месте появится список автоматически отобранных матчей с	некоторым
избытком для замены непонравившихся. Если надо, можно объединить два
интервала игровых дней, нажав "+" после первого интервала.<br />
<br />
Разместите матчи в нужном порядке - первые 15 матчей войдут в программку.
На порядок матчей также можно влиять предварительным указанием
рейтингового бонуса для стран справа у флажка (бонус для своей страны по
умолчанию 300, для 1-х дивизионов бонус вдвое меньше, чем дпя премьер-лиг,
пока матчи 1-го дивизиона добавляются только для России).<br />
<br />
Укажите код тура, срок отправки прогнозов и, при необходимости, введите
списки матчей и генераторов. Если этого не сделать, программка и
генераторы тура будут взяты из файлов конфигурации сезона - это обычная
практика для построения программки для туров чемпионата.<br />
<br />
При необходимости можно добавить матчи, отсутствующие в выборе. Для этого
в поле ввода справа внизу, укажите описание матчей по одному в строке в
формате:<br />
<b>Хозяева - Гости, месяц-день, код страны, код турнира</b> (коды не обязательны)<br />
Пример: "Паровоз - Дирижабль, 08-29, RUS, CUP"<br />
Добавленным матчам автоматически устанавливается рейтинг 400 для их
попадания в основную сетку программки.<br />
<br />
      </span>
    </div>
    <ul id="mzone-1">
<?php
$matches = array();
$ddd = date('m-d', time() + 864000);
$time = strtotime("tue +0 week");
$time = strtotime("tue +-1 week");
//for ($w=-1; $w<=6; $w++)
for ($w=-1; $w<=6; $w++)
{
  $time = $time + 604800 + 3600;
//  $time = $time - 401600;
  $week = date('W', $time);
  $year = date('Y', $time);
//  $year = date('Y', $time + 432000);
  $day1 = date('m-d', $time);
  $day2 = date('m-d', $time + 172800);
  $day3 = date('m-d', $time + 259200);
  $day4 = date('m-d', $time + 518400);
  foreach ($ccs as $cc => $country)
  foreach ($suffix as $tournament)
  if (is_file($online_dir . "fixtures/$year/$week/$cc$tournament"))
  {
    $ac = file($online_dir . "fixtures/$year/$week/$cc$tournament");
    foreach ($ac as $line) if (trim($line))
    {
      $am = explode(',', trim($line));
/// translate teams here
      if ($cc == 'RUS' || $cc == 'UKR' || $cc == 'BLR')
      {
        $am[0] = $translate[$am[0]];
        $am[1] = $translate[$am[1]];
      }

      if ($cc == 'INT')
      {
        if ($am[4] == 'Friendly') {
          $am[4] = 'FR';
        }
        else {
          $to = '';
          if (strpos($am[4], ' W')) $to .= 'W';
          if (strpos($am[4], 'ontinental')) $to .= 'C';
          if (strpos($am[4], ' C')) $to .= 'C';
          if (strpos($am[4], ' F')) $to .= 'F';
          if (strpos($am[4], ' Q')) $to .= 'Q';
          $am[4] = $to;
        }
      }

      if ($am[2] < $day1 && $am[2] > '01-00') {
        if (isset($day3p))
          $matches[$day3p][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
      }
      elseif ($am[2] < $day3) {
        $matches[$day1][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
      }
      else {
        $matches[$day3][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
      }
    }
  }
  $day3p = $day3;
}

if ($d)
{
  $ddd = $d;
  if (strlen($ddd) > 5 && $ddd[5] == ' ')
  {
    $dde = substr($ddd, 6);
    $ddd = substr($ddd, 0, 5);
  }
  else $dde = '';

  $mrated = array();
  $selected = array();
  foreach ($matches[$ddd] as $cc => $liga)
    foreach ($liga as $match => $mdata)
    {
      $rating = 0;
      $tournament = $mdata[4];
      if ($tournament == 'D1') $rating += $rat[$cc] / 2;
      else $rating += $rat[$cc];
      if (($ccc == 'BLR') && ($cc == 'RUS')) $rating += $rat[$cc] / 2;
      if ($cc == 'INT')
      {
        $rating += $teams[$cc][$mdata[0]];
        $rating += $teams[$cc][$mdata[1]];
	$rating /= 2;
        $rating += 100;
	if (($ccn[$ccc] != $mdata[0]) && ($ccn[$ccc] != $mdata[1]))
          $rating -= 100;
	if (!isset($teams[$cc][$mdata[0]])) {
	  echo ' no rank: '.$mdata[0].'<br>';
	  $ratingh = 20;
	}
	else $ratingh = $teams[$cc][$mdata[0]];
	if (!isset($teams[$cc][$mdata[1]])) {
	  echo ' no rank: '.$mdata[1].'<br>';
	  $ratinga = 20;
	}
	else $ratinga = $teams[$cc][$mdata[1]];
        $rating -= abs(10 + $ratingh - $ratinga);
      }
      else
      {
        $cc1 = $cc;
        if ($tournament == 'D1' || $tournament == '3R' || $tournament == '2R')
        {
          if (isset($teams[$cc][$realteam[$mdata[0]]]))
            $rating += $teams[$cc][$realteam[$mdata[0]]];
          else
            $teams[$cc][$realteam[$mdata[0]]] = 0;

          if (isset($teams[$cc][$realteam[$mdata[1]]]))
            $rating += $teams[$cc][$realteam[$mdata[1]]];
          else
            $teams[$cc][$realteam[$mdata[1]]] = 0;

        }
        else
        {
          if (!isset($teams[$cc1][$realteam[$mdata[0]]]))
          {
            echo ' no rank: '.$cc.' '.$mdata[0].'<br>';
            $teams[$cc1][$realteam[$mdata[0]]] = 0;
          }
          else
            $rating += $teams[$cc1][$realteam[$mdata[0]]];

          if (!isset($teams[$cc1][$realteam[$mdata[1]]]))
          {
            echo ' no rank: '.$cc.' '.$mdata[1].'<br>';
            $teams[$cc1][$realteam[$mdata[1]]] = 0;
          }
          else
            $rating += $teams[$cc1][$realteam[$mdata[1]]];

        }
        $rating -= abs($ccrank[$cc][4] + $teams[$cc1][$realteam[$mdata[0]]] - $teams[$cc1][$realteam[$mdata[1]]]);
      }
      $rating = max(0, $rating);
      $matches[$ddd][$cc][$match][5] = $rating;
      $mrated[] = $rating;
      $selected[$cc][$match] = $matches[$ddd][$cc][$match];
    }

  if ($dde)
  foreach ($matches[$dde] as $cc => $liga)
    foreach ($liga as $match => $mdata)
    {
      $rating = 0;
      $tournament = $mdata[4];
      if ($tournament == 'D1') $rating += $rat[$cc] / 2;
      else $rating += $rat[$cc];
      if (($ccc == 'BLR') && ($cc == 'RUS')) $rating += $rat[$cc] / 2;
      if ($cc == 'INT')
      {
        $rating += $teams[$cc][$mdata[0]];
        $rating += $teams[$cc][$mdata[1]];
	$rating /= 2;
        $rating += 100;
	if (($ccn[$ccc] != $mdata[0]) && ($ccn[$ccc] != $mdata[1]))
          $rating -= 100;
	if (!isset($teams[$cc][$mdata[0]])) {
	  echo ' no rank: '.$mdata[0].'<br>';
	  $ratingh = 20;
	}
	else $ratingh = $teams[$cc][$mdata[0]];
	if (!isset($teams[$cc][$mdata[1]])) {
	  echo ' no rank: '.$mdata[1].'<br>';
	  $ratinga = 20;
	}
	else $ratinga = $teams[$cc][$mdata[1]];
        $rating -= abs(10 + $ratingh - $ratinga);
      }
      else
      {
	$cc1 = $cc;
	if ($tournament == 'D1' || $tournament == '2R' || $tournament == '3R')
	{
          $rating += $teams[$cc][$realteam[$mdata[0]]];
          $rating += $teams[$cc][$realteam[$mdata[1]]];
	}
	else
	{
	  if (!$teams[$cc1][$realteam[$mdata[0]]]) echo ' no rank: '.$cc.' '.$mdata[0].'<br>';
          else $rating += $teams[$cc1][$realteam[$mdata[0]]];
	  if (!$teams[$cc1][$realteam[$mdata[1]]]) echo ' no rank: '.$cc.' '.$mdata[1].'<br>';
          else $rating += $teams[$cc1][$realteam[$mdata[1]]];
        }
        $rating -= abs($ccrank[$cc][4] + $teams[$cc1][$realteam[$mdata[0]]] - $teams[$cc1][$realteam[$mdata[1]]]);
      }
      $rating = max(0, $rating);
      $matches[$dde][$cc][$match][5] = $rating;
      $mrated[] = $rating;
      $selected[$cc][$match] = $matches[$dde][$cc][$match];
    }

  if ($add) {
    $matchesadd = explode("\n", $add);
    foreach($matchesadd as $matchadd) if (trim($matchadd)) {
      $mdata = explode(',', $matchadd);
      $match = trim($mdata[0]);
      $mpair = explode(' - ', $match);
      $cc = trim($mdata[2]);
      $rating = 400;
      $matches[$ddd][$cc][$match][0] = trim($mpair[0]);
      $matches[$ddd][$cc][$match][1] = trim($mpair[1]);
      $matches[$ddd][$cc][$match][2] = trim($mdata[1]);
      $matches[$ddd][$cc][$match][3] = trim($mdata[2]);
      $matches[$ddd][$cc][$match][4] = trim($mdata[3]);
      $matches[$ddd][$cc][$match][5] = $rating;
      $mrated[] = $rating;
      $selected[$cc][$match] = $matches[$ddd][$cc][$match];
    }
  }

  rsort($mrated);
  $r10 = $mrated[9];
  $r15 = $mrated[14];
  if (sizeof($mrated) > 20)
    $r20 = max(25, $mrated[69]); // $r20 = max(30, $mrated[39])
  else
    $r20 = 0;

  foreach ($selected as $cc => $liga) if ($cc == $ccc)
    foreach ($liga as $match => $mdata)
      if ($mdata[5] >= $r10)
	echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle">'.$match.'<div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';

  foreach ($selected as $cc => $liga) if ($cc != $ccc)
    foreach ($liga as $match => $mdata)
      if ($mdata[5] >= $r10)
	echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle">'.$match.'<div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';
  echo '<li class="module" id="hline-1"><span class="handle"><hr></span></li>';

  foreach ($selected as $cc => $liga) if ($cc != $ccc)
    foreach ($liga as $match => $mdata)
      if (($mdata[5] < $r10) && ($mdata[5] >= $r15))
	echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle">'.$match.'<div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';

  foreach ($selected as $cc => $liga) if ($cc == $ccc)
    foreach ($liga as $match => $mdata)
      if (($mdata[5] < $r10) && ($mdata[5] >= $r15))
	echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle">'.$match.'<div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';
  echo '<li class="module" id="hline-2"><span class="handle"><hr></span></li>';

  foreach ($selected as $cc => $liga)
    foreach ($liga as $match => $mdata)
      if (($mdata[5] < $r15) && ($mdata[5] >= $r20))
	echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle">'.$match.'<div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';
}
?>    </ul>
  </div>
  <div class="dropzone">
<input type="text" id="tour" onfocus="this.value=''; this.onfocus=null;" name="tour" value=" код тура" size="8" />
<input type="text" id="srok" name="srok" value="<?php echo date('d.m', mktime(0, 0, 0, substr($ddd,0,2), substr($ddd,3), $year) - 86400); ?>" size="26" />
&laquo; срок отправки прогнозов<br />
<textarea id="cal" onfocus="this.value=''; this.onfocus=null;" name="cal" rows="8" cols="64">
 календарь (для 2х лиг - в 2 колонки)</textarea><br />
<textarea id="gen" onfocus="this.value=''; this.onfocus=null;" name="gen" rows="8" cols="64">
 генераторы (для 2х лиг - в 2 колонки)</textarea><br />
<input type="submit" name="saveprog" value="записать программку" />
<input type="submit" name="preview" value="предварительный просмотр программки" /><br />
<br />
<textarea id="add" <?php if(!$add) echo "onfocus=\"this.value=''; this.onfocus=null;\" ";?>name="add" rows="8" cols="64">
<?php if($add) echo $add; else echo $defaultadd; ?>
</textarea><br />
<input type="submit" name"addmatch" value="добавить матчи" /><br />
</div>

</div>
<script type="text/javascript">
// <![CDATA[
    jQuery(document).ready(function(){
	jQuery('ul#mzone-1').Sortable({
	accept: 'module', activeclass: 'activeDraggable', opacity: 0.8, revert: true, onStop: updateAll
	});
    });
// ]]>
</script>
<input type="hidden" name="d" value="<?php echo $d;?>">
</form>
<?php echo '
<br>
<table><tr><th colspan=3>игровые дни</th><th>всего</th>';
foreach ($ccs as $cc => $country)
  echo "<th>$cc</th>";
echo '</tr>';
$time = strtotime("tue +0 week");
$time = strtotime("tue -1 week");
//for ($w=-1; $w<=6; $w++)
for ($w=-1; $w<=6; $w++)
{
  $time = $time + 604800 + 3600;
//  $time = $time - 401600;
  $week = date('W', $time);
  $year = date('Y', $time);
//  $year = date('Y', $time + 432000);
  $day1 = date('m-d', $time);
  $day2 = date('m-d', $time + 172800);
  $day3 = date('m-d', $time + 259200);
  $day4 = date('m-d', $time + 518400);
  $day5 = date('m-d', $time + 604800);
  $s1 = ''; $s2 = ''; $t1 = 0; $t2 = 0;
  foreach ($ccs as $cc => $country)
  {
    if (isset($matches[$day1][$cc])) $m1 = sizeof($matches[$day1][$cc]); else $m1 = 0;
    if (isset($matches[$day3][$cc])) $m3 = sizeof($matches[$day3][$cc]); else $m3 = 0;
    if ($m1 && ($cc == $ccc)) $bgc = ' bgcolor=pink'; else $bgc = '';
    $s1 .= "<td align=center$bgc>$m1</td>";
    $t1 += $m1;
    if ($m3 && ($cc == $ccc)) $bgc = ' bgcolor=pink'; else $bgc = '';
    $s2 .= "<td align=center$bgc>$m3</td>";
    $t2 += $m3;
  }
  $uadd = str_replace("\n", "%0A", $add);
  if ($t1 > 0) echo "
<tr>
<td align=right><a href='?cc=$ccc&d=$day1&add=$uadd'>вт $day1</a> ...</td>
<td align=right><a href='?cc=$ccc&d=$day1&add=$uadd'>чт $day2</a></td>
<td align=right><a href='?cc=$ccc&d=$day1+$day3&add=$uadd'> + </a></td>
<td align=center>$t1</td>$s1</tr>";
  if ($t2 > 0) echo "
<tr>
<td align=right><a href='?cc=$ccc&d=$day3&add=$uadd'>пт $day3</a> ...</td>
<td align=right><a href='?cc=$ccc&d=$day3&add=$uadd'>пн $day4</a></td>
<td align=right><a href='?cc=$ccc&d=$day3+$day5&add=$uadd'> + </a></td>
<td align=center>$t2</td>$s2</tr>";
}
?>
</table>
</body>
</html>
