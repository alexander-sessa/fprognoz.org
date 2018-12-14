<link href="css/comments.css?ver=2" rel="stylesheet">
<link href="js/croppic/croppic.css" rel="stylesheet">
<script src="/js/croppic/croppic-3.0.min.js"></script>
<script>//<![CDATA[
var isEnabled=[],contentHTML=[],cke=[],cke_config={language:"ru"}
/*
function getSelectionText() {
           var text = "";
           if (window.getSelection) {
               text = window.getSelection().toString();
           } else if (document.selection && document.selection.type != "Control") {
               text = document.selection.createRange().text;
           }
           return text;
       }
var mytext=''
if(!window.x)x={}
x.Selector={}
x.Selector.getSelected=function(){
    var t='';
    if(window.getSelection)
        t=window.getSelection()
    else if(document.getSelection)
        t=document.getSelection()
    else if(document.selection)
        t=document.selection.createRange().text

    return t;
}
$(document).bind("mouseup",function(){selected=x.Selector.getSelected();if(mytext.length==0)mytext=selected})
*/
function c_quote(cid,inf){
com=$('[commentid="'+cid+'"]');c_text=$("main",com).html();var begin=1+c_text.indexOf('>'),end=c_text.lastIndexOf('<');c_text=c_text.substr(begin,end-begin);var c_date=$(".c-comment-date",com).html(),sStr="<blockquote><p><sub>"+$(".c-comment-author",com).html()+" <em>писал" + inf + ' '+c_date.split(' ').join(" в ")+"</em></sub></p><p>&bdquo;"+c_text+"&ldquo;</p></blockquote><p></p>";$("#cke"+cid).html($("#cke"+cid).html()+sStr)}
function changeRating(id,rate_yes,rate_no,vote){$("#r_yes"+id).html(rate_yes?rate_yes:"");$("#r_no"+id).html(rate_no?rate_no:"");$.get("comments/vote.php",{user:"<?=$coach_name?>",id:id,vote:vote,hash:"<?=crypt($coach_name,$salt)?>"})}
function saveContent(id,c_text){$.get("comments/save.php",{user:"<?=$coach_name?>",id:id,c_text:c_text,hash:"<?=crypt($coach_name,$salt)?>"})}
function modComment(id,man,status){$.get("comments/mod.php",{key:"content:"+id,man:man,status:status});$("#"+(status>0?"approve":"c_block")+id).hide()}

