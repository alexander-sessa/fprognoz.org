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
    'KQpozjzZNDCJFCV4Vpxpog==',
];

# Имя и картинка для сообщений бота

$botSender = new Sender([
    'name' => 'voron',
    'avatar' => 'https://voron.ua/favicon.ico'
]);

# Настройки лога

$log = new Logger('bot');
$log->pushHandler(new StreamHandler('viber/bot.log', Logger::DEBUG));

# Кнопки главного меню

$buttons = [
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Пoиcк')
        ->setSilent(true)
        ->setText('Пoиcк'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Kopзина')
        ->setSilent(true)
        ->setText('Kopзина'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Зaкaзы')
        ->setSilent(true)
        ->setText('Зaкaзы'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Зaклaдки')
        ->setSilent(true)
        ->setText('Зaклaдки'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Maгaзин')
        ->setSilent(true)
        ->setText('Maгaзин'),
    (new \Viber\Api\Keyboard\Button())
        ->setColumns(2)
        ->setRows(1)
        ->setBgColor('#2fa4e7')
        ->setTextSize('large')
        ->setActionType('reply')
        ->setActionBody('Cпpaвкa')
        ->setSilent(true)
        ->setText('Cпpaвкa'),
];

#
# Формирование "карусельной" карточки товара для показа Закладок и результатов поиска
#

function productCard($product, $user, $columns, $rows, $replacement=false) {
    global $product_memory;

    // проверка на дубли в заменах
    if ($replacement && isset($product_memory) && $product_memory && array_search($product['id'], $product_memory) !== false)
        return false;

    $rowImage = 4;
    $rowText = 2;
    $card = $arr_replace = [];
    $sale = product_sale($product);
    $status = $user['status'];
    $stock = $product['stock'] + $product['stock_dp'];
    if ($replacement && $product['replacements'])
        foreach (explode(';', $product['replacements']) as $prod)
            if (($prod = get_product($prod)) && $prod['stock'] + $prod['stock_dp'] > 0)
                $arr_replace[] = $prod;

    if ($stock <= 0 && !sizeof($arr_replace))
        return false;

    $product['norma_otpuska'] = max(1, $product['norma_otpuska']);
    $cena = $kolvo = [];
    if (PriceNotEmpty($product['price_roznica']))
    { // "находим" товар, только если у него установлена розничная цена
        $product_memory[] = $product['id'];

//             = product_price_correct($product); // коррекция цен
        $price = strtr($product['price_roznica'], ['.' => ',']);

        if ($status == USER_STATUS_SUPER_OPT || $status == USER_STATUS_EXTRA_OPT)
        {
            $price = shop_product_get_price_value($product, 1, $status);
            $price = PriceNotEmpty($price) ? $price : '???,??'; //_format_price(,'$')
            $kolvo[] = [$status == USER_STATUS_SUPER_OPT ? 'user_super' : 'user_extra', '', ''];
            $cena[] = [$price, '', ''];
        }
        else
        {
            $cena[] = strtr(round($product['price_roznica'] * $product['norma_otpuska'] + 0.004999, 2), ['.' => ',']);
            $kolvo[] = $product['norma_otpuska'];
            if (!PriceNotEmpty($product['price_opt']) || $stock < $product['opt_amount'])
            {
                //$cena[] = strtr(round($product['price_roznica'] * 2 * $product['norma_otpuska'] + 0.004999, 2), ['.' => ',']);
                $cena[] = strtr($product['price_roznica'], ['.' => ',']);
                $kolvo[] = 2 * $product['norma_otpuska'];
            }
            if (PriceNotEmpty($product['price_small_opt']) && $stock >= $product['small_opt_amount'])
            {
                //$cena[] = strtr(round($product['price_small_opt'] * $product['small_opt_amount'] + 0.004999, 2), ['.' => ',']);
                $cena[] = strtr($product['price_small_opt'], ['.' => ',']);
                $kolvo[] = $product['small_opt_amount'];
            }
            else
            {
                //$cena[] = strtr(round($product['price_roznica'] * 5 * $product['norma_otpuska'] + 0.004999, 2), ['.' => ',']);
                $cena[] = strtr($product['price_roznica'], ['.' => ',']);
                $kolvo[] = 5 * $product['norma_otpuska'];
            }
            if (PriceNotEmpty($product['price_opt']) && $stock >= $product['opt_amount'])
            {
                //$cena[] = strtr(round($product['price_opt'] * $product['opt_amount'] + 0.004999, 2), ['.' => ',']);
                $cena[] = strtr($product['price_opt'], ['.' => ',']);
                $kolvo[] = $product['opt_amount'];
            }
        }
/*
        if ($sale)
        {
 sales_get_name($sale)
        }
  // цвет поискового запроса
  $name = html_product_name($product);
  getCoverField($product['cover'], 'name')
  getManufacturerField($product['manufacturer'], 'name')
        if ($amount = shop_basket_get_amount($product['id']))
        {
            $style = "in-basket-search";
            $amount .= " шт";
        }
        else
        {
            $style = "no-basket-search";
            $amount = "";
        }
//    $amount
*/
        $link = 'https://voron.ua/catalog/'.product_pretty_url($product);
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns($rowImage)
            ->setRows($rowImage)
            ->setActionType('open-url')
            ->setActionBody($link)
            //->setImageScaleType('fit')
            ->setSilent(true)
            ->setImage('https://voron.ua'.$product['picture_small']);
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns($columns - $rowImage)
            ->setRows($rowImage)
            ->setActionType('open-url')
            ->setActionBody($link)
            ->setSilent(true)
            ->setText('код товара: <b>'.$product['id'].'</b>')
            ->setTextSize('small')
            ->setTextVAlign('top')
            ->setTextHAlign('left');
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns($columns)
            ->setRows($rowText)
            ->setActionType('open-url')
            ->setActionBody($link)
            ->setText('<font color=#000000><b>'.htmlspecialchars(product_name($product)).'</b></font><br><font color=#ff3333>'.$price.($status == USER_STATUS_SUPER_OPT || $status == USER_STATUS_EXTRA_OPT ? '$' : ' грн').'</font><br><font color=#228b22>В наличии: <b>'.$stock.'</b></font>')
            ->setSilent(true)
            ->setTextSize('small')
            ->setTextVAlign('top')
            ->setTextHAlign('left');
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns(2)
            ->setRows(1)
            ->setActionType('reply')
            ->setActionBody('Kopзина'.':'.$product['id'].':'.$kolvo[0])
            ->setBgColor('#e0ffff')
            //->setBgMediaType('picture')
            //->setBgMedia('https://voron.ua/img/icons.png')
            //->setImage('https://voron.ua/viber/cart-plus-64x64.png')
            ->setText('<b>+ '.$kolvo[0].' x<br><font color=#ff3333>'.$cena[0].(strlen($cena[0]) < 6 ? ' грн' : '').'</font></b>')
            ->setSilent(true)
            ->setTextSize('small')
            ->setTextVAlign('middle')
            ->setTextHAlign('left');
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns(2)
            ->setRows(1)
            ->setActionType('reply')
            ->setActionBody('Kopзина'.':'.$product['id'].':'.$kolvo[1])
            ->setBgColor('#afeeee')
            ->setText('<b>+ '.$kolvo[1].' x<br><font color=#ff3333>'.$cena[1].(strlen($cena[1]) < 6 ? ' грн' : '').'</font></b>')
            ->setSilent(true)
            ->setTextSize('small')
            ->setTextVAlign('middle')
            ->setTextHAlign('left');
        $card[] = (new \Viber\Api\Keyboard\Button())
            ->setColumns(2)
            ->setRows(1)
            ->setActionType('reply')
            ->setActionBody('Kopзина'.':'.$product['id'].':'.$kolvo[2])
            ->setBgColor('#add8e6')
            ->setText('<b>+ '.$kolvo[2].' x<br><font color=#ff3333>'.$cena[2].(strlen($cena[2]) < 6 ? ' грн' : '').'</font></b>')
            ->setSilent(true)
            ->setTextSize('small')
            ->setTextVAlign('middle')
            ->setTextHAlign('left');
    }
    if ($replacement)
        foreach ($arr_replace as $prod)
            $card = array_merge($card, productCard(get_product($prod), $user, $columns, $rows, true));

    return $card;
}

#
# Показ результатов поиска в виде карточек товаров, сгруппированных по 6 в "карусельный" ряд
#

function showSearch($search, $userid, $columns, $rows) {
    global $sqli;

    $cards = $carousel = [];
    $used_cards = 0;
    $query_max_limit = 25;
    if (strlen(trim($search)) >= 3)
    {
        $res = $sqli->query("SELECT `id`, `status` FROM `users` WHERE `viber` LIKE '{$userid}' ORDER BY `id` DESC LIMIT 1");
        $user = $res->fetch_assoc();
        $search = trim(mb_strtolower($search, 'UTF-8'));
        $search_regexp = false;
        if (preg_match("/^reg\:/i", $search))
            $search_regexp = ['reg', preg_replace('/^[a-z]+\:/i', '', $search)];
        else
        {
            $original_search = $search; // нужна для проверки кода товара
            $search = strtr($search, ['*'=>' ', '?'=>'_', '-'=>' ', ','=>'.', 'ё'=>'е', '('=>' ', ')'=>' ']);
            $escape = '';
            if (strpos($search, '%') !== false)
            {
                $escape = " ESCAPE '|' ";
                $search = str_replace('|', '||', $search);
                $search = str_replace('%', '|%', $search);
            }
            $words = preg_split('/\s+/', $search); // разбиваем строку
            // разбор запроса
            $search_find = search_result_product($search);
            $word_where = $search_find['word'];
            $word_sort = $search_find['sort'];
            // поисковый запрос
            $sql_word = $sqli->real_escape_string(implode('%', $words));
            $sql_word_likes = implode(' AND ', $word_where) . ($sql_word_likes ? $escape : '');
        }
        // товар с кодом показываем по любому
        if (preg_match('/^pr [0-9]{3}$/i',$search)) //код товара "PR-***": надо восстановить "-" только для трёх цифр после PR
            $original_search = preg_replace('/^(pr) ([0-9]+)$/i', '$1-$2', $original_search);

        $original_search = preg_replace('/(^кт)([0-9]{4})/', 'KT$2', $original_search); // некоторые набирают КТ кириллицей, помогаем им найти
        if (preg_match('/^((pr-|pr|kt|sv)?[0-9]{3,6})|(^ms-\\S{3,})$/i', $original_search, $z)) // только от 3 до 6-ти цифр (MS-исключение)
            // если цифр меньше 6, забиваем начало нулями
            $product_code = get_product(is_numeric($z[0]) ? str_pad($z[0], CODE_PRODUCT_LIGTH, '0', STR_PAD_LEFT) : $z[0]);

        if ($search_regexp)
            $q = "SELECT * FROM `products` WHERE `group` = 0 AND `search_name_index` REGEXP '".addslashes($search_regexp[1])."' ORDER BY `sorting_stock` DESC LIMIT $query_max_limit";
        else if (count($words) == 1) // запрос
            $q = "SELECT * /*id, prefix, name, search_name_index, sorting_stock*/ FROM `products` WHERE `group` = 0 AND {$sql_word_likes} ORDER BY 0+LOCATE('{$word_sort[0]}', search_name_index) + (7 - sorting_stock )*1000000 LIMIT $query_max_limit";
        else
            $q = "SELECT *, /*id, prefix, name, search_name_index, sorting_stock*/
            @t1 := LOCATE('" . $word_sort[0] . "', search_name_index) as t1,
            @t2 := LOCATE('" . $word_sort[1] . "', search_name_index) as t2,
            @t3 := (0+if((0+@t2)<(0+@t1), @t1+(@t1-@t2)*300, @t1+(@t2-@t1-" . mb_strlen($words[0], "UTF-8") . ")*100)) + ( 7 - sorting_stock )*1000000 as t3
            FROM `products` WHERE `group` = 0 AND {$sql_word_likes} ORDER BY t3 LIMIT $query_max_limit";

        $res = $sqli->query($q);
        if ($res->num_rows)
        {
            if ($card = productCard($product_code, $user, $columns, $rows))
            { // первым идёт вывод найденного по коду товара
                $cards = array_merge($cards, $card);
                $used_cards += sizeof($card) / 6;
            }
            while ($product = $res->fetch_array())
            {
                if (!isset($product_code['id']) || $product['id'] != $product_code['id'])
                    if ($card = productCard($product, $user, $columns, $rows, true))
                    {
                        $cards = array_merge($cards, $card);
                        $used_cards += sizeof($card) / 6;
                    }

                if ($used_cards == 6)
                { // сообщение ограничено 6 блоками, поэтому группируем вывод в группы по 6
                    $carousel[] = $cards;
                    $cards = [];
                    $used_cards = 0;
                }
            }
            if ($cards)
                $carousel[] = $cards;

        }
    }
    if (!sizeof($carousel))
    {
        $carousel[] = [
            (new \Viber\Api\Keyboard\Button())
                ->setColumns(4)
                ->setRows(4)
                ->setActionType('open-url')
                ->setActionBody('https://voron.ua/basket.php')
                ->setSilent(true)
                //->setImageScaleType('fit')
                ->setImage('https://voron.ua/img/shopping_cart.png'),
            (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns)
                ->setRows(3)
                ->setActionType('open-url')
                ->setActionBody('https://voron.ua/blogs.php?tid=464')
                ->setSilent(true)
                ->setText('<b>По Вашему запросу ничего не найдено.<br><font color=#ff6600>Возможно мы сможем Вам помочь!</font></b>')
                ->setTextSize('medium')
                ->setTextVAlign('middle')
                ->setTextHAlign('left')
        ];
    }
    return $carousel;
}

#
# Редактирование товара в корзине. Третий параметр: 0 - удалить товар, другое число - увеличить или уменьшить количество, Z - триггер упаковки
#

function viberBasketEdit($user, $productId, $amount=1) {
    global $sqli;

    $update = false;
    $res = $sqli->query($q="SELECT `id`, `product`, `amount`, `zip_pack` FROM `baskets` WHERE `user`= {$user['id']} AND `product` LIKE '$productId' ORDER BY `id` DESC LIMIT 1");
    if ($res && $res->num_rows)
    {
        $basket = $res->fetch_assoc();
        if ($amount == 'Z')
            return $sqli->query("UPDATE `baskets` SET `zip_pack` = NOT `zip_pack` WHERE `id` = {$basket['id']} LIMIT 1");

        if ($amount == 0)
            $basket['amount'] = 0;
        else
        {
            $basket['amount'] += intval($amount);
            $update = true;
        }
    }
    else
        $basket['amount'] = intval($amount);

    if (shop_products_exists($productId) && $basket['amount'] > 0)
    {
        $product = get_product($productId);
        if ($product['norma_otpuska'] > 1 && $basket['amount'] % $product['norma_otpuska'])
            $basket['amount'] = ceil($basket['amount'] / $product['norma_otpuska']) * $product['norma_otpuska'];

        if ($update)
            $q = "UPDATE `baskets` SET `amount` = {$basket['amount']} WHERE `id` = {$basket['id']} LIMIT 1";
        else
        {
            $zip_pack = $product['pack_type'] && !$user['no_zip_pack'] ? 1 : 0;
            $q = "INSERT INTO `baskets` SET `user` = {$user['id']}, `data` = NOW(), `product` = '$productId', `amount` = {$basket['amount']}, `zip_pack` = $zip_pack";
        }
    }
    else if (isset($basket['id']))
        $q = "DELETE FROM `baskets` WHERE `id` = {$basket['id']} LIMIT 1";

    return $sqli->query($q);
}

#
# Показ корзины в виде "карусельных" карточек. Первая карточка - общая информация о корзине
#

function showBasket($user, $columns, $rows) {
    global $sqli;

    $basket = $cards = $carousel = [];
    $order_zip_pack = $not_prod = $allProductsPrice = 0;
    $used_cards = 1; // первая карта зарезервирована для описания корзины
    $is_sales = $not_filled = false;
    $rowImage = 4;
    $rowText = 2;
    $cur = is_super_user($user) ? '' : ' грн';
    $ukrpost_delivery = is_super_user($user) ? UKRPOST_DELIVERY_USD : UKRPOST_DELIVERY;
    $zip_pack_price = is_super_user($user) ? ZIP_PACK_PRICE_USD : ZIP_PACK_PRICE;
    $res = $sqli->query($q="SELECT `product`, `amount`, `zip_pack` FROM `baskets` WHERE `user`={$user['id']}");
    if ($res && $res->num_rows)
    {
        while ($row = $res->fetch_array())
            if (isset($basket[$row['product']]))
                $basket[$row['product']]['amount'] += $row['amount'];
            else
                $basket[$row['product']] = $row;
    }
    else
        $carousel[0] = [];

    foreach ($basket as $pId =>$pData)
    {
        $product = get_product($pId);
        if ($product)
        {
            $sale = product_sale($product['id']);
            if ($pData['amount'] > $product['stock'] + $product['stock_dp'])
                $not_prod++;

            $link = 'https://voron.ua/catalog/'.product_pretty_url($product);
            //$singlePrice = _format_price(shop_product_get_price($product, $pData['amount'], $user['status']));
            $singlePrice = shop_product_get_price_value($product, $pData['amount'], $user['status']);
            //$productPrice = shop_product_calc_price($product, $pData['amount'], $user['status']);
            $productPrice = $pData['amount'] * shop_product_get_price_value($product, $pData['amount'], $user['status']);
            if ($packs = packing_available($product, $pData['amount']))
            {
                if ($zippack_mandatory = !$user['no_zip_pack'] && is_zippack_mandatory($product['id']))
                    $zip = 'mandatory';
                else
                    $zip = ($pData['zip_pack'] ? '' : 'un') . 'selected';
            }
            else
                $zip = 'no';

            if ($zip == 'mandatory' || $zip == 'selected')
            {
                $zipPack = 'пакет:<br>+'.$zip_pack_price.$cur;
                $allProductsPrice += $zip_pack_price;
            }
            else
                $zipPack = $zip == 'no' ? 'без<br>пакета' : 'сложить<br>в пакет';

            if ($zip == 'mandatory' || $zip == 'no')
            {
                $zipActionType = 'open-url';
                $zipAction = $link;
            }
            else
            {
                $zipActionType = 'reply';
                $zipAction = 'Kopзина'.':'.$product['id'].':Z';
            }
            $allProductsPrice += $productPrice;
            $norma = max(1, $product['norma_otpuska']);
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($rowImage)
                ->setRows($rowImage)
                ->setActionType('open-url')
                ->setActionBody($link)
                ->setSilent(true)
                ->setImage('https://voron.ua'.$product['picture_small']);
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns - $rowImage)
                ->setRows($rowImage - 2)
                ->setActionType('open-url')
                ->setActionBody($link)
                ->setText('Код товара: <b>'.$pId.'</b>')
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('top')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns - $rowImage)
                ->setRows(2)
                ->setActionType($zipActionType)
                ->setActionBody($zipAction)
                ->setImage('https://voron.ua/viber/ip-'.$zip.'.png')
                ->setText($zipPack)
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('bottom')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns)
                ->setRows($rowText)
                ->setActionType('open-url')
                ->setActionBody($link)
                ->setText('<font color=#000000><b>'.htmlspecialchars(product_name($product)).'</b></font><br>'.$pData['amount'].' x <font color=#ff3333>'.$singlePrice.$cur.'</font><br>Сумма: <font color=#ff3333><b>'.$productPrice.$cur.'</b></font>')
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('top')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns(2)
                ->setRows(1)
                ->setActionType('reply')
                ->setActionBody('Kopзина'.':'.$product['id'].':-'.$norma)
                ->setBgColor('#87cefa')
                ->setText('-'.$norma)
                ->setSilent(true)
                ->setTextSize('medium')
                ->setTextVAlign('middle')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns(2)
                ->setRows(1)
                ->setActionType('reply')
                ->setActionBody('Kopзина'.':'.$product['id'].':'.$norma)
                ->setBgColor('#98fb98')
                ->setText('+'.$norma)
                ->setSilent(true)
                ->setTextSize('medium')
                ->setTextVAlign('middle')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns(2)
                ->setRows(1)
                ->setActionType('reply')
                ->setActionBody('Kopзина'.':'.$product['id'].':0')
                ->setBgColor('#ffa07a')
                ->setText('удалить')
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('middle')
                ->setTextHAlign('left');
            if (++$used_cards == 6)
            { // сообщение ограничено 6 блоками, поэтому группируем вывод в группы по 6
                $carousel[] = $cards;
                $cards = [];
                $used_cards = 0;
            }
/* показ акционного товара, последний был в 2016 г., поэтому комментарий
            $sale = product_sale($pId);
            if ($sale['products'])
            {
                foreach ($sale['products'] as $sale_key => $sale_value)
                {
                    $sale_value *= $pData['amount'] / $product['norma_otpuska'];
                    $product_sale = get_product($sale_key);
                    if ($product_sale)
                    {
                        $on_stock = stock_user($product_sale);
                        $allProductsPrice += 0.01*$sale_value;
                    }
                }
            }
*/
        }
    }
    if (sizeof($cards))
    {
        $carousel[] = $cards;
        $cards = [];
    }
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns(1)
        ->setRows(1)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/basket.php')
        ->setSilent(true)
        ->setImage('https://voron.ua/img/shopping_cart.png');
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns - 1)
        ->setRows(1)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/basket.php')
        ->setSilent(true)
        ->setText('В вашей корзине '.sizeof($basket).' товаров на сумму <font color=#ff3333><b>'.$allProductsPrice.$cur.'</b></font>')
        ->setTextSize('small')
        ->setTextVAlign('middle')
        ->setTextHAlign('left');
    if (($cut = strpos($user['comment_basket'], '{"')) !== false)
    {
        $delivery_data = json_decode(substr($user['comment_basket'], $cut), true);
        foreach ($delivery_data as $var => $val)
            //if (substr($var, 0, 8) == 'dostavka')
                $user[$var] = $val;
            //else
            //    $$var = $val; // $payment_method и $cash_on_delivery

        $comment_basket = substr($user['comment_basket'], 0, $cut);
    }
    else
        $comment_basket = $user['comment_basket'];

    $order = $sqli->query("SELECT * FROM `orders` WHERE `user`= {$user['id']} ORDER BY `num` DESC LIMIT 1")->fetch_assoc();
    $times = $order ? time() - 24*60*60 : 0;  // Текущее время -1 сутки;
    $payment_method = '';
    if ($order)
    { // Способ оплаты берем, как последний раз, если не загружен сохраненный в "корзине"
        $payment_method = $user['payment_method'] ?? $order['payment_method'];
        $cash_on_delivery = $user['cash_on_delivery'] ?? $order['cash_on_delivery'];
        if (($order['obrabotka_date'] == 0) || ($order['obrabotka_date'] > $times))
        {
            $payment .= 'Объединить с пред. заказом<br>';
            $cash_ok = ($allProductsPrice + $order['summa_order'] >= MIN_SUMM_CASH_ON_DELIVERY); // Допустим ли наложенный платёж?
            $add_prev_order = true;
        }
    }
    // Если последнего раза не было, учитывается форма собственности из данных клиента
    if (!isset($payment_method))
        $payment_method = ($user['forma_sob'] == 2 || $user['forma_sob'] == 3) ? 2 : 1;

    $payment .= 'Оплата от '.($user['payment_method'] == 1 ? 'частного лица' : 'юридического лица');
    if (!isset($cash_ok))
        $cash_ok = ($allProductsPrice >= MIN_SUMM_CASH_ON_DELIVERY); // Допустим ли наложенный платёж?

    if ($cash_ok && isset($cash_on_delivery) && $cash_on_delivery)
        $payment .= '<br>Наложенный платеж';

    $dostavka = 'Способ доставки: '.carrier_name($user['dostavka']);
    if ($user['dostavka'] != 1)
    {
        $dostavka .= '<br>Адрес: '.user_get_value($user, 'dostavka_punkt').', '.strtr(user_get_value($user, 'dostavka_adress'), ['&quot;' => '"']);
        if (($user['forma_sob'] == 2 || $user['forma_sob'] == 3) && $user['edrpou'])
            $dostavka .= '<br>Предприятие: '.$user['firma'].'<br>Код ЕДРПОУ (ОКПО): '.$user['edrpou'];

    }
    $dostavka .= '<br>'.user_get_value($user, 'dostavka_poluchatel').'<br>Телефон: '.user_get_value($user,'dostavka_telefon');
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns)
        ->setRows($rows - 2)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/basket.php')
        ->setSilent(true)
        ->setText($payment.'<br>'.$dostavka.'<br>'.$comment_basket)
        ->setTextSize('small')
        ->setTextVAlign('middle')
        ->setTextHAlign('left');
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns / 2)
        ->setRows(1)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/basket.php')
        ->setBgColor('#87cefa')
        ->setText(' изменить<br> реквизиты')
        ->setSilent(true)
        ->setTextSize('small')
        ->setTextVAlign('middle')
        ->setTextHAlign('left');
    $actionType = 'open-url';
    $actionBody = 'https://voron.ua/basket.php';
    $bgColor = '#d3d3d3';
    if (in_array($user['dostavka'], [0, 1, 15]))
        $text = 'измените<br>доставку'; // способ доставки не указан или самовывоз
    else if (is_super_user($user) || isset($add_prev_order) || ($allProductsPrice >= MIN_SUMM_ORDER_DELIVERY))
    { // достигнута минимальная сумма заказа?
        $actionType = 'reply';
        $actionBody = 'oфopмить зaкaз';
        $bgColor = '#ff8c00';
        $text = '<font color=#ffffff> оформить<br> заказ</font>';
    }
    else
        $text = 'мин.сумма<br>'.MIN_SUMM_ORDER_DELIVERY.' грн';

    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns / 2)
        ->setRows(1)
        ->setActionType($actionType)
        ->setActionBody($actionBody)
        ->setBgColor($bgColor)
        ->setText($text)
        ->setSilent(true)
        ->setTextSize('small')
        ->setTextVAlign('middle')
        ->setTextHAlign('left');
    $carousel[0] = array_merge($cards, $carousel[0]);
    return $carousel;
}

