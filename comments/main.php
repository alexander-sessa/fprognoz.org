<?php
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
  <div id="%FORMID%" class="w-100 pb-3"%HIDDEN%>
    <form id="%FORMID%_form" action="#comment" method="POST">
    <input type="hidden" name="parent" value="%PARENT%" />
    <input type="hidden" name="userid" value="%USERID%" />
    <aside class="c-croppic-cnt">';

  if (!$hidden) { // в главной форме показываем блок с аватаром
    $c_form .= '
      <div id="cropContainerModal" class="c-croppic" style="width:96px;height:96px;">';
    (isset($c_user['avatar']) && trim($c_user['avatar']))
      ? $c_form .= '<img src="images/avatars/96/' . $c_user['avatar'] .'" /></div>'
      : $c_form .= '<i class="fas fa-camera" aria-hidden="true"></i> &nbsp;загрузите &nbsp;аватар</div>';
  }

  $c_form .= '
    </aside>
    <main class="shadow">';
  $c_form .= '
      <textarea id="ta_%CKEDID%" name="c_text" hidden></textarea>
      <div id="%CKEDID%" class="border border-1 border-dark" name="c_text"></div>
    </main>
    <footer class="d-flex justify-content-between mt-2">
      <div>';
//  if (!isset($c_user['nicknm']) || $c_user['nicknm'] == '') { // если в базе redis не указан ник
    $c_form .= '
        &nbsp;Укажите ваш ник для комментариев:
        <input type="text" style="height: 2rem; width: 250px; padding: 4px; border-radius: 4px" name="nicknm" value="'.(isset($c_user['nicknm']) && $c_user['nicknm'] ? $c_user['nicknm'] : '').'" placeholder=" не обязательно"><br />';
//  }
  $c_form .= '
      </div>
      <div>
        <button class="btn btn-secondary shadow-sm" onClick="$(\'#ta_%CKEDID%\').val($(\'#%CKEDID%\').html())">Комментировать</button>
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
          <span>Разделяете мнение?</span>
          <span class="text-success">да</span>&nbsp;<a onClick="changeRating(' . $id . ',' . ($rate_yes + 1) . ',' . $rate_no . ',1);return false;" class="fas fa-thumbs-up c-comment-like" aria-hidden="true"></a> 
          <span id="r_yes' . $id . '" class="text-success px-1" title="согласен">' . ($rate_yes ? $rate_yes : ' ') . '</span>
          <span class="text-danger">нет</span>&nbsp;
          <a onClick="changeRating(' . $id  . ',' . $rate_yes . ',' . ($rate_no + 1) . ',0);return false;" class="fas fa-thumbs-down c-comment-dislike" aria-hidden="true"></a> 
          <span id="r_no' . $id . '" class="text-danger px-1" title="не согласен">' . ($rate_no ? $rate_no : ' ') . '</span>
';
}

