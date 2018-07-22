<?php
$last_month = 02;
$last_day = 12;
if (!isset($updates)) $updates = NULL;
$base = get_results_by_date($last_month, $last_day, $updates);
$rprognoz = '';
$publish = true;
$tour = 'KONK35';
$nm = 20;
// формирование показа программки тура с реальными результатами
// парсинг программки
$matches = explode("\n", file_get_contents($online_dir . 'konkurs/programs/' . $tour));
require_once ('online/tournament.inc.php');
include ('online/realteam.inc.php');
// формирование вывода и базы тура
$mdp = array();
$program_table = '<table align="center">
<tr><th>№</th><th align="left" width="288">матч для прогнозирования</th><th>турнир</th><th>дата время</th><th>счёт</th><th>исход</th><th>угадано прогнозов</th></tr>
';
$id_arr = '';
foreach ($matches as $line) if ($line = trim($line)) {
  if ($line[0] == '|') {
    $atemp = explode('|', $line);
    $home = $away = '';
    if (sizeof($atemp) > 2 && $cut = strpos($atemp[2], ' - ')) {
      $nm = rtrim(trim($atemp[1]), '.');
      $dm = trim($atemp[3]);
      if ($dm[2] == '-') $dm = $dm[3].$dm[4].'.'.$dm[0].$dm[1];
      $home = trim(substr($atemp[2], 0, $cut));
      if ($cut1 = strrpos($home, '(')) $home = trim(substr($home, 0, $cut1));
      if ($ct = strrpos($home, '(')) $hfix = trim(substr($home, 0, $ct));
      else $hfix = $home;
      $away = trim(substr($atemp[2], $cut + 3));
      if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
      else $afix = $away;
      if (trim(substr($atemp[2], -3))) {
        $cut = strrpos($away, ' ');
        $tournament = substr($away, $cut + 1);
        $away = trim(substr($away, 0, strrpos($away, ' ')));
        if ($cut1 = strrpos($away, '(')) $away = trim(substr($away, 0, $cut1));
        if ($ct = strrpos($away, '(')) $afix = trim(substr($away, 0, $ct));
        else $afix = $away;
        $match = $realteam[$hfix].' - '.$realteam[$afix].'/'.$tourname[trim($tournament)];;
        if (!trim($tournament) || !isset($base[$match])) $match = $realteam[$hfix].' - '.$realteam[$afix];
      }
      else {
        $tournament = '&nbsp;';
        $match = $realteam[$hfix].' - '.$realteam[$afix];
      }
      $mt = '-:-'; $rt = '?'; $tn = '??:??'; // по умолчанию информации о счёте и времени матча нет
      if (isset($base[$match])) {
        list($match_date, $match_time) = explode(' ', $base[$match][2]);
        list($match_month, $match_day) = explode('-', $match_date);
        if ($match_month . $match_day > $last_month . $last_day) {
          // грубая проверка на максимальный срок переноса матча (все месяцы считаются по 31 дню)
          list($prog_day, $prog_month) = explode('.', $dm);
          if (($prog_month == 12) && ($match_month == 1)) $prog_month = 0;
//          if (($prog_month * 31 + $prog_day + 7) < ($match_month * 31 + $match_day)) $st = 'POS'; // перенос более, чем на 7 дней
          else { // матч надо учитывать
            $dm = $match_day . '.' . $match_month;
            $tn = $match_time;
            $st = $base[$match][3];
            $mt = $base[$match][5];
          }
          if (($st != '-') && (($st <= '90') || ($st == 'HT'))) {
            $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"' . $mt . '","' . $st . '"];
';
            $mt = '<span class="red">' . $mt . '</span>';
            ($st == 'HT') ? $rt = '<span class="red">' . $st . '</span>' : $rt = '<span class="blink">' . $st . "'" . '</span>';
          }
          elseif (($st == 'CAN') || ($st == 'POS') || ($st == 'SUS')) {
            $mt = $st;
            $rt = '-';
          }
          elseif ($st == 'FT') {
            list($gh, $ga) = explode(':', $mt);
            if ($gh == $ga) $rt = 'X';
            else ($gh > $ga) ? $rt = '1' : $rt = '2';
            $stat = true;
          }
          elseif ($match_day == date('d', time()))
            $id_arr .= 'base[' . $base[$match][6] . '] = [' . $nm . ',0,"-:-","-"];
';
        }
        else {
          $dm = $match_day.'.'.$match_month;
          $tn = $match_time;
          $mt = $st = '-:-';
          $rt = '-';
        }
      }
      $mdp[$nm]['home'] = $home;
      $mdp[$nm]['away'] = $away;
      $mdp[$nm]['rslt'] = $mt;
      $mdp[$nm]['case'] = $rt;
      $mdp[$nm]['hits'] = 0;
      $program_table .= '<tr id="' . $base[$match][6] . '"><td align="right">'.$nm.'</td><td align="left">'.$home.' - '.$away.'</td><td align="left">'.$tournament.'</td><td align="right">&nbsp;'.$dm.' '.$tn.'&nbsp;</td><td align="middle">'.$mt.'</td><td align="middle">'.$rt.'</td><td><!--'.$nm.'--></td></tr>
';
      if (strlen($rt) > 1)
        $rt = '?';
      $rprognoz .= $rt;
    }
  }
}
$program_table .=  '</table>
';
$aprognoz = array();
$addfile = file_get_contents($online_dir . 'konkurs/adds/' . $tour);
$added = explode("\n", $addfile);
foreach ($added as $line) if ($line = rtrim($line)) {
  if ($line[0] != ' ') {
    $team = trim(substr($line, 0, 21));
    $line = trim(substr($line, 22, 40));
    if ($cut = min(21, strpos($line, ' ', 15))) $prognoz = trim(substr($line, 0, $cut));
    else $prognoz = trim($line);
    $aprognoz[$team] = $prognoz;
  }
}
$prognozlist = 'Правильный прогноз:  ' . $rprognoz . '  угадано

