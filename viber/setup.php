<?php
chdir('/home/fp');
require_once 'data/config.inc.php';
require_once 'vendor/autoload.php';

use Viber\Client;

try {
    $client = new Client(['token' => $viber_api_key]);
    $result = $client->setWebhook('https://fprognoz.org/viber/bot.php');
    echo "Success!\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
