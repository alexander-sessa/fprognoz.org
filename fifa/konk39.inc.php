<?php
$last_month = '08';
$last_day = '27';
if (!isset($updates)) $updates = NULL;
$base = get_results_by_date($last_month, $last_day, $updates, '2021');
$rprognoz = '';
$publish = true;
$tour = 'KONK39';
$nm = 40;
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
$addfile = time() > 1600515000 ? file_get_contents($online_dir . 'konkurs/adds/' . $tour) : '';
$added = explode("\n", $addfile);
foreach ($added as $line) if ($line = rtrim($line)) {
  if ($line[0] != ' ') {
    $team = trim(mb_substr($line, 0, 21));
    $line = trim(mb_substr($line, 22, 40));
    if ($cut = min(21, mb_strpos($line, ' ', 15))) $prognoz = trim(substr($line, 0, $cut));
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

  $prognozlist .= mb_sprintf('%-21s', $ta).sprintf('%-16s', $colored).'  '.sprintf('%2s',$hita)."\n";
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

  if (count($aprognoz) - (int)$mdp[$i+1]['hits'] == 1)
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
//setInterval(getDate, 1000);

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
socket=io.connect("//www.score2live.net:1998",{"reconnect":true,"reconnection delay":500,"max reconnection attempts":20,"secure":true})
socket.on("connect",function(){socket.emit("hellothere")})
socket.on("hellobz",function(){socket.emit("getscores","football(soccer)","today")})
socket.on("scoredatas",function(d){$("#statusline").css("display","none")})
socket.on("guncelleme",function(d){
	json=""
	$.each(d.updates,function(index,ux){if(base[ux.idx]!==undefined){if(ux.s==4&&base[ux.idx][3]!="FT")json+=(json.length?",":"")+JSON.stringify(ux);scorefix(ux)}})
	if(json.length)$.ajax({type:"POST",url:"'.$this_site.'",data:"updates="+encodeURIComponent("["+json+"]")+params,success:function(html){$("#pl").html(html)}})
})

//]]></script>
<style>
.bet {
    width: 1.5em;
    height: 1.5em;
    vertical-align: middle;
    margin-bottom: 0.2em;
    color: white;
    font-weight: bold;
    border: 1px solid black;
    border-radius: 50%;
    --bs-bg-opacity: .67;
}
.bet:active {
    border: 2px solid black;
    border-radius: 50%;
}
.pr_str {
    font-size: 1.2em;
    font-weight: bold;
    width: 0.8em;
    height: 1.1em;
    box-sizing: content-box;
}
.blink { color: red;
    -webkit-animation: blink 2s linear infinite;
    -moz-animation: blink 2s linear infinite;
    -ms-animation: blink 2s linear infinite;
    -o-animation: blink 2s linear infinite;
    animation: blink 2s linear infinite;
}

@-webkit-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-moz-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-ms-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@-o-keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 1; }
    50.01% { opacity: 0; }
    100% { opacity: 0; }
}
/* ---------------------------------------------------
    LIVESCORE.BZ
----------------------------------------------------- */
.tablex {
	border-top-left-radius: 0px;
	border-top-right-radius: 0px;
	border-bottom-left-radius: 0px;
	border-bottom-right-radius: 0px;
	margin: 0px 0px;
	width: 100%;
	border: 0px;
	border-spacing: 0px;
	padding: 0px;
}
.loaderP { justify-content: space-around;	align-items: center;	display: flex;}
.loader {
	border: 3px solid #f3f3f3;
	border-radius: 50%;
	border-top: 3px solid blue;
	border-right: 3px solid green;
	border-bottom: 3px solid red;
	border-left: 3px solid pink;
	width: 15px;
	height: 15px;
	-webkit-animation: spinbz 2s linear infinite;
	animation: spinbz 2s linear infinite;
	margin-top: 1px;
	margin-bottom: 0px;
}
.loaderB {
	border: 6px solid #f3f3f3;
	border-radius: 50%;
	border-top: 6px solid blue;
	border-right: 6px solid green;
	border-bottom: 6px solid red;
	border-left: 6px solid pink;
	width: 48px;
	height: 48px;
	-webkit-animation: spinbz 2s linear infinite;
	animation: spinbz 2s linear infinite;
	margin-top: 135px;
	margin-bottom: 0px;
}
@-webkit-keyframes spinbz {
	0% { -webkit-transform: rotate(0deg); }
	100% { -webkit-transform: rotate(360deg); }
}
@keyframes spinbz {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}
.det {padding:0px;font-size:92%}
.det table tr td {background-color:lavender;color:black;vertical-align: middle; border-width: 0px 0px 0px 0px !important;}
.det table tr td em {opacity: 0.5;}
.det img {max-height: 12px; border-width: 0px 0px 0px 0px;display: inline-block;width: auto;margin: 0px 0px;float: none !important;}
.det table td:nth-child(2) {text-align: center !important;}
.det .min {width:36px}
.det .side {width:47%}
.det .center {width:6%;text-align:center}
.det .left {float:left;text-align:left}
.det .right {float:right;text-align:right}
.det .green-red {width:18px;color:red;font-weight:bolder;position:relative;display:inline-block;vertical-align:top;}
.det .green-red:before,
.det .green-red:after {content:"⇅";position:absolute;top:0;left:0;padding-left:1px;color:green;width:50%;overflow:hidden;}
.monospace {
	white-space: pre-wrap;
	font-family: monospace;
	font-size: 1.1em;
	line-height: 1;
}
#mt	{
	white-space:pre;
	width:33%;
}
.tribune li {
    background: #1e2a64;
    color: whitesmoke;
    list-style-type: none;
    padding: 8px;
    border-radius: 10px;
    margin-top: 4px;
    white-space: pre-wrap;
}
.tribune b { color: yellow; }
.blue { color: blue;	font-weight: bold; }
.magenta { color: magenta;	font-weight: bold; }
.red { color: red;	font-weight: bold; }
mark { font-weight: bold;	background-color: yellow;	padding: 0; }
</style>
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
      <td><div id="pl" style="white-space:pre; font-family: monospace; text-align: left; font-size: 1.2em">