#
# Оформление заказа
#

function createOrder($user, $columns, $rows) {
    global $sqli;
    global $ORDERS_DATE;

    $cards = [];
    // параметры доставки и оплаты
    if (($cut = strpos($user['comment_basket'], '{"')) !== false)
        $basket = array_merge(['comments' => substr($user['comment_basket'], 0, $cut)], json_decode(substr($user['comment_basket'], $cut), true));
    else
    {
        $basket = $user;
        $basket['comments'] = $user['comment_basket'];
    }
    $error = delivery_validation($basket);
    $res = $sqli->query($q="SELECT `product`, `amount`, `zip_pack` FROM `baskets` WHERE `user`={$user['id']}");
    if (!$res || $res->num_rows == 0)
        $error[] = 'Ваша корзина пуста';

    if ($error)
        return [(new \Viber\Api\Keyboard\Button())
            ->setColumns($columns)
            ->setRows($rows)
            ->setActionType('open-url')
            ->setActionBody('https://voron.ua/basket.php')
            ->setText("Заказ не оформлен из-за ошибки:\n".implode("\n", $error))
            ->setSilent(true)
            ->setTextSize('small')
            ->setTextVAlign('top')
            ->setTextHAlign('left')];

    while ($row = $res->fetch_assoc())
        if (isset($basket['product'][$row['product']]))
            $basket['product'][$row['product']]['amount'] += $row['amount'];
        else
            $basket['product'][$row['product']] = $row;

    $order = shop_order_create($user, $basket, false);
    $delivery_data = json_decode($order->otgruzheno_comments, true);
    foreach ($delivery_data as $var => $val)
        $user[$var] = $val;

    $dostavka = 'Способ доставки: '.carrier_name($user['dostavka']);
    if ($user['dostavka'] != 1)
    {
        $dostavka .= '<br>Населенный пункт: '.user_get_value($user, 'dostavka_punkt').'<br>Адрес: '.strtr(user_get_value($user, 'dostavka_adress'), ['&quot;' => '"']);

        if (($user['forma_sob'] == 2 || $user['forma_sob'] == 3) && $user['edrpou'])
            $dostavka .= '<br>Предприятие: '.$user['firma'].'<br>Код ЕДРПОУ (ОКПО): '.$user['edrpou'];

    }
    $dostavka .= '<br>Получатель: '.user_get_value($user, 'dostavka_poluchatel').'<br>Телефон: '.user_get_value($user,'dostavka_telefon');

    $status = '';
    foreach ($ORDERS_DATE as $date => $command)
    {
        if ($date == 'stop') break;
        if ($order->$date)
            $status .= '<br>'.date(FORMAT_DATE_WO_SEC, $date=='order_date' ? strtotime($order->$date) : $order->$date).' '.$command['commands'];

    }
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns)
        ->setRows($rows - 3)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/user_orders.php?id='.$order->id)
        ->setText('Оформлен <b>ЗАКАЗ № '.$order->number.'</b><br>Оплата от '.
            ($order->payment_method == 1 ? 'частного' : 'юридического').' лица'.
            ($order->cash_on_delivery ? '<br><b>Наложенный платеж</b>' : '').
            ($order->combine ? '<br>Объединён с предыдущим заказом' : '').
            '<br>Сумма заказа: <b>'.$order->summa_order.'</b> '.$order->currency.
            '<br>'.$dostavka)
        ->setSilent(true)
        ->setTextSize('small')
        ->setTextVAlign('top')
        ->setTextHAlign('left');
    $cards[] = (new \Viber\Api\Keyboard\Button())
        ->setColumns($columns)
        ->setRows(3)
        ->setActionType('open-url')
        ->setActionBody('https://voron.ua/user_orders.php?id='.$order->id)
        ->setText($status)
        ->setSilent(true)
        ->setTextSize('small')
        ->setTextVAlign('top')
        ->setTextHAlign('left');
    return $cards;
}

