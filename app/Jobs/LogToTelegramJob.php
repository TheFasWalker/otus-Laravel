<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogToTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 30;
    public $tries = 3;
    
    protected string $level;
    protected string $message;
    protected array $context;
    protected string $channel;

    /**
     * Create a new job instance.
     */
 public function __construct(
        string $level,
        string $message,
        array $context = [],
        string $channel = 'telegram'  
    ) {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $enhancedContext = array_merge($this->context, [
                'timestamp' => now()->toISOString(),
            ]);

            Log::channel($this->channel)->log($this->level, $this->message, $enhancedContext);
            
        } catch (\Exception $e) {
            Log::error('Failed to send log to Telegram', [
                'original_message' => $this->message,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('LogToTelegramJob failed', [
            'message' => $this->message,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}