';
$sprognoz = array();
foreach ($aprognoz as $ta => $prog) {
  $prognoz = $aprognoz[$ta];
  $sprognoz[$ta] = $prognoz;
  $hita = 0;
  $colored = '';
  for ($i=0; $i<$nm; $i++)
    if ($rprognoz[$i] == $prognoz[$i]) {
      $hita++;
      $mdp[$i+1]['hits']++;
      $colored .= '<span class="blue">'.$prognoz[$i].'</span>';
    }
    else $colored .= $prognoz[$i];

  $prognozlist .= sprintf('%-21s', $ta).sprintf('%-16s', $colored).'  '.sprintf('%2s',$hita)."\n";
}
//$prognozlist .= '
//Количество игроков, угадавших прогнозы:
//';
for ($i=0; $i<$nm; $i++) {
  if ($rprognoz[$i] == '-') $mdp[$i+1]['hits'] = '-';
  if (!isset($mdp[$i+1]['hits'])) {
    $mdp[$i+1]['hits'] = 0;
    $mdp[$i+1]['plyr'] = ':(';
  }
  elseif (isset($mdp[$i+1]['hits']) && $mdp[$i+1]['hits'] == count($aprognoz))
    $mdp[$i+1]['plyr'] = ':)';

  if ($mdp[$i+1]['hits'] == 1)
    foreach ($sprognoz as $teamn => $progn)
      if ($progn[$i] == $rprognoz[$i]) $mdp[$i+1]['plyr'] = $teamn;
  if (count($aprognoz) - $mdp[$i+1]['hits'] == 1)
    foreach ($sprognoz as $teamn => $progn)
      if (($progn[$i] != '=') && ($progn[$i] != $rprognoz[$i]))
        $mdp[$i+1]['plyr'] = $teamn;

  //$prognozlist .= ($i < 9 ? ' ' : '').($i + 1).'. '.($mdp[$i+1]['hits'] < 10 ? ' ' : '').$mdp[$i+1]['hits'].(isset($mdp[$i+1]['plyr']) ? ' '.$mdp[$i+1]['plyr'] : '')."\n";
  $program_table = str_replace('<!--' . ($i + 1) .'-->', ($mdp[$i+1]['hits'] < 10 ? '&nbsp;&nbsp;' : '').$mdp[$i+1]['hits'].(isset($mdp[$i+1]['plyr']) ? ' '.$mdp[$i+1]['plyr'] : ''), $program_table);
}
if (isset($updates))
  echo $prognozlist; // REST responce on event 'FT'