#
# Показ информации о последних (6) заказах в виде карусельных карточек
#

function showOrders($user, $columns, $rows) {
    global $sqli;
    global $ORDERS_DATE;

    $cards = [];
    $res = $sqli->query($q="SELECT * FROM `orders` WHERE `user`={$user['id']} ORDER BY `id` DESC LIMIT 6");
    if ($res && $res->num_rows)
        while ($order = $res->fetch_array())
        {
            $delivery_data = json_decode($order['otgruzheno_comments'], true);
            foreach ($delivery_data as $var => $val)
                $user[$var] = $val;

            $dostavka = 'Способ доставки: '.carrier_name($user['dostavka']);
            if ($user['dostavka'] != 1)
            {
                $dostavka .= '<br>Населенный пункт: '.user_get_value($user, 'dostavka_punkt').'<br>Адрес: '.strtr(user_get_value($user, 'dostavka_adress'), ['&quot;' => '"']);

                if (($user['forma_sob'] == 2 || $user['forma_sob'] == 3) && $user['edrpou'])
                    $dostavka .= '<br>Предприятие: '.$user['firma'].'<br>Код ЕДРПОУ (ОКПО): '.$user['edrpou'];
            }
            $dostavka .= '<br>Получатель: '.user_get_value($user, 'dostavka_poluchatel').'<br>Телефон: '.user_get_value($user,'dostavka_telefon');

            $status = '';
            foreach ($ORDERS_DATE as $date => $command)
            {
                if ($date == 'stop') break;
                if ($order[$date])
                    $status .= '<br>'.date(FORMAT_DATE_WO_SEC, $date=='order_date' ? strtotime($order[$date]) : $order[$date]).' '.$command['commands'];
            }
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns)
                ->setRows($rows - 3)
                ->setActionType('open-url')
                ->setActionBody('https://voron.ua/user_orders.php?id='.$order['id'])
                ->setText('<b>ЗАКАЗ № '.order_num($order).'</b><br>Оплата от '.
                    ($order['payment_method'] == 1 ? 'частного' : 'юридического').' лица'.
                    ($order['cash_on_delivery'] ? '<br><b>Наложенный платеж</b>' : '').
                    ($order['combine'] ? '<br>Объединён с предыдущим заказом' : '').
                    '<br>Сумма заказа: <b>'.$order['summa_order'].'</b> '.$order['currency'].
                    '<br>'.$dostavka)
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('top')
                ->setTextHAlign('left');
            $cards[] = (new \Viber\Api\Keyboard\Button())
                ->setColumns($columns)
                ->setRows(3)
                ->setActionType('open-url')
                ->setActionBody('https://voron.ua/user_orders.php?id='.$order['id'])
                ->setText($status)
                ->setSilent(true)
                ->setTextSize('small')
                ->setTextVAlign('top')
                ->setTextHAlign('left');
        }
    return $cards;
}

