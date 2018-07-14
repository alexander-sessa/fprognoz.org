<style>
.bestoftour	{}
.invert	{}
.order	{}
.playerstable	{}
.statthead	{font-size:85%;line-height:8px}
.stattable	{width:100%}
.tdl	{padding:0 25px}
.tdn	{text-align:right;padding-right:20px}
.td1	{text-align:right;padding-right:10px}
.tdr	{text-align:right;padding-right:35px}
.teamstable	{}
</style>
<script>
n=0
function cv(k,o=""){return function(a,b){if(!a.hasOwnProperty(k)||!b.hasOwnProperty(k))return 0;r=0;if(typeof a[k]==="string"){if(a[k]>b[k])r=1;else if(a[k]<b[k])r=-1}else r=(a[k]-b[k]);return o=="r"?r*-1:r};}
function st(k,o=""){tm.sort(cv(k,o));$.each(tc,function(i,f){if(f["co"]==k)f["sr"]=o});$.each(to,function(i,f){if(f["co"]==k){f["sr"]=o;f["ch"]=++n}})}
function sp(k,o=""){pl.sort(cv(k,o));$.each(pc,function(i,f){if(f["co"]==k)f["sr"]=o});$.each(po,function(i,f){if(f["co"]==k){f["sr"]=o;f["ch"]=++n}})}
function ht(id){if(id=="#teamstable"){o=to;c=tc;d=tm}else{o=po;c=pc;d=pl}
h='<h1>Статистика '+(id=="#teamstable"?'команд':'игроков')+'</h1><p class="order">Сортировка:';o.sort(cv("ch","r"));h+=o[0]["ln"];h+='</p><table class="stattable"><tr class="statthead"><th>№</th>';
$.each(c,function(i,f){h+='<th><a id="'+f["co"]+'" class="invert" href="javascript:;" title="'+f["ln"]+'">'+f["sn"]+'<br /><i class="fas fa-caret-'+(f["sr"]?"down":"up")+'"></i></a></th>'});h+='</tr>'
$.each(d,function(i,r){h+='<tr><td class="tdn">'+(i+1)+'</td>';$.each(c,function(j,f){h+='<td'+f["ta"]+'>'+r[f["co"]]+'</td>'});h+='</tr>'})
h+='</table>'
$(id).html(h)
}
function ih(tb){
	$("#"+tb+" .invert").hover(function(){$("i",this).toggleClass("fa-caret-up");$("i",this).toggleClass("fa-caret-down")})
	$("#"+tb+" .invert").click(function(){o=$("i",this).hasClass("fa-caret-up")?"":"r";if(this.id.length==4){sp(this.id,o);ht("#playerstable")}else{st(this.id,o);ht("#teamstable")}ih(tb)})
}
$(document).ready(function(){
	$("#bestoftour").html(best)
	st("total","r");ht("#teamstable")
	sp("summ","r");ht("#playerstable")
//	$("#teamstable .invert").hover(function(){$("i",this).toggleClass("fa-caret-up");$("i",this).toggleClass("fa-caret-down")})
//	$("#teamstable .invert").click(function(){o=$("i",this).hasClass("fa-caret-up")?"":"r";if(this.id.length==4){sp(this.id,o);ht("#playerstable")}else{st(this.id,o);ht("#teamstable")}})
	ih("teamstable");ih("playerstable")
})
to=[
{co:"teamn",ln:"название команды",sr:"",ch:0},
{co:"total",ln:"баллы основного состава",sr:"",ch:0},
{co:"goals",ln:"забитые мячи",sr:"",ch:0},
{co:"averm",ln:"средний балл основного состава",sr:"",ch:0},
{co:"avera",ln:"средний балл всех игроков",sr:"",ch:0},
{co:"lrost",ln:"удачность выбора основного состава",sr:"",ch:0},
{co:"repls",ln:"количество замен",sr:"",ch:0},
{co:"lrepl",ln:"влияние замен на баллы осн. состава",sr:"",ch:0},
{co:"coach",ln:"общая оценка действий тренера",sr:"",ch:0},
{co:"tteam",ln:"количество номинаций \"команда тура\"",sr:"",ch:0},
{co:"tcoac",ln:"количество номинаций \"тренер тура\"",sr:"",ch:0}
]
po=[
{co:"name",ln:"имя игрока",sr:"",ch:0},
{co:"team",ln:"название команды",sr:"",ch:0},
{co:"tour",ln:"количество сыгранных туров",sr:"",ch:0},
{co:"summ",ln:"количество набранных баллов",sr:"",ch:0},
{co:"aver",ln:"средний балл",sr:"",ch:0},
{co:"accu",ln:"количество угаданных исходов",sr:"",ch:0},
{co:"avac",ln:"точность передач (исходы / туры)",sr:"",ch:0},
{co:"main",ln:"количество таймов в основном составе",sr:"",ch:0},
{co:"goal",ln:"количество забитых голов",sr:"",ch:0},
{co:"pass",ln:"количество голевых передач",sr:"",ch:0},
{co:"effc",ln:"средняя эффективность (гол+пас/таймы)",sr:"",ch:0},
{co:"best",ln:"количество номинаций \"игрок тура\"",sr:"",ch:0},
{co:"tute",ln:"количество попаданий в сборную тура",sr:"",ch:0}
]
tc=[
{co:"teamn",sn:"команда",ln:"название команды",ta:"",sr:""},
{co:"total",sn:"баллы",ln:"баллы основного состава",ta:" class=\"tdl\"",sr:"r"},
{co:"goals",sn:"голы",ln:"забитые мячи",ta:" class=\"tdl\"",sr:"r"},
{co:"averm",sn:"сред.балл",ln:"средний балл основного состава",ta:" class=\"tdl\"",sr:"r"},
{co:"avera",sn:"ср.балл всех",ln:"средний балл всех игроков",ta:" class=\"tdl\"",sr:"r"},
{co:"lrost",sn:"выбор о.с.",ln:"удачность выбора основного состава",ta:" class=\"tdl\"",sr:"r"},
{co:"repls",sn:"к-во замен",ln:"количество замен",ta:" class=\"tdl\"",sr:"r"},
{co:"lrepl",sn:"эфф.замен",ln:"влияние замен на баллы осн. состава",ta:" class=\"tdr\"",sr:"r"},
{co:"coach",sn:"оц.тренера",ln:"общая оценка действий тренера",ta:" class=\"tdl\"",sr:"r"},
{co:"tteam",sn:"команда №1",ln:"количество номинаций \"команда тура\"",ta:" class=\"tdr\"",sr:"r"},
{co:"tcoac",sn:"тренер №1",ln:"количество номинаций \"тренер тура\"",ta:" class=\"tdr\"",sr:"r"}
]
pc=[
{co:"name",sn:"имя игрока",ln:"имя игрока",ta:"",sr:""},
{co:"team",sn:"команда",ln:"название команды",ta:"",sr:""},
{co:"tour",sn:"туры",ln:"количество сыгранных туров",ta:" class=\"tdl\"",sr:"r"},
{co:"summ",sn:"баллы",ln:"количество набранных баллов",ta:" class=\"tdn\"",sr:"r"},
{co:"aver",sn:"сред.балл",ln:"средний балл",ta:" class=\"tdl\"",sr:"r"},
{co:"accu",sn:"исходы",ln:"количество угаданных исходов",ta:" class=\"tdn\"",sr:"r"},
{co:"avac",sn:"точность",ln:"точность передач (исходы / туры)",ta:" class=\"tdl\"",sr:"r"},
{co:"main",sn:"таймы",ln:"количество таймов в основном составе",ta:" class=\"tdn\"",sr:"r"},
{co:"goal",sn:"голы",ln:"количество забитых голов",ta:" class=\"td1\"",sr:"r"},
{co:"pass",sn:"пасы",ln:"количество голевых передач",ta:" class=\"td1\"",sr:"r"},
{co:"effc",sn:"эффект.",ln:"средняя эффективность (гол+пас/таймы)",ta:" class=\"tdl\"",sr:"r"},
{co:"best",sn:"№1 тура",ln:"количество номинаций \"игрок тура\"",ta:" class=\"tdn\"",sr:"r"},
{co:"tute",sn:"сборная",ln:"количество попаданий в сборную тура",ta:" class=\"tdn\"",sr:"r"}
]
<?php
if (is_file($online_dir.'WL/'.$s.'/publish/stat.'.$t))
  include($online_dir.'WL/'.$s.'/publish/stat.'.$t);
else
  include($online_dir.'WL/'.$s.'/publish/stat.'.$l.$t);
?>
</script>
<div id="bestoftour" class="bestoftour">
</div>
<div id="teamstable" class="teamstable">
</div>
<div id="playerstable" class="playerstable">
</div>
