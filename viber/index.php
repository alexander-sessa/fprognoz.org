<?php
use Viber\Bot;
use Viber\Api\Sender;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
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

# Имя и картинка для сообщений бота

$botSender = new Sender([
    'name' => 'FPrognoz.Org Bot',
    'avatar' => 'https://fprognoz.org/images/sfp.jpg',
]);

# Настройки лога

$log = new Logger('bot');
$log->pushHandler(new StreamHandler('viber/bot.log', Logger::DEBUG));

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
    $bot = new Bot(['token' => $config['apiKey']]);
    $bot

# Первый контакт с ботом: связывание аккаунта по id в context

        ->onConversation(function ($event) use ($bot, $botSender, $buttons, $log, $data_dir) {
            $receiverId = $event->getUser()->getId();
            $log->info($receiverId . ' onConversation handler');
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

        ->onSubscribe(function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getUser()->getId();
            $log->info($receiverId . ' onSubscribe handler');
            $this->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText('Есть контакт! Используйте кнопки меню:')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

# Обработка нажатий на кнопки и ключевых фраз в сообщениях от клиента

        ->onText('|изменить доставку|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "изменить доставку"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Изменить способ и адрес доставки можно в разделе Корзина на нашем сайте https://voron.ua/basket.php . Вы также можете внести изменения в уже оформленный заказ до его оплаты или до его отправки, если выбрана оплата наложенным платежом.')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|изменить заказ|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "изменить заказ"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Вы можете внести изменения в уже оформленный заказ до его оплаты или до его отправки, если выбрана оплата наложенным платежом.')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|с моим заказом|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "с моим заказом"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Узнать состояние заказа можно нажав кнопку "Заказы".')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|оплата|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "оплата"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Узнать, поступила ли оплата, можно нажав кнопку "Заказы".')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|чат-бот|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "чат-бот"');

            $text = 'Наш чат-бот обучен такому:

- искать товары, показывать найденное, добавлять выбранные товары в Корзину;

- показывать Корзину, изменять количество товаров в ней, оформить заказ;

- показывать информацию о ваших последних Заказах;

- показывать сделанные на сайте Закладки и добавлять товары из Закладок в Корзину;

- рассказать о нашем Магазине: время работы, контакты, расположение, сайт;

- отвечать на часто задаваемые вопросы (Справка).

Если Вы не нашли готовый ответ на интересующий вопрос, напишите боту сообщение. Он переадресует его нам, а мы постараемся ответить как можно быстрее.

Для полноценной работы бота необходимо связать ваш аккаунт viber с сайтом магазина. Для этого на том же устройстве, где установлен viber, необходимо кликнуть на значок viber-а в уголке пользователя сайта https://voron.ua

Некоторые вещи чат-бот не сможет делать: например, показать каталог товаров, показать подробную информацию о товаре, выбрать способ и адрес доставки, отложить понравившийся товар в Закладки.
Для использования полного сервиса нашего магазина, пожалуйста, пользуйтесь нашим сайтом https://voron.ua';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Haш aдpec|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Haш aдpec"');

            $text = 'г. Днепр,
ул. Новокрымская 58
(на углу пересечения с ул. Матросская)';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Location())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setLat(48.419286)
                ->setLng(35.004262)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Oпиcaниe пpoeздa|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Oпиcaниe пpoeздa"');

            $text = 'от Вокзала
- маршрутное такси № 34, 106 (остановка ул. Матросская).

от пл.Островского (пл.Старомостовая)
- маршрутное такси №106, 33, трамвай №12 (остановка ул. Матросская).

из Центра
- маршрутное такси № 136А, 136, 151А, 151Б, 45, 45А (остановка ул. Матросская).';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Гpaфик paбoты|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Гpaфик paбoты"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText(workHours())
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Teлeфoн Kиeвcтap|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Teлeфoн Kиeвcтap"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Contact())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setName('ЧП Ворон (Киевстар)')
                ->setPhoneNumber('+380675651300')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Teлeфoн Vodafone|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Teлeфoн Vodafone"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Contact())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setName('ЧП Ворон (Vodafone)')
                ->setPhoneNumber('+380505868657')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|oфopмить зaкaз|s', function ($event) use ($bot, $botSender, $buttons, $log, $sqli) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' оформление заказа');
            $res = $sqli->query("SELECT * FROM `users` WHERE `viber` LIKE '{$receiverId}' ORDER BY `id` DESC LIMIT 1");
            $user = $res->fetch_assoc();
            $columns = 6;
            $rows = 7;
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns($columns)
                ->setButtonsGroupRows($rows)
                ->setButtons(createOrder($user, $columns, $rows))
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|help|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' "help" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText('Для получния информация нажмите нужную кнопку')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|https://voron.ua|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' "https://voron.ua" in the message');
            $bot->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons));
        })