#
# Показ товаров в Закладках в виде карусельных карточек
#

function showBookmarks($userid, $columns, $rows) {
    global $sqli;

    $cards = $carousel = [];
    $used_cards = 0;
    $res = $sqli->query("SELECT `id`, `status` FROM `users` WHERE `viber` LIKE '{$userid}' ORDER BY `id` DESC LIMIT 1");
    $user = $res->fetch_assoc();
    $res = $sqli->query($q="SELECT `product` FROM `bookmark` WHERE `user`={$user['id']}");
    while ($bookmark = $res->fetch_array())
        if ($product = get_product($bookmark['product']))
        {
            if ($card = productCard($product, $user, $columns, $rows, false))
            {
                $cards = array_merge($cards, $card);
                $used_cards += sizeof($card) / 6;
            }
            if ($used_cards == 6)
            { // сообщение ограничено 6 блоками, поэтому группируем вывод в группы по 6
                $carousel[] = $cards;
                $cards = [];
                $used_cards = 0;
            }
        }

    if ($cards)
        $carousel[] = $cards;

    return $carousel;
}

#
# Адаптация функции work_hours
#

function workHours() {
    global $sqli;

    $set = $sqli->query("SELECT * FROM `work_hours` WHERE `date` >= '".date('Y-m-d')."' AND `date` <= '".date('Y-m-d', strtotime('+1 week'))."' AND `magazine` = 1 ORDER BY `date`");
    $get = [];
    while ($get_date = $set->fetch_assoc())
    {
        $get[$get_date['date']]['not_works'] = $get_date['not_works'];
        $get[$get_date['date']]['start_hours'] = $get_date['start_hours'];
        $get[$get_date['date']]['end_hours'] = $get_date['end_hours'];
        $get[$get_date['date']]['description'] = $get_date['description'];
    }
    if (count($get) > 0)
    {
        $result = 'График работы магазина:
';
        for ($i = 1; $i <= 7; $i++)
        {
            $date = date('Y-m-d', time() + 60 * 60 * 24 * ($i - 1));
            $date_week = date('N', strtotime($date));
            $date_desc = date('j', strtotime($date)) . ' ' . mb_strtolower(month_number(date('n', strtotime($date)),1), 'UTF-8') . ' (' . mb_strtolower(week_number($date_week), 'UTF-8') . '): ';
            $result .= "\n";
            if ($date_week == 7)
                $result .= $date_desc . 'выходной';
            else if (isset($get[$date]))
            {
                if ($get[$date]['not_works'])
                {
                    $result .= $date_desc . 'выходной';
                    if (!empty($get[$date]['description']))
                        $result .= ' (' . $get[$date]['description'] . ')';

                }
                else
                    $result .= $date_desc . 'c ' . $get[$date]['start_hours'] . ' до ' . $get[$date]['end_hours'];

            }
            else
            {
                if ($date_week == 6)
                    $result .= $date_desc . 'c ' . WORK_HOURS_START . ' до ' . WORK_HOURS_END_SHORT;
                else
                    $result .= $date_desc . 'c ' . WORK_HOURS_START . ' до ' . WORK_HOURS_END;
            }
        }
    }
    else
        $result = 'График работы магазина:
С понедельника по пятницу с 9-00 до 18-00
В субботу с 9-00 до 15-00
Воскресенье: выходной';

    return $result;
}