' . $prognozlist . '
      </div></td>
    </tr></table>
    </center>
</div>
<div>
<h4>Распределение команд</h4>
<p>
  Команды распределены с учётом регламента конкурса, квот и пожеланий участников.<br>
  В итогах учитываются 35 матчей из программки конкурса.
  5 матчей состоялись в пятницу, поэтому ставки на них не учитываются.<br>
  Прежде чем приступить к распределению, мы раздали команды в ФП ассоциациях,
  в которых собственно конкурса не было, чтобы пополнить призовой фонд командами,
  от которых отказались в пользу других команд.
</p>
<p>
  <strong>azarte</strong> "пожертвовал" команду украинской Премьер-лиги
  <strong>ФК&nbsp;Олександрія</strong> ради получения <strong>Торпедо-БелАЗ</strong> (Беларусь).<br>
  <strong>БГ-05</strong> предпочёл обменять команду украинской Премьер-лиги
  <strong>МФК&nbsp;Миколаїв</strong> на команду первой лиги <strong>ВПК-Агро</strong>.
</p>
<p>
  Угадав 22 исхода из 35, конкурс выиграл <strong>Andrey Donec</strong>.
  Победа увеличила его квоту и позволила получить 2 новые команды.<br>
  Первыми в предпочтениях были указаны "вылетевшие" PSG и Huesca, но Регламент сайта закрепляет вылетевшие
  команды за прежними игроками сроком на 1 сезон, поэтому Андрей получает <strong>Strasbourg</strong>
  (Франция) и <strong>Rayo Valecano</strong> (Испания).
