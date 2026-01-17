<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use TelegramBot\Api\BotApi;

class TelegramLogger extends AbstractProcessingHandler
{
    private $botToken;
    private $chatId;
    private $projectName;
    private $environment;
    private BotApi $bot;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['log_level']);
        
        parent::__construct($level, true);

        $this->botToken = $config['bot_token'];
        $this->chatId = $config['chat_id'];
        $this->projectName = $config['project_name'];
        $this->environment = $config['environment'];
        $this->initializeBot();
    }

    private function initializeBot(): void
    {
        $this->bot = new BotApi($this->botToken);
    }

    protected function write(LogRecord $record): void
    {
        try {
            $message = $this->formatMessage($record);
            
            $this->bot->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );
        } catch (\Exception $e) {
            if (function_exists('error_log')) {
                error_log('Telegram logger failed: ' . $e->getMessage());
            }
        }
    }

    private function formatMessage(LogRecord $record): string
    {
        $level = strtoupper($record->level->getName());
        $message = $record->message;
        $context = !empty($record->context) ? json_encode($record->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $datetime = $record->datetime->format('Y-m-d H:i:s');
        $path = 'UNKNOWN';
        try {
            if (!app()->runningInConsole() && request() && request()->url()) {
                $path = parse_url(request()->url(), PHP_URL_PATH) ?? 'WEB';
            } elseif (app()->runningInConsole()) {
                $path = 'CONSOLE';
                if (isset($_SERVER['argv']) && count($_SERVER['argv']) > 1) {
                    $command = implode(' ', array_slice($_SERVER['argv'], 1));
                    $path .= ": " . $command;
                }
            }
        } catch (\Throwable $e) {
            $path = 'ERROR_GETTING_PATH';
        }
        return sprintf(
            "🚨 <b>%s</b> | %s\n" .
            "📱 <b>Проект:</b> %s\n" .
            "🕒 <b>Время:</b> %s\n" .
            "📊 <b>Уровень:</b> %s\n" .
            "📝 <b>Сообщение:</b> %s\n" .
            "%s",
            $level,
            parse_url(request()->url(), PHP_URL_PATH) ?? 'CLI',
            $this->projectName,
            $datetime,
            $level,
            htmlspecialchars($message),
            $context ? "<code>" . htmlspecialchars($context) . "</code>" : ''
        );
    }
}