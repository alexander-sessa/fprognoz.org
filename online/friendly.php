<?php
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
session_save_path('/var/lib/php/sessions');
session_start();
$online_dir = '/home/fp/data/online/';
include 'realteam.inc.php';
include 'cc.inc.php';
include 'translate.inc.php';

function send_email($from, $name, $email, $subj, $body) {
  $params = ['token' => 'FPrognoz.Org', 'from' => $from, 'name' => $name, 'email' => $email, 'subj' => $subj, 'body' => $body];
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
      'content' => http_build_query($params),
    ),
  ));
  return file_get_contents('http://forum.fprognoz.org/mail-proxy.php', false, $context);
}

$notice = '';
$access = file('/home/fp/data/auth/.access');
$cmd_db = array();
foreach($access as $access_str)
{
  $ta = explode(';', $access_str);
  $cmd_db[$ta[0].'@'.$ta[1]]['ccn'] = $ta[1];
  $cmd_db[$ta[0].'@'.$ta[1]]['cmd'] = $ta[2];
  $cmd_db[$ta[0].'@'.$ta[1]]['usr'] = $ta[3];
  $cmd_db[$ta[0].'@'.$ta[1]]['eml'] = $ta[4];
  $cmd_db[$ta[0].'@'.$ta[1]]['rol'] = $ta[6];
}

$cal = '';
if (isset($_POST['rat']))
  $rat = $_POST['rat'];
else
{
  $rat = array();
  if (isset($_GET['cc']))
    $rat[$_GET['cc']] = 300;

}
if (isset($_GET['d'])) $d = $_GET['d'];
elseif (isset($_POST['d'])) $d = $_POST['d'];