#
# Обработчики событий
#

try {
    $bot = new Bot(['token' => $config['apiKey']]);
    $bot

# Первый контакт с ботом: связывание аккаунта по id в context

        ->onConversation(function ($event) use ($bot, $botSender, $buttons, $log, $sqli) {
            $receiverId = $event->getUser()->getId();
            $log->info($receiverId . ' onConversation handler');
            $user = NULL;
            $res = $sqli->query("SELECT `id`, `fam`, `name`, `otch` FROM `users` WHERE `viber` LIKE '$receiverId' ORDER BY `id` DESC LIMIT 1");
            if ($res && $res->num_rows)
            { // есть привязка к viber
                $user = $res->fetch_assoc();
            }
            else if ($id = intval($event->getContext()))
            { // привязка по id клиента в context
                $res = $sqli->query("SELECT `id`, `fam`, `name`, `otch` FROM `users` WHERE `id` = $id LIMIT 1");
                if ($res && $res->num_rows)
                {
                    $user = $res->fetch_assoc(); // есть клиент с таким id
                    $sqli->query("UPDATE `users` SET `viber` = '$receiverId' WHERE `id` = $id LIMIT 1");
                }
            }
            if ($user)
            {
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Здравствуйте, '.$user['name'].' '.$user['otch'].'!')
                    ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons));
            }
            else
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText('Ваш viber не связан с вашим аккаунтом в нашем магазине. Пожалуйста, вернитесь сюда по кнопке-ссылке "viber" в уголке пользователя на сайте https://voron.ua');
        })