# Обработка сообщений, содержащих произвольный текст

        ->onText('|.*|s', function ($event) use ($bot, $botSender, $buttons, $log, $sqli, $viber_managers) {
            $receiverId = $event->getSender()->getId();
            if ($id = $event->getMessage()->getTrackingData())
            {
                if ($id == 'Пoиcк')
                { // выдача результатов поиска
                    $log->info($receiverId . ' search "'.addslashes($event->getMessage()->getText()).'"');
                    $columns = 6;
                    $rows = 7;
                    $carousel = showSearch($event->getMessage()->getText(), $receiverId, $columns, $rows);
                    foreach ($carousel as $cards)
                        $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setButtonsGroupColumns($columns)
                            ->setButtonsGroupRows($rows)
                            ->setButtons($cards)
                            ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
                        );

                }
                else
                { // ответ менеджера пересылаем приславшему текст
                    $res = $sqli->query("SELECT `userid`, `reply`, `manager` FROM `viber_bot` WHERE `id` = $id ORDER BY `id` DESC LIMIT 1");
                    $message = $res->fetch_assoc();
                    $userid = $message['userid'];
                    $manager = $message['manager'];
                    $reply = $sqli->real_escape_string($message['reply']."\n".$event->getMessage()->getText());
                    $res = $sqli->query("SELECT `viber` FROM `users` WHERE `id` = $userid LIMIT 1");
                    if ($res && $res->num_rows)
                        $user = $res->fetch_assoc();

                    $log->info($receiverId . ' reply "'.addslashes($event->getMessage()->getText()).'"', ['userid' => $userid]);
                    $sqli->query("UPDATE `viber_bot` SET `reply` = '$reply', `status` = 2 WHERE `id` = $id");
                    // ответ менеджера пересылается, если он длиннее 1
                    if (strlen($event->getMessage()->getText()) > 1)
                        $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($user['viber'])
                            ->setText($event->getMessage()->getText())
                            ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
                        );

                    // если есть неотвеченные сообщения, отправить этому менеджеру следующее с предпочтением назначенному ему
                    $timeout = date('Y-m-d H:i:s', time() - 900); // таймаут 15 мин привязки сообщений к менеджерам
                    $res = $sqli->query("SELECT * FROM `viber_bot` WHERE `status` = 0
                                            AND (`manager` = -1 OR `manager` = $manager OR `date` < '$timeout')
                                            ORDER BY (`manager` = $manager) DESC, `id` ASC LIMIT 1");
                    if ($res && $res->num_rows)
                    {
                        $message = $res->fetch_assoc();
                        $res = $sqli->query("SELECT `fio`, `name`, `otch` FROM `users` WHERE `id` = {$message['userid']} LIMIT 1");
                        if ($res && $res->num_rows)
                            $user = $res->fetch_assoc();

                        $log->info($receiverId . ' message "'.addslashes($message['message']).'" from the queue resent to manager ' . $manager, ['userid' => $message['userid']]);
                        $name = $user['fam'].' '.$user['name'].' '.$user['otch'].'('.$message['userid'].')';
                        $sqli->query("UPDATE `viber_bot` SET `manager` = $manager, `status` = 1 WHERE `id` = {$message['id']}");
                        $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setTrackingData($message['id'])
                            ->setText($name.': "'.$message['message'].'"')
                        );
                    }
                }
            }
            else
            { // пересылка произвольного текста свободному менеджеру или в очередь сообщений
                $res = $sqli->query("SELECT `id`, `fam`, `name`, `otch` FROM `users` WHERE `viber` LIKE '{$receiverId}' ORDER BY `id` DESC LIMIT 1");
                if ($res && $res->num_rows)
                {
                    $user = $res->fetch_assoc();
                    $name = $user['fam'].' '.$user['name'].' '.$user['otch'].'('.$user['id'].')';
                    // если последнее сообщение было в течение 15 минут, назначаем того же менеджера
                    // если на последнее сообщение ещё не получен ответ, сообщения объединяются
                    // $res = $sqli->query("SELECT * FROM `viber_bot` WHERE `userid` = {$user['id']} AND `status` < 2 ORDER BY `id` DESC LIMIT 1");
                    $res = $sqli->query("SELECT * FROM `viber_bot` WHERE `userid` = {$user['id']} ORDER BY `id` DESC LIMIT 1");
                    if ($res && $res->num_rows)
                        $message = $res->fetch_assoc();

                    if (isset($message) && $message['status'] < 2)
                    { // если на последнее сообщение ещё не получен ответ, сообщения объединяются
                        $log->info($receiverId . ' message "'.addslashes($event->getMessage()->getText()).'" resent to manager ' . $message['manager']);
                        $text = $sqli->real_escape_string($message['message']."\n".$event->getMessage()->getText());
                        $sqli->query("UPDATE `viber_bot` SET `message` = '$text' WHERE `id` = {$message['id']}");
                        if ($message['status'] == 0)
                            return NULL;
                        else
                            // показ новых сообщений менеджеру, который уже назначен этому клиенту
                            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                                ->setSender($botSender)
                                ->setReceiver($viber_managers[$message['manager']])
                                ->setTrackingData($message['id'])
                                ->setText($name.': "'.$event->getMessage()->getText().'"')
                            );

                    }
                    else
                    {
                        // менеджеры, занятые ответами
                        $busy = [];
                        $res = $sqli->query("SELECT DISTINCT `manager` FROM `viber_bot` WHERE `status` = 1");
                        while ($manager = $res->fetch_all())
                            $busy[] = $manager['id'];

                        if (isset($message) && time() - strtotime($message['time']) < 900)
                        { // Если со времени последнего сообщения прошло < 15 минут, назначаем новое тому же менеджеру
                            $manager = $message['manager'];
                            $receiverId = $viber_managers[$manager];
                            $status = in_array($id, $busy) ? 0 : 1;
                        }
                        else
                        {
                            $receiverId = $status = 0;
                            foreach ($viber_managers as $id => $vid)
                                if (!in_array($id, $busy))
                                {
///// проверка статуса менеджера в viber не работает на PC, да и вообще не подходит
///// вероятно, следует ввести команды: "я здесь" / "меня нет"
                                    $receiverId = $vid;
                                    $status = 1;
                                    break;
                                }

                            $manager = $status ? $id : -1;
                        }
                        $sqli->query("INSERT INTO `viber_bot` SET `userid` = {$user['id']}, `message` = '{$sqli->real_escape_string($event->getMessage()->getText())}', `time` = NOW(), `manager` = $manager, `status` = $status");
                        if ($status)
                        {
                            // пересылка сообщения менеджеру
                            $log->info($receiverId . ' message "'.addslashes($event->getMessage()->getText()).'" resent to manager ' . $manager);
                            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setTrackingData($sqli->insert_id)
                                ->setText($name.': "'.$event->getMessage()->getText().'"')
                            );
                        }
                        else
                            $log->info($receiverId . ' message "'.addslashes($event->getMessage()->getText()).'" queued' . ($manager > -1 ? ' to manager ' . $manager : ''));

                    }
                }
                else
                { // клиент не найден, необходимо связать аккаунты viber и магазина
                    $log->info($receiverId . ' unknown receiver');
                    $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText('Ваш viber не связан с вашим аккаунтом в нашем магазине. Пожалуйста, вернитесь сюда по кнопке-ссылке "viber" в уголке пользователя на сайте https://voron.ua')
                    );
                }
            }
        })

        ->run();

}
catch (Exception $e) {
    $log->warning('Exception: ' . $e->getMessage());
    if ($bot)
    {
        $log->warning('Actual sign: ' . $bot->getSignHeaderValue());
        $log->warning('Actual body: ' . $bot->getInputBody());
    }
}