if (isset($_POST['saveprog']))
{
  $tourn = file_get_contents($online_dir . 'FCL/mserial');
  file_put_contents($online_dir . 'FCL/mserial', ++$tourn);
  $TourCode = 'FCL'.$tourn;
  $season = '';
  $dir = scandir($online_dir . 'FCL');
  foreach ($dir as $subdir)
    if ($subdir[0] == '2')
      $season = $subdir;

  $place = '';
  if ($_POST['place'] == 'neut')
    $place .= 'Матч на нейтральном поле! ';

  if ($_POST['draw'] == 'pena')
    $place .= 'При ничьей играется доп.время и пенальти!';

  $mycmd = $_POST['mycmd'];
  if (!strpos($mycmd, '@'))
    $mycmd .= '@FCL';

  $enemy = $_POST['enemy'];
  if (!strpos($enemy, '@'))
    $enemy .= '@FCL';

  if ($_POST['place'] == 'away')
    $cal = $cmd_db[$enemy]['cmd'].' - '.$cmd_db[$mycmd]['cmd'];
  else
    $cal = $cmd_db[$mycmd]['cmd'].' - '.$cmd_db[$enemy]['cmd'];

  $Srok = $_POST['srok'];
  $m = 1;
  $marr = explode('&', str_replace('mzone-1[]=', '', $_POST['mzone-1order']));
  $prog = array();
  $date1 = '13-00';
  $date2 = '00-00';
  foreach ($marr as $mline)
  {
    if ($mline[0] != 'h')
    {
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
  $month = mb_substr($date1, 0, 2);
  $date1 = mb_substr($date1, -2).'.'.mb_substr($date1, 0, 2);
  $date2 = mb_substr($date2, -2).'.'.mb_substr($date2, 0, 2);
  if ($date1 == $date2) $Dates = "      $date1";
  else $Dates = "$date1-$date2";

  $template = str_replace('', '', file_get_contents($online_dir . 'FCL/p.tpl'));
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
  $template = str_replace('[TourNum]', $tourn, $template);
  // [TourCode]
  $template = str_replace('[TourCode]', sprintf('%-10s', $TourCode), $template);
  $template = str_replace("    \n", "\n", $template);
  // [__Dates__]
  $template = str_replace('[TourCode]', sprintf('%-10s', $TourCode), $template);
  $template = str_replace('[__Dates__]', $Dates, $template);
  // [Programme]
  $programme = array();
  foreach ($prog as $m => $mt)
  {
    if ($m == 11) $programme[] = $line11;
    $line = str_repeat(' ', $c1pos).$c1val.sprintf('%2s', $m).'.';
    $line = sprintf('%-'.$c2pos.'s', $line).$c2val.' ';
    $line .= sprintf('%-'.($c2matchmaxln + strlen($mt['match']) - mb_strlen($mt['match'])).'s', $mt['match']);
    $t = ' '.sprintf('%-4s', $mt['cc']);
    $line .= $t.$c3val.$mt['date'].$c4val;
    $programme[] = $line;
    $ta = explode(' - ', $mt['match']);
  }
  // [Srok]
  $Srok = $date1; // for friendly only
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
  $template = str_replace('[Calendar]', $cal, $template);
  $template = str_replace('[Place]', $place, $template);
  $out = '';
  foreach ($programme as $line)
    $out .= "$line\n";

  $programm = str_replace("[Programme]\n", $out, $template);
  if ($_POST['place'] == 'neut')
  {
    $programm = str_replace('(1)', '', $programm);
    $programm = str_replace('<', '', $programm);
  }
  if ($_POST['draw'] == 'stop')
    $programm = str_replace('  pen. 8,9,11,14,3,7', '', $programm);

  file_put_contents($online_dir . "FCL/$season/programms/$TourCode", $programm);
  mkdir ($online_dir . "FCL/$season/prognoz/$TourCode", 0755);
  touch($online_dir . "FCL/$season/prognoz/$TourCode/mail");
  if (!is_dir($online_dir . "FCL/$season/$month"))
    mkdir($online_dir . "FCL/$season/$month", 0755);

  symlink("../programms/$TourCode", $online_dir . "FCL/$season/$month/".$date1.'_'.$cal.'_'.$tourn);
  file_put_contents($online_dir . "FCL/$season/prognoz/$TourCode/link", $online_dir . "FCL/$season/$month/".$date1.'_'.$cal.'_'.$tourn);
  $myprogramm = str_replace('FCL00', substr($mycmd, 0, strpos($mycmd, '@')),$programm);
  if ($_POST['place'] == 'home')
    $myprogramm = str_replace('<', '', $myprogramm);
  elseif ($_POST['place'] == 'away')
    $myprogramm = str_replace('(1)', '', $myprogramm);

  $res = send_email("FP Clubs Friendly <fcl@fprognoz.org>", $cmd_db[$mycmd]['usr'], $cmd_db[$mycmd]['eml'],
    "ФП. Товарищеский матч $cal ($date1)", $myprogramm);
  $myprogramm = str_replace('FCL00', substr($enemy, 0, strpos($enemy, '@')),$programm);
  if ($_POST['place'] == 'away')
    $myprogramm = str_replace('<', '', $myprogramm);
  elseif ($_POST['place'] == 'home')
    $myprogramm = str_replace('(1)', '', $myprogramm);

  $res .= send_email("FP Clubs Friendly <fcl@fprognoz.org>", $cmd_db[$enemy]['usr'], $cmd_db[$enemy]['eml'],
    "ФП. Товарищеский матч $cal ($date1)", $myprogramm);
  $notice = '<p>Программка добавлена в календарь и разослана участникам матча:</p>' . $res;
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
foreach ($ccrank as $cct => $ccarr)
  if (!isset($rat[$cct]))
    $rat[$cct] = 0;

$rat['UEL'] = $rat['UCL'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<title>Make FP Friendly Programm</title>
<script type="text/javascript">
//<![CDATA[
function addLoadEvent(func) {if ( typeof wpOnload!='function'){wpOnload=func;}else{ var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}}
//]]>
</script>
<style type="text/css">* html { overflow-x: hidden; }</style>
<script type='text/javascript' src='js/fat.js?ver=1.0-RC1_3660'></script>
<script type='text/javascript' src='js/jquery.js?ver=1.1.4'></script>
<script type='text/javascript' src='js/interface.js?ver=1.2'></script>
<link rel='stylesheet' href='css/friendly.css?version=2.3' type='text/css' />
<script type="text/javascript">
// <![CDATA[
	var cols = ['mzone-1', 'mzone-2'];
	var matches = ['1','2','3','4','5','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40'];
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
echo $notice;
if (!isset($d))
  $d='';

echo '<form id="progedit" method="post" action="" onsubmit="serializeAll();">
';
?>
<div id="zones">
  <input type="hidden" id="mzone-1order" name="mzone-1order" value="" />
  <div class="dropzone">
    <ul id="mzone-1">
<?php
$matches = array();
$ddd = date('m-d', time() + 864000);
for ($w=-1; $w<=0; $w++)
{
  $time = strtotime("tue +$w week");
  $week = date('W', $time);
  $year = date('Y', $time);
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
     if (strtotime($year.'-'.$am[2].' '.$am[3]) - time() > 7200)
     {
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

      if ($am[2] < $day1)
      {
        if (isset($day3p))
          $matches[$day3p][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
      }
      elseif ($am[2] < $day3)
        $matches[$day1][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
      else
        $matches[$day3][$cc][$am[0].' - '.$am[1]] = array(trim($am[0]),trim($am[1]),$am[2],$am[3],$am[4]);
     }
    }
  }
  $day3p = $day3;
}
if ($d)
{
  $ddd = $d;
  if (strlen($ddd) > 4 && $ddd[5] == ' ')
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
      if ($cc == 'INT')
      {
        $rating += $teams[$cc][$mdata[0]];
        $rating += $teams[$cc][$mdata[1]];
	$rating /= 2;
	if (!isset($teams[$cc][$mdata[0]])) {
//	  echo ' no rank: '.$mdata[0].'<br>';
	  $ratingh = 20;
	}
	else $ratingh = $teams[$cc][$mdata[0]];
	if (!isset($teams[$cc][$mdata[1]])) {
//	  echo ' no rank: '.$mdata[1].'<br>';
	  $ratinga = 20;
	}
	else $ratinga = $teams[$cc][$mdata[1]];
        $rating -= abs(10 + $ratingh - $ratinga);
      }
      else
      {
	$cc1 = $cc;
	if ($tournament == 'D1' || $tournament == '3QR' || $tournament == '2QR')
	{
          $rating += $teams[$cc][$realteam[$mdata[0]]];
          $rating += $teams[$cc][$realteam[$mdata[1]]];
	}
	else
	{
	  if (!isset($teams[$cc1][$realteam[$mdata[0]]])) ; //echo ' no rank: '.$cc.' '.$mdata[0].'<br>';
          else $rating += $teams[$cc1][$realteam[$mdata[0]]];
	  if (!isset($teams[$cc1][$realteam[$mdata[1]]])) ; //echo ' no rank: '.$cc.' '.$mdata[1].'<br>';
          else $rating += $teams[$cc1][$realteam[$mdata[1]]];
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
      if ($cc == 'INT')
      {
        $rating += $teams[$cc][$mdata[0]];
        $rating += $teams[$cc][$mdata[1]];
	$rating /= 2;
	if (!isset($teams[$cc][$mdata[0]])) {
//	  echo ' no rank: '.$mdata[0].'<br>';
	  $ratingh = 20;
	}
	else $ratingh = $teams[$cc][$mdata[0]];
	if (!isset($teams[$cc][$mdata[1]])) {
//	  echo ' no rank: '.$mdata[1].'<br>';
	  $ratinga = 20;
	}
	else $ratinga = $teams[$cc][$mdata[1]];
        $rating -= abs(10 + $ratingh - $ratinga);
      }
      else
      {
	$cc1 = $cc;
	if ($tournament == 'D1' | $tournament == '3QR' || $tournament == '2QR')
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

  rsort($mrated);
  $r10 = $mrated[9];
  $r15 = $mrated[14];
  if (sizeof($mrated) > 20)
    $r20 = max(30, $mrated[39]);
  else
    $r20 = 0;

  foreach ($selected as $cc => $liga)
    foreach ($liga as $match => $mdata) if ($mdata[5] >= $r10)
      echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle"><div class="match">&nbsp;'.$match.'</div><div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';
  echo '<li class="module" id="hline-1"><span class="handle"><hr></span></li>';
  foreach ($selected as $cc => $liga)
    foreach ($liga as $match => $mdata) if (($mdata[5] < $r10) && ($mdata[5] >= $r15))
      echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle"><div class="match">&nbsp;'.$match.'</div><div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';

  echo '<li class="module" id="hline-2"><span class="handle"><hr></span></li>';
  foreach ($selected as $cc => $liga)
    foreach ($liga as $match => $mdata) if (($mdata[5] < $r15) && ($mdata[5] >= $r20))
      echo '
<li class="module" id="matchprefix-'.$match.$cc.$mdata[2].'"><span class="handle"><div class="match">&nbsp;'.$match.'</div><div class="ccode">&nbsp;'.$cc.'&nbsp;'.$mdata[4].'&nbsp;</div><div class="mdate">'.$mdata[2].'</div><div class="mrate">&nbsp;'.$mdata[5].'</div><br /></span></li>';

}
else
  echo '<li>
1. Выберите справа в таблице "<b>выбор дат матча</b>" интервал игровых дней с матчами.
Неделя разбита на два интервала: "вторник - четверг" и "пятница - понедельник".
Если нужно, можно объединить смежные интервалы игровых дней, нажав "+" после первого
интервала.<br />
<br />
2. На этом месте появится список автоматически отобранных матчей с некоторым
избытком для замены не понравившихся. Для отбора используется собственный рейтинг
команд, на основании которого определяются коэффициенты интересности матчей (они
показываются уменьшенным шрифтом в последней колонке списка).<br />
<br />
3. На порядок показа и выбор матчей также можно влиять указанием рейтингового
коэффициента для стран и еврокубков справа у флагов ("<b>управление сортировкой</b>").
Коэффициент 300 уверенно поднимет матчи выбранной страны в первую десятку,
даже если изначально они не показывались в отобранных матчах.<br />
Чтобы быстро добавить 300 к коэффициенту страны, кликните на её флаг.<br />
<br />
4. Программка товарищеского матча состоит из 15 реальных матчей: 10 - в основной
части программки и 5 - в дополнительной. Основная часть используется для определения
счета основного времени, а дополнительная - для замены несостоявшихся матчей, игры
дополнительного времени, а также при пробитии послематчевых 11-метровых ударов.<br />
Части программки в списке матчей для удобства отделяются пустой полоской.<br />
<br />
5. Путем перетаскивания полосок с матчами разместите матчи в нужном порядке - первые
15 войдут в программку. Расположение пустых полосок при этом не имеет значения -
они не учитываются при подсчёте.<br />
<br />
6. Дальше надо выбрать свою команду (если их несколько), команду соперника и указать
место проведения товарищеского матча. Команда, игpающая  дома, имеет фоpy в виде того,
что может выставить на один из основных матчей дополнительный ваpиант пpогноза.<br />
<br />
7. Вы также имеете возможность указать, продолжать ли матч после ничьей в основное
время.<br />
<br />
8. Если всё готово, можно записать программку матча. Вы и ваш соперник получите
уведомления по EMail со ссылкой на страницу матча. Эта ссылка также появится в
календаре товарищеских матчей.<br />
<br />
</li>';
  echo '    </ul>
  </div>
  <div class="dropzone">
  <center>
    <b>выбор дат матча:</b><br />
    <br />
    <table><tr><td colspan="2" align="center">игровые дни</td><td>матчи</td>';
echo '</tr>';
for ($w=-1; $w<=0; $w++)
{
  $time = strtotime("tue +$w week");
  $week = date('W', $time);
  $year = date('Y', $time);
  $day1 = date('m-d', $time);
  $day2 = date('m-d', $time + 172800);
  $day3 = date('m-d', $time + 259200);
  $day4 = date('m-d', $time + 518400);
  $day5 = date('m-d', $time + 604800);
  $t1 = 0; $t2 = 0;
  foreach ($ccs as $cc => $country)
  {
    if (isset($matches[$day1][$cc])) $m1 = sizeof($matches[$day1][$cc]); else $m1 = 0;
    if (isset($matches[$day3][$cc])) $m3 = sizeof($matches[$day3][$cc]); else $m3 = 0;
    $t1 += $m1;
    $t2 += $m3;
  }
  if ($t1 > 0)
  {
    echo '
<tr>
<td align="right"><a href="?d='.$day1.'">вт '.$day1.' ... чт '.$day2.'</a></td>
<td align="right"><a href="?d='.$day1.'+'.$day3.'"> <b>+</b> </a></td>
<td align="center">'.$t1.'</td></tr>';
  }
  if ($t2 > 0)
  {
    echo '
<tr>
<td align="right"><a href="?d='.$day3.'">пт '.$day3.' ... пн '.$day4.'</a></td>
<td align="right"><a href="?d='.$day3.'+'.$day5.'"> <b>+</b> </a></td>
<td align="center">'.$t2.'</td></tr>';
  }
}
echo '    </table>
<input type="hidden" id="srok" name="srok" value="'.date('d.m', mktime(0, 0, 0, substr($ddd,0,2), substr($ddd,3), $year)).'" />
<br />
<br />
<br />
<br />
<b>управление сортировкой:</b><br />
<br />
<a href="?cc=UCL&amp;d='.$d.'"><img src="/images/uefa.gif" alt="EuroCups" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[UCL]" value="'.$rat['UCL'].'" />
<a href="?cc=ENG&amp;d='.$d.'"><img src="/images/england.gif" alt="England" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[ENG]" value="'.$rat['ENG'].'" />
<a href="?cc=ESP&amp;d='.$d.'"><img src="/images/spain.gif" alt="Spain" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[ESP]" value="'.$rat['ESP'].'" />
<br />
<a href="?cc=GER&amp;d='.$d.'"><img src="/images/germany.gif" alt="Germany" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[GER]" value="'.$rat['GER'].'" />
<a href="?cc=ITA&amp;d='.$d.'"><img src="/images/italy.gif" alt="Italy" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[ITA]" value="'.$rat['ITA'].'" />
<a href="?cc=FRA&amp;d='.$d.'"><img src="/images/france.gif" alt="France" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[FRA]" value="'.$rat['FRA'].'" />
<br />
<a href="?cc=RUS&amp;d='.$d.'"><img src="/images/russia.gif" alt="Russia" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[RUS]" value="'.$rat['RUS'].'" />
<a href="?cc=UKR&amp;d='.$d.'"><img src="/images/ukraine.gif" alt="Ukraine" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[UKR]" value="'.$rat['UKR'].'" />
<a href="?cc=BLR&amp;d='.$d.'"><img src="/images/belarus.gif" alt="Belarus" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[BLR]" value="'.$rat['BLR'].'" />
<br />
<a href="?cc=PRT&amp;d='.$d.'"><img src="/images/portugal.gif" alt="Portugal" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[PRT]" value="'.$rat['PRT'].'" />
<a href="?cc=NLD&amp;d='.$d.'"><img src="/images/netherlands.gif" alt="Netherlands" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[NLD]" value="'.$rat['NLD'].'" />
<a href="?cc=SCO&amp;d='.$d.'"><img src="/images/scotland.gif" alt="Scotland" border="0" /></a>
<input type="text" style="width: 28px;" name="rat[SCO]" value="'.$rat['SCO'].'" />
<br />
<input type="submit" name="changek" value="изменить коэффициенты" />
<br />
<br />
<br />
<br />
<br />
';
$year = date('Y', time());
$codes = file($online_dir . "FCL/$year/codes.tsv");
$registered = false;
$out = '';
foreach ($codes as $player)
{
  $aplayer = explode('	', trim($player));
  if (isset($_SESSION['Coach_name']) && trim($aplayer[2]) == $_SESSION['Coach_name'])
  {
    $registered = true;
    $out .= '<option value="'.$aplayer[0].'">'.$aplayer[1].'</option>
';
  }
}
if ($registered)
{
  echo '<b>ваша команда:</b><br />
<select name="mycmd" style="width: 208px;">
'.$out.'</select>
<br />
<br />
<b>выбор соперника:</b><br />
<select name="enemy" style="width: 208px;">
';
  foreach ($codes as $player)
  {
    $aplayer = explode('	', trim($player));
    if (isset($_SESSION['Coach_name']) && trim($aplayer[2]) && trim($aplayer[2]) != $_SESSION['Coach_name'])
    {
      echo '<option value="'.$aplayer[0].'">'.$aplayer[1].','.$aplayer[2].'</option>
';
    }
  }
  echo '</select>
<br />
<br />
<b>место проведения матча:</b><br />
<select name="place" style="width: 208px;">
<option value="neut">Игра на нейтральном поле</option>
<option value="home">Вы проводите матч дома</option>
<option value="away">Игра на поле соперника</option>
</select>
<br />
<br />
<b>при ничьей в осн. время:</b><br />
<select name="draw" style="width: 208px;">
<option value="pena">Доп.время и пенальти</option>
<option value="stop">Остановить матч</option>
</select>
<br />
<br />
<br />
<br />
';
  echo '<input type="submit" name="saveprog" value="запись программки матча" />
';
}
else
  echo '<font color="red">Чтобы организовать<br />товарищеский матч,<br />нужно залогиниться и<br />
  <a href="http://fprognoz.org/?a=friendly&amp;m=register" target="_blank">
<font color="red"><b>зарегистрироваться</b></font></a><br /><br />
... или в обратном порядке,<br />
если у Вас ещё нет логина.</font>
';
?>
    <br />
    <br />
    <br />
    <br />
    <a href="friendly.php"><b>показать инструкцию</b></a>
    <br />
    <br />
  </center>
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
<input type="hidden" name="d" value="<?php echo $d;?>" />
</form>
</body>
</html>