function c_inline_editor($id, $text, $owner) {
  $escape_chars = array('\\' => '\\\\', "\r" => "", "\n" => '\n', '"' => '\"'); //"
  return '
        <script>contentHTML[' . $id . ']="' . strtr($text, $escape_chars) . '";</script>
        &nbsp;
        <a id="toggle' . $id . '" style="cursor:pointer" onClick="toggleEditor(' . $id . ');"><i class="fas fa-edit" aria-hidden="true"></i> Исправить</a>
        <a id="reset' . $id . '" style="display:none;cursor:pointer" onClick="this.style.display=' . "'none'" . ';content' . $id . '.innerHTML=contentHTML[' . $id . '];saveContent(' . $id . ',contentHTML[' . $id . ']);"><i class="fas fa-undo text-danger" aria-hidden="true"></i> <span class="c-undo">Отмена</span></a>';
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
  <div id="c_block' . $id . '" class="d-flex pb-3 w-100">
    <aside style="padding-left:' . (min($level, 5) * 48) . 'px">';
  if (isset($c_user['avatar']) && trim($c_user['avatar']))
    $out .= '
      <img src="/images/avatars/96/' . $c_user['avatar'] .'" width="48px" height="48px" alt="' . $initials . '"  class="rounded-lg">';
  else
    $out .= '
      <div class="c-comment-icon' . ($c_user['status'] == 2 ? ' bg-gold' : '') . '">' . $initials . '</div>';
  $out .= '
    </aside>
    <div class="ml-1 w-100" commentid="' . $id . '">
      <header>
        <a name="comment-' . $id . '"></a>
        <strong class="c-comment-author px-2 text-' . (isset($c_user['status']) && $c_hash['status'] == 2 ? 'gold' : 'dark') . '">' . $author . '</strong>
        <span class="c-comment-date text-secondary small">' . date_tz('j-m-Y G:i', '', $c_hash['tstamp'], $_COOKIE['TZ']) . '</span>
        <a href="javascript:;" onClick="$(\'#share' . $id . '\').toggle();share' . $id . '.select();return false;" class="fas fa-share-alt" aria-hidden="true" style="cursor:pointer" title="поделиться"> </a>
        <input type="text" id="share' . $id . '" class="small" style="display:none;width:350px;height:15px;font-size:12px;" value="' . $this_site . '/' . $uri . '#comment-' . $id . '">
      </header>

      <main id="content' . $id .'" class="c-content p-2 border border-light">
        ';
  $out .= ($c_hash['status'] != 2) ? strip_tags($c_hash['c_text'], '<p><a><strong><em><ol><ul><li><blockquote><sub><sup>') : $c_hash['c_text']; // резать теги, если не модер
  $out .= '
      </main>';

  if (isset($coach_name)) {                          // кнопки ответа на коммент
    $out .= '
      <footer class="d-flex justify-content-between">
        <div class="text-secondary">
          <sup>
            <a onClick=\'if(!isEditorEnabled('.$id.'))InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").toggle()\' style="cursor:pointer"><i class="fas fa-reply" aria-hidden="true"></i> Ответить</a>
            &nbsp;
            <a onClick=\'if(isEditorEnabled('.$id.')){editor=cke['.$id.'];editor.destroy()}c_quote('.$id.',"");InlineEditor.create(document.querySelector("#cke'.$id.'"),cke_config).then(function(editor){cke["'.$id.'"]=editor});$("#comment'.$id.'").show()\' style="cursor:pointer"><i class="fas fa-quote-right" aria-hidden="true"></i> Цитировать</a>';
    if ($role == 'president' || ($coach_name == $c_hash['userid'] && !$redis->exists('comment:' . $id)))
      $out .= c_inline_editor($id, $c_hash['c_text'], $coach_name); // кнопки редактирования коммента

    if ($role == 'president')
      $out .= c_moderation($id, $c_hash['status']); // кнопки модерирования

    $out .= '
          </sup>
        </div>
        <div class="text-secondary text-right">
          <sup>
            ' . c_vote_comment($id) . '
          </sup>
        </div>
      </footer>';                    // оценка полезности отзыва
  }
  $out .= c_make_form('comment', $id, true) . '
    </div>
  </div>';

  $comment_c_list = array_reverse($redis->lrange('comment:' . $id, 0, -1));
  foreach ($comment_c_list as $c_id)
    $out .= c_out_comments($c_id, $level + 1, $pid);

  return $out;
}

// обработчик

while(list($k,$v)=each($_POST)) $$k=$v;
$modurl = $this_site . '/comments/mod.php?key=';
$comments_email = 'SFP Comment System <scs@fprognoz.org>';

if (isset($coach_name) && isset($parent) && ($c_text = trim($c_text))) {
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
        $redis->hset('content:' . $pid, 'status', 1); // ответил президент == одобрение
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
      send_email ($comments_email,
'Fprognoz.Org Moderator', 'alexander.sessa@gmail.com',
'Новый комментарий', '
<!DOCTYPE html>
<html>
<body>
На страницу <a href="' . $this_site . '/' . $uri . '#comment">' . $this_site . '/' . $uri . '#comment</a>
юзером ' . $coach_name . ' добавлен комментарий следующего содержания:
<hr>
' . $c_text . '
<hr>
Выполните одно из действий:<ul>
<li><a href ="' . $modurl . $key . '&status=1">Проверен модератором</a></li>
<li><a href ="' . $this_site . '/' . $uri . '#comment">Ответить</a></li>
<li><a href ="' . $modurl . $key . '&status=-1">Скрыть комментарий</a></li>
<li><a href ="' . $modurl . $key . '&status=6">Блокировать юзера</a></li>
</ul>
</body>
</html>
');
  }
}

// выдача контента
if (!isset($s)) $s = $cur_year;
$id = $a . ':' . $s;
$redis_prefix = 'page';
if (!isset($c_from))
{
  echo '
<a name="comment"></a>
<div id="comment" class="bg-white mt-2 w-100">
  <div class="text-center">
    <h5>ФАН-ЗОНА. ' . ($coach_name ? 'Здесь Вы можете оставить свой комментарий' : 'Для полного доступа необходимо авторизоваться на сайте') . '</h5>
  </div>'
. c_make_form($redis_prefix, $id, false) . '
</div>'; // prefix, id, hidden - главная форма
  $c_from = 0;
}
$c_on_page = 16; // количество цепочек комментариев на странице, можно брать из настроек пользователя
$c_to = $c_from + $c_on_page; //  = -1 -> все
$comments_list = $redis->lrange($redis_prefix . ':' . $id, $c_from, $c_to - 1); // список комментов, свежие впереди
foreach ($comments_list as $c_id)
  echo c_out_comments($c_id, 0, $id);

if ($c_to > 0 && $redis->lrange($redis_prefix . ':' . $id, $c_to, $c_to))
  echo '<div id="more_comments" class="w-100 text-center">
  <button style="width:400px" onClick=\'moreComments("'.$coach_name.'","'.$a.'","'.$s.'",'.$c_to.')\'> показать ещё </button></center>
</div>';
?>