</p>
<p>
  3 участника угадали по 21 исходу.
  Их заявки оказались короткими и без пересекающихся интересов, поэтому пока распределение идёт просто:<br>
  - <strong>Дмитрий Кузьменко</strong> вернул команду <strong>Комета</strong> в ФНЛ-2 (Россия);<br>
  - <strong>Alexandr Balakirev</strong> сменил <strong>Alavés</strong> на <strong>Real Madrid</strong> (Испания);<br>
  - <strong>Artyom Belov</strong> выиграл <strong>Celtic</strong> (Шотландия).<br>
  <br>
  В группе угадавших 20 исходов оказалось сразу 2 неудачника - <strong>Константин Сметанин</strong> и
  <strong>Eugene (Joker) Plugin</strong> претендовали только на Real.<br>
  Третий участник группы - <strong>olegshm</strong> получает <strong>Gil Vicente</strong> (Португалия).<br>
  <br>
  19 исходов угадали:<br>
  - <strong>Alex Baranovsky</strong> уходит ни с чем, поскольку и Real и Celtic уже недоступны;<br>
  - <strong>saleh</strong> получает указанный в заявке <strong>Салют Белгород</strong> (Россия);<br>
  - <strong>Дмитрий Визгин</strong> получает <strong>Ajax</strong> (Голландия),
  ради которого не пытается спасти выбывший Valenciennes;<br>
  - <strong>Gleb Arsatov</strong> тоже хотел взять Ajax, но уступил его Дмитрию,
  поскольку имеет больше команд; зато он получает команды <strong>Ajax II</strong> (Голландия),
  <strong>Arouca</strong> (Португалия), <strong>Alessandria</strong> (Италия) и
  <strong>Олександрія</strong> (Украина).
</p>
<p>
  Сразу 9 участников угадали по 18 исходов.
  Чтобы сделать распределение в это группе проще, сначала убираем тех, кто не смог получить желаемое:
  <strong>Batya35</strong>, <strong>Dmitry Redkin</strong>, <strong>БГ-05</strong> и
  <strong>Богдан Синиця</strong> претендовали только на именитые команды, разобранные к этому моменту.<br>
  Следующим шагом 3 команды получает <strong>Mikhaylidi Stanislav</strong>, заявка которого не конкурирует
  с остальными: <strong>Динамо Барнаул</strong> (Россия), <strong>Troyes</strong> (Франция),
  <strong>Verl</strong> (Германия).<br>
  Среди остальных 4-х претендентов была конкуренция 2/1 за команды Feyenoord (получает Serge Shibaev по п.3)
  и Италии (Andrey Vityuk по п.3):<br>
  - <strong>Roman77</strong> получает команды <strong>Peterborough United</strong> (Англия)
  и <strong>FC Andorra</strong> (Испания);<br>
  - <strong>Александр Сесса</strong> подтверждает участие <strong>Paços de Ferreira</strong> (Португалия);<br>
  - <strong>Andrey Vityuk</strong> становится обладателем команд <strong>Reggina</strong> (Италия),
  <strong>Twente</strong> (Голландия) и <strong>Estoril</strong> (Португалия);<br>
  - <strong>Serge Shibaev</strong> меняет команду Roda JC на <strong>Feyenoord</strong> (Голландия).
</p>
<p>
  17 исходов у 5 участников. Для двоих из них - <strong>Микола Синиця</strong> и
  <strong>Михаил Сирота</strong> - желаемых команд не осталось.<br>
  Остальным досталось:<br>
  - <strong>антон</strong> - <strong>NEC</strong> (Голландия);<br>
  - <strong>Nick Gahovich</strong> мог бы получить Clermont (Франция), но его квота на команды исчерпана;<br>
  - <strong>Star</strong> - <strong>Миколаїв</strong> (Украина), <strong>Oxford United</strong> (Англия),
  <strong>Saarbrücken</strong> (Германия).
</p>
<p>
  <strong>Ivan Shschyhlinki</strong> угадал только 16 исходов, но этого оказалось достаточно для
  возврата его прежней команды - <strong>St Mirren</strong> (Шотландия).
</p>
<p>
  15 исходов не хватило <strong>Andrey Razarenov</strong> для сохранения команды St Johnstone (Шотландия).<br>
  <strong>azarte</strong> с тем же результатом довольствуется <strong>Торпедо-БелАЗ</strong>-ом,
  а <strong>Максим Кузнецов</strong> уходит с пустыми руками.<br>
  <br>
  14 балов, которые набрал <strong>Вадим Федоренко</strong> - недостаточно для выигрыша Real Madrid.<br>
  <br>
  Замкнул таблицу участников конкурса <strong>Sergij Kupka</strong>(сер18).
  Но даже 13 исходов оказалось достаточно для получения команд <strong>Alaves</strong> (Испания) и
  <strong>Інгулець</strong> (Украина).<br>
  <br>
  После раздачи команд остались по одной вакансии в ФП Беларуси, Испании и России.<br>
  На усмотрение руководства этих ассоциаций свободные места могут быть отданы неудачникам предыдущего сезона
  или в фонд "Вакансии".
</p>
</div>
';
?>