else
  echo '<script>//<![CDATA[
var '.date('\h\o\u\r\s=G,\m\i\n\u\t\e\s=i,\s\e\c\o\n\d\s=s',time()).',base=[],params="&m=konk33live"
' . $id_arr . '
function getDate(){
	if(seconds<59)seconds++;else{seconds=0;if(minutes<59)minutes++;else{minutes=0;hours=hours<23?hours+1:0}}
	var s=seconds+"",m=minutes+"";if(s.length<2)s="0"+s;if(m.length<2)m="0"+m
	$("#timedisplay").html("время сервера: "+hours+":"+m+":"+s)
}
setInterval(getDate, 1000);

mom=[]
momup=function(i){
	clearInterval(mom[i])
	mom[i]=setInterval(function(){
		if(!isNaN(base[i][3])){
			tm=+base[i][3];base[i][3]=(tm==45||tm==90)?tm+"+":++tm
			row=$("#"+i)[0];row.cells[5].innerHTML="<span class=\"blink\">"+base[i][3]+"’</span>"
		}
	}, 60000);
}
scorefix=function(d){
	i=d.idx
	m=+d.dk
	if(m<1)m=1;else if(m>59)m=base[i][3]
	h=d.evs;a=d.deps
	s=h+":"+a
	if(base[i][2]!=s){base[i][2]=s;base[i][1]=1}else base[i][1]=0
	s=+d.s
	if(s==0){base[i][3]="?";base[i][2]="-:-"}
	else if(s==1){base[i][3]=(m>45)?"45+":m;momup(i)}
	else if(s==2)base[i][3]="HT"
	else if(s==3){base[i][3]=(m>45)?"90+":m+45;momup(i)}
	else if(s==4||s==8||s==11){if(base[i][3]!="FT"){base[i][3]="FT";var bg=$("#"+i).css("background-color");$("#"+i).css("background-color","gold");$("#"+i).animate({"background-color":bg},20000)}}
	else if(s==5)base[i][3]="SP"
	else if(s==13||s==14)base[i][3]="PP"
	else base[i][3]="?"
	s=base[i][3]
	if(s!="?"){
		r=base[i][2]
		row=$("#"+i)[0]
		row.cells[4].innerHTML=(base[i][1]==1)?"<span class=\"blink\">"+r+"</span>":(s=="FT")?r:"<span class=\"red\">"+r+"</span>"
		row.cells[5].innerHTML=(s=="HT"||s=="SP")?"<span class=\"red\">"+s+"</span>":(s=="?"||s=="PP")?"<span>"+s+"</span>":(s=="FT")?"<span>"+((h==a)?"X":(h>a)?1:2)+"</span>":"<span class=\"blink\">"+s+"’</span>"
	}
}
socket=io.connect("//score2live.net:1998",{"reconnect":true,"reconnection delay":500,"max reconnection attempts":20,"secure":true})
socket.on("connect",function(){socket.emit("hellothere")})
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){$("#statusline").css("display","none")})
socket.on("guncelleme",function(d){
	json=""
	$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}})
	if(json.length)$.ajax({type:"POST",url:"'.$this_site.'",data:"updates="+encodeURIComponent("["+json+"]")+params,success:function(html){$("#pl").html(html)}})
})

//]]></script>
<div style="position:relative;width:100%;margin:0 0 20px 0">
  <div id="statusline" style="position:relative;float:left;text-align:left;display:block">получение результатов с <a href="https://www.livescore.bz" sport="football(soccer)" data-1="today" lang="en">www.livescore.bz</a></div>
  <div id="timedisplay" style="position:relative;float:right;text-align:right"></div>
</div>
<div style="position:relative;width:100%">
    <p class="title text15b">&nbsp;&nbsp;&nbsp;Результаты конкурса ' . $tour . ' online</p>
    <hr size="1" width="98%" />
    <center>
    <br />
' . $program_table . '    <br />
    <table align="center"><tr>
      <td><div id="pl" style="white-space:pre; font-family: monospace; text-align: left;">
' . $prognozlist . '
      </div></td>
    </tr></table>
    </center>
</div>
';
?>
