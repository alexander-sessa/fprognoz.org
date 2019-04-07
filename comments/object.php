<?php
function send_comment_by_email($from, $name, $email, $subj, $body) {
function c_register_user($nicknm) {
function c_make_form($prefix, $id, $hidden) {
function c_vote_comment($id) {
function c_inline_editor($id, $text, $owner) {
function c_moderation($id, $status) {
function c_out_comments($id, $level, $pid) {

// выдача контента
if (!isset($s)) $s = $cur_year;
$id = $a . ':' . $s;
$redis_prefix = 'page';
if (!isset($c_from))
{
  echo '
<a name="comment"></a>
<div id="comment" style="background:white;margin-top:10px;padding-top:5px;text-align:left;width:100%">
  <div style="text-align:center">
    <h5>ФАН-ЗОНА. ' . ($coach_name ? 'Здесь Вы можете оставить свой комментарий' : 'Для полного доступа необходимо авторизоваться на сайте') . '</h5>
  </div>'
. c_make_form($redis_prefix, $id, false) . '
</div>'; // prefix, id, hidden - главная форма
  $c_from = 0;
}
$c_on_page = 16; // количество цепочек комментариев на странице, можно брать из настроек пользователя
$c_to = $c_from + $c_on_page; //  = -1 -> все
$c_to = $c_from + $c_on_page; //  = -1 -> все
$comments_list = $redis->lrange($redis_prefix . ':' . $id, $c_from, $c_to - 1); // список комментов, свежие впереди
foreach ($comments_list as $c_id)
  echo c_out_comments($c_id, 0, $id);

if ($c_to > 0 && $redis->lrange($redis_prefix . ':' . $id, $c_to, $c_to))
  echo '<div id="more_comments" class="w-100">
  <center><button style="width:400px" onClick=\'moreComments("'.$coach_name.'","'.$a.'","'.$s.'",'.$c_to.')\'> показа
</div>';