function isEditorEnabled(id){if(cke[id])return true;else cke[id]=1;return false}
function toggleEditor(id) {
	var reset=document.getElementById("reset"+id),toggle=document.getElementById("toggle"+id),content=document.getElementById("content"+id)
	if(isEnabled[id]==undefined)isEnabled[id]=null
	if(isEnabled[id]){
		editor=isEnabled[id]
		if(editor.model.document.differ)reset.style.display="inline"
		toggle.innerHTML='<i class="fas fa-edit" aria-hidden="true"></i> Исправить'
		editor.destroy()
		isEnabled[id]=null
		saveContent(id,content.innerHTML)
	}
	else{
		toggle.innerHTML='<i class="fas fa-save c-save" aria-hidden="true"></i> <span class="c-save">Записать<span>'
		InlineEditor
			.create(document.querySelector("#content"+id),cke_config)
			.then(function(editor){
				isEnabled[id]=editor;
				$("#content"+id).click().focus()
			})
	}
}
//]]></script>
<?php
function send_comment_by_email($from, $name, $email, $subj, $body) {
  $email = str_replace(',', ' ', $email);
  $amail = explode(' ', $email);
  foreach ($amail as $email) if (strpos($email, '@')) {
    $email = trim($email);
    if (trim($from) && trim($subj) && trim($body))
      @mail("$name <$email>",
'=?UTF-8?B?'.base64_encode($subj).'?=',
str_replace("\r", '', $body).'
',
'From: '.$from.'
Reply-To: '.$from.'
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: FP Informer 3.07.170321');
  }
}

function c_register_user($nicknm) {
  global $redis;
  global $role;
  global $coach_name;

  $status = ($role == 'president') ? 2 : 0;
  $redis->hmset('c_user:' . $coach_name, [
    'nicknm' => $nicknm,
    'status' => $status,
  ]);
}

function c_make_form($prefix, $id, $hidden) {
  global $redis;
  global $coach_name;
  if (!$coach_name) return '';                                     // незарегистрированным форма недоступна
  $c_user = $redis->hgetall('c_user:' . $coach_name);
  if (isset($c_user['status']) && $c_user['status'] > 999999999) { // проверка на окончание срока бана
    if ($c_user['status'] > time()) return '';                     // ещё рано: форму не показываем
    $redis->hset('c_user:' . $coach_name, 'status', 0);            // срок блока истёк, меняем статус на нормальный
  }
  $c_form = '
  <div id="%FORMID%" class="c-form"%HIDDEN%>
    <form action="#comment" method="POST">
    <input type="hidden" name="parent" value="%PARENT%" />
    <input type="hidden" name="userid" value="%USERID%" />
    <aside class="c-croppic-cnt">';

  if (!$hidden) {                                          // в главной форме показываем блок с аватаром
    $c_form .= '
      <div id="cropContainerModal" class="c-croppic" style="width:96px;height:96px;">';
    (isset($c_user['avatar']) && trim($c_user['avatar']))
      ? $c_form .= '<img src="images/avatars/96/' . $c_user['avatar'] .'" /></div>'
      : $c_form .= '<i class="fas fa-camera" aria-hidden="true"></i> &nbsp;загрузите &nbsp;аватар</div>';
  }

  $c_form .= '
    </aside>
    <main class="c-text-cnt">';
  $c_form .= '
      <textarea id="ta_%CKEDID%" name="c_text" hidden></textarea>
      <div id="%CKEDID%" name="c_text" class="c-textarea"></div>
    </main>
    <footer class="c-submit-cnt">
      <div class="c-submit-left">';
  if (!isset($c_user['nicknm']) || $c_user['nicknm'] == '') { // если в базе redis не указан ник
    $c_form .= '
        Если Вы хотите в комментариях использовать псевдоним вместо реального имени, укажите его здесь:
        <input type="text" name="nicknm" value="" /><br />';
  }
  $c_form .= '
      </div>
      <div class="c-submit">
        <button class="c-button" onClick="$(\'#ta_%CKEDID%\').val($(\'#%CKEDID%\').html())">Комментировать</button>
      </div>
    </footer>
    </form>' . ($hidden ? '' : '
    <script>InlineEditor.create(document.querySelector("#%CKEDID%"),cke_config)</script>
') . '
  </div>
';
  $c_form = str_replace('%PARENT%', $prefix.':'.$id, $c_form);
  $c_form = str_replace('%USERID%',     $coach_name, $c_form);
  $c_form = str_replace('%FORMID%',   $prefix . $id, $c_form);
  $c_form = str_replace('%CKEDID%',     'cke' . strtr($id, ':', '_'), $c_form);
  ($hidden) ? $c_form = str_replace('%HIDDEN%', ' style="display: none;"', $c_form) : $c_form = str_replace('%HIDDEN%', '', $c_form);
  return $c_form;
}

function c_vote_comment($id) {
  global $redis;
  $rate_yes = $redis->zcount('voting:' . $id, 1, 1);
  $rate_no = $redis->zcount('voting:' . $id, -1, -1);
  return '
        <span class="c-comment-vote">
          <span class="c-comment-null">Разделяете мнение?</span>
          <span class="c-comment-good">да</span>
          <a onClick="changeRating(' . $id . ',' . ($rate_yes + 1) . ',' . $rate_no . ',1);return false;">
            <i class="fas fa-thumbs-up c-comment-like" aria-hidden="true"></i> 
          </a>
          <span id="r_yes' . $id . '" class="c-comment-yes" title="согласен">' . ($rate_yes ? $rate_yes : ' ') . '</span>
          <span class="c-comment-bad">нет</span>
          <a class="material-icons md-18 c-comment-dislike" onClick="changeRating(' . $id  . ',' . $rate_yes . ',' . ($rate_no + 1) . ',0);return false;">
            <i class="fas fa-thumbs-down c-comment-dislike" aria-hidden="true"></i> 
          </a>
          <span id="r_no' . $id . '" class="c-comment-no" title="не согласен">' . ($rate_no ? $rate_no : ' ') . '</span>
        </span>
';
}

function c_inline_editor($id, $text, $owner) {
  $escape_chars = array('\\' => '\\\\', "\r" => "", "\n" => '\n', '"' => '\"'); //"
  return '
        <script>contentHTML[' . $id . ']="' . strtr($text, $escape_chars) . '";</script>
        &nbsp;
        <a id="toggle' . $id . '" style="cursor:pointer" onClick="toggleEditor(' . $id . ');"><i class="fas fa-edit" aria-hidden="true"></i> Исправить</a>
        <a id="reset' . $id . '" style="display:none;cursor:pointer" onClick="this.style.display=' . "'none'" . ';content' . $id . '.innerHTML=contentHTML[' . $id . '];saveContent(' . $id . ',contentHTML[' . $id . ']);"><i class="fas fa-undo c-undo" aria-hidden="true"></i> <span class="c-undo">Отмена</span></a>';
}

function c_moderation($id, $status) {
  return '
        &nbsp; <a style="cursor:pointer" onClick="modComment(' . $id . ', 0, -1);"><i class="fas fa-ban" aria-hidden="true"></i> Скрыть</a>'
      . ($status == 0 ? '
        &nbsp; <a id="approve' . $id . '" style="cursor:pointer" onClick="modComment(' . $id . ', 0, 1);"><i class="fas fa-check-square" aria-hidden="true"></i> Одобрить</a>' : '');
}

function c_out_comments($id, $level, $pid) {
  global $redis;
  global $coach_name;
  global $role;
  global $c_count;
  global $this_site;
  if (!$redis->exists('content:' . $id)) return ''; // несуществующий коммент
  $c_hash = $redis->hgetall('content:' . $id);
  if ($c_hash['status'] == -1) return ''; // не показываем скрытый коммент
  $uri = '?a=' . str_replace(':', '&s=', $pid);
  $c_user = $redis->hgetall('c_user:' . $c_hash['userid']);
  $temp = explode(' ', $c_hash['userid']);
  $initials = mb_substr($temp[0], 0,1);
  if (count($temp) > 1) $initials .= mb_substr($temp[1], 0,1);
  $author = $c_user['nicknm'];
  if (!$author) $author = $c_hash['userid'];

  $out = '
  <div id="c_block' . $id . '" class="c-comment">
    <aside style="position:relative;padding-left:' . (min($level, 5) * 48) . 'px">';
  if (isset($c_user['avatar']) && trim($c_user['avatar']))
    $out .= '
      <img src="/images/avatars/96/' . $c_user['avatar'] .'" width="48px" height="48px" alt="' . $initials . '" />';
  else
    $out .= '
      <div class="c-comment-icon' . ($c_user['status'] == 2 ? ' bg_gold' : '') . '">' . $initials . '</div>';
  $out .= '
    </aside>
    <div class="c-comment-info" style="position:relative;padding-left:' . (60 + min($level, 5) * 48) . 'px" commentid="' . $id . '">
      <header>
        <a name="comment-' . $id . '"></a>
        <span class="c-comment-author ' . (isset($c_user['status']) && $c_hash['status'] == 2 ? ' c-moderator' : '')
          . '">' . $author . '</span>
        <span class="c-comment-date">' . date('j-m-Y G:i', $c_hash['tstamp']) . '</span>
        <i onClick="$(\'#share' . $id . '\').toggle();share' . $id . '.select();return false;" class="fas fa-share-alt" aria-hidden="true" style="cursor:pointer;color:#b0b0b0" title="поделиться"> </i>
        <input type="text" id="share' . $id . '" style="display:none;width:350px;height:15px;font-size:12px;" value="' . $this_site . '/' . $uri . '#comment-' . $id . '" />
      </header>

      <main id="content' . $id .'">
        ';
  $out .= ($c_hash['status'] != 2) ? strip_tags($c_hash['c_text'], '<p><a><strong><em><ol><ul><li><blockquote><sub><sup>') : $c_hash['c_text']; // резать теги, если не модер
  $out .= '      </main>

      <footer>';
  if (isset($coach_name)) {                    // кнопки ответа на коммент
    $out .= '
        <a onClick=\'if(!isEditorEnabled('.$id.'))InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").toggle()\' style="cursor:pointer"><i class="fas fa-reply" aria-hidden="true"></i> Ответить</a>
        &nbsp; <a onClick=\'if(isEditorEnabled('.$id.')){editor=cke['.$id.'];editor.destroy()}c_quote('.$id.',"");InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").show()\' style="cursor:pointer"><i class="fas fa-quote-right" aria-hidden="true"></i> Цитировать</a>';
    if ($role == 'president' || ($coach_name == $c_hash['userid'] && !$redis->exists('comment:' . $id)))
      $out .= c_inline_editor($id, $c_hash['c_text'], $coach_name); // кнопки редактирования коммента

    if ($role == 'president')
      $out .= c_moderation($id, $c_hash['status']);    // кнопки модерирования

    $out .= c_vote_comment($id);                     // оценка полезности отзыва
  }
  $out .= '
      </footer>
    </div>' .
    c_make_form('comment', $id, true) . '
  </div>';

  $comment_c_list = array_reverse($redis->lrange('comment:' . $id, 0, -1));
  foreach ($comment_c_list as $c_id)
    $out .= c_out_comments($c_id, $level + 1, $pid);

  $c_count++;
  return $out;
}

// обработчик

while(list($k,$v)=each($_POST)) $$k=$v;
$modurl = $this_site . '/comments/mod.php?key=';
$comments_email = 'SFP Comment System <scs@fprognoz.org>';

if (isset($coach_name) && isset($parent) && ($c_text = trim($c_text))) { // добавить новый непустой коммент
  if (isset($nicknm)) {            // если есть параметр nickname,
    $nicknm = strip_tags($nicknm); // оформляем как нового юзера комментов
    c_register_user($nicknm);
  }

  $userid = $coach_name;

  // защита от дублирования сообщений
  $c_last = $redis->lrange('userlog:' . $userid, 0, 0);
  if (sizeof($c_last) == 0 || $redis->hget('content:' . $c_last[0], 'c_text') != $c_text) {
    $pre = $parent;
    $pid = explode(':', $pre)[1];
    while ($pre[0] == 'c') {
      $pre = $redis->hget('content:' . $pid, 'parent');
      $pid = explode(':', $pre)[1];
    }
    $uri = '?a=' . explode(':', $pre)[1] . '&s=' . explode(':', $pre)[2];

    // обработка флагов предка при ответе доверенного юзера или президента
    $status = $redis->hget('c_user:' . $coach_name, 'status');
    if (($status == 1 || $status == 2) && $parent[0] == 'c') {// если добавлен ответ на чей-то коммент
      $pid = explode(':', $parent)[1];
      $content = $redis->hgetall('content:' . $pid);
      if ($status == 2 && $content['status'] == 0)
        $redis->hset('content:' . $pid, 'status', 1);         // ответил президент == одобрение
    }

    // запись коммента
    $comment_id = $redis->incr('comment:id');
    $key = 'content:' . $comment_id;
    $redis->lpush('userlog:' . $userid, $comment_id); // список сообщений юзера
    $redis->zincrby('activeuser', 1, $userid);        // счётчик сообщений юзера
    $redis->lpush($parent, $comment_id);              // список сообщений ветки
    $redis->hmset($key, [
      'userid' => $userid,
      'tstamp' => time(),
      'parent' => $parent,
      'c_text' => $c_text,
      'status' => $status,
    ]);
    if (isset($ctitle)) $redis->hset($key, 'title', $ctitle);

    // комментарии обычного пользователя, а также требующие ответа, отправляются модератору
    if ($status == 0)
      send_comment_by_email ($comments_email,
'Fprognoz.Org Moderator', 'alexander.sessa@gmail.com',
'Новый комментарий', '
На страницу <a href="' . $this_site . '/' . $uri . '#comment">' . $this_site . '/' . $uri . '#comment</a>
юзером ' . $coach_name . ' добавлен комментарий следующего содержания:
<hr />
' . $c_text . '
<hr />
Выполните одно из действий:<ul>
<li><a href ="' . $modurl . $key . '&status=1">Проверен модератором</a></li>
<li><a href ="' . $this_site . '/' . $uri . '#comment">Ответить</a></li>
<li><a href ="' . $modurl . $key . '&status=-1">Скрыть комментарий</a></li>
<li><a href ="' . $modurl . $key . '&status=6">Блокировать юзера</a></li>
</ul>
');
  }
}

// выдача контента
if (!isset($s)) $s = $cur_year;
$id = $a . ':' . $s;
$redis_prefix = 'page';
echo '<a name="comment"></a>
<div id="comment" style="background:white;margin-top:10px;padding-top:5px;text-align:left;width:100%">
  <div style="text-align:center">
    <h5>ФАН-ЗОНА. ' . (isset($coach_name) ? 'Здесь Вы можете оставить свой комментарий' : 'Для полного доступа необходимо авторизоваться на сайте') . '</h5>
  </div>' .
     c_make_form($redis_prefix, $id, false) . '
</div>';        // prefix, id, hidden - главная форма
$c_count = 0;                                                      // счетчик комментов для вывода на вкладке
$comments_list = $redis->lrange($redis_prefix . ':' . $id, 0, -1); // список комментов, свежие впереди
$out = '';
foreach ($comments_list as $c_id)
  $out .= c_out_comments($c_id, 0, $id);

echo $out;
?>
<script>//<![CDATA[
var croppicContainerModalOptions={uploadUrl:"comments/img_save_to_file.php",cropUrl:"comments/img_crop_to_file.php?userId=<?=$coach_name?>",modal:true,doubleZoomControls:false,imgEyecandyOpacity:0.4,loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',}
var cropContainerModal=new Croppic("cropContainerModal",croppicContainerModalOptions);
//]]></script>
