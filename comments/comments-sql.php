<?php
function get_comment_list($id, $order) {
  global $sqli;
  $res = $sqli->query("SELECT `child` FROM `relatives` WHERE `parent` = $id ORDER BY `child` $order");
  $list = [];
  while ($row = $res->fetch_assoc())
    $list[] = $row['child'];

  return $list;
}

function c_make_form($id, $hidden) {
  global $user;
  return '
  <div id="' . $id . '" class="c-form"' . ($hidden ? ' style="display: none;"' : '') . '>
    <form action="#comment" method="POST">
    <input type="hidden" name="parent" value="' . $id . '" />
    <input type="hidden" name="userid" value="' . $user . '" />
    <main class="c-text-cnt">
      <textarea id="ta_cke' . $id . '" name="c_text" hidden></textarea>
      <div id="cke' . $id . '" name="c_text" class="c-textarea"></div>
    </main>
    <footer class="c-submit-cnt">
      <div class="c-submit-left">
      </div>
      <div class="c-submit">
        <button class="c-button" onClick="$(\'#ta_cke'.$id.'\').val($(\'#cke'.$id.'\').html())">Add comment</button>
      </div>
    </footer>
    </form>' . ($hidden ? '' : '
    <script>InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config)</script>
') . '
  </div>
';
}

function c_inline_editor($id, $text, $owner) {
  $escape_chars = ['\\' => '\\\\', "\r" => "", "\n" => '\n', '"' => '\"']; //"
  return '
        <script>contentHTML['.$id.']="' . strtr($text, $escape_chars) . '";</script>
        &nbsp;
        <a id="toggle'.$id.'" style="cursor:pointer" onClick="toggleEditor('.$id.');"><i class="fas fa-edit" aria-hidden="true"></i> Edit</a>
        <a id="reset'.$id.'" style="display:none;cursor:pointer" onClick="this.style.display=\'none\';content'.$id.'.innerHTML=contentHTML['.$id.'];saveContent('.$id.',contentHTML['.$id.']);"><i class="fas fa-undo c-undo" aria-hidden="true"></i> <span class="c-undo">Undo</span></a>';
}

function c_out_comments($id, $level, $pid) {
  global $sqli;
  global $user;

  $res = $sqli->query("SELECT * FROM `comments` WHERE `id` = $id LIMIT 1");
  if ($res->num_rows == 0) return ''; // несуществующий коммент

  $c_hash = $res->fetch_assoc();
  if ($c_hash['status'] == -1) return ''; // не показываем скрытый коммент

  $out = '
  <div id="c_block'.$id.'" class="c-comment">
    <div class="c-comment-info" style="position:relative;padding-left:'.(min($level, 5) * 48).'px" commentid="'.$id.'">
      <header>
        <a name="comment-'.$id.'"></a>
        <span class="c-comment-author">'.$c_hash['user'].'</span>
        <span class="c-comment-date">'.date('j-m-Y G:i', $c_hash['tstamp']).'</span>
      </header>
      <main id="content'.$id.'">
        '.$c_hash['c_text'].'
      </main>
      <footer>
        <a onClick=\'if(!isEditorEnabled('.$id.'))InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").toggle()\' style="cursor:pointer"><i class="fas fa-reply" aria-hidden="true"></i> Reply</a>
        &nbsp; <a onClick=\'if(isEditorEnabled('.$id.')){editor=cke['.$id.'];editor.destroy()}c_quote('.$id.',"");InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").show()\' style="cursor:pointer"><i class="fas fa-quote-right" aria-hidden="true"></i> Quote</a>';
  if ($role == 'admin' || ($user == $c_hash['user'] && !get_comment_list($id, 'ASC')))
      $out .= c_inline_editor($id, $c_hash['c_text'], $user); // кнопки редактирования коммента

  $out .= '
      </footer>
    </div>' .
    c_make_form($id, true) . '
  </div>';

  $comment_c_list = get_comment_list($id, 'DESC');
  foreach ($comment_c_list as $c_id)
    $out .= c_out_comments($c_id, $level + 1, $pid);

  return $out;
}

