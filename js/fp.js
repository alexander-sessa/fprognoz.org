(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', 'UA-92920347-1', 'auto');ga('send', 'pageview');
$.browser={};$.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());$.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());$.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());$.browser.msie=/msie/.test(navigator.userAgent.toLowerCase())
var fg=0,ti=[],si=[],tz=jstz.determine();document.cookie="TZ="+tz.name()+";path=/;SameSite=Strict"
class UploadAdapter{
        constructor(loader){this.loader = loader;}
        upload(){
                return this.loader.file
                .then(file=>new Promise((resolve,reject)=> {
                        this._initRequest();
                        this._initListeners(resolve,reject,file);
                        this._sendRequest(file);
                }));
        }
        abort(){if(this.xhr){this.xhr.abort();}}
        _initRequest(){
                const xhr=this.xhr=new XMLHttpRequest();
                xhr.open('POST','https://fprognoz.org/online/ajax.php',true);
                xhr.responseType='json';
        }
        _initListeners(resolve,reject,file){
                const xhr=this.xhr;
                const loader=this.loader;
                const genericErrorText=`Невозможно залить файл: ${file.name}.`;
                xhr.addEventListener('error',()=>reject(genericErrorText));
                xhr.addEventListener('abort',()=>reject());
                xhr.addEventListener('load',()=>{
                        const response = xhr.response;
                        if(!response||response.error){return reject(response&&response.error?response.error.message:genericErrorText)}
                        resolve({default:response.url});
                });
                if(xhr.upload){
                        xhr.upload.addEventListener('progress',evt=>{
                                if(evt.lengthComputable){loader.uploadTotal=evt.total;loader.uploaded=evt.loaded;}
                        });
                }
        }
        _sendRequest(file){
                const data=new FormData();
                data.append('upload',file);
                data.append('hash',$('#comments_wrapper').data('hash'));
                this.xhr.send(data);
        }
}
function UploadAdapterPlugin(editor){editor.plugins.get('FileRepository').createUploadAdapter=(loader)=>{return new UploadAdapter(loader)}}
var cke_config={extraPlugins:[UploadAdapterPlugin],language:"ru"},editable=null,isEnabled=[],contentHTML=[],cke=[]
function c_quote(cid,inf){com=$("[commentid=\""+cid+"\"]");c_text=$("main",com).html();var begin=1+c_text.indexOf(">"),end=c_text.lastIndexOf("<");c_text=c_text.substr(begin,end-begin);var c_date=$(".c-comment-date",com).html(),sStr="<blockquote><p><sub>"+$(".c-comment-author",com).html()+" <em>писал"+inf+" "+c_date.split(" ").join(" в ")+"</em></sub></p><p>&bdquo;"+c_text+"&ldquo;</p></blockquote><p></p>";$("#cke"+cid).html($("#cke"+cid).html()+sStr)}
function changeRating(id,rate_yes,rate_no,vote){$("#r_yes"+id).html(rate_yes?rate_yes:"");$("#r_no"+id).html(rate_no?rate_no:"");$.get("comments/vote.php",{user:$("#comments_wrapper").data("name"),id:id,vote:vote,hash:$("#comments_wrapper").data("hash")})}
function saveContent(id,c_text){$.post("comments/save.php",{user:$("#comments_wrapper").data("name"),id:id,c_text:encodeURIComponent(c_text),hash:$("#comments_wrapper").data("hash")})}
function modComment(id,man,status){$.get("comments/mod.php",{key:"content:"+id,man:man,status:status});$("#"+(status>0?"approve":"c_block")+id).hide()}
function isEditorEnabled(id){if(cke[id])return true;else cke[id]=1;return false}
function toggleEditor(id) {
	var reset=document.getElementById("reset"+id),toggle=document.getElementById("toggle"+id),content=document.getElementById("content"+id)
	if(isEnabled[id]==undefined)isEnabled[id]=null
	if(isEnabled[id]){
		editor=isEnabled[id]
		if(editor.model.document.differ)reset.style.display="inline"
		toggle.innerHTML="<i class=\"fas fa-edit\" aria-hidden=\"true\"></i> Исправить"
		editor.destroy()
		isEnabled[id]=null
		content.innerHTML=content.innerHTML.replace("img src","img class=\"img-fluid\" src")
		saveContent(id,content.innerHTML)
	}
	else{
		toggle.innerHTML="<i class=\"fas fa-save text-success\" aria-hidden=\"true\"></i> <span class=\"c-save\">Записать<span>"
		InlineEditor
			.create(document.querySelector("#content"+id),cke_config)
			.then(function(editor){
				isEnabled[id]=editor;
				$("#content"+id).click().focus()
			})
	}
}
function moreComments(c,a,s,f){$.post("/online/ajax.php",{a:a,s:s,c_from:f,coach:c},function(r){$("#more_comments").replaceWith(r)})}
function validateEmail(email){var re=/\S+@\S+\.\S+/;return re.test(email)}
function passwordCheck(str){
  $.post("/online/ajax.php",{data:$("#pass_str").data("tpl"),nick:$("#name_str").val(),pswd:str},function(r){
    if(r=='0'){
      $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times"></i> неверный пароль</span>');
      fg=setTimeout(function(){$("#forget").show()},17000)
    }
    else if(r=='1'){
      $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check"></i> добро пожаловать!</span>');
      $("#l_form").submit()}
    else if(r=='2'){
      $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check"></i> пароль выслан</span>')
    }
  })
}
function showTab(i){
var uri=window.location.search,pos=uri.lastIndexOf("&n=");if(pos>0)uri=uri.substring(0,pos);window.history.pushState(null, null, uri+"&n="+i);$(".multitabs").hide();$("#tab-"+i).show();$("#whatsifn").val(i);$("#dynamic").attr("data-tab",i);$("#pl").attr("tabindex",-1).focus();return false}
function sendPredicts(apikey,tour,codes,predicts){$.post("/online/ajax.php",{data:apikey,tour:tour,team_codes:codes,predicts:predicts},function(r){$("#statusline").html(r);$("#send_predict").removeClass("btn-primary");$("#send_predict").addClass(r.indexOf("success")>0?"btn-success":"btn-danger");$("#send_predict").removeAttr("disabled")})}
function emailCheck(str){$.post("/online/ajax.php",{data:$("#name_str").data("tpl"),nick:str,email:str},function(r){if(r=='0')$("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times"></i> такой e-mail не найден</span>');else{str=$("#pass_str").val();if(str.length)passwordCheck(str);else $("#valid_name").html('<span style="color:lightblue;cursor:pointer" onClick="tokenSend(); return false" title="Вам будет выслана ссылка для входа"><i class="fas fa-check"></i> войти без пароля?</span>')}})}
function nicknameCheck(str){$.post("/online/ajax.php",{data:$("#name_str").data("tpl"),nick:str,email:""},function(r){if(r=='0')$("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times"></i> имя/e-mail не найдены</span>');else{str=$("#pass_str").val();if(str.length)passwordCheck(str);else $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check"></i> теперь введите пароль</span>')}})}
function tokenSend(){$.post("/online/ajax.php",{data:$("#l_form").data("tpl"),nick:$("#name_str").val()},function(r){if(r=='1')$("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check"></i> проверьте вашу почту</span>');else $("#valid_name").html('<span style="color:lightsalmon"><i class="fas fa-times"></i> не удалось отправить</span>')})}
function newPassword(){return true}
function addTournament(o){var id=$(o).data("id");a=+id.substring(id.length-1);if(typeof ti[a]=="undefined")ti[a]=a;ti[a]++;a=ti[a];html='<ul id="tournament-'+a+'"><li><div>Название турнира: </div><input type="text" name="tournament['+a+']" value="" placeholder="не обязательно"> <div class="delete_stage" data-id="tournament-'+a+'"><button class="fas fa-trash" title="удалить турнир"></button></div></li><li><div>Префикс кода тура: </div><input type="text" name="prefix['+a+']" value="" placeholder="по умолчанию - код ассоциации"></li><li><div>Схема розыгрыша: </div><select name="type['+a+']"><option value="chm">чемпионат (круговой турнир)</option><option value="cup">кубок (турнир с выбыванием)</option><option value="com">комбинированный (группы + плей-офф)</option></select></li><li><div>Нумерация туров: </div><select name="numeration['+a+']"><option value="stage">поэтапная (каждый этап начинается туром 1)</option><option value="toend">сквозная (без сброса номера, как в еврокубках)</option></select></li><li><h6>Этапы: <div class="add_stage" data-id="trn-'+a+'-st-0"><button class="fas fa-plus-circle" title="добавить этап"></button></div></h6><div id="div-trn-'+a+'-st-0" class="stage-div"></div></li></ul><div id="div-tournament-'+a+'" class="tournament-div"></div>';$("#div-tournament-"+(a-1)).after(html);}
function addStage(o){var id=$(o).data("id");a=id.split('-');b=+a[3];a=+a[1];if(typeof ti[a]=="undefined")ti[a]=a;if(typeof si[a]=="undefined")si[a]=[];if(typeof si[a][b]=="undefined")si[a][b]=b;si[a][b]++;b=si[a][b];html='<ul id="trn-'+a+'-st-'+b+'"><li><div>Название этапа: </div><input type="text" name="stage['+a+']['+b+']" value="" placeholder="не обязательно"> <div class="delete_stage" data-id="trn-'+a+'-st-'+b+'"><button class="fas fa-trash" title="удалить этап"></button></div></li><li><div>Суффикс кода тура: </div><input type="text" name="suffix['+a+']['+b+']" value="" placeholder="по умолчанию нет"></li><li><div>Файл календаря: </div><input type="text" name="cal['+a+']['+b+']" value="" placeholder="по умолчанию cal"></li><li><div>Количество групп (лиг): </div><input type="text" name="groups['+a+']['+b+']" value="" placeholder="по умолчанию 1"></li><li><div>Количество туров: </div><input type="text" name="tourn['+a+']['+b+']" value=""></li><li><div>Количество кругов: </div><input type="text" name="round['+a+']['+b+']" value="" placeholder="по умолчанию 2"></li><li><div>Префикс названия тура: </div><input type="text" name="nprefix['+a+']['+b+']" value="" placeholder="по умолчанию Тур: "></li></ul><div id="div-trn-'+a+'-st-'+b+'" class="stage-div"></div>';$("#div-trn-"+a+"-st-"+(b-1)).after(html);}
function replaceEditable(m){
if($("#editable").data("hl")){var t=$("#editable").data("hl");$("#editable").html($("#editable").html().replace(new RegExp("<mark>"+t+"</mark>", 'g'), t))}
if(m=="pres")
{
//  $("#editable").replaceWith('<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="mailSubject" placeholder=" Заголовок пресс-релиза"></p><textarea id="editable" name="text" class="monospace" style="width:100%;height:30em" placeholder=" Место для нового пресс-релиза"></textarea></form>')
  $("#editable").replaceWith('<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="mailSubject" placeholder=" Заголовок пресс-релиза"></p>Форматированный текст нового пресс-релиза, но можно и простой текст без украшательств:<div id="editable" name="text" class="border border-1" style="width:100%;height:30em"></div>Чисто текстовая версия нового пресс-релиза для примитивных почтовых клиентов (необязательно):<textarea name="altbody" class="monospace" style="width:100%;height:10em"></textarea></form>')
  InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})
}
else if(m=="text"){
  if($("#editable").hasClass("monospace"))
    $("#editable").replaceWith('<form id="theMail" method="POST"><p><input id="subject" type="text" name="subj" class="mailSubject" value="'+$("#mailIcon").data("subj")+'"></p><textarea id="editable" name="text" class="monospace" style="width:100%;height:'+Math.max(20,$("#editable").html().split("\n").length)+'em">'+$("#editable").html()+"</textarea></form>");
  else
    InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})
}
else if(m=="edit"){if($("#editable").hasClass("monospace"))$("#editable").replaceWith('<textarea id="editable" class="monospace" style="width:100%;height:'+Math.max(20,$("#editable").html().split("\n").length)+'em">'+$("#editable").html()+"</textarea>");else InlineEditor.create(document.querySelector("#editable"),cke_config).then(function(editor){editable=editor;$("#editable").click().focus()})}
else{html='<form id="theMail" method="POST"><div id="editable" style="display: flex; width: 100%; align-items: stretch;perspective: 900px;"><div style="min-width: 13em; max-width: 13em; line-height: 1em">';i=0;$('.player_name').each(function(){name=$(this).html();if(name.indexOf("value=")>0)name=name.split('"')[5];if(name)html+='<br><label><input type="checkbox" name="p['+(i++)+']"> '+name+"</label>"});html+='</div><div style="width: 100%; min-height: '+(i*1.5)+'em"><input type="text" name="subj" style="width: 100%" placeholder=" Заголовок сообщения"><textarea name="text" style="width: 100%; margin-top: 10px; height:'+((i-2)*1.5)+'em" placeholder=" Текст сообщения"></textarea></div></div></form>';$("#editable").replaceWith(html)}
}
function bindCroppic(){
  if($("#funZoneIndicator").html()){
    var croppicContainerModalOptions={uploadUrl:"comments/img_save_to_file.php",cropUrl:"comments/img_crop_to_file.php?userId="+$("#comments_wrapper").data("name"),modal:true,doubleZoomControls:false,imgEyecandyOpacity:0.4,loaderHtml:"<div class=\"loader bubblingG\"><span id=\"bubblingG_1\"></span><span id=\"bubblingG_2\"></span><span id=\"bubblingG_3\"></span></div> ",}
    var cropContainerModal=new Croppic("cropContainerModal",croppicContainerModalOptions)
  }
}
$(document).ready(function(){
if($(".rightbar-header").data("log")=="out")history.pushState(null,"", "/")
else if($(".rightbar-header").data("log")=="in"){var x=window.matchMedia("(max-width:1200px)");if(x.matches){$("#rightbar").addClass("active");$("#rightbarCollapse").addClass("active");$("#rightbarIconUser").hide();$("#rightbarIconUserX").show()}};
$("#sidebarCollapse").click(function(){$("#sidebar").toggleClass("active");$(this).toggleClass("active")});
$("#rightbarCollapse").click(function(){$("#rightbar").toggleClass("active");$(this).toggleClass("active");if($("#rightbarIconUser").is(":hidden")){$("#rightbarIconUser").show();$("#rightbarIconUserX").hide()}else if($("#rightbarIconUserX").is(":hidden")){$("#rightbarIconUser").hide();$("#rightbarIconUserX").show()}});
$("#name_str").blur(function(){if(!$("#name_str").is(":hover")){var str=$(this).val();if(str.length<2)$("#valid_name").html('<span style="color:pink"><i class="fas fa-times"></i> введите хотя бы 2 буквы</span>');else if(validateEmail(str)) emailCheck(str);else nicknameCheck(str)}})
$("#pass_str").keyup(function(k){
  if($("#pass_str").is(":focus")&&k.key!="Shift"){
    clearTimeout(fg);
    str=$(this).val();
    if(str.length)passwordCheck(str);
    else{
      str=$("#name_str").val();
      if(validateEmail(str))emailCheck(str);
      else $("#valid_name").html('<span style="color:lightgreen"><i class="fas fa-check"></i> теперь введите пароль</span>')
    }
  }
})
$(window).scroll(function(){if($(this).scrollTop()>100)$(".scrollToTop").fadeIn();else $(".scrollToTop").fadeOut()});
$(".scrollToTop").click(function(){$("html,body").animate({scrollTop:0},400);return false});
$("#editIcon").click(function(){$("#saveIcon").show();$("#editIcon").hide();replaceEditable("edit")})
$("#saveIcon").click(function(){$("#editIcon").show();$("#editIcon").css("background","green").css("color","whitesmoke");$("#saveIcon").hide();if($("#editable").hasClass("monospace"))$("textarea#editable").replaceWith('<div id="editable" class="monospace">'+$("#editable").val()+"</div>");else editable.destroy();$.post("/online/ajax.php",{data:$("#saveIcon").data("tpl"),text:encodeURIComponent($("#editable").html())},function(r){})})
$(".add_tournament").click(function(){addTournament(this);$(".add_stage").of("click").click(function(){addStage(this)});$(".delete_stage").off("click").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})})
$(".add_stage").click(function(){addStage(this);$(".delete_stage").off("click").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})})
$(".delete_stage").click(function(){$("#"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove();$("#div-"+$(this).data("id")).remove()})
$("#season_settings").change(function(){console.log('y');$("#ConfigEditor").show();$("#saveCfgIcon").css("background","orangered")})
$("#ConfigEditor").click(function(){$.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#saveCfgIcon").data("tpl"))+'&'+$("#season_settings").serialize(),success:function(r){$("#saveCfgIcon").css("background","green")}})})
$("#MainForm").change(function(){$("#SubmitForm").show()})
$(".pressrelease-title").click(function(){show=$("#"+$(this).data("pr")).is(":hidden");$(".pressrelease").hide();if(show)$("#"+$(this).data("pr")).show();$("html,body").scrollTop(0)})
$("#mailIcon").click(function(){$("#sendIcon").show();$("#mailIcon").hide();replaceEditable($(this).data("mode"))})
$("#sendIcon").click(function(){$(".overlay").fadeTo("slow",0.65);$(".overlay").html('<div class="loaderP"><div class="loaderB">');
if($("div[name='text']").length)$("#theMail").append("<textarea name='text' hidden>"+$("div[name='text']").html()+"</textarea>")
$.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#sendIcon").data("tpl"))+'&'+$("#theMail").serialize(),success:function(r){$("#editable").html(r);$("#mailIcon").show().css("background","green").css("color","whitesmoke");$("#sendIcon").hide();$(".overlay").hide()}})})
$("a[name=modal]").click(function(e){e.preventDefault();$('.overlay').fadeTo("fast",0.65);$("#mwin").addClass("popup-show")});
$(".popup .close,.overlay").click(function(e){e.preventDefault();$(".overlay").hide();$("#mwin").removeClass("popup-show")});
$("#editable").html(function(index,text){if($(this).data("hl")){var t=$(this).data("hl");return text.replace(new RegExp(t, 'g'), "<mark>"+t+"</mark>")}})
if($("#timedisplay").length>6)setInterval(function(){if(seconds<59)seconds++;else{seconds=0;if(minutes<59)minutes++;else{minutes=0;hours=hours<23?hours+1:0}}var sts=seconds+"",stm=minutes+"";if(sts.length<2)sts="0"+sts;if(stm.length<2)stm="0"+stm;$("#timedisplay").html(hours+":"+stm+":"+sts)},1000)
$("#toggleFunZone").click(function(){$.ajax({type:"POST",url:"/online/ajax.php",data:"data="+encodeURIComponent($("#funZone").data("tpl")),success:function(r){c=r?"on":"off";$("#funZoneIndicator").html('<img src="images/'+c+'.gif" border="0" alt="'+c+'">');$("#comments_wrapper").html(r);if(c=="on")bindCroppic()}})})
$(".c-content").change(function(){
  if($("#toggle"+this.id.substr(7)+" i").hasClass("fa-edit")&&html.indexOf("img-fluid")==-1)
    $(this).html($(this).html().replace('src=', 'class="img-fluid" src='))
})
bindCroppic()
})