# Подписка: получение первого сообщения от клиента

        ->onSubscribe(function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getUser()->getId();
            $log->info($receiverId . ' onSubscribe handler');
            $this->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText('Thanks for subscription!')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

# Обработка нажатий на кнопки и ключевых фраз в сообщениях от клиента

        ->onText('|Пoиcк|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Пoиcк"');
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setTrackingData('Пoиcк')
                ->setText('Пожалуйста, введите строку поиска:')
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Kopзина|s', function ($event) use ($bot, $botSender, $buttons, $log, $sqli) {
            $receiverId = $event->getSender()->getId();
            $res = $sqli->query("SELECT * FROM `users` WHERE `viber` LIKE '{$receiverId}' ORDER BY `id` DESC LIMIT 1");
            $user = $res->fetch_assoc();
            if ($event->getMessage()->getText() != 'Kopзина')
            { // обработка, если есть текст после Kopзина
                list($a, $productId, $amount) = explode(':', $event->getMessage()->getText());
                $log->info($receiverId . ' basket edit', ['user' => $user['id'], 'product' => $productId, 'amount' => $amount]);
                viberBasketEdit($user, $productId, $amount);
            }
            else
                $log->info($receiverId . ' click on "Kopзина"');

            $columns = 6;
            $rows = 7;
            $carousel = showBasket($user, $columns, $rows);
            foreach ($carousel as $cards)
                $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                    ->setSender($botSender)
                    ->setReceiver($receiverId)
                    ->setButtonsGroupColumns($columns)
                    ->setButtonsGroupRows($rows)
                    ->setButtons($cards)
                    ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
                );

        })

        ->onText('|Зaкaзы|s', function ($event) use ($bot, $botSender, $buttons, $log, $sqli) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Зaкaзы"');
            $res = $sqli->query("SELECT `id`, `status` FROM `users` WHERE `viber` LIKE '{$receiverId}' ORDER BY `id` DESC LIMIT 1");
            $user = $res->fetch_assoc();
            $columns = 6;
            $rows = 7;
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns($columns)
                ->setButtonsGroupRows($rows)
                ->setButtons(showOrders($user, $columns, $rows))
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Зaклaдки|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Зaклaдки"');
            $columns = 6;
            $rows = 7;
            $carousel = showBookmarks($receiverId, $columns, $rows);
            foreach ($carousel as $cards)
                $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                    ->setSender($botSender)
                    ->setReceiver($receiverId)
                    ->setButtonsGroupColumns($columns)
                    ->setButtonsGroupRows($rows)
                    ->setButtons($cards)
                    ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
                );

        })

        ->onText('|Maгaзин|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Maгaзин"');
            $columns = 6;
            $rows = 6;
            $cards = [
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('open-url')
                    ->setActionBody('https://voron.ua')
                    ->setSilent(true)
                    ->setText('Beб-caйт')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#adffad')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Haш aдpec')
                    ->setSilent(true)
                    ->setText('Haш aдpec')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#e0ffff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Oпиcaниe пpoeздa')
                    ->setSilent(true)
                    ->setText('Oпиcaниe пpoeздa')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#fffacd')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Гpaфик paбoты')
                    ->setSilent(true)
                    ->setText('Гpaфик paбoты')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#60e0ff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Teлeфoн Kиeвcтap')
                    ->setSilent(true)
                    ->setText('Teлeфoн Kиeвcтap')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#ffc0c0')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Teлeфoн Vodafone')
                    ->setSilent(true)
                    ->setText('Teлeфoн Vodafone')
                    ->setTextHAlign('left'),
            ];
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns($columns)
                ->setButtonsGroupRows($rows)
                ->setButtons($cards)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );

        })

        ->onText('|Cпpaвкa|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Cпpaвкa"');
            $columns = 6;
            $rows = 7;
            $cards = [
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#faebd7')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Как заказать?')
                    ->setSilent(true)
                    ->setText('Как заказать?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#adffad')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Как оплатить?')
                    ->setSilent(true)
                    ->setText('Как оплатить?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#fffacd')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Как изменить доставку?')
                    ->setSilent(true)
                    ->setText('Как изменить доставку?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#e0ffff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Как изменить заказ?')
                    ->setSilent(true)
                    ->setText('Как изменить заказ?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#e6e6fa')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Что с моим заказом?')
                    ->setSilent(true)
                    ->setText('Что с моим заказом?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#f0fff0')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Поступила ли оплата?')
                    ->setSilent(true)
                    ->setText('Поступила ли оплата?')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#b0e0ff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('чат-бот')
                    ->setSilent(true)
                    ->setText('Что умеет этот чат-бот?')
                    ->setTextHAlign('left'),
            ];
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns($columns)
                ->setButtonsGroupRows($rows)
                ->setButtons($cards)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );

        })

        ->onText('|заказать|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "заказать"');

            $text = 'ЗАКАЗАТЬ ТОВАР можно следующими способами:

1. Найти интересующий товар прямо здесь:
- нажмите кнопку "Поиск", затем введите название или краткое описание товара;
- добавьте интересующий Вас товар в "Корзину", нажав кнопку внизу карточки с нужным количеством товара.