// обработчик
foreach ($_POST as $k => $v)
   $$k = $v;

if (isset($parent) && ($c_text = trim($c_text))) {
  $res = $sqli->query("SELECT `c_text` FROM `comments` WHERE `parent` = $parent ORDER BY `id` DESC LIMIT 1");
  $c_last = $res->num_rows ? $res->fetch_assoc()['c_text'] : '';
  if ($c_last != $c_text) {
/*
    $pid = $parent;
    while ($pid >= 10000) {
      $res = $sqli->query("SELECT `parent` FROM `comments` WHERE `id` = $pid LIMIT 1");
      $pid = $res->fetch_assoc($res)['parent'];
    }
*/
    // запись коммента
    $time = time();
    $res = $sqli->query("INSERT INTO `comments` SET
      `user` = '$user',
      `tstamp` = $time,
      'orderid' = $id,
      'parent' = $parent,
      'c_text' = '$c_text',
      'status' = 1");
    $child = $sqli->insert_id;
    $sqli->query("INSERT INTO `relatives` SET `parent` = $parent, `child` = $child");
    $sqli->query("UPDATE `orders` SET `comment` = '$c_text' WHERE `id` = $id LIMIT 1");
  }
}

// выдача контента
echo '
<script>//<![CDATA[
var isEnabled=[],contentHTML=[],cke=[],cke_config={language:"en"}
function c_quote(cid,inf){
com=$("[commentid=\""+cid+"\"]");c_text=$("main",com).html();var begin=1+c_text.indexOf(">"),end=c_text.lastIndexOf("<");c_text=c_text.substr(begin,end-begin);var c_date=$(".c-comment-date",com).html(),sStr="<blockquote><p><sub>"+$(".c-comment-author",com).html()+" <em>wrote"+inf+" "+c_date.split(" ").join(" ")+"</em></sub></p><p>&bdquo;"+c_text+"&ldquo;</p></blockquote><p></p>";$("#cke"+cid).html($("#cke"+cid).html()+sStr)}
function saveContent(id,c_text){$.get("comments/save.php",{user:"<?=$name?>",id:id,c_text:c_text,hash:"<?=crypt($name,$salt)?>"})}
function isEditorEnabled(id){if(cke[id])return true;else cke[id]=1;return false}
function toggleEditor(id) {
	var reset=document.getElementById("reset"+id),toggle=document.getElementById("toggle"+id),content=document.getElementById("content"+id)
	if(isEnabled[id]==undefined)isEnabled[id]=null
	if(isEnabled[id]){
		editor=isEnabled[id]
		if(editor.model.document.differ)reset.style.display="inline"
		toggle.innerHTML="<i class=\"fas fa-edit\" aria-hidden=\"true\"></i> Edit"
		editor.destroy()
		isEnabled[id]=null
		saveContent(id,content.innerHTML)
	}
	else{
		toggle.innerHTML="<i class=\"fas fa-save c-save\" aria-hidden=\"true\"></i> <span class=\"c-save\">Save<span>"
		InlineEditor
			.create(document.querySelector("#content"+id),cke_config)
			.then(function(editor){
				isEnabled[id]=editor;
				$("#content"+id).click().focus()
			})
	}
}
//]]></script>
<a name="comment"></a>
<div id="comment" style="background:white;margin-top:10px;padding-top:5px;text-align:left;width:100%">
  <div style="text-align:center">
    <h5>COMMENTS</h5>
  </div>'
. c_make_form($id, false) . '
</div>'; // id, hidden - главная форма
$comments_list = get_comment_list($id, 'ASC'); // список комментов, свежие впереди
foreach ($comments_list as $c_id)
  echo c_out_comments($c_id, 0, $id);

?>
