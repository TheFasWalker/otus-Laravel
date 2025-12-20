<?php

namespace App\Logging;

use Monolog\Logger;

class CreateTelegramLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('telegram');
        $logger->pushHandler(new TelegramLogger(config('telegram-logger')));

        return $logger;
    }
}