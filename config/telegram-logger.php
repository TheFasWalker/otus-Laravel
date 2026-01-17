<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    'log_level' => env('TELEGRAM_LOG_LEVEL', 'warning'), 
    'log_exceptions' => env('TELEGRAM_LOG_EXCEPTIONS', true),
    'project_name' => env('APP_NAME', 'Laravel'),
    'environment' => env('APP_ENV', 'production'),
];