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
    if (isset($user['email']))
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
    global $online_dir;
    $fl = fopen($online_dir . 'log/bot.log', 'a');
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
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Мои туры')
        ->setSilent(true)
        ->setText('Туры'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Мои команды')
        ->setSilent(true)
        ->setText('Команды'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Выбор сезона')
        ->setSilent(true)
        ->setText('Сезоны'),
];
/*
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(3)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('open-url')
        ->setActionBody('https://fprognoz.org')
        ->setSilent(true)
        ->setText('Открыть сайт'),
*/
function getMyCommands($email) {
    global $data_dir;

    $db = []; // база данных команд, сгруппированная по ассоциациям
    $access = file($data_dir . 'auth/.access', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($access as $access_str)
    {
        list($code, $as_code, $team, $name, $mail, $pwd, $role) = explode(';', $access_str);
        if ($mail == $email)
            $db[$as_code][] = ['cod' => $code, 'cmd' => $team];

    }
    return $db;
}

function current_season($y, $m, $cc) {
    if ($cc == 'SUI')
        return '2020-2';
    else if ($m < 9)
        $y--;

    return $y . '-' . (substr($y, 2) + 1);
}

function getMyTours($email) {
    //global $ccn;
    global $online_dir;

    $cmd_db = getMyCommands($email);
    $currentTime = time();
    $statusColor = ['0' => 'noplay', '1' => 'toolate', '2' => 'alarm', '3' => 'absent', '4' => 'playing', '5' => 'present', '6' => 'result'];
    $tudb = $tout = [];
    $startTime = $currentTime - 259200; // - 3 day
    $startDay = date('d', $startTime);
    $startMonth = date('m', $startTime);
    $startYear = date('Y', $startTime);
    $sched[0] = $startYear . '/' . $startMonth;
    $sched[1] = ($startMonth == 12) ? ($startYear + 1) . "/01" : sprintf("%4d/%02d", $startYear, $startMonth + 1);
//    $world = file_get_contents($online_dir . 'UNL/'.$startYear.'/codes.tsv');
//    $final = file($online_dir . 'UNL/'.$startYear.'/final', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    for ($nm = 0; $nm <= 1; $nm++)
        if (is_dir($online_dir . 'schedule/' . $sched[$nm]))
        {
            $dir = scandir($online_dir . 'schedule/' . $sched[$nm]);
            foreach ($dir as $fname)
                if ($fname[0] != '.' && ($nm || $fname >= $startDay))
                {
                    $subdir = scandir($online_dir . 'schedule/' . $sched[$nm] . '/' . $fname);
                    foreach ($subdir as $event)
                        if ($event[0] != '.' && !strpos($event, '.resend'))
                        {
                            list($timeStamp, $countryCode, $tourCode, $action) = explode('.', $event);
                            $currentSeason = current_season($startYear, $startMonth, $countryCode);

//if (in_array($tourCode, ['SUI09']))
//  $currentSeason = '2019-4';

// World
//          if ($countryCode == 'UNL' && $action == 'remind' && strpos($world, $_SESSION['Coach_name']) !== false)
                            if ($countryCode == 'UNL' && $action == 'remind')
                            { // танцуют ВСЕ!!!
                                $tour_dir = $online_dir . 'UNL/' . $startYear . '/prognoz/' . $tourCode;
                                if (is_file($tour_dir . '/published'))
                                    $status = 6; // завершён
                                else if (is_file($tour_dir.'/closed'))
                                    $status = 4; // играется
//            else if ($tourCode > 'UNL11' && !in_array($_SESSION['Coach_name'], $final))
//              $status = 0; // не участвует
                                else if (strpos("\n" . file_get_contents($tour_dir.'/mail'), "\n" . $name . ';') !== false)
                                    $status = 5; // есть прогноз
                                else
                                    $status = ($timeStamp <= $currentTime + 86400) ? 2 : 3; // нет прогноза

                                $tout[] = [$tourCode, $status]; //, 'a=world&s=' . $startYear . '&t=' . substr($tourCode, 3) . '&m=' . ($status == 6 ? 'result' : 'prognoz')];
                            }
                            $itFName = $online_dir . $countryCode . '/' . $currentSeason . '/publish/';
                            if ($countryCode == 'SFP')
                                $itFName .= substr($tourCode, 0, 3) . '/it' . substr($tourCode, -2);
                            else if ($tourCode[4] == 'L')
                                $itFName .= substr($tourCode, 0, 5).'/itc'.substr($tourCode, -2);
                            else
                                $itFName .= 'it'.strtolower(substr($tourCode, 3));

                            $tour_dir = $online_dir . $countryCode . '/' . $currentSeason . '/prognoz/' . $tourCode;
                            if ($countryCode != 'UNL' && isset($cmd_db[$countryCode]))
                            {
                                $team_str = $cmd_db[$countryCode][0][$countryCode == 'SFP' ? 'cmd' : 'cod'];
                                if (is_file($itFName))
                                    $tudb[$countryCode][$tourCode] = 6; // 6 - опубликованы итоги
                                // ФП совпала, итогов еще нет, надо проверить, есть ли команда в программке тура
                                else if (!isset($tudb[$countryCode][$tourCode]))
                                { // первое упоминание тура
                                    $content = file_get_contents($online_dir . $countryCode . '/' . $currentSeason . '/programs/' . $tourCode);
                                    $content = substr($content, strpos($content, 'Контрольный с'));
                                    if ($countryCode != 'SUI' && !strpos($content, $cmd_db[$countryCode][0]['cmd']))
                                    {
                                        if ($tourCode[4] != 'L')
                                            $tudb[$countryCode][$tourCode] = 0; // 0 - неучастие

                                    }
                                    elseif (is_file($tour_dir . '/closed'))
                                    {
                                        $content = "\n" . file_get_contents($tour_dir . '/mail');
                                        if (strpos($content, "\n" . $cmd_db[$countryCode][0]['cod'] . ';') === false)
                                        {
                                            if (is_file($tour_dir . '/adds'))
                                            {
                                                $content = "\n" . file_get_contents($tour_dir . '/adds');
                                                if (strpos($content, "\n" . $cmd_db[$countryCode][0]['cmd'] . ' ') === false)
                                                    $tudb[$countryCode][$tourCode] = 1; // 1 - прогноза нет и больше не принимаются
                                                else
                                                    $tudb[$countryCode][$tourCode] = 4; // 4 - прогноз найден в дополнениях
                                            }
                                            else
                                                $tudb[$countryCode][$tourCode] = 1; // 1 - прогноза нет и больше не принимаются

                                        }
                                        else
                                            $tudb[$countryCode][$tourCode] = 4; // 4 - прогноз найден в почте

                                    }
                                    else
                                    {
                                        $content = is_file($tour_dir.'/mail') ? "\n" . file_get_contents($tour_dir . '/mail') : '';
                                        if ((strpos($content, "\n" . $cmd_db[$countryCode][0]['cod'] . ';') === false) && (strpos($content, "\n" . $team_str.';') === false))
                                        {
                                            $content = is_file($tour_dir . '/adds') ? "\n".file_get_contents($tour_dir.'/adds') : '';
                                            if (strpos($content, "\n" . $cmd_db[$countryCode][0]['cmd'].' ') === false)
                                            {
                                                if ($action == 'remind' && $timeStamp <= $currentTime + 86400 || is_file($tour_dir.'/published'))
                                                    $tudb[$countryCode][$tourCode] = 2; // 2 - прогноза нет и уже горит
                                                else
                                                    $tudb[$countryCode][$tourCode] = 3; // 3 - прогноза нет

                                            }
                                            else
                                                $tudb[$countryCode][$tourCode] = is_file($tour_dir.'/published') ? 4 : 5; // прогноз найден в дополнениях

                                        }
                                        else
                                            $tudb[$countryCode][$tourCode] = is_file($tour_dir.'/published') ? 4 : 5; // прогноз найден в почте

                                    }
                                }
                                else if ($tudb[$countryCode][$tourCode] == 3 && ($action == 'remind' && $timeStamp <= $currentTime + 86400 || is_file($tour_dir.'/published')))
                                    $tudb[$countryCode][$tourCode] = 2; // 2 - прогноза нет и уже горит

                            }
                        }

                }

        }
        $prev_fp = '';
        foreach (['SFP', 'BLR', 'ENG', 'ESP', 'FRA', 'GER', 'ITA', 'NLD', 'PRT', 'RUS', 'SCO', 'UKR', 'SUI', 'UEFA'] as $countryCode)
            if (isset($cmd_db[$countryCode]))
            {
                $currentSeason = current_season($startYear, $startMonth, $countryCode);
//if (in_array($tourCode, ['SUI09']))
//  $currentSeason = '2019-4';
                $ll = $cmd_db[$countryCode][0]['cmd'];
                $prev_fp = $countryCode;
                if (isset($tudb[$countryCode]))
                    foreach ($tudb[$countryCode] as $tcode => $status)
                    {
                        if (strlen($tcode) > 3 && $tcode[4] == 'L')
                        {
                            $cclen = 5;
                            $ll = '&l='.substr($tcode, 0, $cclen);
                        }
                        else if ($countryCode == 'SFP')
                        {
                            $cclen = 3;
                            $ll = ($ll == 'PRO' || $ll == 'FFP' || $ll == 'TOR' || $ll == 'SPR') ? $ll = '&l=' . substr($tcode, 0, $cclen) : '&';
                        }
                        else
                        {
                            $cclen = 3;
                            $ll = '';
                        }
                        $linktext = $status != 6 ? 'prognoz' : 'text&ref=it';
//if (in_array($tcode, ['SUI09']))
//  $currentSeason = '2019-4';
//else 
if ($countryCode == 'SUI')
    $currentSeason = '2020-2';

                        if ($ll != '&' && ($status != 0 || $countryCode != 'SFP'))
                            $tout[] = [$tcode, $status]; //, 'a=' . strtolower($ccn[$countryCode]) . '&c=' . $c . $ll . '&s=' . $currentSeason . '&m=' . $linktext . '&t=' . strtolower(substr($tcode, $cclen))];

                    }

            }

    return $tout;
}

function getCC($tour) {
    $cc = substr($tour, 0, 3);
    switch ($cc)
    {
        case 'PRO': $cc = 'SFP'; break;
        case 'SPR': $cc = 'SFP'; break;
        case 'TOR': $cc = 'SFP'; break;
        case 'FFP': $cc = 'SFP'; break;
        case 'GOL': $cc = 'UEFA'; break;
        case 'CHA': $cc = 'UEFA'; break;
        case 'CUP': $cc = 'UEFA'; break;
        case 'UEF': $cc = 'UEFA'; break;
    }
    return $cc;
}

function getProgram($tour) {
    global $online_dir;

    $cc = getCC($tour);
    return file_get_contents($online_dir . $cc . '/' . current_season(date('Y'), date('m'), $cc) . '/programs/' . $tour);
}

function getSeasons($cc) {
    global $online_dir;

    $dir = scandir($online_dir . $cc, SCANDIR_SORT_DESCENDING);
    $i = 0;
    $cards = [];
    foreach ($dir as $season)
        if (is_dir($online_dir . $cc . '/' . $season) && $season[0] == '2' && $i++ < 14)
            $cards[] = (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('season:'.$cc.':'.$season)
                    ->setSilent(true)
                    ->setText($season)
                    ->setTextHAlign('left');

    return $cards;
}

function myTours($receiverId) {
    global $data_dir;

    $colors = ['#dcdcdc', '#ff0000', '#ff4500', '#ffff00', '#00ffff', '#00ff7f', '#f5f5f5'];
    $user = getUser($receiverId);
    $tout = getMyTours($user['email']);
    $cards = [];
    foreach ($tout as $tour)
        $cards[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns(2)
            ->setRows(1)
            ->setBgColor($colors[$tour[1]])
            ->setTextSize('large')
            ->setActionType('reply')
            ->setActionBody('тур:' . $tour[0])
            ->setSilent(true)
            ->setText($tour[0])
            ->setTextHAlign('left');

    return $cards;
}

function myTeams($receiverId) {
    global $data_dir;

    $user = getUser($receiverId);
    $cmd_db = getMyCommands($user['email']);
    $out = '';
    foreach ($cmd_db as $cc => $teams)
        foreach ($teams as $team)
            $out .= $team['cmd'] . ' (' . $cc . ')
';
    return $out;
}

function predictSave($receiverId, $tour, $prognoz) {
    global $online_dir;

    $time = time();
    $user = getUser($receiverId);
    $cc = getCC($tour);
    $s = current_season(date('Y', $time), date('m', $time), $cc);
    $tour_dir = $online_dir . $cc . '/' . $s . '/prognoz/' . $tour;
    if (!is_file($tour_dir . '/term') || file_get_contents($tour_dir . '/term') >= $time)
    {
        if (!is_dir($tour_dir))
            mkdir($tour_dir, 0755, true);

        $user = getUser($receiverId);
        $cmd_db = getMyCommands($user['email']);
        $out = '';
        if ($cut = stripos($prognoz, ' pen'))
        {
            $pena = trim(substr($prognoz, $cut));
            $prognoz = strtr(trim(substr($prognoz, 0, $cut)), ['х' => 'X', 'Х' => 'X', 'x' => 'X']);
        }
        else
            $pena = '';

        foreach ($cmd_db[$cc] as $team)
            $out .= $team['cod'] . ';' . $prognoz . ';' . $time . ';' . $pena . "\n";

        $pfile = fopen($tour_dir . '/mail', 'a');
        fwrite($pfile, $out);
        fclose($pfile);
        $report = '
В основной части - ';
        list($main, $add) = explode(' ', $prognoz);
        $stat = count_chars($main);
        $m = $stat['0'] + $stat['1'] + $stat['2'] + $stat['X'];
        if ($stat['('] == 1 && $stat[')'] == 1)
            $report .= ($m - 1) . ' ставок с одной подстраховкой';
        else
            $report .= $m . ' ставок';

        if ($stat['='])
            $report .= ' и ' . $stat['='] . 'пропусков';

        $stat = count_chars($add);
        $m = $stat['0'] + $stat['1'] + $stat['2'] + $stat['X'];
        $report .= '
В дополнительной части - ' . $m . ' ставок';
        if ($stat['<'])
            $report .= ', гостевая подстраховка';

        if ($stat['='])
            $report .= ' и ' . $stat['='] . 'пропусков';

        if ($pena)
            $report .= '
Указаны пенальти - ' . $pena;

        $responce = 'Принят прогноз ' . $prognoz . ' на ' . $tour . $report;
    }
    else
        $responce = 'Прогноз не принят. Возможно, уже наступил дедлайн.';

    return $responce;
}

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
            if (!($user = getUser($receiverId)))
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

        ->onText('|Мои туры|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            addToLog($receiverId, '"Мои туры" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns(6)
                ->setButtonsGroupRows(7)
                ->setButtons(myTours($receiverId))
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Мои команды|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            $tour = substr($event->getMessage()->getText(), 7);
            addToLog($receiverId, '"Мои команды" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText(myTeams($receiverId))
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Выбор сезона|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            addToLog($receiverId, '"Выбор сезона" in the message');
            $cards = [
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:UNL')
                    ->setSilent(true)
                    ->setText('Лига Наций')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:BLR')
                    ->setSilent(true)
                    ->setText('Беларусь')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:UEFA')
                    ->setSilent(true)
                    ->setText('ФП УЕФА')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:FRA')
                    ->setSilent(true)
                    ->setText('Франция')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:ENG')
                    ->setSilent(true)
                    ->setText('Англия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:NLD')
                    ->setSilent(true)
                    ->setText('Голландия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:ESP')
                    ->setSilent(true)
                    ->setText('Испания')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:PRT')
                    ->setSilent(true)
                    ->setText('Португалия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:GER')
                    ->setSilent(true)
                    ->setText('Германия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:RUS')
                    ->setSilent(true)
                    ->setText('Россия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:ITA')
                    ->setSilent(true)
                    ->setText('Италия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:SCO')
                    ->setSilent(true)
                    ->setText('Шотландия')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:SUI')
                    ->setSilent(true)
                    ->setText('Швейцария')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns(3)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('assoc:UKR')
                    ->setSilent(true)
                    ->setText('Украина')
                    ->setTextHAlign('left'),
            ];
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns(6)
                ->setButtonsGroupRows(7)
                ->setButtons($cards)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|тур:|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            $tour = substr($event->getMessage()->getText(), 7);
            addToLog($receiverId, '"тур:" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setTrackingData('прогноз:' . $tour)
                ->setText(getProgram($tour) . 'Сейчас Вы можете отправить строку прогноза на этот тур. Если у Вас несколько команд в лиге ФП УЕФА, прогноз будет принят для всех команд этой лиги.')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|assoc:|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            $cc = substr($event->getMessage()->getText(), 6);
            addToLog($receiverId, '"assoc:' . $cc . '" in the message');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns(6)
                ->setButtonsGroupRows(7)
                ->setButtons(getSeasons($cc))
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

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
        ->onText('|.*|s', function ($event) use ($bot, $botSender, $buttons) {
            $receiverId = $event->getSender()->getId();
            if ($id = $event->getMessage()->getTrackingData())
            {
                if (substr($id, 0, 15) == 'прогноз:')
                { // получение прогноза
                    $responce = predictSave($receiverId, substr($id, 15), $event->getMessage()->getText());
                    addToLog($receiverId, 'прогноз ' . $event->getMessage()->getText());
                    $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText($responce)
                        ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
                    );
                }
            }
        })
        ->run();
}
catch (Exception $e) {
}
