<?php
function maillist_create($subscribers, $subject, $content) {
  // получить номер мейллиста из maillist:id
  // создать maillist - аналог page - просто нумеруется, не привязываясь к страницам
  // записать его заголовок в subject:номер
  // создать список имен получателей этого мейллиста subscribers
  // создать comment в maillist, содержащий content
  // для каждого из subscribers:
  // - добавить номер мейллиста в список subscribed:user
  // - кроме себя, добавить номер мейллиста в список unread:user
}

function maillist_show($id) {
  // если юзер есть в списке Subscribers:$id
  //   вывод цепочки комментов из maillist:$id
  //   удаление $id из unread
}

function maillist_exit($id) {
  // удаление $id из unread
  // удаление $id из subscribed
  // удаление юзера из subscribers
}

function maillist_list() {
  // получить список всех мейллистов юзера
}

function maillist_read($id) {
  // удаление $id из unread
}

function maillist_unread() {
  // получить список всех обновлённых мейллистов юзера
}