Обратите внимание на то, что есть 3 кнопки добавления товара с количеством и ценами за розничное, мелко-оптовое и оптовое количество товара - цена единицы товара будет зависеть не от того, какая кнопка была нажата, а от количества товара в корзине;

Количество товара можно увеличить повторным нажатием на кнопку в карточке найденного товара или изменить в карточке товара в корзине.

2. Оформить заказ через интернет-магазин https://voron.ua/ , возпользовавшись каталогом товаров или поиском;

3. Непосредственно в нашем магазине.

ДОСТАВКА.

1. КУРЬЕРСКИЕ СЛУЖБЫ. Посылки отправляются в течении 1-2 рабочих дней с момента получения оплаты. Мы работаем со службами:

Нова пошта - посылка доставляется курьером по указанному Вами адресу (домой или в офис) в течении 24-48 часов.
Подробнее на сайте:
http://www.novaposhta.com.ua/

Justin - доставка в отделения в сетях супермаркетов Сільпо, Fozzy, Фора и др. Время работы с 8.00 до 20.00 7 дней в неделю.
Подробнее на сайте: http://justin.ua/

2. УКРПОШТА (посылки по Украине доставляются в среднем за 5-7 дней. Обязательно сообщайте свой почтовый индекс. Если Ваш населенный пункт не является областным или районным центром - обязательно укажите район).
Подробнее на сайте:
http://www.ukrposhta.com

3. САМОВЫВОЗ станет снова доступен после окончания карантина. Вы забираете свой заказ непосредственно в нашем магазине. В городе Днепре Вы можете получить свой заказ в офисе по адресу: г.Днепр, ул. Новокрымская 58 (на углу пересечения с ул. Матросская).

ДЛЯ ОФОРМЛЕНИЯ ЗАКАЗА ОТКРОЙТЕ КОРЗИНУ (здесь или на сайте
https://voron.ua/basket.php ).

В вашей корзине Вы увидите перечень выбранного для покупки товара. Вы можете редактировать уже выбранный в корзину товар либо продолжать покупки далее.

Для переходу к оформлению заказа, убедитесь, что Вы выбрали способ доставки и указали адрес и реквизиты получателя. Фамилию, Имя, Отчество получателя указывайте полностью. Эту информацию можно внести или изменить только в корзине на сайте.

Там же, в корзине на сайте, в графе "Комментарии" вы можете указать дополнительный требования к отправке заказа либо интересующие Вас позиции, которых нет в наличии.

Если доставка указана и выполнены минимальные условия для заказа, для подтверждения заказа кликните по кнопке "оформить заказ" в корзине вайбера или "Заказать" на сайте для подтверждения заказа.

Мы будем информировать Вас о ходе выполнения заказа.

Приятных покупок!';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|оплатить|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "оплатить"');

            $text = 'Оплатить заказ можно любым из нижеуказанных способов:

ДЛЯ ФИЗИЧЕСКИХ ЛИЦ:';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
            );
            $columns = 6;
            $rows = 4;
            $cards = [
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#ffddff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('наложенный платеж')
                    ->setSilent(true)
                    ->setText('наложенный платеж,')
                    //->setTextSize('medium')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#eeeeff')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('в отделении банка')
                    ->setSilent(true)
                    ->setText('в отделении банка,')
                    //->setTextSize('medium')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#eeffee')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('Приват24')
                    ->setSilent(true)
                    ->setText('Приват24,')
                    //->setTextSize('medium')
                    ->setTextHAlign('left'),
                (new \Viber\Api\Keyboard\Button())
                    ->setColumns($columns)
                    ->setRows(1)
                    ->setBgColor('#ffeeee')
                    ->setTextSize('large')
                    ->setActionType('reply')
                    ->setActionBody('платежный терминал')
                    ->setSilent(true)
                    ->setText('платежный терминал,')
                    //->setTextSize('medium')
                    ->setTextHAlign('left'),
            ];
            $bot->getClient()->sendMessage((new \Viber\Api\Message\CarouselContent())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setButtonsGroupColumns($columns)
                ->setButtonsGroupRows($rows)
                ->setButtons($cards)
            );

            $text = 'а также наличными в нашем магазине.


ДЛЯ ЮРИДИЧЕСКИХ ЛИЦ:

Безналичная оплата (без НДС)

Наличными в нашем магазине

Каждый из способов гарантирует оперативную и надежную отправку платежа получателю. Пожалуйста, обратите внимание, что одновременное использование нескольких способов оплаты для одного заказа не допускается.

Внимание! Сохраняйте все платежные документы (чеки,квитанции, в том числе от терминалов самообслуживания), подтверждающие Вашу оплату, до момента получения товара. Если по каким либо причинам мы не получим оплату, то у Вас будет документ, по которому Вы сможете предъявить претензию нам или банку, в зависимости от сложившейся ситуации.';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|наложенный платеж|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "наложенный платеж"');

            $text = 'НАЛОЖЕННЫЙ ПЛАТЕЖ (ТОЛЬКО ДЛЯ ЧАСТНЫХ ЛИЦ)

Наложенный платеж, или оплата при получении, возможен для заказов на сумму 200 грн и более. Заказы на сумму менее 200 грн отгружаем только по предоплате. Наложенным платежом мы отправляем заказы через Нову Пошту. Другие перевозчики - только по предоплате.

Обращаем Ваше внимание, что стоимость такой доставки выше, так как кроме доставки товара, Вы оплачиваете и пересылку денег. Чтобы избежать недоразумений, внимательно ознакомьтесь с тарифами на сайте перевозчика.

Для того, чтобы забрать свой заказ, Вам необходимо иметь при себе документ, удостоверяющий личность — паспорт или водительское удостоверение, точную сумму стоимости заказа без сдачи (это важно!), сумму стоимости доставки (указывается в накладной) и стоимости пересылки денег (рассчитывается при оформлении получения Вами товара).

Чтобы проинформировать нас о своем желании получить заказ Наложенным платежом, необходимо в Корзине указать, что Оплата от Частного лица и поставить галочку возле поля Наложенный платеж.

Если в Вашей Корзине нет поля для выбора Наложенного платежа, Вам необходимо выбрать способ доставки Нова Пошта. Не забудьте указать номер или название удобного для Вас отделения (склада).

Для того, чтобы с нашей стороны не было задержек с отгрузкой заказа - проверьте, чтобы в Реквизитах были указаны следующие данные:

ФИО получателя полностью на русском или украинском языке (максимум два получателя)
Город получателя
Номер или название отделения (склада)
Мобильный телефон

Мы не отправляем заказы наложенным платежом в города, где есть наши магазины. Возможна отправка в эти города на выбранный Вами склад после поступления полной оплаты заказа. Как вариант, Вы можете сделать оплату в нашем магазине.';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|в отделении банка|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "в отделении банка"');

            $text = 'ОПЛАТА В ОТДЕЛЕНИИ БАНКА

После обработки заказа Вам на e-mail, указанном при регистрации, придет письмо со счетом. Вы можете оплатить его в любом коммерческом банке. Для этого необходимо распечатать счет. Внимательно проверьте распечатку, чтобы не были обрезаны поля.

