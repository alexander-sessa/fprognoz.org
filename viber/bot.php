<?php
use Viber\Bot;
use Viber\Api\Sender;
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('utf-8');
chdir('/home/fp');
require_once 'data/config.inc.php';
require_once 'vendor/autoload.php';

# Массив viber id "дежурных" менеджеров

$viber_managers = [
    'qO40SlCgu3pxJvpLl1VGYw==',
];

function getUser($viberId)
{
    global $data_dir;

    $user = [];
    foreach (file($data_dir.'auth/.viber', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line)
    {
        list($id, $email) = explode(';', $line);
        if ($viberId == $id)
        {
            $user['email'] = $email;
            break;
        }
    }
    foreach (file($data_dir.'auth/.access', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line)
    {
        list($code, $cc, $team, $name, $email, $hash, $role) = explode(';', $line, 7);
        if ($email == $user['email'])
        {
            $user['teams'][] = ['code' => $code, 'cc' => $cc, 'team' => $team];
            if (!isset($user['name']))
                $user['name'] = $name;

        }
    }
    return $user;
}

function addToLog($receiverId, $text) {
    $fl = fopen('viber/bot.log');
    fwrite($fl, date('Y-m-d H:i:s')." $receiverId $text\n");
    fclose($fl);
}

# Имя и картинка для сообщений бота

$botSender = new Sender([
    'name' => 'FPrognoz.Org Bot',
    'avatar' => 'https://fprognoz.org/images/sfp.jpg',
]);

# Кнопки главного меню

$buttons = [
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(3)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Мои туры')
        ->setSilent(true)
        ->setText('Мои туры'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(3)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Мои команды')
        ->setSilent(true)
        ->setText('Мои команды'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(3)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Выбор сезона')
        ->setSilent(true)
        ->setText('Выбор сезона'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(3)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('open-url')
        ->setActionBody('https://fprognoz.org')
        ->setSilent(true)
        ->setText('Открыть сайт'),
];

#
# Обработчики событий
#

try {
    $bot = new Bot(['token' => $viber_api_key]);
    $bot

# Первый контакт с ботом: связывание аккаунта по id в context

        ->onConversation(function ($event) use ($bot, $botSender, $buttons, $data_dir) {
            $receiverId = $event->getUser()->getId();
            addToLog($receiverId, 'onConversation handler');
            if (!($user = getUser($receiverId)) && ($id = intval($event->getContext())))
            { // привязка по id клиента в context
                $fo = fopen($data_dir.'auth/.viber', 'a');
                fwrite($fo, $receiverId.';'.$event->getContext()."\n");
                fclose($fo);
                $user = getUser($receiverId);
            }
            if (isset($user['name']))
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Здравствуйте, '.$user['name'].'!')
                    ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons));
            else
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Ваш viber не связан с вашим аккаунтом на сайте. Пожалуйста, вернитесь сюда по ссылке "Открыть Viber Bot" в персональном кабинете на сайте https://fprognoz.org');
        })

# Подписка: получение первого сообщения от клиента

        ->onSubscribe(function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getUser()->getId();
            addToLog($receiverId, 'onSubscribe handler');
            $this->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText('Есть контакт! Используйте кнопки меню:')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

# Обработка нажатий на кнопки и ключевых фраз в сообщениях от клиента

        ->onText('|help|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            addToLog($receiverId, '"help" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Для получния информация нажмите нужную кнопку')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

# Обработка сообщений, содержащих произвольный текст

        ->run();
}
catch (Exception $e) {
}
