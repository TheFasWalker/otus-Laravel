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

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['log_level']);
        
        parent::__construct($level, true);

        $this->botToken = $config['bot_token'];
        $this->chatId = $config['chat_id'];
        $this->projectName = $config['project_name'];
        $this->environment = $config['environment'];
    }

    protected function write(LogRecord $record): void
    {
        try {
            $bot = new BotApi($this->botToken);
            
            $message = $this->formatMessage($record);
            
            $bot->sendMessage(
                $this->chatId,
                $message,
                'HTML'
            );
        } catch (\Exception $e) {
            Log::error('Telegram logger failed: ' . $e->getMessage());
        }
    }

    private function formatMessage(LogRecord $record): string
    {
        $level = strtoupper($record->level->getName());
        $message = $record->message;
        $context = !empty($record->context) ? json_encode($record->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '';
        $datetime = $record->datetime->format('Y-m-d H:i:s');
        
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