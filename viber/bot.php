<?php
require_once '/home/fp/vendor/autoload.php';
require_once '/home/fp/data/config.inc.php';
use Viber\Bot;
use Viber\Api\Sender;

$users = [];
foreach (file($data_dir.'auth/.viber', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line)
{
    list($id, $email) = explode(';', $line);
    $users[$id] = $email;
}

// reply name
$botSender = new Sender([
    'name' => 'FPrognoz.Org Bot',
    'avatar' => 'https://fprognoz.org/images/sfp.jpg',
]);

try {
    // create bot instance
    $bot = new Bot(['token' => $viber_api_key]);
    $bot
        // first interaction with bot - return "welcome message"
        ->onConversation(function ($event) use ($bot, $botSender) {
            global $data_dir;
            global $users;
            if (isset($users[$event->getUser()->getId()]))
            {
file_put_contents('log', var_export($event->getUser(), true));
            }
            else if ($event->getContext())
            {
                $users[$event->getUser()->getId()] = $event->getContext();
                $out = '';
                foreach ($users as $id => $email)
                    $out .= $id.';'.$email."\n";

                file_put_contents($data_dir.'auth/.viber', $out);
$log = 'onConversation: ' . var_export($event, true);
file_put_contents('log', $log);
            }
            else
            {
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Пожалуйста, зайдите сюда через ссылку "Открыть Viber Bot" в личном кабинете на сайте https://fprognoz.org');
            }
            // авторизоваться
            // показать кнопки туров и ассоциаций
            $buttons = [];
            for ($i = 0; $i <= 8; $i++) {
                $buttons[] =
                    (new \Viber\Api\Keyboard\Button())
                        ->setColumns(1)
                        ->setActionType('reply')
                        ->setActionBody('k' . $i)
                        ->setText('k' . $i);
            }
            return (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText("Hi, you can see some demo: send 'k1' or 'k2' etc.")
                ->setKeyboard(
                (new \Viber\Api\Keyboard())
                    ->setButtons($buttons)
            );
        })
        // when user subscribe to PA
        ->onSubscribe(function ($event) use ($bot, $botSender) {
//            $log->info('onSubscribe handler');
            $this->getClient()->sendMessage(
                (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Thanks for subscription!')
            );
        })
        ->onText('|btn-click|s', function ($event) use ($bot, $botSender) {
//            $log->info('click on button');
            $log = 'onClick: ' . var_export($event, true);
file_put_contents('log', $log);
            $receiverId = $event->getSender()->getId();
            $bot->getClient()->sendMessage(
                (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($receiverId)
                    ->setText('you press the button')
            );
        })
        ->onText('|k\d+|is', function ($event) use ($bot, $botSender) {
            $caseNumber = (int)preg_replace('|[^0-9]|s', '', $event->getMessage()->getText());
//            $log->info('onText demo handler #' . $caseNumber);
            $client = $bot->getClient();
            $receiverId = $event->getSender()->getId();
            switch ($caseNumber) {
                case 0:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText('Basic keyboard layout')
                            ->setKeyboard(
                                (new \Viber\Api\Keyboard())
                                    ->setButtons([
                                        (new \Viber\Api\Keyboard\Button())
                                            ->setActionType('reply')
                                            ->setActionBody('btn-click')
                                            ->setText('Tap this button')
                                    ])
                            )
                    );
                    break;
                //
                case 1:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText('More buttons and styles')
                            ->setKeyboard(
                                (new \Viber\Api\Keyboard())
                                    ->setButtons([
                                        (new \Viber\Api\Keyboard\Button())
                                            ->setBgColor('#8074d6')
                                            ->setTextSize('small')
                                            ->setTextHAlign('right')
                                            ->setActionType('reply')
                                            ->setActionBody('btn-click')
                                            ->setText('Button 1'),

                                        (new \Viber\Api\Keyboard\Button())
                                            ->setBgColor('#2fa4e7')
                                            ->setTextHAlign('center')
                                            ->setActionType('reply')
                                            ->setActionBody('btn-click')
                                            ->setText('Button 2'),

                                        (new \Viber\Api\Keyboard\Button())
                                            ->setBgColor('#555555')
                                            ->setTextSize('large')
                                            ->setTextHAlign('left')
                                            ->setActionType('reply')
                                            ->setActionBody('btn-click')
                                            ->setText('Button 3'),
                                    ])
                            )
                    );
                    break;
                //
                case 2:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Contact())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setName('Novikov Bogdan')
                            ->setPhoneNumber('+380000000000')
                    );
                    break;
                //
                case 3:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Location())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setLat(48.486504)
                            ->setLng(35.038910)
                    );
                    break;
                //
                case 4:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Sticker())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setStickerId(114408)
                    );
                    break;
                //
                case 5:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Url())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setMedia('https://hcbogdan.com')
                    );
                    break;
                //
                case 6:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Picture())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText('some media data')
                            ->setMedia('https://developers.viber.com/img/devlogo.png')
                    );
                    break;
                //
                case 7:
                    $client->sendMessage(
                        (new \Viber\Api\Message\Video())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setSize(2 * 1024 * 1024)
                            ->setMedia('http://techslides.com/demos/sample-videos/small.mp4')
                    );
                    break;
                //
                case 8:
                    $client->sendMessage(
                        (new \Viber\Api\Message\CarouselContent())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setButtonsGroupColumns(6)
                            ->setButtonsGroupRows(6)
                            ->setBgColor('#FFFFFF')
                            ->setButtons([
                                (new \Viber\Api\Keyboard\Button())
                                    ->setColumns(6)
                                    ->setRows(3)
                                    ->setActionType('open-url')
                                    ->setActionBody('https://www.google.com')
                                    ->setImage('https://i.vimeocdn.com/portrait/58832_300x300'),

                                (new \Viber\Api\Keyboard\Button())
                                    ->setColumns(6)
                                    ->setRows(3)
                                    ->setActionType('reply')
                                    ->setActionBody('https://www.google.com')
                                    ->setText('<span style="color: #ffffff; ">Buy</span>')
                                    ->setTextSize("large")
                                    ->setTextVAlign("middle")
                                    ->setTextHAlign("middle")
                                    ->setImage('https://s14.postimg.org/4mmt4rw1t/Button.png')
                            ])
                    );
                    break;
            }
        })
        ->run();
} catch (Exception $e) {
/*
    $log->warning('Exception: ' . $e->getMessage());
    if ($bot) {
        $log->warning('Actual sign: ' . $bot->getSignHeaderValue());
        $log->warning('Actual body: ' . $bot->getInputBody());
    }
*/
}
