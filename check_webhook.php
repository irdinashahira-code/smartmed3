<?php
require 'vendor/autoload.php';

use Telegram\Bot\Api;

$telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
$response = $telegram->getWebhookInfo();

dd($response);
