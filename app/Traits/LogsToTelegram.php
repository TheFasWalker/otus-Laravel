<?php

namespace App\Traits;

use App\Services\TelegramLoggerService;

trait LogsToTelegram
{
    protected ?TelegramLoggerService $telegramLogger = null;
    
    /**
     * Получить экземпляр логгера
     */
    protected function telegramLogger(): TelegramLoggerService
    {
        if (!$this->telegramLogger) {
            $this->telegramLogger = app(TelegramLoggerService::class);
        }
        
        return $this->telegramLogger;
    }
    
    /**
     * Быстрые методы через трейт
     */
    protected function logEmergency(string $message, array $context = []): void
    {
        $this->telegramLogger()->emergency($message, $context);
    }
    
    protected function logError(string $message, array $context = []): void
    {
        $this->telegramLogger()->error($message, $context);
    }
    
    protected function logWarning(string $message, array $context = []): void
    {
        $this->telegramLogger()->warning($message, $context);
    }
    
    protected function logInfo(string $message, array $context = []): void
    {
        $this->telegramLogger()->info($message, $context);
    }
    
    protected function logException(\Throwable $exception, string $level = 'error', array $additionalContext = []): void
    {
        $this->telegramLogger()->exception($exception, $level, $additionalContext);
    }
}