Если у Вас нет возможности сделать распечатку, то перепишите со счета следующую информацию: 

Поставщик
Код ЕГРПОУ(ОКПО)
Расчетный счет IBAN-код (29 символов и начинается с UA)
Название банка
Номер счета и дату
Сумму счета (Всего)
Назначение платежа

Отдайте кассиру счет, дальше он сам все сделает.
В счете есть вся необходимая информация (включая назначение платежа), которая необходима кассиру для перевода денег. Обратите внимание, что в назначении платежа должно быть указано “За товар согласно счета №...”. Все платежи с другими назначениями (“Поступление торговой выручки…”, “За услуги…” и т.п.) будут возвращены банку-отправителю. Если у Вас или кассира возникнут вопросы по оплате - в письме со счетом указаны контактные данные для этих вопросов.
Комиссия за перевод денег оплачивается отдельно. Сумму комиссии уточняйте у кассира.
Обязательно сохраняйте квитанцию до получения посылки.

Сроки поступления оплаты (валютирования): 

ПриватБанк -- до 15-00 -- 2 часа, после 15-00 -- следующий банковский день.
Другие банки 1-2 банковских дня
ОщадБанк - до 3-х банковских дней
(Мы указали ориентировочные сроки, При необходимости уточняйте у кассира или смотрите в квитанции строку “Дата валютирования” - это дата, когда к нам будут зачислена Ваша оплата. В праздничные и выходные дни банки могут принимать платежи, но мы получим их только в первый рабочий день.)

Ближайшее отделение ПриватБанка удобно искать при помощи их сервиса http://privatbank.ua/map/';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|Приват24|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "Приват24"');

            $text = 'ОПЛАТА ЧЕРЕЗ ПРИВАТ24

После обработки заказа, Вам на e-mail, указанном при регистрации, придет письмо со счетом. В нём есть вся необходимая информация для оплаты через ПРИВАТ24.
Зайдя в свой аккаунт ПРИВАТ24, выбираем вкладку «Все услуги > «Платежи» > «По Украине».

В появившемся окне заполняем форму. Данные для заполнения полей берем из счета.

«ЄДРПОУ(ОКПО)» - Код ЄДРПОУ(ОКПО) указан в счете
«Получатель» - ФИО предпринимателя указанного в счете.
«Номер счета получателя» - Вводим сюда номер счета в формате IBAN-код (29 символов и начинатися с UA). Обратите внимание, что номер вводится без пробелов.
«Назначение платежа» - Назначение платежа, как оно указано счете.
«Сумма» - Указываем сумму платежа.
После нажатия кнопки «Добавить в корзину» необходимо подтвердить платеж в вашей корзине укозав с какого счета будет списан баланс. 

Нажимаем кнопку «Подтвердить», и вводим код пришедший к Вам на мобильный телефон в виде SMS. Все, платеж совершен!
Внутрибанковские платежи, по утверждению банка, происходят в течение нескольких минут.

Возможны некоторые отличия в отображении страниц, связанные с постоянными изменениями дизайна Приват24.';

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text)
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

        ->onText('|платежный терминал|s', function ($event) use ($bot, $botSender, $buttons, $log) {
            $receiverId = $event->getSender()->getId();
            $log->info($receiverId . ' click on "платежный терминал"');

            $text = [
'БАНКОВСКИЙ ТЕРМИНАЛ САМООБСЛУЖИВАНИЯ (ПЛАТЕЖНЫЙ ТЕРМИНАЛ)

Оплатить счет Вы можете через терминалы самообслуживания, которые стоят в отделениях Приватбанка и крупных торговых центрах. Обратите внимание, что это должен быть именно терминал Приватбанка, а не платежный терминал сторонних компаний. Его внешний вид:',

'Прежде чем идти платить, распечатайте счет. Или перепишите реквизиты. У вас должны быть следующие данные:

Номер расчётного счета
Ф.И.О. получателя
Номер счета
Дата выписки счета
Сумма платежа

Оплатить можно наличными или платежной картой. В последнее время терминалы Приватбанка требуют номер телефона или карту для авторизации платежа.',

'Вводим свой номер телефона или вставляем карту Приватбанка при её наличии.',

'В случае с телефоном Вам придет СМС с кодом, который нужно ввести в следующем окне.',

'После этого появится главное окно терминала. Выбираем пункт «Оплата других услуг» («Оплата інших послуг»).',

'Нас интересует «Найти предприятие по имени или реквизитам» («Знайти підприємсто за назвою або за реквізитами»).',

'В появившемся меню нажатием на соответствующее поле выбираем «Расчетный счет» («Розрахунковий рахунок») и вводим номер из счета. Это число, которое начинается с 2600.',

'В следующем появившемся меню будет показано найденное предприятие, на счет которого вы производите оплату. Если название предприятия и расчетный счет верные — подтвердите, нажав на эту кнопку.',

'Далее появится еще одна кнопка с надписью «Оплата за товар». Снова нажимаем на неё.',

'Теперь вводим данные платежа: 

Ваши Ф.И.О.
Номер счета
Дата выписки счета
Сумма платежа

(Учитывайте, что терминал принимает оплату без сдачи и многие не принимают купюры 1-2 грн. Если Вы вносите сумму большую, чем указана в счете, то остаток вы сможете использовать при следующем заказе.)',

'После заполнения всех полей нажмаете кнопку «Продолжить». Появляется окно с данными плательщика. Нажимаем эту кнопку.',

'В следующем окне проверяем все данные платежа. Если всё правильно, то нажимайте «Продолжить».',

'Заключительное окно – прием платежа. Вы можете оплатить картой или наличными. После внесения необходимой суммы нажимаем «Оплатить» и затем «Печатать чек».

Платежный терминал выдаст вам чек. Обязательно сохраняйте его! Ведь это документ подтверждающий факт совершенной оплаты.

Так же при оплате счетов через терминал или кассу банка, стоит учитывать понятие «банковское время». Это рабочее время до 16:00 в которое осуществляют переводы между счетами.

Если вы хотите оплатить позже 16:00, — это не означает, что у вас платеж не примут, его просто зачислят следующим рабочим днем. К примеру, если оплата произведена в субботу, ваш платеж будет зачислен в понедельник утром.

Информация по зачислению средств, есть на вашем чеке в виде «Дата Вал: хх.хх.».

Помните, что если вы не разобрались как пользоваться терминалом, вы всегда сможете обратится за помощью к консультанту (в крупных отделениях есть) или операционисту.'
            ];

            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[0])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal00.jpg')
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[1])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal01.jpg')
                ->setText($text[2])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal02.jpg')
                ->setText($text[3])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal03.jpg')
                ->setText($text[4])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal04.jpg')
                ->setText($text[5])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal05.jpg')
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[6])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal06.jpg')
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[7])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal07.jpg')
                ->setText($text[8])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal08.jpg')
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[9])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal09.jpg')
                ->setText($text[10])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal10.jpg')
                ->setText($text[11])
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Picture())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setMedia('https://voron.ua/img/terminal11.jpg')
            );
            $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($receiverId)
                ->setText($text[12])
                ->setKeyboard((new \Viber\Api\Keyboard())->setButtons($buttons))
            );
        })

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
