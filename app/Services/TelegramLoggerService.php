<?php

namespace App\Services;

use App\Jobs\LogToTelegramJob;

class TelegramLoggerService
{
    protected string $defaultChannel = 'telegram';
    
    /**
     * Отправка лога с разными уровнями
     */
    public function log(string $level, string $message, array $context = [], bool $sync = false): void
    {
        $job = new LogToTelegramJob($level, $message, $context, $this->defaultChannel);
        
        if ($sync || app()->environment('local', 'testing')) {
            // В локальном окружении или при синхронном вызове
            dispatch_sync($job);
        } else {
            // В продакшене асинхронно
            dispatch($job)->onQueue('logs');
        }
    }
    
    /**
     * Быстрые методы для разных уровней логирования
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }
    
    public function alert(string $message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }
    
    public function critical(string $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }
    
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }
    
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }
    
    public function notice(string $message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }
    
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }
    
    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }
    
    /**
     * Логирование исключений
     */
    public function exception(\Throwable $exception, string $level = 'error', array $additionalContext = []): void
    {
        $context = array_merge([
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ], $additionalContext);
        
        $this->log($level, "Exception: " . $exception->getMessage(), $context);
    }
    
    /**
     * Логирование действий пользователя
     */
    public function userAction(int $userId, string $action, array $details = []): void
    {
        $context = array_merge([
            'user_id' => $userId,
            'action' => $action,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ], $details);
        
        $this->info("User action: {$action}", $context);
    }
    
    /**
     * Логирование бизнес-событий
     */
    public function businessEvent(string $event, array $data = []): void
    {
        $context = array_merge([
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'environment' => app()->environment(),
        ], $data);
        
        $this->info("Business event: {$event}", $context);
    }
}